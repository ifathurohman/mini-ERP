var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host 			= window.location.origin+'/';
var url 			= window.location.href;
var page_login 		= host + "main/login";
var page_register 	= host + "main/register";
var url_list 		= host + "invoice_ar/ajax_list/";
var url_edit        = host + "invoice_ar/ajax_edit/";
var url_cancel 		= host + "invoice_ar/cancel/";
var url_simpan 		= host + "invoice_ar/save";
var url_simpan_remark = host + "invoice_ar/save_remark";
var save_method; //for save method string
var table;

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
	data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    title_page  = data_page.title;
    
    if(data_page.id){
        statusid = data_page.statusid;
        statusid = statusid.split("-");
        if(statusid[0] != 1){
            if(statusid[1] == "selling"){
                get_detail_sell(data_page.id);
            }
            else if(statusid[1] == "delivery"){
                get_detail_delivery(data_page.id);
            }
            else if(statusid[1] == "return"){
                get_detail_return(data_page.id);
            }
        }
    }

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

    fSearch             = $('#form-filter [name=fSearch]').val();
    fStartDate          = $('#form-filter [name=fStartDate]').val();
    fEndDate            = $('#form-filter [name=fEndDate]').val();
    fStatus             = $('#form-filter [name=fStatus]').val();
    // fTypeStatus         = $('#form-filter [name=fTypeStatus]').val();

    data_post = {
        Search              : fSearch,
        StartDate           : fStartDate,
        EndDate             : fEndDate,
        Status              : fStatus,
        // Type                : fTypeStatus,
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
    $('.modal-title').text(language_app.lb_add_new+' '+title_page); // Set Title to Bootstrap modal title
    $('[name="crud"]').val("insert");
    $('[name=temp_sellno]').val('');
    $('[name=temp_deliveryno]').val('');
    $('.disabled').attr('disabled',true);
    reset_button_action();
    reset_file_upload();
    show_upload_file();
    hide_button_cancel2();
    create_form_attach();
    $('.btn-close').show();
    //-----------------------------------------------------------------------------
}

// customer address
function select_address_vendor(classnya){
    vendorid = $('#CustomerID').val();
    vendorid = vendorid.split('-');
    if(vendorid[0]){
        address_vendor(vendorid[0],classnya);
    }else{
        swal('',language_app.lb_customer_select,'warning');
    }
}
function reset_address_vendor(){
    $('.vdelivery .address, .vinvoice .address').val('');
    $('.vdelivery .city, .vinvoice .city').val('');
    $('.vdelivery .province, .vinvoice .province').val('');
    $('.vdelivery #NPWP').val('');
}
// end customer address

// invoice_delivery
function invoice_delivery(id){
    vendorid        = $('#CustomerID').val();
    vendorid        = vendorid.split('-');
    crud            = $('[name=crud]').val();
    invoiceno       = $('[name=temp_invoiceno]').val();
    deliveryno      = $('[name=temp_deliveryno]').val();
    returnno        = $('[name=temp_returnno]').val();
    data_post = {
        VendorID    : vendorid[0],
        method      : crud,
        InvoiceNo   : invoiceno,
        deliveryno  : deliveryno,
        returnno    : returnno,
    }
    tbl = ".table-add-product";
    $(tbl+" tbody").empty();
    $.ajax({
        url : host+"api/invoice_delivery",
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            list = data.list;
            if(list.length>0){
                $.each(list,function(i,v){
                    setitem(v,'',id);
                });
                transaction_cek('element');
                moneyFormat();
            }else{
                item  = '<tr>';
                item += '<td colspan="11"><div class="text-center">'+language_app.lb_data_not_found+'</div></td>';
                item += '</tr>';

                $(tbl+" tbody").append(item);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);
        }
    });

}

