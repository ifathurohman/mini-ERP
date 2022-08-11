var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host = window.location.origin+'/pipesys_qa/';
var url = window.location.href;
var page_login = host + "main/login";
var page_register = host + "main/register";
var save_method; //for save method string
var table;
var url_list = host + "product/ajax_list/";
var url_edit = host + "product/ajax_edit/";
var url_hapus = host + "product/ajax_delete/";
var url_simpan = host + "product/simpan";
var url_update = host + "product/ajax_update";
var costmethod = 'average';

$(document).ready(function() {
    data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    costmethod  = data_page.costmethod;
    title_page  = data_page.title;
        
    $("[name=inventory]").change(function() {
        check_inventory();
    });
    $("[name=sales]").change(function() {
        check_sales();
    });

    $('body').on('click', function (e) {
        $('[data-toggle="popover"]').each(function () {
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                $(this).popover('hide');
            }
        });
    });

    $("[name=product_type]").change(function() {
        check_product_type();
    });
    $("[name=serial_auto]").change(function() {
        check_product_type();
    });

    //datatables
    filter_table();
});
function filter_table(page){
    data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    url         = url_list+url_modul+"/"+modul;
    date_now    = data_page.date;

    fSearch             = $('#form-filter [name=fSearch]').val();
    fActive             = $('#form-filter [name=fActive]').val();
    fProductType        = $('#form-filter [name=fProductType]').val();
    fSalesType          = $('#form-filter [name=fSalesType]').val();
    // fTypeStatus         = $('#form-filter [name=fTypeStatus]').val();

    data_post = {
        Search              : fSearch,
        Active              : fActive,
        ProductType         : fProductType,
        SalesType           : fSalesType,
    }
    table = $('#table').DataTable({
        "destroy"   : true,
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "searching": false, //Feature Search false
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
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [{
            "targets": [0], //last column
            "orderable": false, //set not orderable
        },],
    });
}
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}

function tambah()
{
    // $("[name=product_code]").attr("disabled",false);
    $(".parent_product_v").hide();
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $("#form input, #form textarea, #form select").attr("disabled",false);
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('.inventory').show(300);
    $('#modal').modal('show'); // show bootstrap modal
    $('.modal-title').text(language_app.lb_add_new+' '+title_page); // Set Title to Bootstrap modal title
    $('[name="crud"]').val("insert");
    $(".serial_format_v").hide();
    
    $(".dropify-preview").hide();
    $(".dropify-render img").remove();
    $('.dropify-filename-inner').text('');
    $(".dropify-clear").click();

    $('#unit').keyup(function(){
        $(this).val($(this).val().toUpperCase());
    });
    $("[name=product_type]").attr("disabled",false);
    $('.costmethod').hide(300);
    $('.inventory').hide(300);
    $('.sales').hide(300);
    $("[name=purchase_price]").attr("disabled",true);
    $("[name=unit]").show(300);
    $("[name=qty]").attr("disabled",true);
    // $("[name=konv]").attr("disabled",true);
    $('.vaction').hide();
    check_inventory();
    check_product_type();
    check_sales();
    check_costmethod(costmethod);
    $('[data-toggle="popover"]').popover();
    reload_table();
    reset_button_action();
}

