<?php
	$PrintBy = "Prepared By : ".$this->session->nama.", Printed : ".date("Y-m-d H:i:s");
	$address = $company->address;
	$content = $template->Content;

  preg_match_all("/##(\\w+)/", $content, $matches);
  $hastag = $matches[1];

  $content = str_replace("##Logo", '<img src="'.$logo.'" height="50px">', $content);
  $content = str_replace("##CompanyName", $company_name, $content);
  $content = str_replace("##CompanyAddress", $address, $content);
  $content = str_replace("##CompanyContact", $company->phone_company, $content);
  $content = str_replace("##PrintBy", $PrintBy, $content);
  $content = str_replace("##TitleTransaction", $title2, $content);

  $content = str_replace("##TransactionCode", $list->InvoiceNo, $content);
  $content = str_replace("##TransactionDate", $list->Date, $content);
  $content = str_replace("##Term", $list->Term, $content);
  $content = str_replace("##DueDate", date("Y-m-d", strtotime($list->Date." +".$list->Term." Days")), $content);
  $content = str_replace("##VendorName", $list->vendorName, $content);
  $content = str_replace("##VendorAddress", $list->address, $content);
  $content = str_replace("##VendorContact", $list->vendorPhone, $content);
  $content = str_replace("##Remark", $list->Remark, $content);


  $content = str_replace("##GrandTotal", $this->main->currency($list->Total), $content);
  
  $penyebut = ucfirst($this->main->terbilang($list->Total));
  $content  = str_replace("##SpellingGrandTotal", "(".$penyebut.")", $content);

  $item = '';
  foreach ($detail as $k => $v) {
    $no = $k + 1;
    if($v->invoiceType == "return"):
      $code = $v->ReturNo;
    else:
      $code = $v->ReceiveNo;
    endif;
    $item  .= '<tr>';
    if(in_array("GridNo", $hastag)): $item .= '<td>'.$no.'</td>'; endif;
    if(in_array("GridTransactionCode", $hastag)): $item .= '<td>'.$code.'</td>'; endif;
    if(in_array("GridTransactionDate", $hastag)): $item .= '<td>'.$v->Date.'</td>'; endif;
    if(in_array("GridPurchaseNo", $hastag)): $item .= '<td>'.$v->PurchaseNo.'</td>'; endif;
    if(in_array("GridDeliveryCode", $hastag)): $item .= '<td>'.$v->DeliveryCode.'</td>'; endif;
    if(in_array("GridSubTotal", $hastag)): $item .= '<td>'.$this->main->currency($v->SubTotal).'</td>'; endif;
    if(in_array("GridDiscountValue", $hastag)): $item .= '<td>'.$this->main->currency($v->Discount).'</td>'; endif;
    if(in_array("GridTax", $hastag)): $item .= '<td>'.$this->main->currency($v->PPN).'</td>'; endif;
    if(in_array("GridDeliveryCost", $hastag)): $item .= '<td>'.$this->main->currency($v->DeliveryCost).'</td>'; endif;
    if(in_array("GridTotal", $hastag)): $item .= '<td>'.$this->main->currency($v->Total).'</td>'; endif;

    $item .= '</tr>';
  }

  $no = 0;
  $s_detail ='';
  foreach ($hastag as $key => $v) {
    if (strpos($v, 'Grid') !== false):
      $no += 1;
      if($no != 1):
        $content = str_replace("##".$v, '', $content);
        $content = str_replace('<td></td>', '', $content);
      else:
        $s_detail = $v;
      endif;
    endif;
  }
  $content = str_replace("##".$s_detail, '', $content);
  $content = trim(preg_replace('/\s+/', ' ', $content));
  $content = str_replace('<tr> <td></td> </tr>', $item, $content);

  if(in_array("CompanyBank", $hastag)):
    $bank = $this->main->get_bank_company();
    $item = '';
    foreach ($bank as $a) {
      $item .= '<table style="margin-bottom:15px">';
      $item .= '<tr><td>Bank Name</td><td>:</td><td>'.$a->BankName.'</td></tr>';
      $item .= '<tr><td>Branch Name (Bank)</td><td>:</td><td>'.$a->BankBranch.'</td></tr>';
      $item .= '<tr><td>Name Account</td><td>:</td><td>'.$a->AnRekening.'</td></tr>';
      $item .= '<tr><td>No.Account</td><td>:</td><td>'.$a->NoRekening.'</td></tr>';
      $item .= '</table>';
    }
    $content = str_replace("##CompanyBank", $item, $content);
  endif;
?>

<html>
<head>
    <title><?= $title; ?></title>
    <link rel="apple-touch-icon" href="<?= base_url('img/rc.png') ?>">
    <link rel="shortcut icon" href="<?= base_url('img/rc.png'); ?>">  <!-- Stylesheets -->
    <?php 
      if($cetak): 
       $this->load->view("report/report_css");
      endif;
    ?>
</head>
<body>
	<?= $content; ?>
</body>
</html>