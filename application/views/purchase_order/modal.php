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
            <li class="active vstep1" onclick="step(1)"><a data-toggle="tab" href="#vstep1" role="tab"><?= $this->lang->line('lb_order'); ?></a></li>
            <li class="vstep2" onclick="step(2)"><a data-toggle="tab" href="#vstep2" role="tab"><?= $this->lang->line('lb_delivery1'); ?></a></li>
            <li class="vstep3" onclick="step(3)"><a data-toggle="tab" href="#vstep3" role="tab"><?= $this->lang->line('lb_invoice'); ?></a></li>
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
              <label class="control-label block"><?= $this->lang->line('lb_product_type'); ?></label>
              <div class="radio-custom radio-primary radio-inline">
                <input type="radio" id="item" name="product_status" value="0" checked="">
                <label for="item"><?= $this->lang->line('lb_product_item'); ?></label>
              </div>
              <div class="radio-custom radio-primary radio-inline">
                <input type="radio" id="services" name="product_status" value="1">
                <label for="services"><?= $this->lang->line('lb_product_service'); ?></label>
              </div>
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row vsell">
            <div class="form-group col-sm-4">
              <label class="control-label"><?= $this->lang->line('lb_transaction_no') ?></label>
              <input name="PurchaseNo" id="PurchaseNo" type="text" class="form-control data-ID readonly" readonly>
            </div>

            <div class="form-group col-sm-4">
              <label class="control-label"><?= $this->lang->line('lb_date'); ?> <span class="wajib"></span></label>
              <input name="Date" id="Date" type="text" value="<?= date("Y-m-d") ?>" class="form-control date cursor readonly" readonly>
              <span class="help-block"></span>
            </div>

            <div class="form-group col-sm-4">
              <label class="control-label"><?= $this->lang->line('lb_vendor'); ?> <span class="wajib"></span></label>
              <div class="input-group">
                <span class="input-group-addon pointer btn_select" onclick="vendor_modal('.autocomplete_vendor')">
                  <i class="fa-search" aria-hidden="true"></i>
                </span>
                <input name="VendorName" id="VendorName" type="text" class="form-control pointer readonly autocomplete_vendor-name" data-position="customer" readonly="readonly" placeholder="<?= $this->lang->line('lb_vendor_select'); ?>" onclick="vendor_modal('.autocomplete_vendor')">
                <input name="VendorID" id="VendorID" type="text" class="form-control pointer readonly autocomplete_vendor content-hide" data-position="vendor" readonly="readonly" placeholder="<?= $this->lang->line('lb_vendor_select'); ?>" onclick="vendor_modal('.autocomplete_vendor')">
                <span></span>
                <span class="input-group-addon pointer btn_select" onclick="venodr_add('.autocomplete_vendor')">
                  <i class="fa-plus" aria-hidden="true"></i>
                </span>
                <!-- <span class="help-block"></span> -->
              </div>
            </div>

          </div>
          <div class="row vsell">
            <div class="form-group col-sm-4">
              <label class="control-label"><?= $this->lang->line('lb_sales'); ?> <span class="wajib"></span></label>
              <div class="input-group">
                <span class="input-group-addon pointer btn_select" onclick="sales_modal('.SalesID')">
                  <i class="fa-search" aria-hidden="true"></i>
                </span>
                <input name="SalesName" id="SalesName" type="text" class="form-control pointer readonly SalesID-name" readonly="readonly" placeholder="<?= $this->lang->line('lb_sales_select'); ?>" onclick="sales_modal('.SalesID')">
                <input name="SalesID" id="SalesID" type="text" class="form-control pointer readonly SalesID content-hide" readonly="readonly" placeholder="<?= $this->lang->line('lb_sales_select'); ?>" onclick="sales_modal('.SalesID')">
                <span></span>
                <span class="input-group-addon pointer btn_select" onclick="sales_add('.SalesID')">
                  <i class="fa-plus" aria-hidden="true"></i>
                </span>
                <!-- <span class="help-block"></span> -->
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

            <div class="form-group col-sm-4">
              <label class="control-label"><?= $this->lang->line('lb_term'); ?> (<?= $this->lang->line('lb_days'); ?>)</label>
              <input name="Term" id="Term" type="text" min="0" class="form-control angkaint autocomplete_sell-term autocomplete_vendor-term">
              <span class="help-block"></span>
            </div>

          </div>
          <div class="row vsell">
            <div class="form-group col-sm-4">
              <label class="control-label"><?= $this->lang->line('lb_due_date'); ?></label>
              <input name="DueDate" id="DueDate" type="text" class="form-control date cursor readonly" value="<?= date("Y-m-d") ?>">
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row vsell">
            <div class="form-group col-sm-4">
              <div class="checkbox-custom checkbox-primary">
                <input type="checkbox" id="ckPPN" name="ckPPN" value="1">
                <label for="ckPPN"><?= $this->lang->line('lb_tax'); ?> %</label>
              </div>
            </div>
          </div>
          <div class="row vdelivery">
            <div class="form-group col-sm-6">
                <label class="control-label"><?= $this->lang->line('lb_address'); ?> <span class="wajib"></span></label>
                <textarea name="delAddress" id="delAddress" class="form-control txtRemark" style="height: 50px"><?= $datacompany->address ?></textarea>
                <span class="help-block"></span>
              </div>
          </div>
          <div class="row vdelivery">
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_city'); ?> <span class="wajib"></span></label>
              <input name="delCity" id="delCity" type="text" class="form-control" value="<?= $datacompany->city ?>">
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row vdelivery">
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_province'); ?> <span class="wajib"></span></label>
              <input name="delProvince" id="delProvince" type="text" value="<?= $datacompany->province ?>" class="form-control">
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row vinvoice">
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_name'); ?> <span class="wajib"></span></label>
              <input type="text" name="BillingTo" id="BillingTo" class="form-control readonly" value="<?= $datacompany->nama ?>">
              <span class="help-block"></span>
            </div>
            <!-- <div class="form-group col-sm-6">
              <label class="control-label">Sales</label>
              <input name="invSalesID" id="invSalesID" type="text" class="form-control pointer readonly invSalesID" readonly="readonly" placeholder="Select Sales" onclick="sales_modal('.invSalesID')">
              <span class="help-block"></span>
            </div> -->
          </div>
          <div class="row vinvoice">
            <div class="form-group col-sm-6">
                <label class="control-label"><?= $this->lang->line('lb_address'); ?> <span class="wajib"></span></label>
                <textarea name="invAddress" id="invAddress" class="form-control txtRemark" style="height: 50px"><?= $datacompany->address ?></textarea>
                <span class="help-block"></span>
              </div>
          </div>
          <div class="row vinvoice">
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_city'); ?> <span class="wajib"></span></label>
              <input name="invCity" id="invCity" type="text" class="form-control" value="<?= $datacompany->city ?>">
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row vinvoice">
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_province'); ?> <span class="wajib"></span></label>
              <input name="invProvince" id="invProvince" type="text" class="form-control" value="<?= $datacompany->province ?>">
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row">           
            <div class="form-group col-sm-12">
              <table class="table-add-product table table-striped input-table3" style="margin-top: 15px;margin-bottom: 0px;">
                <thead>
                  <tr> 
                    <th class="th-code" colspan="4"><?= $this->lang->line('lb_product_code'); ?></th>
                    <th><?= $this->lang->line('lb_product_name'); ?></th>
                    <th width="70px" class="vproduct-services"><?= $this->lang->line('lb_qty_hand'); ?></th>
                    <th width="70px" class="vproduct-services"><?= $this->lang->line('lb_qty2'); ?></th>
                    <th width="100px" class="vproduct-services"><?= $this->lang->line('lb_unit'); ?></th>
                    <th class="content-hide" width="70px"><?= $this->lang->line('lb_conversion'); ?></th>
                    <th><?= $this->lang->line('price'); ?></th>
                    <th><?= $this->lang->line('lb_discount'); ?> (%)</th>
                    <th><?= $this->lang->line('lb_sub_total'); ?></th>
                    <th class="th-sub"><?= $this->lang->line('lb_remark'); ?></th>
                    <th><?= $this->lang->line('lb_delivery_date'); ?></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              <a href="javascript:void(0)" onclick="add_new_row()" class="link_add_row">+ <?= $this->lang->line('lb_add_column'); ?></a>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="row">
              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_remarks'); ?></label>
                <textarea name="purchase_remark" id="purchase_remark" class="form-control txtRemark" style="height: 100px"></textarea>
                <span class="help-block"></span>
              </div>
            </div>
              
            <div class="form-attach"></div>

          </div>
          <div class="col-sm-6">
            <div class="row">
              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_sub_total'); ?></label>
                <input type="text" name="SubTotal" id="SubTotal" class="form-control duit disabled">
                <span class="help-block"></span>
              </div>

              <div class="form-group col-sm-12 content-hide">
                <label class="control-label"><?= $this->lang->line('lb_discount_total'); ?> (%)</label>
                <input type="text" name="Discount" id="Discount" class="form-control disabled">
                <span class="help-block"></span>
              </div>

              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_discount_total'); ?> (Rp)</label>
                <input type="text" name="DiscountRp" id="DiscountRp" class="form-control duit disabled">
                <span class="help-block"></span>
              </div>

              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_tax'); ?></label>
                <input type="text" name="PPN" id="PPN" value="10" class="form-control content-hide disabled">
                <input type="text" name="TotalPPN" id="TotalPPN" class="form-control duit disabled">
                <span class="help-block"></span>
              </div>

              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_delivery_cost'); ?></label>
                <input name="Ongkir" id="Ongkir" type="text" onkeyup="SumTotal()" class="form-control duit autocomplete_sell-deliverycost">
                <span class="help-block"></span>
              </div>

              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_total'); ?></label>
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
          <button type="button" class="btn btn-primary save btn-save2"><?= $this->lang->line('btn_save'); ?></button>
          <?= $this->main->button_action("action", array("cancel","edit","next","attachment")); ?>
          <?= $this->main->button_action("print"); ?>
          <?= $this->main->general_button('close') ?>
          <button type="button" class="btn btn-danger btn-default margin-0 kotak btn-back"><?= $this->lang->line('btn_cancel'); ?></button>
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
          <a href="#" id="link_print" target="_blank" class="btn btn-outline btn-default btn-white kotak vbtn-view">Print</a>
          <div class="btn-group vbtn-view open">
             <button type="button" class="btn btn-outline btn-default btn-white dropdown-toggle  waves-effect" data-toggle="dropdown" aria-expanded="true">
             PDF <span class="caret"></span> </button>
             <ul class="dropdown-menu">
                <li><a href="#" id="link_pdf_1" target="_blank">Portrait</a></li>
                <li><a href="#" id="link_pdf_2" target="_blank">Landscape</a></li>
             </ul>
          </div>
          <button type="button" class="btn btn-outline btn-default margin-0 kotak" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>