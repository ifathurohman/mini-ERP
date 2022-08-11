var mobile      = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
// var host        = window.location.origin+'/discovery/';
var host        = window.location.origin+'/';
var url         = window.location.href;

$(document).ready(function() {
    initialize();
    resetDataEdit();
});

// maps
var map,lat,lng,zoom,statusmap;
var markers = [];
var myCenter    = new google.maps.LatLng(-6.919105, 107.601868);
var myCenter_default = new google.maps.LatLng(-6.919105, 107.601868);
var marker      = new google.maps.Marker({
    position:myCenter
});
var infowindow;
var geocoder;
var zoom = 12;

var data_edit   = null;
var no_edit     = 0;
function initialize() {
    var mapProp = {
        center:myCenter,
        zoom: zoom,
        gestureHandling: 'greedy'
        // mapTypeId:google.maps.MapTypeId.ROADMAP
    };
    infowindow  = new google.maps.InfoWindow;
    geocoder    = new google.maps.Geocoder;
    map         = new google.maps.Map(document.getElementById("map"),mapProp);

    map.addListener('click', function(event) {
        myCenter = event.latLng;
        addMarker(myCenter);
        geocodeLatLng(myCenter,geocoder, map, infowindow);
    });
    
}

function resetMap(){
    myCenter = myCenter_default;
    resizingMap();
    deleteMarkers();
    resetMarkerInput();
}  

function resizeMap(lat="",lng="") {
   if(typeof map =="undefined") return;
   setTimeout( function(){
        resizingMap(lat,lng);
    } , 400);
}

function resizingMap(lat="",lng="") {
    if(typeof map =="undefined") return;
    statusmap = false;
    if(lat != "" && lng != ""){
        // deleteMarkers();

        myCenter = new google.maps.LatLng(lat, lng);
        marker   = new google.maps.Marker({
            position:myCenter
        });
        statusmap   = true;
        center      = myCenter;
        zoom        = 16;
    } else {
        // deleteMarkers();
        zoom        = 16;
        center      = myCenter;
    }

    google.maps.event.trigger(map, "resize");
    map.setCenter(center); 
    map.setZoom(zoom); 
    if(statusmap){
        addMarker(myCenter);
    }
}
function zoomMaps(lat="",lng="") {
    if(typeof map =="undefined") return;
    statusmap = false;
    if(lat != "" && lng != ""){
        // deleteMarkers();

        myCenter = new google.maps.LatLng(lat, lng);
        marker   = new google.maps.Marker({
            position:myCenter
        });
        statusmap   = true;
        center      = myCenter;
        zoom        = 14;
    } else {
        // deleteMarkers();
        zoom        = 14;
        center      = myCenter;
    }

    google.maps.event.trigger(map, "resize");
    map.setCenter(center); 
    map.setZoom(zoom); 
}
function setMapOnAll(map) {
    for (var i = 0; i < markers.length; i++) {
      markers[i].setMap(map);
    }
}
function clearMarkers() {
    setMapOnAll(null);
}
function deleteMarkers() {
    clearMarkers();
    markers = [];
}
function setMarkerinput(location) {
    $("#lat").val(location.lat());
    $("#lng").val(location.lng());
}
function resetMarkerInput(){
    $("#lat").val('');
    $("#lng").val('');
}
function addMarker(location) {
    deleteMarkers();
    marker = new google.maps.Marker({
      position: location,
      map: map,
      animation : google.maps.Animation.DROP,
      // draggable : true

    });
    markers.push(marker);
    setMarkerinput(location);
    marker.addListener('click', function() {
      infowindow.open(map, marker);
    });
}
function autocomplete()
{
    var input         = (document.getElementById('Address11'));
    var types         = document.getElementById('type-selector');
    var autocomplete  = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);
    autocomplete.addListener('place_changed', function() {
        infowindow.close();
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            window.alert("Lokasi tidak ditemukan");
            return;
        }
        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
            map.setZoom(15);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(13); // Why 17? Because it looks good.
        }
        addMarker(place.geometry.location);
        var address = '';
        if (place.address_components) {
            address = [
                (place.address_components[0] && place.address_components[0].short_name || ''),
                (place.address_components[1] && place.address_components[1].short_name || ''),
                (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
        }
        infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
        // infowindow.open(map, marker);
        // map.getUiSettings().setMyLocationButtonEnabled(true);
    });
    google.maps.event.addDomListener(window, 'load', initialize);
}
function geocodeLatLng(latlng,geocoder, map, infowindow) {
    geocoder.geocode({'location': latlng}, function(results, status) {
      if (status === 'OK') {
        if (results[0]) {
          // map.setZoom(11);
          addMarker(latlng);
          address = results[0].formatted_address;
          // $("[name=address]").val(address);
          infowindow.setContent(address);
          // infowindow.open(map, marker);
        } else {
          window.alert('No results found');
        }
      } else {
        window.alert('Geocoder failed due to: ' + status);
      }
    });
  }
