<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Http\Controllers\DataCrypter;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        \App\Models\UserStatus::insert([
            ['id'=>1,'name' => 'Ziyaretçi','admin'=>0,'seller'=>0, 'customer' => 0],
            ['id'=>2,'name' => 'Onaysız','admin'=>0,'seller'=>0, 'customer' => 0],
            ['id'=>3, 'name' => 'Müşteri', 'admin' => 0, 'seller' => 0, 'customer' => 1],
            ['id' => 4, 'name' => 'Satıcı','admin'=>0,'seller'=>1,'customer'=>1 ],
            ['id' => 5, 'name' => 'Yönetici', 'admin' => 1, 'seller' => 1, 'customer' => 1],
        ]);

        \App\Models\UserImage::insert([
            ['id' => 1, 'name' => 'test_image', 'type' => 'url', 'path' => 'https://png.pngtree.com/png-vector/20191129/ourmid/pngtree-office-checklist-icon-business-checklist-survey-test-icon-png-image_2047566.jpg']
        ]);

        \App\Models\User::insert([
            ['id' => 1, 'image_id' => 1, 'name' => 'ETicaret', 'surname'=>'Admin', 'code' => DataCrypter::md5R(uniqid()), 'status_id' => 5, 'email' => 'mucahit@dehasoft.com.tr', 'password' => DataCrypter::md5R('123456789')]
        ]);
        \App\Models\UserIp::insert([
            ['id' => 1, 'user_id' => 1, 'register_ip' => 'admin ']
        ]);

        \App\Models\ProductImage::insert([
            ['id' => 1, 'user_id'=>1,'name' => 'test_image', 'type' => 'url', 'path' => 'https://png.pngtree.com/png-vector/20191129/ourmid/pngtree-office-checklist-icon-business-checklist-survey-test-icon-png-image_2047566.jpg']
        ]);

        \App\Models\Icon::insert([
            ['id' => 1,'user_id' => 1, 'name' => 'test_icon', 'type' => 'base64', 'path' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAABmJLR0QA/wD/AP+gvaeTAAACnklEQVRoge2ay2pUQRCGPy8wM94dNU7cmaiYZzDErSF5A80DCC4UxfEZXAiCoLiMD2EkInjbSMSdxszoLgMqGq9gMGFc9CnoHE7m0l2n+yTkh4I6F6rqn6qu6e7TsIViYZuirSowBowCI8AwMADsTp7/AT4BH4G3wEvgKbCkGIMzysAUMAusAu0+ZQV4BFxIbAVHBbgGtHoItldpAVcT20EwiSkPLQJp+QBM5EmgDNzOkUBapoFd2iQGgTcBSYjMATUtEkOYdIcmIdJMYvDCALAQkYQ9bpwzUyFOOa0nr3Fs0fc9HS8D14FjidSTez427/ZLYtLTYTsJPI26gt3xXklU0Bncgxm2awp2m2SU2PYMZ5dQ6BJkz+N2KNgdBi52e6mM3rQjq7RuKNlepMvAn1Jy1MYM7Dq6g92W852IzCo6yltm7MDtOq4Cn9Gp4xBYBQ4D32HtYD/LxiEBJtYxubCJnAkfizdGRdlp3RxRdLDeErqt6APgtCh2Rk4qOwmBU6LYRA4pOpDOkjeqothE9gRwrI19omRNUTYkbCK/o0Xhjp+i2ES+RgjEF99EsYk0IgTiiwVRbCLvIgTii3lRbCIvIgTii+dZNw9i9mI1Z6hpaNr+B+wXw3ZGloAnPf4SRcBj4IdcpP9HpsPG4oUHnR6WMMvIopdWi9RSN52RZeBWJ6YFwU3gb7eXNLaDusHHdoM+dhwnCkzkXK8kBPc8HeYhd/olASZ9cwUIXuQVphk54QjwvgAkmsBRVxKCocRQTBLHfUkIapjvEzHKyTsTaZQI/zE010/V4+Rbag0cWqwrysAVdKczi8BlIp2AKGGOX8zgtgRYAR5idtadWyvoHqo5wNpDNScw7Xtv8vwX8AVTOvOYRdEzrKn4FjYT/gNeSWt2b9vWyAAAAABJRU5ErkJggg==']
        ]);

        \App\Models\Image::insert([
            ['id' => 1, 'name' => 'test_image', 'type' => 'url', 'path' => 'https://png.pngtree.com/png-vector/20191129/ourmid/pngtree-office-checklist-icon-business-checklist-survey-test-icon-png-image_2047566.jpg']
        ]);

        

        \App\Models\Category::insert([
            ['id' => 1, 'image_id' => 1, 'icon_id' => 1, 'slug' => 'kisisel-bakim', 'name' => 'Kişisel Bakım', 'parent_id' => null],
            ['id' => 2, 'image_id' => 1, 'icon_id' => 1, 'slug' => 'saglik', 'name' => 'Sağlık', 'parent_id' => null],
            ['id' => 3, 'image_id' => 1, 'icon_id' => 1, 'slug' => 'giyim', 'name' => 'Giyim', 'parent_id' => null],
            ['id' => 4, 'image_id' => 1, 'icon_id' => 1, 'slug' => 'eglence', 'name' => 'Eğlence', 'parent_id' => null],
            ['id' => 5, 'image_id' => 1, 'icon_id' => 1, 'slug' => 'parfum', 'name' => 'Parfüm', 'parent_id' => 1],
            ['id' => 6, 'image_id' => 1, 'icon_id' => 1, 'slug' => 'agri-kesici', 'name' => 'Ağrı Kesici', 'parent_id' => 2],
            ['id' => 7, 'image_id' => 1, 'icon_id' => 1, 'slug' => 'elbise', 'name' => 'Elbise', 'parent_id' => 3],
            ['id' => 8, 'image_id' => 1, 'icon_id' => 1, 'slug' => 'stres-carki', 'name' => 'Stres Çarkı', 'parent_id' => 4]
        ]);

        \App\Models\Product::insert([
            ['id' => 1, 'stock' => 55, 'price' => 12.34, 'description' => 'lorem ipsum sit dollar felan nokta test test aciklama test içerik açıklaması e ticaret dehasoft test açıklama metni lorem ipsum ', 'name' => 'Çark', 'slug' => 'cark', 'image_id' => 1, 'category_id' => 8],
            ['id' => 2, 'stock' => 55, 'price' => 5255.3686, 'description' => 'lorem ipsum sit dollar felan nokta test test aciklama test içerik açıklaması e ticaret dehasoft test açıklama metni lorem ipsum ', 'name' => 'Kırmızı Çark', 'slug' => 'kirmizi-cark', 'image_id' => 1, 'category_id' => 8],
            ['id' => 3, 'stock' => 55, 'price' => 945193.00, 'description' => 'lorem ipsum sit dollar felan nokta test test aciklama test içerik açıklaması e ticaret dehasoft test açıklama metni lorem ipsum ', 'name' => 'Sarı Çark', 'slug' => 'sari-cark', 'image_id' => 1, 'category_id' => 8],
            ['id' => 4, 'stock' => 55, 'price' => 33.00, 'description' => 'lorem ipsum sit dollar felan nokta test test aciklama test içerik açıklaması e ticaret dehasoft test açıklama metni lorem ipsum ', 'name' => 'Mavi Çark', 'slug' => 'mavi-cark', 'image_id' => 1, 'category_id' => 8],
            ['id' => 5, 'stock' => 55, 'price' => 85.99, 'description' => 'lorem ipsum sit dollar felan nokta test test aciklama test içerik açıklaması e ticaret dehasoft test açıklama metni lorem ipsum ', 'name' => 'Yeşil Çark', 'slug' => 'yesil-cark', 'image_id' => 1, 'category_id' => 8],
            ['id' => 6, 'stock' => 55, 'price' => 99.99, 'description' => 'lorem ipsum sit dollar felan nokta test test aciklama test içerik açıklaması e ticaret dehasoft test açıklama metni lorem ipsum ', 'name' => 'Turuncu Çark', 'slug' => 'turuncu-cark', 'image_id' => 1, 'category_id' => 8],
        ]);
      
    }
}
