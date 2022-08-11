var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host 			= window.location.origin+'/';
var url 			= window.location.href;
var page_login 		= host + "main/login";
var page_register 	= host + "main/register";
var url_list 		= host + "payment_ap/ajax_list/";
var url_edit        = host + "payment_ap/ajax_edit/";
var url_cancel 		= host + "payment_ap/cancel/";
var url_simpan 		= host + "payment_ap/save";
var url_simpan_remark = host + "payment_ap/save_remark";
var save_method; //for save method string
var table;
var data_detailnya = [];

var id_row = 0;
//dashboard
var url_post        = host + "dashboard/dashboard/";
var period = "";
var chart_purchase_open,chart_purchase_overdude,chart_purchase_payment;
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

    get_coa_setting();
    if(data_page.id){
        statusid = data_page.statusid;
        statusid = statusid.split("-");
        if(statusid[0] != 1){
            if(statusid[1] == "invoice"){
                get_detail_invoice(data_page.id);
            }
        }
    }
    // purchase
    selected_item('#ul-purchase-open', st_period_type,'non');
    selected_item('#ul-purchase-overdude', st_period_type,'non');
    selected_item('#ul-purchase-payment', st_period_type,'non');

    create_chart();
    //datatables
    filter_table();
});

function filter_table(page){
    data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    url         = url_list+url_modul+"/"+modul;

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
        "order": [], //Initial no order.
        "searching": false, //Feature Search false
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
    $('.vaction, .vprint').hide();
    $('.table-add-product tbody').children( 'tr' ).remove();
    // add_new_row();
    $('.modal-title').text(language_app.lb_add_new+' '+ title_page); // Set Title to Bootstrap modal title
    $('[name="crud"]').val("insert");
    $('[name=temp_invoiceno]').val('');
    $('[name=temp_paymentno]').val('');
    $('#cash :input').prop('disabled',true);
    $('#giro :input').prop('disabled',true);
    $('#card :input').prop('disabled',true);
    $('.disabled').attr('disabled',true);
    ckPaymentType1();
    ckPaymentType2();
    ckPaymentType3();
    reset_button_action();
    reset_file_upload();
    show_upload_file();
    hide_button_cancel2();
    create_form_attach();
    $('.btn-close').show();
    //-----------------------------------------------------------------------------
}

$('input[type=checkbox][name=PaymentType1]').change(function() {
    ckPaymentType1();
});
function ckPaymentType1(data){
    val         = $('input[type=checkbox][name=PaymentType1]:checked').val();
    if(val == 1){
        $('#cash :input').prop('disabled',false);
    }else{
        $('#cash :input').prop('disabled',true);
    }
    
    coa_bayar1(val,data);
    cekrow('element');
}

$('input[type=checkbox][name=PaymentType2]').change(function() {
    ckPaymentType2();
});
function ckPaymentType2(data){
    val         = $('input[type=checkbox][name=PaymentType2]:checked').val();
    if(val == 2){
        $('#giro :input').prop('disabled',false);
        $(".v-giro").show(300);
    }else{
        $('#giro :input').prop('disabled',true);
        $(".v-giro").hide(300);
    }
    
    coa_bayar2(val,data);
    cekrow('element');
}

$('input[type=checkbox][name=PaymentType3]').change(function() {
    ckPaymentType3();
});
function ckPaymentType3(data){
    val         = $('input[type=checkbox][name=PaymentType3]:checked').val();
    if(val == 3){
        $('#card :input').prop('disabled',false);
        $(".v-card").show(300);
    }else{
        $('#card :input').prop('disabled',true);
        $(".v-card").hide(300);
    }
    
    coa_bayar3(val,data);
    cekrow('element');
}

