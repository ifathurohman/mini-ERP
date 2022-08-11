var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host 			= window.location.origin+'/';
var url 			= window.location.href;
var page_login 		= host + "main/login";
var page_register 	= host + "main/register";
var url_list 		= host + "conversion_balance/ajax_list/";
var url_edit 		= host + "conversion_balance/ajax_edit/";
var url_hapus 		= host + "conversion_balance/ajax_delete/";
var url_active 		= host + "conversion_balance/ajax_active/";
var url_simpan 		= host + "conversion_balance/save";
var url_data        = host + "api/conversion_balance";
var save_method; //for save method string
var table;
var d_list = [];
var d_type = "";

$(document).ready(function() {
    $('.btn-cancel').hide(300);
    get_data();
});

function get_data(){
    data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;

    data_post   = {
        url_modul   : url_modul,
        modul       : modul,
        level       : 4,
        select      : "active",
        kasbank     : "active",
    }

    $.ajax({
        url  : url_data,
        data : data_post,
        type : "POST",
        dataType: "JSON",
        success: function(data)
        {
            if(data.hak_akses == "super_admin"){
                console.log(data);
            }
            if(data.list.length>0){
                d_type = "data";
                d_list = data.list;
                add_table();
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);
        }
    });
}

function add_table(page){
    if ( $.fn.DataTable.isDataTable('#table') ) {
      $('#table').DataTable().destroy();
    }


    var data_list = [];
    totalDebit  = 0;
    totalCredit = 0;
    $.each(d_list,function(k,v){
        no  = k + 1;
        no += '<input type="hidden" name="COAID[]" value="'+v.ID+'" >';
        search = true;

        totalCredit += parseFloat(v.credit);
        totalDebit += parseFloat(v.debit);

        debit  = '<span class="d-text">'+number_format(parseFloat(v.debit))+'</span>';
        credit = '<span class="d-text">'+number_format(parseFloat(v.credit))+'</span>';

        if(page == "add"){
            debit   = '<input  type="text" class="duit d-input" onkeyup="SumTotal()" name="debit[]" value="'+parseFloat(v.debit)+'">';
            credit  = '<input  type="text" class="duit d-input" onkeyup="SumTotal()" name="credit[]" value="'+parseFloat(v.credit)+'">';
            search  = false;
        }

        a = [
            no,
            v.Code,
            v.Name,
            v.Level,
            v.parentName,
            debit,
            credit,
        ];
        data_list.push(a);
    });

    $('#table').DataTable( {
        "ordering": false,
        "paging":false,
        "searching" : search,
        data: data_list,
    } );
    $('.totalDebit').text(number_format(totalDebit));
    $('.totalCredit').text(number_format(totalCredit));
    $('[name=totalDebit]').val(totalDebit);
    $('[name=totalCredit]').val(totalCredit);
}

function tambah(){
    $('.btn-add').text('Save Conversion Balance');
    $('.btn-add').attr('onclick','save()');
    $('.btn-cancel, .vdate, .vimport').removeClass('content-hide').show(300);
    $('.btn-filter').hide(300);
    add_table("add");
    moneyFormat();
}

function cancel(){
    $('.btn-add').text('Edit Conversion Balance');
    $('.btn-add').attr('onclick','tambah()');
    $('.btn-cancel, .vdate , .vimport').hide(300);
    $('.btn-filter').show(300);
    if(d_type == "import"){
        get_data();
    }else{
        add_table();
    }
}

function save(method)
{   
    swal({   
        title: "Are you sure want to save?",   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: "Save",   
        cancelButtonText: "Cancel",   
        closeOnConfirm: false,   
        closeOnCancel: false }, 
        function(isConfirm){   
            if (isConfirm) { 
                $('.save').button('loading');
                $.ajax({
                    url : url_simpan,
                    type: "POST",
                    data: $('#form').serialize(),
                    dataType: "JSON",
                    success: function(data)
                    {
                        if(data.hak_akses == "super_admin"){
                            console.log(data);
                        }
                        if(data.status) //if success close modal and reload ajax table
                        {
                            swal('',data.message,'success');
                        }
                        else
                        {
                            if(data.message){
                                swal('',data.message,'warning');
                            }
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
                        $('.save').button('reset');
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert('Error adding / update data');
                        $('.save').button('reset');
                        console.log(jqXHR.responseText);
                    }
                });
            } 
            else {
                swal("Canceled", "", "error");   
            } 
    });
}

function SumTotal(){
    d = $("input[name='COAID[]']").length;

    totalCredit = 0;
    totalDebit  = 0;
    for (i = 0; i < d; i++) {
        debit   = $('[name="debit[]"]').eq(i).val();
        debit   = removeduit(debit);

        credit   = $('[name="credit[]"]').eq(i).val();
        credit   = removeduit(credit);

        totalDebit += debit;
        totalCredit += credit;
    }

    $('.totalDebit').text(number_format(totalDebit));
    $('.totalCredit').text(number_format(totalCredit));
    $('[name=totalDebit]').val(totalDebit);
    $('[name=totalCredit]').val(totalCredit);
    moneyFormat(); 
}

function import_data(){
    clear_img();
    $('#modal-import').modal('show');
    $('#modal-import .modal-title').text('Import Data');
}

function upload_import(){
    $('.btn-import').text('Import..'); //change button text
    $('.btn-import').attr('disabled',true); //set button enable

    url = host+"conversion_balance/import";
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
            
        }
        if(data.status){ 
            if(data.list.length>0){
                $('#modal-import').modal('hide');
                d_type = "import";
                d_list = data.list;
                add_table("add");
                moneyFormat();
            }
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