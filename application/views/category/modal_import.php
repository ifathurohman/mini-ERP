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
          <form action="<?= site_url("category/import"); ?>" method="post" id="form-import" autocomplete="off" enctype="multipart/form-data" target="_blank">
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
          <a href="<?= site_url("category/export"); ?>" target="_blank">here</a></h4><p></p>
          <!-- <p>New version of excel file use the first row as the column header, the text for the column header must be exactly as shown below (no space between column):</p>
          <p><strong>product_code</strong></p> -->
          <ul>
            <li>Category Code : Category Code is required and Maximum length is 10 digit</li>
            <li>Name : Category Name is required</li>
            <li>Level : Level is required, Value of Level can fill from 1-5 level</li>
            <li>Parent Code : Parent Code is required, if level of category value is 1, only filled with 0. But if level of category value is 2 - 5, Parent code is required</li>
            <!-- <li>Category Code : kode kategori hanya boleh maksimal 10 digit dan wajib diisi</li>
            <li>Name : nama kategori dan wajib diisi</li>
            <li>Level : level dari kategori dan wajib diisi, hanya bisa diisi dari level 1-5</li>
            <li>Parent Code : parent code wajib diisi, jika level kategorinya 1 hanya boleh diisi dengan angka 0, tapi jika levelnya 2-5, parent code bisa diisi dengan category code</li> -->
          </ul>
      </div>
      <div class="modal-footer">
        <div class="btn-group">    
          <button onclick="import_data()" type="button" class="btn btn-primary btn-import">Import</button>
          <button type="button" class="btn btn-danger btn-default margin-0 kotak" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>