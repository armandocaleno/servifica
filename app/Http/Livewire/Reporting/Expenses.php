<?php

namespace App\Http\Livewire\Reporting;

use App\Models\Expense;
use App\Models\Supplier;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Expenses extends Component
{
    use WithPagination;

    public $from_date, $to_date, $supplier_id;

    public function mount()
    {                      
        $this->from_date = Carbon::now()->format('Y-m-d');
        $this->to_date = Carbon::now()->format('Y-m-d');  
        $this->supplier_id = "";
    }

    public function render()
    {
        if ($this->supplier_id == "") {
            $expenses = Expense::where('company_id', session('company')->id)
                                ->whereBetween('date', [$this->from_date, $this->to_date])
                                ->paginate(10);
        }else {
            $expenses = Expense::where('company_id', session('company')->id)
                                ->whereBetween('date', [$this->from_date, $this->to_date])
                                ->where('supplier_id', $this->supplier_id)->paginate(10);
        }
       
        $suppliers = Supplier::where('company_id', session('company')->id)->get();
       
        return view('livewire.reporting.expenses', compact('expenses', 'suppliers'));
    }
}
