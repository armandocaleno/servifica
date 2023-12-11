<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Check extends Model
{
    use HasFactory;

    const ACTIVO = 1;
    const INACTIVO = 0;

    const INPUT = "I";
    const OUTPUT = "O";

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $with = ['bank_account','journal', 'expenses'];

    public function bank_account()
    {
       return $this->belongsTo(BankAccount::class);
    }

    public function journal()
    {
        return $this->morphOne(Journal::class, 'journable', 'journable_type', 'journable_id');
    }

    public function expenses()
    {
        return $this->belongsToMany(Expense::class)->withPivot('value');
    }
}
