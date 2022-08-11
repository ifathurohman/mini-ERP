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

function selling_modal2(id,classnya,page)
{
    position= "all";
    without = ''; 
    type    = '';
    $('#modal-sellno').modal('show'); // show bootstrap modal
    $('#modal-sellno .modal-title').text('Sell No'); // Set Title to Bootstrap modal title
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
    crud        = $('[name=crud]').val();
    temp_sellno = $('[name=temp_sellno]').val();
    product_status = $('[name=product_status]:checked').val();
    data_post = {
        id 		: id,
        page 	: page,
        crud    : crud,
        temp_sellno : temp_sellno,
        product_status : product_status,
    }
    url = host+"api/sell/"+page+"/"+id;
    tbl = $('.table-sellno').DataTable();
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
                    code        = v.sellno;
                    name        = v.vendorname;
                    date        = v.date;

                    tag_data    = ' data-classnya="'+classnya+'" ';
                    tag_data    +=' data-code="'+code+'" ';
                    tag_data    +=' data-vendor="'+name+'" ';
                    tag_data    +=' data-salesid="'+v.salesid+'" ';
                    tag_data    +=' data-salesname="'+v.salesName+'" ';
                    tag_data    +=' data-term="'+v.Term+'" ';
                    tag_data    +=' data-deliverycost="'+parseFloat(v.DeliveryCost)+'" ';
                    tag_data    +=' data-tax="'+parseFloat(v.Tax)+'" ';
                    tag_data    +=' data-ppn="'+parseFloat(v.ppn)+'" ';
                    tag_data    +=' data-deladdress="'+v.DeliveryAddress+'" ';
                    tag_data    +=' data-delcity="'+v.DeliveryCity+'" ';
                    tag_data    +=' data-delprovince="'+v.DeliveryProvince+'" ';

                    item  = '<tr>';
                    item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_selling(this)">'+code+'</a></td>';
                    item += '<td class="tb-customer"><a href="javascript:void(0)"  '+tag_data+' onclick="chose_selling(this)">'+name+'</a></td>';
                    item += '<td class="tb-date"><a href="javascript:void(0)"  '+tag_data+' onclick="chose_selling(this)">'+converttoDate(date)+'</a></td>';
                    item += '</tr>';
                    tbl.row.add( $(item)[0] ).draw();
                });
                if(type == "date"){
                    $('.tb-date').show(300);
                    $('.tb-customer').hide(300);
                }else{
                    $('.tb-date').hide(300);
                    $('.tb-customer').show(300);
                }
            }else{

            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
           console.log(jqXHR.responseText);
        }
    });
}

