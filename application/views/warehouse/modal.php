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
          <input type="hidden" name="WarehouseID">
          <div class="row">
            <div class="form-group col-sm-12">
              <label class="control-label">Warehouse Code</label>
              <input name="Code" id="Code" type="text" class="form-control" maxlength="10" placeholder="<?= $this->lang->line('lb_auto'); ?>">
              <span style="color:red"><?= $this->lang->line('lb_warehouse_code_note'); ?></span>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label"><?= $this->lang->line('lb_warehouse_name'); ?> <span class="wajib"></span></label>
              <input name="Name" id="Name" type="text" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label"><?= $this->lang->line('lb_address'); ?></label>
              <input name="Address" id="Address" type="txtRemark" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label"><?= $this->lang->line('lb_description'); ?></label>
              <input name="Description" id="Description" type="txtRemark" class="form-control">
              <span class="help-block"></span>
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