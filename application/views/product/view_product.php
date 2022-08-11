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
           <div class="form-group ">
              <div class="col-sm-12">
                  <label class="control-label">Image</label>
                    <input type="file" name="gambar" id="gambar" class="dropify" />
                  <span class="help-block"></span>
                  <span>File format (PNG, JPG, JPEG), max size (2MB)</span>
              </div>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label">Product Code</label>
              <input name="product_code" id="product_code" type="text" class="form-control">
              <span style="color:red">* If this column blank system automatic generate</span>
              <span class="help-block"></span>
            </div>
            <!-- <div class="form-group col-sm-12">
                <label class="control-label block">Product Type</label>
                <div class="radio-custom radio-primary radio-inline">
                  <input type="radio" id="general" name="product_type" value="general" checked="">
                  <label for="general">General</label>
                </div>
                <div class="radio-custom radio-primary radio-inline">
                  <input type="radio" id="unique" name="product_type" value="unique">
                  <label for="unique">Unique</label>
                </div>
                <div class="radio-custom radio-primary radio-inline">
                  <input type="radio" id="serial" name="product_type" value="serial">
                  <label for="serial">Serial Number</label>
                </div>
                <span class="help-block"></span>
            </div> -->
            <div class="form-group col-sm-12">
              <label class="control-label block">Product Type</label>
              <div class="checkbox-custom checkbox-primary">
                <input type="checkbox" class="autocomplete_inventory" id="inventory" name="inventory" value="inventory" checked="">
                <label for="inventory">Inventory</label>
                <span class="fa fa-exclamation-circle" data-toggle="popover" data-html="true" data-popover-type="singleton" data-content="This is allow system to monitor inventroy product stock."></span>
              </div>
              <div class="checkbox-custom checkbox-primary">
                <input type="checkbox" class="autocomplete_sales" id="sales" name="sales" value="1">
                <label for="inventory">Sales</label>
                <span class="fa fa-exclamation-circle" data-toggle="popover" data-html="true" data-popover-type="singleton" data-content="This is allow system to sell this item."></span>
              </div>
              <!-- <div class="checkbox-custom checkbox-primary checkbox-inline">
              <input type="checkbox" class="autocomplete_product_set" id="product_set" name="product_set" value="2">
              <label for="inventory">Product Set</label>
              <span class="fa fa-exclamation-circle" data-toggle="popover" data-html="true" title="Notice" data-popover-type="singleton" data-content="This is allow system to sell group of items Set."></span>
              </div> -->
              <div class="checkbox-custom checkbox-primary">
                <input type="checkbox" id="unique" name="product_type" value="unique">
                <label for="inventory">Serial</label>
                <span class="fa fa-exclamation-circle" data-toggle="popover" data-html="true" data-popover-type="singleton" data-content="This is allow system to ask serial of item."></span>
              </div>
            </div>
            <div class="form-group col-sm-12 serial_format_v">
              <label class="control-label">Serial Number Format <i class="icon wb-settings" style="cursor:pointer;" onclick="modal_notransaction('serial_format')" data-toggle="tooltip"  data-placement="left" data-html="true" title="Serial Number akan dibuat otomatis oleh sistem. Klik disini untuk mengatur format serial number"></i></label>
              <input name="serial_format" id="serial_format" type="text" class="form-control serial_format" placeholder="AUTOMATIC">
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
            <div class="form-group col-sm-6 inventory">
              <label class="control-label">Min. Qty</label>
              <input name="min_qty" id="min_qty" type="text" class="form-control angka">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6 inventory">
              <label class="control-label">Qty</label>
              <input name="qty" id="qty" type="text" class="form-control" disabled="">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6 inventory">
              <label class="control-label">Unit</label>
              <select name="unit" id="unit" type="text" class="form-control unit_option"></select>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6 inventory">
              <label class="control-label">Konv.</label>
              <input name="konv" id="konv" type="text" class="form-control conversion" disabled="">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label">Selling Price</label>
              <input name="selling_price" id="selling_price" type="text" class="form-control duit">
              <span class="help-block"></span>
              <span style="color:red">* Price before tax</span>
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

<link rel="stylesheet" href="<?= base_url('aset/css/dropify.min.css'); ?>">
<script src="<?= base_url('aset/js/dropify.min.js'); ?>"></script>