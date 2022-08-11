<!-- Modal -->
<div id="modal" class="modal modal-primary fade modal-fade-in-scale-up" data-backdrop="static" aria-hidden="true" >
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
          <input type="hidden" name="productid">
          <div class="row">
            <div class="form-group col-sm-12">
              <label class="control-label"><?= $this->lang->line('lb_product_code'); ?></label>
              <input name="product_code" id="product_code" type="text" class="form-control" placeholder="<?= $this->lang->line('lb_auto'); ?>">
              <span style="color:red"><?= $this->lang->line('lb_product_code_note'); ?></span>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
                <input type="file" name="photo" id="input-file-now" class="dropify">
            </div>
            <div class="form-group col-sm-6">
                <label class="control-label block"><?= $this->lang->line('lb_product_type'); ?></label>
                <div class="checkbox-custom checkbox-primary checkbox">
                  <input type="checkbox" id="inventory" name="inventory" value="inventory" checked="">
                  <label for="inventory"><?= $this->lang->line('lb_inventory'); ?></label>
                  <i class="fa fa-info-circle" aria-hidden="true" data-toggle="popover" data-html="true" data-content="<?= $this->lang->line('lb_product_inventory_note'); ?>"></i>
                </div>
                 <div class="checkbox-custom checkbox-primary checkbox">
                  <input type="checkbox" id="sales" name="sales" value="sell" checked="">
                  <label for="sales"><?= $this->lang->line('lb_selling1'); ?></label>
                  <i class="fa fa-info-circle" aria-hidden="true" data-toggle="popover" data-html="true" data-content="<?= $this->lang->line('lb_product_selling_note'); ?>"></i>
                </div>
                <div class="checkbox-custom checkbox-primary checkbox">
                  <input type="checkbox" id="serial" name="product_type" value="serial">
                  <label for="serial"><?= $this->lang->line('lb_serial'); ?></label>
                  <i class="fa fa-info-circle" aria-hidden="true" data-toggle="popover" data-html="true" data-content="<?= $this->lang->line('lb_product_serial_note'); ?>"></i>
                </div>
                <div class="checkbox-custom checkbox-primary checkbox serial_format_v">
                  <input type="checkbox" id="serial_auto" name="serial_auto" value="1">
                  <label for="serial_auto">Auto Serial Number</label>
                  <i class="fa fa-info-circle" aria-hidden="true" data-toggle="popover" data-html="true" data-content="This item have serial number." data-original-title="" title=""></i>
                </div>
                <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12 serial_format_v2">
              <label class="control-label"><?= $this->lang->line('lb_sn_format'); ?> <i class="icon wb-settings" style="cursor:pointer;" onclick="modal_notransaction('serial_format')" data-toggle="tooltip"  data-placement="left" data-html="true" title="<?= $this->lang->line('lb_sn_format_note'); ?>"></i></label>
              <input name="serial_format" id="serial_format" type="text" class="form-control serial_format" placeholder="<?= $this->lang->line('lb_auto'); ?>">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label"><?= $this->lang->line('lb_product_name'); ?></label>
              <input name="product_name" id="product_name" type="text" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label"><?= $this->lang->line('lb_category'); ?></label>
              <select name="product_category" placeholder="<?= $this->lang->line('lb_category'); ?>" id="product_category" class="form-control category_option"> 
              </select>
              <span class="help-block"></span>
            </div>
            
            <div class="form-group col-sm-6 content-hide">
              <label class="control-label"><?= $this->lang->line('lb_qty'); ?></label>
              <input name="qty" id="qty" type="text" class="form-control" disabled="">
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-sm-6 inventory">
              <label class="control-label"><?= $this->lang->line('lb_unit'); ?> <span class="wajib"></span> <i class="fa fa-info-circle" aria-hidden="true" data-toggle="popover" data-html="true" data-content="<?= $this->lang->line('lb_uom'); ?>"></i></label>
              <input name="unit" id="unit" type="text" placeholder="EX: PCS" class="form-control text-char autocomplete-unit" onkeyup="this.value = this.value.toUpperCase();">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_min_qty'); ?> <i class="fa fa-info-circle" aria-hidden="true" data-toggle="popover" data-html="true" data-content="<?= $this->lang->line('lb_product_qty_note'); ?>"></i></label>
              <input name="min_qty" id="min_qty" type="text" class="form-control duit" data-qty="active">
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-sm-6 purchase">
              <label class="control-label"><?= $this->lang->line('lb_purchase_price'); ?></label>
              <input name="purchase_price" id="purchase_price" type="text" class="form-control duit" disabled="">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6 sales">
              <label class="control-label"><?= $this->lang->line('lb_selling_price'); ?></label>
              <input name="selling_price" id="selling_price" type="text" class="form-control duit">
              <!-- <input name="selling_price" id="selling_price" type="text" class="form-control" pattern="^\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency"> -->
              <span class="help-block"></span>
              <span style="color:red"><?= $this->lang->line('lb_before_tax'); ?></span>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
       <div class="btn-group">
          <?= $this->main->general_button('general_save') ?>
          <?= $this->main->button_action("action", array("view","product_branch","edit","delete","customer_price")); ?>
          <?= $this->main->general_button('close') ?>
        </div>
      </div>
    </div>
  </div>
</div>