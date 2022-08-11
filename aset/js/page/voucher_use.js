var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host = window.location.origin+'/';
var url_current = window.location.href;
var url_list 		= host + "voucher/ajax_use_voucher_list/";
var url_save 		= host + "voucher/save_use_voucher/";
var save_method; //for save method string
var table;

$(document).ready(function() {
	data_page   = $(".data-page, .page-data").data();
    page_name   = data_page.page_name;
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    search      = data_page.search;

    filter_table();
});

function filter_table(page){
	data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;

    fSearch             = $('#form-filter [name=fSearch]').val();
    fStartDate          = $('#form-filter [name=fStartDate]').val();
    fEndDate            = $('#form-filter [name=fEndDate]').val();

    data_post = {
        Search              : fSearch,
        StartDate           : fStartDate,
        EndDate             : fEndDate,
    }

    url    		= url_list+url_modul+"/"+modul;

    table = $('#table').DataTable({
        "destroy"   : true,
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "searching": false, //Feature Search false
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": url,
            "type": "POST",
            "data"  : data_post,
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [{
            "targets": [ 0], //last column
            "orderable": false, //set not orderable
        },],
    });
}

function use_voucher(){
	save_method = 'add';
	$('.vlist_voucher').empty();
	$('.div-error').removeClass('div-error');
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal('show'); // show bootstrap modal
    $('.modal-title').text('Use Voucher'); // Set Title to Bootstrap modal title
    $('[name="crud"]').val("insert");
    add_row();
}

var id_row = 0;
function add_row(){
	div_count 	= $('.div-voucher').length;
	id_row 		+= 1;
	btn_delete = '';

	row_class = 'vrow'+id_row;
	code_class= 'voucher_code'+id_row;

	if(div_count>0){
		btn_delete = '<a href="javascript:void(0)" onclick="delete_row('+"'."+row_class+"'"+')">'+language_app.lb_delete_form_voucher+'</a>';
	}

	item = '<div class="row '+row_class+' div-voucher">\
                <div class="form-group col-sm-12">\
                  <label class="control-label">'+language_app.lb_select_module+' <span class="wajib">(*)</span></label>\
                  <input type="hidden" name="rowid[]" value="'+row_class+'">\
                  <select name="voucher_module[]" class="form-control">\
                    <option value="0">'+language_app.lb_select_module+'</option>\
                    <option value="ap">'+language_app.lb_module_purchase+'</option>\
                    <option value="ar">'+language_app.lb_module_selling+'</option>\
                    <option value="inventory">'+language_app.lb_module_inventory+'</option>\
                    <option value="ac">'+language_app.lb_module_ac+'</option>\
                  </select>\
                </div>\
                <div class="form-group col-sm-12">\
                  <div class="form-group">\
                    <input type="text" class="form-control '+code_class+'" name="voucher[]" placeholder="'+language_app.lb_voucher_enter+'">\
                  </div>\
                </div>\
                <div class="form-group col-sm-12">\
                  <span class="help-block"></span>\
                  '+btn_delete+'\
                  <hr style="margin-top: 0px; margin-bottom: 0px">\
                </div>\
              </div>';
    $('.vlist_voucher').append(item);
}

function delete_row(element){
	$(element).remove();
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}

function save()
{
    proses_save_button();
    
    $('.div-error').removeClass('div-error');
    $('.form-group').removeClass('has-error'); // clear error class
  	$('.help-block').empty();
    var url = url_save;
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
                swal('',data.message,'success');
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('.'+data.inputerror[i]).addClass('div-error');
                    $('.'+data.inputerror[i]+' .help-block').text(data.error_string[i]);   
                }
            }
            success_save_button();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            success_save_button();
            console.log(jqXHR.responseText);
        }
    });
}