function setitem(v,page,id){
    tbl = ".table-add-product";

    remark  = '';
    checked = '';
    detid   = '';
    if(page == "invoice"){
        checked         = 'checked';
        deliveryno      = v.DeliveryNo;
        deliveryno2     = v.DeliveryNo;
        sellno          = v.SellNo,
        date            = v.Date,
        price           = v.SubTotal;
        discount        = v.Discount,
        deliverycost    = v.DeliveryCost;
        tax             = v.PPN;
        total           = v.Total;
        remark          = v.Remark;
        detid           = v.InvoiceDet;
        invoicetype     = v.invoiceType;
        invoicetypetxt  = v.invoiceTypetxt;
    }else{
        temp_deliveryno = $('[name=temp_deliveryno]').val();
        temp_deliveryno = temp_deliveryno.split(',');
        if(jQuery.inArray(v.DeliveryNo, temp_deliveryno) !== -1){
            checked = 'checked';
        }
        deliveryno      = v.DeliveryNo;
        deliveryno2     = v.DeliveryNo;
        sellno          = v.SellNo,
        date            = v.Date,
        price           = v.price;
        discount        = v.discount,
        deliverycost    = v.deliverycost;
        tax             = v.totalppn;
        total           = v.total;
        invoicetype     = v.invoiceType;
        invoicetypetxt  = v.invoiceTypetxt;
    }

    if(invoicetype == "return"){
        deliveryno  = v.ReturNo;
        sub_total   = parseFloat(price) - parseFloat(discount);
        if(v.Tax == 1){
            tax = PersenttoRp(sub_total,10);
        }
    }

    deliveryno_replace = deliveryno.replace(/[/]/g, '-');
    // checkbox
    item  = '<tr class="vd'+deliveryno_replace+'">';
    item += '<td><div class="info-warning"></div></td>';
    item += '<td>\
            <input class="cekbox" type="checkbox" onclick="transaction_cek(this)" name="check[]" value="'+deliveryno_replace+'" '+checked+'>\
            <input class="disabled" type="hidden" name="detid[]" value="'+detid+'" >\
            </td>';

    // delivery no
    item += '<td>\
            <input class="disabled" type="text" name="deliveryno[]" value="'+deliveryno+'" >\
            <input class="disabled" type="hidden" name="deliveryno2[]" value="'+deliveryno2+'" >\
            </td>';

    // Transaction Type
    item += '<td>\
            <input class="disabled" type="hidden" name="invoicetype[]" value="'+invoicetype+'" >\
            <input class="disabled" type="text" value="'+invoicetypetxt+'" >\
            </td>';

    // selling no
    item += '<td>\
            <input class="disabled" type="text" name="sellno[]" value="'+sellno+'" >\
            </td>';

    // delivery date
    item += '<td>\
            <input class="disabled" type="text" name="delDate[]" value="'+date+'" >\
            </td>';

    // sub total
    item += '<td>\
            <input class="disabled duit" type="text" name="delSub_total[]" value="'+parseFloat(price)+'" >\
            </td>';

    // discount
    item += '<td>\
            <input class="disabled duit" type="text" name="delDiscount[]" value="'+parseFloat(discount)+'" >\
            </td>';

    // TAX/PPN
    item += '<td>\
            <input class="disabled duit" type="text" name="delTax[]" value="'+parseFloat(tax)+'" >\
            </td>';

    // delivery cost
    item += '<td>\
            <input class="duit" type="text" name="delCost[]" value="'+parseFloat(deliverycost)+'" onkeyup="transaction_cek()">\
            </td>';

    // Total
    item += '<td>\
            <input class="disabled duit" type="text" name="delTotal[]" value="'+parseFloat(total)+'" >\
            </td>';

    // Remark
    item += '<td>\
            <input type="text" name="delRemark[]" placeholder="input remark" value="'+remark+'">\
            </td>';

    $(tbl+" tbody").append(item);
    if(id){
        $('.vd'+id+' .cekbox').prop('checked', true);
    }
    $(".disabled").attr("disabled",true);
}
// end invoice_delivery

