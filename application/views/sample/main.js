var mobile  = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host    = window.location.origin+'/';
var current_url = window.location.href;
var page_login = host + "login";
var page_register = host + "register";
var page_forgot = host + "forgot-password";
var page_resetpass = host + "reset-password";
var page_verification = host + "verification-account";
var hakakses;
$(document).ready(function() { 
    $("[type=text]").attr("maxlength",50);
    $("[type=number]").attr("maxlength",20);
    $(".angka").attr("maxlength",25);
    $("[name=category_code]").attr("maxlength",3);
    $("[name=phone]").attr("maxlength",20);
    page_data = $(".page-data").data();
    // $('[data-toggle="tooltip"]').tooltip(); 
    if(page_data){
		    hakakses  = page_data.hakakses;
    }
    uri_hash = window.location.hash;
    if(current_url == page_login){
      post("login");
    } else if(current_url == page_register) {
      var nama_toko = "";
      var nama = "";
      var email = "";
      var password = "";
      post("register");
      $("#btn-login").attr("disabled",true);
      $("[name=nama_toko],[name=nama_perusahaan],[name=email],[name=password]").keyup(function(){
        if(this.name == "nama_perusahaan"){
          nama = $(this).val();
        } else if(this.name == "email"){
          email = $(this).val();
        } else if(this.name == "password"){
          password = $(this).val();
        } else if(this.name == "no_hp"){
          no_hp = $(this).val();
        }
        if(nama != "" && email != "" && password != "" && no_hp != ""){
          $("#btn-login").attr("disabled",false);
        } else {
          $("#btn-login").attr("disabled",true);
        }
      });
    } else if(current_url == page_forgot) {
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
    } else if(current_url == page_resetpass || uri_hash == "#reset") {
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
    } else if(current_url == page_verification){
      post("verification_account");
    } else if(current_url == host+"company-information" || current_url == host+"main/company_information"){
      company_information("company");
    } else if(current_url == host+"user-account" || current_url == host+"main/user_account"){
      company_information("user_account");
    } else if(current_url == host+"page-setting-parameter" || current_url == host+"main/setting_parameter"){
      company_information("setting_parameter");
    }
    if(current_url == host+"product"){
      category_list_option();
      unit_list_option();
    }
});
$(document).ready(function(){
 
  angka();
  if($("div, input").hasClass("date")){
    date();
    $("form .date").attr("readonly","");
  }


  if($("select").hasClass("slimselect")){
    slimselect();
  }
  if($("select").hasClass("select2")) {
    $(".select2").select2();
  }
  if($("div,input").hasClass("attachment_upload")){
    img_preview();                 
  }  
  $('.nav-menu a').each(function(){
      var $this = $(this);
      if($this.attr('href') == current_url){
          $this.addClass('active');
      }
  });
});
function angka()
{
  $(".angka").keyup(function(){
    angka = this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
  });
  $('#angka1').maskMoney();
  $('#angka2').maskMoney({prefix:'US$'});
  $('.rupiah').maskMoney({prefix:'Rp. ', thousands:'.', decimal:',', precision:0});
  $('.duit').maskMoney({thousands:',', decimal:'.', precision:2});
  $('.perKM').maskMoney({prefix:'Rp. ', thousands:'.', decimal:',', precision:0, suffix:' Per KM', });
  $('.perMnt').maskMoney({prefix:'Rp. ', thousands:'.', decimal:',', precision:0, suffix:' Per Menit', });
  $('.uang').maskMoney();
  $('.km').maskMoney({ thousands:'.', decimal:',', precision:0, suffix:' KM', });
}


