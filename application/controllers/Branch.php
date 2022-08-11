<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Branch extends CI_Controller {
	var $title = 'Store & Device Management';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_branch",'branch');
		$this->main->cek_session();
		$this->title = $this->lang->line('lb_manage_store');
	}
	public function index()
	{
		$url_modul 					= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url_modul);
		$read 						= $this->main->read($id_url);
		if($read == 0){ redirect(); }
		$branch_tambah 				= $this->main->menu_tambah($id_url);
		if($branch_tambah > 0):
            $tambah = $this->main->general_button('add',$this->lang->line('lb_add_new').' '.$this->title);
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['title']  		= $this->title;
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'branch/modal';
		$data['page'] 			= 'branch/list';
		$data['view'] 			= 'index';
		$data['modul'] 			= 'branch';
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
            $tambah = '<button type="button" class="btn btn-primary btn-outline" onclick="tambah()" >Add New Employee</button>';
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['title']  		= 'Employee';
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'branch/modal_sales';
		$data['page'] 			= 'branch/list_sales';
		$data['modul'] 			= 'sales';
		$data['url_modul']  	= $url_modul;
		$this->load->view('index',$data);
	}
	public function add_store($view = "",$id="")
	{
		$data['title']  		= 'Store & Device Management';
		$data['page'] 			= 'branch/form';
		$data['view'] 			= $view;
		$data['id'] 			= $id;
		$this->load->view('index',$data);
	}
	//---------------------------------------------------------------------------------------
	public function ajax_list($url_modul = "",$modul = "")
	{
		$id_url = $this->main->id_menu($url_modul);
		$list 	= $this->branch->get_datatables();
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		foreach ($list as $a) {
            $active = "";
            $device = "";
            $token  = "";
            $unlink = "";
            $hapus  = "";
            $ubah 	= "";
            $ExpireAccount = "";
            $BtnActive = "";
			$branch_ubah 	= $this->main->menu_ubah($id_url);
			$branch_hapus 	= $this->main->menu_hapus($id_url);
			if($branch_ubah > 0):
           	$ubah = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="Edit" onclick="edit('."'".$a->BranchID."'".')">Edit</a>';
			endif;
			if($branch_hapus > 0):
           		if($a->Active == 1):
           			$hapus = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="delete Data" onclick="hapus('."'".$a->BranchID."'".')">Delete</a>';
           	
           		else:
           			$hapus = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="undelete Data" onclick="active('."'".$a->BranchID."'".')">Undelete</a>';
           		endif;
			endif;
			if($a->DeviceID):
            	$generate 	= "'unlink'";
				$unlink 	= '<a href="javascript:void(0)" type="button" class="btn btn-danger btn-xs" title="active Data" onclick="generate_token('.$a->BranchID.','.$generate.')">Unlink</a>'; 
			endif;
			
			if($a->ExpireAccount && $a->ExpireAccount <= date("Y-m-d") || $a->DeviceID && $a->ExpireAccount == ""):
				// $BtnActive = '<a href="javascript:void(0)" type="button" class="btn btn-info" title="Edit" onclick="edit('."'".$a->BranchID."'".','."'active'".')">Active</a>';
			endif;

            if($a->Active == 0):
            	$active = '<i class="icon fa-trash pull-right" aria-hidden="true"></i>';
            endif;

            $token 		= $a->Token;
            $DeviceID 	= $a->DeviceID;
            if(empty($token)):
            	$generate 	= "'generate'";
            	$token 		= '<button class="btn btn-info btn-xs" onclick="generate_token('.$a->BranchID.','.$generate.')">'.$this->lang->line('btn_generate_token').'</button>';
            else:
            	$token 		= "<hijau>token : ".$token."</hijau>";
            endif;
            #jika device id masih ada maka unlink akan active dan token tidak akan ada
            if($DeviceID):
            	$device = "ID : ".$DeviceID;
            	$token  = "";
            endif;
            if($a->ExpireAccount):
            	$ExpireAccount = date("Y-m-d",strtotime($a->ExpireAccount));
            	if($a->ExpireAccount <= date("Y-m-d")):
            		$ExpireAccount = '<span class="info-red">'.$ExpireAccount.'</span>';
            	else:
            		$ExpireAccount = '<span class="info-green">'.$ExpireAccount.'</span>';
		           	
            	endif;
            endif;

            $ho = '';
            if($a->Index == 1):
            	$ho = ' <span class="info-green">HO</span>';
            endif;

			$button  = '<div class="btn-group btn-group-xs" aria-label="Basic example" role="group">';
			$button .= $BtnActive;
            $button .= $unlink;
            $button .= $ubah;
            $button .= $hapus;
            $button .= '</div>';

			$no++;
			$row 	= array();
			$row[] 	= $i++;

			$code = '<a href="javascript:void(0)" type="button" class="" title="View Detail" onclick="edit('."'".$a->BranchID."','view'".')">'.$a->Name.$ho.'</a>';

			if($modul == "branch"):
				$row[] 	= $code.$active;
				$row[] 	= $a->Address;
				$row[] 	= $a->City;
				$row[] 	= $a->Province;
				$row[] 	= $a->Country;
			elseif($modul == "sales"):
				$row[] 	= $a->Email.$active;
				$row[] 	= $a->FirstName;
				$row[] 	= $a->LastName;
				$row[] 	= $a->Phone;
			endif;
				$row[] 	= $token.$device.'<br>'.$unlink;
				$row[] 	= $ExpireAccount;
				// $row[] 	= $button;		
				$data[] = $row;


		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->branch->count_all(),
			"recordsFiltered" => $this->branch->count_filtered(),
			"data"			  => $data,
		);
		// header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
	}
	public function ajax_edit($id)
	{
		$data 	= $this->branch->get_by_id($id);
		$edit   = $this->main->button_action("edit2",$id);
		if($data->Active == 1):
   			$delete = $this->main->button_action("delete4",$id);
   		else:
   			$delete = $this->main->button_action("undelete2",$id);
   			$edit 	= "";
   		endif;

   		if($data->Index == 1):
   			$delete = '';
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
		$Code 		= $this->main->branch_code_generate();
		$pass 	 	= $this->input->post("Password");
		$password 	= $this->main->hash($pass);
		if($this->session->app == "pipesys"):
			$data = array(
				'App'		=> $this->session->app,
				"Code"     	=> $Code,
				'CompanyID' => $this->session->CompanyID,
				'UserCode' 	=> $this->session->kode_user,
				'Name' 		=> $this->input->post('Name'),
				'Address' 	=> $this->input->post('Address'),
				'City' 		=> $this->input->post('City'),
				'Province' 	=> $this->input->post('Province'),
				'Country' 	=> $this->input->post('Country'),
				'Postal' 	=> $this->input->post('Postal'),
				'Phone' 	=> $this->input->post('Phone'),
				'Fax' 		=> $this->input->post('Fax'),
				'Lat' 		=> $this->input->post('Lat'),
				'Lng' 		=> $this->input->post('Lng'),
			);
		elseif($this->session->app == "salespro"):
			$data = array(
				'App'		=> $this->session->app,
				'CompanyID' => $this->session->CompanyID,
				'Email' 	=> $this->input->post('Email'),
				'FirstName' => $this->input->post('FirstName'),
				'LastName' 	=> $this->input->post('LastName'),
				'Phone' 	=> $this->input->post('Phone'),
				'Name' 		=> $this->input->post('FirstName')." ".$this->input->post('LastName'),
				'Password' 	=> $password,

			);
		endif;
		$insert 		= $this->branch->save($data);
		$BranchID 		= $insert;
		echo json_encode(array("status" => TRUE,"pesan" => "yoi"));
		$this->branch->copy_product($BranchID);
	}
	public function ajax_update()
	{
		$this->_validate("update");
		$pass 	 	= $this->input->post("Password");
		$password 	= $this->main->hash($pass);
		$BranchID 	= $this->input->post('BranchID');
		$CompanyID 	= $this->session->CompanyID;
		if($this->session->app == "pipesys"):
			$data = array(
				'CompanyID' => $this->session->CompanyID,
				'UserCode' 	=> $this->session->kode_user,
				'Name' 		=> $this->input->post('Name'),
				'Address' 	=> $this->input->post('Address'),
				'City' 		=> $this->input->post('City'),
				'Province' 	=> $this->input->post('Province'),
				'Country' 	=> $this->input->post('Country'),
				'Postal' 	=> $this->input->post('Postal'),
				'Phone' 	=> $this->input->post('Phone'),
				'Fax' 		=> $this->input->post('Fax'),
				'Lat' 		=> $this->input->post('Lat'),
				'Lng' 		=> $this->input->post('Lng'),
			);
			$ck_ho = $this->db->count_all("Branch where BranchID = $BranchID and CompanyID = $CompanyID and Branch.Index = '1'");
			if($ck_ho>0):
				$data_session['BranchName'] = $this->input->post('Name');
				$this->session->set_userdata($data_session);
			endif;
		elseif($this->session->app == "salespro"):
			$data = array(
				'CompanyID' => $this->session->CompanyID,
				'Email' 	=> $this->input->post('Email'),
				'FirstName' => $this->input->post('FirstName'),
				'LastName' 	=> $this->input->post('LastName'),
				'Phone' 	=> $this->input->post('Phone'),
				'Name' 		=> $this->input->post('FirstName')." ".$this->input->post('LastName'),
			);
			if($pass && $pass != "undefined"):
				$data["Password"] = $password;
			endif;
		endif;
		$this->branch->update(array('BranchID' => $this->input->post('BranchID')), $data);
		echo json_encode(array("status" => TRUE,"pesan" => $this->input->post("status")));
	}
	public function active_account()
	{
		$ID = "";
		$this->_validate_voucher();
		$VoucherCode 	= $this->input->post("VoucherCode");
		$BranchID1 		= $this->input->post('BranchID');

		$this->db->select("
			Voucher.Type,
			VoucherDetail.VoucherDetailID,
			VoucherDetail.BranchID,
			VoucherDetail.Status,
			VoucherDetail.ExpireDate,
			Branch.Name as SalesName,
		");
		$this->db->join("Branch","VoucherDetail.BranchID = Branch.BranchID","left");
		$this->db->join("Voucher","VoucherDetail.VoucherID = Voucher.VoucherID");
		$this->db->where("VoucherDetail.CompanyID",$this->session->CompanyID);
		$this->db->where("VoucherDetail.App",$this->session->app);
		$this->db->where("VoucherDetail.Code",$VoucherCode);
		$a = $this->db->get("VoucherDetail")->row();

		if($a):
			$Type 		= $a->Type;
			$ID 		= $a->VoucherDetailID;
			$BranchID2 	= $a->BranchID;
			$Status 	= $a->Status;
			$ExpireDate = $a->ExpireDate;
			$SalesName 	= $a->SalesName;
			if($Status == "used" && $BranchID2 && $BranchID1 != $BranchID2):
				echo json_encode(array("status" => FALSE,"message" => "Sorry, this voucher has been used by ".$SalesName));		
				exit();
			endif;
			$NewExpireDate = date("Y-m-d",strtotime("+".$Type." month"));
			if($Status == "not"):
				$data_voucher = array(
					"BranchID"		=> $BranchID1,
					"ExpireDate"	=> $NewExpireDate,
					"Status" 		=> "used",
					"UseDate"		=> date("Y-m-d H:i:s"),
				);
				$this->update_voucher($ID,$data_voucher);
			endif;

			$data = array(
				"StatusAccount"	=> "active",
				"ExpireAccount"	=> $NewExpireDate,
			);
			$this->branch->update(array('BranchID' => $BranchID1), $data);
		else:
			echo json_encode(array("status" => FALSE,"message" => "Sorry, this voucher cannot be found".$VoucherCode));		
			exit();
		endif;
		echo json_encode(array("status" => TRUE,"pesan" => $BranchID1));	
	}
	public function update_voucher($ID,$data)
	{
		$this->db->where("VoucherDetailID",$ID);
		$this->db->update("VoucherDetail",$data);
	}
	public function ajax_delete($id,$status = "")
	{		
		$active 	= 0;
		$message 	= "";
		$success 	= TRUE;
		$CompanyID  = $this->session->CompanyID;
		$ck_index 	= $this->main->get_one_column("Branch","Index",array("CompanyID" => $CompanyID, "BranchID" => $id));
		if($ck_index):
			if($ck_index->Index == 1):
				$success = FALSE;
				$message = "Can't delete Store HO";
			endif;
		else:
			$success = FALSE;
			$message = $this->lang->line('lb_data_not_found');
		endif;

		if($status == "active"):
			$app 	= $this->session->app;
			$data 	= $this->branch->get_by_id($id);
			$Email  = $data->Email;
			if($this->db->count_all("Branch where Email='$Email' and App='$app' and Active = '1' and CompanyID!='$CompanyID' ") > 0):
				$query = $this->main->getBranch($Email);
				$comanyName = "";
				foreach ($query->result() as $d) {
					$comanyName .= $d->nama.", ";
				}
				$success 	= FALSE;
				$message 	= 'Email address has been taken in '.$comanyName." Please contact the company administrator to deactivate email address";
			endif;
			$active = 1;
		endif;
		if($success):
			$data = array(
				"Active" => $active,
			);
			$this->branch->update(array('BranchID' => $id), $data);
			echo json_encode(array("status" => TRUE));
		else:
			echo json_encode(array("status" => FALSE,"message" => $message));
		endif;
	}
	private function _validate($modul = "")
	{
		$CompanyID  = $this->session->CompanyID;
		$crud 		= $this->input->post('crud');
		$Email 		= $this->input->post('Email');
		$password 	= 0; 
		if($crud == "insert"):
			$password = 1;
		else:
			
		endif;
		$data 					= array();
		$data['error_string'] 	= array();
		$data['inputerror'] 	= array();
		$data['status'] 		= TRUE;

		if($this->session->app == "pipesys"):
			if($this->input->post('Name') == ''):
				$data['inputerror'][] 	= 'Name';
				$data['error_string'][] = $this->lang->line('lb_store_name_empty');
				$data['status'] 		= FALSE;
			endif;
		elseif($this->session->app == "salespro"):
			if($this->input->post('FirstName') == '')
			{
				$data['inputerror'][] 	= 'FirstName';
				$data['error_string'][] = $this->lang->line('lb_first_name_empty');
				$data['status'] 		= FALSE;
			}
			if($this->input->post('LastName') == '')
			{
				$data['inputerror'][] 	= 'LastName';
				$data['error_string'][] = $this->lang->line('lb_lasr_name_empty');
				$data['status'] 		= FALSE;
			}
			if($this->input->post('Email') == '')
			{
				$data['inputerror'][] 	= 'Email';
				$data['error_string'][] = $this->lang->line('lb_email_empty');
				$data['status'] 		= FALSE;
			}
			if($this->input->post('Phone') == '')
			{
				$data['inputerror'][] 	= 'Phone';
				$data['error_string'][] = 'Enter Phone Number';
				$data['status'] 		= FALSE;
			}
			if($modul == "save" && $this->input->post('Password') == '')
			{
				$data['inputerror'][] 	= 'Password';
				$data['error_string'][] = 'Enter Password';
				$data['status'] 		= FALSE;
			}
			$app = $this->session->app;
			if($modul == "save" && $this->db->count_all("Branch where Email='$Email' and App='$app' and CompanyID='$CompanyID' ") > 0)
			{
				$data['inputerror'][] 	= 'Email';
				$data['error_string'][] = 'Email address has been already exist';
				$data['status'] 		= FALSE;
			}

			if($this->db->count_all("Branch where Email='$Email' and App='$app' and Active = '1' and CompanyID!='$CompanyID' ") > 0)
			{
				$query = $this->main->getBranch($Email);
				$comanyName = "";
				foreach ($query->result() as $d) {
					$comanyName .= $d->nama.", ";
				}
				$data['inputerror'][] 	= 'Email';
				$data['error_string'][] = 'Email address has been taken in '.$comanyName." Please contact the company administrator to deactivate email address";
				$data['status'] 		= FALSE;
			}

			if($modul == "update"){
			$a = $this->branch->get_by_id($this->input->post('BranchID'));
			$BranchID = $a->BranchID;
			$EmailVal = $a->Email;

				if($Email != $EmailVal){
					if($this->db->count_all("Branch where Email='$Email' and App='$app' and CompanyID='$CompanyID' ") > 0)
					{
						$data['inputerror'][] 	= 'Email';
						$data['error_string'][] = 'Email address has been already exist';
						$data['status'] 		= FALSE;
					}
				}
			}
		endif;

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	private function _validate_voucher()
	{
		$data 					= array();
		$data['error_string'] 	= array();
		$data['inputerror'] 	= array();
		$data['status'] 		= TRUE;

		if($this->input->post('VoucherCode') == ''):
			$data['inputerror'][] 	= 'VoucherCode';
			$data['error_string'][] = 'Please insert your voucher code';
			$data['status'] 		= FALSE;
		endif;
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	public function generate_token($id,$status = ""){
		$data = array(
			"Date_Ch" 	=> date("Y-m-d H:i:s"),
			"User_Ch" 	=> $this->session->nama,
		);

		if($status == "generate"):
			$data["Token"] 		= $this->generate_token_();
			$data["DeviceID"] 	= null;
		elseif($status == "unlink"):
			$data["Token"]		= null;
			$data["DeviceID"] 	= null;
		endif;
		$this->db->where("BranchID",$id);
		$this->db->update("Branch",$data);
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
	    $generate = strtoupper(substr($this->session->CompanyID.$b, 0,6));
	    // $generate = "670D12";
	    if($this->db->count_all("Branch where token = '$generate'") > 0):
	    	$this->generate_token_();
	    else:
	    	return $generate;
	    endif;
	}

	public function sp_list_sales($CompanyID){
		$query = $this->main->branch("","","",$CompanyID);
		$output = "";
		foreach ($query as $d) {
			$output .= '<option value="'.$d->branchid.'">'.$d->name.'</option>';
		}
		$res["data"] = $output;
		header('Content-Type: application/json');
        echo json_encode($res,JSON_PRETTY_PRINT);  
	}
}
