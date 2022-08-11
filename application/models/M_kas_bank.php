<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_kas_bank extends CI_Model {
    
    var $table = "AC_KasBank";
    var $column = array(
        'AC_KasBank.KasBankNo',
        'AC_KasBank.KasBankNo',
        'AC_KasBank.Date',
        'AC_KasBank.DebitTotal',
        'AC_KasBank.CreditTotal',
    );
    var $order      = array('AC_KasBank.Date_Add' => 'desc'); // default order 
    var $hak_akses  = '';
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set("Asia/Jakarta");
        $this->hak_akses = $this->session->hak_akses;
    }

    private function _get_datatables_query()
    {
        $table = $this->table;
        $this->db->select("
            $table.KasBankNo,
            $table.Date,
            $table.DebitTotal,
            $table.CreditTotal,
        ");
        $this->db->from($this->table);
        $this->db->where("$table.CompanyID",$this->session->CompanyID);
        $this->db->where_in("$table.Type",array(1,2));
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
        // if($this->input->post("Status") != "none"):
        //     $Status = $this->input->post("Status");
        //     $this->db->where("$table.Status", $Status);
        // endif;
        
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
        $this->db->where("AC_KasBank.CompanyID", $this->session->CompanyID);
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
        $this->db->insert("AC_KasBank_Det", $data);
        return $this->db->insert_id();  
    }
    public function update_det($where, $data)
    {
        $this->db->set("User_Ch",$this->session->NAMA);
        $this->db->set("Date_Ch",date("Y-m-d H:i:s"));
        $this->db->update("AC_KasBank_Det", $data, $where);
        return $this->db->affected_rows();
    }

    public function delete_by_id($id)
    {
        $this->db->where('KasBankNo', $id);
        $this->db->where('CompanyID', $this->session->CompanyID);
        $this->db->delete($this->table);
    }

    public function delete_by_detail($id){
        $this->db->where('KasBankNo', $id);
        $this->db->where('CompanyID', $this->session->CompanyID);
        $this->db->delete("AC_KasBank_Det");
    }

    public function get_by_id($id){
        $CompanyID = $this->session->CompanyID;
        $table = $this->table;
        $this->db->select("
            $table.KasBankNo,
            $table.Date,
            $table.DebitTotal,
            $table.CreditTotal,
            $table.Remark,
            $table.Type,

            case
                when $table.Type = 1 then 'Cash'
                else 'Bank'
            end as Typetxt,
        ");
        $this->db->where("$table.CompanyID", $CompanyID);
        $this->db->where_in("$table.Type",array(1,2));
        $this->db->where("$table.KasBankNo", $id);
        $query = $this->db->get($table);

        return $query->row();
    }

    public function get_by_detail($id){
        $CompanyID = $this->session->CompanyID;
        $table = "AC_KasBank_Det";
        $this->db->select("
            $table.KasBankNo,
            $table.KasBankDetNo,
            $table.COAID,
            $table.Debit,
            $table.Credit,
            $table.Remark,

            coa.Name as coaName,
            coa.Code as coaCode,
        ");
        $this->db->join("AC_COA as coa", "coa.COAID = $table.COAID", "left");
        $this->db->where("$table.CompanyID", $CompanyID);
        $this->db->where("$table.KasBankNo", $id);
        $query = $this->db->get($table);

        return $query->result();        
    }
}