<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reset extends CI_Controller {
	var $title = "Reset Data";
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_reset",'reset');
		$this->main->cek_session();
		$this->title = $this->lang->line('lb_reset_data');
	}

	public function index()
	{	
		$url 	= $this->uri->segment(1); 
		$id_url = $this->main->id_menu($url);
		$read 	= $this->main->read($id_url);
		if($read == 0){ redirect(); }
		$selling_reset = $this->main->menu_tambah($id_url);
		if($selling_reset > 0):
            $tambah = $this->main->general_button('add', $this->title);
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['title']  		= $this->title;
		$data['tambah'] 		= $tambah;
		$data['page'] 			= 'reset/list';
		$data['modul'] 			= 'reset_data';
		$data['url_modul'] 		= $url;
		$this->load->view('index',$data);
	}

	public function ajax_list($url_modul="",$modul=""){
		$CompanyID  = $this->session->CompanyID;
		$id_url = $this->main->id_menu($url_modul);
		$edit 	= $this->main->menu_ubah($id_url);
		$delete = $this->main->menu_hapus($id_url);
		$list 	= $this->reset->get_datatables();
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		foreach ($list as $a) {
			$btn_edit 		= '';
			$btn_cancel 	= '';
			$btn_view 		= '';

			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $a->Date;
			$row[] 	= $a->User_Add;
			$row[] 	= $this->main->label_reset_type($a->Type);
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->reset->count_all(),
			"recordsFiltered" => $this->reset->count_filtered(),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function save(){
		$CompanyID 	= $this->session->CompanyID;
		$Type 		= $this->input->post("Type");

		if(strlen($Type)>0 && $CompanyID):
			$fType = 2;
			if($Type == 1):
				$fType = 1;
			endif;
			$data = array(
				'CompanyID'	=> $CompanyID,
				'UserID'	=> $this->session->UserID,
				'Date'		=> date("Y-m-d H:i:s"),
				'Type'		=> $fType,
			);
			$this->reset->save($data);
			$this->reset->transaction_delete();
			if($fType == 2):
				$this->reset->master_delete();
			endif;
			$this->main->insert_log(2,"reset_data",json_encode($data));
			$output = array(
				"status" 	=> true,
				"message"	=> $this->lang->line('lb_reset_success'),
			);
		else:
			$output = array(
				"status" 	=> false,
				"message"	=> $this->lang->line('lb_data_not_found'),
			);
		endif;

		$this->main->echoJson($output);
	}
}