var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
var host = window.location.origin+'/pipesys_qa/';
var url = window.location.href;
// var id_row = 0;
$(document).ready(function() {

});
function branch_modal(page="",classnya = "")
{
    $('#modal-branch').modal('show'); // show bootstrap modal
    $('#modal-branch .modal-title').text('branch'); // Set Title to Bootstrap modal title
    tbl = $('.table-branch').DataTable();
    tg_data = $(classnya).data();
    select = '';
    if(tg_data.select){
        select = tg_data.select;
    }
    data_post = {
        select : select,
    }
    tbl.clear();
     $.ajax({
        url : host+"api/branch",
        type: "POST",
        data: data_post,
        dataType: "JSON",
        success: function(data){
            $.each(data.list_data, function(i, v) {
                branchid    = v.branchid;
                code        = v.code;
                name        = v.name;
                label_status= '';
                label_index = '';
                if(v.Active != 1){
                    label_status= ' <merah>Non Active</merah>';
                }
                if(v.Index == 1){
                    label_index = ' <span class="info-green">HO</span>';
                }
	    		item = '<tr>\
	    					<td>\
	    						<a href="javascript:void(0)" \
                                data-page="'+page+'" \
	    						data-classnya="'+classnya+'" \
                                data-branchid="'+branchid+'" \
                                data-code="'+code+'"\
                                data-name="'+name+'"\
	    						onclick="chose_branch(this)">'+code+'</a>\
	    					</td>\
	    					<td>\
                                <a href="javascript:void(0)" \
                                data-page="'+page+'" \
                                data-classnya="'+classnya+'" \
                                data-branchid="'+branchid+'" \
                                data-code="'+code+'"\
                                data-name="'+name+'"\
                                onclick="chose_branch(this)">'+name+label_status+label_index+'</a>\
                            </td>\
	    				</tr>';
	    		tbl.row.add( $(item)[0] ).draw();
          	});
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
           console.log("error get list product");
        }
    });
}
function chose_branch(v)
{
    data_page   = $(".data-page, .page-data").data();
    product_branch_reset = '';
    if(data_page.product_branch_reset){
        product_branch_reset = data_page.product_branch_reset;
    }

	v = $(v).data();
    page        = v.page;
    classnya    = v.classnya;
    branchid    = v.branchid;
	branch_name = v.name;
    branch_val  = branchid + "-" + branch_name;
    
    if(product_branch_reset == "active"){
        branchid2 = $('#BranchID').val();
        branchid2 = branchid2.split('-');
        if(branchid2[0] != branchid){
            reset_column_product();
        }
    }    

    $(classnya).val(branch_val);
    $(classnya+'-name').val(branch_name);
    $('#modal-branch').modal('hide');
    if(page == "payment_sales"){
        empty_row();
        $("[name=branchid]").val(branchid);
    }
    else if(page == "ar_correction"){
        $(classnya+" [name='BranchName[]']").val(branch_name);
        $(classnya+" [name='BranchID[]']").val(branchid);
    }

}
