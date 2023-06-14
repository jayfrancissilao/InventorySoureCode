<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'category_id',
        'price',
        'product_image',
        'stock',
        'description'
    ];

    public function Categories(){
        return $this->belongsTo(Category::class,'category_id','id');
    }

}
