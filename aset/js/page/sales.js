var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host 			= window.location.origin+'/pipesys_qa/';
var url 			= window.location.href;
var page_login 		= host + "main/login";
var page_register 	= host + "main/register";
var url_list 		= host + "sales/ajax_list/";
var url_edit        = host + "sales/ajax_edit/";
var url_delete 		= host + "sales/delete/";
var url_simpan 		= host + "sales/save";
var save_method; //for save method string
var table,modul;

$(document).ready(function() {
    data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    title_page  = data_page.title;
    //datatables
    filter_table();

    if(data_page.status == "add"){
        tambah();   
    }
});

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
        // Type                : fTypeStatus,
    }
    table = $('#table').DataTable({
        "destroy" 	: true,
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "searching" : false, //Feature Search false
        "order": [], //Initial no order.
         "language": {                
            "infoFiltered": ""
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": url,
            "type": "POST",
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

function tambah(){
    save_method = 'add';
    modulTitle = modul;
    $(".readonly").attr("readonly",true);
    $("#form input, #form textarea, #form select").attr("disabled",false);
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add New '+ title_page); // Set Title to Bootstrap modal title
    $('[name="crud"]').val("insert");
    reset_button_action();
    //-----------------------------------------------------------------------------
}

function save(method)
{
    proses_save_button();
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty();
    $.ajax({
        url : url_simpan,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {
            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal').modal("hide");
                reload_table();
                swal('',data.message,'success');
            }
            else
            {
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

function active(id,method){
    button = 'Yes, delete it!';
    if(method == "nonactive"){
        button = 'Yes, undelete it!';
    }
    swal({   
        title: "Are you sure?",   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: button,   
        cancelButtonText: "No, cancel it!",   
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
    $('#form')[0].reset();
    $("#form input, #form textarea, #form select, #button").attr("disabled",false);
    $('.form-group').removeClass('has-error');
    $('.help-block').empty(); // clear error string
    $('[name="crud"]').val("update");
    //Ajax Load data from ajax
    reset_button_action();

    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(json_respons)
        {   
            data = json_respons.data;
            if(data){
                $(".vaction").hide();
                $('#form [name=SalesID]').val(data.SalesID);
                $('#form [name=Code]').val(data.Code);
                $("[name=Code]").attr('disabled',true);
                $('#form [name=Name]').val(data.Name);
                $('#form [name=Phone]').val(data.Contact);
                $('#form [name=City]').val(data.City);
                $('#form [name=Address]').val(data.Address);
                $('#form [name=Remark]').val(data.Remark);
            }
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

function view_sales(id,page)
{
    reset_button_action();
    action_print_button();
    // $(".parent_category_v").hide();
    // save_method = 'update';
    $('#form')[0].reset();
    $("#form input, #form textarea, #form select, #button").attr("disabled",true);
    $('.form-group').removeClass('has-error');
    $('.help-block').empty(); // clear error string
    $('[name="crud"]').val("update");
    //Ajax Load data from ajax
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(json_respons)
        {
            data = json_respons.data;  
            console.log(data);
            if(data){
                $('#form [name=SalesID]').val(data.SalesID);
                $('#form [name=Code]').val(data.Code);
                $('#form [name=Name]').val(data.Name);
                $('#form [name=Phone]').val(data.Contact);
                $('#form [name=City]').val(data.City);
                $('#form [name=Address]').val(data.Address);
                $('#form [name=Remark]').val(data.Remark);
            }

            $('#modal').modal("show");
            $('.modal-title').text(title_page+' Edit');

            success_save_button();
            set_button_action(json_respons);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            success_save_button();
            alert('Error get data from ajax');
        }
    });
}

function modal_import()
{
    $(".dropify-clear").click();
    $('#modal-import').modal('show');
    $('.modal-title').text('Import Data');
}

function import_data(){
    clear_img();
    $('#modal-import').modal('show');
    $('#modal-import .modal-title').text('Import Data');
}

function clear_img(){
  var drEvent = $('.dropify').dropify();
  drEvent = drEvent.data('dropify');
  drEvent.resetPreview();
  drEvent.clearElement();
}

function upload_import(){
    proses_save_button("next","btn-import");

    url = host+"sales/import";
    var form = $('#form-import')[0]; // You need to use standard javascript object here
    var formData = new FormData(form);
    $.ajax({
    url : url,
    type: "POST",
    // data: $('#form').serialize(),
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
            // swal('',data.message,'success');
            // reload_table();
            item_import_data(data);
        } else {
            swal('',data.message,'warning');  
        }
        success_save_button("next","btn-import");
    },
    error: function (jqXHR, textStatus, errorThrown){
        alert("import data error");
        success_save_button("next","btn-import");
        console.log(jqXHR.responseText);
    }
  });
}

function item_import_data(data){
    $('#modal-import-data').modal('show');
    $('#modal-import-data .modal-title').text("Import Data Detail");
    $('#modal-import-data .content-import').empty();
    $('#modal-import-data .div-loader').hide();
    if(data.data.length>0){
        item = '<table id="table-import-data" data-filename="'+data.inputFileName+'" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">';
            item += '<thead><tr>';
            item += '<th style="width:50px"></th>';
            total_column_header = data.header[0].length;
            total_failed  = 0;
            total_success = 0;
            $.each(data.header[0],function(k,v){
                item += '<th>'+v+'</th>';
            });
            item += '<th>Status</th>';
            item += '<th>Message</th>';
            item += '</tr></thead>';

            item += '<tbody>';
            $.each(data.data,function(k,v){
                checkbox_status = '';
                background      = 'bg-merah-pias';
                label_status    = 'Failed';
                status_data     = '';
                if(v.status){
                    total_success += 1;
                    checkbox_status = ' checked ';
                    background = '';
                    label_status    = '<hijau>Success</hijau>';
                    if(v.status_data == "insert"){
                        status_data = '<br><hijau>Insert</hijau>';
                    }else{
                        status_data = '<br><hijau>Update</hijau>';
                    }
                }else{
                    total_failed += 1;
                }

                checkbox = '<div class="checkbox-custom checkbox-primary">\
                  <input type="checkbox" '+checkbox_status+' disabled>\
                  <label style="color:white">.</label>\
                </div>';

                item += '<tr class="'+background+'">';
                item += '<td>'+checkbox+'</td>';
                item += '<td>'+v.Code+'</td>';
                item += '<td>'+v.Name+'</td>';
                item += '<td>'+v.Contact+'</td>';
                item += '<td>'+v.City+'</td>';
                item += '<td>'+v.Address+'</td>';
                item += '<td>'+v.Remark+'</td>';
                item += '<td>'+label_status+status_data+'</td>';
                item += '<td>'+v.Message+'</td>';
                item += '</tr>';
            });
            item += '</tbody>';

        item += '</table>';

        item_total = '<span>Total Data : '+data.data.length+', '+total_success+' Success, '+total_failed+' Fail'+'</span>';
        $('#modal-import-data .content-import').append(item_total);
        $('#modal-import-data .content-import').append(item);
        $('#modal-import-data #table-import-data').DataTable({
            "destroy"   : true,
        });
    }
    else{
        item = '<center><h4>Data Not Found</h4></center>';
        $('#modal-import-data .content-import').append(item);
    }
}

// 20190716 MW
// save import
function save_import(){
    tag_data = $('#modal-import-data #table-import-data').data();
    if(tag_data.filename){
        proses_save_button('import','btn-import-data');
        data_post = {filename : tag_data.filename};
        $.ajax({
            url : host+"sales/save_import",
            type: "POST",
            data : data_post,
            dataType: "JSON",
            success: function(data){
                if(data.hakakses == "super_admin"){
                    console.log(data);
                }
                if(data.status){
                    $('#modal-import-data').modal('hide');
                    swal('',data.message, 'success');
                    reload_table();
                }else{
                    swal('',data.message, 'warning');
                }
                success_save_button('import','btn-import-data');
            },
            error: function (jqXHR, textStatus, errorThrown){
                alert('Error adding / update data');
                success_save_button('import','btn-import-data');
                console.log(jqXHR.responseText);
            }
        });
    }else{
        swal('','File not found, please reupload import file','warning');
    }
}