function edit(id)
{
    // $("[name=product_code]").attr("disabled",true);
    save_method = 'update';
    $('#form')[0].reset();
    $("#form input, #form textarea, #form select").attr("disabled",false);
    $('.form-group').removeClass('has-error');
    $('.help-block').empty(); // clear error string
    $(".dropify-preview").hide();
    $(".dropify-render img").remove();
    $('.dropify-filename-inner').text('');
    $(".dropify-clear").click();
    //Ajax Load data from ajax
    reset_button_action();
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(json_respons)
        {   
            data = json_respons.data;
            $("[name=productid]").val(data.productid);
            $("[name=product_code]").val(data.product_code);
            $("[name=product_name]").val(data.product_name);
            $("[name=min_qty]").val(data.min_qty);
            $("[name=unit]").val(data.unit);
            $('#unit').keyup(function(){
                $(this).val($(this).val().toUpperCase());
            });
            // $("[name=konv]").val(data.conversion);
            $("[name=selling_price]").val(parseFloat(data.selling_price).toFixed( 2 ));
            $("[name=purchase_price]").val(parseFloat(data.purchase_price).toFixed( 2 ));
            $(".serial_format_v").hide();
            $(".vaction").hide();
            $("[name=product_category]").val(data.parent_code);
            $("[name=inventory]").attr('disabled',false);
            if(data.ProductType == 'item'){
                $("#inventory").prop("checked",true);
                $('#inventory').attr('readonly',true);
                $(".inventory").show(300);
            }else{
                $("#inventory").prop("checked",false);
                $('#inventory').attr('readonly',false);
                $(".inventory").hide(300);
            }
            $("[name=sales]").attr('disabled',true);
            if(data.SalesType == 'sell'){
                $("#sales").prop("checked",true);
                $(".sales").show(300);
            }else{
                $("#sales").prop("checked",false);
                $(".sales").hide(300);
            }
            $("[name=purchase_price]").attr("disabled",true);
            if(data.CostMethod == "average"){
                $('.costmethod').hide(300);
            }else{
                $('.costmethod').show(300);
            }
            img = '<img src="'+json_respons.Image+'" />';
            $(".dropify-render").append(img);
            $(".dropify-preview").show();

            $('[data-toggle="popover"]').popover();
            $('body').on('click', function (e) {
                $('[data-toggle="popover"]').each(function () {
                    //the 'is' for buttons that trigger popups
                    //the 'has' for icons within a button that triggers a popup
                    if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                        $(this).popover('hide');
                    }
                });
            });
            $("[name=product_type]").attr("disabled",true);
            $("[name=serial_auto]").attr("disabled",true);
            $("[name=serial_format]").attr("disabled",true);
            $("[name=product_code]").attr('disabled',true);
            $("[name=konv]").attr('disabled', true);
            $("[name=qty]").attr('disabled', true);
            // if(data.typecode == 1){
            //     $("[name=product_code]").removeAttr('disabled', false);
            // }
            if(data.type == 1){
                $("#unique").prop("checked",true);
                $(".serial_format_v").show();
                $('#serial_auto').prop('checked', false);
            } else if(data.type == 2){
                $("#serial").prop("checked",true);
                if(data.serial_auto == 1){
                    $('#serial_auto').prop('checked', true);
                }else{
                    $('#serial_auto').prop('checked', false);
                }
            } else {
                $("#general").prop("checked",true);
                $('#serial_auto').prop('checked', false);
            }
            $("[name=serial_format]").val(data.serial_format);

            $('#modal').modal("show");
            $('.modal-title').text(language_app.btn_edit+' '+title_page);

            success_save_button();

            check_costmethod(data.CostMethod);
            check_product_type();
            moneyFormat();
            create_format_currency2();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}
