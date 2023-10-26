<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    const INDIVIDUAL = 1;
    const LOTES = 2;
    const PAGO = 3;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $with = ['partner', 'journal'];    

    //  Convierte el campo tipo json a un array
    protected $casts = [
        'content' => 'array',
        'voucher' => 'array',
        'aditional_info' => 'array'
    ];   

     //Relacion uno a muchos inversa
     public function partner(){
        return $this->belongsTo(Partner::class);
    }   

    public function company()
    {
       return $this->belongsTo(Company::class);
    }

    public function journal()
    {
        return $this->morphOne(Journal::class, 'journable', 'journable_type', 'journable_id');
    }

     // Query scope
     public function scopePartner($query, $partner)
     {
         if ($partner) {
             return $query->where('partners.id', $partner);
         }
     }    
    
}
