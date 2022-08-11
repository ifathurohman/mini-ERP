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
    <div class="swiper-container swiper-horizontal">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <div class="slideshow">
            <?php 
              $slideshow = $this->frontend->slideshow("list_data");
            ?>
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
              <ol class="carousel-indicators">
                <?php 
                // foreach($slideshow as $key => $a): 
                //   $active = "";
                //   if($key == 0):
                //     $active = "active";
                //   endif;
                //   echo '<li data-target="#carouselExampleIndicators" data-slide-to="'.$key.'" class="'.$active.'"></li>';
                // endforeach;
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
                    <h3 class="title"><?= $a->Name; ?></h3>
                    <p class="text">
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
          </div>
        </div>
        <?php foreach($this->frontend->list_link("fitur") as $a): ?>
        <div class="swiper-slide">
          <div class="section-normal bg-light text-center wb-min-height-100vh" >
            <div class="container container-gallery">
              <div class="section-title"><?= $this->lang->line('features'); ?></div>
              <div class="text-center">
                <div class="border-bottom-blue"></div>
              </div>
              <div class="row justify-content-center mt-3r gallery-img list-experience list-fitur ">
                <div class="col-sm-6  pointer  padding-30">
                  <div class="item" style="margin:auto;"> 
                      <img alt="" src="<?= $a->img; ?>" class="img">
                  </div>
                  <b style="font-size: 20px;"><?= $a->title; ?></b>
                  <p style="font-size: 17px;"><?= $a->text; ?></p>
              </div>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
        <?php foreach($this->frontend->list_link("harga") as $key => $a): ?>
        <div class="swiper-slide">
          <div class="bg-harga section-normal bg-light text-center wb-min-height-100vh">
            <div class="container-fluid container-small">
              <div class="section-title"><?= $this->lang->line('price'); ?></div>
              <div class="text-center">
                <div class="border-bottom-blue"></div>
              </div>
              <div class="row justify-content-center mt-5r list-product list-product-data list-package">
                <div class="col-sm-6  ">
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
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
        <div class="swiper-slide">
          <div class="section-normal bg-light text-center" style="min-height: 0px;padding-top: 30vh !important;">
            <div class="container-fluid">
              <div class="section-title"><?= $this->lang->line('try_free_now'); ?></div>
              <div class="text-center">
                <div class="border-bottom-blue"></div>
              </div>
              <div class="mt-3r ">
                <a href="#" class="btn btn-custom btn-radius"><?= $this->lang->line('try_free'); ?></a>
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-slide">
          <div class="footer bg-footer section-normal bg-light text-center" >
            <div class="container container-small">
              <div class="row no-gutters">
                <div class="col-lg-6 text-left">
                  <h3 class="title"><?= $this->lang->line('another_product'); ?></h3>
                  <ul class="list-link">
                  <?php 
                  foreach($this->frontend->list_link("another_product") as $a):
                    echo '<li><a href="'.$a->link.'" class="link">'.$a->name.'</a></li>';
                  endforeach; ?>
                  </ul>
                </div>
                <div class="col-lg-6 text-left">
                  <h3 class="title"><?= $this->lang->line('contact'); ?></h3>
                  <table class="list-link list-link-icon front_contact">
                    
                  </table>
                </div>
              </div>
              <div class="row mt-3r footer-bottom">
                <div class="col-lg-12 h-100 text-center my-auto">
                  <ul class="list-inline">
                    <li class="item dropdown">
                      <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)" data-animation="scale-up" aria-expanded="false" role="button">
                        <?php 
                          if($this->session->bahasa == "indonesia"): 
                            echo '<span class="flag-icon flag-icon-id mr-10"></span>Bahasa Indonesia';
                          else:
                            echo '<span class="flag-icon flag-icon-us mr-10"></span>English';
                          endif;
                        ?>
                      </a>
                      <ul class="list-bahasa dropdown-menu" role="menu">
                        <?php foreach($this->frontend->list_link('bahasa') as $a): ?>
                        <li role="presentation">
                          <a href="<?= $a->link;  ?>" role="menuitem">
                            <span class="<?= $a->icon; ?>"></span> <?= $a->name; ?></a>
                        </li>
                        <?php endforeach; ?>
                      </ul>
                    </li>
                  </ul>
                  <ul class="list-inline mb-20">
                    <li class="list-inline-item">
                      <a href="<?= site_url('privacy-policy.html'); ?>" target="_blank">Privacy Policy</a>
                    </li>
                    <li class="list-inline-item">|</li>
                    <li class="list-inline-item">
                      <a href="<?= site_url('terms-of-service'); ?>" target="_blank">Terms Of Service</a>
                    </li>
                  </ul>
                  <p class="text-muted small mb-4 mb-lg-0"><a href="https://rcelectronic.co.id" target="_blank">Copyright &copy; since 2019 - RC Electronic, Cv</a></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
<!--       <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div> -->
       <div class="swiper-pagination"></div>
    </div>    
    <script>
    var swiper = new Swiper('.swiper-horizontal', {
        // direction: 'vertical',
        slidesPerView: 1,
        // spaceBetween: 30,
        keyboard: {
          enabled: true,
        },
        mousewheel: true,
        pagination: {
          el: '.swiper-horizontal .swiper-pagination',
          clickable: true,
        },
    });
    </script>