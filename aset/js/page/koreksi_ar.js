var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host = window.location.origin+'/';
var url = window.location.href;
var page_login = host + "main/login";
var page_register = host + "main/register";
var save_method; //for save method string
var table;
var url_list = host + "Koreksi_piutang/ajax_list/";
var url_edit = host + "Koreksi_piutang/ajax_edit/";
var url_hapus = host + "Koreksi_piutang/ajax_delete/";
var url_simpan = host + "Koreksi_piutang/simpan";
var url_update = host + "Koreksi_piutang/ajax_update";
var url_simpan_remark = host +"Koreksi_piutang/save_remark";

var id_row = 0;
//dashboard
var url_post        = host + "dashboard/dashboard/";
var period = "";
var chart_sales_open,chart_sales_overdude,chart_sales_payment;
//end dashboard
$(window).load(function(){

    page_data   = $(".page-data").data();
    app         = page_data.app;
    ap          = page_data.ap;

    
});
//end dashboard
$(document).ready(function() {
    date();
    // selling
    selected_item('#ul-sales-open', st_period_type,'non');
    selected_item('#ul-sales-overdude', st_period_type,'non');
    selected_item('#ul-sales-payment', st_period_type,'non');
    create_chart();
    //datatables
    filter_table();
});
function filter_table(page){
    data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    url         = url_list+url_modul+"/"+modul;
    date_now    = data_page.date;
    title_page  = data_page.title;

    fSearch             = $('#form-filter [name=fSearch]').val();
    fStartDate          = $('#form-filter [name=fStartDate]').val();
    fEndDate            = $('#form-filter [name=fEndDate]').val();
    fStatus             = $('#form-filter [name=fStatus]').val();

    data_post = {
        Search              : fSearch,
        StartDate           : fStartDate,
        EndDate             : fEndDate,
        Status              : fStatus,
    }
    table = $('#table').DataTable({
        "destroy"   : true,
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "searching": false, //Feature Search false
        "order": [], //Initial no order.
         "language": {                
            "infoFiltered": ""
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url"   : url,
            "type"  : "POST",
            "data"  : data_post,
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [{
            "targets": [0], //last column
            "orderable": false, //set not orderable
        },],
    });
    load_data('sellheader');
}
function modal_width() {
    if(mobile){
        $("#modal .modal-dialog").css("width","93%");
    } else {
        $("#modal .modal-dialog").css("width","65%");
    }
}
function tambah()
{
    save_method = 'add';
    modal_width();
    $("#form input").attr("disabled",false);
    $("#form .disabled").attr("disabled",true);
    $("#form .readonly").attr("readonly",true);
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal('show'); // show bootstrap modal
    $('.modal-title').text(language_app+' '+title_page); // Set Title to Bootstrap modal title
    $(".save").show();
    $('[name="crud"]').val("insert");
    ckOrder();

    $('.table-arcorrection tbody').empty();
    $('.table-arcorrection2 tbody').empty();
    add_new_row_store();
    add_new_row_selling();
    reset_button_action();
    reset_file_upload();
    show_upload_file();
    hide_button_cancel2();
    create_form_attach();
    $('.btn-close').show();

}
function ambil_arcorrection(page = "",search = "")
{
    $(".table-arcorrection tbody tr").remove();
    $.ajax({
        url : host+"api/ar_correction/ar_correction",
        type: "GET",
        dataType: "JSON",
        success: function(data){
            if(data.hakakses == "super_admin"){
                console.log(data); 
            }
            if(data.list_data.length > 0){
                no = 0;
                $.each(data.list_data,function(i,v){
                    no += 1;
                    item = '<tr>\
                        <td>\
                        <input type="hidden" name="branchid[]" value="'+v.branchid+'">\
                        <t>'+no+'</t></td>\
                        <td><t>'+v.branchname+'</t></td>\
                        <td><t>'+v.lbl_sisatotal+'</t></td>\
                        <td>\
                        <input type="hidden" name="totalcorrection[]" placeholder="" value="'+v.sisatotal+'">\
                        <input type="number" name="total[]" placeholder="" class="bg-abu">\
                        </td>\
                    </tr>';
                    $(".table-arcorrection tbody").append(item);
                });
                angka();
            } else {
                item = '<tr><td style="text-align:center" colspan="4">'+language_app.lb_data_not_found+'</td></tr>';
                $(".table-arcorrection tbody").append(item);
            }
        },
        error: function (jqXHR, textStatus, errorThrown){
            alert('Error get data ar correction');
        }
    });
}
// view
function view(id,page){
    reset_button_action();
    if(page == "print"){
        open_modal_template(id,page);
    }else{
        view_print_data(id,page);
    }
    url = host + "koreksi-piutang-view/"+id+"?page=print";
    action_print_button(url);
}

function view_print_data(id,page){
    if(page == "print"){
        page = "print";
    }else{
        page = "ar_correction";
    }
    $('#modal-print').modal('show');
    $('#modal-print .modal-title').text(title_page+" "+language_app.btn_detail);
    url = host + "koreksi-piutang-view/"+id+"?page="+page;

    var TemplateID       = $('.template_select option:selected').val();
    var default_template = 0;
    if ($('#default_template').is(":checked")){
        default_template = 1;
    }

    data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;

    data_post  = {
        TemplateID       : TemplateID,
        default_template : default_template,
        "cktemplate"     : 1,
        modul            : modul,
        url_modul        : url_modul,
    }

    $("#view-print").load(url,data_post,function(){
        $(".div-loader").hide();
        reset_file_upload();
        create_form_attach2();
        hide_upload_file();
        hide_button_cancel();
        set_button_action(arrData);
        disabled_file();
        show_attachment_file(id);
    });

    $("#link_print").attr("href",url+"&cetak=cetak");
    $("#link_pdf_1").attr("href",url+"&cetak=pdf&position=portrait");
    $("#link_pdf_2").attr("href",url+"&cetak=pdf&position=landscape");
}
// end view
function add_serial() {
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal-add-serial').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Serial Number'); // Set Title to Bootstrap modal title

}
 
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}
 
