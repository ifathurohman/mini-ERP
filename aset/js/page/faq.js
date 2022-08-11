var mobile      = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));
var host        = window.location.origin + '/';
$(document).ready(function() {
  check_page_asside();
  get_list("General");
});

var page_aside_switch = false;
$('.page-aside-switch').click(function(){
  check_page_asside();
});

function check_page_asside(){
  if(page_aside_switch == false){
    $('.page-aside').addClass("open");
    page_aside_switch = true;
  }else{
    $('.page-aside').removeClass("open");
    page_aside_switch = false;
  }
}

$('.list-group-item').click(function(){
	$('.list-group-item').removeClass("active");
	$(this).addClass("active");
	page = $(this).text();
	$('.title').text(page);
	get_list(page);
});

function get_list(page){
  $('.panel-group').text('');
  if(page == "General" || page == "Website"){
    
  }else{
    page = "Android";
  }
  loading_page();
	$.ajax({
        url: host + "content/faq_list",
        data:{
          page:page,
        },
        type: "POST",
        dataType: "JSON",
        success: function(data) {
          if(data.hakakses == "super_admin"){
          	console.log(data);
          }else{
          	console.clear();
          }
          if(data.list_data.length > 0){
          	no = 1;
          	$.each(data.list_data,function(i,v){
          		item = '<div class="panel">';
                  item += '<div class="panel-heading" id="question-'+no+'" role="tab">';
                    item += '<a class="panel-title" style="padding: 15px 15px 15px 0px !important;font-weight: 500;" aria-controls="answer-'+no+'" aria-expanded="false" data-toggle="collapse" href="#answer-'+no+'" data-parent="#accordion">';
                    	item += v.Name;
                    item += '</a>';
                  item += '</div>';
                  item += '<div class="panel-collapse collapse" id="answer-'+no+'" aria-labelledby="question-'+no+'" role="tabpanel">';
                    item += '<div class="panel-body">'
                      item += v.Description;
                    item += '</div>'
                  item += '</div>'
                item += '</div>';
           		$(".panel-group").append(item);
           		no += 1;
           	});
          }
          dismiss_loading_page();

        },
        error: function(jqXHR, textStatus, errorThrown) {
        	console.log("error data ");
          dismiss_loading_page();
        }
    });
}