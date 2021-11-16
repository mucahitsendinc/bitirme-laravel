<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CustomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
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
            $get=User::where('email',$request->email)->where('password',$request->password)->first();
            if($get){
                dd($get);
            }
        }catch (\Exception $ex){
            return response()->json(['error'=>true,'message'=>'Kullanıcı adı veya şifre hatalı'],400);
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
            $token=md5(uniqid());
            $save=User::insert([
                'name'=>$request->name,
                'surname'=>$request->surname,
                'email'=>$request->email,
                'password'=>$request->password,
                'code'=>$token
            ]);
            if($save){
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
            $update=User::where('code',$request->token)->update([
                'code'=>md5(uniqid()),
                'status'=>0
            ]);
            if($update){
                return response()->json([
                    'error'=>false,
                    'message'=>'Doğrulama işleminiz başarı ile tamamlandı.',
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
}
