<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AddressController extends Controller
{

    /**
     * @OA\GET(
     * path="/api/user/address/get",
     * summary="Adresleri getir",
     * description="Kullanıcının kayıtlı adreslerini listeler.",
     * operationId="userAddressGet",
     * tags={"Kullanıcı Adres"},
     * security={{"deha_token":{}}},
     * @OA\Response(
     *    response=200,
     *    description="Kullanıcı adresleri başarı ile sorgulandı",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Kullanıcı adresleri başarı ile sorgulandı"),
     *        )
     *     )
     * )
     */
    public function get(Request $request){
        try {
            $user = $request->get('user');
            $addresses = $user->getAddress->toArray();
            return response()->json([
                'error' => false,
                'message' => 'Kullanıcı adresleri başarı ile sorgulandı.',
                'addresses' => array_map(function($address){
                    return [
                        'id' => $address['id'],
                        'name' => $address['name'],
                        'address' => $address['address'],
                        'city' => $address['city'],
                        'country' => $address['country'],
                        'street' => $address['street'],
                        'neighborhood' => $address['neighborhood'],
                        'postal_code' => $address['postal_code'],
                        'phone' => $address['phone'],
                        'mobile' => $address['mobile']
                    ];
                }, $addresses)
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => 'Kullanıcı adresleri sorgulanırken bir hata oluştu.',
            ], 403);
        }
        return response()->json([
            'error' => true,
            'message' => 'Kullanıcı adresleri sorgulanırken bir hata oluştu.',
        ], 403);
    }

    /**
     * @OA\POST(
     * path="/api/user/address/create",
     * summary="Adres oluştur",
     * description="Kullanıcı yeni adres tanımı.",
     * operationId="userAddressCreate",
     * tags={"Kullanıcı Adres"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Yeni bilgilerle adres oluşturulur.",
     *    @OA\JsonContent(
     *       required={"name", "address", "city", "country", "street", "neighborhood", "postal_code", "phone", "mobile"},
     *          @OA\Property(property="name", type="string", example="Adı Soyadı"),
     *          @OA\Property(property="address", type="string", example="Adres"),
     *          @OA\Property(property="city", type="string", example="Şehir"),
     *          @OA\Property(property="country", type="string", example="Ülke"),
     *          @OA\Property(property="street", type="string", example="Sokak"),
     *          @OA\Property(property="neighborhood", type="string", example="Mahalle"),
     *          @OA\Property(property="postal_code", type="string", example="Posta Kodu"),
     *          @OA\Property(property="phone", type="string", example="Telefon"),
     *          @OA\Property(property="mobile", type="string", example="Cep Telefonu"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Kullanıcı adresi başarı ile oluşturuldu",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Kullanıcı adresi başarı ile oluşturuldu"),
     *        )
     *     )
     * )
     */
    public function create(Request $request){
        $validation = Validator::make($request->all(), [
            'name' => 'required|min:2|max:45',
            'address' => 'required|min:8|max:255',
            'city' => 'required|min:3|max:45',
            'country' => 'required|min:3|max:45',
            'street' => 'required|min:3|max:45',
            'neighborhood' => 'required|min:3|max:45',
            'postal_code' => 'required|min:3|max:15',
            'phone' => 'required|min:7|max:15',
            'mobile' => 'required|min:8|max:15'
        ]);
        if ($validation->fails() || $request->phone == $request->mobile) {
            $messages = [
                'name' => ($validation->getMessageBag())->messages()['name'] ?? 'success',
                'address' => ($validation->getMessageBag())->messages()['address'] ?? 'success',
                'city' => ($validation->getMessageBag())->messages()['city'] ?? 'success',
                'country' => ($validation->getMessageBag())->messages()['country'] ?? 'success',
                'street' => ($validation->getMessageBag())->messages()['street'] ?? 'success',
                'neighborhood' => ($validation->getMessageBag())->messages()['neighborhood'] ?? 'success',
                'postal_code' => ($validation->getMessageBag())->messages()['postal_code'] ?? 'success',
                'phone' => ($validation->getMessageBag())->messages()['phone'] ?? 'success',
                'mobile' => ($validation->getMessageBag())->messages()['mobile'] ?? 'success',
                'numbers' => 'Telefon numaraları aynı olamaz'
            ];
            return response()->json([
                'error' => true,
                'message' => 'Bu işlem için gerekli bilgiler eksik.',
                'validation' => array_filter($messages, function ($value) {
                    return $value != 'success';
                })
            ], 400);
        }
        try {
            $id = $request->get('user_id');
            $addresses = User::where('id', $id)->first()->getAddress;
            if (count($addresses) >= 4) {
                return response()->json([
                    'error' => true,
                    'message' => 'Adres ekleme limitiniz dolmuştur.',
                ], 400);
            }
            $address = UserAddress::insertGetId([
                'name' => $request->name,
                'address' => $request->address,
                'city' => $request->city,
                'country' => $request->country,
                'street' => $request->street,
                'neighborhood' => $request->neighborhood,
                'postal_code' => $request->postal_code,
                'phone' => $request->phone,
                'mobile' => $request->mobile,
                'user_id' => $id
            ]);
            if ($address) {
                return response()->json([
                    'error' => false,
                    'message' => 'Adresiniz başarıyla eklendi.',
                    'address' => [
                        'id' => $address,
                        'name' => $request->name,
                        'address' => $request->address,
                        'city' => $request->city,
                        'country' => $request->country,
                        'street' => $request->street,
                        'neighborhood' => $request->neighborhood,
                        'postal_code' => $request->postal_code,
                        'phone' => $request->phone,
                        'mobile' => $request->mobile
                    ]
                ], 200);
            }
        } catch (\Exception $ex) {
            return response()->json([
                'error' => true,
                'message' => 'Beklenmedik bir hata oluştu.',
                'exception' => $ex
            ], 403);
        }
        return response()->json([
            'error' => true,
            'message' => 'Beklenmedik bir hata oluştu.',
        ], 403);
    }

    /**
     * @OA\POST(
     * path="/api/user/address/delete",
     * summary="Adres sil",
     * description="Kullanıcının kayıtlı adresini siler.",
     * operationId="userAddressDelete",
     * tags={"Kullanıcı Adres"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Kullanıcının belirli adresini siler.",
     *    @OA\JsonContent(
     *       required={"address_id"},
     *          @OA\Property(property="address_id", type="integer", example="1")
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Kullanıcı adresi başarı ile silindi",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Kullanıcı adresi başarı ile silindi"),
     *        )
     *     )
     * )
     */
    public function delete(Request $request){
        $validation = Validator::make($request->all(), [
            'address_id' => 'required|numeric'
        ]);
        if ($validation->fails()) {
            $messages = [
                'address_id' => ($validation->getMessageBag())->messages()['address_id'] ?? 'success'
            ];
            return response()->json([
                'error' => true,
                'message' => 'Bu işlem için gerekli bilgiler eksik.',
                'validation' => array_filter($messages, function ($value) {
                    return $value != 'success';
                })
            ], 400);
        }
        try {
            $id = $request->get('user_id');
            $address = UserAddress::where('id', $request->address_id)->where('user_id', $id)->delete();
            if ($address) {
                $address->delete();
                return response()->json([
                    'error' => false,
                    'message' => 'Adresiniz başarıyla silindi.'
                ], 200);
            }
        } catch (\Exception $ex) {
            return response()->json([
                'error' => true,
                'message' => 'Beklenmedik bir hata oluştu.',
                'exception' => $ex
            ], 403);
        }
        return response()->json([
            'error' => true,
            'message' => 'Beklenmedik bir hata oluştu.',
        ], 403);
    }

    /**
     * @OA\POST(
     * path="/api/user/address/update",
     * summary="Adres güncelle",
     * description="Kullanıcının belirli adresini günceller.",
     * operationId="userAddressUpdate",
     * tags={"Kullanıcı Adres"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Kullanıcının adresini güncelle.",
     *    @OA\JsonContent(
     *       required={"address_id", "name", "address", "city", "country", "street", "neighborhood", "postal_code", "phone", "mobile"},
     *          @OA\Property(property="address_id", type="integer", example="1"),
     *          @OA\Property(property="name", type="string", example="Adres Adı"),
     *          @OA\Property(property="address", type="string", example="Adres"),
     *          @OA\Property(property="city", type="string", example="Şehir"),
     *          @OA\Property(property="country", type="string", example="Ülke"),
     *          @OA\Property(property="street", type="string", example="Sokak"),   
     *          @OA\Property(property="neighborhood", type="string", example="Mahalle"),
     *          @OA\Property(property="postal_code", type="string", example="Posta Kodu"),
     *          @OA\Property(property="phone", type="string", example="Telefon"),
     *          @OA\Property(property="mobile", type="string", example="Cep Telefonu"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Kullanıcı adresi başarı ile güncellendi",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Kullanıcı adresi başarı ile güncellendi"),
     *        )
     *     )
     * )
     */
    public function update(Request $request){
        $validation = Validator::make($request->all(), [
            'address_id' => 'required|numeric',
            'name' => 'required|min:2|max:45',
            'address' => 'required|min:8|max:255',
            'city' => 'required|min:3|max:45',
            'country' => 'required|min:3|max:45',
            'street' => 'required|min:3|max:45',
            'neighborhood' => 'required|min:3|max:45',
            'postal_code' => 'required|min:3|max:15',
            'phone' => 'required|min:7|max:15',
            'mobile' => 'required|min:8|max:15'
        ]);
        if($validation->fails() || $request->phone == $request->mobile){
            $messages = [
                'address_id' => ($validation->getMessageBag())->messages()['address_id'] ?? 'success',
                'name' => ($validation->getMessageBag())->messages()['name'] ?? 'success',
                'address' => ($validation->getMessageBag())->messages()['address'] ?? 'success',
                'city' => ($validation->getMessageBag())->messages()['city'] ?? 'success',
                'country' => ($validation->getMessageBag())->messages()['country'] ?? 'success',
                'street' => ($validation->getMessageBag())->messages()['street'] ?? 'success',
                'neighborhood' => ($validation->getMessageBag())->messages()['neighborhood'] ?? 'success',
                'postal_code' => ($validation->getMessageBag())->messages()['postal_code'] ?? 'success',
                'phone' => ($validation->getMessageBag())->messages()['phone'] ?? 'success',
                'mobile' => ($validation->getMessageBag())->messages()['mobile'] ?? 'success',
                'numbers' => 'Telefon numaraları aynı olamaz'
            ];
            return response()->json([
                'error' => true,
                'message' => 'Bu işlem için gerekli bilgiler eksik.',
                'validation' => array_filter($messages, function ($value) {
                    return $value != 'success';
                })
            ], 400);
        }
        try {
            $id = $request->get('user_id');
            $address = UserAddress::where('id', $request->address_id)->where('user_id', $id)->first();
            if ($address) {
                $address->name = $request->name;
                $address->address = $request->address;
                $address->city = $request->city;
                $address->country = $request->country;
                $address->street = $request->street;
                $address->neighborhood = $request->neighborhood;
                $address->postal_code = $request->postal_code;
                $address->phone = $request->phone;
                $address->mobile = $request->mobile;
                $address->save();
                return response()->json([
                    'error' => false,
                    'message' => 'Adresiniz başarıyla güncellendi.',
                    'address'=>[
                        'id' => $address->id,
                        'name' => $address->name,
                        'address' => $address->address,
                        'city' => $address->city,
                        'country' => $address->country,
                        'street' => $address->street,
                        'neighborhood' => $address->neighborhood,
                        'postal_code' => $address->postal_code,
                        'phone' => $address->phone,
                        'mobile' => $address->mobile
                    ]
                ], 200);
            }
        } catch (\Exception $ex) {
            return response()->json([
                'error' => true,
                'message' => 'Beklenmedik bir hata oluştu.',
                'exception' => $ex
            ], 403);
        }
        return response()->json([
            'error' => true,
            'message' => 'Beklenmedik bir hata oluştu.',
        ], 403);
        
    }

}
