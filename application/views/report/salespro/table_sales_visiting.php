<table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
 <thead>
 <?php if($this->input->post("group") == "all"): ?>

  <tr>
    <th>No</th>
    <th>Transaction No</th>
    <th>Date</th>
    <th>Employee</th>
    <th>Customer</th>
    <th>CheckIn</th>
    <th>KM</th>
    <th>Total KM</th>
    <?php if($this->session->ParentID>0): ?>
    <th>Company</th>
    <?php endif; ?>
    
    <?php if(!$this->input->get("cetak")): ?>
    <!-- <th>Action</th> -->
    <?php endif; ?>
  
  </tr>
<?php elseif($this->input->post("group") == "date"): ?>
  <tr>
    <th>No</th>
    <th>Date</th>
    <th>Employee</th>
    <th>Total Visit</th>
    <th>Total KM</th>

    <?php if($this->session->ParentID>0): ?>
    <th>Company</th>
    <?php endif; ?>

    <?php if(!$this->input->get("cetak")): ?>
    <th>Action</th>
    <?php endif; ?>
  
  </tr>
<?php endif; ?>
</thead>
<tbody>
<?php 
if($this->input->get("cetak") == "pdf" || $this->input->get("cetak") == "print"):

    if($this->input->post("group") == "all"):
        $origin    = "0";
        $total_km       = 0;
        foreach ($list as $a):
            if(empty($a->VendorID)):
                $Latlng     = $a->CheckInLatlng;
            else:
                $Lat        = $a->Lat;
                $Lng        = $a->Lng;
                $Latlng     = $Lat.",".$Lng;
            endif;

            $distance = $this->main->Distance($origin,$Latlng);
            $km     = $distance["km"];
            $value  = $distance["value"];
            $total_km    = $total_km+$value;

            $no++;
            $tr = "<tr>";
            $tr .= "<td>".$i++."</td>";
            $tr .= "<td>".$a->Code."</td>";
            $tr .= "<td>".date("Y-m-d",strtotime($a->Date))."</td>";
            $tr .= "<td>".$a->SalesName."</td>";
            $tr .= "<td>".$a->customer."</td>";
            $tr .= "<td>".$this->main->convertSelisih($a->total_checkin)."</td>";
            $tr .= "<td>".$km."</td>";
            $tr .= "<td>".number_format(($total_km/1000), 1)." KM"."</td>";
            
            if($this->session->ParentID):
            $tr .= "<td>".$a->nama."</td>";
            endif;
            
            $tr .= "</tr>";
            $origin = $Latlng;
            echo $tr;
        endforeach;
    elseif($this->input->post("group") == "date"):
        foreach ($list as $a):
            $no++;
            $tr = "<tr>";
            $tr .= "<td>".$i++."</td>";
            $tr .= "<td>".date("Y-m-d",strtotime($a->Date))."</td>";
            $tr .= "<td>".$a->SalesName."</td>";
            $tr .= "<td>".$a->total_visit."</td>";
            $tr .= "<td>".$this->main->Distance($a->ID,"TransactionRouteIDArray",$a->Date)["km"]."</td>";

            if($this->session->ParentID):
            $tr .= "<td>".$a->nama."</td>";
            endif;
            
            $tr .= "</tr>";
            echo $tr;
        endforeach;
    endif;
endif;
?>
</tbody>
</table>