function save()
{
    proses_save_button();
    $("#form input").attr("disabled",false);
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty();
    $('.info-warning').empty();
    var url;
    url = url_simpan;

    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {
            if(data.status) //if success close modal and reload ajax table
            {
                if(ck_count_save_file()>0){
                    upload_attachment_file(data.ID);
                }
                $('#modal').modal("hide");
                swal('','Success', 'success');
                reload_table();
            }
            else
            {
                console.log(data);
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    list    = data.list[i];
                    tab     = data.tab[i];
                    if(list == 'list'){
                        item = '<i class="icon fa-exclamation-triangle" title="'+data.error_string[i]+'" style="cursor:pointer;padding:5px;"></i>';
                        $(data.inputerror[i]+' .info-warning').append(item);
                    }else{
                        $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                        $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                    }
                }

                if(data.message){
                    swal('',data.message,'warning');
                }else{
                    swal('',language_app.lb_incomplete_form, 'warning');
                }
            }
            success_save_button();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            console.log(jqXHR.responseText);
            success_save_button();
        }
    });
}
function hapus(id)
{
    swal({   title: "Are you sure?",   
             text: "You will not be able to recover this data !",   
             type: "warning",   
             showCancelButton: true,   
             confirmButtonColor: "#DD6B55",   
             confirmButtonText: "Yes, delete it!",   
             cancelButtonText: "No, cancel it!",   
             closeOnConfirm: false,
             showLoaderOnConfirm: true,
             closeOnCancel: false }, 
             function(isConfirm){   
                 if (isConfirm) { 
                    $.ajax({
                        url : url_hapus+id,
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
function date(){
    container = $('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
    $(".date").datepicker({
        format: 'yyyy-mm-dd',
        container: container,
        todayHighlight: true,
        autoclose: true,
    });
}

function add_new_row() {
    item = '<tr>\
                    <td></td>\
                    <td><input type="text" name=""></td>\
                    <td><input type="text" name=""></td>\
                    <td><input type="text" name=""></td>\
                  </tr>';
    $(".table-add-product tbody").append(item);
}

function add_new_row_store(v){
    kolom       = $('.table-arcorrection tbody').find('tr').length + 1;
    id_row      += 1;
    btn_remove  = '';

    if(kolom>100){
        swal('','Data item max 100', 'warning');
        return '';
    }

    if(kolom > 1){
       btn_remove = '<i  onclick="delete_row_selling(this)" class="icon fa-remove remove_row" aria-hidden="true" style="cursor:pointer;padding:5px;"></i>'; 
    }

    Detid         = '';
    BranchName    = '';
    BranchID      = '';
    Total         = '';
    Remark        = '';
    if(v){
        Detid         = v.BalanceDetID;
        BranchID      = v.branchid;
        BranchName    = v.branchname;
        Total         = parseFloat(v.totalcorrection);
        Remark        = v.Remark;
    }

    item = 
        '<tr class="dtt'+id_row+'">\
            <td>\
                <input type="hidden" name="rowid[]" value="dtt'+id_row+'">\
                <div class="info-warning"></div>\
            </td>\
            <td><i class="icon fa-search remove_row" onclick="branch_modal('+"'ar_correction','.dtt"+id_row+"'"+')" style="cursor:pointer;padding:5px;"></i></td>\
            <td>\
                <input type="text" class="readonly pointer" onclick="branch_modal('+"'ar_correction','.dtt"+id_row+"'"+')" name="BranchName[]" value="'+BranchName+'" placeholder="'+language_app.lb_store_select+'">\
                <input type="hidden" name="BranchID[]" value="'+BranchID+'"/>\
                <input type="hidden" name="Detid[]" value="'+Detid+'"/>\
            </td>\
            <td><input type="text" class="duit" name="Total[]" placeholder="'+language_app.lb_input_nominal+'" value="'+Total+'"></td>\
            <td><input type="text" name="Remarks[]" placeholder="'+language_app.lb_remark_input+'" value="'+Remark+'"></td>\
            <td>'+btn_remove+'</td>\
        </tr>';
    $(".table-arcorrection tbody").append(item);
    $('.readonly').attr('readonly', true);
    moneyFormat();
}
function add_new_row_selling(v){
    kolom       = $('.table-arcorrection2 tbody').find('tr').length + 1;
    id_row      += 1;
    btn_remove  = '';

    if(kolom>100){
        swal('','Data item max 100', 'warning');
        return '';
    }

    if(kolom > 1){
       btn_remove = '<i  onclick="delete_row_selling(this)" class="icon fa-remove remove_row" aria-hidden="true" style="cursor:pointer;padding:5px;"></i>'; 
    }

    arDetid         = '';
    arVendorID      = '';
    arVendorName    = '';
    arTotal         = '';
    arRemark        = '';
    if(v){
        arDetid         = v.BalanceDetID;
        arVendorID      = v.vendorid;
        arVendorName    = v.vendorName;
        arTotal         = parseFloat(v.totalcorrection);
        arRemark        = v.Remark;
    }

    item = 
        '<tr class="dt'+id_row+'" data-position="customer">\
            <td>\
                <input type="hidden" name="arrowid[]" value="dt'+id_row+'">\
                <div class="info-warning"></div>\
            </td>\
            <td><i class="icon fa-search remove_row" onclick="vendor_modal('+"'.dt"+id_row+"'"+')" style="cursor:pointer;padding:5px;"></i></td>\
            <td>\
                <input type="text" class="readonly pointer" onclick="vendor_modal('+"'.dt"+id_row+"'"+')" name="arVendorName[]" value="'+arVendorName+'" placeholder="select customer">\
                <input type="hidden" name="arVendorID[]" value="'+arVendorID+'"/>\
                <input type="hidden" name="arDetid[]" value="'+arDetid+'"/>\
            </td>\
            <td><input type="text" class="duit" name="arTotal[]" placeholder="'+language_app.lb_input_nominal+'" value="'+arTotal+'"></td>\
            <td><input type="text" name="arRemark[]" placeholder="'+language_app.lb_remark_input+'" value="'+arRemark+'"></td>\
            <td>'+btn_remove+'</td>\
        </tr>';
    $(".table-arcorrection2 tbody").append(item);
    $('.readonly').attr('readonly', true);
    moneyFormat();
}
function  delete_row_selling(a) {
    $(a).closest('tr').remove();
}

// checkbox Sales Order
$('input[type=radio][name=ckOrder]').change(function() {
    ckOrder();
});
function ckOrder(non){
    val         = $('input[type=radio][name=ckOrder]:checked').val();
    if(val == 1) {
        $('.vstore').show(300);
        $('.vselling').hide(300);
    }else{
        $('.vstore').hide(300);
        $('.vselling').show(300);
    }
}
// end checkbox sales order

function delete_data(id){
    swal({   
        title: language_app.lb_delete_alert,   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: language_app.btn_save,   
        cancelButtonText: language_app.btn_cancel,   
        closeOnConfirm: false,
        showLoaderOnConfirm: true,
        closeOnCancel: false }, 
        function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    url : url_hapus+id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data)
                    {
                        if(data.status){
                            $('#modal-print').modal('hide');
                            swal('',data.message, 'success');
                        }else{
                            swal('',data.message, 'warning');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert('Error save data from ajax');
                        console.log(jqXHR.responseText);
                    }
                });
            } 
            else {
                swal(language_app.lb_canceled, "", "error");   
            } 
    });
}

// edit
function edit(id){
    id_row = 0;
    save_method = "update";
    $(".readonly").attr("readonly",true);
    $("#form input, #form textarea, #form select").attr("disabled",false);
    $('#form')[0].reset(); // reset form on modals
    $('.form-group, .input-group').removeClass('has-error'); // clear error class
    $('.input-error').removeClass('input-error');
    $('.help-block').empty(); // clear error string
    $('#form [name=crud]').val('update');
    // $('#modal').modal({backdrop: 'static', keyboard: true}); // show bootstrap modal
    $("#modal").modal("show");
    $('#modal .modal-title').text(title_page+' '+language_app.lb_edit); // Set Title to Bootstrap modal title
    $('.table-arcorrection tbody').children('tr').remove();
    $('.table-arcorrection2 tbody').children('tr').remove();
    reset_button_action();
    reset_file_upload();
    reset_button_action();
    show_upload_file();
    hide_button_cancel2();
    $('.btn-close').show();
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            list        = data.list;
            detail      = data.detail;

            $('[name=BalanceID]').val(list.BalanceID);
            $('[name=balanceno]').val(list.balanceno);
            $('[name=date]').val(list.date);
            $('[name=Remark]').val(list.Remark);

            $("input[name=ckOrder][value='"+list.OrderType+"']").prop('checked', true);
            ckOrder();
            $("input[name=BalanceType][value='"+list.BalanceType+"']").prop('checked', true);

            if(list.OrderType == 1){
                $.each(detail, function(i,v){
                    add_new_row_store(v);
                });
            }else{
                $.each(detail, function(i,v){
                    add_new_row_selling(v);
                });
            }

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);
        }
    });
}

