var mobile          = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host            = window.location.origin+'/';
var url             = window.location.href;
var page_login      = host + "main/login";
var page_register   = host + "main/register";
var save_method; //for save method string
var table;
var url_list    = host + "vendor/ajax_list/";
var url_edit    = host + "vendor/ajax_edit/";
var url_hapus   = host + "vendor/ajax_delete/";
var url_simpan  = host + "vendor/simpan/";
var url_update  = host + "vendor/ajax_update/";
var addressno   = 0;
var contactno   = 0;
var modul       = "";
var app         = "";
var radius_val  = 0;
var page_name;
var url_modul;
$(document).ready(function() {
    data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    //datatables
    filter_table();

    if(data_page.id){
        view_vendor(data_page.id);
    }
});
function filter_table(page){
    data_page   = $(".data-page, .page-data").data();
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    app         = data_page.app;
    url         = url_list+url_modul+"/"+modul;
    date_now    = data_page.date;

    fSearch             = $('#form-filter [name=fSearch]').val();
    fActive             = $('#form-filter [name=fActive]').val();
    fPosition           = $('#form-filter [name=fPosition]').val();
    // fTypeStatus         = $('#form-filter [name=fTypeStatus]').val();

    data_post = {
        Search              : fSearch,
        Active              : fActive,
        Position            : fPosition,
        // Type                : fTypeStatus,
    }
    table = $('#table').DataTable({
        "destroy"   : true,
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "searching": false, //Feature Search false
        "order": [], //Initial no order.
         "language": {                
            "infoFiltered": ""
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url"   : url,
            "type"  : "POST",
            "data"  : data_post,
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [{
            "targets": [0], //last column
            "orderable": false, //set not orderable
        },],
    });

    $("#add_address").click(function(){
        add_address();
    });
    $("#add_contact").click(function(){
        add_contact();
    });
}
function tambah(){
    modal_width();
    addressno = 0;
    contactno = 0;
    $(".address_v div").remove();
    $(".contact_v div").remove();
    $('.vaction').hide();
    add_address();
    add_contact();
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $("#form :input").prop("disabled", false);
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal('show'); // show bootstrap modal
    // $(".vaction").hide();
    $('.modal-title').text('Add New Business Partner'); // Set Title to Bootstrap modal title
    $('[name="crud"]').val("insert");
    $('#add_address, #add_contact').show();
    reset_button_action();
}
function edit(id)
{
    reset_button_action();
    modal_width();
    addressno = 0;
    contactno = 0;
    $(".address_v div").remove();
    $(".contact_v div").remove();
    save_method = 'update';
    $('#form')[0].reset();
    $("#form :input").prop("disabled", false);
    $('.form-group').removeClass('has-error');
    $('.help-block').empty(); // clear error string
    $('#add_address, #add_contact').show();
    reset_button_action();
    //Ajax Load data from ajax
    $.ajax({
        url : url_edit + id+"/"+modul,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            console.log(data);
            $("[name=vendorid]").val(data.vendorid);
            $("[name=code]").val(data.code);
            $('[name=code]').prop('disabled', true);
            $("[name=name]").val(data.name);
            $("[name=type]").prop("disabled",true);
            // $(".vaction").hide();
            $("[name=npwp]").val(data.npwp);
            $("[name=top]").val(data.top);
            $("[name=remark]").val(data.remark);
            $("[name=email]").val(data.email);
            $("[name=phone]").val(data.phone);
            $("[name=address]").val(data.address);
            $("[name=lat]").val(data.lat);
            $("[name=lng]").val(data.lng);
            $("[name=radius]").val(data.radius);
            $("[name=GroupName]").val(data.groupname);
            if(app == "pipesys"){
                if(data.position == 1){
                    $("#vendor").prop("checked",true);
                } else {
                    $("#customer").prop("checked",true);
                }
                list_address_row = data.list_address.length;
                list_contact_row = data.list_contact.length;
                if(list_address_row > 0){
                    $.each(data.list_address, function(i, v) {
                        data_address = {
                            address_code:v.address_code,
                            address:v.address,
                            city:v.city,
                            province:v.province,
                            invoice:v.invoice,
                            delivery:v.delivery,
                        }
                        add_address("edit",data_address);
                    });
                } else {
                    add_address();
                }
                if(list_contact_row > 0){
                    $.each(data.list_contact, function(i, v) {
                        data_contact = {
                            contact_code:v.contact_code,
                            phone:v.phone,
                            email:v.email,
                        }
                        add_contact("edit",data_contact);
                    });
                } else {
                    add_contact();
                }
            
            } else {
                set_radius();
                if(data.basecamp == 1){
                    $("#BaseCampY").prop("checked",true);
                } else {
                    $("#BaseCampN").prop("checked",true);
                }

                resizeMap(data.lat,data.lng);
            }

            $('#modal').modal("show");
            $('.modal-title').text('Edit Data');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);
        }
    });
}

