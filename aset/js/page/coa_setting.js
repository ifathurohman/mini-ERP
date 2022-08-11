var mobile      = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host        = window.location.origin+'/';
var url         = window.location.href;
var url_save    = host + "api/save_coa";
var url_list    = host + "api/get_coa_setting";
var page_name;
var modul;
$(document).ready(function() {
    page_data   = $(".page-data").data();
    page_name   = page_data.page_name; 
    modul       = page_data.modul;
    edt 		= page_data.edit;

    if(edt == 1){
    	item = '<div class="btn-group pull-right">\
                  <button class="btn btn-primary save" onclick="save_coa_setting()" type="button"><i class="icon fa-save"></i> Save</button>\
              </div>';

        $('.vsave').append(item);
    }
    get_coa_setting();
});

function save_coa_setting(){
    $("#form input").attr("disabled",false);
    $('#btnSave, .save').button('loading');
    $('.form-group, .input-group').removeClass('has-error'); // clear error class
    $('.input-error').removeClass('input-error');
    $('.help-block').empty();

    url = url_save;

    var form        = $('#form')[0]; // You need to use standard javascript object here
    var formData    = new FormData(form);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        dataType: "JSON",
        processData: false,
        contentType: false,
        success: function(data)
        {
            console.log(data);
            $(".disabled").attr("disabled",true);
            if(data.status) //if success close modal and reload ajax table
            {
                swal('',data.message,'success');
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    list    = data.list[i];
                    tab     = data.tab[i];
                    if(list == 'list'){
                        item = '<i class="icon fa-exclamation-triangle" title="'+data.message+'" style="cursor:pointer;padding:5px;"></i>';
                        $(data.inputerror[i]+' .info-warning').append(item);
                    }else{
                        $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                        $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                    }

                    if(tab == "sell"){
                        $('.vstep1').addClass('input-error');
                    }else if(tab == "delivery"){
                        $('.vstep2').addClass('input-error');
                    }
                    else if(tab == "invoice"){
                        $('.vstep3').addClass('input-error');
                    }
                }
               
                if(data.message){
                    swal('',data.message,'warning');
                }else{
                    swal('','incomplete form', 'warning');
                }
            }  
            $('#btnSave, .save').button('reset');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            console.log(jqXHR.responseText);
            $('#btnSave, .save').button('reset');
        }
    });
}

function get_coa_setting(){
    url = url_list;
    var form        = $('#form')[0]; // You need to use standard javascript object here
    var formData    = new FormData(form);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        dataType: "JSON",
        processData: false,
        contentType: false,
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            list = data.list;
            $.each(list,function(i,v){
                $("[name='"+v.Code+"']").val(v.nValue+"||"+v.cValue);
                $("[name='"+v.Code+"']").next().val(v.cValue+"-"+v.coaName);
            })
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data');
            console.log(jqXHR.responseText);
        }
    });
}

