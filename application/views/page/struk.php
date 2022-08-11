<!DOCTYPE html>
<html>
<head>
	<title><?= $title; ?></title>
	<style type="text/css">
		.vPeriode{display: none}
		.w-50{
			width: 50%;
		}
		.text-left{
			text-align: left;
		}
		.text-right{
			text-align: right;
		}
		.text-center{
			text-align: center;
		}
	</style>
	<?php if($cetak != "pdf"): ?>
		<style type="text/css">
			body{
				min-width: 300px;
				width: 300px;
				margin: auto;
				border: 1px solid #ccc;
				font-size: 12px;
			}
		</style>
	<?php endif; ?>
</head>
<body>
	<div class="page-receipt">
	    <div class="header">
	    	<?php $this->load->view("print_header"); ?>
	    </div>
	</div>
	<div class="content">
		<table style="width:100%;margin:0px;">
			<tr>
				<td class="w-50 text-left">Payment No <?= $list->PaymentNo ?></td>
				<td class="text-right"><?= date("d-m-y/H:i", strtotime($list->Date)) ?></td>
			</tr>
			<tr><td class="w-50 text-left"><?= $list->Typetxt ?> <?= $list->Code ?></td></tr>
			<?php if($list->VendorID): echo '<tr><td>No.Selling'.$list->customerName.'</td></tr>'; endif; ?>
			<tr>
				<td><?= $list->PaymentTypetxt ?></td>
				<?php if($list->PaymentType != 0): echo '<td class="text-right">'.$list->accounttxt." ".$list->accountnotxt.'</td>'; endif; ?>
			</tr>
			<?php if($list->PaymentType != 0):?>
				<tr>
					<td class="w-50 text-left">Bank <?= $list->BankName ?></td>
					<td class="text-right">An <?= $list->AccountName ?></td>
				</tr>
			<?php endif; ?>
		</table>
		<hr style="border-top: 1px dashed #ccc;height: 1px;"  align="bottom">
		<table style="width:100%;margin:0px;">
			<thead>
				<tr class="text-center">
					<td>Name</td>
					<td>Qty</td>
					<td>Price</td>
					<td>Total</td>
				</tr>
			</thead>
			<tbody>
				<?php
					$sub_total 	= 0;
					foreach ($detail as $k => $v) {
						$price 		= (float) $v->Price;
						$qty 		= (float) $v->Qty;
						$TotalPrice = $price * $qty;
						$sub_total 	+= $TotalPrice;

						$item  = '<tr>';
						$item .= '<td class="text-left">'.$v->product_name.'</td>';
						$item .= '<td class="text-center">'.$v->Qty.'</td>';
						$item .= '<td class="text-right">'.number_format($v->Price).'</td>';
						$item .= '<td class="text-right">'.number_format($TotalPrice).'</td>';
						$item .= '</tr>';

						if($v->Discount>0):
							$total 	= $this->main->PersenttoRp($TotalPrice,$v->Discount);
							$sub_total -= $total;
							$item .= '<tr>';
							$item .= '<td class="text-right" colspan="3">Discount '.(float) $v->Discount.'%</td>';
							$item .= '<td class="text-right">('.number_format($total).')</td>';
							$item .= '</tr>';
						endif;

						echo $item;
					}
				?>
				<tr><td></td><td class="text-right" colspan="3"><hr style="border-top: 1px dashed #ccc;height: 1px;"  align="bottom"></td></tr>
				<?php if($list->Type == 3): ?>
					<tr>
						<td class="text-right" colspan="3">Sub Total</td>
						<td class="text-right"><?= number_format($sub_total) ?></td>
					</tr>
					<tr>
						<td class="text-right" colspan="3">PPN <?= (float) $sell->PPN ?>%</td>
						<td class="text-right"><?= number_format($sell->TotalPPN) ?></td>
					</tr>
					<tr><td></td><td class="text-right" colspan="3"><hr style="border-top: 1px dashed #ccc;height: 1px;"  align="bottom"></td></tr>
					<tr>
						<td class="text-right" colspan="3">Total</td>
						<td class="text-right"><?= number_format($sell->Payment) ?></td>
					</tr>
					<tr>
						<td class="text-right" colspan="3">Payment</td>
						<td class="text-right"><?= number_format($list->Total) ?></td>
					</tr>
					<?php if($list->UnPayment>0):?>
						<tr>
							<td class="text-right" colspan="3">Unpayment</td>
							<td class="text-right"><?= number_format($list->UnPayment) ?></td>
						</tr>
					<?php endif; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</body>
</html>