var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host 			= window.location.origin+'/pipesys_qa/';
var url 			= window.location.href;
var page_login 		= host + "main/login";
var page_register 	= host + "main/register";
var url_list 		= host + "purchase_order/ajax_list/";
var url_edit        = host + "purchase_order/ajax_edit/";
var url_cancel 		= host + "purchase_order/cancel/";
var url_simpan 		= host + "purchase_order/save";
var url_simpan_remark = host + "purchase_order/save_remark";
var save_method; //for save method string
var table;
var date_now;
var id_row = 0;

$(window).load(function(){

    page_data   = $(".page-data").data();
    app         = page_data.app;
    ap          = page_data.ap;

    
});

$(document).ready(function() {
	data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    url    		= url_list+url_modul+"/"+modul;
    date_now    = data_page.date;
    title_page  = data_page.title;

    //datatables
    filter_table();
});
function filter_table(page){
	data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    url    		= url_list+url_modul+"/"+modul;

    fSearch             = $('#form-filter [name=fSearch]').val();
    fStartDate          = $('#form-filter [name=fStartDate]').val();
    fEndDate            = $('#form-filter [name=fEndDate]').val();
    fStatus             = $('#form-filter [name=fStatus]').val();
    fProductType        = $('#form-filter [name=fProductType]').val();
    fBranch          = $('#form-filter [name=fBranch]').val();

    data_post = {
        Search              : fSearch,
        StartDate           : fStartDate,
        EndDate             : fEndDate,
        Status              : fStatus,
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
    $('.btn_select').removeClass('cursor_disabled');
    $('#form')[0].reset(); // reset form on modals
    $('.has-error').removeClass('has-error'); // clear error class
    $('.input-error').removeClass('input-error');
    $('.help-block').empty(); // clear error string
    $(".th-code").attr("colspan",4);
    $("#modal .save").show();
    $('#modal').modal('show'); // show bootstrap modal
    if (modul == "sales") {
        modulTitle = "Employee";
    }else{
        modulTitle = modul;
    }
    $('.link_add_row').show();
    $('.vaction, .vprint').hide();
    $('#ckPPN').attr('checked', true);
    $('#PPN').val(10);
    $('.table-add-product tbody').children( 'tr' ).remove();
    default_tab();
    set_default_branch();
    add_new_row();
    // $('.vstep2, .vstep3').addClass('disabled');
    // $('.vstep2 a, .vstep3 a').attr('href', 'javascript:void(0)').removeAttr('data-toggle');
    $('.modal-title').text(language_app.lb_add_new+' '+ title_page); // Set Title to Bootstrap modal title
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

function add_new_row(data) {
    product_status = $('[name=product_status]:checked').val();
    kolom = $('.table-add-product tbody').find('tr').length + 1;
    id_row += 1;
    btn_serial = "";
    btn_remove = "";

    if(kolom>100){
        swal('',language_app.lb_max_item+" 100", 'warning');
        return;
    }
    
    purchasedet     = '';
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
    delivery_date 	= date_now;

    if(kolom > 1){
       btn_remove = '<i  onclick="delete_row(this)" class="icon fa-remove remove_row" aria-hidden="true" style="cursor:pointer;padding:5px;"></i>'; 
    }
    if(data){
        purchasedet     = data.PurchaseDet;
        productid       = data.productid;
        code            = data.product_code;
        name            = data.product_name;
        qty             = parseFloat(data.product_qty);
        p_qty           = parseFloat(data.product_qty);
        stock_product   = parseFloat(data.ReceiveQty);
        unitid          = data.unitid;
        product_unit    = data.unit_name;
        product_konv    = data.product_conv;
        product_type    = data.type;
        product_price   = parseFloat(data.product_price);
        product_total   = parseFloat(data.product_total);
        product_discount= parseFloat(data.discount);
        product_remark  = data.remark;
        delivery_date 	= data.delivery_date;
        sub_total       = '';

    }

    item2 = '<td><input type="text" value="'+stock_product+'" class="disabled"></td>\
                <td><input type="text" name="product_qty[]" value="'+qty+'" data-qty="active" data-class="p_min_qty" class="p_min_qty duit" onkeyup="SumTotal()" min="0"></td>\
                <td>\
                <input type="hidden" name="product_unitid[]" value="'+unitid+'" class="p_unitid">\
                <input type="hidden" value="'+product_unit+'" class="p_unit disabled">\
                <select style="min-width:100px" class="p_unit2 width-100per" onchange="check_product_unit(this)"></select>\
                </td>';
    if(product_status == 1){
        item2 = '';
    }
    // onkeyup="keyup_product('+id_row+',this)"
    item = '<tr class="rowid_'+id_row+' rowdata" data-row="'+id_row+'" data-typenya="1">\
                <td>\
                    <input type="hidden" name="rowid[]" value="rowid_'+id_row+'">\
                    <input type="hidden" name="purchasedet[]" value="'+purchasedet+'">\
                    <div class="info-warning"></div>\
                </td>\
                <td class="remove_row">\
                    <i class="icon fa-search remove_row" onclick="product_modal('+id_row+')" style="cursor:pointer;padding:5px;"></i>\
                </td>\
                <td>'+btn_remove+'</td>\
                <td><input type="text" value="'+code+'" class="autocomplete_product p_code product_modal disabled">\
                <input type="hidden" name="productid[]" value="'+productid+'" class="p_id">\
                </td>\
                <td><input type="text" value="'+name+'" class="p_name disabled"></td>\
                '+item2+'\
                <td class="content-hide"><input type="text" name="product_konv[]" value="'+product_konv+'" class="p_conv disabled"></td>\
                <td>\
                    <input type="hidden" name="product_type[]" value="'+product_type+'" data-class="p_type" class="p_type">\
                    <input type="text" name="product_price[]" value="'+product_price+'" data-class="p_purchaseprice" class="p_purchaseprice duit" onkeyup="SumTotal()">\
                    <input type="hidden" name="product_total[]" value="'+product_total+'" class="duit" readonly>\
                </td>\
                <td><input type="text" name="product_discount[]" value="'+product_discount+'" data-class="p_purchasediscount" class="p_purchasediscount" onkeyup="SumTotal(this)"></td>\
                <td><input type="text" value="'+product_total+'" data-class="p_sub_total" class="p_sub_total duit disabled"></td>\
                <td><input type="text" name="product_remark[]" value="'+product_remark+'" data-class="p_remark" class="p_remark""></td>\
                <td><input type="text" name="product_delivery_date[]" value="'+delivery_date+'" data-class="p_delivery_date" class="p_delivery_date date""></td>\
                <td>\
                   '+btn_remove+btn_serial+'\
                </td>\
            </tr>';
    $(".table-add-product tbody").append(item);
    $(".disabled").attr("disabled",true);
    moneyFormat('SumTotal()');
    date();
}
function add_new_row2(page = "",v = "") {

    purchasedet         = v.PurchaseDet;
    productid           = v.productid;
    product_code        = v.product_code;
    product_name        = v.product_name;
    product_qty         = parseFloat(v.product_qty);
    product_qty2        = parseFloat(v.ReceiveQty);
    unitid              = v.unitid;
    unit_name           = v.unit_name;
    product_type        = v.type;
    product_konv        = v.product_conv;
    product_price       = v.product_price;
    product_total       = parseFloat(v.product_total);
    remark              = v.remark;
    discount            = v.discount;
    delivery_date 		= v.delivery_date;
    btn_serial          = "";
    // if(product_type == "general" || product_type == "serial"){
        page        = "'purchase'";
        purchasedet = "'"+purchasedet+"'";
        if(product_type == 0 || product_type == 2){
            // btn_serial  = '<a  onclick="add_serial('+page+','+purchasedet+')" class="btn-serial-v" aria-hidden="true" style="cursor:pointer;padding:5px;">Add Serial</a>';
        }
    // }
    item    = '<tr>\
                <td>'+'</td>\
                <td>'+'</td>\
                <td>'+product_code+'</td>\
                <td>'+product_name+'</td>\
                <td class="vproduct-services">'+v.ReceiveQty_txt+'</td>\
                <td class="vproduct-services">'+v.product_qty_txt+'</td>\
                <td class="vproduct-services">'+unit_name+'</td>\
                <td class="content-hide">'+product_konv+'</td>\
                <td>'+v.product_price_txt+'</td>\
                <td>'+v.discount_txt+'</td>\
                <td>'+v.product_total_txt+'</td>\
                <td>'+remark+'</td>\
                <td>'+delivery_date+'</td>\
                <td>'+btn_serial+'</td>\
            </tr>';
    $(".table-add-product tbody").append(item);
}

function delete_row(a) {
    // console.log(a);
    // if(confirm("Are you sure you want to delete this Row?")==true)
    $(a).closest('tr').remove();
    SumTotal();
    create_format_currency2();
    // return false;
}

function save(page)
{
    $("#form input").attr("disabled",false);
    $('.info-warning').empty();
    $('.has-error').removeClass('has-error'); // clear error class
    $('.input-error').removeClass('input-error');
    $('.help-block').empty();
    proses_save_button();

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
                    load_data('purchaseheader');
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

function SumTotal(element){
    data_product = get_total_price_product();
    total_price_product = data_product[0];
    total_discount      = data_product[1];
    total_discount_p    = RptoPersent(data_product[2],total_discount);
    $('#DiscountRp').val(removeduit(total_discount));
    $('#Discount').val(checkNan(total_discount_p));
    $('#SubTotal').val(parseFloat(data_product[2]));

    if($('#ckPPN').is(":checked")) {
        PPN = PersenttoRp(10,total_price_product);
    }else{
        PPN = 0;
    }

    deliverycost     = $("[name=Ongkir]").val();
    deliverycost     = removeduit(deliverycost);

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
    product_status = $('[name=product_status]:checked').val();
    d = $("input[name='product_price[]']").length;
    total = 0.00;
    discount_rp = 0.00;
    before_total = 0.00;
    for (i = 0; i < d; i++) { 
        code        = $('[name="product_code[]"]').eq(i).val();
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
            before_total += sub_total;
            total += sub_total - total_discount;
            discount_rp += total_discount;

            t = sub_total - total_discount;
            $('[name="product_total[]"]').eq(i).val(parseFloat(t.toFixed(amountdecimal)));
            $('.p_sub_total').eq(i).val(parseFloat(t.toFixed(amountdecimal)));
        }
    }

    var data = [total.toFixed(amountdecimal),discount_rp.toFixed(amountdecimal),before_total.toFixed(amountdecimal)];
    return data;
}

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

function view(id,page){
    reset_button_action();
    if(page == "print"){
    	open_modal_template(id,page);
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
        reset_button_action();
        reset_file_upload();
        create_form_attach();
        hide_upload_file();
        hide_button_cancel();
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
	            row = 0;
	            total_price = 0;
	            list = data.list;
	            $("[name=PurchaseNo]").val(list.PurchaseNo); 
	            if(list.VendorID){
                    $("[name=VendorID]").val(list.VendorID+"-"+list.vendorName);
	                $("[name=VendorName]").val(list.vendorName);
	            }else{
	                $("[name=VendorID]").val('');
	            }            
	            if(list.SalesID){
                    $('[name=SalesID]').val(list.SalesID+"-"+list.salesName);
	                $('[name=SalesName]').val(list.salesName);
	            }else{
	                $('[name=SalesID]').val('');
	            }

                if(list.BranchID){
                    $('[name=BranchID]').val(list.BranchID+"-"+list.branchName);
                    $('#BranchName').val(list.branchName);
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
	            $("[name=Date]").val(list.Date);
	            $('[name=Term]').val(list.PaymentTerm);
                count_term('term');

	            $("[name=purchase_remark]").val(list.Remark);         
	            $("[name=SubTotal]").val(parseFloat(list.Total));
	            $("[name=Total]").val(parseFloat(list.Payment));
	            $("[name=PPN]").val(list.PPN);
	            $("[name=TotalPPN]").val(parseFloat(list.TotalPPN));
	            $("[name=DiscountRp]").val(parseFloat(list.Discount));
	            $("[name=Discount]").val(list.DiscountPersent);

	            // delivery
	            $('[name=DeliveryTo]').val(list.DeliveryTo);
	            $('[name=delAddress]').val(list.DeliveryAddress);
	            $('[name=delCity]').val(list.DeliveryCity);
	            $('[name=delProvince]').val(list.DeliveryProvince);
	            $('[name=Ongkir]').val(parseFloat(list.DeliveryCost));

	            // invoice
	            $('[name=BillingTo]').val(list.PaymentTo);
	            $('[name=invAddress]').val(list.PaymentAddress);
	            $('[name=invCity]').val(list.PaymentCity);
	            $('[name=invProvince]').val(list.PaymentProvince);

	            $.each(data.list_detail, function(i, v) {
	                add_new_row2("view",v);
	            });
                $.each(data.attach,function(i,v){
                    set_file(v,1,"view");
                })
                check_product_status(list.ProductType,'none');
	            if(data.list_detail.length == 0){
	                item = '<tr><td colspan="8" style="text-align:center;">Empty Purchase Data</td></tr>'
	                $(".table-add-product tbody").append(item);
	            }

	            $("#form input").attr("disabled",true);
	            $(".remove_row").hide();

	            sn_status       = data.sn_status;
	            $('#form-pembayaran [name=sn_status]').val(sn_status);
	            moneyFormat('SumTotal()');
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
    url = host + "purchase-order-view/"+id+"?page=print";
    action_print_button(url);
}

function view_print_data(id,page){
	if(page == "print"){
        page = "print";
    }else{
        page = "Purchase";
    }
    $('#modal-print').modal('show');
    $('#modal-print .modal-title').text(language_app.lb_print+" "+title_page);
    url = host + "purchase-order-view/"+id+"?page="+page;

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

function check_status(id){
    page_status     = $('.status'+id).data();
    data_status     = page_status.status;
    if(data_status == 1){
        $('.btn-add-serial').show();
    }else{
        $('.btn-add-serial, .vaction').hide();
    }
}

function button_print(data){
    list            = data.list;
    PurchaseNo 		= list.purchaseno;
    $('#p-purchase').attr('onclick', 'view_print_data('+"'"+PurchaseNo+"'"+')');
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
                            load_data('purchaseheader');
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
// end cancel

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
    $(".btn-serial-v").hide();
    $('.vaction, .vprint').hide();
    $('.table-add-product tbody').children('tr').remove();
    $(".th-code").attr("colspan",4);
    $(".table-add-product").removeClass("table-td-padding-0");
    check_status(id);
    default_tab();
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
            row = 0;
            total_price = 0;
            list = data.list;
            $("[name=PurchaseNo]").val(list.PurchaseNo);       
            if(list.VendorID){
                $("[name=VendorID]").val(list.VendorID+"-"+list.vendorName);
                $("[name=VendorName]").val(list.vendorName);
            }else{
                $("[name=VendorID]").val('');
            }
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
            $("[name=Date]").val(list.Date);
            $('[name=Term]').val(list.PaymentTerm);
            count_term('term');

            $("[name=purchase_remark]").val(list.Remark);         
            $("[name=SubTotal]").val(parseFloat(list.Total));
            $("[name=Total]").val(parseFloat(list.Payment));
            $("[name=PPN]").val(list.PPN);
            $("[name=TotalPPN]").val(parseFloat(list.TotalPPN));
            $("[name=DiscountRp]").val(parseFloat(list.Discount));
            $("[name=Discount]").val(list.DiscountPersent);

            // delivery
            $('[name=delAddress]').val(list.DeliveryAddress);
            $('[name=delCity]').val(list.DeliveryCity);
            $('[name=delProvince]').val(list.DeliveryProvince);
            $('[name=Ongkir]').val(parseFloat(list.DeliveryCost));

            // invoice
            $('[name=BillingTo]').val(list.PaymentTo);
            $('[name=invAddress]').val(list.PaymentAddress);
            $('[name=invCity]').val(list.PaymentCity);
            $('[name=invProvince]').val(list.PaymentProvince);

            $.each(data.list_detail, function(i, v) {
                add_new_row(v);
            });
            check_product_status(list.ProductType,'none');
            if(data.list_detail.length == 0){
                item = '<tr><td colspan="8" style="text-align:center;">Empty Purchase Data</td></tr>'
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
// end edit

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
    d_date      = new Date($("#Date").val());
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
        if(page != "none"){
            $('.table-add-product tbody').children( 'tr' ).remove();
            add_new_row();
        }
    }else{
        $('.vproduct-services').hide(300);
        if(page != "none"){
            $('.table-add-product tbody').children( 'tr' ).remove();
            add_new_row();
        }
    }
}
// end product services

function edit_attach(id){
    $('#modal .modal-title').text(title_page+' Edit'); // Set Title to Bootstrap modal title
    show_upload_file();
    $('#purchase_remark').attr('disabled', false);
    show_button_cancel();
    $('.btn-back').attr('onclick', 'cancel_attach('+"'"+id+"'"+')');
    $('.btn-save2').attr('onclick', 'save_attach('+"'"+id+"'"+')');
}

function cancel_attach(id){
    view(id)
}

function save_attach(id){
    ID = $('.data-ID').val();
    remark = $('#purchase_remark').val();
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