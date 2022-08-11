<table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
 	<thead>
	 	<?php if($group == "all"): ?>
	 	<tr>
		    <th><?= $this->lang->line('lb_no') ?></th>
		    <th><?= $this->lang->line('lb_purchaseno') ?></th>
		    <th><?= $this->lang->line('lb_date') ?></th>
		    <th><?= $this->lang->line('lb_vendor_name') ?></th>
		    <th><?= $this->lang->line('lb_store') ?></th>
		    <th><?= $this->lang->line('lb_product_code') ?></th>
		    <th><?= $this->lang->line('lb_product_name') ?></th> 
		    <th><?= $this->lang->line('lb_qty') ?></th>
		    <th><?= $this->lang->line('lb_unit') ?></th>
		    <th><?= $this->lang->line('lb_conversion') ?></th>
		    <th><?= $this->lang->line('price') ?></th>
		    <th><?= $this->lang->line('lb_discount') ?> (%)</th>
		    <th><?= $this->lang->line('lb_discount') ?></th>
		    <th><?= $this->lang->line('lb_total_bruto') ?></th>
		    <th><?= $this->lang->line('lb_tax') ?> (%)</th>
		    <th><?= $this->lang->line('lb_tax') ?></th>
		    <th><?= $this->lang->line('lb_total_netto') ?></th>
		    <th><?= $this->lang->line('lb_sales_name') ?></th>
		    <th><?= $this->lang->line('lb_remark') ?></th>
	  	</tr>
	  	<?php elseif($group == "gr_purchase"): ?>
	  	<tr>
	  		<th><?= $this->lang->line('lb_no') ?></th>
	  		<th><?= $this->lang->line('lb_purchaseno') ?></th>
	  		<th><?= $this->lang->line('lb_date') ?></th>
	  		<th><?= $this->lang->line('lb_vendor_name') ?></th>
	  		<th><?= $this->lang->line('lb_store') ?></th>
	  		<th><?= $this->lang->line('lb_qty_total') ?></th>
		    <th><?= $this->lang->line('lb_sub_total') ?></th>
		    <th><?= $this->lang->line('lb_discount_total') ?></th>
		    <th><?= $this->lang->line('lb_tax') ?></th>
		    <th><?= $this->lang->line('lb_delivery_cost') ?></th>
		    <th><?= $this->lang->line('lb_total') ?></th>
	  		<th><?= $this->lang->line('lb_sales_name') ?></th>
	  		<th><?= $this->lang->line('lb_remark') ?></th>
	  	</tr>
	  	<?php elseif($group == "product_name"): ?>
	  	<tr>
	  		<th><?= $this->lang->line('lb_no') ?></th>
	  		<th><?= $this->lang->line('lb_product_code') ?></th>
	  		<th><?= $this->lang->line('lb_product_name') ?></th>
	  		<th><?= $this->lang->line('lb_qty') ?></th>
	  		<th><?= $this->lang->line('lb_unit') ?></th>
	  		<th><?= $this->lang->line('lb_conversion') ?></th>
	  	</tr>
	  	<?php elseif($group == "vendor"): ?>
	  	<tr>
	  		<th><?= $this->lang->line('lb_no') ?></th>
	  		<th><?= $this->lang->line('lb_vendor_name') ?></th>
	  		<th><?= $this->lang->line('lb_purchase_total') ?></th>
	  		<th><?= $this->lang->line('lb_qty_total') ?></th>
	  		<th><?= $this->lang->line('lb_price_total') ?></th>
	  		<th><?= $this->lang->line('lb_total_netto') ?></th>
	  	</tr>
	  	<?php elseif($group == "store"): ?>
  		<tr>
	  		<th><?= $this->lang->line('lb_no') ?></th>
	  		<th><?= $this->lang->line('lb_store') ?></th>
	  		<th><?= $this->lang->line('lb_purchase_total') ?></th>
	  		<th><?= $this->lang->line('lb_qty_total') ?></th>
	  		<th><?= $this->lang->line('lb_price_total') ?></th>
	  		<th><?= $this->lang->line('lb_total_netto') ?></th>
	  	</tr>
		<?php endif; ?>
	</thead>
	<tbody>
		<?php
		$total1  = 0;
		$total2  = 0;
		$total3  = 0;
		$total4  = 0;
		$total5  = 0;
		$total6  = 0;
		$total7  = 0;
		$total8  = 0;
		$total9  = 0;
		$total10 = 0;
		$total11 = 0;
			if($this->input->get("cetak") == "pdf" || $this->input->get("cetak") == "print"):
				if($group == "all"):
					foreach ($list as $a) {
						$sub_total 	= ($a->price * $a->qty) - $a->discount_value;
						$tax 		= $a->tax;
						$total 		= $sub_total;
						$ppn 		= 0;
						if($tax == 1):
							$tax 	= 10;
							$ppn 	= $sub_total * (10/100);
							$total 	= $sub_total + $ppn;
						endif;

						$tr = "<tr>";
				        $tr .= "<td>". $i++."</td>";
				        $tr .= "<td>". $a->PurchaseNo."</td>";
				        $tr .= "<td>". $a->Date."</td>";
				        $tr .= "<td>". $a->vendor_name."</td>";
				        $tr .= "<td>". $a->branchName."</td>";
				        $tr .= "<td>". $a->product_code."</td>";
				        $tr .= "<td>". $a->product_name."</td>";
				        $tr .= "<td>". $this->main->qty($a->qty)."</td>";
				        $tr .= "<td>". $a->unit_name."</td>";
				        $tr .= "<td>". (float) $a->conversion."</td>";
				        $tr .= "<td>". $this->main->currency($a->price)."</td>";
				        $tr .= "<td>". (float) $a->discount."</td>";
				        $tr .= "<td>". $this->main->currency($a->discount_value)."</td>";
				        $tr .= "<td>". $this->main->currency($sub_total)."</td>";
				        $tr .= "<td>". $tax." %"."</td>";
				        $tr .= "<td>". $this->main->currency($ppn)."</td>";
				        $tr .= "<td>". $this->main->currency($total)."</td>";
				        $tr .= "<td>". $a->sales_name."</td>";
				        $tr .= "<td>". $a->remark."</td>";

				        $tr .= "</tr>";

				        $total8  += $a->qty;
		                $total9  += '';
		                $total10 += '';
		                $total1  += $a->price;
		                $total2  += '';
		                $total3  += $a->discount_value;
		                $total4  += $sub_total;
		                $total5  += '';
		                $total6  += $ppn;
		                $total7  += $total;

				        echo $tr;
					}
				elseif($group == "gr_purchase"):
					foreach ($list as $a) {
						$tr  = '<tr>';
						$tr .= '<td>'.$i++.'</td>';
						$tr .= '<td>'.$a->PurchaseNo.'</td>';
						$tr .= '<td>'.$a->Date.'</td>';
						$tr .= '<td>'.$a->vendor_name.'</td>';
						$tr .= '<td>'.$a->branchName.'</td>';
						$tr .= '<td>'.$this->main->qty($a->qty).'</td>';
						$tr .= '<td>'.$this->main->currency($a->subtotal).'</td>';
						$tr .= '<td>'.$this->main->currency($a->discount).'</td>';
						$tr .= '<td>'.$this->main->currency($a->TotalPPN).'</td>';
						$tr .= '<td>'.$this->main->currency($a->DeliveryCost).'</td>';
						$tr .= '<td>'.$this->main->currency($a->payment).'</td>';
						$tr .= '<td>'.$a->sales_name.'</td>';
						$tr .= '<td>'.$a->remark.'</td>';
						$tr .= '</tr>';

						$total1  += $a->subtotal;
						$total3  += $a->discount;
						$total4  += $a->TotalPPN;
						$total6  += $a->DeliveryCost;
						$total8  += $a->qty;
						$total7  += $a->payment;

						echo $tr;
					}
				elseif($group == "product_name"):
					foreach ($list as $a) {
						$tr  = '<tr>';
						$tr .= '<td>'.$i++.'</td>';
						$tr .= '<td>'.$a->product_code.'</td>';
						$tr .= '<td>'.$a->product_name.'</td>';
						$tr .= '<td>'.$this->main->qty($a->qty).'</td>';
						$tr .= '<td>'.$a->unit_name.'</td>';
						$tr .= '<td>'.(float) $a->conversion.'</td>';
						$tr .= '</tr>';

						$total8 += $a->qty;

						echo $tr;
					}
				elseif($group == "vendor"):
					foreach ($list as $a) {
						$tr  = '<tr>';
						$tr .= '<td>'.$i++.'</td>';
						$tr .= '<td>'.$a->vendor_name.'</td>';
						$tr .= '<td>'.$a->totalpurchase.'</td>';
						$tr .= '<td>'.$this->main->qty($a->qty).'</td>';
						$tr .= '<td>'.$this->main->currency($a->price).'</td>';
						$tr .= '<td>'.$this->main->currency($a->total).'</td>';
						$tr .= '</tr>';

						$total11 += $a->totalpurchase;
		                $total8 += $a->qty;
		                $total3 += $a->price;
		                $total4 += $a->total;

						echo $tr;
					}
				elseif($group == "store"):
					foreach ($list as $a) {
						$tr  = '<tr>';
						$tr .= '<td>'.$i++.'</td>';
						$tr .= '<td>'.$a->branchName.'</td>';
						$tr .= '<td>'.$a->totalpurchase.'</td>';
						$tr .= '<td>'.$this->main->qty($a->qty).'</td>';
						$tr .= '<td>'.$this->main->currency($a->price).'</td>';
						$tr .= '<td>'.$this->main->currency($a->total).'</td>';
						$tr .= '</tr>';

						$total11 += $a->totalpurchase;
		                $total8 += $a->qty;
		                $total3 += $a->price;
		                $total4 += $a->total;

						echo $tr;
					}
				endif;
			endif;
		?>
