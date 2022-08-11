<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_sales extends CI_Model {
    
    var $table = "PS_Sales";
    var $column = array(
        'PS_Sales.SalesID',
        'PS_Sales.Code',
        'PS_Sales.Name',
        'PS_Sales.Contact',
        'PS_Sales.City',
        'PS_Sales.Address',
        'PS_Sales.Status',
    );
    var $order      = array('PS_Sales.SalesID' => 'desc'); // default order 
    var $hak_akses  = '';
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set("Asia/Jakarta");
        $this->hak_akses = $this->session->hak_akses;
    }

    private function _get_datatables_query($page="")
    {
        $this->db->select("
            PS_Sales.SalesID,
            PS_Sales.Code,
            PS_Sales.Name,
            PS_Sales.Contact,
            PS_Sales.City,
            PS_Sales.Address,
            PS_Sales.Remark,
            PS_Sales.Status,
            ");
        $this->db->from($this->table);
        if($this->hak_akses == "super_admin" || $this->hak_akses == "company"):
            $this->db->where("PS_Sales.CompanyID",$this->session->CompanyID);
            // $this->db->where("PS_Sales.BranchID",null);
        else:
            $this->db->where("PS_Sales.CompanyID",$this->session->CompanyID);
            // $this->db->where("PS_Sales.BranchID",null);
        endif;
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
        if($this->input->post("Status") != "none"):
            $Status = $this->input->post("Status");
            $this->db->where("PS_Sales.Status", $Status);
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
        $this->db->where("PS_Sales.CompanyID",$this->session->CompanyID);
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function count_all($page="")
    {
        $this->db->where("PS_Sales.CompanyID",$this->session->CompanyID);
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

    public function get_by_id($id){
        $this->db->select("
            SalesID,
            Code,
            Name,
            Contact,
            City,
            Address,
            Remark,
            Status
            ");
        $this->db->where("SalesID",$id);
        $this->db->where("CompanyID", $this->session->CompanyID);
        $query = $this->db->get($this->table);

        return $query->row();
    }
}