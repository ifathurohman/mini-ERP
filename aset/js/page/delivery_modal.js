var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host = window.location.origin+'/pipesys_qa/';
var url = window.location.href;
var page_name;
var modul;
$(document).ready(function() {
    page_data   = $(".page-data").data();
    page_name   = page_data.page_name; 
    modul       = page_data.modul; 
});

function delivery_modal2(id,classnya,page)
{
    position= "all";
    without = ''; 
    type    = '';
    $('#modal-delivery').modal('show'); // show bootstrap modal
    $('#modal-delivery .modal-title').text('Delivery'); // Set Title to Bootstrap modal title
    if(classnya){
        tag_data = $(classnya).data();
        select 	 = tag_data.select;
        without  = tag_data.without;
        type     = tag_data.type;
    }

    if(without == "active"){
        $('.btn-without').show();
        $('.btn-without').data('classnya', classnya);
    }else{
        $('.btn-without').hide();
    }
    crud        	= $('[name=crud]').val();
    temp_deliveryno = $('[name=temp_deliveryno]').val();
    data_post = {
        id 		: id,
        page 	: page,
        crud    : crud,
        temp_deliveryno : temp_deliveryno,
    }
    url = host+"api/delivery/"+page+"/"+id;
    tbl = $('.table-delivery').DataTable();
    tbl.clear().draw();
     $.ajax({
        url : url,
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data){
            if(data.hakakses == "super_admin"){
            	console.log(data);
            }
            app     = data.app;
            if(data.list_data.length>0){
                $.each(data.list_data, function(i, v) {
                    code        = v.DeliveryNo;
                    date        = v.Date;

                    tag_data    = ' data-classnya="'+classnya+'" ';
                    tag_data    +=' data-code="'+code+'" ';
                    
                    item  = '<tr>';
                    item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_delivery(this)">'+code+'</a></td>';
                    item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_delivery(this)">'+date+'</a></td>';
                    item += '</tr>';

                    tbl.row.add( $(item)[0] ).draw();
                });
            }else{

            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
           console.log(jqXHR.responseText);
        }
    });
}

function delivery_modal(id,classnya,page){
    position= "all";
    without = ''; 
    type    = '';
    $('#modal-delivery').modal('show'); // show bootstrap modal
    $('#modal-delivery .modal-title').text('Delivery'); // Set Title to Bootstrap modal title
    if(classnya){
        tag_data = $(classnya).data();
        select   = tag_data.select;
        without  = tag_data.without;
        type     = tag_data.type;
    }

    if(without == "active"){
        $('.btn-without').show();
        $('.btn-without').data('classnya', classnya);
    }else{
        $('.btn-without').hide();
    }
    crud            = $('[name=crud]').val();
    temp_deliveryno = $('[name=temp_deliveryno]').val();
    data_post = {
        classnya        : classnya,
        version         : "serverSide",
        id              : id,
        page            : page,
        crud            : crud,
        temp_deliveryno : temp_deliveryno,
    }

    url = host+"api/delivery/"+page+"/"+id;
    tbl = $('.table-delivery').DataTable({
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
            },
            dataSrc : function (json) {
                return json.data;
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [{
            "targets": [], //last column
            "orderable": false, //set not orderable
        },],
    });
}

function chose_delivery(v)
{
    data_page   = $(".data-page, .page-data").data();
    modul       = data_page.modul;
    detail 		= "";

	v = $(v).data();
    classnya        = v.classnya;
    code            = v.code;

    if(classnya){
        tag_data = $(classnya).data();
        detail   = tag_data.detail;
    }
    $(classnya).val(code);
    if(detail == "active"){
    	delivery_detail(code);
    }
    $('#modal-delivery').modal('hide');
}

