<?php
    // echo "<pre>";
    // echo print_r($list);
    // echo "</pre>";
?>
<table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
 <thead>
<?php if($this->input->post("group") == "all"): ?>
   <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_customer_name') ?></th>
    <th><?= $this->lang->line('lb_saldo') ?></th>
  </tr>
<?php elseif($this->input->post("group") == "transaction"): ?>
   <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_customer_name') ?></th>
    <th><?= $this->lang->line('lb_transaction_no') ?></th>
    <th><?= $this->lang->line('lb_transaction_date') ?></th>
    <th><?= $this->lang->line('lb_term') ?> (<?= $this->lang->line('lb_days') ?>)</th>
    <th><?= $this->lang->line('lb_due_date') ?></th>
    <th><?= $this->lang->line('lb_total_nota') ?></th>
    <th>0-30 <?= $this->lang->line('lb_days') ?></th>
    <th>31-60 <?= $this->lang->line('lb_days') ?></th>
    <th>60-90 <?= $this->lang->line('lb_days') ?></th>
    <th>>90 <?= $this->lang->line('lb_days') ?></th>
  </tr>
<?php endif;?>
</thead>
<tbody>
<?php
$list          = $this->report->select_age_off_debt();
$Total         = 0;
$total_term    = 0;
$total_nota    = 0;
$total_max30   = 0;
$total_max60   = 0;
$total_max90   = 0;
$total_max     = 0;
if($group == "all"):
    $Total        = 0;
    foreach ($list as $a):
        $no++;
        $tr  = "<tr>";
        $tr .= "<td>".$i++."</td>";
        $tr .= "<td>".$a->vendorName."</td>";
        $tr .= '<td>'.$this->main->currency($a->Unpaid)."</td>";
        $tr .= "</tr>";

        $Total += $a->Unpaid;
        echo $tr;
    endforeach;

elseif($group == "transaction"):
    foreach ($list as $a):
        $max30  = 0;
        $max60  = 0;
        $max90  = 0;
        $max    = 0;

        $due_date = date("Y-m-d", strtotime($a->transactionDate." +".$a->Term." Days"));
        $selisih = 0;
        if($due_date<date("Y-m-d")):
            $selisih  = $this->main->selisih_hari($due_date,date("Y-m-d"));
        endif;

        if($selisih<=30):
            $max30  = $a->Unpaid;
        elseif($selisih<=60):
            $max60  = $a->Unpaid;
        elseif($selisih<=90):
            $max90  = $a->Unpaid;
        else:
            $max    = $a->Unpaid;
        endif;

        $total_term     += $a->Term;
        $total_max30    += $max30;
        $total_max60    += $max60;
        $total_max90    += $max90;
        $total_max      += $max;
        $total_nota     += $a->Unpaid;

        $tr  = "<tr>";
        $tr .= "<td>".$i++."</td>";
        $tr .= "<td>".$a->vendorName."</td>";
        $tr .= "<td>".$a->transactionCode."</td>";
        $tr .= "<td>".$a->transactionDate."</td>";
        $tr .= "<td>".(float) $a->Term."</td>";
        $tr .= "<td>".date("Y-m-d", strtotime($a->transactionDate." +".$a->Term." Days"))."</td>";
        $tr .= "<td>".$this->main->currency($a->Unpaid)."</td>";
        $tr .= "<td>".$this->main->currency($max30)."</td>";
        $tr .= "<td>".$this->main->currency($max60)."</td>";
        $tr .= "<td>".$this->main->currency($max90)."</td>";
        $tr .= "<td>".$this->main->currency($max)."</td>";
        $tr .= "</tr>";

        echo $tr;
    endforeach;
endif;
?>
</tbody>
<?php if($this->input->post("group") == "all"): ?>
    <tfoot>
        <tr>
            <th colspan="2"><?= $this->lang->line('lb_total') ?></th>
            <th><?= $this->main->currency($Total) ?></th>
        </tr>
    </tfoot>
<?php else: ?>
    <tfoot>
        <tr>
            <th colspan="4"><?= $this->lang->line('lb_total') ?></th>
            <th><?= $total_term ?></th>
            <th></th>
            <th><?= $this->main->currency($total_nota) ?></th>
            <th><?= $this->main->currency($total_max30) ?></th>
            <th><?= $this->main->currency($total_max60) ?></th>
            <th><?= $this->main->currency($total_max90) ?></th>
            <th><?= $this->main->currency($total_max) ?></th>
        </tr>
    </tfoot>
<?php endif; ?>
</tbody>
</table>