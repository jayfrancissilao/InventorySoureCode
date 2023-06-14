<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_detail extends Model
{
    use HasFactory;

    protected $fillable = [

        'total',
        'customer_id'
    ];

    public function customers()
    {
//        $this->belongsTo(Customer::class);
        return $this->belongsTo(Customer::class,'customer_id','id');
    }
}
