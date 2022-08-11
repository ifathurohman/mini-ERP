<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {
	var $title = 'Item Product';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_product",'product');
		$this->main->cek_session();
		$this->title = $this->lang->line("lb_product_item");
	}
	public function index()
	{
		$CompanyID 					= $this->session->CompanyID;
		$url 						= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url);
		$read 						= $this->main->read($id_url);
		if($read == 0){ redirect(); }
		$product_tambah 				= $this->main->menu_tambah($id_url);
		if($product_tambah > 0):
            $tambah = $this->main->general_button('add',$this->lang->line('lb_add_new').' '.$this->title);
		else: 
			$tambah = ""; 
		endif;
		$ck_category = $this->db->count_all("ps_product where CompanyID = '$CompanyID' and Position != 0 and Active = 1");
		#ini untuk session halaman aturan user privileges;
		$data['url'] 			= 'list-product';
		$data['title']  		= $this->title;
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'product/modal';
		$data['page'] 			= 'product/list';
		$data['modul'] 			= 'product/edit';
		$data['category']		= $ck_category;
		$data['url_modul'] 		= $url;
		$this->load->view('index',$data);
	}
	//---------------------------------------------------------------------------------------
	public function ajax_list()
	{
		$CompanyID  = $this->session->CompanyID;
		$page 		= "product";
		$url 		= $this->uri->segment(1);
		$id_url 	= $this->main->id_menu($url);
		$list 		= $this->product->get_datatables($page);
		$data 		= array();
		$no 		= $_POST['start'];
		$i 			= 1;
		foreach ($list as $product) {
			$product_ubah 	= $this->main->menu_ubah($id_url);
			$product_hapus 	= $this->main->menu_hapus($id_url);
			$label_type 	= $this->product->label_product_type($product->type_product);
			$label_type1 	= $this->product->label_sales_type($product->type_sales);
			$link = "attachment/".$product->productid."?type=product";
			$view = '<a href="javascript:void(0)" type="button" class="" title="View Detail" onclick="view('."'".$product->productid."'".')">detail</a>';
			$view_serial = '<a href="javascript:void(0)" type="button" class="" title="View Serial Number" onclick="view_serial('."'".$product->productid."'".')">serial number</a>';
			if($product_ubah > 0):
           	$ubah = '<a href="javascript:void(0)" type="button" class="" title="Edit Data" onclick="edit('."'".$product->productid."'".')">edit</a>';
			else:
				$ubah = ""; 
			endif;
			if($product_hapus > 0):
           		if($product->active == 1):
           			$hapus = '<a href="javascript:void(0)" type="button" class="" title="Delete Data" onclick="hapus('."'".$product->productid."'".')">delete</a>';
           		else:
           			$hapus = '<a href="javascript:void(0)" type="button" class="" title="Undelete Data" onclick="active('."'".$product->productid."'".')">undelete</a>';
           		endif;
			else: 
				$hapus = ""; 
			endif;

			$tag  = 'data-selling_price ="'.$product->sellingprice.'" ';
			$tag .= 'data-purchase_price ="'.$product->PurchasePrice.'" ';
			$btn_vendor_price 	= '<a href="javascript:void(0)" '.$tag.' type="button" class="vvd-'.$product->productid.'" title="Vendor Price" onclick="modal_vendor_price('."'customer','".$product->productid."'".')">Customer Price</a>';
			$btn_attachemnt  	= $this->main->button_attach_dropdown($product->productid,$link,"product");

			$button  = '<div class="btn-group btn-group-xs">';
			$button	.= '<a type="button" class="btn btn-outline btn-default dropdown-toggle" id="exampleGroupDrop2" data-toggle="dropdown" aria-expanded="false"><i class="icon wb-settings" aria-hidden="true"></i></a>';
            $button	.= '<ul class="dropdown-menu" aria-labelledby="exampleGroupDrop2" role="menu">';
            $button .= '<li role="presentation">'.$view.'</li>';
            $button .= '<li role="presentation">'.$view_serial.'</li>';
            $button .= '<li role="presentation">'.$ubah.'</li>';
            $button .= '<li role="presentation">'.$hapus.'</li>';
            $button .= '<li role="presentation">'.$btn_vendor_price.'</li>';
            $button .= '<li role="presentation">'.$btn_attachemnt.'</li>';
            $button .= '</ul>';
            $button .= '</div>';

            $active = "";
            if($product->active == 0):
            	$active = '<i class="icon fa-trash pull-right" aria-hidden="true"></i>';
            endif;

            $Image = '';
           	if($product->Image):
           		$Image = '<img src="'.base_url($product->Image).'" style="width:120px;height:120px;object-fit:cover;background-position: center;"/>';
           	else:
           		$Image = '<img src="'.base_url('/aset/images/noimage.jpg').'" style="width:120px;height:120px;object-fit:cover;background-position: center;"/>';
           	endif;

           	$code = '<a href="javascript:void(0)" type="button" class="" title="View Detail" onclick="view_product('."'".$product->productid."'".')">'.$product->product_code.'</a>';

           	$average = '<a href="javascript:;" type="button" title="'.$this->lang->line('lb_average_price').'" onclick="average_store('.$product->productid.')">'.$this->lang->line('lb_average_view').'</a>';

			$no++;
			$id_produk  = $product->productid;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $Image;
			$row[] 	= $code.$active;
			$row[] 	= $product->product_name;
			$row[] 	= $product->category_name;
			$row[] 	= $this->main->qty($product->min_qty);
			$row[] 	= $this->main->qty($product->qty);
			$row[] 	= $product->unit_name;
			// $row[] 	= $product->conversion;
			// $row[] 	= $this->main->currency($product->PurchasePrice);
			$row[] 	= $this->main->currency($product->sellingprice);
			// $row[] 	= $average;
			$row[] 	= $label_type;
			$row[] 	= $label_type1;
			// $row[] 	= $button;		
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->product->count_all($page),
			"recordsFiltered" => $this->product->count_filtered($page),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$CompanyID 	 = $this->session->CompanyID;
		$data 		 = $this->product->get_by_id($id,"product");
		$edit 		 = $this->main->button_action("edit2",$id);
		$view 		 = $this->main->button_action("view2",$id);
		$delete 	 = $this->main->button_action("delete4",$id);
		if($data->Active == 0):
			$delete 	 = $this->main->button_action("undelete2",$id);
		endif;
		if($data->Active == 0):
			$edit 	 	 = '';
		endif;

		$view_serial 	= $this->main->button_action("view_serial",$id);
		$product_branch = $this->main->button_action("product_branch",$id);
		$customer_price = $this->main->button_action("customer_price",$id);

		$Image = $this->main->get_one_column("PS_Attachment","Image", array("CompanyID" => "$CompanyID", "Cek" => "1","ID" => $id,"Type"	=> "product"));
		$ImageUrl = base_url('/aset/images/noimage.jpg');
		if($Image):
			if(file_exists('./' . $Image->Image)):
				$ImageUrl = site_url($Image->Image);
			endif;
		endif;

		$output = array(
			"data" 		  	 => $data,
			// "attachment"  => site_url('attachment/'.$id.'?type=product'),
			"edit" 	 	  	 => $edit,
			"view" 	 	  	 => $view,
			"delete" 	  	 => $delete,
			"view_serial" 	 => $view_serial,
			"customer_price" => $customer_price,
			"Image"			 => $ImageUrl,
			"hakakses"		 => $this->session->hak_akses,
		);
		echo json_encode($output);
	}

	public function simpan()
	{
		$this->_validate("save");
		$crud  			= $this->input->post("crud");
		$CompanyID 		= $this->session->CompanyID;
		$CostMethod		= $this->session->CostMethod;
		$type 			= $this->input->post('product_type');
		$serial_format 	= $this->input->post("serial_format");
		$product_code 	= $this->input->post('product_code');
		$inventory 		= $this->input->post("inventory");
		$unit 			= $this->input->post("unit");
		$sales			= $this->input->post("sales"); 
		$serial_auto 	= $this->input->post("serial_auto");
		$TypeCode 		= 1;
		$SNFormat 		= "";
		$xserial_auto 	= null;

		if($type == "unique"): $type = 1; elseif($type == "serial"): $type = 2; else: $type = 0; endif;
		
		if($type == 2):
			if($serial_format == ""): $serial_format = "auto"; endif;
			$SNFormat = $serial_format;
			if(!$serial_auto):
				$SNFormat = null;
				$xserial_auto = 0;
			else:
				$xserial_auto = 1;
			endif;
		else:
			$SNFormat = null;
		endif;
		
		if(!$product_code): // ini buat inputannya yg tidak diisi
			$TypeCode 		= 0;
			$product_code   = $this->product->generate_product_code($this->input->post('product_category')); // auto generate
		endif;

		$data = array(
			'Position' 		=> 0,
			'UserID'		=> $this->session->id_user,
			'CompanyID'		=> $this->session->CompanyID,
			'CostMethod'	=> $this->session->CostMethod,
			'Code' 			=> $product_code,
			'Type' 			=> $type,
			'Name' 			=> $this->input->post('product_name'),
			'Qty' 			=> 0,
			'ParentCode' 	=> $this->input->post('product_category'),
			'SellingPrice'	=> str_replace(",", "", $this->input->post('selling_price')),
			'PurchasePrice'	=> str_replace(",", "", $this->input->post('purchase_price')),
			'Active'		=> 1,
			'Status'		=> 0,
			"ProductType"	=> $inventory,
			"SalesType"		=> $sales,
			"TypeCode"		=> $TypeCode,
			"SNFormat"		=> $SNFormat,
			"SNAuto"		=> $xserial_auto,
		);

		if($inventory == "inventory"):
			$data['MinimumStock']	= str_replace(",", "", $this->input->post('min_qty'));
			$data['ProductType'] 	= 'item';
			$data['Uom']			= $unit;

			# 20190830 MW
			# karena ps_unit sudah tidak digunakan karena unit memakai ps_product_unit
			// $cek_unit 	= $this->db->count_all("ps_unit where Name = '$unit' and CompanyID = '$CompanyID'");
			// $UnitID 	= 0;
			// if($cek_unit<=0): // ini kondisi untuk insert baru
			// 	$data_unit 	= array(
			// 		'CompanyID'		=> $this->session->CompanyID,
			// 		'parentid' 		=> 0,
			// 		'position' 		=> 0,
			// 		'active' 		=> 1,
			// 		'position' 		=> 0,
			// 		'name' 			=> $unit,
			// 		"conversion"	=> 1,
			// 	);
			// 	$this->db->set("user_add",$this->session->userdata("NAMA"));
			// 	$this->db->set("date_add",date("Y-m-d H:i:s"));
			// 	$this->db->insert("ps_unit", $data_unit);
			// 	$UnitID =  $this->db->insert_id();
			// else:
			// 	$UnitID = $this->main->get_one_column("ps_unit","UnitID", array("Name"  => $unit,"CompanyID" => $CompanyID))->UnitID;
			// endif;
			// $data['UnitID'] = $UnitID;

		else:
			$data['ProductType'] 	= 'service';

			# 20190830 MW
			# karena ps_unit sudah tidak digunakan karena unit memakai ps_product_unit
			// $cek_unit 	= $this->db->count_all("ps_unit where Name = '$unit' and CompanyID = '$CompanyID'");
			// $UnitID 	= 0;
			// if($cek_unit<=0): // ini kondisi untuk insert baru
			// 	$data_unit 	= array(
			// 		'CompanyID'		=> $this->session->CompanyID,
			// 		'parentid' 		=> 0,
			// 		'position' 		=> 0,
			// 		'active' 		=> 1,
			// 		'position' 		=> 0,
			// 		'name' 			=> $unit,
			// 		"conversion"	=> 1,
			// 	);
			// 	$this->db->set("user_add",$this->session->userdata("NAMA"));
			// 	$this->db->set("date_add",date("Y-m-d H:i:s"));
			// 	$this->db->insert("ps_unit", $data_unit);
			// 	$UnitID =  $this->db->insert_id();
			// else:
			// 	$UnitID = $this->main->get_one_column("ps_unit","UnitID", array("Name"  => $unit,"CompanyID" => $CompanyID))->UnitID;
			// endif;
			// $data['UnitID'] = $UnitID;

		endif;

		$insert 	= $this->product->save($data);
		$ProductID  = $insert;
		$this->save_image($ProductID);
		$this->product->copy_branch($ProductID);
		if($inventory == "inventory"):
			$this->save_uom($ProductID,$unit);
		endif;
		$res = array(
			'status' 	=> true,
			'message' 	=> $this->lang->line('lb_success'),
		);
		$this->main->echoJson($res);

	}

	private function save_uom($ProductID,$Uom,$method=""){
		$CompanyID = $this->session->CompanyID;
		$data = array(
			"CompanyID"		=> $this->session->CompanyID,
			"ProductID"		=> $ProductID,
			"Uom"			=> strtoupper($Uom),
			"Uom2"			=> strtoupper($Uom),
			"Conversion"	=> 1,
			"Active"		=> 1,
		);
		$cek = $this->db->count_all("ps_product_unit where CompanyID = '$CompanyID' and ProductID = '$ProductID'");
		if($method == "update" && $cek>0):
			#catatan : jika kedepannya akan ada 1 product banyak unit mohon tambahkan ke where oum2 sebelumnya pada kondisi update
			$where  = array(
				"ProductID"	=> $ProductID,
				"CompanyID"	=> $this->session->CompanyID,
			);
			$this->product->update_uom($where,$data);
		else:
			$this->product->save_uom($data);
		endif;
	}


	public function ajax_update()
	{
		$this->_validate("update");
		$CompanyID 		= $this->session->CompanyID;
		$product_code 	= $this->input->post('product_code');
		$type 			= $this->input->post('product_type');
		$serial_format 	= $this->input->post("serial_format");
		$TypeCode 		= 1;
		$unit 			= $this->input->post("unit");
		$inventory 		= $this->input->post("inventory");
		$ProductID 		= $this->input->post('productid');

		if($type == "unique"): $type = 1; elseif($type == "serial"): $type = 2; else: $type = 0; endif;
		if($type == 2):
			if($serial_format == ""): $serial_format = "auto"; endif;
			$data["SNFormat"] = $serial_format;
		else:
			$data["SNFormat"] = "";
		endif;
		
		if(!$product_code): // ini buat inputannya yg tidak diisi
			$product_code   = $this->product->generate_product_code($this->input->post('product_category')); // auto generate
			$TypeCode 		= 0;
		endif;

		$data = array(
			// 'Code' 			=> $product_code,
			// 'Type' 			=> $type,
			'Name' 			=> $this->input->post('product_name'),
			'MinimumStock'	=> str_replace(",", "", $this->input->post('min_qty')),
			'SellingPrice'	=> str_replace(",", "", $this->input->post('selling_price')),
			'PurchasePrice'	=> str_replace(",", "", $this->input->post('purchase_price')),
			'ParentCode' 	=> $this->input->post('product_category'),
		);

		if($inventory == "inventory"):
			$data['Uom']	= strtoupper($unit);
			# 20190830 MW
			# karena ps_unit sudah tidak digunakan karena unit memakai ps_product_unit
			// $cek_unit 	= $this->db->count_all("ps_unit where Name = '$unit' and CompanyID = '$CompanyID'");
			// $UnitID 	= 0;
			// if($cek_unit<=0): // ini kondisi untuk insert baru
			// 	$data_unit 	= array(
			// 		'CompanyID'	 => $this->session->CompanyID,
			// 		'parentid' 	 => 0,
			// 		'position' 	 => 0,
			// 		'active' 	 => 1,
			// 		'position' 	 => 0,
			// 		'name' 		 => strtoupper($unit),
			// 		"conversion" => 1,
			// 	);
			// 	$this->db->set("user_add",$this->session->userdata("NAMA"));
			// 	$this->db->set("date_add",date("Y-m-d H:i:s"));
			// 	$this->db->insert("ps_unit", $data_unit);
			// 	$UnitID =  $this->db->insert_id();
			// else:
			// 	$UnitID = $this->main->get_one_column("ps_unit","UnitID", array("Name"  => $unit,"CompanyID" => 1))->UnitID;
			// endif;
			// $data['UnitID'] = $UnitID;
			// $data['Uom']	= $unit;
			$data['ProductType'] = "item";
		else:
			$data['Uom']	= null;
			$data['ProductType'] = "service";

			# 20190830 MW
			# karena ps_unit sudah tidak digunakan karena unit memakai ps_product_unit
			// $cek_unit 	= $this->db->count_all("ps_unit where Name = '$unit' and CompanyID = '$CompanyID'");
			// $UnitID 	= 0;
			// 	if($cek_unit<=0): // ini kondisi untuk insert baru
			// 		$data_unit 	= array(
			// 			'CompanyID'	 => $this->session->CompanyID,
			// 			'parentid' 	 => 0,
			// 			'position' 	 => 0,
			// 			'active' 	 => 1,
			// 			'position' 	 => 0,
			// 			'name' 		 => strtoupper($unit),
			// 			"conversion" => 1,
			// 		);
			// 		$this->db->set("user_add",$this->session->userdata("NAMA"));
			// 		$this->db->set("date_add",date("Y-m-d H:i:s"));
			// 		$this->db->insert("ps_unit", $data_unit);
			// 		$UnitID =  $this->db->insert_id();
			// 	else:
			// 		$UnitID = $this->main->get_one_column("ps_unit","UnitID", array("Name"  => $unit,"CompanyID" => 1))->UnitID;
			// 	endif;
			// $data['UnitID'] = $UnitID;
		endif;

		$this->product->update(array('ProductID' => $this->input->post('productid')), $data);
		if($inventory == "inventory"):
			$this->save_uom($ProductID,$unit,"update");
		endif;
		$this->save_image($ProductID,"update");

		$res = array(
			'status' 	=> true,
			'message' 	=> 'success',
		);
		$this->main->echoJson($res);
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
		$this->product->update(array('ProductID' => $id), $data);
		echo json_encode(array("status" => TRUE));
	}
	private function _validate($page = "")
	{
		// $data['inputerror'][] 	= "";
		// $data['error_string'][] = '';
		// $data['list'][] 		= '';
		// $data['tab'][] 			= '';
		// $data['ara'] 			= $this->input->post();
		// $data['status'] 		= FALSE;

		// $this->main->echoJson($data);
		// exit();

		$CompanyID 		= $this->session->CompanyID;
		$product_code 	= $this->input->post('product_code');
		$product_name 	= $this->input->post('product_name');
		$cek_code		= $this->db->count_all("ps_product where code='$product_code' && position ='0' && CompanyID='$CompanyID'");
		$inventory 		= $this->input->post("inventory");
		$unit 			= $this->input->post("unit");

		$data 					= array();
		$data['error_string'] 	= array();
		$data['inputerror'] 	= array();
		$data['status'] 		= TRUE;

		// if($page == "save" && $this->input->post('product_code') == '')
		// {
		// 	$data['inputerror'][] 	= 'product_code';
		// 	$data['error_string'][] = 'Product code cannot be null';
		// 	$data['status'] 		= FALSE;
		// }
		if($page == "save" && $cek_code > 0)
		{
			$data['inputerror'][] 	= 'product_code';
			$data['error_string'][] = $this->lang->line('lb_product_code_exist');
			$data['status'] 		= FALSE;
		}
		if($this->input->post('product_name') == '')
		{
			$data['inputerror'][] 	= 'product_name';
			$data['error_string'][] = $this->lang->line('lb_product_name_empty');
			$data['status'] 		= FALSE;
		}
		$cek = $this->db->count_all("ps_product where code = '$product_code' and CompanyID = '$CompanyID' and position ='0' and active = '1'");
		if($page == "save" && $cek>0){
			$data['inputerror'][] 	= 'product_code';
			$data['error_string'][] = $this->lang->line('lb_product_code_exist');
			$data['status'] 		= FALSE;
		}
		if($this->input->post('product_category') == '')
		{
			$data['inputerror'][] 	= 'product_category';
			$data['error_string'][] = $this->lang->line('lb_category_empty');
			$data['status'] 		= FALSE;
		}
		if($inventory == "inventory"):
			if(!$unit):
				$data['inputerror'][] 	= 'unit';
			$data['error_string'][] = $this->lang->line('lb_unit_empty');
			$data['status'] 		= FALSE;
			endif;
		endif;
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	public function import(){
		// $this->product->import("product");
		$this->import_product();
	}

	private function import_product(){
		$fileName                 = $this->input->post('file', TRUE);
		$CompanyID 				  = $this->session->CompanyID;
		$userid 				  = $this->session->id_user;
		$folder 				  = $this->main->create_folder_product();
		$config['upload_path']    = './'.$folder; 
		$config['file_name']      = $fileName;
		$config['allowed_types']  = 'xls|xlsx|csv|ods|ots';
		$config['max_size']       = 10000;
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if (!$this->upload->do_upload('file')):
			$output = array(
				"status" 	=> false,
				"message"	=> $this->lang->line('lb_excell_empty'),
				"hak_akses"	=> $this->session->hak_akses,
			);
		else:
			$media 			= $this->upload->data();
			$inputFileName 	= $folder.$media['file_name'];
			try {
				$inputFileType  = IOFactory::identify($inputFileName);
				$objReader      = IOFactory::createReader($inputFileType);
				$objPHPExcel  	= $objReader->load($inputFileName);
			}
			catch(Exception $e) {
				die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
			}

			$sheet         = $objPHPExcel->getSheet(0);
			$highestRow    = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();

			$status    = true;
			$message   = "success";
			$CostMethod= $this->session->CostMethod;
			$arrData   = array();
			$arrHeader = array(); 
			$rowData   = $sheet->rangeToArray('A' . 1 . ':' . $highestColumn . 1,NULL,TRUE,FALSE);
			if($rowData): $arrHeader = $rowData; endif;
			$total_data = 0;
			for ($row = 2; $row <= $highestRow; $row++){
				$total_data += 1;
				$rowData 		= $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
				if(count($rowData[0]) == 10 && $CostMethod == "average" || count($rowData[0]) == 11 && $CostMethod == "standard"):
					$arrType 		  = array('berat','panjang','volume');
					$arrType_product  = array('item','service');
					$arrType_sales 	  = array('sell','nonsell');

					$status_product = true;
					$status_data 	= "insert";
					$status_category= "insert";
					$purchase_price = 0;
					$code 			= $this->main->checkInputData($rowData[0][0]);
					$category_code 	= $this->main->checkInputData($rowData[0][1]);
					$category_name 	= $this->main->checkInputData($rowData[0][2]);
					$name 			= $this->main->checkInputData($rowData[0][3]);
					$min_qty 		= $this->main->checkDuitInput($rowData[0][4]);
					$unit 			= $this->main->checkInputData($rowData[0][5]);
					$sellingprice 	= $this->main->checkDuitInput($rowData[0][6]);
					if($CostMethod == "average"):
						$type_product 	= $this->main->checkInputData($rowData[0][7]);
						$type_sales 	= $this->main->checkInputData($rowData[0][8]);
						$type_serial 	= $this->main->checkInputData($rowData[0][9]);
					else:
						$purchase_price = $this->main->checkDuitInput($rowData[0][7]);
						$type_product 	= $this->main->checkInputData($rowData[0][8]);
						$type_sales 	= $this->main->checkInputData($rowData[0][9]);
						$type_serial 	= $this->main->checkInputData($rowData[0][10]);
					endif;
					
				    $remark 		= '';
				    $no 			= 0;

				    if($type_product == 'Yes'):
			            $xtype_product = "item";
			        else:
			            $xtype_product = "service";
			        endif;

			        if($type_sales == 'Yes'):
			            $xtype_sales = "sell";
			        else:
			            $xtype_sales = "nonsell";
			        endif;

			        // validate
			        $ck_code = $this->db->count_all("ps_product where CompanyID = '$CompanyID' and Code = '$code' and Position = '0'");
			        $ck_category = $this->db->count_all("ps_product where Code = '$category_code' and CompanyID = '$CompanyID' and position != 0 ");
			        if(!$code):
			        	$status_data = "insert";
			        elseif($ck_code>0):
			        	$status_data = "update";
			        elseif($ck_code<=0):
			        	$status_data = "insert";
			        endif;

					if($ck_category>0):
						$ck_category_nm = $this->db->count_all("ps_product where Code = '$category_code' and CompanyID = '$CompanyID' and position != 0 and Name = '$category_name'");
						$ck_category_active = $this->db->count_all("ps_product where Code = '$category_code' and CompanyID = '$CompanyID' and position != 0 and Active = '1'");
						if($ck_category_nm>0):
							$status_category = '';
						else:
							$status_category = 'update';
						endif;
						if($ck_category_active<=0):
							$status_product = false;
							$remark 		.= "- ".$this->lang->line('lb_category_inactive')." <br>";
						endif;
					else:
						$status_category = "insert";
					endif;

			        if($ck_code>0):
						$ck_product_type = $this->db->count_all("ps_product where CompanyID = '$CompanyID' and Code = '$code' and Position = '0' and ProductType = '$xtype_product'");
						$ck_sales_type 	 = $this->db->count_all("ps_product where CompanyID = '$CompanyID' and Code = '$code' and Position = '0' and SalesType = '$xtype_sales'");
						if($type_serial == "Yes"):
							$ck_serial 		 = $this->db->count_all("ps_product where CompanyID = '$CompanyID' and Code = '$code' and Position = '0' and Type = 2");
						else:
							$ck_serial 		 = $this->db->count_all("ps_product where CompanyID = '$CompanyID' and Code = '$code' and Position = '0' and Type != 2 ");
						endif;
						$ck_active = $this->db->count_all("ps_product where CompanyID = '$CompanyID' and Code = '$code' and Position = '0' and Active = '1'");
						
						if($ck_product_type<=0): // cek jika update product type tidak boleh brubah
							$status_product = false;
							$remark 		.= "- ".$this->lang->line('lb_inventory_not_change')." <br>";
						endif;
						if($ck_sales_type<=0): // cek jika update sales type tidak boleh brubah
							$status_product = false;
							$remark 		.= "- ".$this->lang->line('lb_selling_not_change')." <br>";
						endif;
						if($ck_serial<=0):
							$status_product = false;
							$remark 		.= "- ".$this->lang->line('lb_serial_not_change')." <br>";
						endif;
						if($ck_active<=0):
							$status_product = false;
							$remark 		.= "- ".$this->lang->line('lb_product_inactive')." <br>";
						endif;
					endif;

			        if(!$name):
			        	$status_product = false;
						$remark 		.= "- ".$this->lang->line('lb_product_name_empty')." <br>";
					endif;
					if(!$unit && $xtype_product == "item"):
						$status_product = false;
						$remark 		.= "- ".$this->lang->line('lb_inventory_type_empty')." <br>";
					endif;
					if(!is_numeric($sellingprice)):
						$status_product = false;
						$remark 		.= "- ".$this->lang->line('lb_price_input_format')." <br>";
					endif;
					if(!in_array($xtype_product,$arrType_product)):
						$status_product = false;
						$remark 		.= "- ".$this->lang->line('lb_inventory_only_use')." <br>";
					endif;
					if(!in_array($xtype_sales,$arrType_sales)):
						$status_product = false;
						$remark 		.= "- ".$this->lang->line('lb_selling_only_use')." <br>";
					endif;
					if(!in_array($type_serial,array('Yes','No'))):
						$status_product = false;
						$remark 		.= "- ".$this->lang->line('lb_serial_only_use')." <br>";
					endif;
					if($xtype_sales == "nonsell" && $sellingprice>0):
						$status_product = false;
						$remark 		.= "- ".$this->lang->line('lb_selling_zero')." <br>";
					endif;
					if(!$category_code):
						$status_product = false;
						$remark 		.= "- ".$this->lang->line('lb_category_code_empty')." <br>";
					endif;
					if(!$category_name):
						$status_product = false;
						$remark 		.= "- ".$this->lang->line('lb_category_name_empty')." <br>";
					endif;
					// end validate

				    $h = array(
				    	"status"			=> $status_product,
				    	"status_data"		=> $status_data,
				    	"status_category"	=> $status_category,
				    	"product_code"  	=> $code,
				    	"category_code" 	=> $category_code,
				    	"category_name" 	=> $category_name,
				    	"product_name"		=> $name,
				    	"product_min_qty"	=> $min_qty,
				    	"product_unit"		=> $unit,
				    	"product_selling"	=> $this->main->currency($sellingprice),
				    	"product_purchase"	=> $this->main->currency($purchase_price),
				    	"product_type"		=> $type_product,
				    	"product_sales"		=> $type_sales,
				    	"product_serial"	=> $type_serial,
				    	"remark"			=> $remark,
				    );

				    array_push($arrData, $h);

				else:
					$status  = false;
					$message = $this->lang->line('lb_column_not_match');
				endif;
			}

			if($total_data<=0):
				$status = false;
				$message = $this->lang->line('lb_data_not_found');
			endif;

			$output = array(
				"status" 	 	=> $status,
				"message"		=> $message,
				"hak_akses"	 	=> $this->session->hak_akses,
				"data"		 	=> $arrData,
				"header"	 	=> $arrHeader,
				"CostMethod" 	=> $CostMethod,
				"inputFileName" => $inputFileName,
			);
		endif;
		$this->main->echoJson($output);
	}

	public function save_import(){
		$CompanyID 	= $this->session->CompanyID;
		$CostMethod= $this->session->CostMethod;
		$filename 	= $this->input->post("filename");
		if(is_file("./".$filename)):
			$status  = true;
			$message = $this->lang->line('lb_success_import');

			$inputFileName 	= $filename;
			try {
				$inputFileType  = IOFactory::identify($inputFileName);
				$objReader      = IOFactory::createReader($inputFileType);
				$objPHPExcel  	= $objReader->load($inputFileName);
			}
			catch(Exception $e) {
				die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
			}

			$sheet         = $objPHPExcel->getSheet(0);
			$highestRow    = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();

			for ($row = 2; $row <= $highestRow; $row++){
				$rowData 		= $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
				if(count($rowData[0]) == 10 && $CostMethod == "average" || count($rowData[0]) == 11 && $CostMethod == "standard"):
					$arrType 		  = array('berat','panjang','volume');
					$arrType_product  = array('item','service');
					$arrType_sales 	  = array('sell','nonsell');

					$status_product = true;
					$status_data 	= "insert";
					$status_category= "insert";
					$purchase_price = 0;
					$code 			= $this->main->checkInputData($rowData[0][0]);
					$category_code 	= $this->main->checkInputData($rowData[0][1]);
					$category_name 	= $this->main->checkInputData($rowData[0][2]);
					$name 			= $this->main->checkInputData($rowData[0][3]);
					$min_qty 		= $this->main->checkDuitInput($rowData[0][4]);
					$unit 			= $this->main->checkInputData($rowData[0][5]);
					$unit 			= strtoupper($unit);
					$sellingprice 	= $this->main->checkDuitInput($rowData[0][6]);
					if($CostMethod == "average"):
						$type_product 	= $this->main->checkInputData($rowData[0][7]);
						$type_sales 	= $this->main->checkInputData($rowData[0][8]);
						$type_serial 	= $this->main->checkInputData($rowData[0][9]);
					else:
						$purchase_price = $this->main->checkDuitInput($rowData[0][7]);
						$type_product 	= $this->main->checkInputData($rowData[0][8]);
						$type_sales 	= $this->main->checkInputData($rowData[0][9]);
						$type_serial 	= $this->main->checkInputData($rowData[0][10]);
					endif;
					
				    $remark 		= '';
				    $no 			= 0;

				    if($type_product == 'Yes'):
			            $xtype_product = "item";
			        else:
			            $xtype_product = "service";
			        endif;

			        if($type_sales == 'Yes'):
			            $xtype_sales = "sell";
			        else:
			            $xtype_sales = "nonsell";
			        endif;

			        // validate
			        $ck_code = $this->db->count_all("ps_product where CompanyID = '$CompanyID' and Code = '$code' and Position = '0'");
			        $ck_category = $this->db->count_all("ps_product where Code = '$category_code' and CompanyID = '$CompanyID' and position != 0 ");

			        if($ck_category>0):
			        	$ck_category_active = $this->db->count_all("ps_product where Code = '$category_code' and CompanyID = '$CompanyID' and position != 0 and Active = '1'");
			        	if($ck_category_active<=0):
			        		$status_product = false;
			        	endif;
			        endif;

			        if($ck_code>0):
						$ck_product_type = $this->db->count_all("ps_product where CompanyID = '$CompanyID' and Code = '$code' and Position = '0' and ProductType = '$xtype_product'");
						$ck_sales_type 	 = $this->db->count_all("ps_product where CompanyID = '$CompanyID' and Code = '$code' and Position = '0' and SalesType = '$xtype_sales'");
						if($type_serial == "Yes"):
							$ck_serial 		 = $this->db->count_all("ps_product where CompanyID = '$CompanyID' and Code = '$code' and Position = '0' and Type = 2");
						else:
							$ck_serial 		 = $this->db->count_all("ps_product where CompanyID = '$CompanyID' and Code = '$code' and Position = '0' and Type != 2 ");
						endif;
						$ck_active = $this->db->count_all("ps_product where CompanyID = '$CompanyID' and Code = '$code' and Position = '0' and Active = '1'");
						
						if($ck_product_type<=0): // cek jika update product type tidak boleh brubah
							$status_product = false;
						endif;
						if($ck_sales_type<=0): // cek jika update sales type tidak boleh brubah
							$status_product = false;
						endif;
						if($ck_serial<=0):
							$status_product = false;
						endif;
						if($ck_active<=0):
							$status_product = false;
						endif;
					endif;

			        if(!$name):
			        	$status_product = false;
					endif;
					if(!$unit && $xtype_product == "item"):
						$status_product = false;
					endif;
					if(!is_numeric($sellingprice)):
						$status_product = false;
					endif;
					if(!in_array($xtype_product,$arrType_product)):
						$status_product = false;
					endif;
					if(!in_array($xtype_sales,$arrType_sales)):
						$status_product = false;
					endif;
					if(!in_array($type_serial,array('Yes','No'))):
						$status_product = false;
					endif;
					if($xtype_sales == "nonsell" && $sellingprice>0):
						$status_product = false;
					endif;
					if(!$category_code):
						$status_product = false;
					endif;
					if(!$category_name):
						$status_product = false;
					endif;
					// end validate

					if($status_product):

						// pengecekan category
						$data_category = array(
							"Code"			=> $category_code,
							'CompanyID'		=> $CompanyID,
							'UserID'		=> $this->session->id_user,
							'Name'			=> $category_name,
						);
						$cek_category = $this->db->count_all("ps_product where Code = '$category_code' and CompanyID = '$CompanyID' and position != 0 ");
						// update category
						if($cek_category>0):
							$data_category["user_ch"] = $this->session->nama;
							$data_category["date_ch"] = date("Y-m-d H:i:s");
							$this->db->where("Code", $category_code);
							$this->db->where("CompanyID", $CompanyID);
							$this->db->where("Position != ",0);
							$this->db->update("ps_product", $data_category);
						// insert Category
						else:
							$data_category["Position"] 	= 1;
							$data_category['Active']	= 1;
							$data_category["user_add"]  = $this->session->nama;
							$data_category["date_add"]  = date("Y-m-d H:i:s");
							$this->db->insert("ps_product", $data_category);
						endif;

						// pengecekan unit
						# 20190830 MW
						# karena ps_unit sudah tidak digunakan karena unit memakai ps_product_unit
						// $UnitID = 0;
				  //       if($xtype_product == "item"):
				  //       	$cek_unit = $this->db->count_all("ps_unit where CompanyID = '$CompanyID' and Name = '$unit'");
					 //        if($cek_unit>0):
					 //        	$UnitID = $this->main->get_one_column("ps_unit", "UnitID", array("CompanyID" => $CompanyID, "Name" => $unit))->UnitID;
					 //        else:
					 //        	$data_unit = array(
					 //        		"Name"			=> $unit,
					 //        		"CompanyID" 	=> $CompanyID,
					 //        		"Conversion"	=> 1,
					 //        		"active"		=> 1,
					 //        	);
					 //        	$data_unit["user_add"] = $this->session->nama;
						// 		$data_unit["date_add"] = date("Y-m-d H:i:s");
						// 		$this->db->insert("ps_unit",$data_unit);
						// 		$UnitID = $this->db->insert_id();
					 //        endif;
				  //       endif;

				        if($xtype_sales == "nonsell"):
				        	$sellingprice = 0;
				        endif;

				        $data     = array(
					    	'UserID'		=> $this->session->id_user,
							'CompanyID'		=> $CompanyID,
					    	'Code'			=> $code,
							'ParentCode' 	=> $category_code,
					       	'Name' 			=> $name,
							'Position' 		=> 0,
							'minimumstock'  => $min_qty,
							'sellingprice'  => $sellingprice,
							'uom'			=> $unit,
							'Active'		=> 1,
							'TypeCode'		=> 1,
							'ProductType'	=> $xtype_product,
							'SalesType'		=> $xtype_sales,
							// "UnitID"		=> $UnitID,
					    );
				        if($type_serial == "Yes"):
				        	$data['Type'] = 2;
				        else:
				        	$data['Type'] = 0;
				        endif;

				        if($CostMethod == "standard"):
				        	$data['PurchasePrice'] = $purchase_price;
				        endif;

				        if($ck_code>0):
				        	$data["user_ch"] = $this->session->nama;
							$data["date_ch"] = date("Y-m-d H:i:s");
							$this->db->where("CompanyID",$CompanyID);
							$this->db->where("Code",$code);
							$this->db->update("ps_product",$data);
				        else:
				        	if(!$code):
				        		$code = $this->product->generate_product_code($category_code);
				        		$data["Code"] 		= $code;// auto generate
								$data["TypeCode"] 	= 0;
				        	endif;
				        	$data["user_add"] = $this->session->nama;
							$data["date_add"] = date("Y-m-d H:i:s");
							$this->db->insert("ps_product",$data);
							$productid = $this->db->insert_id();
							$this->product->copy_branch($productid);
				        endif;

				        if($xtype_product == "item"):
							$ProductID = $this->main->get_one_column("ps_product", "ProductID",array("CompanyID" => $CompanyID, "Code" => $code, "Position" => 0))->ProductID;
							$this->save_uom($ProductID,$unit,"update");
						endif;
					endif;
				endif;
			}
			if(unlink($filename)):

			endif;
		else:
			$status  = false;
			$message = $this->lang->line('lb_file_not_found1');
		endif;

		$output = array(
			"status" 	=> $status,
			"message"	=> $message,
			"hakakses"	=> $this->session->hak_akses,
		);

		$this->main->echoJson($output);
	}

	public function export($page=""){
		$this->product->export("product",$page);
	}
	public function view($productid="")
	{
		$a = $this->product->get_by_id($productid,"product");
		$product_name = $a->product_name;

		$this->db->select("
			ppb.ProductID as productid,
			ppb.BranchID as branchid,
			ppb.Qty as qty,
			ppb.PurchasePrice as purchase_price,
			ppb.AveragePrice  as average_price,
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
				"average_price" 	=> $this->main->currency($a->average_price),
				"purchase_price" 	=> $this->main->currency($a->purchase_price),
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
		$this->db->select("Name as unit");
		$this->db->where("CompanyID",$this->session->CompanyID);
		$this->db->like("Name",$search);
		$query = $this->db->get("ps_unit");
		return $query->row();
	}
	public function unit($search){
		$a = $this->get_unit($search)->name;
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

 	public function save_image($ProductID,$page="")
 	{
 		$CompanyID 					= $this->session->CompanyID;
	    $nmfile                     = "pipesys_".date("ymdHis");
	    $config['upload_path']      = './img/attachment'; //path folder 
	    $config['allowed_types']    = 'gif|jpg|png|jpeg|bmp|PNG|JPG'; //type yang dapat diakses bisa anda sesuaikan 
	    $config['max_size']         = '99999'; //maksimum besar file 2M 
	    $config['max_width']        = '99999'; //lebar maksimum 1288 px 
	    $config['max_height']       = '99999'; //tinggi maksimu 768 px 
	    $config['file_name']        = $nmfile; //nama yang terupload nantinya 
	    $this->upload->initialize($config); 
	    $upload                     = $this->upload->do_upload('photo');
	    $gbr                        = $this->upload->data();
      	if($upload):
      		$image 					= "img/attachment/".$gbr['file_name'];
            $data = array(
				'CompanyID'			=> $this->session->CompanyID,
				"ID" 				=> $ProductID,
				"Cek" 				=> 1,
				"Type"				=> "product",
				"UserAdd" 			=> $this->session->NAMA,
				"DateAdd" 			=> date("Y-m-d H:i:s"),
				"Image"				=> $image,
			);
			if($page == "update"):
				$cek_attach = $this->db->count_all("PS_Attachment where CompanyID = '$CompanyID' and Type = 'product' and ID = '$ProductID' and Cek = '1'");
				if($cek_attach>0):
					$this->db->select("Image,AttachmentID");
					$this->db->where("CompanyID", $CompanyID);
					$this->db->where("ID", $ProductID);
					$this->db->where("Type", "product");
					$this->db->where("Cek", 1);
					$query = $this->db->get("PS_Attachment")->row();
					if(file_exists('./' . $query->Image)){
	                    unlink('./' . $query->Image);
	                }
	                $this->db->where("AttachmentID", $query->AttachmentID);
	                $this->db->delete("PS_Attachment");
				endif;
			endif;
			$this->db->insert("PS_Attachment",$data);
			$AttachmentID = $this->db->insert_id();
        endif;
 	}

}