function edit_attach(id){
    create_form_remark();
    $('#modal-print .modal-title').text(title_page+' '+language_app.lb_edit); // Set Title to Bootstrap modal title
    show_upload_file();
    show_button_cancel();
    $('.btn-back').attr('onclick', 'cancel_attach('+"'"+id+"'"+')');
    $('.btn-save2').attr('onclick', 'save_attach('+"'"+id+"'"+')');
}

function cancel_attach(id){
    view(id)
}

function save_attach(id){
    ID = $('.data-ID').val();
    remark = $('#div_remark').val();
    if(ID){
        $('.btn-save2').button('loading');
        data_post = {
            ID : ID,
            Remark : remark,
        }
        url = url_simpan_remark;
        $.ajax({
            url : url,
            type: "POST",    
            data: data_post,
            dataType: "JSON",
            success: function(data){
                $('.btn-save2').button('reset');
                if(data.hakakses == "super_admin"){
                    console.log(data);
                }
                if(data.status){
                    swal('',data.message,'success');
                    view(id);
                }else{
                    swal('',data.message,'warning');
                }
            },
            error: function (jqXHR, textStatus, errorThrown){
                console.log(jqXHR.responseText);
                $('.btn-save2').button('reset');
            }
        });
    }
}

//dashboard
function create_chart(){
    var ctx = document.getElementById("sales_open");
    chart_sales_open = new Chart(ctx,{});

    var ctx = document.getElementById("sales_overdude");
    chart_sales_overdude = new Chart(ctx,{});

    var ctx = document.getElementById("sales_payment");
    chart_sales_payment = new Chart(ctx,{});

}
function load_data(page = "")
{
    url_post    =  host+"dashboard/dashboard";
    Check       = $("[name=Check]:checked").val();
    StartDate   = $("[name=fStartDate]").val();
    EndDate     = $("[name=fEndDate]").val();

    // untuk data diagram
    sales_overdudex  = $('#ul-sales-overdude .li-active').data();
    sales_openx      = $('#ul-sales-open .li-active').data();
    sales_paymentx   = $('#ul-sales-payment .li-active').data();

    data_post   = {
        Check           : Check,
        StartDate       : StartDate,
        EndDate         : EndDate,
        sales_open      : sales_openx.type,
        sales_overdude  : sales_overdudex.type,
        sales_payment   : sales_paymentx.type,
        page            : page,
    };
    // if(StartDate > EndDate){
    //     alert("date from must less than date to");
    //     return;
    // }
    $.ajax({
        url : url_post,
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            
            $('.is-loading').removeClass("is-loading");
            
            if(page == "sales_open"){
                sales_open(data.sales_open);
            }else if(page == "sales_overdude"){
                sales_overdude(data.sales_overdude);
            }else if(page == "sales_payment"){
                sales_payment(data.sales_payment);
            }else{
                set_data_selling(data);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);
            $('.is-loading').removeClass("is-loading");
        }
    });
}
function set_data_selling(data){
    $(".total_purchase_amount").text(data.total_purchase_amount);
    $(".total_sell_amount").text(data.total_sell_amount);
    $(".total_sell_qty").text(data.total_sell_qty);
    $(".total_sell").text(data.total_sell);
    $(".total_customer").text(data.total_customer);
    $(".total_product").text(data.total_product);
        
    sales_open(data.sales_open);
    sales_overdude(data.sales_overdude);
    sales_payment(data.sales_payment);

}

