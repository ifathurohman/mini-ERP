<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_ps_transaction_route extends CI_Model {

	var $table 	= 'SP_TransactionRoute';
	var $column = array(
		'SP_TransactionRoute.TransactionRouteID',
		'SP_TransactionRoute.Code',
		'SP_TransactionRoute.Date',
		'Branch.Name',
	);
	var $order 	= array('TransactionRouteID' => 'desc'); // default order 
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($page="")
	{
		$url = $this->uri->segment(1);
		$this->db->select("
			SP_TransactionRoute.TransactionRouteID 	as TransactionRouteID,
			SP_TransactionRoute.Code 				as Code,
			SP_TransactionRoute.Date 				as Date,
			SP_TransactionRoute.Active 				as Active,
			Branch.Name 							as Name,
		");
		$this->db->join("Branch","SP_TransactionRoute.BranchID = Branch.BranchID","left");
		$this->db->where("SP_TransactionRoute.CompanyID",$this->session->CompanyID);
		$this->db->from($this->table." as SP_TransactionRoute");
		$i = 0;
		foreach ($this->column as $item) // loop column 
		{
			if($this->input->post("search")) // if datatable send POST for search
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

	function get_datatables($page ="")
	{
		$this->_get_datatables_query($page);
		if($this->input->post("length") != -1)
		$this->db->limit($this->input->post("length"), $this->input->post("start"));
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($page ="")
	{
		$this->_get_datatables_query($page);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($page = "")
	{
		$this->db->where("SP_TransactionRoute.CompanyID",$this->session->CompanyID);
		$this->db->from($this->table." as SP_TransactionRoute");
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->select("
			SP_TransactionRoute.TransactionRouteID 	as TransactionRouteID,
			SP_TransactionRoute.Code 				as Code,
			SP_TransactionRoute.Date 				as Date,
			SP_TransactionRoute.Active 				as Active,
			Branch.BranchID							as Name,
		");
		$this->db->join("Branch","SP_TransactionRoute.BranchID = Branch.BranchID","left");
		$this->db->from($this->table);
		$this->db->where('TransactionRouteID',$id);
		$query = $this->db->get();
		return $query->row();
	}
	public function get_detail_by_id($id)
	{
		$this->db->select("
			PS_Vendor.VendorID,
			PS_Vendor.Name,
			PS_Vendor.Address,
			SP_TransactionRouteDetail.TransactionRouteDetailID,
			SP_TransactionRouteDetail.Remark
		");
		$this->db->join("PS_Vendor","SP_TransactionRouteDetail.VendorID = PS_Vendor.VendorID","left");
		$this->db->from("SP_TransactionRouteDetail");
		$this->db->where('TransactionRouteID',$id);
		$query = $this->db->get();
		return $query->result();
	}
	public function save($data)
	{
		$this->db->set("UserAdd",$this->session->userdata("NAMA"));
		$this->db->set("DateAdd",date("Y-m-d H:i:s"));
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
	public function save_detail($data)
	{
		$this->db->set("UserAdd",$this->session->userdata("NAMA"));
		$this->db->set("DateAdd",date("Y-m-d H:i:s"));
		$this->db->insert("SP_TransactionRouteDetail", $data);
		return $this->db->insert_id();
	}
	public function update($where, $data)
	{
		$this->db->set("UserCh",$this->session->userdata("NAMA"));
		$this->db->set("DateCh",date("Y-m-d H:i:s"));
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}
	public function update_detail($where, $data)
	{
		$this->db->set("UserCh",$this->session->userdata("NAMA"));
		$this->db->set("DateCh",date("Y-m-d H:i:s"));
		$this->db->update("SP_TransactionRouteDetail", $data, $where);
		return $this->db->affected_rows();
	}
	public function delete_by_id($id)
	{
		$this->db->where('TransactionRouteID', $id);
		$this->db->delete($this->table);

		$this->db->where('TransactionRouteID', $id);
		$this->db->delete("SP_TransactionRouteDetail");
	}

	public function getData(){
		$this->db->select("
			TransactionRouteID,
			CompanyID,
			");
		$query = $this->db->get($this->table);

		return $query;
	}
}
