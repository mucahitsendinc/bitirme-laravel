<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    public function getStatus(){
        return $this->hasOne('App\Models\UserStatus', 'id', 'status_id');
    }
    public function getAddress(){
        return $this->hasMany('App\Models\UserAddress', 'user_id', 'id');
    }
    public function getCard(){
        return $this->hasMany('App\Models\UserCard', 'user_id', 'id');
    }

    public function getImage(){
        return $this->hasOne('App\Models\UserImage', 'user_id', 'id');
    }

}


