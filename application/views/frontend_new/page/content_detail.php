    <?php if($data->Template != "custom"): ?>
<!-- Masthead -->
    <?php if($data->Img): $IMG = "url('".site_url($data->Img)."')"; ?> 
    <header class="banner text-white bg-cover" style="background: <?= $IMG; ?> top">
      <!-- <div class="overlay"></div> -->
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
          </div>
        </div>
      </div>
    </header>
    <?php endif; ?>

    <div class="banner-content banner-bottom container-fluid container-small">
      <div class="text-left mb-20">
        <div class="border-bottom-blue"></div>
      </div>
      <?php $NameColor = ""; if($data->NameColor): $NameColor="color:".$data->NameColor." !important;"; endif; 
      ?>
      <div class="title text-white " style="<?= $NameColor; ?>">
        <?= $data->Name; ?>
      </div>
      <div class="div-link link-white" data-aos="fade-right" >
        <ul>
          <li><a href="<?= site_url(); ?>" style="<?= $NameColor; ?>">Home</a></li>
          <li>></li>
          <li><a href="<?= current_url(); ?>" style="<?= $NameColor; ?>"><?= $data->Name; ?></a></li>
        </ul>
      </div>
    </div>
    <?php endif; ?>
    <section class="section  pt-zero text-left">
      <?php if($data->Template == "custom"): ?>
      <div class="container-fluid p-0">
        <?= $data->Description; ?>
      </div>
      <?php else: ?>
      <div class="container-fluid container-small">
        <div class="div-content p-lr-zero">
          <div class="row">
            <?php if($data->Template == "fullwidth"): ?> 
            <div class="col-lg-12">
            <?php else: ?>
            <div class="col-lg-9"> 
            <?php endif; ?>
              <div class="div-title">
                <?php if($data->Type == "content"):?>
                  <div class="content-header pb-3r">
                    <h2 class="title title-small bold text-left" data-aos="fade-down"><?= $data->Name; ?></h2>
                    <span class="author"><?= $Author = $data->UserName." - ".date("D, d M Y H:i",strtotime($data->Date)); ?></span>                
                  </div>
                <?php endif; ?>
                <div class="content-body <?= $data->Template; ?>">
                  <?= $data->Description; ?>
                </div>
            </div>
          </div>
          <?php if($data->Template == "sidemenu"): ?>
          <div class="col-sm-3">
            <?php $this->load->view("frontend/side_menu",array("side_menu" => "home")); ?>                       
            <?php $this->load->view("frontend/side_menu",array("side_menu" => "x")); ?>       
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>
    </section>