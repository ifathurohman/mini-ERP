var mobile      = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host        = window.location.origin+"/";
var url         = window.location.href;
var numberWeek  = 0;
var TransactionListID;
$(document).ready(function() {
    dt = $(".data-div").data();
    TransactionListID = dt.projectid;
    if(TransactionListID){
        // $(".li-option").hide();
    }
    load_data();
    $(".portlet-widgets .ion-refresh").click(function(){
        get_project();
    });
});
function get_project(){
    TransactionListID = $(".sidebar-project [name=TransactionListID]").val();
    load_data();
}
function load_data()
{
    $(".loading, .div-status-project .overlay").show();
    data_post = {
        TransactionListID : TransactionListID
    }
    console.log(data_post);
    $.ajax({
        url : host + "project_preview/load_data/"+TransactionListID,
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data){
            if(data.HakAkses == "rc"){
                console.log(data);
            }
            if(data.Data){
                $(".portlet-title").text(data.Data.Name);
            } else {
                $(".portlet-title").text("Project Status");
            }

            $(".sidebar-project .ul-module").empty();
            $(".table-project thead").empty();
            $(".table-project tbody").empty();
            $(".table-project tfoot").empty();
            if(data.ListData.length > 0){
                $.each(data.ListData,function(i,v){
                    lihoverid   = 'li-hover-id-'+(i+1);
                    subicon     = '';
                    subclass    = '';
                    if(v.SubMenu.length > 0){
                        subicon     = '<span class="menu-arrow"></span>';
                    }
                    item = '<li class="has_sub"><a href="javascript:void(0)" class="li-hover '+lihoverid+'" data-li="'+lihoverid+'" data-id="'+v.ModuleName+'" title="'+v.ModuleName+'"><span>'+v.ModuleName+'</span>'+subicon+'</a>';
                    // <i class="ti-crown"></i>
                    if(v.SubMenu.length > 0){
                        item += '<ul class="list-unstyled" style="display:none;">';
                        $.each(v.SubMenu,function(ii,vv){
                            subicon     = '';
                            subclass    = '';
                            if(vv.Detail.length > 0){
                                subicon     = '<span class="menu-arrow"></span>';
                            }
                            lihoveridx = lihoverid + '-'+ii;
                            item += '<li><a href="#" class="li-hoverx '+lihoveridx+'" data-li="'+lihoveridx+'" data-id="'+vv.WorkerName+'" title="'+vv.WorkerName+'">'+vv.WorkerName+subicon+'</a>';
                            if(vv.Detail.length > 0){
                                item += '<ul class="list-unstyled" style="display:none;">';
                                $.each(vv.Detail,function(zi,z){
                                    lihoveridz = lihoverid + '-z-'+zi;
                                    item += '<li><a href="#" class="li-hoverxx '+lihoveridz+'" data-li="'+lihoveridz+'" data-id="'+z.WorkName+'" title="'+z.WorkName+' ('+z.TypeName+')">'+z.WorkName+' ('+z.TypeName+')</a></li>';
                                });
                                item += '</ul>';
                            }
                            item += '</li>';
                        });                      
                        item += '</ul>';
                    }
                    item += '</li>';
                    $(".sidebar-project .ul-module").append(item);
                });



                tha = '<tr>';
                $.each(data.BulanArray,function(a,a){
                    thlabel = a.split("-"); 
                    Thn     = thlabel[0];
                    Bln     = thlabel[1];
                    BlnName = thlabel[2];
                    colspan = 0;
                    $.each(data.TglArray,function(b,b){
                        if(Thn == b.Thn && Bln == b.Bln){
                            colspan += 1;
                        }
                    });
                    colspanx = '';
                    if(colspan>0){
                        colspanx = 'colspan="'+colspan+'"';
                    }
                    tha += '<th '+colspanx+'>'+BlnName+'</th>';   
                });
                tha += '</tr>';

                thb = '<tr>';
                $.each(data.TglArray,function(ix,x){
                    classtd = '';
                    if(x.TglName == "Minggu" || x.TglName == "Sabtu"){
                        classtd = ' td-libur';
                    }
                    thb += '<td class="'+classtd+' td-tgl">'+x.Tgl+'</td>';
                });
                thb += '</tr>';

                thead = tha;
                thead += thb; 

                tfoot = thb;
                tfoot += tha;

                $(".table-project thead").append(thead);
                $(".table-project tfoot").append(tfoot);
                //perkumpulan table------------------------------------------------------------------------------------
                $.each(data.ListData,function(i,v){
                    label_txt   = v.ModuleName;
                    lihoverid   = 'li-hover-id-'+(i+1);
                    item = '<tr class="li-hover '+lihoverid+'" data-li="'+lihoverid+'" data-id="'+v.ModuleName+'">';
                    bg = "";
                    $.each(data.TglArray,function(ix,x){
                        title_txt   = label_txt;
                        classtd     = '';
                        if(x.TglName == "Minggu" || x.TglName == "Sabtu"){
                            classtd = ' td-libur';
                        }
                        $.each(v.SubMenu,function(ii,vv){
                            $.each(vv.Detail,function(zi,z){
                                if(z.StartDate == x.Tanggal){
                                    bg = "bg-td";
                                }
                                if(z.EndDateStop == x.Tanggal){
                                    bg = "";
                                }
                            });
                        });
                        item += '<td class="'+classtd+' '+bg+'" title="'+title_txt+'"></td>';
                    });
                    item += '</tr>';
                    if(v.SubMenu.length > 0){
                        $.each(v.SubMenu,function(ii,vv){
                            title_txt  = label_txt + ' - ' + vv.WorkerName;
                            lihoveridx = lihoverid + '-'+ii;
                            item += '<tr class="li-hover tr-detail '+lihoverid +'-tr '+lihoveridx+'" data-li="'+lihoveridx+'" data-id="'+vv.WorkerName+'" style="display:none;">';
                            // item += '<td>'+vv.WorkerName+'<td>';
                            bg = "";
                            $.each(data.TglArray,function(ix,x){
                                classtd = '';
                                if(x.TglName == "Minggu" || x.TglName == "Sabtu"){
                                    classtd = ' td-libur';
                                }
                                $.each(vv.Detail,function(zi,z){
                                    if(z.StartDate == x.Tanggal){
                                        bg = "bg-td";
                                    }
                                    if(z.EndDateStop == x.Tanggal){
                                        bg = "";
                                    }
                                });
                                item += '<td class="'+classtd+' '+bg+'" title="'+title_txt+'"></td>';
                            });
                            item += '</tr>';

                            if(vv.Detail.length > 0){
                                $.each(vv.Detail,function(zi,z){
                                    lihoveridz = lihoverid + '-z-'+zi;
                                    item += '<tr class="li-hoverx tr-detailx '+lihoveridx+'-trx '+lihoveridz+'" data-li="'+lihoveridz+'" data-id="'+z.WorkerName+'" style="display:none;">';
                                    // item += '<td><td>';
                                    bg = "";
                                    $.each(data.TglArray,function(ix,x){
                                        classtd = '';
                                        if(x.TglName == "Minggu" || x.TglName == "Sabtu"){
                                            classtd = ' td-libur';
                                        }
                                        if(z.StartDate == x.Tanggal){
                                        	bg = "bg-td";
                                        }
                                        if(z.EndDateStop == x.Tanggal){
                                        	bg = "";
                                        }
                                        title = z.WorkName+' ('+z.TypeName+') - tanggal : ' + z.StartDateTxt +' - ' + z.EndDateTxt;
                                        title = title_txt + ' - ' + title;
                                        item += '<td class="'+classtd+' '+bg+'" title="'+title+'"></td>';
                                    });
                                    item += '</tr>';
                                });
                            }


                        });                    
                    }
                    $(".table-project tbody").append(item);
                });
                load_js();
                li_hover();
                mouse_drag();
            }
            $(".loading, .div-status-project .overlay").hide();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            console.log(jqXHR.responseText);
            $(".loading, .div-status-project .overlay").hide();
        }
    });
}