</tbody>
<?php if($this->input->post("group") == "all"): ?>
 <tfoot>
    <tr>
        <th colspan="7"><?= $this->lang->line('lb_total') ?></th>
        <th class="total8"><?= $this->main->qty($total8); ?></th>
        <th class="total9"><?= ''; ?></th>
        <th class="total10"><?= ''; ?></th>
        <th class="total1"><?= $this->main->currency($total1); ?></th>
        <th class="total2"></th>
        <th class="total3"><?= $this->main->currency($total3); ?></th>
        <th class="total4"><?= $this->main->currency($total4); ?></th>
        <th class="total5"><?= $this->main->currency($total5); ?></th>
        <th class="total6"><?= $this->main->currency($total6); ?></th>
        <th class="total7"><?= $this->main->currency($total7); ?></th>
        <th class=""></th>
        <th class=""></th>
    </tr>
<?php elseif($this->input->post("group") == "gr_purchase"): ?>
    <tr>
        <th colspan="5"><?= $this->lang->line('lb_total') ?></th>
        <th class="total8"><?= $this->main->qty($total8) ?></th>
        <th class="total1"><?= $this->main->currency($total1) ?></th>
        <th class="total3"><?= $this->main->currency($total3) ?></th>
        <th class="total4"><?= $this->main->currency($total4) ?></th>
        <th class="total6"><?= $this->main->currency($total6) ?></th>
        <th class="total7"><?= $this->main->currency($total7); ?></th>
        <th class=""></th>
        <th class=""></th>
    </tr>
<?php elseif($this->input->post("group") == "product_name"): ?>
    <tr>
        <th colspan="3"><?= $this->lang->line('lb_total') ?></th>
         <th class="total8"><?= $this->main->qty($total8); ?></th>
         <th class=""></th>
         <th class=""></th>
    </tr>
<?php elseif($this->input->post("group") == "vendor" || $this->input->post("group") == "store"):?>
    <tr>
        <th colspan="2"><?= $this->lang->line('lb_total') ?></th>
        <th class="total11"><?= $total11; ?></th>
        <th class="total8"><?= $this->main->qty($total8); ?></th>
        <th class="total3"><?= $this->main->currency($total3); ?></th>
        <th class="total4"><?= $this->main->currency($total4); ?></th>
    </tr>
</tfoot> 
<?php endif;?>
</table>
