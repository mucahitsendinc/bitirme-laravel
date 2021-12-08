<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CustomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\UserIp;
use App\Models\UserToken;
use App\Models\UserCard;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\DataCrypter;
use Illuminate\Hashing\BcryptHasher;

class UserController extends Controller
{

    /**
     * Dakika cinsinden oturum süresi
     */
    public $session_time = 60*60*60;

    /**
     * Dakika cinsinden eposta tekrarlama süresi
     */
    public $email_repeat_time=5;

    /**
     * Gönderilecek token için karmaşıklık değeri
     */
    public $forgot_prefix= "b6b717a2ebee";

    /**
     * @OA\GET(
     * path="/api/user/get",
     * summary="Kullanıcı Bilgileri",
     * description="Giriş yapmış kullanıcının bilgilerini getir",
     * operationId="authUserInfo",
     * tags={"Üyelik"},
     * security={{"deha_token":{}}},
     * @OA\Response(
     *    response=200,
     *    description="Bilgiler sorgulandı.",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Bilgiler sorgulandı."),
     *        )
     *     )
     * )
     */
    public function get(Request $request){
        $user = $request->get('user');
        if($user){
            return response()->json(['error'=>false,'user'=>[
                'id'=>$user->id,
                'name'=>$user->name,
                'surname' => $user->surname,
                'email'=>$user->email,
                'phone'=>$user->phone,
                'address'=>$user->address,
                'city'=>$user->city,
                'country'=>$user->country,
                'postal_code'=>$user->postal_code,
                'birthday'=>$user->birthday,
            ]]);
        }else{
            return response()->json(['status'=>'error','message'=>'Kullanıcı bulunamadı.']);
        }
    }

    /**
     * @OA\Post(
     * path="/api/login",
     * summary="Giriş Yap",
     * description="Kullanıcı girişi, eposta ve parola ile",
     * operationId="authLogin",
     * tags={"Üyelik"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Kullanıcı bilgilerini girin",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="mucahit@dehasoft.com.tr"),
     *       @OA\Property(property="password", type="string", format="password", example="123456789"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Başarılı giriş yapıldı",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Giriş yapıldı"),
     *        )
     *     )
     * )
    */
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
                $token=$crypt->crypt_router(strval(json_encode($data)),true,'encode',$this->session_time*60);
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

    /**
     * Kullanıcı kayıt
     */
    /**
     * @OA\Post(
     * path="/api/register",
     * summary="Kayıt Ol",
     * description="Kullanıcı kaydı",
     * operationId="authRegister",
     * tags={"Üyelik"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Kullanıcı bilgilerini girin",
     *    @OA\JsonContent(
     *       required={"name","surname","email","password"},
     *       @OA\Property(property="name", type="string", format="text", example="Mücahit"),
     *       @OA\Property(property="surname", type="string", format="text", example="Sendinç"),
     *       @OA\Property(property="email", type="string", format="email", example="mucahit@dehasoft.com.tr"),
     *       @OA\Property(property="password", type="string", format="password", example="123456789"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Kayıt başarılı",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Kayıt yapıldı. Eposta adresi doğrulaması gönderildi."),
     *        )
     *     )
     * )
     */
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
            $token= DataCrypter::uniqidR();
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
                if($this->send_email_verify_code($request->email,$token,$request->name,$request->surname)){
                    return response()->json([
                        'error' => false,
                        'message' => 'Kayıt başarı ile oluşturuldu. Hesabınızı aktif hale getirebilmek için eposta adresinize onaylama bağlantısı gönderildi.',
                    ], 201);
                }else{
                    return response()->json([
                        'error' => false,
                        'message' => 'Kayıt başarı ile oluşturuldu. Aktivasyon eposta adresinize gönderilemedi, giriş yaparak tekrar doğrulama kodu talep ediniz.',
                    ], 204);
                }
                
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

