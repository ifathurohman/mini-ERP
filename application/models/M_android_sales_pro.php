<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_android_sales_pro extends CI_Model {
    
    var $table_branch = "Branch";

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set("Asia/Jakarta");
    }

    public function checkToken($token, $type=""){
        $this->db->select("CompanyID,BranchID,Email,Name,StatusAccount,ExpireAccount");
        if($type == "email"):
            $this->db->where("Email", $token);
            $this->db->where("Active", 1);
        else:
            $this->db->where("Token", $token);
        endif;
        
        $query = $this->db->get($this->table_branch);

        return $query;
    }

    public function Branch($data){
        $this->db->select("BranchID,Name,Email,Phone,App,Active,Password,AutoCheckOut,StatusAccount,ExpireAccount");
        $this->db->where($data);
        $query = $this->db->get($this->table_branch);

        return $query;
    }

    public function getCompanyID($email){
        $this->db->select("id_user");
        $this->db->where("Email", $email);
        $this->db->where("App !=", "pipesys");
        $query = $this->db->get("user");
        if($query->num_rows()>0):
            $d = $query->row();
            return $d->id_user;
        else:
            return 0;
        endif;
    }

    public function getCompanyName($CompanyID){
        $this->db->select("nama");
        $this->db->where("id_user", $CompanyID);
        $query = $this->db->get("user");
        $d = $query->row();
        return $d->nama;
    }

    public function user_name($BranchID){
        $this->db->select("Name");
        $this->db->where("BranchID", $BranchID);
        $query = $this->db->get($this->table_branch);
        $d = $query->row();

        return $d->Name;
    }

    public function transaction_today($companyID,$BranchID){
        $this->db->select("
            sp_tr.Code,
            sp_tr.Date,
            sp_trd.TransactionRouteDetailID,
            sp_trd.CheckIn,
            sp_trd.CheckInLatlng,
            sp_trd.CheckInAddress,
            sp_trd.CheckOut,
            sp_trd.CheckOutLatlng,
            sp_trd.CheckOutAddress,
            sp_trd.Remark,
            sp_trd.RemarkSales,
            ps_v.VendorID,
            ps_v.Name,
            ps_v.Address,
            ps_v.Lat,
            ps_v.Lng,
            ps_v.Radius,
            ");
        $this->db->join("SP_TransactionRouteDetail as sp_trd", "sp_tr.TransactionRouteID = sp_trd.TransactionRouteID");
        $this->db->join("PS_Vendor as ps_v", "sp_trd.VendorID = ps_v.VendorID", "left");
        $this->db->where("sp_tr.Date", date("Y-m-d"));
        $this->db->where("sp_tr.CompanyID", $companyID);
        $this->db->where("sp_tr.BranchID", $BranchID);
        $this->db->where("sp_tr.Active", 1);
        $query = $this->db->get("SP_TransactionRoute as sp_tr");

        return $query;
    }

    public function transaction_detail($ID){
        $this->db->select("
            sp_tr.Code,
            sp_tr.Date,
            sp_trd.TransactionRouteDetailID,
            sp_trd.CheckIn,
            sp_trd.CheckInLatlng,
            sp_trd.CheckInAddress,
            sp_trd.CheckOut,
            sp_trd.CheckOutLatlng,
            sp_trd.CheckOutAddress,
            sp_trd.Remark,
            sp_trd.RemarkSales,
            sp_trd.Attachment,
            ps_v.VendorID,
            ps_v.Name,
            ps_v.Address,
            ps_v.Lat,
            ps_v.Lng,
            IFNULL(ps_v.Radius, '0') as Radius,
            ");
        $this->db->join("SP_TransactionRouteDetail as sp_trd", "sp_tr.TransactionRouteID = sp_trd.TransactionRouteID");
        $this->db->join("PS_Vendor as ps_v", "sp_trd.VendorID = ps_v.VendorID", "left");
        $this->db->where("sp_trd.TransactionRouteDetailID", $ID);
        $query = $this->db->get("SP_TransactionRoute as sp_tr");

        return $query;
    }

    public function transactionNotCustomer($ID){
        $this->db->select("
            sp_tr.Code,
            sp_tr.Date,
            sp_trd.TransactionRouteDetailID,
            sp_trd.CheckIn,
            sp_trd.CheckInLatlng,
            sp_trd.CheckInAddress,

            ");
        $this->db->join("SP_TransactionRouteDetail as sp_trd", "sp_tr.TransactionRouteID = sp_trd.TransactionRouteID");
        $this->db->where("sp_trd.TransactionRouteDetailID", $ID);
        $query = $this->db->get("SP_TransactionRoute as sp_tr");

        return $query;
    }

    public function history($BranchID,$companyID){
        $startDate  = $this->input->post("start_date");
        $endDate    = $this->input->post("end_date");

        if($startDate == ''):
            $startDate = date("Y-m-01");
        endif;
        if($endDate == ''):
            $endDate = date("Y-m-d");
        endif;

        $this->db->select("
            sp_tr.Code,
            sp_tr.Date,
            sp_trd.TransactionRouteDetailID,
            sp_trd.CheckIn,
            sp_trd.CheckOut,
            sp_trd.Duration,
            sp_trd.ImgSales,
            sp_trd.RemarkSales,
            sp_trd.Attachment,
            IFNULL(ps_v.Name, 'Unknown') as Name,
            IFNULL(ps_v.Address, '-') as Address,
            IFNULL(sp_trd.CheckInAddress, '-') as CheckInAddress,
            IFNULL(sp_trd.CheckOutAddress, '-') as CheckOutAddress,
            IFNULL(sp_trd.RemarkSales, ' ') as RemarkSales,
            ");
        $this->db->join("SP_TransactionRouteDetail as sp_trd", "sp_tr.TransactionRouteID = sp_trd.TransactionRouteID");
        $this->db->join("PS_Vendor as ps_v", "sp_trd.VendorID = ps_v.VendorID", "left");
        $this->db->where("sp_tr.CompanyID", $companyID);
        $this->db->where("sp_tr.BranchID", $BranchID);
        // $this->db->where("sp_trd.CheckIn !=", null);
        $this->db->where("sp_tr.Date >= ", $startDate);
        $this->db->where("sp_tr.Date <= ", $endDate);
        $this->db->order_by("sp_tr.Date");
        $query = $this->db->get("SP_TransactionRoute as sp_tr");

        return $query;

    }

    public function getImage($CompanyID){
        $this->db->select("img_url");
        $this->db->where("id_user", $CompanyID);
        $query = $this->db->get("user");

        $d = $query->row();

        if($d->img_url):
            $img    = site_url().$d->img_url;
        else:
            $img    = site_url("img/rc.png");
        endif;

        return $img;
    }

    //check expire aplikasi 
    public function data_expire($data){
        $this->db->select("ExpireAccount,StatusAccount");
        $this->db->where($data);
        $query = $this->db->get($this->table_branch);

        return $query->row();
    }

    public function voucher($CompanyID, $Voucher){
        $this->db->select("
            v.VoucherID,
            v.Type,
            vd.Status,
            ");
        $this->db->join("VoucherDetail as vd", "v.VoucherID = vd.VoucherID");
        $this->db->where("vd.CompanyID", $CompanyID);
        $this->db->where("vd.Code", $Voucher);
        $this->db->where("vd.App", "salespro");
        $query = $this->db->get("Voucher as v");

        return $query;
    }
    //end 

    public function unlinkDevice($DeviceID){
        $cek = $this->db->count_all("Branch where DeviceID='$DeviceID'");
        if($cek>0):
            $data = array(
                "DeviceID"      => Null,
                "Token"         => Null,
                "User_Ch"       => 'unlink android sales pro',
                "Date_Ch"       => date("Y-m-d H:i:s"),
                );
            $this->db->where("DeviceID", $DeviceID);
            $this->db->update($this->table_branch, $data);
        endif;
    }

    //customer
    public function customer($CompanyID){
        $this->db->select("
            VendorID,
            Name,
            Address,
            Lat,
            Lng,
            Radius,
            ");
        $this->db->where("CompanyID", $CompanyID);
        $this->db->where("Position", 2);
        $this->db->where("App", "salespro");
        $this->db->where("Active", 1);
        $this->db->order_by("Name");
        $query = $this->db->get("PS_Vendor");

        return $query;
    }
    //end customer

    public function get_checkIn($CompanyID, $BranchID){
        $this->db->select("
            sp_trd.TransactionRouteDetailID,
            sp_trd.CheckIn,
            ");
        $this->db->join("SP_TransactionRouteDetail as sp_trd", "sp_tr.TransactionRouteID = sp_trd.TransactionRouteID");
        $this->db->join("PS_Vendor as ps_v", "sp_trd.VendorID = ps_v.VendorID", "left");
        // $this->db->where("sp_tr.Date", date("Y-m-d"));
        $this->db->where("sp_tr.CompanyID", $CompanyID);
        $this->db->where("sp_tr.BranchID", $BranchID);
        $this->db->where("sp_tr.Active", 1);
        $this->db->where("sp_trd.CheckIn != ", null);
        $this->db->where("sp_trd.CheckOut", null);
        $this->db->order_by("TransactionRouteDetailID", "desc");
        $query = $this->db->get("SP_TransactionRoute as sp_tr");

        return $query;
    }

    public function pushCurrentLocation($CompanyID,$BranchID,$Location){
        $Location         = json_decode($Location);
        if($Location->status):
            $data = array(
                "CompanyID"     => $CompanyID,
                "BranchID"      => $BranchID,
                "Latlng"        => json_encode($Location->data),
                "Date"          => date("Y-m-d"),
                "UserAdd"       => $this->user_name($BranchID),
                "DateAdd"       => date("Y-m-d H:i:s"),
                );
            $this->db->insert("SP_BranchRoute", $data);
        else:

        endif;
    }

    public function getLat($latlng){
        $latlng = explode(",", $latlng);
        $lat    = $latlng[0];

        return $lat;
    }
    public function getLng($latlng){
        $latlng = explode(",", $latlng);
        $lng    = $latlng[1];

        return $lng;
    }

    //2018-05-14 MW
    // get branch selain comany sendiri
    public function getBranch($Email, $CompanyID, $App){
        $this->db->select("user.nama");
        $this->db->where("Branch.Email", $Email);
        $this->db->where("Branch.App", $App);
        $this->db->where("Branch.CompanyID !=", $CompanyID);
        $this->db->join("user", "Branch.CompanyID = user.id_user");
        $query = $this->db->get("Branch");

        return $query;
    }
}