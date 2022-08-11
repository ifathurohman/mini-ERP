var mobile  = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host    = window.location.origin+'/pipesys_qa/';
var url     = window.location.href;
var pathname = window.location.pathname;
var page_login = host + "login";
var page_register = host + "register";
var page_forgot = host + "forgot-password";
var page_resetpass = host + "reset-password";
var page_verification = host + "verification-account";
var hakakses;
var id_kontak = 0; 
var slim_modul;
var myVar;
var title_page = '';
var amountdecimal = 0;
var qtydecimal = 0;
var branchid_default = 0,branch_name_default = 0;
var txt_currency = '';

/* Fungsi formatRupiah */
function currencyFormat(angka, prefix,decimal_txt) {
  if(!decimal_txt){
    decimal_txt = 0;
  }
  min_txt     = angka.split("-");
  str_min_txt = '';
  var number_string = angka.replace(/[^.\d]/g, "").toString();
  number_string = removeduit(number_string).toString();
  split = number_string.split(".");

  // atur decimal
  decimal_value = split[1];
  if(decimal_value != undefined){
    if(decimal_value.length>decimal_txt){
      number_string = parseFloat(number_string);
      number_string = number_string.toFixed(decimal_txt);
      number_string = number_string.toString();
    }
  }

  split         = number_string.split(".");
  decimal_value = split[1];
  sisa          = split[0].length % 3;
  rupiah        = split[0].substr(0, sisa);
  ribuan        = split[0].substr(sisa).match(/\d{3}/gi);

  // tambahkan titik jika yang di input sudah menjadi angka ribuan
  if (ribuan) {
    separator = sisa ? "," : "";
    rupiah += separator + ribuan.join(",");
  }

  rupiah        = decimal_value != undefined ? rupiah + "." + decimal_value : rupiah;
  value         = prefix == undefined ? rupiah : rupiah ? "" + rupiah : "";

  if(min_txt.length>1){
    if(min_txt[0] == ""){
      str_min_txt = "-";
    }
  }

  return str_min_txt+value;
}

var run_function = '';
function create_format_currency(){
  $('.duit').attr('maxlength',50);
  $('.duit').on('blur change', function(){
    if(run_function){eval(run_function)}
    create_format_currency2();
  });
  $('.duit').on('keyup focus',function(){
    tg_data = $(this).data();
    if(tg_data.qty){
      decimal_txt = qtydecimal;
    }else{
      decimal_txt = amountdecimal;
    }
    min_txt     = this.value.split("-");
    str_min_txt = '';
    value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
    value = value.toString();
    split = value.split(".");
    decimal_value = split[1];
    value = split[0];
    if(decimal_value != undefined){
      if(decimal_value.length>decimal_txt){
        decimal_value = decimal_value.substr(0,decimal_txt).toString();
        value = value +"."+ decimal_value;
      }else{
        value = value +"."+ decimal_value;
      }
    }
    if(min_txt.length>1){
      if(min_txt[0] == ""){
        str_min_txt = "-";
      }
    }

    this.value = str_min_txt+value;
  });
}

function create_format_currency2(){
  length_class = $('.duit').length;
  for (var i = 0; i < length_class; i++) {
    tg_data = $('.duit').eq(i).data();
    if(tg_data.qty){
      decimal_txt = qtydecimal;
    }else{
      decimal_txt = amountdecimal;
    }

    value = $('.duit').eq(i).val();  
    value = currencyFormat(value, "", decimal_txt);
    $('.duit').eq(i).val(value);
  }
}

$(document).ready(function() {
    $("[type=text]").attr("maxlength",250);
    $(".txtRemark").attr("maxlength",250);
    $(".txtCode").attr("maxlength",20);
    $("[type=number]").attr("maxlength",20);
    $(".angka").attr("maxlength",25);
    $("[name=category_code]").attr("maxlength",10);
    $("[name=phone]").attr("maxlength",20);
    page_data = $(".page-data").data();
    // $('[data-toggle="tooltip"]').tooltip(); 
    if(page_data){
        hakakses  = page_data.hakakses;
    }
    uri_hash = window.location.hash;
    if(url == page_login){
      post("login");
    } else if(url == page_register) {
      var nama_toko = "";
      var nama      = "";
      var email     = "";
      var password  = "";
      post("register");
      $("#btn-login").attr("disabled",true);
      $("[name=nama_toko],[name=nama_perusahaan],[name=email],[name=password]").keyup(function(){
        if(this.name == "nama_toko"){
          nama_toko = $(this).val();
        } else if(this.name == "nama_perusahaan"){
          nama = $(this).val();
        } else if(this.name == "email"){
          email = $(this).val();
        } else if(this.name == "password"){
          password = $(this).val();
        } else if(this.name == "no_hp"){
          no_hp = $(this).val();
        }
        if(nama_toko != "" && nama != "" && email != "" && password != ""){
          $("#btn-login").attr("disabled",false);
        } else {
          $("#btn-login").attr("disabled",true);
        }
      });
    } else if(url == page_forgot) {
      post("forgot_password");  
      $("#btn-login").attr("disabled",true);
      email = "";
      $("[name=email]").keyup(function(){
        if(this.name == "email"){
          email = $(this).val();
        } 
        if(email != ""){
          $("#btn-login").attr("disabled",false);
        } else {
          $('#form').removeClass('has-error'); // clear error class
          $('.help-block').empty();
          $("#btn-login").attr("disabled",true);
        }
      });
    } else if(url == page_resetpass || uri_hash == "#reset") {
      post("reset_password");
      $("#btn-login").attr("disabled",true);
      password = "";
      password_kon = "";
      $("[name=password],[name=password_kon]").keyup(function(){
        if(this.name == "password"){
          password = $(this).val();
        } else if(this.name == "password_kon"){
          password_kon = $(this).val();
        } 
        if(password != "" && password_kon != ""){
          $("#btn-login").attr("disabled",false);
        } else {
          $('#form').removeClass('has-error'); // clear error class
          $('.help-block').empty();
          $("#btn-login").attr("disabled",true);
        }
      });
    } else if(url == page_verification){
      post("verification_account");
    } else if(url == host+"company-information" || url == host+"main/company_information"){
      company_information("company");
    } else if(url == host+"user-account" || url == host+"main/user_account"){
      company_information("user_account");
    } else if(url == host+"page-setting-parameter" || url == host+"main/setting_parameter"){
      company_information("setting_parameter");
    }
    if(url == host+"product"){
      category_list_option();
      unit_list_option();
    }
    if(url == host+"product-service"){
      category_list_option();
      unit_list_option();
    }
});
$(document).ready(function(){
  default_setting_parameter();
  create_format_currency();
  angka();
  if($("div, input").hasClass("date")){
    date();
    $("form .date").attr("readonly","");
  }
  init_plugin();

  val_modul = $('.get_modul');
  if(val_modul.length>0){
    check_menu_modul();
  }

  $(".modal").on('shown.bs.modal', function(e){
      index = $(this).attr('id');
      classModalBody = $('#'+index+' .modal-body');
      classModalBody.animate({ scrollTop: 0 }, 'fast');
      check_dropdown(classModalBody);
  });

  $('#toTop').fadeOut();
  $(window).on('scroll', function() {
    if ($(this).scrollTop() > 100) {
      $('#toTop').fadeIn();
    } else {
      $('#toTop').fadeOut();
    }
  });

  $('#toTop').on('click', function(e) {
    $("html, body").animate({scrollTop: 0}, 500);
  });
});

