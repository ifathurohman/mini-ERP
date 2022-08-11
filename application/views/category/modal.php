<!-- Modal -->
<div id="modal" class="modal modal-primary fade modal-fade-in-scale-up" data-backdrop="static" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
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
          <input type="hidden" name="categoryid">
          <div class="row">
            <div class="form-group col-sm-12">
              <label class="control-label">Category Code <span class="wajib"></span></label>
              <input name="category_code" id="category_code" type="text" class="form-control" maxlength="10">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label">Category Name <span class="wajib"></span></label>
              <input name="category_name" id="category_name" type="text" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label">Level <span class="wajib"></span></label>
              <select name="level" placeholder="level" id="level" class="form-control"> 
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
              </select>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12 parent_category_v">
              <label class="control-label">Parent Category <span class="wajib"></span></label>
              <select name="parent_category" placeholder="Parent Category" id="parent_category" class="form-control category_option"> 
              </select>
              <span class="help-block"></span>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button id="btnSave" onclick="save()" type="button" class="btn btn-primary btnSave save">Save</button>
          <?= $this->main->button_action("action", array("edit","delete")); ?>
          <button type="button" class="btn btn-danger btn-default margin-0 kotak" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>