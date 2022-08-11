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

  $content = str_replace("##TransactionCode", $list->PaymentNo, $content);
  $content = str_replace("##TransactionDate", date("Y-m-d", strtotime($list->Date)), $content);
  $content = str_replace("##PaymnetType", $this->main->label_payment_type($list->PaymentType), $content);
  $content = str_replace("##GiroName", $list->GiroNo, $content);
  $content = str_replace("##AccountNo", $list->AcountNo, $content);
  $content = str_replace("##BankName", $list->BankName, $content);
  $content = str_replace("##AccountName", $list->AccountName, $content);
  $content = str_replace("##CustomerName", $list->vendorName, $content);
  $content = str_replace("##CustomerAddress", $list->vendorAddress, $content);
  $content = str_replace("##CustomerContact", $list->vendorPhone, $content);
  $content = str_replace("##Remark", $list->Remark, $content);

  $PaymentMethod = '';
  if(strlen($list->PaymentType)>0):
    $PaymentMethod .= '<div class="div-payment">';
    $PaymentMethod .= 'Payment Type : '.$this->lang->line('lb_cash')." <br>";
    $PaymentMethod .= 'Pay '.$this->lang->line('lb_cash')." : ".$this->main->currency($list->Cash)." <br>";
    $PaymentMethod .= '</div>';
  endif;
  if($list->PaymentType2 == 2):
    $PaymentMethod .= '<div class="div-payment">';
    $PaymentMethod .= 'Payment Type : '.$this->lang->line('lb_transfer')." <br>";
    $PaymentMethod .= $this->lang->line('lb_bank_acountno')." : ".$list->AcountNo." <br>";
    $PaymentMethod .= $this->lang->line('lb_bank_name')." : ".$list->BankName." <br>";
    $PaymentMethod .= $this->lang->line('lb_bank_acount')." : ".$list->AccountName." <br>";
    $PaymentMethod .= 'Pay '.$this->lang->line('lb_transfer')." : ".$this->main->currency($list->Credit)." <br>";
    $PaymentMethod .= '</div>';
  endif;
  if($list->PaymentType1 == 1):
    $PaymentMethod .= '<div class="div-payment">';
    $PaymentMethod .= 'Payment Type : '.$this->lang->line('lb_giro')." <br>";
    $PaymentMethod .= $this->lang->line('lb_girono')." : ".$list->GiroNo." <br>";
    $PaymentMethod .= $this->lang->line('lb_bank_name')." : ".$list->BankName1." <br>";
    $PaymentMethod .= $this->lang->line('lb_bank_acount')." : ".$list->AccountName1." <br>";
    $PaymentMethod .= 'Pay '.$this->lang->line('lb_giro')." : ".$this->main->currency($list->Giro)." <br>";
    $PaymentMethod .= '</div>';
  endif;
  $PaymentMethod .= '<div class="div-payment">';
  $PaymentMethod .= 'Total Payment : '.$this->main->currency($list->Total)." <br>";
  $PaymentMethod .= '</div>';

  $content = str_replace("##PaymentMethod", $PaymentMethod, $content);

  $content = str_replace("##TotalPaid", $this->main->currency($list->Total), $content);

  $penyebut = ucfirst($this->main->terbilang($list->Total));
  $content  = str_replace("##SpellingTotalPaid", "(".$penyebut.")", $content);

  $item = '';
  $TotalPay = 0;
  $TotalUnpaid = 0;
  foreach ($detail as $k => $v) {
    $no = $k + 1;

    $TotalPayx    = $v->TotalPay;
    $TotalUnpaidx = $v->TotalUnpaid;
    $Totalx       = $v->Total;

    if($v->Type == 1):
      $code = $v->InvoiceNo;
      $date = $v->invoiceDate;
    else:
      $code = $v->balanceCode;
      $date = $v->balanceDate;
    endif;

    if($v->balanceType == 2):
      $TotalPayx    = "-".$TotalPayx;
      $TotalUnpaidx = "-".$TotalUnpaidx;
      $Totalx       = "-".$Totalx;
    endif;
    $TotalPay += $TotalPayx;
    $TotalUnpaid += $TotalUnpaidx;

    $item  .= '<tr>';
    if(in_array("GridNo", $hastag)): $item .= '<td>'.$no.'</td>'; endif;
    if(in_array("GridTransactionCode", $hastag)): $item .= '<td>'.$code.'</td>'; endif;
    if(in_array("GridTransactionDate", $hastag)): $item .= '<td>'.$date.'</td>'; endif;
    if(in_array("GridBalanceType", $hastag)): $item .= '<td>'.$this->main->label_balance_type($v->balanceType,"cetak").'</td>'; endif;
    if(in_array("GridPay", $hastag)): $item .= '<td>'.$this->main->currency($TotalPayx).'</td>'; endif;
    if(in_array("GridUnpaid", $hastag)): $item .= '<td>'.$this->main->currency($TotalUnpaidx).'</td>'; endif;
    if(in_array("GridPaid", $hastag)): $item .= '<td>'.$this->main->currency($Totalx).'</td>'; endif;
    if(in_array("GridRemark", $hastag)): $item .= '<td>'.$v->Remark.'</td>'; endif;


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
  $content = str_replace("##TotalPay", $this->main->currency($TotalPay), $content);
  $content = str_replace("##TotalUnpaid", $this->main->currency($TotalPay), $content);

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
    <style type="text/css">
      .div-payment{
        margin-top: 10px;
      }
    </style>
</head>
<body>
  <?= $content; ?>
</body>
</html>