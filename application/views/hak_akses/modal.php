<!-- Modal -->
<div id="modal" class="modal fade modal-fade-in-scale-up" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" tabindex="-1">
  <div class="modal-dialog" style="width: 95%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Modal Title</h4>
      </div>
      <div class="modal-body">
        <form class="col s12" method="post" id="form">
          <input type="hidden" name="id_hak_akses">
          <div class="form-group col-sm-6" id="div_hak_akses">
            <label class="control-label">User Privileges Name</label>
            <input name="nama_hak_akses" id="nama_hak_akses" type="text" class="form-control">
            <span class="help-block"></span>
          </div>
          <div class="form-group col-sm-12" id="div_hak_akses">
            <div class="nav-tabs-vertical">
              <ul class="nav nav-tabs nav-tabs-line" data-plugin="nav-tabs" role="tablist">
                <?php $i = 1; foreach($array as $ar): if($i++ == 1): $aktif = "active"; else: $aktif=""; endif;?>
                
                <li class="<?= $aktif; ?>" role="presentation">
                  <a data-toggle="tab" href="#<?= str_replace(" ", "_",$ar[1]); ?>" aria-controls="<?= str_replace(" ", "_",$ar[1]); ?>" role="tab"><?= $ar[1]; ?></a>
                </li>
                <?php endforeach; ?>
              </ul>
              <div class="tab-content padding-20">
                <?php $i = 1; foreach($array as $ar): if($i++ == 1):$aktif = "active"; else: $aktif=""; endif;?>
                <div class="tab-pane <?= $aktif; ?>" id="<?= str_replace(" ", "_",$ar[1]); ?>" role="tabpanel">
                  <div class="row">
                  <?php
                    $menu = $this->hak_akses->menu($ar[0]);
                    if(!empty($menu)):
                      foreach ($menu as $mn):
                    ?>
                    <div class="col-sm-3 col-lg-3">
                      <div class="checkbox-custom checkbox-primary">
                        <input class="icheckbox-primary" name="menu[]" id="idmenu<?= $mn->id_menu; ?>" type="checkbox" value="<?= $mn->id_menu; ?>">
                        <label for="idmenu<?= $mn->id_menu; ?>" style="text-transform: uppercase;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;"><?= $mn->nama_menu; ?></label>                      
                      </div>
                    </div>
                    <div class="col-sm-3 col-lg-3">
                      <div class="checkbox-custom checkbox-primary">
                        <input name="tambah[]" id="tambah<?= $mn->id_menu; ?>" type="checkbox" class="validate" value="<?= $mn->id_menu; ?>">
                        <label for="tambah<?= $mn->id_menu; ?>" style="text-transform: uppercase;">create</label>
                      </div>
                    </div>
                    <div class="col-sm-3 col-lg-3">
                      <div class="checkbox-custom checkbox-primary">
                        <input name="ubah[]" id="ubah<?= $mn->id_menu; ?>" type="checkbox" class="validate" value="<?= $mn->id_menu; ?>">
                        <label for="ubah<?= $mn->id_menu; ?>" style="text-transform: uppercase;">update</label>                      
                      </div>
                    </div>
                    <div class="col-sm-3 col-lg-3">
                      <div class="checkbox-custom checkbox-primary">
                        <input name="hapus[]" id="hapus<?= $mn->id_menu; ?>" type="checkbox" class="validate" value="<?= $mn->id_menu; ?>">
                        <label for="hapus<?= $mn->id_menu; ?>" style="text-transform: uppercase;">delete</label>                      
                      </div>
                    </div>
                    <?php 
                      endforeach;
                    else:
                      echo '<div class="col-sm-12 col-lg-12">Tidak Ada</div>';
                    endif;
                  ?>                  
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button id="btnSave" onclick="save()" type="button" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-danger margin-0" data-dismiss="modal">Close</button>          
        </div>
      </div>
    </div>
  </div>
</div>