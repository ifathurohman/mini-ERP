<style type="text/css">
	canvas{
		display: none !important;
	}
</style>
<!-- dashboard -->
<div id="dashboard" class="">
	<div class="dashboard-body">
		<div class="row">
			<div class="panel panel-map panel-bordered">
				<div class="col-sm-4">
					<div class="panel panel-map panel-bordered">
						<div class="panel-heading panel-gb-header content-hide">
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
						<div class="widget-content clearfix">
						   <div class="panel panel-map panel-bordered bg-gradient2">
						      <div class="box-top-blue bg-dashboard">
						         <div class="panel-body bg-transanparant">
						            <div class="mini-stat clearfix bx-shadow">
						               <span class="mini-stat-icon bg-info"><img src="http://qa.pipesys.rcelectronic.co.id/img/icon/icon-dashboard/035-shopping-cart.png" class="product-img"></span>
						               <div class="mini-stat-info text-right text-muted">
						                  <span class="counter-number total_sales_open">0</span>
						                  <?= $this->lang->line('lb_total_open_selling') ?>
						               </div>
						            </div>
						         </div>
						      </div>
						   </div>
						</div>
						<canvas id="sales_open"></canvas>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="panel panel-map panel-bordered">
						 <div class="panel-heading panel-gb-header content-hide">
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
						<div class="widget-content clearfix">
						   <div class="panel panel-map panel-bordered bg-gradient2">
						      <div class="box-top-blue bg-dashboard">
						         <div class="panel-body bg-transanparant">
						            <div class="mini-stat clearfix bx-shadow">
						               <span class="mini-stat-icon bg-info"><img src="http://qa.pipesys.rcelectronic.co.id/img/icon/icon-dashboard/034-sale.png" class="product-img"></span>
						               <div class="mini-stat-info text-right text-muted">
						                  <span class="counter-number total_sales_overdude">0</span>
						                  <?= $this->lang->line('lb_total_overdude_selling') ?>
						               </div>
						            </div>
						         </div>
						      </div>
						   </div>
						</div>
						<canvas id="sales_overdude"></canvas>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="panel panel-map panel-bordered">
						<div class="panel-heading panel-gb-header content-hide">
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
						<div class="widget-content clearfix">
						   <div class="panel panel-map panel-bordered bg-gradient2">
						      <div class="box-top-blue bg-dashboard">
						         <div class="panel-body bg-transanparant">
						            <div class="mini-stat clearfix bx-shadow">
						               <span class="mini-stat-icon bg-info"><img src="http://qa.pipesys.rcelectronic.co.id/img/icon/icon-dashboard/036-data.png" class="product-img"></span>
						               <div class="mini-stat-info text-right text-muted">
						                  <span class="counter-number total_sales_payment">0</span>
						                  <?= $this->lang->line('lb_total_payment') ?>
						               </div>
						            </div>
						         </div>
						      </div>
						   </div>
						</div>
						<canvas id="sales_payment"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<style>
	.panel-map .panel-body {
	    padding-top: 0px;
	    padding: 0px;
	    min-height: 16.3vh	;
	    overflow: hidden;
	}
	.product-img {
	    position: absolute;
	    left: 0px;
	    margin-top: 0%;
	    margin-left: 0%;
	    width: 30%;
	}
	.mini-stat-info {
	    padding-top: 2px;
	}
	.mini-stat-info span {
	    display: block;
	    font-size: 19px;
	    font-weight: 600;
	    color: #fff;
	    padding-top: 21px;
	}
	.text-muted {
	    color: #ffffff;
	    font-weight: 300;
	    font-family: Roboto,sans-serif;
	}
	.counter {
	    text-align: right; 
	}
	.progress.progress-sm {
	    height: 5px !important;
	}
	.progress {
	    overflow: hidden;
	    /*margin-bottom: 18px;*/
	    background-color: #f5f5f5;
	    /*-webkit-box-shadow: none !important;*/
	    box-shadow: none !important;
	    /*height: 10px;*/
	}
</style>