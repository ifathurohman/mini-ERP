<div class="fixed-top contact-top" style="z-index: 1031;">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-6 col-xs-6">
          <ul class="list-contact">
            <li class="item Whatsapp1"></li>
            <li class="item Whatsapp2"></li>
          </ul>
        </div>
        <div class="col-lg-6 col-xs-6 text-right">
          <ul class="list-contact">
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
            <!-- <li class="item m-tb-zero">
                <a href="<?= site_url('people-shape-faq'); ?>" class="btn btn-custom btn-radius mt-5p mb-5p" style="line-height: 15px;min-width: 100px;"><?= $this->lang->line('help_center'); ?></a>
            </li> -->
          </ul>
        </div>
      </div>
    </div>
  </div>
  <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top" >
      <div class="container-fluid navbar-container">
        <a class="navbar-brand" href="<?= site_url(); ?>">
          <img class="SiteLogo" src="<?= $this->frontend->set_('Logo'); ?>" alt="Pipesys">
        </a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="true" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse" id="navbarResponsive" style="">
          <ul class="navbar-nav ml-auto">
          <?php 
            $MainMenu = $this->frontend->set_("MainMenu");
            $MainMenu = json_decode($MainMenu);
            if(count($MainMenu) > 0):  
          ?>
          <?php foreach($MainMenu as $a): ?>
            <?php if($a->ContentID == "products" || $a->ContentID == "our-solution"):?>
              <li class="nav-item dropdown dropdown-large">
               <!--  <a class="nav-link dropdown-toggle" href="javascript:;" id="navbarDropdownPortfolio">Products</a> -->
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPortfolio" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Products</a>
                <div class="dropdown-menu dropdown-menu-right dropdown-dark p-zero margin-zero" aria-labelledby="navbarDropdownPortfolio">
                  <div class="row-dropdown">
                    <div class="dropdown-main p-r-zero">
                    <?php foreach($this->frontend->Category("list") as $a): ?><a class="dropdown-item" href="<?= site_url('product/list/'.$a->Link); ?>" id="#"><?= $a->Name; ?></a>
                    <?php endforeach; ?></div> 
                  </div>
                </div>
              </li>
            <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="<?= $a->Url; ?>"><?= $a->Name ?></a>
            </li>
            <?php endif; ?>
          <?php endforeach; ?>
          <?php else: ?>
            <?php foreach($this->frontend->list_link("main_menu") as $key => $a): ?>
            <li class="nav-item">
              <?php 
              $link   = $a->link;
              $ddata  = "";
              $arrMenu= array('Dashboard','Login');
              if(current_url() == site_url() || current_url() == base_url()):
                if($device == "web"):
                  if($a->type == "home"): 
                    $link   = "javascript:;"; 
                    $ddata  = 'data-slide="0"';
                  elseif($a->type == "fitur"):
                    $link   = "javascript:;"; 
                    $ddata  = 'data-slide="1"';
                  elseif($a->type == "harga"):
                    $link   = "javascript:;"; 
                    $ddata  = 'data-slide="3"';
                  endif;
                else:
                  if($a->type == "home"): 
                    $link   = "javascript:;"; 
                    $ddata  = 'data-slide="0"';
                  elseif($a->type == "fitur"):
                    $link   = "javascript:;"; 
                    $ddata  = 'data-slide="1"';
                  elseif($a->type == "harga"):
                    $link   = "javascript:;"; 
                    $ddata  = 'data-slide="6"';
                  endif;
                endif;
              elseif(!in_array($a->name, $arrMenu)):
                $link = site_url().$link;
              endif;

              ?>
              <a class="nav-link" href="<?= $link; ?>" <?= $ddata; ?> ><?= $a->name; ?></a>
            </li>        
          <?php endforeach; ?>
            <?php endif; ?>
            <li class="nav-item mobile dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPortfolio" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                  <?php 
                  if($this->session->bahasa == "indonesia"): 
                    echo '<span class="flag-icon flag-icon-id mr-10"></span> Bahasa Indonesia';
                  else:
                    echo '<span class="flag-icon flag-icon-us mr-10"></span> English';
                  endif;
                ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-dark p-zero margin-zero" aria-labelledby="navbarDropdownPortfolio">
                  <div class="row-dropdown">
                    <div class="dropdown-main p-r-zero">
                    <?php foreach($this->frontend->list_link('bahasa') as $a): ?>
                    <a class="dropdown-item" href="<?= $a->link; ?>"><span class="<?= $a->icon; ?>"></span> <?= $a->name; ?></a>
                    <?php endforeach; ?>
                    </div> 
                  </div>
                </div>
              </li>
          </ul>
        </div>
      </div>
    </nav>