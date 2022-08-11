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
          <h5>1. Download sample template Category excel file from 
          <a href="<?= site_url("vendor/export/template"); ?>">here</a></h5>
          <ul>
            <li>This file has preset column header required by PipeSys to import your data correctly</li>
          </ul>
          <h5>2. Entry Data To File Template</h5>
          <ul>
            <li>Use Microsoft Excel application to copy and paste your data into Pipesys import template that you just downloaded. Make sure the data you that you input corresponds to each column header provided on the import template and note below.</li>
            <li>Code: Code is required and no duplicate</li>
            <li>Name: Name is required</li>
            <li>Partner Type: Partner Type is required, Ex: Vendor, Customer</li>
            <li>Address: fill in the Address of Business Partner </li>
            <li>City: fill in the City of Business Partner</li>
            <li>Province: fill in the Province of Business Partner</li>
            <li>Phone: fill in the Phone of Business Partner</li>
            <li>Email:fill in the Email of Business Partner</li>
            <li>NPWP:fill in the NPWP of Business Partner</li>
            <li>TOP:fill in the TOP of Business Partner</li>
            <li>Group Name:fill in the Group Name of Business Partner</li>
            <li>Remark:fill in the Remark of Business Partner</li>
          </ul>
          <h5>3. Upload File</h5>
          <ul>
            <li>After your finished with step 2, please upload the file. File needs to be in CSV (Comma Separated Values) format and ended with extension .xls</li>
          </ul>
          <form action="<?= site_url("vendor/import"); ?>" method="post" id="form-import" autocomplete="off" enctype="multipart/form-data" target="_blank">
            <div class="row">
              <div class="form-group col-sm-12">
                <label class="control-label">Please Select File</label>
                <input type="file" name="file" id="input-file-now" class="dropify" />
                <!-- onchange="$('form').submit();" -->
              </div>
            </div>
            <!-- <input type="submit" name="submit" value="import"> -->
          </form>   
          <div class="alert alert-warning">NOTE: New excel format applied, if you already have one please update your excel to the latest format before uploading!</div>
      </div>
      <div class="modal-footer">
        <div class="btn-group">   
          <button onclick="import_data()" type="button" class="btn btn-primary btn-import">Next</button>
          <button type="button" class="btn btn-danger btn-default margin-0 kotak" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>