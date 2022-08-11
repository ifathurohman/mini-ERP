
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_api extends CI_Model {
    var $host;
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->host = site_url();
        date_default_timezone_set("Asia/Jakarta");
    }

    #coa
    public function coa_company($CompanyID,$select="",$level="",$ID="",$page=""){
        $this->db->select("
            AC_COA.COAID as ID,
            AC_COA.Code,
            AC_COA.Name,
            AC_COA.Position as Level,
            AC_COA.PaymentType,
            AC_COA.ParentID,
            AC_COA.Active,
            AC_COA.Remark,

            ifnull(parent.Name, '') as parentName,
        ");

        $this->db->join("AC_COA as parent", "parent.COAID = AC_COA.ParentID", "left");
        $this->db->where("AC_COA.CompanyID", $CompanyID);
        $this->db->from("AC_COA");
        
        if($select):
            $this->db->where("AC_COA.Active", 1);
        endif;
        if($level):
            $this->db->where("AC_COA.Position", $level);
        endif;
        if($page == "parent"):
            $this->db->where("AC_COA.ParentID", $ID);
        endif;

        $query = $this->db->get();

        return $query->result();
    }
    public function coa_list($page="",$id="",$type=""){
        $CompanyID = $this->session->CompanyID;
        $this->db->select("
            AC_COA.COAID as ID,
            AC_COA.Code,
            AC_COA.Name,
            AC_COA.Position as Level,
            AC_COA.PaymentType,
            AC_COA.ParentID,

            ifnull(parent.Name, '') as parentName,
            ifnull(parent.Code, '') as parentCode,
            AC_COA.Remark,
        ");
        $this->db->join("AC_COA as parent", "parent.COAID = AC_COA.ParentID", "left");
        $this->db->from("AC_COA");
        $this->db->where("AC_COA.CompanyID", $CompanyID);
        if($type == "code"):
            $this->db->where("AC_COA.Code", $id);
        endif;

        if($page == "detail"):
            $query = $this->db->get()->row();
        endif;

        return $query;
    }
    public function coa_select($xselect = "",$xlevel = "",$xkasbank="",$p2="",$dt=""){

        $select  = $this->input->post("select");
        $level   = $this->input->post("level");
        $kasbank = $this->input->post('kasbank');
        if($xselect): $select = $xselect; endif;
        if($xlevel): $level = $xlevel; endif;
        if($xkasbank): $kasbank = $xkasbank;endif;

        if($p2 == "serverSide"):
            $pageServerSide = $dt["page"];
            if($pageServerSide == "count_all"):
                $this->db->where("Active", 1);
                $this->db->where("CompanyID", $this->session->CompanyID);
                $this->db->from("AC_COA");
                return $this->db->count_all_results();
            endif;
        endif;

        $this->db->select("
            AC_COA.COAID as ID,
            AC_COA.Code,
            AC_COA.Name,
            AC_COA.Position as Level,
            AC_COA.PaymentType,
            AC_COA.ParentID,

            ifnull(parent.Name, '') as parentName,
            ifnull(parent.Code, '') as parentCode,
            AC_COA.Remark,
        ");

        if($kasbank == "active"):
            $debit = "(select dt.Debit from AC_KasBank_Det as dt join AC_KasBank as mt 
                on mt.KasBankNo = dt.KasBankNo and mt.CompanyID = dt.CompanyID
                where mt.Type = '0' and mt.CompanyID = AC_COA.CompanyID and dt.COAID = AC_COA.COAID
                order by mt.Date_Add desc limit 1
                )";

            $credit = "(select dt.Credit from AC_KasBank_Det as dt join AC_KasBank as mt 
                on mt.KasBankNo = dt.KasBankNo and mt.CompanyID = dt.CompanyID
                where mt.Type = '0' and mt.CompanyID = AC_COA.CompanyID and dt.COAID = AC_COA.COAID
                order by mt.Date_Add desc limit 1
                )";

            $this->db->select("
                ifnull($debit,0)  as debit,
                ifnull($credit,0) as credit,
            ");
        endif;

        if($select == "active"):
            $this->db->where("AC_COA.Active",1);
        elseif($select == "inactive" || $select == "nonactive"):
            $this->db->where("AC_COA.Active",0);
        endif;
        if($level != "all"):
            $this->db->where("AC_COA.Position",$level);
        endif;
        $this->db->join("AC_COA as parent", "parent.COAID = AC_COA.ParentID", "left");
        $this->db->where("AC_COA.Name !=","");
        $this->db->where("AC_COA.CompanyID", $this->session->CompanyID);
        $this->db->order_by("AC_COA.Code","ASC");
        $this->db->from("AC_COA");

        if($p2 == "serverSide"):
            $column = $dt['column'];
            $Search = $this->input->post("search")["value"];
            $order_by = $dt['order_by'];
            $i = 0;
            foreach ($column as $item) 
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
            if(isset($_POST['order'])):
                $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            elseif(isset($order_by)):
                $this->db->order_by(key($order_by), $order_by[key($order_by)]);
            endif;
            if($pageServerSide == "list"):
                $this->db->limit($_POST['length'], $_POST['start']);
                $query = $this->db->get();
                return $query->result();
            elseif($pageServerSide == "count_filtered"):
                $query = $this->db->get();
                return $query->num_rows();
            endif;
        else:
            $query = $this->db->get();
            return $query->result();
        endif;
    }
    public function get_coa_setting($array){
        $table = "UT_Rule";
        $this->db->select("
            $table.Code,
            $table.nValue,
            $table.cValue,

            coa.Name    as coaName,
        ");
        $this->db->join("AC_COA as coa", "coa.COAID = $table.nValue", "left");
        $this->db->where("$table.CompanyID", $this->session->CompanyID);
        $this->db->where_in("$table.Code", $array);
        $this->db->from($table);
        $query = $this->db->get();

        return $query->result();
    }
    public function get_coa_seeting_company($CompanyID){
        $table = "UT_Rule";
        $this->db->select("
            $table.Code,
            $table.nValue,
            $table.cValue,

            coa.Name    as coaName,
        ");
        $this->db->join("AC_COA as coa", "coa.COAID = $table.nValue", "left");
        $this->db->where("$table.CompanyID", $CompanyID);
        $this->db->where("$table.cValue != ","template");
        $this->db->from($table);
        $query = $this->db->get();

        return $query->result();
    }
    public function generate_coa_list_example(){
        $arr_level1 = array();
        $level1     = $this->coa_company(0,1,1);
        foreach ($level1 as $a) {
            $arr_level2 = array();
            $level2     = $this->coa_company(0,1,2,$a->ID,"parent");
            foreach ($level2 as $b) {
                $arr_level3 = array();
                $level3     = $this->coa_company(0,1,3,$b->ID,"parent");
                foreach ($level3 as $c) {
                    $arr_level4 = array();
                    $level4     = $this->coa_company(0,1,4,$c->ID,"parent");
                    foreach ($level4 as $d) {
                        array_push($arr_level4, $d);
                    }
                    $c->list_parent = $arr_level4;
                    array_push($arr_level3, $c);
                }
                $b->list_parent = $arr_level3;
                array_push($arr_level2, $b);
            }
            $a->list_parent = $arr_level2;
            array_push($arr_level1, $a);
        }
        
        return $level1;
    }

    public function generate_coa_list($CompanyID){
        $nama_company = $this->main->get_one_column("user","nama",array("id_user" => $CompanyID))->nama;
        // $nama_company = "System";
        $level1     = $this->coa_company(0,1,1);
        foreach ($level1 as $a) {
            $data_level1 = array(
                "CompanyID" => $CompanyID,
                "Code"      => $a->Code,
                "Name"      => $a->Name,
                "Position"  => $a->Level,
                "Active"    => $a->Active,
                "Remark"    => $a->Remark,
                "UserAdd"   => $nama_company,
            );
            $level1_ID = $this->insert_coa($data_level1);
            
            $level2     = $this->coa_company(0,1,2,$a->ID,"parent");
            foreach ($level2 as $b) {
                $data_level2 = array(
                    "CompanyID" => $CompanyID,
                    "Code"      => $b->Code,
                    "Name"      => $b->Name,
                    "Position"  => $b->Level,
                    "Active"    => $b->Active,
                    "Remark"    => $b->Remark,
                    "UserAdd"   => $nama_company,
                    "ParentID"  => $level1_ID,
                );
                $level2_ID = $this->insert_coa($data_level2);
                
                $level3     = $this->coa_company(0,1,3,$b->ID,"parent");
                foreach ($level3 as $c) {
                    $data_level3 = array(
                        "CompanyID" => $CompanyID,
                        "Code"      => $c->Code,
                        "Name"      => $c->Name,
                        "Position"  => $c->Level,
                        "Active"    => $c->Active,
                        "Remark"    => $c->Remark,
                        "UserAdd"   => $nama_company,
                        "ParentID"  => $level2_ID,
                    );
                    $level3_ID = $this->insert_coa($data_level3);

                    $level4     = $this->coa_company(0,1,4,$c->ID,"parent");
                    foreach ($level4 as $d) {
                       $data_level4 = array(
                            "CompanyID" => $CompanyID,
                            "Code"      => $d->Code,
                            "Name"      => $d->Name,
                            "Position"  => $d->Level,
                            "Active"    => $d->Active,
                            "Remark"    => $d->Remark,
                            "UserAdd"   => $nama_company,
                            "ParentID"  => $level3_ID,
                        );
                        $level4_ID = $this->insert_coa($data_level4);
                    }
                }
            }
        }
    }

    private function insert_coa($data){
        $this->db->set("DateAdd",date("Y-m-d H:i:s"));
        $this->db->insert("AC_COA", $data);
        return $this->db->insert_id();
    }

    public function generate_coa_setting($CompanyID){
        $nama_company   = $this->main->get_one_column("user","nama",array("id_user" => $CompanyID))->nama;
        $list           = $this->get_coa_seeting_company(0);
        foreach ($list as $a) {
            $COAID = $this->main->get_one_column("AC_COA","COAID", array("Code" => $a->cValue, "CompanyID" => $CompanyID))->COAID;
            $data = array(
                "Code"          => $a->Code,
                "CompanyID"     => $CompanyID,
                "nValue"        => $COAID,
                "cValue"        => $a->cValue,
                "UserAdd"       => $nama_company,
                "DateAdd"       => date("Y-m-d H:i:s"),
            );

            $this->insert_coa_setting($data);
        }
    }
    private function insert_coa_setting($data){
        $this->db->set("DateAdd",date("Y-m-d H:i:s"));
        $this->db->insert("UT_Rule", $data);
        return $this->db->insert_id();
    }
    #end coa

    #conversion_balance
    public function conversion_balance(){

    }
    #end

    public function sales_select($xactive){
        $active = $this->input->post("Active");
        if($xactive !=""): $active = $xactive; endif;

        $this->db->select("
            SalesID as ID,
            Code,
            Name,
            Address,
            City,
            Remark,
            Contact,
            ");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->where("SalesID !=","");
        $this->db->order_by("Name","ASC");
        if($active == "active"):
            $this->db->where("Status", 1);
        endif;
        $query = $this->db->get("PS_Sales");   
        return $query->result();
    }

    public function customer_select(){
        $active = $this->input->post("Active");
        $this->db->select("VendorID as ID, VendorID as Name");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->where("VendorID !=","");
        $this->db->group_by("VendorID");
        $this->db->order_by("VendorID","ASC");
        $query = $this->db->get("PS_Vendor");   
        return $query->result();
    }

    public function purchaseno_select(){
        $active = $this->input->post("Active");
        $this->db->select("PurchaseNo as ID, PurchaseNo as PurchaseNo");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->where("PurchaseNo !=","");
        $this->db->group_by("PurchaseNo");
        $this->db->order_by("PurchaseNo","ASC");
        $query = $this->db->get("PS_Purchase");   
        return $query->result();
    }

    public function sales1_select(){
        $active = $this->input->post("Active");
        $this->db->select("SalesID as ID, Name as Name");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->where("SalesID !=","");
        $this->db->group_by("SalesID");
        $this->db->order_by("SalesID","ASC");
        $query = $this->db->get("PS_Sales");   
        return $query->result();
    }

    public function deliveryno_select(){
        $active = $this->input->post("Active");
        $this->db->select("DeliveryNo as ID, DeliveryNo as DeliveryNo");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->where("DeliveryNo !=","");
        $this->db->group_by("DeliveryNo");
        $this->db->order_by("DeliveryNo","ASC");
        $query = $this->db->get("PS_Delivery");   
        return $query->result();
    }

     public function sellno_select(){
        $active = $this->input->post("Active");
        $this->db->select("SellNo as ID, SellNo as SellNo");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->where("SellNo !=","");
        $this->db->group_by("SellNo");
        $this->db->order_by("SellNo","ASC");
        $query = $this->db->get("PS_Sell");   
        return $query->result();
    }

    public function invoice_select(){
        $active = $this->input->post("Active");
        $this->db->select("InvoiceNo as ID, InvoiceNo as InvoiceNo");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->where("InvoiceNo !=","");
        $this->db->group_by("InvoiceNo");
        $this->db->order_by("InvoiceNo","ASC");
        $query = $this->db->get("PS_Invoice");   
        return $query->result();
    }

    public function payno_select(){
        $active = $this->input->post("Active");
        $this->db->select("PaymentNo as ID, PaymentNo as PaymentNo");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->where("PaymentNo !=","");
        $this->db->group_by("PaymentNo");
        $this->db->order_by("PaymentNo","ASC");
        $query = $this->db->get("PS_Payment");   
        return $query->result();
    }

    public function branch_select(){
        $active = $this->input->post("Active");
        $this->db->select("BranchID as ID, Name as Name, Index");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->where("BranchID !=","");
        $this->db->group_by("BranchID");
        $this->db->order_by("BranchID","ASC");
        $query = $this->db->get("Branch");   
        return $query->result();
    }

    public function product_select(){
        $active = $this->input->post("Active");
        $this->db->select("ProductID as ID, ProductID as Name");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->where("ProductID !=","");
        $this->db->group_by("ProductID");
        $this->db->order_by("ProductID","ASC");
        $query = $this->db->get("ps_product");   
        return $query->result();
    }

    public function tax_select(){
        $active = $this->input->post("Active");
        $this->db->select("Tax as ID, Tax");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->where("Tax !=","");
        $this->db->group_by("Tax");
        $this->db->order_by("Tax","ASC");
        $query = $this->db->get("PS_Sell");   
        return $query->result();
    }

    #20190517 MW
    public function city_select(){
        $select     = $this->input->post("select");
        $CompanyID  = $this->session->CompanyID;
        $this->db->select("DeliveryCity");
        $this->db->where("CompanyID", $CompanyID);
        $this->db->where("DeliveryCity is not null");
        if($select == "active"):
            $this->db->where("Status", 1);
        endif;
        $this->db->group_by("DeliveryCity");
        $this->db->order_by("DeliveryCity");
        $this->db->from("PS_Sell");
        $query = $this->db->get();

        return $query->result();
    }

    public function get_AveragePrice($ProductID,$BranchID=""){
        $this->db->select("ifnull(AveragePrice,0) as AveragePrice");
        $this->db->where("CompanyID", $this->session->CompanyID);
        $this->db->where("ProductID", $ProductID);
        if($BranchID):
            $this->db->where("BranchID", $BranchID);
            $query = $this->db->get("PS_Product_Branch")->row();
        else:
            $query = $this->db->get("ps_product")->row();
        endif;
        
        if($query):
            return $query->AveragePrice;
        else:
            return 0;
        endif;
    }

    public function product_unit($ID,$page=""){
        $CompanyID = $this->session->CompanyID;
        $table = "ps_product_unit";

        if($page != "autocomplete"):
            $this->db->select("
                ProductUnitID as ID,
                Uom,
                Uom2,
                Conversion,
                ifnull(PurchasePrice01, 0) as purchase,
                ifnull(SellingPrice01,0)   as selling,
            ");
        endif;
        $this->db->where("CompanyID", $CompanyID);
        if($page == "detail"):

        elseif($page == "autocomplete"):
            $this->db->select("Uom");
            $this->db->like("Uom", $ID);
            $this->db->group_by("Uom");
        else:
            $this->db->where("ProductID", $ID);
        endif;
        $query = $this->db->get($table);

        return $query->result();
    }

    // public function pegawai_select(){
    //     $active = $this->input->post("Active");
    //     $this->db->select("UserID as ID, Name, Skill");
    //     $this->db->where("CompanyID",$this->session->CompanyID);
    //     $this->db->where("HakAksesID",3);
    //     if($active == 1):
    //         $this->db->where("Active",$active);
    //     endif;
    //     $this->db->order_by("Name","ASC");
    //     $query = $this->db->get("UT_User");   
    //     return $query->result();
    // }

    #selling
    public function selling($ID="",$page=""){
        $this->db->select("
            PS_Sell.SellNo,
            PS_Sell.Total,
            PS_Sell.Payment,
            PS_Sell.VendorID,
            PS_Sell.PPN,
            PS_Sell.TotalPPN,
            PS_Sell.Discount,
            PS_Sell.DiscountPersent,
            PS_Sell.Date,
            PS_Sell.Remark,
            customer.Name as customerName,
        ");
        $this->db->join("PS_Vendor as customer", "PS_Sell.VendorID = customer.VendorID", "left");
        if($ID != ""):
            $this->db->where("PS_Sell.SellNo", $ID);
        endif;
        $this->db->where("PS_Sell.CompanyID", $this->session->CompanyID);
        $this->db->from("PS_Sell");
        $query = $this->db->get();
        if($page == "detail"):
            return $query->row();
        else:
            return $query->result();
        endif;
    }
    public function selling_detail($id){
        $this->db->select("
            ps_sd.SellNo,
            ps_sd.SellDet,
            ps_sd.ProductID,
            ps_sd.UnitID,
            ps_sd.Qty,
            ps_sd.Conversion,
            ps_sd.Price,
            ps_sd.TotalPrice,
            ps_sd.Discount,
            ps_sd.Type,
            IFNULL(ps_sd.Remark,'') as Remark,

            ps_product.Name as product_name,
            ps_product.Code as product_code,

            ps_unit.Name unit_name,
        ");
        $this->db->join("ps_product", "ps_sd.ProductID = ps_product.ProductID", "left");
        $this->db->join("ps_unit","ps_sd.UnitID = ps_unit.UnitID", "left");
        $this->db->from("PS_Sell_Detail as ps_sd");
        $this->db->where("ps_sd.CompanyID", $this->session->CompanyID);
        $this->db->where("ps_sd.SellNo", $id);
        $query = $this->db->get();

        return $query->result();
    }

    public function sellingdet_detail($selldet,$sellno=""){
        $this->db->select("Qty,Module,PS_Sell.BranchID");
        $this->db->where("PS_Sell_Detail.SellDet", $selldet);
        if($sellno !=""):
            $this->db->where("PS_Sell_Detail.SellNo", $sellno);
        endif;
        $this->db->where("PS_Sell.CompanyID", $this->session->CompanyID);
        $this->db->join("PS_Sell", "PS_Sell_Detail.SellNo = PS_Sell.SellNo and PS_Sell_Detail.CompanyID = PS_Sell.CompanyID", "left");
        $query = $this->db->get("PS_Sell_Detail");
 
        return $query->row();
    }

    public function purchasedet_detail($PurchaseDet,$PurchaseNo=""){
        $this->db->select("Qty");
        $this->db->where("PS_Purchase_Detail.PurchaseDet", $PurchaseDet);
        if($PurchaseNo !=""):
            $this->db->where("PS_Purchase_Detail.PurchaseNo", $PurchaseNo);
        endif;
        $this->db->where("PS_Purchase.CompanyID", $this->session->CompanyID);
        $this->db->where("PS_Purchase_Detail.CompanyID", $this->session->CompanyID);
        $this->db->join("PS_Purchase", "PS_Purchase_Detail.PurchaseNo = PS_Purchase.PurchaseNo and PS_Purchase_Detail.CompanyID = PS_Purchase.CompanyID", "left");
        $query = $this->db->get("PS_Purchase_Detail");
 
        return $query->row();
    }

     public function avrage($ProductID){
        $this->db->select("
            ifnull(sum(grdet.Qty * grdet.Price), 0) as total,
            ifnull(sum(grdet.Qty), 0)               as qty,
            ");
        $this->db->join("AP_GoodReceipt as gr", "gr.ReceiveNo = grdet.ReceiveNo and gr.CompanyID = grdet.CompanyID", "left");
        $this->db->where("grdet.CompanyID", $this->session->CompanyID);
        // $this->db->where("grdet.ProductID", $produtidnya);
        $this->db->where("grdet.ProductID", $ProductID);
        $this->db->where("gr.Status", 1);
        $this->db->from("AP_GoodReceipt_Det as grdet");
        $query  = $this->db->get();
        $d      = $query->row();

        // $qty_gr     = 1;
        // $price_gr   = 100000;

        // $sub_total      = $qty_gr * $price_gr;
        // $total_price    = $sub_total + $d->total;
        // $total_qty      = $d->qty + $qty_gr;

        // $harganya = $total_price / $total_qty;

        $harganya = $d->total / $d->qty;

        $data = array(
            "AveragePrice" => $harganya,
        );
        $this->db->where("CompanyID", $this->session->CompanyID);
        $this->db->where("ProductID", $ProductID);
        $this->db->update("ps_product", $data);

        // echo "1. qty transaksi sebelumnya : ".$d->qty." / total price sebelumnya : ".$d->total."<br>";
        // echo "2. qty gr : ".$qty_gr." / price gr :".$price_gr."<br>";
        // echo "3. sub total gr : ".$sub_total."<br>";
        // echo "4. total price : ".$total_price." / total qty : ".$total_qty."<br>";
        // echo "5. hasilnya : ".$harganya;
    }

    #invoice
    public function invoice_detail($id,$page=""){
        $this->db->select("
            psi.InvoiceNo,
            psi.CompanyID,
            psi.BranchID,
            psi.VendorID,
            psi.SalesID,
            psi.SellNo,
            psi.PurchaseNo,
            psi.Date,
            psi.Type,
            psi.BillingTo,
            psi.Address,
            psi.City,
            psi.Province,
            psi.PPN,
            psi.Discount,
            psi.Total,
            psi.Remark,
            psi.Status,

            pss.Name as salesName,
            ");
        $this->db->join("PS_Sales as pss", "psi.SalesID = pss.SalesID", "left");
        if($page == "selling"):
            $this->db->where("psi.SellNo", $id);
        else:
            $this->db->where("psi.InvoiceNo", $id);
        endif;
        $query = $this->db->get("PS_Invoice as psi");

        return $query->row();
    }

    public function invoice_det_detail($id,$page=""){
        $table = "PS_Invoice_Detail";
        $this->db->select("
            $table.InvoiceDet,
            $table.CompanyID,
            $table.InvoiceNo,
            $table.ReceiveNo,
            $table.DeliveryNo,
            $table.SellNo,
            $table.ReturNo,
            $table.Date,
            $table.DeliveryCost,
            $table.Discount,
            $table.PPN,
            $table.SubTotal,
            $table.Total,
            $table.Remark,

            invoice.Type        as invoiceType,
            invoice.OrderType,
        ");
        $this->db->join("PS_Invoice as invoice", "invoice.InvoiceNo = $table.InvoiceNo and invoice.CompanyID = $table.CompanyID", "left");
        $this->db->where("$table.CompanyID", $this->session->CompanyID);
        $this->db->from($table);
        if($page == "detail"):
            $this->db->where("$table.InvoiceDet", $id);
            $query = $this->db->get();
            $query = $query->row();
        else:
            $this->db->where("$table.InvoiceNo", $id);
            $this->db->order_by("$table.ReturNo");
            $query = $this->db->get();
            $query = $query->result();
        endif;

        return $query;
    }

    #delivery
    public function delivery_detail($id,$page=""){
        $CompanyID = $this->session->CompanyID;
        $this->db->select("
            psd.DeliveryNo,
            psd.CompanyID,
            psd.BranchID,
            psd.VendorID,
            psd.SalesID,
            psd.SellNo,
            psd.PurchaseNo,
            psd.Date,
            psd.DeliveryTo,
            psd.Address,
            psd.City,
            psd.Province,
            psd.Status,
            psd.Remark,
            psd.Payment,

            pss.Name as salesName,
            ");
        $this->db->join("PS_Sales as pss", "psd.SalesID = pss.SalesID", "left");
        $this->db->where("psd.CompanyID", $CompanyID);
        if($page == "selling"):
            $this->db->where("psd.SellNo", $id);
        else:
            $this->db->where("psd.DeliveryNo", $id);
        endif;
        $query = $this->db->get("PS_Delivery as psd");

        return $query->row();
    }

    #receive
    public function receive_detail($id,$page=""){
        $CompanyID = $this->session->CompanyID;
        $this->db->select("
            apg.ReceiveNo,
            apg.CompanyID,
            apg.BranchID,
            apg.VendorID,
            apg.SalesID,
            apg.PurchaseNo,
            apg.Date,
            apg.Status,
            apg.Remark,
            apg.Payment,

            pss.Name as salesName,
            ");
        $this->db->join("PS_Sales as pss", "apg.SalesID = pss.SalesID", "left");
        $this->db->where("apg.CompanyID", $CompanyID);
        if($page == "purchase_order"):
            $this->db->where("apg.PurchaseNo", $id);
        else:
            $this->db->where("apg.ReceiveNo", $id);
        endif;
        $query = $this->db->get("AP_GoodReceipt as apg");

        return $query->row();
    }

    // 20190102 MW
    #payment
    public function payment_list($id="",$page=""){
        if($id == "" and $page == ""):
            $id     = $this->input->post('id');
            $page   = $this->input->post('page');
        endif;

        $this->db->select("
            psp.PaymentNo,
            psp.CompanyID,
            psp.BranchID,
            psp.VendorID,
            psp.SellNo,
            psp.Date,
            psp.TotalAwal,
            psp.Total,
        ");
        if(!$id):
            $this->db->where("type", 999);
        endif;
        if($page == "selling"):
            $this->db->where("psp.Type", 3);
            $this->db->where("psp.SellNo", $id);
        endif;
        $this->db->where("psp.CompanyID", $this->session->CompanyID);
        $query = $this->db->get("PS_Payment as psp");

        return $query->result();
    }

    public function payment_detail($id,$page=""){
        $this->db->select("
            psp.PaymentNo,
            psp.CompanyID,
            psp.BranchID,
            psp.VendorID,
            psp.SellNo,
            psp.Date,
            psp.TotalAwal,
            psp.Total,
            psp.UnPayment,
            psp.BankName,
            psp.AccountName,
            psp.PaymentType,
            psp.Type,

            (case
                when psp.Type = 0 or psp.Type = 1 or psp.Type = 3 then 'Selling No'
                when psp.Type = 2 then 'Purchase No'
                else 'Not found'
            end) as Typetxt,

            (case
                when psp.Type = 0 or psp.Type = 1 or psp.Type = 3 then psp.SellNo
                when psp.Type = 2 then psp.SellNo
                else 'Not found'
            end) as Code,

            (case
                when psp.PaymentType = 0 then 'Cash'
                when psp.PaymentType = 1 then 'Giro'
                when psp.PaymentType = 2 then 'Transfer'
                else 'Not found'
            end) as PaymentTypetxt,

            (case
                when psp.PaymentType = 0 then 'Account No'
                when psp.PaymentType = 1 then 'Giro No'
                when psp.PaymentType = 2 then 'Account No'
                else 'Not found'
            end) as accounttxt,

            (case
                when psp.PaymentType = 0 then '-'
                when psp.PaymentType = 1 then psp.GiroNo
                when psp.PaymentType = 2 then psp.AcountNo
                else 'Not found'
            end) as accountnotxt,

            coa.Name as coaName,
            customer.Name as customerName,
        ");
        $this->db->join("PS_Vendor as customer", "psp.VendorID = customer.VendorID", "left");
        $this->db->join("AC_COA as coa", "psp.PaymentMethod = coa.COAID", "left");
        $this->db->where("psp.CompanyID", $this->session->CompanyID);
        $this->db->where("psp.PaymentNo", $id);
        if($page == "selling"):
            $this->db->where("psp.Type", 3);
        endif;
        $query = $this->db->get("PS_Payment as psp");

        return $query->row();
    }

    // 20190103 MW
    #return
    public function return_list($id="",$page=""){
        if($id == "" and $page == ""):
            $id     = $this->input->post('id');
            $page   = $this->input->post('page');
        endif;
        $this->db->select("
            return.ReturNo,
            return.CompanyID,
            return.VendorID,
            return.ReceiveNo,
            return.SellNo,
            return.Type,
            return.Date,
            return.Remark,

            vendor.Name as vendorName,
        ");
        $this->db->join("PS_Vendor as vendor", "return.VendorID = vendor.VendorID", "left");
        $this->db->where("return.CompanyID", $this->session->CompanyID);
        if($page == "selling"):
            $this->db->where("return.Type", 2);
            $this->db->where("return.SellNo", $id);
            $this->db->where("return.BranchID is null");
        endif;
        $query = $this->db->get("AP_Retur as return");

        return $query->result();
    }

    public function return_detail($id,$page=""){
        $this->db->select("
            return.ReturNo,
            return.CompanyID,
            return.VendorID,
            return.ReceiveNo,
            return.SellNo,
            return.DeliveryNo,
            return.Type,
            return.ReturType,
            return.Date,
            return.Remark,
            vendor.Name as vendorName,

            sum(returndet.Total) as Total,

            (case
                when return.Type = 1 then return.ReceiveNo
                when return.Type = 2 then return.SellNo
                else 'not found'
            end) as id,

            (case
                when return.Type = 1 then 'Receive No'
                when return.Type = 2 then 'Selling No'
                else 'not found'
            end) as idtxt,

            (case
                when return.Type = 1 then 'Vendor Name'
                when return.Type = 2 then 'Customer Name'
                else 'not found'
            end) as customertxt,

        ");
        $this->db->join("PS_Vendor      as vendor", "return.VendorID = vendor.VendorID", "left");
        $this->db->join("AP_Retur_Det   as returndet", "returndet.ReturNo = return.ReturNo and returndet.CompanyID = return.CompanyID", "left");
        $this->db->where("return.CompanyID", $this->session->CompanyID);
        if($page == "selling"):
            $this->db->where("return.Type", 2);
        endif;
        $this->db->where("return.ReturNo", $id);
        $query = $this->db->get("AP_Retur as return");

        return $query->row();
    }

    public function return_by_detail($id,$page=""){
        $this->db->select("
            returndet.ReturDet,
            returndet.CompanyID,
            returndet.ReturNo,
            returndet.ReceiveNo,
            returndet.ReceiveDet,
            returndet.SellNo,
            returndet.SellDet,
            returndet.ProductID,
            returndet.UnitID,
            returndet.Qty,
            returndet.Conversion,
            returndet.Price,
            returndet.Total,
            returndet.Type,
            returndet.Complete,
            returndet.SerialNumber,
            returndet.Remark,

            product.Code as product_code,
            product.Name as product_name,

            ps_unit.Name unit_name,
        ");
        $this->db->join("AP_Retur as return", "returndet.ReturNo = return.ReturNo and returndet.CompanyID = return.CompanyID", "left");
        $this->db->join("ps_product as product", "returndet.ProductID = product.ProductID", "left");
        $this->db->join("ps_unit","returndet.UnitID = ps_unit.UnitID", "left");
        $this->db->where("returndet.CompanyID", $this->session->CompanyID);
        if($page == "selling"):
            $this->db->where("return.Type", 2);
        endif;
        $this->db->where("return.ReturNo", $id);
        $query = $this->db->get("AP_Retur_Det as returndet");

        return $query->result();
    }

    #product
    public function tambahQty($ProductID,$Qty){
        $this->db->query(
            "UPDATE ps_product set 
                Qty=Qty+$Qty
            WHERE
                ProductID = '$ProductID'
        ");
    }

    public function create_serial($productid,$product_qty){
        $sn  = array();
        $a              = $this->main->product("select",$productid);
        $serial_format  = $a->serial_format;
        $explodesn      = explode("/", $serial_format);
        $countsn        = count($explodesn);
        $digit          = strlen($explodesn[$countsn-1]);
        $serial_format  = str_replace("YEAR",date("y"),$serial_format);
        $serial_format  = str_replace("MONTH",date("m"),$serial_format);
        $serial_format  = substr($serial_format, 0,-$digit);
        $serial_format  = str_replace("/", "",$serial_format);
        if($serial_format == "auto"):
            $awalan  = date("ym");
            $lebar   = 6;
        else:
            $awalan  = $serial_format;
            $lebar   = $digit;
        endif;     
        foreach (range(1, $product_qty) as $key => $a):
            if($key == 0):
                if($serial_format == "auto"):
                    $serial_number  = $this->main->autoNumber("PS_Product_Serial","SerialNo",6,date("ym"),$productid);
                else:
                    $serial_number  = $this->main->autoNumber("PS_Product_Serial","SerialNo",$digit,$serial_format,$productid);
                endif;
            else:
                $nomor = intval(substr($serial_number,strlen($awalan))) + 1;
                $angka = $awalan.str_pad($nomor,$lebar,"0",STR_PAD_LEFT);
                $serial_number = $angka;
            endif;
            $h['productserialid'] = '';
            $h['serialnumber']    = $serial_number;
            array_push($sn, $h);
        endforeach;
        $sn = json_encode($sn);
        $sn = json_decode($sn);
        return $sn;
    }

     public function update_company($where, $data)
    {
        // $this->db->set("UserCh",$this->session->NAMA);
        // $this->db->set("DateCh",date("Y-m-d H:i:s"));
        $this->db->update("user", $data, $where);
        return $this->db->affected_rows();
    }

    public function save_rekening($data)
    {
        $this->db->set("UserAdd",$this->session->NAMA);
        $this->db->set("DateAdd",date("Y-m-d H:i:s"));
        $this->db->insert("user_rekening", $data);
        return $this->db->insert_id();  
    }
    public function update_rekening($where, $data)
    {
        $this->db->set("UserCh",$this->session->NAMA);
        $this->db->set("DateCh",date("Y-m-d H:i:s"));
        $this->db->update("user_rekening", $data, $where);
        return $this->db->affected_rows();
    }
    public function delete_rekening($array){
        if(count($array)>0):
            $this->db->where_not_in("UserRekID",$array);
            $this->db->where("CompanyID", $this->session->CompanyID);
            $this->db->delete("user_rekening");
        endif;
    }

    function get_rekening($UserID){
        $this->db->select("
            UserRekID,
            BankName,
            ISNULL(BankBranch, '') as BankBranch,
            AnRekening,
            NoRekening,
            ");
        $this->db->where("UserRekID", $UserRekID);
        $this->db->order_by("UserRekID");
        $query = $this->db->get("user_rekening");

        return $query->result();
    }

    public function save_module($data)
    {
        $this->db->insert("VoucherPackage", $data);
        return $this->db->insert_id();  
    }
    public function update_module($where, $data)
    {
        $this->db->update("VoucherPackage", $data, $where);
        return $this->db->affected_rows();
    }
    public function delete_module($array){
        if(count($array)>0):
            $this->db->where_not_in("VoucherPackageID",$array);
            $this->db->delete("VoucherPackage");
        endif;
    }

    public function get_product_customer($id="",$page=""){
        $this->db->select("Status");
        $this->db->where("CompanyID", $this->session->CompanyID);
        $this->db->from("ps_product_customer");
        
        if($page == "detail"):
            $this->db->where("ProductCustomerID",$id);
            $query = $this->db->get()->row();
        endif;

        return $query;
    }

    public function template_select(){
        $select = $this->input->post("select");
        $type   = $this->input->post("type");
        $default = 'aset/images/noimage.jpg';
        $table = "Template";
        $this->db->select("
            $table.TemplateID as ID,
            $table.Name,
            $table.Type,
            ifnull($table.Image,'$default') as Image,
        ");

        if($select == "active"):
            $this->db->where("$table.Status", 1);
        endif;
        if($type):
            $this->db->where("$table.Type", $type);
        endif;
        $this->db->from($table);
        $query = $this->db->get();

        return $query->result();
    }

    public function voucher(){
        $CompanyID  = $this->session->CompanyID;
        $table      = "VoucherDetail";

        $Module = 1;

        $this->db->select("
            Voucher.Code    as no,
            $table.Code     as code,
            (case 
            when Voucher.Type = 24 THEN '2 Year'
            when Voucher.Type = 12 THEN '1 Year'
            when Voucher.Type = 6 THEN '6 Month'
            when Voucher.Type = 3 THEN '3 Month'
            when Voucher.Type = 1 THEN '1 Month' else 'none' end)   as Type,
        ");
        $this->db->join("Voucher", "Voucher.VoucherID = $table.VoucherID");
        $this->db->where("$table.CompanyID", $CompanyID);
        $this->db->where("$table.Status", "not");
        $this->db->where("Voucher.Module", $Module);
        $this->db->from($table);
        $query = $this->db->get();

        return $query->result();
    }

    public function attachment_list($Type,$ID="",$page=""){
        $CompanyID = $this->session->CompanyID;

        $table = "PS_Attachment";
        $this->db->select("
            $table.AttachmentID as attachID,
            $table.CompanyID,
            $table.ID,
            $table.Name,
            $table.Image,
            $table.Type,
        ");

        if($page == "array"):
            $this->db->where_in("Type",$Type);
        else:
            $this->db->where("CompanyID", $CompanyID);
            $this->db->where("Type", $Type);
            $this->db->where("ID", $ID);
        endif;
        $this->db->from($table);
        $query = $this->db->get();

        return $query->result();
    }

    public function user($page){
        $CompanyID = $this->session->CompanyID;
        $this->db->select("
            hak_akses,
            id_user,
            email,
            nama,
            VoucherExpireDate,
        ");
        $this->db->where("CompanyID", $CompanyID);
        $this->db->where("CompanyID is not null");
        if($page == "additional"):
            $this->db->where("hak_akses", "additional");
        endif;
        $query = $this->db->get("user");

        return $query->result();
    }

    #serial number
    public function temp_serial($page,$SN,$Class,$ProductID,$p2=""){
        $CompanyID  = $this->session->CompanyID;
        $UserID     = $this->session->UserID;

        $this->db->select("CompanyID,UserID,ProductID,Class,Page,SN");
        $this->db->where("Page", $page);
        $this->db->where("CompanyID", $CompanyID);
        $this->db->where("UserID", $UserID);
        $this->db->where("ProductID", $ProductID);
        if($p2=="list"):
            $this->db->where("Class", $Class);
            $Qty = $this->input->post("Qty");
            if(is_numeric($Qty)):
                $this->db->limit($Qty);
            endif;
        elseif($p2 == "class"):
            $this->db->where("Class", $Class);
        else:
            $this->db->where_in("SN", $SN);
        endif;
        $query = $this->db->get("Temp_Serial_Number");

        return $query->result();
    }
    public function good_receipt_serial($page,$ProductID,$headerID="",$detailID="",$sn=""){
        $CompanyID = $this->session->CompanyID;
        $table = "AP_GoodReceipt_Det_SN";
        $this->db->select("
            ReceiveNo,
            ReceiveDet,
            ProductID,
            Qty,
            SN,
            Status,
        ");
        $this->db->where("CompanyID", $CompanyID);
        $this->db->where("ProductID", $ProductID);
        $this->db->where("ReceiveNo", $headerID);
        $this->db->where("ReceiveDet", $detailID);
        if($page == "data_active"):
            $this->db->where("Status", 1);
        elseif($page == "autocomplete"):
            $this->db->where("Status", 1);
            $this->db->like("SN",$sn);
        endif;
        $query = $this->db->get($table);

        return $query->result();

    }
    public function selling_serial($page,$ProductID,$headerID="",$detailID="",$sn=""){
        $CompanyID = $this->session->CompanyID;
        $table = "PS_Sell_Detail_SN";
        $this->db->select("
            SellNo,
            SellDet,
            ProductID,
            Qty,
            SN,
            Status,
        ");
        $this->db->where("CompanyID", $CompanyID);
        $this->db->where("ProductID", $ProductID);
        $this->db->where("SellNo", $headerID);
        $this->db->where("SellDet", $detailID);
        if($page == "data_active"):
            $this->db->where("Status", 1);
        elseif($page == "autocomplete"):
            $this->db->where("Status", 1);
            $this->db->like("SN",$sn);
        endif;
        $query = $this->db->get($table);

        return $query->result();

    }
    public function delivery_serial($page,$ProductID,$headerID="",$detailID="",$sn=""){
        $CompanyID = $this->session->CompanyID;
        $table = "PS_Delivery_Det_SN";
        $this->db->select("
            DeliveryNo,
            DeliveryDet,
            ProductID,
            Qty,
            SN,
            Status,
        ");
        $this->db->where("CompanyID", $CompanyID);
        $this->db->where("ProductID", $ProductID);
        $this->db->where("DeliveryNo", $headerID);
        $this->db->where("DeliveryDet", $detailID);
        if($page == "data_active"):
            $this->db->where("Status", 1);
        elseif($page == "autocomplete"):
            $this->db->where("Status", 1);
            $this->db->like("SN",$sn);
        endif;
        $query = $this->db->get($table);

        return $query->result();

    }

    #product branch
    public function product_branch($BranchID,$ProductID,$page=""){
        $CompanyID  = $this->session->CompanyID;
        $table      = "PS_Product_Branch";
        $this->db->select("ifnull(Qty,0) as Qty,ProductID");
        $this->db->where("CompanyID", $CompanyID);
        $this->db->where("BranchID", $BranchID);
        if($page == "array"):
            $this->db->where_in("ProductID", $ProductID);
        else:
            $this->db->where("ProductID", $ProductID);
        endif;
        $query = $this->db->get($table);

        return $query->result();
    }

    #20190819 MW
    #general settings
    public function general_settings($page="",$Code=""){
        $table = "UT_General";
        $this->db->select("
            Code,
            Name,
            Value,
        ");
        $this->db->from($table);
        if($page == "array"):
            $this->db->where_in("Code", $Code);
        endif;
        $query = $this->db->get();

        return $query->result();
    }
    // 29102019
    public function save_slideshow($data)
    {
        $this->db->set("UserAdd",$this->session->Name);
        $this->db->set("DateAdd",date("Y-m-d H:i:s"));
        $this->db->insert('UT_Attachment', $data);
        return $this->db->insert_id();
    }
    public function slideshow($modul = "",$id = "",$language=""){
        $page_setting = $this->input->post('page_setting');

        $table      = "UT_Attachment";
        $default    = site_url("aset/img/default.png");
        $data       = array();
        if($modul == "list_data"):
            $this->db->select("
                UT_Attachment.AttachmentID,
                UT_Attachment.ParentID,
                UT_Attachment.AttachmentID as ID,
                UT_Attachment.Name,
                UT_Attachment.NameColor,
                UT_Attachment.Description,
                UT_Attachment.Type,
                UT_Attachment.Sort,
                UT_Attachment.Active,
                (CASE 
                WHEN UT_Attachment.Patch IS NOT NULL THEN CONCAT('$this->host/',UT_Attachment.Patch) 
                WHEN UT_Attachment.Patch = '/' THEN '$default' 
                ELSE  '$this->host/aset/img/default.png' END) as Patch,
                UT_Attachment.Position,
                UT_Attachment.ButtonLink,
                UT_Attachment.Language,

                eng.AttachmentID as AttachmentIDeng,
                eng.ParentID,
                eng.AttachmentID as ID,
                eng.Name as Nameeng,
                eng.NameColor as NameColoreng,
                eng.Description as Descriptioneng,
                eng.Type as Typeeng,
                eng.Sort as Sorteng,
                eng.Active as Activeeng,
                eng.Position as Positioneng,
                eng.ButtonLink as ButtonLinkeng,
                eng.Language
            ");
            $this->db->join("UT_Attachment as eng", "UT_Attachment.AttachmentID = eng.ParentID", "left");
            $language   = $this->session->bahasa;
            if($page_setting):
                $this->db->where("UT_Attachment.Language", 1);
            else:
                if($language == "indonesia"):
                    $this->db->where("UT_Attachment.Language", 1);
                else:
                    $this->db->where("UT_Attachment.Language", 2);
                endif;
            endif;
            $this->db->where("UT_Attachment.Type","slideshow");
            $this->db->order_by("UT_Attachment.Sort","asc");
            $query = $this->db->get("UT_Attachment");
            $data  = $query->result();
        elseif($modul == "last_sort"):
            $this->db->select("UT_Attachment.Sort");
            $this->db->where("UT_Attachment.Type","slideshow");
            $this->db->order_by("UT_Attachment.Sort","desc");
            $this->db->limit(1);
            $query = $this->db->get("UT_Attachment");
            $query = $query->row();
            if($query):
                $data = $query->Sort;
            else:
                $data = 1;
            endif;
        elseif($modul == "edit"):
            $this->db->select("
                UT_Attachment.AttachmentID,
                UT_Attachment.AttachmentID as ID,
                UT_Attachment.Name,
                UT_Attachment.NameColor,
                UT_Attachment.Description,
                UT_Attachment.Type,
                UT_Attachment.Sort,
                UT_Attachment.Active,
                (CASE 
                WHEN UT_Attachment.Patch IS NOT NULL THEN CONCAT('$this->host/',UT_Attachment.Patch) 
                WHEN UT_Attachment.Patch = '/' THEN '$default' 
                ELSE  '$this->host/aset/img/default.png' END) as Patch,
                UT_Attachment.Position,
                UT_Attachment.ButtonLink,

                eng.AttachmentID as AttachmentIDeng,
                eng.ParentID,
                eng.AttachmentID as ID,
                eng.Name as Nameeng,
                eng.NameColor as NameColoreng,
                eng.Description as Descriptioneng,
                eng.Type as Typeeng,
                eng.Sort as Sorteng,
                eng.Active as Activeeng,
                (CASE 
                WHEN eng.Patch IS NOT NULL THEN CONCAT('$this->host/',eng.Patch) 
                WHEN eng.Patch = '/' THEN '$default' 
                ELSE  '$this->host/aset/img/default.png' END) as Patch,
                eng.Position as Positioneng,
                eng.ButtonLink as ButtonLinkeng,
                eng.Language as Languageeng
            ");
            $this->db->join("UT_Attachment as eng", "UT_Attachment.AttachmentID = eng.ParentID", "left");
            $this->db->where("UT_Attachment.AttachmentID",$id);
            $query = $this->db->get("UT_Attachment");
            $data  = $query->row();
        endif;
        return $data;
    }
    // 29102019

    // 30102019
    public function module_user($modul = "",$id = ""){
        if($modul == "list_data"):
            $this->db->select("
                VoucherPackageID,
                App,
                Type,
                Module,
                Price
            ");
            $this->db->where("VoucherPackage.App","oneapp");
            $query = $this->db->get("VoucherPackage");
            $data  = $query->result();
            return $query->result();
        endif;
        return $data;
    }
    // 30102019

    // 31102019

    public function get_module($VoucherPackageID){
        $this->db->select("
            VoucherPackageID,
            Type,
            App,
            Module,
            Price,
            ");
        $this->db->where("VoucherPackageID", $VoucherPackageID);
        $this->db->order_by("VoucherPackageID");
        $query = $this->db->get("VoucherPackage");

        return $query->result();
    }
    // 31102019
    // 01112019
    public function voucher_list(){
        $this->db->select('Type,Module,Price');
        $this->db->from('VoucherPackage');
        $query = $this->db->get()->result();

        $data = array();
        $temp_module = '';
        foreach ($query as $k => $v) {
            $status = true;
            if($v->Type >11):
                $year = $v->Type / 12;
                $v->label = $year.' '.$this->lang->line('lb_year');
            else:
                $v->label = $v->Type.' '.$this->lang->line('lb_month');
            endif;

            if($v->Module == 2):
                if($temp_module == $v->Price):
                    $status = false;
                endif;
                $v->label = $this->lang->line('additional');
                $temp_module = $v->Price;
                $v->label2 = $this->lang->line('user');
            else:
                $v->label2 = $this->lang->line('module');
            endif;

            $v->label3 = $this->lang->line('lb_month');
            $v->Pricetxt = $this->main->currency($v->Price);
            if($status):
                array_push($data,$v);
            endif;
        }
        $this->echoJson($data);
        return $data;
    }
    // 01112019

}