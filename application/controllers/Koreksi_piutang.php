<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Koreksi_piutang extends CI_Controller {
	var $title = 'Receivables Correction';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_koreksi_piutang",'koreksi');
		$this->main->cek_session();
		$this->title = $this->lang->line('lb_correctionar');
	}
	public function index()
	{
		$url 						= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url);
		$read 						= $this->main->read($id_url);
		$ar 	= $this->main->check_parameter_module("ar","correction_ar");
		if($read == 0 or $ar->view == 0){ redirect(); }
		$koreksi_tambah 				= $this->main->menu_tambah($id_url);
		if($koreksi_tambah > 0 and $ar->add > 0):
            $tambah = $this->main->general_button('add',$this->lang->line('lb_add_new').' '.$this->title);
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['url'] 			= 'list-koreksi';
		$data['title']  		= $this->title;
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'koreksi_ar/modal';
		$data['modal_vendor'] 	= 'modal/modal_vendor';
		$data['page'] 			= 'koreksi_ar/list';
		$data['modul'] 			= 'ar_correction';
		$data['url_modul'] 		= $url;
		$this->load->view('index',$data);
	}
	//---------------------------------------------------------------------------------------
	public function ajax_list()
	{
		$CompanyID = $this->session->CompanyID;
		$page 	= "koreksi";
		$url 	= $this->uri->segment(1); 
		$id_url = $this->main->id_menu($url);
		$list 	= $this->koreksi->get_datatables($page);
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		$ar 	= $this->main->check_parameter_module("ar","correction_ar");
		foreach ($list as $a) {
			$btn_view 	= '';
			$ubah 		= "";
			$hapus 		= "";
			$koreksi_ubah 	= $this->main->menu_ubah($id_url);
			$koreksi_hapus 	= $this->main->menu_hapus($id_url);
			$btn_view 		= $this->main->button_action_dropdown("view", $a->BalanceID);
			$btn_print 		= $this->main->button_action_dropdown("print", $a->BalanceID);
			if($koreksi_ubah > 0 and $ar->add > 0):
				$ubah 		= $this->main->button_action_dropdown("edit", $a->BalanceID);
			endif;
			if($koreksi_hapus > 0 and $ar->add > 0):
           		$hapus 		= $this->main->button_action_dropdown("delete", $a->BalanceID);
			endif;

			$cek  			= $a->ck_payment;
			if($cek>0):
				$ubah = '';
				$hapus = '';
			endif;

			$button  = '<div class="btn-group pointer">';
			$button .= '<div data-toggle="dropdown" aria-expanded="true">';
			$button .= '<i class="fal fa-cog"></i> <span class="caret"></span> </div>';
			$button .= '<ul class="dropdown-menu animate">';
			$button .= $btn_view;
			$button .= $btn_print;
			$button .= $ubah;
            $button .= $hapus;
	        $button .= ' </ul>';
	        $button .= '</div>';

	        $btn_action 	= $this->main->button_action("code", $a->BalanceID,$a->balanceno);

			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $btn_action;
			$row[] 	= date("Y-m-d",strtotime($a->date));

			// $row[] 	= $button;		
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
		$data = $this->koreksi->get_list_detail($id);
		$output = array(
			"hakakses"	=> $this->session->hak_akses,
			"list"		=> $a,
			"detail"	=> $data
		);
		header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
	}

	public function simpan()
	{
		$this->_validate("save");
		
		$CompanyID 	= $this->session->CompanyID;
		$crud 		= $this->input->post("crud");
		$BalanceID 	= $this->input->post('BalanceID');
		$Date 		= $this->input->post("date");
		$balanceno 	= $this->input->post("balanceno");
		$ckOrder 	= $this->input->post("ckOrder");
		$Remark 	= $this->input->post("Remark");
		$BalanceType 	= $this->input->post("BalanceType");

		// detail dari selling
		$arVendorName 	= $this->input->post('arVendorName');
		$arVendorID 	= $this->input->post('arVendorID');
		$arTotal 		= $this->input->post('arTotal');
		$arRemark 		= $this->input->post('arRemark');
		$arDetid 		= $this->input->post('arDetid');

		// detail dari store
		$BranchID 	= $this->input->post('BranchID');
		$Total 		= $this->input->post('Total');
		$Detid 		= $this->input->post('Detid');
		$Remarks 		= $this->input->post('Remarks');


		$total = 0;
		if($ckOrder == 1):
			foreach ($BranchID as $key => $value) {
				$totalx = $this->main->checkDuitInput($Total[$key]);
				if($BranchID and $totalx>0):
					$total += $totalx;
				endif;
			}
		else:
			foreach ($arVendorID as $key => $value) {
				$totalx = $this->main->checkDuitInput($arTotal[$key]);
				if($arVendorID and $totalx>0):
					$total += $totalx;
				endif;
			}
		endif;

		$data = array(
			"CompanyID" 		=> $CompanyID,
			"Date" 				=> $Date,
			"Type"				=> 2,
			"OrderType"			=> $ckOrder,
			"BalanceType"		=> $BalanceType,
			"TotalCorrection" 	=> $total,
			"Remark" 			=> $Remark,
		);

		if($crud == "update"):
			$where = array("CompanyID" => $CompanyID, "BalanceID" => $BalanceID);
			$this->koreksi->update($where, $data);
		else:
			$balanceno 		= $this->main->correctionar_generate();
			$data['Code'] 	= $balanceno;
			$data['Active']	= 1;
			$BalanceID  	= $this->koreksi->save($data);
		endif;
		
		$detailidnya = array();
		if($ckOrder == 1):
			foreach ($BranchID as $key => $value) {
				$totalx = $this->main->checkDuitInput($Total[$key]);
				if($BranchID and $totalx>0):
					$data_detail = array(
						'BalanceID' 	=> $BalanceID,
						'CompanyID' 	=> $CompanyID,
						'BranchID'		=> $BranchID[$key],
						"Type"			=> 2,
						"Remark"		=> $Remarks[$key],
						"TotalReal"		=> $totalx,
						"TotalCorrection"	=> $totalx,
						"Payment"		=> 0,
					);

					if($Detid[$key]):
						$BalanceDetID = $Detid[$key];
						$where = array("BalanceID" => $BalanceID, "BalanceDetID" => $BalanceDetID);
						$this->koreksi->update_detail($where,$data_detail);
					else:
						$BalanceDetID = $this->koreksi->save_detail($data_detail);
					endif;
					array_push($detailidnya, $BalanceDetID);
				endif;
			}
		else:
			foreach ($arVendorID as $key => $value) {
				$totalx = $this->main->checkDuitInput($arTotal[$key]);
				if($arVendorID and $totalx>0):
					$data_detail = array(
						'BalanceID' 	=> $BalanceID,
						'CompanyID' 	=> $CompanyID,
						'VendorID'		=> $arVendorID[$key],
						"Type"			=> 2,
						"Remark"		=> $arRemark[$key],
						"TotalReal"		=> $totalx,
						"TotalCorrection"	=> $totalx,
						"Payment"		=> 0,
					);

					if($arDetid[$key]):
						$BalanceDetID = $arDetid[$key];
						$where = array("BalanceID" => $BalanceID, "BalanceDetID" => $BalanceDetID);
						$this->koreksi->update_detail($where,$data_detail);
					else:
						$BalanceDetID = $this->koreksi->save_detail($data_detail);
					endif;
					array_push($detailidnya, $BalanceDetID);
				endif;
			}
		endif;
		// untuk delete detail yang tidak digunakan
		$this->delete_detail($BalanceID,$detailidnya);

		// generate journal
		$this->db->query("CALL run_generate_jurnal('UPDATE', '$balanceno', 'correction_ar', '$CompanyID')");
		
		$output = array(
			"status" 	=> TRUE,
			"pesan"		=> $this->lang->line('lb_success'),
			"ID"		=> $BalanceID,
		);
		header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
	}
	private function _validate($page = "")
	{
		$this->main->validate_update();
		$this->main->validate_modlue_add("ar","ar");
		$data 					= array();
		$data['error_string'] 	= array();
		$data['inputerror'] 	= array();
		$data['status'] 		= TRUE;
		
		$crud 		= $this->input->post('crud');
		$ckOrder 	= $this->input->post('ckOrder');

		// data detail
		$arVendorName 	= $this->input->post('arVendorName');
		$arVendorID 	= $this->input->post('arVendorID');
		$arTotal 		= $this->input->post('arTotal');
		$arrowid 		= $this->input->post('arrowid');

		// detail dari store
		$BranchID 	= $this->input->post('BranchID');
		$Total 		= $this->input->post('Total');
		$rowid 		= $this->input->post('rowid');
		if($ckOrder == 1):
			$arstatus = true;
			if(count($BranchID)>0):
				$branchstatus = FALSE;
				foreach ($BranchID as $key => $v) {
					$total = $this->main->checkDuitInput($Total[$key]);
					if($BranchID[$key]):
						$branchstatus = TRUE;
					endif;
					if($BranchID[$key] and $total<=0):
						$data['inputerror'][] 	= ".".$rowid[$key];
						$data['error_string'][] = $this->lang->line('lb_correction_total_empty');
						$data['list'][] 		= 'list';
						$data['tab'][] 			= '';
						$data['message'] 		= $this->lang->line('lb_correction_total_empty');
						$data['status'] 		= FALSE;
						$arstatus 				= FALSE;
					endif;
				}
				if(!$branchstatus):
					$data['inputerror'][] 	= '';
					$data['error_string'][] = '';
					$data['list'][] 		= '';
					$data['tab'][] 			= '';
					$data['message'] 		= $this->lang->line('lb_store_select1');
					$data['status'] 		= FALSE;
					$this->main->echoJson($data);
					exit();
				endif;
				if(!$arstatus):
					$this->main->echoJson($data);
					exit();
				endif;
			else:
				$data['inputerror'][] 	= 'productid';
				$data['error_string'][] = 'productid';
				$data['list'][] 		= '';
				$data['tab'][] 			= '';
				$data['message'] 		= $this->lang->line('lb_store_select1');
				$data['status'] 		= FALSE;
				$this->main->echoJson($data);
				exit();
			endif;
		else:
			$arstatus = true;
			if(count($arVendorID)>0):
				$vendorstatus = FALSE;
				foreach ($arVendorID as $key => $v) {
					$total = $this->main->checkDuitInput($arTotal[$key]);
					if($arVendorID[$key]):
						$vendorstatus = TRUE;
					endif;
					if($arVendorID[$key] and $total<=0):
						$data['inputerror'][] 	= ".".$arrowid[$key];
						$data['error_string'][] = $this->lang->line('lb_correction_total_empty');
						$data['list'][] 		= 'list';
						$data['tab'][] 			= '';
						$data['message'] 		= $this->lang->line('lb_correction_total_empty');
						$data['status'] 		= FALSE;
						$arstatus 				= FALSE;
					endif;
				}
				if(!$vendorstatus):
					$data['inputerror'][] 	= '';
					$data['error_string'][] = '';
					$data['list'][] 		= '';
					$data['tab'][] 			= '';
					$data['message'] 		= $this->lang->line('lb_customer_select');
					$data['status'] 		= FALSE;
					$this->main->echoJson($data);
					exit();
				endif;
				if(!$arstatus):
					$this->main->echoJson($data);
					exit();
				endif;
			else:
				$data['inputerror'][] 	= 'productid';
				$data['error_string'][] = 'productid';
				$data['list'][] 		= '';
				$data['tab'][] 			= '';
				$data['message'] 		= $this->lang->line('lb_customer_select');
				$data['status'] 		= FALSE;
				$this->main->echoJson($data);
				exit();
			endif;
		endif;

		if($data['status'] === FALSE)
		{
			$this->main->echoJson($data);
			exit();
		}
	}

	private function delete_detail($BalanceID,$array){
		if(count($array)>0):
			$this->db->where("CompanyID", $this->session->CompanyID);
			$this->db->where("BalanceID", $BalanceID);
			$this->db->where_not_in("BalanceDetID", $array);
			$this->db->delete("AC_BalancePayable_Det");
		endif;
	}

	public function ajax_delete($id){
		$this->main->validate_modlue_add("ar","ar");
		$CompanyID = $this->session->CompanyID;
		$cek = $this->db->count_all("AC_BalancePayable where CompanyID = '$CompanyID' and BalanceID = '$id' and Active = '1'");
		if($cek>0):
			$attach = $this->api->attachment_list("ar_correction",$id);
			foreach ($attach as $k => $v) {
				$file = $v->Image;
				if(file_exists('./' . $file)){
                    unlink('./' . $file);
                }
                $this->db->where("CompanyID", $CompanyID);
                $this->db->where("AttachmentID", $v->attachID);
                $this->db->delete("PS_Attachment");
			}
			$Code = $this->main->get_one_column("AC_BalancePayable","Code",array("CompanyID" => $CompanyID, "BalanceID" => $id))->Code;
			$this->koreksi->delete_by_detail($id);
			$this->koreksi->delete_by_id($id);
			$output = array(
				"status" 	=> TRUE,
				"message"	=> $this->lang->line('lb_success'),
			);
			// generate journal
			$this->db->query("CALL run_generate_jurnal('DELETEFULL', '$Code', 'correction_ar', '$CompanyID')");
		else:
			$output = array(
				"status" 	=> FALSE,
				"message"	=> $this->lang->line('lb_data_not_found'),
			);
		endif;

		$this->main->echoJson($output);
	}

	public function cetak($id){
		$idnya 		= $id;
		$this->main->default_template("ar_correction");
		$cetak 		= $this->input->get("cetak");
		$position 	= $this->input->get("position");
		$page 		= $this->input->get("page");
		$code 		= "ARC".$id;

		$nama_laporan   = "";
        $datacompany    = $this->main->company("api");
        $company_name   = $datacompany->nama;
        $company_imgbin = $datacompany->img_bin;
        $company_imgurl = $datacompany->img_url;
        $logo           = base_url($company_imgurl);
		
		$list  		= $this->koreksi->get_by_id($id,"edit");
		$detail 	= $this->koreksi->get_list_detail($id);

		$modul  	= $this->input->post("modul");
		$url_modul 	= $this->input->post("url_modul");
		$id_url 	= $this->main->id_menu($url_modul);
		$edit 		= $this->main->menu_ubah($id_url);
		$delete 	= $this->main->menu_hapus($id_url);

		$ar 	= $this->main->check_parameter_module("ar","ar");
		$data_action = array();
		if($list->Active == 1 && $ar->add>0 && $list->ck_payment<=0 && $delete>0):
			$delete = $this->main->button_action("delete3",$idnya);
	        $data_action['delete'] = $delete;
		endif;
		if($ar->add>0 && $edit>0):
			$btn_edit = $this->main->button_action("edit_attach",$idnya);
			$data_action['edit'] = $btn_edit;
		endif;

		$data['page'] 			= $page;
		$data["cetak"]			= $cetak;
		$data['list']			= $list;
		$data["detail"]			= $detail;
		$data['title']  		= 'print ar correcton';
		$data['title2'] 		= $this->title;
		$data["company_name"]   = $company_name;
		$data["nama_laporan"]   = $nama_laporan;
    	$data["logo"]           = $logo;
    	$data["company"]		= $datacompany;
    	$data['data_action']	= $data_action;

    	if($page == "print"):
    		$data['template'] 	= $this->main->get_default_template("ar_correction");
    		$this->load->view('koreksi_ar/template',$data);
    	else:
    		$this->load->view('koreksi_ar/view',$data);
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

	public function save_remark(){
		$CompanyID 	= $this->session->CompanyID;
		$ID 		= $this->input->post("ID");
		$Remark 	= $this->input->post("Remark");

		$status 	= false;
		$message 	= $this->lang->line('lb_data_not_found');

		if($CompanyID && $ID):
			$cek = $this->db->count_all("AC_BalancePayable where CompanyID = '$CompanyID' and BalanceID = '$ID'");
			if($cek>0):
				$data = array(
					"Remark"	=> $Remark,
				);
				$this->db->where("CompanyID", $CompanyID);
				$this->db->where("BalanceID", $ID);
				$this->db->update("AC_BalancePayable", $data);

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
}
