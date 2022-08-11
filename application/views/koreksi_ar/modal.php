<!-- Modal -->
<div id="modal" class="modal modal-primary fade modal-fade-in-scale-up" data-backdrop="static" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
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
          	<input type="hidden" name="BalanceID" class="data-ID">
          	<input type="hidden" name="crud">
          	<div class="row vdelivery">
            	<div class="form-group col-sm-2">
             	 	<div class="radio-custom radio-primary">
                	<input type="radio" id="ckOrder2" name="ckOrder" value="1" checked>
                	<label for="ckOrder2"><?= $this->lang->line('lb_store') ?></label>
              	</div>
            	</div>
	            <div class="form-group col-sm-3">
	              	<div class="radio-custom radio-primary">
	                	<input type="radio" id="ckOrder3" name="ckOrder" value="2">
	                	<label for="ckOrder3"><?= $this->lang->line('lb_selling1') ?></label>
	              	</div>
	            </div>
          	</div>
          	<div class="row">
	            <div class="form-group col-sm-6">
	              <label class="control-label"><?= $this->lang->line('lb_transaction_no') ?></label>
	              <div class="input-group">
	                <span class="input-group-addon">
	                  <i class="icon wb-order" aria-hidden="true"></i>
	                </span>
	                <input type="text" name="balanceno" class="form-control readonly" placeholder="" id="balanceno">
	              </div>
	            </div>
	            <div class="form-group col-sm-6">
	              <label class="control-label"><?= $this->lang->line('lb_date') ?></label>
	              <div class="input-group">
	                <span class="input-group-addon">
	                  <i class="icon wb-calendar" aria-hidden="true"></i>
	                </span>
	                <input type="text" name="date" class="form-control date cursor readonly" placeholder="" id="date" value="<?= date("Y-m-d"); ?>">
	              </div>
	            </div>
        	</div>
	        <div class="row">
	            <div class="form-group col-sm-6">
	                <label class="control-label block"><?= $this->lang->line('lb_correction_type') ?></label>
	                <div class="radio-custom radio-primary radio-inline">
	                  <input type="radio" id="debit" name="BalanceType" value="1" checked="">
	                  <label for="debit"><?= $this->lang->line('lb_debit') ?></label>
	                </div>
	                <div class="radio-custom radio-primary radio-inline">
	                  <input type="radio" id="credit" name="BalanceType" value="2">
	                  <label for="credit"><?= $this->lang->line('lb_credit') ?></label>
	                </div>
	            </div>
	      	</div>
	       	<div class="row">
	            <div class="form-group col-sm-12 vstore">
	              <table class="table-arcorrection table table-striped table-td-padding-0 input-table" style="margin-top: 15px;margin-bottom: 0px;">
	                <thead>
	                  <tr> 
	                    <th colspan="3"><?= $this->lang->line('lb_store_name') ?></th>
	                    <th><?= $this->lang->line('lb_correction_total_ar') ?></th>
	                    <th colspan="2"><?= $this->lang->line('lb_remark') ?></th>
	                  </tr>
	                </thead>
	                <tbody>
	                </tbody>
	              </table>
	              <a href="javascript:void(0)" onclick="add_new_row_store()" class="link_add_row">+ Add Column</a>
	            </div>

	            <div class="form-group col-sm-12 vselling">
	              <table class="table-arcorrection2 table table-striped table-td-padding-0 input-table2" style="margin-top: 15px;margin-bottom: 0px;">
	                <thead>
	                  <tr> 
	                    <th colspan="3"><?= $this->lang->line('lb_customer_name') ?></th>
	                    <th><?= $this->lang->line('lb_correction_total_ar') ?></th>
	                    <th colspan="2"><?= $this->lang->line('lb_remark') ?></th>
	                  </tr>
	                </thead>
	                <tbody>
	                </tbody>
	              </table>
	              <a href="javascript:void(0)" onclick="add_new_row_selling()" class="link_add_row">+ <?= $this->lang->line('lb_add_column') ?></a>
	            </div>
          	</div>
          	<div class="row">
              <div class="form-group col-sm-6">
                <label class="control-label"><?= $this->lang->line('lb_remarks') ?></label>
                <textarea name="Remark" id="Remark" maxlength="225" type="text" class="form-control" style="height: 100px"></textarea>
                <span class="help-block"></span>
              </div>
            </div>
            <div class="row col-sm-6" style="padding: 0">
            	<div class="form-attach"></div>
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