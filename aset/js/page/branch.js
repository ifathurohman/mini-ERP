var mobile  = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host    = window.location.origin+'/pipesys_qa/';
var url     = window.location.href;
var page_login      = host + "main/login";
var page_register   = host + "main/register";
var save_method; //for save method string
var table;
var url_list    = host + "branch/ajax_list/";
var url_edit    = host + "branch/ajax_edit/";
var url_hapus   = host + "branch/ajax_delete/";
var url_simpan  = host + "branch/simpan";
var url_update  = host + "branch/ajax_update";
var page_data;
var hakakses;
var page_name;
var url_modul;
var modul;

$(document).ready(function() {
    page_data   = $(".page-data").data();
    hakakses    = page_data.hakakses;
    data_page   = $(".data-page, .page-data").data();
    page_name   = data_page.page_name;
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    title_page  = data_page.title;
    url_list    = url_list+url_modul+"/"+modul;
    if(modul == "branch"){
        modal_width();
    }
    table = $('#table').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "ajax": {
            "url": url_list,
            "type": "POST"
        },
        "columnDefs": [{
            "targets": [ 0 ], //last column
            "orderable": false, //set not orderable
        },],
    });
});


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
    if(mobile){
        $(".modal-dialog").css("width","92%");
    } else {
        $(".modal-dialog").css("width","80%");
    }
}
function tambah()
{

    $(".view-form-sales").show();
    $(".view-form-voucher").hide();
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('#form input, #form select').attr('disabled', false);
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal('show'); // show bootstrap modal
    if (modul == "sales") {
        modulTitle = "Employee";
    }else{
        modulTitle = title_page
    }
    disabled(true);
    $('.modal-title').text(language_app.lb_add_new+' '+modulTitle); // Set Title to Bootstrap modal title
    $('[name="crud"]').val("insert");
    reset_button_action();
    //-----------------------------------------------------------------------------
    if(modul == "branch"){
        resizeMap();
    }

}
function edit(id,page)
{

    data_page   = $(".data-page, .page-data").data();
    page_name   = data_page.page_name;
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    
    $('#form input, #form select').attr('disabled', false);
    disabled(true);
    if(modul == "active"){
        save_method = 'active';
        modal_title = "Activation Device";
        $(".view-form-sales").hide();
        $(".view-form-voucher").show();

    } else {
        save_method = 'update';
        modal_title = language_app.btn_edit +" "+title_page;
        $(".view-form-sales").show();
        $(".view-form-voucher").hide();
    }
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    reset_button_action();
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(json_response)
        {
            data = json_response.data;
            if(hakakses == "super_admin"){
                console.log(data);
            }
            $('label').addClass('active');
            $('.validate').addClass('valid');
            $('[name="crud"]').val("update");
            $('[name="BranchID"]').val(data.BranchID);
            $('[name="Name"]').val(data.Name);
            $('[name="Address"]').val(data.Address);
            $('[name="City"]').val(data.City);
            $('[name="Province"]').val(data.Province);
            $('[name="Country"]').val(data.Country);
            $('[name="Postal"]').val(data.Postal);
            $('[name="Phone"]').val(data.Phone);
            $('[name="Fax"]').val(data.Fax);
            $('[name="Lat"]').val(data.Lat);
            $('[name="Lng"]').val(data.Lng);
            $('[name="Email"]').val(data.Email);
            $('[name="FirstName"]').val(data.FirstName);
            $('[name="LastName"]').val(data.LastName);


            if(modul == "branch"){
                resizeMap(data.Lat,data.Lng);
            }

            if(page == "view"){
                save_method = "view";
                action_print_button();
                $('#form input, #form select').attr('disabled', true);
                set_button_action(json_response);
                modal_title = page_name+' Detail';
            }

            $('#modal').modal("show");
            $('.modal-title').text(modal_title);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}
 
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}
 
