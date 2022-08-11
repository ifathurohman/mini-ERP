var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host = window.location.origin+'/';
var url = window.location.href;
var page_login = host + "main/login";
var page_register = host + "main/register";
var save_method; //for save method string
var table;
var url_list 	= host + "Kas_Bank/ajax_list/";
var url_edit 	= host + "Kas_Bank/ajax_edit/";
var url_hapus 	= host + "Kas_Bank/ajax_delete/";
var url_simpan 	= host + "Kas_Bank/save";
var url_update 	= host + "Kas_Bank/ajax_update";

var id_row = 0;
$(document).ready(function() {
    date();
    //datatables
    filter_table();
});

function filter_table(page){
    data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    title_page  = data_page.title;
    url         = url_list+url_modul+"/"+modul;
    date_now    = data_page.date;

    fSearch             = $('#form-filter [name=fSearch]').val();
    fStartDate          = $('#form-filter [name=fStartDate]').val();
    fEndDate            = $('#form-filter [name=fEndDate]').val();

    data_post = {
        Search              : fSearch,
        StartDate           : fStartDate,
        EndDate             : fEndDate,
    }
    table = $('#table').DataTable({
        "destroy"   : true,
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "searching": true, //Feature Search false
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
            "targets": [0], //last column
            "orderable": false, //set not orderable
        },],
    });
}

function tambah()
{
    save_method = 'add';
    $("#form input").attr("disabled",false);
    $("#form .disabled").attr("disabled",true);
    $("#form .readonly").attr("readonly",true);
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal('show'); // show bootstrap modal
    $('.modal-title').text(language_app.lb_add_new+' '+title_page); // Set Title to Bootstrap modal title
    $(".save").show();
    $('[name="crud"]').val("insert");
    $('.table-detail tbody').empty();
    id_row = 0;
    add_new_row();
    reset_button_action();
    reset_file_upload();
    show_upload_file();
    hide_button_cancel2();
    create_form_attach();
    $('.btn-close').show();
}

function add_new_row(v){
    kolom       = $('.table-detail tbody').find('tr').length + 1;
    id_row      += 1;
    btn_remove  = '';

    if(kolom>100){
        swal('','Data item max 100', 'warning');
        return '';
    }

    if(kolom > 1){
       btn_remove = '<i  onclick="delete_row(this)" class="icon fa-remove remove_row" aria-hidden="true" style="cursor:pointer;padding:5px;"></i>'; 
    }

    detid      = '';
    coaid      = '';
    coacode    = '';
    coaname    = '';
    detremark  = '';
    detcredit  = '';
    detdebit   = '';
    if(v){
        detid      = v.KasBankDetNo;
        coaid      = v.COAID;
        coacode    = v.coaCode;
        coaname    = v.coaName;
        detremark  = v.Remark;
        detcredit  = parseFloat(v.Credit);
        detdebit   = parseFloat(v.Debit);
    }

    item = 
        '<tr class="dt'+id_row+'" data-level="4" data-select="active">\
            <td>\
                <input type="hidden" name="rowid[]" value="dt'+id_row+'">\
                <div class="info-warning"></div>\
            </td>\
            <td><i class="icon fa-search remove_row" onclick="coa_modal('+"'.dt"+id_row+"'"+')" style="cursor:pointer;padding:5px;"></i></td>\
            <td>\
                <input type="text" class="readonly pointer" onclick="coa_modal('+"'.dt"+id_row+"'"+')" name="coacode[]" value="'+coacode+'" placeholder="'+language_app.lb_coa_select+'">\
                <input type="hidden" name="coaid[]" value="'+coaid+'"/>\
                <input type="hidden" name="detid[]" value="'+detid+'"/>\
            </td>\
            <td><input type="text" class="disabled" name="coaname[]" value="'+coaname+'"></td>\
            <td><input type="text" name="detremark[]" placeholder="input remark" value="'+detremark+'"></td>\
            <td><input type="text" class="duit" onkeyup="SumTotal()" name="detdebit[]" placeholder="'+language_app.lb_input_nominal+'" value="'+detdebit+'"></td>\
            <td><input type="text" class="duit" onkeyup="SumTotal()" name="detcredit[]" placeholder="'+language_app.lb_input_nominal+'" value="'+detcredit+'"></td>\
            <td>'+btn_remove+'</td>\
        </tr>';
    $(".table-detail tbody").append(item);
    $('.readonly').attr('readonly', true);
    $('.disabled').attr('disabled', true);
    moneyFormat();
}
function  delete_row(a) {
    $(a).closest('tr').remove();
    SumTotal();
    create_format_currency2();
}

