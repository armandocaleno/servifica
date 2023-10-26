<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    const ACTIVO = 1;
    const INACTIVO = 0;
    const AUTO = '1';
    const MANUAL = '0';

    protected $with = ['details'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    //Relación uno a muchos
    public function details()
    {
        return $this->hasMany(JournalDetail::class);
    }

    public function journable()
    {
        return $this->morphTo();
    }

    public function getNumber()
    {
        $nuevoNumero = 1000001;

        $lastRegister = Journal::latest('id')->first();

        if ($lastRegister) {
            $this->number = intval($lastRegister->number);
            $nuevoNumero = $this->number + 1;
        }     
              
        return $nuevoNumero;
    }
}
