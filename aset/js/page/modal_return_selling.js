var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host 			= window.location.origin+'/';
var url 			= window.location.href;
var page_login 		= host + "main/login";
var page_register 	= host + "main/register";
var url_list_return 	= host + "selling/ajax_list/";
var url_edit_return    = host + "selling/ajax_edit/";
var url_cancel_return 	= host + "selling/cancel/";
var url_save_return 	= host + "return_selling/save_return";
var return_table;

$(document).ready(function() {
    //datatables
});

// return
function return_selling(id,page){
	$('#form-return [name=return_page]').val(page);
	$('#form-return [name=return_sellno]').val(id);
    payment = $('#form-return [name=return_pay]').val();
    if(payment>0){
        if(page == "selling"){
        	$("#modal").modal("hide");
        	$('#form-return [name=return_type]').val(2);
        	$('.transaction').text('Selling No');
        	$('.vreturn-customer').text("Customer");
        }
        return_back();
        $("#modal-return").modal("show");
        $('#modal-return .modal-title').text('Return List'); // Set Title to Bootstrap modal title
        filter_table_return();
    }else{
        swal('','No Payment', 'warning');
    }
}

function return_close(){
	id 		= $('#form-return [name=return_sellno]').val();
	page 	= $('#form-return [name=return_page]').val();
	if(page == "selling"){
		$("#modal-return").modal("hide");
		view(id);
	}
}

function return_back(){
	$('.vreturn-list').show(300);
    $('.vreturn-form, .returnbtnsave').hide(300);
}

function filter_table_return(){
	id 		= $('#form-return [name=return_sellno]').val();
	page 	= $('#form-return [name=return_page]').val();

	data_post = {
		id 		: id,
		page  	: page,
	}

	return_table = $('#return_table').DataTable();
    return_table.clear();
    $.ajax({
        url : host+"api/return_list",
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data){
            app     = data.app;
            $.each(data.list_data, function(i, v) {
                no  		= i + 1;
                returnno 	= v.ReturNo;
                date 		= v.Date;
                vendor 		= v.vendorName,

                btn = '<div class="btn-group btn-group-xs" aria-label="Basic example" role="group">\
                        <a href="javascript:void(0)" type="button" class="btn btn-success" title="View" onclick="view_return('+"'"+returnno+"','selling'"+')">\
                        <i class="icon fa-search" aria-hidden="true"></i></a>\
                        <a href="javascript:void(0)" type="button" class="btn btn-success" title="View print" onclick="view_print('+"'"+returnno+"','return'"+')">\
                        <i class="icon fa-print" aria-hidden="true"></i></a>\
                    </div>';

                item = '<tr>';
                item += '<td>'+no+'</td>';
                item += '<td>'+returnno+'</td>';
                item += '<td>'+date+'</td>';
                item += '<td>'+vendor+'</td>';
                item += '<td>'+btn+'</td>';
                item += '</tr>';

	    		return_table.row.add( $(item)[0] ).draw();
          	});
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
           console.log("error get list product");
           console.log(jqXHR.responseText);
        }
    });
}

function return_tambah(){
	$('.vreturn-list').hide(300);
    $('.vreturn-form, .returnbtnsave').show(300);
    reset_return_form();
    add_return_data();
}

