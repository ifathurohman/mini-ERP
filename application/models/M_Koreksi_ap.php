<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Koreksi_ap extends CI_Model {

	var $table 	= 'AC_BalancePayable';
	var $column = array('Code','Code','Date'); //set column field database for order and search
	var $order 	= array('BalanceID' => 'desc'); // default order 
	var $CompanyID;
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->CompanyID = $this->session->CompanyID;
	}

	private function _get_datatables_query()
	{
		$url = $this->uri->segment(1);
		$table = $this->table;

		$payment = "(select count(dt.BalanceID) from PS_Payment_Detail dt join PS_Payment mt
            on mt.CompanyID = dt.CompanyID and mt.PaymentNo = dt.PaymentNo
            where mt.CompanyID = $table.CompanyID and dt.BalanceID = $table.BalanceID and mt.Status = '1'
            )";

		$this->db->select("
			BalanceID,
			Code 		as balanceno,
			Date 		as date,

			ifnull($payment,0) as ck_payment,
		");
		$this->db->from($this->table);
		$this->db->where("CompanyID",$this->CompanyID);
		$this->db->where("Type", 1);
		$i = 0;
		$i = 0;
        $column = $this->column;
        $Search = $this->input->post("Search");
        foreach ($column as $item) // loop column 
        {
            if($Search){
                
                if($i===0){
                    $this->db->group_start();
                    $this->db->like($item, $Search);
                }
                else{
                    $this->db->or_like($item, $Search);
                }
                if(count($column) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $column[$i] = $item; // set column array variable to order processing
            $i++;
        }
        if($this->input->post("StartDate")):
            $StartDate = $this->input->post("StartDate");
            $this->db->where("$table.Date >=", $StartDate);
        endif;
        if($this->input->post("EndDate")):
            $EndDate = $this->input->post("EndDate");
            $this->db->where("$table.Date <=", $EndDate." 23:59:59");
        endif;
        if($this->input->post("Status") != "none"):
            $Status = $this->input->post("Status");
            $this->db->where("$table.Active", $Status);
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
		$this->db->where("CompanyID", $this->session->CompanyID);
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($id,$tambahan="")
	{
		$table = $this->table;
		$this->db->select("
			BalanceID,
			Code 		as balanceno,
			Date 		as date,
			Type,
			BalanceType,
			OrderType,
			TotalCorrection,
			Active,
			Remark,

			case
				when OrderType = 1 then 'Vendor'
				else 'Purchase'
			end as OrderTypetxt,
			case 
				when BalanceType = 1 then 'Debit'
				else 'Credit'
			end as BalanceTypetxt,
		");
		$this->db->from($this->table);
		$this->db->where('BalanceID',$id);

		if($tambahan == "edit"):
			$this->db->select("ifnull((select count(dt.BalanceID) from PS_Payment_Detail dt join PS_Payment mt
            on mt.CompanyID = dt.CompanyID and mt.PaymentNo = dt.PaymentNo
            where mt.CompanyID = $table.CompanyID and dt.BalanceID = $table.BalanceID and mt.Status = '1'
            ),0) as ck_payment,");
		endif;

		$query = $this->db->get();

		return $query->row();
	}
	public function get_list_detail($id)
	{
		$this->db->select("
			cd.BalanceDetID,
			cd.BalanceID,
			cd.BranchID 		as branchid,
			cd.VendorID 		as vendorid,
			cd.TotalReal 		as total,
			cd.TotalCorrection 	as totalcorrection,
			cd.Payment 			as totalpayment,
			cd.Remark,
			b.Name 				as branchname,
			vendor.Name 		as vendorName,
		");
        $this->db->join("Branch as b","cd.BranchID = b.BranchID","left");
        $this->db->join("PS_Vendor as vendor","cd.VendorID = vendor.VendorID","left");
		$this->db->where("cd.CompanyID",$this->CompanyID);
		$this->db->where("cd.BalanceID",$id);
		$query = $this->db->get("AC_BalancePayable_Det as cd");
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

	public function save_detail($data)
	{
		$this->db->set("user_add",$this->session->userdata("NAMA"));
		$this->db->set("date_add",date("Y-m-d H:i:s"));
		$this->db->insert("AC_BalancePayable_Det", $data);
		return $this->db->insert_id();
	}

	public function update_detail($where, $data)
	{
		$this->db->set("user_ch",$this->session->userdata("NAMA"));
		$this->db->set("date_ch",date("Y-m-d H:i:s"));
		$this->db->update("AC_BalancePayable_Det", $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('BalanceID', $id);
		$this->db->where('CompanyID', $this->session->CompanyID);
		$this->db->delete($this->table);
	}

	public function delete_by_detail($id){
		$this->db->where('BalanceID', $id);
		$this->db->where('CompanyID', $this->session->CompanyID);
		$this->db->delete("AC_BalancePayable_Det");
	}

}
