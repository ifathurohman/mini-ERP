<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_goodissue extends CI_Controller {
	var $title = 'Stock Issue';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_inventory_goodissue",'goodissue');
		$this->main->cek_session();
		$this->title = $this->lang->line('lb_stock_issue');
	}

	public function index()
	{	
		$url 	= $this->uri->segment(1); 
		$id_url = $this->main->id_menu($url);
		$read 	= $this->main->read($id_url);
		$ap 	= $this->main->check_parameter_module("inventory","good_issue");
		if($read == 0 or $ap->view == 0){ redirect(); }
		$selling_tambah 				= $this->main->menu_tambah($id_url);
		if($selling_tambah > 0 and $ap->add>0):
            $tambah = $this->main->general_button('add',$this->lang->line('lb_add_new').' '.$this->title);
		else: 
			$tambah = ""; 
		endif;
		$this->main->delete_temp_sn("good_issue");
		#ini untuk session halaman aturan user privileges;
		$data['title']  		= $this->title;
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'inventory_goodissue/modal';
		$data['page'] 			= 'inventory_goodissue/list';
		$data['modul'] 			= 'good_issue';
		$data['url_modul'] 		= $url;
		$this->load->view('index',$data);
	}

	public function ajax_list()
	{
		$page 	= "good_issue";
		$list 	= $this->goodissue->get_datatables($page);
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		// $inventory = $this->main->check_parameter_module("inventory", "inventory");
		foreach ($list as $a) {

			$CorrectionNo 	= str_replace("/", "-", $a->CorrectionNo);
            $code 		= '<a href="javascript:;" type="button" title="View" onclick="view('."'".$a->CorrectionNo."'".')">'.$a->CorrectionNo.'</a>';
            $status 	= $this->main->label_active($a->Status,"",$CorrectionNo);

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
			$row[] 	= $a->Date;
			$row[] 	= $status;

			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->goodissue->count_all($page),
			"recordsFiltered" => $this->goodissue->count_filtered($page),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function save(){
		$this->_validate();

		$CompanyID 		= $this->session->CompanyID;
		$correctionno 	= $this->main->correction_code_generate("good_issue");
		$date 			= $this->input->post('Date');
		$BranchID 		= $this->input->post('BranchID');
		$BranchID 		= explode("-", $BranchID)[0];
		$SalesID 		= $this->input->post('SalesID');
		$SalesID 		= explode("-", $SalesID);
		$Remark 		= $this->input->post('Remark');

		// detail
		$rowid 			= $this->input->post('rowid');
		$qty 			= $this->input->post('product_qty');
		$product_id 	= $this->input->post('product_id');
		$product_unitid = $this->input->post('product_unitid');
		$product_type 	= $this->input->post('product_type');
		$product_konv 	= $this->input->post('product_konv');
		$product_price 	= $this->input->post('product_price');
		$product_remark = $this->input->post('product_remark');

		// serial post
		$dt_serial 	  	= $this->input->post("dt_serial");
		$dt_serialkey 	= $this->input->post("dt_serialkey");
		$dt_serialauto 	= $this->input->post("dt_serialauto");
		$dt_serial    	= json_decode($dt_serial);
		$dt_serialkey 	= json_decode($dt_serialkey);
		$dt_serialauto 	= json_decode($dt_serialauto);

		$data = array(
			'CorrectionNo' 	=> $correctionno,
			'CompanyID'		=> $CompanyID,
			'Date' 			=> $date,
			'Type'			=> 4,
			'SalesID'		=> $SalesID[0],
			'Remark'		=> $Remark,
		);
		if($BranchID):
			$data['BranchID'] = $BranchID;
		endif;
		$insert = $this->goodissue->save($data);

		foreach ($product_id as $key => $v) {
			if($product_id[$key]):
				$correctiondet 	= $this->main->correctiondet_code_generate("good_issue");
				$xprice 		= $this->main->checkDuitInput($product_price[$key]);
				$xqty 			= $this->main->checkDuitInput($qty[$key]);
				$xqty2 			= -1 * ($xqty * $product_konv[$key]);
				$xqty3 			= ($xqty * $product_konv[$key]);
				$average 		= $this->main->average($product_id[$key],$xqty2,$xprice,$BranchID);

				$data = array(
					'CorrectionDet'	=> $correctiondet,
					'CorrectionNo' 	=> $correctionno,
					'CompanyID'		=> $CompanyID,
					'ProductID' 	=> $product_id[$key],
					'Qty' 			=> $xqty,
					'Price'			=> $xprice,
					'Conversion'	=> $product_konv[$key],
					'Uom'			=> $product_unitid[$key],
					'Remark'		=> $product_remark[$key],
					'User_Add' 		=> $this->session->nama,
					'Date_Add' 		=> date("Y-m-d H:i:s")
				);

				$det_ID = $this->goodissue->save_det($data); // ini insert detail

				$this->kurangQty($product_id[$key],$xqty3,$BranchID);

				// simpan serial number
				$xtype = $product_type[$key];
				if($xtype == 2):
					$arrkey = array_keys($dt_serialkey,$rowid[$key]);
					$arrTempSN = array();
					foreach ($arrkey as $key2 => $value_key) {
						$sn = $dt_serial[$value_key];
						array_push($arrTempSN, $sn);
					}
					
					foreach ($arrkey as $key2 => $value_key) {
						$no = $key2 + 1;
						if($xqty>=$no):
							$sn = $dt_serial[$value_key];
							
							// insert ke table PS_Correction_Detail_SN
							$data_serial = array(
								"CompanyID"			=> $CompanyID,
								"Status"			=> 1,
								"Qty"				=> 1,
								"ProductID"			=> $product_id[$key],
								"CorrectionNo"		=> $correctionno,
								"CorrectionDetID" 	=> $det_ID,
								"User_Add"			=> $this->session->NAMA,
								"Date_Add"			=> date("Y-m-d H:i:s"),
								"SN"				=> $sn,
							);
							$this->db->insert("PS_Correction_Detail_SN", $data_serial);

            				// update ke PS_Product_Serial
            				$data_serial = array(
								"Status" 	=> 0,
								"User_Ch"	=> $this->session->NAMA,
								"Date_Ch"	=> date("Y-m-d H:i:s"),
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

		$this->main->delete_temp_sn("good_issue");
		echo json_encode(array("status" => TRUE,"message" => $this->lang->line('lb_success'), "ID" => $correctionno));
	}

	private function _validate(){
		$this->main->validate_update();
		$this->main->validate_modlue_add("inventory","inventory");
		$data = array();
		$data['status'] = TRUE;

		// $data['inputerror'][] 	= "";
		// $data['error_string'][] = '';
		// $data['list'][] 		= '';
		// $data['ara'] 			= $this->input->post();
		// $data['status'] 		= FALSE;

		// $this->main->echoJson($data);
		// exit();

		$CompanyID 		= $this->session->CompanyID;
		$BranchID 		= $this->input->post('BranchID');
		$BranchID 		= explode("-", $BranchID)[0];
		$rowid 			= $this->input->post('rowid');
		$product_id 	= $this->input->post('product_id');
		$product_type 	= $this->input->post('product_type');
		$qty 			= $this->input->post('product_qty');
		$product_konv 	= $this->input->post('product_konv');

		// serial post
		$dt_serial 	  	= $this->input->post("dt_serial");
		$dt_serialkey 	= $this->input->post("dt_serialkey");
		$dt_serialauto 	= $this->input->post("dt_serialauto");
		$dt_serial    	= json_decode($dt_serial);
		$dt_serialkey 	= json_decode($dt_serialkey);
		$dt_serialauto 	= json_decode($dt_serialauto);

		$arrProductID = array();
		foreach ($product_id as $k => $v) {
			if($v){
				if(!in_array($v,$arrProductID)): array_push($arrProductID,$v); endif;
			}
		}

		if($BranchID):
			$data_qty 	= $this->api->product_branch($BranchID,$arrProductID,"array");
		else:
			$data_qty	= $this->main->product_detail($CompanyID,$arrProductID,"array");
		endif;

		// pengecekan product
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
				$data['message'] 		= $this->lang->line('lb_select_product_empty');
				$data['status'] 		= FALSE;
				$status_product 		= false;
				echo json_encode($data);
				exit();
			endif;
		else:
			$data['inputerror'][] 	= '';
			$data['error_string'][] = '';
			$data['list'][] 		= '';
			$data['message'] 		= $this->lang->line('lb_select_product_empty');
			$data['status'] 		= FALSE;
			$status_product 		= false;
			echo json_encode($data);
			exit();
		endif;

		// pengecekan product qty dan serial number
		if(count($product_id) > 0):
			$status_qty = true;
			foreach($product_id as $key => $v):
				if($product_id[$key]):
					$xqty = $this->main->checkDuitInput($qty[$key]);
					// jika qty nya kurang dari 1 atau kosong
					if($xqty < 1 || empty($xqty)):
						$data['inputerror'][] 	= $rowid[$key];
						$data['error_string'][] = $this->lang->line('lb_product_qty_empty');
						$data['list'][] 		= 'list';
						$data['message'] 		= $this->lang->line('lb_product_qty_empty');
						$data['status'] 		= FALSE;
						$status_qty 			= false;
					
					// pengecekan serial number
					else:
						$xqty2  	= $xqty * $product_konv[$key];
						$check_qty 	= $this->check_qty($data_qty,$product_id[$key],$xqty2);
						$cek 		= $check_qty['status'];
						$data_qty 	= $check_qty['data'];
						if(!$cek):
							$data['inputerror'][] 	= $rowid[$key];
							$data['error_string'][] = $this->lang->line('lb_product_qty_stock');
							$data['list'][] 		= 'list';
							$data['message'] 		= $this->lang->line('lb_product_qty_stock');
							$data['status'] 		= FALSE;
							$status_qty 	= false;
							$lb_message 	= $this->lang->line('lb_product_qty_stock');
						endif;

						$xtype = $product_type[$key];
						$temp_data  = $this->api->temp_serial("good_issue","",$rowid[$key],$product_id[$key],"class");
						if($xtype == 2):
							$arrkey = array_keys($dt_serialkey,$rowid[$key]);
							if($xqty>count($arrkey)):
								$data['inputerror'][] 	= $rowid[$key];
								$data['error_string'][] = $this->lang->line('lb_serial_empty');
								$data['list'][] 		= 'list';
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

		$ck_branch = $this->db->count_all("Branch where CompanyID = '$CompanyID' and BranchID = '$BranchID' and Active = '1'");
		if($ck_branch<=0):
			$data['inputerror'][] 	= 'BranchID';
			$data['error_string'][] = '';
			$data['list'][] 		= '';
			$data['status'] 		= FALSE;
			$data['message'] 		= $this->lang->line('lb_data_not_found');
			echo json_encode($data);
			exit();
		endif;

		if($this->input->post('SalesID') == ''):
			$data['inputerror'][] 	= 'SalesID';
			$data['error_string'][] = '';
			$data['list'][] 		= '';
			$data['status'] 		= FALSE;
		endif;

		if($data['status'] === FALSE)
        {
            $this->main->echoJson($data);
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
		$idnya 		= $id;
		$id 		= str_replace("-", "/", $id);
		$cetak 		= $this->input->get("cetak");
		$position 	= $this->input->get("position");
		$page 		= $this->input->get("page");
		$code 		= "stock-issue-".$id;

		$nama_laporan   = "";
        $datacompany    = $this->main->company("api");
        $company_name   = $datacompany->nama;
        $company_imgbin = $datacompany->img_bin;
        $company_imgurl = $datacompany->img_url;
        $logo           = base_url($company_imgurl);
		
		$list  		= $this->goodissue->get_by_id($id,"edit");
		$detail 	= $this->goodissue->get_by_detail($id);

		$modul  	= $this->input->post("modul");
		$url_modul 	= $this->input->post("url_modul");
		$id_url 	= $this->main->id_menu($url_modul);
		$edit 		= $this->main->menu_ubah($id_url);
		$delete 	= $this->main->menu_hapus($id_url);

		$ap 	= $this->main->check_parameter_module("inventory","inventory");
		$data_action = array();
      	$cancel = '';
      	if($list->Status == 1 && $ap->add>0 && $delete>0):
	        $cancel = $this->main->button_action("cancel",$idnya);
	        // $data_action['cancel'] = $cancel;
      	endif;
		if($ap->add>0 && $edit>0):
			$btn_edit = $this->main->button_action("edit_attach",$idnya);
			$data_action['edit'] = $btn_edit;
		endif;

		$data['page'] 			= $page;
		$data["cetak"]			= $cetak;
		$data['list']			= $list;
		$data["detail"]			= $detail;
		$data['title']  		= 'print Good Issue';
		$data['title2'] 		= $this->title;
		$data["company_name"]   = $company_name;
		$data["nama_laporan"]   = $nama_laporan;
    	$data["logo"]           = $logo;
    	$data["company"]		= $datacompany;
    	$data['data_action']	= $data_action;
    	if($page == "print"):
    		$data['template'] 	= $this->main->get_default_template("penerimaan");
    		// $this->load->view('inventory_goodissue/template',$data);
    		$this->load->view('inventory_goodissue/view',$data);
    	else:
    		$this->load->view('inventory_goodissue/view',$data);
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

	public function serial_number_list(){
		$mt = $this->input->post("mt");
		$dt = $this->input->post("dt");

		$list = $this->goodissue->serial_number_list($mt,$dt);

		$output = array(
			"status"	=> true,
			"list"		=> $list,
		);

		$this->main->echoJson($output);
	}

	public function save_remark(){
		$CompanyID 	= $this->session->CompanyID;
		$ID 		= $this->input->post("ID");
		$Remark 	= $this->input->post("Remark");

		$status 	= false;
		$message 	= $this->lang->line('lb_data_not_found');

		if($CompanyID && $ID):
			$cek = $this->db->count_all("PS_Correction where CompanyID = '$CompanyID' and CorrectionNo = '$ID'");
			if($cek>0):
				$data = array(
					"Remark"	=> $Remark,
				);
				$this->db->where("CompanyID", $CompanyID);
				$this->db->where("CorrectionNo", $ID);
				$this->db->update("PS_Correction", $data);

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

	public function cancel($id){
		exit();
		$this->main->validate_modlue_add("inventory","inventory");
		$id 		= str_replace("-", "/", $id);
		$CompanyID = $this->session->CompanyID;
		$cek = $this->db->count_all("PS_Correction where CorrectionNo = '$id'");
		if($cek>0):
			$header = $this->goodissue->get_by_id($id);
			$detail = $this->goodissue->get_by_detail($id);
			foreach ($detail as $key => $v) {
				$xqty  		= ($v->Qty * $v->Conversion);
				$xprice 	= (float) $v->Price;
				$this->main->average($v->ProductID,$xqty,$xprice,$header->BranchID);
				$this->tambahQty($v->ProductID,$v->Qty,$header->BranchID);

				// serial number
				if($v->product_type == 2):
					$data_sn = $this->goodissue->serial_number($id,$v->ID,$v->ProductID);
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

			}
			$data = array("Status" => 0);
			$this->goodissue->update(array("CorrectionNo"=>$id, "CompanyID" => $CompanyID,),$data);

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

	private function tambahQty($ProductID,$Qty,$BranchID){
		if($BranchID):
			$this->db->query("UPDATE PS_Product_Branch set Qty=ifnull(Qty,0)+$Qty WHERE ProductID = '$ProductID' and BranchID = '$BranchID'");
		else:
			$this->db->query("UPDATE ps_product set Qty=ifnull(Qty,0)+$Qty WHERE ProductID = '$ProductID'");
		endif;
	}

	private function kurangQty($ProductID,$Qty,$BranchID){
		if($BranchID):
			$this->db->query("UPDATE PS_Product_Branch set Qty=ifnull(Qty,0)-$Qty WHERE ProductID = '$ProductID' and BranchID = '$BranchID'");
		else:
			$this->db->query("UPDATE ps_product set Qty=ifnull(Qty,0)-$Qty WHERE ProductID = '$ProductID'");
		endif;
	}
}