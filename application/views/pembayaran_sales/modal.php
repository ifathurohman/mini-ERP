<!-- Modal -->
<div id="modal" class="modal modal-primary fade modal-fade-in-scale-up" data-backdrop="static" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" > 
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
          <input type="hidden" name="paymentno">
          <div class="row">
            <div class="col-sm-6">
              <div class="row">
                <div class="form-group col-sm-12">
                  <label class="control-label"><?= $this->lang->line('lb_paymentno') ?></label>
                  <input type="text" name="paymentno" class="form-control readonly" placeholder="" id="paymentno">
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label"><?= $this->lang->line('lb_store_name') ?></label>
                  <input type="hidden" name="branchid" placeholder="" id="branchid">
                  <div class="input-group">
                    <span class="input-group-addon pointer btn_select" onclick="branch_modal('payment_sales','.autocompletebranch')">
                      <i class="fa-search" aria-hidden="true"></i>
                    </span>
                    <input type="text" name="BranchName" class="form-control pointer addbranchmodal autocompletebranch-name readonly" placeholder="<?= $this->lang->line('lb_store_select') ?>" id="name">
                    <input type="text" name="name" class="form-control pointer addbranchmodal autocompletebranch readonly content-hide" placeholder="<?= $this->lang->line('lb_store_select') ?>" id="name">
                  </div>
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-8">
                  <label class="control-label"><?= $this->lang->line('lb_date') ?></label>
                  <input type="text" name="date" class="form-control date" placeholder="" >
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-4">
                  <label style="color: #fff;">t</label>
                  <a href="javascript:void(0)" class="btn btn-outline btn-default form-control btn-search-sell" onclick="date_change(this)">Search Data</a>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label"><?= $this->lang->line('lb_total_ar_invoice') ?></label>
                  <input name="total_ar" id="total_ar" type="text" class="form-control readonly">
                  <span class="help-block"></span>
                </div>             
              </div>
            </div>
            <div class="col-sm-6">
              <div class="row">
                <div class="form-group col-sm-12">
                  <label class="control-label"><?= $this->lang->line('lb_pay_cash') ?></label>
                  <input name="pay_cash" id="pay_cash" type="text" class="form-control angka gt">
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label"><?= $this->lang->line('lb_pay_credit_debit') ?></label>
                  <input name="pay_credit" id="pay_credit" type="text" class="form-control angka gt">
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label"><?= $this->lang->line('lb_pay_giro') ?></label>
                  <input name="pay_giro" id="pay_giro" type="text" class="form-control angka gt">
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label"><?= $this->lang->line('lb_pay_additional') ?></label>
                    <input name="add_cost" id="add_cost" type="text" class="form-control angka gt">
                  <span class="help-block"></span>
                </div>

              </div>              
            </div>
            <div class="col-sm-12">
              <div class="row">
                
                <div class="form-group col-sm-6">
                  <label class="control-label"><?= $this->lang->line('lb_grand_total_sales') ?></label>
                  <input name="total_payment" id="total_payment" type="text" class="form-control readonly">
                  <span class="help-block"></span>
                </div>   
                <div class="form-group col-sm-6">
                  <label class="control-label"><?= $this->lang->line('lb_total_payment') ?></label>
                    <input name="grandtotal" id="grandtotal" type="text" class="form-control readonly">
                  <span class="help-block"></span>
                </div>                
              </div>
            </div>
            <div class="form-group col-sm-12">
              <table class="table-add-sell table-td-padding-0 table  table-striped input-table" style="margin-top: 15px;margin-bottom: 0px;">
                <thead>
                  <tr> 
                    <th width="50px"><input type="checkbox" name="cekbox_all" value="1" onclick="cekboxall(this)"></th>
                    <th><?= $this->lang->line('lb_sellingno') ?></th>
                    <th><?= $this->lang->line('lb_date') ?></th>
                    <th><?= $this->lang->line('lb_customer_name') ?></th>
                    <th style="width: 30%"><?= $this->lang->line('lb_total_sales') ?></th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
              <!-- <a href="javascript:void(0)" onclick="add_new_row()" class="link_add_row">+ Tambah Kolom</a> -->
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