<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_reset extends CI_Model {
    
    var $table = "UT_Reset";
    var $column = array(
        'UT_Reset.ResetID',
    );
    var $order  = array('UT_Reset.ResetID' => 'desc'); // default order

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
            $table.ResetID,    
            $table.Date,    
            $table.CompanyID,
            $table.UserID,
            $table.Type,
            $table.User_Add,
        ");
        $this->db->from($this->table);
        $this->db->where("$table.CompanyID",$this->session->CompanyID);
        
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
        if($this->input->post("Type")):
            $Type = $this->input->post("Type");
            $this->db->where("$table.Type", $Type);
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
        $this->db->where("UT_Reset.CompanyID",$this->session->CompanyID);
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function count_all($page="")
    {
        $this->db->where("UT_Reset.CompanyID",$this->session->CompanyID);
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

    #20190805 MW
    #delete transaction
    #selling data = payment sales,payment store, correction ar, invoice ar, return ar, delivery, selling.
    #purchase data = payment ap, correction ap, invoice ap, return ap, good receipt, purchase ap
    #inventory data = mutation, stock product, stock opname
    #journal data = journal manual, cash/bank
    #master product = update hpp/average, stock, SN, purchase price
    public function transaction_delete(){
        $CompanyID = $this->session->CompanyID;
        $data = array("Deleted" => 0);
        // attachment
        $Type = array(
            'selling','delivery','return_sales','invoice_ar','payment_ar','ap_correction',
            'purchase','penerimaan','retur','invoice_ap','ap_correction','payment_ap','inventory_goodreceipt',
        );
        $attachment = $this->api->attachment_list($Type,"","array");
        foreach ($attachment as $v) {
            $this->main->delete_file($v->Image);
        }
        $this->db->where("CompanyID", $CompanyID);
        $this->db->where_in("Type", $Type);
        $this->db->delete("PS_Attachment");

        // payment
        $this->db->update("PS_Payment_Detail",$data,array("CompanyID" => $CompanyID));
        $this->db->delete("PS_Payment_Detail", array("CompanyID" => $CompanyID));
        $this->db->delete("PS_Payment", array("CompanyID" => $CompanyID));

        // correction
        $this->db->update("AC_BalancePayable_Det",$data,array("CompanyID" => $CompanyID));
        $this->db->delete("AC_BalancePayable_Det", array("CompanyID" => $CompanyID));
        $this->db->delete("AC_BalancePayable", array("CompanyID" => $CompanyID));

        // invoice
        $this->db->update("PS_Invoice_Detail",$data,array("CompanyID" => $CompanyID));
        $this->db->delete("PS_Invoice_Detail", array("CompanyID" => $CompanyID));
        $this->db->delete("PS_Invoice", array("CompanyID" => $CompanyID));

        // return
        $this->db->update("AP_Retur_Det",$data,array("CompanyID" => $CompanyID));
        $this->db->delete("AP_Retur_Det_SN", array("CompanyID" => $CompanyID));
        $this->db->delete("AP_Retur_Det", array("CompanyID" => $CompanyID));
        $this->db->delete("AP_Retur", array("CompanyID" => $CompanyID));

        // delivery
        $this->db->update("PS_Delivery_Det",$data,array("CompanyID" => $CompanyID));
        $this->db->delete("PS_Delivery_Det_SN", array("CompanyID" => $CompanyID));
        $this->db->delete("PS_Delivery_Det", array("CompanyID" => $CompanyID));
        $this->db->delete("PS_Delivery", array("CompanyID" => $CompanyID));

        // good receipt
        $this->db->update("AP_GoodReceipt_Det",$data,array("CompanyID" => $CompanyID));
        $this->db->delete("AP_GoodReceipt_Det_SN", array("CompanyID" => $CompanyID));
        $this->db->delete("AP_GoodReceipt_Det", array("CompanyID" => $CompanyID));
        $this->db->delete("AP_GoodReceipt", array("CompanyID" => $CompanyID));

        // selling
        $this->db->update("PS_Sell_Detail",$data,array("CompanyID" => $CompanyID));
        $this->db->delete("PS_Sell_Detail_SN", array("CompanyID" => $CompanyID));
        $this->db->delete("PS_Sell_Detail", array("CompanyID" => $CompanyID));
        $this->db->delete("PS_Sell", array("CompanyID" => $CompanyID));

        // purchase
        $this->db->update("PS_Purchase_Detail",$data,array("CompanyID" => $CompanyID));
        $this->db->delete("PS_Purchase_Detail", array("CompanyID" => $CompanyID));
        $this->db->delete("PS_Purchase", array("CompanyID" => $CompanyID));

        // correction stock
        $this->db->update("AC_CorrectionPR_Det",$data,array("CompanyID" => $CompanyID));
        $this->db->delete("AC_CorrectionPR_Det", array("CompanyID" => $CompanyID));
        $this->db->delete("AC_CorrectionPR", array("CompanyID" => $CompanyID));

        // correction stock
        $this->db->update("PS_Correction_Detail",$data,array("CompanyID" => $CompanyID));
        $this->db->delete("PS_Correction_Detail_SN", array("CompanyID" => $CompanyID));
        $this->db->delete("PS_Correction_Detail", array("CompanyID" => $CompanyID));
        $this->db->delete("PS_Correction", array("CompanyID" => $CompanyID));

        // mutation
        $this->db->delete("PS_Mutation_Detail", array("CompanyID" => $CompanyID));
        $this->db->delete("PS_Mutation_Detail_SN", array("CompanyID" => $CompanyID));
        $this->db->delete("PS_Mutation", array("CompanyID" => $CompanyID));

        // journal
        $this->db->delete("AC_KasBank_Det", array("CompanyID" => $CompanyID));
        $this->db->delete("AC_KasBank", array("CompanyID" => $CompanyID));

        // update master product
        $data = array(
            "Qty"           => 0,
            "AveragePrice"  => 0,
            "PurchasePrice" => 0,
        );
        $this->db->where("CompanyID", $CompanyID);
        $this->db->update("ps_product", $data);

        // update product branch
        $this->db->where("CompanyID", $CompanyID);
        $this->db->update("PS_Product_Branch", $data);

        // delete sn 
        $this->db->delete("PS_Product_Serial", array("CompanyID" => $CompanyID));

    }

    #20190805 MW
    #delete master
    #master = item product, category product, business pather, sales & employe, Store & devices,
    public function master_delete(){
        $CompanyID = $this->session->CompanyID;

        // attachment
        $Type = array(
            'product',
        );
        $attachment = $this->api->attachment_list($Type,"","array");
        foreach ($attachment as $v) {
            $this->main->delete_file($v->Image);
        }
        $this->db->where("CompanyID", $CompanyID);
        $this->db->where_in("Type", $Type);
        $this->db->delete("PS_Attachment");

        // sales & employee
        $this->db->delete("PS_Sales", array("CompanyID" => $CompanyID));
        
        // business pather / vendor
        $this->db->delete("ps_vendor_contact", array("CompanyID" => $CompanyID));
        $this->db->delete("ps_vendor_address", array("CompanyID" => $CompanyID));
        $this->db->delete("PS_Vendor", array("CompanyID" => $CompanyID));

        // Store & devices / Branch
        $this->db->delete("Branch", array("CompanyID" => $CompanyID, "Index" => null));
        $this->db->delete("PS_Product_Branch", array("CompanyID" => $CompanyID));

        // item product & category product & unit
        $this->db->delete("ps_product_unit", array("CompanyID" => $CompanyID));
        $this->db->delete("ps_unit", array("CompanyID" => $CompanyID));
        $this->db->delete("ps_product_customer", array("CompanyID" => $CompanyID));
        $this->db->delete("PS_Product_Serial", array("CompanyID" => $CompanyID));
        $this->db->delete("ps_product", array("CompanyID" => $CompanyID));


    }
}