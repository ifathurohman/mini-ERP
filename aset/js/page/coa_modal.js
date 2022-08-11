var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host = window.location.origin+'/';
var url = window.location.href;
var page_name;
var modul;
$(document).ready(function() {
    page_data   = $(".page-data").data();
    page_name   = page_data.page_name; 
    modul       = page_data.modul;
});

function coa_modal2(classnya,page)
{
    position= "all";
    level 	= "all";
    without = ''; 
    type    = '';
    $('#modal-coa').modal('show'); // show bootstrap modal
    $('#modal-coa .modal-title').text('COA'); // Set Title to Bootstrap modal title
    if(classnya){
        tag_data = $(classnya).data();
        select 	 = tag_data.select;
        without  = tag_data.without;
        if(tag_data.level){
            level = tag_data.level;
        }
    }

    if(page == "coa_setting"){
    	select     = "active";
        without    = "active"; 
    }

    if(without == "active"){
        $('.btn-without').show();
        $('.btn-without').data('classnya', classnya);
    }else{
        $('.btn-without').hide();
    }
    data_post = {
        page 	: page,
        select 	: select,
        level 	: level,
    }
    url = host+"api/coa_select";
    tbl = $('.table-coa').DataTable();
    tbl.clear().draw();
     $.ajax({
        url : url,
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data){
            if(data.length>0){
                $.each(data, function(i, v) {
                    code        = v.Code;
                    coaid 		= v.ID;
                    name 		= v.Name;
                    level 		= v.Level;
                    parentid 	= v.ParentID;
                    parentName 	= v.parentName;

                    tag_data    = ' data-classnya="'+classnya+'" ';
                    tag_data    +=' data-id="'+coaid+'" ';
                    tag_data    +=' data-code="'+code+'" ';
                    tag_data    +=' data-name="'+name+'" ';
                    tag_data    +=' data-level="'+level+'" ';

                    item  = '<tr>';
                    item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_coa(this)">'+code+'</a></td>';
                    item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_coa(this)">'+name+'</a></td>';
                    item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_coa(this)">'+level+'</a></td>';
                    item += '<td><a href="javascript:void(0)"  '+tag_data+' onclick="chose_coa(this)">'+parentName+'</a></td>';
                    item += '</tr>';
                    tbl.row.add( $(item)[0] ).draw();
                });
            }else{

            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
           console.log(jqXHR.responseText);
        }
    });
}

function coa_modal(classnya,page){
    position= "all";
    level   = "all";
    without = ''; 
    type    = '';
    $('#modal-coa').modal('show'); // show bootstrap modal
    $('#modal-coa .modal-title').text('COA'); // Set Title to Bootstrap modal title
    if(classnya){
        tag_data = $(classnya).data();
        select   = tag_data.select;
        without  = tag_data.without;
        if(tag_data.level){
            level = tag_data.level;
        }
    }

    if(page == "coa_setting"){
        select     = "active";
        without    = "active"; 
    }

    if(without == "active"){
        $('.btn-without').show();
        $('.btn-without').data('classnya', classnya);
    }else{
        $('.btn-without').hide();
    }
    data_post = {
        classnya        : classnya,
        version         : "serverSide",
        page            : page,
        select          : select,
        level           : level,
    }
    url = host+"api/coa_select";
    tbl = $('.table-coa').DataTable({
        "destroy"   : true,
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "searching": true, //Feature Search false
        "order": [], //Initial no order.
         "language": {                
            "infoFiltered": ""
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url"   : url,
            "type"  : "POST",
            "data"  : data_post,
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
            },
            dataSrc : function (json) {
                return json.data;
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [{
            "targets": [], //last column
            "orderable": false, //set not orderable
        },],
    });
}

function chose_coa(v)
{
    data_page   = $(".data-page, .page-data").data();
    modul       = data_page.modul;

	v = $(v).data();
    classnya        = v.classnya;
    code            = v.code;
    name 			= v.name;
    level 			= v.level;
    id 				= v.id;

    if(modul == "coa_setting"){
    	$(classnya).val(id+"||"+code);
    	$(classnya).next().val(code+"-"+name);
    }
    else if(modul == "kas_bank" || modul == "jurnal_manual"){
        $(classnya+" [name='coacode[]']").val(code);
        $(classnya+" [name='coaname[]']").val(name);
        $(classnya+" [name='coaid[]']").val(id);
        SumTotal();
    }

    $('#modal-coa').modal('hide');
}

function without_coa(v){
    v = $(v).data();
    classnya    = v.classnya;
    if(modul == "coa_setting"){
        $(classnya).val('');
        $(classnya).next().val('');
    }

    $('#modal-coa').modal('hide');
}