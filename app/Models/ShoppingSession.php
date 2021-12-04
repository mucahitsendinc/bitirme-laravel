<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingSession extends Model
{
    use HasFactory;
    
    public function getCart(){
        return $this->hasMany('App\Models\UserCart','session_id','id');
    }
}
