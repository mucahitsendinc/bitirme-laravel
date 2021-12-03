<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ActiveAccount
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
        $statusName= $request->get('user')->getStatus;
        if($statusName->id>2){
            return response()->json([
                'error'=>true,
                'message'=>'Bu işlem için hesabınızı aktifleştirmeniz gerekmektedir'
            ],401);
        }
        return $next($request);
    }
}
