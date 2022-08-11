<div class="page page-data" data-hakakses="<?= $this->session->hak_akses; ?>" data-title="<?= $title ?>">
  <div class="page-header">
  </div>
  <div class="page-content">
    <!-- Panel Basic -->
    <div class="panel">
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
              <?= $this->main->general_button('reload') ?>
            </div>
            <?php $this->load->view($modal); ?>
          </div>
        </div>
        <br>
        <table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
          <thead>
             <tr>
                <th>No</th>
                <th>Index</th>
                <th>Menu Name</th>
                <th>Url</th>
                <th>Method</th>
                <th>Category</th>
                <th>Modul</th>
                <th>Modul Page</th>
                <th style="width:125px;">Action</th>
             </tr>
          </thead>
          <tbody>
          </tbody>
          <tfoot>
             <tr>
                <th>No</th>
                <th>Index</th>
                <th>Menu Name</th>
                <th>Url</th>
                <th>Method</th>
                <th>Category</th>
                <th>Action</th>
                <th>Modul</th>
                <th>Modul Page</th>
             </tr>
          </tfoot>
        </table>
      </div>
    </div>
    <!-- End Panel Basic -->
  </div>
</div>
<!-- Plugins For This Page -->
<link rel="stylesheet" href="<?= base_url('aset/css/dataTables.bootstrap.min.css'.$this->main->js_css_version()); ?>">
<script src="<?= base_url('aset/js/jquery.dataTables.min.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/dataTables.bootstrap.min.js'.$this->main->js_css_version()); ?>"></script>

<link rel="stylesheet" href="<?= base_url('aset/plugin/slimselect/slimselect.min.css') ?>">
<script src="<?= base_url('aset/plugin/slimselect/slimselect.min.js') ?>"></script>

<!-- Plugins For This Page -->
<script src="<?= base_url('aset/js/page/menu.js'.$this->main->js_css_version()); ?>"></script>
