var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host = window.location.origin+'/';
var url = window.location.href;
var page_login = host + "main/login";
var page_register = host + "main/register";
var save_method; //for save method string
var table;
var url_list = host + "admin/ajax_list/";
var url_list_company = host + "admin/ajax_list_company/";
var url_edit = host + "admin/ajax_edit/";
var url_hapus = host + "admin/ajax_delete/";
var url_simpan = host + "admin/simpan";
var url_update = host + "admin/ajax_update";
var url_update_super_admin = "admin/update_super_admin";

var page_name;
var url_modul;
var set_admin;

$(document).ready(function() {
    data_page   = $(".data-page, .page-data").data();
    page_name   = data_page.page_name;
    url_modul   = data_page.url_modul;
    modul       = data_page.modul;
    if(modul == "company"){
        set_admin = new SlimSelect({
          select: '#super_admin'
        })
        url_list = url_list_company+url_modul+"/"+modul;
    } else {
        url_list = url_list+url_modul+"/"+modul;
    }

    //datatables
    table = $('#table').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": url_list,
            "type": "POST",
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [
        {
            "targets": [ 0 ], //last column
            "orderable": false, //set not orderable
        },
        ],
    });
});
function tambah()
{
    save_method = 'add';
    $("#form input, #form textarea").attr("disabled",false);
    $('#form')[0].reset(); // reset form on modals
    $('.has-error').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add New User'); // Set Title to Bootstrap modal title
    $('[name="crud"]').val("insert");
    $('#btnDelete').hide();
    $('.v_voucher').show();
    list_store();
    reset_button_action();
}
function edit(id,page)
{
    $('#list_company').show();
    $('.super_admin').hide();
    save_method = 'update';
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('#btnDelete, .v_voucher').hide();
    reset_button_action();
    //---------------------------------------------
    if(modul == "company"){
       save_method = "view";
       modal_title = "View Detail";
       $("#btnSave").hide(); 
       $("#form input, #form textarea").attr("disabled",true);
       $('.modal-body').css('height', 'auto');
    } else {
        $("#form input, #form textarea").attr("disabled",false);
        modal_title = 'Edit Data ' + page_name;
    }
    data_post = {
        modul : modul,
    }
    $.ajax({
        url : url_edit + id,
        type: "GET",
        data: data_post,
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);
            }

            v = data.data;

            $('label').addClass('active');
            $('.validate').addClass('valid');
            $('[name="crud"]').val("update");
            $('[name="id_user"]').val(v.id_user);
            $('[name="nama"]').val(v.nama);
            $('[name="first_name"]').val(v.first_name);
            $('[name="last_name"]').val(v.last_name);
            $('[name="email"]').val(v.email);
            $('[name="phone"]').val(v.phone);
            $('[name="id_hak_akses"]').val(v.id_hak_akses);
            list_store(data.list_store,page);
            if(modul == "company"){
                $("[name=Name]").val(v.nama);
                $("[name=Address]").val(v.address);
                $("[name=City]").val(v.city);
                $("[name=Province]").val(v.province);
                $("[name=Country]").val(v.country);
                $("[name=Fax]").val(v.fax);
                $("[name=PostalCode]").val(v.postal);
            }
            $("[type=checkbox]").prop('checked',true);
            $('#modal').modal("show");
            if(page == "view"){
                action_print_button();
                $('#form input, #form select').attr('disabled', true);
                set_button_action(data);
                modal_title = page_name+' Detail';
            }
            $('.disabled').attr('disabled',true);
            $('.modal-title').text(modal_title);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function super_admin(id){
    save_method = 'update_super_admin';
    $('#list_company').hide();
    $('.super_admin').show();
    $('#form')[0].reset();
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('.modal-body').css('height', '250px');
    if(modul == "company"){
       modal_title = "Set Super Admin";
       $("#form input, #form textarea").attr("disabled",false);
       $('#btnDelete').show();
       $('#btnSave').show();
    }
    $.ajax({
        url : url_edit + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);
            }

            v = data.data;
            $('label').addClass('active');
            $('.validate').addClass('valid');
            $('[name="crud"]').val("update");
            $('[name="id_user"]').val(v.id_user);
            if(v.ParentID){
                set_admin.set(v.ParentID);
            }else{
                set_admin.set('none');
            
            }
            $('#btnDelete').attr('onClick', "delete_super_admin('"+v.id_user+"')");
            $('#modal').modal("show");
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
 
function save()
{
    proses_save_button()
    
    var url;
    if(save_method == 'add') {
        url = url_simpan;
    } else if(save_method == "update"){
        url = url_update;
    }else if(save_method = 'update_super_admin'){
        url = url_update_super_admin;
    }
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {
            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal').modal("hide");
                reload_table();
                swal('','Success','success');
            }
            else
            {   
              $('.form-group').removeClass('has-error'); // clear error class
              $('.help-block').empty();
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error');
                    
                    if(data.inputerror[i] == "super_admin"){
                        $('.super_admin').addClass('has-error');
                        $('#has-sales-error').text(data.error_string[i]);
                    }else if(data.inputerror[i] == "password"){
                        $('.vPassword').addClass('has-error');
                    }else{
                        $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
                    }
                }
                if(data.message){
                    swal('',data.message,'warning');
                }
            }
            success_save_button();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            success_save_button();
            console.log(jqXHR.responseText);
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
                            //if success reload ajax table
                            if(data.status){
                                reload_table();
                                swal('','Success','success');
                                $('#modal').modal('hide');
                            }else{
                                swal('',data.message,'warning');
                            }
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
function active(id){
    $.ajax({
        url : url_hapus+id+"/active",
        type: "POST",
        dataType: "JSON",
        success: function(data){
            reload_table();
            swal('','Success','success');
            $('#modal').modal('hide');
        },
        error: function (jqXHR, textStatus, errorThrown){
            swal('Error undeleting data');
        }
    });
}
function list_store(list_store,page)
{
    $(".table-store tbody tr").remove();
    data_post = {
        select : "active",
    }
    $.ajax({
        url : host +"api/branch",
        type: "POST",
        data : data_post,
        dataType: "JSON",
        success: function(data){
            disabled = '';
            if(page == "view"){
                disabled = ' disabled ';
            }
            $.each(data.list_data,function(i,v){
                item = '<tr>\
                    <td>\
                      <div class="checkbox-custom checkbox-primary">\
                        <input class="icheckbox-primary branchid'+v.branchid+'" name="branch[]" id="branchid'+v.branchid+'" type="checkbox" value="'+v.branchid+'" '+disabled+'>\
                        <label for="branchid'+v.branchid+'">'+v.name+'</label>\
                      </div>\
                    </td>\
                    <td width="100px" style="text-align:center;">\
                      <label>as</label>\
                    </td>\
                    <td>\
                        <select name="hakakses[]" class="form-control hakakses'+v.branchid+'" '+disabled+'>\
                        <option value="supervisor">Supervisor</option>\
                        <option value="cashier">Cashier</option>\
                        </select>\
                    </td>\
                  </tr>';
                $(".table-store tbody").append(item);
            });
            if(list_store){
                $.each(list_store,function(i,v){
                    id = ".branchid"+v.branchid;
                    hakakses = ".hakakses"+v.branchid;
                    $(id).prop('checked', true);
                    $(hakakses).val(v.hakakses);
                    $("hakakses")
                });

            }
        },
        error: function (jqXHR, textStatus, errorThrown){
        }
    });
}
function generate_token(id,status)
{
    $.ajax({
        url : host+"admin/generate_token/" + id+"/"+status,
        type: "GET",
        dataType: "JSON",
        success: function(data){
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            if(data.status){
                reload_table();
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            console.log("error generate token");
        }
    });
}
function unlink(id){
    $.ajax({
        url : host+"admin/unlink/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            if(data.status){
                reload_table();
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            console.log("error generate token");
        }
    });
}

function delete_super_admin(id){
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
                        url : host+"admin/delete_super_admin/" + id,
                        type: "GET",
                        dataType: "JSON",
                        success: function(data){
                            if(data.hakakses == "super_admin"){
                                console.log(data);
                            }
                            if(data.status){
                                $('#modal').modal("hide");
                                reload_table();
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            console.log("error generate token");
                        }
                    });
                    swal("Deleted!", "Your data has been deleted.", "success");   } 
                 else {
                     swal("Canceled", "Your data is safe :)", "error");   } 
    });
}

function voucher_use(id){
    reset_button_action();
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty();
    $('#modal-voucher-use').modal('show');
    $('#modal-voucher-use .modal-title').text(language_app.lb_voucher_use);
    $('#form-voucher-use [name=ID]').val(id);
}

function voucher_save(){
    proses_save_button();
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty();
    url = host="save-voucher-additional";
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form-voucher-use').serialize(),
        dataType: "JSON",
        success: function(data)
        {
            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal-voucher-use').modal("hide");
                reload_table();
                swal('',data.message,'success');
            }
            else
            {   
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error');
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]);
                }
                if(data.message){
                    swal('',data.message,'warning');
                }
            }
            success_save_button();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            success_save_button();
            console.log(jqXHR.responseText);
        }
    });
}
