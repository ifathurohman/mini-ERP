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
    <?php $this->load->view("report/header"); ?>
    </div>
    <div class="content">
      <div class="vpage" style="padding: 10px"><center><strong><?= $title2 ?></strong></center></div>
      <table>
        <tr>
          <td><?= $this->lang->line('lb_transaction_type') ?></td><td width="20px" class="text-center"> : </td><td><?= $list->OrderTypetxt ?></td>
        </tr>
        <tr>
          <td><?= $this->lang->line('lb_transaction_no') ?></td><td class="text-center"> : </td><td><?= $list->balanceno ?></td>
        </tr>
        <tr>
          <td><?= $this->lang->line('lb_date') ?></td><td class="text-center"> : </td><td><?= $list->date ?></td>
        </tr>
        <tr>
          <td><?= $this->lang->line('lb_correction_type') ?></td><td class="text-center"> : </td><td><?= $list->BalanceTypetxt ?></td>
        </tr>
      </table>
      <table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th><?= $this->lang->line('lb_no') ?></th>
            <th><?= $this->lang->line('lb_vendor_name') ?></th>
            <th><?= $this->lang->line('lb_correction_total') ?></th>
            <th><?= $this->lang->line('lb_remark') ?></th>
          </tr>
        </thead>
        <tbody>
          <?php
            foreach ($detail as $k => $v) {
              $no = $k + 1;
              $item  = '<tr>';
              $item .= '<td>'.$no.'</td>';
              if($list->OrderType == 1):
                $item .= '<td>'.$v->branchname.'</td>';
              else:
                $item .= '<td>'.$v->vendorName.'</td>';
              endif;
              $item .= '<td>'.$this->main->currency($v->totalcorrection).'</td>';
              $item .= '<td class="vpage">'.$v->Remark.'</td>';
              $item .= '</tr>';

              echo $item;
            }
          ?>
        </tbody>
      </table>
      <table width="100%">
        <tr>
          <td class="w50">
            <div class="d-ID content-hide"><?= $list->BalanceID ?></div>
            <div class="div-remark">
              <?= $this->lang->line('lb_remark') ?> : <span class="d-remark"><?= $list->Remark ?></span>
            </div>
            <div class="div-attach"></div>
          </td>
          <td class="w50"></td>
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