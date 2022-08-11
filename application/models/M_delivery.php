<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_delivery extends CI_Model {
    
    var $table = "PS_Delivery";
    var $column = array(
        'PS_Delivery.DeliveryNo',
        'PS_Delivery.DeliveryNo',
        'PS_Delivery.Date',
        'PS_Delivery.SellNo',
        'vendor.Name',
        'PS_Delivery.Status',
        'PS_Delivery.Type',
        '(select sum(Qty) from PS_Delivery_Det where DeliveryNo = PS_Delivery.DeliveryNo and CompanyID = PS_Delivery.CompanyID)',
    );
    var $order  = array('PS_Delivery.DeliveryNo' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set("Asia/Jakarta");
    }
    private function _get_datatables_query($page="")
    {
        $table = $this->table;

        $invoice = "(select count(dt.DeliveryNo) from PS_Invoice_Detail dt left join PS_Invoice mt 
            on mt.CompanyID = dt.CompanyID and mt.InvoiceNo = dt.InvoiceNo
            where dt.DeliveryNo = $table.DeliveryNo and dt.CompanyID = $table.CompanyID and mt.Status = '1'
            )";

        $return = "(select count(mt.DeliveryNo) from AP_Retur mt
            where mt.DeliveryNo = $table.DeliveryNo and mt.CompanyID = $table.CompanyID and mt.Status = '1'
            )";

        $this->db->select("
            $table.DeliveryNo,    
            $table.SellNo,    
            $table.DeliveryTo,
            $table.Date,
            $table.Status,
            $table.Type,
            $table.ProductType,
            $table.VendorID,
            (select sum(Qty) from PS_Delivery_Det where DeliveryNo = $table.DeliveryNo and CompanyID = $table.CompanyID) as Qty,
            vendor.Name as vendorName,

            ifnull($invoice,0) as ck_invoice,
            ifnull($return,0) as ck_return,

        ");
        $this->db->join("PS_Vendor as vendor", "$table.VendorID = vendor.VendorID", "left");
        $this->db->from($this->table);
        $this->db->where("$table.CompanyID",$this->session->CompanyID);
        if($this->input->post('sellno')):
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
        if($this->input->post("ProductType") != "none"):
            $ProductType = $this->input->post("ProductType");
            $this->db->where("$table.ProductType", $ProductType);
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
        $this->db->where("PS_Delivery.CompanyID",$this->session->CompanyID);
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function count_all($page="")
    {
        $this->db->where("PS_Delivery.CompanyID",$this->session->CompanyID);
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
        $this->db->insert("PS_Delivery_Det", $data);
        return $this->db->insert_id();  
    }
    public function update_det($where, $data)
    {
        $this->db->set("User_Ch",$this->session->NAMA);
        $this->db->set("Date_Ch",date("Y-m-d H:i:s"));
        $this->db->update("PS_Delivery_Det", $data, $where);
        return $this->db->affected_rows();
    }

    public function save_serial($data)
    {
        $this->db->set("User_Add",$this->session->NAMA);
        $this->db->set("Date_Add",date("Y-m-d H:i:s"));
        $this->db->insert("PS_Delivery_Det_SN", $data);
        return $this->db->insert_id();  
    }
    public function update_serial($where, $data)
    {
        $this->db->set("User_Ch",$this->session->NAMA);
        $this->db->set("Date_Ch",date("Y-m-d H:i:s"));
        $this->db->update("PS_Delivery_Det_SN", $data, $where);
        return $this->db->affected_rows();
    }

    public function get_by_id($id,$tambahan=""){
        $page       = $this->input->post('page');
        $CompanyID  = $this->session->CompanyID;
        $this->db->select("
            delivery.DeliveryNo,
            delivery.CompanyID,
            delivery.BranchID,
            delivery.VendorID,
            delivery.SalesID,
            delivery.SellNo,
            delivery.Date,
            delivery.DeliveryTo,
            delivery.Address,
            delivery.City,
            delivery.Province,
            delivery.Status,
            delivery.Remark,
            delivery.Type,

            delivery.Tax,
            delivery.PPN,
            delivery.TotalPPN,
            delivery.Discount,
            delivery.DiscountPersent,
            delivery.Total,
            delivery.Payment,
            delivery.DeliveryCost,
            delivery.ProductType,
            delivery.InvoiceStatus,
            (case
                when delivery.Type = '1' then sell.Term
                else delivery.Term
            end) as Term,
            delivery.Module as deliveryModule,

            sell.Date       as sellDate,

            sales.Name      as salesName,
            vendor.Name     as vendorName,
            vendor.Phone    as vendorPhone,
            vendor.Address  as vendorAddress,
            ifnull(vendor.AP_Max,0) as vendorTerm,
            vendor.NPWP     as vendorNPWP,
            ifnull(vendor.productcustomer,'') as productcustomer,
            ifnull(Branch.Name,'') as branchName,
        ");

        $this->db->join("PS_Vendor      as vendor", "delivery.VendorID = vendor.VendorID", "left");
        $this->db->join("PS_Sales       as sales", "delivery.SalesID = sales.SalesID", "left");
        $this->db->join("PS_Sell        as sell", "delivery.SellNo = sell.SellNo", "left");
        $this->db->join("Branch", "delivery.BranchID = Branch.BranchID and delivery.CompanyID = Branch.CompanyID", "left");
        $this->db->where("delivery.CompanyID", $this->session->CompanyID);
        // $this->db->where("sell.CompanyID", $this->session->CompanyID);
        $this->db->where("delivery.DeliveryNo", $id);
        $this->db->from($this->table." as delivery");

        if($tambahan == "edit"):
            $this->db->select("
                ifnull((select count(dt.DeliveryNo) from PS_Invoice_Detail dt join PS_Invoice mt on dt.InvoiceNo = mt.InvoiceNo and dt.CompanyID = mt.CompanyID where dt.DeliveryNo = delivery.DeliveryNo and dt.CompanyID = delivery.CompanyID and mt.Status = '1'), 0) as InvoiceCount,
            ");
        endif;
        
        if($page == "invoice_selling"):
            $this->main->join_vendor_address("invoice");
        endif;

        $query = $this->db->get();

        return $query->row();
    }

    public function get_by_detail($id,$page=""){
        $this->db->select("
            deliverydet.DeliveryNo,
            deliverydet.DeliveryDet,
            deliverydet.SellNo,
            deliverydet.SellDet,
            deliverydet.ProductID,
            deliverydet.Qty,
            deliverydet.Conversion,
            deliverydet.Uom as UnitID,
            deliverydet.Type,
            deliverydet.Price,
            deliverydet.TotalPrice,
            deliverydet.Discount,
            deliverydet.DiscountValue,
            deliverydet.Remark,
            deliverydet.DeliveryCost,
            ifnull(deliverydet.Cost,0) as Cost,

            sell.Date       as sellDate,
            sell.Module     as sellModule,
            selldet.Qty     as sellQty,
            selldet.UnitID  as unitid,
            selldet.Type    as Typetxt,
            case
                when selldet.Type = 0 then 'general'
                when selldet.Type = 1 then 'unique'
                else 'serial'
            end as product_type_txt,
            selldet.SerialNumber as product_serial,
            
            product.Code        as productCode,
            product.Name        as productName,
            ifnull(unit.Uom,'') as unitName,

            sell.BranchID,
            ifnull(Branch.Name,'') as branchName,
        ");
        $this->db->join("PS_Sell_Detail as selldet", "deliverydet.SellDet = selldet.SellDet and deliverydet.CompanyID = selldet.CompanyID", "left");
        $this->db->join("PS_Sell        as sell", "selldet.SellNo = sell.SellNo and selldet.CompanyID = sell.CompanyID", "left");
        $this->db->join("Branch", "sell.BranchID = Branch.BranchID and sell.CompanyID = Branch.CompanyID", "left");
        $this->db->join("ps_product     as product", "deliverydet.ProductID = product.ProductID", "left");
        // $this->db->join("ps_unit        as unit", "deliverydet.UnitID = unit.UnitID", "left");
        $this->db->join("ps_product_unit as unit", "unit.ProductUnitID = deliverydet.Uom", "left");
        $this->db->where("deliverydet.CompanyID", $this->session->CompanyID);
        if($page == "detail"):
            $this->db->where("deliverydet.DeliveryDet", $id);
        else:
            $this->db->where("deliverydet.DeliveryNo", $id);
        endif;
        $this->db->from("PS_Delivery_Det as deliverydet");
        $query = $this->db->get();

        if($page == "detail"):
            return $query->row();
        else:
            return $query->result();
        endif;
    }

    public function get_by_detail_non_order($id){
        $this->db->select("
            deliverydet.DeliveryNo,
            deliverydet.DeliveryDet,
            deliverydet.SellNo,
            deliverydet.SellDet,
            deliverydet.ProductID,
            deliverydet.Qty,
            deliverydet.Conversion,
            deliverydet.Uom as UnitID,
            deliverydet.Type,
            deliverydet.Price,
            deliverydet.TotalPrice,
            deliverydet.Discount,
            deliverydet.DiscountValue,
            deliverydet.Remark,
            deliverydet.DeliveryCost,
            ifnull(deliverydet.Cost,0) as Cost,

            ('')            as sellDate,
            ('')            as sellQty,
            
            product.Code    as productCode,
            product.Name    as productName,
            product.Qty     as product_stock,
            product.Type    as Typetxt,
            case
                when product.Type = 0 then 'general'
                when product.Type = 1 then 'unique'
                else 'serial'
            end as product_type_txt,
            (product.Qty + deliverydet.Qty) as productStock,

            ifnull(unit.ProductUnitID,'') as unitid,
            ifnull(unit.Uom,'') as unitName,

            '' as BranchID,
            '' as branchName,
        ");
        $this->db->join("ps_product     as product", "deliverydet.ProductID = product.ProductID", "left");
        // $this->db->join("ps_unit        as unit", "deliverydet.UnitID = unit.UnitID", "left");
        $this->db->join("ps_product_unit as unit", "unit.ProductUnitID = deliverydet.Uom", "left");
        $this->db->where("deliverydet.CompanyID", $this->session->CompanyID);
        $this->db->where("deliverydet.DeliveryNo", $id);
        $this->db->from("PS_Delivery_Det as deliverydet");
        $query = $this->db->get();

        return $query->result();
    }

    public function label_delivery_type($type,$page="",$id=""){
        if($type == 1):
            $label  = '<hijau class="dtype'.$id.'" data-status="1">Sales Order</hijau>';
            if($page == "cetak"):
                $label  = 'Active';
            endif;
        else:
            $label  = '<biru class="dtype'.$id.'" data-status="2">Non Sales Order</biru>';
            if($page == "cetak"):
                $label  = 'Cancel';
            endif;
        endif;

        return $label;
    }

    public function get_serial_by_id($detail,$header="",$page=""){
        $this->db->select('*,DeliveryDetSN as serial_id');
        $this->db->where("CompanyID", $this->session->CompanyID);
        if($page == "detail"):

        else:
            $this->db->where("DeliveryDet", $detail);
        endif;
        if($header):
            $this->db->where("DeliveryNo", $header);
        endif;

        $query = $this->db->get("PS_Delivery_Det_SN");

        return $query->result();
    }

    public function serial_number($id,$det,$ProductID){
        $CompanyID = $this->session->CompanyID;
        $this->db->select("
            SN,
        ");
        $this->db->where("CompanyID", $CompanyID);
        $this->db->where("DeliveryNo", $id);
        $this->db->where("DeliveryDet", $det);
        $this->db->where("ProductID", $ProductID);
        $query = $this->db->get("PS_Delivery_Det_SN");

        return $query->result();
    }

    public function serial_number_list($mt,$dt){
        $CompanyID  = $this->session->CompanyID;
        $table      = "PS_Delivery_Det_SN";

        $this->db->select("
            SN,
            product.Name as product_name,
            'Serial'     as product_type,
            dt.Qty,
        ");

        $this->db->join("ps_product       as product", "product.ProductID = $table.ProductID", "left");
        $this->db->join("PS_Delivery_Det  as dt", "dt.DeliveryNo = $table.DeliveryNo and dt.DeliveryDet = $table.DeliveryDet and dt.CompanyID = $table.CompanyID");

        $this->db->where("$table.CompanyID", $CompanyID);
        $this->db->where("$table.DeliveryNo", $mt);
        $this->db->where("$table.DeliveryDet", $dt);
        $query = $this->db->get($table);

        return $query->result();
    }
}