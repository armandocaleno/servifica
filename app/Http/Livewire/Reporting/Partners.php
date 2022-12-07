<?php

namespace App\Http\Livewire\Reporting;

use App\Models\Partner;
use Livewire\Component;
use Livewire\WithPagination;

class Partners extends Component
{
    use WithPagination;

    public function render()
    {            
        $partners = Partner::whereStatus(Partner::ACTIVO)->whereCompanyId(session('company')->id)->paginate(10);

        return view('livewire.reporting.partners', compact('partners'));
    }
}
