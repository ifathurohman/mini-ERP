var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host 			= window.location.origin+'/';
var url 			= window.location.href;
var page_login 		= host + "main/login";
var page_register 	= host + "main/register";
var url_list_pay 	= host + "selling/ajax_list/";
var url_edit_pay    = host + "selling/ajax_edit/";
var url_cancel_pay 	= host + "selling/cancel/";
var url_save_pay 	= host + "payment_selling/save_pay";
var pay_table;

$(document).ready(function() {
    //datatables
});

// pembayaran 
function paymnet_selling(id,page){
	$('#form-pembayaran [name=pay_page]').val(page);
	$('#form-pembayaran [name=pay_sellno]').val(id);
    sn_status = $('#form-pembayaran [name=sn_status]').val();
    if(sn_status == "true"){
        if(page == "selling"){
        	$("#modal").modal("hide");
        	$('#form-pembayaran [name=pay_type]').val(3);
        	$('.transaction').text('Selling No');
        }
        pay_back();
        $("#modal-pembayaran").modal("show");
        $('#modal-pembayaran .modal-title').text('Payment List'); // Set Title to Bootstrap modal title
        filter_table_pay();
    }else{
        swal('','Please add serial number', 'warning');
    }
}

function filter_table_pay(){
	id 		= $('#form-pembayaran [name=pay_sellno]').val();
	page 	= $('#form-pembayaran [name=pay_page]').val();

	data_post = {
		id 		: id,
		page  	: page,
	}

	pay_table = $('#pay_table').DataTable();
    pay_table.clear();
    $.ajax({
        url : host+"api/payment_list",
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data){
            app     = data.app;
            $.each(data.list_data, function(i, v) {
                no  		= i + 1;
                paymentno   = v.PaymentNo;
                date        = v.Date;
                totalawal   = v.TotalAwal;
                total     	= v.Total;

                btn = '<div class="btn-group btn-group-xs" aria-label="Basic example" role="group">\
                        <a href="javascript:void(0)" type="button" class="btn btn-success" title="View" onclick="view_print('+"'"+paymentno+"','payment'"+')">\
                        <i class="icon fa-search" aria-hidden="true"></i></a>\
                        <a href="javascript:void(0)" type="button" class="btn btn-success" title="Print Receive" onclick="view_print('+"'"+paymentno+"','struk'"+')">\
                        <i class="icon fa-print" aria-hidden="true"></i></a>\
                    </div>';

                item = '<tr>';
                item += '<td>'+no+'</td>';
                item += '<td>'+paymentno+'</td>';
                item += '<td>'+date+'</td>';
                item += '<td>'+totalawal+'</td>';
                item += '<td>'+total+'</td>';
                item += '<td>'+btn+'</td>';
                item += '</tr>';

	    		pay_table.row.add( $(item)[0] ).draw();
          	});
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
           console.log("error get list product");
           console.log(jqXHR.responseText);
        }
    });
}

function pay_close(){
	id 		= $('#form-pembayaran [name=pay_sellno]').val();
	page 	= $('#form-pembayaran [name=pay_page]').val();
	if(page == "selling"){
		$("#modal-pembayaran").modal("hide");
		view(id);
	}
}

function pay_tambah(){
	id 		= $('#form-pembayaran [name=pay_sellno]').val();
	page 	= $('#form-pembayaran [name=pay_page]').val();

	pay_reset_form();
	$('.vpay-list').hide(300);
    $('.vpay-form, .paybtnsave').show(300);

    data_post = {
		id 		: id,
		page  	: page,
	}

	$.ajax({
        url : host+"api/payment_add",
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data){
            if(page == "selling"){
            	selling = data.data;
            	$('#pay_total_ar').val(parseFloat(data.total));
            	$('#pay_unpayment').val(parseFloat(data.unpayment));
                if(selling.VendorID){
                    customer = selling.VendorID+"-"+selling.customerName;
                    $('#pay_customer').val(customer);
                }
            	moneyFormat();
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
           console.log("error get list product");
        }
    });
}

function pay_back(){
	$('.vpay-list').show(300);
    $('.vpay-form, .paybtnsave').hide(300);
}

$('#pay_paymentType').on('change', function(){
	val = $(this).val();
	check_payment_type(val);
})
function check_payment_type(val){
	if(val == 0){
		$('.vpay-giro, .vpay-transfer, .pay_no_cash').hide(300);
	}else if(val == 1){
		$('.vpay-giro, .pay_no_cash').show(300);	
		$('.vpay-transfer').hide(300);
	}else if(val == 2){
		$('.vpay-transfer, .pay_no_cash').show(300);	
		$('.vpay-giro').hide(300);
	}
	coa_bayar(val);
}

function pay_reset_form(){
	$('#pay_paymentType').val(0);
	check_payment_type(0);
	$('.resetinput').val('');
}

function SumPayment(){
	payment_cost 	= $('#pay_payment').val();
	payment_cost 	= removeduit(payment_cost);
	// pay_additional 	= $('#pay_additional').val();
	// pay_additional 	= removeduit(pay_additional);

	// check value 
    payment_cost    = checkFloatInput(payment_cost);
    // pay_additional 	= checkFloatInput(pay_additional);

    // total = parseFloat(payment_cost) + parseFloat(pay_additional);

    $('#pay_total').val(parseFloat(payment_cost));
    moneyFormat();
}

function pay_save(){
	$("#form-pembayaran input").attr("disabled",false);
    $('.save').text('saving...'); //change button text
    $('.save').attr('disabled',true); //set button disable
    $('.form-group, .input-group').removeClass('has-error'); // clear error class
    $('.input-error').removeClass('input-error');
    $('.help-block').empty();

    var url;
    url = url_save_pay;

    var form        = $('#form-pembayaran')[0]; // You need to use standard javascript object here
    var formData    = new FormData(form);
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
            if(data.status) //if success close modal and reload ajax table
            {
                swal('',data.message,'success');
                filter_table_pay();
                pay_back();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++)
                {
                	type = data.type[i];
                	if(type == 'select'){
                		$('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error');
                	}else{
                		$('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                    	$('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                	}
                }
            }  
            $('.save').text('save'); //change button text
            $('.save').attr('disabled',false); //set button enable
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            console.log(jqXHR.responseText);
            $('.save').text('save'); //change button text
            $('.save').attr('disabled',false); //set button enable
        }
    });
}

function coa_bayar(TipeBayar,data){
	coa_tb          = $(".coa_select"); 
    coa             = $(coa_tb).data();
    coa_tipe_op     = $(".coa_select option");
    $("#pay_paymentmethod").empty();
    item = '<option value="none">Pilih COA</option>';
    $('#pay_paymentmethod').append(item);
    $.each(coa_tipe_op,function(i,v){
        dt = $(v).data();
        item = '<option value="'+dt.id+'">'+dt.code+" - "+dt.name+'</option>';
        code = String(dt.code);
        if(TipeBayar == dt.payment && dt.level == 4 ){
            $("#pay_paymentmethod").append(item);
        } else if(TipeBayar == dt.payment && dt.level == 4){
        	$("#pay_paymentmethod").append(item);
        }else if(TipeBayar == dt.payment && dt.level == 4){
        	$("#pay_paymentmethod").append(item);
        }
    });
    $('.selectpicker').selectpicker('refresh');
}