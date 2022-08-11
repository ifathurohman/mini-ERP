<table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
 <thead>
<?php if($this->input->post("group") == "all"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_transaction_no') ?></th>
    <th><?= $this->lang->line('lb_date') ?></th>
    <th><?= $this->lang->line('lb_goodrcno') ?></th>
    <th><?= $this->lang->line('lb_vendor_name') ?></th>
    <th><?= $this->lang->line('lb_store') ?></th>
    <th><?= $this->lang->line('lb_product_code') ?></th>
    <th><?= $this->lang->line('lb_product_name') ?></th>
    <th><?= $this->lang->line('lb_qty') ?></th>
    <th><?= $this->lang->line('lb_unit') ?></th>
    <th><?= $this->lang->line('lb_conversion') ?></th>
    <th><?= $this->lang->line('price') ?></th>
    <th><?= $this->lang->line('lb_sub_total') ?></th>
    <th><?= $this->lang->line('lb_discount') ?></th>
    <th><?= $this->lang->line('lb_tax') ?></th>
    <th><?= $this->lang->line('lb_total') ?></th>
  </tr>
<?php elseif($this->input->post("group") == "date"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_date') ?></th>
    <th><?= $this->lang->line('lb_qty') ?></th>>
    <th><?= $this->lang->line('lb_sub_total') ?></th>
  </tr>
<?php elseif($this->input->post("group") == "return_code"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_transaction_no') ?></th>
    <th><?= $this->lang->line('lb_transaction_date') ?></th>
    <th><?= $this->lang->line('lb_goodrcno') ?></th>
    <th><?= $this->lang->line('lb_vendor_name') ?></th>
    <th><?= $this->lang->line('lb_store') ?></th>
    <th><?= $this->lang->line('lb_qty_total') ?></th>
    <th><?= $this->lang->line('lb_sub_total') ?></th>
    <th><?= $this->lang->line('lb_discount_total') ?></th>
    <th><?= $this->lang->line('lb_tax') ?></th>
    <th><?= $this->lang->line('lb_total') ?></th>
  </tr>
<?php elseif($this->input->post("group") == "vendor_name"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_vendor_name') ?></th>
    <th><?= $this->lang->line('lb_qty') ?></th>
    <th><?= $this->lang->line('lb_total') ?></th>
    <th><?= $this->lang->line('lb_return_purchase_total') ?></th>
  </tr>
<?php elseif($this->input->post("group") == "product_name"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_product_code') ?></th>
    <th><?= $this->lang->line('lb_product_name') ?></th>
    <th><?= $this->lang->line('lb_qty_total') ?></th>
    <th><?= $this->lang->line('lb_unit') ?></th>
    <th><?= $this->lang->line('lb_conversion') ?></th>
  </tr>
<?php elseif($this->input->post("group") == "store"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_store') ?></th>
    <th><?= $this->lang->line('lb_qty_total') ?></th>
    <th><?= $this->lang->line('lb_total') ?></th>
    <th><?= $this->lang->line('lb_return_purchase_total') ?></th>
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
 $total6  = 0;
 $total7  = 0;
if($this->input->get("cetak") == "pdf" || $this->input->get("cetak") == "print"):
    if($group == "all"):
        foreach ($list as $a):
            $discount   = $this->main->PersenttoRp($a->subtotal,$a->discount);
            $sub_total  = $a->subtotal - $discount;
            if($a->Tax == 1):
              $tax        = $this->main->PersenttoRp($sub_total,10);
            else:
              $tax        = 0;
            endif;

            $no++;
            $tr = "<tr>";
            $tr .= "<td>". $i++."</td>";
            $tr .= "<td>". $a->returno."</td>";
            $tr .= "<td>". $a->date."</td>";
            $tr .= "<td>". $a->receiveno."</td>";
            $tr .= "<td>". $a->vendorname."</td>";
            $tr .= "<td>". $a->branchName."</td>";
            $tr .= "<td>". $a->product_code."</td>";
            $tr .= "<td>". $a->product_name."</td>";
            $tr .= "<td>". $this->main->qty($a->qty)."</td>";
            $tr .= "<td>". $a->unit_name."</td>";
            $tr .= "<td>". (float) $a->conversion."</td>";
            $tr .= "<td>". $this->main->currency($a->price)."</td>";
            $tr .= "<td>". $this->main->currency($a->subtotal)."</td>";
            $tr .= "<td>". (float) $a->discount.'%'."</td>";
            $tr .= "<td>". $this->main->currency($tax)."</td>";
            $tr .= "<td>". $this->main->currency($a->total)."</td>";
            $tr ."</tr>";
            $total6 += $a->qty;
            $total1 += $a->price;
            $total2 += $a->subtotal;
            $total3 += '';
            $total4 += $tax;
            $total5 += $a->total;
            echo $tr;
        endforeach;
    elseif($group == "date"):
        foreach ($list as $a):
            // $total_qty += floatval($a->qty);
            // $total_subtotal += floatval($a->subtotal);

            $no++;
            $tr = "<tr>";
            $tr .= "<td>". $i++."</td>";
            $tr .= "<td>". $a->date."</td>";
            $tr .= "<td>". $this->main->qty($a->qty)."</td>";
            $tr .= "<td>". $this->main->currency($a->subtotal)."</td>";
            $tr ."</tr>";
            $total5 += $a->subtotal;
            echo $tr;
        endforeach;
    elseif($group == "return_code"):
        foreach ($list as $a):
            // $total_subtotal += floatval($a->subtotal);
            
            $no++;
            $tr = "<tr>";
            $tr .= "<td>". $i++."</td>";
            $tr .= "<td>". $a->returno."</td>";
            $tr .= "<td>". $a->date."</td>";
            $tr .= "<td>". $a->receiveno."</td>";
            $tr .= "<td>". $a->vendorname."</td>";
            $tr .= "<td>". $a->branchName."</td>";
            $tr .= "<td>". $this->main->qty($a->qty)."</td>";
            $tr .= "<td>". $this->main->currency($a->subtotal)."</td>";
            $tr .= "<td>". $this->main->currency($a->discount)."</td>";
            $tr .= "<td>". $this->main->currency($a->TotalPPN)."</td>";
            $tr .= "<td>". $this->main->currency($a->total)."</td>";
            $tr ."</tr>";

            $total2 += $a->subtotal;
            $total3 += $a->discount;
            $total4 += $a->TotalPPN;
            $total5 += $a->total;
            $total6 += $a->qty;
            echo $tr;
        endforeach;
    elseif($group == "vendor_name"):
        foreach ($list as $a):
            // $total_qty += floatval($a->qty);
            // $total_subtotal += floatval($a->subtotal);

            $no++;
            $tr = "<tr>";
            $tr .= "<td>". $i++."</td>";
            $tr .= "<td>". $a->vendorname."</td>";
            $tr .= "<td>". $this->main->qty($a->qty)."</td>";
            $tr .= "<td>". $this->main->currency($a->total)."</td>";
            $tr .= "<td>". $a->total_return."</td>";
            $tr ."</tr>";
            $total6 += $a->qty;
            $total5 += $a->total;
            $total7 += $a->total_return;
            echo $tr;
        endforeach;
    elseif($group == "product_name"):
        foreach ($list as $a):
            // $total_qty += floatval($a->qty);
            $no++;
            $tr = "<tr>";
            $tr .= "<td>". $i++."</td>";
            $tr .= "<td>". $a->product_code."</td>";
            $tr .= "<td>". $a->product_name."</td>";
            $tr .= "<td>". $this->main->qty($a->qty)."</td>";
            $tr .= "<td>". $a->unit_name."</td>";
            $tr .= "<td>". (float) $a->conversion."</td>";
            $tr ."</tr>";
            $total6 += $a->qty;
            echo $tr;
        endforeach;
    elseif($group == "store"):
        foreach ($list as $a) {
            $no++;
            $tr = "<tr>";
            $tr .= "<td>". $i++."</td>";
            $tr .= "<td>". $a->branchName."</td>";
            $tr .= "<td>". $this->main->qty($a->qty)."</td>";
            $tr .= "<td>". $this->main->currency($a->total)."</td>";
            $tr .= "<td>". $a->total_return."</td>";
            $tr ."</tr>";

            $total6 += $a->qty;
            $total5 += $a->total;
            $total7 += $a->total_return;
            echo $tr;
        }
    endif;
endif;
?>
</tbody>
<?php if($this->input->post("group") == "all"): ?>
 <tfoot>
    <tr>
        <th colspan="8"><?= $this->lang->line('lb_total') ?></th>
        <th class="total6"><?= $this->main->qty($total6) ?></th>
        <th class=""></th>
        <th class=""></th>
        <th class="total1"><?= $this->main->currency($total1); ?></th>
        <th class="total2"><?= $this->main->currency($total2); ?></th>
        <th class="total3"></th>
        <th class="total4"><?= $this->main->currency($total4); ?></th>
        <th class="total5"><?= $this->main->currency($total5); ?></th>
    </tr>
<?php elseif($this->input->post("group") == "return_code"): ?>
    <tr>
        <th colspan="6"><?= $this->lang->line('lb_total') ?></th>
        <th class="total6"><?= $this->main->qty($total6); ?></th>
        <th class="total2"><?= $this->main->currency($total2); ?></th>
        <th class="total3"><?= $this->main->currency($total3); ?></th>
        <th class="total4"><?= $this->main->currency($total4); ?></th>
        <th class="total5"><?= $this->main->currency($total5); ?></th>
        
    </tr>
<?php elseif($this->input->post("group") == "vendor_name" || $this->input->post("group") == "store"): ?>
    <tr>
        <th colspan="2"><?= $this->lang->line('lb_total') ?></th>
        <th class="total6"><?= $this->main->qty($total6) ?></th>
        <th class="total5"><?= $this->main->currency($total5); ?></th>
        <th class="total7"><?= $total7 ?></th>
    </tr>
<?php elseif($this->input->post("group") == "product_name"): ?>
    <tr>
        <th colspan="3"><?= $this->lang->line('lb_total') ?></th>
        <th class="total6"><?= $this->main->qty($total6) ?></th>
        <th></th>
        <th></th>
    </tr>
</tfoot> 
<?php endif;?>
</table>