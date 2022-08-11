<style type="text/css">
/*		.view-info {
			font-family:monospace;
			background:#fafbfc;
		}*/
		.view-info .div-email {
			background: #fff;
			margin:0px 25%;
			border:1px solid #cdcdcd;
		}
		.view-info .header {
			padding:10px 20px;
			border-bottom:1px solid #cdcdcd;
		/* 	background:#2196F3; */
		/* 	color:#fff; */
		}
		.view-info .header .title{
			font-weight:bold;
			font-size:25px;
		}
		.view-info .content{
			padding:10px 20px;
			min-height:200px;
		}
		.view-info .footer {
			padding:15px;
			text-align:center;
			border-top:1px solid #cdcdcd;
			background: #eaeaea;
			font-size:11px
		}
		.view-info .footer .title{
			display:block;
		}
		.view-info .footer .alamat{
			display:block;
		}
		.view-info .btn {
			text-align: center;
		    display: block;
		    border-radius: 3px;
		    color: #fff;
		    background: #1b9ce2;
		    text-decoration: none;
		    font-family: Roboto,Helvetica Neue,Helvetica,Arial,sans-serif;
		    font-size: 14px;
		    border-top: 12px solid #1b9ce2;
		    border-right: 30px solid #1b9ce2;
		    border-bottom: 12px solid #1b9ce2;
		    border-left: 30px solid #1b9ce2;
		    text-transform: uppercase;
		    width: 50%;
		}
		.view-info .table {
			width: 100%;
		    border-collapse: collapse;
		    width: 100%;
		    margin-bottom: 15px;
		}

		.view-info .table th, .view-info .table td {
		    text-align: left;
		    padding: 8px;
		}

		.view-info .table tr:nth-child(even){background-color: #eaeaea}

		.view-info .table th {
		    background-color: #4CAF50;
		    color: white;
		}
		.view-info .bg-gray {
			background: #eaeaea;
			padding:10px;
			border-radius: 3px;
		}
		.view-info .red_txt {
			color: red !important;
		}
	</style>
<?php 
	if($data->App != "Pipesys"):
		$App = "People Shape Sales";
	else:
		$App = $data->App;
	endif;
?>
<div>
<?php
	$qty  		= $data->parentQty;
    $QtyModule 	= $data->Qty;
    $price 		= $data->parentPrice;
    $priceModule= $data->Price;
    if($data->Module == "2"):
    	$qty  	= $data->Qty;
    	$price 	= $data->Price;
    	$QtyModule  	= 0;
    	$priceModule 	= 0;
    endif;

	if($data->Status == "proccess"):
    	$status = "Transaction Proccess";
    	$label  = 'label-info';
    elseif($data->Status == "finish"):
    	$status = "Transaction Success";
    	$label  = "label-success";
    elseif($data->Status == "expire"):
    	$status = "Transaction Expire";
    	$label  = "label-danger";
    else:
    	$status = "Transaction Cancel";
    	$label  = "label-danger";
    endif;

    $trModuleIndo 		= '';
    $trModuleEng  		= '';
    $trDeviceIndo 		= '';
    $trDeviceEnd  		= '';
    $trModulePriceIndo 	= '';
    $trModulePriceEnd 	= '';
    $trDevicesPriceIndo = '';
    $trDevicesPriceEng  = '';
    if($QtyModule>0):
    	$trModuleIndo = '<tr>
			<td>Kuantitas Modul / Perangkat</td>
			<td>'.number_format($QtyModule,0,",",".") .' Voucher</td>
		</tr>';
		$trModuleEng = '<tr>
			<td>Module / Devices Quantity</td>
			<td>'.number_format($QtyModule,0,",",".") .' Voucher</td>
		</tr>';
		$trModulePriceIndo = '<tr>
			<td>Harga Module / Perangkat</td>
			<td>'."IDR ".number_format($priceModule,2,".",",").'</td>
		</tr>';
		$trModulePriceEnd = '<tr>
			<td>Module / Devices Price</td>
			<td>'."IDR ".number_format($priceModule,2,".",",").'</td>
		</tr>';

    endif;

    if($qty>0):
    	$trDeviceIndo = '<tr>
			<td>Kuantitas User Tambahan</td>
			<td>'.number_format($qty,0,",",".") .' Voucher</td>
		</tr>';
		$trDeviceEnd = '<tr>
			<td>Additional User Quantity</td>
			<td>'.number_format($qty,0,",",".") .' Voucher</td>
		</tr>';
		$trDevicesPriceIndo = '<tr>
			<td>Harga User Tambahan</td>
			<td>'."IDR ".number_format($price,2,".",",").'</td>
		</tr>';
		$trDevicesPriceEng = '<tr>
			<td>Additional User Price</td>
			<td>'."IDR ".number_format($price,2,".",",").'</td>
		</tr>';
    endif;
    // echo '<span class="label '.$label.'">'.$status.'</span>';
?>
</div>
<div class="content view-info">
	<p class="">
	Terimakasih telah memesan voucher <?= $App; ?> Anda telah memesan pembelian voucher pada tanggal <?= $this->main->konversi_tanggal("d M Y",$data->Date); ?>.
	<br/>
	<span class="red_txt">Mohon untuk mengirimkan bukti transfer dengan membalas email ini.</span>
	</p>
	<b>Detail Pembelian :</b> 
	<table class="table">
		<tr>
			<td style="width: 35%">Kode Transaksi</td>
			<td><?= $data->Code; ?></td>
		</tr>
		<tr>
			<td>Nama</td>
			<td><?= $data->Name; ?></td>
		</tr>
		<tr>
			<td>Email</td>
			<td><?= $data->Email ?></td>
		</tr>
		<tr>
			<td>Aplikasi</td>
			<td><?= $App; ?></td>
		</tr>
		<tr>
			<td>Paket</td>
			<td><?= $data->Type; ?></td>
		</tr>
		<?= $trModuleIndo.$trDeviceIndo.$trModulePriceIndo.$trDevicesPriceIndo ?>
		<tr>
			<td>Total Pembelian</td>
			<td><?= "IDR ".number_format($data->TotalPrice,2,".",","); ?></td>
		</tr>
	</table>
	<b>Transfer Ke:</b>
	<table class="table">
		<tr>
			<td style="width: 35%">Bank</td>
			<td><?= $data->Bank; ?></td>
		</tr>
		<tr>
			<td>Atas Nama</td>
			<td> CV RC Electronic</td>
		</tr>
		<tr>
			<td>No Rekening</td>
			<td>06.08000.20111</td>
		</tr>
		<tr>
			<td>Total Transfer</td>
			<td><?= "IDR ".number_format($data->TotalPrice,2,".",","); ?></td>
		</tr>
		
	</table>
	<?php $this->load->view("email/footer_indo"); ?>
</div>
<hr/>
<div class="content view-info">
	<p class="">
	Thank you for request <?= $App; ?> Voucher. You have requested voucher on <?= $this->main->konversi_tanggal("d M Y",$data->Date); ?>.
	<br/>
	<span class="red_txt">Please send Payment Proof by reply this email.</span>
	</p>
	<b>Detail Purchase :</b> 
	<table class="table">
		<tr>
			<td style="width: 35%">Transaction Code</td>
			<td><?= $data->Code; ?></td>
		</tr>
		<tr>
			<td>Name</td>
			<td><?= $data->Name; ?></td>
		</tr>
		<tr>
			<td>Email</td>
			<td><?= $data->Email ?></td>
		</tr>
		<tr>
			<td>Application</td>
			<td><?= $App; ?></td>
		</tr>
		<tr>
			<td>Package</td>
			<td><?= $data->Type; ?></td>
		</tr>
		<?= $trModuleEng.$trDeviceEnd.$trModulePriceEnd.$trDevicesPriceEng ?>
		<tr>
			<td>Total Purchase</td>
			<td><?= "IDR ".number_format($data->TotalPrice,2,".",","); ?></td>
		</tr>
	</table>
	<b>Transfer To:</b>
	<table class="table">
		<tr>
			<td style="width: 35%">Bank</td>
			<td><?= $data->Bank; ?></td>
		</tr>
		<tr>
			<td>Account Name</td>
			<td> CV RC Electronic</td>
		</tr>
		<tr>
			<td>Account Number</td>
			<td>06.08000.20111</td>
		</tr>
		<tr>
			<td>Transfer Amount</td>
			<td><?= "IDR ".number_format($data->TotalPrice,2,".",","); ?></td>
		</tr>
		
	</table>
	<?php $this->load->view("email/footer_eng"); ?>
</div>