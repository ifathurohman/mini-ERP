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
      <div style="padding: 10px"><center><strong><?= $title2 ?></strong></center></div>
      <table>
        <tr>
          <td><?= $this->lang->line('lb_transaction_no') ?></td><td width="20px" class="text-center"> : </td><td><?= $list->KasBankNo ?></td>
        </tr>
        <tr>
          <td><?= $this->lang->line('lb_date') ?></td><td class="text-center"> : </td><td><?= $list->Date ?></td>
        </tr>
        <tr>
          <td><?= $this->lang->line('lb_type') ?></td><td class="text-center"> : </td><td><?= $list->Typetxt ?></td>
        </tr>
      </table>
      <table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th><?= $this->lang->line('lb_no') ?></th>
            <th><?= $this->lang->line('lb_coa_code') ?></th>
            <th><?= $this->lang->line('lb_coa_name') ?></th>
            <th class="vpage"><?= $this->lang->line('lb_remark') ?></th>
            <th><?= $this->lang->line('lb_debit') ?></th>
            <th><?= $this->lang->line('lb_credit') ?></th>
          </tr>
        </thead>
        <tbody>
          <?php
            foreach ($detail as $k => $v) {
              $no = $k + 1;

              $item = '<tr>';
              $item .= '<td>'.$no.'</td>';
              $item .= '<td>'.$v->coaCode.'</td>';
              $item .= '<td>'.$v->coaName.'</td>';
              $item .= '<td class="vpage">'.$v->Remark.'</td>';
              $item .= '<td>'.$this->main->currency($v->Debit).'</td>';
              $item .= '<td>'.$this->main->currency($v->Credit).'</td>';
              $item .= '</tr>';

              echo $item;
            }
          ?>
        </tbody>
      </table>
      <table width="100%" style="margin: 15px">
        <tr>
          <td class="w50">
            <table>               
                <tr><td class="td-width-150 "><?= $this->lang->line('lb_remark') ?></td><td class="td-width-10">:</td><td><?= $list->Remark ?></td></tr>
            </table>
          </td>
          <td class="w50">
            <table>               
                <tr><td class="td-width-150"><?= $this->lang->line('lb_total_debit') ?></td><td class="td-width-10">:</td><td><?= $this->main->currency($list->DebitTotal) ?></td></tr>
                <tr><td><?= $this->lang->line('lb_total_credit') ?></td><td>:</td><td><?= $this->main->currency($list->CreditTotal) ?></td></tr>
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
  set_button_action(arrData)
</script>