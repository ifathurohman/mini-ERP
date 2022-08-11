<!-- Modal -->
<div id="modal-vendor-price" class="modal modal-primary fade modal-fade-in-scale-up" data-backdrop="static" style="margin-top: 20px;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
        <form id="form-vendor-price" autocomplete="off">
          <input type="hidden" name="vendor_price_page_type">
          <input type="hidden" name="vendor_price_id">
          <input type="hidden" name="vendor_price_crud">
          <div class="row div-list">
            <div class="form-group col-sm-12">
              <div class="col-sm-12 mb-10">
                <div class="btn-group">
                  <button type="button" class="btn btn-primary btn-outline btn-add" onclick="add_vendor_price()">Add New</button>
                  <button type="button" class="btn btn-primary btn-outline btn-edit" onclick="edit_vendor_price()">Edit</button>
                  <button type="button" class="btn btn-primary btn-outline btn-cancel" onclick="cancel_vendor_price()">Cancel</button>
                </div>
              </div>
              
              <table id="table-vendor-price" class="table table-striped input-table3" width="100%" style="margin-top: 15px;margin-bottom: 0px;">
                <thead>
                  <tr>
                    <th><div style="width: 50px !important" class="vendor_price_check"><input type="checkbox" name="vendor_price_checkall"></div></th>
                    <th>No</th>
                    <th>Group Name</th>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Unit</th>
                    <th>Price Type</th>
                    <th>Standart Price</th>
                    <th>Product Price</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button type="button" onclick="save_vendor_price()" class="btn btn-primary btn-default margin-0 kotak save">Save</button>
          <button type="button" onclick="back_vendor_price()" class="btn btn-danger btn-default margin-0 kotak btn-back">Back</button>
          <button type="button" class="btn btn-danger btn-default margin-0 kotak" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>