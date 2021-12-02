<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;


    public function getCategory()
    {
        return $this->hasOne('App\Models\Category', 'id', 'category_id');
    }

    public function getImage(){
        return $this->hasMany('App\Models\ProductImage', 'id', 'image_id');
    }

    public function getDiscount(){
        return $this->hasMany('App\Models\Discount', 'id', 'discount_id');
    }

}
