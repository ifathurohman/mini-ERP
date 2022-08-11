<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penerimaan extends CI_Controller {
	var $title = 'Good Receipt';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_penerimaan",'penerimaan');
		$this->main->cek_session();
		$this->title = $this->lang->line('lb_goodrc');
	}

	public function index()
	{	
		$ID  	= $this->input->post("ID");
		$Status = $this->input->post("Status");
		
		$url 	= $this->uri->segment(1); 
		$id_url = $this->main->id_menu($url);
		$read 	= $this->main->read($id_url);
		$ap 	= $this->main->check_parameter_module("ap", "receipt");
		if($read == 0 || $ap->view == 0){ redirect(); }
		$selling_tambah 				= $this->main->menu_tambah($id_url);
		if($selling_tambah > 0 and $ap->add > 0):
            $tambah = $this->main->general_button('add','Add New '.$this->title);
		else: 
			$tambah = ""; 
		endif;
		$this->main->delete_temp_sn("penerimaan");
		#ini untuk session halaman aturan user privileges;
		$data['title']  		= $this->title;
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'penerimaan/modal';
		$data['modal_vendor'] 	= 'modal/modal_vendor';
		$data['page'] 			= 'penerimaan/list';
		$data['modul'] 			= 'penerimaan';
		$data['url_modul'] 		= $url;
		$data['ID']				= $ID;
		$data['Status']			= $Status;
		$this->load->view('index',$data);
	}

	public function ajax_list($url_modul="",$modul=""){
		$CompanyID  = $this->session->CompanyID;
		$id_url = $this->main->id_menu($url_modul);
		$edit 	= $this->main->menu_ubah($id_url);
		$delete = $this->main->menu_hapus($id_url);
		$list 	= $this->penerimaan->get_datatables();
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		$ap 	= $this->main->check_parameter_module("ap", "receipt");
		foreach ($list as $a) {
			$btn_edit 		= '';
			$btn_cancel 	= '';
			$btn_view 		= '';
			
			$ReceiveNo = str_replace("/", "-", $a->ReceiveNo);

			$status 		= $this->main->label_active($a->Status,"",$ReceiveNo);
			$label_type 	= $this->penerimaan->label_purchase_type($a->Type,"",$ReceiveNo);
			$btn_view 		= $this->main->button_action_dropdown("view", $ReceiveNo);
			$btn_print 		= $this->main->button_action_dropdown("print", $ReceiveNo);
			$btn_serial 	= $this->main->button_action_dropdown("serial", $ReceiveNo);
			$label_product_type = $this->main->label_product($a->ProductType,"",$ReceiveNo);
			if($edit>0 and $ap->add > 0):
				$btn_edit 	= $this->main->button_action_dropdown("edit", $ReceiveNo);
			endif;

			if($delete>0 and $ap->add > 0):
				if($a->Status == 1):
					$btn_cancel = $this->main->button_action_dropdown("cancel", $ReceiveNo);
				else:
					$btn_edit = '';
				endif;
			endif;

			$cek_invioce = $a->ck_invoice;
			$cek_retur   = $a->ck_retur;
			if($cek_invioce>0):
				$btn_edit 	= '';
				$btn_cancel = '';
			endif;
			if($cek_retur>0):
				$btn_edit 	= '';
				$btn_cancel = '';
			endif;

			$button  = '<div class="btn-group pointer">';
			$button .= '<div data-toggle="dropdown" aria-expanded="true">';
			$button .= '<i class="fal fa-cog"></i> <span class="caret"></span> </div>';
			$button .= '<ul class="dropdown-menu animate">';
			$button .= $btn_view;
			$button .= $btn_print;
			$button .= $btn_edit;
			$button .= $btn_cancel;
			$button .= $btn_serial;
	        $button .= ' </ul>';
	        $button .= '</div>';

	        // $code = '<a href="javascript:;" onclick="view('."'".$ReceiveNo."','print'".')">'.$a->ReceiveNo."</a>";
	        $btn_action 	= $this->main->button_action("code", $ReceiveNo,$a->ReceiveNo);
	        $vendor 		= $this->main->button_action("general_onclick","redirect_post('partner','".$a->VendorID."')",$a->ReceiveName);
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
			$row[] 	= $a->PurchaseNo; 
			$row[] 	= $vendor;
			$row[]	= $branch;
			$row[] 	= $status."<br>".$label_product_type;
			$row[] 	= $label_type;
			$row[] 	= $this->main->qty($qty); 
			// $row[] 	= $button; 
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->penerimaan->count_all(),
			"recordsFiltered" => $this->penerimaan->count_filtered(),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function save(){
		$this->_validate();

		$crud 				 = $this->input->post('crud');
		$ReceiveNo  		 = $this->input->post('ReceiveNo');
		$Date 				 = $this->input->post('receipt_date');
		$ReceiveName 		 = $this->input->post('ReceiveName');
		$PurchaseNo 		 = $this->main->checkNullInput($this->input->post('PurchaseNo'));
		$Remark 			 = $this->input->post('Remark');
		$ckOrder 			 = $this->input->post('ckOrder');
		$sj_no 				 = $this->input->post('sj_no');
		$Purchase_purchaseno = $this->input->post('po_no');
		$receipt_name 		 = $this->input->post('receipt_name');
		$SalesID 			 = $this->input->post('SalesID');
		$Ongkir 		  	 = $this->input->post('Ongkir');
		$BranchID 		 	= $this->input->post('BranchID');
		$BranchID 		 	= explode("-", $BranchID)[0];

		$ckPPN 				 = $this->input->post('ckPPN');
		$SubTotal 			 = $this->input->post('SubTotal');
		$Discount 			 = $this->input->post('Discount');
		$DiscountRp 		 = $this->input->post('DiscountRp');
		$TotalPPN 			 = $this->input->post('TotalPPN');
		$PPN 				 = $this->input->post('PPN');
		$Total 				 = $this->input->post('Total');
		$Term 		 		 = $this->input->post('Term');
		$CompanyID 			 = $this->session->CompanyID;
		$product_status  	 = $this->input->post("product_status");

		// product
		$check 				= $this->input->post('check');
		$rowid 				= $this->input->post('rowid');
		$detid 				= $this->input->post('detid');
		$purchasedet 		= $this->input->post('product_purchasedet');
		$product_purchaseno = $this->input->post('product_purchaseno');
		$qty 				= $this->input->post('product_qty');
		$product_id 		= $this->input->post('product_id');
		// $product_code 		= $this->input->post('product_code');
		$product_name 		= $this->input->post('product_name');
		$product_qty 		= $this->input->post('product_qty');
		$product_unit 		= $this->input->post('product_unit');
		$product_unitid 	= $this->input->post('product_unitid');
		$product_type 		= $this->input->post('product_type');
		$product_konv 		= $this->input->post('product_konv');
		$product_price 		= $this->input->post('product_price');
		$product_discount 	= $this->input->post('product_discount');
		$product_discountrp = $this->input->post('product_discountrp');
		$product_tax 		= $this->input->post('product_tax');
		$product_subtotal 	= $this->input->post('product_subtotal');
		$product_remark 	= $this->input->post('product_remark');
		$product_delivery 	= $this->input->post('product_delivery');

		// serial post
		$dt_serial 	  = $this->input->post("dt_serial");
		$dt_serialkey = $this->input->post("dt_serialkey");
		$dt_serialauto = $this->input->post("dt_serialauto");
		$dt_serial    = json_decode($dt_serial);
		$dt_serialkey = json_decode($dt_serialkey);
		$dt_serialauto = json_decode($dt_serialauto);

		// $purchase 			= $this->main->purchase("detail", $PurchaseNo);

		$inventory 		= $this->main->check_parameter_module("inventory","inventory");

		$module_data = array(
			"stock"		=> $inventory->add,
		);

		if($product_status == 1):
			$module_data = array(
				"stock"		=> 0,
			);
		endif;

		$data = array(
			'CompanyID'			=> $this->session->CompanyID,
			'Date'				=> $Date,
			// 'SalesID'			=> $SalesID,
			'Remark'			=> $Remark,
			'Type'				=> $ckOrder,
			'SJNo'				=> $sj_no,
			'Tax' 				=> $ckPPN,
			'PPN'				=> $PPN,
			'DeliveryCost'		=> $this->main->checkDuitInput($Ongkir),
			'TotalPPN'			=> $this->main->checkDuitInput($TotalPPN),
			'Discount'			=> $this->main->checkDuitInput($DiscountRp),
			'Payment'			=> $this->main->checkDuitInput($Total),
			'Total'				=> $this->main->checkDuitInput($SubTotal),
			'DiscountPersent'	=> $Discount,
			"Module"			=> json_encode($module_data),
			"ProductType"		=> $product_status,

		);
		if($ckOrder == 1):
			$data['PurchaseNo'] = $Purchase_purchaseno;
		else:
			$data['PurchaseNo'] = null;
			$data['Term']	    = $this->main->checkDuitInput($Term);
			// $data['SalesID'] 	= $SalesID;
		endif;
		if($receipt_name != ''):
			$receipt_name = explode('-', $receipt_name);
			$data['VendorID'] 	 = $receipt_name[0];
			$data['ReceiveName'] = $receipt_name[1];
		endif;
		if($SalesID != ''):
			$SalesID = explode('-', $SalesID);
			$data['SalesID'] = $SalesID[0];
		endif;
		if($BranchID):
			$data['BranchID'] = $BranchID;
		endif;

		if($crud == "insert"):
			$delCode = $this->main->penerimaan_code_generate();
			$data['ReceiveNo']		= $delCode;
			$data['Status']			= 1;
			$insert = $this->penerimaan->save($data);
		else:
			$delCode = $ReceiveNo;
			$this->calculationQty($delCode,"tambah");
			$insert = $this->penerimaan->update(array("ReceiveNo" => $ReceiveNo,"CompanyID" => $CompanyID),$data);
			$this->delete_all_serial($delCode);
		endif;

		$penerimaandetid 	= array();
		$sellid 			= array();
		$arrProductID 		= array();
		if($ckOrder == 1):
			foreach ($purchasedet as $key => $value) {
				if(in_array($purchasedet[$key], $check)):
					if($product_status == 1):
						$xqty  			= 1;
						$xunitid 		= null;
						$xconversion  	= 1;
					else:
						$xqty  			= $this->main->checkDuitInput($qty[$key]);
						$xunitid 		= $product_unitid[$key];
						$xconversion  	= $product_konv[$key];
					endif;

					$xqty2 		= $xqty * $xconversion;
					$xprice 	= $this->main->checkDuitInput($product_price[$key]);
					$average 	= $this->main->average($product_id[$key],$xqty2,$xprice,$BranchID);

					$data_det = array(
						"CompanyID"		=> $this->session->CompanyID,
						"ReceiveNo"		=> $delCode,
						"PurchaseNo"	=> $product_purchaseno[$key],
						"PurchaseDet"	=> $purchasedet[$key],
						"ProductID"		=> $product_id[$key],
						"Qty"			=> $xqty,
						"Conversion"	=> $xconversion,
						// "UnitID"		=> $xunitid,
						"Uom"			=> $xunitid,
						// "product_type"	=> $product_type[$key],
						// "DeliveryCost"	=> $this->main->checkDuitInput($product_delivery[$key]),
						"Price"			=> $this->main->checkDuitInput($product_price[$key]),
						"SubTotal"		=> $this->main->checkDuitInput($product_subtotal[$key]),
						"Discount"		=> $product_discount[$key],
						"DiscountValue"	=> $this->main->checkDuitInput($product_discountrp[$key]),
						"Remark" 		=> $product_remark[$key],
						"AveragePrice"	=> $average,
					);

					if($detid[$key] == ""):
						$deldet_code = $this->main->penerimaandetail_code_generate();
						$data_det['ReceiveDet'] = $deldet_code;
						$data_det['Status']		 = 1;
						$idnya = $this->penerimaan->save_det($data_det); // ini insert detail
						array_push($penerimaandetid, $deldet_code);
					else:
						$deldet_code = $detid[$key];
						$where = array("ReceiveDet" => $detid[$key], "CompanyID" => $CompanyID);
						$this->penerimaan->update_det($where,$data_det);
						array_push($penerimaandetid, $detid[$key]);

					endif;

					if(!in_array($product_purchaseno[$key], $sellid)){
						array_push($sellid, $product_purchaseno[$key]);
					}

					if($inventory->add>0 and $product_status == 0):
						$this->tambahQty($product_id[$key],$xqty2,$BranchID);
					endif;
					$this->tambahQtyPurchase($product_purchaseno[$key],$purchasedet[$key],$xqty);
					
					if(!in_array($product_id[$key], $arrProductID)):
						array_push($arrProductID, $product_id[$key]);
					endif;

					// simpan serial number
					$classnya = "vd".$purchasedet[$key];
					$xtype = $product_type[$key];
					if($xtype == 2 && $dt_serialauto[$key] != 1 && $product_status == 0):
						$arrkey = array_keys($dt_serialkey,$classnya);
						
						$arrTempSN = array();
						foreach ($arrkey as $key2 => $value_key) {
							$sn = $dt_serial[$value_key];
							array_push($arrTempSN, $sn);
						}
						$sn_data = $this->main->product_serial("array",$arrTempSN,$product_id[$key],"",$BranchID);

						foreach ($arrkey as $key2 => $value_key) {
							$no = $key2 + 1;
							if($xqty>=$no):
								$sn = $dt_serial[$value_key];
								
								// insert ke table AP_GoodReceipt_Det_SN
								$data_serial = array(
									"CompanyID"		=> $CompanyID,
									"Status"		=> 1,
									"Qty"			=> 1,
									"ProductID"		=> $product_id[$key],
									"ReceiveNo"		=> $delCode,
									"ReceiveDet" 	=> $deldet_code,
									"User_Add"		=> $this->session->NAMA,
									"Date_Add"		=> date("Y-m-d H:i:s"),
									"SN"			=> $sn,
								);
								$this->db->insert("AP_GoodReceipt_Det_SN", $data_serial);

								$sn_p 	   = array_search($sn, array_column($sn_data, "serialno"));
                				$sn_length = strlen($sn_p);

                				// insert data sn yang baru
                				if($sn_length<=0):
                					$data_serial = array(
										"ReceiveNo"		=> $delCode,
										"ReceiveDet"	=> $deldet_code,
										"CompanyID"		=> $CompanyID,
										"ProductID"		=> $product_id[$key],
										"Date"			=> date("Y-m-d"),
										"Qty"			=> 1,
										"Status"		=> 1,
										"SerialNo"		=> $sn,
										"User_Add"		=> $this->session->NAMA,
										"Date_Add"		=> date("Y-m-d H:i:s"),
									);
									$data_serial['BranchID'] = $BranchID;
									$this->db->insert("PS_Product_Serial", $data_serial);
                				// update status active
                				else:
                					$data_serial = array(
										"Status" 	=> 1,
										"User_Ch"	=> $this->session->NAMA,
										"Date_Ch"	=> date("Y-m-d H:i:s"),
									);
									$this->db->where("BranchID", $BranchID);
									$this->db->where("CompanyID", $CompanyID);
									$this->db->where("ProductID", $product_id[$key]);
									$this->db->where("SerialNo", $sn);
									$this->db->update("PS_Product_Serial", $data_serial);
                				endif;

							endif;
						}
					elseif($xtype == 2 && $dt_serialauto[$key] == 1 && $product_status == 0):
						$sn_data = $this->api->create_serial($product_id[$key],$xqty);
						foreach ($sn_data as $k => $v2) {
							$sn = $v2->serialnumber;

							// insert ke table AP_GoodReceipt_Det_SN
							$data_serial = array(
								"CompanyID"		=> $CompanyID,
								"Status"		=> 1,
								"Qty"			=> 1,
								"ProductID"		=> $product_id[$key],
								"ReceiveNo"		=> $delCode,
								"ReceiveDet" 	=> $deldet_code,
								"User_Add"		=> $this->session->NAMA,
								"Date_Add"		=> date("Y-m-d H:i:s"),
								"SN"			=> $sn,
							);
							$this->db->insert("AP_GoodReceipt_Det_SN", $data_serial);

							// insert ke table PS_Product_Serial
							$data_serial = array(
								"ReceiveNo"		=> $delCode,
								"ReceiveDet"	=> $deldet_code,
								"CompanyID"		=> $CompanyID,
								"ProductID"		=> $product_id[$key],
								"Date"			=> date("Y-m-d"),
								"Qty"			=> 1,
								"Status"		=> 1,
								"SerialNo"		=> $sn,
								"User_Add"		=> $this->session->NAMA,
								"Date_Add"		=> date("Y-m-d H:i:s"),
							);
							$data_serial['BranchID'] = $BranchID;
							$this->db->insert("PS_Product_Serial", $data_serial);
						}
					endif;
				endif;
			}
		else:
			foreach($product_id as $key => $v){
				if($product_id[$key]):
					if($product_status == 1):
						$xqty  			= 1;
						$xunitid 		= null;
						$xconversion  	= 1;
					else:
						$xqty  			= $this->main->checkDuitInput($qty[$key]);
						$xunitid 		= $product_unitid[$key];
						$xconversion  	= $product_konv[$key];
					endif;

					$xqty2 		= $xqty * $xconversion;
					$xprice 	= $this->main->checkDuitInput($product_price[$key]);
					$average 	= $this->main->average($product_id[$key],$xqty2,$xprice,$BranchID);

					$data_det = array(
						"CompanyID"		=> $this->session->CompanyID,
						"ReceiveNo"		=> $delCode,
						"PurchaseNo"	=> null,
						"PurchaseDet"	=> null,
						"ProductID"		=> $product_id[$key],
						"Qty"			=> $xqty,
						"Conversion"	=> $xconversion,
						// "UnitID"		=> $xunitid,
						"Uom" 			=> $xunitid,
						// "product_type"	=> $product_type[$key],
						"Price"			=> $this->main->checkDuitInput($product_price[$key]),
						"SubTotal"		=> $this->main->checkDuitInput($product_subtotal[$key]),
						"Discount"		=> $product_discount[$key],
						"DiscountValue"	=> $this->main->checkDuitInput($product_discountrp[$key]),
						"Remark" 		=> $product_remark[$key],
						"AveragePrice"	=> $average,
					);
					if($detid[$key] == ""):
						$deldet_code = $this->main->penerimaandetail_code_generate();
						$data_det['ReceiveDet'] = $deldet_code;
						$data_det['Status']		 = 1;
						$this->penerimaan->save_det($data_det);
						array_push($penerimaandetid, $deldet_code);
					else:
						$deldet_code = $detid[$key];
						$where = array("ReceiveDet" => $detid[$key], "CompanyID" => $CompanyID);
						$this->penerimaan->update_det($where,$data_det);
						array_push($penerimaandetid, $detid[$key]);
					endif;
					
					if($inventory->add>0 and $product_status == 0):
						$this->tambahQty($product_id[$key],$xqty2,$BranchID);
					endif;
					
					// $this->tambahQtyPurchase($product_purchaseno[$key],$purchasedet[$key],$qty[$key]);

					if(!in_array($product_id[$key], $arrProductID)):
						array_push($arrProductID, $product_id[$key]);
					endif;

					// simpan serial number
					$xtype = $product_type[$key];
					if($xtype == 2 && $dt_serialauto[$key] != 1 && $product_status == 0):
						$arrkey = array_keys($dt_serialkey,$rowid[$key]);

						$arrTempSN = array();
						foreach ($arrkey as $key2 => $value_key) {
							$sn = $dt_serial[$value_key];
							array_push($arrTempSN, $sn);
						}
						$sn_data = $this->main->product_serial("array",$arrTempSN,$product_id[$key],"",$BranchID);

						foreach ($arrkey as $key2 => $value_key) {
							$no = $key2 + 1;
							if($xqty>=$no):
								$sn = $dt_serial[$value_key];
								
								// insert ke table AP_GoodReceipt_Det_SN
								$data_serial = array(
									"CompanyID"		=> $CompanyID,
									"Status"		=> 1,
									"Qty"			=> 1,
									"ProductID"		=> $product_id[$key],
									"ReceiveNo"		=> $delCode,
									"ReceiveDet" 	=> $deldet_code,
									"User_Add"		=> $this->session->NAMA,
									"Date_Add"		=> date("Y-m-d H:i:s"),
									"SN"			=> $sn,
								);
								$this->db->insert("AP_GoodReceipt_Det_SN", $data_serial);

								$sn_p 	   = array_search($sn, array_column($sn_data, "serialno"));
                				$sn_length = strlen($sn_p);

                				// insert data sn yang baru
                				if($sn_length<=0):
                					$data_serial = array(
										"ReceiveNo"		=> $delCode,
										"ReceiveDet"	=> $deldet_code,
										"CompanyID"		=> $CompanyID,
										"ProductID"		=> $product_id[$key],
										"Date"			=> date("Y-m-d"),
										"Qty"			=> 1,
										"Status"		=> 1,
										"SerialNo"		=> $sn,
										"User_Add"		=> $this->session->NAMA,
										"Date_Add"		=> date("Y-m-d H:i:s"),
									);
									$data_serial['BranchID'] = $BranchID;
									$this->db->insert("PS_Product_Serial", $data_serial);
                				else:
                					$data_serial = array(
										"Status" 	=> 1,
										"User_Ch"	=> $this->session->NAMA,
										"Date_Ch"	=> date("Y-m-d H:i:s"),
									);
									$this->db->where("BranchID", $BranchID);
									$this->db->where("CompanyID", $CompanyID);
									$this->db->where("ProductID", $product_id[$key]);
									$this->db->where("SerialNo", $sn);
									$this->db->update("PS_Product_Serial", $data_serial);
                				endif;

							endif;
						}
					elseif($xtype == 2 && $dt_serialauto[$key] == 1 && $product_status == 0):
						$sn_data = $this->api->create_serial($product_id[$key],$xqty2);
						foreach ($sn_data as $k => $v2) {
							$sn = $v2->serialnumber;

							// insert ke table AP_GoodReceipt_Det_SN
							$data_serial = array(
								"CompanyID"		=> $CompanyID,
								"Status"		=> 1,
								"Qty"			=> 1,
								"ProductID"		=> $product_id[$key],
								"ReceiveNo"		=> $delCode,
								"ReceiveDet" 	=> $deldet_code,
								"User_Add"		=> $this->session->NAMA,
								"Date_Add"		=> date("Y-m-d H:i:s"),
								"SN"			=> $sn,
							);
							$this->db->insert("AP_GoodReceipt_Det_SN", $data_serial);

							// insert ke table PS_Product_Serial
							$data_serial = array(
								"ReceiveNo"		=> $delCode,
								"ReceiveDet"	=> $deldet_code,
								"CompanyID"		=> $CompanyID,
								"ProductID"		=> $product_id[$key],
								"Date"			=> date("Y-m-d"),
								"Qty"			=> 1,
								"Status"		=> 1,
								"SerialNo"		=> $sn,
								"User_Add"		=> $this->session->NAMA,
								"Date_Add"		=> date("Y-m-d H:i:s"),
							);
							$data_serial['BranchID'] = $BranchID;
							$this->db->insert("PS_Product_Serial", $data_serial);
						}
					endif;
				endif;
			}
		endif;

		// delete delivery det yang tidak digunakan
		$this->delete_purchasedet($penerimaandetid,$delCode);
		// end

		$this->check_purchase_status($sellid);
		$this->main->delete_temp_sn("penerimaan");

		// generate journal
		$this->db->query("CALL run_generate_jurnal('UPDATE', '$delCode', 'terima', '$CompanyID')");

		$res = array(
			"status" 	=> true,
			"message"	=> "Success",
			"ckOrder"	=> $ckOrder,
			"idnya"		=> $penerimaandetid,
			"ID"		=> $delCode,
		);

		$this->main->echoJson($res);
	}

	public function simpan_serial_unique($receipt_det = "",$productid = "",$product_qty ="")
	{
		$a = $this->main->product("select",$productid);
		$serial_format 	= $a->serial_format;
		$explodesn 		= explode("/", $serial_format);
		$countsn 		= count($explodesn);
		$digit 			= strlen($explodesn[$countsn-1]);
		$serial_format 	= str_replace("YEAR",date("y"),$serial_format);
		$serial_format 	= str_replace("MONTH",date("m"),$serial_format);
		$serial_format 	= substr($serial_format, 0,-$digit);
		$serial_format 	= str_replace("/", "",$serial_format);
		$cek 			= $this->db->count_all("PS_Product_Serial WHERE ProductID='$productid'");		
		foreach (range(1, $product_qty) as $a):
			if($serial_format == "auto"):
        		$serial_number	= $this->main->autoNumber("PS_Product_Serial","SerialNo",6,date("ym"));
	    	else:
	    		
    			$serial_number 	= $this->main->autoNumber("PS_Product_Serial","SerialNo",$digit,$serial_format,$productid);
	    	endif;
			$data = array(
				'ReceiveDet'=> $receipt_det,
				'CompanyID' => $this->session->CompanyID,
				'ProductID' => $productid,
				'SerialNo' 	=> $serial_number,
				'Qty'		=> 1,
				'Date'		=> date("Y-m-d")

				
			);
			$data["User_Add"] = $this->session->nama;
			$data["Date_Add"] = date("Y-m-d H:i:s");
			$this->db->insert("PS_Product_Serial",$data);	
		endforeach;
	}
	public function simpan_serial($page = "")
	{
		$this->validate_serial();
		$productserialid 	= $this->input->post("productserialid");
		$receipt_det 		= $this->input->post("receipt_det");
		$productid 			= $this->input->post("productid");
		$product_type 		= $this->input->post("product_type");
		$serial_qty 		= $this->input->post("serial_qty");
		$serial_number 		= $this->input->post("serial_number");
		if($page == "tes"):
			$serial_number 	= array("111111111111");
		endif;

		$qty 				= 1;
		if($product_type == "general"):
			$qty = $serial_qty;
		endif;
		$data 				= array();
		foreach ($serial_number as $key => $v) {
			$data = array(
				'ReceiveDet'=> $receipt_det,
				'CompanyID' => $this->session->CompanyID,
				'ProductID' => $productid,
				'SerialNo' 	=> $serial_number[$key],
				'Qty'		=> $qty,
				'Date'		=> date("Y-m-d")				
			);
			if($productserialid[$key]):
				$data["User_Ch"] = $this->session->nama;
				$data["Date_Ch"] = date("Y-m-d H:i:s");
				$this->db->where("ProductSerialID",$productserialid[$key]);
				$this->db->update("PS_Product_Serial",$data);
			else:
				$data["User_Add"] = $this->session->nama;
				$data["Date_Add"] = date("Y-m-d H:i:s");
				$this->db->insert("PS_Product_Serial",$data);
			endif;
		}
		echo json_encode(array("page"=>"add_serial","status" => "add_serial","pesan" => $data));
	}
	private function validate_serial(){
		$data = array();
		$data['status'] = TRUE;

		$CompanyID 			= $this->session->CompanyID;
		$productid 			= $this->input->post('productid');
		$product_type 		= $this->input->post('product_type');
		$productserialid 	= $this->input->post('productserialid');
		$serial_number 		= $this->input->post('serial_number');

		if(count($serial_number) !== count(array_unique($serial_number))):
			$data['inputerror'][] 	= '';
			$data['error_string'][] = '';
			$data['list'][] 		= '';
			$data['tab'][] 			= '';
			$data['message'] 		= "Serial number duplicate";
			$data['status'] 		= FALSE;

			$this->main->echoJson($data);
			exit();
		endif;

		foreach ($serial_number as $k => $v) {
			if($serial_number[$k] == ''):
				$data['inputerror'][] 	= '';
				$data['error_string'][] = '';
				$data['list'][] 		= '';
				$data['tab'][] 			= '';
				$data['message'] 		= "Serial number not complete";
				$data['status'] 		= FALSE;

				$this->main->echoJson($data);
				exit();
			endif;
		}

		foreach ($serial_number as $key => $v) {
			$from 	= "new";
			$id 	= $productserialid[$key];
			$sn 	= $serial_number[$key];
			if($productserialid[$key]):
				$from = "old";
			endif;
			if($from == "new"):
				$cek = $this->db->count_all("PS_Product_Serial where CompanyID = '$CompanyID' and SerialNo = '$sn' and ProductID = '$productid'");
			else:
				$cek = $this->db->count_all("PS_Product_Serial where CompanyID = '$CompanyID' and SerialNo = '$sn' and ProductID = '$productid' and ProductSerialID != '$id'");
			endif;
			if($cek>0):
				$data['inputerror'][] 	= $key;
				$data['error_string'][] = '';
				$data['list'][] 		= '';
				$data['tab'][] 			= '';
				$data['message'] 		= "Serial number has been already exist";
				$data['status'] 		= FALSE;
			endif;
		}

		if($data['status'] === FALSE)
        {
            header('Content-Type: application/json');
            echo json_encode($data,JSON_PRETTY_PRINT);  
            exit();
        }
	}
	private function _validate(){
		$this->main->validate_update();
		$this->main->validate_modlue_add("ap","ap");
		$data = array();
		$data['status'] = TRUE;

		// $data['inputerror'][] 	= "";
		// $data['error_string'][] = '';
		// $data['list'][] 		= '';
		// $data['tab'][] 			= '';
		// $data['ara'] 			= $this->input->post();
		// $data['status'] 		= FALSE;

		// $this->main->echoJson($data);
		// exit();

		$CompanyID 		= $this->session->CompanyID;
		$UserID 		= $this->session->UserID;
		$ReceiveNo 		= $this->input->post('ReceiveNo');
		$crud 			= $this->input->post('crud');
		$ckOrder 		= $this->input->post('ckOrder');
		$product_status = $this->input->post("product_status");
		// $data_qty 		= $this->main->product_detail($CompanyID);
		// if($ckOrder != 1 and $crud != "insert" and $product_status != 1):
		// 	$detail = $this->penerimaan->get_by_detail_non_order($ReceiveNo);
		// 	foreach ($detail as $k => $v) {
		// 		foreach ($data_qty as $kk => $vv) {
		// 			if($v->ProductID == $vv->ProductID):
		// 				$vv->Qty = $vv->Qty + $v->product_stock;
		// 			endif;
		// 		}
		// 	}
		// endif;

		// product
		$check 					= $this->input->post('check');
		$rowid 					= $this->input->post('rowid');
		$purchasedet 			= $this->input->post('product_purchasedet');
		$qty 					= $this->input->post('product_qty');
		$product_konv 			= $this->input->post('product_konv');
		$product_id 			= $this->input->post('product_id');
		$product_purchaseno 	= $this->input->post('product_purchaseno');
		$product_type 			= $this->input->post('product_type');

		// serial post
		$dt_serial 	  	= $this->input->post("dt_serial");
		$dt_serialkey 	= $this->input->post("dt_serialkey");
		$dt_serialauto 	= $this->input->post("dt_serialauto");
		$dt_serial    	= json_decode($dt_serial);
		$dt_serialkey 	= json_decode($dt_serialkey);
		$dt_serialauto 	= json_decode($dt_serialauto);

		// pengecekan sales order / type = 1
		if($ckOrder == 1):
			if($check):
				$status_penerimaan = true;
				foreach ($purchasedet as $key => $value) {
					if(in_array($purchasedet[$key], $check) and $product_status == 0):
						$PurchaseNo = $product_purchaseno[$key];
						$xqty 		= $this->main->checkDuitInput($qty[$key]);
						if($xqty):
							$xqty2 = $xqty * $product_konv[$key];
							$check_qty = $this->check_qty($PurchaseNo,$purchasedet[$key],$product_id[$key],$xqty);
							
							// pengecekan qty
							if(!$check_qty):
								$data['inputerror'][] 	= $purchasedet[$key];
								$data['error_string'][] = 'product_purchasedet';
								$data['list'][] 		= 'list';
								$data['tab'][] 			= '';
								$data['message'] 		= $this->lang->line('lb_product_qty_stock');
								$data['status'] 		= FALSE;
								$status_penerimaan 		= false;

							// pengecekan serial number
							else:
								$classnya = "vd".$purchasedet[$key];
								$xtype = $product_type[$key];
								$temp_data  = $this->api->temp_serial("penerimaan","",$classnya,$product_id[$key],"class");
								if($xtype == 2 && $dt_serialauto[$key] != 1):
									$arrkey = array_keys($dt_serialkey,$classnya);
									if($xqty>count($arrkey)):
										$data['inputerror'][] 	= $purchasedet[$key];
										$data['error_string'][] = $this->lang->line('lb_serial_empty');
										$data['list'][] 		= 'list';
										$data['tab'][] 			= '';
										$data['message'] 		= $this->lang->line('lb_serial_empty');
										$data['status'] 		= FALSE;
										$status_penerimaan 		= false;
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
											$data['inputerror'][] 	= $purchasedet[$key];
											$data['error_string'][] = $message_sn;
											$data['list'][] 		= 'list';
											$data['tab'][] 			= '';
											$data['status'] 		= FALSE;
											$data['message'] 		= $message_sn;
											$status_penerimaan 		= false;
										endif;
									endif;
								endif;
							endif;

						else:
							$data['inputerror'][] 	= $purchasedet[$key];
							$data['error_string'][] = 'product_purchasedet';
							$data['list'][] 		= 'list';
							$data['tab'][] 			= '';
							$data['message'] 		= $this->lang->line('lb_product_qty_empty');
							$data['status'] 		= FALSE;
							$status_penerimaan 		= false;
						endif;
					endif;
				}
				if(!$status_penerimaan):
					echo json_encode($data);
					exit();
				endif;
			else:
				$data['inputerror'][] 	= '';
				$data['error_string'][] = '';
				$data['list'][] 		= '';
				$data['tab'][] 			= '';
				$data['message'] 		= $this->lang->line('lb_select_product_empty');
				$data['status'] 		= FALSE;
				echo json_encode($data);
				exit();
			endif;
		// pengecekan non sales order
		else:
			// pengecekan select product
			if(count($product_id)>0):
				$status_product = false;
				foreach($product_id as $key => $v):
					if($product_id[$key] == ''):
						$cek = 0;
					else:
						$cek = $this->db->count_all("ps_product where CompanyID = '$CompanyID' and ProductID = '$product_id[$key]'");
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
					$status_product 		= false;
					echo json_encode($data);
					exit();
				endif;
			endif;

			// pengecekan qty dan serial number
			if(count($product_id) > 0):
				$status_qty = true;
				foreach($product_id as $key => $v):
					if($product_id[$key]):
						// jika qty nya kurang dari 1 atau kosong
						$xqty = $this->main->checkDuitInput($qty[$key]);
						if($xqty < 1 || empty($xqty) and $product_status == 0):
							$data['inputerror'][] 	= $rowid[$key];
							$data['error_string'][] = $this->lang->line('lb_product_qty_empty');
							$data['list'][] 		= 'list';
							$data['tab'][] 			= '';
							$data['message'] 		= $this->lang->line('lb_product_qty_empty');
							$data['status'] 		= FALSE;
							$status_qty 			= false;
						
						// pengecekan serial number
						else:
							$xtype = $product_type[$key];
							$temp_data  = $this->api->temp_serial("penerimaan","",$rowid[$key],$product_id[$key],"class");
							if($xtype == 2 && $dt_serialauto[$key] != 1):
								$arrkey = array_keys($dt_serialkey,$rowid[$key]);
								if($xqty>count($arrkey)):
									$data['inputerror'][] 	= $rowid[$key];
									$data['error_string'][] = $this->lang->line('lb_serial_empty');
									$data['list'][] 		= 'list';
									$data['tab'][] 			= '';
									$data['message'] 		= $this->lang->line('lb_serial_empty');
									$data['status'] 		= FALSE;
									$status_qty 			= false;
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
										$data['inputerror'][] 	= $rowid[$key];
										$data['error_string'][] = $message_sn;
										$data['list'][] 		= 'list';
										$data['tab'][] 			= '';
										$data['status'] 		= FALSE;
										$data['message'] 		= $message_sn;
										$status_qty 			= false;
									endif;
								endif;
							endif;
						endif;
					endif;
				endforeach;
				if(!$status_qty):
					echo json_encode($data);
					exit();
				endif;
			endif;
		endif;

		$BranchID 		= $this->input->post('BranchID');
		$BranchID 		= explode("-", $BranchID)[0];
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

		if($this->input->post('receipt_name') == ''):
			$data['inputerror'][] 	= 'receipt_name';
			$data['error_string'][] = '';
			$data['list'][] 		= '';
			$data['tab'][] 			= 'penerimaan';
			$data['message'] 		= $this->lang->line('lb_incomplete_form');
			$data['status'] 		= FALSE;
		endif;
		
		if($this->input->post('sj_no') == ''):
			$data['inputerror'][] 	= 'sj_no';
			$data['error_string'][] = $this->lang->line('lb_delivery_empty');
			$data['list'][] 		= '';
			$data['tab'][] 			= 'penerimaan';
			$data['message'] 		= $this->lang->line('lb_incomplete_form');
			$data['status'] 		= FALSE;
		endif;

		if($this->input->post('SalesID') == ''):
			$data['inputerror'][] 	= 'SalesID';
			$data['error_string'][] = '';
			$data['list'][] 		= '';
			$data['tab'][] 			= 'penerimaan';
			$data['status'] 		= FALSE;
		endif;

		if($data['status'] === FALSE)
        {
            header('Content-Type: application/json');
            echo json_encode($data,JSON_PRETTY_PRINT);  
            exit();
        }
	}
	
	private function check_qty($Purchase_purchaseno,$Purchase_purchasedet,$productid,$qty){
		$CompanyID 		= $this->session->CompanyID;
		$ReceiveNo 	 	= $this->input->post('ReceiveNo');
		$crud 			= $this->input->post('crud');
		$status 		= true;
		$qty 			= (float) $qty;

		if($crud == "update"):
			$d 		= $this->qty_receive($Purchase_purchaseno,$Purchase_purchasedet,$productid,$ReceiveNo);
			$qty 	+= $d->Qty;
		else:
			$d 		= $this->qty_receive($Purchase_purchaseno,$Purchase_purchasedet,$productid);
			$qty 	+= $d->Qty;
		endif;
		$data_purchase 	= $this->api->purchasedet_detail($Purchase_purchasedet,$Purchase_purchaseno);
		$purchaseQty 	= $data_purchase->Qty;

		// $data_product 	= $this->main->product_detail($CompanyID,$product_id,"detail");
		// $productQty 	= $data_product->Qty;

		if($qty>$purchaseQty):
			$status = false;
		endif;

		return $status;
	}

	private function check_qty_non_order($data,$productid,$Qty){
		$status  	= TRUE;
		$data_qty 	= array();
		foreach ($data as $key => $v) {
			if($v->productid == $productid):
				if($Qty>$v->Qty):
					$status = FALSE;
				endif;
				$qty = $v->Qty + $Qty;
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

	## qty delivery dan check status delivery ##
	private function qty_receive($Purchase_purchaseno,$Purchase_purchasedet,$productid,$ReceiveNo=""){
		$this->db->select("SUM(goodreceiptdet.Qty) as Qty");
		$this->db->where("goodreceiptdet.PurchaseNo", $Purchase_purchaseno);
		$this->db->where("goodreceiptdet.PurchaseDet", $Purchase_purchasedet);
		$this->db->where("goodreceiptdet.ProductID", $productid);
		$this->db->where("goodreceiptdet.CompanyID", $this->session->CompanyID);
		$this->db->where("goodreceipt.CompanyID", $this->session->CompanyID);
		$this->db->where("goodreceipt.Status", 1);
		if($ReceiveNo != ""):
			$this->db->where("goodreceiptdet.ReceiveNo !=",$ReceiveNo);
		endif;
		$this->db->join("AP_GoodReceipt as goodreceipt", "goodreceiptdet.ReceiveNo = goodreceipt.ReceiveNo and goodreceiptdet.CompanyID = goodreceipt.CompanyID", "left");
		$this->db->from("AP_GoodReceipt_Det as goodreceiptdet");
		$query 	= $this->db->get();
		$d 		= $query->row();

		return $query->row();
	}

	private function check_purchase_status($sellid){
		if(count($sellid)>0):
			$CompanyID = $this->session->CompanyID;

			foreach ($sellid as $key => $Purchase_purchaseno) {
				$this->db->query("
					UPDATE  PS_Purchase mt 
					JOIN (
						SELECT mt.purchaseno,
							case when sum(ifnull(dt.ReceiveQty,0)) >= sum(ifnull(dt.Qty,0)) then 1 else 0 end as DeliveryStatus
						from PS_Purchase mt
						join PS_Purchase_Detail dt on mt.PurchaseNo = dt.PurchaseNo and mt.CompanyID = dt.CompanyID
						where mt.PurchaseNo = '$Purchase_purchaseno' and mt.CompanyID = '$CompanyID' limit 1
						) dt ON mt.purchaseno = dt.purchaseno
					set mt.DeliveryStatus = dt.deliverystatus
					where mt.PurchaseNo = '$Purchase_purchaseno' and mt.CompanyID = '$CompanyID'
				");
			}
		endif;
	}


	public function ajax_edit($id){
		$id 		= str_replace("-", "/", $id);
		$list 		= $this->penerimaan->get_by_id($id);
		$data_purchase 	= array();

		if($list->Type == 1):
			$detail 	= $this->penerimaan->get_by_detail($id);
			foreach ($detail as $k => $v) {
				$list_sell 				= $this->main->purchase_detail("penerimaan", $v->Purchase_purchaseno, "update");
				$Purchase_purchasedet 	= $this->purchasedet($detail);
				foreach ($list_sell as $k => $v) {
					if(!in_array($v->Purchase_purchasedet, $Purchase_purchasedet)):
						array_push($data_purchase, $v);
					endif;
				}
			}
		else:
			$detail = $this->penerimaan->get_by_detail_non_order($id);
			$module_data 	= $this->main->check_module_stock($list->Module);
			foreach ($detail as $k => $v) {
				$stock 	= $v->product_stock;
				foreach ($detail as $kk => $vv) {
					if($v->productid == $vv->productid):
						if($module_data->stock>0):
							$stock += $vv->receive_qty;
						endif;
					endif;
				}
				$v->productStock = $stock;
			}
		endif;

		$data = array(
			"hakakses"		=> $this->session->hak_akses,
			"app"			=> $this->session->app,
			"list"			=> $list,
			"detail"		=> $detail,
			"data_purchase"	=> $data_purchase,
 		);

 		$this->main->echoJson($data);
	}

	public function ajax_edit_serial($id,$page="")
	{
		$a 			 =  $this->penerimaan->get_by_detail($id,"add_serial");
		$list_serial = $this->main->product_serial("add_serial",$a->receipt_det);
		if(count($list_serial)<= 0 and $a->product_type == "serial"):
			if($page != "view"):
				$list_serial = $this->api->create_serial($a->productid,$a->receive_qty);
			endif;
		elseif(count($list_serial)<= 0 and $a->product_type == "general"):
			$list_serial = $this->main->product_serial("detail",$a->productid);
		endif;
		$data = array(
			"product_code" 	=> $a->product_code,
			"product_name" 	=> $a->product_name,
			"product_type" 	=> $a->product_type,
			"productid" 	=> $a->productid,
			"receipt_det" 	=> $a->receipt_det,
			"receipt_konv" 	=> $a->receipt_konv,
			"receipt_no" 	=> $a->receipt_no,
			"receipt_price" => $a->receipt_price,
			"serial_qty" 	=> $a->receive_qty,
			"receipt_subtotal" 	=> $a->receipt_subtotal,
			"unit_name" 		=> $a->unit_name,
			"unitid" 			=> $a->unitid,
			"list_serial" 		=> $list_serial,
			"hakakses"			=> $this->session->hak_akses
		);
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
	}

	private function purchasedet($list){
		$data = array();
		foreach ($list as $key => $v) {
			array_push($data,$v->Purchase_purchasedet);
		}
		return $data;
	}

	public function cetak($id){
		$idnya 		= $id;
		$id 		= str_replace("-", "/", $id);
		$this->main->default_template("penerimaan");
		$cetak 		= $this->input->get("cetak");
		$position 	= $this->input->get("position");
		$page 		= $this->input->get("page");
		$code 		= "penerimaan".$id;

		$nama_laporan   = "";
        $datacompany    = $this->main->company("api");
        $company_name   = $datacompany->nama;
        $company_imgbin = $datacompany->img_bin;
        $company_imgurl = $datacompany->img_url;
        $logo           = base_url($company_imgurl);
		
		$list  		= $this->penerimaan->get_by_id($id,"edit");
		if($list->Type == 1):
			$detail 	= $this->penerimaan->get_by_detail($id);
		else:
			$detail 	= $this->penerimaan->get_by_detail_non_order($id);
		endif;

		$modul  	= $this->input->post("modul");
		$url_modul 	= $this->input->post("url_modul");
		$id_url 	= $this->main->id_menu($url_modul);
		$edit 		= $this->main->menu_ubah($id_url);
		$delete 	= $this->main->menu_hapus($id_url);

		$ap 	= $this->main->check_parameter_module("ap","ap");
		$data_action = array();
      	$cancel = '';
      	if($list->Status == 1 && $list->InvoiceCount<=0 && $list->ReturnCount<=0 && $ap->add>0 && $delete>0):
	        $cancel = $this->main->button_action("cancel",$idnya);
	        $data_action['cancel'] = $cancel;
      	endif;
      	if($ap->add>0 && $list->Status == 1):
			$data_action['next'] = $this->main->button_action("invoice_purchase",$idnya, $list->InvoiceStatus."-receipt");
		endif;
		if($ap->add>0 && $edit>0):
			$btn_edit = $this->main->button_action("edit_attach",$idnya);
			$data_action['edit'] = $btn_edit;
		endif;

		$data['page'] 			= $page;
		$data["cetak"]			= $cetak;
		$data['list']			= $list;
		$data["detail"]			= $detail;
		$data['title']  		= 'print Good Receipt';
		$data['title2'] 		= $this->title;
		$data["company_name"]   = $company_name;
		$data["nama_laporan"]   = $nama_laporan;
    	$data["logo"]           = $logo;
    	$data["company"]		= $datacompany;
    	$data['data_action']	= $data_action;
    	if($page == "print"):
    		$data['template'] 	= $this->main->get_default_template("penerimaan");
    		$this->load->view('penerimaan/template',$data);
    	else:
    		$this->load->view('penerimaan/view',$data);
    	endif;
    	$CompanyID = $this->session->CompanyID;

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
	}

	private function delete_purchasedet($ReceiveDetID,$ReceiveNo){
		if(count($ReceiveDetID)>0):
			$this->db->where_not_in("ReceiveDet", $ReceiveDetID);
			$this->db->where("ReceiveNo", $ReceiveNo);
			$this->db->where("CompanyID", $this->session->CompanyID);
			$this->db->delete("AP_GoodReceipt_Det");
		endif;
	}

	private function kurangQty($ProductID,$Qty,$BranchID=""){
		if($BranchID):
			$this->db->query("UPDATE PS_Product_Branch set Qty=ifnull(Qty,0)-$Qty WHERE ProductID = $ProductID and BranchID = $BranchID");
		else:
			$this->db->query("UPDATE ps_product set Qty=ifnull(Qty,0)-$Qty WHERE ProductID = $ProductID");
		endif;
	}

	private function tambahQty($ProductID,$Qty,$BranchID=""){
		if($BranchID):
			$this->db->query("UPDATE PS_Product_Branch set Qty=ifnull(Qty,0)+$Qty WHERE ProductID = $ProductID and BranchID = $BranchID");
		else:
			$this->db->query("UPDATE ps_product set Qty=ifnull(Qty,0)+$Qty WHERE ProductID = $ProductID");
		endif;
	}

	private function calculationQty($receipt_no,$page=""){
		$header 		= $this->penerimaan->get_by_id($receipt_no);
		$module_data 	= $this->main->check_module_stock($header->Module);
		if($header->Type == 1):
			$detail = $this->penerimaan->get_by_detail($receipt_no);
		else:
			$detail = $this->penerimaan->get_by_detail_non_order($receipt_no);
		endif;
		if($page == "tambah"):
			foreach ($detail as $k => $v) {
				if($module_data->stock>0):
					$this->kurangQty($v->productid,$v->receive_qty,$header->BranchID);
				endif;
				
				if($header->Type == 1):
					$this->kurangQtyPurchase($v->Purchase_purchaseno,$v->Purchase_purchasedet,$v->receive_qty);
				endif;
			}
		endif;
	}

	private function tambahQtyPurchase($PurchaseNo,$PurchaseDet,$Qty){
		$CompanyID = $this->session->CompanyID;
		
		$this->db->query(
            "UPDATE PS_Purchase_Detail set 
                ReceiveQty=ifnull(ReceiveQty,0)+$Qty
            WHERE
                PurchaseDet 	= '$PurchaseDet' and 
                PurchaseNo 		= '$PurchaseNo' and 
                CompanyID 		= $CompanyID
        ");
	}

	private function kurangQtyPurchase($PurchaseNo,$PurchaseDet,$Qty){
		$CompanyID = $this->session->CompanyID;
		
		$this->db->query(
            "UPDATE PS_Purchase_Detail set 
                ReceiveQty=ifnull(ReceiveQty, 0)-$Qty
            WHERE
                PurchaseDet 	= '$PurchaseDet' and 
                PurchaseNo 		= '$PurchaseNo' and 
                CompanyID 		= $CompanyID
        ");
	}

	public function cancel($id){
		$this->main->validate_modlue_add("ap","ap");
		$id 		= str_replace("-", "/", $id);
		$CompanyID = $this->session->CompanyID;
		$cek = $this->db->count_all("AP_GoodReceipt where ReceiveNo = '$id'");
		if($cek>0):
			$header = $this->penerimaan->get_by_id($id);
			$module_data 	= $this->main->check_module_stock($header->Module);
			if($header->Type == 1):
				$detail = $this->penerimaan->get_by_detail($id);
			else:
				$detail = $this->penerimaan->get_by_detail_non_order($id);
			endif;
			foreach ($detail as $key => $v) {
				$xqty  		= -1 * ($v->receive_qty * $v->receipt_konv);
				$xqty2 		= ($v->receive_qty * $v->receipt_konv);
				$xprice 	= (float) $v->receipt_price;
				$this->main->average($v->productid,$xqty,$xprice,$header->BranchID);

				if($module_data->stock>0):
					$this->kurangQty($v->productid,$xqty2,$header->BranchID);
				endif;
				
				if($header->Type == 1):
					$this->kurangQtyPurchase($v->Purchase_purchaseno,$v->Purchase_purchasedet,$v->receive_qty);
				endif;

				$data_detail = array("Status" => 0);
				$this->penerimaan->update_det(array("ReceiveDet"=>$v->Purchase_purchasedet, "CompanyID" => $CompanyID,),$data_detail);
			}
			$data = array("Status" => 0);
			$this->penerimaan->update(array("ReceiveNo"=>$id, "CompanyID" => $CompanyID,),$data);

			if($header->Type == 1):
				$data_purchase = array("DeliveryStatus" => 0);
				$this->db->where("CompanyID", $CompanyID);
				$this->db->where("PurchaseNo", $header->po_no);
				$this->db->update("PS_Purchase", $data_purchase);
			endif;

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

	private function delete_all_serial($ReceiveNo){
		$header = $this->penerimaan->get_by_id($ReceiveNo);
		if($header->Type == 1):
			$detail = $this->penerimaan->get_by_detail($ReceiveNo);
		else:
			$detail = $this->penerimaan->get_by_detail_non_order($ReceiveNo);
		endif;

		foreach ($detail as $key => $v) {
			$this->db->where("ProductID", $v->productid);
			$this->db->where("ReceiveDet", $v->receipt_det);
			$this->db->delete("PS_Product_Serial");
		}
	}

	public function save_remark(){
		$CompanyID 	= $this->session->CompanyID;
		$ID 		= $this->input->post("ID");
		$Remark 	= $this->input->post("Remark");

		$status 	= false;
		$message 	= $this->lang->line('lb_data_not_found');

		if($CompanyID && $ID):
			$cek = $this->db->count_all("AP_GoodReceipt where CompanyID = '$CompanyID' and ReceiveNo = '$ID'");
			if($cek>0):
				$data = array(
					"Remark"	=> $Remark,
				);
				$this->db->where("CompanyID", $CompanyID);
				$this->db->where("ReceiveNo", $ID);
				$this->db->update("AP_GoodReceipt", $data);

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

		$list = $this->penerimaan->serial_number_list($mt,$dt);

		$output = array(
			"status"	=> true,
			"list"		=> $list,
		);

		$this->main->echoJson($output);
	}

}