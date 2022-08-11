var host         = window.location.origin+'/pipesys_qa/';
var s_level     = 'checkout';
var btn_txt     = '';
var url_back    = host + 'api/back_voucher';
$(document).ready(function() {
    btn_txt = language_app.lb_check_out;
    if(data_voucher.s_level){
        s_level = data_voucher.s_level;
        set_data_voucher();
    }
    $("[name=Type], [name=Qty], [name=App], [name=Module]").change(function(){
        Type        = $("[name=Type]").val();
        App         = $("[name=App]").val();
        Qty         = $("[name=Qty]").val();
        QtyModule   = $("[name=Module]").val();
        if(Type != "none" && Qty != "none" || Type != "none" && QtyModule != "none"){
            get_voucher_price();
        } else {
            $('[name=PriceDevice]').val(0.00);
            $('[name=PriceModule]').val(0.00);
            $("[name=Price]").val(0.00);
        }
    });
    check_level(s_level,data_voucher);
    $('.save').text(btn_txt);

});

function get_voucher_price()
{   
    Qty         = $("[name=Qty]").val();
    QtyModule   = $("[name=Module]").val();

    data_post = {
        App: "pipesys",
        Qty: Qty,
        Type: $("[name=Type]").val(),
        QtyModule : QtyModule,
    };
    $.ajax({
        url : host+"api/get_voucher_price",
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            if(data){
                no = 1;
                $('.totalAmounttxt').text(data.price_total_txt);
                $("[name=Price]").val(data.price_total_txt);
                
                $('#table-voucher tbody').empty();  
                if(QtyModule != "none"){
                    $('[name=PriceModule]').val(data.module_total_txt);
                    type    = $('[name=Type] option:selected').text();
                    qty     = $("[name=Module] option:selected").text();
                    item    = '<tr>';
                    item    += '<td>'+no+'</td>';
                    item    += '<td>'+type+'</td>';
                    item    += '<td>'+language_app.lb_voucher_module+'</td>';
                    item    += '<td>'+qty+'</td>';
                    item    += '<td>'+data.price_module_txt+'</td>';
                    item    += '<td>'+data.module_total_txt+'</td>';
                    item    += '</tr>';
                    $('#table-voucher tbody').append(item);
                    no +=1;
                }else{
                    $('[name=PriceModule]').val(0.00);
                }

                if(Qty != "none"){
                    $('[name=PriceDevice]').val(data.device_total_txt);
                    type    = $('[name=Type] option:selected').text();
                    qty     = $("[name=Qty] option:selected").text();
                    item    = '<tr>';
                    item    += '<td>'+no+'</td>';
                    item    += '<td>'+type+'</td>';
                    item    += '<td>'+language_app.lb_voucher_user+'</td>';
                    item    += '<td>'+qty+' User</td>';
                    item    += '<td>'+data.price_txt+'</td>';
                    item    += '<td>'+data.device_total_txt+'</td>';
                    item    += '</tr>';
                    $('#table-voucher tbody').append(item);
                    no +=1;
                }else{
                    $('[name=PriceDevice]').val(0.00);
                }

                item = '<tr>';
                item += '<th colspan="5">Total</th>';
                item += '<th>'+data.price_total_txt+'</th>';
                item += '</tr>';
                $('#table-voucher tbody').append(item);
            } else {
                $("[name=Price]").val(0.00);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            console.log(jqXHR.responseText);
        }
    });
}

function save(page){
    $('.save').text(language_app.lb_loading+'...');
    $('button').attr('disabled', true);

    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty();

    if(s_level == "checkout"){
        Qty         = $("[name=Qty]").val();
        QtyModule   = $("[name=Module]").val();
        data_post = {
            App         : "pipesys",
            Qty         : Qty,
            Type        : $("[name=Type]").val(),
            QtyModule   : QtyModule,
            s_level     : s_level,
        }
    }else if(s_level == "user_info"){
        status_validate = validate_form_user();
        if(!status_validate){
            $('.save').text(btn_txt);
            $('button').attr('disabled', false);
            check_agree();
            return false;
        }

        if(!page){
            if(status_validate){
                alert_confirm();
                $('.save').text(btn_txt);
                $('button').attr('disabled', false);
                check_agree();
                return;
            }
        }

        Name        = $('[name=Name]').val();
        Email       = $('[name=Email]').val();
        Address     = $('[name=Address]').val();
        City        = $('[name=City]').val();
        State       = $('[name=State]').val();
        Country     = $('[name=Country]').val();
        Agree       = $('[name=Agree]:checked').val();
        data_post   = {
            s_level : s_level,
            Name    : Name,
            Email   : Email,
            Address : Address,
            City    : City,
            State   : State,
            Country : Country,
            Agree   : Agree,
        }
    }else if(s_level == "confirm"){
        data_post = {
            s_level : s_level,
        }
        redirect_post("buy-voucher-app", {Status : "complete"});
        return '';
    }

    url = host + "save-voucher";
    $.ajax({
        url : url,
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data)
        {   
            if(data.status) //if success close modal and reload ajax table
            {   
                if(data.s_level){
                    check_level(data.s_level,data.data);
                }else if(data.message == "complete"){
                    location.reload();
                }
            }
            else
            {
                toastr.warning(language_app.lb_incomplete_form,language_app.lb_information);
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
            $('.save').text(btn_txt);
            $('button').attr('disabled', false);
            check_agree();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error buy voucher system');
            $('.save').text(btn_txt);
            $('button').attr('disabled', false);
            check_agree();
            console.log(jqXHR.responseText);
        }
    });

}

