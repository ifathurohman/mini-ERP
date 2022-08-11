<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="RC Electronic">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php 
    if (isset($meta)):
        echo $meta;
    else:
    ?>
    <meta name="description" content="IoT Indonesia, Internet of Things, Smart House, Smart Home, Smart Office, Smart Apartment, Pagar Remote, Pagar Elektrik, Pagar Otomatis, Lampu Otomatis, Home Automation, Rumah Canggih">
    <meta name="keywords" content="IoT Indonesia, Internet of Things, Smart House, Smart Home, Smart Office, Smart Apartment, Pagar Remote, Pagar Elektrik, Pagar Otomatis, Lampu Otomatis, Home Automation, Rumah Canggih">
    <?php endif; ?>
    <title><?= $title; ?></title>
    <link rel="shortcut icon" href="<?= $this->frontend->set_('Icon'); ?>">
    <link rel="stylesheet" href="<?= base_url('aset/frontend/plugin/swiper/swiper.min.css'); ?>">
    <?php $this->load->view("frontend_new/aset_css"); ?>
    <script src="<?= base_url('/aset/js/jquery-2.1.4.min.js'); ?>"></script>
    <script src="<?= base_url('/aset/general_app.js?'.time()) ?>"></script>
    <?php if($this->session->bahasa == "indonesia"): ?>
        <script src="<?= base_url('/aset/language_indo.js?'.time()) ?>"></script>
    <?php else: ?>
        <script src="<?= base_url('/aset/language_english.js?'.time()) ?>"></script>
    <?php endif; ?>
    <script src="<?= base_url('/aset/js/site.js?') ?>"></script>
    <script src="<?= base_url('aset/frontend/plugin/swiper/swiper.min.js'); ?>"></script>
  </head>
  <body  class="frontend page-data index-horizontal" data-index="frontend" data-devices=<?= $device ?>>
    <?php $this->load->view("frontend_new/navbar_horizontal"); ?>
    <div class="body-content">
    <?php $this->load->view($content);?>
    </div>
    <!-- Footer -->
    <!-- Bootstrap core JavaScript -->
    <?php $this->load->view("frontend_new/aset_js"); ?>
    <!-- <script src="<?= site_url("aset/js/bootstrap.min.js"); ?>"></script> -->
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
    $('a[data-slide]').on('click', function () {
        swiper.slideTo($(this).data('slide'));
    });
    </script>
    <script type="text/javascript">
      function module_modal() {
        $("#modal-module").modal("show");
      }
    </script>
  </body>
</html>
