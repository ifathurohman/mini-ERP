<?php
    // echo "<pre>";
    // echo print_r($list);
    // echo "</pre>";
?>
<table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
 <thead>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_date') ?></th>
    <th><?= $this->lang->line('lb_transaction_no') ?></th>
    <th><?= $this->lang->line('lb_deliveryno') ?></th>
    <th><?= $this->lang->line('lb_vendor_name') ?></th>
    <th><?= $this->lang->line('lb_initial_balance') ?></th>
    <th><?= $this->lang->line('lb_debit') ?></th>
    <th><?= $this->lang->line('lb_credit') ?></th>
    <th><?= $this->lang->line('lb_final_balance') ?></th>
  </tr>
</thead>
<tbody>
<?php 
$total1  = 0;
$total2  = 0;
$total3  = 0;
$total4  = 0;
if($this->input->get("cetak") == "pdf" || $this->input->get("cetak") == "print"):
  // $total_qty      = 0.00;
  // $total_price    = 0.00;
  // $total_subtotal = 0.00;
    // $Total1 = 0;
    // $Total2 = 0;
    foreach ($list as $a):
        
        $no++;
        $tr = "<tr>";
        $tr .= "<td>".$i++."</td>";
        $tr .= "<td>".date("Y-m-d",strtotime($a->Tanggal))."</td>";
        $tr .= "<td>".$a->nobukti."</td>";
        $tr .= "<td>".$a->nopengiriman."</td>";
        $tr .= "<td>".$a->Supplier."</td>";
        $tr .= "<td>".$this->main->currency($a->Awal)."</td>";
        $tr .= "<td>".$this->main->currency($a->Debit)."</td>";
        $tr .= "<td>".$this->main->currency($a->Kredit)."</td>";
        $tr .= "<td>".$this->main->currency($a->Saldo)."</td>";
        $tr .= "</tr>";

        $total1    += $a->Awal;
        $total2    += $a->Debit;
        $total3    += $a->Kredit;
        $total4    += $a->Saldo;

        echo $tr;
    endforeach;
    $tf = "<tr>";
    // $tf .= "<td colspan='8'>Total</td>";
    // $tf .= "<td>".$this->main->currency($total_sales)."</td>";
    // $tf .= "<td>".$this->main->currency($total_grand)."</td>";
    // $tf .= "<td>".$this->main->currency($total_Saldo)."</td>";
    $tf .= "</tr>";
    echo $tf;
endif; ?>
</tbody>
<tfoot>
    <tr>
        <th colspan="5"><?= $this->lang->line('lb_total') ?></th>
        <th class="total1"><?= $this->main->currency($total1); ?></th>
        <th class="total2"><?= $this->main->currency($total2); ?></th>
        <th class="total3"><?= $this->main->currency($total3); ?></th>
        <th class="total4"><?= $this->main->currency($total4); ?></th>
    </tr>
</tfoot>
</table>