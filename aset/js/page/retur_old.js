var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host = window.location.origin+'/';
var url = window.location.href;
var page_login = host + "main/login";
var page_register = host + "main/register";
var save_method; //for save method string
var table;
var url_list = host + "retur/ajax_list/";
var url_edit = host + "retur/ajax_edit/";
var url_hapus = host + "retur/ajax_delete/";
var url_simpan = host + "retur/simpan";
var url_update = host + "retur/ajax_update";
$(document).ready(function() {
    date();
    //datatables
    table = $('#table').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": url_list,
            "type": "POST"
        },
        //Set column definition initialisation properties.
        "columnDefs": [{
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },],
    });
});
function modal_width()
{
    if(mobile){
        $(".modal-return .modal-dialog").css("width","93%");
    } else {
        $(".modal-return .modal-dialog").css("width","75%");
    }
}
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}
function tambah(type = "")
{
    save_method = 'add';
    modal_width();
    $("#modal .save").show();
    $("#form input").attr("disabled",false);
    $(".disabled").attr("disabled",true);
    $(".readonly").attr("readonly",true);
    $('#form')[0].reset(); // reset form on modals
    $('.form-group, .input-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    //------------------------------------------------------------------
    $("[name=type]").val(type);
    $(".v_sell, .v_purchase").hide();
    if(type == "purchase"){
        $(".v_purchase").show();
        title = "Purchase";
    } else {
        $(".v_sell").show();
        title = "Sales";
    }   
    $('.table-add-product tbody').children( 'tr:not(:first)' ).remove();
    $(".link_add_row").show();
    tbl = ".table-add-product";
    $(tbl + " tbody tr").remove();
    item = ' <tr>\
                <td  colspan="9" style="text-align:center">Empty Product Data</td>\
              </tr>';
    $(tbl + " tbody").append(item);       
    $(".add_modal_sellno").attr("onclick","modal_sellno('retur')");
    $('#modal').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add New Return'); // Set Title to Bootstrap modal title
    $(".addmodalreceive").attr("onclick","modal_receive('return')");
    $(".table-add-product").addClass("table-td-padding-0");
    $(".table-add-product input").show();
    

}
function view(id = "",type = "") {
    save_method = "view";
    modal_width();
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    //------------------------------------------------------------
    $("[name=type]").val(type);
    $(".v_sell, .v_purchase").hide();
    if(type == "purchase"){
        $(".v_purchase").show();
        title = "Purchase";
    } else {
        $(".v_sell").show();
        title = "Sales";
    }
    $("#form input, #form textarea").attr("disabled",true);
    $(".link_add_row").hide();
    $(".btn-serial-v").show();
    $("#modal .save").hide();
    $(".add_modal_sellno").attr("onclick","");
    tbl = ".table-add-product";
    $(tbl + " tbody tr").remove();
    $('#modal').modal('show'); // show bootstrap modal
    $('.modal-title').text('Retur Detail ' + title);
    $(".addmodalreceive").attr("onclick","");
    $(".table-add-product").removeClass("table-td-padding-0");
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            sellno = data.sellno;
            $("[name=returno]").val(data.returno);
            $("[name=date]").val(data.returdate);
            $("[name=sellno]").val(data.sellno);
            $("[name=receive]").val(data.receive);
            $("[name=vendorname]").val(data.vendorname);
            
            if(data.list_data.length == 0){
                item = ' <tr>\
                <td  colspan="9" style="text-align:center">Empty Product Data</td>\
                          </tr>';
                $(tbl + " tbody").append(item); 
            }
            $.each(data.list_data, function(i, v) {
                add_return_row("view",v);

            });
            $(".table-add-product input").hide();

            $("#form input").attr("disabled",true);           
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });

}
function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable
    
    var url;
    if(save_method == 'add') {
        url = url_simpan;
    } else {
        url = url_update;
    }
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);            
            }


            if(data.status){
                $('#modal').modal("hide");
                reload_table();
            }
            else{
                $('.form-group, .input-group').removeClass('has-error');
                $('.help-block').empty();
                for (var i = 0; i < data.inputerror.length; i++){
                    $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                    //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
                if(data.message){
                    swal('',data.message,'warning');                    
                }
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable
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
function remove_row()
{
    $(".table-add-product tbody tr").remove();
}
$(".cekcheckboxall").click(function(){
    if($(this).is(":checked")){
        $(".cekbox").prop("checked",true);
    } else {
        $(".cekbox").prop("checked",false);
    }
});
function add_return_row(page = "",v = "") {
    product_code     = ""; 
    product_konv     = "";
    product_name     = "";
    product_price    = "";
    product_qty      = "";
    product_subtotal = "";
    product_type     = "";
    productid        = "";
    receive_det      = "";
    receive_no       = "";
    unit_name        = "";
    unitid           = "";
    remark           = "";
    returdet         = "";
    btn_serial       = "";

    if(v){
        product_code        = v.product_code;
        product_konv        = v.product_konv;
        product_name        = v.product_name;
        product_price       = v.product_price;
        product_qty         = v.product_qty;
        product_subtotal    = v.product_subtotal;
        product_type        = v.product_type;
        productid           = v.productid;
        returdet            = v.returdet;
        returno             = v.returno;
        unit_name           = v.unit_name;
        unitid              = v.unitid;
        remark              = v.remark;
    }
    if(page == "add"){
        product_qty = '<input type="number" name="product_qty[]" class="bg-abu" placeholder="input quantity">';
        remark      = '<input type="text" name="remark[]" class="bg-abu" placeholder="input remark">';
    }
    else{
        // if(product_type == "general" || product_type == "serial"){
            page        = "'retur'";
            receive_no  = "'"+receive_no+"'";
            btn_serial  = '<a  onclick="add_serial('+page+','+returdet+')" class="btn-serial-v " aria-hidden="true" style="cursor:pointer;padding:5px;">Add Serial</a>';
        // }

    }

    input   = '<input type="hidden" name="productid[]" value="'+productid+'">';
    input   += '<input type="hidden" name="product_price[]" value="'+product_price+'">';
    input   += '<input type="hidden" name="product_konv[]" value="'+product_konv+'">';
    input   += '<input type="hidden" name="unitid[]" value="'+unitid+'">';
    input   += '<input type="hidden" name="receivedet[]" value="'+receive_det+'">';
    item    = '<tr>\
                <td><input type="checkbox" name="cekbox[]" class="cekbox" value="'+productid+'">'+input+'</td>\
                <td>'+product_code+'</td>\
                <td>'+product_name+'</td>\
                <td>'+product_qty+'</td>\
                <td>'+product_type+'</td>\
                <td>'+unit_name+'</td>\
                <td>'+product_konv+'</td>\
                <td>'+product_price+'</td>\
                <td>'+remark+'</td>\
                <td>'+btn_serial+'</td>\
            </tr>';
    $(".table-add-product tbody").append(item);
}

function get_receive_product(v)
{   
    console.log(v);
    remove_row();
    $.ajax({
        url : host+"api/receive_detail",
        data : v,
        type: "POST",
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);            
            }
            if(data.status){
                $.each(data.list_data,function(i,v){
                    add_return_row("add",v);
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert("Error get data receive detail")
        }
    });
}