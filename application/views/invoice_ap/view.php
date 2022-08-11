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
    </style>
    <?php
      if($page == "print"):
        echo 
          '<style>
            .vpage, .page_footer{
              display:none !important;
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
                <td class="td-width-150"><?= $this->lang->line('lb_transaction_no') ?></td><td class="td-width-10">:</td><td><?= $list->InvoiceNo ?></td>
              </tr>
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_invoice_date') ?></td><td class="td-width-10">:</td><td><?= $list->Date ?></td>
              </tr>
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_vendor_name') ?></td><td class="td-width-10">:</td><td><?= $list->vendorName ?></td>
              </tr>
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_npwp') ?></td><td class="td-width-10">:</td><td><?= $list->InvoiceNPWP ?></td>
              </tr>
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_term') ?></td><td class="td-width-10">:</td><td><?= $list->Term ?> (<?= $this->lang->line('lb_days') ?>)</td>
              </tr>
            </table>
          </td>
          <td class="w50">
            <table style="margin-bottom: 0px">
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_address') ?></td><td class="td-width-10">:</td><td><?= $list->address ?></td>
              </tr>
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_city') ?></td><td class="td-width-10">:</td><td><?= $list->city ?></td>
              </tr>
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_province') ?></td><td class="td-width-10">:</td><td><?= $list->province ?></td>
              </tr>
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_phone') ?></td><td class="td-width-10">:</td><td><?= $list->vendorPhone ?></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>No</th>
            <th class="vpage"><?= $this->lang->line('lb_transaction_no') ?></th>
            <th><?= $this->lang->line('lb_transaction') ?></th>
            <th><?= $this->lang->line('lb_purchaseno') ?></th>
            <th><?= $this->lang->line('lb_date') ?></th>
            <th><?= $this->lang->line('lb_sub_total') ?></th>
            <th><?= $this->lang->line('lb_discount') ?></th>
            <th><?= $this->lang->line('lb_tax') ?></th>
            <th><?= $this->lang->line('lb_delivery_cost') ?></th>
            <th><?= $this->lang->line('lb_total') ?></th>
            <th class="vpage"><?= $this->lang->line('lb_remark') ?></th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($detail as $k => $v) {
            $no = $k + 1;
            $item  = '<tr>';
            $item .= '<td>'.$no.'</td>';
            if($v->invoiceType == "return"):
              $item .= '<td class="vpage">'.$v->ReturNo.'</td>';
            else:
              $item .= '<td class="vpage">'.$v->ReceiveNo.'</td>';
            endif;
            $item .= '<td>'.$v->invoiceTypetxt.'</td>';
            $item .= '<td>'.$v->PurchaseNo.'</td>';
            $item .= '<td>'.$v->Date.'</td>';
            $item .= '<td>'.$this->main->currency($v->SubTotal).'</td>';
            $item .= '<td>'.$this->main->currency($v->Discount).'</td>';
            $item .= '<td>'.$this->main->currency($v->PPN).'</td>';
            $item .= '<td>'.$this->main->currency($v->DeliveryCost).'</td>';
            $item .= '<td>'.$this->main->currency($v->Total).'</td>';
            $item .= '<td class="vpage">'.$v->Remark.'</td>';
            $item .= '</tr>';

            echo $item;
          }
          ?>
        </tbody>
      </table>
      <table class="table width-full" data-plugin="dataTable" cellspacing="0" width="100%">
        <tr>
          <td class="w50">
            <div class="d-ID content-hide"><?= $list->InvoiceNo ?></div>
              <div class="div-remark">
                <?= $this->lang->line('lb_remark') ?> : <span class="d-remark"><?= $list->Remark ?></span>
              </div>
              <div class="div-attach"></div>
          </td>
          <td class="w50"><?= $this->lang->line('lb_grand_total') ?> : <?= $this->main->currency($list->Total) ?></td>
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