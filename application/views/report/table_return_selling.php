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
    <th><?= $this->lang->line('lb_return_date') ?></th>
    <th><?= $this->lang->line('lb_return_no') ?></th>
    <th><?= $this->lang->line('lb_date') ?></th>
    <th><?= $this->lang->line('lb_store_name') ?></th>
    <th><?= $this->lang->line('lb_transaction_no') ?></th>
    <th><?= $this->lang->line('lb_customer_name') ?></th>
    <th><?= $this->lang->line('lb_product_code') ?></th> 
    <th><?= $this->lang->line('lb_product_name') ?></th> 
    <th><?= $this->lang->line('lb_qty') ?></th>
    <th><?= $this->lang->line('lb_unit') ?></th>
    <th><?= $this->lang->line('lb_conversion') ?></th>
    <th><?= $this->lang->line('price') ?></th>
    <th><?= $this->lang->line('lb_discount') ?></th>
    <th><?= $this->lang->line('lb_tax') ?></th>
    <th><?= $this->lang->line('lb_total') ?></th>
    <th><?= $this->lang->line('lb_remark') ?></th>
  </tr>
<?php elseif($this->input->post("group") == "selling"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_return_date') ?></th>
    <th><?= $this->lang->line('lb_return_no') ?></th>
    <th><?= $this->lang->line('lb_date') ?></th>
    <th><?= $this->lang->line('lb_transaction_no') ?></th>
    <th><?= $this->lang->line('lb_customer_name') ?></th>
    <th><?= $this->lang->line('lb_return_qty_total') ?></th>
    <th><?= $this->lang->line('lb_return_total') ?></th>
    <th><?= $this->lang->line('lb_remark') ?></th>
    <th><?= $this->lang->line('lb_sales_name') ?></th>
  </tr>
<?php endif;?>
</thead>
<tbody>
<?php 
$totalQty      = 0;
$totalPrice    = 0;
$totalDiscount = 0;
$totalSubTotal = 0;
$totalTax      = 0;
$totalPayment  = 0;
$totalTransaction = 0;
if($this->input->get("cetak") == "pdf" || $this->input->get("cetak") == "print"):
  if($group == "all"):
    foreach ($list as $a):
        $sub_total  = $a->qty * $a->price;
        $sub_total  = $sub_total - $a->discount;
        if($a->tax == 1):
          $tax        = $this->main->PersenttoRp($sub_total,10);
        else:
          $tax        = 0;
        endif;

        $no++;
        $tr = "<tr>";
        $tr .= "<td>".$i++."</td>";
        $tr .= "<td>".date("Y-m-d",strtotime($a->date))."</td>";
        $tr .= "<td>".$a->returno."</td>";
        $tr .= "<td>".date("Y-m-d",strtotime($a->sellDate))."</td>";
        $tr .= "<td>".$a->branch."</td>";
        $tr .= "<td>".$a->SellNo."</td>";
        $tr .= "<td>".$a->vendorname."</td>";
        $tr .= "<td>".$a->product_code."</td>";
        $tr .= "<td>".$a->product_name."</td>";
        $tr .= "<td>".$a->qty."</td>";
        $tr .= "<td>".$a->unit_name."</td>";
        $tr .= "<td>".$a->conversion."</td>";
        $tr .= "<td>".$this->main->currency($a->price)."</td>";
        $tr .= "<td>".$this->main->currency($a->discount)."</td>";
        $tr .= "<td>".$this->main->currency($tax)."</td>";
        $tr .= "<td>".$this->main->currency($a->total)."</td>";
        $tr .= "<td>".$a->remark."</td>";
        $tr .= "</tr>";
        echo $tr;

        $totalQty       += $a->qty;
        $totalPrice     += $a->price;
        $totalDiscount  += $a->discount;
        $totalTax       += $tax;
        $totalPayment   += $a->total;

    endforeach;
elseif($group == "selling"):
    foreach ($list as $a):
        $no++;
        $tr = "<tr>";
        $tr .= "<td>".$i++."</td>";
        $tr .= "<td>".date("Y-m-d",strtotime($a->date))."</td>";
        $tr .= "<td>".$a->returno."</td>";
        $tr .= "<td>".date("Y-m-d",strtotime($a->sellDate))."</td>";
        $tr .= "<td>".$a->SellNo."</td>";
        $tr .= "<td>".$a->vendorname."</td>";
        $tr .= "<td>".$this->main->qty($a->qty)."</td>";
        $tr .= "<td>".$this->main->currency($a->total)."</td>";
        $tr .= "<td>".$a->remark."</td>";
        $tr .= "<td>".$a->sales_name."</td>";
        $tr .= "</tr>";
        echo $tr;

        $totalQty       += $a->qty;
        $totalPayment   += $a->total;
    endforeach;
endif;
endif; ?>
</tbody>
<tfoot>
<?php if($this->input->post("group") == "all"): ?>
    <tr>
        <th colspan="9"><?= $this->lang->line('lb_total') ?></th>
        <th class="totalQty"><?= $this->main->qty($totalQty) ?></th>
        <th colspan="2"></th>
        <th class="totalPrice"><?= $this->main->currency($totalPrice) ?></th>
        <th class="totalDiscount"><?= $this->main->currency($totalDiscount) ?></th>
        <th class="totalTax"><?= $this->main->currency($totalTax) ?></th>
        <th class="totalPayment"><?= $this->main->currency($totalPayment) ?></th>
        <th></th>
    </tr>
<?php elseif($this->input->post("group") == "selling"): ?>
    <tr>
        <th colspan="6"><?= $this->lang->line('lb_total') ?></th>
        <th class="totalQty"><?= $this->main->qty($totalQty) ?></th>
        <th class="totalPayment"><?= $this->main->currency($totalPayment) ?></th>
        <th colspan="2"></th>
    </tr>
<?php endif;?>
</tfoot>
</table>