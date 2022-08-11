var mobile      = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host        = window.location.origin+"/";
var url         = window.location.href;
var numberBln   = 0;
var numberBln   = 0;
var Tahun       = "none";
$(document).ready(function() {
    get_data_kalender();
    $(".portlet-widgets .ion-refresh").click(function(){
        get_data_kalender();
    });
});
function data_month(mod){
    if(mod == "next"){
        numberBln += 1;
    } else if(mod == "prev") {
        if(numberBln == 1){
            numberBln = "none";
        } else {
            numberBln -= 1;
        }
    } else {

    }
    get_data_kalender();
}
function get_data_kalender()
{
    $(".div-loader").show();
    Tahunx = $(".filter [name=Tahun]").val();
    if(Tahunx != "none"){
        Tahun = Tahunx;
    }
    data_post = {
        Tahun : Tahun,
        numberBln : numberBln
    }
    console.log(data_post);
    $.ajax({
        url : host + "dashboard/data_kalender",
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data)
        {
            if(data.HakAkses == "rc"){
                console.log(data);
            }
            Tahun = data.ListTanggal.Tahun;
            v = data.Data;
            DataKalender(data);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            console.log(jqXHR.responseText);
        }
    });
}
function DataKalender(data){
    numberBln = data.ListTanggal.numberBln;
    $(".div-loader").hide();
    $(".numberYearx").text(data.ListTanggal.Tahun);
    $(".numberBlnx").text(data.ListTanggal.Bulan);
    $(".data-kalender").empty();
    itemtgl     = '';
    itemhari    = '';
    $.each(data.ListHari,function(i,v){
        itemhari += '<th class="th-mobil" style="text-align:center;text-transform:uppercase;">'+v.hari+'</th>';
    });
    $.each(data.ListTanggal.ListWeek,function(i1,a){
        itemtgl += '<tr>';
        $.each(data.ListHari,function(i2,b){
            itemtgl += '<td><div style="min-height:100px;">';
                $.each(data.ListTanggal.ListTanggal,function(i3,c){
                    if(a == c.minggu && b.day == c.day){
                        TotalMobilMasuk     = 0;
                        TotalMobilKeluar    = 0;
                        itemreg = '';
                        itemout = '';
                        $.each(data.KendaraanMasuk,function(i4,d){
                            if(c.tgl == d.Date && d.Type == 'register'){
                                TotalMobilMasuk += 1;
                                itemreg += '<li style="padding:5px;">'+d.Brand+" - "+d.Plate+'</li>';
                            }
                            if(c.tgl == d.Date && d.Type == 'checkout'){
                                TotalMobilKeluar += 1;
                                itemout += '<li style="padding:5px;">'+d.Brand+" - "+d.Plate+'</li>';
                            }
                        });
                        itemtgl += '<div class="td-date">'+c.number+'</div>';
                        if(TotalMobilMasuk > 0){
                            itemtgl += '<div onclick="lihat_item_detail(this)" data-toggle="collapse" data-target="#item-register-'+i3+'" aria-expanded="false" aria-controls="collapseExample" class="label label-success pointer" data-id="reg'+i3+'" data-type="register">Masuk : <span style="font-size:11pt">'+TotalMobilMasuk+'</span></div>';
                            itemtgl += '<br/>';
                        }
                        if(TotalMobilKeluar > 0){
                            itemtgl += '<div onclick="lihat_item_detail(this)" data-toggle="collapse" data-target="#item-checkout-'+i3+'" aria-expanded="false" aria-controls="collapseExample" class="label label-danger pointer" data-id="out'+i3+'" data-type="checkout">Keluar : <span style="font-size:11pt">'+TotalMobilKeluar+'</span></div>';
                        }

                        itemtgl +='<ul id="item-register-'+i3+'" class="collapse list-unstyled transaction-list item-register item-register-reg'+i3+'" style="border:1px solid #ebeff2; border-radius:2px;margin-top:5px;">';
                        itemtgl += '<li style="padding:5px;font-weight:bold;text-align:center;color:#fff;background:#81c868">Mobil Masuk</li>';
                        itemtgl += itemreg;
                        itemtgl +='</ul>';

                        itemtgl +='<ul id="item-checkout-'+i3+'" class="collapse list-unstyled transaction-list item-checkout item-checkout-out'+i3+'" style="border:1px solid #ebeff2; border-radius:2px; margin-top:5px;">';
                        itemtgl += '<li style="padding:5px;font-weight:bold;text-align:center;color:#fff;background:#f05050">Mobil Keluar</li>';
                        itemtgl += itemout;
                        itemtgl +='</ul>';

                    }
                });
            itemtgl += '</div></td>';
        });
        itemtgl += '</tr>';
    });
    itemtbl = '';
    itemtbl += '<table class="table table-bordered table-kalender">';
    itemtbl += '<thead>';
    itemtbl += '<tr>';          
    itemtbl += itemhari;
    itemtbl += '</tr>';
    itemtbl += '</thead>';
    itemtbl += '<tbody>';
    itemtbl += itemtgl;
    itemtbl += '</tbody>';
    itemtbl += '</table>';
    $(".data-kalender").append(itemtbl);

}