function save()
{
   	proses_save_button();
    var url;
    if(save_method == 'add') {
        url = url_simpan;
    } else {
        url = url_update;
    }
    var form        = $('#form')[0]; // You need to use standard javascript object here
    var formData    = new FormData(form);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        mimeType:"multipart/form-data", // upload
        contentType: false, // upload
        cache: false, // upload
        processData:false, //upload
        dataType: "JSON",
        success: function(data)
        {
            if(data.status) //if success close modal and reload ajax table
            {
                swal('',data.message,'success');
                $('#modal').modal("hide");
                reload_table();
            }
            else{
              $('.form-group').removeClass('has-error'); // clear error class
              $('.help-block').empty();
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                    //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                    // swal('',data.error_string[i],'warning');
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
function hapus(id)
{
    swal({   title: language_app.lb_undeleted,   
             // text: "You will not be able to recover this data !",   
             type: "warning",   
             showCancelButton: true,   
             confirmButtonColor: "#DD6B55",   
             confirmButtonText: language_app.lb_deleted,   
             cancelButtonText: language_app.lb_canceled1,   
             closeOnConfirm: false,   
             closeOnCancel: false }, 
             function(isConfirm){   
                 if (isConfirm) { 
                    $.ajax({
                        url : url_hapus+id+"/nonactive",
                        type: "POST",
                        dataType: "JSON",
                        success: function(data)
                        {
                            $(".modal:visible").modal('toggle');
                            reload_table();
                            swal('',language_app.lb_success,'success');
                        },
                        error: function (jqXHR, textStatus, errorThrown){
                            swal('Error deleting data');
                        }
                    });
                } 
                else {
                    swal(language_app.lb_canceled,"", "error");   
                } 
    });
}
function active(id){

    swal({   title: language_app.lb_undeleted, 
             // text: "You will not be able to recover this data !",   
             type: "warning",   
             showCancelButton: true,   
             confirmButtonColor: "#DD6B55",   
             confirmButtonText: language_app.lb_deleted,   
             cancelButtonText: language_app.lb_canceled1,
             closeOnConfirm: false,   
             closeOnCancel: false }, 
             function(isConfirm){   
                 if (isConfirm) { 
                    $.ajax({
                    url : url_hapus+id+"/active",
                    type: "POST",
                    dataType: "JSON",
                    success: function(data)
                    {
                        $(".modal:visible").modal('toggle');
                        reload_table();
                        swal('',language_app.lb_success,'success');
                    },
                    error: function (jqXHR, textStatus, errorThrown){
                        swal('Error undeleting data');
                    }
                });
            } 
            else {
                swal(language_app.lb_canceled, "error");  
            } 
    });
}
function modal_import()
{
    $(".dropify-clear").click(); 
    $('#modal-import').modal('show');
    $('.modal-title').text('Import Data');
    success_save_button("next","btn-import");
}
function import_data()
{
    proses_save_button("next","btn-import");

    url = host+"product/import";
    var form = $('#form-import')[0]; // You need to use standard javascript object here
    var formData = new FormData(form);
    $.ajax({
    url : url,
    type: "POST",
    // data: $('#form').serialize(),
    data:  formData,
    mimeType:"multipart/form-data",
    contentType: false,
    cache: false,
    processData:false,
    dataType: "JSON",
    success: function(data)
    {   
        if(data.hak_akses == "super_admin"){
            console.log(data);
        }
        if(data.status){ 
           // $('#modal').modal("hide");
           $(".modal:visible").modal('toggle');
           // swal('',data.message,'success');
           // reload_table();
           item_import_data(data);
        } else {
            swal('',data.message,'warning');  
        }
        success_save_button("next","btn-import");
    },
    error: function (jqXHR, textStatus, errorThrown){
        alert("import data error");
        success_save_button("next","btn-import");
        console.log(jqXHR.responseText);
    }
  });
}
function clear_img(){
  var drEvent = $('.dropify').dropify();
  drEvent = drEvent.data('dropify');
  drEvent.resetPreview();
  drEvent.clearElement();
}

function view(id)
{
    no = 0;
    tbl = $('.table-view-product').DataTable();
    tbl.clear();

    // $('.table-view-product tbody').children('tr').remove();
    $.ajax({
        url : host+"product/view/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            if(data.status){
                product_name = data.product_name;
                $.each(data.list_data, function(i, v) {
                    no += 1;
                    name = v.name;
                    address = v.address;
                    city = v.city;
                    qty = v.qty;
                    item = '<tr>\
                        <td>'+no+'</td>\
                        <td>'+name+'</td>\
                        <td>'+address+'</td>\
                        <td>'+city+'</td>\
                        <td>'+qty+'</td>\
                        <td>'+v.purchase_price+'</td>\
                        <td>'+v.average_price+'</td>\
                    </tr>';

                    // $(".table-view-product tbody").append(item);
                    tbl.row.add( $(item)[0] ).draw();

                });



                $('#modal-view-product').modal('show'); // show bootstrap modal
                $('#modal-view-product .modal-title').text(product_name); // Set Title to Bootstrap modal title

            } else {
                alert(data.message);
            }
        },
        error: function (jqXHR, textStatus, errorThrown){
            alert('failed get data');
        }
    });
}

function view_product(id,page){
        reset_button_action();
        action_print_button();
        id_row = 0;
        // save_method = "view";
        $('#form')[0].reset();
        $("#form input, #form textarea, #form select, #button").attr("disabled",true);
        $('.form-group').removeClass('has-error');
        $('.help-block').empty(); // clear error string
        $('.vaction').show();
        $('.dropify-filename-inner').text('');
        $(".dropify-clear").click();

        $.ajax({
            url : url_edit + id,
            type: "GET",
            dataType: "JSON",
           success: function(json_respons)
        {
            data = json_respons.data;
            if(json_respons.hakakses == "super_admin"){
                console.log(json_respons);
            }
            $("[name=productid]").val(data.productid);
            $("[name=product_code]").val(data.product_code);
            $("[name=product_name]").val(data.product_name);
            $("[name=min_qty]").val(data.min_qty);
            $("[name=unit]").val(data.unit);
            $("[name=konv]").val(data.conversion);
            $("[name=selling_price]").val(parseFloat(data.selling_price).toFixed(2));
            $("[name=purchase_price]").val(parseFloat(data.purchase_price).toFixed(2));
            $(".serial_format_v").hide();
            $("[name=product_category]").val(data.parent_code);
            $("[name=inventory]").attr('disabled',true);
            if(data.ProductType == 'item'){
                $("#inventory").prop("checked",true);
                $(".inventory").show(300);
            }else{
                $("#inventory").prop("checked",false);
                $(".inventory").hide(300);
            }
            $("[name=sales]").attr('disabled',true);
            if(data.SalesType == 'sell'){
                $("#sales").prop("checked",true);
                $(".sales").show(300);
            }else{
                $("#sales").prop("checked",false);
                $(".sales").hide(300);
            }
            if(data.CostMethod == "average"){
                $('.costmethod').hide(300);
            }else{
                $('.costmethod').show(300);
            }
            $(".dropify-preview").hide();
            $(".dropify-render img").remove();

            $('body').on('click', function (e) {
                $('[data-toggle="popover"]').each(function () {
                    //the 'is' for buttons that trigger popups
                    //the 'has' for icons within a button that triggers a popup
                    if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                        $(this).popover('hide');
                    }
                });
            });

            img = '<img src="'+json_respons.Image+'" />';
            $(".dropify-render").append(img);
            $(".dropify-preview").show();

            if(data.type == 1){
                $("#unique").prop("checked",true);
                $(".serial_format_v").show();
                $('#serial_auto').prop('checked', false);
            } else if(data.type == 2){
                $("#serial").prop("checked",true);
                if(data.serial_auto == 1){
                    $('#serial_auto').prop('checked', true);
                }else{
                    $('#serial_auto').prop('checked', false);
                }
            } else {
                $("#general").prop("checked",true);
                $('#serial_auto').prop('checked', false);
            }
            $("[name=serial_format]").val(data.serial_format);

            $('#modal').modal("show");
            $('.modal-title').text(title_page+' '+language_app.btn_detail);

            set_button_action(json_respons); 
            check_costmethod(data.CostMethod);
            check_product_type();
            moneyFormat();
            create_format_currency2();

        },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
                console.log(jqXHR.responseText);
            }
        });
}
function view_serial(id)
{
    $('#modal-view-serial').modal('show');
    $('.modal-title').text(language_app.lb_product_serial);
    tblserial = $('.table-view-serial').DataTable({
        "searching": false,
        "destroy": true,
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": host+"api/serial_number_datatables",
            "type": "POST",
            "data": {
            productid: id,
          }
        },
        //Set column definition initialisation properties.
        "columnDefs": [{
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },],
    });
}

