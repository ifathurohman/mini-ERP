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
          <form action="<?= site_url("product_service/import"); ?>" method="post" id="form-import" autocomplete="off" enctype="multipart/form-data" target="_blank">
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
          <p></p><h4>Download sample Product excel file from 
          <a href="<?= site_url("product_service/export"); ?>">here</a></h4><p></p>
          <!-- <p>New version of excel file use the first row as the column header, the text for the column header must be exactly as shown below (no space between column):</p>
          <p><strong>product_code</strong></p> -->
           <ul>
            <li>Product Code : Product Code dikosongkan, karena otomatis dibuat oleh sistem</li>
            <li>Category Code :Category wajib diisi dan bisa dilihat di master kategori</li>
             <li>Category Name :Category Name wajib diisi dan bisa dilihat di master kategori</li>
            <li>Product Name : nama produk wajib diisi</li>
            <li>Selling Price : harga jual produk dan wajib diisi</li>
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