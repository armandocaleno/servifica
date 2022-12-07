<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    const ACTIVO = 1;
    const INACTIVO = 0;

    //Relacion uno a muchos
    public function transactions(){
        return $this->hasMany(Transaction::class);
    }   

    //Relacion muchos a muchos
    public function accounts(){
        return $this->belongsToMany(Account::class)->withPivot('value');
    }

    //Relación uno a muchos inversa
    public function accounting()
    {
        return $this->belongsTo(Accounting::class);
    }
}
