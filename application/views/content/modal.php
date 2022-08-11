<!-- Modal -->
<div id="modal" class="modal modal-primary fade modal-fade-in-scale-up">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
        <form id="form" autocomplete="off">
          <input name="ContentID" id="ContentID" type="hidden" class="form-control">
          <input name="method" id="method" type="hidden" class="form-control">
          <div class="row">
            <div class="form-group col-sm-12">
              <label class="control-label"></label>
              <input type="file" name="image" id="input-file-now" class="dropify"/>
              <!--  onchange="$('form').submit();" -->
              <span><?= $this->lang->line('note_img'); ?></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_name'); ?> <span class="wajib"></span></label>
              <input name="name" id="name" type="text" class="form-control" autocomplete="off">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_author'); ?></label>
              <input name="author" id="author" type="text" class="form-control" autocomplete="off">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="control-label"><?= $this->lang->line('lb_category'); ?> <span class="wajib"></span></label>
              <input name="category" id="category" type="text" class="form-control" autocomplete="off">
              <span class="help-block"></span>
            </div>
            <div class="form-group col-sm-6">
                <label class="control-label block">Status</label>
                <div class="radio-custom radio-primary radio-inline">
                  <input type="radio" name="status" id="publish" value="1" checked>
                  <label for="publish"><?= $this->lang->line('lb_publish'); ?></label>
                </div>
                <div class="radio-custom radio-primary radio-inline">
                  <input type="radio" id="unpublish" name="status" value="0">
                  <label for="unpublish"><?= $this->lang->line('lb_unpublish'); ?></label>
                </div>
            </div>
            <div class="form-group col-sm-12">
              <label class="control-label"><?= $this->lang->line('lb_content'); ?></label>
              <textarea name="content" id="content" type="text" class="form-control" autocomplete="off"></textarea>
              <span class="help-block"></span>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <?= $this->main->general_button('general_save') ?>
          <?= $this->main->general_button('close') ?>
        </div>
      </div>
    </div>
  </div>
</div>