var mobile      = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
// var host        = window.location.origin+'/discovery/';
var host        = window.location.origin+"/";
var url         = window.location.href;
var url_list    = host + "pegawai/ajax_list/";
var url_hapus   = host + "pegawai/delete/";
var url_active  = host + "pegawai/active/";
var url_simpan  = host + "pegawai/save";
var url_simpan  = host + "pegawai/save";
var url_edit    = host + "pegawai/edit/";

var save_method; //for save method string
var table;

$(document).ready(function() {
    data_page   = $(".data-page, .page-data").data();
    page_name   = data_page.page_name;
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    url_list    = url_list+url_modul;
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
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('form div').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal({backdrop: "static"}); // show bootstrap modal
    $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title
    $('[name="crud"]').val("insert");
    $('#Skill').select2().select2('val', []); 

    $(".dropify-preview").hide();
    $(".dropify-render img").remove();
    
}

function save(method)
{
    $('#btnSave').button('loading');
    var url;
    url = url_simpan;
    $('form div').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    var form        = $('#form')[0]; // You need to use standard javascript object here
    var formData    = new FormData(form);

    if(method == "user_edit"){
        formData.append("page", "user_edit");
    }

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
            if(data.status) //if success close modal and reload ajax table
            {
                swal({   
                // type:"input",
                title: "Info",   
                text: 'Berhasil proses data',   
                // type: "warning",   
                showCancelButton: false,   
                // confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Ya",   
                closeOnConfirm: true,   
                closeOnCancel: true }, 
                function(isConfirm){   
                    if (isConfirm) { 
                        if(data.image != ''){
                            if(method == "user_edit"){
                               location.reload();  
                            }
                        }
                    }
                });
                $('#modal').modal('hide'); // show bootstrap modal
                reload_table();

                
                
            }
            else
            {
                console.log(data);
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    label = $('[name="'+data.inputerror[i]+'"]').parent().parent().find("label").text();
                    label = label.replace("(*)", "");

                    if(data.error_string[i] != ''){
                        error_label = data.error_string[i];
                    }else{
                        error_label = label+" tidak boleh kosong";
                    }

                    if(data.type[i] == ''){
                        $('[name="'+data.inputerror[i]+'"]').next().text(error_label);
                    }

                    $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                    
                }
            }
            $('#btnSave').button('reset');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            status = jqXHR.status;
            if(status == 413){
                alert('ukuran gambar terlalu besar');
            }else{
                alert('Gagal menambah atau pengubah data');
            }
            $('#btnSave').button('reset');
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
    $('form div').removeClass('has-error'); // clear error class
    $('.help-block').empty();
    $('[name="crud"]').val("update");

    $(".dropify-preview").hide();
    $(".dropify-render img").remove();
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "rc"){
                console.log(data);
            }
            v = data.data;
            $('[name=UserID]').val(v.UserID);
            $('[name="Name"]').val(v.Name);
            $('[name=Email]').val(v.Email);
            $('[name=Password]').val('********');
            $('[name="Phone"]').val(v.Phone);
            $('[name="Remarks"]').val(v.Remarks);
            $('[name="Skill"]').val(v.Skill);
            $("[name=HakAksesID]").val(v.HakAksesID);

            var aar1 = [];
            $.each(data.data_skill, function(a,b){
                aar1.push(b);
            });

            $('#Skill').select2().select2('val', [aar1]);

            $('#HakAksesID').val(v.HakAksesID);
            $('#hak_akses').val(v.hakaksesName);

            img = '<img src="'+v.Image+'" />';
            $(".dropify-render").append(img);
            $(".dropify-preview").show();
            
            // if(data.contact){
            //     $.each(data.contact,function(i,v){
            //         add_contact(v);
            //     });
            // }

            $('#modal').modal({backdrop: "static"});
            $('.modal-title').text("Edit Data");
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
    $('form div').removeClass('has-error'); // clear error class
    $(".help-block").empty();
    var url;
 
    // if(save_method == 'add') {
    // } else {
    //     url = url_update;
    // }
    var form        = $('#form')[0]; // You need to use standard javascript object here
    var formData    = new FormData(form);
    url = url_simpan;
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
                if(data.page == 'user_edit'){
                    swal('Info','Berhasil menyimpan data','success');
                    // alert("berhasil menyimpan data");
                }

                $('#modal').modal("hide");
                reload_table();
            }
            else
            {
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
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable
 
 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Gagal menambah atau pengubah data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable
            console.log(jqXHR.responseText);
            
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

