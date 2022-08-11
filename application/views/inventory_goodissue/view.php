<!DOCTYPE html>
<html>
<head>
    <title><?= $title; ?></title>
    <?php
    	$total = 0;
      	if($cetak): 
       		$this->load->view("report/report_css");
      	endif;
    ?>
    <?php
    // echo "<pre>";
    // echo print_r($list);
    // echo "</pre>";
    ?>
    <style type="text/css">
      .vPeriode{display: none}
      .td-width-150{
        width: 150px;
      }
      .td-width-10{
        width: 10px;
      }
      .w50{
        width: 50% !important;
      }
      .w33{
        width: 33.33333333333333% !important;
      }
      .title-header{
        font-family: 'rockwell';
        font-size: 10pt !important;
        color: #000 !important;
        font-weight: bold !important;
        text-transform: uppercase !important;
      }
      .mb{
        margin-bottom: 0px !important;
      }
      .text-right{
        text-align: right;
      }
      .content-hide{
      	display: none;
      }
    </style>
</head>
<body>
	<div class="page-receipt">
	    <div class="header">
	    	<?php $this->load->view("report/header"); ?>
	    </div>
	    <div class="content">
	    	<div style="padding: 10px"><center><strong><?= $title2 ?></strong></center></div>
	    	<table class="table" data-plugin="dataTable" cellspacing="0">
	          	<tr>
	            	<td class="td-width-150"><?= $this->lang->line('lb_transaction_no') ?></td><td class="td-width-10">:</td><td><?= $list->CorrectionNo ?></td>
	          	</tr>
	          	<tr>
	            	<td><?= $this->lang->line('lb_date') ?></td><td>:</td><td><?= $list->Date ?></td>
	          	</tr>
	          	<tr>
	            	<td><?= $this->lang->line('lb_sales_name') ?></td><td>:</td><td><?= $list->salesName ?></td>
	          	</tr>
	          	<tr>
	            	<td><?= $this->lang->line('lb_store') ?></td><td>:</td><td><?= $list->branchName ?></td>
	          	</tr>
	        </table>
	        <table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
	          	<thead>
		            <tr>
		              	<th><?= $this->lang->line('lb_no') ?></th>
		              	<th><?= $this->lang->line('lb_product_code') ?></th>
		              	<th><?= $this->lang->line('lb_product_name') ?></th>
		              	<th><?= $this->lang->line('lb_qty2') ?></th>
		              	<th><?= $this->lang->line('lb_unit') ?></th>
		              	<th class="content-hide">Conv</th>
		              	<th><?= $this->lang->line('price') ?></th>
		              	<th><?= $this->lang->line('lb_sub_total') ?></th>
		              	<th><?= $this->lang->line('lb_remark') ?></th>
		              	<?php if(!$cetak): echo '<th></th>'; endif; ?>
		            </tr>
	          	</thead>
	          	<tbody>
          			<?php
          			foreach ($detail as $k => $v) {
          				$btn_serial = '';
        				if($v->product_type == 2):
        					$btn_serial = '<a href="javascript:;" onclick="view_serial_number('."'inventory_goodreceipt','".$list->CorrectionNo."','".$v->ID."'".')" class="btn-serial-v" aria-hidden="true" style="cursor:pointer;padding:5px;">'.$this->lang->line('btn_view_serial').'</a>';
        				endif;

        				$total_qty 	= (float) $v->total_qty;
        				$subtotal 	= $total_qty * (float) $v->Price;
        				$total 		+= $subtotal;

          				$no = $k + 1;
          				$item  = '<tr>';
		                $item .= '<td>'.$no.'</td>';
		                $item .= '<td>'.$v->product_code.'</td>';
		                $item .= '<td>'.$v->product_name.'</td>';
		                $item .= '<td>'.$this->main->qty($v->Qty).'</td>';
		                $item .= '<td>'.$v->unit_name.'</td>';
		                $item .= '<td class="content-hide">'.$v->Conversion.'</td>';
		                $item .= '<td>'.$this->main->currency($v->Price).'</td>';
		                $item .= '<td>'.$this->main->currency($subtotal).'</td>';
		                $item .= '<td>'.$v->Remark.'</td>';
		                if(!$cetak):
		                  $item .= '<td>'.$btn_serial.'</td>';
		                endif;
		                $item .= '</tr>';
		                echo $item;
          			}
          			?>
	          	</tbody>
	        </table>
	        <table width="100%" class="mt-20">
	          	<tr>
	            	<td class="w50">
	              		<div class="d-ID content-hide"><?= $list->CorrectionNo ?></div>
	              		<div class="div-remark">
	                		<?= $this->lang->line('lb_remark') ?> : <span class="d-remark"><?= $list->Remark ?></span>
	              		</div>
	            	</td>
	            	<td class="w50">
	              		<table>               
	                  		<tr><td><?= $this->lang->line('lb_total') ?> </td><td>:</td><td class="text-right"><?= $this->main->currency($total) ?></td></tr>
	              		</table>
	           		 </td>
	          	</tr>
	          	<tr>
	          		<td class="w50"><div class="div-attach"></div></td>
	          	</tr>
	        </table>
	    </div>
	    <div class="page_footer">
	    	<?php $this->load->view("report/footer"); ?>
	    </div>
	</div>
</body>
</html>
<script type="text/javascript">
  arrData = <?= json_encode($data_action) ?>;
  // set_button_action(arrData)
</script>