var mobile      = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));
var host        = window.location.origin + '/';
$(document).ready(function() {
  get_list();
  get_list("list");
});

function get_list(page="",pagenum=""){
	if(page == ""){
      page = "sum";
    }
	$.ajax({
        url: host + "content/blog_list/"+pagenum,
        data:{
          page:page,
          pagenum:pagenum,
        },
        type: "POST",
        dataType: "JSON",
        success: function(data) {
          if(data.hakakses == "super_admin"){
          	console.clear();
          }else{
          	console.clear();
          }
          //ini untuk mendapatkan total data untuk pagingnya pagingnya
          $("#item-list").text('');
          if(page == "sum"){
            total_data = data.total_data;
            total_page = Math.ceil(total_data / 6);
            $('#page-selection').bootpag({
              total: total_page,
              // page: 3,
              maxVisible: 10
            }).on("page", function(event,num){
                $('html, body').animate({scrollTop: $("#item-list").offset().top -120}, 'slow');
                get_list("list",num);
            });
          }

          if(data.list_data.length > 0){
          	var no  		= 1;
          	$.each(data.list_data,function(i,v){
          		var full_width	= '';
          		var right 		= '';
          		var img 		= '';
          		if(no == 1 || no == 5){
          			full_width = 'full-width ';
          			if(no == 5){
          				right = 'right';
          			}
          		}
          		no += 1; 
           		item = '<div class="col-sm-6 box-list-1 '+full_width+right+'">';
           			item += '<a href="'+v.Link+'">';
	           			item += '<div>';
	           				item += '<img src="'+v.Image+'">';
	           				item += '<div class="description">';
	           					item += '<span class="title">'+v.Name+'</span><br>';
	           					item += '<span>'+v.Date+'</span>';
	           				item += '</div>';
	           			item += '</div>';
	           		item += '</a>';
           		item += '</div>';
           		$("#item-list").append(item);
           	});
          }       
        },
        error: function(jqXHR, textStatus, errorThrown) {
        	console.log("error data ");
        }
    });
}