<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryOffer extends Model
{
    use HasFactory;

    public function getDiscount(){
        return $this->hasOne('App\Models\Discount', 'id', 'discount_id');
    }
}
