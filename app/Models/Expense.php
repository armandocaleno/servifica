<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $with = ['journal', 'suppliers'];

    // protected $appends = ['state'];

    public function journal()
    {
        return $this->morphOne(Journal::class, 'journable', 'journable_type', 'journable_id');
    }

    // esta en singular porque existe un campo de nombre supplier
    public function suppliers()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function checks()
    {
        return $this->belongsToMany(Check::class)->withPivot('value');
    }

    public function totalValueChecks()
    {
        $total = 0;

        foreach ($this->checks as $check) {
            $total += $check->total;
        }

        return $total;
    }

    public function state()
    {
        $state = 'pendiente';

        if ($this->totalValueChecks() > 0) {
            if ($this->totalValueChecks() == $this->total) {
                $state = 'pagada';
            }else {
                $state = 'abonada';
            }
        }

        return $state;
    }

    public function residue()
    {
        $residue = $this->total - $this->totalValueChecks();
        $residue = number_format($residue,2, '.', '');
        return $residue;
    }
}
