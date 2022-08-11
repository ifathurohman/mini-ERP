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

function purchase_modal2(id,classnya,page)
{
    $('#modal-purchase').modal('show'); // show bootstrap modal
    $('#modal-purchase .modal-title').text('Purchase Order'); // Set Title to Bootstrap modal title
    product_status  = $('[name=product_status]:checked').val();
    if(classnya){
        tag_data = $(classnya).data();
        select 	 = tag_data.select;
        without  = tag_data.without;
        type     = tag_data.type;
    }
    crud        	= $('[name=crud]').val();
    temp_purchaseno = $('[name=temp_purchaseno]').val();
    data_post = {
        id 		: id,
        page 	: page,
        crud    : crud,
        temp_purchaseno : temp_purchaseno,
        product_status : product_status,
    }
    url = host+"api/purchase/"+page+"/"+id;
    tbl = $('.table-purchase').DataTable();
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
            $.each(data.list_data, function(i, v) {
                code        = v.PurchaseNo;
                name        = v.vendorname;

                tag_data    = ' data-classnya="'+classnya+'" ';
                tag_data    +=' data-code="'+code+'" ';
                tag_data    +=' data-vendor="'+name+'" ';
                tag_data    +=' data-deliverycost="'+parseFloat(v.DeliveryCost)+'" ';
                tag_data    +=' data-tax="'+parseFloat(v.Tax)+'" ';
                tag_data    +=' data-ppn="'+parseFloat(v.ppn)+'" ';

                item  = '<tr>';
                item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_purchase(this)">'+code+'</a></td>';
                item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_purchase(this)">'+name+'</a></td>';
                item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_purchase(this)">'+v.Date+'</a></td>';
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

function purchase_modal(id,classnya,page){
    $('#modal-purchase').modal('show'); // show bootstrap modal
    $('#modal-purchase .modal-title').text('Purchase Order'); // Set Title to Bootstrap modal title
    product_status  = $('[name=product_status]:checked').val();
    if(classnya){
        tag_data = $(classnya).data();
        select   = tag_data.select;
        without  = tag_data.without;
        type     = tag_data.type;
    }
    crud            = $('[name=crud]').val();
    temp_purchaseno = $('[name=temp_purchaseno]').val();
    data_post = {
        classnya        : classnya,
        version         : "serverSide",
        id              : id,
        page            : page,
        crud            : crud,
        temp_purchaseno : temp_purchaseno,
        product_status  : product_status,
    }
    url = host+"api/purchase/"+page+"/"+id;
    tbl = $('.table-purchase').DataTable({
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

function chose_purchase(v)
{
    data_page   = $(".data-page, .page-data").data();
    modul       = data_page.modul;
    detail 		= "";

	v = $(v).data();
    classnya        = v.classnya;
    code    	    = v.code;
    vendor          = v.vendor;
    deliverycost    = v.deliverycost;
    tax             = v.tax;
    ppn             = v.ppn;
    BranchID        = v.branch_id;
    BranchName      = v.branch_name;

    if(classnya){
        tag_data = $(classnya).data();
        detail   = tag_data.detail;
    }

    if(detail == "active"){
    	purchase_det(code);
    }

    $(classnya).val(code);
    if($('#ckPPN')){
        if(tax == 1){
            $('#ckPPN').prop('checked', true);
            $('#PPN').val(10);
        }else{
            $('#ckPPN').prop('checked', false);
            $('#PPN').val(0);
        }
        $('#ckPPN').attr('disabled', true);
    }
    if(BranchID){
        $('#BranchName').val(BranchName);
        $('#BranchName2').val(BranchName);
        $('[name=BranchID]').val(BranchID+"-"+BranchName);
    }else{
        $('#BranchName').val('');
        $('#BranchName2').val('');
        $('[name=BranchID]').val('');
    }
    $(classnya+"-deliverycost").val(deliverycost);
    $('#modal-purchase').modal('hide');
}

function purchase_det(code){
	code2 = code.replace(/\//g, '-');
	crud            	= $('[name=crud]').val();
    temp_purchasedet    = $('[name=temp_purchasedet]').val();
    product_status  = $('[name=product_status]:checked').val();
	data_post = {
		sellno 	         : code,
		modul 	         : modul,
        method           : crud,
        temp_purchasedet : temp_purchasedet,
        product_status   : product_status,
	}
	url = host+"api/purchase_detail/"+modul+"/"+code2;
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
	                PurchaseNo      = v.PurchaseNo;
	                PurchaseDet     = v.Purchase_purchasedet;
	                productid       = v.productid;
			        code            = v.product_code;
			        name            = v.product_name;
			        qty             = parseFloat(v.product_qty);
	                receipt_qty     = parseFloat(v.recevice_qty);
			        xqty            = qty - receipt_qty;
	                unitid          = v.product_unitid;
			        product_unit    = v.product_unitname;
			        product_konv    = v.product_konv;
			        product_type    = v.product_type;
                    serial_auto     = v.serial_auto;
			        product_price 	= parseFloat(v.product_price);
			        product_discount= parseFloat(v.product_discount);
                    product_delivery= parseFloat(v.DeliveryCost);
			        tax 			= parseFloat(v.tax);
                    BranchID        = v.BranchID;
                    BranchName      = v.branchName;

	                tag_data    =' data-code="'+code+'" ';

                    if(modul == "retur"){
                        // qty     = parseFloat(v.qty_stock);
                        // xqty    =  qty;
                    }else{
                        tax = 0;
                    }
                    if(modul == "retur" && crud == "update"){
                        xdata = ckselldet(Purchase_purchasedet);
                        if(xdata[0] == "true"){
                            checked          = 'checked';
                            detid            = xdata[2];
                            xqty             = xdata[1];
                            qty             += xqty;
                            product_remark   = xdata[3];
                        }
                    }

                    if($('#ckPPN')){
                        if(v.purchaseTax == 1){
                            $('#ckPPN').prop('checked', true);
                            $('#PPN').val(10);
                        }else{
                            $('#ckPPN').prop('checked', false);
                            $('#PPN').val(0);
                        }
                        $('#ckPPN').attr('disabled', true);
                    }

                    if(BranchID){
                        $('[name=BranchID]').val(BranchID+"-"+BranchName);
                        $('#BranchName').val(BranchName);
                    }else{
                        $('[name=BranchID]').val('');
                        $('#BranchName').val('');
                    }

                    btn_serial = '';
                    if(product_type == 2 && serial_auto != 1){
                        btn_serial  = '<a href="javascript:;" data-rowid="vd'+PurchaseDet+'" onclick="add_serial_number(this)" style="cursor:pointer;padding:5px;">Add Serial</a>';
                    }
	            
	                // checkbox
	                item  = '<tr class="vd'+PurchaseDet+' rowdata" data-classnya="vd'+PurchaseDet+'">';
	                item += '<td><div class="info-warning"></div></td>';
	                item += '<td>\
	                        <input class="cekbox" type="checkbox" onclick="SumTotal(element)" name="check[]" value="'+PurchaseDet+'">\
	                        <input class="disabled" type="hidden" name="detid[]" >\
	                        <input class="disabled" type="hidden" name="product_purchasedet[]" value="'+PurchaseDet+'" >\
	                        <input class="disabled" type="hidden" name="product_purchaseno[]" value="'+PurchaseNo+'" >\
	                        <input class="disabled p_id" type="hidden" name="product_id[]" value="'+productid+'" >\
	                        </td>';

                    // Branch
                    item += '<td>\
                            <input class="disabled" type="text" value="'+BranchName+'" >\
                            </td>';

	                // code
	                item += '<td>\
	                        <input class="disabled" type="text" value="'+code+'" >\
	                        </td>';

	                // name
	                item += '<td>\
	                        <input class="disabled p_name" type="text" value="'+name+'" >\
	                        </td>';

                    if(product_status == 0){
                         // qty purchase
                        item += '<td>\
                                <input class="disabled duit" data-qty="active" type="text" name="product_qty_s[]" value="'+qty+'" >\
                                </td>';

                        // qty receive
                        item += '<td>\
                                <input placeholder="input qty" data-qty="active" class="duit" type="text" onkeyup="SumTotal()" name="product_qty[]" value="'+xqty+'" min="0" max="'+qty+'" >\
                                </td>';

                        // unit
                        item += '<td>\
                                <input class="disabled" type="text" value="'+product_unit+'" >\
                                <input class="disabled" type="hidden" name="product_unitid[]" value="'+unitid+'" >\
                                <input class="disabled p_type" type="hidden" name="product_type[]" value="'+product_type+'" >\
                                <input class="disabled p_serial_auto" type="hidden" value="'+serial_auto+'">\
                                </td>';
                    }

	                // conversion
	                item += '<td class="content-hide">\
	                        <input class="disabled" type="text" name="product_konv[]" value="'+product_konv+'" >\
	                        </td>';

	                // price
	                item += '<td>\
	                        <input type="text" class="disabled duit" name="product_price[]" value="'+product_price+'">\
	                        </td>';

	                // discount
	                item += '<td>\
	                        <input type="text" class="disabled " name="product_discount[]" value="'+product_discount+'">\
	                        </td>';

	                // TAX
	                item += '<td>\
	                        <input type="text" class="disabled duit" name="product_tax[]" value="'+tax+'">\
	                        </td>';

	                // sub total
	                item += '<td>\
	                        <input type="text" class="disabled duit" name="product_subtotal[]">\
	                        </td>';

                    //  // sub total
                    // item += '<td>\
                    //        <input type="text" class="duit" name="product_delivery[]" value="'+product_delivery+'" onkeyup="SumTotal()" onchange="SumTotal()">\
                    //         </td>';

	                // remark
	                item += '<td>\
	                        <input type="text" name="product_remark[]" placeholder="input remark">\
	                        </td>';

                    item += '<td>'+btn_serial+'</td>';

	                item += '</tr>';

	                $(tbl+' tbody').append(item);
	                $(".disabled").attr("disabled",true);
	          	});
            }else{
            	item  = '<tr>';
            	item += '<td colspan="12"><div class="text-center">Data not found</div></td>';
            	item += '</tr>';

            	$(tbl+' tbody').append(item);
                $(".disabled").attr("disabled",true);
            }
            moneyFormat('SumTotal()');
            if(modul == "receive"){
                SumTotal();
            }else if(modul == "retur"){
                $('.vdcode').hide();
                SumTotal();
            }
            create_format_currency2();
			
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
           console.log(jqXHR.responseText);
        }
    });
}

function without_sellno(v){
    v = $(v).data();
    classnya    = v.classnya;
    $(classnya).val('');
    if(modul == "receive"){
        all_sell_detail();
    }
    $('#modal-purchaseno').modal('hide');
}

function all_sell_detail(){
    crud            = $('[name=crud]').val();
    temp_purchasedet    = $('[name=temp_purchasedet]').val();
    product_status  = $('[name=product_status]:checked').val();
    
    vendorid = '';
    if(modul == "receive"){
        vendorid = $('#CustomerID').val();
        vendorid = vendorid.split('-');
        vendorid = vendorid[0];
    }

    data_post = {
        sellno              : code,
        modul               : modul,
        method              : crud,
        temp_purchasedet    : temp_purchasedet,
        vendorid            : vendorid,
        product_status      : product_status,
    }
    url = host+"api/purchase_detail/"+modul;
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
                    PurchaseNo      = v.PurchaseNo;
                    PurchaseDet     = v.Purchase_purchasedet;
                    productid       = v.productid;
                    code            = v.product_code;
                    name            = v.product_name;
                    qty             = parseFloat(v.product_qty);
                    receive_qty     = parseFloat(v.receive_qty);
                    xqty            = qty - receive_qty;
                    unitid          = v.product_unitid;
                    product_unit    = v.product_unitname;
                    product_konv    = v.product_konv;
                    product_type    = v.product_type;
                    product_price   = parseFloat(v.product_price);
                    product_discount= parseFloat(v.product_discount);
                    tax             = parseFloat(v.tax);

                    tag_data    =' data-code="'+code+'" ';
                
                    // checkbox
                    item  = '<tr class="vd'+Purchase_purchasedet+'">';
                    item += '<td><div class="info-warning"></div></td>';
                    item += '<td>\
                            <input class="cekbox" type="checkbox" name="check[]" onclick="SumTotal()" value="'+Purchase_purchasedet+'">\
                            <input class="disabled" type="hidden" name="detid[]" >\
                            <input class="disabled" type="hidden" name="product_selldet[]" value="'+Purchase_purchasedet+'" >\
                            <input class="disabled" type="hidden" name="product_sellno[]" value="'+PurchaseNo+'" >\
                            <input class="disabled" type="hidden" name="product_id[]" value="'+productid+'" >\
                            </td>';

                    // code
                    item += '<td class="vdcode">\
                            <input class="disabled" type="text" value="'+PurchaseNo+'" >\
                            </td>';

                    // code
                    item += '<td>\
                            <input class="disabled" type="text" value="'+code+'" >\
                            </td>';

                    // name
                    item += '<td>\
                            <input class="disabled" type="text" value="'+name+'" >\
                            </td>';

                    // qty selling
                    item += '<td>\
                            <input class="disabled" type="text" name="product_qty_s[]" value="'+qty+'" >\
                            </td>';

                    // qty delivery
                    item += '<td>\
                            <input placeholder="input qty" type="number" name="product_qty[]" onkeyup="SumTotal()" onchange="SumTotal()" value="'+xqty+'" min="0" max="'+qty+'" >\
                            </td>';

                    // unit
                    item += '<td>\
                            <input class="disabled" type="text" value="'+product_unit+'" >\
                            <input class="disabled" type="hidden" name="product_unitid[]" value="'+unitid+'" >\
                            <input class="disabled" type="hidden" name="product_type[]" value="'+product_type+'" >\
                            </td>';

                    // conversion
                    item += '<td class="content-hide">\
                            <input class="disabled" type="text" name="product_konv[]" value="'+product_konv+'" >\
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
                            </td>';

                    // sub total
                    item += '<td>\
                            <input type="text" class="disabled duit" name="product_subtotal[]" value="'+0+'">\
                            </td>';

                    // remark
                    item += '<td>\
                            <input type="text" name="product_remark[]" placeholder="input remark">\
                            </td>';

                    item += '</tr>';

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
            if(modul == "receive"){
                SumTotal();
            }else if(modul == "retur"){
                $('.vdcode').hide();
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
           console.log(jqXHR.responseText);
        }
    });
}