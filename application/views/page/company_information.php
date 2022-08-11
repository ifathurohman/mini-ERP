<style type="text/css">
  .dropify-wrapper{
    margin: 4px 0px;
  }
</style>
<div class="page page-data" data-hakakses="<?= $this->session->hak_akses; ?>">
  <div class="page-header">
  </div>
  <div class="page-content">
    <div class="panel panel-bordered">
      <header class="panel-heading">
        <div class="panel-actions"></div>
        <h3 class="panel-title"><?= $title; ?></h3>
      </header>
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-12" style="line-height: 15px">
            <form id="form-company" autocomplete="off" method="post">
              <div class="row col-sm-6">
                <div class="form-group col-sm-12">
                  <label class="control-label"><?= $this->lang->line('lb_company_logo') ?></label>
                  <input type="file" name="photo" id="input-file-now" class="dropify"/>
                  <!--  onchange="$('form').submit();" -->
                  <span style="padding-top: 5px"><?= $this->lang->line('note_img') ?></span>
                </div>
                <div class="form-group col-sm-12" style="margin-top: 5px;">
                  <label class="control-label"><?= $this->lang->line('lb_company_name') ?> <span class="wajib"></span></label>
                  <input name="nama" id="nama" type="text" class="form-control">
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label"><?= $this->lang->line('lb_address') ?></label>
                  <textarea name="address" id="address" class="form-control txtRemark"></textarea>
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label"><?= $this->lang->line('lb_city') ?></label>
                  <input name="city" id="city" type="text" class="form-control">
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label"><?= $this->lang->line('lb_pos_code') ?></label>
                  <input name="postal" id="postal" type="text" class="form-control angka">
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label"><?= $this->lang->line('lb_phone') ?></label>
                  <input name="phone" id="phone" type="text" class="form-control angka">
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label"><?= $this->lang->line('lb_fax') ?></label>
                  <input name="fax" id="fax" type="text" class="form-control angka">
                  <span class="help-block"></span>
                </div>
                  <div class="form-group col-sm-12">
                  <label class="control-label"><?= $this->lang->line('lb_npwp') ?></label>
                  <input name="npwp" id="npwp" type="text" class="form-control">
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label"><?= $this->lang->line('lb_province') ?></label>
                  <input name="province" id="province" type="text" class="form-control">
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label"><?= $this->lang->line('lb_country') ?></label>
                  <input name="country" id="country" type="text" class="form-control">
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="col-sm-6" style="line-height: 15px">
                <div class="row div-contact"></div>
                <div class="row">
                  <div class="form-group col-sm-12" style="padding-bottom: 25px;">
                    <a href="javascript:void(0)" onclick="add_contact()" class="pull-right"><?= $this->lang->line('lb_add_bank') ?></a>
                  </div>
                </div>
                  <button class="btn btn-primary pull-right" onclick="company_save('company')" id="btnSave"><?= $this->lang->line('btn_save_company') ?></button>
              </div>
            </form>
          </div>          
        </div>
      </div>
    </div>
  </div>
</div>
<link rel="stylesheet" href="<?= base_url('aset/css/dropify.min.css'); ?>">
<script src="<?= base_url('aset/js/dropify.min.js'); ?>"></script>