function sales_open(data){
    var ctx = document.getElementById("sales_open");
    chart_sales_open.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    total               = data.total;
    if(data.ID.length>0){
        no   = 0;
        name = '';
        date1 = '';
        $.each(data.ID, function(k,v){
            value = [];
            $.each(data.data, function(kk,vv){
                if($.inArray(vv.date, labels) == -1){
                    labels.push(vv.date);
                }
                if(v == vv.VendorID){
                    name = vv.vendorName;
                    value.push(parseFloat(vv.total));
                }
            });
            color = get_backgroundColor(k);
            d = {
                label           : name,
                data            : value,
                backgroundColor : color[0],
                borderColor     : color[1],
                borderWidth     : 1,
            };
            datasets.push(d);
        });

    }else{
        labels.push("Data Not Found");
        value.push(100);
        backgroundColor.push('rgba(208,208,208,0.82');
        borderColor.push('rgba(208,208,208,0.82)');
        d = {
            label           : 'Data Not Found',
            data            : 0,
            backgroundColor : backgroundColor,
            borderColor     : borderColor,
            borderWidth     : 1,
        };
        datasets.push(d);
    }
    var data = {
        labels: labels,
        datasets : datasets,
    };

    chart_sales_open = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            barValueSpacing: 20,
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                    }
                }]
            },
            legend: {
                position : "right",
            }
        },
    
    });

    $('.total_sales_open').text(total);
}

