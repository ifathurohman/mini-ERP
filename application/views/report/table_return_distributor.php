<?php
    // echo "<pre>";
    // echo print_r($list);
    // echo "</pre>";
?>
<table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
 <thead>
<?php if($this->input->post("group") == "all"): ?>
  <tr>
    <th>No</th>
    <th>Date Return</th>
    <th>Return No</th>
    <th>Date Selling</th>
    <th>Branch Name</th>
    <th>Selling No</th>
    <th>Customer Name</th>
    <th>Product Code</th> 
    <th>Product Name</th> 
    <th>Qty</th>
    <th>Unit</th>
    <th>Conversion</th>
  <!--   <th>Delivery No</th> -->
    <th>Remark</th>
  </tr>
<?php elseif($this->input->post("group") == "distributor"): ?>
  <tr>
    <th>No</th>
    <th>Date Return</th>
    <th>Return No</th>
    <th>Date Selling</th>
    <th>Branch Name</th>
    <th>Selling No</th>
    <th>Customer Name</th>
    <th>Total Qty Return</th>
    <th>Total Return</th>
    <th>Remark</th>
    <th>Sales Name</th>
  </tr>
<?php endif;?>
</thead>
<tbody>
<?php 
if($this->input->get("cetak") == "pdf" || $this->input->get("cetak") == "print"):
  $total_qty      = 0.00;
  $total_price    = 0.00;
  $total_subtotal = 0.00;
  if($group == "all"):
    foreach ($list as $a):
        // $total_qty += floatval($a->qty);
        // $total_price += floatval($a->price);
        // $total_subtotal += floatval($a->subtotal);
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
        $tr .= "<td>".$a->remark."</td>";
        $tr .= "</tr>";
        echo $tr;
    endforeach;
    $tf = "<tr>";
    // $tf .= "<td colspan='7'>Total</td>";
    // $tf .= "<td>".$this->main->qty($total_qty)."</td>";
    // $tf .= "<td></td>";
    // $tf .= "<td></td>";
    // $tf .= "<td>".$this->main->currency($total_price)."</td>";
    // $tf .= "<td>".$this->main->currency($total_subtotal)."</td>";
    $tf .= "</tr>";
    // echo $tf;
elseif($group == "distributor"):
    foreach ($list as $a):
        // $total_qty += floatval($a->qty);
        // $total_subtotal += floatval($a->subtotal);
        $no++;
        $tr = "<tr>";
        $tr .= "<td>".$i++."</td>";
        $tr .= "<td>".date("Y-m-d",strtotime($a->date))."</td>";
        $tr .= "<td>".$a->returno."</td>";
        $tr .= "<td>".date("Y-m-d",strtotime($a->sellDate))."</td>";
        $tr .= "<td>".$a->branch."</td>";
        $tr .= "<td>".$a->SellNo."</td>";
        $tr .= "<td>".$a->vendorname."</td>";
        $tr .= "<td>".$a->qty."</td>";
        $tr .= "<td>".$this->main->currency($a->total_qty)."</td>";
        $tr .= "<td>".$a->remark."</td>";
        $tr .= "<td>".$a->sales_name."</td>";
        $tr .= "</tr>";
        echo $tr;
    endforeach;
    $tf = "<tr>";
    // $tf .= "<td colspan='3'>Total</td>";
    // $tf .= "<td>".$this->main->qty($total_qty)."</td>";
    // $tf .= "<td>".$this->main->currency($total_subtotal)."</td>";
    $tf .= "</tr>";
    // echo $tf;
endif;
endif; ?>
</tbody>
</table>