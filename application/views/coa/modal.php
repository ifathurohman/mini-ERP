<!-- Modal -->
<div id="modal" class="modal modal-primary fade modal-fade-in-scale-up" data-backdrop="static">
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
          <input name="COAID" id="COAID" type="hidden" class="form-control">
          <input type="hidden" name="crud" class="form-control">
          <div class="row">
            <div class="form-group col-sm-12">
              <label class="control-label">Code <span class="wajib"></span></label>
              <input name="Code" id="Code" type="text" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label">Name <span class="wajib"></span></label>
              <input name="Name" id="Name" type="text" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12 content-hide">
              <label class="control-label">Payment Type <span class="wajib"></span></label>
              <select class="form-control" name="PaymentType" id="PaymentType">
                <option value="0">Cash</option>
                <option value="1">Giro</option>
                <option value="2">Transfer</option>
              </select>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label">Level <span class="wajib"></span></label>
              <select class="form-control" name="Level" id="Level">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
              </select>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12 vparent">
              <label class="control-label">Parent <span class="wajib"></span></label>
              <select class="form-control coa_select" data-live-search="true" data-select="active" data-level="all" style="display: none">
              </select>
              <select class="form-control coa_select_level" name="ParentID" id="ParentID"></select>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label">Remark</label>
              <textarea name="Remark" id="Remark" class="form-control txtRemark" rows="5"></textarea>
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