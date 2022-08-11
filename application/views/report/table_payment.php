<table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
 <thead>
<?php if($this->input->post("group") == "all"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_date') ?></th>
    <th><?= $this->lang->line('lb_transaction_no') ?></th>
    <th><?= $this->lang->line('lb_customer_name') ?></th>
    <th><?= $this->lang->line('lb_transaction_date') ?></th>
    <th><?= $this->lang->line('lb_transaction_no') ?></th>
    <th><?= $this->lang->line('lb_store_name') ?></th>
    <th><?= $this->lang->line('lb_total_netto') ?></th>
    <th><?= $this->lang->line('lb_total_unpaid') ?></th>
    <th><?= $this->lang->line('lb_total_paid') ?></th>
  </tr>
<?php elseif($this->input->post("group") == "date"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_date') ?></th>
    <th><?= $this->lang->line('lb_grand_total_sales') ?></th>
    <th><?= $this->lang->line('lb_giro_total') ?></th>
    <th><?= $this->lang->line('lb_debit_credit_total') ?></th>
    <th><?= $this->lang->line('lb_cash_total') ?></th>
    <th><?= $this->lang->line('lb_pay_additional') ?></th>
    <th><?= $this->lang->line('lb_total_payment') ?></th>
  </tr>
<?php elseif($this->input->post("group") == "payment_code"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_date') ?></th>
    <th><?= $this->lang->line('lb_transaction_no') ?></th>
    <th><?= $this->lang->line('lb_total_netto') ?></th>
    <th><?= $this->lang->line('lb_giro_total') ?></th>
    <th><?= $this->lang->line('lb_debit_credit_total') ?></th>
    <th><?= $this->lang->line('lb_cash_total') ?></th>
    <th><?= $this->lang->line('lb_pay_additional') ?></th>
    <th><?= $this->lang->line('lb_total_payment') ?></th>
  </tr>
<?php elseif($this->input->post("group") == "store_name"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_store_name') ?></th>
    <th><?= $this->lang->line('lb_grand_total_sales') ?></th>
    <th><?= $this->lang->line('lb_giro_total') ?></th>
    <th><?= $this->lang->line('lb_debit_credit_total') ?></th>
    <th><?= $this->lang->line('lb_cash_total') ?></th>
    <th><?= $this->lang->line('lb_pay_additional') ?></th>
    <th><?= $this->lang->line('lb_total_payment') ?></th>
  </tr>
<?php elseif($this->input->post("group") == "sales_code"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_sellingno') ?></th>
    <th><?= $this->lang->line('lb_grand_total_sales') ?></th>  
    <th><?= $this->lang->line('lb_giro_total') ?></th>
    <th><?= $this->lang->line('lb_debit_credit_total') ?></th>
    <th><?= $this->lang->line('lb_cash_total') ?></th>
    <th><?= $this->lang->line('lb_pay_additional') ?></th>
    <th><?= $this->lang->line('lb_total_payment') ?></th>
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
if($this->input->get("cetak") == "pdf" || $this->input->get("cetak") == "print"):
    if($group == "all"):
        foreach ($list as $a):
            $no++;
            $tr = "<tr>";
            $tr .= "<td>".$i++."</td>";
            $tr .= "<td>".date("Y-m-d",strtotime($a->date))."</td>";
            $tr .= "<td>".$a->paymentno."</td>";
            $tr .= "<td>".$a->vendorName."</td>";
            $tr .= "<td>".date("Y-m-d",strtotime($a->transactionDate))."</td>";
            $tr .= "<td>".$a->sellno."</td>";
            $tr .= "<td>".$a->store_name."</td>";
            $tr .= "<td>".$this->main->currency($a->grandtotal)."</td>";
            $tr .= "<td>".$this->main->currency($a->unpaid)."</td>";
            $tr .= "<td>".$this->main->currency($a->total_payment)."</td>";
            $tr .= "</tr>";

            $total1    += $a->grandtotal;
            $total2    += $a->unpaid;
            $total6    += $a->total_payment;

            echo $tr;
        endforeach;
    elseif($group == "date"):
        foreach ($list as $a):
            $no++;
            $tr = "<tr>";
            $tr .= "<td>".$i++."</td>";
            $tr .= "<td>".date("Y-m-d",strtotime($a->date))."</td>";
            $tr .= "<td>".$this->main->currency($a->grandtotal)."</td>";
            $tr .= "<td>".$this->main->currency($a->giro)."</td>";
            $tr .= "<td>".$this->main->currency($a->credit)."</td>";
            $tr .= "<td>".$this->main->currency($a->cash)."</td>";
            $tr .= "<td>".$this->main->currency($a->addcost)."</td>";
            $tr .= "<td>".$this->main->currency($a->total_payment)."</td>";
            $tr .= "</tr>";

            $total1    += $a->grandtotal;
            $total2    += $a->giro;
            $total3    += $a->credit;
            $total4    += $a->cash;
            $total5    += $a->addcost;
            $total6    += $a->total_payment;


            echo $tr;
        endforeach;
    elseif($group == "payment_code"):
        foreach ($list as $a):
            $no++;
            $tr = "<tr>";
            $tr .= "<td>".$i++."</td>";
            $tr .= "<td>".date("Y-m-d",strtotime($a->Date))."</td>";
            $tr .= "<td>".$a->paymentno."</td>";
            $tr .= "<td>".$this->main->currency($a->grandtotal)."</td>";
            $tr .= "<td>".$this->main->currency($a->giro)."</td>";
            $tr .= "<td>".$this->main->currency($a->credit)."</td>";
            $tr .= "<td>".$this->main->currency($a->cash)."</td>";
            $tr .= "<td>".$this->main->currency($a->addcost)."</td>";
            $tr .= "<td>".$this->main->currency($a->total_payment)."</td>";
            $tr .= "</tr>";

            $total1    += $a->grandtotal;
            $total2    += $a->giro;
            $total3    += $a->credit;
            $total4    += $a->cash;
            $total5    += $a->addcost;
            $total6    += $a->total_payment;


            echo $tr;
        endforeach;
    elseif($group == "store_name"):
        foreach ($list as $a):
            $no++;
            $tr = "<tr>";
            $tr .= "<td>".$i++."</td>";
            $tr .= "<td>".$a->store_name."</td>";
            $tr .= "<td>".$this->main->currency($a->grandtotal)."</td>";
            $tr .= "<td>".$this->main->currency($a->giro)."</td>";
            $tr .= "<td>".$this->main->currency($a->credit)."</td>";
            $tr .= "<td>".$this->main->currency($a->cash)."</td>";
            $tr .= "<td>".$this->main->currency($a->addcost)."</td>";
            $tr .= "<td>".$this->main->currency($a->total_payment)."</td>";
            $tr .= "</tr>";

            $total1    += $a->grandtotal;
            $total2    += $a->giro;
            $total3    += $a->credit;
            $total4    += $a->cash;
            $total5    += $a->addcost;
            $total6    += $a->total_payment;


            echo $tr;
        endforeach;
    elseif($group == "sales_code"):
        foreach ($list as $a):
            $no++;
            $tr = "<tr>";
            $tr .= "<td>".$i++."</td>";
            $tr .= "<td>".$a->sellno."</td>";
            $tr .= "<td>".$this->main->currency($a->grandtotal)."</td>";
            $tr .= "<td>".$this->main->currency($a->giro)."</td>";
            $tr .= "<td>".$this->main->currency($a->credit)."</td>";
            $tr .= "<td>".$this->main->currency($a->cash)."</td>";
            $tr .= "<td>".$this->main->currency($a->addcost)."</td>";
            $tr .= "<td>".$this->main->currency($a->total_payment)."</td>";
            $tr .= "</tr>";

            $total1    += $a->grandtotal;
            $total2    += $a->giro;
            $total3    += $a->credit;
            $total4    += $a->cash;
            $total5    += $a->addcost;
            $total6    += $a->total_payment;


            echo $tr;
        endforeach;
    endif;
endif; ?>
</tbody>
<?php if($this->input->post("group") == "all"): ?>
 <tfoot>
    <tr>
        <th colspan="7"><?= $this->lang->line('lb_total') ?></th>
        <th class="total1"><?= $this->main->currency($total1); ?></th>
        <th class="total2"><?= $this->main->currency($total2); ?></th>
        <th class="total6"><?= $this->main->currency($total6); ?></th>
    </tr>
<?php elseif($this->input->post("group") == "payment_code"): ?>
    <tr>
        <th colspan="3"><?= $this->lang->line('lb_total') ?></th>
        <th class="total1"><?= $this->main->currency($total1); ?></th>
        <th class="total2"><?= $this->main->currency($total2); ?></th>
        <th class="total3"><?= $this->main->currency($total3); ?></th>
        <th class="total4"><?= $this->main->currency($total4); ?></th>
        <th class="total5"><?= $this->main->currency($total5); ?></th>
        <th class="total6"><?= $this->main->currency($total6); ?></th>
    </tr>
<?php elseif($this->input->post("group") == "store_name"): ?>
    <tr>
        <th colspan="2"><?= $this->lang->line('lb_total') ?></th>
        <th class="total1"><?= $this->main->currency($total1); ?></th>
        <th class="total2"><?= $this->main->currency($total2); ?></th>
        <th class="total3"><?= $this->main->currency($total3); ?></th>
        <th class="total4"><?= $this->main->currency($total4); ?></th>
        <th class="total5"><?= $this->main->currency($total5); ?></th>
        <th class="total6"><?= $this->main->currency($total6); ?></th>
    </tr>
<?php elseif($this->input->post("group") == "sales_code"): ?>
    <tr>
        <th colspan="2"><?= $this->lang->line('lb_total') ?></th>
        <th class="total1"><?= $this->main->currency($total1); ?></th>
        <th class="total2"><?= $this->main->currency($total2); ?></th>
        <th class="total3"><?= $this->main->currency($total3); ?></th>
        <th class="total4"><?= $this->main->currency($total4); ?></th>
        <th class="total5"><?= $this->main->currency($total5); ?></th>
        <th class="total6"><?= $this->main->currency($total6); ?></th>
    </tr>
</tfoot> 
<?php endif;?>
</table>