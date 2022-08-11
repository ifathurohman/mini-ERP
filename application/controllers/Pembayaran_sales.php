<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembayaran_sales extends CI_Controller {
	var $title = 'Sales Payment';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_pembayaran_sales",'pembayaran');
		$this->main->cek_session();
		$this->title = $this->lang->line('lb_paymentstore');
	}
	public function index()
	{
		$url 						= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url);
		$read 						= $this->main->read($id_url);
		$ar 	= $this->main->check_parameter_module("ar","payment_ar");
		if($read == 0 or $ar->view == 0){ redirect(); }
		$pembayaran_tambah 				= $this->main->menu_tambah($id_url);
		if($pembayaran_tambah > 0 and $ar->add>0):
            $tambah = $this->main->general_button('add','Add New '.$this->title);
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['url'] 			= 'list-pembayaran';
		$data['title']  		= $this->title;
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'pembayaran_sales/modal';
		$data['page'] 			= 'pembayaran_sales/list';
		$this->load->view('index',$data);
	}
	//---------------------------------------------------------------------------------------
	public function ajax_list()
	{
		$page 	= "pembayaran";
		$url 	= $this->uri->segment(1); 
		$id_url = $this->main->id_menu($url);
		$list 	= $this->pembayaran->get_datatables($page);
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		$ar 	= $this->main->check_parameter_module("ar","payment_ar");
		foreach ($list as $a) {
			$ubab 	= "";
			$hapus 	= "";
			$pembayaran_ubah 	= $this->main->menu_ubah($id_url);
			$pembayaran_hapus 	= $this->main->menu_hapus($id_url);
			if($pembayaran_ubah > 0):
           	$ubah = '<a href="javascript:void(0)" type="button" class="btn btn-success" title="Edit" onclick="view('."'".$a->paymentno."'".')"><i class="icon fa-search" aria-hidden="true"></i></a>';
			endif;
			if($pembayaran_hapus > 0):
           	$hapus = '<a href="javascript:void(0)" type="button" class="btn btn-danger" title="Hapus" onclick="hapus('."'".$a->paymentno."'".')"><i class="icon fa-remove" aria-hidden="true"></i></a>';
			endif;
			$print 	= '<a href="javascript:void(0)" type="button" class="btn btn-info" title="Hapus" onclick="print('."'".$a->paymentno."'".')"><i class="icon fa-print" aria-hidden="true"></i></a>'; 


            if($a->status == 1):
            	// $ubah 	= "";
            	$status = "<hijau>hijau</hijau>";
            else:
            	$status = "<merah>merah</merah>";
            endif;

   			$button  = '<div class="btn-group btn-group-xs" aria-label="Basic example" role="group">';
            // $button .= $print;
            $button .= $ubah;
            // $button .= $hapus;
            $button .= '</div>';

            $code = '<a href="javascript:void(0)" type="button" title="View" onclick="view('."'".$a->paymentno."'".')">'.$a->paymentno.'</a>';

			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $code;
			$row[] 	= date("Y-m-d",strtotime($a->date));
			$row[] 	= $a->storename;
			$row[] 	= $this->main->currency($a->total);
			$row[] 	= $this->main->currency($a->grandtotal);
			// $row[] 	= number_format($a->total-$a->grandtotal,0,".","");
			// $row[] 	= $button;		
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->pembayaran->count_all($page),
			"recordsFiltered" => $this->pembayaran->count_filtered($page),
			"data"			  => $data,
		);
		echo json_encode($output);
	}
	public function sell($page = "",$search = "")
	{
		$branchid 	= "";
		$date 		= "";
		if($page == "add"):
			$branchid 	= $this->input->post("branchid"); 
			$date 		= $this->input->post("date"); #payment date
		elseif($page == "edit"):
			$a 			= $this->pembayaran->get_by_id($search,"pembayaran");
			$branchid 	= $a->branchid;
		endif;
		$list_data  = array();
		if($page == "add"):
			#data penjualan
			$data 		= $this->main->sell("branch",$branchid,$date);
			foreach($data as $b):
	            $item = array(
	                "paid"      	=> $b->paid,
	                "id" 			=> $b->sellno,
	                "sellno"    	=> $b->sellno,
	                "balanceid" 	=> "",
	                "status"    	=> $b->status,
	                "date"     		=> date("Y-m-d",strtotime($b->date)),
	                "total"     	=> $b->total,
	                "totaltxt" 		=> $this->main->currency($b->total),
	                "payment"   	=> $this->main->currency($b->payment),
	                "sisa"   		=> $this->main->currency($b->total),
	                "vendorid"  	=> $b->vendorid,
	                "vendorname"  	=> $b->vendorname,
	                "type" 			=> 1,
	                "jenis"			=> "sell"
	            );
	            array_push($list_data,$item);
	        endforeach;
	        #data ar correction
			$data 	= $this->main->ar_correction_detail2("branch",$branchid);
	        foreach($data as $b):
	        	$sisa = $this->main->currency($b->total);
	        	if($b->type != 1):
	        		$sisa = $this->main->currency('-'.$b->total);
	        	endif;
	            $item = array(
	                "paid"      	=> "",
	                "id" 			=> $b->balancedet,
	                "sellno"    	=> $b->balanceno,
	                "balanceid" 	=> $b->balanceid,
	                "status"    	=> "",
	                "date"     		=> date("Y-m-d",strtotime($b->date)),
	                "total"     	=> $b->total,
	                "totaltxt" 		=> $this->main->currency($b->total),
	                "payment"   	=> "",
	                "sisa"   		=> $sisa,
	                "vendorid"  	=> $b->vendorid,
	                "vendorname"  	=> $b->vendorname,
	                "type" 			=> $b->type,
	                "jenis"			=> "ar"
	            );
	            array_push($list_data,$item);
	        endforeach;
	    else:
	    	$data 	= $this->pembayaran->pembayaran_detail($a->branchid,$a->paymentno);
	        foreach($data as $b):
	        	$code = $b->sellno;
	        	$sisa = $this->main->currency($b->total);
	        	if($b->jenis == "ar"):
	        		$code = $b->balanceCode;
	        		if($b->type == 2):
	        			$sisa = $this->main->currency("-".$b->total);
	        		endif;
	        	endif;
	            $item = array(
	                "paid"      	=> "",
	                "sellno"    	=> $code,
	                "status"    	=> "",
	                "date"     		=> date("Y-m-d",strtotime($b->date)),
	                "total"     	=> $b->total,
	                "payment"   	=> "",
	                "sisa"   		=> $sisa,
	                "vendorid"  	=> $b->vendorid,
	                "vendorname"  	=> $b->vendorname,
	                "jenis"			=> $b->jenis
	            );
	            array_push($list_data,$item);
	        endforeach;
	    endif;
		$output 	= array(
			"branchid"  => $branchid,
			"date"  	=> $date,
			"list_data" => $list_data,
			"status" 	=> TRUE,
			"message" 	=> "",
			"hakakses" 	=> $this->session->hak_akses
		);

		if($page == "edit"):
			$output["paymentno" ] = $a->paymentno;
			$output["branchid" 	] = $a->branchid;
			$output["name" 		] = $a->branchname;
			$output["pay_cash" 	] = $this->main->currency($a->pay_cash,TRUE);
			$output["pay_credit"] = $this->main->currency($a->pay_credit,TRUE);
			$output["pay_giro" 	] = $this->main->currency($a->pay_giro,TRUE);
			$output["add_cost" 	] = $this->main->currency($a->add_cost,TRUE);
			$output["total" 	] = $this->main->currency($a->total,TRUE);
			$output["grandtotal"] = $this->main->currency($a->grandtotal,TRUE);
			$output["date" 		] = date("Y-m-d",strtotime($a->date));
		else:
			$output["total"]	  = "";
		endif;

		header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
	}
	public function simpan($page = "")
	{
		if($page != "tes"):
		$this->_validate("simpan");
		endif;
		$paymentno  = $this->main->paymentno_generate();
		$branchid 	= $this->input->post("branchid");
		$date 		= $this->input->post("date");
		$pay_cash 	= $this->input->post("pay_cash");
		$pay_credit = $this->input->post("pay_credit");
		$pay_giro 	= $this->input->post("pay_giro");
		$add_cost 	= $this->input->post("add_cost");
		$grandtotal = $this->input->post("grandtotal");
		$total_ar 	= $this->input->post("total_ar");



		#data payment detail atau data penjualan
		$cekbox 	= $this->input->post('cekbox');
		$sellno		= $this->input->post('sellno');
		$vendorid	= $this->input->post('vendorid');
		$payment	= $this->input->post('payment');
		$sell_date	= $this->input->post('sell_date');
		$jenis		= $this->input->post('jenis');
		$balanceid 	= $this->input->post('balanceid');
		$id 		= $this->input->post('id');


		if($page == "tes"){
			$sellno = array(1,2,3);
			$cekbox = array(1,1,0);
		}
		$list_data 	= array();
		foreach ($id as $key => $v):
			if(in_array($id[$key], $cekbox)):
				if($jenis[$key] == "ar"):
					$type = 2;
				else:
					$type = 1;
				endif;
				$paymentdetail = array(
					"PaymentNo" => $paymentno,
					"CompanyID" => $this->session->CompanyID,
					"BranchID" 	=> $branchid,
					"VendorID" 	=> $vendorid[$key],
					"Total" 	=> $payment[$key],
					"Date" 		=> date("Y-m-d",strtotime($sell_date[$key])),
					"Type"		=> $type,
					'User_Add' 	=> $jenis[$key],//$this->session->nama,
					'Date_Add' 	=> date("Y-m-d H:i:s")
				);
				if($jenis[$key] == "sell"):
					$paymentdetail["SellNo"] 	= $sellno[$key];
					$this->update_sell($branchid,$sellno[$key],$payment[$key]);
				else:
					$paymentdetail['BalanceDetID'] 	= $id[$key];
					$paymentdetail['BalanceID']		= $balanceid[$key];
					$this->update_ar($branchid,$id[$key],$payment[$key]);
				endif;
				$this->save_payment_detail($paymentdetail);
			endif;
		endforeach;

		$data = array(
			"PaymentNo" 	=> $paymentno,
			"CompanyID" 	=> $this->session->CompanyID,
			"BranchID"		=> $branchid,
			"Date"			=> date("Y-m-d",strtotime($date)),
			"Cash"			=> str_replace(".", "", $pay_cash),
			"Credit"		=> str_replace(".", "", $pay_credit),
			"Giro"			=> str_replace(".", "", $pay_giro),
			"AdditionalCost"=> str_replace(".", "", $add_cost),
			"Total"			=> str_replace(".", "", $total_ar),
			"GrandTotal" 	=> str_replace(".", "", $grandtotal),
			"Status" 		=> 1,
		);
		$this->pembayaran->save($data);
		// $this->update_payment_status();
		// $this->update_sell_status();
		// $this->update_ar_status();

		$output = array(
			"status" 		=> TRUE,
			"message" 		=> $data,
			"cekbox"		=> $cekbox,
			"sellno"		=> $sellno,
			"hakakses" 		=> $this->session->hak_akses
		);
		header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
	}
	public function update_sell($branchid,$sellno,$payment)
	{
		$CompanyID = $this->session->CompanyID;
		$data = array(
			"Payment" 	=> $payment,
			"Paid"		=> 1,
			"User_Ch" 	=> $this->session->nama,
			"Date_Ch" 	=> date("Y-m-d")
		);
		$this->db->where("CompanyID",$CompanyID);
		$this->db->where("BranchID",$branchid);
		$this->db->where("SellNo",$sellno);
		$this->db->update("PS_Sell",$data);
		// $this->db->query("UPDATE PS_Sell SET Payment = Payment+$payment WHERE CompanyID = '$CompanyID' AND SellNo='$sellno' ");
	}
	public function update_ar($branchid,$sellno,$payment){
		$CompanyID = $this->session->CompanyID;
		$data = array(
			"Payment" 		=> $payment,
			"PaymentStatus"	=> 1,
			"User_Ch" 		=> $this->session->nama,
			"Date_Ch" 		=> date("Y-m-d")
		);
		$this->db->where("CompanyID",$CompanyID);
		$this->db->where("BranchID",$branchid);
		$this->db->where("BalanceDetID",$sellno);
		$this->db->update("AC_BalancePayable_Det",$data);
		// $this->db->query("UPDATE AC_CorrectionPR_Det SET Payment = Payment+$payment WHERE CompanyID = '$CompanyID' AND BalanceNo='$sellno' AND VendorID='$vendorid' ");
	}
	public function update_payment($paymentno,$total,$grandtotal){
		$CompanyID = $this->session->CompanyID;
		$this->db->query("UPDATE PS_Payment SET GrandTotal = GrandTotal + $grandtotal WHERE CompanyID = '$CompanyID' AND PaymentNo='$paymentno' ");
	}
	public function save_payment_detail($data){
		$this->db->insert("PS_Payment_Detail",$data);
	}
	public function update_payment_status(){
		$CompanyID 	= $this->session->CompanyID;
		$query 		= $this->db->query("SELECT PaymentNo FROM PS_Payment WHERE CompanyID = '$CompanyID' AND GrandTotal >= Total ");
		$result 	= $query->result();
		foreach($result as $a):
			$data = array(
				"Status" 	=> 1,
				"User_Ch" 	=> $this->session->nama,
				"Date_Ch" 	=> date("Y-m-d H:i:s")
			);
			$this->db->where("CompanyID",$CompanyID);
			$this->db->where("PaymentNo",$a->PaymentNo);
			$this->db->update("PS_Payment",$data);
		endforeach;
	}
	public function update_sell_status(){
		$CompanyID 	= $this->session->CompanyID;
		$query 		= $this->db->query("SELECT SellNo FROM PS_Sell WHERE CompanyID = '$CompanyID' AND Payment >= Total ");
		$result 	= $query->result();
		foreach($result as $a):
			$data = array(
				"Paid" 		=> 1,
				"User_Ch" 	=> $this->session->nama,
				"Date_Ch" 	=> date("Y-m-d H:i:s")
			);
			$this->db->where("CompanyID",$CompanyID);
			$this->db->where("SellNo",$a->SellNo);
			$this->db->update("PS_Sell",$data);
		endforeach;
	}
	public function update_ar_status(){
		$CompanyID 	= $this->session->CompanyID;
		$query 		= $this->db->query("SELECT BalanceDet FROM AC_CorrectionPR_Det WHERE CompanyID = '$CompanyID' AND Payment >= TotalCorrection ");
		$result 	= $query->result();
		foreach($result as $a):
			$data = array(
				"Status" 	=> 1,
				"User_Ch" 	=> $this->session->nama,
				"Date_Ch" 	=> date("Y-m-d H:i:s")
			);
			$this->db->where("CompanyID",$CompanyID);
			$this->db->where("BalanceDet",$a->BalanceDet);
			$this->db->update("AC_CorrectionPR_Det",$data);
		endforeach;
	}
	private function _validate($page = "")
	{
		$data 					= array();
		$data['error_string'] 	= array();
		$data['inputerror'] 	= array();
		$data['status'] 		= TRUE;
		$data["hakakses"]		= $this->session->hak_akses;
		// if($this->input->post('total') > number_format($this->input->post('grand_total'),0,".","")){
		// 	$data['total']		= $this->input->post('total');
		// 	$data['grand_total']= number_format($this->input->post('grand_total'),0,".","");
		// 	$data['message']	= "Grand Total and Total must be same ";
		// 	$data['status'] 	= FALSE;
		// }

		if($page == "simpan"):
			if($this->input->post('name') == '' || $this->input->post("branchid") == ''):
	            $data['inputerror'][]   = 'name';
	            $data['error_string'][] = 'Please select store';
	            $data['status']         = FALSE;
	        endif;
	        if($this->input->post('date') == ''):
	            $data['inputerror'][]   = 'date';
	            $data['error_string'][] = $this->lang->line('lb_date_empty');
	            $data['status']         = FALSE;
	        endif;
	        if($this->input->post('total_ar') == ''):
	            $data['inputerror'][]   = 'total_ar';
	            $data['error_string'][] = $this->lang->line('lb_total_ar_empty');
	            $data['status']         = FALSE;
	        endif;
	        if($this->input->post('grandtotal') == ''):
	            $data['inputerror'][]   = 'grandtotal';
	            $data['error_string'][] = $this->lang->line('lb_grand_total_empty');
	            $data['status']         = FALSE;
	        endif;
	        if($this->input->post('total_ar') > $this->input->post("grandtotal") || $this->input->post("grandtotal") > $this->input->post('total_ar')  ):
				// $data['message']		= "Grand Total and Total AR Invoice must be same ";
	            $data['inputerror'][]   = 'grandtotal';
	            $data['error_string'][] = $this->lang->line('lb_total_not_same');
	            $data['status']         = FALSE;
	        endif;
		endif;
		if(empty($this->input->post('cekbox')))
		{
			$data['message']	= $this->lang->line('lb_sales_no_select');
			$data['status'] 	= FALSE;
		}
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
}
