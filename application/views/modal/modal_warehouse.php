<!-- Modal -->
<div id="modal-warehouse" class="modal modal-primary fade modal-fade-in-scale-up" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" style="padding-top: 20px;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
        <table class="table-warehouse table table-stiped datatable" width="100%">
          <thead>
            <tr>
              <?php if($this->session->app == "pipesys"): ?>
                <th><?= $this->lang->line('lb_code') ?></th>
              <?php endif; ?>
              <th style="min-width: 150px;"><?= $this->lang->line('lb_name') ?></th>
              <?php if($this->session->app == "salespro"): ?>
              <th><?= $this->lang->line('lb_address') ?></th>
              <?php endif; ?>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <?= $this->main->general_button('close'); ?>
          <button type="button" class="btn btn-outline btn-default margin-0 kotak btn-without" data-classnya="" onclick="without_warehouse(this)"><?= $this->lang->line('lb_warehouse_without') ?></button> 
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="modal-warehouse-address" class="modal modal-primary fade modal-fade-in-scale-up" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" style="padding-top: 20px;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
        <table class="table-warehouse-address table table-stiped datatable">
          <thead>
            <tr>
              <th><?= $this->lang->line('lb_address') ?></th>
              <th><?= $this->lang->line('lb_city') ?></th>
              <th><?= $this->lang->line('lb_province') ?></th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <?= $this->main->general_button('close'); ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="modal-warehouse-add" class="modal modal-primary fade modal-fade-in-scale-up" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" style="padding-top: 20px;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
        <form id="form-warehouse-add" autocomplete="off">
          <input type="hidden" name="WarehouseID">
          <div class="row">
            <div class="form-group col-sm-12">
              <label class="control-label">Warehouse Code</label>
              <input name="Code" id="Code" type="text" class="form-control" maxlength="10" placeholder="<?= $this->lang->line('lb_auto'); ?>">
              <span style="color:red">* <?= $this->lang->line('lb_warehouse_code_note'); ?></span>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label"><?= $this->lang->line('lb_warehouse_name'); ?></label>
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
          <?= $this->main->general_button('general_save','warehouse_save()') ?>
          <?= $this->main->general_button('close'); ?>
        </div>
      </div>
    </div>
  </div>
</div>