function save()
{
    disabled(false);

    proses_save_button();
    
    var url;
    if(save_method == 'active') {
        url = host+"branch/active_account";
    } else if(save_method == 'add') {
        url = url_simpan;
    } else {
        url = url_update;
    }
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {
            disabled(true);
            console.log(data);
            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal').modal("hide");
                reload_table();
                if(save_method == "active"){
                    swal('','activation account success','success');
                }else{
                    swal('','Success', 'success');
                }

            }
            else
            {
        
                $('.form-group').removeClass('has-error'); // clear error class
                $('.help-block').empty();
                if(data.message){
                    swal('',data.message,'warning');
                }
                if(data.inputerror){
                    for (var i = 0; i < data.inputerror.length; i++)
                    {
                        $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                        //select parent twice to select div form-group class and add has-error class
                        $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                        // swal('',data.error_string[i],'warning');
                    }                    
                }


            }
            success_save_button();

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            disabled(true);

            alert('Error adding / update data');
            success_save_button();
            console.log(jqXHR.responseText);

        }
    });
}
function hapus(id)
{
    swal({   title: language_app.lb_ask,
             // text: "You will not be able to recover this data !",   
             type: "warning",   
             showCancelButton: true,   
             confirmButtonColor: "#DD6B55",   
             confirmButtonText: language_app.lb_deleted,   
             cancelButtonText: language_app.lb_canceled1,   
             closeOnConfirm: false,   
             closeOnCancel: false }, 
             function(isConfirm){   
                 if (isConfirm) { 
                    $.ajax({
                        url : url_hapus+id,
                        type: "POST",
                        dataType: "JSON",
                        success: function(data)
                        {
                            //if success reload ajax table
                            if(data.status){
                                reload_table();
                                swal('',language_app.lb_success,'success');
                                $('#modal').modal('hide');
                            }else{
                                swal('',data.message,'warning');
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            swal('Error deleting data');
                            console.log(jqXHR.responseText);
                        }
                    });
                } 
                 else {
                     swal(language_app.lb_canceled, "error");   } 
    });
}
function active(id,status = ""){
    $.ajax({
        url : url_hapus+id+"/active",
        type: "POST",
        dataType: "JSON",
        success: function(data){
            if(data.status){
                reload_table();
                swal('',language_app.lb_success,'success');
                $('#modal').modal('hide');
            }else{
                swal('',data.message,'error');       
            }
        },
        error: function (jqXHR, textStatus, errorThrown){
            swal('Error undeleting data');
        }
    });
}
function generate_token(id,status)
{
    $.ajax({
        url : host+"branch/generate_token/" + id+"/"+status,
        type: "GET",
        dataType: "JSON",
        success: function(data){
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            if(data.status){
                reload_table();
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            console.log("error generate token");
        }
    });
}
function unlink(id){
    $.ajax({
        url : host+"branch/unlink/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            if(data.status){
                reload_table();
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            console.log("error generate token");
        }
    });
}
// --------------------------------------------------------------------------------------------------------------------------------------------------------------
var map;
var markers = [];
var lat;
var lng;
var zoom;
var myCenter;
var marker;
var zoom = 2;
var statusmap;
$(document).ready(function() { 
    if(modul == "branch"){
        myCenter    = new google.maps.LatLng(20, -10);
        marker      = new google.maps.Marker({
            position:myCenter
        });
        google.maps.event.addDomListener(window, 'load', initialize);
        google.maps.event.addDomListener(window, "resize", resizingMap());
    }
});
function initialize() {
    var mapProp = {
        center:myCenter,
        zoom: zoom,
        // mapTypeId:google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map"),mapProp);
    map.addListener('click', function(event) {
        if(save_method != "view"){
            myCenter = event.latLng; 
            addMarker(myCenter);
            setMarkerinput(myCenter);
        }
    });
}
function resizeMap(lat="",lng="") {
   if(typeof map =="undefined") return;
   setTimeout( function(){
        resizingMap(lat,lng);
    } , 400);
}
function resizingMap(lat="",lng="") {
    console.log(lat);
    if(typeof map =="undefined") return;
    statusmap = false;
    if(lat != "" && lng != ""){
        deleteMarkers();

        myCenter = new google.maps.LatLng(lat, lng);
        addMarker(myCenter);
        statusmap   = true;
        center      = myCenter;
        zoom        = 12;
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
    var marker = new google.maps.Marker({
      position: location,
      map: map,
      animation : google.maps.Animation.DROP,
      draggable : true

    });
    markers.push(marker);
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
    $("#Lat").val(location.lat());
    $("#Lng").val(location.lng());
}