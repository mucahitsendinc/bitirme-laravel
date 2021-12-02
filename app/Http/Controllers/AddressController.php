<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AddressController extends Controller
{

    /**
     * Kullanıcı adreslerini listele
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
     * Kullanıcı adres güncelleme
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
     * Kullanıcı adres silme
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

    /*
    * Kullanıcı adres güncelleme
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
