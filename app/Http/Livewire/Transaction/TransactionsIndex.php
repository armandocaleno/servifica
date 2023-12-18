<?php

namespace App\Http\Livewire\Transaction;

use App\Models\Account;
use App\Models\AccountingConfig;
use App\Models\Journal;
use App\Models\JournalDetail;
use App\Models\Partner;
use App\Models\Transaction;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use PDF;
use App\Mail\VoucherMail;
use Illuminate\Support\Facades\Mail;

class TransactionsIndex extends Component
{
    use WithPagination;

    public $search, $sort, $direction, $transaction, $detail, $aditional_info;
    public $openDetailModal, $confirmingDeletion;
    public $partner;

    public function mount()
    {
        $this->transaction = [];
        $this->detail = [];               
        $this->sort = "transactions.id";
        $this->direction = "desc";
        $this->search = "";
        $this->openDetailModal = false;
        $this->confirmingDeletion = false;

        Cart::instance('old')->destroy();
        Cart::instance('new')->destroy();
    }

    public function render()
    {                                                
        $transactions = Transaction::join('partners', 'partners.id', 'transactions.partner_id')
        ->select(['transactions.*', 'partners.name'])
        ->where('transactions.company_id', session('company')->id)
        ->where(function($q){
            $q->where('number', 'LIKE', '%' . $this->search . '%') 
            ->orWhere('name', 'LIKE', '%' . $this->search . '%')       
            ->orwhere('reference', 'LIKE', '%' . $this->search . '%') ;                        
        })
        ->orderBy($this->sort, $this->direction)->paginate(10);                      
        
        return view('livewire.transaction.transactions-index', compact('transactions'));
    }

    public function order($sort){
        if ($this->sort == $sort) {
            if ($this->direction == 'desc') {
                $this->direction = 'asc';
            } else {
                $this->direction = 'desc';
            }
            
        }else{
            $this->sort = $sort;
            $this->direction = 'desc';
        }        
    }

    public function updatingSearch()
    {
        $this->resetPage();         
    }

    public function showDetail(Transaction $transaction)
    {                                
        $this->detail = $transaction->content; 
        $this->aditional_info = $transaction->aditional_info; 
        
        if ($this->detail) {
            sort($this->detail, SORT_NUMERIC); //se ordena el array por el id
        }
        
        $this->openDetailModal = true;                
    }

    public function delete(Transaction $transaction)
    {   
        $this->transaction = $transaction;   
        $this->confirmingDeletion = true;     
    }

    public function destroy()
    {                       
        DB::beginTransaction();

        try {
            //actualiza los saldos de la cuentas de la transacción a eliminar
            $this->totalUpdate($this->transaction);

            // CREA ASIENTO CONTABLE DE REVERSO

            // OBTENER NUMERO DE ASIENTO
            $j = new Journal();
            $number = $j->getNumber();

            // CUENTA DE SOCIOS
            $account_cash = AccountingConfig::where('name', 'cash')->first()->accounting_id;

            $fecha_actual = date("Y-m-d");

            //COMPROBAR SI LA TRANSACCION TIENE ASIENTO CONTABLE, SINO SE COLOCA UNA NOTA EN LA GLOSA DEL ASIENTO DE REVERSO
            $note = "";

            foreach ($this->transaction->content as $item) {
                if (!isset($item['options']['accounting_id'])) {
                    $note = "(Transaction sin asiento contable)";
                    break;
                }
            }
    
            $journal = Journal::create([
                'number' => $number,
                'date' => $fecha_actual,
                'refence' => "Anulación Transacción: " .  $this->transaction->number . " - Socio: " . $this->transaction->partner->name . " " . $note,
                'company_id' => session('company')->id,
                'type' => Journal::AUTO,
                'journable_id' => $this->transaction->id,
                'journable_type' => Transaction::class
            ]);

            JournalDetail::create([
                'journal_id' => $journal->id,
                'accounting_id' => $account_cash,
                'debit_value' => 0,
                'credit_value' => $this->transaction->total
            ]);

            foreach ($this->transaction->content as $item) {
                
                $accounting_id = Account::find($item['id'])->accounting_id;
               
                JournalDetail::create([
                    'journal_id' => $journal->id,
                    'accounting_id' => $accounting_id,
                    'debit_value' => $item['price'],
                    'credit_value' => 0
                ]);
            }

            //elimina la transacción
            $this->transaction->delete();

            DB::commit();
              //cierra el modal
            $this->confirmingDeletion = false;

            // Mensaje enviado a la vista
            $this->success('Registro eliminado correctamente.');

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            $this->info('Hubo un error y no se eliminó la transacción.');
            $this->info($th->getMessage());
        }
    }    

    public function totalUpdate(Transaction $transaction)
    {                   
        //setea el contenido de la transacción
        $content = $transaction->content;
    
        //Obtiene el socio de la transacción
        $partner = Partner::find($transaction->partner_id);  
                        
        foreach ($content as $value) {                                                    
            //setea el saldo de la cuenta
            $total = $value['price'];                                         
            
            //Consulta si el socio ya tiene la cuenta ingresada.
            $account_partner = $partner->accounts()->find($value['id']);            

            //consulta la cuenta asociada
            $account = Account::find($value['id']);
            //setea el valor de cuenta existente
            $amount = $account_partner->pivot->value;

            $newtotal = $amount + $total ;
            if ($transaction->type == Transaction::LOTES || $account->type == Account::EGRESO) {
                $newtotal = $amount - $total;
            }
            
            //si ya existe actualiza la tabla relacionada con el nuevo saldo.
            if ($account_partner) {                                               
                $partner->accounts()->updateExistingPivot($account_partner->id, ['value' => $newtotal]);
            }                     
        }                                        
    }

    public function generateVoucherEmail($id)
    {
        $transaction = Transaction::find($id); //obtiene la transacción 
    
        Arr::sort($transaction->voucher);
        $transaction->date = Carbon::parse($transaction->date)->format('d/m/Y'); //formatea la fecha       
        $voucher_path = '/app/Pago_'. $transaction->number . '.pdf';

        //genera el pdf con el contenido de la transacción
        PDF::loadView('transactions.voucher', ['transaction' => $transaction])->save(storage_path(). $voucher_path);

        $this->emailSend($transaction, $voucher_path);
    }

    function emailSend(Transaction $transaction, $path) {
        $company = session('company');
        if ($transaction->partner->email === null || $transaction->partner->email === '') {
            $this->info('No es posible enviar email. Email no válido.');
            return;
        } else {
            if (Mail::to($transaction->partner->email)->send(new VoucherMail($transaction, $company))) {
                $this->success('Email enviado!');
            } else {
                $this->info('No se pudo enviar el email.');
            }
        }
        
        // eliminar archivo generado
        if (file_exists(storage_path().$path)) {
            unlink(storage_path().$path);
        }
    }

     // Mensaje de confirmación de acción
     public function success($message)
     {
         $this->dispatchBrowserEvent('success', 
             [
                 'message' => $message,                       
             ]
         );       
     }

     // Mensaje de información
    public function info($message)
    {
        $this->dispatchBrowserEvent('info', 
            [
                'message' => $message,                       
            ]
        );       
    }
}
