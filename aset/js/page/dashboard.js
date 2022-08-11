var mobile  = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host    = window.location.origin+'/pipesys_qa/';
var url     = window.location.href;
var map;
var markers = [];
var lat;
var lng;
var zoom;
var myCenter    = new google.maps.LatLng(20, -10);
var marker      = new google.maps.Marker({
    position:myCenter
});
var zoom = 2;
var statusmap;
var app;
var url_post;
var currentdate;
var startdate;
var period = "";
var chart_top_sales_branch,chart_category,chart_outstanding,chart_sales_hour,
    chart_sales_store,chart_omset_cost,chart_sales_city,chart_sales_customer,chart_sales_open,chart_sales_overdude,chart_sales_payment,
    chart_purchase_order,chart_purchase_transaction,chart_purchase_return,
    chart_purchase_open,chart_purchase_overdude,chart_purchase_payment,chart_stock_branch,
    chart_store_receivable,chart_balance_sheet,chart_top_customer,chart_loss_profit;
$(window).load(function(){

    page_data   = $(".page-data").data();
    app         = page_data.app;
    // currentdate = page_data.currentdate;
    // startdate   = page_data.startdate;
    ar          = page_data.ar;
    ap          = page_data.ap;
    ac          = page_data.ac;
    inventory   = page_data.inventory;

    //selling
    selected_item('#ul-sales-hour', st_period_type,'non');
    selected_item('#ul-sales-store', st_period_type,'non');
    selected_item('#ul-sales-cost', st_period_type,'non');
    selected_item('#ul-sales-customer', st_period_type,'non');
    selected_item('#ul-sales-open', st_period_type,'non');
    selected_item('#ul-sales-overdude', st_period_type,'non');
    selected_item('#ul-sales-payment', st_period_type,'non');
    selected_item('#ul-outstanding', st_period_type,'non');
    selected_item('#ul-sales_city', st_period_type, 'non');

    // purchase
    selected_item('#ul-purchase-order', st_period_type,'non');
    selected_item('#ul-purchase-transaction', st_period_type,'non');
    selected_item('#ul-purchase-return', st_period_type,'non');
    selected_item('#ul-purchase-open', st_period_type,'non');
    selected_item('#ul-purchase-overdude', st_period_type,'non');
    selected_item('#ul-purchase-payment', st_period_type,'non');

    // accounting
    selected_item("#ul-store_receivable", st_period_type,'non');
    selected_item("#ul-loss-profit", st_period_type,'non');
    selected_item("#ul-balance_sheet", st_period_type,'non');

    create_chart();

    // $("[name=StartDate]").val(startdate);
    // $("[name=EndDate]").val(currentdate);
    load_data("expire");
    load_data("sellheader");
    load_data("purchaseheader");
    load_data("inventory");
    load_data("accounting");
    $("[name=Check]").change(function(){
        load_data("CheckStatus");
    });

    if(ar<=0){
        $('.var').hide();
    }
    if(ap<=0){
        $('.vap').hide();
    }
    if(ac<=0){
        $('.vac').hide();
    }
    if(inventory<=0){
        $('.vinventory').hide();
    }
});

function create_chart(){
    var ctx = document.getElementById("sales_category");
    chart_category = new Chart(ctx, {});
    
    // var ctx = document.getElementById("sales_branch");
    // chart_top_sales_branch = new Chart(ctx, {});
    
    var ctx = document.getElementById("outstanding_delivery");
    chart_outstanding = new Chart(ctx,{});

    // var ctx = document.getElementById("sales_hour");
    // chart_sales_hour = new Chart(ctx,{});

    // var ctx = document.getElementById("sales_store");
    // chart_sales_store = new Chart(ctx,{});

    var ctx = document.getElementById("omset_cost");
    chart_omset_cost = new Chart(ctx,{});

    var ctx = document.getElementById("sales_city");
    chart_sales_city = new Chart(ctx,{});

    // var ctx = document.getElementById("total_sales_customer");
    // chart_sales_customer = new Chart(ctx,{});
    
    var ctx = document.getElementById("sales_open");
    chart_sales_open = new Chart(ctx,{});

    // var ctx = document.getElementById("sales_overdude");
    // chart_sales_overdude = new Chart(ctx,{});

    // var ctx = document.getElementById("sales_payment");
    // chart_sales_payment = new Chart(ctx,{});

    // var ctx = document.getElementById("purchase_order");
    // chart_purchase_order = new Chart(ctx,{});

    var ctx = document.getElementById("purchase_transaction");
    chart_purchase_transaction = new Chart(ctx,{});

    // var ctx = document.getElementById("purchase_return");
    // chart_purchase_return = new Chart(ctx,{});

    var ctx = document.getElementById("purchase_open");
    chart_purchase_open = new Chart(ctx,{});

    // var ctx = document.getElementById("purchase_overdude");
    // chart_purchase_overdude = new Chart(ctx,{});

    // var ctx = document.getElementById("purchase_payment");
    // chart_purchase_payment = new Chart(ctx,{});

    var ctx = document.getElementById("stock_branch");
    chart_stock_branch = new Chart(ctx,{});

    // var ctx = document.getElementById("store_receivable");
    // chart_store_receivable = new Chart(ctx,{});

    var ctx = document.getElementById("balance_sheet");
    chart_balance_sheet = new Chart(ctx,{});

    var ctx = document.getElementById("top_sales_customer");
    chart_top_customer = new Chart(ctx,{});

    var ctx = document.getElementById("loss_profit");
    chart_loss_profit = new Chart(ctx,{});

}

