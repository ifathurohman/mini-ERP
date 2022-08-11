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
function warehouse_modal2(classnya="")
{
    position= "all";
    page    = '';
    without = ''; 
    $('#modal-warehouse').modal('show'); // show bootstrap modal
    $('#modal-warehouse .modal-title').text('warehouse'); // Set Title to Bootstrap modal title
    if(classnya){
        tag_data = $(classnya).data();
        position = tag_data.position;
        without  = tag_data.without;
        page     = tag_data.page;
    }

    if(without == "active"){
        $('.btn-without').show();
        $('.btn-without').data('classnya', classnya);
    }else{
        $('.btn-without').hide();
    }

    data_post = {
        position : position,
        page : page,
    }

    tbl = $('.table-warehouse').DataTable();
    tbl.clear();
     $.ajax({
        url : host+"api/warehouse",
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data){
            console.log(data);
            app     = data.app;
            if(!mobile && app == "salespro"){
                $("#modal-warehouse .modal-dialog").css("width","50%");
            }
            $.each(data.list_data, function(i, v) {
                warehouseid = v.warehouseid;
                code        = v.code;
                name        = v.name;
                address     = v.address;
                description = v.description;

                // if(v.d_address){d_address = v.d_address;}
                // if(v.d_city){d_city = v.d_city;}
                // if(v.d_province){d_province = v.d_province;}

                tag_data    = ' data-classnya="'+classnya+'" ';
                tag_data    +=' data-warehouseid="'+warehouseid+'" ';
                tag_data    +=' data-code="'+code+'" ';
                tag_data    +=' data-name="'+name+'" ';
                tag_data    +=' data-productwarehouse="'+v.productwarehouse+'" ';
                tag_data    +=' data-address="'+address+'" ';
                tag_data    +=' data-description="'+description+'" ';

                item = '<tr>';
                if(app == "pipesys"){
                item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_warehouse(this)">'+code+'</a></td>';
                }
                item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_warehouse(this)">'+name+'</a></td>';
                if(app == "salespro"){
                item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_warehouse(this)">'+address+'</a></td>';
                }
                item += '</tr>';
	    		tbl.row.add( $(item)[0] ).draw();
          	});
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
           console.log("error get list product");
           console.log(jqXHR.responseText);
        }
    });
}
    