$(".table-add-product [name=check_all]").click(function(){
    if($(this).is(':checked')){
        $(".table-add-product tbody [type=checkbox]").prop("checked",true);
    } else {
        $(".table-add-product tbody [type=checkbox]").prop("checked",false);
    }
    transaction_cek('element');
});

function transaction_cek(element){
    sub_total       = 0;
    discount        = 0;
    deliverycost    = 0;
    ppn             = 0;
    total           = 0;
    total_kolom     = 0;
    status_kolom    = true;

    list_data       = $(".table-add-product tbody input");
    $.each(list_data,function(i,v){
        if($(v).is(":checked")){
            total_kolom += 1;
            if(total_kolom>100){
                status_kolom = false;
                $(v).attr('checked', false);
            }else{
                val = $(v).val();

                xinvoicetype    = $('.vd'+val+' [name="invoicetype[]"]').val();

                xsub_total      = $('.vd'+val+' [name="delSub_total[]"]').val();
                xsub_total      = removeduit(xsub_total);

                xdiscount       = $('.vd'+val+' [name="delDiscount[]"]').val();
                xdiscount       = removeduit(xdiscount);

                xdelcost        = $('.vd'+val+' [name="delCost[]"]').val();
                xdelcost        = removeduit(xdelcost);

                xppn            =  $('.vd'+val+' [name="delTax[]"]').val();
                xppn            = removeduit(xppn);

                total           = parseFloat(xsub_total) -parseFloat(xdiscount) + parseFloat(xppn) +parseFloat(xdelcost);
                xtotal          = $('.vd'+val+' [name="delTotal[]"]').val(total);
                // xtotal          = removeduit(xtotal);

                if(xinvoicetype == "return"){
                    sub_total       -= xsub_total;
                    discount        -= xdiscount;
                    deliverycost    -= xdelcost;
                    ppn             -= xppn;
                    total           -= xtotal;
                }else{
                    sub_total       += xsub_total;
                    discount        += xdiscount;
                    deliverycost    += xdelcost;
                    ppn             += xppn;
                    total           += xtotal;
                }
            }    
        }
    });

    if(!status_kolom){
        swal('','Data item max 100', 'warning');
    }

    total = parseFloat(sub_total) -parseFloat(discount) + parseFloat(ppn) +parseFloat(deliverycost);
    $('[name=SubTotal]').val(sub_total);
    $('[name=Discount]').val(discount);
    $('[name=PPN]').val(ppn);
    $('[name=DeliveryCost]').val(deliverycost);
    $('[name=GrandTotal]').val(total);
    run_function = 'transaction_cek()';
    if(element){
        create_format_currency2();
    }
}

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
                    for (var i = 0; i < data.inputerror.length; i++)
                    {
                        list    = data.list[i];
                        if(list == 'list'){
                            item = '<i class="icon fa-exclamation-triangle" title="'+data.message+'" style="cursor:pointer;padding:5px;"></i>';
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
            success_save_button();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            $('.disabled').attr('disabled',true);
            success_save_button();
            alert('Error adding / update data');
            console.log(jqXHR.responseText);
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

    url = host + "invoice-ar-view/"+id+"?page=print";
    action_print_button(url);
}
function view_print_data(id,page){
    if(page == "print"){
        page = "print";
    }else{
        page = "Invoice";
    }
    $('#modal-print').modal('show');
    $('#modal-print .modal-title').text(title_page+" "+language_app.btn_detail);
    url = host + "invoice-ar-view/"+id+"?page="+page;

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
                            view(id);
                            swal('',data.message, 'success');
                            reload_table();
                            $(".modal:visible").modal('toggle');
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
    tbl = '.table-add-product';
    reset_button_action();
    reset_file_upload();
    show_upload_file();
    hide_button_cancel2();
    create_form_attach();
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
            delivery    = data.delivery;
            sell        = data.sell;
            $('[name=InvoiceNo]').val(list.InvoiceNo);
            $('[name=temp_invoiceno]').val(list.InvoiceNo);
            $('[name=Date]').val(list.Date);
            $('[name=CustomerID]').val(list.VendorID+"-"+list.vendorName);
            $('[name=CustomerName]').val(list.vendorName);
            $('[name=invAddress]').val(list.InvoiceAddress);
            $('[name=invCity]').val(list.InvoiceCity);
            $('[name=invProvince]').val(list.InvoiceProvince);
            $('[name=NPWP]').val(list.InvoiceNPWP);
            $('[name=Remark]').val(list.Remark);
            $('[name=GrandTotal]').val(parseFloat(list.Total));
            $('[name=Term]').val(parseFloat(list.Term));
            count_term('term');

            // delivery
            if(list.OrderType == 1){
                $("input[name=OrderType][value='1']").prop('checked', true);
                ckOrderType('non');
                temp_deliveryno = [];
                temp_returnno   = [];
                $.each(detail, function(i, v) {
                    setitem(v,"invoice");
                    if(v.invoiceType == "return"){
                        temp_returnno.push(v.ReturNo);
                    }else{
                        temp_deliveryno.push(v.DeliveryNo);
                    }
                });
                $('[name=temp_deliveryno]').val(temp_deliveryno);
                $('[name=temp_returnno]').val(temp_returnno);
                
                $.each(delivery, function(i, v) {
                    setitem(v);
                });
            // delivery
            }else if(list.OrderType == 2){
                $("input[name=OrderType][value='2']").prop('checked', true);
                ckOrderType('non');
                temp_sellno = [];
                temp_returnno   = [];
                $.each(detail, function(i, v) {
                    setitem_sell(v,"invoice");
                    if(v.invoiceType == "return"){
                        temp_returnno.push(v.ReturNo);
                    }else{
                        temp_sellno.push(v.SellNo);
                    }
                });
                $('[name=temp_sellno]').val(temp_sellno);
                $('[name=temp_returnno]').val(temp_returnno);

                $.each(sell, function(i, v) {
                    setitem_sell(v);
                });
            }else{
                $("input[name=OrderType][value='2']").prop('checked', true);
                ckOrderType('non');
            }
            
            transaction_cek();
            moneyFormat();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);
        }
    });
}

