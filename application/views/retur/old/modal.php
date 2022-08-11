<!-- Modal -->
<div id="modal" class="modal-return modal modal-primary fade modal-fade-in-scale-up" data-backdrop="static" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
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
          <input type="hidden" name="type">
          <div class="row">
            <div class="form-group col-sm-6">
              <label class="control-label">Retur No.</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="icon wb-order" aria-hidden="true"></i>
                </span>
                <input type="text" name="returno" class="form-control disabled readonly" placeholder="" id="returno">
              </div>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label">Date</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="icon wb-calendar" aria-hidden="true"></i>
                </span>
                <input type="text" name="date" class="form-control date disabled readonly" placeholder="" id="date" value="<?= date("Y-m-d"); ?>">
              </div>
            </div>
            <div class="form-group col-sm-6 v_sell">
              <label class="control-label">Sales No.</label>
              <div class="input-group">
                <span class="input-group-addon add_modal_sellno" style="cursor: pointer;">
                  <i class="icon wb-search" aria-hidden="true"></i>
                </span>
                <input type="text" name="sellno" class="form-control" placeholder="" id="sellno">
              </div>
            </div>
            <div class="form-group col-sm-6 v_purchase">
              <label class="control-label">Good Receipt Code</label>
              <input type="text" name="receiveno" class="form-control pointer addmodalreceive readonly" placeholder="Select Good Receipt" id="receiveno">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label v_sell">Customer Name</label>
              <label class="control-label v_purchase">Vendor Name</label>
              <input type="text" name="vendorname" class="form-control disabled readonly" placeholder="" id="vendorname" >
              <input type="hidden" name="vendorid">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <table class="table-add-product table table-striped input-table" style="margin-top: 15px;margin-bottom: 0px;">
                <thead>
                  <tr> 
                    <th width="40px"><input type="checkbox" name="cekcheckboxall" class="cekcheckboxall"></th>
                    <th class="th-code">Product Code</th>
                    <th>Product Name</th>
                    <th>Qty</th>
                    <th>Type</th>
                    <th>Unit</th>
                    <th>Konv.</th>
                    <th>Price</th>
                    <th class="th-sub">Remark</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              <!-- <a href="javascript:void(0)" onclick="add_new_row()" class="link_add_row">+ Tambah Kolom</a> -->
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button type="button" class="btn btn-outline btn-default margin-0 kotak" data-dismiss="modal">Close</button>     
          <button id="btnSave" onclick="save()" type="button" class="btn btn-primary save">Save</button>
        </div>
      </div>
    </div>
  </div>
</div>