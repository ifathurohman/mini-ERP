<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_payment_ar extends CI_Model {
    
    var $table = "PS_Payment";
    var $column = array(
        'PS_Payment.PaymentNo',
        'PS_Payment.PaymentNo',
        'PS_Payment.Date',
        'vendor.Name',
        'PS_Payment.Total',
        'PS_Payment.Total',
        'PS_Payment.Status',
    );
    var $order  = array('PS_Payment.PaymentNo' => 'desc'); // default order 

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
            $table.PaymentNo,    
            $table.Date,    
            $table.VendorID,
            $table.Total,
            $table.Total Paid,
            $table.Status,

            vendor.Name as vendorName,
        ");
        $this->db->join("PS_Vendor as vendor", "$table.VendorID = vendor.VendorID", "left");
        $this->db->from($this->table);
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
        if($this->input->post("Status") != "none"):
            $Status = $this->input->post("Status");
            $this->db->where("$table.Status", $Status);
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
        $this->db->where("PS_Payment.CompanyID",$this->session->CompanyID);
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function count_all($page="")
    {
        $this->db->where("PS_Payment.CompanyID",$this->session->CompanyID);
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
        $this->db->insert("PS_Payment_Detail", $data);
        return $this->db->insert_id();  
    }
    public function update_det($where, $data)
    {
        $this->db->set("User_Ch",$this->session->NAMA);
        $this->db->set("Date_Ch",date("Y-m-d H:i:s"));
        $this->db->update("PS_Payment_Detail", $data, $where);
        return $this->db->affected_rows();
    }

    public function get_by_id($id){
        $table      = $this->table;
        $CompanyID  = $this->session->CompanyID;
        $this->db->select("
            $table.PaymentNo,
            $table.Date,
            $table.VendorID,
            $table.Status,
            $table.Type,
            ifnull($table.PaymentType, '') as PaymentType,
            ifnull($table.PaymentType1, '') as PaymentType1,
            ifnull($table.PaymentType2, '') as PaymentType2,
            
            $table.PaymentMethod,
            $table.PaymentMethod1,
            $table.PaymentMethod2,
            $table.Total,
            $table.TotalAwal,
            $table.GiroNo,
            $table.AcountNo,
            $table.BankName,
            $table.BankName1,
            $table.AccountName,
            $table.AccountName1,
            $table.Remark,

            $table.Cash,
            $table.Giro,
            $table.Credit,

            vendor.Name     as vendorName,
            vendor.Address  as vendorAddress,
            vendor.Phone    as vendorPhone,
            coa.Code        as coaCode,
            coa.Name        as coaName,

            coa_giro.Code    as giro_coaCode,
            coa_giro.Name    as giro_coaName,

            coa_credit.Code  as credit_coaCode,
            coa_credit.Name  as credit_coaName,
        ");
        $this->db->join("PS_Vendor  as vendor","$table.VendorID = vendor.VendorID", "left");
        $this->db->join("AC_COA     as coa","$table.PaymentMethod = coa.COAID", "left");
        $this->db->join("AC_COA     as coa_giro","$table.PaymentMethod1 = coa_giro.COAID", "left");
        $this->db->join("AC_COA     as coa_credit","$table.PaymentMethod2 = coa_credit.COAID", "left");
        $this->db->where("$table.PaymentNo", $id);
        $this->db->where("$table.CompanyID", $CompanyID);
        $this->db->where("$table.Type", 3);
        $this->db->from($table);
        $query = $this->db->get();

        return $query->row();
    }

    public function get_by_detail($id){
        $table      = "PS_Payment_Detail";
        $CompanyID  = $this->session->CompanyID;
        $this->db->select("
            $table.PaymentDet,
            $table.PaymentNo,
            $table.InvoiceNo,
            $table.BalanceID,
            $table.BalanceDetID,
            $table.TotalPay,
            $table.TotalUnpaid,
            $table.Total,
            $table.Type,
            $table.Date,
            ifnull($table.Remark, '') as Remark,

            invoice.Date    as invoiceDate,
            invoice.Remark  as invoiceRemark,

            balance.Date    as balanceDate,
            balance.Code    as balanceCode,
            balance.BalanceType as balanceType,
        ");
        $this->db->join("AC_BalancePayable_Det as balancedet", "balancedet.BalanceDetID = $table.BalanceDetID", "left");
        $this->db->join("AC_BalancePayable as balance", "balance.BalanceID = $table.BalanceID", "left");
        $this->db->join("PS_Invoice as invoice", "$table.InvoiceNo = invoice.InvoiceNo and $table.CompanyID = invoice.CompanyID", "left");
        $this->db->where("$table.CompanyID", $CompanyID);
        $this->db->where("$table.PaymentNo", $id);
        $this->db->from($table);
        $query = $this->db->get();

        return $query->result();
    }
}