    /**
     * @OA\Post(
     * path="/api/activate-account",
     * summary="Hesap Doğrula",
     * description="Kullanıcı doğrulama",
     * operationId="authActiveAccount",
     * tags={"Üyelik"},
     * @OA\RequestBody(
     *    required=true,
     *    description="EPosta ile kullanıcıya iletilen tokeni iletin",
     *    @OA\JsonContent(
     *       required={"token"},
     *       @OA\Property(property="token", type="string", format="text", example="adfaghadfgdagadgdfagadgad"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Doğrulama başarılı",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Doğrulama yapıldı."),
     *        )
     *     )
     * )
     */
    public function email_verify(Request $request){
        $validation = Validator::make($request->all(),[
            'token' => 'required|min:31'
        ]);
        if($validation->fails()){
            return response()->json(['error'=>true,'message'=>'Bu işlem için gerekli bilgiler eksik.'],400);
        }
        try {
            $token=DataCrypter::md5R(DataCrypter::uniqidR(),5);
            $user=User::where('code',$request->token)->first();
            $update=User::where('id',$user->id)->update([
                'code'=>$token,
                'status_id'=>3
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

    /**
     * @OA\Post(
     * path="/api/resend-activation-code",
     * summary="Hesap Doğrula Kodu Gönder",
     * description="Kullanıcı doğrulama kodunu yeniden gönder",
     * operationId="authResendActivationCode",
     * security={{"deha_token":{}}},
     * tags={"Üyelik"},
     * @OA\RequestBody(
     *    required=true,
     *    description="EPosta adresini gönderdiğiniz kullanıcıya doğrulama gönderir",
     *    @OA\JsonContent(
     *       required={"email"},
     *       @OA\Property(property="email", type="string", format="email", example="mucahit@dehasoft.com.tr"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Doğrulama kodu başarı ile gönderildi",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Doğrulama kodu gönderildi."),
     *        )
     *     )
     * )
     */
    public function new_email_verify_code(Request $request){
        if($request->get('user')->getStatus->name!='Onaysız'){
            return response()->json([
                'error'=>true,
                'message'=>'Hesabınız aktif olduğu için onay kodu gönderilmedi.'
            ],202);
        }
        $tokenEmail= $request->get('email')??'';
        $validation = Validator::make($request->all(),[
            'email' => 'required|email|min:5|max:45'
        ]);
        if($validation->fails() && $tokenEmail==$request->email){
            return response()->json(['error'=>true,'message'=>'Bu işlem için gerekli bilgiler eksik.'],400);
        }
        try {
            $user=User::where('email',$request->email)->first();
            if($user){
                $lastUpdate=(string)$user->updated_at;
                $expire=strtotime($lastUpdate)+(60*$this->email_repeat_time);
                
                if(time()<$expire){
                    return response()->json([
                        'error'=>true,
                        'message'=> 'Yeni bir eposta için ' . $this->email_repeat_time . ' dakika sonra tekrar talep gönderebilirsiniz.',
                        'time'=>$expire-time()
                    ],400);
                }
                $token=DataCrypter::md5R(DataCrypter::uniqidR());
                $update=User::where('id',$user->id)->update([
                    'code'=>$token
                ]);
                if($update){
                    if($this->send_email_verify_code($request->email,$token,$user->name,$user->surname)){
                        return response()->json([
                            'error'=>false,
                            'message'=>'Yeni doğrulama kodu eposta adresinize gönderildi.',
                        ],200);
                    }else{
                        return response()->json([
                            'error'=>false,
                            'message'=>'Yeni doğrulama kodu eposta adresinize gönderilemedi, giriş yaparak tekrar doğrulama kodu talep ediniz.',
                        ],204);
                    }
                }
            }else{
                return response()->json([
                    'error'=>true,
                    'message'=>'Bu eposta adresi ile kayıtlı bir hesap bulunamadı.',
                ],400);
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

    /**
     * Kullanıcı eposta adresi doğrulama kodunu gönder
     */
    public function send_email_verify_code($email,$token,$name="",$surname=""){
        try {
            $data = [
                'email' => $email,
                'sender' => env('MAIL_FROM_ADDRESS'),
            ];
            Mail::send('emails.verify', [
                'token' => $token,
                'name' => $name,
                'surname' => $surname
            ], function ($message) use ($data) {
                $message->from($data['sender'], 'Kayıt işlemi tamamlama - Dehasoft E-Ticaret');
                $message->subject("Kayıt işlemi tamamlama - Dehasoft E-Ticaret");
                $message->to($data['email']);
            });
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * @OA\Post(
     * path="/api/logout",
     * summary="Çıkış Yap",
     * description="Güvenli çıkış gönderilen token yok edilir.",
     * operationId="authLogOut",
     * security={{"deha_token":{}}},
     * tags={"Üyelik"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Token ve Tokentype gönderilir ve yok edilir.",
     *    @OA\JsonContent(
     *       required={"token","tokenType"},
     *       @OA\Property(property="tokenType", type="string", format="text", example="gadfdhadjhadfhadfh"),
     *       @OA\Property(property="token", type="string", format="text", example="gadfdhadjhadfhadfh"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Doğrulama kodu başarı ile gönderildi",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Doğrulama kodu gönderildi."),
     *        )
     *     )
     * )
     */
    public function logout(Request $request){
        $validation = Validator::make($request->all(), [
            'token' => 'required|min:25',
            'tokenType' => 'required|min:25',
        ]);
        if($validation->fails()){
            return response()->json(['error'=>true,'message'=>'Bu işlem için gerekli bilgiler eksik.'],400);
        }
        try {
            $user=UserToken::where('token',$request->token)->where('prefix',$request->tokenType)->first();
            if($user){
                UserIp::where('user_id',$user->id)->update([
                    'last_logout_ip'=>$request->ip(),
                    'last_logout_date'=>date('Y-m-d H:i:s')
                ]);
                $update=UserToken::where('id',$user->id)->update([
                    'token'=>null,
                    'prefix'=>null
                ]);
                if($update){
                    return response()->json([
                        'error'=>false,
                        'message'=>'Başarı ile çıkış yapıldı.',
                    ],200);
                }
            }else{
                UserIp::where('user_id', $user->id)->update([
                    'last_unsuccessful_logout_ip' => $request->ip(),
                    'last_unsuccessful_logout_date' => date('Y-m-d H:i:s')
                ]);
                return response()->json([
                    'error' => true,
                    'message' => 'Çıkış yapılamadı.'
                ], 400);
            }
        }catch(\Exception $ex){
            return response()->json([
                'error'=>true,
                'message'=>'Çıkış yapılamadı.',
                'exception'=>$ex
            ],403);
        }
    }

    
    /**
     * @OA\Post(
     * path="/api/forgot-password",
     * summary="Şifremi Unuttum",
     * description="Kullanıcı parolasını sıfırlamak için kullanılır.",
     * operationId="authForgotPassword",
     * tags={"Üyelik"},
     * @OA\RequestBody(
     *    required=true,
     *    description="EPosta gönderilir.",
     *    @OA\JsonContent(
     *       required={"email"},
     *       @OA\Property(property="email", type="string", format="email", example="mucahit@dehasoft.com.tr"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Doğrulama kodu başarı ile gönderildi",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Doğrulama kodu gönderildi."),
     *        )
     *     )
     * )
     */
    public function forgot_password(Request $request){
        $validation = Validator::make($request->all(), [
            'email' => 'required|email|min:5|max:45'
        ]);
        if($validation->fails()){
            return response()->json(['error'=>true,'message'=>'Bu işlem için gerekli bilgiler eksik.'],400);
        }
        if(isset($request->returnUrl) && !strstr($request->returnUrl, env('APP_FRONT_URL'))){
            return response()->json(['error'=>true,'message'=>'Geçersiz işlem.'],400);
        }
        try {
            $user=User::where('email',$request->email)->first();
            if($user){
                $lastUpdate=(string)$user->updated_at;
                $expire=strtotime($lastUpdate)+(60*$this->email_repeat_time);
                if(time()<$expire){
                    return response()->json([
                        'error'=>true,
                        'message'=> 'Yeni bir eposta için '.$this->email_repeat_time.' dakika sonra tekrar talep gönderebilirsiniz.',
                        'time'=>$expire-time()
                    ],400);
                }
                $crypt=new DataCrypter;
                $token= DataCrypter::md5R($this->forgot_prefix. DataCrypter::uniqidR());
                $update=User::where('id',$user->id)->update([
                    'code'=> $token
                ]);
                if($update){
                    if($this->send_email_verify_code($request->email, $token,$user->name,$user->surname)){
                        return response()->json([
                            'error'=>false,
                            'message'=>'Eposta adresinize parola sıfırlama bağlantısı gönderildi.',
                        ],200);
                    }else{
                        return response()->json([
                            'error'=>false,
                            'message'=>'Yeni doğrulama kodu eposta adresinize gönderilemedi, lütfen daha sonra tekrar talep ediniz.',
                        ],204);
                    }
                }
            }else{
                return response()->json([
                    'error'=>true,
                    'message'=>'Bu eposta adresi ile kayıtlı bir hesap bulunamadı.',
                ],400);
            }
        }catch (\Exception $ex){
            return response()->json([
                'error'=>true,
                'message'=>'Beklenmedik bir hata oluştu.',
                'exception'=>$ex
            ],403);
        }

        

    }

    /**
     * @OA\Post(
     * path="/api/reset-password",
     * summary="Şifre Sıfırlama",
     * description="Kullanıcı parolasını sıfırlamak için kullanılır.",
     * operationId="authResetPassword",
     * tags={"Üyelik"},
     * @OA\RequestBody(
     *    required=true,
     *    description="EPosta ile gelen kod gönderilir.",
     *    @OA\JsonContent(
     *       required={"token","password","passwordConfirmation"},
     *       @OA\Property(property="token", type="string", format="text", example="adfgdagadgfadgad"),
     *       @OA\Property(property="password", type="string", format="password", example="adfgdagadgfadgad"),
     *       @OA\Property(property="passwordConfirmation", type="string", format="password", example="adfgdagadgfadgad"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Doğrulama kodu başarı ile gönderildi",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Doğrulama kodu gönderildi."),
     *        )
     *     )
     * )
     */
    public function reset_password(Request $request){
        $validation = Validator::make($request->all(), [
            'token' => 'required|min:25',
            'password' => 'required|min:5|max:55',
            'passwordConfirmation' => 'required|min:5|max:55',
        ]);
        if($validation->fails()){
            return response()->json(['error'=>true,'message'=>'Bu işlem için gerekli bilgiler eksik.'],400);
        }
        if($request->password!=$request->passwordConfirmation){
            return response()->json(['error'=>true,'message'=>'Şifreler uyuşmuyor.'],400);
        }
        try {
            $user=User::where('code',$request->token)->first();
            if($user){
                $lastUpdate=strtotime((string)$user->updated_at);
                $expire=($lastUpdate)+(60*$this->email_repeat_time);
                if($expire<time()){
                    dd('');
                    return response()->json([
                        'error'=>true,
                        'message'=> 'Kullandığınız tokenin geçerlilik süresi dolmuştur. Lütfen yeni bir parola sıfırlama talebinde bulununuz.',
                    ],400);
                }
                $update=User::where('id',$user->id)->update([
                    'password'=> DataCrypter::md5R($request->password),
                    'code'=> DataCrypter::uniqidR()
                ]);
                if($update){
                    return response()->json([
                        'error'=>false,
                        'message'=>'Şifreniz başarıyla değiştirildi.',
                    ],200);
                }
            }else{
                return response()->json([
                    'error'=>true,
                    'message'=>'Token geçersiz veya süresi dolmuş işlem.',
                ],400);
            }
        }catch (\Exception $ex){
            return response()->json([
                'error'=>true,
                'message'=>'Beklenmedik bir hata oluştu.',
                'exception'=>$ex
            ],403);
        }
    }
}