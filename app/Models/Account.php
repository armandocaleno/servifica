<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'accounting_id'];

    protected $with = ['accounting'];

    const INGRESO = 1;
    const EGRESO = 2;

    //Relación uno a muchos inversa
    public function accounting()
    {
        return $this->belongsTo(Accounting::class);
    }
    
}