function keyup_product(row,group,page){
    group_val = $(group).val();
    if(group_val == ""){

    }else{
        autocomplete_product_unit(row,group_val,page);
    }
}
function autocomplete_product_unit(row,group_val,page){
    if(page == "product"){
      classnya = ".autocomplete_product_unit";
    }else{
      classnya = ".rowid_"+row+" .autocomplete_product_unit";
    }
    $(classnya).autocomplete({
    minLength:1,
    max:10,
    scroll:true,
    source: function(request, response) {
        $.ajax({ 
            url: host + "api/autocomplete_product_unit",
            data: { search: group_val},
            dataType: "json",
            type: "POST",
            success: function(data){
                response(data);
            }    
        });
    },
    select:function(event, ui){
        label = ui.item.label;
        // $(".rowid_"+row+" .p_id").val(productid);
    }
  });
}
// Jquery Dependency

$("input[data-type='currency']").on({
    keyup: function() {
      formatCurrency($(this));
    },
    blur: function() { 
      formatCurrency($(this), "blur");
    }
});


function formatNumber(n) {
  // format number 1000000 to 1,234,567
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
}


function formatCurrency(input, blur) {
  // appends $ to value, validates decimal side
  // and puts cursor back in right position.
  
  // get input value
  var input_val = input.val();
  
  // don't validate empty input
  if (input_val === "") { return; }
  
  // original length
  var original_len = input_val.length;

  // initial caret position 
  var caret_pos = input.prop("selectionStart");
    
  // check for decimal
  if (input_val.indexOf(".") >= 0) {

    // get position of first decimal
    // this prevents multiple decimals from
    // being entered
    var decimal_pos = input_val.indexOf(".");

    // split number by decimal point
    var left_side = input_val.substring(0, decimal_pos);
    var right_side = input_val.substring(decimal_pos);

    // add commas to left side of number
    left_side = formatNumber(left_side);

    // validate right side
    right_side = formatNumber(right_side);
    
    // On blur make sure 2 numbers after decimal
    if (blur === "blur") {
      right_side += "00";
    }
    
    // Limit decimal to only 2 digits
    right_side = right_side.substring(0, 2);

    // join number by .
    input_val = left_side + "." + right_side;

  } else {
    // no decimal entered
    // add commas to number
    // remove all non-digits
    input_val = formatNumber(input_val);
    input_val = input_val;
    
    // final formatting
    if (blur === "blur") {
      input_val += ".00";
    }
  }
  
  // send updated string to input
  input.val(input_val);

  // put caret back in the right position
  var updated_len = input_val.length;
  caret_pos = updated_len - original_len + caret_pos;
  input[0].setSelectionRange(caret_pos, caret_pos);
}

