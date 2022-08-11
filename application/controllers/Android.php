<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Android extends CI_Controller {
	var $companyID = "";
	var $branchID  = "";
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_android",'android');
		$this->load->model("M_main", "main");
	}

	public function echoJson($data){
		header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);
	}

	public function version_app(){
		$version_name = $this->input->post("version_name");
		$version_code = (int) $this->input->post("version_code");
		// $version_code = 1;

		$query  = $this->android->version_app("pipesys");
		$d 		= $query->row();
		$min_version_code = (int) $d->min_version_code;
		// $min_version_code = 1;

		if($version_code>=$min_version_code):
			$res = array(
				"status" 	=> true,
				"res_code"	=> 1,
				"message"	=> "Aplikasi bisa digunakan",
				);
		else:
			$res = array(
				"status" 	=> false,
				"res_code"	=> 0,
				"url"		=> site_url(''),
				"message"	=> "Application is not used",
				);
		endif;


		$this->echoJson($res);
	}

	public function tokenDevice(){
		$token 		= $this->input->post("token");
		$deviceID 	= $this->input->post("deviceID");
		
		// $token 		= "1488D8";
		// $deviceID 	= "b077015307494f95";

		$cek = $this->db->count_all("Branch where Token = '$token'");
		if($cek>0):
			$query = $this->android->tokenDevice($token);
			$rslt = $query->result_array();
			$res = array(
				"status" 	=> true,
				"res_code"	=> 1,
				"message"	=> "Token Device cocok",
				);
			$d = $query->row();
			$res["branchID"]   	= $d->BranchID;
			$res["branchCode"] 	= $d->Code;
			$res["lastBranchCode"] = intval(substr($rslt[0]["Code"],strlen(date("Y"))));
			$res["Name"]	   	= $d->Name;
			$res["companyID"]  	= $d->CompanyID;
			$res["nama"]	   	= $d->nama;
			if($d->img_url):
				$res["img_url"]		= site_url().$d->img_url;
			else:
				$res["img_url"]		= site_url("img/rc.png");
			endif;

			$data = array(
				"DeviceID" 	=> $deviceID,
				"Token"		=> '',
				"User_Ch"	=> "android user",
				"Date_Ch"	=> date("Y-m-d H:i:s"),
				);
			//check first use aplikasi
			$cek_first = $this->db->count_all("Branch where CompanyID = '$d->CompanyID' AND StatusAccount != 'none' AND App = 'pipesys'");
			if($cek_first>0):
				$data_where = array(
					"CompanyID"	=> $d->CompanyID,
					"BranchID"	=> $d->BranchID,
					);
				$data_expire = $this->android->data_expire($data_where);

				$status 	= $data_expire->StatusAccount;
				$expire 	= $data_expire->ExpireAccount;
				if($expire < date("Y-m-d")):
					$expire = TRUE; // masa aktif sudah habis
				else:
					$expire = FALSE; // masa aktif masih ada
				endif;
				$res["StatusAccount"] 	= $status;
				$res["Expire"]			= $expire;
			else:
				$tgl = date('Y-m-d', strtotime('+1 month', strtotime(date("Y-m-d"))));
				$data["StatusAccount"] = "trial";
				$data["ExpireAccount"] = $tgl;
				$res["StatusAccount"]  = "first";
			endif;

			$this->db->where("Token", $token);
			$this->db->update("Branch", $data);

		else:
			$res = array(
				"status" 	=> false,
				"res_code"	=> 0,
				"message"	=> "Activation code is not match",
				);
		endif;

		$this->echoJson($res);

	}

	public function unlink(){
		$companyID 	= $this->input->post("companyID");
		$branchID  	= $this->input->post("branchID");
		$deviceID 	= $this->input->post("deviceID");

		$cek = $this->db->count_all("Branch where CompanyID = '$companyID' AND BranchID='$branchID' AND DeviceID='$deviceID'");
		if($cek>0):
			$res["status"] = true;
			$data = array(
				"DeviceID" 	=> '',
				"Token"		=> '',
				"User_Ch"	=> 'unlink android',
				"Date_Ch"	=> date("Y-m-d H:i:s"),
				);
			$this->db->where("CompanyID", $companyID);
			$this->db->where("BranchID", $branchID);
			$this->db->update("Branch", $data);
		else:
			$res["status"] = true;
		endif;

		$this->echoJson($res);
	}

	public function loginUser(){
		$branchID 	= $this->input->post("branchID");
		$deviceID 	= $this->input->post("deviceID");

		// $deviceID 	= '7895022969680bcd';

		$cek 		= $this->db->count_all("Branch where BranchID = '$branchID' AND DeviceID = '$deviceID'");
		if($cek>0):
			$data 		= $this->main->login("api");
			if($data["status"] == true):
				$cekBranch 	= $this->android->cekBranch($data["id_user"], $branchID);
				$username 	= $this->android->user($data["id_user"]);
				if ($cekBranch["status"] == true):
					$data["res_code"] 	= 1;
					$data["nama"]		= $username;
					$data["hak_akses2"]	= $cekBranch["hak_akses"];
					$this->echoJson($data);
				else:
					$res = array(
						"status" 	=> false,
						"res_code"	=> 2,
						"message"	=> "Your account is not already in store",
						);
					$this->echoJson($res);
				endif;
			else:
				$res = array(
					"status" 	=> false,
					"res_code"	=> 0,
					"message"	=> $data["error_string"],
					);
				$this->echoJson($res);
			endif;
		else:
			$res = array(
				"status" 	=> false,
				"res_code"	=> 3,
				"message"	=> "Sorry your device is not active",
				);
			$this->echoJson($res);
		endif;

	}

	public function login(){
		$email 		 = $this->input->post("email");
		$password	 = $this->input->post("password");
		$imei 		 = $this->input->post("imei");
		$token 		 = $this->input->post("token");
		$check_token = $this->input->post("check_token");

		// ujicoba
		// $email 		= "tes@gmail.com";
		// $password 	= "123123";
		
		$password 	= $this->main->hash($password);

		$data = array(
			"email" 	=> $email,
			"password"	=> $password,
			"imei"		=> $imei,
			"token"		=> $token,
			"check_token" => $check_token,
			);

		if($check_token != 'false'):
			$cek 		= $this->db->count_all("user where email = '$email' && password = '$password'");
		else:
			$cek 		= $this->db->count_all("user where email = '$email' && password = '$password'");
		endif;

		if($cek>0):
			$user 	= $this->android->get_code_user($data);			
			if($user->num_rows()>0):
				$kode_user  = $user->row()->kode_user;
				$d 			= $this->android->get_user_detail($kode_user);
				$res = array(
					"kode_user"	=> $kode_user,
					"companyID" => $d->id_user,
					"email"		=> $d->email,
					"status"	=> true,
					"res_code"	=> 1,
					"message"	=> "Login Berhasil",
					);
			else:
				$res = array(
					"status" 	=> false,
					"res_code"	=> 2,
					"message"	=> "Kode Aktifasi Salah",
					);
			endif;
		else:
			$res = array(
				"status" 	=> false,
				"res_code"	=> 0,
				"message"	=> "Email/Password salah",
				);
		endif;

		$this->echoJson($res);
	}

	//{customer_code=4, companyID=37, 
	//product_data=[
	//{"code":"C1.0001","product":"product1","qty":"8","sub_total":"80000"},
	//{"code":"C1.0001","product":"product1","qty":"2","sub_total":"20000","unit":"1","type":"0"}],
	// latlng=, total=100000}
	public function save_selling(){
		$product_data  = $this->input->post("product_data");
		$customer_code = $this->input->post("customer_code");
		$total 		   = $this->input->post("total");
		$companyID 	   = $this->input->post("companyID");
		$latlng 	   = $this->input->post("latlng");
		$branchID 	   = $this->input->post("branchID");

		// $product_data  = '[{"code":"C1.0001","product":"product1","qty":"8","sub_total":"80000"},{"code":"C1.0001","product":"product1","qty":"2","sub_total":"20000","unit":"1","type":"0"}]';
		// $customer_code = 4;
		// $total 		   = 100000;
		// $companyID 	   = 37;
		// $latlng 	   = "";

		$product_data  = json_decode($product_data);

		$sellNo = $this->android->autoNumber("PS_Sell", "SellNo", 5, date("y"));
		
		$data_PS_Sell = array(
			"SellNo" 	=> $sellNo,
			"CompanyID"	=> $companyID,
			"BranchID"	=> $branchID,
			"VendorID"	=> $customer_code,
			"Paid"		=> 0,
			"Payment"	=> 0,
			"Total"		=> $total,
			"Date"		=> date("Y-m-d H:i:s"),
			"LatLng"	=> $latlng,
			"User_Add"	=> "wildan",
			"Date_Add"	=> date("Y-m-d H:i:s"),
			);
		// $query = $this->db->insert("PS_Sell", $data_PS_Sell);
		$query = false;
		if($query):
			foreach ($product_data as $d) {
				$sellDet = $this->android->autoNumber("PS_Sell_Detail", "SellDet", 5, date("y"));
				$data_PS_Sell_Detail = array(
					"SellDet" 	=> $sellDet,
					"SellNo"	=> $sellNo,
					"CompanyID"	=> $companyID,
					"BranchID"	=> $branchID,
					"ProductID"	=> $this->android->getProductID($d->code),
					"Qty"		=> $d->qty,
					"UnitID"	=> $d->unit,
					"Type"		=> $d->type,
					"Price"		=> $d->sub_total,
					"User_Add"	=> "wildan",
					"Date_Add"	=> date("Y-m-d H:i:s"),
					);
				$this->db->insert("PS_Sell_Detail", $data_PS_Sell_Detail);
			}
			$res["status"] 	 = true;
			$res["res_code"] = 1;
			$res["message"]	 = "Success save Data";
		else:
			$res["status"] 	 = false;
			$res["res_code"] = 0;
			$res["message"]	 = "Gagal Save Data";
		endif;
		$this->echoJson($res);
	}

	public function get_selling(){
		$customer_code 	= $this->input->post("customer_code");
		$branchID 		= $this->input->post("branchID");
		$companyID 		= $this->input->post("companyID");

		$customer_code 	= 4;
		$branchID 		= 20;
		$companyID 		= 1;

		$query = $this->android->getDataSelling($customer_code);
		if($query->num_rows()>0):
			$piutang = (int) 0;
			$res["data"]	= array();
			foreach ($query->result() as $d) {
				$h["code"]			= $d->SellNo;
				$h["sn"] 			= $d->SellNo;
				$h["total_sell"]	= (int) $d->Total;
				$h["status"]		= $this->android->labelSelling($d->Paid);
				$piutang = $piutang+(int) $d->Total;
				array_push($res["data"], $h);
			}
			$res["piutang"]  = $piutang;
			$res["status"] 	 = true;
			$res["res_code"] = 1;
			$res["message"]	 = "Success get data selling";
		else:
			$res["status"] 	 = false;
			$res["res_code"] = 0;
			$res["message"]	 = "Data Selling tidak ditemukan";
		endif;


		$this->echoJson($res);
	}

	public function save_payment(){
		$customer_code  = $this->input->post("customer_code");
		$companyID 		= $this->input->post("companyID");
		$pay 		    = $this->input->post("pay");
		$branchID 		= $this->input->post("branchID");

		$paymentNo 	= $this->android->autoNumber("PS_Payment", "PaymentNo", 5, date("y"));
		$data 		= array(
			"PaymentNo"		=> $paymentNo,
			"CompanyID"		=> $companyID,
			"BranchID"		=> $branchID,
			"VendorID"		=> $customer_code,
			"Total"			=> $pay,
			"Date"			=> date("Y-m-d H:i:s"),
			"User_Add"		=> "wildan",
			"Date_Add"		=> date("Y-m-d H:i:s"),
			);
		// $query = $this->db->insert("PS_Payment", $data);
		$query = false;
		if($query):
			$res["status"] 	 = true;
			$res["res_code"] = 1;
			$res["message"]	 = "Pembayaran Berhasil";
		else:
			$res["status"] 	 = false;
			$res["res_code"] = 0;
			$res["message"]	 = "Pembayaran Gagal";
		endif;

		$this->echoJson($res);
	}

	public function get_customer(){
		$companyID = $this->input->post("companyID");
		$branchID  = $this->input->post("branchID");
		// $companyID = 1;
		$check = $this->db->count_all("PS_Vendor where CompanyID = '$companyID'");
		if($check>0):
			$query  = $this->android->get_customer($companyID);
			
			$res["data"] = array();
			foreach ($query->result() as $d) {
				$h["code"]	 = $d->VendorID;
				$h["title"]	 = strtoupper(substr($d->Name, 0,1));
				$h["name"]	 = $d->Name;
				$h["email"]	 = $d->Email;
				$h["phone"]	 = $d->Phone;
				array_push($res["data"], $h);
			}
			$res["status"] 	 = true;
			$res["res_code"] = 1;
			$res["message"]	 = "Data customer";
		else:
			$res["status"] 	 = false;
			$res["res_code"] = 0;
			$res["message"]	 = "Data customer tidak ditemukan";
		endif;

		$this->echoJson($res);
	}

	public function max_product(){
		// $res["status"] 	  = true;
		// $res["status_code"] = 1;
		// $res["data"] = array();
		// $no = 2;
		// for ($i=0; $i <3 ; $i++) { 
		// 	$h["code"] = "17004637".$no++;
		// 	$h["qty"]  = 10+$no;
		// 	array_push($res["data"], $h);
		// }

		// $this->echoJson($res);
		$companyID 		= $this->input->post("companyID");
		$branchID 		= $this->input->post("branchID");
		
		$companyID = 1;
		$branchID  = 1;
		
		$query = $this->android->max_qty_product($companyID, $branchID);
		if($query->num_rows()>0):
			$res["status"] 	  	= true;
			$res["res_code"] 	= 1;
			$res["message"]		= "Data Product";
			$res["data"]		= array();
			foreach ($query->result() as $d) {
				$h["ProductBranchID"] = $d->ProductBranchID;
				$h["code"] = $d->Code;
				$h["qty"]  = (int) $d->Qty;
				array_push($res["data"], $h);
			}
		else:
			$res["status"] 		= false;
			$res["res_code"]	= 0;
			$res["message"]		= "Maaf product tidak ditemukan";
		endif; 

		$this->echoJson($res);
	}

	public function get_product(){
		$branchID 		= $this->input->post("branchID");
		$companyID 		= $this->input->post("companyID");
		
		$companyID 		= 1;
		$branchID  		= 20;
		
		// $cek = 1;
		// if($cek>0):
		// 	$res["status"] 	    = true;
		// 	$res["res_code"] 	= 1;
		// 	$res["message"] 	= "data product";
		// 	$res["data"] = array();
		// 	$no = 2;
		// 	for ($i=0; $i <3 ; $i++) { 
		// 		$h["code"]  	= "17004637".$no++;
		// 		$h["title"]		= "T";
		// 		$h["name"]  	= "TES PRODUCT".$no;
		// 		$h["price"] 	= 150000;
		// 		$h["unit"]   	= "cm";
		// 		$h["categoty"]	= "Makanan";
		// 		$h["qty"]   	= 10+$no;
		// 		array_push($res["data"], $h);
		// 	}
		// else:
		// 	$res["status"] 	    = false;
		// 	$res["res_code"] 	= 0;
		// 	$res["message"] 	= "data product tidak ada";
			
		// endif;

		// $this->echoJson($res);
		$query = $this->android->get_product($companyID, $branchID);
		if($query->num_rows()>0):
			$res["status"] 	  	= true;
			$res["res_code"] 	= 1;
			$res["message"]		= "Data Product";
			$res["data"]		= array();
			foreach ($query->result() as $d) {
				$h["ProductBranchID"] = $d->ProductBranchID;
				$h["code"] 		= $d->Code;
				$h["title"]		= strtoupper(substr($d->Name, 0,1));
				$h["name"]		= $d->Name;
				$h["price"] 	= (int) $d->SellingPrice;
				$h["qty"]  		= (int) $d->Qty;
				$h["unitName"]  = $d->unitName;
				$h["unitID"]	= $d->UnitID;
				$h["type"]		= $d->Type;
				array_push($res["data"], $h);
			}
		else:
			$res["status"] 		= false;
			$res["res_code"]	= 0;
			$res["message"]		= "Maaf product tidak ditemukan";
		endif;

		$this->echoJson($res);
	}

	public function get_history_selles(){
		$customer_code = $this->input->post("customer_code");
		$companyID 	= $this->input->post("companyID");
		$branchID	= $this->input->post("branchID");
		$date_from 	= $this->input->post("date_from");
		$date_to 	= $this->input->post("date_to")." 23:59:00";

		// $customer_code  = 4;
		// $companyID 		= 1;
		// $branchID  		= 20;
		// $date_from 		= null;
		// $date_to 		= null;

		$data = array(
			"ps_s.VendorID" 	=> $customer_code,
			"ps_s.CompanyID"	=> $companyID,
			"ps_s.BranchID"		=> $branchID,
			);

		$query = $this->android->get_history_selles($data, $date_from,$date_to);
		if($query->num_rows()>0):
			$res["status"] 	  	= true;
			$res["res_code"] 	= 1;
			$res["message"]		= "Data Product";
			$res["data"] = array();
			$total = 0;
			foreach ($query->result() as $d) {
				$h["productID"] 	= $d->ProductID;
				$h["productCode"]	= $d->Code;
				$h["product_name"]	= $d->Name;
				$h["qty"]			= (int) $d->Qty;
				$h["price"]			= (int) $d->Price;
				$h["unit"]			= $d->unit;
				$h["type"]			= $d->Type;
				$h["date"]			= date("Y-m-d", strtotime($d->Date));
				$h["time"]			= date("H:i", strtotime($d->Date));
				
				$total = $total+ (int) $d->Price;

				array_push($res["data"], $h);
			}
			$res["total"] = $total;
		else:
			$res["status"] 		= false;
			$res["res_code"]	= 0;
			$res["message"]		= "Data penjualan tidak ditemukan";
		endif;

		$this->echoJson($res);
	}

	public function get_history_payment(){
		$customer_code = $this->input->post("customer_code");
		$companyID 	= $this->input->post("companyID");
		$branchID	= $this->input->post("branchID");
		$date_from 	= $this->input->post("date_from");
		$date_to 	= $this->input->post("date_to")." 23:59:00";

		// $customer_code  = 4;
		$companyID 		= 1;
		$branchID  		= 20;
		// $date_from 		= null;
		// $date_to 		= null;

		$data = array(
			"VendorID" 	=> $customer_code,
			"CompanyID"	=> $companyID,
			"BranchID"	=> $branchID,
			);

		$query = $this->android->get_history_payment($data, $date_from,$date_to);
		if($query->num_rows()>0):
			$res["status"] 	  	= true;
			$res["res_code"] 	= 1;
			$res["message"]		= "Data Payment";
			$res["data"] = array();
			$total = 0;
			foreach ($query->result() as $d) {
				$h["date"]	= date("Y-m-d", strtotime($d->Date));;
				$h["pay"]	= (int) $d->Total;
				$total = $total+(int) $d->Total;
				array_push($res["data"], $h);
			}
			$res["total"] = $total;
		else:
			$res["status"] 		= false;
			$res["res_code"]	= 0;
			$res["message"]		= "Data pembayaran tidak ditemukan";
		endif;

		$this->echoJson($res);
	}

	public function get_branch(){
		$companyID 	= $this->input->post("companyID");
		$branchID 	= $this->input->post("branchID");

		// $companyID 	= 1;
		// $branchID 	= 3;

		$cek = $this->db->count_all("Branch where CompanyID = '$companyID'");
		if($cek>0):
			$res["status"] 		= true;
			$res["res_code"]	= 1;
			$res["message"]		= "success";
			$res["data"]		= array();
			$query = $this->android->get_branch($companyID, $branchID);
			foreach ($query->result() as $d) {
				$h["branchID"]	= $d->BranchID;
				$h["title"]		= strtoupper(substr($d->Name, 0,1));
				$h["name"]		= $d->Name;
				array_push($res["data"], $h);
			}
		else:
			$res["status"] 		= false;
			$res["res_code"]	= 0;
			$res["message"]		= "Branch tidak ditemukan";
		endif;

		$this->echoJson($res);
	}

	public function syncron(){
		$companyID 	= $this->input->post("companyID");
		$branchID  	= $this->input->post("branchID");
		$payment 	= $this->input->post("payment");
		$selling 	= $this->input->post("selling");
		$return	 	= $this->input->post("return");
		$deviceID 	= $this->input->post("deviceID");
		$username 	= $this->input->post("username");

		// $companyID 	= 55;
		// $branchID 	= 49;
		// $deviceID 	= "7895022969680bcd";

		$cek = $this->db->count_all("Branch where BranchID = '$branchID' AND CompanyID = '$companyID' AND DeviceID = '$deviceID'");
		if($cek>0):
			$AC = $this->android->autoNumber("AC_CorrectionPR", "BalanceNo", 5, "AC".date("ym"));
			$this->android->syncronSellAndroid($selling, $username,$AC,$return);
			$this->android->syncronPaymentAndroid($payment, $companyID, $branchID, $username);
		
			$data_u = array(
				"CompanyID"	=> $companyID,
				"BranchID"	=> $branchID,
				);
			$query_customer = $this->syncron_customer($companyID, $branchID);
			$query_product	= $this->syncron_product($companyID, $branchID);
			$query_serialNo	= $this->syncron_serial_number($companyID, $branchID);
			$query_selling	= $this->syncron_selling($companyID, $branchID);
			$query_payment 	= $this->syncron_payment($companyID, $branchID);
			$query_return 	= $this->syncron_return($companyID, $branchID);
			$query_company 	= $this->syncron_company($companyID);
			$query_branch	= $this->syncron_branch($companyID,$branchID);

			$res["status"]			= true;
			$res["message"]			= "success";
			$res["company_data"]	= $query_company;
			$res["branch_data"]		= $query_branch;
			$res["customer_data"] 	= $query_customer;
			$res["product_data"]	= $query_product;
			$res["serialNo_data"]	= $query_serialNo;
			$res["selling_data"]	= $query_selling;
			$res["payment_data"]	= $query_payment;
			$res["return_data"]		= $query_return;

			$folder     = 'file/';
	        $file_name  = 'result_json_'.$branchID.".json";
	        $temp_file  = $folder.$file_name;
	        $fp = fopen($temp_file, 'w');
	        fwrite($fp, json_encode($res));
	        fclose($fp);

	        $res = array(
	        	"status"	=> true,
	        	"message"	=> "success",
	        	"url_file"	=> site_url($temp_file),
	        );

		else:
			$res["status"]			= false;
			$res["message"]			= "Sorry your device is not active";
		endif;
		$this->echoJson($res);
	}

	public function syncron_customer($companyID, $branchID){
		$cek = $this->db->count_all("PS_Vendor where CompanyID = '$companyID' AND Position = '2'"); 
		if($cek>0):
			$res["status"] 	= true;
			$res["data"]	= array();
			$query = $this->android->get_customer_branch($companyID, $branchID);
			foreach ($query->result() as $d) {
				$h["vendorID"]		= $d->VendorID;
				$h["customer_code"] = $d->Code;
				$h["title"]	 		= strtoupper(substr($d->Name, 0,1));
				$h["name"]	 		= $d->Name;
				$h["email"]	 		= $d->Email;
				$h["phone"]	 		= $d->Phone;
				$h["address"]		= $d->Address;
				$h["active"]		= $d->Active;
				array_push($res["data"], $h);
			}
		else:
			$res["status"] = false;
		endif;

		return $res;
	}

	public function syncron_product($companyID, $branchID){
		$cek = $this->db->count_all("PS_Product_Branch where CompanyID = '$companyID' and BranchID = '$branchID'"); 
		if($cek>0):
			$res["status"] 	  	= true;
			$res["data"]		= array();
			$query = $this->android->get_product($companyID, $branchID);
			foreach ($query->result() as $d) {
				$h["ProductBranchID"] = $d->ProductBranchID;
				$h["productID"] = $d->ProductID;
				$h["code"] 		= $d->Code;
				$h["title"]		= strtoupper(substr($d->Name, 0,1));
				$h["name"]		= $d->Name;
				$h["price"] 	= (int) $this->android->get_priceMutation($companyID,$branchID,$d->ProductID);
				$h["qty"]  		= $d->Qty;
				$h["unitName"]  = $d->unitName;
				$h["unitID"]	= $d->UnitID;
				$h["type"]		= $d->Type;
				$h["categoryCode"] = $d->ParentCode;
				$h["categoryName"] = $d->categoryName;
				$h["active"]	= $d->Active;
				array_push($res["data"], $h);
			}
		else:
			$res["status"] 		= false;
		endif;

		return $res;
	}

	public function syncron_serial_number($companyID,$branchID){
		$this->companyID = $companyID;
		$this->branchID  = $branchID;
		$cek = $this->db->count_all("PS_Mutation where CompanyID = '$companyID' AND BranchIDTo = '$branchID'");
		if($cek>0):
			$res["status"] 	  	= true;
			$res["data"]		= array();
			$query = $this->android->syncron_serialNumber($companyID, $branchID);
			foreach ($query->result() as $d) {
				$h["ProductID"]		= $d->ProductID;
				$h["serialNumber"] 	= $this->serialNumber($d->SerialNumber, $d->MutationDet, $d->Type);
				$h["mutationDet"]	= $d->MutationDet;
				$h["company"]		= $this->companyID;
				
				array_push($res["data"], $h);
			}
		else:
			$res["status"] 	  	= false;
		endif;

		return $res;
	}

	public function serialNumber($serialNumber, $mutationDet, $type){
		$serialNumber = json_decode($serialNumber);
		$data = array();
		foreach ($serialNumber as $d) {
			$h["serialNo"]	= $d->SerialNumber;
			if($type == 0):
				array_push($data, $d->SerialNumber);
			else:
				$checkSn = $this->check_sn_selling($d->SerialNumber, $mutationDet);
				if($checkSn):
					array_push($data, $d->SerialNumber);
				endif;
			endif;
		}
		return $data;
	}

	public function check_sn_selling($serialNo, $mutationNo){
		$cek = $this->db->count_all("PS_Sell_Detail where CompanyID = '$this->companyID' AND BranchID = '$this->branchID'");
		if($cek>0):
			$query = $this->android->get_sell_detail_sn($this->companyID, $this->branchID);
			foreach ($query->result() as $d) {
				$data = json_decode($d->SerialNumber);
				foreach ($data as $sn) {
					if($serialNo == $sn->SerialNumber):
						$checkSn = $this->checkReturnSn($sn->SerialNumber);
						if($checkSn):
							return true;
						else:
							return false;
						endif;
						break;
					endif;
				}
			}
			return true;
		else:
			return true;
		endif;
	}
	public function checkReturnSn($serialNo){
		$cek = $this->db->count_all("AP_Retur where CompanyID = '$this->companyID' AND BranchID = '$this->branchID'");
		if($cek>0):
			$query = $this->android->getReturnSn($this->companyID, $this->branchID);
			foreach ($query->result() as $d) {
				$data = json_decode($d->SerialNumber);
				foreach ($data as $sn) {
					if($serialNo == $sn->SerialNumber):
						return true;
						break;
					endif;
				}
			}
			return false;
		else:
			return false;
		endif;
	}

	public function syncron_selling($companyID, $branchID){
		$cek = $this->db->count_all("PS_Sell where CompanyID = '$companyID' and BranchID = '$branchID'");
		if($cek>0):
			$res["status"]	= true;
			$res["data"]	= array();
			$query = $this->android->get_selling($companyID, $branchID);
			foreach ($query->result() as $d) {
				$h["SellNo"]	= $d->SellNo;
				$h["VendorID"]	= $d->VendorID;
				$h["Paid"]		= $d->PaidAndroid;
				$h["PaidAndroid"] = $d->PaidAndroid;
				$h["Total"]		= $d->Total;
				$h["Payment"]	= $d->Payment;
				$h["Change"]	= $d->Change;
				$h["Remark"]	= $d->Remark;
				$h["Date"]		= $d->Date;
				$h["Latlng"]	= $d->Latlng;
				$h["Status"]	= $d->Status;

				$h["sell_detail_data"] = $this->syncron_sell_detail($d->SellNo);

				array_push($res["data"], $h);
			}
		else:
			$res["status"]	= false;
		endif;

		return $res;
	}

	public function syncron_sell_detail($sellNo){
		$cek = $this->db->count_all("PS_Sell_Detail where SellNo = '$sellNo'");
		if($cek>0):
			$res["status"]	= true;
			$res["data"]	= array();
			$query = $this->android->get_sell_detail($sellNo);
			foreach ($query->result() as $d) {
				$h["SellDet"]		= $d->SellDet;
				$h["SellNo"]		= $d->SellNo;
				$h["ProductID"]		= $d->ProductID;
				$h["UnitID"]		= $d->UnitID;
				$h["Conversion"]	= $d->Conversion;
				$h["Type"]			= $d->Type;
				$h["Qty"]			= $d->Qty;
				$h["Price"]			= $d->Price;
				$h["TotalPrice"] 	= $d->TotalPrice;
				$h["Discount"]		= $d->Discount;
				$h["SerialNumber"] 	= $d->SerialNumber;
				$h["Complete"]		= $d->Complete;
				$h["Status"]		= $d->Status;

				array_push($res["data"], $h);
			}
		else:
			$res["status"]	= false;
		endif;

		return $res;
	}

	public function syncron_payment($companyID, $branchID){
		$cek = $this->db->count_all("PS_Payment where CompanyID = '$companyID' and BranchID = '$branchID'");
		if($cek>0):
			$res["status"] 	= true;
			$res["data"]	= array();
			$query = $this->android->get_payment($companyID, $branchID);
			foreach ($query->result() as $d) {
				$h["PaymentNo"] = $d->PaymentNo;
				$h["CompanyID"] = $d->CompanyID;
				$h["BranchID"]	= $d->BranchID;
				$h["SellNo"]	= $d->SellNo;
				$h["VendorID"]	= $d->VendorID;
				$h["Date"]		= $d->Date;
				$h["TotalAwal"]	= $d->TotalAwal;
				$h["Total"]		= $d->Total;
				$h["GrandTotal"]= $d->GrandTotal;
				$h["Status"]	= $d->Status;
				$h["Type"]		= $d->PaymentType;
				$h["ApproveCode"] = $d->ApproveCode;

				$h["payment_detail_data"] = $this->syncron_payment_detail($d->PaymentNo);

				array_push($res["data"], $h);
			}
		else:
			$res["status"] = false;
		endif;

		return $res;
	}

	public function syncron_payment_detail($paymentNo){
		$cek = $this->db->count_all("PS_Payment_Detail where PaymentNo = '$paymentNo'");
		if($cek>0):
			$res["status"] 	= true;
			$res["data"]	= array();
			$query = $this->android->get_payment_detail($paymentNo);
			foreach ($query->result() as $d) {
				$h["PaymentDet"]	= $d->PaymentDet;
				$h["PaymentNo"]		= $d->PaymentNo;
				$h["CompanyID"]		= $d->CompanyID;
				$h["VendorID"]		= $d->VendorID;
				$h["SellNo"]		= $d->SellNo;
				$h["CorrectionNo"]	= $d->CorrectionNo;
				$h["Total"]			= $d->Total;
				$h["Type"]			= $d->Type;

				array_push($res["data"], $h);
			}
		else:
			$res["status"] = false;
		endif;

		return $res;
	}

	public function syncron_return($companyID,$branchID){
		$cek = $this->db->count_all("AP_Retur where CompanyID = '$companyID' AND BranchID = '$branchID'");
		if($cek>0):
			$res["status"] 	= true;
			$res["data"]	= array();
			$query = $this->android->get_return($companyID, $branchID);
			foreach ($query->result() as $d) {
				$h["ReturNo"] 	= $d->ReturNo;
				$h["CompanyID"]	= $d->CompanyID;
				$h["BranchID"]	= $d->BranchID;
				$h["SellNo"]	= $d->SellNo;
				$h["VendorID"]	= $d->VendorID;
				$h["Type"]		= $d->Type;
				$h["Date"]		= $d->Date;
				$h["returnDet"]	= $this->syncron_return_det($d->ReturNo);
				array_push($res["data"], $h);
			}
		else:
			$res["status"]	= false;
		endif;

		return $res;
	}
	public function syncron_return_det($returNo){
		$cek = $this->db->count_all("AP_Retur_Det where ReturNo = '$returNo'");
		if($cek>0):
			$res["status"] 	= true;
			$res["data"]	= array();
			$query = $this->android->get_return_det($returNo);
			foreach ($query->result() as $d) {
				$h["ReturDet"] 	= $d->ReturDet;
				$h["CompanyID"]	= $d->CompanyID;
				$h["ReturNo"]	= $d->ReturNo;
				$h["SellNo"]	= $d->SellNo;
				$h["SellDet"]	= $d->SellDet;
				$h["ProductID"]	= $d->ProductID;
				$h["UnitID"]	= $d->UnitID;
				$h["Conversion"]= $d->Conversion;
				$h["Qty"]		= $d->Qty;
				$h["Price"]		= $d->Price;
				$h["Total"]		= $d->Total;
				$h["Type"]		= $d->Type;
				$h["Complete"]	= $d->Complete;
				$h["SerialNumber"] = $d->SerialNumber;
				$h["Remark"]	= $d->Remark;
				array_push($res["data"], $h);
			}
		else:
			$res["stauts"] 	= false;
		endif;

		return $res;
	}

	public function syncron_company($companyID = ""){
        // $companyID = 1;
        $cek = $this->db->count_all("user where id_user = '$companyID'");
        if($cek>0):
        	$res["status"] 	= true;
        	$res["data"]	= array();
        	$query     = $this->android->get_company_info($companyID);
        	foreach ($query->result() as $d) {
        		$h["name"]		= $d->nama;
        		$h["email"]		= $d->email;
        		$h["phone"]		= $d->phone;
        		$h["phone_c"]	= $d->phone_company;
        		$h["postal"]	= $d->postal;
        		$h["fax"]		= $d->fax;
        		$h["address"]	= $d->address;
        		$h["city"]		= $d->city;
        		$h["province"]	= $d->province;
        		$h["country"]	= $d->country;
        		$h["lat"]		= null;
        		$h["lng"]		= null;

        		array_push($res["data"], $h);
        	}
        else:
        	$res["status"] = false;
        endif;        

        return $res;
    }

    public function syncron_branch($companyID, $branchID){
    	// $companyID  = 1;
    	// $branchID 	= 3;

    	$cek = $this->db->count_all("Branch where CompanyID = '$companyID' AND BranchID = '$branchID'");
        if($cek>0):
        	$res["status"] 	= true;
        	$res["data"]	= array();
        	$query     = $this->android->get_branch_info($companyID, $branchID);
        	foreach ($query->result() as $d) {
        		$h["name"]		= $d->Name;
        		$h["email"]		= null;
        		$h["phone"]		= $d->Phone;
        		$h["postal"]	= $d->Postal;
        		$h["fax"]		= $d->Fax;
        		$h["address"]	= $d->Address;
        		$h["city"]		= $d->City;
        		$h["province"]	= $d->Province;
        		$h["country"]	= $d->Country;
        		$h["lat"]		= $d->Lat;
        		$h["lng"]		= $d->Lng;

        		array_push($res["data"], $h);
        	}
        else:
        	$res["status"] = false;
        endif;

        return $res;
    }

	public function create_customer(){
		$companyID 	= $this->input->post("companyID");
		$branchID 	= $this->input->post("branchID");
		$deviceID 	= $this->input->post("deviceID");
		$username 	= $this->input->post("username");
		$name 		= $this->input->post("name");
		$address 	= $this->input->post("address");
		$city		= $this->input->post("city");
		$province	= $this->input->post("province");
		$phone 		= $this->input->post("phone");
		$email 		= $this->input->post("email");
		$npwp 		= $this->input->post("npwp");

		$cek = $this->db->count_all("Branch where BranchID = '$branchID' AND CompanyID = '$companyID' AND DeviceID = '$deviceID'");
		if($cek>0):
			$code = $this->android->autoNumber("PS_Vendor", "Code", 5, date("ym"));
			$data_customer = array(
				"CompanyID"	=> $companyID,
				"Position"	=> 2,
				"Code"		=> $code,
				"Name"		=> $name,
				"Title"		=> "MR",
				"Address"	=> $address,
				"Phone"		=> $phone,
				"Email"		=> $email,
				"NPWP"		=> $npwp,
				"User_Add"	=> $username,
				"Date_Add"	=> date("Y-m-d H:i:s"),
				);
			$query = $this->db->insert("PS_Vendor", $data_customer);
			if($query):
				$query_customer = $this->syncron_customer($companyID,$branchID);
				$res["customer_data"] = $query_customer;
			endif;
			// $query_customer = $this->syncron_customer($companyID,$branchID);
			// $res["customer_data"] = $query_customer;
			$res["status"] 	= true;
			$res["message"] = "success";

		else:
			$res["status"]	= false;
			$res["message"]	= "Maaf device Anda sudah di nonaktifkan";
		endif;

		$this->echoJson($res);
	}

	public function save_mutation(){
		$companyID 	= $this->input->post("companyID");
		$branchID 	= $this->input->post("branchID");
		$deviceID 	= $this->input->post("deviceID");
		$username 	= $this->input->post("username");
		$branchIDTO = $this->input->post("branchIDTO");
		$type 		= $this->input->post("type");
		$remark 	= $this->input->post("remark");
		$product_data = $this->input->post("product_data");

		// $companyID 	= 49;
		// $branchID 	= 9;
		// $product_data = '[{"ProductBranchID":"124","code":"PSM0001","productID":"59","product":"Safira","qty":"1.00","price":"70000","sub_total":"70000","unit":"3","type":"2","no":"0","discount":"0","remark":"","sn":[{"MutationDet":"MTD180200032","SerialNumber":"SA001"}]}]';
		// $deviceID 	= '396e91ef02562aba';
		// $type 		= 1;

		// $username = "tes manual";

		$cek = $this->db->count_all("Branch where BranchID = '$branchID' AND CompanyID = '$companyID' AND DeviceID = '$deviceID'");
		if($cek>0):
			$res["status"]		= true;
			$res["res_code"]	= 1;
			$res["message"]		= "success";
			$mutationNo = $this->android->autoNumber("PS_Mutation", "MutationNo", 5, "MT".date("ym"));
			$data_mutation = array(
				"MutationNo" 	=> $mutationNo,
				"CompanyID"		=> $companyID,
				"BranchID"		=> $branchID,
				"Date"			=> date("Y-m-d"),
				"Remark"		=> $remark,
				"User_Add"		=> $username,
				"Date_Add"		=> date("Y-m-d H:i:s"),
				);
			//type 1 = to company and 2 = to store
			if($type == 1):
				$data_mutation["Type"] = 2;
				$data_mutation["BranchIDTo"] = $companyID;
			else:
				$data_mutation["Type"] = 1;
				$data_mutation["BranchIDTo"] = $branchIDTO;
			endif;
			$query = $this->db->insert("PS_Mutation", $data_mutation);
			if($query):
				$this->android->save_mutation_det($product_data, $mutationNo, $companyID, $branchID,$branchIDTO,$username, $type);
			endif;
		else:
			$res["status"]		= false;
			$res["res_code"]	= 0;
			$res["message"]		= "Maaf device Anda sudah di nonaktifkan";
		endif;
		$this->echoJson($res);
	}
	public function syncron2(){
		$companyID 	= $this->input->post("companyID");
		$branchID  	= $this->input->post("branchID");
		$payment 	= $this->input->post("payment");
		$selling 	= $this->input->post("selling");
		$return	 	= $this->input->post("return");
		$serial 	= $this->input->post("serial");
		$deviceID 	= $this->input->post("deviceID");
		$username 	= $this->input->post("username");

		// $selling 	= '{"status":true,"data":[{"IDSellNo":"1","SellNo":"S318022300001","CompanyID":"1","BranchID":"3","VendorID":"0","Paid":"1","PaidAndroid":"1","Total":"6000.00","Payment":"6000.00","Remark":"null","Date":"2018-02-23 13:59:28","status":"1","selldetail":{"status":true,"data":[{"IDSellDet":"1","SellDet":"SD318022300001","CompanyID":"1","BranchID":"3","IDSellNo":"1","SellNo":"S318022300001","ProductID":"61","UnitID":"3","Type":"2","Qty":"2.00","Conversion":"1.00","Price":"3000.00","Discount":"0.00","TotalPrice":"6000.00","SerialNumber":"[{\"MutationDet\":\"MTD180200001\",\"SerialNumber\":\"PCSN0001\",\"status\":\"1\"},{\"MutationDet\":\"MTD180200001\",\"SerialNumber\":\"PCSN0002\",\"status\":\"0\"}]","status":"1"}]}}]}';
		// $return 	= '{"status":true,"data":[{"ReturNo":"RT318022300001","CompanyID":"1","BranchID":"3","SellNo":"S318022300001","Type":"2","Date":"2018-02-23","VendorID":"0","returnDet":{"status":true,"data":[{"ReturDet":"1","CompanyID":"1","BranchID":"3","ReturNo":"RT318022300001","SellNo":"S318022300001","SellDet":"SD318022300001","ProductID":"61","UnitID":"3","Qty":"1.00","Conversion":"1.00","Price":"3000.00","Total":"3000.00","Type":"2","Complete":"0","SerialNumber":"[{\"MutationDet\":\"MTD180200001\",\"SerialNumber\":\"PCSN0002\",\"status\":\"1\",\"SellDet\":\"SD318022300001\"}]","Remark":""}]}}]}';
		// $payment 	= '{"status":true,"data":[{"IDPaymentNo":"1","PaymentNo":"PC318022300001","CompanyID":"1","BranchID":"3","VendorID":"0","Date":"2018-02-23 13:59:28","TotalAwal":"6000.00","Total":"6000.00","GrandTotal":"0.00","Status":"0","SellNo":"S318022300001","Type":"0","ApproveCode":"null"}]}';
		// $serial 	= '{"status":true,"data":[{"MutationDet":"MTD180200002","ProductID":"52","SerialNumber":"G0001","status":"1"},{"MutationDet":"MTD180200001","ProductID":"61","SerialNumber":"PCSN0002","status":"1"},{"MutationDet":"MTD180200001","ProductID":"61","SerialNumber":"PCSN0003","status":"1"},{"MutationDet":"MTD180200001","ProductID":"61","SerialNumber":"PCSN0004","status":"1"},{"MutationDet":"MTD180200001","ProductID":"61","SerialNumber":"PCSN0005","status":"1"}]}';
		// $companyID 	= 1;
		// $branchID 	= 231;
		// $deviceID 	= "c4a188f6cf4c1e9d";
		// $username 	= 'Ade';

		$cek = $this->db->count_all("Branch where BranchID = '$branchID' AND CompanyID = '$companyID' AND DeviceID = '$deviceID'");
		if($cek>0):
			$AC = $this->android->autoNumber("AC_CorrectionPR", "BalanceNo", 5, "AC".date("ym"));
			$this->android->syncronSellAndroid($selling, $username,$AC,$return);
			$this->android->syncronPaymentAndroid($payment, $companyID, $branchID, $username);
			$this->android->syncronSerialMutation($serial, $username);
		
			$data_u = array(
				"CompanyID"	=> $companyID,
				"BranchID"	=> $branchID,
				);
			$query_customer = $this->syncron_customer($companyID, $branchID);
			$query_product	= $this->syncron_product($companyID, $branchID);
			$query_serialNo	= $this->syncron_serial_number2($companyID, $branchID);
			$query_selling	= $this->syncron_selling($companyID, $branchID);
			$query_payment 	= $this->syncron_payment($companyID, $branchID);
			$query_return 	= $this->syncron_return($companyID, $branchID);
			$query_company 	= $this->syncron_company($companyID);
			$query_branch	= $this->syncron_branch($companyID,$branchID);
			$query_setting 	= $this->android->get_setting($companyID);

			$res["status"]			= true;
			$res["message"]			= "success";
			$res["company_data"]	= $query_company;
			$res["branch_data"]		= $query_branch;
			$res["customer_data"] 	= $query_customer;
			$res["product_data"]	= $query_product;
			$res["serialNo_data"]	= $query_serialNo;
			$res["selling_data"]	= $query_selling;
			$res["payment_data"]	= $query_payment;
			$res["return_data"]		= $query_return;
			$res["setting_default"]	= $query_setting;

			$folder     = 'file/';
	        $file_name  = 'result_json_'.$branchID.".json";
	        $temp_file  = $folder.$file_name;
	        $fp = fopen($temp_file, 'w');
	        fwrite($fp, json_encode($res));
	        fclose($fp);

	        $res = array(
	        	"status"	=> true,
	        	"message"	=> "success",
	        	"url_file"	=> site_url($temp_file),
	        );
		else:
			$res["status"]			= false;
			$res["message"]			= "Sorry your device is not active";
		endif;
		$this->echoJson($res);
	}

	public function syncron_serial_number2($companyID,$branchID){
		$this->companyID = $companyID;
		$this->branchID  = $branchID;
		$cek = $this->db->count_all("PS_Mutation where CompanyID = '$companyID' AND BranchIDTo = '$branchID'");
		if($cek>0):
			$res["status"] 	  	= true;
			$res["data"]		= array();
			$query = $this->android->syncron_serialNumber($companyID, $branchID);
			foreach ($query->result() as $d) {
				$serialNumber 	= $d->SerialNumber;
				$mutationDet 	= $d->MutationDet;
				$type 			= $d->Type;
				$productID 		= $d->ProductID;

				$serialNumber = json_decode($serialNumber);
				foreach ($serialNumber as $d2) {
					if($type == 0):
						$h["ProductID"]		= $productID;
						$h["mutationDet"]	= $mutationDet;
						$h["companyID"]		= $companyID;
						$h["status"]		= 1;
						$h["serialNumber"] 	= $d2->SerialNumber;

						array_push($res["data"], $h);
					else:
						if (!empty($d2->status)){
							if($d2->status == 1):
							$h["ProductID"]		= $productID;
							$h["mutationDet"]	= $mutationDet;
							$h["companyID"]		= $companyID;
							$h["status"]		= $d2->status;
							$h["serialNumber"]	= $d2->SerialNumber;

							array_push($res["data"], $h);
						endif;
						}
					endif;
				}
			}
		else:
			$res["status"] 	  	= false;
		endif;

		return $res;
	}

	public function save_mutation2(){
		$companyID 	= $this->input->post("companyID");
		$branchID 	= $this->input->post("branchID");
		$deviceID 	= $this->input->post("deviceID");
		$username 	= $this->input->post("username");
		$branchIDTO = $this->input->post("branchIDTO");
		$type 		= $this->input->post("type");
		$remark 	= $this->input->post("remark");
		$product_data = $this->input->post("product_data");

		// $companyID 	= 49;
		// $branchID 	= 9;
		// $product_data = '[{"ProductBranchID":"124","code":"PSM0001","productID":"59","product":"Safira","qty":"1.00","price":"70000","sub_total":"70000","unit":"3","type":"2","no":"0","discount":"0","remark":"","sn":[{"MutationDet":"MTD180200032","SerialNumber":"SA001"}]}]';
		// $deviceID 	= '396e91ef02562aba';
		// $type 		= 1;

		// $username = "tes manual";

		$cek = $this->db->count_all("Branch where BranchID = '$branchID' AND CompanyID = '$companyID' AND DeviceID = '$deviceID'");
		if($cek>0):
			$res["status"]		= true;
			$res["res_code"]	= 1;
			$res["message"]		= "success";
			$mutationNo = $this->android->autoNumber("PS_Mutation", "MutationNo", 5, "MT".date("ym"));
			$data_mutation = array(
				"MutationNo" 	=> $mutationNo,
				"CompanyID"		=> $companyID,
				"BranchID"		=> $branchID,
				"Date"			=> date("Y-m-d"),
				"Remark"		=> $remark,
				"User_Add"		=> $username,
				"Date_Add"		=> date("Y-m-d H:i:s"),
				);
			//type 1 = to company and 2 = to store
			if($type == 1):
				$data_mutation["Type"] = 2;
				$data_mutation["BranchIDTo"] = $companyID;
			else:
				$data_mutation["Type"] = 1;
				$data_mutation["BranchIDTo"] = $branchIDTO;
			endif;
			$query = $this->db->insert("PS_Mutation", $data_mutation);
			if($query):
				$this->android->save_mutation_det2($product_data, $mutationNo, $companyID, $branchID,$branchIDTO,$username, $type);
			endif;
		else:
			$res["status"]		= false;
			$res["res_code"]	= 0;
			$res["message"]		= "Maaf device Anda sudah di nonaktifkan";
		endif;
		$this->echoJson($res);
	}

	public function voucher(){
		$CompanyID 		= $this->input->post("companyID");
		$DeviceID 		= $this->input->post("deviceID");
		$BranchID 		= $this->input->post("branchID");
		$Voucher 		= $this->input->post("voucher");
		$id_user 		= $this->input->post("id_user");

		// $CompanyID 		= 1;
		// $DeviceID 		= "7895022969680bcd";
		// $BranchID 		= 25;
		// $Voucher 		= "6757";
		// $Username 			= "wildan m";

		$cek = $this->db->count_all("Branch where CompanyID = '$CompanyID' AND BranchID = '$BranchID' AND DeviceID='$DeviceID'");
		if($cek>0):
			$voucher_data = $this->android->voucher($CompanyID, $Voucher);
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
						"UsedCompanyID"	=> $CompanyID,
						"UsedID"		=> $id_user,
						"ExpireDate" 	=> $expire,
						"UseDate"		=> date("Y-m-d H:i:s"),
						"Status"		=> "used",
						"UserCh"		=> "Android pipesys voucher",
						"DateCh"		=> date("Y-m-d H:i:s"),
						);
					$data_Branch = array(
						"StatusAccount" 	=> "active",
						"ExpireAccount"		=> $expire,
						"User_Ch"			=> "Android pipesys voucher",
						"Date_Ch"			=> date("Y-m-d H:i:s"),
						);

					$this->db->where("Code", $Voucher);
					// $this->db->where("CompanyID", $CompanyID);
					$this->db->update("VoucherDetail", $data);
					$this->android->updateBranch($data_Branch, $BranchID);

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

	public function checkExpire(){
		$CompanyID 		= $this->input->post("companyID");
		$DeviceID 		= $this->input->post("deviceID");
		$BranchID 		= $this->input->post("branchID");

		// $CompanyID 		= 1;
		// $DeviceID 		= "7895022969680bcd";
		// $BranchID 		= 25;

		$cek = $this->db->count_all("Branch where CompanyID = '$CompanyID' AND BranchID = '$BranchID' AND DeviceID='$DeviceID'");
		if($cek>0):
			$data_where = array(
					"CompanyID"	=> $CompanyID,
					"BranchID"	=> $BranchID,
					);
			$data_expire = $this->android->checkExpire($data_where);

			$res["status"] 		= TRUE;
			$res["res_code"]	= 1;
			$res["message"]		= "Success get data";
			$res["Selisih"]  		= $data_expire["Selisih"];
			$res["StatusAccount"]	= $data_expire["StatusAccount"];
			$res["Expire"]			= $data_expire["Expire"];
			$res["ExpireAccount"]	= $data_expire["ExpireAccount"];
		else:
			$res["status"] 		= FALSE;
			$res["res_code"]	= 0;
			$res["message"]		= "Device is not active";
		endif;

		$this->echoJson($res);
	}
}