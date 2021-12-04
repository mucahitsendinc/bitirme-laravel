<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function getDiscounts(){
        return $this->hasMany('App\Models\CategoryOffer', 'category_id', 'id');
    }
}
