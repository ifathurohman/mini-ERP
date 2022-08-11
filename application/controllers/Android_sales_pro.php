<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Android_sales_pro extends CI_Controller {
	
	var $table_branch = "Branch";

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_android",'android');
		$this->load->model("M_android_sales_pro", 'sales_pro');
		$this->load->model("M_ps_transaction_route",'transaction_route');

	}

	public function echoJson($data){
		header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);
	}

	public function version_app(){
		$version_name = $this->input->post("version_name");
		$version_code = (int) $this->input->post("version_code");
		// $version_code = 1;

		$query  = $this->android->version_app("sales_pro");
		$d 		= $query->row();
		$min_version_code = (int) $d->min_version_code;
		// $min_version_code = 2;

		if($version_code>=$min_version_code):
			$res = array(
				"status" 	=> true,
				"res_code"	=> 1,
				"message"	=> "Aplikasi Sales Pro bisa digunakan",
				);
		else:
			$res = array(
				"status" 	=> false,
				"res_code"	=> 0,
				"url"		=> 'https://play.google.com/store/apps/details?id=com.rc.sales_pro',
				"message"	=> "There is newer version of this application available, please update your application",
				);
		endif;


		$this->echoJson($res);
	}

	public function version_app_ascon(){
		$version_name = $this->input->post("version_name");
		$version_code = (int) $this->input->post("version_code");
		// $version_code = 1;

		$query  = $this->android->version_app("sales_pro_ascon");
		$d 		= $query->row();
		$min_version_code = (int) $d->min_version_code;
		// $min_version_code = 2;

		if($version_code>=$min_version_code):
			$res = array(
				"status" 	=> true,
				"res_code"	=> 1,
				"message"	=> "Aplikasi Sales Pro bisa digunakan",
				);
		else:
			$res = array(
				"status" 	=> false,
				"res_code"	=> 0,
				"url"		=> 'https://play.google.com/store/apps/details?id=com.rc.sales_pro',
				"message"	=> "There is newer version of this application available, please update your application",
				);
		endif;


		$this->echoJson($res);
	}

	public function syncron(){
		$res["status"] 		= TRUE;
		$res["res_code"]	= 1;
		$res["message"]		= "Sycnron Berhasil";

		$this->echoJson($res);
	}

	#login Branch dan token
	public function loginUser(){
		$Activation = $this->input->post("Activation");
		if($Activation == "email"):
			$this->loginEmailCompany();
		else:
			$this->loginActivationCode();
		endif;

	}

	public function loginToken(){
		$token 		= $this->input->post("token");
		$DeviceID	= $this->input->post("DeviceID");

		// $token 		= "149E5B";
		// $DeviceID 	= "123123";

		$tokenLeng 	= strlen($token);
		if($tokenLeng > 6):
			//Activation Email
			$res = $this->ActivationWithEmail($token);
		else:
			//Activation Code
			$res = $this->ActivationCode($token, $DeviceID);
		endif;
		
		$this->echoJson($res);
	}

	public function register(){
		$res = $this->main->register("android");
		$this->echoJson($res);
	}

	public function register_sales(){
		$email 		= $this->input->post("email");
		$password	= $this->input->post("password");
		$first_name = $this->input->post("first_name");
		$last_name 	= $this->input->post("last_name");
		$phone 		= $this->input->post("phone");
		$CompanyID 	= $this->input->post("CompanyID");

		// $email 		= "siapa@gmail.com";
		// $password 	= "123123";
		// $first_name = "siapa";
		// $last_name 	= "YA";
		// $phone 		= "089609974119";
		// $CompanyID 	= "iqbal@rcelectronic.net";

		$password 	= $this->main->hash($password);

		$CompanyLeng 	= strlen($CompanyID);
		if($CompanyLeng>6):
			$CompanyID = $this->sales_pro->getCompanyID($CompanyID);
		endif;

		if($this->db->count_all("user where id_user = '$CompanyID'")>0):
			if($this->db->count_all("Branch where Email='$email' and App='salespro' and Active = '1' and CompanyID!='$CompanyID' ")>0):
				$comanyName = "";
				$query = $this->sales_pro->getBranch($email, $CompanyID, "salespro");
				$res["status"] 		= FALSE;
				$res["res_code"]	= 0;
				$res["message"]		= $this->getCompanyName($query);
			else:
				if($this->db->count_all("Branch where Email='$email' and App='salespro' and CompanyID='$CompanyID' ")>0):
					$res["status"] 		= FALSE;
					$res["res_code"]	= 0;
					$res["message"]		= "Email address has been already exist";
				else:
					$data = array(
						'App'		=> "salespro",
						'CompanyID' => $CompanyID,
						'Email' 	=> $email,
						'FirstName' => $first_name,
						'LastName' 	=> $last_name,
						'Phone' 	=> $phone,
						'Name' 		=> $first_name." ".$last_name,
						'Password' 	=> $password,
						'User_Add'	=> $first_name." ".$last_name,
						"Date_Add"	=> date("Y-m-d H:i:s"),
						);
					$this->db->insert("Branch", $data);
					$res["status"] 		= TRUE;
					$res["res_code"]	= 1;
					$res["message"]		= "Employee Registration Success";
					$res["CompanyID"]	= $CompanyID;
					$res["CompanyName"] = $this->sales_pro->getCompanyName($CompanyID);
				endif;
			endif;
		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 2;
			$res["message"]		= "Company not found";
		endif;

		$this->echoJson($res);

	}

	private function getCompanyName($query){
		$comanyName = "";
		foreach ($query->result() as $d) {
			$comanyName .= $d->nama.", ";
		}

		return 'Email address has been taken as employee in '.$comanyName." Please contact the company administrator to deactivate email address";
	}

	public function send_email(){
		$email = $this->input->post("email");
		if($email):
			$res["status"] = TRUE;
			$this->main->send_email("register",$email);
		else:
			$res["status"] = FALSE;
		endif;

		$this->echoJson($res);
	}

	public function unlink(){
		$CompanyID 	= $this->input->post("CompanyID");
		$DeviceID 	= $this->input->post("DeviceID");
		$BranchID 	= $this->input->post("BranchID");
		$Location 	= $this->input->post("Location");

		// $CompanyID 	= "1";
		// $DeviceID 	= "123123";

		$cek = $this->db->count_all("Branch where CompanyID = '$CompanyID' AND DeviceID='$DeviceID'");
		if($cek>0):
			// $this->sales_pro->pushCurrentLocation($CompanyID,$BranchID,$Location);
			$res["status"] = true;
			$data = array(
				"DeviceID" 		=> Null,
				"Token"	=> Null,
				"User_Ch"		=> 'unlink android sales pro',
				"Date_Ch"		=> date("Y-m-d H:i:s"),
				);
			$this->db->where("CompanyID", $CompanyID);
			$this->db->where("DeviceID", $DeviceID);
			$this->db->update($this->table_branch, $data);
		else:
			$res["status"] = true;
		endif;

		$this->echoJson($res);
	}

	#transaction
	public function get_transaction_today(){
		$CompanyID 	= $this->input->post("CompanyID");
		$DeviceID 	= $this->input->post("DeviceID");
		$BranchID 	= $this->input->post("BranchID");

		// $CompanyID 	= "1";
		// $DeviceID 	= "7895022969680bcd";
		// $BranchID 	= 30;

		$cek 	= $this->db->count_all("Branch where CompanyID = '$CompanyID' AND DeviceID = '$DeviceID' AND BranchID = '$BranchID'");

		if($cek>0):
			$query = $this->sales_pro->transaction_today($CompanyID,$BranchID);
			
			//pengecekan expire account
			$data_where = array(
				"CompanyID"	=> $CompanyID,
				"BranchID"	=> $BranchID,
				);

			$data_expire = $this->checkExpire($data_where);

			$res["Selisih"]  		= $data_expire["Selisih"];
			$res["StatusAccount"]	= $data_expire["StatusAccount"];
			$res["Expire"]			= $data_expire["Expire"];
			$res["ExpireAccount"]	= $data_expire["ExpireAccount"];
			//end pengecekan expire account

			if($query->num_rows()>0):
				$res["status"] 		= TRUE;
				$res["res_code"]	= 1;
				$res["message"]		= "Berhasil get data";
				$res["date"] 		= date("Y-m-d H:i:s");
				$res["data"] 		= array();
				
				$no = 0;
				foreach ($query->result() as $d) {
					$no += 1;

					$status = 0;
					if($d->CheckOut != null):
						$status = 1;
					endif;

					$h["index"] 	= $no;
					$h["Code"] 		= $d->Code;
					$h["ID"] 		= $d->TransactionRouteDetailID;
					
					if($d->Name):
						$h["customer"] 	= $d->Name;
						$h["address"]	= $d->Address;
						$h["lat"] 		= $d->Lat;
						$h["lng"]		= $d->Lng;
						$h["Radius"] 	= $d->Radius;
					else:
						$h["customer"] 	= "Unknown";
						$h["address"]	= $d->CheckInAddress;
						$h["lat"] 		= $this->sales_pro->getLat($d->CheckInLatlng);
						$h["lng"]		= $this->sales_pro->getLng($d->CheckInLatlng);
						$h["Radius"] 	= "300";
					endif;

					$h["checkIn"]	= $d->CheckIn;
					$h["checkIn_latlng"] = $d->CheckInLatlng;
					$h["checkIn_address"]= $d->CheckInAddress;
					$h["checkOut"] 	= $d->CheckOut;
					$h["checkOut_latlng"] = $d->CheckOutLatlng;
					$h["checkOut_address"] = $d->CheckOutAddress;
					$h["status"]	= $status;
					$h["remark"] 	= $d->Remark;
					$h["remarkSales"] = $d->RemarkSales;

					array_push($res["data"], $h);
				}
			else:
				$res["status"] 		= FALSE;
				$res["res_code"]	= 2;
				$res["message"]		= "List today empty";
				$res["date"] 		= date("Y-m-d H:i:s");
			endif;
		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 0;
			$res["message"]		= "Your device is not active";
		endif;

		$this->echoJson($res);
	}
	public function transaction_detail(){
		$CompanyID 	= $this->input->post("CompanyID");
		$DeviceID 	= $this->input->post("DeviceID");
		$BranchID 	= $this->input->post("BranchID");
		$ID 		= $this->input->post("ID");

		// $CompanyID 	= "1";
		// $DeviceID 	= "7895022969680bcd";
		// $BranchID 	= 30;
		// $ID 		= 358;

		$cek 	= $this->db->count_all("Branch where CompanyID = '$CompanyID' AND DeviceID = '$DeviceID' AND BranchID = '$BranchID'");
		if($cek>0):
			$query = $this->sales_pro->transaction_detail($ID);
			if($query->num_rows()>0):
				$res["status"] 		= TRUE;
				$res["res_code"]	= 1;
				$res["message"]		= "Berhasil get data";
				$res["data"] 		= array();
				
				$no = 0;
				foreach ($query->result() as $d) {
					$no += 1;

					$status = 0;
					if($d->CheckOut):
						$status = 1;
					endif;

					$h["index"] 	= $no;
					$h["Code"] 		= $d->Code;
					$h["ID"] 		= $d->TransactionRouteDetailID;
					$h["customer"] 	= $d->Name;
					$h["address"]	= $d->Address;
					$h["lat"] 		= $d->Lat;
					$h["lng"]		= $d->Lng;
					$h["checkIn"]	= $d->CheckIn;
					$h["checkIn_latlng"] = $d->CheckInLatlng;
					$h["checkIn_address"]= $d->CheckInAddress;
					$h["checkOut"] 	= $d->CheckOut;
					$h["checkOut_latlng"] = $d->CheckOutLatlng;
					$h["checkOut_address"] = $d->CheckOutAddress;
					$h["status"]	= $status;
					$h["remark"] 	= $d->Remark;
					$h["remarkSales"] = $d->RemarkSales;
					$h["Radius"] 	= $d->Radius;

					array_push($res["data"], $h);
				}
			else:
				$res["status"] 		= FALSE;
				$res["res_code"]	= 2;
				$res["message"]		= "List today empty";
				
			endif;
		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 0;
			$res["message"]		= "Your device is not active";
		endif;

		$this->echoJson($res);
	}
	public function transactionNotCustomer(){
		$CompanyID 	= $this->input->post("CompanyID");
		$DeviceID 	= $this->input->post("DeviceID");
		$BranchID 	= $this->input->post("BranchID");
		$ID 		= $this->input->post("ID");

		// $CompanyID 	= "1";
		// $DeviceID 	= "7895022969680bcd";
		// $BranchID 	= 30;
		// $ID 		= 359;

		$cek 	= $this->db->count_all("Branch where CompanyID = '$CompanyID' AND DeviceID = '$DeviceID' AND BranchID = '$BranchID'");
		if($cek>0):
			$query = $this->sales_pro->transactionNotCustomer($ID);
			if($query->num_rows()>0):
				$res["status"] 		= TRUE;
				$res["res_code"]	= 1;
				$res["message"]		= "Berhasil get data";
				$res["data"] 		= array();
				
				$no = 0;
				foreach ($query->result() as $d) {
					$h["index"] 	= 10;
					$h["Code"] 		= $d->Code;
					$h["ID"] 		= $d->TransactionRouteDetailID;
					$h["customer"] 	= "-";
					$h["address"]	= $d->CheckInAddress;
					$h["lat"] 		= $this->sales_pro->getLat($d->CheckInLatlng);
					$h["lng" ]		= $this->sales_pro->getLng($d->CheckInLatlng);
					$h["checkIn"]			= $d->CheckIn;
					$h["checkIn_latlng"] 	= $d->CheckInLatlng;
					$h["checkIn_address"]	= $d->CheckInAddress;
					$h["checkOut"] 	= "";
					$h["checkOut_latlng"] = "";
					$h["checkOut_address"] = "";
					$h["status"]	= 0;
					$h["remark"] 	= "-";
					$h["remarkSales"] = "-";
					$h["Radius"] 	= "0";
					$h["remark"]	="-";

					array_push($res["data"], $h);
				}
			else:
				$res["status"] 		= FALSE;
				$res["res_code"]	= 2;
				$res["message"]		= "List today empty";
				
			endif;
		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 0;
			$res["message"]		= "Your device is not active";
		endif;

		$this->echoJson($res);
	}

	public function checkIn(){
		$CompanyID 	= $this->input->post("CompanyID");
		$DeviceID 	= $this->input->post("DeviceID");
		$BranchID 	= $this->input->post("BranchID");
		$address 	= $this->input->post("address");
		$latlng 	= $this->input->post("latlng");
		$id  		= $this->input->post("id");
		$radius 	= $this->input->post("radius");
		$title 		= $this->input->post("title");

		// $CompanyID 	= 1;
		// $DeviceID 	= "7895022969680bcd";
		// $BranchID 	= 30;
		// $address 	= "Jl. Indrayasa No.170, Cibaduyut, Bojongloa Kidul, Kota Bandung, Jawa Barat 40236, Indonesia";
		// $latlng 	= "-6.9521054,107.5954857";
		// $id 		= 21;

		$radius = round($radius, 0, PHP_ROUND_HALF_UP);
		$cek 	= $this->db->count_all("Branch where CompanyID = '$CompanyID' AND DeviceID = '$DeviceID' AND BranchID = '$BranchID'");
		if($cek>0):
			$data_Branch = array(
				"Check"		=> "in",
				"CheckTime"	=> date("Y-m-d H:i:s"),
				"CheckAddress"	=> $address,
				"User_Ch"	=> "Check In Android Sales Pro",
				"Date_Ch"	=> date("Y-m-d H:i:s"),
				);
			$this->updateBranch($data_Branch, $BranchID);

			$data_transaction = array(
				"CheckIn" 			=> date("Y-m-d H:i:s"),
				"CheckInAddress"	=> $address,
				"CheckInLatlng"		=> $latlng,
				"CheckInRadius"		=> $radius,
				"CheckInTitle"		=> $title,
				"Status"			=> "not",
				"UserCh"			=> $this->sales_pro->user_name($BranchID),
				"DateCh"			=> date("Y-m-d H:i:s"),
				);
			$this->db->where("TransactionRouteDetailID", $id);
			$this->db->update("SP_TransactionRouteDetail", $data_transaction);

			$res["status"] 		= TRUE;
			$res["res_code"]	= 1;
			$res["message"]		= "Berhasil get data";
		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 0;
			$res["message"]		= "Your device is not active";
		endif;
		$this->echoJson($res);
	}

	private function updateBranch($data, $BranchID){
		$this->db->where("BranchID", $BranchID);
		$this->db->update($this->table_branch, $data);
	}

	public function checkOut(){
		$CompanyID 	= $this->input->post("CompanyID");
		$DeviceID 	= $this->input->post("DeviceID");
		$BranchID 	= $this->input->post("BranchID");
		$address 	= $this->input->post("address");
		$latlng 	= $this->input->post("latlng");
		$id  		= $this->input->post("id");
		$radius 	= $this->input->post("radius");
		$title 		= $this->input->post("title");
		$duration 	= $this->input->post("duration");

		// $CompanyID 	= 1;
		// $DeviceID 	= "7895022969680bcd";
		// $BranchID 	= 30;
		// $address 	= "Jl. Indrayasa No.170, Cibaduyut, Bojongloa Kidul, Kota Bandung, Jawa Barat 40236, Indonesia";
		// $latlng 	= "-6.9521054,107.5954857";
		// $id 		= 2;
		// $radius 	= 7;
		// $title 		= "good";
		// $duration 	= "00:13:00";

		$radius = round($radius, 0, PHP_ROUND_HALF_UP);
		$cek 	= $this->db->count_all("Branch where CompanyID = '$CompanyID' AND DeviceID = '$DeviceID' AND BranchID = '$BranchID'");
		if($cek>0):
			$data_Branch = array(
				"Check"		=> "out",
				"CheckTime"	=> date("Y-m-d H:i:s"),
				"CheckAddress"	=> $address,
				"User_Ch"	=> "Check Out Android Sales Pro",
				"Date_Ch"	=> date("Y-m-d H:i:s"),
				);
			$this->updateBranch($data_Branch, $BranchID);

			$data_transaction = array(
				"CheckOut" 			=> date("Y-m-d H:i:s"),
				"CheckOutAddress"	=> $address,
				"CheckOutLatlng"	=> $latlng,
				"CheckOutRadius"	=> $radius,
				"CheckOutTitle"		=> $title,
				"Duration"			=> $duration,
				"Status"			=> "complete",
				"UserCh"			=> $this->sales_pro->user_name($BranchID),
				"DateCh"			=> date("Y-m-d H:i:s"),
				);
			$this->db->where("TransactionRouteDetailID", $id);
			$this->db->update("SP_TransactionRouteDetail", $data_transaction);

			$res["status"] 		= TRUE;
			$res["res_code"]	= 1;
			$res["message"]		= "Berhasil get data";
		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 0;
			$res["message"]		= "Your device is not active";
		endif;
		$this->echoJson($res);
	}

	public function notes(){
		$CompanyID 	= $this->input->post("CompanyID");
		$DeviceID 	= $this->input->post("DeviceID");
		$BranchID 	= $this->input->post("BranchID");
		$id  		= $this->input->post("id");
		$notes 		= $this->input->post("notes");

		$cek 	= $this->db->count_all("Branch where CompanyID = '$CompanyID' AND DeviceID = '$DeviceID' AND BranchID = '$BranchID'");
		if($cek>0):
			$res["status"] 		= TRUE;
			$res["res_code"]	= 1;
			$res["message"]		= "Berhasil get data";

			$data_transaction = array(
				"RemarkSales"	=> $notes,
				"UserCh"		=> $this->sales_pro->user_name($BranchID),
				"DateCh"		=> date("Y-m-d H:i:s"),
				);
			$this->db->where("TransactionRouteDetailID", $id);
			$this->db->update("SP_TransactionRouteDetail", $data_transaction);

		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 0;
			$res["message"]		= "Your device is not active";
		endif;

		$this->echoJson($res);
	}

	#user
	public function user(){
		$CompanyID 	= $this->input->post("CompanyID");
		$DeviceID 	= $this->input->post("DeviceID");
		$BranchID 	= $this->input->post("BranchID");

		// $CompanyID 	= 1;
		// $BranchID 	= 30;

		$cek = $this->db->count_all("Branch where CompanyID = '$CompanyID' AND BranchID = '$BranchID'");
		if($cek>0):
			$res["status"] 		= TRUE;
			$res["res_code"]	= 1;
			$res["message"]		= "Success";

			$data = array(
					"CompanyID"		=> $CompanyID,
					"BranchID" 		=> $BranchID,
					);
			$query 	= $this->sales_pro->Branch($data);
			$d  	= $query->row();

			$Phone 	= substr($d->Phone, 0,1);
			$Phone2 = substr($d->Phone, 1);
			if ($Phone == "0") {
				$Phone = "+62".$Phone2;
			}else{
				$Phone = "+62".$d->Phone;
			}

			$res["Name"]	= $d->Name;
			$res["Email"]	= $d->Email;
			$res["Phone"]	= $Phone;
			$res["AutoCheckOut"] = $d->AutoCheckOut;
			$res["StatusAccount"]= $d->StatusAccount;
			$res["ExpireAccount"]= $d->ExpireAccount;

		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 0;
			$res["message"]		= "Branch not found";
		endif;

		$this->echoJson($res);
	}

	public function updatePosition(){
		$CompanyID 	= $this->input->post("CompanyID");
		$DeviceID 	= $this->input->post("DeviceID");
		$BranchID 	= $this->input->post("BranchID");
		$latlng 	= $this->input->post("latlng");
		
		// $CompanyID 	= "1";
		// $DeviceID 	= "123123";
		// $BranchID 	= "30";

		$cek = $this->db->count_all("Branch where CompanyID = '$CompanyID' AND BranchID = '$BranchID' AND DeviceID='$DeviceID'");
		if($cek>0):
			$res["status"] 		= TRUE;
			$res["res_code"]	= 1;
			$res["message"]		= "Update Location";

			$lat 	 = $this->sales_pro->getLat($latlng);
			$lng 	 = $this->sales_pro->getLng($latlng);

			$address = $this->getaddress($lat,$lng);

			$data = array(
				"Lat" 		=> $lat,
				"Lng"		=> $lng,
				"User_Ch"	=> $this->sales_pro->user_name($BranchID),
				"Date_Ch"	=> date("Y-m-d H:i:s"),
				);
			if($address != null):
				$data["CheckAddress"] = $address;
			endif;

			$this->updateBranch($data,$BranchID);
		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 0;
			$res["message"]		= "Device is not active";
		endif;

		$this->echoJson($res);
	}

	#firebase
	//update firebase
	public function checkFirebase($BranchID, $token, $imei){
		$cek = $this->db->count_all("FirebaseUser where ID = '$BranchID'");
		if($cek>0):
			$data = array(
				"Token" 	=> $token,
				"Imei"		=> $imei,
				"UserCh"	=> $this->sales_pro->user_name($BranchID),
				"DateCh"	=> date("Y-m-d H:i:s"),
				);
			$this->updateFirebase($data,$BranchID);
		else:
			$data = array(
				"ID"		=> $BranchID,
				"Token" 	=> $token,
				"App" 		=> "salespro",
				"Imei"		=> $imei,
				"UserAdd"	=> $this->sales_pro->user_name($BranchID),
				"DateAdd"	=> date("Y-m-d H:i:s"),
				);
			$this->insertFirebase($data);
		endif;
	}

	private function insertFirebase($data){
		$this->db->insert("FirebaseUser", $data);
	}
	private function updateFirebase($data,$BranchID){
		$this->db->where("ID", $BranchID);
		$this->db->update("FirebaseUser", $data);
	}

	#history
	public function history(){
		$CompanyID 	= $this->input->post("CompanyID");
		$DeviceID 	= $this->input->post("DeviceID");
		$BranchID 	= $this->input->post("BranchID");
		$startDate 	= $this->input->post("start_date");
		$endDate 	= $this->input->post("end_date");

		if($startDate == ''):
			$startDate = date("Y-m-01");
		endif;
		if($endDate == ''):
			$endDate = date("Y-m-d");
		endif;

		// $CompanyID 	= "1";
		// $DeviceID 	= "7895022969680bcd";
		// $BranchID 	= 30;

		$cek = $this->db->count_all("Branch where CompanyID = '$CompanyID' AND BranchID = '$BranchID' AND DeviceID='$DeviceID'");
		if($cek>0):
			if($startDate<=$endDate):
				$query = $this->sales_pro->history($BranchID,$CompanyID);
				if($query->num_rows()>0):
					$res["status"] 		= TRUE;
					$res["res_code"]	= 1;
					$res["message"]		= "Success";
					$res["data"] 		= array();
					
					$total_route        = 0;
			        $total_route_miss   = 0;
			        $total_planning     = 0;
			        $total_not_planning = 0;
					foreach ($query->result() as $d) {
						$duration = "";
						if($d->CheckIn && $d->CheckOut):
			                $duration = $this->main->selisih_waktu(date("Y-m-d H:i",strtotime($d->CheckIn)),date("Y-m-d H:i",strtotime($d->CheckOut)));
			            endif;

			            $total_route = $total_route+1;
			            if($d->Name):
			                $total_planning = $total_planning+1;
			            else:
			                $total_not_planning = $total_not_planning+1;
			            endif;
			            if(!$d->CheckIn):
			                $total_route_miss = $total_route_miss+1;
			            endif;

						$h["Code"] 		= $d->Code;
						$h["ID"] 		= $d->TransactionRouteDetailID;
						$h["customer"] 	= $d->Name;
						$h["checkIn"]	= $d->CheckIn;
						$h["checkOut"] 	= $d->CheckOut;
						$h["date"]		= $d->Date;
						$h["duration"]	= $d->Duration;
						$h["duration2"] = $duration;
						$h["Address"]	= $d->Address;
						$h["RemarkSales"]	  = $d->RemarkSales;
						$h["CheckInAddress"]  = $d->CheckInAddress;
						$h["CheckOutAddress"] = $d->CheckOutAddress;
						$h["ImgSales"]	= $d->ImgSales;
						$h["Notes"]		= $d->RemarkSales;
						$attachment 	= array();
						if($d->Attachment != null):
							$attachment = json_decode($d->Attachment);
						endif;

						$h["Attachment"]= $attachment;

						array_push($res["data"], $h);
					}
					$res["total_route"]      		  = $total_route;
		            $res["total_route_miss"] 		  = $total_route_miss;
		            $res["total_route_planning"]      = $total_planning;
		            $res["total_route_not_planning"]  = $total_not_planning;
				else:
					$res["status"] 		= FALSE;
					$res["res_code"]	= 2;
					$res["message"]		= "Data Not Found";
				endif;
			else:
				$res["status"] 		= FALSE;
				$res["res_code"]	= 3;
				$res["message"]		= "date from must less than date to";
			endif;
		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 0;
			$res["message"]		= "Device is not active";
		endif;

		$this->echoJson($res);
	}
	public function updateAutoCheckOut(){
		$CompanyID 		= $this->input->post("CompanyID");
		$DeviceID 		= $this->input->post("DeviceID");
		$BranchID 		= $this->input->post("BranchID");
		$AutoCheckOut 	= $this->input->post("AutoCheckOut");

		$cek = $this->db->count_all("Branch where CompanyID = '$CompanyID' AND BranchID = '$BranchID' AND DeviceID='$DeviceID'");
		if($cek>0):
			$res["status"] 		= TRUE;
			$res["res_code"]	= 1;
			$res["message"]		= "Success";
			
			$data = array(
				"AutoCheckOut" 	=> $AutoCheckOut,
				"User_Ch"		=> $this->sales_pro->user_name($BranchID),
				"Date_Ch"		=> date("Y-m-d H:i:s"),
				);
			$this->updateBranch($data, $BranchID);

		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 0;
			$res["message"]		= "Device is not active";
		endif;

		$this->echoJson($res);
	}

	//check expire
	public function checkExpire($data){
		$data_expire = $this->sales_pro->data_expire($data);

		$status 	= $data_expire->StatusAccount;
		$expiredate = $data_expire->ExpireAccount;
		$time1		=strtotime(date("Y-m-d"));
		$time2		=strtotime($expiredate);
		
		if($expiredate < date("Y-m-d")):
			$expire = TRUE; // masa aktif sudah habis
		else:
			$expire = FALSE; // masa aktif masih ada
		endif;
		$selisih = ($time2-$time1)/(60*60*24);
		$res["StatusAccount"] 	= $status;
		$res["Expire"]			= $expire;
		$res["Selisih"]			= $selisih;
		$res["ExpireAccount"]	= $expiredate;

		return $res;
	}
	public function voucher(){
		$CompanyID 		= $this->input->post("CompanyID");
		$DeviceID 		= $this->input->post("DeviceID");
		$BranchID 		= $this->input->post("BranchID");
		$Voucher 		= $this->input->post("Voucher");

		// $CompanyID 		= 55;
		// $DeviceID 		= "ec408ab9d14a63f5";
		// $BranchID 		= 33;
		// $Voucher 		= "14B663";

		$cek = $this->db->count_all("Branch where CompanyID = '$CompanyID' AND BranchID = '$BranchID' AND DeviceID='$DeviceID'");
		if($cek>0):
			$voucher_data = $this->sales_pro->voucher($CompanyID, $Voucher);
			if($voucher_data->num_rows()>0):
				$d = $voucher_data->row();
				if($d->Status == "not"):
					$res["status"] 		= TRUE;
					$res["res_code"]	= 1;
					$res["message"]		= "Success transaction voucher";
					
					$Type = '+'.$d->Type." month";
					$expire = date('Y-m-d', strtotime($Type, strtotime(date("Y-m-d"))));

					$data = array(
						"BranchID"		=> $BranchID,
						"ExpireDate" 	=> $expire,
						"UseDate"		=> date("Y-m-d H:i:s"),
						"Status"		=> "used",
						"UserCh"		=> $this->sales_pro->user_name($BranchID),
						"DateCh"		=> date("Y-m-d H:i:s"),
						);
					$data_Branch = array(
						"StatusAccount" 	=> "active",
						"ExpireAccount"		=> $expire,
						"User_Ch"			=> $this->sales_pro->user_name($BranchID),
						"Date_Ch"			=> date("Y-m-d H:i:s"),
						);

					$this->db->where("Code", $Voucher);
					$this->db->where("CompanyID", $CompanyID);
					$this->db->update("VoucherDetail", $data);
					$this->updateBranch($data_Branch, $BranchID);
					$res["ExpireAccount"] = $expire;
				else:
					$res["status"] 		= FALSE;
					$res["res_code"]	= 0;
					$res["message"]		= "Voucher has been used";
				endif;
			else:
				$res["status"] 		= FALSE;
				$res["res_code"]	= 0;
				$res["message"]		= "Voucher not match";
			endif;

		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 0;
			$res["message"]		= "Device is not active";
		endif;

		$this->echoJson($res);
	}
	//end check expire

	//Customer
	public function customer(){
		$CompanyID 		= $this->input->post("CompanyID");
		$DeviceID 		= $this->input->post("DeviceID");
		$BranchID 		= $this->input->post("BranchID");

		// $CompanyID 	= "1";
		// $DeviceID 	= "7895022969680bcd";
		// $BranchID 	= 30;

		$cek = $this->db->count_all("Branch where CompanyID = '$CompanyID' AND BranchID = '$BranchID' AND DeviceID='$DeviceID'");
		if($cek>0):
			$query = $this->sales_pro->customer($CompanyID);
			if($query->num_rows()>0):
				$res["status"] 		= TRUE;
				$res["res_code"]	= 1;
				$res["message"]		= "Success";
				$res["data"]		= array();
				foreach ($query->result() as $d) {
					$h["VendorID"] 	= $d->VendorID;
					$h["Name"]		= $d->Name;
					$h["Address"]	= $d->Address;
					$h["Lat"]		= $d->Lat;
					$h["Lng"]		= $d->Lng;
					$h["Radius"]	= $d->Radius;

					array_push($res["data"], $h);
				}
			else:
				$res["status"] 		= FALSE;
				$res["res_code"]	= 2;
				$res["message"]		= "Customer not found";
			endif;
		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 0;
			$res["message"]		= "Device is not active";
		endif;

		$this->echoJson($res);
	}
	public function addCheckInCustomer(){
		$CompanyID 		= $this->input->post("CompanyID");
		$DeviceID 		= $this->input->post("DeviceID");
		$BranchID 		= $this->input->post("BranchID");
		$VendorID 		= $this->input->post("VendorID");
		$Address 		= $this->input->post("Address");
		$Latlng 		= $this->input->post("Latlng");
		$Radius 		= $this->input->post("Radius");
		$Title 			= $this->input->post("Title");


		// $CompanyID 	= "1";
		// $DeviceID 	= "7895022969680bcd";
		// $BranchID 	= 30;
		// $VendorID 	= 25;
		// $Address 	= "Jl. Indrayasa No.162, Cibaduyut, Bojongloa Kidul, Kota Bandung, Jawa Barat 40236, Indonesia";
		// $Latlng 	= "-6.9522048,107.5955376";
		// $Radius 	= 15.445438497083472;
		// $Title 		= "good";

		$Radius = round($Radius, 0, PHP_ROUND_HALF_UP);
		$cek = $this->db->count_all("Branch where CompanyID = '$CompanyID' AND BranchID = '$BranchID' AND DeviceID='$DeviceID'");
		if($cek>0):
			$data_Branch = array(
				"Check"			=> "in",
				"CheckTime"		=> date("Y-m-d H:i:s"),
				"CheckAddress"	=> $Address,
				"User_Ch"		=> "Check In Android Sales Pro",
				"Date_Ch"		=> date("Y-m-d H:i:s"),
				);
			$this->updateBranch($data_Branch, $BranchID);

			$Code 		= $this->main->transaction_route_code_generate($CompanyID);
			$user 		= $this->sales_pro->user_name($BranchID);
			$data_transaction = array(
				"CompanyID"		=> $CompanyID,
				"BranchID"		=> $BranchID,
				"Code"			=> $Code,
				"Date"			=> date("Y-m-d"),
				"Active"		=> 1,
				"Platform"		=> "android",
				"UserAdd"		=> $user,
				"DateAdd"		=> date("Y-m-d H:i:s"),
				);
			$insert = $this->transaction_route->save($data_transaction);
			$data_detail = array(
					"TransactionRouteID" 	=> $insert,
					"CompanyID"				=> $CompanyID,
					"VendorID"				=> $VendorID,
					"CheckIn"				=> date("Y-m-d H:i:s"),
					"CheckInAddress"		=> $Address,
					"CheckInLatlng"			=> $Latlng,
					"CheckInRadius"			=> $Radius,
					"CheckInTitle"			=> $Title,
					"UserAdd"				=> $user,
					"DateAdd"				=> date("Y-m-d H:i:s"),
				);
			$ID 	= $this->transaction_route->save_detail($data_detail);
			$res["status"] 		= TRUE;
			$res["res_code"]	= 1;
			$res["message"]		= "Success";
			$res["TransactionRouteID"] = $ID;
		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 0;
			$res["message"]		= "Device is not active";
		endif;

		$this->echoJson($res);
	}
	public function addCheckIn(){
		$CompanyID 		= $this->input->post("CompanyID");
		$DeviceID 		= $this->input->post("DeviceID");
		$BranchID 		= $this->input->post("BranchID");
		$Address 		= $this->input->post("Address");
		$Latlng 		= $this->input->post("Latlng");
		$Radius 		= $this->input->post("Radius");
		$Title 			= $this->input->post("Title");


		// $CompanyID 	= "1";
		// $DeviceID 	= "7895022969680bcd";
		// $BranchID 	= 30;
		// $VendorID 	= 25;
		// $Address 	= "Jl. Indrayasa No.162, Cibaduyut, Bojongloa Kidul, Kota Bandung, Jawa Barat 40236, Indonesia";
		// $Latlng 	= "-6.9522048,107.5955376";
		// $Radius 	= 15.445438497083472;
		// $Title 		= "good";

		$Radius = round($Radius, 0, PHP_ROUND_HALF_UP);
		$cek = $this->db->count_all("Branch where CompanyID = '$CompanyID' AND BranchID = '$BranchID' AND DeviceID='$DeviceID'");
		if($cek>0):
			$data_Branch = array(
				"Check"			=> "in",
				"CheckTime"		=> date("Y-m-d H:i:s"),
				"CheckAddress"	=> $Address,
				"User_Ch"		=> "Check In Android Sales Pro",
				"Date_Ch"		=> date("Y-m-d H:i:s"),
				);
			$this->updateBranch($data_Branch, $BranchID);

			$Code 		= $this->main->transaction_route_code_generate($CompanyID);
			$user 		= $this->sales_pro->user_name($BranchID);
			$data_transaction = array(
				"CompanyID"		=> $CompanyID,
				"BranchID"		=> $BranchID,
				"Code"			=> $Code,
				"Date"			=> date("Y-m-d"),
				"Active"		=> 1,
				"Platform"		=> "android",
				"UserAdd"		=> $user,
				"DateAdd"		=> date("Y-m-d H:i:s"),
				);
			$insert = $this->transaction_route->save($data_transaction);
			$data_detail = array(
					"TransactionRouteID" 	=> $insert,
					"CompanyID"				=> $CompanyID,
					"CheckIn"				=> date("Y-m-d H:i:s"),
					"CheckInAddress"		=> $Address,
					"CheckInLatlng"			=> $Latlng,
					"CheckInRadius"			=> $Radius,
					"CheckInTitle"			=> $Title,
					"UserAdd"				=> $user,
					"DateAdd"				=> date("Y-m-d H:i:s"),
				);
			$ID 	= $this->transaction_route->save_detail($data_detail);
			$res["status"] 		= TRUE;
			$res["res_code"]	= 1;
			$res["message"]		= "Success";
			$res["TransactionRouteID"] = $ID;
		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 0;
			$res["message"]		= "Device is not active";
		endif;

		$this->echoJson($res);
	}
	//end customer

	//upload image
	public function uploadImage(){
		$CompanyID 		= $this->input->post("CompanyID");
		$DeviceID 		= $this->input->post("DeviceID");
		$BranchID 		= $this->input->post("BranchID");
		$ID 			= $this->input->post("ID");
		$photo 			= $this->input->post("photo");

        $cek = $this->db->count_all("Branch where CompanyID = '$CompanyID' AND BranchID = '$BranchID' AND DeviceID='$DeviceID'");
		if($cek>0):
			$res["status"] 		= TRUE;
			$res["res_code"]	= 1;
			$res["message"]		= "Success";
			
			$path	= "img/sales_pro/android/";
			$nmfile = "pipesys_".$ID."_".date("ymd").".png";
			file_put_contents($path.$nmfile,base64_decode($photo));
			// $config['source_image'] = $path.$nmfile;
	  //       $config['quality'] = '50%';
	  //       $config['new_image'] = $path.$nmfile;
	  //       $config['maintain_ratio'] = TRUE;
	  //       $config['master_dim'] = 'width';
	  //       $config['width'] = 200;
	  //       $config['height'] = 150;
	  //       $this->image_lib->initialize($config);
	  //       $this->image_lib->resize();

			$data = array(
				"ImgSales" => $path.$nmfile,
				);
			$this->db->where("TransactionRouteDetailID", $ID);
			$this->db->update("SP_TransactionRouteDetail", $data);
	        
		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 0;
			$res["message"]		= "Device is not active";
		endif;

		$this->echoJson($res);
	}

	public function get_checkIn(){
		$CompanyID 		= $this->input->post("CompanyID");
		$DeviceID 		= $this->input->post("DeviceID");
		$BranchID 		= $this->input->post("BranchID");
		
		// $CompanyID 	= "1";
		// $DeviceID 	= "b077015307494f95";
		// $BranchID 	= 30;

        $cek = $this->db->count_all("Branch where CompanyID = '$CompanyID' AND BranchID = '$BranchID' AND DeviceID='$DeviceID'");
		if($cek>0):
			$query = $this->sales_pro->get_checkIn($CompanyID,$BranchID);
			if($query->num_rows()>0):
				$res["status"] 		= TRUE;
				$res["res_code"]	= 1;
				$res["message"]		= "Success";
				$res["data"]		= array();
				
				$d = $query->row();
				
				$h["ID"] 		= $d->TransactionRouteDetailID;
				$h["checkIn"]	= $d->CheckIn;

				array_push($res["data"], $h);
			else:
				$res["status"] 		= FALSE;
				$res["res_code"]	= 0;
				$res["message"]		= "Data not found";
			endif;
			
		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 0;
			$res["message"]		= "Device is not active";
		endif;

		$this->echoJson($res);
	}

	public function pushCurrentLocation(){
		$CompanyID 		= $this->input->post("CompanyID");
		$DeviceID 		= $this->input->post("DeviceID");
		$BranchID 		= $this->input->post("BranchID");
		$Location 		= $this->input->post("Location");
		
		// $CompanyID 	= "1";
		// $DeviceID 	= "7895022969680bcd";
		// $BranchID 	= 30;

        $cek = $this->db->count_all("Branch where CompanyID = '$CompanyID' AND BranchID = '$BranchID' AND DeviceID='$DeviceID'");
		if($cek>0):
			$this->sales_pro->pushCurrentLocation($CompanyID,$BranchID,$Location);
			$res["status"] 		= TRUE;
			$res["res_code"]	= 1;
			$res["message"]		= "Success";
		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 0;
			$res["message"]		= "Device is not active";
		endif;

		$this->echoJson($res);
	}

	function getaddress($lat,$lng)
	{
	    $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false';
	    $json = @file_get_contents($url);
	    $data=json_decode($json);
	    $status = $data->status;
	    if($status=="OK"){
	       return $data->results[0]->formatted_address;
	    }
	    else{
	       return null;
	    }
	}

	private function ActivationCode($token,$DeviceID){
		$cek 		= $this->db->count_all("Branch where Token = '$token'");
		if($cek>0):
			$res["status"] 		= TRUE;
			$res["res_code"]	= 1;
			$res["message"]		= "Success";
			$res["Activation"]	= "token";

			$this->sales_pro->unlinkDevice($DeviceID);
			
			$data  = array(
				"DeviceID" 		=> $DeviceID,
				"Token"			=> Null,
				"User_Ch"		=> "Login Android Sales Pro",
				"Date_Ch"		=> date("Y-m-d H:i:s"),
				);

			$query = $this->sales_pro->checkToken($token);
			$d = $query->row();
			
			$CompanyID 	= $d->CompanyID;
			$BranchID 	= $d->BranchID;

			$res["CompanyID"]	= $CompanyID;
			$res["BranchID"]	= $BranchID;
			$res["CompanyName"]	= $this->sales_pro->getCompanyName($CompanyID);
			$res["Image"]		= $this->sales_pro->getImage($d->CompanyID);
			$this->db->where("Token", $token);
			$this->db->update($this->table_branch, $data);//update token and device

			$data_where = array(
				"CompanyID"	=> $CompanyID,
				"BranchID"	=> $BranchID,
				);

			//check first use aplikasi
			$data_expire = $this->sales_pro->data_expire($data_where);
			$cek_first = $this->db->count_all("Branch where CompanyID = '$CompanyID' AND StatusAccount != 'none' AND App = 'salespro'");
			if($data_expire->StatusAccount == "none"):
				if($cek_first>4):
					$status 	= $data_expire->StatusAccount;
					$expire 	= $data_expire->ExpireAccount;
					if($expire < date("Y-m-d")):
						$expire = TRUE; // masa aktif sudah habis
					else:
						$expire = FALSE; // masa aktif masih ada
					endif;
					$res["StatusAccount"] 	= $status;
					$res["Expire"]			= $expire;
					$res["ExpireAccount"]	= $data_expire->ExpireAccount;
				else:
					$tgl = date('Y-m-d', strtotime('+30 days', strtotime(date("Y-m-d"))));
					$data_trial = array(
						"StatusAccount" => "trial",
						"ExpireAccount"	=> $tgl,
						"User_Ch"		=> $this->sales_pro->user_name($d->BranchID),
						"Date_Ch"		=> date("Y-m-d H:i:s"),
						);
					$this->db->where($data_where);
					$this->db->update($this->table_branch, $data_trial); // update sebagai trial
					$res["StatusAccount"] 	= "none";
					$res["ExpireAccount"]	= $tgl;
				endif;
			else:
				$status 	= $data_expire->StatusAccount;
				$expire 	= $data_expire->ExpireAccount;
				if($expire < date("Y-m-d")):
					$expire = TRUE; // masa aktif sudah habis
				else:
					$expire = FALSE; // masa aktif masih ada
				endif;
				$res["StatusAccount"] 	= $status;
				$res["Expire"]			= $expire;
				$res["ExpireAccount"]	= $data_expire->ExpireAccount;
			endif;
		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 0;
			$res["message"]		= "Company Email/Token not found";
		endif;

		return $res;
	}
	private function ActivationWithEmail($Email){
		$cek 		= $this->db->count_all("user where Email = '$Email' AND App = 'all' OR Email = '$Email' AND App = 'salespro' ");
		if($this->input->post("from") == "sales"):
			$cek = 0;
		endif;

		if($cek>0):
			$CompanyID = $this->sales_pro->getCompanyID($Email);
			$res["status"] 		= TRUE;
			$res["res_code"]	= 1;
			$res["message"]		= "Success Avtivation Email";
			$res["Activation"]	= "email";
			$res["CompanyID"]	= $CompanyID;
			$res["CompanyName"]	= $this->sales_pro->getCompanyName($CompanyID);
			$res["Image"]		= $this->sales_pro->getImage($CompanyID);
		else:
			$sales = $this->ActivationSales();
			if($sales["status"]):
				$res = $sales;
			else:
				$res["status"] 		= FALSE;
				$res["res_code"]	= 2;
				$res["message"]		= "Company Email/Token not found";
			endif;
		endif;

		return $res;
	}

	private function ActivationSales(){
		$token 		= $this->input->post("token");
		$DeviceID	= $this->input->post("DeviceID");
		$cek 		= $this->db->count_all("Branch where Email = '$token' AND Active = '1'");
		if($cek>0):
			$res["status"] 		= TRUE;
			$res["res_code"]	= 2;
			$res["message"]		= "Success Activation Seles";
			$res["Activation"]	= "token";

			$this->sales_pro->unlinkDevice($DeviceID);
			
			$data  = array(
				"DeviceID" 		=> $DeviceID,
				"Token"			=> Null,
				"User_Ch"		=> "Login Android Sales Pro",
				"Date_Ch"		=> date("Y-m-d H:i:s"),
				);

			$query = $this->sales_pro->checkToken($token,"email");
			$d = $query->row();
			
			$CompanyID 	= $d->CompanyID;
			$BranchID 	= $d->BranchID;

			$res["CompanyID"]	= $CompanyID;
			$res["BranchID"]	= $BranchID;
			$res["CompanyName"]	= $this->sales_pro->getCompanyName($CompanyID);
			$res["Image"]		= $this->sales_pro->getImage($d->CompanyID);
			$this->db->where("BranchID", $BranchID);
			$this->db->update($this->table_branch, $data);//update token and device

			$data_where = array(
				"CompanyID"	=> $CompanyID,
				"BranchID"	=> $BranchID,
				);

			//check first use aplikasi
			$data_expire = $this->sales_pro->data_expire($data_where);
			$cek_first = $this->db->count_all("Branch where CompanyID = '$CompanyID' AND StatusAccount != 'none' AND App = 'salespro'");
			if($data_expire->StatusAccount == "none"):
				if($cek_first>4):
					$status 	= $data_expire->StatusAccount;
					$expire 	= $data_expire->ExpireAccount;
					if($expire < date("Y-m-d")):
						$expire = TRUE; // masa aktif sudah habis
					else:
						$expire = FALSE; // masa aktif masih ada
					endif;
					$res["StatusAccount"] 	= $status;
					$res["Expire"]			= $expire;
					$res["ExpireAccount"]	= $data_expire->ExpireAccount;
				else:
					$tgl = date('Y-m-d', strtotime('+30 days', strtotime(date("Y-m-d"))));
					$data_trial = array(
						"StatusAccount" => "trial",
						"ExpireAccount"	=> $tgl,
						"User_Ch"		=> $this->sales_pro->user_name($d->BranchID),
						"Date_Ch"		=> date("Y-m-d H:i:s"),
						);
					$this->db->where($data_where);
					$this->db->update($this->table_branch, $data_trial); // update sebagai trial
					$res["StatusAccount"] 	= "none";
					$res["ExpireAccount"]	= $tgl;
				endif;
			else:
				$status 	= $data_expire->StatusAccount;
				$expire 	= $data_expire->ExpireAccount;
				if($expire < date("Y-m-d")):
					$expire = TRUE; // masa aktif sudah habis
				else:
					$expire = FALSE; // masa aktif masih ada
				endif;
				$res["StatusAccount"] 	= $status;
				$res["Expire"]			= $expire;
				$res["ExpireAccount"]	= $data_expire->ExpireAccount;
			endif;
		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 0;
			$res["message"]		= "Company Email found";
		endif;

		return $res;
	}

	//2018-05-11 MW
	//login berdasarkan activation dari kode token
	private function loginActivationCode(){
		$CompanyID 	= $this->input->post("CompanyID");
		$DeviceID 	= $this->input->post("DeviceID");
		$Email 		= $this->input->post("Email");
		$Password 	= $this->input->post("Password");
		$token 		= $this->input->post("tokenFirebase");
		$imei 		= $this->input->post("imei");
		$Activation = $this->input->post("Activation");

		// $CompanyID 	= "1";
		// $DeviceID 	= "7895022969680bcd";
		// $Email 		= "wildan@rcelectronic.net";
		// $Password 	= "123123";
		// $token 		= "c_-_kKnTH-Q:APA91bEbnieDyam_1DLDYYPkhxBNzS8N9onAnR9hP-bYRD2az1Wcy5Fhzdz3nPixerEysYnRsGrI_uuTgvfLD7Kyxxs1g-a2wh0uMCPfql03HdyAuf6T4X0wVl9zHfoFy4S2Vk4Uk6Pa";
		// $imei 		= "354085075387665";
		
		$Password 	= $this->main->hash($Password);
		$cek = $this->db->count_all("Branch where CompanyID = '$CompanyID' AND DeviceID = '$DeviceID'");
		if($cek>0):
			$cek_Branch = $this->db->count_all("Branch where CompanyID = '$CompanyID' AND DeviceID='$DeviceID' AND Email = '$Email' AND App != 'pipesys'");
			if($cek_Branch>0):
				$data = array(
					"CompanyID"		=> $CompanyID,
					"DeviceID"		=> $DeviceID,
					"Email" 		=> $Email,
					);
				$query 	= $this->sales_pro->Branch($data);
				$d  	= $query->row();

				$Active = $d->Active;

				if($d->Password == $Password):
					if($Active == 1):
						$res["status"] 		= TRUE;
						$res["res_code"]	= 1;
						$res["message"] 	= "Berhasil";
						$res["BranchID"] 	= $d->BranchID;
						$res["Name"] 		= $d->Name;
						$res["Email"]		= $Email;
						$res["AutoCheckOut"]  = $d->AutoCheckOut;
						$res["ExpireAccount"] = $d->ExpireAccount;
						$this->checkFirebase($d->BranchID, $token, $imei);
					else:
						$res["status"] 		= FALSE;
						$res["res_code"]	= 0;
						$res["message"] 	= "Acccount is not Active";
					endif;
				else:
					$res["status"] 		= FALSE;
					$res["res_code"]	= 0;
					$res["message"] 	= "Wrong Password";
				endif;

			else:
				$res["status"] 		= FALSE;
				$res["res_code"]	= 0;
				$res["message"] 	= "Account and Device is not compatible. Please unlink for switch account.";
			endif;
		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 0;
			$res["message"] 	= "Your device is not active";
		endif;

		$this->echoJson($res);
	}

	//2018-05-11 MW
	//login berdasarkan activation dari email company
	private function loginEmailCompany(){
		$CompanyID 	= $this->input->post("CompanyID");
		$DeviceID 	= $this->input->post("DeviceID");
		$Email 		= $this->input->post("Email");
		$Password 	= $this->input->post("Password");
		$token 		= $this->input->post("tokenFirebase");
		$imei 		= $this->input->post("imei");
		$Activation = $this->input->post("Activation");

		// $CompanyID 	= "1";
		// $DeviceID 	= "7895022969680bcd";
		// $Email 		= "wildan@rcelectronic.net";
		// $Password 	= "123123";
		// $token 		= "c_-_kKnTH-Q:APA91bEbnieDyam_1DLDYYPkhxBNzS8N9onAnR9hP-bYRD2az1Wcy5Fhzdz3nPixerEysYnRsGrI_uuTgvfLD7Kyxxs1g-a2wh0uMCPfql03HdyAuf6T4X0wVl9zHfoFy4S2Vk4Uk6Pa";
		// $imei 		= "354085075387665";

		$Password 	= $this->main->hash($Password);
		$cekCompany = $this->db->count_all("user where id_user = '$CompanyID'");
		if($cekCompany>0):
			$cekActive = $this->db->count_all("user where id_user = '$CompanyID' AND status = '1'");
			if($cekActive>0):
				$data = array(
					"CompanyID"		=> $CompanyID,
					"Email" 		=> $Email,
					);
				$query 	= $this->sales_pro->Branch($data);
				if($query->num_rows()>0):
					$d  	= $query->row();
					$Active = $d->Active;
					if($d->Password == $Password):
						if($Active == 1):
							$res["status"] 		= TRUE;
							$res["res_code"]	= 1;
							$res["message"] 	= "Berhasil";
							$res["BranchID"] 	= $d->BranchID;
							$res["Name"] 		= $d->Name;
							$res["Email"]		= $Email;
							$res["AutoCheckOut"] = $d->AutoCheckOut;

							$this->checkFirebase($d->BranchID, $token, $imei);

							$data_Branch  = array(
								"DeviceID" 		=> $DeviceID,
								"Token"			=> Null,
								"User_Ch"		=> "Login Android Sales Pro",
								"Date_Ch"		=> date("Y-m-d H:i:s"),
								);
							$this->db->where("BranchID", $d->BranchID);
							$this->db->update($this->table_branch, $data_Branch);//update token and device

							//check first use aplikasi
							$data_where = array(
								"CompanyID"	=> $CompanyID,
								"BranchID"	=> $d->BranchID,
								);
							$data_expire = $this->sales_pro->data_expire($data_where);
							$cek_first = $this->db->count_all("Branch where CompanyID = '$CompanyID' AND StatusAccount != 'none' AND App = 'salespro'");
							
							if($data_expire->StatusAccount == "none"):
								if($cek_first>4):
									$status 	= $data_expire->StatusAccount;
									$expire 	= $data_expire->ExpireAccount;
									if($expire < date("Y-m-d")):
										$expire = TRUE; // masa aktif sudah habis
									else:
										$expire = FALSE; // masa aktif masih ada
									endif;
									$res["StatusAccount"] 	= $status;
									$res["Expire"]			= $expire;
									$res["ExpireAccount"]	= $data_expire->ExpireAccount;
								else:
									$tgl = date('Y-m-d', strtotime('+30 days', strtotime(date("Y-m-d"))));
									$data_trial = array(
										"StatusAccount" => "trial",
										"ExpireAccount"	=> $tgl,
										"User_Ch"		=> $this->sales_pro->user_name($d->BranchID),
										"Date_Ch"		=> date("Y-m-d H:i:s"),
										);
									$this->db->where($data_where);
									$this->db->update($this->table_branch, $data_trial); // update sebagai trial
									$res["StatusAccount"] 	= "none";
									$res["ExpireAccount"]	= $tgl;
								endif;
							else:
								$status 	= $data_expire->StatusAccount;
								$expire 	= $data_expire->ExpireAccount;
								if($expire < date("Y-m-d")):
									$expire = TRUE; // masa aktif sudah habis
								else:
									$expire = FALSE; // masa aktif masih ada
								endif;
								$res["StatusAccount"] 	= $status;
								$res["Expire"]			= $expire;
								$res["ExpireAccount"]	= $data_expire->ExpireAccount;
							endif;

						else:
							$res["status"] 		= FALSE;
							$res["res_code"]	= 0;
							$res["message"] 	= "Account is not Active";
						endif;
					else:
						$res["status"] 		= FALSE;
						$res["res_code"]	= 0;
						$res["message"] 	= "Wrong Password";
					endif;
				else:
					$res["status"] 		= FALSE;
					$res["res_code"]	= 0;
					$res["message"] 	= "Employee Account not found";
				endif;
			else:
				$res["status"] 		= FALSE;
				$res["res_code"]	= 0;
				$res["message"] 	= "Your account has not been activated. Please check your registered email to activate Company Account.";
			endif;
		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 0;
			$res["message"] 	= "Company not registered";
		endif;

		$this->echoJson($res);
	}

	public function attachment(){
		$CompanyID 		= $this->input->post("CompanyID");
		$DeviceID 		= $this->input->post("DeviceID");
		$BranchID 		= $this->input->post("BranchID");
		$ID 			= $this->input->post("ID");
		$photo 			= $this->input->post("photo");

		// $ID 			= 405;
		// $CompanyID 		= 1;
		// $BranchID 		= 30;
		// $DeviceID 		= "b077015307494f95";


        $cek = $this->db->count_all("Branch where CompanyID = '$CompanyID' AND BranchID = '$BranchID' AND DeviceID='$DeviceID'");
		if($cek>0):
			$query = $this->sales_pro->transaction_detail($ID);
			$d = $query->row();

			$attachment = array();
			if($d->Attachment != null):
				$attachment = json_decode($d->Attachment);
			endif;

			if(count($attachment)<6):
				$res["status"] 		= TRUE;
				$res["res_code"]	= 1;
				$res["message"]		= "Success";
				
				$path	= "img/sales_pro/android/attachment/";
				$nmfile = "salespro_".$ID."_".date("ymdHis").".png";
				file_put_contents($path.$nmfile,base64_decode($photo));

				array_push($attachment, $path.$nmfile);

				sort($attachment);
				$data = array(
					"Attachment" => json_encode($attachment),
					);

				$this->db->where("TransactionRouteDetailID", $ID);
				$this->db->update("SP_TransactionRouteDetail", $data);
			else:
				$res["status"] 		= FALSE;
				$res["res_code"]	= 0;
				$res["message"]		= "MAX 6 Attachment";
			endif;
	        
		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 0;
			$res["message"]		= "Device is not active";
		endif;

		$this->echoJson($res);
	}

	public function get_attachment(){
		$CompanyID 		= $this->input->post("CompanyID");
		$DeviceID 		= $this->input->post("DeviceID");
		$BranchID 		= $this->input->post("BranchID");
		$ID 			= $this->input->post("ID");

		// $ID 			= 406;
		// $CompanyID 		= 1;
		// $BranchID 		= 30;
		// $DeviceID 		= "7895022969680bcd";


        $cek = $this->db->count_all("Branch where CompanyID = '$CompanyID' AND BranchID = '$BranchID' AND DeviceID='$DeviceID'");
		if($cek>0):
			$query = $this->sales_pro->transaction_detail($ID);
			$d = $query->row();

			$res["status"] 		= TRUE;
			$res["res_code"]	= 1;
			$res["message"]		= "Success";

			$attachment = array();
			if($d->Attachment != null):
				$attachment = json_decode($d->Attachment);
			endif;
			$res["data"] 		= $attachment;
	        
		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 0;
			$res["message"]		= "Device is not active";
		endif;

		$this->echoJson($res);
	}

	public function delete_attachment(){
		$CompanyID 		= $this->input->post("CompanyID");
		$DeviceID 		= $this->input->post("DeviceID");
		$BranchID 		= $this->input->post("BranchID");
		$ID 			= $this->input->post("ID");
		$data 			= $this->input->post("data");
		$data 			= json_decode($data);
		$data 			= $data->data;

		// $ID 			= 406;
		// $CompanyID 		= 1;
		// $BranchID 		= 30;
		// $DeviceID 		= "7895022969680bcd";


        $cek = $this->db->count_all("Branch where CompanyID = '$CompanyID' AND BranchID = '$BranchID' AND DeviceID='$DeviceID'");
		if($cek>0):
			$res["status"] 		= TRUE;
			$res["res_code"]	= 1;
			$res["message"]		= "Success";
			$query = $this->sales_pro->transaction_detail($ID);
			$d = $query->row();
			$attachment = array();
			if($d->Attachment != null):
				$attachment = json_decode($d->Attachment);
			endif;
			$attachment = array_diff($attachment, $data);
			foreach ($data as $key => $value) {
				$gambar_url = base_url($value);
				$root       = explode(base_url(), $gambar_url)[1];
	            $headers 	= @get_headers($gambar_url);
	            if (preg_match("|200|", $headers[0])) {
	                unlink('./' . $root);
	            }
			}
			sort($attachment);
			if(count($attachment) == 0):
				$data_set = array(
					"Attachment" => null,
				);
			else:
				$data_set = array(
					"Attachment" => json_encode($attachment),
				);
			endif;
			$this->db->where("TransactionRouteDetailID", $ID);
			$this->db->update("SP_TransactionRouteDetail", $data_set);
		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 0;
			$res["message"]		= "Device is not active";
		endif;

		$this->echoJson($res);
	}

	public function send_file(){
		$CompanyID 		= $this->input->post("CompanyID");
		$DeviceID 		= $this->input->post("DeviceID");
		$BranchID 		= $this->input->post("BranchID");
		$ID 			= $this->input->post("ID");
		$File 			= $this->input->post("File");
		$FileName 		= $this->input->post("FileName");

		// $ID 			= 405;
		// $CompanyID 		= 1;
		// $BranchID 		= 30;
		// $DeviceID 		= "b077015307494f95";


        $cek = $this->db->count_all("Branch where CompanyID = '$CompanyID' AND BranchID = '$BranchID' AND DeviceID='$DeviceID'");
		if($cek>0):
			$res["status"] 		= TRUE;
			$res["res_code"]	= 1;
			$res["message"]		= "Success";
			$this->main->send_email("send_file", $BranchID);
	        
		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 0;
			$res["message"]		= "Device is not active";
		endif;

		$this->echoJson($res);
	}


	// _______________________________________________________________________________________________________

	#clear data dengan id
	public function clearData($CompanyID){
		$res["PS_Vendor"] 		= $this->deletePS_Vendor($CompanyID);
		$res["Broadcast"]		= $this->deleteBroadCast($CompanyID);
		$res["SP_BranchRoute"]	= $this->deleteSP_BranchRoute($CompanyID);
		$res["SP_TransactionRoute"] = $this->deleteSP_TransactionRoute($CompanyID);
		$res["SP_TransactionRouteDetail"] = $this->deleteSP_TransactionRouteDetail($CompanyID);
		$res["Voucher"]			= $this->deleteVoucher($CompanyID);
		$res["VoucherDetail"]	= $this->deleteVoucherDetail($CompanyID);
		$res["Branch"]			= $this->deleteBranch($CompanyID);
		$res["SettingParameter"]= $this->deleteSettingParameter($CompanyID);
		$res["ps_product"]		= $this->deleteps_product($CompanyID);
		$res["PS_Product_Branch"]= $this->deletePS_Product_Branch($CompanyID);
		$res["PS_Product_Serial"]= $this->deletePS_Product_Serial($CompanyID);
		$res["user"]			= $this->deleteuser($CompanyID);

		$this->echoJson($res);
	}

	private function deletePS_Vendor($CompanyID){
		$cek = $this->db->count_all("PS_Vendor where CompanyID = '$CompanyID'");
		if($cek>0):
			$this->db->where("CompanyID", $CompanyID);
			$this->db->delete("PS_Vendor");
			$res["status"] 	= TRUE;
			$res["message"]	= "data ps vendor di hapus";
		else:
			$res["status"] 	= FALSE;
			$res["message"]	= "data tidak ada";
		endif;
		return $res;
	}

	private function deleteBroadCast($CompanyID){
		$cek = $this->db->count_all("Broadcast where CompanyID = '$CompanyID'");
		if($cek>0):
			$this->db->where("CompanyID", $CompanyID);
			$this->db->delete("Broadcast");
			$res["status"] 	= TRUE;
			$res["message"]	= "data Broadcast di hapus";
		else:
			$res["status"] 	= FALSE;
			$res["message"]	= "data Broadcast tidak ada";
		endif;
		return $res;
	}

	private function deleteSP_BranchRoute($CompanyID){
		$cek = $this->db->count_all("SP_BranchRoute where CompanyID = '$CompanyID'");
		if($cek>0):
			$this->db->where("CompanyID", $CompanyID);
			$this->db->delete("SP_BranchRoute");
			$res["status"] 	= TRUE;
			$res["message"]	= "data SP_BranchRoute di hapus";
		else:
			$res["status"] 	= FALSE;
			$res["message"]	= "data SP_BranchRoute tidak ada";
		endif;
		return $res;
	}

	private function deleteSP_TransactionRoute($CompanyID){
		$cek = $this->db->count_all("SP_TransactionRoute where CompanyID = '$CompanyID'");
		if($cek>0):
			$this->db->where("CompanyID", $CompanyID);
			$this->db->delete("SP_TransactionRoute");
			$res["status"] 	= TRUE;
			$res["message"]	= "data SP_TransactionRoute di hapus";
		else:
			$res["status"] 	= FALSE;
			$res["message"]	= "data SP_TransactionRoute tidak ada";
		endif;
		return $res;
	}

	private function deleteSP_TransactionRouteDetail($CompanyID){
		$cek = $this->db->count_all("SP_TransactionRouteDetail where CompanyID = '$CompanyID'");
		if($cek>0):
			$this->db->where("CompanyID", $CompanyID);
			$this->db->delete("SP_TransactionRouteDetail");
			$res["status"] 	= TRUE;
			$res["message"]	= "data SP_TransactionRouteDetail di hapus";
		else:
			$res["status"] 	= FALSE;
			$res["message"]	= "data SP_TransactionRouteDetail tidak ada";
		endif;
		return $res;
	}

	private function deleteVoucher($CompanyID){
		$cek = $this->db->count_all("Voucher where CompanyID = '$CompanyID'");
		if($cek>0):
			$this->db->where("CompanyID", $CompanyID);
			$this->db->delete("Voucher");
			$res["status"] 	= TRUE;
			$res["message"]	= "data Voucher di hapus";
		else:
			$res["status"] 	= FALSE;
			$res["message"]	= "data Voucher tidak ada";
		endif;
		return $res;
	}

	private function deleteVoucherDetail($CompanyID){
		$cek = $this->db->count_all("VoucherDetail where CompanyID = '$CompanyID'");
		if($cek>0):
			$this->db->where("CompanyID", $CompanyID);
			$this->db->delete("VoucherDetail");
			$res["status"] 	= TRUE;
			$res["message"]	= "data VoucherDetail di hapus";
		else:
			$res["status"] 	= FALSE;
			$res["message"]	= "data VoucherDetail tidak ada";
		endif;
		return $res;
	}

	private function deleteBranch($CompanyID){
		$cek = $this->db->count_all("Branch where CompanyID = '$CompanyID'");
		if($cek>0):
			$this->db->where("CompanyID", $CompanyID);
			$this->db->delete("Branch");
			$res["status"] 	= TRUE;
			$res["message"]	= "data Branch di hapus";
		else:
			$res["status"] 	= FALSE;
			$res["message"]	= "data Branch tidak ada";
		endif;
		return $res;
	}

	private function deleteSettingParameter($CompanyID){
		$cek = $this->db->count_all("SettingParameter where CompanyID = '$CompanyID'");
		if($cek>0):
			$this->db->where("CompanyID", $CompanyID);
			$this->db->delete("SettingParameter");
			$res["status"] 	= TRUE;
			$res["message"]	= "data SettingParameter di hapus";
		else:
			$res["status"] 	= FALSE;
			$res["message"]	= "data SettingParameter tidak ada";
		endif;
		return $res;
	}

	private function deleteuser($CompanyID){
		$cek = $this->db->count_all("user where id_user = '$CompanyID'");
		if($cek>0):
			$this->db->where("id_user", $CompanyID);
			$this->db->delete("user");
			$res["status"] 	= TRUE;
			$res["message"]	= "data user di hapus";
		else:
			$res["status"] 	= FALSE;
			$res["message"]	= "data user tidak ada";
		endif;
		return $res;
	}

	private function deleteps_product($CompanyID){
		$cek = $this->db->count_all("ps_product where CompanyID = '$CompanyID'");
		if($cek>0):
			$this->db->where("CompanyID", $CompanyID);
			$this->db->delete("ps_product");
			$res["status"] 	= TRUE;
			$res["message"]	= "data ps_product di hapus";
		else:
			$res["status"] 	= FALSE;
			$res["message"]	= "data ps_product tidak ada";
		endif;
		return $res;
	}

	private function deletePS_Product_Branch($CompanyID){
		$cek = $this->db->count_all("PS_Product_Branch where CompanyID = '$CompanyID'");
		if($cek>0):
			$this->db->where("CompanyID", $CompanyID);
			$this->db->delete("PS_Product_Branch");
			$res["status"] 	= TRUE;
			$res["message"]	= "data PS_Product_Branch di hapus";
		else:
			$res["status"] 	= FALSE;
			$res["message"]	= "data PS_Product_Branch tidak ada";
		endif;
		return $res;
	}

	private function deletePS_Product_Serial($CompanyID){
		$cek = $this->db->count_all("PS_Product_Serial where CompanyID = '$CompanyID'");
		if($cek>0):
			$this->db->where("CompanyID", $CompanyID);
			$this->db->delete("PS_Product_Serial");
			$res["status"] 	= TRUE;
			$res["message"]	= "data PS_Product_Serial di hapus";
		else:
			$res["status"] 	= FALSE;
			$res["message"]	= "data PS_Product_Serial tidak ada";
		endif;
		return $res;
	}
	#end clear data

	public function tes(){
		$data = '["img\/sales_pro\/android\/attachment\/salespro_0000000000.png","img\/sales_pro\/android\/attachment\/salespro_414_180608125309.png","img\/sales_pro\/android\/attachment\/salespro_414_180608125317.png","img\/sales_pro\/android\/attachment\/salespro_414_180608125310.png"]';
		$data = json_decode($data);

		$data2 = '["img\/sales_pro\/android\/attachment\/salespro_0000000000.png"]';
		$data2 = json_decode($data2);

		$data = array_diff($data, $data2);
  		sort($data);

  		$this->echoJson($data);
	}
}