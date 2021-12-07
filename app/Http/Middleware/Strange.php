<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\StrangeController;
use App\Models\Setting;

class Strange
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
        $check=Setting::where('setting','strange_security')->first();
        if($check->option=='true'){
            if(!StrangeController::check_attack($request)){
                return response()->json([
                        'error'=>true,
                        'message'=>'Tarafınızdan olağandışı işlem tespit edildi, yapmak istediğiniz işlem kalıcı olarak engellendi. Devam edebilmek için lütfen yetkililerle iletişim sağlayın.'
                    ],403);
            }
        }
        return $next($request);
    }
}
