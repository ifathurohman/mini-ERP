<div class="fixed-top contact-top" style="z-index: 1031;">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-6 col-xs-6">
          <ul class="list-contact">
            <li class="item"><a href="https://api.whatsapp.com/send?phone=628112199050"><i class="fa fa-phone"></i> 08112199050</a></li>
            <li class="item"><a href="https://api.whatsapp.com/send?phone=628122059327"><i class="fa fa-phone"></i> 08122059327</a></li>
          </ul>
        </div>
        <div class="col-lg-6 col-xs-6 text-right">
          <ul class="list-contact">
            <li class="item m-tb-zero">
                <a href="<?= site_url('people-shape-faq'); ?>" class="btn btn-custom btn-radius mt-5p mb-5p" style="line-height: 20px;min-width: 100px;"><?= $this->lang->line('help_center'); ?></a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <nav class="main-menu bg-transparant navbar navbar-expand-lg navbar-light bg-light fixed-top" >
      <div class="container-fluid navbar-container">
        <a class="navbar-brand" href="<?= site_url(); ?>"><img src="<?= $this->frontend->set_('Logo'); ?>" /></a>
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
            <?php foreach($this->frontend->list_link("main_menu") as $a): ?>
            <li class="nav-item">
              <?php $link = $a->link; if($a->name == "Home" && current_url() == site_url() || $a->name == "Home" && current_url() == base_url()): $link = "#home"; endif;?>
              <a class="nav-link" href="<?= $link; ?>"><?= $a->name; ?></a>
            </li>          
          <?php endforeach; ?>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>