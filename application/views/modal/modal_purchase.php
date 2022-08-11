<!-- Modal -->
<div id="modal-purchase" class="modal modal-primary fade modal-fade-in-scale-up" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" style="padding-top: 20px;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
        <table class="table-purchase table table-stiped datatable" width="100%">
          <thead>
            <tr>
              <th><?= $this->lang->line('lb_purchaseno') ?></th>
              <th><?= $this->lang->line('lb_vendor_name') ?></th>
              <th><?= $this->lang->line('lb_date') ?></th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <?= $this->main->general_button('close') ?>
        </div>
      </div>
    </div>
  </div>
</div>