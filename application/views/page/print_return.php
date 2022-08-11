<!DOCTYPE html>
<html>
<head>
    <title><?= $title; ?></title>
    <?php if($cetak): 
      $this->load->view("report/report_css");
    endif;?>
    <style type="text/css">
      .vPeriode{display: none}
      .td-width-150{
        width: 150px;
      }
      .td-width-10{
        width: 10px;
      }
      .w-50{
        width: 50%;
      }
      .t-border tr td{
        font-size: 14px;
        padding:5px;
      }
      .no-border tr td{
        border: none !important;
      }
      .title-header{
        font-family: 'rockwell';
        font-size: 10pt !important;
        color: #000 !important;
        font-weight: bold !important;
        text-transform: uppercase !important;
      }
      .mb-0{
      	margin-bottom: 0px !important;
      }
    </style>
</head>
<body>
  <div class="page-receipt">
    <div class="header">
    <?php $this->load->view("report/header"); ?>
    </div>
    <div class="content">
      	<div style="padding: 10px"><center><span class="title-header">Return Transaction</span></center></div>
	      	<table style="width: 100%" class="t-border">
		        <tr>
		          <td class="w-50">
		            <table class="table no-border mb-0" data-plugin="dataTable" cellspacing="0">
		              <tr><td class="td-width-150">Return No</td><td class="td-width-10">:</td><td><?= $list->ReturNo ?></td></tr>
		              <tr><td><?= $list->idtxt ?></td><td class="td-width-10">:</td><td><?= $list->id ?></td></tr>
		            </table>
		          </td>
		          <td class="w-50">
		            <table class="table no-border mb-0" data-plugin="dataTable" cellspacing="0">
		              <tr><td class="td-width-150">Date</td><td class="td-width-10">:</td><td><?= $list->Date ?></td></tr>
		              <tr><td><?= $list->customertxt ?></td><td class="td-width-10">:</td><td><?= $this->main->checkvalueprint($list->vendorName) ?></td></tr>
		            </table>
		          </td>
		        </tr>
      		</table>
      		<table id="table" class="table table-hover dataTable table-striped width-full" data-plugin="dataTable" cellspacing="0" width="100%">
	 			<thead>
	 				<tr>
	 					<th>No</th>
	 					<th>Product Code</th>
	 					<th>Name</th>
	 					<th>Qty</th>
	 					<th>Unit</th>
	 					<th>Conv</th>
	 					<th>Price</th>
	 					<th>Total</th>
	 					<th>Remark</th>
	 				</tr>
	 			</thead>
	 			<tbody>
	 				<?php 
	 					foreach ($detail as $k => $v) {
	 						$no = $k + 1;
	 						echo '<tr>';
	 						echo '<td>'.$no.'</td>';
	 						echo '<td>'.$v->product_code.'</td>';
	 						echo '<td>'.$v->product_name.'</td>';
	 						echo '<td>'.$v->Qty.'</td>';
	 						echo '<td>'.$v->unit_name.'</td>';
	 						echo '<td>'.$v->Conversion.'</td>';
	 						echo '<td>'.$v->Price.'</td>';
	 						echo '<td>'.$v->Total.'</td>';
	 						echo '<td>'.$v->Remark.'</td>';
	 						echo '</tr>';
	 					}
	 				?>
	 			</tbody>
 			</table>
 			<table style="width: 100%;margin-bottom: 10px" class="t-border">
		        <tr>
		          <td class="w-50">
		            <table class="table no-border mb-0" data-plugin="dataTable" cellspacing="0">
		              <tr><td class="td-width-150">Remark</td><td class="td-width-10">:</td><td><?= $list->Remark ?></td></tr>
		            </table>
		          </td>
		        </tr>
		    </table>
    	</div>
    <div>
    <?php $this->load->view("report/footer"); ?>
    </div>
  </div>
</body>
</html>