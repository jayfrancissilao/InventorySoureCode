<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'phone_number'
    ];

    //anak
    public function addresses()
    {
        return $this->hasMany(Address::class,'customer_id','id');
    }

    public function customer_payments()
    {
        return $this->hasMany(Customer_payment::class,'customer_id','id');

    }

    public function order_details()
    {
        return $this->hasMany(Customer_payment::class,'customer_id','id');

    }
}
