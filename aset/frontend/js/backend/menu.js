var mobile      = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host        = window.location.origin+'/';
var url         = window.location.href;
var url_list    = host + "menu/list_data/";
var url_edit    = host + "menu/edit/";
var url_hapus   = host + "menu/delete/";
var url_simpan  = host + "menu/save";
var url_update  = host + "menu/update";
var url_menu_parent  = host + "api/menu_parent";
var table;
var save_method; //for save method string
$(document).ready(function() {
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

    $('[name=Type]').change(function(){
        if($(this).val() == "1"){
            $(".v_parent").hide(300);
        } else {
            $(".v_parent").show(300);
        }
    });
    $("[name=Modul]").change(function(){
        Modul = $(this).val();
        if(Modul != 0){
            menu_parent(Modul);
        }
    });

    $("[name=Category]").change(function(){
        if($(this).val() == 'page_backend'){
            $(".v_icon").show();
        } else {
            $(".v_icon").hide();
        }
    });

});
function tambah()
{
    save_method = 'add';
    $(".v_parent").hide();
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Data'); // Set Title to Bootstrap modal title
}
function edit(id)
{
    save_method = 'update';
    $(".v_parent").hide();
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty(); // clear error string
    //Ajax Load data from ajax
    console.log(url_edit);
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            console.log(data);
            $('label').addClass('active');
            $('.validate').addClass('valid');
            $('[name="MenuID"]').val(data.MenuID);
            $('[name="Name"]').val(data.Name);
            $('[name="Category"]').val(data.Category).selected = true;
            $('[name="Url"]').val(data.Url);
            $('[name="HakAkses"]').val(data.HakAkses);
            $('[name="Root"]').val(data.Root);
            $('[name="Type"]').val(data.Type);
            $('[name="Modul"]').val(data.Modul);
            $('[name="Icon"]').val(data.Icon);
            if(data.Category == 'page_backend'){
                $(".v_icon").show();
            } else {
                $(".v_icon").hide();
            }
            menu_parent(data.Modul,"edit",data.ParentID);
            $('[name="ParentID"]').val(data.ParentID);
            if(data.Type == 1){
                $(".v_parent").hide();
            } else {
                $(".v_parent").show();
            }
            $('#modal').modal();
            $('.modal-title').text('Edit Data');

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
    table.ajax.reload(null,false); //reload datatable ajax
}
function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable
    var url;
 
    if(save_method == 'add') {
        url = url_simpan;
    } else {
        url = url_update;
    }
    // ajax adding data to database
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
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); 
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
 
        }
    });
}
function hapus(id)
{
  swal({   title: "Are you sure ?",   
             text: "You will not be able to recover this data !",   
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
                            swal("Hapus", "Deleting data success", "success");   
                            reload_table();
                        },
                        error: function (jqXHR, textStatus, errorThrown){
                            swal('Error deleting data');
                        }
                    });
                }
    });
}
function menu_parent(Modul,Method,ParentID)
{
    $("[name=ParentID] option").remove();
    item = '<option value="0">Choose Parent Menu</option>';
    $("[name=ParentID]").append(item);
    data_post = {
        Modul : Modul
    };

    $.ajax({
        url : url_menu_parent,
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data)        {
            if(data.HakAkses == "developer"){
                console.log(data);
            }

            $.each(data.ListData,function(i,v){
                item = '<option value="'+v.MenuID+'">'+v.Name+'</option>';
                $("[name=ParentID]").append(item);
            });
            if(Method == "edit"){
                $("[name=ParentID]").val(ParentID);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          console.log(jqXHR.responseText);
        }
    });

}