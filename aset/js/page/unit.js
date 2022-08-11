var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host = window.location.origin+'/';
var url = window.location.href;
var page_login = host + "main/login";
var page_register = host + "main/register";
var save_method; //for save method string
var table;
var url_list = host + "unit/ajax_list/";
var url_edit = host + "unit/ajax_edit/";
var url_hapus = host + "unit/ajax_delete/";
var url_simpan = host + "unit/simpan";
var url_update = host + "unit/ajax_update";

$(document).ready(function() {
    data_page   = $(".data-page, .page-data").data();
    filter_table();
});

function filter_table(page){
    data_page   = $(".data-page, .page-data").data();
    url         = url_list;

    fSearch             = $('#form-filter [name=fSearch]').val();
    fActive             = $('#form-filter [name=fActive]').val();

    data_post = {
        Search              : fSearch,
        Active              : fActive,
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
    save_method = 'add';
    $(".vaction").hide();
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add New Unit'); // Set Title to Bootstrap modal title
    $('[name="crud"]').val("insert");
}
function edit(id)
{
    // $(".parent_unit_v").hide();
    save_method = 'update';
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty(); // clear error string
    //Ajax Load data from ajax
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
         success: function(json_respons)
        {   
            data = json_respons.data;
            console.log(data);
            $("[name=unitid]").val(data.unitid);
            $("[name=unit_name]").val(data.name);
            $("[name=conversion]").val(data.conversion);
            $("[name=type]").val(data.type);
            $("[name=remark]").val(data.remark);
            $(".vaction").hide();
            $('.modal-title').text('Edit Data');
            $('#modal').modal('show'); // show bootstrap modal
            $('#btnSave, .save').text('save'); //change button text
            $('#btnSave, .save').show(300); //set button enable
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function view_unit(id,page)
{
    reset_button_action();
    // $(".parent_unit_v").hide();
    // save_method = 'update';
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty(); // clear error string
    //Ajax Load data from ajax
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(json_respons)
        {
            data = json_respons.data;
            console.log(data);
            $("[name=unitid]").val(data.unitid);
            $("[name=unit_name]").val(data.name);
            $("[name=conversion]").val(data.conversion);
            $("[name=type]").val(data.type);
            $("[name=remark]").val(data.remark);
            
            $('.modal-title').text('Unit Detail');
            $('#modal').modal('show'); // show bootstrap modal
            $('#btnSave, .save').text('save'); //change button text
            $('#btnSave, .save').hide(300); //set button enable
            set_button_action(json_respons);
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
            console.log(data);
            if(data.status) //if success close modal and reload ajax table
            {
                swal('',data.message,'success');
                $('#modal').modal("hide");
                reload_table();
            }
            else{
              $('.form-group').removeClass('has-error'); // clear error class
              $('.help-block').empty();
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                    //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                    // swal('',data.error_string[i],'warning');
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

function modal_import()
{
    $('#modal-import').modal('show');
    $('.modal-title').text('Import Data');
}
function import_data()
{
    $('.btn-import').text('Import..'); //change button text
    $('.btn-import').attr('disabled',true); //set button enable

    url = host+"unit/import";
    var form = $('#form-import')[0]; // You need to use standard javascript object here
    var formData = new FormData(form);
    $.ajax({
    url : url,
    type: "POST",
    data: $('#form').serialize(),
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
            swal('success',data.message,'success');
            reload_table();
        } else {
            swal('',data.message,'warning');  
        }
        $('.btn-import').text('Import'); //change button text
        $('.btn-import').attr('disabled',false); //set button enable
    },
    error: function (jqXHR, textStatus, errorThrown){
        alert("import data error");
        $('.btn-import').text('Import'); //change button text
        $('.btn-import').attr('disabled',false); //set button enable
        console.log(jqXHR.responseText);
    }
  });
}
function clear_img(){
  var drEvent = $('.dropify').dropify();
  drEvent = drEvent.data('dropify');
  drEvent.resetPreview();
  drEvent.clearElement();
}
