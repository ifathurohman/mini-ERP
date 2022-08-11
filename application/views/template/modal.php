<!-- Modal -->
<div id="modal-print" class="modal modal-primary fade modal-fade-in-scale-up" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
        <div class="div-loader">
          <div class="loader"></div>
       </div>
       <div class="content-print" id="view-print" style="min-height: 500px"></div>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <?= $this->main->button_action("action", array("edit","delete")); ?>
          <button type="button" class="btn btn-danger margin-0 kotak" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>