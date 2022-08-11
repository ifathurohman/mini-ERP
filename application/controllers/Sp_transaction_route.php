<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sp_transaction_route extends CI_Controller {
	var $CompanyID;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_ps_transaction_route",'transaction_route');
		$this->main->cek_session();
		$this->CompanyID = $this->session->CompanyID;
	}
	public function index()
	{
		$url 						= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url);
		$read 						= $this->main->read($id_url);
		if($read == 0){ redirect(); }
		$transaction_route_tambah 				= $this->main->menu_tambah($id_url);
		if($transaction_route_tambah > 0):
            $tambah = '<button type="button" class="btn btn-primary btn-outline" onclick="tambah()" ><i class="fa fa-plus"></i> Add New Transaction</button>';
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['title']  	= 'Employee Route Transaction';
		$data['tambah'] 	= $tambah;
		$data['modal'] 		= 'salespro/transaction_route/modal';
		$data['page'] 		= 'salespro/transaction_route/list';
		$data['modul'] 		= 'partner';
		$this->load->view('index',$data);
	}

	public function ajax_list($page ="")
	{
		$url 	= $this->uri->segment(1); 
		$id_url = $this->main->id_menu($url);
		$list 	= $this->transaction_route->get_datatables($page);
		$data 	= array();
		$no 	= $this->input->post("start");
		$i 		= 1;
		foreach ($list as $a) {
			$transaction_route_ubah 	= $this->main->menu_ubah($id_url);
			$transaction_route_hapus 	= $this->main->menu_hapus($id_url);
			if($transaction_route_ubah > 0):
           	$ubah = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="Edit" onclick="edit('."'".$a->TransactionRouteID."'".')">view detail</a>';
			else:
				$ubah = ""; 
			endif;
			if($transaction_route_hapus > 0):
           		if($a->Active == 1):
           			$hapus = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="Delete Data" onclick="hapus('."'".$a->TransactionRouteID."'".')">delete</a>';
           	
           		else:
           			$hapus = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="Undelete Data" onclick="active('."'".$a->TransactionRouteID."'".')">undelete</a>';
           		endif;
			else: 
				$hapus = ""; 
			endif;
			// if($transaction_route->position == 1): $hapus = ""; endif;

			$button  = '<div class="btn-group btn-group-xs" aria-label="Basic example" role="group">';
            $button .= $ubah;
            $button .= $hapus;
            $button .= '</div>';
			
			$active = "";
            if($a->Active == 0):
            	$active = '<i class="icon fa-trash pull-right" aria-hidden="true"></i>';
            endif;
			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $a->Code.$active;
			$row[] 	= $a->Date;
			$row[] 	= $a->Name;
			$row[] 	= $button;		
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $this->input->post("draw"),
			"recordsTotal" 	  => $this->transaction_route->count_all($page),
			"recordsFiltered" => $this->transaction_route->count_filtered($page),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$a 				= $this->transaction_route->get_by_id($id);
		$data = array(
			"TransactionRouteID"		=> $a->TransactionRouteID,
			"Code" 						=> $a->Code,
			"Name" 						=> $a->Name,
			"Date" 						=> $a->Date,
			"hakakses"					=> "super_admin",
			"list_detail"				=> $this->transaction_route->get_detail_by_id($id)
		);
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
	}

	public function simpan($page="")
	{
		$list_address = "";
		$this->_validate($page);
		$Code 		= $this->main->transaction_route_code_generate();
		$BranchID 	= $this->input->post("Name");
		$Date 		= $this->input->post("Date");
		#ini untuk Detail
		$Customer 	= $this->input->post("Customer");
		$Remark 	= $this->input->post("Remark");



		$data 		= array(
			"CompanyID"	=> $this->session->CompanyID,
			'Code'		=> $Code,
			"BranchID"	=> $BranchID,
			"Date"		=> date("Y-m-d",strtotime($Date)),
		);
		$insert = $this->transaction_route->save($data);
		#ini untuk detail
		foreach ($Customer as $key => $v):
			if($v):
				$VendorID = explode("-", $v)[0];
				$data_detail = array(
					"TransactionRouteID" 	=> $insert,
					"CompanyID"				=> $this->CompanyID,
					"VendorID"				=> $VendorID,
					"Remark" 				=> $Remark[$key] 
				);
				$this->transaction_route->save_detail($data_detail);
			endif;
		endforeach;

		if($insert):
			$TransactionRouteID = $insert;
			$UserID				= $BranchID;
			$this->firebase->push_new_route_transaction($TransactionRouteID,$UserID);
		endif;

        header('Content-Type: application/json');
		echo json_encode(array("status" => TRUE,"pesan" => $list_address),JSON_PRETTY_PRINT);
	}
	public function ajax_update($page="")
	{
		$list_address = "";
		$this->_validate($page);
		$Code 		= $this->main->transaction_route_code_generate();
		$BranchID 	= $this->input->post("Name");
		$Date 		= $this->input->post("Date");
		#ini untuk Detail
		$TransactionRouteDetailID = $this->input->post("TransactionRouteDetailID");
		$Customer 	= $this->input->post("Customer");
		$Remark 	= $this->input->post("Remark");
		$data 		= array(
			"BranchID"	=> $BranchID,
			"Date"		=> date("Y-m-d",strtotime($Date)),
		);
		$this->transaction_route->update(array('TransactionRouteID' => $this->input->post('TransactionRouteID')), $data);
		#ini untuk detail
		foreach ($TransactionRouteDetailID as $key => $v):
			if($v):
				$VendorID 		= explode("-", $Customer[$key])[0];
				$data_detail 	= array(
					"CompanyID"				=> $this->CompanyID,
					"VendorID"				=> $VendorID,
					"Remark" 				=> $Remark[$key] 
				);
			$this->transaction_route->update_detail(array('TransactionRouteDetailID' => $TransactionRouteDetailID[$key]), $data_detail);
			endif;
		endforeach;
		echo json_encode(array("status" => TRUE,"pesan" => ""));
	}
	public function ajax_delete($id,$status="")
	{
		$this->transaction_route->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}
	private function _validate($page="")
	{
		$data 					= array();
		$data['error_string'] 	= array();
		$data['inputerror'] 	= array();
		$data['status'] 		= TRUE;
		if($this->input->post('Name') == 0)
		{
			$data['inputerror'][] 	= 'Name';
			$data['error_string'][] = 'Please select sales';
			$data['status'] 		= FALSE;
		}
		if($this->input->post('Date') == '')
		{
			$data['inputerror'][] 	= 'Date';
			$data['error_string'][] = 'Date cannot be null';
			$data['status'] 		= FALSE;
		}
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	//20180426 MW
	//ini fungsi manual untuk mereset Code Transaksi di tabel SP_TransactionRoute
	public function ubahCode(){
		$query = $this->transaction_route->getData();
		if($query->num_rows()>0):
			foreach ($query->result() as $d) {
				$CompanyID 	= $d->CompanyID;
				$ID 		= $d->TransactionRouteID;
				$Code 		= $this->main->autoNumber("SP_TransactionRoute","Code",5,date("ym"),$CompanyID);

				$data = array(
					"Code" => $Code,
					);
				$this->db->where("TransactionRouteID", $ID);
				$this->db->update("SP_TransactionRoute", $data);
			}
		endif;
	}
}
