<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    const AHORRO = 1;
    const CORRIENTE = 2;
    const ACTIVO = 1;
    const INACTIVO = 2;

    protected $with = ['bank', 'accounting'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    //Relación uno a muchos inversa
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function accounting()
    {
        return $this->belongsTo(Accounting::class);
    }
}
