<!-- Modal -->
<div id="modal" class="modal modal-primary fade modal-fade-in-scale-up" data-backdrop="static" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
  <div class="modal-dialog width-80per">
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
          <input type="hidden" name="temp_invoiceno">
          <input type="hidden" name="temp_deliveryno">
          <input type="hidden" name="temp_sellno">
          <input type="hidden" name="temp_returnno">
          <div class="row">
            <div class="form-group col-sm-2">
              <div class="radio-custom radio-primary">
                <input type="radio" id="OrderType1" name="OrderType" value="1" checked>
                <label for="OrderType1"><?= $this->lang->line('lb_delivery1') ?></label>
              </div>
            </div>
            <div class="form-group col-sm-2">
              <div class="radio-custom radio-primary">
                <input type="radio" id="OrderType2" name="OrderType" value="2">
                <label for="OrderType2"><?= $this->lang->line('lb_selling') ?></label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_transaction_no') ?></label>
              <input name="InvoiceNo" id="InvoiceNo" type="text" class="form-control data-ID readonly" readonly>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_date') ?> <span class="wajib"></span></label>
              <input name="Date" id="Date" type="text" value="<?= date("Y-m-d") ?>" class="form-control date cursor readonly" readonly>
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_customer_name') ?> <span class="wajib"></span></label>
              <div class="input-group">
                <span class="input-group-addon pointer btn_select" onclick="vendor_modal('.autocomplete_vendor')">
                  <i class="fa-search" aria-hidden="true"></i>
                </span>
                <input name="CustomerName" id="CustomerName" type="text" class="form-control pointer readonly autocomplete_vendor-name" data-position="customer" data-page="invoice" readonly="readonly" placeholder="Select Customer" onclick="vendor_modal('.autocomplete_vendor')">
                <input name="CustomerID" id="CustomerID" type="text" class="form-control pointer readonly autocomplete_vendor content-hide" data-position="customer" data-page="invoice" readonly="readonly" placeholder="Select Customer" onclick="vendor_modal('.autocomplete_vendor')">
                <span></span>
              </div>
            </div>

            <div class="form-group col-sm-6 vinvoice">
              <label class="control-label"><?= $this->lang->line('lb_address') ?> <span class="wajib"></span></label>
              <div class="input-group">
                <span class="input-group-addon pointer" onclick="select_address_vendor('.vinvoice')">
                  <i class="fa-search" aria-hidden="true"></i>
                </span>
                <textarea name="invAddress" id="invAddress" type="text" placeholder="Select Customer Address" class="form-control address autocomplete_vendor-address" style="height: 35px"></textarea>
              </div>
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row vinvoice">
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_city') ?></label>
              <input name="invCity" id="invCity" type="text" class="form-control city autocomplete_vendor-city">
              <span class="help-block"></span>
            </div>

            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_province') ?></label>
              <input name="invProvince" id="invProvince" type="text" class="form-control province autocomplete_vendor-province">
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row vinvoice">
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_npwp') ?></label>
              <input name="NPWP" id="NPWP" type="text" class="form-control autocomplete_vendor-npwp">
              <span class="help-block"></span>
            </div>

            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_due_date') ?></label>
              <input name="DueDate" id="DueDate" type="text" class="form-control date cursor readonly" value="<?= date("Y-m-d") ?>">
              <span class="help-block"></span>
            </div>

            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_term') ?> (<?= $this->lang->line('lb_days') ?>)</label>
              <input name="Term" id="Term" type="text" class="form-control angkaint autocomplete_vendor-term">
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row">           
            <div class="form-group col-sm-12">
              <table class="table-add-product table table-striped input-table3" style="margin-top: 15px;margin-bottom: 0px;">
                <thead>
                  <tr>
                    <th></th>
                    <th style="width:30px"><input type="checkbox" name="check_all"></th>
                    <th class="th-code vdo"><?= $this->lang->line('lb_transaction_no') ?> </th>
                    <th><?= $this->lang->line('lb_transaction') ?></th>
                    <th class="th-code"><?= $this->lang->line('lb_sellingno') ?></th>
                    <th><?= $this->lang->line('lb_date') ?></th>
                    <th><?= $this->lang->line('lb_sub_total') ?></th>
                    <th><?= $this->lang->line('lb_discount') ?></th>
                    <th><?= $this->lang->line('lb_tax') ?></th>
                    <th><?= $this->lang->line('lb_delivery_cost') ?></th>
                    <th><?= $this->lang->line('lb_total') ?></th>
                    <th><?= $this->lang->line('lb_remark') ?></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="row">
              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_remarks') ?></label>
                <textarea name="Remark" id="Remark" type="text" class="form-control" style="height: 100px"></textarea>
                <span class="help-block"></span>
              </div>

              <div class="form-attach"></div>

            </div>
          </div>
          <div class="col-sm-6">
            <div class="row">
              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_sub_total') ?></label>
                <input type="text" name="SubTotal" id="SubTotal" class="form-control duit disabled">
                <span class="help-block"></span>
              </div>

              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_discount') ?></label>
                <input type="text" name="Discount" id="Discount" class="form-control duit disabled">
                <span class="help-block"></span>
              </div>

              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_tax') ?> </label>
                <input type="text" name="PPN" id="PPN" class="form-control duit disabled">
                <span class="help-block"></span>
              </div>

              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_delivery_cost') ?></label>
                <input type="text" name="DeliveryCost" id="DeliveryCost" class="form-control duit disabled">
                <span class="help-block"></span>
              </div>

              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_grand_total') ?></label>
                <input type="text" name="GrandTotal" id="GrandTotal" class="form-control duit disabled">
                <span class="help-block"></span>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">    
          <?= $this->main->general_button('general_save') ?>
          <?= $this->main->general_button('close') ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="modal-print" class="modal modal-primary fade modal-fade-in-scale-up" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
        <div class="div-loader">
          <div class="loader"></div>
       </div>
       <div class="content-print table-responsive" id="view-print" style="min-height: 500px"></div>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button type="button" class="btn btn-primary save btn-save2"><?= $this->lang->line('btn_save') ?></button>
          <?= $this->main->button_action("action", array("cancel","edit","next")); ?>
          <?= $this->main->button_action("print"); ?>
          <?= $this->main->general_button('close') ?>
          <button type="button" class="btn btn-danger btn-default margin-0 kotak btn-back"><?= $this->lang->line('btn_cancel') ?></button>
        </div>
      </div>
    </div>
  </div>
</div>