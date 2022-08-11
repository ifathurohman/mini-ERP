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
    <th><?= $this->lang->line('lb_correction_no') ?></th>
    <th><?= $this->lang->line('lb_vendor_name') ?></th>
    <th><?= $this->lang->line('lb_date') ?></th>
    <th><?= $this->lang->line('lb_total_payment') ?></th>
    <th><?= $this->lang->line('lb_correction_total') ?></th>
    <th><?= $this->lang->line('lb_total') ?></th>
  </tr>
<?php elseif($this->input->post("group") == "transaction"): ?>
   <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_correction_no') ?></th>
    <th><?= $this->lang->line('lb_vendor_name') ?></th>
    <th><?= $this->lang->line('lb_date') ?></th>
    <th><?= $this->lang->line('lb_total_payment') ?></th>
    <th><?= $this->lang->line('lb_correction_total') ?></th>
    <th><?= $this->lang->line('lb_total') ?></th>
  </tr>
<?php elseif($this->input->post("group") == "vendor"): ?>
   <tr>
     <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_vendor_name') ?></th>
    <th><?= $this->lang->line('lb_correction_total') ?></th>
  </tr>
<?php endif;?>
</thead>
<tbody>
<?php 
$total1  = 0;
$total2  = 0;
$total3  = 0;
if($this->input->get("cetak") == "pdf" || $this->input->get("cetak") == "print"):

  if($group == "all"):

    foreach ($list as $a):

        $no++;
        $tr = "<tr>";
        $tr .= "<td>".$i++."</td>";
        $tr .= "<td>".$a->Code."</td>";
        $tr .= "<td>".$a->vendorName."</td>";
        $tr .= "<td>".date("Y-m-d",strtotime($a->Date))."</td>";
        $tr .= "<td>".$this->main->currency($a->totalpayment)."</td>";
        $tr .= "<td>".$this->main->currency($a->totalcorrection)."</td>";
        $tr .= "<td>".$this->main->currency($a->total)."</td>";
        $tr .= "</tr>";

        $total1    += $a->totalpayment;
        $total2    += $a->totalcorrection;
        $total3    += $a->total;
        // $Total1 += $total_Saldo;
        // $Total2 += $a->grandtotal;
        echo $tr;
    endforeach;
    $tf = "<tr>";
    // $tf .= "<td colspan='2'>Total</td>";
    // $tf .= "<td>".$this->main->currency($total_saldo1)."</td>";
    // $tf .= "<td></td>";
    // $tf .= "<td></td>";
    // $tf .= "<td>".$this->main->currency($total_price)."</td>";
    // $tf .= "<td>".$this->main->currency($total_subtotal)."</td>";
    $tf .= "</tr>";
    // echo $tf;
elseif($group == "transaction"):
    // $total_Saldo  = 0;
    foreach ($list as $a):

        $no++;
        $tr = "<tr>";
        $tr .= "<td>".$i++."</td>";
        $tr .= "<td>".$a->Code."</td>";
        $tr .= "<td>".$a->vendorName."</td>";
        $tr .= "<td>".date("Y-m-d",strtotime($a->Date))."</td>";
        $tr .= "<td>".$this->main->currency($a->totalpayment)."</td>";
        $tr .= "<td>".$this->main->currency($a->TotalCorrection)."</td>";
        $tr .= "<td>".$this->main->currency($a->total)."</td>";
        $tr .= "</tr>";

        $total1    += $a->totalpayment;
        $total2    += $a->TotalCorrection;
        $total3    += $a->total;
           // $Total1 = $total_Saldo;
        // $Total1 += $a->total;
        // $Total2 += $a->grandtotal;
        echo $tr;
    endforeach;
    $tf = "<tr>";
    // $tf .= "<td colspan='6'>Total</td>";
    // $tf .= "<td>".$this->main->currency($total_saldo1)."</td>";
    // $tf .= "<td>".$this->main->currency($total_subtotal)."</td>";
    $tf .= "</tr>";
    // echo $tf;
elseif($group == "vendor"):
    // $total_Saldo  = 0;
    foreach ($list as $a):

        $no++;
        $tr = "<tr>";
        $tr .= "<td>".$i++."</td>";
        $tr .= "<td>".$a->vendorName."</td>";
        $tr .= "<td>".$this->main->currency($a->TotalCorrection)."</td>";
        $tr .= "</tr>";

        $total2    += $a->TotalCorrection;
           // $Total1 = $total_Saldo;
        // $Total1 += $a->total;
        // $Total2 += $a->grandtotal;
        echo $tr;
    endforeach;
    $tf = "<tr>";
    // $tf .= "<td colspan='6'>Total</td>";
    // $tf .= "<td>".$this->main->currency($total_saldo1)."</td>";
    // $tf .= "<td>".$this->main->currency($total_subtotal)."</td>";
    $tf .= "</tr>";
    // echo $tf;
    endif;
endif; ?>
</tbody>
<?php if($this->input->post("group") == "all"): ?>
 <tfoot>
    <tr>
        <th colspan="4"><?= $this->lang->line('lb_total') ?></th>
        <th class="total1"><?= $this->main->currency($total1); ?></th>
        <th class="total2"><?= $this->main->currency($total2); ?></th>
        <th class="total3"><?= $this->main->currency($total3); ?></th>
    </tr>
<?php elseif($this->input->post("group") == "transaction"): ?>
    <tr>
        <th colspan="4"><?= $this->lang->line('lb_total') ?></th>
        <th class="total1"><?= $this->main->currency($total1); ?></th>
        <th class="total2"><?= $this->main->currency($total2); ?></th>
        <th class="total3"><?= $this->main->currency($total3); ?></th>
    </tr>
<?php elseif($this->input->post("group") == "vendor"): ?>
    <tr>
        <th colspan="2"><?= $this->lang->line('lb_total') ?></th>
        <th class="total2"><?= $this->main->currency($total2); ?></th>
    </tr>
</tfoot> 
<?php endif;?>
</table>