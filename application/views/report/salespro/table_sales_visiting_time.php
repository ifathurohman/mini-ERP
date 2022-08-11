<div class="box-report-route" style="display:none">
    <div class="col-sm-3 box-report">
        <div class="widget-content padding-30 bg-white clearfix pointer">
           <div class="counter  counter text-right pull-right">
              <div class="counter-label text-capitalize font-size-16">Total Route</div>
              <div class="counter-number-group">
                 <span class="counter-number total_route">0</span>
              </div>
           </div>
        </div>
    </div>
    <div class="col-sm-3 box-report">
        <div class="widget-content padding-30 bg-white clearfix pointer">
           <div class="counter  counter text-right pull-right">
              <div class="counter-label text-capitalize font-size-16">Total Planned Route</div>
              <div class="counter-number-group">
                 <span class="counter-number total_route_planning">0</span>
              </div>
           </div>
        </div>
    </div>
    <div class="col-sm-3 box-report">
        <div class="widget-content padding-30 bg-white clearfix pointer">
           <div class="counter  counter text-right pull-right">
              <div class="counter-label text-capitalize font-size-16">Total Missed Route</div>
              <div class="counter-number-group">
                 <span class="counter-number total_route_miss">0</span>
              </div>
           </div>
        </div>
    </div>
    <div class="col-sm-3 box-report">
        <div class="widget-content padding-30 bg-white clearfix pointer">
           <div class="counter  counter text-right pull-right">
              <div class="counter-label text-capitalize font-size-16">Total Unplanned Route</div>
              <div class="counter-number-group">
                 <span class="counter-number total_route_not_planning">0</span>
              </div>
           </div>
        </div>
    </div>
</div>

<table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
 <thead>
  <tr>
    <th>No</th>
    <th>Transaction No</th>
    <th>Date</th>
    <th>Employee</th>
    <th>Customer</th>
    <?php if($page == "sales_visiting_time"): ?>
    <th>Customer Address</th>
    <th>Check In Address</th>
    <th>Check Out Address</th>
    <th>Check In Time</th>
    <th>Check Out Time</th>
    <th>Duration</th>

    <?php if($this->session->ParentID>0): ?>
    <th>Company</th>
    <?php endif; ?>

    <?php if(!$this->input->get('cetak')): ?>
    <th>Action</th>
    <?php endif; ?>
    <?php elseif($page == "sales_visiting_remark"): ?>
    <th>Remark</th>
    <th>Remark Employee</th>
        
        <?php if($this->session->ParentID>0): ?>
        <th>Company</th>
        <?php endif; ?>
    
    <?php endif; ?>
  </tr>
</thead>
<tbody>
<?php 
if($this->input->get("cetak") == "pdf" || $this->input->get("cetak") == "print"):
    $total_route        = 0;
    $total_route_miss   = 0;
    $total_planning     = 0;
    $total_not_planning = 0;
    foreach ($list as $a):
        $CheckIn    = "";
        $CheckOut   = "";
        $duration   = "";
        if($a->CheckIn && $a->CheckOut):
            $duration = $this->main->selisih_waktu(date("Y-m-d H:i",strtotime($a->CheckIn)),date("Y-m-d H:i",strtotime($a->CheckOut)));
        endif;
        if($a->CheckIn):
            $CheckIn = date("H:i",strtotime($a->CheckIn));
        endif;
        if($a->CheckOut):
            $CheckOut = date("H:i",strtotime($a->CheckOut));
        endif;

        $total_route = $total_route+1;
        if($a->CustomerName):
            $total_planning = $total_planning+1;
        else:
            $total_not_planning = $total_not_planning+1;
        endif;
        if(!$a->CheckIn):
            $total_route_miss = $total_route_miss+1;
        endif;

        $no++;
        $tr = "<tr>";
        $tr .= "<td>".$i++."</td>";
        $tr .= "<td>".$a->Code."</td>";
        $tr .= "<td>".date("Y-m-d",strtotime($a->Date))."</td>";
        $tr .= "<td>".$a->SalesName."</td>";
        $tr .= "<td>".$a->CustomerName."</td>";
        if($page == "sales_visiting_time"):
            $tr .= "<td>".$a->CustomerAddress."</td>";
            $tr .= "<td>".$a->CheckInAddress."</td>";
            $tr .= "<td>".$a->CheckOutAddress."</td>";
            $tr .= "<td>".$CheckIn."</td>";
            $tr .= "<td>".$CheckOut."</td>";
            $tr .= "<td>".$duration."</td>";

            if($this->session->ParentID>0):
            $tr .= "<td>".$a->nama."</td>";
            endif;

        elseif($page == "sales_visiting_remark"):
            $tr .= "<td>".$a->Remark."</td>";
            $tr .= "<td>".$a->RemarkSales."</td>";

            if($this->session->ParentID>0):
            $tr .= "<td>".$a->nama."</td>";
            endif;
            
        endif;
        $tr .= "</tr>";
        echo $tr;
    endforeach;
endif;
?>
</tbody>
</table>
<?php if($this->input->get("cetak") == "pdf" || $this->input->get("cetak") == "print"): ?>
    <table class="table table-hover dataTable table-striped width-full" border>
        <tbody align="left">
            <tr>
                <th>Total Route</th>
                <td>:</td>
                <td><?= $total_route ?></td>
            </tr>
            <tr>
                <th>Total Planned Route</th>
                <td>:</td>
                <td><?= $total_planning ?></td>
            </tr>
            <tr>
                <th>Total Missed Route</th>
                <td>:</td>
                <td><?= $total_route_miss ?></td>
            </tr>
            <tr>
                <th>Total Unplanned Route</th>
                <td>:</td>
                <td><?= $total_not_planning ?></td>
            </tr>
        </tbody>
    </table>
<?php endif; ?>