var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host 			= window.location.origin+'/';
var url 			= window.location.href;
var page_login 		= host + "main/login";
var page_register 	= host + "main/register";
var url_list 		= host + "master_coa/ajax_list/";
var url_edit 		= host + "master_coa/ajax_edit/";
var url_hapus 		= host + "master_coa/ajax_delete/";
var url_active 		= host + "master_coa/ajax_active/";
var url_simpan 		= host + "master_coa/save";
var save_method; //for save method string
var table;

$(document).ready(function() {
    data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    title_page  = data_page.title;
    //datatables
    filter_table();
});

function filter_table(page){
	data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;

    url    		= url_list+url_modul+"/"+modul;
    fSearch             = $('#form-filter [name=fSearch]').val();
    fActive             = $('#form-filter [name=fActive]').val();
    // fTypeStatus         = $('#form-filter [name=fTypeStatus]').val();

    data_post = {
        Search              : fSearch,
        Active              : fActive,
        // Type                : fTypeStatus,
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
    $('#form')[0].reset(); // reset form on modals
    $("#form input, #form textarea, #form select, #button").attr("disabled",false);
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add New '+ title_page); // Set Title to Bootstrap modal title
    $('[name="crud"]').val("insert");
    change_level(1);
    reset_button_action();
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
            if(json_respons.hakakses == "super_admin"){
                console.log(json_respons);
            }
            data = json_respons.data;
            if(data){
            	$('#form [name=COAID]').val(data.COAID);
            	$('#form [name=Code]').val(data.Code);
            	$('#form [name=Name]').val(data.Name);
            	$('#form [name=Level]').val(data.Position);
            	$('#form [name=Remark]').val(data.Remark);
                $('#form [name=PaymentType]').val(data.PaymentType);
                change_level(data.Position,data.ParentID);
                if(json_respons.used>0){
                    $('#form [name=Level]').attr('disabled', true);
                }else{
                    $('#form [name=Level]').attr('disabled', false);
                }
            }

            $('#modal').modal("show");
            $('#modal .modal-title').text(title_page+' Edit');
            success_save_button()
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            success_save_button();
            alert('Error get data from ajax');
        }
    });
}

function view_coa(id,page)
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
            if(json_respons.hakakses == "super_admin"){
                console.log(json_respons);
            }
            data = json_respons.data;
            if(data){
                $('#form [name=COAID]').val(data.COAID);
                $('#form [name=Code]').val(data.Code);
                $('#form [name=Name]').val(data.Name);
                $('#form [name=Level]').val(data.Position);
                $('#form [name=Remark]').val(data.Remark);
                $('#form [name=PaymentType]').val(data.PaymentType);
                change_level(data.Position,data.ParentID);
            }

            $('#modal').modal("show");
            $('#modal .modal-title').text(title_page+' Edit');

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
 
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}
 
function save(method)
{
    $('#btnSave').button('loading');
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
                coa_select();
                swal('',data.message, 'success');
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
            $('#btnSave').button('reset');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').button('reset');
            console.log(jqXHR.responseText);
            save_log_error(jqXHR.responseText);
        }
    });
}
function active(id,method){
    swal({   
    	title: "Are you sure?",   
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
			        url : url_active+id,
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

$('#form [name=Level]').change(function(){
	val = $(this).val();
	change_level(val);
});

function change_level(val,data){
	if(val == 1){
		$('.vparent').hide(300);
		$('.coa_select_level').empty();
	}else{
		$('.vparent').show(300);
		parent_level(val,data);
	}
}

function parent_level(level,data){
	coa_tb          = $(".coa_select"); 
    coa             = $(coa_tb).data();
    coa_tipe_op     = $(".coa_select option");
    $(".coa_select_level").empty();
    item = '<option value="none">Pilih COA</option>';
    $(".coa_select_level").append(item);
    $.each(coa_tipe_op,function(i,v){
        dt = $(v).data();
        item = '<option value="'+dt.id+'">'+dt.code+" - "+dt.name+'</option>';
        if(level == 2 && dt.level == 1){
            $(".coa_select_level").append(item);
        }else if(level == 3 && dt.level == 2){
        	$(".coa_select_level").append(item);
        }else if(level == 4 && dt.level == 3){
        	$(".coa_select_level").append(item);
        }
    });
    if(data){
        $('#ParentID').val(data);
    }
}

function modal_import(){
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

    url = host+"master_coa/import";
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
                item += '<td>'+v.Level+'</td>';
                item += '<td>'+v.Parent+'</td>';
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
            url : host+"master_coa/save_import",
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
