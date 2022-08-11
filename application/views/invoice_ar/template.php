<?php
	$PrintBy = "Prepared By : ".$this->session->nama.", Printed : ".date("Y-m-d H:i:s");
	$address = $company->address;
	$content = $template->Content;
  $CompanyBank = $this->main->get_bank_company();

	preg_match_all("/##(\\w+)/", $content, $matches);
	$hastag = $matches[1];
	// $this->main->echoJson($matches[1]);

	$content = str_replace("##Logo", '<img src="'.$logo.'" height="50px">', $content);
	$content = str_replace("##CompanyName", $company_name, $content);
	$content = str_replace("##CompanyAddress", $address, $content);
	$content = str_replace("##CompanyContact", $company->phone_company, $content);
	$content = str_replace("##PrintBy", $PrintBy, $content);
	$content = str_replace("##TitleTransaction", $title2, $content);

	$content = str_replace("##TransactionCode", $list->InvoiceNo, $content);
	$content = str_replace("##Term", $list->Term, $content);
	$content = str_replace("##DueDate", date("Y-m-d", strtotime($list->Date." +".$list->Term." Days")), $content);
	$content = str_replace("##TransactionDate", $list->Date, $content);
	$content = str_replace("##CustomerName", $list->vendorName, $content);
	$content = str_replace("##CustomerAddress", $list->vendorAddress, $content);
	$content = str_replace("##CustomerContact", $list->vendorPhone, $content);
	$content = str_replace("##Remark", $list->Remark, $content);

	$content = str_replace("##GrandTotal", $this->main->currency($list->Total), $content);

	$penyebut = ucfirst($this->main->terbilang($list->Total));
	$content  = str_replace("##SpellingGrandTotal", "(".$penyebut.")", $content);

  if(count($CompanyBank)>0):
    $tb_bank = '<table>';
  else:
    $tb_bank = '';
  endif;
  foreach ($CompanyBank as $k => $v) {
    if($v->BankName):
      $tb_bank .= '<tr>';
      $tb_bank .= '<td> BANK NAME : '.$v->BankName.'</td>';
      $tb_bank .= '</tr>';
    endif;
    if($v->BankBranch):
      $tb_bank .= '<tr>';
      $tb_bank .= '<td> BANK BRANCH : '.$v->BankBranch.'</td>';
      $tb_bank .= '</tr>';
    endif;
    if($v->NoRekening):
      $tb_bank .= '<tr>';
      $tb_bank .= '<td> BANK ACCOUNT NUMBER : '.$v->NoRekening.'</td>';
      $tb_bank .= '</tr>';
    endif;
    if($v->AnRekening):
      $tb_bank .= '<tr>';
      $tb_bank .= '<td> BANK ACCOUNT NAME : '.$v->AnRekening.'</td>';
      $tb_bank .= '</tr>';
    endif;
    $tb_bank .= '<tr>';
    $tb_bank .= '<td><hr></td>';
    $tb_bank .= '</tr>';
  }
  if(count($CompanyBank)>0):
    $tb_bank .= '</table>';
  endif;

  $content = str_replace("##CompanyBank", $tb_bank, $content);

	$item = '';
  foreach ($detail as $k => $v) {
    $no = $k + 1;
    if($v->invoiceType == "return"):
      $code = $v->ReturNo;
    else:
      $code = $v->DeliveryNo;
    endif;
    $item  .= '<tr>';
    if(in_array("GridNo", $hastag)): $item .= '<td>'.$no.'</td>'; endif;
    if(in_array("GridTransactionCode", $hastag)): $item .= '<td>'.$code.'</td>'; endif;
    if(in_array("GridTransactionDate", $hastag)): $item .= '<td>'.$v->Date.'</td>'; endif;
    if(in_array("GridSellingOrder", $hastag)): $item .= '<td>'.$v->SellNo.'</td>'; endif;
    if(in_array("GridPoCode", $hastag)): $item .= '<td>'.$v->NoPOKonsumen.'</td>'; endif;
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