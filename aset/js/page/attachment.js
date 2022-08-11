// kalau pusing tanya yang bikinya
var host        = window.location.origin+'/';
var attc;
var TypePage;
var IDPage;

$(document).ready(function(){    
    data_page = $(".page-data").data();
    TypePage  = data_page.type;
    IDPage    = data_page.id2;
    get_what_expect();
    // Basic
    $('.file-icon').attr("src","");
    $('.file-icon').html("<h4>Add File</h4>");
    var drEvent = $('#input-file-events').dropify();
    drEvent.on('dropify.beforeClear', function(event, element){
        return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
    });
    drEvent.on('dropify.afterClear', function(event, element){
        alert('File deleted');
    });
    drEvent.on('dropify.errors', function(event, element){
        console.log('Has Errors');
    });
    var drDestroy = $('#input-file-to-destroy').dropify();
    drDestroy = drDestroy.data('dropify')
    $('#toggleDropify').on('click', function(e){
        e.preventDefault();
        if (drDestroy.isDropified()) {
            drDestroy.destroy();
        } else {
            drDestroy.init();
        }
    });   
});

function get_what_expect()
{
  $("#attachment-list").empty();
  $.ajax({
    url : host+"Attachment/list/"+TypePage+"/"+IDPage,
    type: "GET",
    dataType: "JSON",
    success: function(data)
    {
    	// console.log(data);
     	$.each(data, function (key,value) {
    	 	setImage(value);   
      	});
    },
    error: function (jqXHR, textStatus, errorThrown)
    {
        // alert('Error get data from ajax');
    }
  });
}

$("#form_attachment").submit(function( event ) {
  event.preventDefault();
  $('.form-group').removeClass('has-error'); // clear error class
  $('.help-block').empty();
  $("#save").text("Saving...");
  $("#save").attr("disable",true);
  $.ajax({
    url: host+"Attachment/save",
    type: "POST",
    data:  new FormData(this),
    mimeType:"multipart/form-data",
    contentType: false,
    cache: false,
    processData:false,
    dataType: "JSON",
    success: function(data_res)
    {
        // console.log(data_res);
        $.each(data_res,function(i,data){
          if(data.status) //if success close modal and reload ajax table
          {
            $("[name=caption]").val("");
          	$("#save").text("Save");
          	$("#save").attr("disable",false);
          	toastr.success("Saving data success","Information");
          	clear_img();
          	setImage(data);
          } 
          else {
              if(data.message){
                  swal({ html:true,type: "warning", title:'', text:data.message,});
              }
          }
        });
    },
    error: function (jqXHR, textStatus, errorThrown)
    {
      console.log(jqXHR.responseText);
      $("[name=caption]").val("");
      $("#save").text("Save");
      $("#save").attr("disable",false);
      clear_img();
      toastr.error("Error saving data, Size image too large or image format does not match","Information");
    }
  });
});

function clear_img(){
  var drEvent = $('.dropify').dropify();
  drEvent = drEvent.data('dropify');
  drEvent.resetPreview();
  drEvent.clearElement();
}

function setImage(data)
{
	AttachmentID = data.AttachmentID;
	ID 			 = data.ID;
	cek 		 = data.cek;
	url 		 = data.url_photo;
	url_file 	 = data.url_file;
	caption 	 = data.caption;

  	if(cek == 1){
    	checked = "checked=''";
    	status  = "main";
  	} else {
    	status  = "basic";
    	checked = "";
  	}

  	img = '<div class="col-sm-3" id="row-'+AttachmentID+'">';
  	img +='  <div class="div-image">';
  	// img +='<a href="javascript:void(0)" class="img-view" data-toggle="tooltip" data-placement="top" title="Edit data" target="_blank" onclick="edit('+AttachmentID+')">';
  	// img +='<i class="fa fa-pencil" aria-hidden="true"></i>';
  	// img +='</a>';

  	// type = $('[name=Type]').val();
  	// if(type == 6 || type == 5){
    img += '<input type="radio" id="cek'+AttachmentID+'" name="cek" value="ada" '+checked+' class="input-hidden" onclick="update_file('+AttachmentID+','+"'"+ID+"'"+')"/>';
    img += '<label for="cek'+AttachmentID+'" class="img-primary" data-toggle="tooltip" data-placement="top" title="Set Default Image">';
    img += '<i class="fa fa-image" aria-hidden="true"></i>';
    img += '</label>';
  	// }

  	img += '<a href="javascript:void(0)" class="img-remove" data-toggle="tooltip" data-placement="top" title="Delete Image" onclick="remove_file('+AttachmentID+',this)" data-status="'+status+'">';
  	img += '<i class="fa fa-trash" aria-hidden="true"></i>';
  	img += '</a>';
  
  	img += '<a href="'+url_file+'" target="_blank">';
  	img += '<img src="'+url+'" class="img-gallery-list">';
  	img += '<div class="form-group">';
  	// img +='<input type="text" name="photo" class="form-control" id="input-'+AttachmentID+'" placeholder="Photo caption" value="'+caption+'" onchange="update_file('+AttachmentID+')"/>';
  	img +='</div>';  
  	img += '</a>';

  	img +='</div>';
  	img +='</div>';
  	$("#attachment-list").prepend(img);
  	$('[data-toggle="tooltip"]').tooltip(); 
}

function update_file(AttachmentID,ID){
  attc    = $("#data").data("attc");
  caption = $("#input-" + AttachmentID).val();
  cek     = "tidak";
  if($("#cek"+AttachmentID).is(':checked')) {
    cek   = $("#cek"+AttachmentID).val(); 
  }

  data_post = {
      Type 			    : TypePage,
      AttachmentID 	: AttachmentID,
      ID 			      : ID,
      caption 		  : caption,
      attc 			    : attc,
      cek 			    : cek,
  };
  $.ajax({
    url : host+"Attachment/update/",
    type: "POST",
    data : data_post,
    dataType: "JSON",
    success: function(data)
    {
        toastr.success("Update data success","Information");
        get_what_expect();
    },
    // error: function (jqXHR, textStatus, errorThrown)
    // {
    //     toastr.error("Failed update data","Information");
    // }
  });
}

function remove_file(id,element){
  	status = $(element).data("status");

  	type = $('[name=Type]').val();
  	if(type == 6 || type == 5){

  	}else{
    	status = "bukan_main";
  	}

  	if(status == "main"){
    	toastr.error("Sorry this image cannot be delete","Information");
  	} else {
  		swal({   
        	title: "Are you sure ?",   
        	type: "warning",   
        	showCancelButton: true,   
        	confirmButtonColor: "#DD6B55",   
        	confirmButtonText: "Delete",   
        	cancelButtonText: "Cancel",   
        	closeOnConfirm: true,
        	closeOnCancel: false }, 
        	function(isConfirm){   
            	if (isConfirm) { 
            		$.ajax({
			      		url : host+"Attachment/delete/"+id,
			      		type: "POST",
			      		dataType: "JSON",
			      		success: function(data)
			      		{
				         	$("#row-"+id).remove();
				          	toastr.error("Deleteting data success","Information");
			      		},
			      		error: function (jqXHR, textStatus, errorThrown)
			      		{
				          	toastr.error("Failed deleting data","Information");
				          	get_what_expect();
			      		}
				    });
            	} 
            	else {
                	swal("Canceled", "", "error");   
            	} 
    	});
  	}

}