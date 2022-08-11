<!-- Modal -->
<div id="modal-print" class="modal modal-primary fade modal-fade-in-scale-up" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog">
  <div class="modal-dialog width-80per">
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
       <div class="content-print table-responsive" id="view-print" style="min-height: 500px"></div>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <a href="#" id="link_print" target="_blank" class="btn btn-outline btn-default">Cetak</a>
          <div class="btn-group open">
             <button type="button" class="btn btn-white dropdown-toggle  waves-effect" data-toggle="dropdown" aria-expanded="true">
             PDF <span class="caret"></span> </button>
             <ul class="dropdown-menu">
                <li><a href="#" id="link_pdf_1" target="_blank">Portrait</a></li>
                <li><a href="#" id="link_pdf_2" target="_blank">Landscape</a></li>
             </ul>
          </div>
          <button type="button" class="btn btn-outline btn-default margin-0 kotak" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>