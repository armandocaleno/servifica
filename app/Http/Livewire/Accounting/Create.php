<?php

namespace App\Http\Livewire\Accounting;
use App\Models\AccountClass;
use App\Models\Accounting;
use App\Models\AccountType;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    public $accounting, $account_class_id, $code, $name, $group, $account_type_id, $parent_id;
    public $accounting_parents;

    protected $rules = [
        'account_class_id' => 'required',
    ];
    public function mount()
    {
        $account_class = AccountClass::first();

        if ($account_class != null) {
            $this->account_class_id = $account_class->id;
            $this->accounting_parents = Accounting::where('group', 1)->where('account_class_id', $this->account_class_id)->orderBy('account_class_id')->get();
        }

        $this->account_type_id = AccountType::first()->id;
        $this->group = "0";

        $this->setAccountCode($this->parent_id);
    }

    public function render()
    {
        $accounting_types = AccountType::all();
        $accounting_class = AccountClass::all();
    
        return view('livewire.accounting.create', compact('accounting_class', 'accounting_types'));
    }

    public function store()
    {
        $this->validate([
            'code' => 'required',
            'name' => 'required',
            'group' => Rule::in(Accounting::$is_group),
            'parent_id' => 'nullable',
            'account_class_id' => 'required',
            'account_type_id' => 'required',
        ]);

        Accounting::create([
            'code' => $this->code,
            'name' => $this->name,
            'group' => $this->group,
            'account_class_id' => $this->account_class_id,
            'account_type_id' => $this->account_type_id,
            'parent_id' => $this->parent_id,
            'company_id' => session('company')->id,
        ]);

        return redirect()->route('accounting.index');
    }

    public function updatedAccountClassId($value)
    {
        $this->accounting_parents = Accounting::where('group', 1)->where('account_class_id', $value)->orderBy('account_class_id')->get();

        $this->setAccountCode($this->parent_id);
    }

    public function UpdatedParentId($value)
    {
        $this->setAccountCode($value);
    }

    public function setAccountCode($parent_id = null)
    {
       if ($parent_id == null) {
            $last_parent_count = Accounting::where('parent_id', null)->orderBy('code', 'desc')->first();

            if ($last_parent_count != null) {
                $this->code = $last_parent_count->code + 1;
            }
       }else {
            $accountig = Accounting::find($parent_id);
            $lastAccountigChild = Accounting::where('parent_id', $parent_id)->orderBy('code', 'desc')->first();

            if ($lastAccountigChild == null) {
                $this->code = (string)$accountig->code . '01';
            }else {
                $this->code = intVal($lastAccountigChild->code) + 1;
            }
       }
    }
}