function view_vendor(id,page)
{
    reset_button_action();
    action_print_button();
    modal_width();
    addressno = 0;
    contactno = 0;
    $(".address_v div").remove();
    $(".contact_v div").remove();
    // save_method = 'update';
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $("#form :input").prop("disabled", true);
    $('.help-block').empty(); // clear error string
    $('.vaction').show();
    $('#add_address, #add_contact').hide();
    //Ajax Load data from ajax
    $.ajax({
        url : url_edit + id+"/"+modul,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $("[name=vendorid]").val(data.vendorid);
            $("[name=code]").val(data.code);
            $("[name=name]").val(data.name);
            $("[name=npwp]").val(data.npwp);
            $("[name=top]").val(data.top);
            $("[name=remark]").val(data.remark);
            $("[name=email]").val(data.email);
            $("[name=phone]").val(data.phone);
            $("[name=address]").val(data.address);
            $("[name=lat]").val(data.lat);
            $("[name=lng]").val(data.lng);
            $("[name=radius]").val(data.radius);
            $("[name=GroupName]").val(data.groupname);
            if(app == "pipesys"){
                if(data.position == 1){
                    $("#vendor").prop("checked",true);
                } else {
                    $("#customer").prop("checked",true);
                }
                list_address_row = data.list_address.length;
                list_contact_row = data.list_contact.length;
                if(list_address_row > 0){
                    $.each(data.list_address, function(i, v) {
                        data_address = {
                            address_code:v.address_code,
                            address:v.address,
                            city:v.city,
                            province:v.province,
                            invoice:v.invoice,
                            delivery:v.delivery,
                        }
                        add_address("view",data_address);
                    });
                }
                if(list_contact_row > 0){
                    $.each(data.list_contact, function(i, v) {
                        data_contact = {
                            contact_code:v.contact_code,
                            phone:v.phone,
                            email:v.email,
                        }
                        add_contact("view",data_contact);
                    });
                }
            
            } else {
                set_radius();
                if(data.basecamp == 1){
                    $("#BaseCampY").prop("checked",true);
                } else {
                    $("#BaseCampN").prop("checked",true);
                }

                resizeMap(data.lat,data.lng);
            }

            $('#modal').modal("show");
            $('.modal-title').text('Vendor Detail');
            set_button_action(data);
            $('.open').removeClass('open');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);
        }
    });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}