function reload_data(page = "")
{
    if(page == "status_sales"){
        load_data("CheckStatus");
    } else if(page == "map"){
        load_data("map");
    }
}
function s_load_data(){
	load_data("sellheader");
    load_data("purchaseheader");
    load_data("inventory");
    load_data("accounting");
}
function load_data(page = "")
{

    if(!page || page == "basic"){
        $('.panel-bordered').addClass('is-loading');
    }else if(page == 'sellheader'){
        $('.var .panel-bordered').addClass('is-loading');
    }else if(page == 'purchaseheader'){
        $('.vap .panel-bordered').addClass('is-loading');
    }else if(page == 'inventory'){
        $('.vinventory .panel-bordered').addClass('is-loading');
    }else if(page == 'accounting'){
        $('.vac .panel-bordered').addClass('is-loading');
    }

    if(app == "pipesys"){
        url_post =  host+"dashboard/dashboard";
    } else if(app == "salespro"){
        url_post = host+"api/dashboard_salespro";
    }
    Check       = $("[name=Check]:checked").val();
    StartDate   = $("[name=fStartDate]").val();
    EndDate     = $("[name=fEndDate]").val();

    // untuk data diagram
    outstanding_delx = $('#ul-outstanding .li-active').data();
    sales_storex     = $('#ul-sales-store .li-active').data();
    sales_costx      = $('#ul-sales-cost .li-active').data();
    sales_openx      = $('#ul-sales-open .li-active').data();
    sales_overdudex  = $('#ul-sales-overdude .li-active').data();
    sales_customerx  = $('#ul-sales-customer .li-active').data();
    sales_paymentx   = $('#ul-sales-payment .li-active').data();
    sales_cityx      = $('#ul-sales_city .li-active').data();

    purchase_orderx         = $('#ul-purchase-order .li-active').data();
    purchase_transactionx   = $('#ul-purchase-transaction .li-active').data();
    purchase_returnx        = $('#ul-purchase-return .li-active').data();
    purchase_openx          = $('#ul-purchase-open .li-active').data();
    purchase_overdudex      = $('#ul-purchase-overdude .li-active').data();
    purchase_paymentx       = $('#ul-purchase-payment .li-active').data();

    store_receivablex       = $('#ul-store_receivable .li-active').data();
    loss_profitx       		= $('#ul-loss-profit .li-active').data();
    balance_sheetx          = $('#ul-balance_sheet .li-active').data();

    data_post   = {
        Check           : Check,
        StartDate       : StartDate,
        EndDate         : EndDate,
        outstand_type   : outstanding_delx.type,
        // sales_store     : sales_storex.type,
        sales_cost      : sales_costx.type,
        // sales_city      : sales_cityx.type,
        // sales_customer  : sales_customerx.type,
        sales_open      : sales_openx.type,
        // sales_overdude  : sales_overdudex.type,
        // sales_payment   : sales_paymentx.type,
        // purchase_order  : purchase_orderx.type,
        purchase_transaction : purchase_transactionx.type,
        // purchase_return : purchase_returnx.type,
        purchase_open   : purchase_openx.type,
        // purchase_overdude   : purchase_overdudex.type,
        // purchase_payment    : purchase_paymentx.type,
        // store_receivable    : store_receivablex.type,
        loss_profit 		: loss_profitx.type,
        balance_sheet       : balance_sheetx.type,
        page            : page,
    };
    // if(StartDate > EndDate){
    //     alert("date from must less than date to");
    //     return;
    // }
    $.ajax({
        url : url_post,
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            
            if(app == "pipesys"){
                if(page == 'sellheader'){
                    $('.var .is-loading').removeClass("is-loading");
                }else if(page == 'purchaseheader'){
                    $('.vap .is-loading').removeClass("is-loading");
                }else if(page == 'inventory'){
                    $('.vinventory .is-loading').removeClass("is-loading");
                }else if(page == 'accounting'){
                    $('.vac .is-loading').removeClass("is-loading");
                }else if(page == 'expire'){

                }else{
                    $('.is-loading').removeClass("is-loading");
                }

                if(page =="expire"){
                    expired_module(data.list_expire);
                }
                
                if(page == "outstanding_delivery"){
                    outstanding_delivery(data.outstanding_delivery);
                }else if(page == "sales_store"){
                    sales_store(data.sales_store);
                }else if(page == "omset_cost"){
                    sales_cost(data.sales_cost);
                }else if(page == "sales_city"){
                    sales_city(data.sales_city);
                }else if(page == "sales_customer"){
                    sales_customer(data.sales_customer);
                }else if(page == "sales_open"){
                    sales_open(data.sales_open);
                }else if(page == "sales_overdude"){
                    sales_overdude(data.sales_overdude);
                }else if(page == "sales_payment"){
                    sales_payment(data.sales_payment);
                }else if(page == "purchase_order"){
                    purchase_order(data.purchase_order);
                }else if(page == "purchase_transaction"){
                    purchase_transaction(data.purchase_transaction);
                }else if(page == "purchase_return"){
                    purchase_return(data.purchase_return);
                }else if(page == "purchase_open"){
                    purchase_open(data.purchase_open);
                }else if(page == "purchase_overdude"){
                    purchase_overdude(data.purchase_overdude);
                }else if(page == "purchase_payment"){
                    purchase_payment(data.purchase_payment);
                }else if(page == "sellheader"){
                    set_data_selling(data);
                }else if(page == "purchaseheader"){
                    set_data_purchase(data);
                }else if(page == "inventory"){
                    set_data_inventory(data);
                }else if(page == "accounting"){
                    set_data_accounting(data);
                }else if(page == "loss_profit"){
                	set_data_loss_profit(data.loss_profit);
                }else if(page == "balance_sheet"){
                    set_balance_sheet(data.balance_sheet)
                }else if(data.AlertVerification == 1){
                    $("#modal-information .modal-footer").empty();
                    $("#modal-information .title-info").empty();
                    $("#modal-information .message-info").empty();
                    $("#modal-information .title-info").append("Information");
                    $("#modal-information .message-info").append("Please verify your account, if you don't verify, your account will deactivate. Please verify before" + '<b>'+data.VerificationExpire+'</b>');
                    $("#modal-information .modal-footer").append('<a href="'+host+'verification-account" class="btn btn-primary">'+"Verification Account"+'</a>');
                    $("#modal-information").modal("show");
                }else if(page == "expire"){
                    
                }else{
                    set_data_selling(data);
                    set_data_purchase(data);
                    set_data_inventory(data);
                    set_data_accounting(data);
                }
            } else if(app == "salespro"){
                $(".total_sales").text(data.total_sales);
                $(".total_route_transaction").text(data.total_route_transaction);
                $(".total_complete_route").text(data.total_complete_route);
                $(".total_customer").text(data.total_customer);

                if(page == "basic"){
                    if(data.list_sales && data.list_sales.length > 0){
                        add_location_store(data.list_sales,{zoom:true});                
                    }
                } else if(page == "map"){
                    if(data.list_sales && data.list_sales.length > 0){
                        add_location_store(data.list_sales);                
                    }
                }

                if(Check == "CheckIn"){
                    DataCheck = data.list_checkin;
                } else {
                    DataCheck = data.list_checkout;
                }


                if(page == "basic" || page == 'CheckStatus'){
                    $("#table-info-sales thead tr").remove();
                    itemTH =  '<tr>';
                    itemTH += '<th>No</th>';
                    itemTH += '<th>Sales Name</th>';
                    itemTH += '<th>Location</th>';
                    itemTH += '<th>Time</th>';
                    if(Check == "CheckIn"){
                        itemTH += '<th>Duration</th>';
                    }
                    itemTH += '</tr>';
                    $("#table-info-sales thead").append(itemTH);
                    
                    if(DataCheck){
                        $("#table-info-sales tbody tr").remove();
                        if(DataCheck.length > 0){
                            $.each(DataCheck,function(i,v){

                                Address = 'Address name not found';
                                if(v.CheckAddress){
                                    Address = v.CheckAddress;
                                }

                                i += 1;
                                item = '<tr>';
                                item += '<td>'+i+'</td>';
                                item += '<td>'+v.Name+'</td>';
                                item += '<td>'+Address+'</td>';
                                item += '<td>'+v.CheckTime+'</td>';
                                if(Check == "CheckIn"){
                                    item += '<td>'+v.Duration+'</td>';
                                }
                                item += '</tr>';
                                $("#table-info-sales tbody").append(item);
                            });

                        } else {
                            item = '<tr><td colspan="5"><center>Data Not Found</center></td></tr>'
                            $("#table-info-sales tbody").append(item);
                        }
                    }
                }
                var StartDate   = $('[name=StartDate]').val();
                var EndDate     = $('[name=EndDate]').val();
                period = "Period "+StartDate+" - "+EndDate;
                top_sales_visit(data.top_sales);
                total_route(data.total_route1);
                total_hour(data.total_hour);
                if(page == "basic" && data.list_expire && data.list_expire.length > 0){
                    $("#table-list-expire tbody").empty();
                    $.each(data.list_expire,function(i,v){
                        classx = "";
                        if(v.Tipe == 0){
                            classx += "tr-red";
                        }
                        days = v.Selisih;
                        daysx = Math.abs(v.Selisih);
                        if(days > 0){
                                days = daysx + ' day(s) left';
                            } else {
                                days = daysx + ' day(s) ago';
                            }
                        item = '<tr class="'+classx+'">';
                        item += '<td>'+(i+1)+'</td>';
                        item += '<td>'+v.UserAdd+'</td>';
                        item += '<td>'+v.Module+'</td>';
                        item += '<td>'+v.ExpireDate+'</td>';
                        item += '<td>'+days+'</td>';
                        item += '<tr>';
                        $("#table-list-expire tbody").append(item);
                    });

                    info        = '';
                    modal_title = "List Module Expire";

                    $(".list-info").append(info);
                    $("#modal-list-expire .modal-title").text(modal_title);
                    $("#modal-list-expire").modal("show");
                }
                if(data.AlertVerification == 1){
                    $("#modal-information .modal-footer").empty();
                    $("#modal-information .title-info").empty();
                    $("#modal-information .message-info").empty();
                    $("#modal-information .title-info").append("Information");
                    $("#modal-information .message-info").append("Please verify your account, if you don't verify, your account will deactivate. Please verify before " +'<b>'+data.VerificationExpire+'</b>');
                    $("#modal-information .modal-footer").append('<a href="'+host+'verification-account" class="btn btn-primary">'+"Verification Account"+'</a>');
                    $("#modal-information").modal("show");
                }
            }
            if(data.StatusAccount == "trial"){
                // expired_message(data.ExpireAccountDayLeft);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);
            $('.is-loading').removeClass("is-loading");
        }
    });
}

function set_data_selling(data){
    $(".total_purchase_amount").text(data.total_purchase_amount);
    $('#total_purchase_amount').LineProgressbar({
        percentage: data.total_purchase_amount,
        fillBackgroundColor: '#cddc39',
         height: '15px'
    });
    $(".total_sell_amount").text(data.total_sell_amount);
    $('#total_sell_amount').LineProgressbar({
        percentage: data.total_sell_amount,
        fillBackgroundColor: '#cddc39',
         height: '15px'
    });
    $(".total_sell_qty").text(data.total_sell_qty);
    $('#total_sell_qty').LineProgressbar({
        percentage: data.total_sell_qty,
        fillBackgroundColor: '#00BCD4',
         height: '15px'
    });
    $(".total_sell").text(data.total_sell);
    $('#total_sell').LineProgressbar({
        percentage: data.total_sell,
        fillBackgroundColor: '#00BCD4',
         height: '15px'
    });
    $(".total_customer").text(data.total_customer);
    $('#total_customer').LineProgressbar({
        percentage: data.total_customer,
        fillBackgroundColor: '#00BCD4',
         height: '15px'
    });
    $(".total_product").text(data.total_product);
    $('#total_product').LineProgressbar({
        percentage: data.total_product,
        fillBackgroundColor: '#00BCD4',
         height: '15px'
    });
    
    if(data.list_store && data.list_store.length > 0){
        add_location_store(data.list_store,{zoom:true});                
    }
    
    top_sales_category(data.top_sales_category);
    top_sales_customer(data.top_sales_customer);
    // top_sales_branch(data.top_sales_branch);
    outstanding_delivery(data.outstanding_delivery);
    // sales_hour(data.sales_hour);
    // sales_store(data.sales_store);
    sales_city(data.sales_city);
    // sales_customer(data.sales_customer);
    sales_open(data.sales_open);
    // sales_overdude(data.sales_overdude);
    // sales_payment(data.sales_payment);

    // total 
    $('.total_sales_overdude').text(data.sales_overdude.total);
    $('.total_sales_payment').text(data.sales_payment.total);
    $('.total_vendor').text(data.total_vendor);
}

function set_data_purchase(data){
    // purchase_order(data.purchase_order);
    purchase_transaction(data.purchase_transaction);
    // purchase_return(data.purchase_return);
    purchase_open(data.purchase_open);
    // purchase_overdude(data.purchase_overdude);
    // purchase_payment(data.purchase_payment);

    // total
    $('.total_purchase_overdude').text(data.purchase_overdude.total);
    $('.total_purchase_payment').text(data.purchase_payment.total);
}

function set_data_inventory(data){
    minimal_product(data.minimal_product);
    product_branch(data.stock_product,data.product_branch);

    $('.total_stock').text(parseFloat(data.stock_product));
    $('#total_stock').LineProgressbar({
        percentage: data.stock_product,
        fillBackgroundColor: '#00BCD4',
         height: '15px'
    });
}

function set_data_accounting(data){
    $('.total_net_omset').text(data.total_net_omset);

    sales_cost(data.sales_cost);
    // store_receivable(data.store_receivable);
    account_watchlist(data.account_watchlist);
    set_data_loss_profit(data.loss_profit);
    set_balance_sheet(data.balance_sheet)
}

