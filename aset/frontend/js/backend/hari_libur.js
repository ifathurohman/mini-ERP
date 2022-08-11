var mobile      = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
// var host        = window.location.origin+'/discovery/';
var host        = window.location.origin+"/";
var url         = window.location.href;
var url_list    = host + "hari_libur/ajax_list/";
var url_edit    = host + "hari_libur/edit/";
var url_hapus   = host + "hari_libur/delete/";
var url_active  = host + "hari_libur/active/";
var url_simpan  = host + "hari_libur/save";
var url_update  = host + "hari_libur/update";

var save_method; //for save method string
var table;
var page_name;
var url_modul;

var id_kontak;

$(document).ready(function() {
    data_page   = $(".data-page, .page-data").data();
    page_name   = data_page.page_name;
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    if(modul == "company"){
        url_list = url_list_company+url_modul+"/"+modul;
    } else {
        url_list = url_list+url_modul+"/"+modul;
    }
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
function cek_aja(element){
    StartDate = $("[name=StartDate]").val();
    if($(element).is(':checked')){
        $("[name=EndDate]").val(StartDate);
    } else {
        $("[name=EndDate]").val("");
    }
}
function tambah()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('form div').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal({backdrop: "static"}); // show bootstrap modal
    $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title
    $('[name="crud"]').val("insert");
}
function edit(id)
{
    save_method = 'update';
    $('#form')[0].reset();
    $('form div').removeClass('has-error'); // clear error class
    $('.help-block').empty();
    if(modul == "company"){
       save_method = "view";
       modal_title = "View Detail";
       $("#btnSave").hide(); 
       $("#form input, #form textarea").attr("disabled",true);
    } else {
        modal_title = 'Ubah Data ';
    }
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "RC"){
                console.log(data);
            }
            v = data.data;
            $('[name="HolidayID"]').val(v.HolidayID);
            $('[name="Name"]').val(v.Name);
            $('[name="StartDate"]').val(v.StartDate);
            $('[name="EndDate"]').val(v.EndDate);

            if(v.StartDate == v.EndDate){
                $("[name=Cek]").prop("checked",true);
            }

            if(data.contact){
                $.each(data.contact,function(i,v){
                    add_contact(v);
                });
            }

            $('#modal').modal({backdrop: "static"});
            $('.modal-title').text(modal_title);
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
function save(method)
{
    $('#btnSave').button('loading');
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
            if(data.status) //if success close modal and reload ajax table
            {
                if(method == "close"){
                    $('#modal').modal("hide");
                } else if(method == "new"){
                    save_method = "add";
                    $('#form')[0].reset();
                    $('form div').removeClass('has-error'); // clear error class
                    $('.help-block').empty();
                }
                reload_table();
            }
            else
            {
              $('.form-group').removeClass('has-error'); // clear error class
              $('.help-block').empty();
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    label = $('[name="'+data.inputerror[i]+'"]').parent().parent().find("label").text();
                    label = label.replace("(*)", "");

                    error_label = label+" tidak boleh kosong";
                    $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                    $('[name="'+data.inputerror[i]+'"]').next().text(error_label);
                }
            }
            $('#btnSave').button('reset');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').button('reset');
            console.log(jqXHR.responseText);
        }
    });
}
function hapus(id)
{
    swal({   title: "Apa anda yakin ?",   
             // text: "You will not be able to recover this data !",   
             type: "warning",   
             showCancelButton: true,   
             confirmButtonColor: "#DD6B55",   
             confirmButtonText: "Ya",   
             cancelButtonText: "Tidak",   
             closeOnConfirm: true,   
             closeOnCancel: true }, 
             function(isConfirm){   
                if (isConfirm) { 
                    $.ajax({
                        url : url_hapus+id+"/nonactive",
                        type: "POST",
                        dataType: "JSON",
                        success: function(data){
                            swal("Hapus", "data berhasil di hapus", "success");   
                            reload_table();
                        },
                        error: function (jqXHR, textStatus, errorThrown){
                            swal('Error deleting data');
                        }
                    });
                }
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