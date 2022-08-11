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
      .text-right{
        text-align: right;
      }
    </style>
</head>
<body>
  <div class="page-receipt">
    <div class="header">
    <?php 
      $this->load->view("report/header"); 
    ?>
    </div>
    <div class="content">
      <div style="padding: 10px"><center><span class="title-header"><?= $title2 ?></span></center></div>
        <table class="table" data-plugin="dataTable" cellspacing="0">
          <tr>
            <td class="td-width-150"><?= $this->lang->line('lb_deliveryno') ?></td><td class="td-width-10">:</td><td><?= $list->DeliveryNo ?></td>
          </tr>
          <tr>
            <td><?= $this->lang->line('lb_date') ?></td><td>:</td><td><?= $list->Date ?></td>
          </tr>
          <tr>
            <td><?= $this->lang->line('lb_customer_name') ?></td><td>:</td><td><?= $list->vendorName ?></td>
          </tr>
          <tr>
            <td><?= $this->lang->line('lb_sales_name') ?></td><td>:</td><td><?= $list->salesName ?></td>
          </tr>
          <?php
            if($list->Type == 2):
              echo '<tr>
                <td>'.$this->lang->line('lb_store').'</td><td>:</td><td>'.$list->branchName.'</td>
              </tr>';
            endif;
          ?>
          <tr>
            <td><?= $this->lang->line('lb_delivery_to') ?>A</td><td>:</td><td><?= $list->DeliveryTo ?></td>
          </tr>
          <tr>
            <td><?= $this->lang->line('lb_delivery_address') ?></td><td>:</td><td><?= $list->Address ?></td>
          </tr>
          <tr>
            <td><?= $this->lang->line('lb_delivery_city') ?></td><td>:</td><td><?= $list->City ?></td>
          </tr>
          <tr>
            <td><?= $this->lang->line('lb_delivery_province') ?></td><td>:</td><td><?= $list->Province ?></td>
          </tr>
          <tr>
            <td><?= $this->lang->line('lb_delivery_cost') ?></td><td>:</td><td><?= $this->main->currency($list->DeliveryCost) ?></td>
          </tr>
        </table>
        <table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>No</th>
              <th><?= $this->lang->line('lb_sellingno') ?></th>
              <th><?= $this->lang->line('lb_selling_date') ?></th>
              <?php
              if($list->Type == 1):
                echo '<th>'.$this->lang->line('lb_store').'</th>';
              endif;
              ?>
              <th><?= $this->lang->line('lb_product_code') ?></th>
              <th><?= $this->lang->line('lb_product_name') ?></th>
              <th><?= $this->lang->line('lb_qty_order') ?></th>
              <th><?= $this->lang->line('lb_deliveryqty') ?></th>
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
              foreach ($detail as $k => $v) {
                $btn_serial = '';
                // if($list->Type == 1 and $list->ProductType == 0):
                //   $btn_serial = '<a  onclick="add_serial2('."'delivery','".$v->DeliveryDet."'".')" class="btn-serial-v" aria-hidden="true" style="cursor:pointer;padding:5px;">Add Serial</a>';
                // elseif($list->Type != 1 and $list->ProductType == 0):
                //   $btn_serial = '<a  onclick="add_serial('."'delivery_non_order','".$v->DeliveryDet."'".')" class="btn-serial-v" aria-hidden="true" style="cursor:pointer;padding:5px;">Add Serial</a>';
                // endif;
                if($v->Type == 2):
                  $btn_serial = '<a href="javascript:;" onclick="view_serial_number('."'delivery','".$v->DeliveryNo."','".$v->DeliveryDet."'".')" class="btn-serial-v" aria-hidden="true" style="cursor:pointer;padding:5px;">View Serial</a>';
                endif;
                $tax = 0;
                if($list->Tax == 1):
                  $sub_total  = $v->Qty * $v->Price;
                  $discount   = $sub_total*($v->Discount/100);
                  $sub_total  = $sub_total - $discount;
                  $tax        = $sub_total*(10/100);
                  $tax        = $tax;
                endif;
                $sell_qty     = $v->sellQty;
                $qty          = $v->Qty;
                $unit_name    = $v->unitName;
                if($list->ProductType == 1):
                  $sell_qty     = '';
                  $qty          = 0;
                  $unit_name    = "PCS";
                endif;

                $no = $k + 1;
                $item  = '<tr>';
                $item .= '<td>'.$no.'</td>';
                $item .= '<td>'.$v->SellNo.'</td>';
                $item .= '<td>'.$v->sellDate.'</td>';
                if($list->Type == 1):
                  $item .= '<td>'.$v->branchName.'</td>';
                endif;
                $item .= '<td>'.$v->productCode.'</td>';
                $item .= '<td>'.$v->productName.'</td>';
                $item .= '<td>'.$this->main->qty($sell_qty).'</td>';
                $item .= '<td>'.$this->main->qty($qty).'</td>';
                $item .= '<td>'.$unit_name.'</td>';
                $item .= '<td class="content-hide">'.$v->Conversion.'</td>';
                $item .= '<td>'.$this->main->currency($v->Price).'</td>';
                $item .= '<td>'.$this->main->currency($v->Discount,TRUE).'</td>';
                $item .= '<td>'.$this->main->currency($tax).'</td>';
                $item .= '<td>'.$this->main->currency($v->TotalPrice).'</td>';
                $item .= '<td>'.$v->Remark.'</td>';
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
              <div class="d-ID content-hide"><?= $list->DeliveryNo ?></div>
              <div class="div-remark">
                <?= $this->lang->line('lb_remark') ?> : <span class="d-remark"><?= $list->Remark ?></span>
              </div>
              <div class="div-attach"></div>    
            </td>
            <td class="w50">
              <table>               
                  <tr><td class="td-width-150"><?= $this->lang->line('lb_sub_total') ?></td><td class="td-width-10">:</td><td class="text-right"><?= $this->main->currency($list->Total) ?></td></tr>
                  <tr><td><?= $this->lang->line('lb_discount_total') ?></td><td>:</td><td class="text-right"><?= $this->main->currency($list->Discount) ?></td></tr>
                  <tr><td><?= $this->lang->line('lb_tax') ?></td><td>:</td><td class="text-right"><?= $this->main->currency($list->TotalPPN) ?></td></tr>
                  <tr><td><?= $this->lang->line('lb_delivery_cost') ?></td><td>:</td><td class="text-right"><?= $this->main->currency($list->DeliveryCost) ?></td></tr>
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