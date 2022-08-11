<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Return_sales extends CI_Controller {
	var $title = 'Sales Return';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_return_sales",'return');
		$this->main->cek_session();
		$this->title = $this->lang->line('lb_returnar');
	}
	public function index()
	{	
		$url 		= $this->uri->segment(1); 
		$id_url 	= $this->main->id_menu($url);
		$read 		= $this->main->read($id_url);
		$ar 	= $this->main->check_parameter_module("ar","return_ar");
		if($read == 0 or $ar->view == 0){ redirect(); }
		$tambah 	= $this->main->menu_tambah($id_url);
		if($tambah > 0 and $ar->add>0):
            $tambah = $this->main->general_button('add',$this->lang->line('lb_add_new').' '.$this->title);
		else: 
			$tambah = ""; 
		endif;
		$this->main->delete_temp_sn("return_sales");
		#ini untuk session halaman aturan user privileges;
		$data['title']  	= $this->title;
		$data['tambah'] 	= $tambah;
		$data['modal'] 		= 'return_sales/modal';
		$data['modal_vendor'] = 'modal/modal_vendor';
		$data['page'] 		= 'return_sales/list';
		$data['modul'] 		= 'return_sales';
		$data['url_modul'] 	= $url;
		$this->load->view('index',$data);
	}

	public function ajax_list($url_modul="",$modul=""){
		$CompanyID  = $this->session->CompanyID;
		$id_url = $this->main->id_menu($url_modul);
		$edit 	= $this->main->menu_ubah($id_url);
		$delete = $this->main->menu_hapus($id_url);
		$list 	= $this->return->get_datatables();
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		$ar 	= $this->main->check_parameter_module("ar","return_ar");
		foreach ($list as $a) {
			$btn_edit 		= '';
			$btn_cancel 	= '';
			$btn_view 		= '';

			$ReturNo 		= str_replace("/", "-", $a->ReturNo);

			$status 		= $this->main->label_active($a->Status,"",$ReturNo);
			$return_type 	= $this->main->label_return_type($a->ReturType,"",$ReturNo);
			$btn_print 		= $this->main->button_action_dropdown("print", $ReturNo);

			if($edit>0 and $ar->add>0):
				$btn_view 		= $this->main->button_action_dropdown("view", $ReturNo);
				$btn_edit 		= $this->main->button_action_dropdown("edit", $ReturNo);
			endif;

			if($delete>0 and $ar->add>0):
				if($a->Status == 1):
					$btn_cancel = $this->main->button_action_dropdown("cancel", $ReturNo);
				else:
					$btn_edit = '';
				endif;
			endif;

			$cek_invoice = $a->ck_invoice;
			if($cek_invoice>0):
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

	        // $code = '<a href="javascript:;" onclick="view('."'".$ReturNo."','print'".')">'.$a->ReturNo."</a>";
	        $btn_action 	= $this->main->button_action("code", $ReturNo,$a->ReturNo);
	        $vendor 		= $this->main->button_action("general_onclick","redirect_post('partner','".$a->VendorID."')",$a->vendorName);

			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $btn_action;
			$row[] 	= $a->Date; 
			$row[] 	= $vendor; 
			$row[]  = $a->transactionCode;
			$row[] 	= $this->main->qty($a->Qty); 
			$row[] 	= $this->main->currency($a->Total);
			$row[] 	= $return_type;
			$row[] 	= $status; 
			$row[] 	= $button; 
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->return->count_all(),
			"recordsFiltered" => $this->return->count_filtered(),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function save(){
		$this->validate();

		$CompanyID 	= $this->session->CompanyID;
		$crud 		= $this->input->post('crud');
		$ReturnNo   = $this->input->post('ReturnNo');
		$Date 		= $this->input->post('Date');
		$CustomerID = $this->input->post('CustomerID');
		$CustomerID = explode("-", $CustomerID);
		$Remark 	= $this->input->post('Remark');
		$ckOrder 	= $this->input->post('ckOrder');
		$SellNo 	= $this->input->post('SellNo');
		$DeliveryNo = $this->input->post('DeliveryNo');
		$SalesID 	= $this->input->post('SalesID');
		$SalesID 	= explode("-", $SalesID);

		// product
		$check 				= $this->input->post('check');
		$detid 				= $this->input->post('detid');
		$product_selldet 	= $this->input->post('product_selldet');
		$product_sellno 	= $this->input->post('product_sellno');
		$product_delno 		= $this->input->post('product_delno');
		$product_deldet 	= $this->input->post('product_deldet');
		$qty 				= $this->input->post('product_qty');
		$product_id 		= $this->input->post('product_id');
		$product_code 		= $this->input->post('product_code');
		$product_name 		= $this->input->post('product_name');
		$product_qty 		= $this->input->post('product_qty');
		$product_unit 		= $this->input->post('product_unit');
		$product_unitid 	= $this->input->post('product_unitid');
		$product_type 		= $this->input->post('product_type');
		$product_konv 		= $this->input->post('product_konv');
		$product_price 		= $this->input->post('product_price');
		$product_subtotal 	= $this->input->post('product_subtotal');
		$product_discount 	= $this->input->post('product_discount');
		$product_discountrp = $this->input->post('product_discountrp');
		$product_remark 	= $this->input->post('product_remark');
		$product_module 	= $this->input->post('product_module');

		// serial post
		$dt_serial 	  = $this->input->post("dt_serial");
		$dt_serialkey = $this->input->post("dt_serialkey");
		$dt_serialauto = $this->input->post("dt_serialauto");
		$dt_serial    = json_decode($dt_serial);
		$dt_serialkey = json_decode($dt_serialkey);
		$dt_serialauto = json_decode($dt_serialauto);

		$data = array(
			"CompanyID" 	=> $CompanyID,
			"VendorID"		=> $CustomerID[0],
			"SalesID"		=> $SalesID[0],
			"Date"			=> $Date,
			"Remark" 		=> $Remark,
			"Type"			=> 2,
			"Tax"			=> $this->input->post('product_tax2')[0],
		);
		if($ckOrder == 3):
			$data['SellNo'] = $SellNo;
			$data['ReturType']	= 3;
		else:
			$data['DeliveryNo'] = $DeliveryNo;
			$data['ReturType']	= 4;
		endif;

		if($crud == "update"):
			$code  = $ReturnNo;
			$where = array("ReturNo" => $code, "CompanyID" => $CompanyID);
			$this->calculationQty($code);
			$this->return->update($where,$data);
		else:
			$code = $this->main->return_sales_generate();
			$data['ReturNo'] = $code;
			$data['Status']	 = 1;
			$this->return->save($data);
		endif;

		$returndetid 	= array();
		$xjurnalType 	= '';
		// detail selling
		if($ckOrder == 3):
			$xjurnalType = 'returnsales_so';
			foreach ($product_selldet as $key => $value) {
				if(in_array($product_selldet[$key], $check)):
					$BranchID= $this->main->get_one_column("PS_Sell","BranchID",array("CompanyID" => $CompanyID, "SellNo" => $product_sellno[$key]))->BranchID;

					$xqty  	= $this->main->checkDuitInput($qty[$key]);
					$xqty2 	= $xqty * $product_konv[$key];

					$data_det = array(
						"CompanyID"		=> $CompanyID,
						"ReturNo"		=> $code,
						"SellNo"		=> $product_sellno[$key],
						"SellDet"		=> $product_selldet[$key],
						"ProductID"		=> $product_id[$key],
						"Qty"			=> $xqty,
						"Conversion"	=> $product_konv[$key],
						// "UnitID"		=> $product_unitid[$key],
						"Uom"			=> $product_unitid[$key],
						"Type"			=> $product_type[$key],
						"Price"			=> $this->main->checkDuitInput($product_price[$key]),
						"Total"			=> $this->main->checkDuitInput($product_subtotal[$key]),
						"Discount"		=> $this->main->checkDuitInput($product_discountrp[$key]),
						"DiscountPersent" => $this->main->checkDuitInput($product_discount[$key]),
						"Remark" 		=> $product_remark[$key],
						"BranchID"		=> $BranchID,
					);

					if($detid[$key] == ""):
						$det_id = $this->return->save_det($data_det);
						array_push($returndetid, $det_id);
					else:
						$det_id = $detid[$key];
						$where = array("ReturDet" => $det_id, "CompanyID" => $CompanyID);
						$this->return->update_det($where,$data_det);
						array_push($returndetid, $det_id);
					endif;
					$d_module = $product_module[$key];
					$d_module = $this->main->relpace_str($d_module,"'",'"');
					$module_data = $this->main->check_module_stock($d_module);
					
					if($module_data->stock>0):
						$this->main->retur_qty("cancel",$product_id[$key], $xqty2,$BranchID);
					endif;

					// serial number
					$xtype = $product_type[$key];
					if($xtype == 2):
						$classnya = "vd".$product_selldet[$key];
						$arrkey = array_keys($dt_serialkey,$classnya);
						foreach ($arrkey as $key2 => $value_key) {
							$no = $key2 + 1;
							if($xqty>=$no):
								$sn = $dt_serial[$value_key];
								
								// insert ke table AP_Retur_Det_SN
								$data_serial = array(
									"CompanyID"		=> $CompanyID,
									"Status"		=> 1,
									"Qty"			=> 1,
									"ProductID"		=> $product_id[$key],
									"ReturNo"		=> $code,
									"ReturDet" 		=> $det_id,
									"User_Add"		=> $this->session->NAMA,
									"Date_Add"		=> date("Y-m-d H:i:s"),
									"SN"			=> $sn,
								);
								$this->db->insert("AP_Retur_Det_SN", $data_serial);

								// update ke table PS_Sell_Detail_SN
								$data_serial = array(
									"Status"		=> 0,
									"User_Ch"		=> $this->session->NAMA,
									"Date_Ch"		=> date("Y-m-d H:i:s"),
								);
								$this->db->where("CompanyID", $CompanyID);
								$this->db->where("ProductID", $product_id[$key]);
								$this->db->where("SellNo", $product_sellno[$key]);
								$this->db->where("SellDet", $product_selldet[$key]);
								$this->db->where("SN", $sn);
								$this->db->update("PS_Sell_Detail_SN", $data_serial);

								// update ke table PS_Product_Serial
								$data_serial = array(
									"Status"		=> 1,
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
		// detail delivery
		else:
			$xjurnalType = 'returnsales_delivery';
			foreach ($product_deldet as $key => $value) {
				if(in_array($product_deldet[$key], $check)):

					$BranchID = $this->main->delivery_detail("detail",$product_deldet[$key],$product_delno[$key])->BranchID;
					$xqty  	= $this->main->checkDuitInput($qty[$key]);
					$xqty2 	= $xqty * $product_konv[$key];
					$data_det = array(
						"CompanyID"		=> $CompanyID,
						"ReturNo"		=> $code,
						"DeliveryNo"	=> $product_delno[$key],
						"DeliveryDet"	=> $product_deldet[$key],
						"ProductID"		=> $product_id[$key],
						"Qty"			=> $xqty,
						"Conversion"	=> $product_konv[$key],
						// "UnitID"		=> $product_unitid[$key],
						"Uom"			=> $product_unitid[$key],
						"Type"			=> $product_type[$key],
						"Price"			=> $this->main->checkDuitInput($product_price[$key]),
						"Total"			=> $this->main->checkDuitInput($product_subtotal[$key]),
						"Discount"		=> $this->main->checkDuitInput($product_discountrp[$key]),
						"DiscountPersent" => $this->main->checkDuitInput($product_discount[$key]),
						"Remark" 		=> $product_remark[$key],
						"BranchID"		=> $BranchID,
					);

					if($detid[$key] == ""):
						$det_id = $this->return->save_det($data_det);
						array_push($returndetid, $det_id);
					else:
						$det_id = $detid[$key];
						$where = array("ReturDet" => $det_id, "CompanyID" => $CompanyID);
						$this->return->update_det($where,$data_det);
						array_push($returndetid, $det_id);
					endif;
					$d_module = $product_module[$key];
					$d_module = $this->main->relpace_str($d_module,"'",'"');
					$module_data = $this->main->check_module_stock($d_module);
					if($module_data->stock>0):
						$this->main->retur_qty("cancel",$product_id[$key], $xqty2,$BranchID);
					endif;

					// serial number
					$xtype = $product_type[$key];
					if($xtype == 2):
						$classnya = "vd".$product_deldet[$key];
						$arrkey = array_keys($dt_serialkey,$classnya);
						foreach ($arrkey as $key2 => $value_key) {
							$no = $key2 + 1;
							if($xqty>=$no):
								$sn = $dt_serial[$value_key];
								
								// insert ke table AP_Retur_Det_SN
								$data_serial = array(
									"CompanyID"		=> $CompanyID,
									"Status"		=> 1,
									"Qty"			=> 1,
									"ProductID"		=> $product_id[$key],
									"ReturNo"		=> $code,
									"ReturDet" 		=> $det_id,
									"User_Add"		=> $this->session->NAMA,
									"Date_Add"		=> date("Y-m-d H:i:s"),
									"SN"			=> $sn,
								);
								$this->db->insert("AP_Retur_Det_SN", $data_serial);

								// update ke table PS_Delivery_Det_SN
								$data_serial = array(
									"Status"		=> 0,
									"User_Ch"		=> $this->session->NAMA,
									"Date_Ch"		=> date("Y-m-d H:i:s"),
								);
								$this->db->where("CompanyID", $CompanyID);
								$this->db->where("ProductID", $product_id[$key]);
								$this->db->where("DeliveryNo", $product_delno[$key]);
								$this->db->where("DeliveryDet", $product_deldet[$key]);
								$this->db->where("SN", $sn);
								$this->db->update("PS_Delivery_Det_SN", $data_serial);

								// update ke table PS_Product_Serial
								$data_serial = array(
									"Status"		=> 1,
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
		$this->delete_returndet($returndetid,$code);
		$this->main->delete_temp_sn("return_sales");
		// end

		// generate journal
		$this->db->query("CALL run_generate_jurnal('UPDATE', '$code', '$xjurnalType', '$CompanyID')");

		$res = array(
			"status" 	=> true,
			"message"	=> $this->lang->line('lb_success'),
			"ID"		=> $code,
		);

		$this->main->echoJson($res);
	}

	private function validate(){
		$this->main->validate_update();
		$this->main->validate_modlue_add("ar","ar");
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

		$CompanyID 	= $this->session->CompanyID;
		$ckOrder 	= $this->input->post('ckOrder');
		$ReturNo 	= $this->input->post('ReturNo');
		$crud 		= $this->input->post('crud');
		$Remark 	= $this->input->post('Remark');

		// product
		$check 				= $this->input->post('check');
		$qty 				= $this->input->post('product_qty');
		$product_id 		= $this->input->post('product_id');
		$product_sellno 	= $this->input->post('product_sellno');
		$product_selldet 	= $this->input->post('product_selldet');
		$product_delno 		= $this->input->post('product_delno');
		$product_deldet 	= $this->input->post('product_deldet');
		$product_type 		= $this->input->post('product_type');
		$product_konv 		= $this->input->post('product_konv');

		// serial post
		$dt_serial 	  	= $this->input->post("dt_serial");
		$dt_serialkey 	= $this->input->post("dt_serialkey");
		$dt_serialauto 	= $this->input->post("dt_serialauto");
		$dt_serial    	= json_decode($dt_serial);
		$dt_serialkey 	= json_decode($dt_serialkey);
		$dt_serialauto 	= json_decode($dt_serialauto);

		if($check):
			// pengecekan untuk type order sales
			if($ckOrder == 3):
				$status_sell = true;
				foreach ($product_selldet as $key => $v) {
					if(in_array($product_selldet[$key], $check)):
						$xqty  	= $this->main->checkDuitInput($qty[$key]);
						$xqty2 	= $xqty * $product_konv[$key];
						if($xqty):
							$check_qty = $this->check_qty_sell($product_sellno[$key],$product_selldet[$key],$product_id[$key],$xqty);
							if(!$check_qty):
								$data['inputerror'][] 	= $product_selldet[$key];
								$data['error_string'][] = $this->lang->line('lb_sales_qty_empty');
								$data['list'][] 		= 'list';
								$data['tab'][] 			= '';
								$data['message'] 		= $this->lang->line('lb_sales_qty_empty');
								$data['status'] 		= FALSE;
								$status_sell 		= false;

							// pengecekan serial number
							else:
								$classnya = "vd".$product_selldet[$key];
								$xtype = $product_type[$key];
								$temp_data  = $this->api->temp_serial("return_sales","",$classnya,$product_id[$key],"class");
								if($xtype == 2):
									$arrkey = array_keys($dt_serialkey,$classnya);
									if($xqty>count($arrkey)):
										$data['inputerror'][] 	= $product_selldet[$key];
										$data['error_string'][] = $this->lang->line('lb_serial_empty');
										$data['list'][] 		= 'list';
										$data['tab'][] 			= '';
										$data['message'] 		= $this->lang->line('lb_serial_empty');
										$data['status'] 		= FALSE;
										$status_sell 		= false;
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
											$data['inputerror'][] 	= $product_selldet[$key];
											$data['error_string'][] = $message_sn;
											$data['list'][] 		= 'list';
											$data['tab'][] 			= '';
											$data['status'] 		= FALSE;
											$data['message'] 		= $message_sn;
											$status_sell 		= false;
										endif;
									endif;
								endif;
							endif;
						else:
							$data['inputerror'][] 	= $product_selldet[$key];
							$data['error_string'][] = $this->lang->line('lb_product_qty_empty');
							$data['list'][] 		= 'list';
							$data['tab'][] 			= '';
							$data['message'] 		= $this->lang->line('lb_product_qty_empty');
							$data['status'] 		= FALSE;
							$status_sell 			= false;
						endif;
					endif;
				}
				if(!$status_sell):
					echo json_encode($data);
					exit();
				endif;
			// pengecekan untuk type delivery
			else:
				$status_delivery = true;
				foreach ($product_deldet as $key => $v) {
					if(in_array($product_deldet[$key], $check)):
						$xqty  	= $this->main->checkDuitInput($qty[$key]);
						$xqty2 	= $xqty * $product_konv[$key];
						if($xqty):
							$check_qty = $this->check_qty_delivery($product_delno[$key],$product_deldet[$key],$product_id[$key],$xqty);
							if(!$check_qty):
								$data['inputerror'][] 	= $product_deldet[$key];
								$data['error_string'][] = $this->lang->line('lb_delivery_qty_empty');
								$data['list'][] 		= 'list';
								$data['tab'][] 			= '';
								$data['message'] 		= $this->lang->line('lb_delivery_qty_empty');
								$data['status'] 		= FALSE;
								$status_delivery 		= false;

							// pengecekan serial number
							else:
								$classnya = "vd".$product_deldet[$key];
								$xtype = $product_type[$key];
								$temp_data  = $this->api->temp_serial("return_sales","",$classnya,$product_id[$key],"class");
								if($xtype == 2):
									$arrkey = array_keys($dt_serialkey,$classnya);
									if($xqty>count($arrkey)):
										$data['inputerror'][] 	= $product_deldet[$key];
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
											$data['inputerror'][] 	= $product_deldet[$key];
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
							$data['inputerror'][] 	= $product_deldet[$key];
							$data['error_string'][] = $this->lang->line('lb_product_qty_empty');
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

		if($this->input->post('CustomerID') == ''):
			$data['inputerror'][] 	= 'CustomerID';
			$data['error_string'][] = $this->lang->line('lb_customer_empty');
			$data['list'][] 		= '';
			$data['tab'][] 			= '';
			$data['message'] 		= $this->lang->line('lb_incomplete_form');
			$data['status'] 		= FALSE;
		endif;

		if($this->input->post('SalesID') == ''):
			$data['inputerror'][] 	= 'SalesID';
			$data['error_string'][] = '';
			$data['list'][] 		= '';
			$data['tab'][] 			= '';
			$data['message'] 		= $this->lang->line('lb_incomplete_form');
			$data['status'] 		= FALSE;
		endif;

		if($Remark == ''):
			$data['inputerror'][] 	= 'Remark';
			$data['error_string'][] = $this->lang->line('lb_remark_empty');
			$data['list'][] 		= '';
			$data['tab'][] 			= '';
			$data['message'] 		= $this->lang->line('lb_incomplete_form');
			$data['status'] 		= FALSE;
		endif;

		if($ckOrder == 3):
			if($this->input->post('SellNo') == ''):
				$data['inputerror'][] 	= 'SellNo';
				$data['error_string'][] = 'Sell Code cannot be null';
				$data['list'][] 		= '';
				$data['tab'][] 			= '';
				$data['message'] 		= $this->lang->line('lb_incomplete_form');
				$data['status'] 		= FALSE;
			endif;
		else:
			if($this->input->post('DeliveryNo') == ''):
				$data['inputerror'][] 	= 'DeliveryNo';
				$data['error_string'][] = 'Delivery Code cannot be null';
				$data['list'][] 		= '';
				$data['tab'][] 			= '';
				$data['message'] 		= $this->lang->line('lb_incomplete_form');
				$data['status'] 		= FALSE;
			endif;
		endif;

		if($data['status'] === FALSE)
        {
            header('Content-Type: application/json');
            echo json_encode($data,JSON_PRETTY_PRINT);  
            exit();
        }
	}

	// pengecekan untuk type sales order
	private function check_qty_sell($sellno,$selldet,$productid,$qty){
		$CompanyID 	= $this->session->CompanyID;
		$crud 		= $this->input->post('crud');
		$ReturnNo 	= $this->input->post('ReturnNo');
		$status 	= true;
		$qty 		= (float) $qty;

		if($crud == "update"):
			$d 		= $this->qty_return($sellno,$selldet,$productid,"sell",$ReturnNo);
			$qty 	+= $d->Qty;
		else:
			$d 		= $this->qty_return($sellno,$selldet,$productid,"sell");
			$qty 	+= $d->Qty;
		endif;

		$data_delivery 	= $this->api->sellingdet_detail($selldet,$sellno);
		$delQty 	= $data_delivery->Qty;

		if($qty>$delQty):
			$status = false;
		endif;

		return $status;
	}
	// pengecetak untuk type delivery
	private function check_qty_delivery($deliveryno,$deliverydet,$productid,$qty){
		$CompanyID 	= $this->session->CompanyID;
		$crud 		= $this->input->post('crud');
		$ReturnNo 	= $this->input->post('ReturnNo');
		$status 	= true;
		$qty 		= (float) $qty;

		if($crud == "update"):
			$d 		= $this->qty_return($deliveryno,$deliverydet,$productid,"delivery",$ReturnNo);
			$qty 	+= $d->Qty;
		else:
			$d 		= $this->qty_return($deliveryno,$deliverydet,$productid,"delivery");
			$qty 	+= $d->Qty;
		endif;

		$data_delivery 	= $this->main->delivery_detail("detail",$deliverydet);
		$delQty 	= $data_delivery->Qty;

		if($qty>$delQty):
			$status = false;
		endif;

		return $status;
	}

	private function qty_return($no,$det,$productid,$page="",$ReturNo=""){
		$this->db->select("SUM(returndet.Qty) as Qty");
		if($page == "delivery"):
			$this->db->where("returndet.DeliveryNo", $no);
			$this->db->where("returndet.DeliveryDet", $det);
			$this->db->where("return.ReturType", 4);
		else:
			$this->db->where("returndet.SellNo", $no);
			$this->db->where("returndet.SellDet", $det);
			$this->db->where("return.ReturType", 3);
		endif;
		$this->db->where("returndet.ProductID", $productid);
		$this->db->where("returndet.CompanyID", $this->session->CompanyID);
		$this->db->where("return.Status", 1);
		$this->db->where("return.Type", 2);
		if($ReturNo != ""):
			$this->db->where("returndet.ReturNo !=",$ReturNo);
		endif;
		$this->db->join("AP_Retur as return", "return.ReturNo = returndet.ReturNo and return.CompanyID = returndet.CompanyID", "left");
		$this->db->from("AP_Retur_Det as returndet");
		$query 	= $this->db->get();
		$d 		= $query->row();

		return $query->row();
	}

	private function delete_returndet($array,$code){
		if(count($array)>0):
			$this->db->where_not_in("ReturDet", $array);
			$this->db->where("ReturNo", $code);
			$this->db->where("CompanyID", $this->session->CompanyID);
			$this->db->delete("AP_Retur_Det");
		endif;
	}

	public function cetak($id){
		$idnya 		= $id;
		$id 		= str_replace("-", "/", $id);
		$this->main->default_template("return_sales");
		$cetak 		= $this->input->get("cetak");
		$position 	= $this->input->get("position");
		$page 		= $this->input->get("page");
		$code 		= "Return Sales ".$id;

		$nama_laporan   = "";
        $datacompany    = $this->main->company("api");
        $company_name   = $datacompany->nama;
        $company_imgbin = $datacompany->img_bin;
        $company_imgurl = $datacompany->img_url;
        $logo           = base_url($company_imgurl);
		
		$list  		= $this->return->get_by_id($id,"edit");
		$detail 	= $this->return->get_by_detail($id,$list->ReturType);

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
			$data_action['next'] = $this->main->button_action("invoice_selling",$idnya, $list->InvoiceStatus."-return");
		endif;
		if($ar->add>0 && $edit>0):
			$btn_edit = $this->main->button_action("edit_attach",$idnya);
			$data_action['edit'] = $btn_edit;
		endif;

		$data['page'] 			= $page;
		$data["cetak"]			= $cetak;
		$data['list']			= $list;
		$data["detail"]			= $detail;
		$data['title']  		= 'print return sales';
		$data['title2'] 		= $this->title;
		$data["company_name"]   = $company_name;
		$data["nama_laporan"]   = $nama_laporan;
    	$data["logo"]           = $logo;
    	$data["company"]		= $datacompany;
    	$data['data_action']	= $data_action;
    	if($page == "print"):
    		$data['template'] 	= $this->main->get_default_template("return_sales");
    		$this->load->view('return_sales/template',$data);
    	else:
    		$this->load->view('return_sales/view',$data);
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

	public function cancel($id){
		$this->main->validate_modlue_add("ar","ar");
		$id 		= str_replace("-", "/", $id);
		$CompanyID = $this->session->CompanyID;
		$cek = $this->db->count_all("AP_Retur where ReturNo = '$id' and CompanyID = '$CompanyID' and Status = '1'");
		if($cek>0):
			$header = $this->return->get_by_id($id);
			$detail = $this->return->get_by_detail($id,$header->ReturType);
			foreach ($detail as $k => $v) {
				$Qty = $v->Qty * $v->Conversion;
				$this->main->retur_qty("done",$v->ProductID, $Qty, $v->BranchID);

				// sales order
				if($header->ReturType == 3 && $v->product_type == 2):
					$data_sn = $this->return->serial_number($id,$v->ReturDet,$v->ProductID);
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

						// update ke table PS_Product_Serial
						$data_serial = array(
							"Status"		=> 0,
							"User_Ch"		=> $this->session->NAMA,
							"Date_Ch"		=> date("Y-m-d H:i:s"),
						);

						$this->db->where("BranchID", $BranchID);
						$this->db->where("CompanyID", $CompanyID);
						$this->db->where("ProductID", $v->ProductID);
						$this->db->where("SerialNo", $v2->SN);
						$this->db->update("PS_Product_Serial", $data_serial);
					}
					
				// delivery
				elseif($header->ReturType == 4 && $v->product_type == 2):
					$data_sn = $this->return->serial_number($id,$v->ReturDet,$v->ProductID);
					foreach ($data_sn as $k2 => $v2) {
						// update ke table PS_Delivery_Det_SN
						$data_serial = array(
							"Status"		=> 1,
							"User_Ch"		=> $this->session->NAMA,
							"Date_Ch"		=> date("Y-m-d H:i:s"),
						);
						$this->db->where("CompanyID", $CompanyID);
						$this->db->where("ProductID", $v->ProductID);
						$this->db->where("DeliveryNo", $v->DeliveryNo);
						$this->db->where("DeliveryDet", $v->DeliveryDet);
						$this->db->where("SN", $v2->SN);
						$this->db->update("PS_Delivery_Det_SN", $data_serial);

						// update ke table PS_Product_Serial
						$data_serial = array(
							"Status"		=> 0,
							"User_Ch"		=> $this->session->NAMA,
							"Date_Ch"		=> date("Y-m-d H:i:s"),
						);
						$this->db->where("BranchID", $BranchID);
						$this->db->where("CompanyID", $CompanyID);
						$this->db->where("ProductID", $v->ProductID);
						$this->db->where("SerialNo", $v2->SN);
						$this->db->update("PS_Product_Serial", $data_serial);
					}
				endif;
			}
			$data = array("Status" => 0);
			$this->return->update(array("ReturNo"=>$id, "CompanyID" => $CompanyID,),$data);

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

	public function ajax_edit($id){
		$id 		= str_replace("-", "/", $id);
		$list 		= $this->return->get_by_id($id);
		$detail 	= $this->return->get_by_detail($id, $list->ReturType);
		$data = array(
			"hakakses"	=> $this->session->hak_akses,
			"app"		=> $this->session->app,
			"list"		=> $list,
			"detail"	=> $detail,
 		);

 		$this->main->echoJson($data);
	}

	private function calculationQty($id,$page=""){
		$header = $this->return->get_by_id($id);
		$detail = $this->return->get_by_detail($id,$header->ReturType);	
		foreach ($detail as $k => $v) {
			$module_data = $this->main->check_module_stock($v->Module);
			if($module_data->stock>0):
				$this->main->retur_qty("done",$v->ProductID, $v->Qty);
			endif;
		}
	}

	public function save_remark(){
		$CompanyID 	= $this->session->CompanyID;
		$ID 		= $this->input->post("ID");
		$Remark 	= $this->input->post("Remark");

		$status 	= false;
		$message 	= $this->lang->line('lb_data_not_found');

		if($CompanyID && $ID):
			$cek = $this->db->count_all("AP_Retur where CompanyID = '$CompanyID' and ReturNo = '$ID'");
			if($cek>0):
				$data = array(
					"Remark"	=> $Remark,
				);
				$this->db->where("CompanyID", $CompanyID);
				$this->db->where("ReturNo", $ID);
				$this->db->update("AP_Retur", $data);

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

		$list = $this->return->serial_number_list($mt,$dt);

		$output = array(
			"status"	=> true,
			"list"		=> $list,
		);

		$this->main->echoJson($output);
	}
}