function validateNumber(e) {
   // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
         // Allow: Ctrl/cmd+A
        (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
         // Allow: Ctrl/cmd+C
        (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
         // Allow: Ctrl/cmd+X
        (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
         // Allow: home, end, left, right
        (e.keyCode >= 35 && e.keyCode <= 39)) {
             // let it happen, don't do anything
             return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
}

function angka()
{
  $(".angka").keyup(function(){
    this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
  });
  $('.angkaint').keyup(function(){
    this.value = this.value.replace(/\D/g,'');
  });
  $('#angka1').maskMoney();
  $('#angka2').maskMoney({prefix:'US$'});
  $('.rupiah').maskMoney({prefix:'Rp. ', thousands:'.', decimal:',', precision:0});
  // $('.duit').maskMoney({allowNegative: true,thousands:',', decimal:'.', precision:2,selectAllOnFocus: true});
  $('.perKM').maskMoney({prefix:'Rp. ', thousands:'.', decimal:',', precision:0, suffix:' Per KM', });
  $('.perMnt').maskMoney({prefix:'Rp. ', thousands:'.', decimal:',', precision:0, suffix:' Per Menit', });
  $('.uang').maskMoney();
  $('.km').maskMoney({ thousands:'.', decimal:',', precision:0, suffix:' KM', });
}

function numberonly(a){
  a.value = a.value.replace(/\D/g,'');
}

function init_plugin()
{  
  if ($("select").hasClass("coa_select")) {
    coa_select();
  }
  if($("select").hasClass("template_select")){
    template_select();
  }
  if($("select").hasClass("city_select")){
    city_select();
  }
  if ($("input, div").hasClass("date")) {
      container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
      $(".date").datepicker({
          format: 'dd-mm-yyyy',
          container: container,
          todayHighlight: true,
          autoclose: true,
      });
  }
  if ($("div,input").hasClass("attachment_upload")) {
    {
      img_preview();
    }
  }
  if ($("input").hasClass("dropify")) {
      $('.dropify').dropify();
  }
  if ($("select").hasClass("select2")) {
     $(".select2").select2();
  }
  if($("span").hasClass("wajib")){
    $(".wajib").html("(*)");
  }
  if($("div").hasClass("progress-data")){
    item = '<div class="progress progress-striped active">\
            <div class="progress-bar progress-bar-primary" style="width:0%"></div>\
          </div>';
    $('.progress-data').html(item);
  }

  if($("div").hasClass("ck-module-expire")){
    ck_module_expire();
  }
  
  if($('input').hasClass("text-char")){
    $('.text-char').on('keyup',function(){
      val = $(this).val().replace(/[^a-zA-Z]/g,'');
      $(this).val(val);
    });
  }
  if($('input').hasClass('autocomplete-unit')){
    $('.autocomplete-unit').on('keyup',function(){
      select_unit_autocomplete(this);
    });
  }
}

function post(page)
{
  url = "";
  $("#form").submit(function( event ) {
      $('#btn-login').button('loading');
      event.preventDefault();
      $('.form-group').removeClass('has-error'); // clear error class
      $('.help-block').empty();

  if(page == "login"){
    url = host + "api/login";
  } else if(page == "register"){
    url = host + "api/register"
  } else if(page == "forgot_password"){
    url = host + "api/forgot_password"
  } else if(page == "reset_password"){
    url = host + "api/reset_password"
  } else if(page == "verification_account"){
    url = host + "api/verification_account"
  }
      $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {
          if(data.status){
            if(page == "login" || page == "register" || page == "verification_account" || page == "setting_parameter"){
             if(page == "register"){
                swal({
                title: "Informasi",
                text: data.message,
                type: "success",
                confirmButtonText: "OK"
                }, function(isConfirm){
                  if (isConfirm) {
                    window.location.href = data.redirect;
                  }
                });
              } else {
                window.location.href = data.redirect;         
              }            
            } 
            else if(page == "register" || page == "reset_password" || page == "forgot_password"){
              swal({
                title: "Informasi",
                text: data.message,
                type: "success",
                confirmButtonText: "OK"
              }, function(isConfirm){
                if (isConfirm) {
                  // window.location.href = data.redirect;
                }
              });  
              $('#form')[0].reset();            
            }
            else {
              swal('Informasi',data.message,'success');
              $('#form')[0].reset();
            }

            if(page == "register" || page == "forgot_password" || page == "reset_password"){
              $("#btn-login").attr("disabled",true);
            }
          }
          else{
            if(data.popup){
              swal('Informasi',data.message,'error');
            } else {
              if(data.inputerror){
                for (var i = 0; i < data.inputerror.length; i++){
                    if(data.inputerror[i] == "Phone"){
                      $(".Alert"+data.inputerror[i]).parent().addClass('has-error'); 
                      $(".Alert"+data.inputerror[i]).text(data.error_string[i]);
                    } else {
                      $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                      $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                    }
                }
              }
            }
          }
          $('#btn-login').button('reset');
          
          // $('#btnSave').text('save'); //change button text
          // $('#btnSave').attr('disabled',false); //set button enable
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          console.log(jqXHR.responseText);
          $('#btn-login').button('reset');
          // $("#pesan-error").show();
          // $("#text-pesan-error").text(data.pesan); 
        }
      });
  });
}
category_list_option();
function category_list_option()
{
    $.ajax({
        url : host+"api/category",
        type: "POST",
        dataType: "JSON",
        success: function(data){
            $(".category_option option").remove();
            $.each(data, function(i, v) {
                item = "<option value='"+v.category_code+"'>"+v.category_name+"</option>";
                $(".category_option").append(item);
            });
        },
        error: function (jqXHR, textStatus, errorThrown){
        }
    });
}
function unit_list_option(page="")
{
    unitid = $(".unit_option").val();
    if(page == "select"){
      data_post = {page:page,unitid:unitid};      
    } else {
      data_post = {page:"list"};
      $(".unit_option").attr('onclick', 'unit_list_option("select")');
    }
    $.ajax({
        url : host+"api/unit",
        type: "POST",
        dataType: "JSON",
        data:data_post,
        success: function(data){
            if(page == "select"){
              if(unitid == null){
                $(".conversion").val("0.00");
              } else {
                $(".conversion").val(data.conversion);
              }
            } else {
              $(".unit_option option").remove();
              $.each(data, function(i, v) {
                  item = "<option value='"+v.unitid+"'>"+v.unit_name+"</option>";
                  $(".unit_option").append(item);
              });
            }
        },
        error: function (jqXHR, textStatus, errorThrown){
        }
    });
}

function add_contact(data)
{
    id_kontak += 1; 
    eid_kontak = "item-div-contact-"+id_kontak;
    c_kotak = "'"+eid_kontak+"'";

    BankName         = "";
    Cabang           = "";
    AnRekening       = "";
    NoRekening       = "";
    ID               = "";
    if(data){
        BankName         = data.BankName;
        Cabang           = data.BankBranch;
        AnRekening       = data.AnRekening;
        NoRekening       = data.NoRekening;
        ID               = data.UserRekID;
    }

    item = '<div  id="'+eid_kontak+'">';
    item += '<div class="form-group item-div-contact">';
    item += '<div class="col-sm-12">';
    item += '<label class="control-label">'+language_app.lb_bank_name+'</label>';
    item += '<input type="hidden" name="ID[]" class="form-control" value="'+ID+'">';
    item += '<input type="text" name="CBankName[]" class="form-control" value="'+BankName+'">';
    item += '<span class="help-block"></span>';
    item += '</div>';
    item += '</div>';

    item += '<div class="form-group">';
    item += '<div class="col-sm-12">';
    item += '<label class="control-label">'+language_app.lb_branch_name+'</label>';
    item += '<input type="text" name="BankCabang[]" class="form-control" value="'+Cabang+'">';
    item += '<span class="help-block"></span>';
    item += '</div>';
    item += '</div>';

    item += '<div class="form-group">';
    item += '<div class="col-sm-12">';
    item += '<label class="control-label">'+language_app.lb_bank_acount+'</span></label>';
    item += '<input type="text" name="CAnRekening[]" class="form-control" value="'+AnRekening+'">';
    item += '<span class="help-block"></span>';
    item += '</div>';
    item += '</div>';

    item += '<div class="form-group">';
    item += '<div class="col-sm-12">';
    item += '<label class="control-label">'+language_app.lb_bank_acountno+'</span></label>';
    item += '<input type="text" name="CNoRekening[]" class="form-control" value="'+NoRekening+'">';
    item += '<span class="help-block"></span>';
    item += '<a href="javascript:void(0)" onclick="remove_contact('+c_kotak+')">'+language_app.lb_remove_bank+'</a><hr/></div>';
    item += '</div>';
    item += '</div>';


    if($(".div-contact .item-div-contact").length >= 4){
        alert(language_app.lb_bank_max);
        return;
    }
    $(".div-contact").append(item);
}
function remove_contact(element)
{
    if($(".div-contact .item-div-contact").length == 1){
        alert(language_app.lb_bank_min_1);
        return;
    }
    $('#'+element).remove();
}
function remove_contact_all()
{
    id_kontak = 1;
    $(".div-contact div").remove();
}


var txt_locked    = '';
var txt_unlocked  = '';
var expire_date_ap= '';
var expire_date_ar= '';
var expire_date_ac= '';
var expire_date_inventory= '';
function company_information(page = "")
{ 
  txt_locked    = '<i class="fa fa-lock" aria-hidden="true"></i> '+language_app.lb_locked;
  txt_unlocked  = '<i class="fa fa-unlock-alt" aria-hidden="true"></i> '+language_app.lb_unlocked;
  data_post = {
    modul : pathname,
  }
  $.ajax({
      url : host+"api/company",
      type: "POST",
      data : data_post,
      dataType: "JSON",
      success: function(data){
        if(hakakses == "super_admin"){
          console.log(data);
        }
        if(page == "company"){
          
          $('.dropify').dropify();

          $('[name="nama"]').val(data.nama);
          $('[name="address"]').val(data.address);
          $('[name="city"]').val(data.city);
          $('[name="province"]').val(data.province);
          $('[name="country"]').val(data.country);
          $('[name="postal"]').val(data.postal);
          $('[name="phone"]').val(data.phone_company);
          $('[name="PhoneCode"]').val(data.PhoneCode);
          $('select[name=PhoneCode]').change();
          $('[name="fax"]').val(data.fax);
          $('[name="npwp"]').val(data.npwp);
          $(".dropify-preview").show();

          $('[name="ID"]').val(data.UserRekID);
          $('[name="CBankName"]').val(data.BankName); 
          $('[name="BankCabang"]').val(data.BankBranch);
          $('[name="CAnRekening"]').val(data.AnRekening);
          $('[name="CNoRekening"]').val(data.NoRekening);

          remove_contact_all();
           $.each(data.bank, function(i, v) {
             add_contact(v);
          });
          img = 'noimage.png';
          if(data.img_bin){
            img = "logo/"+data.img_bin;
          }
          img = host + "img/"+img;
          img = '<img src="'+img+'" />';
          $(".dropify-render").append(img);

        } else if(page == "user_account"){
          // $('[name="first_name"]').val(data.first_name);
          // $('[name="last_name"]').val(data.last_name);
          $('[name="email"]').val(data.email);
          // $('[name="phone"]').val(data.phone);  
          // $('[name="PhoneCode"]').val(v.PhoneCode);
          // $('select[name=PhoneCode]').change();         
          $('[name="password"]').val("undefined");          
        } else if(page == "setting_parameter"){
          slim_modul.set([]);
          if(data.SettingParameterID){
            $("[name=settingparameterid]").val(data.SettingParameterID);
            $('[name=currency]').val(data.Currency);
            if(data.AmountDecimal){
              if(data.AmountDecimal == 0){
                item = '<option value="0">0</option>';
                item += '<option value="2">2</option>';
                item += '<option value="4">4</option>';
              }else if(data.AmountDecimal == 2){
                item = '<option value="2">2</option>';
                item += '<option value="4">4</option>';
              }else if(data.AmountDecimal == 4){
                item = '<option value="4">4</option>';
              }else{
                item = '<option value="0">0</option>';
                item += '<option value="2">2</option>';
                item += '<option value="4">4</option>';
              }
              $('#amountdecimal').empty();
              $('#amountdecimal').append(item);
              $('[name=amountdecimal]').val(data.AmountDecimal);
            }
            if(data.QtyDecimal){
              item = '';
              for (var i = data.QtyDecimal; i <= 4; i++) {
                item += '<option value="'+i+'">'+i+'</option>';
              }
              $('#qtydecimal').empty();
              $('#qtydecimal').append(item);
              $('[name=qtydecimal]').val(data.QtyDecimal);
            }
            $('[name=negativestock]').val(data.NegativeStock);
            $('[name=costmethod]').val(data.CostMethod);
            $('[name=datasetting]').val(data.DataSetting);
            if(data.Days){
              data_days = jQuery.parseJSON(data.Days);
              slim_modul.set(data_days);
            }
            if(data.Module){
              dtModule  = jQuery.parseJSON(data.Module);
              dtAP      = jQuery.parseJSON(data.AP);
              dtAR      = jQuery.parseJSON(data.AR);
              dtAC      = jQuery.parseJSON(data.AC);
              datenow   = new Date();
              
              expire_date_ap = dtModule.ap.expire;
              $('.vap #timer2').data('expire', expire_date_ap);
              setInterval("updateTimer('.vap #timer2','"+expire_date_ap+"')", 1000 );
              if(dtModule.ap.status == 1 && new Date(dtModule.ap.expire+" 23:59:59") >= datenow){
                $('.vap input, .vap button').attr('disabled', false);
              }else{
                $('.vap input, .vap button').attr('disabled', true);
              }

              expire_date_ar = dtModule.ar.expire;
              $('.var #timer2').data('expire', expire_date_ar);
              setInterval("updateTimer('.var #timer2','"+expire_date_ar+"')", 1000 );
              if(dtModule.ar.status == 1 && new Date(dtModule.ar.expire+" 23:59:59") >= datenow){
                $('.var input, .var button').attr('disabled', false);
              }else{
                $('.var input, .var button').attr('disabled', true);
              }

              expire_date_inventory = dtModule.inventory.expire;
              $('.vinventory #timer2').data('expire', expire_date_inventory);
              setInterval("updateTimer('.vinventory #timer2','"+expire_date_inventory+"')", 1000 );
              if(dtModule.inventory.status == 1 && new Date(dtModule.inventory.expire+" 23:59:59") >= datenow){
                $('.vinventory input, .vinventory button').attr('disabled', false);
              }else{
                $('.vinventory input, .vinventory button').attr('disabled', true);
              }

              expire_date_ac = dtModule.ac.expire;
              $('.vac #timer2').data('expire', expire_date_ac);
              setInterval("updateTimer('.vac #timer2','"+expire_date_ac+"')", 1000 );
              if(dtModule.ac.status == 1 && new Date(dtModule.ac.expire+" 23:59:59") >= datenow){
                $('.vac input, .vac button').attr('disabled', false);
              }else{
                $('.vac input, .vac button').attr('disabled', true);
              }
              $('.input_voucher').attr('disabled', false);

              // detailnya
              if(data.AP){
                arrAP   = jQuery.parseJSON(data.AP);
              }else{
                arrAP = [];
              }
              if(data.AR){
                arrAR   = jQuery.parseJSON(data.AR);
              }else{
                arrAR = [];
              }
              if(data.AC){
                arrAC   = jQuery.parseJSON(data.AC);
              }else{
                arrAC = [];
              }
              if(data.Inventory){
                arrInv  = jQuery.parseJSON(data.Inventory);
              }else{
                arrInv = [];
              }

              if(arrAP.length > 0){
                if(findArray(arrAP, "ap")){
                  $('#ap').prop('checked', true);
                  unlock_expire('.vap');
                  $.each(arrAP, function(k,i){
                    if(findArray(arrAP,i) != "ap"){
                      if(findArray(arrAP,i)){
                        $('#'+i).prop('checked', true);
                      }
                    }
                  })
                }else{
                  locked_expire('.vap');
                  $('.vdap').hide(0);
                  $('.vdap input').attr('disabled', true);
                }
              }else{
                locked_expire('.vap');
                $('.vdap').hide(0);
                $('.vdap input').attr('disabled', true);
              }

              if(arrAR.length > 0){
                if(findArray(arrAR, "ar")){
                  $('#ar').prop('checked', true);
                  unlock_expire('.var');
                  $.each(arrAR, function(k,i){
                    if(findArray(arrAR,i) != "ar"){
                      if(findArray(arrAR,i)){
                        $('#'+i).prop('checked', true);
                      }
                    }
                  })
                }else{
                  locked_expire('.var');
                  $('.vdar').hide(0);
                  $('.vdar input').attr('disabled', true);
                }
              }else{
                locked_expire('.var');
                $('.vdar').hide(0);
                $('.vdar input').attr('disabled', true);
              }

              if(arrInv.length > 0){
                if(findArray(arrInv, "inventory")){
                  $('#inventory').prop('checked', true);
                  unlock_expire('.vinventory');
                  $.each(arrInv, function(k,i){
                    if(findArray(arrInv,i) != "inventory"){
                      if(findArray(arrInv,i)){
                        $('#'+i).prop('checked', true);
                      }
                    }
                  })
                }else{
                  locked_expire('.vinventory');
                  $('.vdinventory').hide(0);
                  $('.vdinventory input').attr('disabled', true);
                }
              }else{
                locked_expire('.vinventory');
                $('.vdinventory').hide(0);
                $('.vdinventory input').attr('disabled', true);
              }

              if(arrAC.length > 0){
                if(findArray(arrAC, "ac")){
                  unlock_expire('.vac');
                  $('#ac').prop('checked', true);
                  $.each(arrAC, function(k,i){
                    if(findArray(arrAC,i) != "ac"){
                      if(findArray(arrAC,i)){
                        $('#'+i).prop('checked', true);
                      }
                    }
                  })
                }else{
                  locked_expire('.vac');
                  $('.vdac').hide(0);
                  $('.vdac input').attr('disabled', true);
                }
              }else{
                locked_expire('.vac');
                $('.vdac').hide(0);
                $('.vdac input').attr('disabled', true);
              }
            }         
          }
        }

      },
      error: function (jqXHR, textStatus, errorThrown){
        console.log("error get company dta");
        console.log(jqXHR.responseText);
      }
  });
}

function locked_expire(dt_class){
    $(dt_class+' button, '+dt_class+' .badge').removeClass('bg-green');
    $(dt_class+' button, '+dt_class+' .badge').addClass('bg-red-wall');
    $(dt_class+' button').html(txt_locked);
    $(dt_class+' .badge').text(language_app.btn_nonactive);
}

function unlock_expire(dt_class){
    $(dt_class+' button, '+dt_class+' .badge').removeClass('bg-red-wall');
    $(dt_class+' button, '+dt_class+' .badge').addClass('bg-green');
    $(dt_class+' button').html(txt_unlocked);
    $(dt_class+' .badge').text(language_app.btn_active);
}

function findArray(array,value){
  if(array.indexOf(value) != -1){
    indx = array.indexOf(value);
    return array[indx];
  }else{
    return 0;
  }
}

function company_save(page)
{
    btn_title = "";
    if(page == "company"){
      btn_title = language_app.btn_save_company;
    } else if(page == "user_account") {
      btn_title = language_app.btn_save_user_account;
    }
    else if(page == "setting_parameter"){
      btn_title = language_app.lb_save_setting;

    }
    proses_save_button();
    var form = $('form')[0]; // You need to use standard javascript object here
    var formData = new FormData(form);
    $.ajax({
        url : host+"api/company_save/"+page,
        type: "POST",
        // data: $('#form').serialize(),
        data:  formData,
        mimeType:"multipart/form-data", // upload
        contentType: false, // upload
        cache: false, // upload
        processData:false, //upload
        dataType: "JSON",
        success: function(data)
        {
            if(hakakses == "super_admin"){
              console.log(data);
            }
            if(data.status){
              // swal('Informasi',data.message,'success');
              $('.form-group').removeClass('has-error'); // clear error class
              $('.help-block').empty(); // clear error string
              swal({   
                title: language_app.lb_information,
                text : data.message,   
                type: "success",   
                showCancelButton: false,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: language_app.lb_ok,   
                closeOnConfirm: false, 
                showLoaderOnConfirm: true, 
                closeOnCancel: false }, 
                function(isConfirm){   
                    if (isConfirm) { 
                        location.reload();
                    }
              });
            }
            else
            {
              $('.form-group').removeClass('has-error'); // clear error class
              $('.help-block').empty();
              
              if(data.message){
                    swal('',data.message,'warning');
              }
              if(data.inputerror){
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                    //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
              }
            }
            success_save_button();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error pdate data');
            success_save_button();
            console.log(jqXHR.responseText);
        }
    });
}


function modal_notransaction(classnya = ""){
  $('#form-format')[0].reset();
  $("#modal-notransaksi").modal("show");
  $("#modal-notransaksi .modal-title").text("Serial Number Format");
  $("[name=format_class]").val(classnya);
}
function save_format()
{
  format_class  = $("[name=format_class]").val();
  format        = $("[name=format]").val();
  format2       = $("[name=format2]").val();
  // format        = "["+format+"]";
  if(format != "select"){
    format      = format+"/";
  } else {
    format      = "";
  }
  if(format2){
    format2     = format2+"/";
  }

  format_from   = $("[name=format_from]").val();
  $("."+format_class).val(format2+format+format_from);
  $("#modal-notransaksi").modal("hide");

}


//serial number 
var noserial;
function add_serial(page ="",id) {
    // console.log(id);
    save_method = "add_serial";
    $(".disabled").attr("disabled",true);
    $(".readonly").attr("readonly",true);
    $('.table-add-serial tbody').children( 'tr' ).remove();
    if(page == "penerimaan"){
      url = host + "penerimaan/ajax_edit_serial/" + id;      
    } 
    else if(page == "penerimaan_view"){
      url = host + "penerimaan/ajax_edit_serial/" + id+"/view";  
      page = "view";  
    }
    else if(page == "mutasi"){
      url = host + "mutasi/ajax_edit_serial/" + id;
    } else if(page == "retur"){
      url = host + "retur/ajax_edit_serial/" + id;
    }else if(page == "selling"){
      url = host + "selling/ajax_edit_serial/" + id;
    }else if(page == "purchase"){
      url = host + "purchase_order/ajax_edit_serial/" + id;
    }else if(page == "delivery_non_order"){
      url = host + "delivery/ajax_edit_serial/"+id;
    }
    // console.log(url);
    $.ajax({
        url : url,
        type: "GET",
        dataType: "JSON",
        success: function(data){
            if(data.hakakses == "super_admin"){
              console.log(data);
            }
            noserial = 0;
            if(data.list_serial.length > 0 || data.list_serial != ""){
                $.each(data.list_serial, function(i, v) {
                    add_row_serial(page,v.productserialid,v.serialnumber);
                });
            } else {
                count = 0
                if(data.serial_number){
                  count = data.serial_number.length;
                }
                if(count>0){
                  $.each(data.serial_number, function(i, v) {
                      add_row_serial("serial_id",v.serial_id,v.SerialNumber);
                  });
                }else{
                  if(data.product_type == "general"){
                      add_row_serial(page,"",data.serialno);
                  } else if(data.product_type == "serial") {
                      for (var i = 1; i <= data.serial_qty; i++){
                          add_row_serial(page,"");
                      }
                  }
                }
            }
            $('#form-serial [name=receipt_det]').val(data.receipt_det);
            $('#form-serial [name=header_code]').val(data.code);
            $('#form-serial [name=detail_code]').val(data.detail_code);
            $('#form-serial [name=productid]').val(data.productid);
            $('#form-serial [name=product_type]').val(data.product_type);
            $('#form-serial [name=product_name]').val(data.product_name);
            $('#form-serial [name=serial_qty]').val(data.serial_qty);
            $('#form-serial [name=page]').val(page);
            // $('#modal-add-serial').modal({backdrop: false, keyboard: true}); // show bootstrap modal
            $('#modal-add-serial').modal("show"); // show bootstrap modal
            $('#modal-add-serial .modal-title').text('Add Serial Number'); // Set Title to Bootstrap modal title
            

            // autocomplete_serialnumber(".autocomplete_serialnumber");
        },
        error: function (jqXHR, textStatus, errorThrown){
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);
        }
    });
}
function add_row_serial(page,productserialid,serial_number,branchid)
{

    if(!serial_number){
      serial_number = '';
    }
    noserial +=1;
    idclass  = noserial - 1;
    input_serialid = "";
    input_branchid = "";
    if(productserialid && page == "serial_id"){
        input_serialid = '<input type="hidden" name="serial_id[]" value="'+productserialid+'">';
    }else{
      input_serialid = '<input type="hidden" name="productserialid[]" value="'+productserialid+'">';
    }
    if(branchid){
        input_branchid = '<input type="hidden" name="branchid[]" value="'+branchid+'">';
    }

    item = '<tr class="vserial vserial-'+idclass+'">\
        <td style="padding:0px 10px !important;">'+noserial+'</td>\
        <td>'+input_serialid+input_branchid+'<input class="autocomplete_serialnumber" type="text" name="serial_number[]" value="'+serial_number+'" onkeyup="autocomplete_serialnumber(this)"></td>\
      </tr>';
    $(".table-add-serial tbody").append(item);
    if(page == "view"){
      $('.vserial input').attr('readonly', true);
      $('.btn-add-serial').hide();
    }else{
      $('.vserial input').attr('readonly', false);
      $('.btn-add-serial').show();
    }
}
function save_serial()
{
    page = $("#form-serial [name=page]").val();
    $("#form input").attr("disabled",false);
    proses_save_button();
    var url;
    form = "#form";
    if(save_method == "add_serial"){
      if(page == "penerimaan"){
        url = host + "penerimaan/simpan_serial";;
      } else if(page == "mutasi"){
        url = host + "mutasi/simpan_serial";;
      } else if(page == "retur"){
        url = host + "retur/simpan_serial";;
      }
      else if(page == "selling"){
        url = host + "selling/save_serial";
      }
      else if(page == "purchase"){
        url = host + "purchase_order/save_serial";
      }else if(page == "delivery_non_order"){
        url = host + "delivery/save_serial";
      }
      form = "#form-serial";
    }
    $.ajax({
        url : url,
        type: "POST",
        data: $(form).serialize(),
        dataType: "JSON",
        success: function(data)
        {
            if(data.status){
              $(".disabled").attr("disabled",true);
              if(data.page){
                $('#modal-add-serial').modal("hide");
                swal('',data.message, 'success');
                if(page == "selling"){
                  sn_status       = data.sn_status;
                  $('#form-pembayaran [name=sn_status]').val(sn_status);
                }
              }
            } else {
              swal('',data.message, 'warning');
            }
            success_save_button();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            success_save_button();
            console.log(jqXHR.responseText);
        }
    });
    if(save_method == "add_serial"){
      $("#form input,#form textarea").attr("disabled",true);
    }
}
function modal_sellno(page = "")
{
  $('#modal-sellno').modal('show'); // show bootstrap modal
  $('#modal-sellno .modal-title').text('Sell No'); // Set Title to Bootstrap modal title
  $.ajax({
    url : host+"api/sell",
    type: "GET",
    dataType: "JSON",
    success: function(data){
      if(data.hakakses == "super_admin"){
        console.log(data);            
      }
      tbl = $('.table-sellno').DataTable();
      tbl.clear();
      $.each(data.list_data, function(i, v) {
        sellno      = v.sellno;
        vendorid    = v.vendorid;
        vendorname  = v.vendorname;
        date        = v.date;
        data_param  = 'data-page="'+page+'" data-sellno="'+sellno+'" data-vendorid="'+vendorid+'" data-vendorname="'+vendorname+'"';
        item = '<tr>\
                  <td>\
                    <a href="javascript:void(0)"  onclick="chose_sellno(this)" '+data_param+'>'+sellno+'</a>\
                  </td>\
                  <td>\
                    <a href="javascript:void(0)"  onclick="chose_sellno(this)" '+data_param+'>'+vendorname+'</a>\
                  </td>\
                  <td class="tb-date">\
                    <a href="javascript:void(0)"  onclick="chose_sellno(this)" '+converttoDate(date)+'>'+vendorname+'</a>\
                  </td>\
                </tr>';
        tbl.row.add( $(item)[0] ).draw();
      });
      $('.tb-date').hide(300);
    },
    error: function (jqXHR, textStatus, errorThrown){
        alert('Error Modal Sell No');
    }
  });
}
function chose_sellno(v){
  v = $(v).data();
  if(v.page == "retur"){
    $("[name=sellno]").val(v.sellno);
    $("[name=vendorid]").val(v.vendorid);
    $("[name=vendorname]").val(v.vendorname);
    sell_detail(v.page,v.sellno);
  }
  $('#modal-sellno').modal('hide'); // show bootstrap modal
}
function sell_detail(page="",search=""){
  tbl = ".table-add-product";
  $(tbl + " tbody tr").remove();
  $.ajax({
    url : host+"api/sell_detail/"+page+"/"+search,
    type: "GET",
    dataType: "JSON",
    success: function(data){
      if(data.hakakses == "super_admin"){
        console.log(data);            
      }
      if(data.list_data.length > 0){
        $.each(data.list_data, function(i, v) {
        productid     = v.productid;
        product_code  = v.product_code;
        product_name  = v.product_name;
        sellprice     = v.sellprice;
        item = ' <tr>\
                    <td width="30px">\
                    <input type="checkbox" name="cekbox[]" value="1">\
                    <input type="hidden" name="productid[]" value="'+productid+'">\
                    <input type="hidden" name="sellprice[]" value="'+sellprice+'">\
                    </td>\
                    <td>'+product_code+'</td>\
                    <td>'+product_name+'</td>\
                    <td><input type="text" name="product_qty[]" placeholder="set qty"></td>\
                    <td>'+sellprice+'</td>\
                    <td><input type="text" name="remark[]" placeholder=""></td>\
                  </tr>';
        $(tbl + " tbody").append(item);        
        });
      } else {
        item = ' <tr>\
                    <td  colspan="6" style="text-align:center">Product Data Empty</td>\
                  </tr>';
        $(tbl + " tbody").append(item);        
        
      }

    },
    error: function (jqXHR, textStatus, errorThrown){
        alert('Error get selling product');
    }
  });
}
function date(){
    container = $('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
    $(".date").datepicker({
        format: 'yyyy-mm-dd',
        container: container,
        todayHighlight: true,
        autoclose: true,
    });
}


// autocomplete
function autocomplete_serialnumber(classnya)
{
    productid = $("[name=productid]").val();
    $(classnya).autocomplete({
      minLength:0,
      delay:0,
      max:10,
      scroll:true,
      source: function(request, response) {
          $.ajax({ 
              url: host + "api/autocomplete_serialnumber/"+productid,
              data: { search: $(classnya).val(), productid: productid},
              dataType: "json",
              type: "POST",
              success: function(data){
                  response(data);
              }    
          });
      },
      select:function(event, ui){
          // productid = ui.item.productid;  
      }
    });
}

// coa
function coa_select()
{
  halaman   = "all";
  select    = "all";
  level     = "all";
  selected  = false;
  vs_data   = $(".coa_select").data();
  if(vs_data.select){
    halaman = vs_data.halaman;
    select  = vs_data.select;
    level   = vs_data.level;
    selected   = vs_data.selected;
  }
  data_post = {
    select : select,
    level : level
  }
  $('.coa_select').empty();
  $.ajax({
        url : host + "api/coa_select/",
        type: "POST",
        dataType: "JSON",
        data:data_post,
        success: function(data){
          item = '<option value="none">Pilih COA</option>';
          $(".coa_select").append(item);
          if(data.length > 0){
            $.each(data,function(i,v){
              item = '<option value="'+v.ID+'" id="COA-CODE-'+v.Code+'" data-name="'+v.Name+'" data-id="'+v.ID+'" data-code="'+v.Code+'" data-level="'+v.Level+'" data-payment="'+v.PaymentType+'">'+v.Code+" - "+v.Name+'</option>';
              $(".coa_select").append(item);
            });
            if(selected){
              console.log(selected);
              $('.coa_select').val(selected).trigger('change');
            }
          }
          data_page   = $(".data-page, .page-data").data();
          tab         = data_page.tab;
          if(tab == 'pembayaran'){
            coa_tipe_bayar(2);
          }
        },
        error: function (jqXHR, textStatus, errorThrown){
            alert('error get data, please try again');
            console.log(jqXHR.responseText);
        }
    });
}

var default_TemplateID = 0;
function template_select(){
  select    = "all";
  type      = "";
  position  = "";
  vs_data   = $(".template_select").data();
  if(vs_data.select){
    select = vs_data.select;
  }
  if(vs_data.type){
    type = vs_data.type;
  }
  if(vs_data.position){
    position = vs_data.position;
  }
  data_post = {
    select   : select,
    type     : type,
    position : position,
  }
  $('.template_select').empty();
  $.ajax({
        url : host + "api/template_select/",
        type: "POST",
        dataType: "JSON",
        data:data_post,
        success: function(data){
          if(data.hakakses == "super_admin"){
            console.log(data);
          }
          if(position != "group"){
            item = '<option value="0">Select Template</option>';
            $(".template_select").append(item);
            if(data.data.length > 0){
              $.each(data.data,function(i,v){
                tag_data = ' data-image="'+host+v.Image+'"';
                item = '<option value="'+v.ID+'" '+tag_data+'>'+v.Name+'</option>';
                $(".template_select").append(item);
              });
            }
            if(data.default){
              default_TemplateID = data.default.TemplateID;
            }
          }else{
            // purchase
            myarray = ['hakakses','page'];
            $.each(data,function(k,d){
              if(jQuery.inArray(k, myarray) == -1){
                // item = '<option value="0">Select Template</option>';
                // $(".template_"+k).append(item);
                if(data[k].list.length>0){
                  $.each(data[k].list, function(i,v){
                    tag_data = ' data-image="'+host+v.Image+'"';
                    item = '<option value="'+k+"-"+v.ID+'" '+tag_data+'>'+v.Name+'</option>';
                    $(".template_"+k).append(item);
                  });
                  if(data[k].default){
                    check_template_select('.template_'+k,data[k].default,k);
                  }
                }
              }
            });
          }
        },
        error: function (jqXHR, textStatus, errorThrown){
            console.log(jqXHR.responseText);
        }
    });

}

function template_view_image(classnya){
  val      = $(classnya+" option:selected").val();
  tag_data = $(classnya+" option:selected").data();
  if(val != 0){
    window.open(tag_data.image, '_blank');
  }else{
    swal('','Please select template','warning');
  }
}

function check_template_select(classnya,data,page){
  d = data.TemplateID;
  if(page){
    d = page+"-"+data.TemplateID;
  }
  val = $(classnya+" option[value='"+d+"']").length;
  if(val>0){
    $(classnya).val(d);
  }
}

function city_select(){
  select    = "all";
  vs_data   = $(".city_select").data();
  if(vs_data.select){
    select = vs_data.select;
  }
  data_post = {
    select : select,
  }
  $('.template_select').empty();
  $.ajax({
        url : host + "api/city_select/",
        type: "POST",
        dataType: "JSON",
        data:data_post,
        success: function(data){
          if(data.hakakses == "super_admin"){
            console.log(data);
          }
          item = '<option value="0">Select City</option>';
          $(".city_select").append(item);
          if(data.list.length > 0){
            $.each(data.list,function(i,v){
              item = '<option>'+v.DeliveryCity+'</option>';
              $(".city_select").append(item);
            });
          }
        },
        error: function (jqXHR, textStatus, errorThrown){
            alert('error get data city');
            console.log(jqXHR.responseText);
        }
    });
}

function moneyFormat(txt){
  if(txt){run_function = txt}else{run_function = '';}
  $(".duit").unbind();
  create_format_currency();
    // $('.duit').maskMoney({allowNegative: true,thousands:',', decimal:'.', precision:2,selectAllOnFocus: true});
    // $('.duit').each(function(){ // function to apply mask on load!
    //     $(this).maskMoney('mask', $(this).val());
    // });
}

function angkaFormat(){
  $(".angka").keyup(function(){
    this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
  });
}

function decimalFormat(element){
  element.value = element.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
}

function PersenttoRp(total,persen){
  total   = parseFloat(total);
  persen  = parseFloat(persen);
  xpersen = persen/100;
  xpersen = math.format(xpersen, {precision: 14})
  hasil   = total*xpersen;
  return hasil;
}

function RptoPersent(Total,Rp){
  Total  = parseFloat(Total);
  Rp     = parseFloat(Rp);
  hasil  = (Rp/Total) * 100;

  return hasil;
}

function checkFloatInput(val){
  if(val == ''){
    val = 0.00;
  }
  return val;
}

function checkIntInput(val){
  if(val == ''){
    val = 0;
  }

  return val;
}

function checkNan(val){
  if(Number.isNaN(val)){
    val = 0;
  }

  return val;
}

function number_format(nStr,p1){
    txt_decimal = 0;
    if(p1 == 'qty'){
      txt_decimal = qtydecimal;
    }else if(p1 == "currency"){
      txt_decimal = amountdecimal;
    }

    nStr = parseFloat(nStr);
    nStr = nStr.toFixed(txt_decimal);

    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
      x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }

    val = x1 + x2;
    return val;
}

