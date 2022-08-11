<!-- Modal -->
<div id="modal-import" class="modal fade modal-fade-in-scale-up" data-backdrop="static" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" >
  <div class="modal-dialog" style="width: 60%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body"> 
          <h5>1. <?= $this->lang->line('lb_product_download'); ?> 
          <a href="<?= site_url("product/export/template"); ?>"><?= $this->lang->line('lb_here'); ?></a></h5>
          <ul>
            <li><?= $this->lang->line('lb_file_note'); ?></li>
          </ul>
          <h5>2. <?= $this->lang->line('lb_file_entry'); ?></h5>
          <ul>
            <li><?= $this->lang->line('lb_use_excell'); ?></li>
            <li><?= $this->lang->line('lb_product_code'); ?>  : <?= $this->lang->line('lb_product_code_note1'); ?></li>
            <li><?= $this->lang->line('lb_category_code'); ?> : <?= $this->lang->line('lb_category_code_note'); ?></li>
            <li><?= $this->lang->line('lb_category_name'); ?> : <?= $this->lang->line('lb_category_name_note'); ?></li>
            <li><?= $this->lang->line('lb_product_name'); ?>  : <?= $this->lang->line('lb_product_name_requi'); ?></li>
            <li><?= $this->lang->line('lb_min_qty'); ?>       : <?= $this->lang->line('lb_min_qty_note'); ?></li>
            <li><?= $this->lang->line('lb_unit'); ?>          : <?= $this->lang->line('lb_unit_note'); ?></li>
            <li><?= $this->lang->line('lb_selling_price'); ?> : <?= $this->lang->line('lb_selling_price_requi'); ?></li>
            <li><?= $this->lang->line('lb_inventory'); ?>     : <?= $this->lang->line('lb_product_type_requi'); ?></li>
            <li><?= $this->lang->line('lb_selling1'); ?>      : <?= $this->lang->line('lb_selling_type_requi'); ?></li>
            <li><?= $this->lang->line('lb_serial'); ?>        : <?= $this->lang->line('lb_serial_type_requi'); ?></li>
          </ul>
          <h5>3. <?= $this->lang->line('lb_upload_file'); ?></h5>
          <ul>
            <li><?= $this->lang->line('lb_file_type'); ?></li>
          </ul>
          <form action="<?= site_url("product/import"); ?>" method="post" id="form-import" autocomplete="off" enctype="multipart/form-data" target="_blank">
            <div class="row">
              <div class="form-group col-sm-12">
                <label class="control-label"><?= $this->lang->line('lb_select_file'); ?></label>
                <input type="file" name="file" id="input-file-now" class="dropify" />
                <!-- onchange="$('form').submit();" -->
                <!-- <span>hanya untuk file sample yang di download</span> -->
              </div>
            </div>
            <!-- <input type="submit" name="submit" value="import"> -->
          </form>
          <div class="alert alert-warning"><?= $this->lang->line('lb_note'); ?>: <?= $this->lang->line('lb_excell_note'); ?></div>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button onclick="import_data()" type="button" class="btn btn-primary btn-import"><?= $this->lang->line('lb_next'); ?></button>
          <?= $this->main->general_button('close') ?>  
        </div>
      </div>
    </div>
  </div>
</div>