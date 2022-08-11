<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template extends CI_Controller {
	var $title = 'Template';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_template",'template');
		// $this->load->library(array('PHPExcel','IOFactory'));
		$this->main->cek_session();
	}
	public function index()
	{
		$url 						= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url);
		$read 						= $this->main->read($id_url);
		if($read == 0){ redirect(); }
		$template_tambah 				= $this->main->menu_tambah($id_url);
		if($template_tambah > 0):
            $tambah = $this->main->general_button('add',$this->lang->line('lb_add_new').' '.$this->title);
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['master'] 		= $this->lang->line('lb_manage');
		$data['title']  		= $this->title;
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'template/modal';
		$data['page'] 			= 'template/list';
		$data['modul'] 			= 'template';
		$data['url_modul'] 		= $url;
		$this->load->view('index',$data);
	}

	public function ajax_list($url_modul="",$modul=""){
		$id_url = $this->main->id_menu($url_modul);
		$edit 	= $this->main->menu_ubah($id_url);
		$delete = $this->main->menu_hapus($id_url);
		$list 	= $this->template->get_datatables();
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		foreach ($list as $a) {
			$btn_edit 		= '';
			$btn_delete 	= '';
			$btn_view  		= $this->main->button_action("view", $a->TemplateID);
			$status 		= $this->main->label_active2($a->Status);

			if($edit>0):
				$btn_edit = $this->main->button_action("edit", $a->TemplateID);
			endif;

			if($delete>0):
				if($a->Status == 1):
					$btn_delete = $this->main->button_action("delete2", $a->TemplateID);
				else:
					$btn_delete = $this->main->button_action("undelete", $a->TemplateID);
				endif;
			endif;

			$image = '<a href="'.base_url($a->Image).'" target="_blank" title="Image"><i class="icon fa-image" aria-hidden="true"></i></a>';

			$button  = '<div class="btn-group btn-group-xs" aria-label="Basic example" role="group">';
            $button .= $btn_view;
            $button .= $btn_edit;
            $button .= $btn_delete;
            $button .= '</div>';

            $code = '<a href="javascript:void(0)" type="button" class="" title="View Detail" onclick="view_print('."'".$a->TemplateID."','view'".')">'.$a->Name.'</a>';

			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $code;
			$row[] 	= $this->main->label_template($a->Type); 
			$row[] 	= $image;
			$row[] 	= $status;
			$row[] 	= $a->Remark;
			// $row[] 	= $button; 
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->template->count_all(),
			"recordsFiltered" => $this->template->count_filtered(),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function save(){
		$this->validate();
		$crud  		= $this->input->post("crud");
		$TemplateID = $this->input->post('TemplateID');
		$Name 		= $this->input->post('Name');
		$Type 		= $this->input->post('Type');
		$Remark 	= $this->input->post('Remark');
		$Content 	= $this->input->post('Content');

		$data = array(
			'Name'		=> $Name,
			'Remark'	=> $Remark,
			'Type'		=> $Type,
			'Content'	=> $Content,
		);

		$fileName                 = $this->session->app."_".time();
		$config['upload_path']    = './img/template'; 
		$config['file_name']      = $fileName;
		$config['allowed_types']    = 'gif|jpg|png|jpeg|bmp|PNG|JPG'; //type yang dapat diakses bisa anda sesuaikan 
		$config['max_size']         = '99999'; //maksimum besar file 2M 
        $config['max_width']        = '99999'; //lebar maksimum 1288 px 
        $config['max_height']       = '99999'; //tinggi maksimu 768 px 
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if ($this->upload->do_upload('file')):
        	$gbr 			= $this->upload->data();
			$image         	= "img/template/".$gbr['file_name'];
			$data['Image']    = $image;
			if($crud == "update"):
            	$this->template->delete_img(array("TemplateID"=>$TemplateID));
            endif;
        endif;

		if($crud == "insert"):
			$data['Status'] 	= 1;
			$data['CompanyID'] 	= $this->session->CompanyID;
			$this->template->save($data);
		else:
			$this->template->update(array("TemplateID" => $TemplateID), $data);
		endif;

		$res = array(
			'status' 	=> true,
			'message' 	=> $this->lang->line('lb_success'),
		);
		$this->main->echoJson($res);
	}

	private function validate(){
		$crud  		= $this->input->post("crud");
		$Name  		= $this->input->post("Name");
		$Type  		= $this->input->post("Type");
		$Content 	= $this->input->post("Content");
		$CompanyID 	= $this->session->CompanyID;
		$data 					= array();
		$data['error_string'] 	= array();
		$data['inputerror'] 	= array();
		$data['status'] 		= TRUE;
		$data['hak_akses'] 		= $this->session->hak_akses;

		if($Name == ''):
			$data['inputerror'][] 	= 'Name';
			$data['error_string'][] = $this->lang->line('lb_name_empty');
			$data['status'] 		= FALSE;
		endif;

		if(!$Type):
			$data['inputerror'][] 	= 'Type';
			$data['error_string'][] = $this->lang->line('lb_type_empty');
			$data['status'] 		= FALSE;
		endif;

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	public function ajax_edit($id){
		$data = $this->template->get_by_id($id);
		$this->main->echoJson($data);
	}

	public function delete($id){
		$a = $this->template->get_by_id($id);
		if($a):
			$active = 0;
			$title 	= $this->lang->line('lb_success');
			if($a->Status != 1):
				$active = 1;
				$title 	= $this->lang->line('lb_success');
			endif;
			$data = array("Status" => $active);
			$this->template->update(array("TemplateID" => $id), $data);
			$status  = true;
			$message = "";
		else:
			$title 	= "";
			$status  = FALSE;
			$message = $this->lang->line('lb_error_data1');
		endif;

		$res = array("status" => $status,"message"=>$message,"title" => $title);
		$this->main->echoJson($res);
	}

	public function cetak($id){
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
		
		$list  		= $this->template->get_by_id($id);

		$modul  	= $this->input->post("modul");
		$url_modul 	= $this->input->post("url_modul");
		$id_url 	= $this->main->id_menu($url_modul);
		$edit 		= $this->main->menu_ubah($id_url);
		$delete 	= $this->main->menu_hapus($id_url);

		$data_action = array();
		if($delete>0):
			if($list->Status == 1):
				$btn_delete = $this->main->button_action("nonactive2",$id);
			else:
				$btn_delete = $this->main->button_action("undelete2",$id);
			endif;
			$data_action['delete'] = $btn_delete;
		endif;
		if($edit>0):
			$btn_edit = $this->main->button_action("edit2",$id);
			$data_action['edit'] = $btn_edit;
		endif;

		$data['page'] 			= $page;
		$data["cetak"]			= $cetak;
		$data['list']			= $list;
		$data['title']  		= 'print template';
		$data['title2'] 		= 'Template';
		$data["company_name"]   = $company_name;
		$data["nama_laporan"]   = $nama_laporan;
    	$data["logo"]           = $logo;
    	$data["company"]		= $datacompany;
    	$data['data_action']	= $data_action;
    	$this->load->view('template/view',$data);

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
}