function get_report(page = ""){ 
    url = host + "report";
    // url = host + "report?tes=ya";
    id  = '#form-dashboard-filter';
    $(id).attr('action', url);
    $(id).attr('target', '_blank');
    $('<input />').attr('type', 'hidden')
          .attr('name', "Report")
          .attr('value', page)
          .appendTo(id);
    $(id).submit();
}
function expired_message(daysleft)
{
    swal({   title: "You have "+daysleft+" days left",   
             text: "Special Introductory Offer! Take 50% off our regular prices for the first 12 months!!",   
             type: "warning",   
             showCancelButton: true,   
             confirmButtonColor: "#62a8ea",   
             confirmButtonText: "Buy Now",   
             cancelButtonText: "Later",   
             closeOnConfirm: true,   
             closeOnCancel: true 
         }, 
         function(isConfirm){   
            if (isConfirm) { 
                swal("Deleted!", "Your data has been deleted.", "success");  
            } else {
                // swal("Canceled", "Your data is safe :)", "error");   
            } 
    });
}


function initialize() {
    var mapProp = {
        center:myCenter,
        zoom: zoom,
        gestureHandling: 'greedy'
        // mapTypeId:google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map"),mapProp);
    // add_location_store();
    // map.addListener('click', function(event) {
    //     myCenter = event.latLng; 
    //     addMarker(myCenter);
    //     setMarkerinput(myCenter);
    // });
}
google.maps.event.addDomListener(window, 'load', initialize);
google.maps.event.addDomListener(window, "resize", resizingMap());

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
        zoom        = 12;
    } else {
        // deleteMarkers();
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
    // deleteMarkers();
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
    $("#lat").val(location.lat());
    $("#lng").val(location.lng());
}
function add_location_store(list_store,setting=""){
    if(markers.length > 0){
        deleteMarkers();
    }
    var bounds = new google.maps.LatLngBounds();
    var infowindow = new google.maps.InfoWindow(); /* SINGLE */
    function placeMarker(v) {
        if(v.Check == 'in'){
            icon = host +'img/icon/marker-blue.svg';
        } else {
            icon = host +'img/icon/marker-red.svg';
        }
        var IconMarker  = {
          url: icon,
          size: new google.maps.Size(45, 45),
          origin: new google.maps.Point(-3, -5,0,0),
          scaledSize: new google.maps.Size(40, 40)
      };


        lat = v.Lat;
        lng = v.Lng;

        var latLng = new google.maps.LatLng(lat, lng);
        bounds.extend(latLng);
        var marker = new google.maps.Marker({
          position : latLng,
          map      : map,
          icon : IconMarker
        });


        markers.push(marker);
        google.maps.event.addListener(marker, 'click', function(){
            Name = v.Name;
            if(v.App == "salespro"){
                if(v.Check == "in"){
                    Check = "Check In : <br/>" + v.CheckTime;
                } else if(v.Check == "out") {
                    Check = "Finish : <br/>" + v.CheckTime;
                } else {
                    Check = "";
                }
                Address = 'Address name not found';
                if(v.CheckAddress){
                    Address = v.CheckAddress;
                }


                WindowContent = "<div id='infowindow'>";
                WindowContent += '<b>'+v.Name+'</b>';
                WindowContent += "<br/>" + Check;
                WindowContent += "<br/>" + Address;
                WindowContent += "</div>";
            } else {
                WindowContent = "<div id='infowindow'>"+ Name +"</div>";
            }

            infowindow.close(); // Close previously opened infowindow
            infowindow.setContent(WindowContent);
            infowindow.open(map, marker);
        });
    }
    $.each(list_store, function(i, v) {
        if(setting.zoom && i == 0){
            myCenter = new google.maps.LatLng(v.Lat, v.Lng);
            center   = myCenter;
            zoom     = 8;
            google.maps.event.trigger(map, "resize");
            map.setCenter(center); 
            map.setZoom(zoom); 
        }
       placeMarker(v);
    });
    map.fitBounds(bounds);
}

function top_sales_visit(data){
    var data_top_visit = [];
    $.each(data,function(i,v){
        var d = {label:v.Name,y:parseInt(v.Count)};
        data_top_visit.push(d);
    });
    CanvasJS.addColorSet("greenShades",
                [
                "#62a8ea"             
                ]);

    var options = {
        colorSet: "greenShades",
        exportEnabled: true,
        theme: "light2",
        title:{
            text:'Top Employee Visit',
            fontSize: 17,
        },
        subtitles:[
        {
            text: period,
            fontSize:12,
            fontWeight: "normal",
        }
        ],  
        axisY: {
            title: "Total Complete Route",
        },
        data: [              
        {
            // Change type to "doughnut", "line", "splineArea", etc.
            type: "column",
            dataPoints: data_top_visit
        }
        ]
    };


    $("#chartContainer").CanvasJSChart(options);
}

function total_route(data){
    var arrayComplete = [];
    var arrayMiss     = [];
    var arraytotal    = [];

    $.each(data,function(i,v){
        var miss        = { x: new Date(parseInt(v.year), parseInt(v.month)-1, parseInt(v.day)), y: parseInt(v.miss) };
        var complete    = { x: new Date(parseInt(v.year), parseInt(v.month)-1, parseInt(v.day)), y: parseInt(v.complete) };
        var total       = { x: new Date(parseInt(v.year), parseInt(v.month)-1, parseInt(v.day)), y: parseInt(v.total) };
        arrayMiss.push(miss);
        arrayComplete.push(complete);
        arraytotal.push(total);
    });
    var options = {
        animationEnabled: true,
        exportEnabled: true,
        theme: "light2",
        title:{
            text:'Total Route',
            fontSize: 17,
        },
        subtitles:[
        {
            text: period,
            fontSize:12,
            fontWeight: "normal",
        }
        ],
        axisX:{
            valueFormatString: "DD MMM",
        },
        axisY: {
            title: "Total Route",
        },
        toolTip:{
            shared:true
        },  
        legend:{
            cursor:"pointer",
            verticalAlign: "bottom",
            horizontalAlign: "center",
            dockInsidePlotArea: false,
            itemclick: toogleDataSeries
        },
        data: [
            {
                type: "line",
                showInLegend: true,
                name: "Total Complete Route",
                markerType: "square",
                xValueFormatString: "DD MMM, YYYY",
                color: "#62a8ea",
                yValueFormatString: "#,##0",
                dataPoints: arrayComplete
            },
            {
                type: "line",
                showInLegend: true,
                name: "Total Missed Route",
                lineDashType: "dash",
                color:"#f96868",
                yValueFormatString: "#,##0",
                dataPoints: arrayMiss
            }
        ]
    };
    $("#lineContainer").CanvasJSChart(options);
}
function toogleDataSeries(e){
    if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
        e.dataSeries.visible = false;
    } else{
        e.dataSeries.visible = true;
    }
    e.chart.render();
}

function total_hour(data){
    var total_hour = [];
    $.each(data,function(i,v){
        var year    = parseInt(v.year);
        var month   = parseInt(v.month);
        var day     = parseInt(v.day)
        var hour = {x: new Date(year, month-1, day), y: parseInt(v.total)};
        total_hour.push(hour);
    });
    var options = {
        animationEnabled: true,
        zoomEnabled: true,
        exportEnabled: true,
        theme: "light2",
        title:{
            text:'Total Visit (Hours)',
            fontSize: 17,
        },
        subtitles:[
        {
            text: period,
            fontSize:12,
            fontWeight: "normal",
        }
        ],
        axisX: {
            valueFormatString : "DD MMM",
        },
        toolTip:{
            shared:true
        }, 
        axisY: {          
            labelFormatter: function(e){
                var text = getTime(e.value);
                return text;
            },
            title: "Total Hour",
            //suffix : " hour"
        },
        legend:{
            cursor:"pointer",
            verticalAlign: "bottom",
            horizontalAlign: "center",
            dockInsidePlotArea: false,
            itemclick: toogleDataSeries
        },
        toolTip:{
        contentFormatter: function ( e ) {
            var text = e.entries[0].dataPoint.y;
            text     = getTime(text);
            return '<span style="color:#62a8ea">'+CanvasJS.formatDate(e.entries[0].dataPoint.x, "DD MMM")+"</span> "+text;  
        }},
        data: [
        {
            type: "line",
            lineDashType: "dash",
            name: "Hour(s)",
            showInLegend: true,
            color: "#62a8ea",
            dataPoints: total_hour
        }
        ]
    };
    $("#lineTotalVisit").CanvasJSChart(options);
}
function getTime(s){
    var h = Math.floor(s/3600); //Get whole hours
    s -= h*3600;
    var m = Math.floor(s/60); //Get remaining minutes
    s -= m*60;
    return h+":"+(m < 10 ? '0'+m : m); //zero padding on minutes and seconds
}

// 20190311 MW
function top_sales_category(data){
    var ctx = document.getElementById("sales_category");
    chart_category.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    if(data.length>0){
        no = 0;
        $.each(data, function(k,v){
            no += 1;
            color = get_backgroundColor(k);
            labels.push(no+". "+v.category);
            value.push(parseFloat(v.qty));
            backgroundColor.push(color[0]);
            borderColor.push(color[1]);
        });
    }else{
        labels.push("Data Not Found");
        value.push(100);
        backgroundColor.push('rgba(208,208,208,0.82');
        borderColor.push('rgba(208,208,208,0.82)');
    }
    chart_category = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                label: '# of Votes',
                data: value,
                backgroundColor: backgroundColor,
                borderColor: borderColor,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                display:false,
            },
            legend: {
                display : false,
                position : "right",
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var labels  = data.labels[tooltipItem.index];
                        if(labels == 'Data Not Found'){
                            var value   = 0;
                        }else{
                            var value   = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        }
                        var labels  = labels +" : "+txt_currency+" "+ number_format(value);
                        return labels;
                    }
                }
            }
        }
    });

    var s = chart_category.generateLegend();
    $('#sales_category-legend').html(s);

    $("#sales_category-legend > ul > li").on("click",function(e){
        var index = $(this).index();
        $(this).toggleClass("strike");
        var ci = e.view.chart_category;
        var curr = ci.data.datasets[0]._meta;
        $.each(curr, function(k,v){
            curr = v.data[index];
            curr.hidden = !curr.hidden;
            ci.update();
        });
        
    })
}

