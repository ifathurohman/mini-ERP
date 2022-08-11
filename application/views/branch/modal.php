<!-- Modal -->
<div id="modal" class="modal modal-primary fade modal-fade-in-scale-up" data-backdrop="static" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" tabindex="-1">
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
          <input type="hidden" name="crud">
          <input type="hidden" name="BranchID">
          <div class="row">
            <div class="form-group col-sm-12">
              <label class="control-label"><?= $this->lang->line('lb_store_name'); ?> <span class="wajib"></span></label>
              <input name="Name" id="Name" type="text" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label"><?= $this->lang->line('lb_address'); ?></label>
              <input name="Address" id="Address" type="text" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_city'); ?></label>
              <input name="City" id="City" type="text" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_province'); ?></label>
              <input name="Province" id="Province" type="text" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_country'); ?></label>
              <input name="Country" id="Country" type="text" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_pos_code'); ?></label>
              <input name="Postal" id="Postal" type="text" class="form-control angka">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_phone'); ?></label>
              <input name="Phone" id="Phone" type="text" class="form-control angka">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_fax'); ?></label>
              <input name="Fax" id="Fax" type="text" class="form-control angka">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
            <label class="control-label"><?= $this->lang->line('lb_location_marker'); ?></label>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label">Latitude</label>
              <input name="Lat" id="Lat" type="text" class="form-control disabled">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label">Longitude</label>
              <input name="Lng" id="Lng" type="text" class="form-control disabled">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <div id="map" style="height: 500px;width: 100%"></div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <?= $this->main->general_button('general_save') ?>
          <?= $this->main->button_action("action", array("edit","delete")); ?>
          <?= $this->main->general_button('close') ?>
        </div>
      </div>
    </div>
  </div>
</div>