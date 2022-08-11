<!-- Modal -->
<div id="modal" class="modal modal-primary fade modal-fade-in-scale-up" data-backdrop="static" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
  <div class="modal-dialog modal-lg">
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
          <div class="row">
            <div class="form-group col-sm-2">
              <div class="radio-custom radio-primary">
                <input type="radio" id="Type1" name="Type" value="1" checked>
                <label for="Type1"><?= $this->lang->line('lb_cash') ?></label>
              </div>
            </div>
            <div class="form-group col-sm-2">
              <div class="radio-custom radio-primary">
                <input type="radio" id="Type2" name="Type" value="2">
                <label for="Type2"><?= $this->lang->line('lb_bank') ?></label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_transaction_no') ?></label>
              <input name="Code" id="Code" type="text" class="form-control readonly" readonly>
            </div>

            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_date') ?> <span class="wajib"></span></label>
              <input name="Date" id="Date" type="text" value="<?= date("Y-m-d") ?>" class="form-control date cursor readonly" readonly>
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row">
          	<div class="form-group col-sm-12 vstore">
              <table class="table-detail table table-striped table-td-padding-0 input-table2" style="margin-top: 15px;margin-bottom: 0px;">
                <thead>
                  <tr>
                    <th colspan="3"><?= $this->lang->line('lb_coa_code') ?></th>
                    <th><?= $this->lang->line('lb_coa_name') ?></th>
                    <th><?= $this->lang->line('lb_remark') ?></th>
                    <th><?= $this->lang->line('lb_debit') ?></th>
                    <th><?= $this->lang->line('lb_credit') ?></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              <a href="javascript:void(0)" onclick="add_new_row()" class="link_add_row">+ <?= $this->lang->line('lb_add_column') ?></a>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="row">
              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_remarks') ?></label>
                <textarea name="Remark" id="Remark" type="text" class="form-control" style="height: 100px"></textarea>
                <span class="help-block"></span>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="row">
              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_total_debit') ?></label>
                <input type="text" name="TotalDebit" id="TotalDebit" class="form-control duit" readonly>
                <span class="help-block"></span>
              </div>

              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_total_credit') ?></label>
                <input type="text" name="TotalCredit" id="TotalCredit" class="form-control duit" readonly>
                <span class="help-block"></span>
              </div>
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