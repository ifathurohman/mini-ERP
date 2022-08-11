<!-- Modal -->
<div id="modal-template" class="modal modal-primary fade modal-fade-in-scale-up" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
  <div class="modal-dialog width-80per">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
       <form id="form-modal-template">
         <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
                <label class="control-label">Template <span class="wajib"></span></label>
                <select name="Template" id="Template" class="form-control template_select" data-type="<?= $modul ?>" data-select="active" onchange="change_template(this)">
                  <option value="0">Select Template</option>
                </select>
                <input type="checkbox" name="default_template" id="default_template" value="1"> <label for="default_template">Default Template</label>
                <span class="help-block"></span>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="template-image text-center"></div>
          </div>
         </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button type="button" class="btn btn-primary btn-default margin-0 kotak btn-next">Next</button>
          <button type="button" class="btn btn-danger btn-default margin-0 kotak" onclick="close_modal_template()">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>