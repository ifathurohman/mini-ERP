<!-- Modal -->
<div id="modal" class="modal modal-primary fade modal-fade-in-scale-up" data-backdrop="static">
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
          <input name="vendorid" id="vendorid" type="hidden" class="form-control">
          <div class="row">
            <div class="form-group col-sm-6">
              <label class="control-label">Name</label>
              <input name="name" id="name" type="text" class="form-control" autocomplete="name">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label">Phone</label>
              <input name="phone" id="phone" type="text" class="form-control" autocomplete="tel-nataional">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label">Email</label>
              <input name="email" id="email" type="text" class="form-control" autocomplete="email">
              <span class="help-block"></span>
            </div>
           <div class="form-group col-sm-6">
              <label class="control-label">Basecamp</label>
              <div class="radio-inline-div">
                <div class="radio-custom radio-primary">
                   <input type="radio" id="BaseCampY" name="basecamp" value="yes" checked="">
                   <label for="CheckIn">Yes</label>
                </div>
                <div class="radio-custom radio-primary">
                   <input type="radio" id="BaseCampN" name="basecamp" value="no">
                   <label for="BaseCampN">No</label>
                </div>
              </div>
           </div>
          </div>
          <div class="row">
            <div class="form-group col-sm-12">
            <label class="control-label">Location (Mark your business partner location on the map below)</label>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label">Address</label>
              <input name="address" id="pac-input" type="text" class="form-control" autocomplete="off" >
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label">GEO Radius</label>
              <input name="radius" type="range" class="" autocomplete="off" min="0" max="1000" >
              <span class="radius_val"></span>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6" style="display: none">
              <label class="control-label">Latitude</label>
              <input name="lat" id="lat" type="text" class="form-control disabled">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6" style="display: none">
              <label class="control-label">Longitude</label>
              <input name="lng" id="lng" type="text" class="form-control disabled">
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
          <button type="button" class="btn btn-default btn-outline margin-0 kotak" data-dismiss="modal">Cancel</button>
          <button id="btnSave" onclick="save()" type="button" class="btn btn-primary">Save</button>
        </div>
      </div>
    </div>
  </div>
</div>