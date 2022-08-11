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

  $content = str_replace("##TransactionType", $list->OrderTypetxt, $content);
  $content = str_replace("##TransactionCode", $list->balanceno, $content);
  $content = str_replace("##TransactionDate", $list->date, $content);
  $content = str_replace("##CorrectionType", $list->BalanceTypetxt, $content);
  $content = str_replace("##Remark", $list->Remark, $content);

  $item = '';
  foreach ($detail as $k => $v) {
    $no = $k + 1;
    $item  .= '<tr>';

    if($list->OrderType == 1):
      $name = $v->branchname;
    else:
      $name = $v->vendorName;
    endif;

    if(in_array("GridNo", $hastag)): $item .= '<td>'.$no.'</td>'; endif;
    if(in_array("GridCustomerName", $hastag)): $item .= '<td>'.$name.'</td>'; endif;
    if(in_array("GridTotal", $hastag)): $item .= '<td>'.$this->main->currency($v->totalcorrection).'</td>'; endif;
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