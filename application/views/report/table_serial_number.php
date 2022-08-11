<?php
    // echo '<pre>';
    // echo print_r($list);
    // echo '</pre>';
?>
<table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
 <thead>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <?php
    if($group != "all"):
        echo '<th>'.$this->lang->line('lb_date').'</th>';
    endif;
    if($group == "mutation"):
        echo '<th>'.$this->lang->line('lb_from').'</th>';
        echo '<th>'.$this->lang->line('lb_to').'</th>';
    else:
        echo '<th>'.$this->lang->line('lb_store').'</th>';
    endif; ?>
    <th><?= $this->lang->line('lb_product_code') ?></th>
    <th><?= $this->lang->line('lb_product_name') ?></th>
    <!-- <th><?= $this->lang->line('lb_qty') ?></th> -->
    <th><?= $this->lang->line('lb_type') ?></th>
    <th><?= $this->lang->line('lb_sn') ?></th>
  </tr>
</thead>
<tbody>
<?php 
if($this->input->get("cetak") == "pdf" || $this->input->get("cetak") == "print"):
    $list   = $this->report->serial_number_report();
    foreach($list as $a):
        $no++;
        $tr = "<tr>";
        $tr .= "<td>".$i++."</td>";
        if($group != "all"):
            $tr .= "<td>".date("Y-m-d",strtotime($a->date))."</td>";
        endif;
        if($group == "mutation"):
            $tr .= '<td>'.$a->branchName_from.'</td>';
            $tr .= '<td>'.$a->branchName_to.'</td>';
        else:
            $tr .= '<td>'.$a->branchName.'</td>';
        endif;
        $tr .= "<td>".$a->product_code."</td>";
        $tr .= "<td>".$a->product_name."</td>";
        // $tr .= "<td>".//$this->main->qty($a->qty)."</td>";
        $tr .= "<td>".$a->type_serial."</td>";
        $tr .= "<td>".$a->serialnumber."</td>";
        $tr .= "</tr>";
        echo $tr;
    endforeach;

endif;
?>
</tbody>
</table>