function selling_modal(id,classnya,page){
    position= "all";
    without = ''; 
    type    = '';
    $('#modal-sellno').modal('show'); // show bootstrap modal
    $('#modal-sellno .modal-title').text('Sell No'); // Set Title to Bootstrap modal title
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
    crud        = $('[name=crud]').val();
    temp_sellno = $('[name=temp_sellno]').val();
    product_status = $('[name=product_status]:checked').val();
    data_post = {
        classnya        : classnya,
        version         : "serverSide",
        id              : id,
        page            : page,
        crud            : crud,
        temp_sellno     : temp_sellno,
        product_status  : product_status,
    }
    url = host+"api/sell/"+page+"/"+id;
    tbl = $('.table-sellno').DataTable({
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

function chose_selling(v)
{
    data_page   = $(".data-page, .page-data").data();
    modul       = data_page.modul;
    detail 		= "";
    sales       = "";

	v = $(v).data();
    classnya        = v.classnya;
    code            = v.code;
    vendor          = v.vendor;
    deliverycost    = v.deliverycost;
    deladdress      = v.deladdress;
    delcity         = v.delcity;
    delprovince     = v.delprovince;
    tax             = v.tax;
    ppn             = v.ppn;
    term            = v.term;
    BranchID     = v.branch_id;
    BranchName   = v.branch_name;

    if(v.salesid){
        sales = v.salesid+"-"+v.salesname;
    }

    if(classnya){
        tag_data = $(classnya).data();
        detail   = tag_data.detail;
    }

    $(classnya).val(code);
    $(classnya+"-sales").val(sales);
    $(classnya+"-salesnm").val(v.salesname);
    $(classnya+"-deliverycost").val(deliverycost);
    $(classnya+"-term").val(term);
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
        $('[name=BranchID]').val(BranchID+"-"+BranchName);
        $('#BranchName').val(BranchName);
    }else{
        $('[name=BranchID]').val('');
        $('#BranchName').val('');
    }
    if(modul == "delivery"){
        $('[name=delAddress]').val(deladdress);
        $('[name=delCity]').val(delcity);
        $('[name=delProvince]').val(delprovince);
    }
    if(detail == "active"){
    	sell_detail(code);
    }
    moneyFormat();
    $('#modal-sellno').modal('hide');
}

function sell_detail(code){
    crud            = $('[name=crud]').val();
    temp_selldet    = $('[name=temp_selldet]').val();
    vendorid        = '';
    product_status  = '';
    if(modul == "delivery"){
        vendorid = $('#CustomerID').val();
        vendorid = vendorid.split('-');
        vendorid = vendorid[0];
        product_status = $('[name=product_status]:checked').val();
    }
    code = code.replace(/\/+/g, '-');
	data_post = {
		sellno 	        : code,
		modul 	        : modul,
        method          : crud,
        temp_selldet    : temp_selldet,
        vendorid        : vendorid,
        product_status  : product_status,
	}
	url = host+"api/sell_detail/"+modul+"/"+code;
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
                    sellno          = v.sellno;
                    selldet         = v.selldet;
                    productid       = v.productid;
                    code            = v.product_code;
                    name            = v.product_name;
                    qty             = parseFloat(v.product_qty);
                    delivery_qty    = parseFloat(v.delivery_qty);
                    xqty            = qty - delivery_qty;
                    unitid          = v.product_unitid;
                    product_unit    = v.product_unitname;
                    product_konv    = v.product_konv;
                    product_price   = parseFloat(v.product_price);
                    product_discount= parseFloat(v.product_discount);
                    product_type    = v.product_type;
                    product_remark  = '';
                    product_tax     = v.Tax;
                    product_delivery= parseFloat(v.DeliveryCost);
                    Cost            = parseFloat(v.Cost);
                    d_module        = v.sellModule;
                    branchName      = v.branchName;
                    if(d_module){
                        d_module        = d_module.replace(/"/g, "'");
                    }

                    tag_data    =' data-code="'+code+'" ';

                    if(modul == "return_sales"){
                        qty     = parseFloat(v.qty_stock);
                        xqty    =  qty;
                    }
                    if(modul == "return_sales" && crud == "update"){
                        xdata = ckselldet(selldet);
                        if(xdata[0] == "true"){
                            checked          = 'checked';
                            detid            = xdata[2];
                            xqty             = xdata[1];
                            qty             += xqty;
                            product_remark   = xdata[3];
                        }
                    }

                    btn_serial = '';
                    if(product_type == 2){
                        btn_serial  = '<a href="javascript:;" data-rowid="vd'+selldet+'" onclick="add_serial_number(this)" style="cursor:pointer;padding:5px;">Add Serial</a>';
                    }
                
                    // checkbox
                    item  = '<tr class="vd'+selldet+' rowdata" data-classnya="vd'+selldet+'" data-detailsn="active" data-selling="active">';
                    item += '<td><div class="info-warning"></div></td>';
                    item += '<td>\
                            <input class="cekbox" type="checkbox" name="check[]" '+checked+' onclick="SumTotal(this)" value="'+selldet+'">\
                            <input class="disabled" type="hidden" name="detid[]" value="'+detid+'">\
                            <input class="disabled detailID" type="hidden" name="product_selldet[]" value="'+selldet+'" >\
                            <input class="disabled headerID" type="hidden" name="product_sellno[]" value="'+sellno+'" >\
                            <input class="disabled p_id" type="hidden" name="product_id[]" value="'+productid+'" >\
                            <input class="disabled" type="hidden" name="product_cost[]" value="'+Cost+'" >\
                            <input class="disabled" type="hidden" name="product_module[]" value="'+d_module+'" >\
                            </td>';

                    // code
                    item += '<td class="vdcode">\
                            <input class="disabled" type="text" value="'+sellno+'" >\
                            </td>';

                    item += '<td>\
                            <input class="disabled" type="text" value="'+branchName+'" >\
                            </td>';

                    // code
                    item += '<td>\
                            <input class="disabled" type="text" value="'+code+'" >\
                            </td>';

                    // name
                    item += '<td>\
                            <input class="disabled p_name" type="text" value="'+name+'" >\
                            </td>';

                    if(product_status != 1){
                        // qty selling
                        item += '<td>\
                                <input class="disabled duit" data-qty="active" type="text" value="'+qty+'" >\
                                </td>';

                        // qty delivery
                        item += '<td>\
                                <input placeholder="input qty" data-qty="active" class="duit" type="text" name="product_qty[]" onkeyup="SumTotal()" value="'+xqty+'" min="0" max="'+qty+'" >\
                                </td>';

                        // unit
                        item += '<td>\
                                <input class="disabled" type="text" value="'+product_unit+'" >\
                                <input class="disabled" type="hidden" name="product_unitid[]" value="'+unitid+'" >\
                                <input class="disabled p_type" type="hidden" name="product_type[]" value="'+product_type+'" >\
                                <input class="disabled p_serial_auto" type="hidden" value="'+0+'">\
                                </td>';
                    }

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

                    if(modul != "return_sales"){
                    // delivery cost
                    item += '<td>\
                            <input type="text" class="duit" name="product_delivery[]" value="'+product_delivery+'" onkeyup="SumTotal()" onchange="SumTotal()">\
                            </td>';
                    }

                    // remark
                    item += '<td>\
                            <input type="text" name="product_remark[]" placeholder="input remark" value="'+product_remark+'">\
                            </td>';

                    item += '<td>'+btn_serial+'</td>';

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
            if(modul == "delivery"){
                SumTotal('element');
            }else if(modul == "return_sales"){
                $('.vdcode').hide();
                SumTotal('element');
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
    if(modul == "delivery"){
        all_sell_detail();
    }
    if($('#ckPPN')){
        $('#ckPPN').attr('disabled', false);
    }
    $('#modal-sellno').modal('hide');
}

function all_sell_detail(){
    crud            = $('[name=crud]').val();
    temp_selldet    = $('[name=temp_selldet]').val();
    
    vendorid = '';
    tax      = '';
    product_status = '';
    if(modul == "delivery"){
        vendorid = $('#CustomerID').val();
        vendorid = vendorid.split('-');
        vendorid = vendorid[0];
        product_status = $('[name=product_status]:checked').val();
        if($('#ckPPN').is(":checked")){
            tax = 1;
        }else{
            tax = 0;
        }


    }

    // sellno          : code,
    data_post = {
        modul           : modul,
        method          : crud,
        temp_selldet    : temp_selldet,
        vendorid        : vendorid,
        tax             : tax,
        product_status  : product_status,
    }
    url = host+"api/sell_detail/"+modul;
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
                    sellno          = v.sellno;
                    selldet         = v.selldet;
                    productid       = v.productid;
                    code            = v.product_code;
                    name            = v.product_name;
                    qty             = parseFloat(v.product_qty);
                    delivery_qty    = parseFloat(v.delivery_qty);
                    xqty            = qty - delivery_qty;
                    unitid          = v.product_unitid;
                    product_unit    = v.product_unitname;
                    product_konv    = v.product_konv;
                    product_type    = v.product_type;
                    product_price   = parseFloat(v.product_price);
                    product_discount = parseFloat(v.product_discount);
                    product_remark  = v.Remark;
                    product_delivery= parseFloat(v.DeliveryCost);
                    Cost            = parseFloat(v.Cost);
                    d_module        = v.sellModule;
                    branchName      = v.branchName;
                    if(d_module){
                        d_module        = d_module.replace(/"/g, "'");
                    }

                    btn_serial = '';
                    if(product_type == 2){
                        btn_serial  = '<a href="javascript:;" data-rowid="vd'+selldet+'" onclick="add_serial_number(this)" style="cursor:pointer;padding:5px;">Add Serial</a>';
                    }

                    tag_data    =' data-code="'+code+'" ';
                
                    // checkbox
                    item  = '<tr class="vd'+selldet+' rowdata" data-classnya="vd'+selldet+'" data-detailsn="active" data-selling="active">';
                    item += '<td><div class="info-warning"></div></td>';
                    item += '<td>\
                            <input class="cekbox" type="checkbox" name="check[]" onclick="SumTotal(this)" value="'+selldet+'">\
                            <input class="disabled" type="hidden" name="detid[]" >\
                            <input class="disabled detailID" type="hidden" name="product_selldet[]" value="'+selldet+'" >\
                            <input class="disabled headerID" type="hidden" name="product_sellno[]" value="'+sellno+'" >\
                            <input class="disabled p_id" type="hidden" name="product_id[]" value="'+productid+'" >\
                            <input class="disabled" type="hidden" name="product_cost[]" value="'+Cost+'" >\
                            <input class="disabled" type="hidden" name="product_module[]" value="'+d_module+'" >\
                            </td>';

                    // code
                    item += '<td class="vdcode">\
                            <input class="disabled" type="text" value="'+sellno+'" >\
                            </td>';

                    item += '<td class="vdcode">\
                            <input class="disabled" type="text" value="'+branchName+'" >\
                            </td>';

                    // code
                    item += '<td>\
                            <input class="disabled" type="text" value="'+code+'" >\
                            </td>';

                    // name
                    item += '<td>\
                            <input class="disabled p_name" type="text" value="'+name+'" >\
                            </td>';

                    if(product_status != 1){
                        // qty selling
                        item += '<td>\
                                <input class="disabled duit" data-qty="active" type="text" value="'+qty+'" >\
                                </td>';

                        // qty delivery
                        item += '<td>\
                                <input placeholder="input qty" data-qty="active" class="duit" type="text" name="product_qty[]" onkeyup="SumTotal()" value="'+xqty+'" min="0" max="'+qty+'" >\
                                </td>';

                        // unit
                        item += '<td>\
                                <input class="disabled" type="text" value="'+product_unit+'" >\
                                <input class="disabled" type="hidden" name="product_unitid[]" value="'+unitid+'" >\
                                <input class="disabled p_type" type="hidden" name="product_type[]" value="'+product_type+'" >\
                                <input class="disabled p_serial_auto" type="hidden" value="'+0+'">\
                                </td>';
                    }

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
                            </td>';

                    // sub total
                    item += '<td>\
                            <input type="text" class="disabled duit" name="product_subtotal[]" value="'+0+'">\
                            </td>';
                    if(modul != "return_sales"){
                    // delivery cost
                    item += '<td>\
                            <input type="text" class="duit" name="product_delivery[]" value="'+product_delivery+'" onkeyup="SumTotal()" onchange="SumTotal()">\
                            </td>';
                    }

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
                item += '<td colspan="14"><div class="text-center">Data Not Found</div></td>';
                item += '</tr>';
                $(tbl+' tbody').append(item);
                $(".disabled").attr("disabled",true);
            }
            moneyFormat();
            if(modul == "delivery"){
                SumTotal('element');
            }else if(modul == "return_sales"){
                $('.vdcode').hide();
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
           console.log(jqXHR.responseText);
        }
    });
}