function top_sales_customer(data){
    var ctx = document.getElementById("top_sales_customer");
    chart_top_customer.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    if(data.length>0){
        no = 0;
        $.each(data, function(k,v){
            no += 1;
            color = get_backgroundColor(k);
            labels.push(no+". "+v.Name);
            value.push(parseFloat(v.total));
            backgroundColor.push(color[0]);
            borderColor.push(color[1]);
        });
    }else{
        labels.push("Data Not Found");
        value.push(100);
        backgroundColor.push('rgba(208,208,208,0.82');
        borderColor.push('rgba(208,208,208,0.82)');
    }
    chart_top_customer = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                label: '# of Votes',
                data: value,
                backgroundColor: backgroundColor,
                borderColor: borderColor,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                display:false,
            },
            legend: {
                display : false,
                position : "right",
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var labels  = data.labels[tooltipItem.index];
                        if(labels == 'Data Not Found'){var value = 0;}
                        else{
                            var value   = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        }
                        var labels  = labels +" : "+txt_currency+" "+ number_format(value);
                        return labels;
                    }
                }
            }
        }
    });

    var s = chart_top_customer.generateLegend();
    $('#top_sales_customer-legend').html(s);

    $("#top_sales_customer-legend > ul > li").on("click",function(e){
        var index = $(this).index();
        $(this).toggleClass("strike");
        var ci = e.view.chart_top_customer;
        var curr = ci.data.datasets[0]._meta;
        $.each(curr, function(k,v){
            curr = v.data[index];
            curr.hidden = !curr.hidden;
            ci.update();
        });
        
    })
}

function top_sales_branch(data){
    var ctx = document.getElementById("sales_branch");
    chart_top_sales_branch.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    total               = data.total;
    data                = data.list;
    if(data.length>0){
        no = 0;
        $.each(data, function(k,v){
            value.push(parseFloat(v.total));
        });
        $.each(data, function(k,v){
            no += 1;
            color = get_backgroundColor(k);
            labels.push(v.branchName);

            val = [];    
            value.forEach(function (item, index, array) {
                  if(k == index){
                    val.push(parseFloat(v.total));
                  }else{
                    val.push(parseFloat(0));
                  }
            });
            d = {
                label           : no+". "+v.branchName,
                data            : val,
                backgroundColor : color[0],
                borderColor     : color[1],
                borderWidth     : 1,
            };
            datasets.push(d);
        });
    }else{
        labels.push("Data Not Found");
        value.push(100);
        backgroundColor.push('rgba(208,208,208,0.82');
        borderColor.push('rgba(208,208,208,0.82)');
        d = {
            label           : 'Data Not Found',
            data            : 0,
            backgroundColor : backgroundColor,
            borderColor     : borderColor,
            borderWidth     : 1,
        };
        datasets.push(d);
    }
    chart_top_sales_branch = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasets,
        },
        options: {
            scales: {
                xAxes: [{
                    stacked: true
                }],
                yAxes: [{
                    stacked: true
                }]
            },
            legend: {
                position : "right",
            }
        }
    });

    $('.total_store').text(total);
}

function outstanding_delivery(data){
    var ctx = document.getElementById("outstanding_delivery");
    chart_outstanding.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    total               = 0;
    if(data.ID.length>0){
        no   = 0;
        name = '';
        date = '';
        $.each(data.ID, function(k,v){
            value = [];
            $.each(data.data, function(kk,vv){
                if($.inArray(vv.date, labels) == -1){
                    labels.push(vv.date);
                }
                if(v == vv.VendorID){
                    name = vv.vendorName;
                    total += parseFloat(vv.qty);
                    value.push(parseFloat(vv.qty));
                }
            });
            color = get_backgroundColor(k);
            d = {
                label           : name,
                data            : value,
                backgroundColor : color[0],
                borderColor     : color[1],
                borderWidth     : 1,
            };
            datasets.push(d);


        });

        var dt_column = data.data.length / data.ID.length;
        if (data.ID.length<10) {
            if(dt_column>=5 && data.ID.length < 4){
                count_loop = 3 - data.ID.length;
            }
            else if(dt_column<=5 && data.ID.length < 6){
                count_loop = 5 - data.ID.length;
            }
            else if(dt_column >= 5 && data.ID.left <6){
            	count_loop = 5 - data.ID.length;	
            }else{
                count_loop = 10 - data.ID.length;
            }
            for (var i = 0; i < count_loop; i++) {
                d = {
                    label           : '',
                    backgroundColor : 'rgba(240, 248, 255, 0)',
                    borderColor     : 'rgba(240, 248, 255, 0)',
                    data            : 0,
                };
                datasets.push(d);       
            }
        }

    }else{
        labels.push("Data Not Found");
        value.push(100);
        backgroundColor.push('rgba(208,208,208,0.82');
        borderColor.push('rgba(208,208,208,0.82)');
        d = {
            label           : 'Data Not Found',
            data            : 0,
            backgroundColor : backgroundColor,
            borderColor     : borderColor,
            borderWidth     : 1,
        };
        datasets.push(d);
    }
    var data = {
        labels: labels,
        datasets : datasets,
    };

    chart_outstanding = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            legend: {
                display:false,
                labels: {
                  fontColor: '#000',
                  fontSize : 12,
                  usePointStyle:true,
                },
                generateLabels: {
                  strokeStyle: "black",
                }
            },
            barValueSpacing: 20,
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                        fontColor: '#000',
                        fontSize : 13,
                    }
                }],
                xAxes: [{
                    ticks: {
                        min: 0,
                        fontColor: '#000',
                        fontSize : 13,
                    }
                }]
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var labels  = data.datasets[tooltipItem.index].label;
                        var value   = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        var labels  = labels +" : "+ number_format(value)+" "+language_app.lb_qty2;
                        return labels;
                    }
                }
            }
        },
    
    });

    var s = chart_outstanding.generateLegend();
    var dt_chart = chart_outstanding.data.datasets;
    s = '<ul>';
    for (var i = 0; i < dt_chart.length; i++) {
        d = dt_chart[i];
        backgroundcolor = 'background-color:'+d.backgroundColor+";";
        bordercolor     = 'border:1px solid '+d.borderColor+";";
        s += '<li><span style="'+backgroundcolor+bordercolor+'"></span>'+d.label+'</li>';
    }
    s += '</ul>';
    $('#outstanding_delivery-legend').html(s);
    $('.total_outstanding_delivery').text(total);

    $("#outstanding_delivery-legend > ul > li").on("click",function(e){
        var index = $(this).index();
        $(this).toggleClass("strike");
        var ci = e.view.chart_outstanding;
        var curr = ci.data.datasets[index]._meta;
        $.each(curr, function(k,v){
            curr = v;
            curr.hidden = !curr.hidden;
            ci.update();
        });
        
    })
}

function sales_hour(data){
    var ctx = document.getElementById("sales_hour");
    chart_sales_hour.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    date                = '';
    if(data.length>0){
        $.each(data, function(k,v){
            date = "Date "+v.date;
            color = get_backgroundColor(0);
            labels.push("Hour "+v.hour);
            value.push(parseFloat(v.qty));
            backgroundColor.push(color[0]);
            borderColor.push(color[1]);
        });
    }else{
        date = 'Data Not Found';
        labels.push("Data Not Found");
        value.push(0);
        backgroundColor.push('rgba(208,208,208,0.82');
        borderColor.push('rgba(208,208,208,0.82)');
    }

    chart_sales_hour = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: date,
                data: value,
                backgroundColor: backgroundColor,
                borderColor: borderColor,
                borderWidth: 1
            }]
        },
        options: {
            barValueSpacing: 20,
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                    }
                }]
            }
        },
    });
}

function sales_store(data){
    var ctx = document.getElementById("sales_store");
    chart_sales_store.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    total               = 0;
    if(data.ID.length>0){
        no   = 0;
        name = '';
        date = '';
        $.each(data.ID, function(k,v){
            value = [];
            $.each(data.data, function(kk,vv){
                if($.inArray(vv.date, labels) == -1){
                    labels.push(vv.date);
                }
                if(v == vv.BranchID){
                    name = vv.branchName;
                    total += parseFloat(vv.qty);
                    value.push(parseFloat(vv.qty));
                }
            });
            color = get_backgroundColor(k);
            d = {
                label           : name,
                data            : value,
                backgroundColor : color[0],
                borderColor     : color[1],
                borderWidth     : 1,
            };
            datasets.push(d);
        });

    }else{
        labels.push("Data Not Found");
        value.push(100);
        backgroundColor.push('rgba(208,208,208,0.82');
        borderColor.push('rgba(208,208,208,0.82)');
        d = {
            label           : 'Data Not Found',
            data            : 0,
            backgroundColor : backgroundColor,
            borderColor     : borderColor,
            borderWidth     : 1,
        };
        datasets.push(d);
    }
    var data = {
        labels: labels,
        datasets : datasets,
    };

    chart_sales_store = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            barValueSpacing: 20,
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                    }
                }]
            },
            legend: {
                position : "right",
            }
        },
    
    });

    $('.total_store_sales').text(total);
}

