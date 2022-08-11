var mobile          = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host            = window.location.origin+'/';
var url             = window.location.href;
var page_login      = host + "main/login";
var page_register   = host + "main/register";
var save_method; //for save method string
var table;
var url_list    = host + "bank/ajax_list/";
var url_edit    = host + "bank/ajax_edit/";
var url_hapus   = host + "bank/ajax_delete/";
var url_simpan  = host + "bank/simpan/";
var url_update  = host + "bank/ajax_update/";
var addressno   = 0;
var modul       = "";
var app         = "";
var radius_val  = 0;
var page_name;
var url_modul;
$(document).ready(function() {
    data_page   = $(".data-page, .page-data").data();
    modul       = data_page.modul;
    app         = data_page.app;
    page_name   = data_page.page_name;
    url_modul   = data_page.url_modul;
    //datatables
    table = $('#table').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": url_list+modul+"/"+url_modul,
            "type": "POST"
        },
        //Set column definition initialisation properties.
        "columnDefs": [
        {
            "targets": [ -1,0 ], //last column
            "orderable": false, //set not orderable
        },
        ],
    });
    $("#add_address").click(function(){
        add_address();
    });
});
function tambah(){
    modal_width();
    addressno = 0;
    $(".address_v div").remove();
    add_address();
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add New ' + page_name); // Set Title to Bootstrap modal title
    $('[name="crud"]').val("insert");
}
function edit(id)
{
    modal_width();
    addressno = 0;
    $(".address_v div").remove();
    save_method = 'update';
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty(); // clear error string
    //Ajax Load data from ajax
    $.ajax({
        url : url_edit + id+"/"+modul,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            console.log(data);
            if(app == "pipesys"){
                list_address_row = data.list_address.length;
                if(list_address_row > 0){
                    $.each(data.list_address, function(i, v) {
                        data_address = {
                            address_code:v.address_code,
                            address:v.address,
                            city:v.city,
                            province:v.province,
                        }
                        add_address("edit",data_address);
                    });
                } else {
                    add_address();
                }

            $('#modal').modal("show");
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
        url = url_simpan+modul;
    } else {
        url = url_update+modul;
    }
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
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
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable
            console.log(jqXHR.responseText);
            
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
                            //if success reload ajax table
                            reload_table();
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            swal('Error deleting data');
                        }
                    });
                    swal("Deleted!", "Your data has been deleted.", "success");   } 
                 else {
                     swal("Canceled", "Your data is safe :)", "error");   } 
    });
}
function active(id){
    $.ajax({
        url : url_hapus+id+"/active",
        type: "POST",
        dataType: "JSON",
        success: function(data){
            reload_table();
        },
        error: function (jqXHR, textStatus, errorThrown){
            swal('Error undeleting data');
        }
    });
}
function add_address(page ="",data_address="")
{
    addressno    += 1;
    address_code = "";
    city         = "";
    province     = "";
    address      = "";
    if(page == "edit"){
        address_code = data_address.address_code;
        city         = data_address.city;
        province     = data_address.province;
        address      = data_address.address;
    }
    item = '<div class="form-group col-sm-12">\
                <input type="hidden" name="address_code[]" value="'+address_code+'">\
                <label class="control-label">Address</label>\
                <input name="address[]" type="text" class="form-control" value="'+address+'">\
                <span class="help-block"></span>\
              </div>\
              <div class="form-group col-sm-6">\
                <label class="control-label">City</label>\
                <input name="city[]" type="text" class="form-control" value="'+city+'">\
                <span class="help-block"></span>\
              </div>\
              <div class="form-group col-sm-6">\
                <label class="control-label">Province</label>\
                <input name="province[]" type="text" class="form-control" value="'+province+'">\
                <span class="help-block"></span>\
              </div>';
    // item = "<div class='row'>"+item+"</div>";
    // item = item + '<div class="form-group col-sm-12"><hr></div>';
    $(".address_v").append(item);
}