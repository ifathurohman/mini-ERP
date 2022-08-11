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
    <th><?= $this->lang->line('lb_purchaseno') ?></th>
    <th><?= $this->lang->line('lb_vendor_name') ?></th>
    <th><?= $this->lang->line('lb_sub_total') ?></th>
    <th><?= $this->lang->line('lb_discount') ?></th>
    <th><?= $this->lang->line('lb_tax') ?> (%)</th>
    <th><?= $this->lang->line('lb_delivery_cost') ?></th>
    <th><?= $this->lang->line('lb_purchase_total') ?></th>
    <th><?= $this->lang->line('lb_total_paid') ?></th>
    <th><?= $this->lang->line('lb_saldo') ?></th>
  </tr>
</thead>
<tbody>
<?php 
$total1 = 0;
$total2 = 0;
$total3 = 0;
$total4 = 0;
$total5 = 0;
$total6 = 0;
$total7 = 0;
if($this->input->get("cetak") == "pdf" || $this->input->get("cetak") == "print"):
  // $total_qty      = 0.00;
  // $total_price    = 0.00;
  // $total_subtotal = 0.00;
    $grandtotal1  = 0;
    $total_Saldo  = 0;
    $total_sales  = 0;
    $total_grand  = 0;
    foreach ($list as $a):
        $total_sales += floatval($a->total);
        $total_grand += floatval($a->grandtotal);
        
             $total_Saldo  = $a->grandtotal - $a->total;

        $no++;
        $tr = "<tr>";
        $tr .= "<td>".$i++."</td>";
        $tr .= "<td>".date("Y-m-d",strtotime($a->date))."</td>";
        $tr .= "<td>".$a->transactionCode."</td>";
        $tr .= "<td>".$a->customerName."</td>";
        $tr .= "<td>".$this->main->currency($a->subtotal)."</td>";
        $tr .= "<td>".$this->main->currency($a->diskon)."</td>";
        $tr .= "<td>".(float) $a->tax."</td>";
        $tr .= "<td>".$this->main->currency($a->deliverycost)."</td>";
        $tr .= "<td>".$this->main->currency($a->total)."</td>";
        $tr .= "<td>".$this->main->currency($a->grandtotal)."</td>";
        $tr .= "<td>".$this->main->currency($total_Saldo)."</td>";
        $tr .= "</tr>";
        $total1 += $a->subtotal;
        $total2 += $a->diskon;
        $total3 += $a->tax;
        $total4 += $a->deliverycost;
        $total5 += $a->total;
        $total6 += $a->grandtotal;
        $total7 += $total_Saldo;
     
        echo $tr;
    endforeach;
    $tf = "<tr>";
    $tf .= "<td colspan='8'>Total</td>";
    $tf .= "<td>".$this->main->currency($total_sales)."</td>";
    $tf .= "<td>".$this->main->currency($total_grand)."</td>";
    $tf .= "<td>".$this->main->currency($total_Saldo)."</td>";
    $tf .= "</tr>";
    // echo $tf;
endif; ?>
</tbody>
 <tfoot>
    <tr>
        <th colspan="4"><?= $this->lang->line('lb_total') ?></th>
        <th class="total1"><?= $this->main->currency($total1); ?></th>
        <th class="total2"><?= $this->main->currency($total2); ?></th>
        <th></th>
        <th class="total4"><?= $this->main->currency($total4); ?></th>
        <th class="total5"><?= $this->main->currency($total5); ?></th>
        <th class="total6"><?= $this->main->currency($total6); ?></th>
        <th class="total7"><?= $this->main->currency($total7); ?></th>
    </tr>
</tfoot> 
</table>