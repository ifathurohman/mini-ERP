<!-- Modal -->
<div id="modal" class="modal modal-primary fade modal-fade-in-scale-up" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
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
          <input type="hidden" name="unitid">
          <div class="row">
            <div class="form-group col-sm-12">
              <label class="control-label">Unit Name</label>
              <input name="unit_name" id="unit_name" type="text" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label">Conversion</label>
              <input name="conversion" id="conversion" type="text" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label">Type</label>
              <select name="type" placeholder="type" id="type" class="form-control"> 
                  <option value="berat">berat</option>
                  <option value="panjang">panjang</option>
                  <option value="volume">volume</option>
              </select>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12 parent_category_v">
              <label class="control-label">Remark</label>
              <textarea name="remark" id="remark" class="form-control txtRemark"></textarea>
              <span class="help-block"></span>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
         <div class="btn-group">
          <button id="btnSave" onclick="save()" type="button" class="btn btn-primary save">Save</button>
          <?= $this->main->button_action("action", array("edit","delete")); ?>
          <button type="button" class="btn btn-danger btn-default margin-0 kotak" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>