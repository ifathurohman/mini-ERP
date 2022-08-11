<!-- Modal -->
<div id="modal-notransaksi" class="modal modal-primary fade modal-fade-in-scale-up" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Serial Number Format</h4>
      </div>
      <div class="modal-body">
        <form id="form-format" autocomplete="off">
          <input type="hidden" name="format_class">
          <div class="row">
            <div class="form-group col-sm-12">
              <!-- <label class="control-label">Format</label> -->
              <input name="format2" id="format2" type="text" class="form-control" placeholder="e.g SN****">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label">Format Date</label>
              <select  name="format" id="format" type="text" class="form-control">
                <option value="select">Select Format</option>
                <option value="YEAR/MONTH">YEAR/MONTH e.g <?= date("ym"); ?>*****</option>
                <option value="YEAR">YEAR e.g <?= date("y"); ?>*****</option>
                <option value="MONTH">MONTH e.g <?= date("m"); ?>*****</option>
              </select>
              <span class="help-block"></span>
            </div>
            
            <!-- <div class="form-group col-sm-6">
              <label class="control-label">Format</label>
              <input name="format3" id="format3" type="text" class="form-control" placeholder="e.g /SN">
              <span class="help-block"></span>
            </div> -->
            <div class="form-group col-sm-12">
              <label class="control-label">Start From</label>
              <input name="format_from" id="format_from" type="format_from" class="form-control" value="0001">
              <span class="help-block"></span>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">         
          <button id="btnSave" onclick="save_format()" type="button" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-danger btn-default margin-0 kotak" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>