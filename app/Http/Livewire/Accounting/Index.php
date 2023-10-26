<?php

namespace App\Http\Livewire\Accounting;

use App\Models\Accounting;
use Livewire\Component;

class Index extends Component
{
    public $search;

    // protected $listeners = ['searching'];

    public function render()
    {
        $accountings = Accounting::where('company_id', session('company')->id)
                        ->where(function ($query) {
                            $query->where('code', 'LIKE', $this->search . '%')
                            ->orWhere('name', 'LIKE', '%' . $this->search . '%');
                        })
                        ->with('class')
                        ->with('type')
                        ->orderBy('account_class_id')
                        ->orderBy('code')->get();

                        // dd($accountings);

        return view('livewire.accounting.index', compact('accountings'));
    }

    public function updatedSearch()
    {
        $this->emit('searching');
    }

    public function destroy(Accounting $accounting)
    {
        $accounting->delete();
        $this->render();
    }
}
