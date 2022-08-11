var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host = window.location.origin+'/pipesys_qa/';
var url = window.location.href;
var page_login = host + "main/login";
var page_register = host + "main/register";
var save_method; //for save method string
var table;
var url_list  = host + "menu/ajax_list/";
var url_edit  = host + "menu/ajax_edit/";
var url_hapus  = host + "menu/ajax_delete/";
var url_simpan = host + "menu/simpan";
var url_update = host + "menu/ajax_update";
var url_index  = host + "menu/set_index";

var slim_modul,slim_modul2;
$(document).ready(function() {
 
    //datatables
    table = $('#table').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
 
        // Load data for the table's content from an Ajax source
        ajax: {
            url: url_list,
            type: "POST",
            dataSrc : function (json) {
                angkaFormat();
                return json.data;
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [
        {
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],
 
    });
    slim_modul = new SlimSelect({
      select: '#modul'
    })
    slim_modul2 = new SlimSelect({
      select: '#modul2'
    })
});
function tambah()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Menu & Page'); // Set Title to Bootstrap modal title
    $("#pipesys, #salespro").prop("checked",true);
    $('.vmodul2, .vtype').hide();
    slim_modul.set([]);
    slim_modul2.set([]);
}
function edit(id)
{
    save_method = 'update';
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty(); // clear error string
    //Ajax Load data from ajax
    slim_modul.set([]);
    slim_modul2.set([]);
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('label').addClass('active');
            $('.validate').addClass('valid');
            $('[name="id_menu"]').val(data.id_menu);
            $('[name="nama_menu"]').val(data.nama_menu);
            $('[name="kategori"]').val(data.kategori).selected = true;
            $('[name="url"]').val(data.url);
            $('[name="hak_akses"]').val(data.hak_akses);
            $('[name="root"]').val(data.root);
            $('[name="icon"]').val(data.icon);
            if(data.modul){
                slim_modul.set(data.modul);
                check_modul2(data.modul,data.modul2);
            }
            check_kategory(data.kategori,data.type);
            $('#modal').modal();
            $('.modal-title').text('Menu & Page Edit');
            if(data.app){
                $.each(data.app,function(i,v){
                    $("#"+v).prop("checked",true);
                });
            }
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
    proses_save_button();
    var url;
 
    if(save_method == 'add') {
        url = url_simpan;
    } else {
        url = url_update;
    }
    // ajax adding data to database
    from = $('#form').serializeArray();
    $.ajax({
        url : url,
        type: "POST",
        data: from,
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
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); 
                    //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                    // swal('',data.error_string[i],'warning');
                }
            }
            success_save_button()
 
 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            success_save_button()
 
        }
    });
}
function hapus(id)
{
  $.ajax({
      url : url_hapus+id,
      type: "POST",
      dataType: "JSON",
      success: function(data)
      {
          reload_table();
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
          alert('Error deleting data');
      }
  });
}

$("#modul").change(function(){
    val = $(this).val();
    check_modul2(val);
});

function check_modul2(val,modul2){
    $('#modul2').empty();
    // item = '<option value="0">Select Modul Page</option>';
    item = '';
    if(val){
        if(jQuery.inArray("ap", val) !== -1){
            item += '<option class="vo_ap" value="po">Purchase</option>';
            item += '<option class="vo_ap" value="receipt">Receipt</option>';
            item += '<option class="vo_ap" value="return_ap">Return Purchase</option>';
            item += '<option class="vo_ap" value="invoice_ap">Invoice Payable</option>';
            item += '<option class="vo_ap" value="correction_ap">Correction Payable</option>';
            item += '<option class="vo_ap" value="payment_ap">Payment Payable</option>';
        }else{
            $('.vo_ap').remove();
        }
        
        if(jQuery.inArray("ar", val) !== -1){
            item += '<option class="vo_ar" value="so">Selling</option>';
            item += '<option class="vo_ar" value="delivery">Delivery</option>';
            item += '<option class="vo_ar" value="return_ar">Return Selling</option>';
            item += '<option class="vo_ar" value="invoice_ar">Invoice Receivable</option>';
            item += '<option class="vo_ar" value="correction_ar">Correction Receivable</option>';
            item += '<option class="vo_ar" value="payment_ar">Payment Receivable</option>';
            
        }else{
            $('.vo_ar').remove();
        }

        if(jQuery.inArray("ac", val) !== -1){
            item += '<option class="vo_ac" value="cash_bank">Cash / Bank</option>';
            item += '<option class="vo_ac" value="jurnal">Jurnal</option>';
        }else{
            $('.vo_ac').remove();
        }

        if(jQuery.inArray("inventory", val) !== -1){
            ["inventory","mutation","stock"]
            item += '<option class="vo_inventory" value="mutation">Mutation</option>';
            item += '<option class="vo_inventory" value="stock">Stock</option>';
            item += '<option class="vo_inventory" value="inventory_goodreceipt">Stock Receipt</option>';
            item += '<option class="vo_inventory" value="good_issue">Stock Issue</option>';
        }
        else{
            $('.vo_inventory').remove();
        }
    }

    if(val){
        $('#modul2').append(item);
        $('.vmodul2').show();
        slim_modul2.destroy();
        slim_modul2 = new SlimSelect({
          select: '#modul2'
        })
        if(modul2){
            slim_modul2.set(modul2);
        }
    }else{
        $('.vmodul2').hide();
    }
}

$('#kategori').change(function(){
    val = $(this).val();
    check_kategory(val);
});

function check_kategory(val,data){
    $('#type').empty();
    if(val == "report"){
        item = '<option value="0">Select Type</option>';
        item += '<option value="1">Finance</option>';
        item += '<option value="2">Stock</option>';
        $('#type').append(item);
        $('.vtype').show(300);
        if(data){
            $('#type').val(data);
        }
    }else{
        $('.vtype').hide(300);
    }
}

function set_index(a,id){
    
    data_post = {
        ID      : id,
        index   : a.value,
    }

    $.ajax({
      url : url_index,
      type: "POST",
      data : data_post,
      dataType: "JSON",
      success: function(data)
      {
        console.log(data);
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
          alert('Error change index data');
      }
  });
}