// end maps

// location select 
$('[name=provinces]').change(function(){
    val = $(this).val();
    checkprovinces(val);
});
function checkprovinces(val){
    if(val != "none"){
        get_location(val,"regencies");
        if(no_edit != 1){
            get_geocoder2("provinces");
        }
    }else{
        resetlocation("provinces");
    }
}

$('[name=regencies]').change(function(){
    val = $(this).val();
    checkregencies(val);
});
function checkregencies(val){
    if(val != "none"){
        get_location(val,"districts");
        get_location(val,"area");
        if(no_edit != 1){
            get_geocoder2("regencies");
        }
    }else{
        resetlocation("regencies");
    }
}

$('[name=districts]').change(function(){
    val = $(this).val();
    checkdistricts(val);
});
function checkdistricts(val){
    if(val != "none"){
        get_location(val,"villages");
        if(no_edit != 1){
            get_geocoder2("districts");
        }
        
    }else{
        get_geocoder2("districts");
    }
}

$('[name=villages]').change(function(){
    val = $(this).val();
    checkvillages(val);
});
function checkvillages(val){
    if(val != "none"){
        if(no_edit != 1){
            get_geocoder("villages");
        }
    }else{
        resetlocation("villages");
    }
}

function get_location(id,page){

    if (page == "regencies") {
        url = host+"api/regencies_select";
        resetlocation("provinces");
    }
    else if(page == "districts"){
        url = host+"api/districts_select";
        resetlocation("regencies");
    }
    else if(page == "villages"){
        url = host+"api/villages_select";
        resetlocation("districts");
    }else if(page == "area"){
        url = host+"api/area_select";
        resetlocation('area');
    }


    data_post = {
        id : id,
    }
    $.ajax({
        url :url,
        type:"POST",
        data:data_post,
        dataType:"JSON",
        success: function(data)
        {
            if(page == "regencies"){
                $(".og-regencies").empty();
                $('#regencies').select2({data:null});
                if(data.regencies.length > 0){
                    $.each(data.regencies,function(i,v){
                      item = '<option value="'+v.ID+'">'+v.Name+'</option>';
                      $(".og-regencies").append(item);
                    });
                    // if(save_method == "update"){
                        if(data_edit.Regencies != null && no_edit == 1){
                            $('#regencies').select2().select2('val', [data_edit.Regencies]);
                        }
                        else if(regenciesID != 0){
                        	val = $('#regencies option').filter(function () { return $(this).html() == regenciesID.toUpperCase(); });
                            if(val){
                                $('#regencies').select2().select2('val', [val.val()]);    
                            }else{
                                $('#regencies').select2().select2('val', ['none']); 
                            }
                        	regenciesID = 0;
                        }
                        else{
                            no_edit = 0;
                            resetDataEdit();
                        }
                    // }
                }
            }
            else if(page == "districts"){
                $(".og-districts").empty();
                $('#districts').select2({data:null});
                if(data.districts.length > 0){
                    $.each(data.districts,function(i,v){
                      item = '<option value="'+v.ID+'">'+v.Name+'</option>';
                      $(".og-districts").append(item);
                    });
                    // if(save_method == "update"){
                        if(data_edit.Districts != null && no_edit == 1){
                            $('#districts').select2().select2('val', [data_edit.Districts]);
                        }
                    // }
                }
            }
            else if(page == "villages"){
                $(".og-villages").empty();
                $('#villages').select2({data:null});
                if(data.villages.length > 0){
                    $.each(data.villages,function(i,v){
                      item = '<option value="'+v.ID+'">'+v.Name+'</option>';
                      $(".og-villages").append(item);
                    });
                    // if(save_method == "update"){
                        if(data_edit.Villages != null && no_edit == 1){
                            $('#villages').select2().select2('val', [data_edit.Villages]);
                        }
                    // }
                    no_edit = 0;
                }
            }else if(page == "area"){
                $('.og-area').empty();
                $('#Area').select2({data:null});
                if(data.area.length > 0){
                    $.each(data.area,function(i,v){
                      item = '<option value="'+v.AreaID+'">'+v.Name+'</option>';
                      $(".og-area").append(item);
                    });
                    // if(save_method == "update"){
                        if(data_edit.AreaID != null && no_edit == 1){
                            $('#Area').select2().select2('val', [data_edit.AreaID]);
                        }
                    // }
                    no_edit = 0;
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            // swal('Error Edit  data');
        }
    });
}

$('#Address').on("change paste", function() {
    val = $(this).val();
    if(val != ''){
        provinces = $("#provinces option:selected").val();
        regencies = $("#regencies option:selected").val();
        districts = $("#districts option:selected").val();
        villages  = $("#villages option:selected").val();

        // if (provinces != "none" && regencies != "none" && districts != "none" && villages != "none") {
            get_geocoder("address");
        // }
    }
});

var regenciesID = 0;
function get_geocoder(page){  

    provinces = $("#provinces option:selected");
    regencies = $("#regencies option:selected");
    districts = $("#districts option:selected");
    villages  = $("#villages option:selected");
    address   = $('#Address').val();

	regenciesID = 0;
    txt_provinces = '';
    txt_regencies = '';

    if(provinces.val() != "none"){
        txt_provinces = provinces.text();
    }

    if(regencies.val() != "none"){
        txt_regencies = regencies.text()
    }

    // data = address+","+villages+","+districts+","+regencies+","+provinces;
    data = address+","+txt_regencies+","+txt_provinces+",Indonesia";

    geocoder.geocode( { 'address': data}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
        var latitude    = results[0].geometry.location.lat();
        var longitude   = results[0].geometry.location.lng();
        
        console.log(results);

        var txt_provinces = '';
        var txt_regencies = '';
        var int_provinces = 1;
        var int_regencies = 1;
        $.each(results[0].address_components,function(i,v){
            var txt_location = v.long_name;
            if(v.types[0] == "administrative_area_level_1"){ //province
                if(int_provinces == 1){
                    txt_provinces = txt_location;
                    int_provinces += 1;
                }
            }
            else if(v.types[0] == "administrative_area_level_2"){ // regencies
                if(int_regencies == 1){
                    txt_regencies  = txt_location;
                    int_regencies += 1;
                }
            }
        });

        if(page == "address"){
        	if(txt_regencies != ''){
	        	regenciesID = txt_regencies;
	        }

	        if(txt_provinces != ''){
	        	val = $('#provinces option').filter(function () { return $(this).html() == txt_provinces.toUpperCase(); });
	            $('#provinces').select2().select2('val', [val.val()]);
	        }
        }

        address = results[0].formatted_address;
        infowindow.setContent(address);
        resizeMap(latitude,longitude);

    } 
    });

}

