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
            <div class="vlist_voucher"></div>
            <div class="form-group col-sm-12" style="padding-bottom: 25px;">
              <a href="javascript:void(0)" onclick="add_row()" class="pull-right"><?= $this->lang->line('lb_add_form_voucher') ?></a>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <?= $this->main->general_button('general_save') ?>
          <?= $this->main->general_button('close') ?>
        </div>
      </div>
    </div>
  </div>
</div>