function removeduit(val){
  val = val.replace(/,/g, '').replace(/(\..*)\./g, '$1');
  if(val == ''){
    val = 0;
  }
  return parseFloat(val);
}

function remove_value(inputnya){
  $('[name='+inputnya+']').val('');
}

function converttoDate(data,format){
   var date = '';
   if(data){
      if(format){
        date =  $.datepicker.formatDate(format, new Date(data));
      }else{
        date =  $.datepicker.formatDate('dd-mm-yy', new Date(data));
      }
   }

   return date;
}

function show_password(namenya){
  // nama inputan dan class groupnya harus sama
  data    = $('.'+namenya+' .btn-pass').data();
  status  = data.status;
  if(status == 1){
    data    = $('.'+namenya+' .btn-pass').data('status', 0);
    $('[name='+namenya+']').attr('type','password');
    $('.'+namenya+' .btn-pass i').removeClass('fa-eye-slash');
    $('.'+namenya+' .btn-pass i').addClass('fa-eye');
  }else{
    data    = $('.'+namenya+' .btn-pass').data('status', 1);
    $('[name='+namenya+']').attr('type','text');
    $('.'+namenya+' .btn-pass i').removeClass('fa-eye');
    $('.'+namenya+' .btn-pass i').addClass('fa-eye-slash');
  }
}

