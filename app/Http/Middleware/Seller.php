<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Seller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $status=$request->get('user')->getStatus??'';
        if(isset($status) && $status!='' && ($status->name == 'Satıcı' || $status->name == 'Yönetici')){
            $request->attributes->add(['status'=>$status]);
            return $next($request);
        }
        return response()->json(['error' => true, 'message' => 'Bu işlem için yetkiniz bulunmamaktadır.'], 401);
    }
}
