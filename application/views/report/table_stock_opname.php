<?php
	$list = $this->report->select_stock_opname();
	// echo '<pre>';
	// print_r($list);
	// echo '</pre>';
?>
<table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
 <thead>
	<?php if($group == "all"): ?>
	<tr>
		<th><?= $this->lang->line('lb_no') ?></th>
		<th><?= $this->lang->line('lb_transaction_no') ?></th>
 		<th><?= $this->lang->line('lb_date') ?></th>
 		<th><?= $this->lang->line('lb_store') ?></th>
		<th><?= $this->lang->line('lb_product_name') ?></th>
		<th><?= $this->lang->line('lb_product_code') ?></th>
		<th><?= $this->lang->line('price') ?></th>
		<th><?= $this->lang->line('lb_stock_opname_qty') ?></th>
		<th><?= $this->lang->line('lb_stock_qty') ?></th>
		<th><?= $this->lang->line('lb_correction_qty') ?></th>
		<th><?= $this->lang->line('lb_unit') ?></th>
		<th><?= $this->lang->line('lb_remark') ?></th>
	</tr>
	<?php elseif($group == "transaction"): ?>
	<tr>
		<th><?= $this->lang->line('lb_no') ?></th>
		<th><?= $this->lang->line('lb_transaction_no') ?></th>
 		<th><?= $this->lang->line('lb_date') ?></th>
 		<th><?= $this->lang->line('lb_store') ?></th>
 		<th><?= $this->lang->line('lb_stock_opname_qty_total') ?></th>
 		<th><?= $this->lang->line('lb_stock_qty_total') ?></th>
 		<th><?= $this->lang->line('lb_correction_qty_total') ?></th>
	</tr>
	<?php elseif($group == "store"): ?>
	<tr>
		<th><?= $this->lang->line('lb_no') ?></th>
		<th><?= $this->lang->line('lb_store') ?></th>
		<th><?= $this->lang->line('lb_total_transaction') ?></th>
 		<th><?= $this->lang->line('lb_stock_opname_qty_total') ?></th>
 		<th><?= $this->lang->line('lb_stock_qty_total') ?></th>
 		<th><?= $this->lang->line('lb_correction_qty_total') ?></th>
 	</tr>
	<?php endif;?>
</thead>
<tbody>
	<?php
	$totalPriceBefore 	= 0;
	$totalPrice 		= 0;
	$totalCorrectionQty = 0;
	$totalQty 			= 0;
	$totalCorrectionStock = 0;

	if($group == "all"):
		$no = 0;
		foreach ($list as $a) {
			$no += 1;
			$tr = '<tr>';
			$tr .= '<td>'.$no.'</td>';	
			$tr .= '<td>'.$a->CorrectionNo.'</td>';
			$tr .= '<td>'.$a->Date.'</td>';
			$tr .= '<td>'.$a->branchName.'</td>';
			$tr .= '<td>'.$a->product_code.'</td>';
			$tr .= '<td>'.$a->product_name.'</td>';
			$tr .= '<td>'.$this->main->currency($a->Price).'</td>';
			$tr .= '<td>'.$this->main->qty($a->CorrectionQty).'</td>';
			$tr .= '<td>'.$this->main->qty($a->Qty).'</td>';
			$tr .= '<td>'.$this->main->qty($a->correction_stock).'</td>';
			$tr .= '<td>'.$a->unit_name.'</td>';
			$tr .= '<td>'.$a->Remark.'</td>';
			$tr .= '</tr>';	

			echo $tr;

			$totalPriceBefore 		+= $a->PriceBefore;
			$totalPrice 			+= $a->Price;
			$totalCorrectionQty 	+= $a->CorrectionQty;
			$totalQty 				+= $a->Qty;
			$totalCorrectionStock 	+= $a->correction_stock;
		}
	elseif($group == "transaction"):
		$no = 0;
		foreach ($list as $a) {
			$no += 1;
			$tr = '<tr>';
			$tr .= '<td>'.$no.'</td>';	
			$tr .= '<td>'.$a->CorrectionNo.'</td>';
			$tr .= '<td>'.$a->Date.'</td>';
			$tr .= '<td>'.$a->branchName.'</td>';
			$tr .= '<td>'.$this->main->qty($a->CorrectionQty).'</td>';
			$tr .= '<td>'.$this->main->qty($a->Qty).'</td>';
			$tr .= '<td>'.$this->main->qty($a->correction_stock).'</td>';
			$tr .= '</tr>';	

			echo $tr;
		}
	elseif($group == "store"):
		$no = 0;
		foreach ($list as $a) {
			$no += 1;
			$tr = '<tr>';
			$tr .= '<td>'.$no.'</td>';	
			$tr .= '<td>'.$a->branchName.'</td>';
			$tr .= '<td>'.$a->totalTransaction.'</td>';
			$tr .= '<td>'.$this->main->qty($a->CorrectionQty).'</td>';
			$tr .= '<td>'.$this->main->qty($a->Qty).'</td>';
			$tr .= '<td>'.$this->main->qty($a->correction_stock).'</td>';
			$tr .= '</tr>';	

			echo $tr;
		}
	endif;

	if(count($list)<1 and $group == "all"):
		echo '<tr><td colspan="13">No data available in table</td></tr>';
	elseif(count($list)<1 and $group == "transaction"):
		echo '<tr><td colspan="7">No data available in table</td></tr>';
	elseif(count($list)<1 and $group == "store"):
		echo '<tr><td colspan="6">No data available in table</td></tr>';
	endif;
	?>
</tbody>
</table>