var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host = window.location.origin+'/';
var url = window.location.href;
var page_login = host + "main/login";
var page_register = host + "main/register";
var save_method; //for save method string
var table;
var url_list = host + "Stock_opname/ajax_list/";
var url_edit = host + "Stock_opname/ajax_edit/";
var url_hapus = host + "Stock_opname/ajax_delete/";
var url_simpan = host + "Stock_opname/simpan";
var url_update = host + "Stock_opname/ajax_update";
var id_row = 0;

var no;
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
    fBranch             = $('#form-filter [name=fBranch]').val();

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
        "searching" : false,
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url"   : url_list,
            "type"  : "POST",
            "data"  : data_post,
        },
        //Set column definition initialisation properties.
        "columnDefs": [{
            "targets": [0], //last column
            "orderable": false, //set not orderable
        },],
    });
}

function tambah()
{
    no = 0;
    $("#form input, #form textarea, #form select").attr("disabled",false);
    $("#form disabled").attr("disabled",true);
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal('show'); // show bootstrap modal
    $('.modal-title').text(language_app.lb_add_new+' '+title_page); // Set Title to Bootstrap modal title
    $('.table-add-product tbody').children( 'tr' ).remove();
    $('.table-add-serial tbody').empty();
    $('.save, .vimport, .vaddrow').show();
    id_row = 0;
    set_default_branch();
    reset_button_action();
    add_new_row();
}
 
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}
function save()
{
    proses_save_button();
    
    var url;
    if(save_method == 'add') {
        url = url_simpan;
    } else {
        url = url_update;
    }
    $('.info-warning').empty();
    $("#form input").attr("disabled",false);
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty();

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
    dt_product_type= form_to_serial_by_class('p_type');
    formData.append('dt_serial', dt_serial);
    formData.append('dt_serialkey', dt_serialkey);
    formData.append('dt_serialauto', dt_serialauto);
    formData.append('dt_product_type', dt_product_type);

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
                swal('',language_app.lb_success,'success');
                $('#modal').modal("hide");
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    list    = data.list[i];
                    if(list == 'list'){
                        item = '<i class="icon fa-exclamation-triangle" title="'+data.message+'" style="cursor:pointer;padding:5px;"></i>';
                        $('.'+data.inputerror[i]+' .info-warning').html(item);
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
            $(".disabled").attr("disabled",true);
            console.log(jqXHR.responseText);
            alert('Error adding / update data');
            success_save_button();
        }
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

function add_new_row(v) {
    id_row += 1;
    kolom = $('.table-add-product tbody').find('tr').length + 1;
    btn_remove = '';
    if(kolom > 1){
       btn_remove = '<i  onclick="delete_row(this)" class="icon fa-remove remove_row" aria-hidden="true" style="cursor:pointer;padding:5px;"></i>'; 
    }

    if(kolom>250){
        swal('','Data item max 250', 'warning');
        return;
    }

    productid       = '';
    product_code    = '';
    product_name    = '';
    product_price   = '';
    product_stock   = '';
    product_qty     = '';
    product_unitid  = '';
    product_unit    = '';
    product_remark  = '';
    product_type    = '';
    product_correction      = '';
    title_warning   = '';
    btn_serial      = '';

    if(v){
        productid       = v.productid;
        product_code    = v.product_code;
        product_name    = v.product_name;
        product_price   = parseFloat(v.product_price);
        product_stock   = parseFloat(v.product_stock_opname);
        product_qty     = parseFloat(v.product_qty);
        product_unitid  = v.unitid;
        product_unit    = v.unit_name;
        product_type    = v.product_type;
        status_average  = v.status_average;
        product_correction      = parseFloat(v.correction_stock);
        if(v.product_remark){
            product_remark = v.product_remark;
        }
        if(!status_average){
            title_warning = '<i class="icon fa-exclamation-triangle" title="'+language_app.lb_average_price_program+'" style="cursor:pointer;padding:5px;"></i>'
        }

        if(v.product_type == 2){
            btn_serial = '<a href="javascript:;" onclick="view_serial_number('+"'stock_opname','"+v.correctionno+"','"+v.CorrectionDetID+"'"+')" class="btn-serial-v" aria-hidden="true" style="cursor:pointer;padding:5px;">'+language_app.btn_view_serial+'</a>'
        }
    }
    // onkeyup="keyup_product('+id_row+',this)"
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
            </td>\
            <td><input type="text" value="'+product_name+'" class="p_name disabled"></td>\
            <td><input type="text" value="'+product_price+'" name="product_price[]" placeholder="'+language_app.lb_price_input+'" data-class="p_price" class="p_price duit"></td>\
            <td><input type="text" value="'+product_stock+'" placeholder="'+language_app.lb_qty_input+'" name="product_stock[]" onkeyup="check_average('+"'.rowid_"+id_row+"'"+')" data-qty="active" data-class="p_stock" class="p_stock duit" min="0"></td>\
            <td><input type="text" value="'+product_qty+'" data-qty="active" name="product_qty[]" class="p_qty duit disabled"></td>\
            <td><input type="text" value="'+product_correction+'" data-qty="active" class="p_correction duit disabled"></td>\
            <td>\
                <input type="hidden" value="'+product_unitid+'" class="p_unitid">\
                <input type="text" value="'+product_unit+'" class="p_unit disabled">\
                <input type="hidden" value="'+product_type+'" data-class="p_type" class="p_type">\
                <input type="hidden" value="'+0+'" data-class="p_serial_auto" class="p_serial_auto">\
            </td>\
            <td><input type="text" name="product_remark[]" value="'+product_remark+'" placeholder="'+language_app.lb_remark_input+'" data-class="p_remark" class="p_remark"></td>\
            <td style="width:100px">'+btn_remove+btn_serial+'<span class="p_add_serial"></span></td>\
        </tr>\
        ';
    $(".table-add-product tbody").append(item);
    $(".disabled").attr("disabled",true);
    moneyFormat();
    date();
}

function  delete_row(a) {
    $(a).closest('tr').remove();
    create_format_currency2();
}

function modal_import()
{
    $('#modal-import').modal('show');
    $('.modal-title').text('Import Data');
    $('#form-import')[0].reset(); // reset form on modals
    $(".dropify-preview").hide();
    $(".dropify-render img").remove();
}

function import_data(){
    proses_save_button("next","btn-import");

    url = host+"Stock_opname/import";
    var form = $('#form-import')[0]; // You need to use standard javascript object here
    var formData = new FormData(form);
    $.ajax({
    url : url,
    type: "POST",
    // data: $('#form').serialize(),
    data:  formData,
    mimeType:"multipart/form-data",
    contentType: false,
    cache: false,
    processData:false,
    dataType: "JSON",
    success: function(data)
    {   
        if(data.hak_akses == "super_admin"){
            console.log(data);
        }
        if(data.status){
            $('#modal-import').modal('hide');
            // $('.table-add-product tbody').children( 'tr' ).remove();
            // $.each(data.list,function(k,v){
            //     add_new_row(v);
            // });
            item_import_data(data);
        } else {
            swal('',data.message,'warning');  
        }
        success_save_button("next","btn-import");
    },
    error: function (jqXHR, textStatus, errorThrown){
        alert("import data error");
        success_save_button("next","btn-import");
        console.log(jqXHR.responseText);
    }
  });
}

function check_average(classnya){
    product_code = $(classnya+' .p_code').val();
    if(product_code){
        product_stock           = $(classnya+' [name="product_stock[]"]').val();
        product_qty             = $(classnya+' .p_qty').val();

        product_stock           = removeduit(product_stock);
        product_qty             = removeduit(product_qty);

        title_warning = '';

        correction_stock = '';
        if(product_stock){
            correction_stock = product_stock - product_qty;
        }

        $(classnya+' .info-warning').html(title_warning);
        $(classnya+' .p_correction').val(correction_stock);
    }
    run_function = 'check_average2()';
}

function check_average2(){
    length_class = $('.rowdata').length;
    for (var i = 0; i < length_class; i++) {
        tg_data = $('.rowdata').eq(i).data();
        dt_class = tg_data.row;
        check_average('.rowid_'+dt_class);
    }
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
    $('.table-add-product tbody').children( 'tr' ).remove();

    $('.save, .vimport, .vaddrow').hide();
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
            $("[name=correctionno]").val(data.correctionno);
            $("#BranchName").val(data.branchName);
            $("[name=date]").val(data.date);
            $.each(data.list_detail, function(i, v) {
                add_new_row(v);
            });
            $("#form input").attr("disabled",true);
            $(".remove_row").hide();
            create_format_currency2();

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

// 20190716 MW
// View import
function item_import_data(data){
    $('#modal-import-data').modal('show');
    $('#modal-import-data .modal-title').text("Import Data Detail");
    $('#modal-import-data .content-import').empty();
    $('#modal-import-data .div-loader').hide();
    if(data.data.length>0){
        item = '<table id="table-import-data" data-filename="'+data.inputFileName+'" data-branchid="'+data.BranchID+'" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">';
            item += '<thead><tr>';
            item += '<th style="width:50px"></th>';
            total_column_header = data.header[0].length;
            total_failed  = 0;
            total_success = 0;
            $.each(data.header[0],function(k,v){
                item += '<th>'+v+'</th>';
            });
            item += '<th>Status</th>';
            item += '<th>'+language_app.lb_message+'</th>';
            item += '</tr></thead>';

            item += '<tbody>';
            $.each(data.data,function(k,v){
                checkbox_status = '';
                background      = 'bg-merah-pias';
                label_status    = 'Failed';
                status_data     = '';
                if(v.status){
                    total_success += 1;
                    checkbox_status = ' checked ';
                    background = '';
                    label_status    = '<hijau>'+language_app.lb_success+'</hijau>';
                    if(v.status_data == "insert"){
                        status_data = '<br><hijau>'+language_app.lb_product_insert+'</hijau>';
                    }else{
                        status_data = '<br><hijau>'+language_app.lb_product_update+'</hijau>';
                    }
                }else{
                    total_failed += 1;
                }

                checkbox = '<div class="checkbox-custom checkbox-primary">\
                  <input type="checkbox" '+checkbox_status+' disabled>\
                  <label style="color:white">.</label>\
                </div>';

                item += '<tr class="'+background+'">';
                item += '<td>'+checkbox+'</td>';
                item += '<td>'+v.Code+'</td>';
                item += '<td>'+v.Name+'</td>';
                item += '<td>'+v.Average+'</td>';
                item += '<td>'+v.StockOpname+'</td>';
                item += '<td>'+v.StockQty+'</td>';
                item += '<td>'+v.Unit+'</td>';
                item += '<td>'+v.Remark+'</td>';
                item += '<td>'+v.SerialNumber+'</td>';
                item += '<td>'+label_status+status_data+'</td>';
                item += '<td>'+v.Message+'</td>';
                item += '</tr>';
            });
            item += '</tbody>';

            colspan = total_column_header + 3 - 1;

        item += '</table>';

        item_total = '<span>'+language_app.lb_store+' : '+data.branchName+'</span></br>';
        item_total += '<span>Total Data : '+data.data.length+', '+total_success+' '+language_app.lb_success+', '+total_failed+' '+language_app.lb_fail+'</span>';

        $('#modal-import-data .content-import').append(item_total);
        $('#modal-import-data .content-import').append(item);
        $('#modal-import-data #table-import-data').DataTable({
            "destroy"   : true,
        });
    }else{
        item = '<center><h4>'+language_app.lb_data_not_found+'</h4></center>';
        $('#modal-import-data .content-import').append(item);
    }
}

// 20190716 MW
// save import
function save_import(){
    tag_data = $('#modal-import-data #table-import-data').data();
    if(tag_data.filename){
        proses_save_button('import','btn-import-data');
        data_post = {filename : tag_data.filename,BranchID : tag_data.branchid};
        $.ajax({
            url : host+"Stock_opname/save_import",
            type: "POST",
            data : data_post,
            dataType: "JSON",
            success: function(data){
                if(data.hakakses == "super_admin"){
                    console.log(data);
                }
                if(data.status){
                    $('#modal-import-data').modal('hide');
                    swal('',data.message, 'success');
                    reload_table();
                }else{
                    swal('',data.message, 'warning');
                }
                success_save_button('import','btn-import-data');
            },
            error: function (jqXHR, textStatus, errorThrown){
                alert('Error adding / update data');
                success_save_button('import','btn-import-data');
                console.log(jqXHR.responseText);
            }
        });
    }else{
        swal('',language_app.lb_file_not_found,'warning');
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