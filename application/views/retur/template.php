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
  $content = str_replace("##TransactionCode", $list->ReturNo, $content);
  $content = str_replace("##TransactionDate", $list->Date, $content);
  $content = str_replace("##ReceiptCode", $list->ReceiveNo, $content);
  $content = str_replace("##SalesName", $list->salesName, $content);
  $content = str_replace("##VendorName", $list->vendorName, $content);
  $content = str_replace("##VendorAddress", $list->address, $content);
  $content = str_replace("##VendorContact", $list->vendorPhone, $content);
  $content = str_replace("##Remark", $list->Remark, $content);

  $item = '';
  $Total = 0;
  foreach ($detail as $k => $v) {
    $no = $k + 1;
    $item  .= '<tr>';
    $sub_total  = $v->Qty * $v->Price;
    $discount   = $this->main->PersenttoRp($sub_total,$v->transactionDiscount);
    $sub_total  = $sub_total - $discount;
    if($list->transactionTax == 1):
      $tax        = $this->main->PersenttoRp($sub_total,10);
    else:
      $tax        = 0;
    endif;
    if(in_array("GridNo", $hastag)): $item .= '<td>'.$no.'</td>'; endif;
    if(in_array("GridProductCode", $hastag)): $item .= '<td>'.$v->product_code.'</td>'; endif;
    if(in_array("GridProductName", $hastag)): $item .= '<td>'.$v->product_name.'</td>'; endif;
    if(in_array("GridQty", $hastag)): $item .= '<td>'.$this->main->qty($v->Qty).'</td>'; endif;
    if(in_array("GridProductUnit", $hastag)): $item .= '<td>'.$v->unit_name.'</td>'; endif;
    if(in_array("GridPriceUnit", $hastag)): $item .= '<td>'.$this->main->currency($v->Price).'</td>'; endif;
    if(in_array("GridProductConv", $hastag)): $item .= '<td>'.$v->Conversion.'</td>'; endif;
    if(in_array("GridDiscountPersent", $hastag)): $item .= '<td>'.$this->main->currency($v->transactionDiscount,TRUE).'</td>'; endif;
    if(in_array("GridDiscountValue", $hastag)): $item .= '<td>'.$this->main->currency($discount).'</td>'; endif;
    if(in_array("GridTax", $hastag)): $item .= '<td>'.$this->main->currency($tax).'</td>'; endif;
    if(in_array("GridSubTotal", $hastag)): $item .= '<td>'.$this->main->currency($v->Total).'</td>'; endif;

    $Total += $v->Total;

    $item .= '</tr>';
  }

  $content = str_replace("##Total", $this->main->currency($Total), $content);

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