function delivery_detail(code){
    crud            	= $('[name=crud]').val();
    temp_deliverydet    = $('[name=temp_deliverydet]').val();
    vendorid = '';
    if(modul == "return_sales"){
        vendorid = $('#CustomerID').val();
        vendorid = vendorid.split('-');
        vendorid = vendorid[0];
    }
    
	data_post = {
		deliveryno 	    : code,
		modul 	        : modul,
        method          : crud,
        temp_deliverydet    : temp_deliverydet,
        vendorid        : vendorid,
	}
    code = code.replace(/\/+/g, '-');
	url = host+"api/delivery_detail/"+modul+"/"+code;
    tbl = '.table-add-product';
    $(tbl + " tbody tr").remove();
	$.ajax({
        url : url,
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data){
            if(data.hakakses == "super_admin"){
            	console.log(data);
            }
            app     = data.app;
            if(data.list_data.length>0){
                $.each(data.list_data, function(i, v) {

                    checked         = '';
                    detid           = '';
                    deliveryno  	= v.DeliveryNo;
                    deliverydet 	= v.DeliveryDet;
                    product_id 		= v.ProductID;
                    product_name 	= v.product_name;
                    product_code 	= v.product_code;
                    product_qty 	= v.Qty;
                    product_unitid 	= v.UnitID;
                    product_unit 	= v.unit_name;
                    product_type 	= v.Type;
                    product_konv 	= v.Conversion;
                    product_price 	= parseFloat(v.Price);
                    product_discount = parseFloat(v.Discount);
                    product_tax 	 = v.Tax;
                    product_remark   = '';
                    d_module         = v.deliveryModule;
                    branchName       = v.branchName;
                    if(d_module){
                        d_module        = d_module.replace(/"/g, "'");
                    }

                    
                    if(modul == "return_sales"){
                        product_qty = parseFloat(v.qty_stock);
                    }

                    xqty            = product_qty;
                    if(modul == "return_sales" && crud == "update"){
                        xdata = ckdeliverydet(v.DeliveryDet);
                        if(xdata[0] == "true"){
                            checked          = 'checked';
                            detid            = xdata[2];
                            xqty             = xdata[1];
                            product_qty     += xqty;
                            product_remark   = xdata[3];
                        }
                    }

                    btn_serial = '';
                    if(product_type == 2){
                        btn_serial  = '<a href="javascript:;" data-rowid="vd'+deliverydet+'" onclick="add_serial_number(this)" style="cursor:pointer;padding:5px;">Add Serial</a>';
                    }

                    // checkbox
                    item  = '<tr class="vd'+deliverydet+' rowdata" data-classnya="vd'+deliverydet+'" data-detailsn="active">';
                    item += '<td><div class="info-warning"></div></td>';
                    item += '<td>\
                            <input class="cekbox" type="checkbox" name="check[]" '+checked+' onclick="SumTotal(this)" value="'+deliverydet+'">\
                            <input class="disabled" type="hidden" name="detid[]" value="'+detid+'">\
                            <input class="disabled detailID" type="hidden" name="product_deldet[]" value="'+deliverydet+'" >\
                            <input class="disabled headerID" type="hidden" name="product_delno[]" value="'+deliveryno+'" >\
                            <input class="disabled p_id" type="hidden" name="product_id[]" value="'+product_id+'" >\
                            <input class="disabled" type="hidden" name="product_module[]" value="'+d_module+'" >\
                            </td>';

                    // delivery no
                    item += '<td class="vdcode">\
                            <input class="disabled" type="text" value="'+deliveryno+'" >\
                            </td>';

                    // Branch
                    item += '<td>\
                            <input class="disabled" type="text" value="'+branchName+'" >\
                            </td>';

                   	// code
                    item += '<td>\
                            <input class="disabled" type="text" value="'+product_code+'" >\
                            </td>';

                    // name
                    item += '<td>\
                            <input class="disabled p_name" type="text" value="'+product_name+'" >\
                            </td>';

                    // qty delivery
                    item += '<td>\
                            <input class="disabled" type="text" value="'+product_qty+'" >\
                            </td>';

                    // qty input
                    item += '<td>\
                            <input placeholder="input qty" type="text" class="duit" name="product_qty[]" onkeyup="SumTotal()" value="'+xqty+'" min="0" max="'+product_qty+'" >\
                            </td>';

                    // unit
                    item += '<td>\
                            <input class="disabled" type="text" value="'+product_unit+'" >\
                            <input class="disabled" type="hidden" name="product_unitid[]" value="'+product_unitid+'" >\
                            <input class="disabled p_type" type="hidden" name="product_type[]" value="'+product_type+'" >\
                            <input class="disabled p_serial_auto" type="hidden" value="'+0+'">\
                            </td>';

                    // conversion
                    item += '<td class="content-hide">\
                            <input class="disabled" name="product_konv[]" type="text" value="'+product_konv+'" >\
                            </td>';

                    // price
                    item += '<td>\
                            <input class="disabled duit" type="text" name="product_price[]" value="'+product_price+'" >\
                            </td>';

                   	// discount
                    item += '<td>\
                            <input type="text" class="disabled" name="product_discount[]" value="'+product_discount+'">\
                            <input type="text" class="disabled content-hide duit" name="product_discountrp[]" value="'+0+'">\
                            </td>';

                    // Tax
                    item += '<td>\
                            <input type="text" class="disabled duit" name="product_tax[]" value="'+0+'">\
                            <input type="hidden" class="disabled" name="product_tax2[]" value="'+product_tax+'">\
                            </td>';

                    // sub total
                    item += '<td>\
                            <input type="text" class="disabled duit" name="product_subtotal[]" value="'+0+'">\
                            </td>';

                    // remark
                    item += '<td>\
                            <input type="text" name="product_remark[]" value="'+product_remark+'" placeholder="input remark">\
                            </td>';

                    item += '<td>'+btn_serial+'</td>';

                    $(tbl+' tbody').append(item);
                    $(".disabled").attr("disabled",true);
                });
            }else{
                item  = '<tr>';
                item += '<td colspan="14"><div class="text-center">Data Not Found</div></td>';
                item += '</tr>';
                $(tbl+' tbody').append(item);
                $(".disabled").attr("disabled",true);
            }
            moneyFormat();
            create_format_currency2();
            if(modul == "return_sales"){
                $('.vdcode').hide(300);
            	SumTotal();
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
           console.log(jqXHR.responseText);
        }
    });
}