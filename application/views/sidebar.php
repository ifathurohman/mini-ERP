<div class="site-menubar">
  <ul class="site-menu">
    <?php if(0):?>
    <li class="site-menu-item">
      <a href="javascript:void(0)">
        <i class="site-menu-icon wb-desktop" aria-hidden="true"></i>
        <span class="site-menu-title"> Modul</span>
        <span class="site-menu-arrow"></span>
      </a>
      <ul class="site-menu-sub">
        <li class="site-menu-item">
          <a class="animsition-link" href="<?= site_url("main/set_app/pipesys"); ?>">
            <span class="site-menu-title">Pipesys</span>
          </a>
        </li>
        <li class="site-menu-item">
            <a class="animsition-link" href="<?= site_url("main/set_app/salespro"); ?>">
            <span class="site-menu-title">People Shape Sales</span>
          </a>
        </li>
      </ul>
    </li>
  <?php endif; ?>
    <!-- <li class="site-menu-item">
      <a href="<?= site_url('user-account'); ?>">
        <i class="site-menu-icon wb-user" aria-hidden="true"></i>
        <span class="site-menu-title"> <?= $this->session->nama; ?></span>
      </a>
    </li> -->
    <li class="site-menu-item has-sub">
      <a href="<?= site_url('dashboard'); ?>">
        <i class="site-menu-icon fa-dashboard" aria-hidden="true"></i>
        <span class="site-menu-title"><?= $this->lang->line('fitur_dashboard') ?></span>
      </a>
    </li>
    <?php if($this->session->hak_akses == "super_admin"): ?>
    <li class="site-menu-item has-sub">
      <a href="javascript:void(0)">
        <i class="site-menu-icon fa-user" aria-hidden="true"></i>
        <span class="site-menu-title"><?= $this->lang->line('lb_manage') ?></span>
        <span class="site-menu-arrow"></span>
      </a>
      <ul class="site-menu-sub">
        <?php foreach($this->main->menu("management") as $a): ?>
        <li class="site-menu-item">
          <a class="animsition-link" href="<?= site_url($a->url); ?>">
            <span class="site-menu-title"><?= $a->nama_menu; ?></span>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
    </li>
    <?php endif; ?>
    <li class="site-menu-item has-sub">
      <a href="javascript:void(0)">
        <i class="site-menu-icon fa-tasks" aria-hidden="true"></i>
        <span class="site-menu-title"><?= $this->lang->line('lb_admin') ?></span>
        <span class="site-menu-arrow"></span>
      </a>
      <ul class="site-menu-sub">
        <?php  $no = 0; foreach($this->main->menu("administrasi") as $a):
        if(!in_array($this->session->hak_akses, array("super_admin","company")) && $a->root == "main/setting_parameter"): else:
        ?>
        <li class="site-menu-item">
          <a class="animsition-link" href="<?= site_url($a->url); ?>">
            <span class="site-menu-title"><?= $a->nama_menu; ?></span>
          </a>
        </li>
        <?php endif; endforeach; ?>
      </ul>
    </li>
    <li class="site-menu-item has-sub">
      <a href="javascript:void(0)">
        <i class="site-menu-icon fa-database" aria-hidden="true"></i>
        <span class="site-menu-title"><?= $this->lang->line('lb_master') ?></span>
        <span class="site-menu-arrow"></span>
      </a>
      <ul class="site-menu-sub">
        <?php foreach($this->main->menu("master") as $a):
          $no += 1;
          $modul = $this->main->relpace_str($a->modul,'"');
          $modul2 = $this->main->relpace_str($a->modul2,'"');
        ?>
        <li class="site-menu-item get_modul vap-<?= $no ?>" data-page="master" data-classnya="vap-<?= $no ?>" data-modul="<?= $modul ?>" data-modul2="<?= $modul2 ?>">
          <a class="animsition-link" href="<?= site_url($a->url); ?>">
            <span class="site-menu-title"><?= $a->nama_menu; ?></span>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
    </li>

    <li class="site-menu-item has-sub v-ar">
      <a href="javascript:void(0)">
        <i class="site-menu-icon fa-money" aria-hidden="true"></i>
        <span class="site-menu-title"><?= $this->lang->line('lb_sales') ?></span>
        <span class="site-menu-arrow"></span>
      </a>
      <ul class="site-menu-sub">
        <?php foreach($this->main->menu("transaction","ar") as $a):
          $no += 1;
          $modul = $this->main->relpace_str($a->modul,'"');
          $modul2 = $this->main->relpace_str($a->modul2,'"');
        ?>
        <li class="site-menu-item get_modul var-<?= $no ?>" data-page="transaction" data-classnya="var-<?= $no ?>" data-modul="<?= $modul ?>" data-modul2="<?= $modul2 ?>">
          <a class="animsition-link" href="<?= site_url($a->url); ?>">
            <span class="site-menu-title"><?= $a->nama_menu; ?></span>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
    </li>

    <li class="site-menu-item has-sub v-ap">
      <a href="javascript:void(0)">
        <i class="site-menu-icon fa-shopping-cart" aria-hidden="true"></i>
        <span class="site-menu-title"><?= $this->lang->line('lb_purchase1') ?></span>
        <span class="site-menu-arrow"></span>
      </a>
      <ul class="site-menu-sub">
        <?php foreach($this->main->menu("transaction","ap") as $a):
          $no += 1;
          $modul = $this->main->relpace_str($a->modul,'"');
          $modul2 = $this->main->relpace_str($a->modul2,'"');
        ?>
        <li class="site-menu-item get_modul vap-<?= $no ?>" data-page="transaction" data-classnya="vap-<?= $no ?>" data-modul="<?= $modul ?>" data-modul2="<?= $modul2 ?>">
          <a class="animsition-link" href="<?= site_url($a->url); ?>">
            <span class="site-menu-title"><?= $a->nama_menu; ?></span>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
    </li>

    <li class="site-menu-item has-sub v-inventory">
      <a href="javascript:void(0)">
        <i class="site-menu-icon fa-shopping-basket" aria-hidden="true"></i>
        <span class="site-menu-title"><?= $this->lang->line('lb_inventory') ?></span>
        <span class="site-menu-arrow"></span>
      </a>
      <ul class="site-menu-sub">
        <?php foreach($this->main->menu("transaction","inventory") as $a):
          $no += 1;
          $modul = $this->main->relpace_str($a->modul,'"');
          $modul2 = $this->main->relpace_str($a->modul2,'"');
        ?>
        <li class="site-menu-item get_modul vinventory-<?= $no ?>" data-page="transaction" data-classnya="vinventory-<?= $no ?>" data-modul="<?= $modul ?>" data-modul2="<?= $modul2 ?>">
          <a class="animsition-link" href="<?= site_url($a->url); ?>">
            <span class="site-menu-title"><?= $a->nama_menu; ?></span>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
    </li>

    <?php foreach($this->main->menu("transaction") as $a):
      $no += 1;
      $modul = $this->main->relpace_str($a->modul,'"');
      $modul2 = $this->main->relpace_str($a->modul2,'"');
    ?>
    <li class="site-menu-item get_modul vt-<?= $no ?>" data-page="transaction" data-classnya="vt-<?= $no ?>" data-modul="<?= $modul ?>" data-modul2="<?= $modul2 ?>">
      <a class="animsition-link" href="<?= site_url($a->url); ?>">
        <i class="site-menu-icon fa-exchange" aria-hidden="true"></i>
        <span class="site-menu-title"><?= $a->nama_menu; ?></span>
      </a>
    </li>
    <?php endforeach; ?>
    
    <li class="site-menu-item has-sub">
      <a href="javascript:void(0)">
        <i class="site-menu-icon fa-file" aria-hidden="true"></i>
        <span class="site-menu-title"><?= $this->lang->line('lb_report') ?></span>
        <span class="site-menu-arrow"></span>
      </a>
      <ul class="site-menu-sub">
        <?php foreach($this->main->menu("report") as $a): ?>
        <li class="site-menu-item">
          <a class="animsition-link" href="<?= site_url($a->url); ?>">
            <span class="site-menu-title"><?= $a->nama_menu; ?></span>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
    </li>
    <?php if($this->session->hak_akses == "super_admin"): ?>
    <li class="site-menu-item has-sub">
      <a href="javascript:void(0)">
        <i class="site-menu-icon wb-settings" aria-hidden="true"></i>
        <span class="site-menu-title"><?= $this->lang->line('lb_setting') ?></span>
        <span class="site-menu-arrow"></span>
      </a>
      <ul class="site-menu-sub">
        <?php foreach($this->main->menu("setting") as $a): ?>
        <li class="site-menu-item">
          <a class="animsition-link" href="<?= site_url($a->url); ?>">
            <span class="site-menu-title"><?= $a->nama_menu; ?></span>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
    </li>
    <?php endif; ?>
  </ul>
</div>