var mobile      = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
// var host        = window.location.origin+'/discovery/';
var host        = window.location.origin+"/";
var url         = window.location.href;
var url_list    = host + "module/ajax_list/";
var url_hapus   = host + "module/delete/";
var url_active  = host + "module/active/";
var url_simpan  = host + "module/save";
var url_edit    = host + "module/edit/";
var url_update  = host + "module/update/";
var url_hapus_detail  = host + "module/delete_detail/";
var save_method; //for save method string
var table;
var page_name;
var url_modul;  
// var id_kontak;
$(document).ready(function() {
    data_page   = $(".data-page, .page-data").data();
    page_name   = data_page.page_name;
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    if(modul == "company"){
        url_list = url_list_company+url_modul+"/"+modul;
    } else {
        url_list = url_list+url_modul+"/"+modul;
    }
    url_list    = url_list+url_modul;
    table       = $('#table').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": url_list,
            "type": "POST"
        },
        //Set column definition initialisation properties.
        "columnDefs": [
        {
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],
    });
    $("[name=Type]").change(function(){
        if($(this).val() == "single"){
            $(".v_group").hide(300);
            $("[name=TotalPoint]").attr("readonly",false);
        } else {
            $(".v_group").show(300);
            $("[name=TotalPoint]").attr("readonly",true);
        }
    });
    $(".table-add tbody input").change(function(){
        console.log("ubah");
        hitung_point();
    });
});
function tambah()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('form div').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal({backdrop: "static"}); // show bootstrap modal
    $('.modal-title').text('Tambah Data'); // Set Title to Bootstrap modal title
    $('[name="crud"]').val("insert");
    $(".v_group").hide(300);
    remove_panel_all();
    tambah_panel("modal");
    $("[name=TotalPoint]").attr("readonly",false);
}
function edit(id)
{
    save_method = 'update';
    $('#form')[0].reset();
    $('form div').removeClass('has-error'); // clear error class
    $('.help-block').empty();
    $(".v_group").hide(300);
    remove_panel_all();
    $("[name=TotalPoint]").attr("readonly",false);
    if(modul == "company"){
       save_method = "view";
       modal_title = "View Detail";
       $("#btnSave").hide(); 
       $("#form input, #form textarea").attr("disabled",true);
    } else {
        modal_title = 'Ubah Data ';
    }
    console.log(url_edit);
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            console.log(data);
            if(data.HakAkses == "rc"){
                console.log(data);
            }
            v = data.data;
            $('[name="ModuleID"]').val(v.ModuleID);
            $('[name="CompanyID"]').val(v.CompanyID);
            $('[name="Code"]').val(v.Code);
            $('[name="Name"]').val(v.Name);
            $('[name="TotalPoint"]').val(v.Point);
            $('[name="Mandays"]').val(v.Mandays);
            $('[name="Remarks"]').val(v.Remarks);

            if(v.Type == 1){
                $("[name=Type]#group").prop("checked",true);
                $("[name=TotalPoint]").attr("readonly",true);
                $(".v_group").show(300);
            } else {
                $("[name=TotalPoint]").attr("readonly",false);
                $("[name=Type]#single").prop("checked",true);
            }
            if(data.ListData && data.ListData.length > 0){
                $.each(data.ListData,function(i,v){
                    tambah_panel("modul",v);
                });
            } else {
                tambah_panel("modul");
            }

            

            $('#modal').modal({backdrop: "static"});
            $('.modal-title').text(modal_title);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);
            
        }
    });
}
function reload_table()
{
    table.ajax.reload(null,true); //reload datatable ajax
}
function save(method)
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable

    var url;
    if(save_method == 'add') {
        url = url_simpan;
    } else if(save_method == "update"){
        url = url_update;
    }
    $.ajax({
        url : url,
        type: "POST",    
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {
            console.log(data);
            if(data.status) //if success close modal and reload ajax table
            {
                if(method == "new"){
                    save_method = 'add';
                    $('#form')[0].reset();
                    $('form div').removeClass('has-error');
                    $('.help-block').empty();
                    $('.modal-title').text('Tambah Data');
                    $(".v_group").hide(300);
                    remove_panel_all();
                    tambah_panel("modal");
                    $("[name=TotalPoint]").attr("readonly",false);
                } else {
                    $('#modal').modal("hide");
                }
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
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable
            console.log(jqXHR.responseText);
            console.log('error');
        }
    });
}
function hapus(id)
{
    swal({   title: "Are you sure?",   
             // text: "You will not be able to recover this data !",   
             type: "warning",   
             showCancelButton: true,   
             confirmButtonColor: "#DD6B55",   
             confirmButtonText: "Yes, delete it!",   
             cancelButtonText: "No, cancel it!",   
             closeOnConfirm: false,   
             closeOnCancel: false }, 
             function(isConfirm){   
                 if (isConfirm) { 
                    $.ajax({
                        url : url_hapus+id+"/nonactive",
                        type: "POST",
                        dataType: "JSON",
                        success: function(data)
                        {
                            swal("Hapus", "data berhasil di hapus", "success");   //if success reload ajax table
                            reload_table();
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            swal('Error deleting data');
                        }
                    });
                    swal("Deleted!", "Your data has been deleted.", "success");   } 
                 else {
                     swal("Canceled", "Your data is safe :)", "error");   } 
    });
}
function active_data(id,status){

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
var id_panel = 0;
function tambah_panel(mod,val){
    ModuleDetailID  = null;
    ModuleID        = null;
    Name            = "";
    Point           = "";
    if(val){
        ModuleDetailID  = val.ModuleDetailID;
        ModuleID        = val.ModuleID;
        Name            = val.Name;
        Point           = val.Point;
    }
    id_panel    += 1;
    modal       = "#modal";
    jumlah_row  = $(modal + " .table-add tbody tr").length + 1;
    class_item  = 'row-'+id_panel;
    item = '<tr class="'+class_item+'">';
    // item += '<td>Module '+jumlah_row+'</td>';
    item += '<td>';
    item += '<input type="hidden" name="ModuleDetailID[]" value="'+ModuleDetailID+'">';
    item += '<input type="hidden" name="NomerRow[]" value="'+jumlah_row+'">';
    item += '<input type="hidden" name="ModuleIDx[]" class="panelid panelid-'+class_item+'" onchange="hitung_point(this)" value="'+ModuleID+'">';
    if(val){
        item += '<input placeholder="Pilih Modul" title="Pilih Modul" type="text" name="panel[]" class="form-control pointer-no panelname-'+class_item+'" readonly="readonly" data-class="'+class_item+'" data-modul="'+mod+'" data-type="single" value="'+Name+'" readonly="readonly">';
        item += '</td>';
        item += '<td style="width:100px;"><input type="text" name="Point[]" class="form-control pointer-no panelpoint panelpoint-'+class_item+'"  maxlength="4" size="4" value="'+Point+'" readonly="readonly"></td>';
        item += '<td><a href="javascript:void(0)" onclick="remove_panel(this)" data-moduledetailid="'+ModuleDetailID+'" data-mod="'+mod+'" data-row="'+id_panel+'" data-old="1" class="btn btn-white btn-xs"><i class="ti-trash"></i></a></td>';
    } else {
        item += '<input placeholder="Pilih Modul" title="Pilih Modul" type="text" name="panel[]" class="form-control pointer panelname-'+class_item+'" readonly="readonly" data-class="'+class_item+'" data-modul="'+mod+'" data-type="single" onclick="modal_panel(this)" value="'+Name+'">';
        item += '</td>';
        item += '<td style="width:100px;"><input type="text" name="Point[]" class="form-control panelpoint panelpoint-'+class_item+'" onkeydown="validateNumber(event);" onkeyup="hitung_point(this)" maxlength="4" size="4" value="'+Point+'" readonly="readonly"></td>';
        item += '<td><a href="javascript:void(0)" onclick="remove_panel(this)" data-moduledetailid="'+ModuleDetailID+'" data-mod="'+mod+'" data-row="'+id_panel+'" data-old="0" class="btn btn-white btn-xs"><i class="ti-trash"></i></a></td>';
    }
    item += '</tr>';
    if(mod == "simulasi"){
        $("#modal-simulasi .table-add tbody").append(item);
    } else {
        $("#modal .table-add tbody").append(item);
    }
}
function remove_panel(element)
{
    de          = $(element).data();
    row         = de.row; 
    id          = de.moduledetailid; 
    old         = de.old; 
    jumlah_row  = $(".table-add tbody tr").length;
    console.log(jumlah_row);
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
                            hitung_point("modal");
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
    $(".table-add tbody").empty();
}
function hitung_point(element){
    TotalPoint  = 0;
    ListElement = $(".table-add tbody input");
    $.each(ListElement,function(i,v){
        data        = $(v).data();
        data_item   = data.class;
        ModuleID    = $(".panelid-"+data_item).val();
        Name        = $(".panelname-"+data_item).val();
        Point       = $(".panelpoint-"+data_item).val();
        if(!Point){ Point = 0; }
        Point       = parseFloat(Point);
        if(ModuleID > 0){
            TotalPoint += Point;
        }
    });
    $("[name=TotalPoint]").val(TotalPoint);

}