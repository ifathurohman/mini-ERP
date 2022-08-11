<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_ap extends CI_Controller {
	var $title = 'Payable Invoice';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_invoice_ap",'invoice');
		$this->main->cek_session();
		$this->title = $this->lang->line('lb_invoiceap');
	}

	public function index()
	{	
		$ID 	= $this->input->post("ID");
		$Status = $this->input->post("Status");

		$url 	= $this->uri->segment(1); 
		$id_url = $this->main->id_menu($url);
		$read 	= $this->main->read($id_url);
		$ap 	= $this->main->check_parameter_module("ap","invoice_ap");
		if($read == 0 or $ap->view == 0){ redirect(); }
		$selling_tambah 				= $this->main->menu_tambah($id_url);
		if($selling_tambah > 0 and $ap->add>0):
            $tambah = $this->main->general_button('add',$this->lang->line('lb_add_new').' '.$this->title);
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['title']  		= $this->title;
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'invoice_ap/modal';
		$data['modal_vendor'] 	= 'modal/modal_vendor';
		$data['page'] 			= 'invoice_ap/list';
		$data['modul'] 			= 'invoice_ap';
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
		$list 	= $this->invoice->get_datatables();
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		$ap 	= $this->main->check_parameter_module("ap","invoice_ap");
		foreach ($list as $a) {
			$btn_edit 		= '';
			$btn_cancel 	= '';
			$btn_view 		= '';
			$InvoiceNo 		= str_replace("/", "-", $a->InvoiceNo);
			
			$status 		= $this->main->label_active($a->Status,"",$InvoiceNo);
			$label_type 	= $this->main->label_invoice_type($a->OrderType,"",$InvoiceNo,1);
			$btn_view 		= $this->main->button_action_dropdown("view", $InvoiceNo);
			$btn_print 		= $this->main->button_action_dropdown("print", $InvoiceNo);
			if($edit>0 and $ap->add>0):
				$btn_edit 	= $this->main->button_action_dropdown("edit", $InvoiceNo);
			endif;

			if($delete>0 and $ap->add>0):
				$btn_cancel = $this->main->button_action_dropdown("cancel", $InvoiceNo);
			endif;

			$cek_payment = $a->ck_payment;
			if($cek_payment>0):
				$btn_edit 	= '';
				$btn_cancel = '';
			endif;

			if($a->Status != 1):
				$btn_edit 	= '';
				$btn_cancel = '';
			endif;

			$button  = '<div class="btn-group pointer">';
			$button .= '<div data-toggle="dropdown" aria-expanded="true">';
			$button .= '<i class="fal fa-cog"></i> <span class="caret"></span> </div>';
			$button .= '<ul class="dropdown-menu animate">';
			$button .= $btn_view;
			$button .= $btn_print;
			$button .= $btn_edit;
			$button .= $btn_cancel;
	        $button .= '</ul>';
	        $button .= '</div>';

	        $btn_action 	= $this->main->button_action("code", $InvoiceNo,$a->InvoiceNo);
	        $vendor 		= $this->main->button_action("general_onclick","redirect_post('partner','".$a->VendorID."')",$a->vendorName);

			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $btn_action;
			$row[] 	= $a->Date; 
			$row[] 	= $vendor; 
			$row[] 	= $status; 
			$row[] 	= $label_type;
			$row[] 	= $this->main->currency($a->Total);
			// $row[] 	= $button; 
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->invoice->count_all(),
			"recordsFiltered" => $this->invoice->count_filtered(),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function save(){
		$this->_validate();

		$CompanyID 		= $this->session->CompanyID;

		$crud 			= $this->input->post("crud");
		$CustomerID 	= $this->input->post('CustomerID'); 
		$Date 			= $this->input->post('Date'); 
		$invAddress 	= $this->input->post('invAddress'); 
		$invCity 		= $this->input->post('invCity'); 
		$invProvince 	= $this->input->post('invProvince');
		$invNPWP 		= $this->input->post('NPWP');
		$SubTotal 		= $this->input->post('SubTotal');
		$Discount 		= $this->input->post('Discount');
		$PPN 			= $this->input->post('PPN');
		$DeliveryCost 	= $this->input->post('DeliveryCost');
		$GrandTotal 	= $this->input->post('GrandTotal');
		$Remark 		= $this->input->post('Remark');
		$OrderType 		= $this->input->post('OrderType');
		$Term 		 	= $this->input->post('Term');

		//product
		$check 			= $this->input->post('check');
		$detid 			= $this->input->post('detid');
		$receiveno 		= $this->input->post('receiveno');
		$purchaseno 	= $this->input->post('purchaseno');
		$delDate 		= $this->input->post('delDate');
		$delCost 		= $this->input->post('delCost');
		$delDiscount 	= $this->input->post('delDiscount');
		$delTax 		= $this->input->post('delTax');
		$delSub_total 	= $this->input->post('delSub_total');
		$delTotal 		= $this->input->post('delTotal');
		$delRemark 		= $this->input->post('delRemark');
		$invoicetype 	= $this->input->post('invoicetype');

		$CustomerID = explode("-", $CustomerID);
		$data_inv = array(
			"CompanyID"			=> $CompanyID,
			"VendorID"			=> $CustomerID[0],
			"Date"				=> $Date,
			"Remark"			=> $Remark,
			"Type"				=> 1,
			"Term"				=> $this->main->checkDuitInput($Term),
			"OrderType"			=> $OrderType,
			"InvoiceName"		=> $CustomerID[1],
			"InvoiceAddress" 	=> $invAddress,
			"InvoiceCity"		=> $invCity,
			"InvoiceProvince"	=> $invProvince,
			"InvoiceNPWP"		=> $invNPWP,
			"Total"				=> $this->main->checkDuitInput($GrandTotal),
			"SubTotal"			=> $this->main->checkDuitInput($SubTotal),
			"Discount"			=> $this->main->checkDuitInput($Discount),
			"PPN"				=> $this->main->checkDuitInput($PPN),
			"DeliveryCost"		=> $this->main->checkDuitInput($DeliveryCost),
		);

		if($crud == "insert"):
			$code 	= $this->main->invoice_ap_generate();
			$data_inv['InvoiceNo']	= $code;
			$data_inv['Status']		= 1;
			$this->invoice->save($data_inv);
		else:
			$code 	= $this->input->post('InvoiceNo');
			$this->return_status($code);
			$where = array("CompanyID" => $CompanyID, "InvoiceNo" => $code);
			$this->invoice->update($where, $data_inv);
		endif;

		$invoicedetid 	= array();
		$xjurnalType 	= '';
		#delivery
		if($OrderType == 1):
			$xjurnalType = 'kontrabon_receive';
			foreach ($receiveno as $key => $value) {
				$xreceiveno = str_replace("/", "-", $receiveno[$key]);
				if(in_array($xreceiveno, $check)):
					$data_inv_det = array(
						"CompanyID"		=> $CompanyID,
						"InvoiceNo"		=> $code,
						"Date"			=> $delDate[$key],
						"DeliveryCost"	=> $this->main->checkDuitInput($delCost[$key]),
						"Discount"		=> $this->main->checkDuitInput($delDiscount[$key]),
						"PPN"			=> $this->main->checkDuitInput($delTax[$key]),
						"SubTotal"		=> $this->main->checkDuitInput($delSub_total[$key]),
						"Total"			=> $this->main->checkDuitInput($delTotal[$key]),
						"Remark" 		=> $delRemark[$key],
					);
					if($invoicetype[$key] == "return"):
						$data_inv_det['ReturNo'] 	= $receiveno[$key];
					else:
						$data_inv_det['ReceiveNo']  = $receiveno[$key];
					endif;
					if($detid[$key] == ""):
						$code_det = $this->main->invoice_ap_det_generate();
						$data_inv_det['InvoiceDet'] = $code_det;
						$this->invoice->save_det($data_inv_det);
					else:
						$code_det = $detid[$key];
						$where = array("CompanyID" => $CompanyID, "InvoiceDet" => $code_det, "InvoiceNo" => $code);
						$this->invoice->update_det($where, $data_inv_det);
					endif;
					array_push($invoicedetid, $code_det);
					$data_detail = array("InvoiceStatus" => 1);
					$this->db->where("CompanyID", $CompanyID);
					if($invoicetype[$key] == "return"):
						$this->db->where("ReturNo", $receiveno[$key]);
						$this->db->update("AP_Retur", $data_detail);
					else:
						$this->db->where("ReceiveNo", $receiveno[$key]);
						$this->db->update("AP_GoodReceipt", $data_detail);
					endif;
				endif;
			}
		#selling
		elseif($OrderType == 2):
			$xjurnalType = 'kontrabon';
			foreach ($purchaseno as $key => $value) {
				if(in_array($purchaseno[$key], $check)):
					$data_inv_det = array(
						"CompanyID"		=> $CompanyID,
						"InvoiceNo"		=> $code,
						"Date"			=> $delDate[$key],
						"DeliveryCost"	=> $this->main->checkDuitInput($delCost[$key]),
						"Discount"		=> $this->main->checkDuitInput($delDiscount[$key]),
						"PPN"			=> $this->main->checkDuitInput($delTax[$key]),
						"SubTotal"		=> $this->main->checkDuitInput($delSub_total[$key]),
						"Total"			=> $this->main->checkDuitInput($delTotal[$key]),
						"Remark" 		=> $delRemark[$key],
					);
					if($invoicetype[$key] == "return"):
						$data_inv_det['ReturNo'] 	= $purchaseno[$key];
					else:
						$data_inv_det['PurchaseNo'] = $purchaseno[$key];
					endif;
					if($detid[$key] == ""):
						$code_det = $this->main->invoice_ar_det_generate();
						$data_inv_det['InvoiceDet'] = $code_det;
						$this->invoice->save_det($data_inv_det);
					else:
						$code_det = $detid[$key];
						$where = array("CompanyID" => $CompanyID, "InvoiceDet" => $code_det, "InvoiceNo" => $code);
						$this->invoice->update_det($where, $data_inv_det);
					endif;
					array_push($invoicedetid, $code_det);
					$data_detail = array("InvoiceStatus" => 1);
					$this->db->where("CompanyID", $CompanyID);
					if($invoicetype[$key] == "return"):
						$this->db->where("ReturNo", $purchaseno[$key]);
						$this->db->update("AP_Retur", $data_detail);
					else:
						$this->db->where("PurchaseNo", $purchaseno[$key]);
						$this->db->update("PS_Purchase", $data_detail);
					endif;
				endif;
			}
		else:

		endif;

		$this->deleteinvoicedet($invoicedetid, $code);

		// generate journal
		$this->db->query("CALL run_generate_jurnal('UPDATE', '$code', '$xjurnalType', '$CompanyID')");

		$res = array(
			"status" 	=> true,
			"message"	=> $this->lang->line('lb_success'),
			"ID"		=> $code,
		);

		$this->main->echoJson($res);
	}

	private function _validate(){
		$this->main->validate_update();
		$this->main->validate_modlue_add("ap","ap");
		$CompanyID = $this->session->CompanyID;
		$data = array();
		$data['status'] = TRUE;
		$crud 			= $this->input->post('crud');
		$InvoiceNo 		= $this->input->post('InvoiceNo');
		$OrderType 		= $this->input->post('OrderType');

		// $data['inputerror'][] 	= "";
		// $data['error_string'][] = '';
		// $data['list'][] 		= '';
		// $data['tab'][] 			= '';
		// $data['ara'] 			= $this->input->post();
		// $data['status'] 		= FALSE;

		// $this->main->echoJson($data);
		// exit();

		// product
		$check 		= $this->input->post('check');
		$receiveno  = $this->input->post('receiveno');
		$purchaseno = $this->input->post('purchaseno');
		$status_ar 	= true;
		if($check):
			if($OrderType == 1):
				foreach ($receiveno as $key => $value) {
					if(in_array($receiveno[$key], $check)):
						if($crud == "insert"):
							$cek = $this->db->count_all("
							PS_Invoice_Detail as ivd
							left join PS_Invoice as iv on ivd.InvoiceNo = iv.InvoiceNo and ivd.CompanyID = iv.CompanyID
							where ReceiveNo = '$receiveno[$key]' and ivd.CompanyID = '$CompanyID' and iv.CompanyID = '$CompanyID' and iv.Status = '1'");
						else:
							$cek = $this->db->count_all("
							PS_Invoice_Detail as ivd
							left join PS_Invoice as iv on ivd.InvoiceNo = iv.InvoiceNo and ivd.CompanyID = iv.CompanyID
							where ReceiveNo = '$receiveno[$key]' and ivd.InvoiceNo != '$InvoiceNo' and ivd.CompanyID = '$CompanyID' and iv.CompanyID = '$CompanyID' and iv.Status = '1'");
						endif;
						if($cek>0):
							$data['inputerror'][] 	= $receiveno[$key];
							$data['error_string'][] = 'product_selldet';
							$data['list'][] 		= 'list';
							$data['tab'][] 			= '';
							$data['message'] 		= $this->lang->line('lb_data_exists');
							$data['status'] 		= FALSE;
							$status_ar 				= FALSE;
						endif;
					endif;
				}
			elseif($OrderType == 2):

			else:

			endif;
			if(!$status_ar):
				echo json_encode($data);
				exit();
			endif;
		else:
			$data['inputerror'][] 	= '';
			$data['error_string'][] = '';
			$data['list'][] 		= '';
			$data['message'] 		= $this->lang->line('lb_select_product_empty');
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

		if($this->input->post('invAddress') == ''):
			$data['inputerror'][] 	= 'invAddress';
			$data['error_string'][] = $this->lang->line('lb_address_empty');
			$data['list'][] 		= '';
			$data['message'] 		= $this->lang->line('lb_incomplete_form');
			$data['status'] 		= FALSE;
		endif;

		if($this->input->post('invCity') == ''):
			$data['inputerror'][] 	= 'invCity';
			$data['error_string'][] = $this->lang->line('lb_city_empty');
			$data['list'][] 		= '';
			$data['message'] 		= $this->lang->line('lb_incomplete_form');
			$data['status'] 		= FALSE;
		endif;

		if($this->input->post('invProvince') == ''):
			$data['inputerror'][] 	= 'invProvince';
			$data['error_string'][] = $this->lang->line('lb_province_empty');
			$data['list'][] 		= '';
			$data['message'] 		= $this->lang->line('lb_incomplete_form');
			$data['status'] 		= FALSE;
		endif;

		// if($this->input->post('NPWP') == ''):
		// 	$data['inputerror'][] 	= 'NPWP';
		// 	$data['error_string'][] = 'NPWP cannot be null';
		// 	$data['list'][] 		= '';
		// 	$data['message'] 		= $this->lang->line('lb_incomplete_form');
		// 	$data['status'] 		= FALSE;
		// endif;

		if($data['status'] === FALSE)
        {
            header('Content-Type: application/json');
            echo json_encode($data,JSON_PRETTY_PRINT);  
            exit();
        }
	}

	private function return_status($InvoiceNo){
		$CompanyID    = $this->session->CompanyID;
		$header 	  = $this->invoice->get_by_id($InvoiceNo);
		$data_detail  = array("InvoiceStatus" => 0);
		if($header->OrderType == 1):
			$detail = $this->invoice->get_by_detail($InvoiceNo);
			foreach ($detail as $k => $v) {
				$this->db->where("CompanyID", $CompanyID);
				if($v->invoiceType == "return"):
					$this->db->where("ReturNo", $v->ReturNo);
					$this->db->update("AP_Retur", $data_detail);
				else:
					$this->db->where("ReceiveNo", $v->ReceiveNo);
					$this->db->update("AP_GoodReceipt", $data_detail);
				endif;
			}
		elseif($header->OrderType == 2):
			$detail = $this->invoice->get_by_detail_payable($InvoiceNo);
			foreach ($detail as $k => $v) {
				$this->db->where("CompanyID", $CompanyID);
				if($v->invoiceType == "return"):
					$this->db->where("ReturNo", $v->ReturNo);
					$this->db->update("AP_Retur", $data_detail);
				else:
					$this->db->where("PurchaseNo", $v->SellNo);
					$this->db->update("PS_Purchase", $data_detail);
				endif;
			}
		else:

		endif;
	}

	// hapus data yang tidak digunakan
	private function deleteinvoicedet($array,$InvoiceNo){
		if(count($array)>0):
			$CompanyID 		= $this->session->CompanyID;
			$this->db->where_not_in("InvoiceDet", $array);
			$this->db->where("CompanyID", $CompanyID);
			$this->db->where("InvoiceNo", $InvoiceNo);
			$this->db->delete("PS_Invoice_Detail");
		endif;
	}

	public function cetak($id){
		$idnya 		= $id;
		$id 		= str_replace("-", "/", $id);
		$this->main->default_template("invoice_ap");
		$cetak 		= $this->input->get("cetak");
		$position 	= $this->input->get("position");
		$page 		= $this->input->get("page");
		$code 		= "Invoice".$id;

		$nama_laporan   = "";
        $datacompany    = $this->main->company("api");
        $company_name   = $datacompany->nama;
        $company_imgbin = $datacompany->img_bin;
        $company_imgurl = $datacompany->img_url;
        $logo           = base_url($company_imgurl);
		
		$list  		= $this->invoice->get_by_id($id,"edit");
		if($list->OrderType == 1):
			$detail 	= $this->invoice->get_by_detail($id);
		elseif($list->OrderType == 2):
			$detail 	= $this->invoice->get_by_detail_payable($id);
		else:
			$detail 	= array();
		endif;

		$modul  	= $this->input->post("modul");
		$url_modul 	= $this->input->post("url_modul");
		$id_url 	= $this->main->id_menu($url_modul);
		$edit 		= $this->main->menu_ubah($id_url);
		$delete 	= $this->main->menu_hapus($id_url);

		$ap 	= $this->main->check_parameter_module("ap","ap");
		$data_action = array();
      	$cancel = '';
      	if($list->Status == 1 && $list->PaymentCount<=0 && $ap->add>0 && $delete>0):
	        $cancel = $this->main->button_action("cancel",$idnya);
	        $data_action['cancel'] = $cancel;
      	endif;
		if($ap->add>0 && $list->Status == 1):
			$data_action['next'] = $this->main->button_action("payment_ap",$idnya, $list->PaymentStatus."-invoice");
		endif;
		if($ap->add>0 && $edit>0):
			$btn_edit = $this->main->button_action("edit_attach",$idnya);
			$data_action['edit'] = $btn_edit;
		endif;

		// $this->main->echoJson($detail);

		$data['page'] 			= $page;
		$data["cetak"]			= $cetak;
		$data['list']			= $list;
		$data["detail"]			= $detail;
		$data['title']  		= 'print invoice ap';
		$data['title2'] 		= $this->title;
		$data["company_name"]   = $company_name;
		$data["nama_laporan"]   = $nama_laporan;
    	$data["logo"]           = $logo;
    	$data["company"]		= $datacompany;
    	$data['data_action']	= $data_action;
    	if($page == "print"):
    		$data['template'] 	= $this->main->get_default_template("invoice_ap");
    		$this->load->view('invoice_ap/template',$data);
    	else:
    		$this->load->view('invoice_ap/view',$data);
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
		$this->main->validate_modlue_add("ap","ap");
		$id 		= str_replace("-", "/", $id);
		$CompanyID = $this->session->CompanyID;
		$cek = $this->db->count_all("PS_Invoice where InvoiceNo = '$id' and CompanyID = $CompanyID");
		if($cek>0):
			$header = $this->invoice->get_by_id($id);
			if($header->OrderType == 1):
				$detail = $this->invoice->get_by_detail($id);
				foreach ($detail as $key => $v) {
					$data_detail = array("InvoiceStatus" => 0);
					$this->db->where("CompanyID", $CompanyID);
					if($v->invoiceType == "return"):
						$this->db->where("ReturNo", $v->ReturNo);
						$this->db->update("AP_Retur", $data_detail);
					else:
						$this->db->where("ReceiveNo", $v->ReceiveNo);
						$this->db->update("AP_GoodReceipt", $data_detail);
					endif;
				}
			elseif($header->OrderType == 2):
				$detail = $this->invoice->get_by_detail_payable($id);
				foreach ($detail as $key => $v) {
					$data_detail = array("InvoiceStatus" => 0);
					$this->db->where("CompanyID", $CompanyID);
					if($v->invoiceType == "return"):
						$this->db->where("ReturNo", $v->ReturNo);
						$this->db->update("AP_Retur", $data_detail);
					else:
						$this->db->where("PurchaseNo", $v->PurchaseNo);
						$this->db->update("PS_Purchase", $data_detail);
					endif;
				}
			else:

			endif;
			
			$data = array("Status" => 0);
			$this->invoice->update(array("InvoiceNo"=>$id, "CompanyID" => $CompanyID,),$data);

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
		$list 		= $this->invoice->get_by_id($id);
		
		$detail 			= array();
		$invoice_delivery 	= array();
		$invoice_sell 		= array();
		
		if($list->OrderType == 1):
			$detail 	= $this->invoice->get_by_detail($id);
			$invoice_delivery = $this->main->invoice_receive($list->VendorID);
		elseif($list->OrderType == 2):
			$detail 		= $this->invoice->get_by_detail_payable($id);
			$invoice_sell 	= $this->main->invoice_purchase($list->VendorID);
			foreach ($invoice_sell as $k => $v) {
	            $d = $this->main->get_invoice_purchase_detail($v);
	            $v->SubTotal = $d['sub_total'];
	            $v->Discount = $d['discount'];
	            $v->PPN      = $d['ppn'];
	            $v->Total    = $d['total'];
	            $v->Date     = date("Y-m-d", strtotime($v->Date));
	            $v->list     = $d['list'];
	        }
		else:
			
		endif;

        // foreach ($invoice_delivery as $k => $v) {
        //     $d = $this->main->get_invoice_delivery_detail($v);
        //     $v->price           = $d['price'];
        //     $v->discount        = $d['discount'];
        //     $v->ppn             = $d['ppn'];
        //     $v->deliverycost    = $d['deliverycost'];
        //     $v->total           = $d['total'];
        // }

		$data = array(
			"hakakses"	=> $this->session->hak_akses,
			"app"		=> $this->session->app,
			"list"		=> $list,
			"detail"	=> $detail,
			"delivery"	=> $invoice_delivery,
			"sell" 		=> $invoice_sell,
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
			$cek = $this->db->count_all("PS_Invoice where CompanyID = $CompanyID and InvoiceNo = '$ID'");
			if($cek>0):
				$data = array(
					"Remark"	=> $Remark,
				);
				$this->db->where("CompanyID", $CompanyID);
				$this->db->where("InvoiceNo", $ID);
				$this->db->update("PS_Invoice", $data);

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