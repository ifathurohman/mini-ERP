<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_retur extends CI_Model {
    
    var $table = "AP_Retur";
    var $column = array(
        'AP_Retur.ReturNo',
        'AP_Retur.ReturNo',
        'AP_Retur.Date',
        'vendor.Name',
        'AP_Retur.ReturNo',
        'Branch.Name',
        'AP_Retur.Date',
        'AP_Retur.Status',
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
            $table.PurchaseNo,
            $table.Status,
            $table.Type,
            $table.ReturType,
            $table.VendorID,
            sum(returndet.Qty)      as Qty,
            sum(returndet.Total)    as Total,

            (case
                when $table.ReturType = 1 then $table.PurchaseNo
                when $table.ReturType = 2 then $table.ReceiveNo
                else ''
            end) as transactionCode,

            vendor.Name as vendorName,

            ifnull($invoice,0)          as ck_invoice,
            ifnull(Branch.Name,'')      as branchName,
            receipt.BranchID,
        ");
        $this->db->join("AP_Retur_Det   as returndet", "returndet.ReturNo = $table.ReturNo and returndet.CompanyID = $table.CompanyID", "left");
        $this->db->join("AP_GoodReceipt as receipt", "receipt.ReceiveNo = $table.ReceiveNo and receipt.CompanyID = $table.CompanyID");
        $this->db->join("Branch", "receipt.BranchID = Branch.BranchID and receipt.CompanyID = Branch.CompanyID", "left");
        $this->db->join("PS_Vendor      as vendor", "$table.VendorID = vendor.VendorID", "left");
        $this->db->from($this->table);
        $this->db->where("$table.CompanyID",$this->session->CompanyID);
        $this->db->where_in("$table.Type", array(1));
        $this->db->where_in("$table.ReturType", array(1,2));

        $this->db->group_by("$table.ReturNo");

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
        $Branch  = $this->input->post("Branch");
        if($Branch != "all" && $Branch):
            $this->db->where("receipt.BranchID", $Branch);
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
            $table.PurchaseNo,
            $table.ReceiveNo,
            $table.Date,
            $table.Type,
            $table.ReturType,
            $table.Remark,
            $table.InvoiceStatus,
            $table.Status,

            (case
                when $table.ReturType = 1 then $table.PurchaseNo
                else $table.ReceiveNo
            end) as transactionCode,

            (case
                when $table.ReturType = 1 then purchase.Date
                else receive.Date
            end) as transactionDate,

            (case
                when $table.ReturType = 1 then purchase.Tax
                else receive.Tax
            end) as transactionTax,

            (case
                when $table.ReturType = 1 then 'purchase Code'
                else 'receive Code'
            end) as codetxt,

            (case
                when $table.ReturType = 1 then 'purchase Date'
                else 'receive Date'
            end) as datetxt,

            vendor.Position,
            vendor.Name     as vendorName,
            vendor.Phone    as vendorPhone,

            ifnull(address1.Address,'')     as address,
            gdpurchase.DeliveryCity         as city,
            gdpurchase.DeliveryProvince     as province,
            ifnull(vendor.AP_Max,0)         as vendorTerm,
            vendor.NPWP                     as vendorNPWP,

            sales.Name      as salesName,
            receive.BranchID,
            ifnull(Branch.Name,'') as branchName,
        ");
        $this->db->join("PS_Purchase        as purchase", "purchase.PurchaseNo = $table.PurchaseNo and purchase.CompanyID = $table.CompanyID", "left");
        $this->db->join("AP_GoodReceipt     as receive", "receive.ReceiveNo = $table.ReceiveNo and receive.CompanyID = $table.CompanyID", "left");
        $this->db->join("PS_Purchase        as gdpurchase", "gdpurchase.PurchaseNo = receive.PurchaseNo and gdpurchase.CompanyID = receive.CompanyID", "left");
        $this->db->join("Branch", "receive.BranchID = Branch.BranchID and receive.CompanyID = Branch.CompanyID", "left");
        $this->db->join("PS_Vendor          as vendor", "$table.VendorID = vendor.VendorID", "left");
        $this->db->join("ps_vendor_address address1", "address1.VendorCode = vendor.Code and address1.Delivery = '1'","left");
        $this->db->join("PS_Sales           as sales", "$table.SalesID = sales.SalesID", "left");
        $this->db->where("$table.CompanyID", $CompanyID);
        $this->db->where("$table.ReturNo", $id);
        $this->db->where("vendor.Position", 1);
        $this->db->where("$table.Type", 1);

        if($tambahan == "edit"):
            $this->db->select("
                ifnull((select count(dt.ReturNo) from PS_Invoice_Detail dt join PS_Invoice mt on dt.InvoiceNo = mt.InvoiceNo and dt.CompanyID = mt.CompanyID where dt.ReturNo = $table.ReturNo and dt.CompanyID = $table.CompanyID and mt.Status = '1'), 0) as InvoiceCount,
            ");
        endif;

        if($page == "invoice_return"):
            $this->main->join_vendor_address("invoice");
        endif;

        $query = $this->db->get($table);

        return $query->row(); 
    }

    public function get_by_detail($id,$returntype=""){
        $CompanyID  = $this->session->CompanyID;
        $table      = "AP_Retur_Det";
        if($returntype == 1 || $returntype == 2):
            $this->db->select("
                (case
                    when $returntype = 1 then $table.PurchaseNo
                    else $table.ReceiveNo
                end) as transactionCode,

                (case
                    when $returntype = 1 then $table.PurchaseDet
                    else $table.ReceiveDet
                end) as transactionDet,
            ");
        endif;
        $this->db->select("
            $table.ReturDet,
            $table.ReturNo,
            $table.PurchaseNo,
            $table.PurchaseDet,
            $table.ReceiveNo,
            $table.ReceiveDet,
            $table.ProductID,
            $table.Qty,
            $table.Uom as UnitID,
            $table.Conversion,
            $table.Price,
            $table.Total,
            $table.Type,
            $table.Remark,

            detail.Qty          as transactionQty,
            detail.Discount     as transactionDiscount,

            product.Code as product_code,
            product.Name as product_name,
            product.Type as product_type,
            ifnull(unit.Uom,'')    as unit_name,
            receive.Module,
        ");
        if($returntype == 1):
            $this->db->join("PS_Purchase_Detail as detail", "$table.PurchaseDet = detail.PurchaseDet and $table.CompanyID = detail.CompanyID", "left");
        elseif($returntype == 2):
            $this->db->join("AP_GoodReceipt_Det as detail", "$table.ReceiveDet = detail.ReceiveDet and $table.CompanyID = detail.CompanyID", "left");
            $this->db->join("AP_GoodReceipt as receive", "receive.ReceiveNo = detail.ReceiveNo and receive.CompanyID = detail.CompanyID", "left");
        endif;
        // $this->db->join("ps_unit as unit", "unit.UnitID = $table.UnitID", "left");
        $this->db->join("ps_product_unit as unit", "$table.Uom = unit.ProductUnitID", "left");
        $this->db->join("ps_product as product", "product.ProductID = $table.ProductID and product.CompanyID = $table.CompanyID", "left");
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

        $this->db->join("ps_product     as product", "product.ProductID = $table.ProductID", "left");
        $this->db->join("AP_Retur_Det   as dt", "dt.ReturNo = $table.ReturNo and dt.ReturDet = $table.ReturDet and dt.CompanyID = $table.CompanyID");

        $this->db->where("$table.CompanyID", $CompanyID);
        $this->db->where("$table.ReturNo", $mt);
        $this->db->where("$table.ReturDet", $dt);
        $query = $this->db->get($table);

        return $query->result();
    }
}