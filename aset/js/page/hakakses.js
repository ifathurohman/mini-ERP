var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host = window.location.origin+'/';
var url = window.location.href;
var page_login = host + "main/login";
var page_register = host + "main/register";
var url_list = host + "hak_akses/ajax_list/";
var url_edit = host + "hak_akses/ajax_edit/";
var url_hapus = host + "hak_akses/ajax_delete/";
var url_simpan = host + "hak_akses/simpan";
var url_update = host + "hak_akses/ajax_update";
var save_method; //for save method string
var table;
$(document).ready(function() {
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
    });
    //set input/textarea/select event when change value, remove class error and remove text help block 
});
 
 
 
function tambah()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    // $('.form-group').removeClass('has-error'); // clear error class
    // $('.help-block').empty(); // clear error string
    $('#modal').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add User Privileges'); // Set Title to Bootstrap modal title
}
function edit(id,hak_akses)
{
    save_method = 'update';
    $('.form-group').removeClass('has-error');
    $('.help-block').empty(); // clear error string
    $('#form')[0].reset(); // reset form on modals
    //Ajax Load data from ajax
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('#form')[0].reset();
            $('#modal').modal("show");
            // var menu = data.menu;
            menu    = JSON.parse(data.menu);
            tambah_1  = JSON.parse(data.tambah);
            ubah    = JSON.parse(data.ubah);
            hapus   = JSON.parse(data.hapus);
            for(var i in menu){
              var id = menu[i];
              $("#idmenu" + id).prop('checked', true);
            }
            for(var i in tambah_1){
              var id = tambah_1[i];
              $("#tambah" + id).prop('checked', true);
            }
            for(var i in ubah){
              var id = ubah[i];
              $("#ubah" + id).prop('checked', true);
            }
            for(var i in hapus)
            {
              var id = hapus[i];
              $("#hapus" + id).prop('checked', true);
            }
            $('[name=nama_hak_akses]').val(data.nama_hak_akses);
            $('label').addClass('active');
            $('.validate').addClass('valid');
            $('[name="id_hak_akses"]').val(data.id_hak_akses);
            // $("#").attr('checked', true);
            $('.modal-title').text('EDIT ' + hak_akses);
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
    proses_save_button();
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
        success: function(data){
            // alert(data.pesan);
            if(data.status){
                $('#modal').modal("hide");
                reload_table();
            }
            else{
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); 
                    //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                    // swal('',data.error_string[i],'warning');
                }
            }
            success_save_button();
        },
        error: function (jqXHR, textStatus, errorThrown){
            alert('Error adding / update data');
            success_save_button();
        }
    });
}
function hapus(id)
{
  $.ajax({
      url : url_hapus+id,
      type: "POST",
      dataType: "JSON",
      success: function(data){
          reload_table();
      },
      error: function (jqXHR, textStatus, errorThrown){
          alert('Error deleting data');
      }
  });
}