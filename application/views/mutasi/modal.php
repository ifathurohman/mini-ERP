<!-- Modal -->
<div id="modal" class="modal modal-primary fade modal-fade-in-scale-up" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
  <div class="modal-dialog" style="width:70%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
        <form id="form" autocomplete="off">
          <input type="hidden" name="unitid">
          <div class="row">
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_transaction_no') ?></label>
              <input name="mutation_code" id="mutation_code" type="text" class="form-control disabled">
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_date') ?> <span class="wajib"></span></label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="icon wb-calendar" aria-hidden="true"></i>
                </span>
                <input type="text" name="date" class="form-control date cursor " value="<?= date("Y-m-d"); ?>" readonly>
              </div>
            </div>
            <div class="form-group col-sm-6 content-hide">
              <label class="control-label"><?= $this->lang->line('lb_mutation_type') ?> <span class="wajib"></span></label>
              <select name="mutation_type" class="form-control">
                <option value="1"><?= $this->lang->line('lb_store_to_store') ?></option>
              </select>
            </div>
            <div class="form-group col-sm-6 from_v">
              <label class="control-label"><?= $this->lang->line('lb_from') ?> <span class="wajib"></span></label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="icon fa-building" aria-hidden="true"></i>
                </span>
                <input name="BranchName1" id="BranchName1" placeholder="<?= $this->lang->line('lb_store_select') ?>" type="text" class="form-control pointer addbranchmodal1 autocomplete_branch1-name readonly">
                <input name="from_name" id="from_name" type="text" class="form-control pointer addbranchmodal1 autocomplete_branch1 readonly content-hide">
              </div>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6 to_v">
              <label class="control-label"><?= $this->lang->line('lb_to') ?> <span class="wajib"></span></label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="icon fa-building" aria-hidden="true"></i>
                </span>
                <input name="BranchName2" id="BranchName2" placeholder="<?= $this->lang->line('lb_store_select') ?>" type="text" class="form-control pointer addbranchmodal2 autocomplete_branch2-name readonly" >
                <input name="to_name" id="to_name" type="text" class="form-control pointer addbranchmodal2 autocomplete_branch2 readonly content-hide" >
              </div>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <table class="table-add-product table table-striped input-table" style="margin-top: 15px;margin-bottom: 0px;">
                <thead>
                  <tr> 
                    <th class="th-code"><?= $this->lang->line('lb_product_code') ?></th>
                    <th><?= $this->lang->line('lb_product_name') ?></th>
                    <th width="70px"><?= $this->lang->line('lb_qty2') ?></th>
                    <th width="70px"><?= $this->lang->line('lb_unit') ?></th>
                    <th><?= $this->lang->line('price') ?></th>
                    <th class="th-sub"><?= $this->lang->line('lb_remark') ?></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              <a href="javascript:void(0)" onclick="add_new_row()" class="link_add_row">+ <?= $this->lang->line('lb_add_column') ?></a>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_remarks') ?></label>
              <textarea name="mutation_remark" id="mutation_remark" type="text" class="form-control"></textarea>
              <span class="help-block"></span>
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