<div class="page page-data" data-hakakses="<?= $this->session->hak_akses; ?>" data-page_name="<?= $title; ?>" data-modul="<?= $modul; ?>" data-url_modul="<?= $url_modul; ?>" data-title="<?= $title ?>">
  <div class="page-header">
  </div>
  <div class="page-content">
    <!-- Panel Basic -->
    <div class="panel panel-bordered">
      <header class="panel-heading">
        <div class="panel-actions"></div>
        <h3 class="panel-title"><?= $title ?></h3>
      </header>
      <div class="panel-body">
        <?php
        if($this->session->flashdata('message')): ?>
        <div class="alert dark alert-warning alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
          <?= $this->session->flashdata('message'); ?>
        </div>
        <?php endif; ?>
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
                <th><?= $this->lang->line('lb_no'); ?></th>
                <th><?= $this->lang->line('lb_store_name'); ?></th>
                <th><?= $this->lang->line('lb_address'); ?></th>
                <th><?= $this->lang->line('lb_city'); ?></th>
                <th><?= $this->lang->line('lb_province'); ?></th>
                <th><?= $this->lang->line('lb_country'); ?></th>
                <th><?= $this->lang->line('lb_device'); ?></th>
                <th><?= $this->lang->line('lb_expire'); ?></th>
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
<link rel="stylesheet" href="<?= base_url('aset/css/dataTables.bootstrap.min.css'.$this->main->js_css_version()); ?>">
<script src="<?= base_url('aset/js/jquery.dataTables.min.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/dataTables.bootstrap.min.js'.$this->main->js_css_version()); ?>"></script>
<!-- Plugins For This Page -->
<script src="https://maps.googleapis.com/maps/api/js?key=<?= $this->config->item("gmap_api"); ?>"></script>
<script src="<?= base_url('aset/js/page/branch.js'.$this->main->js_css_version()); ?>"></script>
