<div class="page page-data" data-page="good_receipt" data-hakakses="<?= $this->session->hak_akses; ?>" data-start_date="<?= date('Y-m-01'); ?>" data-end_date="<?= date('Y-m-d'); ?>">
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
        <div class="row">
          <div class="col-sm-12">
            <form id="form" autocomplete="off" method="post">
            <div class="row">
              <div class="form-group col-sm-3">
                <label>Report</label>
                <select name="report" class="form-control" onchange="load_page('report')">
                  <option value="none">Select Report</option>
                  <option value="good_receipt">Good Receipt</option>
                  <option value="mutation">Mutation</option>
                  <option value="stock">Stock</option>
                  <option value="return">Return</option>
                  <option value="selling">Selling</option>
                  <option value="payment">Payment</option>
                  <option value="account_receive">Account Receive</option>
                  <option value="serial_number">Serial Number</option>
                </select>
              </div>
              <div class="form-group col-sm-3">
                <div>
                  <label class="control-label">Start Date</label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="icon wb-calendar" aria-hidden="true"></i>
                    </span>
                    <input type="text" name="start_date" class="form-control date" placeholder="" id="start_date">
                  </div>
                  <span class="help-block"></span>
                </div>
                <div>
                  <label class="control-label">End Date</label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="icon wb-calendar" aria-hidden="true"></i>
                    </span>
                    <input type="text" name="end_date" class="form-control date" placeholder="" id="end_date">
                  </div>
                  <span class="help-block"></span>
                </div>
              </div>
              <div class="form-group col-sm-3 v_group">
                <label>Group Report</label>
                <select name="group" class="form-control" onchange="load_page('group')">
                </select>
              </div>
              <div class="form-group col-sm-3 v_search">
                <label>Search</label>
                <input type="text" name="search" class="form-control">
              </div>
            </div>
          </form>
          </div>
          <div class="col-lg-12">
            <div class="btn-group pull-right">
            <div class="btn-group open" role="group">
                <button class="btn btn-default btn-outline" onclick="cetak('print')" type="button"><i class="icon fa-print"></i> Print</button>
	            <button type="button" class="btn btn-default btn-outline dropdown-toggle" id="exampleGroupDrop1" data-toggle="dropdown" aria-expanded="true">
	                <i class="icon fa-file"></i> Export
	            	<span class="caret"></span>
	            </button>
	              <ul class="dropdown-menu" aria-labelledby="exampleGroupDrop1" role="menu">
	                <li role="presentation"><a href="javascript:void(0)" role="menuitem" onclick="cetak('pdf')"><i class="icon fa-file-pdf-o"></i> Export To PDF</a></li>
	                <li role="presentation"><a href="javascript:void(0)" role="menuitem" onclick="cetak('excell')"><i class="icon fa-file-excel-o"></i> Export To Excell</a></li>
	              </ul>
	            </div>
                <!-- <button class="btn btn-default btn-outline" onclick="reload_table()" type="button"><i class="icon wb-reload"></i> Reload</button> -->
                <button class="btn btn-default btn-outline" onclick="search_table()" type="button"><i class="icon wb-search"></i> Search Data</button>     
            </div>
          </div>
          <div class="col-sm-12">
          <div class="table-data" style="margin-top: 20px;"></div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Panel Basic -->
  </div>
</div>
<!-- Plugins For This Page -->
<link rel="stylesheet" href="<?= base_url('aset/css/dataTables.bootstrap.min.css'); ?>">
<script src="<?= base_url('aset/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('aset/js/dataTables.bootstrap.min.js'); ?>"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.colVis.min.js"></script>
<!-- Plugins For This Page -->
<!-- <script src="<?= base_url('aset/js/page/report.js'); ?>"></script> -->
<script src="<?= base_url('aset/js/page/reportv2.js'); ?>"></script>
<script src="<?= base_url('aset/js/page/modal_receive.js'); ?>"></script>
<link rel="stylesheet" href="<?= base_url("aset/datepicker/bootstrap-datepicker3.css"); ?>"/>
<script type="text/javascript" src="<?= base_url("aset/datepicker/bootstrap-datepicker.min.js"); ?>"></script>