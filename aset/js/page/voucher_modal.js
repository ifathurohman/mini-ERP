var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host = window.location.origin+'/';
var url = window.location.href;
var page_name;
var modul;
$(document).ready(function() {
    page_data   = $(".page-data").data();
    page_name   = page_data.page_name; 
    modul       = page_data.modul; 
});

function modal_voucher(classnya=""){
    $('#modal-voucher').modal('show');
    $('#modal-voucher .modal-title').text('List Voucher');
	tbl = $('.table-voucher').DataTable();
    tbl.clear();
     $.ajax({
        url : host+"get-voucher",
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data){
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            $.each(data.list_data, function(i, v) {
             	no 			= v.no;
                code        = v.code;
                package     = v.Type;

                tag_data    = ' data-classnya="'+classnya+'" ';
                tag_data    +=' data-code="'+code+'" ';
 

                item = '<tr>';
                item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_voucher(this)">'+no+'</a></td>';
                item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_voucher(this)">'+code+'</a></td>';
                item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_voucher(this)">'+package+'</a></td>';
                item += '</tr>';
	    		tbl.row.add( $(item)[0] ).draw();
          	});
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
           console.log(jqXHR.responseText);
        }
    });
}

function chose_voucher(v)
{
	v = $(v).data();
    classnya    = v.classnya;
    code 		= v.code;
    $(classnya).val(code);
    $('#modal-voucher').modal('hide');
}