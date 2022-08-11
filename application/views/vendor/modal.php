<!-- Modal -->
<div id="modal" class="modal modal-primary fade modal-fade-in-scale-up" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
        <form id="form" autocomplete="off">
          <input name="vendorid" id="vendorid" type="hidden" class="form-control">
          <div class="row">
            <div class="form-group col-sm-12">
              <label class="control-label"><?= $this->lang->line('lb_partner_code'); ?></label>
              <i class="fa fa-info-circle" aria-hidden="true" data-toggle="popover" data-html="true" data-content="The stock will be recorded and monitored by system."></i>
              <input name="code" id="code" type="text" class="form-control" placeholder="Auto">
              <span class="help-block"></span>
              <span style="color:red"><?= $this->lang->line('lb_partner_note'); ?></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label"><?= $this->lang->line('lb_name'); ?> <span class="wajib"></span></label>
              <input name="name" id="name" type="text" class="form-control">
              <span class="help-block"></span>
            </div>
            <?php if($modul == "partner"): ?>
            <div class="form-group col-sm-6">
                <label class="control-label block"><?= $this->lang->line('lb_partner_type'); ?></label>
                <div class="radio-custom radio-primary radio-inline">
                  <input type="radio" id="customer" name="type" value="customer" checked="">
                  <label for="customer"><?= $this->lang->line('lb_customer'); ?></label>
                </div>
                <div class="radio-custom radio-primary radio-inline">
                  <input type="radio" id="vendor" name="type" value="vendor">
                  <label for="vendor"><?= $this->lang->line('lb_vendor'); ?></label>
                </div>
            </div>
            <?php endif; ?>
            <div class="address_v">              
            </div>
            <div class="form-group col-sm-12">
                <a href="#" id="add_address"><?= $this->lang->line('lb_add_new_addr'); ?></a>
            </div>
            <div class="contact_v">
            </div>
            <div class="form-group col-sm-12">
              <a href="#" id="add_contact"><?= $this->lang->line('lb_add_new_contact'); ?></a>
            </div>
            <?php if($modul == "partner"): ?>
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_npwp'); ?></label>
              <input name="npwp" id="npwp" type="text" class="form-control angka">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_top'); ?></label>
              <input name="top" id="top" type="text" class="form-control angka">
              <span class="help-block"></span>
            </div>
            <?php endif; ?>
            <div class="form-group col-sm-12">
              <label class="control-label"><?= $this->lang->line('lb_group_name'); ?></label>
              <input name="GroupName" id="GroupName" type="text" onkeyup="keyup_vendor_price('0',this,'vendor')" class="uppercase autocomplete_vendor_price form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label"><?= $this->lang->line('lb_remarks'); ?></label>
              <input name="remark" id="remark" type="text" class="form-control">
              <span class="help-block"></span>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button id="btnSave" onclick="save()" type="button" class="btn btn-primary btnSave save"><?= $this->lang->line('btn_save'); ?></button>
          <?= $this->main->button_action("action", array("edit","delete")); ?>
          <button type="button" class="btn btn-danger btn-default margin-0 kotak" data-dismiss="modal"><?= $this->lang->line('btn_close'); ?></button>
        </div>
      </div>
    </div>
  </div>
</div>