<!-- Modal -->
<div id="modal" class="modal modal-primary fade modal-fade-in-scale-up" data-backdrop="static" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
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
          <input type="hidden" name="crud">
          <input type="hidden" name="SalesID">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label class="control-label"><?= $this->lang->line('lb_sales_code'); ?></label>
                <input name="Code" id="Code" type="text" class="form-control txtCode" maxlength="20" placeholder="Auto">
                <span class="help-block"></span>
                <span style="color:red">* <?= $this->lang->line('lb_sales_note'); ?></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label"><?= $this->lang->line('lb_sales_name'); ?><span class="wajib"></span></label>
                <input name="Name" id="Name" type="text" class="form-control" maxlength="20">
                <span class="help-block"></span>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label"><?= $this->lang->line('lb_phone'); ?></label>
                <input name="Phone" id="Phone" type="text" class="form-control angka" maxlength="25">
                <span class="help-block"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label class="control-label"><?= $this->lang->line('lb_address'); ?></label>
                <input name="Address" id="Address" type="text" class="form-control txtRemark">
                <span class="help-block"></span>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label"><?= $this->lang->line('lb_city'); ?></label>
                <input name="City" id="City" type="text" class="form-control" maxlength="20">
                <span class="help-block"></span>
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group">
                <label class="control-label"><?= $this->lang->line('lb_remark'); ?></label>
                <textarea name="Remark" id="Remark" class="form-control txtRemark"></textarea>
                <span class="help-block"></span>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button id="btnSave" onclick="save()" type="button" class="btn btn-primary btnSave save">Save</button>
          <?= $this->main->button_action("action", array("edit","delete")); ?>
          <button type="button" class="btn btn-danger btn-default margin-0 kotak" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>