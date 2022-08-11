<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Koreksi_stok extends CI_Controller {
	var $title = 'Stock Correction';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_koreksi_stok",'koreksi');
		$this->main->cek_session();
	}
	public function index()
	{
		$url 						= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url);
		$read 						= $this->main->read($id_url);
		$inventory 					= $this->main->check_parameter_module("inventory", "stock");
		if($read == 0 or $inventory->view == 0){ redirect(); }
		$koreksi_tambah 				= $this->main->menu_tambah($id_url);
		if($koreksi_tambah > 0 and $inventory->add >0):
            $tambah = $this->main->general_button('add',$this->lang->line('lb_add_new').' '.$this->title);
		else: 
			$tambah = ""; 
		endif;
		$this->main->delete_temp_sn("stock_correction");
		#ini untuk session halaman aturan user privileges;
		$data['url'] 			= 'list-koreksi';
		$data['title']  		= $this->title;
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'koreksi/modal';
		$data['page'] 			= 'koreksi/list';
		$data['modul'] 			= "stock_correction";
		$data['url_modul'] 		= $url;
		$this->load->view('index',$data);
	}
	//---------------------------------------------------------------------------------------
	public function ajax_list()
	{
		$page 	= "koreksi";
		$url 	= $this->uri->segment(1); 
		$id_url = $this->main->id_menu($url);
		$list 	= $this->koreksi->get_datatables($page);
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		$inventory = $this->main->check_parameter_module("inventory", "stock");
		foreach ($list as $a) {
			$ubah 	= "";
			$hapus 	= "";
			$koreksi_ubah 	= $this->main->menu_ubah($id_url);
			$koreksi_hapus 	= $this->main->menu_hapus($id_url);
			if($koreksi_ubah > 0 and $inventory->add >0):
           	$ubah = '<a href="javascript:void(0)" type="button" class="btn btn-success" title="Edit" onclick="view('."'".$a->correctionno."'".')"><i class="icon fa-search" aria-hidden="true"></i></a>';
			endif;
			if($koreksi_hapus > 0):
           	$hapus = '<a href="javascript:void(0)" type="button" class="btn btn-danger" title="Hapus" onclick="hapus('."'".$a->correctionno."'".')"><i class="icon fa-remove" aria-hidden="true"></i></a>';
			endif;
			$print 	= '<a href="javascript:void(0)" type="button" class="btn btn-info" title="Hapus" onclick="print('."'".$a->correctionno."'".')"><i class="icon fa-print" aria-hidden="true"></i></a>'; 

			$button  = '<div class="btn-group btn-group-xs" aria-label="Basic example" role="group">';
            // $button .= $print;
            $button .= $ubah;
            // $button .= $hapus;
            $button .= '</div>';

            $code = '<a href="javascript:;" type="button" title="View" onclick="view('."'".$a->correctionno."'".')">'.$a->correctionno.'</a>';
            if($a->BranchID):
	        	$branch 		= $this->main->button_action("general_onclick","redirect_post('store-device-management','".$a->BranchID."')",$a->branchName);
	        else:
	        	$branch 		= "";
	        endif;

			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $code;
			$row[] 	= $branch;
			$row[] 	= $a->date;

			$row[] 	= $button;		
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->koreksi->count_all($page),
			"recordsFiltered" => $this->koreksi->count_filtered($page),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$a = $this->koreksi->get_by_id($id,"koreksi");
		$detail = $this->koreksi->get_list_detail($id);

		foreach ($detail as $k => $v) {
			$v->qty_txt 	= $this->main->qty($v->qty);
			$v->realqty_txt = $this->main->qty($v->realqty);
		}

		$data = array(
			"correctionno" 	=> $a->correctionno,
			"date" 			=> $a->date,
			"branchName"	=> $a->branchName,
			"hakakses" 		=> $this->session->hak_akses,
			"list_detail" 	=> $detail,
		);
		header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
	}

	public function simpan($page = "")
	{
		$this->_validate("save");
		$correctionno 	= $this->main->correction_code_generate();
		$date 			= $this->input->post('date');
		$BranchID 		= $this->input->post('BranchID');
		$BranchID 		= explode("-", $BranchID); $BranchID = $BranchID[0];
		$CompanyID 		= $this->session->CompanyID;

		#ini detail
		$rowid 			= $this->input->post('rowid');
		$productid 		= $this->input->post('productid');
		$product_qty 	= $this->input->post('product_qty');
		$product_type 	= $this->input->post('product_type');

		// serial post
		$dt_serial 	  	= $this->input->post("dt_serial");
		$dt_serialkey 	= $this->input->post("dt_serialkey");
		$dt_serialauto 	= $this->input->post("dt_serialauto");
		$dt_qty 		= $this->input->post("dt_qty");
		$dt_serial    	= json_decode($dt_serial);
		$dt_serialkey 	= json_decode($dt_serialkey);
		$dt_serialauto 	= json_decode($dt_serialauto);
		$dt_qty 		= json_decode($dt_qty);
		
		#ini data 
		$data = array(
			'CorrectionNo' 	=> $correctionno,
			'CompanyID'		=> $this->session->CompanyID,
			'Date' 			=> $date,
		);
		if($BranchID):
			$data['BranchID'] = $BranchID;
		endif;
		$insert = $this->koreksi->save($data);

		#ini insert detail
		$data 				= array();
		foreach ($productid as $key => $v) {
			$qty_length = strlen($product_qty[$key]);
			
			if($qty_length > 0 && $productid[$key]):
				$xqty 		= $this->main->checkDuitInput($product_qty[$key]);
				$xdt_qty 	= $this->main->checkDuitInput($dt_qty[$key]);
				$correctiondet = $this->main->correctiondet_code_generate();
				$data = array(
					'CorrectionDet'	=> $correctiondet,
					'CorrectionNo' 	=> $correctionno,
					'CompanyID'		=> $this->session->CompanyID,
					'ProductID' 	=> $productid[$key],
					'Qty' 			=> $xdt_qty,
					'CorrectionQty' => $xqty,
					'User_Add' 		=> $this->session->nama,
					'Date_Add' 		=> date("Y-m-d H:i:s")
				);
				$this->db->insert("PS_Correction_Detail",$data);
				$ID = $this->db->insert_id();

				if($BranchID):
					$this->main->branch_stock($BranchID,$productid[$key],$xqty);
				endif;
				// simpan serial number
				$xtype = $product_type[$key];
				if($xtype == 2):
					$arrkey = array_keys($dt_serialkey,$rowid[$key]);
					$arrTempSN = array();
					foreach ($arrkey as $key2 => $value_key) {
						$no = $key2 + 1;
						if($xqty>=$no):
							$sn = $dt_serial[$value_key];
							
							// insert ke table PS_Correction_Detail_SN
							$data_serial = array(
								"CompanyID"			=> $CompanyID,
								"Status"			=> 1,
								"Qty"				=> 1,
								"ProductID"			=> $productid[$key],
								"CorrectionNo"		=> $correctionno,
								"CorrectionDetID" 	=> $ID,
								"User_Add"			=> $this->session->NAMA,
								"Date_Add"			=> date("Y-m-d H:i:s"),
								"SN"				=> $sn,
							);
							$this->db->insert("PS_Correction_Detail_SN", $data_serial);
							array_push($arrTempSN, $sn);
						endif;
					}
					if(count($arrTempSN)>0):
						$sn_data = $this->main->product_serial("array",$arrTempSN,$productid[$key],"",$BranchID);
						foreach ($arrTempSN as $k2 => $v2) {
							$sn = array_search($v2, array_column($sn_data, "serialno"));
                			$sn_length = strlen($sn);
                			// insert data sn yang baru
                			if($sn_length<=0):
                				$data_serial = array(
                					"ProductID"	=> $productid[$key],
                					"CompanyID"	=> $CompanyID,
                					"SerialNo"	=> $v2,
                					"Date"		=> date("Y-m-d"),
                					"Qty"		=> 1,
                					"Status"	=> 1,
                					"User_Add"	=> $this->session->NAMA,
									"Date_Add"	=> date("Y-m-d H:i:s"),
                				);
                				$data_serial['BranchID'] = $BranchID;
                				$this->db->insert("PS_Product_Serial", $data_serial);
                			endif;
						}
						// update status active
						$data_serial = array(
							"Status" 	=> 1,
							"User_Ch"	=> $this->session->NAMA,
							"Date_Ch"	=> date("Y-m-d H:i:s"),
						);
        				$this->db->where("BranchID", $BranchID);
						$this->db->where("CompanyID", $CompanyID);
						$this->db->where("ProductID", $productid[$key]);
						$this->db->where_in("SerialNo", $arrTempSN);
						$this->db->update("PS_Product_Serial", $data_serial);

						// update status non active
						$data_serial = array(
							"Status" 	=> 0,
							"User_Ch"	=> $this->session->NAMA,
							"Date_Ch"	=> date("Y-m-d H:i:s"),
						);
        				$this->db->where("BranchID", $BranchID);
						$this->db->where("CompanyID", $CompanyID);
						$this->db->where("ProductID", $productid[$key]);
						$this->db->where_not_in("SerialNo", $arrTempSN);
						$this->db->update("PS_Product_Serial", $data_serial);
					elseif($xqty == 0):
						$data_serial = array(
							"Status" 	=> 0,
							"User_Ch"	=> $this->session->NAMA,
							"Date_Ch"	=> date("Y-m-d H:i:s"),
						);
        				$this->db->where("BranchID", $BranchID);
						$this->db->where("CompanyID", $CompanyID);
						$this->db->where("ProductID", $productid[$key]);
						$this->db->update("PS_Product_Serial", $data_serial);
					endif;
				endif;

			endif;
		}
		$this->main->delete_temp_sn("stock_correction");

		// generate journal
		$this->db->query("CALL run_generate_jurnal('UPDATE', '$correctionno', 'correction_stock', '$CompanyID')");

		echo json_encode(array("status" => TRUE,"pesan" => "yoi", "code" => $correctionno));
	}
	public function correctionstock($productid,$qty)
	{
		$data = array(
			"Qty" 		=> $qty,
			"User_Ch" 	=> $this->session->nama,
			"Date_Ch" 	=> date("Y-m-d H:i:s")
		);
		$this->db->where("ProductID",$productid);
		$this->db->update("ps_product",$data);
	}

	public function ajax_delete($id)
	{
		$this->koreksi->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}
	private function _validate($page = "")
	{
		$this->main->validate_modlue_add("inventory","inventory");
		$CompanyID 	= $this->session->CompanyID;
		$data 					= array();
		$data['error_string'] 	= array();
		$data['inputerror'] 	= array();
		$data['status'] 		= TRUE;
		$data['message']		= '';

		// $data['inputerror'][] 	= "";
		// $data['error_string'][] = '';
		// $data['list'][] 		= '';
		// $data['ara'] 			= $this->input->post();
		// $data['status'] 		= FALSE;

		// $this->main->echoJson($data);
		// exit();

		if(!$CompanyID):
			$data['inputerror'][] 	= "";
			$data['error_string'][] = '';
			$data['list'][] 		= '';
			$data['status'] 		= FALSE;

			$this->main->echoJson($data);
			exit();
		endif;

		$BranchID 		= $this->input->post('BranchID');
		$BranchID 		= explode("-", $BranchID); $BranchID = $BranchID[0];
		$rowid 			= $this->input->post('rowid');
		$productid 		= $this->input->post('productid');
		$product_qty 	= $this->input->post('product_qty');
		$product_type 	= $this->input->post('product_type');

		// serial post
		$dt_serial 	  = $this->input->post("dt_serial");
		$dt_serialkey = $this->input->post("dt_serialkey");
		$dt_serialauto = $this->input->post("dt_serialauto");
		$dt_serial    = json_decode($dt_serial);
		$dt_serialkey = json_decode($dt_serialkey);
		$dt_serialauto = json_decode($dt_serialauto);

		$status_product = false;
		$status_qty 	= true;
		$lb_message 	= $this->lang->line('lb_select_product_empty');
		foreach ($productid as $k => $v) {
			if($productid[$k] != ''):
				$status_product = true;
			endif;

			if($productid[$k]):
				$qty_len = strlen($product_qty[$k]);
				if($qty_len<=0):
					$data['inputerror'][] 	= ".".$rowid[$k];
					$data['error_string'][] = $this->lang->line('lb_product_qty_empty');
					$data['list'][] 		= 'list';
					$status_qty 	= false;
					$lb_message 	= $this->lang->line('lb_product_qty_empty');
				else:
					$xtype 	= $product_type[$k];
					$xqty 	= $this->main->checkDuitInput($product_qty[$k]);
					$temp_data  = $this->api->temp_serial("stock_correction","",$rowid[$k],$productid[$k],"class");
					if($xtype == 2):
						$arrkey = array_keys($dt_serialkey,$rowid[$k]);
						if($xqty>count($arrkey)):
							$data['inputerror'][] 	= ".".$rowid[$k];
							$data['error_string'][] = $this->lang->line('lb_serial_empty');
							$data['list'][] 		= 'list';
							$status_qty 			= false;
							$lb_message 			= $this->lang->line('lb_serial_empty');
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
								$data['inputerror'][] 	= ".".$rowid[$k];
								$data['error_string'][] = $message_sn;
								$data['list'][] 		= 'list';
								$lb_message 			= $message_sn;
								$status_qty 			= false;
							endif;
						endif;
					endif;
				endif;
			endif;
		}

		if(!$status_product || !$status_qty):
			$data['inputerror'][] 	= "";
			$data['error_string'][] = '';
			$data['list'][] 		= '';
			$data['status'] 	= FALSE;
			$data['message'] 	= $lb_message;
			echo json_encode($data);
			exit();
		endif;

		$ck_Branch = $this->db->count_all("Branch where CompanyID = '$CompanyID' and BranchID = '$BranchID' and Active = '1'");
		if($ck_Branch<=0):
			$data['inputerror'][] 	= "BranchID";
			$data['error_string'][] = '';
			$data['list'][] 		= '';
			$data['status'] 		= FALSE;
			$data['message'] 		= $this->lang->line('lb_store_not_found');
		endif;

		// if($page == "save" && $this->input->post('koreksi_code') == '')
		// {
		// 	$data['inputerror'][] 	= 'koreksi_code';
		// 	$data['error_string'][] = 'koreksi code cannot be null';
		// 	$data['status'] 		= FALSE;
		// }
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	
	public function get_detail($id){
		$d = $this->koreksi->get_list_detail($id,"detail");

		$data = array(
			"list"		=> $d,
			"serial" 	=> $this->main->product_serial("add_serial_mutasi",$d->productid),
			"hak_akses"	=> $this->session->hak_akses,
		);

		$this->main->echoJson($data);
	}

	public function save_serial(){
		$this->validate_serial();

		$CompanyID 		= $this->session->CompanyID;
		$productid 		= $this->input->post("productid");
		$page 			= $this->input->post("page");
		$product_type 	= $this->input->post("product_type");
		$serial_qty 	= $this->input->post("serial_qty");
		$check 			= $this->input->post("check");
		$serial_id 		= $this->input->post("serial_id");
		$sn 			= $this->input->post('sn');

		$productserialid = array();
		foreach ($serial_id as $key => $v) {
			if(in_array($serial_id[$key], $check)):
				$from = "old";
				if(strpos($serial_id[$key], 'alias') !== false):
					$from = "new";
				endif;
				$data = array(
					"ProductID" => $productid,
					"CompanyID"	=> $CompanyID,
					"SerialNo"	=> $sn[$key],
					"Date"		=> date("Y-m-d"),
				);
				if($product_type == "general"):
					$qty = $serial_qty;
				elseif($product_type == "serial"):
					$qty = 1;
				endif;
				$data['Qty'] = $qty;
				if($from == "new"):
					$this->db->set("user_add",$this->session->userdata("nama"));
					$this->db->set("date_add",date("Y-m-d H:i:s"));
					$this->db->insert("PS_Product_Serial", $data);
					$id = $this->db->insert_id();
				else:
					$id = $serial_id[$key];
					$this->db->set("User_Ch",$this->session->userdata("nama"));
					$this->db->set("Date_Ch",date("Y-m-d H:i:s"));
					$this->db->where("CompanyID", $CompanyID);
					$this->db->where("ProductSerialID", $id);
					$this->db->update("PS_Product_Serial", $data);
				endif;
				array_push($productserialid, $id);
			endif;
		}


		$this->db->where("CompanyID", $CompanyID);
		$this->db->where("ProductID", $productid);
		$this->db->where_not_in("ProductSerialID", $productserialid);
		$this->db->update("PS_Product_Serial", array("Status", 0));

		$output = array(
			"status"	=> true,
			"message"	=> "Success",
			"page"		=> $page,
		);

		$this->main->echoJson($output);
	}

	private function validate_serial(){
		$data 					= array();
		$data['error_string'] 	= array();
		$data['inputerror'] 	= array();
		$data['status'] 		= TRUE;
		
		$CompanyID 		= $this->session->CompanyID;
		$productid 		= $this->input->post("productid");
		$product_type 	= $this->input->post("product_type");
		$serial_qty 	= $this->input->post("serial_qty");
		$check 			= $this->input->post("check");
		$serial_id 		= $this->input->post("serial_id");
		$sn 			= $this->input->post('sn');

		if(count($check)>0 and $product_type == "serial" and count($check) != $serial_qty):
			$data['status'] 	= FALSE;
			$data['message'] 	= 'Please check total serial number';
		elseif(count($check)>0 and $product_type == "general" and count($check) != 1):
			$data['status'] 	= FALSE;
			$data['message'] 	= 'Please check total serial number';
		elseif(count($check)>0):
			foreach ($serial_id as $k => $v) {
				if(in_array($serial_id[$k], $check)):
					$serial_number 	= $sn[$k];
					$idnya 			= $serial_id[$k];
					$from = "old";
					if(strpos($serial_id[$key], 'alias') !== false):
						$from = "new";
					endif;
					if($serial_number):
						if($from == "old"):
							$cek = $this->db->count_all("PS_Product_Serial where CompanyID = '$CompanyID' and ProductID = '$productid' and SerialNo = '$serial_number' and ProductSerialID != '$idnya'");
						else:
							$cek = $this->db->count_all("PS_Product_Serial where CompanyID = '$CompanyID' and ProductID = '$productid' and SerialNo = '$serial_number' and Status = '1'");
						endif;
						if($cek>0):
							$data['status'] 	= FALSE;
							$data['message'] 	= 'Serial number is duplicate';
						endif;
					else:
						$data['status'] 	= FALSE;
						$data['message'] 	= 'Please check serial number';
					endif;
				endif;
			}
		else:
			$data['status'] 	= FALSE;
			$data['message'] 	= 'Please select serial number';	
		endif;

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	public function serial_number_list(){
		$mt = $this->input->post("mt");
		$dt = $this->input->post("dt");

		$list = $this->koreksi->serial_number_list($mt,$dt);

		$output = array(
			"status"	=> true,
			"list"		=> $list,
		);

		$this->main->echoJson($output);
	}
}
