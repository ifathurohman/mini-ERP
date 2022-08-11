<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_voucher extends CI_Model {

	var $table = 'Voucher';
	var $table_detail = "VoucherDetail";

	var $column = array(
		'Voucher.VoucherID',
		'Voucher.Date',
		'Voucher.Code',
		'Voucher.App',
		'Voucher.Type',
		'Voucher.Qty',
		'Voucher.Qty',
		'Voucher.TotalPrice',
		'Voucher.Bank',
		'Voucher.Status',
		'user.nama'
		); //set column field database for order and search

	var $column_detail = array(
		'VoucherDetail.VoucherDetailID',
		'VoucherDetail.Code',
		"user.nama",
		"(case
			when user.hak_akses = 'company' or user.hak_akses = 'super_admin' then user.nama
			else company.nama
		end)",
		"Voucher.Type",
		"VoucherDetail.Module",
		"VoucherDetail.UseDate",
		"VoucherDetail.ExpireDate",

	);

	var $order = array('VoucherID' => 'desc'); // default order 
	var $order_detail = array('VoucherDetail.UseDate' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($modul = "")
	{
		$url = $this->uri->segment(1);
		$this->db->select("
			Voucher.VoucherID 	as VoucherID,
			Voucher.Code 		as Code,
			Voucher.Date 		as Date,
			Voucher.ExpireDate 	as ExpireDate,
			Voucher.Status 		as Status,
			Voucher.UseDate 	as UseDate,
			Voucher.Active 		as Active,
			Voucher.Qty 		as Qty,
			Voucher.Bank 		as Bank,
			(Voucher.TotalPrice + ifnull(parent.TotalPrice,0))	as Price,
			Voucher.Module,
			Voucher.StatusTransfer 		as StatusTransfer,
			Voucher.Remark 				as Remark,
			user.nama,
			(case 
			when Voucher.App ='pipesys' then 'Pipesys'
			when Voucher.App ='salespro' then 'People Shape Sales'
			else 'Pipesys & People Shape Sales' end) as App,
			(case 
			when Voucher.Type = 24 THEN '2 Year'
			when Voucher.Type = 12 THEN '1 Year'
			when Voucher.Type = 6 THEN '6 Month'
			when Voucher.Type = 3 THEN '3 Month'
			when Voucher.Type = 1 THEN '1 Month' else 'none' end) 	as Type,

			ifnull(parent.Qty,0) as parentQty,
		");
		$this->db->join("user", "Voucher.CompanyID = user.id_user", "left");
		$this->db->join("Voucher as parent", "parent.ParentID = Voucher.VoucherID","left");
		$this->db->where("Voucher.ParentID is null");
		if($modul == "voucher"):
			$this->db->where("Voucher.CompanyID",$this->session->CompanyID);
		endif;

		#ini hanya untuk filter dari url
		if($this->input->post("Filter") == "VoucherID"):
			$this->db->like("Voucher.Code",$this->input->post("Search"));
		#ini untuk filter dari search
		elseif($this->input->post("Filter") == "Filter"):

			if($this->input->post("StartDate") && $this->input->post("EndDate")):
				$this->db->where("$this->table.Date >=",$this->input->post("StartDate"));
				$this->db->where("$this->table.Date <=",$this->input->post("EndDate"));
			endif;
			if($this->input->post("App") != "none"):
				$this->db->where("$this->table.App",$this->input->post("App"));
			endif;
			if($this->input->post("Package") != "none"):
				$this->db->where("$this->table.Type",$this->input->post("Package"));
			endif;
			if($this->input->post("Status") != "none"):
				$this->db->where("$this->table.Status",$this->input->post("Status"));
			endif;
			if($this->input->post("Search")):
				$Search = $this->input->post("Search");
				$this->db->group_start();
				$this->db->like("$this->table.Code",$Search);
				$this->db->or_like("$this->table.TotalPrice",$Search);
				$this->db->or_like("$this->table.Bank",$Search);
				$this->db->group_end();
			endif;
		endif;

		if($modul == "transaction"):
			$this->db->order_by("StatusTransfer","DESC");
			$this->db->order_by("Status","ASC");
		endif;
		$this->db->from($this->table);
		$i = 0;
		foreach ($this->column as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND. 
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$column[$i] = $item; // set column array variable to order processing
			$i++;
		}
		
		if($this->input->post("order")) // here order processing
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables($modul = "")
	{
		$this->_get_datatables_query($modul);
		if($this->input->post("length") != -1)
		$this->db->limit($this->input->post("length"), $this->input->post("start"));
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($modul = "")
	{
		$this->_get_datatables_query($modul);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($modul = "")
	{
		if($modul == "voucher"):
		$this->db->where("CompanyID",$this->session->CompanyID);
		endif;
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->select("
			$this->table.*, 
			user.nama as CustomerName,
			user.email as CustomerEmail,
			user.phone as CustomerPhone,
			ifnull(parent.Qty,0) 		as parentQty,
			ifnull(parent.VoucherID,0) 	as parentVoucherID,
			ifnull(parent.Module,0)		as parentModule,
		");
		$this->db->join("user","$this->table.CompanyID = user.id_user","left");
		$this->db->join("Voucher as parent", "parent.ParentID = Voucher.VoucherID","left");

		$this->db->from($this->table);
		$this->db->where('Voucher.VoucherID',$id);
		$query = $this->db->get();

		return $query->row();
	}
	public function get_list_voucher($id,$page="")
	{
		$this->db->select("
			VoucherDetail.VoucherDetailID,
			VoucherDetail.App,
			VoucherDetail.Code,
			VoucherDetail.Status,
			VoucherDetail.UseDate,
			VoucherDetail.ExpireDate,
			ifnull(VoucherDetail.Module,'') as Module,
			ifnull(user.nama,'') as usedName,
			(case
				when user.hak_akses = 'company' or user.hak_akses = 'super_admin' then ifnull(user.nama,'')
				else ifnull(company.nama,'')
			end) as usedCompany,

			Voucher.Type as voucherType,
		");
		$this->db->join("Voucher","VoucherDetail.VoucherID = Voucher.VoucherID","left");
		$this->db->join("user", "user.id_user = VoucherDetail.UsedID", "left");
		$this->db->join("user as company", "company.id_user = user.CompanyID", "left");
		$this->db->order_by("VoucherDetail.Status","ASC");
		if($page == "detail"):
			$this->db->where("VoucherDetail.Code", $id);
			$query = $this->db->get("VoucherDetail");
			return $query->row();
		else:
			$this->db->where("VoucherDetail.VoucherID",$id);
			$query = $this->db->get("VoucherDetail");
			return $query->result();
		endif;
	}

	public function save($data)
	{
		$this->db->set("UserAdd",$this->session->NAMA);
		$this->db->set("DateAdd",date("Y-m-d H:i:s"));
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
	public function update($where, $data)
	{
		$this->db->set("UserCh",$this->session->NAMA);
		$this->db->set("DateCh",date("Y-m-d H:i:s"));
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function update_detail($where, $data)
	{
		$this->db->set("UserCh",$this->session->NAMA);
		$this->db->set("DateCh",date("Y-m-d H:i:s"));
		$this->db->update("VoucherDetail", $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('VoucherID', $id);
		$this->db->delete($this->table);
	}
	public function generate_voucher($id)
	{

		$this->db->select("
			Voucher.App,
			Voucher.Qty,
			Voucher.CompanyID,
			ifnull(parent.Qty,0) 		as parentQty,
			ifnull(parent.VoucherID,0) 	as parentVoucherID, 
		");
		$this->db->where("Voucher.VoucherID",$id);
		$this->db->join("Voucher as parent", "parent.ParentID = Voucher.VoucherID","left");
		$a 			= $this->db->get("Voucher")->row();
		$CompanyID 	= $a->CompanyID;
		$App 		= $a->App;
		$Qty 		= $a->Qty;
		
		if($App == "all"):
			foreach(range(1,$Qty) as $b):
				$this->save_generate_voucher($id,$CompanyID,"pipesys");
			endforeach;
			foreach(range(1,$Qty) as $b):
				$this->save_generate_voucher($id,$CompanyID,"salespro");
			endforeach;		
		else:
			foreach(range(1,$Qty) as $b):
				$this->save_generate_voucher($id,$CompanyID,$App);
			endforeach;

			if($a->parentVoucherID):
				foreach(range(1,$a->parentQty) as $b):
					$this->save_generate_voucher($a->parentVoucherID,$CompanyID,$App);
				endforeach;
			endif;
		endif;

	}
	public function save_generate_voucher($id,$CompanyID,$App)
	{
		$data = array(
			"Code"		=> $this->generate_token_(),
			"App"		=> $App,
			"VoucherID"	=> $id,
			"CompanyID" => $CompanyID,
			"UserAdd" 	=> $this->session->NAMA,
			"DateAdd"	=> date("Y-m-d H:i:s")
		);
		$this->db->insert("VoucherDetail",$data);
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
	    $voucher = strtoupper(substr($this->session->CompanyID.$b, 0,6));
	    $cek = $this->db->count_all("VoucherDetail where Code = '$voucher'");
	    if($cek>0):
	    	$this->generate_token_();
	    else:
	    	return $voucher;
	    endif;
	}


	#used voucher
	private function used_get_datatables_query($modul = "")
	{
		$url = $this->uri->segment(1);
		$this->db->select("
			VoucherDetail.VoucherDetailID,
			VoucherDetail.Code,
			VoucherDetail.Status,
			VoucherDetail.UseDate,
			VoucherDetail.ExpireDate,
			VoucherDetail.Module,
			ifnull(user.nama,'') as usedName,
			(case
				when user.hak_akses = 'company' or user.hak_akses = 'super_admin' then ifnull(user.nama,'')
				else ifnull(company.nama,'')
			end) as usedCompany,
			(case 
			when Voucher.Type = 24 THEN '2 Year'
			when Voucher.Type = 12 THEN '1 Year'
			when Voucher.Type = 6 THEN '6 Month'
			when Voucher.Type = 3 THEN '3 Month'
			when Voucher.Type = 1 THEN '1 Month' else 'none' end) 	as Type,
			Voucher.Module as module_type,
		");
		$this->db->join("Voucher", "VoucherDetail.VoucherID = Voucher.VoucherID");
		$this->db->join("user", "user.id_user = VoucherDetail.UsedID", "left");
		$this->db->join("user as company", "company.id_user = user.CompanyID", "left");
		$this->db->where("VoucherDetail.Status", "used");
		$this->db->where("VoucherDetail.UsedCompanyID", $this->session->CompanyID);
		$this->db->from($this->table_detail);
		$i = 0;
		$Search = $this->input->post('Search');
		$column = $this->column_detail;
		foreach ($this->column_detail as $item) // loop column 
		{
			if($Search) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND. 
					$this->db->like($item, $Search);
				}
				else
				{
					$this->db->or_like($item, $Search);
				}

				if(count($this->column_detail) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$column[$i] = $item; // set column array variable to order processing
			$i++;
		}
		if($this->input->post("StartDate")):
            $StartDate = $this->input->post("StartDate");
            $this->db->where("Date(VoucherDetail.UseDate) >=", $StartDate);
        endif;
        if($this->input->post("EndDate")):
            $EndDate = $this->input->post("EndDate");
            $this->db->where("Date(VoucherDetail.UseDate) <=", $EndDate);
        endif;
		
		if($this->input->post("order")) // here order processing
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order_detail))
		{
			$order = $this->order_detail;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function used_get_datatables($modul = "")
	{
		$this->used_get_datatables_query($modul);
		if($this->input->post("length") != -1)
		$this->db->limit($this->input->post("length"), $this->input->post("start"));
		$query = $this->db->get();
		return $query->result();
	}

	function used_count_filtered($modul = "")
	{
		$this->used_get_datatables_query($modul);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function used_count_all($modul = "")
	{
		if($modul == "voucher"):
			
		endif;
		$this->db->from($this->table_detail);
		return $this->db->count_all_results();
	}
}
