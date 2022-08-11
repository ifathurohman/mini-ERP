<?php
  // echo '<pre>';
  // echo print_r($list);
  // echo '<pre>';
  // exit();
?>
<table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
 <thead>
 <?php if($this->input->post("group") == "all"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_date') ?></th>
    <th><?= $this->lang->line('lb_goodrcno') ?></th>
    <th><?= $this->lang->line('lb_transaction_no') ?></th>
    <th><?= $this->lang->line('lb_vendor_name') ?></th>
    <th><?= $this->lang->line('lb_store') ?></th>
    <th><?= $this->lang->line('lb_product_code') ?></th>
    <th><?= $this->lang->line('lb_product_name') ?></th>
    <th><?= $this->lang->line('lb_qty') ?></th>
    <th><?= $this->lang->line('lb_unit') ?></th>
    <th><?= $this->lang->line('lb_conversion') ?></th>
    <th><?= $this->lang->line('price') ?></th>
    <th><?= $this->lang->line('lb_discount') ?></th>
    <th><?= $this->lang->line('lb_tax') ?></th>
    <!-- <th><?= $this->lang->line('lb_delivery_cost') ?></th> -->
    <th><?= $this->lang->line('lb_sub_total') ?></th>
  </tr>
 <?php elseif($this->input->post("group") == "date"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_date') ?></th>
    <th><?= $this->lang->line('lb_qty') ?></th>
    <th><?= $this->lang->line('lb_total') ?></th>
  </tr>
 <?php elseif($this->input->post("group") == "gr_code"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_date') ?></th>
    <th><?= $this->lang->line('lb_goodrcno') ?></th>
    <th><?= $this->lang->line('lb_transaction_no') ?></th>
    <th><?= $this->lang->line('lb_vendor_name') ?></th>
    <th><?= $this->lang->line('lb_store') ?></th>
    <th><?= $this->lang->line('lb_qty_total') ?></th>
    <th><?= $this->lang->line('lb_sub_total') ?></th>
    <th><?= $this->lang->line('lb_discount_total') ?></th>
    <th><?= $this->lang->line('lb_tax') ?></th>
    <th><?= $this->lang->line('lb_delivery_cost') ?></th>
    <th><?= $this->lang->line('lb_total') ?></th>
  </tr>
   <?php elseif($this->input->post("group") == "purchase_code"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_date') ?></th>
    <th><?= $this->lang->line('lb_purchaseno') ?></th>
    <th><?= $this->lang->line('lb_goodrcno') ?></th>
    <th><?= $this->lang->line('lb_qty') ?></th>
    <th><?= $this->lang->line('lb_total') ?></th>
  </tr>
 <?php elseif($this->input->post("group") == "receipt_name"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_vendor_name') ?></th>
    <th><?= $this->lang->line('lb_qty') ?></th>
    <th><?= $this->lang->line('lb_total') ?></th>
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
  <?php elseif($this->input->post("group") == "store"): ?>
    <tr>
      <th><?= $this->lang->line('lb_no') ?></th>
      <th><?= $this->lang->line('lb_store') ?></th>
      <th><?= $this->lang->line('lb_qty') ?></th>
      <th><?= $this->lang->line('lb_total') ?></th>
    </tr>
 <?php endif;?>

</thead>
<tbody>
<?php 
$total1    = 0;
$total2  = 0;
$total3      = 0;
$total4  = 0;
$total5    = 0;
$total6      = 0;
if($this->input->get("cetak") == "pdf" || $this->input->get("cetak") == "print"):

  if($group == "all"):
      foreach ($list as $a):
        
        $sub_total  = ($a->price * $a->qty);
        $discount   = $sub_total*($a->discount/100);
        $sub_total  = $sub_total - $discount;
        $tax        = $a->tax;
        $total      = $sub_total;
        $ppn        = 0;
        if($tax == 1):
            $tax    = 10;
            $ppn    = $sub_total * (10/100);
            $total  = $sub_total + $ppn;
        endif;

        $tr = "<tr>";
        $no++;
        $tr .= "<td>". $i++."</td>";
        $tr .= "<td>". $a->date."</td>";
        $tr .= "<td>". $a->transactioncode."</td>";
        $tr .= "<td>". $a->receiveno."</td>";
        $tr .= "<td>". $a->receivename."</td>";
        $tr .= "<td>". $a->branchName."</td>";
        $tr .= "<td>". $a->product_code."</td>";
        $tr .= "<td>". $a->product_name."</td>";
        $tr .= "<td>". $this->main->qty($a->qty)."</td>";
        $tr .= "<td>". $a->unit_name."</td>";
        $tr .= "<td>". (float) $a->conversion."</td>";
        $tr .= "<td>". $this->main->currency($a->price)."</td>";
        $tr .= "<td>". $this->main->currency($discount)."</td>";
        $tr .= "<td>". $this->main->currency($ppn)."</td>";
        //$tr .= "<td>". $this->main->currency($a->deliverycost)."</td>";
        $tr .= "<td>". $this->main->currency($total)."</td>";
        $tr .= "</tr>";

        $total6     += $a->qty;
        $total1   += $a->price;
        $total2    += $discount;
        $total3    += $ppn;
        //$total4    += $a->deliverycost;
        $total5    += $total;
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
          $tr .= "</tr>";

          $total6    += $a->qty;
          $total5    += $a->subtotal;

          echo $tr;
      endforeach;
  elseif($group == "gr_code"):
      foreach ($list as $a):
          $no++;
          $tr = "<tr>";
          $tr .= "<td>". $i++."</td>";
          $tr .= "<td>". $a->date."</td>";
          $tr .= "<td>". $a->receiveno."</td>";
          $tr .= "<td>". $a->transactioncode."</td>";
          $tr .= "<td>". $a->receivename."</td>";
          $tr .= "<td>". $a->branchName."</td>";
          $tr .= "<td>". $this->main->qty($a->qty)."</td>";
          $tr .= "<td>". $this->main->currency($a->sub_total)."</td>";
          $tr .= "<td>". $this->main->currency($a->total_discount)."</td>";
          $tr .= "<td>". $this->main->currency($a->TotalPPN)."</td>";
          $tr .= "<td>". $this->main->currency($a->DeliveryCost)."</td>";
          $tr .= "<td>". $this->main->currency($a->payment)."</td>";
          $tr .= "</tr>";

          $total6 += $a->qty;
          $total1 += $a->sub_total;
          $total2 += $a->total_discount;
          $total3 += $a->TotalPPN;
          $total4 += $a->DeliveryCost;
          $total5 += $a->payment;

          echo $tr;
      endforeach;
  elseif($group == "purchase_code"):
      foreach ($list as $a):
          // $total_qty += floatval($a->qty);
          // $total_subtotal += floatval($a->subtotal);

          $no++;
          $tr = "<tr>";
          $tr .= "<td>". $i++."</td>";
          $tr .= "<td>". $a->date."</td>";
          $tr .= "<td>". $a->PurchaseNo."</td>";
          $tr .= "<td>". $a->receiveno."</td>";
          $tr .= "<td>". $this->main->qty($a->qty)."</td>";
          $tr .= "<td>". $this->main->currency($a->subtotal)."</td>";
          $tr .= "</tr>";

          $total6    += $a->qty;
          $total5    += $a->subtotal;

          echo $tr;
      endforeach;
  elseif($group == "receipt_name"):
    $list = $this->report->good_receipt_manage($list,"VendorID","receivename");
    foreach ($list as $a):
      $no++;
      $tr = "<tr>";
      $tr .= "<td>". $i++."</td>";
      $tr .= "<td>". $a->receivename."</td>";
      $tr .= "<td>". $this->main->qty($a->qty)."</td>";
      $tr .= "<td>". $this->main->currency($a->subtotal)."</td>";
      $tr .= "</tr>";

      $total6    += $a->qty;
      $total5    += $a->subtotal;

      echo $tr;
    endforeach;
  elseif($group == "product_name"):
      foreach ($list as $a):
          

          $no++;
          $tr = "<tr>";
          $tr .= "<td>". $i++."</td>";
          $tr .= "<td>". $a->product_code."</td>";
          $tr .= "<td>". $a->product_name."</td>";
          $tr .= "<td>". $this->main->qty($a->qty)."</td>";
          $tr .= "<td>". $a->unit_name."</td>";
          $tr .= "<td>". $a->Conversion."</td>";
          $tr .= "</tr>";

          $total6    += $a->qty;

          echo $tr;
      endforeach;
  elseif($group == "store"):
    $list = $this->report->good_receipt_manage($list,"BranchID","branchName");
    foreach ($list as $a):
      $no++;
      $tr = "<tr>";
      $tr .= "<td>". $i++."</td>";
      $tr .= "<td>". $a->branchName."</td>";
      $tr .= "<td>". $this->main->qty($a->qty)."</td>";
      $tr .= "<td>". $this->main->currency($a->subtotal)."</td>";
      $tr .= "</tr>";

      $total6    += $a->qty;
      $total5    += $a->subtotal;

      echo $tr;
    endforeach;
  endif;
endif; ?>
</tbody>
<?php if($this->input->post("group") == "all"): ?>
 <tfoot>
    <tr>
        <th colspan="8"><?= $this->lang->line('lb_total') ?></th>
        <th class="total6"><?= $this->main->qty($total6); ?></th>
        <th class=""></th>
        <th class=""></th>
        <th class="total1"><?= $this->main->currency($total1); ?></th>
        <th class="total2"><?= $this->main->currency($total2); ?></th>
        <th class="total3"><?= $this->main->currency($total3); ?></th>
        <!-- <th class="total4"><?= $this->main->currency($total4); ?></th> -->
        <th class="total5"><?= $this->main->currency($total5); ?></th>
    </tr>
<?php elseif($this->input->post("group") == "gr_code"): ?>
    <tr>
        <th colspan="6"><?= $this->lang->line('lb_total') ?></th>
        <th class="total6"><?= $this->main->qty($total6) ?></th>
        <th class="total1"><?= $this->main->currency($total1) ?></th>
        <th class="total2"><?= $this->main->currency($total2) ?></th>
        <th class="total3"><?= $this->main->currency($total3) ?></th>
        <th class="total4"><?= $this->main->currency($total4) ?></th>
        <th class="total5"><?= $this->main->currency($total5); ?></th>
    </tr>
<?php elseif($this->input->post("group") == "purchase_code"): ?>
    <tr>
        <th colspan="4"><?= $this->lang->line('lb_total') ?></th>
        <th class="total6"><?= $this->main->qty($total6); ?></th>
        <th class="total5"><?= $this->main->currency($total5); ?></th>
    </tr>
<?php elseif($this->input->post("group") == "receipt_name" || $this->input->post("group") == "store"): ?>
    <tr>
        <th colspan="2"><?= $this->lang->line('lb_total') ?></th>
         <th class="total6"><?= $this->main->qty($total6); ?></th>
        <th class="total5"><?= $this->main->currency($total5); ?></th>
    </tr>
<?php elseif($this->input->post("group") == "product_name"): ?>
    <tr>
        <th colspan="3"><?= $this->lang->line('lb_total') ?></th>
         <th class="total6"><?= $this->main->qty($total6) ?></th>
         <th class=""></th>
         <th class=""></th>
    </tr>
</tfoot> 
<?php endif;?>
</table>