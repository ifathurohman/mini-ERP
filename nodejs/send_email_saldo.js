var requestLoop1 = setInterval(function(){
  var request 	 = require("request");
  var url 		 = "http://qa.pipesys.rcelectronic.co.id/api/tes";
	request({
	  uri: url,
	  method: "GET",
	  timeout: 0,
	  followRedirect: true,
	  maxRedirects: 1000000
	}, function(error, response, body) {
	  console.log(body);
	});
}, 30000);
