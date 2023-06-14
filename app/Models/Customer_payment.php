<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer_payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_type',
        'customer_id'
    ];


    public function customers()
    {
        return $this->belongsTo(Customer::class,'customer_id','id');
    }
}
