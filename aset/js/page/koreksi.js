var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host = window.location.origin+'/';
var url = window.location.href;
var page_login = host + "main/login";
var page_register = host + "main/register";
var save_method; //for save method string
var table;
var url_list = host + "Koreksi_stok/ajax_list/";
var url_edit = host + "Koreksi_stok/ajax_edit/";
var url_hapus = host + "Koreksi_stok/ajax_delete/";
var url_simpan = host + "Koreksi_stok/simpan";
var url_update = host + "Koreksi_stok/ajax_update";

var no;
var id_row = 0;
$(document).ready(function() {
    date();
    //datatables
    filter_table();
});

function filter_table(){
    data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    url         = url_list+url_modul+"/"+modul;
    title_page  = data_page.title;
    
    fSearch             = $('#form-filter [name=fSearch]').val();
    fStartDate          = $('#form-filter [name=fStartDate]').val();
    fEndDate            = $('#form-filter [name=fEndDate]').val();
    fBranch           = $('#form-filter [name=fBranch]').val();

    data_post = {
        Search              : fSearch,
        StartDate           : fStartDate,
        EndDate             : fEndDate,
        Branch              : fBranch,
    }

    table = $('#table').DataTable({
        "destroy"   : true,
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "searching": false, //Feature Search false
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url"   : url_list,
            "type"  : "POST",
            "data"  : data_post,
        },
        //Set column definition initialisation properties.
        "columnDefs": [{
            "targets": [ 0 ], //last column
            "orderable": false, //set not orderable
        },],
    });
}

