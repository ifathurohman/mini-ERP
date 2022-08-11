var mobile  = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host    = window.location.origin+'/pipesys_qa/';
var url     = window.location.href;
$(document).ready(function() { 
  // if(url == host+"penerimaan"){
    autocomplete(".autocomplete_vendor");
  // }
});
function autocomplete(classnya)
{
    $(classnya).autocomplete({
      minLength:1,
      delay:0,
      max:10,
      scroll:true,
      source: function(request, response) {
          $.ajax({ 
              url: host + "api/autocomplete_vendor",
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