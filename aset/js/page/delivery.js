var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host 			= window.location.origin+'/pipesys_qa/';
var url 			= window.location.href;
var page_login 		= host + "main/login";
var page_register 	= host + "main/register";
var url_list 		= host + "delivery/ajax_list/";
var url_edit        = host + "delivery/ajax_edit/";
var url_cancel 		= host + "delivery/cancel/";
var url_simpan 		= host + "delivery/save";
var url_simpan_remark = host + "delivery/save_remark"
var save_method; //for save method string
var table;
var Dsellno,Dstatus;

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
	data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    Dsellno     = data_page.sellno;
    Dstatus     = data_page.dstatus;
    title_page  = data_page.title;

    // selling
    selected_item('#ul-sales_city', st_period_type, 'non');
    selected_item('#ul-item_delivery', st_period_type,'non');
    selected_item('#ul-sales_return', st_period_type,'non');
    create_chart();
    //datatables
    filter_table();
    if(Dsellno){
        if(Dstatus != 1){
            get_detail_sell();
        }
    }
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
    fProductType        = $('#form-filter [name=fProductType]').val();

    data_post = {
        Search              : fSearch,
        StartDate           : fStartDate,
        EndDate             : fEndDate,
        Status              : fStatus,
        ProductType         : fProductType,
    }
    table = $('#table').DataTable({
        "destroy" 	: true,
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
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
    $('.btn_select').removeClass('cursor_disabled');
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
    $('#service').hide();
    $('.link_add_row').show();
    $('.vaction, .vprint').hide();
    $('.table-add-product tbody').children( 'tr' ).remove();
    $('.table-add-serial tbody').empty();
    // add_new_row();
    set_default_branch();
    ckOrder();
    default_tab();
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
                            item = '<i class="icon fa-exclamation-triangle" title="'+data.message+'" style="cursor:pointer;padding:5px;"></i>';
                            if(val == 1){
                                $('.vd'+data.inputerror[i]+' .info-warning').append(item);
                            }else{
                                $('.'+data.inputerror[i]+' .info-warning').append(item);
                            }
                        }else{
                            $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                            $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                        }

                        if(tab == "delivery"){
                            $('.vstep1').addClass('input-error');
                        }else if(tab == "address"){
                            $('.vstep2').addClass('input-error');
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
            alert('Error adding / update data');
            console.log(jqXHR.responseText);
            success_save_button();
        }
    });
}

// selling modal
function select_sell(classnya){
	vendorid = $('#CustomerID').val();
    vendorid = vendorid.split('-');
    if(vendorid[0]){
        selling_modal(vendorid[0],classnya,"delivery");
    }else{
        swal('',language_app.lb_customer_select,'warning');
    }
}
// end selling

$(".table-add-product [name=check_all]").click(function(){
    if($(this).is(':checked')){
        $(".table-add-product tbody [type=checkbox]").prop("checked",true);
    } else {
        $(".table-add-product tbody [type=checkbox]").prop("checked",false);
    }
    SumTotal('element');
});

// vendor 
function reset_data_sell(){
    $('[name=SellNo]').val('');
    $(".table-add-product tbody tr").remove();
}
// vendor

// view
function view(id,page){
    reset_button_action();
    $('.btn-close').show();
    if(page == "print"){
        open_modal_template(id,page);
    }else{
        view_print_data(id,page);
    }

    url = host + "delivery-view/"+id+"?page=print";
    action_print_button(url);
}