function SumTotal(){
	d = $("input[name='coaid[]']").length;

	totaldebit  = 0;
	totalcredit = 0;

	for (i = 0; i < d; i++) {
		code    = $('[name="coacode[]"]').eq(i).val();
		id     	= $('[name="coaid[]"]').eq(i).val();

		detdebitx  = $('[name="detdebit[]"]').eq(i).val();
		detdebitx  = removeduit(detdebitx);

		detcreditx = $('[name="detcredit[]"]').eq(i).val();
		detcreditx  = removeduit(detcreditx);

		if(detdebitx>0 && detcreditx<=0){
			$('[name="detcredit[]"]').eq(i).attr('disabled', true);
		}else if(detdebitx<=0 && detcreditx<=0){
			$('[name="detcredit[]"]').eq(i).attr('disabled', false);
		}

		if(detdebitx<=0 && detcreditx>0){
			$('[name="detdebit[]"]').eq(i).attr('disabled', true);
		}else if(detdebitx<=0 && detcreditx<=0){
			$('[name="detdebit[]"]').eq(i).attr('disabled', false);
		}

		if(id){
			totalcredit += detcreditx;
			totaldebit 	+= detdebitx;
		}
	}

	$('[name=TotalDebit]').val(totaldebit);
	$('[name=TotalCredit]').val(totalcredit);
    run_function = 'SumTotal()';
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}

function save()
{
    proses_save_button();
    $("#form input").attr("disabled",false);
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty();
    $('.info-warning').empty();
    var url;
    url = url_simpan;

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
                swal('','Success', 'success');
                reload_table();
            }
            else
            {
                console.log(data);
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    list    = data.list[i];
                    tab     = data.tab[i];
                    if(list == 'list'){
                        item = '<i class="icon fa-exclamation-triangle" title="'+data.error_string[i]+'" style="cursor:pointer;padding:5px;"></i>';
                        $(data.inputerror[i]+' .info-warning').append(item);
                    }else{
                        $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                        $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                    }
                }

                if(data.message){
                    swal('',data.message,'warning');
                }else{
                    swal('',language_app.lb_incomplete_form, 'warning');
                }
            }
            success_save_button();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            console.log(jqXHR.responseText);
            success_save_button();
        }
    });
}

// view
function view(id,page){
    reset_button_action();
    if(page == "print"){
        page = "print";
    }else{
        page = "kas_bank";
    }
    $('#modal-print').modal('show');
    $('#modal-print .modal-title').text(title_page+" "+language_app.btn_detail);
    url = host + "cash-bank-view/"+id+"?page="+page;

    data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;

    data_post  = {
        modul            : modul,
        url_modul        : url_modul,
    }

    $("#view-print").load(url,data_post,function(){
        $(".div-loader").hide();
        reset_file_upload();
        create_form_attach2();
        hide_upload_file();
        hide_button_cancel();
        disabled_file();
        show_attachment_file(id);
    });

    url = host + "cash-bank-view/"+id+"?page=print";
    action_print_button(url);
}
// end view

function delete_data(id){
    swal({   
        title: language_app.lb_delete_alert,   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: language_app.btn_save,   
        cancelButtonText: language_app.btn_cancel,   
        closeOnConfirm: false,   
        closeOnCancel: false }, 
        function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    url : url_hapus+id,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data)
                    {
                        if(data.status){
                            swal('',data.message, 'success');
                            reload_table();
                        }else{
                            swal('',data.message, 'warning');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert('Error save data from ajax');
                        console.log(jqXHR.responseText);
                    }
                });
            } 
            else {
                swal(language_app.lb_canceled, "", "error");   
            } 
    });
}

// edit
function edit(id){
    id_row = 0;
    save_method = "update";
    $(".readonly").attr("readonly",true);
    $("#form input, #form textarea, #form select").attr("disabled",false);
    $('#form')[0].reset(); // reset form on modals
    $('.form-group, .input-group').removeClass('has-error'); // clear error class
    $('.input-error').removeClass('input-error');
    $('.help-block').empty(); // clear error string
    $('#form [name=crud]').val('update');
    // $('#modal').modal({backdrop: 'static', keyboard: true}); // show bootstrap modal
    $("#modal").modal("show");
    $('#modal .modal-title').text(title_page+' '+language_app.lb_edit); // Set Title to Bootstrap modal title
    $('.table-detail tbody').children('tr').remove();
    reset_button_action();
    reset_file_upload();
    show_upload_file();
    hide_button_cancel2();
    create_form_attach();
    $('.btn-close').show();
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            list        = data.list;
            detail      = data.detail;

            $('[name=Code]').val(list.KasBankNo);
            $('[name=Date]').val(list.Date);
            $('[name=Remark]').val(list.Remark);
            $('[name=TotalDebit]').val(parseFloat(list.DebitTotal));
            $('[name=TotalCredit]').val(parseFloat(list.CreditTotal));

            $("input[name=Type][value='"+list.Type+"']").prop('checked', true);
            $("input[name=Type]").attr('disabled', true);

            $.each(detail, function(i,v){
                add_new_row(v);
            });

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);
        }
    });
}