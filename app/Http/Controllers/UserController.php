<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CustomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function login(Request $request){

    }
    public function register(Request $request){
        $validation = Validator::make($request->all(),[
            'name' => 'required|min:5|max:25',
            'surname' => 'required|min:5|max:25',
            'email' => 'required|email|min:5|max:25',
        ]);
        dd($validation->fails());
    }
}
