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
          <input type="hidden" name="temp_sellno">
          <input type="hidden" name="temp_selldet">
          <input type="hidden" name="temp_deliveryno">
          <input type="hidden" name="temp_deliverydet">
          <div class="row">
            <div class="form-group col-sm-2">
              <div class="radio-custom radio-primary">
                <input type="radio" id="ckOrder2" name="ckOrder" value="3" checked>
                <label for="ckOrder2"><?= $this->lang->line('lb_selling') ?></label>
              </div>
            </div>
            <div class="form-group col-sm-2">
              <div class="radio-custom radio-primary">
                <input type="radio" id="ckOrder3" name="ckOrder" value="4">
                <label for="ckOrder3"><?= $this->lang->line('lb_delivery1') ?></label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-sm-4">
              <label class="control-label"><?= $this->lang->line('lb_transaction_no') ?></label>
              <input name="ReturnNo" id="ReturnNo" type="text" class="form-control data-ID readonly" readonly>
            </div>

            <div class="form-group col-sm-4">
              <label class="control-label"><?= $this->lang->line('lb_date') ?> <span class="wajib"></span></label>
              <input name="Date" id="Date" type="text" value="<?= date("Y-m-d") ?>" class="form-control date cursor readonly" readonly>
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-sm-4">
              <label class="control-label"><?= $this->lang->line('lb_customer_name') ?> <span class="wajib"></span></label>
              <div class="input-group">
                <span class="input-group-addon pointer btn_select" onclick="vendor_modal('.autocomplete_vendor')">
                  <i class="fa-search" aria-hidden="true"></i>
                </span>
                <input name="CustomerName" id="CustomerName" type="text" class="form-control pointer readonly autocomplete_vendor-name" data-position="customer" readonly="readonly" placeholder="<?= $this->lang->line('lb_customer_select1') ?>" onclick="vendor_modal('.autocomplete_vendor')">
                <input name="CustomerID" id="CustomerID" type="text" class="form-control pointer readonly autocomplete_vendor content-hide" data-position="customer" readonly="readonly" placeholder="<?= $this->lang->line('lb_customer_select1') ?>" onclick="vendor_modal('.autocomplete_vendor')">
                <span></span>
              </div>
            </div>

            <div class="form-group col-sm-4 vsell">
              <label class="control-label"><?= $this->lang->line('lb_sellingno') ?> <span class="wajib"></span></label>
              <div class="input-group">
                <span class="input-group-addon pointer btn_select" onclick="select_sell('.autocomplete_sell')">
                  <i class="fa-search" aria-hidden="true"></i>
                </span>
                <input name="SellNo" id="SellNo" type="text" class="form-control readonly pointer autocomplete_sell" data-select="active" data-type="date" data-detail="active" onclick="select_sell('.autocomplete_sell')" placeholder="<?= $this->lang->line('lb_select') ?> <?= $this->lang->line('lb_sellingno') ?>" readonly>
              <!-- <span class="help-block"></span> -->
              </div>
            </div>

            <div class="form-group col-sm-4 vdelivery">
              <label class="control-label"><?= $this->lang->line('lb_deliveryno') ?> <span class="wajib"></span></label>
              <div class="input-group">
                <span class="input-group-addon pointer btn_select" onclick="select_delivery('.autocomplete_delivery')">
                  <i class="fa-search" aria-hidden="true"></i>
                </span>
                <input name="DeliveryNo" id="DeliveryNo" type="text" class="form-control readonly pointer autocomplete_delivery" data-select="active" data-detail="active" onclick="select_delivery('.autocomplete_delivery')" placeholder="<?= $this->lang->line('lb_select') ?> <?= $this->lang->line('lb_deliveryno') ?>" readonly>
                <!-- <span class="help-block"></span> -->
              </div>
            </div>

            <div class="form-group col-sm-4">
              <label class="control-label"><?= $this->lang->line('lb_sales_name') ?><span class="wajib"></span></label>
              <div class="input-group">
                <span class="input-group-addon pointer btn_select" onclick="sales_modal('.SalesID')">
                  <i class="fa-search" aria-hidden="true"></i>
                </span>
                <input name="SalesName" id="SalesName" type="text" class="form-control pointer readonly SalesID-name" readonly="readonly" placeholder="<?= $this->lang->line('lb_sales_select') ?>" onclick="sales_modal('.SalesID')">
                <input name="SalesID" id="SalesID" type="text" class="form-control pointer readonly SalesID content-hide" readonly="readonly" placeholder="<?= $this->lang->line('lb_sales_select') ?>" onclick="sales_modal('.SalesID')">
                <span class="input-group-addon pointer" onclick="sales_add('.SalesID')">
                  <i class="fa-plus" aria-hidden="true"></i>
                </span>
              </div>
            </div>

          </div>
          <div class="row">           
            <div class="form-group col-sm-12">
              <table class="table-add-product table table-striped input-table3" style="margin-top: 15px;margin-bottom: 15px;">
                <thead>
                  <tr>
                    <th></th>
                    <th style="width:30px"><input class="vorder" type="checkbox" name="check_all"></th>
                    <th class="vorder vdcode"><?= $this->lang->line('lb_sellingno') ?></th>
                    <th><?= $this->lang->line('lb_store'); ?></th>
                    <th class="th-code"><?= $this->lang->line('lb_product_code') ?></th>
                    <th><?= $this->lang->line('lb_product_name') ?></th>
                    <th width="70px" class="th-qty-s"><?= $this->lang->line('lb_sales_qty') ?></th>
                    <th width="100px"><?= $this->lang->line('lb_qty2') ?></th>
                    <th width="70px"><?= $this->lang->line('lb_unit') ?></th>
                    <th class="content-hide" width="70px">Conv</th>
                    <th><?= $this->lang->line('price') ?></th>
                    <th><?= $this->lang->line('lb_discount') ?> (%)</th>
                    <th><?= $this->lang->line('lb_tax') ?></th>
                    <th><?= $this->lang->line('lb_sub_total') ?></th>
                    <th class="th-sub"><?= $this->lang->line('lb_remark') ?></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              <div class="vaddrow"></div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="row">
              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_remarks') ?> <span class="wajib"></span></label>
                <textarea name="Remark" id="Remark" type="text" class="form-control" style="height: 100px" maxlength="225"></textarea>
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

              <div class="form-group col-sm-12 content-hide">
                <label class="control-label"><?= $this->lang->line('lb_discount_total') ?> (%)</label>
                <input type="text" name="Discount" id="Discount" class="form-control disabled">
                <span class="help-block"></span>
              </div>

              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_discount_total') ?></label>
                <input type="text" name="DiscountRp" id="DiscountRp" class="form-control duit disabled">
                <span class="help-block"></span>
              </div>

              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_tax') ?></label>
                <input type="text" name="PPN" id="PPN" value="10" class="form-control content-hide disabled">
                <input type="text" name="TotalPPN" id="TotalPPN" class="form-control duit disabled">
                <span class="help-block"></span>
              </div>

              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_total') ?></label>
                <input type="text" name="Total" id="Total" class="form-control duit disabled">
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
  <div class="modal-dialog width-80per">
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
          <?= $this->main->button_action("action", array("next","edit","cancel")); ?>
          <?= $this->main->button_action("print"); ?>
          <?= $this->main->general_button('close') ?>
          <button type="button" class="btn btn-danger btn-default margin-0 kotak btn-back"><?= $this->lang->line('btn_cancel') ?></button>
        </div>
      </div>
    </div>
  </div>
</div>