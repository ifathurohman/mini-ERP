<!-- Modal -->
<div id="modal" class="modal modal-primary fade modal-fade-in-scale-up" data-backdrop="static" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
  <div class="modal-dialog width-90per">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
        <form id="form" autocomplete="off">
          <input type="hidden" name="correctionid">
          <div class="row">
            
            <div class="form-group col-sm-3">
              <label class="control-label"><?= $this->lang->line('lb_transaction_no') ?></label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="icon wb-order" aria-hidden="true"></i>
                </span>
                <input name="correctionno" id="correctionno" type="text" class="form-control disabled" disabled="">
              </div>
            </div>
            <div class="form-group col-sm-4">
              <label class="control-label"><?= $this->lang->line('lb_store') ?> <span class="wajib"></span></label>
              <div class="input-group">
                <span class="input-group-addon">
                 <i class="fa fa-building" aria-hidden="true"></i>
                </span>
                <input id="BranchName" type="text" class="form-control pointer readonly autocomplete_branch-name" readonly="readonly" placeholder="<?= $this->lang->line('lb_store_select') ?>" onclick="branch_modal('','.autocomplete_branch')">
                <input name="BranchID" id="BranchID" type="text" placeholder="<?= $this->lang->line('lb_store_select') ?>" class="form-control pointer readonly autocomplete_branch content-hide" data-select="active" onclick="branch_modal('','.autocomplete_branch')">
                <span></span>
              </div>
            </div>
            <div class="form-group col-sm-3">
              <label class="control-label"><?= $this->lang->line('lb_date') ?> <span class="wajib"></span></label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="icon wb-calendar" aria-hidden="true"></i>
                </span>
                <input type="text" name="date" class="form-control date cursor " value="<?= date("Y-m-d"); ?>" readonly>
              </div>
            </div>
            <div class="form-group col-sm-12">
              <table class="table-add-product table table-bordered table-striped table-td-padding-0 input-table2" style="margin-top: 15px;margin-bottom: 0px;">
                <thead>
                  <tr> 
                    <th colspan="3"><?= $this->lang->line('lb_product_code') ?></th>
                    <th><?= $this->lang->line('lb_product_name') ?></th>
                    <th><?= $this->lang->line('price') ?></th>
                    <th><?= $this->lang->line('lb_stock_opname_qty') ?></th>
                    <th><?= $this->lang->line('lb_stock_qty') ?></th>
                    <th><?= $this->lang->line('lb_correction_qty') ?></th>
                    <th><?= $this->lang->line('lb_unit') ?></th>
                    <th colspan="2"><?= $this->lang->line('lb_remark') ?></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              <hr style="margin-top: 5px;margin-bottom: 10px;">
              <div class="vaddrow"><a href="javascript:void(0)" onclick="add_new_row()" class="link_add_row">+ <?= $this->lang->line('lb_add_column') ?></a></div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">    
          <?= $this->main->general_button('general_save'); ?>
          <?= $this->main->general_button('close'); ?>
        </div>
      </div>
    </div>
  </div>
</div>