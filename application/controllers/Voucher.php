<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Voucher extends CI_Controller {
	var $title = 'Voucher';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_voucher",'voucher');
		$this->main->cek_session();
	}
	public function index()
	{
		$url_modul 					= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url_modul);
		$read 						= $this->main->read($id_url);
		if($read == 0){ redirect(); }
		$voucher_tambah 				= $this->main->menu_tambah($id_url);
		if($voucher_tambah > 0):
            $tambah = $this->main->general_button('general_blue',site_url('buy-voucher-app'),'Buy '.$this->title);
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['master'] 		= $this->lang->line('lb_admin');
		$data['title']  		= $this->title;
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'voucher/modal';
		$data['page'] 			= 'voucher/list';
		$data['modul'] 			= 'voucher';
		$data['url_modul']  	= $url_modul;
		$this->load->view('index',$data);
	}

	public function used_voucher(){
		$url_modul 					= "buy-voucher";
		$id_url 					= $this->main->id_menu($url_modul);
		$read 						= $this->main->read($id_url);
		if($read == 0){ redirect(); }
		$voucher_tambah 			= $this->main->menu_tambah($id_url);
		if($voucher_tambah > 0):
            $tambah = $this->main->general_button('add',$this->lang->line('lb_use_voucher'));
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['title']  		= $this->lang->line('lb_use_voucher');
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'voucher/use_modal';
		$data['page'] 			= 'voucher/use_list';
		$data['modul'] 			= 'voucher';
		$data['url_modul']  	= $url_modul;
		$this->load->view('index',$data);
	}

	public function transaction()
	{
		$url_modul 					= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url_modul);
		$read 						= $this->main->read($id_url);
		if($read == 0){ redirect(); }
		$tambah = ""; 
		#ini untuk session halaman aturan user privileges;
		$data['master'] 		= $this->lang->line('lb_admin');
		$data['title']  		= $this->lang->line('lb_t_voucher');
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'voucher/modal';
		$data['page'] 			= 'voucher/list';
		$data['modul'] 			= 'transaction';
		$data['url_modul']  	= $url_modul;
		$this->load->view('index',$data);
	}
	public function view($VoucherID){
		$a                  = $this->main->voucher_detail($VoucherID);
        $data["data"]       = $a;
        $this->load->view("voucher/view", $data);
	}
	public function ajax_list($url_modul = "",$modul = "")
	{
		$id_url = $this->main->id_menu($url_modul);
		$list 	= $this->voucher->get_datatables($modul);
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;

		$btn_delete = $this->lang->line('btn_delete');
		$btn_undelete = $this->lang->line('btn_undelete');
		$btn_edit = $this->lang->line('btn_edit');
		$btn_view_v = "View Voucher";//$this->lang->line('btn_view_voucher');
		$btn_confirmation = "Confirmation";//$this->lang->line('btn_confirmation');

		foreach ($list as $a) {
			$token 	= "";
			$device = "";
            $store 	= "";
            $hapus 	= "";
            $ubah 	= "";
            $view 	= "";
            $active = "";
            $unlink = "";
            $voucher= "";
            $remark2= "";

			$voucher_ubah 	= $this->main->menu_ubah($id_url);
			$voucher_hapus 	= $this->main->menu_hapus($id_url);
			if($voucher_ubah > 0):
           		// $ubah = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="Confimation" onclick="edit('."'".$a->VoucherID."'".')">confirmation</a>'; 
			endif;
			if($voucher_hapus > 0):
       			$hapus = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="Hapus" onclick="hapus('."'".$a->VoucherID."'".')">'.$btn_delete.'</a>';
			endif;
			if($a->Status == "finish"):
				// $voucher = '<a href="javascript:void(0)" type="button" class="btn btn-info" title="View Voucher" onclick="edit('."'".$a->VoucherID."'".','."'voucher'".')">'.$btn_view_v.'</a>'; 
				$ubah 	 = "";
			endif;

			if($a->Status == "cancel" || $a->Status == "expire"):
				$ubah = "";
			endif;
			if($modul == "transaction" && $a->StatusTransfer == "proccess" && $a->Status == "proccess" || $modul == "transaction" && $a->StatusTransfer == "finish" && $a->Status == "proccess"):
           		$ubah = '<a href="javascript:void(0)" type="button" class="btn btn-primary" title="Confimation" onclick="edit('."'".$a->VoucherID."'".','."'confirmation'".')">'.$btn_confirmation.'</a>'; 
			elseif($modul == "transaction" && $a->Status == "proccess" && $a->StatusTransfer == "proccess"):
				$ubah = "";
				$remark2 = '<br/><span class="info-green">waiting confirmation</span>';
			endif;
			if($modul == "transaction"):
				$view = '<a href="javascript:void(0)" type="button" class="btn btn-default btn-outline" title="View" onclick="edit('."'".$a->VoucherID."'".','."'view'".')">view</a>'; 
			endif;
			// $info 	= '<a href="javascript:void(0)" type="button" class="btn btn-default btn-outline" title="View" onclick="edit('."'".$a->VoucherID."'".','."'info'".')">Info</a>'; 
			$button  = '<div class="btn-group btn-group-xs" aria-label="Basic example" role="group">';
            // $button .= $info;
            // $button .= $view;
            // $button .= $ubah;
            $button .= $voucher;
            $button .= '</div>';

            if($a->Active == 0):
            	$active = '<i class="icon fa-trash pull-right" aria-hidden="true"></i>';
            endif;

            if($a->Status == "proccess"):
            	$status = $this->lang->line('lb_trans_process');
            elseif($a->Status == "finish"):
            	$status = $this->lang->line('lb_trans_success');
            else:
            	$status = $this->lang->line('lb_trans_cancel');
            endif;

            $qty  		= $a->parentQty;
            $QtyModule 	= $a->Qty;
            if($a->Module == "2"):
            	$qty = $a->Qty;
            	$QtyModule = 0;
            endif;

            $code =  '<a href="javascript:void(0)" title="View" onclick="edit('."'".$a->VoucherID."'".','."'info'".')">'.$a->Code.'</a>';
            if($modul == "transaction" && $a->StatusTransfer == "proccess" && $a->Status == "proccess" || $modul == "transaction" && $a->StatusTransfer == "finish" && $a->Status == "proccess"):
           		$code = '<a href="javascript:void(0)" title="Confimation" onclick="edit('."'".$a->VoucherID."'".','."'confirmation'".')">'.$a->Code.'</a>'; 
           	endif;

			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $a->Date;
			$row[] 	= $code;
			$row[] 	= $a->Type;
			$row[] 	= number_format($qty,0);
			$row[] 	= number_format($QtyModule,0);
			$row[] 	= "IDR ".number_format($a->Price,0,".",",");
			$row[] 	= $a->Bank;
			$row[] 	= $status.$remark2;
			$row[]	= $a->nama;	
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->voucher->count_all($modul),
			"recordsFiltered" => $this->voucher->count_filtered($modul),
			"data"			  => $data,
		);
		echo json_encode($output);
	}
	public function ajax_edit($id)
	{
		$voucher_devices 	= array();
		$voucher_module 	= array();
		$data_voucher 		= $this->voucher->get_by_id($id);
		$list_voucher 		= $this->voucher->get_list_voucher($id);

		foreach($list_voucher as $lv):
			$UseDate 	= "";
			$ExpireDate = "";
			$usedName 	= "";
			$usedCompany= "";
			if($lv->Status == "used"):
				$usedName  = $lv->usedName;
				$usedCompany= $lv->usedCompany;
				$UseDate 	= date("Y-m-d H:i",strtotime($lv->UseDate));
				$ExpireDate = date("Y-m-d",strtotime($lv->ExpireDate));
			endif;

			$item = array(
				"usedName"			=> $usedName,
				"usedCompany"		=> $usedCompany,
				"VoucherDetailID" 	=> $lv->VoucherDetailID,
				"Code"				=> $lv->Code,
				"Status"			=> $lv->Status,
				"UseDate"			=> $UseDate,
				"ExpireDate"		=> $ExpireDate,
				"Module"			=> $this->main->label_modul2($lv->Module),
			);
			if($data_voucher->Module == "1"):
				array_push($voucher_module, $item);
			else:
				array_push($voucher_devices, $item);
			endif;
		endforeach;

		if($data_voucher->parentVoucherID):
			$list_voucher_parent = $this->voucher->get_list_voucher($data_voucher->parentVoucherID);
			foreach($list_voucher_parent as $lv):
				$UseDate 	= "";
				$ExpireDate = "";
				$usedName 	= "";
				$usedCompany= "";
				if($lv->Status == "used"):
					$usedName  = $lv->usedName;
					$usedCompany= $lv->usedCompany;
					$UseDate 	= date("Y-m-d H:i",strtotime($lv->UseDate));
					$ExpireDate = date("Y-m-d",strtotime($lv->ExpireDate));
				endif;

				$item = array(
					"usedName"			=> $usedName,
					"usedCompany"		=> $usedCompany,
					"VoucherDetailID" 	=> $lv->VoucherDetailID,
					"Code"				=> $lv->Code,
					"Status"			=> $lv->Status,
					"UseDate"			=> $UseDate,
					"ExpireDate"		=> $ExpireDate,
				);
				if($data_voucher->parentModule == "module"):
					array_push($voucher_module, $item);
				else:
					array_push($voucher_devices, $item);
				endif;
			endforeach;
		endif;

		$output = array(
			"data"				=> $data_voucher,
			"voucher_module"	=> $voucher_module,
			"voucher_devices"	=> $voucher_devices,
		);
		header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
	}
	public function simpan()
	{
		$this->_validate("buy");
		$insert 		= 0;
		$App    		= $this->input->post("App");
        $Type   		= $this->input->post("Type");
        $Qty    		= $this->input->post("Qty");
        $QtyModule      = $this->input->post("Module");
        $price  		= 0.00;
        $price_total 	= 0.00;
        $price_module   = 0.00;
        $module_total   = 0.00;
        $device_total   = 0.00;
        $a      		= $this->main->voucher_package($App,$Type,"2"); // additional
        $module         = $this->main->voucher_package($App,$Type,"1");
        if($a):
            $price       = $a->Price;
            $price_total = $price * $Qty * $Type;
        endif;

        if($module):
            $price_module = $module->Price;
            $module_total = $price_module * $QtyModule * $Type;
        endif;

        $TrxUnique 		 = mt_rand(200, 499);
        if($TrxUnique):
        	$TrxUniqueLg 	= strlen($TrxUnique);
        	$price_totalLg 	= strlen($price_total);
        	$substr 		= $price_totalLg - $TrxUniqueLg;
        	// $price_total 	= substr($price_total, 0,$substr)+$TrxUnique;
        	$price_total 	= $price_total+$TrxUnique;
        	$module_total 	= $module_total + $TrxUnique;
        endif;
        $code = $this->main->transaction_voucher_generate();

        if($QtyModule != "none"):
        	$data_module = array(
        		"CompanyID"		=> $this->session->CompanyID,
        		"Code"			=> $code,
        		'Date'			=> date("Y-m-d"),
        		'App'			=> $this->input->post("App"),
        		'Type'			=> $this->input->post("Type"),
        		'Bank'			=> $this->input->post("Bank"),
        		'Price'			=> str_replace(",", "", $price_module),
        		'TotalPrice'	=> str_replace(",", "", $module_total),
        		'Qty'			=> $QtyModule,
        		'ExpirePurchase'=> date("Y-m-d",strtotime("+7days")),
        		'TrxUnique' 	=> $TrxUnique,
        		'Module'		=> "1",
        	);
        	$insert = $this->voucher->save($data_module);
        endif;

        if($Qty != "none"):
        	$data 	= array(
				'CompanyID'		=> $this->session->CompanyID,
				'Date'			=> date("Y-m-d"),
				'App'			=> $this->input->post("App"),
				'Type'			=> $this->input->post("Type"),
				'Bank'			=> $this->input->post("Bank"),
				'Price'			=> str_replace(",", "", $price),
				'TotalPrice'	=> str_replace(",", "", $price_total),
				'Qty'			=> $Qty,
				'ExpirePurchase'=> date("Y-m-d",strtotime("+7days")),
				'TrxUnique' 	=> $TrxUnique,
				'Module'		=> "2",
			);
			if($QtyModule == "none"):
				$data['Code']		= $code;
				$insert = $this->voucher->save($data);
			else:
				$data['ParentID']	= $insert;
				$insert2 = $this->voucher->save($data);
			endif;
        endif;
		
		if($insert):
        	$this->main->send_email("buy_voucher",$insert);
		endif;
		echo json_encode(array("status" => TRUE,"pesan" => "yoi"));
	}

	public function ajax_update($modul = "")
	{
		if($modul == "transaction"):
			$this->_validate("finish");
			$data = array(
				"status" => "finish",
			);
			$this->voucher->generate_voucher($this->input->post("VoucherID"));
        	$this->main->send_email("acc_voucher",$this->input->post('VoucherID'));
		else:
			$this->_validate("confirmation");
			$data = array(
				'TransferDate'		=> date("Y-m-d",strtotime($this->input->post("TransferDate"))),
				'AccountBank'		=> $this->input->post("AccountBank"),
				'AccountName'		=> $this->input->post("AccountName"),
				'AccountNumber'		=> $this->input->post("AccountNumber"),
				'Remark'			=> $this->input->post("Remark"),
				'TransferAmount' 	=> str_replace(",", "", $this->input->post('TransferAmount')),
				'StatusTransfer'	=> 'finish',
			);
		endif;
		$this->voucher->update(array('VoucherID' => $this->input->post('VoucherID')), $data);
		echo json_encode(array("status" => TRUE,"pesan" => $this->input->post("status")));
	}
	public function tes(){
		echo $this->voucher->generate_token_();
	}
	public function ajax_delete($id,$status="")
	{
		$this->voucher->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}
	private function _validate($page = "")
	{
		$data 					= array();
		$data['error_string'] 	= array();
		$data['inputerror'] 	= array();
		$data['status'] 		= TRUE;
		if($page == "buy"){
			if($this->input->post('Type') == 'none')
			{
				$data['inputerror'][] 	= 'Type';
				$data['error_string'][] = $this->lang->line('lb_voucher_type_choose');
				$data['status'] 		= FALSE;
			}
			if($this->input->post('Bank') == 'none')
			{
				$data['inputerror'][] 	= 'Bank';
				$data['error_string'][] = $this->lang->line('v_select_bank');
				$data['status'] 		= FALSE;
			}
			if($this->input->post('Qty') == 'none' and $this->input->post("Module") == "none")
			{
				$data['inputerror'][] 	= 'Qty';
				$data['error_string'][] = $this->lang->line('lb_choose_qty');
				$data['inputerror'][] 	= 'Module';
				$data['error_string'][] = $this->lang->line('lb_choose_qty');
				$data['status'] 		= FALSE;
			}
		} elseif($page == "confirmation"){
			if($this->input->post('TransferDate') == '')
			{
				$data['inputerror'][] 	= 'TransferDate';
				$data['error_string'][] = 'Transfer Date cannot be null';
				$data['status'] 		= FALSE;
			}
			if($this->input->post('AccountBank') == 'none')
			{
				$data['inputerror'][] 	= 'AccountBank';
				$data['error_string'][] = 'Please choose Bank';
				$data['status'] 		= FALSE;
			}
			if($this->input->post('AccountName') == '')
			{
				$data['inputerror'][] 	= 'AccountName';
				$data['error_string'][] = 'Account Name cannot be null';
				$data['status'] 		= FALSE;
			}
			if($this->input->post('AccountNumber') == '')
			{
				$data['inputerror'][] 	= 'AccountNumber';
				$data['error_string'][] = 'Account Number cannot be null';
				$data['status'] 		= FALSE;
			}
			if($this->input->post('TransferAmount') < 1)
			{
				$data['inputerror'][] 	= 'TransferAmount';
				$data['error_string'][] = 'Transfer Amount cannot be null';
				$data['status'] 		= FALSE;
			}
		} elseif($page == "finish"){
			if($this->session->hak_akses != "super_admin"):
				$data['inputerror'][] 	= '';
				$data['error_string'][] = '';
				$data['message']		= "Error 404";
				$data['status'] 		= FALSE;
			endif;
		}

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


		$this->db->where("VoucherID",$id);
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

	// used voucher
	public function ajax_use_voucher_list($url_modul = "",$modul = ""){
		$id_url = $this->main->id_menu($url_modul);
		$list 	= $this->voucher->used_get_datatables($modul);
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;

		foreach ($list as $a) {

			$status = '<span class="info-green">available<span>';
			if($a->Status == "used"):
				$status = '<span class="info-red">not available</span>';
			endif;

			if($a->module_type == 2):
				$module = $this->lang->line('additional');
			else:
				$module = $this->main->label_modul2($a->Module);
			endif;

			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $a->Code;
			$row[] 	= $a->usedName;
			$row[] 	= $a->usedCompany;
			$row[] 	= $a->Type;
			$row[]	= $module;
			$row[] 	= $a->UseDate;
			$row[] 	= $a->ExpireDate;
			$data[] = $row;
		}

		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->voucher->used_count_all($modul),
			"recordsFiltered" => $this->voucher->used_count_filtered($modul),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function save_use_voucher(){
		$this->validate_use_voucher();

		$module		= $this->input->post('voucher_module');
		$voucher 	= $this->input->post('voucher');
		$usedate 	= date("Y-m-d H:i:s");
		$date_now  	= date("Y-m-d");

		$module_company = $this->main->get_module_company();

		$date_ap = $module_company->ap->expire;
        $date_ar = $module_company->ar->expire;
        $date_ac = $module_company->ac->expire;
        $date_inventory = $module_company->inventory->expire;
        $date_aset = $module_company->asset->expire;

        $status_ap = $module_company->ap->status;
        $status_ar = $module_company->ar->status;
        $status_ac = $module_company->ac->status;
        $status_inventory = $module_company->inventory->status;
        $status_asset = $module_company->asset->status;

		foreach ($voucher as $key => $value) {
			$code = $voucher[$key];

			$list_voucher = $this->voucher->get_list_voucher($code,"detail");

			$month 		= $list_voucher->voucherType;
			$ExpireDate = date("Y-m-d", strtotime(date("Y-m-d")." +".$month." Month"));

			if($module[$key] == "ap"):
				$status_ap = 1;
				if($date_ap >= $date_now):
					$date_ap = date("Y-m-d", strtotime($date_ap." +".$month." Month"));
				else:
					$date_ap = date("Y-m-d", strtotime($date_now." +".$month." Month"));
				endif;

			elseif($module[$key] == "ar"):
				$status_ar = 1;
				if($date_ar >= $date_now):
					$date_ar = date("Y-m-d", strtotime($date_ar." +".$month." Month"));
				else:
					$date_ar = date("Y-m-d", strtotime($date_now." +".$month." Month"));
				endif;

			elseif($module[$key] == "ac"):
				$status_ac = 1;
				if($date_ac >= $date_now):
					$date_ac = date("Y-m-d", strtotime($date_ac." +".$month." Month"));
				else:
					$date_ac = date("Y-m-d", strtotime($date_now." +".$month." Month"));
				endif;

			elseif($module[$key] == "inventory"):
				$status_inventory = 1;
				if($date_inventory >= $date_now):
					$date_inventory = date("Y-m-d", strtotime($date_inventory." +".$month." Month"));
				else:
					$date_inventory = date("Y-m-d", strtotime($date_now." +".$month." Month"));
				endif;
			endif;

			$data_voucher = array(
				"Status"		=> "used",
				"ExpireDate" 	=> $ExpireDate,
				"UsedID"		=> $this->session->id_user,
				"UsedCompanyID"	=> $this->session->CompanyID,
				"UseDate"		=> $usedate,
				"Module"		=> $module[$key],
			);

			$this->voucher->update_detail(array("VoucherDetailID"	=> $list_voucher->VoucherDetailID), $data_voucher);

		}

		$data['ar'] = array(
            "status"    => $status_ar,
            "expire"    => $date_ar,
        );

        $data['ap'] = array(
            "status"    => $status_ap,
            "expire"    => $date_ap,
        );

        $data['ac'] = array(
            "status"    => $status_ac,
            "expire"    => $date_ac,
        );

        $data['inventory'] = array(
            "status"    => $status_inventory,
            "expire"    => $date_inventory,
        );

        $data['asset'] = array(
            "status"    => $status_asset,
            "expire"    => $date_aset,
        );

        $m_company = json_encode($data);

        $data_company = array(
        	"Module"	=> $m_company,
        	"user_ch"	=> $this->session->NAMA,
        	"date_ch"	=> date("Y-m-d H:i:s"),
        );
        $this->db->where("id_user", $this->session->CompanyID);
        $this->db->update("user", $data_company);
        $this->main->setting_parameter();

		$output = array(
			"status"	=> TRUE,
			"message"	=> $this->lang->line('lb_success'),
			"hakakses"	=> $this->session->hak_akses,
		);

		$this->main->echoJson($output);
	}

	private function validate_use_voucher(){
		$data 					= array();
		$data['error_string'] 	= array();
		$data['inputerror'] 	= array();
		$data['status'] 		= TRUE;

		$arr = array("ap","ar","inventory","ac");

		$rowid 	= $this->input->post('rowid');
		$module	= $this->input->post('voucher_module');
		$voucher= $this->input->post('voucher');

		foreach ($voucher as $key => $v) {
			$code = $voucher[$key];
			$cek = $this->db->count_all("VoucherDetail dt join Voucher mt on dt.VoucherID = mt.VoucherID where dt.Code = '$code' and dt.Status = 'not' and mt.Module = '1'");
			if($cek<=0):
				$data['inputerror'][] 	= $rowid[$key];
				$data['error_string'][] = $this->lang->line('lb_voucher_not_found');
				$data['status'] 		= FALSE;
			endif;

			if(!$code):
				$data['inputerror'][] 	= $rowid[$key];
				$data['error_string'][] = $this->lang->line('lb_voucher_enter_validate');
				$data['status'] 		= FALSE;
			endif;

			if(!in_array($module[$key], $arr)):
				$data['inputerror'][] 	= $rowid[$key];
				$data['error_string'][] = $this->lang->line('lb_module_validate');
				$data['status'] 		= FALSE;
			endif;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}
