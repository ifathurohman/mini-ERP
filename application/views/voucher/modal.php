<!-- Modal -->
<div id="modal" class="modal modal-primary fade modal-fade-in-scale-up" data-backdrop="static" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">

      	<div class="view_info"></div>

        <form id="form" autocomplete="off">
          <input type="hidden" name="crud">
          <input type="hidden" name="VoucherID">
          <div class="row form-buy">
            <div class="form-group col-sm-12 content-hide">
                <label class="control-label block">Application</label>
                <div class="radio-custom radio-primary radio-inline">
                  <input type="radio" id="pipesys" name="App" value="pipesys" checked>
                  <label for="pipesys">Pipesys</label>
                </div>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label"><?= $this->lang->line('lb_package') ?></label>
              <select name="Type" class="form-control">
                <option value="none"><?= $this->lang->line('month') ?></option>
                <option value="3">3 <?= $this->lang->line('month') ?></option>
                <option value="6">6 <?= $this->lang->line('month') ?></option>
                <option value="12">1 <?= $this->lang->line('year') ?></option>
              </select>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label"><?= $this->lang->line('lb_voucher_module') ?></label>
              <select name="Module" class="form-control">
                <option value="none"><?= $this->lang->line('lb_voucher_module_choose') ?></option>
                <?php foreach(range(1,4) as $a): ?>
	                <option value="<?= $a ?>"><?= $a." ".$this->lang->line('module'); ?></option>
              	<?php endforeach; ?>
              </select>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label"><?= $this->lang->line('lb_voucher_user') ?></label>
              <select name="Qty" class="form-control">
                <option value="none"><?= $this->lang->line('lb_voucher_user_choose') ?></option>
              <?php foreach(range(1,100) as $a): ?>
                <option><?= $a; ?></option>
              <?php endforeach; ?>
              </select>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12 vpriceModule">
              <label class="control-label"><?= $this->lang->line('lb_voucher_module_amount') ?></label>
              <input type="text" name="PriceModule" readonly="" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12 vpriceDevice">
              <label class="control-label"><?= $this->lang->line('lb_voucher_user_amount') ?></label>
              <input type="text" name="PriceDevice" readonly="" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label"><?= $this->lang->line('lb_total_amount') ?></label>
              <input type="text" name="Price" readonly="" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label"><?= $this->lang->line('lb_bank') ?></label>
              <select name="Bank" class="form-control">
                <!-- <option value="none">Choose Bank</option>
                <option value="BCA">BCA</option>
                <option value="BRI">BRI</option>
                <option value="mandiri">Mandiri</option> -->
                <option value="OCBC - NISP">OCBC - NISP</option>
              </select>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <?= $this->lang->line('lb_noted') ?> :  <br/><?= $this->lang->line('lb_account_ex') ?>
            </div>
          </div>
          <div class="row form-confirmation">
            <div class="form-group col-sm-12 view-detail-customer">
              <label class="control-label"><?= $this->lang->line('lb_name') ?></label>
              <input type="text" name="CustomerName" class="form-control" readonly="">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6 view-detail-customer">
              <label class="control-label"><?= $this->lang->line('lb_email') ?></label>
              <input type="text" name="CustomerEmail" class="form-control" readonly="">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6 view-detail-customer">
              <label class="control-label"><?= $this->lang->line('lb_phone') ?></label>
              <input type="text" name="CustomerPhone" class="form-control" readonly="">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_transaction_no') ?></label>
              <input type="text" name="Code" class="form-control" readonly="">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_date') ?></label>
              <input type="text" name="TransferDate" class="form-control date">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_bank') ?></label>
              <select name="AccountBank" class="form-control">
                <!-- <option value="none">Choose Bank</option>
                <option value="BCA">BCA</option>
                <option value="BRI">BRI</option>
                <option value="mandiri">Mandiri</option> -->
                <option value="OCBC - NISP">OCBC - NISP</option>
              </select>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_bank_acount') ?></label>
              <input type="text" name="AccountName" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_account_no') ?></label>
              <input type="text" name="AccountNumber" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_transfer_amount') ?></label>
              <input type="text" name="TransferAmount" class="form-control duit">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label"><?= $this->lang->line('lb_remark') ?></label>
              <textarea name="Remark" class="form-control"></textarea>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <?= $this->lang->line('lb_noted') ?> :  <br/>Jika tidak ada konfirmasi 2x24 jam, hub : 08112199050
            </div>
          </div>
        </form>
        <div class="view_voucher" id="view_voucher">
          <div class="panel-group" id="accordion1">
            <div class="panel panel-primary" id="panel-voucher-module">
              <div class="panel-heading pointer" data-toggle="collapse" data-parent="#accordion1" href="#collapse1">
                <h4 class="panel-title">
                  <?= $this->lang->line('lb_module_device') ?>
                </h4>
              </div>
              <div id="collapse1" class="panel-collapse collapse in">
                <div class="panel-body padding-10">
                  <div class="row">
                    <div class="col-sm-12">
                      <table class="table table-hover table-striped" id="table-voucher-module">
                        <thead>
                          <tr>
                            <th><?= $this->lang->line('lb_no') ?></th>
                            <th><?= $this->lang->line('lb_voucher_code') ?></th>
                            <th><?= $this->lang->line('module') ?></th>
                            <th><?= $this->lang->line('lb_used_by_name') ?></th>
                            <th><?= $this->lang->line('lb_used_by_com') ?></th>
                            <th><?= $this->lang->line('lb_use_date') ?></th>
                            <th><?= $this->lang->line('lb_expire_date') ?></th>
                            <th>Status</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>                    
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="panel panel-primary" id="panel-voucher-devices">
              <div class="panel-heading pointer" data-toggle="collapse" data-parent="#accordion1" href="#collapse2">
                <h4 class="panel-title">
                  <?= $this->lang->line('lb_voucher_additional') ?>
                </h4>
              </div>
              <div id="collapse2" class="panel-collapse collapse in">
                <div class="panel-body padding-10">
                  <div class="row">
                    <div class="col-sm-12">
                      <table class="table table-hover table-striped" id="table-voucher-devices">
                        <thead>
                          <tr>
                            <th><?= $this->lang->line('lb_no') ?></th>
                            <th><?= $this->lang->line('lb_voucher_code') ?></th>
                            <th><?= $this->lang->line('lb_used_by_name') ?></th>
                            <th><?= $this->lang->line('lb_used_by_com') ?></th>
                            <th><?= $this->lang->line('lb_use_date') ?></th>
                            <th><?= $this->lang->line('lb_expire_date') ?></th>
                            <th>Status</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>                    
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button id="btnSave" onclick="save()" type="button" class="btn btn-primary save"><?= $this->lang->line('btn_save') ?></button>
          <button type="button" class="btn btn-danger btn-default margin-0 kotak" data-dismiss="modal"><?= $this->lang->line('btn_close') ?></button>
        </div>
      </div>
    </div>
  </div>
</div>