function add_return_data(){
    id      = $('#form-return [name=return_sellno]').val();
    page    = $('#form-return [name=return_page]').val();

    data_post = {
        id      : id,
        page    : page,
    }

    $('.table-return-product tbody').empty();
    $.ajax({
        url : host+"api/return_add",
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data){
            if(page == "selling"){
                selling = data.data;
                detail  = data.detail;
                if(selling.VendorID){
                    $('#form-return [name=return_customer]').val(selling.VendorID+"-"+selling.customerName);
                }
                no = 0;
                $.each(detail, function(k,v){
                    no += 1;
                    item  = '<tr class="returnrowid_'+no+'">';
                    item += '<td><div class="info-warning"></div></td>';
                    item += '<td class="text-center">\
                        <input type="checkbox" name="recekbox[]" class="cekbox" value="'+no+'">\
                        <input type="hidden" name="reproduct_id[]" value="'+v.ProductID+'">\
                        <input type="hidden" name="reproduct_price[]" value="'+v.Price+'">\
                        <input type="hidden" name="reproduct_total[]" value="'+v.TotalPrice+'">\
                        <input type="hidden" name="reproduct_konv[]" value="'+v.Conversion+'">\
                        <input type="hidden" name="reproduct_type[]" value="'+v.Type+'">\
                        <input type="hidden" name="reunitid[]" value="'+v.UnitID+'">\
                        <input type="hidden" name="reselldet[]" value="'+v.SellDet+'">\
                        <input type="hidden" name="reqty[]" value="'+v.Qty+'">\
                        </td>';
                    item += '<td>'+v.product_code+'</td>';
                    item += '<td>'+v.product_name+'</td>';
                    item += '<td>'+'<input type="text" name="reproduct_qty[]" data-class="p_min_qty" class="return_input p_min_qty bg-abu" onkeyup="decimalFormat(this)" placeholder="input qty" min="0">'+'</td>';
                    item += '<td>'+v.unit_name+'</td>';
                    item += '<td>'+v.Conversion+'</td>';
                    item += '<td>'+v.Price+'</td>';
                    item += '<td>'+'<input type="text" class="bg-abu return_input" name="reproduct_remark[]" placeholder="input remark">'+'</td>';
                    item += '</tr>';

                    $(".table-return-product tbody").append(item);
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
           console.log("error get list product");
           console.log(jqXHR.responseText);
        }
    });
}

function view_return(id,page){
    $('.vreturn-list').hide(300);
    $('.vreturn-form, .returnbtnsave').show(300);
    reset_return_form();

    $("#form-return input, #form-return textarea, #form-return select").attr("disabled",true);
    $('#form-return [name=returno]').val(id);

    page    = $('#form-return [name=return_page]').val();

    data_post = {
        id      : id,
        page    : page,
    }

    $('.table-return-product tbody').empty();
    $.ajax({
        url : host+"api/return_view",
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data){
            console.log(data)
            if(page == "selling"){
                selling = data.data;
                detail  = data.detail;
                no = 0;
                $.each(detail, function(k,v){
                    btn_serial  = '<a  onclick="add_serial('+"'retur','"+v.ReturDet+"'"+')" class="btn-serial-v" aria-hidden="true" style="cursor:pointer;padding:5px;">Add Serial</a>';

                    no += 1;
                    item  = '<tr class="returnrowid_'+no+'">';
                    item += '<td><div class="info-warning"></div></td>';
                    item += '<td class="text-center"></td>';
                    item += '<td>'+v.product_code+'</td>';
                    item += '<td>'+v.product_name+'</td>';
                    item += '<td>'+v.Qty+'</td>';
                    item += '<td>'+v.unit_name+'</td>';
                    item += '<td>'+v.Conversion+'</td>';
                    item += '<td>'+v.Price+'</td>';
                    item += '<td>'+v.Remark+'</td>';
                    item += '<td>'+btn_serial+'</td>';
                    item += '</tr>';

                    $(".table-return-product tbody").append(item);
                    console.log('message');
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
           console.log("error get list product");
           console.log(jqXHR.responseText);
        }
    });

}

$(".returncheckboxall").click(function(){
    if($(this).is(":checked")){
        $(".cekbox").prop("checked",true);
    } else {
        $(".cekbox").prop("checked",false);
    }
});

function return_save(){
	$("#form-return input").attr("disabled",false);
    $('.save').text('saving...'); //change button text
    $('.save').attr('disabled',true); //set button disable
    $('.form-group, .input-group').removeClass('has-error'); // clear error class
    $('.input-error').removeClass('input-error');
    $('.help-block, .info-warning').empty();

    var url;
    url = url_save_return;

    var form        = $('#form-return')[0]; // You need to use standard javascript object here
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
                filter_table_return();
                return_back();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++)
                {
                	type = data.type[i];
                	if(type == 'select'){
                		$('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error');
                	}else if(type == "list"){
                		item = '<i class="icon fa-exclamation-triangle" title="'+data.error_string[i]+'" style="cursor:pointer;padding:5px;"></i>';
                        $(data.inputerror[i]+' .info-warning').append(item);
                	}
                	else{
                		$('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                    	$('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                	}

                	if(data.message){
                		swal('',data.message,'warning');
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

function reset_return_form(){
    data_page   = $('.page-data').data();
    date        = data_page.date;
    $('.returncheckboxall, .cekbox').prop('checked', false);
    $('.help-block, .info-warning').empty();
    $('.return_input, #form-return [name=returno]').val('');
    $('#form-return [name=returndate]').val(date);

    $("#form-return input, #form-return textarea, #form-return select").attr("disabled",false);

}