function sales_cost(data){
    var ctx = document.getElementById("omset_cost");
    chart_omset_cost.destroy();
    labels              = [];
    value_cost          = [];
    value_sales         = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    if(data.length>0){
        labels.push('');
        value_cost.push(0);
        value_sales.push(0);
        $.each(data,function(i,v){
            labels.push(v.date);
            value_cost.push(parseFloat(v.cost));
            value_sales.push(parseFloat(v.sale));
        });
        color = get_backgroundColor(1);
        d = {
            label           : 'Sales',
            data            : value_sales,
            backgroundColor : color[0],
            borderColor     : color[1],
            borderWidth     : 1,
        };
        datasets.push(d);

        color = get_backgroundColor(6);
        d = {
            label           : 'Cost',
            data            : value_cost,
            backgroundColor : color[0],
            borderColor     : color[1],
            borderWidth     : 1,
        };
        datasets.push(d);
    }else{
        labels.push("Data Not Found");
        value.push(100);
        backgroundColor.push('rgba(208,208,208,0.82');
        borderColor.push('rgba(208,208,208,0.82)');
        d = {
            label           : 'Data Not Found',
            data            : 0,
            backgroundColor : backgroundColor,
            borderColor     : borderColor,
            borderWidth     : 1,
        };
        datasets.push(d);
    }
    var data = {
        labels: labels,
        datasets : datasets,
    };

    chart_omset_cost = new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            barValueSpacing: 20,
            legend: {
                display : false,
                position : "right",
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        
                        if(tooltipItem.index>0){
                            var labels  = data.datasets[tooltipItem.datasetIndex].label;
                            var value   = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                            var labels  = labels +" : "+txt_currency+" "+ number_format(value);
                        }else{
                            var labels = '';
                        }
                        return labels;
                    }
                }
            }
        },
    
    });
    var s = chart_omset_cost.generateLegend();
    var dt_chart = chart_omset_cost.data.datasets;
    s = '<ul>';
    for (var i = 0; i < dt_chart.length; i++) {
        d = dt_chart[i];
        backgroundcolor = 'background-color:'+d.borderColor+";";
        bordercolor     = 'border:1px solid '+d.borderColor+";";
        s += '<li><span style="'+backgroundcolor+bordercolor+'"></span>'+d.label+'</li>';
    }
    s += '</ul>';
    $('#omset_cost-legend').html(s);
    $("#omset_cost-legend > ul > li").on("click",function(e){
        var index = $(this).index();
        $(this).toggleClass("strike");
        var ci = e.view.chart_omset_cost;
        var curr = ci.data.datasets[index]._meta;
        $.each(curr, function(k,v){
            curr = v;
            curr.hidden = !curr.hidden;
            ci.update();
        });
        
    })
}

function sales_city(data){
    var ctx = document.getElementById("sales_city");
    chart_sales_city.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    if(data.length>0){
        no = 0;
        $.each(data, function(k,v){
            no += 1;
            color = get_backgroundColor(k);
            labels.push(no+". "+v.city);
            value.push(parseFloat(v.qty));
            backgroundColor.push(color[0]);
            borderColor.push(color[1]);
        });
    }else{
        labels.push("Data Not Found");
        value.push(100);
        backgroundColor.push('rgba(208,208,208,0.82');
        borderColor.push('rgba(208,208,208,0.82)');
    }
    chart_sales_city = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                label: '# of Votes',
                data: value,
                backgroundColor: backgroundColor,
                borderColor: borderColor,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                display:false,
            },
            legend: {
                display : false,
                position : "right",
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var labels  = data.labels[tooltipItem.index];
                        if(labels == 'Data Not Found'){
                            var value   = 0;
                        }else{
                            var value   = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        }
                        var labels  = labels +" : "+txt_currency+" "+ number_format(value);
                        return labels;
                    }
                }
            }
        }
    });

    var s = chart_sales_city.generateLegend();
    $('#sales_city-legend').html(s);

    $("#sales_city-legend > ul > li").on("click",function(e){
        var index = $(this).index();
        $(this).toggleClass("strike");
        var ci = e.view.chart_sales_city;
        var curr = ci.data.datasets[0]._meta;
        $.each(curr, function(k,v){
            curr = v.data[index];
            curr.hidden = !curr.hidden;
            ci.update();
        });
        
    })
}

function sales_customer(data){
    var ctx = document.getElementById("total_sales_customer");
    chart_sales_customer.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    total               = 0;
    if(data.ID.length>0){
        no   = 0;
        name = '';
        date = '';
        $.each(data.ID, function(k,v){
            value = [];
            $.each(data.data, function(kk,vv){
                if($.inArray(vv.date, labels) == -1){
                    labels.push(vv.date);
                }
                if(v == vv.VendorID){
                    name = vv.vendorName;
                    value.push(parseFloat(vv.total));
                }
            });
            color = get_backgroundColor(k);
            d = {
                label           : name,
                data            : value,
                backgroundColor : color[0],
                borderColor     : color[1],
                borderWidth     : 1,
            };
            datasets.push(d);
        });

    }else{
        labels.push("Data Not Found");
        value.push(100);
        backgroundColor.push('rgba(208,208,208,0.82');
        borderColor.push('rgba(208,208,208,0.82)');
        d = {
            label           : 'Data Not Found',
            data            : 0,
            backgroundColor : backgroundColor,
            borderColor     : borderColor,
            borderWidth     : 1,
        };
        datasets.push(d);
    }
    var data = {
        labels: labels,
        datasets : datasets,
    };

    chart_sales_customer = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            barValueSpacing: 20,
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                    }
                }]
            },
            legend: {
                position : "right",
            }
        },
    
    });
}

function sales_open(data){
    var ctx = document.getElementById("sales_open");
    chart_sales_open.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    total               = data.total;
    if(data.ID.length>0){
        no   = 0;
        name = '';
        date = '';
        $.each(data.ID, function(k,v){
            value = [];
            $.each(data.data, function(kk,vv){
                labels.push('');
                value.push(0);
                if($.inArray(vv.date, labels) == -1){
                    labels.push(vv.date);
                }
                if(v == vv.VendorID){
                    name = vv.vendorName;
                    value.push(parseFloat(vv.total));
                }
            });
            color = get_backgroundColor(k,"transaparant");
            d = {
                label           : name,
                data            : value,
                backgroundColor : color[0],
                borderColor     : color[1],
                borderWidth     : 1,
            };
            datasets.push(d);
        });
    }else{
        labels.push("Data Not Found");
        value.push(100);
        backgroundColor.push('rgba(208,208,208,0.82');
        borderColor.push('rgba(208,208,208,0.82)');
        d = {
            label           : 'Data Not Found',
            data            : 0,
            backgroundColor : backgroundColor,
            borderColor     : borderColor,
            borderWidth     : 1,
        };
        datasets.push(d);
    }
    var data = {
        labels: labels,
        datasets : datasets,
    };

    chart_sales_open = new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            barValueSpacing: 20,
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                    }
                }]
            },
            legend: {
                display : false,
                position : "right",
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        if(tooltipItem.index>0){
                            var labels  = data.datasets[tooltipItem.datasetIndex].label;
                            var value   = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                            var labels  = labels +" : "+txt_currency+" "+ number_format(value);
                        }else{
                            var labels = '';
                        }
                        return labels;
                    }
                }
            }
        },
    
    });

    $('.total_sales_open').text(total);
    var s = chart_sales_open.generateLegend();
    var dt_chart = chart_sales_open.data.datasets;
    s = '<ul>';
    for (var i = 0; i < dt_chart.length; i++) {
        d = dt_chart[i];
        backgroundcolor = 'background-color:'+d.borderColor+";";
        bordercolor     = 'border:1px solid '+d.borderColor+";";
        s += '<li><span style="'+backgroundcolor+bordercolor+'"></span>'+d.label+'</li>';
    }
    s += '</ul>';
    $('#sales_open-legend').html(s);

    $("#sales_open-legend > ul > li").on("click",function(e){
        var index = $(this).index();
        $(this).toggleClass("strike");
        var ci = e.view.chart_sales_open;
        var curr = ci.data.datasets[index]._meta;
        $.each(curr, function(k,v){
            curr = v;
            curr.hidden = !curr.hidden;
            ci.update();
        });
        
    })
}

function sales_overdude(data){
    var ctx = document.getElementById("sales_overdude");
    chart_sales_overdude.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    total               = data.total;
    if(data.ID.length>0){
        no   = 0;
        name = '';
        date = '';
        $.each(data.ID, function(k,v){
            value = [];
            $.each(data.data, function(kk,vv){
                if($.inArray(vv.date, labels) == -1){
                    labels.push(vv.date);
                }
                if(v == vv.VendorID){
                    name = vv.vendorName;
                    value.push(parseFloat(vv.total));
                }
            });
            color = get_backgroundColor(k);
            d = {
                label           : name,
                data            : value,
                backgroundColor : color[0],
                borderColor     : color[1],
                borderWidth     : 1,
            };
            datasets.push(d);
        });

    }else{
        labels.push("Data Not Found");
        value.push(100);
        backgroundColor.push('rgba(208,208,208,0.82');
        borderColor.push('rgba(208,208,208,0.82)');
        d = {
            label           : 'Data Not Found',
            data            : 0,
            backgroundColor : backgroundColor,
            borderColor     : borderColor,
            borderWidth     : 1,
        };
        datasets.push(d);
    }
    var data = {
        labels: labels,
        datasets : datasets,
    };

    chart_sales_overdude = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            barValueSpacing: 20,
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                    }
                }]
            },
            legend: {
                position : "right",
            }
        },
    
    });

    $('.total_sales_overdude').text(total);
}

