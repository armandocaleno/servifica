<?php

namespace App\Http\Livewire\Journals;

use App\Models\Accounting;
use App\Models\Journal;
use App\Models\JournalDetail;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class Create extends Component
{
    public $journal, $accounting, $accounting_id, $value, $number, $type, $total_debe, $total_haber;

    protected $rules = [
        'journal.date' => 'required|date',
        'journal.number' => 'required|max:15',
        'journal.refence' => 'required|max:255'
    ];

    public function mount(Journal $journal = null)
    {                      
        $this->journal = $journal;
        $this->accounting_id = 0;
        $this->value = "0.00";
        $this->type = 'debe';
        $this->total_debe = "0.00";
        $this->total_haber = "0.00";
       
        Cart::instance('old_journal')->destroy();
        Cart::instance('new_journal')->destroy();

        if ($this->journal->id) {
            $this->journal->date = Carbon::parse($this->journal->date)->format('Y-m-d');

            foreach ($this->journal->details as $value) {   
                $accounting = Accounting::find($value['accounting_id']);  

                if ($value['debit_value'] > 0) {
       

                    Cart::instance('old_journal')->add([
                        'id' => $accounting->id,
                        'name' => $accounting->code . ' ' . $accounting->name,
                        'qty' => 1,
                        'price' => $value['debit_value'],
                        'weight' => 100,
                        'options' => ['type' => 'debe']
                    ]); 

                    Cart::instance('new_journal')->add([
                        'id' => $accounting->id,
                        'name' => $accounting->code . ' ' . $accounting->name,
                        'qty' => 1,
                        'price' => $value['debit_value'],
                        'weight' => 100,
                        'options' => ['type' => 'debe']
                    ]); 

                    $this->total_debe = $this->total_debe + $value['debit_value'];

                } else {
                    Cart::instance('old_journal')->add([
                        'id' => $accounting->id,
                        'name' => $accounting->code . ' ' . $accounting->name,
                        'qty' => 1,
                        'price' => $value['credit_value'],
                        'weight' => 100,
                        'options' => ['type' => 'haber']
                    ]); 

                    Cart::instance('new_journal')->add([
                        'id' => $accounting->id,
                        'name' => $accounting->code . ' ' . $accounting->name,
                        'qty' => 1,
                        'price' => $value['credit_value'],
                        'weight' => 100,
                        'options' => ['type' => 'haber']
                    ]); 

                    $this->total_haber = $this->total_haber + $value['credit_value'];
                }
            }       
        } else {
            $this->journal = new Journal();
            $this->journal->date = Carbon::now()->format('Y-m-d');
            $this->journal->total = Cart::instance('new_journal')->subtotal();
            $this->journal->number = $this->GenjournalNumber();  
            $this->journal->type = journal::MANUAL;          
        }        
    }
    
    public function render()
    {
        $accountings = Accounting::where('company_id', session('company')->id)
                                    ->where('group', '0')
                                    ->orderBy('account_class_id')
                                    ->orderBy('code')
                                    ->get();

        return view('livewire.journals.create', compact('accountings'));
    }

    public function addAccount()
    {
        if ($this->value == 0) {
            $this->info('Monto incorrecto!');    
            return;
        }

        if ($this->accounting_id < 1) {
            $this->info('Seleccione una cuenta primero.');    
            return;
        }       
        
        $accounting = Accounting::find($this->accounting_id);

        Cart::instance('new_journal')->add([
            'id' => $accounting->id,
            'name' =>$accounting->code . " " . $accounting->name,
            'qty' => 1,
            'price' => $this->value,
            'weight' => 100,
            'options' => ['type' => $this->type]
        ]);                               
        
        if ($this->type == 'debe') {
            $this->total_debe = $this->total_debe + floatVal($this->value);
        } else {
            $this->total_haber = $this->total_haber + floatVal($this->value);
        }

        $this->value = "0.00";     
    }  

    public function unsetAccount($rowID)
    {   
        $item = Cart::instance('new_journal')->get($rowID);

        if ($item->options->type == 'debe') {
            $this->total_debe = $this->total_debe - $item->price;
        }else{
            $this->total_haber = $this->total_haber - $item->price;
        }

        Cart::instance('new_journal')->remove($rowID);
    }

    public function save()
    {
        if (strval(floatVal($this->total_debe)) != strval(floatVal($this->total_haber))) {
            $this->info('Los totales del debe y el haber no cuadran.'); 
            return;
        }

        $this->validate();

        if ($this->journal->id) {
            $this->journal->save();

            // ELIMINA LAS CUENTAS DEL DETALLE
            foreach ($this->journal->details as $value) {
                JournalDetail::find($value->id)->delete();
            }

            foreach (Cart::instance('new_journal')->content() as $item) {
                $debit = 0; $credit = 0; 
                if ($item->options->type == 'debe') {
                    $debit = $item->price;
                }else{
                    $credit= $item->price;
                }
    
                JournalDetail::create([
                    'journal_id' => $this->journal->id,
                    'accounting_id' => $item->id,
                    'debit_value' => $debit,
                    'credit_value' => $credit
                ]);
            }

            $this->success('Registro actualizado conrrectamente.');
        }else {
            $journal = Journal::create([
                'number' => $this->GenjournalNumber(),
                'date' => $this->journal->date,
                'refence' => $this->journal->refence,
                'company_id' => session('company')->id,
                'type' => Journal::MANUAL,
                'journable_id' => 1,
                'journable_type' => Journal::class
            ]);
    
            foreach (Cart::instance('new_journal')->content() as $item) {
                $debit = 0; $credit = 0; 
                if ($item->options->type == 'debe') {
                    $debit = $item->price;
                }else{
                    $credit= $item->price;
                }
    
                JournalDetail::create([
                    'journal_id' => $journal->id,
                    'accounting_id' => $item->id,
                    'debit_value' => $debit,
                    'credit_value' => $credit
                ]);
            }

            $this->success('Registro creado conrrectamente.');
        }

        return redirect()->route('journals.index');
    }

    //Generar el número de transacción
    public function GenjournalNumber()
    {                
        $j = new Journal();
        $number = $j->getNumber();
        return $number;
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