// end edit
$('input[type=radio][name=OrderType]').change(function() {
    ckOrderType();
});
function ckOrderType(non){
    val         = $('input[type=radio][name=OrderType]:checked').val();
    CustomerID  = $('#CustomerID').val();
    $('.vaddrow').empty();
    $('.table-add-product tbody').empty();
    if(val == 1){
        $('.vdo').show(300);
        if(non != "non"){
            if(CustomerID){
                invoice_delivery();
            }
        }
    }
    else if(val == 2){
        $('.vdo').hide(300);
        if(non != "non"){
            if(CustomerID){
                invoice_selling();
            }
        }
    }
    else{
        $('.vdo').hide(300);
    }
}
// cek order type

// invoice sales / selling
function invoice_selling(id){
    vendorid        = $('#CustomerID').val();
    vendorid        = vendorid.split('-');
    crud            = $('[name=crud]').val();
    invoiceno       = $('[name=temp_invoiceno]').val();
    temp_sellno     = $('[name=temp_sellno]').val();
    temp_returnno   = $('[name=temp_returnno]').val();
    data_post = {
        VendorID        : vendorid[0],
        method          : crud,
        InvoiceNo       : invoiceno,
        temp_sellno     : temp_sellno,
        temp_returnno   : temp_returnno,
    }
    tbl = ".table-add-product";
    $(tbl+" tbody").empty();
    $.ajax({
        url : host+"api/invoice_sell",
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            list = data.list;
            if(list.length>0){
                $.each(list,function(i,v){
                    setitem_sell(v,'',id);
                });
                transaction_cek('element');
                moneyFormat();
            }else{
                item  = '<tr>';
                item += '<td colspan="11"><div class="text-center">'+language_app.lb_data_not_found+'</div></td>';
                item += '</tr>';

                $(tbl+" tbody").append(item);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);
        }
    });
}

