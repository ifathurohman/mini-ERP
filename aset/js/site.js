$(document).ready(function(){ 
	$('.SiteLogo').attr('src', general_app.SiteLogo);
	
	item_contact = '';
	if(general_app.Address){
		AddressTxt = general_app.Address;
		if(general_app.LinkAddresss){
			AddressTxt = '<a class="a-text-white" target="_blank" href="'+general_app.LinkAddresss+'">'+AddressTxt+'</a>';
		}
		item_contact += '<tr class="mb-20">\
                      <td class="vertical-top" ><i class="fas fa-map-marker-alt"></i></td>\
                      <td>'+AddressTxt+'</td>\
                    </tr>';
	}

	if(general_app.Email){
		EmailTxt = general_app.Email;
		item_contact += '<tr>\
                      <td  class="vertical-top"><i class="fa fa-envelope"></i></td>\
                      <td>'+language_app.lb_technical+' : <a href="mailto:'+EmailTxt+'" class="a-text-white" target="_blank" >'+EmailTxt+'</a></td>\
                    </tr>';
	}

	if(general_app.Telephone){
		TelephoneTxt = general_app.Telephone;
		TelephoneTxt_ = TelephoneTxt.replace(/[^0-9]/g, '');
		item_contact += '<tr>\
                      <td  class="vertical-top"><i class="fa fa-phone"></i></td>\
                      <td>'+language_app.lb_telephone+' : <a href="tel:'+TelephoneTxt_+'" class="a-text-white" target="_blank" >'+TelephoneTxt+'</a></td>\
                    </tr>';
	}

	if(general_app.Whatsapp1){
		WhatsappTxt = general_app.Whatsapp1;
		WhatsappTxt_ = WhatsappTxt.replace(/[^0-9]/g, '');
		item_contact += '<tr>\
                      <td  class="vertical-top"><i class="fab fa-whatsapp"></i></td>\
                      <td>Whatsapp : \
                      	<a href="https://api.whatsapp.com/send?phone='+WhatsappTxt_+'" class="a-text-white" target="_blank" >'+WhatsappTxt+'</a></td>\
                    </tr>';
        $('.Whatsapp1').append('<a target="_blank" href="https://api.whatsapp.com/send?phone='+WhatsappTxt_+'"><i class="fa fa-phone"></i> '+WhatsappTxt+'</a>');
	}

	if(general_app.Whatsapp2){
		WhatsappTxt = general_app.Whatsapp2;
		WhatsappTxt_ = WhatsappTxt.replace(/[^0-9]/g, '');
		item_contact += '<tr>\
                      <td  class="vertical-top"><i class="fab fa-whatsapp"></i></td>\
                      <td>Whatsapp : \
                      	<a href="https://api.whatsapp.com/send?phone='+WhatsappTxt_+'" class="a-text-white" target="_blank" >'+WhatsappTxt+'</a></td>\
                    </tr>';
        $('.Whatsapp2').append('<a target="_blank" href="https://api.whatsapp.com/send?phone='+WhatsappTxt_+'"><i class="fa fa-phone"></i> '+WhatsappTxt+'</a>');
	}

	if($('table').hasClass('front_contact')){
		$('.front_contact').append(item_contact);
	}
});