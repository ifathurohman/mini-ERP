<!-- Modal -->
<div id="modal-import" class="modal fade modal-fade-in-scale-up" data-backdrop="static" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" >
  <div class="modal-dialog" style="width: 60%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
          <form action="<?= site_url("product/import"); ?>" method="post" id="form-import" autocomplete="off" enctype="multipart/form-data" target="_blank">
            <div class="row">
              <div class="form-group col-sm-12">
                <label class="control-label">Please Select File</label>
                <input type="file" name="file" id="input-file-now" class="dropify" accept="application/vnd.ms-excel"/>
                <!-- onchange="$('form').submit();" -->
              </div>
            </div>
            <!-- <input type="submit" name="submit" value="import"> -->
          </form>   
          <br>
          <div class="alert alert-warning">NOTE: New excel format applied, if you already have one please update your excel to the latest format before uploading!</div>
          <p></p><h4>Download sample conversion balance excel file from 
          <a href="<?= site_url("conversion_balance/export_example"); ?>">here</a></h4><p></p>
          <!-- <p>New version of excel file use the first row as the column header, the text for the column header must be exactly as shown below (no space between column):</p>
          <p><strong>product_code</strong></p> -->
          <ul>
            <li>Code: Code COAis required and not duplicate</li>
            <li>Name: Name is required.</li>
            <li>Level: Level is required.</li>
            <li>Parent COA: Parent COA is required if level is not Level 1</li>
            <li>Debit: Total Debit of Chart of Account</li>
            <li>Credit: Total Credit of Chart of Account</li>
          </ul>
      </div>
      <div class="modal-footer">
        <div class="btn-group">   
          <button onclick="upload_import()" type="button" class="btn btn-primary btn-import">Import</button>
          <button type="button" class="btn btn-danger btn-default margin-0 kotak" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>