function check_payment_type(){
    val1         = $('input[type=checkbox][name=PaymentType1]:checked').val();
    val2         = $('input[type=checkbox][name=PaymentType2]:checked').val();
    val3         = $('input[type=checkbox][name=PaymentType3]:checked').val();

    if(val1 == 1){
        $('#cash :input').prop('disabled',false);
    }else{
        $('#cash :input').prop('disabled',true);
    }

    if(val2 == 2){
        $('#giro :input').prop('disabled',false);
        $(".v-giro").show(300);
    }else{
        $('#giro :input').prop('disabled',true);
        $(".v-giro").hide(300);
    }

    if(val3 == 3){
        $('#card :input').prop('disabled',false);
        $(".v-card").show(300);
    }else{
        $('#card :input').prop('disabled',true);
        $(".v-card").hide(300);
    }
}

function get_invoice_ap(id){
    data_page   = $(".data-page, .page-data").data();
    modul       = data_page.modul;

	vendorid        = $('#CustomerID').val();
    vendorid        = vendorid.split('-');
    crud            = $('[name=crud]').val();
    temp_invoiceno  = $('[name=temp_invoiceno]').val();
    temp_balancedetid = $('[name=temp_balancedetid]').val();
    data_post = {
        VendorID        : vendorid[0],
        method          : crud,
        temp_invoiceno  : temp_invoiceno,
        Type 		    : 1,
        modul           : modul,
        temp_balancedetid : temp_balancedetid,

    }
    tbl = ".table-add-product";
    $(tbl+" tbody").empty();
    $.ajax({
        url : host+"api/invoice",
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
                    setitem(v,id);
                });
                transaction_cek();
                moneyFormat();
                create_format_currency2();
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


function setitem(data,id){
    crud = $('[name=crud]').val();
	if(data){
		checked 	= '';
		detid 		= '';
		invoiceno 	= data.InvoiceNo;
		xinvoiceno 	= data.InvoiceNo;
		date 		= data.Date;
		totalpay 	= parseFloat(data.Total);
        unpaid      = parseFloat(data.Unpaid);
        totalpaid   = 0;
		remark 		= '';

        code            = data.Code;
        balanceid       = data.BalanceID;
        balancedetid    = data.BalanceDetID;
        trans_type      = data.transactionType;
        trans_typetxt   = data.transactionTypetxt;
        trans_typetxt2  = '';
        balancetype     = data.BalanceType;

		xinvoiceno = xinvoiceno.replace(/[/]/g, '-');
        if(crud == "update"){
            if(trans_type == 1){
                xdata = ckdatadetail(invoiceno);
            }else{
                xdata = ckdatadetail2(balancedetid);
            }
            if(xdata[0] == "true"){
                checked     = 'checked';
                detid       = xdata[2];
                totalpaid   = xdata[1];
                unpaid      += totalpaid;
                remark      = xdata[3];
            }
        }

        if(trans_type == 1){
            item        = '<tr class="vd'+xinvoiceno+'">';
            code_check  = xinvoiceno;
            idnya       = invoiceno;
        }else{
            item  = '<tr class="vd'+balancedetid+'">';
            code_check  = balancedetid;
            idnya       = balancedetid;
            if(balancetype == 1){
                trans_typetxt2 = "(Debit)";
            }else{
                trans_typetxt2 = "(Credit)";
            }
        }

		item += '<td><div class="info-warning"></div></td>';
		item += '<td>\
            <input class="cekbox" type="checkbox" onclick="transaction_cek(this)" name="check[]" value="'+code_check+'" '+checked+'>\
            <input class="disabled" type="hidden" name="detid[]" value="'+detid+'" >\
            </td>';

        // invoice no
    	item += '<td>\
            <input class="disabled" type="text" value="'+code+'" >\
            <input class="disabled" type="hidden" name="idnya[]" value="'+idnya+'" >\
            <input class="disabled" type="hidden" name="invoiceno[]" value="'+invoiceno+'" >\
            <input class="disabled" type="hidden" name="balanceid[]" value="'+balanceid+'" >\
            <input class="disabled" type="hidden" name="balancedetid[]" value="'+balancedetid+'" >\
            </td>';

        // transaction Type
        item += '<td>\
            <input class="disabled" type="text" value="'+trans_typetxt+'" >\
            <input class="disabled" type="hidden" name="transaction_type[]" value="'+trans_type+'" >\
            <input class="disabled" type="hidden" name="balancetype[]" value="'+balancetype+'" >\
            </td>';

        // balance Type
        item += '<td>\
            <input class="disabled" type="text" value="'+trans_typetxt2+'" >\
            </td>';

        // invoice date
    	item += '<td>\
            <input class="disabled" type="text" name="invoicedate[]" value="'+date+'" >\
            </td>';

        // total pay
    	item += '<td>\
            <input class="disabled duit" type="text" name="det_totalpay[]" value="'+totalpay+'" >\
            </td>';

        // total pay
        item += '<td>\
            <input class="disabled duit" type="text" name="det_totalunpaid[]" value="'+unpaid+'" >\
            </td>';

        // total paid
    	item += '<td>\
            <input class="duit" type="text" name="det_totalpaid[]" onkeyup="transaction_cek()" value="'+totalpaid+'" placeholder="input payment">\
            </td>';

        // remark
    	item += '<td>\
            <input type="text" name="det_remark[]" placeholder="input remark" value="'+remark+'" >\
            </td>';

        $(tbl+" tbody").append(item);
        if(id){
            $('.vd'+id+' .cekbox').prop('checked', true);
        }
    	$(".disabled").attr("disabled",true);

	}
}

function ckdatadetail(invoiceno){
    status      = false;
    totalpaid   = 0;
    detid       = '';
    remark      = '';
    $.each(data_detailnya, function(i,v){
        if(v.InvoiceNo == invoiceno){
            status      = true;
            totalpaid   = parseFloat(v.Total);
            detid       = v.PaymentDet;
            remark      = v.Remark;
        }
    });

    var data = [status,totalpaid,detid,remark];
    return data;
}

function ckdatadetail2(balancedetid){
    status      = false;
    totalpaid   = 0;
    detid       = '';
    remark      = '';
    $.each(data_detailnya, function(i,v){
        if(v.BalanceDetID == balancedetid){
            status      = true;
            totalpaid   = parseFloat(v.Total);
            detid       = v.PaymentDet;
            remark      = v.Remark;
        }
    });

    var data = [status,totalpaid,detid,remark];
    return data;
}

$(".table-add-product [name=check_all]").click(function(){
    if($(this).is(':checked')){
        $(".table-add-product tbody [type=checkbox]").prop("checked",true);
    } else {
        $(".table-add-product tbody [type=checkbox]").prop("checked",false);
    }
    transaction_cek('element');
});

function transaction_cek(element){
    totalpay 	= 0;
    totalpaid 	= 0;
    total_kolom = 0;
    status_kolom= true;

    list_data       = $(".table-add-product tbody input");
    $.each(list_data,function(i,v){
        if($(v).is(":checked")){
            total_kolom += 1;
            if(total_kolom>100){
                status_kolom = false;
                $(v).attr('checked', false);
            }else{
                val = $(v).val();
                xtotalpay  = $('.vd'+val+' [name="det_totalpay[]"]').val();
                xtotalpay  = removeduit(xtotalpay);

                xtotalpaid = $('.vd'+val+' [name="det_totalpaid[]"]').val();
                xtotalpaid = removeduit(xtotalpaid);

                xbalancetype = $('.vd'+val+' [name="balancetype[]"]').val();
                
                if(xbalancetype == 2){
                    xtotalpay  = parseFloat("-"+xtotalpay);
                    xtotalpaid = parseFloat("-"+xtotalpaid);
                }

                totalpay    += xtotalpay;
                totalpaid   += xtotalpaid;
            }
        }
    });

    if(!status_kolom){
        swal('','Data item max 100','warning');
    }

    $('[name=TotalPay]').val(totalpay);
    $('[name=TotalPaid]').val(totalpaid);
    run_function = 'transaction_cek()';
    if(element){
        create_format_currency2();
    }
}

function cekrow(element){
   
    total_payment1  = 0;
    total_payment2  = 0;
    total_payment3  = 0;

    ck_payment1     = $('[name=PaymentType1]');
    ck_payment2     = $('[name=PaymentType2]');
    ck_payment3     = $('[name=PaymentType3]');
    pay_cash        = $('[name=pay_cash]').val();
    pay_giro        = $('[name=pay_giro]').val();
    pay_credit      = $('[name=pay_credit]').val();

    if(ck_payment1.is(":checked")){
        total_payment1 = removeduit(pay_cash);
    }
    if(ck_payment2.is(":checked")){
        total_payment2 = removeduit(pay_giro);
    }
    if(ck_payment3.is(":checked")){
        total_payment3 = removeduit(pay_credit);
    }

    total = total_payment1 + total_payment2 + total_payment3;

    $('[name=grandtotal]').val(total);
    run_function = 'cekrow()';
    if(element){
        create_format_currency2();
    }

}
$(".gt").keyup(function(){
    cekrow();
});

function coa_bayar1(TipeBayar,data){
    coa_tb          = $(".coa_select"); 
    coa             = $(coa_tb).data();
    coa_tipe_op     = $(".coa_select option");
    $("#pay_paymentmethod1").empty();
    item = '<option value="none">Select COA</option>';
    $('#pay_paymentmethod1').append(item);
    $.each(coa_tipe_op,function(i,v){
        dt = $(v).data();
        item = '<option value="'+dt.id+'">'+dt.code+" - "+dt.name+'</option>';
        code = String(dt.code);

        // if(TipeBayar == dt.payment && dt.level == 4 ){
        //     $("#pay_paymentmethod").append(item);
        // } else if(TipeBayar == dt.payment && dt.level == 4){
        //     $("#pay_paymentmethod").append(item);
        // }else if(TipeBayar == dt.payment && dt.level == 4){
        //     $("#pay_paymentmethod").append(item);
        // }

        if(dt.level == 4){
            $("#pay_paymentmethod1").append(item);
        }
    });
    if(data){
        $('#pay_paymentmethod1').val(data);
    }else if(ID_Cash){
        $('#pay_paymentmethod1').val(ID_Cash);
    }
    $('.selectpicker').eq(0).selectpicker('refresh');
}
function coa_bayar2(TipeBayar,data){
    coa_tb          = $(".coa_select"); 
    coa             = $(coa_tb).data();
    coa_tipe_op     = $(".coa_select option");
    $("#pay_paymentmethod2").empty();
    item = '<option value="none">Select COA</option>';
    $('#pay_paymentmethod2').append(item);
    $.each(coa_tipe_op,function(i,v){
        dt = $(v).data();
        item = '<option value="'+dt.id+'">'+dt.code+" - "+dt.name+'</option>';
        code = String(dt.code);

        // if(TipeBayar == dt.payment && dt.level == 4 ){
        //     $("#pay_paymentmethod").append(item);
        // } else if(TipeBayar == dt.payment && dt.level == 4){
        //     $("#pay_paymentmethod").append(item);
        // }else if(TipeBayar == dt.payment && dt.level == 4){
        //     $("#pay_paymentmethod").append(item);
        // }

        if(dt.level == 4){
            $("#pay_paymentmethod2").append(item);
        }
    });
    if(data){
        $('#pay_paymentmethod2').val(data);
    }else if(ID_Giro){
        $('#pay_paymentmethod2').val(ID_Giro);
    }
    $('.selectpicker').eq(2).selectpicker('refresh');
}
function coa_bayar3(TipeBayar,data){
    coa_tb          = $(".coa_select"); 
    coa             = $(coa_tb).data();
    coa_tipe_op     = $(".coa_select option");
    $("#pay_paymentmethod3").empty();
    item = '<option value="none">Select COA</option>';
    $('#pay_paymentmethod3').append(item);
    $.each(coa_tipe_op,function(i,v){
        dt = $(v).data();
        item = '<option value="'+dt.id+'">'+dt.code+" - "+dt.name+'</option>';
        code = String(dt.code);

        // if(TipeBayar == dt.payment && dt.level == 4 ){
        //     $("#pay_paymentmethod").append(item);
        // } else if(TipeBayar == dt.payment && dt.level == 4){
        //     $("#pay_paymentmethod").append(item);
        // }else if(TipeBayar == dt.payment && dt.level == 4){
        //     $("#pay_paymentmethod").append(item);
        // }

        if(dt.level == 4){
            $("#pay_paymentmethod3").append(item);
        }
    });
    if(data){
        $('#pay_paymentmethod3').val(data);
    }else if(ID_Bank){
        $('#pay_paymentmethod3').val(ID_Bank);
    }
    $('.selectpicker').eq(1).selectpicker('refresh');
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
            $(".disabled").attr("disabled",true);
            if(data.page){
                $('#modal-add-serial').modal("hide");
            } else {
                if(data.status) //if success close modal and reload ajax table
                {
                    swal('',data.message,'success');
                    $('#modal').modal("hide");
                    reload_table();
                }
               else
                {
                    check_payment_type();
                    for (var i = 0; i < data.inputerror.length; i++)
                    {
                        list    = data.list[i];
                        if(list == 'list'){
                            item = '<i class="icon fa-exclamation-triangle" title="'+data.error_string[i]+'" style="cursor:pointer;padding:5px;"></i>';
                            $('.vd'+data.inputerror[i]+' .info-warning').append(item);
                        }
                        else if(data.inputerror[i] == "pay_paymentmethod1"){
                            $('.vpmethod').addClass('has-error'); 
                            $('.vpmethod .help-block').text(data.error_string[i]); //select span help-block class set text error string
                        }

                        else if(data.inputerror[i] == "pay_paymentmethod2"){
                            $('.vpmethod1').addClass('has-error'); 
                            $('.vpmethod1 .help-block').text(data.error_string[i]); //select span help-block class set text error string
                        }

                        else if(data.inputerror[i] == "pay_paymentmethod3"){
                            $('.vpmethod2').addClass('has-error'); 
                            $('.vpmethod2 .help-block').text(data.error_string[i]); //select span help-block class set text error string
                        }

                        else{
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
            $(".disabled").attr("disabled",true);
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
        
    $('#modal .modal-title').text(title_page+' '+language_app.btn_detail);

    url = host + "payment-payable-view/"+id+"?page=print";
    action_print_button(url);
}

function view_print_data(id,page){
    if(page == "print"){
        page = "print";
    }else{
        page = "payment-ap";
    }
    $('#modal-print').modal('show');
    $('#modal-print .modal-title').text(title_page+' '+language_app.btn_detail);
    url = host + "payment-payable-view/"+id+"?page="+page;

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
            list            = data.list;
            detail          = data.detail;
            data_detailnya  = detail;

            $('[name=PaymentNo]').val(list.PaymentNo);
            $('[name=temp_paymentno]').val(list.PaymentNo);
            $('[name=Remark]').val(list.Remark);
            $('[name=CustomerID]').val(list.VendorID+"-"+list.vendorName);
            $('[name=CustomerName]').val(list.vendorName);
            $("input[name=PaymentType][value='"+list.PaymentType+"']").prop('checked', true);
            ckPaymentType(list.PaymentMethod);
            
            temp_invoiceno      = [];
            temp_paymentdet     = [];
            temp_balancedetid   = [];
            
            $.each(detail,function(i,v){
                temp_paymentdet.push(v.PaymentDet);
                if(v.Type == 1){
                    temp_invoiceno.push(v.InvoiceNo);
                }else{
                    temp_balancedetid.push(v.BalanceDetID);
                }
            });
            $('[name=temp_paymentdet]').val(temp_paymentdet);
            $('[name=temp_invoiceno]').val(temp_invoiceno);
            $('[name=temp_balancedetid]').val(temp_balancedetid);
            get_invoice_ap();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);
        }
    });
}

// end edit

function get_detail_invoice(id){
    url = host + "invoice_ap/ajax_edit/"+id;
    data_post = {
        page : "payment_ap",
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
            if(list.PaymentStatus == 0){
                tambah();
                $('[name=CustomerID]').val(list.VendorID+"-"+list.vendorName);
                $('[name=CustomerName]').val(list.vendorName);
                get_invoice_ap(id)
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

var ID_Cash;
var ID_Giro;
var ID_Bank;
function get_coa_setting(){
    url = host + "api/get_coa_setting";

    data_post = {
        "AC - Cash" : "",
        "AC - Giro" : "",
        "AC - Transfer" : "",
    }
    $.ajax({
        url : url,
        type: "POST",    
        data: data_post,
        dataType: "JSON",
        success: function(data){
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            $.each(data.list,function(k,v){
                if(v.Code == "AC - Cash"){
                    ID_Cash = v.nValue;
                }else if(v.Code == "AC - Giro"){
                    ID_Giro = v.nValue;
                }else if(v.Code == "AC - Transfer"){
                    ID_Bank = v.nValue;
                }
            });
        },
        error: function (jqXHR, textStatus, errorThrown){
            console.log(jqXHR.responseText);
        }
    });
}

//dashboard
function create_chart(){
    var ctx = document.getElementById("purchase_open");
    chart_purchase_open = new Chart(ctx,{});

    var ctx = document.getElementById("purchase_overdude");
    chart_purchase_overdude = new Chart(ctx,{});

    var ctx = document.getElementById("purchase_payment");
    chart_purchase_payment = new Chart(ctx,{});

}
function load_data(page = "")
{
    url_post    =  host+"dashboard/dashboard";
    Check       = $("[name=Check]:checked").val();
    StartDate   = $("[name=fStartDate]").val();
    EndDate     = $("[name=fEndDate]").val();

    // untuk data diagram
    purchase_openx          = $('#ul-purchase-open .li-active').data();
    purchase_overdudex      = $('#ul-purchase-overdude .li-active').data();
    purchase_paymentx       = $('#ul-purchase-payment .li-active').data();

    data_post   = {
        Check           : Check,
        StartDate       : StartDate,
        EndDate         : EndDate,
        purchase_open   : purchase_openx.type,
        purchase_overdude   : purchase_overdudex.type,
        purchase_payment    : purchase_paymentx.type,
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
            
            if(page == "purchase_open"){
                purchase_open(data.purchase_open);
            }else if(page == "purchase_overdude"){
                purchase_overdude(data.purchase_overdude);
            }else if(page == "purchase_payment"){
                purchase_payment(data.purchase_payment);
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
   purchase_open(data.purchase_open);
   purchase_overdude(data.purchase_overdude);
   purchase_payment(data.purchase_payment);
}

function purchase_open(data){
    var ctx = document.getElementById("purchase_open");
    chart_purchase_open.destroy();
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

    chart_purchase_open = new Chart(ctx, {
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

    $('.total_purchase_open').text(total);
}

function purchase_overdude(data){
    var ctx = document.getElementById("purchase_overdude");
    chart_purchase_overdude.destroy();
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

    chart_purchase_overdude = new Chart(ctx, {
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

    $('.total_purchase_overdude').text(total);
}

function purchase_payment(data){
    var ctx = document.getElementById("purchase_payment");
    chart_purchase_payment.destroy();
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

    chart_purchase_payment = new Chart(ctx, {
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

    $('.total_purchase_payment').text(total);
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