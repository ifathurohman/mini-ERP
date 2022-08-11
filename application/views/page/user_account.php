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
            <form id="form-company" autocomplete="off">
              <div class="row">
                <div class="form-group col-sm-12">
                  <label class="control-label active">Email Address</label>
                  <input name="email" id="email" type="email" class="form-control" disabled="">
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label active">Password</label>
                  <input name="password" id="password" type="password" class="form-control">
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label active">Password Confirmation</label>
                  <input name="password_kon" id="password_kon" type="password" class="form-control">
                  <span class="help-block"></span>
                </div>
                <!-- <div class="form-group col-sm-6">
                  <label class="control-label active">First Name</label>
                  <input name="first_name" id="first_name" type="text" class="form-control">
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-6">
                  <label class="control-label active">Last Name</label>
                  <input name="last_name" id="last_name" type="text" class="form-control">
                  <span class="help-block"></span>
                </div>
                <div class="form-group col-sm-12">
                  <label class="control-label active">Phone</label>
                  <input name="phone" id="phone" type="text" class="form-control angka">
                  <span class="help-block"></span>
                </div> -->
                <div class="form-group col-sm-12">
                  <button class="btn btn-primary pull-right" onclick="company_save('user_account')" id="btnSave">Save User Account</button>
                </div>
              </div>
            </form>
          </div>          
        </div>
      </div>
    </div>
  </div>
</div>