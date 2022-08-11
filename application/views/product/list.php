<div class="page page-data" data-module="inventory" data-modul="<?= $modul ?>" data-url_modul="<?= $url_modul ?>" data-date="<?= date("Y-m-d") ?>" data-hakakses="<?= $this->session->hak_akses ?>" data-costmethod="<?= $this->session->CostMethod ?>" data-title="<?= $title ?>">
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
            <?php if($category<=0): ?>
            <div class="alert dark alert-warning alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
              <?= $this->lang->line('lb_category_not_data') ?> <a href="<?= site_url('category') ?>"><?= $this->lang->line('lb_category_add') ?></a>
            </div>
            <?php endif; ?>
            <form id="form-filter" autocomplete="off" method="post">
              <div class="row">
                <div class="form-group col-sm-3">
                  <label class="control-label"><?= $this->lang->line('lb_delete'); ?></label>
                  <select name="fActive" class="form-control">
                    <option value="none"><?= $this->lang->line('lb_all'); ?></option>
                    <option value="1" selected><?= $this->lang->line('btn_undelete'); ?></option>
                    <option value="0"><?= $this->lang->line('lb_deleted1'); ?></option>
                  </select>
                  <span class="help-block"></span>
                </div>
              <div class="form-group col-sm-3">
                  <label class="control-label"><?= $this->lang->line('lb_inventory'); ?></label>
                  <select name="fProductType" class="form-control">
                    <option value="none"><?= $this->lang->line('lb_inventory_select'); ?></option>
                    <option value="item"><?= $this->lang->line('lb_yes'); ?></option>
                    <option value="service"><?= $this->lang->line('lb_no'); ?></option>
                  </select>
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-3">
                  <label class="control-label"><?= $this->lang->line('lb_selling1'); ?></label>
                  <select name="fSalesType" class="form-control">
                    <option value="none"><?= $this->lang->line('lb_selling_select'); ?></option>
                    <option value="sell"><?= $this->lang->line('lb_yes'); ?></option>
                    <option value="nonsell"><?= $this->lang->line('lb_no'); ?></option>
                  </select>
                  <span class="help-block"></span>
                </div>
               </div>
              <div class="row">
                <div class="form-group col-sm-3 col-sm-offset-9">
                  <label class="control-label"><?= $this->lang->line('lb_search'); ?></label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="fa-search" aria-hidden="true"></i>
                    </span>
                    <input type="text" name="fSearch" class="form-control" placeholder="" id="fSearch" onkeyup="filter_table()">
                  </div>
                  <span class="help-block"></span>
                </div>
              </div>
            </form>
            <div class="btn-group mb-10">
             <?= $tambah; ?>  
            </div>
            <div class="btn-group pull-right">
                <!-- <button class="btn btn-outline btn-default" onclick="reload_table()" type="button"><i class="icon wb-reload"></i>Reload</button> -->
            </div>
            <div class="btn-group pull-right">
              <?= $this->main->general_button('import'); ?>
              <?= $this->main->general_button('export',site_url('product/export')) ?>
              <?= $this->main->general_button('search'); ?>
              <?= $this->main->general_button('reload'); ?>
            </div>
          </div>
        </div>
        <table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
          <thead>
             <tr>
                <th><?= $this->lang->line('lb_no'); ?></th>
                <th><?= $this->lang->line('lb_image'); ?></th>
                <th><?= $this->lang->line('lb_product_code'); ?></th>
                <th><?= $this->lang->line('lb_product_name'); ?></th>
                <th><?= $this->lang->line('lb_category'); ?></th>
                <th><?= $this->lang->line('lb_min_qty'); ?></th>
                <th><?= $this->lang->line('lb_qty'); ?></th>
                <th><?= $this->lang->line('lb_unit'); ?></th>
                <!-- <th>Konv.</th> -->
                <th><?= $this->lang->line('lb_selling_price'); ?></th>
                <!-- <th><?= $this->lang->line('lb_average_price'); ?></th> -->
                <th><?= $this->lang->line('lb_inventory'); ?></th>
                <th><?= $this->lang->line('lb_selling1'); ?></th>
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
<?php $this->load->view("product/modal_import"); ?>
<?php $this->load->view("product/modal_view"); ?>
<?php $this->load->view("product/modal_view_serial"); ?>
<?php $this->load->view("modal/modal_notransaksi"); ?>
<?php $this->load->view("modal/modal_vendor_price"); ?>
<?php $this->load->view("modal/modal_product"); ?>
<?php $this->load->view("modal/modal_import_view"); ?>
<!-- Plugins For This Page -->
<link rel="stylesheet" href="<?= base_url('aset/css/dataTables.bootstrap.min.css'.$this->main->js_css_version()); ?>">
<script src="<?= base_url('aset/js/jquery.dataTables.min.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/dataTables.bootstrap.min.js'.$this->main->js_css_version()); ?>"></script>
<!-- Memanggil Autocomplete.js -->
<script src="<?= base_url('aset/plugin/jquery.autocomplete.min.js') ?>"></script>

<!-- Plugins For This Page -->
<script src="<?= base_url('aset/js/page/product.js'.$this->main->js_css_version()); ?>"></script>
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

