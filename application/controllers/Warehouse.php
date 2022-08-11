<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Warehouse extends CI_Controller {
	var $title = 'Warehouse';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_warehouse",'warehouse');

		$this->main->cek_session();
	}
	public function index()
	{
		$ID = $this->input->post("ID");

		$url 						= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url);
		$read 						= $this->main->read($id_url);
		if($read == 0){ redirect(); }
		$warehouse_tambah 				= $this->main->menu_tambah($id_url);
		if($warehouse_tambah > 0):
            $tambah = $this->main->general_button('add','Add New '.$this->title);
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['url'] 			= 'list-warehouse';
		$data['title']  		= $this->title;
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'warehouse/modal';
		$data['page'] 			= 'warehouse/list';
		$data['modul']			= "warehouse";
		$data['url_modul']		= $url;
		$data['ID']				= $ID;
		$this->load->view('index',$data);
	}
	//---------------------------------------------------------------------------------------
	public function ajax_list()
	{
		$page 	= "warehouse";
		$url 	= $this->uri->segment(1); 
		$id_url = $this->main->id_menu($url);
		$list 	= $this->warehouse->get_datatables("warehouse");
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		foreach ($list as $warehouse) {
			$warehouse_ubah 	= $this->main->menu_ubah($id_url);
			$warehouse_hapus 	= $this->main->menu_hapus($id_url);
			if($warehouse_ubah > 0):
           	$ubah = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="Edit" onclick="edit('."'".$warehouse->WarehouseID."'".')">Edit</a>';
			else:
				$ubah = ""; 
			endif;
			if($warehouse_hapus > 0):
           		if($warehouse->active == 1):
           			$hapus = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="Delete" onclick="hapus('."'".$warehouse->WarehouseID."'".')">Delete</a>';
           	
           		else:
           			$hapus = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="Undelete data" onclick="active('."'".$warehouse->WarehouseID."'".')">Undelete</a>';
           		endif;
			else: 
				$hapus = ""; 
			endif;
			$button  = '<div class="btn-group btn-group-xs" aria-label="Basic example" role="group">';
            $button .= $ubah;
            $button .= $hapus;
            $button .= '</div>';

            $active = "";
            if($warehouse->active == 0):
            	$active = '<i class="icon fa-trash pull-right" aria-hidden="true"></i>';
            endif;

            $code = '<a href="javascript:void(0)" type="button" class="" title="View Detail" onclick="view_warehouse('."'".$warehouse->WarehouseID."'".')">'.$warehouse->Code.'</a>';

			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $code.$active;
			$row[] 	= $warehouse->Name;
			$row[] 	= $warehouse->Address;
			$row[] 	= $warehouse->Description;
			// $row[] 	= $button;		
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->warehouse->count_all($page),
			"recordsFiltered" => $this->warehouse->count_filtered($page),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data 	= $this->warehouse->get_by_id($id,"warehouse");
		$edit   = $this->main->button_action("edit2",$id);
		$delete = $this->main->button_action("delete4",$id);
		
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
		$TypeCode 			= 1;
		$Code 				= $this->input->post('Code');
		if(!$Code): // ini buat inputannya yg tidak diisi
			$TypeCode 		= 0;
			$Code = $this->main->warehouse_generate(); // auto generate
		endif;
		$this->_validate("save");
		$data = array(
			'CompanyID'		=> $this->session->companyid,
			// 'UserID'		=> $this->session->id_user,
			'Code' 			=> $Code,
			'Name' 			=> $this->input->post('Name'),
			'Address' 		=> $this->input->post('Address'),
			'Description' 	=> $this->input->post('Description'),
			'TypeCode'		=> $TypeCode,
		);
		$WarehouseID = $this->warehouse->save($data);
		$this->warehouse->copy_product($WarehouseID);
		echo json_encode(array("status" => TRUE,"pesan" => "yoi"));
	}
	public function ajax_update()
	{
		$this->_validate("update");
		$data = array(
			// 'Code' 			=> $this->input->post('Code'),
			'Name' 			=> $this->input->post('Name'),
			'Address' 		=> $this->input->post('Address'),
			'Description' 	=> $this->input->post('Description'),
		);
		$this->warehouse->update(array('WarehouseID' => $this->input->post('WarehouseID')), $data);
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
		$this->warehouse->update(array('WarehouseID' => $id), $data);
		// $this->warehouse->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}
	private function _validate($page="")
	{
		$CompanyID 				= $this->session->CompanyID;
		$Code 					= $this->input->post('Code');
		$cek_Code				= $this->db->count_all("PS_Warehouse where Code='$Code' && CompanyID='$CompanyID'");
		$Name 					= $this->input->post("Name");

		$data 					= array();
		$data['error_string'] 	= array();
		$data['inputerror'] 	= array();
		$data['status'] 		= TRUE;

		
		// if($page == "save" && $this->input->post('Code') == '')
		// {
		// 	$data['inputerror'][] 	= 'Code';
		// 	$data['error_string'][] = 'Please insert warehouse code';
		// 	$data['status'] 		= FALSE;
		// }
		if($page == "save" && $cek_Code > 0)
		{
			$data['inputerror'][] 	= 'Code';
			$data['error_string'][] = 'Sorry this warehouse code has been already exist';
			$data['status'] 		= FALSE;
		}
		$cek = $this->db->count_all("PS_Warehouse where Code='$Code' && active = '0' && CompanyID = '$CompanyID'");
		if($page == "save" && $cek>0){
			$data['inputerror'][] 	= 'Code';
			$data['error_string'][] = 'warehouse code has been inactive';
			$data['status'] 		= FALSE;
		}
		if($this->input->post('Name') == '')
		{
			$data['inputerror'][] 	= 'Name';
			$data['error_string'][] = 'Please insert warehouse name';
			$data['status'] 		= FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
}