function tambah()
{
    no = 0;
    id_row = 0;
    $("#form input, #form textarea, #form select").attr("disabled",false);
    $('.btn_select').removeClass('cursor_disabled');
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal('show'); // show bootstrap modal
    // $('#modal').modal({backdrop: 'static', keyboard: true}); // show bootstrap modal
    $('.modal-title').text(language_app.lb_add_new+' '+title_page); // Set Title to Bootstrap modal title
    $('.table-add-product tbody').children( 'tr' ).remove();
    $('.table-add-serial tbody').empty();
    $('.disabled').attr('disabled',true);
    $(".readonly").attr("readonly",true);
    $('.vaddrow').show();
    set_default_branch();
    reset_button_action();
    add_new_row();

}
function view(id,page) {
    save_method = 'view';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal('show'); // show bootstrap modal
    // $('#modal').modal({backdrop: 'static', keyboard: true}); // show bootstrap modal
    $('.modal-title').text(title_page+' '+language_app.btn_detail); // Set Title to Bootstrap modal title
    $("#form input, #form textarea").attr("disabled",true);
    $('.btn_select').addClass('cursor_disabled');
    $('.table-add-product tbody').children( 'tr' ).remove();

    $('.save, .vaddrow').hide();
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            no = 0;
            $("[name=correctionid]").val(data.correctionid);
            $("#BranchName").val(data.branchName);
            $("[name=correctionno]").val(data.correctionno);
            $("[name=date]").val(data.date);
            $.each(data.list_detail, function(i, v) {
                add_new_row(v,"view",page);
            });
            $("#form input").attr("disabled",true);
            $(".remove_row").hide();

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });


}
function add_serial() {
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal-add-serial').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Serial Number'); // Set Title to Bootstrap modal title

}
 
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}
function save()
{   
    proses_save_button();
    $('.info-warning').empty();
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty();
    var url;
    if(save_method == 'add') {
        url = url_simpan;
    } else {
        url = url_update;
    }

    var form        = $('#form')[0]; // You need to use standard javascript object here
    var formData    = new FormData(form);

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
    dt_qty       = form_to_serial_by_class('p_qty');
    formData.append('dt_serial', dt_serial);
    formData.append('dt_serialkey', dt_serialkey);
    formData.append('dt_serialauto', dt_serialauto);
    formData.append('dt_qty', dt_qty);

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
            if(data.status) //if success close modal and reload ajax table
            {
                swal('',language_app.lb_success, 'success');
                $('#modal').modal("hide");
                reload_table();
            }
            else
            {
                if(data.message){
                    swal('',data.message,'warning');
                }
                for (var i = 0; i < data.inputerror.length; i++)
                {   
                    list    = data.list[i];
                    if(list == 'list'){
                        item = '<i class="icon fa-exclamation-triangle" title="'+data.error_string[i]+'" style="cursor:pointer;padding:5px;"></i>';
                        $(data.inputerror[i]+' .info-warning').append(item);
                    }else{
                        $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                        $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                    }
                }
            }
            success_save_button();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            console.log(jqXHR.responseText);
            success_save_button();
        }
    });
}
function hapus(id)
{
    swal({   title: "Are you sure?",   
             text: "You will not be able to recover this data !",   
             type: "warning",   
             showCancelButton: true,   
             confirmButtonColor: "#DD6B55",   
             confirmButtonText: "Yes, delete it!",   
             cancelButtonText: "No, cancel it!",   
             closeOnConfirm: false,   
             closeOnCancel: false }, 
             function(isConfirm){   
                 if (isConfirm) { 
                    $.ajax({
                        url : url_hapus+id,
                        type: "POST",
                        dataType: "JSON",
                        success: function(data)
                        {
                            //if success reload ajax table
                            reload_table();
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            swal('Error deleting data');
                        }
                    });
                    swal("Deleted!", "Your data has been deleted.", "success");   } 
                 else {
                     swal("Canceled", "Your data is safe :)", "error");   } 
    });
}
function date(){
    container = $('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
    $(".date").datepicker({
        format: 'yyyy-mm-dd',
        container: container,
        todayHighlight: true,
        autoclose: true,
    });
}

function add_new_row_lama(v="",page = "",page2) {
    no += 1;
    productid = "";
    product_code = "";
    product_name = "";
    product_qty = "";
    btn_serial  = "";
    if(v){
        productid       = v.productid;
        product_code    = v.product_code;
        product_name    = v.product_name;
        product_unitid  = v.unitid;
        product_unit    = v.unit_name;
        product_conv    = v.conversion;
        product_sellingprice = v.sellingprice;    
        product_qty     = v.qty;
        realqty         = v.realqty;

    }
    if(page == "add"){
        realqty = "";
    }else{
        if(page2){
            if(v.product_type == "serial"){
                btn_serial = '<a  onclick="add_serial_stock('+"'stock','"+v.correctiondet+"'"+')" class="btn-serial-v" aria-hidden="true" style="cursor:pointer;padding:5px;">Add Serial</a>';
            }else if(v.product_type == "general"){
                btn_serial = '<a  onclick="add_serial_stock('+"'stock','"+v.correctiondet+"'"+')" class="btn-serial-v" aria-hidden="true" style="cursor:pointer;padding:5px;">Add Serial</a>';
            }
        }
    }
    item = '<tr>\
                    <td class="td">\
                    '+no+'\
                    <input type="hidden" name="productid[]" value="'+productid+'">\
                    </td>\
                    <td class="td">'+product_name+'</td>\
                    <td class="td">'+product_qty+'</td>\
                    <td>\
                    <input type="hidden" name="qty[]" class="td" value="'+product_qty+'">\
                    <input type="text" name="realqty[]" class="td angka" value="'+realqty+'"></td>\
                    <td class="th-code">'+btn_serial+'</td>\
                  </tr>';
    $(".table-add-product tbody").append(item);
    if(page == "add"){
        $('.th-code').hide();
    }else{
        if(page2){
            if(v.product_type == "serial" || v.product_type == "general"){
                $('.th-code').show();
            }else{
                $('.th-code').hide();
            }
        }else{
            $('.th-code').hide();
        }
    }
    angkaFormat();
}

function add_new_row(v,page){
    id_row += 1;
    kolom           = $('.table-add-product tbody').find('tr').length + 1;
    btn_remove      = '';
    title_warning   = '';

    if(kolom>450){
        swal('','Data item max 450', 'warning');
        return;
    }

    productid       = '';
    product_code    = '';
    product_name    = '';
    product_qty     = '';
    product_type    = '';
    realqty         = '';
    btn_serial      = '';

    if(page == 'view'){
        product_code = v.product_code;
        product_name = v.product_name;
        product_qty  = v.qty_txt;
        realqty      = v.realqty_txt;

        if(v.product_type == 2){
            btn_serial = '<a href="javascript:;" onclick="view_serial_number('+"'stock_correction','"+v.correctionno+"','"+v.CorrectionDetID+"'"+')" class="btn-serial-v" aria-hidden="true" style="cursor:pointer;padding:5px;">'+language_app.btn_view_serial+'</a>'
        }
        item2 = '<input type="text" value="'+realqty+'" class="disabled duit">';
    }else{
        item2 = '<input type="text" name="product_qty[]" value="'+realqty+'" placeholder="'+language_app.lb_qty_input+'" data-qty="active" data-class="p_min_qty" class="p_min_qty duit" min="0">';
    }

    if(kolom > 1){
       btn_remove = '<i  onclick="delete_row(this)" class="icon fa-remove remove_row" aria-hidden="true" style="cursor:pointer;padding:5px;"></i>'; 
    }

    item = '<tr class="rowid_'+id_row+' rowdata" data-row="'+id_row+'" data-typenya="1" data-serial="active" data-detailsn="active">\
            <td>\
                <input type="hidden" name="rowid[]" value="rowid_'+id_row+'">\
                <div class="info-warning">'+title_warning+'</div>\
            </td>\
            <td>\
                <i class="icon fa-search remove_row" onclick="product_modal('+"'"+id_row+"'"+')" style="cursor:pointer;padding:5px;"></i>\
            </td>\
            <td><input type="text" value="'+product_code+'" class="autocomplete_product p_code product_modal disabled">\
                <input type="hidden" value="'+productid+'" name="productid[]" class="p_id">\
                <input type="hidden" name="product_type[]" value="'+product_type+'" data-class="p_type" class="p_type">\
                <input type="hidden" value="'+0+'" data-class="p_serial_auto" class="p_serial_auto">\
            </td>\
            <td><input type="text" value="'+product_name+'" class="p_name disabled"></td>\
            <td><input type="text" value="'+product_qty+'" data-qty="active" class="p_qty disabled duit"></td>\
            <td>'+item2+'</td>\
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
}

function add_serial_stock(page,id){
    save_method = "add_serial";
    url = host + "Koreksi_stok/get_detail/"+id;
    $.ajax({
        url : url,
        type: "POST",
        dataType: "JSON",
        success: function(data)
        {   if(data.hak_akses == "super_admin"){
                console.log(data);
            }
            $('.btn-add-serial').show();
            $('#modal-add-serial2 .modal-title').text('Serial Number'); // Set Title to Bootstrap modal title
            $('#modal-add-serial2').modal('show');
            $('#modal-add-serial2 .table-add-serial').empty();

            if ( $.fn.DataTable.isDataTable('#modal-add-serial2 .table-add-serial') ) {
              $('#modal-add-serial2 .table-add-serial').DataTable().destroy();
            }

            $('#form-serial2 [name=page]').val(page);
            $('#form-serial2 [name=productid]').val(data.list.productid);
            $('#form-serial2 [name=product_type]').val(data.list.product_type);
            $('#form-serial2 [name=product_name]').val(data.list.product_name);
            $('#form-serial2 [name=serial_qty]').val(data.list.realqty);
            $('#form-serial2 [name=header_code]').val(data.list.correctionno);
            $('#form-serial2 [name=detail_code]').val(data.list.correctiondet);
            $('#form-serial2 [name=product_type_txt]').val(data.list.product_type);

            var data_list    = [];
            var count_serial = parseFloat(data.serial.length);
            var count_qty    = parseFloat(data.list.realqty);
            if(data.serial.length>0){
                no = 0;
                $.each(data.serial, function(k,v){
                    no += 1;
                    id = v.productserialid;
                    item  = '<input type="checkbox" name="check[]" value="'+id+'" />';
                    item += '<input type="hidden" name="serial_id[]" value="'+id+'" />';
                    item += '<div class="content-hide">'+v.serialnumber+'</div>';

                    a = [item,no,'<input type="text" name="sn[]" value="'+v.serialnumber+'" placeholder="input serial number"/>'];
                    data_list.push(a);
                })
            }
            if(count_qty > count_serial){
                val = count_qty - count_serial;
                if(data.list.product_type == "serial"){
                    for (var i = 1; i <= val; i++) {
                        no += 1;
                        id = i;
                        item  = '<input type="checkbox" name="check[]" value="alias'+id+'" />';
                        item += '<input type="hidden" name="serial_id[]" value="alias'+id+'" />';
                        a = [item,no,'<input type="text" name="sn[]" placeholder="input serial number"/>'];
                        data_list.push(a);
                    }
                }else if(data.list.product_type == "general" && count_serial <= 0){
                    no += 1;
                    id = 1;
                    item  = '<input type="checkbox" name="check[]" value="alias'+id+'" />';
                    item += '<input type="hidden" name="serial_id[]" value="alias'+id+'" />';
                    a = [item,no,'<input type="text" name="sn[]" placeholder="input serial number"/>'];
                    data_list.push(a);
                }
            }

            $('#modal-add-serial2 .table-add-serial').DataTable( {
                "ordering": false,
                columns: [
                    { title: '<input type="checkbox" name="checkall" value="1" onclick="checkbox_all(this)"/>' },
                    { title: 'No' },
                    { title: "Serial Number" }
                ],
                data: data_list,
            } );

            $('.readonly').attr('readonly', true);     
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            swal('Error deleting data');
        }
    });
}

function checkbox_all(element){
    if($(element).is(':checked')){
        $("#modal-add-serial2 .table-add-serial tbody [type=checkbox]").prop("checked",true);
    } else {
        $("#modal-add-serial2 .table-add-serial tbody [type=checkbox]").prop("checked",false);
    }
}

function check_product_not_duplicate(id,row){
    row_length = $('.rowdata').length;
    status_data= true;
    for (var i = 0; i < row_length; i++) {
        tg_data = $('.rowdata').eq(i).data();
        p_id    = $('.rowdata .p_id').eq(i).val();
        classnya= tg_data.row;
        if(p_id == id && classnya != row){
            status_data = false;
        }
    }

    if(!status_data){
        swal('',language_app.lb_product_duplicate, 'warning');
    }

    return status_data;
}

function reset_column_product(){
    $('.table-add-product tbody').children( 'tr' ).remove();
    add_new_row();
}