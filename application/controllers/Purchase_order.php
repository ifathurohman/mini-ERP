<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_order extends CI_Controller {
	var $title = 'Purchase Order';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_purchase_order",'purchase');
		$this->main->cek_session();
		$this->title = $this->lang->line('lb_purchase');
	}
	public function index()
	{	
        
		$url 			= $this->uri->segment(1); 
		$id_url 		= $this->main->id_menu($url);
		$read 			= $this->main->read($id_url);
		$datacompany    = $this->main->company("api");
		$ap 	= $this->main->check_parameter_module("ap", "po");
		if($read == 0 || $ap->view == 0){ redirect(); }
		$selling_tambah 				= $this->main->menu_tambah($id_url);
		if($selling_tambah > 0 and $ap->add>0):
            $tambah = $this->main->general_button('add',$this->lang->line('lb_add_new').' '.$this->title);
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['title']  					= $this->title;
		$data['tambah'] 					= $tambah;
		$data['modal'] 						= 'purchase_order/modal';
		$data['modal_print']  				= 'purchase_order/modal_print';
		$data['modal_vendor'] 				= 'modal/modal_vendor';
		$data['page'] 						= 'purchase_order/list';
		$data['modul'] 						= 'purchase';
		$data['dashboard_purchase_order'] 	= 'dashboard/dashboard_purchase_order';
		$data['datacompany']				= $datacompany;
		$data['url_modul'] 					= $url;
		$this->load->view('index',$data);
	}

	public function ajax_list($url_modul="",$modul=""){
		$CompanyID  = $this->session->CompanyID;
		$id_url = $this->main->id_menu($url_modul);
		$edit 	= $this->main->menu_ubah($id_url);
		$delete = $this->main->menu_hapus($id_url);
		$list 	= $this->purchase->get_datatables();
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		$ap 	= $this->main->check_parameter_module("ap", "po");
		foreach ($list as $a) {
			$PurchaseNo = str_replace("/", "-", $a->PurchaseNo);
			$link = "attachment/".$PurchaseNo."?type=purchase";
			$btn_edit 		= '';
			$btn_cancel 	= '';
			$btn_view 		= '';
			$label_paid 	= ' <oren>Open</oren>';
			$btn_attachemnt = $this->main->button_attach_dropdown($PurchaseNo,$link,"purchase");
			$btn_delivery 	= $this->main->button_action_dropdown("delivery",$PurchaseNo,$a->DeliveryStatus);
			$status 		= $this->main->label_active($a->Status,"",$PurchaseNo);
			$btn_print 		= $this->main->button_action_dropdown("print", $PurchaseNo);
			$btn_view 		= $this->main->button_action_dropdown("view", $PurchaseNo);
			$label_product_type = $this->main->label_product($a->ProductType,"",$PurchaseNo);

			if($edit>0 and $ap->add>0):
				$btn_edit 		= $this->main->button_action_dropdown("edit", $PurchaseNo);
			endif;

			if($delete>0 and $ap->add>0):
				if($a->Status == 1):
					$btn_cancel = $this->main->button_action_dropdown("cancel", $PurchaseNo);
				else:
					$btn_cancel = '';
				endif;
			endif;

			$grd 		= $a->ck_grd;
			$return 	= $a->ck_return;

			if($grd>0 || $return>0):
				$btn_edit = '';
				$btn_cancel = '';
			endif;

			if($a->Status != 1):
				$btn_cancel  	= '';
				$btn_edit 	 	= '';
				$btn_delivery 	= '';
			endif;

			$button  = '<div class="btn-group pointer">';
			$button .= '<div data-toggle="dropdown" aria-expanded="true">';
			$button .= '<i class="fal fa-cog"></i> <span class="caret"></span> </div>';
			$button .= '<ul class="dropdown-menu animate">';
			$button .= $btn_view;
			$button .= $btn_print;
			$button .= $btn_edit;
			$button .= $btn_cancel;
			$button .= $btn_attachemnt;
	        $button .= ' </ul>';
	        $button .= '</div>';

	        $btn_action 	= $this->main->button_action("code", $PurchaseNo,$a->PurchaseNo);
	        $vendor 		= $this->main->button_action("general_onclick","redirect_post('partner','".$a->VendorID."')",$a->vendorName);
	        if($a->BranchID):
	        	$branch 		= $this->main->button_action("general_onclick","redirect_post('store-device-management','".$a->BranchID."')",$a->branchName);
	        else:
	        	$branch 		= "";
	        endif;

	        $qty = $a->Qty;
	        if($a->ProductType == 1):
	        	$qty = 0;
	        endif;

			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $btn_action;
			$row[] 	= $a->Date; 
			$row[] 	= $vendor;
			$row[] 	= $branch;
			$row[] 	= $status."<br>".$label_product_type; 
			$row[] 	= $this->main->qty($qty); 
			$row[] 	= $this->main->currency($a->Payment);
			// $row[] 	= $button; 
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->purchase->count_all(),
			"recordsFiltered" => $this->purchase->count_filtered(),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function save(){
		$this->_validate();

		$CompanyID 		= $this->session->CompanyID;
		$crud  			= $this->input->post('crud');
		$PurchaseNo 	= $this->input->post('PurchaseNo');
		$ckPPN 			= $this->input->post('ckPPN');
		$VendorID 		= $this->input->post('VendorID');
		$SalesID 		= $this->input->post('SalesID');
		$Date 			= $this->input->post('Date');
		$Term 			= $this->input->post('Term');
		$purchase_remark= $this->input->post('purchase_remark');
		$SubTotal 		= $this->input->post('SubTotal');
		$DiscountRp 	= $this->input->post('DiscountRp');
		$Discount 		= $this->input->post('Discount');
		$PPN 			= $this->input->post('PPN');
		$TotalPPN 		= $this->input->post('TotalPPN');
		$Total 			= $this->input->post('Total');
		$DeliveryCost 	= $this->input->post('Ongkir');
		$product_status = $this->input->post('product_status');
		$BranchID 		= $this->input->post('BranchID');
		$BranchID 		= explode("-", $BranchID)[0];

		// delivery
		$delAddress 	= $this->input->post('delAddress');
		$delCity 		= $this->input->post('delCity');
		$delProvince 	= $this->input->post('delProvince');

		// invoice
		$invAddress 	= $this->input->post('invAddress');
		$invCity 		= $this->input->post('invCity');
		$invProvince 	= $this->input->post('invProvince');
		$BillingTo 		= $this->input->post('BillingTo');

		$productid       	= $this->input->post('productid');
		// $product_code       = $this->input->post('product_code');
		$product_unitid    	= $this->input->post('product_unitid');
		$product_unit      	= $this->input->post('product_unit');
		$product_konv      	= $this->input->post('product_konv');
		$product_qty       	= $this->input->post('product_qty');
		$product_price 		= $this->input->post('product_price');
		$product_total 		= $this->input->post('product_total');
		$product_remark 	= $this->input->post('product_remark');
		$product_discount 	= $this->input->post('product_discount');
		$product_type 		= $this->input->post('product_type');
		$delivery_date 		= $this->input->post('product_delivery_date');
		$purchasedet 		= $this->input->post('purchasedet');

		$ap 	= $this->main->check_parameter_module("ap", "receipt");
		$data_purchase = array(
			"CompanyID"			=> $CompanyID,
			"Payment"			=> $this->main->checkDuitInput($Total),
			"Total"				=> $this->main->checkDuitInput($SubTotal),
			"Remark" 			=> $purchase_remark,
			'PPN' 				=> $PPN,
			'TotalPPN' 			=> $this->main->checkDuitInput($TotalPPN),
			'Discount' 			=> $this->main->checkDuitInput($DiscountRp),
			'DiscountPersent' 	=> $Discount,
			'PaymentTerm'		=> $this->main->checkDuitInput($Term),
			'DeliveryAddress'	=> $delAddress,
			'DeliveryCity'		=> $delCity,
			'DeliveryProvince'	=> $delProvince,
			'DeliveryCost'		=> $this->main->checkDuitInput($DeliveryCost),
			'PaymentTo'			=> $BillingTo,
			'PaymentAddress'	=> $invAddress,
			'PaymentCity'		=> $invCity,
			'PaymentProvince'	=> $invProvince,
			"DeliveryParameter"	=> $ap->add,
			"Date"				=> $Date,
			"ProductType"		=> $product_status,
		);
		if($ckPPN):
			$data_purchase['Tax'] = 1;
		else:
			$data_purchase['Tax'] = 0;
		endif;
		if($VendorID != ''):
			$VendorID = explode('-', $VendorID);
			$data_purchase['VendorID'] = $VendorID[0];
		endif;
		if($SalesID != ''):
			$SalesID = explode('-', $SalesID);
			$data_purchase['SalesID'] = $SalesID[0];
		endif;
		if($BranchID):
			$data_purchase['BranchID'] = $BranchID;
		endif;

		if($crud == "insert"):
			$purchaseno 		= $this->main->purchase_generate();
			$data_purchase['PurchaseNo'] = $purchaseno;
			$data_purchase['Status']	= 1;
			$this->purchase->save($data_purchase);
		else:
			$purchaseno = $PurchaseNo;
			$this->purchase->update(array("PurchaseNo"=>$purchaseno,"CompanyID" => $CompanyID), $data_purchase);
		endif;

		// purchase der
		$purchasedetid  	= array();
		foreach($productid as $key => $v):
			if($productid[$key]):
				if($product_status == 1):
					$xqty  			= 1;
					$xunitid 		= null;
					$xconversion 	= 1;
				else:
					$xqty 			= $this->main->checkDuitInput($product_qty[$key]);
					$xunitid 		= $product_unitid[$key];
					$xconversion 	= $product_konv[$key];
				endif;

				$xprice 		= $this->main->checkDuitInput($product_price[$key]);
				$xtotal_product = $xprice * $xqty;
				$xdiscount 		= $this->main->checkDuitInput($product_discount[$key]);
				$DiscountValue 	= $this->main->PersenttoRp($xtotal_product,$xdiscount);
				// sell det
				$data_detail = array(
					"CompanyID" 	=> $CompanyID,
					"PurchaseNo" 	=> $purchaseno,
					"ProductID" 	=> $productid[$key],
					// "UnitID" 		=> $xunitid,
					"Uom"			=> $xunitid,
					"Qty" 			=> $xqty,
					"Type" 			=> $product_type[$key],
					"Conversion" 	=> $xconversion,
					"Price" 		=> $this->main->checkDuitInput($product_price[$key]),
					"TotalPrice" 	=> $this->main->checkDuitInput($product_total[$key]),
					"Discount" 		=> $product_discount[$key],
					"DiscountValue"	=> $DiscountValue,
					"Remark" 		=> $product_remark[$key],
				);

				if($delivery_date[$key] != ""):
					$data_detail['DeliveryDate'] = $delivery_date[$key];
				endif;

				if($purchasedet[$key] == ''):
					$purchase_det = $this->main->purchase_det_generate();
					$data_detail['PurchaseDet'] = $purchase_det;
					$data_detail['Status']  = 1;
					$this->purchase->save_det($data_detail);
					array_push($purchasedetid, $purchase_det);
				else:
					$purchase_det = $purchasedet[$key];
					$where = array('PurchaseDet' => $purchase_det,'CompanyID' => $CompanyID);
					$this->purchase->update_det($where,$data_detail);
					array_push($purchasedetid, $purchase_det);
				endif;

				// PurchasePrice
				if($BranchID):
					$this->db->where("ProductID", $productid[$key]);
					$this->db->where("BranchID", $BranchID);
					$this->db->where("CompanyID", $CompanyID);
					$this->db->update("PS_Product_Branch", array("PurchasePrice" => $this->main->checkDuitInput($product_price[$key]),));
				else:
					$this->db->where("ProductID", $productid[$key]);
					$this->db->where("CompanyID", $CompanyID);
					$this->db->update("ps_product", array("PurchasePrice" => $this->main->checkDuitInput($product_price[$key]),));
				endif;
			endif;
		endforeach;

		$this->check_purchase_det($purchasedetid, $purchaseno);

		$res = array(
			"status" 	=> true,
			"message"	=> $this->lang->line('lb_success'),
			"ID"		=> $purchaseno,
		);

		$this->main->echoJson($res);

	}

	private function _validate(){
		$this->main->validate_update();
		$this->main->validate_modlue_add("ap","ap");
		$data = array();
		$data['status'] = TRUE;
		$CompanyID  	= $this->session->CompanyID;

		$productid 	 = $this->input->post('productid');
		$product_qty = $this->input->post('product_qty');
		$rowid 		 = $this->input->post('rowid');
		$product_status = $this->input->post('product_status');
		$no = 1;

		// $data['inputerror'][] 	= "";
		// $data['error_string'][] = '';
		// $data['list'][] 		= '';
		// $data['tab'][] 			= '';
		// $data['ara'] 			= $this->input->post();
		// $data['status'] 		= FALSE;

		// $this->main->echoJson($data);
		// exit();

		if(count($productid)>0){
			$status_select_product = false;

			foreach($productid as $key => $v):
				$rowID 		= $rowid[$key];
				if($productid[$key] == ''):
					$cek = 0;
				else:
					$cek = $this->db->count_all("ps_product where CompanyID = '$CompanyID' and ProductID = '$productid[$key]'");
				endif;
				if($cek>0):
					$status_select_product 	= true;
				endif;
			endforeach;
			if(!$status_select_product):
				$data['inputerror'][] 	= "";
				$data['error_string'][] = '';
				$data['list'][] 		= '';
				$data['tab'][] 			= '';
				$data['message'] 		= $this->lang->line('lb_select_product_empty');
				$data['status'] 		= FALSE;
				echo json_encode($data);
				exit();
			endif;
		}
		if(count($productid) > 0 && $product_status == 0){
			$status_qty = true;
			foreach($productid as $key => $v):
				$rowID 		= $rowid[$key];
				if($productid[$key]):
					$xqty = $this->main->checkDuitInput($product_qty[$key]);
					if($xqty <= 0):
						$data['inputerror'][] 	= ".".$rowID;
						$data['error_string'][] = $this->lang->line('lb_product_qty_empty');
						$data['list'][] 		= 'list';
						$data['tab'][] 			= '';
						$data['message'] 		= $this->lang->line('lb_product_qty_empty');
						$data['status'] 		= FALSE;
						$status_qty 			= FALSE;	
					endif;
				endif;
			endforeach;
			if(!$status_qty):
				echo json_encode($data);
				exit();
			endif;
		}

		$BranchID 		= $this->input->post('BranchID');
		$BranchID 		= explode("-", $BranchID)[0];
		$ck_branch 		= $this->db->count_all("Branch where CompanyID = '$CompanyID' and BranchID = '$BranchID' and Active = '1'");
		if($ck_branch<=0):
			$data['inputerror'][] 	= 'BranchID';
			$data['error_string'][] = '';
			$data['list'][] 		= '';
			$data['tab'][] 			= 'sell';
			$data['status'] 		= FALSE;
			$data['message'] 		= $this->lang->line('lb_data_not_found');
			echo json_encode($data);
			exit();
		endif;

		if($this->input->post('SalesID') == ''):
			$data['inputerror'][] 	= 'SalesID';
			$data['error_string'][] = '';//$this->lang->line('lb_sales_empty');
			$data['list'][] 		= '';
			$data['tab'][] 			= 'sell';
			$data['status'] 		= FALSE;
		endif;

		if($this->input->post('VendorID') == ''):
			$data['inputerror'][] 	= 'VendorID';
			$data['error_string'][] = '';
			$data['list'][] 		= '';
			$data['tab'][] 			= 'sell';
			$data['status'] 		= FALSE;
		endif;

		// delivery
		$delAddress 	= $this->input->post('delAddress');
		$delCity 		= $this->input->post('delCity');
		$delProvince 	= $this->input->post('delProvince');

		if($delAddress == ""):
			$data['inputerror'][] 	= 'delAddress';
			$data['error_string'][] = $this->lang->line('lb_address_empty');
			$data['list'][] 		= '';
			$data['tab'][] 			= 'delivery';
			$data['status'] 		= FALSE;
		endif;
		if($delCity == ""):
			$data['inputerror'][] 	= 'delCity';
			$data['error_string'][] = $this->lang->line('lb_city_empty');
			$data['list'][] 		= '';
			$data['tab'][] 			= 'delivery';
			$data['status'] 		= FALSE;
		endif;
		if($delProvince == ""):
			$data['inputerror'][] 	= 'delProvince';
			$data['error_string'][] = $this->lang->line('lb_province_empty');
			$data['list'][] 		= '';
			$data['tab'][] 			= 'delivery';
			$data['status'] 		= FALSE;
		endif;

		// invoice
		$BillingTo 		= $this->input->post('BillingTo');
		$invAddress 	= $this->input->post('invAddress');
		$invCity 		= $this->input->post('invCity');
		$invProvince 	= $this->input->post('invProvince');
		if($BillingTo == ""):
			$data['inputerror'][] 	= 'BillingTo';
			$data['error_string'][] = $this->lang->line('lb_billing_empty');
			$data['list'][] 		= '';
			$data['tab'][] 			= 'invoice';
			$data['status'] 		= FALSE;
		endif;
		if($invAddress == ""):
			$data['inputerror'][] 	= 'invAddress';
			$data['error_string'][] = $this->lang->line('lb_address_empty');
			$data['list'][] 		= '';
			$data['tab'][] 			= 'invoice';
			$data['status'] 		= FALSE;
		endif;
		if($invCity == ""):
			$data['inputerror'][] 	= 'invCity';
			$data['error_string'][] = $this->lang->line('lb_city_empty');
			$data['list'][] 		= '';
			$data['tab'][] 			= 'invoice';
			$data['status'] 		= FALSE;
		endif;
		if($invProvince == ""):
			$data['inputerror'][] 	= 'invProvince';
			$data['error_string'][] = $this->lang->line('lb_province_empty');
			$data['list'][] 		= '';
			$data['tab'][] 			= 'invoice';
			$data['status'] 		= FALSE;
		endif;

		if($data['status'] === FALSE)
        {
            header('Content-Type: application/json');
            echo json_encode($data,JSON_PRETTY_PRINT);  
            exit();
        }
	}

	private function check_purchase_det($array,$PurchaseNo){
		$CompanyID = $this->session->CompanyID;
		$this->db->where("PurchaseNo", $PurchaseNo);
		$this->db->where("CompanyID", $CompanyID);
		$this->db->where_not_in("PurchaseDet", $array);
		$this->db->delete("PS_Purchase_Detail");
	}

	public function cetak($id){
		$id 		= str_replace("-", "/", $id);

		$this->main->default_template("purchase");

		$cetak 		= $this->input->get("cetak");
		$position 	= $this->input->get("position");
		$page 		= $this->input->get("page");
		$code 		= "Purchase".$id;

		$nama_laporan   = "";
        $datacompany    = $this->main->company("api");
        $company_name   = $datacompany->nama;
        $company_imgbin = $datacompany->img_bin;
        $company_imgurl = $datacompany->img_url;
        $logo           = base_url($company_imgurl);
		
		$list  		= $this->purchase->get_by_id($id);
		$detail 	= $this->purchase->get_by_detail($id);

		$data['page'] 			= $page;
		$data["cetak"]			= $cetak;
		$data['list']			= $list;
		$data["detail"]			= $detail;
		$data['title']  		= 'print purchase order';
		$data['title2'] 		= $this->title;
		$data["company_name"]   = $company_name;
		$data["nama_laporan"]   = $nama_laporan;
    	$data["logo"]           = $logo;
    	$data["company"]		= $datacompany;
    	if($page == "print"):
    		$data['template'] 	= $this->main->get_default_template("purchase");
    		$this->load->view('purchase_order/template',$data);
    	else:
    		$this->load->view('purchase_order/view',$data);
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

	public function ajax_edit($id){
		$idnya 		= $id;
		$id 		= str_replace("-", "/", $id);
		$header 	= $this->purchase->get_by_id($id,"edit"); 	
	 	$detail 	= $this->purchase->get_by_detail($id);
	 	$ap 		= $this->main->check_parameter_module("ap","ap");
	 	$cancel 	= '';
	 	$btn_edit 	= '';

	 	$modul  	= $this->input->post("modul");
		$url_modul 	= $this->input->post("url_modul");
		$id_url 	= $this->main->id_menu($url_modul);
		$edit 		= $this->main->menu_ubah($id_url);
		$delete 	= $this->main->menu_hapus($id_url);
				
		if(count(array($header))>0):
			if($delete>0 && $header->Status == 1 && $header->CountReceipt<=0):
				$cancel = $this->main->button_action("cancel",$idnya);
			endif;
		endif;
		if($ap->add>0 && $edit>0):
			$btn_edit = $this->main->button_action("edit_attach",$idnya);
		endif;

		foreach ($detail as $k => $v) {
			$v->ReceiveQty_txt 		= $this->main->qty($v->ReceiveQty);
			$v->product_qty_txt 	= $this->main->qty($v->product_qty);
			$v->product_qty2_txt 	= $this->main->qty($v->product_qty2);
			$v->product_price_txt 	= $this->main->currency($v->product_price,TRUE);
			$v->product_total_txt 	= $this->main->currency($v->product_total,TRUE);
			$v->discount_value_txt 	= $this->main->currency($v->discount_value,TRUE);
			$v->discount_txt 		= $this->main->currency($v->discount,TRUE);
		}

		$header->purchaseno = str_replace("/", "-", $header->PurchaseNo);
		$output = array(
			"list" 			=> $header,
			"list_detail" 	=> $detail,
			// "sn_status"		=> $sn,
			// "payment" 		=> $cek_payment,
			// "delivery"		=> $cek_delivery,
			// "attachment" 	=> site_url('attachment/'.$idnya.'?type=purchase'),
			"hakakses"		=> $this->session->hak_akses,
			"cancel" 		=> $cancel,
			"edit"			=> $btn_edit,
			"attach"		=> $this->main->attachment_show($modul,$id),
		);
		if($ap->add>0 && $header->Status == 1):
			$output['next'] = $this->main->button_action("receipt",$idnya, $header->DeliveryStatus."-purchase");
		endif;

		$this->main->echoJson($output);
	}

	public function ajax_edit_serial($id){
		$a =  $this->purchase->get_list_detail($id,"add_serial");
		$sn = json_decode($a->serialnumber);
		if(empty($sn)):
			$serial_number = array();
		else:
			$serial_number = array();
			foreach($sn as $sn):
				$item = array(
					"PurchaseDet" 			=> $sn->PurchaseDet,
					"serialnumber" 		=> $sn->SerialNumber,
					"hakakses"			=> $this->session->hakakses
				);	
				array_push($serial_number, $item);
			endforeach;
		endif;
		$data = array(
			"product_code" 		=> $a->product_code,
			"product_name" 		=> $a->product_name,
			"product_type" 		=> $a->product_type,
			"productid" 		=> $a->productid,
			"code"				=> $a->PurchaseNo,
			"detail_code" 		=> $a->PurchaseDet,
			"purchase_det" 		=> $a->PurchaseDet,
			"purchase_konv" 	=> $a->purchase_conv,
			"purchase_no" 		=> $a->PurchaseNo,
			"purchase_price" 	=> $a->purchase_price,
			"serial_qty" 		=> $a->purchase_qty,
			"unit_name" 		=> $a->unit_name,
			"unitid" 			=> $a->unitid,
			"page"				=> "add_serial_purchase",
			"serialno"			=> $a->serialno,
			"list_serial" 		=> $serial_number,
			"serial_number"		=> $this->purchase->get_serial_by_id($a->PurchaseDet,$a->PurchaseNo),
		);
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);
	}

	public function cancel($id){
		$this->main->validate_modlue_add("ap","ap");
		$id 		= str_replace("-", "/", $id);
		$CompanyID 	= $this->session->CompanyID;
		$cek = $this->db->count_all("PS_Purchase where PurchaseNo = '$id' and CompanyID = '$CompanyID'");
		if($cek>0):
			$detail = $this->purchase->get_by_detail($id);
			foreach ($detail as $key => $v) {
				$data_detail = array("Status" => 0);
				$this->purchase->update_det(array("PurchaseDet"=>$v->PurchaseDet, "CompanyID" => $CompanyID),$data_detail);
			}
			$data = array("Status" => 0);
			$this->purchase->update(array("PurchaseNo"=>$id, "CompanyID" => $CompanyID),$data);

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

	public function save_serial($page = "")
	{
		// $this->_validate_serial();
		$CompanyID 			= $this->session->CompanyID;
		$page 				= $this->input->post('page');
		$serial_id 			= $this->input->post("serial_id");
		$header_code 		= $this->input->post('header_code');
		$detail_code 		= $this->input->post("detail_code");
		$productid 			= $this->input->post("productid");
		$product_type 		= $this->input->post("product_type");
		$serial_qty 		= $this->input->post('serial_qty');
		$serial_number 		= $this->input->post("serial_number");
		$arr_serialid 		= array();
		foreach ($serial_number as $key => $v) {
			$data = array(
				"CompanyID"		=> $CompanyID,
				"PurchaseNo"	=> $header_code,
				"PurchaseDet"	=> $detail_code,
				"ProductID"		=> $productid,
				"SN"			=> $serial_number[$key],
				"Qty"			=> $serial_qty,
			);
			if($serial_id[$key]):
				$detid = $serial_id[$key];
				$where = array(
					"PurchaseDetSN" 	=> $serial_id[$key],
					"CompanyID"			=> $CompanyID,
				);
				$this->purchase->update_serial($where,$data);
			else:
				$detid = $this->purchase->save_serial($data);
			endif;
			array_push($arr_serialid, $detid);
		}

		$this->delete_serial($arr_serialid, $header_code,$detail_code);
		$this->insert_serial_product($header_code,$detail_code,$product_type);

		$output = array(
			"status" 	=> true,
			"message"	=> $this->lang->line('lb_success'),
			"page"		=> $page,
		);

		$this->main->echoJson($output);
	}

	private function delete_serial($array,$header,$detail){
		if(count($array)>0):
			$this->db->where("CompanyID", $this->session->CompanyID);
			$this->db->where("PurchaseNo", $header);
			$this->db->where("PurchaseDet", $detail);
			$this->db->where_not_in("PurchaseDetSN", $array);
			$this->db->delete("PS_Purchase_Detail_SN");
		endif;
	}

	private function insert_serial_product($header,$detail,$product_type){
		$a = $this->purchase->get_by_id($header);
		if($a->DeliveryParameter == 0):
			$list 	= $this->purchase->get_serial_by_id($detail,$header);
			if($product_type != "general"):
				$this->db->where("CompanyID", $a->CompanyID);
				$this->db->where("ReceiveDet", $detail);
				$this->db->delete("PS_Product_Serial");
			endif;
			foreach ($list as $k => $v) {
				$data = array(
					"ReceiveDet"	=> $v->PurchaseDet,
					"ProductID"		=> $v->ProductID,
					"CompanyID"		=> $v->CompanyID,
					"SerialNo"		=> $v->SN,
					"Date"			=> $a->Date,
					"Qty"			=> $v->Qty,
					"User_Add"		=> $this->session->nama,
					"Date_Add"		=> date("Y-m-d H:i:s"),
				);
				if($product_type != "general"):
					$this->db->insert("PS_Product_Serial", $data);
				else:
					$cek = $this->db->count_all("PS_Product_Serial where ProductID = '$v->ProductID'");
					if($cek>0):
						$this->db->where("ProductID", $v->ProductID);
						$this->db->update("PS_Product_Serial",$data);
					else:
						$this->db->insert("PS_Product_Serial", $data);
					endif;
				endif;
			}
		endif;
	}

	public function save_remark(){
		$CompanyID 	= $this->session->CompanyID;
		$ID 		= $this->input->post("ID");
		$Remark 	= $this->input->post("Remark");

		$status 	= false;
		$message 	= $this->lang->line('lb_data_not_found');

		if($CompanyID && $ID):
			$cek = $this->db->count_all("PS_Purchase where CompanyID = '$CompanyID' and PurchaseNo = '$ID'");
			if($cek>0):
				$data = array(
					"Remark"	=> $Remark,
				);
				$this->db->where("CompanyID", $CompanyID);
				$this->db->where("PurchaseNo", $ID);
				$this->db->update("PS_Purchase", $data);

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