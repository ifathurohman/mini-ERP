<div class="page page-data" data-hakakses="<?= $this->session->hak_akses; ?>" data-page_name="<?= $title; ?>" data-modul="<?= $modul; ?>" data-url_modul="<?= $url_modul; ?>" >
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
                <th><?= $this->lang->line('lb_company_name') ?></th>
                <th><?= $this->lang->line('lb_email') ?></th>
                <th><?= $this->lang->line('lb_phone') ?></th>
                <th>Super Admin</th>
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
<!-- Plugins For This Page -->
<link rel="stylesheet" href="<?= base_url('aset/css/dataTables.bootstrap.min.css'); ?>">
<script src="<?= base_url('aset/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('aset/js/dataTables.bootstrap.min.js'); ?>"></script>

<!-- slime select -->
<link rel="stylesheet" href="<?= base_url('aset/plugin/slimselect/slimselect.min.css') ?>">
<script src="<?= base_url('aset/plugin/slimselect/slimselect.min.js') ?>"></script>
<!-- Plugins For This Page -->
<script src="<?= base_url('aset/js/page/admin.js'); ?>"></script>
