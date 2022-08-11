<div class="page page-data" data-modul="<?= $modul ?>" data-url_modul="<?= $url_modul ?>" data-add="<?= $add ?>" data-edit="<?= $edit ?>" data-delete="<?= $delete ?>" data-date="<?= date("Y-m-d") ?>">
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
            <div class="row vsave">
              
            </div>
            <form id="form" autocomplete="off" method="post">
              <!-- Ap -->
              <label><strong>1. <?= $this->lang->line('lb_coa_ap'); ?></strong></label>
              <div class="ml-15">
                <div class="row">
                  <div class="form-group col-sm-6">
                    <label class="col-sm-4 control-label"><?= $this->lang->line('lb_ap'); ?></label>
                    <div class="col-sm-8">
                      <input type="hidden" name="Account Payable" class="ap">
                      <input type="text" class="form-control pointer" onclick="coa_modal('.ap','coa_setting')" placeholder="<?= $this->lang->line('lb_select_data'); ?>" readonly >
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-sm-6">
                    <label class="col-sm-4 control-label"><?= $this->lang->line('lb_ap_temp'); ?></label>
                    <div class="col-sm-8">
                      <input type="hidden" name="Account Payable - Temporary" class="ap_temporaty">
                      <input type="text" class="form-control pointer" onclick="coa_modal('.ap_temporaty','coa_setting')" placeholder="<?= $this->lang->line('lb_select_data'); ?>" readonly >
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-sm-6">
                    <label class="col-sm-4 control-label"><?= $this->lang->line('lb_tax_in'); ?></label>
                    <div class="col-sm-8">
                      <input type="hidden" name="Account Payable - Tax In" class="ap_tax_in">
                      <input type="text" class="form-control pointer" onclick="coa_modal('.ap_tax_in','coa_setting')" placeholder="<?= $this->lang->line('lb_select_data'); ?>" readonly >
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-sm-6">
                    <label class="col-sm-4 control-label"><?= $this->lang->line('lb_tax_in_temp'); ?></label>
                    <div class="col-sm-8">
                      <input type="hidden" name="Account Payable - Tax In Temporary" class="ap_tax_in_temporary">
                      <input type="text" class="form-control pointer" onclick="coa_modal('.ap_tax_in_temporary','coa_setting')" placeholder="<?= $this->lang->line('lb_select_data'); ?>" readonly >
                    </div>
                  </div>

                  <div class="form-group col-sm-6">
                    <label class="col-sm-4 control-label"><?= $this->lang->line('lb_discount'); ?></label>
                    <div class="col-sm-8">
                      <input type="hidden" name="Account Payable - Discount" class="ap_discount">
                      <input type="text" class="form-control pointer" onclick="coa_modal('.ap_discount','coa_setting')" placeholder="<?= $this->lang->line('lb_select_data'); ?>" readonly >
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-sm-6">
                    <label class="col-sm-4 control-label"><?= $this->lang->line('lb_cost_sold'); ?></label>
                    <div class="col-sm-8">
                      <input type="hidden" name="Account Payable - Cost Of Goods Sald" class="ap_cost_goods">
                      <input type="text" class="form-control pointer" onclick="coa_modal('.ap_cost_goods','coa_setting')" placeholder="<?= $this->lang->line('lb_select_data'); ?>" readonly >
                    </div>
                  </div>

                  <!-- <div class="form-group col-sm-6">
                    <label class="col-sm-4 control-label">Inventory</label>
                    <div class="col-sm-8">
                      <input type="hidden" name="Account Payable - Inventory" class="ap_inventory">
                      <input type="text" class="form-control pointer" onclick="coa_modal('.ap_inventory','coa_setting')" placeholder="<?= $this->lang->line('lb_select_data'); ?>" readonly >
                    </div>
                  </div> -->
                </div>
              </div>

              <!-- AR -->
              <label><strong>2. <?= $this->lang->line('lb_coa_ar'); ?></strong></label>
              <div class="ml-15">
                <div class="row">
                  <div class="form-group col-sm-6">
                    <label class="col-sm-4 control-label"><?= $this->lang->line('lb_ar'); ?></label>
                    <div class="col-sm-8">
                      <input type="hidden" name="Account Receivable" class="ar">
                      <input type="text" class="form-control pointer" onclick="coa_modal('.ar','coa_setting')" placeholder="<?= $this->lang->line('lb_select_data'); ?>" readonly >
                    </div>
                  </div>

                  <div class="form-group col-sm-6">
                    <label class="col-sm-4 control-label"><?= $this->lang->line('lb_sales'); ?></label>
                    <div class="col-sm-8">
                      <input type="hidden" name="Account Receivable - Sales" class="ar_sales">
                      <input type="text" class="form-control pointer" onclick="coa_modal('.ar_sales','coa_setting')" placeholder="<?= $this->lang->line('lb_select_data'); ?>" readonly >
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-sm-6">
                    <label class="col-sm-4 control-label"><?= $this->lang->line('lb_ar_temp'); ?></label>
                    <div class="col-sm-8">
                      <input type="hidden" name="Account Receivable - Temporary" class="ar_temporary">
                      <input type="text" class="form-control pointer" onclick="coa_modal('.ar_temporary','coa_setting')" placeholder="<?= $this->lang->line('lb_select_data'); ?>" readonly >
                    </div>
                  </div>

                  <div class="form-group col-sm-6">
                    <label class="col-sm-4 control-label"><?= $this->lang->line('lb_sales_temp'); ?></label>
                    <div class="col-sm-8">
                      <input type="hidden" name="Account Receivable - Sales Temporary" class="ar_sales_temporary">
                      <input type="text" class="form-control pointer" onclick="coa_modal('.ar_sales_temporary','coa_setting')" placeholder="<?= $this->lang->line('lb_select_data'); ?>" readonly >
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-sm-6">
                    <label class="col-sm-4 control-label"><?= $this->lang->line('lb_tax_out'); ?></label>
                    <div class="col-sm-8">
                      <input type="hidden" name="Account Receivable - Tax Out" class="ar_tax_out">
                      <input type="text" class="form-control pointer" onclick="coa_modal('.ar_tax_out','coa_setting')" placeholder="<?= $this->lang->line('lb_select_data'); ?>" readonly >
                    </div>
                  </div>

                  <div class="form-group col-sm-6">
                    <label class="col-sm-4 control-label"><?= $this->lang->line('lb_discount'); ?></label>
                    <div class="col-sm-8">
                      <input type="hidden" name="Account Receivable - Discount" class="ar_discount">
                      <input type="text" class="form-control pointer" onclick="coa_modal('.ar_discount','coa_setting')" placeholder="<?= $this->lang->line('lb_select_data'); ?>" readonly >
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-sm-6">
                    <label class="col-sm-4 control-label"><?= $this->lang->line('lb_tax_out_temp'); ?></label>
                    <div class="col-sm-8">
                      <input type="hidden" name="Account Receivable - Tax Out Temporary" class="ar_tax_out_temporary">
                      <input type="text" class="form-control pointer" onclick="coa_modal('.ar_tax_out_temporary','coa_setting')" placeholder="<?= $this->lang->line('lb_select_data'); ?>" readonly >
                    </div>
                  </div>

                  <div class="form-group col-sm-6">
                    <label class="col-sm-4 control-label"><?= $this->lang->line('lb_returnar'); ?></label>
                    <div class="col-sm-8">
                      <input type="hidden" name="Account Receivable - Return Sales" class="ar_sales_return">
                      <input type="text" class="form-control pointer" onclick="coa_modal('.ar_sales_return','coa_setting')" placeholder="<?= $this->lang->line('lb_select_data'); ?>" readonly >
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-sm-6">
                    
                  </div>

                  <div class="form-group col-sm-6">
                    <label class="col-sm-4 control-label"><?= $this->lang->line('lb_income'); ?></label>
                    <div class="col-sm-8">
                      <input type="hidden" name="Account Receivable - Income" class="ar_income">
                      <input type="text" class="form-control pointer" onclick="coa_modal('.ar_income','coa_setting')" placeholder="<?= $this->lang->line('lb_select_data'); ?>" readonly >
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-sm-6">
                    <label class="col-sm-4 control-label"><?= $this->lang->line('lb_delivery_cost'); ?></label>
                    <div class="col-sm-8">
                      <input type="hidden" name="Account Receivable - Delivery Cost" class="ar_delivery_cost">
                      <input type="text" class="form-control pointer" onclick="coa_modal('.ar_delivery_cost','coa_setting')" placeholder="<?= $this->lang->line('lb_select_data'); ?>" readonly >
                    </div>
                  </div>

                  <!-- <div class="form-group col-sm-6">
                    <label class="col-sm-4 control-label">Inventory</label>
                    <div class="col-sm-8">
                      <input type="hidden" name="Account Receivable - Inventory" class="ar_inventory">
                      <input type="text" class="form-control pointer" onclick="coa_modal('.ar_inventory','coa_setting')" placeholder="<?= $this->lang->line('lb_select_data'); ?>" readonly >
                    </div>
                  </div> -->
                </div>
              </div>

              <!-- Inventory -->
              <label><strong>3. <?= $this->lang->line('lb_inventory'); ?></strong></label>
              <div class="ml-15">
                <div class="row">
                  
                  <div class="form-group col-sm-6">
                    <label class="col-sm-4 control-label"><?= $this->lang->line('lb_gains_stock'); ?></label>
                    <div class="col-sm-8">
                      <input type="hidden" name="Inventory - Gains Stock" class="inventory_gains">
                      <input type="text" class="form-control pointer" onclick="coa_modal('.inventory_gains','coa_setting')" placeholder="<?= $this->lang->line('lb_select_data'); ?>" readonly >
                    </div>
                  </div>

                  <div class="form-group col-sm-6">
                    <label class="col-sm-4 control-label"><?= $this->lang->line('lb_loss_stock'); ?></label>
                    <div class="col-sm-8">
                      <input type="hidden" name="Inventory - Loss Stock" class="inventory_loss_stock">
                      <input type="text" class="form-control pointer" onclick="coa_modal('.inventory_loss_stock','coa_setting')" placeholder="<?= $this->lang->line('lb_select_data'); ?>" readonly >
                    </div>
                  </div>

                  <div class="form-group col-sm-6">
                    <label class="col-sm-4 control-label"><?= $this->lang->line('lb_inventory'); ?></label>
                    <div class="col-sm-8">
                      <input type="hidden" name="Inventory - Inventory" class="inventory_inventory">
                      <input type="text" class="form-control pointer" onclick="coa_modal('.inventory_inventory','coa_setting')" placeholder="<?= $this->lang->line('lb_select_data'); ?>" readonly >
                    </div>
                  </div>
                
                </div>
              </div>

              <!-- Accounting -->
              <label><strong>4. <?= $this->lang->line('fitur_accounting'); ?></strong></label>
              <div class="ml-15">
                <div class="row">
                  <div class="form-group col-sm-6">
                    <label class="col-sm-4 control-label"><?= $this->lang->line('lb_cash'); ?></label>
                    <div class="col-sm-8">
                      <input type="hidden" name="AC - Cash" class="ac_cash">
                      <input type="text" class="form-control pointer" onclick="coa_modal('.ac_cash','coa_setting')" placeholder="<?= $this->lang->line('lb_select_data'); ?>" readonly >
                    </div>
                  </div>

                  <div class="form-group col-sm-6">
                    <label class="col-sm-4 control-label"><?= $this->lang->line('lb_transfer_credit'); ?></label>
                    <div class="col-sm-8">
                      <input type="hidden" name="AC - Transfer" class="ac_transfer">
                      <input type="text" class="form-control pointer" onclick="coa_modal('.ac_transfer','coa_setting')" placeholder="<?= $this->lang->line('lb_select_data'); ?>" readonly >
                    </div>
                  </div>

                  <div class="form-group col-sm-6">
                    <label class="col-sm-4 control-label"><?= $this->lang->line('lb_giro'); ?></label>
                    <div class="col-sm-8">
                      <input type="hidden" name="AC - Giro" class="ac_giro">
                      <input type="text" class="form-control pointer" onclick="coa_modal('.ac_giro','coa_setting')" placeholder="<?= $this->lang->line('lb_select_data'); ?>" readonly >
                    </div>
                  </div>

                  <div class="form-group col-sm-6">
                    <label class="col-sm-4 control-label"><?= $this->lang->line('lb_profit_year'); ?></label>
                    <div class="col-sm-8">
                      <input type="hidden" name="AC - Profit And Loss Current Year" class="ac_profit">
                      <input type="text" class="form-control pointer" onclick="coa_modal('.ac_profit','coa_setting')" placeholder="<?= $this->lang->line('lb_select_data'); ?>" readonly >
                    </div>
                  </div>

                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- End Panel Basic -->
  </div>
</div>
<?php $this->load->view("modal/modal_coa"); ?>

<link rel="stylesheet" href="<?= base_url('aset/css/dataTables.bootstrap.min.css'); ?>">
<script src="<?= base_url('aset/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('aset/js/dataTables.bootstrap.min.js'); ?>"></script>

<script src="<?= base_url('aset/js/page/coa_setting.js'); ?>"></script>
<script src="<?= base_url('aset/js/page/coa_modal.js'); ?>"></script>