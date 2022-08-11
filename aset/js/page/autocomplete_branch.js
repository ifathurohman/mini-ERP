var mobile  = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host    = window.location.origin+'/pipesys_qa/';
var url     = window.location.href;
$(document).ready(function() { 
  if(url == host+"mutasi"){
    autocomplete(".autocomplete_branch1");
    autocomplete(".autocomplete_branch2");    
  }
});
function autocomplete(classnya)
{
    $(classnya).autocomplete({
      minLength:2,
      delay:0,
      max:10,
      scroll:true,
      source: function(request, response) {
          $.ajax({ 
              url: host + "api/autocomplete_branch",
              data: { search: $(classnya).val()},
              dataType: "json",
              type: "POST",
              success: function(data){
                  response(data);
              }    
          });
      },
      select:function(event, ui){
          productid = ui.item.productid;  
      }
    });
}