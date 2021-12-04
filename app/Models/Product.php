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
        return $this->hasMany('App\Models\ProductImage', 'product_id', 'id');
    }

    public function getFirstImage(){
        return $this->hasOne('App\Models\ProductImage', 'product_id', 'id');
    }

    public function getDiscounts(){
        return $this->hasMany('App\Models\ProductOffer', 'product_id', 'id');
    }

}
