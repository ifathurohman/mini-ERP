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
      <div class="vpage" style="padding: 10px"><center><span class="title-header"><?= $title2 ?></span></center></div>
      <table class="table width-full" data-plugin="dataTable" cellspacing="0" width="100%">
        <tr>
          <td class="w50">
            <table style="margin-bottom: 0px">
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_transaction_no') ?></td><td class="td-width-10">:</td><td><?= $list->PurchaseNo ?></td>
              </tr>
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_date') ?></td><td class="td-width-10">:</td><td><?= $list->Date ?></td>
              </tr>
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_vendor_name') ?></td><td class="td-width-10">:</td><td><?= $list->vendorName ?></td>
              </tr>
              <tr class="vpage">
                <td class="td-width-150"><?= $this->lang->line('lb_sales_name') ?></td><td class="td-width-10">:</td><td><?= $list->salesName ?></td>
              </tr>
              <tr class="vpage">
                <td class="td-width-150"><?= $this->lang->line('lb_delivery_address') ?></td><td class="td-width-10">:</td><td><?= $list->DeliveryAddress ?></td>
              </tr>
              <tr class="vpage">
                <td class="td-width-150"><?= $this->lang->line('lb_delivery_city') ?></td><td class="td-width-10">:</td><td><?= $list->DeliveryCity ?></td>
              </tr>
              <tr class="vpage">
                <td class="td-width-150"><?= $this->lang->line('lb_delivery_province') ?></td><td class="td-width-10">:</td><td><?= $list->DeliveryProvince ?></td>
              </tr>
              <tr class="vpage">
                <td class="td-width-150"><?= $this->lang->line('lb_delivery_cost') ?></td><td class="td-width-10">:</td><td><?= $list->DeliveryCost ?></td>
              </tr>
            </table>
          </td>
          <td class="w50">
            <table style="margin-bottom: 0px">
              <tr class="vpage">
                <td class="td-width-150"><?= $this->lang->line('lb_payment_to') ?></td><td class="td-width-10">:</td><td><?= $list->PaymentTo ?></td>
              </tr>
              <tr class="vpage">
                <td class="td-width-150"><?= $this->lang->line('lb_payment_address') ?></td><td class="td-width-10">:</td><td><?= $list->PaymentAddress ?></td>
              </tr>
              <tr class="vpage">
                <td class="td-width-150"><?= $this->lang->line('lb_payment_city') ?></td><td class="td-width-10">:</td><td><?= $list->PaymentCity ?></td>
              </tr>
              <tr class="vpage">
                <td class="td-width-150"><?= $this->lang->line('lb_payment_province') ?></td><td class="td-width-10">:</td><td><?= $list->PaymentProvince ?></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th><?= $this->lang->line('lb_no') ?></th>
            <th><?= $this->lang->line('lb_product_code') ?></th>
            <th><?= $this->lang->line('lb_name') ?></th>
            <th><?= $this->lang->line('lb_qty') ?></th>
            <th><?= $this->lang->line('lb_unit') ?></th>
            <th class="content-hide">Conv</th>
            <th><?= $this->lang->line('price') ?></th>
            <th><?= $this->lang->line('lb_discount') ?></th>
            <th><?= $this->lang->line('lb_discount_value') ?></th>
            <th><?= $this->lang->line('lb_sub_total') ?></th>
            <th class="vpage"><?= $this->lang->line('lb_remark') ?></th>
            <th class="vpage"><?= $this->lang->line('lb_delivery_date') ?></th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($detail as $k => $v) {
            $no = $k + 1;

            $qty = $v->product_qty;
            $unit_name = $v->unit_name;
            if($list->ProductType == 1):
              $qty = 0;
              $unit_name = '';
            endif;

            $item  = '<tr>';
            $item .= '<td>'.$no.'</td>';
            $item .= '<td>'.$v->product_code.'</td>';
            $item .= '<td>'.$v->product_name.'</td>';
            $item .= '<td>'.$qty.'</td>';
            $item .= '<td>'.$unit_name.'</td>';
            $item .= '<td class="content-hide">'.$v->product_conv.'</td>';
            $item .= '<td>'.$v->product_price.'</td>';
            $item .= '<td>'.$v->discount.'</td>';
            $item .= '<td>'.$v->discount_value.'</td>';
            $item .= '<td>'.$v->product_total.'</td>';
            $item .= '<td class="vpage">'.$v->remark.'</td>';
            $item .= '<td class="vpage">'.$v->delivery_date.'</td>';
            $item .= '</tr>';

            echo $item;
          }
          ?>
        </tbody>
      </table>
      <table class="table width-full" data-plugin="dataTable" cellspacing="0" width="100%">
        <tr>
          <td class="w50">
            <div class="vpage"><?= $this->lang->line('lb_remark') ?> : <?= $list->Remark ?></div>
          </td>
          <td class="w50">
            <table style="margin-bottom: 0px">
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_sub_total') ?></td><td class="td-width-10">:</td><td><?= $list->Total ?></td>
              </tr>
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_discount') ?></td><td class="td-width-10">:</td><td><?= $list->Discount ?></td>
              </tr>
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_tax') ?></td><td class="td-width-10">:</td><td><?= $list->TotalPPN ?></td>
              </tr>
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_total') ?></td><td class="td-width-10">:</td><td><?= $list->Payment ?></td>
              </tr>
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