function setitem_sell(v,page,id){
    tbl = ".table-add-product";

    remark  = '';
    checked = '';
    detid   = '';
    if(page == "invoice"){
        checked         = 'checked';
        sellno          = v.SellNo,
        sellno2         = v.SellNo,
        date            = v.Date,
        price           = v.SubTotal;
        discount        = v.Discount,
        deliverycost    = v.DeliveryCost;
        tax             = v.PPN;
        total           = v.Total;
        remark          = v.Remark;
        detid           = v.InvoiceDet;
        invoicetype     = v.invoiceType;
        invoicetypetxt  = v.invoiceTypetxt;
    }else{
        temp_sellno = $('[name=temp_sellno]').val();
        temp_sellno = temp_sellno.split(',');
        if(jQuery.inArray(v.SellNo, temp_sellno) !== -1){
            checked = 'checked';
        }
        sellno          = v.SellNo,
        sellno2         = v.SellNo,
        date            = v.Date,
        price           = v.SubTotal;
        discount        = v.Discount,
        deliverycost    = v.DeliveryCost;
        tax             = v.PPN;
        total           = v.Total;
        invoicetype     = v.invoiceType;
        invoicetypetxt  = v.invoiceTypetxt;
    }

    if(invoicetype == "return"){
        sub_total   = parseFloat(price) - parseFloat(discount);
        sellno      = v.ReturNo;
        if(v.Tax == 1){
            tax = PersenttoRp(sub_total,10);
        }
    }

    sellno_replace = sellno.replace(/[/]/g, '-');
    // checkbox
    item  = '<tr class="vd'+sellno_replace+'">';
    item += '<td><div class="info-warning"></div></td>';
    item += '<td>\
            <input class="cekbox" type="checkbox" onclick="transaction_cek()" name="check[]" value="'+sellno_replace+'" '+checked+'>\
            <input class="disabled" type="hidden" name="detid[]" value="'+detid+'" >\
            </td>';

    // Transaction Type
    item += '<td>\
            <input class="disabled" type="hidden" name="invoicetype[]" value="'+invoicetype+'" >\
            <input class="disabled" type="text" value="'+invoicetypetxt+'" >\
            </td>';

    // selling no
    item += '<td>\
            <input class="disabled" type="text" value="'+sellno2+'" >\
            <input class="disabled" type="hidden" name="sellno[]" value="'+sellno_replace+'" >\
            </td>';

    // delivery date
    item += '<td>\
            <input class="disabled" type="text" name="delDate[]" value="'+date+'" >\
            </td>';

    // sub total
    item += '<td>\
            <input class="disabled duit" type="text" name="delSub_total[]" value="'+parseFloat(price)+'" >\
            </td>';

    // discount
    item += '<td>\
            <input class="disabled duit" type="text" name="delDiscount[]" value="'+parseFloat(discount)+'" >\
            </td>';

    // TAX/PPN
    item += '<td>\
            <input class="disabled duit" type="text" name="delTax[]" value="'+parseFloat(tax)+'" >\
            </td>';

            // delivery cost
    item += '<td>\
            <input class="duit" type="text" name="delCost[]" value="'+parseFloat(deliverycost)+'" onkeyup="transaction_cek()">\
            </td>';

    // Total
    item += '<td>\
            <input class="disabled duit" type="text" name="delTotal[]" value="'+parseFloat(total)+'" >\
            </td>';

    // Remark
    item += '<td>\
            <input type="text" name="delRemark[]" placeholder="input remark" value="'+remark+'">\
            </td>';

    $(tbl+" tbody").append(item);
    if(id){
        $('.vd'+id+' .cekbox').prop('checked', true);
    }
    $(".disabled").attr("disabled",true);
}
// end invoice sell

