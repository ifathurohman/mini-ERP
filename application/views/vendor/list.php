<div class="page page-data" data-hakakses="<?= $this->session->hakakses; ?>" data-modul="<?= $modul; ?>" data-app="<?= $this->session->app; ?>" data-page_name="<?= $title; ?>" data-url_modul="<?= $url_modul; ?>" data-id="<?= $ID ?>">
  <div class="page-header">
  </div>
  <div class="page-content">
    <!-- Panel Basic -->
    <div class="panel panel-bordered">
      <header class="panel-heading">
        <div class="panel-actions"></div>
        <h3 class="panel-title"><?= $title; ?></h3>
      </header>
      <div class="panel-body">
          <div class="row row-lg">
          <div class="col-lg-12">
            <form id="form-filter" autocomplete="off" method="post">
              <div class="row">
                <div class="form-group col-sm-3">
                  <label class="control-label">Delete</label>
                  <select name="fActive" class="form-control">
                    <option value="none">All</option>
                    <option value="1" selected>Undeleted</option>
                    <option value="0">Deleted</option>
                  </select>
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-3">
                  <label class="control-label">Partner Type</label>
                  <select name="fPosition" class="form-control">
                    <option value="0">Select Partner Type</option>
                    <option value="1">Vendor</option>
                    <option value="2">Customer</option>
                  </select>
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-sm-3 col-sm-offset-9">
                  <label class="control-label">Search</label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="fa-search" aria-hidden="true"></i>
                    </span>
                    <input type="text" name="fSearch" class="form-control" placeholder="" id="fSearch">
                  </div>
                  <span class="help-block"></span>
                </div>
              </div>
            </form>
            <div class="btn-group mb-10">
              <?= $tambah; ?>              
            </div>
              <div class="btn-group pull-right">
              <?= $this->main->general_button('import'); ?>
              <?= $this->main->general_button('export',site_url('vendor/export')); ?>
              <?= $this->main->general_button('search'); ?>
              <?= $this->main->general_button('reload'); ?>      
            </div>
          </div>
        </div>
        <br>
         <table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
          <thead>
             <tr>
             <?php if($modul == "partner"): ?>
                <th>No</th>
                <th>Code</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Address</th>
             
                <?php if($this->session->app == "pipesys"): ?>
                <th>Partner Type</th>
                <th>Remark</th>
                <?php endif; ?>
                <!-- <th style="width:125px;">Action</th> -->
              <?php else: ?>
                <th>No</th>
                <th>Name</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Remark</th>
                <th>Device</th>
                <th style="width:125px;">Action</th>
              <?php endif; ?>
             </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
    <!-- End Panel Basic -->
  </div>
</div>
<?php $this->load->view($modal); ?>
<?php $this->load->view("vendor/modal_import"); ?>
<?php $this->load->view("modal/modal_import_view"); ?>

<?php if($this->session->app == "salespro"): ?>
<script  src="https://maps.googleapis.com/maps/api/js?key=<?= $this->config->item("gmap_api"); ?>&libraries=places"></script>
<?php endif; ?>

<!-- Plugins For This Page -->
<link rel="stylesheet" href="<?= base_url('aset/css/dataTables.bootstrap.min.css'.$this->main->js_css_version()); ?>">
<script src="<?= base_url('aset/js/jquery.dataTables.min.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/dataTables.bootstrap.min.js'.$this->main->js_css_version()); ?>"></script>
<!-- Plugins For This Page -->
<link rel="stylesheet" href="<?= base_url('aset/css/dropify.min.css'); ?>">
<script src="<?= base_url('aset/js/page/vendor.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/dropify.min.js'); ?>"></script>
<script type="text/javascript">
var attc;
$(document).ready(function(){
    $('.dropify').dropify();
});
</script>
<style type="text/css">.
.modal {
  z-index: 2 !important;
}
.pac-container {
  z-index: 1800 !important;
}
</style>