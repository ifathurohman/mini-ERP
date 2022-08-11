<!-- Modal -->
<div id="modal-pembayaran" class="modal modal-primary fade modal-fade-in-scale-up" data-backdrop="static" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" onclick="pay_close()">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
        <div class="vpay-list">
          <div class="row row-lg">
            <div class="col-lg-12">
              <div class="btn-group">
                <button type="button" class="btn btn-primary btn-outline" onclick="pay_tambah()"><i class="fa fa-plus"></i> Add Payment</button>              
              </div>
              <div class="btn-group pull-right">
                  <button class="btn btn-outline btn-default" onclick="filter_table_pay()" type="button"><i class="icon wb-reload"></i>Reload</button>
              </div>
            </div>
          </div>
          <br>
          <table id="pay_table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th>No</th>
                <th>Payment No</th>
                <th>Date</th>
                <th>Total</th>
                <th>Total Paid</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
        </div>
        <div class="vpay-form">
          <form id="form-pembayaran" autocomplete="off">
            <input type="hidden" name="sn_status">
            <input type="hidden" name="pay_page">
            <input type="hidden" name="pay_type">
            <div class="row">
              <div class="col-sm-6">
                <div class="row">
                  <div class="form-group col-sm-12">
                    <label class="control-label">Payment Code</label>
                    <input type="text" name="pay_no" class="form-control readonly" placeholder="" id="pay_no">
                    <span class="help-block"></span>
                  </div>
                  <div class="form-group col-sm-12">
                    <label class="control-label">Selling No</label>
                    <input type="text" name="pay_sellno" class="form-control readonly" placeholder="" id="pay_sellno">
                    <span class="help-block"></span>
                  </div>
                  <div class="form-group col-sm-12">
                    <label class="control-label">Customer</label>
                    <input type="text" name="pay_customer" class="form-control readonly" id="pay_customer">
                    <span class="help-block"></span>
                  </div>
                  <div class="form-group col-sm-12">
                    <label class="control-label">Date</label>
                    <input type="text" name="pay_date" id="pay_date" class="form-control date pointer" placeholder="yyyy-mm-dd" value="<?= date("Y-m-d") ?>">
                    <span class="help-block"></span>
                  </div>            
                </div>
              </div>
              <div class="col-sm-6">
                <div class="row">
                  <div class="form-group col-sm-12">
                    <label class="control-label">Payment Type</label>
                    <select name="pay_paymentType" id="pay_paymentType" class="form-control">
                      <option value="0">Cash</option>
                      <option value="1">Giro</option>
                      <option value="2">Transfer</option>
                    </select>
                    <span class="help-block"></span>
                  </div>
                  <div class="form-group col-sm-12 vpay-giro pay_no_cash">
                    <label class="control-label">Giro No <span class="wajib"></span></label>
                    <input type="text" name="pay_girono" class="form-control resetinput" placeholder="" id="pay_girono">
                    <span class="help-block"></span>
                  </div>
                  <div class="form-group col-sm-12 vpay-transfer pay_no_cash">
                    <label class="control-label">Acount No <span class="wajib"></span></label>
                    <input type="text" name="pay_accountno" class="form-control resetinput" placeholder="" id="pay_accountno">
                    <span class="help-block"></span>
                  </div>
                  <div class="form-group col-sm-12 pay_no_cash">
                    <label class="control-label">Bank Name <span class="wajib"></span></label>
                    <input type="text" name="pay_bankname" class="form-control resetinput" placeholder="" id="pay_bankname">
                    <span class="help-block"></span>
                  </div>
                  <div class="form-group col-sm-12 pay_no_cash">
                    <label class="control-label">Account Name <span class="wajib"></span></label>
                    <input type="text" name="pay_accountname" class="form-control resetinput" placeholder="" id="pay_accountname">
                    <span class="help-block"></span>
                  </div>
                  <div class="form-group col-sm-12">
                    <label class="control-label">Payment Method <span class="wajib"></span></label>
                    <select class="form-control selectpicker" data-live-search="true" name="pay_paymentmethod" id="pay_paymentmethod">
                      
                    </select>
                    <select class="form-control coa_select content-hide" data-select="active" data-level="4"></select>
                    <span class="help-block"></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-sm-6">
                <label class="control-label">Total AR Invoice</label>
                <input name="pay_total_ar" id="pay_total_ar" type="text" class="form-control readonly duit">
                <span class="help-block"></span>
              </div>
              <div class="form-group col-sm-6">
                <label class="control-label">Unpayment</label>
                <input name="pay_unpayment" id="pay_unpayment" type="text" class="form-control readonly duit ">
                <span class="help-block"></span>
              </div>
              <div class="form-group col-sm-6">
                <label class="control-label">Payment Cost <span class="wajib"></span></label>
                <input name="pay_payment" id="pay_payment" type="text" class="form-control duit resetinput" onkeyup="SumPayment()">
                <span class="help-block"></span>
              </div>
              <div class="form-group col-sm-6">
                <label class="control-label">Total Payment</label>
                <input name="pay_total" id="pay_total" type="text" class="form-control readonly duit resetinput">
                <span class="help-block"></span>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button type="button" class="btn btn-outline btn-default margin-0 kotak" onclick="pay_close()">Close</button>   
          <button type="button" class="btn btn-outline btn-default margin-0 kotak paybtnsave" onclick="pay_back()">Cancel</button>   
          <button id="btnSave" onclick="pay_save()" type="button" class="btn btn-primary save paybtnsave">Save</button>
        </div>
      </div>
    </div>
  </div>
</div>