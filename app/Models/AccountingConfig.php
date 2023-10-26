<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountingConfig extends Model
{
    use HasFactory;

    protected $with = ['accounting'];

    public function accounting()
    {
        return $this->belongsTo(Accounting::class);
    }
}
