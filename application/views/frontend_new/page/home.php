<!-- Masthead -->
    <!-- <header class="masthead text-white text-center">
      <div class="overlay"></div>
      <div class="container">
        <div class="row">
          <div class="col-xl-9 mx-auto">
            <h1 class="mb-5">Build a landing page for your business or project and generate more leads!</h1>
          </div>
          <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
            
          </div>
        </div>
      </div>
    </header> -->
    <header id="home" class="slideshow">
      <?php 
        $slideshow = $this->frontend->slideshow("list_data");
      ?>
      <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
          <?php 
          foreach($slideshow as $key => $a): 
            $active = "";
            if($key == 0):
              $active = "active";
            endif;
            echo '<li data-target="#carouselExampleIndicators" data-slide-to="'.$key.'" class="'.$active.'"></li>';
          endforeach;
          ?>
        </ol>
        <div class="carousel-inner" role="listbox">
          <?php 
          foreach($slideshow as $key => $a): 
            $active = "";
            if($key == 0):
              $active = "active";
            endif;
            $list_btn = $a->ButtonLink;

          ?>
          <div class="carousel-item wb-max-height-800p <?= $active; ?>" style="background-image: url(<?= $a->Image; ?>);background-repeat: no-repeat;
    background-attachment: fixed;">
            <div class="carousel-caption d-md-block <?= $a->Position; ?>">
              <h3 class="title" style="color:<?= $a->NameColor." !important";?>"><?= $a->Name; ?></h3>
              <p class="text" style="color:<?= $a->NameColor." !important";?>">
                <?= $a->Description; ?>
              </p>
              <div class="div-btn">
                <ul>
                <?php 
                if(count($list_btn) > 0):
                  foreach ($list_btn as $b):
                    if($b->BtnName):
                      echo '<li><a href="'.$b->BtnLink.'" class="btn btn-custom "><span class="label-btn">'.$b->BtnName.'</span></a></li>';
                    endif;
                  endforeach;
                endif;
                ?>
                </ul>                
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
    </header>
    <section id="about-us" class="section-normal bg-light text-center wb-min-height-100vh" style="min-height: 0px;display: none">
      <div class="container container-gallery">
        <div class="section-title" data-aos="fade-down">Tentang Kami</div>
        <div class="text-center">
          <div class="border-bottom-blue"></div>
        </div>
        <div class="row mt-5r">
          <div class="col-sm-6">
            <div class="preview-mobile aos-animate" data-aos="fade-down" style="background-image: url('../aset/frontend/img/iphone.png');">
              <div class="swiper-container swiper-mobile">
                <div class="swiper-wrapper">
                  <?php foreach(range(1,8) as $a): ?>
                    <div class="swiper-slide"><img src="<?= base_url('aset/frontend/img/'.$a.".jpeg");?>"></div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="wb-max-width-70 text-left">
              <b class="title">POS (Point of Sales) / Cashier</b>
              <p>
                Pipesys adalah aplikasi gratis POS (Point of Sales) / kasir untuk handphone android. Applikasi ini membantu Anda untuk mengelola penjualan product retail.
                <br/>
                Fitur sebagai berikut:
              </p>
              <ul style="padding-left: 20px;">
                <li>Point of sales atau kasir, mendukung pengembalian produk.</li>
                <li>Menyediakan Transaksi Tunda and Batal untuk penjualan.</li>
                <li>Fitur ini cocok digunakan untuk bisnis kafe atau restoran.</li>
                <li>History transaksi (penjualan, pembayaran, produk kembali)</li>
                <li>Statistic dengan grafik garis dan grafik pie.</li>
                <li>Menyediakan bahasa: Inggris dan Indonesia.</li>
              </ul>
            </div>
            <div class="wb-max-width-70 mt-3r">
              <p>Download Pipesys POS Sales Canvas App for the store at Google Play Store</p>
              <a href="https://play.google.com/store/apps/details?id=com.rc.pipesys" target="_blank">
                <img src="<?= base_url('aset/frontend/img/googleplay.png'); ?>" class="img-download">
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section id="fitur" class="section-normal bg-light text-center wb-min-height-100vh" style="min-height: 0px;">
      <div class="container container-gallery">
        <div class="section-title" data-aos="fade-down">Fitur Pipesys</div>
        <div class="text-center">
          <div class="border-bottom-blue"></div>
        </div>
        <div class="row justify-content-center mt-5r gallery-img list-experience list-fitur ">
        <?php foreach($this->frontend->list_link("fitur") as $a): ?>
          <div class="col-sm-4 aos-init pointer aos-animate padding-30" data-aos="flip-up">
            <div class="item"> 
                <img src="<?= $a->img; ?>" class="img">
                <!-- <div class="middle">
                    <div class="text"><?= $a->title; ?></div>
                </div> -->
            </div>
            <b style="font-size: 20px;"><?= $a->title; ?></b>
            <p style="font-size: 17px;"><?= $a->text; ?></p>
        </div>
        <?php endforeach; ?>
        </div>
      </div>
    </section>
    
   <section id="harga" class="section-normal bg-light text-center wb-min-height-100vh" style="min-height: 0px;background-image: url('../img/bg/bg.jpg');background-size: cover;background-position: center;background-repeat: no-repeat;
    background-attachment: fixed;">
      <div class="container-fluid container-small">
        <div class="section-title" data-aos="fade-down"><?= $this->lang->line('price'); ?></div>
        <div class="text-center">
          <div class="border-bottom-blue"></div>
        </div>
        <div class="row justify-content-center mt-5r list-product list-product-data list-package">
        <?php foreach($this->frontend->list_link("harga") as $key => $a): ?>
          <div class="col-sm-3 aos-init aos-animate" data-aos="fade-down">
            <div class="item item-price pointer <?= $a->active; ?>">
              <div class="header bg-<?= ($key + 1);?>">
                <p class="title"><?= $a->title; ?></p>
              </div>
              <div class="body">
                <p class="name"><?= $a->name; ?></p>
                <div class="price"><span class=""><?= $a->harga; ?></span><span class="text-price"> <?= $a->text; ?></span></div>
              </div>
              <div class="footer">
                <!-- <a href="javscript:;" class="btn btn-custom">Order</a> -->
                <ul>
                  <?php foreach($a->footer as $b): ?>
                    <li><?= $b; ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        </div>
      </div>
    </section>
    <link rel="stylesheet" href="<?= base_url('aset/frontend/plugin/swiper/swiper.min.css'); ?>">
    <section id="daftar" class="section-normal bg-light text-center wb-max-height-800p" style="min-height: 0px;">
      <div class="container">
        <div class="section-title" data-aos="fade-down"><?= $this->lang->line('try_free_now'); ?></div>
        <div class="text-center">
          <div class="border-bottom-blue"></div>
        </div>
        <div class="mt-3r ">
          <a href="#" class="btn btn-custom btn-radius"><?= $this->lang->line('try_free'); ?></a>
        </div>
      </div>
    </section>
    <script src="<?= base_url('aset/frontend/plugin/swiper/swiper.min.js'); ?>"></script>