<div class="page page-dashboard page-data" data-hakakses="<?= $this->session->hak_akses; ?>" data-app="salespro" data-currentdate="<?= date("Y-m-d"); ?>" data-startdate="<?= date("Y-m-01") ?>">
   <div class="page-content">
      <div class="row">
         <div class="col-sm-12 filter-dashboard">
            <form id="form-dashboard-filter" autocomplete="off" method="post">
               <div class="row">
                  <div class="form-group col-sm-5 div-inline col-sm-offset-7">
                     <div class="input-group width-40">
                        <span class="input-group-addon">
                        <!-- From -->
                        <i class="icon wb-calendar" aria-hidden="true"></i>
                        </span>
                        <input type="text" name="StartDate" class="form-control date" placeholder="from" id="start_date">
                     </div>
                     <span class="help-block"></span>
                     <div class="input-group width-40">
                        <span class="input-group-addon">
                        <i class="icon wb-calendar" aria-hidden="true"></i>
                        <!-- To -->
                        </span>
                        <input type="text" name="EndDate" class="form-control date" placeholder="to" id="end_date">
                     </div>
                     <span class="help-block"></span>
                     <div>
                        <a href="javascript:void(0)" class="btn btn-primary" onclick="load_data('filter')">apply</a>
                     </div>
                  </div>
               </div>
            </form>
         </div>
         <div class="col-sm-12">
            <div class="panel panel-map panel-bordered" style="padding:10px">
               <div id="lineContainer"  style="height: 450px;">
               
               </div>
            </div>            
         </div>
         <div class="col-sm-12">
            <div class="panel panel-map panel-bordered" style="padding:10px">
               <div id="lineTotalVisit"  style="height: 450px;">
               
               </div>
            </div>            
         </div>
         <div class="col-sm-12">
            <div class="panel panel-map panel-bordered" style="padding:10px">
               <div id="chartContainer"  style="height: 400px;">
               
               </div>
            </div>            
         </div>
         <div class="col-sm-4">
            <div class="widget-content padding-30 bg-white clearfix pointer" onclick="get_report('sales_visiting')">
               <div class="pull-left white">
                  <i class="icon icon-circle icon-2x fa-user bg-blue-600" aria-hidden="true"></i>
               </div>
               <div class="counter  counter text-right pull-right">
                  <div class="counter-label text-capitalize font-size-16">Total Employee Today</div>
                  <div class="counter-number-group">
                     <span class="counter-number total_sales">0</span>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="widget-content padding-30 bg-white clearfix pointer" onclick="get_report('sales_visiting_time')">
               <div class="pull-left white">
                  <i class="icon icon-circle icon-2x fa-map-marker bg-red-600" aria-hidden="true"></i>
               </div>
               <div class="counter  counter text-right pull-right">
                  <div class="counter-label text-capitalize font-size-16">Total Route Transaction Today</div>
                  <div class="counter-number-group">
                     <span class="counter-number total_route_transaction">0</span>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="widget-content padding-30 bg-white clearfix pointer" onclick="get_report('sales_visiting_time')">
               <div class="pull-left white">
                  <i class="icon icon-circle icon-2x fa-check bg-green-600" aria-hidden="true"></i>
               </div>
               <div class="counter  counter text-right pull-right">
                  <div class="counter-label text-capitalize font-size-16">Total Complete Route Today</div>
                  <div class="counter-number-group">
                     <span class="counter-number total_complete_route">0</span>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-sm-12">
            <div class="panel panel-map panel-bordered">
               <div class="panel-heading">
                  <h3 class="panel-title"  style="line-height: 30px;">Employee's Location Today <a href="javascript:void(0)" class="btn btn-outline btn-default pull-right" onclick="reload_data('map')"><i class="icon fa-refresh"></i></a></h3>
               </div>
               <div class="panel-body">
                  <div class="panel-group float-table-map" id="accordion">
                     <div class="panel panel-primary " >
                        <div class="panel-heading pointer" data-toggle="collapse" data-parent="#accordion" href="#collapse1">
                           <h4 class="panel-title">
                              Status Sales
                           </h4>
                        </div>
                        <div id="collapse1" class="panel-collapse collapse in">
                           <div class="panel-body padding-10">
                              <div class="row">
                                 <div class="form-group col-sm-12">
                                    <div class="radio-custom radio-primary radio-inline">
                                       <input type="radio" id="CheckIn" name="Check" value="CheckIn" checked="">
                                       <label for="CheckIn">Check In</label>
                                    </div>
                                    <div class="radio-custom radio-primary radio-inline">
                                       <input type="radio" id="CheckOut" name="Check" value="CheckOut">
                                       <label for="CheckOut">Finish</label>
                                    </div>
                                 </div>
                                 <div class="col-sm-6">
                                    <!-- <button class="btn btn-outline btn-default pull-right" onclick="reload_data('status_sales')">Reload</button> -->
                                 </div>
                                 <div class="col-sm-12">
                                    <table class="table table-hover table-striped" id="table-info-sales">
                                       <thead>
                                       </thead>
                                       <tbody>
                                       </tbody>
                                    </table>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div id="map" style="height: 500px;width: 100%"></div>
                  <div id="info-marker-visit">
                     <ul class="ul-line-info">
                        <li>
                           <img src="<?= base_url('img/icon/marker-blue.svg'); ?>" style="height: 30px"> Check In
                        </li>
                        <li>
                           <img src="<?= base_url('img/icon/marker-red.svg'); ?>" style="height: 30px"> Finish
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php $this->load->view("modal/modal_dashboard"); ?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= $this->config->item("gmap_api"); ?>"></script>
<script src="<?= base_url('aset/js/page/dashboard.js'); ?>"></script>
<link rel="stylesheet" href="<?= base_url("aset/datepicker/bootstrap-datepicker3.css"); ?>"/>
<script type="text/javascript" src="<?= base_url("aset/datepicker/bootstrap-datepicker.min.js"); ?>"></script>
<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>