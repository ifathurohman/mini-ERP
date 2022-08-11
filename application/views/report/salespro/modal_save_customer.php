<!-- Modal -->
<div id="modal2" class="modal modal-primary fade modal-fade-in-scale-up customer">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
        <form id="form_customer" autocomplete="off">
          <input name="ID" id="ID" type="hidden" class="form-control">
          <input name="companyID" type="hidden">
          <div class="form-group col-sm-10">
            <label class="control-label">Option Save Customer</label>
            <div class="radio-inline-div">
              <div class="radio-custom radio-primary">
                 <input type="radio" id="new_customer" name="type2" value="new_customer" checked>
                 <label for="new_customer">Add New Customer</label>
              </div>
              <div class="radio-custom radio-primary">
                 <input type="radio" id="old_customer" name="type2" value="old_customer">
                 <label for="old_customer">Select Customer</label>
              </div>
            </div>
          </div>
          <div class="row old_customer" style="display:none">
            <div class="form-group col-sm-6 v_sales">
              <label>Customer</label>
              <select name="customer" id="customer" type="text" class="form-control" autocomplete="Sales">
              <option value="all">Select Customer</option>
              </select>
            </div>
          </div>
          <div class="row new_customer">
            <div class="form-group col-sm-6">
              <label class="control-label">Name</label>
              <input name="name2" id="name2" type="text" class="form-control" autocomplete="name">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label">Phone</label>
              <input name="phone2" id="phone2" type="text" class="form-control" autocomplete="tel-nataional">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label">Email</label>
              <input name="email2" id="email2" type="text" class="form-control" autocomplete="email">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label">Basecamp</label>
              <div class="radio-inline-div">
                <div class="radio-custom radio-primary">
                   <input type="radio" id="BaseCampN1" name="basecamp2" value="yes">
                   <label for="BaseCampN1">Yes</label>
                </div>
                <div class="radio-custom radio-primary">
                   <input type="radio" id="BaseCampN2" name="basecamp2" value="no" checked>
                   <label for="BaseCampN2">No</label>
                </div>
              </div>
            </div>
          </div>
          <div class="row new_customer">
            <div class="form-group col-sm-12">
            <!-- <label class="control-label">Location (Mark your business partner location on the map below)</label> -->
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label">Address</label>
              <input name="address2" id="pac-input" type="text" class="form-control disabled" autocomplete="off">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label">GEO Radius</label>
              <input name="radius2" type="range" class="" autocomplete="off" min="0" max="1000" >
              <span class="radius_val2"></span>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6" style="display: none">
              <label class="control-label">Latitude</label>
              <input name="lat2" id="lat2" type="text" class="form-control disabled">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6" style="display: none">
              <label class="control-label">Longitude</label>
              <input name="lng2" id="lng2" type="text" class="form-control disabled">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <div id="map2" style="height: 500px;width: 100%"></div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button type="button" class="btn btn-default btn-outline margin-0 kotak" data-dismiss="modal">Cancel</button>
          <button id="btnSave2" onclick="save()" type="button" class="btn btn-primary">Save</button>
        </div>
      </div>
    </div>
  </div>
</div>