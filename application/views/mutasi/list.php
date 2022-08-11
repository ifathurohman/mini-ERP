<div class="page page-data ck-module-expire" data-modul="<?= $modul ?>" data-url_modul="<?= $url_modul ?>" data-module="inventory" data-hakakses="<?= $this->session->hak_akses; ?>" data-title="<?= $title ?>">
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
                <div class="form-group col-sm-3">
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
                <div class="form-group col-sm-3">
                  <label><?= $this->lang->line('lb_from') ?></label>
                  <select name="f_fromBranch" id="f_fromBranch" class="form-control select2">
                    <option value="all"><?= $this->lang->line('lb_store_select') ?></option>
                    <?php $list = $this->main->sp_list_branch(); 
                    foreach($list as $a): 
                    echo '<option value="'.$a->ID.'">'.$a->Name.'</option>'; 
                    endforeach; ?>
                  </select>
                </div>
                <div class="form-group col-sm-3">
                  <label><?= $this->lang->line('lb_to') ?></label>
                  <select name="f_toBranch" id="f_toBranch" class="form-control select2">
                    <option value="all"><?= $this->lang->line('lb_store_select') ?></option>
                    <?php
                    foreach($list as $a): 
                    echo '<option value="'.$a->ID.'">'.$a->Name.'</option>'; 
                    endforeach; ?>
                  </select>
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
          </div>
        </div>
        <br>
        <table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
          <thead>
             <tr>
                <th><?= $this->lang->line('lb_no') ?></th>
                <th><?= $this->lang->line('lb_transaction_no') ?></th>
                <th><?= $this->lang->line('lb_date') ?></th>
                <th><?= $this->lang->line('lb_from') ?></th>
                <th><?= $this->lang->line('lb_to') ?></th>
                <th><?= $this->lang->line('lb_type') ?></th>
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
<?php $this->load->view("modal/modal_product"); ?>
<?php $this->load->view("modal/modal_branch"); ?>
<?php $this->load->view("modal/modal_add_serial"); ?>



<!-- Plugins For This Page -->
<link rel="stylesheet" href="<?= base_url('aset/css/dataTables.bootstrap.min.css'); ?>">
<script src="<?= base_url('aset/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('aset/js/dataTables.bootstrap.min.js'); ?>"></script>
<!-- Plugins For This Page -->
<script src="<?= base_url('aset/js/page/mutasi.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/page/product_modal.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/page/branch_modal.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/page/autocomplete_branch.js'.$this->main->js_css_version()); ?>"></script>
<link rel="stylesheet" href="<?= base_url("aset/datepicker/bootstrap-datepicker3.css"); ?>"/>
<link rel="stylesheet" href="<?= base_url('aset/css/select2.css'.$this->main->js_css_version()); ?>">
<script src="<?= base_url('aset/plugin/select2/select2.min.js'.$this->main->js_css_version()) ?>"></script>

<script type="text/javascript" src="<?= base_url("aset/datepicker/bootstrap-datepicker.min.js"); ?>"></script>
<script src="<?= base_url('aset/js/page/branch_modal.js'.$this->main->js_css_version()); ?>"></script>
