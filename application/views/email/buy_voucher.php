<div class="div-email">
<?php 
if($data->App != "Pipesys"):
	$App = "People Shape Sales";
else:
	$App = $data->App;
endif;
?>
<?php
	$qty  		= $data->parentQty;
    $QtyModule 	= $data->Qty;
    $price 		= $data->parentPrice;
    $priceModule= $data->Price;
    if($data->Module == "android"):
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
  <div class="content">
    <div class="es-wrapper-color"> 
   <!--[if gte mso 9]>
      <v:background xmlns:v="urn:schemas-microsoft-com:vml" fill="t">
        <v:fill type="tile" color="#e4e5e7"></v:fill>
      </v:background>
    <![endif]--> 
   <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top;"> 
     <tbody><tr style="border-collapse:collapse;"> 
      <td valign="top" style="padding:0;Margin:0;"> 
       <table class="es-content" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;"> 
         <tbody><tr style="border-collapse:collapse;"> 
          <!-- <td class="es-adaptive" align="center" style="padding:0;Margin:0;"> 
           <table class="es-content-body" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;" width="600" cellspacing="0" cellpadding="0" align="center"> 
             <tbody><tr style="border-collapse:collapse;"> 
              <td align="left" style="padding:10px;Margin:0;"> 
               [if mso]><table width="580"><tr><td width="280" valign="top"><![endif] 
               <table class="es-left" cellspacing="0" cellpadding="0" align="left" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left;"> 
                 <tbody><tr style="border-collapse:collapse;"> 
                  <td width="280" align="left" style="padding:0;Margin:0;"> 
                   <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;"> 
                     <tbody>
                      <tr style="border-collapse:collapse;"> 
                      <td class="es-infoblock" align="left" style="padding:0;Margin:0;line-height:14px;font-size:12px;color:#999999;"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:12px;font-family:arial, &#39;helvetica neue&#39;, helvetica, sans-serif;line-height:14px;color:#999999;">Put your preheader text here</p></td> 
                     </tr> 
                   </tbody></table></td> 
                 </tr> 
               </tbody></table> 
               [if mso]></td><td width="20"></td><td width="280" valign="top"><![endif] 
               <table class="es-right" cellspacing="0" cellpadding="0" align="right" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:right;"> 
                 <tbody><tr style="border-collapse:collapse;"> 
                  <td width="280" align="left" style="padding:0;Margin:0;"> 
                   <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;"> 
                     <tbody><tr style="border-collapse:collapse;"> 
                      <td class="es-infoblock" esdev-links-color="#999999" align="right" style="padding:0;Margin:0;line-height:14px;font-size:12px;color:#999999;"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:12px;font-family:arial, &#39;helvetica neue&#39;, helvetica, sans-serif;line-height:14px;color:#999999;"><a href="https://viewstripo.email/" target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, &#39;helvetica neue&#39;, helvetica, sans-serif;font-size:12px;text-decoration:none;color:#999999;">View in browser</a></p></td> 
                     </tr> 
                   </tbody></table></td> 
                 </tr> 
               </tbody></table> 
               [if mso]></td></tr></table><![endif]</td>  -->
             </tr> 
           </tbody></table></td> 
         </tr> 
       </tbody></table> 
       <table class="es-content" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;"> 
         <tbody><tr style="border-collapse:collapse;"> 
          <td align="center" style="padding:0;Margin:0;"> 
           <table class="es-content-body" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#ededed00;" width="600" cellspacing="0" cellpadding="0" bgcolor="#ededed00" align="center"> 
             <tbody><tr style="border-collapse:collapse;"> 
              <td align="left" style="padding:0;Margin:0;"> 
               <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;"> 
                 <tbody><tr style="border-collapse:collapse;"> 
                  <td width="600" valign="top" align="center" style="padding:0;Margin:0;"> 
                   <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;"> 
                     <tbody><tr style="border-collapse:collapse;"> 
                      <td align="center" style="padding:0;Margin:0;padding-top:20px;padding-left:40px;padding-right:40px;"><h1 style="Margin:0;line-height:36px;mso-line-height-rule:exactly;font-family:tahoma, verdana, segoe, sans-serif;font-size:30px;font-style:normal;font-weight:normal;color:#333333;">Terimakasih telah memesan voucher <?= $App; ?>.</h1></td> 
                     </tr> 
                     <tr style="border-collapse:collapse;"> 
                      <td align="center" style="padding:0;Margin:0;"><img class="adapt-img" src="<?= base_url('aset/images/email/voucher.png');?>" alt="" width="600" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"></td> 
                     </tr> 
                     <tr style="border-collapse:collapse;"> 
                      <td align="center" style="padding:0;Margin:0;padding-bottom:5px;padding-left:40px;padding-right:40px;"><h3 style="Margin:0;line-height:24px;mso-line-height-rule:exactly;font-family:tahoma, verdana, segoe, sans-serif;font-size:20px;font-style:normal;font-weight:normal;color:#333333;"><p><b>Yang Terhormat <?= $nama; ?>,</b></p></h3></td> 
                     </tr> 
                     <tr style="border-collapse:collapse;"> 
                      <td align="center" style="padding:0;Margin:0;padding-bottom:10px;padding-left:40px;padding-right:40px;"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:16px;font-family:arial, &#39;helvetica neue&#39;, helvetica, sans-serif;line-height:24px;color:#333333;"><?= $this->main->konversi_tanggal("d M Y",$data->Date); ?></p></td> 
                     </tr> 
                   </tbody></table></td> 
                 </tr> 
               </tbody></table></td> 
             </tr> 
             <!-- <tr style="border-collapse:collapse;"> 
              <td align="left" style="padding:0;Margin:0;"> 
               <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;"> 
                 <tbody><tr style="border-collapse:collapse;"> 
                  <td width="600" valign="top" align="center" style="padding:0;Margin:0;"> 
                   <table style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;" width="100%" cellspacing="0" cellpadding="0" bgcolor="#ffffff"> 
                     <tbody><tr style="border-collapse:collapse;"> 
                      <td align="center" style="padding:0;Margin:0;"><img class="adapt-img" src="<?= base_url('aset/images/email/77861515064908340.png');?>" alt="" width="600" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"></td> 
                     </tr> 
                   </tbody></table></td> 
                 </tr> 
               </tbody></table></td> 
             </tr>  -->
           </tbody></table></td> 
         </tr> 
       </tbody></table> 
       <table class="es-content" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;"> 
         <tbody><tr style="border-collapse:collapse;"> 
          <td align="center" style="padding:0;Margin:0;"> 
           <table class="es-content-body" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;" width="600" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center"> 
             <tbody><tr style="border-collapse:collapse;"> 
              <td align="left" style="padding-top: 35px;padding-left: 20px;padding-right: 20px;padding-bottom: 15px;"> 
               <table class="es-left" cellspacing="0" cellpadding="0" align="left" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left;"> 
                 <tbody><tr style="border-collapse:collapse;"> 
                  <td class="es-m-p20b" width="700" align="left" style="padding:0;Margin:0;"> 
					<p class="">
					Terimakasih telah memesan voucher <?= $App; ?>.<br>Anda telah memesan pembelian voucher pada tanggal <?= $this->main->konversi_tanggal("d M Y",$data->Date); ?>.
					<br/>
					<span class="red_txt">Mohon untuk mengirimkan bukti transfer dengan membalas email info.pipesys@gmail.com .</span><br>
					Kami akan mengirimkan kode voucher ke alamat email Anda setelah pembayaran kami verifikasi.
					</p>
					<b>Detail Pembelian :</b> 
                   	<table class="table">
						<tr>
							<td style="width: 35%">Kode Transaksi</td>
							<td><?= $data->Code; ?></td>
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
						
					</table></td> 
                  <td class="es-hidden" width="20" style="padding:0;Margin:0;"></td> 
                 </tr> 
               </tbody></table> </td> 
             </tr> 
             <tr style="border-collapse:collapse;"> 
              <td align="left" style="padding-top: 35px;padding-left: 20px;padding-right: 20px;padding-bottom: 15px;"> 
               <table class="es-left" cellspacing="0" cellpadding="0" align="left" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left;"> 
                 <tbody><tr style="border-collapse:collapse;"> 
                  <td class="es-m-p20b" width="700" align="left" style="padding:0;Margin:0;"> 
                   <p class="">
					Thank you for request <?= $App; ?> Voucher. You have requested voucher on <?= $this->main->konversi_tanggal("d M Y",$data->Date); ?>.
					<br/>
					<span class="red_txt">Please send Payment Proof by reply to email info.pipesys@gmail.com .</span><br>
					We will send voucher code to your email address after payment has been verified.
					</p>
					<b>Detail Purchase :</b> 
					<table class="table">
						<tr>
							<td style="width: 35%">Transaction Code</td>
							<td><?= $data->Code; ?></td>
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
						
					</table></td> 
                  <td class="es-hidden" width="20" style="padding:0;Margin:0;"></td> 
                 </tr>
                  <tr style="border-collapse:collapse;"> 
                    <td align="center" style="padding:0;Margin:0;padding-top: 35px; padding-bottom:10px;padding-left:40px;padding-right:40px;"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:16px;font-family:arial, &#39;helvetica neue&#39;, helvetica, sans-serif;line-height:24px;color:#333333;"><span class="red_txt">Please make payment before <?= date("d M Y",strtotime($data->ExpirePurchase)); ?>.</span><br/>
						<span class="blue_txt">Transfer Validation will be processed on working days. Monday to Friday pukul 08.00 - 17.00</span></p></td> 
                   </tr> 
               </tbody></table> </td> 
             </tr> 
             <tr style="border-collapse:collapse;"> 
              <td style="padding:0;Margin:0;background-color:#ededed00;" bgcolor="#ededed00" align="left"> 
               <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;"> 
                 <tbody><tr style="border-collapse:collapse;"> 
                  <td width="600" valign="top" align="center" style="padding:0;Margin:0;"> 
                   <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;"> 
                     <tbody><tr style="border-collapse:collapse;"> 
                      <td align="center" style="padding:0;Margin:0;"><img class="adapt-img" src="./Trigger newsletter_files/6571515162565064.png" alt="" width="600" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"></td> 
                     </tr> 
                   </tbody></table></td> 
                 </tr> 
               </tbody></table></td> 
             </tr> 
           </tbody></table></td> 
         </tr> 
       </tbody></table> 
