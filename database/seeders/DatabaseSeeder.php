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

        \App\Models\Gallery::insert([
            ['name'=>'Gereksiz Fotoğraflar'],
            ['name' => 'Önemli Fotoğraflar'],
            ['name'=>'Diğer Fotoğraflar']
        ]);
        \App\Models\ImageDriver::insert([
            ['name' => 'cloud'],
            ['name' => 'imagekit'],
            ['name' => 'server'],
        ]);

        \App\Models\Warranty::insert([
            ['name'=>'3 aylık garanti', 'description'=>'3 ay boyunca ücretsiz destek'],
            ['name'=>'6 aylık garanti', 'description'=>'6 ay boyunca ücretsiz destek'],
            ['name'=>'9 aylık garanti', 'description'=>'9 ay boyunca ücretsiz destek'],
            ['name'=>'12 aylık garanti','description'=> '12 ay boyunca ücretsiz destek'],
            ['name'=>'24 aylık garanti','description'=> '24 ay boyunca ücretsiz destek'],
            ['name'=>'36 aylık garanti','description'=> '36 ay boyunca ücretsiz destek']
        ]);

        \App\Models\Unit::insert([
            ['name' => 'Adet','symbol'=>'Adet'],
            ['name' => 'Kilogram','symbol'=>'KG'],
            ['name' => 'Litre','symbol'=>'LT'],
            ['name' => 'Metre', 'symbol' => 'M'],
            ['name' => 'Saniye', 'symbol' => 'S'],
            ['name' => 'Megabyte','symbol'=>'Mb'],
            ['name'=>'Amper' , 'symbol'=>'A']
        ]);

        \App\Models\User::insert([
            [
                'id' => 1,
                'name' => 'ETicaret',
                'surname' => 'Admin',
                'code' => DataCrypter::md5R(uniqid()),
                'status_id' => 5,
                'email' => 'mucahit@dehasoft.com.tr',
                'password' => DataCrypter::md5R('123456789')
            ]
        ]);

        \App\Models\UserIp::insert([
            ['id' => 1, 'user_id' => 1, 'register_ip' => 'admin ']
        ]);


        \App\Models\Image::insert([
            [
                'name' => 'test_image',
                'type' => 'url',
                'uploaded_user_id'=>1,
                'image_driver_id' => 1,
                'gallery_id' => 1,
                'path' => 'https://png.pngtree.com/png-vector/20191129/ourmid/pngtree-office-checklist-icon-business-checklist-survey-test-icon-png-image_2047566.jpg'
            ], [
                'name' => 'test_image2',
                'type' => 'url',
                'uploaded_user_id'=>1,
                'image_driver_id'=>1,
                'gallery_id' => 2,
                'path' => 'https://png.pngtree.com/png-vector/20191129/ourmid/pngtree-office-checklist-icon-business-checklist-survey-test-icon-png-image_2047566.jpg'
            ], [
                'name' => 'test_image3',
                'type' => 'url',
                'uploaded_user_id'=>1,
                'image_driver_id'=>1,
                'gallery_id' => 3,
                'path' => 'https://png.pngtree.com/png-vector/20191129/ourmid/pngtree-office-checklist-icon-business-checklist-survey-test-icon-png-image_2047566.jpg'
            ],
        ]);

        \App\Models\UserImage::insert([
            [
                'image_id' => 1,
                'user_id' => 1,
            ]
        ]);

        \App\Models\Category::insert([
            ['id' => 1,  'image_id' => 1, 'slug' => 'kisisel-bakim', 'name' => 'Kişisel Bakım', 'parent_id' => null],
            ['id' => 2,  'image_id' => 1, 'slug' => 'saglik', 'name' => 'Sağlık', 'parent_id' => null],
            ['id' => 3,  'image_id' => 1, 'slug' => 'giyim', 'name' => 'Giyim', 'parent_id' => null],
            ['id' => 4,  'image_id' => 1, 'slug' => 'eglence', 'name' => 'Eğlence', 'parent_id' => null],
            ['id' => 5,  'image_id' => 1, 'slug' => 'parfum', 'name' => 'Parfüm', 'parent_id' => 1],
            ['id' => 6,  'image_id' => 1, 'slug' => 'agri-kesici', 'name' => 'Ağrı Kesici', 'parent_id' => 2],
            ['id' => 7,  'image_id' => 1, 'slug' => 'elbise', 'name' => 'Elbise', 'parent_id' => 1],
            ['id' => 8,  'image_id' => 1, 'slug' => 'stres-carki', 'name' => 'Stres Çarkı', 'parent_id' => 2]
        ]);


        \App\Models\Product::insert([
            ['id' => 1,'warranty_id'=>1,'unit_id'=>1, 'stock' => 55, 'price' => 12.34, 'description' => 'lorem ipsum sit dollar felan nokta test test aciklama test içerik açıklaması e ticaret dehasoft test açıklama metni lorem ipsum ', 'name' => 'Çark', 'slug' => 'cark', 'category_id' => 8],
            ['id' => 2,'warranty_id'=>2,'unit_id'=>1, 'stock' => 55, 'price' => 5255.36, 'description' => 'lorem ipsum sit dollar felan nokta test test aciklama test içerik açıklaması e ticaret dehasoft test açıklama metni lorem ipsum ', 'name' => 'Kırmızı Çark', 'slug' => 'kirmizi-cark',  'category_id' => 8],
            ['id' => 3,'warranty_id'=>3,'unit_id'=>1, 'stock' => 55, 'price' => 9193.00, 'description' => 'lorem ipsum sit dollar felan nokta test test aciklama test içerik açıklaması e ticaret dehasoft test açıklama metni lorem ipsum ', 'name' => 'Sarı Çark', 'slug' => 'sari-cark',  'category_id' => 8],
            ['id' => 4,'warranty_id'=>4,'unit_id'=>1, 'stock' => 55, 'price' => 33.00, 'description' => 'lorem ipsum sit dollar felan nokta test test aciklama test içerik açıklaması e ticaret dehasoft test açıklama metni lorem ipsum ', 'name' => 'Mavi Çark', 'slug' => 'mavi-cark',  'category_id' => 8],
            ['id' => 5,'warranty_id'=>5,'unit_id'=>1, 'stock' => 55, 'price' => 85.99, 'description' => 'lorem ipsum sit dollar felan nokta test test aciklama test içerik açıklaması e ticaret dehasoft test açıklama metni lorem ipsum ', 'name' => 'Yeşil Çark', 'slug' => 'yesil-cark',  'category_id' => 8],
            ['id' => 6,'warranty_id'=>6,'unit_id'=>1, 'stock' => 55, 'price' => 99.99, 'description' => 'lorem ipsum sit dollar felan nokta test test aciklama test içerik açıklaması e ticaret dehasoft test açıklama metni lorem ipsum ', 'name' => 'Turuncu Çark', 'slug' => 'turuncu-cark', 'category_id' => 8],
       ]);
        \App\Models\Product::factory()->count(2200)->create();

        \App\Models\ProductImage::insert([
            [
                'id' => 1,
                'product_id'=>1,
                'image_id'=>1,
            ]
        ]);

        \App\Models\Discount::insert([
            [
                'id' => 1,
                'name' => 'test_discount',
                'description' => 'test discount description',
                'percent'=>10,
                'start_date' => '2021-02-02 22:34:00',
                'end_date' => '2021-12-12 22:34:00',
                'coupon' => 'TEST-COUPON',
                'max_uses'=>10,
                'max_discount_amount'=>100,
                'max_discount_amount_user'=>200,
                'min_order_amount'=>100
            ], [
                'id' => 2,
                'name' => 'test_discount2',
                'description' => 'test discount description2',
                'percent' => 20,
                'start_date' => '2021-02-02 22:34:00',
                'end_date' => '2021-12-12 22:34:00',
                'coupon' => 'TEST-COUPON2',
                'max_uses' => 10,
                'max_discount_amount' => 100,
                'max_discount_amount_user' => 200,
                'min_order_amount' => 100
            ], [
                'id' => 3,
                'name' => 'test_discount3',
                'description' => 'test discount description3',
                'percent' => 30,
                'start_date' => '2021-02-02 22:34:00',
                'end_date' => '2021-12-12 22:34:00',
                'coupon' => 'TEST-COUPON3',
                'max_uses' => 30,
                'max_discount_amount' => 300,
                'max_discount_amount_user' => 300,
                'min_order_amount' => 300
            ]
        ]);

        \App\Models\ProductOffer::insert([
            [
                'id' => 1,
                'product_id' => 1,
                'discount_id' => 1
            ], [
                'id' => 2,
                'product_id' => 2,
                'discount_id' => 2
            ], [
                'id'=>3,
                'product_id' => 1,
                'discount_id' => 3
            ]
        ]);

        \App\Models\CategoryOffer::insert([
            [
                'id' => 1,
                'category_id' => 1,
                'discount_id' => 1
            ], [
                'id' => 2,
                'category_id' => 2,
                'discount_id' => 2
            ], [
                'id' => 3,
                'category_id' => 3,
                'discount_id' => 3
            ]
        ]);

        \App\Models\Setting::insert([
            ['setting'=> 'site_name', 'option' => 'DehaSoft'],
            ['setting'=> 'site_description', 'option' => 'DehaSoft'],
            ['setting'=> 'site_keywords', 'option' => 'DehaSoft'],
            ['setting'=> 'site_email', 'option' => 'info@dehasoft.com.tr'],
            ['setting' =>'name','option' => 'Dehasoft E-Ticaret Demo'],
            ['setting'=> 'copyright', 'option' => 'Copyright © 2021 Dehasoft'],
            ['setting'=> 'address', 'option' => 'Oruç Reis Mah. 6.Sokak No:16/A Giyimkent/Esenler'],
            ['setting'=> 'phone', 'option' => '+90 212 535 04 74'],
            ['setting'=> 'email', 'option' => 'contact@e-ticaret.dehasoft.com.tr'],
            ['setting'=> 'site', 'option'=> 'https://dehasoft.com.tr'],
            ['setting'=> 'front_security' , 'option'=> 'false'],
            ['setting'=> 'strange_security', 'option'=>'false'],
            ['setting'=> 'strange_security_level', 'option'=> 'hard'],
            ['setting'=> 'front_url', 'option'=> 'https://e-ticaret.dehasoftc.om.tr'],
            ['setting'=> 'image_driver', 'option'=>'imagekit'],
            ['setting'=> 'imagekit_options', 'option'=> '{"public_key":"public_DgtfAgBqm0JM2CaDlYNbAdb4jNA=","urlEndpoint":"https://ik.imagekit.io/djw8ypcvuyq","private_key":"private_LMNaOb1AWN2Z+/KWmw3dxPUJDwI="}'],
            ['setting'=>'notify_driver','option'=>'pusher'],
            ['setting'=>'pusher_options','option'=>'{"app_id":"5a9b8f8b8f8b8f8b8f8b8f8b","app_key":"5a9b8f8b8f8b8f8b8f8b8f8b","app_secret":"5a9b8f8b8f8b8f8b8f8b8f8b","cluster":"eu","encrypted":"true"}'],
            ['setting'=>'notify_options','option'=>'{"notify_url":"https://e-ticaret.dehasoftc.om.tr/notify"}'],
            ['setting'=>'mail_driver','option'=>'smtp'],
            ['setting'=>'mail_host','option'=>'mail.e-ticaret.dehasoft.com.tr'],
            ['setting'=>'mail_port','option'=>'587'],
            ['setting'=>'mail_username','option'=>'contact@e-ticaret.dehasoft.com.tr'],
            ['setting'=>'mail_password','option'=> 'Ko4_ib14'],
            ['setting'=>'mail_encryption','option'=>'tls'],
            ['setting'=>'mail_from_address','option'=> 'contact@e-ticaret.dehasoft.com.tr']

        ]);



    }
}
