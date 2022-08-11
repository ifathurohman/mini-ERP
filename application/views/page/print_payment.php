<!DOCTYPE html>
<html>
<head>
    <title><?= $title; ?></title>
    <?php if($cetak): 
      $this->load->view("report/report_css");
    endif;?>
    <style type="text/css">
      .vPeriode{display: none}
      .td-width-150{
        width: 150px;
      }
      .td-width-10{
        width: 10px;
      }
      .w-50{
        width: 50%;
      }
      .t-border tr td{
        border: 1px solid;
        font-size: 14px;
        padding:5px;
      }
      .no-border tr td{
        border: none !important;
      }
      .title-header{
        font-family: 'rockwell';
        font-size: 10pt !important;
        color: #000 !important;
        font-weight: bold !important;
        text-transform: uppercase !important;
      }
    </style>
</head>
<body>
  <div class="page-receipt">
    <div class="header">
    <?php $this->load->view("report/header"); ?>
    </div>
    <div class="content">
      <div style="padding: 10px"><center><span class="title-header">Payment Transaction</span></center></div>
      <table style="width: 100%" class="t-border">
        <tr>
          <td class="w-50">
            <table class="table no-border" data-plugin="dataTable" cellspacing="0">
              <tr><td class="td-width-150">Payment Code</td><td class="td-width-10">:</td><td><?= $list->PaymentNo ?></td></tr>
              <tr><td><?= $list->Typetxt ?></td><td>:</td><td><?= $list->Code ?></td></tr>
              <tr><td>Customer</td><td>:</td><td><?= $this->main->checkvalueprint($list->customerName) ?></td></tr>
              <tr><td>Date</td><td>:</td><td><?= $list->Date ?></td></tr>
              <tr><td>Total Payment</td><td>:</td><td><?= $list->Total ?></td></tr>
            </table>
          </td>
          <td class="w-50">
            <table class="table no-border" data-plugin="dataTable" cellspacing="0">
              <tr><td class="td-width-150">Payment Type</td><td class="td-width-10">:</td><td><?= $list->PaymentTypetxt ?></td></tr>
              <tr><td class="td-width-150">Payment Method</td><td class="td-width-10">:</td><td><?= $list->coaName ?></td></tr>
              <tr><td class="td-width-150"><?= $list->accounttxt ?></td><td class="td-width-10">:</td><td><?= $list->accountnotxt ?></td></tr>
              <tr><td class="td-width-150">Bank Name</td><td class="td-width-10">:</td><td><?= $this->main->checkvalueprint($list->BankName) ?></td></tr>
              <tr><td class="td-width-150">Account Name</td><td class="td-width-10">:</td><td><?= $this->main->checkvalueprint($list->AccountName) ?></td></tr>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </div>
    <div>
    <?php $this->load->view("report/footer"); ?>
    </div>
  </div>
</body>
</html>