function sales_overdude(data){
    var ctx = document.getElementById("sales_overdude");
    chart_sales_overdude.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    total               = data.total;
    if(data.ID.length>0){
        no   = 0;
        name = '';
         date1 = '';
        $.each(data.ID, function(k,v){
            value = [];
            $.each(data.data, function(kk,vv){
                if($.inArray(vv.date, labels) == -1){
                    labels.push(vv.date);
                }
                if(v == vv.VendorID){
                    name = vv.vendorName;
                    value.push(parseFloat(vv.total));
                }
            });
            color = get_backgroundColor(k);
            d = {
                label           : name,
                data            : value,
                backgroundColor : color[0],
                borderColor     : color[1],
                borderWidth     : 1,
            };
            datasets.push(d);
        });

    }else{
        labels.push("Data Not Found");
        value.push(100);
        backgroundColor.push('rgba(208,208,208,0.82');
        borderColor.push('rgba(208,208,208,0.82)');
        d = {
            label           : 'Data Not Found',
            data            : 0,
            backgroundColor : backgroundColor,
            borderColor     : borderColor,
            borderWidth     : 1,
        };
        datasets.push(d);
    }
    var data = {
        labels: labels,
        datasets : datasets,
    };

    chart_sales_overdude = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            barValueSpacing: 20,
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                    }
                }]
            },
            legend: {
                position : "right",
            }
        },
    
    });

    $('.total_sales_overdude').text(total);
}