// Due Date and term
$('[name=Term]').on('keyup paste change',function(){
    count_term('term');
});

$('[name=DueDate]').on('keyup paste change',function(){
    count_term('due_date');
});

$('[name=Date]').on('keyup paste change', function(){
    count_term('term');
    delivery_date = $('[name=DeliveryDate]');
    delivery_date.val($("#Date").val());
    $('.p_delivery').val($("#Date").val());

})

function count_term(page){
    term        = $('[name=Term]');
    due_date    = $('[name=DueDate]');
    date        = new Date($("#Date").val());
    if(page == "term"){
        val = checkIntInput(term.val());
        date.setTime(date.getTime() + val * 24 * 60 * 60 * 1000);
        val = converttoDate(date,"yy-mm-dd");
        due_date.val(val);
    }else{
        val = due_date.val();
        end = new Date(val);

        var diff = new Date(end - date);
        var days = diff/1000/60/60/24;
        term.val(days);
    }
}

// end due date and term

function get_detail_sell(id){
    url = host + "selling/ajax_edit/"+id;
    data_post = {
        page : "invoice_selling",
    }
    $.ajax({
        url : url,
        data : data_post,
        type: "POST",
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            list    = data.list;
            if(list.InvoiceStatus == 0){
                tambah();
                $("input[name=OrderType][value='2']").prop('checked', true);
                ckOrderType('non');
                $('[name=CustomerID]').val(list.VendorID+"-"+list.customerName);
                $('[name=CustomerName]').val(list.customerName);
                $('[name=invAddress]').val(list.d_address);
                $('[name=invCity]').val(list.d_city);
                $('[name=invProvince]').val(list.d_province);
                $('[name=NPWP]').val(list.vendorNPWP);
                $('[name=Term]').val(parseFloat(list.vendorTerm));
                invoice_selling(id);
            }else{
                swal('','data already exists','info');
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);
        }
    });
}

function get_detail_delivery(id){
    url = host + "delivery/ajax_edit/"+id;
    data_post = {
        page : "invoice_selling",
    }
    $.ajax({
        url : url,
        data : data_post,
        type: "POST",
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            list    = data.list;
            if(list.InvoiceStatus == 0){
                tambah();
                $("input[name=OrderType][value='1']").prop('checked', true);
                ckOrderType('non');
                $('[name=CustomerID]').val(list.VendorID+"-"+list.vendorName);
                $('[name=CustomerName]').val(list.vendorName);
                $('[name=invAddress]').val(list.d_address);
                $('[name=invCity]').val(list.d_city);
                $('[name=invProvince]').val(list.d_province);
                $('[name=NPWP]').val(list.vendorNPWP);
                $('[name=Term]').val(parseFloat(list.vendorTerm));
                invoice_delivery(id);
            }else{
                swal('','data already exists','info');
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);
        }
    });
}

function get_detail_return(id){
    url = host + "return_sales/ajax_edit/"+id;
    data_post = {
        page : "invoice_selling",
    }
    $.ajax({
        url : url,
        data : data_post,
        type: "POST",
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            list    = data.list;
            if(list.InvoiceStatus == 0){
                tambah();
                $("input[name=OrderType][value='1']").prop('checked', true);
                ckOrderType('non');
                $('[name=CustomerID]').val(list.VendorID+"-"+list.vendorName);
                $('[name=CustomerName]').val(list.vendorName);
                $('[name=invAddress]').val(list.d_address);
                $('[name=invCity]').val(list.d_city);
                $('[name=invProvince]').val(list.d_province);
                $('[name=NPWP]').val(list.vendorNPWP);
                $('[name=Term]').val(parseFloat(list.vendorTerm));
                if(list.ReturType == 3){
                    invoice_selling(id);
                }
                else if(list.ReturType == 4){
                    invoice_delivery(id);
                }
            }else{
                swal('','data already exists','info');
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
