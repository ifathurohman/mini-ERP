<!DOCTYPE html>
<html>
<head>
    <title><?= $title; ?></title>
    <?php 
      if($cetak): 
       $this->load->view("report/report_css");
      endif;
    ?>
    <?php
    // echo "<pre>";
    // echo print_r($list);
    // echo "</pre>";
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
      .text-right{
        text-align: right;
      }
    </style>
</head>
<body>
  <div class="page-receipt">
    <div class="header">
    <?php $this->load->view("report/header"); ?>
    </div>
    <div class="content">
      <div style="padding: 10px"><center><strong><?= $title2 ?></strong></center></div>
        <table class="table" data-plugin="dataTable" cellspacing="0">
          <tr>
            <td class="td-width-150"><?= $this->lang->line('lb_transaction_no') ?></td><td class="td-width-10">:</td><td><?= $list->receipt_no ?></td>
          </tr>
          <tr>
            <td><?= $this->lang->line('lb_date') ?></td><td>:</td><td><?= $list->receipt_date ?></td>
          </tr>
          <tr>
            <td><?= $this->lang->line('lb_vendor') ?></td><td>:</td><td><?= $list->receipt_name ?></td>
          </tr>
          <tr>
            <td><?= $this->lang->line('lb_sales_name') ?></td><td>:</td><td><?= $list->salesName ?></td>
          </tr>
          <tr>
            <td><?= $this->lang->line('lb_store') ?></td><td>:</td><td><?= $list->branchName ?></td>
          </tr>
          <tr>
            <td><?= $this->lang->line('lb_delivery_order') ?>  </td><td>:</td><td><?= $list->sj_no ?></td>
          </tr>
          <tr>
            <td><?= $this->lang->line('lb_delivery_cost') ?></td><td>:</td><td><?= $this->main->currency($list->receipt_cost) ?></td>
          </tr>
        </table>
        <table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th><?= $this->lang->line('lb_no') ?></th>
              <th><?= $this->lang->line('lb_purchaseno') ?></th>
              <th><?= $this->lang->line('lb_purchase_date') ?></th>
              <th><?= $this->lang->line('lb_product_code') ?></th>
              <th><?= $this->lang->line('lb_name') ?></th>
              <th><?= $this->lang->line('lb_qty_order') ?></th>
              <th><?= $this->lang->line('lb_qty_hand') ?></th>
              <th><?= $this->lang->line('lb_unit') ?></th>
              <th class="content-hide">Conv</th>
              <th><?= $this->lang->line('price') ?></th>
              <th><?= $this->lang->line('lb_discount') ?></th>
              <th><?= $this->lang->line('lb_tax') ?></th>
              <th><?= $this->lang->line('lb_sub_total') ?></th>
              <th><?= $this->lang->line('lb_remark') ?></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
              $sub_total  = 0;
              $tax        = 0;
              $page2 = $this->input->get('page2');
              foreach ($detail as $k => $v) {
                $btn_serial = '';
                if($v->product_type == "serial"):
                  // $btn_serial  = '<a  onclick="add_serial('."'penerimaan','".$v->receipt_det."'".')" class="btn-serial-v" aria-hidden="true" style="cursor:pointer;padding:5px;">Add Serial</a>';
                  // if($page2 == "serial"):
                  //   $btn_serial  = '<a  onclick="add_serial('."'penerimaan_view','".$v->receipt_det."'".')" class="btn-serial-v" aria-hidden="true" style="cursor:pointer;padding:5px;">Show Serial</a>';
                  // endif;
                  $btn_serial = '<a href="javascript:;" onclick="view_serial_number('."'penerimaan','".$v->receipt_no."','".$v->receipt_det."'".')" class="btn-serial-v" aria-hidden="true" style="cursor:pointer;padding:5px;">View Serial</a>';
                endif;
                if($list->Tax == 1):
                  $sub_total  = $v->receive_qty * $v->receipt_price;
                  $discount   = $sub_total*($v->receipt_discount/100);
                  $sub_total  = $sub_total - $discount;
                  $tax        = $sub_total*(10/100);
                  // $tax        = $sub_total + $tax;
                endif;
                $no = $k + 1;
                $item  = '<tr>';
                $item .= '<td>'.$no.'</td>';
                $item .= '<td>'.$v->Purchase_purchaseno.'</td>';
                $item .= '<td>'.$v->Purchase_date.'</td>';
                $item .= '<td>'.$v->product_code.'</td>';
                $item .= '<td>'.$v->product_name.'</td>';
                $item .= '<td>'.$this->main->qty($v->purchaseQty,TRUE).'</td>';
                $item .= '<td>'.$this->main->qty($v->receive_qty).'</td>';
                $item .= '<td>'.$v->unit_name.'</td>';
                $item .= '<td class="content-hide">'.$v->receipt_konv.'</td>';
                $item .= '<td>'.$this->main->currency($v->receipt_price).'</td>';
                $item .= '<td>'.$this->main->currency($v->receipt_discount,TRUE).'</td>';
                $item .= '<td>'.$this->main->currency($tax).'</td>';
                $item .= '<td>'.$this->main->currency($v->receipt_subtotal).'</td>';
                $item .= '<td>'.$v->receipt_remark.'</td>';
                if(!$cetak):
                  $item .= '<td>'.$btn_serial.'</td>';
                endif;
                $item .= '</tr>';
                echo $item;
              }
            ?>
          </tbody>
        </table>
        <table width="100%">
          <tr>
            <td class="w50">
              <div class="d-ID content-hide"><?= $list->receipt_no ?></div>
              <div class="div-remark">
                <?= $this->lang->line('lb_remark') ?> : <span class="d-remark"><?= $list->receipt_remark ?></span>
              </div>
              <div class="div-attach"></div>
            </td>
            <td class="w50">
              <table>               
                  <tr><td class="td-width-150"><?= $this->lang->line('lb_sub_total') ?></td><td class="td-width-10">:</td><td class="text-right"><?= $list->Total ?></td></tr>
                  <tr><td><?= $this->lang->line('lb_discount_total') ?></td><td>:</td><td class="text-right"><?= $this->main->currency($list->receipt_discount) ?></td></tr>
                  <tr><td><?= $this->lang->line('lb_tax') ?></td><td>:</td><td class="text-right"><?= $this->main->currency($list->TotalPPN) ?></td></tr>
                  <tr><td><?= $this->lang->line('lb_delivery_cost') ?></td><td>:</td><td class="text-right"><?= $this->main->currency($list->receipt_cost) ?></td></tr>
                  <tr><td><?= $this->lang->line('lb_total') ?> </td><td>:</td><td class="text-right"><?= $this->main->currency($list->Payment) ?></td></tr>
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