<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_warehouse extends CI_Model {

	var $table 	= 'PS_Warehouse';
	var $column = array('PS_Warehouse.WarehouseID',
						'PS_Warehouse.CompanyID',
						'PS_Warehouse.Name',
						'PS_Warehouse.Code',
						'PS_Warehouse.Address',
						'PS_Warehouse.Description',
						'PS_Warehouse.Active'); //set column field database for order and search
	var $order 	= array('WarehouseID' => 'desc'); // default order 
	var $host;
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->host = base_url();
	}
	private function _get_datatables_query()
	{
		$url = $this->uri->segment(1);
		$this->db->select("
			PS_Warehouse.WarehouseID,
			PS_Warehouse.CompanyID,
			PS_Warehouse.Name,
			PS_Warehouse.Code,
			PS_Warehouse.Address,
			PS_Warehouse.Description,
			PS_Warehouse.TypeCode,
			PS_Warehouse.Active as active,
		");
		$this->db->from($this->table);
		$this->db->where("PS_Warehouse.CompanyID", $this->session->CompanyID);
		$i = 0;
		foreach ($this->column as $item):
			if($_POST['search']['value']):
				if($i===0):
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND. 
					$this->db->like($item, $_POST['search']['value']);
				else:
					$this->db->or_like($item, $_POST['search']['value']);
				endif;
				if(count($this->column) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			endif;
			$column[$i] = $item; // set column array variable to order processing
			$i++;
		endforeach;
		if($this->input->post("Active") != "none"):
            $Active = $this->input->post("Active");
            $this->db->where("PS_Warehouse.Active", $Active);
        endif;
		if(isset($_POST['order'])):
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']); 
		elseif(isset($this->order)):
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		endif;
	}
	function get_datatables()
	{
		$this->_get_datatables_query();
		if($this->input->post("length") != -1)
		$this->db->limit($this->input->post("length"), $this->input->post("start"));
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
		$this->db->where("PS_Warehouse.CompanyID", $this->session->CompanyID);
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function save($data)
	{
		$this->db->set("UserAdd",$this->session->Name);
		$this->db->set("DateAdd",date("Y-m-d H:i:s"));
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
	public function update($where, $data)
	{
		$this->db->set("UserCh",$this->session->Name);
		$this->db->set("DateCh",date("Y-m-d H:i:s"));
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function get_by_id($id){
		$this->db->select("
			PS_Warehouse.WarehouseID,

			PS_Warehouse.Name,
			PS_Warehouse.Code,
			PS_Warehouse.CompanyID,
			PS_Warehouse.Address,
			PS_Warehouse.Description,

			PS_Warehouse.Active as active,

		");
		$this->db->where("WarehouseID", $id);
		$query = $this->db->get($this->table);

		return $query->row();
	}

	public function copy_product($WarehouseID)
	{
		$this->db->select("ps_product.ProductID,ps_product.CompanyID");
		$this->db->where("ps_product.companyid",$this->session->companyid);
		$this->db->where("ps_product.position",0);

		$query = $this->db->get("ps_product");
		$data = $query->result();
		foreach($data as $a):
			$data = array(
				"ProductID" 	=> $a->ProductID,
				"CompanyID" 	=> $a->CompanyID,
				"WarehouseID" 	=> $WarehouseID,
				"Qty" 			=> 0,
			);
			$this->db->set("UserAdd",$this->session->userdata("NAMA"));
			$this->db->set("DateAdd",date("Y-m-d H:i:s"));
			$this->db->insert("PS_Product_Warehouse",$data);
		endforeach;
	}
}