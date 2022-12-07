<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalDetail extends Model
{
    use HasFactory;

    //Relación uno a muchos inversa
    public function accounting()
    {
        return $this->belongsTo(Accounting::class);
    }
}
