<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accounting extends Model
{
    use HasFactory;

    const SI = 1;
    const NO = 0;

    //Relación uno a muchos
    public function classes()
    {
        return $this->belongsTo(AccountClass::class);
    }

    public function types()
    {
        return $this->belongsTo(AccountType::class);
    }
}
