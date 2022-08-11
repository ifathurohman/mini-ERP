<!-- Modal -->
<div id="modal-sales" class="modal modal-primary fade modal-fade-in-scale-up" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" style="padding-top: 20px;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
        <table class="table-sales table table-stiped datatable" width="100%">
          <thead>
            <tr>
              <th><?= $this->lang->line('lb_code') ?></th>
              <th><?= $this->lang->line('lb_name') ?></th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <?= $this->main->general_button('close'); ?>
          <button type="button" class="btn btn-outline btn-default margin-0 kotak btn-without" data-classnya="" onclick="without_sales(this)"><?= $this->lang->line('lb_sales_without') ?></button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="modal-sales-add" class="modal modal-primary fade modal-fade-in-scale-up" data-backdrop="static" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
        <form id="form-sales-add" autocomplete="off">
          <input type="hidden" name="classnya">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label class="control-label"><?= $this->lang->line('lb_sales_code') ?></label>
                <input name="Code" id="Code" type="text" class="form-control txtCode" maxlength="20" placeholder="Auto">
                <span class="help-block"></span>
                <span style="color:red">* <?= $this->lang->line('lb_sales_note') ?></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label"><?= $this->lang->line('lb_name') ?> <span class="wajib"></span></label>
                <input name="Name" id="Name" type="text" class="form-control" maxlength="20">
                <span class="help-block"></span>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label"><?= $this->lang->line('lb_phone') ?></label>
                <input name="Phone" id="Phone" type="text" class="form-control angka" maxlength="25">
                <span class="help-block"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label class="control-label"><?= $this->lang->line('lb_address') ?></label>
                <input name="Address" id="Address" type="text" class="form-control txtRemark">
                <span class="help-block"></span>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label"><?= $this->lang->line('lb_city') ?></label>
                <input name="City" id="City" type="text" class="form-control" maxlength="20">
                <span class="help-block"></span>
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group">
                <label class="control-label"><?= $this->lang->line('lb_remark') ?></label>
                <textarea name="Remark" id="Remark" class="form-control txtRemark"></textarea>
                <span class="help-block"></span>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <?= $this->main->general_button('general_save','sales_save()'); ?>
          <?= $this->main->general_button('close'); ?>
        </div>
      </div>
    </div>
  </div>
</div>