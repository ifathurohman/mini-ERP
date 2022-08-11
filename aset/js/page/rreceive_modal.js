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

function modal_receive2(id,classnya,page)
{
    $('#modal-receive').modal('show'); // show bootstrap modal
    $('#modal-receive .modal-title').text('Receive'); // Set Title to Bootstrap modal title
    if(classnya){
        tag_data = $(classnya).data();
        select 	 = tag_data.select;
        without  = tag_data.without;
        type     = tag_data.type;
    }
    crud        	= $('[name=crud]').val();
    temp_receiveno  = $('[name=temp_receiveno]').val();
    data_post = {
        id 		: id,
        page 	: page,
        crud    : crud,
        temp_receiveno : temp_receiveno,
    }
    url = host+"api/receive/"+page+"/"+id;
    tbl = $('.table-receive').DataTable();
    tbl.clear();
     $.ajax({
        url : url,
        type: "GET",
        dataType: "JSON",
        success: function(data){
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            $.each(data.list_data, function(i, v) {
                receiveno   = v.receiveno;
                receivename = v.receivename;
                date        = v.date;
                vendorid    = v.vendorid;
                vendorname  = v.vendorname;

                tag_data     = ' data-classnya="'+classnya+'" ';
                tag_data    +=' data-tax="'+parseFloat(v.Tax)+'" ';
                tag_data    +=' data-ppn="'+parseFloat(v.ppn)+'" ';
                tag_data    +=' data-page="'+page+'" ';
                tag_data    +=' data-receiveno="'+receiveno+'" ';
                tag_data    +=' data-receivename="'+receivename+'" ';
                tag_data    +=' data-vendorid="'+vendorid+'" ';
                tag_data    +=' data-vendorname="'+vendorname+'" ';

                item = '<tr>';
                item +='<td><a href="javascript:void(0)" '+tag_data+' onclick="chose_receive(this)">'+receiveno+'</a></td>';
                item +='<td><a href="javascript:void(0)" '+tag_data+' onclick="chose_receive(this)">'+receivename+'</a></td>';
                item +='<td><a href="javascript:void(0)" '+tag_data+' onclick="chose_receive(this)">'+date+'</a></td>';
                item +='</tr>';
                tbl.row.add( $(item)[0] ).draw();
            });
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
           console.log(jqXHR.responseText);
        }
    });
}
    
