<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendor extends CI_Controller {
	var $title = 'Business Partner';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_vendor",'vendor');
		$this->main->cek_session();
	}
	public function index()
	{
		$ID = $this->input->post("ID");

		$url_modul 					= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url_modul);
		$read 						= $this->main->read($id_url);
		if($read == 0){ redirect(); }
		$vendor_tambah 				= $this->main->menu_tambah($id_url);
		if($vendor_tambah > 0):
            $tambah = $this->main->general_button('add','Add New '.$this->title);
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['title']  	= $this->title;
		$data['tambah'] 	= $tambah;
		if($this->session->app == "pipesys"):
		$data['modal'] 		= 'vendor/modal';
		else:
		$data['modal'] 		= 'vendor/modal_customer';
		endif;
		$data['page'] 		= 'vendor/list';
		$data['modul'] 		= 'partner';
		$data['ID']			= $ID;
		$data['url_modul']  = $url_modul;
		$this->load->view('index',$data);
	}
	public function customer()
	{
		$url_modul 					= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url_modul);
		$read 						= $this->main->read($id_url);
		if($read == 0){ redirect(); }
		$vendor_tambah 				= $this->main->menu_tambah($id_url);
		if($vendor_tambah > 0):
            $tambah = '<button type="button" class="btn btn-primary btn-outline" onclick="tambah()" ><i class="fa fa-plus"></i> Add New Customer</button>';
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['title']  	= 'Customer';
		$data['tambah'] 	= $tambah;
		if($this->session->app == "pipesys"):
		$data['modal'] 		= 'vendor/modal';
		else:
		$data['modal'] 		= 'vendor/modal_customer';
		endif;
		$data['page'] 		= 'vendor/list';
		$data['modul'] 		= 'partner';
		$data['url_modul']  = $url_modul;
		$this->load->view('index',$data);
	}
	//---------------------------------------------------------------------------------------
	public function ajax_list($page ="",$url_modul = "")
	{
		$id_url = $this->main->id_menu($url_modul);
		$list 	= $this->vendor->get_datatables($page);
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		foreach ($list as $a) {
			$basecamp 		= "";
			$vendor_ubah 	= $this->main->menu_ubah($id_url);
			$vendor_hapus 	= $this->main->menu_hapus($id_url);
			if($vendor_ubah > 0):
           	$ubah = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="Edit" onclick="edit('."'".$a->vendorid."'".')">Edit</a>';
			else:
				$ubah = ""; 
			endif;
			if($vendor_hapus > 0):
           		if($a->active == 1):
           			$hapus = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="Delete Data" onclick="hapus('."'".$a->vendorid."'".')">Delete</a>';
           	
           		else:
           			$hapus = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="Undelete Data" onclick="active('."'".$a->vendorid."'".')">Undelete</a>';
           		endif;
			else: 
				$hapus = ""; 
			endif;
			// if($vendor->position == 1): $hapus = ""; endif;

			$button  = '<div class="btn-group btn-group-xs" aria-label="Basic example" role="group">';
            $button .= $ubah;
            $button .= $hapus;
            $button .= '</div>';
			if($a->position == 1): $posisi = "<biru>vendor</biru>"; else: $posisi = "<hijau>customer</hijau>"; endif;

			$active = "";
            if($a->active == 0):
            	$active = '<i class="icon fa-trash pull-right" aria-hidden="true"></i>';
            endif;
            if($a->basecamp == 1):
            	$basecamp = '<br/><span class="info-green">basecamp</span>';
            endif;


			$no++;

            $code = '<a href="javascript:void(0)" type="button" class="" title="View Detail" onclick="view_vendor('."'".$a->vendorid."'".')">'.$a->name.'</a>';
            $code2 = '<a href="javascript:void(0)" type="button" class="" title="View Detail" onclick="view_vendor('."'".$a->vendorid."'".')">'.$a->partnercode.'</a>';
			$row 	= array();
			$row[] 	= $i++;
			if($page=="partner"):
				$row[] 	= $code2;
				$row[] 	= $code.$active.$basecamp;
				$row[] 	= $a->phone;
				$row[] 	= $a->email;
				$row[] 	= $a->address;
                if($this->session->app == "pipesys"):
				$row[] 	= $posisi;
				$row[] 	= $a->remark;
				endif;
			else:
				$row[] 	= $code.$basecamp;
				$row[] 	= $a->address;
				$row[] 	= $a->phone;
				$row[] 	= $a->email;
				$row[] 	= $a->remark;
				$row[] 	= "";
			endif;
			// $row[] 	= $button;		
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->vendor->count_all($page),
			"recordsFiltered" => $this->vendor->count_filtered($page),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function ajax_edit($id,$page="")
	{
		$list_address 	= array();
		$list_contact 	= array();
		
		$vendorid 		= $this->session->vendorid;
		$vendorcode 	= $this->session->vendorcode;
		$a 				= $this->vendor->get_by_id($id);
		$code 			= $a->vendorcode;

		$data_address 	= $this->vendor->vendor_address_list($code);
		$item 			= array();
		foreach ($data_address as $b):
			$item["address_code"] 	= $b->addressid;
			$item["address"] 		= $b->address;
			$item["city"] 			= $b->city;
			$item["province"] 		= $b->province;
			$item['delivery']		= $b->delivery;
			$item['invoice']		= $b->invoice;

			array_push($list_address,$item);
		endforeach;

		$data_contact 	= $this->vendor->vendor_contact_list($code);
		$item 			= array();
		foreach ($data_contact as $c):
			$item["contact_code"] 	= $c->contactid;
			$item["phone"] 			= $c->phone;
			$item["email"] 			= $c->email;
			array_push($list_contact,$item);
		endforeach;
		// $data 		 = $this->vendor->get_by_id($id,"vendor");
		$edit 		 = $this->main->button_action("edit2",$id);
		$delete 	 = $this->main->button_action("delete4",$id);
		if($a->active == 0):
			$delete 	 = $this->main->button_action("undelete2",$id);
		endif;
		if($a->active == 0):
			$edit 	 	 = '';
		endif;
		$data = array(
			"vendorid"		=> $a->vendorid,
			"code"			=> $a->vendorcode,
			"position"		=> $a->position,
			"name" 			=> $a->name,
			"phone" 		=> $a->phone,
			"email" 		=> $a->email,
			"address" 		=> $a->address,
			"lat" 			=> $a->lat,
			"lng" 			=> $a->lng,
			"radius" 		=> $a->radius,
			"npwp" 			=> $a->npwp,
			"top" 			=> $a->ap_max,
			"remark" 		=> $a->remark,
			"basecamp" 		=> $a->basecamp,
			"groupname" 	=> $a->groupname,
			"list_address" 	=> $list_address,
			"list_contact" 	=> $list_contact,
			"edit" 	 	  	=> $edit,
			"delete" 	  	=> $delete,
		);
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
	}
	
	public function simpan($page="")
	{
		$list_address = "";
		$this->_validate($page);
		if($page == "partner"):
			$poisisi 	= $this->input->post('type');
			$code 		= $this->input->post('code');			
			if($poisisi == "vendor"): 
				$poisisi = 1; 
			else: 
				$poisisi = 2; 
			endif;

			if(!$code):
				$CodeType 		= 0;
				$vendorcode 	= $this->main->vendor_code_generate($poisisi);
			else:
				$CodeType 		= 1;
				$vendorcode  	= $code;
			endif;
			$basecamp = $this->input->post("basecamp");
			if($basecamp == "yes"):
				$basecamp = 1;
			else:
				$basecamp = 0;
			endif;

			$GroupName = strtoupper($this->input->post('GroupName'));
			if(!$GroupName){
				$GroupName = null;
			}

			$data = array(
				'CodeType'		=> $CodeType,
				'code'  		=> $vendorcode,
				'App' 			=> "pipesys",
				'CompanyID'  	=> $this->session->CompanyID,
				'title' 		=> "MR",
				'position'		=> $poisisi,
				'name'			=> $this->input->post('name'),
				'ProductCustomer' => $GroupName,
				'remark'		=> $this->input->post('remark'),
				'npwp' 			=> $this->input->post('npwp'),
				'ap_max'		=> $this->input->post('top'),
				// 'email'			=> $this->input->post('email'),
				// 'phone'			=> $this->input->post('phone'),
				// 'address'		=> $this->input->post("address"),
				'lat'			=> $this->input->post("lat"),
				'lng'			=> $this->input->post("lng"),
				'radius'		=> $this->input->post("radius"),
				'active'		=> 1,
				'basecamp'		=> $basecamp,
			);

			if($this->session->app == "salespro"):
				$data['email']		= $this->input->post('email');
				$data['phone']		= $this->input->post('phone');
				$data['address']	= $this->input->post("address");
			endif;
		else:
			$vendorcode 	= $this->main->vendor_code_generate(1);
			$data = array(
				'code'  	=> $vendorcode,
				'CompanyID'  => $this->session->CompanyID,
				'title' 	=> "MR",
				'position'	=> 0,
				'name'		=> $this->input->post('name'),
				'remark'	=> $this->input->post('remark'),
			);
		endif;
		$insert 		= $this->vendor->save($data);
		if($this->session->app == "pipesys"):
			$list_address 	= $this->vendor->vendor_address_save("update",$vendorcode,$insert);
			$list_contact 	= $this->vendor->vendor_contact_save("update",$vendorcode,$insert);
		endif;
        	
        $address 	= '';
        $city 		= '';
        $province 	= '';
        $page_address = $this->input->post('page_address');
        if($page_address == "invoice"):
        	$data_address 	= $this->vendor->vendor_address_list($vendorcode,"invoice");
        	if(count($data_address)>0):
        		$address 	= $data_address[0]->address;
        		$city 	 	= $data_address[0]->city;
        		$province 	= $data_address[0]->province;
        	endif;
        elseif($page_address == "delivery"):
        	$data_address 	= $this->vendor->vendor_address_list($vendorcode,"delivery");
        	if(count($data_address)>0):
        		$address 	= $data_address[0]->address;
        		$city 	 	= $data_address[0]->city;
        		$province 	= $data_address[0]->province;
        	endif;
        endif;

		$res = array(
			'status' 	=> true,
			'message' 	=> 'success',
			'ID'		=> $insert,
			'Name'		=> $this->input->post('name'),
			'npwp' 		=> $this->input->post('npwp'),
			'ap_max'	=> $this->input->post('top'),
			'address'	=> $address,
			'city'		=> $city,
			'province'	=> $province,
			'productcustomer'	=> $GroupName,
		);
		$this->main->echoJson($res);
	}
	public function ajax_update($page="")
	{
		$this->_validate("update");
		$list_address 	= "";
		$a 				= $this->vendor->get_by_id($this->input->post('vendorid'));
		$vendorcode 	= $a->vendorcode;
		if($page == "partner"):

			$posisi = $this->input->post('type');
			if($posisi == "vendor"): 
				$posisi = 1; 
			else: 
				$posisi = 2; 
			endif;
			$basecamp = $this->input->post("basecamp");
			if($basecamp == "yes"):
				$basecamp = 1;
			else:
				$basecamp = 0;
			endif;

			$GroupName = strtoupper($this->input->post('GroupName'));
			if(!$GroupName){
				$GroupName = null;
			}

			$data = array(
				// 'position' 	=> $posisi,
				'name'				=> $this->input->post('name'),
				'remark'			=> $this->input->post('remark'),
				'ProductCustomer' 	=> $GroupName,
				'npwp' 				=> $this->input->post('npwp'),
				'ap_max' 			=> $this->input->post('top'),
				// 'email'		=> $this->input->post('email'),
				// 'phone'		=> $this->input->post('phone'),
				// 'address'	=> $this->input->post("address"),
				'lat'				=> $this->input->post("lat"),
				'lng'				=> $this->input->post("lng"),
				'radius'			=> $this->input->post("radius"),
				'basecamp'			=> $basecamp,
			);

			if($this->session->app == "salespro"):
				$data['email']		= $this->input->post('email');
				$data['phone']		= $this->input->post('phone');
				$data['address']	= $this->input->post("address");
			endif;
		else:
			$data = array(
				'name'		=> $this->input->post('name'),
				'remark'	=> $this->input->post('remark'),
			);
		endif;
		$this->vendor->update(array('vendorid' => $this->input->post('vendorid')), $data);
		if($this->session->app == "pipesys"):
			$list_address 	= $this->vendor->vendor_address_save("update",$vendorcode,$this->input->post('vendorid'));
			$list_contact 	= $this->vendor->vendor_contact_save("update",$vendorcode,$this->input->post('vendorid'));
		endif;
		// echo json_encode(array("status" => TRUE,"pesan" => $this->input->post("basecamp")));

		$res = array(
			'status' 	=> true,
			'message' 	=> 'success',
		);
		$this->main->echoJson($res);
	}
	public function ajax_delete($id,$status="")
	{
		$active = 0;
		if($status == "active"):
			$active = 1;
		endif;
		$data = array(
			"Active" => $active,
		);
		$this->vendor->update(array('vendorid' => $id), $data);
		echo json_encode(array("status" => TRUE));
	}
	private function _validate($page="")
	{	
		$CompanyID 		= $this->session->CompanyID;
		$name 			= $this->input->post('name');
		$code 			= $this->input->post('code');
		$Position 		= $this->input->post('Position');
		$vendorid 		= $this->input->post('vendorid');
		$where 			= '';
		if($page == "update"):
			$where = " && VendorID != '$vendorid' ";
		endif;

		if($code):
			$cek_code		= $this->db->count_all("PS_Vendor where CompanyID = '$CompanyID' and Code = '$code'");
		else:
			$cek_code 		= 0;
		endif;
		$cek_name 		= $this->db->count_all("PS_Vendor where Name = '$name' and CompanyID = '$CompanyID' ".$where);

		$data 					= array();
		$data['error_string'] 	= array();
		$data['inputerror'] 	= array();
		$data['status'] 		= TRUE;

		if($this->input->post('name') == '')
		{
			$data['inputerror'][] 	= 'name';
			$data['error_string'][] = 'Partner name cannot be null';
			$data['status'] 		= FALSE;
		}
		
		if($cek_code>0){
			$data['inputerror'][] 	= 'code';
			$data['error_string'][] = 'Code has been already exist';
			$data['status'] 		= FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}

	}

	public function sp_list_vendor($id){
		$query = $this->vendor->vendor_list($id);
		$output = "";
		foreach ($query->result() as $d) {
			$output .= '<option value="'.$d->VendorID.'">'.$d->Name.'</option>';
		}
		$res["data"] = $output;
		header('Content-Type: application/json');
        echo json_encode($res,JSON_PRETTY_PRINT);  
	}

	public function import(){
		// $this->vendor->import();
		$this->import_vendor();
	}
	public function export($page=""){
		$this->vendor->export($page);
	}

	private function import_vendor(){
		$fileName                 = $this->input->post('file', TRUE);
		$CompanyID 				  = $this->session->CompanyID;
		$userid 				  = $this->session->id_user;
		$folder 				  = $this->main->create_folder_vendor();
		$config['upload_path']    = './'.$folder; 
		$config['file_name']      = $fileName;
		$config['allowed_types']  = 'xls|xlsx|csv|ods|ots';
		$config['max_size']       = 10000;
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if (!$this->upload->do_upload('file')):
			$output = array(
				"status" 	=> false,
				"message"	=> "Please Upload Excel File",
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
			$arrData   = array();
			$arrHeader = array(); 
			$rowData   = $sheet->rangeToArray('A' . 1 . ':' . $highestColumn . 1,NULL,TRUE,FALSE);
			if($rowData): $arrHeader = $rowData; endif;
			$total_data = 0;

			for ($row = 2; $row <= $highestRow; $row++){
				$total_data += 1;
				$rowData 		= $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
				if(count($rowData[0]) == 12):
					$status_vendor = true;

					$arrPosition 		  = array('Vendor','Customer');

					$Code 			= $this->main->checkInputData($rowData[0][0]);
					$Name 			= $this->main->checkInputData($rowData[0][1]);
					$Position		= $this->main->checkInputData($rowData[0][2]);
					$Address 		= $this->main->checkInputData($rowData[0][3]);
					$City 			= $this->main->checkInputData($rowData[0][4]);
					$Province 		= $this->main->checkInputData($rowData[0][5]);
					$Phone 			= $this->main->checkInputData($rowData[0][6]);
					$Email 			= $this->main->checkInputData($rowData[0][7]);
					$Npwp 			= $this->main->checkInputData($rowData[0][8]);
					$Ap_max 		= $this->main->checkDuitInput($rowData[0][9]);
					$Groupname 		= $this->main->checkInputData($rowData[0][10]);
					$Remark 		= $this->main->checkInputData($rowData[0][11]);
					$Message 		= '';

					if($Position == "Vendor"):
						$xposition = 1;
					else:
						$xposition = 2;
					endif;

					$status_data = "insert";
					$ck_code = $this->db->count_all("PS_Vendor where CompanyID = '$CompanyID' and Code = '$Code'");
					if(!$Code):
			        	$status_data = "insert";
			        elseif($ck_code>0):
			        	$status_data = "update";
			        elseif($ck_code<=0):
			        	$status_data = "insert";
			        endif;

			        if($ck_code>0):
			        	$ck_active = $this->db->count_all("PS_Vendor where CompanyID = '$CompanyID' and Code = '$Code' and Active = '1'");
			        	if($ck_active<=0):
			        		$status_vendor = false;
							$Message 		.= "- "."Vendor Code has been inactive <br>";
			        	endif;
			        endif;
			        if(!$Name):
			        	$status_vendor = false;
						$Message 		.= "- "."Name can't be null <br>";
					endif;
					if(!in_array($Position,$arrPosition)):
						$status_vendor = false;
						$Message 		.= "- "."Partner Type only use Vendor or Customer <br>";
					endif;
					if(!is_numeric($Ap_max)):
						$status_vendor = false;
						$Message 		.= "- "."Input TOP With Number Format <br>";
					endif;
					if($ck_code>0):
						$ck_vendor_type = $this->db->count_all("PS_Vendor where CompanyID = '$CompanyID' and Code = '$Code' and Position = '$xposition'");
						if($ck_vendor_type<=0): // cek jika update product type tidak boleh brubah
							$status_vendor = false;
							$Message 	.= "- "."Update can't change Partner Type <br>";
						endif;
					endif;

					$h = array(
				    	"status"		=> $status_vendor,
				    	"status_data"	=> $status_data,
				    	"Code"  		=> $Code,
				    	"Name" 			=> $Name,
				    	"Position" 		=> $Position,
				    	"Address"		=> $Address,
				    	"City"			=> $City,
				    	"Province"		=> $Province,
				    	"Phone"			=> $Phone,
				    	"Email"			=> $Email,
				    	"Npwp"			=> $Npwp,
				    	"Ap_max"		=> $Ap_max,
				    	"Groupname"		=> $Groupname,
				    	"Remark"		=> $Remark,
				    	"Message" 		=> $Message,
				    );

				    array_push($arrData, $h);

				else:
					$status  = false;
					$message = "Column not match.";
				endif;
			}

			if($total_data<=0):
				$status = false;
				$message = 'Data Not Found';
			endif;

			$output = array(
				"status" 	 	=> $status,
				"message"		=> $message,
				"hak_akses"	 	=> $this->session->hak_akses,
				"data"		 	=> $arrData,
				"header"	 	=> $arrHeader,
				"inputFileName" => $inputFileName,
			);
		endif;
		$this->main->echoJson($output);
	}

	public function save_import(){
		$CompanyID 	= $this->session->CompanyID;
		$filename 	= $this->input->post("filename");
		if(is_file("./".$filename)):
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

			$status    = true;
			$message   = "success";

			for ($row = 2; $row <= $highestRow; $row++){
				$rowData 		= $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
				if(count($rowData[0]) == 12):
					$status_vendor = true;
					$arrPosition 		  = array('Vendor','Customer');

					$Code 			= $this->main->checkInputData($rowData[0][0]);
					$Name 			= $this->main->checkInputData($rowData[0][1]);
					$Position		= $this->main->checkInputData($rowData[0][2]);
					$Address 		= $this->main->checkInputData($rowData[0][3]);
					$City 			= $this->main->checkInputData($rowData[0][4]);
					$Province 		= $this->main->checkInputData($rowData[0][5]);
					$Phone 			= $this->main->checkInputData($rowData[0][6]);
					$Email 			= $this->main->checkInputData($rowData[0][7]);
					$Npwp 			= $this->main->checkInputData($rowData[0][8]);
					$Ap_max 		= $this->main->checkDuitInput($rowData[0][9]);
					$Groupname 		= $this->main->checkInputData($rowData[0][10]);
					$Remark 		= $this->main->checkInputData($rowData[0][11]);

					if($Position == "Vendor"):
						$xposition = 1;
					else:
						$xposition = 2;
					endif;

					$ck_code = $this->db->count_all("PS_Vendor where CompanyID = '$CompanyID' and Code = '$Code'");

					if($ck_code>0):
			        	$ck_active = $this->db->count_all("PS_Vendor where CompanyID = '$CompanyID' and Code = '$Code' and Active = '1'");
			        	if($ck_active<=0):
			        		$status_vendor = false;
			        	endif;
			        endif;
					if(!$Name):
			        	$status_vendor = false;
					endif;
					if(!in_array($Position,$arrPosition)):
						$status_vendor = false;
					endif;
					if(!is_numeric($Ap_max)):
						$status_vendor = false;
					endif;
					if($ck_code>0):
						$ck_vendor_type = $this->db->count_all("PS_Vendor where CompanyID = '$CompanyID' and Code = '$Code' and Position = '$xposition'");
						if($ck_vendor_type<=0): // cek jika update product type tidak boleh brubah
							$status_vendor = false;
						endif;
					endif;

					if($status_vendor):
						$data = array(
							"Name"			=> $Name,
							"Position"		=> $xposition,
							"Address"		=> $Address,
							"Phone"			=> $Phone,
							"Email"			=> $Email,
							"npwp"			=> $Npwp,
							"ap_max"		=> $Ap_max,
							"Remark"		=> $Remark,
							"Active"		=> 1,
							"CompanyID"		=> $CompanyID,
							"App"			=> 'pipesys',
						);

						if($ck_code>0):
							$data['User_Ch'] = $this->session->NAMA;
							$data['Date_Ch'] = date("Y-m-d H:i:s");
							$this->db->where("CompanyID", $CompanyID);
							$this->db->where("Code", $Code);
							$this->db->update("PS_Vendor", $data);
							$VendorID 	  = $this->main->get_one_column("PS_Vendor","VendorID",array("Code"	=> $Code, "CompanyID" => $CompanyID))->VendorID;
						else:
							if(!$Code):
								$Code = $this->main->vendor_code_generate($xposition);
								$data['CodeType'] = 0;
							else:
								$data['CodeType'] = 1;
							endif;
							$data['Code']	  = $Code;
							$data['User_Add'] = $this->session->NAMA;
							$data['Date_Add'] = date("Y-m-d H:i:s");
							$this->db->insert("PS_Vendor", $data);
							$VendorID = $this->db->insert_id();
						endif;

						$list_address 	= $this->vendor->check_address($Code,$Address,$City,$Province,$VendorID);
						$list_contact 	= $this->vendor->check_phone($Code,$Phone,$Email,$VendorID);
					endif;
				else:
					$status  = false;
					$message = "Column not match.";
				endif;
			}

		else:
			$status  = false;
			$message = "File not found, please reupload import file";
		endif;

		$output = array(
			"status" 	=> $status,
			"message"	=> $message,
			"hakakses"	=> $this->session->hak_akses,
		);

		$this->main->echoJson($output);
	}
}		
