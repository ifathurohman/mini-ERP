var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host 			= window.location.origin+'/';
var url 			= window.location.href;
var page_login 		= host + "main/login";
var page_register 	= host + "main/register";
var url_list 		= host + "return_sales/ajax_list/";
var url_edit        = host + "return_sales/ajax_edit/";
var url_cancel 		= host + "return_sales/cancel/";
var url_simpan 		= host + "return_sales/save";
var url_simpan_remark   = host + "return_sales/save_remark";
var save_method; //for save method string
var table;
var data_detailsellnya = [];
var data_detaildelnya  = [];
var title_page         = '';

var id_row = 0;
//dashboard
var url_post        = host + "dashboard/dashboard/";
var period = "";
var chart_sales_city,chart_item_delivery,chart_sales_return;
//end dashboard
$(window).load(function(){

    page_data   = $(".page-data").data();
    app         = page_data.app;
    ap          = page_data.ap;

    
});
//end dashboard
$(document).ready(function() {
    // selling
    selected_item('#ul-sales_city', st_period_type, 'non');
    selected_item('#ul-item_delivery', st_period_type,'non');
    selected_item('#ul-sales_return', st_period_type,'non');
    create_chart();
    //datatables
    filter_table();
});

function filter_table(page){
	data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    url    		= url_list+url_modul+"/"+modul;
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
        "destroy" 	: true,
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
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}

function tambah(){
    save_method = 'add';
    $(".readonly").attr("readonly",true);

    $("#form input, #form textarea, #form select").attr("disabled",false);
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.input-error').removeClass('input-error');
    $('.help-block').empty(); // clear error string
    $("#modal .save").show();
    $('#modal').modal('show'); // show bootstrap modal
    if (modul == "sales") {
        modulTitle = "Employee";
    }else{
        modulTitle = modul;
    }
    $('.link_add_row').show();
    $('.vaction, .vprint').hide();
    $('.table-add-product tbody').children( 'tr' ).remove();
    // add_new_row();
    ckOrder();
    $('.modal-title').text(language_app.lb_add_new+' '+title_page); // Set Title to Bootstrap modal title
    $('[name="crud"]').val("insert");
    $('.disabled').attr('disabled',true);
    reset_button_action();
    reset_file_upload();
    show_upload_file();
    hide_button_cancel2();
    create_form_attach();
    $('.btn-close').show();
    //-----------------------------------------------------------------------------
}

$('input[type=radio][name=ckOrder]').change(function() {
    ckOrder();
});
function ckOrder(non){
	$('.vdcode').hide();
    val         = $('input[type=radio][name=ckOrder]:checked').val();
    CustomerID  = $('#CustomerID').val();
    SellNo      = $('#SellNo').val();
    $('.table-add-product tbody').empty();
    if(val == 3) {
    	$('.vsell').show(300);    
    	$('.vdelivery').hide(300);
    	$('.vorder').text('Sell Code'); 
        $('.th-qty-s').text('Sell Qty');

        code = $('[name=SellNo]').val();
        if(code && non != 'non'){
            sell_detail(code);
        }
    }else{
        $('.vsell').hide(300);    
    	$('.vdelivery').show(300);
    	$('.vorder').text('Delivery Code');
        $('.th-qty-s').text('Delivery Qty');

        code = $('[name=DeliveryNo]').val();
        if(code && non != 'non'){
            delivery_detail(code);
        }
    }
}

// selling modal
function select_sell(classnya){
	vendorid = $('#CustomerID').val();
    vendorid = vendorid.split('-');
    if(vendorid[0]){
        selling_modal(vendorid[0],classnya,"return_sales");
    }else{
        swal('',language_app.lb_customer_select,'warning');
    }
}
// end selling

// delivery modal
function select_delivery(classnya){
	vendorid = $('#CustomerID').val();
    vendorid = vendorid.split('-');
    if(vendorid[0]){
        delivery_modal(vendorid[0],classnya,"return_sales");
    }else{
        swal('',language_app.lb_customer_select,'warning');
    }
}
// end delivery modal

function resetdata(){
    $('.table-add-product tbody').empty();
    $('[name=SellNo], [name=DeliveryNo]').val('');
}

