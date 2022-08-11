var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host = window.location.origin+'/pipesys_qa/';
var url = window.location.href;
var page_login = host + "main/login";
var page_register = host + "main/register";
var save_method; //for save method string
var table;
var url_list = host + "product_service/ajax_list/";
var url_edit = host + "product_service/ajax_edit/";
var url_hapus = host + "product_service/ajax_delete/";
var url_simpan = host + "product_service/simpan";
var url_update = host + "product_service/ajax_update";
$(document).ready(function() {
    data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    //datatables
    filter_table();
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
    // $("[name=product_code]").attr("disabled",false);
    $(".parent_product_v").hide();
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Data'); // Set Title to Bootstrap modal title
    $('[name="crud"]').val("insert");
    $(".serial_format_v").hide();
    $("[name=product_type]").attr("disabled",false);
}
function edit(id)
{
    // $("[name=product_code]").attr("disabled",true);
    save_method = 'update';
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty(); // clear error string
    //Ajax Load data from ajax
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            console.log(data);
            $("[name=productid]").val(data.productid);
            $("[name=product_code]").val(data.product_code);
            $("[name=product_name]").val(data.product_name);
            $("[name=selling_price]").val(parseFloat(data.selling_price).toFixed( 2 ));
            $(".serial_format_v").hide();
            $("[name=product_category]").val(data.parent_code);
            $("[name=product_type]").attr("disabled",true);
            $("[name=product_code]").attr('disabled', true);
            if(data.typecode == 1){
                $("[name=product_code]").removeAttr('disabled', false);
            }
            if(data.type == 1){
                $("#unique").prop("checked",true);
                 $(".serial_format_v").show();
            } else if(data.type == 2){
                $("#serial").prop("checked",true);
            } else {
                $("#general").prop("checked",true);
            }
            $("[name=serial_format]").val(data.serial_format);

            $('#modal').modal("show");
            $('.modal-title').text('Edit Data');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
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
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable
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
            console.log(data);
            if(data.status) //if success close modal and reload ajax table
            {
                swal('',data.message,'success');
                $('#modal').modal("hide");
                reload_table();
            }
            else{
              $('.form-group').removeClass('has-error'); // clear error class
              $('.help-block').empty();
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                    //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                    // swal('',data.error_string[i],'warning');
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
function modal_import()
{
    $('#modal-import').modal('show');
    $('.modal-title').text('Import Data');
}
function import_data()
{
    $('.btn-import').text('Import..'); //change button text
    $('.btn-import').attr('disabled',true); //set button enable

    url = host+"product_service/import";
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

function view(id)
{
    no = 0;
    tbl = $('.table-view-product_service').DataTable();
    tbl.clear();
    // $('.table-view-product tbody').children('tr').remove();
    $.ajax({
        url : host+"product_service/view/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            if(data.status){
                product_name = data.product_name;
                $.each(data.list_data, function(i, v) {
                    no += 1;
                    name = v.name;
                    address = v.address;
                    city = v.city;
                    item = '<tr>\
                        <td>'+no+'</td>\
                        <td>'+name+'</td>\
                        <td>'+address+'</td>\
                        <td>'+city+'</td>\
                    </tr>';

                    // $(".table-view-product tbody").append(item);
                    tbl.row.add( $(item)[0] ).draw();

                });



                $('#modal-view-product_service').modal('show'); // show bootstrap modal
                $('.modal-title').text(product_name); // Set Title to Bootstrap modal title
                $(".product_name").text(product_name);

            } else {
                alert(data.message);
            }
        },
        error: function (jqXHR, textStatus, errorThrown){
            alert('failed get data');
        }
    });
}
function view_serial(id)
{
    $('#modal-view-serial').modal('show');
    $('.modal-title').text('Prduct Serial Number');
    tblserial = $('.table-view-serial').DataTable({
        "searching": false,
        "destroy": true,
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": host+"api/serial_number_datatables",
            "type": "POST",
            "data": {
            productid: id,
          }
        },
        //Set column definition initialisation properties.
        "columnDefs": [{
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },],
    });
}