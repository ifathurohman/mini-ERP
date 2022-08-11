
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
      .w50{
        width: 50% !important;
      }
      .title-header{
        font-family: 'rockwell';
        font-size: 10pt !important;
        color: #000 !important;
        font-weight: bold !important;
        text-transform: uppercase !important;
      }
    </style>
    <?php if($page == "print"): ?>
      <style type="text/css">
        .vpage{
          display: none;
        }
      </style>
    <?php endif; ?>
</head>
<body>
  <div class="page-receipt">
    <div class="header">
    <?php $this->load->view("report/header"); ?>
    </div>
    <div class="content">
      <div style="padding: 10px"><center><span class="title-header"><?= $title2 ?></span></center></div>
      <?php if($page=="selling"): ?>
      	<table class="table" data-plugin="dataTable" cellspacing="0">
      		<tr>
      			<td class="td-width-150">Sell No</td><td class="td-width-10">:</td><td><?= $list->SellNo ?></td>
      		</tr>
          <tr>
            <td>Date</td><td>:</td><td><?= $list->Date ?></td>
          </tr>
      		<tr>
      			<td>Customer</td><td>:</td><td><?= $list->customerName ?></td>
      		</tr>
          <tr>
            <td>Sales</td><td>:</td><td><?= $list->salesName ?></td>
          </tr>
          <tr>
            <td>Delivery To</td><td>:</td><td><?= $list->DeliveryTo ?></td>
          </tr>
          <tr>
            <td>Delivery Address</td><td>:</td><td><?= $list->DeliveryAddress ?></td>
          </tr>
          <tr>
            <td>Delivery City</td><td>:</td><td><?= $list->DeliveryCity ?></td>
          </tr>
          <tr>
            <td>Delivery Province</td><td>:</td><td><?= $list->DeliveryProvince ?></td>
          </tr>
          <tr>
            <td>Delivery Cost</td><td>:</td><td><?= $this->main->currency($list->DeliveryCost) ?></td>
          </tr>
          <tr>
            <td>Delivery Date</td><td>:</td><td><?= $list->DeliveryDate ?></td>
          </tr>
          <tr>
            <td>Term (Days)</td><td>:</td><td><?= $list->Term ?></td>
          </tr>
      	</table>
      <?php elseif($page == "delivery"): ?>
        <table class="table" data-plugin="dataTable" cellspacing="0">
          <tr>
            <td class="td-width-150">Delivery No</td><td class="td-width-10">:</td><td><?= $delivery->DeliveryNo ?></td>
          </tr>
          <tr>
            <td>Delivery To</td><td>:</td><td><?= $delivery->DeliveryTo ?></td>
          </tr>
          <tr>
            <td>Sales</td><td>:</td><td><?= $delivery->salesName ?></td>
          </tr>
          <tr>
            <td>Date</td><td>:</td><td><?= $delivery->Date ?></td>
          </tr>
          <tr>
            <td>Address</td><td>:</td><td><?= $delivery->Address ?></td>
          </tr>
          <tr>
            <td>City</td><td>:</td><td><?= $delivery->City ?></td>
          </tr>
          <tr>
            <td>Province</td><td>:</td><td><?= $delivery->Province ?></td>
          </tr>
        </table>
      <?php elseif($page == "invoice"): ?>
        <table class="table" data-plugin="dataTable" cellspacing="0">
          <tr>
            <td class="td-width-150">Invocie No</td><td class="td-width-10">:</td><td><?= $invoice->InvoiceNo ?></td>
          </tr>
          <tr>
            <td>Billing To</td><td>:</td><td><?= $invoice->BillingTo ?></td>
          </tr>
          <tr>
            <td>Sales</td><td>:</td><td><?= $invoice->salesName ?></td>
          </tr>
          <tr>
            <td>Date</td><td>:</td><td><?= $invoice->Date ?></td>
          </tr>
          <tr>
            <td>Address</td><td>:</td><td><?= $invoice->Address ?></td>
          </tr>
          <tr>
            <td>City</td><td>:</td><td><?= $invoice->City ?></td>
          </tr>
          <tr>
            <td>Province</td><td>:</td><td><?= $invoice->Province ?></td>
          </tr>
        </table>
      <?php elseif($page == "print"): ?>
        <table class="table width-full" data-plugin="dataTable" cellspacing="0" width="100%">
          <tr>
            <td class="w50">
              <table style="margin-bottom: 0px">
                <tr>
                  <td class="td-width-150">Selling Code</td><td class="td-width-10">:</td><td><?= $list->SellNo ?></td>
                </tr>
                <tr>
                  <td class="td-width-150">Selling Date</td><td class="td-width-10">:</td><td><?= $list->Date ?></td>
                </tr>
                <tr>
                  <td class="td-width-150">Sales Name</td><td class="td-width-10">:</td><td><?= $list->salesName ?></td>
                </tr>
              </table>
            </td>
            <td class="w50">
              <table style="margin-bottom: 0px">
                <tr>
                  <td class="td-width-150">Customer Name</td><td class="td-width-10">:</td><td><?= $list->customerName ?></td>
                </tr>
                <tr>
                  <td class="td-width-150">Phone</td><td class="td-width-10">:</td><td><?= $list->customerPhone ?></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      <?php endif; ?>
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
            <th>Discount (%)</th>
   					<th>Sub Total</th>
   					<th>Remark</th>
            <th>Delivery Date</th>
   				</tr>
   			</thead>
   			<tbody>
   				<?php
   				foreach ($detail as $k => $v) {
   					$no = $k + 1;
            $qty = $v->Qty;
            if($list->ProductType == 1):
              $qty = 0;
            endif;
   					$item  = '<tr>';
   					$item .= '<td>'.$no.'</td>';
   					$item .= '<td>'.$v->product_code.'</td>';
   					$item .= '<td>'.$v->product_name.'</td>';
   					$item .= '<td>'.$qty.'</td>';
   					$item .= '<td>'.$v->unit_name.'</td>';
   					$item .= '<td>'.$v->Conversion.'</td>';
   					$item .= '<td>'.$v->Price.'</td>';
            $item .= '<td>'.$v->Discount.'</td>';
   					$item .= '<td>'.$v->TotalPrice.'</td>';
            $item .= '<td>'.$v->Remark.'</td>';
   					$item .= '<td>'.$v->product_delivery.'</td>';
   					$item .= '</tr>';

   					echo $item;
   				}
   				?>
   			</tbody>
 		  </table>
   		<table width="100%" style="margin: 15px">
   			<tr>
   				<td class="w50">
   					<table>
              <?php
              if($page == "selling"):
                echo 
                    '<tr>
                      <td class="td-width-150">Remark</td><td class="td-width-10">:</td><td>'.$list->Remark.'</td>
                    </tr>';
              elseif($page == "delivery"):
                echo 
                    '<tr>
                      <td class="td-width-150">Remark</td><td class="td-width-10">:</td><td>'.$delivery->Remark.'</td>
                    </tr>';
              elseif($page == "invoice"):
                echo 
                    '<tr>
                      <td class="td-width-150">Remark</td><td class="td-width-10">:</td><td>'.$invoice->Remark.'</td>
                    </tr>';
              endif;
              ?>
   					</table>
   				</td>
   				<td class="w50">
   					<table>   						
   							<tr><td class="td-width-150">Sub Total</td><td class="td-width-10">:</td><td><?= $list->Total ?></td></tr>
   							<tr><td>Tax</td><td>:</td><td><?= $list->TotalPPN ?></td></tr>
   							<tr><td>Total Discount (%)</td><td>:</td><td><?= $list->DiscountPersent ?></td></tr>
                <tr><td>Total Discount </td><td>:</td><td><?= $list->Discount ?></td></tr>
   							<tr><td>Delivery Cost </td><td>:</td><td><?= $list->DeliveryCost ?></td></tr>
   							<tr><td>Total </td><td>:</td><td><?= $list->Payment ?></td></tr>
   					</table>
   				</td>
   			</tr>
   		</table>
      <?php if($page == "print"): ?>
        <table class="table width-full" data-plugin="dataTable" cellspacing="0" width="100%" style="margin: 15px">
          <tr>
            <td class="w50">
              <table style="margin-bottom: 0px">
                <tr>
                  <td class="td-width-150">Delivery Address</td><td class="td-width-10"></td><td></td>
                </tr>
                <tr>
                  <td class="td-width-150">Customer Name</td><td class="td-width-10">:</td><td><?= $list->customerName ?></td>
                </tr>
                <tr>
                  <td class="td-width-150">Address</td><td class="td-width-10">:</td><td><?= $list->DeliveryAddress ?></td>
                </tr>
                <tr>
                  <td class="td-width-150">City</td><td class="td-width-10">:</td><td><?= $list->DeliveryCity ?></td>
                </tr>
                <tr>
                  <td class="td-width-150">Province</td><td class="td-width-10">:</td><td><?= $list->DeliveryProvince ?></td>
                </tr>
              </table>
            </td>
            <td class="w50" style="display: none">
              <table style="margin-bottom: 0px">
                <tr>
                  <td class="td-width-150">Invoice Address</td><td class="td-width-10"></td><td></td>
                </tr>
                <tr>
                  <td class="td-width-150">Customer Name</td><td class="td-width-10">:</td><td><?= $list->customerName ?></td>
                </tr>
                <tr>
                  <td class="td-width-150">Address</td><td class="td-width-10">:</td><td><?= $list->PaymentAddress ?></td>
                </tr>
                <tr>
                  <td class="td-width-150">City</td><td class="td-width-10">:</td><td><?= $list->PaymentCity ?></td>
                </tr>
                <tr>
                  <td class="td-width-150">Province</td><td class="td-width-10">:</td><td><?= $list->PaymentProvince ?></td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      <?php endif; ?>
    </div>
    <div class="vpage">
    <?php $this->load->view("report/footer"); ?>
    </div>
  </div>
</body>
</html>