var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host = window.location.origin+'/pipesys_qa/';
var url = window.location.href;
// var id_row = 0;
$(document).ready(function() {

});

function ckproduct_modal(id,page){
    if(page == "ar"){
        val = $('#CustomerID').val();
        if(val){
            data_tag = $('#CustomerID').data();
            productcustomer = '';
            if(data_tag.productcustomer){
                productcustomer = data_tag.productcustomer;
            }
            product_modal(id,productcustomer,page);
        }else{
            swal('','please select customer','warning');
        }
    }else if(page == "mutation"){
        mutation_type = $('[name=mutation_type] option:selected').val();
        Branch = $('#BranchName1').val();
        if(mutation_type != 0){
            if(Branch){
                product_modal(id);
            }else{
                swal('','please select from store','warning');
            }
        }else{
            product_modal(id);
        }
    }
}

function product_modal2(id_row,groupname,page)
{
    $('#modal-product').modal('show'); // show bootstrap modal
    $('#modal-product .modal-title').text('Product'); // Set Title to Bootstrap modal title
    
    status = $('[name=product_status]:checked').val();

    data_post = {
        status    : status,
    }

    if(groupname){
        data_post = {
            groupname : groupname,
            page      : page,
            status    : status,
        }
    }
    
    tbl = $('.table-product').DataTable();
    tbl.clear();
     $.ajax({
        url : host+"api/product",
        type: "POST",
        data:data_post,
        dataType: "JSON",
        success: function(data){
            $.each(data.list_product, function(i, v) {
                productid      = v.productid;
            	product_code   = v.product_code;
                product_name   = v.product_name;
            	product_type   = v.product_type;
                product_unitid = v.unitid;
            	product_unit   = v.unit_name;
            	product_conv   = v.conversion;
                qty            = v.qty;
                average_price  = v.average_price,
            	product_sellingprice = v.sellingprice;
                purchaseprice  = parseFloat(v.purchaseprice);

                tag_data = 'data-row="'+id_row+'" \
                            data-productid="'+productid+'" \
                            data-code="'+product_code+'" \
                            data-name="'+product_name+'" \
                            data-type="'+product_type+'" \
                            data-unitid="'+product_unitid+'" \
                            data-unit="'+product_unit+'" \
                            data-conversion="'+product_conv+'" \
                            data-qty="'+qty+'" \
                            data-sellingprice="'+product_sellingprice+'" \
                            data-average_price="'+average_price+'" \
                            data-status="'+status+'" \
                            data-purchaseprice="'+purchaseprice+'"';

	    		item = '<tr>\
	    					<td>\
	    						<a href="javascript:void(0)" \
                                '+tag_data+'\
	    						onclick="chose_product(this)">'+product_code+'</a>\
	    					</td>\
	    					<td>\
                                <a href="javascript:void(0)" \
                                '+tag_data+'\
                                onclick="chose_product(this)">'+product_name+'</a>\
                            </td>\
	    				</tr>';
	    		tbl.row.add( $(item)[0] ).draw();
          	});
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
           console.log("error get list product");
        }
    });
}

