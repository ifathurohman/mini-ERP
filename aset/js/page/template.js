var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host 			= window.location.origin+'/pipesys_qa/';
var url 			= window.location.href;
var page_login 		= host + "main/login";
var page_register 	= host + "main/register";
var url_list 		= host + "template/ajax_list/";
var url_edit        = host + "template/ajax_edit/";
var url_delete 		= host + "template/delete/";
var url_simpan      = host + "template/save";
var url_print 		= host + "template/cetak/";
var save_method; //for save method string
var table,modul;
var activeEditor;

$(document).ready(function() {
    data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    change_div();
    initialize();
    filter_table();
    filter_table();
});

function initialize(){
    activeEditor = tinymce.init({
        selector: "textarea#Content",
        theme: "modern",
        height: 360,
        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "save table contextmenu directionality emoticons template paste textcolor"
        ],
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        },
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
        style_formats: [{
            title: 'Bold text',
            inline: 'b'
        }]
    });
}

function filter_table(page){
	data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;

    url    		= url_list+url_modul+"/"+modul;

    fSearch             = $('#form-filter [name=fSearch]').val();
    fStatus             = $('#form-filter [name=fStatus]').val();
    // fTypeStatus         = $('#form-filter [name=fTypeStatus]').val();

    data_post = {
        Search              : fSearch,
        Status              : fStatus,
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
            "targets": [0], //last column
            "orderable": false, //set not orderable
        },],
    });
}
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}

function tambah(){
    change_div('open');
    tinymce.remove('#Content');
    $('#Content').val('');
    initialize();
    save_method = 'add';
    modulTitle = modul;
    $(".readonly").attr("readonly",true);
    $("#form input, #form textarea, #form select").attr("disabled",false);
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('[name="crud"]').val("insert");

    resetform();
    reset_button_action();
    //-----------------------------------------------------------------------------
}

function resetform(){
    $('.dropify-clear').click();
}

function save(method)
{
    $('#btnSave').button('loading');
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty();
    var form = $('#form')[0]; // You need to use standard javascript object here
    var formData = new FormData(form);

    $.ajax({
        url : url_simpan,
        type: "POST",
        data:  formData,
        mimeType:"multipart/form-data", // upload
        contentType: false, // upload
        cache: false, // upload
        processData:false, //upload
        dataType: "JSON",
        success: function(data)
        {
            if(data.hak_akses == "super_admin"){
                console.log(data);
            }
            if(data.status) //if success close modal and reload ajax table
            {
                change_div();
                reload_table();
                swal('',data.message,'success');
            }
            else
            {
              swal('',language_app.lb_incomplete_form, 'warning');
              $('.form-group').removeClass('has-error'); // clear error class
              $('.help-block').empty();
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    label = $('[name="'+data.inputerror[i]+'"]').parent().find("label").text();
                    label = label.replace("(*)", "");
                    if(data.error_string[i] == ''){
                        error_label = label+" cannot be null";
                    }else{
                        error_label = data.error_string[i];
                    }
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

function active(id,method){
    button = language_app.lb_deleted;
    if(method == "nonactive"){
        button = language_app.lb_undeleted;
    }
    swal({   
        title: language_app.lb_ask,   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: button,   
        cancelButtonText: language_app.lb_canceled1,   
        closeOnConfirm: false,   
        closeOnCancel: false }, 
        function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    url : url_delete+id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data)
                    {
                        if(data.status){
                            swal(data.title, data.message, "success"); 
                        }else{
                            swal(data.title, data.message, "warning"); 
                        }
                        $('#modal-print').modal('hide');
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        swal('Error deleting data');
                        console.log(jqXHR.responseText);
                    }
                });  
            } 
            else {
                swal("Canceled", "Your data is safe :)", "error");   
            } 
    });
}

function edit(id)
{
    // $(".parent_category_v").hide();
    save_method = 'update';
    $('#modal-print').modal('hide');
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty(); // clear error string
    $('[name="crud"]').val("update");
    resetform();
    reset_button_action();
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data){
                $('#form [name=TemplateID]').val(data.TemplateID);
                $('#form [name=Name]').val(data.Name);
                $('#form [name=Remark]').val(data.Remark);
                $('#form [name=Type]').val(data.Type);
                tinymce.remove('#Content');
                $('#Content').val(data.Content);
                initialize();
                if(data.Image){
                    var img = host+data.Image;
                    img = '<img src="'+img+'" />';
                    $(".dropify-render").append(img);
                    $(".dropify-preview").css("display", "block");
                }
            }
            change_div('open');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

// view
function view_print(id,page){
    reset_button_action();
    $('#modal-print').modal('show');
    $('#modal-print .modal-title').text("View");
    url = url_print+id;

    data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;

    data_post  = {
        modul            : modul,
        url_modul        : url_modul,
    }

    $("#view-print").load(url,data_post,function(){
        $(".div-loader").hide();
    });

    action_print_button();
}
// end view

function change_div(page){
    if(page == "open"){
        $('.div-list').hide(300);
        $('.div-form').show(300);
    }else{
        $('.div-list').show(300);
        $('.div-form').hide(300);
    }
}