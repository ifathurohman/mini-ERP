	<style type="text/css">
		#toast-container > div{opacity: 1 !important}
	</style>
   	<div style="margin-top: 50px;"></div>
   	<section class="section-normal bg-light text-center wb-min-height-100vh" style="min-height: 0px;">
      <div class="container container-gallery">
        <div class="section-title" data-aos="fade-down"><?= $this->lang->line('lb_buy_voucher') ?></div>
        <div class="text-center">
          <div class="border-bottom-blue"></div>
        </div>
        <div class="row justify-content-center mt-5r gallery-img list-experience list-fitur ">
        	<div class="col-md-8" style="border: 1.5px solid #cccccc38;border-radius: 8px;">
        		<div class="panel" id="exampleWizardFormContainer">
		            <div class="panel-heading">
		              
		            </div>
		            <div class="panel-body">
		              <!-- Steps -->
		              <div class="pearls row mt-3r" style="display: flex;">
		                <div class="pearl col-md-4 vcheckout active">
		                  <div class="pearl-icon"><i class="fa fa-list" aria-hidden="true"></i></div>
		                  <span class="pearl-title"><?= $this->lang->line('lb_voucher_package') ?></span>
		                </div>
		                <div class="pearl col-md-4 vuser">
		                  <div class="pearl-icon"><i class="fa fa-user" aria-hidden="true"></i></div>
		                  <span class="pearl-title"><?= $this->lang->line('lb_user_info') ?></span>
		                </div>
		                <div class="pearl col-md-4 vconfirm">
		                  <div class="pearl-icon"><i class="fa fa-credit-card" aria-hidden="true"></i></div>
		                  <span class="pearl-title"><?= $this->lang->line('btn_confirmation') ?></span>
		                </div>
		              </div>
		              <!-- End Steps -->

		              <form id="form" autocomplete="off" style="text-align: left;">
		              	<div class="div-voucher content-hide">
		              		<div class="row">
			              		<div class="form-group col-sm-12">
					              <label class="control-label font-weight-400"><?= $this->lang->line('lb_voucher_package') ?></label>
					              <select name="Type" class="form-control">
					                <option value="3">3 <?= $this->lang->line('lb_month') ?></option>
					                <option value="6">6 <?= $this->lang->line('lb_month') ?></option>
					                <option value="12">1 <?= $this->lang->line('lb_year') ?></option>
					              </select>
					              <span class="help-block"></span>
					            </div>
			              	</div>
			              	<div class="row">
			              		<div class="form-group col-sm-6">
					              <label class="control-label font-weight-400"><?= $this->lang->line('lb_voucher_module') ?></label>
					              <select name="Module" class="form-control">
					                <option value="none"><?= $this->lang->line('lb_voucher_module_choose') ?></option>
					                <?php foreach(range(1,4) as $a): ?>
						                <option value="<?= $a ?>"><?= $a." ".$this->lang->line('module'); ?></option>
					              	<?php endforeach; ?>
					              </select>
					              <span class="help-block"></span>
					            </div>

					            <div class="form-group col-sm-6">
					              <label class="control-label font-weight-400"><?= $this->lang->line('lb_voucher_user') ?></label>
					              <select name="Qty" class="form-control">
					                <option value="none"><?= $this->lang->line('lb_voucher_user_choose') ?></option>
					              <?php foreach(range(1,100) as $a): ?>
					                <option><?= $a; ?></option>
					              <?php endforeach; ?>
					              </select>
					              <span class="help-block"></span>
					            </div>
			              	</div>
			              	<div class="row">
			              		<div class="form-group col-sm-6 vpriceModule">
					              <label class="control-label font-weight-400"><?= $this->lang->line('lb_voucher_module_amount') ?></label>
					              <input type="text" name="PriceModule" readonly="" class="form-control">
					              <span class="help-block"></span>
					            </div>

					            <div class="form-group col-sm-6 vpriceDevice">
					              <label class="control-label font-weight-400"><?= $this->lang->line('lb_voucher_user_amount') ?></label>
					              <input type="text" name="PriceDevice" readonly="" class="form-control">
					              <span class="help-block"></span>
					            </div>
			              	</div>
			              	<div class="row">
			              		<div class="form-group col-sm-12">
					              <label class="control-label font-weight-400"><?= $this->lang->line('lb_total_amount') ?></label>
					              <input type="text" name="Price" readonly="" class="form-control">
					              <span class="help-block"></span>
					            </div>
					            <div class="form-group col-sm-12">
					              <?= $this->lang->line('lb_noted') ?> :  <br/><?= $this->lang->line('lb_account_ex') ?>
					            </div>
			              	</div>
		              	</div>
		              	<div class="div-user content-hide">
		              		<div class="row">
		              			<div class="col-sm-12">
		              				<h5 class="pull-right"><?= $this->lang->line('lb_total_amount') ?> <span class="totalAmounttxt"></span></h5>
		              			</div>
		              		</div>
		              		<div class="row">
			              		<div class="form-group col-sm-6">
					              <label class="control-label font-weight-400"><?= $this->lang->line('lb_name') ?> <span class="wajib"></span></label>
					              <input type="text" name="Name" class="form-control" value="<?= $this->session->nama ?>">
					              <span class="help-block"></span>
					            </div>
					            <div class="form-group col-sm-6">
					              <label class="control-label font-weight-400">Email <span class="wajib"></span></label>
					              <input type="text" name="Email" class="form-control" value="<?= $this->session->email ?>">
					              <span class="help-block"></span>
					            </div>
			              	</div>
			              	<div class="row">
			              		<div class="form-group col-sm-6">
					              <label class="control-label font-weight-400"><?= $this->lang->line('lb_address') ?></label>
					              <input type="text" name="Address" class="form-control">
					              <span class="help-block"></span>
					            </div>
					            <div class="form-group col-sm-6">
					              <label class="control-label font-weight-400"><?= $this->lang->line('lb_city') ?></label>
					              <input type="text" name="City" class="form-control">
					              <span class="help-block"></span>
					            </div>
			              	</div>
			              	<div class="row">
			              		<div class="form-group col-sm-6">
					              <label class="control-label font-weight-400"><?= $this->lang->line('lb_state') ?></label>
					              <input type="text" name="State" class="form-control">
					              <span class="help-block"></span>
					            </div>
					            <div class="form-group col-sm-6">
					              <label class="control-label font-weight-400"><?= $this->lang->line('lb_country') ?></label>
					              <input type="text" name="Country" class="form-control">
					              <span class="help-block"></span>
					            </div>
			              	</div>
			              	<div class="row">
			              		<div class="form-group col-sm-6">
					              <input type="checkbox" name="Agree" id="Agree" value="Agree">
					              <label class="pointer font-weight-400" for="Agree"><?= $this->lang->line('lb_agree_terms') ?></label>
					              <span class="help-block"></span>
					            </div>
					            <div class="form-group col-sm-12">
					              <?= $this->lang->line('lb_noted') ?> :  <br/><?= $this->lang->line('lb_email_provide') ?>
					            </div>
			              	</div>
		              	</div>
		              	<div class="div-confirm content-hide">
		              		<div class="row">
		              			<div class="form-group col-sm-12">
		              				<p><?= $this->lang->line('lb_voucher_notif') ?></p>
		              				<p><?= $this->lang->line('lb_voucher_code') ?> <span class="voucher_code"></span>.</p>
		              				<p><?= $this->lang->line('lb_voucher_confirm') ?></p>
		              				<p><?= $this->lang->line('lb_voucher_detail') ?> : </p>
		              			</div>
		              		</div>
		              		<div class="row">
		              			<div class="form-group col-sm-6">
		              				<?= $this->lang->line('lb_name') ?> : <span class="voucher_name"></span>
		              			</div>
		              			<div class="form-group col-sm-6">
		              				Email : <span class="voucher_email"></span>
		              			</div>
		              			<div class="form-group col-sm-6">
		              				<?= $this->lang->line('lb_address') ?> : <span class="voucher_address"></span>
		              			</div>
		              			<div class="form-group col-sm-6">
		              				<?= $this->lang->line('lb_city') ?> : <span class="voucher_city"></span>
		              			</div>
		              			<div class="form-group col-sm-6">
		              				<?= $this->lang->line('lb_state') ?> : <span class="voucher_state"></span>
		              			</div>
		              			<div class="form-group col-sm-6">
		              				<?= $this->lang->line('lb_country') ?> : <span class="voucher_country"></span>
		              			</div>
		              		</div>
		              		<div class="row">
		              			<div class="form-group col-sm-12">
		              				<table id="table-voucher" class="table">
		              					<thead>
		              						<tr>
		              							<th><?= $this->lang->line('lb_no') ?></th>
		              							<th><?= $this->lang->line('lb_package') ?></th>
		              							<th><?= $this->lang->line('lb_voucher_type') ?></th>
		              							<th><?= $this->lang->line('lb_qty') ?></th>
		              							<th><?= $this->lang->line('lb_amount') ?></th>
		              							<th>Total</th>
		              						</tr>
		              					</thead>
		              					<tbody></tbody>
		              				</table>
		              			</div>
		              		</div>
		              	</div>
		              </form>
		              <div class="row">
	              		<div class="col-sm-12">
	              			<div class="btn-group pull-right vaction">
	              				<button onclick="save()" class="btn btn-custom save min-width-100"><?= $this->lang->line('lb_check_out') ?></button>
	              			</div>
	              			<div class="btn-group pull-left">
	              				
	              			</div>
	              		</div>
		              </div>
		            </div>
		          </div>
        	</div>
        </div>
      </div>
    </section>
    <script type="text/javascript">
		var data_voucher = <?= json_encode($this->main->session_voucher()) ?>
	</script>
	<!-- <link rel="stylesheet" href="<?= base_url('aset\css\bootstrap-extend.min.css'.$this->main->js_css_version()); ?>"> -->
    <link rel="stylesheet" href="<?= base_url('aset\css\site.min.css'); ?>">
	<link rel="stylesheet" href="<?= base_url('aset\css\main.css'); ?>">
	<link rel="stylesheet" href="<?= base_url("aset/css/sweetalert.css"); ?>">
	<script src="<?= base_url("aset/js/sweetalert.min.js".$this->main->js_css_version()); ?>"></script>
	<link rel="stylesheet" href="<?= base_url("aset/plugin/toastr/toastr.min.css"); ?>">
	<script src="<?= base_url("aset/plugin/toastr/toastr.min.js"); ?>"></script><!-- Footer -->
	<script src="<?= base_url('aset\frontend\js\frontend\buy_voucher.js'.$this->main->js_css_version()); ?>"></script>