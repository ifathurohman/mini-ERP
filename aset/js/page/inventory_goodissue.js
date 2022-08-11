var mobile          = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host            = window.location.origin+'/';
var c_control 		= "inventory_goodissue/";
var url             = window.location.href;
var url_list        = host + c_control +"ajax_list/";
var url_edit        = host + c_control +"ajax_edit/";
var url_cancel      = host + c_control +"cancel/";
var url_simpan      = host + c_control +"save";
var url_simpan_remark = host + c_control +"save_remark";
var save_method; //for save method string
var table;

var id_row = 0;
$(document).ready(function() {
    data_page       = $(".data-page, .page-data").data();
    url_modul            = data_page.url_modul;
    modul                = data_page.modul;
    title_page           = data_page.title;

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
    fStatus         	= $('#form-filter [name=fStatus]').val();
    fBranch             = $('#form-filter [name=fBranch]').val();

    data_post = {
        Search              : fSearch,
        StartDate           : fStartDate,
        EndDate             : fEndDate,
        Status              : fStatus,
        Branch              : fBranch,
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
}

function tambah(){
	id_row 		= 0;
    save_method = 'add';
    $(".readonly").attr("readonly",true);

    $("#form input, #form textarea, #form select").attr("disabled",false);
    $('#form')[0].reset(); // reset form on modals
    $('.has-error').removeClass('has-error'); // clear error class
    $('.input-error').removeClass('input-error');
    $('.help-block').empty(); // clear error string
    $("#modal .save").show();
    $('#modal').modal('show'); // show bootstrap modal
    $('.link_add_row').show();
    $(".btn-serial-v").hide();
    $('.vaction, .vprint').hide();
    $('.table-add-product tbody').children( 'tr' ).remove();
    $('.table-add-serial tbody').empty();
    $('.modal-title').text(language_app.lb_add_new+' '+title_page); // Set Title to Bootstrap modal title
    $('[name="crud"]').val("insert");
    $('.disabled').attr('disabled',true);
    set_default_branch();
    add_new_row();
    reset_button_action();
    reset_file_upload();
    show_upload_file();
    hide_button_cancel2();
    create_form_attach();
    $('.btn-close').show();
    //-----------------------------------------------------------------------------
}

function add_new_row(data){
    kolom = $('.table-add-product tbody').find('tr').length + 1;
    id_row += 1;
    btn_serial = "";
    btn_remove = "";

    if(kolom>200){
        swal('','Data item max 200', 'warning');
        return '';
    }
    
    productid       = '';
    code            = '';
    name            = '';
    qty             = '';
    unitid          = '';
    product_unit    = '';
    product_konv    = '';
    product_type    = '';
    product_price   = '';
    product_total   = '';
    product_remark  = '';
    serial_auto     = '';

    if(kolom > 1){
       btn_remove = '<i  onclick="delete_row(this)" class="icon fa-remove remove_row" aria-hidden="true" style="cursor:pointer;padding:5px;"></i>'; 
    }

    item2 = '<td><input type="text" placeholder="'+language_app.lb_qty_input+'" name="product_qty[]" value="'+qty+'" data-qty="active" data-class="p_min_qty" class="p_min_qty duit" onkeyup="SumTotal()" min="0"></td>\
                <td>\
                <input type="hidden" name="product_unitid[]" value="'+unitid+'" class="p_unitid">\
                <input type="hidden" value="'+product_unit+'" class="p_unit disabled">\
                <input type="hidden" name="product_type[]" value="'+product_type+'" data-class="p_type" class="p_type">\
                <input type="hidden" value="'+serial_auto+'" data-class="p_serial_auto" class="p_serial_auto">\
                <select style="min-width:100px" class="p_unit2 width-100per" onchange="check_product_unit(this)"></select>\
                </td>';

    // onkeyup="keyup_product('+id_row+',this)"
    item = '<tr class="rowid_'+id_row+' rowdata" data-row="'+id_row+'" data-classnya="rowid_'+id_row+'" data-typenya="1" data-serial="active" data-detailsn="active">\
                <td>\
                    <input type="hidden" name="rowid[]" value="rowid_'+id_row+'">\
                    <div class="info-warning"></div>\
                </td>\
                <td class="remove_row">\
                    <i class="icon fa-search remove_row" onclick="product_modal('+id_row+')" style="cursor:pointer;padding:5px;"></i>\
                     '+btn_remove+'\
                </td>\
                <td><input type="text" value="'+code+'" class="autocomplete_product p_code product_modal disabled">\
                <input type="hidden" name="product_id[]" value="'+productid+'" class="p_id">\
                </td>\
                <td><input type="text" value="'+name+'" class="p_name disabled"></td>\
                '+item2+'\
                <td class="content-hide"><input type="text" name="product_konv[]" value="'+product_konv+'" class="p_conv disabled"></td>\
                <td>\
                    <input type="text" name="product_price[]" placeholder="'+language_app.lb_price_input+'" value="'+product_price+'" data-class="p_purchaseprice" class="p_purchaseprice duit" onkeyup="SumTotal()">\
                </td>\
                <td><input type="text" value="'+product_total+'" data-class="p_sub_total" class="p_sub_total duit disabled"></td>\
                <td><input type="text" name="product_remark[]" placeholder="'+language_app.lb_remark_input+'" value="'+product_remark+'" data-class="p_remark" class="p_remark"></td>\
                <td style="min-width:110px">\
                    <span class="p_add_serial"></span>\
                   '+btn_remove+btn_serial+'\
                </td>\
            </tr>';
    $(".table-add-product tbody").append(item);
    $(".disabled").attr("disabled",true);
    moneyFormat();
}

function delete_row(a) {
    $(a).closest('tr').remove();
    SumTotal('element');
}

function reset_column_product(){
    $('.table-add-product tbody').children( 'tr' ).remove();
    add_new_row();
    SumTotal('element');
}

function SumTotal(element){
    data_product 		= get_total_price_product();
    total_price_product = data_product[1];

    // $('#SubTotal').val(parseFloat(data_product[1]));

    Total = parseFloat(total_price_product);
    $('#Total').val(parseFloat(Total.toFixed(2)));
    run_function = 'SumTotal()';
    if(element){
        create_format_currency2();
    }
}

function get_total_price_product(){
    d = $("input[name='product_id[]']").length;
    total           = 0;
    discount_rp     = 0;
    before_total   = 0;
    for (i = 0; i < d; i++) { 
        code        = $('[name="product_code[]"]').eq(i).val();
        conversion  = $('[name="product_konv[]"]').eq(i).val();
        conversion  = removeduit(conversion);

        rowid       = $('[name="rowid[]"]').eq(i).val();
        product_type= $('[name="product_type[]"]').eq(i).val();
        serial_auto = $('.p_serial_auto').eq(i).val();
        
        i_qty   = $('[name="product_qty[]"]').eq(i);
        qty     = i_qty.val();
        i_qty.val(qty);
        qty     = removeduit(qty);
        
        qty = qty * conversion;
        
        val     = $('[name="product_price[]"]').eq(i).val();
        val     = removeduit(val);

        if(code != ''){
            sub_total   	= qty * val;
            before_total    += sub_total;
            total       	+= sub_total;

            $('.p_sub_total').eq(i).val(parseFloat(sub_total));
        }
    }

    var data = [total,before_total];
    return data;
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
    url = url_simpan;

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
                        item = '<i class="icon fa-exclamation-triangle" title="'+data.error_string[i]+'" style="cursor:pointer;padding:5px;"></i>';
                        $('.'+data.inputerror[i]+' .info-warning').append(item);
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

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}

// view
function view(id,page){
    reset_button_action();
    if(page == "print"){
        open_modal_template(id,page);
    }else{
        view_print_data(id,page);
    }

    url = host + "inventory-issue-view/"+id+"?page=print";
    action_print_button(url);
}

function view_print_data(id,page){
    page2 = '';
    if(page == "print"){
        page = "print";
    }else{
        page2 = page;
        page = "Receipt";
    }
    $('#modal-print').modal('show');
    $('#modal-print .modal-title').text(title_page+" "+language_app.btn_detail);
    url = host + "inventory-issue-view/"+id+"?page="+page;
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