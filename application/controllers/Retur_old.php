<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retur extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_retur",'retur');
		$this->main->cek_session();
	}
	public function index()
	{
		$url 						= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url);
		$read 						= $this->main->read($id_url);
		if($read == 0){ redirect(); }
		$retur_tambah 				= $this->main->menu_tambah($id_url);
		if($retur_tambah > 0):
            $tambah = '<div class="btn-group">';
            // $tambah .= '<button type="button" class="btn btn-primary btn-outline" onclick="tambah('."'sales'".')" >Add New Return Sales</button>';
            $tambah .= '<button type="button" class="btn btn-primary btn-outline" onclick="tambah('."'purchase'".')" >Add New Return</button>';
            $tambah .= "</div>";
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['url'] 			= 'list-retur';
		$data['title']  		= 'Return';
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'retur/modal';
		$data['page'] 			= 'retur/list';
		$this->load->view('index',$data);
	}
	//---------------------------------------------------------------------------------------
	public function ajax_list()
	{
		$page 	= "retur";
		$url 	= $this->uri->segment(1); 
		$id_url = $this->main->id_menu($url);
		$list 	= $this->retur->get_datatables($page);
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		foreach ($list as $a) {
			$ubab 	= "";
			$hapus 	= "";
			$retur_ubah 	= $this->main->menu_ubah($id_url);
			$retur_hapus 	= $this->main->menu_hapus($id_url);

			$type   = "purchase";
			if($a->type == 1):
				$type = "purchase";
				$lbl_type = "<hijau>purchase</hijau>";
				$receivesellno = $a->receiveno;
			elseif($a->type == 2):
				$type = "sell";
				$lbl_type = "<biru>sales</biru>";
				$receivesellno = $a->sellno;
			endif;

			if($retur_ubah > 0):
           	$ubah = '<a href="javascript:void(0)" type="button" class="btn btn-success" title="Edit" onclick="view('."'".$a->returno."'".','."'".$type."'".')"><i class="icon fa-search" aria-hidden="true"></i></a>';
			endif;
			if($retur_hapus > 0):
           	$hapus = '<a href="javascript:void(0)" type="button" class="btn btn-danger" title="Hapus" onclick="hapus('."'".$a->returno."'".','."'".$type."'".')"><i class="icon fa-remove" aria-hidden="true"></i></a>';
			endif;
			$print 	= '<a href="javascript:void(0)" type="button" class="btn btn-info" title="Hapus" onclick="print('."'".$a->returno."'".','."'".$type."'".')"><i class="icon fa-print" aria-hidden="true"></i></a>'; 

			$button  = '<div class="btn-group btn-group-xs" aria-label="Basic example" role="group">';
            // $button .= $print;
            $button .= $ubah;
            // $button .= $hapus;
            $button .= '</div>';
			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $a->returno;
			$row[] 	= $a->returdate;
			$row[] 	= $receivesellno;
			$row[] 	= $a->vendorname;
			$row[] 	= $lbl_type;
			$row[] 	= $button;		
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $_POST['draw'],
			"recordsTotal" 	  => $this->retur->count_all($page),
			"recordsFiltered" => $this->retur->count_filtered($page),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$a = $this->retur->get_by_id($id,"retur");
		$detail = $this->retur->get_list_detail($id);
		$list_data = array();
		foreach($detail as $b):
			$item = array(
				"returdet" 	=> $b->returdet,
				"returno" 	=> $b->returno,
				"productid" => $b->productid,
				"product_qty" 		=> $this->main->qty($b->product_qty),
				"product_konv" 		=> $b->product_konv,
				"product_price" 	=> $this->main->currency($b->product_sellprice),
				"remark" 			=> $b->remark,
				"unitid" 			=> $b->unitid,
				"serialnumber" 		=> $b->serialnumber,
				"product_code" 		=> $b->product_code,
				"product_name"		=> $b->product_name,
				"product_type"		=> $b->product_type,
				"product_konv" 		=> $b->product_konv,
				"unit_name" 		=> $b->unit_name,
			);
			array_push($list_data,$item);
		endforeach;
		$output = array(
			"returno" 		=> $a->returno,
			"returdate" 	=> $a->returdate,
			"sellno" 		=> $a->sellno,
			"vendorname" 	=> $a->vendorname,
			"list_data" 	=> $list_data,
			"hakakses" 		=> $this->session->hak_akses,
			"status" 		=> TRUE,
		);
		header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
	}
	public function ajax_edit_serial($id)
	{
		$a 	=  $this->retur->get_list_detail($id,"add_serial");
		$sn = json_decode($a->serialnumber);
		if(empty($sn)):
			$serial_number = array();
		else:
			$serial_number = array();
			foreach($sn as $sn):
				$item = array(
					"productserialid" 	=> $sn->ProductSerialID,//$sn["ProductSerialID"],
					"productid" 		=> $sn->ProductID,
					"ReturDet" 			=> $sn->ReturDet,
					"serialnumber" 		=> $sn->SerialNumber,
					"hakakses"			=> $this->session->hak_akses
				);	
				array_push($serial_number, $item);
			endforeach;

		endif;
		$data = array(
			"branchid" 			=> "",
			"product_code" 		=> $a->product_code,
			"product_name" 		=> $a->product_name,
			"product_type" 		=> $a->product_type,
			"productid" 		=> $a->productid,
			"detail_code" 		=> $a->returdet,
			"serial_qty" 		=> $this->main->qty($a->product_qty),
			"page"				=> "add_serial_retur",
			"list_serial" 		=> $serial_number,
		);
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
	}
	public function simpan_serial($page = "")
	{
		$this->_validate_serial();
		$productserialid 	= $this->input->post("productserialid");
		$detail_code 		= $this->input->post("detail_code");
		$productid 			= $this->input->post("productid");
		$product_type 		= $this->input->post("product_type");
		$serial_number 		= $this->input->post("serial_number");
		if($page == "tes"):
			$serial_number 	= array("111111111111","222222222222222");
			$detail_code 	= "010004";
			$productid 		= 3;
		endif;
		$list_data_serial 	= array();
		$data 				= array();
		foreach ($serial_number as $key => $v) {
			if($v):
				$item = array(
					"Page" 				=> "retur",
					'ReturDet'			=> $detail_code,
					'CompanyID' 		=> $this->session->CompanyID,
					'ProductID' 		=> $productid,
					'ProductSerialID' 	=> $productserialid[$key],
					'SerialNumber' 		=> $serial_number[$key],
					
				);
				array_push($list_data_serial, $item);
			endif;
		}
		$list_data_serial = json_encode($list_data_serial);
		$data = array(
			"SerialNumber" 	=> $list_data_serial,
			"User_Ch" 		=> $this->session->nama,
			"Date_Ch" 		=> date("Y-m-d H:i:s"),
		);
		$this->db->where("CompanyID",$this->session->CompanyID);
		$this->db->where("ReturDet",$detail_code);
		$this->db->update("AP_Retur_Det",$data);

		header('Content-Type: application/json');
        echo json_encode(array("page"=>"add_serial","status" => "add_serial","pesan" => $data),JSON_PRETTY_PRINT);  
	}
	public function simpan($page = "")
	{
		if($page != "tes"):
		$this->_validate();
		endif;
		$returno 		= $this->main->returno_generate();
		$type 			= $this->input->post("type");
		$receiveno 		= $this->input->post("receiveno");
		$vendorid 		= $this->input->post("vendorid");

		$cekbox 		= $this->input->post("cekbox");
		$productid 		= $this->input->post("productid");
		$product_qty 	= $this->input->post("product_qty");
		$product_price 	= $this->input->post("product_price");
		$product_konv 	= $this->input->post("product_konv");

		$unitid 		= $this->input->post("unitid");
		$receivedet 	= $this->input->post("receivedet");
		$remark 		= $this->input->post("remark");

		if($page == "tes"){
			$productid  = array(1,2,3);
			$unitid  	= array(1,2,3);
			$product_qty  = array(5,5,5);
			$product_price  = array(1000,2000,3000);
			$productid  = array(1,2,3);
			$cekbox 	= array(3);
			$receivedet = array(3,2,3);
			$remark 	= array("anjir","capcay","");
		}
		// if($type == "purchase"):
		// 	$type = 1;
		// else:
		// 	$type = 2;
		// endif;
		#input retur
		$data = array(
			"ReturNo" 	=> $returno,
			"ReceiveNo"	=> $receiveno,
			"CompanyID" => $this->session->CompanyID,
			"VendorID"	=> $vendorid,
			"Date" 		=> date("Y-m-d"),
			"Type" 		=> 1, #return purchase
		);
		#inpur detail retur
		$list_data 	= array();
		foreach ($productid as $key => $v):
			if(in_array($productid[$key], $cekbox)):
				$datadetail = array(
					"CompanyID" => $this->session->CompanyID,
					"ReturNo" 	=> $returno,
					"ReceiveNo" => $receiveno,
					"ReceiveDet"=> $receivedet[$key],
					"ProductID" => $productid[$key],
					"UnitID" 	=> $unitid[$key],
					"Qty" 		=> $product_qty[$key],
					"Price" 	=> $product_price[$key],
					"Conversion"=> $product_konv[$key],
					"Total" 	=> $product_qty[$key] * $product_price[$key],
					"Remark" 	=> $remark[$key],
					"User_Add"  => $this->session->nama,
					"Date_Add" 	=> date("Y-m-d H:i:s")
				);
				$this->db->insert("AP_Retur_Det",$datadetail);
				if($product_qty[$key] > 0):
					$this->main->retur_qty("done",$productid[$key],$product_qty[$key]);
				endif;
				array_push($list_data, $datadetail);
			endif;
		endforeach;
		$this->retur->save($data);


		$output = array(
			"hakakses" 	=> $this->session->hak_akses,
			"status" 	=> TRUE,
			"pesan" 	=> "",
			"cekbox"	=> $cekbox,
			"cproductid"=> $productid,
			"data" 		=> $data,
			"list_data" => $list_data,
		);
		header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
	}
	public function update_qty_penerimaan($productid,$qty)
	{
		$CompanyID = $this->session->CompanyID;
		$this->db->query("UPDATE ps_product SET Qty = Qty - $qty WHERE CompanyID='$CompanyID' AND ProductID='$productid'");
	}
	private function _validate($page = "")
	{
		$data 					= array();
		$data['error_string'] 	= array();
		$data['inputerror'] 	= array();
		$data['status'] 		= TRUE;
		if(count($this->input->post('cekbox')) < 1)
		{
			$data["message"] 		= "Please select product";
			$data['inputerror'][] 	= '';
			$data['error_string'][] = 'Please select product';
			$data['status'] 		= FALSE;
		}
		if(count($this->input->post('product_qty')) < 1)
		{
			$data["message"] 		= "Please fill out qty product";
			$data['inputerror'][] 	= '';
			$data['error_string'][] = 'Please select product';
			$data['status'] 		= FALSE;
		}
		if($this->input->post('receiveno') == '')
		{
			$data['inputerror'][] 	= 'receiveno';
			$data['error_string'][] = 'Good Receipt Code cannot be null';
			$data['status'] 		= FALSE;
		}
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	private function _validate_serial()
	{
		$productid 			= $this->input->post("productid");
		$serial_number 		= $this->input->post("serial_number");
		foreach ($serial_number as $key => $v) {
			$serialnumber = $serial_number[$key];
			$total = $this->main->cek_serialnumber($productid,$serialnumber);
			if($total == 0):
				$data['inputerror'][] 	= 'productid';
				$data['error_string'][] = 'productid';
				$data['message'] 		= "Serial number ".$serialnumber." not found";
				$data['status'] 		= FALSE;	
				echo json_encode($data);
				exit();
			endif;
		}
	}
}
