<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    const ACTIVO = 1;
    const INACTIVO = 0;

    //Relación uno a muchos
    public function details()
    {
        return $this->hasMany(JournalDetail::class);
    }
}
