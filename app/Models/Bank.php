<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    //Relación uno a muchos
    public function account()
    {
        return $this->hasMany(BankAccount::class);
    }
}
