var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host = window.location.origin+'/';
var url = window.location.href;
var page_login = host + "main/login";
var page_register = host + "main/register";
var save_method; //for save method string
var table;
var url_list = host + "mutasi/ajax_list/";
var url_edit = host + "mutasi/ajax_edit/";
var url_hapus = host + "mutasi/ajax_delete/";
var url_simpan = host + "mutasi/simpan";
var url_update = host + "mutasi/ajax_update";
var url_add_serial = host + "mutasi/simpan_serial";

var id_row = 0;
$(document).ready(function() {
    date();
    //datatables
    filter_table();
    $("[name=mutation_type]").change(function(){
        check_mutation_type($(this).val());
    });
});

function check_mutation_type(val){
    if(val == 1){
        $(".from_v").show(300);
        $(".to_v").show(300);
    } else if(val == 2){
        $(".from_v").show(300);
        $(".to_v").hide(300);
    } else {
        $(".from_v").hide(300);
        $(".to_v").show(300);
    }
    reset_column_product();
}

function reset_column_product(){
    $('.table-add-product tbody').empty();
    add_new_row();
}

function filter_table(){
    data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    url         = url_list+url_modul+"/"+modul;
    title_page  = data_page.title;
    
    fSearch             = $('#form-filter [name=fSearch]').val();
    fStartDate          = $('#form-filter [name=fStartDate]').val();
    fEndDate            = $('#form-filter [name=fEndDate]').val();
    f_fromBranch        = $('#form-filter [name=f_fromBranch]').val();
    f_toBranch          = $('#form-filter [name=f_toBranch]').val();

    data_post = {
        Search              : fSearch,
        StartDate           : fStartDate,
        EndDate             : fEndDate,
        fromBranch          : f_fromBranch,
        toBranch            : f_toBranch,
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
            "targets": [0], //last column
            "orderable": false, //set not orderable
        },],
    });
}

