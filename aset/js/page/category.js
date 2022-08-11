var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host = window.location.origin+'/pipesys_qa/';
var url = window.location.href;
var page_login = host + "main/login";
var page_register = host + "main/register";
var save_method; //for save method string
var table;
var url_list = host + "category/ajax_list/";
var url_edit = host + "category/ajax_edit/";
var url_hapus = host + "category/ajax_delete/";
var url_simpan = host + "category/simpan";
var url_update = host + "category/ajax_update";

$(document).ready(function() {
    data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    title_page  = data_page.title;
    //datatables
    filter_table();
    $("[name=level]").change(function(){
        if($(this).val() != 1){
            $(".parent_category_v").show();
            get_parent_category($(this).val());
        } else {
            $(".parent_category_v").hide();
        }
    });
});
function filter_table(page){
    data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    url         = url_list+url_modul+"/"+modul;
    date_now    = data_page.date;

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
    $("[name=category_code]").attr("disabled",false);
    $(".parent_category_v").hide();
    $(".vaction").hide();
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $("input, select").attr("disabled",false);
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add New '+ title_page); // Set Title to Bootstrap modal title
    $('[name="crud"]').val("insert");
    $('[name=categoryid]').val('');
    reset_button_action();
}
function edit(id)
{
    $(".parent_category_v").hide();
    save_method = 'update';
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty(); // clear error string
    reset_button_action();
    //Ajax Load data from ajax
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(json_respons)
        {   
        	data = json_respons.data;
            $("[name=categoryid]").val(data.categoryid);
            $("[name=category_code]").val(data.category_code);
            $("[name=category_code]").attr("disabled",true);
            $("[name=category_name]").attr("disabled",false);
            $("[name=level]").attr("disabled",false);
            $("[name=parent_category]").attr("disabled",false);
            $("[name=category_name]").val(data.category_name);
            $("[name=level]").val(data.level);
            $(".vaction").hide();
            if(data.level != 1){
                $(".parent_category_v").show();
                get_parent_category(data.level,data.parent_category);
            } else {
                $(".parent_category_v").hide();
            }
            $("[name=parent_category]").val(data.parent_category);

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
 
function view_category(id,page)
{
    reset_button_action();
    action_print_button();
    // $(".parent_category_v").hide();
    // save_method = 'update';
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty(); // clear error string
    //Ajax Load data from ajax
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(json_respons)
        {
            data = json_respons.data;
            console.log(data);
            $("[name=categoryid]").val(data.categoryid);
            $("[name=category_code]").val(data.category_code);
            $("[name=category_code]").attr("disabled",true);
            $("[name=category_name]").attr("disabled",true);
            $("[name=level]").attr("disabled",true);
            $("[name=parent_category]").attr("disabled",true);
            $("[name=category_name]").val(data.category_name);
            $("[name=level]").val(data.level);
            if(data.level != 1){
                $(".parent_category_v").show();
                get_parent_category(data.level,data.parent_category);
            } else {
                $(".parent_category_v").hide();
            }
            $("[name=parent_category]").val(data.parent_category);

            $('#modal').modal("show");
            $('.modal-title').text(title_page+' Detail');

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
 
function save()
{
    proses_save_button();
    
    var url;
    if(save_method == 'add') {
        url = url_simpan;
    } else {
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
                $('#modal').modal("hide");
                reload_table();
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
            success_save_button();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            success_save_button();
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
                            $(".modal:visible").modal('toggle');
                            reload_table();
                        },
                        error: function (jqXHR, textStatus, errorThrown){
                            swal('Error deleting data');
                        }
                    });
                    swal("Deleted!", "Your data has been deleted.", "success");   } 
                 else {
                     swal("Canceled", "Your data is safe :)", "error");   } 
    });
}
function active(id){

    swal({   title: "Are you sure?",   
             // text: "You will not be able to recover this data !",   
             type: "warning",   
             showCancelButton: true,   
             confirmButtonColor: "#DD6B55",   
             confirmButtonText: "Yes, undelete it!",   
             cancelButtonText: "No, cancel it!",   
             closeOnConfirm: false,   
             closeOnCancel: false }, 
             function(isConfirm){   
                 if (isConfirm) { 
                    $.ajax({
                    url : url_hapus+id+"/active",
                    type: "POST",
                    dataType: "JSON",
                    success: function(data)
                    {
                        $(".modal:visible").modal('toggle');
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown){
                        swal('Error undeleting data');
                    }
                });
                    swal("Undelete!", "Your data is active.", "success");   } 
                 else {
                     swal("Canceled", "Your data is safe :)", "error");   } 
    });
}
function get_parent_category(level,parent_category="")
{
    val = $('[name=categoryid]').val();
    data_post = {
        categoryid : val,
        level : level,
    }
    $.ajax({
        url : host+"api/category",
        data:data_post,
        type: "POST",
        data : data_post,
        dataType: "JSON",
        success: function(data){
            $("[name=parent_category] option").remove();
            $.each(data, function(i, v) {
                item = "<option value='"+v.category_code+"'>"+v.category_name+"</option>";
                $("[name=parent_category]").append(item);
            });
            if(parent_category){
                $("[name=parent_category]").val(parent_category);
            }
        },
        error: function (jqXHR, textStatus, errorThrown){
            console.log(jqXHR.responseText);
        }
    });
}
function modal_import()
{
    $(".dropify-clear").click(); 
    $('#modal-import').modal('show');
    $('.modal-title').text('Import Data');
}
function import_data()
{
    $('.btn-import').text('Import..'); //change button text
    $('.btn-import').attr('disabled',true); //set button enable

    url = host+"category/import";
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
           // $('#modal').modal("hide");
           $(".modal:visible").modal('toggle');
           swal('',data.message,'success');
           reload_table();
        } else {
            swal('',data.message,'warning');  
        }
        $('.btn-import').text('Import'); //change button text
        $('.btn-import').attr('disabled',false); //set button enable
    },
    error: function (jqXHR, textStatus, errorThrown){
        alert("import data error");
        $('.btn-import').text('Import'); //change button text
        $('.btn-import').attr('disabled',false); //set button enable
        console.log(jqXHR.responseText);
    }
  });
}
function clear_img(){
  var drEvent = $('.dropify').dropify();
  drEvent = drEvent.data('dropify');
  drEvent.resetPreview();
  drEvent.clearElement();
}