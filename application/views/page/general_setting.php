<div class="page page-data" data-hakakses="<?= $this->session->hak_akses; ?>" data-page_name="<?= $title; ?>" data-modul="<?= $modul; ?>" data-url_modul="<?= $url_modul; ?>" >
  <div class="page-header">
  </div>
  <div class="page-content page-center <?= $modul; ?>">
   <ul class="nav nav-menu nav-pills mb-3r">
      <li><a href="<?= site_url("settings/general"); ?>"><?= $this->lang->line("lb_general") ?></a></li>
      <li><a href="<?= site_url("settings/module_user"); ?>">Module & User</a></li>
      <!-- <li><a href="<?= site_url("settings/main-menu"); ?>">Menu</a></li> -->
      <li><a href="<?= site_url("settings/slideshow"); ?>">Slideshow</a></li>
      <!-- <li><a href="<?= site_url("settings/social-media"); ?>">Social Media</a></li>  -->
   </ul>
   <form enctype="multipart/form-data" method="post"  id="<?= $modul; ?>">
      <input type="hidden" name="modul_page" value="<?= $modul ?>">
      <?php if($modul == "general"): ?>
        <div class="panel panel-btn p-0">
          <div class="panel-heading clearfix">
            <div class="pull-left mt-5"><span><?= $this->lang->line('lb_site_profile') ?></span></div>
            <div class="pull-right">
              <span class="btn-group">
                 <?= $this->main->general_button('general_save'); ?>
              </span>
            </div>
          </div>
          <div class="panel-body mt-20">
            <div class="row">
              <div class="col-sm-3 vSiteLogo">
                  <div class="form-group text-center">
                     <input type="file" id="SiteLogo" name="SiteLogo" class="dropify file_type" accept="image/*" data-height="110" data-max-file-size="2M" data-allowed-file-extensions="png jpg">
                     <label><strong><?= $this->lang->line('lb_logo') ?></strong></label>
                  </div>
               </div>
               <div class="col-sm-3 vSiteLogoSmall">
                  <div class="form-group text-center">
                     <input type="file" id="SiteLogoSmall" name="SiteLogoSmall" class="dropify file_type" accept="image/*" data-height="110" data-max-file-size="2M" data-allowed-file-extensions="png jpg">
                     <label><strong><?= $this->lang->line('lb_logo_small') ?></strong></label>
                  </div>
               </div>
               <div class="col-sm-12">
                  <div class="form-group">
                     <label><?= $this->lang->line('lb_site_title') ?></label>
                     <input type="text" name="SiteTitle" class="form-control">
                  </div>
                  <div class="form-group">
                     <label><?= $this->lang->line('lb_site_tagline') ?></label>
                     <input type="text" name="SiteTagLine" class="form-control">
                  </div>
               </div>
            </div>
          </div>

          <div class="panel-heading clearfix mt-20">
            <div class="pull-left">
              <span><?= $this->lang->line('lb_company_info') ?></span>
            </div>
            <div class="pull-right">
              <span class="pointer" onclick="hide_header(this,'.vcompany')"><i class="fa fa-chevron-up"></i></span>
            </div>
          </div>
          <div class="panel-body mt-20 vcompany">
            <div class="row">
              <div class="form-group col-sm-12">
                 <label><?= $this->lang->line('lb_company_name') ?></label>
                 <input type="text" name="CompanyName" class="form-control">
              </div>
              <div class="form-group col-sm-12">
                 <label><?= $this->lang->line('lb_address') ?></label>
                 <textarea name="Address" class="form-control"></textarea>
              </div>
              <div class="form-group col-sm-12">
                 <label><?= $this->lang->line('lb_address_link') ?></label>
                 <input type="text" name="LinkAddresss" class="form-control">
              </div>
              <div class="form-group col-sm-6">
                 <label><?= $this->lang->line('lb_telephone') ?></label>
                 <input type="text" name="Telephone" class="form-control">
              </div>
              <div class="form-group col-sm-6">
                 <label><?= $this->lang->line('lb_whatsapp') ?> 1</label>
                 <input type="text" name="Whatsapp1" class="form-control">
              </div>
              <div class="form-group col-sm-6">
                 <label><?= $this->lang->line('lb_whatsapp') ?> 2</label>
                 <input type="text" name="Whatsapp2" class="form-control">
              </div>
              <div class="form-group col-sm-6">
                 <label><?= $this->lang->line('lb_email') ?></label>
                 <input type="text" name="Email" class="form-control">
              </div>
            </div>
          </div>

          <div class="panel-heading clearfix mt-20">
            <div class="pull-left">
              <span><?= $this->lang->line('lb_generate_file') ?></span>
            </div>
            <div class="pull-right">
              <span class="pointer" onclick="hide_header(this,'.vgenerate_file')"><i class="fa fa-chevron-up"></i></span>
            </div>
          </div>
          <div class="panel-body mt-20 vgenerate_file">
            <div class="row">
              <div class="btn-group">
                <?= $this->main->general_button('general_blue2','generate_language()',$this->lang->line('lb_generate_language')) ?>
                <?= $this->main->general_button('general_blue2','generate_language()','!') ?>
              </div>
            </div>
          </div>
        </div>
      <?php elseif($modul == "generate-file"): ?>
        <div class="panel  panel-btn">
          <div class="panel-body">
            <?= $this->main->general_button('general_blue2','generate_language()',$this->lang->line('lb_generate_language')) ?>
          </div>
        </div>
      <?php endif; ?>
      <?php if($modul == "module_user"): ?>
      <div class="panel panel-btn p-0">
         <div class="panel-heading clearfix border-bottom-1ddd">
            <span class="btn-group pull-right">
            <a href="javascript:;" onclick="save_setting(this)" data-modul="<?= $modul; ?>" class="btn btn-default btn-sm">Save Setting</a>
            </span>
            <span>Module & User Price Setting</span>
         </div>
         <div class="panel-body">
            <div class="row div-module"></div>
            <div class="row">
              <div class="form-group col-sm-12" style="padding-bottom: 25px;">
                <a href="javascript:void(0)" onclick="add_module()" class="pull-right"><?= $this->lang->line('lb_add_module') ?></a>
              </div>
            </div>
         </div>
      </div>
      <?php endif; ?>
      <?php if($modul == "slideshow"): ?>
        <div class="panel panel-btn p-0">
           <div class="panel-heading clearfix border-bottom-1ddd">
              <span>Slideshow</span>
           </div>
           <!-- <input type="hidden" name="ArticleID">
           <input type="hidden" name="ArticleIDeng"> -->
           <div class="panel-body" style="padding: 0px">
             <ul class="nav nav-tabs" style="background: #ced0d2">
                 <li class="active tab-indo" style="width: 50%;text-align: center;" onclick="language('indonesia')"><a data-toggle="tab" href="#home">Indonesia</a></li>
                 <li class="tab-eng" style="width: 50%;text-align: center;" onclick="language('english')"><a data-toggle="tab" href="#menu1">English</a></li>
             </ul>
           </div>
           <div class="panel-body">
              <div class="row">
                 <div class="col-sm-12">
                    <div class="div-img-preview mb-20">
                       <div class="main-img-preview">
                          <img class="thumbnail img-preview h200" src="#" title="Preview Logo">
                       </div>
                       <div class="input-group">
                          <input id="fakeUploadLogo" class="form-control fake-shadow" placeholder="Choose File" disabled="disabled">
                          <div class="input-group-btn">
                             <div class="fileUpload btn btn-danger fake-shadow">
                                <span><i class="fa fa-upload" aria-hidden="true"></i> Upload</span>
                                <input id="logo-id" name="Image" type="file" class="attachment_upload">
                             </div>
                          </div>
                       </div>
                    </div>
                 </div>
                 <div class="col-sm-6">
                    <div class="form-group vindo">
                       <input type="hidden" name="AttachmentID" class="form-control" placeholder="Name">
                       <input type="text" name="Name" class="form-control" placeholder="Title">
                    </div>
                    <div class="form-group veng">
                       <input type="hidden" name="AttachmentIDeng" class="form-control" placeholder="Nameeng">
                       <input type="text" name="Nameeng" class="form-control" placeholder="Title">
                    </div>
                 </div>
                 <div class="col-sm-3 col-xs-6">
                    <div class="form-group vindo">
                       <select name="Position" class="form-control">
                          <option value="left">Left</option>
                          <option value="right">Right</option>
                          <option value="center">Center</option>
                       </select>
                    </div>
                    <div class="form-group veng">
                       <select name="Positioneng" class="form-control">
                          <option value="left">Left</option>
                          <option value="right">Right</option>
                          <option value="center">Center</option>
                       </select>
                    </div>
                 </div>
                 <div class="col-sm-3 col-xs-6">
                    <div class="form-group vindo">
                       <input type="color" name="NameColor" class="form-control" placeholder="Name">
                    </div>
                    <div class="form-group veng">
                       <input type="color" name="NameColoreng" class="form-control" placeholder="Name">
                    </div>
                 </div>
                 <div class="col-sm-12 ">
                    <span class="btn btn-sm btn-white mb-10" data-toggle="collapse" data-parent="#accordion" href="#show-more">show more</span>
                    <div id="show-more" class="collapse">                        
                       <div class="form-group vindo">
                          <textarea type="text" name="Description" class="form-control " placeholder="Description" maxlength="200"></textarea>
                       </div>
                       <div class="form-group veng">
                          <textarea type="text" name="Descriptioneng" class="form-control " placeholder="Description" maxlength="200"></textarea>
                       </div>
                       <div class="form-group " >
                         <div class="input-group mb-15" id="BtnID-1">
                           <span class="input-group-addon" style="width: 10%">Button Link</span>
                           <input type="hidden" name="BtnID[]" value="1">
                           <input type="text" name="BtnName[]" class="BtnName form-control pull-left" style="width: 30%;" placeholder="Title Button">
                           <input type="text" name="BtnLink[]" class="BtnLink form-control pull-left" style="width: 50%" placeholder="Link Button">
                           <select name="BtnColor[]" class="BtnColor form-control pull-left" style="width: 20%">
                             <option value="blue">Blue</option>
                             <option value="white">White</option>
                           </select>
                        </div>
                       </div>
                    </div>
                  </div>
                 <div class="col-sm-12">
                    <div class="btn-group width-100">                        
                       <a href="javascript:;" onclick="reset_form(this)" data-modul="<?= $modul; ?>" class="btn btn-white width-50">Reset</a>
                       <a href="javascript:;" onclick="save_setting(this)" data-modul="<?= $modul; ?>" class="btn btn-default width-50">Save Slideshow</a>
                    </div>
                 </div>
              </div>
           </div>
        </div>
        <ul class="list-data-slideshow list-drag ">
        </ul>
      <?php endif; ?>
   </form>
  </div>
</div>

<link rel="stylesheet" href="<?= base_url('aset/css/dropify.min.css'); ?>">
<link rel="stylesheet" href="<?= base_url('aset/css/components.css'); ?>">
<link rel="stylesheet" href="<?= base_url('aset/css/core.css'); ?>">
<script src="<?= base_url('aset/js/dropify.min.js'); ?>"></script>
<script src="<?= base_url('aset/js/page/general_settings.js'.$this->main->js_css_version()) ?>"></script>