// add serial 2
function add_serial2(page,id){
  save_method = "add_serial";
  $(".readonly").attr("readonly",true);
  $('#modal-add-serial2 .table-add-serial tbody').children( 'tr' ).remove();
  if(page == "delivery"){
    url = host + "delivery/ajax_edit_serial/" + id;
  }

  $.ajax({
        url : url,
        type: "GET",
        dataType: "JSON",
        success: function(data){
            if(data.hakakses == "super_admin"){
              console.log(data);
            }
            from_page = '';
            $('#form-serial2 [name=page]').val(page);
            $('#form-serial2 [name=productid]').val(data.productid);
            $('#form-serial2 [name=product_type]').val(data.product_type);
            $('#form-serial2 [name=product_name]').val(data.product_name);
            $('#form-serial2 [name=serial_qty]').val(data.serial_qty);
            $('#form-serial2 [name=detail_code]').val(data.detail_code);
            $('#form-serial2 [name=header_code]').val(data.code);
            $('#form-serial2 [name=product_type_txt]').val(data.product_type_txt);

            if(page == "delivery"){
              from_page = 'selling';
            }

            if(data.list_serial.length >0){
              $.each(data.list_serial, function(k,v){
                checked     = '';
                serial_id   = '';
                $.each(data.serial_number, function(kk,vv){
                  if(vv.SerialNumber == v.SerialNumber){
                    serial_id = vv.serial_id;
                    checked   = ' checked ';
                  }
                });
                item  = '<tr>';
                item += '<td width="50px"><input type="hidden" name="serial_id[]" value="'+serial_id+'">\
                        <input type="checkbox" name="serial_checkbox[]" value="'+v.SerialNumber+'" '+checked+'>\
                        <input type="hidden" name="serial_number[]" value="'+v.SerialNumber+'">\
                        </td>';
                item += '<td><input type="text" class="disabled" value="'+v.SerialNumber+'">\</td>';
                item += '</tr>';
                $("#modal-add-serial2 .table-add-serial tbody").append(item);
              });
            }else{
              item = '<tr><td><div class="text-center">Please add serial in '+from_page+' page</div></td></tr>';
              $("#modal-add-serial2 .table-add-serial tbody").append(item);
            }
            $(".disabled").attr("disabled",true);
            $('#modal-add-serial2').modal("show"); // show bootstrap modal
            $('#modal-add-serial2 .modal-title').text('Add Serial Number'); // Set Title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown){
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);
        }
    });
}

