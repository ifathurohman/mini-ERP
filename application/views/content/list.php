<div class="page page-data" data-title="<?= $title ?>">
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
                <th><?= $this->lang->line('lb_no'); ?></th>
                <th><?= $this->lang->line('lb_date'); ?></th>
                <th><?= $this->lang->line('lb_author'); ?></th>
                <th><?= $this->lang->line('lb_name'); ?></th>
                <th>Status</th>
                <th><?= $this->lang->line('btn_action'); ?></th>
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
<script src="<?= base_url('aset/js/page/content.js'.$this->main->js_css_version()); ?>"></script>

<script src="<?= base_url('aset/plugin/bootstrap-tokenfield/bootstrap-tokenfield.js') ?>"></script>
<link href="<?= base_url('aset/plugin/bootstrap-tokenfield/bootstrap-tokenfield.css') ?>" rel="stylesheet"></link>

<link rel="stylesheet" href="<?= base_url("aset/datepicker/bootstrap-datepicker3.css".$this->main->js_css_version()); ?>"/>
<script type="text/javascript" src="<?= base_url("aset/datepicker/bootstrap-datepicker.min.js".$this->main->js_css_version()); ?>"></script>
<!-- include summernote css/js -->
<link href="<?= base_url('aset/plugin/summernote/summernote.css') ?>" rel="stylesheet">
<script src="<?= base_url('aset/plugin/summernote/summernote.js') ?>"></script>
<!-- dropify -->
<link rel="stylesheet" href="<?= base_url('aset/css/dropify.min.css'); ?>">
<script src="<?= base_url('aset/js/dropify.min.js'); ?>"></script>


