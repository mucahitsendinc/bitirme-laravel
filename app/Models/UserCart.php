<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCart extends Model
{
    use HasFactory;

    public function getProduct(){
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }
}