function sales_payment(data){
    var ctx = document.getElementById("sales_payment");
    chart_sales_payment.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    total               = data.total;
    if(data.ID.length>0){
        no   = 0;
        name = '';
        date = '';
        $.each(data.ID, function(k,v){
            value = [];
            $.each(data.data, function(kk,vv){
                if($.inArray(vv.date, labels) == -1){
                    labels.push(vv.date);
                }
                if(v == vv.VendorID){
                    name = vv.vendorName;
                    value.push(parseFloat(vv.total));
                }
            });
            color = get_backgroundColor(k);
            d = {
                label           : name,
                data            : value,
                backgroundColor : color[0],
                borderColor     : color[1],
                borderWidth     : 1,
            };
            datasets.push(d);
        });
        var dt_column = data.data.length / data.ID.length;
        if (data.ID.length<10) {
            if(dt_column>=5 && data.ID.length < 4){
                count_loop = 3 - data.ID.length;
            }
            else if(dt_column<=5 && data.ID.length < 6){
                count_loop = 5 - data.ID.length;
            }
            else if(dt_column >= 5 && data.ID.left <6){
                count_loop = 5 - data.ID.length;    
            }else{
                count_loop = 10 - data.ID.length;
            }
            for (var i = 0; i < count_loop; i++) {
                d = {
                    label           : '',
                    backgroundColor : 'rgba(240, 248, 255, 0)',
                    borderColor     : 'rgba(240, 248, 255, 0)',
                    data            : 0,
                };
                datasets.push(d);       
            }
        }
    }else{
        labels.push("Data Not Found");
        value.push(100);
        backgroundColor.push('rgba(208,208,208,0.82');
        borderColor.push('rgba(208,208,208,0.82)');
        d = {
            label           : 'Data Not Found',
            data            : 0,
            backgroundColor : backgroundColor,
            borderColor     : borderColor,
            borderWidth     : 1,
        };
        datasets.push(d);
    }
    var data = {
        labels: labels,
        datasets : datasets,
    };

    chart_sales_payment = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            barValueSpacing: 20,
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                    }
                }]
            },
            legend: {
                display: false,
                position : "right",
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var labels  = data.datasets[tooltipItem.index].label;
                        var value   = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        var labels  = labels +" : "+txt_currency+" "+ number_format(value);
                        return labels;
                    }
                }
            }
        },
    
    });

    $('.total_sales_payment').text(total);
    var s = chart_sales_payment.generateLegend();
    $('#sales_payment-legend').html(s);

    $("#sales_payment-legend > ul > li").on("click",function(e){
        var index = $(this).index();
        $(this).toggleClass("strike");
        var ci = e.view.chart_sales_payment;
        var curr = ci.data.datasets[index]._meta;
        $.each(curr, function(k,v){
            curr = v;
            curr.hidden = !curr.hidden;
            ci.update();
        });
        
    })
}

function purchase_order(data){
    var ctx = document.getElementById("purchase_order");
    chart_purchase_order.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    total               = 0;

    if(data.length>0){
        $.each(data, function(k,v){
            if($.inArray(v.date, labels) == -1){
                labels.push(v.date);
            }
            total += parseFloat(v.qty);
            value.push(parseFloat(v.qty));
        });

        color = get_backgroundColor(0);
        d = {
            label           : "data purcahse",
            data            : value,
            backgroundColor : color[0],
            borderColor     : color[1],
            borderWidth     : 1,
        };
        datasets.push(d);
    }else{
        labels.push("Data Not Found");
        value.push(100);
        backgroundColor.push('rgba(208,208,208,0.82');
        borderColor.push('rgba(208,208,208,0.82)');
        d = {
            label           : 'Data Not Found',
            data            : 0,
            backgroundColor : backgroundColor,
            borderColor     : borderColor,
            borderWidth     : 1,
        };
        datasets.push(d);
    }

    var data = {
        labels: labels,
        datasets : datasets,
    };

    chart_purchase_order = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            barValueSpacing: 20,
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                    }
                }]
            },
            legend: {
                position : "top",
            }
        },
    
    });

    $('.total_purchase_qty').text(total);
}

function purchase_transaction(data){
    var ctx = document.getElementById("purchase_transaction");
    chart_purchase_transaction.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    total               = 0;

    if(data.length>0){
        $.each(data, function(k,v){
            if($.inArray(v.date, labels) == -1){
                labels.push(v.date);
            }
            total += parseFloat(v.total);
            value.push(parseFloat(v.total));
        });

        if (data.length<=5) {
            count_loop = 5 - data.length;
            for (var i = 0; i < count_loop; i++) {
                value.push(0);
                labels.push('');       
            }
        }

        color = get_backgroundColor(0);
        d = {
            label           : "data purcahse transaction",
            data            : value,
            backgroundColor : color[0],
            borderColor     : color[1],
            borderWidth     : 1,
        };
        datasets.push(d);

    }else{
        labels.push("Data Not Found");
        value.push(100);
        backgroundColor.push('rgba(208,208,208,0.82');
        borderColor.push('rgba(208,208,208,0.82)');
        d = {
            label           : 'Data Not Found',
            data            : 0,
            backgroundColor : backgroundColor,
            borderColor     : borderColor,
            borderWidth     : 1,
        };
        datasets.push(d);
    }

    var data = {
        labels: labels,
        datasets : datasets,
    };

    chart_purchase_transaction = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            barValueSpacing: 20,
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                    }
                }]
            },
            legend: {
                display: false,
                position : "top",
            }
        },
    
    });

    $('.total_purchase_transaction').text(total);
    var s = chart_purchase_transaction.generateLegend();
    $('#purchase_transaction-legend').html(s);

    $("#purchase_transaction-legend > ul > li").on("click",function(e){
        var index = $(this).index();
        $(this).toggleClass("strike");
        var ci = e.view.chart_purchase_transaction;
        var curr = ci.data.datasets[index]._meta;
        $.each(curr, function(k,v){
            curr = v;
            curr.hidden = !curr.hidden;
            ci.update();
        });
        
    })
}

function purchase_return(data){
    var ctx = document.getElementById("purchase_return");
    chart_purchase_return.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    total               = data.total;
    if(data.ID.length>0){
        no   = 0;
        name = '';
        date = '';
        $.each(data.ID, function(k,v){
            value = [];
            $.each(data.data, function(kk,vv){
                if($.inArray(vv.date, labels) == -1){
                    labels.push(vv.date);
                }
                if(v == vv.VendorID){
                    name = vv.vendorName;
                    value.push(parseFloat(vv.qty));
                }
            });
            color = get_backgroundColor(k);
            d = {
                label           : name,
                data            : value,
                backgroundColor : color[0],
                borderColor     : color[1],
                borderWidth     : 1,
            };
            datasets.push(d);
        });

    }else{
        labels.push("Data Not Found");
        value.push(100);
        backgroundColor.push('rgba(208,208,208,0.82');
        borderColor.push('rgba(208,208,208,0.82)');
        d = {
            label           : 'Data Not Found',
            data            : 0,
            backgroundColor : backgroundColor,
            borderColor     : borderColor,
            borderWidth     : 1,
        };
        datasets.push(d);
    }
    var data = {
        labels: labels,
        datasets : datasets,
    };

    chart_purchase_return = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            barValueSpacing: 20,
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                    }
                }]
            },
            legend: {
                position : "top",
            }
        },
    
    });

    $('.total_purchase_return').text(total);
}

function purchase_open(data){
    var ctx = document.getElementById("purchase_open");
    chart_purchase_open.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    total               = data.total;
    if(data.ID.length>0){
        no   = 0;
        name = '';
        date = '';
        $.each(data.ID, function(k,v){
            value = [];
            $.each(data.data, function(kk,vv){
                labels.push('');
                value.push(0);
                if($.inArray(vv.date, labels) == -1){
                    labels.push(vv.date);
                }
                if(v == vv.VendorID){
                    name = vv.vendorName;
                    value.push(parseFloat(vv.total));
                }
            });
            color = get_backgroundColor(k,"transaparant");
            d = {
                label           : name,
                data            : value,
                backgroundColor : color[0],
                borderColor     : color[1],
                borderWidth     : 1,
            };
            datasets.push(d);
        });

    }else{
        labels.push("Data Not Found");
        value.push(100);
        backgroundColor.push('rgba(208,208,208,0.82');
        borderColor.push('rgba(208,208,208,0.82)');
        d = {
            label           : 'Data Not Found',
            data            : 0,
            backgroundColor : backgroundColor,
            borderColor     : borderColor,
            borderWidth     : 1,
        };
        datasets.push(d);
    }
    var data = {
        labels: labels,
        datasets : datasets,
    };

    chart_purchase_open = new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            barValueSpacing: 20,
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                    }
                }]
            },
            legend: {
                display : false,
                position : "top",
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        if(tooltipItem.index>0){
                            var labels  = data.datasets[tooltipItem.datasetIndex].label;
                            var value   = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                            var labels  = labels +" : "+txt_currency+" "+ number_format(value);
                        }else{
                            var labels = '';
                        }
                        return labels;
                    }
                }
            }
        },
    
    });

    $('.total_purchase_open').text(total);
    var s = chart_purchase_open.generateLegend();
    var dt_chart = chart_purchase_open.data.datasets;
    s = '<ul>';
    for (var i = 0; i < dt_chart.length; i++) {
        d = dt_chart[i];
        backgroundcolor = 'background-color:'+d.borderColor+";";
        bordercolor     = 'border:1px solid '+d.borderColor+";";
        s += '<li><span style="'+backgroundcolor+bordercolor+'"></span>'+d.label+'</li>';
    }
    s += '</ul>';
    $('#purchase_open-legend').html(s);

    $("#purchase_open-legend > ul > li").on("click",function(e){
        var index = $(this).index();
        $(this).toggleClass("strike");
        var ci = e.view.chart_purchase_open;
        var curr = ci.data.datasets[index]._meta;
        $.each(curr, function(k,v){
            curr = v;
            curr.hidden = !curr.hidden;
            ci.update();
        });
        
    })
}