function view_print_data(id,page){
    if(page == "print"){
        page = "print";
    }else{
        page = "Delivery";
    }
    $('#modal-print').modal('show');
    $('#modal-print .modal-title').text(title_page+" "+language_app.btn_detail);
    url = host + "delivery-view/"+id+"?page="+page;

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

// edit
function edit(id){
    id_row = 0;
    save_method = "update";
    $(".readonly").attr("readonly",true);
    $("#form input, #form textarea, #form select").attr("disabled",false);
    $('.btn_select').removeClass('cursor_disabled');
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
    default_tab();
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
            data_sell    = data.data_sell
            $('[name=DeliveryNo]').val(list.DeliveryNo);
            $('[name=Date]').val(list.Date);
            $('[name=CustomerID]').val(list.VendorID+"-"+list.vendorName);
            $('#CustomerID').data('productcustomer',list.productcustomer);
            $('[name=CustomerName]').val(list.vendorName);
            $('[name=SellNo]').val(list.SellNo);
            $('[name=SalesID]').val(list.SalesID+"-"+list.salesName);
            $('[name=SalesName]').val(list.salesName);
            $('[name=Remark]').val(list.Remark);
            $('[name=Ongkir]').val(parseFloat(list.DeliveryCost));
            $('[name=delAddress]').val(list.Address);
            $('[name=delCity]').val(list.City);
            $('[name=delProvince]').val(list.Province);
            $('[name=Term]').val(list.Term);
            count_term('term');

            if(list.Tax == 1){
                $('#ckPPN').prop('checked', true);
                $('#PPN').val(10);
            }else{
                $('#ckPPN').prop('checked', false);
                $('#PPN').val(0);
            }

            if(list.SellNo){
                $('#ckPPN').attr('disabled', true);
            }else{
                $('#ckPPN').attr('disabled', false);
            }

            if(list.Type == 1){
                $("input[name=ckOrder][value='1']").prop('checked', true);
            }else{
                $("input[name=ckOrder][value='2']").prop('checked', true);
            }

            if(list.ProductType == 1){
                $("input[name=product_status][value='1']").prop('checked', true);
            }else{
                $("input[name=product_status][value='0']").prop('checked', true);
            }

            ckOrder('non');

            if(list.Type == 1){
                temp_selldet = [];
                temp_sellno  = [];
                $.each(detail, function(i, v) {
                    temp_selldet.push(v.SellDet);
                    setitem(v,"delivery");
                    if(jQuery.inArray(v.SellNo,temp_sellno) == -1){
                        temp_sellno.push(v.SellNo);
                    }
                });
                $.each(data_sell, function(i, v) {
                    setitem(v,"sell");
                });
                SumTotal();
                $('[name=temp_sellno]').val(temp_sellno);
                $('[name=temp_selldet]').val(temp_selldet);
            }else{
                $.each(detail, function(i,v){
                    add_row_not_order(v);
                });
                SumTotal();
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);
        }
    });
}

