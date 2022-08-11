var mobile          = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host            = window.location.origin+'/pipesys_qa/';
var url             = window.location.href;
var page_login      = host + "main/login";
var page_register   = host + "main/register";
var url_list        = host + "penerimaan/ajax_list/";
var url_edit        = host + "penerimaan/ajax_edit/";
var url_cancel      = host + "penerimaan/cancel/";
var url_simpan      = host + "penerimaan/save";
var url_simpan_remark = host + "penerimaan/save_remark";
var save_method; //for save method string
var table;
var Purchase_purchaseno,PStatus;

var id_row = 0;

//dashboard
var url_post        = host + "dashboard/dashboard/";
var period = "";
var chart_purchase_transaction,chart_purchase_return,chart_goodreceipt;
//end dashboard
$(window).load(function(){

    page_data   = $(".page-data").data();
    app         = page_data.app;
    ap          = page_data.ap;

    
});

$(document).ready(function() {
    data_page       = $(".data-page, .page-data").data();
    url_modul            = data_page.url_modul;
    modul                = data_page.modul;
    title_page           = data_page.title;
    
    if(data_page.id){
        statusid = data_page.statusid;
        statusid = statusid.split("-");
        if(statusid[0] != 1){
            if(statusid[1] == "purchase"){
                get_detail_purchase(data_page.id);
            }
        }
    }

    // purchase
    selected_item('#ul-purchase-order', st_period_type,'non');
    selected_item('#ul-purchase-transaction', st_period_type,'non');
    selected_item('#ul-purchase-return', st_period_type,'non');
    selected_item('#ul-purchase-open', st_period_type,'non');
    selected_item('#ul-purchase-overdude', st_period_type,'non');
    selected_item('#ul-purchase-payment', st_period_type,'non');

    // penerimaan
    selected_item('#ul-goodreceipt', st_period_type,'non');

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
    fProductType        = $('#form-filter [name=fProductType]').val();
    fBranch             = $('#form-filter [name=fBranch]').val();
    // fTypeStatus         = $('#form-filter [name=fTypeStatus]').val();

    data_post = {
        Search              : fSearch,
        StartDate           : fStartDate,
        EndDate             : fEndDate,
        Status              : fStatus,
        ProductType         : fProductType,
        Branch              : fBranch,
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
    load_data('purchaseheader');
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
    $(".btn-serial-v").hide();
    $('.vaction, .vprint').hide();
    $('.table-add-product tbody').children( 'tr' ).remove();
    $('.table-add-serial tbody').empty();
    // add_new_row();
    set_default_branch();
    ckOrder();
    // default_tab();
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
    $("#form input").attr("disabled",false);
    $('.info-warning').empty();
    $('.form-group, .input-group').removeClass('has-error'); // clear error class
    $('.input-error').removeClass('input-error');
    $('.help-block').empty();
    proses_save_button();

    var url;

    form = "#form";
    if(save_method == 'add') {
        url = url_simpan;
    } else if(save_method == "add_serial"){
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
                            item = '<i class="icon fa-exclamation-triangle" title="'+data.error_string[i]+'" style="cursor:pointer;padding:5px;"></i>';
                            if(val == 1){
                                $('.vd'+data.inputerror[i]+' .info-warning').append(item);
                            }else{
                                $('.'+data.inputerror[i]+' .info-warning').append(item);
                            }
                        }else{
                            $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                            $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                        }

                        if(tab == "penerimaan"){
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
function select_purchase(classnya){
    vendorid = $('#receipt_name').val();
    vendorid = vendorid.split('-');
    if(vendorid[0]){
        purchase_modal(vendorid[0],classnya,"receive");
    }else{
        swal('',language_app.lb_vendor_select2,'warning');
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
function reset_data_receive (){
    $('[name=po_no]').val('');
    $(".table-add-product tbody tr").remove();
}
// vendor

// view
function view(id,page){
    reset_button_action();
    if(page == "print"){
        open_modal_template(id,page);
    }else{
        view_print_data(id,page);
    }

    url = host + "penerimaan-view/"+id+"?page=print";
    action_print_button(url);
}

function view_print_data(id,page){
    page2 = '';
    if(page == "print"){
        page = "print";
    }else{
        page2 = page;
        page = "Penerimaan";
    }
    $('#modal-print').modal('show');
    $('#modal-print .modal-title').text(title_page+" "+language_app.btn_detail);
    url = host + "penerimaan-view/"+id+"?page="+page;
    $(".btn-serial-v").show();
    $(".btn-serial-v, .vaction, .vprint").show();
    
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

    $("#view-print").load(url+"&page2="+page2,data_post,function(){
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

function view_serial(id){
    url = host + "penerimaan/ajax_view_serial/" + id;
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
            $('#modal-add-serial .modal-title').text('View Serial Number'); // Set Title to Bootstrap modal title
            
            // autocomplete_serialnumber(".autocomplete_serialnumber");
        },
        error: function (jqXHR, textStatus, errorThrown){
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);
        }
    });
}
// end view

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
    $(".btn-serial-v").show();
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
    // default_tab();
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            list         = data.list;
            detail       = data.detail;
            data_sell    = data.data_sell;

            $('[name=ReceiveNo]').val(list.receipt_no);
            $('[name=receipt_no]').val(list.receipt_no);
            $('[name=receipt_date]').val(list.receipt_date);
            $('[name=receipt_name]').val(list.VendorID+"-"+list.receipt_name);
            $('[name=VendorName]').val(list.receipt_name);
            $('[name=SalesID]').val(list.SalesID+"-"+list.salesName);
            $('[name=SalesName]').val(list.salesName);
            $('[name=po_no]').val(list.po_no);
            $('[name=sj_no]').val(list.sj_no);
            $('[name=Ongkir]').val(list.receipt_cost);
            $('[name=product_purchasedet]').val(list.Purchase_purchasedet);

            $('[name=receipt_remark]').val(list.receipt_remark);
            // $('[name=delCity]').val(list.City);
            // $('[name=delProvince]').val(list.Province);
            $('[name=Term]').val(list.Term);
            count_term('term');

            if(list.Tax == 1){
                $('#ckPPN').prop('checked', true);
                $('#PPN').val(10);
            }else{
                $('#ckPPN').prop('checked', false);
                $('#PPN').val(0);
            }

            if(list.po_no){
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
                temp_purchasedet = [];
                temp_purchaseno  = [];
                $.each(detail, function(i, v) {
                    temp_purchasedet.push(v.Purchase_purchasedet);
                    setitem(v,"penerimaan");
                    if(jQuery.inArray(v.PurchaseNo,temp_purchaseno) == -1){
                        temp_purchaseno.push(v.PurchaseNo);
                    }
                });
                $.each(data_sell, function(i, v) {
                    setitem(v,"penerimaan");
                });
                SumTotal();
                $('[name=temp_purchaseno]').val(temp_purchaseno);
                $('[name=temp_purchasedet]').val(temp_purchasedet);
    
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
    product_status  = $('[name=product_status]:checked').val();
    tbl = '.table-add-product';
    if(page == "penerimaan"){
        checkbox        = 'checked';
        receivedet      = v.receipt_det;
        purchaseno      = v.Purchase_purchaseno;
        PurchaseDet     = v.Purchase_purchasedet;

        productid           = v.productid;
        code                = v.product_code;
        name                = v.product_name;
        qty                 = parseFloat(v.purchaseQty);
        rec_qty             = parseFloat(v.receive_qty);
        unitid              = v.unitid;
        product_unit        = v.unit_name;
        product_konv        = v.receipt_konv;
        product_type        = v.product_type,
        product_price       = parseFloat(v.receipt_price);
        product_total       = parseFloat(v.TotalPrice);
        product_discount    = parseFloat(v.receipt_discount);
        product_tax         = parseFloat(v.Tax);
        product_discountrp  = parseFloat(v.DiscountValue);
        product_remark      = v.receipt_remark;
        product_delivery    = parseFloat(v.DeliveryCost);
        
    }
    else if(page == "add_sell"){
        checkbox            = '';
        receivedet          = '';
        purchaseno          = v.Purchase_purchaseno;
        PurchaseDet         = v.Purchase_purchasedet;

        productid           = v.productid;
        code                = v.product_code;
        name                = v.product_name;
        qty                 = parseFloat(v.Qty);
        rec_qty             = qty - parseFloat(v.product_stock);
        unitid              = v.unitid;
        product_unit        = v.unit_name;
        product_konv        = v.receipt_konv;
        product_type        = v.product_type,
        product_price       = parseFloat(v.receipt_price);
        product_total       = parseFloat(v.TotalPrice);
        product_discount    = parseFloat(v.receipt_discount);
        product_tax         = parseFloat(v.Tax);
        product_discountrp  = parseFloat(v.DiscountValue);
        product_remark      = v.receipt_remark;
         product_delivery   = parseFloat(v.DeliveryCost);
    }
    else{
        checkbox            = '';
        receivedet          = '';
        purchaseno          = v.Purchase_purchaseno;
        PurchaseDet         = v.Purchase_purchasedet;

        productid           = v.productid;
        code                = v.product_code;
        name                = v.product_name;
        qty                 = parseFloat(v.purchaseQty);
        rec_qty             = qty - parseFloat(v.receive_qty);
        unitid              = v.unitid;
        product_unit        = v.unit_name;
        product_konv        = v.product_konv;
        product_type        = v.product_type,
        product_price       = parseFloat(v.receipt_price);
        product_total       = 0;
        product_discount    = parseFloat(v.receipt_discount);
        product_tax         = parseFloat(v.Tax);
        product_discountrp  = 0;
        product_remark      = '';
        product_delivery    = parseFloat(v.DeliveryCost);
    }

    tag_data    =' data-code="'+code+'" ';
    
    // checkbox
    item  = '<tr class="vd'+PurchaseDet+'">';
    item += '<td><div class="info-warning"></div></td>';
    item += '<td>\
            <input class="cekbox" type="checkbox" onclick="SumTotal()" name="check[]" '+checkbox+' value="'+PurchaseDet+'">\
            <input class="disabled" type="hidden" name="detid[]" value="'+receivedet+'">\
            <input class="disabled" type="hidden" name="product_purchasedet[]" value="'+PurchaseDet+'" >\
            <input class="disabled" type="hidden" name="product_purchaseno[]" value="'+purchaseno+'" >\
            <input class="disabled" type="hidden" name="product_id[]" value="'+productid+'" >\
            </td>';

    // code
    // item += '<td>\
    //         <input class="disabled" type="text" value="'+purchaseno+'" >\
    //         </td>';

    // code
    item += '<td>\
            <input class="disabled" type="text" value="'+code+'" >\
            </td>';

    // name
    item += '<td>\
            <input class="disabled" type="text" value="'+name+'" >\
            </td>';

    if(product_status == 0){
        // qty selling
        item += '<td>\
                <input class="disabled" type="text" value="'+qty+'" >\
                </td>';

        // qty delivery
        item += '<td>\
                <input placeholder="'+language_app.lb_qty_input+'" type="number" onkeyup="SumTotal()" onchange="SumTotal()" onkeypress="return (event.charCode == 8 || event.charCode == 0 || event.charCode == 13) ? null : event.charCode >= 48 && event.charCode <= 57" name="product_qty[]" value="'+rec_qty+'" min="0" max="'+qty+'">\
                </td>';

        // unit
        item += '<td>\
                <input class="disabled" type="text" value="'+product_unit+'" >\
                <input class="disabled" type="hidden" name="product_unitid[]" value="'+unitid+'" >\
                </td>';
    }

    // conversion
    item += '<td class="content-hide">\
            <input class="disabled" type="text" name="product_konv[]" value="'+product_konv+'" >\
            </td>';

    // price
    item += '<td>\
            <input class="disabled duit" type="text" name="product_price[]" readonly value="'+product_price+'" >\
            </td>';

    // discount
    item += '<td>\
            <input type="text" class="disabled" name="product_discount[]" value="'+product_discount+'">\
            <input type="text" class="disabled content-hide duit" name="DiscountRp[]" value="'+0+'">\
            </td>';

    // // Tax
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

    item += '</tr>';

    $(tbl+' tbody').append(item);
    $(".disabled").attr("disabled",true);
}
// end edit

function get_detail_purchase(id){
    url = host + "purchase_order/ajax_edit/"+id;

    data_post = {
        page : "penerimaan",
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
            if(list){
                if(list.DeliveryStatus == 0){
                    tambah();
                    $('#VendorName').val(list.vendorName);
                    $("#receipt_name").val(list.VendorID+"-"+list.vendorName);
                    if(list.SalesID){
                        $('[name=SalesID]').val(list.SalesID+"-"+list.salesName);
                        $('[name=SalesName]').val(list.salesName);
                    }
                    if(list.BranchID){
                        $('[name=BranchID]').val(list.BranchID+"-"+list.branchName);
                        $('#BranchName').val(list.branchName);
                        $('#BranchName2').val(list.branchName);
                    }
                    $('#po_no').val(list.PurchaseNo);
                    if(list.Tax == 1){
                        $('#ckPPN').attr('checked', true);
                    }else{
                        $('#ckPPN').attr('checked', false);
                    }
                    if(list.ProductType == 1){
                        $("#services").prop("checked",true);
                    }else{
                        $("#item").prop("checked",true);
                    }
                    $('#form [name=Ongkir]').val(parseFloat(list.DeliveryCost));
                    ckOrder('non');
                    purchase_det(list.PurchaseNo);
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);
        }
    });
}

function add_new_row2(page = "",v = "") {
    receipt_det         = v.receipt_det;
    productid           = v.productid;
    product_code        = v.product_code;
    product_name        = v.product_name;
    product_qty         = v.receive_qty;
    unitid              = v.unitid;
    unit_name           = v.unit_name;
    product_konv        = v.receipt_konv;
    product_price       = v.receipt_price;
    product_type        = v.product_type;
    product_discount    = v.receipt_discount;
    product_subtotal    = v.TotalPrice;
    product_remark      = v.receipt_remark;
    product_tax         = v.tax;
    total_price         += eval(product_subtotal);
    purchase_qty        = v.purchase_qty;
    btn_serial          = "";
    if(product_type == "general" || product_type == "serial"){
        page        = "'penerimaan'";
        receipt_det = "'"+receipt_det+"'";
        btn_serial  = '<a  onclick="add_serial('+page+','+receipt_det+')" class="btn-serial-v" aria-hidden="true" style="cursor:pointer;padding:5px;">Add Serial</a>';
    }
    item    = '<tr>\
                <td>'+''+'</td>\
                <td>'+''+'</td>\
                <td>'+product_code+'</td>\
                <td>'+product_name+'</td>\
                <td>'+purchase_qty+'</td>\
                <td>'+product_qty+'</td>\
                <td>'+unit_name+'</td>\
                <td>'+product_konv+'</td>\
                <td>'+product_price+'</td>\
                <td>'+product_discount+'</td>\
                <td>'+product_subtotal+'</td>\
                <td>'+product_remark+'</td>\
                <td>'+btn_serial+'</td>\
            </tr>';
    $(".table-add-product tbody").append(item);
}

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
                swal("Canceled", "", "error");   
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
    val           = $('input[type=radio][name=ckOrder]:checked').val();
    product_status= $('[name=product_status]:checked').val();
    receipt_name  = $('#receipt_name').val();
    PurchaseNo    = $('#po_no').val();
    $('.vaddrow').empty();
    $('.table-add-product tbody').empty();

    if(product_status == 1){
        $('.vproduct_services').hide(300);
    }else{
        $('.vproduct_services').show(300);
    }

    if(val == 1) {
        $('.th-qty-s').text(language_app.lb_qty_order);
        $('.vorder').show(300);
        $('.vnonorder').hide(300);
        $('#ckPPN').attr('disabled', true);
        if(non != "non"){
            if(receipt_name){
                if(PurchaseNo){
                    purchase_det(PurchaseNo);
                }
            }
        }
    }else{
        $('.th-qty-s').text('Qty Stock');
        $('.vorder').hide(300);
        $('.vnonorder').show(300);
        $('#ckPPN').attr('disabled', false);
        $('.table-add-product tbody').empty();
        item = '<a href="javascript:void(0)" onclick="add_row_not_order()" class="link_add_row">+ '+language_app.lb_add_column+'</a>';
        $('.vaddrow').append(item);
        if(non != "non"){
            add_row_not_order();
        }
        if(product_status != 1){
            $('.v_branch').hide();
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
    }else{
        data_product = get_total_non_order();
    }
    total_price_product = data_product[1] - data_product[2];
    total_discount      = data_product[2];
    total_discount_p    = RptoPersent(data_product[1],total_discount);

    $('#DiscountRp').val(parseFloat(total_discount));
    $('#Discount').val(checkNan(total_discount_p));
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
    $('#TotalPPN').val(parseFloat(PPN.toFixed(amountdecimal)));
    $('#Total').val(parseFloat(Total.toFixed(amountdecimal)));

    run_function = 'SumTotal()';
    if(element){
        create_format_currency2();
    }
    // moneyFormat('SumTotal()');  
} 
function get_total_price_product(){
    list_data       = $(".table-add-product tbody input");
    product_status  = $('[name=product_status]:checked').val();
    total           = 0;
    sub_total       = 0;
    discount        = 0;
    deliverycost    = 0;
    $.each(list_data,function(i,v){
        if($(v).is(":checked")){
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
            // xdelivery_cost = removeduit(xdelivery_cost);

            xsub_total = $('.vd'+val+' [name="product_subtotal[]"]').val();
            xsub_total = removeduit(xsub_total);

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
            total         += xsub_total;
            deliverycost  += xdelivery_cost;
            $('.vd'+val+' [name="product_discountrp[]"]').val(xdiscount);
            $('.vd'+val+' [name="product_subtotal[]"]').val(xsub_total);
            $('.vd'+val+' [name="product_tax[]"]').val(xtax);
        }
    });

    var data = [total,sub_total,discount];
    return data;
}

function get_total_non_order(){
    product_status  = $('[name=product_status]:checked').val();
    d = $("input[name='product_price[]']").length;
    total           = 0;
    discount_rp     = 0;
    before_total   = 0;
    for (i = 0; i < d; i++) { 
        code        = $('[name="product_id[]"]').eq(i).val();
        conversion  = $('[name="product_konv[]"]').eq(i).val();
        conversion  = removeduit(conversion);

        rowid       = $('[name="rowid[]"]').eq(i).val();
        product_type= $('[name="product_type[]"]').eq(i).val();
        serial_auto = $('.p_serial_auto').eq(i).val();
        
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

        if(product_type == 2 && serial_auto != 1){
            // check_add_serial_number(rowid,qty);
        }

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
    SumTotal('element');
});
// end checkbox ppn

// function default_tab(){
//     $('.tab-step li').removeClass('active');
//     $('.tab-step .vstep1').addClass('active');
//     $('.vstep2, .vstep3').removeClass('disabled');
//     $('.vstep2 a, .vstep3 a').attr('href', '#vstep').attr('data-toggle', 'tab');
//     step(1);
// }

// function step(val){
//     if(val == 1){
//         $('.vdelivery').show(300);
//         $('.vdaddress').hide(300);
//     }else if(val == 2){
//         $('.vdaddress').show(300);
//         $('.vdelivery').hide(300);
//     }
// }

// customer address
// function select_address_vendor(classnya){
//     vendorid = $('#CustomerID').val();
//     vendorid = vendorid.split('-');
//     if(vendorid[0]){
//         address_vendor(vendorid[0],classnya);
//     }else{
//         swal('','please select customer','warning');
//     }
// }
// function reset_address_vendor(){
//     $('.vdaddress .address').val('');
//     $('.vdaddress .city').val('');
//     $('.vdaddress .province').val('');
// }
// end customer address


// delivery not order

function add_row_not_order(data){
    kolom = $('.table-add-product tbody').find('tr').length + 1;
    product_status  = $('[name=product_status]:checked').val();
    id_row += 1;
    btn_serial = "";
    btn_remove = "";

    if(kolom>100){
        swal('','Data item max 100', 'warning');
        return '';
    }
    
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
    serial_auto     = '';

    if(kolom > 1){
       btn_remove = '<i  onclick="delete_row(this)" class="icon fa-remove remove_row" aria-hidden="true" style="cursor:pointer;padding:5px;"></i>'; 
    }
    if(data){
        detid           = data.receipt_det; 
        productid       = data.ProductID;
        code            = data.product_code;
        name            = data.product_name;
        qty             = parseFloat(data.receive_qty);
        p_qty           = parseFloat(data.product_stock);
        stock_product   = parseFloat(data.productStock);
        unitid          = data.unitid;
        product_unit    = data.unit_name;
        product_konv    = data.receipt_konv;
        product_type    = data.Type;
        product_price   = parseFloat(data.receipt_price);
        product_total   = parseFloat(data.TotalPrice);
        product_discount    = parseFloat(data.receipt_discount);
        product_discountrp  = parseFloat(data.DiscountValue);
        product_remark      = data.receipt_remark;
        sub_total           = '';
    }

    item2 = '<td><input type="text" value="'+stock_product+'" data-qty="active" class="p_qty duit disabled"></td>\
                <td><input type="text" placeholder="'+language_app.lb_qty_input+'" name="product_qty[]" value="'+qty+'" data-qty="active" data-class="p_min_qty" class="p_min_qty duit" onkeyup="SumTotal()" min="0"></td>\
                <td>\
                <input type="hidden" name="product_unitid[]" value="'+unitid+'" class="p_unitid">\
                <input type="hidden" value="'+product_unit+'" class="p_unit disabled">\
                <input type="hidden" name="product_type[]" value="'+product_type+'" data-class="p_type" class="p_type">\
                <input type="hidden" value="'+serial_auto+'" data-class="p_serial_auto" class="p_serial_auto">\
                <select style="min-width:100px" class="p_unit2 width-100per" onchange="check_product_unit(this)"></select>\
                </td>';
    if(product_status == 1){
        item2 = '';
    }
    // onkeyup="keyup_product('+id_row+',this)"
    item = '<tr class="rowid_'+id_row+' rowdata" data-row="'+id_row+'" data-classnya="rowid_'+id_row+'" data-typenya="2" data-serial="active">\
                <td>\
                    <input type="hidden" name="rowid[]" value="rowid_'+id_row+'">\
                    <input type="hidden" name="detid[]" value="'+detid+'">\
                    <div class="info-warning"></div>\
                </td>\
                <td class="remove_row">\
                    <i class="icon fa-search remove_row" onclick="product_modal('+id_row+')" style="cursor:pointer;padding:5px;"></i>\
                     '+btn_remove+'\
                </td>\
                <td><input type="text" value="'+code+'" class="autocomplete_product p_code product_modal disabled">\
                <input type="hidden" name="product_id[]" value="'+productid+'" class="p_id">\
                </td>\
                <td><input type="text" name="product_name[]" value="'+name+'" class="p_name disabled"></td>\
                '+item2+'\
                <td class="content-hide"><input type="text" name="product_konv[]" value="'+product_konv+'" class="p_conv disabled"></td>\
                <td>\
                    <input type="text" name="product_price[]" placeholder="'+language_app.lb_price_input+'" value="'+product_price+'" data-class="p_purchaseprice" class="p_purchaseprice duit" onkeyup="SumTotal()">\
                </td>\
                <td>\
                    <input type="text" name="product_discount[]" placeholder="'+language_app.lb_discount_input+'" value="'+product_discount+'" data-class="p_selldiscount" class="p_selldiscount" min="0" onkeyup="SumTotal(this)">\
                    <input type="text" class="disabled content-hide duit" name="product_discountrp[]" value="0" disabled="disabled">\
                </td>\
                <td><input type="text" class="disabled duit" name="product_tax[]" value="0" disabled="disabled"></td>\
                <td><input type="text" name="product_subtotal[]" value="'+product_total+'" data-class="p_sub_total" class="p_sub_total duit disabled"></td>\
                <td><input type="text" name="product_remark[]" placeholder="'+language_app.lb_remark_input+'" value="'+product_remark+'" data-class="p_remark" class="p_remark"></td>\
                <td style="min-width:110px">\
                    <span class="p_add_serial"></span>\
                   '+btn_remove+btn_serial+'\
                </td>\
            </tr>';
    $(".table-add-product tbody").append(item);
    $(".disabled").attr("disabled",true);
    moneyFormat('SumTotal()');
}
function  delete_row(a) {
    $(a).closest('tr').remove();
    SumTotal();
    create_format_currency2();
}

// Due Date and term
$('[name=Term]').on('keyup paste change',function(){
    count_term('term');
});

$('[name=DueDate]').on('keyup paste change',function(){
    count_term('due_date');
});

$('[name=receipt_date]').on('keyup paste change', function(){
    count_term('term');
    delivery_date = $('[name=DeliveryDate]');
    delivery_date.val($("#receipt_date").val());
    $('.p_delivery').val($("#receipt_date").val());

})

function count_term(page){
    term        = $('[name=Term]');
    due_date    = $('[name=DueDate]');
    date        = new Date($("#receipt_date").val());
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
        purchaseprice= tag_data.purchaseprice;

        $(".rowid_"+row+" .p_unitid").val(id);
        $(".rowid_"+row+" .p_unit").val(unit);
        $(".rowid_"+row+" .p_conv").val(conversion);
        $(".rowid_"+row+" .p_purchaseprice").val(parseFloat(purchaseprice));

        SumTotal();
    }
}

//dashboard
function create_chart(){
    var ctx = document.getElementById("goodreceipt");
    chart_goodreceipt = new Chart(ctx,{});

    var ctx = document.getElementById("purchase_return");
    chart_purchase_return = new Chart(ctx,{});

    var ctx = document.getElementById("purchase_transaction");
    chart_purchase_transaction = new Chart(ctx,{});

}
function load_data(page = "")
{
    url_post    =  host+"dashboard/dashboard";
    Check       = $("[name=Check]:checked").val();
    StartDate   = $("[name=fStartDate]").val();
    EndDate     = $("[name=fEndDate]").val();

    // untuk data diagram

    purchase_transactionx   = $('#ul-purchase-transaction .li-active').data();
    purchase_returnx        = $('#ul-purchase-return .li-active').data();

    goodreceiptx            = $('#ul-goodreceipt .li-active').data();

    data_post   = {
        Check           : Check,
        StartDate       : StartDate,
        EndDate         : EndDate,
        goodreceipt     : goodreceiptx.type,
        purchase_transaction : purchase_transactionx.type,
        purchase_return      : purchase_returnx.type,
        page                 : page,
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
            
            if(page == "purchase_return"){
                purchase_return(data.purchase_return);
            }else if(page == "purchase_transaction"){
                purchase_transaction(data.purchase_transaction);
            }else if(page == "goodreceipt"){
                goodreceipt(data.goodreceipt);
            }else{
                set_data_purchase(data);
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
function set_data_purchase(data){
    goodreceipt(data.goodreceipt);
    purchase_return(data.purchase_return);
    purchase_transaction(data.purchase_transaction);
}

function goodreceipt(data){
    var ctx = document.getElementById("goodreceipt");
    chart_goodreceipt.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    total               = 0;

    if(data.length>0){
        $.each(data, function(k,v){
            if($.inArray(v.date, labels) == -1){
                labels.push(v.date);
            }
            total += parseFloat(v.total);
            value.push(parseFloat(v.total));
        });

        color = get_backgroundColor(0);
        d = {
            label           : "data goodreceipt transaction",
            data            : value,
            backgroundColor : color[0],
            borderColor     : color[1],
            borderWidth     : 1,
        };
        datasets.push(d);
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

    chart_goodreceipt = new Chart(ctx, {
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
                position : "top",
            }
        },
    
    });

    $('.total_goodreceipt').text(total);
}

function purchase_transaction(data){
    var ctx = document.getElementById("purchase_transaction");
    chart_purchase_transaction.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    total               = 0;

    if(data.length>0){
        $.each(data, function(k,v){
            if($.inArray(v.date, labels) == -1){
                labels.push(v.date);
            }
            total += parseFloat(v.total);
            value.push(parseFloat(v.total));
        });

        color = get_backgroundColor(0);
        d = {
            label           : "data purchase transaction",
            data            : value,
            backgroundColor : color[0],
            borderColor     : color[1],
            borderWidth     : 1,
        };
        datasets.push(d);
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

    chart_purchase_transaction = new Chart(ctx, {
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
                position : "top",
            }
        },
    
    });

    $('.total_purchase_transaction').text(total);
}

function purchase_return(data){
    var ctx = document.getElementById("purchase_return");
    chart_purchase_return.destroy();
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

    chart_purchase_return = new Chart(ctx, {
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
                position : "top",
            }
        },
    
    });

    $('.total_purchase_return').text(total);
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
