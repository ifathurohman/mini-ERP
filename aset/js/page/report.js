//note : kalau lu gak paham bertanya kepada yang bisa selamat mencoba
//note :  tanya sama yang bikinnya lah tarif 100rb sekali tanya
//20190509
var host    = window.location.origin+'/';
var url     = window.location.href;
var save_method; //for save method string
var table;
var page;
var start_date,end_date;
var url_table;
var app;
var report;
var url_page;

var loadreport = ['balance_sheet','age_off_debt','age_off_credit','loss_and_profit','ledger','stock_opname','stock_receipt','stock_issue','stock1'];

$(document).ready(function() {
  date();
  $(".v_customer, .v_tanggal, .v_button, .v_product, .v_group, .v_tax, .v_sales, .v_sales1, .v_search, .v_deliveryno, .v_sellno, .v_payno,\
    .v_branch, .v_vendor, .v_purchaseno, .v_purchaseno, .v_city, .div-loader\
    ").hide();
  v           = $(".page-data").data();
  page        = v.page;
  report      = v.report;
  start_date  = v.start_date;
  end_date    = v.end_date;
  app         = v.app;
  url_page    = v.url;

  $("[name=start_date]").val(start_date);
  $("[name=end_date]").val(end_date);
  filter_table();
  if(app == "salespro"){
    $(".v_group").hide();
  }
  if(report != "none"){
    $("[name=report]").val(report);
    load_page("report",report);
  }
  $("[name=group]").change(function(){
    val = $(this).val();
    check_type(val);
  });

  if(url_page == "report_finance"){
    $('.vstock').remove();
  }else{
    $('.vfinance').remove();
  }

});

function check_type(val){
  report = $("[name=report]").val();

  $('.customer_select').select2().select2('val',['all']);
  $('.product_select').select2().select2('val',['all']);
  $('.branch_select').select2().select2('val',['all']);
  $('.vendor_select').select2().select2('val',['all']);
  if(report == "distributor_selling"){
    $('.city_select').select2().select2('val',[0]);
    if (val == 'all') {
      $(".vparent_product, .v_city, .v_customer, .v_branch").show(300);
    }
    else if(val == "selling"){
      $(".v_city, .v_customer, .v_branch").show(300);
      $(".vparent_product").hide(300);
    }else if(val == "product_name"){
      $(".vparent_product").show(300);
      $('.v_city, .v_customer, .v_branch').hide(300);
    }else if(val == "vendor"){
      $(".v_customer").show(300);
      $('.v_city, .vparent_product, .v_branch').hide(300);
    }else if(val == 'store'){
      $('.v_branch').show(300);
      $('.v_city, .vparent_product, .v_customer').hide(300);
    }
  }

  else if(report == "outstanding_delivery"){
		report = $("[name=report]").val();
    if (val == 'all') {
      $(".vparent_product, .v_customer, .v_branch").show(300);
    }else if(val == "product_name"){
      $(".vparent_product").show(300);
      $(".v_customer, .v_branch").hide(300);
    }else if(val == "store"){
      $('.v_branch').show(300);
      $(".vparent_product, .v_customer").hide(300);
    }else{
      $(".v_customer, .v_branch").show(300);
      $(".vparent_product").hide(300);
    }
	}

  else if(report == "return_selling"){
    $(".vparent_product").hide(300);
      report = $("[name=report]").val();
      if (val == 'all') {
        $(".vparent_product, .v_branch").show(300);
      }
      if (val == 'selling') {
        $(".vparent_product, .v_branch").hide(300);
      }
  }

  else if(report == "return_distributor"){
    $(".vparent_product").hide(300);
    report = $("[name=report]").val();
      if (val == 'all') {
          $(".vparent_product").show(300);
          // Position = $(this).val();
      }
      else{
          $(".vparent_product").hide(300);
          $('.customer_select').select2().select2('val',['all']);
          $('.product_select').select2().select2('val',['all']);
      }
      if (val == 'selling') {
          $(".vparent_product").hide(300);
          $('.product_select').select2().select2('val',['all']);
      }
      else{
          $('.customer_select').select2().select2('val',['all']);
      }
  }

  else if(report == "invoice_customer"){
    $(".vparent_product").hide(300);
    report = $("[name=report]").val();
      if (val == 'all') {
          $(".vparent_product").hide(300);
          // Position = $(this).val();
      }
      else{
          $(".vparent_product").hide(300);
          $('.customer_select').select2().select2('val',['all']);
          $('.product_select').select2().select2('val',['all']);
      }
      if (val == 'transaction') {
          $(".vparent_product").hide(300);
          $('.product_select').select2().select2('val',['all']);
      }
      else{
          $('.customer_select').select2().select2('val',['all']);
      }
  }

  else if(report == "invoice_vendor"){
    $(".vparent_product").hide(300);
    report = $("[name=report]").val();
      if (val == 'all') {
          $(".vparent_product").hide(300);
          // Position = $(this).val();
      }
      else{
          $(".vparent_product").hide(300);
          $('.vendor_select').select2().select2('val',['all']);
          $('.product_select').select2().select2('val',['all']);
      }
      if (val == 'transaction') {
          $(".vparent_product").hide(300);
          $('.product_select').select2().select2('val',['all']);
      }
      else{
          $('.vendor_select').select2().select2('val',['all']);
      }
  }

  else if(report == "purchase"){
    if(val == "gr_purchase"){
      $('.v_vendor, .v_branch').show(300);
      $('.v_product').hide(300);
    }else if(val == "all"){
      $('.v_vendor, .v_branch, .v_product').show(300);
    }else if(val == "product_name"){
      $('.v_product').show(300);
      $('.v_vendor, .v_branch').hide(300);
    }else if(val == "vendor"){
      $('.v_vendor').show(300);
      $('.v_product, .v_branch').hide(300);
    }else if(val == "store"){
      $('.v_branch').show(300);
      $('.v_product, .v_vendor').hide(300);
    }
  }

  else if(report == "sales_book"){
    $(".vparent_product").hide(300);
    report = $("[name=report]").val();
    $('.customer_select').select2().select2('val',['all']);
  }

  else if(report == "purchase_book"){
    $(".vparent_product").hide(300);
    report = $("[name=report]").val();
    $('.customer_select').select2().select2('val',['all']);
    $('.purchaseno_select').select2().select2('val',['all']);
  }

  else if(report == "age_off_debt"){
    $(".vparent_product").hide(300);
    report = $("[name=report]").val();
    $('.customer_select').select2().select2('val',['all']);
  }

  else if(report == "correction_ap"){
    $(".vparent_product").hide(300);
    report = $("[name=report]").val();
    $('.customer_select').select2().select2('val',['all']);
  }

  else if(report == "correction_ar"){
    $(".vparent_product").hide(300);
    report = $("[name=report]").val();
    $('.customer_select').select2().select2('val',['all']);
  }

  else if(report == "debtors_account"){
    $(".vparent_product").hide(300);
    report = $("[name=report]").val();
    $('.customer_select').select2().select2('val',['all']);
  }

  else if(report == "creditors_account"){
    $(".vparent_product").hide(300);
    report = $("[name=report]").val();
    $('.customer_select').select2().select2('val',['all']);
  }

  else if(report == "saldo_receivable"){
    $(".vparent_product").hide(300);
    report = $("[name=report]").val();
      if (val == 'all') {
          $(".vparent_product").hide(300);
          $(".v_payno").hide(300);
      }
      else{
          $('.customer_select').select2().select2('val',['all']);
      }
      if (val == 'transaction') {
          $(".vparent_product").hide(300);
          $(".v_payno").hide(300);
      }
      else{
          $('.customer_select').select2().select2('val',['all']);
      }
  }

  else if(report == "saldo_ap"){
    $(".vparent_product").hide(300);
    report = $("[name=report]").val();
      if (val == 'all') {
          $(".vparent_product").hide(300);
          $(".v_payno").hide(300);
      }
      else{
          $('.customer_select').select2().select2('val',['all']);
      }
      if (val == 'transaction') {
          $(".vparent_product").hide(300);
          $(".v_payno").hide(300);
      }
      else{
          $('.customer_select').select2().select2('val',['all']);
      }
  }

  else if(report == "good_receipt"){
    if(val == "gr_code"){
      $('.v_vendor, .v_branch').show(300);
      $('.v_product').hide(300);
    }else if(val == "all"){
      $('.v_vendor, .v_branch, .v_product').show(300);
    }else if(val == "product_name"){
      $('.v_product').show(300);
      $('.v_vendor, .v_branch').hide(300);
    }else if(val == "receipt_name"){
      $('.v_vendor').show(300);
      $('.v_product, .v_branch').hide(300);
    }else if(val == "store"){
      $('.v_branch').show(300);
      $('.v_product, .v_vendor').hide(300);
    }else if(val == "purchase_code"){
      $('.v_product, .v_vendor, .v_branch').hide(300);
    }
  }

  else if(report == "return"){
    if(val == "return_code"){
      $('.v_vendor, .v_branch').show(300);
      $('.v_product').hide(300);
    }else if(val == "all"){
      $('.v_vendor, .v_branch, .v_product').show(300);
    }else if(val == "product_name"){
      $('.v_product').show(300);
      $('.v_vendor, .v_branch').hide(300);
    }else if(val == "vendor_name"){
      $('.v_vendor').show(300);
      $('.v_product, .v_branch').hide(300);
    }else if(val == "store"){
      $('.v_branch').show(300);
      $('.v_product, .v_vendor').hide(300);
    }
  }

  else if(report == "selling"){
    if(val == "all"){
      $('.v_product, .v_branch').show(300);
    }else if(val == "store_name"){
      $('.v_branch').show(300);
      $('.v_product').hide(300);
    }else if(val == "product_name"){
      $('.v_product').show(300);
      $('.v_branch').hide(300);
    }
  }

  else if(report == "correction_stock"){
    if(val == "all"){
      $('.v_product').show(300);
    }else{
      $('.v_product').hide(300);
    }
  }

  else if(report == "stock_opname" || report == "stock_receipt" || report == "stock_issue"){
    if(val == "all"){
      $('.v_branch, .v_product').show(300);
    }else if(val == "transaction" || val == "store"){
      $('.v_branch').show(300);
      $('.v_product').hide(300);
    }
  }

  else if(report == "stock1"){
    if(val == 'transaction' || val == "store"){
      $('.v_branch').show(300);
    }else{
      $('.v_branch').hide(300);
    }
  }
}

