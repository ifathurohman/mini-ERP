<!-- Modal -->
<div id="modal" class="modal fade modal-fade-in-scale-up">
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
          <input type="hidden" name="id_menu">
          <div class="row">
            <div class="form-group col-sm-12">
              <label class="control-label">NAMA MENU <span class="wajib"></span></label>
              <input name="nama_menu" id="nama_menu" type="text" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label">URL <span class="wajib"></span></label>
              <input name="url" id="url" type="text" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label">METHOD <span class="wajib"></span></label>
              <input name="root" id="method" type="text" class="form-control">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label">KATEGORI</label>
              <select name="kategori" placeholder="Kategori" id="kategori" class="form-control"> 
                  <option value="none">Pilih Kategori</option>
                  <option value="management">Management</option>
                  <option value="administrasi">Administration</option>
                  <option value="master">Master</option>
                  <option value="transaction">Transaksi</option>
                  <option value="report">Laporan</option>
                  <option value="setting">Setting</option>
              </select>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12 vtype">
              <label class="control-label">Type</label>
              <select name="type" id="type" class="form-control"></select>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label">Modul</label>
              <select name="modul[]" placeholder="modul" id="modul" multiple> 
                  <option value="ap">Account Payable</option>
                  <option value="ar">Account Receivable</option>
                  <option value="ac">Accounting</option>
                  <option value="inventory">Inventory</option>
              </select>
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-12 vmodul2">
              <label class="control-label">Modul Page</label>
              <select name="modul2[]" id="modul2" multiple></select>
            </div>
            <div class="form-group col-sm-12">
              <label class="label-control" for="toko">Pilih Aplikasi</label>
              <div class="line-checkbox">
                <div class="checkbox-custom checkbox-primary">
                  <input class="icheckbox-primary " name="app[]" id="pipesys" type="checkbox" value="pipesys">
                  <label for="pipesys">Pipesys</label>                      
                </div>
                <div class="checkbox-custom checkbox-primary">
                  <input class="icheckbox-primary " name="app[]" id="salespro" type="checkbox" value="salespro">
                  <label for="salespro">Sales Pro</label>                      
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button id="btnSave" onclick="save()" type="button" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-danger btn-default margin-0 kotak" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>