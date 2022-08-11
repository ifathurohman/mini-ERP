<!-- Modal -->
<div id="modal-verification-account" class="modal modal-primary fade modal-fade-in-scale-up" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" style="padding-top: 20px;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Kirim ulang kode verifikasi</h4>
      </div>
      <div class="modal-body">
        <div class="div-btn-verification">
          <div class="btn-group btn-block">
            <a href="javascript:;" onclick="send_verification_code(this)" data-id="<?= $a->id_user; ?>" data-modul="email" class="btn btn-primary width-80" style="text-align: left;">Kirim kode verifikasi ke email : <span class="text-email"><?= $a->Email; ?></span></a>
            <a href="javascript:;" class="btn btn-default width-20" onclick="change_verification(this)" data-id="<?= $a->id_user; ?>" data-modul="email">ubah</a>
          </div>
          <div class="btn-group btn-block">
            <a href="javascript:;" onclick="send_verification_code(this)" data-id="<?= $a->id_user; ?>" data-modul="phone" class="btn btn-success width-80" style="text-align: left;">Kirim kode verifikasi ke nomor : <span class="text-phone"><?= $a->PhoneNumber; ?></span></a>     
            <a href="javascript:;" class="btn btn-default width-20" onclick="change_verification(this)" data-id="<?= $a->id_user; ?>" data-modul="phone">ubah</a>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button type="button" class="btn btn-outline btn-default margin-0 kotak" data-dismiss="modal">tutup</button>     
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div id="modal-change-verification" class="modal modal-primary fade modal-fade-in-scale-up" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" style="padding-top: 20px;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
      	<form id="change-verification">
      		<div class="row">
      			<div class="col-sm-12 v_email">
      				<div class="form-group form-material">
      					<label class="label-control" for="EmailVerification">Alamat Email</label>
      					<input type="email" id="EmailVerification" name="EmailVerification" class="form-control" placeholder="ex : rc@xmail.com ">
      				</div>
      			</div>
      			<div class="col-sm-12 v_phone">
      				<div class="form-group form-material">
                     <label class="label-control" for="PhoneVerification">No Handphone</label>
                     <div class="div-phone">
                        <select id="mySelect" data-show-icon="true" class="selectpicker" name="PhoneCodeVerification">
                           <?php 
                              foreach($this->main->PhoneISO() as $a): 
                                $seleted = "";
                                if(strtolower($a->ISO) == "id"){
                                  // $seleted = 'seleted="" ';
                                }
                              ?>
                           <option value="<?= $a->PhoneISO; ?>" data-content="<span class='flag-icon  flag-icon-<?= strtolower($a->ISO)?>'></span> <?= "+".$a->PhoneISO ." - ".$a->Name; ?>"></option>
                           <?php endforeach;
                              ?>
                        </select>
                        <input type="text" class="form-control" id="PhoneVerification" name="PhoneVerification" placeholder="ex : 8962188xxx" onkeypress="return isNumber(event)">
                     </div>
                     <span class="help-block AlertPhone"></span>
                  	</div>
      			</div>
      		</div>
      	</form>
      </div>
      <div class="modal-footer">
        <div class="btn-group">
          <button type="button" class="btn btn-outline btn-default margin-0 kotak" data-dismiss="modal">tutup</button>     
          <button type="button" class="btn btn-primary" onclick="save_change_verification(this)">Kirim</button>
        </div>
      </div>
    </div>
  </div>
</div>