<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_retur extends CI_Model {

	var $table 	= 'AP_Retur';
	var $column = array('ReturNo'); //set column field database for order and search
	var $order 	= array('Date' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($page = "")
	{
		$url = $this->uri->segment(1);
		$this->db->select("
			r.ReturNo 	as returno,
			r.Date 		as returdate,
			r.SellNo 	as sellno,
			r.ReceiveNo as receiveno,
			(CASE WHEN r.Type = '1' THEN 'purchase' ELSE 'sell' END) as page,
			r.Type 		as type,
			v.Name 		as vendorname,
		");
		$this->db->from($this->table." as r");
		$this->db->join("PS_Vendor as v","r.VendorID = v.VendorID","left");
		$this->db->where("r.CompanyID",$this->session->CompanyID);
		// $this->db->where("r.Type",1);
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

	function get_datatables($page = "")
	{
		$this->_get_datatables_query($page);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($page = "")
	{
		$this->_get_datatables_query($page = "");
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($page = "")
	{
		$url = $this->uri->segment(1);
		$this->db->where("r.CompanyID",$this->session->CompanyID);
		// $this->db->where("r.Type",1);
		$this->db->from($this->table." as r");

		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->select("
			r.ReturNo 	as returno,
			r.Date 		as returdate,
			r.SellNo 	as sellno,
			r.ReceiveNo	as receiveno,
			(CASE WHEN r.Type = 1 THEN 'sell' ELSE 'purchase' END) as page,
			v.Name 	as vendorname,
		");
		$this->db->from($this->table." as r");
		$this->db->join("PS_Vendor as v","r.VendorID = v.VendorID","left");
		$this->db->where("r.CompanyID",$this->session->CompanyID);
		$this->db->where('ReturNo',$id);
		$query = $this->db->get();
		return $query->row();
	}
	public function get_list_detail($id,$page = "")
	{
		$this->db->select("
			r.ReturDet 			as returdet,
			r.ReturNo 			as returno,
			r.ProductID 		as productid,
			r.Qty 				as product_qty,
			r.Conversion 		as product_konv,
			r.Price 			as product_sellprice,
			r.Remark 			as remark,
			r.UnitID 			as unitid,
			r.SerialNumber 		as serialnumber,
			ps_product.Code 	as product_code,
			ps_product.Name 	as product_name,
			(CASE
            WHEN ps_product.type = 1 THEN 'unique'
            WHEN ps_product.type = 2 THEN 'serial'
            ELSE 'general' END) AS product_type,
			ps_unit.Name as unit_name,

		");
		$this->db->join("ps_product","r.ProductID = ps_product.ProductID","left");
		$this->db->join("ps_unit","r.UnitID = ps_unit.UnitID","left");

		$this->db->where("r.CompanyID",$this->session->CompanyID);
		if($page == "add_serial"):
			$this->db->where("r.ReturDet",$id);
		else:
			$this->db->where("r.ReturNo",$id);
		endif;
		$query = $this->db->get("AP_Retur_Det as r");
		if($page == "add_serial"):
			return $query->row();
		else:
			return $query->result();
		endif;
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
		$this->db->where('ReturNo', $id);
		$this->db->delete($this->table);
	}
}
