<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_inventory_goodreceipt extends CI_Model {
	var $table = "PS_Correction";
	var $column = array(
        'PS_Correction.CorrectionNo',
        'PS_Correction.CorrectionNo',
        'Branch.Name',
        'PS_Correction.Date',
        'PS_Correction.Status',
    );
    var $order  = array('PS_Correction.CorrectionNo' => 'desc'); // default order 

	public function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set("Asia/Jakarta");
    }

    private function _get_datatables_query($page="")
    {
        $table = $this->table;
        $this->db->select("
            $table.CorrectionNo,    
            $table.Date,
            $table.Status,
            $table.BranchID,
            ifnull(Branch.Name,'') as branchName,
        ");
        $this->db->from($this->table);
        $this->db->join("Branch", "$table.BranchID = Branch.BranchID and $table.CompanyID = Branch.CompanyID", "left");
        $this->db->where("$table.CompanyID",$this->session->CompanyID);
        $this->db->where("$table.Type",3);
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
        $Branch = $this->input->post('Branch');
        if($Branch && $Branch != "all"):
            $this->db->where("$table.BranchID", $Branch);
        endif;
        if(isset($_POST['order'])){
            $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order)){
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
    function count_filtered($page="")
    {
        $this->_get_datatables_query($page);
        $this->db->where("PS_Correction.CompanyID",$this->session->CompanyID);
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function count_all($page="")
    {   
        $this->db->where("PS_Correction.Type",3);
        $this->db->where("PS_Correction.CompanyID",$this->session->CompanyID);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function save($data)
    {
        $this->db->set("User_Add",$this->session->NAMA);
        $this->db->set("Date_Add",date("Y-m-d H:i:s"));
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();  
    }
    public function update($where, $data)
    {
        $this->db->set("User_Ch",$this->session->NAMA);
        $this->db->set("Date_Ch",date("Y-m-d H:i:s"));
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function save_det($data)
    {
        $this->db->set("User_Add",$this->session->NAMA);
        $this->db->set("Date_Add",date("Y-m-d H:i:s"));
        $this->db->insert("PS_Correction_Detail", $data);
        return $this->db->insert_id();  
    }
    public function update_det($where, $data)
    {
        $this->db->set("User_Ch",$this->session->NAMA);
        $this->db->set("Date_Ch",date("Y-m-d H:i:s"));
        $this->db->update("PS_Correction_Detail", $data, $where);
        return $this->db->affected_rows();
    }

    public function get_by_id($id){
    	$table 		= $this->table;
    	$CompanyID 	= $this->session->CompanyID;

    	$this->db->select("
    		$table.CorrectionNo,
    		$table.Date,
    		$table.BranchID,
    		$table.SalesID,
    		$table.Status,
    		$table.Remark,

    		Branch.Name 	as branchName,
    		sales.Name 		as salesName,
		");
		$this->db->from($table);
		$this->db->join("Branch", "$table.BranchID = Branch.BranchID and $table.CompanyID = Branch.CompanyID", "left");
		$this->db->join("PS_Sales 		as sales", "$table.SalesID = sales.SalesID and $table.CompanyID = sales.CompanyID", "left");
		$this->db->where("$table.CompanyID", $CompanyID);
		$this->db->where("$table.CorrectionNo", $id);

		$query = $this->db->get();

		return $query->row();
    }

    public function get_by_detail($id){
    	$CompanyID = $this->session->CompanyID;

    	$this->db->select("
    		dt.CorrectionDetID as ID,
    		dt.Qty,
    		dt.Conversion,
    		dt.Price,
    		dt.Remark,
    		dt.ProductID,
    		(dt.Qty * dt.Conversion) as total_qty,

    		product.Name 		as product_name,
    		product.Code 		as product_code,
    		product.Type 		as product_type,
    		ifnull(unit.Uom,'') as unit_name,
		");
		$this->db->from("PS_Correction_Detail as dt");
		$this->db->join("ps_product as product", "product.ProductID = dt.ProductID");
		$this->db->join("ps_product_unit as unit", "unit.ProductUnitID = dt.Uom", "left");
		$this->db->where("dt.CompanyID", $CompanyID);
		$this->db->where("dt.CorrectionNo", $id);

		$query = $this->db->get();

		return $query->result();
    }

    public function serial_number_list($mt,$dt){
        $CompanyID  = $this->session->CompanyID;
        $table      = "PS_Correction_Detail_SN";

        $this->db->select("
            SN,
            product.Name as product_name,
            'Serial'    as product_type,
            dt.Qty,
        ");

        $this->db->join("ps_product         as product", "product.ProductID = $table.ProductID", "left");
        $this->db->join("PS_Correction_Detail as dt", "dt.CorrectionDetID = $table.CorrectionDetID and dt.CorrectionNo = $table.CorrectionNo and dt.CompanyID = $table.CompanyID");
        $this->db->where("$table.CompanyID", $CompanyID);
        $this->db->where("$table.CorrectionNo", $mt);
        $this->db->where("$table.CorrectionDetID", $dt);
        $query = $this->db->get($table);

        return $query->result();
    }
}