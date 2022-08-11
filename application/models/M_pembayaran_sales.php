<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pembayaran_sales extends CI_Model {

	var $table 	= 'PS_Payment';
	var $column = array(
	'PaymentNo',
	'PaymentNo',
	'p.Date',
	'b.Name',
	'p.Total',
	'p.Total',

	); //set column field database for order and search
	var $order 	= array('p.Date_Add' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$url = $this->uri->segment(1);
		$this->db->select("
			p.PaymentNo 	as paymentno,
			p.Date 			as date,
			p.Total 		as total,
			p.TotalAwal 	as totalawal,
			p.GrandTotal 	as grandtotal,
			p.Status 		as status,
			v.Name 			as vendorname,
			b.Name 			as storename,
		");
		$this->db->from($this->table." as p");
		$this->db->where("p.Type",0);
		$this->db->join("PS_Vendor as v","p.VendorID = v.VendorID","left");
		$this->db->join("Branch as b","p.BranchID = b.BranchID","left");
		$this->db->where("p.CompanyID",$this->session->CompanyID);
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
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$url = $this->uri->segment(1);
		$this->db->where("Type",0);
		$this->db->where("CompanyID",$this->session->CompanyID);
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->select("
			p.PaymentNo 	as paymentno,
			p.Date 			as date,
			p.Cash 			as pay_cash,
			p.Giro 			as pay_giro,
			p.Credit 		as pay_credit,
			p.AdditionalCost as add_cost,
			p.Total 		as total,
			p.TotalAwal 	as totalawal,
			p.GrandTotal 	as grandtotal,
			p.Status 		as status,
			v.VendorID 		as vendorid,
			v.Name 			as vendorname,
			b.BranchID 		as branchid,
			b.Name 			as branchname,
		");
		$this->db->from($this->table." as p");
		$this->db->join("PS_Vendor as v","p.VendorID = v.VendorID","left");
		$this->db->join("Branch as b","p.BranchID = b.BranchID","left");
		// $this->db->where("p.CompanyID",$this->session->CompanyID);
		$this->db->where('PaymentNo',$id);
		$query = $this->db->get();
		return $query->row();
	}
	public function pembayaran_detail($branchid,$paymentno)
	{
		$CompanyID = $this->session->CompanyID;
		$this->db->select("
			pd.SellNo 	as sellno,
			pd.Total 	as total,
			pd.Date 	as date,
			pd.Vendorid as vendorid,
			v.Name 		as vendorname,
			(CASE WHEN pd.Type = '1' THEN 'sell' ELSE 'ar' END) as jenis,
			CASE
				WHEN pd.Type = '1' then 1
				ELSE ac.BalanceType
			END as type,
			ac.Code as balanceCode,
		");
		$this->db->join("AC_BalancePayable as ac", "pd.BalanceID = ac.BalanceID", "left");
		$this->db->join("PS_Vendor as v","pd.VendorID = v.VendorID","left");
		$this->db->where("pd.CompanyID",$CompanyID);
		$this->db->where("pd.BranchID",$branchid);
		$this->db->where("pd.PaymentNo",$paymentno);
		$query = $this->db->get("PS_Payment_Detail as pd");
		return $query->result();
	}
	public function save($data)
	{
		$this->db->set("user_add",$this->session->userdata("NAMA"));
		$this->db->set("date_add",date("Y-m-d H:i:s"));
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->set("user_ch",$this->session->userdata("NAMA"));
		$this->db->set("date_ch",date("Y-m-d H:i:s"));
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('PaymentNo', $id);
		$this->db->delete($this->table);
	}
}
