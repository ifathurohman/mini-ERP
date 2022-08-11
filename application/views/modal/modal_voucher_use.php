<!-- Modal -->
<div id="modal-voucher-use" class="modal modal-primary fade modal-fade-in-scale-up" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" style="padding-top: 20px;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
        <form id="form-voucher-use" autocomplete="off">
          <input type="hidden" name="crud">
          <input type="hidden" name="ID">
          <div class="row">
            <div class="form-group col-sm-12">
              <label class="control-label">Voucher Code</label>
              <input name="Voucher" id="Voucher" type="text" class="form-control">
              <span class="help-block"></span>
              <span class="red_txt">* for additional user</span>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <?= $this->main->general_button('general_save', "voucher_save()" ) ?>
          <?= $this->main->general_button('close') ?>
        </div>
      </div>
    </div>
  </div>
</div>