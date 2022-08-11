<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_mutasi extends CI_Model {

	var $table 	= 'PS_Mutation';
	var $column = array('psm.MutationNo','psm.MutationNo','psm.Date','b1.Name','b2.Name','psm.Status',); //set column field database for order and search
	var $order 	= array('MutationNo' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$url = $this->uri->segment(1);
		$this->db->select("
			psm.MutationNo 	 as mutation_no,
			psm.Date 		 as mutation_date,
			psm.Type 		 as type,
			psm.Status 		 as status,
			b2.Name 		 as to_name,
			user.nama 		 as nama,
			psm.BranchID,
			psm.BranchIDTo,

			(CASE
            WHEN psm.Type = 1 THEN b1.Name
            WHEN psm.Type = 2 THEN b1.Name
            ELSE  user.nama
            END) AS from_name,
            (CASE
            WHEN psm.Type = 1 THEN b2.Name
            WHEN psm.Type = 2 THEN user.nama
            ELSE  b2.Name
            END) AS to_name,

            (CASE 
            WHEN psm.Type = 1 THEN 'Store to store' 
			WHEN psm.Type = 2 THEN 'Store to company'
            ELSE 'Company to store' END) as type

		");
		$this->db->join("Branch as b1","psm.BranchID = b1.BranchID","left");
		$this->db->join("Branch as b2","psm.BranchIDTo = b2.BranchID","left");
		$this->db->join("user as user","psm.CompanyID = user.id_user","left");

		$this->db->where("psm.Type",1);
		$this->db->where("psm.CompanyID",$this->session->CompanyID);
		$this->db->from($this->table." as psm");
		$i = 0;

		$Search 	= $this->input->post("Search");
		foreach ($this->column as $item) // loop column 
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

				if(count($this->column) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$column[$i] = $item; // set column array variable to order processing
			$i++;
		}
		if($this->input->post("StartDate")):
            $StartDate = $this->input->post("StartDate");
            $this->db->where("Date(psm.Date) >=", $StartDate);
        endif;
        if($this->input->post("EndDate")):
            $EndDate = $this->input->post("EndDate");
            $this->db->where("Date(psm.Date) <=", $EndDate);
        endif;
        $fromBranch = $this->input->post("fromBranch");
        $toBranch 	= $this->input->post("toBranch");
        if($fromBranch != "all" && $fromBranch):
            $this->db->where("psm.BranchID", $fromBranch);
        endif;
        if($toBranch != "all" && $toBranch):
            $this->db->where("psm.BranchIDTo", $toBranch);
        endif;
		
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
		$this->db->where("Type",1);
		$this->db->where("CompanyID",$this->session->CompanyID);
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{	
		$CompanyID = $this->session->CompanyID;
		$this->db->select("
			psm.MutationNo as mutation_no,
			psm.Date as mutation_date,
			psm.Remark as mutation_remark,
			psm.Type as mutation_type,
			b1.Name as from_name,
			b2.Name as to_name,
		");

		$this->db->from($this->table." as psm");
		$this->db->join("Branch as b1","psm.BranchID = b1.BranchID","left");
		$this->db->join("Branch as b2","psm.BranchIDTo = b2.BranchID","left");
		$this->db->where('MutationNo',$id);
		$this->db->where("psm.CompanyID", $CompanyID);
		$query = $this->db->get();
		return $query->row();
	}
	public function get_list_detail($id,$page = "")
	{
		$this->db->select("
			psmd.MutationDet 	as mutation_det,
			psmd.MutationNo 	as mutation_no,
			psmd.ProductID 		as productid,
			psmd.Qty 			as mutation_qty,
			psmd.Conversion 	as mutation_konv,
			psmd.Price 			as mutation_price,
			psmd.Remark 		as remark,
			psmd.Uom 			as unitid,
			psmd.SerialNumber 	as serialnumber,
			ps_product.Code 	as product_code,
			ps_product.Name 	as product_name,
			ps_product.Type 	as product_type,
			ifnull(unit.Uom,'') as unit_name,
			pm.BranchIDTo as branchid,
		");
		$this->db->join("ps_product","psmd.ProductID = ps_product.ProductID","left");
		// $this->db->join("ps_unit","psmd.UnitID = ps_unit.UnitID","left");
		$this->db->join("ps_product_unit as unit", "unit.ProductUnitID = psmd.Uom", "left");
		$this->db->join("PS_Mutation as pm","psmd.MutationNo = pm.MutationNo","left");
		$this->db->where("psmd.CompanyID",$this->session->CompanyID);
		$this->db->where("pm.CompanyID",$this->session->CompanyID);
		if($page == "add_serial"):
			$this->db->where("psmd.MutationDet",$id);
		else:
			$this->db->where("psmd.MutationNo",$id);
		endif;
		$query = $this->db->get("PS_Mutation_Detail as psmd");
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
		$this->db->where('MutationNo', $id);
		$this->db->delete($this->table);
	}

	public function serial_number_list($mt,$dt){
        $CompanyID  = $this->session->CompanyID;
        $table      = "PS_Mutation_Detail_SN";

        $this->db->select("
            SN,
            product.Name 	 as product_name,
            'Serial'     	 as product_type,
            dt.Qty,
        ");

        $this->db->join("ps_product       as product", "product.ProductID = $table.ProductID", "left");
        $this->db->join("PS_Mutation_Detail  as dt", "dt.MutationNo = $table.MutationNo and dt.MutationDet = $table.MutationDet and dt.CompanyID = $table.CompanyID");

        $this->db->where("$table.CompanyID", $CompanyID);
        $this->db->where("$table.MutationNo", $mt);
        $this->db->where("$table.MutationDet", $dt);
        $query = $this->db->get($table);

        return $query->result();
    }
}
