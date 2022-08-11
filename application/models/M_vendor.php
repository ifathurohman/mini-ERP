<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_vendor extends CI_Model {

	var $table 	= 'PS_Vendor';
	var $column = array(
		'ps_vendor.vendorid',
		'ps_vendor.code',
		'ps_vendor.name',
		'ps_vendor.address',
		'ps_vendor.phone',
		'ps_vendor.email',
		'ps_vendor.npwp',
		'ps_vendor.remark',
	); //set column field database for order and search
	var $order 	= array('vendorid' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		// $this->load->library(array('PHPExcel','IOFactory'));
	}

	private function _get_datatables_query($page="")
	{
		$url = $this->uri->segment(1);
		$this->db->select("
			ps_vendor.vendorid 	as vendorid,
			ps_vendor.code 		as partnercode,
			ps_vendor.name 		as name,
			ps_vendor.address 	as address,
			ps_vendor.phone 	as phone,
			ps_vendor.email 	as email,
			ps_vendor.npwp 		as npwp,
			ps_vendor.remark 	as remark,
			ps_vendor.position 	as position,
			ps_vendor.active 	as active,
			ps_vendor.basecamp 	as basecamp,
		");
		$this->db->where("App","pipesys");
		$this->db->where("ps_vendor.CompanyID",$this->session->CompanyID);
		if($page == "partner"):
			$this->db->where_in("ps_vendor.position",array(0,1,2));
		elseif($page == "branch"):
			$this->db->where_in("ps_vendor.position",array(1));
		else:
			$this->db->where_in("ps_vendor.position",array(0,1,2));
		endif;
		$this->db->order_by("ps_vendor.Active","DESC");

		$this->db->from($this->table." as ps_vendor");
		$i = 0;
        $column = $this->column;
        $Search = $this->input->post("Search");
        foreach ($column as $item) // loop column 
        {
            if($Search){
                
                if($i===0){
                    $this->db->group_start();
                    $this->db->like($item, $Search);
                }
                else{
                    $this->db->or_like($item, $Search);
                }
                if(count($column) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $column[$i] = $item; // set column array variable to order processing
            $i++;
        }
		if($this->input->post("Active") != "none"):
            $Active = $this->input->post("Active");
            $this->db->where("ps_vendor.Active", $Active);
        endif;
        if($this->input->post("Position")):
        	$Position = $this->input->post("Position");
            $this->db->where("ps_vendor.Position", $Position);
        endif;
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables($page ="")
	{
		$this->_get_datatables_query($page);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($page ="")
	{
		$this->_get_datatables_query($page);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($page = "")
	{
		$url = $this->uri->segment(1);
		// $this->db->where("ps_vendor.CompanyID",$this->session->CompanyID);
		// if($page == "partner"):
		// 	$this->db->where_in("ps_vendor.position",array(1,2));
		// else:
		// 	$this->db->where_in("ps_vendor.position",array(0));
		// endif;
		$this->db->where("App",$this->session->app);
		$this->db->where("ps_vendor.CompanyID",$this->session->CompanyID);
		$this->db->from($this->table." as ps_vendor");
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->select("vendorid,code as vendorcode,name,phone,email,address,lat,lng,radius,npwp,ap_max,remark,position,basecamp,active,ifnull(ProductCustomer,'') as groupname");
		$this->db->from($this->table);
		$this->db->where('vendorid',$id);
		$query = $this->db->get();
		return $query->row();
	}

	public function save($data)
	{
		$this->db->set("user_add",$this->session->userdata("NAMA"));
		$this->db->set("date_add",date("Y-m-d H:i:s"));
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->set("user_ch",$this->session->userdata("NAMA"));
		$this->db->set("date_ch",date("Y-m-d H:i:s"));
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('code', $id);
		$this->db->delete($this->table);

		$this->db->where("vendorcode",$id);
		$this->db->delete("ps_vendor_address");

		$this->db->where("vendorcode",$id);
		$this->db->delete("ps_vendor_contact");

	}
	public function vendor_address_save($page,$vendorcode = "",$VendorID=""){
		$address_code 	= $this->input->post("address_code");
		$address 		= $this->input->post("address");
		$city 			= $this->input->post("city");
		$province 		= $this->input->post("province");
		$invoice 		= $this->input->post('r_invoice');
		$delivery 		= $this->input->post('r_delivery');
		$list_address 	= array();
		$data 			= array();

		// $address = array("1111111111");
		$no = 1;
		foreach ($address as $key => $v) {
			$data = array(
				'address' 		=> $address[$key],
				'city' 			=> $city[$key],
				'province' 		=> $province[$key],
				'CompanyID'		=> $this->session->CompanyID,
			);

			if($invoice[$key]):
				$data['Payment'] = 1;
			else:
				$data['Payment'] = 0;
			endif;

			if($delivery[$key]):
				$data['Delivery'] = 1;
			else:
				$data['Delivery'] = 0;
			endif;

			if($page == "save"):
				$data["vendorcode"]  	= $vendorcode;
				$data["VendorID"]  		= $VendorID;
				$data["code"]  			= $this->main->vendor_address_code_generate();
				$data["User_Add"]  		= $this->session->nama;
				$data["Date_Add"]  		= date("Y-m-d H:i:s");
				$this->db->insert("ps_vendor_address",$data);
			elseif($page == "update"):
				if($address_code[$key] == ""):
					$data["vendorcode"]  	= $vendorcode;
					$data["VendorID"]  		= $VendorID;
					$data["code"]  			= $this->main->vendor_address_code_generate();
					$data["User_Add"]  		= $this->session->nama;
					$data["Date_Add"]  		= date("Y-m-d H:i:s");
					$this->db->insert("ps_vendor_address",$data);
				else:
					$this->db->where("vendoraddressid",$address_code[$key]);
					$this->db->update("ps_vendor_address",$data);
				endif;

			endif;
			array_push($list_address,$data);
			if($no++ == 1):
				$this->update(array('code' => $vendorcode), array("Address"=>$address[$key]));
			endif;
		}
		return $list_address;
	}
	public function vendor_contact_save($page,$vendorcode = "",$VendorID=""){
		$contact_code 	= $this->input->post("contact_code");
		$phone 			= $this->input->post("phone");
		$email 			= $this->input->post("email");
		$list_phone 	= array();
		$data 			= array();

		// $phone = array(1111111111111);
		$no = 1;
		foreach ($phone as $key => $v) {
			$data = array(
				'contactphone' 	=> $phone[$key],
				'email' 		=> $email[$key],
				'CompanyID'		=> $this->session->CompanyID,
			);
			if($page == "save"):
				$data["vendorcode"]  	= $vendorcode;
				$data['VendorID']		= $VendorID;
				$data["code"]  			= $this->main->vendor_phone_code_generate();
				$data["User_Add"]  		= $this->session->nama;
				$data["Date_Add"]  		= date("Y-m-d H:i:s");
				$this->db->insert("ps_vendor_contact",$data);
			elseif($page == "update"):
				if($contact_code[$key] == ""):
					$data["vendorcode"]  	= $vendorcode;
					$data['VendorID']		= $VendorID;
					$data["code"]  			= $this->main->vendor_phone_code_generate();
					$data["User_Add"]  		= $this->session->nama;
					$data["Date_Add"]  		= date("Y-m-d H:i:s");
					$this->db->insert("ps_vendor_contact",$data);
				else:
					$this->db->where("vendorcontactid",$contact_code[$key]);
					$this->db->update("ps_vendor_contact",$data);
					$data["contact_code"] = $contact_code[$key];
				endif;
			endif;
			array_push($list_phone,$data);
			if($no++ == 1):
				$this->update(array('code' => $vendorcode), array("Phone"=>$phone[$key],"Email"=>$email[$key]));
			endif;

		}
		return $list_phone;

	}
	public function vendor_address_list($code,$page=""){
		$CompanyID = $this->session->CompanyID;
		$this->db->select("
			vendoraddressid as addressid,
			code as address_code,
			address as address,
			city as city,
			province as province,
			ifnull(Delivery,0) as delivery,
			ifnull(Payment,0)  as invoice,
		");
		if($page == "invoice"):
			$this->db->where("Payment", 1);
		elseif($page == "delivery"):
			$this->db->where("Delivery", 1);
		endif;
		$this->db->where("vendorcode",$code);
		$this->db->where("CompanyID", $CompanyID);
		$query = $this->db->get("ps_vendor_address");
		return $query->result();
	}
	public function vendor_contact_list($code){
		$CompanyID = $this->session->CompanyID;
		$this->db->select("
			vendorcontactid as contactid,
			code as contact_code,
			contactphone as phone,
			email as email,
		");
		$this->db->where("vendorcode",$code);
		$this->db->where("CompanyID", $CompanyID);
		$query = $this->db->get("ps_vendor_contact");
		return $query->result();
	}

	//20180523 MW
	#customer/vendor list
	public function vendor_list($CompanyID){
		$this->db->select("VendorID,Name");
		$this->db->where("CompanyID", $CompanyID);
		$query = $this->db->get($this->table);

		return $query;
	}

	public function export_data(){
		$this->db->select("
			PS_Vendor.Name,
			PS_Vendor.Code,
			(case
				when PS_Vendor.Position = '1' then 'Vendor'
				when PS_Vendor.Position = '2' then 'Customer'
				else 'Vendor'
			end)	 					as Position,
			PS_Vendor.Phone,
			PS_Vendor.Email,
			PS_Vendor.Remark,
			PS_Vendor.Address,
			PS_Vendor.npwp,
			PS_Vendor.ap_max,
			PS_Vendor.ProductCustomer 	as groupname,

			ps_vendor_address.city,
			ps_vendor_address.province,
		");

		$this->db->join("ps_vendor_address","ps_vendor_address.VendorAddressID = (select VendorAddressID from ps_vendor_address where vendorcode = PS_Vendor.Code and CompanyID = PS_Vendor.CompanyID limit 1)","left");
		$this->db->where("PS_Vendor.CompanyID",$this->session->CompanyID);
		$this->db->where("App",$this->session->app);
		$this->db->where("PS_Vendor.active",1);
		$this->db->order_by("PS_Vendor.vendorid","DESC");
		$this->db->from($this->table);
		$query = $this->db->get();
		return $query->result();
	}
	public function export($page="")
	{
		$file_name 			= "SampleVendor".date("Ymd_His").".xls";
		$data["NAMA"]       = $this->session->userdata("NAMA");
		$data["DATETIME"]   = date("Y-m-d H:i:s");
		$lap_data     		= $this->export_data();
		$jumlah_data  		= count($lap_data); 
		$jumlah_kolom 		= $jumlah_data + 1;
		$objPHPExcel 		= new PHPExcel();
		// Set document properties
		$objPHPExcel->getProperties()->setCreator("PIPESYS")
		            ->setLastModifiedBy("PIPESYS")
		            ->setTitle("Office 2003 XLS Test Document")
		            ->setSubject("Office 2003 XLS Test Document")
		            ->setDescription("PIPESYS")
		            ->setKeywords("office 2003 openxml php")
		            ->setCategory("PIPESYS");
		$border_style = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'),)));
		// $objPHPExcel->getActiveSheet()->getStyle("A1:D".$jumlah_kolom)->applyFromArray($border_style);
		foreach(range('A','L') as $columnID):
	        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
	    endforeach;
		#ini untuk kolom pertama      
		$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('A1', 'Business Patner Code')
		            ->setCellValue('B1', 'Name')
		            ->setCellValue('C1', 'Partner Type')
		            ->setCellValue('D1', 'Address')
		            ->setCellValue('E1', 'City')
		            ->setCellValue('F1', 'Province')
		            ->setCellValue('G1', 'Phone')
		            ->setCellValue('H1', 'Email')
		            ->setCellValue('I1', 'NPWP')
		            ->setCellValue('J1', 'TOP')
		            ->setCellValue('K1', 'Group Name')
		            ->setCellValue('L1', 'Remark');
		// Miscellaneous glyphs, UTF-8
		$i    = 1;
		$ii   = 2; 
		#ini untuk kolom data dalam looping
		if(!empty($lap_data) && $page != "template"):
		    foreach ($lap_data as $l):                
		      $no              	= $i++; 
		      $urut            	= $ii++;
		      $objPHPExcel->setActiveSheetIndex(0)
		                  ->setCellValue('A'.$urut, $l->Code)
		                  ->setCellValue('B'.$urut, $l->Name)
		                  ->setCellValue('C'.$urut, $l->Position)
		                  ->setCellValue('D'.$urut, $l->Address)
		                  ->setCellValue('E'.$urut, $l->city)
		                  ->setCellValue('F'.$urut, $l->province)
		                  ->setCellValue('G'.$urut, $l->Phone)
		                  ->setCellValue('H'.$urut, $l->Email)
		                  ->setCellValue('I'.$urut, $l->npwp)
		                  ->setCellValue('J'.$urut, $l->ap_max)
		                  ->setCellValue('K'.$urut, $l->groupname)
		                  ->setCellValue('L'.$urut, $l->Remark);
		    endforeach;
		endif;
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Template Import VendorID	');
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$file_name.'"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();
		$objWriter->save('php://output');
		exit;
	}
	public function import(){
		$fileName                 = $this->input->post('file', TRUE);
		$CompanyID 				  = $this->session->CompanyID;
		$config['upload_path']    = './file/'; 
		$config['file_name']      = $fileName;
		$config['allowed_types']  = 'xls|xlsx|csv|ods|ots';
		$config['max_size']       = 10000;
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		if (!$this->upload->do_upload('file')):
			$output = array(
				"status" 	=> false,
				"message"	=> "import data failed",
				"hak_akses"	=> $this->session->hak_akses,
			);
		else:
			$media 			= $this->upload->data();
			$inputFileName 	= 'file/'.$media['file_name'];
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
			$list 		= array();

			$status 	= true;
			$message 	= "Success";

			for ($row = 2; $row <= $highestRow; $row++){
				$rowData 		= $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
				$Name 			= $rowData[0][0];
				$Position		= $rowData[0][1];

				if($Position == "Vendor"):
					$Position = 1;
				else:
					$Position = 2;
				endif;
				
				$cek_vendor	= $this->db->count_all("PS_Vendor where Name='$Name' && Position ='$Position' && CompanyID='$CompanyID'");

				$cek_vendor;
				if(!$Name):
					$status 	= false;
					$message 	= "Name cann't be null ";
					break;
				// elseif($cek_vendor>0): // cek jika update product type tidak boleh brubah
				// 	$status 	= false;
				// 	$message 	= "Sorry Name Already Exsist";
				// 	break;
				endif;

			}

			$arrData = array();
			if($status):
				for ($row = 2; $row <= $highestRow; $row++){
					$rowData 		= $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);

					$Name 			= $rowData[0][0];
					$Position		= $rowData[0][1];
					$Address 		= $rowData[0][2];
					$city 			= $rowData[0][3];
					$province 		= $rowData[0][4];
					$Phone 			= $rowData[0][5];
					$Email 			= $rowData[0][6];
					$npwp 			= $rowData[0][7];
					$ap_max 		= $rowData[0][8];
					$groupname 		= $rowData[0][9];
					$Remark 		= $rowData[0][10];

					if($Position == 'Vendor'):
			            $Position = "1";
			        else:
			            $Position = "2";
			        endif;

					$data = array(
						"Name"			=> $Name,
						"Position"		=> $Position,
						"Address"		=> $Address,
						"Phone"			=> $Phone,
						"Email"			=> $Email,
						"npwp"			=> $npwp,
						"ap_max"		=> $ap_max,
						"Remark"		=> $Remark,
						"Active"		=> 1,
						"CompanyID"		=> $CompanyID,
						"App"			=> 'pipesys',
					);

					$cek = $this->db->count_all("PS_Vendor where CompanyID = '$CompanyID' and Name = '$Name'");
					if($cek>0):
						$data['User_Ch'] = $this->session->NAMA;
						$data['Date_Ch'] = date("Y-m-d H:i:s");
						$this->db->where("CompanyID", $CompanyID);
						$this->db->where("Name", $Name);
						$this->db->update("PS_Vendor", $data);
						$vendorcode 	  = $this->main->get_one_column("PS_Vendor","Code",array("Name"	=> $Name, "CompanyID" => $CompanyID))->Code;
					else:
						$vendorcode 	  = $this->main->vendor_code_generate();
						$data['Code']	  = $vendorcode;
						$data['User_Add'] = $this->session->NAMA;
						$data['Date_Add'] = date("Y-m-d H:i:s");
						$this->db->insert("PS_Vendor", $data);
					endif;

					$this->check_address($vendorcode,$Address,$city,$province);
					$this->check_phone($vendorcode,$Phone,$Email);

					$arrData[] = $data;
				}
			endif;

			$output = array(
				"status" 	=> $status,
				"message"	=> $message,
				"hak_akses"	=> $this->session->hak_akses,
			);

			if (!unlink($inputFileName)):

			endif;
		endif;

		$this->main->echoJson($output);
	}

	public function check_address($vendorcode,$address,$city,$province,$VendorID=""){
		$CompanyID  = $this->session->CompanyID;
		$cek_vendor = $this->main->get_one_column("ps_vendor_address", "VendorAddressID", array("VendorCode" => $vendorcode, "CompanyID" => $CompanyID));
		$data = array(
			"Address"	=> $address,
			"City"		=> $city,
			"Province"	=> $province,
			"CompanyID"	=> $CompanyID,
		);
		if($cek_vendor):
			$ID = $cek_vendor->VendorAddressID;
			$data["User_Ch"]  		= $this->session->nama;
			$data["Date_Ch"]  		= date("Y-m-d H:i:s");
			if($VendorID):
				$data['VendorID']	= $VendorID;
			endif;
			$this->db->where("VendorAddressID",$ID);
			$this->db->update("ps_vendor_address",$data);
		else:
			$data["code"]  		= $this->main->vendor_address_code_generate();
			$data['VendorCode'] = $vendorcode;
			if($VendorID):
				$data['VendorID']	= $VendorID;
			endif;
			$data["User_Add"]  	= $this->session->nama;
			$data["Date_Add"]  	= date("Y-m-d H:i:s");
			$data['Delivery']	= 1;
			$data['Payment']	= 1;
			$this->db->insert("ps_vendor_address",$data);
		endif;
	}

	public function check_phone($vendorcode,$phone,$email,$VendorID=""){
		$CompanyID = $this->session->CompanyID;
		$cek_vendor = $this->main->get_one_column("ps_vendor_contact", "VendorContactID", array("VendorCode" => $vendorcode, "CompanyID" => $CompanyID));
		$data = array(
			"ContactPhone"	=> $phone,
			"Email"			=> $email,
			"CompanyID"		=> $CompanyID,
		);
		if($cek_vendor):
			$ID = $cek_vendor->VendorContactID;
			$data["User_Ch"]  		= $this->session->nama;
			$data["Date_Ch"]  		= date("Y-m-d H:i:s");
			if($VendorID):
				$data['VendorID']	= $VendorID;
			endif;
			$this->db->where("VendorContactID",$ID);
			$this->db->update("ps_vendor_contact",$data);
		else:
			$data["code"]  		= $this->main->vendor_phone_code_generate();
			$data['VendorCode'] = $vendorcode;
			if($VendorID):
				$data['VendorID']	= $VendorID;
			endif;
			$data["User_Add"]  	= $this->session->nama;
			$data["Date_Add"]  	= date("Y-m-d H:i:s");
			$this->db->insert("ps_vendor_contact",$data);
		endif;
	}

}
