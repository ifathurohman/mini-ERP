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
            <li class="active vstep1" onclick="step(1)"><a data-toggle="tab" href="#vstep1" role="tab"><?= $this->lang->line('lb_order') ?></a></li>
            <li class="vstep2" onclick="step(2)"><a data-toggle="tab" href="#vstep2" role="tab"><?= $this->lang->line('lb_delivery1') ?></a></li>
            <li class="vstep3 content-hide" onclick="step(3)"><a data-toggle="tab" href="#vstep3" role="tab"><?= $this->lang->line('lb_invoice') ?></a></li>
          </ul>
        </div>
        <form id="form" autocomplete="off">
          <input type="hidden" name="unitid">
          <input type="hidden" name="crud">
          <input type="hidden" name="form_step">
          <div class="row col-sm-12 vdelivery content-hide">
            <div class="form-group pull-right">
                <label class="control-label">Notification <i class="icon wb-bell" style="cursor:pointer;" data-toggle="tooltip"  data-placement="left" data-html="true" title="isi data wajib untuk delivery. kosongkan data wajib untuk tidak delivery"></i></label>
                <span class="help-block"></span>
            </div>
          </div>
          <div class="row vsell">
            <div class="form-group col-sm-6">
              <label class="control-label block"><?= $this->lang->line('lb_product_type') ?></label>
              <div class="radio-custom radio-primary radio-inline">
                <input type="radio" id="item" name="product_status" value="0" checked="">
                <label for="item"><?= $this->lang->line('lb_product_item') ?></label>
              </div>
              <div class="radio-custom radio-primary radio-inline">
                <input type="radio" id="services" name="product_status" value="1">
                <label for="services"><?= $this->lang->line('lb_product_service') ?></label>
              </div>
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row vsell">
            <div class="form-group col-sm-4">
              <label class="control-label"><?= $this->lang->line('lb_transaction_no') ?></label>
              <input name="SellingNo" id="SellingNo" type="text" class="form-control disabled data-ID">
            </div>

            <div class="form-group col-sm-4">
              <label class="control-label"><?= $this->lang->line('lb_date') ?> <span class="wajib"></span></label>
              <input name="Date" id="Date" type="text" value="<?= date("Y-m-d") ?>" class="form-control readonly date cursor" readonly>
              <span class="help-block"></span>
            </div>

            <div class="form-group col-sm-4">
              <label class="control-label"><?= $this->lang->line('lb_delivery_date') ?></label>
              <div class="input-group">
                <span class="input-group-addon pointer btn_select" title="Remove Delivery Date" onclick="remove_value('DeliveryDate')">
                  <i class="fa-times" aria-hidden="true"></i>
                </span>
                <input type="text" name="DeliveryDate" class="form-control date cursor" placeholder="" id="DeliveryDate" value="<?= date("Y-m-d") ?>" maxlength="50">
              </div>
              <span class="help-block"></span>
            </div>

          </div>
          <div class="row vsell">
            <div class="form-group col-sm-4">
              <label class="control-label"><?= $this->lang->line('lb_customer_name') ?> <span class="wajib"></span></label>
              <div class="input-group">
                  <span class="input-group-addon pointer btn_select" onclick="vendor_modal('.autocomplete_vendor')">
                    <i class="fa-search" aria-hidden="true"></i>
                  </span>
                  <input name="CustomerName" id="CustomerName" type="text" class="form-control pointer readonly autocomplete_vendor-name" data-position="customer" data-page="delivery" readonly="readonly" placeholder="<?= $this->lang->line('lb_customer_select1') ?>" onclick="vendor_modal('.autocomplete_vendor')">
                  <input name="CustomerID" id="CustomerID" type="text" class="form-control pointer readonly autocomplete_vendor content-hide" data-position="customer" data-page="delivery" readonly="readonly" placeholder="<?= $this->lang->line('lb_customer_select1') ?>" onclick="vendor_modal('.autocomplete_vendor')">
                  <span></span>
                  <span class="input-group-addon pointer btn_select" onclick="venodr_add('.autocomplete_vendor')">
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
                <input name="SalesName" id="SalesName" type="text" class="form-control pointer readonly SalesID-name" readonly="readonly" placeholder="<?= $this->lang->line('lb_sales_select') ?>" onclick="sales_modal('.SalesID')">
                <input name="SalesID" id="SalesID" type="text" class="form-control pointer readonly SalesID content-hide" readonly="readonly" placeholder="<?= $this->lang->line('lb_sales_select') ?>" onclick="sales_modal('.SalesID')">
                <span class="input-group-addon pointer btn_select" onclick="sales_add('.SalesID')">
                  <i class="fa-plus" aria-hidden="true"></i>
                </span>
              </div>
            </div>

            <div class="form-group col-sm-4">
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

          </div>
          <div class="row vsell">
            <div class="form-group col-sm-4">
              <label class="control-label"><?= $this->lang->line('lb_purchaseno') ?></label>
              <input name="NoPo" id="NoPo" type="text" class="form-control">
              <span class="help-block"></span>
            </div>

            <div class="form-group col-sm-4">
              <label class="control-label"><?= $this->lang->line('lb_term') ?> (<?= $this->lang->line('lb_days') ?>)</label>
              <input name="Term" id="Term" type="text" class="form-control angkaint autocomplete_vendor-term">
              <span class="help-block"></span>
            </div>

            <div class="form-group col-sm-4">
              <label class="control-label"><?= $this->lang->line('lb_due_date') ?></label>
              <input name="DueDate" id="DueDate" type="text" class="form-control date cursor readonly" value="<?= date("Y-m-d") ?>">
              <span class="help-block"></span>
            </div>

          </div>
          
          <div class="row vsell">
            <div class="form-group col-sm-6">
              <div class="checkbox-custom checkbox-primary">
                <input type="checkbox" id="ckPPN" name="ckPPN" value="1">
                <label for="ckPPN"><?= $this->lang->line('lb_tax') ?> 10%</label>
              </div>
            </div>
          </div>

          <div class="row vdelivery">
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_delivery_to') ?> <span class="wajib"></span></label>
              <input type="text" name="DeliveryTo" id="DeliveryTo" class="form-control">
              <span class="help-block"></span>
            </div>
            <!-- <div class="form-group col-sm-6">
              <label class="control-label">Sales <span class="wajib"></span></label>
              <input name="delSalesID" id="delSalesID" type="text" class="form-control pointer readonly delSalesID" data-without="active" readonly="readonly" placeholder="<?= $this->lang->line('lb_sales_select') ?>" onclick="sales_modal('.delSalesID')">
              <span class="help-block"></span>
            </div> -->
          </div>
          <!-- <div class="row vdelivery">
            <div class="form-group col-sm-6">
              <label class="control-label">Date <span class="wajib"></span></label>
              <input name="DeliveryDate" id="DeliveryDate" type="text" value="<?= date("Y-m-d") ?>" class="form-control date pointer">
            </div>
          </div> -->
          <div class="row vdelivery">
            <div class="form-group col-sm-6">
                <label class="control-label"><?= $this->lang->line('lb_address') ?> <span class="wajib"></span></label>
                <div class="input-group">
                  <span class="input-group-addon pointer btn_select" onclick="select_address_vendor('.vdelivery')">
                    <i class="fa-search" aria-hidden="true"></i>
                  </span>
                  <textarea name="delAddress" id="delAddress" type="text" placeholder="<?= $this->lang->line('lb_customer_select1') ?> Address" class="form-control address autocomplete_vendor-address" style="height: 35px"></textarea>
                </div>
                <span class="help-block"></span>
              </div>
          </div>
          <div class="row vdelivery">
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_city') ?></label>
              <input name="delCity" id="delCity" type="text" class="form-control city autocomplete_vendor-city">
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row vdelivery">
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_province') ?></label>
              <input name="delProvince" id="delProvince" type="text" class="form-control province autocomplete_vendor-province">
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row vinvoice">
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_billing_to') ?> <span class="wajib"></span></label>
              <input type="text" name="BillingTo" id="BillingTo" class="form-control readonly">
              <span class="help-block"></span>
            </div>
            <!-- <div class="form-group col-sm-6">
              <label class="control-label">Sales</label>
              <input name="invSalesID" id="invSalesID" type="text" class="form-control pointer readonly invSalesID" readonly="readonly" placeholder="<?= $this->lang->line('lb_sales_select') ?>" onclick="sales_modal('.invSalesID')">
              <span class="help-block"></span>
            </div> -->
          </div>
          <div class="row vinvoice">
            <div class="form-group col-sm-6">
                <label class="control-label"><?= $this->lang->line('lb_address') ?> <span class="wajib"></span></label>
                <textarea name="invAddress" placeholder="<?= $this->lang->line('lb_customer_select1') ?> Address" id="invAddress" type="text" class="form-control readonly pointer address txtRemark" onclick="select_address_vendor('.vinvoice')" style="height: 50px"></textarea>
                <span class="help-block"></span>
              </div>
          </div>
          <div class="row vinvoice">
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_city') ?> <span class="wajib"></span></label>
              <input name="invCity" id="invCity" type="text" class="form-control readonly city">
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row vinvoice">
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_province') ?> <span class="wajib"></span></label>
              <input name="invProvince" id="invProvince" type="text" class="form-control readonly province">
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row">           
            <div class="form-group col-sm-12">
              <table class="table-add-product table table-striped input-table3" style="margin-top: 15px;margin-bottom: 0px;">
                <thead>
                  <tr> 
                    <th class="th-code" colspan="3"><?= $this->lang->line('lb_product_code') ?></th>
                    <th><?= $this->lang->line('lb_product_name') ?></th>
                    <th width="70px" class="vproduct-services"><?= $this->lang->line('lb_deliveryqty') ?></th>
                    <th width="70px" class="vproduct-services"><?= $this->lang->line('lb_qty2') ?></th>
                    <th width="70px" class="vproduct-services"><?= $this->lang->line('lb_unit') ?></th>
                    <th class="content-hide" width="70px">Conv</th>
                    <th><?= $this->lang->line('price') ?></th>
                    <th><?= $this->lang->line('lb_discount') ?> (%)</th>
                    <th><?= $this->lang->line('lb_sub_total') ?></th>
                    <th class="th-sub"><?= $this->lang->line('lb_remark') ?></th>
                    <th><?= $this->lang->line('lb_delivery_date') ?></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              <a href="javascript:void(0)" onclick="add_new_row()" class="link_add_row">+ <?= $this->lang->line('lb_add_column') ?></a>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="row">
              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_remarks') ?></label>
                <textarea name="sell_remark" id="sell_remark" class="form-control txtRemark" style="height: 100px"></textarea>
                <span class="help-block"></span>
              </div>

              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_attachment') ?></label>
                <div class="file-result"></div>
                <div class="form-group inputDnD">
                  <label class="sr-only" for="inputFile"><?= $this->lang->line('lb_file_upload') ?></label>
                  <input type="file" multiple="multiple" class="form-control-file text-success font-weight-bold" id="inputFile" onchange="readUrl(this)" data-title='Choose file in brwoser'>
                </div>
                <div class="progress-data"></div>
                <span class="help-block"></span>
              </div>
              
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

               <div class="form-group col-sm-12 vsell">
                <label class="control-label"><?= $this->lang->line('lb_delivery_cost') ?></label>
                <input name="Ongkir" id="Ongkir" type="text" onkeyup="SumTotal()" class="form-control duit" >
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
          <button type="button" class="btn btn-primary save btn-save2"><?= $this->lang->line('btn_save') ?></button>
          <?= $this->main->button_action("action", array("cancel","edit","next","attachment")); ?>
          <?= $this->main->button_action("print"); ?>
          <?= $this->main->general_button('close') ?>
          <button type="button" class="btn btn-danger btn-default margin-0 kotak btn-back"><?= $this->lang->line('btn_cancel') ?></button>
        </div>
      </div>
    </div>
  </div>
</div>