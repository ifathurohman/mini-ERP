<style type="text/css">
	canvas{
		/*height: 397px !important;*/
	}
</style>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.5/css/swiper.min.css'>
<div class="page page-dashboard page-data" data-hakakses="<?= $this->session->hak_akses; ?>" data-app="pipesys" data-currentdate="<?= date("Y-m-d") ?>" data-startdate="<?= date("Y-m-01") ?>" data-ap="<?= $ap ?>" data-ar="<?= $ar ?>" data-ac="<?= $ac ?>" data-inventory="<?= $inventory ?>">
	<div class="page-content p-0">
		<div class="col-sm-12" style="margin: 2% 0% 0% -1%;">
			<form id="form-filter" autocomplete="off" method="post">
			  <div class="row">
			  	<div class="form-group col-sm-3"></div>
			  	<div class="form-group col-sm-3"></div>
			    <div class="form-group col-sm-3 w-100">
			      <label class="control-label"><?= $this->lang->line('lb_startdate') ?></label>
			      <div class="input-group">
			        <span class="input-group-addon">
			          <i class="fa-calendar" aria-hidden="true"></i>
			        </span>
			        <input type="text" name="fStartDate" class="form-control date pointer" placeholder="<?= $this->lang->line('lb_startdate_select') ?>" id="fStartDate" maxlength="50" readonly="readonly">
			        <span class="input-group-addon pointer" title="Remove Start Date" onclick="remove_value('fStartDate')">
			          <i class="fa-times" aria-hidden="true"></i>
			        </span>
			        <span style="visibility: hidden;" class="input-group-addon pointer" title="Remove Start Date" onclick="remove_value('fStartDate')">
			          <i class="fa-times" aria-hidden="true"></i>
			        </span>
			      </div>
			      <span class="help-block"></span>
			    </div>
			    <div class="form-group col-sm-3 w-100">
			      <label class="control-label"><?= $this->lang->line('lb_enddate') ?></label>
			      <div class="input-group">
			        <span class="input-group-addon">
			          <i class="fa-calendar" aria-hidden="true"></i>
			        </span>
			        <input type="text" name="fEndDate" class="form-control date pointer" placeholder="<?= $this->lang->line('lb_enddate_select') ?>" id="fEndDate" maxlength="50" readonly="readonly">
			        <span class="input-group-addon pointer" title="Remove End Date" onclick="remove_value('fEndDate')">
			          <i class="fa-times" aria-hidden="true"></i>
			        </span>
			        <span class="input-group-addon pointer" title="Search Data" onclick="s_load_data()">
			          <i class="fa-search" aria-hidden="true"></i>
			        </span>
			      </div>
			      <span class="help-block"></span>
			    </div>
			  </div>
			</form>
		</div>
		<div class="col-sm-12 content-hide">
		  	<div class="panel panel-map">
		  		<div class="panel-body">
		  			<div class="col-sm-12 p-0">
					    <div class="blog-slider bg-guide">
						  <div class="blog-slider__wrp swiper-wrapper">
						    <div class="blog-slider__item swiper-slide">
						      <div class="blog-slider__img">
						        <img src="img/icon/icon-custom1/online-shop.png" alt="">
						      </div>
						      <div class="blog-slider__content">
						        <!-- <span class="blog-slider__code">26 December 2019</span> -->
						        <div class="blog-slider__title">Manage your item product now</div>
						        <div class="blog-slider__text">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Recusandae voluptate repellendus magni illo ea animi? </div>
						        <a href="<?= site_url('product'); ?>" class="blog-slider__button"><?= $this->lang->line('lb_try_it_now') ?></a>
						      </div>
						    </div>
						    <div class="blog-slider__item swiper-slide">
						      <div class="blog-slider__img">
						         <img src="img/icon/icon-custom1/accounting.png" alt="">
						      </div>
						      <div class="blog-slider__content">
						        <!-- <span class="blog-slider__code">26 December 2019</span> -->
						        <div class="blog-slider__title">Create your first invoice</div>
						        <div class="blog-slider__text">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Recusandae voluptate repellendus magni illo ea animi?</div>
						        <a href="<?= site_url('invoice-receivable'); ?>" class="blog-slider__button"><?= $this->lang->line('lb_try_it_now') ?></a>
						      </div>
						    </div>
						    
						    <div class="blog-slider__item swiper-slide">
						      <div class="blog-slider__img">
						         <img src="img/icon/icon-custom1/stats.png" alt="">
						      </div>
						      <div class="blog-slider__content">
						        <!-- <span class="blog-slider__code">26 December 2019</span> -->
						        <div class="blog-slider__title">See stock and finnace reports</div>
						        <div class="blog-slider__text">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Recusandae voluptate repellendus magni illo ea animi?</div>
						        <a href="<?= site_url('report'); ?>" class="blog-slider__button"><?= $this->lang->line('lb_try_it_now') ?></a>
						      </div>
						    </div>

						    <div class="blog-slider__item swiper-slide">
						      <div class="blog-slider__img">
						         <img src="img/icon/icon-custom1/account.png" alt="">
						      </div>
						      <div class="blog-slider__content">
						        <!-- <span class="blog-slider__code">26 December 2019</span> -->
						        <div class="blog-slider__title">Your account expires immediately now</div>
						        <div class="blog-slider__text">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Recusandae voluptate repellendus magni illo ea animi?</div>
						        <a href="<?= site_url('page-setting-parameter'); ?>" class="blog-slider__button"><?= $this->lang->line('lb_try_it_now') ?></a>
						      </div>
						    </div>
						    
						  </div>
						  <div class="blog-slider__pagination"></div>
						</div>
					</div>
					<button class="btn btn-collapse-onboarding toggleMessage">Hide</button>
	            </div>
			</div>
		</div>
	    <div class="col-sm-12">
		  	<div class="panel panel-map panel-bordered bg-transanparant">
	            <div class="col-sm-12 p-0">
	            	<div class="col-sm-3 var">
					  	<div class="panel panel-map panel-bordered bg-gradient1">
					  		<div class="box-top-blue bg-dashboard">
					            <div class="panel-body bg-transanparant">
					            	<a href="<?= base_url('partner') ?>"><div class="mini-stat clearfix bx-shadow">
	                                    <span class="mini-stat-icon bg-info"><img src="<?= base_url('img/icon/icon-dashboard/043-seller.png') ?>" class="product-img"></span>
	                                    <div class="mini-stat-info text-right text-muted">
	                                        <span class="counter-number total_vendor"></span>
	                                        <span class="mini-title"><?= $this->lang->line('lb_total_vendor') ?></span>
	                                    </div>
	                                    <div class="tiles-progress content-hide">
	                                        <div class="m-t-20">
	                                            <div id="total_sell"></div>
	                                        </div>
	                                    </div>
	                                </div></a>
					            </div>
					        </div>
				      	</div>
				  	</div>
				  	<div class="col-sm-3 var">
					  	<div class="panel panel-map panel-bordered bg-gradient2">
					  		<div class="box-top-blue bg-dashboard">
					            <div class="panel-body bg-transanparant">
					            	<a href="<?= base_url('partner') ?>"><div class="mini-stat clearfix bx-shadow">
	                                    <span class="mini-stat-icon bg-info"><img src="<?= base_url('aset/images/icon-white/value.png') ?>" class="product-img"></span>
	                                    <div class="mini-stat-info text-right text-muted">
	                                        <span class="counter-number total_customer"></span>
	                                        <span class="mini-title"><?= $this->lang->line('lb_total_customer') ?></span>
	                                    </div>
	                                    <div class="tiles-progress content-hide">
	                                        <div class="m-t-20">
	                                            <div id="total_customer"></div>
	                                        </div>
	                                    </div>
	                                </div></a>
					            </div>
					        </div>
				      	</div>
				  	</div>
				  	<div class="col-sm-3 vinventory">
					  	<div class="panel panel-map panel-bordered bg-gradient3">
					  		<div class="box-top-blue bg-dashboard">
					            <div class="panel-body bg-transanparant">
					            	<a href="<?= base_url('product') ?>"><div class="mini-stat clearfix bx-shadow">
	                                    <span class="mini-stat-icon bg-info"><img src="<?= base_url('aset/images/icon-white/gift.png') ?>" class="product-img"></span>
	                                    <div class="mini-stat-info text-right text-muted">
	                                        <span class="counter-number total_product"></span>
	                                        <span class="mini-title"><?= $this->lang->line('lb_total_product') ?></span>
	                                    </div>
	                                    <div class="tiles-progress content-hide">
	                                        <div class="m-t-20">
	                                            <div id="total_product"></div>
	                                        </div>
	                                    </div>
	                                </div></a>
					            </div>
					        </div>
				      	</div>
				  	</div>
				  	<div class="col-sm-3 vinventory">
					  	<div class="panel panel-map panel-bordered bg-gradient4">
					  		<div class="box-top-blue bg-dashboard">
					            <div class="panel-body bg-transanparant">
					            	<a href="<?= base_url('product') ?>"><div class="mini-stat clearfix bx-shadow">
	                                    <span class="mini-stat-icon bg-info"><img src="<?= base_url('aset/images/icon-white/warehouse.png') ?>" class="product-img"></span>
	                                    <div class="mini-stat-info text-right text-muted">
	                                        <span class="counter-number total_stock"></span>
	                                        <span class="mini-title"><?= $this->lang->line('lb_total_stock') ?></span>
	                                    </div>
	                                    <div class="tiles-progress content-hide">
	                                        <div class="m-t-20">
	                                            <div id="total_stock"></div>
	                                        </div>
	                                    </div>
	                                </div></a>
					            </div>
					        </div>
				      	</div>
				  	</div>
				</div>
	        </div>
	    </div>
	    <div class="col-sm-12">
		  	<div class="panel panel-map panel-bordered bg-transanparant">
	            <div class="col-sm-6 var">
				  	<div class="panel panel-map panel-bordered">
				  		<div class="box-top-blue">
				            <div class="panel-body bg-transanparant">
				            	<div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_outstanding_sales') ?></div>
				            	<div class="chart mt-10">
				            		<ul class="nav nav-pills nav-pills-rounded product-filters bg-abu mb-15 pull-right" data-plugin="nav-tabs" id="ul-outstanding" data-page="outstanding_delivery" data-module="ar">
										<li class="v1" data-type="days" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="selected_item('#ul-outstanding','.v1')">
											<a href="javascript:;"><?= $this->lang->line('lb_days') ?></a>
										</li>
										<li class="v2" data-type="month" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="selected_item('#ul-outstanding','.v2')">
											<a href="javascript:;"><?= $this->lang->line('lb_month') ?></a>
										</li>
										<li class="v3" data-type="year" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="selected_item('#ul-outstanding','.v3')">
											<a href="javascript:;"><?= $this->lang->line('lb_year') ?></a>
										</li>
									</ul>
									<div class="col-sm-12"></div>
									<div id="outstanding_delivery-legend" class="chart-legend col-sm-12"></div>
								 	<canvas id="outstanding_delivery" class="black chart-sm-6"></canvas>
				            	</div>
				            </div>
				        </div>
				        <div class="panel-loading"><div class="loader loader-default"></div></div>
			      	</div>
			  	</div>
			  	<div class="col-sm-3 var">
			  		<div class="panel panel-map panel-bordered">
			  			<div class="box-top-blue">
			  				<div class="panel-body bg-transanparant">
			  					<div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_top_by_category') ?></div>
			  					<div class="chart mt-10">
			  						<div id="sales_category-legend" class="chart-legend col-sm-12"></div>
			  						<canvas id="sales_category" class="chart-sm-4"></canvas>
			  					</div>
			  				</div>
			  			</div>
			  		</div>
			  	</div>
			  	<div class="col-sm-3 var">
			  		<div class="panel panel-map panel-bordered">
			  			<div class="box-top-blue">
			  				<div class="panel-body bg-transanparant">
			  					<div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_top_by_customer') ?></div>
			  					<div class="chart mt-10">
			  						<div id="top_sales_customer-legend" class="chart-legend col-sm-12"></div>
			  						<canvas id="top_sales_customer" class="chart-sm-4"></canvas>
			  					</div>
			  				</div>
			  			</div>
			  		</div>
			  	</div>
			  	<div class="col-sm-6 var">
			  		<div class="panel panel-map panel-bordered">
			  			<div class="box-top-blue">
			  				<div class="panel-body bg-transanparant">
			  					<div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_top_by_city') ?></div>
			  					<div class="chart mt-10">
			  						<div id="sales_city-legend" class="chart-legend col-sm-12"></div>
			  						<canvas id="sales_city" class="chart-sm-4"></canvas>
			  					</div>
			  				</div>
			  			</div>
			  		</div>
			  	</div>
			  	<div class="col-sm-6 vap content-hide">
				  	<div class="panel panel-map panel-bordered">
				  		 <div class="box-top-blue">
				            <div class="panel-body bg-transanparant">
				            	<div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_total_purchse_trans') ?></div>
				            	<div class="chart mt-10">
				            		<ul class="nav nav-pills nav-pills-rounded product-filters bg-abu mb-15 pull-right" data-plugin="nav-tabs" id="ul-purchase-transaction" data-page="purchase_transaction">
										<li class="v1" data-type="days" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="selected_item('#ul-purchase-transaction','.v1')">
											<a href="javascript:;"><?= $this->lang->line('lb_days') ?></a>
										</li>
										<li class="v2" data-type="month" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="selected_item('#ul-purchase-transaction','.v2')">
											<a href="javascript:;"><?= $this->lang->line('lb_month') ?></a>
										</li>
										<li class="v3" data-type="year" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="selected_item('#ul-purchase-transaction','.v3')">
											<a href="javascript:;"><?= $this->lang->line('lb_year') ?></a>
										</li>
									</ul>
									<div class="col-sm-12"></div>
									<div id="purchase_transaction-legend" class="chart-legend col-sm-12"></div>
								 	<canvas id="purchase_transaction"></canvas>
				            	</div>
				            </div>
				        </div>
			      	</div>
			  	</div>
			</div>
		</div>

		<div class="col-sm-12">
		  	<div class="panel panel-map panel-bordered bg-transanparant">
	            <div class="col-sm-6 var">
				  	<div class="panel panel-map panel-bordered">
				  		<div class="box-top-blue">
				            <div class="panel-body bg-transanparant">
				            	<div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_open_selling') ?></div>
				            	<div class="chart mt-10">
				            		<ul class="nav nav-pills nav-pills-rounded product-filters bg-abu mb-15 pull-right" data-plugin="nav-tabs" id="ul-sales-open" data-page="sales_open">
										<li class="v1" data-type="days" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="selected_item('#ul-sales-open','.v1')">
											<a href="javascript:;"><?= $this->lang->line('lb_days') ?></a>
										</li>
										<li class="v2" data-type="month" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="selected_item('#ul-sales-open','.v2')">
											<a href="javascript:;"><?= $this->lang->line('lb_month') ?></a>
										</li>
										<li class="v3" data-type="year" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="selected_item('#ul-sales-open','.v3')">
											<a href="javascript:;"><?= $this->lang->line('lb_year') ?></a>
										</li>
									</ul>
									<div class="col-sm-12"></div>
									<div id="sales_open-legend" class="chart-legend col-sm-12"></div>
								 	<canvas id="sales_open" class="black"></canvas>
				            	</div>
				            </div>
				        </div>
				        <div class="panel-loading"><div class="loader loader-default"></div></div>
			      	</div>
			  	</div>
			  	<div class="col-sm-3 var">
				  	<div class="panel panel-map panel-bordered bg-gradient5">
				  		<div class="box-top-blue bg-dashboard">
				            <div class="panel-body bg-transanparant">
				            	<div class="mini-stat clearfix bx-shadow">
                                    <span class="mini-stat-icon bg-info"><img src="<?= base_url('img/icon/icon-dashboard/035-shopping-cart.png') ?>" class="product-img"></span>
                                    <div class="mini-stat-info text-right text-muted">
                                        <span class="counter-number total_sales_open"></span>
                                        <span class="mini-title"><?= $this->lang->line('lb_total_open_selling') ?></span>
                                    </div>
                                </div>
				            </div>
				        </div>
			      	</div>
			  	</div>
			  	<div class="col-sm-3 var">
				  	<div class="panel panel-map panel-bordered bg-gradient6">
				  		<div class="box-top-blue bg-dashboard">
				            <div class="panel-body bg-transanparant">
				            	<div class="mini-stat clearfix bx-shadow">
                                    <span class="mini-stat-icon bg-info"><img src="<?= base_url('img/icon/icon-dashboard/034-sale.png') ?>" class="product-img"></span>
                                    <div class="mini-stat-info text-right text-muted">
                                        <span class="counter-number total_sales_overdude"></span>
                                        <span class="mini-title"><?= $this->lang->line('lb_total_overdude_selling') ?></span>
                                    </div>
                                </div>
				            </div>
				        </div>
			      	</div>
			  	</div>
			  	<div class="col-sm-3 var">
				  	<div class="panel panel-map panel-bordered bg-gradient7">
				  		<div class="box-top-blue bg-dashboard">
				            <div class="panel-body bg-transanparant">
				            	<div class="mini-stat clearfix bx-shadow">
                                    <span class="mini-stat-icon bg-info"><img src="<?= base_url('img/icon/icon-dashboard/036-data.png') ?>" class="product-img"></span>
                                    <div class="mini-stat-info text-right text-muted">
                                        <span class="counter-number total_sales_payment"></span>
                                        <span class="mini-title"><?= $this->lang->line('lb_total_payment') ?></span>
                                    </div>
                                </div>
				            </div>
				        </div>
			      	</div>
			  	</div>
			</div>
		</div>

		<div class="col-sm-12">
		  	<div class="panel panel-map panel-bordered bg-transanparant">
		  		<div class="col-sm-6 p-0">
		  			<div class="col-sm-6 vap">
					  	<div class="panel panel-map panel-bordered bg-gradient8">
					  		<div class="box-top-blue bg-dashboard">
					            <div class="panel-body bg-transanparant">
					            	<div class="mini-stat clearfix bx-shadow">
	                                    <span class="mini-stat-icon bg-info"><img src="<?= base_url('img/icon/icon-dashboard/035-shopping-cart.png') ?>" class="product-img"></span>
	                                    <div class="mini-stat-info text-right text-muted">
	                                        <span class="counter-number total_purchase_open"></span>
	                                        <span class="mini-title"><?= $this->lang->line('lb_open_purchase_total') ?></span>
	                                    </div>
	                                </div>
					            </div>
					        </div>
				      	</div>
				  	</div>
				  	<div class="col-sm-6 vap">
					  	<div class="panel panel-map panel-bordered bg-gradient9">
					  		<div class="box-top-blue bg-dashboard">
					            <div class="panel-body bg-transanparant">
					            	<div class="mini-stat clearfix bx-shadow">
	                                    <span class="mini-stat-icon bg-info"><img src="<?= base_url('img/icon/icon-dashboard/034-sale.png') ?>" class="product-img"></span>
	                                    <div class="mini-stat-info text-right text-muted">
	                                        <span class="counter-number total_purchase_overdude"></span>
	                                        <span class="mini-title"><?= $this->lang->line('lb_overdude_purchase_total') ?></span>
	                                    </div>
	                                </div>
					            </div>
					        </div>
				      	</div>
				  	</div>
				  	<div class="col-sm-6 vap">
					  	<div class="panel panel-map panel-bordered bg-gradient10">
					  		<div class="box-top-blue bg-dashboard">
					            <div class="panel-body bg-transanparant">
					            	<div class="mini-stat clearfix bx-shadow">
	                                    <span class="mini-stat-icon bg-info"><img src="<?= base_url('img/icon/icon-dashboard/036-data.png') ?>" class="product-img"></span>
	                                    <div class="mini-stat-info text-right text-muted">
	                                        <span class="counter-number total_purchase_payment"></span>
	                                        <span class="mini-title"><?= $this->lang->line('lb_purchase_payment_total') ?></span>
	                                    </div>
	                                </div>
					            </div>
					        </div>
				      	</div>
				  	</div>
		  		</div>
	            <div class="col-sm-6 vap">
				  	<div class="panel panel-map panel-bordered">
				  		<div class="box-top-blue">
				            <div class="panel-body bg-transanparant">
				            	<div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_open_purchase') ?></div>
				            	<div class="chart mt-10">
				            		<ul class="nav nav-pills nav-pills-rounded product-filters bg-abu mb-15 pull-right" data-plugin="nav-tabs" id="ul-purchase-open" data-page="purchase_open">
										<li class="v1" data-type="days" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="selected_item('#ul-purchase-open','.v1')">
											<a href="javascript:;"><?= $this->lang->line('lb_days') ?></a>
										</li>
										<li class="v2" data-type="month" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="selected_item('#ul-purchase-open','.v2')">
											<a href="javascript:;"><?= $this->lang->line('lb_month') ?></a>
										</li>
										<li class="v3" data-type="year" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="selected_item('#ul-purchase-open','.v3')">
											<a href="javascript:;"><?= $this->lang->line('lb_year') ?></a>
										</li>
									</ul>
									<div class="col-sm-12"></div>
									<div id="purchase_open-legend" class="chart-legend col-sm-12"></div>
								 	<canvas id="purchase_open" class="black"></canvas>
				            	</div>
				            </div>
				        </div>
				        <div class="panel-loading"><div class="loader loader-default"></div></div>
			      	</div>
			  	</div>
			</div>
		</div>

		<div class="col-sm-12">
		  	<div class="panel panel-map panel-bordered bg-transanparant">
	            <div class="panel-body">
					<div class="grid-container">
					  	<div class="grid-item item1">
						  	<div class="col-sm-5 vinventory">
							  	<div class="panel panel-map panel-bordered">
							  		 <div class="box-top-blue">
							            <div class="panel-body bg-transanparant">
							            	<div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_stock_minimal') ?></div>
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
							</div>
					  	</div>
					  	<div class="grid-item item2">
					  		<div class="col-sm-7 vinventory">
							  	<div class="panel panel-map panel-bordered">
							  		<div class="box-top-blue">
							            <div class="panel-body bg-transanparant">
							            	<div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_total_stock') ?></div>
							            	<div class="chart mt-10">
							            		<div id="stock_branch-legend" class="chart-legend col-sm-12"></div>
											 	<canvas id="stock_branch"></canvas>
							            	</div>
							            </div>
							        </div>
							        <div class="panel-loading"><div class="loader loader-default"></div></div>
						      	</div>
					  		</div>
					  	</div>  
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-12">
		  	<div class="panel panel-map panel-bordered bg-transanparant">
			  	<div class="col-sm-3">
			  		<div class="col-sm-12 vac p-0">
					  	<div class="panel panel-map panel-bordered bg-gradient11">
					  		<div class="box-top-blue bg-dashboard">
					            <div class="panel-body bg-transanparant">
					            	<div class="mini-stat clearfix bx-shadow">
	                                    <span class="mini-stat-icon bg-info"><img src="<?= base_url('img/icon/icon-dashboard/035-shopping-cart.png') ?>" class="product-img"></span>
	                                    <div class="mini-stat-info text-right text-muted">
	                                        <span class="counter-number total_net_omset"></span>
	                                        <span class="mini-title"><?= $this->lang->line('lb_total_omset') ?></span>
	                                    </div>
	                                </div>
					            </div>
					        </div>
				      	</div>
				  	</div>
				  	<div class="col-sm-12 vac p-0">
					  	<div class="panel panel-map panel-bordered bg-gradient12">
					  		<div class="box-top-blue bg-dashboard">
					            <div class="panel-body bg-transanparant">
					            	<div class="mini-stat clearfix bx-shadow">
	                                    <span class="mini-stat-icon bg-info"><img src="<?= base_url('img/icon/icon-dashboard/034-sale.png') ?>" class="product-img"></span>
	                                    <div class="mini-stat-info text-right text-muted">
	                                        <span class="counter-number total_net_profit"></span>
	                                        <span class="mini-title"><?= $this->lang->line('lb_total_net_profit') ?></span>
	                                    </div>
	                                </div>
					            </div>
					        </div>
				      	</div>
				  	</div>
				  	<div class="col-sm-12 vac p-0">
					  	<div class="panel panel-map panel-bordered bg-gradient13">
					  		<div class="box-top-blue bg-dashboard">
					            <div class="panel-body bg-transanparant">
					            	<div class="mini-stat clearfix bx-shadow">
	                                    <span class="mini-stat-icon bg-info"><img src="<?= base_url('img/icon/icon-dashboard/034-sale.png') ?>" class="product-img"></span>
	                                    <div class="mini-stat-info text-right text-muted">
	                                        <span class="counter-number total_net_balace_sheet"></span>
	                                        <span class="mini-title"><?= $this->lang->line('lb_total_net_balance') ?></span>
	                                    </div>
	                                </div>
					            </div>
					        </div>
				      	</div>
				  	</div>
			  	</div>
			  	<div class="col-sm-6 vac">
				  	<div class="panel panel-map panel-bordered">
				  		<div class="box-top-blue">
				            <div class="panel-body bg-transanparant">
				            	<div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_omset') ?></div>
				            	<div class="chart mt-10">
				            		<ul class="nav nav-pills nav-pills-rounded product-filters bg-abu mb-15 pull-right" data-plugin="nav-tabs" id="ul-sales-cost" data-page="omset_cost">
										<li class="v1" data-type="days" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="selected_item('#ul-sales-cost','.v1')">
											<a href="javascript:;"><?= $this->lang->line('lb_days') ?></a>
										</li>
										<li class="v2" data-type="month" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="selected_item('#ul-sales-cost','.v2')">
											<a href="javascript:;"><?= $this->lang->line('lb_month') ?></a>
										</li>
										<li class="v3" data-type="year" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="selected_item('#ul-sales-cost','.v3')">
											<a href="javascript:;"><?= $this->lang->line('lb_year') ?></a>
										</li>
									</ul>
									<div class="col-sm-12"></div>
									<div id="omset_cost-legend" class="chart-legend col-sm-12"></div>
								 	<canvas id="omset_cost" class="black"></canvas>
				            	</div>
				            </div>
				        </div>
				        <div class="panel-loading"><div class="loader loader-default"></div></div>
			      	</div>
			  	</div>
			  	<div class="col-sm-3">
			  		<div class="col-sm-12 p-0">
					  	<div class="panel panel-map panel-bordered">
					  		 <div class="box-top-blue">
					            <div class="panel-body bg-transanparant">
					            	<div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_ac_watchlist') ?></div>
					            	<table id="table_account_watchlist" class="table table-hover dataTable table-striped width-full">
					            		<thead>
					            			<tr>
					            				<th><?= $this->lang->line('lb_no') ?></th>
					            				<th><?= $this->lang->line('lb_bank_acount') ?></th>
					            				<th><?= $this->lang->line('lb_total') ?></th>
					            			</tr>
					            		</thead>
					            		<tbody></tbody>
					            	</table>
					            </div>
					        </div>
				      	</div>
					</div>
			  	</div>
			</div>
		</div>

		<div class="col-sm-12">
		  	<div class="panel panel-map panel-bordered bg-transanparant">
	            <div class="col-sm-6 vac">
				  	<div class="panel panel-map panel-bordered">
				  		<div class="box-top-blue">
				            <div class="panel-body bg-transanparant">
				            	<div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_loss_profit') ?></div>
				            	<div class="chart mt-10">
				            		<ul class="nav nav-pills nav-pills-rounded product-filters bg-abu mb-15 pull-right" data-plugin="nav-tabs" id="ul-loss-profit" data-page="loss_profit">
										<li class="v1" data-type="days" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="selected_item('#ul-loss-profit','.v1')">
											<a href="javascript:;"><?= $this->lang->line('lb_days') ?></a>
										</li>
										<li class="v2" data-type="month" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="selected_item('#ul-loss-profit','.v2')">
											<a href="javascript:;"><?= $this->lang->line('lb_month') ?></a>
										</li>
										<li class="v3" data-type="year" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="selected_item('#ul-loss-profit','.v3')">
											<a href="javascript:;"><?= $this->lang->line('lb_year') ?></a>
										</li>
									</ul>
									<div class="col-sm-12"></div>
									<div id="loss_profit-legend" class="chart-legend col-sm-12"></div>
								 	<canvas id="loss_profit" class="black"></canvas>
				            	</div>
				            </div>
				        </div>
				        <div class="panel-loading"><div class="loader loader-default"></div></div>
			      	</div>
			  	</div>
			  	<div class="col-sm-6 vac">
				  	<div class="panel panel-map panel-bordered">
				  		<div class="box-top-blue">
				            <div class="panel-body bg-transanparant">
				            	<div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_balance_sheet') ?></div>
				            	<div class="chart mt-10">
				            		<ul class="nav nav-pills nav-pills-rounded product-filters bg-abu mb-15 pull-right" data-plugin="nav-tabs" id="ul-balance_sheet" data-page="balance_sheet">
										<li class="v1" data-type="days" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="selected_item('#ul-balance_sheet','.v1')">
											<a href="javascript:;"><?= $this->lang->line('lb_days') ?></a>
										</li>
										<li class="v2" data-type="month" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="selected_item('#ul-balance_sheet','.v2')">
											<a href="javascript:;"><?= $this->lang->line('lb_month') ?></a>
										</li>
										<li class="v3" data-type="year" data-toggle="panel-refresh" data-load-type="round-circle" data-load-callback="customRefreshCallback" aria-hidden="true" onclick="selected_item('#ul-balance_sheet','.v3')">
											<a href="javascript:;"><?= $this->lang->line('lb_year') ?></a>
										</li>
									</ul>
									<div class="col-sm-12"></div>
									<div id="balance_sheet-legend" class="chart-legend col-sm-12"></div>
								 	<canvas id="balance_sheet" class="black"></canvas>
				            	</div>
				            </div>
				        </div>
				        <div class="panel-loading"><div class="loader loader-default"></div></div>
			      	</div>
			  	</div>
			</div>
		</div>

		<div class="col-sm-12">
		  	<div class="panel panel-map panel-bordered bg-transanparant">
	            <div class="grid-item item3">
				  	<div class="col-sm-12">
					  	<div class="panel panel-map panel-bordered">
					  		 <div class="box-top-blue">
					            <div class="panel-body bg-transanparant">
					            	<div class="counter-label text-capitalize font-size-16"><?= $this->lang->line('lb_store_location') ?></div>
					            	<div id="map" style="height: 300px;width: 100%"></div>
					            </div>
					        </div>
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

<?php $this->load->view('dashboard/style'); ?>
<script src='https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.5/js/swiper.min.js'></script>
<script>
	var swiper = new Swiper('.blog-slider', {
	  spaceBetween: 30,
	  effect: 'fade',
	  loop: true,
	  mousewheel: {
	    invert: false,
	  },
	  autoplay: {
	    delay: 4000,
	  },
	  // autoHeight: true,
	  pagination: {
	    el: '.blog-slider__pagination',
	    clickable: true,
	  }
	});
	$(document).ready(function(){
	    $(".toggleMessage").click(function(){
			$(this).text(function(i, v){
			   return v === 'Hide' ? 'Getting Started' : 'Hide'
			});
			$('.blog-slider').slideToggle("slow");
	    });
	});
</script>