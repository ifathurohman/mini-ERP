var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host = window.location.origin+'/';
var url_current = window.location.href;
var page_login = host + "main/login";
var page_register = host + "main/register";
var save_method; //for save method string
var table;
var url_list = host + "voucher/ajax_list/";
var url_edit = host + "voucher/ajax_edit/";
var url_hapus = host + "voucher/ajax_delete/";
var url_simpan = host + "voucher/simpan";
var url_update = host + "voucher/ajax_update";
var page_name;
var url_modul;
var modul;
var search;
var currentdate;
$(document).ready(function() {
    data_page   = $(".data-page, .page-data").data();
    page_name   = data_page.page_name;
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    search      = data_page.search;
    currentdate = data_page.currentdate;
    if(modul == "voucher"){
        window.history.pushState("object or string", "Title", host + "buy-voucher");
    }
    $("[name=Type], [name=Qty], [name=App], [name=Module]").change(function(){
        Type        = $("[name=Type]").val();
        App         = $("[name=App]").val();
        Qty         = $("[name=Qty]").val();
        QtyModule   = $("[name=Module]").val();
        if(Type != "none" && Qty != "none" || Type != "none" && QtyModule != "none"){
            get_voucher_price();
        } else {
            $('[name=PriceDevice]').val(0.00);
            $('[name=PriceModule]').val(0.00);
            $("[name=Price]").val(0.00);
            $('.vpriceModule, .vpriceDevice').hide(300);
        }
    });
    $("[name=App]").click(function(){
        Type    = $("[name=Type]").val();
        App     = $("[name=App]").val();
        Qty     = $("[name=Qty]").val();
        if(Type != "none" && Qty != "none"){
            get_voucher_price();
        } else {
            $("[name=Price]").val(0.00);
        }
    });
    if(search){
        filter_table(search);
        $("#table_filter input").val(search);
    } else {
        filter_table();
    }

    $('.vpriceModule').hide();
    $('.vpriceDevice').hide();
});
function filter_table(filter) {

    StartDate   = $("#form-filter [name=fStartDate]").val();
    EndDate     = $("#form-filter [name=fEndDate]").val();
    App         = $("#form-filter [name=fApp]").val();
    Package     = $("#form-filter [name=fPackage]").val();
    Status      = $("#form-filter [name=fStatus]").val();
    Search      = $("#form-filter [name=fSearch]").val();
    data_filter = {
        Filter : "Filter",
        StartDate : StartDate,
        EndDate : EndDate,
        App : App,
        Package : Package,
        Status : Status,
        Search : Search,
    };

    table = $('#table').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "destroy": true,
        "searching": false, //Feature Search false
        "order": [], //Initial no order.
        "ajax": {
            "url": url_list+url_modul+"/"+modul,
            "type": "POST",
            data: data_filter,
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [
        {
            "targets": [ 0 ], //last column
            "orderable": false, //set not orderable
        },
        ],
    });
}
function tambah()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $(".view_info").empty();
    $('.vpriceModule').hide();
    $('.vpriceDevice').hide();
    $("[name=TransferDate]").val(currentdate);
    $(".view-detail-customer").hide();
    $(".modal input, .modal select, .modal textarea").attr("disabled",false);
    $("#form #pipesys").prop("checked",true);
    $(".modal-dialog").removeClass("width-60per");
    $("#table-voucher-module tbody tr, #table-voucher-devices tbody tr").remove();
    $("#view_voucher").hide();
    $(".modal #form, #btnSave").show();
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal('show'); // show bootstrap modal
    $('.modal-title').text('Buy ' + page_name); // Set Title to Bootstrap modal title
    $('[name="crud"]').val("insert");
    $(".form-buy").show();
    $(".form-confirmation").hide();

    // $('#all').attr("disabled", true);
    // $('#pipesys').attr("disabled", true);
}
function edit(id,page="")
{   
    $(".view_info").empty();
    $('.vpriceModule').hide();
    $('.vpriceDevice').hide();
    $('#form')[0].reset();
    $("[name=TransferDate]").val(currentdate);
    $(".view-detail-customer").hide();
    $(".modal input, .modal select, .modal textarea").attr("disabled",false);
    $(".modal-dialog").removeClass("width-80per");
    $("#table-voucher-module tbody tr, #table-voucher-devices tbody tr").remove();
    if(page == "voucher"){
        if(!mobile){
            $(".modal-dialog").addClass("width-80per");
        }
        $("#panel-voucher-pipesys, #panel-voucher-salespro").hide();
        $("#view_voucher").show();
        $(".modal #form, #btnSave").hide();
        TitleModal  = "Voucher";
        save_method = "voucher";

    } else if(page == "confirmation"){
        $("#view_voucher").hide();
        $(".modal #form, #btnSave").show();
        $(".modal #form").hide();
        $("#btnSave").text(language_app.btn_confirmation);
        $(".modal input, .modal select, .modal textarea").attr("readonly",true);
        $("[name=TransferDate]").removeClass("date");
        TitleModal  = language_app.lb_voucher_confirm1;
        save_method = "confirmation";

    } else if(page == "view"){
        $(".view-detail-customer").show();
        $("#view_voucher").hide();
        $(".modal #form").show();
        $("#btnSave").hide();
        $("#btnSave").text(language_app.btn_confirmation);
        $(".modal input, .modal select, .modal textarea").attr("disabled",true);
        $("[name=TransferDate]").removeClass("date");
        TitleModal  = "View Transfer Detail";
        save_method = "view";
    } else if(page == "info"){
        $("#view_voucher").hide();
        $(".modal #form, #btnSave").hide();
        TitleModal = 'Info';
    }else {
        $("#view_voucher").hide();
        $(".modal #form, #btnSave").show();
        TitleModal  = "Buy Confirmation";
        save_method = 'update';
    }
    $(".form-buy").hide();
    $(".form-confirmation").show();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty(); // clear error string
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(json)
        {
            if(page == "info" || page == "confirmation"){
                url = host + 'voucher/view/'+id;
                $(".view_info").load(url);
            }

            data = json.data;
            if(data.hakakses == "super_admin"){
                console.log(json);
            }
            $('label').addClass('active');
            $('.validate').addClass('valid');
            $('[name="crud"]').val("update");
            $('[name="VoucherID"]').val(data.VoucherID);
            $('[name="Code"]').val(data.Code);
            $('[name="App"]').val(data.App);
            $("#"+data.App).prop("checked",true);
            $('[name="Type"]').val(data.Type);
            $('[name="Status"]').val(data.Status);
            $("[name=Price]").val(parseFloat(data.Price).toFixed( 2 ));
            $('[name="ExpireDate"]').val(data.ExpireDate);
            $('[name="Bank"]').val(data.Bank);
            $('[name="TransferDate"]').val(data.TransferDate);
            $('[name="AccountBank"]').val(data.AccountBank);
            $('[name="AccountName"]').val(data.AccountName);
            $('[name="AccountNumber"]').val(data.AccountNumber);
            $('[name=Remark]').val(data.Remark);

            TransferAmount = data.TransferAmount;
            if(data.TransferAmount){
                $("[name=TransferAmount]").val(parseFloat(TransferAmount).toFixed( 2 ));
            }


            if(data.TransferDate == null && save_method == "update"){
                $("[name=TransferDate]").val(currentdate);
            }
            if(page == "view"){
                $("[name=CustomerName]").val(data.CustomerName);
                $("[name=CustomerEmail]").val(data.CustomerEmail);
                $("[name=CustomerPhone]").val(data.CustomerPhone);
            }

            if(page == "voucher"){

                trnot = "<tr>";
                trnot += '<td colspan="6"><center>Data not found</center></td>';
                trnot += "</tr>";
                if(json.voucher_module.length == 0){
                    $("#table-voucher-module tbody").append(trnot);
                    $("#panel-voucher-module").hide();
                } else {
                    add_item_voucher("#table-voucher-module",json.voucher_module,"module");
                    $("#panel-voucher-module").show();
                }
                if(json.voucher_devices.length == 0){
                    $("#table-voucher-devices tbody").append(trnot);
                    $("#panel-voucher-devices").hide();
                } else {
                    add_item_voucher("#table-voucher-devices",json.voucher_devices);
                    $("#panel-voucher-devices").show();
                }

            }

            $('#modal').modal("show");
            $('.modal-title').text(TitleModal);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}
function add_item_voucher(element,list_data,page)
{
    $.each(list_data,function(i,v){
        if(v.Status == "used"){
            status = '<span class="info-red">not available</span>';
        } else {
            status = '<span class="info-green">available<span>';
        }
        no   = i + 1;
        item = '<tr>';
        item += '<td>'+no+'</td>';
        item += '<td>'+v.Code+'</td>';
        if(page == "module"){
            item += '<td>'+v.Module+'</td>';
        }
        item += '<td>'+v.usedName+'</td>';
        item += '<td>'+v.usedCompany+'</td>';
        item += '<td>'+v.UseDate+'</td>';
        item += '<td>'+v.ExpireDate+'</td>';
        item += '<td>'+status+'</td>';
        item += '</tr>';
        $(element + " tbody").append(item);
    });
}
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}
 
