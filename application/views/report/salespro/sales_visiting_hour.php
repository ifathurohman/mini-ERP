<div class="page page-data" data-modul="transaction_route">
  <div class="page-header">
  </div>
  <div class="page-content">
    <!-- Panel Basic -->
    <div class="panel panel-bordered">
      <header class="panel-heading">
        <div class="panel-actions"></div>
        <h3 class="panel-title"><?= $title; ?></h3>
      </header>
      <div class="panel-body">
        <div class="row row-lg">
          <div class="col-lg-12">
            <form id="form" autocomplete="off" method="post">
              <div class="row">
                <div class="form-group col-sm-3">
                  <div>
                    <label class="control-label">Date</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="icon wb-calendar" aria-hidden="true"></i>
                      </span>
                      <input value="<?= $StartDate ?>" type="text" name="StartDate" class="form-control date2" placeholder="" id="start_date">
                    </div>
                    <span class="help-block"></span>
                  </div>
                </div>
                <?php if($this->session->app == "salespro"): ?>
                  <?php if($this->session->ParentID>0): ?>
                    <div class="form-group col-sm-3">
                      <div>
                        <label>Company Name</label>
                        <select name="company" id="company" class="form-control">
                          <option value="all">Pilih Company</option>
                          <?php foreach($this->main->sp_list_company() as $a): 
                            echo '<option value="'.$a->id_user.'">'.$a->nama.'</option>'; 
                          endforeach; ?>
                        </select>
                      </div>
                      <div style="margin-top: 8px">
                        <label>Employee Name</label>
                        <select name="Sales" id="Sales" type="text" class="form-control" autocomplete="Sales">
                          <option value="all">Pilih sales</option>
                        </select>
                      </div>
                    </div>
                  <?php else: ?>
                    <div class="form-group col-sm-3 v_sales">
                      <label>Employee Name</label>
                      <select name="Sales" id="Sales" type="text" class="form-control" autocomplete="Sales">
                      <option value="all">Pilih sales</option>
                      <?php foreach($this->main->sp_list_sales() as $a): 
                      echo '<option value="'.$a->ID.'">'.$a->Name.'</option>'; 
                      endforeach; ?>
                      </select>
                    </div>
                  <?php endif; ?>
                <?php endif; ?>
              </div>
            </form>
          </div>
          <div class="col-lg-12">
            <div class="btn-group pull-right">
              <button class="btn btn-default btn-outline" onclick="load_data()" type="button"><i class="icon wb-search"></i> Search Data</button>     
            </div>
          </div>
          <div class="col-sm-12">
            <div class="panel panel-map panel-bordered" style="padding:10px">
               <div id="lineContainer"  style="height: 450px;">
               
               </div>
            </div>            
         </div>
        </div>
        <br>
      </div>
    </div>
    <!-- End Panel Basic -->
  </div>
</div>

<!-- Plugins For This Page -->
<link rel="stylesheet" href="<?= base_url("aset/datepicker/bootstrap-datepicker3.css"); ?>"/>
<script type="text/javascript" src="<?= base_url("aset/datepicker/bootstrap-datepicker.min.js"); ?>"></script>
<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>

<script src="<?= base_url('aset/js/page/sales_visiting_hour.js'); ?>"></script>