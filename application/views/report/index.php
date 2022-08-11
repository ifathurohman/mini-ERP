<div class="page page-data" 
data-page="good_receipt" 
data-hakakses="<?= $this->session->hak_akses; ?>" 
data-report="<?= $Report; ?>" 
data-start_date="<?= $StartDate; ?>" 
data-end_date="<?= $EndDate; ?>" 
data-app="<?= $this->session->app; ?>"
data-url="<?= $url ?>">
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
            <form id="form" autocomplete="off" method="post" target="_blank">
            <div class="row">
              <div class="form-group col-sm-3">
                <label>Report</label>
                <select name="report" class="form-control" onchange="load_page('report')">
                  <option value="none">Select Report</option>
                  <?php if($this->session->app == "pipesys"):
                    $list = $this->main->menu("report_detail");
                    // function sort_name($a, $b) 
                    // { 
                    //     return strnatcmp($a->nama_menu, $b->nama_menu); 
                    // } 
                    // uasort($list, 'sort_name');
                    foreach($list as $a):
                      $type = "vstock";
                      if($a->type == 1):
                        $type = "vfinance";
                      endif;
                      $url = substr($a->url, 4);
                      echo '<option class="'.$type.'" value="'.$url.'">'.$a->nama_menu.'</option>';
                    endforeach;
                  endif; ?>
                </select>
              </div>
              <div class="form-group col-sm-3 v_tanggal">
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
              <div class="form-group col-sm-3 vparent_product v_product">
                <label>Product Name</label>
                <select name="product" id="product" class="form-control product_select select2">
                  <option value="all">Select Product</option>
                  <?php foreach($this->main->sp_list_product() as $a): 
                  echo '<option value="'.$a->ID.'">'.$a->Name.'</option>'; 
                  endforeach; ?>
                </select>
              </div>
              <div class="form-group col-sm-3 v_customer">
                <label>Customer Name</label>
                <select name="customer" id="customer" class="form-control customer_select select2">
                  <option value="all">Select Customer</option>
                  <?php foreach($this->main->sp_list_customer() as $a): 
                  echo '<option value="'.$a->ID.'">'.$a->Name.'</option>'; 
                  endforeach; ?>
                </select>
              </div>
              <div class="form-group col-sm-3 v_vendor">
                <label>Vendor Name</label>
                <select name="vendor" id="vendor" class="form-control vendor_select select2">
                  <option value="all">Select Vendor</option>
                  <?php foreach($this->main->sp_list_vendor() as $a): 
                  echo '<option value="'.$a->ID.'">'.$a->Name.'</option>'; 
                  endforeach; ?>
                </select>
              </div>
               <div class="form-group col-sm-3 v_purchaseno">
                <label>Transaction No</label>
                <select name="purchaseno" id="purchaseno" type="text" class="form-control purchaseno_select select2">
                  <option value="all">Select No</option>
                  <?php foreach($this->main->sp_list_purchaseno() as $a): 
                  echo '<option value="'.$a->ID.'">'.$a->PurchaseNo.'</option>'; 
                  endforeach; ?>
                </select>
              </div>
              <div class="form-group col-sm-3 v_sellno">
                <label>Transaction No</label>
                <select name="sellno" id="sellno" type="text" class="form-control sellno_select select2">
                <option value="all">Select No</option>
                  <?php foreach($this->main->sp_list_sellno() as $a): 
                  echo '<option value="'.$a->ID.'">'.$a->SellNo.'</option>'; 
                  endforeach; ?>
                </select>
              </div>
              <div class="form-group col-sm-3 v_deliveryno">
                <label>Delivery No</label>
                <select name="deliveryno" id="deliveryno" type="text" class="form-control deliveryno_select select2">
                  <option value="all">Select No</option>
                  <?php foreach($this->main->sp_list_deliveryno() as $a): 
                  echo '<option value="'.$a->ID.'">'.$a->DeliveryNo.'</option>'; 
                  endforeach; ?>
                </select>
              </div>
              <div class="form-group col-sm-3 v_tax">
                <label>Tax</label>
                <select name="tax" id="tax" class="form-control tax_select select2">
                    <option value="all">Select Tax</option>
                    <option value="1">Tax</option>
                    <option value="0">Non Tax</option>
                </select>
              </div>
              <div class="form-group col-sm-3 v_branch">
                <label><?= $this->lang->line('lb_store') ?></label>
                <select name="branch" id="branch" type="text" class="form-control branch_select select2">
                <option value="all"><?= $this->lang->line('lb_store_select') ?></option>
                  <?php foreach($this->main->sp_list_branch() as $a): 
                  echo '<option value="'.$a->ID.'">'.$a->Name.'</option>'; 
                  endforeach; ?>
                </select>
              </div>
              <div class="form-group col-sm-3 v_payno">
                <label>Invoice No</label>
                <select name="payno" id="payno" type="text" class="form-control payno_select select2">
                  <option value="all">Select Invoice</option>
                  <?php foreach($this->main->sp_list_payno() as $a): 
                  echo '<option value="'.$a->ID.'">'.$a->InvoiceNo.'</option>'; 
                  endforeach; ?>
                </select>
              </div>
              <div class="form-group col-sm-3 v_city">
                <label class="control-label">City</label>
                <select name="city" id="city" class="form-control city_select select2"></select>
              </div>
              <?php if($this->session->app == "salespro"): ?>
                <?php if($this->session->ParentID>0): ?>
                  <div class="form-group col-sm-3 v_company">
                    <label>Company Name</label>
                    <select name="company" id="company" class="form-control">
                    <option value="all">Pilih Company</option>
                    <?php foreach($this->main->sp_list_company() as $a): 
                    echo '<option value="'.$a->id_user.'">'.$a->nama.'</option>'; 
                    endforeach; ?>
                    </select>
                  </div>
                <?php else: ?>
                  <div class="form-group col-sm-3 v_sales">
                    <label>Employee Name</label>
                    <select name="Sales" id="Sales" type="text" class="form-control sales_select select2">
                    <option value="all">Select Sales</option>
                    <?php foreach($this->main->sp_list_sales() as $a): 
                    echo '<option value="'.$a->ID.'">'.$a->Name.'</option>'; 
                    endforeach; ?>
                    </select>
                  </div>
                <?php endif; ?>
              <?php endif; ?>
              <div class="form-group col-sm-3 v_search">
                <label>Search</label>
                <input type="text" name="search" class="form-control">
              </div>
              <?php if($this->session->ParentID>0): ?>
                <div class="form-group col-sm-3 v_sales">
                    <label>Employee Name</label>
                    <select name="Sales" id="Sales" type="text" class="form-control sales_select select2">
                    <option value="all">Select Sales</option>
                    </select>
                  </div>
              <?php endif; ?>
            </div>
          </form>
          </div>
          <div class="col-sm-12">
            <div class="row">
              <div class="btn-group pull-right v_button">
                <!-- <button class="btn btn-info" onclick="cetak('print')" type="button"><i class="icon fa-print"></i> Print</button> -->
                <div class="btn-group" role="group">
                  <button type="button" class="btn btn-info dropdown-toggle" id="exampleGroupDrop1" data-toggle="dropdown" aria-expanded="true">
                      <i class="icon fa-file"></i> Export
                    <span class="caret"></span>
                  </button>
                    <ul class="dropdown-menu" aria-labelledby="exampleGroupDrop1" role="menu">
                      <li role="presentation"><a href="javascript:;" role="menuitem" onclick="cetak('pdf')"><i class="icon fa-file-pdf-o"></i> Export To PDF</a></li>
                      <li role="presentation"><a href="javascript:;" role="menuitem" onclick="cetak('excell')"><i class="icon fa-file-excel-o"></i> Export To Excell</a></li>
                    </ul>
                  </div>
                  <?= $this->main->general_button('search_report'); ?>
                  <?= $this->main->general_button('reload'); ?>    
              </div>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="div-loader">
              <div class="loader"></div>
            </div>
            <div class="table-data" style="margin-top: 20px;overflow: auto;"></div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Panel Basic -->
  </div>
