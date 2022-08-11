<div class="div-email">
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
?>
</div>
  <div class="content">
  	<?php 
		$App = "Pipesys";
	?>
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
                      <td align="center" style="padding:0;Margin:0;padding-top:20px;padding-left:40px;padding-right:40px;"><h1 style="Margin:0;line-height:36px;mso-line-height-rule:exactly;font-family:tahoma, verdana, segoe, sans-serif;font-size:30px;font-style:normal;font-weight:normal;color:#333333;">Terima kasih telah Membeli Voucher dari kami!</h1></td> 
                     </tr> 
                     <tr style="border-collapse:collapse;"> 
                      <td align="center" style="padding:0;Margin:0;"><img class="adapt-img" src="<?= base_url('aset/images/email/5832.png');?>" alt="" width="600" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"></td> 
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
					<p class="bg-gray">Terima kasih telah Membeli Voucher dari kami! </p>
					<b>Detail Pembelian :</b>
					<table class="table">
						<tr>
							<td style="width: 35%">Code Transaksi</td>
							<td><?= $data->Code; ?></td>
						</tr>
						<tr>
							<td>Aplikasi</td>
							<td><?= $App; ?></td>
						</tr>
						<tr>
							<td>Package</td>
							<td><?= $data->Type; ?></td>
						</tr>
						<?= $trModuleIndo.$trDeviceIndo.$trModulePriceIndo.$trDevicesPriceIndo ?>
						<tr>
							<td>Total Pembelian</td>
							<td><?= "IDR ".number_format($data->TotalPrice,2,".",","); ?></td>
						</tr>
					</table>
					<p>Daftar Voucher:</p>
					<table class="table">
						<tr>
							<th>Tipe Voucher</th>
							<th>Kode Voucher</th>
						</tr>
						<?php
						foreach ($voucher as $v) {
							echo '<tr>
								<td>Modul / Perangkat</td>
								<td>'.$v->Code.'</td>
								</tr>';
						}
						foreach ($voucher_parent as $v) {
							echo '<tr>
								<td>User Tambahan</td>
								<td>'.$v->Code.'</td>
								</tr>';
						}
						?>
					</table>
					<?php $this->load->view("email/footer_indo"); ?>
				</td> 
                  <td class="es-hidden" width="20" style="padding:0;Margin:0;"></td> 
                 </tr> 
               </tbody></table> </td> 
             </tr> 
             <tr style="border-collapse:collapse;"> 
              <td align="left" style="padding-top: 35px;padding-left: 20px;padding-right: 20px;padding-bottom: 15px;"> 
               <table class="es-left" cellspacing="0" cellpadding="0" align="left" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left;"> 
                 <tbody><tr style="border-collapse:collapse;"> 
                  <td class="es-m-p20b" width="700" align="left" style="padding:0;Margin:0;"> 
                   <p class="bg-gray">
					Thank you for Buying Voucher from us!
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

					<p>Voucher List :</p>
					<table class="table">
						<tr>
							<th>Voucher Type</th>
							<th>Voucher Code</th>
						</tr>
						<?php
						foreach ($voucher as $v) {
							echo '<tr>
								<td>Module / Devices</td>
								<td>'.$v->Code.'</td>
								</tr>';
						}
						foreach ($voucher_parent as $v) {
							echo '<tr>
								<td>Additional User</td>
								<td>'.$v->Code.'</td>
								</tr>';
						}
						?>
					</table>
					<?php $this->load->view("email/footer_eng"); ?>
				</td> 
                  <td class="es-hidden" width="20" style="padding:0;Margin:0;"></td> 
                 </tr>
                  <!-- <tr style="border-collapse:collapse;"> 
                    <td align="center" style="padding:0;Margin:0;padding-top: 35px; padding-bottom:10px;padding-left:40px;padding-right:40px;"><p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:16px;font-family:arial, &#39;helvetica neue&#39;, helvetica, sans-serif;line-height:24px;color:#333333;"><span class="red_txt">Please make payment before <?= date("d M Y",strtotime($data->ExpirePurchase)); ?>.</span><br/>
						<span class="blue_txt">Transfer Validation will be processed on working days. Monday to Friday pukul 08.00 - 17.00</span></p></td> 
                   </tr>  -->
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
     </tr> 
   </tbody></table> 
  </div>  
  </div>
</div>