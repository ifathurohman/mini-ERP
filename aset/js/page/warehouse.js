var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host = window.location.origin+'/pipesys_qa/';
var url = window.location.href;
var page_login = host + "main/login";
var page_register = host + "main/register";
var save_method; //for save method string
var table;
var url_list = host + "warehouse/ajax_list/";
var url_edit = host + "warehouse/ajax_edit/";
var url_hapus = host + "warehouse/ajax_delete/";
var url_simpan = host + "warehouse/simpan";
var url_update = host + "warehouse/ajax_update";

$(document).ready(function() {
    data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    title_page  = data_page.title;
    //datatables
    filter_table();

    if(data_page.id){
        view_warehouse(data_page.id);
    }
});
function filter_table(page){
    data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    url         = url_list+url_modul+"/"+modul;
    date_now    = data_page.date;

    fSearch             = $('#form-filter [name=fSearch]').val();
    fActive             = $('#form-filter [name=fActive]').val();
    // fTypeStatus         = $('#form-filter [name=fTypeStatus]').val();

    data_post = {
        Search              : fSearch,
        Active              : fActive,
        // Type                : fTypeStatus,
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
            "targets": [ -1,0], //last column
            "orderable": false, //set not orderable
        },],
    });
}
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}
function tambah()
{
    $("[name=Code]").attr("disabled",false);
    $(".vaction").hide();
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $("input, select").attr("disabled",false);
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add New '+ title_page); // Set Title to Bootstrap modal title
    $('[name="crud"]').val("insert");
    $('[name=WarehouseID]').val('');
    reset_button_action();
}
function edit(id)
{
    save_method = 'update';
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $("#form input, #form textarea, #form select").attr("disabled",false);
    $('.help-block').empty(); // clear error string
    reset_button_action();
    //Ajax Load data from ajax
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(json_respons)
        {   
        	data = json_respons.data;
            $("[name=WarehouseID]").val(data.WarehouseID);
            $("[name=Code]").val(data.Code);
            $("[name=Code]").attr("disabled",true);
            $("[name=Name]").val(data.Name);
            $("[name=Address]").val(data.Address);
            $("[name=Description]").val(data.Description);

            $('#modal').modal("show");
            $('.modal-title').text(title_page+' Edit');

            success_save_button()
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            success_save_button();
            alert('Error get data from ajax');
        }
    });
}
 
function view_warehouse(id,page)
{
    reset_button_action();
    action_print_button();

    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $("#form input, #form textarea, #form select, #button").attr("disabled",true);
    $('.help-block').empty(); // clear error string
    //Ajax Load data from ajax
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(json_respons)
        {
            data = json_respons.data;
            $("[name=WarehouseID]").val(data.WarehouseID);
            $("[name=Code]").val(data.Code);
            $("[name=Code]").attr("disabled",true);
            $("[name=Name]").val(data.Name);
            $("[name=Address]").val(data.Address);
            $("[name=Description]").val(data.Description);

            $('#modal').modal("show");
            $('.modal-title').text(title_page+' Edit');

            $('#modal').modal("show");
            $('.modal-title').text(title_page+' Detail');

            success_save_button();
            set_button_action(json_respons);
            $('.open').removeClass('open');
        },

        error: function (jqXHR, textStatus, errorThrown)
        {
            success_save_button();
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
    proses_save_button();
    
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
            if(data.status) //if success close modal and reload ajax table
            {
                swal('',language_app.lb_success,'success');
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
            success_save_button();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            success_save_button();
            console.log(jqXHR.responseText);
        }
    });
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
                            $(".modal:visible").modal('toggle');
                            reload_table();
                        },
                        error: function (jqXHR, textStatus, errorThrown){
                            swal('Error deleting data');
                        }
                    });
                    swal("Deleted!", "Your data has been deleted.", "success");   } 
                 else {
                     swal("Canceled", "Your data is safe :)", "error");   } 
    });
}
function active(id){

    swal({   title: "Are you sure?",   
             // text: "You will not be able to recover this data !",   
             type: "warning",   
             showCancelButton: true,   
             confirmButtonColor: "#DD6B55",   
             confirmButtonText: "Yes, undelete it!",   
             cancelButtonText: "No, cancel it!",   
             closeOnConfirm: false,   
             closeOnCancel: false }, 
             function(isConfirm){   
                 if (isConfirm) { 
                    $.ajax({
                    url : url_hapus+id+"/active",
                    type: "POST",
                    dataType: "JSON",
                    success: function(data)
                    {
                        $(".modal:visible").modal('toggle');
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown){
                        swal('Error undeleting data');
                    }
                });
                    swal("Undelete!", "Your data is active.", "success");   } 
                 else {
                     swal("Canceled", "Your data is safe :)", "error");   } 
    });
}
function clear_img(){
  var drEvent = $('.dropify').dropify();
  drEvent = drEvent.data('dropify');
  drEvent.resetPreview();
  drEvent.clearElement();
}

