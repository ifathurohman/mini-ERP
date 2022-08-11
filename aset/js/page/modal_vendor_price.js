var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host 			= window.location.origin+'/pipesys_qa/';
var url 			= window.location.href;
var v_modal 		= '#modal-vendor-price';
var v_form 			= '#form-vendor-price';
var v_formTag 		= 'vendor_price_';
var n_selling_price = 0;
var n_purchase_price = 0;
var table_vendor_price;

$(document).ready(function() {
    // vendor_filter_table();
});

function modal_vendor_price(type,id){
	$(v_modal).modal('show'); // show bootstrap modal
    $(v_modal+' .modal-title').text('List Group Price'); // Set Title to Bootstrap modal title

    tag = $('.vvd-'+id).data();

    $("[name=vendor_price_page_type]").val(type);
    $("[name=vendor_price_id]").val(id);

    $(v_modal+' .div-list').show(300);
    $(v_modal+' .div-form, #modal-vendor-price .save, #modal-vendor-price .btn-back').hide(300);
    vendor_filter_table();
    cek_row('non');
}

var id_row = 0;
function add_vendor_price(){
	$(v_modal+' .modal-title').text('Add Group Price'); // Set Title to Bootstrap modal title
	// $(v_modal+' .div-list').hide(300);
 //    $(v_modal+' .div-form, #modal-vendor-price .save, #modal-vendor-price .btn-back').show(300);
 //    $(v_form)[0].reset(); // reset form on modals
 //    $('.form-group').removeClass('has-error'); // clear error class
 //    $('.help-block').empty(); // clear error string
 //    $(v_modal+' [name='+v_formTag+'crud]').val("insert");
 //    $(v_modal+' .disabled').attr('disabled',true);
    if ( $.fn.DataTable.isDataTable('#table-vendor-price') ) {
      $('#table-vendor-price').DataTable().destroy();
    }

    id_row += 1;
    item  = '<tr class="vrowid rowid_'+id_row+'" data-row="'+id_row+'">';
    item += '<td></td>';
    item += '<td class="remove_row"><div class="info-warning"></div></td>';
    item += '<td><input type="text" name="product_group[]" onkeyup="keyup_vendor_price('+id_row+',this)" class="uppercase autocomplete_vendor_price p_group product_modal"></td>';
    item += '<td>\
                <input type="hidden" name="product_rowid[]" value="'+id_row+'">\
                <input type="hidden" name="product_purchase[]" class="p_purchaseprice">\
                <input type="hidden" name="product_selling[]" class="p_sellprice">\
                <input type="hidden" name="product_id[]" class="p_id">\
                <input type="text" name="product_code[]" onclick="product_modal('+id_row+')" placeholder="Select Product" class="autocomplete_product pointer p_code product_modal readonly">\
            </td>'
    item += '<td>\
                <input type="text" name="product_name[]" class="autocomplete_product p_name product_modal disabled">\
            </td>'
    item += '<td>\
                <input type="text" name="product_unit[]" class="autocomplete_product p_unit product_modal disabled">\
            </td>'
    item += '<td>\
                <select name="product_p_type[]" onchange="changeType(this)" data-rowid="'+id_row+'" class="autocomplete_product p_p_type product_modal">\
                    <option value="0">Select Type Price</option>\
                    <option value="1">Purchases Price</option>\
                    <option value="2">Selling Price</option>\
                </select>\
            </td>'
    item += '<td>\
                <input type="text" name="product_price[]" class="autocomplete_product p_price product_modal disabled duit">\
            </td>'
    item += '<td>\
                <input type="text" name="product_total[]" class="autocomplete_product p_total product_modal duit">\
            </td>'
    item += '<td><i  onclick="vendor_delete_row(this)" class="icon fa-remove remove_row" aria-hidden="true" style="cursor:pointer;padding:5px;"></i></td>';
    item  += '</tr>';

    if($('#table-vendor-price tbody tr:first').length>0){
        $('#table-vendor-price tbody tr:first').before(item);
    }else{
        $('#table-vendor-price tbody').append(item);
    }
    
    $(v_modal+" .disabled").attr('disabled',true);
    $(v_modal+" .readonly").attr('readonly',true);
    moneyFormat();
    cek_row();
}

function back_vendor_price(){
	$(v_modal+' .modal-title').text('List Customer Price'); // Set Title to Bootstrap modal title
	$(v_modal+' .div-list').show(300);
    $(v_modal+' .div-form, #modal-vendor-price .save, #modal-vendor-price .btn-back').hide(300);
    $(v_modal+' [name='+v_formTag+'crud]').val("");
}

