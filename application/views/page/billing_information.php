<div class="page page-data" data-hakakses="<?= $this->session->hak_akses; ?>">
  <div class="page-header">
  </div>
  <div class="page-content">
    <div class="panel panel-bordered">
      <header class="panel-heading">
        <div class="panel-actions"></div>
        <h3 class="panel-title"><?= $title; ?></h3>
      </header>
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-6">
            <form id="form-company" autocomplete="off" method="post">
              <div class="row">
                <div class="form-group col-sm-12">
                  <label class="control-label">Company Logo</label>
                  <input type="file" name="photo" id="input-file-now" class="dropify"/>
                  <!--  onchange="$('form').submit();" -->
                  <span>File size max 2mb, format (PNG, JPG, JPEG)</span>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label">Company Name</label>
                  <input name="nama" id="nama" type="text" class="form-control">
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label">Address</label>
                  <textarea name="address" id="address" type="text" class="form-control"></textarea>
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label">City</label>
                  <input name="city" id="city" type="text" class="form-control">
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label">Province</label>
                  <input name="province" id="province" type="text" class="form-control">
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label">Country</label>
                  <input name="country" id="country" type="text" class="form-control">
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label">Postal</label>
                  <input name="postal" id="postal" type="text" class="form-control angka">
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label">Phone</label>
                  <input name="phone" id="phone" type="text" class="form-control angka">
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label">Fax</label>
                  <input name="fax" id="fax" type="text" class="form-control angka">
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12">
                  <button class="btn btn-primary pull-right" onclick="company_save('company')" id="btnSave">Save Company Information</button>
                </div>
              </div>
            </form>

          </div>          
        </div>
      </div>
    </div>
  </div>
</div>