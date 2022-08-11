<table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
 <thead>
 <?php if($this->input->post("group") == "all"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_date') ?></th>
    <th><?= $this->lang->line('lb_mutation_no') ?></th>
    <th><?= $this->lang->line('lb_from') ?></th>
    <th><?= $this->lang->line('lb_to') ?></th>
    <th><?= $this->lang->line('lb_product_code') ?></th>
    <th><?= $this->lang->line('lb_product_name') ?></th>
    <th><?= $this->lang->line('lb_qty') ?></th>
    <th><?= $this->lang->line('lb_unit') ?></th>
    <th><?= $this->lang->line('lb_conversion') ?></th>
    <th><?= $this->lang->line('price') ?></th>
    <th><?= $this->lang->line('lb_sub_total') ?></th>
  </tr>
 <?php elseif($this->input->post("group") == "date"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_date') ?></th>
    <th><?= $this->lang->line('lb_qty') ?></th>
    <th><?= $this->lang->line('price') ?></th>
    <th><?= $this->lang->line('lb_sub_total') ?></th>
  </tr>
 <?php elseif($this->input->post("group") == "mutation_code"): ?>
  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_mutation_no') ?></th>
    <th><?= $this->lang->line('lb_qty') ?></th>
    <th><?= $this->lang->line('price') ?></th>
    <th><?= $this->lang->line('lb_sub_total') ?></th>
  </tr>
 <?php elseif($this->input->post("group") == "mutation_from" || $this->input->post("group") == "mutation_to"): ?>

  <tr>
    <th><?= $this->lang->line('lb_no') ?></th>
    <th><?= $this->lang->line('lb_from') ?></th>
    <th><?= $this->lang->line('lb_to') ?></th>
    <th><?= $this->lang->line('lb_qty') ?></th>
    <th><?= $this->lang->line('price') ?></th>
    <th><?= $this->lang->line('lb_sub_total') ?></th>
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
          $total_qty += floatval($a->qty);
          $total_price += floatval($a->price);
          $total_subtotal += floatval($a->subtotal);

          $no++;
          $tr = "<tr>";
          $tr .= "<td>".$i++."</td>";
          $tr .= "<td>".$a->date."</td>";
          $tr .= "<td>".$a->mutationno."</td>";
          $tr .= "<td>".$a->mutationfrom."</td>";
          $tr .= "<td>".$a->mutationto."</td>";
          $tr .= "<td>".$a->product_code."</td>";
          $tr .= "<td>".$a->product_name."</td>";
          $tr .= "<td>".$this->main->qty($a->qty)."</td>";
          $tr .= "<td>".$a->unit_name."</td>";
          $tr .= "<td>".$a->conversion."</td>";
          $tr .= "<td>".$this->main->currency($a->price)."</td>";
          $tr .= "<td>".$this->main->currency($a->subtotal)."</td>";
          $tr .= "</tr>";
          echo $tr;
      endforeach;
      $tf = "<tr>";
      $tf .= "<td colspan='7'>".$this->lang->line('lb_total')."</td>";
      $tf .= "<td>".$this->main->qty($total_qty)."</td>";
      $tf .= "<td></td>";
      $tf .= "<td></td>";
      $tf .= "<td>".$this->main->currency($total_price)."</td>";
      $tf .= "<td>".$this->main->currency($total_subtotal)."</td>";
      $tf .= "</tr>";
      echo $tf;
  elseif($group == "date"):
      foreach ($list as $a):
          $total_qty += floatval($a->qty);
          $total_subtotal += floatval($a->subtotal);
          $no++;
          $tr = "<tr>";
          $tr .= "<td>".$i++."</td>";
          $tr .= "<td>".$a->date."</td>";
          $tr .= "<td>".$this->main->qty($a->qty)."</td>";
          $tr .= "<td>".$this->main->currency($a->subtotal)."</td>";
          $tr .= "</tr>";
          echo $tr;
      endforeach;
      $tf = "<tr>";
      $tf .= "<td colspan='2'>".$this->lang->line('lb_total')."</td>";
      $tf .= "<td>".$this->main->qty($total_qty)."</td>";
      $tf .= "<td>".$this->main->currency($total_subtotal)."</td>";
      $tf .= "</tr>";
      echo $tf;
  elseif($group == "mutation_code"):
      foreach ($list as $a):
          $total_qty += floatval($a->qty);
          $total_subtotal += floatval($a->subtotal);
          $total_price  += floatval($a->price);
          $no++;
          $tr = "<tr>";
          $tr .= "<td>".$i++."</td>";
          $tr .= "<td>".$a->mutationno."</td>";
          $tr .= "<td>".$this->main->qty($a->qty)."</td>";
          $tr .= "<td>".$this->main->currency($a->price)."</td>";
          $tr .= "<td>".$this->main->currency($a->subtotal)."</td>";
          $tr .= "</tr>";
          echo $tr;
      endforeach;
      $tf = "<tr>";
      $tf .= "<td colspan='2'>".$this->lang->line('lb_total')."</td>";
      $tf .= "<td>".$this->main->qty($total_qty)."</td>";
      $tf .= "<td>".$this->main->currency($total_price)."</td>";
      $tf .= "<td>".$this->main->currency($total_subtotal)."</td>";
      $tf .= "</tr>";
      echo $tf;

  elseif($group == "mutation_from" || $group == "mutation_to"):
      foreach ($list as $a):
          $total_qty += floatval($a->qty);
          $total_subtotal += floatval($a->subtotal);
          $total_price  += floatval($a->price);
          $no++;
          $tr = "<tr>";
          $tr .= "<td>".$i++."</td>";
          $tr .= "<td>".$a->mutationfrom."</td>";
          $tr .= "<td>".$a->mutationto."</td>";
          $tr .= "<td>".$this->main->qty($a->qty)."</td>";
          $tr .= "<td>".$this->main->currency($a->price)."</td>";
          $tr .= "<td>".$this->main->currency($a->subtotal)."</td>";
          $tr .= "</tr>";
          echo $tr;
      endforeach;
      $tf = "<tr>";
      $tf .= "<td colspan='3'>".$this->lang->line('lb_total')."</td>";
      $tf .= "<td>".$this->main->qty($total_qty)."</td>";
      $tf .= "<td>".$this->main->currency($total_price)."</td>";
      $tf .= "<td>".$this->main->currency($total_subtotal)."</td>";
      $tf .= "</tr>";
      echo $tf;

  endif; 
endif;?>
</tbody>
</table>