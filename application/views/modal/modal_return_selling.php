<!-- Modal -->
<div id="modal-return" class="modal modal-primary fade modal-fade-in-scale-up" data-backdrop="static" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" onclick="return_close()">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
        <div class="vreturn-list">
          <div class="row row-lg">
            <div class="col-lg-12">
              <div class="btn-group">
                <button type="button" class="btn btn-primary btn-outline" onclick="return_tambah()"><i class="fa fa-plus"></i> Add Return</button>              
              </div>
              <div class="btn-group pull-right">
                  <button class="btn btn-outline btn-default" onclick="filter_table_return()" type="button"><i class="icon wb-reload"></i>Reload</button>
              </div>
            </div>
          </div>
          <br>
          <table id="return_table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th>No</th>
                <th>Return No</th>
                <th>Date</th>
                <th class="vreturn-customer">Vendor</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
        </div>
        <div class="vreturn-form">
          <form id="form-return" autocomplete="off">
            <input type="hidden" name="return_pay">
            <input type="hidden" name="return_page">
            <input type="hidden" name="return_type">
            <div class="row">
              <div class="form-group col-sm-6">
                <label class="control-label">Retur No.</label>
                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="icon wb-order" aria-hidden="true"></i>
                  </span>
                  <input type="text" name="returno" class="form-control disabled readonly" placeholder="" id="returno" maxlength="50" disabled="disabled" readonly="readonly">
                </div>
              </div>
              <div class="form-group col-sm-6">
                <label class="control-label">Date</label>
                <div class="input-group">
                  <span class="input-group-addon">
                    <i class="icon wb-calendar" aria-hidden="true"></i>
                  </span>
                  <input type="text" name="returndate" class="form-control date readonly pointer" placeholder="" id="returndate" value="<?= date("Y-m-d") ?>" maxlength="50" readonly>
                </div>
              </div>
              <div class="form-group col-sm-6">
                <label class="control-label transaction"></label>
                <input type="text" name="return_sellno" class="form-control readonly" placeholder="" id="return_sellno" maxlength="50" readonly="readonly">
                <span class="help-block"></span>
              </div>
              <div class="form-group col-sm-6">
                <label class="control-label vreturn-customer"></label>
                <input type="text" name="return_customer" class="form-control readonly" placeholder="" id="return_customer" maxlength="50" readonly="readonly">
                <span class="help-block"></span>
              </div>
              <div class="form-group col-sm-6">
                <label class="control-label">Remark</label>
                <textarea name="return_remark" id="return_remark" type="text" class="form-control" style="height: 50px" maxlength="50"></textarea>
                <span class="help-block"></span>
              </div>
              <div class="form-group col-sm-12">
              <table class="table-return-product table table-striped input-table table-td-padding-0" style="margin-top: 15px;margin-bottom: 0px;">
                <thead>
                  <tr>
                    <th width="20px"></th>
                    <th width="60px"><input type="checkbox" name="returncheckboxall" class="returncheckboxall"></th>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Konv.</th>
                    <th>Price</th>
                    <th>Remark</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
              <!-- <a href="javascript:void(0)" onclick="add_new_row()" class="link_add_row">+ Tambah Kolom</a> -->
            </div>
            </div>
          </form>
        </div>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button type="button" class="btn btn-outline btn-default margin-0 kotak" onclick="return_close()">Close</button>   
          <button type="button" class="btn btn-outline btn-default margin-0 kotak returnbtnsave" onclick="return_back()">Cancel</button>   
          <button id="btnSave" onclick="return_save()" type="button" class="btn btn-primary save returnbtnsave">Save</button>
        </div>
      </div>
    </div>
  </div>
</div>