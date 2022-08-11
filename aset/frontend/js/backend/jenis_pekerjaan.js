var mobile      = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
// var host        = window.location.origin+'/discovery/';
var host        = window.location.origin+"/";
var url         = window.location.href;
var url_list    = host + "jenis_pekerjaan/ajax_list/";
var url_hapus   = host + "jenis_pekerjaan/delete/";
var url_active  = host + "jenis_pekerjaan/active/";
var url_simpan  = host + "jenis_pekerjaan/save";
var url_edit    = host + "jenis_pekerjaan/edit/";
var url_update  = host + "jenis_pekerjaan/update/";

var save_method; //for save method string
var table;
var page_name;
var url_modul;  

$(document).ready(function() {
    data_page   = $(".data-page, .page-data").data();
    page_name   = data_page.page_name;
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;

    url_list = url_list+url_modul;

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
} );

function tambah()
{
    save_method = 'add';
    $('#form')[0].reset();
    $('form div').removeClass('has-error');
    $('.help-block').empty();
    $('#modal').modal({backdrop: "static"});
    $('.modal-title').text('Tambah Data');
    $('[name="crud"]').val("insert");    
}

function edit(id)
{
    save_method = 'update';
    $('#form')[0].reset();
    $('form div').removeClass('has-error');
    $('.help-block').empty();
    if(modul == "company"){
       save_method = "view";
       modal_title = "View Detail";
       $("#btnSave").hide(); 
       $("#form input, #form textarea").attr("disabled",true);
    } else {
        modal_title = 'Ubah Data ';
    }
    console.log(url_edit);
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.panel == "developer"){
                console.log(data);
            }
            v = data.data;
            $('[name="WorkStatusID"]').val(v.WorkStatusID);
            $('[name="Code"]').val(v.Code);
            $('[name="Name"]').val(v.Name);
            $('[name="Remarks"]').val(v.Remarks);
            if(v.Approval == 1){
                $("#form #ya").prop("checked",true);
            } else {
                $("#form #no").prop("checked",true);
            }
            $('#modal').modal({backdrop: "static"});
            $('.modal-title').text(modal_title);
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
    table.ajax.reload(null,true); //reload datatable ajax
}
function save(method)
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable
    var url;
    if(save_method == 'add') {
        url = url_simpan;
    } else if(save_method == "update"){
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
                reload_table();
                if(method == "new"){
                    save_method = "add";
                    $('#form')[0].reset();
                    $('form div').removeClass('has-error');
                    $('.help-block').empty();
                } else {
                    $('#modal').modal("hide");
                }
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
            console.log('error');
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
function active_data(id,status){

    if(status == "active"){
        pesan = "Apa anda yakin akan mengaktifkan data ini ?";
        pesan_success = "data berhasil diaktifkan";
    } else {
        pesan = "Apa anda yakin akan menonaktifkan data ini ?";
        pesan_success = "data berhasil dinonaktifkan";
    }
    swal({   
        // type:"input",
        title: "Info",   
        text: pesan,   
        // type: "warning",   
        showCancelButton: true,   
        // confirmButtonColor: "#DD6B55",   
        confirmButtonText: "Ya",   
        cancelButtonText: "Tidak",   
        closeOnConfirm: true,   
        closeOnCancel: true }, 
        function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    url : url_active+id+"/"+status,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data){
                        // swal("", pesan_success, "success");   
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown){
                        swal('gagal melakukan ubah data');
                    }
                });
            }
    });

}