function load_page(page = ""){
  $('.customer_select').select2().select2('val',['all']);
  $('.product_select').select2().select2('val',['all']);
  $('.branch_select').select2().select2('val',['all']);
  $('.vendor_select').select2().select2('val',['all']);

  report = $("[name=report]").val();
  $("[name=search]").val("");
  $(".div-loader").show();
  if(page == "report"){
    item = "";
    item += '<option value="all">'+language_app.lb_all+'</option>';
    if(report == "serial_number"){
      // item += '<option value="all">'+language_app.lb_all+'</option>'; 
      item += '<option value="good_receipt">'+language_app.lb_goodrc+'</option>';
      item += '<option value="sale">'+language_app.lb_sale+'</option>';
      item += '<option value="return">'+language_app.lb_returnap+'</option>';
      item += '<option value="return_ar">'+language_app.lb_returnar+'</option>';
      item += '<option value="mutation">'+language_app.lb_mutation+'</option>';
      item += '<option value="correction">'+language_app.lb_stock_correction+'</option>';
      item += '<option value="stock_opname">'+language_app.lb_stock_opname+'</option>';
      item += '<option value="stock_receipt">'+language_app.lb_stock_receipt+'</option>';
      item += '<option value="stock_issue">'+language_app.lb_stock_issue+'</option>';
    }

    if(report == "good_receipt"){
      item = '<option value="gr_code">'+language_app.lb_goodrcno+'</option>';
      item += '<option value="all">'+language_app.btn_detail+'</option>';
      item += '<option value="purchase_code">'+language_app.lb_purchaseno+'</option>';
      item += '<option value="receipt_name">'+language_app.lb_vendor+'</option>';
      item += '<option value="product_name">'+language_app.lb_product_name+'</option>';
      item += '<option value="store">'+language_app.lb_store+'</option>';
    } else if(report == "purchase"){
      item = '<option value="gr_purchase">'+language_app.lb_purchaseno+'</option>';
      item += '<option value="all">'+language_app.btn_detail+'</option>';
      item += '<option value="product_name">'+language_app.lb_product_name+'</option>';
      item += '<option value="vendor">'+language_app.lb_vendor+'</option>';
      item += '<option value="store">'+language_app.lb_store+'</option>';
    } else if(report == "mutation"){
      item = '<option value="mutation_code">'+language_app.lb_mutation_no+'</option>';
      item += '<option value="all">'+language_app.btn_detail+'</option>';
      // item += '<option value="mutation_from">Mutation From</option>';
      // item += '<option value="mutation_to">Mutation To</option>';
    } else if(report == "payment"){
      item += '<option value="payment_code">Payment Code</option>';
      item += '<option value="store_name">Store Name</option>';
      //item += '<option value="sales_code">Sales Code</option>';
    } else if(report == "payment_payable"){
      item += '<option value="payment_code">Transaction</option>';
    } else if(report == "return"){
      item = '<option value="return_code">'+language_app.lb_return_no+'</option>';
      item += '<option value="all">'+language_app.btn_detail+'</option>';
      item += '<option value="vendor_name">'+language_app.lb_vendor+'</option>';
      item += '<option value="product_name">'+language_app.lb_product_name+'</option>';
      item += '<option value="store">'+language_app.lb_store+'</option>';
    } else if(report == "account_receive"){
      item += '<option value="store_name">Store Name</option>';
      item += '<option value="ar_code">AR Code</option>';
    } else if(report == "selling"){
      item += '<option value="store_name">Store Name</option>';
      item += '<option value="product_name">Product Name</option>';
    } else if(report == "distributor_selling"){
      item = '<option value="selling">'+language_app.lb_selling+'</option>';
      item += '<option value="all">'+language_app.btn_detail+'</option>';
      item += '<option value="product_name">'+language_app.lb_product_name+'</option>';
      item += '<option value="vendor">'+language_app.lb_customer+'</option>';
      item += '<option value="store">'+language_app.lb_store+'</option>';
    } else if(report == "outstanding_delivery"){
      item = '<option value="transaction">'+language_app.lb_transaction+'</option>';
      item += '<option value="all">'+language_app.btn_detail+'</option>';
      item += '<option value="product_name">'+language_app.lb_product_name+'</option>';
      item += '<option value="store">'+language_app.lb_store+'</option>';
    } else if(report == "return_selling"){
      item = '<option value="selling">'+language_app.lb_transaction+'</option>';
      item += '<option value="all">'+language_app.btn_detail+'</option>';
    } else if(report == "return_distributor"){
      item += '<option value="distributor">Distributor</option>';
    } else if(report == "invoice_customer"){
      item += '<option value="transaction">Transaction</option>';
    } else if(report == "invoice_vendor"){
      item += '<option value="transaction">Transaction</option>';
    } else if(report == "saldo_receivable"){
      item += '<option value="transaction">Transaction</option>';
    } else if(report == "saldo_ap"){
      item += '<option value="transaction">Transaction</option>';
    } else if(report == "age_off_debt" || report == "age_off_credit"){
      item += '<option value="transaction">Transaction</option>';
    } else if(report == "correction_ap"){
      item += '<option value="transaction">Transaction</option>';
      item += '<option value="vendor">Vendor</option>';
    } else if(report == "correction_ar"){
      item += '<option value="transaction">Transaction</option>';
      item += '<option value="vendor">Customer</option>';
    } else if(report == "stock"){
      item += '<option value="store_name">Store Name</option>';
      item += '<option value="category_name">Category Name</option>';
      item += '<option value="product_name">Product Name</option>';
    } else if(report == "stock1"){
      item = '<option value="all">'+language_app.lb_all_company+'</option>';
      item += '<option value="store">'+language_app.lb_all_store+'</option>';
      item += '<option value="transaction">'+language_app.lb_transaction+'</option>';
    }else if(report == "correction_stock"){
      item = '<option value="transaction">'+language_app.lb_transaction+'</option>';
      item += '<option value="all">'+language_app.btn_detail+'</option>';
      item += '<option value="store">'+language_app.lb_store+'</option>';
    }else if(report == "stock_opname"){
      item = '<option value="transaction">'+language_app.lb_transaction+'</option>';
      item += '<option value="all">'+language_app.btn_detail+'</option>';
      item += '<option value="store">'+language_app.lb_store+'</option>';
    }else if(report == "stock_receipt" || report == "stock_issue"){
      item = '<option value="transaction">'+language_app.lb_transaction+'</option>';
      item += '<option value="all">'+language_app.btn_detail+'</option>';
      item += '<option value="store">'+language_app.lb_store+'</option>';
    }

    $("[name=group] option").remove();
    $("[name=group]").append(item);
    div_report  = $("#div-report")
    $(".v_button, .v_city").hide(300);
    $(".v_product").hide(300);
    $(".v_deliveryno").hide(300);
    $(".v_sellno").hide(300);
    $(".v_payno").hide(300);
    $(".v_branch").hide(300);
    $(".v_purchaseno").hide(300);
    $(".v_vendor").hide(300);
  	if(report == "none"){
  	  div_report.empty();
  	  $(".div-loader").hide();
  	  $(".v_tanggal, .v_customer, .v_tax, .v_group").hide(300);
      $('.table-data').empty();
  	}
  	else {
      $(".v_button").show(300);
      if(report == "distributor_selling"){
        $('#ckPPN').attr('checked', true);
  	    $(".v_tanggal, .v_group, .v_customer, .v_city, .v_branch").show(300);
        $(".v_tax, .v_search, .vparent_product").hide(300);
      } else if(report == "outstanding_delivery"){
  	  	$(".v_customer, .v_tanggal, .v_group, .v_branch").show(300);
        $(".v_tax, .v_deliveryno, .v_search").hide(300);
      } else if(report == "return_selling"){
        $(".v_customer, .v_tanggal, .v_group, .v_branch").show(300);
        $(".v_tax, .v_deliveryno, .v_search").hide(300);
      } else if(report == "return_distributor"){
        $(".v_customer, .v_tanggal, .v_group, .v_product, .v_branch").show(300);
        $(".v_tax, .v_deliveryno, .v_search").hide(300);
      } else if(report == "invoice_customer"){
        $(".v_customer, .v_tanggal, .v_group").show(300);
        $(".v_tax, .v_deliveryno .v_product, .v_branch, .v_vendor, .v_search").hide(300);
      } else if(report == "invoice_vendor"){
        $(".v_vendor, .v_tanggal, .v_group").show(300);
        $(".v_tax, .v_deliveryno .v_product, .v_branch, .v_customer, .v_search").hide(300);
  	  } else if(report == "good_receipt"){
  	  	$(".v_tanggal, .v_group, .v_vendor, .v_branch").show(300);
        $(".v_customer, .v_tax, .v_deliveryno, .v_search").hide(300);
      } else if(report == "purchase"){
        $(".v_tanggal, .v_group, .v_vendor, .v_branch").show(300);
        $(".v_customer, .v_tax, .v_deliveryno, .v_search").hide(300);
  	  } else if(report == "mutation"){
  	  	$(".v_tanggal, .v_group").show(300);
        $(".v_customer, .v_tax, .v_deliveryno, .v_search").hide(300);
  	  } else if(report == "payment"){
  	  	$(".v_tanggal, .v_group, .v_customer").show(300);
        $(".v_vendor, .v_tax, .v_deliveryno, .v_search").hide(300);
      } else if(report == "payment_payable"){
        $(".v_tanggal, .v_group, .v_vendor").show(300);
        $(".v_customer, .v_tax, .v_deliveryno, .v_search").hide(300);
  	  } else if(report == "return"){
  	  	$(".v_tanggal, .v_group, .v_vendor, .v_branch").show(300);
        $(".v_customer, .v_tax, .v_deliveryno, .v_search").hide(300);
  	  } else if(report == "account_receive"){
  	  	$(".v_tanggal, .v_group").show(300);
        $(".v_customer, .v_tax, .v_deliveryno, .v_search").hide(300);
  	  } else if(report == "selling"){
  	  	$(".v_tanggal, .v_group, .v_branch, .v_product").show(300);
        $(".v_customer, .v_tax, .v_deliveryno, .v_search").hide(300);
  	  } else if(report == "stock"){
  	  	$(".v_tanggal, .v_group").show(300);
        $(".v_customer, .v_tax, .v_deliveryno, .v_search").hide(300);
      } else if(report == "stock1"){
        $(".v_tanggal, .v_group, .v_product").show(300);
        $(".v_customer, .v_tax, .v_deliveryno, .v_search").hide(300);
      } else if(report == "sales_book"){
        $(".v_tanggal, .v_customer, .v_sellno").show(300);
        $(".v_group, .v_tax, .v_payno, .v.vendor, .v_search").hide(300);
      } else if(report == "purchase_book"){
        $(".v_tanggal, .v_vendor, .v_purchaseno").show(300);
        $(".v_group, .v_tax, .v_payno, .v_customer, .v_search").hide(300);
      } else if(report == "voucher"){
        $(".v_tanggal, .v_search").show(300);
        $(".v_group, .v_tax, .v_payno, .v_customer").hide(300);
      } else if(report == "age_off_debt"){
        $(".v_tanggal, .v_customer, .v_group").show(300);
        $(".v_tax, .v_payno, .v_deliveryno, .v_vendor, .v_search").hide(300);
      } else if(report == "age_off_credit"){
        $(".v_tanggal, .v_vendor, .v_group").show(300);
        $(".v_tax, .v_payno, .v_deliveryno, .v_customer, .v_search").hide(300);
      } else if(report == "correction_ap"){
        $(".v_tanggal, .v_vendor, .v_group").show(300);
        $(".v_tax, .v_payno, .v_deliveryno, .v_customer, .v_search").hide(300);
      } else if(report == "correction_ar"){
        $(".v_tanggal, .v_customer, .v_group").show(300);
        $(".v_tax, .v_payno, .v_deliveryno, .v_search").hide(300);
      } else if(report == "debtors_account"){
        $(".v_tanggal, .v_customer").show(300);
        $(".v_group, .v_tax, .v_deliveryno, .v_payno, .v_search").hide(300);
      } else if(report == "creditors_account"){
        $(".v_tanggal, .v_vendor").show(300);
        $(".v_group, .v_tax, .v_deliveryno, .v_payno, .v_customer, .v_search").hide(300);
      } else if(report == "saldo_receivable"){
        $(".v_tanggal, .v_customer, .v_group").show(300);
        $(".v_tax, v_vendor, .v_search").hide(300);
      } else if(report == "saldo_ap"){
        $(".v_tanggal, .v_vendor, .v_group").show(300);
        $(".v_tax, .v_customer, .v_search").hide(300);
      } else if(report == "correction_stock"){
        $(".v_tanggal, .v_group, .v_branch").show(300);
        $(".v_customer, .v_tax, .v_deliveryno, .v_search").hide(300);
      } else if(report == "cash" || report == "bank" || report == "jurnal" || report == "balance_sheet" || report == "loss_and_profit" || report == "ledger"){
        $(".v_tanggal").show(300);
        $(".v_customer, .v_vendor, .v_tax, .v_deliveryno, .v_search").hide(300);
      } else if(report == "serial_number"){
        $('.v_group, .v_tanggal, .v_branch').show(300);
        $(".v_customer, .v_vendor, .v_tax, .v_deliveryno, .v_search").hide(300);
      }else if(report == "stock_opname" || report == "stock_receipt" || report == "stock_issue"){
        $('.v_group, .v_tanggal, .v_branch').show(300);
        $(".v_customer, .v_vendor, .v_tax, .v_deliveryno, .v_search, .v_product").hide(300);
      }
  	}
  }else{
    console.log("report "+ report)
  }

  if(page == "report"){
    if(report == "sales_visiting"){
      $("[name=group]").val("date");
    }
  }


  group       = $("[name=group]").val();
  // load page---------------------------------------------
  if(jQuery.inArray(report, loadreport) !== -1){
    load_report_table();
  }else{
    url_table   = host + "report/table/"+report;
    $(".table-data").load(url_table,{group:group},function(response, status, xhr){
      $('.div-loader').hide();
      search_table();
    });
  }
  // console.log(url_table);
}