<!--        <table class="es-content" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;"> 
         <tbody><tr style="border-collapse:collapse;"> 
          <td align="center" style="padding:0;Margin:0;"> 
           <table class="es-content-body" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#EDEDED;" width="600" cellspacing="0" cellpadding="0" bgcolor="#ededed" align="center"> 
             <tbody><tr style="border-collapse:collapse;"> 
              <td align="left" style="Margin:0;padding-top:20px;padding-bottom:20px;padding-left:40px;padding-right:40px;"> 
               <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;"> 
                 <tbody><tr style="border-collapse:collapse;"> 
                  <td width="520" align="left" style="padding:0;Margin:0;"> 
                   <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;"> 
                     <tbody><tr style="border-collapse:collapse;"> 
                      <td align="center" style="padding:0;Margin:0;"><h3 style="Margin:0;line-height:24px;mso-line-height-rule:exactly;font-family:tahoma, verdana, segoe, sans-serif;font-size:20px;font-style:normal;font-weight:normal;color:#333333;">Plan of the Webinar</h3></td> 
                     </tr> 
                     <tr style="border-collapse:collapse;"> 
                      <td align="center" style="padding:0;Margin:0;padding-top:10px;padding-bottom:10px;"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, &#39;helvetica neue&#39;, helvetica, sans-serif;line-height:21px;color:#333333;">Ella Becker and Archie Kendal have prepared a report on the principles of a Message Imposition, and will share their experience regarding optimization.<br></p></td> 
                     </tr> 
                     <tr style="border-collapse:collapse;"> 
                      <td align="center" style="padding:0;Margin:0;padding-bottom:10px;padding-top:15px;"><h3 style="Margin:0;line-height:24px;mso-line-height-rule:exactly;font-family:tahoma, verdana, segoe, sans-serif;font-size:20px;font-style:normal;font-weight:normal;color:#333333;">What you'll learn</h3></td> 
                     </tr> 
                   </tbody></table></td> 
                 </tr> 
                 <tr style="border-collapse:collapse;"> 
                  <td class="es-m-p20b" width="580" align="center" style="padding:0;Margin:0;"> 
                   <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;"> 
                     <tbody><tr style="border-collapse:collapse;"> 
                      <td style="padding:0;Margin:0;"> 
                       <table class="es-table-not-adapt" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;"> 
                         <tbody><tr style="border-collapse:collapse;"> 
                          <td valign="top" align="left" style="padding:0;Margin:0;padding-top:10px;padding-bottom:10px;padding-right:10px;"><img src="./Trigger newsletter_files/Check_Mark_Black5.png" alt="" width="16" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"></td> 
                          <td align="left" style="padding:0;Margin:0;"> 
                           <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;"> 
                             <tbody><tr style="border-collapse:collapse;"> 
                              <td align="left" style="padding:0;Margin:0;"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, &#39;helvetica neue&#39;, helvetica, sans-serif;line-height:21px;color:#333333;">How to create a HTML-template for email.</p></td> 
                             </tr> 
                           </tbody></table></td> 
                         </tr> 
                         <tr style="border-collapse:collapse;"> 
                          <td valign="top" align="left" style="padding:0;Margin:0;padding-top:10px;padding-bottom:10px;padding-right:10px;"><img src="./Trigger newsletter_files/Check_Mark_Black5.png" alt="" width="16" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"></td> 
                          <td align="left" style="padding:0;Margin:0;"> 
                           <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;"> 
                             <tbody><tr style="border-collapse:collapse;"> 
                              <td align="left" style="padding:0;Margin:0;"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, &#39;helvetica neue&#39;, helvetica, sans-serif;line-height:21px;color:#333333;">What is responsive web-design.</p></td> 
                             </tr> 
                           </tbody></table></td> 
                         </tr> 
                         <tr style="border-collapse:collapse;"> 
                          <td valign="top" align="left" style="padding:0;Margin:0;padding-top:10px;padding-bottom:10px;padding-right:10px;"><img src="./Trigger newsletter_files/Check_Mark_Black5.png" alt="" width="16" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"></td> 
                          <td align="left" style="padding:0;Margin:0;"> 
                           <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;"> 
                             <tbody><tr style="border-collapse:collapse;"> 
                              <td align="left" style="padding:0;Margin:0;"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, &#39;helvetica neue&#39;, helvetica, sans-serif;line-height:21px;color:#333333;">Services for testing Emails.</p></td> 
                             </tr> 
                           </tbody></table></td> 
                         </tr> 
                         <tr style="border-collapse:collapse;"> 
                          <td valign="top" align="left" style="padding:0;Margin:0;padding-top:10px;padding-bottom:10px;padding-right:10px;"><img src="./Trigger newsletter_files/Check_Mark_Black5.png" alt="" width="16" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"></td> 
                          <td align="left" style="padding:0;Margin:0;"> 
                           <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;"> 
                             <tbody><tr style="border-collapse:collapse;"> 
                              <td align="left" style="padding:0;Margin:0;"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, &#39;helvetica neue&#39;, helvetica, sans-serif;line-height:21px;color:#333333;">How to automate working process.</p></td> 
                             </tr> 
                           </tbody></table></td> 
                         </tr> 
                       </tbody></table></td> 
                     </tr> 
                     <tr style="border-collapse:collapse;"> 
                      <td align="center" style="padding:0;Margin:0;padding-left:10px;padding-right:10px;padding-top:20px;"><span class="es-button-border" style="border-style:solid;border-color:transparent;background:#34265F none repeat scroll 0% 0%;border-width:0px;display:inline-block;border-radius:5px;width:auto;"><a href="https://viewstripo.email/" class="es-button" target="_blank" style="mso-style-priority:100 !important;text-decoration:none;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, &#39;helvetica neue&#39;, helvetica, sans-serif;font-size:18px;color:#FFFFFF;border-style:solid;border-color:#34265F;border-width:10px 20px 10px 20px;display:inline-block;background:#34265F none repeat scroll 0% 0%;border-radius:5px;font-weight:normal;font-style:normal;line-height:22px;width:auto;text-align:center;">Reserve my seat</a></span></td> 
                     </tr> 
                   </tbody></table></td> 
                 </tr> 
               </tbody></table></td> 
             </tr> 
           </tbody></table></td> 
         </tr> 
       </tbody></table>  -->
     </tr> 
   </tbody></table> 
  </div>  
  </div>
</div>