<!-- <?php
  $payment_typetxt = '';
  if(strlen($list->PaymentType)>0):
    $payment_typetxt .= $this->main->label_payment_type($list->PaymentType);
    if(strlen($list->PaymentType1)>0 || strlen($list->PaymentType2)>0):
      $payment_typetxt .= ', ';
    endif;
  endif;
  if($list->PaymentType1 == 1):
    $payment_typetxt .= $this->main->label_payment_type($list->PaymentType1);
    if(strlen($list->PaymentType2)>0):
      $payment_typetxt .= ', ';
    endif;
  endif;
  if($list->PaymentType2 == 2):
    $payment_typetxt .= $this->main->label_payment_type($list->PaymentType2);
  endif;
?> -->
<!DOCTYPE html>
<html>
<head>
    <title><?= $title; ?></title>
    <?php 
      if($cetak): 
    	 $this->load->view("report/report_css");
      endif;
    ?>
    <style type="text/css">
    	.vPeriode{display: none}
      .td-width-150{
        width: 150px;
      }
      .td-width-10{
        width: 10px;
      }
      .w50{
        width: 50% !important;
      }
      .w33{
        width: 33.33333333333333% !important;
      }
      .title-header{
        font-family: 'rockwell';
        font-size: 10pt !important;
        color: #000 !important;
        font-weight: bold !important;
        text-transform: uppercase !important;
      }
      .mb{
        margin-bottom: 0px !important;
      }
      .vpage{
        margin-top: 20px;
      }
      .vprn-giro, .vprn-card, .vprn-cash{
        display: none;
        margin-top: 20px;
      }
    </style>
    <?php
      if($page == "print" && $list->PaymentType == 0):
        echo 
          '<style>
            .vpage, .page_footer{
              display:none !important;
            }
          </style>';
      endif;
      if(strlen($list->PaymentType)>0):
        echo 
          '<style>
            .vprn-cash{
              display:table-row !important;
            }
          </style>';
      endif;
      if($list->PaymentType1>0):
        echo 
          '<style>
            .vprn-giro{
              display:table-row !important;
            }
          </style>';
      endif;
      if($list->PaymentType2>0):
        echo 
          '<style>
            .vprn-card{
              display:table-row !important;
            }
          </style>';
      endif;
    ?>
