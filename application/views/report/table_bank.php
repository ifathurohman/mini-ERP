<table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
 	<thead>
 		<tr>
 			<th><?= $this->lang->line('lb_no') ?></th>
 			<th><?= $this->lang->line('lb_date') ?></th>
 			<th><?= $this->lang->line('lb_total_amount') ?></th>
 			<th><?= $this->lang->line('lb_coa_code') ?></th>
 			<th><?= $this->lang->line('lb_coa_name') ?></th>
 			<th><?= $this->lang->line('lb_total_debit') ?></th>
 			<th><?= $this->lang->line('lb_total_credit') ?></th>
 			<th><?= $this->lang->line('lb_remark') ?></th>
 		</tr>
 	</thead>
 	<tbody>
 	<?php 
		if($this->input->get("cetak") == "pdf" || $this->input->get("cetak") == "print"):
			$no = 0;
			foreach ($list as $a) {
				$no += 1;

				$item  = '<tr>';
				$item .= '<td>'.$no.'</td>';
				$item .= '<td>'.$a->Date.'</td>';
				$item .= '<td>'.$a->KasBankNo.'</td>';
				$item .= '<td>'.$a->coaCode.'</td>';
				$item .= '<td>'.$a->coaName.'</td>';
				$item .= '<td>'.$this->main->currency($a->Debit).'</td>';
				$item .= '<td>'.$this->main->currency($a->Credit).'</td>';
				$item .= '<td>'.$a->Remark.'</td>';
				$item .= '</tr>';

				echo $item;
			}
		endif;
	?>	
 	</tbody>
</table>