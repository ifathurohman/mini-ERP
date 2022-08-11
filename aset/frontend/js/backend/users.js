var mobile      = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
// var host        = window.location.origin+'/discovery/';
var host        = window.location.origin+"/";
var url         = window.location.href;
var url_list    = host + "user/ajax_list/";
var url_hapus   = host + "user/delete/";
var url_active  = host + "user/active/";
var url_simpan  = host + "user/save";
var url_simpan  = host + "user/save";
var url_edit    = host + "user/edit/";

var save_method; //for save method string
var table;

$(document).ready(function() {

    data_page   = $(".data-page, .page-data").data();
    MenuID      = data_page.menuid;
    data_post   = {
        MenuID : MenuID,
    }
    table = $('#table').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        ajax: {
            url: url_list,
            type: "POST",
            data: data_post,
            error: function (jqXHR, textStatus, errorThrown) {
                // Do something here
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
});
function tambah()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('form div').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal({backdrop: "static"}); // show bootstrap modal
    $('.modal-title').text('Add Data'); // Set Title to Bootstrap modal title
    $('[name="crud"]').val("insert");

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
                text: 'Saving data is success',   
                // type: "warning",   
                showCancelButton: false,   
                // confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Yes",   
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
                    	error_label = label+" cannot be null";
                    }

                    if(data.type[i] == ''){
                    	$('[name="'+data.inputerror[i]+'"]').next().text(error_label);
                    }

                    $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                    
                }
            }
            $('#btnSave').button('reset');
        },
        error: function (jqXHR, textStatus, errorThrown){
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
            if(data.hakakses == "developer"){
                console.log(data);
            }
            v = data.data;
            $('[name=email]').val(v.Email);
            $('[name=UserID]').val(v.UserID);
            $('[name="Name"]').val(v.Name);
            $('[name=Email]').val(v.Email);
            $('[name=password]').val('********');
            $('#role').select2().select2('val', [v.HakAksesID]);

            $('#HakAksesID').val(v.HakAksesID);
            $('#hak_akses').val(v.hakaksesName);

            $(".dropify-preview").show();
			img = '<img src="'+host+v.ImageUser+'" />';
			$(".dropify-render").append(img);

            if(data.contact){
                $.each(data.contact,function(i,v){
                    add_contact(v);
                });
            }

            if(data.contact){
                $.each(data.contact,function(i,v){
                    add_contact(v);
                });
            }

            $('#modal').modal({backdrop: "static"});
            $('.modal-title').text("Edit Data");
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            swal('Info','Failed to get data','');
        }
    });
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable
    $('form div').removeClass('has-error'); // clear error class
    var url;
    var form        = $('#form')[0]; // You need to use standard javascript object here
    var formData    = new FormData(form);
    url = url_simpan;
    var file = $('[name=gambar]')[0].files[0];
    if(file && file.size > 2000000) { //2 MB (this size is in bytes)
        toastr.error('Image size too big, size maximum is 2MB',"Information");
        $('#btnSave').text('save');
        $('#btnSave').attr('disabled',false);
        return;
    }
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
                if(data.data_session){
                    $(".profile-name").text(data.data_session.Name);
                }

                if(data.page == 'user_edit'){
                    swal('Info','Saving data is success','');
                    // alert("berhasil menyimpan data");
                }

                $('#modal').modal("hide");
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error');         
                    // swal('',data.error_string[i],'warning');
                    if(data.type[i] == 'select'){
                        $("."+data.inputerror[i]+"Alert").text(data.error_string[i]);
                    } else {
                        $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); 
                    }
                }
                if(data.message){
                    swal('Info',data.message,'');
                }
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable
 
 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            swal('Info','Failed to saving data','');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable
            console.log(jqXHR.responseText);
            
        }
    });
}

function active_data(id,status){

    if(status == "active"){
        pesan = "Are you sure to active this data ?";
        pesan_success = "data has been active";
    } else {
        pesan = "Are you sure to nonactive this data ?";
        pesan_success = "data has been nonactive";
    }
    swal({   
            // type:"input",
            title: "Info",   
            text: pesan,   
            // type: "warning",   
            showCancelButton: true,   
            // confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Yes",   
            cancelButtonText: "No",   
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
                            swal('failed to saving data');
                        }
                    });
                }
    });

}
