<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyRequest
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
        if(env('APP_FRONT_SECURITY')==true &&$request->headers->get('referer')!=env('APP_FRONT_URL')){
            return response()->json(['error'=>true,'message'=>'Servis kullanım için gerekli izinlere sahip değilsiniz.'],401);
        }
        return $next($request);
    }
}