function check_level(level,data){
    s_level = level;
    $('.vaction .btn-back').remove();
    if(level == "checkout"){
        $('.vuser, .vconfirm').removeClass('active');
        $('.div-voucher').removeClass('content-hide');
        btn_txt = language_app.lb_check_out;
        $('.div-voucher').show(300);
        $('.div-user, .div-confirm').hide(300);
    }else if(level == "user_info"){
        $('.div-user').removeClass('content-hide');
        $('.vuser').addClass('active');
        $('.vconfirm').removeClass('active');
        btn_txt = language_app.lb_order;
        $('.div-user').show(300);
        $('.div-voucher, .div-confirm').hide(300);
        $('.vaction').append('<button onclick="voucher_back()" class="btn btn-danger btn-back min-width-100">'+language_app.btn_back+'</button>');
        check_agree();
    }else if(level == "confirm"){
        $('.div-confirm').removeClass('content-hide');
        $('.vconfirm, .vuser').addClass('active');
        btn_txt = language_app.lb_voucer_continue;
        $('.div-confirm').show(300);
        $('.div-voucher, .div-user').hide(300);
        $('.voucher_name').text(data.voucher_name);
        $('.voucher_email').text(data.voucher_email);
        $('.voucher_address').text(data.voucher_address);
        $('.voucher_city').text(data.voucher_city);
        $('.voucher_state').text(data.voucher_state);
        $('.voucher_country').text(data.voucher_country);
        $('.voucher_code').text(data.voucher_code);
    }
}

function voucher_back(){
    $('.btn-back').text(language_app.lb_loading+'...');
    $('button').attr('disabled', true);
    data_post = {page : "voucher"}
    $.ajax({
        url : url_back,
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data)
        {   
            if(data){
                check_level(data.s_level);
            }
            $('button').attr('disabled', false);
            $('.save').text(btn_txt);
            $('.btn-back').text('Back');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            console.log(jqXHR.responseText);
            $('.save').text(btn_txt);
            $('.btn-back').text('Back');
            $('button').attr('disabled', false);
        }
    });
}

function set_data_voucher(){
    if(data_voucher.voucher_qty){
        $("[name=Qty]").val(data_voucher.voucher_qty);
    }
    if(data_voucher.voucher_qty_module){
        $("[name=Module]").val(data_voucher.voucher_qty_module);
    }
    if(data_voucher.voucher_type){
        $("[name=Type]").val(data_voucher.voucher_type);
        get_voucher_price();
    }
}

function check_agree(){
    val = $('#Agree:checked').val();
    if(s_level == "user_info"){
        if(val){
            $('.save').attr('disabled',false);
        }else{
            $('.save').attr('disabled',true);
        }
    }
}

$('#Agree').on('click',function(){
    check_agree();
});

function IsEmail(email) {
  var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  if(!regex.test(email)) {
    return false;
  }else{
    return true;
  }
}

function IsName(name){
    if ( name.match('^[a-zA-Z]{3,16}$') ) {
        return true;
    } else {
        return false;
    }
}

function validate_form_user(){
    Name        = $('[name=Name]').val();
    Email       = $('[name=Email]').val();

    status = true;
    if(!IsEmail(Email)){
        $('[name=Email]').parent().addClass('has-error'); 
        $('[name=Email]').next().text(language_app.lb_email_format);
        status = '';
    }
    if(Email == ''){
        $('[name=Email]').parent().addClass('has-error'); 
        $('[name=Email]').next().text(language_app.lb_email_empty);
        status = '';
    }
    if(Name == ''){
        $('[name=Name]').parent().addClass('has-error'); 
        $('[name=Name]').next().text(language_app.lb_name_empty);
        status = '';
    }

    if(!status){
        toastr.warning(language_app.lb_incomplete_form,language_app.lb_information);
    }

    return status;
}

function alert_confirm(){
    swal({   
        title: language_app.lb_alert_order,   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: language_app.lb_order,   
        cancelButtonText: language_app.btn_cancel,   
        closeOnConfirm: true,   
        showLoaderOnConfirm: true,
        closeOnCancel: false }, 
        function(isConfirm){   
            if (isConfirm) { 
                save('save');
            } 
            else {
                swal(language_app.lb_canceled, "", "error");   
            } 
    });
}