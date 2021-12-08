<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Http\Controllers\DataCrypter;
class FileController extends Controller
{

    public function base64_clear($data) {
        $data = str_replace('data:image/png;base64,', '', $data);
        $data = str_replace('data:image/jpeg;base64,', '', $data);
        $data = str_replace('data:image/jpg;base64,', '', $data);
        $data = str_replace('data:image/gif;base64,', '', $data);
        $data = str_replace(' ', '+', $data);
        return base64_decode($data);
    }

    public function upload_server($image,$folder="images"){
        try {
            $site = (Setting::where('setting', 'site_name')->first())->option;
            $data = $this->base64_clear($image);
            $folderName = 'public/uploads/';
            $name = DataCrypter::uniqidR() . '.' . 'jpeg';
            $success = file_put_contents(public_path() . '/' . $site . '/uploads/' . $folder . '/' . $name, $data);
            $response = [
                'error' => 'false',
                'message' => 'Fotoğraf başarı ile yüklendi',
                'path' => env('APP_URL') . '/' . $site . '/uploads/' . $folder . '/' . $name,
                'name' => $name,
                'success' => $success
            ];
        } catch (\Exception $ex) {
            $response = [
                'error' => 'true',
                'message' => 'Fotoğraf yüklenirken bir hata oluştu',
                'exception' => $ex
            ];
        }
        return $response;
    }

    public function delete_server($image){
        try {
            $image = str_replace(env('APP_URL') , public_path(), $image);
            $success = unlink($image);
            $response = [
                'error' => 'false',
                'message' => 'Fotoğraf başarı ile silindi',
                'success' => $success
            ];
        } catch (\Exception $ex) {
            $response = [
                'error' => 'true',
                'message' => 'Fotoğraf silinirken bir hata oluştu',
                'exception' => $ex
            ];
        }
        return $response;
    }
}
