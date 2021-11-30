<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserToken;
use App\Http\Controllers\DataCrypter;
use App\Models\User;

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
        try {
            $token = $request->header('Authorization') ?? '';
            if ($token != '') {
                $plodes = explode(' ', $token);
                if (count($plodes) == 2) {
                    $data= UserToken::where('prefix', $plodes[0])->where('token', $plodes[1])->first();
                    $userStatus=$data->getUser->getStatus->name??'';
                    if($userStatus!="Satıcı" || $userStatus!="Yönetici"){
                        $crypt = new DataCrypter;
                        $decrypt = json_decode(($crypt->crypt_router($plodes[1], true, 'decode'))[0]);
                        if ($decrypt != false) {
                            if ($data->getUser) {
                                $request->attributes->add(['email' => $decrypt->email, 'token_time' => $decrypt->time, 'user_id' => $decrypt->id, 'user' => $data->getUser]);
                                return $next($request);
                            }
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'message' => 'Bu işlem için oturum açmanız gerekmektedir.'], 401);
        }
        return response()->json(['error' => true, 'message' => 'Bu işlem için oturum açmanız gerekmektedir.'], 401);
    }
}
