var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host 			= window.location.origin+'/pipesys_qa/';
var url 			= window.location.href;

//dashboard
var url_dashboard        = host + "dashboard/dashboard/";
var period = "";
var chart_purchase_transaction,chart_purchase_return,chart_goodreceipt;
//end dashboard
$(window).load(function(){
    page_data   = $(".page-data").data();
    app         = page_data.app;
    ap          = page_data.ap;
});

$(document).ready(function() {
	
    // purchase
    selected_item('#ul-purchase-order', st_period_type,'non');
    selected_item('#ul-purchase-transaction', st_period_type,'non');
    selected_item('#ul-purchase-return', st_period_type,'non');
    selected_item('#ul-purchase-open', st_period_type,'non');
    selected_item('#ul-purchase-overdude', st_period_type,'non');
    selected_item('#ul-purchase-payment', st_period_type,'non');

    // penerimaan
    selected_item('#ul-goodreceipt', st_period_type,'non');

    create_chart();
});

//dashboard
function create_chart(){
    var ctx = document.getElementById("goodreceipt");
    chart_goodreceipt = new Chart(ctx,{});

    var ctx = document.getElementById("purchase_return");
    chart_purchase_return = new Chart(ctx,{});

    var ctx = document.getElementById("purchase_transaction");
    chart_purchase_transaction = new Chart(ctx,{});

}

function load_data(page = "")
{
    Check       = $("[name=Check]:checked").val();
    StartDate   = $("[name=fStartDate]").val();
    EndDate     = $("[name=fEndDate]").val();

    // untuk data diagram

    purchase_transactionx   = $('#ul-purchase-transaction .li-active').data();
    purchase_returnx        = $('#ul-purchase-return .li-active').data();

    goodreceiptx            = $('#ul-goodreceipt .li-active').data();

    data_post   = {
        Check           : Check,
        StartDate       : StartDate,
        EndDate         : EndDate,
        goodreceipt     : goodreceiptx.type,
        purchase_transaction : purchase_transactionx.type,
        purchase_return      : purchase_returnx.type,
        page                 : page,
    };
    // if(StartDate > EndDate){
    //     alert("date from must less than date to");
    //     return;
    // }
    $.ajax({
        url : url_dashboard,
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            
            $('.is-loading').removeClass("is-loading");
            
            if(page == "purchase_return"){
                purchase_return(data.purchase_return);
            }else if(page == "purchase_transaction"){
                purchase_transaction(data.purchase_transaction);
            }else if(page == "goodreceipt"){
                goodreceipt(data.goodreceipt);
            }else{
                set_data_purchase(data);
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
function set_data_purchase(data){
    goodreceipt(data.goodreceipt);
    purchase_return(data.purchase_return);
    purchase_transaction(data.purchase_transaction);
}

function goodreceipt(data){
    var ctx = document.getElementById("goodreceipt");
    chart_goodreceipt.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    total               = 0;
    total_rp            = 0;

    if(data.length>0){
        $.each(data, function(k,v){
            if($.inArray(v.date, labels) == -1){
                labels.push(v.date);
            }
            total += parseFloat(v.total_qty);
            total_rp += parseFloat(v.total_payment);
            value.push(parseFloat(v.total));
        });

        color = get_backgroundColor(0);
        d = {
            label           : "data goodreceipt transaction",
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

    chart_goodreceipt = new Chart(ctx, {
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

    $('.total_goodreceipt').text(number_format(total,"qty")+" "+language_app.lb_qty);
    $('.total_receipt_rp').text(txt_currency+" "+number_format(total_rp,"currency"));
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
    total_rp            = 0;

    if(data.length>0){
        $.each(data, function(k,v){
            if($.inArray(v.date, labels) == -1){
                labels.push(v.date);
            }
            total += parseFloat(v.total_qty);
            total_rp += parseFloat(v.total_payment);
            value.push(parseFloat(v.total));
        });

        color = get_backgroundColor(0);
        d = {
            label           : "data purchase transaction",
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
                position : "top",
            }
        },
    
    });

    $('.total_purchase_transaction').text(number_format(total,"qty")+" "+language_app.lb_qty);
    $('.total_purchase_rp').text(txt_currency+" "+number_format(total_rp,"currency"));
}

function purchase_return(data){
    var ctx = document.getElementById("purchase_return");
    chart_purchase_return.destroy();
    labels              = [];
    value               = [];
    backgroundColor     = [];
    borderColor         = [];
    datasets            = [];
    total               = parseFloat(data.total);
    total_rp            = 0;
    if(data.ID.length>0){
        no   = 0;
        name = '';
        date1 = '';
        $.each(data.ID, function(k,v){
            value = [];
            $.each(data.data, function(kk,vv){
                if($.inArray(vv.date, labels) == -1){
                    labels.push(vv.date);
                }
                if(v == vv.VendorID){
                    name = vv.vendorName;
                    value.push(parseFloat(vv.qty));
                    total_rp += parseFloat(vv.total_payment);
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

    $('.total_purchase_return').text(number_format(total,"qty")+" "+language_app.lb_qty);
    $('.lb_total_return_rp').text(txt_currency+" "+number_format(total_rp,"currency"));
}

function get_backgroundColor(index,transaparant){
    backgroundcolor = 'rgba(216,216,216,0.82)';
    bordercolor     = 'rgba(216,216,216,1)';
    
    // biru
    if(index == 0){
        backgroundcolor = 'rgba(54,162,235,0.82)';
        bordercolor     = 'rgba(54,162,235,1)';
        if(transaparant){
            backgroundcolor = 'rgba(54,162,235,0)';
        }
    }
    // hijau
    else if(index == 1){
        backgroundcolor = 'rgba(70,190,138,0.82)';
        bordercolor     = 'rgba(70,190,138,1)';
        if(transaparant){
            backgroundcolor = 'rgba(70,190,138,0)';
        }
    }
    // red
    else if(index == 2){
        backgroundcolor = 'rgba(255,99,132,0.82)';
        bordercolor     = 'rgba(255,99,132,1)';
        if(transaparant){
            backgroundcolor = 'rgba(255,99,132,0)';
        }
    }
    // orange
    else if(index == 3){
        backgroundcolor = 'rgba(227,165,74,0.82)';
        bordercolor     = 'rgba(227,165,74,1)';
        if(transaparant){
            backgroundcolor = 'rgba(227,165,74,0)';
        }
    }
    // kuning
    else if(index == 4){
        backgroundcolor = 'rgba(255,205,86,0.82)';
        bordercolor     = 'rgba(255,205,86,1)';
        if(transaparant){
            backgroundcolor = 'rgba(255,205,86,0)';
        }
    }
    

    data = [backgroundcolor,bordercolor];

    return data;
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

function selected_item(ul_id,li_class,page){
    $(ul_id+" li").removeClass('li-active');
    $(ul_id+" "+li_class).addClass("li-active");
    if(page != "non"){
        tagdata = $(ul_id).data();
        page = '';
        if(tagdata.page){
            page = tagdata.page;
        }
        load_data(page);
    }
}