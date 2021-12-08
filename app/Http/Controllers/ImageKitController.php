<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ImageKit\ImageKit;
use App\Models\Setting;
use App\Http\Controllers\DataCrypter;

class ImageKitController extends Controller
{
    public function image_validation($name){
        if(strpos($name, '.png') == false && strpos($name, '.jpg') == false && strpos($name, '.jpeg') == false && strpos($name, '.gif') == false){
            return false;
        }
        return true;
    }
    public function upload_imagekit($image,$folder="images"){
        try {
            $settings = json_decode(Setting::where('setting', 'imagekit_options')->first()->option);
            $name = md5(time()) . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            if(!$this->image_validation($name)){
                return ['error'=>'true','message'=>'Geçersiz fotoğraf formatı'];
            }
            $site = (Setting::where('setting', 'site_name')->first())->option;
            $path = $settings->urlEndpoint .'/'. 'e-ticaret/' . $site . '/' . $folder . '/' . $name;

            $imageKit = new ImageKit(
                $settings->public_key,
                $settings->private_key,
                $settings->urlEndpoint
            );

            $result = $imageKit->uploadFiles(array(
                "file" => $image, // required
                "fileName" =>  $name, // required
                "useUniqueFileName" => true, // optional
                "folder" => 'e-ticaret/'.$site . '/' . $folder , // optional
                "isPrivateFile" => false, // optional
                "height"=>10,
                "width"=>10,
                "tags" => array("e-ticaret", "e-ticaret-".$site,"e-ticaret-".$site."-".$folder), // optional
            ));
            
            if (empty($result->err)) {
                
                return (['error' => 'false', 'message' => 'Fotoğraf yüklendi','success'=>$result->success]);
            }else{
                return (['error' => 'true', 'message' => $result->err]);
            }
            
        } catch (\Throwable $th) {
            return ['error'=>'true','message'=>$th->getMessage()];
        }
        return ['error' => 'true', 'message' => $th->getMessage()];
        

    }

    public function delete_imagekit($image_id){
        try {
            $settings = json_decode(Setting::where('setting', 'imagekit_options')->first()->option);
            $imageKit = new ImageKit(
                $settings->public_key,
                $settings->private_key,
                $settings->urlEndpoint
            );
            $result = $imageKit->deleteFile($image_id);
            if (empty($result->err)) {
                return ['error' => 'false', 'message' => 'Fotoğraf silindi'];
            }else{
                return ['error' => 'true', 'message' => $result->err];
            }
        } catch (\Throwable $th) {
            return ['error'=>'true','message'=>$th->getMessage()];
        }
    }
    
}
