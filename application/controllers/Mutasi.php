<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mutasi extends CI_Controller {
	var $title = 'Stock Mutation';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_mutasi",'mutasi');
		$this->main->cek_session();
		$this->title = $this->lang->line('lb_stock_mutation');
	}
	public function index()
	{
		$CompanyID 					= $this->session->CompanyID;
		$url 						= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url);
		$read 						= $this->main->read($id_url);
		$inventory 	= $this->main->check_parameter_module("inventory", "mutation");
		if($read == 0 || $inventory->view == 0){ redirect(); }
		$mutasi_tambah 				= $this->main->menu_tambah($id_url);
		if($mutasi_tambah > 0 and $inventory->add >0):
            $tambah = $this->main->general_button('add',$this->lang->line('lb_add_new').' '.$this->title);
		else: 
			$tambah = ""; 
		endif;
		$this->main->delete_temp_sn("mutation");

		$ck_count = $this->db->count_all("Branch where CompanyID = '$CompanyID' and Active = '1'");
		if($ck_count<=1):
            $this->session->set_flashdata('message', $this->lang->line('lb_store_more_than_one'));
            redirect(site_url('store-device-management'));
			exit();
		endif;

		#ini untuk session halaman aturan user privileges;
		$data['url'] 			= 'list-mutasi';
		$data['title']  		= $this->title;
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'mutasi/modal';
		$data['page'] 			= 'mutasi/list';
		$data['modul'] 			= "mutation";
		$data['url_modul'] 		= $url;
		$this->load->view('index',$data);
	}
	//---------------------------------------------------------------------------------------
	public function ajax_list()
	{
		$page 	= "mutasi";
		$url 	= $this->uri->segment(1); 
		$id_url = $this->main->id_menu($url);
		$list 	= $this->mutasi->get_datatables($page);
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		$inventory 	= $this->main->check_parameter_module("inventory", "mutation");
		foreach ($list as $a) {
			$ubab 	= "";
			$hapus 	= "";
			$mutasi_ubah 	= $this->main->menu_ubah($id_url);
			$mutasi_hapus 	= $this->main->menu_hapus($id_url);
			if($mutasi_ubah > 0 and $inventory->add >0):
           	$ubah = '<a href="javascript:void(0)" type="button" class="btn btn-success" title="Edit" onclick="view('."'".$a->mutation_no."'".')"><i class="icon fa-search" aria-hidden="true"></i></a>';
			endif;
			if($mutasi_hapus > 0 and $inventory->add >0):
           	$hapus = '<a href="javascript:void(0)" type="button" class="btn btn-danger" title="Hapus" onclick="hapus('."'".$a->mutation_no."'".')"><i class="icon fa-remove" aria-hidden="true"></i></a>';
			endif;
			$print 	= '<a href="javascript:void(0)" type="button" class="btn btn-info" title="Hapus" onclick="print('."'".$a->mutation_no."'".')"><i class="icon fa-print" aria-hidden="true"></i></a>'; 


			if($a->status == 1): $status = "<hijau>done</hijau>"; else: $status= "<merah>cancel</merah>"; $hapus=""; endif;

			$button  = '<div class="btn-group btn-group-xs" aria-label="Basic example" role="group">';
            // $button .= $print;
            $button .= $ubah;
            // $button .= $hapus;
            $button .= '</div>';

            $code = '<a href="javascript:;" type="button" title="View" onclick="view('."'".$a->mutation_no."'".')">'.$a->mutation_no.'</a>';

            if($a->BranchID):
            	$from_name = $this->main->button_action("general_onclick","redirect_post('store-device-management','".$a->BranchID."')",$a->from_name);
            else:
            	$from_name = '';
            endif;

            if($a->BranchIDTo):
            	$to_name = $this->main->button_action("general_onclick","redirect_post('store-device-management','".$a->BranchIDTo."')",$a->to_name);
            else:
            	$to_name = '';
            endif;

			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $code;
			$row[] 	= $a->mutation_date;
			$row[] 	= $from_name;
			$row[] 	= $to_name;
			$row[] 	= $a->type;
			// $row[] 	= $status;

			// $row[] 	= $button;		
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->mutasi->count_all($page),
			"recordsFiltered" => $this->mutasi->count_filtered($page),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$a = $this->mutasi->get_by_id($id,"mutasi");
		$detail = $this->mutasi->get_list_detail($id);

		foreach ($detail as $k => $v) {
			$v->mutation_qty_txt 	= $this->main->qty($v->mutation_qty);
			$v->mutation_price_txt 	= $this->main->currency($v->mutation_price);
		}

		$output = array(
			"mutation_no" 		=> $a->mutation_no,
			"mutation_date" 	=> $a->mutation_date,
			"mutation_remark" 	=> $a->mutation_remark,
			"mutation_type" 	=> $a->mutation_type,
			"from_name" 		=> $a->from_name,
			"to_name" 			=> $a->to_name,
			"hakakses"			=> $this->session->hak_akses,
			"list_detail" 		=> $detail,
		);
		header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
	}
	public function ajax_edit_serial($id)
	{
		$a =  $this->mutasi->get_list_detail($id,"add_serial");
		$sn = json_decode($a->serialnumber);
		if(empty($sn)):
			$serial_number = array();
		else:
			$serial_number = array();
			foreach($sn as $sn):
				$item = array(
					"productserialid" 	=> $sn->ProductSerialID,//$sn["ProductSerialID"],
					"productid" 		=> $sn->ProductID,
					"mutationno" 		=> $id,
					"mutationdet" 		=> $sn->MutationDet,
					"serialnumber" 		=> $sn->SerialNumber,
					"hakakses"			=> $this->session->hakakses
				);	
				array_push($serial_number, $item);
			endforeach;

		endif;
		$data = array(
			"branchid" 			=> $a->branchid,
			"product_code" 		=> $a->product_code,
			"product_name" 		=> $a->product_name,
			"product_type" 		=> $a->product_type,
			"productid" 		=> $a->productid,
			"detail_code" 		=> $a->mutation_det,
			"mutation_det" 		=> $a->mutation_det,
			"mutation_konv" 	=> $a->mutation_konv,
			"mutation_no" 		=> $a->mutation_no,
			"mutation_price" 	=> $a->mutation_price,
			"serial_qty" 		=> $a->mutation_qty,
			"unit_name" 		=> $a->unit_name,
			"unitid" 			=> $a->unitid,
			"page"				=> "add_serial_mutasi",
			"list_serial" 		=> $serial_number,
			// "list_serial" 	=> $this->main->product_serial("add_serial_mutasi",$a->productid)
		);
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
	}
	public function simpan_serial($page = "")
	{
		$this->_validate_serial();
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
				"Page" 				=> "mutasi",
				'MutationDet'		=> $detail_code,
				'CompanyID' 		=> $this->session->CompanyID,
				'ProductID' 		=> $productid,
				'ProductSerialID' 	=> $productserialid[$key],
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
		$this->db->where("MutationDet",$detail_code);
		$this->db->update("PS_Mutation_Detail",$data);
		header('Content-Type: application/json');
        echo json_encode(array("page"=>"add_serial","status" => "add_serial","pesan" => $data),JSON_PRETTY_PRINT);  
	}
	public function simpan($page = "")
	{
		if($page != "tes"):
		$this->_validate("save");
		$this->_validate_product();
		endif;
		$CompanyID 		= $this->session->CompanyID;
		$mutasi_code 	= $this->main->mutasi_code_generate();
		$from_name 		= $this->input->post("from_name");
		$to_name 		= $this->input->post("to_name");

		$from_name 		= explode("-", $from_name)[0];
		$to_name 		= explode("-", $to_name)[0];

		$type 			= $this->input->post("mutation_type");
		$type 		 	= 1; // mutasi hanya store to store
		$remark 		= $this->input->post("mutation_remark");
		$date 			= $this->input->post('date');
		#1 jika dari branch to branch 0 jika dari HO/perusahaan ke branch
		
		$rowid 		 = $this->input->post('rowid');
		$productid       	= $this->input->post('productid');
		$product_qty       	= $this->input->post('product_qty');
		$product_remark 	= $this->input->post('product_remark');
		$product_type= $this->input->post('product_type');

		// serial post
		$dt_serial 	  = $this->input->post("dt_serial");
		$dt_serialkey = $this->input->post("dt_serialkey");
		$dt_p_unitid  = $this->input->post("dt_p_unitid");
		$dt_price  	  = $this->input->post("dt_price");
		$dt_serial    = json_decode($dt_serial);
		$dt_serialkey = json_decode($dt_serialkey);
		$dt_p_unitid  = json_decode($dt_p_unitid);
		$dt_price 	  = json_decode($dt_price);


		if($type == 0):
			$from_name = $this->session->CompanyID; 
		elseif($type == 2):
			$to_name = $this->session->CompanyID;
		endif;
		$data = array(
			'MutationNo' 	=> $mutasi_code,
			'CompanyID'		=> $this->session->CompanyID,
			'BranchID'		=> $from_name,
			'BranchIDTo'	=> $to_name,
			'Date' 			=> $date,
			'Remark' 		=> $remark,
			'Status' 		=> 1,
			'Type' 			=> $type,
		);
		$insert 			= $this->mutasi->save($data);
		
		$data 				= array();
		foreach ($productid as $idx => $v) {
			if($v):
				$xqty 	= $this->main->checkDuitInput($product_qty[$idx]);
				$xprice = $this->main->checkDuitInput($dt_price[$idx]);
				$mutationdet = $this->main->mutasidetail_code_generate();
				$data = array(
					'MutationDet' 	=> $mutationdet,
					'MutationNo' 	=> $mutasi_code,
					'CompanyID'		=> $this->session->CompanyID,
					'ProductID' 	=> $productid[$idx],
					'Conversion'	=> 1,
					'Qty' 			=> $xqty,
					'Uom' 			=> $dt_p_unitid[$idx],
					'Price'			=> $xprice,
					'Remark'		=> $product_remark[$idx],
					'User_Add' 		=> $this->session->nama,
					'Date_Add' 		=> date("Y-m-d H:i:s")
				);

				// simpan serial number
				$xtype = $product_type[$idx];
				$arrTempSN2 = array();
				if($xtype == 2):
					$arrkey = array_keys($dt_serialkey,$rowid[$idx]);
					$arrTempSN = array();
					foreach ($arrkey as $key2 => $value_key) {
						$no = $key2 + 1;
						if($xqty>=$no):
							$sn = $dt_serial[$value_key];
							
							// insert ke table PS_Mutation_Detail_SN
							$data_serial = array(
								"CompanyID"			=> $CompanyID,
								"Status"			=> 1,
								"Qty"				=> 1,
								"ProductID"			=> $productid[$idx],
								"MutationNo"		=> $mutasi_code,
								"MutationDet" 		=> $mutationdet,
								"User_Add"			=> $this->session->NAMA,
								"Date_Add"			=> date("Y-m-d H:i:s"),
								"SN"				=> $sn,
							);
							$this->db->insert("PS_Mutation_Detail_SN", $data_serial);
							array_push($arrTempSN, $sn);

							$item = array(
								"Page" 				=> "mutasi",
								'MutationDet'		=> $mutationdet,
								'CompanyID' 		=> $this->session->CompanyID,
								'ProductID' 		=> $productid[$idx],
								'ProductSerialID' 	=> null,
								'SerialNumber' 		=> $sn,
								'status'			=> 1,
							);
							array_push($arrTempSN2, $item);
						endif;
					}
					if(count($arrTempSN)>0):
						
						// mutasi ke branch
						if($type != 2):
							$sn_data = $this->main->product_serial("array",$arrTempSN,$productid[$idx],"",$to_name);
						else:
							$sn_data = $this->main->product_serial("array",$arrTempSN,$productid[$idx],"","");
						endif;

						foreach ($arrTempSN as $k2 => $v2) {
							$sn = array_search($v2, array_column($sn_data, "serialno"));
                			$sn_length = strlen($sn);
                			// insert data sn yang baru
                			if($sn_length<=0):
                				$data_serial = array(
                					"ProductID"	=> $productid[$idx],
                					"CompanyID"	=> $CompanyID,
                					"SerialNo"	=> $v2,
                					"Date"		=> date("Y-m-d"),
                					"Qty"		=> 1,
                					"Status"	=> 1,
                					"User_Add"	=> $this->session->NAMA,
									"Date_Add"	=> date("Y-m-d H:i:s"),
                				);
                				if($type != 2):
                					$data_serial['BranchID'] = $to_name;
                				endif;
                				$this->db->insert("PS_Product_Serial", $data_serial);
                			endif;
						}

						if($type == 0):
							$this->update_product_serial_number($arrTempSN,$productid[$idx],0,"");
							$this->update_product_serial_number($arrTempSN,$productid[$idx],1,$to_name);
						elseif($type == 1):
							$this->update_product_serial_number($arrTempSN,$productid[$idx],0,$from_name);
							$this->update_product_serial_number($arrTempSN,$productid[$idx],1,$to_name);
						elseif($type == 2):
							$this->update_product_serial_number($arrTempSN,$productid[$idx],0,$to_name);
							$this->update_product_serial_number($arrTempSN,$productid[$idx],1,"");
						endif;
					endif;
				endif;
				$data['SerialNumber'] = json_encode($arrTempSN2);
				$this->db->insert("PS_Mutation_Detail",$data);
				if($xqty > 0):
					$this->main->mutasi_qty($page ="from",$type,$from_name,$productid[$idx],$xqty);
					$this->main->mutasi_qty($page ="to",$type,$to_name,$productid[$idx],$xqty);
				endif;

			endif;
		}
		$this->main->delete_temp_sn("mutation");
		header('Content-Type: application/json');
        echo json_encode(array("status" => TRUE,"pesan" => $data),JSON_PRETTY_PRINT);  
	}

	private function update_product_serial_number($arrTempSN,$ProductID,$Status,$BranchID=""){
		$CompanyID = $this->session->CompanyID;
		// update status 
		$data_serial = array(
			"Status" 	=> $Status,
			"User_Ch"	=> $this->session->NAMA,
			"Date_Ch"	=> date("Y-m-d H:i:s"),
		);
		$this->db->where("CompanyID", $CompanyID);
		$this->db->where("ProductID", $ProductID);
		if($BranchID):
			$this->db->where("BranchID", $BranchID);
		else:
			$this->db->where("BranchID", null);
		endif;
		$this->db->where_in("SerialNo", $arrTempSN);
		$this->db->update("PS_Product_Serial", $data_serial);
	}

	public function ajax_delete($id)
	{
		$this->db->set("Status",0);
		$this->db->where("MutationNo",$id);
		$this->db->update("PS_Mutation");
		echo json_encode(array("status" => TRUE));
	}
	private function _validate($page = "")
	{

		$CompanyID 		= $this->session->CompanyID;
		$from_name 		= $this->input->post("from_name");
		$to_name 		= $this->input->post("to_name");
		$from_name 		= explode("-", $from_name)[0];
		$to_name 		= explode("-", $to_name)[0];

		$data 					= array();
		$data['error_string'] 	= array();
		$data['inputerror'] 	= array();
		$data['status'] 		= TRUE;
		if($this->input->post("mutation_type") == 0 && $this->input->post('to_name') == '' || $this->input->post("mutation_type") == 0 && $to_name == "" 
		|| $this->input->post("mutation_type") == 1 && $this->input->post('to_name') == '' || $this->input->post("mutation_type") == 1 && $to_name == ""  )
		{
			$data['inputerror'][] 	= 'to_name';
			$data['error_string'][] = $this->lang->line('lb_to_empty');
			$data['list'][] 		= '';
			$data['status'] 		= FALSE;
		}
		if($this->input->post("mutation_type") == 1 && $this->input->post('from_name') == '' || $this->input->post("mutation_type") == 1 && $from_name == ""
		|| $this->input->post("mutation_type") == 2 && $this->input->post('from_name') == '' || $this->input->post("mutation_type") == 2 && $from_name == "" )
		{
			$data['inputerror'][] 	= 'from_name';
			$data['error_string'][] = $this->lang->line('lb_from_empty');
			$data['list'][] 		= '';
			$data['status'] 		= FALSE;
		}

		$mutation_type = $this->input->post("mutation_type");
		if($mutation_type == 1 and $from_name == $to_name):
			$data['inputerror'][] 	= 'from_name';
			$data['error_string'][] = $this->lang->line('lb_store_same');
			$data['list'][] 		= '';
			$data['inputerror'][] 	= 'to_name';
			$data['error_string'][] = $this->lang->line('lb_store_same');
			$data['list'][] 		= '';
			$data['status'] 		= FALSE;
			$data['message'] 		= $this->lang->line('lb_store_same');
			echo json_encode($data);
			exit();
		endif;

		$ck_branch1 = $this->db->count_all("Branch where CompanyID = '$CompanyID' and BranchID = '$from_name' and Active = '1'");
		$ck_branch2 = $this->db->count_all("Branch where CompanyID = '$CompanyID' and BranchID = '$to_name' and Active = '1'");
		if($ck_branch1<=0):
			$data['inputerror'][] 	= 'from_name';
			$data['error_string'][] = $this->lang->line('lb_data_not_found');
			$data['list'][] 		= '';
			$data['status'] 		= FALSE;
			$data['message'] = $this->lang->line('lb_data_not_found');
			echo json_encode($data);
			exit();
		endif;
		if($ck_branch2<=0):
			$data['inputerror'][] 	= 'to_name';
			$data['error_string'][] = $this->lang->line('lb_data_not_found');
			$data['list'][] 		= '';
			$data['status'] 		= FALSE;
			$data['message'] = $this->lang->line('lb_data_not_found');
			echo json_encode($data);
			exit();
		endif;

		if($data['status'] === FALSE)
		{
			$data['message'] = $this->lang->line('lb_incomplete_form');
			echo json_encode($data);
			exit();
		}
	}
	private function _validate_product()
	{
		$this->main->validate_modlue_add("inventory","inventory");
		// $data['inputerror'][] 	= "";
		// $data['error_string'][] = '';
		// $data['list'][] 		= '';
		// $data['ara'] 			= $this->input->post();
		// $data['status'] 		= FALSE;

		// $this->main->echoJson($data);
		// exit();
		$CompanyID 	 = $this->session->CompanyID;
		$type 		 = $this->input->post("mutation_type");
		$type 		 = 1; // mutasi hanya store to store
		$from_name 	 = $this->input->post("from_name");
		$rowid 		 = $this->input->post('rowid');
		$productid 	 = $this->input->post('productid');
		$product_qty = $this->input->post('product_qty');
		$product_type= $this->input->post('product_type');
		$no = 1;

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

		$arrProductID = array();
		foreach ($productid as $k => $v) {
			if($v){
				if(!in_array($v,$arrProductID)): array_push($arrProductID,$v); endif;
			}
		}

		foreach($productid as $k => $v):
			if($productid[$k] != ''):
				$status_product = true;
			endif;

			if($productid[$k]):
				$qty_len = strlen($product_qty[$k]);
				if(!$product_qty[$k]):
					$data['inputerror'][] 	= ".".$rowid[$k];
					$data['error_string'][] = $this->lang->line('lb_product_qty_empty');
					$data['list'][] 		= 'list';
					$status_qty 	= false;
					$lb_message 	= $this->lang->line('lb_product_qty_empty');
				else:
					$xtype 	= $product_type[$k];
					$xqty 	= $this->main->checkDuitInput($product_qty[$k]);
					$temp_data  = $this->api->temp_serial("mutation","",$rowid[$k],$productid[$k],"class");
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
		endforeach;

		if(!$status_product || !$status_qty):
			$data['inputerror'][] 	= "";
			$data['error_string'][] = '';
			$data['list'][] 		= '';
			$data['status'] 	= FALSE;
			$data['message'] 	= $lb_message;
			echo json_encode($data);
			exit();
		endif;

		if($type == 0):
			$data_qty	= $this->main->product_detail($CompanyID,$arrProductID,"array");
		else:
			$from_name  = explode("-", $from_name);
			$BranchID 	= $from_name[0];
			$data_qty 	= $this->api->product_branch($BranchID,$arrProductID,"array");
		endif;

		$status_qty = true;
		foreach ($productid as $k => $v) {
			if($productid[$k] && $product_qty[$k]):
				$xqty 	= $this->main->checkDuitInput($product_qty[$k]);
				$check_qty 	= $this->check_qty($data_qty,$productid[$k],$xqty);
				$cek 		= $check_qty['status'];
				$data_qty 	= $check_qty['data'];

				if(!$cek):
					$data['inputerror'][] 	= ".".$rowid[$k];
					$data['error_string'][] = $this->lang->line('lb_product_qty_stock');
					$data['list'][] 		= 'list';
					$status_qty 	= false;
					$lb_message 	= $this->lang->line('lb_product_qty_stock');
				endif;
			endif;
		}

		if(!$status_qty):
			$data['inputerror'][] 	= "";
			$data['error_string'][] = '';
			$data['list'][] 		= '';
			$data['status'] 	= FALSE;
			$data['message'] 	= $lb_message;
			echo json_encode($data);
			exit();
		endif;

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

	private function _validate_serial()
	{
		$productid 			= $this->input->post("productid");
		$serial_number 		= $this->input->post("serial_number");
		foreach ($serial_number as $key => $v) {
			$serialnumber = $serial_number[$key];
			$total = $this->main->cek_serialnumber($productid,$serialnumber);
			if($total == 0):
				$data['inputerror'][] 	= 'productid';
				$data['error_string'][] = 'productid';
				$data['message'] 		= "Serial number ".$serialnumber." not found";
				$data['status'] 		= FALSE;	
				echo json_encode($data);
				exit();
			endif;
		}
	}
	public function tes($id)
	{
		print_r($this->mutasi->generate_mutasi_code($id));
	}
	public function mutation_qty($page ="",$productid="",$qty=""){
		if($page == "done"):
		$this->db->query("UPDATE ps_product set Qty=Qty+$qty WHERE position=0 AND productid='$productid' ");
		elseif($page == "cancel"):
		$this->db->query("UPDATE ps_product set Qty=Qty-$qty WHERE position=0 AND productid='$productid' ");
		endif;
	}

	public function serial_number_list(){
		$mt = $this->input->post("mt");
		$dt = $this->input->post("dt");

		$list = $this->mutasi->serial_number_list($mt,$dt);

		$output = array(
			"status"	=> true,
			"list"		=> $list,
		);

		$this->main->echoJson($output);
	}
	
}
