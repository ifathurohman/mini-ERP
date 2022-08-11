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
        <h5><?= $this->lang->line('lb_sales_import1'); ?>
          <a href="<?= site_url("sales/export/template"); ?>"><?= $this->lang->line('lb_here'); ?></a></h5>
        <ul>
          <li><?= $this->lang->line('lb_sales_import1_note1'); ?></li>
        </ul>
        <h5><?= $this->lang->line('lb_sales_import2'); ?></h5>
        <ul>
          <li><?= $this->lang->line('lb_sales_import2_note1'); ?></li>
          <li><?= $this->lang->line('lb_sales_import2_note2'); ?></li>
          <li><?= $this->lang->line('lb_sales_import2_note3'); ?></li>
          <li><?= $this->lang->line('lb_sales_import2_note4'); ?></li>
          <li><?= $this->lang->line('lb_sales_import2_note5'); ?></li>
          <li><?= $this->lang->line('lb_sales_import2_note6'); ?></li>
          <li><?= $this->lang->line('lb_sales_import2_note7'); ?></li>
        </ul>
        <h5><?= $this->lang->line('lb_sales_import3'); ?></h5>
        <ul>  
          <li><?= $this->lang->line('lb_sales_import3_note3'); ?></li>
        </ul>
        <form method="post" id="form-import" autocomplete="off" enctype="multipart/form-data" target="_blank">
          <div class="row">
            <div class="form-group col-sm-12">
              <label class="control-label"><?= $this->lang->line('lb_select_file'); ?></label>
              <input type="file" name="file" id="input-file-now" class="dropify" accept="application/vnd.ms-excel"/>
            </div>
          </div>
        </form>   
        <div class="alert alert-warning"><?= $this->lang->line('lb_import_alert'); ?></div>
      </div>
      <div class="modal-footer">
        <div class="btn-group"> 
          <button onclick="upload_import()" type="button" class="btn btn-primary btn-import"><?= $this->lang->line('lb_next'); ?></button>
          <button type="button" class="btn btn-danger btn-default margin-0 kotak" data-dismiss="modal"><?= $this->lang->line('lb_close'); ?></button>
        </div>
      </div>
    </div>
  </div>
</div>