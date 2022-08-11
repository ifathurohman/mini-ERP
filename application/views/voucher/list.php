<div class="page page-data" data-hakakses="<?= $this->session->hak_akses; ?>" data-page_name="<?= $title; ?>" data-modul="<?= $modul; ?>" data-url_modul="<?= $url_modul; ?>" data-search="<?= $this->input->get("s"); ?>" data-currentdate="<?= date("Y-m-d"); ?>">
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
          <div class="col-sm-12">
            <form id="form-filter" autocomplete="off" method="post">
              <div class="row">
                <div class="form-group col-sm-3">
                  <div>
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
                <div class="form-group col-sm-3">
                  <div class="content-hide">
                    <label>Application</label>
                    <select name="fApp" class="form-control">
                      <option value="none">Please Choose Application</option>
                      <option value="pipesys">Pipesys</option>
                    </select>
                    <span class="help-block"></span>
                  </div>
                  <div>
                    <label><?= $this->lang->line('lb_package') ?></label>
                    <select name="fPackage" class="form-control">
                      <option value="none"><?= $this->lang->line('v_select_voucher_package') ?></option>
                      <option value="3">3 <?= $this->lang->line('month') ?></option>
                      <option value="6">6 <?= $this->lang->line('month') ?></option>
                      <option value="12">1 <?= $this->lang->line('year') ?></option>
                    </select>
                    <span class="help-block"></span>

                  </div>
                </div>
                <div class="form-group col-sm-3 v_group">
                  <label><?= $this->lang->line('lb_transaction_status') ?></label>
                  <select name="fStatus" class="form-control">
                    <option value="none"><?= $this->lang->line('lb_choose_t_status') ?></option>
                    <option value="proccess"><?= $this->lang->line('lb_process') ?></option>
                    <option value="finish"><?= $this->lang->line('lb_finish') ?></option>
                    <option value="cancel"><?= $this->lang->line('btn_cancel') ?></option>
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
          </div>
          <div class="col-lg-12">
            <div class="btn-group">
              <?= $tambah; ?>
              <?php if($modul != "transaction"): $this->main->general_button('general_blue',site_url('use-voucher'), 'Voucher Use'); endif; ?>
            </div>
            <?php if($modul == "voucher"): ?>
            <span class="red_txt "><?= $this->lang->line('note_voucher2') ?></span>
            <?php endif ; ?>
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
                <th><?= $this->lang->line('lb_date') ?></th>
                <th><?= $this->lang->line("lb_transaction_no") ?></th>
                <th><?= $this->lang->line('lb_package') ?></th>
                <th><?= $this->lang->line('lb_additional_qty') ?></th>
                <th><?= $this->lang->line('lb_module_qty') ?></th>
                <th><?= $this->lang->line('lb_amount') ?></th>
                <th><?= $this->lang->line('lb_bank') ?></th>
                <th><?= $this->lang->line('lb_status_remark') ?></th>
                <th><?= $this->lang->line('lb_company') ?></th>
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
<script src="<?= base_url('aset/js/page/voucher.js'.$this->main->js_css_version()); ?>"></script>

<link rel="stylesheet" href="<?= base_url("aset/datepicker/bootstrap-datepicker3.css"); ?>"/>
<script type="text/javascript" src="<?= base_url("aset/datepicker/bootstrap-datepicker.min.js"); ?>"></script>