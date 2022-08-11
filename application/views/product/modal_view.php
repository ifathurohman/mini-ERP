<!-- Modal -->
<div id="modal-view-product" class="modal modal-primary fade modal-fade-in-scale-up" data-backdrop="static" aria-hidden="true" >
  <div class="modal-dialog" style="width: 75%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
        <!-- <div class="row">
          <div class="col-sm-12">
            <h4 class="product_name"></h4>
          </div>
        </div> -->
        <table class="table-view-product table table-striped  table-hover datatables">
          <thead>
            <tr>
              <th><?= $this->lang->line('lb_no') ?></th>
              <th style="min-width: 120px"><?= $this->lang->line('lb_store_name') ?></th>
              <th style="max-width: 400px"><?= $this->lang->line('lb_address') ?></th>
              <th><?= $this->lang->line('lb_city') ?></th>
              <th><?= $this->lang->line('lb_qty') ?></th>
              <th><?= $this->lang->line('lb_purchase_price') ?></th>
              <th><?= $this->lang->line('lb_average_price') ?></th>
            </tr>  
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button type="button" class="btn btn-outline btn-default margin-0 kotak" data-dismiss="modal">Close</button>     
        </div>
      </div>
    </div>
  </div>
</div>