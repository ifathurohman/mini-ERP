var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host = window.location.origin+'/';
var url = window.location.href;
// var id_row = 0;
var page;
$(document).ready(function() {

});
function modal_receive(page = "")
{
    $('#modal-receive').modal('show'); // show bootstrap modal
    $('#modal-receive .modal-title').text('Receive'); // Set Title to Bootstrap modal title
    tbl = $('.table-receive').DataTable();
    tbl.clear();
     $.ajax({
        url : host+"api/receive",
        type: "GET",
        dataType: "JSON",
        success: function(data){
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            $.each(data.list_data, function(i, v) {
                receiveno   = v.receiveno;
                receivename = v.receivename;
                date        = v.receipt_date;
                vendorid    = v.vendorid;
                vendorname  = v.vendorname;

	    		item = '<tr>\
                            <td>\
                            <a href="javascript:void(0)" \
                                data-page="'+page+'" \
                                data-receiveno="'+receiveno+'" \
                                data-receivename="'+receivename+'" \
                                data-vendorid="'+vendorid+'" \
                                data-vendorname="'+vendorname+'" \
                                onclick="chose_receive(this)">'+receiveno+'</a>\
                            </td>\
                            <td><a href="javascript:void(0)" \
                                data-page="'+page+'" \
                                data-receiveno="'+receiveno+'" \
                                data-receivename="'+receivename+'" \
                                data-vendorid="'+vendorid+'" \
                                data-vendorname="'+vendorname+'" \
                                onclick="chose_receive(this)">'+date+'</a>\</td>\
	    					<td><a href="javascript:void(0)" \
                                data-page="'+page+'" \
                                data-receiveno="'+receiveno+'" \
                                data-receivename="'+receivename+'" \
                                data-vendorid="'+vendorid+'" \
                                data-vendorname="'+vendorname+'" \
                                onclick="chose_receive(this)">'+receivename+'</a>\</td>\
	    				</tr>';
	    		tbl.row.add( $(item)[0] ).draw();
          	});
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
           console.log("error get list receive");
        }
    });
}
function chose_receive(receive)
{
	receive     = $(receive).data();
    page        = receive.page;
    receiveno   = receive.receiveno;
    receivename = receive.receivename;
    vendorid    = receive.vendorid;
    vendorname  = receive.vendorname;
    $("[name=receiveno]").val(receiveno);
    $("[name=vendorid]").val(vendorid);
    $("[name=vendorname]").val(vendorname);
    
    if(page == "return"){
        get_receive_product(receive);
    }

    $('#modal-receive').modal('hide');

}