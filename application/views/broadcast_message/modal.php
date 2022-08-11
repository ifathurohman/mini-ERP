<!-- Modal -->
<div id="modal" class="modal modal-primary fade modal-fade-in-scale-up">
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
          <input name="TransactionRouteID" id="TransactionRouteID" type="hidden" class="form-control">
          <div class="row">
            <div class="form-group col-sm-12">
              <label class="control-label">Subject</label>
              <input name="Subject" id="Subject" type="text" class="form-control" autocomplete="off">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label">Sales</label>
              <div class="checkbox-custom checkbox-primary">
                <input type="checkbox" id="Sales" name="Sales" value="1">
                <label for="Sales">All</label>
                <span class="help-block"></span>
              </div>
            </div>
            <div class="form-group col-sm-12 Select_sales">
              <select name="Select_sales[]" id="Select_sales" class="multiselect" multiple>
                <?php foreach ($sales as $d) { ?>
                  <option value="<?= $d->branchid ?>"><?= $d->name ?></option>";
                <?php } ?>
              </select>
              <span class="help-block" id="has-sales-error"></span>
            </div>
            <div class="form-group col-sm-12">
              <div class="col-sm-12 Select_sales2">
                
              </div>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label">Message</label>
              <textarea class="form-control" name="Message" id="Message" style="resize: none;height:150px"></textarea>
              <span class="help-block"></span>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button type="button" class="btn btn-default btn-outline margin-0 kotak" data-dismiss="modal">Close</button>
          <button id="btnSave" onclick="save()" type="button" class="btn btn-primary">Save</button>
        </div>
      </div>
    </div>
  </div>
</div>