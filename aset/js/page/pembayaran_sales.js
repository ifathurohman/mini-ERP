var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host = window.location.origin+'/';
var url = window.location.href;
var page_login = host + "main/login";
var page_register = host + "main/register";
var save_method; //for save method string
var table;
var url_list = host + "pembayaran_sales/ajax_list/";
var url_edit = host + "pembayaran_sales/ajax_edit/";
var url_hapus = host + "pembayaran_sales/ajax_delete/";
var url_simpan = host + "pembayaran_sales/simpan";
var url_update = host + "pembayaran_sales/ajax_update";
var idrow = 0;
$(document).ready(function() {
    date();
    data_page   = $(".data-page, .page-data").data();
    title_page  = data_page.title;
    //datatables
    table = $('#table').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": url_list,
            "type": "POST"
        },
        //Set column definition initialisation properties.
        "columnDefs": [{
            "targets": [ 0 ], //last column
            "orderable": false, //set not orderable
        },],
    });
});

function modal_width() {
    if(mobile){
        $("#modal .modal-dialog").css("width","93%");
    } else {
        $("#modal .modal-dialog").css("width","65%");
    }
}
function tambah()
{
    save_method = 'add';
    modal_width();
    $(".btn-search-sell").show();
    $("#form input").attr("disabled",false);
    $(".readonly").attr("readonly",true);
    $('#btnSave').show();
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal('show'); // show bootstrap modal
    $('.modal-title').text(language_app.lb_add_new+' '+title_page); // Set Title to Bootstrap modal title
    $(".modal-footer").show();
    $('.table-add-sell tbody').children( 'tr' ).remove();
    $(".link_add_row").show();
    $(".btn-serial-v").hide();
    $("[name=grand_total]").val('0');
    $(".addbranchmodal").attr("onclick","branch_modal('payment_sales','.autocompletebranch')");
    empty_row();
    $(".table-add-sell input").show();

}
function view(id) {
    save_method = 'update';
    modal_width();
    $(".btn-search-sell").hide();
    $('#btnSave').attr('disabled',false);
    $('#btnSave').hide();
    $(".disabled").attr("disabled",true);
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal').modal('show'); // show bootstrap modal
    $('.modal-title').text(title_page+' '+language_app.btn_detail); // Set Title to Bootstrap modal title
    $(".modal-footer").show();
    $('.table-add-sell tbody').children( 'tr' ).remove();
    $(".addbranchmodal").attr("onclick","");

    $.ajax({
        url : host+"pembayaran_sales/sell/edit/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            if(data.status){
                $("[name=paymentno]").val(data.paymentno);
                $("[name=branchid]").val(data.branchid);
                $("[name=name]").val(data.name);
                $("[name=BranchName]").val(data.name);
                $("[name=date]").val(data.date);
                $("[name=pay_cash]").val(data.pay_cash);
                $("[name=pay_credit]").val(data.pay_credit);
                $("[name=pay_giro]").val(data.pay_giro);
                $("[name=add_cost]").val(data.add_cost);
                $("[name=grandtotal]").val(data.grandtotal);
                $("[name=total_ar]").val(data.total);
                $("[name=total_payment]").val(data.total);
                idrow = 0;
                $.each(data.list_data, function(i, v) {
                    if(v.total != v.payment){
                        add_sell_row(v);
                    }
                });
                if(data.list_data.length == 0){
                   empty_row();
                }
                $(".table-add-sell input").hide();
            }
            $("#form input").attr("disabled",true);

        },
        error: function (jqXHR, textStatus, errorThrown){
            alert('Error get data from ajax');
            console.log(jqXHR.responseText);
        }
    });
}
function  empty_row() {
    $('.table-add-sell tbody').children( 'tr' ).remove();
    item = ' <tr>\
        <td  colspan="5" style="text-align:center">'+language_app.lb_data_not_found+'</td>\
      </tr>';
    $(".table-add-sell tbody").append(item);
    $("[name=total_ar]").val("");

}
function remove_row() {
    $('.table-add-sell tbody').children( 'tr' ).remove();
    $("[name=total_ar]").val("");
}
function add_sell_row(v = "") {
    ar_total = 0;
    idrow   += 1;
    id          = v.id;
    sellno      = v.sellno;
    balanceid   = v.balanceid;
    payment     = v.payment;
    total       = v.total;
    sisa        = v.sisa;
    status      = v.status;
    jenis       = v.jenis;
    date        = v.date;
    vendorid    = v.vendorid;
    vendorname  = v.vendorname;
    type        = v.type; // type 1 = debit , 2 = credit
    if(vendorname == "" || vendorname == null){
        vendorname = "";
    }
    if(status == 1){
        status = "<hijau style='border-radius:0px;' >lunas</hijau>";
    } else {
        status = "<merah style='border-radius:0px;' >belum lunas</merah>";
    }
    if(jenis == "sell"){
        label = "<hijau  style='border-radius:0px;' class='pull-right'>sales</hijau>"; 
    } else {
        label = "<biru  style='border-radius:0px;' class='pull-right'>ar correction</biru>"; 
    }

    value = '<input type="hidden" name="sellno[]" value="'+sellno+'">';
    value +='<input type="hidden" name="id[]" value="'+id+'">';
    value +='<input type="hidden" name="balanceid[]" value="'+balanceid+'">';
    value +='<input type="hidden" name="vendorid[]" value="'+vendorid+'">';
    value +='<input type="hidden" name="sell_date[]" value="'+date+'">';
    value +='<input type="hidden" name="total[]" value="'+total+'" class="selltotal">';
    value +='<input type="hidden" name="payment[]" value="'+total+'">';
    value +='<input type="hidden" name="jenis[]" value="'+jenis+'">';
    value +='<input type="hidden" name="type[]" value="'+type+'">';

    item    = '<tr class="rid'+idrow+'">\
                <td><input class="cek'+idrow+'" type="checkbox" name="cekbox[]" onclick="cekrow('+idrow+',this)" value="'+id+'" data-class="cek'+idrow+'">'+value+'</td>\
                <td style="padding: 0px 5px !important;">'+sellno+label+'</td>\
                <td style="padding: 0px 5px !important;">'+date+'</td>\
                <td style="padding: 0px 5px !important;">'+vendorname+'</td>\
                <td style="padding: 0px 5px !important;" class="num">'+sisa+'</td>\
              </tr>';
    //         <td><input name="payment[]" type="number" onkeyup="keyup('+idrow+',this)" class="rtotal" data-class="rtotal" disabled="" placeholder="0"/></td>\
    $(".table-add-sell tbody").append(item);
}
function cekboxall(bebep)
{
    if($(bebep).is(":checked")){
        $(".table-add-sell [type=checkbox]").prop("checked",true);
    } else {
        $(".table-add-sell [type=checkbox]").prop("checked",false);
    }
    cekrow();
}
function cekrow(id = "",input=""){
    tot = 0;
    arr = $('.selltotal');
    for(var i=0;i<arr.length;i++){
        if(parseFloat(arr[i].value)){
            id = i + 1;
            if($(".rid"+id+" [type=checkbox]").is(":checked")){
                type = $(".rid"+id+" [name='type[]']").val();
                if(type == 1){
                    tot += parseFloat(arr[i].value);
                }else{
                    tot -= parseFloat(arr[i].value);
                }                    
            }
        }
    }
    $("[name=total_payment]").val(tot);
    // $("[name=total_ar]").val(tot.toFixed(2));
}
$(".gt").keyup(function(){
    grandtotal = 0;
    $(".gt").each(function(i,v){
        value = $(this).val().replace(".", "");
        if(value == ""){
            value = 0;
        }
        // $("#result").val(+(first+second).toFixed(2));
        grandtotal += parseFloat(value);
    });
    // $("[name=grandtotal]").val(grandtotal.toFixed(2));
    $("[name=grandtotal]").val(grandtotal);

});


