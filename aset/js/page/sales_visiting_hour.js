var host            = window.location.origin+'/';
var url             = window.location.href;
$(document).ready(function() {
    page_data = $(".page-data").data();
    page_name = page_data.page_name; 

    $(".date2").datepicker( {
        autoclose:true,
        orientation: 'auto top',
        format: "yyyy-mm",
        viewMode: "months", 
        minViewMode: "months"
    });
});

//2018-05-21 MW
//Company Select
$("#company").change(function()
{
  value = $(this).val();
  $('#Sales').find('option').remove().end();
  $('#Sales').append('<option value="all">Pilih Sales</option>');
  if (value == "all") {
    $('.v_sales').hide();
  }else{
    $('.v_sales').show();
    sp_list_sales(value);
  }
});

function sp_list_sales(id){
  $.ajax({
    url : host+"branch/sp_list_sales/"+id,
    type: "GET",
    dataType: "JSON",
    success: function(data)
    {
      $('#Sales').append(data.data);
    },
    error: function (jqXHR, textStatus, errorThrown)
    {
      alert('Error get data from ajax');
    }
  });
}

function load_data(){
    data  = {
        StartDate  : $("[name=StartDate]").val(),
        Sales      : $("[name=Sales]").val(),
        company    : $("[name=company]").val(),
      }
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty();
    $.ajax({
        url : host+"report/get_sales_visiting_hour",
        type: "POST",
        dataType: "JSON",
        data: data,
        success: function(data)
        {
          console.log(data);
          if(data.status){
            total_hour(data.sales_visiting_hour, data.comparison);
          }else{
            alert(data.error_string[0])
          }    
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          alert('Error get data from ajax');
        }
    });
}

function total_hour(data_sales_visit, data_comparison){
    var total_hour = [];
    var comparison = [];
    $.each(data_sales_visit,function(i,v){
        var year    = parseInt(v.year);
        var month   = parseInt(v.month);
        var day     = parseInt(v.day)
        var hour    = {x: new Date(year, month-1, day), y: parseInt(v.total)};
        
        dd = {x: new Date(year, month-1, day), y: parseInt(data_comparison[i])};

        total_hour.push(hour);
        comparison.push(dd);
    });
    console.log("comparison :", comparison);

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
            text: "Period "+$('[name=StartDate]').val(),
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
            name: "Total Duration",
            showInLegend: true,
            color: "#62a8ea",
            dataPoints: total_hour
        }//,
        // {
        //     type: "line",
        //     lineDashType: "dash",
        //     name: "CheckIn CheckOut Difference",
        //     showInLegend: true,
        //     color: "#f96868",
        //     dataPoints: comparison
        // }
        ]
    };
    $("#lineContainer").CanvasJSChart(options);
}
function getTime(s){
    var h = Math.floor(s/3600); //Get whole hours
    s -= h*3600;
    var m = Math.floor(s/60); //Get remaining minutes
    s -= m*60;
    return h+":"+(m < 10 ? '0'+m : m); //zero padding on minutes and seconds
}
function toogleDataSeries(e){
    if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
        e.dataSeries.visible = false;
    } else{
        e.dataSeries.visible = true;
    }
    e.chart.render();
}