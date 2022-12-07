<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    const ACTIVO = 1;
    const INACTIVO = 0;

    //Relación muchos a muchos
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    //Relación uno a muchos
    public function journals()
    {
        return $this->belongsTo(Journal::class);
    }

    public function partners()
    {        
        return $this->hasMany(Partner::class);
    }

    public function accountings()
    {
        return $this->belongsTo(Accounting::class);
    }
}
