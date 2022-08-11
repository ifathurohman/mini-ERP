<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_selling extends CI_Model {
    
    var $table = "PS_Sell";
    var $column = array(
        'PS_Sell.SellNo',
        'PS_Sell.SellNo',
        'PS_Sell.Date',
        'customer.Name',
        'Branch.Name',
        'PS_Sell.SellNo',
        'PS_Sell.Payment',
        'PS_Sell.Payment',
    );
    var $order  = array('PS_Sell.SellNo' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set("Asia/Jakarta");
    }

    private function _get_datatables_query($page="")
    {
        $CompanyID = $this->session->CompanyID;
        $this->db->select("
            PS_Sell.SellNo,
            PS_Sell.BranchID,
            PS_Sell.Date,
            PS_Sell.Payment,
            PS_Sell.Status,
            PS_Sell.Paid,
            PS_Sell.DeliveryStatus,
            PS_Sell.DeliveryParameter,
            PS_Sell.ProductType,
            PS_Sell.VendorID,
            PS_Sell.BranchID,

            sum(PS_Sell_Detail.Qty) as Qty,

            customer.Name as customerName,
            ifnull(Branch.Name,'') as branchName,
            ");
        $this->db->join("PS_Sell_Detail", "PS_Sell.SellNo = PS_Sell_Detail.SellNo and PS_Sell.CompanyID = PS_Sell_Detail.CompanyID", "left");
        $this->db->join("PS_Vendor      as customer", "PS_Sell.VendorID = customer.VendorID", "left");
        $this->db->join("Branch", "PS_Sell.BranchID = Branch.BranchID and PS_Sell.CompanyID = Branch.CompanyID", "left");
        $this->db->from($this->table);
        $this->db->where("PS_Sell.CompanyID",$CompanyID);
        $this->db->where("PS_Sell.Mobile", 0);
        $this->db->group_by("PS_Sell.SellNo,Branch.Name");
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
            $this->db->where("PS_Sell.Date >=", $StartDate);
        endif;
        if($this->input->post("EndDate")):
            $EndDate = $this->input->post("EndDate");
            $this->db->where("PS_Sell.Date <=", $EndDate." 23:59:59");
        endif;
        if($this->input->post("Status") != "none"):
            $Status = $this->input->post("Status");
            $this->db->where("PS_Sell.Status", $Status);
        endif;
        if($this->input->post("ProductType") != "none"):
            $ProductType = $this->input->post("ProductType");
            $this->db->where("PS_Sell.ProductType", $ProductType);
        endif;
        $Branch = $this->input->post('Branch');
        if($Branch != "all" && $Branch):
            $this->db->where("PS_Sell.BranchID", $Branch);
        endif;
        if($this->input->post("TransactionStatus") != "none"):
            $TransactionStatus = $this->input->post("TransactionStatus");
            // status open
            if($TransactionStatus == 0):
                $this->db->group_start();
                $this->db->where("(select count(dt.DeliveryNo) from PS_Delivery_Det as dt left join PS_Delivery as d 
                    on dt.DeliveryNo = d.DeliveryNo and dt.CompanyID = d.CompanyID
                    where dt.CompanyID = '$CompanyID' and dt.SellNo = PS_Sell.SellNo and d.Status = '1') <=", 0);
                $this->db->where("(select count(idt.InvoiceNo) from PS_Invoice_Detail as idt left 
                    join PS_Invoice as id 
                    on idt.InvoiceNo = id.InvoiceNo and idt.CompanyID = id.CompanyID
                    where idt.CompanyID = '$CompanyID' and idt.SellNo = PS_Sell.SellNo and id.Status = '1') <=", 0);
                $this->db->group_end();
            // status delivery
            elseif($TransactionStatus == 1):
                $this->db->where("(select count(ps_dd.DeliveryNo) from PS_Delivery_Det as ps_dd 
                    left join PS_Delivery as ps_d 
                    on ps_d.DeliveryNo = ps_dd.DeliveryNo and ps_d.CompanyID = ps_dd.CompanyID 
                    where ps_dd.CompanyID = '$CompanyID' and ps_dd.SellNo = PS_Sell.SellNo and ps_d.Status = '1') >", 0);
                $this->db->where("(select count(ps_id.InvoiceNo) from PS_Invoice_Detail ps_id
                    left join PS_Invoice as ps_i on ps_i.InvoiceNo = ps_id.InvoiceNo and ps_i.CompanyID =  ps_id.CompanyID
                    left join PS_Delivery as ps_d on ps_d.DeliveryNo = ps_id.DeliveryNo and ps_d.CompanyID = ps_id.CompanyID
                    left join PS_Delivery_Det as ps_dd on ps_dd.DeliveryNo = ps_d.DeliveryNo and ps_dd.CompanyID = ps_d.CompanyID
                    where ps_id.CompanyID = '$CompanyID' and ps_dd.SellNo = PS_Sell.SellNo and ps_i.Status = '1') <=",0);
                $this->db->where("(select count(ps_id.InvoiceNo) from PS_Invoice_Detail ps_id
                    left join PS_Invoice as ps_i on ps_i.InvoiceNo = ps_id.InvoiceNo and ps_i.CompanyID = ps_id.CompanyID
                    where ps_id.CompanyID = '$CompanyID' and ps_id.SellNo = PS_Sell.SellNo and ps_i.Status = '1') <=",0);
            // status unpaid
            elseif($TransactionStatus == 2):
                $this->db->group_start();
                // invoice dari delivery
                $this->db->where("(select count(ivd.InvoiceDet) from PS_Invoice_Detail as ivd 
                    left join PS_Invoice as ps_i on ivd.InvoiceNo = ps_i.InvoiceNo and ivd.CompanyID = ps_i.CompanyID
                    left join PS_Delivery as del on ivd.DeliveryNo = del.DeliveryNo and ivd.CompanyID = del.CompanyID
                    where ivd.CompanyID = '$CompanyID' and del.SellNo = PS_Sell.SellNo and ps_i.Status = '1') >", 0);
                $this->db->where("(select count(ps_pay_det.PaymentNo) from PS_Payment_Detail as ps_pay_det
                    left join PS_Payment as ps_pay on ps_pay.PaymentNo = ps_pay_det.PaymentNo and ps_pay.CompanyID = ps_pay_det.CompanyID
                    left join PS_Invoice_Detail as ps_id on ps_id.InvoiceNo =  ps_pay_det.InvoiceNo and ps_id.CompanyID = ps_pay_det.CompanyID
                    left join PS_Delivery_Det as ps_dd on ps_dd.DeliveryNo = ps_id.DeliveryNo ps_dd.CompanyID = ps_id.CompanyID
                    where ps_pay_det.CompanyID = '$CompanyID' and ps_pay.Status = '1' and ps_dd.SellNo = PS_Sell.SellNo) <=",0);
                // invoice dari selling
                $this->db->or_where("(select count(InvoiceDet) from PS_Invoice_Detail as ps_id
                    left join PS_Invoice as ps_i on ps_id.InvoiceNo = ps_i.InvoiceNo and ps_id.CompanyID = ps_i.CompanyID
                    where ps_id.SellNo = PS_Sell.SellNo and ps_id.CompanyID = '$CompanyID' and ps_i.Status = '1') >", 0);
                $this->db->where("(select count(ps_pay_det.PaymentNo) from PS_Payment_Detail as ps_pay_det
                    left join PS_Payment as ps_pay on ps_pay.PaymentNo = ps_pay_det.PaymentNo and ps_pay.CompanyID = ps_pay_det.CompanyID
                    left join PS_Invoice_Detail as ps_id on ps_id.InvoiceNo =  ps_pay_det.InvoiceNo and ps_id.CompanyID = ps_pay_det.CompanyID
                    where ps_pay_det.CompanyID = '$CompanyID' and ps_pay.Status = '1' and ps_id.SellNo = PS_Sell.SellNo) <=",0);
                $this->db->group_end();
            // status paid
            elseif($TransactionStatus == 3):
                $this->db->group_start();
                // invoice dari selling
                $this->db->where("(select count(ps_pay_det.PaymentNo) from PS_Payment_Detail as ps_pay_det
                    left join PS_Payment as ps_pay on ps_pay.PaymentNo = ps_pay_det.PaymentNo and ps_pay.CompanyID = ps_pay_det.CompanyID
                    left join PS_Invoice_Detail as ps_id on ps_id.InvoiceNo =  ps_pay_det.InvoiceNo and ps_id.CompanyID = ps_pay_det.CompanyID
                    where ps_pay_det.CompanyID = '$CompanyID' and ps_pay.Status = '1' and ps_id.SellNo = PS_Sell.SellNo) >",0);
                // invoice dari delivery
                $this->db->or_where("(select count(ps_pay_det.PaymentNo) from PS_Payment_Detail as ps_pay_det
                    left join PS_Payment as ps_pay on ps_pay.PaymentNo = ps_pay_det.PaymentNo and ps_pay.CompanyID = ps_pay_det.CompanyID
                    left join PS_Invoice_Detail as ps_id on ps_id.InvoiceNo =  ps_pay_det.InvoiceNo and ps_id.CompanyID = ps_pay_det.CompanyID
                    left join PS_Delivery_Det as ps_dd on ps_dd.DeliveryNo = ps_id.DeliveryNo and ps_dd.CompanyID = ps_id.CompanyID
                    where ps_pay_det.CompanyID = '$CompanyID' and ps_pay.Status = '1' and ps_dd.SellNo = PS_Sell.SellNo) >",0);
                $this->db->group_end();
            endif;
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
        $this->db->where("PS_Sell.CompanyID",$this->session->CompanyID);
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function count_all($page="")
    {
        $this->db->where("PS_Sell.CompanyID",$this->session->CompanyID);
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
        $this->db->insert("PS_Sell_Detail", $data);
        return $this->db->insert_id();  
    }
    public function update_det($where, $data)
    {
        $this->db->set("User_Ch",$this->session->NAMA);
        $this->db->set("Date_Ch",date("Y-m-d H:i:s"));
        $this->db->update("PS_Sell_Detail", $data, $where);
        return $this->db->affected_rows();
    }
    public function save_invoice($data)
    {
        $this->db->set("User_Add",$this->session->NAMA);
        $this->db->set("Date_Add",date("Y-m-d H:i:s"));
        $this->db->insert("PS_Invoice", $data);
        return $this->db->insert_id();  
    }
    public function update_invoice($where, $data)
    {
        $this->db->set("User_Ch",$this->session->NAMA);
        $this->db->set("Date_Ch",date("Y-m-d H:i:s"));
        $this->db->update("PS_Invoice", $data, $where);
        return $this->db->affected_rows();
    }

    public function save_delivery($data)
    {
        $this->db->set("User_Add",$this->session->NAMA);
        $this->db->set("Date_Add",date("Y-m-d H:i:s"));
        $this->db->insert("PS_Delivery", $data);
        return $this->db->insert_id();
    }
    public function update_delivery($where, $data)
    {
        $this->db->set("User_Ch",$this->session->NAMA);
        $this->db->set("Date_Ch",date("Y-m-d H:i:s"));
        $this->db->update("PS_Delivery", $data, $where);
        return $this->db->affected_rows();
    }

    public function save_delivery_det($data)
    {
        $this->db->set("User_Add",$this->session->NAMA);
        $this->db->set("Date_Add",date("Y-m-d H:i:s"));
        $this->db->insert("PS_Delivery_Det", $data);
        return $this->db->insert_id();
    }
    public function update_delivery_det($where, $data)
    {
        $this->db->set("User_Ch",$this->session->NAMA);
        $this->db->set("Date_Ch",date("Y-m-d H:i:s"));
        $this->db->update("PS_Delivery_Det", $data, $where);
        return $this->db->affected_rows();
    }

    public function get_by_id($id,$tambahan=""){
        $page       = $this->input->post('page');
        $CompanyID  = $this->session->CompanyID;
        $this->db->select("
            PS_Sell.VendorID,
            PS_Sell.SalesID,
            PS_Sell.SellNo,
            PS_Sell.CompanyID,
            PS_Sell.BranchID,
            PS_Sell.Total,
            PS_Sell.Payment,
            PS_Sell.PPN,
            PS_Sell.TotalPPN,
            PS_Sell.Discount,
            PS_Sell.DiscountPersent,
            PS_Sell.Date,
            PS_Sell.Remark,
            PS_Sell.Tax,
            PS_Sell.NoPOKonsumen,
            PS_Sell.DeliveryTo,
            PS_Sell.DeliveryAddress,
            PS_Sell.DeliveryCity,
            PS_Sell.DeliveryProvince,
            PS_Sell.DeliveryParameter,
            ifnull(PS_Sell.DeliveryDate,'') as DeliveryDate,
            IFNULL(PS_Sell.DeliveryCost, 0) as DeliveryCost,
            PS_Sell.PaymentTo,
            PS_Sell.PaymentAddress,
            PS_Sell.PaymentCity,
            PS_Sell.PaymentProvince,
            PS_Sell.Term,
            PS_Sell.Module,
            PS_Sell.ProductType,
            PS_Sell.Status,
            PS_Sell.DeliveryStatus,
            PS_Sell.InvoiceStatus,

            vendor.Name   as customerName,
            vendor.Phone  as customerPhone,
            vendor.NPWP   as vendorNPWP,
            ifnull(vendor.AP_Max,0) as vendorTerm,
            ifnull(vendor.productcustomer,'') as productcustomer,
            sales.Name                  as salesName,
            ifnull(Branch.Name,'')      as branchName,

        ");
        $this->db->join("PS_Vendor      as vendor", "PS_Sell.VendorID = vendor.VendorID", "left");
        $this->db->join("PS_Sales       as sales", "PS_Sell.SalesID = sales.SalesID", "left");
        $this->db->join("Branch", "PS_Sell.BranchID = Branch.BranchID and PS_Sell.CompanyID = Branch.CompanyID", "left");

        if($tambahan == "edit"):
            $this->db->select("
                ifnull((select count(dt.SellNo) from PS_Delivery_Det dt join PS_Delivery mt on dt.DeliveryNo = mt.DeliveryNo and dt.CompanyID = mt.CompanyID where dt.SellNo = PS_Sell.SellNo and dt.CompanyID = PS_Sell.CompanyID and mt.Status = '1'), 0) as DeliveryCount,
                ifnull((select count(dt.SellNo) from PS_Invoice_Detail dt join PS_Invoice mt on dt.InvoiceNo = mt.InvoiceNo and dt.CompanyID = mt.CompanyID where dt.SellNo = PS_Sell.SellNo and dt.CompanyID = PS_Sell.CompanyID and mt.Status = '1'), 0) as InvoiceCount,
                ifnull((select count(dt.SellNo) from AP_Retur_Det dt join AP_Retur mt on dt.ReturNo = mt.ReturNo and dt.CompanyID = mt.CompanyID where dt.SellNo = PS_Sell.SellNo and dt.CompanyID = PS_Sell.CompanyID and mt.Status = '1'), 0) as ReturnCount,
            ");
        endif;

        if($page == "delivery"):
            $this->db->where("PS_Sell.InvoiceStatus", 0);
        elseif($page == "invoice_selling"):
            $this->main->join_vendor_address("invoice");
        endif;

        $this->db->where("PS_Sell.SellNo",$id);
        $this->db->where("PS_Sell.CompanyID", $CompanyID);
        $this->db->from($this->table);
        $query = $this->db->get();

        return $query->row();
    }

    public function get_by_detail($id){
        $page = $this->input->post('page');
        $CompanyID = $this->session->CompanyID;
        $this->db->select("
            ps_sd.SellNo,
            ps_sd.SellDet,
            ps_sd.ProductID,
            ps_sd.Uom as UnitID,
            ps_sd.Qty,
            ((select Qty from ps_product where ProductID = ps_sd.ProductID)+(select sum(Qty) from PS_Sell_Detail where ProductID = ps_sd.ProductID and SellNo = ps_sd.SellNo)) as product_qty,
            ps_product.Qty as stock_product,
            ps_sd.Conversion,
            ps_sd.Price,
            ps_sd.TotalPrice,
            ps_sd.Discount,
            ps_sd.DiscountValue,
            ps_product.Type,
            ifnull(ps_sd.DeliveryDate,'')   as product_delivery,
            IFNULL(ps_sd.Remark,'')         as Remark,
            ifnull(ps_sd.DeliveryQty, 0)    as DeliveryQty,
            ifnull(PS_Sell.DeliveryCost,0)  as DeliveryCost,
            ifnull(PS_Sell.Module,'')       as sellModule,
            ifnull(ps_sd.Cost,0)            as Cost,

            ps_product.Name as product_name,
            ps_product.Code as product_code,

            ifnull(unit.Uom, '') as unit_name,
            PS_Sell.ProductType,
            ifnull(Branch.Name,'')   as branchName,
            PS_Sell.BranchID,
        ");
        $this->db->join("PS_Sell","PS_Sell.SellNo = ps_sd.SellNo and PS_Sell.CompanyID = ps_sd.CompanyID", "left");
        $this->db->join("Branch", "PS_Sell.BranchID = Branch.BranchID and PS_Sell.CompanyID = Branch.CompanyID", "left");
        $this->db->join("ps_product", "ps_sd.ProductID = ps_product.ProductID", "left");
        // $this->db->join("ps_unit","ps_sd.UnitID = ps_unit.UnitID", "left");
        $this->db->join("ps_product_unit as unit", "unit.ProductUnitID = ps_sd.Uom", "left");
        $this->db->from("PS_Sell_Detail as ps_sd");
        $this->db->where("ps_sd.SellNo", $id);
        $this->db->where("ps_sd.CompanyID", $CompanyID);
        if($page == "delivery"):
            $this->db->where("PS_Sell.InvoiceStatus", 0);
        endif;
        $query = $this->db->get();

        return $query->result();
    }

    public function get_list_detail($id,$page = "")
    {
        $this->db->select("
            pssd.SellNo,
            pssd.SellDet,
            pssd.ProductID      as productid,
            pssd.Qty            as sell_qty,
            pssd.Conversion     as sell_konv,
            pssd.Price          as sell_price,
            pssd.Remark         as remark,
            pssd.UnitID         as unitid,
            pssd.Type           as type,
            ifnull(pssd.DeliveryDate,'')   as product_delivery,
            pssd.SerialNumber   as serialnumber,
            ps_product.Code     as product_code,
            ps_product.Name     as product_name,
            (CASE
            WHEN pssd.Type = 1 THEN 'unique'
            WHEN pssd.Type = 2 THEN 'serial'
            ELSE 'general' END) AS product_type,
            ps_unit.Name as unit_name,
        ");
        $this->db->join("ps_product","pssd.ProductID = ps_product.ProductID","left");
        $this->db->join("ps_unit","pssd.UnitID = ps_unit.UnitID","left");
        $this->db->where("pssd.CompanyID",$this->session->CompanyID);
        if($page == "add_serial"):
            $this->db->where("pssd.SellDet",$id);
        else:
            $this->db->where("pssd.SellNo",$id);
        endif;
        $query = $this->db->get("PS_Sell_Detail as pssd");
        if($page == "add_serial"):
            return $query->row();
        else:
            return $query->result();
        endif;
    }

    public function serial_number($id,$det,$ProductID){
        $CompanyID = $this->session->CompanyID;
        $this->db->select("
            SN,
        ");
        $this->db->where("CompanyID", $CompanyID);
        $this->db->where("SellNo", $id);
        $this->db->where("SellDet", $det);
        $this->db->where("ProductID", $ProductID);
        $query = $this->db->get("PS_Sell_Detail_SN");

        return $query->result();
    }

    public function serial_number_list($mt,$dt){
        $CompanyID  = $this->session->CompanyID;
        $table      = "PS_Sell_Detail_SN";

        $this->db->select("
            SN,
            product.Name as product_name,
            'Serial'     as product_type,
            dt.Qty,
        ");

        $this->db->join("ps_product       as product", "product.ProductID = $table.ProductID", "left");
        $this->db->join("PS_Sell_Detail   as dt", "dt.SellNo = $table.SellNo and dt.SellDet = $table.SellDet and dt.CompanyID = $table.CompanyID");

        $this->db->where("$table.CompanyID", $CompanyID);
        $this->db->where("$table.SellNo", $mt);
        $this->db->where("$table.SellDet", $dt);
        $query = $this->db->get($table);

        return $query->result();
    }
}