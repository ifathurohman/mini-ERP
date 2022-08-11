<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_ar extends CI_Controller {
	var $title = "Receivables Payment";
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_payment_ar",'payment');
		$this->main->cek_session();
		$this->title = $this->lang->line('lb_paymentar');
	}

	public function index()
	{	
		$ID 	= $this->input->post("ID");
		$Status = $this->input->post("Status");

		$url 	= $this->uri->segment(1); 
		$id_url = $this->main->id_menu($url);
		$read 	= $this->main->read($id_url);
		$ar 	= $this->main->check_parameter_module("ar","payment_ar");
		if($read == 0 or $ar->view == 0){ redirect(); }
		$selling_tambah 				= $this->main->menu_tambah($id_url);
		if($selling_tambah > 0 and $ar->add>0):
            $tambah = '<button type="button" class="btn btn-blue" onclick="tambah()" >'.$this->lang->line('lb_add_new')." ".$this->title.'</button>';
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['title']  		= $this->title;
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'payment_ar/modal';
		$data['modal_vendor'] 	= 'modal/modal_vendor';
		$data['page'] 			= 'payment_ar/list';
		$data['modul'] 			= 'payment_ar';
		$data['url_modul'] 		= $url;
		$data['ID']				= $ID;
		$data['Status']			= $Status;
		$this->load->view('index',$data);
	}

	public function ajax_list($url_modul="",$modul=""){
		$CompanyID  = $this->session->CompanyID;
		$id_url = $this->main->id_menu($url_modul);
		$edit 	= $this->main->menu_ubah($id_url);
		$delete = $this->main->menu_hapus($id_url);
		$list 	= $this->payment->get_datatables();
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		$ar 	= $this->main->check_parameter_module("ar","payment_ar");
		foreach ($list as $a) {
			$btn_edit 		= '';
			$btn_cancel 	= '';
			$btn_view 		= '';
			$PaymentNo 		= str_replace("/", "-", $a->PaymentNo);
			
			$status 		= $this->main->label_active($a->Status,"",$PaymentNo);
			$btn_view 		= $this->main->button_action_dropdown("view", $PaymentNo);
			$btn_print 		= $this->main->button_action_dropdown("print", $PaymentNo);
			if($edit>0 and $ar->add>0):
				$btn_edit 	= $this->main->button_action_dropdown("edit", $PaymentNo);
			endif;

			if($delete>0 and $ar->add>0):
				if($a->Status == 1):
					$btn_cancel = $this->main->button_action_dropdown("cancel", $PaymentNo);
				else:
					$btn_edit = '';
				endif;
			endif;

			$button  = '<div class="btn-group pointer">';
			$button .= '<div data-toggle="dropdown" aria-expanded="true">';
			$button .= '<i class="fal fa-cog"></i> <span class="caret"></span> </div>';
			$button .= '<ul class="dropdown-menu animate">';
			$button .= $btn_view;
			$button .= $btn_print;
			$button .= $btn_edit;
			$button .= $btn_cancel;
	        $button .= ' </ul>';
	        $button .= '</div>';

	        $btn_action 	= $this->main->button_action("code", $PaymentNo,$a->PaymentNo);
	        $vendor 		= $this->main->button_action("general_onclick","redirect_post('partner','".$a->VendorID."')",$a->vendorName);

			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $btn_action;
			$row[] 	= date("Y-m-d", strtotime($a->Date)); 
			$row[] 	= $vendor; 
			$row[] 	= $this->main->currency($a->Total); 
			$row[] 	= $this->main->currency($a->Total); 
			$row[] 	= $status;
			// $row[] 	= $button; 
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->payment->count_all(),
			"recordsFiltered" => $this->payment->count_filtered(),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function save(){
		$this->validate();

		$CompanyID 		= $this->session->CompanyID;
		$crud 			= $this->input->post('crud');
		$PaymentNo 		= $this->input->post('PaymentNo');
		$Date 			= $this->input->post('Date');
		$PaymentType 	= $this->input->post('PaymentType1');
		$PaymentType1 	= $this->input->post('PaymentType2');
		$PaymentType2 	= $this->input->post('PaymentType3');
		$paymentmethod	= $this->input->post('pay_paymentmethod1');
		$paymentmethod1	= $this->input->post('pay_paymentmethod2');
		$paymentmethod2	= $this->input->post('pay_paymentmethod3');
		$CustomerID 	= $this->input->post('CustomerID');
		$CustomerID 	= explode("-", $CustomerID);
		$Remark 		= $this->input->post('Remark');
		$TotalPay 		= $this->input->post('TotalPay');
		$TotalPaid 		= $this->input->post('TotalPaid');
		$GiroNo 		= $this->input->post('GiroNo');
		$AcountNo 		= $this->input->post('AccountNo');
		$BankName 		= $this->input->post('BankName');
		$AccountName 	= $this->input->post('AccountName');
		$BankName1 		= $this->input->post('BankName1');
		$AccountName1 	= $this->input->post('AccountName1');

		$check 			 = $this->input->post('check');
		$idnya 			 = $this->input->post('idnya');
		$detid 			 = $this->input->post('detid');
		$invoiceno 		 = $this->input->post('invoiceno');
		$balanceid 		 = $this->input->post('balanceid');
		$balancedetid 	 = $this->input->post('balancedetid');
		$trans_type 	 = $this->input->post('transaction_type');
		$det_totalpaid 	 = $this->input->post('det_totalpaid');
		$det_totalpay 	 = $this->input->post('det_totalpay');
		$det_totalunpaid = $this->input->post('det_totalunpaid');
		$det_remark 	 = $this->input->post('det_remark');

		$pay_cash 		 = $this->input->post("pay_cash");
		$pay_credit 	 = $this->input->post("pay_credit");
		$pay_giro 		 = $this->input->post("pay_giro");
		$grandtotal 	 = $this->input->post("grandtotal");

		// // cash
		// if($PaymentType == 1):
		// 	$GiroNo  	 = null;
		// 	$AccountNo 	 = null;
		// 	$BankName 	 = null;
		// 	$AccountName = null;
		// endif;
		// // transfer
		// if($PaymentType1 == 2):
		// 	$GiroNo  	 = null;
		// endif;
		// // giro
		// if($PaymentType2 == 3):
		// 	$AccountNo 	 = null;
		// 	$AccountName = null;
		// 	$BankName 	 = null;
		// endif;

		$data = array(
			"CompanyID" 	=> $CompanyID,
			"Type"			=> 3,
			"Total"			=> $this->main->checkDuitInput($TotalPaid),
			"GrandTotal"	=> $this->main->checkDuitInput($TotalPaid),
			"VendorID"		=> $CustomerID[0],
			"Remark" 		=> $Remark,
			"Date" 			=> $Date,
		);
		if($PaymentType == 1):
			$data['Cash'] = $this->main->checkDuitInput($pay_cash);
			$data['PaymentMethod']  = $paymentmethod;
			$data['PaymentType']  	= 0;
		endif;
		if($PaymentType1 == 2):
			$data['Credit'] = $this->main->checkDuitInput($pay_credit);
			$data['PaymentMethod1'] = $paymentmethod1;
			$data['AccountName']	= $AccountName;
			$data['BankName']		= $BankName;
			$data['AcountNo']		= $AcountNo;
			$data['PaymentType1']  	= 1;
		endif;
		if($PaymentType2 == 3):
			$data['Giro'] 			= $this->main->checkDuitInput($pay_giro);
			$data['GiroNo'] 		= $GiroNo;
			$data['PaymentMethod2'] = $paymentmethod2;
			$data['AccountName1']	= $AccountName1;
			$data['BankName1']		= $BankName1;
			$data['PaymentType2']  	= 2;
		endif;

		if($crud == "update"):
			$code = $PaymentNo;
			$this->return_status($code);
			$this->payment->update(array("PaymentNo" => $code, "CompanyID" => $CompanyID), $data);
		else:
			$code = $this->main->payment_ar_generate();
			$data['PaymentNo'] 	= $code;
			$data['Status']		= 1;
			$this->payment->save($data);
		endif;

		$paymentdetid 	= array();
		foreach ($idnya as $key => $v) {
			$xinvoiceno = str_replace("/", "-", $idnya[$key]);
			if(in_array($xinvoiceno, $check)):
				$data_detail = array(
					"PaymentNo"		=> $code,
					"CompanyID"		=> $CompanyID,
					"InvoiceNo"		=> $invoiceno[$key],
					"Total"			=> $this->main->checkDuitInput($det_totalpaid[$key]),
					"TotalPay"		=> $this->main->checkDuitInput($det_totalpay[$key]),
					"TotalUnpaid"	=> $this->main->checkDuitInput($det_totalunpaid[$key]),
					"Date"			=> $Date,
					"Remark" 		=> $det_remark[$key],
					"Type"			=> $trans_type[$key],
				);

				// Invoice
				if($trans_type[$key] == 1):
					$data_detail['InvoiceNo'] 	 = $invoiceno[$key];
					$data_detail['BalanceID'] 	 = null;
					$data_detail['BalanceDetID'] = null;
				// koreksi
				else:
					$data_detail['InvoiceNo'] 	 = null;
					$data_detail['BalanceID'] 	 = $balanceid[$key];
					$data_detail['BalanceDetID'] = $balancedetid[$key];

					$total 	= $this->main->checkDuitInput($det_totalpaid[$key]);
					$id 	= $balancedetid[$key];
					$this->db->query("
					UPDATE AC_BalancePayable_Det 
					set Payment = Payment + $total
					WHERE CompanyID='$CompanyID' and BalanceDetID = $id");

				endif;

				if($detid[$key] == ""):
					$PaymentDet = $this->payment->save_det($data_detail);
				else:
					$PaymentDet = $detid[$key];
					$where = array("CompanyID" => $CompanyID, "PaymentDet" => $PaymentDet, "PaymentNo" => $code);
					$this->payment->update_det($where, $data_detail);
				endif;
				array_push($paymentdetid, $PaymentDet);

				if($trans_type[$key] == 1):
					$this->check_payment_status($invoiceno[$key],$det_totalpay[$key]);
				else:
					$this->check_koreksi_status($balancedetid[$key],$det_totalpay[$key]);
				endif;
			endif;
		}

		$this->deletepaymentdet($paymentdetid,$code);

		// generate journal
		$this->db->query("CALL run_generate_jurnal('UPDATE', '$code', 'paymentar', '$CompanyID')");

		$res = array(
			"status" 	=> true,
			"message"	=> $this->lang->line('lb_success'),
			"ID"		=> $code,
		);

		$this->main->echoJson($res);
		
	}

	private function validate(){

		// $data['inputerror'][] 	= "";
		// $data['error_string'][] = '';
		// $data['list'][] 		= '';
		// $data['tab'][] 			= '';
		// $data['ara'] 			= $this->input->post();
		// $data['status'] 		= FALSE;

		// $this->main->echoJson($data);
		// exit();

		$this->main->validate_update();
		$this->main->validate_modlue_add("ar","ar");
		$data = array();
		$data['status'] = TRUE;

		$CompanyID 		 = $this->session->CompanyID;
		$check 			 = $this->input->post('check');
		$PaymentType1 	 = $this->input->post('PaymentType1');
		$PaymentType2 	 = $this->input->post('PaymentType2');
		$PaymentType3	 = $this->input->post('PaymentType3');
		$idnya 			 = $this->input->post('idnya');
		$invoiceno 		 = $this->input->post('invoiceno');
		$det_totalpaid 	 = $this->input->post('det_totalpaid');
		$det_totalunpaid = $this->input->post('det_totalunpaid');
		$grandtotal 	 = $this->input->post('grandtotal');

		if($check):
			$status_invoice = true;
			foreach ($idnya as $key => $value) {
				$xinvoiceno = str_replace("/", "-", $idnya[$key]);
				if(in_array($xinvoiceno, $check)):
					$xdet_totalpaid  	= $this->main->checkDuitInput($det_totalpaid[$key]);
					$xdet_totalunpaid 	= $this->main->checkDuitInput($det_totalunpaid[$key]);
					if($xdet_totalpaid>0 || $xdet_totalpaid != 0):
						if($xdet_totalpaid>$xdet_totalunpaid):
							$data['inputerror'][] 	= $xinvoiceno;
							$data['error_string'][] = $this->lang->line('lb_paid_exceeding');
							$data['list'][] 		= 'list';
							$data['tab'][] 			= '';
							$data['message'] 		= $this->lang->line('lb_paid_exceeding');
							$data['status'] 		= FALSE;
							$status_invoice 		= false;
						endif;
					elseif($xdet_totalpaid == 0 and $xdet_totalunpaid == 0):
					else:
						$data['inputerror'][] 	= $xinvoiceno;
						$data['error_string'][] = $this->lang->line('lb_paid_empty');
						$data['list'][] 		= 'list';
						$data['tab'][] 			= '';
						$data['message'] 		= $this->lang->line('lb_paid_empty');
						$data['status'] 		= FALSE;
						$status_invoice 		= false;
					endif;
				endif;
			}
			if(!$status_invoice):
				echo json_encode($data);
				exit();
			endif;
		else:
			$data['inputerror'][] 	= '';
			$data['error_string'][] = '';
			$data['list'][] 		= '';
			$data['message'] 		= $this->lang->line('lb_select_item');
			$data['status'] 		= FALSE;
			echo json_encode($data);
			exit();
		endif;

		if($this->input->post('CustomerID') == ''):
			$data['inputerror'][] 	= 'CustomerID';
			$data['error_string'][] = $this->lang->line('lb_customer_empty');
			$data['list'][] 		= '';
			$data['message'] 		= $this->lang->line('lb_incomplete_form');
			$data['status'] 		= FALSE;
		endif;

		if($PaymentType1 == 1):
			$pay_paymentmethod1 = $this->input->post('pay_paymentmethod1');
			if($pay_paymentmethod1 == "none"):
				$data['inputerror'][] 	= 'pay_paymentmethod1';
				$data['error_string'][] = $this->lang->line('lb_payment_method_empty');
				$data['list'][] 		= '';
				$data['message'] 		= $this->lang->line('lb_incomplete_form');
				$data['status'] 		= FALSE;
			endif;
		endif;
		if($PaymentType2 == 2):
			$pay_paymentmethod2 = $this->input->post('pay_paymentmethod2');
			if($pay_paymentmethod2 == "none"):
				$data['inputerror'][] 	= 'pay_paymentmethod2';
				$data['error_string'][] = $this->lang->line('lb_payment_method_empty');
				$data['list'][] 		= '';
				$data['message'] 		= $this->lang->line('lb_incomplete_form');
				$data['status'] 		= FALSE;
			endif;
		endif;
		if($PaymentType3 == 3):
			$pay_paymentmethod3 = $this->input->post('pay_paymentmethod3');
			if($pay_paymentmethod3 == "none"):
				$data['inputerror'][] 	= 'pay_paymentmethod3';
				$data['error_string'][] = $this->lang->line('lb_payment_method_empty');
				$data['list'][] 		= '';
				$data['message'] 		= $this->lang->line('lb_incomplete_form');
				$data['status'] 		= FALSE;
			endif;
		endif;		

		if($this->input->post('TotalPaid') > $this->input->post("grandtotal") || $this->input->post("grandtotal") > $this->input->post('TotalPaid')  ):
            $data['inputerror'][]   = 'grandtotal';
            $data['error_string'][] = $this->lang->line('lb_paid_incorect');
            $data['list'][] 		= '';
			$data['message'] 		= $this->lang->line('lb_incomplete_form');
			$data['status'] 		= FALSE;
        endif;

		if($data['status'] === FALSE)
        {
            header('Content-Type: application/json');
            echo json_encode($data,JSON_PRETTY_PRINT);  
            exit();
        }
	}

	private function deletepaymentdet($array,$PaymentNo){
		if(count($array)>0):
			$CompanyID 		= $this->session->CompanyID;
			$this->db->where_not_in("PaymentDet", $array);
			$this->db->where("CompanyID", $CompanyID);
			$this->db->where("PaymentNo", $PaymentNo);
			$this->db->delete("PS_Payment_Detail");
		endif;
	}

	private function check_payment_status($invoiceno,$totalpay){
		$CompanyID = $this->session->CompanyID;
		$query = $this->db->query("
            SELECT ifnull(sum(ps_pd.Total), 0) as Total from PS_Payment_Detail as ps_pd left join PS_Payment as ps_p 
            on ps_pd.PaymentNo = ps_p.PaymentNo and ps_pd.CompanyID = ps_p.CompanyID
            where ps_pd.CompanyID = '$CompanyID' and ps_pd.InvoiceNo = '$invoiceno' and ps_p.Status = 1 and ps_p.Type = 3
            ");
		$totalpaid  = (float) $query->row()->Total;
		$totalpay 	= (float) $this->main->checkDuitInput($totalpay);
		$this->db->where("CompanyID", $CompanyID);
		$this->db->where("InvoiceNo", $invoiceno);
		if($totalpaid >= $totalpay):
			$this->db->update("PS_Invoice", array("PaymentStatus" => 1));
		else:
			$this->db->update("PS_Invoice", array("PaymentStatus" => 0));
		endif;

		// $this->main->CostPaid($invoiceno,$totalpaid);
	}

	private function check_koreksi_status($balancedetid,$totalpay){
		$CompanyID = $this->session->CompanyID;
		$query = $this->db->query("
            SELECT ifnull(sum(ps_pd.Total), 0) as Total from PS_Payment_Detail as ps_pd left join PS_Payment as ps_p 
            on ps_pd.PaymentNo = ps_p.PaymentNo and ps_pd.CompanyID = ps_p.CompanyID
            where ps_pd.CompanyID = '$CompanyID' and ps_pd.BalanceDetID = '$balancedetid' and ps_p.Status and ps_p.Type = 3
            ");
		$totalpaid  = (float) $query->row()->Total;
		$totalpay 	= (float) $this->main->checkDuitInput($totalpay);
		$this->db->where("CompanyID", $CompanyID);
		$this->db->where("BalanceDetID", $balancedetid);
		if($totalpaid >= $totalpay):
			$this->db->update("AC_BalancePayable_Det", array("PaymentStatus" => 1));
		else:
			$this->db->update("AC_BalancePayable_Det", array("PaymentStatus" => 0));
		endif;
	}

	private function return_status($paymentno){
		$CompanyID = $this->session->CompanyID;
		$detail = $this->payment->get_by_detail($paymentno);
		foreach ($detail as $k => $v) {

			if($v->Type == 1):
				$this->db->where("CompanyID", $CompanyID);
				$this->db->where("InvoiceNo", $v->InvoiceNo);
				$this->db->update("PS_Invoice", array("PaymentStatus" => 0));
			else:
				$total 	= $v->Total;
				$id 	= $v->BalanceDetID;
				$this->db->query("
					UPDATE AC_BalancePayable_Det 
					set PaymentStatus = '0', Payment = Payment - $total
					WHERE CompanyID='$CompanyID' and BalanceDetID = $id");
			endif;
		}
	}

	public function cetak($id){
		$idnya 		= $id;
		$id 		= str_replace("-", "/", $id);
		$this->main->default_template("payment_ar");
		$cetak 		= $this->input->get("cetak");
		$position 	= $this->input->get("position");
		$page 		= $this->input->get("page");
		$code 		= "Payment-receivable".$id;

		$nama_laporan   = "";
        $datacompany    = $this->main->company("api");
        $company_name   = $datacompany->nama;
        $company_imgbin = $datacompany->img_bin;
        $company_imgurl = $datacompany->img_url;
        $logo           = base_url($company_imgurl);
		
		$list  		= $this->payment->get_by_id($id);
		$detail 	= $this->payment->get_by_detail($id);

		$modul  	= $this->input->post("modul");
		$url_modul 	= $this->input->post("url_modul");
		$id_url 	= $this->main->id_menu($url_modul);
		$edit 		= $this->main->menu_ubah($id_url);
		$delete 	= $this->main->menu_hapus($id_url);

		$ar 	= $this->main->check_parameter_module("ar","ar");
		$data_action = array();
		if($list->Status == 1 && $ar->add>0 && $delete>0):
			$cancel = $this->main->button_action("cancel",$idnya);
	        $data_action['cancel'] = $cancel;
		endif;
		if($ar->add>0 && $edit>0):
			$btn_edit = $this->main->button_action("edit_attach",$idnya);
			$data_action['edit'] = $btn_edit;
		endif;

		$data['page'] 			= $page;
		$data["cetak"]			= $cetak;
		$data['list']			= $list;
		$data["detail"]			= $detail;
		$data['title']  		= 'print payment receivable';
		$data['title2'] 		= $this->title;
		$data["company_name"]   = $company_name;
		$data["nama_laporan"]   = $nama_laporan;
    	$data["logo"]           = $logo;
    	$data["company"]		= $datacompany;
    	$data['data_action']	= $data_action;
    	
    	if($page == "print"):
    		$data['template'] 	= $this->main->get_default_template("payment_ar");
    		$this->load->view('payment_ar/template',$data);
    	else:
    		$this->load->view('payment_ar/view',$data);
    	endif;

		if($cetak == "pdf"):
			$this->load->library('dompdf_gen'); 
			$html = $this->output->get_output();
			if($position == "landscape"):
	   	   		$this->dompdf->set_paper('legal', 'landscape');
	   	   	else:
	   	   		$this->dompdf->set_paper('legal', 'portrait');
	   	   	endif;

	    	$this->dompdf->load_html($html);
	    	$this->dompdf->render();
			$this->dompdf->stream($code.".pdf",array('Attachment'=>0));
		endif;
	}

	public function cancel($id){
		$this->main->validate_modlue_add("ar","ar");
		$id 		= str_replace("-", "/", $id);
		$CompanyID = $this->session->CompanyID;
		$cek = $this->db->count_all("PS_Payment where PaymentNo = '$id' and CompanyID = '$CompanyID'");
		if($cek>0):
			$header = $this->payment->get_by_id($id);
			$detail = $this->payment->get_by_detail($id);
			foreach ($detail as $k => $v) {
				if($v->InvoiceNo):
					$this->db->where("CompanyID", $CompanyID);
					$this->db->where("InvoiceNo", $v->InvoiceNo);
					$this->db->update("PS_Invoice", array("PaymentStatus" => 0));
				endif;

				if($v->BalanceDetID):
					$total 			= (float) $v->Total;
					$BalanceDetID 	= $v->BalanceDetID;
					$this->db->query("
					UPDATE AC_BalancePayable_Det 
					set 
						Payment  		= Payment - $total,
						PaymentStatus 	= '0'
					WHERE CompanyID='$CompanyID' and BalanceDetID = $BalanceDetID");
				endif;
			}

			$data = array("Status" => 0);
			$this->payment->update(array("PaymentNo"=>$id, "CompanyID" => $CompanyID,),$data);

			$res = array(
				'status'	=> true,
				'message' 	=> $this->lang->line('lb_success'),
			);
		else:
			$res = array(
				'status'	=> false,
				'message' 	=> $this->lang->line('lb_data_not_found'),
			);
		endif;
		$this->main->echoJson($res);
	}

	public function ajax_edit($id){
		$id 		= str_replace("-", "/", $id);
		$list  		= $this->payment->get_by_id($id);
		$detail 	= $this->payment->get_by_detail($id);

		$data = array(
			"hakakses"	=> $this->session->hak_akses,
			"app"		=> $this->session->app,
			"list"		=> $list,
			"detail"	=> $detail,
 		);

 		$this->main->echoJson($data);
	}

	public function save_remark(){
		$CompanyID 	= $this->session->CompanyID;
		$ID 		= $this->input->post("ID");
		$Remark 	= $this->input->post("Remark");

		$status 	= false;
		$message 	= $this->lang->line('lb_data_not_found');

		if($CompanyID && $ID):
			$cek = $this->db->count_all("PS_Payment where CompanyID = '$CompanyID' and PaymentNo = '$ID'");
			if($cek>0):
				$data = array(
					"Remark"	=> $Remark,
				);
				$this->db->where("CompanyID", $CompanyID);
				$this->db->where("PaymentNo", $ID);
				$this->db->update("PS_Payment", $data);

				$status = true;
				$message = $this->lang->line('lb_success');
			endif;
		endif;

		$output = array(
			"status" 	=> $status,
			"message" 	=> $message,
			"hakakses"	=> $this->session->hak_akses,
		);

		$this->main->echoJson($output);
	}
}