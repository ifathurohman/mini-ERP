<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Delivery extends CI_Controller {
	var $title = 'Item Delivery';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_delivery",'delivery');
		$this->main->cek_session();
		$this->title = $this->lang->line('lb_delivery');
	}

	public function index()
	{	
		$SellNo 		= $this->input->post('SellNo');
		$DeliveryStatus = $this->input->post('DeliveryStatus');

		$url 	= $this->uri->segment(1); 
		$id_url = $this->main->id_menu($url);
		$read 	= $this->main->read($id_url);
		$ar 	= $this->main->check_parameter_module("ar","delivery");
		if($read == 0 or $ar->view == 0){ redirect(); }
		$selling_tambah 				= $this->main->menu_tambah($id_url);
		if($selling_tambah > 0 and $ar->add >0):
            $tambah = $this->main->general_button('add','Add New '.$this->title);
		else: 
			$tambah = ""; 
		endif;
		$this->main->delete_temp_sn("delivery");
		#ini untuk session halaman aturan user privileges;
		$data['title']  		= $this->title;
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'delivery/modal';
		$data['modal_vendor'] 	= 'modal/modal_vendor';
		$data['page'] 			= 'delivery/list';
		$data['modul'] 			= 'delivery';
		$data['url_modul'] 		= $url;
		$data['SellNo']			= $SellNo;
		$data['DStatus']		= $DeliveryStatus;
		$this->load->view('index',$data);
	}

	public function ajax_list($url_modul="",$modul=""){
		$CompanyID  = $this->session->CompanyID;

		$id_url = $this->main->id_menu($url_modul);
		$edit 	= $this->main->menu_ubah($id_url);
		$delete = $this->main->menu_hapus($id_url);
		$list 	= $this->delivery->get_datatables();
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		$ar 	= $this->main->check_parameter_module("ar","delivery");
		foreach ($list as $a) {
			$btn_edit 		= '';
			$btn_cancel 	= '';
			$btn_view 		= '';
			
			$DeliveryNo 	= str_replace("/", "-", $a->DeliveryNo);

			$status 		= $this->main->label_active($a->Status,"",$DeliveryNo);
			$label_type 	= $this->delivery->label_delivery_type($a->Type,"",$DeliveryNo);
			$btn_view 		= $this->main->button_action_dropdown("view", $DeliveryNo);
			$btn_print 		= $this->main->button_action_dropdown("print", $DeliveryNo);
			$label_product_type = $this->main->label_product($a->ProductType,"",$DeliveryNo);
			
			if($edit>0 and $ar->add >0):
				$btn_edit 	= $this->main->button_action_dropdown("edit", $DeliveryNo);
			endif;

			if($delete>0 and $ar->add >0):
				if($a->Status == 1):
					$btn_cancel = $this->main->button_action_dropdown("cancel", $DeliveryNo);
				else:
					$btn_edit = '';
				endif;
			endif;

			$cek_invioce = $a->ck_invoice;
			$cek_return  = $a->ck_return;
			if($cek_invioce>0):
				$btn_edit 	= '';
				$btn_cancel = '';
			endif;
			if($cek_return>0):
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
	        $button .= ' </ul>';
	        $button .= '</div>';

	        $btn_action 	= $this->main->button_action("code", $DeliveryNo,$a->DeliveryNo);
	        $vendor 		= $this->main->button_action("general_onclick","redirect_post('partner','".$a->VendorID."')",$a->vendorName);
	        $qty = $a->Qty;
	        if($a->ProductType == 1):
	        	$qty = 0;
	        endif;

			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $btn_action;
			$row[] 	= $a->Date; 
			$row[] 	= $a->SellNo; 
			$row[] 	= $vendor; 
			$row[] 	= $status."<br>".$label_product_type;
			$row[] 	= $label_type;
			$row[] 	= $this->main->qty($qty); 
			// $row[] 	= $button; 
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->delivery->count_all(),
			"recordsFiltered" => $this->delivery->count_filtered(),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function save(){
		$this->_validate();

		$crud 		= $this->input->post('crud');
		$DeliveryNo = $this->input->post('DeliveryNo');
		$Date 		= $this->input->post('Date');
		$CustomerID = $this->input->post('CustomerID');
		$SellNo 	= $this->main->checkNullInput($this->input->post('SellNo'));
		$Remark 	= $this->input->post('Remark');
		$ckOrder 	= $this->input->post('ckOrder');
		$SalesID 	= $this->input->post('SalesID');
		$Ongkir 	= $this->input->post('Ongkir');
		$ckPPN 		= $this->input->post('ckPPN');
		$SubTotal 	= $this->input->post('SubTotal');
		$Discount 	= $this->input->post('Discount');
		$DiscountRp = $this->input->post('DiscountRp');
		$TotalPPN 	= $this->input->post('TotalPPN');
		$PPN 		= $this->input->post('PPN');
		$Total 		= $this->input->post('Total');
		$Address 	= $this->input->post('delAddress');
		$City 		= $this->input->post('delCity');
		$Province 	= $this->input->post('delProvince');
		$Term 		= $this->input->post('Term');
		$CompanyID 	= $this->session->CompanyID;
		$product_status  	= $this->input->post("product_status");
		$BranchID	= $this->input->post('BranchID');
		$BranchID 	= explode("-", $BranchID)[0];

		// product
		$rowid 			= $this->input->post('rowid');
		$check 			= $this->input->post('check');
		$detid 			= $this->input->post('detid');
		$selldet 		= $this->input->post('product_selldet');
		$product_sellno = $this->input->post('product_sellno');
		$qty 			= $this->input->post('product_qty');
		$product_id 	= $this->input->post('product_id');
		// $product_code 	= $this->input->post('product_code');
		// $product_name 	= $this->input->post('product_name');
		$product_qty 	= $this->input->post('product_qty');
		$product_unit 	= $this->input->post('product_unit');
		$product_unitid = $this->input->post('product_unitid');
		$product_type 	= $this->input->post('product_type');
		$product_konv 	= $this->input->post('product_konv');
		$product_price 	= $this->input->post('product_price');
		$product_discount 	= $this->input->post('product_discount');
		$product_discountrp = $this->input->post('product_discountrp');
		$product_tax 		= $this->input->post('product_tax');
		$product_subtotal 	= $this->input->post('product_subtotal');
		$product_remark 	= $this->input->post('product_remark');
		$product_delivery 	= $this->input->post('product_delivery');
		$product_cost 		= $this->input->post('product_cost');
		$product_module 	= $this->input->post('product_module');

		// $sell 	= $this->main->sell("detail", $SellNo);

		// serial post
		$dt_serial 	  = $this->input->post("dt_serial");
		$dt_serialkey = $this->input->post("dt_serialkey");
		$dt_serialauto = $this->input->post("dt_serialauto");
		$dt_serial    = json_decode($dt_serial);
		$dt_serialkey = json_decode($dt_serialkey);
		$dt_serialauto = json_decode($dt_serialauto);

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
			'Remark'			=> $Remark,
			'Address'			=> $Address,
			'City'				=> $City,
			'Province'			=> $Province,
			'Type'				=> $ckOrder,
			'Tax' 				=> $ckPPN,
			'PPN'				=> $PPN,
			'TotalPPN'			=> $this->main->checkDuitInput($TotalPPN),
			'Discount'			=> $this->main->checkDuitInput($DiscountRp),
			'DeliveryCost'		=> $this->main->checkDuitInput($Ongkir),
			'Payment'			=> $this->main->checkDuitInput($Total),
			'Total'				=> $this->main->checkDuitInput($SubTotal),
			'DiscountPersent'	=> $Discount,
			'ProductType'		=> $product_status,
		);
		if($ckOrder == 1):
			$data['SellNo'] = $SellNo;
			$data['Module']	= null;
		else:
			$data['SellNo'] = null;
			$data['Term']	= $this->main->checkDuitInput($Term);
			$data['Module']	= json_encode($module_data);
			if($BranchID):
				$data['BranchID'] = $BranchID;
			endif;
		endif;
		if($CustomerID != ''):
			$CustomerID = explode('-', $CustomerID);
			$data['VendorID'] 	= $CustomerID[0];
			$data['DeliveryTo'] = $CustomerID[1];
		endif;
		if($SalesID != ''):
			$SalesID = explode('-', $SalesID);
			$data['SalesID'] = $SalesID[0];
		endif;

		if($crud == "insert"):
			$delCode = $this->main->delivery_generate();
			$data['DeliveryNo']	= $delCode;
			$data['Status']		= 1;
			$insert = $this->delivery->save($data);
		else:
			$delCode = $DeliveryNo;
			$this->calculationQty($delCode,"tambah");
			$insert = $this->delivery->update(array("DeliveryNo" => $DeliveryNo,"CompanyID" => $CompanyID),$data);
		endif;

		$deliverydetid 	= array();
		$sellid 		= array();
		if($ckOrder == 1):
			foreach ($selldet as $key => $value) {
				if(in_array($selldet[$key], $check)):

					if($product_status == 1):
						$xqty 		= 1;
						$xunitid 	= null;
						$xConversion = 1;
						$xproduct_type = 0;
					else:
						$xqty 		= $this->main->checkDuitInput($qty[$key]);
						$xunitid 	= $product_unitid[$key];
						$xConversion = $product_konv[$key];
						$xproduct_type = $product_type[$key];
					endif;

					$BranchID = $this->main->get_one_column("PS_Sell","BranchID",array("SellNo" => $product_sellno[$key], "CompanyID" => $CompanyID))->BranchID;

					$data_det = array(
						"CompanyID"		=> $this->session->CompanyID,
						"DeliveryNo"	=> $delCode,
						"SellNo"		=> $product_sellno[$key],
						"SellDet"		=> $selldet[$key],
						"ProductID"		=> $product_id[$key],
						"Qty"			=> $xqty,
						"Conversion"	=> $xConversion,
						"BranchID"		=> $BranchID,
						// "UnitID"		=> $xunitid,
						"Uom" 			=> $xunitid,
						"Type"			=> $xproduct_type,
						"Price"			=> $this->main->checkDuitInput($product_price[$key]),
						"TotalPrice"	=> $this->main->checkDuitInput($product_subtotal[$key]),
						"Discount"		=> $product_discount[$key],
						"DiscountValue"	=> $this->main->checkDuitInput($product_discountrp[$key]),
						"DeliveryCost"	=> $this->main->checkDuitInput($product_delivery[$key]),
						"Remark" 		=> $product_remark[$key],
						"Cost"			=> $this->main->checkDuitInput($product_cost[$key]),
					);

					if($detid[$key] == ""):
						$deldet_code = $this->main->deliverydet_generate();
						$data_det['DeliveryDet'] = $deldet_code;
						$data_det['Status']		 = 1;
						$this->delivery->save_det($data_det);
						array_push($deliverydetid, $deldet_code);
					else:
						$deldet_code = $detid[$key];
						$where = array("DeliveryDet" => $detid[$key], "CompanyID" => $CompanyID);
						$this->delivery->update_det($where,$data_det);
						array_push($deliverydetid, $detid[$key]);
					endif;

					if(!in_array($product_sellno[$key], $sellid)){
						array_push($sellid, $product_sellno[$key]);
					}
					$d_module = $product_module[$key];
					$d_module = $this->main->relpace_str($d_module,"'",'"');
					$module_data = $this->main->check_module_stock($d_module);
					if($module_data->stock>0):
						$xqty2 = $xqty * $xConversion;
						$this->kurangQty($product_id[$key],$xqty2,$BranchID);
					endif;
					$this->tambahQtyDelivery($product_sellno[$key],$selldet[$key],$xqty);

					// serial number
					$xtype = $product_type[$key];
					if($xtype == 2 && $product_status == 0):
						$classnya = "vd".$selldet[$key];
						$arrkey = array_keys($dt_serialkey,$classnya);
						foreach ($arrkey as $key2 => $value_key) {
							$no = $key2 + 1;
							if($xqty>=$no):
								$sn = $dt_serial[$value_key];
								
								// insert ke table PS_Delivery_Det_SN
								$data_serial = array(
									"CompanyID"		=> $CompanyID,
									"Status"		=> 1,
									"Qty"			=> 1,
									"ProductID"		=> $product_id[$key],
									"DeliveryNo"	=> $delCode,
									"DeliveryDet" 	=> $deldet_code,
									"User_Add"		=> $this->session->NAMA,
									"Date_Add"		=> date("Y-m-d H:i:s"),
									"SN"			=> $sn,
								);
								$this->db->insert("PS_Delivery_Det_SN", $data_serial);

								// update ke table PS_Sell_Detail_SN
								$data_serial = array(
									"Status"		=> 0,
									"User_Ch"		=> $this->session->NAMA,
									"Date_Ch"		=> date("Y-m-d H:i:s"),
								);
								$this->db->where("CompanyID", $CompanyID);
								$this->db->where("ProductID", $product_id[$key]);
								$this->db->where("SellNo", $product_sellno[$key]);
								$this->db->where("SellDet", $selldet[$key]);
								$this->db->where("SN", $sn);
								$this->db->update("PS_Sell_Detail_SN", $data_serial);
							endif;
						}
					endif;

				endif;
			}
		else:
			foreach($product_id as $key => $v){
				if($product_id[$key]):
					if($product_status == 1):
						$xqty 		= 1;
						$xunitid 	= null;
						$xConversion = null;
						$xproduct_type = 0;
					else:
						$xqty 		= $this->main->checkDuitInput($qty[$key]);
						$xunitid 	= $product_unitid[$key];
						$xConversion = $product_konv[$key];
						$xproduct_type = $product_type[$key];
					endif;

					$average  = $this->api->get_AveragePrice($product_id[$key],$BranchID);

					$data_det = array(
						"CompanyID"		=> $this->session->CompanyID,
						"DeliveryNo"	=> $delCode,
						"SellNo"		=> null,
						"SellDet"		=> null,
						"ProductID"		=> $product_id[$key],
						"Qty"			=> $xqty,
						"Conversion"	=> $xConversion,
						"UnitID"		=> $xunitid,
						"Uom"			=> $xunitid,
						"Type"			=> $xproduct_type,
						"Price"			=> $this->main->checkDuitInput($product_price[$key]),
						"TotalPrice"	=> $this->main->checkDuitInput($product_subtotal[$key]),
						"Discount"		=> $product_discount[$key],
						"DiscountValue"	=> $this->main->checkDuitInput($product_discountrp[$key]),
						"Remark" 		=> $product_remark[$key],
						"Cost"			=> $average,
					);
					if($detid[$key] == ""):
						$deldet_code = $this->main->deliverydet_generate();
						$data_det['DeliveryDet'] = $deldet_code;
						$data_det['Status']		 = 1;
						$this->delivery->save_det($data_det);
						array_push($deliverydetid, $deldet_code);
					else:
						$deldet_code = $detid[$key];
						$where = array("DeliveryDet" => $detid[$key], "CompanyID" => $CompanyID);
						$this->delivery->update_det($where,$data_det);
						array_push($deliverydetid, $detid[$key]);
					endif;

					if($inventory->add>0 && $product_status == 0):
						$xqty2 = $xqty * $xConversion;
						$this->kurangQty($product_id[$key],$xqty2,$BranchID);
					endif;

					// simpan serial number
					$xtype = $product_type[$key];
					if($xtype == 2 && $product_status == 0):
						$arrkey = array_keys($dt_serialkey,$rowid[$key]);
						foreach ($arrkey as $key2 => $value_key) {
							$no = $key2 + 1;
							if($xqty>=$no):
								$sn = $dt_serial[$value_key];
								
								// insert ke table PS_Delivery_Det_SN
								$data_serial = array(
									"CompanyID"		=> $CompanyID,
									"Status"		=> 1,
									"Qty"			=> 1,
									"ProductID"		=> $product_id[$key],
									"DeliveryNo"	=> $delCode,
									"DeliveryDet" 	=> $deldet_code,
									"User_Add"		=> $this->session->NAMA,
									"Date_Add"		=> date("Y-m-d H:i:s"),
									"SN"			=> $sn,
								);
								$this->db->insert("PS_Delivery_Det_SN", $data_serial);

								// update ke table PS_Product_Serial
								$data_serial = array(
									"Status"		=> 0,
									"User_Ch"		=> $this->session->NAMA,
									"Date_Ch"		=> date("Y-m-d H:i:s"),
								);

								$this->db->where("BranchID", $BranchID);
								$this->db->where("CompanyID", $CompanyID);
								$this->db->where("ProductID", $product_id[$key]);
								$this->db->where("SerialNo", $sn);
								$this->db->update("PS_Product_Serial", $data_serial);
							endif;
						}
					endif;
				endif;
			}
		endif;

		// delete delivery det yang tidak digunakan
		$this->delete_deleverydet($deliverydetid,$delCode);
		// end

		$this->check_delivery_status($sellid);
		$this->main->delete_temp_sn("delivery");

		// generate journal
		$this->db->query("CALL run_generate_jurnal('UPDATE', '$delCode', 'suratjalan', '$CompanyID')");

		$res = array(
			"status" 	=> true,
			"message"	=> $this->lang->line('lb_success'),
			"ID"		=> $delCode,
		);

		$this->main->echoJson($res);
	}

	private function _validate(){
		$this->main->validate_update();
		$this->main->validate_modlue_add("ar","ar");
		$data = array();
		$data['status'] = TRUE;
		$arrProductID 	= array();

		// $data['inputerror'][] 	= "";
		// $data['error_string'][] = '';
		// $data['list'][] 		= '';
		// $data['tab'][] 			= '';
		// $data['ara'] 			= $this->input->post();
		// $data['status'] 		= FALSE;

		// $this->main->echoJson($data);
		// exit();

		$CompanyID 	= $this->session->CompanyID;
		$DeliveryNo = $this->input->post('DeliveryNo');
		$crud 		= $this->input->post('crud');
		$ckOrder 	= $this->input->post('ckOrder');
		$product_id = $this->input->post('product_id');
		$BranchID	= $this->input->post('BranchID');
		$BranchID 	= explode("-", $BranchID)[0];
		
		if(count($product_id)>0):
			foreach ($product_id as $key => $v) {
				if(!in_array($v, $arrProductID)):
					array_push($arrProductID, $v);
				endif;
			}
		endif;

		// product
		$check 				= $this->input->post('check');
		$rowid 				= $this->input->post('rowid');
		$selldet 			= $this->input->post('product_selldet');
		$qty 				= $this->input->post('product_qty');
		$product_sellno 	= $this->input->post('product_sellno');
		$product_status  	= $this->input->post("product_status");
		$product_type 		= $this->input->post('product_type');
		$product_konv 		= $this->input->post('product_konv');

		// serial post
		$dt_serial 	  = $this->input->post("dt_serial");
		$dt_serialkey = $this->input->post("dt_serialkey");
		$dt_serialauto = $this->input->post("dt_serialauto");
		$dt_serial    = json_decode($dt_serial);
		$dt_serialkey = json_decode($dt_serialkey);
		$dt_serialauto = json_decode($dt_serialauto);

		// pengecekan sales order / type = 1
		if($ckOrder == 1):
			if($check):
				$status_delivery = true;
				foreach ($selldet as $key => $value) {
					if(in_array($selldet[$key], $check) && $product_status == 0):
						$SellNo = $product_sellno[$key];
						$xqty 	= $this->main->checkDuitInput($qty[$key]);
						if($xqty):
							$check_qty = $this->check_qty($SellNo,$selldet[$key],$product_id[$key],$xqty);
							if(!$check_qty):
								$data['inputerror'][] 	= $selldet[$key];
								$data['error_string'][] = 'product_selldet';
								$data['list'][] 		= 'list';
								$data['tab'][] 			= '';
								$data['message'] 		= $this->lang->line('lb_product_qty_stock');
								$data['status'] 		= FALSE;
								$status_delivery 		= false;
							else:
								$classnya = "vd".$selldet[$key];
								$xtype = $product_type[$key];
								$temp_data  = $this->api->temp_serial("delivery","",$classnya,$product_id[$key],"class");
								if($xtype == 2):
									$arrkey = array_keys($dt_serialkey,$classnya);
									if($xqty>count($arrkey)):
										$data['inputerror'][] 	= $selldet[$key];
										$data['error_string'][] = $this->lang->line('lb_serial_empty');
										$data['list'][] 		= 'list';
										$data['tab'][] 			= '';
										$data['message'] 		= $this->lang->line('lb_serial_empty');
										$data['status'] 		= FALSE;
										$status_delivery 		= false;
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
											$data['inputerror'][] 	= $selldet[$key];
											$data['error_string'][] = $message_sn;
											$data['list'][] 		= 'list';
											$data['tab'][] 			= '';
											$data['status'] 		= FALSE;
											$data['message'] 		= $message_sn;
											$status_delivery 		= false;
										endif;
									endif;
								endif;
							endif;

						else:
							$data['inputerror'][] 	= $selldet[$key];
							$data['error_string'][] = 'product_selldet';
							$data['list'][] 		= 'list';
							$data['tab'][] 			= '';
							$data['message'] 		= $this->lang->line('lb_product_qty_empty');
							$data['status'] 		= FALSE;
							$status_delivery 		= false;
						endif;
					endif;
				}
				if(!$status_delivery):
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
			endif;
		// pengecekan non sales order
		else:
			if($BranchID):
				$data_qty 	= $this->api->product_branch($BranchID,$arrProductID,"array");
			else:
				$data_qty	= $this->main->product_detail($CompanyID,$arrProductID,"array");
			endif;
			if($ckOrder != 1 and $crud != "insert"):
				$detail = $this->delivery->get_by_detail_non_order($DeliveryNo);
				foreach ($detail as $k => $v) {
					foreach ($data_qty as $kk => $vv) {
						if($v->ProductID == $vv->ProductID):
							$vv->Qty = $vv->Qty + $v->Qty;
						endif;
					}
				}
			endif;

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

			// pengecekan qty
			if(count($product_id) > 0):
				$status_qty = true;
				foreach($product_id as $key => $v):
					// jika qty nya kurang dari 1 atau kosong
					if($product_status == 0 and $product_id[$key]):
						$xqty = $this->main->checkDuitInput($qty[$key]);
						if($xqty < 1 || empty($xqty)):
							$data['inputerror'][] 	= $rowid[$key];
							$data['error_string'][] = $rowid[$key];
							$data['list'][] 		= 'list';
							$data['tab'][] 			= '';
							$data['message'] 		= $this->lang->line('lb_product_qty_empty');
							$data['status'] 		= FALSE;
							$status_qty 			= false;
						else:
							$inventory 		= $this->main->check_parameter_module("inventory","inventory");
							if($inventory->add>0):
								$xqty2  	= $xqty * $product_konv[$key];
								$check_qty 	= $this->check_qty_non_order($data_qty,$product_id[$key],$xqty2);
								$cek 		= $check_qty['status'];
								$data_qty 	= $check_qty['data'];
							else:
								$cek 		= TRUE;
							endif;
							if(!$cek):
								$data['inputerror'][] 	= $rowid[$key];
								$data['error_string'][] = $rowid[$key];
								$data['list'][] 		= 'list';
								$data['tab'][] 			= '';
								$data['message'] 		= $this->lang->line('lb_product_qty_stock');
								$data['status'] 		= FALSE;
								$status_qty 			= false;
							
							// pengecekan serial number
							else:
								$xtype = $product_type[$key];
								$temp_data  = $this->api->temp_serial("delivery","",$rowid[$key],$product_id[$key],"class");
								if($xtype == 2):
									$arrkey = array_keys($dt_serialkey,$rowid[$key]);
									if($xqty>count($arrkey)):
										$data['inputerror'][] 	= $rowid[$key];
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
														$message_sn = "Serial not found";
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
											$status_product 		= false;
										endif;
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

		if($ckOrder == 2):
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
		endif;

		if($this->input->post('CustomerID') == ''):
			$data['inputerror'][] 	= 'CustomerID';
			$data['error_string'][] = $this->lang->line('lb_customer_empty');
			$data['list'][] 		= '';
			$data['tab'][] 			= 'delivery';
			$data['message'] 		= $this->lang->line('lb_incomplete_form');
			$data['status'] 		= FALSE;
		endif;
		
		// if($this->input->post('SalesID') == ''):
		// 	$data['inputerror'][] 	= 'SalesID';
		// 	$data['error_string'][] = 'Sales cannot be null';
		// 	$data['list'][] 		= '';
		// 	$data['tab'][] 			= 'delivery';
		// 	$data['message'] 		= $this->lang->line('lb_incomplete_form');
		// 	$data['status'] 		= FALSE;
		// endif;

		// if($SellNo == ''):
		// 	$data['inputerror'][] 	= 'SellNo';
		// 	$data['error_string'][] = 'Sell No cannot be null';
		// 	$data['list'][] 		= '';
		// 	$data['message'] 		= $this->lang->line('lb_incomplete_form');
		// 	$data['status'] 		= FALSE;
		// endif;

		//address
		if($this->input->post('delAddress') == ''):
			$data['inputerror'][] 	= 'delAddress';
			$data['error_string'][] = $this->lang->line('lb_address_empty');
			$data['list'][] 		= '';
			$data['tab'][] 			= 'address';
			$data['message'] 		= $this->lang->line('lb_incomplete_form');
			$data['status'] 		= FALSE;
		endif;

		// if($this->input->post('delProvince') == ''):
		// 	$data['inputerror'][] 	= 'delProvince';
		// 	$data['error_string'][] = 'Province cannot be null';
		// 	$data['list'][] 		= '';
		// 	$data['tab'][] 			= 'address';
		// 	$data['message'] 		= $this->lang->line('lb_incomplete_form');
		// 	$data['status'] 		= FALSE;
		// endif;

		// if($this->input->post('delCity') == ''):
		// 	$data['inputerror'][] 	= 'delCity';
		// 	$data['error_string'][] = 'City cannot be null';
		// 	$data['list'][] 		= '';
		// 	$data['tab'][] 			= 'address';
		// 	$data['message'] 		= $this->lang->line('lb_incomplete_form');
		// 	$data['status'] 		= FALSE;
		// endif;

		if($data['status'] === FALSE)
        {
            header('Content-Type: application/json');
            echo json_encode($data,JSON_PRETTY_PRINT);  
            exit();
        }
	}
	
	private function check_qty($sellno,$selldet,$productid,$qty){
		$CompanyID 	= $this->session->CompanyID;
		$DeliveryNo = $this->input->post('DeliveryNo');
		$crud 		= $this->input->post('crud');
		$status = true;
		$qty 	= (float) $qty;

		if($crud == "update"):
			$d 		= $this->qty_delivery($sellno,$selldet,$productid,$DeliveryNo);
			$qty 	+= $d->Qty;
		else:
			$d 		= $this->qty_delivery($sellno,$selldet,$productid);
			$qty 	+= $d->Qty;
		endif;
		
		$data_sell 	 = $this->api->sellingdet_detail($selldet,$sellno);
		$sellQty 	 = $data_sell->Qty;
		$module_data = $this->main->check_module_stock($data_sell->Module);
		$data_product 	= $this->main->product_detail($CompanyID,$productid,"detail",$data_sell->BranchID);
		$productQty 	= $data_product->Qty;

		if($module_data->stock>0):
			if($qty>$sellQty || $qty>$productQty):
				$status = false;
			endif;
		else:
			if($qty>$sellQty):
				$status = false;
			endif;
		endif;

		return $status;
	}

	private function check_qty_non_order($data,$ProductID,$Qty){
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

	private function qty_delivery($sellno,$selldet,$productid,$DeliveryNo=""){
		$this->db->select("SUM(deliverydet.Qty) as Qty");
		$this->db->where("deliverydet.SellNo", $sellno);
		$this->db->where("deliverydet.SellDet", $selldet);
		$this->db->where("deliverydet.ProductID", $productid);
		$this->db->where("delivery.CompanyID", $this->session->CompanyID);
		$this->db->where("delivery.Status", 1);
		if($DeliveryNo != ""):
			$this->db->where("deliverydet.DeliveryNo !=",$DeliveryNo);
		endif;
		$this->db->join("PS_Delivery as delivery", "deliverydet.DeliveryNo = delivery.DeliveryNo and deliverydet.CompanyID = delivery.CompanyID", "left");
		$this->db->from("PS_Delivery_Det as deliverydet");
		$query 	= $this->db->get();
		$d 		= $query->row();

		return $query->row();
	}

	private function check_delivery_status($sellid){
		foreach ($sellid as $key => $SellNo) {
			$list = $this->main->sell_detail("sell",$SellNo);
			$status = true;
			foreach ($list as $k => $v) {
				if($v->delivery_qty != $v->product_qty):
					$status = false;
				endif;
			}

			if($status):
				$data = array("DeliveryStatus" => 1);
			else:
				$data = array("DeliveryStatus" => 0);
			endif;
			$this->db->where("CompanyID", $this->session->CompanyID);
			$this->db->where("SellNo", $SellNo);
			$this->db->update("PS_Sell", $data);	
		}
	}

	public function ajax_edit($id){
		$id 		= str_replace("-", "/", $id);
		$list 		= $this->delivery->get_by_id($id);
		$data_sell 	= array();
		$cancel 	= '';

		if($list->Type == 1):
			$detail 	= $this->delivery->get_by_detail($id);
			foreach ($detail as $k => $v) {
				$list_sell 	= $this->main->sell_detail("delivery", $v->SellNo, "update");
				$selldet 	= $this->sellingdet($detail);
				foreach ($list_sell as $k => $v) {
					if(!in_array($v->selldet, $selldet)):
						array_push($data_sell, $v);
					endif;
				}
			}
		else:
			$detail = $this->delivery->get_by_detail_non_order($id);
			$module_data 	= $this->main->check_module_stock($list->deliveryModule);
			foreach ($detail as $k => $v) {
				$stock 	= $v->product_stock;
				foreach ($detail as $kk => $vv) {
					if($v->ProductID == $vv->ProductID):
						if($module_data->stock>0):
							$stock += $vv->Qty;
						endif;
					endif;
				}
				$v->productStock = $stock;
			}
		endif;

		$data = array(
			"hakakses"	=> $this->session->hak_akses,
			"app"		=> $this->session->app,
			"list"		=> $list,
			"detail"	=> $detail,
			"data_sell"	=> $data_sell,
			"cancel" 	=> $cancel,
 		);

 		$this->main->echoJson($data);
	}

	private function sellingdet($list){
		$data = array();
		foreach ($list as $key => $v) {
			array_push($data,$v->SellDet);
		}
		return $data;
	}

	public function cetak($id){
		$idnya  	= $id;
		$id 		= str_replace("-", "/", $id);

		$this->main->default_template("delivery");

		$cetak 		= $this->input->get("cetak");
		$position 	= $this->input->get("position");
		$page 		= $this->input->get("page");
		$code 		= "Delivery".$id;

		$nama_laporan   = "";
        $datacompany    = $this->main->company("api");
        $company_name   = $datacompany->nama;
        $company_imgbin = $datacompany->img_bin;
        $company_imgurl = $datacompany->img_url;
        $logo           = base_url($company_imgurl);
		
		$list  		= $this->delivery->get_by_id($id,"edit");
		if($list->Type == 1):
			$detail 	= $this->delivery->get_by_detail($id);
		else:
			$detail 	= $this->delivery->get_by_detail_non_order($id);
		endif;

		$modul  	= $this->input->post("modul");
		$url_modul 	= $this->input->post("url_modul");
		$id_url 	= $this->main->id_menu($url_modul);
		$edit 		= $this->main->menu_ubah($id_url);
		$delete 	= $this->main->menu_hapus($id_url);

		$ar 	= $this->main->check_parameter_module("ar","ar");
		$data_action = array();
      	$cancel = '';
      	if($list->Status == 1 && $list->InvoiceCount<=0 && $ar->add>0 && $delete>0):
	        $cancel = $this->main->button_action("cancel",$idnya);
	        $data_action['cancel'] = $cancel;
      	endif;
		if($ar->add>0 && $list->Status == 1):
			$data_action['next'] = $this->main->button_action("invoice_selling",$idnya, $list->InvoiceStatus."-delivery");
		endif;
		if($ar->add>0 && $edit>0):
			$btn_edit = $this->main->button_action("edit_attach",$idnya);
			$data_action['edit'] = $btn_edit;
		endif;

		$data['page'] 			= $page;
		$data["cetak"]			= $cetak;
		$data['list']			= $list;
		$data["detail"]			= $detail;
		$data['title']  		= 'print delivery';
		$data['title2'] 		= $this->title;
		$data["company_name"]   = $company_name;
		$data["nama_laporan"]   = $nama_laporan;
    	$data["logo"]           = $logo;
    	$data["company"]		= $datacompany;
    	$data['data_action']	= $data_action;
    	if($page == "print"):
    		$data['template'] 	= $this->main->get_default_template("delivery");
    		$this->load->view('delivery/template',$data);
    	else:
    		$this->load->view('delivery/view',$data);
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
	}

	private function delete_deleverydet($deliverydet,$DeliveryNo){
		if(count($deliverydet)>0):
			$this->db->where_not_in("DeliveryDet", $deliverydet);
			$this->db->where("DeliveryNo", $DeliveryNo);
			$this->db->where("CompanyID", $this->session->CompanyID);
			$this->db->delete("PS_Delivery_Det");
		endif;
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

	private function calculationQty($DeliveryNo,$page=""){
		$header = $this->delivery->get_by_id($DeliveryNo);
		if($header->Type == 1):
			$detail = $this->delivery->get_by_detail($DeliveryNo);
		else:
			$detail  		= $this->delivery->get_by_detail_non_order($DeliveryNo);
		endif;
		if($page == "tambah"):
			foreach ($detail as $k => $v) {
				if($header->Type == 1):
					$module_data 	= $this->main->check_module_stock($v->sellModule);
					$this->kurangQtyDelivery($v->SellNo,$v->SellDet,$v->Qty);
				else:
					$module_data 	= $this->main->check_module_stock($header->deliveryModule);
				endif;
				if($module_data->stock>0):
					$this->tambahQty($v->ProductID,$v->Qty);
				endif;
			}
		endif;
	}

	private function tambahQtyDelivery($SellNo,$SellDet,$Qty){
		$CompanyID = $this->session->CompanyID;
		
		$this->db->query(
            "UPDATE PS_Sell_Detail set 
                DeliveryQty=ifnull(DeliveryQty,0)+$Qty
            WHERE
                SellDet 	= '$SellDet' and 
                SellNo 		= '$SellNo' and 
                CompanyID 	= '$CompanyID'
        ");
	}

	private function kurangQtyDelivery($SellNo,$SellDet,$Qty){
		$CompanyID = $this->session->CompanyID;
		
		$this->db->query(
            "UPDATE PS_Sell_Detail set 
                DeliveryQty=DeliveryQty-$Qty
            WHERE
                SellDet 	= '$SellDet' and 
                SellNo 		= '$SellNo' and 
                CompanyID 	= '$CompanyID'
        ");
	}

	public function cancel($id){
		$this->main->validate_modlue_add("ar","ar");
		$id 		= str_replace("-", "/", $id);
		$CompanyID = $this->session->CompanyID;
		$cek = $this->db->count_all("PS_Delivery where DeliveryNo = '$id' and CompanyID = '$CompanyID' and Status = '1'");
		if($cek>0):
			$header = $this->delivery->get_by_id($id);
			if($header->Type == 1):
				$detail = $this->delivery->get_by_detail($id);
			else:
				$detail = $this->delivery->get_by_detail_non_order($id);
				$module_data 	= $this->main->check_module_stock($header->deliveryModule);
			endif;
			
			foreach ($detail as $key => $v) {
				if($header->Type == 1):
					$module_data 	= $this->main->check_module_stock($v->sellModule);
					$BranchID 	= $v->BranchID;
				else:
					$BranchID 	= $header->BranchID;
				endif;
				if($module_data->stock>0):
					$Qty = $v->Qty * $v->Conversion;
					$this->tambahQty($v->ProductID,$Qty,$BranchID);
				endif;
				if($header->Type == 1):
					$this->kurangQtyDelivery($v->SellNo,$v->SellDet,$v->Qty);
					// serial number
					if($v->Type == 2):
						$data_sn = $this->delivery->serial_number($id,$v->DeliveryDet,$v->ProductID);
						foreach ($data_sn as $k2 => $v2) {
							// update ke table PS_Sell_Detail_SN
							$data_serial = array(
								"Status"		=> 1,
								"User_Ch"		=> $this->session->NAMA,
								"Date_Ch"		=> date("Y-m-d H:i:s"),
							);
							$this->db->where("CompanyID", $CompanyID);
							$this->db->where("ProductID", $v->ProductID);
							$this->db->where("SellNo", $v->SellNo);
							$this->db->where("SellDet", $v->SellDet);
							$this->db->where("SN", $v2->SN);
							$this->db->update("PS_Sell_Detail_SN", $data_serial);
						}
					endif;
				
				else:
					if($v->Type == 2):
						$data_sn = $this->delivery->serial_number($id,$v->DeliveryDet,$v->ProductID);
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
				endif;
				$data_detail = array("Status" => 0);
				$this->delivery->update_det(array("DeliveryDet"=>$v->DeliveryDet, "CompanyID" => $CompanyID,),$data_detail);
			}
			$data = array("Status" => 0);
			$this->delivery->update(array("DeliveryNo"=>$id, "CompanyID" => $CompanyID,),$data);

			if($header->Type == 1):
				$data_sell = array("DeliveryStatus" => 0);
				$this->db->where("CompanyID", $CompanyID);
				$this->db->where("SellNo", $v->SellNo);
				$this->db->update("PS_Sell", $data_sell);
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

	// serial number
	public function ajax_edit_serial($id){
		$CompanyID		= $this->session->CompanyID;
		$detail 		= $this->delivery->get_by_detail($id,"detail");

		$serial 		= json_decode($detail->product_serial);
		$list_serial 	= array();
		if(!$serial):
			$serial = array();
		endif;
		foreach ($serial as $key => $v) {
			$cek = $this->db->count_all("
					PS_Delivery_Det_SN dt join PS_Delivery d on dt.DeliveryNo = d.DeliveryNo and dt.CompanyID = d.CompanyID
					where dt.CompanyID = '$CompanyID' and d.Status = '1' and dt.DeliveryDet != '$id' and dt.ProductID = '$detail->ProductID' and dt.SerialNumber = '$v->SerialNumber'");
			if($cek<=0):
				array_push($list_serial, $v);
			endif;
		}

		$data = array(
			"product_code" 		=> $detail->productCode,
			"product_name" 		=> $detail->productName,
			"product_type" 		=> $detail->product_type_txt,
			"product_type_txt"	=> $detail->product_type_txt,
			"productid" 		=> $detail->ProductID,
			"detail_code" 		=> $detail->DeliveryDet,
			"code" 				=> $detail->DeliveryNo,
			"konv" 				=> $detail->Conversion,
			"price" 			=> $detail->Price,
			"serial_qty" 		=> $detail->Qty,
			"unit_name" 		=> $detail->unitName,
			"unitid" 			=> $detail->UnitID,
			"page"				=> "add_serial_delivery",
			"page_type"			=> "not_array",
			"list_serial" 		=> $list_serial,
			"serial_number"		=> $this->delivery->get_serial_by_id($detail->DeliveryDet,$detail->DeliveryNo),
		);
        $this->main->echoJson($data);
	}

	public function save_serial(){
		$page			= $this->input->post('page');
		if($page == "delivery"):
			$this->validate_serial();
		endif;
		
		$CompanyID 		= $this->session->CompanyID;
		$header_code 	= $this->input->post('header_code');
		$detail_code 	= $this->input->post('detail_code');
		$productid 		= $this->input->post('productid');
		$product_type 	= $this->input->post('product_type');
		$serial_checkbox = $this->input->post('serial_checkbox');
		$serial_number 	 = $this->input->post('serial_number');
		$serial_id 	 	 = $this->input->post('serial_id');

		$arr_serialid = array();
		if($page == "delivery"):
			foreach ($serial_number as $key => $v) {
				if(in_array($serial_number[$key], $serial_checkbox)):
					$data = array(
						"CompanyID"		=> $CompanyID,
						"DeliveryNo"	=> $header_code,
						"DeliveryDet"	=> $detail_code,
						"ProductID"		=> $productid,
						"SerialNumber"	=> $serial_number[$key],
					);
					if($serial_id[$key]):
						$detid = $serial_id[$key];
						$where = array(
							"DeliveryDetSN" 	=> $serial_id[$key],
						);
						$this->delivery->update_serial($where,$data);
					else:
						$detid = $this->delivery->save_serial($data);
					endif;
					array_push($arr_serialid, $detid);
				endif;
			}
		else:
			foreach ($serial_number as $key => $v) {
				$data = array(
					"CompanyID"		=> $CompanyID,
					"DeliveryNo"	=> $header_code,
					"DeliveryDet"	=> $detail_code,
					"ProductID"		=> $productid,
					"SerialNumber"	=> $serial_number[$key],
				);
				if($serial_id[$key]):
					$detid = $serial_id[$key];
					$where = array(
						"DeliveryDetSN" 	=> $serial_id[$key],
					);
					$this->delivery->update_serial($where,$data);
				else:
					$detid = $this->delivery->save_serial($data);
				endif;
				array_push($arr_serialid, $detid);
			}
		endif; 

		$this->delete_serial($arr_serialid, $header_code,$detail_code);

		$output = array(
			"status" 	=> true,
			"message"	=> $this->lang->line('lb_success'),
			"page"		=> $page,
		);

		$this->main->echoJson($output);
	}
	private function validate_serial(){
		$data = array();
		$data['status'] = TRUE;

		$serial_checkbox = $this->input->post('serial_checkbox');
		$serial_qty 	 = $this->input->post('serial_qty');
		
		if(count($serial_checkbox)>$serial_qty):
			$data['inputerror'][] 	= '';
			$data['error_string'][] = '';
			$data['list'][] 		= '';
			$data['message'] 		= "Total serial number not balance with total qty";
			$data['status'] 		= FALSE;
		endif;

		if(!$serial_checkbox):
			$data['inputerror'][] 	= '';
			$data['error_string'][] = '';
			$data['list'][] 		= '';
			$data['message'] 		= "Serial Number cannot be null";
			$data['status'] 		= FALSE;
		endif;

		if($data['status'] === FALSE)
        {
            header('Content-Type: application/json');
            echo json_encode($data,JSON_PRETTY_PRINT);  
            exit();
        }
	}
	private function delete_serial($array,$header,$detail){
		if(count($array)>0):
			$this->db->where("CompanyID", $this->session->CompanyID);
			$this->db->where("DeliveryNo", $header);
			$this->db->where("DeliveryDet", $detail);
			$this->db->where_not_in("DeliveryDetSN", $array);
			$this->db->delete("PS_Delivery_Det_SN");
		endif;
	}
	// end serial number

	public function save_remark(){
		$CompanyID 	= $this->session->CompanyID;
		$ID 		= $this->input->post("ID");
		$Remark 	= $this->input->post("Remark");

		$status 	= false;
		$message 	= $this->lang->line('lb_data_not_found');

		if($CompanyID && $ID):
			$cek = $this->db->count_all("PS_Delivery where CompanyID = '$CompanyID' and DeliveryNo = '$ID'");
			if($cek>0):
				$data = array(
					"Remark"	=> $Remark,
				);
				$this->db->where("CompanyID", $CompanyID);
				$this->db->where("DeliveryNo", $ID);
				$this->db->update("PS_Delivery", $data);

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

		$list = $this->delivery->serial_number_list($mt,$dt);

		$output = array(
			"status"	=> true,
			"list"		=> $list,
		);

		$this->main->echoJson($output);
	}
}