function get_geocoder2(page){  

    provinces = $("#provinces option:selected");
    regencies = $("#regencies option:selected");
    districts = $("#districts option:selected");
    villages  = $("#villages option:selected");
    address   = $('#Address').val();

    txt_provinces = '';
    txt_regencies = '';

    if(provinces.val() != "none"){
        txt_provinces = provinces.text();
    }

    if(regencies.val() != "none"){
        txt_regencies = regencies.text()
    }

    // data = address+","+villages+","+districts+","+regencies+","+provinces;
    data = address+","+txt_regencies+","+txt_provinces+",Indonesia";

    geocoder.geocode( { 'address': data}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
        var latitude    = results[0].geometry.location.lat();
        var longitude   = results[0].geometry.location.lng();
        address = results[0].formatted_address;
        infowindow.setContent(address);
        resizeMap(latitude,longitude);
    } 
    });

}

function resetlocation(page,method){
    if (page == "provinces") {
        $(".og-regencies").empty();
        $('[name=regencies]').select2({data:null});

        $(".og-districts").empty();
        $('[name=districts]').select2({data:null});

        $(".og-villages").empty();
        $('[name=villages]').select2({data:null});
    }
    else if(page == "regencies"){
        $(".og-districts").empty();
        $('[name=districts]').select2({data:null});

        $('.og-area').empty();
        $('.new-area').empty();
        $('[name=Area]').select2({data:null});

        $(".og-villages").empty();
        $('#villages').select2({data:null});
    }
    else if(page == "districts"){
        $(".og-villages").empty();
        $('[name=villages]').select2({data:null});
    }

    if(method == "add"){
        $('#provinces').select2().select2('val', ['none']);
        resetDataEdit();
    }
}
function resetDataEdit(){
    var d = JSON.parse('{ "Regencies":"null", "Districts":"null", "Villages":"null", "AreaID":"null"}');
    data_edit = d;
    no_edit = 0;
}
// end location select