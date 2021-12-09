<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    public function getDriver(){
        return $this->hasOne('App\Models\ImageDriver', 'id', 'image_driver_id');
    }
}
