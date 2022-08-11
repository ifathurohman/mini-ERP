var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host = window.location.origin+'/';
var url = window.location.href;
var page_login = host + "main/login";
var page_register = host + "main/register";
var save_method; //for save method string
var table;
var url_list = host + "sp_transaction_route/ajax_list/";
var url_edit = host + "sp_transaction_route/ajax_edit/";
var url_hapus = host + "sp_transaction_route/ajax_delete/";
var url_simpan = host + "sp_transaction_route/simpan";
var url_update = host + "sp_transaction_route/ajax_update";
var page_name;

var id_item_customer = 0;
$(document).ready(function() {
    page_data = $(".page-data").data();
    page_name = page_data.page_name; 


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
        "columnDefs": [
        {
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],
    });
});
function tambah()
{
    id_item_customer = 0;
    arrayNewCustomer = [];
    save_method = 'add';
    $("#form input, #form textarea, #form select").attr("disabled",false);
    $("#btnSave, #AddNewCustomer").show();
    $(".item-add-customer").remove();
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add New Transaction'); // Set Title to Bootstrap modal title
    $('[name="crud"]').val("insert");
    modal_width();
    add_new_customer();
}
function edit(id)
{
    id_item_customer = 0;
    arrayNewCustomer = [];
    save_method      = 'update';
    $("#form input, #form textarea, #form select").attr("disabled",true);
    $("#btnSave, #AddNewCustomer").hide();
    $(".item-add-customer").remove();
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty(); // clear error string
    $("#btnSave").hide();
    modal_width();
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            $('label').addClass('active');
            $('.validate').addClass('valid');
            $('[name="crud"]').val("update");
            $('[name="TransactionRouteID"]').val(data.TransactionRouteID);
            $('[name="Code"]').val(data.Code);
            $('[name="Date"]').val(data.Date);
            $('[name="Name"]').val(data.Name);
            if(data.list_detail.length > 0){
                $.each(data.list_detail,function(i,v){
                    add_new_customer(v);
                });
            }
            $('#modal').modal("show");
            $('.modal-title').text('Detail Data');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);

        }
    });
}
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}
function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable
    var url;
    if(save_method == 'add') {
        url = url_simpan;
        $.ajax({
            url : url,
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function(data)
            {
                if(data.status) //if success close modal and reload ajax table
                {
                    $('#modal').modal("hide");
                    reload_table();
                }
                else
                {
                  $('.form-group').removeClass('has-error'); // clear error class
                  $('.help-block').empty();
                    for (var i = 0; i < data.inputerror.length; i++)
                    {
                        $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                        //select parent twice to select div form-group class and add has-error class
                        $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                        // swal('',data.error_string[i],'warning');
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
                console.log(jqXHR.responseText);
            }
        });

    } else {
        url = url_update;
    }
}
function hapus(id)
{
    swal({   title: "Are you sure?",   
             // text: "You will not be able to recover this data !",   
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
                        url : url_hapus+id+"/nonactive",
                        type: "POST",
                        dataType: "JSON",
                        success: function(data)
                        {
                            //if success reload ajax table
                            if(data.status){
                                reload_table();
                            } else {
                                swal('',data.message,'warning');
                            }
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
function active(id){
    $.ajax({
        url : url_hapus+id+"/active",
        type: "POST",
        dataType: "JSON",
        success: function(data){
            reload_table();
        },
        error: function (jqXHR, textStatus, errorThrown){
            swal('Error undeleting data');
        }
    });
}
function modal_width()
{
    if(!mobile){
        $("#modal .modal-dialog").css("width","80%");
    }
}
var arrayNewCustomer = [];
function add_new_customer(v="")
{
    input_detail = "";
    Customer    = "";
    Address     = "";
    Remark      = "";
    if(v){
        Customer    = v.VendorID+"-"+v.Name;
        Address     = v.Address;
        Remark      = v.Remark;
        TransactionRouteDetailID = v.TransactionRouteDetailID;
        input_detail = '<input name="TransactionRouteDetailID[]" type="hidden" value="'+TransactionRouteDetailID+'">';
    }

    id_item_customer += 1;
    vendormodal    = "'.add-new-customer-"+id_item_customer+"'";
    item ='<div class="col-sm-12 item-add-customer" id="item-customer-'+id_item_customer+'" style="padding-right: 0px; padding-left: 0px;">';
    item +='<div class="form-group col-sm-4">';
    item +='<label class="control-label">Customer Name</label>';
    item +='<div class="input-group">';
    item +='<span class="input-group-btn">';
    item +='<button type="button" class="btn btn-default btn-outline btn-x" onclick="remove_new_customer('+id_item_customer+')"><i class="icon fa-close"></i></button>';
    item +='</span>';
    item += input_detail;
    item +='<input name="Customer[]" type="text" class="form-control add-new-customer-'+id_item_customer+'" placeholder="" readonly="" value="'+Customer+'">';
    item +='<span class="input-group-btn">';
    item +='<button type="button" class="btn btn-default btn-outline btn-x" onclick="vendor_modal('+vendormodal+')"><i class="icon fa-search"></i></button>';
    item +='</span>';
    item +='</div>';

    item +='</div>';
    item +='<div class="form-group col-sm-4">';
    item +='<label class="control-label">Address</label>';
    item +='<textarea name="Address[]" id="" type="text" class="form-control add-new-customer-'+id_item_customer+'-address " autocomplete="Date" readonly="">'+Address+'</textarea>';
    item +='<span class="help-block"></span>';
    item +='</div>';
    item +='<div class="form-group col-sm-4">';
    item +='<label class="control-label">Remark and Notes</label>';
    item +='<textarea name="Remark[]" id="Remark[]" type="text" class="form-control">'+Remark+'</textarea>';
    item +='<span class="help-block"></span>';
    item +='</div>';
    item +='</div>';
    $(".list-add-customer").append(item);
    arrayNewCustomer.push(id_item_customer);

    if(v){
        $(".item-add-customer input, .item-add-customer textarea").attr("disabled","");
        $(".btn-x").addClass("disabled");
    }

}
function remove_new_customer(id)
{
    if(arrayNewCustomer.length == 1){
        alert("sorry this customer cannot be delete");
    }
    else{
        index       = arrayNewCustomer.indexOf(id);
        if (index !== -1) arrayNewCustomer.splice(index, 1);
        $("#item-customer-"+id).remove();
    }

}