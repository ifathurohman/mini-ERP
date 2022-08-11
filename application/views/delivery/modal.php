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
        <div class="nav-tabs-horizontal" style="margin-bottom: 20px">
          <ul class="nav nav-tabs tab-step" data-plugin="nav-tabs" role="tablist">
            <li class="active vstep1" onclick="step(1)"><a data-toggle="tab" href="#vstep1" role="tab"><?= $this->lang->line('lb_delivery1') ?></a></li>
            <li class="vstep2" onclick="step(2)"><a data-toggle="tab" href="#vstep2" role="tab"><?= $this->lang->line('lb_delivery_address') ?></a></li>
          </ul>
        </div>

        <form id="form" autocomplete="off">
          <input type="hidden" name="crud">
          <input type="hidden" name="temp_sellno">
          <input type="hidden" name="temp_selldet">
          <div class="row vdelivery">
            <div class="form-group col-sm-12">
              <label class="control-label block"><?= $this->lang->line('lb_product_type') ?></label>
            </div>
            <div class="form-group col-sm-2">
              <div class="radio-custom radio-primary">
                <input type="radio" id="item" name="product_status" value="0" checked="">
                <label for="item"><?= $this->lang->line('lb_product_item') ?></label>
              </div>
            </div>
            <div id="service">
              <div class="form-group col-sm-2">
                <div class="radio-custom radio-primary">
                  <input type="radio" id="services" name="product_status" value="1">
                  <label for="services"><?= $this->lang->line('lb_product_service') ?></label>
                </div>
              </div>
            </div>
          </div>
          <div class="row vdelivery">
            <div class="form-group col-sm-2">
              <div class="radio-custom radio-primary">
                <input type="radio" id="ckOrder2" name="ckOrder" value="1" checked>
                <label for="ckOrder2"><?= $this->lang->line('lb_selling') ?></label>
              </div>
            </div>
            <div class="form-group col-sm-2">
              <div class="radio-custom radio-primary">
                <input type="radio" id="ckOrder3" name="ckOrder" value="2">
                <label for="ckOrder3"><?= $this->lang->line('lb_sales_order_non') ?></label>
              </div>
            </div>
          </div>
          <div class="row vdelivery">
            <div class="form-group col-sm-4">
              <label class="control-label"><?= $this->lang->line('lb_transaction_no') ?></label>
              <input name="DeliveryNo" id="DeliveryNo" type="text" class="form-control data-ID readonly" readonly>
            </div>

            <div class="form-group col-sm-4">
              <label class="control-label"><?= $this->lang->line('lb_date') ?> <span class="wajib"></span></label>
              <input name="Date" id="Date" type="text" value="<?= date("Y-m-d") ?>" class="form-control date cursor readonly" readonly>
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row vdelivery">
            <div class="form-group col-sm-4">
              <label class="control-label"><?= $this->lang->line('lb_customer_name') ?> <span class="wajib"></span></label>
              <div class="input-group">
                <span class="input-group-addon pointer btn_select" onclick="vendor_modal('.autocomplete_vendor')">
                  <i class="fa-search" aria-hidden="true"></i>
                </span>
                <input name="CustomerName" id="CustomerName" type="text" class="form-control pointer readonly autocomplete_vendor-name" data-position="customer" data-page="delivery" readonly="readonly" placeholder="Select Customer" onclick="vendor_modal('.autocomplete_vendor')">
                <input name="CustomerID" id="CustomerID" type="text" class="form-control pointer readonly autocomplete_vendor content-hide" data-position="customer" data-page="delivery" readonly="readonly" placeholder="Select Customer" onclick="vendor_modal('.autocomplete_vendor')">
                <span></span>
                <span class="input-group-addon pointer" onclick="venodr_add('.autocomplete_vendor')">
                  <i class="fa-plus" aria-hidden="true"></i>
                </span>
              </div>
            </div>

            <div class="form-group col-sm-4">
              <label class="control-label"><?= $this->lang->line('lb_sales_name') ?></label>
              <div class="input-group">
                <span class="input-group-addon pointer btn_select" onclick="sales_modal('.SalesID')">
                  <i class="fa-search" aria-hidden="true"></i>
                </span>
                <input name="SalesName" id="SalesName" type="text" class="form-control pointer readonly SalesID-name autocomplete_sell-salesnm" readonly="readonly" placeholder="Select Sales" onclick="sales_modal('.SalesID')">
                <input name="SalesID" id="SalesID" type="text" class="form-control pointer readonly SalesID autocomplete_sell-sales content-hide" readonly="readonly" placeholder="Select Sales" onclick="sales_modal('.SalesID')">
                <span class="input-group-addon pointer" onclick="sales_add('.SalesID')">
                  <i class="fa-plus" aria-hidden="true"></i>
                </span>
              </div>
            </div>

            <div class="form-group col-sm-4 vnonorder">
              <label class="control-label"><?= $this->lang->line('lb_store') ?> <span class="wajib"></span></label>
              <div class="input-group">
                <span class="input-group-addon pointer btn_select" onclick="branch_modal('','.autocomplete_branch')">
                  <i class="fa-search" aria-hidden="true"></i>
                </span>
                <input id="BranchName" type="text" class="form-control pointer readonly autocomplete_branch-name" readonly="readonly" placeholder="<?= $this->lang->line('lb_store_select') ?>" onclick="branch_modal('','.autocomplete_branch')">
                <input name="BranchID" id="BranchID" type="text" placeholder="<?= $this->lang->line('lb_store_select') ?>" class="form-control pointer readonly autocomplete_branch content-hide" data-select="active" onclick="branch_modal('','.autocomplete_branch')">
                <span></span>
              </div>
            </div>

            <div class="form-group col-sm-4 vorder">
              <label class="control-label"><?= $this->lang->line('lb_sellingno') ?></label>
              <div class="input-group">
                <span class="input-group-addon pointer btn_select" onclick="select_sell('.autocomplete_sell')">
                  <i class="fa-search" aria-hidden="true"></i>
                </span>
                <input name="SellNo" id="SellNo" type="text" class="form-control readonly pointer autocomplete_sell" data-select="active" data-type="date" data-detail="active" data-without="active" onclick="select_sell('.autocomplete_sell')" placeholder="Select <?= $this->lang->line('lb_sellingno') ?>" readonly>
                <span class="help-block"></span>
              </div>
            </div>

            <div class="form-group col-sm-4 vnonorder">
              <label class="control-label"><?= $this->lang->line('lb_term') ?> (<?= $this->lang->line('lb_days') ?>)</label>
              <input name="Term" id="Term" type="text" class="form-control angkaint autocomplete_sell-term autocomplete_vendor-term">
              <span class="help-block"></span>
            </div>

            <div class="form-group col-sm-4 vnonorder">
              <label class="control-label"><?= $this->lang->line('lb_due_date') ?></label>
              <input name="DueDate" id="DueDate" type="text" class="form-control date cursor readonly" value="<?= date("Y-m-d") ?>">
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row vdelivery">
            <div class="form-group col-sm-6">
              <div class="checkbox-custom checkbox-primary">
                <input type="checkbox" class="autocomplete_sell-tax" id="ckPPN" name="ckPPN" value="1" checked>
                <label for="ckPPN"><?= $this->lang->line('lb_tax') ?> 10%</label>
              </div>
            </div>
          </div>
          <div class="row vdaddress">
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_address') ?> <span class="wajib"></span></label>
              <div class="input-group">
                <span class="input-group-addon pointer btn_select" onclick="select_address_vendor('.vdaddress')">
                  <i class="fa-search" aria-hidden="true"></i>
                </span>
                <textarea name="delAddress" id="delAddress" type="text" placeholder="Select Customer Address" class="form-control address autocomplete_vendor-address" style="height: 35px"></textarea>
              </div>
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row vdaddress">
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_city') ?></label>
              <input name="delCity" id="delCity" type="text" class="form-control city autocomplete_vendor-city">
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row vdaddress">
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_province') ?></label>
              <input name="delProvince" id="delProvince" type="text" class="form-control province autocomplete_vendor-province">
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-sm-12">
              <table class="table-add-product table table-striped input-table3" style="margin-top: 15px;margin-bottom: 15px;">
                <thead>
                  <tr>
                    <th></th>
                    <th style="width:30px"><input class="vorder" type="checkbox" name="check_all"></th>
                    <th class="vorder"><?= $this->lang->line('lb_sellingno') ?></th>
                    <th class="vorder"><?= $this->lang->line('lb_store') ?></th>
                    <th class="th-code"><?= $this->lang->line('lb_product_code') ?></th>
                    <th><?= $this->lang->line('lb_product_name') ?></th>
                    <th width="70px" class="th-qty-s vproduct_services"><?= $this->lang->line('lb_sales_qty') ?></th>
                    <th width="100px" class="vproduct_services"><?= $this->lang->line('lb_deliveryqty') ?></th>
                    <th width="70px" class="vproduct_services"><?= $this->lang->line('lb_unit') ?></th>
                    <th class="content-hide" width="70px">Conv</th>
                    <th><?= $this->lang->line('price') ?></th>
                    <th><?= $this->lang->line('lb_discount') ?> (%)</th>
                    <th><?= $this->lang->line('lb_tax') ?></th>
                    <th><?= $this->lang->line('lb_sub_total') ?></th>
                    <th class="vorder"><?= $this->lang->line('lb_delivery_cost') ?></th>
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
                <label class="control-label"><?= $this->lang->line('lb_remarks') ?></label>
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

               <div class="form-group col-sm-12 vdelivery">
                <label class="control-label"><?= $this->lang->line('lb_delivery_cost') ?></label>
                <input name="Ongkir" id="Ongkir" type="text" onkeyup="SumTotal()" class="form-control duit autocomplete_sell-deliverycost">
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
          <?= $this->main->button_action("action", array("cancel","edit","next")); ?>
          <?= $this->main->button_action("print"); ?>
          <?= $this->main->general_button('close') ?>
          <button type="button" class="btn btn-danger btn-default margin-0 kotak btn-back"><?= $this->lang->line('btn_cancel') ?></button>
        </div>
      </div>
    </div>
  </div>
</div>