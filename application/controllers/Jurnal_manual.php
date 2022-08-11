<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnal_manual extends CI_Controller {
	var $title = 'Journal Manual';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_jurnal_manual",'jurnal');
		$this->main->cek_session();
	}

	public function index()
	{	
		$url 						= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url);
		$read 						= $this->main->read($id_url);
		$ac 	= $this->main->check_parameter_module("ac", "jurnal");
		if($read == 0 || $ac->view == 0){ redirect(); }
		$jurnal_tambah 				= $this->main->menu_tambah($id_url);
		if($jurnal_tambah > 0 and $ac->add>0):
            $tambah = $this->main->general_button('add',$this->lang->line('lb_add_new').' '.$this->title);
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['title']  	= $this->title;
		$data['tambah'] 	= $tambah;
		$data['modal'] 		= 'jurnal_manual/modal';
		$data['page'] 		= 'jurnal_manual/list';
		$data['modul'] 		= 'jurnal_manual';
		$data['url_modul'] 	= $url;
		$this->load->view('index',$data);
	}

	public function ajax_list($url_modul="",$modul=""){
		$CompanyID  = $this->session->CompanyID;
		$id_url = $this->main->id_menu($url_modul);
		$edit 	= $this->main->menu_ubah($id_url);
		$delete = $this->main->menu_hapus($id_url);
		$list 	= $this->jurnal->get_datatables();
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		$ac 	= $this->main->check_parameter_module("ac", "jurnal");
		foreach ($list as $a) {
			$KasBankNo = str_replace("/", "-", $a->KasBankNo);
			$btn_edit 		= '';
			$btn_delete 	= '';
			$btn_view 		= '';

			$btn_print 		= $this->main->button_action_dropdown("print", $KasBankNo);
			if($edit>0 and $ac->add>0):
				$btn_view 		= $this->main->button_action_dropdown("view", $KasBankNo);
				$btn_edit 		= $this->main->button_action_dropdown("edit", $KasBankNo);
			endif;

			if($delete>0 and $ac->add>0):
				$btn_delete = $this->main->button_action_dropdown("delete", $KasBankNo);
			endif;

			$button  = '<div class="btn-group pointer">';
			$button .= '<div data-toggle="dropdown" aria-expanded="true">';
			$button .= '<i class="fal fa-cog"></i> <span class="caret"></span> </div>';
			$button .= '<ul class="dropdown-menu animate">';
			$button .= $btn_view;
			$button .= $btn_print;
			$button .= $btn_edit;
			$button .= $btn_delete;
	        $button .= ' </ul>';
	        $button .= '</div>';

	        $btn_action 	= $this->main->button_action("code", $KasBankNo,$a->KasBankNo);

			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $btn_action;
			$row[] 	= $a->Date;  
			$row[] 	= $this->main->currency($a->DebitTotal); 
			$row[] 	= $this->main->currency($a->CreditTotal); 
			// $row[] 	= $button; 
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->jurnal->count_all(),
			"recordsFiltered" => $this->jurnal->count_filtered(),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function save(){
		$this->validate();

		$CompanyID 	 = $this->session->CompanyID;
		$crud 		 = $this->input->post("crud");
		$Code 	 	 = $this->input->post("Code");
		$Date 		 = $this->input->post("Date");
		$Remark 	 = $this->input->post("Remark");
		$TotalDebit  = $this->main->checkDuitInput($this->input->post('TotalDebit'));
		$TotalCredit = $this->main->checkDuitInput($this->input->post('TotalCredit'));

		// detailnya
		$coaid 		 = $this->input->post("coaid");
		$detid 		 = $this->input->post("detid");
		$detremark 	 = $this->input->post("detremark");
		$detdebit 	 = $this->input->post("detdebit");
		$detcredit 	 = $this->input->post("detcredit");

		$data = array(
			"CompanyID"		=> $CompanyID,
			"Date" 			=> $Date,
			"Remark"		=> $Remark,
			"Type"			=> 3,
			"DebitTotal"	=> $TotalDebit,
			"CreditTotal"	=> $TotalCredit,
		);

		if($crud == "update"):
			$where = array("KasBankNo" => $Code, "CompanyID" => $CompanyID);
			$this->jurnal->update($where,$data);
		else:
			$Code  = $this->main->jurnal_generate();
			$data['KasBankNo']	= $Code;
			$this->jurnal->save($data);
		endif;

		$detailidnya = array();
		foreach ($coaid as $key => $v) {
			if($coaid):
				$debitx 	= $this->main->checkDuitInput($detdebit[$key]);
				$creditx 	= $this->main->checkDuitInput($detcredit[$key]);
				$data_det = array(
					"CompanyID"		=> $CompanyID,
					"KasBankNo"		=> $Code,
					"COAID"			=> $coaid[$key],
					"Debit" 		=> $debitx,
					"Credit"		=> $creditx,
					"Remark"		=> $detremark[$key],	
				);

				if($detid[$key]):
					$where = array("KasBankDetNo" => $detid[$key], "CompanyID" => $CompanyID);
					$this->jurnal->update_det($where,$data_det);
					array_push($detailidnya, $detid[$key]);
				else:
					$codedet = $this->main->jurnal_det_generate();
					$data_det['KasBankDetNo'] = $codedet;
					$this->jurnal->save_det($data_det);
					array_push($detailidnya, $codedet);
				endif;
			endif;
		}

		// untuk delete detail yang tidak digunakan
		$this->delete_detail($Code,$detailidnya);
		
		$output = array(
			"status" 	=> TRUE,
			"pesan"		=> $this->lang->line('lb_success'),
		);
		header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT); 
	}

	private function validate(){
		$this->main->validate_update();
		$this->main->validate_modlue_add("ac","ac");
		$data = array();
		$data['status'] = TRUE;

		$TotalDebit  = $this->main->checkDuitInput($this->input->post('TotalDebit'));
		$TotalCredit = $this->main->checkDuitInput($this->input->post('TotalCredit'));

		// detail
		$rowid 	 	= $this->input->post('rowid');
		$coaid 	 	= $this->input->post('coaid');
		$debit 		= $this->input->post('detdebit');
		$credit 	= $this->input->post('detcredit');

		$arstatus = true;
		if(count($coaid)>0):
			$coastatus = FALSE;
			foreach ($coaid as $key => $v) {
				$debitx 	= $this->main->checkDuitInput($debit[$key]);
				$creditx 	= $this->main->checkDuitInput($credit[$key]);
				if($coaid[$key]):
					$coastatus = TRUE;
				endif;
				if($coaid[$key] && $debitx<=0 && $creditx<=0):
					$data['inputerror'][] 	= ".".$rowid[$key];
					$data['error_string'][] = "Debit or credit can't be null";
					$data['list'][] 		= 'list';
					$data['tab'][] 			= '';
					$data['message'] 		= "Debit or credit can't be null";
					$data['status'] 		= FALSE;
					$arstatus 				= FALSE;
				endif;
			}
			if(!$coastatus):
				$data['inputerror'][] 	= '';
				$data['error_string'][] = '';
				$data['list'][] 		= '';
				$data['tab'][] 			= '';
				$data['message'] 		= "Please select COA";
				$data['status'] 		= FALSE;
				$this->main->echoJson($data);
				exit();
			endif;
			if(!$arstatus):
				$this->main->echoJson($data);
				exit();
			endif;
		else:
			$data['inputerror'][] 	= 'coaid';
			$data['error_string'][] = 'coaid';
			$data['list'][] 		= '';
			$data['tab'][] 			= '';
			$data['message'] 		= "Please select COA";
			$data['status'] 		= FALSE;
			$this->main->echoJson($data);
			exit();
		endif;

		if($TotalCredit != $TotalDebit):
			$data['inputerror'][] 	= '';
			$data['error_string'][] = '';
			$data['list'][] 		= '';
			$data['tab'][] 			= '';
			$data['message'] 		= "Total debit and credit not balance";
			$data['status'] 		= FALSE;
		endif;

		if($data['status'] === FALSE)
        {
            header('Content-Type: application/json');
            echo json_encode($data,JSON_PRETTY_PRINT);  
            exit();
        }
	}

	private function delete_detail($id,$array){
		if(count($array)>0):
			$this->db->where("CompanyID", $this->session->CompanyID);
			$this->db->where("KasBankNo", $id);
			$this->db->where_not_in("KasBankDetNo", $array);
			$this->db->delete("AC_KasBank_Det");
		endif;
	}

	public function cetak($id){
		$idnya 		= $id;
		$id 		= str_replace("-", "/", $id);
		$cetak 		= $this->input->get("cetak");
		$position 	= $this->input->get("position");
		$page 		= $this->input->get("page");
		$code 		= "CashBank".$id;

		$nama_laporan   = "";
        $datacompany    = $this->main->company("api");
        $company_name   = $datacompany->nama;
        $company_imgbin = $datacompany->img_bin;
        $company_imgurl = $datacompany->img_url;
        $logo           = base_url($company_imgurl);
		
		$list  		= $this->jurnal->get_by_id($id);
		$detail 	= $this->jurnal->get_by_detail($id);

		$modul  	= $this->input->post("modul");
		$url_modul 	= $this->input->post("url_modul");
		$id_url 	= $this->main->id_menu($url_modul);
		$edit 		= $this->main->menu_ubah($id_url);
		$delete 	= $this->main->menu_hapus($id_url);

		$ac 	= $this->main->check_parameter_module("ac","ac");
		$data_action = array();
		if($ac->add>0 && $delete>0):
			$delete = $this->main->button_action("delete3",$idnya);
        	$data_action['delete'] = $delete;
		endif;

		$data['page'] 			= $page;
		$data["cetak"]			= $cetak;
		$data['list']			= $list;
		$data["detail"]			= $detail;
		$data['title']  		= 'print jurnal';
		$data['title2'] 		= $this->title;
		$data["company_name"]   = $company_name;
		$data["nama_laporan"]   = $nama_laporan;
    	$data["logo"]           = $logo;
    	$data["company"]		= $datacompany;
    	$data['data_action']	= $data_action;
    	$this->load->view('jurnal_manual/view',$data);

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

	public function ajax_delete($id){
		$this->main->validate_modlue_add("ac","ac");
		$id = str_replace("-", "/", $id);
		$CompanyID = $this->session->CompanyID;
		$cek = $this->db->count_all("AC_KasBank where CompanyID = '$CompanyID' and KasBankNo = '$id'");
		if($cek>0):
			$this->jurnal->delete_by_detail($id);
			$this->jurnal->delete_by_id($id);
			$output = array(
				"status" 	=> TRUE,
				"message"	=> $this->lang->line('lb_add_new'),
			);
		else:
			$output = array(
				"status" 	=> FALSE,
				"message"	=> $this->lang->line('lb_data_not_found'),
			);
		endif;

		$this->main->echoJson($output);
	}

	public function ajax_edit($id)
	{	
		$id = str_replace("-", "/", $id);
		$a = $this->jurnal->get_by_id($id);
		$data = $this->jurnal->get_by_detail($id);
		$output = array(
			"hakakses"	=> $this->session->hak_akses,
			"list"		=> $a,
			"detail"	=> $data
		);
		header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
	}
}