function setitem(v,page){
    tbl = '.table-add-product';
    product_status  = $('[name=product_status]:checked').val();
    if(page == "delivery"){
        checkbox        = 'checked';
        deliverydet     = v.DeliveryDet;
        sellno          = v.SellNo;
        selldet         = v.SellDet;

        productid           = v.ProductID;
        code                = v.productCode;
        name                = v.productName;
        qty                 = parseFloat(v.sellQty);
        del_qty             = parseFloat(v.Qty);
        unitid              = v.unitid;
        product_unit        = v.unitName;
        product_konv        = v.Conversion;
        product_type        = v.Typetxt,
        product_price       = parseFloat(v.Price);
        product_total       = parseFloat(v.TotalPrice);
        product_discount     = parseFloat(v.Discount);
        product_discountrp  = parseFloat(v.DiscountValue);
        product_remark      = v.Remark;
        product_delivery    = parseFloat(v.DeliveryCost);
        Cost                = parseFloat(v.Cost);
        branchName       = '';
        d_module            = v.sellModule;
        if(d_module){
            d_module        = d_module.replace(/"/g, "'");
        }
    }
    else if(page == "add_sell"){
        checkbox        = '';
        deliverydet     = '';
        sellno          = v.SellNo;
        selldet         = v.SellDet;

        productid       = v.ProductID;
        code            = v.product_code;
        name            = v.product_name;
        qty             = parseFloat(v.Qty);
        del_qty         = qty - parseFloat(v.DeliveryQty);
        unitid          = v.UnitID;
        product_unit    = v.unit_name;
        product_konv    = v.Conversion;
        product_type        = v.Type;
        product_price       = parseFloat(v.Price);
        product_total       = parseFloat(v.TotalPrice);
        product_discount     = parseFloat(v.Discount);
        product_discountrp  = parseFloat(v.DiscountValue);
        product_remark      = '';
        product_delivery    = parseFloat(v.DeliveryCost);
        Cost                = parseFloat(v.Cost);
        branchName          = v.branchName;
        d_module            = v.sellModule;
        if(d_module){
            d_module        = d_module.replace(/"/g, "'");
        }
    }
    else{
        checkbox        = '';
        deliverydet     = '';
        sellno          = v.sellno;
        selldet         = v.selldet;

        productid           = v.productid;
        code                = v.product_code;
        name                = v.product_name;
        qty                 = parseFloat(v.product_qty);
        del_qty             = qty - parseFloat(v.delivery_qty);
        unitid              = v.product_unitid;
        product_unit        = v.product_unitname;
        product_konv        = v.product_konv;
        product_type        = v.product_type;
        product_price       = parseFloat(v.product_price);
        product_total       = 0;
        product_discount     = parseFloat(v.product_discount);
        product_discountrp  = 0;
        product_remark  = '';
        product_delivery    = parseFloat(v.DeliveryCost);
        Cost                = parseFloat(v.Cost);
        branchName       = '';
        d_module            = v.sellModule;
        if(d_module){
            d_module        = d_module.replace(/"/g, "'");
        }
    }

    btn_serial = '';
    if(product_type == 2){
        btn_serial  = '<a href="javascript:;" data-rowid="vd'+selldet+'" onclick="add_serial_number(this)" style="cursor:pointer;padding:5px;">Add Serial</a>';
    }

    tag_data    =' data-code="'+code+'" ';
    
    // checkbox
    item  = '<tr class="vd'+selldet+' rowdata" data-classnya="vd'+selldet+'" data-detailsn="active" data-selling="active">';
    item += '<td><div class="info-warning"></div></td>';
    item += '<td>\
            <input class="cekbox" type="checkbox" onclick="SumTotal(this)" name="check[]" '+checkbox+' value="'+selldet+'">\
            <input class="disabled" type="hidden" name="detid[]" value="'+deliverydet+'">\
            <input class="disabled detailID" type="hidden" name="product_selldet[]" value="'+selldet+'" >\
            <input class="disabled headerID" type="hidden" name="product_sellno[]" value="'+sellno+'" >\
            <input class="disabled p_id" type="hidden" name="product_id[]" value="'+productid+'" >\
            <input class="disabled" type="hidden" name="product_cost[]" value="'+Cost+'" >\
            <input class="disabled" type="hidden" name="product_module[]" value="'+d_module+'" >\
            </td>';

    // code
    item += '<td>\
            <input class="disabled" type="text" value="'+sellno+'" >\
            </td>';

    item += '<td class="vdcode">\
            <input class="disabled" type="text" value="'+branchName+'" >\
            </td>';

    // code
    item += '<td>\
            <input class="disabled" type="text" value="'+code+'" >\
            </td>';

    // name
    item += '<td>\
            <input class="disabled p_name" type="text" name="product_name[]" value="'+name+'" >\
            </td>';
    if(product_status == 0){
        // qty selling
        item += '<td>\
                <input class="disabled duit" data-qty="active" type="text" value="'+qty+'" >\
                </td>';

        // qty delivery
        item += '<td>\
                <input placeholder="'+language_app.lb_qty_input+'" data-qty="active" class="duit" type="text" onkeyup="SumTotal()" name="product_qty[]" value="'+del_qty+'" min="0" max="'+qty+'">\
                </td>';

        // unit
        item += '<td>\
                <input class="disabled" type="text" value="'+product_unit+'" >\
                <input class="disabled" type="hidden" name="product_unitid[]" value="'+unitid+'" >\
                <input class="disabled p_type" type="hidden" name="product_type[]" value="'+product_type+'" >\
                <input class="disabled p_serial_auto" type="hidden" value="'+0+'">\
                </td>';
    }

    // conversion
    item += '<td class="content-hide">\
            <input class="disabled" type="text" name="product_konv[]" value="'+product_konv+'" >\
            </td>';

    // price
    item += '<td>\
            <input class="disabled duit" type="text" name="product_price[]" value="'+product_price+'" >\
            </td>';

    // discount
    item += '<td>\
            <input type="text" class="disabled" name="product_discount[]" value="'+product_discount+'">\
            <input type="text" class="disabled content-hide duit" name="product_discountrp[]" value="'+0+'">\
            </td>';

    // Tax
    item += '<td>\
            <input type="text" class="disabled duit" name="product_tax[]" value="'+0+'">\
            </td>';

    // sub total
    item += '<td>\
            <input type="text" class="disabled duit" name="product_subtotal[]" value="'+0+'">\
            </td>';

    // delivery cost
    item += '<td class="vorder">\
            <input type="text" class="duit" name="product_delivery[]" value="'+product_delivery+'" onkeyup="SumTotal()" onchange="SumTotal()">\
            </td>';

    // remark
    item += '<td>\
            <input type="text" name="product_remark[]" placeholder="'+language_app.lb_remark_input+'" value="'+product_remark+'">\
            </td>';

    item += '<td>'+btn_serial+'</td>';

    item += '</tr>';

    $(tbl+' tbody').append(item);
    $(".disabled").attr("disabled",true);
}
// end edit

// get detail sell
function get_detail_sell(){
    url = host + "selling/ajax_edit/"+Dsellno;

    data_post = {
        page : "delivery",
    }

    $.ajax({
        url : url,
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            list    = data.list;
            detail  = data.list_detail;

            if(list.DeliveryStatus == 0){
                tambah();
                $('[name=CustomerID]').val(list.VendorID+"-"+list.customerName);
                $('[name=CustomerName]').val(list.customerName);
                // $('[name=Ongkir]').val(parseFloat(list.DeliveryCost));
                $('[name=SalesID]').val(list.SalesID+"-"+list.salesName);
                $('[name=SalesName]').val(list.salesName);
                $('[name=SellNo]').val(list.SellNo);
                $('[name=delAddress]').val(list.DeliveryAddress);
                $('[name=delCity]').val(list.DeliveryCity);
                $('[name=delProvince]').val(list.DeliveryProvince);
                if(list.Tax == 1){
                    $('#ckPPN').prop('checked', true);
                    $('#PPN').val(10);
                }else{
                    $('#ckPPN').prop('checked', false);
                    $('#PPN').val(0);
                }
                if(list.ProductType == 1){
                    $("input[name=product_status][value='1']").prop('checked', true);
                }else{
                    $("input[name=product_status][value='0']").prop('checked', true);
                }

                if(list.BranchID){
                    $('[name=BranchID]').val(list.BranchID+"-"+list.branchName);
                    $('#BranchName').val(list.branchName);
                }else{
                    $('[name=BranchID]').val('');
                    $('#BranchName').val('');
                }

                $("input[name=ckOrder][value='1']").prop('checked', true);
                ckOrder('non');
                $.each(detail, function(i, v) {
                    setitem(v,"add_sell");
                });
                moneyFormat();
                create_format_currency2();
            }else{
                item  = '<tr>';
                item += '<td colspan="14"><div class="text-center">'+language_app.lb_data_not_found+'</div></td>';
                item += '</tr>';
                $(".table-add-product tbody").append(item);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);
        }
    });
}
// end get detail sell

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

// checkbox Sales Order
$('input[type=radio][name=ckOrder]').change(function() {
    ckOrder();
});

$('input[type=radio][name=product_status]').change(function() {
    ckOrder();
});

function ckOrder(non){
    val             = $('input[type=radio][name=ckOrder]:checked').val();
    product_status  = $('[name=product_status]:checked').val();
    CustomerID      = $('#CustomerID').val();
    SellNo          = $('#SellNo').val();
    $('.vaddrow').empty();
    $('.table-add-product tbody').empty();

    if(product_status == 1){
        $('.vproduct_services').hide(300);
    }else{
        $('.vproduct_services').show(300);
    }

    if(val == 1) {
        $('.th-qty-s').text('Qty Order');
        $('.vorder').show(300);
        $('.vnonorder').hide(300);
        $('#Ongkir').attr('readonly', true);
        if(non != "non"){
            if(CustomerID){
                if(SellNo){
                    sell_detail(SellNo);
                }else{
                    all_sell_detail();
                }
            }
        }
    }else{
        $('.th-qty-s').text('Qty Stock');
        $('.vorder').hide(300);
        $('.vnonorder').show(300);
        $('#Ongkir').attr('readonly', false);
        $('#ckPPN').attr('disabled', false);
        $('.table-add-product tbody').empty();
        item = '<a href="javascript:void(0)" onclick="add_row_not_order()" class="link_add_row">+ '+language_app.lb_add_column+'</a>';
        $('.vaddrow').append(item);
        if(non != "non"){
            add_row_not_order();
        }
    }
    SumTotal('element');
}
// end checkbox sales order

// perhitungan qty price
function SumTotal(element){
    val         = $('input[type=radio][name=ckOrder]:checked').val();
    if(val == 1){
        data_product = get_total_price_product();
        $('#Ongkir').val(parseFloat(data_product[3]));
    }else{
        data_product = get_total_non_order();
    }
    total_price_product = data_product[1] - data_product[2];
    total_discount      = data_product[2];
    total_discount_p    = RptoPersent(data_product[1],total_discount);

    $('#DiscountRp').val(parseFloat(total_discount));
    $('#Discount').val(total_discount_p);
    $('#SubTotal').val(parseFloat(data_product[1]));

    if($('#ckPPN').is(":checked")) {
        PPN = $('#PPN').val();
        PPN = PersenttoRp(PPN,total_price_product);
    }else{
        PPN = 0;
    }

    deliverycost     = $("[name=Ongkir]").val();
    deliverycost     = deliverycost.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
    if(deliverycost == ''){
        deliverycost = 0;
    }

    Total = parseFloat(total_price_product) + parseFloat(PPN) + parseFloat(deliverycost);
    $('#TotalPPN').val(parseFloat(PPN.toFixed(2)));
    $('#Total').val(parseFloat(Total.toFixed(2)));
    run_function = 'SumTotal()';
    if(element){
        create_format_currency2();
    }
}
function get_total_price_product(){
    product_status  = $('[name=product_status]:checked').val();
    list_data       = $(".table-add-product tbody input");
    total           = 0;
    sub_total       = 0;
    discount        = 0;
    deliverycost    = 0;
    total_kolom     = 0;
    status_kolom    = true;

    $.each(list_data,function(i,v){
        if($(v).is(":checked")){
            total_kolom += 1;
            if(total_kolom>100){
                status_kolom = false;
                $(v).attr('checked', false);
            }else{
                val = $(v).val();

                conversion  = $('.vd'+val+' [name="product_konv[]"]').val();
                conversion  = removeduit(conversion);

                if(product_status == 1){
                    xqty = 1;
                    conversion = 1;
                }else{
                    xqty = $('.vd'+val+' [name="product_qty[]"]').val();
                    xqty = removeduit(xqty);
                }

                xqty = xqty * conversion;

                xprice = $('.vd'+val+' [name="product_price[]"]').val();
                xprice = removeduit(xprice);

                xdiscount = $('.vd'+val+' [name="product_discount[]"]').val();
                xdiscount = removeduit(xdiscount);

                xdelivery_cost = $('.vd'+val+' [name="product_delivery[]"]').val();
                xdelivery_cost = removeduit(xdelivery_cost);

                if($('#ckPPN').is(":checked")) {
                    PPN = $('#PPN').val();
                    xtax = removeduit(PPN);
                }else{
                    xtax = 0;
                }

                xsub_total = xqty * xprice;
                sub_total += xsub_total;
                xdiscount  = PersenttoRp(xsub_total,xdiscount);
                discount  += xdiscount;
                xsub_total = xsub_total - xdiscount;
                xtax       = PersenttoRp(xsub_total,xtax);
                xsub_total = xsub_total + xtax;
                total        += xsub_total;
                deliverycost += xdelivery_cost;
                $('.vd'+val+' [name="product_discountrp[]"]').val(xdiscount);
                $('.vd'+val+' [name="product_subtotal[]"]').val(xsub_total);
                $('.vd'+val+' [name="product_tax[]"]').val(xtax);
            }
        }
    });

    if(!status_kolom){
        swal('','Data input max 100','warning');
    }

    var data = [total,sub_total,discount,deliverycost];
    return data;
}

function get_total_non_order(){
    product_status  = $('[name=product_status]:checked').val();
    d = $("input[name='product_price[]']").length;
    total           = 0;
    discount_rp     = 0;
    before_total   = 0;
    for (i = 0; i < d; i++) { 
        code    = $('[name="product_id[]"]').eq(i).val();
        conversion  = $('[name="product_konv[]"]').eq(i).val();
        conversion  = removeduit(conversion);
        
        if(product_status == 1){
            qty = 1;
            conversion = 1;
        }else{
            i_qty   = $('[name="product_qty[]"]').eq(i);
            qty     = i_qty.val();
            i_qty.val(qty);
            qty     = removeduit(qty);
        }

        qty = qty * conversion;
        
        i_discount   = $('[name="product_discount[]"]').eq(i);
        discount     = i_discount.val();
        discount     = removeduit(discount);
        if(parseFloat(discount)>100){
            discount = 100;
        }
        i_discount.val(discount);

        val     = $('[name="product_price[]"]').eq(i).val();
        
        val     = removeduit(val);
        if(code != ''){
            sub_total   = qty * val;
            total_discount = PersenttoRp(sub_total,discount);

            if($('#ckPPN').is(":checked")) {
                PPN = $('#PPN').val();
                xtax = removeduit(PPN);
            }else{
                xtax = 0;
            }
            
            before_total    += sub_total;
            total           += sub_total - total_discount;
            discount_rp     += total_discount;

            t       = sub_total - total_discount;
            xtax    = PersenttoRp(t,xtax);
            t       = t + xtax;
            
            $('[name="product_discountrp[]"]').eq(i).val(parseFloat(total_discount));
            $('[name="product_tax[]"]').eq(i).val(parseFloat(xtax));
            $('[name="product_subtotal[]"]').eq(i).val(parseFloat(t));
        }
    }

    var data = [total,before_total,discount_rp];
    return data;
}
// end perhitungan qty price

// checkbox ppn
$('#ckPPN').change(function() {
    if($(this).is(":checked")) {
        $('#PPN').val(10);
    }else{
        $('#PPN').val(0);
    }
    val         = $('#SellNo').val();
    val2        = $('input[type=radio][name=ckOrder]:checked').val();
    customer    = $('#CustomerID').val();
    if(val2 == 1){
        if(!val && customer){
            all_sell_detail();
        }
    }
    SumTotal('element');
});
// end checkbox ppn

function default_tab(){
    $('.tab-step li').removeClass('active');
    $('.tab-step .vstep1').addClass('active');
    $('.vstep2, .vstep3').removeClass('disabled');
    $('.vstep2 a, .vstep3 a').attr('href', '#vstep').attr('data-toggle', 'tab');
    step(1);
}

function step(val){
    if(val == 1){
        $('.vdelivery').show(300);
        $('.vdaddress').hide(300);
    }else if(val == 2){
        $('.vdaddress').show(300);
        $('.vdelivery').hide(300);
    }
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
    $('.vdaddress .address').val('');
    $('.vdaddress .city').val('');
    $('.vdaddress .province').val('');
}
// end customer address


// delivery not order
function add_row_not_order(data){
    product_status  = $('[name=product_status]:checked').val();
    kolom = $('.table-add-product tbody').find('tr').length + 1;
    id_row += 1;
    btn_serial = "";
    btn_remove = "";
    
    detid           = '';
    productid       = '';
    code            = '';
    name            = '';
    qty             = '';
    p_qty           = '';
    stock_product   = '';
    unitid          = '';
    product_unit    = '';
    product_konv    = '';
    product_type    = '';
    product_price   = '';
    product_total   = '';
    product_discount= '';
    product_remark  = '';
    sub_total       = '';
    product_delivery= '';

    if(kolom>100){
        swal('','Data input max 100', 'warning');
        return '';
    }

    if(kolom > 1){
       btn_remove = '<i  onclick="delete_row(this)" class="icon fa-remove remove_row" aria-hidden="true" style="cursor:pointer;padding:5px;"></i>'; 
    }
    if(data){
        detid           = data.DeliveryDet;
        productid       = data.ProductID;
        code            = data.productCode;
        name            = data.productName;
        qty             = parseFloat(data.Qty);
        p_qty           = parseFloat(data.product_qty);
        stock_product   = parseFloat(data.productStock);
        unitid          = data.UnitID;
        product_unit    = data.unitName;
        product_konv    = data.Conversion;
        product_type    = data.Type;
        product_price   = parseFloat(data.Price);
        product_total   = parseFloat(data.TotalPrice);
        product_discount= parseFloat(data.Discount);
        product_remark  = data.Remark;
        sub_total       = '';
    }

    item2 = '<td><input type="text" value="'+stock_product+'" data-qty="active" class="p_qty disabled duit"></td>\
                <td><input type="text" placeholder="'+language_app.lb_qty_input+'" name="product_qty[]" value="'+qty+'" data-qty="active" data-class="p_min_qty" class="p_min_qty duit" onkeyup="SumTotal()" min="0"></td>\
                <td>\
                <input type="hidden" name="product_unitid[]" value="'+unitid+'" class="p_unitid">\
                <input type="hidden" value="'+product_unit+'" class="p_unit disabled">\
                <input type="hidden" name="product_type[]" value="'+product_type+'" data-class="p_type" class="p_type">\
                <select style="min-width:100px" class="p_unit2 width-100per" onchange="check_product_unit(this)"></select>\
                </td>';
    if(product_status == 1){
        item2 = '';
    }

    item = '<tr class="rowid_'+id_row+' rowdata" data-row="'+id_row+'" data-typenya="2" data-serial="active" data-detailsn="active">\
                <td>\
                    <input type="hidden" name="rowid[]" value="rowid_'+id_row+'">\
                    <input type="hidden" name="detid[]" value="'+detid+'">\
                    <div class="info-warning"></div>\
                </td>\
                <td class="remove_row" style="min-width:60px">\
                    <i class="icon fa-search remove_row" onclick="ckproduct_modal('+"'"+id_row+"','ar'"+')" style="cursor:pointer;padding:5px;"></i>\
                    '+btn_remove+'\
                </td>\
                <td><input type="text" value="'+code+'" class="autocomplete_product p_code product_modal disabled">\
                <input type="hidden" name="product_id[]" value="'+productid+'" class="p_id">\
                </td>\
                <td><input type="text" name="product_name[]" value="'+name+'" class="p_name disabled"></td>\
                '+item2+'\
                <td class="content-hide"><input type="text" name="product_konv[]" value="'+product_konv+'" class="p_conv disabled"></td>\
                <td>\
                    <input type="text" name="product_price[]" placeholder="'+language_app.lb_price_input+'" value="'+product_price+'" data-class="p_sellprice" class="p_sellprice duit" onkeyup="SumTotal()">\
                </td>\
                <td>\
                    <input type="text" name="product_discount[]" placeholder="'+language_app.lb_discount_input+'" value="'+product_discount+'" data-class="p_selldiscount" class="p_selldiscount" min="0" onkeyup="SumTotal(this)">\
                    <input type="text" class="disabled content-hide duit" name="product_discountrp[]" value="0" disabled="disabled">\
                </td>\
                <td><input type="text" class="disabled duit" name="product_tax[]" value="0" disabled="disabled"></td>\
                <td><input type="text" name="product_subtotal[]" value="'+product_total+'" data-class="p_sub_total" class="p_sub_total duit disabled"></td>\
                <td><input type="text" name="product_remark[]" placeholder="'+language_app.lb_remark_input+'" value="'+product_remark+'" data-class="p_remark" class="p_remark"></td>\
                <td>\
                    <span class="p_add_serial"></span>\
                   '+btn_remove+btn_serial+'\
                </td>\
            </tr>';
    $(".table-add-product tbody").append(item);
    $(".disabled").attr("disabled",true);
    moneyFormat();
}
function  delete_row(a) {
    $(a).closest('tr').remove();
    SumTotal('element');
}

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

// 20190806 MW
// pengecekan untuk unit
function check_product_unit(element){
    product_status = $('[name=product_status]:checked').val();

    if(product_status == 1){

    }else{
        tag_data = $(element).find(':selected').data();

        id          = tag_data.id;
        row         = tag_data.rowid;
        conversion  = tag_data.conversion;
        unit        = tag_data.name;
        sellingprice= tag_data.sellingprice;

        $(".rowid_"+row+" .p_unitid").val(id);
        $(".rowid_"+row+" .p_unit").val(unit);
        $(".rowid_"+row+" .p_conv").val(conversion);
        $(".rowid_"+row+" .p_sellprice").val(parseFloat(sellingprice));

        SumTotal();
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

function reset_column_product(){
    $('.table-add-product tbody').children( 'tr' ).remove();
    add_row_not_order();
    SumTotal('element');
}