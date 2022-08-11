//note : kalau lu gak paham bertanya kepada yang bisa selamat mencoba
//note :  tanya sama yang bikinnya lah tarif 100rb sekali tanya
var host    = window.location.origin + '/';
var url     = window.location.href;
var save_method; //for save method string
var table;
var page;
var data_post = [];
var table_report = false;
var report,app,start_date,end_date,tahun,bulan,jenis_transaksi,bank,plate,Group;
var url_table;
$(document).ready(function(){
  $(".v_filter, .v_bulan, .v_tahun, .v_button, .v_UserID, .v_WorkStatusID, .v_pekerjaan, .v_pegawai").hide();
  v             = $(".page-data").data();
  page          = v.page;
  report        = v.report;
  StartDate     = v.start_date;
  EndDate       = v.end_date;
  Tahun         = v.tahun;
  Bulan         = v.bulan;
  // UserID        = v.UserID;
  // WorkStatusID  = v.WorkStatusID;


  start_date    = StartDate;
  end_date      = EndDate;
  tahun         = Tahun;
  bulan         = Bulan;
  // UserID        = v.UserID;
  // WorkStatusID  = v.WorkStatusID

  $("[name=StartDate]").val(start_date);
  $("[name=EndDate]").val(end_date);
  $("[name=Tahun]").val(Tahun);
  $("[name=Bulan]").val(Bulan);
  // $("[name=UserID]").val(UserID);
  // $("[name=WorkStatusID]").val(WorkStatusID);


  if(report != "none"){
    $("[name=report]").val(report);
    load_page("report",report);
  } 
})
function load_page(page = ""){
  div_report  = $("#div-report")
  report      = $("[name=Report]").val();
  Group       = $("[name=Group]").val();
  $(".div-loader").show();
  if(report == "none"){
      div_report.empty();
      $(".div-loader").hide();
      $(".v_tgl, .v_pegawai, .v_project, .v_group").hide(300);
  }
  else {
    $(".v_button").show(300);
    if(report == "lap_project"){
      $(".v_tgl, .v_group").show(300);
  	  Group = $("[name=Group]").val();
  	  if(Group == "project"){
  	  	$(".v_project, .v_pegawai").show(300);
  	  } else {
  	  	$(".v_project, .v_pegawai").hide(300);
  	  }
    } else if(report == "lap_pegawai"){
      $(".v_pegawai, .v_tgl, .v_project").show(300);
      $(".v_group").hide(300);
    } else if(report == "lap_harian"){
	    $(".v_pegawai, .v_tgl, .v_project").show(300);
    	$(".v_group").hide(300);
    }
    if(report == "lap_harianx"){
      load_report_table();
      
    } else {
      table_report = false;
      $(".div-loader").hide();
      $("#div-report").load("report/table/"+report,{Group:Group},function(){
        search_table();
        $(".div-loader").hide();
      });
    }
  }
}
function load_report_table(){
  report          = $("[name=Report]").val();
  start_date      = $("[name=StartDate]").val();
  end_date        = $("[name=EndDate]").val();
  search          = $("[name=Search]").val();
  bulan           = $("[name=Bulan]").val();
  WorkStatusID    = $("[name=WorkStatusID]").val();
  TransactionListID  = $("[name=TransactionListID]").val();
  UserID          = $("[name=UserID]").val();
  Plate           = $(".Plate").val();
  Status          = $("[name=Status]").val();
  Group           = $("[name=Group]").val();
  data_post = {
    cetak : "load",
    Report : report,
    StartDate : start_date,
    EndDate : end_date,
    TransactionListID : TransactionListID,
    UserID: UserID,
    WorkStatusID: WorkStatusID,
    Plate: Plate,
    Status: Status,
    Group: Group,
  }
  console.log(data_post);
  $(".div-loader").show();
  table_report = true;
  $("#div-report").load("report/table/"+report,data_post,function(){
    $(".div-loader").hide();
  });
}
function search_table()
{
  if(table_report){
    load_report_table();
  } else {
    table_report = false;

    if(report == "none"){
      // alert("please select report");
      swal("info","please select report")
    } else {
       // if(end_date >= start_date){
        if(report != "none"){
            filter_table();
        }
        // } else {
        //   swal("info","start date must less than date to")
        // }
    }
  }
}
function filter_table(){

  report          	= $("[name=Report]").val();
  Group          	= $("[name=Group]").val();
  start_date      	= $("[name=StartDate]").val();
  end_date       	= $("[name=EndDate]").val();
  search          	= $("[name=Search]").val();
  bulan           	= $("[name=Bulan]").val();
  TransactionListID = $("[name=TransactionListID]").val();
  WorkStatusID    	= $("[name=WorkStatusID]").val();
  UserID          	= $("[name=UserID]").val();
  Plate           	= $(".Plate").val();
  Status          	= $("[name=Status]").val();
  data_post = {
    cetak 		: "load",
    Report 		: report,
    Group 		: Group,
    StartDate 	: start_date,
    EndDate 	: end_date,
    TransactionListID: TransactionListID,
    UserID 		: UserID,
    WorkStatusID: WorkStatusID,
    Plate 		: Plate,
    Status 		: Status,
  }
  console.log(data_post);
  table = $('#table-report').DataTable({
    paging: false,
    info:false,
    searching: false,
    destroy: true,
    processing: true, //Feature control the processing indicator.
    serverSide: false, //Feature control DataTables' server-side processing mode.
    // "order": [], //Initial no order.
    ajax: {
        url: "report/datatables/"+report,
        type: "POST",
        data: data_post,
        dataSrc : function (json) {
          console.log(json);
          df = json.datafoot;
          if(df){
            $("tfoot .Total1").text(df.Total1);
            $("tfoot .Total2").text(df.Total2);
            $("tfoot .Total3").text(df.Total3);
            $("tfoot .Total4").text(df.Total4);
          }
          if(json.report == "lap_harian"){
            df = json.datafoot;
            $("tfoot .total1").text(df.total1);
            $("tfoot .total2").text(df.total2);
          }
          return json.data;
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            console.log(jqXHR.responseText);
        }
    },
    // columnDefs: [{
    //     targets: [0], //last column
    //     orderable: false, //set not orderable
    // },],
  });
  $(".dataTables_processing").empty();
}
function reload_table()
{
  if(table_report){
    load_report_table();
  } else{
    table.ajax.reload(null,false); //reload datatable ajax
  }
}
function cetak(data){
  host        = window.location.origin+'/';
  // console.log(data);
  status  = data.cetak;
  view    = data.view;
  url     = "";
  report  =  $("[name=Report]").val();
  if(report == "none"){
    alert("please select report");
  } else {
    if(status == "print"){
      url     = host + "report/cetak/"+report+"?cetak="+status;;
    } else if(status == "pdf"){
      url     = host + "report/cetak/"+report+"?cetak="+status+"&view="+view;
    } else {
      url     = host + "report/"+report+"_excell";
    }
    console.log(host);
    console.log(url);
    $('form').attr('action', url);
    // if(status == "print" || status == "excell"){
      $('form').attr('target', '_blank');
    // }
    $("#form").submit();
    Plate = $("[name=Plate]").val();
    data_post = {variable: "tes", Plate : Plate,Platex : Plate};
    console.log(data_post);
    $.post(url, data_post);

  }
}