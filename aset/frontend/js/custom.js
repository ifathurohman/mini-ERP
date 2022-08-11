// author muhammad iqbal ramadhan
// kalau mau tanya silahkan
// IG : iqbal_raamadhan
// telp: 089621882292
// email : iqbalzt.ramadhan@gmail.com
// job : web programmer dan android programmer  

var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));
var host        = window.location.origin+'/pipesys_qa/';
var url = window.location.href;
var index_page;
var id_page;
var modul_page;
$(document).ready(function() {
	dt 			= $(".page-data").data();
	dt2 		= $(".data-page").data();
	index_page 	= dt.index;
	if(dt2){
		modul_page  = dt2.modul;
		id_page 	= dt2.id;
	}
	if(window.location.hash) {
	 	goToByScroll(window.location.hash);
	}
   link_about_us();
   init_page();

    $(window).scroll(function() {
    	if(mobile){
    		if($(this).scrollTop() > 200) { 
			  $('.main-menu').removeClass('bg-transparant');
			} else {
			  $('.main-menu').addClass('bg-transparant');
			}
    	} else {	
			if($(this).scrollTop() > 700) { 
			  $('.main-menu').removeClass('bg-transparant');
			} else {
			  $('.main-menu').addClass('bg-transparant');
			}
    	}
    });

});
$(document).on('click', 'a[href^="#"]', function(e) {
    // target element id
    var id = $(this).attr('href');
    // target element
    var $id = $(id);
    if ($id.length === 0) {
        return;
    }
    // prevent standard hash navigation (avoid blinking in IE)
    e.preventDefault();
    // top position relative to the document
    var pos = $id.offset().top - 50; 
    // animated top scrolling
    $('body, html').animate({scrollTop: pos});
});



function link_about_us(){
	if($("div,span").hasClass('aos-init')){
   		AOS.init();
	}
	if($("div,nav").hasClass("main-menu")){
		$(".main-menu .nav-link").click(function(){
			$(".main-menu .nav-link").removeClass('active');
			$(this).addClass('active');
		});
	}

	$(".dropdown-toggle").click(function(){
		$.each($(".row-dropdown .dropdown-content"),function(i,v){
			if(i == 0){
				$(v).addClass("active");
			}
		});
	});
	$(".row-dropdown .dropdown-main a").hover(function(){
		a 	= $(this);
		dt 	= a.data();
		obj = a[0];
		$(".dropdown-content").removeClass("active");
	});

	if(mobile){
		$(".nav-search").css("width","100%");
	} else {
		if(index_page == "frontend"){    
		  $(".navbar-menu-atas .dropdown-mega a").removeClass("dropdown-toggle");
		  $(".navbar-menu-atas .dropdown-mega a").attr("data-toggle","");
		  $('.navbar-menu-atas .dropdown, .navbar-menu-atas .dropdown-menu, .nav-item.dropdown').hover(
		       function(){ $(this).addClass('show'); $(".nav-item .dropdown-menu").addClass("show"); },
		       function(){ $(this).removeClass('show'); $(".nav-item .dropdown-menu").removeClass('show'); }
		  );
		}
	}



	
}
var TotalData = 0;
var Start 	  = 0;
var Limit 	  = 0;
var ix 		  = 0;

function goToByScroll(id){
	// if(mobile){
	//     $('html,body').animate({scrollTop: $(id).offset().top - 50},'slow');
	// } else {
	//     $('html,body').animate({scrollTop: $(id).offset().top - 50},'slow');
	// }
}
$(function(){
    var current = location.pathname;
    $('.navbar-nav li a').each(function(){
        var $this = $(this);
        // console.log($this.attr('href'));
        // if the current path is like this link, make it active
        // if($this.attr('href').indexOf(current) !== -1){
        if($this.attr('href') == url){
            $this.addClass('active');
        }
    });

    $(".side-menu .list-link a").each(function(){
        var $this = $(this);
        if($this.attr('href') == url){
            $this.parent().parent().parent().addClass('show');
        }
    });
});

function init_page(){
	if(modul_page == "list-product"){
		choose_category(id_page,'index');
	}
}

function choose_category(e,modul){
	$(".loading-item").show();
	$(".list-product-data").empty();
	if(modul == "index"){
		CategoryID = e;
	} else {
		$(".list-category .item").removeClass("active");
		$(e).children().addClass("active");
		dt = $(e).data();
		CategoryID = dt.id;
		window.history.pushState('page2', 'Title', host+'product/list/'+CategoryID);
	}

	$.ajax({
        url : host + "api/product_list/"+CategoryID,
        type: "POST",
        dataType: "JSON",
        data: '',
        success: function(json){
          if(json.Status){
          	$.each(json.ListData,function(i,v){
	          	item = '<div class="col-sm-6 col-xs-6 aos-init" data-aos="fade-down">\
					<a href="'+v.Link+'">\
						<div class="item">\
							<div class="left">\
								<p class="title">'+v.Name+'</p>\
								<p class="text">'+v.Summary+'</p>\
								<button class="btn btn-white link-more">Learn More</button>\
							</div>\
							<div class="right">\
								<img src="'+v.Image+'" class="img" title="'+v.Name+'">\
							</div>\
						</div>\
					</a>\
				</div>';
				$(".list-product-data").append(item);
          	});

          } else {
          	item = '<div class="col-sm-12 aos-init" data-aos="fade-down">\
          		<div class="empty">\
          			<img scr="'+host+'aset/img/not-found.png" class="img">\
          			<p class="title">Oops, product not found :( </p>\
					<p class="text">No result found. Try another category?</p>\
          		</div>\
          	</div>'; 
			$(".list-product-data").append(item);

          }
	  	  $(".loading-item").hide();
        },
        error: function (jqXHR, textStatus, errorThrown){
            console.log(jqXHR.responseText);
        }
    });
}