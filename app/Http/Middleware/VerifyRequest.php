<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
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
        $check = Setting::where('setting', 'front_security')->first();
        if($check->option=='true' ){
            $check=Setting::where('setting','front_url')->first();
            if($check->option!=$request->headers->get('referer')){
                return response()->json(['error' => true, 'message' => 'Servis kullanım için gerekli izinlere sahip değilsiniz.'], 401);
            }
        }
        return $next($request);
    }
}
