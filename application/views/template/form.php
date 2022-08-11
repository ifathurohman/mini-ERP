<div class="row">
   <form id="form" autocomplete="off">
      <input type="hidden" name="crud">
      <input type="hidden" name="TemplateID">
      <div class="row">
         <div class="form-group col-sm-12">
            <label class="control-label"><?= $this->lang->line('lb_select_file') ?></label>
            <input type="file" name="file" id="input-file-now" class="dropify" accept=".*"/>
            <!-- onchange="$('form').submit();" -->
         </div>
         <div class="col-sm-6">
            <div class="form-group">
               <label class="control-label"><?= $this->lang->line('lb_name') ?> <span class="wajib"></span></label>
               <input name="Name" id="Name" type="text" class="form-control" maxlength="20">
               <span class="help-block"></span>
            </div>
            <div class="form-group">
               <label class="control-label"><?= $this->lang->line('lb_type') ?> <span class="wajib"></span></label>
               <select name="Type" id="Type" class="form-control">
                  <option value="0"><?= $this->lang->line('lb_select_type') ?></option>
                  <optgroup label="<?= $this->lang->line('lb_purchase1') ?>">
                     <option value="purchase"><?= $this->lang->line('lb_purchase') ?></option>
                     <option value="penerimaan"><?= $this->lang->line('lb_goodrc') ?></option>
                     <option value="retur"><?= $this->lang->line('lb_returnap') ?></option>
                     <option value="invoice_ap"><?= $this->lang->line('lb_invoiceap') ?></option>
                     <option value="ap_correction"><?= $this->lang->line('lb_correctionap') ?></option>
                     <option value="payment_ap"><?= $this->lang->line('lb_paymentap') ?></option>
                  </optgroup>
                  <optgroup label="<?= $this->lang->line('lb_selling1') ?>">
                     <option value="selling"><?= $this->lang->line('lb_selling') ?></option>
                     <option value="delivery"><?= $this->lang->line('lb_delivery') ?></option>
                     <option value="return_sales"><?= $this->lang->line('lb_returnar') ?></option>
                     <option value="invoice_ar"><?= $this->lang->line('lb_invoicear') ?></option>
                     <option value="ar_correction"><?= $this->lang->line('lb_correctionar') ?></option>
                     <option value="payment_ar"><?= $this->lang->line('lb_paymentar') ?></option>
                  </optgroup>
               </select>
               <span class="help-block"></span>
            </div>
         </div>
         <div class="col-sm-6">
            <div class="form-group">
               <label class="control-label"><?= $this->lang->line('lb_remark') ?></label>
               <textarea name="Remark" id="Remark" class="form-control txtRemark" rows="5" maxlength="225"></textarea>
               <span class="help-block"></span>
            </div>
         </div>
         <div class="col-sm-12">
            <div class="form-group">
               <label class="control-label"><?= $this->lang->line('lb_content') ?></label>
               <textarea name="Content" id="Content" class="form-control"></textarea>
               <span class="help-block"></span>
            </div>
         </div>
      </div>
   </form>
   <div class="btn-group pull-right">
      <button id="btnSave" onclick="save()" type="button" class="btn btn-primary save"><?= $this->lang->line('btn_save') ?></button>
      <button type="button" class="btn btn-danger btn-default margin-0 kotak" onclick="change_div()"><?= $this->lang->line('btn_close') ?></button>
    </div>
</div>