function save_serial2(){
  page = $("#form-serial2 [name=page]").val();
    $("#form2 input").attr("disabled",false);
    $('#btnSave, .save').text('saving...'); //change button text
    $('#btnSave, .save').attr('disabled',true); //set button disable
    var url;
    form = "#form";
    if(save_method == "add_serial"){
      if(page == "delivery"){
        url = host + "delivery/save_serial";
      }
      else if (page == "stock"){
        url = host + "Koreksi_stok/save_serial"
      }
      form = "#form-serial2";
    }
    $.ajax({
        url : url,
        type: "POST",
        data: $(form).serialize(),
        dataType: "JSON",
        success: function(data)
        {
            // console.log(data);
            if(data.status){
              swal('',data.message, 'success');
              $(".disabled").attr("disabled",true);
              if(data.page){
                $('#modal-add-serial2').modal("hide");
              }
            } else {
              swal('',data.message,'warning');
            }
            $('#btnSave, .save').attr('disabled',false); //set button enable
            $('#btnSave, .save').text('save'); //change button text
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave, .save').text('save'); //change button text
            $('#btnSave, .save').attr('disabled',false); //set button enable
            console.log(jqXHR.responseText);
        }
    });
}
// end add serial 2


function keyup_vendor_price(row,group,page){
    group_val = $(group).val();
    if(group_val == ""){

    }else{
        autocomplete_vendor_price(row,group_val,page);
    }
}
function autocomplete_vendor_price(row,group_val,page){
    if(page == "vendor"){
      classnya = ".autocomplete_vendor_price";
    }else{
      classnya = ".rowid_"+row+" .autocomplete_vendor_price";
    }
    $(classnya).autocomplete({
    minLength:1,
    max:10,
    scroll:true,
    source: function(request, response) {
        $.ajax({ 
            url: host + "api/autocomplete_vendor_price",
            data: { search: group_val},
            dataType: "json",
            type: "POST",
            success: function(data){
                response(data);
            }    
        });
    },
    select:function(event, ui){
        label = ui.item.label;
        // $(".rowid_"+row+" .p_id").val(productid);
    }
  });
}

function open_modal_template(id,page){
  // $('#form-modal-template')[0].reset(); // reset form on modals
  // $('#modal-template').modal('show');
  // $('#modal-template .modal-title').text('Select Template');
  // $('#modal-template .btn-next').attr('onclick','check_template('+"'"+id+"','"+page+"'"+')');
  // $('.template_select').val(0);
  // $('.template-image').empty();
  // if($('.template_select option').length>0){
  //   if(default_TemplateID>0){
  //       val = $(".template_select option[value='"+default_TemplateID+"']").length;
  //       if(val>0){
  //         $(".template_select").val(default_TemplateID);
  //         $('#default_template').prop('checked', true);
  //       }else{
  //         $('#default_template').prop('checked', false);
  //         $(".template_select").val($(".template_select option:eq(1)").val());
  //       }
  //   }else{
  //     $('#default_template').prop('checked', false);
  //     $(".template_select").val($(".template_select option:eq(1)").val());
  //   }
  // }
  // change_template();
  view_print_data(id,page);
}
function close_modal_template(){
  $('#modal-template').modal('hide');
  $('.template_select').val(0);
  $('.template-image').empty();
}
function change_template(element){
  var option = $('.template_select option:selected').data();
  $('.template-image').empty();
  if(option.image){
    image = option.image;
    item = '<img class="auto-image" src="'+image+'" />';
    $('.template-image').append(item);
  }
}

function check_template(id,page){
  var option = $('.template_select option:selected').val();
  if(option == 0){
    swal('','please select template print','warning');
  }else{
    $('#modal-template').modal('hide');
    view_print_data(id,page);
  }
}

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function limit(element,limit){
    var max_chars = limit;
    if(element.value.length > max_chars) {
        element.value = element.value.substr(0, max_chars);
    }
}
function autoTabLimit(element,len, field2) {
  var max_chars = len;
  if (element.value.length >= len) {
    document.getElementById(field2).focus();
  }
  if(element.value.length > max_chars) {
      element.value = element.value.substr((element.value.length - 1), max_chars);
  }
}

var VerificationAction;
var VerificationID;
var VerificationModul;
function verification_account_modal(){
  $("#modal-verification-account").modal("show");
}
function send_verification_code(element){
  $(element).button("loading");
  dt      = $(element).data();
  UserID  = dt.id;
  Modul   = dt.modul; 
  VerificationAction  = "verification_account";
  VerificationID      = UserID;
  VerificationModul   = Modul;
  data_post = {
    Action : VerificationAction,
    UserID : UserID,
    Modul  : Modul
  }
  post_verification(element,data_post);
  
}
function change_verification(element){
  dt     = $(element).data();
  UserID = dt.id;
  Modul  = dt.modul; 
  VerificationAction  = "change_verification";
  VerificationID      = UserID;
  VerificationModul   = Modul;
  $("#modal-change-verification .v_email, #modal-change-verification .v_phone").hide();
  if(Modul == "email"){
    title = "Ubah alamat email dan kirim ulang kode verifikasi baru";
    $("#modal-change-verification .v_email").show();
  } else {
    title = "Ubah Nomor Handphone dan kirim ulang kode verifikasi baru";
    $("#modal-change-verification .v_phone").show();

  }
  $('select[name=PhoneCodeVerification]').val("62");
  $('.selectpicker').selectpicker('refresh');
  $("#modal-change-verification input").val("");
  $("#modal-change-verification .modal-title").text(title);
  $("#modal-change-verification").modal("show");
}
function save_change_verification(element){
  $(element).button("loading");
  data_post = {
    Action : VerificationAction,
    UserID : VerificationID,
    Modul  : VerificationModul,
    Email  : $("[name=EmailVerification]").val(),
    PhoneCode : $("[name=PhoneCodeVerification]").find("option:selected").val(),
    Phone : $("[name=PhoneVerification]").val(),
  }
  post_verification(element,data_post);
}
function post_verification(element,data_post){
  url = host;
  if(VerificationAction == "verification_account"){
    url = host + "api/send_verification_code";
  } else if(VerificationAction == "change_verification"){
    url = host + "api/change_verification";
  }
  $.ajax({
    url : url,
    type: "POST",    
    data: data_post,
    dataType: "JSON",
    success: function(data){
      $(element).button("reset");
      if(data.status){
        swal("Informasi",data.message,"success");
        
        if(VerificationAction == "change_verification"){
          if(VerificationModul == "email"){
            $(".text-email").text(data.Email);
          } else if(VerificationModul == "phone"){
            $(".text-phone").text(data.Phone);
          }
          $("#modal-change-verification").modal("hide");
        }
      } else {
        swal("Informasi",data.message,"error");
      }
    },
    error: function (jqXHR, textStatus, errorThrown){
      $(element).button("reset");
      console.log(jqXHR.responseText);

    }
  });
}

function check_menu_modul(){
  val_modul = $('.get_modul');
  no = 0;
  arr_modul = [];
  for(i= 0; i<val_modul.length;i++){
    no += 1;
    val_data = $('.get_modul').eq(i).data();
    arr_modul.push(val_data);
  }

  data_post = {
    modul : arr_modul,
  }
  url = host+"api/check_menu_modul";
  $.ajax({
    url : url,
    type: "POST",    
    data: data_post,
    dataType: "JSON",
    success: function(data){
      if(data.hakakses == "super_admin"){
        console.log(data);
      }
      v_ar = 0;
      v_ap = 0;
      v_ac = 0;
      v_inventory = 0;
      $.each(data.modul,function(k,v){
        
        // ar
        if (jQuery.inArray("ar", v.modul) !== -1) {
          if(v.view == 0){
            $('.'+v.classnya).remove();
          }else{
            if(v.page != "master"){
              v_ar = 1;
            }
          }
        }

        // ap
        if (jQuery.inArray("ap", v.modul) !== -1) {
          if(v.view == 0){
            $('.'+v.classnya).remove();
          }else{
            if(v.page != "master"){
              v_ap = 1;
            }
          }
        }

        // ac
        if (jQuery.inArray("ac", v.modul) !== -1) {
          if(v.view == 0){
            $('.'+v.classnya).remove();
          }else{
            if(v.page != "master"){
              v_ac = 1;
            }
          }
        }

        // inventory
        if (jQuery.inArray("inventory", v.modul) !== -1) {
          if(v.view == 0){
            $('.'+v.classnya).remove();
          }else{
            if(v.page != "master"){
              v_inventory = 1;
            }
          }
        }
      });

      if(v_ar == 0){ $('.v-ar').remove(); }
      if(v_ap == 0){ $('.v-ap').remove(); }
      // if(v_ac == 0){ $('.v-ac').remove(); }
      if(v_inventory == 0){ $('.v-inventory').remove(); }
    },
    error: function (jqXHR, textStatus, errorThrown){
      console.log(jqXHR.responseText);

    }
  });
}

