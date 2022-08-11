var mobile      = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host        = window.location.origin+"/";
var url         = window.location.href;
var numberWeek  = 0;
var Tahun;
$(document).ready(function() {
    get_data_filter();
    $(".portlet-widgets .ion-refresh").click(function(){
        get_data_filter();
    });
});
function data_week(mod){
    if(mod == "next"){
        numberWeek += 1;
    } else if(mod == "prev") {
        numberWeek -= 1;
    } else {

    }
    get_data();
}

function get_data_filter(){
    get_data();
}
function get_data()
{
    $(".div-loader").show();
    Tahunx = $(".filter [name=Tahun]").val();
    OrderTanggal = $(".filter [name=OrderTanggal]").val();
    ProjectIDps  = $("[name=ProjectIDps]").val();
    FilterProjectStatus = $("[name=FilterProjectStatus]").val();
    if(Tahunx != "none"){
        Tahun = Tahunx;
    }
    data_post = {
        Tahun : Tahun,
        numberWeek : numberWeek,
        OrderTanggal : OrderTanggal,
        ProjectIDps : ProjectIDps,
        FilterProjectStatus : FilterProjectStatus,
    }
    console.log(data_post);
    $.ajax({
        url : host + "dashboard/data_dashboard",
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data)
        {
            console.lo
            if(data.HakAkses == "rc"){
                console.log(data);
            }
            if(data.StatusProject.length > 0){
            	$(".div-status-project").empty();
            	$.each(data.StatusProject,function(i,v){
            		TransactionListID 	= v.TransactionListID;
            		ProjectName  		= v.Name;
            		PersenSelesai 		= v.PersenSelesai;
            		PersenBelumSelesai 	= v.PersenBelumSelesai;
            		label_miss 			= 'danger';
            		if(PersenBelumSelesai <= 10){
            			label_miss      = 'warning';
            		} else if(PersenBelumSelesai > 10 && PersenBelumSelesai <= 30){
            			label_miss 		= 'pink';
            		}
                   

            		item = '<div class="col-sm-12 div-item"  onclick="detail_project(this)" data-id="'+TransactionListID+'">';
            		item += '<div class="div-item-content width-100">';
            		item += '<div class="workername" title="'+ProjectName+'"><a href="javascript:;" onclick="detail_project(this)" data-id="'+TransactionListID+'">'+ProjectName+'</a></div>';
            		item += '      <div class="progress progress-md width-100">';
                    if(FilterProjectStatus == "overview"){               
                        TotalRencana          = v.JumlahRencana * 100;
                        TotalRencanaToday     = v.JumlahRencanaToday * 100;
                        TotalRencanaSisa      = TotalRencana - TotalRencanaToday;
                        RencanaSisaPercent    = (TotalRencanaSisa / TotalRencana) * 100;
                        JumlahPercentage      = v.JumlahPercentage;
                        JumlahPercentageToday = v.JumlahPercentageToday;
                        TargetHariIni         = Math.round((TotalRencanaToday / TotalRencana) * 100);
                        TargetHariIniReal     = Math.round((JumlahPercentageToday / TotalRencana) * 100);
                        TargetMiss            = Math.round(TargetHariIni - TargetHariIniReal);
                        MasihRencana         = Math.round(100 - TargetHariIni);
                        // console.log("Target : " + TargetHariIni + ' - Real : '+TargetHariIniReal + ' Miss : ' + TargetMiss + ' Rencana ' + MasihRencana);
                        item += '          <div title="Pekerjaan Selesai" class="progress-bar progress-bar-success" role="progressbar" style="width: '+TargetHariIniReal+'%;">';
                        item += '              '+TargetHariIniReal+'%';
                        item += '          </div>';

                        item += '          <div title="Pekerjaan Miss" class="progress-bar progress-bar-danger" role="progressbar" style="width: '+TargetMiss+'%;">';
                        item += '              '+TargetMiss+'%';
                        item += '          </div>';

                        item += '          <div title="Masih Dalam Rencana" class="progress-bar progress-bar-inverse" role="progressbar" style="width: '+MasihRencana+'%;">';
                        item += '              '+MasihRencana+'%';
                        item += '          </div>';
                    } else{
                        item += '          <div title="Pekerjaan Selesai" class="progress-bar progress-bar-success" role="progressbar" style="width: '+v.PersenSelesai+'%;">';
                        item += '              '+v.PersenSelesai+'%';
                        item += '          </div>';
                        item += '          <div title="Pekerjaan Belum Selesai" class="progress-bar progress-bar-'+label_miss+'" role="progressbar" style="width: '+v.PersenBelumSelesai+'%;">';
                        item += '              '+v.PersenBelumSelesai+'%';
                        item += '          </div>';

                    }
                    item += '      </div> '; 
            		item += '</div>'; // div-item-content
            		item += '</div>';
					$(".div-status-project").append(item );
            	});
            }

            if(data.StatusPegawai.length > 0){
            	$(".div-status-pegawai").empty();
            	No = 0;
            	$.each(data.StatusPegawai,function(i,v){
            		No += 1;

            		PersenSelesai 		= v.PersenSelesai;
            		PersenBelumSelesai 	= v.PersenBelumSelesai;
            		label_miss 			= 'danger';
            		if(PersenBelumSelesai <= 10){
            			label_miss      = 'warning';
            		} else if(PersenBelumSelesai > 10 && PersenBelumSelesai <= 30){
            			label_miss 		= 'pink';
            		}

            		item = '<div class="col-sm-12 div-item">';
            		item += '<div class="image"><img src="'+v.WorkerImage+'" title="'+v.WorkerName+'" class="img-circle"></div>';
            		item += '<div class="div-item-content width-100">';
            		item += '<div class="workername" title="'+v.WorkerName+'">'+v.WorkerName+'</div>';
            		item += '<span class="percentage" title="Total Pekerjaan">Total Pekerjaan : '+v.JumlahRencana+'</span>';
            		item += '      <div class="progress progress-md width-100">';
                    item += '          <div title="Pekerjaan Selesai" class="progress-bar progress-bar-success" role="progressbar" style="width: '+v.PersenSelesai+'%;">';
                    item += '              '+v.PersenSelesai+'%';
                    item += '          </div>';
                    item += '          <div title="Pekerjaan Belum Selesai" class="progress-bar progress-bar-'+label_miss+'" role="progressbar" style="width: '+v.PersenBelumSelesai+'%;">';
                    item += '              '+v.PersenBelumSelesai+'%';
                    item += '          </div>';
                    item += '      </div> '; 
            		item += '</div>'; // div-item-content
            		item += '</div>';
					$(".div-status-pegawai").append(item );
            	});
            } else {
            	$(".div-status-pegawai").empty();
            	item = '<div class="col-sm-12" style="text-align: center;">Tidak ada data</div>';
            	$(".div-status-pegawai").append(item);
            }

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            console.log(jqXHR.responseText);
        }
    });
}
function detail_project(element){
	dt = $(element).data();
	console.log(dt);
	TransactionListID = dt.id;
   	$(".modal").modal("show");
	$.ajax({
        url : host + "dashboard/detail_project/"+TransactionListID,
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data)
        {
            if(data.HakAkses == "rc"){
                console.log(data);
            }
            if(data.ListData.length > 0){
            	$(".modal tbody").empty();
            	No = 0;
            	$.each(data.ListData,function(i,v){
            		No += 1;
            		bgtr = '';
            		if(v.Status <= 'Miss'){
            			bgtr = "background:red;"
            		}

            		item = '<tr style="'+bgtr+';color:#000;">';
            		item += '<td>'+No+'</td>';
            		item += '<td>'+v.ModuleName+'</td>';
            		item += '<td>'+v.TypeName+'</td>';
            		item += '<td>'+v.WorkName+'</td>';
            		item += '<td>'+v.PercentageTxt+'</td>';
            		item += '<td>'+v.Status+'</td>';
            		item += '</tr>';
					$(".modal tbody").append(item );
            	});
            }
           	$(".div-loader").hide();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          	$(".div-loader").hide();
            console.log(jqXHR.responseText);
        }
    });
}