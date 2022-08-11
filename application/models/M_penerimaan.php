<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_penerimaan extends CI_Model {
    
    var $table = "AP_GoodReceipt";
    var $column = array(
        'AP_GoodReceipt.ReceiveNo',
        'AP_GoodReceipt.ReceiveNo',
        'AP_GoodReceipt.Date',
        'AP_GoodReceipt.PurchaseNo',
        'AP_GoodReceipt.ReceiveName',
        'Branch.Name',
        'AP_GoodReceipt.Status',
        'AP_GoodReceipt.Type',
        '(select sum(Qty) from AP_GoodReceipt_Det where ReceiveNo = AP_GoodReceipt.ReceiveNo)',
    );
    var $order  = array('AP_GoodReceipt.ReceiveNo' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set("Asia/Jakarta");
    }
    private function _get_datatables_query($page="")
    {
        $table = $this->table;
        $invoice = "(select count(dt.ReceiveNo) from PS_Invoice_Detail dt left join PS_Invoice mt 
            on mt.CompanyID = dt.CompanyID and mt.InvoiceNo = dt.InvoiceNo
            where dt.ReceiveNo = $table.ReceiveNo and dt.CompanyID = $table.CompanyID and mt.Status = '1'
            )";

        $retur = "(select count(mt.ReceiveNo) from AP_Retur mt
            where mt.ReceiveNo = $table.ReceiveNo and mt.CompanyID = $table.CompanyID and mt.Status = '1'
            )";
        $this->db->select("
            $table.ReceiveNo,    
            $table.PurchaseNo,    
            $table.Date,
            $table.Status,
            $table.Type,
            $table.VendorID,
            $table.BranchID,
            (select sum(Qty) from AP_GoodReceipt_Det where ReceiveNo = $table.ReceiveNo and CompanyID = $table.CompanyID) as Qty,
            $table.ReceiveName as ReceiveName,
            $table.ProductType,

            ifnull(Branch.Name,'') as branchName,

            ifnull($invoice,0) as ck_invoice,
            ifnull($retur,0) as ck_retur,
        ");
        $this->db->join("PS_Vendor      as vendor", "$table.VendorID = vendor.VendorID and $table.CompanyID = vendor.CompanyID", "left");
        $this->db->join("Branch", "$table.BranchID = Branch.BranchID and $table.CompanyID = Branch.CompanyID", "left");
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
        if($this->input->post("Status") != "none"):
            $Status = $this->input->post("Status");
            $this->db->where("$table.Status", $Status);
        endif;
        if($this->input->post("ProductType") != "none"):
            $ProductType = $this->input->post("ProductType");
            $this->db->where("$table.ProductType", $ProductType);
        endif;
        $Branch = $this->input->post("Branch");
        if($Branch != "all" && $Branch):
            $this->db->where("$table.BranchID", $Branch);
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
        $this->db->where("AP_GoodReceipt.CompanyID",$this->session->CompanyID);
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function count_all($page="")
    {
        $this->db->where("AP_GoodReceipt.CompanyID",$this->session->CompanyID);
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
        $this->db->insert("AP_GoodReceipt_Det", $data);
        return $this->db->insert_id();  
    }
    public function update_det($where, $data)
    {
        $this->db->set("User_Ch",$this->session->NAMA);
        $this->db->set("Date_Ch",date("Y-m-d H:i:s"));
        $this->db->update("AP_GoodReceipt_Det", $data, $where);
        return $this->db->affected_rows();
    }

    public function get_by_id($id,$tambahan="")
	{
        $page       = $this->input->post('page');
		$this->db->select("
            good.Status                  as Status,
			good.ReceiveNo               as receipt_no,
            good.CompanyID,
			good.ReceiveName             as receipt_name,
            good.VendorID,
            good.BranchID,
			good.SJNo                    as sj_no,
			good.PurchaseNo              as po_no,
            good.Type                    as Type,
			good.Date                    as receipt_date,
			good.Remark                  as receipt_remark,
            IFNULL(good.DeliveryCost, 0) as receipt_cost,
            good.Discount                as receipt_discount,
            grdet.Qty                    as Qty,
            good.Total                   as Total,
            good.Payment                 as Payment,
            good.PPN,
            good.ProductType,
            good.InvoiceStatus,
            ifnull(good.Term,0)         as Term,
             (case
                when good.Type = '1' then purchase.PaymentTerm
                else good.Term
            end)                        as Term,
            good.DeliveryDate,
            good.Module,
            good.TotalPPN,
            good.Tax                    as Tax,
            sales.Name                  as salesName,
            vendor.Name                 as vendorName,
            ifnull(address1.Address,'') as vendorAddress,
            vendor.Phone                as vendorPhone,
            ifnull(vendor.AP_Max,0)     as vendorTerm,
            vendor.NPWP                 as vendorNPWP,
            ifnull(Branch.Name,'')      as branchName,
		");
		$this->db->where('good.ReceiveNo',$id);
        // $this->db->where("purchase.CompanyID",$this->session->CompanyID);
        $this->db->where("good.CompanyID",$this->session->CompanyID);
        $this->db->join("AP_GoodReceipt_Det as grdet", "good.ReceiveNo = grdet.ReceiveNo and good.CompanyID = grdet.CompanyID", "left");
        $this->db->join("PS_Purchase    as purchase", "good.PurchaseNo = purchase.PurchaseNo and good.CompanyID = purchase.CompanyID", "left");
        $this->db->join("PS_Sales       as sales", "good.SalesID = sales.SalesID and good.CompanyID = sales.CompanyID", "left");
        $this->db->join("Branch", "good.BranchID = Branch.BranchID and good.CompanyID = Branch.CompanyID", "left");
        $this->db->join("PS_Vendor      as vendor", "good.VendorID = vendor.VendorID and good.CompanyID = sales.CompanyID", "left");
        $this->db->join("ps_vendor_address address1", "address1.VendorCode = vendor.Code and address1.Delivery = '1'","left");
		$this->db->from($this->table." as good");

        if($tambahan == "edit"):
            $this->db->select("
                ifnull((select count(dt.ReceiveNo) from PS_Invoice_Detail dt join PS_Invoice mt on dt.InvoiceNo = mt.InvoiceNo and dt.CompanyID = mt.CompanyID where dt.ReceiveNo = good.ReceiveNo and dt.CompanyID = good.CompanyID and mt.Status = '1'), 0) as InvoiceCount,
                ifnull((select count(dt.ReceiveNo) from AP_Retur_Det dt join AP_Retur mt on dt.ReturNo = mt.ReturNo and dt.CompanyID = mt.CompanyID where dt.ReceiveNo = good.ReceiveNo and dt.CompanyID = good.CompanyID and mt.Status = '1'), 0) as ReturnCount,
            ");
        endif;

        if($page == "invoice_receipt"):
            $this->main->join_vendor_address("invoice");
        endif;

		$query = $this->db->get();

		return $query->row();
	}

    public function get_by_detail($id,$page = "")
    {
        $this->db->select("
           
            grdet.PurchaseNo        as Purchase_purchaseno,                            
            grdet.PurchaseDet       as Purchase_purchasedet,
            grdet.ReceiveDet        as receipt_det,
            grdet.ReceiveNo         as receipt_no,
            grdet.ProductID         as productid,
            grdet.Qty               as receive_qty,
            grdet.Status            as grStatus,
            grdet.Conversion        as receipt_konv,
            grdet.Price             as receipt_price,
            grdet.TotalPrice,
            grdet.SubTotal          as receipt_subtotal,
            grdet.Discount          as receipt_discount,
            grdet.Remark            as receipt_remark,
            grdet.UnitID            as unitid,
            grdet.Uom,
            grdet.DeliveryCost      as receipt_cost,
            ifnull(grdet.Cost,0)    as Cost,

            purchase.Tax            as Tax,
            purchase.TotalPPN,
            purchase.Date           as Purchase_date,

            purchasedet.Qty         as purchaseQty,
            purchasedet.DiscountValue,

            ps_product.Code         as product_code,
            ps_product.Name         as product_name,
            ps_product.Qty          as product_stock,

            (CASE
            WHEN ps_product.Type = 1 THEN 'unique'
            WHEN ps_product.Type = 2 THEN 'serial'
            ELSE 'general' END) AS product_type,

            ifnull(unit.Uom,'')      as unit_name,

        ");
        $this->db->join("PS_Purchase_Detail as purchasedet", "grdet.PurchaseDet = purchasedet.PurchaseDet and grdet.CompanyID = purchasedet.CompanyID", "left");
        $this->db->join("PS_Purchase as purchase", "purchasedet.PurchaseNo = purchase.PurchaseNo and purchasedet.CompanyID = purchase.CompanyID", "left");
        $this->db->join("ps_product","grdet.ProductID = ps_product.ProductID","left");
        // $this->db->join("ps_unit","grdet.UnitID = ps_unit.UnitID","left");
        $this->db->join("ps_product_unit as unit", "unit.ProductUnitID = grdet.Uom", "left");
        $this->db->where("grdet.CompanyID",$this->session->CompanyID);


        if($page == "add_serial"):
            $this->db->where("ReceiveDet",$id);
        else:
            $this->db->where("ReceiveNo",$id);
        endif;
        $query = $this->db->get("AP_GoodReceipt_Det as grdet");
        if($page == "add_serial"):
            return $query->row();
        else:
            return $query->result();
        endif;
    }

    public function get_by_detail_non_order($id,$page=""){
        $this->db->select("
            grdet.PurchaseDet       as Purchase_purchasedet,
            grdet.PurchaseNo        as Purchase_purchaseno,
            grdet.ReceiveDet        as receipt_det,
            grdet.ReceiveNo         as receipt_no,
            grdet.ProductID         as productid,
            grdet.Qty               as receive_qty,
            grdet.Status            as grStatus,
            grdet.Discount          as receipt_discount,
            grdet.Conversion        as receipt_konv,
            grdet.Price             as receipt_price,
            grdet.TotalPrice,
            grdet.SubTotal          as receipt_subtotal,
            grdet.Remark            as receipt_remark,
            grdet.AveragePrice,
            grdet.UnitID            as unitid,
            grdet.Uom,
            grdet.DeliveryCost,
            ifnull(grdet.Cost,0)    as Cost,
            
            ('')                    as Purchase_date,
            ('')                    as purchaseQty,
            ps_product.Tax,
            ps_product.PPN,
            ps_product.TotalPPN,
            ps_product.ProductID    as ProductID,
            ps_product.SalesID      as SalesID,
            ps_product.Code         as product_code,
            ps_product.Name         as product_name,
            ps_product.Qty          as product_stock,
            (ps_product.Qty - grdet.Qty) as productStock,
        
			(CASE
            WHEN ps_product.type = 1 THEN 'unique'
            WHEN ps_product.type = 2 THEN 'serial'
            ELSE 'general' END) AS product_type,
            ps_product.SNAuto,

			ifnull(unit.Uom,'') 	as unit_name,

		"); 
        // $this->db->join("AP_GoodReceipt as good",  "grdet.ReceiveNo  = good.ReceiveNo", "left");
		$this->db->join("ps_product","grdet.ProductID = ps_product.ProductID","left");
		// $this->db->join("ps_unit","grdet.UnitID = ps_unit.UnitID","left");
        $this->db->join("ps_product_unit as unit", "unit.ProductUnitID = grdet.Uom", "left");
        $this->db->where("grdet.CompanyID",$this->session->CompanyID);

        if($page == "add_serial"):
            $this->db->where("ReceiveDet",$id);
        else:
            $this->db->where("ReceiveNo",$id);
        endif;
        $query = $this->db->get("AP_GoodReceipt_Det as grdet");
		if($page == "add_serial"):
			return $query->row();
		else:
			return $query->result();
		endif;
    }

    public function label_purchase_type($type,$page="",$id=""){
        if($type == 1):
            $label  = '<hijau class="dtype'.$id.'" data-status="1">Purchase Order</hijau>';
            if($page == "cetak"):
                $label  = 'Active';
            endif;
        else:
            $label  = '<biru class="dtype'.$id.'" data-status="2">Non Purchase Order</biru>';
            if($page == "cetak"):
                $label  = 'Cancel';
            endif;
        endif;

        return $label;
    }

    public function serial_number_list($mt,$dt){
        $CompanyID  = $this->session->CompanyID;
        $table      = "AP_GoodReceipt_Det_SN";

        $this->db->select("
            SN,
            product.Name as product_name,
            'Serial'    as product_type,
            dt.Qty,
        ");

        $this->db->join("ps_product         as product", "product.ProductID = $table.ProductID", "left");
        $this->db->join("AP_GoodReceipt_Det as dt", "dt.ReceiveDet = $table.ReceiveDet and dt.ReceiveNo = $table.ReceiveNo and dt.CompanyID = $table.CompanyID");
        $this->db->where("$table.CompanyID", $CompanyID);
        $this->db->where("$table.ReceiveNo", $mt);
        $this->db->where("$table.ReceiveDet", $dt);
        $query = $this->db->get($table);

        return $query->result();
    }
}