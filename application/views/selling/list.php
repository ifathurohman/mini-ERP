<div class="page page-data ck-module-expire" data-module="ar" data-modul="<?= $modul ?>" data-url_modul="<?= $url_modul ?>" data-date="<?= date("Y-m-d") ?>" data-hakakses="<?= $this->session->hak_akses ?>" data-title="<?= $title ?>" data-product_branch="active" data-product_branch_reset="active">
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
                <div class="form-group col-sm-2-custom">
                  <label class="control-label"><?= $this->lang->line('lb_startdate') ?></label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="fa-calendar" aria-hidden="true"></i>
                    </span>
                    <input type="text" name="fStartDate" class="form-control date pointer" placeholder="<?= $this->lang->line('lb_startdate_select') ?>" id="fStartDate" value="<?= date("Y-m-01"); ?>">
                    <span class="input-group-addon pointer" title="Remove Start Date" onclick="remove_value('fStartDate')">
                      <i class="fa-times" aria-hidden="true"></i>
                    </span>
                  </div>
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-2-custom">
                  <label class="control-label"><?= $this->lang->line('lb_enddate') ?></label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="fa-calendar" aria-hidden="true"></i>
                    </span>
                    <input type="text" name="fEndDate" class="form-control date pointer" placeholder="<?= $this->lang->line('lb_enddate_select') ?>" id="fEndDate" value="<?= date("Y-m-d"); ?>">
                    <span class="input-group-addon pointer" title="Remove End Date" onclick="remove_value('fEndDate')">
                      <i class="fa-times" aria-hidden="true"></i>
                    </span>
                  </div>
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-2-custom">
                  <label class="control-label"><?= $this->lang->line('lb_product_type') ?></label>
                  <select name="fProductType" class="form-control">
                    <option value="none"><?= $this->lang->line('lb_product_type_select') ?></option>
                    <option value="0"><?= $this->lang->line('lb_product_item') ?></option>
                    <option value="1"><?= $this->lang->line('lb_product_service') ?></option>
                  </select>
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-2-custom">
                  <label class="control-label">Status</label>
                  <select name="fStatus" class="form-control">
                    <option value="none"><?= $this->lang->line('lb_status_select') ?></option>
                    <option value="1" selected><?= $this->lang->line('btn_active') ?></option>
                    <option value="0"><?= $this->lang->line('btn_cancel') ?></option>
                  </select>
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-2-custom">
                  <label class="control-label"><?= $this->lang->line('lb_t_status') ?></label>
                  <select name="fTransactionStatus" class="form-control">
                    <option value="none"><?= $this->lang->line('lb_status_select') ?></option>
                    <option value="0"><?= $this->lang->line('lb_open') ?></option>
                    <option value="1"><?= $this->lang->line('lb_delivery1') ?></option>
                    <option value="2">Unpaid</option>
                    <option value="3"><?= $this->lang->line('lb_paid') ?></option>
                  </select>
                  <span class="help-block"></span>
                </div>

                <div class="form-group col-sm-2-custom">
                  <label><?= $this->lang->line('lb_store') ?></label>
                  <select name="fBranch" id="fBranch" class="form-control select2">
                    <option value="all"><?= $this->lang->line('lb_store_select') ?></option>
                    <?php foreach($this->main->sp_list_branch() as $a): 
                    echo '<option value="'.$a->ID.'">'.$a->Name.'</option>'; 
                    endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-sm-12">
                  <?php $this->load->view("dashboard/dashboard_sales"); ?>
                </div>
              </div> 
              <div class="row">
                <div class="form-group col-sm-3 col-sm-offset-9">
                  <label class="control-label"><?= $this->lang->line('lb_search') ?></label>
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
              <span class="btn-group">
                  <button class="btn btn-blue dropdown-toggle" data-toggle="dropdown" aria-expanded="true" title="<?= $this->lang->line('lb_information') ?>">
                    <i class="icon wb-info-circle"></i>
                  </button>   
                   <ul class="dropdown-menu" style="right: 0; left: auto;">
                    <li class="clearfix">
                      <a href="javascript:;" class="small">
                        <i class="icon fa-truck"></i> = <?= $this->lang->line('lb_delivery1') ?>
                      </a>
                    </li>
                   </ul>
                </span>
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
                <th><?= $this->lang->line('lb_customer_name') ?></th>
                <th><?= $this->lang->line('lb_store') ?></th>
                <th>Status</th>
                <th><?= $this->lang->line('lb_qty_total') ?></th>
                <th><?= $this->lang->line('lb_total') ?></th>
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

<?php $this->load->view("modal/modal_pembayaran_selling"); ?>
<?php $this->load->view("modal/modal_return_selling"); ?>
<?php $this->load->view($modal); ?>
<?php $this->load->view($modal_vendor); ?>
<?php $this->load->view("modal/modal_product"); ?>
<?php $this->load->view("modal/modal_sales"); ?>
<?php $this->load->view($modal_print); ?>
<?php $this->load->view("modal/modal_add_serial"); ?>
<?php $this->load->view("modal/modal_template"); ?>
<?php $this->load->view("modal/modal_branch"); ?>

<!-- Plugins For This Page -->
<link rel="stylesheet" href="<?= base_url('aset/css/dataTables.bootstrap.min.css'.$this->main->js_css_version()); ?>">
<script src="<?= base_url('aset/js/jquery.dataTables.min.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/dataTables.bootstrap.min.js'.$this->main->js_css_version()); ?>"></script>
<link rel="stylesheet" href="<?= base_url("aset/datepicker/bootstrap-datepicker3.css".$this->main->js_css_version()); ?>"/>
<script type="text/javascript" src="<?= base_url("aset/datepicker/bootstrap-datepicker.min.js".$this->main->js_css_version()); ?>"></script>

<link rel="stylesheet" href="<?= base_url('aset/plugin/selectpicker/bootstrap-select.min.css'); ?>">
<script src="<?= base_url('aset/plugin/selectpicker/bootstrap-select.min.js'); ?>"></script>

<link rel="stylesheet" href="<?= base_url('aset/css/select2.css'.$this->main->js_css_version()); ?>">
<script src="<?= base_url('aset/plugin/select2/select2.min.js'.$this->main->js_css_version()) ?>"></script>

<script src="<?= base_url('aset/js/page/selling.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/page/modal_payment_selling.js'); ?>"></script>
<script src="<?= base_url('aset/js/page/modal_return_selling.js'); ?>"></script>
<script src="<?= base_url('aset/js/page/vendor_modal.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/page/sales_modal.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/page/product_modal.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/page/branch_modal.js'.$this->main->js_css_version()); ?>"></script>

<script src="<?= base_url('aset/plugin/Chart.js') ?>"></script>


