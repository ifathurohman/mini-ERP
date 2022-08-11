<!-- Modal -->
<div id="modal-import" class="modal fade modal-fade-in-scale-up" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" >
  <div class="modal-dialog" style="width: 60%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
          <form action="<?= site_url("unit/import"); ?>" method="post" id="form-import" autocomplete="off" enctype="multipart/form-data" target="_blank">
            <div class="row">
              <div class="form-group col-sm-12">
                <label class="control-label">Please Select File</label>
                <input type="file" name="file" id="input-file-now" class="dropify" />
                <!-- onchange="$('form').submit();" -->
                <span>hanya untuk file sample yang di download</span>
              </div>
            </div>
            <!-- <input type="submit" name="submit" value="import"> -->
          </form>   
          <br>
          <div class="alert alert-warning">NOTE: New excel format applied, if you already have one please update your excel to the latest format before uploading!</div>
          <p></p><h4>Download sample Category excel file from 
          <a href="<?= site_url("unit/export"); ?>">here</a></h4><p></p>
          <!-- <p>New version of excel file use the first row as the column header, the text for the column header must be exactly as shown below (no space between column):</p>
          <p><strong>product_code</strong></p> -->
          <ul>
            <li>Unit Name : Unit Name is required, ex : CM, KG, KM. Unit Name is not duplicate, if data is same then sistem will update new data</li>
            <li>Conversion : Conversion is required</li>
            <li>Type : Type is required, ex: volume, berat, panjang</li>
            <li>Remark : Remark is optional</li>
          </ul>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button type="button" class="btn btn-outline btn-default margin-0 kotak" data-dismiss="modal">Close</button>     
          <button onclick="import_data()" type="button" class="btn btn-primary btn-import">Import</button>
        </div>
      </div>
    </div>
  </div>
</div>