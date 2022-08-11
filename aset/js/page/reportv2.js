//note : kalau lu gak paham bertanya kepada yang bisa selamat mencoba
//note :  tanya sama yang bikinnya lah tarif 100rb sekali tanya
var host    = window.location.origin+'/';
var url     = window.location.href;
var save_method; //for save method string
var table;
var page;
var start_date,end_date;
var url_table;
$(document).ready(function() {
  date();
  v           = $(".page-data").data();
  page        = v.page;
  start_date  = v.start_date;
  end_date    = v.end_date;
  $("[name=start_date]").val(start_date);
  $("[name=end_date]").val(end_date);
  filter_table();
});
function load_page(page = ""){
  report      = $("[name=report]").val();
  start_date  = $("[name=start_date]").val();
  end_date    = $("[name=end_date]").val();
  search      = $("[name=search]").val();

  $("[name=search]").val("");
  if(page == "report"){
  	item = "";
    item = '<option value="all">All</option>';
  	if(report == "serial_number"){
    item += '<option value="good_receipt">Good Receipt</option>';  		
    item += '<option value="sale">Sale</option>';  		
    item += '<option value="return">Return</option>';  		
    item += '<option value="mutation">Mutation</option>';  		  		
    }
    else {
    item += '<option value="date">Date</option>';  		
  	}

    if(report == "good_receipt"){
      item += '<option value="gr_code">GR Code</option>';
      item += '<option value="receipt_name">Receipt Name</option>';
      item += '<option value="product_name">Product Name</option>';
    } else if(report == "mutation"){
      item += '<option value="mutation_code">Mutation Code</option>';
      item += '<option value="mutation_from">Mutation From</option>';
      item += '<option value="mutation_to">Mutation To</option>';
    } else if(report == "payment"){
      item += '<option value="payment_code">Payment Code</option>';
      item += '<option value="store_name">Store Name</option>';
      item += '<option value="sales_code">Sales Code</option>';
    } else if(report == "return"){
      item += '<option value="return_code">Return Code</option>';
      item += '<option value="vendor_name">Vendor Name</option>';
      item += '<option value="product_name">Product Name</option>';
    } else if(report == "account_receive"){
      item += '<option value="store_name">Store Name</option>';
      item += '<option value="ar_code">AR Code</option>';
    } else if(report == "selling"){
      item += '<option value="store_name">Store Name</option>';
      item += '<option value="product_name">Product Name</option>';
    } else if(report == "distributor_selling"){
      item += '<option value="selling">Selling</option>';
    } else if(report == "distributor_outstanding"){
      item += '<option value="delivery">Delivery</option>';
    } else if(report == "stock"){
      item += '<option value="store_name">Store Name</option>';
      item += '<option value="category_name">Category Name</option>';
      item += '<option value="product_name">Product Name</option>';
    }
    $("[name=group] option").remove();
    $("[name=group]").append(item);
    $(".v_group").show(300);
    if(report == "stock"){
      $(".v_group").hide(300);
    }
  }
  group       = $("[name=group]").val();
  // load page---------------------------------------------
  url_table   = host + "report/table/"+report+"?cetak=print";
  $(".table-data").load(url_table,{report:report,group:group,start_date:start_date,end_date:end_date,search:search},function(){
    // search_table();
  });
  console.log(url_table);
}
function search_table()
{
  load_page("group");
}
function xsearch_table()
{
  report      = $("[name=report]").val();
  start_date  = $("[name=start_date]").val();
  end_date    = $("[name=end_date]").val();
  search      = $("[name=search]").val();
  if(report != "none"){
    filter_table({report:report});
  }
}
function reload_table()
{
    // filter_table("reset");
    table.ajax.reload(null,false); //reload datatable ajax
}
function preview_detail(id)
{
  console.log(id);
}

function filter_table(v = "")
{
  url = "";
  if(v.report == "good_receipt"){
    url = "report/good_receipt_list";
  } else if(v.report == "mutation"){
    url = "report/mutation_list";
  } else if(v.report == "payment"){
    url = "report/payment_list";
  } else if(v.report == "return"){
    url = "report/return_list";
  } else if(v.report == "account_receive"){
    url = "report/account_receive_list";
  } else if(v.report == "selling"){
    url = "report/selling_list";
  } else if(v.report == "stock"){
    url = "report/stock_list";
  } else if(v.report == "serial_number"){
    url = "report/serial_number_list";
  }
  } else if(v.report == "distributor_selling"){
    url = "report/distributor_selling_list";
  }else if(v.report == "distributor_outstanding"){
    url = "report/distributor_outstanding_list";
  }
  data  = {
    report     : $("[name=report]").val(),
    start_date : $("[name=start_date]").val(),
    end_date   : $("[name=end_date]").val(),
    group      : $("[name=group]").val(),
    search      : $("[name=search]").val(),
  }
  console.log(url);
  console.log(data);
  table = $('#table').DataTable({
    paging: false,
    info:false,
    searching: false,
    destroy: true,
    processing: true, //Feature control the processing indicator.
    serverSide: true, //Feature control DataTables' server-side processing mode.
    // "order": [], //Initial no order.
    ajax: {
        url: url,
        type: "POST",
        data: data,
    },
    columnDefs: [{
        targets: [0,1,-1], //last column
        orderable: false, //set not orderable
    },],
  });
}

function cetak(status){
  url     = "";
  report  =  $("[name=report]").val();
  
  if(report == "none"){
    alert("please select report");
  } else {
    url     = host + "report/cetak/"+report+"?cetak="+status;
    if(status == "excell"){
    url     = host + "report/"+report+"_excell";
    }
    $('form').attr('action', url);
    if(status == "print" || status == "excell"){
      $('form').attr('target', '_blank');
    }
    $("form").submit();
    $.post(url, {variable: "tes"});

  }
}
function date(){
    container = $('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
    $(".date").datepicker({
        format: 'yyyy-mm-dd',
        container: container,
        todayHighlight: true,
        autoclose: true,
    });
}