function check_inventory(){
    if($("[name=inventory]").is(":checked")) {
        $('.inventory').show(300);
    }else{
        $('.inventory').hide(300);
    }
}

function check_sales(){
    if($("[name=sales]").is(":checked")) {
        $('.sales').show(300);
    }else{
        $('.sales').hide(300);
    }
}

function check_product_type(){
    if($("[name=product_type]").is(":checked")) {
        $('.serial_format_v').show(300);
        if($('#serial_auto').is(':checked')){
            $('.serial_format_v2').show(300);
        }else{
            $('.serial_format_v2').hide(300);
        }
    }else{
        $('.serial_format_v, .serial_format_v2').hide(300);
    }
}

function check_costmethod(page){
    if(page == "average"){
        $('.purchase').hide();
    }else{
        $('.purchase').show();
    }
}

// 20190716 MW
// View import
function item_import_data(data){
    $('#modal-import-data').modal('show');
    $('#modal-import-data .modal-title').text(language_app.lb_import_detail);
    $('#modal-import-data .content-import').empty();
    $('#modal-import-data .div-loader').hide();
    if(data.data.length>0){
        item = '<table id="table-import-data" data-filename="'+data.inputFileName+'" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">';
            item += '<thead><tr>';
            item += '<th style="width:50px"></th>';
            total_column_header = data.header[0].length;
            total_failed  = 0;
            total_success = 0;
            $.each(data.header[0],function(k,v){
                item += '<th>'+v+'</th>';
            });
            item += '<th>Status</th>';
            item += '<th>'+language_app.lb_message+'</th>';
            item += '</tr></thead>';

            item += '<tbody>';
            $.each(data.data,function(k,v){
                checkbox_status = '';
                background  	= 'bg-merah-pias';
                label_status 	= language_app.lb_failed;
                status_data 	= '';
                status_category = '';
                if(v.status){
                	total_success += 1;
                    checkbox_status = ' checked ';
                    background = '';
                    label_status 	= '<hijau>'+language_app.lb_success+'</hijau>';
                    if(v.status_data == "insert"){
                    	status_data = '<br><hijau>'+language_app.lb_product_insert+'</hijau>';
                    }else{
                    	status_data = '<br><hijau>'+language_app.lb_product_update+'</hijau>';
                    }
                    if(v.status_category == "insert"){
                        status_category = '<br><hijau>'+language_app.lb_category_insert+'</hijau>';
                    }else if(v.status_category == "update"){
                        status_category = '<br><hijau>'+language_app.lb_category_update+'</hijau>';
                    }
                }else{
                	total_failed += 1;
                }

                checkbox = '<div class="checkbox-custom checkbox-primary">\
                  <input type="checkbox" '+checkbox_status+' disabled>\
                  <label style="color:white">.</label>\
                </div>';

                item += '<tr class="'+background+'">';
                item += '<td>'+checkbox+'</td>';
                item += '<td>'+v.product_code+'</td>';
                item += '<td>'+v.category_code+'</td>';
                item += '<td>'+v.category_name+'</td>';
                item += '<td>'+v.product_name+'</td>';
                item += '<td>'+v.product_min_qty+'</td>';
                item += '<td>'+v.product_unit+'</td>';
                item += '<td>'+v.product_selling+'</td>';
                if(data.CostMethod == "standard"){
                	item += '<td>'+v.product_purchase+'</td>';
                }
                item += '<td>'+v.product_type+'</td>';
                item += '<td>'+v.product_sales+'</td>';
                item += '<td>'+v.product_serial+'</td>';
                item += '<td>'+label_status+status_data+status_category+'</td>';
                item += '<td>'+v.remark+'</td>';
                item += '</tr>';
            });
            item += '</tbody>';

            colspan = total_column_header + 3 - 1;
            // item += '<tfoot>';
            // 	item += '<tr>';
            // 	item += '<th class="text-right" colspan="'+colspan+'">Total Data : </th>';
            // 	item += '<th colspan="1">'+data.data.length+'</th>';
            // 	item += '</tr>';
            // 	item += '<tr>';
            // 	item += '<th class="text-right" colspan="'+colspan+'">Total Failed : </th>';
            // 	item += '<th colspan="1">'+total_failed+'</th>';
            // 	item += '</tr>';
            // 	item += '<tr>';
            // 	item += '<th class="text-right" colspan="'+colspan+'">Total Success : </th>';
            // 	item += '<th colspan="1">'+total_success+'</th>';
            // 	item += '</tr>';
            // item += '</tfoot>';

        item += '</table>';

        item_total = '<span>'+language_app.lb_total_data+' : '+data.data.length+', '+total_success+' '+language_app.lb_success+', '+total_failed+' '+language_app.lb_fail+'</span>';

        $('#modal-import-data .content-import').append(item_total);
        $('#modal-import-data .content-import').append(item);
        $('#modal-import-data #table-import-data').DataTable({
        	"destroy"   : true,
        });
    }else{
        item = '<center><h4>'+language_app.lb_data_not_found+'</h4></center>';
        $('#modal-import-data .content-import').append(item);
    }
}

// 20190716 MW
// save import
function save_import(){
	tag_data = $('#modal-import-data #table-import-data').data();
	if(tag_data.filename){
		proses_save_button('import','btn-import-data');
		data_post = {filename : tag_data.filename};
		$.ajax({
	        url : host+"product/save_import",
	        type: "POST",
	        data : data_post,
	        dataType: "JSON",
	        success: function(data){
	            if(data.hakakses == "super_admin"){
	                console.log(data);
	            }
	            if(data.status){
	            	$('#modal-import-data').modal('hide');
	            	swal('',data.message, 'success');
	            	reload_table();
	            }else{
	            	swal('',data.message, 'warning');
	            }
	            success_save_button('import','btn-import-data');
	        },
	        error: function (jqXHR, textStatus, errorThrown){
	            alert('Error adding / update data');
	            success_save_button('import','btn-import-data');
	            console.log(jqXHR.responseText);
	        }
	    });
	}else{
		swal('',language_app.lb_file_not_found,'warning');
	}
}