<div class="page page-data" data-modul="<?= $modul ?>" data-url_modul="<?= $url_modul ?>" data-title="<?= $title ?>">
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
                  <label class="control-label">Active</label>
                  <select name="fActive" class="form-control">
                    <option value="none">All</option>
                    <option value="1" selected>Active</option>
                    <option value="0">Nonactive</option>
                  </select>
                  <span class="help-block"></span>
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
            <div class="btn-group">
              <?= $tambah; ?>              
            </div>
            <div class="btn-group pull-right">
              <?= $this->main->general_button('import'); ?>
              <?= $this->main->general_button('export',site_url('master_coa/export')); ?>
              <?= $this->main->general_button('general',site_url('conversion-balance'),'Conversion Balance'); ?>
              <?= $this->main->general_button('search'); ?>
              <?= $this->main->general_button('reload'); ?>
            </div>
          </div>
        </div>
        <br>
        <table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
          <thead>
             <tr>
                <th>No</th>
                <th>Code</th>
                <th>Name</th>
                <th>Level</th>
                <th>Parent COA</th>
                <th>Remark</th>
                <!-- <th>Action</th> -->
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
<?php $this->load->view('coa/modal_import'); ?>
<?php $this->load->view("modal/modal_import_view"); ?>

<!-- Plugins For This Page -->
<link rel="stylesheet" href="<?= base_url('aset/css/dataTables.bootstrap.min.css'.$this->main->js_css_version()); ?>">
<script src="<?= base_url('aset/js/jquery.dataTables.min.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/dataTables.bootstrap.min.js'.$this->main->js_css_version()); ?>"></script>
<link rel="stylesheet" href="<?= base_url('aset/css/dropify.min.css'.$this->main->js_css_version()); ?>">
<script src="<?= base_url('aset/js/dropify.min.js'.$this->main->js_css_version()); ?>"></script>

<script src="<?= base_url('aset/js/page/coa.js'.$this->main->js_css_version()); ?>"></script>


