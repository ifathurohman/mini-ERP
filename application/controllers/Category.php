<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {
	var $title = 'Category Product';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_product",'category');

		$this->main->cek_session();
	}
	public function index()
	{
		$url 						= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url);
		$read 						= $this->main->read($id_url);
		if($read == 0){ redirect(); }
		$category_tambah 				= $this->main->menu_tambah($id_url);
		if($category_tambah > 0):
            $tambah = $this->main->general_button('add','Add New '.$this->title);
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['url'] 			= 'list-category';
		$data['title']  		= $this->title;
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'category/modal';
		$data['page'] 			= 'category/list';
		$data['modul']			= "category";
		$data['url_modul']		= $url;
		$this->load->view('index',$data);
	}
	//---------------------------------------------------------------------------------------
	public function ajax_list()
	{
		$page 	= "category";
		$url 	= $this->uri->segment(1); 
		$id_url = $this->main->id_menu($url);
		$list 	= $this->category->get_datatables("category");
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		foreach ($list as $category) {
			$category_ubah 	= $this->main->menu_ubah($id_url);
			$category_hapus 	= $this->main->menu_hapus($id_url);
			if($category_ubah > 0):
           	$ubah = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="Edit" onclick="edit('."'".$category->productid."'".')">Edit</a>';
			else:
				$ubah = ""; 
			endif;
			if($category_hapus > 0):
           		if($category->active == 1):
           			$hapus = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="Delete" onclick="hapus('."'".$category->productid."'".')">Delete</a>';
           	
           		else:
           			$hapus = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="Undelete data" onclick="active('."'".$category->productid."'".')">Undelete</a>';
           		endif;
			else: 
				$hapus = ""; 
			endif;
			$button  = '<div class="btn-group btn-group-xs" aria-label="Basic example" role="group">';
            $button .= $ubah;
            $button .= $hapus;
            $button .= '</div>';

            $active = "";
            if($category->active == 0):
            	$active = '<i class="icon fa-trash pull-right" aria-hidden="true"></i>';
            endif;

            $code = '<a href="javascript:void(0)" type="button" class="" title="View Detail" onclick="view_category('."'".$category->productid."'".')">'.$category->category_code.'</a>';

			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $code.$active;
			$row[] 	= $category->category_name;
			$row[] 	= $category->level;
			$row[] 	= $category->parent_name;
			// $row[] 	= $button;		
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->category->count_all($page),
			"recordsFiltered" => $this->category->count_filtered($page),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data 	= $this->category->get_by_id($id,"category");
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
		$this->_validate("save");
		$level 			= $this->input->post('level');
		$parent_code 	= $this->input->post('parent_category');
		if($level == 1):
			$parent_code = 0;
		endif;
		$data = array(
			'CompanyID'		=> $this->session->companyid,
			'UserID'		=> $this->session->id_user,
			'Code' 			=> substr($this->input->post('category_code'), 0, 10),
			'Name' 			=> $this->input->post('category_name'),
			'MinimumStock' 	=> $this->input->post('min_qty'),
			'SellingPrice' 	=> $this->input->post('selling_price'),
			'Position' 		=> $level,
			'ParentCode' 	=> $parent_code,
		);
		$insert = $this->category->save($data);
		echo json_encode(array("status" => TRUE,"pesan" => "yoi"));
	}
	public function ajax_update()
	{
		$this->_validate("update");
		$level 			= $this->input->post('level');
		$parent_code 	= $this->input->post('parent_category');
		if($level == 1):
			$parent_code = 0;
		endif;
		$data = array(
			// 'Code' 			=> $this->input->post('category_code'),
			'Name' 			=> $this->input->post('category_name'),
			'Position' 		=> $level,
			'ParentCode' 	=> $parent_code,
		);
		$this->category->update(array('ProductID' => $this->input->post('categoryid')), $data);
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
		$this->category->update(array('ProductID' => $id), $data);
		// $this->category->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}
	private function _validate($page="")
	{
		$CompanyID 				= $this->session->CompanyID;
		$category_code 			= $this->input->post('category_code');
		$cek_category_code		= $this->db->count_all("ps_product where code='$category_code' && position !='0' && CompanyID='$CompanyID'");
		$level 					= $this->input->post("level");
		$parent_category 		= $this->input->post("parent_category");

		$data 					= array();
		$data['error_string'] 	= array();
		$data['inputerror'] 	= array();
		$data['status'] 		= TRUE;


		if($page == "save" && $this->input->post('category_code') == '')
		{
			$data['inputerror'][] 	= 'category_code';
			$data['error_string'][] = 'Please insert category code';
			$data['status'] 		= FALSE;
		}
		if($page == "save" && $cek_category_code > 0)
		{
			$data['inputerror'][] 	= 'category_code';
			$data['error_string'][] = 'Sorry this category code has been already exist';
			$data['status'] 		= FALSE;
		}
		$cek = $this->db->count_all("ps_product where code='$category_code' && position !='0' && active = '0' && CompanyID = '$CompanyID'");
		if($page == "save" && $cek>0){
			$data['inputerror'][] 	= 'category_code';
			$data['error_string'][] = 'Category code has been inactive';
			$data['status'] 		= FALSE;
		}
		if($this->input->post('category_name') == '')
		{
			$data['inputerror'][] 	= 'category_name';
			$data['error_string'][] = 'Please insert category name';
			$data['status'] 		= FALSE;
		}

		if($level && $level != 1 && !$parent_category):
			$data['inputerror'][] 	= 'parent_category';
			$data['error_string'][] = 'Please insert parent category';
			$data['status'] 		= FALSE;
		endif;

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	public function import(){
		// $this->category->import("category");
	}
	public function export(){
		$this->category->export("category");
	}
}
