var host            = window.location.origin+'/';
var url             = window.location.href;
var page_login      = host + "main/login";
var page_register   = host + "main/register";
var url_simpan      = host + "content/simpan";
var url_list        = host + "content/ajax_list/";
var url_edit        = host + "content/ajax_edit/";
var url_hapus       = host + "content/delete/";
var save_method; //for save method string
var table;
var page_name;
var summernote;


$(document).ready(function() {
    page_data = $(".page-data").data();
    page_name = page_data.page_name;
    title_page  = page_data.title;
    $('#category').tokenfield({
      autocomplete: {
        source: ['General','Article','Tutorial','Website','Android','Faq'],
        delay: 100
      },
      showAutocompleteOnFocus: true
    });
    summernote = $('#content').summernote({
        tabsize: 2,
        height: 400,
        minHeight: 400,
      });
    $('.dropify').dropify();

    filter();
});

function filter(){
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
        "iDisplayLength": 25
    });
}

function tambah(){
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $("#form input, #form textarea, #form select").attr("disabled",false);
    $('.help-block').empty(); // clear error string
    $('#modal').modal('show'); // show bootstrap modal
    $('.modal-title').text(language_app.lb_add_content); // Set Title to Bootstrap modal title
    $("#btnSave").show();
    $('#author').val('RC Electronic');
    $('#method').val(save_method);
    resetform();
}

function resetform(){
    $('#category').tokenfield('setTokens', ['']);
    summernote.summernote('code', '');
    $('.dropify-clear').click();
}

function save(){
    proses_save_button();
    var url = url_simpan;
    var form = $('form')[0]; // You need to use standard javascript object here
    var formData = new FormData(form);
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
                $('#modal').modal("hide");
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++){
                    if(data.inputerror[i] == "category"){
                        $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error');
                        $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
                    }else{
                        $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error');
                        $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
                    }
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

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}

function edit(id)
{
    save_method      = 'update';
    $('#method').val(save_method);
    $('#ContentID').val(id);
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty(); // clear error string
    resetform();
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);
            } 
            $('#name').val(data.Name);
            $('#category').tokenfield('setTokens', data.Category);
            $('#author').val(data.Author);
            if(data.Status == 1){
                $("#publish").prop("checked",true);
            } else {
                $("#unpublish").prop("checked",true);
            }
            if(data.Image){
                var img = host+data.Image;
                img = '<img src="'+img+'" />';
                console.log(img);
                $(".dropify-render").append(img);
                $(".dropify-preview").css("display", "block");
            }
            summernote.summernote('code', data.Description);
            $('#modal').modal("show");
            $('.modal-title').text('Edit Content');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);

        }
    });
}
function hapus(id){
    swal({   title: language_app.lb_ask,   
    // text: "You will not be able to recover this data !",   
    type: "warning",   
    showCancelButton: true,   
    confirmButtonColor: "#DD6B55",   
    confirmButtonText: language_app.lb_deleted,   
    cancelButtonText: language_app.lb_canceled1,   
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
                swal('',language_app.lb_success, 'success');
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                swal('Error deleting data');
            }
        });   
    } 
     else {
         swal(language_app.lb_canceled, '', "error");   } 
    });
}