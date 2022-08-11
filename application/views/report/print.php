<html>
<head>
	<title>Good Receipt</title>
	<link rel="stylesheet" type="text/css" href="<?= base_url('aset/css/report.css'); ?>">
</head>
<body class="report">
	<div class="header">
		<h3><?= $title; ?></h3>
		<img class="logo" src="<?= base_url('img/rc.png'); ?>">
		<p>JL. Indrayasa No.158, Mekar Wangi, Bandung</p>
		<p>2018/01/01 - 2018-01-30</p>
	</div>
	<div class="body">
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<th>No</th>
					<th>Date</th>
					<th>Good Receipt Code</th>
					<th>Receipt Name</th>
					<th>Total Qty</th>
					<th>Total Price</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>1</td>
					<td>2018-01-01</td>
					<td>1801000001</td>
					<td>PIPESYS MART</td>
					<td>100</td>
					<td>IDR 200.000,00</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td>Total</td>
					<td></td>
					<td></td>
					<td></td>
					<td>100</td>
					<td>IDR 200.000,00</td>
				</tr>
			</tfoot>
		</table>		
	</div>
	<?php $this->load->view("report/footer"); ?>
</body>
</html>