function validate_btn_view(page){
  data_page   = $(".data-page, .page-data").data();

  if(page == "print"){
    $('.vbtn-view').show();
  }else{
    if(data_page.hakakses == "super_admin"){
      $('.vbtn-view').show();
    }else{
      $('.vbtn-view').hide();
    }
  }
}

function action_print_button(url){
    $('.vaction').show();
    $('.btnSave').hide();
    $("#link_print").attr("href",url+"&cetak=cetak");
    $("#link_pdf_1").attr("href",url+"&cetak=pdf&position=portrait");
    $("#link_pdf_2").attr("href",url+"&cetak=pdf&position=landscape");
}
function set_button_action(data){
  status = 0;
  if(data.attachment){
    $(".btn-attachment").show();
    $("#link_attachment").attr("href",data.attachment);
    status = 1;
  }else{
    $(".btn-attachment").hide();
  }

  if(data.next){
    $('.btn-next').show();
    $('.btn-next').append(data.next);
    status = 1;
  }else{
    $('.btn-next').hide();
  }
  if(data.cancel){
    $('.btn-cancel').show();
    $('.btn-cancel').append(data.cancel);
    status = 1;
  }else{
    $('.btn-cancel').hide();
  }
  if(data.delete){
    $('.btn-delete').show();
    $('.btn-delete').append(data.delete);
    status = 1;
  }else{
    $('.btn-delete').hide();
  }

  if(data.edit){
    $('.btn-edit-data').show();
    $('.btn-edit-data').append(data.edit);
     status = 1;
  }else{
    $('.btn-edit-data').hide();
  }

  if(data.view){
    $('.btn-view-data').show();
    $('.btn-view-data').append(data.view);
    status = 1;
  }else{
    $('.btn-view-data').hide();
  }

  if(data.view_serial){
    $('.btn-view_serial-data').show();
    $('.btn-view_serial-data').append(data.view_serial);
    status = 1;
  }else{
    $('.btn-view_serial-data').hide();
  }

  if(data.customer_price){
    $('.btn-customer_price-data').show();
    $('.btn-customer_price-data').append(data.customer_price);
    status = 1;
  }else{
    $('.btn-customer_price-data').hide();
  }

  if(data.product_branch){
    $('.btn-product_branch-data').show();
    $('.btn-product_branch-data').append(data.product_branch);
    status = 1;
  }else{
    $('.btn-product_branch-data').hide();
  }

  if(status == 1){
    $('.vaction2').show();
  }else{
    $('.vaction2').hide();
  }
}
function reset_button_action(){
  $("#link_print, #link_pdf_1, #link_pdf_2, #link_attachment").attr("href","javascript:;");
  $('.btn-next, .btn-cancel, .btn-delete, .btn-edit-data, .btn-view-data, .btn-view_serial-data, .btn-customer_price-data, .btn-product_branch-data').empty();
  $('.vaction').hide();
  $('.btnSave').show();
  $('.btn-back, .btn-save2').attr('onclick', 'javascript:;');
}

function show_button_cancel(){
  $('.vaction, .btn-close').hide();
  $('.btn-back, .btn-save2, .btn-close-attach').show();
}
function hide_button_cancel(){
  $('.vaction, .btn-close').show();
  $('.btn-back, .btn-save2, .btn-close-attach').hide();
}
function hide_button_cancel2(){
  $('.btn-back, .btn-save2, .btn-close-attach').hide();
}

function redirect_post(url,id,status){
  $.redirect(host+url,
  {
      ID : id,
      Status: status,
  },
  "POST",);
}

var date_expire = null;
function ck_module_expire(){
  tag_data = $('.ck-module-expire').data();
  if(tag_data.module){
    data_post = {
      module : tag_data.module,
    }
    url = host+"api/ck_module_expire";
    $.ajax({
      url : url,
      type: "POST",    
      data: data_post,
      dataType: "JSON",
      success: function(data){
        if(data.hakakses == "super_admin"){
          console.log(data);
        }
        if(data.status){
          $('#vexpire-module').text(data.module);
          $('#vexpire-date').text(data.date);
          date_expire = data.date;
          setInterval("updateTimer('','"+date_expire+"')", 1000 );
          $('#modal-list-expire').modal('show');
        }
      },
      error: function (jqXHR, textStatus, errorThrown){
        console.log(jqXHR.responseText);

      }
    });
  }
}

function updateTimer(dt_class,dt_date) {
  if(dt_date){
    if(dt_class){
      gt_data_class = $(dt_class).data();
      dt_date = gt_data_class.expire;
    }
    future  = new Date(dt_date+" 23:59:59");
    now     = new Date();
    diff    = future - now;

    days  = Math.floor( diff / (1000*60*60*24) );
    hours = Math.floor( diff / (1000*60*60) );
    mins  = Math.floor( diff / (1000*60) );
    secs  = Math.floor( diff / 1000 );

    d = days;
    h = hours - days  * 24;
    m = mins  - hours * 60;
    s = secs  - mins  * 60;

    if (diff < 0) { 
        clearInterval(future);
        if(!dt_class){
          item = '<div>' + 0 + '<span>days</span></div>' +
          '<div>' + 0 + '<span>hours</span></div>' +
          '<div>' + 0 + '<span>minutes</span></div>' +
          '<div>' + 0 + '<span>seconds</span></div>';
          document.getElementById("timer").innerHTML = item;
        }else{
          item = '<label class="control-label">'+language_app.lb_expire+': '+dt_date+'</label><br>\
          <div>'+0+' '+language_app.lb_days+':</div>\
          <div>'+0+' '+language_app.lb_hours+':</div>\
          <div>'+0+' '+language_app.lb_minutes+':</div>\
          <div>'+0+' '+language_app.lb_seconds+'</div>';
          $(dt_class).html(item);
        }
    }else{      

      if(!dt_class){
        item = '<div>' + d + '<span>days</span></div>' +
          '<div>' + h + '<span>hours</span></div>' +
          '<div>' + m + '<span>minutes</span></div>' +
          '<div>' + s + '<span>seconds</span></div>' ;

        document.getElementById("timer").innerHTML = item;
      }else{
        item = '<label class="control-label">'+language_app.lb_expire+': '+dt_date+'</label><br>\
        <div>'+d+' '+language_app.lb_days+':</div>\
        <div>'+h+' '+language_app.lb_hours+':</div>\
        <div>'+m+' '+language_app.lb_minutes+':</div>\
        <div>'+s+' '+language_app.lb_seconds+'</div>';
        $(dt_class).html(item);
      }
    }
  }
}

// bounce

// End bounce

function readUrl(input) {
  if (input.files && input.files[0]) {
    $.each(input.files, function(k,v){
        var reader = new FileReader();
        reader.readAsDataURL(input.files[k]);
        var url_image = reader.result;
        reader.onload = function (e) {
            data = input.files[k];
            url_image = e.target.result;
            type = "";
            if(data.name){
                d = data.name;
                d = d.split('.');
                type = d[1];
            }

            arrData = {
                filename : data.name,
                url      : url_image,
                type     : type,
                size     : data.size,
                status   : 0,
                page     : '',
                id       : '',

            }

            set_file(arrData, input.files.length, k+1);
        };
    });
  }
}

function reset_file_upload(){
  $('.div-attach').empty();
  $('.form-attach').empty();
  $('.file-result').empty();
  $('.progress-data').hide();
}

function set_file(data,total_data,key){
    item = 
    '<div class="box-file-result" data-id="'+data.id+'" data-page="'+data.page+'" data-size="'+data.size+'" data-file="'+data.url+'" data-type="'+data.type+'" data-filename="'+data.filename+'" data-status="'+data.status+'">\
        <div class="col-xs-2 box-file-result-img" style="padding: 0;\">\
            <a href="javascript:;" onclick="event_click_file(this)">\
                <img src="'+host+icon_file(data.type)+'">\
            </a>\
        </div>\
        <div class="col-xs-10" style="margin-top: 10px">\
            <div>\
                <button class="close btn-close-attach" style="margin-top:-5px" type="button" onclick="remove_file(this)">×</button>\
                <a href="javascript:;" onclick="event_click_file(this)">'+data.filename+'</a>\
                <div>'+bytesToSize(data.size)+'</div>\
            </div>\
        </div>\
    </div>';

    $('.file-result').append(item);
    ID = $('.data-ID').val();
    if(key == "view"){
      $('.btn-close-attach').hide();
    }
    if(ID && total_data == key){
        upload_attachment_file();
    } 
}

function bytesToSize(bytes) {
   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
   if (bytes == 0) return '0 Byte';
   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
};

function remove_file(a){
    ID          = $('.data-ID').val();
    if(ID){
        remove_attachment_file(a);
    }else{
        // swal('',language_app.lb_success, 'success');
        $(a).closest('.box-file-result').remove();
    }
}

function icon_file(type){
  file = 'aset/images/icon_file/file.png';
  if(type == "pdf"){file = 'aset/images/icon_file/pdf.png';}
  else if(type == "png"){file = 'aset/images/icon_file/png.png';}
  else if(type == "jpg" || type == "jpeg"){file = 'aset/images/icon_file/jpg.png';}
  else if(type == "doc" || type == "docx"){file = 'aset/images/icon_file/doc.png'}
  else if(type == "xls" || type == "xlsx"){file = 'aset/images/icon_file/excell.png';}
  else{file = 'aset/images/icon_file/file.png';}
  return file;
}

function link_file(type,data,page){
  url     = '';
  docs    = '';//https://docs.google.com/gview?url=
  typenya = ['png','jpg','jpeg'];
  if(jQuery.inArray(type, typenya) !== -1){
    docs = ''; 
  }

  return docs+data;
}

function event_click_file(a){
  tag_data = $(a).closest('.box-file-result').data();
  if(tag_data){
    frame = link_file(tag_data.type, tag_data.file);
    $.redirect(host+"show-attachment",
    {
      frame : frame,
      filename  : tag_data.filename,
      type      : tag_data.type,
      page      : tag_data.page,
    },
    "POST","_blank",);
  }
}

function ck_count_save_file(){
  d = $('.box-file-result');
  count = 0;
  $.each(d,function(k,v){
    tag_data = $(v).data();
    if(tag_data){
      if(tag_data.status == 0){
        count += 1;
      }
    }
  });

  return count;
}

function upload_attachment_file(id){
  arrFilename = [];
  arrSize     = [];
  arrFile     = [];
  arrKey      = [];

  d = $('.box-file-result');
  $.each(d,function(k,v){
    tag_data = $(v).data();
    if(tag_data){
      if(tag_data.status == 0){
        arrFilename.push(tag_data.filename);
        arrFile.push(tag_data.file);
        arrSize.push(tag_data.size);
        arrKey.push(k);
      }
    }
  });

  data_page   = $(".data-page, .page-data").data();
  modul       = data_page.modul;
  if(!id){
    ID          = $('.data-ID').val();
  }else{
    ID = id;
  }

  data_post   = {
    ID    : ID,
    type  : modul,
    file  : arrFile,
    filename : arrFilename,
    size  : arrSize,
    key   : arrKey,
  }

  url = host+"save-attachment";
  console.log(data_post);
  $.ajax({
    url : url,
    type: "POST",    
    data: data_post,
    dataType: "JSON",
    success: function(data){
      if(data.hakakses == "super_admin"){
        console.log(data);
      }
      if(data.status){
        for(i = 0;i<data.key.length; i++){
          $('.box-file-result').eq(data.key[i]).data("status", 1);
          $('.box-file-result').eq(data.key[i]).data("id", data.ID[i]);
        }
      }
    },
    error: function (jqXHR, textStatus, errorThrown){
      console.log(jqXHR.responseText);

    }
  });
}

