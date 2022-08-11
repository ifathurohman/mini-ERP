var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host 			= window.location.origin+'/pipesys_qa/';
var url 			= window.location.href;
var page_login 		= host + "main/login";
var page_register 	= host + "main/register";
var url_list 		= host + "selling/ajax_list/";
var url_edit        = host + "selling/ajax_edit/";
var url_cancel 		= host + "selling/cancel/";
var url_simpan 		= host + "selling/save";
var url_simpan_remark = host + "selling/save_remark";
var save_method; //for save method string
var table;
var date_now;

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
    date_now    = data_page.date;
    title_page  = data_page.title;

    fSearch             = $('#form-filter [name=fSearch]').val();
    fStartDate          = $('#form-filter [name=fStartDate]').val();
    fEndDate            = $('#form-filter [name=fEndDate]').val();
    fStatus             = $('#form-filter [name=fStatus]').val();
    fTransactionStatus  = $('#form-filter [name=fTransactionStatus]').val();
    fProductType        = $('#form-filter [name=fProductType]').val();
    fBranch             = $('#form-filter [name=fBranch]').val();

    data_post = {
        Search              : fSearch,
        StartDate           : fStartDate,
        EndDate             : fEndDate,
        Status              : fStatus,
        TransactionStatus   : fTransactionStatus,
        ProductType         : fProductType,
        Branch              : fBranch,
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
    $('.link_add_row').show();
    $('.vaction, .vprint').hide();
    $('.table-add-serial tbody').empty();
    $('#ckPPN').attr('checked', true);
    $('#PPN').val(10);
    $('.table-add-product tbody').children( 'tr' ).remove();
    set_default_branch();
    check_product_status(0);
    default_tab();
    reset_file_upload();
    // $('.vstep2, .vstep3').addClass('disabled');
    // $('.vstep2 a, .vstep3 a').attr('href', 'javascript:void(0)').removeAttr('data-toggle');
    $('.modal-title').text(language_app.lb_add_new+' '+title_page); // Set Title to Bootstrap modal title
    $('[name="crud"]').val("insert");
    $('.disabled').attr('disabled',true);
    reset_button_action();
    show_upload_file();
    hide_button_cancel2();
    $('.btn-close').show();
    //-----------------------------------------------------------------------------
}

