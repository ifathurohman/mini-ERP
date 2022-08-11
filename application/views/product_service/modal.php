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
          <input type="hidden" name="productid">
          <div class="row">
            <div class="form-group col-sm-12">
              <label class="control-label">Product Code</label>
              <input name="product_code" id="product_code" type="text" class="form-control">
              <span style="color:red">* If this column blank system automatic generate</span>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label">Product Name</label>
              <input name="product_name" id="product_name" type="text" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label">Product Category</label>
              <select name="product_category" placeholder="Product Category" id="product_category" class="form-control category_option"> 
              </select>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label">Selling Price</label>
              <input name="selling_price" id="selling_price" type="text" class="form-control duit">
              <span class="help-block"></span>
              <span style="color:red">*Price before tax</span>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button type="button" class="btn btn-outline btn-default margin-0 kotak" data-dismiss="modal">Close</button>     
          <button id="btnSave" onclick="save()" type="button" class="btn btn-primary">Save</button>
        </div>
      </div>
    </div>
  </div>
</div>