function show_attachment_file(id){
  data_page   = $(".data-page, .page-data").data();
  modul       = data_page.modul;
  data_post   = {
    modul   : modul,
    ID      : id,
  }
  url = host + 'attachment-file';
  $.ajax({
      url : url,
      type: "POST",
      data : data_post,
      dataType: "JSON",
      success: function(data){
        console.log(data);
        if(data.status){
          $.each(data.attach,function(i,v){
            set_file(v,1,"view");
          });
        }
      },
      error: function (jqXHR, textStatus, errorThrown){
        console.log(jqXHR.responseText);
      }
    });
}

function remove_attachment_file(d){
  if(d){
    tag_data = $(d).closest('.box-file-result').data();
    url = host+"attachment/delete/"+tag_data.id;
    $.ajax({
      url : url,
      type: "POST",
      dataType: "JSON",
      success: function(data){
        if(data.status){
          swal('','Successfully deleted', 'success');
          $(d).closest('.box-file-result').remove();
        }
      },
      error: function (jqXHR, textStatus, errorThrown){
        console.log(jqXHR.responseText);
      }
    });
  }
}

function disabled_file(){
  $('#inputFile').attr('disabled', false);
}

function show_upload_file(){
  $('.inputDnD').show();
}

function hide_upload_file(){
  $('.inputDnD').hide();
}

function progres_bar(){
  $('.progress-data').show();
  $(".progress-bar").animate({
    width: "80%"
  }, 1500);
}

function success_progres_bar(){
  $(".progress-bar").animate({
    width: "100%"
  });
  $('.progress-data').hide();
}

function create_form_attach(){
  item = '<div class="form-group col-sm-12">\
                <label class="control-label">'+language_app.lb_attachment+'</label>\
                <div class="file-result"></div>\
                <div class="form-group inputDnD">\
                  <label class="sr-only" for="inputFile">'+language_app.lb_file_upload+'</label>\
                  <input type="file" multiple="multiple" class="form-control-file text-success font-weight-bold" id="inputFile" onchange="readUrl(this)" data-title="'+language_app.lb_file_choose+'">\
                </div>\
                <div class="progress-data"></div>\
                <span class="help-block"></span>\
              </div>';
  $('.form-attach').append(item);
}

function create_form_attach2(){
  item = '<div class="form-group">\
                <label class="control-label">'+language_app.lb_attachment+'</label>\
                <div class="file-result col-sm-12"></div>\
                <div class="form-group inputDnD col-sm-12">\
                  <label class="sr-only" for="inputFile">'+language_app.lb_file_upload+'</label>\
                  <input type="file" multiple="multiple" class="form-control-file text-success font-weight-bold" id="inputFile" onchange="readUrl(this)" data-title="'+language_app.lb_file_choose+'">\
                </div>\
                <div class="progress-data"></div>\
                <span class="help-block"></span>\
              </div>';
  $('.div-attach').append(item);
}

function create_form_remark(){
  remark = $('.div-remark .d-remark').text();
  ID      = $('.d-ID').text();
  $('.data-ID').val(ID);
  $('.div-remark').empty();
  item = '<div class="form-group">\
              <label class="control-label">'+language_app.lb_remarks+'</label>\
              <div class="col-sm-12"><textarea name="div_remark" id="div_remark" type="text" placeholder="'+language_app.lb_remark_input+'" class="form-control" style="height: 100px" maxlength="225">'+remark+'</textarea></div>\
              <span class="help-block"></span>\
            </div>';
  $('.div-remark').append(item);
}

function proses_save_button(page,classnya){
  if(page){
    $('.'+classnya).text(language_app.lb_loading+'...'); //change button text
    $('.'+classnya).attr('disabled',true); //set button disable
    $('button, a').attr('disabled', true);
    $('a').addClass('cursor_disabled');
  }else{
    if(!classnya){
      classnya = '';
    }
    $(classnya+' #btnSave, '+classnya+' .save').text(language_app.lb_saving+'...'); //change button text
    $(classnya+' #btnSave, '+classnya+' .save').attr('disabled',true); //set button disable
    if(classnya){
      $(classnya+' button, '+classnya+' a').attr('disabled', true);
      $(classnya+' a').addClass('cursor_disabled');
    }else{
      $('button, a').attr('disabled', true);
      $('a').addClass('cursor_disabled');
    }
  }
  
}

function success_save_button(page,classnya){
  if(page){
    title = '';
    if(page == "next"){
      title = language_app.lb_next;
    }else if(page == "import"){
      title = 'Import';
    }
    $('.'+classnya).text(title); //change button text
    $('.'+classnya).attr('disabled',false); //set button disable
  }else{
    $('#btnSave, .save').text(language_app.btn_save); //change button text
    $('#btnSave, .save').attr('disabled',false); //set button enable
  }
  $('button, a').attr('disabled', false);
  $('a').removeClass('cursor_disabled');
}

function loading_page(){
  $('.div-loader').show();
}
function dismiss_loading_page(){
  $('.div-loader').hide();
}

//20190801 MW
//form
//untuk mengambil nama form yg menjadi array string
//fungsi ini belum digunakan. kali aja nanti butuh
function form_string(){
  var form          = $('#form').serializeArray();
  var serializedArr = JSON.stringify(form);

  data_post = {
    form : serializedArr,
  }
  $.ajax({
      url : url,
      type: "POST",    
      data: data_post,
      dataType: "JSON",
      success: function(data){
          
      },
      error: function (jqXHR, textStatus, errorThrown){
          console.log(jqXHR.responseText);
      }
  });
}

// 20190809 MW
// form to serial by class
function form_to_serial_by_class(dt_class){
  class_length = $('.'+dt_class).length;
  arrDt = [];
  for (var i = 0; i < class_length; i++) {
    val = $('.'+dt_class).eq(i).val();
    arrDt.push(val);
  }

  arrDt = JSON.stringify(arrDt);

  return arrDt;
}


// 20190808 MW
// add serial number 
function add_serial_number(element){
    data_page       = $(".data-page, .page-data").data();
    modul           = data_page.modul;
    xauto_complete  = "active";
    if(modul == "penerimaan"){
      xauto_complete = '';
    }

    tag_data    = $(element).data();
    row         = tag_data.rowid;
    tg_data_row = $('.'+row).data();

    name     = $("."+row+" .p_name").val();
    productid= $("."+row+" .p_id").val();
    if(modul == "stock_opname"){
      qty      = $("."+row+' [name="product_stock[]"]').val();
    }else{
      qty      = $("."+row+' [name="product_qty[]"]').val();
    }
    qty      = removeduit(qty);
    detailsn = tg_data_row.detailsn;

    headerID = '';
    detailID = '';
    if(modul == "retur" || modul == "delivery" || modul == "return_sales"){
      headerID = $('.'+row+' .headerID').val();
      detailID = $('.'+row+' .detailID').val();
    }

    xform    = '#form-serial';

    $('#modal-add-serial .has-error').removeClass('has-error'); // clear error class
    $('#modal-add-serial .help-block').empty(); // clear error string
    $(xform+' input').attr('readonly', true);

    $(xform+' #header_code').val(''+row);
    $(xform+' [name=productid]').val(productid);
    $(xform+' [name=detail_code]').val(headerID); // digukanan untuk menampung value header transaksi
    $(xform+' [name=receipt_det]').val(detailID); // digukanan untuk menampung value detail transaksi
    $(xform+' #product_name').val(name);
    $(xform+' #serial_qty').val(qty);
    $(xform+' #product_type').val('serial');
    $('.vserial').hide();
    $('.v-serial-'+row).show();

    $('.btn-add-serial').show();
    $('#modal-add-serial .modal-title').text('Add Serial Number '+name);
    $('#modal-add-serial').modal('show');
    $('.div-loader').show();

    rowdata_length = $('.rowdata').length;
    arrClass        = [];
    arrProductID    = [];
    for (var i = 0; i < rowdata_length; i++) {
        tg_data     = $('.rowdata').eq(i).data();
        code        = tg_data.classnya;
        type        = $('.rowdata .p_type').eq(i).val();
        type_auto   = $('.rowdata .p_serial_auto').eq(i).val();
        xproductid  = $('.rowdata .p_id').eq(i).val();
        if(type == 2 && type_auto != 1){
          arrClass.push(code);
          arrProductID.push(xproductid);
        }else if(type == 2 && type_auto == 1 && detailsn == "active"){
          arrClass.push(code);
          arrProductID.push(xproductid);
        }
    }
    
    serializedArr  = JSON.stringify(arrClass);
    arrProductID   = JSON.stringify(arrProductID);
    data_post = {
        ID          : productid,
        header_code : row,
        page        : modul,
        Qty         : qty,
        dt_rowid    : serializedArr,
        arrProductID: arrProductID,
        detailsn    : detailsn,
        headerID    : headerID,
        detailID    : detailID,
    }    
    url = host + "api/temp_serial_number_list";
    $.ajax({
        url : url,
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data)
        {   
            $('.div-loader').hide();
            if(data.status){
                $('.v-serial-'+row).remove();
                data_length = data.list.length;
                no = 0;
                for (var i = 0; i < qty; i++) {
                    no = i +1;
                    sn = '';
                    if(no<=data_length){
                        sn      = data.list[i].SN;
                    }

                    item2 = '';
                    if(xauto_complete == "active"){
                      xauto_complete = ' onkeyup="select_serial_autocomplete(this)" ';
                    }

                    item =
                        '<tr class="vserial v-serial-'+row+'" data-rowid="'+row+'">\
                            <td><div class="info-warning"></div></td>\
                            <td class="no-count" style="padding:0px 10px !important;">'+no+'</td>\
                            <td>\
                                <input class="value-key" type="hidden" value="'+row+'" name="serial_key[]"/>\
                                <input class="value-serial" value="'+sn+'" '+xauto_complete+' placeholder="Input serial number" type="text" name="serial_number[]" value="">\
                            </td>\
                        </tr>';
                    $('.table-add-serial tbody').append(item);
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            console.log(jqXHR.responseText);
            $('.div-loader').hide();
        }
    });
}

function check_add_serial_number(rowid,qty){
    classnya = $('.v-serial-'+rowid);
    no = 0;
    for (var i = 0; i < classnya.length; i++) {
        no = i+1;
        if(no>qty){
            $('.v-serial-'+rowid).eq(i).remove();
        }
    }
    no = 0;
    for (var i = 0; i < qty; i++) {
        no = i+1;
        div_langth = $('.v-serial-'+rowid).eq(i).length;
        if(div_langth<=0){
            item =
            '<tr class="vserial v-serial-'+rowid+'" data-rowid="'+rowid+'">\
                <td><div class="info-warning"></div></td>\
                <td class="no-count" style="padding:0px 10px !important;">'+no+'</td>\
                <td><input class="value-serial" placeholder="Input serial number" type="text" name="serial_number[]" value=""></td>\
            </tr>';
            $('.table-add-serial tbody').append(item);
        }else{
            $('.v-serial-'+rowid+' .no-count').eq(i).text(no);
        }
    }
}

function save_serial_number(){
    data_page   = $(".data-page, .page-data").data();
    modul       = data_page.modul;

    xform       = '#form-serial';

    productid   = $(xform+' [name=productid]').val();
    headerID    = $(xform+' [name=detail_code]').val(); // digukanan untuk menampung value header transaksi
    detailID    = $(xform+' [name=receipt_det]').val(); // digukanan untuk menampung value detail transaksi
    header_code = $(xform+' #header_code').val();
    tg_data_row = $('.'+header_code).data();
    detailsn    = tg_data_row.detailsn;
    page2       = '';

    branchid       = '';
    product_branch = '';
    if(data_page.product_branch){
      product_branch = data_page.product_branch;
    }
    if(product_branch == "active"){
      branchid = $('#BranchID').val();
    }

    if(modul == "delivery" || modul == "return_sales"){
      page2 = tg_data_row.selling;
    }else if(modul == "mutation"){
      mutation_type = $('[name=mutation_type] option:selected').val();
      if(mutation_type != 0){
        page2 = $('#from_name').val();
      }
    }

    classnya  = $('.v-serial-'+header_code);
    dt_serial = [];
    dt_tempID = [];
    status_serial = true;
    for (var i = 0; i < classnya.length; i++) {
        val     = $('.v-serial-'+header_code+' .value-serial').eq(i).val();
        tempID  = $('.v-serial-'+header_code+' .value-tempID').eq(i).val();
        item2 = '';
        if(val == ''){
            status_serial = false;
            item2 = '<i class="icon fa-exclamation-triangle" title="Serial number cannot null" style="cursor:pointer;padding:5px;"></i>';
        }else{
            if(jQuery.inArray(val, dt_serial) !== -1){
                status_serial = false;
                item2 = '<i class="icon fa-exclamation-triangle" title="Serial number cannot duplicate" style="cursor:pointer;padding:5px;"></i>';
            }else{
                dt_serial.push(val);
                dt_tempID.push(tempID);
            }
        }

        $('.v-serial-'+header_code+' .info-warning').eq(i).html(item2);
    }

    if(!status_serial){
        swal('','Please check again serial number','warning');
        return;
    }
    serializedArr   = JSON.stringify(dt_serial);
    data_post   = {
        ID          : productid,
        header_code : header_code,
        page        : modul,
        serial      : serializedArr,
        headerID    : headerID,
        detailID    : detailID,
        detailsn    : detailsn,
        page2       : page2,
        BranchID    : branchid,
    }
    console.log(data_post);
    proses_save_button();
    url = host+"api/save_serial_temp";
    $.ajax({
        url : url,
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data)
        {
            if(data.status){
                swal('',data.message,'success');
                $('#modal-add-serial').modal('hide');
            }else{
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    list    = data.list[i];
                    if(list == 'list'){
                        item = '<i class="icon fa-exclamation-triangle" title="'+data.error_string[i]+'" style="cursor:pointer;padding:5px;"></i>';
                        $('.v-serial-'+data.inputerror[i]+' .info-warning').eq(data.index[i]).html(item);
                    }else{
                        $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                        $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                    }
                }
                if(data.message){
                    swal('',data.message,'warning');
                }
            }
            success_save_button();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            success_save_button();
            console.log(jqXHR.responseText);
        }
    });
}