function modal_receive(id,classnya,page){
    $('#modal-receive').modal('show'); // show bootstrap modal
    $('#modal-receive .modal-title').text('Receive'); // Set Title to Bootstrap modal title
    if(classnya){
        tag_data = $(classnya).data();
        select   = tag_data.select;
        without  = tag_data.without;
        type     = tag_data.type;
    }
    crud            = $('[name=crud]').val();
    temp_receiveno  = $('[name=temp_receiveno]').val();
    data_post = {
        classnya        : classnya,
        version         : "serverSide",
        id              : id,
        page            : page,
        crud            : crud,
        temp_receiveno  : temp_receiveno,
    }
    url = host+"api/receive/"+page+"/"+id;
    tbl = $('.table-receive').DataTable({
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

function chose_receive(receive)
{
    data_page   = $(".data-page, .page-data").data();
    modul       = data_page.modul;
    detail      = "";

    receive = $(receive).data();
    classnya    = receive.classnya;
    code        = receive.receiveno;
    vendor      = receive.vendor;
    tax         = receive.tax;
    ppn         = receive.ppn;
    BranchID    = receive.branch_id;
    BranchName  = receive.branch_name;

    if(classnya){
        tag_data = $(classnya).data();
        detail   = tag_data.detail;
    }
    
    if(detail == "active"){
        receive_det(code);
    }

    if(modul == "retur"){
        if(BranchID){
            $('#BranchName2').val(BranchName);
        }else{
            $('#BranchName2').val('');
        }
    }

    // if(page == "retur"){
    //     get_receive_product(receive);
    // }
    $(classnya).val(code);
    $('#modal-receive').modal('hide');

    // $("[name=receiveno]").val(receiveno);
    // $("[name=vendorid]").val(vendorid);
    // $("[name=vendorname]").val(vendorname);
}
function receive_det(code){
    // code2 = code.replace(/\//g, '-');
    crud                = $('[name=crud]').val();
    temp_receivedet     = $('[name=temp_receivedet]').val();
    data_post = {
        sellno           : code,
        modul            : modul,
        method           : crud,
        temp_receivedet  : temp_receivedet,
    }
    code = code.replace(/\/+/g, '-');
    url = host+"api/receive_detail/"+modul+"/"+code;
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
                    receive_no      = v.receive_no;
                    receive_det1    = v.receive_det;
                    productid       = v.productid;
                    code            = v.product_code;
                    name            = v.product_name;
                    Tcode           = v.transactionCode;
                    qty             = parseFloat(v.product_qty);
                    receive_qty     = parseFloat(v.receive_qty);
                    xqty            = qty - receive_qty;
                    unitid          = v.unitid;
                    product_unit    = v.product_unitname;
                    product_konv    = v.product_konv;
                    product_type    = v.product_type;
                    serial_auto     = v.serial_auto;
                    product_price   = parseFloat(v.product_price);
                    product_discount= parseFloat(v.product_discount);
                    product_tax     = v.tax;
                    checked         = '';
                    d_module        = v.Module;
                    BranchID        = v.BranchID;
                    BranchName      = v.branchName;

                    if(d_module){
                        d_module        = d_module.replace(/"/g, "'");
                    }

                    tag_data    =' data-code="'+code+'" ';

                    if(modul == "retur"){
                        // qty     = parseFloat(v.qty_stock);
                        // xqty    =  qty;
                    }
                    if(modul == "retur" && crud == "update"){
                        xdata = ckselldet(receive_det1);
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
                        btn_serial  = '<a href="javascript:;" data-rowid="vd'+receive_det1+'" onclick="add_serial_number(this)" style="cursor:pointer;padding:5px;">Add Serial</a>';
                    }
                
                    // checkbox
                    item  = '<tr class="vd'+receive_det1+' rowdata" data-classnya="vd'+receive_det1+'" data-detailsn="active">';
                    item += '<td><div class="info-warning"></div></td>';
                    item += '<td>\
                            <input class="cekbox" type="checkbox" onclick="SumTotal(this)" name="check[]" value="'+receive_det1+'" '+checked+'>\
                            <input class="disabled" type="hidden" name="detid[]" >\
                            <input class="disabled detailID" type="hidden" name="product_deldet[]" value="'+receive_det1+'" >\
                            <input class="disabled headerID" type="hidden" name="product_delno[]" value="'+receive_no+'" >\
                            <input class="disabled p_id" type="hidden" name="product_id[]" value="'+productid+'" >\
                            <input class="disabled" type="hidden" name="product_module[]" value="'+d_module+'" >\
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

                    // Transaction Code
                    item += '<td>\
                            <input class="disabled" type="text" value="'+Tcode+'" >\
                            </td>';

                    // qty purchase
                    item += '<td>\
                            <input class="disabled duit" data-qty="active" type="text" value="'+xqty+'" >\
                            </td>';

                    // qty receive
                    item += '<td>\
                            <input placeholder="input qty" type="text" data-qty="active" class="duit" onkeyup="SumTotal()" name="product_qty[]" value="'+xqty+'" min="0" max="'+xqty+'" >\
                            </td>';

                    // unit
                    item += '<td>\
                            <input class="disabled" type="text" value="'+product_unit+'" >\
                            <input class="disabled" type="hidden" name="product_unitid[]" value="'+unitid+'" >\
                            <input class="disabled p_type" type="hidden" name="product_type[]" value="'+product_type+'" >\
                            <input class="disabled p_serial_auto" type="hidden" value="'+serial_auto+'">\
                            </td>';

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
                            <input type="text" class="disabled" name="product_discount[]" value="'+product_discount+'">\
                            <input type="text" class="disabled content-hide duit" name="product_discountrp[]" value="'+0+'">\
                            </td>';

                      // Tax
                    item += '<td>\
                            <input type="text" class="disabled duit p_product_tax" value="'+0+'">\
                            <input type="hidden" class="disabled" name="product_tax2[]" value="'+product_tax+'">\
                            </td>';

                    // sub total
                    item += '<td>\
                           <input type="text" class="disabled duit" name="product_subtotal[]" value="'+0+'">\
                            </td>';

                    // remark
                    item += '<td>\
                            <input type="text" name="product_remark[]" placeholder="input remark">\
                            </td>';

                    item += '<td>\
                            '+btn_serial+'\
                            </td>';

                    item += '</tr>';

                    $(tbl+' tbody').append(item);
                    $(".disabled").attr("disabled",true);
                });
            }else{
                item  = '<tr>';
                item += '<td colspan="14"><div class="text-center">data not found</div></td>';
                item += '</tr>';

                $(tbl+' tbody').append(item);
                $(".disabled").attr("disabled",true);
            }
            moneyFormat();
            if(modul == "receive1"){
                SumTotal();
            }else if(modul == "retur"){
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