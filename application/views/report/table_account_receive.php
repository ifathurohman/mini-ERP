<table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
 <thead>
<?php if($this->input->post("group") == "all"): ?>
  <tr>
    <th>No</th>
    <th>Date</th>
    <th>Store Name</th>
    <th>AR Code</th>
    <th>Total AR Invoice</th>
  </tr>
<?php elseif($this->input->post("group") == "date"): ?>
  <tr>
    <th>No</th>
    <th>Date</th>
    <th>Total AR Invoice</th>
  </tr>
<?php elseif($this->input->post("group") == "store_name"): ?>
  <tr>
    <th>No</th>
    <th>Store Name</th>
    <th>Total AR Invoice</th>
  </tr>
<?php elseif($this->input->post("group") == "ar_code"): ?>
  <tr>
    <th>No</th>
    <th>AR Code</th>
    <th>Total AR Invoice</th>
  </tr>
<?php endif;?>
</thead>
<tbody>
<?php 
if($this->input->get("cetak") == "pdf" || $this->input->get("cetak") == "print"):
  $total_ar = 0.00;
  if($group == "all"):
      foreach ($list as $a):
          $total_ar += floatval($a->total);

          $no++;
          $tr = "<tr>";
          $tr .= "<td>". $i++."</td>";
          $tr .= "<td>". $a->date."</td>";
          $tr .= "<td>". $a->store_name."</td>";
          $tr .= "<td>". $a->arcode."</td>";
          $tr .= "<td>". $this->main->currency($a->total)."</td>";
          $tr .= "</tr>";
          echo $tr;
      endforeach;
      $tf = "<tr>";
      $tf .= "<td colspan='4'>Total</td>";
      $tf .= "<td>".$this->main->currency($total_ar)."</td>";
      $tf .= "</tr>";
      echo $tf;
  elseif($group == "date"):
      foreach ($list as $a):
          $total_ar += floatval($a->total);

          $no++;
          $tr = "<tr>";
          $tr .= "<td>". $i++."</td>";
          $tr .= "<td>". $a->date."</td>";
          $tr .= "<td>". $this->main->currency($a->total)."</td>";
          $tr .= "</tr>";
          echo $tr;
      endforeach;
      $tf = "<tr>";
      $tf .= "<td colspan='2'>Total</td>";
      $tf .= "<td>".$this->main->currency($total_ar)."</td>";
      $tf .= "</tr>";
      echo $tf;
  elseif($group == "store_name"):
      foreach ($list as $a):
          $total_ar += floatval($a->total);

          $no++;
          $tr = "<tr>";
          $tr .= "<td>". $i++."</td>";
          $tr .= "<td>". $a->store_name."</td>";
          $tr .= "<td>". $this->main->currency($a->total)."</td>";
          $tr .= "</tr>";
          echo $tr;
      endforeach;
      $tf = "<tr>";
      $tf .= "<td colspan='2'>Total</td>";
      $tf .= "<td>".$this->main->currency($total_ar)."</td>";
      $tf .= "</tr>";
      echo $tf;
  elseif($group == "ar_code"):
      foreach ($list as $a):
          $total_ar += floatval($a->total);

          $no++;
          $tr = "<tr>";
          $tr .= "<td>". $i++."</td>";
          $tr .= "<td>". $a->arcode."</td>";
          $tr .= "<td>". $this->main->currency($a->total)."</td>";
          $tr .= "</tr>";
          echo $tr;
      endforeach;
      $tf = "<tr>";
      $tf .= "<td colspan='2'>Total</td>";
      $tf .= "<td>".$this->main->currency($total_ar)."</td>";
      $tf .= "</tr>";
      echo $tf;
  endif;
endif;
?>
</tbody>
</table>