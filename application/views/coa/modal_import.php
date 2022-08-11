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
        <h5>1. Download sample template Category excel file from 
        <a href="<?= site_url("master_coa/export/template"); ?>">here</a></h5>
        <ul>
          <li>This file has preset column header required by PipeSys to import your data correctly</li>
        </ul>
        <h5>2. Entry Data To File Template</h5>
        <ul>
          <li>Use Microsoft Excel application to copy and paste your data into Pipesys import template that you just downloaded. Make sure the data you that you input corresponds to each column header provided on the import template and note below.</li>
          <li>Code: Code COAis required and not duplicate</li>
          <li>Name: Name is required.</li>
          <li>Level: Level is required, entry Level data at excell must be sequential from 1 until 4.</li>
          <li>Parent COA: Parent COA is required if level is not Level 1</li>
        </ul>
        <h5>3. Upload File</h5>
        <ul>
          <li>After your finished with step 2, please upload the file. File needs to be in CSV (Comma Separated Values) format and ended with extension .xls</li>
        </ul>
        <form method="post" id="form-import" autocomplete="off" enctype="multipart/form-data" target="_blank">
          <div class="row">
            <div class="form-group col-sm-12">
              <label class="control-label">Please Select File</label>
              <input type="file" name="file" id="input-file-now" class="dropify" accept="application/vnd.ms-excel"/>
              <!-- onchange="$('form').submit();" -->
            </div>
          </div>
          <!-- <input type="submit" name="submit" value="import"> -->
        </form>   
        <div class="alert alert-warning">NOTE: New excel format applied, if you already have one please update your excel to the latest format before uploading!</div>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button onclick="upload_import()" type="button" class="btn btn-primary btn-import">Next</button>
          <button type="button" class="btn btn-danger btn-default margin-0 kotak" data-dismiss="modal">Close</button>  
        </div>
      </div>
    </div>
  </div>
</div>