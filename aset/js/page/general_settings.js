var mobile      = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host        = window.location.origin+"/";
var url         = window.location.href;
var current_url = window.location.href;
var url_save 	= host+"api/save_general_setting";
var url_get_settings = host+"api/get_general_setting";
var page_name;
var url_modul;
var modul;

$(document).ready(function() {
    page_data   = $(".page-data").data();
    page_name   = page_data.page_name; 
    modul       = page_data.modul;

    get_setting(modul);

    $('.nav-menu a').each(function(){
      var $this = $(this);
      if($this.attr('href') == current_url){
          $this.addClass('active');
      }
    });

    get_general_setting();
});

function save(){
	proses_save_button();
	var form        = $('#'+modul)[0]; // You need to use standard javascript object here
    var formData    = new FormData(form);

    $.ajax({
        url : url_save,
        type: "POST",
        data: formData,
        mimeType:"multipart/form-data", // upload
        contentType: false, // upload
        cache: false, // upload
        processData:false, //upload
        dataType: "JSON",
        success: function(data)
        {	
        	if(data.status){
        		swal({   
		            title: language_app.lb_information,
		            text : data.message,
		            type: "success",   
		            showCancelButton: false,   
		            confirmButtonColor: "#DD6B55",   
		            confirmButtonText: language_app.lb_ok,   
		            closeOnConfirm: false, 
		            showLoaderOnConfirm: true, 
		            closeOnCancel: false }, 
		            function(isConfirm){   
		                if (isConfirm) { 
		                    location.reload();
		                }
		          });
        	}else{
        		swal('',data.message,'warning');
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

function get_general_setting(){
	var form        = $('#'+modul)[0]; // You need to use standard javascript object here
    var formData    = new FormData(form);

    $.ajax({
        url : url_get_settings,
        type: "POST",
        data: formData,
        mimeType:"multipart/form-data", // upload
        contentType: false, // upload
        cache: false, // upload
        processData:false, //upload
        dataType: "JSON",
        success: function(data)
        {	
        	if(data.status){
        		$.each(data.list,function(k,v){
        			nmPost 	= v.Code;
        			value 	= v.Value;
                    myarray = ['SiteLogo','SiteLogoSmall'];
                    if(jQuery.inArray(nmPost, myarray) != -1){
                        console.log(value);
                        $(".v"+nmPost+" .dropify-render img").remove();
                        img = '<img src="'+value+'" />';
                        $('#'+modul+" .v"+nmPost+" .dropify-render").append(img);
                        $(".dropify-preview").show();
                    }else{
                        $('#'+modul+' [name='+nmPost+']').val(value);
                    }
        		})
        	}else{
        		swal('',data.message,'warning');
        	}
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            console.log(jqXHR.responseText);
        }
    });
}

function hide_header(element,classnya){
    if($(classnya).hasClass('close')){
        $(classnya).removeClass('close');
        $(classnya).show(300);
        $(element).find('.fa').removeClass('fa-chevron-up');
        $(element).find('.fa').addClass('fa-chevron-down');
    }else{
        $(classnya).addClass('close');
        $(classnya).hide(-300);
        $(element).find('.fa').removeClass('fa-chevron-down');
        $(element).find('.fa').addClass('fa-chevron-up');
    }
}
function get_slideshow(element){
    dt      = $(element).data();
    id      = dt.id;
    modul   = dt.modul;
    get_setting(modul,id);
    language("english");
}
function reset_form(element){
    dt      = $(element).data();
    modul   = dt.modul;
    $('#'+modul)[0].reset();
    img_preview("reset");
}
function get_setting(modul,id){
    if(modul == "edit_slideshow"){
        url     = host + "api/slideshow/edit/"+id;
        form    = $('#slideshow')[0];
        $('#slideshow')[0].reset();
        language("english");
    } else {
        url     = host + "api/get_setting/"+modul;
        form    = $('#'+modul)[0];
        language("english");
    }
    var formData    = new FormData(form);
    formData.append('page_setting','slideshow');
    $.ajax({
        url : url,
        type: "POST",
        data:  formData,
        mimeType:"multipart/form-data", // upload
        contentType: false, // upload
        cache: false, // upload
        processData:false, //upload
        dataType: "JSON",
        success: function(data)
        {            
            if(data.HakAkses == "super_admin"){
                console.log(data);
            }
            if(modul == 'slideshow'){
                $.each(data.ListData,function(i,v){
                    add_slideshow_item(v);
                });
            } else if(modul == "edit_slideshow"){
                value = data.Data;
                img_preview("set",value.Patch);

                $("[name=AttachmentID]").val(value.AttachmentID);
                $("[name=Name]").val(value.Name);
                $("[name=NameColor]").val(value.NameColor);
                $("[name=Description]").val(value.Description);
                $("[name=Position]").val(value.Position);

                $("[name=AttachmentIDeng]").val(value.AttachmentIDeng);
                $("[name=Nameeng]").val(value.Nameeng);
                $("[name=NameColoreng]").val(value.NameColoreng);
                $("[name=Descriptioneng]").val(value.Descriptioneng);
                $("[name=Positioneng]").val(value.Positioneng);

                $.each(value.ButtonLink,function(i,v){
                    BtnIDx = "#BtnID-"+ (i + 1);
                    $(BtnIDx+' .BtnName').val(v.BtnName);
                    $(BtnIDx+' .BtnLink').val(v.BtnLink);
                    $(BtnIDx+' .BtnColor').val(v.BtnColor);
                });

                $('html,body').animate({scrollTop: $("#slideshow").offset().top - 150},'slow');
            } else {            
                $.each(data.ListData,function(i,v){
                    value = v.Value;
                    if(v.Code != "Logo"){
                        $("[name="+v.Code+"]").val(value);
                    }
                    if(v.Code == 'TimeZone'){
                         $('[name='+v.Code+']').val(value).trigger('change');
                    }
                    if(v.Code == "Logo"){
                        img_preview("set",host + value);
                    }
                    if(v.Code == "CompanyLocation"){
                        if(value){
                            $("#MAP").html(value);
                        }
                    }
                });
                $('[name="ID"]').val(data.VoucherPackageID);
                $('[name="Type"]').val(data.Type); 
                $('[name="Module"]').val(data.Module);
                $('[name="Price"]').val(data.Price);

                remove_module_all();
                $.each(data.ListData, function(i, v) {
                    add_module(v);
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown){
            console.log(jqXHR.responseText);
        }
    });
}

function save_setting(element)
{
    $(element).button('loading');
    dt      = $(element).data();
    modul   = dt.modul;

    if(modul == 'general'|| modul == 'slideshow'){
        if(modul == "general"){
            var file = $('[name=Logo]')[0].files[0];
        } else {
            var file = $('[name=Image]')[0].files[0];
        }
        if(file && file.size > 5000000) { //2 MB (this size is in bytes)
            $(element).button("reset");
            toastr.error('Image size too big, size maximum is 500kb',"Information");
            return;
        }
    }


    url             = host + "api/save_setting/"+modul;
    var form        = $('#'+modul)[0]; // You need to use standard javascript object here
    var formData    = new FormData(form);
    $.ajax({
        url : url,
        type: "POST",
        data:  formData,
        mimeType:"multipart/form-data", // upload
        contentType: false, // upload
        cache: false, // upload
        processData:false, //upload
        dataType: "JSON",
        success: function(data)
        {
            if(data.HakAkses == "rc"){
                console.log(data);
            }
            if(data.Status){
                swal("Info","saving data success","");
                if(data.Modul == "slideshow"){
                    $(".list-data-slideshow").empty();
                    $('#'+modul)[0].reset();
                    img_preview("reset");
                    get_setting(modul);
                }
            } else {
                if(data.Modul == "slideshow"){
                    if(data.message){
                        swal("Info",data.message,"warning");
                    }
                }
              // $('.form-group').removeClass('has-error'); // clear error class
              // $('.help-block').empty();
              //   for (var i = 0; i < data.inputerror.length; i++)
              //   {
              //       label = $('[name="'+data.inputerror[i]+'"]').parent().parent().find("label").text();
              //       error_label = label+" tidak boleh kosong";
              //       $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
              //       $('[name="'+data.inputerror[i]+'"]').next().text(error_label);
              //   }
            }
            $(element).button('reset');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            // alert('Error adding / update data');
            swal("Info","saving data failed","warning");
            $(element).button('reset');
            console.log(jqXHR.responseText);
        }
    });

}
function add_slideshow_item(v){
    item = '';
    item +=' <li class="item" data-id="'+v.AttachmentID+'" data-urutan="'+v.Sort+'">';
    item +='   <div class="text">';
    item +='   <div class="title" style="color:'+v.NameColor+' !important;">'+v.Name+'</div>';
    item +='   <div class="description"  style="color:'+v.NameColor+' !important;">'+v.Description+'</div>';
    item +='   </div>'
    item +='   <img src="'+v.Patch+'">';
    item +='   <div class="btn-group width-100 btn-control">';
    item +='      <a class="btn btn-white width-50" onclick="get_slideshow(this)" data-id="'+v.AttachmentID+'" data-modul="edit_slideshow">Edit</a>';
    item +='      <a class="btn btn-white width-50" onclick="delete_item(this)" data-id="'+v.ParentID+'" data-modul="slideshow">Delete</a>';
    item +='   </div>';
    item +='</li>';
    $(".list-data-slideshow").append(item);
}

function startCallback(event, ui) {
    // stuff
}
function stopCallback(event, ui) {
    ArrayID = []; 
    ArrayUrutan = [];
    Listul  = $(".list-drag .item");
    $.each(Listul,function(i,v){
      dt = $(v).data();
      id            = dt.id;
      urutan_before = dt.urutan;
      urutan        = i + 1;
      ArrayID.push(id);
      ArrayUrutan.push(urutan);
      $(v).data("urutan",urutan);
      console.log("id : "+id+",  urutan sekarang : " + urutan + ', urutan sebelumnya : '+urutan_before);
    });
    if(modul == "slideshow"){
        save_urutan(ArrayID,ArrayUrutan)
    }
}

$(".list-drag").sortable({
    start: startCallback,
    stop: stopCallback
}).disableSelection();
$(document).ready(function () {
   $("#main-menu-notfix, #main-menu-fix").sortable({
       connectWith: ".taskList",
       placeholder: 'task-placeholder',
       forcePlaceholderSize: true,
       update: function (event, ui) {
           var inprogress   = $("#main-menu-fix").sortable("toArray");
           StopMainMenu();  
       }
   }).disableSelection();

});
function StopMainMenu(){
    menifix      = $("#main-menu-fix .item");
    $.each(menifix,function(i,v){
      dt            = $(v).data();
      id            = dt.id;
      name          = dt.name;
      if(!$(v).hasClass("fix")){
        $(v).addClass("fix");
        item = '<input type="hidden" name="ContentIDFix[]" value="'+id+'" class="ContentIDFix">';
        $(v).append(item);
      }
    });
    $("#main-menu-notfix .item").removeClass("fix");
    $("#main-menu-notfix .item .ContentIDFix").remove();
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}

function save_urutan(ArrayID,ArrayUrutan){
    data_post = {
        ArrayID : ArrayID,
        ArrayUrutan : ArrayUrutan,
    };
    url = host + "api/slideshow/ubah_urutan/";
    $.ajax({
        url : url,
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data){
            if(data.HakAkses == "rc"){
                console.log(data);
            }
            if(data.Status){
                toastr.success("Update sorting data success","Information");
            } else {
                toastr.error("Update failed","Information");
            }
        },
        error: function (jqXHR, textStatus, errorThrown){
            toastr.error("Update failed","Information");
            $('#btnSave').button('reset');
            console.log(jqXHR.responseText);
        }
    });
}
function delete_item(element){
    ci_method = "";
    dt      = $(element).data();
    id      = dt.id;
    modul   = dt.modul;
    if(modul == "slideshow"){
        ci_method = "slideshow";
    }
    swal({   
        title: "Info",   
        text: "Are you sure to delete this data ?",   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: "Ya",   
        cancelButtonText: "Tidak",   
        closeOnConfirm: true,   
        closeOnCancel: true }, 
        function(isConfirm){   
        if (isConfirm) { 
            $.ajax({
                url : host +'api/'+ci_method+'/delete/'+id,
                type: "POST",
                dataType: "JSON",
                success: function(data){
                    if(data.Status){
                        swal("Info", "delete data success", "");   
                        reload_table();
                    } else {
                        swal("Info", data.message, "");   
                    }
                    if(modul == "slideshow"){
                        $(".list-data-slideshow").empty();
                        get_setting(modul);
                    }
                    remove_overlay();
                },
                error: function (jqXHR, textStatus, errorThrown){
                    console.log(jqXHR.responseText);
                    swal('Info','failed to delete this data');
                    remove_overlay();
                }
            });
        }
    });
}

function language(val){
    $('.tab-indo, .tab-eng').removeClass("active");
    if(val == "indonesia"){
        $('.vindo').show(300);
        $('.veng').hide(300);
        $('.tab-indo').addClass("active");
    }else{
        $('.vindo').hide(300);
        $('.veng').show(300);
        $('.tab-eng').addClass("active");
    }
}

function add_module(data)
{
    id_module += 1; 
    eid_module = "item-div-module-"+id_module;
    c_kotak = "'"+eid_module+"'";

    Price            = "";
    Type             = "";
    Module           = "";
    App              = "";
    ID               = "";
    if(data){
        Price            = data.Price;
        Type             = data.Type;
        Module           = data.Module;
        App              = data.App;
        ID               = data.VoucherPackageID;
    }

    item = '<div  id="'+eid_module+'">';
    item += '<div class="form-group item-div-module">';
    item += '<div class="col-sm-4">';
    item += '<label class="control-label">'+language_app.module+'</label>';
    item += '<input type="hidden" name="ID[]" class="form-control" value="'+ID+'">';
    item += '<select name="Module[]" class="form-control v_module">';
    item += '<option value="none">Select Module</option>';
    item += '<option value="1">Module</option>';
    item += '<option value="2">Additional</option>';
    item += '</select>';
    item += '<span class="help-block"></span>';
    item += '</div>';
    item += '</div>';

    item += '<div class="form-group">';
    item += '<div class="col-sm-4">';
    item += '<label class="control-label">'+language_app.lb_type+'</span></label>';
    item += '<select name="Type[]" class="form-control vtype">';
    item += '<option value="none">Select Type</option>';
    item += '<option value="3">3 Month</option>';
    item += '<option value="6">6 Month</option>';
    item += '<option value="12">12 Month</option>';
    item += '</select>';
    item += '<span class="help-block"></span>';
    item += '</div>';
    item += '</div>';

    item += '<div class="form-group">';
    item += '<div class="col-sm-4">';
    item += '<label class="control-label">'+language_app.price+'</label>';
    item += '<input type="text" name="Price[]" class="duit" value="'+Price+'">';
    item += '<span class="help-block"></span>';
    item += '</div>';
    item += '</div>';

    item += '<div class="form-group">';
    item += '<div class="col-sm-12">';
    item += '<a href="javascript:void(0)" onclick="remove_module('+c_kotak+')">'+language_app.lb_remove_module+'</a><hr/></div>';
    item += '</div>';
    item += '</div>';


    if($(".div-module .item-div-module").length >= 6){
        alert(language_app.lb_bank_max);
        return;
    }
    $(".div-module").append(item);
    if(data){
        index = $('.vtype').length - 1;
        $('.vtype').eq(index).val(Type);
        $('.v_module').eq(index).val(Module);
    }
    moneyFormat();
    create_format_currency2();
}
function remove_module(element)
{
    if($(".div-module .item-div-module").length == 1){
        alert(language_app.lb_bank_min_1);
        return;
    }
    $('#'+element).remove();
}
function remove_module_all()
{
    id_module = 1;
    $(".div-module div").remove();
}