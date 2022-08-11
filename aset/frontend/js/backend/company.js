var mobile      = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
// var host        = window.location.origin+'/discovery/';
var host        = window.location.origin+"/";
var url         = window.location.href;
var url_list    = host + "company/ajax_list/";
var url_hapus   = host + "company/delete/";
var url_active  = host + "company/active/";
var url_simpan  = host + "company/save/";
var url_simpan  = host + "company/save/";
var url_edit    = host + "company/edit/";
var url_update  = host + "company/update/";

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
    $('#form')[0].reset(); // reset form on modals
    $('form div').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal({backdrop: "static"}); // show bootstrap modal
    $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title
    $('[name="crud"]').val("insert");


    $(".dropify-preview").hide();
    $(".dropify-render img").remove();

    // $('[name="City"]').val('none').trigger('change');
    // $('[name="Province"]').val('none').trigger('change');

    // tambah();
    // resetForm();
    // resetMap();
    // resetlocation("provinces","add");
}

function resetMap(){
    myCenter = myCenter_default;
    resizingMap();
    deleteMarkers();
    resetMarkerInput();
}

function edit(id)
{
    save_method = 'update';
    $('#form')[0].reset();
    $('form div').removeClass('has-error'); // clear error class
    $('.help-block').empty();
    $('[name="City"]').val('none').trigger('change');
    $('[name="Province"]').val('none').trigger('change');

    $(".dropify-preview").hide();
    $(".dropify-render img").remove();

    modal_title = 'Ubah Data ';
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
                console.log(data);
            if(data.HakAkses == "rc"){
            }
            v = data.data;
            $('[name="CompanyID"]').val(v.CompanyID);
            $('[name="Name"]').val(v.Name);
            $('[name="Email"]').val(v.Email);
            $('[name="Password"]').val("********");
            $('[name="Address"]').val(v.Address);
            $('[name="Phone"]').val(v.Phone);
            $('[name="JoinDate"]').val(v.JoinDate);
            $('[name="Province"]').val(v.ProvinceID);
            $('[name="City"]').val(v.CityID);
            //select2
            $('[name="Province"]').val(v.ProvinceID).trigger('change');
            list_op = $(".city_select");
            if(list_op.length <= 1){
                city_select(v.ProvinceID,v.CityID);
            } else {
                $('[name="City"]').val(v.CityID).trigger('change');
            }


            img = '<img src="'+v.Image+'" />';
            $(".dropify-render").append(img);
            $(".dropify-preview").show();

            // if(v.Lat && v.Lng){
            //     latlng = new google.maps.LatLng(v.Lat, v.Lng);
            //     infowindow.setContent(v.Address);
            //     addMarker(latlng);
            //     resizingMap(v.Lat,v.Lng);
            // }
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
function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable

    var url;
    if(save_method == 'add') {
        url = url_simpan+'save';
    } else if(save_method == "update"){
        url = url_simpan+'update';
    }
    $('#btnSave').button('loading');
    $('form div').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    var form        = $('#form')[0]; // You need to use standard javascript object here
    var formData    = new FormData(form);

    $.ajax({
        //url : urlku,
        //type: "POST",
        //data:  $('#form').serialize(),
        //dataType: "JSON",
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
                reload_table();
            }
            else
            {
                $('form div').removeClass('has-error'); // clear error class
                $('.help-block').empty();
                if(data.inputerror){
                  for (var i = 0; i < data.inputerror.length; i++)
                  {
                        if(data.type[i] == 'select' || data.type[i] == "icon"){
                            if(data.type[i] == 'select'){
                                $("."+data.inputerror[i]+"Alert").text(data.error_string[i]);
                            } else {
                                $('[name="'+data.inputerror[i]+'"], #'+data.inputerror[i]).parent().next().text(data.error_string[i]);
                            }
                            $('#'+data.inputerror[i]).parent().addClass('has-error');
                            $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error');
                        } else {
                            $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
                            $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error');
                        }
                  }
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
            console.log(jqXHR.responseText);
            console.log('error');
        }
    });
    $('#btnSave').button('reset');

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
                        url : url_hapus+id,
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