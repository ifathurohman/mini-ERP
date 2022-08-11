// author muhammad iqbal ramadhan
// kalau mau tanya silahkan
// IG : akang_ramadhan
// telp: 089621882292
// email : iqbalzt.ramadhan@gmail.com
// job : web programmer dan android programmer
var mobile      = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));
var host        = window.location.origin + '/';
var NewsID;
var category,limit_post;
var Search;
$(document).ready(function() {
	page_data   = $(".page-data").data();
  category    = page_data.category;
	limit_post  = page_data.limit_post;
	get_news("sum");
	get_news("list");
	$("[name=search_product]").keyup(function(){
	Search = $(this).val();
		get_news("search");
	});
});
function list(v){
  if(category == "news"){
    item = '<div class="col-sm-4">';
    item += '<div class="item-2 bg-white bg-shadow">';
    item += '<div class="image">';
    item += '<img src="'+v.Image+'">';
    item += '</div>';
    item += '<div class="content">';
    item += '<b class="title" title="'+v.Name+'">'+v.Name+'</b>';
    item += '<div class="date" title="'+v.Author+'">'+v.Author+'</div>';
    item += '<div class="summary">';
    item += '<p class="text">'+v.Summary+'</p>';
    item += '</div>';
    item += '<div class="link text-right"><a href="'+v.Link+'" class="blue" title="'+v.Name+'" target="_blank">Read More</a></div>';
    item += '</div>';
    item += '</div>';
    item += '</div>';
  } else {  
    item = '<div class="col-sm-6">'
    item += '<div class="item bg-white bg-shadow item-news">';
    item += '<div class="image">';
    item += '<img src="'+v.Image+'">';
    item += '</div>';
    item += '<div class="content">';
    item += '<b class="title" title="'+v.Name+'">'+v.Name+'</b>';
    item += '<div class="date" title="'+v.Author+'">'+v.Author+'</div>';
    item += '<div class="summary">';
    item += '<p class="text">'+v.Summary+'</p>';
    item += '</div>';
    item += '<div class="link text-right"><a href="'+v.Link+'" class="blue" title="'+v.Name+'" target="_blank">Read More</a></div>';
    item += '</div>';
    item += '</div>';
    item += '</div>';
  }

  $("#list-post").append(item);
	$("#list-post.loading-effect").hide();
}
function get_news(page,pagenum) {
    if(page == ""){
      page = "sum";
    } else if(page == "search"){
      page = "search";
    }
    $.ajax({
        url: host + "api/content_list/"+pagenum,
        data:{
          category:category,
          page:page,
          pagenum:pagenum,
          Search:Search
        },
        type: "POST",
        dataType: "JSON",
        success: function(data) {
	    		console.log(data);
          if(data.HakAkses){
	    	}
          //ini untuk mendapatkan total data untuk pagingnya pagingnya
          if(page == "sum"){
            total_data = data.total_data;
            total_page = Math.ceil(total_data / limit_post);
            $('#page-selection').bootpag({
              total: total_page,
              // page: 3,
              maxVisible: 5
            }).on("page", function(event,num){
          			$("#list-post.loading-effect").show();
                if(mobile){
                  $('html, body').animate({scrollTop: $("#list-post").offset().top -200}, 'slow');
                } else {
                  $('html, body').animate({scrollTop: $("#list-post").offset().top -300}, 'slow');
                }
                $("#list-post .item, #list-post .item-2").remove();
                get_news("list",num);
            });
          }
          if(page == "search"){
            $(".item-news").remove();
          }
          //ini untuk mendapatkan list datanya
          if(page == "list" || page == "search"){
            if(data.list_data.length > 0){
              $.each(data.list_data,function(i,v){  
				        list(v);
              });

            } else {
                item = '<div class="col-sm-12 item-news data-notfound"><center>Data not found</center></div>';
                $("#list-post").append(item);
				        $("#list-post.loading-effect").hide();
            }
          } else {
          	

          }
          
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.log(jqXHR.responseText);
          alert('Please Try Again');
        }
    });
}