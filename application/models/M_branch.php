<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_branch extends CI_Model {

	var $table = 'Branch';
	var $column = array(
		'BranchID',
		'Branch.Name',
		'Branch.Address',
		'Branch.City',
		'Branch.Province',
		'Branch.Country',
		'Branch.DeviceID',
	); //set column field database for order and search
	var $order = array('BranchID' => 'desc'); // default order 
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	private function _get_datatables_query()
	{
		$this->db->select("
			Branch.BranchID 	 as BranchID,
			Branch.Name 		 as Name,
			Branch.FirstName 	 as FirstName,
			Branch.LastName 	 as LastName,
			Branch.Email 		 as Email,
			Branch.Phone 		 as Phone,
			Branch.Address 		 as Address,
			Branch.City 		 as City,
			Branch.Province 	 as Province,
			Branch.Country 		 as Country,
			Branch.Token 		 as Token,
			Branch.DeviceID 	 as DeviceID,
			Branch.Active 		 as Active,
			Branch.ExpireAccount as ExpireAccount,
			Branch.Index,
		");
		$this->db->order_by("Branch.Active","DESC");
		$this->db->where("Branch.CompanyID",$this->session->CompanyID);
		$this->db->where("Branch.App",$this->session->app);

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
		$this->db->where("Branch.CompanyID",$this->session->CompanyID);
		$this->db->where("Branch.App",$this->session->app);
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->select("
			Branch.BranchID 	 as BranchID,
			Branch.Name 		 as Name,
			Branch.FirstName 	 as FirstName,
			Branch.LastName 	 as LastName,
			Branch.Email 		 as Email,
			Branch.Phone 		 as Phone,
			Branch.Postal 		 as Postal,
			Branch.Fax 			 as Fax,
			Branch.Address 		 as Address,
			Branch.City 		 as City,
			Branch.Province 	 as Province,
			Branch.Country 		 as Country,
			Branch.Token 		 as Token,
			Branch.DeviceID 	 as DeviceID,
			Branch.Active 		 as Active,
			Branch.ExpireAccount as ExpireAccount,
			Branch.Lat as Lat,
			Branch.Lng as Lng,
			Branch.Index,
		");
		$this->db->from($this->table);
		$this->db->where('BranchID',$id);
		$query = $this->db->get();
		return $query->row();
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
		$this->db->where('BranchID', $id);
		$this->db->delete($this->table);
	}
	public function copy_product($BranchID)
	{
		$this->db->select("ps_product.ProductID,ps_product.CompanyID");
		$this->db->join("ps_product as category","ps_product.parentcode = category.code","left");

		$this->db->where("ps_product.companyid",$this->session->companyid);
		$this->db->where("category.companyid",$this->session->companyid);
		$this->db->where("ps_product.position",0);

		$query = $this->db->get("ps_product");
		$data = $query->result();
		foreach($data as $a):
			$data = array(
				"ProductID" 	=> $a->ProductID,
				"CompanyID" 	=> $a->CompanyID,
				"BranchID" 		=> $BranchID,
				"Qty" 			=> 0,
			);
			$this->db->set("User_Add",$this->session->userdata("NAMA"));
			$this->db->set("Date_Add",date("Y-m-d H:i:s"));
			$this->db->insert("PS_Product_Branch",$data);
		endforeach;
	}
}
