var host            = window.location.origin+'/';
var url             = window.location.href;
var page_login      = host + "main/login";
var page_register   = host + "main/register";
var url_simpan      = host + "broadcast_message/simpan";
var url_list        = host + "broadcast_message/ajax_list/";
var url_edit        = host + "broadcast_message/ajax_edit/";
var save_method; //for save method string
var table;
var page_name;
var slimSelect_sales;

$(document).ready(function() {
    slimselect();
    page_data = $(".page-data").data();
    page_name = page_data.page_name; 


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
        "iDisplayLength": 25
    });
});

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}

$('#Sales').change(function() {
    if ($(this).prop('checked')) {
        $('.Select_sales').hide();
    }
    else {
        $('.Select_sales').show();
    }
});

function tambah()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $("#form input, #form textarea, #form select").attr("disabled",false);
    $('.help-block').empty(); // clear error string
    $('#modal').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add New Message'); // Set Title to Bootstrap modal title
    $('.Select_sales').show();
    $("#btnSave").show();
    $('.Select_sales2').empty();
    $('.Select_sales2').hide();
    slimselect();
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable
    var url;
    if(save_method == 'add') {
        url = url_simpan;
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
                    
                    if(data.inputerror[i] == "Select_sales"){
                        $('.Select_sales').addClass('has-error');
                        $('#has-sales-error').text(data.error_string[i]);
                    }else{
                        $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
                    }
                }
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable
            console.log(data);
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
function edit(id)
{
    save_method      = 'update';
    $("#form input, #form textarea, #form select").attr("disabled",true);
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty(); // clear error string
    $("#btnSave").hide();
    $('.Select_sales2').empty();
    $('.Select_sales').hide();
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            $('label').addClass('active');
            $('.validate').addClass('valid');
            $('[name="Subject"]').val(data.Subject);
            $('[name="Message"]').val(data.Message);

            if(data.All){
                $('#Sales').prop('checked', true);
                $('.Select_sales2').hide();
            }else{
                $('.Select_sales2').show();
                $('#Sales').prop('checked', false);
                $.each(data.Sales,function(i,v){
                    var append = '<span class="info-primary">'+v+'</span>';
                    $('.Select_sales2').append(append);
                });
            }
            
            $('#modal').modal("show");
            $('.modal-title').text('Detail Data');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);

        }
    });
}

function slimselect(page = "")
{

    slimSelect_sales = new SlimSelect({
        select: '#Select_sales',
        closeOnSelect: false
    });
    $('.ss-disabled').text("Select Employee")
}