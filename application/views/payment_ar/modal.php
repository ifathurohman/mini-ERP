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
          <input type="hidden" name="temp_paymentno">
          <input type="hidden" name="temp_paymentdet">
          <input type="hidden" name="temp_invoiceno">
          <input type="hidden" name="temp_balancedetid">

          <div class="col-sm-6">
            <div class="row">
              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_transaction_no') ?></label>
                <input type="text" name="PaymentNo" class="form-control data-ID" readonly>
                <span class="help-block"></span>
              </div>

              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_date') ?> <span class="wajib"></span></label>
                <input type="text" name="Date" class="form-control date cursor" value="<?= date("Y-m-d") ?>" readonly>
                <span class="help-block"></span>
              </div>

              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_customer_name') ?> <span class="wajib"></span></label>
                <input name="CustomerName" id="CustomerName" type="text" class="form-control pointer readonly autocomplete_vendor-name" data-position="customer" readonly="readonly" placeholder="Select Customer" onclick="vendor_modal('.autocomplete_vendor')">
                <input type="text" name="CustomerID" id="CustomerID" class="form-control pointer readonly autocomplete_vendor content-hide" data-position="customer" onclick="vendor_modal('.autocomplete_vendor')" placeholder="select customer " readonly>
                <span></span>
              </div>
            </div>
          </div>

            <div class="col-sm-6">
            <div class="row">
              <div class="form-group col-sm-2">
                <div class="checkbox-custom checkbox-primary" style="padding-top: 16px;">
                  <input type="checkbox" id="PaymentType1" name="PaymentType1" value="1">
                  <label for="PaymentType1"><?= $this->lang->line('lb_cash') ?></label>
                </div>
              </div>

              <div id="cash">
                <div class="form-group col-sm-10 vpmethod">
                  <label class="control-label"></label>
                  <select class="form-control coa_select content-hide" data-select="active" data-level="4"></select>
                  <select name="pay_paymentmethod1" id="pay_paymentmethod1" class="form-control selectpicker" data-live-search="true">   
                  </select>
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label"><?= $this->lang->line('lb_pay_cash') ?></label>
                  <input name="pay_cash" id="pay_cash" type="text" class="form-control duit" onkeyup="cekrow()" disabled>
                  <span class="help-block"></span>

                </div>
              </div>

               <div class="form-group col-sm-2">
                <div class="checkbox-custom checkbox-primary" style="padding-top: 16px;">
                  <input type="checkbox" id="PaymentType3" name="PaymentType3" value="3">
                  <label for="PaymentType3"><?= $this->lang->line('lb_transfer') ?></label>
                </div>
              </div>

              <div id="card">
                <div class="form-group col-sm-10 vpmethod2">
                  <label class="control-label"></label>
                  <select name="pay_paymentmethod3" id="pay_paymentmethod3" class="form-control selectpicker" data-live-search="true">   
                  </select>
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12 v-card">
                  <label class="control-label"><?= $this->lang->line('lb_bank_acountno') ?></label>
                  <input type="text" name="AccountNo" class="form-control" disabled>
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12 v-card">
                  <label class="control-label"><?= $this->lang->line('lb_bank_name') ?></label>
                  <input type="text" name="BankName" class="form-control" disabled>
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12 v-card">
                  <label class="control-label"><?= $this->lang->line('lb_bank_acount') ?></label>
                  <input type="text" name="AccountName" class="form-control" disabled>
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label"><?= $this->lang->line('lb_pay_transfer') ?></label>
                  <input name="pay_credit" id="pay_credit" type="text" class="form-control duit" onkeyup="cekrow()" disabled>
                  <span class="help-block"></span>
                </div>
              </div>

              <div class="form-group col-sm-2">
                <div class="checkbox-custom checkbox-primary" style="padding-top: 16px;">
                  <input type="checkbox" id="PaymentType2" name="PaymentType2" value="2">
                  <label for="PaymentType2"><?= $this->lang->line('lb_giro') ?></label>
                </div>
              </div>

              <div id="giro">
                <div class="form-group col-sm-10 vpmethod1">
                  <label class="control-label"></label>
                  <select name="pay_paymentmethod2" id="pay_paymentmethod2" class="form-control selectpicker" data-live-search="true">   
                  </select>
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12 v-giro">
                  <label class="control-label"><?= $this->lang->line('lb_girono') ?></label>
                  <input type="text" name="GiroNo" class="form-control" disabled>
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12 v-giro">
                  <label class="control-label"><?= $this->lang->line('lb_bank_name') ?></label>
                  <input type="text" name="BankName1" class="form-control" disabled>
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12 v-giro">
                  <label class="control-label"><?= $this->lang->line('lb_bank_acount') ?></label>
                  <input type="text" name="AccountName1" class="form-control" disabled>
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label"><?= $this->lang->line('lb_pay_giro') ?></label>
                  <input name="pay_giro" id="pay_giro" type="text" class="form-control duit" onkeyup="cekrow()" disabled>
                  <span class="help-block"></span>
                </div>
              </div>

              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_total_payment') ?></label>
                  <input name="grandtotal" id="grandtotal" type="text" class="form-control duit readonly" maxlength="50">
                <span class="help-block"></span>
              </div>
            </div>
          </div>

          <div class="row">           
            <div class="form-group col-sm-12">
              <table class="table-add-product table table-striped input-table3" style="margin-top: 15px;margin-bottom: 0px;">
                <thead>
                  <tr>
                    <th></th>
                    <th style="width:30px"><input type="checkbox" name="check_all"></th>
                    <th><?= $this->lang->line('lb_transaction_no') ?></th>
                    <th><?= $this->lang->line('lb_transaction_type') ?></th>
                    <th><?= $this->lang->line('lb_balance_type') ?></th>
                    <th><?= $this->lang->line('lb_date') ?></th>
                    <th><?= $this->lang->line('lb_pay_total') ?></th>
                    <th><?= $this->lang->line('lb_total_unpaid') ?></th>
                    <th><?= $this->lang->line('lb_total_paid') ?></th>
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
                <label class="control-label"><?= $this->lang->line('lb_pay_total') ?></label>
                <input type="text" name="TotalPay" id="TotalPay" class="form-control duit disabled">
                <span class="help-block"></span>
              </div>

              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_total_paid') ?></label>
                <input type="text" name="TotalPaid" id="TotalPaid" class="form-control duit disabled">
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
          <?= $this->main->button_action("action", array("cancel","edit")); ?>
          <?= $this->main->button_action("print"); ?>
          <?= $this->main->general_button('close') ?>
          <button type="button" class="btn btn-danger btn-default margin-0 kotak btn-back"><?= $this->lang->line('btn_cancel') ?></button>
        </div>
      </div>
    </div>
  </div>
</div>