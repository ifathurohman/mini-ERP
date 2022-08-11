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
							<h3 class="panel-title">
								<?= $this->lang->line('lb_total_purchse_trans') ?>
							</h3>
							<div class="panel-actions panel-actions-keep">
								<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
								<div class="dropdown">
									<a class="panel-action" id="examplePanelDropdown" data-toggle="dropdown" href="#" aria-expanded="false" role="button">
										<i class="icon wb-more-vertical" aria-hidden="true"></i>
									</a>
									<ul class="dropdown-menu animate" id="ul-purchase-transaction" data-page="purchase_transaction">
										<li class="v1" data-type="days" onclick="selected_item('#ul-purchase-transaction','.v1')">
											<a href="javascript:void(0)" type="button">
												<i class="icon fa-calendar" aria-hidden="true"></i>
												<?= $this->lang->line('lb_days') ?>
											</a>
										</li>
										<li class="v2" data-type="month" onclick="selected_item('#ul-purchase-transaction','.v2')">
											<a href="javascript:void(0)" type="button">
												<i class="icon fa-calendar" aria-hidden="true"></i>
												<?= $this->lang->line('lb_month') ?>
											</a>
										</li>
										<li class="v3" data-type="year" onclick="selected_item('#ul-purchase-transaction','.v3')">
											<a href="javascript:void(0)" type="button">
												<i class="icon fa-calendar" aria-hidden="true"></i>
												<?= $this->lang->line('lb_year') ?>
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="widget-content clearfix bg-dashboard">
						   <div class="panel panel-map panel-bordered bg-gradient2">
						      <div class="box-top-blue bg-dashboard">
						         <div class="panel-body bg-transanparant">
						            <div class="mini-stat clearfix bx-shadow">
						               <span class="mini-stat-icon bg-info"><img src="http://qa.pipesys.rcelectronic.co.id/img/icon/icon-dashboard/005-shopping bag-min.png" class="product-img"></span>
						               <div class="mini-stat-info text-right text-muted">
						                  <span class="counter-number total_purchase_transaction">0</span>
						                  <span class="mini-title2"><?= $this->lang->line('lb_total_purchse_trans') ?></span>
						                  <span style="padding: 0" class="total_purchase_rp"></span>
						               </div>
						            </div>
						         </div>
						      </div>
						   </div>
						</div>
						<canvas id="purchase_transaction"></canvas>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="panel panel-map panel-bordered">
						<div class="panel-heading panel-gb-header content-hide">
							<h3 class="panel-title">
								<?= $this->lang->line('lb_total_penerimaan_trans') ?>
							</h3>
							<div class="panel-actions panel-actions-keep">
								<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
								<div class="dropdown">
									<a class="panel-action" id="examplePanelDropdown" data-toggle="dropdown" href="#" aria-expanded="false" role="button">
										<i class="icon wb-more-vertical" aria-hidden="true"></i>
									</a>
									<ul class="dropdown-menu animate" id="ul-goodreceipt" data-page="goodreceipt">
										<li class="v1" data-type="days" onclick="selected_item('#ul-goodreceipt','.v1')">
											<a href="javascript:void(0)" type="button">
												<i class="icon fa-calendar" aria-hidden="true"></i>
												<?= $this->lang->line('lb_days') ?>
											</a>
										</li>
										<li class="v2" data-type="month" onclick="selected_item('#ul-goodreceipt','.v2')">
											<a href="javascript:void(0)" type="button">
												<i class="icon fa-calendar" aria-hidden="true"></i>
												<?= $this->lang->line('lb_month') ?>
											</a>
										</li>
										<li class="v3" data-type="year" onclick="selected_item('#ul-goodreceipt','.v3')">
											<a href="javascript:void(0)" type="button">
												<i class="icon fa-calendar" aria-hidden="true"></i>
												<?= $this->lang->line('lb_year') ?>
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="widget-content clearfix bg-dashboard">
						   <div class="panel panel-map panel-bordered bg-gradient3">
						      <div class="box-top-blue bg-dashboard">
						         <div class="panel-body bg-transanparant">
						            <div class="mini-stat clearfix bx-shadow">
						               <span class="mini-stat-icon bg-info"><img src="http://qa.pipesys.rcelectronic.co.id/img/icon/icon-dashboard/006-bill-min.png" class="product-img"></span>
						               <div class="mini-stat-info text-right text-muted">
						                  <span class="counter-number total_goodreceipt">0</span>
						                  <span class="mini-title2"><?= $this->lang->line('lb_total_penerimaan_trans') ?></span>
						                  <span style="padding: 0" class="total_receipt_rp"></span>
						               </div>
						            </div>
						         </div>
						      </div>
						   </div>
						</div>
						<canvas id="goodreceipt"></canvas>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="panel panel-map panel-bordered">
						<div class="panel-heading panel-gb-header content-hide">
							<h3 class="panel-title">
								<?= $this->lang->line('lb_total_product_return') ?>
							</h3>
							<div class="panel-actions panel-actions-keep">
								<!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
								<div class="dropdown">
									<a class="panel-action" id="examplePanelDropdown" data-toggle="dropdown" href="#" aria-expanded="false" role="button">
										<i class="icon wb-more-vertical" aria-hidden="true"></i>
									</a>
									<ul class="dropdown-menu animate" id="ul-purchase-return" data-page="purchase_return">
										<li class="v1" data-type="days" onclick="selected_item('#ul-purchase-return','.v1')">
											<a href="javascript:void(0)" type="button">
												<i class="icon fa-calendar" aria-hidden="true"></i>
												<?= $this->lang->line('lb_days') ?>
											</a>
										</li>
										<li class="v2" data-type="month" onclick="selected_item('#ul-purchase-return','.v2')">
											<a href="javascript:void(0)" type="button">
												<i class="icon fa-calendar" aria-hidden="true"></i>
												<?= $this->lang->line('lb_month') ?>
											</a>
										</li>
										<li class="v3" data-type="year" onclick="selected_item('#ul-purchase-return','.v3')">
											<a href="javascript:void(0)" type="button">
												<i class="icon fa-calendar" aria-hidden="true"></i>
												<?= $this->lang->line('lb_year') ?>
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="widget-content clearfix bg-dashboard">
						   <div class="panel panel-map panel-bordered bg-gradient9">
						      <div class="box-top-blue bg-dashboard">
						         <div class="panel-body bg-transanparant">
						            <div class="mini-stat clearfix bx-shadow">
						               <span class="mini-stat-icon bg-info"><img src="http://qa.pipesys.rcelectronic.co.id/img/icon/icon-dashboard/021-purchase-return.png" class="product-img"></span>
						               <div class="mini-stat-info text-right text-muted">
						                  <span class="counter-number total_purchase_return">0</span>
						                  <span class="mini-title2"><?= $this->lang->line('lb_total_product_return') ?></span>
						                  <span style="padding: 0" class="lb_total_return_rp"></span>
						               </div>
						            </div>
						         </div>
						      </div>
						   </div>
						</div>
						<canvas id="purchase_return"></canvas>
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
	    overflow: hidden;
	}
	.product-img {
	    position: absolute;
	    left: 0px;
	    margin-top: 0%;
	    margin-left: 0%;
	    width: 26%;
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
	    background-color: #f5f5f5;
	    box-shadow: none !important;
	}
</style>

<script src="<?= base_url('aset/js/page/dashboard_purchase.js'.$this->main->js_css_version()) ?>"></script>