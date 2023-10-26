<?php

namespace App\Http\Livewire\Accounting;
use App\Models\AccountClass;
use App\Models\Accounting;
use App\Models\AccountSubclass;
use App\Models\AccountType;
use App\Models\JournalDetail;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    public $accounting, $account_class_id, $code, $name, $group, $account_type_id, $parent_id;
    public $accounting_parents, $global_level, $accounting_subclasses;

    protected $rules = [
        'accounting.code' => 'required',
        'accounting.name' => 'required',
        'accounting.group' => 'required',
        'accounting.level' => 'required|integer',
        'accounting.account_type_id' => 'required',
        'accounting.account_class_id' => 'required',
        'accounting.account_subclass_id' => 'nullable',
        'accounting.parent_id' => 'nullable',
    ];

    public function mount(Accounting $accounting, $global_level)
    {
        $this->accounting = $accounting;
        $this->global_level = $global_level;
     
        if ($this->accounting->id == null) {
            $this->accounting->account_type_id = AccountType::first()->id;
            $this->accounting->account_class_id = AccountClass::first()->id;
            $this->setAccountCode($this->accounting->parent_id);
            $this->accounting->group = "0";
            $this->accounting->level = 1;    
        
        }
        $this->accounting_subclasses = AccountSubclass::where('account_class_id', $this->accounting->account_class_id )->get();

        $this->accounting_parents = Accounting::where('group', 1)->where('account_class_id', $this->accounting->account_class_id)
        ->orderBy('account_class_id')->get();
    }

    public function render()
    {
        $accounting_types = AccountType::all();
        $accounting_class = AccountClass::all();
    
        return view('livewire.accounting.create', compact('accounting_class', 'accounting_types'));
    }

    public function store()
    {
        //ESTABLECE EL NIVEL DE LA CUENTA
        $parent = Accounting::find($this->accounting->parent_id);

        if ($parent) {
            if ($parent->level) {
                $this->accounting->level = $parent->level + 1;
            } else {
                $this->accounting->level = 1;
            }
        } else {
            $this->accounting->level = 1;
        }

        // ESTABLECE LA NATURALEZA DE LA CUENTA
        if ($this->accounting->account_class_id == 1 || $this->accounting->account_class_id == 6 || $this->accounting->account_class_id == 7) {
            $this->accounting->account_type_id = 1;
        } else {
            $this->accounting->account_type_id = 2;
        }
        

        $this->validate();
      
        if ($this->accounting->id != null) {
            //VALIDAR SI LA CUENTA TIENE MOVIMIENTOS
            $mov = JournalDetail::where('accounting_id', $this->accounting->id)->get();
            if (count($mov) > 0) {
                $this->info('Esta cuenta contable ya tiene movimientos asociados y no puede ser modificada.');
                return;
            }

            $this->accounting->save();
        }else {
            Accounting::create([
                'code' => $this->accounting->code,
                'name' => $this->accounting->name,
                'group' => $this->accounting->group,
                'level' => $this->accounting->level,
                'account_class_id' => $this->accounting->account_class_id,
                'account_type_id' => $this->accounting->account_type_id,
                'parent_id' => $this->accounting->parent_id,
                'company_id' => session('company')->id,
            ]);
        }

        return redirect()->route('accounting.index');
    }

    public function updatedAccountingAccountClassId($value)
    {
        $this->accounting_parents = Accounting::where('group', 1)->where('account_class_id', $value)->orderBy('account_class_id')->get();
        $this->accounting_subclasses = AccountSubclass::where('account_class_id', $value)->get();
        
        $this->setAccountCode($this->parent_id);
    }

    public function UpdatedAccountingParentId($value)
    {
        $this->setAccountCode($value);
    }

    public function setAccountCode($parent_id = null)
    {
      
       if ($parent_id == null) {
            $last_parent_count = Accounting::where('parent_id', null)->orderBy('code', 'desc')->first();

            if ($last_parent_count != null) {
                $this->accounting->code = $last_parent_count->code + 1;
            }
       }else {
            $accountig = Accounting::find($parent_id);
            $lastAccountigChild = Accounting::where('parent_id', $parent_id)->orderBy('code', 'desc')->first();
            
            if ($lastAccountigChild == null) {
                $this->accounting->code = (string)$accountig->code . '01';
            }else {
                $this->accounting->code = intVal($lastAccountigChild->code) + 1;
            }
       }
    }

     // Mensaje de confirmación de acción
     public function info($message)
     {
         $this->dispatchBrowserEvent(
             'info',
             [
                 'message' => $message,
             ]
         );
     }

    
}
