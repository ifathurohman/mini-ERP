<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unit extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_unit",'unit');
		$this->main->cek_session();
	}
	public function index()
	{
		$url 						= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url);
		$read 						= $this->main->read($id_url);
		if($read == 0){ redirect(); }
		$unit_tambah 				= $this->main->menu_tambah($id_url);
		if($unit_tambah > 0):
            $tambah = '<button type="button" class="btn btn-primary btn-outline" onclick="tambah()" >Add New Unit</button>';
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['url'] 			= 'list-unit';
		$data['title']  		= 'Unit';
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'unit/modal';
		$data['page'] 			= 'unit/list';
		$this->load->view('index',$data);
	}
	//---------------------------------------------------------------------------------------
	public function ajax_list()
	{
		$page 	= "unit";
		$url 	= $this->uri->segment(1); 
		$id_url = $this->main->id_menu($url);
		$list 	= $this->unit->get_datatables($page);
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		foreach ($list as $unit) {
			$unit_ubah 	= $this->main->menu_ubah($id_url);
			$unit_hapus 	= $this->main->menu_hapus($id_url);
			if($unit_ubah > 0):
           	$ubah = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="Edit" onclick="edit('."'".$unit->unitid."'".')">edit</a>';
			else:
				$ubah = ""; 
			endif;
			if($unit_hapus > 0):
           		if($unit->active == 1):
           			$hapus = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="delete Data" onclick="hapus('."'".$unit->unitid."'".')">delete</a>';
           	
           		else:
           			$hapus = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="undelete Data" onclick="active('."'".$unit->unitid."'".')">undelete</a>';
           		endif;
			else: 
				$hapus = ""; 
			endif;
			$button  = '<div class="btn-group btn-group-xs" aria-label="Basic example" role="group">';
            $button .= $ubah;
            $button .= $hapus;
            $button .= '</div>';

            $active = "";
            if($unit->active == 0):
            	$active = '<i class="icon fa-trash pull-right" aria-hidden="true"></i>';
            endif;

            $code = '<a href="javascript:void(0)" type="button" class="" title="View Detail" onclick="view_unit('."'".$unit->unitid."'".')">'.$unit->name.'</a>';

			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $code.$active;
			$row[] 	= $unit->conversion;
			$row[] 	= $unit->type;
			$row[] 	= $unit->remark;
			$row[] 	= $button;		
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->unit->count_all($page),
			"recordsFiltered" => $this->unit->count_filtered($page),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$this->db->select("unitid,name,conversion,type,remark");
		$data 		 = $this->unit->get_by_id($id,"unit");
		$edit 		 = $this->main->button_action("edit2",$id);
		$delete 	 = $this->main->button_action("delete4",$id);
		if($data->active == 0):
			$delete 	 = $this->main->button_action("undelete2",$id);
		endif;
		if($data->active == 0):
			$edit 	 	 = '';
		endif;
		$output = array(
			"data" 		  => $data,
			"edit" 	 	  => $edit,
			"delete" 	  => $delete,
		);
		echo json_encode($output);
	}

	public function simpan()
	{
		$this->_validate("save");
		$data = array(
			'CompanyID'	=> $this->session->CompanyID,
			'parentid' 	=> 0,
			'position' 	=> 0,
			'active' 	=> 1,
			'position' 	=> 0,
			'name' 		=> $this->input->post('unit_name'),
			'conversion'=> $this->input->post('conversion'),
			'type'		=> $this->input->post('type'),
			'remark'	=> $this->input->post('remark'),
		);
		$insert = $this->unit->save($data);
		
		$res = array(
			'status' 	=> true,
			'message' 	=> 'success',
		);
		$this->main->echoJson($res);
	}
	public function ajax_update()
	{
		$this->_validate("update");
		$data = array(
			'name' 		=> $this->input->post('unit_name'),
			'conversion'=> $this->input->post('conversion'),
			'type'		=> $this->input->post('type'),
			'remark'		=> $this->input->post('remark'),
		);
		$this->unit->update(array('unitid' => $this->input->post('unitid')), $data);
		echo json_encode(array("status" => TRUE,"pesan" => $this->input->post("status")));
	}
	public function ajax_delete($id,$status = "")
	{
		$active = 0;
		if($status == "active"):
			$active = 1;
		endif;
		$data = array(
			"Active" => $active,
		);
		$this->unit->update(array('unitid' => $id), $data);
		// $this->category->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}
	private function _validate($page = "")
	{
		$CompanyID 		= $this->session->CompanyID;
		$unit_name 		= $this->input->post('unit_name');

		$data 					= array();
		$data['error_string'] 	= array();
		$data['inputerror'] 	= array();
		$data['status'] 		= TRUE;
		

		if($page == "save" && !$unit_name){
			$data['inputerror'][] 	= 'unit_name';
			$data['error_string'][] = 'Unit name cannot be null';
			$data['status'] 		= FALSE;
		}
		$cek = $this->db->count_all("ps_unit where name = '$unit_name' and CompanyID = '$CompanyID' and active = '1'");
		if($page == "save" && $cek>0){
			$data['inputerror'][] 	= 'unit_name';
			$data['error_string'][] = 'Unit name has been already exist';
			$data['status'] 		= FALSE;
		}
		$cek_aktif = $this->db->count_all("ps_unit where name = '$unit_name' and CompanyID = '$CompanyID' and active = '0'");
		if($cek_aktif>0){
			$data['inputerror'][] 	= 'unit_name';
			$data['error_string'][] = 'Unit name has been inactive';
			$data['status'] 		= FALSE;
		}
		
		// $cek_aktif = $this->db->count_all("ps_unit where name = '$name' and active = '1'");
		// if(!$cek_aktif == 1){
		// 	$data['inputerror'][] 	= 'unit_name';
		// 	$data['error_string'][] = 'Unit name has been inactive';
		// 	$data['status'] 		= FALSE;
		// }
		
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	#2018-02-08
	public function import(){
		$this->unit->import();
	}
	public function export(){
		$this->unit->export();
	}
}