function product_modal(id_row,groupname,page){
    $('#modal-product').modal('show'); // show bootstrap modal
    $('#modal-product .modal-title').text('Product'); // Set Title to Bootstrap modal title

    status      = $('[name=product_status]:checked').val();
    branchid    = $('#BranchID').val(); //pastikan agar ID/# BranchID tidak duplicate di form
    selling     = '';
    tag_data    = $(".rowid_"+id_row).data();

    if(tag_data.selling){
        selling = tag_data.selling;
    }

    data_post = {
        classnya    : id_row,
        version     : "serverSide",
        status      : status,
        selling     : selling,
        BranchID    : branchid,
    }

    if(groupname){
        data_post = {
            classnya    : id_row,
            version     : "serverSide",
            groupname   : groupname,
            page        : page,
            status      : status,
            selling     : selling,
            BranchID    : branchid,
        }
    }
    url = host+"api/product";
    tbl = $('.table-product').DataTable({
        "destroy"   : true,
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "searching": true, //Feature Search false
        "order": [], //Initial no order.
         "language": {                
            "infoFiltered": "",
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

function chose_product(product)
{   
    page_data   = $(".page-data").data();
    page_name   = page_data.page_name; 
    modul       = page_data.modul;

	product      = $(product).data();
	row 	     = product.row;
    productid    = product.productid;
	code 	     = product.code;
    name         = product.name;
	type 	     = product.type;
    unitid       = product.unitid;
	unit 	     = product.unit;
	conversion   = product.conversion;
    qty          = product.qty;
	sellingprice = product.sellingprice;
    purchaseprice = product.purchaseprice;
    average_price = product.average_price;
    status        = product.status;
    xserial_auto  = product.serial_auto;

    if(modul == "stock_correction" || modul == "stock_opname" || modul == "mutation"){
        if(!check_product_not_duplicate(productid,row)){
            return '';
        }
    }

    data_post = {ProductID : productid}
    $.ajax({
        url : host+"api/product_unit",
        type: "POST",
        data:data_post,
        dataType: "JSON",
        success: function(data){
            if(data.status && data.list.length>0){
                data_unit = data.list;
                data_unit = data_unit[0];

                unitid      = data_unit.ID;
                unit        = data_unit.Uom;
                conversion  = data_unit.Conversion;
                if(parseFloat(data_unit.purchase)>0){
                    purchaseprice = data_unit.purchase;
                    $(".rowid_"+row+" .p_purchaseprice").val(parseFloat(purchaseprice));
                }
                if(parseFloat(data_unit.selling)>0){
                    sellingprice = data_unit.selling;
                    $(".rowid_"+row+" .p_sellprice").val(parseFloat(sellingprice));
                }

                $(".rowid_"+row+" .p_unitid").val(unitid);
                $(".rowid_"+row+" .p_unit").val(unit);
                $(".rowid_"+row+" .p_conv").val(conversion);
                unit_tag = 'data-id="'+unitid+'" ';
                unit_tag += 'data-rowid="'+row+'" ';
                unit_tag += 'data-name="'+unit+'" ';
                unit_tag += 'data-conversion="'+conversion+'" ';
                unit_tag += 'data-sellingprice="'+sellingprice+'" ';
                unit_tag += 'data-purchaseprice="'+purchaseprice+'" ';
                item = '<option '+unit_tag+' value="'+unitid+'">'+unit+'</option>';
                $(".rowid_"+row+" .p_unit2").empty();
                $(".rowid_"+row+" .p_unit2").append(item);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
           console.log(jqXHR.responseText);
        }
    });

    unit_tag = 'data-id="'+unitid+'" ';
    unit_tag = 'data-rowid="'+row+'" ';
    unit_tag += 'data-name="'+unit+'" ';
    unit_tag += 'data-conversion="'+conversion+'" ';
    unit_tag += 'data-sellingprice="'+sellingprice+'" ';
    unit_tag += 'data-purchaseprice="'+purchaseprice+'" ';
    item = '<option '+unit_tag+' value="'+unitid+'">'+unit+'</option>';
    $(".rowid_"+row+" .p_unit2").empty();
    $(".rowid_"+row+" .p_unit2").append(item);

    $(".rowid_"+row+" .p_id").val(productid);
    $(".rowid_"+row+" .p_code").val(code);
    $(".rowid_"+row+" .p_name").val(name);
    $(".rowid_"+row+" .p_type").val(type);
    $(".rowid_"+row+" .p_serial_auto").val(xserial_auto);
    $(".rowid_"+row+" .p_unitid").val(unitid);
    $(".rowid_"+row+" .p_unit").val(unit);
    $(".rowid_"+row+" .p_conv").val(conversion);
    $(".rowid_"+row+" .p_qty").val(qty);
    $(".rowid_"+row+" .p_sellprice").val(parseFloat(sellingprice));
    $(".rowid_"+row+" .p_purchaseprice").val(parseFloat(purchaseprice));
    $(".rowid_"+row+" .p_average_program").val(parseFloat(average_price));
    $(".rowid_"+row+" .p_average").val(parseFloat(average_price));

    $('#modal-product').modal('hide');

    dt = $(".rowid_"+row).data();
    nIndex = $(".rowid_"+row).index();
    nIndex += 1;
    val = $(".rowdata").eq(nIndex);
    // tipe
    // 1 = add_new_row();
    if(val.length==0){
        tipenya = dt.typenya;
        if(tipenya == 1){
            add_new_row();
        }else if(tipenya == 2){
            add_row_not_order();
        }else if(tipenya == 3){
            add_new_services();
        }
    }
    if(dt.serial == "active"){
        $(".rowid_"+row+" .p_add_serial").empty();
        if(type == 2 && xserial_auto != 1 || type == 2 && xserial_auto == 1 && dt.detailsn == "active"){
            btn_serial  = '<a href="javascript:;" data-rowid="rowid_'+row+'" onclick="add_serial_number(this)" style="cursor:pointer;padding:5px;">Add Serial</a>';
            $(".rowid_"+row+" .p_add_serial").append(btn_serial);
        }
    }

    moneyFormat();
    if(status == 1){
        if(modul == "selling"){
            SumTotalServices();

        }else{
            SumTotal();
        }
    }
    create_format_currency2();

}
function keyup_product(row,product)
{
    product_val = $(product).val();
    if(product_val == ""){
        $(".rowid_"+row+" .p_id").val("");
        $(".rowid_"+row+" .p_code").val("");
        $(".rowid_"+row+" .p_name").val("");
        $(".rowid_"+row+" .p_unitid").val("");
        $(".rowid_"+row+" .p_unit").val("");
        $(".rowid_"+row+" .p_conv").val("");
        $(".rowid_"+row+" .p_sellprice").val("");
    
    } else {
        autocomplete_product(row,product);
    }
}
function autocomplete_product(row,product)
{
  
  $(".rowid_"+row+" .autocomplete_product").autocomplete({
    minLength:2,
    delay:0,
    max:10,
    scroll:true,
    source: function(request, response) {
        $.ajax({ 
            url: host + "api/autocomplete_product",
            data: { search: $(".rowid_"+row+" .autocomplete_product").val()},
            dataType: "json",
            type: "POST",
            success: function(data){
                response(data);
            }    
        });
    },
    select:function(event, ui){
        productid = ui.item.productid;
        code    = ui.item.product_code;
        name    = ui.item.product_name;
        unitid  = ui.item.product_unitid;
        unit    = ui.item.product_unit;
        qty     = ui.item.product_qty;
        type    = ui.item.product_type;
        conversion = ui.item.product_conversion;
        sellingprice = ui.item.product_sellingprice;
        average_price = ui.item.average_price;

        $(".rowid_"+row+" .p_id").val(productid);
        $(".rowid_"+row+" .p_code").val(code);
        $(".rowid_"+row+" .p_name").val(name);
        $(".rowid_"+row+" .p_unitid").val(unitid);
        $(".rowid_"+row+" .p_unit").val(unit);
        $(".rowid_"+row+" .p_conv").val(conversion);
        $(".rowid_"+row+" .p_sellprice").val(sellingprice);
        $(".rowid_"+row+" .p_qty").val(qty);
        $(".rowid_"+row+" .p_type").val(type);
        $(".rowid_"+row+" .p_average_program").val(parseFloat(average_price));
        $(".rowid_"+row+" .p_average").val(parseFloat(average_price));
        moneyFormat();
    }
  });
}