// calculate total
function SumTotal(element){
    sub_total       = 0;
    discount        = 0;
    deliverycost    = 0;
    ppn             = 0;
    total           = 0;

    list_data       = $(".table-add-product tbody input");
    $.each(list_data,function(i,v){
        if($(v).is(":checked")){
            val = $(v).val();
            conversion = $('.vd'+val+' [name="product_konv[]"]').val();
            conversion = removeduit(conversion);

            xprice = $('.vd'+val+' [name="product_price[]"]').val();
            xprice = removeduit(xprice);

            xqty   = $('.vd'+val+' [name="product_qty[]"]').val();
            xqty   = removeduit(xqty);

            xqty   = xqty * conversion;

            xdiscount = $('.vd'+val+' [name="product_discount[]"]').val();
            xdiscount = removeduit(xdiscount);

            xtax = $('.vd'+val+' [name="product_tax2[]"]').val();
            xtax = removeduit(xtax);
            
            if(xtax == 1){
                xtax = 10;
            }else{
                xtax = 0;
            }

            xsub_total  = xprice * xqty;
            sub_total   += xsub_total;
            xdiscount   = PersenttoRp(xsub_total, xdiscount);
            discount    += xdiscount;
            xsub_total  -= xdiscount;
            xtax        = PersenttoRp(xsub_total,xtax);
            ppn         += xtax;
            xsub_total  += xtax;
            total       += xsub_total;

            $('.vd'+val+' [name="product_discountrp[]"]').val(xdiscount);
            $('.vd'+val+' [name="product_tax[]"]').val(xtax);
            $('.vd'+val+' [name="product_subtotal[]"]').val(xsub_total);
        }
    });

    discount2 = RptoPersent(sub_total,discount)

    $('[name=SubTotal]').val(sub_total);
    $('[name=Discount]').val(discount2);
    $('[name=DiscountRp]').val(discount);
    $('[name=TotalPPN]').val(ppn);
    $('[name=Total]').val(total);
    run_function = 'SumTotal()';
    if(element){
        create_format_currency2();
    }
}
// calculate total

