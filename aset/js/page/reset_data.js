var mobile 			= (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host 			= window.location.origin+'/pipesys_qa/';
var url 			= window.location.href;
var url_list 		= host + "Reset/ajax_list/";
var url_simpan 		= host + "Reset/save";
var save_method;
var table;

$(document).ready(function() {
	data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    title_page  = data_page.title;
    //datatables
    filter_table();     

});

function filter_table(page){
    data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    url         = url_list+url_modul+"/"+modul;

    fSearch             = $('#form-filter [name=fSearch]').val();
    fStartDate          = $('#form-filter [name=fStartDate]').val();
    fEndDate          	= $('#form-filter [name=fEndDate]').val();
    fType            	= $('#form-filter [name=fType]').val();
    fStatus             = $('#form-filter [name=fStatus]').val();

    data_post = {
        Search            : fSearch,
        StartDate         : fStartDate,
        EndDate           : fEndDate,
        Type              : fType,
    }

    table = $('#table').DataTable({
        "destroy"   : true,
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "searching": false, //Feature Search false
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

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}

function tambah(){
	swal({   
        title: language_app.lb_reset_alert,
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: language_app.btn_save,   
        cancelButtonText: language_app.btn_cancel,   
        closeOnConfirm: false,   
        showLoaderOnConfirm: true,
        closeOnCancel: false }, 
        function(isConfirm){   
            if (isConfirm) {
            	fType = 0;
            	if($('#Type').is(":checked")){
            		fType = 1;
            	}
            	data_post = {
            		Type : fType,
            	}
                $.ajax({
                    url : url_simpan,
                    type: "POST",
                    data: data_post,
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
                        swal("", "Error save data from ajax", "error");
                        console.log(jqXHR.responseText);

                    }
                });
            }
            else {
                swal(language_app.lb_canceled, "", "error");   
            } 
    	}
    );
}