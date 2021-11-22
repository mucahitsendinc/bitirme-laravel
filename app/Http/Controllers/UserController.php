<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CustomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\UserIp;
use App\Models\UserToken;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\DataCrypter;
class UserController extends Controller
{

    public function login(Request $request){
        $validation = Validator::make($request->all(),[
            'email' => 'required|email|min:5|max:35',
            'password' => 'required|min:6|max:100',
        ]);
        if($validation->fails()){
            return response()->json(['error'=>true,'message'=>'Kullanıcı adı veya şifre hatalı'],400);
        }
        try {
            $get=User::where('email',$request->email)->where('password',DataCrypter::md5R($request->password))->first();
            if($get){
                $crypt=new DataCrypter;
                $data=[
                  'id'=>$get->id,
                  'email'=>$request->email,
                  'time'=>date('Y-m-d H:i:s')
                ];
                $token=$crypt->crypt_router(strval(json_encode($data)),true,'encode',3600);
                $save=UserToken::where('user_id',$get->id)->update([
                    'prefix'=>md5(env('APP_NAME')),
                    'token'=>$token
                ]);
                if($save){
                    return response()->json(['error'=>false,'message'=>'Giriş işlemi başarı ile gerçekleşti.','token'=>$token,'tokenType'=>md5(env('APP_NAME'))],200);
                }else{
                    $insert=UserToken::insert([
                        'user_id'=>$get->id,
                        'prefix'=>md5(env('APP_NAME')),
                        'token'=>$token
                    ]);
                    if($insert){
                        return response()->json(['error'=>false,'message'=>'Giriş işlemi başarı ile gerçekleşti.','token'=>$token,'tokenType'=>md5(env('APP_NAME'))],200);
                    }
                }
            }
        }catch (\Exception $ex){
            return response()->json(['error'=>true,'message'=>'Kullanıcı adı veya şifre hatalı','exception'=>$ex],400);
        }
        return response()->json(['error'=>true,'message'=>'Kullanıcı adı veya şifre hatalı'],400);
    }
    public function register(Request $request){
        /**
         * Validasyon işlemi ,
         * Ad için 5-25 aralık
         * Soyad için 5-25 aralık
         * Eposta için 5-35 aralık
         * Parola için en az 1 harf , 1 rakam ve 1 özel karakter
         */
        $validation = Validator::make($request->all(),[
            'name' => 'required|min:5|max:25',
            'surname' => 'required|min:5|max:25',
            'email' => 'required|email|min:5|max:35',
            'password' => 'required|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$=+?#*&%]).*$/|min:6',
        ]);

        if($validation->fails()){
            $messages=[
                'password'=>($validation->getMessageBag())->messages()['password']??'success',
                'email'=>($validation->getMessageBag())->messages()['email']??'success',
                'name'=>($validation->getMessageBag())->messages()['name']??'success',
                'surname'=>($validation->getMessageBag())->messages()['surname']??'success'
            ];
            return response()->json([
                'error'=>true,
                'message'=>'Kayıt için bilgiler doğru gönderilmedi.',
                'validation'=>array_filter($messages,function($e){
                    if($e!='success'){
                        return true;
                    }
                })
            ],400);
        }

        try{
            $token=DataCrypter::md5R(uniqid());
            $save=User::insertGetId([
                'name'=>$request->name,
                'surname'=>$request->surname,
                'email'=>$request->email,
                'password'=>DataCrypter::md5R($request->password),
                'code'=>$token
            ]);

            if($save){
                UserIp::insert([
                    'user_id'=>$save,
                    'register_ip'=>$request->ip(),
                    'last_login_ip'=>$request->ip(),
                    'last_request_ip'=>$request->ip()
                ]);
                $data=[
                    'email'=>$request->email,
                    'sender'=>env('MAIL_FROM_ADDRESS'),
                ];
                Mail::send('emails.verify', [
                    'token'=>$token,
                    'name'=>$request->name,
                    'surname'=>$request->surname
                ], function ($message) use($data) {
                    $message->from($data['sender'], 'Kayıt işlemi tamamlama - Dehasoft E-Ticaret');
                    $message->subject("Kayıt işlemi tamamlama - Dehasoft E-Ticaret");
                    $message->to($data['email']);
                });
                return response()->json([
                    'error'=>false,
                    'message'=>'Kayıt başarı ile oluşturuldu. Hesabınızı aktif hale getirebilmek için eposta adresinize onaylama bağlantısı gönderildi.',
                ],200);
            }
        }catch (\Exception $ex){
            return response()->json([
                'error'=>true,
                'message'=>'Beklenmedik bir hata oluştu.',
                'exception'=>$ex
            ],403);
        }

        return response()->json([
            'error'=>true,
            'message'=>'Beklenmedik bir hata oluştu.'
        ],400);

    }
    public function email_verify(Request $request){
        $validation = Validator::make($request->all(),[
            'token' => 'required|min:31|max:33'
        ]);
        if($validation->fails()){
            return response()->json(['error'=>true,'message'=>'Bu işlem için gerekli bilgiler eksik.'],400);
        }
        try {
            $token=DataCrypter::md5R(uniqid(),5);
            $user=User::where('code',$request->token)->first();
            $update=User::where('id',$user->id)->update([
                'code'=>$token,
                'status'=>0
            ]);
            if($update){

                UserIp::where('user_id',$user->id)->update([
                    'last_request_ip'=>$request->ip(),
                    'last_request_date'=>date('Y-m-d H:i:s'),
                ]);
                return response()->json([
                    'error'=>false,
                    'message'=>'Doğrulama işleminiz başarı ile tamamlandı.',
                ],200);
            }else if($user){
                UserIp::where('user_id',$user->id)->update([
                    'last_unsuccessful_request_ip'=>$request->ip(),
                    'last_unsuccessful_request_date'=>date('Y-m-d H:i:s')
                ]);
            }
        }catch (\Exception $ex){
            return response()->json([
                'error'=>true,
                'message'=>'Beklenmedik bir hata oluştu.',
                'exception'=>$ex
            ],403);
        }

        return response()->json([
            'error'=>true,
            'message'=>'Beklenmedik bir hata oluştu.'
        ],400);

    }
}