function reload_table_vendor()
{
    table_vendor_price.ajax.reload(null,false); //reload datatable ajax
}

function vendor_filter_table(){
    data_post = {
        a : '1',
    }
    url         = host+"main/vendor_price";
    table_vendor_price = $('#table-vendor-price').DataTable({
        "destroy"   : true,
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "searching": false, //Feature Search false
        "bLengthChange": false,
        "ordering": false,
        "order": [], //Initial no order.
         "language": {                
            "infoFiltered": ""
        },
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
            "targets": [ -1,0], //last column
            "orderable": false, //set not orderable
        },],
    });
}

function vendor_delete_row(a){
    $(a).closest('tr').remove();
    cek_row();
}

function changeType(a){
    // p_purchaseprice
    // p_sellprice
    dt     = $(a).data();
    idrow  = dt.rowid;
    
    val     = $(a).val();
    code    = $('.rowid_'+idrow+" .p_code").val();

    hasil = 0;
    if(val == 1 && code != ''){
        hasil = $('.rowid_'+idrow+" .p_purchaseprice").val();
        hasil = parseFloat(hasil);
        
    }else if(val == 2 && code != ''){
        hasil = $('.rowid_'+idrow+" .p_sellprice").val();
        hasil = parseFloat(hasil);
    }

    $('.rowid_'+idrow+" .p_price").val(hasil);
    $('.rowid_'+idrow+" .p_total").val(hasil);
    moneyFormat();
    create_format_currency2();
}

function cek_row(page){
    $('.btn-cancel').hide();
    if($(v_modal+' .vrowid').length>0){
        $("[name=vendor_price_crud]").val("insert");
        $(v_modal+' .modal-title').text('Add Customer Price'); // Set Title to Bootstrap modal title
        $(v_modal+' .save,'+v_modal+" .btn-add").show(0);
        $(v_modal+" .btn-edit, .btn-active").hide(0);
        $("."+v_formTag+"check").hide();
    }else{
        $(v_modal+' .modal-title').text('List Customer Price'); // Set Title to Bootstrap modal title
        $("[name=vendor_price_crud]").val("");
        $(v_modal+' .save').hide(0);
        $("."+v_formTag+"check,"+v_modal+" .btn-edit, "+v_modal+" .btn-add, .btn-active").show();
        if(page != "none"){
            vendor_filter_table();
        }
    }
}

function save_vendor_price(){
    $(v_form+" input").attr("disabled",false);
    $('#btnSave, .save').text('saving...'); //change button text
    $('#btnSave, .save').attr('disabled',true); //set button disable
    $('.info-warning').empty();
    $('.form-group, .input-group').removeClass('has-error'); // clear error class
    $('.input-error').removeClass('input-error');
    $('.help-block').empty();

    crud = $("[name=vendor_price_crud]").val();

    if(crud == "insert"){
        url = host+"main/save_vendor_price";
    }else{
        url = host+"main/update_vendor_price";
    }
    var form        = $(v_form)[0]; // You need to use standard javascript object here
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
            $(".disabled").attr("disabled",true);
            if(data.status) //if success close modal and reload ajax table
            {
                $(v_modal+" .vrowid").remove();
                swal('',data.message,'success');
                vendor_filter_table();
                cek_row('non');
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    list    = data.list[i];
                    if(list == 'list'){
                        item = '<i class="icon fa-exclamation-triangle" title="'+data.error_string[i]+'" style="cursor:pointer;padding:5px;"></i>';
                        $('.rowid_'+data.inputerror[i]+' .info-warning').append(item);
                    }
                    else{
                        $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                        $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                    }
                }
                if(data.message){
                    swal('',data.message,'warning');
                }else{
                    swal('','incomplete form', 'warning');
                }
            }
            $('#btnSave, .save').text('save'); //change button text
            $('#btnSave, .save').attr('disabled',false); //set button enable
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            $('#btnSave, .save').text('save'); //change button text
            $('#btnSave, .save').attr('disabled',false); //set button enable
            alert('Error adding / update data');
            console.log(jqXHR.responseText);
        }
    });
}

$(v_form+" [name=vendor_price_checkall]").click(function(){
    if($(this).is(':checked')){
        $("#table-vendor-price tbody [type=checkbox]").prop("checked",true);
    } else {
        $("#table-vendor-price tbody [type=checkbox]").prop("checked",false);
    }
});

