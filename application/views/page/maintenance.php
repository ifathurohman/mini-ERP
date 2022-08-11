<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
<head>
  <?php $this->load->view("meta"); ?>
  <title>Pipesys</title>
  <?php $this->load->view("shortcut"); ?>
  <?php $this->load->view("maincss"); ?>
  <link rel="stylesheet" href="<?= base_url("global"); ?>\vendor\flag-icon-css\flag-icon.min.css?v2.2.0">
  <link rel="stylesheet" href="<?= base_url("aset/css/login-v2.min.css"); ?>">
  <link rel="stylesheet" href="<?= base_url("aset/css/sweetalert.css"); ?>">
  <?php $this->load->view("font"); ?>
  <?php $this->load->view("mainjs"); ?>
   <link rel="stylesheet" href="<?= base_url('aset/plugin/selectpicker/bootstrap-select.min.css'); ?>">
  <script src="<?= base_url('aset/plugin/selectpicker/bootstrap-select.min.js'); ?>"></script>
</head>
<body class="page-login-v2 layout-full page-dark bg-one">
  <?php $this->load->view("frontend/navbar");  ?>
  <div class="page animsition" data-animsition-in="fade-in" data-animsition-out="fade-out">
    <div class="page-content">
      <div class="page-brand-info">
        <div class="brand">
          <img class="brand-img" src="<?= $this->frontend->set_('Logo'); ?>" alt="..." height="80px" style="background: #fff;border-radius: 5px;">
          <!-- <h2 class="brand-text font-size-40"><?= $this->main->getTitleApp(); ?></h2> -->
        </div>
        <p class="font-size-20" style="font-weight: bolder;color: #fff">Pipesys is the ERP system used to help business process in purchasing, sales, inventory, and accounting transactions.</p>
      </div>

      <div class="page-login-main" data-message="<?= $this->session->flashdata('message') ?>" style="text-align: center;">
        <div class="brand visible-xs">
          <img class="brand-img" src="<?= base_url("img/logo.png"); ?>" alt="..." height="80px">
        </div>
        <h2>Maintenance</h2>
        <form id="form" method="post">
          <p class="m2-txt1">
            Our website is currently undergoing scheduled maintenance. We Should be back shortly. Thank you for your patience.
          </p>
        </form>

        <footer class="page-copyright">
          <p>2018 Developed by <a href="https://www.rcelectronic.co.id" target="_blank" style="color: black;">RC Electronic,CV</a></p>
          <div class="social">
            <a class="btn btn-icon btn-round social-twitter" target="_blank" href="https://twitter.com/CvRcelectronic">
              <i class="icon bd-twitter" aria-hidden="true"></i>
            </a>
            <a class="btn btn-icon btn-round social-facebook" target="_blank" href="https://www.facebook.com/CV-RC-Electronic-1037346079640114/">
              <i class="icon bd-facebook" aria-hidden="true"></i>
            </a>
            <a class="btn btn-icon btn-round social-google-plus" target="_blank" href="https://www.rcelectronic.co.id/">
              <i class="icon icon-web-white" aria-hidden="true"></i>
            </a>
          </div>
        </footer>
      </div>

    </div>
  </div>

  <script src="<?= base_url("global"); ?>\vendor\bootstrap\bootstrap.min.js"></script>
  <script src="<?= base_url("global"); ?>\vendor\jquery-mmenu\jquery.mmenu.min.all.js"></script>
  <script src="<?= base_url("global"); ?>\js\core.min.js"></script>
  <script src="<?= base_url("aset"); ?>\js\site.min.js"></script>
  <script src="<?= base_url("aset"); ?>\js\sections\menubar.min.js"></script>
</body>
</html>