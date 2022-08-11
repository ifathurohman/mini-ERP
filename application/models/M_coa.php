<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_coa extends CI_Model {
    
    var $table = "AC_COA";
    var $column = array(
        'AC_COA.COAID',
        'AC_COA.Code',
        'AC_COA.Name',
        'AC_COA.Position',
        'AC_COA.ParentID',
        'AC_COA.Remark'
    );
    var $order  = array('AC_COA.Code' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set("Asia/Jakarta");
    }

    private function _get_datatables_query($page="")
    {
        $this->db->select("
            AC_COA.COAID,
            AC_COA.Code,
            AC_COA.Name,
            AC_COA.Position,
            AC_COA.ParentID,
            AC_COA.Remark,
            AC_COA.Active,
            parent.Name as parentName,
            ");
        $this->db->from($this->table);
        $this->db->join("AC_COA as parent", "AC_COA.ParentID = parent.COAID", "left");
        $this->db->where("AC_COA.CompanyID", $this->session->CompanyID);

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
        if($this->input->post("Active") != "none"):
            $Active = $this->input->post("Active");
            $this->db->where("AC_COA.Active", $Active);
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
        $this->db->where("AC_COA.CompanyID",$this->session->CompanyID);
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function count_all($page="")
    {
        $this->db->where("AC_COA.CompanyID",$this->session->CompanyID);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function save($data)
    {
        $this->db->set("UserAdd",$this->session->NAMA);
        $this->db->set("DateAdd",date("Y-m-d H:i:s"));
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();  
    }
    public function update($where, $data)
    {
        $this->db->set("UserCh",$this->session->NAMA);
        $this->db->set("DateCh",date("Y-m-d H:i:s"));
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function get_by_id($id){
        $this->db->select("
            COAID,
            Code,
            ParentID,
            Name,
            Position,
            Remark,
            Active,
            PaymentType,
            ");
        $this->db->where("COAID", $id);
        $this->db->where("CompanyID", $this->session->CompanyID);
        $query = $this->db->get($this->table);

        return $query->row();
    }

}