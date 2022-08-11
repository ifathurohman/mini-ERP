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
	$content = str_replace("##TransactionCode", $list->PurchaseNo, $content);
	$content = str_replace("##TransactionDate", $list->Date, $content);
	$content = str_replace("##Term", $list->PaymentTerm, $content);
	$content = str_replace("##DueDate", date("Y-m-d", strtotime($list->Date." +".$list->PaymentTerm." Days")), $content);
	$content = str_replace("##SalesName", $list->salesName, $content);
	$content = str_replace("##VendorName", $list->vendorName, $content);
	$content = str_replace("##VendorAddress", $list->vendorAddress, $content);
	$content = str_replace("##VendorContact", $list->vendorPhone, $content);
	$content = str_replace("##Remarks", $list->Remark, $content);
	$content = str_replace("##CompanyDeliveryAddress", $list->DeliveryAddress, $content);
	$content = str_replace("##CompanyDeliveryCity", $list->DeliveryCity, $content);
	$content = str_replace("##CompanyDeliveryProvince", $list->DeliveryProvince, $content);
	$content = str_replace("##CompanyInvoiceAddress", $list->PaymentAddress, $content);
	$content = str_replace("##CompanyInvoiceCity", $list->PaymentCity, $content);
	$content = str_replace("##CompanyInvoiceProvince", $list->PaymentProvince, $content);

	// untuk bentuk uang
	$content = str_replace("##SubTotal", $this->main->currency($list->Total), $content);
	$content = str_replace("##DiscountTotal", $this->main->currency($list->Discount), $content);
	$content = str_replace("##TaxTotal", $this->main->currency($list->TotalPPN), $content);
	$content = str_replace("##DeliveryCost", $this->main->currency($list->DeliveryCost), $content);
	$content = str_replace("##Total", $this->main->currency($list->Payment), $content);

	$item = '';
	foreach ($detail as $k => $v) {
		$no = $k + 1;
        $item  .= '<tr>';
        $sub_total  = 0;
      	$tax        = 0;
      	if($list->Tax == 1):
          $sub_total  = $v->product_qty * $v->product_price;
          $discount   = $sub_total*($v->discount/100);
          $sub_total  = $sub_total - $discount;
          $tax        = $sub_total*(10/100);
          // $tax        = $sub_total + $tax;
        endif;

        $qty = $v->product_qty;
        $unit_name = $v->unit_name;
        $product_conv = $v->product_conv;
        if($list->ProductType == 1):
          $qty = 0;
          $unit_name = 'PCS';
          $product_conv = '1.00';
        endif;

        if(in_array("GridNo", $hastag)): $item .= '<td>'.$no.'</td>'; endif;
        if(in_array("GridProductCode", $hastag)): $item .= '<td>'.$v->product_code.'</td>'; endif;
        if(in_array("GridProductName", $hastag)): $item .= '<td>'.$v->product_name.'</td>'; endif;
        if(in_array("GridOrderQty", $hastag)): $item .= '<td>'.$qty.'</td>'; endif;
        if(in_array("GridProductUnit", $hastag)): $item .= '<td>'.$unit_name.'</td>'; endif;
        if(in_array("GridPriceUnit", $hastag)): $item .= '<td>'.$this->main->currency($v->product_price).'</td>'; endif;
        if(in_array("GridProductConv", $hastag)): $item .= '<td>'.$product_conv.'</td>'; endif;
        if(in_array("GridDiscountPersent", $hastag)): $item .= '<td>'.$this->main->currency($v->discount,TRUE).'</td>'; endif;
        if(in_array("GridDiscountValue", $hastag)): $item .= '<td>'.$this->main->currency($v->discount_value).'</td>'; endif;
        if(in_array("GridTax", $hastag)): $item .= '<td>'.$this->main->currency($tax).'</td>'; endif;
        if(in_array("GridSubTotal", $hastag)): $item .= '<td>'.$this->main->currency($v->product_total).'</td>'; endif;
        if(in_array("GridDeliveryDate", $hastag)): $item .= '<td>'.$v->delivery_date.'</td>'; endif;

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