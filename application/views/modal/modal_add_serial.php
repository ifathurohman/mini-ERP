<!-- Modal -->
<div id="modal-add-serial" class="modal modal-primary fade modal-fade-in-scale-up" data-backdrop="static" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" style="margin-top: 20px;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
        <form id="form-serial" autocomplete="off">
          <input type="hidden" name="page">
          <input type="hidden" name="header_code" id="header_code">
          <input type="hidden" name="detail_code">
          <input type="hidden" name="receipt_det">
          <input type="hidden" name="productid">
          <input type="hidden" name="product_type">
          <div class="row">
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_product_name') ?></label>
              <input name="product_name" id="product_name" type="text" class="form-control readonly">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-3">
              <label class="control-label"><?= $this->lang->line('lb_product_type') ?></label>
              <input name="product_type" id="product_type" type="text" class="form-control readonly">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-3">
              <label class="control-label"><?= $this->lang->line('lb_qty') ?></label>
              <input name="serial_qty" id="serial_qty" type="text" data-qty="active" class="form-control readonly duit">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <table class="table-add-serial table table-bordered table-striped table-td-padding-0 input-table" style="margin-top: 15px;margin-bottom: 0px;">
                <thead>
                  <tr>
                    <th colspan="2"><?= $this->lang->line('lb_no') ?></th>
                    <th>Serial Number</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </form>
        <div class="div-loader">
          <div class="loader"></div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <!-- <button id="btnSave" onclick="save_serial()" type="button" class="btn btn-primary save btn-add-serial">Save</button> -->
          <button id="btnSave" onclick="save_serial_number()" type="button" class="btn btn-primary save btn-add-serial"><?= $this->lang->line('btn_save') ?></button>
          <?= $this->main->general_button('close'); ?>
        </div>
      </div>
    </div>
  </div>
</div>