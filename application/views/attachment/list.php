<div class="page page-data" data-type="<?= $Type ?>" data-id="<?= $ID ?>" data-id2="<?= $ID2 ?>" data-hakakses="<?= $this->session->hak_akses; ?>">
  <div class="page-header">
  </div>
  <div class="page-content">
    <div class="panel panel-bordered">
      <header class="panel-heading">
        <div class="panel-actions"></div>
        <h3 class="panel-title"><?= $title; ?></h3>
      </header>
      <div class="panel-body">
        <div class="row" style="margin-bottom: 20px">
          <div class="col-sm-6">
            <form id="form_attachment" autocomplete="off">
              <input type="hidden" name="ID" value="<?= $ID; ?>">
              <input type="hidden" name="Type" value="<?= $Type; ?>">
              <div class="row">
                <div class="form-group col-sm-12">
                  <input type="file" name="photo[]" id="input-file-now" class="dropify" multiple="multiple" onchange="$('#form_attachment').submit();"/>
                </div>
              </div>
            </form>          
          </div>
          <div class="col-sm-6">
            <p><?= $format ?></p>
          </div>
        </div>
        <div class="row" id="attachment-list"></div>
      </div>
    </div>
  </div>
</div>
<link rel="stylesheet" href="<?= base_url('aset/css/dropify.min.css'); ?>">
<script src="<?= base_url('aset/js/dropify.min.js'); ?>"></script>
<link rel="stylesheet" href="<?= base_url("aset/plugin/toastr/toastr.min.css"); ?>">
<script src="<?= base_url("aset/plugin/toastr/toastr.min.js"); ?>"></script><!-- Footer -->
<script src="<?= base_url("aset/js/page/attachment.js".$this->main->js_css_version()); ?>"></script>