function load_js()
{
  var head   = document.getElementsByTagName('head')[0];
  var script = document.createElement('script');
  // script.src = host + 'assets/js/bootstrap.min.js';
  // script.src = host + 'assets/js/jquery.slimscroll.js';
  // script.src = host + 'assets/js/wow.min.js';
  // script.src = host + 'assets/js/jquery.core.js';
script.src = host + 'assets/js/jquery.app.js';
// head.appendChild(script);
$(script).insertAfter('.js-app');

}
function li_hover(){
    $('.li-hover, .li-hoverx, .li-hoverxx').hover(function () {
        $(".li-hover, .li-hoverx, .li-hoverxx").removeClass("li-hover-gb");
        dt = $(this).data();
        li = dt.li;
        $("."+li).addClass("li-hover-gb");
    });

    $(".ul-module .li-hover").click(function(){
        dt = $(this).data();
        li = dt.li;         
        if($(this).hasClass("subdrop")) {
          $(".tr-detail, .tr-detailx").slideUp(350);
          $("."+li+'-tr').slideDown(350);
        }else if(!$(this).hasClass("subdrop")) {
         $(".tr-detail, .tr-detailx").slideUp(350);
        }
    });

    $(".ul-module .li-hoverx").click(function(){
        dt = $(this).data();
        li = dt.li;         
        if($(this).hasClass("subdrop")) {
          $(".tr-detailx").slideUp(350);
          $("."+li+'-trx').slideDown(350);
        }else if(!$(this).hasClass("subdrop")) {
         $(".tr-detailx").slideUp(350);
        }
    });

}



function mouse_drag(){
    var mx = 0;
    $(".data-project").on({
      mousemove: function(e) {
        var mx2 = e.pageX - this.offsetLeft;
        if(mx) this.scrollLeft = this.sx + mx - mx2;
      },
      mousedown: function(e) {
        this.sx = this.scrollLeft;
        mx = e.pageX - this.offsetLeft;
      }
    });
    $(document).on("mouseup", function(){
      mx = 0;
    });


    $('.data-project').mousewheel(function(e, delta) {
        this.scrollLeft -= (delta * 40);
        e.preventDefault();
    });
}