function post(page)
{
  url = "";
  $("#form").submit(function( event ) {
      $("#btn-login").button("loading");
      event.preventDefault();
      $('.form-group').removeClass('has-error'); // clear error class
      $('.help-block').empty();
      console.log(url);
      console.log(page);

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
          $("#btn-login").button("reset");
          console.log(data);
          if(data.status){
            if(page == "login" || page == "register" || page == "verification_account"){
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
            } else if(page == "register" || page == "reset_password" || page == "forgot_password"){
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
            } else {
              swal('Informasi',data.message,'success');
              $('#form')[0].reset();
            }
            if(page == "register" || page == "forgot_password" || page == "reset_password" || page == "verification_account"){
              $("#btn-login").attr("disabled",true);
            }
          }
          else{
            if(data.popup){
              swal('Informasi',data.message,'error');
            } else {
              if(data.inputerror.length > 0){

                for (var i = 0; i < data.inputerror.length; i++){
                    if(data.inputerror[i] == "Phone"){
                      $(".Alert"+data.inputerror[i]).parent().addClass('has-error'); 
                      $(".Alert"+data.inputerror[i]).text(data.error_string[i]);
                    } else {
                      $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                      $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                    }
                }
              } else {
                console.log(data);
              }
            }
          }
          
          // $('#btnSave').text('save'); //change button text
          // $('#btnSave').attr('disabled',false); //set button enable
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          $("#btn-login").button("reset");
          console.log(jqXHR.responseText);

          // $("#pesan-error").show();
          // $("#text-pesan-error").text(data.pesan); 
        }
      });
  });
}
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
function company_information(page = "")
{ 
  $.ajax({
      url : host+"api/company",
      type: "POST",
      dataType: "JSON",
      success: function(data){
        if(hakakses == "super_admin"){
        }
          console.log(data);
        v = data.data;
        if(page == "company"){
          if(data.hak_akses == "sales"){
            $("input,textarea").attr("disabled",true);
          } 
          $('.dropify').dropify();

          $('[name="Name"]').val(v.nama);
          $('[name="nama"]').val(v.nama);
          $('[name="address"]').val(v.address);
          $('[name="city"]').val(v.city);
          $('[name="province"]').val(v.province);
          $('[name="country"]').val(v.country);
          $('[name="postal"]').val(v.postal);
          $('[name="phone"]').val(v.Phone);
          $('[name="PhoneCode"]').val(v.PhoneCode);
          $('select[name=PhoneCode]').change();
          $('[name="fax"]').val(v.fax);
          $(".dropify-preview").show();
  
          img = 'noimage.png';
          if(v.img_bin){
            img = "logo/"+v.img_bin;
          }
          img = host + "img/"+img;
          img = '<img src="'+img+'" />';
          $(".dropify-render").append(img);

        } else if(page == "user_account"){
          $('[name="Name"]').val(v.Name);
          $('[name="first_name"]').val(v.first_name);
          $('[name="last_name"]').val(v.last_name);
          $('[name="email"]').val(v.email);
          $('[name="phone"]').val(v.Phone);
          $('[name="PhoneCode"]').val(v.PhoneCode);
          $('select[name=PhoneCode]').change(); 
          $('[name="password"]').val("*****");          
        } else if(page == "setting_parameter"){
          if(v.SettingParameterID){
            $("[name=settingparameterid]").val(v.SettingParameterID);
            $('[name=currency]').val(v.Currency);
            $('[name=amountdecimal]').val(v.AmountDecimal);
            $('[name=qtydecimal]').val(v.QtyDecimal);
            $('[name=negativestock]').val(v.NegativeStock);            
          }
        }

      },
      error: function (jqXHR, textStatus, errorThrown){
        console.log("error get company dta");
      }
  });
}
function company_save(page)
{
    btn_title = "";
    if(page == "company"){
      btn_title = "Save Company Infomation";
    } else if(page == "user_account") {
      btn_title = "Save User Account";
    }
    else if(page == "setting_parameter"){
      btn_title = "Save Setting";

    }
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable
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
              swal('Informasi',data.message,'success');
              $('.form-group').removeClass('has-error'); // clear error class
              $('.help-block').empty(); // clear error string
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
            $('#btnSave').text(btn_title); //change button text
            $('#btnSave').attr('disabled',false); //set button enable
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error update data');
            $('#btnSave').text(btn_title); //change button text
            $('#btnSave').attr('disabled',false); //set button enable
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
    console.log(page);
    console.log(id);
    save_method = "add_serial";
    $(".disabled").attr("disabled",true);
    $('.table-add-serial tbody').children( 'tr' ).remove();
    if(page == "penerimaan"){
      url = host + "penerimaan/ajax_edit_serial/" + id;      
    } else if(page == "mutasi"){
      url = host + "mutasi/ajax_edit_serial/" + id;
    } else if(page == "retur"){
      url = host + "retur/ajax_edit_serial/" + id;
    }
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
                if(data.product_type == "general"){
                    add_row_serial(page,"");
                } else {
                    for (var i = 1; i <= data.serial_qty; i++){
                        add_row_serial(page,"");
                    }
                }
            }              
            $('#form-serial [name=receipt_det]').val(data.receipt_det);
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
        }
    });
}
function add_row_serial(page ="",productserialid="",serial_number = "",branchid)
{

    noserial +=1;
    input_serialid = "";
    input_branchid = "";
    if(productserialid){
        input_serialid = '<input type="hidden" name="productserialid[]" value="'+productserialid+'">';
    }
    if(branchid){
        input_branchid = '<input type="hidden" name="branchid[]" value="'+branchid+'">';
    }
    item = '<tr>\
        <td style="padding:0px 10px !important;">'+noserial+'</td>\
        <td>'+input_serialid+input_branchid+'<input class="autocomplete_serialnumber" type="text" name="serial_number[]" value="'+serial_number+'" onkeyup="autocomplete_serialnumber(this)"></td>\
      </tr>';
    $(".table-add-serial tbody").append(item);

}
function save_serial()
{
    page = $("#form-serial [name=page]").val();
    $("#form input").attr("disabled",false);
    $('#btnSave, .save').text('saving...'); //change button text
    $('#btnSave, .save').attr('disabled',true); //set button disable
    url = host;
    form = "#form";
    if(save_method == "add_serial"){
      if(page == "penerimaan"){
        url = host + "penerimaan/simpan_serial";;
      } else if(page == "mutasi"){
        url = host + "mutasi/simpan_serial";;
      } else if(page == "retur"){
        url = host + "retur/simpan_serial";;
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
            console.log(data);
            if(data.status){
              $(".disabled").attr("disabled",true);
              if(data.page){
                  $('#modal-add-serial').modal("hide");
              }
            } else {
              alert(data.message);
            }
            $('#btnSave, .save').attr('disabled',false); //set button enable
            $('#btnSave, .save').text('save'); //change button text
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave, .save').text('save'); //change button text
            $('#btnSave, .save').attr('disabled',false); //set button enable
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
        data_param  = 'data-page="'+page+'" data-sellno="'+sellno+'" data-vendorid="'+vendorid+'" data-vendorname="'+vendorname+'"';
        item = '<tr>\
                  <td>\
                    <a href="javascript:void(0)"  onclick="chose_sellno(this)" '+data_param+'>'+sellno+'</a>\
                  </td>\
                  <td>\
                    <a href="javascript:void(0)"  onclick="chose_sellno(this)" '+data_param+'>'+vendorname+'</a>\
                  </td>\
                </tr>';
        tbl.row.add( $(item)[0] ).draw();
      });
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


function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}


function slimselect(page = "")
{

    slimselect = new SlimSelect({
        select: '.slimselect',
        closeOnSelect: false
    });
    $('.ss-disabled').text(lang('lb_chosee_employee'))
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
  console.log(data_post);
  $.ajax({
    url : url,
    type: "POST",    
    data: data_post,
    dataType: "JSON",
    success: function(data){
      console.log(data);
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
      console.log(data);
    },
    error: function (jqXHR, textStatus, errorThrown){
      $(element).button("reset");
      console.log(jqXHR.responseText);

    }
  });
}
function img_preview(page = "", img = "") {
  if(page == "reset"){
    $(".img-preview").attr('src',host + 'aset/img/noicon.png');
  } else if(page == "set"){
    $(".img-preview").attr('src',img);
  } else {  
    $(".img-preview").attr('src',host + 'aset/img/noicon.png');
    var brand       = document.getElementById('logo-id');
    brand.className = 'attachment_upload';
    brand.onchange  = function() {
        document.getElementById('fakeUploadLogo').value = this.value.substring(12);
    };
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('.img-preview').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#logo-id").change(function() {
        readURL(this);
    });
  }
}