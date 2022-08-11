<table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
 	<thead>
	 	<?php if($group == "all"): ?>
	 	<tr>
	 		<th><?= $this->lang->line('lb_no') ?></th>
	 		<th><?= $this->lang->line('lb_transaction_no') ?></th>
	 		<th><?= $this->lang->line('lb_date') ?></th>
	 		<th><?= $this->lang->line('lb_store') ?></th>
	 		<th><?= $this->lang->line('lb_product_code') ?></th>
	 		<th><?= $this->lang->line('lb_product_name') ?></th>
	 		<th><?= $this->lang->line('lb_qty') ?></th>
	 		<th><?= $this->lang->line('lb_qty_real1') ?></th>
	 	</tr>
	 	<?php elseif($group == "transaction"): ?>
	 		<tr>
	 			<th><?= $this->lang->line('lb_no') ?></th>
		 		<th><?= $this->lang->line('lb_transaction_no') ?></th>
		 		<th><?= $this->lang->line('lb_date') ?></th>
		 		<th><?= $this->lang->line('lb_store') ?></th>
		 		<th><?= $this->lang->line('lb_qty_total') ?></th>
		 		<th><?= $this->lang->line('lb_qty_total_real') ?></th>
	 		</tr>
	 	<?php elseif($group == "store"): ?>
	 		<tr>
	 			<th><?= $this->lang->line('lb_no') ?></th>
	 			<th><?= $this->lang->line('lb_store') ?></th>
	 			<th><?= $this->lang->line('lb_total_transaction') ?></th>
	 			<th><?= $this->lang->line('lb_qty_total') ?></th>
		 		<th><?= $this->lang->line('lb_qty_total_real') ?></th>
	 		</tr>
	 	<?php endif; ?>
	</thead>
	<tbody>
		<?php
		$totalQty 	= 0;
		$totalQty2 	= 0;
		$totalTransaction = 0;
		if($this->input->get("cetak") == "pdf" || $this->input->get("cetak") == "print"):
			if($group == "all"):
				$no = 1;
				foreach ($list as $a) {
					$tr  = '<tr>';
					$tr .= '<td>'.$no++.'</td>';
					$tr .= '<td>'.$a->CorrectionNo.'</td>';
					$tr .= '<td>'.$a->Date.'</td>';
					$tr .= '<td>'.$a->branchName.'</td>';
					$tr .= '<td>'.$a->product_code.'</td>';
					$tr .= '<td>'.$a->product_name.'</td>';
					$tr .= '<td>'.$this->main->qty($a->Qty).'</td>';
					$tr .= '<td>'.$this->main->qty($a->CorrectionQty).'</td>';
					$tr .= '</tr>';	

					echo $tr;

					$totalQty += $a->Qty;
					$totalQty2 += $a->CorrectionQty;
				}
			elseif($group == "transaction"):
				$no = 1;
				foreach ($list as $a) {
					$tr  = '<tr>';
					$tr .= '<td>'.$no++.'</td>';
					$tr .= '<td>'.$a->CorrectionNo.'</td>';
					$tr .= '<td>'.$a->Date.'</td>';
					$tr .= '<td>'.$a->branchName.'</td>';
					$tr .= '<td>'.$this->main->qty($a->Qty).'</td>';
					$tr .= '<td>'.$this->main->qty($a->CorrectionQty).'</td>';
					$tr .= '</tr>';	

					echo $tr;

					$totalQty += $a->Qty;
					$totalQty2 += $a->CorrectionQty;
				}
			elseif($group == "store"):
				$no = 1;
				foreach ($list as $a) {
					$tr  = '<tr>';
					$tr .= '<td>'.$no++.'</td>';
					$tr .= '<td>'.$a->branchName.'</td>';
					$tr .= '<td>'.$a->totalTransaction.'</td>';
					$tr .= '<td>'.$this->main->qty($a->Qty).'</td>';
					$tr .= '<td>'.$this->main->qty($a->CorrectionQty).'</td>';
					$tr .= '</tr>';	

					echo $tr;

					$totalQty += $a->Qty;
					$totalQty2 += $a->CorrectionQty;
					$totalTransaction += $a->totalTransaction;
				}
			endif;
		endif;
		?>
	</tbody>
	<tfoot>
		<?php if($group == "all"): ?>
			<tr>
				<th colspan="6"><?= $this->lang->line('lb_total') ?></th>
				<th class="totalQty"><?= $this->main->qty($totalQty) ?></th>
				<th class="totalQty2"><?= $this->main->qty($totalQty2) ?></th>
			</tr>
		<?php elseif($group == "transaction"): ?>
			<tr>
				<th colspan="4"><?= $this->lang->line('lb_total') ?></th>
				<th class="totalQty"><?= $this->main->qty($totalQty) ?></th>
				<th class="totalQty2"><?= $this->main->qty($totalQty2) ?></th>
			</tr>
		<?php elseif($group == "store"): ?>
			<tr>
				<th colspan="2"><?= $this->lang->line('lb_total') ?></th>
				<th class="totalTransaction"><?= $totalTransaction ?></th>
				<th class="totalQty"><?= $this->main->qty($totalQty) ?></th>
				<th class="totalQty2"><?= $this->main->qty($totalQty2) ?></th>
			</tr>
		<?php endif; ?>
	</tfoot>
</table>