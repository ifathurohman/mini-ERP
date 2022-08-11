<div class="page page-data" data-modul="transaction_route">
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
                <button class="btn btn-outline btn-default" onclick="reload_table()" type="button"><i class="icon wb-reload"></i>Reload</button>
            </div>
          </div>
        </div>
        <br>
        <table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
          <thead>
             <tr>
                <th>No</th>
                <th>Transaction No</th>
                <th>Date</th>
                <th>Name</th>
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
<?php $this->load->view("modal/modal_product"); ?>
<?php $this->load->view("modal/modal_vendor"); ?>

<!-- Plugins For This Page -->
<link rel="stylesheet" href="<?= base_url('aset/css/dataTables.bootstrap.min.css'); ?>">
<script src="<?= base_url('aset/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('aset/js/dataTables.bootstrap.min.js'); ?>"></script>
<script src="<?= base_url('aset/js/page/sp_transaction_route.js'); ?>"></script>
<script src="<?= base_url('aset/js/page/vendor_modal.js'); ?>"></script>
<script src="<?= base_url('aset/js/page/autocomplete_vendor.js'); ?>"></script>

<link rel="stylesheet" href="<?= base_url("aset/datepicker/bootstrap-datepicker3.css"); ?>"/>
<script type="text/javascript" src="<?= base_url("aset/datepicker/bootstrap-datepicker.min.js"); ?>"></script>


