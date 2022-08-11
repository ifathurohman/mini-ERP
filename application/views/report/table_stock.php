<table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
 <thead>
  <tr>
    <th>No</th>
    <th>Date</th>
    <th>Product Name</th>
    <th>Category Code</th>
    <th>Category Name</th>
    <th>Initial</th>
    <th>In</th>
    <th>Out</th>
    <th>Last</th>
    <th>Unit</th>
    <th>Konv.</th>
    <th>Min. Qty</th>
  </tr>
</thead>
<tbody>
<?php 
if($this->input->get("cetak") == "pdf" || $this->input->get("cetak") == "print"):
    $total_initial  = 0.00;
    $total_in       = 0.00;
    $total_out      = 0.00;
    $total_last     = 0.00;
    foreach ($list as $a):
        $initial = $this->report->stock_initial($a->productid,$a->date,$a->conversion,$a->unitid);
        if($initial){
            $initial = $initial->qty;
        } else {
            $initial = 0.00;
        }
        $last = $initial + $a->qty;

        $total_initial += floatval($initial);
        $total_in += floatval($a->qty_in);
        $total_out += floatval($a->qty_out);
        $total_last += floatval($last);

        $no++;
        $tr = "<tr>";
        $tr .= "<td>".$i++."</td>";
        $tr .= "<td>".date("Y-m-d",strtotime($a->date))."</td>";
        $tr .= "<td>".$a->product_name."</td>";
        $tr .= "<td>".$a->category_code."</td>";
        $tr .= "<td>".$a->category_name."</td>";
        $tr .= "<td>".$this->main->qty($initial)."</td>";
        $tr .= "<td>".$this->main->qty($a->qty_in)."</td>";
        $tr .= "<td>".$this->main->qty($a->qty_out)."</td>";
        $tr .= "<td>".$this->main->qty($last)."</td>";
        $tr .= "<td>".$a->unit_name."</td>";
        $tr .= "<td>".$a->conversion."</td>";
        $tr .= "<td>".$this->main->qty($a->min_qty)."</td>";
        $tr .= "</tr>";
        echo $tr;
    endforeach;
    $tf = "<tr>";
    $tf .= "<td colspan='5'>Total</td>";
    $tf .= "<td>".$this->main->qty($total_initial)."</td>";
    $tf .= "<td>".$this->main->qty($total_in)."</td>";
    $tf .= "<td>".$this->main->qty($total_out)."</td>";
    $tf .= "<td>".$this->main->qty($total_last)."</td>";
    $tf .= "<td colspan='3'></td>";
    $tf .= "</tr>";
    echo $tf;
endif;
?>
</tbody>
</table>