function purchase_overdude(data){
    var ctx = document.getElementById("purchase_overdude");
    chart_purchase_overdude.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    total               = data.total;
    if(data.ID.length>0){
        no   = 0;
        name = '';
        date = '';
        $.each(data.ID, function(k,v){
            value = [];
            $.each(data.data, function(kk,vv){
                if($.inArray(vv.date, labels) == -1){
                    labels.push(vv.date);
                }
                if(v == vv.VendorID){
                    name = vv.vendorName;
                    value.push(parseFloat(vv.total));
                }
            });
            color = get_backgroundColor(k);
            d = {
                label           : name,
                data            : value,
                backgroundColor : color[0],
                borderColor     : color[1],
                borderWidth     : 1,
            };
            datasets.push(d);
        });

    }else{
        labels.push("Data Not Found");
        value.push(100);
        backgroundColor.push('rgba(208,208,208,0.82');
        borderColor.push('rgba(208,208,208,0.82)');
        d = {
            label           : 'Data Not Found',
            data            : 0,
            backgroundColor : backgroundColor,
            borderColor     : borderColor,
            borderWidth     : 1,
        };
        datasets.push(d);
    }
    var data = {
        labels: labels,
        datasets : datasets,
    };

    chart_purchase_overdude = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            barValueSpacing: 20,
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                    }
                }]
            },
            legend: {
                position : "top",
            }
        },
    
    });

    $('.total_purchase_overdude').text(total);
}

function purchase_payment(data){
    var ctx = document.getElementById("purchase_payment");
    chart_purchase_payment.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    total               = data.total;
    if(data.ID.length>0){
        no   = 0;
        name = '';
        date = '';
        $.each(data.ID, function(k,v){
            value = [];
            $.each(data.data, function(kk,vv){
                if($.inArray(vv.date, labels) == -1){
                    labels.push(vv.date);
                }
                if(v == vv.VendorID){
                    name = vv.vendorName;
                    value.push(parseFloat(vv.total));
                }
            });
            color = get_backgroundColor(k);
            d = {
                label           : name,
                data            : value,
                backgroundColor : color[0],
                borderColor     : color[1],
                borderWidth     : 1,
            };
            datasets.push(d);
        });

    }else{
        labels.push("Data Not Found");
        value.push(100);
        backgroundColor.push('rgba(208,208,208,0.82');
        borderColor.push('rgba(208,208,208,0.82)');
        d = {
            label           : 'Data Not Found',
            data            : 0,
            backgroundColor : backgroundColor,
            borderColor     : borderColor,
            borderWidth     : 1,
        };
        datasets.push(d);
    }
    var data = {
        labels: labels,
        datasets : datasets,
    };

    chart_purchase_payment = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            barValueSpacing: 20,
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                    }
                }]
            },
            legend: {
                position : "right",
            }
        },
    
    });

    $('.total_purchase_payment').text(total);
}

function product_branch(data1,data){
    var ctx = document.getElementById("stock_branch");
    chart_stock_branch.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    
    // labels.push("HO");
    // value.push(data1);
    // color = get_backgroundColor(0);
    // d = {
    //     label           : 'HO',
    //     data            : value,
    //     backgroundColor : color[0],
    //     borderColor     : color[1],
    //     borderWidth     : 1,
    // };
    // datasets.push(d);
    
    if(data.length>0){
        no = 0;
        $.each(data, function(k,v){
            value.push(parseFloat(v.qty));
        });
        $.each(data, function(k,v){
            no += 1;
            color = get_backgroundColor(k);
            labels.push(v.branchName);

            val = [];    
            value.forEach(function (item, index, array) {
                  if(k == index){
                    val.push(parseFloat(v.qty));
                  }else{
                    val.push(parseFloat(0));
                  }
            });
            d = {
                label           : no+". "+v.branchName,
                data            : val,
                backgroundColor : color[0],
                borderColor     : color[1],
                borderWidth     : 1,
            };
            datasets.push(d);
        });
        if(data.length<=5){
            dt_column = 5 - data.length;
            for (var i = 0; i < dt_column; i++) {
                val = [];
                for (var ii = 0; ii < dt_column; ii++) {
                    val.push(0);
                }
                color = get_backgroundColor('non');
                d = {
                    label           : '',
                    data            : val,
                    backgroundColor : color[0],
                    borderColor     : color[1],
                    borderWidth     : 1,
                };
                datasets.push(d);
            }
        }
    }
    chart_stock_branch = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasets,
        },
        options: {
            barValueSpacing: 20,
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                    }
                }]
            },
            legend: {
                display:false,
                position : "top",
            }
        },
    });

    var s = chart_stock_branch.generateLegend();
    $('#stock_branch-legend').html(s);
    $("#stock_branch-legend > ul > li").on("click",function(e){
        var index = $(this).index();
        $(this).toggleClass("strike");
        var ci = e.view.chart_stock_branch;
        var curr = ci.data.datasets[index]._meta;
        $.each(curr, function(k,v){
            curr = v;
            curr.hidden = !curr.hidden;
            ci.update();
        });
        
    })
}

function store_receivable(data){
    var ctx = document.getElementById("store_receivable");
    chart_store_receivable.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    total               = data.total;
    if(data.ID.length>0){
        no   = 0;
        name = '';
        date = '';
        $.each(data.ID, function(k,v){
            value = [];
            $.each(data.data, function(kk,vv){
                if($.inArray(vv.date, labels) == -1){
                    labels.push(vv.date);
                }
                if(v == vv.VendorID){
                    name = vv.vendorName;
                    value.push(parseFloat(vv.total));
                }
            });
            color = get_backgroundColor(k);
            d = {
                label           : name,
                data            : value,
                backgroundColor : color[0],
                borderColor     : color[1],
                borderWidth     : 1,
            };
            datasets.push(d);
        });

    }else{
        labels.push("Data Not Found");
        value.push(100);
        backgroundColor.push('rgba(208,208,208,0.82');
        borderColor.push('rgba(208,208,208,0.82)');
        d = {
            label           : 'Data Not Found',
            data            : 0,
            backgroundColor : backgroundColor,
            borderColor     : borderColor,
            borderWidth     : 1,
        };
        datasets.push(d);
    }
    var data = {
        labels: labels,
        datasets : datasets,
    };

    chart_store_receivable = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            barValueSpacing: 20,
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                    }
                }]
            },
            legend: {
                position : "right",
            }
        },
    
    });

    $('.total_store_receivable').text(total);
}

function get_backgroundColor(index,transaparant){
    backgroundcolor = 'rgba(216,216,216,0.82)';
    bordercolor     = 'rgba(216,216,216,1)';
    
    // red
    if(index == 0){
        backgroundcolor = 'rgba(209,59,59,0.82)';
        bordercolor     = 'rgba(209,59,59,1)';
        if(transaparant){
            backgroundcolor = 'rgba(209,59,59,0)';
        }
    }
    // pink
    else if(index == 1){
        backgroundcolor = 'rgba(209,59,99,0.82)';
        bordercolor     = 'rgba(209,59,99,1)';
        if(transaparant){
            backgroundcolor = 'rgba(209,59,99,0)';
        }
    }

    // orange
    else if(index == 2){
        backgroundcolor = 'rgba(221,124,54,0.82)';
        bordercolor     = 'rgba(221,124,54,1)';
        if(transaparant){
            backgroundcolor = 'rgba(221,124,54,0)';
        }
    }
    // orange - yellow
    else if(index == 3){
        backgroundcolor = 'rgba(227,165,74,0.82)';
        bordercolor     = 'rgba(227,165,74,1)';
        if(transaparant){
            backgroundcolor = 'rgba(227,165,74,0)';
        }
    }
    // yellow
    else if(index == 4){
        backgroundcolor = 'rgba(255,205,86,0.82)';
        bordercolor     = 'rgba(255,205,86,1)';
        if(transaparant){
            backgroundcolor = 'rgba(255,205,86,0)';
        }
    }
    // green
    else if(index == 5){
        backgroundcolor = 'rgba(125,226,212,0.82)';
        bordercolor = 'rgba(125,226,212,1)';
        if(transaparant){
            backgroundcolor = 'rgba(125,226,212,0)';
        }
    }
    // blue
    else if(index == 6){
        backgroundcolor = 'rgba(59,154,230,0.82)';
        bordercolor = 'rgba(59,154,230,1)';
        if(transaparant){
            backgroundcolor = 'rgba(59,154,212,0)';
        }
    }else if(index == 'non'){
        backgroundcolor = 'rgba(240, 248, 255, 0)';
        bordercolor     = 'rgba(240, 248, 255, 0)';
    }
    

    data = [backgroundcolor,bordercolor];

    return data;
}

// inventory
function minimal_product(data){
    $('#table-minimal-stock tbody').empty();
    if(data.length>0){
        item = '';
        $.each(data,function(k,v){
            no = k + 1;

            item += '<tr>';
            item += '<td>'+no+'</td>';
            item += '<td>'+v.product_code+'</td>';
            item += '<td>'+v.product_name+'</td>';
            item += '<td>'+v.MinimumStock+'</td>';
            item += '<td>'+v.product_qty+'</td>';
            item += '</tr>';
        });
    }else{
        item = '<tr>';
        item += '<td colspan="5"><div class="text-center">Data Empty</div></td>'
        item += '</tr>';
    }
    $('#table-minimal-stock tbody').append(item);
}