function save()
{
    proses_save_button();

    var url;
    if(save_method == 'add') {
        url = url_simpan+modul;
    } else {
        url = url_update+modul;
    }

    form = $('#form').serializeArray();
    
    invoice     = $('.r-invoice');
    delivery    = $('.r-delivery');
    $.each(invoice,function(k,v){
        val = 0;
        if($(v).is(':checked')){
            val = 1;
        }
        form.push({name: 'r_invoice[]', value: val});
    });
    $.each(delivery,function(k,v){
        val = 0;
        if($(v).is(':checked')){
            val = 1;
        }
        form.push({name: 'r_delivery[]', value: val});
    });
    $("#form input").attr("disabled",false);
    $.ajax({
        url : url,
        type: "POST",
        data: form,
        dataType: "JSON",
        success: function(data)
        {
            console.log(data);
            if(data.status) //if success close modal and reload ajax table
            {
                swal('',data.message,'success');
                $('#modal').modal("hide");
                reload_table();
            }
            else{
              $('.form-group').removeClass('has-error'); // clear error class
              $('.help-block').empty();
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                    //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                    // swal('',data.error_string[i],'warning');
                }
                if(data.message){
                    swal('',data.message,'warning');
                }
            }
            success_save_button();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            success_save_button();
            console.log(jqXHR.responseText);
            
        }
    });
}
function hapus(id)
{
    swal({   title: "Are you sure?",   
             // text: "You will not be able to recover this data !",   
             type: "warning",   
             showCancelButton: true,   
             confirmButtonColor: "#DD6B55",   
             confirmButtonText: "Yes, delete it!",   
             cancelButtonText: "No, cancel it!",   
             closeOnConfirm: false,   
             closeOnCancel: false }, 
             function(isConfirm){   
                 if (isConfirm) { 
                    $.ajax({
                        url : url_hapus+id+"/nonactive",
                        type: "POST",
                        dataType: "JSON",
                        success: function(data)
                        {
                            //if success reload ajax table
                            reload_table();
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            swal('Error deleting data');
                        }
                    });
                    swal("Deleted!", "Your data has been deleted.", "success");   } 
                 else {
                     swal("Canceled", "Your data is safe :)", "error");   } 
    });
}
function active(id){
    $.ajax({
        url : url_hapus+id+"/active",
        type: "POST",
        dataType: "JSON",
        success: function(data){
            reload_table();
        },
        error: function (jqXHR, textStatus, errorThrown){
            swal('Error undeleting data');
        }
    });
}
function add_address(page ="",data_address="")
{
    addressno    += 1;
    address_code = "";
    city         = "";
    province     = "";
    address      = "";
    title        = "";
    checked      = "";
    checked2     = "";

    count_address = $('[name="address_code[]"]').length;
    if(count_address == 0){
        checked  = " checked ";
        checked2 = " checked ";
    }

    if(page == "edit" || page == "view"){
        address_code = data_address.address_code;
        city         = data_address.city;
        province     = data_address.province;
        address      = data_address.address;
        if(data_address.invoice == 1){checked = "checked";}else{checked = "";}
        if(data_address.delivery == 1){checked2 = "checked";}else{checked2 = "";}
    }

    type = $('[name=type]:checked').val();
    if(type == "customer"){
        title = "Delivery";
    }else{
        title = "Good Receipt";
    }

    str_disabled = '';
    if(page == "view"){
        str_disabled = ' disabled ';
    }

    item = '<div class="form-group col-sm-12">\
                <input type="hidden" name="address_code[]" value="'+address_code+'" '+str_disabled+'>\
                <label class="control-label">Address</label>\
                <input name="address[]" type="text" class="form-control" value="'+address+'"  '+str_disabled+'>\
                <span class="help-block"></span>\
            </div>\
            <div class="form-group col-sm-6">\
                <label class="control-label">City</label>\
                <input name="city[]" type="text" class="form-control" value="'+city+'"  '+str_disabled+'>\
                <span class="help-block"></span>\
            </div>\
            <div class="form-group col-sm-6">\
                <label class="control-label">Province</label>\
                <input name="province[]" type="text" class="form-control" value="'+province+'"  '+str_disabled+'>\
                <span class="help-block"></span>\
            </div>\
            <div class="form-group col-sm-12">\
                <label class="control-label block">Set Default Address</label>\
                <div class="radio-custom radio-primary radio-inline">\
                  <input type="radio" class="r-invoice" id="invoice-'+addressno+'" name="invoice[]" value="1" '+checked+'  '+str_disabled+'>\
                  <label for="invoice-'+addressno+'">Invoice</label>\
            </div>\
            <div class="radio-custom radio-primary radio-inline">\
                  <input type="radio" class="r-delivery" id="delivery-'+addressno+'" name="delivery[]" value="1" '+checked2+'  '+str_disabled+'>\
                  <label class="type_title" for="delivery-'+addressno+'">'+title+'</label>\
                </div>\
                <span class="help-block"></span>\
                <hr>\
            </div>';
    // item = "<div class='row'>"+item+"</div>";
    // item = item + '<div class="form-group col-sm-12"><hr></div>';
    $(".address_v").append(item);
}
function add_contact(page="",data_contact="")
{
    contactno       += 1;
    contact_code    = "";
    phone           = "";
    email           = "";
    if(page == "edit" || page == "view"){
        contact_code    = data_contact.contact_code;
        phone           = data_contact.phone;
        email           = data_contact.email;
    }

    str_disabled = '';
    if(page == "view"){
        str_disabled = ' disabled ';
    }

    item = ' <div class="form-group col-sm-6">\
                <input type="hidden" name="contact_code[]" value="'+contact_code+'" '+str_disabled+'>\
                <label class="control-label">Phone</label>\
                <input name="phone[]" type="text" class="form-control angka" value="'+phone+'" '+str_disabled+'>\
                <span class="help-block"></span>\
              </div>\
              <div class="form-group col-sm-6">\
                <label class="control-label">Email</label>\
                <input name="email[]" type="text" class="form-control" value="'+email+'" '+str_disabled+'>\
                <span class="help-block"></span>\
              </div>';
    $(".contact_v").append(item);
    angkaFormat();

}


var map;
var markers = [];
var lat;
var lng;
var zoom;



var myCenter;
var marker;
var zoom = 2;
var statusmap;
var infowindow;
var geocoder;
var cityCircle;