function add_new_row(data) {
    product_status = $('[name=product_status]:checked').val();
    if(product_status == 1){
        add_new_services();
        return "";
    }
    kolom = $('.table-add-product tbody').find('tr').length + 1;

    if(kolom>100){
        swal('','Data item max 100', 'warning');
        return;
    }

    id_row += 1;
    btn_serial = "";
    btn_remove = "";
    
    selldet         = '';
    productid       = '';
    code            = '';
    name            = '';
    qty             = '';
    p_qty           = '';
    stock_product   = 0;
    unitid          = '';
    product_unit    = '';
    product_konv    = '';
    product_type    = '';
    product_price   = '';
    product_total   = '';
    product_discount= '';
    product_remark  = '';
    sub_total       = '';
    serial_auto     = '';
    product_delivery= date_now;

    if(kolom > 1){
       btn_remove = '<i  onclick="delete_row(this)" class="icon fa-remove remove_row" aria-hidden="true" style="cursor:pointer;padding:5px;"></i>'; 
    }
    if(data){
        selldet         = data.SellDet;
        productid       = data.ProductID;
        code            = data.product_code;
        name            = data.product_name;
        qty             = parseFloat(data.Qty);
        p_qty           = parseFloat(data.product_qty);
        stock_product   = parseFloat(data.stock_product);
        unitid          = data.UnitID;
        product_unit    = data.unit_name;
        product_konv    = data.Conversion;
        product_type    = data.Type;
        product_price   = parseFloat(data.Price);
        product_total   = parseFloat(data.TotalPrice);
        product_discount= parseFloat(data.Discount);
        product_remark  = data.Remark;
        sub_total       = '';
        if(data.product_delivery){
            product_delivery= data.product_delivery;
        }
    }
    item = '<tr class="rowid_'+id_row+' rowdata" data-row="'+id_row+'" data-selling="active" data-typenya="1" data-serial="active" data-detailsn="active">\
                <td>\
                    <input type="hidden" name="rowid[]" value="rowid_'+id_row+'">\
                    <input type="hidden" name="selldet[]" value="'+selldet+'">\
                    <div class="info-warning"></div>\
                </td>\
                <td class="remove_row" style="min-width:60px;">\
                    <i class="icon fa-search remove_row" onclick="ckproduct_modal('+"'"+id_row+"','ar'"+')" style="cursor:pointer;padding:5px;"></i>\
                    '+btn_remove+'\
                </td>\
                <td><input type="text" value="'+code+'" class="p_code product_modal disabled">\
                <input type="hidden" name="productid[]" value="'+productid+'" class="p_id">\
                </td>\
                <td><input type="text" value="'+name+'" class="p_name disabled"></td>\
                <td><input type="text" value="'+stock_product+'" class="disabled"></td>\
                <td><input type="text" name="product_qty[]" value="'+qty+'" data-qty="active" data-class="p_min_qty" class="p_min_qty duit" onkeyup="SumTotal()" min="0"></td>\
                <td>\
                <input type="hidden" name="product_unitid[]" value="'+unitid+'" class="p_unitid">\
                <input type="hidden" value="'+product_unit+'" class="p_unit disabled">\
                <select style="min-width:100px" class="p_unit2 width-100per" onchange="check_product_unit(this)"></select>\
                </td>\
                <td class="content-hide"><input type="text" name="product_konv[]" value="'+product_konv+'" class="p_conv disabled"></td>\
                <td>\
                    <input type="hidden" name="product_type[]" value="'+product_type+'" data-class="p_type" class="p_type">\
                    <input type="hidden" value="'+serial_auto+'" data-class="p_serial_auto" class="p_serial_auto">\
                    <input type="text" name="product_price[]" value="'+product_price+'" data-class="p_sellprice" class="p_sellprice duit" onkeyup="SumTotal()">\
                    <input type="hidden" name="product_total[]" value="'+product_total+'" class="duit content-hide" readonly>\
                </td>\
                <td><input type="text" name="product_discount[]" value="'+product_discount+'" data-class="p_selldiscount" class="p_selldiscount" onkeyup="SumTotal(this)"></td>\
                <td><input type="text" value="'+product_total+'" data-class="p_sub_total" class="p_sub_total duit disabled"></td>\
                <td><input type="text" name="product_remark[]" value="'+product_remark+'" data-class="p_remark" class="p_remark"></td>\
                <td><input type="text" name="product_delivery[]" value="'+product_delivery+'" class="p_delivery date"></td>\
                <td style="min-width:120px">\
                    <span class="p_add_serial"></span>\
                   '+btn_remove+btn_serial+'\
                </td>\
            </tr>';
    $(".table-add-product tbody").append(item);
    $(".disabled").attr("disabled",true);
    $(".p_sellprice").data("min","tes");
    moneyFormat();
    date();
    SumTotal();
}
function  delete_row(a) {
    product_status = $('[name=product_status]:checked').val();

    $(a).closest('tr').remove();
    if(product_status == 1){
        SumTotalServices();
    }else{
        SumTotal();
    }
    create_format_currency2();
}