</head>
<body>
  <div class="page-receipt">
    <div class="header">
    <?php $this->load->view("report/header"); ?>
    </div>
    <div class="content">
      <div class="vpage" style="padding: 10px"><center><strong><?= $title2 ?></strong></center></div>
      <table class="table width-full" data-plugin="dataTable" cellspacing="0" width="100%">
        <tr>
          <td class="w50">
            <table style="margin-bottom: 0px">
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_transaction_no') ?></td><td class="td-width-10">:</td><td><?= $list->PaymentNo ?></td>
              </tr>
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_payment_date') ?></td><td class="td-width-10">:</td><td><?= date("Y-m-d", strtotime($list->Date)) ?></td>
              </tr>
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_customer_name') ?></td><td class="td-width-10">:</td><td><?= $list->vendorName ?></td>
              </tr>
            </table>
          </td>
          <td class="w50">
            <table style="margin-bottom: 0px">

              <tr class="vprn-cash">
                <td class="td-width-150"><?= $this->lang->line('lb_payment_type') ?></td><td class="td-width-10">:</td><td><?= $this->main->label_payment_type($list->PaymentType); ?></td>
              </tr>
              <tr class="vprn-cash">
                <td class="td-width-150"><?= $this->lang->line('lb_payment_method') ?></td><td class="td-width-10">:</td><td><?= $list->coaCode." - ".$list->coaName ?></td>
              </tr>
              <tr class="vprn-cash">
                <td class="td-width-150"><?= $this->lang->line('lb_pay_cash') ?></td><td class="td-width-10">:</td><td><?= $list->Cash ?></td>
              </tr>
              <tr class="vprn-card">
                <td class="td-width-150"><?= $this->lang->line('lb_payment_type') ?></td><td class="td-width-10">:</td><td><?= $this->main->label_payment_type($list->PaymentType2); ?></td>
              </tr>
              <tr class="vprn-card">
                <td class="td-width-150"><?= $this->lang->line('lb_payment_method') ?></td><td class="td-width-10">:</td><td><?= $list->credit_coaCode." - ".$list->credit_coaName ?></td>
              </tr>
              <tr class="vprn-card">
                <td class="td-width-150"><?= $this->lang->line('lb_bank_acountno') ?></td><td class="td-width-10">:</td><td><?= $list->AcountNo ?></td>
              </tr>
              <tr class="vprn-card">
                <td class="td-width-150"><?= $this->lang->line('lb_bank_name') ?></td><td class="td-width-10">:</td><td><?= $list->BankName ?></td>
              </tr>
              <tr class="vprn-card">
                <td class="td-width-150"><?= $this->lang->line('lb_bank_acount') ?></td><td class="td-width-10">:</td><td><?= $list->AccountName ?></td>
              </tr>
              <tr class="vprn-card">
                <td class="td-width-150"><?= $this->lang->line('lb_pay_transfer') ?></td><td class="td-width-10">:</td><td><?= $list->Credit ?></td>
              </tr>
              <tr class="vprn-giro">
                <td class="td-width-150"><?= $this->lang->line('lb_payment_type') ?></td><td class="td-width-10">:</td><td><?= $this->main->label_payment_type($list->PaymentType1); ?></td>
              </tr>
              <tr class="vprn-giro">
                <td class="td-width-150"><?= $this->lang->line('lb_payment_method') ?></td><td class="td-width-10">:</td><td><?= $list->giro_coaCode." - ".$list->giro_coaName ?></td>
              </tr>
              <tr class="vprn-giro">
                <td class="td-width-150"><?= $this->lang->line('lb_girono') ?></td><td class="td-width-10">:</td><td><?= $list->GiroNo ?></td>
              </tr>
              <tr class="vprn-giro">
                <td class="td-width-150"><?= $this->lang->line('lb_bank_name') ?></td><td class="td-width-10">:</td><td><?= $list->BankName1 ?></td>
              </tr>
              <tr class="vprn-giro">
                <td class="td-width-150"><?= $this->lang->line('lb_bank_acount') ?></td><td class="td-width-10">:</td><td><?= $list->AccountName1 ?></td>
              </tr>
              <tr class="vprn-giro">
                <td class="td-width-150"><?= $this->lang->line('lb_pay_giro') ?></td><td class="td-width-10">:</td><td><?= $list->Giro ?></td>
              </tr>
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_total_payment') ?></td><td class="td-width-10">:</td><td><?= $list->Total ?></td>
              </tr>

            </table>
          </td>
        </tr>
      </table>
      <table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th><?= $this->lang->line('lb_no') ?></th>
            <th><?= $this->lang->line('lb_transaction_no') ?></th>
            <th><?= $this->lang->line('lb_transaction_date') ?></th>
            <th><?= $this->lang->line('lb_balance_type') ?></th>
            <th><?= $this->lang->line('lb_pay_total') ?></th>
            <th><?= $this->lang->line('lb_total_unpaid') ?></th>
            <th><?= $this->lang->line('lb_total_paid') ?></th>
            <th class="vpage"><?= $this->lang->line('lb_remark') ?></th>
          </tr>
        </thead>
        <tbody>
          <?php
          $TotalPay = 0;
          foreach ($detail as $k => $v) {
            $no = $k + 1;
            $TotalPayx    = $v->TotalPay;
            $TotalUnpaidx = $v->TotalUnpaid;
            $Totalx       = $v->Total;

            $item  = '<tr>';
            $item .= '<td>'.$no.'</td>';
            if($v->Type == 1):
              $item .= '<td>'.$v->InvoiceNo.'</td>';
              $item .= '<td>'.$v->invoiceDate.'</td>';
            else:
              $item .= '<td>'.$v->balanceCode.'</td>';
              $item .= '<td>'.$v->balanceDate.'</td>';
            endif;

            if($v->balanceType == 2):
              $TotalPayx    = "-".$TotalPayx;
              $TotalUnpaidx = "-".$TotalUnpaidx;
              $Totalx       = "-".$Totalx;
            endif;
            $TotalPay += $TotalPayx;

            $item .= '<td>'.$this->main->label_balance_type($v->balanceType,"cetak").'</td>';
            $item .= '<td>'.$this->main->currency($TotalPayx).'</td>';
            $item .= '<td>'.$this->main->currency($TotalUnpaidx).'</td>';
            $item .= '<td>'.$this->main->currency($Totalx).'</td>';
            $item .= '<td class="vpage">'.$v->Remark.'</td>';
            $item .= '</tr>';

            echo $item;
          }
          ?>
        </tbody>
      </table>
      <table width="100%" style="margin: 15px">
        <tr>
          <td class="w50">
            <div class="d-ID content-hide"><?= $list->PaymentNo ?></div>
            <div class="div-remark">
              <?= $this->lang->line('lb_remark') ?> : <span class="d-remark"><?= $list->Remark ?></span>
            </div>
            <div class="div-attach"></div>
          </td>
          <td class="w50">
            <table>               
                <tr><td class="td-width-150">Total Pay</td><td class="td-width-10">:</td><td><?= $this->main->currency($TotalPay) ?></td></tr>
                <tr><td><?= $this->lang->line('lb_total_paid') ?></td><td>:</td><td><?= $this->main->currency($list->Total) ?></td></tr>
            </table>
          </td>
        </tr>
      </table>
    </div>
    <div class="page_footer">
    <?php $this->load->view("report/footer"); ?>
    </div>
  </div>
</body>
</html>
<script type="text/javascript">
  arrData = <?= json_encode($data_action) ?>;
  // set_button_action(arrData)
</script>