function sales_payment(data){
    var ctx = document.getElementById("sales_payment");
    chart_sales_payment.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    total               = data.total;
    if(data.ID.length>0){
        no   = 0;
        name = '';
         date1 = '';
        $.each(data.ID, function(k,v){
            value = [];
            $.each(data.data, function(kk,vv){
                if($.inArray(vv.date, labels) == -1){
                    labels.push(vv.date);
                }
                if(v == vv.VendorID){
                    name = vv.vendorName;
                    value.push(parseFloat(vv.total));
                }
            });
            color = get_backgroundColor(k);
            d = {
                label           : name,
                data            : value,
                backgroundColor : color[0],
                borderColor     : color[1],
                borderWidth     : 1,
            };
            datasets.push(d);
        });

    }else{
        labels.push("Data Not Found");
        value.push(100);
        backgroundColor.push('rgba(208,208,208,0.82');
        borderColor.push('rgba(208,208,208,0.82)');
        d = {
            label           : 'Data Not Found',
            data            : 0,
            backgroundColor : backgroundColor,
            borderColor     : borderColor,
            borderWidth     : 1,
        };
        datasets.push(d);
    }
    var data = {
        labels: labels,
        datasets : datasets,
    };

    chart_sales_payment = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            barValueSpacing: 20,
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                    }
                }]
            },
            legend: {
                position : "right",
            }
        },
    
    });

    $('.total_sales_payment').text(total);
}

function get_backgroundColor(index,transaparant){
    backgroundcolor = 'rgba(216,216,216,0.82)';
    bordercolor     = 'rgba(216,216,216,1)';
    
    // biru
    if(index == 0){
        backgroundcolor = 'rgba(54,162,235,0.82)';
        bordercolor     = 'rgba(54,162,235,1)';
        if(transaparant){
            backgroundcolor = 'rgba(54,162,235,0)';
        }
    }
    // hijau
    else if(index == 1){
        backgroundcolor = 'rgba(70,190,138,0.82)';
        bordercolor     = 'rgba(70,190,138,1)';
        if(transaparant){
            backgroundcolor = 'rgba(70,190,138,0)';
        }
    }
    // red
    else if(index == 2){
        backgroundcolor = 'rgba(255,99,132,0.82)';
        bordercolor     = 'rgba(255,99,132,1)';
        if(transaparant){
            backgroundcolor = 'rgba(255,99,132,0)';
        }
    }
    // orange
    else if(index == 3){
        backgroundcolor = 'rgba(227,165,74,0.82)';
        bordercolor     = 'rgba(227,165,74,1)';
        if(transaparant){
            backgroundcolor = 'rgba(227,165,74,0)';
        }
    }
    // kuning
    else if(index == 4){
        backgroundcolor = 'rgba(255,205,86,0.82)';
        bordercolor     = 'rgba(255,205,86,1)';
        if(transaparant){
            backgroundcolor = 'rgba(255,205,86,0)';
        }
    }
    

    data = [backgroundcolor,bordercolor];

    return data;
}

function get_report(page = ""){ 
    url = host + "report";
    // url = host + "report?tes=ya";
    id  = '#form-dashboard-filter';
    $(id).attr('action', url);
    $(id).attr('target', '_blank');
    $('<input />').attr('type', 'hidden')
          .attr('name', "Report")
          .attr('value', page)
          .appendTo(id);
    $(id).submit();
}

function selected_item(ul_id,li_class,page){
    $(ul_id+" li").removeClass('li-active');
    $(ul_id+" "+li_class).addClass("li-active");
    if(page != "non"){
        tagdata = $(ul_id).data();
        page = '';
        if(tagdata.page){
            page = tagdata.page;
        }
        load_data(page);
    }
}
