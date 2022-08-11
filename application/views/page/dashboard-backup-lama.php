<style type="text/css">
	canvas{
		height: 200px !important;
	}
</style>
<div class="page page-dashboard page-data" data-hakakses="<?= $this->session->hak_akses; ?>" data-app="pipesys" data-currentdate="<?= date("Y-m-d") ?>" data-startdate="<?= date("Y-m-01") ?>" data-ap="<?= $ap ?>" data-ar="<?= $ar ?>" data-ac="<?= $ac ?>" data-inventory="<?= $inventory ?>">
<div class="page-content">
	<form id="form-filter" autocomplete="off" method="post">
	  <div class="row">
	  	<div class="form-group col-sm-3"></div>
	  	<div class="form-group col-sm-3"></div>
	    <div class="form-group col-sm-3">
	      <label class="control-label"><?= $this->lang->line('lb_startdate') ?></label>
	      <div class="input-group">
	        <span class="input-group-addon">
	          <i class="fa-calendar" aria-hidden="true"></i>
	        </span>
	        <input type="text" name="fStartDate" class="form-control date pointer" placeholder="<?= $this->lang->line('lb_startdate_select') ?>" id="fStartDate" maxlength="50" readonly="readonly">
	        <span class="input-group-addon pointer" title="Remove Start Date" onclick="remove_value('fStartDate')">
	          <i class="fa-times" aria-hidden="true"></i>
	        </span>
	      </div>
	      <span class="help-block"></span>
	    </div>
	    <div class="form-group col-sm-3">
	      <label class="control-label"><?= $this->lang->line('lb_enddate') ?></label>
	      <div class="input-group">
	        <span class="input-group-addon">
	          <i class="fa-calendar" aria-hidden="true"></i>
	        </span>
	        <input type="text" name="fEndDate" class="form-control date pointer" placeholder="<?= $this->lang->line('lb_enddate_select') ?>" id="fEndDate" maxlength="50" readonly="readonly">
	        <span class="input-group-addon pointer" title="Remove End Date" onclick="remove_value('fEndDate')">
	          <i class="fa-times" aria-hidden="true"></i>
	        </span>
	        <span class="input-group-addon pointer" title="Search Data" onclick="load_data()">
	          <i class="fa-search" aria-hidden="true"></i>
	        </span>
	      </div>
	      <span class="help-block"></span>
	    </div>
	  </div>
	</form>

  	<div class="row">
	  <div class="col-sm-3 content-hide">
	    <div class="widget-content padding-30 bg-white clearfix">
	      <div class="pull-left white">
	        <i class="icon icon-circle icon-2x fa-money bg-green-600" aria-hidden="true"></i>
	      </div>
	      <div class="counter  counter text-right pull-right">
	        <div class="counter-number-group">
	          <span class="counter-number total_sell_amount">0</span>
	        </div>
	        <div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_total_sales_amount') ?></div>
	        <!-- <a href="#" class="counter-label text-capitalize font-size-16" target="_blank">See Detail</a> -->
	      </div>
	    </div>
	  </div>
	  <div class="col-sm-3 content-hide">
	    <div class="widget-content padding-30 bg-white clearfix">
	      <div class="pull-left white">
	        <i class="icon icon-circle icon-2x fa-money bg-green-600" aria-hidden="true"></i>
	      </div>
	      <div class="counter  counter text-right pull-right">
	        <div class="counter-number-group">
	          <span class="counter-number ">0</span>
	        </div>
	        <div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_total_gross_margin') ?></div>
	        <!-- <a href="#" class="counter-label text-capitalize font-size-16" target="_blank">See Detail</a> -->
	      </div>
	    </div>
	  </div>
	  <div class="col-sm-3 content-hide">
	    <div class="widget-content padding-30 bg-white clearfix">
	      <div class="pull-left white">
	        <i class="icon icon-circle icon-2x fa-money bg-green-600" aria-hidden="true"></i>
	      </div>
	      <div class="counter  counter text-right pull-right">
	        <div class="counter-number-group">
	          <span class="counter-number total_purchase_amount">0</span>
	        </div>
	        <div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_total_purchase_amount') ?></div>
	        <!-- <a href="#" class="counter-label text-capitalize font-size-16" target="_blank">See Detail</a> -->
	      </div>
	    </div>
	  </div>

	   <div class="col-sm-12 vexpire content-hide">
	  	<div class="panel panel-map panel-bordered is-collapse">
            <div class="panel-heading">
              <div class="Message Message--orange frosted">
			  <div class="Message-icon">
			    <i class="fa fa-exclamation-circle"></i>
			  </div>
			  <div class="Message-body">
			    <p style="font-weight: bold; font-size: large; color: white;"><?= $this->lang->line('lb_module_device_expire') ?></p>
			  </div>
			</div>

              <div class="panel-actions panel-actions-keep">
              	<!-- <a class="panel-action icon wb-refresh" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="load_data('sellheader')" style="color:#ffffff;"></a> -->
              	<a class="panel-action icon wb-plus animated bounce" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true" style="color:#ffffff;"></a>
              </div>
            </div>

            <div class="panel-body">
            	<div class="col-sm-12 vexpire-module">
				  	<div class="panel panel-map panel-bordered">
			            <div class="panel-heading panel-gb-header">
			              <h3 class="panel-title" style="color:#ff0000;text-align: center;"><?= $this->lang->line('lb_module_expire') ?></h3>
			              <div class="panel-actions panel-actions-keep" style="color: #e4e4e4">
			              	<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
			              </div>
			            </div>
		            	<table id="table_module" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
				           <thead>
		            			<tr>
		            				<th><?= $this->lang->line('lb_no') ?></th>
		            				<th><?= $this->lang->line('module') ?></th>
		            				<th><?= $this->lang->line('lb_expire_date') ?></th>
		            				<th><?= $this->lang->line('lb_day_left') ?></th>
		            			</tr>
		            		</thead>
				          <tbody>
				          </tbody>
				        </table>
			      	</div>
			  	</div>
			  	<div class="col-sm-12 vexpire-devices">
				  	<div class="panel panel-map panel-bordered">
			            <div class="panel-heading panel-gb-header">
			              <h3 class="panel-title" style="color:#ff0000;text-align: center;"><?= $this->lang->line('lb_device_expire') ?></h3>
			              <div class="panel-actions panel-actions-keep">
			              	<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
			              </div>
			            </div>
		            	<table id="table_devices" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
				          <thead>
		            			<tr>
		            				<th><?= $this->lang->line('lb_no') ?></th>
		            				<th><?= $this->lang->line('lb_store') ?></th>
		            				<th><?= $this->lang->line('lb_expire_date') ?></th>
		            				<th><?= $this->lang->line('lb_day_left') ?></th>
		            			</tr>
		            		</thead>
				          <tbody>
				          </tbody>
				        </table>
			      	</div>
			  	</div>
			  	<div class="col-sm-12 vexpire-additional">
				  	<div class="panel panel-map panel-bordered">
			            <div class="panel-heading panel-gb-header">
			              <h3 class="panel-title" style="color:#ff0000;text-align: center;"><?= $this->lang->line('lb_additional_expire') ?></h3>
			              <div class="panel-actions panel-actions-keep">
			              	<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
			              </div>
			            </div>
		            	<table id="table_additional" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
				          <thead>
		            			<tr>
		            				<th><?= $this->lang->line('lb_no') ?></th>
		            				<th><?= $this->lang->line('lb_name') ?></th>
		            				<th><?= $this->lang->line('lb_expire_date') ?></th>
		            				<th><?= $this->lang->line('lb_day_left') ?></th>
		            			</tr>
		            		</thead>
				          <tbody>
				          </tbody>
				        </table>
			      	</div>
			  	</div>
            </div>
      	</div>
	  </div>
	  <div class="col-sm-12 var">
	  	<div class="panel panel-map panel-bordered">
            <div class="panel-heading">
              <h3 class="panel-title"><?= $this->lang->line('lb_sales') ?></h3>
              <div class="panel-actions panel-actions-keep">
              	<a class="panel-action icon wb-refresh" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="load_data('sellheader')"></a>
              	<a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a>
              </div>
            </div>
            <div class="panel-body">
            	<div class="col-sm-4">
				  	<div class="panel panel-map panel-bordered">
			            <div class="panel-heading panel-gb-header">
			              <h3 class="panel-title"><?= $this->lang->line('lb_total_by_category') ?></h3>
			              <div class="panel-actions panel-actions-keep">
			              	<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
			              </div>
			            </div>
			            <div class="panel-body">
			            	<div class="widget-content padding-10 clearfix bg-dashboard">
								<div class="pull-left white">
									<img src="img/icon/icon-dashboard/038-shopping.png" alt="" class="baru-img">
								</div>
								<div class="counter-dashboard  counter text-right pull-right">
						        <div class="counter-number-group">
						          <span class="counter-number total_sell_qty">0</span>
						        </div>
						        <div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_total_by_category') ?></div>
						        <!-- <a href="#" class="counter-label text-capitalize font-size-16" target="_blank">See Detail</a> -->
						      </div>
						    </div>
			            	<canvas id="sales_category"></canvas>
			            </div>
			      	</div>
			  	</div>
			  	<div class="col-sm-4 content-hide">
				  	<div class="panel panel-map panel-bordered">
			            <div class="panel-heading panel-gb-header">
			              <h3 class="panel-title"><?= $this->lang->line('lb_total_sales_transaction') ?></h3>
			              <div class="panel-actions panel-actions-keep">
			              	<div class="dropdown">
			                  	<a class="panel-action" id="examplePanelDropdown" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="icon wb-more-vertical" aria-hidden="true"></i></a>
			                  	<ul class="dropdown-menu animate" id="ul-sales_city" data-page="sales_city">
			                  		<li class="v1" data-type="days" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="selected_item('#ul-sales_city','.v1')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_days') ?></a>
			                  		</li>
			                  		<li class="v2" data-type="month" onclick="selected_item('#ul-sales_city','.v2')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_month') ?></a>
			                  		</li>
			                  		<li class="v3" data-type="year" onclick="selected_item('#ul-sales_city','.v3')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_year') ?></a>
			                  		</li>
			                  	</ul>
			                </div>
			              	<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
			              </div>
			            </div>
			            <div class="panel-body">
			            	<div class="widget-content padding-10 clearfix bg-dashboard">
									<div class="pull-left white">
										<img src="img/icon/icon-dashboard/005-shopping bag-min.png" alt="" class="baru-img">
									</div>
									<div class="counter-dashboard  counter text-right pull-right">
						        <div class="counter-number-group">
						          <span class="counter-number total_sell">0</span>
						        </div>
						        <div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_total_sales_transaction') ?></div>
						        <!-- <a href="#" class="counter-label text-capitalize font-size-16" target="_blank">See Detail</a> -->
						      </div>
						    </div>
						    <canvas id="sales_city"></canvas>
			            </div>
			      	</div>
			  	</div>
			  	<div class="col-sm-8">
				  	<div class="panel panel-map panel-bordered">
			            <div class="panel-heading panel-gb-header">
			              <h3 class="panel-title"><?= $this->lang->line('lb_outstanding_sales') ?></h3>
			              <div class="panel-actions panel-actions-keep">
			              	<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
			              	<div class="dropdown">
			                  	<a class="panel-action" id="examplePanelDropdown" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="icon wb-more-vertical" aria-hidden="true"></i></a>
			                  	<ul class="dropdown-menu animate" id="ul-outstanding" data-page="outstanding_delivery">
			                  		<li class="v1" data-type="days" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="selected_item('#ul-outstanding','.v1')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_days') ?></a>
			                  		</li>
			                  		<li class="v2" data-type="month" onclick="selected_item('#ul-outstanding','.v2')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_month') ?></a>
			                  		</li>
			                  		<li class="v3" data-type="year" onclick="selected_item('#ul-outstanding','.v3')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_year') ?></a>
			                  		</li>
			                  	</ul>
			                </div>
			              </div>
			            </div>
			            <div class="panel-body">
			            	<div class="widget-content padding-10 clearfix bg-dashboard">
									<div class="pull-left white">
										<img src="img/icon/icon-dashboard/042-check.png" alt="" class="baru-img">
									</div>
									<div class="counter-dashboard-sales  counter text-right pull-right">
						        <div class="counter-number-group">
						          <span class="counter-number total_outstanding_delivery">0</span>
						        </div>
						        <div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_outstanding_sales') ?></div>
						        <!-- <a href="#" class="counter-label text-capitalize font-size-16" target="_blank">See Detail</a> -->
						      </div>
						    </div>
			            	<canvas id="outstanding_delivery"></canvas>
			            </div>
			      	</div>
			  	</div>
			  	<div class="col-sm-4 content-hide">
				  	<div class="panel panel-map panel-bordered">
			            <div class="panel-heading panel-gb-header">
			              <h3 class="panel-title"><?= $this->lang->line('lb_total_store_sales') ?></h3>
			              <div class="panel-actions panel-actions-keep">
			              	<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
			              	<div class="dropdown">
			                  	<a class="panel-action" id="examplePanelDropdown" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="icon wb-more-vertical" aria-hidden="true"></i></a>
			                  	<ul class="dropdown-menu animate" id="ul-sales-store" data-page="sales_store">
			                  		<li class="v1" data-type="days" onclick="selected_item('#ul-sales-store','.v1')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_days') ?></a>
			                  		</li>
			                  		<li class="v2" data-type="month" onclick="selected_item('#ul-sales-store','.v2')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_month') ?></a>
			                  		</li>
			                  		<li class="v3" data-type="year" onclick="selected_item('#ul-sales-store','.v3')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_year') ?></a>
			                  		</li>
			                  	</ul>
			                </div>
			              </div>
			            </div>
			            <div class="panel-body">
			            	<div class="widget-content padding-10 clearfix bg-dashboard">
									<div class="pull-left white">
										<img src="img/icon/icon-dashboard/005-shopping bag-min.png" alt="" class="baru-img">
									</div>
									<div class="counter-dashboard  counter text-right pull-right">
						        <div class="counter-number-group">
						          <span class="counter-number total_store_sales">0</span>
						        </div>
						        <div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_total_store_sales') ?></div>
						        <!-- <a href="#" class="counter-label text-capitalize font-size-16" target="_blank">See Detail</a> -->
						      </div>
						    </div>
			            	<canvas id="sales_store"></canvas>
			            </div>
			      	</div>
			  	</div>
			  	<div class="col-sm-4 content-hide">
				  	<div class="panel panel-map panel-bordered">
			            <div class="panel-heading panel-gb-header">
			              <h3 class="panel-title"><?= $this->lang->line('lb_total_store') ?></h3>
			              <div class="panel-actions panel-actions-keep">
			              	<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
			              </div>
			            </div>
			            <div class="panel-body">
			            	<div class="widget-content padding-10 clearfix bg-dashboard">
									<div class="pull-left white">
										<img src="img/icon/icon-dashboard/005-shopping bag-min.png" alt="" class="baru-img">
									</div>
									<div class="counter-dashboard  counter text-right pull-right">
						        <div class="counter-number-group">
						          <span class="counter-number total_store">0</span>
						        </div>
						        <div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_total_store') ?></div>
						        <!-- <a href="#" class="counter-label text-capitalize font-size-16" target="_blank">See Detail</a> -->
						      </div>
						    </div>
			            	<canvas id="sales_branch"></canvas>
			            </div>
			      	</div>
			  	</div>
			  	<div class="col-sm-4 content-hide">
				  	<div class="panel panel-map panel-bordered">
			            <div class="panel-heading panel-gb-header">
			              <h3 class="panel-title"><?= $this->lang->line('lb_total_customer') ?></h3>
			              <div class="panel-actions panel-actions-keep">
			              	<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
			              	<div class="dropdown">
			                  	<a class="panel-action" id="examplePanelDropdown" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="icon wb-more-vertical" aria-hidden="true"></i></a>
			                  	<ul class="dropdown-menu animate" id="ul-sales-customer" data-page="sales_customer">
			                  		<li class="v1" data-type="days" onclick="selected_item('#ul-sales-customer','.v1')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_days') ?></a>
			                  		</li>
			                  		<li class="v2" data-type="month" onclick="selected_item('#ul-sales-customer','.v2')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_month') ?></a>
			                  		</li>
			                  		<li class="v3" data-type="year" onclick="selected_item('#ul-sales-customer','.v3')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_year') ?></a>
			                  		</li>
			                  	</ul>
			                </div>
			              </div>
			            </div>
			            <div class="panel-body">
			            	<div class="widget-content padding-10 clearfix bg-dashboard">
									<div class="pull-left white">
										<img src="img/icon/icon-dashboard/005-shopping bag-min.png" alt="" class="baru-img">
									</div>
									<div class="counter-dashboard  counter text-right pull-right">
						        <div class="counter-number-group">
						          <span class="counter-number total_customer">0</span>
						        </div>
						        <div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_total_customer') ?></div>
						        <!-- <a href="#" class="counter-label text-capitalize font-size-16" target="_blank">See Detail</a> -->
						      </div>
						    </div>
						    <canvas id="total_sales_customer"></canvas>
			            </div>
			      	</div>
			  	</div>
			  	<div class="col-sm-4">
				  	<div class="panel panel-map panel-bordered">
			            <div class="panel-heading panel-gb-header">
			              <h3 class="panel-title"><?= $this->lang->line('lb_open_selling') ?></h3>
			              <div class="panel-actions panel-actions-keep">
			              	<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
			              	<div class="dropdown">
			                  	<a class="panel-action" id="examplePanelDropdown" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="icon wb-more-vertical" aria-hidden="true"></i></a>
			                  	<ul class="dropdown-menu animate" id="ul-sales-open" data-page="sales_open">
			                  		<li class="v1" data-type="days" onclick="selected_item('#ul-sales-open','.v1')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_days') ?></a>
			                  		</li>
			                  		<li class="v2" data-type="month" onclick="selected_item('#ul-sales-open','.v2')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_month') ?></a>
			                  		</li>
			                  		<li class="v3" data-type="year" onclick="selected_item('#ul-sales-open','.v3')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_year') ?></a>
			                  		</li>
			                  	</ul>
			                </div>
			              </div>
			            </div>
			            <div class="panel-body">
			            	<div class="widget-content padding-10 clearfix bg-dashboard">
									<div class="pull-left white">
										<img src="img/icon/icon-dashboard/035-shopping-cart.png" alt="" class="baru-img">
									</div>
									<div class="counter-dashboard  counter text-right pull-right">
						        <div class="counter-number-group">
						          <span class="counter-number total_sales_open">0</span>
						        </div>
						        <div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_total_open_selling') ?></div>
						        <!-- <a href="#" class="counter-label text-capitalize font-size-16" target="_blank">See Detail</a> -->
						      </div>
						    </div>
			            	<canvas id="sales_open"></canvas>
			            </div>
			      	</div>
			  	</div>
			  	<div class="col-sm-4">
				  	<div class="panel panel-map panel-bordered">
			            <div class="panel-heading panel-gb-header">
			              <h3 class="panel-title"><?= $this->lang->line('lb_overdude_selling') ?></h3>
			              <div class="panel-actions panel-actions-keep">
			              	<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
			              	<div class="dropdown">
			                  	<a class="panel-action" id="examplePanelDropdown" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="icon wb-more-vertical" aria-hidden="true"></i></a>
			                  	<ul class="dropdown-menu animate" id="ul-sales-overdude" data-page="sales_overdude">
			                  		<li class="v1" data-type="days" onclick="selected_item('#ul-sales-overdude','.v1')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_days') ?></a>
			                  		</li>
			                  		<li class="v2" data-type="month" onclick="selected_item('#ul-sales-overdude','.v2')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_month') ?></a>
			                  		</li>
			                  		<li class="v3" data-type="year" onclick="selected_item('#ul-sales-overdude','.v3')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_year') ?></a>
			                  		</li>
			                  	</ul>
			                </div>
			              </div>
			            </div>
			            <div class="panel-body">
			            	<div class="widget-content padding-10 clearfix bg-dashboard">
									<div class="pull-left white">
										<img src="img/icon/icon-dashboard/034-sale.png" alt="" class="baru-img">
									</div>
									<div class="counter-dashboard  counter text-right pull-right">
						        <div class="counter-number-group">
						          <span class="counter-number total_sales_overdude">0</span>
						        </div>
						        <div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_total_overdude_selling') ?></div>
						        <!-- <a href="#" class="counter-label text-capitalize font-size-16" target="_blank">See Detail</a> -->
						      </div>
						    </div>
			            	<canvas id="sales_overdude"></canvas>
			            </div>
			      	</div>
			  	</div>
			  	<div class="col-sm-4">
				  	<div class="panel panel-map panel-bordered">
			            <div class="panel-heading panel-gb-header">
			              <h3 class="panel-title"><?= $this->lang->line('lb_payment_receivable') ?></h3>
			              <div class="panel-actions panel-actions-keep">
			              	<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
			              	<div class="dropdown">
			                  	<a class="panel-action" id="examplePanelDropdown" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="icon wb-more-vertical" aria-hidden="true"></i></a>
			                  	<ul class="dropdown-menu animate" id="ul-sales-payment" data-page="sales_payment">
			                  		<li class="v1" data-type="days" onclick="selected_item('#ul-sales-payment','.v1')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_days') ?></a>
			                  		</li>
			                  		<li class="v2" data-type="month" onclick="selected_item('#ul-sales-payment','.v2')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_month') ?></a>
			                  		</li>
			                  		<li class="v3" data-type="year" onclick="selected_item('#ul-sales-payment','.v3')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_year') ?></a>
			                  		</li>
			                  	</ul>
			                </div>
			              </div>
			            </div>
			            <div class="panel-body">
			            	<div class="widget-content padding-10 clearfix bg-dashboard">
									<div class="pull-left white">
										<img src="img/icon/icon-dashboard/036-data.png" alt="" class="baru-img">
									</div>
									<div class="counter-dashboard  counter text-right pull-right">
						        <div class="counter-number-group">
						          <span class="counter-number total_sales_payment">0</span>
						        </div>
						        <div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_total_payment') ?></div>
						        <!-- <a href="#" class="counter-label text-capitalize font-size-16" target="_blank">See Detail</a> -->
						      </div>
						    </div>
			            	<canvas id="sales_payment"></canvas>
			            </div>
			      	</div>
			  	</div>
            </div>
      	</div>
	  </div>

	  <div class="col-sm-12 vap">
	  	<div class="panel panel-map panel-bordered">
            <div class="panel-heading">
              <h3 class="panel-title"><?= $this->lang->line('lb_purchase1') ?></h3>
              <div class="panel-actions panel-actions-keep">
              	<a class="panel-action icon wb-refresh" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="load_data('purchaseheader')"></a>
              	<a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a>
              </div>
            </div>
            <div class="panel-body">
            	<div class="col-sm-4 content-hide">
				  	<div class="panel panel-map panel-bordered">
			            <div class="panel-heading panel-gb-header">
			              <h3 class="panel-title"><?= $this->lang->line('Total Purchase Quantity') ?></h3>
			              <div class="panel-actions panel-actions-keep">
			              	<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
			              	<div class="dropdown">
			                  <a class="panel-action" id="examplePanelDropdown" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="icon wb-more-vertical" aria-hidden="true"></i></a>
			                  	<ul class="dropdown-menu animate" id="ul-purchase-order" data-page="purchase_order">
			                  		<li class="v1" data-type="days" onclick="selected_item('#ul-purchase-order','.v1')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_days') ?></a>
			                  		</li>
			                  		<li class="v2" data-type="month" onclick="selected_item('#ul-purchase-order','.v2')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_month') ?></a>
			                  		</li>
			                  		<li class="v3" data-type="year" onclick="selected_item('#ul-purchase-order','.v3')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_year') ?></a>
			                  		</li>
			                  	</ul>
			                </div>
			              </div>
			            </div>
			            <div class="panel-body">
			            	<div class="widget-content padding-10 clearfix bg-dashboard">
									<div class="pull-left white">
										<img src="img/icon/icon-dashboard/005-shopping bag-min.png" alt="" class="baru-img">
									</div>
									<div class="counter-dashboard  counter text-right pull-right">
						        <div class="counter-number-group">
						          <span class="counter-number total_purchase_qty">0</span>
						        </div>
						        <div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('Total Purchase Quantity') ?></div>
						        <!-- <a href="#" class="counter-label text-capitalize font-size-16" target="_blank">See Detail</a> -->
						      </div>
						    </div>
			            	<canvas id="purchase_order"></canvas>
			            </div>
			      	</div>
			  	</div>

			  	<div class="col-sm-4 content-hide">
				  	<div class="panel panel-map panel-bordered">
			            <div class="panel-heading panel-gb-header">
			              <h3 class="panel-title"><?= $this->lang->line('lb_total_purchse_trans') ?></h3>
			              <div class="panel-actions panel-actions-keep">
			              	<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
			              	<div class="dropdown">
			                  <a class="panel-action" id="examplePanelDropdown" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="icon wb-more-vertical" aria-hidden="true"></i></a>
			                  	<ul class="dropdown-menu animate" id="ul-purchase-transaction" data-page="purchase_transaction">
			                  		<li class="v1" data-type="days" onclick="selected_item('#ul-purchase-transaction','.v1')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_days') ?></a>
			                  		</li>
			                  		<li class="v2" data-type="month" onclick="selected_item('#ul-purchase-transaction','.v2')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_month') ?></a>
			                  		</li>
			                  		<li class="v3" data-type="year" onclick="selected_item('#ul-purchase-transaction','.v3')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_year') ?></a>
			                  		</li>
			                  	</ul>
			                </div>
			              </div>
			            </div>
			            <div class="panel-body">
			            	<div class="widget-content padding-10 clearfix bg-dashboard">
									<div class="pull-left white">
										<img src="img/icon/icon-dashboard/005-shopping bag-min.png" alt="" class="baru-img">
									</div>
									<div class="counter-dashboard  counter text-right pull-right">
						        <div class="counter-number-group">
						          <span class="counter-number total_purchase_transaction">0</span>
						        </div>
						        <div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_total_purchse_trans') ?></div>
						        <!-- <a href="#" class="counter-label text-capitalize font-size-16" target="_blank">See Detail</a> -->
						      </div>
						    </div>
			            	<canvas id="purchase_transaction"></canvas>
			            </div>
			      	</div>
			  	</div>

			  	<div class="col-sm-4 content-hide">
				  	<div class="panel panel-map panel-bordered">
			            <div class="panel-heading panel-gb-header">
			              <h3 class="panel-title"><?= $this->lang->line('lb_total_product_return') ?></h3>
			              <div class="panel-actions panel-actions-keep">
			              	<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
			              	<div class="dropdown">
			                  <a class="panel-action" id="examplePanelDropdown" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="icon wb-more-vertical" aria-hidden="true"></i></a>
			                  	<ul class="dropdown-menu animate" id="ul-purchase-return" data-page="purchase_return">
			                  		<li class="v1" data-type="days" onclick="selected_item('#ul-purchase-return','.v1')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_days') ?></a>
			                  		</li>
			                  		<li class="v2" data-type="month" onclick="selected_item('#ul-purchase-return','.v2')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_month') ?></a>
			                  		</li>
			                  		<li class="v3" data-type="year" onclick="selected_item('#ul-purchase-return','.v3')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_year') ?></a>
			                  		</li>
			                  	</ul>
			                </div>
			              </div>
			            </div>
			            <div class="panel-body">
			            	<div class="widget-content padding-10 clearfix bg-dashboard">
									<div class="pull-left white">
										<img src="img/icon/icon-dashboard/005-shopping bag-min.png" alt="" class="baru-img">
									</div>
									<div class="counter-dashboard  counter text-right pull-right">
						        <div class="counter-number-group">
						          <span class="counter-number total_purchase_transaction">0</span>
						        </div>
						        <div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_total_product_return') ?></div>
						        <!-- <a href="#" class="counter-label text-capitalize font-size-16" target="_blank">See Detail</a> -->
						      </div>
						    </div>
			            	<canvas id="purchase_return"></canvas>
			            </div>
			      	</div>
			  	</div>

			  	<div class="col-sm-4">
				  	<div class="panel panel-map panel-bordered">
			            <div class="panel-heading panel-gb-header">
			              <h3 class="panel-title"><?= $this->lang->line('lb_open_purchase') ?></h3>
			              <div class="panel-actions panel-actions-keep">
			              	<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
			              	<div class="dropdown">
			                  <a class="panel-action" id="examplePanelDropdown" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="icon wb-more-vertical" aria-hidden="true"></i></a>
			                  	<ul class="dropdown-menu animate" id="ul-purchase-open" data-page="purchase_open">
			                  		<li class="v1" data-type="days" onclick="selected_item('#ul-purchase-open','.v1')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_days') ?></a>
			                  		</li>
			                  		<li class="v2" data-type="month" onclick="selected_item('#ul-purchase-open','.v2')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_month') ?></a>
			                  		</li>
			                  		<li class="v3" data-type="year" onclick="selected_item('#ul-purchase-open','.v3')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_year') ?></a>
			                  		</li>
			                  	</ul>
			                </div>
			              </div>
			            </div>
			            <div class="panel-body">
			            	<div class="widget-content padding-10 clearfix bg-dashboard">
									<div class="pull-left white">
										<img src="img/icon/icon-dashboard/022-open-purchase.png" alt="" class="baru-img">
									</div>
									<div class="counter-dashboard  counter text-right pull-right">
						        <div class="counter-number-group">
						          <span class="counter-number total_purchase_open">0</span>
						        </div>
						        <div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_open_purchase_total') ?></div>
						        <!-- <a href="#" class="counter-label text-capitalize font-size-16" target="_blank">See Detail</a> -->
						      </div>
						    </div>
			            	<canvas id="purchase_open"></canvas>
			            </div>
			      	</div>
			  	</div>

			  	<div class="col-sm-4">
				  	<div class="panel panel-map panel-bordered">
			            <div class="panel-heading panel-gb-header">
			              <h3 class="panel-title"><?= $this->lang->line('lb_overdude_purchase') ?></h3>
			              <div class="panel-actions panel-actions-keep">
			              	<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
			              	<div class="dropdown">
			                  <a class="panel-action" id="examplePanelDropdown" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="icon wb-more-vertical" aria-hidden="true"></i></a>
			                  	<ul class="dropdown-menu animate" id="ul-purchase-overdude" data-page="purchase_overdude">
			                  		<li class="v1" data-type="days" onclick="selected_item('#ul-purchase-overdude','.v1')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_days') ?></a>
			                  		</li>
			                  		<li class="v2" data-type="month" onclick="selected_item('#ul-purchase-overdude','.v2')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_month') ?></a>
			                  		</li>
			                  		<li class="v3" data-type="year" onclick="selected_item('#ul-purchase-overdude','.v3')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_year') ?></a>
			                  		</li>
			                  	</ul>
			                </div>
			              </div>
			            </div>
			            <div class="panel-body">
			            	<div class="widget-content padding-10 clearfix bg-dashboard">
									<div class="pull-left white">
										<img src="img/icon/icon-dashboard/023-overdue-purchase.png" alt="" class="baru-img">
									</div>
									<div class="counter-dashboard  counter text-right pull-right">
						        <div class="counter-number-group">
						          <span class="counter-number total_purchase_overdude">0</span>
						        </div>
						        <div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_overdude_purchase_total') ?></div>
						        <!-- <a href="#" class="counter-label text-capitalize font-size-16" target="_blank">See Detail</a> -->
						      </div>
						    </div>
			            	<canvas id="purchase_overdude"></canvas>
			            </div>
			      	</div>
			  	</div>

			  	<div class="col-sm-4">
				  	<div class="panel panel-map panel-bordered">
			            <div class="panel-heading panel-gb-header">
			              <h3 class="panel-title"><?= $this->lang->line('lb_payment_purchase') ?></h3>
			              <div class="panel-actions panel-actions-keep">
			              	<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
			              	<div class="dropdown">
			                  <a class="panel-action" id="examplePanelDropdown" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="icon wb-more-vertical" aria-hidden="true"></i></a>
			                  	<ul class="dropdown-menu animate" id="ul-purchase-payment" data-page="purchase_payment">
			                  		<li class="v1" data-type="days" onclick="selected_item('#ul-purchase-payment','.v1')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_days') ?></a>
			                  		</li>
			                  		<li class="v2" data-type="month" onclick="selected_item('#ul-purchase-payment','.v2')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_month') ?></a>
			                  		</li>
			                  		<li class="v3" data-type="year" onclick="selected_item('#ul-purchase-payment','.v3')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_year') ?></a>
			                  		</li>
			                  	</ul>
			                </div>
			              </div>
			            </div>
			            <div class="panel-body">
			            	<div class="widget-content padding-10 clearfix bg-dashboard">
									<div class="pull-left white">
										<img src="img/icon/icon-dashboard/030-payment method.png" alt="" class="baru-img">
									</div>
									<div class="counter-dashboard  counter text-right pull-right">
						        <div class="counter-number-group">
						          <span class="counter-number total_purchase_payment">0</span>
						        </div>
						        <div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_payment_purchase_total') ?></div>
						        <!-- <a href="#" class="counter-label text-capitalize font-size-16" target="_blank">See Detail</a> -->
						      </div>
						    </div>
			            	<canvas id="purchase_payment"></canvas>
			            </div>
			      	</div>
			  	</div>
            </div>
        </div>
      </div>

      <div class="col-sm-12 vinventory">
	  	<div class="panel panel-map panel-bordered">
            <div class="panel-heading">
              <h3 class="panel-title"><?= $this->lang->line('lb_inventory') ?></h3>
              <div class="panel-actions panel-actions-keep">
              	<a class="panel-action icon wb-refresh" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="load_data('inventory')"></a>
              	<a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a>
              </div>
            </div>
            <div class="panel-body">
            	<div class="col-sm-6">
				  	<div class="panel panel-map panel-bordered">
			            <div class="panel-heading panel-gb-header">
			              <h3 class="panel-title"><?= $this->lang->line('lb_total_stock') ?></h3>
			              <div class="panel-actions panel-actions-keep">
			              	<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
			              </div>
			            </div>
			            <div class="panel-body">
			            	<div class="widget-content padding-10 clearfix bg-dashboard">
									<div class="pull-left white">
										<img src="img/icon/icon-dashboard/041-stock.png" alt="" class="baru-img">
									</div>
									<div class="counter-dashboard  counter text-right pull-right">
						        <div class="counter-number-group">
						          <span class="counter-number total_stock">0</span>
						        </div>
						        <div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_total_stock') ?></div>
						        <!-- <a href="#" class="counter-label text-capitalize font-size-16" target="_blank">See Detail</a> -->
						      </div>
						    </div>
						    <canvas id="stock_branch"></canvas>
			            </div>
			        </div>
			    </div>

            	<div class="col-sm-6">
				  	<div class="panel panel-map panel-bordered">
			            <div class="panel-heading panel-gb-header">
			              <h3 class="panel-title"><?= $this->lang->line('lb_stock_minimal') ?></h3>
			              <div class="panel-actions panel-actions-keep">
			              	<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
			              </div>
			            </div>
			            <div class="panel-body">
			            	<table id="table-minimal-stock" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
			            		<thead>
			            			<tr>
			            				<th><?= $this->lang->line('lb_no') ?></th>
			            				<th><?= $this->lang->line('lb_product_code') ?></th>
			            				<th><?= $this->lang->line('lb_product_name') ?></th>
			            				<th><?= $this->lang->line('lb_minimal_stock') ?></th>
			            				<th><?= $this->lang->line('lb_residu') ?></th>
			            			</tr>
			            		</thead>
			            		<tbody></tbody>
			            	</table>
			            </div>
			      	</div>
			  	</div>
			  	<div class="col-sm-6">
				  	<div class="panel panel-map panel-bordered">
			            <div class="panel-heading panel-gb-header">
			              <h3 class="panel-title"><?= $this->lang->line('lb_total_product') ?></h3>
			              <div class="panel-actions panel-actions-keep">
			              	<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
			              </div>
			            </div>
			            <div class="panel-body">
			            	<div class="widget-content padding-10 clearfix bg-dashboard">
									<div class="pull-left white">
										<img src="img/icon/icon-dashboard/037-product.png" alt="" class="baru-img">
									</div>
									<div class="counter-dashboard  counter text-right pull-right">
						        <div class="counter-number-group">
						          <span class="counter-number total_product">0</span>
						        </div>
						        <div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_total_product') ?></div>
						        <!-- <a href="#" class="counter-label text-capitalize font-size-16" target="_blank">See Detail</a> -->
						      </div>
						    </div>
			            </div>
			        </div>
			    </div>
            </div>
        </div>
      </div>

      <div class="col-sm-12 vac content-hide">
	  	<div class="panel panel-map panel-bordered">
            <div class="panel-heading">
              <h3 class="panel-title"><?= $this->lang->line('lb_ac') ?></h3>
              <div class="panel-actions panel-actions-keep">
              	<a class="panel-action icon wb-refresh" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="load_data('accounting')"></a>
              	<a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a>
              </div>
            </div>
            <div class="panel-body">
            	<div class="col-sm-4">
				  	<div class="panel panel-map panel-bordered">
			            <div class="panel-heading panel-gb-header">
			              <h3 class="panel-title"><?= $this->lang->line('lb_total_omset') ?></h3>
			              <div class="panel-actions panel-actions-keep">
			              	<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
			              	<div class="dropdown">
			                  	<a class="panel-action" id="examplePanelDropdown" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="icon wb-more-vertical" aria-hidden="true"></i></a>
			                  	<ul class="dropdown-menu animate" id="ul-sales-cost" data-page="omset_cost">
			                  		<li class="v1" data-type="days" onclick="selected_item('#ul-sales-cost','.v1')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_days') ?></a>
			                  		</li>
			                  		<li class="v2" data-type="month" onclick="selected_item('#ul-sales-cost','.v2')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_month') ?></a>
			                  		</li>
			                  		<li class="v3" data-type="year" onclick="selected_item('#ul-sales-cost','.v3')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_year') ?></a>
			                  		</li>
			                  	</ul>
			                </div>
			              </div>
			            </div>
			            <div class="panel-body">
			            	<div class="widget-content padding-10 clearfix bg-dashboard">
									<div class="pull-left white">
										<img src="img/icon/icon-dashboard/005-shopping bag-min.png" alt="" class="baru-img">
									</div>
									<div class="counter-dashboard  counter text-right pull-right">
						        <div class="counter-number-group">
						          <span class="counter-number total_net_omset">0</span>
						        </div>
						        <div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_total_omset') ?></div>
						        <!-- <a href="#" class="counter-label text-capitalize font-size-16" target="_blank">See Detail</a> -->
						      </div>
						    </div>
						    <canvas id="omset_cost"></canvas>
			            </div>
			        </div>
			    </div>

			    <div class="col-sm-4">
				  	<div class="panel panel-map panel-bordered">
			            <div class="panel-heading panel-gb-header">
			              <h3 class="panel-title"><?= $this->lang->line('lb_total_sales_store_ar') ?></h3>
			              <div class="panel-actions panel-actions-keep">
			              	<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
			              	<div class="dropdown">
			                  	<a class="panel-action" id="examplePanelDropdown" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="icon wb-more-vertical" aria-hidden="true"></i></a>
			                  	<ul class="dropdown-menu animate" id="ul-store_receivable" data-page="store_receivable">
			                  		<li class="v1" data-type="days" onclick="selected_item('#ul-store_receivable','.v1')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_days') ?></a>
			                  		</li>
			                  		<li class="v2" data-type="month" onclick="selected_item('#ul-store_receivable','.v2')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_month') ?></a>
			                  		</li>
			                  		<li class="v3" data-type="year" onclick="selected_item('#ul-store_receivable','.v3')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_year') ?></a>
			                  		</li>
			                  	</ul>
			                </div>
			              </div>
			            </div>
			            <div class="panel-body">
			            	<div class="widget-content padding-10 clearfix bg-dashboard">
									<div class="pull-left white">
										<img src="img/icon/icon-dashboard/005-shopping bag-min.png" alt="" class="baru-img">
									</div>
									<div class="counter-dashboard  counter text-right pull-right">
						        <div class="counter-number-group">
						          <span class="counter-number total_store_receivable">0</span>
						        </div>
						        <div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_total_sales_store_ar') ?></div>
						        <!-- <a href="#" class="counter-label text-capitalize font-size-16" target="_blank">See Detail</a> -->
						      </div>
						    </div>
						    <canvas id="store_receivable"></canvas>
			            </div>
			        </div>
			    </div>

			    <div class="col-sm-4 content-hide">
				  	<div class="panel panel-map panel-bordered">
			            <div class="panel-heading panel-gb-header">
			              <h3 class="panel-title"><?= $this->lang->line('lb_total_net_profit') ?></h3>
			              <div class="panel-actions panel-actions-keep">
			              	<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
			              	<div class="dropdown">
			                  	<a class="panel-action" id="examplePanelDropdown" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="icon wb-more-vertical" aria-hidden="true"></i></a>
			                  	<ul class="dropdown-menu animate" id="ul-net_profit" data-page="net_profit">
			                  		<li class="v1" data-type="days" onclick="selected_item('#ul-net_profit','.v1')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_days') ?></a>
			                  		</li>
			                  		<li class="v2" data-type="month" onclick="selected_item('#ul-net_profit','.v2')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_month') ?></a>
			                  		</li>
			                  		<li class="v3" data-type="year" onclick="selected_item('#ul-net_profit','.v3')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_year') ?></a>
			                  		</li>
			                  	</ul>
			                </div>
			              </div>
			            </div>
			            <div class="panel-body">
			            	<div class="widget-content padding-10 clearfix bg-dashboard">
									<div class="pull-left white">
										<img src="img/icon/icon-dashboard/005-shopping bag-min.png" alt="" class="baru-img">
									</div>
									<div class="counter-dashboard  counter text-right pull-right">
						        <div class="counter-number-group">
						          <span class="counter-number total_net_profit">0</span>
						        </div>
						        <div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_total_net_profit') ?></div>
						        <!-- <a href="#" class="counter-label text-capitalize font-size-16" target="_blank">See Detail</a> -->
						      </div>
						    </div>
						    <canvas id="net_profit"></canvas>
			            </div>
			        </div>
			    </div>

			    <div class="col-sm-4 content-hide">
				  	<div class="panel panel-map panel-bordered">
			            <div class="panel-heading panel-gb-header">
			              <h3 class="panel-title"><?= $this->lang->line('lb_total_net_balance') ?></h3>
			              <div class="panel-actions panel-actions-keep">
			              	<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
			              	<div class="dropdown">
			                  	<a class="panel-action" id="examplePanelDropdown" data-toggle="dropdown" href="#" aria-expanded="false" role="button"><i class="icon wb-more-vertical" aria-hidden="true"></i></a>
			                  	<ul class="dropdown-menu animate" id="ul-balance_sheet" data-page="balance_sheet">
			                  		<li class="v1" data-type="days" onclick="selected_item('#ul-balance_sheet','.v1')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_days') ?></a>
			                  		</li>
			                  		<li class="v2" data-type="month" onclick="selected_item('#ul-balance_sheet','.v2')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_month') ?></a>
			                  		</li>
			                  		<li class="v3" data-type="year" onclick="selected_item('#ul-balance_sheet','.v3')">
			                  			<a href="javascript:void(0)" type="button"><i class="icon fa-calendar" aria-hidden="true"></i><?= $this->lang->line('lb_year') ?></a>
			                  		</li>
			                  	</ul>
			                </div>
			              </div>
			            </div>
			            <div class="panel-body">
			            	<div class="widget-content padding-10 clearfix bg-dashboard">
									<div class="pull-left white">
										<img src="img/icon/icon-dashboard/005-shopping bag-min.png" alt="" class="baru-img">
									</div>
									<div class="counter-dashboard  counter text-right pull-right">
						        <div class="counter-number-group">
						          <span class="counter-number total_balance_sheet">0</span>
						        </div>
						        <div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_total_net_balance') ?></div>
						        <!-- <a href="#" class="counter-label text-capitalize font-size-16" target="_blank">See Detail</a> -->
						      </div>
						    </div>
						    <canvas id="balance_sheet"></canvas>
			            </div>
			        </div>
			    </div>

			    <div class="col-sm-4">
				  	<div class="panel panel-map panel-bordered">
			            <div class="panel-heading panel-gb-header">
			              <h3 class="panel-title"><?= $this->lang->line('lb_ac_watchlist') ?></h3>
			              <div class="panel-actions panel-actions-keep">
			              	<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
			              </div>
			            </div>
			            <div class="panel-body">
			            	<table id="table_account_watchlist" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
					           <thead>
			            			<tr>
			            				<th><?= $this->lang->line('lb_no') ?></th>
			            				<th><?= $this->lang->line('lb_bank_acount') ?></th>
			            				<th><?= $this->lang->line('lb_total') ?></th>
			            			</tr>
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

		<div class="col-sm-6">
	  	<div class="panel panel-map panel-bordered">
            <div class="panel-heading">
              <h3 class="panel-title"><?= $this->lang->line('lb_sales_by_hour') ?></h3>
            </div>
            <div class="panel-body">
            	<canvas id="sales_hour"></canvas>
            </div>
      	</div>
	  </div>
	  
	  <div class="col-sm-12">
	    <div class="panel panel-map panel-bordered">
            <div class="panel-heading">
              <h3 class="panel-title"><?= $this->lang->line('lb_store_location') ?></h3>
            </div>
            <div class="panel-body">
             <div id="map" style="height: 500px;width: 100%"></div>
            </div>
          </div>
	  </div>
	</div>
  </div>
</div>
</div>
<?php $this->load->view("modal/modal_dashboard"); ?>
<?php $this->load->view("modal/modal_dashboard_expire");?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= $this->config->item("gmap_api"); ?>"></script>
<script src="<?= base_url('aset/plugin/Chart.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url("aset/datepicker/bootstrap-datepicker3.css"); ?>"/>
<script type="text/javascript" src="<?= base_url("aset/datepicker/bootstrap-datepicker.min.js"); ?>"></script>
<script src="<?= base_url('aset/js/page/dashboard.js'); ?>"></script>

