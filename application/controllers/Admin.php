<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
	var $title = 'User Management';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_admin",'admin');
		$this->main->cek_session();
	}
	public function index()
	{
		$url_modul 					= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url_modul);
		$read 						= $this->main->read($id_url);
		if($read == 0){ redirect(); }
		$admin_tambah 				= $this->main->menu_tambah($id_url);
		$check_user_add 			= $this->main->check_user_add();
		if($admin_tambah > 0):
            $tambah = $this->main->general_button('add', $this->lang->line('lb_add_new').' '.$this->title);
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['title']  		= $this->title;
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'admin/modal';
		$data['page'] 			= 'admin/list';
		$data['modul'] 			= 'admin';
		$data['url_modul']  	= $url_modul;
		$this->load->view('index',$data);
	}
	public function sales()
	{
		$url_modul 					= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url_modul);
		$read 						= $this->main->read($id_url);
		if($read == 0){ redirect(); }
		$admin_tambah 				= $this->main->menu_tambah($id_url);
		if($admin_tambah > 0):
            $tambah = '<button type="button" class="btn btn-primary btn-outline" onclick="tambah()" >Add New Sales</button>';
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['title']  		= 'Sales';
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'admin/modal';
		$data['page'] 			= 'admin/list';
		$data['modul'] 			= 'admin';
		$data['url_modul']  	= $url_modul;
		$this->load->view('index',$data);
	}
	public function company()
	{
		$url_modul 					= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url_modul);
		$read 						= $this->main->read($id_url);
		if($read == 0){ redirect(); }
		$admin_tambah 				= $this->main->menu_tambah($id_url);
		if($admin_tambah > 0):
            $tambah = '<button type="button" class="btn btn-primary btn-outline" onclick="tambah()" >Add New Sales</button>';
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['title']  		= $this->lang->line('lb_company');
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'admin/modal';
		$data['page'] 			= 'admin/list_company';
		$data['modul'] 			= 'company';
		$data["list"]			= $this->admin->get_company();
		$data['url_modul']  	= $url_modul;
		$this->load->view('index',$data);
	}
	//---------------------------------------------------------------------------------------
	public function ajax_list($url_modul = "")
	{
		$parameter_modul    = $this->main->get_module_company();
		$date_ar = $parameter_modul->ar->expire;
		$date_ap = $parameter_modul->ap->expire;
		$date_ac = $parameter_modul->ac->expire;
		$date_inventory = $parameter_modul->inventory->expire;

		$arr_date = array($date_ar,$date_ap,$date_ac,$date_inventory);
		function date_sort($a, $b) {
		    return strtotime($b) - strtotime($a);
		}
		usort($arr_date, "date_sort");

		$id_url = $this->main->id_menu($url_modul);
		$list 	= $this->admin->get_datatables();
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		$datenow = date("Y-m-d");
		foreach ($list as $a) {
			$token 	= "";
			$device = "";
            $store 	= "";
            $hapus 	= "";
            $ubah 	= "";
            $active = "";
            $unlink = "";

			$admin_ubah 	= $this->main->menu_ubah($id_url);
			$admin_hapus 	= $this->main->menu_hapus($id_url);
			if($admin_ubah > 0):
           	$ubah = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="Edit" onclick="edit('."'".$a->id_user."'".')">Edit</a>'; 
			endif;
			if($admin_hapus > 0):
           		if($a->active == 1):
           			$hapus = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="Delete" onclick="hapus('."'".$a->id_user."'".')">Delete</a>';
           		else:
           			$hapus = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="Undelete Data" onclick="active('."'".$a->id_user."'".')">Undelete</a>';
           		endif;
			endif;
			if($a->deviceid):
            	$generate 	= "'unlink'";
				$unlink 	= '<a href="javascript:void(0)" type="button" class="btn btn-danger" title="Active Data" onclick="generate_token('.$a->id_user.','.$generate.')">Unlink</a>'; 
			endif;

			$button  = '<div class="btn-group btn-group-xs" aria-label="Basic example" role="group">';
            $button .= $unlink;
            $button .= $ubah;
            $button .= $hapus;
            $button .= '</div>';
            if($a->active == 0):
            	$active = '<i class="icon fa-trash pull-right" aria-hidden="true"></i>';
            endif;
            #ini untuk pipesys
            if(count(json_decode($a->store)) > 0):
            	$store = $this->select_store($a->store);
            endif;
            #ini untuk salespro
            $token 		= $a->devicetoken;
            $deviceid 	= $a->deviceid;
            if(empty($token)):
            	$generate 	= "'generate'";
            	$token 		= '<button class="btn btn-info btn-xs" onclick="generate_token('.$a->id_user.','.$generate.')">Generate Token</button>';
            else:
            	$token 		= "<hijau>token : ".$token."</hijau>";
            endif;
            #jika device id masih ada maka unlink akan active dan token tidak akan ada
            if($deviceid):
            	$device = "ID : ".$deviceid;
            	$token  = "";
            endif;
            $ExpireAccount = "";
            if($a->ExpireAccount):
            	$ExpireAccount = date("Y-m-d",strtotime($a->ExpireAccount));
            endif;

            $code = '<a href="javascript:void(0)" type="button" class="" title="View Detail" onclick="edit('."'".$a->id_user."','view'".')">'.$a->email.'</a>';

            $additional = '';
            $ExpireUser = '';
            $UseVoucher = '';
            if($a->hak_akses == "additional"):
            	$additional  = ' <hijau>Additional User</hijau>';
            	$UseVoucher  = $this->main->general_button("general_onclick_xs","voucher_use('".$a->id_user."')",$this->lang->line('lb_voucher_use'));
            	if($datenow<=$a->VoucherExpireDate):
            		$ExpireUser = '<hijau>'.$a->VoucherExpireDate.'</hijau><br>';
            	else:
            		$ExpireUser = '<merah>'.$a->VoucherExpireDate.'</merah><br>';
            	endif;
            else:
            	if($a->user_index == 1):
            		$ExpireUser = $arr_date[0];
            	elseif($a->user_index >= 2 && $a->user_index <=3):
            		$ExpireUser = $arr_date[1];
            	elseif($a->user_index >= 4 && $a->user_index <=5):
            		$ExpireUser = $arr_date[2];
            	elseif($a->user_index >= 6 && $a->user_index <=7):
            		$ExpireUser = $arr_date[3];
            	endif;
            	if($ExpireUser):
            		if($datenow<=$ExpireUser):
	            		$ExpireUser = '<hijau>'.$ExpireUser.'</hijau><br>';
	            	else:
	            		$ExpireUser = '<merah>'.$ExpireUser.'</merah><br>';
	            	endif;
            	endif;
            endif;

			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $code.$additional.$active;
			$row[] 	= $a->first_name;
			$row[] 	= $a->last_name;
			$row[] 	= $a->phone;
			#ini untuk aplikasi pipesys
			if($this->session->app == "pipesys"):
			$row[] 	= $store;
			$row[] 	= $ExpireUser.$UseVoucher;
			elseif($this->session->app == "salespro"):
			$row[] 	= $token.$device;
			$row[] 	= $ExpireAccount;
			endif;
			// $row[] 	= $button;		
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->admin->count_all(),
			"recordsFiltered" => $this->admin->count_filtered(),
			"data"			  => $data,
		);
		echo json_encode($output);
	}
	public function ajax_list_company($url_modul = "",$modul = "")
	{
		$id_url = $this->main->id_menu($url_modul);
		$list 	= $this->admin->get_datatables($modul);
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		foreach ($list as $a) {
			$token 	= "";
			$device = "";
            $store 	= "";
            $hapus 	= "";
            $ubah 	= "";
            $active = "";
            $unlink = "";

			$admin_ubah 	= $this->main->menu_ubah($id_url);
			$admin_hapus 	= $this->main->menu_hapus($id_url);
			if($admin_ubah > 0):
           	$ubah = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="View" onclick="edit('."'".$a->id_user."'".')">View</a>'; 
			endif;
			if($admin_hapus > 0):
           		if($a->active == 1):
           			$hapus = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="Delete" onclick="hapus('."'".$a->id_user."'".')">Delete</a>';
           		else:
           			$hapus = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="Undelete Data" onclick="active('."'".$a->id_user."'".')">Undelete</a>';
           		endif;
			endif;
			if($a->deviceid):
            	$generate 	= "'Unlink'";
				$unlink 	= '<a href="javascript:void(0)" type="button" class="btn btn-danger" title="Active Data" onclick="generate_token('.$a->id_user.','.$generate.')">Unlink</a>'; 
			endif;

			$button  = '<div class="btn-group btn-group-xs" aria-label="Basic example" role="group">';
            $button .= $unlink;
            $button .= $ubah;
            $button .= $hapus;
            if($modul == "company"):
            	$button .= '<a href="javascript:void(0)" type="button" class="btn btn-primary" title="Super Admin" onclick="super_admin('."'".$a->id_user."'".')">Set Super Admin</a>';
            endif;
            $button .= '</div>';
            if($a->active == 0):
            	$active = '<i class="icon fa-trash pull-right" aria-hidden="true"></i>';
            endif;
            $ParentID = '';
            if($a->super_admin):
            	$ParentID = '<span class="info-green">'.$a->super_admin.'</span>';
           	endif;

           	$code = '<a href="javascript:void(0)" type="button" class="" title="View Detail" onclick="edit('."'".$a->id_user."','view'".')">'.$a->nama.'</a>';         

			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $code.$active;
			$row[] 	= $a->email;
			$row[] 	= $a->phone;
			$row[] 	= $ParentID;
			// $row[] 	= $button;
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->admin->count_all($modul),
			"recordsFiltered" => $this->admin->count_filtered($modul),
			"data"			  => $data,
		);
		echo json_encode($output);
	}
	public function select_store($BranchID)
	{
		// $BranchID = '[{"branchid":"20"},{"branchid":"21"},{"branchid":"22"},{"branchid":"23"}]';
		$BranchID = json_decode($BranchID);
		$BranchIDArray = array();
		foreach($BranchID as $a):
			$item = $a->branchid;
			array_push($BranchIDArray,$item);
		endforeach;
		$this->db->select("Name");
		$this->db->where_in("BranchID",$BranchIDArray);
		$this->db->where("CompanyID",$this->session->CompanyID);
		$query 	= $this->db->get("Branch");
		$data 	= $query->result();
		$label 	= ""; 
		foreach($data as $b):
			$label .= " <abu style='font-size:10px'>".$b->Name."</abu>";
		endforeach;
		return $label;
	}
	public function ajax_edit($id)
	{	
		$modul = $this->input->get("modul");
		$a = $this->admin->get_by_id($id);
		$edit   = $this->main->button_action("edit2",$id);

		if($a->status == 1):
   			$delete = $this->main->button_action("delete4",$id);
   		else:
   			$delete = $this->main->button_action("undelete2",$id);
   			$edit 	= "";
   		endif;

   		if($modul == "company"):
   			$edit = "";
   		endif;

		$output = array(
			"data"			=> $a,
			"list_store"	=> json_decode($a->store),
			"status" 		=> TRUE,
			"hakakses"		=> $this->session->hak_akses,
			"edit"			=> $edit,
			"delete"		=> $delete,
		);
		header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
	}

	public function simpan()
	{

		$this->_validate();
		$CompanyID 	= $this->session->CompanyID;
		$password 	= $this->input->post("password");
		$password 	= $this->main->hash($password);
		$branch 	= $this->input->post("branch");
		$hakakses 	= $this->input->post("hakakses");
		$Voucher 	= $this->input->post("VoucherAdditional");
		$hak_akses 	= "branch";

		if(!$Voucher):
			$ck_user 	= $this->db->count_all("user where (CompanyID = '$CompanyID' or id_user = '$CompanyID') and user.index <=7 and user.index is not null");
			$user_expire = null;
		else:
			$hak_akses   = "additional";
			$user_expire = date("Y-m-d");
			$this->db->select("user.index");
			$this->db->where("CompanyID", $CompanyID);
			$this->db->where("user.index >=", 21);
			$this->db->order_by("user.index", "desc");
			$query = $this->db->get("user")->row();
			if($query):
				$ck_user = $query->index;
			else:
				$ck_user = 20;
			endif;
		endif;
		
		$index_user = $ck_user + 1;

		$store 		= array();
		if(count($branch) > 0):
			foreach ($branch as $key => $v) {
				$item = array("branchid" => $branch[$key], "hakakses" => $hakakses[$key]);
				array_push($store, $item);
			}
		endif;
		$store 		= json_encode($store);
		$data = array(
			'CompanyID'			=> $this->session->companyid,
			'email' 			=> $this->input->post('email'),
			'phone' 			=> $this->input->post('phone'),
			'nama'				=> $this->input->post("first_name")." ".$this->input->post("last_name"),
			'first_name' 		=> $this->input->post('first_name'),
			'last_name' 		=> $this->input->post('last_name'),
			'password' 			=> $password,
			"hak_akses" 		=> $hak_akses,
			"store" 			=> $store,
			"App"				=> $this->session->app,
			"index"				=> $index_user,
			"status"			=> 1,
			"VoucherExpireDate"	=> $user_expire,
			"StatusVerify"		=> 1,
			"StatusParameter"	=> 1,
		);
		$insert = $this->admin->save($data);
		if($Voucher):
			$this->main->UseVoucher($Voucher,$insert,"additional");
		endif;
		echo json_encode(array("status" => TRUE,"pesan" => "yoi"));
	}
	public function ajax_update()
	{
		$pass 	 	= $this->input->post("password");
		$password 	= $this->main->hash($pass);
		$branch 	= $this->input->post("branch");
		$hakakses 	= $this->input->post("hakakses");
		$store 		= array();
		if(count($branch) > 0):
			foreach ($branch as $key => $v) {
				$item = array("branchid" => $branch[$key], "hakakses" => $hakakses[$key]);
				array_push($store, $item);
			}
		endif;
		$store 		= json_encode($store);
		$data = array(
			// 'email' 			=> $this->input->post('email'),
			'phone' 			=> $this->input->post('phone'),
			'nama'				=> $this->input->post("first_name")." ".$this->input->post("last_name"),
			'first_name' 		=> $this->input->post('first_name'),
			'last_name' 		=> $this->input->post('last_name'),
			"store"				=> $store
		);
		if($pass && $pass != "undefined"):
			$data["password"] = $password;
		endif;
		$this->admin->update(array('id_user' => $this->input->post('id_user')), $data);
		echo json_encode(array("status" => TRUE,"pesan" => $this->input->post("status")));
	}
	public function ajax_delete($id,$status_page="")
	{
		$CompanyID = $this->session->CompanyID;

		$status 	= true;
		$message 	= "Success";
		$ck_user 	= $this->db->count_all("user where id_user = '$id' and CompanyID = '$CompanyID'");
		if($id == $CompanyID):
			$status = false;
			$message = "Can't delete user company";
		else:
			if($ck_user>0):
				$active = 0;
				if($status_page == "active"):
					$active = 1;
				endif;
				$data = array(
					"status" => $active,
				);
				$this->admin->update(array('id_user' => $id), $data);
			else:
				$status = false;
				$message = "Can't delete user company";
			endif;
		endif;
		
		$output = array(
			"status"	=> $status,
			"message"	=> $message,
		);

		$this->main->echoJson($output);
	}
	private function _validate()
	{
		$crud 		= $this->input->post('crud');
		$CompanyID 	= $this->session->CompanyID;
		$password 	= 0; 
		if($crud == "insert"):
			$password = 1;
		else:
		endif;
		$email  	= $this->input->post("email");
		$Voucher 	= $this->input->post("VoucherAdditional");
		$data 					= array();
		$data['error_string'] 	= array();
		$data['inputerror'] 	= array();
		$data['status'] 		= TRUE;
		if($this->input->post('first_name') == '')
		{
			$data['inputerror'][] 	= 'first_name';
			$data['error_string'][] = 'Enter name';
			$data['status'] 		= FALSE;
		}
		if($this->input->post('last_name') == '')
		{
			$data['inputerror'][] 	= 'last_name';
			$data['error_string'][] = 'Enter name';
			$data['status'] 		= FALSE;
		}
		if($this->input->post('email') == '')
		{
			$data['inputerror'][] 	= 'email';
			$data['error_string'][] = 'Enter email address';
			$data['status'] 		= FALSE;
		}
		$app = $this->session->app;
		if($this->db->count_all("user where email='$email' and CompanyID = '$CompanyID' or email = '$email' and id_user = '$CompanyID'") > 0)
		{
			$data['inputerror'][] 	= 'email';
			$data['error_string'][] = 'Email address has been already exist';
			$data['status'] 		= FALSE;
		}
		if($this->input->post('password') == '' && $password == 1)
		{
			$data['inputerror'][] 	= 'password';
			$data['error_string'][] = 'Enter password';
			$data['status'] 		= FALSE;
		}
		if($this->input->post('hak_akses') == '0')
		{
			$data['inputerror'][] 	= 'hak_akses';
			$data['error_string'][] = 'Select level';
			$data['status'] 		= FALSE;
		}

		if($crud == "insert" && $Voucher == ''):
			$datenow 	= date("Y-m-d");
			$Module 	= $this->main->get_module_company();
			$module_ar 	= $this->main->parameter_modul("ar");
			$module_ap 	= $this->main->parameter_modul("ap");
			$module_ac 	= $this->main->parameter_modul("ac");
			$module_inventory 	= $this->main->parameter_modul("inventory");
			$count_module	= 0;
			$max_user 		= 0;

			if($Module->ar->status == 1):
				if(in_array("ar", $module_ar)):
					if($Module->ar->expire >= $datenow): $count_module += 1; endif;
				endif;
			endif;
			if($Module->ap->status == 1):
				if(in_array("ap", $module_ap)):
					if($Module->ap->expire >= $datenow): $count_module += 1; endif;
				endif;
			endif;
			if($Module->ac->status == 1):
				if(in_array("ac", $module_ac)):
					if($Module->ac->expire >= $datenow): $count_module += 1; endif;
				endif;
			endif;
			if($Module->inventory->status == 1):
				if(in_array("inventory", $module_inventory)):
					if($Module->inventory->expire >= $datenow): $count_module += 1; endif;
				endif;
			endif;

			$ck_user = $this->db->count_all("user where (CompanyID = '$CompanyID' or id_user = '$CompanyID') and user.index <=7 and user.index is not null");
			$ck_user += 1;
			if($count_module == 1 && $ck_user>1):
				$data['inputerror'][] 	= '';
				$data['error_string'][] = '';
				$data['status'] 		= FALSE;
				$data['message']		= 'Max add user 1 for '.$count_module." Module";
			elseif($count_module == 2 && $ck_user>3):
				$data['inputerror'][] 	= '';
				$data['error_string'][] = '';
				$data['status'] 		= FALSE;
				$data['message']		= 'Max add user 3 for '.$count_module." Module";
			elseif($count_module == 3 && $ck_user>5):
				$data['inputerror'][] 	= '';
				$data['error_string'][] = '';
				$data['status'] 		= FALSE;
				$data['message']		= 'Max add user 5 for '.$count_module." Module";
			elseif($count_module == 4 && $ck_user>7):
				$data['inputerror'][] 	= '';
				$data['error_string'][] = '';
				$data['status'] 		= FALSE;
				$data['message']		= 'Max add user 7 for '.$count_module." Module";
			endif;
		endif;

		if($Voucher):
			$ck_voucher = $this->db->count_all("VoucherDetail dt join Voucher mt on dt.VoucherID = mt.VoucherID where dt.Code = '$Voucher' and dt.Status = 'not' and mt.Module = '2'");
			if($ck_voucher<=0):
				$data['inputerror'][] 	= 'VoucherAdditional';
				$data['error_string'][] = 'Voucher not found';
				$data['status'] 		= FALSE;
			endif;
		endif;

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	public function generate_token($id,$status = ""){
		$data = array(
			"date_ch" 	=> date("Y-m-d H:i:s"),
			"user_ch" 	=> $this->session->nama,
		);

		if($status == "generate"):
			$data["DeviceToken"] 	= $this->generate_token_();
			$data["DeviceID"] 		= null;
		elseif($status == "unlink"):
			$data["DeviceToken"]	= null;
			$data["DeviceID"] 		= null;
		endif;


		$this->db->where("id_user",$id);
		$this->db->update("user",$data);
		$output = array(
			"status" 	=> TRUE,
			"message" 	=> $status." Success",
			"hakakses" 	=> $this->session->hak_akses
		);
		header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
	}

	public function generate_token_()
	{
	    $b = sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
	        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
	        mt_rand( 0, 0xffff ),
	        mt_rand( 0, 0x0C2f ) | 0x4000,
	        mt_rand( 0, 0x3fff ) | 0x8000,
	        mt_rand( 0, 0x2Aff ), mt_rand( 0, 0xffD3 ), mt_rand( 0, 0xff4B )
	    );
	    return strtoupper(substr($this->session->CompanyID.$b, 0,6));

	}

	#ParentID or super admin or privilege
	//20180521 MW
	public function update_super_admin(){
		$this->validate_super_admin();
		$super_admin = $this->input->post('super_admin');
		$data = array(
			"ParentID" => $super_admin,
			);
		$this->admin->update(array('id_user' => $this->input->post('id_user')), $data);
		echo json_encode(array("status" => TRUE,"pesan" => $this->input->post("status")));
	}

	private function validate_super_admin(){
		$data 					= array();
		$data['error_string'] 	= array();
		$data['inputerror'] 	= array();
		$data['status'] 		= TRUE;
		if($this->input->post('super_admin') == 'none')
		{
			$data['inputerror'][] 	= 'super_admin';
			$data['error_string'][] = 'Please select Super Admin';
			$data['status'] 		= FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	public function delete_super_admin($id){
		$data = array(
			"ParentID" => null,
		);
		$this->admin->update(array('id_user' => $id), $data);
		echo json_encode(array("status" => TRUE));
	}
}
