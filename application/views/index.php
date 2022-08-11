<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
<head>
  <?php $this->load->view("meta"); ?>
  <title>Pipesys</title>
  <?php $this->load->view("shortcut"); ?>
  <!-- Stylesheets -->
  <!-- Plugins -->
  <link rel="stylesheet" href="<?= base_url("global"); ?>\vendor\animsition\animsition.min.css?v2.2.0">
  <link rel="stylesheet" href="<?= base_url("global"); ?>\vendor\asscrollable\asScrollable.min.css?v2.2.0">
  <link rel="stylesheet" href="<?= base_url("global"); ?>\vendor\switchery\switchery.min.css?v2.2.0">
  <link rel="stylesheet" href="<?= base_url("global"); ?>\vendor\intro-js\introjs.min.css?v2.2.0">
  <link rel="stylesheet" href="<?= base_url("global"); ?>\vendor\slidepanel\slidePanel.min.css?v2.2.0">
  <link rel="stylesheet" href="<?= base_url("global"); ?>\vendor\jquery-mmenu\jquery-mmenu.min.css?v2.2.0">
  <link rel="stylesheet" href="<?= base_url("global"); ?>\vendor\flag-icon-css\flag-icon.min.css?v2.2.0">
  <!-- Fonts -->
  <?php $this->load->view("maincss"); ?>
  <?php $this->load->view("font"); ?>
  <script src="<?= base_url("global"); ?>\vendor\jquery\jquery.min.js"></script>  
  <?php $this->load->view("mainjs"); ?>
  <script src="<?= base_url("global"); ?>\vendor\breakpoints\breakpoints.min.js"></script>
  <script>
    Breakpoints();
  </script>
  <link href="<?= base_url("aset/css/ui.css"); ?>" rel="stylesheet">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body class="site-navbar-small vbody" 
  data-CompanyID="<?= $this->session->CompanyID ?>" data-datasetting="<?= $this->session->DataSetting ?>" 
  data-start_date="<?= $this->main->default_setting_parameter('datasetting','StartDate') ?>" 
  data-end_date="<?= $this->main->default_setting_parameter('datasetting','EndDate') ?>" data-amountdecimal="<?= $this->session->amountdecimal ?>" data-qtydecimal="<?= $this->session->qtydecimal ?>" data-branch_id="<?= $this->session->BranchID ?>" data-branch_name="<?= $this->session->BranchName ?>"
  data-currency="<?= $this->session->currency ?>"
>
  <?php if($this->session->StatusAccount == "trial"): ?>
  
  <?php endif; ?>
  <!-- <?php //$this->load->view("page_loader"); ?> -->
  <?php 
  $this->load->view("navbar"); 
  $this->load->view("sidebar");
   if($this->session->StatusVerify == 0):
    $this->load->view("trial_info");
  endif;
  $this->load->view($page);
  ?>
  <!-- Footer -->
  <footer class="site-footer">
    <div class="site-footer-legal">© 2018 <a href="javascript:;">Pipesys</a></div>
    <div class="site-footer-right">
      Developed by <a href="https://www.rcelectronic.co.id/">RC Electronic,CV</a>
    </div>
  </footer>
  <div id="toTop"><i class="fa fa-angle-up"></i></div>


  <!-- load modal -->
  <?php $this->load->view("modal/modal_expire"); ?>

  <!-- Core  -->
  <script src="<?= base_url("global"); ?>\vendor\bootstrap\bootstrap.min.js"></script>
  <!-- Plugins -->
  <script src="<?= base_url("global"); ?>\vendor\jquery-mmenu\jquery.mmenu.min.all.js"></script>
  <!-- Scripts -->
  <script src="<?= base_url("global"); ?>\js\core.min.js"></script>
  <script src="<?= base_url("aset"); ?>\js\site.min.js"></script>
  <script src="<?= base_url("aset/plugin/redirect/jquery.redirect.js") ?>"></script>
  <script src="<?= base_url('aset/plugin/math.min.js') ?>"></script>

  <!-- <script src="<?= base_url("aset"); ?>\js\sections\menu.min.js"></script> -->
  <script src="<?= base_url("aset"); ?>\js\sections\menubar.min.js"></script>
  <!-- <script src="<?= base_url("aset"); ?>\js\sections\sidebar.min.js"></script> -->

  <script src="<?= base_url("global"); ?>\js\components\panel.min.js"></script>

  <script>
    (function(document, window, $) {
      'use strict';

      var Site = window.Site;
      $(document).ready(function() {
        Site.run();
      });
    })(document, window, jQuery);
  </script>


 <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-123879084-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-123879084-1');
</script>

</body>

</html>