function save()
{
    proses_save_button();
    btnsavetxt = "Save";

    var url;
    if(save_method == 'add') {
        url = url_simpan;
    } else if(save_method == "update"){
        url = url_update;
    } else if(save_method == "confirmation"){
        url = url_update + "/" + modul;
        btnsavetxt = "Confirmation";
    } else if(save_method == "view"){
        url = "";
        alert("sorry");
    }
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {
            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal').modal("hide");
                reload_table();
                if(save_method == "confirmation"){
                    swal('','confirmation transaction voucher success','success');
                }


            }
            else
            {
                if(data.message){
                    swal('',data.message, 'warning');
                }
                $('.form-group').removeClass('has-error'); // clear error class
                $('.help-block').empty();
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                    //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                    // swal('',data.error_string[i],'warning');
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
function hapus(id)
{
    swal({   title: "Are you sure?",   
             // text: "You will not be able to recover this data !",   
             type: "warning",   
             showCancelButton: true,   
             confirmButtonColor: "#DD6B55",   
             confirmButtonText: "Yes, delete it!",   
             cancelButtonText: "No, cancel it!",   
             closeOnConfirm: false,   
             closeOnCancel: false }, 
             function(isConfirm){   
                 if (isConfirm) { 
                    $.ajax({
                        url : url_hapus+id+"/nonactive",
                        type: "POST",
                        dataType: "JSON",
                        success: function(data)
                        {
                            //if success reload ajax table
                            reload_table();
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            swal('Error deleting data');
                        }
                    });
                    swal("Deleted!", "Your data has been deleted.", "success");   } 
                 else {
                     swal("Canceled", "Your data is safe :)", "error");   } 
    });
}
function active(id){
    $.ajax({
        url : url_hapus+id+"/active",
        type: "POST",
        dataType: "JSON",
        success: function(data){
            reload_table();
        },
        error: function (jqXHR, textStatus, errorThrown){
            swal('Error undeleting data');
        }
    });
}
function generate_token(id,status)
{
    $.ajax({
        url : host+"admin/generate_token/" + id+"/"+status,
        type: "GET",
        dataType: "JSON",
        success: function(data){
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            if(data.status){
                reload_table();
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            console.log("error generate token");
        }
    });
}
function unlink(id){
    $.ajax({
        url : host+"admin/unlink/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            if(data.status){
                reload_table();
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            console.log("error generate token");
        }
    });
}
function get_voucher_price()
{   
    Qty         = $("[name=Qty]").val();
    QtyModule   = $("[name=Module]").val();

    data_post = {
        App: $("[name=App]:checked").val(),
        Qty: Qty,
        Type: $("[name=Type]").val(),
        QtyModule : QtyModule,
    };
    $.ajax({
        url : host+"api/get_voucher_price",
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            if(data){
                $("[name=Price]").val(data.price_total_txt);
                if(Qty != "none"){
                    $('[name=PriceDevice]').val(data.device_total_txt);
                    $('.vpriceDevice').show(300);

                }else{
                    $('[name=PriceDevice]').val(0.00);
                    $('.vpriceDevice').hide(300);
                }

                if(QtyModule != "none"){
                    $('[name=PriceModule]').val(data.module_total_txt);
                    $('.vpriceModule').show(300);
                }else{
                    $('[name=PriceModule]').val(0.00);
                    $('.vpriceModule').hide(300);
                }
            } else {
                $("[name=Price]").val(0.00);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            console.log(jqXHR.responseText);
        }
    });
}