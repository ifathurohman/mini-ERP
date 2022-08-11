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
                <td class="td-width-150"><?= $this->lang->line('lb_sales_name') ?></td><td class="td-width-10">:</td><td><?= $list->salesName ?></td>
              </tr>
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_store') ?></td><td class="td-width-10">:</td><td><?= $list->branchName ?></td>
              </tr>
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_vendor_name') ?></td><td class="td-width-10">:</td><td><?= $list->vendorName ?></td>
              </tr>
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_phone') ?></td><td class="td-width-10">:</td><td><?= $list->vendorPhone ?></td>
              </tr>
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_address') ?></td><td class="td-width-10">:</td><td><?= $list->address ?></td>
              </tr>
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_city') ?></td><td class="td-width-10">:</td><td><?= $list->city ?></td>
              </tr>
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_province') ?></td><td class="td-width-10">:</td><td><?= $list->province ?></td>
              </tr>
            </table>
          </td>
           <td class="w50">
            <table style="margin-bottom: 0px">
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_transaction_no') ?></td><td class="td-width-10">:</td><td><?= $list->ReturNo ?></td>
              </tr>
              <tr>
                <td class="td-width-150"><?= $this->lang->line('lb_date') ?></td><td class="td-width-10">:</td><td><?= $list->Date ?></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>No</th>
            <th><?= $this->lang->line('lb_goodrcno') ?></th>
            <th><?= $this->lang->line('lb_goodrc_date') ?></th>
            <th class="vpage"><?= $this->lang->line('lb_product_code') ?></th>
            <th><?= $this->lang->line('lb_product_name') ?></th>
          <!--   <th>Receive Qty</th> -->
            <th><?= $this->lang->line('lb_return_qty') ?></th>
            <th><?= $this->lang->line('lb_unit') ?></th>
            <th class="content-hide">Conversion</th>
            <th class="vpage"><?= $this->lang->line('price') ?></th>
            <th class="vpage"><?= $this->lang->line('lb_discount') ?></th>
            <th class="vpage"><?= $this->lang->line('lb_tax') ?></th>
            <th class="vpage"><?= $this->lang->line('lb_sub_total') ?></th>
            <th class="vpage"><?= $this->lang->line('lb_remark') ?></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($detail as $k => $v) {
            $sub_total  = $v->Qty * $v->Price;
            $discount   = $this->main->PersenttoRp($sub_total,$v->transactionDiscount);
            $sub_total  = $sub_total - $discount;

            if($list->transactionTax == 1):
              $tax        = $this->main->PersenttoRp($sub_total,10);
            else:
              $tax        = 0;
            endif;

            $btn_serial = '';
            if($v->product_type == 2):
              $btn_serial = '<a href="javascript:;" onclick="view_serial_number('."'retur','".$v->ReturNo."','".$v->ReturDet."'".')" class="btn-serial-v" aria-hidden="true" style="cursor:pointer;padding:5px;">'.$this->lang->line('btn_view_serial').'</a>';
            endif;

            $no    = $k + 1;
            $item  = '<tr>';
            $item .= '<td>'.$no.'</td>';
            $item .= '<td>'.$v->transactionCode.'</td>';
            $item .= '<td>'.date("Y-m-d", strtotime($list->transactionDate)).'</td>';
            $item .= '<td class="vpage">'.$v->product_code.'</td>';
            $item .= '<td>'.$v->product_name.'</td>';
            // $item .= '<td>'.$v->transactionQty.'</td>';
            $item .= '<td>'.$this->main->qty($v->Qty).'</td>';
            $item .= '<td>'.$v->unit_name.'</td>';
            $item .= '<td class="content-hide">'.$v->Conversion.'</td>';
            $item .= '<td class="vpage">'.$this->main->currency($v->Price).'</td>';
            $item .= '<td class="vpage">'.$this->main->currency($discount).'</td>';
            $item .= '<td class="vpage">'.$this->main->currency($tax).'</td>';
            $item .= '<td class="vpage">'.$this->main->currency($v->Total).'</td>';
            $item .= '<td class="vpage">'.$v->Remark.'</td>';
            if(!$cetak):
              $item .= '<td>'.$btn_serial.'</td>';
            endif;
            $item .= '</tr>';

            echo $item;
          }
          ?>
        </tbody>
      </table>
      <table class="table width-full" data-plugin="dataTable" cellspacing="0" width="100%">
        <tr>
          <td class="w50">
            <div class="d-ID content-hide"><?= $list->ReturNo ?></div>
              <div class="div-remark">
                <?= $this->lang->line('lb_remark') ?> : <span class="d-remark"><?= $list->Remark ?></span>
              </div>
              <div class="div-attach"></div>
          </td>
          <td class="w50"></td>
        </tr>
      </table>
      <!-- <table class="table width-full" data-plugin="dataTable" cellspacing="0" width="100%">
        <tr>
          <td class="w50 text-center">
            Receiver,
            <div style="height: 100px"></div>
            _________________________
          </td>
          <td class="w50 text-center">
            Our Respect,
            <div style="height: 100px"></div>
            _________________________
          </td>
        </tr>
      </table> -->
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