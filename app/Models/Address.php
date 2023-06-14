<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'region',
        'province',
        'municipality',
        'baranggay',
        'zip_code',
        'street',
        'customer_id'
    ];



    public function customers()
    {
//        $this->belongsTo(Customer::class);
        return $this->belongsTo(Customer::class,'customer_id','id');
    }
}
