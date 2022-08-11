<div class="page page-data" data-modul="<?= $modul ?>" data-url_modul="<?= $url_modul ?>">
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
            <div class="btn-group">
              <?= $tambah; ?>
              <button type="button" class="btn btn-danger btn-cancel content-hide" onclick="cancel()" >Cancel</button>
            </div>
            <div class="btn-group pull-right">
                <button class="btn btn-info vimport content-hide" onclick="import_data()" type="button"><i class="icon wb-file"></i>Import</button>
                <a href="<?= site_url("conversion_balance/export"); ?>" class="btn btn-info btn-filter"><i class="icon wb-download"></i>Export</a>
                <button class="btn btn-blue btn-filter" onclick="get_data()" type="button"><i class="icon wb-reload"></i>Reload</button>
            </div>
          </div>
        </div>
        <form id="form" autocomplete="off" class="mt-10">
          <div class="row">
            <div class="form-group col-sm-6 vdate content-hide">
              <label class="control-label">Conversion Date</label>
              <div class="input-group col-sm-6">
                <span class="input-group-addon">
                  <i class="fa-calendar" aria-hidden="true"></i>
                </span>
                <input type="text" name="Date" class="form-control date pointer" placeholder="" id="fStartDate" value="<?= date("Y-m-d"); ?>">
              </div>
              <span class="red_txt">You will still be able to enter transactions before conversion date and it will not affect your current balances (balance as at after conversion date)</span>
              <span class="help-block"></span>
            </div>
          </div>
          <table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
            <thead>
               <tr>
                  <th>No</th>
                  <th>Code</th>
                  <th>Name</th>
                  <th>Level</th>
                  <th>Parent COA</th>
                  <th>Debit</th>
                  <th>Credit</th>
               </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="5">Total</th>
                <th><input type="hidden" name="totalDebit" class="duit"><span class="totalDebit"></span></th>
                <th><input type="hidden" name="totalCredit" class="duit"><span class="totalCredit"></span></th>
              </tr>
            </tfoot>
          </table>
        </form>
      </div>
    </div>
    <!-- End Panel Basic -->
  </div>
</div>

<?php $this->load->view('conversion_balance/modal_import'); ?>

<!-- Plugins For This Page -->
<link rel="stylesheet" href="<?= base_url('aset/css/dataTables.bootstrap.min.css'.$this->main->js_css_version()); ?>">
<script src="<?= base_url('aset/js/jquery.dataTables.min.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/dataTables.bootstrap.min.js'.$this->main->js_css_version()); ?>"></script>
<link rel="stylesheet" href="<?= base_url("aset/datepicker/bootstrap-datepicker3.css".$this->main->js_css_version()); ?>"/>
<script type="text/javascript" src="<?= base_url("aset/datepicker/bootstrap-datepicker.min.js".$this->main->js_css_version()); ?>"></script>
<link rel="stylesheet" href="<?= base_url('aset/css/dropify.min.css'); ?>">
<script src="<?= base_url('aset/js/dropify.min.js'); ?>"></script>

<script src="<?= base_url('aset/js/page/conversion_balance.js'.$this->main->js_css_version()); ?>"></script>


