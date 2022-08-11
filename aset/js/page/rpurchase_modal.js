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

function purchase_modal(id,classnya,page)
{
    $('#modal-purchase').modal('show'); // show bootstrap modal
    $('#modal-purchase .modal-title').text('Purchase Order'); // Set Title to Bootstrap modal title
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
                tag_data    +=' data-tax="'+parseFloat(v.Tax)+'" ';
                tag_data    +=' data-ppn="'+parseFloat(v.ppn)+'" ';

                item  = '<tr>';
                item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_purchase(this)">'+code+'</a></td>';
                item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_purchase(this)">'+name+'</a></td>';
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

function chose_purchase(v)
{
    data_page   = $(".data-page, .page-data").data();
    modul       = data_page.modul;
    detail 		= "";

	v = $(v).data();
    classnya    = v.classnya;
    code    	= v.code;
    vendor      = v.vendor;
    tax         = v.tax;
    ppn         = v.ppn;

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
    
    $('#modal-purchase').modal('hide');
}

function purchase_det(code){
	code2 = code.replace(/\//g, '-');
	crud            	= $('[name=crud]').val();
    temp_purchasedet    = $('[name=temp_purchasedet]').val();
	data_post = {
		sellno 	         : code,
		modul 	         : modul,
        method           : crud,
        temp_purchasedet : temp_purchasedet,
	}
	url = host+"api/purchase_detail/"+modul+"/"+code2;
	console.log(url);
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
                    Tcode           = v.transactionCode;
                    qty             = parseFloat(v.product_qty);
	                recevice_qty    = parseFloat(v.recevice_qty);
			        xqty            = qty - recevice_qty;
	                unitid          = v.product_unitid;
			        product_unit    = v.product_unitname;
			        product_konv    = v.product_konv;
			        product_type    = v.product_type;
			        product_price 	= parseFloat(v.product_price);
			        product_discount= parseFloat(v.product_discount);
			        tax 			= parseFloat(v.tax);

	                tag_data    =' data-code="'+code+'" ';

                    if(modul == "retur"){
                        // qty     = parseFloat(v.qty_stock);
                        // xqty    =  qty;
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
	            
	                // checkbox
	                item  = '<tr class="vd'+PurchaseDet+'">';
	                item += '<td><div class="info-warning"></div></td>';
	                item += '<td>\
	                        <input class="cekbox" type="checkbox" onclick="SumTotal()" name="check[]" value="'+PurchaseDet+'">\
	                        <input class="disabled" type="hidden" name="detid[]" >\
	                        <input class="disabled" type="hidden" name="product_purchasedet[]" value="'+PurchaseDet+'" >\
	                        <input class="disabled" type="hidden" name="product_purchaseno[]" value="'+PurchaseNo+'" >\
	                        <input class="disabled" type="hidden" name="product_id[]" value="'+productid+'" >\
	                        </td>';

	                // code
	                item += '<td>\
	                        <input class="disabled" type="text" name="product_code[]" value="'+code+'" >\
	                        </td>';

	                // name
	                item += '<td>\
	                        <input class="disabled" type="text" name="product_name[]" value="'+name+'" >\
	                        </td>';

                      // Transaction Code
                    item += '<td>\
                            <input class="disabled" type="text" name="tcode[]" value="'+Tcode+'" >\
                            </td>';

	                // qty purchase
	                item += '<td>\
	                        <input class="disabled" type="hidden" name="product_qty_s[]" value="'+qty+'" >\
	                        </td>';

	                // qty receive
	                item += '<td>\
	                        <input placeholder="input qty" type="number" onkeyup="SumTotal()" onchange="SumTotal()" name="product_qty[]" value="'+xqty+'" min="0" max="'+qty+'" >\
	                        </td>';

	                // unit
	                item += '<td>\
	                        <input class="disabled" type="text" name="product_unit[]" value="'+product_unit+'" >\
	                        <input class="disabled" type="hidden" name="product_unitid[]" value="'+unitid+'" >\
	                        <input class="disabled" type="hidden" name="product_type[]" value="'+product_type+'" >\
	                        </td>';

	                // conversion
	                item += '<td>\
	                        <input class="disabled" type="text" name="product_konv[]" value="'+product_konv+'" >\
	                        </td>';

	                // price
	                item += '<td>\
	                        <input type="text" class="disabled" name="product_price[]" value="'+product_price+'">\
	                        </td>';

	                // discount
	                item += '<td>\
	                        <input type="text" class="disabled" name="product_discount[]" value="'+product_discount+'">\
	                        </td>';

	                // TAX
	                item += '<td>\
	                        <input type="text" class="disabled" name="product_tax[]" value="'+tax+'">\
	                        </td>';

	                // sub total
	                item += '<td>\
	                        <input type="text" class="disabled" name="product_subtotal[]">\
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
            	item += '<td><div class="text-center">data not found</div></td>';
            	item += '</tr>';

            	$(tbl+' tbody').append(item);
                $(".disabled").attr("disabled",true);
            }
            moneyFormat();
            if(modul == "receive"){
                SumTotal();
            }else if(modul == "retur"){
                $('.vdcode').hide();
                SumTotal();
            }
			
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
                            <input class="disabled" type="text" name="product_code[]" value="'+code+'" >\
                            </td>';

                    // name
                    item += '<td>\
                            <input class="disabled" type="text" name="product_name[]" value="'+name+'" >\
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
                            <input class="disabled" type="text" name="product_unit[]" value="'+product_unit+'" >\
                            <input class="disabled" type="hidden" name="product_unitid[]" value="'+unitid+'" >\
                            <input class="disabled" type="hidden" name="product_type[]" value="'+product_type+'" >\
                            </td>';

                    // conversion
                    item += '<td>\
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