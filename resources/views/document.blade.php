<!DOCTYPE html>
<html lang="TR">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
  <link href="{{asset('assets/images/favicon.png')}}" rel="icon" />
  <title>Dökümantasyon | Mücahit E-Ticaret</title>
  <meta name="description" content="mucahit yazılım firmasının e-ticaret ürün dökümantasyonudur.">
  <meta name="author" content="mucahit.com.tr">
  <link rel="stylesheet" type="text/css" href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" />
  <link rel="stylesheet" type="text/css" href="{{asset('assets/vendor/font-awesome/css/all.min.css')}}" />
  <link rel="stylesheet" type="text/css" href="{{asset('assets/vendor/magnific-popup/magnific-popup.min.css')}}" />
  <link rel="stylesheet" type="text/css" href="{{asset('assets/vendor/highlight.js/styles/github.css')}}" />
  <link rel="stylesheet" type="text/css" href="{{asset('assets/css/stylesheet.css')}}" />
  <style>
    section {
      margin-top: 100px;
      padding-top: 100px;
      border-top: 1px solid #ddd;
    }
  </style>
</head>

<body class="box" data-spy="scroll" data-target=".idocs-navigation" data-offset="125">
  <div class="preloader">
    <div class="lds-ellipsis">
      <div></div>
      <div></div>
      <div></div>
      <div></div>
    </div>
  </div>
  <div id="main-wrapper">

    <header id="header" class="sticky-top">
      <nav class="primary-menu navbar navbar-expand-lg navbar-dropdown-dark">
        <div class="container-fluid">
          <button id="sidebarCollapse" class="navbar-toggler d-block d-md-none" type="button"><span></span><span class="w-75"></span><span class="w-50"></span></button>
          <a class="logo ml-md-3" href="https://mucahit.com.tr" title="mucahit"> <img height="75" src="{{asset('assets/images/logo.png')}}" alt="mucahit" /> </a>
          <span class="text-2 ml-2">v1.0</span>
          <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#header-nav"><span></span><span></span><span></span></button>

          <div id="header-nav" class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
              <!-- <li class="dropdown"> <a class="dropdown-toggle" href="#">Dropdown</a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Action</a></li>
                  <li class="dropdown"><a class="dropdown-item dropdown-toggle" href="#">Dropdown Action</a>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="index.html">Action</a></li>
                      <li><a class="dropdown-item" href="feature-header-dark.html">Another Action</a></li>
                      <li><a class="dropdown-item" href="feature-header-primary.html">Something Else Here</a></li>
                      <li><a class="dropdown-item" href="index-2.html">Another Link</a></li>
                    </ul>
                  </li>
                  <li><a class="dropdown-item" href="#">Another Action</a>
                  <li><a class="dropdown-item" href="#">Something Else Here</a></li>
                </ul>
              </li>
              <li><a target="_blank" href="https://themeforest.net/user/harnishdesign/portfolio?ref=HarnishDesign">Other Template</a></li> -->
              <li><a target="_blank" href="mailto:destek@mucahit.com.tr">Yardım Merkezi</a></li>
            </ul>
          </div>
          <ul class="social-icons social-icons-sm ml-lg-2 mr-2">
            <li class="social-icons-linkedin"><a data-toggle="tooltip" href="https://www.linkedin.com/company/mucahit" target="_blank" title="" data-original-title="Linkedin"><i class="fab fa-linkedin"></i></a></li>
            <!-- <li class="social-icons-facebook"><a data-toggle="tooltip" href="mailto:destek@mucahit.com.tr" target="_blank" title="" data-original-title="Email"><i class="fa fa-envelope"></i></a></li> -->
            <li class="social-icons-dribbble"><a data-toggle="tooltip" href="https://mucahit.com.tr" target="_blank" title="" data-original-title="Web"><i class="fab fa-dribbble"></i></a></li>
          </ul>
        </div>
      </nav>
    </header>
    <div id="content" role="main">

      <div class="idocs-navigation bg-light">
        <ul class="nav flex-column ">
          <li class="nav-item"><a class="nav-link active" href="#idocs_start">Başlarken</a>
            <ul class="nav flex-column">
              <li class="nav-item"><a class="nav-link" href="#idocs_pricing">Fiyatlandırma</a></li>
            </ul>
          </li>

          <li class="nav-item"><a class="nav-link " href="#idocs_users">Üyelik</a>
            <ul class="nav flex-column">
              <li class="nav-item"><a class="nav-link" href="#idocs_register">Kayıt işlemi</a></li>
              <li class="nav-item"><a class="nav-link" href="#idocs_login">Giriş işlemi</a></li>
              <li class="nav-item"><a class="nav-link" href="#idocs_logout">Çıkış işlemi</a></li>
              <li class="nav-item"><a class="nav-link" href="#idocs_password">Parola işlemleri</a></li>
              <li class="nav-item"><a class="nav-link" href="#idocs_active_account">Hesap Doğrulama</a></li>
              <li class="nav-item"><a class="nav-link" href="#idocs_re_active_account">Doğrulama Kodu Tekrarlama</a></li>
            </ul>
          </li>

          <li class="nav-item"><a class="nav-link " href="#idocs_product">Ürün</a>
            <ul class="nav flex-column">
              <li class="nav-item"><a class="nav-link" href="#idocs_p_list">Listeleme</a></li>
              <li class="nav-item"><a class="nav-link" href="#idocs_p_detail">Detay</a></li>
              <li class="nav-item"><a class="nav-link" href="#idocs_p_discover">Keşfet</a></li>
              <li class="nav-item"><a class="nav-link" href="#idocs_p_add">Ekle</a></li>
              <li class="nav-item"><a class="nav-link" href="#idocs_p_update">Düzenle</a></li>
              <li class="nav-item"><a class="nav-link" href="#idocs_p_delete">Sil</a></li>
            </ul>
          </li>

          <li class="nav-item"><a class="nav-link" href="#idocs_faq">SSS</a></li>
          <li class="nav-item"><a class="nav-link" href="#idocs_changelog">Güncelleme Notları</a>
            <ul class="nav flex-column">
              <li class="nav-item"><a class="nav-link" href="#v1-0">v1.0</a></li>
            </ul>
          </li>
        </ul>
      </div>

      <div class="idocs-content">
        <div class="container">

          <section id="idocs_start">
            <h1>Dökümantasyon</h1>
            <h3>Mücahit E-Ticaret Çözümü</h3>
            <p class="lead">Paketinize uygun dökümantasyon ve düzenleme işlemlerini gerçekleştirebilmek için bu dökümanı inceleyebilirsiniz.</p>
            <hr>
            <div class="row">
              <div class="col-sm-6 col-lg-4">
                <ul class="list-unstyled">
                  <li><strong>Versiyon:</strong> 1.0</li>
                  <li><strong>Yayıncı:</strong> <a href="https://mucahit.com.tr" target="_blank">mucahit</a></li>
                </ul>
              </div>
              <div class="col-sm-6 col-lg-4">
                <ul class="list-unstyled">
                  <li><strong class="font-weight-700">Oluşturulma:</strong> 16 Ekim, 2021</li>
                  <li><strong>Güncelleme:</strong> 28 Kasım, 2021</li>
                </ul>
              </div>
            </div>
            <p class="alert alert-info">Eğer bir problem ile karşılaşırsanız yardım merkezi adımlarını takip ederek bizlere iletebilirsiniz.<a target="_blank" href="mailto:destek@mucahit.com.tr">Yardım Merkezi</a></p>
          </section>

          <hr class="divider">

          <section id="idocs_pricing">
            <h2>Fiyatlandırma</h2>
            <p class="lead">Size uygun paket ve fiyatlar:</p>
            <ul>
              <li>Mücahit e-ticaret çözümü kullanım koşullarını satın aldığınız takdirde kabul etmiş sayılırsınız.</li>
            </ul>
            <h3 class="text-center">Aktif bir paket bulunamadı</h3>
          </section>

          <hr class="divider">

          <section id="idocs_users">
            <h1>Üyelik İşlemleri</h1>
            <p class="lead">Üyelik işlemleri için aşağıdaki adımları takip edin.</p>
          </section>

          <hr class="divider">

          <section id="idocs_register">
            <h2>Kayıt işlemi</h2>
            <button class="btn btn-primary">POST</button><strong style="margin:10px;font-size:22px;">/api/register</strong>
            <br><br>
            Gönderilecek parametreler ( "*" : zorunlu alan ) :
            <ul>
              <li><strong>email</strong> (*) : Geçerli eposta formatında string</li>
              <li><strong>surname</strong> (*) : string</li>
              <li><strong>password</strong> (*) : string</li>
            </ul>
            <h5>Header parametreler :</h5>
            <ul>
            </ul>
          </section>

          <hr class="divider">

          <section id="idocs_login">
            <h2>Giriş işlemi</h2>
            <button class="btn btn-primary">POST</button><strong style="margin:10px;font-size:22px;">/api/login</strong>
            <br><br>
            Gönderilecek parametreler ( "*" : zorunlu alan ) :
            <ul>
              <li><strong>email</strong> (*) : Geçerli eposta formatında string</li>
              <li><strong>password</strong> (*) : string</li>
            </ul>
            <h5>Header parametreler :</h5>
            <ul>
            </ul>
          </section>

          <hr class="divider">

          <section id="idocs_logout">
            <h2>Çıkış işlemi</h2>
            <button class="btn btn-primary">POST</button><strong style="margin:10px;font-size:22px;">/api/logout</strong>
            <br><br>
            Gönderilecek parametreler ( "*" : zorunlu alan ) :
            <ul>
              <li><strong>token</strong> (*) : string</li>
              <li><strong>tokenType</strong> (*) : string</li>
            </ul>
            <h5>Header parametreler :</h5>
            <ul>
            </ul>
          </section>

          <hr class="divider">

          <section id="idocs_password">
            <h2>Parola işlemleri</h2>
            <button class="btn btn-primary">POST</button><strong style="margin:10px;font-size:22px;">/api/forgot-password</strong>
            <br><br>
            <h4>Şifremi unuttum işlemi</h4>
            Gönderilecek parametreler ( "*" : zorunlu alan ) :
            <ul>
              <li><strong>email</strong> (*) : Geçerli eposta formatında string</li>
              <li><strong>password</strong> (*) : string</li>
            </ul>
            <h5>Header parametreler :</h5>
            <ul>
            </ul>
          </section>

          <section id="idocs_active_account">
            <h2>Hesap Doğrulama</h2>
            <button class="btn btn-primary">POST</button><strong style="margin:10px;font-size:22px;">/api/active-account</strong>
            <br><br>
            <h4>Hesabın aktif hale getirilmesi için eposta aracılığı ile gelen tokenin iletilmesi gerekmektedir</h4>
            Gönderilecek parametreler ( "*" : zorunlu alan ) :
            <ul>
              <li><strong>token</strong> (*) : String</li>
            </ul>
            <h5>Header parametreler :</h5>
            <ul>
            </ul>
          </section>

          <section id="idocs_re_active_account">
            <h2>Doğrulama Kodu Tekrarlama</h2>
            <button class="btn btn-primary">POST</button><strong style="margin:10px;font-size:22px;">/api/resend-activation-code</strong>
            <br><br>
            <h4>Kodun tekrar gönderilebilmesi için oturum açılması gerekiyor.</h4>
            </h4>
            Gönderilecek parametreler ( "*" : zorunlu alan ) :
            <ul>
              <li><strong>email</strong> (*) : Geçerli olması ve kayıtlı olması gereken string.</li>
            </ul>
            <h5>Header parametreler :</h5>
            <ul>
              <li><strong>Authorization</strong> (*) : Login sonrası tokenType ve token aralarında boşluk olacak şekilde</li>
            </ul>
          </section>


          <section id="idocs_product">
            <h1>Ürün İşlemleri</h1>
            <p class="lead">Ürün işlemleri için aşağıdaki adımları takip edin.</p>
          </section>

          <section id="idocs_p_list">
            <h2>Ürün Listeleme</h2>
            <button class="btn btn-warning">GET</button><strong style="margin:10px;font-size:22px;">/api/products</strong>
            <br><br>
            <h4>Üyelik işlemi gerektirmeden listeleme yapılabilir.</h4>
            </h4>
            Gönderilecek parametreler ( "*" : zorunlu alan ) :
            <ul>
              <li><strong>start</strong> : varsayılan 1 , 1 numaralı üründen listelemeye başlar, integer.</li>
              <li><strong>end</strong> : varsayılan 20 , 20 numaralı ürüne kadar listeler</li>
              <li><strong>allSearch</strong> : Tüm alanlarda arama , gereken string.</li>
              <li><strong>name</strong> : Ürün adı alanlarda arama , gereken string.</li>
              <li><strong>price</strong> : Ürün fiyatı alanlarda arama , gereken integer.</li>
              <li><strong>slug</strong> : Ürün link alanlarda arama , gereken string.</li>
              <li><strong>description</strong> : Ürün açıklama alanlarda arama , gereken string.</li>
              <li><strong>stock</strong> : Ürün stok sayı alanlarda arama , gereken integer.</li>
            </ul>
            <h5>Header parametreler :</h5>
            <ul>
              <li><strong>Authorization</strong> : Bu işlem kullanıcı hesabı var ise keşfet algoritmasında benzer ürün getirmesine olanak sağlamak adına ürünü kullanıcı görüntülenmesi olarak işleyecektir. Login sonrası tokenType ve token aralarında boşluk olacak şekilde</li>
            </ul>
          </section>

          <section id="idocs_p_detail">
            <h2>Ürün Detay</h2>
            <button class="btn btn-warning">GET</button><strong style="margin:10px;font-size:22px;">/api/products/{id-or-slug}</strong>
            <br><br>

            <h4>products/ 'dan sonrası ürün id numarası veya ürün link adresi gönderilir.</h4>
            </h4>
            Gönderilecek parametreler ( "*" : zorunlu alan ) :
            <ul>
            </ul>
            <h5>Header parametreler :</h5>
            <ul>
              <li><strong>Authorization</strong> : Bu işlem kullanıcı hesabı var ise keşfet algoritmasında benzer ürün getirmesine olanak sağlamak adına ürünü kullanıcı görüntülenmesi olarak işleyecektir. Login sonrası tokenType ve token aralarında boşluk olacak şekilde</li>
            </ul>
          </section>

          <section id="idocs_p_discover">
            <h2>Ürün Keşfet</h2>
            <button class="btn btn-warning">GET</button><strong style="margin:10px;font-size:22px;">/api/discover</strong>
            <br><br>
            <h4>Rastgele ürün listeler. Üyelik gerektirmez.</h4>
            </h4>
            Gönderilecek parametreler ( "*" : zorunlu alan ) :
            <ul>
              <li><strong>count</strong> : varsayılan 20 , 20 adet ürün getirir, integer.</li>
              <li><strong>allSearch</strong> : Tüm alanlarda arama , gereken string.</li>
              <li><strong>name</strong> : Ürün adı alanlarda arama , gereken string.</li>
              <li><strong>price</strong> : Ürün fiyatı alanlarda arama , gereken integer.</li>
              <li><strong>slug</strong> : Ürün link alanlarda arama , gereken string.</li>
              <li><strong>description</strong> : Ürün açıklama alanlarda arama , gereken string.</li>
              <li><strong>stock</strong> : Ürün stok sayı alanlarda arama , gereken integer.</li>
            </ul>
            <h5>Header parametreler :</h5>

            <ul>
              <li><strong>Authorization</strong> : Bu işlem kullanıcı hesabı var ise keşfet algoritmasında benzer ürün getirmesine olanak sağlamak adına ürünü kullanıcı görüntülenmesi olarak işleyecektir. Login sonrası tokenType ve token aralarında boşluk olacak şekilde</li>
            </ul>
          </section>

          <section id="idocs_p_add">
            <h2>Ürün Ekle</h2>
            <button class="btn btn-primary">POST</button><strong style="margin:10px;font-size:22px;">/api/seller/product/add</strong>
            <br><br>
            <h4><strong>Yönetici</strong> veya <strong>Satıcı</strong> üyeliği gerektirir.</h4>
            </h4>
            Gönderilecek parametreler ( "*" : zorunlu alan ) :
            <ul>
              <li><strong>name</strong> (*) : string.</li>
              <li><strong>description</strong> (*) : string.</li>
              <li><strong>price</strong> (*) : double.</li>
              <li><strong>category_id</strong> (*) : integer.</li>
              <li><strong>stock</strong> : stok sayısı, varsayılan 0 ,integer.</li>
            </ul>
            <h5>Header parametreler :</h5>
            <ul>
              <li><strong>Authorization</strong> (*) : Login sonrası tokenType ve token aralarında boşluk olacak şekilde</li>
            </ul>
          </section>

          <section id="idocs_p_update">
            <h2>Ürün Güncelle</h2>
            <button class="btn btn-primary">POST</button><strong style="margin:10px;font-size:22px;">/api/seller/product/update</strong>
            <br><br>
            <h4><strong>Yönetici</strong> veya <strong>Satıcı</strong> üyeliği gerektirir.</h4>
            </h4>
            Gönderilecek parametreler ( "*" : zorunlu alan ) :
            <ul>
              <li><strong>name</strong> (*) : string.</li>
              <li><strong>description</strong> (*) : string.</li>
              <li><strong>price</strong> (*) : double.</li>
              <li><strong>category_id</strong> (*) : integer.</li>
              <li><strong>stock</strong> : stok sayısı, varsayılan 0 ,integer.</li>
            </ul>
            <h5>Header parametreler :</h5>
            <ul>
              <li><strong>Authorization</strong> (*) : Login sonrası tokenType ve token aralarında boşluk olacak şekilde</li>
            </ul>
          </section>

          <section id="idocs_p_delete">
            <h2>Ürün Sil</h2>
            <button class="btn btn-primary">POST</button><strong style="margin:10px;font-size:22px;">/api/seller/product/delete</strong>
            <br><br>
            <h4><strong>Yönetici</strong> veya <strong>Satıcı</strong> üyeliği gerektirir.</h4>
            </h4>
            Gönderilecek parametreler ( "*" : zorunlu alan ) :
            <ul>
              <li><strong>product_id</strong> (*) : integer.</li>
            </ul>
            <h5>Header parametreler :</h5>
            <ul>
              <li><strong>Authorization</strong> (*) : Login sonrası tokenType ve token aralarında boşluk olacak şekilde</li>
            </ul>
          </section>



          <hr class="divider">
          <section id="idocs_faq">
            <h2>SSS</h2>
            <p class="text-4">Sıkça sorulan soruların yanıtları aşağıdaki gibidir</p>

            <div class="row">
              <div class="col-lg-12">
                <div class="accordion accordion-alterate arrow-right" id="popularTopics">

                  <div class="card">
                    <div class="card-header" id="heading1">
                      <h5 class="mb-0"> <a href="#" class="collapsed" data-toggle="collapse" data-target="#collapse1" aria-expanded="false" aria-controls="collapse1">Ne zaman yayınlanacak?</a> </h5>
                    </div>
                    <div id="collapse1" class="collapse" aria-labelledby="heading1" data-parent="#popularTopics">
                      <div class="card-body">bitince. </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>

          </section>

          <!-- <hr class="divider"> -->

          <!-- <section id="idocs_source_credits">
            <h2>Source & Credits</h2>
            <h4>Images:</h4>
            <ul>
              <li>Unsplash - <a target="_blank" href="https://unsplash.com/">https://unsplash.com/</a></li>
            </ul>
            <h4>Fonts:</h4>
            <ul>
              <li>Icons Font Face - <a target="_blank" href="https://fontawesome.com/">https://fontawesome.com/</a></li>
            </ul>
            <h4>Scripts:</h4>
            <ul>
              <li>jQuery - <a target="_blank" href="http://www.jquery.com/">http://www.jquery.com/</a></li>
              <li>Bootstrap 4 - <a target="_blank" href="http://getbootstrap.com/">http://getbootstrap.com/</a></li>
              <li>Highlight Js - <a target="_blank" href="https://highlightjs.org/">https://highlightjs.org/</a></li>
              <li>jQuery easing - <a target="_blank" href="http://gsgd.co.uk/sandbox/jquery/easing/">http://gsgd.co.uk/sandbox/jquery/easing/</a></li>
              <li>Magnific Popup - <a target="_blank" href="http://dimsemenov.com/plugins/magnific-popup/">http://dimsemenov.com/plugins/magnific-popup/</a></li>
            </ul>
          </section> -->

          <!-- <hr class="divider"> -->

          <!-- <section id="idocs_support">
            <h2>Support</h2>
            <p>If this documentation doesn't answer your questions, So, Please send us Email via <a class="btn btn-primary" target="_blank" href="https://themeforest.net/user/harnishdesign#contact">Item Support Page</a></p>
            <p> We are located in GMT +5:30 time zone and we answer all questions within 12-24 hours in weekdays. In some rare cases the waiting time can be to 48 hours. (except holiday seasons which might take longer).</p>
            <div class="alert alert-warning mb-4"><span class="badge badge-danger text-uppercase">Note:</span> While we aim to provide the best support possible, please keep in mind that it only extends to verified buyers and only to issues related to our template like bugs and errors. Custom modifications or third party module implementations are not included.</div>
            <h4>Don’t forget to Rate this template
              <i class="fas fa-star text-warning"></i> <i class="fas fa-star text-warning"></i> <i class="fas fa-star text-warning"></i> <i class="fas fa-star text-warning"></i> <i class="fas fa-star text-warning"></i>
            </h4>
            <div class="alert alert-success">
              Please Add your Review (Opinion) for Our template. It would be a great support for us.<br>
              Go to your <strong>Themeforest Profile</strong> > <strong>Downloads Tab</strong> > & then You can Rate & Review for our template.<br>
              Thank You.
            </div>
          </section> -->

          <!-- <hr class="divider"> -->

          <!-- <section id="idocs_templates">
            <h2>More Templates</h2>
            <p class="lead">Checkout Our Below Premium Templates</p>

            <div class="row">
              <div class="col-4 my-3 text-center">
                <a target="_blank" href="https://themeforest.net/item/x/28476751?ref=HarnishDesign"><img class="img-fluid border" src="assets/images/templates/simone.jpg" alt="">
                  <h6 class="pt-2">Simone - Personal Portfolio Template</h6>
                </a>
              </div>
              <div class="col-4 my-3 text-center">
                <a target="_blank" href="https://themeforest.net/item/x/26454458?ref=HarnishDesign"><img class="img-fluid border" src="assets/images/templates/kenil.jpg" alt="">
                  <h6 class="pt-2">Kenil - Responsive Bootstrap 4 One Page Portfolio Template</h6>
                </a>
              </div>
              <div class="col-4 my-3 text-center">
                <a target="_blank" href="https://themeforest.net/item/x/26313677?ref=HarnishDesign"><img class="img-fluid border" src="assets/images/templates/doon.jpg" alt="">
                  <h6 class="pt-2">Doon - One Page Parallax HTML Template</h6>
                </a>
              </div>

              <div class="col-4 my-3 text-center">
                <a target="_blank" href="https://themeforest.net/item/x/27172933?ref=HarnishDesign"><img class="img-fluid border" src="assets/images/templates/domainx.jpg" alt="">
                  <h6 class="pt-2">DomainX- Domain for Sale HTML Template</h6>
                </a>
              </div>
              <div class="col-4 my-3 text-center">
                <a target="_blank" href="https://themeforest.net/item/x/28848609?ref=HarnishDesign"><img class="img-fluid border" src="assets/images/templates/oxyy.jpg" alt="">
                  <h6 class="pt-2">Oxyy - Login and Register Form HTML Templates</h6>
                </a>
              </div>
              <div class="col-4 my-3 text-center">
                <a target="_blank" href="https://themeforest.net/item/x/27034971?ref=HarnishDesign"><img class="img-fluid border" src="assets/images/templates/koice.jpg" alt="">
                  <h6 class="pt-2">Koice - Invoice HTML Template</h6>
                </a>
              </div>

              <div class="col-4 my-3 text-center">
                <a target="_blank" href="https://themeforest.net/item/x/24017808?ref=HarnishDesign"><img class="img-fluid border" src="assets/images/templates/payyed.jpg" alt="">
                  <h6 class="pt-2">Payyed - Money Transfer and Online Payments HTML Template</h6>
                </a>
              </div>
              <div class="col-4 my-3 text-center">
                <a target="_blank" href="https://themeforest.net/item/x/22375065?ref=HarnishDesign"><img class="img-fluid border" src="assets/images/templates/quickai.jpg" alt="">
                  <h6 class="pt-2">Quickai - Recharge & Bill Payment, Booking HTML5 Template</h6>
                </a>
              </div>
            </div>

            <p class="text-center"><a class="btn btn-lg btn-primary my-4" target="_blank" href="https://themeforest.net/user/harnishdesign/portfolio?ref=HarnishDesign">Our Portfolio</a></p>

          </section> -->

          <hr class="divider">


          <section id="idocs_changelog">
            <h2>Güncelleme Notları</h2>

            <hr class="divider">
            <h3 id="v1-0">Versiyon 1.0 <small class="text-muted">( yayınlanmadı )</small></h3>
            <p>Aktif Çalışmalar</p>
            <ul class="changelog">
              <li><span class="badge badge-info">Yapılıyor</span> Gelişmiş arayüz</li>
              <li><span class="badge badge-info">Yapılıyor</span> Modern yönetim paneli</li>
              <li><span class="badge badge-info">Yapılıyor</span> Entegrasyon arka plan servisi</li>
            </ul>
            <!-- <h3 id="v1-0">Version 1.0 <small class="text-muted">(8 April, 2020)</small></h3>
            <p>Initial Release</p>
            <ul class="changelog">
              <li><span class="badge badge-success">Added</span> Your information here for added new feature</li>
              <li><span class="badge badge-danger">Fixed</span> Some minor bugs for browser compatibility</li>
              <li><span class="badge badge-danger">Fixed</span> Some minor bugs for responsive</li>
              <li><span class="badge badge-info">Updated</span> FontAwesome to Latest Version</li>
              <li><span class="badge badge-info">Updated</span> Bootstrap to Latest Version</li>
              <li><span class="badge badge-info">Updated</span> Improvements in CSS and JS</li>
            </ul> -->
          </section>

        </div>
      </div>

    </div>
    <footer id="footer" class="section bg-dark footer-text-light">
      <div class="container">
        <ul class="social-icons social-icons-lg social-icons-muted justify-content-center mb-3">
          <li><a data-toggle="tooltip" href="https://www.linkedin.com/company/mucahit" target="_blank" title="" data-original-title="Linkedin"><i class="fab fa-linkedin"></i></a></li>
          <li><a data-toggle="tooltip" href="https://mucahit.com.tr" target="_blank" title="" data-original-title="Web"><i class="fab fa-dribbble"></i></a></li>
          <li><a data-toggle="tooltip" href="https://github.com/mucahit-tr" target="_blank" title="" data-original-title="GitHub"><i class="fab fa-github"></i></a></li>
        </ul>
        <p class="text-center"> 2021 &copy; Tüm hakları saklıdır.</p>
        <p class="text-2 text-center mb-0">Powered by <a class="btn-link" target="_blank" href="https://mucahit.com.tr">mucahit</a></p>
      </div>
    </footer>
    <!-- Footer end -->

  </div>
  <!-- Document Wrapper end -->

  <!-- Back To Top -->
  <a id="back-to-top" data-toggle="tooltip" title="Back to Top" href="javascript:void(0)"><i class="fa fa-chevron-up"></i></a>

  <!-- JavaScript
============================ -->
  <script src="{{asset('assets/vendor/jquery/jquery.min.js')}}"></script>
  <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <!-- Highlight JS -->
  <script src="{{asset('assets/vendor/highlight.js/highlight.min.js')}}"></script>
  <!-- Easing -->
  <script src="{{asset('assets/vendor/jquery.easing/jquery.easing.min.js')}}"></script>
  <!-- Magnific Popup -->
  <script src="{{asset('assets/vendor/magnific-popup/jquery.magnific-popup.min.js')}}"></script>
  <!-- Custom Script -->
  <script src="{{asset('assets/js/theme.js')}}"></script>
</body>

</html>