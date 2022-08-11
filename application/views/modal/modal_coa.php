<!-- Modal -->
<div id="modal-coa" class="modal modal-primary fade modal-fade-in-scale-up" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" style="margin-top: 20px;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
        <form id="form-coa" autocomplete="off">
          <input type="hidden" name="page">
          <div class="row">
            <div class="form-group col-sm-12">
              <table class="table-coa table table-striped input-table" style="margin-top: 15px;margin-bottom: 0px;" width="100%">
                <thead>
                  <tr> 
                    <th><?= $this->lang->line('lb_code'); ?></th>
                    <th><?= $this->lang->line('lb_name'); ?></th>
                    <th><?= $this->lang->line('lb_level'); ?></th>
                    <th><?= $this->lang->line('lb_coa_parent'); ?></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <?= $this->main->general_button('close') ?>
          <button type="button" class="btn btn-outline btn-default margin-0 kotak btn-without" data-classnya="" onclick="without_coa(this)"><?= $this->lang->line('lb_coa_without'); ?></button>    
        </div>
      </div>
    </div>
  </div>
</div>