var slim_modul,slim_modul2;
$(document).ready(function() {
	slim_modul = new SlimSelect({
      select: '#Days'
    })
});

$('#ap').change(function() {
	check_module_ap($(this));
});

function check_module_ap(element){
    if(element.is(":checked")) {
        $('.vdap input').attr('disabled', false);
        $('.vdap').show(300);
    }else{
        $('.vdap input').attr('disabled', true);
        $('.vdap input').prop('checked', false);
        $('.vdap').hide(300);
    }
}

$('#ar').change(function() {
	check_module_ar($(this));
});

function check_module_ar(element){
    if(element.is(":checked")) {
        $('.vdar input').attr('disabled', false);
        $('.vdar').show(300);
    }else{
        $('.vdar input').attr('disabled', true);
        $('.vdar input').prop('checked', false);
        $('.vdar').hide(300);
    }
}

$('#inventory').change(function(){
	check_module_inventory($(this));
});

function check_module_inventory(element){
    if(element.is(":checked")) {
        $('.vdinventory input').attr('disabled', false);
        $('.vdinventory').show(300);
    }else{
        $('.vdinventory input').attr('disabled', true);
        $('.vdinventory input').prop('checked', false);
        $('.vdinventory').hide(300);
    }
}

$('#ac').change(function() {
	check_module_ac($(this));
});

function check_module_ac(element){
    if(element.is(":checked")) {
        $('.vdac input').attr('disabled', false);
        $('.vdac').show(300);
    }else{
        $('.vdac input').attr('disabled', true);
        $('.vdac input').prop('checked', false);
        $('.vdac').hide(300);
    }
}

function check_lock(dt_class){
    if(dt_class == ".vap"){
        if($('#ap').is(":checked")){
            $('#ap').prop('checked', false);
            locked_expire('.vap');
        }else{
            $('#ap').prop('checked', true);
            unlock_expire('.vap');
        }
        check_module_ap($('#ap'));
    }else if(dt_class == ".var"){
        if($('#ar').is(":checked")){
            $('#ar').prop('checked', false);
            locked_expire('.var');
        }else{
            $('#ar').prop('checked', true);
            unlock_expire('.var');
        }
        check_module_ar($('#ar'));
    }else if(dt_class == ".vinventory"){
        if($('#inventory').is(":checked")){
            $('#inventory').prop('checked', false);
            locked_expire('.vinventory');
        }else{
            $('#inventory').prop('checked', true);
            unlock_expire('.vinventory');
        }
        check_module_inventory($('#inventory'));
    }else if(dt_class == ".vac"){
        if($('#ac').is(":checked")){
            $('#ac').prop('checked', false);
            locked_expire('.vac');
        }else{
            $('#ac').prop('checked', true);
            unlock_expire('.vac');
        }
        check_module_ac($('#ac'));
    }
}

function insert_voucher_module(dt_class){
    status      = false;
    dt_module   = '';
    if(dt_class == '.vap'){status      = true;dt_module   = 'ap';}
    else if(dt_class == '.var'){status      = true;dt_module   = 'ar';}
    else if(dt_class == '.vac'){status      = true;dt_module   = 'ac';}
    else if(dt_class == '.vinventory'){status      = true;dt_module   = 'inventory';}

    $(dt_class+' .div-error').removeClass('div-error');
    if(status){
        voucher     = $(dt_class+' .input_voucher').val();
        if(voucher){

            data_post = {
                Module  : dt_module,
                Voucher : voucher,
            }
            proses_save_button(dt_class+' .save_voucher');
            url = host+"api/voucher_save_use";
            $.ajax({
                url : url,
                type: "POST",
                data: data_post,
                dataType: "JSON",
                success: function(data)
                {
                    if(data.status){
                        swal('',data.message,'success');
                        if(data.Expire){
                            if(data.Module == "ap"){
                                $('.vap #timer2').data('expire', data.Expire);
                                $('.vap input, .vap button').attr('disabled', false);}
                            else if(data.Module == "ar"){
                                $('.var #timer2').data('expire', data.Expire);
                                $('.var input, .var button').attr('disabled', false);}
                            else if(data.Module == "ac"){
                                $('.vac #timer2').data('expire', data.Expire);
                                $('.vac input, .vac button').attr('disabled', false);}
                            else if(data.Module == "inventory"){
                                $('.vinventory #timer2').data('expire', data.Expire);
                                $('.vinventory input, .vinventory button').attr('disabled', false);}
                        }
                    }else{
                        swal('',data.message,'warning');
                        $(dt_class+' .input_voucher').addClass('div-error');
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

        }else{
            swal('',language_app.lb_incomplete_form,'warning');
            $(dt_class+' .input_voucher').addClass('div-error');
        }
    }
}
