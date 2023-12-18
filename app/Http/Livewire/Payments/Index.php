<?php

namespace App\Http\Livewire\Payments;

use App\Models\Check;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\JournalDetail;
use App\Models\Journal;
use Livewire\WithPagination;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentMethods;
use Livewire\Component;

class Index extends Component
{
    use WithPagination;

    public $search, $sort, $direction;
    public $confirmingDeletion;
    public $payment;

    public function mount()
    {
        $this->sort = "id";
        $this->direction = "desc";
        $this->search = "";
        $this->confirmingDeletion = false;
    }

    public function render()
    {
        $payments = Payment::join('bank_accounts', 'bank_accounts.id', 'bank_account_id')
            ->where('bank_accounts.company_id', session('company')->id)
            ->where(function ($q) {
                $q->where('payments.number', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('beneficiary', 'LIKE', '%' . $this->search . '%')
                    ->orwhere('payments.reference', 'LIKE', '%' . $this->search . '%');
            })
            ->orderBy($this->sort, $this->direction)
            ->select('payments.*')->paginate(10);

        return view('livewire.payments.index', compact('payments'));
    }

    public function order($sort)
    {
        if ($this->sort == $sort) {
            if ($this->direction == 'desc') {
                $this->direction = 'asc';
            } else {
                $this->direction = 'desc';
            }
        } else {
            $this->sort = $sort;
            $this->direction = 'desc';
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(Payment $payment)
    {
        $this->payment = $payment;
        $this->confirmingDeletion = true;
    }

    function destroy()
    {
        DB::beginTransaction();

        try {

            //Crea el asiento reverso de anulacion
            $journal = $this->payment->journal;

            $debit_account =  "";
            $credit_account = "";

            if ($journal != null) {
                foreach ($journal->details as $detail) {
                    if ($detail->debit_value > 0) {
                        $debit_account = $detail->accounting;
                    } else {
                        $credit_account = $detail->accounting;
                    }
                }
            }

            $newjournal = Journal::create([
                'number' => $this->GenjournalNumber(),
                'date' => Carbon::now(),
                'refence' => "Anulación - Pago: " .  $this->payment->reference . " - Beneficiario: " . $this->payment->beneficiary,
                'company_id' => session('company')->id,
                'type' => Journal::AUTO,
                'journable_id' => $this->payment->id,
                'journable_type' => Payment::class
            ]);

            JournalDetail::create([
                'journal_id' => $newjournal->id,
                'accounting_id' => $credit_account->id,
                'debit_value' => $this->payment->total,
                'credit_value' => 0
            ]);

            JournalDetail::create([
                'journal_id' => $newjournal->id,
                'accounting_id' => $debit_account->id,
                'debit_value' => 0,
                'credit_value' => $this->payment->total
            ]);

            $payment_method = PaymentMethod::find($this->payment->payment_method_id);

            if ($payment_method->name == "Cheque") {
                $check = Check::where('number', $this->payment->number)->where('bank_account_id', $this->payment->bank_account_id)->first();
                
                if ($check) {
                    // $check->state = '0';
                    // $check->save();
                    $check->update(['state' => '0']); //Actualiza el estado del cheque
                }
            }

            $this->payment->delete();

            $this->success('Pago eliminado correctamente.');

            DB::commit();

            $this->confirmingDeletion = false;
        } catch (\Throwable $th) {
            DB::rollback();
            $this->info('Hubo un error y no se eliminó el pago.');
            $this->info($th->getMessage());
        }
    }

    //Generar el número de asiento
    public function GenjournalNumber()
    {
        $j = new Journal();
        $number = $j->getNumber();
        return $number;
    }

    // Mensaje de información
    public function info($message)
    {
        $this->dispatchBrowserEvent(
            'info',
            [
                'message' => $message,
            ]
        );
    }

    // Mensaje de confirmación de acción
    public function success($message)
    {
        $this->dispatchBrowserEvent(
            'success',
            [
                'message' => $message,
            ]
        );
    }
}