function load_report_table(){
    data_post  = {
      report     : $("[name=report]").val(),
      start_date : $("[name=start_date]").val(),
      end_date   : $("[name=end_date]").val(),
      group      : $("[name=group]").val(),
      search     : $("[name=search]").val(),
      Sales      : $("[name=Sales]").val(),
      sales1     : $("[name=sales1]").val(),
      company    : $("[name=company]").val(),
      product    : $("[name=product]").val(),
      customer   : $("[name=customer]").val(),
      vendor     : $("[name=vendor]").val(),
      tax        : $("[name=tax]").val(),
      deliveryno : $('[name=deliveryno]').val(),
      sellno     : $('[name=sellno]').val(),
      payno      : $('[name=payno').val(),
      branch     : $('[name=branch').val(),
      purchaseno : $('[name=purchaseno').val(),
      city       : $('[name=city]').val(),
    }
    url_table   = host + "report/table/"+report;
    $(".table-data").load(url_table,data_post,function(){
      $('.div-loader').hide();
    });
}

function search_table()
{

  report      = $("[name=report]").val();
  start_date  = $("[name=start_date]").val();
  end_date    = $("[name=end_date]").val();
  search      = $("[name=search]").val();
  
  if(report == "none"){
    // alert("please select report");
  } else {
     if(end_date >= start_date){
      if(report != "none"){
          if(jQuery.inArray(report, loadreport) !== -1){
            $('.div-loader').show();
            load_report_table();
          }else{
            filter_table({report:report});
          }
        }
      } else {
        alert("date from must less than date to");
      }
  }
}
function reload_table()
{
    // filter_table("reset");
    report      = $("[name=report]").val();
    if(jQuery.inArray(report, loadreport) !== -1){
      load_report_table();
    }else{
      table.ajax.reload(null,false); //reload datatable ajax
    }
}
function preview_detail(id)
{
  // console.log(id);
}

