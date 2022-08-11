<div class="page page-data ck-module-expire" data-module="ap" data-modul="<?= $modul ?>" data-url_modul="<?= $url_modul ?>" data-date="<?= date("Y-m-d") ?>" data-hakases="<?= $this->session->hak_akses ?>" data-id="<?= $ID ?>" data-statusid="<?= $Status ?>" data-title="<?= $title ?>">
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
                  <label class="control-label">Start Date</label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="fa-calendar" aria-hidden="true"></i>
                    </span>
                    <input type="text" name="fStartDate" class="form-control date pointer" placeholder="" id="fStartDate" value="<?= date("Y-m-01"); ?>">
                    <span class="input-group-addon pointer" title="Remove Start Date" onclick="remove_value('fStartDate')">
                      <i class="fa-times" aria-hidden="true"></i>
                    </span>
                  </div>
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-3">
                  <label class="control-label">End Date</label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="fa-calendar" aria-hidden="true"></i>
                    </span>
                    <input type="text" name="fEndDate" class="form-control date pointer" placeholder="" id="fEndDate" value="<?= date("Y-m-d"); ?>">
                    <span class="input-group-addon pointer" title="Remove End Date" onclick="remove_value('fEndDate')">
                      <i class="fa-times" aria-hidden="true"></i>
                    </span>
                  </div>
                  <span class="help-block"></span>
                </div>
                <!-- <div class="form-group col-sm-3">
                  <label class="control-label">Product Type</label>
                  <select name="fProductType" class="form-control">
                    <option value="none">Select Product Type</option>
                    <option value="0">Item Product</option>
                    <option value="1">Service</option>
                  </select>
                  <span class="help-block"></span>
                </div> -->
                <div class="form-group col-sm-3">
                  <label class="control-label">Status</label>
                  <select name="fStatus" class="form-control">
                    <option value="none">Select Status</option>
                    <option value="1" selected>Active</option>
                    <option value="0">Cancel</option>
                  </select>
                  <span class="help-block"></span>
                </div>
                <!-- <div class="form-group col-sm-3">
                  <label class="control-label">Type Order</label>
                  <select name="fTypeStatus" class="form-control">
                    <option value="none">Select Type</option>
                    <option value="1">Purchase Order</option>
                    <option value="2">Non Purchase Order</option>
                  </select>
                  <span class="help-block"></span>
                </div> -->
              </div>
              <div class="row">
                <div class="form-group col-sm-12">
                  <?php $this->load->view("dashboard/dashboard_purchase1"); ?>
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
              <?= $this->main->general_button('search'); ?>
              <?= $this->main->general_button('reload'); ?>
            </div>
          </div>
        </div>
        <br>
        <table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
          <thead>
             <tr>
                <th><?= $this->lang->line('lb_no') ?></th>
                <th><?= $this->lang->line('lb_transaction_no') ?></th>
                <th><?= $this->lang->line('lb_date') ?></th>
                <th><?= $this->lang->line('lb_vendor_name') ?></th>
                <th><?= $this->lang->line('lb_total') ?></th>
                <th><?= $this->lang->line('lb_total_paid') ?></th>
                <th>Status</th>
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
<?php $this->load->view($modal_vendor); ?>
<?php $this->load->view("modal/modal_template"); ?>

<!-- Plugins For This Page -->
<link rel="stylesheet" href="<?= base_url('aset/css/dataTables.bootstrap.min.css'.$this->main->js_css_version()); ?>">
<script src="<?= base_url('aset/js/jquery.dataTables.min.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/dataTables.bootstrap.min.js'.$this->main->js_css_version()); ?>"></script>
<link rel="stylesheet" href="<?= base_url("aset/datepicker/bootstrap-datepicker3.css".$this->main->js_css_version()); ?>"/>
<script type="text/javascript" src="<?= base_url("aset/datepicker/bootstrap-datepicker.min.js".$this->main->js_css_version()); ?>"></script>

<link rel="stylesheet" href="<?= base_url('aset/plugin/selectpicker/bootstrap-select.min.css'.$this->main->js_css_version()); ?>">
<script src="<?= base_url('aset/plugin/selectpicker/bootstrap-select.min.js'.$this->main->js_css_version()); ?>"></script>

<script src="<?= base_url('aset/js/page/payment_ap.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/page/vendor_modal.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/plugin/Chart.js') ?>"></script>


