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
          <input type="hidden" name="id_user">
          <div id="list_company">
            <?php if($modul == "company"): ?>
            <div class="row">
              <div class="form-group col-sm-6">
                <label class="control-label"><?= $this->lang->line('lb_email') ?> <span class="wajib"></span></label>
                <input name="email" id="email" type="email" class="form-control disabled">
                <span class="help-block"></span>
              </div>
              <div class="form-group col-sm-6">
                <label class="control-label"><?= $this->lang->line('lb_company_name') ?> <span class="wajib"></span></label>
                <input name="Name" id="Name" type="Name" class="form-control disabled">
                <span class="help-block"></span>
              </div>
            </div>
            <?php else: ?>
            <div class="row">
              <div class="form-group col-sm-6">
                <label class="control-label"><?= $this->lang->line('lb_email') ?> <span class="wajib"></span></label>
                <input name="email" id="email" type="email" class="form-control disabled">
                <span class="help-block"></span>
              </div>
              <div class="form-group col-sm-6">
                <label class="control-label"><?= $this->lang->line('lb_password') ?> <span class="wajib"></span></label>
                <div class="input-group password">
                  <input name="password" id="password" type="password" class="form-control" autocomplete="new-password">
                  <span class="input-group-btn" onclick="show_password('password')">
                    <button class="btn btn-default reveal btn-pass" data-status="0" type="button"><i class="fa fa-eye"></i></button>
                  </span>
                </div>
                <span class="help-block"></span>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-sm-6">
                <label class="control-label"><?= $this->lang->line('lb_first_name') ?> <span class="wajib"></span></label>
                <input name="first_name" id="first_name" type="text" class="form-control">
                <span class="help-block"></span>
              </div>
              <div class="form-group col-sm-6">
                <label class="control-label"><?= $this->lang->line('lb_last_name') ?> <span class="wajib"></span></label>
                <input name="last_name" id="last_name" type="text" class="form-control">
                <span class="help-block"></span>
              </div>
            </div>
            <?php endif; ?>
            <div class="row">
              <div class="form-group col-sm-6">
                <label class="control-label"><?= $this->lang->line('lb_phone') ?></label>
                <input name="phone" id="phone" type="text" class="form-control angka">
                <span class="help-block"></span>
              </div>
              <?php if($this->session->app == "pipesys" && $modul != "company"): ?>
              <div class="form-group col-sm-6 v_voucher">
                <label class="control-label"><?= $this->lang->line('lb_voucher_additional') ?></label>
                <input name="VoucherAdditional" id="VoucherAdditional" type="text" class="form-control">
                <span class="help-block"></span>
                <span class="red_txt"><?= $this->lang->line('lb_for_additional') ?></span>
              </div>
              <?php endif; ?>
            </div>
              <?php if($this->session->app == "pipesys" && $modul != "company"): ?>
              <div class="row">
                <div class="form-group col-sm-12">
                  <label>Choose store and role where user can log in</label>                                      
                  <table class="table-store">
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
          <?php endif; ?>
          <?php if($modul == "company"): ?>
            <div class="row">
              <div class="form-group col-sm-6">
                <label class="control-label"><?= $this->lang->line('lb_fax') ?></label>
                <input name="Fax" id="Fax" type="text" class="form-control angka">
                <span class="help-block"></span>
              </div>
              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_address') ?></label>
                <textarea name="Address" class="form-control"></textarea>
                <span class="help-block"></span>
              </div>
              <div class="form-group col-sm-6">
                <label class="control-label"><?= $this->lang->line('lb_city') ?></label>
                <input name="City" id="City" type="text" class="form-control angka">
                <span class="help-block"></span>
              </div>
              <div class="form-group col-sm-6">
                <label class="control-label"><?= $this->lang->line('lb_province') ?></label>
                <input name="Province" id="Province" type="text" class="form-control angka">
                <span class="help-block"></span>
              </div>
              <div class="form-group col-sm-6">
                <label class="control-label"><?= $this->lang->line('lb_country') ?></label>
                <input name="Country" id="Country" type="text" class="form-control angka">
                <span class="help-block"></span>
              </div>
              <div class="form-group col-sm-6">
                <label class="control-label"><?= $this->lang->line('lb_pos_code') ?></label>
                <input name="PostalCode" id="PostalCode" type="text" class="form-control angka">
                <span class="help-block"></span>
              </div>
            </div>
          <?php endif; ?>
          </div>
          <?php if($modul == "company"): ?>
          <div class="row super_admin" style="display:none">
            <div class="form-group col-sm-12 Select_sales">
              <select name="super_admin" id="super_admin" >
                <option value="none">Select Company</option>
                <?php foreach ($list->result() as $d) { ?>
                  <option value="<?= $d->id_user ?>"><?= $d->nama ?></option>
                <?php } ?>
              </select>
              <span class="help-block" id="has-sales-error"></span>
            </div>
          </div>
          <?php endif; ?>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <?= $this->main->general_button('general_save') ?>
          <?= $this->main->button_action("action", array("edit","delete")); ?>
          <?= $this->main->general_button('close') ?>
          <button id="btnDelete" onclick="delete_super_admin()" type="button" class="btn btn-warning">Delete Privilege</button>
        </div>
      </div>
    </div>
  </div>
</div>