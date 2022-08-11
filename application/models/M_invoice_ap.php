<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_invoice_ap extends CI_Model {
    
    var $table = "PS_Invoice";
    var $column = array(
        'PS_Invoice.InvoiceNo',
        'PS_Invoice.InvoiceNo',
        'PS_Invoice.Date',
        'vendor.Name',
        'PS_Invoice.Status',
        'PS_Invoice.Total',
    );
    var $order  = array('PS_Invoice.InvoiceNo' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set("Asia/Jakarta");
    }

    private function _get_datatables_query($page="")
    {
        $table = $this->table;

        $payment = "(select count(dt.InvoiceNo) from PS_Payment_Detail dt join PS_Payment mt
            on mt.CompanyID = dt.CompanyID and mt.PaymentNo = dt.PaymentNo
            where mt.CompanyID = $table.CompanyID and dt.InvoiceNo = $table.InvoiceNo and mt.Status = '1'
            )";

        $this->db->select("
            $table.InvoiceNo, 
            $table.Date,
            $table.Status,
            $table.Total,
            $table.OrderType,
            $table.VendorID,

            vendor.Name as vendorName,

            ifnull($payment,0) as ck_payment,
        ");
        $this->db->join("PS_Vendor as vendor", "$table.VendorID = vendor.VendorID and $table.VendorID = vendor.VendorID", "left");
        $this->db->from($this->table);
        $this->db->where("$table.CompanyID",$this->session->CompanyID);
        $this->db->where("$table.BranchID",null);
        $this->db->where("$table.Type",1);
        if($this->input->post('purchaseno')):
            // $this->db->where("$table.SellNo", $this->input->post('sellno'));
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
        $this->db->where("PS_Invoice.CompanyID",$this->session->CompanyID);
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function count_all($page="")
    {
        $this->db->where("PS_Invoice.CompanyID",$this->session->CompanyID);
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
        $this->db->insert("PS_Invoice_Detail", $data);
        return $this->db->insert_id();  
    }
    public function update_det($where, $data)
    {
        $this->db->set("User_Ch",$this->session->NAMA);
        $this->db->set("Date_Ch",date("Y-m-d H:i:s"));
        $this->db->update("PS_Invoice_Detail", $data, $where);
        return $this->db->affected_rows();
    }

    public function get_by_id($id,$tambahan=""){
        $table      = $this->table;
        $CompanyID  = $this->session->CompanyID;
        $this->db->select("
            $table.InvoiceNo,
            $table.Date,
            $table.VendorID,
            $table.CompanyID,
            $table.Total,
            $table.InvoiceName,
            $table.InvoiceAddress,
            $table.InvoiceCity,
            $table.InvoiceProvince,
            $table.InvoiceNPWP,
            $table.Remark,
            $table.Type,
            ifnull($table.Term, 0) as Term,
            $table.OrderType,
            $table.DeliveryCost,
            $table.Status,
            $table.InvoiceAddress   as address,
            $table.InvoiceCity      as city,
            $table.InvoiceProvince  as province,
            $table.PaymentStatus,

            vendor.Name     as vendorName,
            vendor.Phone    as vendorPhone,
        ");
        $this->db->join("PS_Vendor as vendor", "$table.VendorID = vendor.VendorID", "left");
        $this->db->from($table);
        $this->db->where("$table.CompanyID", $CompanyID);
        $this->db->where("$table.InvoiceNo", $id);

        if($tambahan == "edit"):
            $this->db->select("
                ifnull((select count(dt.InvoiceNo) from PS_Payment_Detail dt join PS_Payment mt on dt.PaymentNo = mt.PaymentNo and dt.CompanyID = mt.CompanyID where dt.InvoiceNo = $table.InvoiceNo and dt.CompanyID = $table.CompanyID and mt.Status = '1'), 0) as PaymentCount,
            ");
        endif;

        $query = $this->db->get();

        return $query->row();
    }

    public function get_by_detail($id){
        $CompanyID  = $this->session->CompanyID;
        $table      = "PS_Invoice_Detail";
        $this->db->select("
            $table.InvoiceDet,
            $table.InvoiceNo,
            ifnull($table.ReceiveNo,'') as ReceiveNo,
            ifnull($table.ReturNo,'')    as ReturNo,
            $table.Date,
            $table.DeliveryCost,
            $table.Discount,
            $table.PPN,
            $table.SubTotal,
            $table.Total,
            $table.Remark,

            ifnull(del.PurchaseNo, '') as PurchaseNo,

            (case 
                when $table.ReturNo is not null then 'return'
                else 'receive'
            end) as invoiceType,

            (case 
                when $table.ReturNo is not null then 'Return'
                else 'receive'
            end) as invoiceTypetxt,

            del.SJNo as DeliveryCode,
        ");
        $this->db->join("AP_GoodReceipt as del", "$table.ReceiveNo = del.ReceiveNo and $table.CompanyID = del.CompanyID", "left");
        $this->db->from($table);
        $this->db->where("$table.CompanyID", $CompanyID);
        $this->db->where("$table.InvoiceNo", $id);
        $query = $this->db->get();

        return $query->result();
    }

    public function get_by_detail_payable($id){
        $CompanyID  = $this->session->CompanyID;
        $table      = "PS_Invoice_Detail";
        $this->db->select("
            $table.InvoiceDet,
            $table.InvoiceNo,
            ('')                        as ReceiveNo,
            ifnull($table.ReturNo,'')   as ReturNo,
            ifnull($table.PurchaseNo,return.PurchaseNo)    as PurchaseNo,
            $table.Date,
            $table.DeliveryCost,
            $table.Discount,
            $table.PPN,
            $table.SubTotal,
            $table.Total,
            $table.Remark,

            (case 
                when $table.ReturNo is not null then 'return'
                else 'purchase'
            end) as invoiceType,

            (case 
                when $table.ReturNo is not null then 'Return'
                else 'purchase'
            end) as invoiceTypetxt,
            '' as DeliveryCode,

        ");
        $this->db->join("AP_Retur as return", "return.ReturNo = $table.ReturNo and return.CompanyID = $table.CompanyID", "left");
        $this->db->from($table);
        $this->db->where("$table.CompanyID", $CompanyID);
        $this->db->where("$table.InvoiceNo", $id);
        $query = $this->db->get();

        return $query->result();
    }
}