function save(page)
{
    proses_save_button();
    $("#form input").attr("disabled",false);
    $('.info-warning').empty();
    $('.form-group, .input-group').removeClass('has-error'); // clear error class
    $('.input-error').removeClass('input-error');
    $('.help-block').empty();

    var url;
    // form = "#form";
    if(save_method == "add_serial"){
        url = url_add_serial;
        form = "#form-serial";
    }
    else {
        url = url_simpan;
    }

    var form        = $('#form')[0]; // You need to use standard javascript object here
    var formData    = new FormData(form);
    formData.append('warning', page);

    serial_length = $('.vserial').length;
    arrSerial       = [];
    arrSerialKey    = [];
    for (var i = 0; i < serial_length; i++) {
        sn      = $('.vserial .value-serial').eq(i).val();
        key     = $('.vserial .value-key').eq(i).val();

        arrSerial.push(sn);
        arrSerialKey.push(key);
    }

    dt_serial    = JSON.stringify(arrSerial);
    dt_serialkey = JSON.stringify(arrSerialKey);
    dt_serialauto= form_to_serial_by_class('p_serial_auto');
    formData.append('dt_serial', dt_serial);
    formData.append('dt_serialkey', dt_serialkey);
    formData.append('dt_serialauto', dt_serialauto);

    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        dataType: "JSON",
        processData: false,
        contentType: false,
        success: function(data)
        {
            console.log(data);
            $(".disabled").attr("disabled",true);
            if(data.page){
                $('#modal-add-serial').modal("hide");
            } else {
                if(data.status) //if success close modal and reload ajax table
                {
                    if(ck_count_save_file()>0){
                        upload_attachment_file(data.ID);
                    }
                    swal('',data.message,'success');
                    $('#modal').modal("hide");
                    reload_table();
                }
                else
                {
                    val         = $('input[type=radio][name=ckOrder]:checked').val();
                    for (var i = 0; i < data.inputerror.length; i++)
                    {
                        list    = data.list[i];
                        tab     = data.tab[i];
                        if(list == 'list'){
                            item = '<i class="icon fa-exclamation-triangle" title="'+data.error_string[i]+'" style="cursor:pointer;padding:5px;"></i>';
                            $('.vd'+data.inputerror[i]+' .info-warning').append(item);
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
            }
            $(".disabled").attr("disabled",true);
            success_save_button();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            $(".disabled").attr("disabled",true);
            alert('Error adding / update data');
            console.log(jqXHR.responseText);
            success_save_button();
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
    
    url = host + "return-sales-view/"+id+"?page=print";
    action_print_button(url);
}

function view_print_data(id,page){
    if(page == "print"){
        page = "print";
    }else{
        page = "Return";
    }
    $('#modal-print').modal('show');
    $('#modal-print .modal-title').text(title_page+" "+language_app.btn_detail);
    url = host + "return-sales-view/"+id+"?page="+page;

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

// cancel
function cancel(id){
    swal({   
        title: language_app.lb_cancel_alert,   
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
                    url : url_cancel+id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data)
                    {
                        if(data.status){
                            $('#modal-print').modal('hide');
                            swal('',data.message, 'success');
                            reload_table();
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
// cencel

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
    $('.link_add_row, .save').show();
    $('.table-add-product tbody').children('tr').remove();
    reset_button_action();
    reset_file_upload();
    reset_button_action();
    show_upload_file();
    hide_button_cancel2();
    tbl = '.table-add-product';
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            list            = data.list;
            detail          = data.detail;

            $('[name=ReturnNo]').val(list.ReturNo);
            $('[name=Remark]').val(list.Remark);
            $('[name=CustomerID]').val(list.VendorID+"-"+list.vendorName);
            $('[name=CustomerName]').val(list.vendorName);
            $('[name=SalesID]').val(list.SalesID+"-"+list.salesName);
            $('[name=SalesName]').val(list.salesName);
            $("input[name=ckOrder][value='"+list.ReturType+"']").prop('checked', true);
            ckOrder('non');
            if(list.ReturType == 3){
                $('[name=SellNo]').val(list.transactionCode);
                $('[name=temp_sellno]').val(list.transactionCode);
                temp_selldet = [];
                data_detailsellnya = detail;
                $.each(detail, function(i,v){
                    temp_selldet.push(v.SellDet);
                });
                $('[name=temp_selldet]').val(temp_selldet);
                sell_detail(list.SellNo);
            }else{
                $('[name=DeliveryNo]').val(list.transactionCode);
                $('[name=temp_deliveryno]').val(list.transactionCode);
                temp_deliverydet = [];
                data_detaildelnya = detail;
                $.each(detail, function(i,v){
                    temp_deliverydet.push(v.DeliveryDet);
                });
                $('[name=temp_deliverydet]').val(temp_deliverydet);
                delivery_detail(list.transactionCode);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);
        }
    });
}

// end edit

function ckdeliverydet(DeliveryDet){
    status      = false;
    detid       = '';
    remark      = '';
    qty         = 0;
    $.each(data_detaildelnya, function(i,v){
        if(v.DeliveryDet == DeliveryDet){
            status      = true;
            detid       = v.ReturDet;
            remark      = v.Remark;
            qty         = parseFloat(v.Qty);
        }
    });

    var data = [status,qty,detid,remark];
    return data;
}

function ckselldet(SellDet){
    status      = false;
    detid       = '';
    remark      = '';
    xqty         = 0;
    $.each(data_detailsellnya, function(i,v){
        if(v.SellDet == SellDet){
            status      = true;
            detid       = v.ReturDet;
            remark      = v.Remark;
            xqty         = parseFloat(v.Qty);
        }
    });

    var data = [status,xqty,detid,remark];
    return data;
}

$(".table-add-product [name=check_all]").click(function(){
    if($(this).is(':checked')){
        $(".table-add-product tbody [type=checkbox]").prop("checked",true);
    } else {
        $(".table-add-product tbody [type=checkbox]").prop("checked",false);
    }
    SumTotal('element');
});

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
    var ctx = document.getElementById("sales_city");
    chart_sales_city = new Chart(ctx,{});

    var ctx = document.getElementById("item_delivery");
    chart_item_delivery = new Chart(ctx,{});

    var ctx = document.getElementById("sales_return");
    chart_sales_return = new Chart(ctx,{});

}
function load_data(page = "")
{
    url_post    =  host+"dashboard/dashboard";
    Check       = $("[name=Check]:checked").val();
    StartDate   = $("[name=fStartDate]").val();
    EndDate     = $("[name=fEndDate]").val();

    // untuk data diagram
    sales_cityx      = $('#ul-sales_city .li-active').data();
    item_deliveryx   = $('#ul-item_delivery .li-active').data();
    sales_returnx    = $('#ul-sales_return .li-active').data();

    data_post   = {
        Check           : Check,
        StartDate       : StartDate,
        EndDate         : EndDate,
        sales_city      : sales_cityx.type,
        sales_return    : sales_returnx.type,
        item_delivery   : item_deliveryx.type,
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
            
            if(page == "sales_city"){
                sales_city(data.sales_city);
            }else if(page == "item_delivery"){
                item_delivery(data.item_delivery);
            }else if(page == "sales_return"){
                sales_return(data.sales_return);
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
        
    item_delivery(data.item_delivery);
    sales_city(data.sales_city);
    sales_return(data.sales_return);

}

function sales_city(data){
    var ctx = document.getElementById("sales_city");
    chart_sales_city.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];

    if(data.name.length>0){
        no   = 0;
        name = '';
         date1 = '';
        $.each(data.name, function(k,v){
            value = [];
            $.each(data.data, function(kk,vv){
                if($.inArray(vv.date, labels) == -1){
                    labels.push(vv.date);
                }
                if(v == vv.city){
                    name = vv.city;
                    value.push(parseFloat(vv.qty));
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
    chart_sales_city = new Chart(ctx, {
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
            }
        },
    
    });
}

function item_delivery(data){
    var ctx = document.getElementById("item_delivery");
    chart_item_delivery.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    total               = 0;
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
                    total += parseFloat(vv.qty);
                    value.push(parseFloat(vv.qty));
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

    chart_item_delivery = new Chart(ctx, {
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
            }
        },
    
    });

    $('.total_item_delivery').text(total);
}

function sales_return(data){
    var ctx = document.getElementById("sales_return");
    chart_sales_return.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    total               = 0;
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
                    total += parseFloat(vv.qty);
                    value.push(parseFloat(vv.qty));
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

    chart_sales_return = new Chart(ctx, {
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
            }
        },
    
    });

    $('.total_sales_return').text(total);
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