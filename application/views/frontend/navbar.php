<nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega" role="navigation">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle collapsed" data-target="#site-navbar-collapse" data-toggle="collapse">
      <i class="icon wb-more-horizontal" aria-hidden="true"></i>
    </button>
    <div class="navbar-brand navbar-brand-center site-gridmenu-toggle" data-toggle="gridmenu">
      <a href="<?= site_url() ?>">
        <img class="navbar-brand-logo" src="<?= $this->frontend->set_('Icon'); ?>" title="Pipesys">
        <span class="navbar-brand-text hidden-xs">
          <?= $this->main->getTitleApp() ?>
        </span>
      </a>
    </div>
  </div>

  <div class="navbar-container container-fluid" style="margin-left:0px;background: #62a8ea;">
    <!-- Navbar Collapse -->
    <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
      <!-- Navbar Toolbar -->
      <ul class="nav navbar-toolbar">
        <li class="hidden-float" id="toggleMenubar">
          <a href="<?= site_url() ?>" role="button" class="nav-frontend">
            <img class="navbar-brand-logo" src="<?= $this->frontend->set_('Logo'); ?>" title="Pipesys">
            <span class="navbar-brand-text hidden-xs">
              <!-- <?= $this->main->getTitleApp() ?> -->
            </span>
          </a>
        </li>
      </ul>
      <!-- End Navbar Toolbar -->

      <!-- Navbar Toolbar Right -->   
      <ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
        <li class="dropdown">
          <div class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false" data-animation="scale-up" role="button">
            <span class="text-white">+628112199050</span>
          </div>
          <ul class="dropdown-menu" role="menu">
            <li role="presentation">
              <a href="https://api.whatsapp.com/send?phone=628112199050" target="_blank" role="menuitem">Whatsapp</a>
            </li>
            <li role="presentation">
              <a href="tel:+628112199050" role="menuitem">Call</a>
            </li>
          </ul>
        </li>
        <li class="dropdown">
          <div class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false" data-animation="scale-up" role="button">
            <span class="text-white">+628122059327</span>
          </div>
          <ul class="dropdown-menu" role="menu">
            <li role="presentation">
              <a href="https://api.whatsapp.com/send?phone=628122059327" target="_blank" role="menuitem">Whatsapp</a>
            </li>
            <li role="presentation">
              <a href="tel:+628122059327" role="menuitem">Call</a>
            </li>
          </ul>
        </li>
        <li class="dropdown">
          <div class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false" data-animation="scale-up" role="button">
            <a href="mailto:support@rcelectronic.co.id">support@rcelectronic.co.id</a>
          </div>
        </li>
        <!-- <li role="presentation">
          <a href="<?= site_url('people-shape-faq') ?>" style="background:#8d221a" role="menuitem"><i class="icon md-power" aria-hidden="true"></i> <span class="text-white">Help Center</span></a>
        </li> -->
        <?php if($this->session->login): ?>
          <li class="dropdown">
            <a class="navbar-avatar dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false" data-animation="scale-up" role="button" style="padding-top: 19px;padding-bottom: 19px;">
              <span class="avatar avatar-online">
                <img src="<?php  echo $this->main->company_logo(); ?>" alt="...">
                <i></i>
              </span>
            </a>
            <ul class="dropdown-menu" role="menu">
              <li role="presentation">
                <a href="<?= site_url("user-account"); ?>" role="menuitem"><?= $this->lang->line('profile') ?></a>
              </li>
              <li role="presentation">
                <a href="<?= site_url("company-information"); ?>" role="menuitem"><?= $this->lang->line('lb_company_info') ?></a>
              </li>
              <li role="presentation">
                <a href="<?= site_url("buy-voucher"); ?>" role="menuitem">Buy Voucher</a>
              </li>
              <li class="divider" role="presentation"></li>
              <li role="presentation">
                <a href="<?= site_url("logout"); ?>" role="menuitem"><?= $this->lang->line('lb_logout') ?></a>
              </li>
            </ul>
          </li>
        <?php endif; ?>
      </ul>
      <!-- End Navbar Toolbar Right -->
    </div>
    <!-- End Navbar Collapse -->
  </div>
</nav>