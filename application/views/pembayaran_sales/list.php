<div class="page page-data" data-hakakses="<?= $this->session->hak_akses; ?>" data-title="<?= $title ?>">
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
            </div>
            <div class="btn-group pull-right">
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
                <th><?= $this->lang->line('lb_store_name') ?></th>
                <th><?= $this->lang->line('lb_total') ?></th>
                <th><?= $this->lang->line('lb_total_paid') ?></th>
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
<?php $this->load->view("modal/modal_branch"); ?>
<!-- Plugins For This Page -->
<link rel="stylesheet" href="<?= base_url('aset/css/dataTables.bootstrap.min.css'.$this->main->js_css_version()); ?>">
<script src="<?= base_url('aset/js/jquery.dataTables.min.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/dataTables.bootstrap.min.js'.$this->main->js_css_version()); ?>"></script>
<!-- Plugins For This Page -->
<script src="<?= base_url('aset/js/page/pembayaran_sales.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/page/branch_modal.js'.$this->main->js_css_version()); ?>"></script>
<!--  -->
<link rel="stylesheet" href="<?= base_url("aset/datepicker/bootstrap-datepicker3.css".$this->main->js_css_version()); ?>"/>
<script type="text/javascript" src="<?= base_url("aset/datepicker/bootstrap-datepicker.min.js".$this->main->js_css_version()); ?>"></script>