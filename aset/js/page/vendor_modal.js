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
function vendor_modal2(classnya="")
{
    position= "all";
    page    = '';
    without = ''; 
    $('#modal-vendor').modal('show'); // show bootstrap modal
    $('#modal-vendor .modal-title').text('Vendor'); // Set Title to Bootstrap modal title
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

    tbl = $('.table-vendor').DataTable();
    tbl.clear();
     $.ajax({
        url : host+"api/vendor",
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data){
            console.log(data);
            app     = data.app;
            if(!mobile && app == "salespro"){
                $("#modal-vendor .modal-dialog").css("width","50%");
            }
            $.each(data.list_data, function(i, v) {
                vendorid    = v.vendorid;
                code        = v.code;
                name        = v.name;
                address     = v.address;
                npwp        = v.npwp;
                term         = v.term;
                d_address   = '';
                d_city      = '';
                d_province  = '';

                if(v.d_address){d_address = v.d_address;}
                if(v.d_city){d_city = v.d_city;}
                if(v.d_province){d_province = v.d_province;}

                tag_data    = ' data-classnya="'+classnya+'" ';
                tag_data    +=' data-vendorid="'+vendorid+'" ';
                tag_data    +=' data-code="'+code+'" ';
                tag_data    +=' data-name="'+name+'" ';
                tag_data    +=' data-address="'+address+'" ';
                tag_data    +=' data-npwp="'+npwp+'" ';
                tag_data    +=' data-term="'+term+'" ';
                tag_data    +=' data-productcustomer="'+v.productcustomer+'" ';
                tag_data    +=' data-d_address="'+d_address+'" ';
                tag_data    +=' data-d_city="'+d_city+'" ';
                tag_data    +=' data-d_province="'+d_province+'" ';

                item = '<tr>';
                if(app == "pipesys"){
                item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_vendor(this)">'+code+'</a></td>';
                }
                item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_vendor(this)">'+name+'</a></td>';
                if(app == "salespro"){
                item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_vendor(this)">'+address+'</a></td>';
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
    
function vendor_modal(classnya){
    position= "all";
    page    = '';
    without = ''; 
    $('#modal-vendor').modal('show'); // show bootstrap modal
    $('#modal-vendor .modal-title').text('Vendor'); // Set Title to Bootstrap modal title
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
    url = host+"api/vendor";
    tbl = $('.table-vendor').DataTable({
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

function chose_vendor(v)
{
    data_page   = $(".data-page, .page-data").data();
    modul       = data_page.modul;

	v = $(v).data();
    classnya    = v.classnya;
    vendorid    = v.vendorid;
    name        = v.name;
	address 	= v.address;
    npwp        = v.npwp;
    term        = v.term;
    val         = vendorid + "-" + name;
    productcustomer = v.productcustomer;
    d_address   = v.d_address;
    d_city      = v.d_city;
    d_province  = v.d_province;

    if(modul == "selling"){
        vendorid2 = $('#CustomerID').val();
        vendorid2 = vendorid2.split('-');
        if(vendorid2[0] != vendorid){
            reset_address_vendor();
        }
        $('#BillingTo, #DeliveryTo').val(name);
    }
    else if(modul == "delivery"){
        vendorid2 = $('#CustomerID').val();
        vendorid2 = vendorid2.split('-');
        ckorder   = $('input[type=radio][name=ckOrder]:checked').val();
        if(vendorid2[0] != vendorid){
            reset_address_vendor();
            $(classnya).val(val);
            if(ckorder == 1){
                reset_data_sell();
                all_sell_detail();
            }
        }
    }
    else if(modul == "invoice_ar"){
        vendorid2 = $('#CustomerID').val();
        vendorid2 = vendorid2.split('-');
        if(vendorid2[0] != vendorid){
            reset_address_vendor();
        }
        $(classnya).val(val);
        OrderType = $('input[type=radio][name=OrderType]:checked').val();
        if(OrderType == 1){
            invoice_delivery();
        }else if(OrderType == 2){
            invoice_selling();
        }else{

        }
    }

    else if(modul == "invoice_ap"){
        vendorid2 = $('#CustomerID').val();
        vendorid2 = vendorid2.split('-');
        if(vendorid2[0] != vendorid){
            reset_address_vendor();
        }
        $(classnya).val(val);
        OrderType = $('input[type=radio][name=OrderType]:checked').val();
        if(OrderType == 1){
            invoice_receive();
        }else if(OrderType == 2){
            invoice_purchase();
        }else{

        }
    }

    else if(modul == "payment_ar"){
        $(classnya).val(val);
        get_invoice();
    }
     else if(modul == "payment_ap"){
        $(classnya).val(val);
        get_invoice_ap();
    }
    else if(modul == "penerimaan"){
        vendorid2  = $('#receipt_name').val();
        vendorid2  = vendorid2.split('-');
        ckorder   = $('input[type=radio][name=ckOrder]:checked').val();
        if(vendorid2[0] != vendorid){
          $(classnya).val(val);
            if(ckorder == 1){
                reset_data_receive();
            }
        }
    }else if(modul == "return_sales"){
        vendorid2 = $('#CustomerID').val();
        vendorid2 = vendorid2.split('-');
        if(vendorid2[0] != vendorid){
            resetdata();
    }
    }else if(modul == "retur"){
        vendorid2 = $('#receipt_name').val();
        vendorid2 = vendorid2.split('-');
        if(vendorid2[0] != vendorid){
            resetdata();
    }
    }else if(modul == "ar_correction"){
        $(classnya+" [name='arVendorName[]']").val(name);
        $(classnya+" [name='arVendorID[]']").val(vendorid);

    }else if(modul == "ap_correction"){
        $(classnya+" [name='apVendorName[]']").val(name);
        $(classnya+" [name='apVendorID[]']").val(vendorid);
    }


    $(classnya).val(val);
    $(classnya).data('productcustomer',productcustomer);
    $(classnya+"-address").val(d_address);
    $(classnya+"-city").val(d_city);
    $(classnya+"-province").val(d_province);
    $(classnya+"-npwp").val(npwp);
    $(classnya+"-term").val(term);
    $(classnya+"-name").val(name);
    if($(classnya+"-term").length>0){
        count_term('term');
    }
    $('#modal-vendor').modal('hide');
}

function chose_vendor_address(v){
    v = $(v).data();
    classnya    = v.classnya;
    vendorid    = v.vendorid;
    name        = v.name;
    addressid   = v.addressid;
    address     = v.address;
    city        = v.city;
    province    = v.province;
    val         = addressid + "-" +address;
    if(classnya == "sellingvendor"){
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
    $('#modal-vendor-address').modal('hide');

}

function without_vendor(v){
    v = $(v).data();
    classnya    = v.classnya;
    $(classnya).val('');
    $('#modal-vendor').modal('hide');
}

function address_vendor(vendorid,classnya){
    $('#modal-vendor-address').modal('show'); // show bootstrap modal
    $('#modal-vendor-address .modal-title').text('Vendor Address'); // Set Title to Bootstrap modal title
    if(classnya){
        tag_data = $(classnya).data();
    }

    data_post = {
        vendorid : vendorid,
    }

    tbl = $('.table-vendor-address').DataTable();
    tbl.clear();
     $.ajax({
        url : host+"api/vendor_address",
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data){
            console.log(data);
            app     = data.app;
            if(!mobile && app == "salespro"){
                $("#modal-vendor-address .modal-dialog").css("width","50%");
            }
            $.each(data.list_data, function(i, v) {
                vendorid    = v.vendorid;
                addressid   = v.addressid;
                address     = v.address;
                city        = v.city;
                province    = v.province;
                name        = v.name;

                tag_data    = ' data-classnya="'+classnya+'" ';
                tag_data    +=' data-vendorid="'+vendorid+'" ';
                tag_data    +=' data-addressid="'+addressid+'" ';
                tag_data    +=' data-address="'+address+'" ';
                tag_data    +=' data-city="'+city+'" ';
                tag_data    +=' data-province="'+province+'" ';
                tag_data    +=' data-name="'+name+'" ';

                item = '<tr>';
                item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_vendor_address(this)">'+address+'</a></td>';
                item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_vendor_address(this)">'+city+'</a></td>';
                item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_vendor_address(this)">'+province+'</a></td>';
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

function venodr_add(classnya){
    tg_data     = $(classnya).data();
    modulTitle  = language_app.lb_customer;
    typenya     = 'customer';
    page_address= '';
    addressno   = 0;
    contactno   = 0;
    if(tg_data.position == "vendor"){
        modulTitle = language_app.lb_vendor;
        typenya     = 'vendor';
    }
    if(tg_data.page == "invoice"){
        page_address = "invoice";
    }else if(tg_data.page == "delivery"){
        page_address = "delivery";
    }
    $('#form-vendor-add')[0].reset(); // reset form on modals
    $('#form-vendor-add .form-group').removeClass('has-error'); // clear error class
    $('#form-vendor-add .help-block').empty(); // clear error string
    $('#form-vendor-add [name=classnya]').val(classnya);
    $('#form-vendor-add [name=type]').val(typenya);
    $('#form-vendor-add [name=page_address]').val(page_address);
    $("#form-vendor-add .address_v div").remove();
    $("#form-vendor-add .contact_v div").remove();
    add_address();
    add_contact();
    $('#modal-vendor-add').modal('show'); // show bootstrap modal
    $('#modal-vendor-add .modal-title').text(language_app.lb_add_new+' '+ modulTitle); // Set Title to Bootstrap modal title
    reset_button_action();
}

var addressno = 0;
function add_address(){
    addressno    += 1;
    address_code = "";
    city         = "";
    province     = "";
    address      = "";
    title        = "";
    checked      = "";
    checked2     = "";

    count_address = $('#form-vendor-add [name="address_code[]"]').length;
    if(count_address == 0){
        checked  = " checked ";
        checked2 = " checked ";
    }

    type = $('#form-vendor-add [name=type]').val();
    if(type == "customer"){
        title = "Delivery";
    }else{
        title = "Good Receipt";
    }

    str_disabled = '';

    item = '<div class="form-group col-sm-12">\
                <input type="hidden" name="address_code[]" value="'+address_code+'" '+str_disabled+'>\
                <label class="control-label">Address</label>\
                <input name="address[]" type="text" class="form-control" value="'+address+'"  '+str_disabled+'>\
                <span class="help-block"></span>\
            </div>\
            <div class="form-group col-sm-6">\
                <label class="control-label">City</label>\
                <input name="city[]" type="text" class="form-control" value="'+city+'"  '+str_disabled+'>\
                <span class="help-block"></span>\
            </div>\
            <div class="form-group col-sm-6">\
                <label class="control-label">Province</label>\
                <input name="province[]" type="text" class="form-control" value="'+province+'"  '+str_disabled+'>\
                <span class="help-block"></span>\
            </div>\
            <div class="form-group col-sm-12">\
                <label class="control-label block">Set Default Address</label>\
                <div class="radio-custom radio-primary radio-inline">\
                  <input type="radio" class="r-invoice" id="invoice-'+addressno+'" name="invoice[]" value="1" '+checked+'  '+str_disabled+'>\
                  <label for="invoice-'+addressno+'">Invoice</label>\
            </div>\
            <div class="radio-custom radio-primary radio-inline">\
                  <input type="radio" class="r-delivery" id="delivery-'+addressno+'" name="delivery[]" value="1" '+checked2+'  '+str_disabled+'>\
                  <label class="type_title" for="delivery-'+addressno+'">'+title+'</label>\
                </div>\
                <span class="help-block"></span>\
                <hr>\
            </div>';
    $("#form-vendor-add .address_v").append(item);
}
var contactno = 0;
function add_contact()
{
    contactno       += 1;
    contact_code    = "";
    phone           = "";
    email           = "";

    str_disabled = '';

    item = ' <div class="form-group col-sm-6">\
                <input type="hidden" name="contact_code[]" value="'+contact_code+'" '+str_disabled+'>\
                <label class="control-label">Phone</label>\
                <input name="phone[]" type="text" class="form-control angka" value="'+phone+'" '+str_disabled+'>\
                <span class="help-block"></span>\
              </div>\
              <div class="form-group col-sm-6">\
                <label class="control-label">Email</label>\
                <input name="email[]" type="text" class="form-control" value="'+email+'" '+str_disabled+'>\
                <span class="help-block"></span>\
              </div>';
    $("#form-vendor-add .contact_v").append(item);
    angkaFormat();

}

function vendor_save(){
    page_data   = $(".page-data").data();
    modul       = page_data.modul;
    proses_save_button('','#modal-vendor-add');

    url = host + "vendor/simpan/partner";

    form = $('#form-vendor-add').serializeArray();
    invoice     = $('.r-invoice');
    delivery    = $('.r-delivery');
    $.each(invoice,function(k,v){
        val = 0;
        if($(v).is(':checked')){
            val = 1;
        }
        form.push({name: 'r_invoice[]', value: val});
    });
    $.each(delivery,function(k,v){
        val = 0;
        if($(v).is(':checked')){
            val = 1;
        }
        form.push({name: 'r_delivery[]', value: val});
    });
    $.ajax({
        url : url,
        type: "POST",
        data: form,
        dataType: "JSON",
        success: function(data)
        {
            if(data.status) //if success close modal and reload ajax table
            {   
                classnya = $('#form-vendor-add [name=classnya]').val();
                if(modul == "penerimaan"){
                    vendorid2  = $('#receipt_name').val();
                    vendorid2  = vendorid2.split('-');
                    ckorder   = $('input[type=radio][name=ckOrder]:checked').val();
                    if(vendorid2[0] != data.ID){
                      $(classnya).val(data.ID+"-"+data.Name);
                        if(ckorder == 1){
                            reset_data_receive();
                        }
                    }
                }else if(modul == "delivery"){
                    vendorid2 = $('#CustomerID').val();
                    vendorid2 = vendorid2.split('-');
                    ckorder   = $('input[type=radio][name=ckOrder]:checked').val();
                    if(vendorid2[0] != data.ID){
                        reset_address_vendor();
                        $(classnya).val(data.ID+"-"+data.Name);
                        if(ckorder == 1){
                            reset_data_sell();
                            all_sell_detail();
                        }
                    }
                }
                $(classnya).val(data.ID+"-"+data.Name);
                $(classnya+"-name, #DeliveryTo").val(data.Name);
                $(classnya+"-npwp").val(data.npwp);
                $(classnya+"-term").val(data.ap_max);
                $(classnya).data('productcustomer',data.productcustomer);
                $(classnya+"-address").val(data.address);
                $(classnya+"-city").val(data.city);
                $(classnya+"-province").val(data.province);
                if($(classnya+"-term").length>0){
                    count_term('term');
                }
                swal('',data.message,'success');
                $('#modal-vendor-add').modal("hide");
            }
            else{
              $('#form-vendor-add .form-group').removeClass('has-error'); // clear error class
              $('#form-vendor-add .help-block').empty();
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('#form-vendor-add [name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                    //select parent twice to select div form-group class and add has-error class
                    $('#form-vendor-add [name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
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