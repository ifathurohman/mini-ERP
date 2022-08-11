<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_service extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_product_service",'product_service');
		$this->main->cek_session();
	}
	public function index()
	{
		$url 						= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url);
		$read 						= $this->main->read($id_url);
		if($read == 0){ redirect(); }
		$product_tambah 				= $this->main->menu_tambah($id_url);
		if($product_tambah > 0):
            $tambah = '<button type="button" class="btn btn-primary btn-outline" onclick="tambah()" >Add New Product</button>';
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['url'] 			= 'list-product_service';
		$data['title']  		= 'Product';
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'product_service/modal';
		$data['page'] 			= 'product_service/list';
		$this->load->view('index',$data);
	}
	//---------------------------------------------------------------------------------------
	public function ajax_list()
	{
		$CompanyID  = $this->session->CompanyID;
		$page 		= "product-service";
		$url 		= $this->uri->segment(1); 
		$id_url 	= $this->main->id_menu($url);
		$list 		= $this->product_service->get_datatables($page);
		$data 		= array();
		$no 		= $_POST['start'];
		$i 			= 1;
		foreach ($list as $product_service) {
			$product_ubah 	= $this->main->menu_ubah($id_url);
			$product_hapus 	= $this->main->menu_hapus($id_url);
			$link = "attachment/".$product_service->productid."?type=product";
			// $view = '<a href="javascript:void(0)" type="button" class="" title="View Detail" onclick="view('."'".$product_service->productid."'".')">detail</a>';
			// $view_serial = '<a href="javascript:void(0)" type="button" class="" title="View Serial Number" onclick="view_serial('."'".$product_service->productid."'".')">serial number</a>';
			if($product_ubah > 0):
           	$ubah = '<a href="javascript:void(0)" type="button" class="" title="Edit Data" onclick="edit('."'".$product_service->productid."'".')">edit</a>';
			else:
				$ubah = ""; 
			endif;
			if($product_hapus > 0):
           		if($product_service->active == 1):
           			$hapus = '<a href="javascript:void(0)" type="button" class="" title="Delete Data" onclick="hapus('."'".$product_service->productid."'".')">delete</a>';
           	
           		else:
           			$hapus = '<a href="javascript:void(0)" type="button" class="" title="Undelete Data" onclick="active('."'".$product_service->productid."'".')">undelete</a>';
           		endif;
			else: 
				$hapus = ""; 
			endif;
			$tag  = 'data-selling_price ="'.$product_service->sellingprice.'" ';
			$tag .= 'data-purchase_price ="'.$product_service->PurchasePrice.'" ';
			$btn_vendor_price 	= '<a href="javascript:void(0)" '.$tag.' type="button" class="vvd-'.$product_service->productid.'" title="Vendor Price" onclick="modal_vendor_price('."'customer','".$product_service->productid."'".')">Customer Price</a>';
			$btn_attachemnt  	= $this->main->button_attach_dropdown($product_service->productid,$link,"product");

			$button  = '<div class="btn-group btn-group-xs">';
			$button	.= '<a type="button" class="btn btn-outline btn-default dropdown-toggle" id="exampleGroupDrop2" data-toggle="dropdown" aria-expanded="false"><i class="icon wb-settings" aria-hidden="true"></i></a>';
            $button	.= '<ul class="dropdown-menu" aria-labelledby="exampleGroupDrop2" role="menu">';
            // $button .= '<li role="presentation">'.$view.'</li>';
            // $button .= '<li role="presentation">'.$view_serial.'</li>';
            $button .= '<li role="presentation">'.$ubah.'</li>';
            $button .= '<li role="presentation">'.$hapus.'</li>';
            $button .= '<li role="presentation">'.$btn_vendor_price.'</li>';
            $button .= '<li role="presentation">'.$btn_attachemnt.'</li>';
            $button .= '</ul>';
            $button .= '</div>';

            $active = "";
            if($product_service->active == 0):
            	$active = '<i class="icon fa-trash pull-right" aria-hidden="true"></i>';
            endif;

           	$Image = '';
           	if($product_service->Image):
           		$Image = '<img src="'.base_url($product_service->Image).'" style="width:120px"/>';
           	else:
           		$Image = '<img src="'.base_url('/aset/images/noimage.jpg').'" style="width:120px"/>';
           	endif;

			$no++;
			$id_produk  = $product_service->productid;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $Image;
			$row[] 	= $product_service->product_code.$active;
			$row[] 	= $product_service->product_name;
			$row[] 	= $product_service->category_name;
			$row[] 	= $this->main->currency($product_service->sellingprice);
			$row[] 	= $button;		
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->product_service->count_all($page),
			"recordsFiltered" => $this->product_service->count_filtered($page),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->product_service->get_by_id($id,"product-service");
		echo json_encode($data);
	}

	public function simpan()
	{
		$this->_validate("save");
		$crud  			= $this->input->post("crud");
		$CompanyID 		= $this->session->CompanyID;
		$type 			= $this->input->post('product_type');
		$serial_format 	= $this->input->post("serial_format");
		$product_code 	= $this->input->post('product_code');
		$TypeCode 		= 1;

		if($type == "unique"): $type = 1; elseif($type == "serial"): $type = 2; else: $type = 0; endif;
		if(!$product_code): // ini buat inputannya yg tidak diisi
			$TypeCode 		= 0;
			$product_code   = $this->product_service->generate_product_code($this->input->post('product_category')); // auto generate
		endif;
		$data = array(
			'Position' 		=> 0,
			'UserID'		=> $this->session->id_user,
			'CompanyID'		=> $this->session->CompanyID,
			'Code' 			=> $product_code,
			'Type' 			=> $type,
			'Name' 			=> $this->input->post('product_name'),
			'SellingPrice'	=> str_replace(",", "", $this->input->post('selling_price')),
			'ParentCode' 	=> $this->input->post('product_category'),
			'Active'		=> 1,
			'Status'		=> 1,
			"TypeCode"		=> $TypeCode,
		);
		if($type == 2):
			if($serial_format == ""): $serial_format = "auto"; endif;
			$data["SNFormat"] = $serial_format;
		else:
			$data["SNFormat"] = "";
		endif;

		$insert = $this->product_service->save($data);
		$ProductID = $insert;
		$this->product_service->copy_branch($ProductID);

		$res = array(
			'status' 	=> true,
			'message' 	=> 'success',
		);
		$this->main->echoJson($res);
	}
	public function ajax_update()
	{
		$this->_validate("update");
		$type = $this->input->post('product_type');
		$serial_format 	= $this->input->post("serial_format");
		$TypeCode 		= 1;
		$product_code 	= $this->input->post('product_code');
		
		if($type == "unique"): $type = 1; elseif($type == "serial"): $type = 2; else: $type = 0; endif;
		if($type == 2):
			if($serial_format == ""): $serial_format = "auto"; endif;
			$data["SNFormat"] = $serial_format;
		else:
			$data["SNFormat"] = "";
		endif;
		$data = array(
			// 'Type' 			=> $type,
			'Name' 			=> $this->input->post('product_name'),
			'SellingPrice'	=> str_replace(",", "", $this->input->post('selling_price')),
			'ParentCode' 	=> $this->input->post('product_category'),
		);
		if(!$product_code): // ini buat inputannya yg tidak diisi
			$TypeCode 		= 0;
			$product_code   = $this->product_service->generate_product_code($this->input->post('product_category')); // auto generate
		endif;
		$this->product_service->update(array('ProductID' => $this->input->post('productid')), $data);
		echo json_encode(array("status" => TRUE,"pesan" => $this->input->post("status")));
	}
	public function ajax_delete($id,$status="")
	{
		// $this->product->delete_by_id($id);
		$active = 0;
		if($status == "active"):
			$active = 1;
		endif;
		$data = array(
			"Active" => $active,
		);
		$this->product_service->update(array('ProductID' => $id), $data);
		echo json_encode(array("status" => TRUE));
	}
	private function _validate($page = "")
	{
		$CompanyID 		= $this->session->CompanyID;
		$product_code 	= $this->input->post('product_code');
		$product_name 	= $this->input->post('product_name');
		$cek_code		= $this->db->count_all("ps_product where code='$product_code' && position ='0' && CompanyID='$CompanyID'");

		$data 					= array();
		$data['error_string'] 	= array();
		$data['inputerror'] 	= array();
		$data['status'] 		= TRUE;
		
		if($page == "save" && $cek_code > 0)
		{
			$data['inputerror'][] 	= 'product_code';
			$data['error_string'][] = 'Sorry this product code has been already exist';
			$data['status'] 		= FALSE;
		}
		if($this->input->post('product_name') == '')
		{
			$data['inputerror'][] 	= 'product_name';
			$data['error_string'][] = 'Product name cannot be null';
			$data['status'] 		= FALSE;
		}
		$cek = $this->db->count_all("ps_product where name = '$product_name' and CompanyID = '$CompanyID' and position ='0' and active = '1'");
		if($cek>0):
			$data['inputerror'][] 	= 'product_name';
			$data['error_string'][] = 'Product name has been already exist';
			$data['status'] 		= FALSE;
		endif;
		$cek_aktif = $this->db->count_all("ps_product where name = '$product_name' and active = '1'");
		if(!$cek_aktif=1):
			$data['inputerror'][] 	= 'product_name';
			$data['error_string'][] = 'Product name has been inactive';
			$data['status'] 		= FALSE;
		endif;
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	public function import(){
		$this->product_service->import("product-service");
	}
	public function export(){
		$this->product_service->export("product-service");
	}
	public function view($productid="")
	{
		$a = $this->product_service->get_by_id($productid,"product");
		$product_name = $a->product_name;

		$this->db->select("
			ppb.ProductID as productid,
			ppb.BranchID as branchid,
			ppb.Qty as qty,
			b.Name as name,
			b.Address as address,
			b.City as city,
		");
		$this->db->join("Branch as b","ppb.BranchID = b.BranchID");
		$this->db->where("ppb.CompanyID",$this->session->CompanyID);
		$this->db->where("ppb.ProductID",$productid);
		$this->db->where("b.App", $this->session->app);
		$query 		= $this->db->get("PS_Product_Branch as ppb");
		$data		= $query->result();
		$list_data 	= array();
		foreach($data as $a):
			$item = array(
				"productid" => $a->productid,
				"qty" 		=> $this->main->qty($a->qty),
				"branchid" 	=> $a->branchid,
				"name" 		=> $a->name,
				"address"	=> $a->address,
				"city" 		=> $a->city,
			);
			array_push($list_data,$item);
		endforeach;
		$output 	= array(
			"status" 		=> TRUE,
			"message" 		=> "",
			"hakakses" 		=> $this->session->hak_akses,
			"product_name" 	=> $product_name,
			"list_data" 	=> $list_data
		);

		header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
	}
	public function get_unit($search)
	{
		$this->db->select("UnitID as unitid");
		$this->db->where("CompanyID",$this->session->CompanyID);
		$this->db->like("Name",$search);
		$query = $this->db->get("ps_unit");
		return $query->row();
	}
	public function unit($search){
		$a = $this->get_unit($search)->unitid;
		echo "<pre>";
		print_r($a);
	}
	 protected function processRowData(\PHPExcel_Worksheet_Row $row){
     $cellIterator = $row->getCellIterator();
     $cellIterator->setIterateOnlyExistingCells(false);
     // This loops all cells,
     $rowIndex = $row->getRowIndex();
     $rowData = [];
     foreach ($cellIterator as $cell) {
         $cellData = $this->processCellData($cell);
         $rowData[$cellData['column']] = $cellData['value'];
     }
     return ['rowIndex' => $rowIndex, 'data' => $rowData];
 	}
}
