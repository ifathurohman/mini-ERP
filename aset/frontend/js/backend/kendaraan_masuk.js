var mobile      = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
// var host        = window.location.origin+'/discovery/';
var host        = window.location.origin+"/";
var url         = window.location.href;
var url_list    = host + "kendaraan_masuk/ajax_list/";
var url_edit    = host + "kendaraan_masuk/edit/";
var url_hapus   = host + "kendaraan_masuk/delete/";
var url_hapus_detail   = host + "kendaraan_masuk/delete_detail/";
var url_active  = host + "kendaraan_masuk/active/";
var url_simpan  = host + "kendaraan_masuk/save/";
var url_update  = host + "kendaraan_masuk/update/";

var save_method;
var save_methodx;
var table;
var page_name;
var url_modul;
var id_panel = 0;

$(document).ready(function() {
    filter_table();
    $("#form-filter").keyup(function(e){
        if(e.keyCode == 13) {
            filter_table();
        }
    });
});
function filter_table()
{
    data_page   = $(".data-page, .page-data").data();
    page_name   = data_page.page_name;
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    batas_panel = data_page.batas_panel;

    url_list    = url_list+url_modul+"/"+modul;
    Group       = $("#form-filter [name=Group]").val();
    StartDate   = $("#form-filter [name=StartDate]").val();
    EndDate     = $("#form-filter [name=EndDate]").val();
    UserID      = $("#form-filter [name=UserID]").val();
    WorkStatusID      = $("#form-filter [name=WorkStatusID]").val();
    Status      = $("#form-filter [name=Status]").val();
    Search      = $("#form-filter [name=Search]").val();
    data_post   = {
        Group: Group,
        StartDate: StartDate,
        EndDate: EndDate,
        UserID: UserID,
        WorkStatusID: WorkStatusID,
        Status: Status,
        Search:Search
    }
    console.log(data_post);
    table = $('#table').DataTable({
      "destroy": true,
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "searching": false,
        "order": [], //Initial no order.
        ajax: {
            url: url_list,
            type: "POST",
            data: data_post,
            error: function (jqXHR, textStatus, errorThrown) {
                // Do something here
                console.log(jqXHR.responseText);
            }
        },
        "columnDefs": [
        {
            "targets": [ -1,0 ], //last column
            "orderable": false, //set not orderable
        },
        ],
    });
}
function cek_aja(element){
    StartDate = $("[name=StartDate]").val();
    if($(element).is(':checked')){
        $("[name=EndDate]").val(StartDate);
    } else {
        $("[name=EndDate]").val("");
    }
}
function tambah(method)
{
    save_method = 'add';
    save_methodx = 'x';
    $(".modal form input, .modal form select, .modal textarea").attr("disabled",false);

    $('#form')[0].reset();
    $('form div, form span').removeClass('has-error');
    $('.help-block').empty();
    $(".v_btn_add_panel, .v_btn_save, .v_btn_add_panel").show();
    $(".v_btn_close").hide();
    if(method == "simulasi"){
        $(".v_modal, .v_back_simulasi").hide();
        $(".v_simulasi").show();
        $('.modal-title').text('Simulasi');
        $("[name=EstimationDate], [name=CustomerDate]").attr("disabled",true);
    } else {
        $(".v_simulasi, .v_back_simulasi").hide();
        $(".v_modal").show();
        $('.modal-title').text('Tambah Data');
        $("[name=EstimationDate], [name=CustomerDate]").attr("disabled",false);
    }
    $('#modal').modal({backdrop: "static"});
    remove_panel_all();
    tambah_panel("modal");
    $(".v_info").empty();
    $(".disabled").attr("disabled",true);
    $('#PegawaiID').select2().select2('val', []); 
}
function view_detail(element){
    dt      = $(element).data();
    id      = dt.id;
    edit(id,"view"); 
    $(".v_modal, .v_btn_close").show();
    $(".v_back_simulasi, .v_simulasi, .v_btn_add_panel, .v_btn_save").hide();
}
function edit(id,method)
{
    save_method     = 'update';
    save_methodx    = 'x';
    $("input, select, textarea").attr("disabled",false);
    $('#form')[0].reset();
    $('form div, form span').removeClass('has-error'); // clear error class
    $('.help-block').empty();
    $("[name=EstimationDate], [name=CustomerDate]").attr("disabled",false);
    $(".v_simulasi, .v_back_simulasi, .v_btn_close").hide();
    $(".v_modal, .v_btn_save").show();
    remove_panel_all();
    $('#PegawaiID').select2().select2('val', []); 
    if(modul == "company"){
       save_method = "view";
       modal_title = "View Detail";
       $("#btnSave").hide(); 
       $("#form input, #form textarea").attr("disabled",true);
    } else {
        modal_title = 'Ubah Data ';
       $(".disabled").attr("disabled",true);
    }
    if(method == "view"){
        modal_title = "Lihat Data";
        save_methodx = "view";
    }


    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(json)
        {
            if(json.HakAkses == "rc"){
                console.log(json);
            }
            v = json.Data;
            $("[name=TransactionListID]").val(v.TransactionListID);
            $("[name=Code]").val(v.Code);
            $("[name=Name]").val(v.Name);
            $("[name=Client]").val(v.Client);
            $("[name=PICID]").val(v.PICID);
            $("[name=StartDate]").val(v.StartDate);
            $("[name=EndDate]").val(v.EndDate);
            $("[name=Remark]").val(v.Remark);
            $('.pegawai_select').select2().select2('val', [v.PegawaiID]);

            Condition = v.Condition;
            if(Condition){
                $.each(Condition,function(i,v){
                    $("#"+v).prop("checked",true);
                });
            }
            Cek = v.Cek;

            if(json.ListData){
                $.each(json.ListData,function(i,v){
                    mtd = "edit";
                    if(Cek == 0){
                        mtd = "editx";
                    }
                    tambah_panel("modal",v,mtd);
                });
            }

            if(method == "view"){
                 $(".modal form input, .modal form select, .disabled, .modal textarea").attr("disabled",true);
            } else {
                $(".v_btn_add_panel").show();
                if(v.Cek > 0){
                    $("[name=RegisterDate]").attr("disabled",true);
                    $(".modal form .table-add  input, .modal form .table-add  select, .disabled, .modal .checkbox input").attr("disabled",true);
                }
            }
            if(method == "view"){
                $('#modal-view').modal("show");
            } else {
                $('#modal').modal({backdrop: "static"});
            }
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
function save_simulasi(method){
    if(method == "kalkulasi_simulasi"){
        $('#btn-simulasi').button('loading');
        save("kalkulasi_simulasi");
    } else if(method == 'back_simulasi'){
        $(".v_simulasi").show(300);
        $(".v_modal, .v_back_simulasi").hide(300);
        $(".modal-title").text("Simulasi");
        $("[name=EstimationDate], [name=CustomerDate]").attr("disabled",true);
    } else if(method == "cek_tanggal"){
        $('#btn-cek-tanggal').button('loading');
        save("cek_tanggal");
    } else {
        if(warning_save == 1){        
            swal({   title: "Info",
                     text : "Apakah anda yakin akan menyimpan data dengan tanggal tersebut ?",
                     type: "warning",   
                     showCancelButton: true,   
                     confirmButtonColor: "#DD6B55",   
                     confirmButtonText: "Ya",   
                     cancelButtonText: "Tidak",   
                     closeOnConfirm: true,   
                     closeOnCancel: true }, 
                     function(isConfirm){   
                        if (isConfirm) { 
                            save_method = 'add';
                            $(".v_simulasi").hide(300);
                            $(".v_modal, .v_back_simulasi").show(300);
                            $(".modal-title").text("Tambah Data");
                            $("[name=EstimationDate], [name=CustomerDate]").attr("disabled",false);
                        }
            });
        } else {
            save_method = 'add';
            $(".v_simulasi").hide(300);
            $(".v_modal, .v_back_simulasi").show(300);
            $(".modal-title").text("Tambah Data");
            $("[name=EstimationDate], [name=CustomerDate]").attr("disabled",false);
        }

    }
}
var warning_save = 0;
function save(method)
{
    warning_save = 0;
    $('#btnSave').button('loading');
    var url;
    if(method == 'add' || method == "close" || method == "new") {
        if(save_method == "add"){
            url         = url_simpan + "save";
        } else {
            url         = url_update;
            
        }
    } else if(method == "kalkulasi_simulasi"){
        url         = url_simpan + "kalkulasi_simulasi";
    } else if(method == "save_simulasi"){
        url         = url_simpan + "save_simulasi";
    } else if(method == "cek_tanggal"){
        url         = url_simpan + "cek_tanggal";
    }
    console.log(method);
    console.log(save_method);
    console.log(url);

    $('form div').removeClass('has-error'); // clear error class
    $('.help-block').empty();
    $("input,select,div").attr("disabled",false);
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(json)
        {
            if(json.status) //if success close modal and reload ajax table
            {
                if(save_method == "add" || save_method == "update"){
                    if(method == "close"){
                        $('#modal').modal("hide");
                    } else if(method == "new"){
                        save_method = "add";
                        $('#form')[0].reset();
                        $('form div').removeClass('has-error'); // clear error class
                        $('.help-block').empty();

                    }
                    if(method == "new" || method == "close"){ 
                        $(".v_btn_add_panel").show(300);
                        $(".v_info").empty();
                        remove_panel_all();
                        tambah_panel("modal");
                        reload_table();
                    }
                }
                if(method == "kalkulasi_simulasi"){
                    ds = json.data;
                    console.log(json);
                    $("[name=EstimationDate]").val(ds.EstimationDate);
                    $("[name=CustomerDate]").val(ds.CustomerDate);

                    $(".v_info").empty();
                    item = '<ul>';
                    item += '<li><strong>Informasi</strong></li>';
                    item += '<li>Total Hari : '+ds.TotalHari+'</li>';
                    item += '<li>Total Hari Pengerjaan : '+ds.TotalHariPengerjaan+'</li>';
                    item += '<li>Total Panel : '+ds.TotalPoint+'</li>';
                    item += '<li>Rata - Rata : '+ds.RataRata+'</li>';
                    item += '<li>Tanggal Selesai : '+ds.FinishDate+'</li>';
                    item += '<li>Tanggal Checkout : '+ds.CheckoutDate+'</li>';
                    item += '</ul>';
                    $(".v_info").append(item);
                    
                }

                if(method == "kalkulasi_simulasi" || method == "cek_tanggal"){
                    warning_level2 = 0;
                    warning_level2pesan = "";
                    $.each(json.warning,function(i,vw){
                        if(vw.Level == 1){
                            $('[name="'+vw.Name+'"]').next().addClass('has-error');
                            $('[name="'+vw.Name+'"]').next().text(vw.Pesan);
                        }
                        if(vw.Level == 2){
                            warning_level2 = 1;
                            warning_level2pesan = vw.Pesan;
                        }
                        
                    });
                    if(warning_level2 == 1){
                        warning_save = 1;
                        swal('Info',warning_level2pesan,'warning');
                    }
                }

            }
            else
            {
                
                if(json.inputerror.length > 0){
                    for (var i = 0; i < json.inputerror.length; i++)
                    {
                        label = $('[name="'+json.inputerror[i]+'"]').parent().parent().find("label").text();
                        $('[name="'+json.inputerror[i]+'"]').parent().addClass('has-error'); 
                        if(json.inputerror[i] == "BankID"){
                            $("."+json.inputerror[i]+"Alert").text(json.error_string[i]);
                        } else {
                            $('[name="'+json.inputerror[i]+'"]').next().text(json.error_string[i]);
                        }
                    }
                }
                if(json.message){
                    swal('',json.message,'warning');
                }
            }
            $('#btnSave, .btn').button('reset');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Gagal menambah atau pengubah data');
            $('#btnSave, .btn').button('reset');
            console.log(jqXHR.responseText);
        }
    });
}
function hapus(id)
{
    swal({   title: "Apa anda yakin ?",   
             // text: "You will not be able to recover this data !",   
             type: "warning",   
             showCancelButton: true,   
             confirmButtonColor: "#DD6B55",   
             confirmButtonText: "Ya",   
             cancelButtonText: "Tidak",   
             closeOnConfirm: true,   
             closeOnCancel: true }, 
             function(isConfirm){   
                if (isConfirm) { 
                    $.ajax({
                        url : url_hapus+id+"/nonactive",
                        type: "POST",
                        dataType: "JSON",
                        success: function(data){
                            swal("Hapus", "data berhasil di hapus", "success");   
                            reload_table();
                        },
                        error: function (jqXHR, textStatus, errorThrown){
                            swal('Error deleting data');
                        }
                    });
                }
    });
}
function active(id,status){

    if(status == "active"){
        pesan = "Apa anda yakin akan mengaktifkan data ini ?";
        pesan_success = "data berhasil diaktifkan";
    } else {
        pesan = "Apa anda yakin akan menonaktifkan data ini ?";
        pesan_success = "data berhasil dinonaktifkan";
    }
    swal({   
            // type:"input",
            title: "Info",   
            text: pesan,   
            // type: "warning",   
            showCancelButton: true,   
            // confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ya",   
            cancelButtonText: "Tidak",   
            closeOnConfirm: true,   
            closeOnCancel: true }, 
            function(isConfirm){   
                if (isConfirm) { 
                    $.ajax({
                        url : url_active+id+"/"+status,
                        type: "POST",
                        dataType: "JSON",
                        success: function(data){
                            // swal("", pesan_success, "success");   
                            reload_table();
                        },
                        error: function (jqXHR, textStatus, errorThrown){
                            swal('gagal melakukan ubah data');
                        }
                    });
                }
    });

}
function refresh_modal(){
    $('#form')[0].reset(); // reset form on modals
    remove_panel_all();
    tambah_panel();
    $(".v_info").empty();
    $('#btn-refresh').button('loading');
    $('#btn-refresh').button('reset');
    $('form div, form span').removeClass('has-error'); // clear error class
    $('.help-block').empty();

}
function tambah_panel(mod,value,method){

    id_panel += 1;
    if(mod == "simulasi"){
        modal = "#modal-simulasi";
    } else {
        modal = "#modal";
    }
    jumlah_row  = $(modal + " .table-add tbody tr").length + 1;
    class_item  = 'row-'+id_panel;
    TransactionListDetailID = "";
    ModuleID    = "";
    Name        = "";
    Point       = "";
    Keterangan  = "";
    LHCek       = "";
    RHCek       = "";
    if(value){
        TransactionListDetailID = value.TransactionListDetailID;
        ModuleID = value.ModuleID;
        Point    = value.Point;
        Name     = value.Name;
        Keterangan = value.Keterangan;

        if(Keterangan == null){
            Keterangan = "";
        }

        if(value.Side == 1){
            LHCek = 'checked="checked"';
        } else if(value.Side == 2){
            RHCek = 'checked="checked"';
        } else if(value.Side == 0){
            LHCek = 'checked="checked"';
            RHCek = 'checked="checked"';
        }
    }
    item = '<tr class="'+class_item+'">';
    // item += '<td>Module '+jumlah_row+'</td>';
    item += '<td>';
    item += '<input type="hidden" name="TransactionListDetailID[]" value="'+TransactionListDetailID+'">';
    item += '<input type="hidden" name="NomerRow[]" value="'+jumlah_row+'">';
    item += '<input type="hidden" name="ModuleID[]" class="panelid-'+class_item+'" value="'+ModuleID+'">';
    item += '<input type="text" name="panel[]" placeholder="Pilih Modul" class="form-control pointer panelname-'+class_item+'" readonly="readonly" data-class="'+class_item+'" data-modul="'+mod+'" onclick="modal_panel(this)" value="'+Name+'">';
    item += '</td>';
    item += '<td><input type="text" name="Keterangan[]" value="'+Keterangan+'" placeholder="Keterangan" class="form-control"> </td>';
    item += '<td style="width:100px;"><input type="text" name="Point[]" class="form-control panelpoint-'+class_item+'" onkeydown="validateNumber(event);" maxlength="4" size="4"  value="'+Point+'" placeholder="hari"></td>';
    // item += '<td>';
    // item += '<select class="form-control panelselect-'+class_item+'" name="Type[]">';

    // JenisPekerjaan = $(".jenis_pekerjaan_select option");
    // $.each(JenisPekerjaan,function(i,v){
    //     dtx = $(v).data();
    //     console.log(dtx);
    //     item += '<option value="'+dtx.id+'">'+dtx.name+'</option>';
    // });
    // item += '</select>';
    // item += '</td>';


    item_remove = "";
    if(method != "edit"){
        if(value && value.TransactionListDetailID){
            item_remove = '<td><a href="javascript:void(0)" onclick="remove_panel(this)" data-transactiondetailid="'+TransactionListDetailID+'" data-mod="'+mod+'" data-row="'+id_panel+'" data-old="1" class="btn btn-white btn-xs"><i class="ti-trash"></i></a></td>';
        } else {
            item_remove = '<td><a href="javascript:void(0)" onclick="remove_panel(this)" data-transactiondetailid="'+TransactionListDetailID+'" data-mod="'+mod+'" data-row="'+id_panel+'" data-old="0" class="btn btn-white btn-xs"><i class="ti-trash"></i></a></td>';
        }      
    }

    if(save_methodx == "view"){
        item_remove = "";
    }

    item += item_remove;
    item += '</tr>';
    if(mod == "simulasi"){
        $("#modal-simulasi .table-add tbody").append(item);
    } else {
        $("#modal .table-add tbody, #modal-view .table-add tbody").append(item);
    }
    if(value && value.TransactionListDetailID){
        $(".panelselect-"+class_item).val(value.Type);
    }

}
function remove_panel(element)
{
    de          = $(element).data();
    row         = de.row; 
    id          = de.transactiondetailid; 
    old         = de.old; 
    jumlah_row  = $(".table-add tbody tr").length;
    if(jumlah_row == 1){
        swal('Maaf, module ini tidak bisa di hapus');
        return;
    }
    if(old == 0){
        $(".table-add tbody .row-"+row).remove();
    } else {
        swal({   
            // type:"input",
            title: "Info",   
            text: "Apa anda yakin akan menghapus data ini ?",   
            // type: "warning",   
            showCancelButton: true,   
            // confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ya",   
            cancelButtonText: "Tidak",   
            closeOnConfirm: true,   
            closeOnCancel: true }, 
            function(isConfirm){   
                if (isConfirm) { 
                    $.ajax({
                        url :url_hapus_detail+id,
                        type: "POST",
                        dataType: "JSON",
                        success: function(data){
                            // swal("", pesan_success, "success");   
                            $(".table-add tbody .row-"+row).remove();
                        },
                        error: function (jqXHR, textStatus, errorThrown){
                            swal('gagal melakukan ubah data');
                        }
                    });
                }
        });
    }
}
function remove_panel_all(mod)
{
    id_panel = 1;
    if(mod == "simulasi"){
        $("#modal-simulasi .table-add tbody").empty();
    } else if(mod == "simpan"){
        $("#modal .table-add tbody").empty();
    } else {
        $(".table-add tbody").empty();
    }
}