$(document).ready(function() {
    if(app == "salespro"){
        google.maps.event.addDomListener(window, 'load', initialize);
        google.maps.event.addDomListener(window, "resize", resizingMap());
    }
    $(".radius_val").text("0 Meter");
    $("[name=radius]").change(function(){
        set_radius();
    });
});
function initialize() {
    myCenter    = new google.maps.LatLng(20, -10);
    infowindow  = new google.maps.InfoWindow;
    geocoder    = new google.maps.Geocoder;
    marker      = new google.maps.Marker({
        position:myCenter
    });

    var mapProp     = {
        center:myCenter,
        zoom: zoom,
        // mapTypeId:google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map"),mapProp);

    cityCircle = new google.maps.Circle({
        strokeColor: '#FF0000',
        strokeOpacity: 0.8,
        strokeWeight: 1,
        fillColor: '#FF0000',
        fillOpacity: 0.35,
        map: map,
        center: myCenter,
        radius: Math.sqrt(0) * 100
      });

    map.addListener('click', function(event) {
        deleteMarkers();
        myCenter = event.latLng;
        addMarker(myCenter);
        geocodeLatLng(myCenter,geocoder, map, infowindow);
    });
    autocomplete();
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
        deleteMarkers();

        myCenter = new google.maps.LatLng(lat, lng);
        marker   = new google.maps.Marker({
            position:myCenter
        });
        statusmap   = true;
        center      = myCenter;
        zoom        = 15;
    } else {
        deleteMarkers();
        zoom        = 2;
        myCenter    = new google.maps.LatLng(10, -20);
        center      = map.getCenter();
    }

    google.maps.event.trigger(map, "resize");
    map.setCenter(center); 
    map.setZoom(zoom); 
    if(statusmap){
        addMarker(myCenter);
    }
}
function addMarker(location) {
    deleteMarkers();
    cityCircle.setMap(null);
    marker = new google.maps.Marker({
      position: location,
      map: map,
      animation : google.maps.Animation.DROP,
      // draggable : true

    });
    cityCircle = new google.maps.Circle({
        strokeColor: '#FF0000',
        strokeOpacity: 0.8,
        strokeWeight: 1,
        fillColor: '#FF0000',
        fillOpacity: 0.35,
        map: map,
        center: location,
        radius: radius_val
      });
    markers.push(marker);
    setMarkerinput(location);
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

function disabled(status)
{
    if(status){
        $(".disabled").attr("disabled",true);
    } else {
        $(".disabled").attr("disabled",false);
    }
}
function modal_width()
{
    if(app == "salespro"){
        if(mobile){
            $(".modal-dialog").css("width","92%");
        } else {
            $(".modal-dialog").css("width","60%");
        }
    }
}

function autocomplete()
{
    var input         = (document.getElementById('pac-input'));
    var types         = document.getElementById('type-selector');
    var autocomplete  = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);
    autocomplete.addListener('place_changed', function() {
        infowindow.close();
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
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
        infowindow.open(map, marker);
        // map.getUiSettings().setMyLocationButtonEnabled(true);
    });
}
function geocodeLatLng(latlng,geocoder, map, infowindow) {
    geocoder.geocode({'location': latlng}, function(results, status) {
      if (status === 'OK') {
        if (results[1]) {
          // map.setZoom(11);
          addMarker(latlng);
          address = results[1].formatted_address;
          $("[name=address]").val(address);
          infowindow.setContent(address);
          infowindow.open(map, marker);
        } else {
          window.alert('No results found');
        }
      } else {
        window.alert('Geocoder failed due to: ' + status);
      }
    });
  }
  function set_radius()
  {
    radius_val = $("[name=radius]").val();
    radius_val    = parseInt(radius_val);
    $(".radius_val").text(radius_val + " Meter");
    // radius_val = Math.sqrt(radius_val) * 50;
    // radius_val = Math.round(radius_val);
    cityCircle.setRadius(radius_val);
  }

$('[name=type]').on('click',function(){
    val     = $(this).val();
    title   = 'Good Receipt';
    if(val == "customer"){
        title = 'Delivery';
    }
    $('.type_title').text(title);
});