function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}
 
function save()
{
    proses_save_button();
    
    $(".disabled").attr("disabled",false);
    var url;
    if(save_method == 'add') {
        url = url_simpan;
    } else {
        url = url_update;
    }
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            if(data.status){
                $('#modal').modal("hide");
                reload_table();
            } 
            else{
                $('.form-group, .input-group').removeClass('has-error'); // clear error class
                $('.help-block').empty();
                
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error'); 
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
                if(data.message){
                    swal('',data.message,'warning');
                }
            }
            success_save_button();
            $(".disabled").attr("disabled",true);

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            success_save_button();
            $(".disabled").attr("disabled",true);
        }
    });
}
function hapus(id)
{
    swal({   title: "Are you sure?",   
             text: "You will not be able to recover this data !",   
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
                        url : url_hapus+id,
                        type: "POST",
                        dataType: "JSON",
                        success: function(data)
                        {
                            //if success reload ajax table
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
function date(){
    container = $('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
    $(".date").datepicker({
        format: 'yyyy-mm-dd',
        container: container,
        todayHighlight: true,
        autoclose: true,
    });
}
$("[name=date]").on("change",function(){
    // remove_row();
    empty_row();
    // date_change(this);
    // empty_row();
});
function date_change(data) {
    if(save_method == "add"){
        date     = $('[name=date]').val();
        branchid = $("[name=branchid]").val();
        v = {
            branchid: branchid,
            date: date
        }
        if(branchid){
            sell_list(v);
        }
    }
}
function sell_list(v = ""){
    // console.log(v);
    remove_row();
    $.ajax({
        url : host+"pembayaran_sales/sell/add",
        data: v,
        type: "POST",
        dataType: "JSON",
        success: function(data){
            if(data.hakakses == "super_admin"){
                console.log(data);
            }
            if(data.status){
                var total_ar = 0;
                idrow = 0;
                $.each(data.list_data, function(i, v) {
                    add_sell_row(v);
                    if(v.type == 1){
                        total_ar  += eval(v.total);
                    }else{
                        total_ar  -= eval(v.total);
                    }
                });
                $("[name=total_ar]").val(total_ar);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
           alert("Error get data sales")
        }
    });
}