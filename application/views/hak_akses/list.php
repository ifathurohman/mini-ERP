<div class="page page-data" data-hakakses="<?= $this->session->hak_akses; ?>" data-title="<?= $title ?>">
  <div class="page-header">
  </div>
  <div class="page-content">
    <div class="panel">
      <header class="panel-heading">
        <div class="panel-actions"></div>
        <!-- <h3 class="panel-title" style="text-transform: uppercase;"><?= $title; ?> <?= $this->session->app; ?></h3> -->
        <h3 class="panel-title"><?= $title; ?></h3>
      </header>
      <div class="panel-body">
        <div class="row row-lg">
          <div class="col-lg-12">
            <div class="btn-group">
              <?= $tambah; ?>
            </div>
            <div class="btn-group pull-right">
              <?= $this->main->general_button('reload') ?>
            </div>
            <?php $this->load->view($modal); ?>
          </div>
        </div>
        <br>
        <table id="table" class="table table-hover dataTable table-striped width-full" cellspacing="0" width="100%">
          <thead>
             <tr>
                <th>No</th>
                <th>User Privileges Name</th>
                <th style="width:125px;">Action</th>
             </tr>
          </thead>
          <tbody>
          </tbody>
       </table>
      </div>
    </div>
  </div>
</div>
<link rel="stylesheet" href="<?= base_url('aset/css/dataTables.bootstrap.min.css'.$this->main->js_css_version()); ?>">
<script src="<?= base_url('aset/js/jquery.dataTables.min.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/dataTables.bootstrap.min.js'.$this->main->js_css_version()); ?>"></script>
<!-- Plugins For This Page -->
<script src="<?= base_url('aset/js/page/hakakses.js'.$this->main->js_css_version()); ?>"></script>
