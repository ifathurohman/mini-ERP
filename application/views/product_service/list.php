<div class="page page-data" data-hakakses="<?= $this->session->hak_akses; ?>">
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
                <button class="btn btn-outline btn-default" onclick="filter_table()" type="button"><i class="icon wb-search"></i>Search</button>
                <!-- <button class="btn btn-outline btn-default" onclick="reload_table()" type="button"><i class="icon wb-reload"></i>Reload</button> -->
            </div>
            <div class="btn-group pull-right">
              <button class="btn btn-outline btn-default" type="button" onclick="modal_import()"><i class="icon fa fa-download"></i>Import</button>                    
              <button class="btn btn-outline btn-default" onclick="reload_table()" type="button"><i class="icon wb-reload"></i>Reload</button>              
            </div>
          </div>
        </div>
        <br>
        <table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
          <thead>
             <tr>
                <th>No</th>
                <th>Product Image</th>
                <th>Product Code</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Selling Price</th>
                <th>Action</th>
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
<?php $this->load->view("product_service/modal_import"); ?>
<?php $this->load->view("product_service/modal_view"); ?>
<?php $this->load->view("product_service/modal_view_serial"); ?>
<?php $this->load->view("modal/modal_notransaksi"); ?>
<?php $this->load->view("modal/modal_vendor_price"); ?>
<?php $this->load->view("modal/modal_product"); ?>
<!-- Plugins For This Page -->
<link rel="stylesheet" href="<?= base_url('aset/css/dataTables.bootstrap.min.css'.$this->main->js_css_version()); ?>">
<script src="<?= base_url('aset/js/jquery.dataTables.min.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/dataTables.bootstrap.min.js'.$this->main->js_css_version()); ?>"></script>
<!-- Plugins For This Page -->
<script src="<?= base_url('aset/js/page/product_service.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/page/modal_vendor_price.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/page/product_modal.js'.$this->main->js_css_version()); ?>"></script>
<link rel="stylesheet" href="<?= base_url('aset/css/dropify.min.css'); ?>">
<script src="<?= base_url('aset/js/dropify.min.js'); ?>"></script>
<script type="text/javascript">
var attc;
$(document).ready(function(){
    $('.dropify').dropify();
});
</script>

