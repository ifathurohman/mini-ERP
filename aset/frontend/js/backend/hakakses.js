var mobile      = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host        = window.location.origin+'/';
var url         = window.location.href;
var url_list    = host + "hakakses/ajax_list/";
var url_edit    = host + "hakakses/edit/";
var url_hapus   = host + "hakakses/delete/";
var url_simpan  = host + "hakakses/save";
var url_update  = host + "hakakses/update";
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
function cek_all(element){
    // $("#input").prop('checked', true);
    console.log(element);
    data    = $(element).data();
    value   = $(element).val();
    modul   = data.modul;
    id = "#all"+value+"-"+modul;
    classnya = ".cek-"+value;
    if($(element).is(':checked')){
        $("."+modul +" "+classnya).prop('checked', true);
        if(value == "view"){
            $("."+modul +" .v_display").show();
        }
    } else {
        $("."+modul +" "+classnya).prop('checked', false);
        if(value == "view"){
            $("."+modul +" .v_display").hide();
        }
    }
}
function click_view(element){
    data    = $(element).data();
    value   = $(element).val();
    modul   = data.modul;
    menuid  = data.menuid;
    
    classnya = ".display-"+menuid
     if($(element).is(':checked')){
        $(classnya).show();
    } else {
        $(classnya).hide();
    }
    console.log(classnya);
}
 
function tambah()
{
    $(".v_display").hide();
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal({backdrop: "static"}); // show bootstrap modal
    $('.modal-title').text('Add Data'); // Set Title to Bootstrap modal title
}
function edit(id,hakakses)
{
    $(".v_display").hide();
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
            console.log(data);
            $('#form')[0].reset();
            $('#modal').modal({backdrop: "static"});
            menu    = data.Menu;
            xtambah  = data.Tambah;
            ubah    = data.Ubah;
            hapus   = data.Hapus;
            approve   = data.Approve;
            for(var i in menu){
              var id = menu[i];
              $("#idmenu" + id).prop('checked', true);
              $(".display-"+id).show();


            }
            for(var i in xtambah){
              var id = xtambah[i];
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
            for(var i in approve)
            {
              var id = approve[i];
              $("#approve" + id).prop('checked', true);
            }

            $.each(data.Filter,function(i,v){
                $("#filter"+v.ID).val(v.Filter);
            });

            $('[name=Name]').val(data.Name);
            $('label').addClass('active');
            $('.validate').addClass('valid');
            $('[name="HakAksesID"]').val(data.HakAksesID);
            $("#").attr('checked', true);
            $('.modal-title').text('EDIT ' + hakakses);
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
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable
        },
        error: function (jqXHR, textStatus, errorThrown){
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable
            console.log(jqXHR.responseText);

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