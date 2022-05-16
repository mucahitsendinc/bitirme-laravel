<?php

namespace App\Http\Middleware;

use App\Http\Controllers\DataCrypter;
use Closure;
use Illuminate\Http\Request;
use App\Models\UserToken;

class VerifyAuthenticate
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
        try {
            if($request->get('mode')!=null && $request->get('mode')!='b2b'){
                return $next($request);
            }
            $token = $request->header('Authorization') ?? '';
            if ($token != '') {
                $plodes = explode(' ', $token);
                if (count($plodes) == 2) {
                    $user = UserToken::where('prefix', $plodes[0])->where('token', $plodes[1])->first();
                    $crypt = new DataCrypter;
                    $decrypt= json_decode(($crypt->crypt_router($plodes[1], true, 'decode'))[0]);
                    if($decrypt!=false){
                        if ($user->getUser) {
                            $request->attributes->add(['email' => $decrypt->email,'token_time'=>$decrypt->time,'user_id'=>$decrypt->id, 'user' => $user->getUser]);
                            return $next($request);
                        }
                    }
                }
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => true,'message'=>'Bu işlem için oturum açmanız gerekmektedir.','exception'=>$ex->getMessage()], 401);
        }
        return response()->json(['error' => true, 'message' => 'Bu işlem için oturum açmanız gerekmektedir.'], 401);
    }
}
