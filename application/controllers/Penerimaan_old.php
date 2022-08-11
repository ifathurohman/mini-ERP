<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penerimaan extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_penerimaan",'penerimaan');
		$this->main->cek_session();
	}
	public function index()
	{
		$url 						= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url);
		$read 						= $this->main->read($id_url);
		if($read == 0){ redirect(); }
		$penerimaan_tambah 				= $this->main->menu_tambah($id_url);
		if($penerimaan_tambah > 0):
            $tambah = '<button type="button" class="btn btn-primary btn-outline" onclick="tambah()" ><i class="fa fa-plus"></i> Add New Good Receipt</button>';
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['url'] 			= 'list-penerimaan';
		$data['title']  		= 'Good Receipt';
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'penerimaan/modal';
		$data['page'] 			= 'penerimaan/list';
		$data['modul'] 			= 'penerimaan';
		$data['url_modul'] 		= $url;
		$this->load->view('index',$data);
	}
	//---------------------------------------------------------------------------------------
	public function ajax_list()
	{
		$page 	= "penerimaan";
		$url 	= $this->uri->segment(1); 
		$id_url = $this->main->id_menu($url);
		$list 	= $this->penerimaan->get_datatables($page);
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		foreach ($list as $a) {
			$ubab 	= "";
			$hapus 	= "";
			$penerimaan_ubah 	= $this->main->menu_ubah($id_url);
			$penerimaan_hapus 	= $this->main->menu_hapus($id_url);
			if($penerimaan_ubah > 0):
           	$ubah = '<a href="javascript:void(0)" type="button" class="btn btn-success" title="View" onclick="view('."'".$a->receipt_no."'".')"><i class="icon fa-search" aria-hidden="true"></i></a>';
			endif;
			if($penerimaan_hapus > 0):
           	$hapus = '<a href="javascript:void(0)" type="button" class="btn btn-danger" title="Cancel" onclick="hapus('."'".$a->receipt_no."'".')"><i class="icon fa-remove" aria-hidden="true"></i></a>';
			endif;
			$print 	= '<a href="javascript:void(0)" type="button" class="btn btn-info" title="Hapus" onclick="print('."'".""."'".')"><i class="icon fa-print" aria-hidden="true"></i></a>'; 


            $status = "";
            if($a->status == 1): $status = "<hijau>done</hijau>"; else: $status= "<merah>cancel receipt</merah>"; $hapus=""; endif;


			$button  = '<div class="btn-group btn-group-xs" aria-label="Basic example" role="group">';
            // $button .= $print;
            $button .= $ubah;
            // $button .= $hapus;
            $button .= '</div>';



			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $a->receipt_no;
			$row[] 	= $a->receipt_date;
			$row[] 	= $a->receipt_name;
			// $row[] 	= $status;

			$row[] 	= $button;		
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->penerimaan->count_all($page),
			"recordsFiltered" => $this->penerimaan->count_filtered($page),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$a = $this->penerimaan->get_by_id($id,"penerimaan");
		$data = array(
			"receipt_no" 		=> $a->receipt_no,
			"receipt_name" 		=> $a->receipt_name,
			"sj_no" 			=> $a->sj_no,
			"po_no" 			=> $a->po_no,
			"receipt_date" 		=> $a->receipt_date,
			"receipt_remark" 	=> $a->receipt_remark,
			"list_detail" 		=> $this->penerimaan->get_list_detail($id)
		);
		
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
	}
	public function ajax_edit_serial($id)
	{
		$a =  $this->penerimaan->get_list_detail($id,"add_serial");
		$data = array(
			"product_code" 	=> $a->product_code,
			"product_name" 	=> $a->product_name,
			"product_type" 	=> $a->product_type,
			"productid" 	=> $a->productid,
			"receipt_det" 	=> $a->receipt_det,
			"receipt_konv" 	=> $a->receipt_konv,
			"receipt_no" 	=> $a->receipt_no,
			"receipt_price" => $a->receipt_price,
			"serial_qty" 	=> $a->receipt_qty,
			"receipt_subtotal" 	=> $a->receipt_subtotal,
			"unit_name" 		=> $a->unit_name,
			"unitid" 			=> $a->unitid,
			"list_serial" 		=> $this->main->product_serial("add_serial",$a->receipt_det),
			"hakakses"			=> $this->session->hak_akses
		);
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
	}
	public function simpan($page = "")
	{
		if($page == "tes"):
		else:
		$this->_validate_product();
		$this->_validate("save");
		endif;
		$penerimaan_code 	= $this->main->penerimaan_code_generate();
		$name 				= $this->input->post("receipt_name");
		$po_no 				= $this->input->post("po_no");
		$sj_no 				= $this->input->post("sj_no");
		$VendorID  			= "";
		if(strpos($name, "-") != false):
			$namex 	= explode("-", $name);
			$VendorID 	= $namex[0]; 
			$name 		= $namex[1]; 
		endif;
		$remark 			= $this->input->post("receipt_remark");
		$productid       	= $this->input->post('productid');
		$product_code       = $this->input->post('product_code');
		$product_type       = $this->input->post('product_type');
		$product_unitid    	= $this->input->post('product_unitid');
		$product_unit      	= $this->input->post('product_unit');
		$product_konv      	= $this->input->post('product_konv');
		$product_qty       	= $this->input->post('product_qty');
		$product_price 		= $this->input->post('product_price');
		$product_subtotal 	= $this->input->post('product_subtotal');
		if($page == "tes"):
			$penerimaan_code = $penerimaan_code;
			$name 			= $this->session->nama;
			$productid 		= array(18);
			$product_qty 	= array(22);
		endif;
		$data = array(
			'ReceiveNo' 	=> $penerimaan_code,
			'CompanyID'		=> $this->session->CompanyID,
			'VendorID'		=> $VendorID,
			'ReceiveName'	=> $name,
			'PurchaseNo' 	=> $po_no,
			'SJNo'			=> $sj_no,
			'Date' 			=> date("Y-m-d"),
			'Remark' 		=> $remark,
			'Status' 		=> 1,
		);
		$insert 			= $this->penerimaan->save($data);
		#ini insert detail
		$data 				= array();
		foreach ($productid as $key => $v) {
			if($v):
			$receipt_det = $this->main->penerimaandetail_code_generate();
			$data = array(
				'ReceiveDet'=> $receipt_det,
				'ReceiveNo' => $penerimaan_code,
				'CompanyID'	=> $this->session->CompanyID,
				'ProductID' => $productid[$key],
				'Conversion'=> $product_konv[$key],
				'Qty' 		=> $product_qty[$key],
				'UnitID' 	=> $product_unitid[$key],
				'Price'		=> $product_price[$key],
				'SubTotal'	=> $product_subtotal[$key],
				'User_Add' 	=> $this->session->nama,
				'Date_Add' 	=> date("Y-m-d H:i:s")
			);
			$this->db->insert("AP_GoodReceipt_Det",$data);
			if($product_qty[$key] > 0):
				$this->main->penerimaan_qty("done",$productid[$key],$product_qty[$key]);
					if($product_type[$key] == 1){
						$this->simpan_serial_unique($receipt_det,$productid[$key],$product_qty[$key]);
					}
				endif;
			endif;
		}
		echo json_encode(array("status" => TRUE,"pesan" => $data));
	}
	public function simpan_serial_unique($receipt_det = "",$productid = "",$product_qty ="")
	{
		$a = $this->main->product("select",$productid);
		$serial_format 	= $a->serial_format;
		$explodesn 		= explode("/", $serial_format);
		$countsn 		= count($explodesn);
		$digit 			= strlen($explodesn[$countsn-1]);
		$serial_format 	= str_replace("YEAR",date("y"),$serial_format);
		$serial_format 	= str_replace("MONTH",date("m"),$serial_format);
		$serial_format 	= substr($serial_format, 0,-$digit);
		$serial_format 	= str_replace("/", "",$serial_format);
		$cek 			= $this->db->count_all("PS_Product_Serial WHERE ProductID='$productid'");		
		foreach (range(1, $product_qty) as $a):
			if($serial_format == "auto"):
        		$serial_number	= $this->main->autoNumber("PS_Product_Serial","SerialNo",6,date("ym"));
	    	else:
	    		
    			$serial_number 	= $this->main->autoNumber("PS_Product_Serial","SerialNo",$digit,$serial_format,$productid);
	    	endif;
			$data = array(
				'ReceiveDet'=> $receipt_det,
				'CompanyID' => $this->session->CompanyID,
				'ProductID' => $productid,
				'SerialNo' 	=> $serial_number,
				'Qty'		=> 1,
				'Date'		=> date("Y-m-d")

				
			);
			$data["User_Add"] = $this->session->nama;
			$data["Date_Add"] = date("Y-m-d H:i:s");
			$this->db->insert("PS_Product_Serial",$data);	
		endforeach;
	}
	public function simpan_serial($page = "")
	{
		$productserialid 	= $this->input->post("productserialid");
		$receipt_det 		= $this->input->post("receipt_det");
		$productid 			= $this->input->post("productid");
		$product_type 		= $this->input->post("product_type");
		$serial_qty 		= $this->input->post("serial_qty");
		$serial_number 		= $this->input->post("serial_number");
		if($page == "tes"):
			$serial_number 	= array("111111111111");
		endif;


		$qty 				= 1;
		if($product_type == "general"):
			$qty = $serial_qty;
		endif;
		$data 				= array();
		foreach ($serial_number as $key => $v) {
			$data = array(
				'ReceiveDet'=> $receipt_det,
				'CompanyID' => $this->session->CompanyID,
				'ProductID' => $productid,
				'SerialNo' 	=> $serial_number[$key],
				'Qty'		=> $qty,
				'Date'		=> date("Y-m-d")				
			);
			if($productserialid[$key]):
				$data["User_Ch"] = $this->session->nama;
				$data["Date_Ch"] = date("Y-m-d H:i:s");
				$this->db->where("ProductSerialID",$productserialid[$key]);
				$this->db->update("PS_Product_Serial",$data);
			else:
				$data["User_Add"] = $this->session->nama;
				$data["Date_Add"] = date("Y-m-d H:i:s");
				$this->db->insert("PS_Product_Serial",$data);
			endif;
		}
		echo json_encode(array("page"=>"add_serial","status" => "add_serial","pesan" => $data));
	}
	public function ajax_delete($id)
	{
		$this->penerimaan->update(array("ReceiveNo"=>$id),array("Status"=>0));

		$this->db->select("ProductID as productid, Qty as qty");
		$this->db->where("ReceiveNo",$id);
		$query = $this->db->get("AP_GoodReceipt_Det");
		foreach($query->result() as $a):
			$productid 		= $a->productid;
			$product_qty 	= $a->qty;
			$this->main->penerimaan_qty("cancel",$productid,$product_qty);
		endforeach;
		echo json_encode(array("status" => TRUE));
	}
	private function _validate($page = "")
	{
		$name 				= $this->input->post("receipt_name");
		$VendorID  			= "";
		if(strpos($name, "-") != false):
			$namex 	= explode("-", $name);
			$VendorID 	= $namex[0]; 
			$name 		= $namex[1]; 
		endif;
		$cek_vendor 	= $this->db->count_all("PS_Vendor WHERE VendorID='$VendorID'");

		$data 					= array();
		$data['error_string'] 	= array();
		$data['inputerror'] 	= array();
		$data['status'] 		= TRUE;
		if($this->input->post('receipt_name') == '')
		{
			$data['inputerror'][] 	= 'receipt_name';
			$data['error_string'][] = 'Receipt name cannot be null';
			$data['status'] 		= FALSE;
		}
		if($cek_vendor == 0)
		{
			$data['inputerror'][] 	= 'receipt_name';
			$data['error_string'][] = 'Please Insert Your Vendor Name';
			$data['status'] 		= FALSE;
		}
		if($this->input->post('po_no') == '')
		{
			$data['inputerror'][] 	= 'po_no';
			$data['error_string'][] = ' Purchase Order No cannot be null';
			$data['status'] 		= FALSE;
		}
		if($this->input->post('sj_no') == '')
		{
			$data['inputerror'][] 	= 'sj_no';
			$data['error_string'][] = 'Delivery Order cannot be null';
			$data['status'] 		= FALSE;
		}
		
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	private function _validate_product()
	{
		$productid 	 = $this->input->post('productid');
		$product_qty = $this->input->post('product_qty');
		$no = 1;
		if(count($productid) == 1){
			foreach($productid as $key => $v):
				if(empty($v)):
					$data['inputerror'][] 	= 'productid';
					$data['error_string'][] = 'productid';
					$data['message'] 		= "Please select product".$v;
					$data['status'] 		= FALSE;	
					echo json_encode($data);
					exit();
				endif;
			endforeach;
		}
		if(count($productid) > 0){
			foreach($productid as $key => $v):
				if($product_qty[$key] < 1 || empty($product_qty[$key])):
					$data['inputerror'][] 	= 'productid';
					$data['error_string'][] = 'productid';
					$data['message'] 		= "Qty product cannot be null";
					$data['status'] 		= FALSE;	
					echo json_encode($data);
					exit();
				endif;
			endforeach;
		}
	}
}
