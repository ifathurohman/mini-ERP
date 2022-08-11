<?php
    // echo "<pre>";
    // echo print_r($list);
    // echo "</pre>";
?>
<table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
 <thead>
<?php if($group == "all"): ?>
   <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_customer_name') ?></th>
    <th><?= $this->lang->line('lb_saldo') ?></th>
  </tr>
<?php elseif($group == "transaction"): ?>
   <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_date') ?></th>
    <th><?= $this->lang->line('lb_customer_name') ?></th>
    <th><?= $this->lang->line('lb_transaction_no') ?></th>
    <th><?= $this->lang->line('lb_total_nota') ?></th>
    <th><?= $this->lang->line('lb_paid') ?></th>
    <th><?= $this->lang->line('lb_saldo') ?></th>
  </tr>
<?php endif;?>
</thead>
<tbody>
<?php 
$total1  = 0;
$total2  = 0;
$total3  = 0;
if($cetak == "pdf" || $cetak == "print"):
  // $Total2 = 0;
  if($group == "all"):
    foreach ($list as $a):
             
        $no++;
        $tr =  "<tr>";
        $tr .= "<td>".$i++."</td>";
        $tr .= "<td>".$a->Supplier."</td>";
        $tr .= "<td>".$this->main->currency($a->Saldo)."</td>";
        $tr .= "</tr>";
       
        $total3    += $a->Saldo;

        echo $tr;
    endforeach;
    $tf = "<tr>";
    $tf .= "<td colspan='2'>Total</td>";
    // $tf .= "<td>".$this->main->currency($total_saldo1)."</td>";
    // $tf .= "<td></td>";
    // $tf .= "<td></td>";
    // $tf .= "<td>".$this->main->currency($total_price)."</td>";
    // $tf .= "<td>".$this->main->currency($total_subtotal)."</td>";
    $tf .= "</tr>";
    // echo $tf;
elseif($group == "transaction"):
    foreach ($list as $a):
      
        $no++;
        $tr = "<tr>";
        $tr .= "<td>".$i++."</td>";
        $tr .= "<td>".date("Y-m-d",strtotime($a->Tanggal))."</td>";
        $tr .= "<td>".$a->supplier."</td>";
        $tr .= "<td>".$a->notransaksi."</td>";
        $tr .= "<td>".$this->main->currency($a->Totalnota)."</td>";
        $tr .= "<td>".$this->main->currency($a->Bayar)."</td>";
        $tr .= "<td>".$this->main->currency($a->Saldo)."</td>";
        $tr .= "</tr>";
            
        $total1    += $a->Totalnota;
        $total2    += $a->Bayar;
        $total3    += $a->Saldo;

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
<?php if($group == "transaction"): ?>
 <tfoot>
    <tr>
        <th colspan="4"><?= $this->lang->line('lb_total') ?></th>
        <th class="total1"><?= $this->main->currency($total1); ?></th>
        <th class="total2"><?= $this->main->currency($total2); ?></th>
        <th class="total3"><?= $this->main->currency($total3); ?></th>
    </tr>
</tfoot> 
<?php endif;?>
</table>