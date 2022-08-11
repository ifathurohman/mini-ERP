var mobile      = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host        = window.location.origin+'/';
var url         = window.location.href;
var url_list    = host + "category/ajax_list/";
var url_edit    = host + "category/edit/";
var url_hapus   = host + "category/delete/";
var url_active  = host + "category/active/";
var url_simpan  = host + "category/save";
var url_update  = host + "category/update";

var save_method; //for save method string
var table;
var page_name;
var url_modul;

$(document).ready(function() {
    filter_table();
});

function filter_table(page){
    data_page   = $(".data-page, .page-data").data();
    page_name   = data_page.page_name;
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    hakakses    = data_page.hakakses;
    MenuID      = data_page.menuid;
    data_post   = {
        Modul : modul,
        MenuID : MenuID,
    }
    url    = url_list;
    table = $('#table').DataTable({
        "destroy": true,
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "searching": true,
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
        "columnDefs": [
        {
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],
    });
}

function add_data(){
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add New Category'); // Set Title to Bootstrap modal title
    $('[name="crud"]').val("insert");
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable
    
    var url;
    url = url_simpan;
    var form        = $('#form')[0]; // You need to use standard javascript object here
    var formData    = new FormData(form);
    $.ajax({
        url : url,
        type: "POST",
        data:  formData,
        mimeType:"multipart/form-data", // upload
        contentType: false, // upload
        cache: false, // upload
        processData:false, //upload
        dataType: "JSON",
        success: function(data)
        {
            console.log(data);
            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal').modal("hide");
                swal('Info','Saving data is success','');
                reload_table();
            }
            else
            {
                if(data.message){
                    swal({ html:true,type: "warning", title:'', text:data.message,});
                }
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
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}

function edit(id)
{
    save_method = 'update';
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('[name=crud]').val('update');
    //---------------------------------------------
    modal_title = 'Edit Data ' + page_name;

    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "developer"){
                console.log(data);
            }
            v = data.data;
            $('[name="CategoryID"]').val(v.CategoryID);
            $('[name="Name"]').val(v.Name);
            $('[name="SeoText"]').val(v.SeoText);
            $('[name="SeoImage"]').val(v.SeoImage);
            $('[name="Remark"]').val(v.Remark);
            $('[name="Icon"]').val(v.Icon);
           
            $('#modal').modal("show");
            $('.modal-title').text(modal_title);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function active_data(id,status){

    if(status == "active"){
        pesan = "Are you sure want to active data?";
        pesan_success = "Success";
    } else {
        pesan = "Are you sure want to nonactive data?";
        pesan_success = "Success";
    }
    swal({   
        title: pesan,   
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
                    url : url_active+id+"/"+status,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data)
                    {
                        if(data.status){
                            swal('Info',data.message, '');
                            reload_table();
                        }else{
                            swal('Info',data.message, '');
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