function tambah()
{
    save_method = 'add';
    id_row = 0;
    $(".readonly").attr("readonly",true);

    $("#form input, #form textarea, #form select").attr("disabled",false);
    $('.btn_select').removeClass('cursor_disabled');
    $('#form')[0].reset(); // reset form on modals
    $('.form-group, .input-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $("#modal").modal("show");
    // $('#modal').modal({backdrop: 'static', keyboard: true}); // show bootstrap modal
    $('#modal .modal-title').text(language_app.lb_add_new+' '+title_page); // Set Title to Bootstrap modal title
    $("#modal .save").show();
    $('.table-add-product tbody').children( 'tr' ).remove();
    // add_new_row();
    // $('.table-add-product tbody').children( 'tr:not(:first)' ).remove();
    $(".link_add_row").show();
    $(".btn-serial-v").hide();
    $(".disabled").attr("disabled",true);
    $(".remove_row").show();
    $(".th-sub, .th-code").attr("colspan",3);
    $(".from_v").hide();
    $(".addbranchmodal1").attr("onclick","branch_modal('mutasi','.autocomplete_branch1')");
    $(".addbranchmodal2").attr("onclick","branch_modal('mutasi','.autocomplete_branch2')");
    $(".table-add-product").addClass("table-td-padding-0");
    check_mutation_type(1);

}
function view(id) {
    id_row = 0;
    save_method = "view";
    $(".readonly").attr("readonly",true);
    
    $('#form')[0].reset(); // reset form on modals
    $('.form-group, .input-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    // $('#modal').modal({backdrop: 'static', keyboard: true}); // show bootstrap modal
    $("#modal").modal("show");
    $('#modal .modal-title').text(title_page+' '+language_app.btn_detail); // Set Title to Bootstrap modal title
    $("#modal .save").hide();
    $(".link_add_row").hide();
    $(".btn-serial-v").show();
    $('.table-add-product tbody').children('tr').remove();
    $(".th-code").attr("colspan",1);
    $(".th-sub").attr("colspan",2);
    $("#form input, #form textarea, #form select").attr("disabled",true);
    $('.btn_select').removeClass('cursor_disabled');
    $(".from_v").hide();
    $(".addbranchmodal1").attr("onclick","");
    $(".addbranchmodal2").attr("onclick","");
    $(".table-add-product").removeClass("table-td-padding-0");

    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            console.log(data);
            row = 0;
            total_price = 0;
            $("[name=mutation_code]").val(data.mutation_no);         
            $("[name=from_name]").val(data.from_name);     
            $("[name=BranchName1]").val(data.from_name);     
            $("[name=to_name]").val(data.to_name);
            $("[name=BranchName2]").val(data.to_name);         
            $("[name=mutation_date]").val(data.mutation_date);         
            $("[name=mutation_remark]").val(data.mutation_remark);
            $("[name=mutation_type]").val(data.mutation_type);
            if(data.mutation_type == 1){
                $(".from_v").show();
            } else if(data.mutation_type == 2){
                $(".to_v").show();
            } else {
                $(".from_v").hide();
            }
            $.each(data.list_detail, function(i, v) {
                // add_new_row("view",v.mutation_det,v.product_type);
                add_new_row2("view",v);
            });
            if(data.list_detail.length == 0){
                item = '<tr><td colspan="7" style="text-align:center;">'+language_app.lb_data_not_found+'</td></tr>'
                $(".table-add-product tbody").append(item);
            }
            $("#form input").attr("disabled",true);
            $(".remove_row").hide();

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}
function save()
{
    $('.info-warning').empty();
    $('.has-error').removeClass('has-error'); // clear error class
    $('.help-block').empty();
    $("#form input").attr("disabled",false);
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
        url = "";
    }

    var form        = $(form)[0]; // You need to use standard javascript object here
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
    dt_price     = form_to_serial_by_class('p_sellprice');
    dt_p_unitid  = form_to_serial_by_class('p_unitid');
    formData.append('dt_serial', dt_serial);
    formData.append('dt_serialkey', dt_serialkey);
    formData.append('dt_serialauto', dt_serialauto);
    formData.append('dt_price', dt_price);
    formData.append('dt_p_unitid', dt_p_unitid);

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
                    swal('',language_app.lb_success,'success');
                    $('#modal').modal("hide");
                    reload_table();
                } 
                else if(data.status == "detail"){
                    alert(data.message);
                }
                else
                {
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
                    if(data.message){
                        swal('',data.message,'warning');
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
function add_new_row2(page = "",v = "") {

    mutation_det        = v.mutation_det;
    productid           = v.productid;
    product_code        = v.product_code;
    product_name        = v.product_name;
    product_qty         = v.mutation_qty;
    unitid              = v.unitid;
    unit_name           = v.unit_name;
    product_konv        = v.mutation_konv;
    product_price       = v.mutation_price;
    product_type        = v.product_type;
    product_subtotal    = v.mutation_subtotal;    
    remark              = v.remark;    
    btn_serial          = "";
    if(product_type == "2"){
        btn_serial = '<a href="javascript:;" onclick="view_serial_number('+"'mutation','"+v.mutation_no+"','"+v.mutation_det+"'"+')" class="btn-serial-v" aria-hidden="true" style="cursor:pointer;padding:5px;">'+language_app.btn_view_serial+'</a>';
    }
    item    = '<tr>\
                <td>'+product_code+'</td>\
                <td>'+product_name+'</td>\
                <td>'+v.mutation_qty_txt+'</td>\
                <td>'+unit_name+'</td>\
                <td>'+v.mutation_price_txt+'</td>\
                <td>'+remark+'</td>\
                <td>'+btn_serial+'</td>\
            </tr>';
    $(".table-add-product tbody").append(item);
}
function add_new_row(page = "",mutation_det="",product_type="") {
    mutation_type = $('[name=mutation_type] option:selected').val();
    kolom = $('.table-add-product tbody').find('tr').length + 1;

    if(kolom>350){
        swal('','Data item max 350', 'warning');
        return '';
    }

    id_row += 1;
    btn_serial = "";
    if(page == "view"){
        // if(product_type == "general" || product_type == "serial"){
            page = "'mutasi'";
            mutation_det = "'"+mutation_det+"'";
            btn_serial = '<a  onclick="add_serial('+page+','+mutation_det+')" class="btn-serial-v" aria-hidden="true" style="cursor:pointer;padding:5px;">Add Serial</a>';
        // }
    }
    btn_remove = "";
    if(kolom > 1){
       btn_remove = '<i  onclick="delete_row(this)" class="icon fa-remove remove_row" aria-hidden="true" style="cursor:pointer;padding:5px;"></i>'; 
    }
    item = '<tr class="rowid_'+id_row+' rowdata" data-row="'+id_row+'" data-typenya="1" data-serial="active" data-detailsn="active" data-type="'+mutation_type+'">\
                    <td>\
                        <div class="info-warning"></div>\
                    </td>\
                    <td class="remove_row"><i class="icon fa-search remove_row" onclick="ckproduct_modal('+"'"+id_row+"','mutation'"+')" style="cursor:pointer;padding:5px;"></i></td>\
                    <td><input type="text" class="disabled p_code product_modal">\
                    <input type="hidden" name="productid[]" class="p_id">\
                    <input type="hidden" name="rowid[]" value="rowid_'+id_row+'">\
                    </td>\
                    <td><input type="text"class="p_name disabled"></td>\
                    <td><input type="text" name="product_qty[]" placeholder="'+language_app.lb_qty_input+'" data-qty="active" data-class="p_min_qty" class="p_min_qty duit" min="0"></td>\
                    <td>\
                    <input type="text" class="p_unit disabled">\
                    <input type="hidden" class="p_unitid">\
                    <input type="hidden" name="product_type[]" data-class="p_type" class="p_type">\
                    <input type="hidden" value="'+0+'" data-class="p_serial_auto" class="p_serial_auto">\
                    </td>\
                    <td><input type="text" data-class="p_sellprice" class="p_sellprice duit disabled"></td>\
                    <td><input type="text" name="product_remark[]" placeholder="'+language_app.lb_remark_input+'" data-class="p_remark" class="p_remark""></td>\
                    <td>\
                        <span class="p_add_serial"></span>\
                       '+btn_remove+'\
                    </td>\
                  </tr>';
    $(".table-add-product tbody").append(item);
    $(".disabled").attr("disabled",true);
    $(".p_sellprice").data("min","tes");
    moneyFormat();
}
function  delete_row(a) {
    // console.log(a);
     // if(confirm("Are you sure you want to delete this Row?")==true)
       $(a).closest('tr').remove(); 
       // return false;
}
function  select_product(apa) {
    product_code = $(apa).val();
}
function min_max(v, min, max) {
      return (val > min) ? ((val < max) ? val : max) : min;
}
function parseNumber(n) {
    var f = parseFloat(n); //Convert to float number.
    return isNaN(f) ? 0 : f; //treat invalid input as 0;
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