function add_new_row2(page = "",v = "") {

    selldet             = v.SellDet;
    productid           = v.productid;
    product_code        = v.product_code;
    product_name        = v.product_name;
    product_qty         = v.Qty;
    p_qty               = parseFloat(v.product_qty);
    stock_product       = parseFloat(v.DeliveryQty);
    unitid              = v.UnitID;
    unit_name           = v.unit_name;
    product_konv        = v.Conversion;
    product_price       = v.Price;
    product_total       = parseFloat(v.TotalPrice);
    product_delivery    = v.product_delivery;
    remark              = v.Remark;
    discount            = v.Discount;   
    btn_serial          = "";
    if(v.Type == 2 && v.ProductType == 0){
        page        = "'selling'";
        selldet = "'"+selldet+"'";
        btn_serial  = '<a  onclick="view_serial_number('+"'selling','"+v.SellNo+"',"+selldet+""+')" class="btn-serial-v" aria-hidden="true" style="cursor:pointer;padding:5px;">View Serial</a>';
    }
    item    = '<tr>\
                <td>'+'</td>\
                <td>'+'</td>\
                <td>'+product_code+'</td>\
                <td>'+product_name+'</td>\
                <td class="vproduct-services">'+v.DeliveryQty_txt+'</td>\
                <td class="vproduct-services">'+v.Qty_txt+'</td>\
                <td class="vproduct-services">'+unit_name+'</td>\
                <td class="content-hide">'+product_konv+'</td>\
                <td>'+v.Price_txt+'</td>\
                <td>'+v.Discount_txt+'</td>\
                <td>'+v.TotalPrice_txt+'</td>\
                <td>'+remark+'</td>\
                <td>'+product_delivery+'</td>\
                <td>'+btn_serial+'</td>\
            </tr>';
    $(".table-add-product tbody").append(item);
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
                    swal('',data.message,'success');
                    $('#modal').modal("hide");
                    reload_table();
                    if(ck_count_save_file()>0){
                        upload_attachment_file(data.ID);
                    }
                }
                else
                {
                    for (var i = 0; i < data.inputerror.length; i++)
                    {
                        list    = data.list[i];
                        tab     = data.tab[i];
                        if(list == 'list'){
                            item = '<i class="icon fa-exclamation-triangle" title="'+data.message+'" style="cursor:pointer;padding:5px;"></i>';
                            $(data.inputerror[i]+' .info-warning').append(item);
                        }else{
                            $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                            $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                        }

                        if(tab == "sell"){
                            $('.vstep1').addClass('input-error');
                        }else if(tab == "delivery"){
                            $('.vstep2').addClass('input-error');
                        }
                        else if(tab == "invoice"){
                            $('.vstep3').addClass('input-error');
                        }
                    }
                    if(data.negative == "warning"){
                        if(data.message){
                            save_warning(data);
                        }
                    }else if(data.negative == "block"){
                        if(data.message){
                            swal('',data.message,'error');
                        }
                    }else{
                        if(data.message){
                            swal('',data.message,'warning');
                        }else{
                            swal('',language_app.lb_incomplete_form, 'warning');
                        }
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

function save_warning(data){
    swal({   
        title: data.message,   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: language_app.btn_save,   
        cancelButtonText: language_app.btn_cancel,   
        closeOnConfirm: false,   
        closeOnCancel: false }, 
        function(isConfirm){   
            if (isConfirm) { 
                save("warning");
            } 
            else {
                swal(language_app.lb_canceled, "", "error");   
            } 
    });
}

function SumTotal(element){
    data_product = get_total_price_product();
    total_price_product = data_product[0];
    total_discount      = data_product[1];
    total_discount_p    = RptoPersent(data_product[2],total_discount);

    $('#DiscountRp').val(parseFloat(total_discount));
    $('#Discount').val(total_discount_p);
    $('#SubTotal').val(parseFloat(data_product[2]));

    if($('#ckPPN').is(":checked")) {
        PPN = PersenttoRp(10,total_price_product);
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
    d = $("input[name='product_price[]']").length;
    total = 0.00;
    discount_rp = 0.00;
    before_total = 0.00;
    for (i = 0; i < d; i++) { 
        code    = $('[name="productid[]"]').eq(i).val();
        conversion  = $('[name="product_konv[]"]').eq(i).val();
        conversion  = removeduit(conversion);
        
        i_qty   = $('[name="product_qty[]"]').eq(i);
        qty     = i_qty.val();
        i_qty.val(qty);
        qty     = removeduit(qty);

        qty     = qty * conversion;
        
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
            before_total += sub_total;
            total += sub_total - total_discount;
            discount_rp += total_discount;

            t = sub_total - total_discount;
            $('[name="product_total[]"]').eq(i).val(parseFloat(t.toFixed(2)));
            $('.p_sub_total').eq(i).val(parseFloat(t.toFixed(2)));
        }
    }

    var data = [total.toFixed(2),discount_rp.toFixed(2),before_total.toFixed(2)];
    return data;
}

function view(id,page) {
    reset_button_action();
    if(page == "print"){
        view_print(id,page)
    }else{
        id_row = 0;
        save_method = "view";
        $(".readonly").attr("readonly",true);
        $('#form')[0].reset(); // reset form on modals
        $('.form-group, .input-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#form [name=crud]').val('view');
        // $('#modal').modal({backdrop: 'static', keyboard: true}); // show bootstrap modal
        $("#modal").modal("show");
        $('#modal .modal-title').text(title_page+' '+language_app.btn_detail); // Set Title to Bootstrap modal title
        $("#modal .save").hide();
        $(".link_add_row").hide();
        $(".btn-serial-v, .vaction, .vprint").show();
        $('.table-add-product tbody').children('tr').remove();
        $(".th-code").attr("colspan",3);
        $("#form input, #form textarea, #form select").attr("disabled",true);
        $('.btn_select').addClass('cursor_disabled');
        $(".from_v").hide();
        $(".table-add-product").removeClass("table-td-padding-0");
        // $('.vstep2, .vstep3').removeClass('disabled');
        // $('.vstep2 a, .vstep3 a').attr('href', '#vstep').attr('data-toggle', 'tab');
        check_status(id);
        default_tab();
        reset_file_upload();
        hide_upload_file();
        hide_button_cancel();

        data_page   = $(".data-page, .page-data").data();
        url_modul   = data_page.url_modul;
        modul       = data_page.modul;

        data_post   = {
            modul       : modul,
            url_modul   : url_modul,
        }

        $.ajax({
            url : url_edit + id,
            type: "POST",
            data    : data_post,
            dataType: "JSON",
            success: function(data)
            {
                row = 0;
                total_price = 0;
                list = data.list;
                $("[name=SellingNo]").val(list.SellNo); 
                if(list.VendorID){
                    $("[name=CustomerID]").val(list.VendorID+"-"+list.customerName);
                    $("[name=CustomerName]").val(list.customerName);
                }else{
                    $("[name=CustomerID]").val('');
                }  
                $('#CustomerID').data('productcustomer',list.productcustomer);          
                if(list.SalesID){
                    $('[name=SalesID]').val(list.SalesID+"-"+list.salesName);
                    $('[name=SalesName]').val(list.salesName);
                }else{
                    $('[name=SalesID]').val('');
                }
                if(list.BranchID){
                    $('[name=BranchID]').val(list.BranchID+"-"+list.branchName);
                    $('#BranchName').val(list.branchName);
                }else{
                    $('[name=BranchID]').val('');
                    $('#BranchName').val('');
                }
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
                $("[name=Date]").val(converttoDate(list.Date,"yy-mm-dd"));
                $('[name=NoPo]').val(list.NoPOKonsumen);
                $('[name=Term]').val(list.Term);
                count_term('term');

                $("[name=sell_remark]").val(list.Remark);         
                $("[name=SubTotal]").val(parseFloat(list.Total));
                $("[name=Total]").val(parseFloat(list.Payment));
                $("[name=PPN]").val(list.PPN);
                $('[name=TotalPPN]').val(parseFloat(list.TotalPPN));
                $("[name=DiscountRp]").val(parseFloat(list.Discount));
                $("[name=Discount]").val(list.DiscountPersent);

                // delivery
                $('[name=DeliveryTo]').val(list.DeliveryTo);
                $('[name=delAddress]').val(list.DeliveryAddress);
                $('[name=delCity]').val(list.DeliveryCity);
                $('[name=delProvince]').val(list.DeliveryProvince);
                $('[name=Ongkir]').val(parseFloat(list.DeliveryCost));
                $('[name=DeliveryDate]').val(list.DeliveryDate);

                // invoice
                $('[name=BillingTo]').val(list.PaymentTo);
                $('[name=invAddress]').val(list.PaymentAddress);
                $('[name=invCity]').val(list.PaymentCity);
                $('[name=invProvince]').val(list.PaymentProvince);

                // if(data.mutation_type == 1){
                //     $(".from_v").show();
                // } else if(data.mutation_type == 2){
                //     $(".to_v").show();
                // } else {
                //     $(".from_v").hide();
                // }

                $.each(data.list_detail, function(i, v) {
                    add_new_row2("view",v);
                });
                $.each(data.attach,function(i,v){
                    set_file(v,1,"view");
                })
                check_product_status(list.ProductType,'none');
                if(data.list_detail.length == 0){
                    item = '<tr><td colspan="8" style="text-align:center;">Empty Selling Data</td></tr>'
                    $(".table-add-product tbody").append(item);
                }

                $("#form input").attr("disabled",true);
                $(".remove_row").hide();

                sn_status       = data.sn_status;
                $('#form-pembayaran [name=sn_status]').val(sn_status);
                $('#form-return [name=return_pay]').val(data.payment);
                moneyFormat();
                create_format_currency2();
                set_button_action(data);
                disabled_file();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
                console.log(jqXHR.responseText);
            }
        });
    }

    url = host + "selling-view/"+id+"?page=print";
    action_print_button(url);
}

function button_print(data){
    list            = data.list;
    data_invoice    = data.data_invoice;
    data_delivery   = data.data_delivery;
    payment         = data.payment;
    delivery        = data.delivery;

    sellno          = list.SellNo;
    sellno          = sellno.replace(/\/+/g, '-');

    $('#pselling').attr('onclick', 'view_print('+"'"+sellno+"','selling'"+')');
    $('#pstruck').attr('onclick', 'view_print('+"'"+sellno+"','struck'"+')');
    $('#pinvoice').attr('onclick', 'view_print('+"'"+sellno+"','invoice'"+')');

    if(delivery>0){
        $('#pdelivery').attr('onclick', 'view_print('+"'"+sellno+"','delivery'"+')');
        $('#pdelivery').show();
    }else{
        $('#pdelivery').removeAttr('onclick');
        $('#pdelivery').hide();
    }    

    $('#apayment').attr('onclick', 'paymnet_selling('+"'"+sellno+"','selling'"+')');
    if(payment>0){
        $('#areturn').attr('onclick', 'return_selling('+"'"+sellno+"','selling'"+')');
        $('#areturn').show();
    }else{
        $('#areturn').removeAttr('onclick');
        $('#areturn').hide();
    }
}

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
    $(".btn-serial-v").hide();
    $('.vaction, .vprint').hide();
    $('.table-add-product tbody').children('tr').remove();
    $(".th-code").attr("colspan",3);
    $(".table-add-product").removeClass("table-td-padding-0");
    check_status(id);
    default_tab();
    reset_button_action();
    reset_file_upload();
    show_upload_file();
    hide_button_cancel2();
    $('.btn-close').show();

    data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;

    data_post   = {
        modul       : modul,
        url_modul   : url_modul,
    }

    $.ajax({
        url : url_edit + id,
        type: "POST",
        data : data_post,
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            row = 0;
            total_price = 0;
            list = data.list;
            $("[name=SellingNo]").val(list.SellNo);       
            if(list.VendorID){
                $("[name=CustomerID]").val(list.VendorID+"-"+list.customerName);
                $("[name=CustomerName]").val(list.customerName);
            }else{
                $("[name=CustomerID]").val('');
            }
            $('#CustomerID').data('productcustomer',list.productcustomer);
            if(list.SalesID){
                $('[name=SalesID]').val(list.SalesID+"-"+list.salesName);
                $('[name=SalesName]').val(list.salesName);
            }else{
                $('[name=SalesID]').val('');
            }
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
            $("[name=Date]").val(converttoDate(list.Date,"yy-mm-dd"));
            $('[name=NoPo]').val(list.NoPOKonsumen);
            $('[name=Term]').val(list.Term);
            count_term('term');

            $("[name=sell_remark]").val(list.Remark);         
            $("[name=SubTotal]").val(parseFloat(list.Total));
            $("[name=Total]").val(parseFloat(list.Payment));
            $("[name=PPN]").val(list.PPN);
            $("[name=DiscountRp]").val(parseFloat(list.Discount));
            $("[name=Discount]").val(list.DiscountPersent);

            // delivery
            $('[name=DeliveryTo]').val(list.DeliveryTo);
            $('[name=delAddress]').val(list.DeliveryAddress);
            $('[name=delCity]').val(list.DeliveryCity);
            $('[name=delProvince]').val(list.DeliveryProvince);
            $('[name=Ongkir]').val(parseFloat(list.DeliveryCost));
            $('[name=DeliveryDate]').val(list.DeliveryDate);

            // invoice
            $('[name=BillingTo]').val(list.PaymentTo);
            $('[name=invAddress]').val(list.PaymentAddress);
            $('[name=invCity]').val(list.PaymentCity);
            $('[name=invProvince]').val(list.PaymentProvince);

            // if(data.mutation_type == 1){
            //     $(".from_v").show();
            // } else if(data.mutation_type == 2){
            //     $(".to_v").show();
            // } else {
            //     $(".from_v").hide();
            // }
            check_product_status(list.ProductType,'none');
            $.each(data.list_detail, function(i, v) {
                if(list.ProductType == 1){
                    add_new_services(v);
                }else{
                    add_new_row(v);
                }
            });
            if(data.list_detail.length == 0){
                item = '<tr><td colspan="8" style="text-align:center;">Empty Selling Data</td></tr>'
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
                            $('#modal').modal('hide');
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

function check_status(id){
    page_status     = $('.status'+id).data();
    data_status     = page_status.status;
    if(data_status == 1){
        $('.btn-add-serial').show();
    }else{
        $('.btn-add-serial, .vaction').hide();
    }
}

function default_tab(){
    $('.tab-step li').removeClass('active');
    $('.tab-step .vstep1').addClass('active');
    $('.vstep2, .vstep3').removeClass('disabled');
    $('.vstep2 a, .vstep3 a').attr('href', '#vstep').attr('data-toggle', 'tab');
    step(1);
}

function step(val){
    if(val == 1){
        $('.vsell').show(300);
        $('.vdelivery, .vinvoice').hide(300);
    }else if(val == 2){
        $('.vdelivery').show(300);
        $('.vsell, .vinvoice').hide(300);
    }else if(val == 3){
        $('.vinvoice').show(300);
        $('.vdelivery, .vsell').hide(300);
    }
}

function view_print(id,page){
    if(page == "print"){
        open_modal_template(id,page);
    }else{
        view_print_data(id,page);
    }
}

function view_print_data(id,page){
    $('#modal-print').modal('show');
    $('#modal-print .modal-title').text("Print "+page);
    url = host + "selling-view/"+id+"?page="+page;

    var TemplateID       = $('.template_select option:selected').val();
    var default_template = 0;
    if ($('#default_template').is(":checked")){
        default_template = 1;
    }
    data_post  = {
        TemplateID       : TemplateID,
        default_template : default_template,
        "cktemplate"     : 1,
    }

    $("#view-print").load(url,data_post,function(){
        $(".div-loader").hide();
    });
    $("#link_print").attr("href",url+"&cetak=cetak");
    $("#link_pdf_1").attr("href",url+"&cetak=pdf&position=portrait");
    $("#link_pdf_2").attr("href",url+"&cetak=pdf&position=landscape");
}

// checkbox ppn
$('#ckPPN').change(function() {
    if($(this).is(":checked")) {
        $('#PPN').val(10);
    }else{
        $('#PPN').val(0);
    }
    product_status = $('[name=product_status]:checked').val();
    if(product_status == 0){
        SumTotal('element');
    }else{
        SumTotalServices('element');
    }
});
// end checkbox ppn

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
}
// end customer address

// delivery / invoice order
function delivery_order(id,status){
    $.redirect(host+"delivery",
    {
        SellNo: id,
        DeliveryStatus: status,
    },
    "POST",);
}
// end delivery / invoice order


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
    d_date        = new Date($("#Date").val());
    if(page == "term"){
        val = checkIntInput(term.val());
        d_date.setTime(d_date.getTime() + val * 24 * 60 * 60 * 1000);
        val = converttoDate(d_date,"yy-mm-dd");
        due_date.val(val);
    }else{
        val = due_date.val();
        end = new Date(val);

        var diff = new Date(end - d_date);
        var days = diff/1000/60/60/24;
        term.val(days);
    }
}

// end due date and term

// product services
$('[name=product_status]').on('click',function(){
    val     = $(this).val();
    check_product_status(val);
});
function check_product_status(val,page){
    if(val == 0){
        $('.vproduct-services').show(300);
        $('#Ongkir').attr('onkeyup', 'SumTotal()');
        if(page != "none"){
            $('.table-add-product tbody').children( 'tr' ).remove();
            add_new_row();
        }
    }else{
        $('#Ongkir').attr('onkeyup', 'SumTotalServices()');
        $('.vproduct-services').hide(300);
        if(page != "none"){
            $('.table-add-product tbody').children( 'tr' ).remove();
            add_new_services();
        }
    }
}

function add_new_services(data){
    kolom = $('.table-add-product tbody').find('tr').length + 1;

    if(kolom>100){
        swal('','Data item max 100', 'warning');
        return;
    }

    id_row += 1;

    id_row += 1;
    btn_serial = "";
    btn_remove = "";
    
    selldet         = '';
    productid       = '';
    code            = '';
    name            = '';
    unitid          = '';
    product_unit    = '';
    product_konv    = '';
    product_type    = '';
    product_price   = '';
    product_total   = '';
    product_discount= '';
    product_remark  = '';
    sub_total       = '';
    product_delivery= date_now;

    if(kolom > 1){
       btn_remove = '<i  onclick="delete_row(this)" class="icon fa-remove remove_row" aria-hidden="true" style="cursor:pointer;padding:5px;"></i>'; 
    }
    if(data){
        selldet         = data.SellDet;
        productid       = data.ProductID;
        code            = data.product_code;
        name            = data.product_name;
        unitid          = data.UnitID;
        product_unit    = data.unit_name;
        product_konv    = data.Conversion;
        product_type    = data.Type;
        product_price   = parseFloat(data.Price);
        product_total   = parseFloat(data.TotalPrice);
        product_discount= parseFloat(data.Discount);
        product_remark  = data.Remark;
        sub_total       = '';
        if(data.product_delivery){
            product_delivery= data.product_delivery;
        }
    }

    item = '<tr class="rowid_'+id_row+' rowdata" data-row="'+id_row+'" data-status="1" data-selling="active" data-typenya="3">\
                <td>\
                    <input type="hidden" name="rowid[]" value="rowid_'+id_row+'">\
                    <input type="hidden" name="selldet[]" value="'+selldet+'">\
                    <div class="info-warning"></div>\
                </td>\
                <td class="remove_row" style="min-width:60px;">\
                    <i class="icon fa-search remove_row" onclick="ckproduct_modal('+"'"+id_row+"','ar'"+')" style="cursor:pointer;padding:5px;"></i>\
                    '+btn_remove+'\
                </td>\
                <td><input type="text" value="'+code+'" class="p_code product_modal disabled">\
                <input type="hidden" name="productid[]" value="'+productid+'" class="p_id">\
                </td>\
                <td><input type="text" value="'+name+'" class="p_name disabled"></td>\
                <td>\
                    <input type="hidden" name="product_type[]" value="'+product_type+'" data-class="p_type" class="p_type">\
                    <input type="text" name="product_price[]" value="'+product_price+'" data-class="p_sellprice" class="p_sellprice duit" onkeyup="SumTotalServices()">\
                    <input type="hidden" value="'+product_total+'" class="duit p_product_total" readonly>\
                </td>\
                <td><input type="text" name="product_discount[]" value="'+product_discount+'" data-class="p_selldiscount" class="p_selldiscount" onkeyup="SumTotalServices(this)"></td>\
                <td><input type="text" value="'+product_total+'" data-class="p_sub_total" class="p_sub_total duit disabled"></td>\
                <td><input type="text" name="product_remark[]" value="'+product_remark+'" data-class="p_remark" class="p_remark"></td>\
                <td><input type="text" name="product_delivery[]" value="'+product_delivery+'" class="p_delivery date"></td>\
                <td>\
                   '+btn_remove+btn_serial+'\
                </td>\
            </tr>';
    $(".table-add-product tbody").append(item);
    $(".disabled").attr("disabled",true);
    $(".p_sellprice").data("min","tes");
    moneyFormat();
    date();
    SumTotalServices();
}

function SumTotalServices(element){
    data_product = get_total_price_product_services();
    total_price_product = data_product[0];
    total_discount      = data_product[1];
    total_discount_p    = RptoPersent(data_product[2],total_discount);

    $('#DiscountRp').val(parseFloat(total_discount));
    $('#Discount').val(checkNan(total_discount_p));
    $('#SubTotal').val(parseFloat(data_product[2]));

    if($('#ckPPN').is(":checked")) {
        PPN = PersenttoRp(10,total_price_product);
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

    run_function = 'SumTotalServices()';
    if(element){
        create_format_currency2();
    }
}

function get_total_price_product_services(){
    d = $("input[name='productid[]']").length;
    total = 0.00;
    discount_rp = 0.00;
    before_total = 0.00;
    for (i = 0; i < d; i++) { 
        code    = $('[name="productid[]"]').eq(i).val();
        rowid   = $('[name="rowid[]"]').eq(i).val();
        
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
            total_discount  = PersenttoRp(val,discount);
            before_total    += val;
            total           += val - total_discount;
            discount_rp     += total_discount;

            t = val - total_discount;
            $('.p_product_total').eq(i).val(parseFloat(t.toFixed(2)));
            $('.p_sub_total').eq(i).val(parseFloat(t.toFixed(2)));
        }
    }

    var data = [total.toFixed(2),discount_rp.toFixed(2),before_total.toFixed(2)];
    return data;
}
// end product services

function edit_attach(id){
    $('#modal .modal-title').text(title_page+' '+language_app.lb_edit); // Set Title to Bootstrap modal title
    show_upload_file();
    $('#sell_remark').attr('disabled', false);
    show_button_cancel();
    $('.btn-back').attr('onclick', 'cancel_attach('+"'"+id+"'"+')');
    $('.btn-save2').attr('onclick', 'save_attach('+"'"+id+"'"+')');
}

function cancel_attach(id){
    view(id)
}

function save_attach(id){
    ID = $('.data-ID').val();
    remark = $('#sell_remark').val();
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
    add_new_row();
    SumTotal();
}