function edit_vendor_price(){
    input = $(v_form+' .data-list');
    no = 0;
    var ID = [];
    $.each(input,function(i,v){
        check = $(v_form+' .data-list [type=checkbox]').eq(i);
        if($(check).is(":checked")){
           no += 1;
           ID.push(check.val());
        }
    });

    if(no>0){
        set_data_edit(ID);
    }else{
        swal('','Please select data checkbox','warning');
    }
}

function set_data_edit(ID){
    $(v_modal+' .modal-title').text('Edit Group Price'); // Set Title to Bootstrap modal title
    $("[name=vendor_price_crud]").val("update");
    $(v_modal+' .save, .btn-cancel').show(0);
    $(v_modal+" .btn-add").hide(0);
    $.each(ID,function(i,v){
        $('.data-vendor'+v+' .list-data, .btn-active').hide();
        $('.data-vendor'+v+' .product_modal').remove();

        tagdata = $('.data-vendor'+v).data();

        ProductCustomerID   = tagdata.id;
        id                  = tagdata.id;
        group_name          = tagdata.group_name;
        producid            = tagdata.producid;
        product_code        = tagdata.product_code;
        product_name        = tagdata.product_name;
        product_unit        = tagdata.product_unit;
        price_type          = tagdata.price_type;
        price               = tagdata.price;
        price_sell          = tagdata.price_sell;
        sell_price          = tagdata.sell_price;
        purchase_price      = tagdata.purchase_price;

        if(price_type == 1){
            purchase_price  = price;
        }else if(price_type == 2){
            sell_price      = price;
        }

        item_warning        = '<div class="info-warning product_modal"></div>';
        item_group_name     = '<input type="text" value="'+group_name+'" name="product_group[]" onkeyup="keyup_vendor_price('+id+',this)" class="uppercase autocomplete_vendor_price p_group product_modal">';
        item_product_code   = '<input type="hidden" value="'+parseFloat(purchase_price)+'" name="product_purchase[]" class="p_purchaseprice product_modal">\
                <input type="hidden" value="'+parseFloat(sell_price)+'" name="product_selling[]" class="p_sellprice product_modal">\
                <input type="hidden" value="'+producid+'" name="product_id[]" class="p_id">\
                <input type="hidden" name="data_vendor_ID[]" value="'+id+'"/>\
                <input type="text" value="'+product_code+'" name="product_code[]" onclick="product_modal('+id+')" placeholder="Select Product" class="autocomplete_product pointer p_code product_modal readonly">';
        item_product_name   = '<input type="text" value="'+product_name+'" name="product_name[]" class="autocomplete_product p_name product_modal disabled">';
        item_product_unit   = '<input type="text" value="'+product_unit+'" name="product_unit[]" class="autocomplete_product p_unit product_modal disabled">';
        item_type           = '<select name="product_p_type[]" onchange="changeType(this)" data-rowid="'+id+'" class="autocomplete_product p_p_type product_modal">\
                    <option value="0">Select Type Price</option>\
                    <option value="1">Purchases Price</option>\
                    <option value="2">Selling Price</option>\
                </select>';
        item_price          = '<input type="text" value="'+parseFloat(price)+'" name="product_price[]" class="autocomplete_product p_price product_modal disabled duit">';
        item_sellprice      = '<input type="text" value="'+parseFloat(price_sell)+'" name="product_total[]" class="autocomplete_product p_total product_modal duit">';

        $('.data-vendor'+v).eq(1).append(item_warning)
        $('.data-vendor'+v).eq(2).append(item_group_name);
        $('.data-vendor'+v).eq(3).append(item_product_code);
        $('.data-vendor'+v).eq(4).append(item_product_name);
        $('.data-vendor'+v).eq(5).append(item_product_unit);
        $('.data-vendor'+v).eq(6).append(item_type);
        $('.data-vendor'+v).eq(7).append(item_price);
        $('.data-vendor'+v).eq(8).append(item_sellprice);

        $('.data-vendor'+v+' .p_p_type').val(price_type);
    });

    $(v_modal+" .disabled").attr('disabled',true);
    $(v_modal+" .readonly").attr('readonly',true);
    moneyFormat();
}

function cancel_vendor_price(){
    cek_row();
}

function delete_vendor_price(id,page){
    if(page == "active"){
        message = "active data?";
    }else{
        message = "nonactive data?";
    }

    url = host+"main/delete_vendor_price/"+id;
    swal({   
        title: "Are you sure want to "+message,   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: "Save",   
        cancelButtonText: "Cancel",   
        closeOnConfirm: false,   
        showLoaderOnConfirm: true,
        closeOnCancel: false }, 
        function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    url : url,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data)
                    {
                        if(data.status){
                            swal('',data.message, 'success');
                            reload_table_vendor();
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