function view_serial_number(page,mt,dt){
    url = '';
    if(page == "penerimaan"){
      url = host+"Penerimaan/serial_number_list";
    }else if(page == "selling"){
      url = host+"selling/serial_number_list";
    }else if(page == "retur"){
      url = host+"retur/serial_number_list";
    }else if(page == "delivery"){
      url = host+"delivery/serial_number_list";
    }else if(page == "return_sales"){
      url = host+"return_sales/serial_number_list";
    }else if(page == "stock_correction"){
      url = host+"Koreksi_stok/serial_number_list";
    }else if(page == "stock_opname"){
      url = host+"Stock_opname/serial_number_list"; 
    }else if(page == "mutation"){
      url = host+"Mutasi/serial_number_list";
    }else if(page == "inventory_goodreceipt"){
      url = host+"inventory_goodreceipt/serial_number_list";
    }

    if(url != ''){
        $('.btn-add-serial').hide();
        $('#modal-add-serial').modal('show');
        $('#modal-add-serial .div-loader').show();
        xform    = '#form-serial';

        data_post = {
            mt : mt,
            dt : dt,
        }

        $.ajax({
            url : url,
            type: "POST",
            data: data_post,
            dataType: "JSON",
            success: function(data)
            {   
                name    = '';
                qty     = '';
                type    = '';
                $('.table-add-serial tbody').empty();
                $.each(data.list,function(k,v){
                    no = k + 1;
                    name = v.product_name;
                    qty  = v.Qty;
                    type = v.product_type;

                    item =
                    '<tr>\
                        <td></td>\
                        <td>'+no+'</td>\
                        <td>'+v.SN+'</td>\
                    </tr>';
                    
                    $('.table-add-serial tbody').append(item);
                });
                $('#modal-add-serial .modal-title').text('View Serial Number '+name);
                $(xform+' #product_name').val(name);
                $(xform+' #serial_qty').val(qty);
                $(xform+' #product_type').val(type);
                $(".readonly").attr("readonly",true);
                $('#modal-add-serial .div-loader').hide();
                create_format_currency2();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                $('#modal-add-serial .div-loader').hide();
                alert('Error adding / update data');
                console.log(jqXHR.responseText);
            }
        });
    }
}

function select_serial_autocomplete(element){
  data_page       = $(".data-page, .page-data").data();
  modul           = data_page.modul;

  xform       = '#form-serial';

  productid   = $(xform+' [name=productid]').val();
  headerID    = $(xform+' [name=detail_code]').val(); // digukanan untuk menampung value header transaksi
  detailID    = $(xform+' [name=receipt_det]').val(); // digukanan untuk menampung value detail transaksi
  header_code = $(xform+' #header_code').val();

  tg_data_row = $('.'+header_code).data();
  detailsn    = tg_data_row.detailsn;
  page2       = '';

  branchid       = '';
  product_branch = '';
  if(data_page.product_branch){
    product_branch = data_page.product_branch;
  }
  if(product_branch == "active"){
    branchid = $('#BranchID').val();
  }

  if(modul == "delivery" || modul == "return_sales"){
    page2 = tg_data_row.selling;
  }else if(modul == "mutation"){
    mutation_type = $('[name=mutation_type] option:selected').val();
    if(mutation_type != 0){
      page2 = $('#from_name').val();
    }else{
      WarehouseID = $('#WarehouseID').val();
    }
  }

  data_post   = {
    ID          : productid,
    header_code : header_code,
    page        : modul,
    headerID    : headerID,
    detailID    : detailID,
    detailsn    : detailsn,
    page2       : page2,
    serial      : $(element).val(),
    BranchID    : branchid,
  }

  console.log(data_post);
  $(element).autocomplete({
      minLength:2,
      max:10,
      scroll:true,
      source: function(request, response) {
          $.ajax({ 
            url: host + "api/select_serial_autocomplete",
            data: data_post,
            dataType: "JSON",
            type: "POST",
            success: function(data){
              console.log(data);
              if(data.status){
                response(data.list);
              }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
              console.log(jqXHR.responseText);
            }    
          });
      },
      select:function(event, ui){
          // productid = ui.item.productid;  
      }
    });

}

// generate lanuage
function generate_language(){
  proses_save_button();

  url = host+"api/create_language"
  $.ajax({
    url : url,
    type: "POST",
    dataType: "JSON",
    success: function(data)
    { 
      if(data.status){
        swal('',data.message,'success');
      }else{
        swal('',data.message,'warning');
      }
      success_save_button();
    },
    error: function (jqXHR, textStatus, errorThrown)
    {
      success_save_button();
      alert('Error adding / update data');
      console.log(jqXHR.responseText);
    }
  });
}

function generate_site(){
  proses_save_button();

  url = host+"api/create_site"
  $.ajax({
    url : url,
    type: "POST",
    dataType: "JSON",
    success: function(data)
    { 
      if(data.status){
        swal('',data.message,'success');
      }else{
        swal('',data.message,'warning');
      }
      success_save_button();
    },
    error: function (jqXHR, textStatus, errorThrown)
    {
      success_save_button();
      alert('Error adding / update data');
      console.log(jqXHR.responseText);
    }
  });
}

function default_setting_parameter(){
  tg_body         = $('body').data();
  st_period_type  = '.v1';
  st_start_date   = tg_body.start_date;
  st_end_date     = tg_body.end_date;
  if(tg_body.datasetting == "Days"){
      st_period_type = '.v1';
  }else if(tg_body.datasetting == "Month"){
      st_period_type = '.v2';
  }else if(tg_body.datasetting == "Year"){
      st_period_type = '.v3';
  }

  if(tg_body.amountdecimal){
    amountdecimal = parseFloat(tg_body.amountdecimal);
  }
  if(tg_body.qtydecimal){
    qtydecimal = parseFloat(tg_body.qtydecimal);
  }

  if(tg_body.branch_id){
    branchid_default = tg_body.branch_id;
  }
  if(tg_body.branch_name){
    branch_name_default = tg_body.branch_name;
  }
  if(tg_body.currency){
    txt_currency = tg_body.currency;
  }

  $('[name=fStartDate]').val(st_start_date);
  $('[name=fEndDate]').val(st_end_date);

}

function select_unit_autocomplete(element){
  val = $('.autocomplete-unit').val();
  
  data_post   = {
    unit : val,
  }

  $(element).autocomplete({
    minLength:2,
    max:10,
    scroll:true,
    source: function(request, response) {
        $.ajax({ 
          url: host + "api/select_unit_autocomplete",
          data: data_post,
          dataType: "JSON",
          type: "POST",
          success: function(data){
            console.log(data);
            if(data.status){
              response(data.list);
            }
          }    
        });
    },
    select:function(event, ui){
        // productid = ui.item.productid;  
    }
  });
}

function check_dropdown(classnya){
  ara_length = $('.check-dropdown').length;
  for(i = 0; i<ara_length; i++){
    element = $('.check-dropdown').eq(i);
    scrollTop     = $(window).scrollTop();
    topDiv        = element.offset().top;
    bottomWindow  = $(window).height();
    dropdownHight = $(".check-dropdown .dropdown-menu").eq(i).height();
    distanceTop   = bottomWindow - topDiv;
    distanceRight = -70;
    if(dropdownHight>distanceTop){
      $(element).addClass('dropup');
    }else{
      $(element).removeClass('dropup');
    }

    $('.check-dropdown .dropdown-menu').css('left', distanceRight);
  }
}

function set_default_branch(){
  $('#BranchName').val(branch_name_default);
  $('#BranchID').val(branchid_default+"-"+branch_name_default);
}

document.onkeyup = PresTab;
 
function PresTab(e)
{
     var keycode = (window.event) ? event.keyCode : e.keyCode;
     if (keycode == 9){
      var focused = $(':focus');
      length_end =  focused[0].selectionEnd;
      focused[0].setSelectionRange(length_end, length_end);
      // focused[0].setSelectionRange(0,0);
     }
}
function img_preview(page = "", img = "") {
  if (page == "reset") {
    $(".img-preview").attr('src', host + 'aset/img/noicon.png');
  } else if (page == "set") {
    $(".img-preview").attr('src', img);
  } else {
    $(".img-preview").attr('src', host + 'aset/img/noicon.png');
    var brand = document.getElementById('logo-id');
    brand.className = 'attachment_upload';
    brand.onchange = function () {
      document.getElementById('fakeUploadLogo').value = this.value.substring(12);
    };

    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('.img-preview').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
    $("#logo-id").change(function () {
      readURL(this);
    });
  }
}
function remove_overlay() {
  $("body").removeClass("stop-scrolling");
}