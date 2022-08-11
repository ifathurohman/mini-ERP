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

var clickCount = 0;
function sales_modal2(classnya)
{
    $(this).dblclick(function(){
        return;  
    });

    position= "all";
    $('#modal-sales').modal('show'); // show bootstrap modal
    $('#modal-sales .modal-title').text('sales'); // Set Title to Bootstrap modal title
    if(classnya){
        tag_data = $(classnya).data();
        without  = tag_data.without;
    }

    if(without == "active"){
        $('.btn-without').show();
        $('.btn-without').data('classnya', classnya);
    }else{
        $('.btn-without').hide();
    }

    data_post = {
        position : position,
    }

    tbl = $('.table-sales').DataTable();
    tbl.clear();
    $.ajax({
        url : host+"api/sales",
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data){
            app     = data.app;
            if(!mobile && app == "salespro"){
                $("#modal-sales .modal-dialog").css("width","50%");
            }
            $.each(data.list_data, function(i, v) {
                salesid    = v.salesid;
                code        = v.code;
                name        = v.name;
                address     = v.address;

                tag_data    = ' data-classnya="'+classnya+'" ';
                tag_data    +=' data-salesid="'+salesid+'" ';
                tag_data    +=' data-code="'+code+'" ';
                tag_data    +=' data-name="'+name+'" ';
                tag_data    +=' data-address="'+address+'" ';

                item = '<tr>';
                if(app == "pipesys"){
                item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_sales(this)">'+code+'</a></td>';
                }
                item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_sales(this)">'+name+'</a></td>';
                if(app == "salespro"){
                item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_sales(this)">'+address+'</a></td>';
                }
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
    
function sales_modal(classnya){
    position= "all";
    $('#modal-sales').modal('show'); // show bootstrap modal
    $('#modal-sales .modal-title').text('sales'); // Set Title to Bootstrap modal title
    if(classnya){
        tag_data = $(classnya).data();
        without  = tag_data.without;
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
    }

    url = host+"api/sales";
    tbl = $('.table-sales').DataTable({
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

function chose_sales(v)
{
	v = $(v).data();
    classnya    = v.classnya;
    salesid    = v.salesid;
    name        = v.name;
	address 	= v.address;
    val         = salesid + "-" + name;
    $(classnya).val(val);
    $(classnya+"-address").val(address);
    $(classnya+"-name").val(name);
    $('#modal-sales').modal('hide');
}

function without_sales(v){
    v = $(v).data();
    classnya    = v.classnya;
    $(classnya).val('');
    $('#modal-sales').modal('hide');
}

function sales_add(classnya){
    modulTitle = language_app.lb_sales_employee;
    $('#form-sales-add')[0].reset(); // reset form on modals
    $('#form-sales-add .form-group').removeClass('has-error'); // clear error class
    $('#form-sales-add .help-block').empty(); // clear error string
    $('#form-sales-add [name=classnya]').val(classnya);
    $('#modal-sales-add').modal('show'); // show bootstrap modal
    $('#modal-sales-add .modal-title').text(language_app.lb_add_new+' '+ modulTitle); // Set Title to Bootstrap modal title
    reset_button_action();
}

function sales_save(){
    proses_save_button('','#modal-sales-add');

    $('#form-sales-add .form-group').removeClass('has-error'); // clear error class
    $('#form-sales-add .help-block').empty();

    var form        = $('#form-sales-add')[0]; // You need to use standard javascript object here
    var formData    = new FormData(form);

    url = host + "sales/save";

    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        dataType: "JSON",
        processData: false,
        contentType: false,
        success: function(data)
        {
            if(data.status) //if success close modal and reload ajax table
            {   
                classnya = $('#form-sales-add [name=classnya]').val();
                val = data.ID +"-"+ data.name;
                $(classnya).val(val);
                $(classnya+"-address").val(data.address);
                $(classnya+"-name").val(data.name);
                $('#modal-sales-add').modal("hide");
                swal('',data.message,'success');
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++){
                    label = $('#form-sales-add [name="'+data.inputerror[i]+'"]').parent().find("label").text();
                    label = label.replace("(*)", "");
                    if(data.error_string[i] == ''){
                        error_label = label+" cannot be null";
                    }else{
                        error_label = data.error_string[i];
                    }
                    $('#form-sales-add [name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                    $('#form-sales-add [name="'+data.inputerror[i]+'"]').next().text(error_label);
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