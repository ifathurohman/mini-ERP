<!-- Modal -->
<div id="modal-vendor" class="modal modal-primary fade modal-fade-in-scale-up" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" style="padding-top: 20px;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
        <table class="table-vendor table table-stiped datatable" width="100%">
          <thead>
            <tr>
              <?php if($this->session->app == "pipesys"): ?>
                <th><?= $this->lang->line('lb_code') ?></th>
              <?php endif; ?>
              <th style="min-width: 150px;"><?= $this->lang->line('lb_name') ?></th>
              <?php if($this->session->app == "salespro"): ?>
              <th><?= $this->lang->line('lb_address') ?></th>
              <?php endif; ?>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <?= $this->main->general_button('close'); ?>
          <button type="button" class="btn btn-outline btn-default margin-0 kotak btn-without" data-classnya="" onclick="without_vendor(this)"><?= $this->lang->line('lb_vendor_without') ?></button> 
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="modal-vendor-address" class="modal modal-primary fade modal-fade-in-scale-up" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" style="padding-top: 20px;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
        <table class="table-vendor-address table table-stiped datatable">
          <thead>
            <tr>
              <th><?= $this->lang->line('lb_address') ?></th>
              <th><?= $this->lang->line('lb_city') ?></th>
              <th><?= $this->lang->line('lb_province') ?></th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <?= $this->main->general_button('close'); ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="modal-vendor-add" class="modal modal-primary fade modal-fade-in-scale-up" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" style="padding-top: 20px;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
        <form id="form-vendor-add" autocomplete="off">
          <input type="hidden" name="classnya">
          <input type="hidden" name="type">
          <input type="hidden" name="page_address">
          <div class="row">
            <div class="form-group col-sm-12">
              <label class="control-label">Business Patner Code</label>
              <input name="code" id="code" type="text" class="form-control" placeholder="Auto">
              <span class="help-block"></span>
              <span style="color:red">* Left blank, Business Patner Code will be generate automatically by system</span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label">Name</label>
              <input name="name" id="name" type="text" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="address_v">              
            </div>
            <div class="form-group col-sm-12">
                <a href="javascript:;" onclick="add_address()">+ Add new address</a>
            </div>
            <div class="contact_v">
            </div>
            <div class="form-group col-sm-12">
              <a href="javascript:;" onclick="add_contact()">+ Add new contact</a>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label">NPWP</label>
              <input name="npwp" id="npwp" type="text" class="form-control angka">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label">TOP</label>
              <input name="top" id="top" type="text" class="form-control angka">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label">Group Name</label>
              <input name="GroupName" id="GroupName" type="text" onkeyup="keyup_vendor_price('0',this,'vendor')" class="uppercase autocomplete_vendor_price form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label">Remark</label>
              <input name="remark" id="remark" type="text" class="form-control">
              <span class="help-block"></span>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <?= $this->main->general_button('general_save','vendor_save()') ?>
          <?= $this->main->general_button('close'); ?>
        </div>
      </div>
    </div>
  </div>
</div>