function modal_import()
{
    $(".dropify-clear").click(); 
    $('#modal-import').modal('show');
    $('.modal-title').text('Import Data');
}
function import_data()
{
    proses_save_button("next","btn-import");

    url = host+"vendor/import";
    var form = $('#form-import')[0]; // You need to use standard javascript object here
    var formData = new FormData(form);
    $.ajax({
    url : url,
    type: "POST",
    data: $('#form').serialize(),
    data:  formData,
    mimeType:"multipart/form-data",
    contentType: false,
    cache: false,
    processData:false,
    dataType: "JSON",
    success: function(data)
    {   
        if(data.hak_akses == "super_admin"){
            console.log(data);
        }
        if(data.status){ 
            $('#modal-import').modal('hide');
            // swal('success',data.message,'success');
            // reload_table();
            item_import_data(data);
        } else {
            swal('',data.message,'warning');  
        }
        success_save_button("next","btn-import");
    },
    error: function (jqXHR, textStatus, errorThrown){
        alert("import data error");
        success_save_button("next","btn-import");
        console.log(jqXHR.responseText);
    }
  });
}
function clear_img(){
  var drEvent = $('.dropify').dropify();
  drEvent = drEvent.data('dropify');
  drEvent.resetPreview();
  drEvent.clearElement();
}

function item_import_data(data){
    $('#modal-import-data').modal('show');
    $('#modal-import-data .modal-title').text("Import Data Detail");
    $('#modal-import-data .content-import').empty();
    $('#modal-import-data .div-loader').hide();
    if(data.data.length>0){
        item = '<table id="table-import-data" data-filename="'+data.inputFileName+'" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">';
            item += '<thead><tr>';
            item += '<th style="width:50px"></th>';
            total_column_header = data.header[0].length;
            total_failed  = 0;
            total_success = 0;
            $.each(data.header[0],function(k,v){
                item += '<th>'+v+'</th>';
            });
            item += '<th>Status</th>';
            item += '<th>Message</th>';
            item += '</tr></thead>';

            item += '<tbody>';
            $.each(data.data,function(k,v){
                checkbox_status = '';
                background      = 'bg-merah-pias';
                label_status    = 'Failed';
                status_data     = '';
                if(v.status){
                    total_success += 1;
                    checkbox_status = ' checked ';
                    background = '';
                    label_status    = '<hijau>Success</hijau>';
                    if(v.status_data == "insert"){
                        status_data = '<br><hijau>Insert</hijau>';
                    }else{
                        status_data = '<br><hijau>Update</hijau>';
                    }
                }else{
                    total_failed += 1;
                }

                checkbox = '<div class="checkbox-custom checkbox-primary">\
                  <input type="checkbox" '+checkbox_status+' disabled>\
                  <label style="color:white">.</label>\
                </div>';

                item += '<tr class="'+background+'">';
                item += '<td>'+checkbox+'</td>';
                item += '<td>'+v.Code+'</td>';
                item += '<td>'+v.Name+'</td>';
                item += '<td>'+v.Position+'</td>';
                item += '<td>'+v.Address+'</td>';
                item += '<td>'+v.City+'</td>';
                item += '<td>'+v.Province+'</td>';
                item += '<td>'+v.Phone+'</td>';
                item += '<td>'+v.Email+'</td>';
                item += '<td>'+v.Npwp+'</td>';
                item += '<td>'+v.Ap_max+'</td>';
                item += '<td>'+v.Groupname+'</td>';
                item += '<td>'+v.Remark+'</td>';
                item += '<td>'+label_status+status_data+'</td>';
                item += '<td>'+v.Message+'</td>';
                item += '</tr>';
            });
            item += '</tbody>';

        item += '</table>';

        item_total = '<span>Total Data : '+data.data.length+', '+total_success+' Success, '+total_failed+' Fail'+'</span>';
        $('#modal-import-data .content-import').append(item_total);
        $('#modal-import-data .content-import').append(item);
        $('#modal-import-data #table-import-data').DataTable({
            "destroy"   : true,
        });
    }
    else{
        item = '<center><h4>Data Not Found</h4></center>';
        $('#modal-import-data .content-import').append(item);
    }
}

// 20190716 MW
// save import
function save_import(){
    tag_data = $('#modal-import-data #table-import-data').data();
    if(tag_data.filename){
        proses_save_button('import','btn-import-data');
        data_post = {filename : tag_data.filename};
        $.ajax({
            url : host+"vendor/save_import",
            type: "POST",
            data : data_post,
            dataType: "JSON",
            success: function(data){
                if(data.hakakses == "super_admin"){
                    console.log(data);
                }
                if(data.status){
                    $('#modal-import-data').modal('hide');
                    swal('',data.message, 'success');
                    reload_table();
                }else{
                    swal('',data.message, 'warning');
                }
                success_save_button('import','btn-import-data');
            },
            error: function (jqXHR, textStatus, errorThrown){
                alert('Error adding / update data');
                success_save_button('import','btn-import-data');
                console.log(jqXHR.responseText);
            }
        });
    }else{
        swal('','File not found, please reupload import file','warning');
    }
}