</div>
<?php if($this->session->app == "salespro"): 
$this->load->view("report/salespro/modal_visit");
$this->load->view("report/salespro/modal_save_customer");
?>
<script  src="https://maps.googleapis.com/maps/api/js?key=<?= $this->config->item("gmap_api"); ?>&libraries=places"></script>
<?php endif; ?>
<!-- Plugins For This Page -->
<link rel="stylesheet" href="<?= base_url('aset/css/dataTables.bootstrap.min.css'.$this->main->js_css_version()); ?>">
<link rel="stylesheet" href="<?= base_url('aset/css/select2.css'.$this->main->js_css_version()); ?>">
<script src="<?= base_url('aset/plugin/select2/select2.min.js') ?>"></script>
<script src="<?= base_url('aset/js/jquery.dataTables.min.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/dataTables.bootstrap.min.js'.$this->main->js_css_version()); ?>"></script>

<!-- Plugins For This Page -->

<script src="<?= base_url('aset/js/page/report.js'.$this->main->js_css_version()); ?>"></script>
<script src="<?= base_url('aset/js/page/modal_receive.js'.$this->main->js_css_version()); ?>"></script>
<link rel="stylesheet" href="<?= base_url("aset/datepicker/bootstrap-datepicker3.css".$this->main->js_css_version()); ?>"/>
<script type="text/javascript" src="<?= base_url("aset/datepicker/bootstrap-datepicker.min.js".$this->main->js_css_version()); ?>"></script>