function warehouse_modal(classnya){
    position= "all";
    page    = '';
    without = ''; 
    $('#modal-warehouse').modal('show'); // show bootstrap modal
    $('#modal-warehouse .modal-title').text(language_app.lb_warehouse); // Set Title to Bootstrap modal title
    if(classnya){
        tag_data = $(classnya).data();
        position = tag_data.position;
        without  = tag_data.without;
        page     = tag_data.page;
    }
    if(without == "active"){
        $('.btn-without').show();
        $('.btn-without').data('classnya', classnya);
    }else{
        $('.btn-without').hide();
    }

    data_post = {
        classnya    : classnya,
        version     : "serverSide",
        position    : position,
        page        : page,
    }
    url = host+"api/warehouse";
    tbl = $('.table-warehouse').DataTable({
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

function chose_warehouse(v)
{
    data_page   = $(".data-page, .page-data").data();
    modul       = data_page.modul;
    warehouse_reset = '';
    if(data_page.warehouse_reset){
        warehouse_reset = data_page.warehouse_reset;
    }

	v = $(v).data();
    classnya    = v.classnya;
    warehouseid = v.warehouseid;
    name        = v.name;
	address 	= v.address;
    description = v.description;
    warehousecode = warehouseid + "-" + name;

    if(modul == "stock_correction" || modul == "stock_opname" || modul == "mutation" || warehouse_reset == "active"){
        warehouseid2 = $('#WarehouseID').val();
        warehouseid2 = warehouseid2.split('-');
        if(warehouseid2[0] != warehouseid){
            reset_column_product();
        }
    }


    $(classnya).val(warehousecode);
    $(classnya+"-name").val(v.name);
    $(classnya+"-description").val(description);
    
    $('#modal-warehouse').modal('hide');
}

function chose_warehouse_address(v){
    v = $(v).data();
    classnya    = v.classnya;
    warehouseid    = v.warehouseid;
    name        = v.name;
    addressid   = v.addressid;
    address     = v.address;
    city        = v.city;
    province    = v.province;
    val         = addressid + "-" +address;
    if(classnya == "sellingwarehouse"){
        $('#delAddress, #invAddress').val(address);
        $('#BillingTo, #DeliveryTo').val(name);
        $('#delCity, #invCity').val(city);
        $('#delProvince, #invProvince').val(province);

    }else{
        $(classnya).val(val);
        $(classnya+' .address').val(address);
        $(classnya+' .city').val(city);
        $(classnya+' .province').val(province);
    }
    $('#modal-warehouse-address').modal('hide');

}

function without_warehouse(v){
    data_page   = $(".data-page, .page-data").data();
    modul       = data_page.modul;
    warehouse_reset = '';
    if(data_page.warehouse_reset){
        warehouse_reset = data_page.warehouse_reset;
    }

    v = $(v).data();
    classnya    = v.classnya;
    $(classnya).val('');
    $(classnya+'-name').val('');
    if(modul == "stock_correction" || modul == "stock_opname" || modul == "mutation" || warehouse_reset == "active"){
        reset_column_product();
    }
    $('#modal-warehouse').modal('hide');
}

function address_warehouse(warehouseid,classnya){
    $('#modal-warehouse-address').modal('show'); // show bootstrap modal
    $('#modal-warehouse-address .modal-title').text('warehouse Address'); // Set Title to Bootstrap modal title
    if(classnya){
        tag_data = $(classnya).data();
    }

    data_post = {
        warehouseid : warehouseid,
    }

    tbl = $('.table-warehouse-address').DataTable();
    tbl.clear();
     $.ajax({
        url : host+"api/warehouse_address",
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data){
            console.log(data);
            app     = data.app;
            if(!mobile && app == "salespro"){
                $("#modal-warehouse-address .modal-dialog").css("width","50%");
            }
            $.each(data.list_data, function(i, v) {
                warehouseid    = v.warehouseid;
                addressid   = v.addressid;
                address     = v.address;
                city        = v.city;
                province    = v.province;
                name        = v.name;

                tag_data    = ' data-classnya="'+classnya+'" ';
                tag_data    +=' data-warehouseid="'+warehouseid+'" ';
                tag_data    +=' data-addressid="'+addressid+'" ';
                tag_data    +=' data-address="'+address+'" ';
                tag_data    +=' data-city="'+city+'" ';
                tag_data    +=' data-province="'+province+'" ';
                tag_data    +=' data-name="'+name+'" ';

                item = '<tr>';
                item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_warehouse_address(this)">'+address+'</a></td>';
                item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_warehouse_address(this)">'+city+'</a></td>';
                item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_warehouse_address(this)">'+province+'</a></td>';
                item += '</tr>';
                tbl.row.add( $(item)[0] ).draw();
            });
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
           console.log("error get list product");
        }
    });
}

function warehouse_add(classnya){
    tg_data     = $(classnya).data();
    modulTitle  = language_app.lb_warehouse;
    typenya     = 'warehouse';
    page_address= '';
    // if(tg_data.position == "warehouse"){
    //     modulTitle = language_app.lb_warehouse;
    //     typenya     = 'warehouse';
    // }
    if(tg_data.page == "stock"){
        page_address = "stock";
    }
    // $('#form-warehouse-add')[0].reset(); // reset form on modals
    $('#form-warehouse-add .form-group').removeClass('has-error'); // clear error class
    $('#form-warehouse-add .help-block').empty(); // clear error string
    $('#form-warehouse-add [name=classnya]').val(classnya);
    $('#form-warehouse-add [name=type]').val(typenya);
    $('#form-warehouse-add [name=page_address]').val(page_address);
    $('#modal-warehouse-add').modal('show'); // show bootstrap modal
    $('#modal-warehouse-add .modal-title').text(language_app.lb_add_new+' '+ modulTitle); // Set Title to Bootstrap modal title
    reset_button_action();
}

function warehouse_save(){
    proses_save_button('','#modal-warehouse-add');

    url = host + "warehouse/simpan/warehouse";

    form = $('#form-warehouse-add').serializeArray();
    // invoice     = $('.r-invoice');
    // delivery    = $('.r-delivery');
    // $.each(invoice,function(k,v){
    //     val = 0;
    //     if($(v).is(':checked')){
    //         val = 1;
    //     }
    //     form.push({name: 'r_invoice[]', value: val});
    // });
    // $.each(delivery,function(k,v){
    //     val = 0;
    //     if($(v).is(':checked')){
    //         val = 1;
    //     }
    //     form.push({name: 'r_delivery[]', value: val});
    // });
    $.ajax({
        url : url,
        type: "POST",
        data: form,
        dataType: "JSON",
        success: function(data)
        {
            console.log(data);
            if(data.status) //if success close modal and reload ajax table
            {   
                classnya = $('#form-warehouse-add [name=classnya]').val();
                $(classnya).val(data.ID+"-"+data.Name);
                $(classnya+"-name, #DeliveryTo").val(data.Name);
                $(classnya+"-npwp").val(data.npwp);
                $(classnya+"-term").val(data.ap_max);
                $(classnya).data('productcustomer',data.productcustomer);
                $(classnya+"-address").val(data.address);
                $(classnya+"-city").val(data.city);
                $(classnya+"-province").val(data.province);

                $(classnya).val(data.WarehouseID);
                $(classnya+"Code").val(data.Code);
                $(classnya+"Name").val(data.Name);
                $(classnya+"Address").val(data.Address);
                $(classnya+"Description").val(data.Description);

                swal('',data.message,'success');
                $('#modal-warehouse-add').modal("hide");
            }
            else{
              $('#form-warehouse-add .form-group').removeClass('has-error'); // clear error class
              $('#form-warehouse-add .help-block').empty();
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('#form-warehouse-add [name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                    //select parent twice to select div form-group class and add has-error class
                    $('#form-warehouse-add [name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                    // swal('',data.error_string[i],'warning');
                }
                if(data.message){
                    swal('',data.message,'warning');
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