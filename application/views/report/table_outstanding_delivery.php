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
    <th><?= $this->lang->line('lb_sellingno') ?></th>
    <th><?= $this->lang->line('lb_date') ?></th>
    <th><?= $this->lang->line('lb_customer_name') ?></th>
    <th><?= $this->lang->line('lb_store') ?></th>
    <th><?= $this->lang->line('lb_product_code') ?></th>
    <th><?= $this->lang->line('lb_product_name') ?></th> 
    <th><?= $this->lang->line('lb_qty_order') ?></th>
    <th><?= $this->lang->line('lb_deliveryqty') ?></th>
    <th><?= $this->lang->line('lb_qty_residue') ?></th>
  </tr>
<?php elseif($group == "transaction"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_sellingno') ?></th>
    <th><?= $this->lang->line('lb_date') ?></th>
    <th><?= $this->lang->line('lb_customer_name') ?></th>
    <th><?= $this->lang->line('lb_store') ?></th>
    <th><?= $this->lang->line('lb_qty_order') ?></th>
    <th><?= $this->lang->line('lb_deliveryqty') ?></th>
    <th><?= $this->lang->line('lb_qty_residue') ?></th>
  </tr>
  <?php elseif($group == "product_name"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_product_code') ?></th>
    <th><?= $this->lang->line('lb_product_name') ?></th>
    <th><?= $this->lang->line('lb_qty') ?></th>
    <th><?= $this->lang->line('lb_deliveryqty') ?></th>
    <th><?= $this->lang->line('lb_qty_residue') ?></th>
    <th><?= $this->lang->line('lb_unit') ?></th>
    <th><?= $this->lang->line('lb_conversion') ?></th>
  </tr>
  <?php elseif($group == "store"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_store') ?></th>
    <th><?= $this->lang->line('lb_qty') ?></th>
    <th><?= $this->lang->line('lb_deliveryqty') ?></th>
    <th><?= $this->lang->line('lb_qty_residue') ?></th>
  </tr>  
<?php endif;?>
</thead>
<tbody>
<?php
$totalQty           = 0;
$totalQtyDelivery   = 0;
$totalQtyResidue    = 0;
if($this->input->get("cetak") == "pdf" || $this->input->get("cetak") == "print"):
    if($group == "all"):
        foreach ($list as $a):

            $no++;
            $tr = "<tr>";
            $tr .= "<td>".$i++."</td>";
            $tr .= "<td>".$a->SellNo."</td>";
            $tr .= "<td>". date("Y-m-d",strtotime($a->Date))."</td>";
            $tr .= "<td>".$a->vendorName."</td>";
            $tr .= "<td>".$a->branchName."</td>";
            $tr .= "<td>".$a->productCode."</td>";
            $tr .= "<td>".$a->productName."</td>";
            $tr .= "<td>".$this->main->qty($a->Qty)."</td>";
            $tr .= "<td>".$this->main->qty($a->DeliveryQty)."</td>";
            $tr .= "<td>".$this->main->qty($a->qtyResidue)."</td>";
            $tr .= "</tr>";
            echo $tr;

            $totalQty           += $a->Qty;
            $totalQtyDelivery   += $a->DeliveryQty;
            $totalQtyResidue    += $a->qtyResidue;
        endforeach;
    elseif($group == "transaction"):
        foreach ($list as $a):

            $no++;
            $tr = "<tr>";
            $tr .= "<td>".$i++."</td>";
            $tr .= "<td>".$a->SellNo."</td>";
            $tr .= "<td>".date("Y-m-d",strtotime($a->Date))."</td>";
            $tr .= "<td>".$a->vendorName."</td>";
            $tr .= "<td>".$a->branchName."</td>";
            $tr .= "<td>".$this->main->qty($a->Qty)."</td>";
            $tr .= "<td>".$this->main->qty($a->DeliveryQty)."</td>";    
            $tr .= "<td>".$this->main->qty($a->qtyResidue)."</td>";
            $tr .= "</tr>";
            echo $tr;

            $totalQty           += $a->Qty;
            $totalQtyDelivery   += $a->DeliveryQty;
            $totalQtyResidue    += $a->qtyResidue;
        endforeach;
    elseif($group == "product_name"):
        foreach ($list as $a) {
            $tr = "<tr>";
            $tr .= "<td>".$i++."</td>";
            $tr .= "<td>".$a->product_code."</td>";
            $tr .= "<td>".$a->product_name."</td>";
            $tr .= "<td>".$this->main->qty($a->Qty)."</td>";
            $tr .= "<td>".$this->main->qty($a->DeliveryQty)."</td>";
            $tr .= "<td>".$this->main->qty($a->qtyResidue)."</td>";
            $tr .= "<td>".$a->unit_name."</td>";
            $tr .= "<td>".(float) $a->conversion."</td>";
            
            $tr .= "</tr>";

            echo $tr;

            $totalQty           += $a->Qty;
            $totalQtyDelivery   += $a->DeliveryQty;
            $totalQtyResidue    += $a->qtyResidue;
        }
    elseif($group == "store"):
        foreach ($list as $a) {
            $tr = "<tr>";
            $tr .= "<td>".$i++."</td>";
            $tr .= "<td>".$a->branchName."</td>";
            $tr .= "<td>".$this->main->qty($a->Qty)."</td>";
            $tr .= "<td>".$this->main->qty($a->DeliveryQty)."</td>";
            $tr .= "<td>".$this->main->qty($a->qtyResidue)."</td>";
            
            $tr .= "</tr>";

            echo $tr;

            $totalQty           += $a->Qty;
            $totalQtyDelivery   += $a->DeliveryQty;
            $totalQtyResidue    += $a->qtyResidue;
        }
    endif;
endif; ?>
</tbody>
<tfoot>
<?php if($group == "transaction"): ?>
    <tr>
        <th colspan="5"><?= $this->lang->line('lb_total') ?></th>
        <th class="totalQty"><?= $this->main->qty($totalQty) ?></th>
        <th class="totalQtyDelivery"><?= $this->main->qty($totalQtyDelivery) ?></th>
        <th class="totalQtyResidue"><?= $this->main->qty($totalQtyResidue) ?></th>
    </tr>
<?php elseif($group == "all"): ?>
    <tr>
        <th colspan="7"><?= $this->lang->line('lb_total') ?></th>
        <th class="totalQty"><?= $this->main->qty($totalQty) ?></th>
        <th class="totalQtyDelivery"><?= $this->main->qty($totalQtyDelivery) ?></th>
        <th class="totalQtyResidue"><?= $this->main->qty($totalQtyResidue) ?></th>
    </tr>
<?php elseif($group == "product_name"): ?>
    <tr>
        <th colspan="3"><?= $this->lang->line('lb_total') ?></th>
        <th class="totalQty"><?= $this->main->qty($totalQty) ?></th>
        <th class="totalQtyDelivery"><?= $this->main->qty($totalQtyDelivery) ?></th>
        <th class="totalQtyResidue"><?= $this->main->qty($totalQtyResidue) ?></th>
        <th colspan="2"></th>
    </tr>
<?php elseif($group == "store"): ?>
    <tr>
        <th colspan="2"><?= $this->lang->line('lb_total') ?></th>
        <th class="totalQty"><?= $this->main->qty($totalQty) ?></th>
        <th class="totalQtyDelivery"><?= $this->main->qty($totalQtyDelivery) ?></th>
        <th class="totalQtyResidue"><?= $this->main->qty($totalQtyResidue) ?></th>
    </tr>
<?php endif; ?>
</tfoot>
</table>