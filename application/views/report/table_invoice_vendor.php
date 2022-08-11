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
    <th><?= $this->lang->line('lb_date') ?></th>
    <th><?= $this->lang->line('lb_invoiceno') ?></th>
    <th><?= $this->lang->line('lb_transaction_no') ?></th>
    <th><?= $this->lang->line('lb_transaction_date') ?></th>
    <th><?= $this->lang->line('lb_vendor_name') ?></th>
    <th><?= $this->lang->line('lb_sub_total') ?></th>
    <th><?= $this->lang->line('lb_discount_total') ?></th> 
    <th><?= $this->lang->line('lb_tax') ?></th>
    <th><?= $this->lang->line('lb_delivery_cost') ?></th>
    <th><?= $this->lang->line('lb_total') ?></th> 
    <!-- <th>Remark</th> -->
  </tr>
<?php elseif($this->input->post("group") == "transaction"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_date') ?></th>
    <th><?= $this->lang->line('lb_invoiceno') ?></th>
    <th><?= $this->lang->line('lb_vendor_name') ?></th>
    <th><?= $this->lang->line('lb_sub_total') ?></th>
    <th><?= $this->lang->line('lb_discount_total') ?></th> 
    <th><?= $this->lang->line('lb_tax') ?></th>
    <th><?= $this->lang->line('lb_delivery_cost') ?></th>
    <th><?= $this->lang->line('lb_total') ?></th> 
    <!-- <th>Remark</th> -->
  </tr>
<?php endif;?>
</thead>
<tbody>
<?php 
$total1  = 0;
$total2  = 0;
$total3  = 0;
$total4  = 0;
$total5  = 0;
if($this->input->get("cetak") == "pdf" || $this->input->get("cetak") == "print"):
  if($group == "all"):
    $no = 0;
    foreach ($list as $a) {
        // $total_qty += floatval($a->qty);
        // $total_price += floatval($a->price);
        // $total_subtotal += floatval($a->subtotal);
        $no++;
        $item = "<tr>";
        $item .= "<td>".$i++."</td>";
        $item .= "<td>".date("Y-m-d",strtotime($a->Date))."</td>";
        $item .= "<td>".$a->InvoiceNo."</td>";
        $item .= "<td>".$a->transactionCode."</td>";
        $item .= "<td>".date("Y-m-d",strtotime($a->transactionDate))."</td>";
        $item .= "<td>".$a->Name."</td>";
        $item .= "<td>".$this->main->currency($a->Subtotal)."</td>";
        $item .= "<td>".$this->main->currency($a->Discount)."</td>";
        $item .= "<td>".$this->main->currency($a->PPN)."</td>";
        $item .= "<td>".$this->main->currency($a->DeliveryCost)."</td>";
        $item .= "<td>".$this->main->currency($a->Total)."</td>";
        // $item .= "<td>".$a->Remark."</td>";
        $item .= "</tr>";

        $total1    += $a->Subtotal;
        $total2    += $a->Discount;
        $total3    += $a->PPN;
        $total4    += $a->DeliveryCost;
        $total5    += $a->Total;

        echo $item;
    }
    $tf = "<tr>";
    // $tf .= "<td colspan='7'>Total</td>";
    // $tf .= "<td>".$this->main->qty($total_qty)."</td>";
    // $tf .= "<td></td>";
    // $tf .= "<td></td>";
    // $tf .= "<td>".$this->main->currency($total_price)."</td>";
    // $tf .= "<td>".$this->main->currency($total_subtotal)."</td>";
    $tf .= "</tr>";
    // echo $tf;
elseif($group == "transaction"):
    $no = 0;
    foreach ($list as $a){
        // $total_qty += floatval($a->qty);
        // $total_subtotal += floatval($a->subtotal);
        $no++;
        $item = "<tr>";
        $item .= "<td>".$i++."</td>";
        $item .= "<td>".date("Y-m-d",strtotime($a->Date))."</td>";
        $item .= "<td>".$a->InvoiceNo."</td>";
        $item .= "<td>".$a->Name."</td>";
        $item .= "<td>".$this->main->currency($a->Subtotal)."</td>";
        $item .= "<td>".$this->main->currency($a->Discount)."</td>";
        $item .= "<td>".$this->main->currency($a->PPN)."</td>";
        $item .= "<td>".$this->main->currency($a->DeliveryCost)."</td>";
        $item .= "<td>".$this->main->currency($a->Total)."</td>";
        // $item .= "<td>".$a->Remark."</td>";
        $item .= "</tr>";

        $total1    += $a->Subtotal;
        $total2    += $a->Discount;
        $total3    += $a->PPN;
        $total4    += $a->DeliveryCost;
        $total5    += $a->Total;

        echo $item;
    }
    $tf = "<tr>";
    // $tf .= "<td colspan='3'>Total</td>";
    // $tf .= "<td>".$this->main->qty($total_qty)."</td>";
    // $tf .= "<td>".$this->main->currency($total_subtotal)."</td>";
    $tf .= "</tr>";
    // echo $tf;
    endif;
endif; ?>
</tbody> 
<?php if($this->input->post("group") == "all"): ?>
 <tfoot>
    <tr>
        <th colspan="6"><?= $this->lang->line('lb_total') ?></th>
        <th class="total1"><?= $this->main->currency($total1); ?></th>
        <th class="total2"><?= $this->main->currency($total2); ?></th>
        <th class="total3"><?= $this->main->currency($total3); ?></th>
        <th class="total4"><?= $this->main->currency($total4); ?></th>
        <th class="total5"><?= $this->main->currency($total5); ?></th>
    </tr>
<?php elseif($this->input->post("group") == "transaction"): ?>
    <tr>
        <th colspan="4"><?= $this->lang->line('lb_total') ?></th>
        <th class="total1"><?= $this->main->currency($total1); ?></th>
        <th class="total2"><?= $this->main->currency($total2); ?></th>
        <th class="total3"><?= $this->main->currency($total3); ?></th>
        <th class="total4"><?= $this->main->currency($total4); ?></th>
        <th class="total5"><?= $this->main->currency($total5); ?></th>
    </tr>
</tfoot> 
<?php endif;?>
</table>