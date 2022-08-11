<nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega" role="navigation">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle hamburger hamburger-close navbar-toggle-left hided" data-toggle="menubar" onclick="check_logo(1)">
      <span class="sr-only">Toggle navigation</span>
      <span class="hamburger-bar"></span>
    </button>
    <button type="button" class="navbar-toggle collapsed" data-target="#site-navbar-collapse" data-toggle="collapse">
      <i class="icon wb-more-horizontal" aria-hidden="true"></i>
    </button>
    <div class="navbar-brand navbar-brand-center site-gridmenu-toggle" data-toggle="gridmenu">
      <a href="<?= site_url() ?>">
        <img class="navbar-brand-logo" src="<?= base_url("img/pipesys.png"); ?>" title="Pipesys">
        <span class="navbar-brand-text hidden-xs">
         <img src="http://qa.pipesys.rcelectronic.co.id/img/pipesys_small.png" height="45px" style="
      margin-top: -10px;">
        </span>
      </a>
    </div>
  </div>

  <div class="navbar-container container-fluid">
    <!-- Navbar Collapse -->
    <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
      <!-- Navbar Toolbar -->
      <ul class="nav navbar-toolbar">
        <li class="hidden-float" id="toggleMenubar" onclick="check_logo(0)">
          <a data-toggle="menubar" href="#" role="button">
            <i class="icon hamburger hamburger-arrow-left">
                <span class="sr-only">Toggle menubar</span>
                <span class="hamburger-bar"></span>
              </i>
          </a>
        </li>

        <!-- <li class="dropdown dropdown-fw dropdown-mega">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false" data-animation="fade" role="button">Mega <i class="icon wb-chevron-down-mini" aria-hidden="true"></i></a>
          <ul class="dropdown-menu" role="menu">
            <li role="presentation">
              <div class="mega-content">
                <div class="row">
                  <div class="col-sm-4">
                    <h5>UI Kit</h5>
                    <ul class="blocks-2">
                      <li class="mega-menu margin-0">
                        <ul class="list-icons">
                          <li><i class="wb-chevron-right-mini" aria-hidden="true"></i>
                            <a href="..\advanced\animation.html">Animation</a>
                          </li>
                          <li><i class="wb-chevron-right-mini" aria-hidden="true"></i>
                            <a href="..\uikit\buttons.html">Buttons</a>
                          </li>
                          <li><i class="wb-chevron-right-mini" aria-hidden="true"></i>
                            <a href="..\uikit\colors.html">Colors</a>
                          </li>
                          <li><i class="wb-chevron-right-mini" aria-hidden="true"></i>
                            <a href="..\uikit\dropdowns.html">Dropdowns</a>
                          </li>
                          <li><i class="wb-chevron-right-mini" aria-hidden="true"></i>
                            <a href="..\uikit\icons.html">Icons</a>
                          </li>
                          <li><i class="wb-chevron-right-mini" aria-hidden="true"></i>
                            <a href="..\advanced\lightbox.html">Lightbox</a>
                          </li>
                        </ul>
                      </li>
                      <li class="mega-menu margin-0">
                        <ul class="list-icons">
                          <li><i class="wb-chevron-right-mini" aria-hidden="true"></i>
                            <a href="..\uikit\modals.html">Modals</a>
                          </li>
                          <li><i class="wb-chevron-right-mini" aria-hidden="true"></i>
                            <a href="..\uikit\panel-structure.html">Panels</a>
                          </li>
                          <li><i class="wb-chevron-right-mini" aria-hidden="true"></i>
                            <a href="..\structure\overlay.html">Overlay</a>
                          </li>
                          <li><i class="wb-chevron-right-mini" aria-hidden="true"></i>
                            <a href="..\uikit\tooltip-popover.html">Tooltips</a>
                          </li>
                          <li><i class="wb-chevron-right-mini" aria-hidden="true"></i>
                            <a href="..\advanced\scrollable.html">Scrollable</a>
                          </li>
                          <li><i class="wb-chevron-right-mini" aria-hidden="true"></i>
                            <a href="..\uikit\typography.html">Typography</a>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </li> -->
      </ul>
      <!-- End Navbar Toolbar -->

      <!-- Navbar Toolbar Right -->
      <ul class="nav navbar-toolbar navbar-right navbar-toolbar-right" style="font-weight: bold;">
        
        <!-- <li class="dropdown">
          <a data-toggle="dropdown" href="javascript:void(0)" title="Notifications" aria-expanded="false" data-animation="scale-up" role="button">
            <i class="icon wb-bell" aria-hidden="true"></i>
            <span class="badge badge-danger up">5</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-right dropdown-menu-media" role="menu">
            <li class="dropdown-menu-header" role="presentation">
              <h5>NOTIFICATIONS</h5>
              <span class="label label-round label-danger">New 5</span>
            </li>

            <li class="list-group" role="presentation">
              <div data-role="container">
                <div data-role="content">
                  <a class="list-group-item" href="javascript:void(0)" role="menuitem">
                    <div class="media">
                      <div class="media-left padding-right-10">
                        <i class="icon wb-order bg-red-600 white icon-circle" aria-hidden="true"></i>
                      </div>
                      <div class="media-body">
                        <h6 class="media-heading">A new order voucher has been placed</h6>
                        <time class="media-meta" datetime="2016-06-12T20:50:48+08:00">5 hours ago</time>
                      </div>
                    </div>
                  </a>
                  <a class="list-group-item" href="javascript:void(0)" role="menuitem">
                    <div class="media">
                      <div class="media-left padding-right-10">
                        <i class="icon wb-user bg-green-600 white icon-circle" aria-hidden="true"></i>
                      </div>
                      <div class="media-body">
                        <h6 class="media-heading">Completed the task</h6>
                        <time class="media-meta" datetime="2016-06-11T18:29:20+08:00">2 days ago</time>
                      </div>
                    </div>
                  </a>
                  <a class="list-group-item" href="javascript:void(0)" role="menuitem">
                    <div class="media">
                      <div class="media-left padding-right-10">
                        <i class="icon wb-settings bg-red-600 white icon-circle" aria-hidden="true"></i>
                      </div>
                      <div class="media-body">
                        <h6 class="media-heading">Settings updated</h6>
                        <time class="media-meta" datetime="2016-06-11T14:05:00+08:00">2 days ago</time>
                      </div>
                    </div>
                  </a>
                  <a class="list-group-item" href="javascript:void(0)" role="menuitem">
                    <div class="media">
                      <div class="media-left padding-right-10">
                        <i class="icon wb-calendar bg-blue-600 white icon-circle" aria-hidden="true"></i>
                      </div>
                      <div class="media-body">
                        <h6 class="media-heading">Event started</h6>
                        <time class="media-meta" datetime="2016-06-10T13:50:18+08:00">3 days ago</time>
                      </div>
                    </div>
                  </a>
                  <a class="list-group-item" href="javascript:void(0)" role="menuitem">
                    <div class="media">
                      <div class="media-left padding-right-10">
                        <i class="icon wb-chat bg-orange-600 white icon-circle" aria-hidden="true"></i>
                      </div>
                      <div class="media-body">
                        <h6 class="media-heading">Message received</h6>
                        <time class="media-meta" datetime="2016-06-10T12:34:48+08:00">3 days ago</time>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
            </li>
            <li class="dropdown-menu-footer" role="presentation">
              <a class="dropdown-menu-footer-btn" href="javascript:void(0)" role="button">
                <i class="icon wb-settings" aria-hidden="true"></i>
              </a>
              <a href="javascript:void(0)" role="menuitem">
                  All notifications
                </a>
            </li>
          </ul>
        </li> -->
       <!--  <li class="dropdown">
          <a data-toggle="dropdown" href="javascript:void(0)" title="Messages" aria-expanded="false" data-animation="scale-up" role="button">
            <i class="icon wb-envelope" aria-hidden="true"></i>
            <span class="badge badge-info up">3</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-right dropdown-menu-media" role="menu">
            <li class="dropdown-menu-header" role="presentation">
              <h5>MESSAGES</h5>
              <span class="label label-round label-info">New 3</span>
            </li>

            <li class="list-group" role="presentation">
              <div data-role="container">
                <div data-role="content">
                  <a class="list-group-item" href="javascript:void(0)" role="menuitem">
                    <div class="media">
                      <div class="media-left padding-right-10">
                        <span class="avatar avatar-sm avatar-online">
                          <img src="<?= base_url("global"); ?>\portraits\2.jpg" alt="...">
                          <i></i>
                        </span>
                      </div>
                      <div class="media-body">
                        <h6 class="media-heading">Mary Adams</h6>
                        <div class="media-meta">
                          <time datetime="2016-06-17T20:22:05+08:00">30 minutes ago</time>
                        </div>
                        <div class="media-detail">Anyways, i would like just do it</div>
                      </div>
                    </div>
                  </a>
                  <a class="list-group-item" href="javascript:void(0)" role="menuitem">
                    <div class="media">
                      <div class="media-left padding-right-10">
                        <span class="avatar avatar-sm avatar-off">
                          <img src="<?= base_url("global"); ?>\portraits\3.jpg" alt="...">
                          <i></i>
                        </span>
                      </div>
                      <div class="media-body">
                        <h6 class="media-heading">Caleb Richards</h6>
                        <div class="media-meta">
                          <time datetime="2016-06-17T12:30:30+08:00">12 hours ago</time>
                        </div>
                        <div class="media-detail">I checheck the document. But there seems</div>
                      </div>
                    </div>
                  </a>
                  <a class="list-group-item" href="javascript:void(0)" role="menuitem">
                    <div class="media">
                      <div class="media-left padding-right-10">
                        <span class="avatar avatar-sm avatar-busy">
                          <img src="<?= base_url("global"); ?>\portraits\4.jpg" alt="...">
                          <i></i>
                        </span>
                      </div>
                      <div class="media-body">
                        <h6 class="media-heading">June Lane</h6>
                        <div class="media-meta">
                          <time datetime="2016-06-16T18:38:40+08:00">2 days ago</time>
                        </div>
                        <div class="media-detail">Lorem ipsum Id consectetur et minim</div>
                      </div>
                    </div>
                  </a>
                  <a class="list-group-item" href="javascript:void(0)" role="menuitem">
                    <div class="media">
                      <div class="media-left padding-right-10">
                        <span class="avatar avatar-sm avatar-away">
                          <img src="<?= base_url("global"); ?>\portraits\5.jpg" alt="...">
                          <i></i>
                        </span>
                      </div>
                      <div class="media-body">
                        <h6 class="media-heading">Edward Fletcher</h6>
                        <div class="media-meta">
                          <time datetime="2016-06-15T20:34:48+08:00">3 days ago</time>
                        </div>
                        <div class="media-detail">Dolor et irure cupidatat commodo nostrud nostrud.</div>
                      </div>
                    </div>
                  </a>
                </div>
              </div>
            </li>
            <li class="dropdown-menu-footer" role="presentation">
              <a class="dropdown-menu-footer-btn" href="javascript:void(0)" role="button">
                <i class="icon wb-settings" aria-hidden="true"></i>
              </a>
              <a href="javascript:void(0)" role="menuitem">
                  See all messages
                </a>
            </li>
          </ul>
        </li> -->
        <li class="dropdown content-hide">
          <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)" data-animation="scale-up" aria-expanded="false" role="button">
            <?php 
              if($this->session->userdata("bahasa") == "english"): 
                echo '<span class="flag-icon flag-icon-us"></span>';
              else:
                echo '<span class="flag-icon flag-icon-id"></span>';
              endif;
            ?>
          </a>
          <ul class="dropdown-menu" role="menu">
            <li role="presentation">
              <a href="<?= site_url('main/bahasa/english') ?>" role="menuitem">
                <span class="flag-icon flag-icon-us"></span> <?= $this->lang->line('lb_english') ?></a>
            </li>
            <li role="presentation">
              <a href="<?= site_url('main/bahasa/indonesia') ?>" role="menuitem">
                <span class="flag-icon flag-icon-id"></span> <?= $this->lang->line('lb_indonesia') ?></a>
            </li>
          </ul>
        </li>
        <li class="dropdown">
          <a class="navbar-avatar dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false" data-animation="scale-up" role="button">
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
              <a href="<?= site_url("buy-voucher"); ?>" role="menuitem">Voucher</a>
            </li>
            <li class="divider" role="presentation"></li>
            <li role="presentation">
              <a href="<?= site_url("logout"); ?>" role="menuitem"><?= $this->lang->line('lb_logout') ?></a>
            </li>
          </ul>
        </li>
      </ul>
      <!-- End Navbar Toolbar Right -->
    </div>
    <!-- End Navbar Collapse -->
  </div>
</nav>

<script type="text/javascript">
  function check_logo(page){
    if(!$('.unfolded').hasClass('unfolded') && page == 0){
      $('.navbar-brand-logo').hide();
    }else{
      $('.navbar-brand-logo').show();
    }
  }
</script>