function filter_table(v = "")
{
  url = "";
  if(v.report == "good_receipt"){
    url = "report/good_receipt_list";
  } else if(v.report == "purchase"){
    url = "report/purchase_list";
  } else if(v.report == "mutation"){
    url = "report/mutation_list";
  } else if(v.report == "payment"){
    url = "report/payment_list";
  } else if(v.report == "payment_payable"){
    url = "report/payment_payable_list";
  } else if(v.report == "return"){
    url = "report/return_list";
  } else if(v.report == "account_receive"){
    url = "report/account_receive_list";
  } else if(v.report == "selling"){
    url = "report/selling_list";
  } else if(v.report == "distributor_selling"){
    url = "report/distributor_selling_list";
  } else if(v.report == "outstanding_delivery"){
    url = "report/outstanding_delivery_list";
  } else if(v.report == "return_selling"){
    url = "report/return_selling_list";
  } else if(v.report == "return_distributor"){
    url = "report/return_distributor_list";
  } else if(v.report == "invoice_customer"){
    url = "report/invoice_customer_list";
   } else if(v.report == "invoice_vendor"){
    url = "report/invoice_vendor_list";
  } else if(v.report == "sales_book"){
    url = "report/sales_book_list";
  } else if(v.report == "purchase_book"){
    url = "report/purchase_book_list";
  } else if(v.report == "voucher"){
    url = "report/voucher_list";
  } else if(v.report == "debtors_account"){
    url = "report/debtors_account_list";
  } else if(v.report == "creditors_account"){
    url = "report/creditors_account_list";
  } else if(v.report == "age_off_debt"){
    url = "report/age_off_debt_list";
  } else if(v.report == "correction_ap"){
    url = "report/correction_ap_list";
  } else if(v.report == "correction_ar"){
    url = "report/correction_ar_list";
  } else if(v.report == "saldo_receivable"){
    url = "report/saldo_receivable_list";
  } else if(v.report == "saldo_ap"){
    url = "report/saldo_ap_list";
  } else if(v.report == "stock"){
    url = "report/stock_list";
  } else if(v.report == "stock1"){
    url = "report/stock_list1";
  } else if(v.report == "serial_number"){
    url = "report/serial_number_list";
  } else if(v.report == "correction_stock"){
    url = "report/correction_stock_list";
  }
  // ini untuk salespro
  else if(v.report == "sales_visiting"){
    url = "report/sales_visiting_list";
  } else if(v.report == "sales_visiting_time"){
    url = "report/sales_visiting_time_list/time";
    $(".box-report-route").show();
  } else if(v.report == "sales_visiting_remark"){
    url = "report/sales_visiting_time_list/remark";
    $(".box-report-route").show();
  }
  else{
    url = "report/"+v.report+"_list";
  }
  data  = {
    report     : $("[name=report]").val(),
    start_date : $("[name=start_date]").val(),
    end_date   : $("[name=end_date]").val(),
    group      : $("[name=group]").val(),
    search     : $("[name=search]").val(),
    Sales      : $("[name=Sales]").val(),
    sales1     : $("[name=sales1]").val(),
    company    : $("[name=company]").val(),
    product    : $("[name=product]").val(),
    customer   : $("[name=customer]").val(),
    vendor     : $("[name=vendor]").val(),
    tax        : $("[name=tax]").val(),
    deliveryno : $('[name=deliveryno]').val(),
    sellno     : $('[name=sellno]').val(),
    purchaseno : $('[name=purchaseno]').val(),
    payno      : $('[name=payno').val(),
    branch     : $('[name=branch').val(),
    city       : $('[name=city]').val(),
  }
  
  // console.log(url);
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
        error: function (xhr, error, code)
        {
            console.log(xhr.responseText);
            console.log(code);
        },
        dataSrc : function (json) {
          console.log(json);
          df = json.datafoot;
          if(df){
            $("tfoot .Total1").text(df.Total1);
            $("tfoot .Total2").text(df.Total2);
            $("tfoot .Total3").text(df.Total3);
            // $("tfoot .Total4").text(df.Total4);
          }
          if(v.report == "sales_book"){
            $(".total1").text(json.total1);
            $(".total2").text(json.total2);
            $(".total3").text(json.total3);
            $(".total4").text(json.total4);
            $(".total5").text(json.total5);
            $(".total6").text(json.total6);
            $(".total7").text(json.total7);
          }
          if(v.report == "purchase_book"){
            $(".total1").text(json.total1);
            $(".total2").text(json.total2);
            $(".total3").text(json.total3);
            $(".total4").text(json.total4);
            $(".total5").text(json.total5);
            $(".total6").text(json.total6);
            $(".total7").text(json.total7);
          }
          if(v.report == "voucher"){
            $(".total1").text(json.total1);
            $(".total2").text(json.total2);
            $(".total3").text(json.total3);
            $(".total4").text(json.total4);
            $(".total5").text(json.total5);
            $(".total6").text(json.total6);
            $(".total7").text(json.total7);
          }
          if(v.report == "invoice_vendor"){
            $(".total1").text(json.total1);
            $(".total2").text(json.total2);
            $(".total3").text(json.total3);
            $(".total4").text(json.total4);
            $(".total5").text(json.total5);
          }
          if(v.report == "invoice_customer"){
            $(".total1").text(json.total1);
            $(".total2").text(json.total2);
            $(".total3").text(json.total3);
            $(".total4").text(json.total4);
            $(".total5").text(json.total5);
          }
          if(v.report == "correction_ap"){
            $(".total1").text(json.total1);
            $(".total2").text(json.total2);
            $(".total3").text(json.total3);
          }
           if(v.report == "correction_ar"){
            $(".total1").text(json.total1);
            $(".total2").text(json.total2);
            $(".total3").text(json.total3);
          }
           if(v.report == "payment_payable"){
            $(".total1").text(json.total1);
            $(".total2").text(json.total2);
            $(".total3").text(json.total3);
            $(".total4").text(json.total4);
            $(".total5").text(json.total5);
            $(".total6").text(json.total6);
          }
          if(v.report == "payment"){
            $(".total1").text(json.total1);
            $(".total2").text(json.total2);
            $(".total3").text(json.total3);
            $(".total4").text(json.total4);
            $(".total5").text(json.total5);
            $(".total6").text(json.total6);
          }
           if(v.report == "debtors_account"){
            $(".total1").text(json.total1);
            $(".total2").text(json.total2);
            $(".total3").text(json.total3);
            $(".total4").text(json.total4);
            $(".total5").text(json.total5);
          }
          if(v.report == "saldo_ap"){
            $(".total1").text(json.total1);
            $(".total2").text(json.total2);
            $(".total3").text(json.total3);
          }
          if(v.report == "saldo_receivable"){
            $(".total1").text(json.total1);
            $(".total2").text(json.total2);
            $(".total3").text(json.total3);
          }
          if(v.report == "creditors_account"){
            $(".total1").text(json.total1);
            $(".total2").text(json.total2);
            $(".total3").text(json.total3);
            $(".total4").text(json.total4);
          }
          if(v.report == "purchase"){
            $(".total1").text(json.total1);
            $(".total2").text(json.total2);
            $(".total3").text(json.total3);
            $(".total4").text(json.total4);
            $(".total5").text(json.total5);
            $(".total6").text(json.total6);
            $(".total7").text(json.total7);
            $(".total8").text(json.total8);
            $(".total9").text(json.total9);
            $(".total10").text(json.total10);
            $(".total11").text(json.total11);
          }
          if(v.report == "good_receipt"){
            $(".total1").text(json.total1);
            $(".total2").text(json.total2);
            $(".total3").text(json.total3);
            $(".total4").text(json.total4);
            $(".total5").text(json.total5);
            $(".total6").text(json.total6);
          }
           if(v.report == "return"){
            $(".total1").text(json.total1);
            $(".total2").text(json.total2);
            $(".total3").text(json.total3);
            $(".total4").text(json.total4);
            $(".total5").text(json.total5);
            $(".total6").text(json.total6);
            $(".total7").text(json.total7);
          }
           if(v.report == "selling"){
            $(".total1").text(json.total1);
            $(".total2").text(json.total2);
            $(".total3").text(json.total3);
            $(".total4").text(json.total4);
            $(".total5").text(json.total5);
            $(".total6").text(json.total6);
            $(".total7").text(json.total7);
            $(".total8").text(json.total8);
          }
          if(json.report == "purchase_book"){
            df = json.datafoot;
            $("tfoot .total1").text(df.total1);
            $("tfoot .total2").text(df.total2);
            $("tfoot .total3").text(df.total3);
          }
          if(json.report == "saldo_receivable"){
            df = json.datafoot;
            $("tfoot .total1").text(df.total1);
            $("tfoot .total2").text(df.total2);
          }
          if(json.report == "saldo_ap"){
            df = json.datafoot;
            $("tfoot .total1").text(df.total1);
            $("tfoot .total2").text(df.total2);
          }
          if(v.report == "sales_visiting_time" || v.report == "sales_visiting_remark"){
            console.log(json);
            $('.total_route').text(json.total_route);
            $('.total_route_miss').text(json.total_route_miss);
            $('.total_route_planning').text(json.total_route_planning);
            $('.total_route_not_planning').text(json.total_route_not_planning);
          }
          if(v.report == "jurnal"){
            $('.totalDebit').text(json.totalDebit);
            $('.totalCredit').text(json.totalCredit);
          }

          // 20190516 MW
          if(json.totalQty){$('tfoot .totalQty').text(json.totalQty);}
          if(json.totalQty2){$('tfoot .totalQty2').text(json.totalQty2);}
          if(json.totalQtyDelivery){$('tfoot .totalQtyDelivery').text(json.totalQtyDelivery);}
          if(json.totalQtyResidue){$('tfoot .totalQtyResidue').text(json.totalQtyResidue);}
          if(json.totalPrice){$('tfoot .totalPrice').text(json.totalPrice);}
          if(json.totalDiscount){$('tfoot .totalDiscount').text(json.totalDiscount);}
          if(json.totalSubTotal){$('tfoot .totalSubTotal').text(json.totalSubTotal);}
          if(json.totalTax){$('tfoot .totalTax').text(json.totalTax);}
          if(json.totalDelivery){$('tfoot .totalDelivery').text(json.totalDelivery);}
          if(json.totalPayment){$('tfoot .totalPayment').text(json.totalPayment);}
          if(json.totalTransaction){$('tfoot .totalTransaction').text(json.totalTransaction);}
          return json.data;
        }
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
  nama_laporan = $('[name=report] option:selected').text();
  
  if(report == "none"){
    alert("please select report");
  } else {
    url     = host + "report/cetak/"+report+"?cetak="+status+"&name="+nama_laporan;
    if(status == "excell"){
    url     = host + "report/"+report+"_excell?name="+nama_laporan;
    }
    $('form').attr('action', url);
    if(status == "print" || status == "excell"){
      $('form').attr('target', '_blank');
    }
    $("#form").submit();
    // $.post(url, {variable: "tes"});

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
function modal_visit(modal = "",id = "", date = "")
{
    $(".list-route-customer li").remove();
    modal_width();
    $("#info-marker-visit").hide();
    if(modal == "routing_sales"){
      $(".col-routing_sales").show();
      $(".col-sales_visit").hide();

      modal_title = "Routing Employee";
      page        = "routing_sales";
    } else if(modal == "sales_visit") {
      $("#info-marker-visit").show();
      $(".col-routing_sales").hide();
      $(".col-sales_visit").show();
      
      modal_title = "Employee Visit";
      page        = "sales_visit";
    }
    $('.form-group').removeClass('has-error');
    $('.help-block').empty(); // clear error string
    $.ajax({
        url : host+"report/modal_visit/"+id+"/"+page+"/"+date,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            console.log(data);
            if(data.hakakses == "super_admin"){
            }
            if(modal == "sales_visit"){
              deleteMarkers();
              delete_radius();
              directionsDisplay.setMap(null);

              dd  = data.detail;
              Lat = dd.CustomerLat;
              Lng = dd.CustomerLng;
              Radius = dd.Radius;

              if(dd.CheckInLatlng && dd.CustomerID == null){
                CheckInLatLng = dd.CheckInLatlng.split(",");
                Lat           = parseFloat(CheckInLatLng[0]);
                Lng           = parseFloat(CheckInLatLng[1]);
              }
              if(dd.Radius == null){
                Radius = 100;
              }

              $("#vCode").text(dd.Code);
              $("#vDate").text(dd.Date);
              $("#vSalesName").text(dd.SalesName);
              $("#vCustomerName").text(dd.CustomerName);
              $("#vCustomerAddress").text(dd.CustomerAddress);
              $("#vCheckInDate").text(dd.CheckIn);
              $("#vCheckOutDate").text(dd.CheckOut);
              $("#vCheckInAddress").text(dd.CheckInAddress);
              $("#vCheckOutAddress").text(dd.CheckOutAddress);
              $("#vDuration").text(dd.Duration);
              $("[name=radius]").val(Radius);
              $("#vCustomerPlace").attr("src",dd.CustomerImage);
              $("#vCustomerPlaceLink").attr("href",dd.CustomerImage);

              set_radius();
              resizeMap(Lat,Lng,{marker:true,radius:true});
              if(dd.CheckInLatlng && dd.CustomerID){
                CheckInLatLng = dd.CheckInLatlng.split(",");
                CheckInLat    = parseFloat(CheckInLatLng[0]);
                CheckInLng    = parseFloat(CheckInLatLng[1]);
                CheckInLatLng = new google.maps.LatLng(CheckInLat, CheckInLng);
                addMarker(CheckInLatLng,{Check:'in'});
                //membuat poliline
                arrayPoliline = [];
                CustomerLoc   = {lat:parseFloat(dd.CustomerLat), lng:parseFloat(dd.CustomerLng)};
                CheckInLoc    = {lat:CheckInLat,lng:CheckInLng};
                arrayPoliline.push(CustomerLoc);
                arrayPoliline.push(CheckInLoc);
                set_poliline(arrayPoliline);
              }

            } else {
              deleteMarkers();
              delete_polyline();
              delete_radius();
              radius_val = 0;
              if(data.list_data_real.length == 1){
                resizeMap(data.origin.Lat, data.origin.Lng,{marker:true,radius:false});
              } else {
                resizeMap(data.origin.Lat, data.origin.Lng,{marker:false,radius:false});
              }

              // initialize();
              directionsDisplay.setMap(map);

              if(data.list_data_real){
                if(data.list_data_real.length > 1){
                  calculateAndDisplayRoute(directionsService, directionsDisplay,data.list_data,data.origin,data.destination);
                } else {
                  directionsDisplay.setMap(null);
                  var summaryPanel = document.getElementById('directions-panel');
                  summaryPanel.innerHTML = '';
                  $("#TotalKM").text("0 KM");
                  add_location(data.list_data);
                }
              }

              var summaryPanel = document.getElementById('directions-panel2');
              $.each(data.list_data_real,function(i,v){
                item = "<li>";
                item += '<b>'+v.Name+'</b>';
                item += "<br/>";
                item += v.Address;
                item += "</li>";
                $(".list-route-customer").append(item);
              });

                
            }

            $('#modal-visit').modal("show");
            $('.modal-title').text(modal_title);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}





// ini untuk salespro

var map,map2;
var markers = [];
var lat;
var lng;
var zoom;
var myCenter;
var zoom = 2;
var statusmap;
var infowindow;
var geocoder;
var cityCircle;
var flightPath;
var directionsService 
var directionsDisplay;
$(document).ready(function() {
    if(app == "salespro"){
        google.maps.event.addDomListener(window, 'load', initialize);
        google.maps.event.addDomListener(window, "resize", resizingMap());
    }
});
function initialize() {
    directionsService = new google.maps.DirectionsService;
    directionsDisplay = new google.maps.DirectionsRenderer;
    myCenter    = new google.maps.LatLng(20, -10);
    infowindow  = new google.maps.InfoWindow;
    geocoder    = new google.maps.Geocoder;
    addMarker(myCenter);

    var mapProp     = {
        center:myCenter,
        zoom: zoom,
        gestureHandling: 'greedy'

        // mapTypeId:google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map"),mapProp);
    map2 = new google.maps.Map(document.getElementById("map2"),mapProp);

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
    directionsDisplay.setMap(map);
    $("#cekdirection").click(function(){
      calculateAndDisplayRoute(directionsService, directionsDisplay);
    });
    // map.addListener('click', function(event) {
    //       addMarker(event.latLng);
    // });
    // autocomplete();
}
function resizeMap(lat="",lng="",setting = "") {
   if(typeof map =="undefined") return;
   setTimeout( function(){
        resizingMap(lat,lng,setting);
    } , 400);
}

function resizingMap(lat="",lng="",setting = "") {
    if(typeof map =="undefined") return;
    statusmap = false;
    if(lat != "" && lng != ""){
        // deleteMarkers();

        myCenter = new google.maps.LatLng(lat, lng);
        var marker   = new google.maps.Marker({
            position:myCenter
        });
        statusmap   = true;
        center      = myCenter;
        zoom        = 15;
    } else {
        // deleteMarkers();
        zoom        = 2;
        myCenter    = new google.maps.LatLng(10, -20);
        center      = map.getCenter();
    }

    google.maps.event.trigger(map, "resize");
    map.setCenter(center); 
    map.setZoom(zoom); 
    if(statusmap && setting.marker == true){
        addMarker(myCenter,{Radius:true});
    }
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
function add_location(list_store){
    var infowindow = new google.maps.InfoWindow(); /* SINGLE */
    function placeMarker(v) {
        var latLng = new google.maps.LatLng(v.Lat, v.Lng);
        var marker = new google.maps.Marker({
          position : latLng,
          map      : map
        });
        google.maps.event.addListener(marker, 'click', function(){
            infowindow.close(); // Close previously opened infowindow
            infowindow.setContent( "<div id='infowindow'>"+ v.Name +"</div>");
            infowindow.open(map, marker);
        });
    }
    $.each(list_store, function(i, v) {
        if(i == 0){
            myCenter = new google.maps.LatLng(v.Lat, v.Lng);
            center   = myCenter;
            zoom     = 8;
            google.maps.event.trigger(map, "resize");
            map.setCenter(center); 
            map.setZoom(zoom); 
        }
       placeMarker(v);
    });
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
            $(".modal-dialog").css("width","85%");
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
function calculateAndDisplayRoute(directionsService, directionsDisplay,list_data = "",vorigin = "",vdestination = "") {
  origin          = new google.maps.LatLng(vorigin.Lat, vorigin.Lng);
  destination     = new google.maps.LatLng(vdestination.Lat, vdestination.Lng);
  // origin          = new google.maps.LatLng(-6.89000000000000, 106.236363259999998);
  // destination     = new google.maps.LatLng(-6.9700000001, 107.116363259999998);
  var waypts  = [];
  var checkboxArray = document.getElementById('waypoints');
  if(list_data && list_data.length > 0){
    $.each(list_data,function(i,v){
      // location = new google.maps.LatLng(v.Lat, v.Lng);
      waypts.push({
        location: new google.maps.LatLng(v.Lat, v.Lng),
        stopover: true
      });

    });
  }
  directionsService.route({
    origin: origin,
    destination: destination,
    waypoints: waypts,
    // optimizeWaypoints: true,
    travelMode: 'DRIVING',
    // travelMode: 'BICYCLING',
    provideRouteAlternatives: false,
    avoidHighways:true
  }, function(response, status) {
    console.log(response);
    if (status === 'OK') {
      directionsDisplay.setDirections(response);
      var route = response.routes[0];
      var summaryPanel = document.getElementById('directions-panel');
      summaryPanel.innerHTML = '';
      // For each route, display summary information.
      totalKM = 0;
      var str_destination = "";
      for (var i = 0; i < route.legs.length; i++) {
        var routeSegment = i + 1;
        if(routeSegment == 1){
          if(list_data.length > 0){
            summaryPanel.innerHTML += '<a href="#"><b>Route Segment: ' + vorigin.Name +' to '+list_data[i].Name+'</b><br>';
            str_destination = list_data[i].Name;
          }else{
            summaryPanel.innerHTML += '<a href="#"><b>Route Segment: ' + vorigin.Name +' to '+vdestination.Name+'</b><br>';
            str_destination = vdestination.Name;
          }
        }
        else if(routeSegment == route.legs.length){
          summaryPanel.innerHTML += '<a href="#"><b>Route Segment: ' + str_destination+' to '+vdestination.Name +'</b><br>';
        }
        else{
          summaryPanel.innerHTML += '<a href="#"><b>Route Segment: ' + str_destination +' to '+list_data[i].Name+'</b><br>';
          str_destination = list_data[i].Name;  
        }
        summaryPanel.innerHTML += route.legs[i].start_address + ' to ';
        summaryPanel.innerHTML += route.legs[i].end_address + '<br>';
        summaryPanel.innerHTML += route.legs[i].distance.text + '<br><br></a>';
        totalKM += route.legs[i].distance.value;
      }
      totalKM = totalKM / 1000;
      $("#TotalKM").text(totalKM.toFixed(1) + " KM");
    } else {
      window.alert('Directions request failed due to ' + status);
    }
  });
}
function addMarker(location,setting="") {
  //https://maps.gstatic.com/mapfiles/api-3/images/spotlight-poi2.png
  if(setting.Check == 'in'){
      icon = host +'img/icon/marker-blue.svg';
  } else {
      icon = host +'img/icon/marker-red.svg';
  }
  console.log(icon);
  var IconMarker  = {
    url: icon,
    size: new google.maps.Size(45, 45),
    origin: new google.maps.Point(-3, -5,0,0),
    scaledSize: new google.maps.Size(40, 40)
  };
  var marker = new google.maps.Marker({
    position: location,
    map: map,
    icon:IconMarker,
    // animation : google.maps.Animation.DROP,

  });
  markers.push(marker);
  if(setting.Radius == true){
      addRadius(location);
  }
}
function addRadius(location,setting = "")
{
  if(setting){
    radius_val = setting.radius;
    color = '#4CAF50';
  } else {
    color = '#FF0000';
  }

  if(radius_val){
      cityCircle = new google.maps.Circle({
      strokeColor: color,
      strokeOpacity: 0.8,
      strokeWeight: 1,
      fillColor: color,
      fillOpacity: 0.35,
      map: map,
      center: location,
      radius: radius_val
    });
  }
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
function delete_radius()
{
    cityCircle.setMap(null);
}

function set_poliline(PoliLine)
{
  delete_polyline();
  // var flightPlanCoordinates = [
  //   {lat: 37.772, lng: -122.214},
  //   {lat: 21.291, lng: -157.821},
  // ];
  // console.log(flightPlanCoordinates);
  // console.log(PoliLine);
  // $.each(PoliLine,function(i,v){
  //   var CheckInLatLng = new google.maps.LatLng(v.lat, v.lng);
  //   var marker = new google.maps.Marker({
  //       position: CheckInLatLng,
  //       map: map
  //   });
  // });
  flightPath = new google.maps.Polyline({
    path: PoliLine,
    geodesic: true,
    strokeColor: '#2196F3',
    strokeOpacity: 1.0,
    strokeWeight: 2
  });

  flightPath.setMap(map);
  var lengthInMeters = google.maps.geometry.spherical.computeLength(flightPath.getPath());
  console.log("polyline is "+lengthInMeters+" long");
}
function delete_polyline()
{
  if(flightPath){
    flightPath.setMap(null);
  }
}

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
      console.log(data);
      $('#Sales').append(data.data);
    },
    error: function (jqXHR, textStatus, errorThrown)
    {
      alert('Error get data from ajax');
    }
  });
}

//save customer
var radius_val2;
var statusmap2;
var myCenter2;
var marker2;
var center2;
var zoom2;
function save_customer(id){
  modal_width2();
  save_method = "add";
  $('#form_customer')[0].reset();
  $('.form-group').removeClass('has-error'); // clear error class
  $('.help-block').empty();
  $('.new_customer').show();
  $('.old_customer').hide();
  $.ajax({
    url : host+"report/get_transaction_detail/"+id,
    type: "GET",
    dataType: "JSON",
    success: function(data)
    {
      console.log(data);
      $('[name=address2]').val(data.address);
      $('[name=radius2]').val(100);
      $('[name=ID]').val(id);
      $('[name=companyID]').val(data.companyID);
      set_radius2();
      resizeMap2(data.lat,data.lng);
      sp_list_vendor(data.companyID);

      $('#modal2').modal("show");
      $('.modal-title').text("Save as Customer");
    },
    error: function (jqXHR, textStatus, errorThrown)
    {
      alert('Error get data from ajax');
    }
  });
}
function sp_list_vendor(CompanyID){
  $('#customer').find('option').remove().end();
  $('#customer').append('<option value="none">Select Customer</option>');
  $.ajax({
    url : host+"vendor/sp_list_vendor/"+CompanyID,
    type: "GET",
    dataType: "JSON",
    success: function(data)
    {
      console.log(data);
      $('#customer').append(data.data);
    },
    error: function (jqXHR, textStatus, errorThrown)
    {
      alert('Error get data from ajax');
    }
  });
}
function modal_width2()
{
  if(app == "salespro"){
      if(mobile){
          $(".modal-dialog").css("width","92%");
      } else {
          $(".modal-dialog").css("width","60%");
      }
  }
}
function set_radius2()
{
  radius_val2 = $("[name=radius2]").val();
  radius_val2    = parseInt(radius_val2);
  $(".radius_val2").text(radius_val2 + " Meter");
  // radius_val = Math.sqrt(radius_val) * 50;
  // radius_val = Math.round(radius_val);
  cityCircle.setRadius(radius_val2);
}
function resizeMap2(lat="",lng="") {
 if(typeof map2 =="undefined") return;
 setTimeout( function(){
      resizingMap2(lat,lng);
  } , 400);
}
function resizingMap2(lat="",lng="") {
  if(typeof map2 =="undefined") return;
  statusmap2 = false;
  if(lat != "" && lng != ""){
      deleteMarkers();

      myCenter2 = new google.maps.LatLng(lat, lng);
      marker2   = new google.maps.Marker({
          position:myCenter2
      });
      statusmap2   = true;
      center2      = myCenter2;
      zoom2        = 15;
  } else {
      deleteMarkers();
      zoom2        = 2;
      myCenter2    = new google.maps.LatLng(10, -20);
      center2      = map2.getCenter();
  }

  google.maps.event.trigger(map2, "resize");
  map2.setCenter(center2); 
  map2.setZoom(zoom2); 
  if(statusmap2){
      addMarker2(myCenter2);
  }
}
function addMarker2(location) {
    deleteMarkers();
    cityCircle.setMap(null);
    marker2 = new google.maps.Marker({
      position: location,
      map: map2,
      animation : google.maps.Animation.DROP,
      // draggable : true

    });
    cityCircle = new google.maps.Circle({
        strokeColor: '#FF0000',
        strokeOpacity: 0.8,
        strokeWeight: 1,
        fillColor: '#FF0000',
        fillOpacity: 0.35,
        map: map2,
        center: location,
        radius: radius_val2
      });
    markers.push(marker2);
    setMarkerinput(location);
}

function setMarkerinput(location) {
  $("#lat2").val(location.lat());
  $("#lng2").val(location.lng());
}

$(document).ready(function() {
    $(".radius_val2").text("0 Meter");
    $("[name=radius2]").change(function(){
        set_radius2();
    });

    $("[name=type2]").change(function(){
      if ($(this).val() == "new_customer") {
        $('.new_customer').show();
        $('.old_customer').hide();
      }else{
        $('.new_customer').hide();
        $('.old_customer').show();
      }
    });
});

function save(){
  $('#btnSave2').text('saving...'); //change button text
  $('#btnSave2').attr('disabled',true); //set button disable
  $('.form-group').removeClass('has-error'); // clear error class
  $('.help-block').empty();
  var url;
  if(save_method == 'add') {
      url = host+"report/save_customer";
  }else{
      url = host;
  }
  $.ajax({
      url : url,
      type: "POST",
      data: $('#form_customer').serialize(),
      dataType: "JSON",
      success: function(data)
      {
          console.log(data);
          if(data.status) //if success close modal and reload ajax table
          {
              $('#modal2').modal("hide");
              reload_table();
          }
          else
          {
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty();
              for (var i = 0; i < data.inputerror.length; i++)
              {
                  $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                  //select parent twice to select div form-group class and add has-error class
                  $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                  // swal('',data.error_string[i],'warning');
              }
          }
          $('#btnSave2').text('save'); //change button text
          $('#btnSave2').attr('disabled',false); //set button enable
      },
      error: function (jqXHR, textStatus, errorThrown)
      {
          alert('Error adding / update data');
          $('#btnSave2').text('save'); //change button text
          $('#btnSave2').attr('disabled',false); //set button enable   
      }
  });
}
