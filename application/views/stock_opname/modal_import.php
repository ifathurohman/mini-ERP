<!-- Modal -->
<div id="modal-import" class="modal fade modal-fade-in-scale-up" data-backdrop="static" >
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
        <form action="<?= site_url("product/import"); ?>" method="post" id="form-import" autocomplete="off" enctype="multipart/form-data" target="_blank">
          <h5>1. <?= $this->lang->line('lb_product_download') ?>
          <a href="<?= site_url("stock_opname/export/template"); ?>"><?= $this->lang->line('lb_here') ?></a></h5>
          <ul>
            <li><?= $this->lang->line('lb_file_note') ?></li>
          </ul>
          <h5>2. <?= $this->lang->line('lb_file_entry') ?></h5>
          <ul>
            <li><?= $this->lang->line('lb_use_excell') ?></li>
            <li><?= $this->lang->line('lb_product_code') ?>: <?= $this->lang->line('lb_code_required') ?></li>
            <li><?= $this->lang->line('lb_product_code') ?>: <?= $this->lang->line('lb_product_inventory_active') ?></li>
            <li><?= $this->lang->line('lb_product_name') ?>: <?= $this->lang->line('lb_name_not_change') ?> </li>
            <li><?= $this->lang->line('price') ?>: <?= $this->lang->line('lb_price_required') ?> </li>
            <li><?= $this->lang->line('lb_stock_opname_qty') ?>: <?= $this->lang->line('lb_stock_op_required') ?> </li>
            <li><?= $this->lang->line('lb_stock_qty') ?>: <?= $this->lang->line('lb_stock_qty_not_change') ?></li>
            <li><?= $this->lang->line('lb_unit') ?>: <?= $this->lang->line('lb_unit_not_change') ?></li>
            <li><?= $this->lang->line('lb_remark') ?> : <?= $this->lang->line('lb_remark_opsional') ?></li>
            <li><?= $this->lang->line('lb_sn') ?> : <?= $this->lang->line('lb_sn_reqired') ?></li>
          </ul>
          <h5>3. <?= $this->lang->line('lb_store_select') ?></h5>
          <ul>
            <li><?= $this->lang->line('lb_store_required') ?></li>
          </ul>
          <div class="row">
            <div class="form-group col-sm-6">
              <div class="input-group">
                <span class="input-group-addon pointer" onclick="branch_modal('','.autocomplete_branch2')">
                 <i class="fa fa-search" aria-hidden="true"></i>
                </span>
                <input id="BranchName" type="text" class="form-control pointer readonly autocomplete_branch2-name" readonly="readonly" placeholder="<?= $this->lang->line('lb_store_select') ?>" onclick="branch_modal('','.autocomplete_branch2')">
                <input name="BranchID2" id="BranchID2" type="text" placeholder="<?= $this->lang->line('lb_store_select') ?>" class="form-control pointer readonly autocomplete_branch2 content-hide" data-select="active" onclick="branch_modal('','.autocomplete_branch2')">
                <span></span>
              </div>
            </div>
          </div>
          <h5>4. <?= $this->lang->line('lb_upload_file') ?></h5>
          <ul>
            <li><?= $this->lang->line('lb_file_type') ?></li>
          </ul>
            <div class="row">
              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_select_file') ?></label>
                <input type="file" name="file" id="input-file-now" class="dropify" accept="application/vnd.ms-excel"/>
                <!-- onchange="$('form').submit();" -->
              </div>
            </div>
            <!-- <input type="submit" name="submit" value="import"> -->
          <div class="alert alert-warning">
            <?= $this->lang->line('lb_excell_note') ?>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">    
          <button onclick="import_data()" type="button" class="btn btn-primary btn-import"><?= $this->lang->line('lb_next') ?></button>
          <?= $this->main->general_button('close') ?>
        </div>
      </div>
    </div>
  </div>
</div>