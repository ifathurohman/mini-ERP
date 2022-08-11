<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
<head>
  <?php $this->load->view("meta"); ?>
  <title><?= $title; ?> - Pipesys</title>
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

      <div class="page-login-main" data-message="<?= $this->session->flashdata('message') ?>">
        <div class="brand visible-xs">
          <img class="brand-img" src="<?= base_url("img/logo.png"); ?>" alt="..." height="80px">
        </div>
        <h3 class="font-size-24"><?= $title_form; ?></h3>
        <!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p> -->

        <form id="form" method="post">
          <?php if($page == "register"): ?>
          <div class="form-group form-material"> 
            <label class="label-control" for="module">Select Module <span class="wajib"></span></label>
            <select id="module" name="module" class="form-control pointer">
              <option value="ap">Purchase Module</option>
              <option value="ar">Sales Module</option>
              <option value="inventory">Inventory Module</option>
              <option value="ac">Accounting Module</option>
            </select>
            <span class="help-block"></span>
          </div>
          <div class="form-group form-material"> 
            <label class="label-control" for="perusahaan">Company Name</label>
            <input type="text" class="form-control" id="perusahaan" name="nama_perusahaan" placeholder="">
            <span class="help-block"></span>
          </div>
          <div class="form-group form-material">
            <label class="label-control" for="toko">Store Name</label>
            <input type="text" class="form-control" id="toko" name="nama_toko" placeholder="">
            <span class="help-block"></span>
          </div>
          <div class="form-group form-material">
          <label class="label-control" for="no_hp">Phone Number</label>
               <div class="div-phone">
                  <select id="mySelect" data-show-icon="true" class="selectpicker" name="PhoneCode">
                     <?php 
                        foreach($this->main->PhoneISO() as $a): 
                          $seleted = "";
                          if(strtolower($a->ISO) == "id"){
                            // $seleted = 'seleted="" ';
                          }
                        ?>
                     <option value="<?= $a->PhoneISO; ?>" data-content="<span class='flag-icon  flag-icon-<?= strtolower($a->ISO)?>'></span> <?= "+".$a->PhoneISO ." - ".$a->Name; ?>"></option>
                     <?php endforeach;
                        ?>
                  </select>
                  <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="ex : 8962188xxx" onkeypress="return isNumber(event)">
               </div>
                 <span class="help-block AlertPhone"></span>
              </div>
          <?php endif; ?>
          <?php if($page == "login" || $page == "register" || $page == "forgot_password"): ?>
          <div class="form-group form-material">
            <label class="label-control" for="inputEmail">Email</label>
            <input type="email" class="form-control" id="inputEmail" name="email" placeholder="">
            <span class="help-block"></span>
          </div>
          <?php endif; ?>
          <?php if($page == "login" || $page == "register" || $page == "reset_password"): ?>
          <div class="form-group form-material">
            <label class="label-control" for="inputPassword">Password</label>
            <input type="password" class="form-control inputPassword" id="inputPassword" name="password" placeholder="">
            <span class="help-block"></span>
          </div>
          <?php endif; ?>
          <?php if($page == "reset_password"): ?>
          <div class="form-group form-material">
            <label class="label-control" for="inputPassword"><?= $this->lang->line('lb_password_confirm') ?></label>
            <input type="hidden" name="id_user" value="<?= $id_user; ?>">
            <input type="password" class="form-control inputPassword" id="inputPassword" name="password_kon" placeholder="">
            <span class="help-block"></span>
          </div>
          <?php endif; ?>
          <?php if($page == "login" || $page == "register" || $page == "reset_password"): ?>
            <label><input id="shpassword" name="shpassword" onchange="show_password('inputPassword')" type="checkbox"> Show password</label>
          <?php endif; ?>
          <?php if($page == "xregister"): ?> 
          <div class="form-group form-material">
            <label class="label-control" for="toko">Pilih Aplikasi</label>
            <div class="line-checkbox">
              <div class="checkbox-custom checkbox-primary">
                <input class="icheckbox-primary " name="app[]" id="app1" type="checkbox" value="pipesys">
                <label for="app1">Pipesys</label>                      
              </div>
              <div class="checkbox-custom checkbox-primary">
                <input class="icheckbox-primary " name="app[]" id="app2" type="checkbox" value="salespro">
                <label for="app2">Sales Pro</label>                      
              </div>
            </div>
          </div>
          <?php endif;?>
          <?php if($page == "verification_account"): ?>
          <div class="form-group form-material">
             <label class="label-control" for="inputVerificationNumber">Enter Verification Code</label>
             <input type="hidden" name="Token" value="<?= $Token; ?>">
             <div class="div-input-kode">
                <div class="row">
                   <div class="col-sm-3 col-xs-3">
                      <input type="text" class="input" name="VerificationNumber[]" id="i1" maxlength="1" onkeyup="autoTabLimit(this,'1', 'i2')" onkeypress="return isNumber(event)">
                   </div>
                   <div class="col-sm-3 col-xs-3">
                      <input type="text" class="input" name="VerificationNumber[]" id="i2" maxlength="1" onkeyup="autoTabLimit(this,'1', 'i3')" onkeypress="return isNumber(event)">
                   </div>
                   <div class="col-sm-3 col-xs-3">
                      <input type="text" class="input" name="VerificationNumber[]" id="i3" maxlength="1" onkeyup="autoTabLimit(this,'1', 'i4')" onkeypress="return isNumber(event)">
                   </div>
                   <div class="col-sm-3 col-xs-3">
                      <input type="text" class="input" name="VerificationNumber[]" id="i4" maxlength="1" onkeyup="autoTabLimit(this,'1', 'i1')" onkeypress="return isNumber(event)">
                   </div>
                </div>
             </div>
             <span class="help-block"></span>
          </div>
          <?php endif; ?>
          <button type="submit" class="btn btn-primary btn-block" id="btn-login"><?= $btn_text; ?></button>
        </form>
        <?php if($page == "verification_account" && $this->main->AlertVerification() != 2): ?>
               <a href="<?= site_url("main/verification_account_lewat"); ?>" class="btn btn-default btn-outline btn-block" style="margin-bottom: 20px;">SKIP</a>
               <?php endif; ?>
        <?php if($page == "login"): echo "<a href=".site_url("forgot-password").">Forgot password ?</a>"; endif;?>
        <p><?= $link; ?></p>

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

 <?php if($page == "verification_account"): $this->load->view("modal/modal_verification_account"); endif; ?>
  <script src="<?= base_url("aset/js/sweetalert.min.js"); ?>"></script>
  <script src="<?= base_url("global"); ?>\vendor\bootstrap\bootstrap.min.js"></script>
  <script src="<?= base_url("global"); ?>\vendor\jquery-mmenu\jquery.mmenu.min.all.js"></script>
  <script src="<?= base_url("global"); ?>\js\core.min.js"></script>
  <script src="<?= base_url("aset"); ?>\js\site.min.js"></script>
  <script src="<?= base_url("aset"); ?>\js\sections\menubar.min.js"></script>
  <script type="text/javascript">
    function show_password(idnya){
      if ($('#shpassword').is(":checked")){
        $('.'+idnya).attr('type', "text");
      }else{
        $('.'+idnya).attr('type', "password");
      }
    }
    $(document).ready(function() {
      tag_data = $('.page-login-main').data();
      if(tag_data.message){
        swal('',tag_data.message,'warning');
      }
    });
  </script>
</body>
</html>