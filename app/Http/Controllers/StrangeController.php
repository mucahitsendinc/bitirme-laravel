<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Strange;

class StrangeController extends Controller
{
    public static function check_attack($request){
        $url=$request->url();
        $ip=$request->ip();

        $check=Strange::where('request_ip',$ip)->where('request_url',$url)->first();

        if(!$check){
            $data=[
                'request_url'=>$request->url(),
                'request_ip'=>$request->ip(),
                'request_data'=>json_encode($request->all())
            ];
            $save=Strange::insert($data);
            if($save){
                return true;
            }
        }
        $level=env('APP_STRANGE_SECURITY_LEVEL')??'hard';
        if($check->count<(
            $level=='easy' ? 500 : ($level=='normal' ? 250 : 50))){
            $data=[
                'request_url'=>$request->url(),
                'request_ip'=>$request->ip(),
                'request_data'=>json_encode($request->all())
            ];
            $save=Strange::where('request_ip',$ip)->where('request_url',$url)->update(['count'=>$check->count+1]);
            return true;
        }
        return false;
    }
}
