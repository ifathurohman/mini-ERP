<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_return_sales extends CI_Model {
    
    var $table = "AP_Retur";
    var $column = array(
        'AP_Retur.ReturNo',
        'AP_Retur.ReturNo',
        'AP_Retur.Date',
        'vendor.Name',
        'AP_Retur.SellNo',
        'AP_Retur.DeliveryNo',
        'AP_Retur.ReturNo',
        'AP_Retur.ReturType',
        'AP_Retur.Status',
    );
    var $order  = array('AP_Retur.ReturNo' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set("Asia/Jakarta");
    }

    private function _get_datatables_query($page="")
    {   
        $table = $this->table;

        $invoice = "(select count(dt.ReturNo) from PS_Invoice_Detail dt left join PS_Invoice mt 
            on mt.CompanyID = dt.CompanyID and mt.InvoiceNo = dt.InvoiceNo
            where dt.ReturNo = $table.ReturNo and dt.CompanyID = $table.CompanyID and mt.Status = '1'
            )";

        $this->db->select("
            $table.ReturNo,    
            $table.Date,    
            $table.SellNo,
            $table.Status,
            $table.Type,
            $table.ReturType,
            $table.VendorID,
            sum(returndet.Qty)      as Qty,
            sum(returndet.Total)    as Total,

            (case
                when $table.ReturType = 3 then $table.SellNo
                when $table.ReturType = 4 then $table.DeliveryNo
                else ''
            end) as transactionCode,

            vendor.Name as vendorName,

            ifnull($invoice,0) as ck_invoice,
        ");
        $this->db->join("AP_Retur_Det as returndet", "returndet.ReturNo = $table.ReturNo and returndet.CompanyID = $table.CompanyID", "left");
        $this->db->join("PS_Vendor as vendor", "$table.VendorID = vendor.VendorID", "left");
        $this->db->from($this->table);
        $this->db->where("$table.CompanyID",$this->session->CompanyID);
        $this->db->where("$table.BranchID",null);
        $this->db->where_in("$table.Type", array(2));
        $this->db->where_in("$table.ReturType", array(3,4));

        $this->db->group_by("$table.ReturNo,$table.Date,$table.SellNo,$table.DeliveryNo,$table.Status,$table.Type,$table.ReturType,vendor.Name");

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
        $this->db->where("AP_Retur.CompanyID",$this->session->CompanyID);
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function count_all($page="")
    {
        $this->db->where("AP_Retur.CompanyID",$this->session->CompanyID);
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
        $this->db->insert("AP_Retur_Det", $data);
        return $this->db->insert_id();  
    }
    public function update_det($where, $data)
    {
        $this->db->set("User_Ch",$this->session->NAMA);
        $this->db->set("Date_Ch",date("Y-m-d H:i:s"));
        $this->db->update("AP_Retur_Det", $data, $where);
        return $this->db->affected_rows();
    }

    public function get_by_id($id,$tambahan=""){
        $page       = $this->input->post('page');
        $CompanyID = $this->session->CompanyID;
        $table = $this->table;
        $this->db->select("
            $table.ReturNo,
            $table.VendorID,
            $table.SalesID,
            $table.SellNo,
            $table.DeliveryNo,
            $table.Date,
            $table.Type,
            $table.ReturType,
            $table.Remark,
            $table.Status,
            $table.InvoiceStatus,

            (case
                when $table.ReturType = 3 then $table.SellNo
                else $table.DeliveryNo
            end) as transactionCode,

            (case
                when $table.ReturType = 3 then sell.Date
                else delivery.Date
            end) as transactionDate,

            ifnull($table.Tax,0) as transactionTax,

            (case
                when $table.ReturType = 3 then 'Selling No'
                else 'Delivery No'
            end) as codetxt,

            (case
                when $table.ReturType = 3 then 'Selling Date'
                else 'Delivery Date'
            end) as datetxt,

            vendor.Name     as vendorName,
            vendor.Address  as vendorAddress,
            vendor.Phone    as vendorPhone,
            ifnull(vendor.AP_Max,0) as vendorTerm,
            vendor.NPWP     as vendorNPWP,
            sales.Name      as salesName,
        ");
        $this->db->join("PS_Sell        as sell", "sell.SellNo = $table.SellNo and sell.CompanyID = $table.CompanyID", "left");
        $this->db->join("PS_Delivery    as delivery", "delivery.DeliveryNo = $table.DeliveryNo and delivery.CompanyID = $table.CompanyID", "left");
        $this->db->join("PS_Vendor      as vendor", "$table.VendorID = vendor.VendorID", "left");
        $this->db->join("PS_Sales       as sales", "$table.SalesID = sales.SalesID", "left");
        $this->db->join("Branch", "sell.BranchID = Branch.BranchID and sell.CompanyID = Branch.CompanyID", "left");
        $this->db->where("$table.CompanyID", $CompanyID);
        $this->db->where("$table.ReturNo", $id);
        $this->db->where("$table.Type", 2);

        if($tambahan == "edit"):
            $this->db->select("
                ifnull((select count(dt.ReturNo) from PS_Invoice_Detail dt join PS_Invoice mt on dt.InvoiceNo = mt.InvoiceNo and dt.CompanyID = mt.CompanyID where dt.ReturNo = $table.ReturNo and dt.CompanyID = $table.CompanyID and mt.Status = '1'), 0) as InvoiceCount,
            ");
        endif;

        if($page == "invoice_selling"):
            $this->main->join_vendor_address("invoice");
        endif;

        $query = $this->db->get($table);

        return $query->row(); 
    }

    public function get_by_detail($id,$returntype=""){
        $CompanyID  = $this->session->CompanyID;
        $table      = "AP_Retur_Det";
        if($returntype == 3 || $returntype == 4):
            $this->db->select("
                (case
                    when $returntype = 3 then $table.SellNo
                    else $table.DeliveryNo
                end) as transactionCode,

                (case
                    when $returntype = 3 then $table.SellDet
                    else $table.DeliveryDet
                end) as transactionDet,
            ");
        endif;
        $this->db->select("
            $table.ReturDet,
            $table.ReturNo,
            $table.SellNo,
            $table.SellDet,
            $table.DeliveryNo,
            $table.DeliveryDet,
            $table.ProductID,
            $table.Qty,
            $table.Uom as UnitID,
            $table.Conversion,
            $table.Price,
            $table.Total,
            $table.Type,
            $table.Remark,
            $table.BranchID,

            detail.Qty                  as transactionQty,
            $table.DiscountPersent      as transactionDiscount,

            product.Code as product_code,
            product.Name as product_name,
            product.Type as product_type,
            ifnull(unit.Uom,'') as unit_name,
            ifnull(Branch.Name,'') as branchName,
        ");
        if($returntype == 3):
            $this->db->select("ifnull(PS_Sell.Module,'') as Module,");
            $this->db->join("PS_Sell_Detail as detail", "detail.SellDet = $table.SellDet and detail.CompanyID = $table.CompanyID", "left");
            $this->db->join("PS_Sell", "detail.SellNo = PS_Sell.SellNo and detail.CompanyID = PS_Sell.CompanyID", "left");
        elseif($returntype == 4):
            $this->db->select("
                (case 
                    when delivery.Type = 1 then (select ifnull(PS_Sell.Module,'') from PS_Sell where SellNo = detail.SellNo and CompanyID = detail.CompanyID)
                    else ifnull(delivery.Module,'')
                end) as Module,
            ");
            $this->db->join("PS_Delivery_Det as detail", "detail.DeliveryDet = $table.DeliveryDet and detail.CompanyID = $table.CompanyID", "left");
            $this->db->join("PS_Delivery as delivery", "delivery.DeliveryNo = detail.DeliveryNo and detail.CompanyID = delivery.CompanyID", "left");
        endif;
        // $this->db->join("ps_unit as unit", "unit.UnitID = $table.UnitID", "left");
        $this->db->join("Branch", "$table.BranchID = Branch.BranchID and $table.CompanyID = Branch.CompanyID", "left");
        $this->db->join("ps_product_unit as unit", "unit.ProductUnitID = $table.Uom", "left");
        $this->db->join("ps_product as product", "product.ProductID = $table.ProductID", "left");
        $this->db->where("$table.CompanyID", $CompanyID);
        $this->db->where("$table.ReturNo", $id);
        $query = $this->db->get($table);

        return $query->result();
    }

    public function serial_number($id,$det,$ProductID){
        $CompanyID = $this->session->CompanyID;
        $this->db->select("
            SN,
        ");
        $this->db->where("CompanyID", $CompanyID);
        $this->db->where("ReturNo", $id);
        $this->db->where("ReturDet", $det);
        $this->db->where("ProductID", $ProductID);
        $query = $this->db->get("AP_Retur_Det_SN");

        return $query->result();
    }

    public function serial_number_list($mt,$dt){
        $CompanyID  = $this->session->CompanyID;
        $table      = "AP_Retur_Det_SN";

        $this->db->select("
            SN,
            product.Name as product_name,
            'Serial'     as product_type,
            dt.Qty,
        ");

        $this->db->join("ps_product    as product", "product.ProductID = $table.ProductID", "left");
        $this->db->join("AP_Retur_Det  as dt", "dt.ReturNo = $table.ReturNo and dt.ReturDet = $table.ReturDet and dt.CompanyID = $table.CompanyID");

        $this->db->where("$table.CompanyID", $CompanyID);
        $this->db->where("$table.ReturNo", $mt);
        $this->db->where("$table.ReturDet", $dt);
        $query = $this->db->get($table);

        return $query->result();
    }
}