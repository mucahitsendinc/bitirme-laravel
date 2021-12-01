<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'city',
        'country',
        'street',
        'neighborhood',
        'postal_code',
        'phone',
        'mobile'
    ];
}
