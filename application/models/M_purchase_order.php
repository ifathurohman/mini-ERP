<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_purchase_order extends CI_Model {
    
    var $table = "PS_Purchase";
    var $column = array(
        'PS_Purchase.PurchaseNo',
        'PS_Purchase.PurchaseNo',
        'PS_Purchase.Date',
        'vendor.Name',
        'Branch.Name',
        'PS_Purchase.Status',
        'PS_Purchase.Payment',
        'PS_Purchase.Payment',
    );
    var $order  = array('PS_Purchase.PurchaseNo' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set("Asia/Jakarta");
    }

    private function _get_datatables_query($page="")
    {
        $CompanyID = $this->session->CompanyID;
        $table = $this->table;

        $grd = "( select count(mt.PurchaseNo) from AP_GoodReceipt mt where mt.CompanyID = $table.CompanyID and mt.PurchaseNo = $table.PurchaseNo and mt.Status = '1')";
        $return = "( select count(mt.PurchaseNo) from AP_Retur mt where mt.CompanyID = $table.CompanyID and mt.PurchaseNo = $table.PurchaseNo and mt.Status = '1')";

        $this->db->select("
            PS_Purchase.PurchaseNo,
            PS_Purchase.Date,
            PS_Purchase.Payment,
            PS_Purchase.Status,
            PS_Purchase.DeliveryStatus,
            PS_Purchase.ProductType,
            PS_Purchase.VendorID,
            PS_Purchase.BranchID,

            sum(PS_Purchase_Detail.Qty) as Qty,

            vendor.Name as vendorName,

            ifnull(Branch.Name,'') as branchName,

            ifnull($grd,0) as ck_grd,
            ifnull($return,0) as ck_return,
            ");
        $this->db->join("PS_Purchase_Detail", "PS_Purchase.PurchaseNo = PS_Purchase_Detail.PurchaseNo and PS_Purchase.CompanyID = PS_Purchase_Detail.CompanyID", "left");
        $this->db->join("PS_Vendor as vendor", "PS_Purchase.VendorID = vendor.VendorID", "left");
        $this->db->join("Branch", "$table.BranchID = Branch.BranchID and $table.CompanyID = Branch.CompanyID", "left");
        $this->db->from($this->table);
        $this->db->where("PS_Purchase.CompanyID",$CompanyID);
        $this->db->group_by("
            PS_Purchase.PurchaseNo,
            Branch.Name
            ");
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
            $this->db->where("PS_Purchase.Date >=", $StartDate);
        endif;
        if($this->input->post("EndDate")):
            $EndDate = $this->input->post("EndDate");
            $this->db->where("PS_Purchase.Date <=", $EndDate." 23:59:59");
        endif;
        if($this->input->post("Status") != "none"):
            $Status = $this->input->post("Status");
            $this->db->where("PS_Purchase.Status", $Status);
        endif;
        if($this->input->post("ProductType") != "none"):
            $ProductType = $this->input->post("ProductType");
            $this->db->where("PS_Purchase.ProductType", $ProductType);
        endif;

        $Branch = $this->input->post("Branch");
        if($Branch != "all" && $Branch):
            $this->db->where("PS_Purchase.BranchID", $Branch);
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
        $this->db->where("PS_Purchase.CompanyID",$this->session->CompanyID);
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function count_all($page="")
    {
        $this->db->where("PS_Purchase.CompanyID",$this->session->CompanyID);
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
        $this->db->insert("PS_Purchase_Detail", $data);
        return $this->db->insert_id();  
    }
    public function update_det($where, $data)
    {
        $this->db->set("User_Ch",$this->session->NAMA);
        $this->db->set("Date_Ch",date("Y-m-d H:i:s"));
        $this->db->update("PS_Purchase_Detail", $data, $where);
        return $this->db->affected_rows();
    }

    public function save_serial($data)
    {
        $this->db->set("User_Add",$this->session->NAMA);
        $this->db->set("Date_Add",date("Y-m-d H:i:s"));
        $this->db->insert("PS_Purchase_Detail_SN", $data);
        return $this->db->insert_id();  
    }
    public function update_serial($where, $data)
    {
        $this->db->set("User_Ch",$this->session->NAMA);
        $this->db->set("Date_Ch",date("Y-m-d H:i:s"));
        $this->db->update("PS_Purchase_Detail_SN", $data, $where);
        return $this->db->affected_rows();
    }

    public function get_by_id($id,$tambahan=""){
        $table      = $this->table;
        $CompanyID  = $this->session->CompanyID;

        $this->db->select("
            $table.PurchaseNo,
            $table.CompanyID,
            $table.VendorID,
            $table.BranchID,
            $table.SalesID,
            $table.Date,
            $table.PaymentTerm,
            $table.Tax,
            $table.PPN,
            $table.TotalPPN,
            $table.Discount,
            $table.DiscountPersent,
            $table.Total,
            $table.Payment,
            $table.DeliveryAddress,
            $table.DeliveryCity,
            $table.DeliveryProvince,
            $table.DeliveryCost,
            $table.DeliveryStatus,
            $table.PaymentTo,
            $table.PaymentAddress,
            $table.PaymentCity,
            $table.PaymentProvince,
            $table.DeliveryParameter,
            $table.Remark,
            $table.Status,
            $table.ProductType,

            vendor.Name                 as vendorName,
            ifnull(address.Address,'')  as vendorAddress,
            vendor.Phone                as vendorPhone,
            ifnull(sales.Name,'')       as salesName,
            ifnull(Branch.Name,'')      as branchName,
        ");
        $this->db->join("PS_Vendor          as vendor", "$table.VendorID = vendor.VendorID", "left");
        $this->db->join("Branch", "$table.BranchID = Branch.BranchID and $table.CompanyID = Branch.CompanyID", "left");
        $this->db->join("ps_vendor_address  as address", "address.VendorCode = vendor.Code and address.Delivery = '1'","left");
        $this->db->join("PS_Sales           as sales", "$table.SalesID = sales.SalesID", "left");
        $this->db->where($table.".CompanyID", $CompanyID);
        $this->db->where($table.".PurchaseNo", $id);

        if($tambahan == "edit"):
            $this->db->select("
                ifnull((select count(dt.PurchaseNo) from AP_GoodReceipt_Det dt join AP_GoodReceipt mt on mt.ReceiveNo = dt.ReceiveNo and mt.CompanyID = dt.CompanyID where mt.Status = '1' and dt.CompanyID = $table.CompanyID and dt.PurchaseNo = $table.PurchaseNo), 0) as CountReceipt,
            ");
        endif;

        $this->db->from($table);

        $query = $this->db->get();
        return $query->row();
    }

    public function get_by_detail($id,$page=""){
        $CompanyID = $this->session->CompanyID;
        $this->db->select("
            purchasedet.PurchaseNo,
            purchasedet.PurchaseDet,
            purchasedet.ProductID       as productid,
            purchasedet.Qty             as product_qty,
            purchasedet.Conversion      as product_conv,
            purchasedet.Price           as product_price,
            purchasedet.TotalPrice      as product_total,
            purchasedet.Remark          as remark,
            purchasedet.UnitID          as unitid,
            purchasedet.Uom,
            purchasedet.Type            as type,
            purchasedet.DeliveryDate    as delivery_date,
            purchasedet.Discount        as discount,
            purchasedet.DiscountValue   as discount_value,
            ifnull(purchasedet.ReceiveQty,0) as ReceiveQty,
            ps_product.Code             as product_code,
            ps_product.Name             as product_name,
            ps_product.Qty              as product_qty2,
            (CASE
            WHEN purchasedet.Type = 1 THEN 'unique'
            WHEN purchasedet.Type = 2 THEN 'serial'
            ELSE 'general' END) AS product_type,
            unit.Uom2 as unit_name,
        ");
        $this->db->join("ps_product","purchasedet.ProductID = ps_product.ProductID","left");
        $this->db->join("ps_product_unit as unit", "purchasedet.Uom = unit.ProductUnitID", "left");
        // $this->db->join("ps_unit","purchasedet.UnitID = ps_unit.UnitID","left");
        $this->db->where("purchasedet.CompanyID", $CompanyID);
        if($page == "add_serial"):
            $this->db->where("purchasedet.SellDet",$id);
        else:
            $this->db->where("purchasedet.PurchaseNo", $id);
        endif;
        $this->db->from("PS_Purchase_Detail as purchasedet");
        $query = $this->db->get();
        return $query->result();
    }

    public function get_list_detail($id,$page = "")
    {
        $this->db->select("
            purchasedet.PurchaseNo,
            purchasedet.PurchaseDet,
            purchasedet.ProductID      as productid,
            purchasedet.Qty            as purchase_qty,
            purchasedet.Conversion     as purchase_conv,
            purchasedet.Price          as purchase_price,
            purchasedet.Remark         as remark,
            purchasedet.UnitID         as unitid,
            purchasedet.Type           as type,
            purchasedet.SerialNumber   as serialnumber,
            ps_product.Code            as product_code,
            ps_product.Name            as product_name,
            ps_product.Qty             as product_qty2,
            (CASE
            WHEN purchasedet.Type = 1 THEN 'unique'
            WHEN purchasedet.Type = 2 THEN 'serial'
            ELSE 'general' END) AS product_type,
            ps_unit.Name as unit_name,
            (select SerialNo from PS_Product_Serial where CompanyID = purchasedet.CompanyID and ProductID = purchasedet.ProductID limit 1) as serialno,
        ");
        $this->db->join("ps_product","purchasedet.ProductID = ps_product.ProductID","left");
        $this->db->join("ps_unit","purchasedet.UnitID = ps_unit.UnitID","left");
        $this->db->where("purchasedet.CompanyID",$this->session->CompanyID);
        if($page == "add_serial"):
            $this->db->where("purchasedet.PurchaseDet",$id);
        else:
            $this->db->where("purchasedet.PurchaseNo",$id);
        endif;
        $query = $this->db->get("PS_Purchase_Detail as purchasedet");
        if($page == "add_serial"):
            return $query->row();
        else:
            return $query->result();
        endif;
    }

    public function get_serial_by_id($detail,$header="",$page=""){
        $this->db->select('*,PurchaseDetSN as serial_id,SN as SerialNumber');
        $this->db->where("CompanyID", $this->session->CompanyID);
        if($page == "detail"):

        else:
            $this->db->where("PurchaseDet", $detail);
        endif;
        if($header):
            $this->db->where("PurchaseNo", $header);
        endif;

        $query = $this->db->get("PS_Purchase_Detail_SN");

        return $query->result();
    }
}
  