<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Selling extends CI_Controller {
	var $title = 'Sales Order';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_selling",'selling');
		$this->main->cek_session();
		$this->title = $this->lang->line('lb_selling');
	}
	public function index()
	{	
		$url 		= $this->uri->segment(1); 
		$id_url 	= $this->main->id_menu($url);
		$read 		= $this->main->read($id_url);
		$ar 		= $this->main->check_parameter_module("ar","selling");
		if($read == 0 || $ar->view == 0){ redirect(); }
		$selling_tambah 				= $this->main->menu_tambah($id_url);
		if($selling_tambah > 0 and $ar->add > 0):
            $tambah = $this->main->general_button('add','Add New '.$this->title);
		else: 
			$tambah = ""; 
		endif;
		$this->main->delete_temp_sn("selling");
		#ini untuk session halaman aturan user privileges;
		$data['title']  	= $this->title;
		$data['tambah'] 	= $tambah;
		$data['modal'] 		= 'selling/modal';
		$data['modal_print']  = 'selling/modal_print';
		$data['modal_vendor'] = 'modal/modal_vendor';
		$data['page'] 		= 'selling/list';
		$data['modul'] 		= 'selling';
		$data['url_modul'] 	= $url;
		$this->load->view('index',$data);
	}

	public function ajax_list($url_modul="",$modul=""){
		$CompanyID  = $this->session->CompanyID;
		$id_url = $this->main->id_menu($url_modul);
		$edit 	= $this->main->menu_ubah($id_url);
		$delete = $this->main->menu_hapus($id_url);
		$list 	= $this->selling->get_datatables();
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		$ar 	= $this->main->check_parameter_module("ar","selling");
		foreach ($list as $a) {
			$SellNo 		= str_replace("/", "-", $a->SellNo);

			$link = "attachment/".$SellNo."?type=selling";
			$btn_edit 		= '';
			$btn_cancel 	= '';
			$btn_view 		= '';
			$btn_delivery 	= '';
			$label_paid 	= ' <oren>Open</oren>';
			$label_product_type = $this->main->label_product($a->ProductType,"",$SellNo);
			$btn_attachemnt = $this->main->button_attach_dropdown($SellNo,$link,"selling");
			$status 		= $this->main->label_active($a->Status,"",$SellNo);

			$btn_print 		= $this->main->button_action_dropdown("print", $SellNo);
			if($edit>0 and $ar->add > 0):
				$btn_view 		= $this->main->button_action_dropdown("view", $SellNo);
				$btn_edit 		= $this->main->button_action_dropdown("edit", $SellNo);
			endif;

			if($delete>0 and $ar->add > 0):
				if($a->Status == 1):
					$btn_cancel = $this->main->button_action_dropdown("cancel", $SellNo);
				else:
					$btn_edit = '';
				endif;
			endif;


			$cek_return 	= $this->db->count_all("AP_Retur where SellNo = '$a->SellNo' and CompanyID = '$CompanyID' and Status = '1'");

			#cek dari delivery
			$cek_delivery_det 	= $this->db->count_all("PS_Delivery_Det as dt left join PS_Delivery as d 
				on dt.DeliveryNo = d.DeliveryNo and dt.CompanyID = d.CompanyID
				where dt.CompanyID = '$CompanyID' and dt.SellNo = '$a->SellNo' and d.Status = '1'");
			$cek_delivery 		= $this->db->count_all("PS_Delivery_Det where CompanyID = '$CompanyID' and SellNo = '$a->SellNo'");
			$cek_inv_del 		= $this->db->count_all("PS_Invoice_Detail as ps_id 
				left join PS_Delivery as ps_d on ps_d.DeliveryNo = ps_id.DeliveryNo and ps_d.CompanyID = ps_id.CompanyID 
				left join PS_Delivery_Det as ps_dd on ps_dd.DeliveryNo = ps_d.DeliveryNo and ps_dd.CompanyID = ps_d.CompanyID
				where ps_dd.SellNo = '$a->SellNo' and ps_dd.CompanyID = '$CompanyID' ");
			$cek_inv_del_det 	= $this->db->count_all("PS_Invoice_Detail as ps_id
				left join PS_Invoice as ps_i on ps_i.InvoiceNo = ps_id.InvoiceNo and ps_i.CompanyID and ps_id.CompanyID
				left join PS_Delivery as ps_d on ps_d.DeliveryNo = ps_id.DeliveryNo and ps_d.CompanyID = ps_id.CompanyID
				left join PS_Delivery_Det as ps_dd on ps_dd.DeliveryNo = ps_d.DeliveryNo and ps_dd.CompanyID = ps_d.CompanyID
				where ps_dd.SellNo = '$a->SellNo' and ps_i.Status = '1' and ps_dd.CompanyID = '$CompanyID'");
			$cek_pay_del 		= $this->db->count_all("PS_Payment_Detail as ps_pay_det
				left join PS_Payment as ps_pay on ps_pay.PaymentNo = ps_pay_det.PaymentNo and ps_pay.CompanyID = ps_pay_det.CompanyID
				left join PS_Invoice_Detail as ps_id on ps_pay_det.InvoiceNo = ps_id.InvoiceNo and ps_pay_det.CompanyID = ps_id.CompanyID
				left join PS_Delivery_Det 	as ps_dd on ps_id.DeliveryNo = ps_dd.DeliveryNo and ps_dd.CompanyID = ps_id.CompanyID
				where ps_pay_det.CompanyID = '$CompanyID' and ps_pay.Status = '1' and ps_dd.SellNo = '$a->SellNo'
				");

			#cek dari invoice sell
			$cek_inv_sel  		= $this->db->count_all("PS_Invoice_Detail where CompanyID = '$CompanyID' and SellNo = '$a->SellNo'");
			$cek_inv_sel_det 	= $this->db->count_all("PS_Invoice_Detail as ps_id 
				left join PS_Invoice as ps_i on ps_i.InvoiceNo = ps_id.InvoiceNo and ps_i.CompanyID = ps_id.CompanyID
				where ps_id.CompanyID = '$CompanyID' and ps_i.Status = '1' and ps_id.SellNo = '$a->SellNo'");
			$cek_pay_sell 		= $this->db->count_all("PS_Payment_Detail as ps_pay_det
				left join PS_Payment as ps_pay on ps_pay.PaymentNo = ps_pay_det.PaymentNo and ps_pay.CompanyID = ps_pay_det.CompanyID
				left join PS_Invoice_Detail as ps_id on ps_pay_det.InvoiceNo = ps_id.InvoiceNo and ps_id.CompanyID = ps_pay_det.CompanyID
				where ps_pay_det.CompanyID = '$CompanyID' and ps_pay.Status = '1' and ps_id.SellNo = '$a->SellNo'
				");
			
			// pengecekannya
			# cek delivery
			if($cek_delivery>0 && $cek_inv_del_det <= 0):
				if($cek_delivery_det>0):
					$btn_edit = '';
					$btn_cancel = '';
					$label_paid = ' <biru>'.$this->lang->line('lb_delivery1').'</biru>';
				endif;
			endif;
			#cek invoice
			if($cek_inv_sel>0 || $cek_inv_del >0):
				if($cek_inv_del_det>0 and $cek_pay_del<= 0 or $cek_inv_sel_det>0 and $cek_pay_sell <= 0):
					$btn_edit = '';
					$btn_cancel = '';
					$label_paid = ' <merah>'.$this->lang->line('lb_unpaid').'</merah>';
				elseif($cek_pay_del >0 || $cek_pay_sell > 0):
					$btn_edit = '';
					$btn_cancel = '';
					$label_paid = ' <hijau>'.$this->lang->line('lb_paid').'</hijau>';
				endif;
			endif;

			if($a->DeliveryParameter == 1):
				$btn_delivery 	= $this->main->button_action_dropdown("delivery",$SellNo,$a->DeliveryStatus);
				$label_paid .= ' <i class="icon fa-truck" title="'.$this->lang->line('lb_delivery1').'" aria-hidden="true"></i>';
			endif;

			// return
			if($cek_return>0):
				$btn_cancel = '';
				$btn_edit  	= '';
				$btn_delivery = '';
			endif;

			if($a->Status != 1):
				$btn_delivery = '';
			endif;

			$button  = '<div class="btn-group pointer">';
			$button .= '<div data-toggle="dropdown" aria-expanded="true">';
			$button .= '<i class="fal fa-cog"></i> <span class="caret"></span> </div>';
			$button .= '<ul class="dropdown-menu animate">';
			$button .= $btn_view;
			$button .= $btn_print;
			$button .= $btn_edit;
            $button .= $btn_cancel;
            $button .= $btn_attachemnt;
            $button .= $btn_delivery;
	        $button .= ' </ul>';
	        $button .= '</div>';

			// $button  = '<div class="btn-group btn-group-xs" aria-label="Basic example" role="group">';
   //          $button .= $btn_view;
   //          $button .= $btn_edit;
   //          $button .= $btn_cancel;
   //          $button .= $btn_attachemnt;
   //          $button .= $btn;
   //          $button .= '</div>';

	        $btn_action 	= $this->main->button_action("code", $SellNo,$a->SellNo.$label_paid);
	        $vendor 		= $this->main->button_action("general_onclick","redirect_post('partner','".$a->VendorID."')",$a->customerName);
	        if($a->BranchID):
	        	$branch 		= $this->main->button_action("general_onclick","redirect_post('store-device-management','".$a->BranchID."')",$a->branchName);
	        else:
	        	$branch 		= "";
	        endif;
	        
	        $qty = $a->Qty;
	        if($a->ProductType == 1):
	        	$qty = 0;
	        endif;

			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $btn_action;
			$row[] 	= $a->Date; 
			$row[] 	= $vendor;
			$row[] 	= $branch;
			$row[] 	= $status."<br>".$label_product_type; 
			$row[] 	= $this->main->qty($qty); 
			$row[] 	= $this->main->currency($a->Payment); 
			// $row[] 	= $button;
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->selling->count_all(),
			"recordsFiltered" => $this->selling->count_filtered(),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function save(){
		$this->_validate_product();
		$crud  			= $this->input->post('crud');
		$product_status = $this->input->post("product_status");
		$ckPPN 			= $this->input->post('ckPPN');
		$CustomerID 	= $this->input->post('CustomerID');
		$SalesID 		= $this->input->post('SalesID');
		$Date 			= $this->input->post('Date');
		$Date 			= date($Date." H:i:s");
		$NoPo 			= $this->input->post('NoPo');
		$sell_remark  	= $this->input->post('sell_remark');
		$SubTotal 		= $this->input->post('SubTotal');
		$DiscountRp 	= $this->input->post('DiscountRp');
		$Discount 		= $this->input->post('Discount');
		$PPN 			= $this->input->post('PPN');
		$TotalPPN 		= $this->input->post('TotalPPN');
		$Total 			= $this->input->post('Total');
		$SellingNo 		= $this->input->post('SellingNo');
		$CompanyID 		= $this->session->CompanyID;
		$DeliveryCost 	= $this->input->post('Ongkir');
		$DeliveryDate 	= $this->input->post('DeliveryDate');
		$Term 			= $this->input->post('Term');
		$BranchID 		= $this->input->post("BranchID");
		$BranchID 		= explode("-", $BranchID)[0];

		$rowid 		 		= $this->input->post('rowid');
		$productid       	= $this->input->post('productid');
		// $product_code       = $this->input->post('product_code');
		$product_unitid    	= $this->input->post('product_unitid');
		// $product_unit      	= $this->input->post('product_unit');
		$product_konv      	= $this->input->post('product_konv');
		$product_qty       	= $this->input->post('product_qty');
		$product_price 		= $this->input->post('product_price');
		$product_total 		= $this->input->post('product_total');
		$product_remark 	= $this->input->post('product_remark');
		$product_discount 	= $this->input->post('product_discount');
		$product_type 		= $this->input->post('product_type');
		$product_delivery 	= $this->input->post('product_delivery');
		$selldet 			= $this->input->post('selldet');

		// delivery
		$delAddress 	= $this->input->post('delAddress');
		$delCity 		= $this->input->post('delCity');
		$delProvince 	= $this->input->post('delProvince');
		$DeliveryTo 	= $this->input->post('DeliveryTo');

		// invoice
		$invAddress 	= $this->input->post('invAddress');
		$invCity 		= $this->input->post('invCity');
		$invProvince 	= $this->input->post('invProvince');
		$BillingTo 		= $this->input->post('BillingTo');

		// serial post
		$dt_serial 	  = $this->input->post("dt_serial");
		$dt_serialkey = $this->input->post("dt_serialkey");
		$dt_serialauto = $this->input->post("dt_serialauto");
		$dt_serial    = json_decode($dt_serial);
		$dt_serialkey = json_decode($dt_serialkey);
		$dt_serialauto = json_decode($dt_serialauto);

		if(!$DeliveryDate):
			$DeliveryDate = null;
		endif;
		$ar 			= $this->main->check_parameter_module("ar","delivery");
		$inventory 		= $this->main->check_parameter_module("inventory","inventory");

		$module_data = array(
			"stock"		=> $inventory->add,
		);

		if($product_status == 1):
			$module_data = array(
				"stock"		=> 0,
			);
		endif;

		$last_code 		= $this->main->get_last_companyID();
		$data_PS_Sell 	= array(
			"CompanyID"			=> $this->session->CompanyID,
			"Payment"			=> $this->main->checkDuitInput($Total),
			"Total"				=> $this->main->checkDuitInput($SubTotal),
			"Remark" 			=> $sell_remark,
			'PPN' 				=> $PPN,
			'TotalPPN' 			=> $this->main->checkDuitInput($TotalPPN),
			'Discount' 			=> $this->main->checkDuitInput($DiscountRp),
			'DiscountPersent' 	=> $Discount,
			'NoPOKonsumen'		=> $NoPo,
			'DeliveryTo' 		=> $DeliveryTo,
			'DeliveryAddress'	=> $delAddress,
			'DeliveryCity'		=> $delCity,
			'DeliveryProvince'	=> $delProvince,
			'DeliveryDate'		=> $DeliveryDate,
			'DeliveryCost'		=> $this->main->checkDuitInput($DeliveryCost),
			'PaymentTo'			=> $BillingTo,
			'PaymentAddress'	=> $invAddress,
			'PaymentCity'		=> $invCity,
			'PaymentProvince'	=> $invProvince,
			'Term'				=> $this->main->checkDuitInput($Term),
			"DeliveryParameter"	=> $ar->add,
			"Module"			=> json_encode($module_data),
			"Date"				=> $Date,
			"ProductType"		=> $product_status,
			"Mobile"			=> 0,
		);
		if($ckPPN):
			$data_PS_Sell['Tax'] = 1;
		else:
			$data_PS_Sell['Tax'] = 0;
		endif;
		if($CustomerID != ''):
			$CustomerID = explode('-', $CustomerID);
			$data_PS_Sell['VendorID'] = $CustomerID[0];
		endif;
		if($SalesID != ''):
			$SalesID = explode('-', $SalesID);
			$data_PS_Sell['SalesID'] = $SalesID[0];
		endif;
		if($BranchID):
			$data_PS_Sell['BranchID'] = $BranchID;
		endif;

		if($crud == "insert"):
			$sellNo 		= $this->main->selling_generate();
			$data_PS_Sell['SellNo'] = $sellNo;
			$data_PS_Sell['Status']	= 1;
			$data_PS_Sell['Paid']	= 0;
			$this->selling->save($data_PS_Sell);
			$header 	 = $this->main->get_one_column("PS_Sell","Module,DeliveryParameter", array("SellNo" => $sellNo, "CompanyID" => $CompanyID));
			$module_data = $this->main->check_module_stock($header->Module);
		else:
			$sellNo 		= $SellingNo;
			$header 	 = $this->main->get_one_column("PS_Sell","Module,DeliveryParameter", array("SellNo" => $sellNo, "CompanyID" => $CompanyID));
			$module_data 	= $this->main->check_module_stock($header->Module);
			if($header->DeliveryParameter == 0):
				if($module_data->stock>0):
					$this->calculationQty($sellNo,"tambah");
				endif;
			endif;
			$this->selling->update(array("SellNo"=>$sellNo, "CompanyID" => $CompanyID), $data_PS_Sell);
		endif;

		// sell det
		$selldetid  	= array();
		foreach($productid as $key => $v):
			if($productid[$key]):
				if($product_status == 1):
					$xprice 		= $this->main->checkDuitInput($product_price[$key]);
					$xdiscount 		= $this->main->checkDuitInput($product_discount[$key]);
					$DiscountValue 	= $this->main->PersenttoRp($xprice,$xdiscount);
					$TotalPrice 	= $xprice - $DiscountValue;

					$xunitid 		= null;
					$xConversion 	= 1;
					$xqty 			= 1;
				else:
					$xqty 			= $this->main->checkDuitInput($product_qty[$key]);
					$xprice 		= $this->main->checkDuitInput($product_price[$key]);
					$xtotal_product = $xprice * $xqty;
					$xdiscount 		= $this->main->checkDuitInput($product_discount[$key]);
					$DiscountValue 	= $this->main->PersenttoRp($xtotal_product,$xdiscount);
					$TotalPrice 	= $xtotal_product - $DiscountValue;

					$xunitid 		= $product_unitid[$key];
					$xConversion 	= $product_konv[$key];
				endif;
				
				$DeliveryDate = $product_delivery[$key];
				if(!$DeliveryDate):
					$DeliveryDate = null;
				endif;

				$average  = $this->api->get_AveragePrice($productid[$key],$BranchID);

				// sell det
				$data_detail = array(
					"CompanyID" 	=> $this->session->CompanyID,
					"SellNo" 		=> $sellNo,
					"ProductID" 	=> $productid[$key],
					// "UnitID" 		=> $xunitid,
					"Uom" 			=> $xunitid,
					"Qty" 			=> $xqty,
					"Type" 			=> $product_type[$key],
					"Conversion" 	=> $xConversion,
					"Price" 		=> $this->main->checkDuitInput($product_price[$key]),
					"TotalPrice" 	=> $TotalPrice,
					"Discount" 		=> $product_discount[$key],
					"DiscountValue"	=> $DiscountValue,
					"Remark" 		=> $product_remark[$key],
					"DeliveryDate"	=> $DeliveryDate,
					"Cost"			=> $average,
				);

				if($selldet[$key] == ''):
					$SellDet 		= $this->main->selling_detail_generate();
					$data_detail['SellDet'] = $SellDet;
					$data_detail['Status']  = 1;
					$this->selling->save_det($data_detail);
					if($header->DeliveryParameter <= 0 && $product_status == 0):
						if($module_data->stock>0):
							$xqty2 = $xqty * $xConversion;
							$this->kurangQty($productid[$key],$xqty2,$BranchID);
						endif;
					endif;
					array_push($selldetid, $SellDet);
				else:
					$SellDet = $selldet[$key];
					$where = array('SellDet' => $SellDet,'CompanyID' => $CompanyID);
					$this->selling->update_det($where,$data_detail);
					if($header->DeliveryParameter <= 0 && $product_status == 0):
						if($module_data->stock):
							$xqty2 = $xqty * $xConversion;
							$this->kurangQty($productid[$key],$xqty2,$BranchID);
						endif;
					endif;
					array_push($selldetid, $SellDet);
				endif;

				// simpan serial number
				$xtype = $product_type[$key];
				if($xtype == 2 && $product_status == 0):
					$arrkey = array_keys($dt_serialkey,$rowid[$key]);
					foreach ($arrkey as $key2 => $value_key) {
						$no = $key2 + 1;
						if($xqty>=$no):
							$sn = $dt_serial[$value_key];
							
							// insert ke table PS_Sell_Detail_SN
							$data_serial = array(
								"CompanyID"		=> $CompanyID,
								"Status"		=> 1,
								"Qty"			=> 1,
								"ProductID"		=> $productid[$key],
								"SellNo"		=> $sellNo,
								"SellDet" 		=> $SellDet,
								"User_Add"		=> $this->session->NAMA,
								"Date_Add"		=> date("Y-m-d H:i:s"),
								"SN"			=> $sn,
							);
							$this->db->insert("PS_Sell_Detail_SN", $data_serial);

							// update ke table PS_Product_Serial
							$data_serial = array(
								"Status"		=> 0,
								"User_Ch"		=> $this->session->NAMA,
								"Date_Ch"		=> date("Y-m-d H:i:s"),
							);
							$this->db->where("BranchID", $BranchID);
							$this->db->where("CompanyID", $CompanyID);
							$this->db->where("ProductID", $productid[$key]);
							$this->db->where("SerialNo", $sn);
							$this->db->update("PS_Product_Serial", $data_serial);
						endif;
					}
				endif;

			endif;
		endforeach;

		$this->check_sell_det($selldetid,$sellNo);
		$this->main->delete_temp_sn("selling");

		// generate journal
		// tidak delivery dan mengurangi stock
		if($header->DeliveryParameter<=0):
			$this->db->query("CALL run_generate_jurnal('UPDATE', '$sellNo', 'suratjalan_so', '$CompanyID')");
		endif;

		$res = array(
			"status" 	=> true,
			"message"	=> $this->lang->line('lb_success'),
			"ID"		=> $sellNo,
		);

		$this->main->echoJson($res);
	}

	private function _validate_product()
	{
		$this->main->validate_update();
		$this->main->validate_modlue_add("ar","ar");
		$data 			= array();
		$arrProductID 	= array();
		$data['status'] = TRUE;

		// $data['inputerror'][] 	= "";
		// $data['error_string'][] = '';
		// $data['list'][] 		= '';
		// $data['tab'][] 			= '';
		// $data['ara'] 			= $this->input->post();
		// $data['status'] 		= FALSE;

		// $this->main->echoJson($data);
		// exit();

		$CompanyID  	= $this->session->CompanyID;
		$NegativeStock 	= $this->session->NegativeStock;

		$BranchID 		= $this->input->post('BranchID');
		$BranchID 		= explode("-", $BranchID)[0];
		
		$product_status = $this->input->post("product_status");
		$productid 	 	= $this->input->post('productid');
		$product_qty 	= $this->input->post('product_qty');
		$product_type 	= $this->input->post('product_type');
		$product_konv   = $this->input->post('product_konv');
		$rowid 		 	= $this->input->post('rowid');
		$warning 	 	= $this->input->post('warning');

		// serial post
		$dt_serial 	  = $this->input->post("dt_serial");
		$dt_serialkey = $this->input->post("dt_serialkey");
		$dt_serialauto = $this->input->post("dt_serialauto");
		$dt_serial    = json_decode($dt_serial);
		$dt_serialkey = json_decode($dt_serialkey);
		$dt_serialauto = json_decode($dt_serialauto);

		foreach ($productid as $key => $v) {
			if(!in_array($v, $arrProductID)):
				array_push($arrProductID, $v);
			endif;
		}

		if($BranchID):
			$data_qty 	= $this->api->product_branch($BranchID,$arrProductID,"array");
		else:
			$data_qty	= $this->main->product_detail($CompanyID,$arrProductID,"array");
		endif;

		$no = 1;
		if(count($productid)>0){
			$status_product = false;
			foreach($productid as $key => $v):
				if($productid[$key] == ''):
					$cek = 0;
				else:
					$cek = $this->db->count_all("ps_product where CompanyID = '$CompanyID' and ProductID = '$productid[$key]'");
				endif;
				if($cek>0):
					$status_product = true;
				endif;
			endforeach;
			if(!$status_product):
				$data['inputerror'][] 	= '';
				$data['error_string'][] = '';
				$data['list'][] 		= '';
				$data['tab'][] 			= '';
				$data['message'] 		= $this->lang->line('lb_select_product_empty');
				$data['status'] 		= FALSE;
				echo json_encode($data);
				exit();
			endif;
		}
		if(count($productid) > 0 && $product_status == 0){
			$status_product = true;
			foreach($productid as $key => $v):
				$rowID 		= $rowid[$key];
				$xqty 		= $this->main->checkDuitInput($product_qty[$key]);
				$xqty2 		= $xqty * $product_konv[$key];
				if($productid[$key]):
					if($xqty <= 0 || empty($xqty)):
						$status_product = false;
						$data['inputerror'][] 	= ".".$rowID;
						$data['error_string'][] = ".".$rowID;
						$data['list'][] 		= 'list';
						$data['tab'][] 			= '';
						$data['message'] 		= $this->lang->line('lb_product_qty_empty');
						$data['status'] 		= FALSE;

					// pengecekan serial number
					else:
						$xtype = $product_type[$key];
						$temp_data  = $this->api->temp_serial("selling","",$rowid[$key],$productid[$key],"class");
						if($xtype == 2):
							$arrkey = array_keys($dt_serialkey,$rowid[$key]);
							if($xqty>count($arrkey)):
								$data['inputerror'][] 	= ".".$rowid[$key];
								$data['error_string'][] = $this->lang->line('lb_serial_empty');
								$data['list'][] 		= 'list';
								$data['tab'][] 			= '';
								$data['message'] 		= $this->lang->line('lb_serial_empty');
								$data['status'] 		= FALSE;
								$status_product 		= false;
							else:
								$status_sn 	= true;
								$message_sn = '';
								foreach ($arrkey as $key2 => $value_key) {
									$no = $key2 + 1;
									if($xqty>=$no):
										if($dt_serial[$value_key] == ''):
											$status_sn = false;
											$message_sn = $this->lang->line('lb_serial_empty');
										else:
											$temp_sn = array_search($dt_serial[$value_key], array_column($temp_data, 'SN'));
                							$temp_sn_length = strlen($temp_sn);

                							if($temp_sn_length<=0):
                								$status_sn = false;
												$message_sn = $this->lang->line('lb_serial_not_found');
                							endif;
										endif;
									endif;
								}
								if(!$status_sn):
									$data['inputerror'][] 	= ".".$rowid[$key];
									$data['error_string'][] = $message_sn;
									$data['list'][] 		= 'list';
									$data['tab'][] 			= '';
									$data['status'] 		= FALSE;
									$data['message'] 		= $message_sn;
									$status_product 		= false;
								endif;
							endif;
						endif;
					endif;
				endif;
			endforeach;
			if(!$status_product):
				echo json_encode($data);
				exit();
			endif;
		}

		if(count($productid) > 0 && $product_status == 0){
			foreach($productid as $key => $v):
				$ProductID 	= $productid[$key];
				$Qty 		= $this->main->checkDuitInput($product_qty[$key]);
				$Qty 		= $Qty * $product_konv[$key];
				$rowID 		= $rowid[$key];
				if($ProductID):
					$inventory 		= $this->main->check_parameter_module("inventory","inventory");
					if($inventory->add>0):
						$check_qty 	= $this->check_qty($data_qty,$ProductID,$Qty);
						$cek 		= $check_qty['status'];
						$data_qty 	= $check_qty['data'];
					else:
						$cek 		= TRUE;
					endif;
					
					// $cek = $this->db->count_all("ps_product where ProductID = '$ProductID' AND CompanyID = '$CompanyID' and Qty<'$Qty'");
					// if($cek>0):
					if(!$cek):
						if($NegativeStock == "warning" AND $warning != "warning"):
							$data['inputerror'][] 	= ".".$rowID;
							$data['error_string'][] = ".".$rowID;
							$data['list'][] 		= 'list';
							$data['tab'][] 			= '';
							$data['message'] 		= $this->lang->line('lb_product_preorder');
							$data['negative'] 		= $NegativeStock;
							$data['status'] 		= FALSE;
						elseif($NegativeStock == 'block'):
							$data['inputerror'][] 	= ".".$rowID;
							$data['error_string'][] = ".".$rowID;
							$data['list'][] 		= 'list';
							$data['tab'][] 			= '';
							$data['message'] 		= $this->lang->line('lb_product_qty_stock');
							$data['negative'] 		= $NegativeStock;
							$data['status'] 		= FALSE;
						endif;
					endif;
				endif;
			endforeach;
		}

		$ck_branch 		= $this->db->count_all("Branch where CompanyID = '$CompanyID' and BranchID = '$BranchID' and Active = '1'");
		if($ck_branch<=0):
			$data['inputerror'][] 	= 'BranchID';
			$data['error_string'][] = '';
			$data['list'][] 		= '';
			$data['tab'][] 			= 'sell';
			$data['status'] 		= FALSE;
			$data['message'] 		= $this->lang->line('lb_data_not_found');
			echo json_encode($data);
			exit();
		endif;

		// if($this->input->post('SalesID') == ''):
		// 	$data['inputerror'][] 	= 'SalesID';
		// 	$data['error_string'][] = 'Sales cannot be null';
		// 	$data['list'][] 		= '';
		// 	$data['tab'][] 			= 'sell';
		// 	$data['status'] 		= FALSE;
		// endif;

		if($this->input->post('CustomerID') == ''):
			$data['inputerror'][] 	= 'CustomerID';
			$data['error_string'][] = $this->lang->line('lb_customer_empty');
			$data['list'][] 		= '';
			$data['tab'][] 			= 'sell';
			$data['status'] 		= FALSE;
		endif;

		// invoice
		// $BillingTo 		= $this->input->post('BillingTo');
		// $invAddress 	= $this->input->post('invAddress');
		// $invCity 		= $this->input->post('invCity');
		// $invProvince 	= $this->input->post('invProvince');
		// if($BillingTo == ""):
		// 	$data['inputerror'][] 	= 'BillingTo';
		// 	$data['error_string'][] = 'BillingTo cannot be null';
		// 	$data['list'][] 		= '';
		// 	$data['tab'][] 			= 'invoice';
		// 	$data['status'] 		= FALSE;
		// endif;
		// if($invAddress == ""):
		// 	$data['inputerror'][] 	= 'invAddress';
		// 	$data['error_string'][] = $this->lang->line('lb_address_empty');
		// 	$data['list'][] 		= '';
		// 	$data['tab'][] 			= 'invoice';
		// 	$data['status'] 		= FALSE;
		// endif;
		// if($invCity == ""):
		// 	$data['inputerror'][] 	= 'invCity';
		// 	$data['error_string'][] = 'City cannot be null';
		// 	$data['list'][] 		= '';
		// 	$data['tab'][] 			= 'invoice';
		// 	$data['status'] 		= FALSE;
		// endif;
		// if($invProvince == ""):
		// 	$data['inputerror'][] 	= 'invProvince';
		// 	$data['error_string'][] = 'Province cannot be null';
		// 	$data['list'][] 		= '';
		// 	$data['tab'][] 			= 'invoice';
		// 	$data['status'] 		= FALSE;
		// endif;

		// delivery
		$DeliveryTo 	= $this->input->post('DeliveryTo');
		$delAddress 	= $this->input->post('delAddress');
		$delCity 		= $this->input->post('delCity');
		$delProvince 	= $this->input->post('delProvince');

		if($DeliveryTo == ""):
			$data['inputerror'][] 	= 'DeliveryTo';
			$data['error_string'][] = $this->lang->line('lb_delivery_to_empty');
			$data['list'][] 		= '';
			$data['tab'][] 			= 'delivery';
			$data['status'] 		= FALSE;
		endif;
		if($delAddress == ""):
			$data['inputerror'][] 	= 'delAddress';
			$data['error_string'][] = $this->lang->line('lb_address_empty');
			$data['list'][] 		= '';
			$data['tab'][] 			= 'delivery';
			$data['status'] 		= FALSE;
		endif;
		// if($delCity == ""):
		// 	$data['inputerror'][] 	= 'delCity';
		// 	$data['error_string'][] = 'City cannot be null';
		// 	$data['list'][] 		= '';
		// 	$data['tab'][] 			= 'delivery';
		// 	$data['status'] 		= FALSE;
		// endif;
		// if($delProvince == ""):
		// 	$data['inputerror'][] 	= 'delProvince';
		// 	$data['error_string'][] = 'Province cannot be null';
		// 	$data['list'][] 		= '';
		// 	$data['tab'][] 			= 'delivery';
		// 	$data['status'] 		= FALSE;
		// endif;

		if($data['status'] === FALSE)
        {
        	$data['aye'] = 'AA';
            header('Content-Type: application/json');
            echo json_encode($data,JSON_PRETTY_PRINT);  
            exit();
        }

	}

	private function check_qty($data,$ProductID,$Qty){
		$status  	= TRUE;
		$data_qty 	= array();
		foreach ($data as $key => $v) {
			if($v->ProductID == $ProductID):
				if($Qty>$v->Qty):
					$status = FALSE;
				endif;
				$qty = $v->Qty - $Qty;
				$v->Qty = $qty;
			endif;
			array_push($data_qty, $v);
		}
		$data = array(
			'status'	=> $status,
			'data'		=> $data_qty,
		);
		return $data;
	}

	public function cetak($id){
		$id 		= str_replace("-", "/", $id);
		// $this->main->default_template("selling");
		$cetak 		= $this->input->get("cetak");
		$position 	= $this->input->get("position");
		$page 		= $this->input->get("page");
		$code 		= "Selling".$id;

		$nama_laporan   = "";
        $datacompany    = $this->main->company("api");
        $company_name   = $datacompany->nama;
        $company_imgbin = $datacompany->img_bin;
        $company_imgurl = $datacompany->img_url;
        $logo           = base_url($company_imgurl);

		$list  		= array();
		$detail 	= array();
		if($page == "selling" || $page == "struk" || $page == "delivery" || $page == "invoice" || $page == "payment" || $page == "return" || $page == "print"):
			if($page == "payment" || $page == "return" || $page == "struk"):
			else:
				$list  		= $this->selling->get_by_id($id);
				$detail 	= $this->selling->get_by_detail($id);
				$data["detail"]			= $detail;
			endif;
			
			if($page == "selling"):
				$data['title']  		= 'print selling';
				$data['title2'] 		= 'Sales Order';
			elseif($page == "delivery"):
				$delivery = $this->api->delivery_detail($id,"selling");
				$data['title'] 			= 'print delivery';
				$data['title2'] 		= 'Delivery';
				$data['delivery']		= $delivery;
			elseif($page == "invoice"):
				$invoice = $this->api->invoice_detail($id,"selling");
				$data['title']			= 'print invoice';
				$data['title2'] 		= 'Invoice';
				$data['invoice'] 		= $invoice;
			elseif($page == "payment"):
				$list = $this->api->payment_detail($id,"selling");
				$code = "Payment".$id;
				$data['title']			= 'print payment';
			elseif($page == "return"):
				$list 	= $this->api->return_detail($id,"selling");
				$detail = $this->api->return_by_detail($id,"selling");
				$code 	= "Retun".$id;
				$data['title']			= 'print return';
				$data["detail"]			= $detail;
			elseif($page == "struk"):
				$list  	= $this->api->payment_detail($id,"selling");
				$sell  	= $this->selling->get_by_id($list->Code);
				$detail = $this->selling->get_by_detail($list->Code);
				$data['title']			= 'print receipt';
				$data["detail"]			= $detail;
				$data['sell'] 			= $sell;
			else:
				$data['title']  		= 'print';
				$data['title2'] 		= $this->title;
			endif;
			
			$data['page'] 			= $page;
			$data["cetak"]			= $cetak;
			$data['list']			= $list;
			$data["company_name"]   = $company_name;
			$data["nama_laporan"]   = $nama_laporan;
        	$data["logo"]           = $logo;
        	$data["company"]		= $datacompany;
        	if($page == "payment"):
        		$this->load->view('page/print_payment',$data);
        	elseif($page == "struk"):
        		$this->load->view('page/struk',$data);
        	elseif($page == "return"):
        		$this->load->view('page/print_return',$data);
        	else:
        		if($page == "print"):
		    		$data['template'] 	= $this->main->get_default_template("selling");
		    		$this->load->view('selling/template',$data);
		    	else:
		    		$this->load->view('selling/view',$data);
		    	endif;
        	endif;
			
			if($cetak == "pdf"):
				$this->load->library('dompdf_gen'); 
				$html = $this->output->get_output();
				if($position == "landscape"):
		   	   		$this->dompdf->set_paper('legal', 'landscape');
		   	   	else:
		   	   		$this->dompdf->set_paper('legal', 'portrait');
		   	   	endif;

		    	$this->dompdf->load_html($html);
		    	$this->dompdf->render();
				$this->dompdf->stream($code.".pdf",array('Attachment'=>0));
			endif;
		endif;
	}

	public function ajax_edit($id){
		$idnya	 		= $id;
		$id 			= str_replace("-", "/", $id);
		$header 		= $this->selling->get_by_id($id,"edit");
		$detail 		= $this->selling->get_by_detail($id);
		$ar 			= $this->main->check_parameter_module("ar","ar");
		$cancel 		= '';
		$btn_edit 		= '';
		// $data_invoice 	= $this->api->invoice_detail($id,"selling");
		// $data_delivery 	= $this->api->delivery_detail($id,"selling");

		$modul  	= $this->input->post("modul");
		$url_modul 	= $this->input->post("url_modul");
		$id_url 	= $this->main->id_menu($url_modul);
		$edit 		= $this->main->menu_ubah($id_url);
		$delete 	= $this->main->menu_hapus($id_url);

		$sn 			= array();
		$cek_payment 	= 0;
		$cek_delivery 	= 0;
		if(count(array($header))>0):
			$sn 			= $this->check_serial_number($id);
			$cek_payment 	= $this->db->count_all("PS_Payment where CompanyID = '$header->CompanyID' and SellNo = '$header->SellNo'");
			$cek_delivery 	= $this->db->count_all("PS_Delivery where CompanyID = '$header->CompanyID' and SellNo = '$header->SellNo'");

			if($header->DeliveryCount <= 0 && $header->InvoiceCount<=0 && $header->ReturnCount<=0 && $header->Status == 1 && $ar->add>0 && $delete>0):
				$cancel = $this->main->button_action("cancel",$idnya);
			endif;
		endif;

		if($ar->add>0 && $edit>0):
			$btn_edit = $this->main->button_action("edit_attach",$idnya);
		endif;

		foreach ($detail as $k => $v) {
			$v->Qty_txt 			= $this->main->qty($v->Qty);
			$v->product_qty_txt 	= $this->main->qty($v->product_qty);
			$v->stock_product_txt 	= $this->main->qty($v->stock_product);
			$v->Price_txt 			= $this->main->currency($v->Price,TRUE);
			$v->TotalPrice_txt 		= $this->main->currency($v->TotalPrice,TRUE);
			$v->Discount_txt 		= $this->main->currency($v->Discount,TRUE);
			$v->DiscountValue_txt 	= $this->main->currency($v->DiscountValue,TRUE);
			$v->DeliveryQty_txt 	= $this->main->qty($v->DeliveryQty);
			$v->DeliveryCost_txt 	= $this->main->currency($v->DeliveryCost,TRUE);
		}

		$module_data 	= $this->main->check_module_stock($header->Module);
		$output = array(
			"list" 			=> $header,
			"list_detail" 	=> $detail,
			"sn_status"		=> $sn,
			"payment" 		=> $cek_payment,
			"delivery"		=> $cek_delivery,
			"hakakses"		=> $this->session->hak_akses,
			"stock"			=> $module_data->stock,
			// "attachment" 	=> site_url('attachment/'.$idnya.'?type=selling'),
			"cancel" 		=> $cancel,
			"edit"			=> $btn_edit,
			"attach"		=> $this->main->attachment_show("selling",$id),
		);

		if($ar->add>0):
			if($header->DeliveryParameter == 1 and $header->Status == 1 and $header->ProductType == 0):
				$output['next'] = $this->main->button_action("delivery",$idnya,$header->DeliveryStatus);
			elseif($header->DeliveryParameter == 0 and $header->Status == 1 or $header->ProductType == 1 and $header->Status == 1):
				$output['next'] = $this->main->button_action("invoice_selling",$idnya, $header->InvoiceStatus."-selling");
			endif;
		endif;

		$this->main->echoJson($output);
	}

	private function kurangQty($ProductID,$Qty,$BranchID=""){
		if($BranchID):
			$this->db->query("UPDATE PS_Product_Branch set Qty=ifnull(Qty,0)-$Qty WHERE ProductID = '$ProductID' and BranchID = '$BranchID'");
		else:
			$this->db->query("UPDATE ps_product set Qty=ifnull(Qty,0)-$Qty WHERE ProductID = '$ProductID'");
		endif;
	}

	private function tambahQty($ProductID,$Qty,$BranchID=""){
		if($BranchID):
			$this->db->query("UPDATE PS_Product_Branch set Qty=ifnull(Qty,0)+$Qty WHERE ProductID = '$ProductID' and BranchID = '$BranchID'");
		else:
			$this->db->query("UPDATE ps_product set Qty=ifnull(Qty,0)+$Qty WHERE ProductID = '$ProductID'");
		endif;
	}

	public function ajax_edit_serial($id){
		$a =  $this->selling->get_list_detail($id,"add_serial");
		$sn = json_decode($a->serialnumber);
		if(empty($sn)):
			$serial_number = array();
		else:
			$serial_number = array();
			foreach($sn as $sn):
				$item = array(
					"SellDet" 			=> $sn->SellDet,
					"serialnumber" 		=> $sn->SerialNumber,
					"hakakses"			=> $this->session->hakakses
				);	
				array_push($serial_number, $item);
			endforeach;
		endif;
		$data = array(
			"product_code" 		=> $a->product_code,
			"product_name" 		=> $a->product_name,
			"product_type" 		=> $a->product_type,
			"productid" 		=> $a->productid,
			"detail_code" 		=> $a->SellDet,
			"sell_det" 			=> $a->SellDet,
			"sell_konv" 		=> $a->sell_konv,
			"sell_no" 			=> $a->SellNo,
			"sell_price" 		=> $a->sell_price,
			"serial_qty" 		=> $a->sell_qty,
			"unit_name" 		=> $a->unit_name,
			"unitid" 			=> $a->unitid,
			"page"				=> "add_serial_selling",
			"list_serial" 		=> $serial_number,
			// "list_serial" 	=> $this->main->product_serial("add_serial_mutasi",$a->productid)
		);
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT); 
	}

	public function save_serial($page = "")
	{
		// $this->_validate_serial();
		$productserialid 	= $this->input->post("productserialid");
		$detail_code 		= $this->input->post("detail_code");
		$productid 			= $this->input->post("productid");
		$product_type 		= $this->input->post("product_type");
		$serial_number 		= $this->input->post("serial_number");
		if($page == "tes"):
			$serial_number 	= array("111111111111","222222222222222");
			$detail_code 	= "010004";
			$productid 		= 3;
		endif;
		$list_data_serial 	= array();
		$data 				= array();
		foreach ($serial_number as $key => $v) {
			$item = array(
				'SellDet'			=> $detail_code,
				'SerialNumber' 		=> $serial_number[$key],
				'status'			=> 1,
				
			);
			array_push($list_data_serial, $item);
		}
		$list_data_serial = json_encode($list_data_serial);
		$data = array(
			"SerialNumber" 	=> $list_data_serial,
			"User_Ch" 		=> $this->session->nama,
			"Date_Ch" 		=> date("Y-m-d H:i:s"),
		);
		$this->db->where("CompanyID",$this->session->CompanyID);
		$this->db->where("SellDet",$detail_code);
		$this->db->update("PS_Sell_Detail",$data);

		$a =  $this->selling->get_list_detail($detail_code,"add_serial");
		$sn_status = $this->check_serial_number($a->SellNo);

		header('Content-Type: application/json');
        echo json_encode(array("page"=>"add_serial","status" => "add_serial","pesan" => $data, "sn_status" => $sn_status),JSON_PRETTY_PRINT);  
	}

	private function check_serial_number($id){
		$status = true;
		$a =  $this->selling->get_list_detail($id);
		foreach ($a as $k => $v) {
			$sn = json_decode($v->serialnumber);
			if(empty($sn)):
				$status = false;
			else:
				if($v->type == 0):
					if(count($sn)<1):
						$status = false;
					endif;
				else:
					if(count($sn)<$v->sell_qty):
						$status = false;
					endif;
				endif;
			endif;
		}
		return $status;
	}

	public function cancel($id){
		$this->main->validate_modlue_add("ar","ar");
		$id 		= str_replace("-", "/", $id);
		$CompanyID 	= $this->session->CompanyID;
		$cek = $this->db->count_all("PS_Sell where SellNo = '$id' and CompanyID = '$CompanyID' and Status = '1'");
		if($cek>0):
			$header = $this->selling->get_by_id($id);
			$detail = $this->selling->get_by_detail($id);
			$module_data = $this->main->check_module_stock($header->Module);
			foreach ($detail as $key => $v) {
				if($header->DeliveryParameter == 0):
					if($module_data->stock>0):
						$this->tambahQty($v->ProductID,$v->Qty,$header->BranchID);
					endif;
				endif;

				// serial number
				if($v->Type == 2):
					$data_sn = $this->selling->serial_number($id,$v->SellDet,$v->ProductID);
					foreach ($data_sn as $k2 => $v2) {
						// update ke table PS_Product_Serial
						$data_serial = array(
							"Status"		=> 1,
							"User_Ch"		=> $this->session->NAMA,
							"Date_Ch"		=> date("Y-m-d H:i:s"),
						);
						$this->db->where("BranchID", $header->BranchID);
						$this->db->where("CompanyID", $CompanyID);
						$this->db->where("ProductID", $v->ProductID);
						$this->db->where("SerialNo", $v2->SN);
						$this->db->update("PS_Product_Serial", $data_serial);
					}
				endif;

				$data_detail = array("Status" => 0);
				$this->selling->update_det(array("SellDet"=>$v->SellDet, "CompanyID" => $CompanyID),$data_detail);
			}
			$data = array("Status" => 0);
			$this->selling->update(array("SellNo"=>$id, "CompanyID" =>$CompanyID),$data);

			$res = array(
				'status'	=> true,
				'message' 	=> $this->lang->line('lb_success'),
			);
		else:
			$res = array(
				'status'	=> false,
				'message' 	=> $this->lang->line('lb_data_not_found'),
			);
		endif;
		$this->main->echoJson($res);
	}

	private function calculationQty($sellNo,$page=""){
		$detail = $this->selling->get_by_detail($sellNo);
		if($page == "tambah"):
			foreach ($detail as $k => $v) {
				$this->tambahQty($v->ProductID,$v->Qty);
			}
		endif;
	}

	private function check_sell_det($array,$sellNo){
		$CompanyID = $this->session->CompanyID;
		$this->db->where("SellNo", $sellNo);
		$this->db->where("CompanyID", $CompanyID);
		$this->db->where_not_in("SellDet", $array);
		$this->db->delete("PS_Sell_Detail");

		// $delivery = $this->api->delivery_detail($sellNo,"selling");
		// if($delivery):
		// 	$this->db->where("DeliveryNo", $delivery->DeliveryNo);
		// 	$this->db->where_not_in("SellDet", $array);
		// 	$this->db->where("CompanyID", $CompanyID);
		// 	$this->db->delete("PS_Delivery_Det");
		// endif;
	}

	private function deleteDelivery($sellNo){
		$CompanyID = $this->session->CompanyID;

		$this->db->where("SellNo", $sellNo);
		$this->db->where("CompanyID", $CompanyID);
		$this->db->delete("PS_Delivery");

		$this->db->where("SellNo", $sellNo);
		$this->db->where("CompanyID", $CompanyID);
		$this->db->delete("PS_Delivery_Det");
	}

	public function save_remark(){
		$CompanyID 	= $this->session->CompanyID;
		$ID 		= $this->input->post("ID");
		$Remark 	= $this->input->post("Remark");

		$status 	= false;
		$message 	= $this->lang->line('lb_data_not_found');

		if($CompanyID && $ID):
			$cek = $this->db->count_all("PS_Sell where CompanyID = '$CompanyID' and SellNo = '$ID'");
			if($cek>0):
				$data = array(
					"Remark"	=> $Remark,
				);
				$this->db->where("CompanyID", $CompanyID);
				$this->db->where("SellNo", $ID);
				$this->db->update("PS_Sell", $data);

				$status = true;
				$message = $this->lang->line('lb_success');
			endif;
		endif;

		$output = array(
			"status" 	=> $status,
			"message" 	=> $message,
			"hakakses"	=> $this->session->hak_akses,
		);

		$this->main->echoJson($output);
	}

	public function serial_number_list(){
		$mt = $this->input->post("mt");
		$dt = $this->input->post("dt");

		$list = $this->selling->serial_number_list($mt,$dt);

		$output = array(
			"status"	=> true,
			"list"		=> $list,
		);

		$this->main->echoJson($output);
	}
}