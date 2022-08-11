<!-- Modal -->
<div id="modal" class="modal modal-primary fade modal-fade-in-scale-up" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" tabindex="-1">
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
          <div class="row view-form-sales">
            <div class="form-group col-sm-12">
              <label class="control-label">Email Address</label>
              <input name="Email" id="Email" type="email" class="form-control readonly">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label">Password</label>
              <input name="Password" id="Password" type="password" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label">First Name</label>
              <input name="FirstName" id="FirstName" type="text" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label">Last Name</label>
              <input name="LastName" id="LastName" type="text" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label">Phone</label>
              <input name="Phone" id="Phone" type="text" class="form-control angka">
              <span class="help-block"></span>
            </div>
          </div>
          <div class="row view-form-voucher">
            <div class="form-group col-sm-12">
              <label class="control-label">Voucher Code</label>
              <input name="VoucherCode" id="VoucherCode" type="text" class="form-control">
              <span class="help-block"></span>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">       
          <button id="btnSave" onclick="save()" type="button" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-danger btn-default margin-0 kotak" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>