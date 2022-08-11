<?php
	$list = $this->report->select_stock_receipt();
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
		<th><?= $this->lang->line('lb_qty') ?></th>
		<th><?= $this->lang->line('lb_unit') ?></th>
		<th><?= $this->lang->line('lb_conversion') ?></th>
		<th><?= $this->lang->line('price') ?></th>
		<th><?= $this->lang->line('lb_sub_total') ?></th>
	</tr>
	<?php elseif($group == "transaction"): ?>
	<tr>
		<th><?= $this->lang->line('lb_no') ?></th>
		<th><?= $this->lang->line('lb_transaction_no') ?></th>
 		<th><?= $this->lang->line('lb_date') ?></th>
 		<th><?= $this->lang->line('lb_store') ?></th>
 		<th><?= $this->lang->line('lb_qty_total') ?></th>
 		<th><?= $this->lang->line('lb_sub_total') ?></th>
	</tr>
	<?php elseif($group == "store"): ?>
	<tr>
		<th><?= $this->lang->line('lb_no') ?></th>
		<th><?= $this->lang->line('lb_store') ?></th>
		<th><?= $this->lang->line('lb_total_transaction') ?></th>
		<th><?= $this->lang->line('lb_qty_total') ?></th>
 		<th><?= $this->lang->line('lb_sub_total') ?></th>
	</tr>
	<?php endif;?>
</thead>
<tbody>
	<?php
	$totalQty 	= 0;
	$totalPrice = 0;
	$totalSubTotal 		= 0;
	$totalTransaction 	= 0;

	$no = 0;
	if($group == "all"):
		foreach ($list as $a) {
			
			$total_qty 	= (float) $a->total_qty;
			$subtotal 	= $total_qty * (float) $a->Price;

			$no += 1;
			$tr = '<tr>';
			$tr .= '<td>'.$no.'</td>';	
			$tr .= '<td>'.$a->CorrectionNo.'</td>';
			$tr .= '<td>'.$a->Date.'</td>';
			$tr .= '<td>'.$a->branchName.'</td>';
			$tr .= '<td>'.$a->product_code.'</td>';
			$tr .= '<td>'.$a->product_name.'</td>';
			$tr .= '<td>'.$this->main->qty($a->Qty).'</td>';
			$tr .= '<td>'.$a->unit_name.'</td>';
			$tr .= '<td>'.(float) $a->Conversion.'</td>';
			$tr .= '<td>'.$this->main->currency($a->Price).'</td>';
			$tr .= '<td>'.$this->main->currency($subtotal).'</td>';
			$tr .= '</tr>';	

			echo $tr;

			$totalQty 		+= $a->Qty;
			$totalPrice 	+= $a->Price;
			$totalSubTotal 	+= $subtotal;
		}
	elseif($group == "transaction"):
		foreach ($list as $a) {

			$no += 1;
			$tr = '<tr>';
			$tr .= '<td>'.$no.'</td>';	
			$tr .= '<td>'.$a->CorrectionNo.'</td>';
			$tr .= '<td>'.$a->Date.'</td>';
			$tr .= '<td>'.$a->branchName.'</td>';
			$tr .= '<td>'.$this->main->qty($a->Qty).'</td>';
			$tr .= '<td>'.$this->main->currency($a->Price).'</td>';
			$tr .= '</tr>';	

			echo $tr;

			$totalQty 		+= $a->Qty;
			$totalPrice 	+= $a->Price;
		}
	elseif($group == "store"):
		foreach ($list as $a) {

			$no += 1;
			$tr = '<tr>';
			$tr .= '<td>'.$no.'</td>';
			$tr .= '<td>'.$a->branchName.'</td>';
			$tr .= '<td>'.$a->totalTransaction.'</td>';
			$tr .= '<td>'.$this->main->qty($a->Qty).'</td>';
			$tr .= '<td>'.$this->main->currency($a->Price).'</td>';
			$tr .= '</tr>';	

			echo $tr;

			$totalQty 		+= $a->Qty;
			$totalPrice 	+= $a->Price;
			$totalTransaction += $a->totalTransaction;
		}
	endif;
	?>
</tbody>
<tfoot>
	<?php if($group == "all"): ?>
	<tr>
		<th colspan="6"><?= $this->lang->line('lb_total') ?></th>
		<th class="totalQty"><?= $this->main->qty($totalQty) ?></th>
		<th></th>
		<th></th>
		<th class="totalPrice"><?= $this->main->currency($totalPrice) ?></th>
		<th class="totalSubTotal"><?= $this->main->currency($totalSubTotal) ?></th>
	</tr>
	<?php elseif($group == "transaction"): ?>
	<tr>
		<th colspan="4"><?= $this->lang->line('lb_total') ?></th>
		<th class="totalQty"><?= $this->main->qty($totalQty) ?></th>
		<th class="totalPrice"><?= $this->main->currency($totalPrice) ?></th>
	</tr>
	<?php elseif($group == "store"): ?>
	<tr>
		<th colspan="2"><?= $this->lang->line('lb_total') ?></th>
		<th><?= $totalTransaction ?></th>
		<th class="totalQty"><?= $this->main->qty($totalQty) ?></th>
		<th class="totalPrice"><?= $this->main->currency($totalPrice) ?></th>
	</tr>
	<?php endif;?>
</tfoot>
</table>
