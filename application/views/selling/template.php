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

  $content = str_replace("##TransactionCode", $list->SellNo, $content);
  $content = str_replace("##TransactionDate", date("Y-m-d", strtotime($list->Date)), $content);
  $content = str_replace("##PurchaseNo", $list->NoPOKonsumen, $content);
  $content = str_replace("##Term", $list->Term, $content);
  $content = str_replace("##DueDate", date("Y-m-d", strtotime($list->Date." +".$list->Term." Days")), $content);
  $content = str_replace("##DeliveryDate", $list->DeliveryDate, $content);
  $content = str_replace("##SalesName", $list->salesName, $content);
  $content = str_replace("##CustomerName", $list->customerName, $content);
  $content = str_replace("##CustomerAddress", $list->DeliveryAddress, $content);
  $content = str_replace("##CustomerContact", $list->customerPhone, $content);
  $content = str_replace("##Remark", $list->Remark, $content);

  $content = str_replace("##SubTotal", $this->main->currency($list->Total), $content);
  $content = str_replace("##TotalDiscount", $this->main->currency($list->Discount), $content);
  $content = str_replace("##TotalTax", $this->main->currency($list->TotalPPN), $content);
  $content = str_replace("##DeliveryCost", $this->main->currency($list->DeliveryCost), $content);
  $content = str_replace("##TotalPayment", $this->main->currency($list->Payment), $content);

  $item = '';
  foreach ($detail as $k => $v) {
    $no  = $k + 1;
    $qty = $v->Qty;
    $tax = 0;
    $unit_name  = $v->unit_name;
    $conversion = $v->Conversion;
    if($list->Tax == 1):
      $sub_total = $v->Qty * $v->Price;
      $discount   = $sub_total*($v->Discount/100);
      $sub_total  = $sub_total - $discount;
      $tax        = $sub_total*(10/100);
    endif;
    if($list->ProductType == 1):
      $qty = 0;
      $unit_name  = "PCS";
      $conversion = "1.00";
    endif;
    $item .= '<tr>';
    if(in_array("GridNo", $hastag)): $item .= '<td>'.$no.'</td>'; endif;
    if(in_array("GridProductCode", $hastag)): $item .= '<td>'.$v->product_code.'</td>'; endif;
    if(in_array("GridProductName", $hastag)): $item .= '<td>'.$v->product_name.'</td>'; endif;
    if(in_array("GridOrderQty", $hastag)): $item .= '<td>'.$this->main->qty($qty).'</td>'; endif;
    if(in_array("GridProductUnit", $hastag)): $item .= '<td>'.$unit_name.'</td>'; endif;
    if(in_array("GridProductConv", $hastag)): $item .= '<td>'.$conversion.'</td>'; endif;
    if(in_array("GridPriceUnit", $hastag)): $item .= '<td>'.$this->main->currency($v->Price).'</td>'; endif;
    if(in_array("GridDiscountPersent", $hastag)): $item .= '<td>'.$this->main->currency($v->Discount,TRUE).'</td>'; endif;
    if(in_array("GridDiscountValue", $hastag)): $item .= '<td>'.$this->main->currency($v->DiscountValue).'</td>'; endif;
    if(in_array("GridTax", $hastag)): $item .= '<td>'.$this->main->currency($tax).'</td>'; endif;
    if(in_array("GridSubTotal", $hastag)): $item .= '<td>'.$this->main->currency($v->TotalPrice).'</td>'; endif;

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