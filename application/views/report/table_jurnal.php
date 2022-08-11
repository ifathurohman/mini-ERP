<table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th><?= $this->lang->line('lb_no') ?></th>
			<th><?= $this->lang->line('lb_date') ?></th>
			<th><?= $this->lang->line('lb_transaction_no') ?></th>
			<th><?= $this->lang->line('lb_transaction_name') ?></th>
			<th><?= $this->lang->line('lb_remark') ?></th>
			<th><?= $this->lang->line('lb_bank_acountno') ?></th>
			<th><?= $this->lang->line('lb_bank_acount') ?></th>
			<th><?= $this->lang->line('lb_debit') ?></th>
			<th><?= $this->lang->line('lb_credit') ?></th>
		</tr>
	</thead>
	<tbody>
	<?php 
		$totalDebit  = 0;
		$totalCredit = 0;
		if($this->input->get("cetak") == "pdf" || $this->input->get("cetak") == "print"):
			$no = 0;
			foreach ($list as $a) {
				$no += 1;

				$item  = '<tr>';
				$item .= '<td>'.$no.'</td>';
				$item .= '<td>'.$a->Date.'</td>';
				$item .= '<td>'.$a->KasBankNo.'</td>';
				$item .= '<td>'.$this->main->kasbank_type($a->type).'</td>';
				$item .= '<td>'.$a->Remarks.'</td>';
				$item .= '<td>'.$a->Code.'</td>';
				$item .= '<td>'.$a->Name.'</td>';
				$item .= '<td>'.$this->main->currency($a->Debit).'</td>';
				$item .= '<td>'.$this->main->currency($a->Credit).'</td>';

				$item .= '</tr>';

				$totalDebit 	+= $a->Debit;
				$totalCredit 	+= $a->Credit;

				echo $item;
			}
		endif;
	?>
	</tbody>
	<tfoot>
		<tr>
			<th colspan="7"><?= $this->lang->line('lb_total') ?></th>
			<th class="totalDebit"><?= $this->main->currency($totalDebit); ?></th>
			<th class="totalCredit"><?= $this->main->currency($totalCredit); ?></th>
		</tr>
	</tfoot>
</table>