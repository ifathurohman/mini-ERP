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
                    <input type="text" name="fStartDate" class="form-control date pointer" placeholder="" id="fStartDate" value="<?= date("Y-m-01"); ?>">
                    <span class="input-group-addon pointer" title="<?= $this->lang->line('lb_remove') ?> <?= $this->lang->line('lb_startdate') ?>" onclick="remove_value('fStartDate')">
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
                    <input type="text" name="fEndDate" class="form-control date pointer" placeholder="" id="fEndDate" value="<?= date("Y-m-d"); ?>">
                    <span class="input-group-addon pointer" title="<?= $this->lang->line('lb_remove') ?> <?= $this->lang->line('lb_enddate') ?>" onclick="remove_value('fEndDate')">
                      <i class="fa-times" aria-hidden="true"></i>
                    </span>
                  </div>
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
              <button type="button" class="btn btn-blue" onclick="use_voucher()"><?= $title ?></button>     
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
                <th><?= $this->lang->line('lb_voucher_code') ?></th>
                <th><?= $this->lang->line('lb_used_by_name') ?></th>
                <th><?= $this->lang->line('lb_used_by_com') ?></th>
                <th><?= $this->lang->line('lb_package') ?></th>
                <th><?= $this->lang->line('module') ?></th>
                <th><?= $this->lang->line('lb_use_date') ?></th>
                <th><?= $this->lang->line('lb_expire_date') ?></th>
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
<?php $this->load->view("modal/modal_voucher"); ?>

<!-- Plugins For This Page -->
<link rel="stylesheet" href="<?= base_url('aset/css/dataTables.bootstrap.min.css'); ?>">
<script src="<?= base_url('aset/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('aset/js/dataTables.bootstrap.min.js'); ?>"></script>
<link rel="stylesheet" href="<?= base_url("aset/datepicker/bootstrap-datepicker3.css"); ?>"/>
<script type="text/javascript" src="<?= base_url("aset/datepicker/bootstrap-datepicker.min.js"); ?>"></script>
<script src="<?= base_url('aset/js/page/voucher_use.js'); ?>"></script>
<script src="<?= base_url('aset/js/page/voucher_modal.js'); ?>"></script>