function expired_module(data){
    if(data.module.length<=0 && data.devices.length<=0 && data.additional.length <= 0){
        $('.vexpire').remove();
    }else{
        $('.vexpire').removeClass("content-hide");
        status_modul_expired        = false;
        status_devices_expired      = false;
        status_additional_expire    = false;

        if(data.module.length>0){
           $.each(data.module,function(i,v){
                classx  = "";
                hari    = parseFloat(v.hari);
                if(parseFloat(v.hari) < 0){
                    hari = 'Expired';
                }
                no      = i+1;
                header  = '<tr style="background:#f9c1c1;color:#f53434;font-weight: 700">';
                header2 = '<tr style="background:#f9c1c1;color:#f53434;font-weight: 700">';
                item  = '<td>'+no+'</td>';
                item += '<td>'+v.module+'</td>';
                item += '<td>'+v.date+'</td>';
                item += '<td>'+hari+'</td>';
                item += '</tr>';
                $('#table_module tbody').append(header+item);
                if(parseFloat(v.hari) <=7){
                   $('#table_module_modal tbody').append(header2+item); 
                   status_modul_expired = true;  
                }
                
            }); 
        }else{
            $('.vexpire-module').remove();
        }

        if(data.devices.length>0){
            $.each(data.devices,function(i,v){
                hari = parseFloat(v.hari);
                if(parseFloat(v.hari) < 0){
                    hari = 'Expired';
                }
                no      = i+1;
                header  = '<tr style="background:#f9c1c1;color:#f53434;font-weight: 700">';
                header2  = '<tr style="background:#f9c1c1;color:#f53434;font-weight: 700">';
                item  = '<td>'+no+'</td>';
                item += '<td>'+v.name+'</td>';
                item += '<td>'+v.date+'</td>';
                item += '<td>'+hari+'</td>';
                item += '</tr>';
                $('#table_devices tbody').append(header+item);
                if(parseFloat(v.hari) <=7){
                    $('#table_devices_modal tbody').append(header2+item); 
                    status_devices_expired = true;   
                }
            })
        }else{
            $('.vexpire-devices').remove();
        }

        if(data.additional.length>0){
            $.each(data.additional,function(i,v){
                hari = parseFloat(v.hari);
                if(parseFloat(v.hari) < 0){
                    hari = 'Expired';
                }
                no      = i+1;
                header  = '<tr style="background:#f9c1c1;color:#f53434;font-weight: 700">';
                header2  = '<tr style="background:#f9c1c1;color:#f53434;font-weight: 700">';
                item  = '<td>'+no+'</td>';
                item += '<td>'+v.name+'</td>';
                item += '<td>'+v.date+'</td>';
                item += '<td>'+hari+'</td>';
                item += '</tr>';
                $('#table_additional tbody').append(header+item);
                if(parseFloat(v.hari) <=7){
                    $('#table_additional_modal tbody').append(header2+item); 
                    status_additional_expire = true;   
                }
            })
        }else{
            $('.vexpire-additional').remove();
        }

        if(!status_modul_expired){$('.vexpire-module-modal').remove();}
        if(!status_devices_expired){$('.vexpire-devices-modal').remove();}
        if(!status_additional_expire){$('.vexpire-additional-modal').remove();}
        if(status_modul_expired || status_devices_expired || status_additional_expire){$('#modal-dashboard-expire').modal("show");}
    }
    
}
// inventory

// account watchlist
function account_watchlist(data){
    $('#table_account_watchlist tbody').empty();
    item = '';
    if(data.length>0){
        $.each(data,function(k,v){
            no = k + 1;
            item += '<tr>';
            item += '<td>'+no+'</td>';
            item += '<td>'+v.name+'</td>';
            item += '<td>'+v.total+'</td>';
            item += '<tr>';
        })
    }else{
        item += '<tr>';
        item += '<td colspan="3">Data not found</td>';
        item += '</tr>';
    }
    $('#table_account_watchlist tbody').append(item);
}

// loss profit
function set_data_loss_profit(data){
	var ctx = document.getElementById("loss_profit");
    chart_loss_profit.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    value_income        = [];
    value_expense       = [];
    total_net_profit 	= data.total;

    if(data.data.length>0){
    	labels.push('');
        value_income.push(0);
        value_expense.push(0);

        $.each(data.date,function(i,v){
        	labels.push(v);
        });

        $.each(data.data,function(i,v){
            if(v.ID == 'I'){
            	value_income.push(parseFloat(v.Total));
            }else if(v.ID == 'II'){
            	value_expense.push(parseFloat(v.Total));
            }
        });

        color = get_backgroundColor(1);
        d = {
            label           : language_app.lb_income,
            data            : value_income,
            backgroundColor : color[0],
            borderColor     : color[1],
            borderWidth     : 1,
        };
        datasets.push(d);

        color = get_backgroundColor(6);
        d = {
            label           : language_app.lb_expense,
            data            : value_expense,
            backgroundColor : color[0],
            borderColor     : color[1],
            borderWidth     : 1,
        };
        datasets.push(d);
    }else{
    	labels.push("Data Not Found");
        value.push(100);
        backgroundColor.push('rgba(208,208,208,0.82');
        borderColor.push('rgba(208,208,208,0.82)');
        d = {
            label           : 'Data Not Found',
            data            : 0,
            backgroundColor : backgroundColor,
            borderColor     : borderColor,
            borderWidth     : 1,
        };
        datasets.push(d);
    }

    var data = {
        labels: labels,
        datasets : datasets,
    };

    chart_loss_profit = new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            barValueSpacing: 20,
            legend: {
                display : false,
                position : "right",
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        
                        if(tooltipItem.index>0){
                            var labels  = data.datasets[tooltipItem.datasetIndex].label;
                            var value   = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                            var labels  = labels +" : "+txt_currency+" "+ number_format(value);
                        }else{
                            var labels = '';
                        }
                        return labels;
                    }
                }
            }
        },
    
    });

    $('.total_net_profit').text(total_net_profit);
    var s = chart_loss_profit.generateLegend();
    var dt_chart = chart_loss_profit.data.datasets;
    s = '<ul>';
    for (var i = 0; i < dt_chart.length; i++) {
        d = dt_chart[i];
        backgroundcolor = 'background-color:'+d.borderColor+";";
        bordercolor     = 'border:1px solid '+d.borderColor+";";
        s += '<li><span style="'+backgroundcolor+bordercolor+'"></span>'+d.label+'</li>';
    }
    s += '</ul>';
    $('#loss_profit-legend').html(s);
    $("#loss_profit-legend > ul > li").on("click",function(e){
        var index = $(this).index();
        $(this).toggleClass("strike");
        var ci = e.view.chart_loss_profit;
        var curr = ci.data.datasets[index]._meta;
        $.each(curr, function(k,v){
            curr = v;
            curr.hidden = !curr.hidden;
            ci.update();
        });
        
    })
}

// balance sheet
function set_balance_sheet(data){
    var ctx = document.getElementById("balance_sheet");
    chart_balance_sheet.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    value_aset          = [];
    value_liabilities   = [];
    total_net_balace_sheet = data.total;

    if(data.data.length>0){
        labels.push('');
        value_aset.push(0);
        value_liabilities.push(0);

        $.each(data.date,function(i,v){
            labels.push(v);
        });

        $.each(data.data,function(i,v){
            if(v.ID == 1){
                value_aset.push(parseFloat(v.Total));
            }else if(v.ID == 2){
                value_liabilities.push(parseFloat(v.Total));
            }
        });

        color = get_backgroundColor(1);
        d = {
            label           : language_app.lb_aset,
            data            : value_aset,
            backgroundColor : color[0],
            borderColor     : color[1],
            borderWidth     : 1,
        };
        datasets.push(d);

        color = get_backgroundColor(6);
        d = {
            label           : language_app.lb_liabilities,
            data            : value_liabilities,
            backgroundColor : color[0],
            borderColor     : color[1],
            borderWidth     : 1,
        };
        datasets.push(d);
    }else{
        labels.push("Data Not Found");
        value.push(100);
        backgroundColor.push('rgba(208,208,208,0.82');
        borderColor.push('rgba(208,208,208,0.82)');
        d = {
            label           : 'Data Not Found',
            data            : 0,
            backgroundColor : backgroundColor,
            borderColor     : borderColor,
            borderWidth     : 1,
        };
        datasets.push(d);
    }

    var data = {
        labels: labels,
        datasets : datasets,
    };

    chart_balance_sheet = new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            barValueSpacing: 20,
            legend: {
                display : false,
                position : "right",
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        
                        if(tooltipItem.index>0){
                            var labels  = data.datasets[tooltipItem.datasetIndex].label;
                            var value   = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                            var labels  = labels +" : "+txt_currency+" "+ number_format(value);
                        }else{
                            var labels = '';
                        }
                        return labels;
                    }
                }
            }
        },
    
    });

    $('.total_net_balace_sheet').text(total_net_balace_sheet);
    var s = chart_balance_sheet.generateLegend();
    var dt_chart = chart_balance_sheet.data.datasets;
    s = '<ul>';
    for (var i = 0; i < dt_chart.length; i++) {
        d = dt_chart[i];
        backgroundcolor = 'background-color:'+d.borderColor+";";
        bordercolor     = 'border:1px solid '+d.borderColor+";";
        s += '<li><span style="'+backgroundcolor+bordercolor+'"></span>'+d.label+'</li>';
    }
    s += '</ul>';
    $('#balance_sheet-legend').html(s);
    $("#balance_sheet-legend > ul > li").on("click",function(e){
        var index = $(this).index();
        $(this).toggleClass("strike");
        var ci = e.view.chart_balance_sheet;
        var curr = ci.data.datasets[index]._meta;
        $.each(curr, function(k,v){
            curr = v;
            curr.hidden = !curr.hidden;
            ci.update();
        });
        
    })
}

function selected_item(ul_id,li_class,page){
    $(ul_id+" li").removeClass('li-active');
    $(ul_id+" "+li_class).addClass("li-active");
    if(page != "non"){
        tagdata = $(ul_id).data();
        console.log(tagdata);
        page = '';
        p1   = '';
        if(tagdata.page){
            page = tagdata.page;
            p1   = tagdata.module;
        }
        load_data(page);
    }
}