<div class="page page-data" data-modul="<?= $modul ?>" data-url_modul="<?= $url_modul ?>">
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
        <div class="div-list">
          <div class="row row-lg">
            <div class="col-lg-12">
              <form id="form-filter" autocomplete="off" method="post">
              <div class="row">
                <div class="form-group col-sm-3">
                  <label class="control-label">Status</label>
                  <select name="fStatus" class="form-control">
                    <option value="none"><?= $this->lang->line('lb_all') ?></option>
                    <option value="1" selected><?= $this->lang->line('btn_active') ?></option>
                    <option value="0"><?= $this->lang->line('btn_nonactive') ?></option>
                  </select>
                  <span class="help-block"></span>
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
              <div class="btn-group">
                <?= $tambah; ?>              
              </div>
                <div class="btn-group pull-right">
                  <?= $this->main->general_button('search'); ?>
                  <?= $this->main->general_button('reload'); ?>
                </div>
             <!--  <div class="btn-group pull-right">
                <button class="btn btn-outline btn-default" onclick="reload_table()" type="button"><i class="icon wb-reload"></i>Reload</button>
              </div> -->
            </div>
          </div>
          <br>
          <table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
            <thead>
               <tr>
                  <th><?= $this->lang->line('lb_no') ?></th>
                  <th><?= $this->lang->line('lb_name') ?></th>
                  <th><?= $this->lang->line('lb_type') ?></th>
                  <th><?= $this->lang->line('lb_image') ?></th>
                  <th>Status</th>
                  <th><?= $this->lang->line('lb_remark') ?></th>
               </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <div class="div-form">
          <?php $this->load->view('template/form'); ?>
        </div>
      </div>
    </div>
    <!-- End Panel Basic -->
  </div>
</div>
<?php $this->load->view($modal); ?>
<link rel="stylesheet" href="<?= base_url('aset/css/dataTables.bootstrap.min.css'.$this->main->js_css_version()); ?>">
<script src="<?= base_url('aset/js/jquery.dataTables.min.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/dataTables.bootstrap.min.js'.$this->main->js_css_version()); ?>"></script>

<link rel="stylesheet" href="<?= base_url('aset/css/dropify.min.css'); ?>">
<script src="<?= base_url('aset/js/dropify.min.js'); ?>"></script>

<script src="<?= base_url('aset/plugin/') ?>tinymce/tinymce.min.js"></script>


<script src="<?= base_url('aset/js/page/template.js'.$this->main->js_css_version()); ?>"></script>
