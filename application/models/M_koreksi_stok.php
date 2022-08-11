<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_koreksi_stok extends CI_Model {

	var $table 	= 'PS_Correction';
	var $column = array(
		'PS_Correction.CorrectionNo',
		'PS_Correction.CorrectionNo',
		'Branch.Name',
		'PS_Correction.Date',
	); //set column field database for order and search
	var $order 	= array('PS_Correction.CorrectionNo' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($page="")
	{
		$url = $this->uri->segment(1);
		$this->db->select("
			PS_Correction.CorrectionNo as CorrectionNo,
			PS_Correction.CorrectionNo as correctionno, 
			PS_Correction.BranchID,
			PS_Correction.Date 		as date,

			ifnull(Branch.Name,'') 	as branchName,
		");

		$this->db->join("Branch", "Branch.BranchID = PS_Correction.BranchID and Branch.CompanyID = PS_Correction.CompanyID", "left");
		$this->db->from($this->table);
		$this->db->where("PS_Correction.CompanyID",$this->session->CompanyID);
		$this->db->where("PS_Correction.Type", 1);
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
            $this->db->where("Date(PS_Correction.Date) >=", $StartDate);
        endif;
        if($this->input->post("EndDate")):
            $EndDate = $this->input->post("EndDate");
            $this->db->where("Date(PS_Correction.Date) <=", $EndDate);
        endif;
        $BranchID = $this->input->post("Branch");
        if($BranchID != "all" and $BranchID):
            $this->db->where("PS_Correction.BranchID", $BranchID);
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
		$this->db->where("PS_Correction.Type", 1);
		$this->db->where("PS_Correction.CompanyID",$this->session->companyid);
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->select("
			PS_Correction.CorrectionNo as correctionno, 
			PS_Correction.BranchID, 
			ifnull(Branch.Name,'') as branchName, 
			PS_Correction.Date 	as date");
		$this->db->join("Branch", "Branch.BranchID = PS_Correction.BranchID and Branch.CompanyID = PS_Correction.CompanyID", "left");
		$this->db->from($this->table);
		$this->db->where("PS_Correction.CompanyID",$this->session->companyid);
		$this->db->where('PS_Correction.CorrectionNo',$id);
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
		$this->db->where('CorrectionNo', $id);
		$this->db->delete($this->table);
	}
	public function get_list_detail($id,$page = "")
	{
		$this->db->select("
			cd.CorrectionDetID,
			cd.CorrectionDet 	as correctiondet,
			cd.CorrectionNo 	as correctionno,
			cd.Qty 				as qty,
			cd.CorrectionQty 	as realqty,
			cd.ProductID 		as productid,
			ps_product.Code 	as product_code,
			ps_product.Name 	as product_name,
			ps_product.Type 	as product_type,
			ps_unit.Name 		as unit_name,
		");
		$this->db->join("ps_product","cd.ProductID = ps_product.ProductID","left");
		$this->db->join("ps_unit","cd.UnitID = ps_unit.UnitID","left");
		$this->db->where("cd.CompanyID",$this->session->CompanyID);
		if($page == "add_serial"):
			$this->db->where("CorrectionNo",$id);
		elseif($page == "detail"):
			$this->db->where("CorrectionDet",$id);
		else:
			$this->db->where("CorrectionNo",$id);
		endif;
		$query = $this->db->get("PS_Correction_Detail as cd");
		if($page == "add_serial" || $page == "detail"):
			return $query->row();
		else:
			return $query->result();
		endif;
	}

	public function serial_number_list($mt,$dt){
        $CompanyID  = $this->session->CompanyID;
        $table      = "PS_Correction_Detail_SN";

        $this->db->select("
            SN,
            product.Name 	 as product_name,
            'Serial'     	 as product_type,
            dt.CorrectionQty as Qty,
        ");

        $this->db->join("ps_product       as product", "product.ProductID = $table.ProductID", "left");
        $this->db->join("PS_Correction_Detail  as dt", "dt.CorrectionNo = $table.CorrectionNo and dt.CorrectionDetID = $table.CorrectionDetID and dt.CompanyID = $table.CompanyID");

        $this->db->where("$table.CompanyID", $CompanyID);
        $this->db->where("$table.CorrectionNo", $mt);
        $this->db->where("$table.CorrectionDetID", $dt);
        $query = $this->db->get($table);

        return $query->result();
    }
}
