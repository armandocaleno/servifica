<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accounting extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    const SI = "1";
    const NO = "0";

    public static $is_group = [self::SI, self::NO];

    protected $with = ['class', 'type'];

    //Relación uno a muchos
    public function class()
    {
        return $this->belongsTo(AccountClass::class, 'account_class_id');
    }

    public function type()
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }
}
