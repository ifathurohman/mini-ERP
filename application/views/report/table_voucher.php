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
    <th><?= $this->lang->line('lb_package') ?></th>
    <th><?= $this->lang->line('lb_additional_qty') ?></th>
    <th><?= $this->lang->line('lb_module_qty') ?></th>
    <th><?= $this->lang->line('lb_voucher_user_amount') ?></th>
    <th><?= $this->lang->line('lb_voucher_module_amount') ?></th>
    <th><?= $this->lang->line('lb_total_amount') ?></th>
    <th><?= $this->lang->line('lb_name') ?></th>
  </tr>
</thead>
<tbody>
<?php 
// $total1 = 0;
// $total2 = 0;
// $total3 = 0;
// $total4 = 0;
// $total5 = 0;
// $total6 = 0;
// $total7 = 0;
if($this->input->get("cetak") == "pdf" || $this->input->get("cetak") == "print"):
  
    foreach ($list as $a):

        $qty        = $a->parentQty;
        $QtyModule  = $a->Qty;
        if($a->Module == "android"):
            $qty = $a->Qty;
            $QtyModule = 0;
        endif;

        $no++;
        $tr = "<tr>";
        $tr .= "<td>".$i++."</td>";
        $tr .= "<td>".date("Y-m-d",strtotime($a->Date))."</td>";
        $tr .= "<td>".$a->Code."</td>";
        $tr .= "<td>".$a->Type."</td>";
        $tr .= "<td>".number_format($qty,0)."</td>";
        $tr .= "<td>".number_format($QtyModule,0)."</td>";
        $tr .= "<td>".$this->main->currency($a->Price)."</td>";
        $tr .= "<td>".$this->main->currency($a->PriceModule)."</td>";
        $tr .= "<td>".$this->main->currency($a->TotalPrice)."</td>";
        $tr .= "<td>".$a->nama."</td>";
        $tr .= "</tr>";
            
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
<!--  <tfoot>
    <tr>
        <th colspan="4">Total</th>
        <th class="total1"><?= $this->main->currency($total1); ?></th>
        <th class="total2"><?= $this->main->currency($total2); ?></th>
        <th class="total3"><?= $this->main->currency($total3); ?></th>
        <th class="total4"><?= $this->main->currency($total4); ?></th>
        <th class="total5"><?= $this->main->currency($total5); ?></th>
        <th class="total6"><?= $this->main->currency($total6); ?></th>
        <th class="total7"><?= $this->main->currency($total7); ?></th>
    </tr>
</tfoot>  -->
</table>