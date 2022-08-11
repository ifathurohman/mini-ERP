<!-- Modal -->
<div id="modal-dashboard-expire" class="modal modal-60 modal-primary fade modal-fade-in-scale-up" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" style="padding-top: 20px;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background: #8d0c0c;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title"><?= $this->lang->line('lb_module_device_expire') ?></h4>
      </div>
      <div class="modal-body" style="margin: 0px;padding: 0px">
        <div class="panel panel-map panel-bordered">
          <div class="panel-body">
            <div class="col-sm-12 vexpire-module-modal">
              <div class="panel panel-map panel-bordered">
                <div class="panel-heading panel-gb-header">
                  <h3 class="panel-title" style="color:#ff0000;text-align: center;"><?= $this->lang->line('lb_module_expire') ?></h3>
                  <div class="panel-actions panel-actions-keep" style="color: #e4e4e4">
                    <!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
                  </div>
                </div>
                <table id="table_module_modal" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
                     <thead>
                        <tr>
                          <th><?= $this->lang->line('lb_no') ?></th>
                          <th><?= $this->lang->line('module') ?></th>
                          <th><?= $this->lang->line('lb_expire_date') ?></th>
                          <th><?= $this->lang->line('lb_day_left') ?></th>
                        </tr>
                      </thead>
                    <tbody>
                    </tbody>
                  </table>
              </div>
            </div>

            <div class="col-sm-12 vexpire-devices-modal">
              <div class="panel panel-map panel-bordered">
                <div class="panel-heading panel-gb-header">
                  <h3 class="panel-title" style="color:#ff0000;text-align: center;"><?= $this->lang->line('lb_device_expire') ?></h3>
                  <div class="panel-actions panel-actions-keep">
                    <!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
                  </div>
                </div>
                <table id="table_devices_modal" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
                     <thead>
                        <tr>
                          <th><?= $this->lang->line('lb_no') ?></th>
                          <th><?= $this->lang->line('lb_store') ?></th>
                          <th><?= $this->lang->line('lb_expire_date') ?></th>
                          <th><?= $this->lang->line('lb_day_left') ?></th>
                        </tr>
                      </thead>
                    <tbody>
                    </tbody>
                  </table>
              </div>
            </div>

            <div class="col-sm-12 vexpire-additional-modal">
              <div class="panel panel-map panel-bordered">
                <div class="panel-heading panel-gb-header">
                  <h3 class="panel-title" style="color:#ff0000;text-align: center;"><?= $this->lang->line('lb_additional_expire') ?></h3>
                  <div class="panel-actions panel-actions-keep">
                    <!-- <a class="panel-action icon wb-minus" aria-expanded="true" data-toggle="panel-collapse" aria-hidden="true"></a> -->
                  </div>
                </div>
                <table id="table_additional_modal" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
                     <thead>
                        <tr>
                          <th><?= $this->lang->line('lb_no') ?></th>
                          <th><?= $this->lang->line('lb_name') ?></th>
                          <th><?= $this->lang->line('lb_expire_date') ?></th>
                          <th><?= $this->lang->line('lb_day_left') ?></th>
                        </tr>
                      </thead>
                    <tbody>
                    </tbody>
                  </table>
              </div>
            </div>

          </div>
        </div>
        <div class="list-info"></div>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <?= $this->main->general_button('close') ?>
        </div>
      </div>
    </div>
  </div>
</div>