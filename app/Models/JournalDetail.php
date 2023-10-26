<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalDetail extends Model
{
    use HasFactory;

    protected $with = ['accounting'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    //Relación uno a muchos inversa
    public function accounting()
    {
        return $this->belongsTo(Accounting::class);
    }
}
