<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_android extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set("Asia/Jakarta");
    }

    public function autoNumber($tabel, $kolom, $lebar=0, $awalan) {
        $this->db->select("$kolom");
        $this->db->order_by($kolom, "desc");
        $this->db->limit(1);
        $this->db->from($tabel);
        $query = $this->db->get();
        $rslt = $query->result_array();


        $total_rec = $query->num_rows();
        if ($total_rec == 0) {
        $nomor = 1;
        } else {
        $nomor = intval(substr($rslt[0][$kolom],strlen($awalan))) + 1;
        }

        if($lebar > 0) {
            $angka = $awalan.str_pad($nomor,$lebar,"0",STR_PAD_LEFT);
        } else {
            $angka = $awalan.$nomor;
        }

         return $angka;
    }

    public function version_app($app_name){
        $this->db->where("app_name", $app_name);
    	$query = $this->db->get("version_app");

    	return $query;
    }

    public function tokenDevice($token){
        $this->db->select("
            BranchID,
            Code,
            Name,
            b.CompanyID,
            u.nama,
            u.img_url,
            ");
        $this->db->join("user as u", "b.CompanyID = u.id_user");
        $this->db->where("b.Token", $token);
        $query = $this->db->get("Branch as b");

        return $query;
    }

    //"store": "[{\"branchid\":\"20\"},{\"branchid\":\"21\"},{\"branchid\":\"22\"},{\"branchid\":\"23\"}]"
    
    public function user($id_user){
        $this->db->select("first_name,last_name");
        $this->db->where("id_user", $id_user);
        $query = $this->db->get("user");
        if($query->num_rows()>0):
            $d = $query->row();
            $first_name = $d->first_name;
            $last_name  = $d->last_name;

            $name = $first_name." ".$last_name;
        else:
            $name = "";
        endif;

        return $name;
    }

    public function cekBranch($id_user, $branchID){
        $this->db->select("store");
        $this->db->where("id_user", $id_user);
        $query = $this->db->get("user");
        $d = $query->row();
        $store = json_decode($d->store);
        if (!empty($store)):
            foreach ($store as $data) {
                if ($data->branchid == $branchID):
                    $res["status"]      = true;
                    $res["hak_akses"]   = $data->hakakses;
                else:
                    // $res["status"] = false;
                endif;
            }
        else:
            $res["status"] = false;
        endif;

        return $res;
    }

    public function Branch($data){
        $this->db->select("BranchID,Name,Email,Phone,App,Active,Password,AutoCheckOut,StatusAccount,ExpireAccount");
        $this->db->where($data);
        $query = $this->db->get("Branch");

        return $query;
    }

    public function get_code_user($data){
    	$this->db->select("kode_user,id_user");
    	$this->db->where("email", $data["email"]);
    	$this->db->where("password", $data["password"]);
    	if($data["check_token"] != 'false'):
    		$this->db->where("token", $data["token"]);
    	endif;
    	
    	$d = $this->db->get("user");

    	return $d;
    }

    public function get_user_detail($kode_user){
    	$this->db->select('*');
    	$this->db->where("kode_user", $kode_user);
    	return $this->db->get("user")->row();
    }

    #customer_______________________________________________________________________________
    public function get_customer($companyID){
        $this->db->select('*');
        $this->db->where("CompanyID", $companyID);
        $this->db->where("Position", 0);
        $this->db->where("Active", 1);
        $this->db->where_in("App", array('all','pipesys'));
        $this->db->order_by("Name");
        $query = $this->db->get("PS_Vendor");

        return $query;
    }
    public function get_customer_branch($companyID, $branchID){
        $this->db->select('*');
        $this->db->where("Position", 2);
        $this->db->where_in("App", array('all','pipesys'));
        $this->db->where("CompanyID", $companyID);
        $this->db->where("Active", 1);
        // $this->db->where("BranchID", $branchID);
        $this->db->order_by("Name");
        $query = $this->db->get("PS_Vendor");

        return $query;
    }

    #end customer___________________________________________________________________________

    //from table PS_Mutation_Detail
    public function max_qty_product($companyID,$branchID){
        $this->db->select("
            ProductBranchID,
            ps_p_b.ProductID,
            ps_p_b.Qty,
            ps_p.Code,
        ");
        $this->db->join("ps_product as ps_p", "ps_p_b.ProductID = ps_p.ProductID");
        $this->db->where("ps_p_b.CompanyID", $companyID);
        $this->db->where("ps_p_b.BranchID", $branchID);
        $query = $this->db->get("PS_Product_Branch as ps_p_b");
        return $query;
    }

    //from table PS_Mutation_Detail
    public function get_product($companyID, $branchID){
        $this->db->select("
            ProductBranchID,
            ps_p_b.ProductID,
            ps_p_b.Qty,
            unit.ProductUnitID as UnitID,
            ps_p.SellingPrice,
            ps_p.Name,
            ps_p.Code,
            ps_p.Type,
            ps_p.ParentCode,
            category.Name as categoryName,
            ps_p.Active,
            unit.Uom as unitName,
            ");
        $this->db->join("ps_product as ps_p", "ps_p_b.ProductID = ps_p.ProductID");
        $this->db->join("ps_product as category", "ps_p.ParentCode = category.Code");
        $this->db->join("ps_product_unit as unit", "unit.ProductID = ps_p.ProductID and unit.Uom = ps_p.Uom","left");
        $this->db->where("ps_p_b.CompanyID", $companyID);
        $this->db->where("ps_p_b.BranchID", $branchID);
        $query = $this->db->get("PS_Product_Branch as ps_p_b");
        return $query;
    }

    public function getProductID($productCode){
        $this->db->select("ProductID");
        $this->db->where("Code", $productCode);
        $query = $this->db->get("ps_product");
        $d     = $query->row();

        return $d->ProductID; 
    }

    //data selling
    public function getDataSelling($customer_code){
        $this->db->select("*");
        $this->db->where("VendorID", $customer_code);
        $query = $this->db->get("PS_Sell");

        return $query;
    }

    public function labelSelling($status){
        if($status == 0):
            $status = "Pendding";
        endif;

        return $status;
    }

    #history
    public function get_history_selles($data, $date_from = null, $date_to = null){
        $this->db->select("
            ps_p.Code,
            ps_p.Name,
            ps_s_d.ProductID,
            ps_s_d.Qty,
            ps_s_d.Price,
            ps_s_d.Type,
            ps_u.Name as unit,
            ps_s.Date,
            ");
        $this->db->join("PS_Sell_Detail as ps_s_d", "ps_s.SellNo = ps_s_d.SellNo");
        $this->db->join("ps_product ps_p", "ps_s_d.ProductID = ps_p.ProductID");
        $this->db->join("ps_unit ps_u", "ps_s_d.UnitID = ps_u.UnitID");
        $this->db->where($data);
        if($date_from != null):
            $this->db->where('ps_s.Date >=', $date_from);
        endif;
        if($date_to != null):
            $this->db->where('ps_s.Date <=', $date_to);
        endif;
        $query = $this->db->get("PS_Sell as ps_s");

        return $query;
    }

    public function get_history_payment($data, $date_from = null, $date_to = null){
        $this->db->select("
            Date,
            Total,
            ");
        $this->db->where($data);
        if($date_from != null):
            $this->db->where('Date >=', $date_from);
        endif;
        if($date_to != null):
            $this->db->where('Date <=', $date_to);
        endif;
        $query = $this->db->get("PS_Payment");

        return $query;
    }

    #get syncron data_______________________________________________________________________________

    public function get_selling($companyID, $branchID){
        $this->db->select("
            SellNo,
            VendorID,
            PaidAndroid,
            Paid,
            Total,
            Payment,
            Change,
            Noresep,
            User_Cancel,
            Date_Cancel,
            Remark,
            Discount as Diskon,
            Date,
            Latlng,
            Status,
            ");
        $this->db->where("CompanyID", $companyID);
        $this->db->where("BranchID", $branchID);
        $query = $this->db->get("PS_Sell");

        return $query;
    }

    public function get_sell_detail($sellNo){
        $this->db->select("
            SellDet,
            SellNo,
            ProductID,
            UnitID,
            Conversion,
            Type,
            Qty,
            Price,
            TotalPrice,
            Discount,
            SerialNumber,
            Complete,
            Status,
            ");
        $this->db->where("SellNo", $sellNo);
        $query = $this->db->get("PS_Sell_Detail");

        return $query;
    }

    public function get_sell_detail_sn($companyID, $branchID){
        $this->db->select("SerialNumber");
        $this->db->where("CompanyID", $companyID);
        $this->db->where("BranchID", $branchID);
        $query = $this->db->get("PS_Sell_Detail");

        return $query;
    }

    public function get_conversion_from_mutation($mutationDet){
        $this->db->select("Conversion");
        $this->db->where("MutationDet", $mutationDet);
        $query = $this->db->get("PS_Mutation_Detail");

        return $query;
    }

    public function get_payment($companyID, $branchID){
        $this->db->select("
            PaymentNo,
            CompanyID,
            BranchID,
            SellNo,
            VendorID,
            Date,
            TotalAwal,
            Total,
            GrandTotal,
            Status,
            PaymentType,
            ApproveCode,
            ");
        $this->db->where("CompanyID", $companyID);
        $this->db->where("BranchID", $branchID);
        $query = $this->db->get("PS_Payment");

        return $query;
    }

    public function get_payment_detail($paymentNo){
        $this->db->select("
            PaymentDet,
            PaymentNo,
            CompanyID,
            VendorID,
            SellNo,
            CorrectionNo,
            Total,
            Type,
            ");
        $this->db->where("PaymentNo", $paymentNo);
        $query = $this->db->get("PS_Payment_Detail");

        return $query;
    }

    public function get_return($companyID, $branchID){
        $this->db->select("ReturNo,CompanyID,BranchID,VendorID,SellNo,Type,Date");
        $this->db->where("CompanyID", $companyID);
        $this->db->where("BranchID", $branchID);
        $query = $this->db->get("AP_Retur");

        return $query;
    }
    public function get_return_det($returnNo){
        $this->db->select("
            ReturDet,CompanyID,ReturNo,SellNo,SellDet,ProductID,UnitID,Qty,Conversion,Price,
            Total,Type,Complete,SerialNumber,Remark,
            ");
        $this->db->where("ReturNo", $returnNo);
        $query = $this->db->get("AP_Retur_Det");

        return $query;
    }
    public function getReturnSn($companyID, $branchID){
        $this->db->select("ap_r_d.SerialNumber");
        $this->db->join("AP_Retur_Det as ap_r_d", "ap_r.ReturNo = ap_r_d.ReturNo");
        $this->db->where("ap_r.CompanyID", $companyID);
        $this->db->where("ap_r.BranchID", $branchID);
        $query = $this->db->get("AP_Retur as ap_r");

        return $query;
    }

    public function get_return_id(){
        $this->db->select("ReturDet");
        $this->db->order_by("ReturDet", "DESC");
        $this->db->limit(1);
        $query = $this->db->get("AP_Retur_Det");
        $d = $query->row();

        return $d->ReturDet;
    }

    //mengambil data branch 
    public function get_branch($companyID, $branchID){
        $this->db->select("BranchID,Name");
        $this->db->where("CompanyID", $companyID);
        $this->db->where("BranchID != ", $branchID);
        $query = $this->db->get("Branch");

        return $query;
    }

    public function get_company_info($companyID){
        $this->db->select("nama,email,phone,address,city,province,country,phone_company,postal,fax");
        $this->db->where("id_user", $companyID);
        $query = $this->db->get("user");

        return $query;
    }

    public function get_branch_info($companyID, $branchID){
        $this->db->select("Name,Address,City,Province,Country,Postal,Phone,Lat,Lng,Fax");
        $this->db->where("CompanyID", $companyID);
        $this->db->where("BranchID", $branchID);
        $query = $this->db->get("Branch");

        return $query;
    }

    #end get syncron data___________________________________________________________________________


    #syncron data____________________________________________________________________________________
    public function syncronPaymentAndroid($data, $companyID="", $branchID ="", $username =""){
        $data_p         = json_decode($data);
        if($data_p->status):
            foreach ($data_p->data as $d) {
                $data_insert = array(
                    "PaymentNo" => $d->PaymentNo,
                    "CompanyID" => $companyID,
                    "BranchID"  => $branchID,
                    "VendorID"  => $d->VendorID,
                    "SellNo"    => $d->SellNo,
                    "Date"      => $d->Date,
                    "TotalAwal" => $d->TotalAwal,
                    "Total"     => $d->Total,
                    "GrandTotal"=> $d->GrandTotal,
                    "Type"      => 1,
                    "PaymentType" => $d->Type,
                    "status"    => 0,
                    "User_Add"  => $username,
                    "Date_Add"  => $d->Date,
                    );
                if($d->Type != 0):
                    $data_insert["ApproveCode"] = $d->ApproveCode;
                endif;
                $cek = $this->db->count_all("PS_Payment where CompanyID = '$companyID' AND BranchID = '$branchID' AND PaymentNo = '$d->PaymentNo'");
                if($cek<1){
                    $this->db->insert("PS_Payment", $data_insert);
                }
            }
        endif;
    }

    public function syncronSellAndroid($data, $username="", $AC = "", $return){
        $data = json_decode($data);
        if ($data->status):
            foreach ($data->data as $d) {
                $data_PS_Sell = array(
                    "SellNo"    => $d->SellNo,
                    "CompanyID" => $d->CompanyID,
                    "BranchID"  => $d->BranchID,
                    "VendorID"  => $d->VendorID,
                    "Paid"      => 0,
                    "PaidAndroid"  => $d->PaidAndroid,
                    "Payment"   => $d->Payment,
                    "Total"     => $d->Total,
                    "Date"      => $d->Date,
                    "User_Add"  => $username,
                    "Date_Add"  => $d->Date,
                    );
                $data_AC = array(
                    "BalanceNo" => $AC,
                    "CompanyID" => $d->CompanyID,
                    "BranchID"  => $d->BranchID,
                    "VendorID"  => $d->VendorID,
                    "Date"      => $d->Date,
                    "Total"     => $d->Total,
                    "Type"      => 1,
                    "User_Add"  => $username,
                    "Date_Add"  => date("Y-m-d H:i:s"),
                    );
                $cek = $this->db->count_all("PS_Sell where CompanyID = '$d->CompanyID' AND BranchID = '$d->BranchID' AND SellNo = '$d->SellNo'");
                if($cek<1):
                    $this->db->insert("PS_Sell", $data_PS_Sell);
                    $this->AC_CorrectionPR($d->CompanyID, $d->BranchID, $username,$AC);
                    $this->db->insert("AC_CorrectionPR_Det", $data_AC);
                    $this->syncronSellDetAndroid($d->selldetail, $username);
                else:
                    if($d->status == 2):
                        $this->syncronSellDetAndroid($d->selldetail, $username);
                    endif;
                endif;
            }
        endif;
        $this->syncronReturnAndroid($return, $username,$AC);
    }
    public function syncronSellDetAndroid($data, $username =""){
        if($data->status):
            foreach ($data->data as $d) {
                $data_PS_Sell_Detail = array(
                    "SellDet"   => $d->SellDet,
                    "SellNo"    => $d->SellNo,
                    "CompanyID" => $d->CompanyID,
                    "BranchID"  => $d->BranchID,
                    "ProductID" => $d->ProductID,
                    "Qty"       => $d->Qty,
                    "UnitID"    => $d->UnitID,
                    "Conversion" => $this->add_conversion($d->CompanyID,$d->UnitID),
                    "Type"      => $d->Type,
                    "Price"     => $d->Price,
                    "TotalPrice"=> $d->TotalPrice,
                    "SerialNumber" => $d->SerialNumber,
                    "Discount"  => $d->Discount,
                    "User_Add"  => $username,
                    "Date_Add"  => date("Y-m-d H:i:s"),
                    );
                $cek = $this->db->count_all("PS_Sell_Detail where SellDet = '$d->SellDet' AND CompanyID = '$d->CompanyID' AND BranchID = '$d->BranchID'");
                if($cek<1):
                    $this->db->insert("PS_Sell_Detail", $data_PS_Sell_Detail);
                    $this->updateQtyProductBranch($d->CompanyID, $d->BranchID, $d->ProductID, $d->Qty, $username);
                else:
                    if($d->status == 2):
                        $data_update = array(
                            "SerialNumber"  => $d->SerialNumber,
                            "User_Ch"       => $username,
                            "Date_Ch"       => date("Y-m-d H:i:s"),
                            );
                        $this->db->where("SellDet", $d->SellDet);
                        $this->db->update("PS_Sell_Detail", $data_update);
                    endif;
                endif;
            }
        endif;
    }
    public function updateQtyProductBranch($companyID, $branchID, $productID, $qty, $username){
        $date = date("Y-m-d H:i:s");
        $this->db->query(
            "UPDATE PS_Product_Branch set 
                Qty=Qty-$qty 
            WHERE 
                CompanyID = '$companyID' AND 
                BranchID  = '$branchID' AND 
                ProductID = '$productID'
                ");
    }
    public function addQtyProductCompany($companyID, $productID, $qty, $username){
        $date = date("Y-m-d H:i:s");
        $this->db->query(
            "UPDATE ps_product set 
                Qty=Qty+$qty
            WHERE 
                CompanyID = '$companyID' AND 
                ProductID = '$productID'
                ");
    }
    public function addQtyProductBranch($companyID, $branchID, $productID, $qty){
        $this->db->query(
            "UPDATE PS_Product_Branch set 
                Qty=Qty+$qty 
            WHERE 
                CompanyID = '$companyID' AND 
                BranchID  = '$branchID' AND 
                ProductID = '$productID'
                ");
    }

    var $companyID = "";
    var $branchID  = "";
    var $vendorID  = "";
    var $date      = "";
    public function syncronReturnAndroid($data, $username="", $AC=""){
        $data = json_decode($data);
        if ($data->status):
            foreach ($data->data as $d) {
                $data_return = array(
                    "ReturNo"   => $d->ReturNo,
                    "CompanyID" => $d->CompanyID,
                    "BranchID"  => $d->BranchID,
                    "VendorID"  => $d->VendorID,
                    "SellNo"    => $d->SellNo,
                    "Type"      => 2,
                    "Date"      => $d->Date,
                    "User_Add"  => $username,
                    "Date_Add"  => date("Y-m-d H:i:s"),
                    );
                $cek = $this->db->count_all("AP_Retur where CompanyID = '$d->CompanyID' AND BranchID = '$d->BranchID' AND ReturNo = '$d->ReturNo'");
                if($cek<1):
                    $this->db->insert("AP_Retur", $data_return);
                    $this->companyID    = $d->CompanyID;
                    $this->branchID     = $d->BranchID;
                    $this->vendorID     = $d->VendorID;
                    $this->date         = $d->Date;
                    $this->AC_CorrectionPR($this->companyID, $this->branchID, $username,$AC);
                    $this->syncronReturnDetAndroid($d->returnDet, $username, $AC);
                endif;
            }
        endif;
    }
    private function syncronReturnDetAndroid($data, $username ="", $AC = ""){
        if ($data->status):
            $Total = 0;
            foreach ($data->data as $d) {
                $data_return_det = array(
                    "CompanyID"     => $d->CompanyID,
                    "ReturNo"       => $d->ReturNo,
                    "SellNo"        => $d->SellNo,
                    "SellDet"       => $d->SellDet,
                    "ProductID"     => $d->ProductID,
                    "UnitID"        => $d->UnitID,
                    "Conversion"    => $this->add_conversion_return($d->SellDet),
                    "Qty"           => $d->Qty,
                    "Price"         => $d->Price,
                    "Total"         => $d->Total,
                    "Type"          => $d->Type,
                    "Complete"      => $d->Complete,
                    "SerialNumber"  => $d->SerialNumber,
                    "Remark"        => $d->Remark,
                    "User_Add"      => $username,
                    "Date_Add"      => date("Y-m-d H:i:s"),
                    );
                $Total = $Total+$d->Total;
                $this->db->insert("AP_Retur_Det", $data_return_det);
                $this->addQtyProductBranch($d->CompanyID, $d->BranchID, $d->ProductID, $d->Qty);

                // $this->add_returnDet($d->SerialNumber, $d->CompanyID, $d->ProductID);
            }
            $data_AC = array(
                "BalanceNo" => $AC,
                "CompanyID" => $this->companyID,
                "BranchID"  => $this->branchID,
                "VendorID"  => $this->vendorID,
                "Date"      => $this->date,
                "Total"     => $Total,
                "Type"      => 2,
                "User_Add"  => $username,
                "Date_Add"  => date("Y-m-d H:i:s"),
                );
            $this->db->insert("AC_CorrectionPR_Det", $data_AC);
        endif;
    }

    public function add_returnDet($data="", $companyID, $productID){
        // $data = '[{"MutationDet":"MTD180200013","SerialNumber":"AZ09020001","SellDet":"SD918020900003"}]';
        $data = json_decode($data);
        $returDet = $this->android->get_return_id();
        // [{"Page":"retur","ReturDet":"45","CompanyID":"1","ProductID":"9","ProductSerialID":null,"SerialNumber":"123123"},{"Page":"retur","ReturDet":"45","CompanyID":"1","ProductID":"9","ProductSerialID":null,"SerialNumber":"1234123"}]
        foreach ($data as $d) {
            $d->Page        = "retur";
            $d->ReturDet    = $returDet;
            $d->CompanyID   = $companyID;
            $d->ProductID   = $productID;
            $d->ProductSerialID = null;
        }
        $data = json_encode($data);
        $data_update["SerialNumber"] = $data;
        $this->db->where("ReturDet", $returDet);
        $this->db->update("AP_Retur_Det", $data_update);
        return $data;
    }

    public function AC_CorrectionPR($companyID, $branchID, $username, $AC){
        $data = array(
            "BalanceNo" => $AC,
            "CompanyID" => $companyID,
            "BranchID"  => $branchID,
            "Date"      => date("Y-m-d"),
            "User_Add"  => $username,
            "Date_Add"  => date("Y-m-d H:i:s"),
            );
        $cek = $this->db->count_all("AC_CorrectionPR where CompanyID = '$companyID' AND BranchID = '$branchID' AND BalanceNo = '$AC'");
        if($cek<1):
            $this->db->insert("AC_CorrectionPR", $data);
        endif;
    }

    public function get_priceMutation($companyID,$branchID,$productID){
        $query = $this->db->query(
            "SELECT  
                ps_m_d.Price 
            FROM
                PS_Mutation as ps_m 
            LEFT JOIN 
                PS_Mutation_Detail as ps_m_d on ps_m.MutationNo = ps_m_d.MutationNo 
            WHERE 
                ps_m.CompanyID   = '$companyID' AND
                ps_m.BranchIDTo  = '$branchID' AND 
                ps_m_d.ProductID = '$productID' 
                ORDER BY ps_m.Date_Add DESC 
                limit 1
                "
            );
        if($query->num_rows()>0):
            $d = $query->row();
            $price = $d->Price;
        else:
            $price = "0";
        endif;

        return $price;
    }

    public function syncron_serialNumber($companyID, $branchID){
        $this->db->select("ps_m_d.MutationDet,ps_m_d.SerialNumber,ps_m_d.ProductID,ps_p.Type");
        $this->db->join("PS_Mutation_Detail as ps_m_d", "ps_m.MutationNo = ps_m_d.MutationNo");
        $this->db->join("ps_product as ps_p ", "ps_m_d.ProductID = ps_p.ProductID");
        $this->db->where("ps_m.CompanyID", $companyID);
        $this->db->where("ps_m_d.CompanyID", $companyID);
        $this->db->where("ps_m.BranchIDTo", $branchID);
        $this->db->where('ps_m_d.SerialNumber is NOT NULL', NULL, FALSE);
        $this->db->order_by("ps_m_d.ProductID");
        $query = $this->db->get("PS_Mutation as ps_m");

        return $query;
    }

    public function save_mutation_det($product_data, $mutationNo, $companyID, $branchID, $branchIDTO,$username, $type){
        $data = json_decode($product_data);
        foreach ($data as $d) {
            $mutationDet = $this->android->autoNumber("PS_Mutation_Detail", "MutationDet", 5, "MTD".date("ym"));
            $data_mutation_det = array(
                "MutationDet"   => $mutationDet,
                "MutationNo"    => $mutationNo,
                "CompanyID"     => $companyID,
                "ProductID"     => $d->productID,
                "UnitID"        => $d->unit,
                "Qty"           => $d->qty,
                "Conversion"    => $this->android->add_conversion($companyID, $d->unit),
                "Price"         => $d->price,
                "Remark"        => $d->remark,
                "SerialNumber"  => $this->add_serial_mutation($mutationDet,$companyID,$d->productID,$d->sn),
                "User_Add"      => $username,
                "Date_Add"      => date("Y-m-d H:i:s"),
                );
            $this->db->insert("PS_Mutation_Detail", $data_mutation_det);
            $this->updateQtyProductBranch($companyID, $branchID, $d->productID, $d->qty, $username);//mengurangi stock pada branch
            if($type == 1):
                $this->addQtyProductCompany($companyID, $d->productID, $d->qty, $username);
            else:
                $this->addQtyProductBranch($companyID, $branchIDTO, $d->productID, $d->qty);
            endif;
        }
    }
    public function add_serial_mutation($mutationDet,$companyID,$productID,$data){
        foreach ($data as $d) {
            $d->Page        = "mutasi";
            $d->MutationDet = $mutationDet;
            $d->CompanyID   = $companyID;
            $d->ProductID   = $productID;
            $d->ProductSerialID = null;
        }
        $data = json_encode($data);
        return $data;
    }

    public function syncronSerialMutation($data, $username){
        $data = json_decode($data);
        if($data->status):
            foreach ($data->data as $d) {
                $mutationDet    = $d->MutationDet;
                $productID      = $d->ProductID;
                $serial         = $d->SerialNumber;
                $status         = $d->status;

                $query = $this->getMutationDetSn($mutationDet);
                $d2 = $query->row();

                $sn = $d2->SerialNumber;
                $sn = json_decode($sn);
                foreach($sn as $key => $value)
                {
                    if($value->SerialNumber == $serial):
                        $sn[$key]->status = $status;
                    endif;
                }

                $sn_mutasi = json_encode($sn);
                $data_mutasai = array(
                    "SerialNumber"  => $sn_mutasi,
                    "Date_Ch"       => date("Y-m-d H:i:s"),
                    "User_Ch"       => $username,
                    );
                $this->db->where("MutationDet", $mutationDet);
                $this->db->update("PS_Mutation_Detail", $data_mutasai);
            }
        endif;
    }

    public function get_setting($companyID){
        $this->db->select("
            Currency,
            AmountDecimal,
            QtyDecimal,
            NegativeStock,
            CostMethod,
        ");
        $this->db->from("SettingParameter");
        $this->db->where("CompanyID", $companyID);
        $query = $this->db->get()->row();

        $data = array(
            "status" => false,
        );
        if($query):
            $data = array(
                "status"        => true,
                "Currency"      => $query->Currency,
                "AmountDecimal" => $query->AmountDecimal,
                "QtyDecimal"    => $query->QtyDecimal,
                "NegativeStock" => $query->NegativeStock,
                "CostMethod"    => $query->CostMethod,
            );
        endif;

        return $data;
    }
    #end syncron data _______________________________________________________________________________

    public function add_conversion($companyID, $unitID){
        $this->db->select("Conversion");
        $this->db->where("UnitID", $unitID);
        $this->db->where("CompanyID", $companyID);
        $query = $this->db->get("ps_unit");
        if($query->num_rows()>0):
            $d = $query->row();
            $Conversion = $d->Conversion;
        else:
            $Conversion = "1.00";
        endif;

        return $Conversion;
    }

    public function add_conversion_return($sellDet){
        $this->db->select("Conversion");
        $this->db->where("SellDet", $sellDet);
        $query = $this->db->get("PS_Sell_Detail");
        $d = $query->row();

        return "1.00";
    }
    
    public function add_conversion_array($data = ""){
        // $data    = '[{"MutationDet":"MTD180200013","SerialNumber":"AZ09020001"}]';
        $data   = json_decode($data);
        foreach ($data as $d) {
            $mutationDet = $d->MutationDet;         
            $query  = $this->android->get_conversion_from_mutation($mutationDet);
            $a      = $query->row();
            $d->Conversion = $a->Conversion;
        }
        $data = json_encode($data);
        return $data;
    }

    public function save_mutation_det2($product_data, $mutationNo, $companyID, $branchID, $branchIDTO,$username, $type){
        $data = json_decode($product_data);
        foreach ($data as $d) {
            //type untuk jenis serial
            if($d->type !=0):
                $this->updateMutationDetSn($d->sn, $username);
            endif;
            
            $mutationDet = $this->android->autoNumber("PS_Mutation_Detail", "MutationDet", 5, "MTD".date("ym"));
            $data_mutation_det = array(
                "MutationDet"   => $mutationDet,
                "MutationNo"    => $mutationNo,
                "CompanyID"     => $companyID,
                "ProductID"     => $d->productID,
                "UnitID"        => $d->unit,
                "Qty"           => $d->qty,
                "Conversion"    => $this->android->add_conversion($companyID, $d->unit),
                "Price"         => $d->price,
                "Remark"        => $d->remark,
                "SerialNumber"  => $this->add_serial_mutation($mutationDet,$companyID,$d->productID,$d->sn),
                "User_Add"      => $username,
                "Date_Add"      => date("Y-m-d H:i:s"),
                );
            $this->db->insert("PS_Mutation_Detail", $data_mutation_det);
            $this->updateQtyProductBranch($companyID, $branchID, $d->productID, $d->qty, $username);//mengurangi stock pada branch
            //type untuk jenis mutasi 
            // 1 = ke company, 2 = ke store
            if($type == 1):
                $this->addQtyProductCompany($companyID, $d->productID, $d->qty, $username);
            else:
                $this->addQtyProductBranch($companyID, $branchIDTO, $d->productID, $d->qty);
            endif;
        }
    }

    public function updateMutationDetSn($sn, $username){
        foreach ($sn as $d) {
            $mutationDet    = $d->MutationDet;
            $serial         = $d->SerialNumber;

            $query = $this->getMutationDetSn($mutationDet);
            $d2 = $query->row();

            $sn = $d2->SerialNumber;
            $sn = json_decode($sn);
            foreach($sn as $key => $value)
            {
                if($value->SerialNumber == $serial):
                    $sn[$key]->status = 0;
                endif;
            }

            $sn_mutasi = json_encode($sn);
            $data_mutasai = array(
                "SerialNumber"  => $sn_mutasi,
                "Date_Ch"       => date("Y-m-d H:i:s"),
                "User_Ch"       => $username,
                );
            $this->db->where("MutationDet", $mutationDet);
            $this->db->update("PS_Mutation_Detail", $data_mutasai);

        }
    }

    public function getMutationDetSn($mutationDet){
        $this->db->select("SerialNumber");
        $this->db->where("MutationDet", $mutationDet);
        $query = $this->db->get("PS_Mutation_Detail");

        return $query;
    }

    //check expire aplikasi
    public function updateBranch($data, $BranchID){
        $this->db->where("BranchID", $BranchID);
        $this->db->update("Branch", $data);
    }
    public function data_expire($data){
        $this->db->select("ExpireAccount,StatusAccount");
        $this->db->where($data);
        $query = $this->db->get("Branch");

        return $query->row();
    }

    public function voucher($CompanyID, $Voucher){
        $this->db->select("
            v.VoucherID,
            v.Type,
            vd.Status,
            ");
        $this->db->join("VoucherDetail as vd", "v.VoucherID = vd.VoucherID");
        // $this->db->where("vd.CompanyID", $CompanyID);
        $this->db->where("vd.Code", $Voucher);
        $this->db->where("vd.App", "pipesys");
        $this->db->where("v.Module", 1);
        $query = $this->db->get("Voucher as v");

        return $query;
    }

    //check expire
    public function checkExpire($data){
        $data_expire = $this->data_expire($data);

        $status     = $data_expire->StatusAccount;
        $expiredate = $data_expire->ExpireAccount;
        $time1      =strtotime(date("Y-m-d"));
        $time2      =strtotime($expiredate);
        
        if($expiredate < date("Y-m-d")):
            $expire = TRUE; // masa aktif sudah habis
        else:
            $expire = FALSE; // masa aktif masih ada
        endif;
        $selisih = ($time2-$time1)/(60*60*24);
        $res["StatusAccount"]   = $status;
        $res["Expire"]          = $expire;
        $res["Selisih"]         = $selisih;
        $res["ExpireAccount"]   = $expiredate;

        return $res;
    }

    //end 
}