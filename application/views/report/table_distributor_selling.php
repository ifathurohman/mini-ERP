<table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
 <thead>
<?php if($this->input->post("group") == "all"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_transaction_no') ?></th>
    <th><?= $this->lang->line('lb_date') ?></th>
    <th><?= $this->lang->line('lb_customer_name') ?></th>
    <th><?= $this->lang->line('lb_store') ?></th>
    <th><?= $this->lang->line('lb_city') ?></th>
    <th><?= $this->lang->line('lb_product_code') ?></th>
    <th><?= $this->lang->line('lb_product_name') ?></th> 
    <th><?= $this->lang->line('lb_qty') ?></th>
    <th><?= $this->lang->line('lb_unit') ?></th>
    <th><?= $this->lang->line('lb_conversion') ?></th>
    <th><?= $this->lang->line('price') ?></th>
    <th><?= $this->lang->line('lb_discount') ?> (%)</th>
    <th><?= $this->lang->line('lb_discount') ?></th>
    <th><?= $this->lang->line('lb_total_bruto') ?></th>
    <th><?= $this->lang->line('lb_tax') ?> (%)</th>
    <th><?= $this->lang->line('lb_tax') ?></th>
    <th><?= $this->lang->line('lb_total_netto') ?></th>
    <th><?= $this->lang->line('lb_sales_name') ?></th>
    <th><?= $this->lang->line('lb_remark') ?></th>
  </tr>
<?php elseif($this->input->post("group") == "selling"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_transaction_no') ?></th>
    <th><?= $this->lang->line('lb_date') ?></th>
    <th><?= $this->lang->line('lb_customer_name') ?></th>
    <th><?= $this->lang->line('lb_store') ?></th>
    <th><?= $this->lang->line('lb_city') ?></th>
    <th><?= $this->lang->line('lb_qty_total') ?></th>
    <th><?= $this->lang->line('lb_sub_total') ?></th>
    <th><?= $this->lang->line('lb_discount_total') ?></th>
    <th><?= $this->lang->line('lb_tax') ?></th>
    <th><?= $this->lang->line('lb_delivery_cost') ?></th>
    <th><?= $this->lang->line('lb_total_netto') ?></th>
    <th><?= $this->lang->line('lb_sales_name') ?></th>
    <th><?= $this->lang->line('lb_remark') ?></th>  
  </tr>
<?php elseif($this->input->post("group") == "customer"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_transaction_no') ?></th>
    <th><?= $this->lang->line('lb_date') ?></th>
    <th><?= $this->lang->line('lb_customer_name') ?></th>
    <th><?= $this->lang->line('lb_sub_total') ?></th>
    <th><?= $this->lang->line('lb_discount') ?> (%)</th>
    <th><?= $this->lang->line('lb_discount') ?> </th>
    <th><?= $this->lang->line('lb_tax') ?></th>
    <th><?= $this->lang->line('lb_total_netto') ?></th>
    <th><?= $this->lang->line('lb_sales_name') ?></th>
    <th><?= $this->lang->line('lb_remark') ?></th>
  </tr>
<?php elseif($this->input->post("group") == "product_name"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_product_code') ?></th>
    <th><?= $this->lang->line('lb_product_name') ?></th>
    <th><?= $this->lang->line('lb_qty') ?></th>
    <th><?= $this->lang->line('lb_unit') ?></th>
    <th><?= $this->lang->line('lb_conversion') ?></th>
  </tr>
<?php elseif($group == "vendor"): ?>
    <tr>
        <th><?= $this->lang->line('lb_no') ?></th>
        <th><?= $this->lang->line('lb_customer_name') ?></th>
        <th><?= $this->lang->line('lb_selling_total') ?></th>
        <th><?= $this->lang->line('lb_qty_total') ?></th>
        <th><?= $this->lang->line('lb_price_total') ?></th>
        <th><?= $this->lang->line('lb_total_netto') ?></th>
    </tr>
<?php elseif($group == "store"): ?>
    <tr>
        <th><?= $this->lang->line('lb_no') ?></th>
        <th><?= $this->lang->line('lb_store') ?></th>
        <th><?= $this->lang->line('lb_selling_total') ?></th>
        <th><?= $this->lang->line('lb_qty_total') ?></th>
        <th><?= $this->lang->line('lb_price_total') ?></th>
        <th><?= $this->lang->line('lb_total_netto') ?></th>
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
$totalDelivery = 0;
$totalTransaction = 0;
if($this->input->get("cetak") == "pdf" || $this->input->get("cetak") == "print"):
  if($group == "all"):
    foreach ($list as $a):
        $ppn        = 0;
        $total_net  = 0;
        if($a->tax == 1):
            $ppn = $this->main->PersenttoRp($a->TotalPrice, $a->ppn);
            $total_net = $a->TotalPrice + $ppn;
        endif;

        $no++;
        $tr = "<tr>";
        $tr .= "<td>". $i++."</td>";
        $tr .= "<td>". $a->sellno."</td>";
        $tr .= "<td>". date("Y-m-d",strtotime($a->date))."</td>";
        $tr .= "<td>". $a->customerName."</td>";
        $tr .= "<td>". $a->branchName."</td>";
        $tr .= "<td>". $a->DeliveryCity."</td>";
        $tr .= "<td>". $a->product_code."</td>";
        $tr .= "<td>". $a->product_name."</td>";
        $tr .= "<td>". $this->main->qty($a->qty)."</td>";
        $tr .= "<td>". $a->unit_name."</td>";
        $tr .= "<td>". (float) $a->conversion."</td>";
        $tr .= "<td>". $this->main->currency($a->price)."</td>";
        $tr .= '<td>'. (float) $a->diskon.'</td>';
        $tr .= '<td>'. $this->main->currency($a->diskonValue).'</td>';
        $tr .= "<td>". $this->main->currency($a->TotalPrice).'</td>';
        $tr .= "<td>". $this->main->label_tax($a->tax).'</td>';
        $tr .= '<td>'. $this->main->currency($ppn).'</td>';
        $tr .= '<td>'. $this->main->currency($total_net).'</td>';
        $tr .= '<td>'. $a->salesName.'</td>';
        $tr .= '<td>'. $a->remark.'</td>';
        $tr .= "</tr>";
        echo $tr;

        $totalQty       += $a->qty;
        $totalPrice     += $a->price;
        $totalDiscount  += $a->diskonValue;
        $totalSubTotal  += $a->TotalPrice;
        $totalTax       += $ppn;
        $totalPayment   += $total_net;

    endforeach;
elseif($group == "selling"):
    foreach ($list as $a):
        $no++;
        $tr = "<tr>";
        $tr .= "<td>". $i++."</td>";
        $tr .= "<td>". $a->sellno."</td>";
        $tr .= "<td>". date("Y-m-d",strtotime($a->date))."</td>";
        $tr .= "<td>". $a->customerName."</td>";
        $tr .= "<td>". $a->branchName."</td>";
        $tr .= "<td>". $a->DeliveryCity."</td>";
        $tr .= "<td>". $this->main->qty($a->qty)."</td>";
        $tr .= '<td>'. $this->main->currency($a->subtotal);
        $tr .= '<td>'. $this->main->currency($a->totaldiscount);
        $tr .= '<td>'. $this->main->currency($a->totalppn);
        $tr .= '<td>'. $this->main->currency($a->deliverycost);
        $tr .= '<td>'. $this->main->currency($a->payment);
        $tr .= '<td>'. $a->salesName.'</td>';
        $tr .= '<td>'. $a->remark.'</td>';
        $tr .= "</tr>";
        echo $tr;

        $totalQty       += $a->qty;
        $totalSubTotal  += $a->subtotal;
        $totalDiscount  += $a->totaldiscount;
        $totalTax       += $a->totalppn;
        $totalDelivery  += $a->deliverycost;
        $totalPayment   += $a->payment;
    endforeach;
    elseif($group == "product_name"):
        foreach ($list as $a) {
            $tr  = '<tr>';
            $tr .= '<td>'.$i++.'</td>';
            $tr .= '<td>'.$a->product_code.'</td>';
            $tr .= '<td>'.$a->product_name.'</td>';
            $tr .= '<td>'.$this->main->qty($a->qty).'</td>';
            $tr .= '<td>'.$a->unit_name.'</td>';
            $tr .= '<td>'.(float) $a->conversion.'</td>';
            $tr .= '</tr>';

            echo $tr;

            $totalQty += $a->qty;
        }
    elseif($group == "vendor"):
        foreach ($list as $a) {
            $tr  = '<tr>';
            $tr .= '<td>'.$i++.'</td>';
            $tr .= '<td>'.$a->vendor_name.'</td>';
            $tr .= '<td>'.$a->totalTransaction.'</td>';
            $tr .= "<td>". $this->main->qty($a->qty)."</td>";
            $tr .= "<td>". $this->main->currency($a->price)."</td>";
            $tr .= "<td>". $this->main->currency($a->payment)."</td>";
            
            $tr .= '</tr>';

            echo $tr;

            $totalQty       += $a->qty;
            $totalPrice     += $a->price;
            $totalPayment   += $a->payment;
            $totalTransaction += $a->totalTransaction;
        }
    elseif($group == "store"):
        foreach ($list as $a) {
            $tr  = '<tr>';
            $tr .= '<td>'.$i++.'</td>';
            $tr .= '<td>'.$a->branchName.'</td>';
            $tr .= '<td>'.$a->totalTransaction.'</td>';
            $tr .= "<td>". $this->main->qty($a->qty)."</td>";
            $tr .= "<td>". $this->main->currency($a->price)."</td>";
            $tr .= "<td>". $this->main->currency($a->payment)."</td>";
            
            $tr .= '</tr>';

            echo $tr;

            $totalQty       += $a->qty;
            $totalPrice     += $a->price;
            $totalPayment   += $a->payment;
            $totalTransaction += $a->totalTransaction;
        }
    endif;
endif; ?>
</tbody>
<tfoot>
<?php if($this->input->post("group") == "all"): ?>
    <tr>
        <th colspan="8"><?= $this->lang->line('lb_total') ?></th>
        <th class="totalQty"><?= $this->main->qty($totalQty) ?></th>
        <th></th>
        <th></th>
        <th class="totalPrice"><?= $this->main->currency($totalPrice) ?></th>
        <th></th>
        <th class="totalDiscount"><?= $this->main->currency($totalDiscount) ?></th>
        <th class="totalSubTotal"><?= $this->main->currency($totalSubTotal) ?></th>
        <th></th>
        <th class="totalTax"><?= $this->main->currency($totalTax) ?></th>
        <th class="totalPayment"><?= $this->main->currency($totalPayment) ?></th>
        <th></th>
        <th></th>
    </tr>
<?php elseif($this->input->post("group") == "selling"): ?>
    <tr>
        <th colspan="6"><?= $this->lang->line('lb_total') ?></th>
        <th class="totalQty"><?= $this->main->qty($totalQty) ?></th>
        <th class="totalSubTotal"><?= $this->main->currency($totalSubTotal) ?></th>
        <th class="totalDiscount"><?= $this->main->currency($totalDiscount) ?></th>
        <th class="totalTax"><?= $this->main->currency($totalTax) ?></th>
        <th class="totalDelivery"><?= $this->main->currency($totalDelivery) ?></th>
        <th class="totalPayment"><?= $this->main->currency($totalPayment) ?></th>
        <th></th>
        <th></th>
    </tr>
<?php elseif($this->input->post("group") == "vendor" || $this->input->post("group") == "store"): ?>
    <tr>
        <th colspan="2"><?= $this->lang->line('lb_total') ?></th>
        <th class="totalTransaction"><?= $totalTransaction ?></th>
        <th class="totalQty"><?= $this->main->qty($totalQty) ?></th>
        <th class="totalPrice"><?= $this->main->currency($totalPrice) ?></th>
        <th class="totalPayment"><?= $this->main->currency($totalPayment) ?></th>
    </tr>
<?php elseif($this->input->post("group") == "product_name"): ?>
    <tr>
        <th colspan="3"><?= $this->lang->line('lb_total') ?></th>
        <th class="totalQty"><?= $this->main->qty($totalQty) ?></th>
        <th></th>
        <th></th>
    </tr>
<?php endif;?>

</tfoot>
</table>