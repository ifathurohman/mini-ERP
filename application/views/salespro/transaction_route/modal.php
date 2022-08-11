<!-- Modal -->
<div id="modal" class="modal modal-primary fade modal-fade-in-scale-up">
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
          <input name="TransactionRouteID" id="TransactionRouteID" type="hidden" class="form-control">
          <div class="row">
            <div class="form-group col-sm-4">
              <label class="control-label">Transaction Number</label>
              <input name="Code" id="Code" type="text" class="form-control" autocomplete="Code" readonly="">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-4">
              <label class="control-label">Date</label>
              <input name="Date" id="Date" type="text" class="form-control date" autocomplete="Date" value="<?= date("Y-m-d"); ?>">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-4">
              <label class="control-label">Employee Name</label>
              <select name="Name" id="Name" type="text" class="form-control" autocomplete="Sales">
              <option value="0">Pilih Employee</option>
              <?php foreach($this->main->sp_list_sales() as $a): 
              echo '<option value="'.$a->ID.'">'.$a->Name.'</option>'; 
              endforeach; ?>
              </select>
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row">
          <hr/>
            <div class="list-add-customer">
            </div>
            <div class="form-group col-sm-12">
              <a href="javascript:void(0)" onclick="add_new_customer()" id="AddNewCustomer"> Add New Customer</a>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button type="button" class="btn btn-default btn-outline margin-0 kotak" data-dismiss="modal">Close</button>
          <button id="btnSave" onclick="save()" type="button" class="btn btn-primary">Save</button>
        </div>
      </div>
    </div>
  </div>
</div>