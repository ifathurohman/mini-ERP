<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#tanggal 2017-08-30
#author m iqbal ramadhan
class M_main extends CI_Model {
    var $host;
    var $app;
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set("Asia/Jakarta");
        $this->host = base_url();
        $this->app  = $this->session->app;
        $this->setting_parameter();
        $this->lang->load('bahasa', $this->session->userdata("bahasa"));
        $this->check_login_user();
    }
    
    public function echoJson($data){
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);
    }

    public function hash($password){
        $password   = "imissyou".$password."beybeh";
        $hash       = sha1($password);
        return $hash;
    }
    public function autoNumber($tabel, $kolom, $lebar=0, $awalan, $tambahan = "") {
        $this->db->select("$kolom");
        if($this->session->app == "pipesys"):
            if($tabel == "AP_GoodReceipt" || $tabel == "PS_Mutation" || $tabel == "PS_Correction" || $tabel == "AP_Retur" || $tabel == "AC_CorrectionPR" || $tabel=="PS_Sell" || $tabel == "PS_Sell_Detail" || $tabel == "PS_Invoice" || $tabel=="PS_Invoice_Detail" || $tabel == "PS_Delivery" || $tabel == "PS_Delivery_Det" || $tabel == "PS_Payment" || $tambahan == "month_reset"):
                $this->db->where("MONTH(date_add)",date("m"));
                $this->db->where("CompanyID",$this->session->CompanyID);
            elseif($tabel == "PS_Product_Serial"):
                $this->db->where("CompanyID",$this->session->CompanyID);
                $this->db->where("ProductID",$tambahan);
            elseif($tambahan == "company"):
                $this->db->where("CompanyID",$this->session->CompanyID);
            else:

            endif;
        else:
        #ini untuk salespro
            if($tabel == "SP_TransactionRoute"):
                $this->db->where("MONTH(DateAdd)",date("m"));
                $this->db->where("CompanyID",$tambahan);
            endif;
        endif;
        
        $pagenya = $this->session->pagenya;
        if($pagenya == "payment"):
            $this->db->where("Type", $this->session->tipenya);
        elseif($pagenya == "invoice"):
            $this->db->where("Type", $this->session->tipenya);
        elseif($pagenya == "selling_ar"):
            $this->db->where("BranchID", null);
        elseif($pagenya == "ac_kas_bank"):
            $this->db->where("Type", $this->session->tipenya);
        elseif($pagenya == "ac_kas_bank_det"):
            $this->db->where("ID", null);
        elseif($pagenya == "correctionar"):
            $this->db->where("Type", $this->session->tipenya);
        elseif($pagenya == "return_sales"):
            $this->db->where("Type", $this->session->tipenya);
        elseif($pagenya == "retur"):
            $this->db->where("Type", $this->session->tipenya);
        elseif($pagenya == "stock_opname" || $pagenya == "koreksi_stock" || $pagenya == "inventory_receipt"):
            $this->db->where("Type", $this->session->tipenya);
        elseif($pagenya == "vendor"):
            $this->db->where("CodeType", 0);
            $this->db->where("Position", $this->session->tipenya);
        elseif($pagenya == "master_sales"):
            $this->db->where("CodeType", 0);
        endif;

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

        $angka = $this->check_code($angka,$tabel,$kolom,$lebar,$awalan,$nomor);

        $data = array("pagenya" => "","tipenya" => "");
        $this->session->set_userdata($data);
        return $angka;
    }

    private function check_code($code,$tabel,$kolom,$lebar,$awalan,$nomor){
        $CompanyID = $this->session->CompanyID;
        if(!$CompanyID):
            $CompanyID = 0;
        endif;
        $cek = $this->db->count_all("$tabel where $kolom = '$code' and CompanyID = '$CompanyID'");
        if($cek>0):
            $nomor += 1;
            $angka = $awalan.str_pad($nomor,$lebar,"0",STR_PAD_LEFT);
            $angka = $this->check_code($angka,$tabel,$kolom,$lebar,$awalan,$nomor);

            return $angka;
        else:
            return $code;
        endif;
    }

    public function konversi_tanggal($format, $tanggal="now", $bahasa="en"){
     $en = array("Sun","Mon","Tue","Wed","Thu","Fri","Sat","Jan","Feb",
     "Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
     $id = array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu",
     "Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September",
     "Oktober","November","Desember");
     // tambahan untuk bahasa prancis
     $eng = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","January","February",
        "March","April","May","June","July","August","September","October","November","December");
     // sumber http://w.blankon.in/6V
     $fr = array("dimanche","lundi","mardi","mercredi","jeudi","vendredi","samedi",
     "janvier","février","mars","avril","Mei","mai","juillet","aoùt","septembre",
     "octobre","novembre","décembre");
     // mengganti kata yang berada pada array en dengan array id, fr (default id)
     if($this->session->bahasa != "indonesia"):
        $bahasa = "eng";
     endif;
     return str_replace($en,$$bahasa,date($format,strtotime($tanggal)));
    }
    public function user_code_generate()
    {
        return $this->autoNumber("user","kode_user",5,date("ym"));
    }
    public function branch_code_generate()
    {
        return $this->autoNumber("Branch","Code",5,date("ym"));
    }
    public function vendor_code_generate($type)
    {   
        $data = array("pagenya" => "vendor", "tipenya" => $type);
        $this->session->set_userdata($data);
        if($type == 1):
            $awalan = "VND";
        else:
            $awalan = "CST";
        endif;
        return $this->autoNumber("PS_Vendor","Code",4,$awalan,"company");
    }
    public function vendor_address_code_generate()
    {
        return $this->autoNumber("ps_vendor_address","Code",5,date("ym"));
    }
    public function vendor_phone_code_generate()
    {
        return $this->autoNumber("ps_vendor_contact","Code",5,date("ym"));
    }
    public function sales_generate(){
        $data = array("pagenya" => "master_sales");
        return $this->autoNumber("PS_Sales","Code",4,"SA","company");
    }
    public function penerimaan_code_generate()
    {
        $month  = $this->bulan_romawi();
        return $this->autoNumber("AP_GoodReceipt","ReceiveNo",5,"GR/".date("Y")."/".$month."/","month_reset");
    }
    public function penerimaandetail_code_generate()
    {
        return $this->autoNumber("AP_GoodReceipt_Det","ReceiveDet",5,"GRD".date("ym"));
    }
    public function mutasi_code_generate()
    {
        return $this->autoNumber("PS_Mutation","MutationNo",5,"MT".date("ym"));
    }
    public function mutasidetail_code_generate()
    {
        return $this->autoNumber("PS_Mutation_Detail","MutationDet",5,"MTD".date("ym"));
    }
    public function correction_code_generate($page="")
    {   
        if($page == "stock_opname"):
            $data = array("pagenya" => "stock_opname", "tipenya" => 2);
            $this->session->set_userdata($data);
            return $this->autoNumber("PS_Correction","CorrectionNo",5,"IC".date("ym"),"month_reset");
        elseif($page == "inventory_receipt"):
            $data = array("pagenya" => "inventory_receipt", "tipenya" => 3);
            $this->session->set_userdata($data);
            return $this->autoNumber("PS_Correction","CorrectionNo",5,"SR".date("ym"),"month_reset");
        elseif($page == "good_issue"):
            $data = array("pagenya" => "inventory_receipt", "tipenya" => 4);
            $this->session->set_userdata($data);
            return $this->autoNumber("PS_Correction","CorrectionNo",5,"SI".date("ym"),"month_reset");
        else:
            $data = array("pagenya" => "koreksi_stock", "tipenya" => 1);
            $this->session->set_userdata($data);
            return $this->autoNumber("PS_Correction","CorrectionNo",5,"CS".date("ym"),"month_reset");
        endif;
    }
    public function correctiondet_code_generate($page="")
    {   
        if($page == "stock_opname"):
            return $this->autoNumber("PS_Correction_Detail","CorrectionDet",5,"ICD".date("ym"),"month_reset");
        elseif($page == "inventory_receipt"):
            return $this->autoNumber("PS_Correction_Detail","CorrectionDet",5,"SRD".date("ym"),"month_reset");
        elseif($page == "good_issue"):
            return $this->autoNumber("PS_Correction_Detail","CorrectionDet",5,"SID".date("ym"),"month_reset");
        else:
            return $this->autoNumber("PS_Correction_Detail","CorrectionDet",5,"CSD".date("ym"),"month_reset");
        endif;
    }
     public function rekno_generate()
    {
        return $this->autoNumber("user_rekening","UserRekID",5,"RK".date("ym"));
    }
    public function returno_generate()
    {
        return $this->autoNumber("AP_Retur","ReturNo",5,"RT".date("ym"));
    }
    public function return_sales_generate(){
        $data = array("pagenya" => "return_sales", "tipenya" => 2);
        $this->session->set_userdata($data);
        $month  = $this->bulan_romawi();
        return $this->autoNumber("AP_Retur","ReturNo",4,"RS/".date("Y")."/".$month."/","month_reset");
    }

    public function retur_payable_generate(){
        $data = array("pagenya" => "retur", "tipenya" => 1);
        $month  = $this->bulan_romawi();
        $this->session->set_userdata($data);
        return $this->autoNumber("AP_Retur","ReturNo",5,"RP/".date("Y")."/".$month."/", "month_reset");
    }

    public function correctionar_generate()
    {
        $data = array("pagenya" => "correctionar", "tipenya" => 2);
        $this->session->set_userdata($data);
        $month  = $this->bulan_romawi();
        return $this->autoNumber("AC_BalancePayable","Code",4,"AC/".date("Y")."/".$month."/","month_reset");
    }

    public function correctionap_generate()
    {
        $data = array("pagenya" => "correctionar", "tipenya" => 1);
        $this->session->set_userdata($data);
        $month  = $this->bulan_romawi();
        return $this->autoNumber("AC_BalancePayable","Code",5,"AP/".date("Y")."/".$month."/","month_reset");
    }

    public function selling_generate(){
        $data = array("pagenya" => "selling_ar");
        $this->session->set_userdata($data);
        $month  = $this->bulan_romawi();
        return $this->main->autoNumber("PS_Sell", "SellNo", 4, "SO/".date("Y")."/".$month."/","month_reset");
    }
    public function selling_detail_generate(){
        $data = array("pagenya" => "selling_ar");
        $this->session->set_userdata($data);
        return $this->main->autoNumber("PS_Sell_Detail", "SellDet", 5, "SD".date("ym"),"month_reset");
    }
    public function paymentno_generate()
    {
        return $this->autoNumber("PS_Payment","PaymentNo",5,"PS".date("ym"));
    }
    public function delivery_generate(){
        $month  = $this->bulan_romawi();
        return $this->autoNumber("PS_Delivery","DeliveryNo",4,"DO/".date("Y")."/".$month."/", "month_reset");
    }
    public function deliverydet_generate(){
        return $this->autoNumber("PS_Delivery_Det","DeliveryDet",5,"DT".date("ym"));
    }
    public function invoice_ar_generate(){
        $month  = $this->bulan_romawi();
        $data = array("pagenya" => "invoice", "tipenya" => 2);
        $this->session->set_userdata($data);
        return $this->autoNumber("PS_Invoice","InvoiceNo",4,"IV/".date("Y")."/".$month."/");
    }
    public function invoice_ap_generate(){
        $month  = $this->bulan_romawi();
        $data = array("pagenya" => "invoice", "tipenya" => 1);
        $this->session->set_userdata($data);
        return $this->autoNumber("PS_Invoice","InvoiceNo",4,"IVP/".date("Y")."/".$month."/","month_reset");
    }
    public function invoice_ap_det_generate(){
        $month  = $this->bulan_romawi();
        return $this->autoNumber("PS_Invoice_Detail","InvoiceDet",5,date("ym"),"month_reset");
    }
    public function invoice_ar_det_generate(){
        return $this->autoNumber("PS_Invoice_Detail","InvoiceDet",5,date("ym"),"month_reset");
    }
    public function purchase_generate(){
        $month  = $this->bulan_romawi();
        return $this->autoNumber("PS_Purchase","PurchaseNo",4,"PR/".date("Y")."/".$month."/","month_reset");
    }
    public function purchase_det_generate(){
        return $this->autoNumber("PS_Purchase_Detail","PurchaseDet",5,date("ym"),"month_reset");
    }
    public function kas_bank_generate($type){
        if($type == 0):
            $awal = "SA";
        elseif($type == 1):
            $awal = "CT";
        elseif($type == 2):
            $awal = "BT";
        elseif($type == 3):
            $awal = "JE";
        endif;

        $month  = $this->bulan_romawi();
        $data = array("pagenya" => "ac_kas_bank", "tipenya" => $type);
        $this->session->set_userdata($data);
        return $this->autoNumber("AC_KasBank","KasBankNo",4,$awal."/".date("Y")."/".$month."/","month_reset");
    }

    public function kas_bank_det_generate(){
        $data = array("pagenya" => "ac_kas_bank_det");
        $this->session->set_userdata($data);
        return $this->autoNumber("AC_KasBank_Det","KasBankDetNo",5,"Kas_Bank/".date("ym"),"month_reset");
    }

    public function jurnal_generate(){
        $awal = "JE";
        $month  = $this->bulan_romawi();
        $data = array("pagenya" => "ac_kas_bank", "tipenya" => 3);
        $this->session->set_userdata($data);
        return $this->autoNumber("AC_KasBank","KasBankNo",4,$awal."/".$month."/","month_reset");
    }

    public function jurnal_det_generate(){
        $data = array("pagenya" => "ac_kas_bank_det");
        $this->session->set_userdata($data);
        return $this->autoNumber("AC_KasBank_Det","KasBankDetNo",5,"JE/".date("ym"),"month_reset");
    }

    public function transaction_route_code_generate($CompanyID = "")
    {
        if($CompanyID == ""):
            $CompanyID = $this->session->CompanyID;
        endif;

        return $this->autoNumber("SP_TransactionRoute","Code",5,date("ym"), $CompanyID);
    }
    public function payment_ar_generate(){
        $data = array("pagenya" => "payment", "tipenya" => 3,);
        $this->session->set_userdata($data);
        $month  = $this->bulan_romawi();
        return $this->autoNumber("PS_Payment","PaymentNo",4,"PP/".date("Y")."/".$month."/","month_reset");
    }

     public function payment_ap_generate(){
        $data = array("pagenya" => "payment", "tipenya" => 2);
        $this->session->set_userdata($data);
        $month  = $this->bulan_romawi();
        return $this->autoNumber("PS_Payment","PaymentNo",4,"PA/".date("Y")."/".$month."/","month_reset");
    }

    public function transaction_voucher_generate()
    {
        return $this->autoNumber("Voucher","Code",4,"TRV".date("ym"));
    }
    public function hak_akses()
    {
        $hak_akses = $this->session->hak_akses;
        $this->db->select('*');
        $this->db->from('hak_akses');
        $this->db->where('hak_akses',$hak_akses);
        $this->db->where('app',$this->session->app);
       return $this->db->get()->row()->menu;
    }
    public function menu($kategori,$modul="")
    {
        $hk     = $this->hak_akses();
        $data   = json_decode($hk);
        // $this->db->select("*");
        // $this->db->where("kategori",$kategori);
        // $this->db->where_in("id_menu",implode(',', array_map('intval', $data)));
        // $this->db->like("app",$this->session->app);
        if($modul):
            $modul = ' AND modul like "%'.$modul.'%" ';
        elseif($kategori == "transaction"):
            $modul = " AND (modul not like '%ar%' and modul not like '%ap%' and modul not like '%inventory%')  ";
        elseif($kategori == "report"):
            $modul = " AND (url not like '%lap_%') ";
        elseif($kategori == "report_detail"):
            $kategori = "report";
            $modul = " AND (url like '%lap_%') ";
        else:
            $modul = '';
        endif;

        $query = $this->db->get("menu");
        $query  = $this->db->query('
            SELECT * FROM menu 
            WHERE 
                kategori="'.$kategori.'" AND id_menu IN (' . implode(',', array_map('intval', $data)) . ')
                '.$modul.'
            ORDER BY ifnull(menu.index,100000),type' );
        return $query->result();
    }
    public function menu_detail($id_menu){
        $this->db->where("id_menu", $id_menu);
        $query = $this->db->get("menu");
        
        return $query->row();
    }
    public function id_menu($url)
    {   
        $id_menu = 0;
        if($url):
            $this->db->select('id_menu');
            $this->db->from('menu');
            $this->db->like('url',$url);
            $this->db->or_like('root',$url);
            $id_menu = $this->db->get()->row()->id_menu;
            if(empty($id_menu)):
                $id_menu = 0;
            endif;
        endif;
        return $id_menu;
    }
    public function read($id_menu)
    {
        $ihk = $this->session->hak_akses;
        $query = $this->db->query("SELECT menu FROM hak_akses WHERE hak_akses='$ihk' AND app='$this->app' ");
        foreach($query->result() as $b){
            $array = json_decode($b->menu);
            return count(array_keys($array, $id_menu ));
        }
    }
    public function menu_tambah($id_menu)
    {
        $ihk = $this->session->hak_akses;
        $query = $this->db->query("SELECT tambah FROM hak_akses WHERE hak_akses='$ihk' AND app='$this->app' ");
        foreach($query->result() as $b){
            $array = json_decode($b->tambah);
            return count( array_keys( $array, $id_menu ));
        }
    }
    public function menu_hapus($id_menu)
    {
        $ihk = $this->session->hak_akses;
        $query = $this->db->query("SELECT hapus FROM hak_akses WHERE hak_akses='$ihk' AND app='$this->app' ");
        foreach($query->result() as $b){
            $array = json_decode($b->hapus);
            return count( array_keys( $array, $id_menu ));
        }
    }
    public function menu_ubah($id_menu)
    {
        $ihk = $this->session->hak_akses;
        $query = $this->db->query("SELECT ubah FROM hak_akses WHERE hak_akses='$ihk' AND app='$this->app' ");
        foreach($query->result() as $b){
            $array = json_decode($b->ubah);
            return count( array_keys( $array, $id_menu ));
        }
    }

    public function GetMenuName($url)
    {
        $Value = 'Nothing';
        $this->db->select('nama_menu');
        $this->db->from('menu');
        if($url == "current_url"):
            $url = current_url();
            $url = str_replace(base_url(), "", $url);
            $this->db->where('Url',$url);
        else:
            $this->db->like('Url',$url);
            $this->db->or_like('Root',$url);
        endif;
        $data = $this->db->get()->row();
        if($data):
            $Value = $data->nama_menu;
        endif;
        return $Value;
    }

    public function set_session_user($id){
        $this->db->select("
            user.id_user,
            ''  as branchid,
            user.kode_user,
            user.email,
            user.nama,
            user.username,
            user.hak_akses,
            user.CompanyID as companyid,
            user.StatusAccount,
            user.ExpireAccount,
            user.Module,
            user.App,
            user.status,
            user.StatusVerify      as StatusVerify,
            user.StatusParameter   as StatusParameter,
            user.StatusVerifyEmail as StatusVerifyEmail,
            user.StatusVerifyPhone as StatusVerifyPhone,
            user.token             as Token,
            user.JoinDate          as JoinDate,
        ");
        $this->db->where("id_user",$id);
        $query = $this->db->get("user");
        $a          = $query->row();
        $modulapp   = "pipesys";
        $app        = "pipesys";
        $uniqueCode = $this->generate_token_();

        $data = array(
            'login'         => TRUE,
            'UserID'        => $a->id_user,
            'id_user'       => $a->id_user,
            'branchid'      => $a->branchid,
            'kode_user'     => $a->kode_user,
            'iduser'        => $a->id_user,
            'email'         => $a->email,
            'nama'          => $a->nama,
            'NAMA'          => $a->nama,
            'Name'          => $a->nama,
            'username'      => $a->username,
            'hak_akses'     => $a->hak_akses,
            'app'           => $app,
            'modulapp'      => $modulapp,
            'JoinDate'      => $a->JoinDate,
            'StatusVerify'    => $a->StatusVerify,
            'StatusParameter' => $a->StatusParameter,
            'StatusAccount'   => $a->StatusAccount,
            'ExpireAccount'   => $a->ExpireAccount,
            'Module'          => $a->Module,
            'Token'           => $a->Token,
            'UserUnique'      => $uniqueCode,
        );
        if($a->hak_akses == "super_admin" || $a->hak_akses == "company"):
            $data['companyid'] = $a->id_user;
            $data['CompanyID'] = $a->id_user;
        else:
            $data['companyid'] = $a->companyid;
            $data['CompanyID'] = $a->companyid;
        endif;
        $this->session->set_userdata($data);
        $companyID      = $this->input->post("companyID");
        if(!$companyID):
            $data = array(
                "LoginStatus"   => 1,
                "UserUnique"    => $uniqueCode,
                "LoginActivity" => date("Y-m-d H:i:s"),
            );
            $this->db->where("id_user", $id);
            $this->db->update("user", $data);
        endif;
    }
    public function login($page = "",$email = "")
    {
        if($page == "konfirmasi_akun"):
            $email    = $email;
        else:
            $this->validasi_login();
            $email    = $this->input->post("email");
            $password = $this->input->post("password");
            $password = $this->hash($password);
        endif;
        $companyID      = $this->input->post("companyID");
        $this->db->select("
            user.id_user,
            user.kode_user,
            user.email,
            user.nama,
            user.username,
            user.hak_akses,
            user.CompanyID as companyid,
            user.StatusAccount,
            user.ExpireAccount,
            user.Module,
            user.App,
            user.status,
            user.StatusVerify      as StatusVerify,
            user.StatusParameter   as StatusParameter,
            user.StatusVerifyEmail as StatusVerifyEmail,
            user.StatusVerifyPhone as StatusVerifyPhone,
        ");
        // $this->db->where("user.App",'all');
        // $this->db->where("user.status",1);
        $this->db->where("user.email",$email);
        $this->db->where_not_in("user.App", array("salespro"));
        if($page == "konfirmasi_akun"):

        else:
           $this->db->where("password",$password);
        endif;
        if($companyID):
            $this->db->group_start();
            $this->db->where("CompanyID", $companyID);
            $this->db->or_where("id_user", $companyID);
            $this->db->group_end();
        endif;
        $this->db->where_in("hak_akses",array("super_admin","company","branch","additional"));
        $query = $this->db->get("user");
        $a     = $query->row();
        $row   = $query->num_rows();
        if($row > 0):
            if($a->status == 0 && $a->StatusVerify != 2):
                $data["popup"]    = TRUE;
                $data["status"]   = FALSE;
                $data["message"]  = "Login Failed, Your account is deadactive";
                return $data;
                exit();
            endif;
            $modulapp   = $a->App;
            if($modulapp == "all"):
                // $app        = "salespro";
                $app        = "pipesys";
            else:
                $app        = $a->App; 
            endif;
            $data = array(
                'login'         => TRUE,
                'id_user'       => $a->id_user,
                'kode_user'     => $a->kode_user,
                'iduser'        => $a->id_user,
                'email'         => $a->email,
                'nama'          => $a->nama,
                'NAMA'          => $a->nama,
                'username'      => $a->username,
                'hak_akses'     => $a->hak_akses,
                'app'           => $app,
                'modulapp'      => $modulapp,
                'StatusAccount' => $a->StatusAccount,
                'ExpireAccount' => $a->ExpireAccount,
                'Module'        => $a->Module,
            );
            if($a->hak_akses == "super_admin" || $a->hak_akses == "company"):
                $data['companyid'] = $a->id_user;
                $data['CompanyID'] = $a->id_user;
            else:
                $data['companyid'] = $a->companyid;
                $data['CompanyID'] = $a->companyid;
            endif;
            $this->session->set_userdata($data);
            $this->set_session_user($a->id_user);
            $this->setting_parameter();
            $this->branch_ho();
            $AlertVerification = $this->AlertVerification();

            $this->insert_log(1,"login","");
            
            $data["status"]   = TRUE;
            $data["message"]  = "Login Success";
            if(in_array($a->StatusVerify, array(1,2))):

                if($a->status == 1 && $AlertVerification == 2 || $a->status == 0 && $a->StatusVerify == 2):
                    $data["redirect"] = site_url("verification-account");
                elseif($a->status == 1 && $a->StatusParameter == 0 || $a->status == 0 && $a->StatusParameter == 0):
                    $data["redirect"] = site_url("page-setting-parameter");
                else:
                    $data["redirect"] = site_url("dashboard");
                endif;
            else:
                $data["redirect"] = site_url("verification-account");
            endif;
            // $data["redirect"] = site_url("dashboard");
        else:
            $data["popup"]    = TRUE;
            $data["status"]   = FALSE;
            $data["message"]  = "Login Failed";
        endif;

        return $data;
    }
    public function logout()
    {
        if($this->session->UserID):
            $data = array(
                "LoginStatus"   => 0,
                "UserUnique"    => null,
            );
            $this->db->where("id_user", $this->session->UserID);
            $this->db->update("user", $data);

            $this->insert_log(6,"logout", "");
        endif;
        session_destroy();
        redirect(site_url("login"));
    }
     public function register($from = ""){
        if($from != "android"):
            $validasi   = $this->validasi_register("member");
        else:
            $validasi   = $this->validasi_android();
        endif;
         #-----------------------------------------------------
        $PhoneNumber="";
        $status     = FALSE;
        $popup      = FALSE;
        $message    = "";
        $CompanyID  = "";
        $nama_toko  = $this->input->post("nama_toko");
        $nama       = $this->input->post("nama_perusahaan");
        $no_hp      = $this->input->post("no_hp");
        $PhoneCode  = $this->input->post("PhoneCode");
        $email      = $this->input->post("email");
        $password   = $this->input->post("password");
        if($PhoneCode == ""):
            $PhoneCode = "62";
        endif;
        $cek_email  = $this->db->count_all("user where email='$email'");
        if($cek_email > 0):
            $message = "An account with this email already exists. Please re-enter.";
            $popup   = TRUE;
        elseif($cek_email == 0 && !empty($nama) && !empty($email) && !empty($password)):
            $password   = $this->hash($password);
            $no_hp      = $this->PhoneFormat($no_hp);
            $str_module = $this->input->post("module");
            $Module     = $this->createModule(30,$str_module);
            $kode_user  = "";
            $PhoneNumber= $PhoneCode.$no_hp;
            // $kode_user  = $this->user_code_generate();
            $VerificationNumber       = $this->random_number(4);
            $VerificationNumberExpire = date("Y-m-d H:i:s",strtotime("+7 days"));
            $data       = array(
                // "kode_user"  => $kode_user,
                "token"         => $this->token_encode($email),
                "title"         => "MR",
                "nama"          => $nama,
                "email"         => $email,
                "password"      => $password,
                "hak_akses"     => "company",
                "App"           => "pipesys",
                "StatusAccount" => "trial",
                "ExpireAccount" => date("Y-m-d",strtotime("+30 days")),
                "VerificationNumber"       => $VerificationNumber,
                "VerificationNumberExpire" => $VerificationNumberExpire,
                "JoinDate"                 => date("Y-m-d H:i:s"),
                "jenis_kelamin" => "male",
                "status"        => 0,
                "Module"        => $Module,
                "phone"         => $no_hp,
                "PhoneCode"     => $PhoneCode,
                "index"         => 1,
                "user_add"      => $nama,
                "date_add"      => date("Y-m-d H:i:s")
            );
           
            $this->db->insert("user",$data);
            $CompanyID = $this->db->insert_id();
            $this->setting_insert($CompanyID);
            $this->api->generate_coa_list($CompanyID);
            $this->api->generate_coa_setting($CompanyID);
            $PhoneNumber                = $PhoneCode.$this->PhoneFormat($no_hp);
            $VerificationNumberExpire   = $this->tanggal("d M Y H:i",$VerificationNumberExpire);
            $msg                        = "Kode verifikasi anda adalah ".$VerificationNumber.", berlaku sampai ".$VerificationNumberExpire.". Mohon tidak memberikan kode verifikasi kepada siapapun.";
            if($from != "android"):
                $Code       = $this->main->branch_code_generate();
                $data_branch = array(
                    "Code"          => $Code,
                    "CompanyID"     => $CompanyID,
                    "UserCode"      => $kode_user,
                    // "Code"          => $this->branch_code_generate(),
                    "Name"          => $nama_toko,
                    "User_Add"      => $nama,
                    "Date_Add"      => date("Y-m-d H:i:s"),
                    "Active"        => 1,
                    "Index"         => 1,
                );
                $branchid = $this->branch_insert($kode_user,$data_branch);
                $this->store_user($CompanyID,$branchid);
                $this->send_email("register",$email);
                $this->send_sms($PhoneNumber,$msg);
            else:
                $data_branch = array(
                    "CompanyID"     => $CompanyID,
                    "UserCode"      => $kode_user,
                    // "Code"          => $this->branch_code_generate(),
                    "Name"          => $nama,
                    "User_Add"      => $nama,
                    "Date_Add"      => date("Y-m-d H:i:s"),
                    "App"           => "pipesys",
                    "FirstName"     => $nama,
                    "LastName"      => "",
                    "Email"         => $email,
                    "Phone"         => $no_hp,
                    "PhoneCode"     => $PhoneCode,
                    "Password"      => $password,
                    "StatusAccount" => "trial",
                    "ExpireAccount" => date("Y-m-d",strtotime("+30 days")),
                    "Active"        => 1,
                    "Index"         => 1,
                    );
                $branchid    = $this->branch_insert($kode_user,$data_branch);
                $kirim_sms   = $this->send_sms($PhoneNumber,$msg);
                $kirim_email = $this->send_email("register",$CompanyID);
                $this->set_session_user($CompanyID);
                $status   = TRUE;
                $message  = "Register a new account successfully, please check your email or mobile number to verify your account";
            endif;
            $this->set_session_user($CompanyID);
            $status      = TRUE;
            $message     = "Register a new account successfully, please check your email or phone number to verify your account";
        endif;
        $respon = array(
            "status"    => $status,
            "popup"     => $popup,
            "message"   => $message,
            "CompanyID" => $CompanyID,
            "img_logo"  => site_url("img/logo.png"),
            // "redirect"  => site_url("main/tes_email/register/".$email)
            "redirect"      => site_url("verification-account"),
        );
        return $respon;
    }

    public function random_number($digits){
        return rand(pow(10, $digits-1), pow(10, $digits)-1);
    }

    public function store_user($CompanyID,$branchid)
    {
        $store  = array(array("branchid" => $branchid, "hakakses" => "supervisor"));
        $store  = json_encode($store);
        $this->db->set("store",$store);
        $this->db->where("id_user",$CompanyID);
        $this->db->update("user");
    }
    public function setting_insert($CompanyID){
        $data = array(
            "CompanyID" => $CompanyID,
            "Currency"  => "IDR",
            "AmountDecimal" => 0,
            "QtyDecimal"    => 0,
            "NegativeStock" => "allow",
            "CostMethod"    => 'average',
            "AR"            => "[]",
            "AP"            => "[]",
            "AC"            => "[]",
            "Inventory"     => "[]",
            "Asset"         => "[]",
        );
        $this->db->insert("SettingParameter",$data);
    }
    public function branch_insert($kode_user,$data){
        $this->db->insert("Branch",$data);
        return $this->db->insert_id();

    }
    public function vendor_insert($kode_user,$data){
        $this->db->insert("PS_Vendor",$data);
    }
    public function forgot_password(){
        $id_user    = "";
        $email      = $this->input->post("email");
        $a          = $this->user_detail($email);
        if($a):
            $id_user    = $a->id_user;
        endif;
        if(empty($email)):
            $data['inputerror'][] = 'email';
            $data['error_string'][] = $this->lang->line('lb_email_empty');
            $data['status'] = FALSE;
            echo json_encode($data);
            exit();
        elseif(empty($a)):
            $data['inputerror'][] = 'email';
            $data['error_string'][] = $this->lang->line('v_email_not_registered');
            $data['status'] = FALSE;
            echo json_encode($data);
            exit();
        endif;
        #data user----------------------
        $token      = $this->token_encode($id_user);
        $data       = array("token" => $token);
        $this->user_update($email,$data);
        $this->send_email("forgot_password",$id_user);
        #respon-------------------------
        $respon["data"] = $data;
        $respon["status"] = TRUE;
        $respon["message"] = $this->lang->line('lb_instruction');
        $respon["redirect"] = site_url("main/tes_email/forgot_password/".$email);
        return $respon;
    }
    public function reset_password(){
        $respon["status"] = FALSE;
        $respon["message"] = "Please Try Again";

        $id_user        = $this->input->post("id_user");
        $id_user        = $this->token_decode($id_user);
        $password       = $this->input->post("password");
        $password_kon   = $this->input->post("password_kon");

        if($password != $password_kon):
            $respon['inputerror'][] = 'password_kon';
            $respon['error_string'][] = 'Kata sandi tidak cocok';
            $respon['status'] = FALSE;
            echo json_encode($respon);
            exit();
        endif;

        $password   = $this->hash($password);
        $data       = array(
            "password" => $password 
        );
        $this->db->where("id_user",$id_user);
        $query = $this->db->update("user",$data);
        
        if($query):
            $respon["status"] = TRUE;
            $respon["message"] = "Password anda telah disetel ulang silakan coba untuk login";
            $respon["redirect"] = site_url("login");
        endif;
        return $respon;
    }

    public function VerificationAccount($page = ""){
        $respon["status"]       = FALSE;
        $respon["message"]      = "Verifikasi akun gagal";
        $respon["popup"]        = TRUE;

        if($page == "android"):
            $UserID             = $this->input->post("CompanyID");
            $VerificationNumber = $this->input->post("num1");
            $VerificationNumber .= $this->input->post("num2");
            $VerificationNumber .= $this->input->post("num3");
            $VerificationNumber .= $this->input->post("num4");
        else:
            $Token               = $this->input->post("Token");
            $UserID              = $this->token_decode($Token);
            $VerificationNumber  = $this->input->post("VerificationNumber");
            $VerificationNumber  = implode("",$VerificationNumber);
        endif;
        $this->db->select("VerificationNumber,VerificationNumberExpire");
        $this->db->where("id_user",$UserID);
        $query = $this->db->get("user as A");
        if($query->num_rows() > 0):
            $data = $query->row();
            if($VerificationNumber == ""):
                $respon["message"]  = "please fill out your verification code";
            elseif($VerificationNumber != $data->VerificationNumber):
                $respon["message"]  = "Your verification code it is wrong";
            elseif(time() > strtotime($data->VerificationNumberExpire)):
                $respon["message"]  = "Your verification code has expired, please resend a new verification number";
            elseif($VerificationNumber && time() < strtotime($data->VerificationNumberExpire)  && $VerificationNumber == $data->VerificationNumber):
                $this->db->set("StatusVerify",1);
                $this->db->set("status",1);
                $this->db->where("id_user",$UserID);
                $this->db->update("user");
                $respon["status"]   = TRUE;
                if($page == "android"):
                    $respon["message"]  = "Verification account successfully";
                else:
                    $this->session->set_userdata("StatusVerify",1);
                    $respon["redirect"] = site_url('dashboard');
                    $respon["message"]  = "Verification account successfully";
                endif;
            endif;
        endif;
        return $respon;
    }
    public function SendVerificationCode($page = ""){
        if($page == "change_verification"):
            $UserID = $this->session->UserID;
        else:
            $UserID = $this->input->post("UserID");
        endif;
        $Modul       = $this->input->post("Modul");
        $a           = $this->main->user_detail($UserID);
        $PhoneNumber = $a->PhoneNumber;
        $VerificationNumber = $this->main->random_number(4);
        $VerificationNumberExpire = date("Y-m-d H:i:s",strtotime("+7 days"));
        $this->main->user_update($a->id_user,array(
            "VerificationNumber"       => $VerificationNumber,
            "VerificationNumberExpire" => $VerificationNumberExpire,
        ));
        $status     = FALSE;
        if($Modul == "email"):
            $kirim = $this->main->send_email("verification_account",$a->id_user);
            if($kirim):
                $status     = TRUE;
                $message    = "New verification code has been send to email address"." : ".$a->Email; 
            else:
                $message    = "failed to send verification of new code to email address"." : ".$a->Email; 
            endif;
        else:
            $VerificationNumberExpire = $this->main->tanggal("d M Y H:i",$VerificationNumberExpire);
            // $msg = $VerificationNumber." adalah Kode Verifikasi Anda. gunakan sebelum ".$VerificationNumberExpire." - People Shape Sales";
            $msg   = "Kode verifikasi anda adalah ".$VerificationNumber.", berlaku sampai ".$VerificationNumberExpire.". Mohon tidak memberikan kode verifikasi kepada siapapun.";
            $kirim = $this->main->send_sms($PhoneNumber,$msg);
            if($kirim):
                $status     = TRUE;
                $message    = "kirim ulang kode verifikasi"." : ".$a->PhoneNumber; 
            else:
                $message    = "verifikasi kode salah"." : ".$a->PhoneNumber; 
            endif;
        endif;
        $output = array(
            "status"  => $status,
            "message" => $message,
            "data"    => $a,
        );
        return $output;
    }
    public function ChangeVerification(){
        $this->ChangeVerificationValidation();
        $status      = FALSE;
        $UPDATE      = FALSE;
        $UserID      = $this->session->UserID;
        $CompanyID   = $this->session->CompanyID;
        $Modul       = $this->input->post("Modul");
        $Email       = $this->input->post("Email");
        $PhoneCode   = $this->input->post("PhoneCode");
        $Phone       = $this->main->PhoneFormat($this->input->post("Phone"));
        $PhoneNumber = "+".$PhoneCode.$Phone;
        $data = array();
        $datax = array();
        if($Modul == "email"):
            $data["email"] = $Email;
            $data["email"] = $Email;
            $message = "Change email address and send a new verification code failed  Change email address successfully and";
        else:
            $data["PhoneCode"] = $PhoneCode;
            $data["phone"] = $Phone;
            $message = "Change phone number and send a new verification code failed";
        endif;
        if($this->session->UserID):
            $a = $this->main->user_detail($UserID);
            $EmailOld       = $a->Email;
            $PhoneCodeOld   = $a->PhoneCode;
            $PhoneOld       = $a->Phone;
            $data["user_ch"]  = $this->session->NAMA;
            $data["date_ch"]  = date("Y-m-d H:i:s");
            $datax["User_Ch"] = $this->session->NAMA;
            $datax["Date_Ch"] = date("Y-m-d H:i:s");
            $this->db->where("id_user",$UserID);
            $UPDATE = $this->db->update("user",$data);
            $this->db->where("CompanyID",$CompanyID);
            $this->db->where("PhoneCode",$PhoneCodeOld);
            $this->db->where("Phone",$PhoneOld);
            $UPDATE = $this->db->update("user",$datax);
        endif;
        if($UPDATE):
            $respon = $this->SendVerificationCode("change_verification");
            $UPDATE = $respon["status"];
            if($UPDATE):
                $status = TRUE;
                if($Modul == "email"):
                    $message = "Change email address successfully and"." ".$respon["message"];
                else:
                    $message = "Change phone number successfully and"." ".$respon["message"];
                endif;
            endif;
        endif;
        $output = array(
            "status"  => $status,
            "message" => $message,
            "Email"   => $Email,
            "Phone"   => $PhoneNumber,
        );
       return $output;
    }

    private function ChangeVerificationValidation()
    {
        $status      = TRUE;
        $message     = "";
        $Modul       = $this->input->post("Modul");
        $Email       = $this->input->post("Email");
        $PhoneCode   = $this->input->post("PhoneCode");
        $Phone       = $this->input->post("Phone");
        if($Modul == "email"):
            $cek_email   = $this->db->count_all("user where email='$Email'");
            // $cek_branch_email = $this->db->count_all("Branch where Email = '$Email' AND Active = '1'");     
            if($Email == ""):
                $status  = FALSE;
                $message = "Please fill out email address";
            elseif(strpos($Email, '@') == false):
                $status  = FALSE;
                $message = "Email address format incorrect";
            elseif($cek_email > 0):
                $status  = FALSE;
                $message = "An account with this email address already exists"; 
            endif;
        else:
            $cek_phone   = $this->db->count_all("user where phone='$Phone' ");
            // $cek_branch_phone = $this->db->count_all("Branch where Phone = '$Phone' AND Active = '1'");
            if($Phone == ""):
                $status  = FALSE;
                $message = "Please fill out phone number";
            elseif($cek_phone > 0):
                $status  = FALSE;
                $message = "An account with this email address already exists";
            endif;
        endif;
        $output = array(
            "status"  => $status,
            "message" => $message,
        );
        if($output["status"] == FALSE):
            header('Content-Type: application/json');
            echo json_encode($output,JSON_PRETTY_PRINT); 
            exit();
        endif;
    }

    public function send_email($page,$code,$cek_page = ""){
        $lampiran   = FALSE;
        $delete_lampiran = FALSE;
        $attach     = array();
        $bcc        = array();
        if($page == "forgot_password"):
            $a                  = $this->user_detail($code);
            $nama               = $a->nama;
            $email              = $a->email;
            $token              = $a->token;
            $subject            = "Forgot Password";
            $page_email         = "email/index";
            $data["message"]    = site_url("reset-password?t=".$token."&#reset");
            $data["page"]       = "email/forgot_password"; 
        elseif($page == "register"):
            $a                  = $this->user_detail($code);
            $nama               = $a->nama;
            $email              = $a->email;
            $token              = $a->token;
            $VerificationNumber = $a->VerificationNumber;
            $VerificationNumberExpire = $a->VerificationNumberExpire;
            $url_konfirmasi     = site_url(urlencode("konfirmasi-akun?t=".$token));
            $subject            = "Verifikasi Akun Baru ";
            $page_email         = "email/index";
            $data["modul"]      = $page;
            $data["url"]        = $url_konfirmasi;
            $data["page"]       = "email/register";
            $data["VerificationNumber"] = $VerificationNumber;
            $data["VerificationNumberExpire"] = $VerificationNumberExpire;
            // $bcc                = array('ricky@rcelectronic.co.id');
        elseif($page == "verification_account"):
            $a                  = $this->user_detail($code);
            $nama               = $a->nama;
            $email              = $a->email;
            $token              = $a->token;
            $VerificationNumber = $a->VerificationNumber;
            $VerificationNumberExpire = $a->VerificationNumberExpire;
            $url_konfirmasi     = site_url(urlencode("konfirmasi-akun?t=".$token));
            $subject            = "Verifikasi Akun - ".$VerificationNumber;
            $page_email         = "email/index";
            $data["modul"]      = $page;
            $data["url"]        = $url_konfirmasi;
            $data["page"]       = "email/register";
            $data["VerificationNumber"] = $VerificationNumber;
            $data["VerificationNumberExpire"] = $VerificationNumberExpire;
        elseif($page == "send_file"):
            $a                  = $this->branch_by_id($code);
            $nama               = $a->Name;
            $email              = $this->input->post("email");
            $subject            = "Report Employee Visiting Time";
            $page_email         = "email/index";
            $StartDate          = $this->input->post("startDate");
            $EndDate            = $this->input->post("endDate");
            $data["message"]    = "<br> Berikut ini adalah report employee visiting time periode ".$StartDate." - ".$EndDate;
            $data["page"]       = "email/register";
        elseif($page == "buy_voucher"):
            $a                  = $this->voucher_detail($code);
            $nama               = $a->Name;
            $email              = $a->Email;
            $data["data"]       = $a;
            $data["modul"]      = "buy_voucher";
            $subject            = "Transaction Purchase Voucher " . $a->Code;
            $page_email         = "email/index";
            $data["page"]       = "email/buy_voucher";
            // $bcc                = array('ricky@rcelectronic.co.id');
        elseif($page == "acc_voucher"):
            $a            = $this->voucher_detail($code);
            $b            = $this->get_list_voucher($code);
            $c            = array();
            if($a->parentVoucherID):
                $c = $this->get_list_voucher($a->parentVoucherID);
            endif;

            $voucher            = array();
            $voucher_parent     = array();

            foreach ($b as $k => $v) {
                if($a->Module == "1"):
                    array_push($voucher, $v);
                else:
                    array_push($voucher_parent, $v);
                endif;
            }

            foreach ($c as $k => $v) {
                if($a->parentModule == "1"):
                    array_push($voucher, $v);
                else:
                    array_push($voucher_parent, $v);
                endif;
            }

            $nama               = $a->Name;
            $email              = $a->Email;
            $data["data"]       = $a;
            $data["modul"]      = "acc_voucher";
            $data['voucher']    = $voucher;
            $data['voucher_parent'] = $voucher_parent;
            $subject            = "Thank you for Buying Voucher " . $a->Code;
            $page_email         = "email/index";
            $data["page"]       = "email/acc_voucher";
        elseif($page == "saldo_receivable"):
            $nama               = $code->Supplier;
            $email              = $code->VendorEmail;
            $subject            = "Saldo Receivable & Saldo Payable ";
            $page_email         = "email/index";
            if($code->file_name_ar):
                array_push($attach, "file/".$code->file_name_ar);
            endif;
            if($code->file_name_ap):
                array_push($attach, "file/".$code->file_name_ap);
            endif;
            $lampiran           = TRUE;
            $delete_lampiran    = TRUE;
            $data['total_receivable']   = $code->total_receivable;
            $data['total_payable']      = $code->total_payable;
            $data["page"]       = "email/saldo_receivable";
        elseif($page == "testing"):
            // $data["page"]       = "email/saldo_receivable";
            $page_email         = "email/tes";
            $nama = "Tes";
        endif;
        $data["nama"] = $nama;
        if($cek_page == "ya"):
            $this->load->view($page_email,$data);
        else:
            $data_email = $this->db->query("SELECT * FROM smtp");
            if ($data_email->num_rows() > 0):
                $hasil      = $data_email->row(1);
                $protocol   = $hasil->protocol;
                $smtp_host  = $hasil->smtp_host;
                $smtp_port  = $hasil->smtp_port;
                $smtp_user  = $hasil->smtp_user;
                $smtp_pass  = $hasil->smtp_pass;
            endif;
            $config = Array(
                'useragent'     => "Codeigniter",
                'protocol'      => $protocol,
                'smtp_host'     => $smtp_host,
                'smtp_port'     => $smtp_port,
                'smtp_user'     => $smtp_user,
                'smtp_pass'     => $smtp_pass,
                'mailtype'      => 'html',
                'charset'       => 'iso-8859-1',
                'wordwrap'      => TRUE,
                'newline'       => "\r\n",
                '_encoding'     => 'base64'
            );
            $title = $this->getTitleApp();
            $this->load->library('email');
            $this->email->initialize($config);
            $this->email->set_newline("\r\n");
            $this->email->set_mailtype("html");
            $this->email->subject($title." - ".$subject);
            $this->email->from("info.pipesys@gmail.com",$title);
            $this->email->to($email);
            if(count($bcc)>0):
                $this->email->bcc($bcc);
            endif;
            $this->email->message($this->load->view($page_email, $data, TRUE));
            if($lampiran):
                foreach ($attach as $key => $value) {
                    $this->email->attach($value);
                }
                if($delete_lampiran):
                    foreach ($attach as $key => $value) {
                        if(file_exists('./' . $value)){
                            unlink('./' . $value);
                        }
                    }
                endif;
            endif;
            if($page == "send_file"):
                $File           = $this->input->post("File");
                $FileName       = $this->input->post("FileName");
                $this->email->attach(base64_decode($File),'attachment',$FileName,'application/xls');
            endif;
            $send = $this->email->send();
            if($send):
                // print_r("email to ".$email." success");
                if($page == "verification_account" || $page == "register"):
                    return TRUE;
                endif;
            else:
                if($page == "verification_account" || $page == "register"):
                    return FALSE;
                else:
                    $error = $this->email->print_debugger();
                    print_r($error);                    
                endif;
            endif;
        endif;
    }
    public function send_sms($telepon, $message)
    {
        if($telepon):
            $telepon   = str_replace("+", '', $telepon);
            //$userkey  = "d0ntuse"; // userkey lihat di zenziva
            //$passkey  = "P@ssword123!"; // set passkey di zenziva
            $userkey  = "jbiekp";
            $passkey  = "Support09!";
            $url = "https://reguler.zenziva.net/apps/smsapi.php"; #reguler
            //$url = "http://demo.zenziva.net/apps/smsapi.php"; #demo
            $curlHandle = curl_init();
            curl_setopt($curlHandle, CURLOPT_URL, $url);
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, 
              'userkey='.$userkey.'&passkey='.$passkey.'&nohp='.$telepon.'&tipe=reguler&pesan='.urlencode($message));
            curl_setopt($curlHandle, CURLOPT_HEADER, 0);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
            curl_setopt($curlHandle, CURLOPT_POST, 1);
            $results = curl_exec($curlHandle);
            curl_close($curlHandle);
            $status     = false;
            $message    = "";
            $xml        = simplexml_load_string($results);
            $json       = json_encode($xml);
            $respons    = json_decode($json);
            if(isset($respons->message)):      
                if($respons->message->status == 0):
                    $status     = true;
                    $message    = $respons->message->text; 
                else:
                    $status     = false;
                    $message    = $respons->message->text;
                endif;
            endif;
        else:
            $status = false;
            $message= "nomor telepon tidak diketahui";
            $respons = array();
        endif;
        $respons = array(
            "status"    => $status,
            "message"   => $message,
            "full"      => $respons,
        );
        return $respons;
        // Script http API SMS Reguler Zenziva
    }
    #2017-12-13 iqbal
    public function cek_session($page = "")
    {
        if($page == "luar"):
            if($this->session->login):
                if(in_array($this->session->StatusVerify,array(1,2)) && $this->AlertVerification() != 2):
                    redirect('dashboard');
            // elseif(in_array($this->session->StatusParameter,array(1,2))):
            //         redirect();
                else:
                    redirect("verification-account");
                endif;
            endif;
        else:
            if(!$this->session->login):
                $this->session->set_flashdata('message', 'Session is expire');
                redirect("login");
            elseif($this->session->StatusVerify == 0 || $this->AlertVerification() == 2):
                redirect("verification-account");
            elseif($this->session->StatusParameter != 1 and $page != "parameter"):
                redirect("page-setting-parameter");
            endif;
        endif;
    }
    #token 2017-12-13 iqbal
    public function token_encode($token)
    {
        $first  = $this->randomstring(8);
        $second = $this->randomstring(7);
        $token  = $first.$token.$second;
        return $token = base64_encode($token);
    }
    public function token_decode($token)
    {
        $token = base64_decode($token);
        $token = substr($token, 8);
        $token = substr($token, 0,-7);
        return $token;
    }
    private function randomstring($length = 10) {
        $characters         = '1234567890';//abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength   = strlen($characters);
        $randomString       = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    #data update
    public function user_update($code,$data){
        $this->db->where("id_user",$code);
        $this->db->or_where("email",$code);
        $this->db->or_where("token",$code);
        $query = $this->db->update("user",$data);
    }
    #data detail
    public function user_detail($code)
    {
        #update Branch set phone = case when SUBSTRING(phone, 1, 1) = 0 then SUBSTRING(phone,-(LENGTH(phone) - 1),(LENGTH(phone) - 1)) else phone end
        $this->db->select("
            id_user,
            id_user as UserID,
            nama,
            nama as Name,
            email,
            token,
            PhoneCode as PhoneCode,
            phone as Phone,
            VerificationNumber,
            VerificationNumberExpire,
            JoinDate,
            StatusVerify,
            Module,
            StatusParameter,
            email as Email,
            (case when LENGTH(phone) > 0 and SUBSTRING(phone, 1, 1) = 0 then concat('+62',SUBSTRING(phone,-(LENGTH(phone) - 1),(LENGTH(phone) - 1))) 
            when LENGTH(phone) > 0 then concat('+',PhoneCode,phone) else '' end)as PhoneNumber,
        ");
        $this->db->where("id_user",$code);
        $this->db->or_where("email",$code);
        $this->db->or_where("token",$code);
        $query = $this->db->get("user");
        return $query->row();
    }

    #2018-04-06
    public function voucher_detail($VoucherID)
    {
        $CompanyID = $this->session->CompanyID;
        $this->db->select("
            Voucher.VoucherID,
            Voucher.CompanyID,
            Voucher.Code,
            Voucher.Type,
            Voucher.Date,
            Voucher.Qty,
            Voucher.App,
            Voucher.Price,
            (Voucher.TotalPrice + ifnull(parent.TotalPrice,0))  as TotalPrice,
            Voucher.TrxUnique,
            Voucher.Bank,
            Voucher.ExpirePurchase,
            Voucher.Status,
            Voucher.Module,
            ifnull(Voucher.Name, user.nama)    as Name,
            ifnull(Voucher.Email, user.email)  as Email,
            (case 
            when Voucher.App ='pipesys' then 'Pipesys'
            when Voucher.App ='salespro' then 'SalesPro'
            else 'Pipesys & Salespro' end) as App,
            (case 
            when Voucher.Type = 24 THEN '2 Year'
            when Voucher.Type = 12 THEN '1 Year'
            when Voucher.Type = 6 THEN '6 Month'
            when Voucher.Type = 3 THEN '3 Month'
            when Voucher.Type = 1 THEN '1 Month' else 'none' end)   as Type,
            ifnull(parent.Qty,0)        as parentQty,
            ifnull(parent.Price,0)      as parentPrice,
            ifnull(parent.VoucherID,0)  as parentVoucherID,
            ifnull(parent.TrxUnique,0)  as parentTrxUnique,
            ifnull(parent.Module,0)     as parentModule,
        ");
        $this->db->join("user","Voucher.CompanyID = user.id_user","left");
        $this->db->join("Voucher as parent", "parent.ParentID = Voucher.VoucherID","left");
        // $this->db->where("Voucher.CompanyID",$this->session->CompanyID);
        $this->db->where("Voucher.VoucherID",$VoucherID);
        $query = $this->db->get("Voucher");
        return $query->row();
    }

    public function get_list_voucher($id,$page="",$type="")
    {
        $this->db->select("
            VoucherDetail.VoucherDetailID,
            VoucherDetail.App,
            VoucherDetail.Code,
            VoucherDetail.Status,
            VoucherDetail.UseDate,
            VoucherDetail.ExpireDate,
            ifnull(VoucherDetail.Module,'') as Module,
            ifnull(user.nama,'') as usedName,
            (case
                when user.hak_akses = 'company' or user.hak_akses = 'super_admin' then ifnull(user.nama,'')
                else ifnull(company.nama,'')
            end) as usedCompany,

            Voucher.Type as voucherType,
        ");
        $this->db->join("Voucher","VoucherDetail.VoucherID = Voucher.VoucherID","left");
        $this->db->join("user", "user.id_user = VoucherDetail.UsedID", "left");
        $this->db->join("user as company", "company.id_user = user.CompanyID", "left");
        $this->db->order_by("VoucherDetail.Status","ASC");
        if($page == "detail"):
            $this->db->where("VoucherDetail.Code", $id);
            $this->db->where("Voucher.Module", $type);
            $query = $this->db->get("VoucherDetail");
            return $query->row();
        else:
            $this->db->where("VoucherDetail.VoucherID",$id);
            $query = $this->db->get("VoucherDetail");
            return $query->result();
        endif;
    }


    #list data dan list option
    public function category()
    {
        $position   = $this->input->post("level");
        $categoryid = $this->input->post("categoryid");

        $this->db->select("productid as categoryid,UPPER(name) as category_name,code as category_code");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->where("Active",1);
        if($position > 1):
            $position = $position - 1;
            // echo $position;
            $this->db->where("position",$position);
        else:
            $this->db->where("position !=",0);
        endif;
        if($categoryid):
            $this->db->where("productid !=", $categoryid);
        endif;
        $this->db->order_by("name","ASC");
        $query = $this->db->get("ps_product");
        return $query->result();
    }
    public function unit()
    {
        $page   = $this->input->post("page");
        $unitid = $this->input->post("unitid");
        $this->db->select("unitid,name as unit_name,conversion");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->where("Active",1);
        
        if($page == "select"):
            $this->db->where("unitid",$unitid);
        endif;
        $this->db->order_by("name","ASC");
        $query = $this->db->get("ps_unit");
        if($page == "select"):
            $data = $query->row();
        else:
            $data = $query->result();
        endif;
        return $data;
    }

    public function uom()
    {
        $page   = $this->input->post("page");
        $productid = $this->input->post("productid");
        $this->db->select("productid,uom as uom,conversion");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->where("Active",1);
        
        if($page == "select"):
            $this->db->where("productid",$productid);
        endif;
        $this->db->order_by("uom","desc");
        $query = $this->db->get("ps_product_unit");
        if($page == "select"):
            $data = $query->row();
        else:
            $data = $query->result();
        endif;
        return $data;
    }

    public function product($page = "",$search = "")
    {
        $groupname  = $this->input->post('groupname');
        $status     = $this->input->post('status'); // product type item or services
        $page2      = $this->input->post('page');
        $selling    = $this->input->post("selling");
        $BranchID   = $this->input->post("BranchID");

        if($status == 1):
            $status = "service";
        else:
            $status = "item";
        endif;

        if($page == "serverSide"):
            $pageServerSide = $search["page"];
            if($pageServerSide == "count_all"):
                $this->db->where("ps_product.Active", 1);
                $this->db->where("ps_product.ProductType", $status);
                $this->db->where("ps_product.CompanyID", $this->session->CompanyID);
                $this->db->from("ps_product");
                return $this->db->count_all_results();
            endif;
        endif;

        $this->db->select("
            ps_product.productid    as productid,
            ps_product.code         as product_code,
            unit.ProductUnitID      as unitid,
            ps_product.name         as product_name,
            ps_product.type         as product_type,
            ps_product.SNFormat     as serial_format,
            ps_product.SNAuto       as serial_auto,
            ps_product.Uom,
            ps_product.minimumstock as min_qty,
            LCASE(category.name)    as category_name,
            ifnull(unit.Uom,'')     as unit_name,
            unit.conversion         as conversion,
        ");

        if($BranchID):
            $BranchID = explode("-", $BranchID);
            $BranchID = $BranchID[0];
            $this->db->select("
                ifnull(p_branch.PurchasePrice, 0) as purchaseprice,
                ifnull(p_branch.Qty,0) as qty,
                ifnull(p_branch.AveragePrice,0)   as average_price,
            ");
            $this->db->join("PS_Product_Branch as p_branch", "p_branch.ProductID = ps_product.ProductID and p_branch.CompanyID = p_branch.CompanyID");
            $this->db->where("p_branch.BranchID", $BranchID);
        else:
            $this->db->select("
                ifnull(ps_product.PurchasePrice, 0) as purchaseprice,
                ifnull(ps_product.qty,0)            as qty,
                ifnull(ps_product.AveragePrice,0)   as average_price,
            ");
        endif;

        if($page2 == "ar" && $groupname != ''):
            $this->db->select("
                ifnull(
                    (select PriceSell from ps_product_customer mt where mt.CompanyID = ps_product.CompanyID and mt.Status = '1' and mt.ProductID = ps_product.ProductID and mt.GroupName = '$groupname'),
                    ps_product.sellingprice
                ) as sellingprice
            ");
        else:
            $this->db->select("
                ifnull(ps_product.sellingprice,0) as sellingprice,
            ");
        endif;

        if($selling == "active"):
            $this->db->where("ps_product.SalesType", "sell");
        endif;

        $this->db->where("ps_product.ProductType", $status);
        $this->db->join("ps_product as category","ps_product.parentcode = category.code");
        $this->db->join("ps_product_unit as unit", "unit.ProductID = ps_product.ProductID and unit.Uom = ps_product.Uom","left");
        // $this->db->join("ps_unit as unit","ps_product.unitid = unit.unitid","left");
        $this->db->where("ps_product.companyid",$this->session->companyid);
        $this->db->where("category.companyid",$this->session->companyid);
        $this->db->where("ps_product.position",0);
        $this->db->where("ps_product.active",1);
        if($page == "autocomplete"):
            $this->db->group_start();
            $this->db->like("ps_product.code",$search);
            $this->db->or_like("ps_product.name",$search);
            $this->db->or_like("ps_product.unitid",$search);
            $this->db->group_end();
            $this->db->limit(15);
        elseif($page == "select"):
            $this->db->group_start();
            $this->db->where("ps_product.productid",$search);
            $this->db->group_end();
        elseif($page == "array_code"):
            $this->db->where_in("ps_product.Code",$search);
        endif;
        $this->db->from("ps_product");

        if($page == "serverSide"):
            $column = $search['column'];
            $Search = $this->input->post("search")["value"];
            $order_by = $search['order_by'];
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
            if($page == "select"):
                return $query->row();
            elseif($page == "count"):
                return $query->num_rows();
            else:
                return $query->result();
            endif;
        endif;
    }

    public function product_branch($page="",$search="",$BranchID=""){
        $table      = "PS_Product_Branch";
        $CompanyID  = $this->session->CompanyID;

        $this->db->select("
            p.ProductID         as productid,
            p.Code              as product_code,
            p.Name              as product_name,
            $table.AveragePrice as average_price,
            $table.Qty          as qty,
            p.Uom               as unit_name,
            p.Uom,
            branch.Name         as branchName,
            branch.BranchID,
            p.ProductType,
            p.Type,
            p.SalesType,
            0 as import_data,
            '[]' as SN,
            0 as temp_qty,
        ");
        $this->db->join("ps_product as p", "p.ProductID = $table.ProductID and p.CompanyID and $table.CompanyID");
        $this->db->join("Branch as branch", "branch.BranchID = $table.BranchID and branch.CompanyID = $table.CompanyID");
        $this->db->from($table);
        $this->db->where("$table.CompanyID", $CompanyID);
        $this->db->where("p.Active", 1);
        $this->db->where("p.ProductType", "item");
        $this->db->where("p.Position", 0);
        if($page == "array_code"):
            $this->db->where_in("p.Code",$search);
            if($BranchID):
                $this->db->where("branch.BranchID", $BranchID);
            endif;
        endif;
        $query = $this->db->get();

        return $query->result();
    }

    public function product_service($page = "",$search = "")
    {
        $groupname  = $this->input->post('groupname');
        $page2      = $this->input->post('page');
        $this->db->select("
            ps_product.productid    as productid,
            ps_product.code         as product_code,
            ps_product.name         as product_name,
            ps_product.minimumstock as min_qty,
            ifnull(ps_product.qty,0)            as qty,
            LCASE(category.name)    as category_name,
        ");

        if($page2 == "ar" && $groupname != ''):
            $this->db->select("
                ifnull(
                    (select PriceSell from ps_product_customer mt where mt.CompanyID = ps_product.CompanyID and mt.Status = '1' and mt.ProductID = ps_product.ProductID and mt.GroupName = '$groupname'),
                    ps_product.sellingprice
                ) as sellingprice
            ");
        else:
            $this->db->select("
                ifnull(ps_product.sellingprice,0) as sellingprice,
            ");
        endif;

        $this->db->join("ps_product as category","ps_product.parentcode = category.code");
        $this->db->join("ps_unit as unit","ps_product.unitid = unit.unitid","left");
        $this->db->where("ps_product.companyid",$this->session->companyid);
        $this->db->where("category.companyid",$this->session->companyid);
        $this->db->where("ps_product.position",0);
        $this->db->where("ps_product.active",1);
        $this->db->where("ps_product.status",1);
        if($page == "autocomplete"):
            $this->db->group_start();
            $this->db->like("ps_product.code",$search);
            $this->db->or_like("ps_product.name",$search);
            $this->db->group_end();
            $this->db->limit(15);
        elseif($page == "select"):
            $this->db->group_start();
            $this->db->where("ps_product.productid",$search);
            $this->db->group_end();
        elseif($page == "array_code"):
            $this->db->where_in("ps_product.Code",$search);
        endif;
        $query = $this->db->get("ps_product");
        if($page == "select"):
            return $query->row();
        elseif($page == "count"):
            return $query->num_rows();
        else:
            return $query->result();
        endif;
    }

    public function product_serial($page = "",$search = "",$productid="",$select="",$BranchID="")
    {
        $this->db->select("
            pps.ProductSerialID as productserialid,
            pps.ProductID       as productid,
            pps.ReceiveDet      as receivedet,
            pps.SerialNo        as serialno,
            pps.SerialNo        as serialnumber,
        ");
        $this->db->where("pps.CompanyID",$this->session->CompanyID);
        if($page == "autocomplete"):
            if($productid):
                $this->db->where("pps.ProductID",$productid);
            endif;
            if($select == "active"):
                $this->db->where("pps.Status", 1);
            endif;
            $this->db->like("pps.SerialNo",$search);
        elseif($page == "add_serial_mutasi"):
            $this->db->where("pps.ProductID",$search);
        elseif($page == "detail"):
            $this->db->where("pps.ProductID",$search);
            $this->db->limit(1);
        elseif($page == "array"):
            $this->db->where("pps.ProductID",$productid);
            $this->db->where_in("pps.SerialNo", $search);
            if($select == "active"):
                $this->db->where("pps.Status", 1);
            elseif($select == "nonactive"):
                $this->db->where("pps.Status", 0);
            endif;
        else:
            $this->db->where("ReceiveDet",$search);
        endif;

        if($BranchID):
            $this->db->where("BranchID", $BranchID);
        else:
            $this->db->where("BranchID", null);
        endif;
        $query  = $this->db->get("PS_Product_Serial as pps");
        $data   = $query->result();
        return $data;
    }
    
    public function vendor($page = "",$search = ""){
        $position  = $this->input->post('position');
        $page_post = $this->input->post('page');

        if($position == "customer"):
            $position = 2;
        else:
            $position = 1;
        endif;

        if($page == "serverSide"):
            $pageServerSide = $search["page"];
            if($pageServerSide == "count_all"):
                $this->db->where("Active",1);
                $this->db->where("Position",$position);
                $this->db->where("CompanyID", $this->session->CompanyID);
                $this->db->from("PS_Vendor");
                return $this->db->count_all_results();
            endif;
        endif;

        $this->db->select("
            psv.VendorID as vendorid,
            psv.UserCode as usercode,
            psv.ParentID as parentid,
            psv.Position as position,
            psv.Code     as code,
            psv.Name     as name,
            psv.Title    as title,
            psv.Email    as email,
            psv.Address  as address,
            psv.NPWP     as npwp,
            psv.AP_Max   as term,
            ifnull(psv.ProductCustomer,'') as productcustomer,
        ");
        

        if($this->session->app == "pipesys"):
            $this->db->where("App",$this->session->app);
        elseif($this->session->app == "salespro"):
            $this->db->where("App",$this->session->app);
        endif;

        if($page_post == "delivery"):
            $this->db->select("
                ifnull(address.Address,'')   as d_address,
                ifnull(address.City,'')      as d_city,
                ifnull(address.Province,'')  as d_province,
            ");
            $this->db->join("ps_vendor_address address", "address.VendorID = psv.VendorID and address.Delivery = '1'","left");
        elseif($page_post == "invoice"):
            $this->db->select("
                ifnull(address.Address,'')   as d_address,
                ifnull(address.City,'')      as d_city,
                ifnull(address.Province,'')  as d_province,
            ");
            $this->db->join("ps_vendor_address address", "address.VendorID = psv.VendorID and address.Payment = '1'","left");
        endif;

        $this->db->where("psv.Position",$position);
        $this->db->where("psv.CompanyID",$this->session->companyid);
        $this->db->where("psv.Active",1);
        if($page == "autocomplete"):
            $this->db->like("psv.name",$search);
            $this->db->limit(15);
        endif;
        if($page == "serverSide"):
            $this->db->from("PS_Vendor as psv");
            $column = $search['column'];
            $Search = $this->input->post("search")["value"];
            $order_by = $search['order_by'];
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
            $query = $this->db->get("PS_Vendor as psv");
            $data = $query->result();    
            return $data;
        endif;
    }
    public function vendor_address(){
        $vendorid = $this->input->post("vendorid");
        $this->db->select("
            psva.VendorAddressID    as addressid,
            psva.Address            as address,
            psva.City               as city,
            psva.Province           as province,
            psv.VendorID            as vendorid,
            psv.Name                as name,
            ");
        $this->db->join("PS_Vendor as psv", "psva.VendorCode = psv.Code and psva.CompanyID = psv.CompanyID", "left");
        $this->db->where("psv.CompanyID", $this->session->CompanyID);
        if($vendorid != ""):
            $this->db->where("psv.VendorID", $vendorid);
        endif;
        $query = $this->db->get("ps_vendor_address as psva");
        return $query->result();
    }

    public function vendor_bank(){
        $UserRekID = $this->input->post("UserRekID");
        $this->db->select("
            user_rek.UserID,
            user_rek.UserRekID,
            user_rek.BankName,     
            user_rek.BankBranch,   
            user_rek.RekNo,         
            user_rek.AnRek,        
            user.id_user,            
            user.Name,
            ");
        $this->db->join("user", "user_rek.UserRekID = user.UserRekID", "left");
        $this->db->where("user.CompanyID", $this->session->CompanyID);
        if($id_user != ""):
            $this->db->where("user.id_user", $UserRekID);
        endif;
        $query = $this->db->get("user_rekening as user_rek");
        return $query->result();
    }

    public function sales($page = "",$search = ""){
        if($page == "serverSide"):
            $pageServerSide = $search["page"];
            if($pageServerSide == "count_all"):
                $this->db->where("Status", 1);
                $this->db->where("CompanyID", $this->session->CompanyID);
                $this->db->from("PS_Sales");
                return $this->db->count_all_results();
            endif;
        endif;

        $this->db->select("
            pss.SalesID as salesid,
            pss.Code    as code,
            pss.Name    as name,
            pss.Contact as contact,
            pss.Address as address,
            pss.City    as city,
        ");
        $this->db->where("CompanyID",$this->session->companyid);
        $this->db->where("Status",1);
        if($page == "autocomplete"):
            $this->db->like("pss.Name",$search);
            $this->db->limit(15);
        endif;
        if($page == "serverSide"):
            $this->db->from("PS_Sales as pss");
            $column = $search['column'];
            $Search = $this->input->post("search")["value"];
            $order_by = $search['order_by'];
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
            $query = $this->db->get("PS_Sales as pss");
            $data = $query->result();    
            return $data;
        endif;
    }
    public function customer($page = "",$search = ""){
        $this->db->select("
            psv.VendorID as vendorid,
            psv.UserCode as usercode,
            psv.ParentID as parentid,
            psv.Position as position,
            psv.Code     as code,
            psv.Name     as name,
            psv.Title    as title,
            psv.Email    as email,
        ");
        $this->db->where("CompanyID",$this->session->companyid);
        $this->db->where("App",$this->session->app);
        $this->db->where("Position",2);
        if($this->session->app == "pipesys"):
            $this->db->where("Active",1);
        endif;
        if($page == "autocomplete"):
            $this->db->like("psv.name",$search);
            $this->db->limit(15);
        endif;
        $query = $this->db->get("PS_Vendor as psv");
        if($page == "count"):
            return $query->num_rows();
        else:
            $data = $query->result();    
            return $data;
        endif;
    }
    public function branch($page = "",$search = "",$active="", $CompanyID = "none"){
        $ParentID   = $this->getParentID();
        $select     = $this->input->post("select");
        $this->db->select("
            b.BranchID as branchid,
            b.CompanyID as companyid,
            b.Name as name,
            b.Code as code,
            b.Lat as lat,
            b.Lng as lng,
            b.Active,
            b.StatusAccount,
            b.ExpireAccount,
            b.Index,
        ");
        $this->db->where("b.App",$this->session->app);
        if($active == 1 || $select == "active"):
            $this->db->where("b.Active", 1);
        endif;
        if($page == "autocomplete"):
            $this->db->like("b.name",$search);
            $this->db->limit(15);
        endif;
        if($CompanyID == "none"):
            $this->db->where("b.CompanyID",$this->session->CompanyID);
        elseif($CompanyID == "all"):
            $this->db->where_in("b.CompanyID",$ParentID);
        else:
            $this->db->where("b.CompanyID",$CompanyID);
        endif;
        $query  = $this->db->get("Branch as b");
        $data   = $query->result();    
        return $data;
    }
    public function branch_by_id($BranchID){
        $this->db->select("
            BranchID,
            Name,
            CompanyID,
            Email,
            ");
        $this->db->where("BranchID", $BranchID);
        $query = $this->db->get("Branch");

        return $query->row();
    }

    public function sell($page = "",$search = "",$date = "",$p2="",$dt=""){
        $CompanyID = $this->session->CompanyID;
        $select = $this->input->post("select");
        $id     = $this->input->post("id");
        $crud   = $this->input->post("crud");
        $temp_sellno     = $this->input->post("temp_sellno");
        $product_status  = $this->input->post('product_status');

        if($p2 == "serverSide"):
            $pageServerSide = $dt["page"];
            if($pageServerSide == "count_all"):
                $this->db->where("DeliveryStatus", 0);
                $this->db->where("InvoiceStatus", 0);
                $this->db->where("Status", 1);
                $this->db->where("CompanyID", $this->session->CompanyID);
                $this->db->from("PS_Sell");
                return $this->db->count_all_results();
            endif;
        endif;

        $this->db->select("
            sell.SellNo     as sellno,
            sell.Total      as total,
            sell.Payment    as payment,
            sell.Paid       as paid,
            sell.Paid       as status,
            sell.Date       as date,
            sell.Tax,
            ifnull(sell.PPN,0) as ppn,
            sell.SalesID,
            sell.DeliveryTo,
            sell.DeliveryAddress,
            sell.DeliveryCity,
            sell.DeliveryProvince,
            sell.DeliveryStatus,
            ifnull(sell.DeliveryCost, 0) as DeliveryCost,
            ifnull(sell.Term,0) as Term,
            sell.PaymentAddress,
            sell.PaymentCity,
            sell.PaymentProvince,

            pv.VendorID     as vendorid,
            pv.Name         as vendorname,

            sell.SalesID    as salesid,
            sales.Name      as salesName,

            sell.BranchID,
            ifnull(Branch.Name,'') as branchName,
        ");
        $this->db->join("PS_Vendor      as pv","sell.VendorID = pv.VendorID","left");
        $this->db->join("PS_Sales       as sales", "sell.SalesID = sales.SalesID", "left");
        $this->db->join("Branch", "sell.BranchID = Branch.BranchID and sell.CompanyID = Branch.CompanyID", "left");
        $this->db->where("sell.CompanyID",$this->session->CompanyID);

        if($product_status == 1):
            $this->db->where("sell.ProductType", 1);
        else:
            $this->db->where("sell.ProductType", 0);
        endif;

        if($page == "autocomplete"):
            $this->db->like("sell.Sellno",$search);
            $this->db->limit(15);
        elseif($page == "branch"):
            $this->db->where("sell.BranchID",$search);
            $this->db->where("sell.Paid",0);
        elseif($page == "customer"):
            $this->db->where("sell.VendorID",$search);
            $this->db->where("sell.Paid",0);
        elseif($page == "delivery"):
            $where = "ifnull((select count(dt.SellNo) from AP_Retur_Det as dt left join AP_Retur as d 
            on d.ReturNo = dt.ReturNo and d.CompanyID = dt.CompanyID 
            where d.Status = 1 and dt.CompanyID = '$CompanyID' and dt.SellNo = sell.SellNo), 0)";
            $this->db->where($where." <= 0");
            $this->db->where("sell.Mobile", 0);
            $this->db->where("sell.VendorID",$search);
            $this->db->where("sell.Status", 1);
            $this->db->where("sell.DeliveryParameter", 1);
            if($crud == "update"):
                $temp_sellno = explode(",", $temp_sellno);
                $this->db->group_start();
                $this->db->where("sell.DeliveryStatus",0);
                $this->db->where("sell.InvoiceStatus",0);
                $this->db->or_where_in("sell.SellNo", $temp_sellno);
                $this->db->group_end();
            else:
                $this->db->where("sell.DeliveryStatus",0);
                $this->db->where("sell.InvoiceStatus",0);
            endif;
        elseif($page == "return_sales"):
            $where = "ifnull((select count(dt.SellNo) from PS_Delivery_Det as dt left join PS_Delivery as d 
            on d.DeliveryNo = dt.DeliveryNo and d.CompanyID = dt.CompanyID
            where d.Status = 1 and dt.CompanyID = '$CompanyID' and dt.SellNo = sell.SellNo), 0)";
            $ck_invoice = "(select count(dt.SellNo) from PS_Invoice_Detail dt join PS_Invoice mt
                on mt.InvoiceNo = dt.InvoiceNo and mt.CompanyID = dt.CompanyID 
                where mt.Status = '1' and dt.CompanyID = sell.CompanyID and dt.SellNo = sell.SellNo
                )";
            $ck_return = "(select sum(dt.Qty) from AP_Retur_Det dt join AP_Retur mt 
                on mt.ReturNo = dt.ReturNo and mt.CompanyID = dt.CompanyID
                where mt.Status = '1' and dt.CompanyID = sell.CompanyID and dt.SellNo = sell.SellNo
                )";
            $ck_qty    = "(select sum(dt.Qty) from PS_Sell_Detail dt where dt.CompanyID = sell.CompanyID and dt.SellNo = sell.SellNo)";

            $this->db->where("sell.Mobile", 0);
            $this->db->where("sell.VendorID",$search);
            $this->db->where("sell.DeliveryStatus",0);
            $this->db->where("sell.DeliveryParameter", 0);
            $this->db->where("sell.Status", 1);
            $this->db->where("sell.ProductType", 0);
            if($crud == "update"):
                $this->db->group_start();
                $this->db->where($where." <= 0");
                $this->db->where("ifnull($ck_invoice,0) <= 0");
                $this->db->where("ifnull($ck_return,0) < ifnull($ck_qty,0)");
                $this->db->or_where_in("sell.SellNo", $temp_sellno);
                $this->db->group_end();
            else:
                $this->db->where($where." <= 0");
                $this->db->where("ifnull($ck_invoice,0) <= 0");
                $this->db->where("ifnull($ck_return,0) < ifnull($ck_qty,0)");
            endif;
        elseif($page == "detail"):
            $this->db->where("sell.SellNo", $search);
        endif;
        if($date):
            $this->db->where("DATE(sell.Date) <=",date("Y-m-d",strtotime($date)));
        endif;
        $this->db->from("PS_Sell as sell");

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
            $this->db->order_by("DATE(sell.Date)","DESC");
            $query  = $this->db->get();
            if($page == "count"):
                return $query->num_rows();
            elseif($page == "detail"):
                return $query->row();
            else:
                $data   = $query->result();    
                return $data;
            endif; 
        endif;   
    }
    public function sell_detail($page = "",$search = "",$method=""){
        $CompanyID      = $this->session->CompanyID;
        if($method      == ""):
            $method     = $this->input->post('method');
        endif;
        $temp_selldet   = $this->input->post('temp_selldet');
        $vendorid       = $this->input->post('vendorid');
        $tax            = $this->input->post('tax');
        $product_status = $this->input->post('product_status');

        $this->db->select("
            sell.SellDet        as selldet,
            sell.SellNo         as sellno,
            sell.ProductID      as productid,
            sell.Qty            as product_qty,
            sell.Conversion     as product_konv,
            sell.Type           as product_type,
            sell.Uom            as product_unitid,
            sell.Price          as product_price,
            sell.Discount       as product_discount,
            ifnull(unit.Uom,'') as product_unitname,
            p.Code              as product_code,
            p.Name              as product_name,
            p.SellingPrice      as sellprice,
            PS_Sell.Tax         as Tax,
            PS_Sell.DeliveryCost,
            ifnull(PS_Sell.Module,'') as sellModule,

            ifnull(sell.DeliveryQty, 0)    as delivery_qty,
            ifnull(sell.Cost, 0)           as Cost,
            ifnull(Branch.Name,'')          as branchName,
        ");
        $this->db->join("PS_Sell", "sell.SellNo = PS_Sell.SellNo and sell.CompanyID = PS_Sell.CompanyID", "left");
        $this->db->join("Branch", "PS_Sell.BranchID = Branch.BranchID and PS_Sell.CompanyID = Branch.CompanyID", "left");
        $this->db->join("ps_product as p","sell.ProductID = p.ProductID","left");
        // $this->db->join("ps_unit    as unit", "sell.UnitID = unit.UnitID", "left");
        $this->db->join("ps_product_unit as unit", "sell.Uom = unit.ProductUnitID", "left");
        $this->db->where("sell.CompanyID",$this->session->CompanyID);

        if($page == "autocomplete"):
            $this->db->like("sell.Sellno",$search);
            $this->db->limit(15);
        elseif($page == "branch"):
            $this->db->where("BranchID",$search);
        elseif($page == "sell" || $page == "retur"):
            $this->db->where("sell.SellNo",$search);
        elseif($page == "delivery"):
            $where = "ifnull((select count(dt.SellNo) from AP_Retur_Det as dt left join AP_Retur as d 
            on d.ReturNo = dt.ReturNo and d.CompanyID = dt.CompanyID
            where d.Status = 1 and dt.CompanyID = '$CompanyID' and dt.SellNo = sell.SellNo), 0)";
            $this->db->where($where." <= 0");
            $this->db->where("PS_Sell.Status", 1);
            $this->db->where("PS_Sell.Mobile", 0);
            $this->db->where("PS_Sell.VendorID", $vendorid);
            $this->db->where("PS_Sell.DeliveryParameter", 1);
            if($search != ""):
                $this->db->where("sell.SellNo",$search);
            else:
                if($tax == 1):
                    $this->db->where("PS_Sell.Tax", 1);
                elseif($tax == 0):
                    $this->db->where("PS_Sell.Tax", 0);
                endif;
            endif;

            if($product_status == 1):
                $this->db->where("PS_Sell.ProductType", 1);
            else:
                $this->db->where("PS_Sell.ProductType", 0);
            endif;

            if($method == "update"):
                $temp_selldet = explode(",", $temp_selldet);
                $this->db->group_start();
                $this->db->where("ifnull(sell.DeliveryQty, 0) < sell.Qty");
                $this->db->where("PS_Sell.InvoiceStatus", 0);
                $this->db->or_where_in("sell.SellDet", $temp_selldet);
                $this->db->group_end();
            elseif($method == "insert"):
                $this->db->where("ifnull(sell.DeliveryQty, 0) < sell.Qty");
                $this->db->where("PS_Sell.InvoiceStatus", 0);
            endif;
        elseif($page == "return_sales"):
            $this->db->where("PS_Sell.Mobile", 0);
            $this->db->where("PS_Sell.ProductType", 0);
            $this->db->where("sell.SellNo",$search);
            $where = "ifnull((select sum(dt.Qty) from AP_Retur_Det as dt left join AP_Retur as d 
            on d.ReturNo = dt.ReturNo and d.CompanyID = dt.CompanyID
            where d.Status = 1 and dt.CompanyID = '$CompanyID' and dt.SellDet = sell.SellDet), 0)";
            $this->db->select("sell.Qty - ".$where." as qty_stock");
            if($method == "update"):
                $temp_selldet = explode(",", $temp_selldet);
                $this->db->group_start();
                $this->db->where($where." < sell.Qty");
                $this->db->or_where_in("sell.SellDet", $temp_selldet);
                $this->db->group_end();
            else:
                $this->db->where($where." < sell.Qty");
            endif;
        endif;
        $this->db->order_by("sell.Date_Add","DESC");
        $query  = $this->db->get("PS_Sell_Detail as sell");
        $data   = $query->result();    
        return $data;
    }
    public function sell_detail_sum($page = "")
    {
        $select = "*";
        if($page == "qty"):
            $select = "SUM(Qty) as sum";
        elseif($page == "total_amount"):
            $select = "SUM(Qty * Price) as sum";
        endif;
        $this->db->select($select);
        $this->db->where("sell.CompanyID",$this->session->CompanyID);
        // $this->db->where("sell.Status", 1);
        $query  = $this->db->get("PS_Sell_Detail as sell");
        $a      = $query->row();
        return $a->sum;
    }
    public function purchase_detail_sum($page = "")
    {
        $select = "*";
        if($page == "qty"):
            $select = "SUM(Qty) as sum";
        elseif($page == "total_amount"):
            $select = "SUM(Qty * Price) as sum";
        endif;
        $this->db->select($select);
        $this->db->where("sell.CompanyID",$this->session->CompanyID);
        $query  = $this->db->get("AP_GoodReceipt_Det as sell");
        $a      = $query->row();
        return $a->sum;
    }

    #purchase
    public function purchase($page="", $search="",$p2="",$dt=""){
        $select = $this->input->post("select");
        $id     = $this->input->post("id");
        $crud   = $this->input->post("crud");
        $temp_purchaseno     = $this->input->post("temp_purchaseno");
        $product_status      = $this->input->post("product_status");

        if($p2 == "serverSide"):
            $pageServerSide = $dt["page"];
            if($pageServerSide == "count_all"):
                $this->db->where("DeliveryStatus", 0);
                $this->db->where("Status", 1);
                $this->db->where("CompanyID", $this->session->CompanyID);
                $this->db->from("PS_Purchase");
                return $this->db->count_all_results();
            endif;
        endif;

        $this->db->select("
            purchase.PurchaseNo,
            purchase.Total,
            purchase.Payment,
            purchase.Date,
            purchase.SalesID,
            purchase.DeliveryAddress,
            purchase.DeliveryCity,
            purchase.DeliveryProvince,
            purchase.DeliveryStatus,
            purchase.PaymentTo,
            purchase.PaymentAddress,
            purchase.PaymentCity,
            purchase.PaymentProvince,
            purchase.DeliveryCost,
            purchase.Tax,
            ifnull(purchase.PPN,0) as ppn,

            pv.VendorID     as vendorid,
            pv.Name         as vendorname,

            purchase.BranchID,
            ifnull(Branch.Name,'') as branchName,
        ");

        $this->db->join("PS_Vendor pv","purchase.VendorID = pv.VendorID","left");
        $this->db->join("Branch", "purchase.BranchID = Branch.BranchID and purchase.CompanyID = Branch.CompanyID", "left");
        $this->db->where("purchase.CompanyID",$this->session->CompanyID);
        $this->db->from("PS_Purchase as purchase");

        if($product_status == 1):
            $this->db->where("purchase.ProductType", 1);
        else:
            $this->db->where("purchase.ProductType", 0);
        endif;

        if($page == "receive"):
            if($crud == "update"):
                $this->db->where("purchase.VendorID",$search);
                $this->db->where("purchase.Status", 1);
                $this->db->group_start();
                $this->db->where("purchase.DeliveryStatus",0);
                $this->db->or_where("purchase.PurchaseNo", $temp_purchaseno);
                $this->db->group_end();
            else:
                $this->db->where("purchase.VendorID",$search);
                $this->db->where("purchase.DeliveryStatus",0);
                $this->db->where("purchase.Status", 1);
            endif;
        endif;

        if($p2="serverSide"):
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
            $this->db->order_by("DATE(purchase.Date)","ASC");
            $query  = $this->db->get();
            if($page == "count"):
                return $query->num_rows();
            elseif($page == "detail"):
                return $query->row();
            else:
                $data   = $query->result();    
                return $data;
            endif;
        endif;  
    }
    public function purchase_detail($page="",$search="",$method=""){
        if($method == ""):
            $method         = $this->input->post('method');
        endif;
        $temp_purchasedet   = $this->input->post('temp_purchasedet');
        $product_status     = $this->input->post("product_status");

        // purchasedet.UnitID          as product_unitid,unit.Name                   as product_unitname,
        $this->db->select("
            purchasedet.PurchaseDet     as Purchase_purchasedet,
            purchasedet.PurchaseNo      as PurchaseNo,
            purchasedet.ProductID       as productid,
            purchasedet.Qty             as product_qty,
            purchasedet.Conversion      as product_konv,
            purchasedet.Uom             as product_unitid,
            purchasedet.Price           as product_price,
            purchasedet.Type            as product_type,
            purchasedet.Discount        as product_discount,
            purchase.DeliveryCost,
            purchase.Tax                as purchaseTax,
            unit.Uom                    as product_unitname,
            sales.Name                  as SalesName,
            p.Code                      as product_code,
            p.Name                      as product_name,
            p.SNAuto                    as serial_auto,
            ifnull(purchasedet.ReceiveQty, 0)   as recevice_qty,

            (case
                when purchase.Tax = 1 then purchase.PPN
                else '0'
            end) as tax,

            purchase.BranchID,
            ifnull(Branch.Name,'') as branchName,

        ");
        $this->db->join("PS_Purchase    as purchase", "purchasedet.PurchaseNo = purchase.PurchaseNo and purchasedet.CompanyID = purchase.CompanyID", "left");
        $this->db->join("Branch", "purchase.BranchID = Branch.BranchID and purchase.CompanyID = Branch.CompanyID", "left");
        $this->db->join("PS_Sales           as sales","purchase.SalesID = sales.SalesID","left");
        $this->db->join("ps_product         as p","purchasedet.ProductID = p.ProductID","left");
        // $this->db->join("ps_unit            as unit", "purchasedet.UnitID = unit.UnitID", "left");
        $this->db->join("ps_product_unit    as unit", "purchasedet.Uom = unit.ProductUnitID", "left");
        $this->db->where("purchasedet.CompanyID",$this->session->CompanyID);

        if($product_status == 1):
            $this->db->where("purchase.ProductType", 1);
        else:
            $this->db->where("purchase.ProductType", 0);
        endif;

        if($page == "autocomplete"):
            $this->db->like("purchasedet.PurchaseDet",$search);
            $this->db->limit(15);
        elseif($page == "penerimaan"):
            $this->db->where("purchasedet.PurchaseNo",$search);
            if($method == "update"):
                $temp_purchasedet = explode(",", $temp_purchasedet);
                $this->db->group_start();
                $this->db->where("ifnull(purchasedet.ReceiveQty, 0) < purchasedet.Qty");
                $this->db->or_where_in("purchasedet.PurchaseDet", $temp_purchasedet);
                $this->db->group_end();
            elseif($method == "insert"):
                $this->db->where("ifnull(purchasedet.ReceiveQty, 0) < purchasedet.Qty");
            endif;
        elseif($page == "array"):
            $this->db->where_in("purchasedet.PurchaseNo",$search);
        else:
            $this->db->where("purchasedet.PurchaseNo",$search);
        endif;

        $this->db->order_by("purchasedet.Date_Add","DESC");
        $query  = $this->db->get("PS_Purchase_Detail as purchasedet");
        $data   = $query->result();    
        return $data;
    }
    #end purchase

    #receive 
    public function receive($page="", $search="",$p2="",$dt="")
    {   
        $select             = $this->input->post("select");
        $id                 = $this->input->post("id");
        $crud               = $this->input->post("crud");
        $temp_receiveno     = $this->input->post("temp_receiveno");
        $CompanyID          = $this->session->CompanyID;

        if($p2 == "serverSide"):
            $pageServerSide = $dt["page"];
            if($pageServerSide == "count_all"):
                $this->db->where("InvoiceStatus", 0);
                $this->db->where("Status", 1);
                $this->db->where("CompanyID", $this->session->CompanyID);
                $this->db->from("AP_GoodReceipt");
                return $this->db->count_all_results();
            endif;
        endif;

        $this->db->select("
            gr.ReceiveNo    as receiveno,
            gr.ReceiveName  as receivename,
            gr.Date         as date,
            gr.Tax,
            gr.InvoiceStatus,
            gr.Type,
            ifnull(gr.PPN,0) as ppn,
            gr.VendorID     as vendorid,
            v.Name          as vendorname,
            gr.BranchID,
            ifnull(Branch.Name,'') as branchName,
        ");
        $this->db->join("PS_Vendor      as v","gr.VendorID = v.VendorID and gr.CompanyID = v.CompanyID","left");
        $this->db->join("Branch", "gr.BranchID = Branch.BranchID and gr.CompanyID = Branch.CompanyID", "left");
        $this->db->where("gr.CompanyID",$CompanyID);
        $this->db->order_by("gr.receiveno","DESC");
        $this->db->from("AP_GoodReceipt as gr");
        if($page == "receive1"):
            if($crud == "update"):
                $this->db->where("gr.VendorID",$search);
                $this->db->where("gr.Status", 1);
                $this->db->group_start();
                $this->db->or_where("gr.ReceiveNo", $temp_receiveno);
                $this->db->group_end();
            else:
                $this->db->where("gr.VendorID",$search);
                $this->db->where("gr.Status", 1);
            endif;
        endif;
        $this->db->order_by("DATE(gr.Date)","ASC");
        if($page == "retur"):
            // $where = "ifnull((select count(dt.ReceiveNo) from AP_GoodReceipt_Det as dt left join AP_GoodReceipt as d 
            // on d.ReceiveNo = dt.ReceiveNo and d.CompanyID = dt.CompanyID
            // where d.Status = 1 and dt.CompanyID = '$CompanyID' and dt.ReceiveNo = gr.ReceiveNo), 0)";
            $ck_invoice = "(select count(dt.ReceiveNo) from PS_Invoice_Detail dt join PS_Invoice mt
                on mt.InvoiceNo = dt.InvoiceNo and mt.CompanyID = dt.CompanyID 
                where mt.Status = '1' and dt.CompanyID = gr.CompanyID and dt.ReceiveNo = gr.ReceiveNo
                )";
            $ck_return = "(select sum(dt.Qty) from AP_Retur_Det dt join AP_Retur mt 
                on mt.ReturNo = dt.ReturNo and mt.CompanyID = dt.CompanyID
                where mt.Status = '1' and dt.CompanyID = gr.CompanyID and dt.ReceiveNo = gr.ReceiveNo
                )";
            $ck_qty    = "(select sum(dt.Qty) from AP_GoodReceipt_Det dt where dt.CompanyID = gr.CompanyID and dt.ReceiveNo = gr.ReceiveNo)";

            $this->db->where("gr.ProductType", 0);
            $this->db->where("gr.VendorID",$search);
            $this->db->where("gr.InvoiceStatus",0);
            $this->db->where("gr.Status", 1);
            if($crud == "update"):
                $this->db->group_start();
                $this->db->where($where." <= 0");
                $this->db->where("ifnull($ck_invoice,0) <= 0");
                $this->db->where("ifnull($ck_return,0) < ifnull($ck_qty,0)");
                $this->db->or_where_in("gr.ReceiveNo", $temp_receiveno);
                $this->db->group_end();
            else:
                // $this->db->where($where." <= 0");
                $this->db->where("ifnull($ck_invoice,0) <= 0");
                $this->db->where("ifnull($ck_return,0) < ifnull($ck_qty,0)");
            endif;
        endif;

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
            $query  = $this->db->get();
            if($page == "count"):
                return $query->num_rows();
            elseif($page == "detail"):
                return $query->row();
            else:
                return $query->result();
            endif;
        endif;
    }

    public function receive_detail($page="",$search="",$method="")
    { 
        $CompanyID      = $this->session->CompanyID;
        if($method      == ""):
            $method     = $this->input->post('method');
        endif;
        $temp_receiveno  = $this->input->post('temp_receiveno');
        $temp_receivedet = $this->input->post('temp_receivedet');
        $vendorid        = $this->input->post('vendorid');
        // $tax            = $this->input->post('tax');

        $this->db->select("
            gd.ReceiveDet                                   as receive_det,
            gd.ReceiveNo                                    as receive_no,
            ifnull(gd.ReceiveNo,'')                         as ReceiveNo,
            ifnull(purchase.PurchaseNo,'')                  as PurchaseNo,
            (case 
                when gd.ReceiveNo  is not null then gd.ReceiveNo
                when gd.PurchaseNo is not null then purchase.PurchaseNo
                else purchase.PurchaseNo
            end)                                            as transactionCode,
            gd.ProductID        as productid,
            gd.Qty              as product_qty,
            gd.Conversion       as product_konv,
            gd.Price            as product_price,
            gd.SubTotal         as product_subtotal,
            gd.Discount         as product_discount,
            ifnull(g.PPN,'0')   as tax,
            g.Type,
            g.InvoiceStatus,
            g.CostPaid,
            g.Payment as TotalPrice,
            g.CompanyID,
            ifnull(g.Module,'') as Module,
            gd.Uom              as unitid,
            ifnull(unit.Uom,'') as product_unitname,
            ps_product.Code     as product_code,
            ps_product.Name     as product_name,
            ps_product.Qty      as product_stock,
            ps_product.type     as product_type,
            ps_product.SNAuto   as serial_auto,

            ifnull(unit.Uom,'') as unit_name,

            g.BranchID,
            ifnull(Branch.Name, '') as branchName,
        ");
        $this->db->join("AP_GoodReceipt as g","gd.ReceiveNo = g.ReceiveNo and gd.CompanyID = g.CompanyID","left");
        $this->db->join("Branch", "g.BranchID = Branch.BranchID and g.CompanyID = Branch.CompanyID", "left");
        $this->db->join("PS_Purchase as purchase","gd.PurchaseNo = purchase.PurchaseNo and gd.CompanyID = purchase.CompanyID","left");
        $this->db->join("ps_product","gd.ProductID = ps_product.ProductID and gd.CompanyID = ps_product.CompanyID","left");
        // $this->db->join("ps_unit","gd.UnitID = ps_unit.UnitID","left");
        $this->db->join("ps_product_unit as unit", "gd.Uom = unit.ProductUnitID", "left");
        $this->db->where("gd.CompanyID",$CompanyID);
        if($page != "list"):
            $this->db->where("g.InvoiceStatus",0);
        endif;
        if($page == "autocomplete"):
            $this->db->like("gd.ReceiveDet",$search);
            $this->db->limit(15);
        elseif($page == "penerimaan"):
            $this->db->where("gd.ReceiveNo",$search);
            if($method == "update"):
                $temp_receivedet = explode(",", $temp_receivedet);
                $this->db->group_start();
                $this->db->where("ifnull(ps_product.product_stock, 0) < gd.Qty");
                $this->db->or_where_in("ps_product.product_stock", $temp_receivedet);
                $this->db->group_end();
            elseif($method == "insert"):
                $this->db->where("ifnull(ps_product.product_stock, 0) < gd.Qty and ps_product.CompanyID < gd.CompanyID");

            endif;
        elseif($page == "retur"): // untuk retur
            $this->db->where("gd.ReceiveNo", $search);
            $this->db->where("g.ProductType", 0);
            $where = "ifnull((select sum(dt.Qty) from AP_Retur_Det as dt left join AP_Retur as d 
            on d.ReturNo = dt.ReturNo and d.CompanyID = dt.CompanyID
            where d.Status = 1 and dt.CompanyID = '$CompanyID' and dt.ReceiveDet = gd.ReceiveDet), 0)";
            $this->db->select("gd.Qty - ".$where." as qty_stock,$where as receive_qty,");
            if($method == "update"):
                $temp_receivedet = explode(",", $temp_receivedet);
                $this->db->group_start();
                $this->db->where($where." < gd.Qty");
                $this->db->or_where_in("gd.ReceiveDet", $temp_receivedet);
                $this->db->group_end();
            else:
                $this->db->where($where." < gd.Qty");
            endif;
        elseif($page == "detail"):
            $this->db->where("gd.ReceiveDet", $search);
        elseif($page == "list"):
            $this->db->where("gd.ReceiveNo", $search);
        endif;
        $query = $this->db->get("AP_GoodReceipt_Det as gd");
        if($page == "detail"):
            return $query->row();
        else:
            return $query->result();
        endif;
    }
    #------------------------------------------------------------------------------------------------------------------------------
    public function company($page = "",$CompanyID="")
    {   
        $modul = $this->input->post("modul");
        $modul = str_replace("/", "", $modul);

        if($page == "detail"):
            $this->db->where("id_user",$CompanyID);
        else:
            if(in_array($this->session->hak_akses, array("company", "super_admin"))):
                $this->db->where("id_user",$this->session->id_user);
            else:
                if($modul == "user-account"):
                    $this->db->where("id_user",$this->session->id_user);
                else:
                    $this->db->where("id_user",$this->session->CompanyID);
                endif;
            endif;
        endif;
        $this->db->join("SettingParameter as sp","user.id_user = sp.CompanyID",'left');
        $query  = $this->db->get("user");
        $data   = $query->row();
        return $data;
    }
    public function get_one_column($table,$column,$where){
        $this->db->select($column);
        $this->db->where($where);
        $this->db->from($table);
        $query = $this->db->get();

        return $query->row();
    }
    public function company_logo()
    {
        $this->db->select("(CASE WHEN img_bin IS NOT NULL THEN CONCAT('$this->host/img/logo/',img_bin) ELSE  '$this->host/img/noimage.png' END) as photo");
        $this->db->where("CompanyID",$this->session->id_user);
        $this->db->or_where("id_user",$this->session->id_user);
        $query  = $this->db->get("user");
        $data   = $query->row();
        return $data->photo;   
    }
    #qty penerimaan dan mutasi
    public function penerimaan_qty($page ="",$productid="",$qty=""){
        if($qty > 0):
            if($page == "done"):
            $this->db->query("UPDATE ps_product set Qty=ifnull(Qty,0)+$qty WHERE productid='$productid' ");
            elseif($page == "cancel"):
            $this->db->query("UPDATE ps_product set Qty=ifnull(Qty,0)-$qty WHERE productid='$productid' ");
            endif;
        endif;
    }
    public function mutasi_qty($page ="",$type="",$BranchID ="",$productid="",$qty=""){
        $CompanyID = $this->session->CompanyID;
        if($qty > 0):
            if($page == "to"):
                if($type == 2):
                    $this->db->query("UPDATE ps_product set Qty=ifnull(Qty,0)+$qty WHERE CompanyID='$CompanyID' AND ProductID='$productid' and BranchID is null");
                else:
                    $this->db->query("UPDATE PS_Product_Branch set Qty=ifnull(Qty,0)+$qty WHERE CompanyID='$CompanyID' AND BranchID='$BranchID' AND ProductID='$productid' ");
                endif;
                
            elseif($page == "from"):
                if($type != 0):
                    $this->db->query("UPDATE PS_Product_Branch set Qty=ifnull(Qty,0)-$qty WHERE CompanyID='$CompanyID' AND BranchID='$BranchID' AND ProductID='$productid' ");
                else:
                    $this->db->query("UPDATE ps_product set Qty=ifnull(Qty,0)-$qty WHERE CompanyID='$CompanyID' AND ProductID='$productid' and BranchID is null");
                endif;
            endif;
        endif;
    }
    public function retur_qty($page ="",$productid="",$qty="",$BranchID="")
    {
        $CompanyID = $this->session->CompanyID;
        if($qty > 0):
            if($page == "done"):
                if($BranchID):
                    $this->db->query("UPDATE PS_Product_Branch set Qty=ifnull(Qty,0)-$qty where productid='$productid' and BranchID = '$BranchID'");
                else:
                    $this->db->query("UPDATE ps_product set Qty=ifnull(Qty,0)-$qty WHERE productid='$productid' ");
                endif;
            elseif($page == "cancel"):
                if($BranchID):
                    $this->db->query("UPDATE PS_Product_Branch set Qty=ifnull(Qty,0)+$qty where productid='$productid' and BranchID = '$BranchID'");
                else:
                    $this->db->query("UPDATE ps_product set Qty=ifnull(Qty,0)+$qty WHERE productid='$productid' ");
                endif;
            endif;
        endif;
    }
    #validasi -------------------------------------------------------------------------------------
    private function validasi_login()
    {
        $email          = $this->input->post("email");
        $companyID      = $this->input->post("companyID");
        $datenow        = date("Y-m-d");
        $email_confirm  = "";
        $password       = "";
        $UserStatus     = true;
        $status         = 0;
        $message        = "";
        $confirm_password = $this->input->post("password");
        $this->db->select("email,password,status,hak_akses,CompanyID,id_user,VoucherExpireDate");
        $this->db->where("email",$email);
        if($companyID):
            $this->db->group_start();
            $this->db->where("CompanyID", $companyID);
            $this->db->or_where("id_user", $companyID);
            $this->db->group_end();
        endif;
        $query  = $this->db->get("user");
        $row    = $query->row();
        if($row):
            $email_confirm  = $row->email;
            $password       = $row->password;
            $status         = $row->status;
            $hak_akses      = $row->hak_akses;
            if($hak_akses == "branch"):
                $ck_user = $this->UserStatus($row->CompanyID,$row->id_user);
                if(!$ck_user->status):
                    $UserStatus = $ck_user->status;
                    $message    = $ck_user->message;
                endif;
            elseif($hak_akses == "additional"):
                if($datenow>=$row->VoucherExpireDate):
                    $UserStatus = false;
                    $message    = $this->lang->line('lb_user_expired_validate');
                endif;
            endif;
        endif;
        $confirm_password = $this->hash($confirm_password);
        $data = array();
        $data['error_string']   = array();
        $data['inputerror']     = array();
        $data['status']         = TRUE;
        if($this->input->post('email') == '')
        {
            $data['inputerror'][] = 'email';
            $data['error_string'][] = 'Please fill out Email';
            $data['status'] = FALSE;
        }
        if($email_confirm == '')
        {
            $data['inputerror'][]   = 'email';
            $data['error_string'][] = 'email not registered';
            $data['status'] = FALSE;
        }

        if($this->input->post('password') == '')
        {
            $data['inputerror'][] = 'password';
            $data['error_string'][] = 'Please fill out Password';
            $data['status'] = FALSE;
        }

        if($email_confirm != "" && $password != $confirm_password){
            $data['inputerror'][]   = 'password';
            $data['error_string'][] = 'Password is wrong';
            $data['status'] = FALSE;
        }

        if(!$UserStatus):
            $data["popup"]    = TRUE;
            $data["status"]   = FALSE;
            $data["message"]  = $message;
        endif;

        // if($status == 0)
        // {
        //     $data["popup"]    = TRUE;
        //     $data["status"]   = FALSE;
        //     $data["message"]  = "sorry your account is not active";
        // }
        if($data['status'] === FALSE)
        {

            echo json_encode($data);
            exit();
        }
    }
    private function validasi_register($page = "")
    {
        $email = $this->input->post('email');
        $cek_email  = $this->db->count_all("user where email='$email' AND App = 'pipesys'");
        $cek_branch = $this->db->count_all("Branch where Email = '$email' AND Active = '1' AND App = 'pipesys'");//sementara untuk sales pro

        $data = array();
        $data['error_string']   = array();
        $data['inputerror']     = array();
        $data['status']         = TRUE;
        if($page == "member" && $this->input->post('nama_toko') == '')
        {
            $data['inputerror'][]   = 'nama_toko';
            $data['error_string'][] = 'Please fill out full name1';
            $data['status']         = FALSE;
        }
        if($page == "member" && $this->input->post('nama_perusahaan') == '')
        {
            $data['inputerror'][]   = 'nama_perusahaan';
            $data['error_string'][] = 'Please fill out full name1';
            $data['status']         = FALSE;
        }
        if($page == "agen" && $this->input->post('nama') == '')
        {
            $data['inputerror'][]   = 'nama';
            $data['error_string'][] = 'Please fill out name';
            $data['status']         = FALSE;
        }
        if($page == "agen" && $this->input->post('telepon') == '')
        {
            $data['inputerror'][]   = 'telepon';
            $data['error_string'][] = 'Please fill out Phone Number';
            $data['status']         = FALSE;
        }
        if (strpos($email, '@') == false){
            $data['inputerror'][]   = 'email';
            $data['error_string'][] = 'Email format incorrect';
            $data['status']         = FALSE;
        }
        if($this->input->post('email') == '')
        {
            $data['inputerror'][]   = 'email';
            $data['error_string'][] = 'Please fill out email';
            $data['status']         = FALSE;
        }
        if($cek_email > 0)
        {
            $data['inputerror'][]   = 'email';
            $data['error_string'][] = 'An account with this email already exists';
            $data['status']         = FALSE;
        }
        if ($cek_branch>0) {
            $query = $this->getBranch($email, "branch");
            $comanyName = "";
            foreach ($query->result() as $d) {
                $comanyName .= $d->nama.", ";
            }
            $data['inputerror'][]   = 'email';
            $data['error_string'][] = 'Email address has been taken as employee in  '.$comanyName." Please contact the company administrator to deactivate email address";
            $data['status']         = FALSE;
        }
        if(strlen($this->input->post('password')) < 6)
        {
            $data['inputerror'][] = 'password';
            $data['error_string'][] = 'Password is too short';
            $data['status'] = FALSE;
        }
        if($this->input->post('password') == '')
        {
            $data['inputerror'][] = 'password';
            $data['error_string'][] = 'Please fill out password';
            $data['status'] = FALSE;
        }
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }

    private function validasi_android(){
        $email = $this->input->post('email');
        $cek_branch = $this->db->count_all("Branch where Email = '$email' AND Active = '1'");//sementara untuk sales pro
        $cek_email  = $this->db->count_all("user where email='$email'");
        
        $res = array();
        $res['status']         = TRUE;

        if ($cek_branch>0) {
            $query = $this->getBranch($email, "branch");
            $comanyName = "";
            foreach ($query->result() as $d) {
                $comanyName .= $d->nama.", ";
            }
            $res['message'] = 'Email address has been taken as employee in '.$comanyName." Please contact the company administrator to deactivate email address";
            $res['status']  = FALSE;
        }

        if($cek_email > 0)
        {
            $res['message'] = 'An account with this email already exists';
            $data['status']         = FALSE;
        }

        if($res['status'] === FALSE)
        {
            echo json_encode($res);
            exit();
        }
    }

    #datatables
    #serial number
    function serial_number_datatables($page = "")
    {
        $this->_serial_number_datatables($page);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function _serial_number_datatables(){
        $column = array("ProductSerialID");
        $order  = array("pps.ProductSerialID,pps.SerialNo");
        $this->db->select("pps.SerialNo as serialno");
        $this->db->from("PS_Product_Serial as pps");
        $this->db->where("pps.companyid",$this->session->companyid);
        $this->db->where("pps.ProductID",$this->input->post("productid"));
        
        $i = 0;
        foreach ($column as $item) // loop column 
        {
            if($_POST['search']['value']){
                if($i===0){
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND. 
                    $this->db->like($item, $_POST['search']['value']);
                }
                else{
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($column) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $column[$i] = $item; // set column array variable to order processing
            $i++;
        }
        if(isset($_POST['order'])){
            $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($order)){
            $order = $order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    public function serial_count_filtered($page="")
    {
        $this->_serial_number_datatables($page);
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function serial_count_all($page="")
    {
        $this->db->where("pps.companyid",$this->session->companyid);
        $this->db->where("pps.ProductID",$this->input->post("productid"));
        $this->db->from("PS_Product_Serial as pps");
        return $this->db->count_all_results();
    }

    #setting parameter 
    public function setting_parameter($page = "")
    {
        $this->db->where("CompanyID",$this->session->CompanyID);
        $query = $this->db->get("SettingParameter");
        
        $a = $query->row();
        if($a):
            $data = array(
                "currency"      => $a->Currency,
                "amountdecimal" => $a->AmountDecimal,
                "qtydecimal"    => $a->QtyDecimal,
                "negativestock" => $a->NegativeStock,
                "CostMethod"    => $a->CostMethod,
                "Currency"      => $a->Currency,
                "AmountDecimal" => $a->AmountDecimal,
                "QtyDecimal"    => $a->QtyDecimal,
                "Days"          => $a->Days,
                "DataSetting"   => $a->DataSetting,
                "NegativeStock" => $a->NegativeStock,
                "AR"            => $a->AR,
                "AP"            => $a->AP,
                "AC"            => $a->AC,
                "Inventory"     => $a->Inventory,
                "Asset"         => $a->Asset,
            );
        else:
            $data = array(
                "currency"      => "IDR",
                "amountdecimal" => "0",
                "qtydecimal"    => "0",
                "days"          => null,
                "DataSetting"   => "Days",
                "CostMethod"    => "average",
                "negativestock" => "allow",
                "Currency"      => "IDR",
                "AmountDecimal" => "0",
                "QtyDecimal"    => "0",
                "NegativeStock" => "allow",
                "AR"            => "[]",
                "AP"            => "[]",
                "AC"            => "[]",
                "Inventory"     => "[]",
                "Asset"         => "[]",
            );
        endif;
        $this->session->set_userdata($data);        
    }

    #branch
    public function branch_ho(){
        $CompanyID = $this->session->CompanyID;
        $this->db->select("BranchID,Name");
        $this->db->where("CompanyID", $CompanyID);
        $this->db->where("Index", 1);
        $query = $this->db->get("Branch");
        $d = $query->row();
        if($d):
            $data = array(
                "BranchID"      => $d->BranchID,
                "BranchName"    => $d->Name,
            );
        else:
            $data = array(
                "BranchID"      => '',
                "BranchName"    => '',
            );
        endif;
        $this->session->set_userdata($data);
    }

    public function branch_stock($BranchID,$productid,$qty,$AveragePrice="")
    {   
        $CompanyID = $this->session->CompanyID;
        $data = array(
            "Qty"       => $qty,
            "User_Ch"   => $this->session->nama,
            "Date_Ch"   => date("Y-m-d H:i:s")
        );
        if($AveragePrice):
            $data['AveragePrice'] = $AveragePrice;
        endif;
        $this->db->where("ProductID",$productid);
        $this->db->where("BranchID",$BranchID);
        $this->db->where("CompanyID", $CompanyID);
        $this->db->update("PS_Product_Branch",$data);
    }

    #ar correction
    public function ar_correction($page = "", $search = ""){

        $this->db->select("
            sell.BranchID       as branchid,
            b.Name              as branchname,
            SUM(sell.Total)     as total,
            SUM(sell.Payment)   as grandtotal,
            SUM(sell.Total - sell.Payment) as sisatotal, 
        ");
        // $this->db->join("PS_Vendor as v","sell.VendorID = v.VendorID","left");
        $this->db->join("Branch as b","sell.BranchID = b.BranchID","left");
        $this->db->where("sell.CompanyID",$this->session->CompanyID);
        $this->db->group_by("sell.BranchID");
        $this->db->order_by("b.Name","ASC");
        if($page == "ar_correction"):
            $this->db->where("sell.BranchID !=",null);
        endif;
        $query = $this->db->get("PS_Sell as sell");
        return $query->result();
    }

    public function ap_correction($page = "", $search = ""){

        $this->db->select("
            purchase.BranchID       as branchid,
            b.Name                  as branchname,
            SUM(purchase.Total)     as total,
            SUM(purchase.Payment)   as grandtotal,
            SUM(purchase.Total - purchase.Payment) as sisatotal, 
        ");
        // $this->db->join("PS_Vendor as v","sell.VendorID = v.VendorID","left");
        $this->db->join("Branch as b","purchase.BranchID = b.BranchID","left");
        $this->db->where("purchase.CompanyID",$this->session->CompanyID);
        $this->db->group_by("purchase.BranchID");
        $this->db->order_by("b.Name","ASC");
        if($page == "ap_correction"):
            $this->db->where("purchase.BranchID !=",null);
        endif;
        $query = $this->db->get("PS_Purchase as purchase");
        return $query->result();
    }

    public function ar_correction_detail($page = "",$search = ""){
        $this->db->select("
            acd.BalanceNo       as balanceno,
            acd.BalanceDet      as balancedet,
            acd.TotalCorrection as total,
            acd.Date            as date,
            acd.VendorID        as vendorid,
            pv.Name             as vendorname,
        ");
        $this->db->join("PS_Vendor pv","acd.VendorID = pv.VendorID","left");
        $this->db->where("acd.CompanyID",$this->session->CompanyID);
        $this->db->where("acd.Status",0);
        if($page == "customer"):
            $this->db->where("acd.VendorID",$search);
        elseif($page == "branch"):
            $this->db->where("acd.BranchID",$search);
        endif;
        $query = $this->db->get("AC_CorrectionPR_Det as acd");
        return $query->result();
    }

    public function ar_correction_detail2($page = "",$search = ""){
        $this->db->select("
            acd.BalanceDetID    as balancedet,
            ac.Code             as balanceno,
            ac.BalanceID        as balanceid,
            acd.TotalCorrection as total,
            acd.Payment         as payment,
            ac.Date             as date,
            acd.VendorID        as vendorid,
            ac.BalanceType      as type,
            pv.Name             as vendorname,
        ");
        $this->db->join("PS_Vendor pv","acd.VendorID = pv.VendorID","left");
        $this->db->join("AC_BalancePayable as ac", "ac.BalanceID = acd.BalanceID", "left");
        $this->db->where("acd.CompanyID",$this->session->CompanyID);
        $this->db->where("ac.Active", 1);
        $this->db->where("acd.PaymentStatus", 0);
        if($page == "customer"):
            $this->db->where("acd.VendorID",$search);
        elseif($page == "branch"):
            $this->db->where("acd.BranchID",$search);
        endif;
        $query = $this->db->get("AC_BalancePayable_Det as acd");
        return $query->result();
    }

    public function ar_correction_invoice($page=""){
        $method          = $this->input->post('method');
        $modul           = $this->input->post("modul");
        $Type            = $this->input->post('Type');
        $VendorID        = $this->input->post('VendorID');
        $temp_balancedet = $this->input->post('temp_balancedetid');
        $CompanyID       = $this->session->CompanyID;
        $this->db->select("
            balance.Code,
            '' as InvoiceNo,
            balancedet.BalanceID,
            balancedet.BalanceDetID,
            balancedet.VendorID,
            balance.Date,
            balancedet.TotalCorrection as Total,
            balance.Type,
            balance.OrderType,
            balancedet.Remark,
            '2'             as transactionType,
            'AR Correction' as transactionTypetxt,
            balance.BalanceType,
        ");
        $this->db->join("AC_BalancePayable as balance", "balance.BalanceID = balancedet.BalanceID", "left");
        $this->db->where("balancedet.CompanyID", $CompanyID);
        $this->db->from("AC_BalancePayable_Det as balancedet");

        // ap
        if($Type == 1):
            $this->db->where("balance.Type", 1);
        // ar
        elseif($Type == 2):
            $this->db->where("balance.Type", 2);
        endif;

        if($VendorID):
            $this->db->where("balancedet.VendorID", $VendorID);
        endif;

        if($modul == "payment_ar"):
            if($method == "update"):
                $this->db->select("(balancedet.TotalCorrection - ifnull(balancedet.Payment, 0) ) as Unpaid,");

                $temp_balancedet = explode(",", $temp_balancedet);
                $this->db->group_start();
                $this->db->where("balancedet.PaymentStatus", 0);
                $this->db->or_where_in("balancedet.BalanceDetID", $temp_balancedet);
                $this->db->group_end();
            else:
                $this->db->select("(balancedet.TotalCorrection - ifnull(balancedet.Payment, 0) ) as Unpaid,");

                $this->db->where("balancedet.PaymentStatus", 0);
            endif;

            $query = $this->db->get_compiled_select();
            return $query;
        elseif($modul == "payment_ap"):
            if($method == "update"):
                $this->db->select("(balancedet.TotalCorrection - ifnull(balancedet.Payment, 0) ) as Unpaid,");

                $temp_balancedet = explode(",", $temp_balancedet);
                $this->db->group_start();
                $this->db->where("balancedet.PaymentStatus", 0);
                $this->db->or_where_in("balancedet.BalanceDetID", $temp_balancedet);
                $this->db->group_end();
            else:
                $this->db->select("(balancedet.TotalCorrection - ifnull(balancedet.Payment, 0) ) as Unpaid,");

                $this->db->where("balancedet.PaymentStatus", 0);
            endif;

            $query = $this->db->get_compiled_select();
            return $query;
        endif;

        $query = $this->db->get();
        if($page == "detail"):
            return $query->row();
        else:
            return $query->result();
        endif;
    }
    #end ar correction
    public function currency($amount,$cr=""){
        $currency       = $this->session->currency;
        $amountdecimal  = $this->session->amountdecimal;
        $qtydecimal     = $this->session->qtydecimal;
        $amount         = number_format($amount,$amountdecimal,".",",");
        if($cr == TRUE):
            $amount = $amount;
        else:
            $amount = $currency." ".$amount;    
        endif;
        return $amount;
        
    }
    public function currency2($amount,$cr=""){
        $amount1        = $amount;
        $currency       = $this->session->currency;
        $amountdecimal  = $this->session->amountdecimal;
        $qtydecimal     = $this->session->qtydecimal;
        if($amount1<0):
            $amount         *= -1;
        endif;
        $amount         = number_format($amount,$amountdecimal,".",",");
        if($cr == TRUE):
            $amount = $amount;
        else:
            $amount = $currency." ".$amount;    
        endif;

        if($amount1<0):
            $amount = "( ".$amount." )";
        endif;

        return $amount;
        
    }

    public function qty($qty,$empty="")
    {
        if(!$qty):
            $qty = 0;
        endif;
        $qtydecimal  = $this->session->qtydecimal;
        $qty         = number_format($qty,$qtydecimal,".",",");
        
        if(!$qty && $empty == TRUE):
            $qty = '';
        endif;

        return $qty;
    }

    public function delete_img($table="",$where="",$root="")
    {
        $this->db->select("img_bin");
        $this->db->where($where);
        $query      = $this->db->get($table)->row();
        $gambar_url = base_url($root.$query->img_bin);
        if(!empty($query->img_bin)):
            $root       = explode(base_url(), $gambar_url)[1];
            $headers = @get_headers($gambar_url);
            if (preg_match("|200|", $headers[0])) {
                unlink('./' . $root);
            } 
        endif;
    }

    #20190805 MW
    #delete file url
    public function delete_file($image){
        $gambar_url = site_url($image);
        if(!empty($gambar_url)):
            $root       = explode(base_url(), $gambar_url)[1];
            $headers    = @get_headers($gambar_url);
            if (preg_match("|200|", $headers[0])) {

                if(file_exists('./' . $root)){
                    unlink('./' . $root);
                }else{

                }
            } 
        endif;
    }

    public function cek_serialnumber($productid,$serialnumber)
    {
        $this->db->select("SerialNo");
        $this->db->where("ProductID",$productid);
        $this->db->where("SerialNo !=","");
        $this->db->where("SerialNo",$serialnumber);
        $query = $this->db->get("PS_Product_Serial");
        $total = $query->num_rows();
        return $total;
    }
    public function upload_validation($file,$allow_file)
    {
        if($allow_file == "image"):
            $allowed = array("png","jpg","jpeg");
        elseif($allow_file == "image|pdf"):
            $allowed = array("png","jpg","jpeg","pdf");
        else:
            $allowed = array("png","jpg","pdf","csv");
        endif;
        $filename   = $_FILES[$file]['name'];
        $ext        = pathinfo($filename, PATHINFO_EXTENSION);
        if($filename && !in_array(strtolower($ext),$allowed) ) {
            echo json_encode(array("status" => FALSE, "message" => "The filetype you are attempting to upload is not allowed."));
        exit();
        }
    }

     public function sp_list_branch($page = "")
    {
        $this->db->select("BranchID as ID, Name as Name");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->where("Branch.Active",1);
        $this->db->order_by("Branch.BranchID","ASC");
        $query = $this->db->get("Branch");
        if($page == "count"):
            return $query->num_rows();
        else:
            return $query->result();
        endif;
    }

    public function sp_list_customer($page = "")
    {
        $app = $this->session->app;
        $this->db->select("VendorID as ID,Name as Name");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->where("App",$app);
        $this->db->where("PS_Vendor.Position",2);
        $this->db->where("PS_Vendor.Active",1);
        $this->db->order_by("PS_Vendor.Name","ASC");
        $query = $this->db->get("PS_Vendor");
        if($page == "count"):
            return $query->num_rows();
        else:
            return $query->result();
        endif;
    }

    public function sp_list_vendor($page = "")
    {
        $app = $this->session->app;
        $this->db->select("VendorID as ID,Name as Name");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->where("App",$app);
        $this->db->where("PS_Vendor.Position",1);
        $this->db->where("PS_Vendor.Active",1);
        $this->db->order_by("PS_Vendor.Name","ASC");
        $query = $this->db->get("PS_Vendor");
        if($page == "count"):
            return $query->num_rows();
        else:
            return $query->result();
        endif;
    }


    public function sp_list_sales1($page = "")
    {
        $app = $this->session->app;
        $this->db->select("SalesID as ID,Name as Name");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->where("App",$app);
        $this->db->where("PS_Sales.Status",1);
        $this->db->order_by("PS_Sales.Name","ASC");
        $query = $this->db->get("PS_Sales");
        if($page == "count"):
            return $query->num_rows();
        else:
            return $query->result();
        endif;
    }

     public function sp_list_sales($page = "")
    {
        $app = $this->session->app;
        $this->db->select("SalesID as ID,Name as Name");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->where("App",$app);
        $this->db->where("PS_Sales.Status",1);
        $this->db->order_by("PS_Sales.Name","ASC");
        $query = $this->db->get("PS_Sales");
        if($page == "count"):
            return $query->num_rows();
        else:
            return $query->result();
        endif;
    }
    public function sp_list_deliveryno($page = "")
    {
        $this->db->select("DeliveryNo as ID, DeliveryNo as DeliveryNo");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->order_by("PS_Delivery.DeliveryNo","ASC");
        $query = $this->db->get("PS_Delivery");
        if($page == "count"):
            return $query->num_rows();
        else:
            return $query->result();
        endif;
    }
    public function sp_list_sellno($page = "")
    {
        $this->db->select("SellNo as ID, SellNo as SellNo");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->order_by("PS_Sell.SellNo","ASC");
        $query = $this->db->get("PS_Sell");
        if($page == "count"):
            return $query->num_rows();
        else:
            return $query->result();
        endif;
    }

     public function sp_list_purchaseno($page = "")
    {
        $this->db->select("PurchaseNo as ID, PurchaseNo as PurchaseNo");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->order_by("PS_Purchase.PurchaseNo","ASC");
        $query = $this->db->get("PS_Purchase");
        if($page == "count"):
            return $query->num_rows();
        else:
            return $query->result();
        endif;
    }

    public function sp_list_payno($page = "")
    {
        $this->db->select("InvoiceNo as ID, InvoiceNo as InvoiceNo");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->where("PS_Invoice.BranchID",null);
        $this->db->order_by("PS_Invoice.InvoiceNo","ASC");
        $query = $this->db->get("PS_Invoice");
        if($page == "count"):
            return $query->num_rows();
        else:
            return $query->result();
        endif;
    }
    public function sp_list_product($page = "")
    {
        $this->db->select("ProductID as ID,Name as Name");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->where("ps_product.position",0);
        $this->db->where("ps_product.Active",1);
        // $this->db->where("App","salespro");
        $this->db->order_by("ps_product.Name","ASC");
        $query = $this->db->get("ps_product");
        if($page == "count"):
            return $query->num_rows();
        else:
            return $query->result();
        endif;
    }

        public function sp_list_tax($page = "")
    {
        $this->db->select("SellNo as ID,Tax as Tax");
        $this->db->where("CompanyID",$this->session->CompanyID);
        // $this->db->where("App","salespro");
        $this->db->order_by("PS_Sell.Tax","ASC");
        $query = $this->db->get("PS_Sell");
        if($page == "count"):
            return $query->num_rows();
        else:
            return $query->result();
        endif;
    }

    public function sp_total_route_transaction($page = "")
    {

        $StartDate  = $this->input->post("StartDate");
        $EndDate    = $this->input->post("EndDate");

        $StartDate  = date("Y-m-d");
        $EndDate    = date("Y-m-d");

        if($page == "count_sales"):
            $this->db->select("SP_TransactionRoute.BranchID");
            $this->db->join("SP_TransactionRoute","SP_TransactionRouteDetail.TransactionRouteID = SP_TransactionRoute.TransactionRouteID","left");
            $this->db->where("SP_TransactionRoute.CompanyID",$this->session->CompanyID);
            $this->db->where("SP_TransactionRoute.Date",date("Y-m-d"));
            $this->db->where("SP_TransactionRouteDetail.CheckIn is not null");
            $this->db->group_by("SP_TransactionRoute.BranchID");
            $query = $this->db->get("SP_TransactionRouteDetail");
        else:
            $this->db->select("TransactionRouteDetailID");
            $this->db->join("SP_TransactionRoute","SP_TransactionRouteDetail.TransactionRouteID = SP_TransactionRoute.TransactionRouteID","left");
            $this->db->where("SP_TransactionRoute.CompanyID",$this->session->CompanyID);
            if($StartDate):
                $this->db->where("SP_TransactionRoute.Date >=",$StartDate);
                $this->db->where("SP_TransactionRoute.Date <=",$EndDate);
            endif;
            if($page == "complete"):
                $this->db->where("SP_TransactionRouteDetail.Status","complete");
            endif;
            $query = $this->db->get("SP_TransactionRouteDetail");
        endif;
        return $query->num_rows();
    }    
    public function sp_sales_location(){
        $this->db->select("
            Branch.BranchID       as BranchID,
            Branch.Name           as Name,
            Branch.Email          as Email,
            Branch.Phone          as Phone,
            Branch.Lat            as Lat,
            Branch.Lng            as Lng,
            Branch.Check          as Check,
            Branch.CheckTime      as CheckTime,
            Branch.CheckAddress   as CheckAddress
        ");
        $this->db->where("App",$this->session->app);
        $this->db->where("date(CheckTime)",date("Y-m-d"));
        $this->db->group_start();
        $this->db->where("CompanyID",$this->session->companyid);
        $this->db->or_where("BranchID",$this->session->companyid);
        $this->db->group_end();
        $query = $this->db->get("Branch");
        return $query->result();
    }
    public function selisih_jam($dari,$sampai)
    {
        list($h,$m,$s)  = explode(":",$dari);
        $awal           = mktime($h,$m,$s,"1","1","1");
        list($h,$m,$s)  = explode(":",$sampai);
        $akhir          = mktime($h,$m,$s,"1","1","1");
        $selisih        = $akhir - $awal;

        $menit          = $selisih/60;
        $jam            = explode(".", $menit/60);
        $sisa_menit1    = ($menit/60) -$jam[0];
        $sisa_menit2    = $sisa_menit1 * 60;
        $jam            = $jam[0];
        $menit          = number_format($sisa_menit2,0);
        if($menit == 60):
            $jam        += 1;
            $menit      = "";
        endif;
        if($jam > 0):
            $jam        = $jam." hr";
        else:
            $jam        = "";
        endif;
        if($menit > 0):
            $menit        = $menit." min";
        else:
            $menit        = "";
        endif;
        return $jam." ".$menit;
    }
    public function selisih_waktu($awal,$akhir){
        $selish_waktu   = "";
        $tahun          = "";
        $bulan          = "";
        $hari           = "";
        $jam            = "";
        $menit          = "";
        $detik          = "";
        $awal           = new DateTime($awal);
        $akhir          = new DateTime($akhir); // Waktu sekarang
        $diff           = $awal->diff($akhir);

        $tahun = $diff->y;
        $bulan = $diff->m;
        $hari  = $diff->d;
        $jam   = $diff->h;
        $menit = $diff->i;
        $detik = $diff->s;

        if($tahun){ $selish_waktu .= $tahun." tahun ";}
        if($bulan){ $selish_waktu .= $bulan." bulan ";}
        if($hari){ $selish_waktu .= $hari." hari ";}
        if($jam){ $selish_waktu .= $jam." jam ";}
        if($menit){ $selish_waktu .= $menit." menit ";}
        if($detik){ $selish_waktu .= $detik." detik";}
        if(empty($selish_waktu)):
            $selish_waktu = "0 menit";
        endif;

        return $selish_waktu;
    }
    public function selisih_hari($date1,$date2)
    {
        $datetime1  = new DateTime($date1);
        $datetime2  = new DateTime($date2);
        $difference = $datetime1->diff($datetime2);
        return $difference->days;
    }
    public function convertSelisih($selisih){
        $menit          = $selisih/60;
        $jam            = explode(".", $menit/60);
        $sisa_menit1    = ($menit/60) -$jam[0];
        $sisa_menit2    = $sisa_menit1 * 60;
        $jam            = $jam[0];
        $menit          = number_format($sisa_menit2,0);
        if($menit == 60):
            $jam        += 1;
            $menit      = "";
        endif;
        if($jam > 0):
            $jam        = $jam." Jam";
        else:
            $jam        = "";
        endif;
        if($menit > 0):
            $menit        = $menit." Menit";
        else:
            $menit        = "";
        endif;
        if($jam == 0 && $menit == 0):
            $menit = "0 Menit";
        endif;

        return $jam." ".$menit;
    }
    public function voucher_package($App,$Type,$Module)
    {
        $price = 0.00;

        if($Type < 1):
            $Type = 1;
        elseif($Type > 12):
            $Type = 12;
        endif;

        if($App == "all"):
            $App = "all";
        else:
            $App = "oneapp";
        endif;

        $this->db->where("Module", $Module);
        $this->db->where("App",$App);
        $this->db->where("Type",$Type);
        $query  = $this->db->get("VoucherPackage");
        $a      = $query->row();
        return $a;
    }

    #20180427 MW
    #broadcast transaction today
    public function broadcastToday(){
        $this->db->select("
            BranchID,
            ");
        $this->db->where("Date", date("Y-m-d"));
        $this->db->where("Active", 1);
        $this->db->where("CompanyID", 1);
        $this->db->group_by("BranchID");
        $query = $this->db->get("SP_TransactionRoute");

        return $query;
    }

    //2018-05-14 MW
    // get branch selain comany sendiri
    public function getBranch($Email, $CompanyID = "none"){
        $this->db->select("user.nama");
        $this->db->where("Branch.Email", $Email);
        if($CompanyID == "none"):
            $this->db->where("Branch.App", $this->session->app);
            $this->db->where("Branch.CompanyID !=", $this->session->CompanyID);
        else:
            $this->db->where("Branch.App", "salespro");
        endif;
        $this->db->join("user", "Branch.CompanyID = user.id_user");
        $query = $this->db->get("Branch");

        return $query;
    }

    //2018-05-17 MW
    // get branch selain company sendiri
    public function getTitleApp(){
        $domain = site_url();
        if($domain == "http://qa.peopleshape.rcelectronic.co.id" || $domain == "https://peopleshape.rcelectronic.co.id"):
            $title = "People Shape Sales";
        else:
            $title = "PipeSys";
        endif;


        return $title;
    }

    //2018-05-21 MW
    //ParentID jadikan sebagai session
    public function countParentID(){
        $CompanyID = $this->session->CompanyID;
        $count = $this->db->count_all("user where ParentID = '$CompanyID'");
        $data = array(
            "ParentID" => $count,
            );
        $this->session->set_userdata($data);
    }

    //2018-05-21 MW
    //Company Parent List
    public function sp_list_company(){
        $this->db->select("id_user,nama");
        $this->db->where("ParentID", $this->session->CompanyID);
        $this->db->where("user.status",1);
        $this->db->or_where("id_user", $this->session->CompanyID);
        $query = $this->db->get("user");
        return $query->result();
    }

    private function getParentID(){
        $this->db->select("id_user");
        $this->db->where("ParentID", $this->session->CompanyID);
        $query = $this->db->get("user");
        $data =  array($this->session->CompanyID);
        foreach ($query->result() as $d) {
            array_push($data, $d->id_user);
        }
        return $data;
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

    public function sp_top_sales(){
        $StartDate  = $this->input->post("StartDate");
        $EndDate    = $this->input->post("EndDate");

        $this->db->select("
            b.Name,count(sp_td.TransactionRouteDetailID) as Count,
            ");
        $this->db->join("SP_TransactionRouteDetail as sp_td", "sp_t.TransactionRouteID = sp_td.TransactionRouteID", "left");
        $this->db->join("Branch as b", "sp_t.BranchID = b.BranchID", "left");
        $this->db->where("sp_t.CompanyID", $this->session->CompanyID);
        $this->db->where("sp_td.CheckIn != ", null);
        $this->db->where("sp_td.CheckOut !=", null);
        if($StartDate):
            $this->db->where("sp_t.Date >=",$StartDate);
            $this->db->where("sp_t.Date <=",$EndDate);
        endif;
        $this->db->group_by("sp_t.BranchID");
        $this->db->order_by("Count", "desc");
        $this->db->limit(5);
        $query = $this->db->get("SP_TransactionRoute as sp_t");

        return $query->result();
    }

    public function Distance($id,$filter,$date=""){
        if($filter == "TransactionRouteIDArray"):
            $TransactionRouteIDArray  = array();
            $IDA = $this->report->get_transaction_route_id($id,$date);
            foreach($IDA as $ida):
                array_push($TransactionRouteIDArray, $ida->TransactionRouteID);
            endforeach;
            $id = $TransactionRouteIDArray;

            $list_data          = $this->report->get_transaction_route_detail($filter,$id);
            $count_list         = count($list_data);
            if($count_list>1):
                $waypoin = "";
                $destinationnum = $count_list - 1;
                foreach ($list_data as $key => $a) {
                    $latlng = $a->Lat.",".$a->Lng;
                    if(empty($a->VendorID)):
                        $latlng = $a->CheckInLatlng;
                    endif;

                    if($key == 0):
                        $origin = $latlng;
                    elseif($key == $destinationnum):
                        $destination = $latlng;
                    else:
                        $waypoin .= $latlng."|";
                    endif;
                }
                $query = $this->report->TransactionRouteKm($id);
                if($query->num_rows()<count($id)):
                    $url = "https://maps.googleapis.com/maps/api/directions/json?origin=".$origin."&destination=".$destination."&waypoints=".$waypoin."&provideRouteAlternatives=false&avoid=highways&travelMode=DRIVING&key=".$this->config->item("gmap_api");
                    $distance = $this->getDistance($url, $filter);
                    $data = array("Km"=>$distance["km"], "KmValue"=>$distance["value"]);
                    $this->db->where_in("TransactionRouteID", $id);
                    $this->db->update("SP_TransactionRoute", $data);

                else:
                    $d = $query->row();
                    $distance["value"] = $d->KmValue;
                    $distance["km"]    = $d->Km;
                endif;
                return $distance;
            else:
                $distance["value"]   = 0;
                $distance["km"]      = "0 KM";
                return $distance;
            endif;
        else:
            $origin         = $id;
            $destination    = $filter;
            $waypoin        = "";
            if($destination == ""):
                $distance["value"]   = 0;
                $distance["km"]      = "0 KM";
            else:
                $url = "https://maps.googleapis.com/maps/api/directions/json?origin=".$origin."&destination=".$destination."&waypoints=".$waypoin."&provideRouteAlternatives=false&avoid=highways&travelMode=DRIVING&key=".$this->config->item("gmap_api");
                $distance = $this->getDistance($url,$filter);
            endif;
            
            return $distance;
        endif;
        
    }

    private function getDistance($url, $filter)
    {
        $json = @file_get_contents($url);
        $data=json_decode($json);
        $status = $data->status;
        if($status=="OK"){
            $distance = "";
            $value = 0;
            foreach ($data->routes[0]->legs as $key => $d) {
                $value += $d->distance->value;
            }
            $km = $value/1000;
            $res["value"]   = $value;
            if($filter == "TransactionRouteIDArray"):
                $res["km"]      = number_format($km, 1)." KM";
            else:
                $res["km"]      = $data->routes[0]->legs[0]->distance->text;
            endif;

            return $res;
        }
        else{
            $res["value"]   = 0;
            $res["km"]      = "0 KM";
            return $res;
        }
    }

    public function TempDate(){
        $maketemp = "
            CREATE TEMPORARY TABLE TempDate (
              `TempDateID` int NOT NULL AUTO_INCREMENT,
              `Date` Date,
              PRIMARY KEY(TempDateID)
            )
          "; 
        $this->db->query($maketemp);
    }
    public function TempDateInsert(){
        $StartDate  = $this->input->post("StartDate");
        $EndDate    = $this->input->post("EndDate");
        $StartDate  = "2018-05-08";
        $EndDate  = "2018-05-09";
        if($StartDate):
            $begin = new DateTime($StartDate);
            $end = new DateTime($EndDate);

            $interval   = DateInterval::createFromDateString('1 day');
            $period     = new DatePeriod($begin, $interval, $end);

            $dataArray = array();
            foreach ($period as $dt) {
                array_push($dataArray, $dt->format("Y-m-d"));
                $data = array("Date" => $dt->format("Y-m-d"));
                $this->db->insert("TempDate", $data);
            }
        $data = array("Date" => $EndDate);
        $this->db->insert("TempDate", $data);
        array_push($dataArray, $EndDate);
        
        return $dataArray;

        endif;
    }

    public function TempDateSelect(){
        $query = $this->db->get("TempDate");
        return $query->result();
    }

    public function dashboard($page=""){
        $StartDate  = $this->input->post("StartDate");
        $EndDate    = $this->input->post("EndDate");
        // $StartDate  = "2018-04-06";
        // $EndDate    = "2018-04-06";

        if($page == "sales_visiting_hour"):
            $month = date("m",strtotime($StartDate));
            $year  = date("Y",strtotime($StartDate));
            $data["sales_visiting_hour"] = array();
            $data["comparison"]          = array();
            for($d=1; $d<=31; $d++)
            {
                $time=mktime(12, 0, 0, $month, $d, $year);          
                if (date('m', $time)==$month):    
                    $date = date('Y-m-d', $time);
                    $data_visiting_hour = $this->sales_visiting_hour($date)->row();
                    $data_comparison    = $this->comparison_sales_visiting_hour($date);
                    array_push($data["sales_visiting_hour"], $data_visiting_hour);
                    array_push($data["comparison"], $data_comparison);
                endif;
            }
        else:
            $begin = new DateTime($StartDate);
            $end = new DateTime($EndDate);

            $interval   = DateInterval::createFromDateString('1 day');
            $period     = new DatePeriod($begin, $interval, $end);

            $data["total_route"] = array();
            $data["total_hour"]  = array();
            foreach ($period as $key => $dt) {
                $date = $dt->format("Y-m-d");
                $data_route = $this->total_route($date);
                $data_hour  = $this->total_hour($date);
                array_push($data["total_route"], $data_route);
                array_push($data["total_hour"], $data_hour);
            }
            $data_route = $this->total_route($EndDate);
            $data_hour  = $this->total_hour($EndDate);
            array_push($data["total_route"], $data_route);
            array_push($data["total_hour"], $data_hour);
        endif;

        return $data;
    }
    public function total_route($date){
        $this->db->select("
            IFNULL(Year(sp_t.Date), Year('$date')) as year,
            IFNULL(Month(sp_t.Date), Month('$date')) as month,
            IFNULL(Day(sp_t.Date), Day('$date'))as day,
            count(sp_td.TransactionRouteDetailID ) as total,
            IFNULL(sum(
                CASE
                  WHEN IFNULL(sp_td.CheckIn, 1) != 1 AND IFNULL(sp_td.CheckOut, 1) != 1 THEN 1
                  ELSE 0
                END
            ), 0)as complete,
            IFNULL(sum(
                CASE
                  WHEN IFNULL(sp_td.CheckIn, 1) = 1 OR IFNULL(sp_td.CheckOut, 1) = 1 THEN 1
                  ELSE 0
                END
           ), 0)as miss
        ");
        $this->db->join("SP_TransactionRouteDetail as sp_td", "sp_t.TransactionRouteID = sp_td.TransactionRouteID", "left");
        $this->db->where("sp_t.Date", $date);
        $this->db->where("sp_t.CompanyID", $this->session->CompanyID);
        $query = $this->db->get("SP_TransactionRoute as sp_t");

        return $query->row();
    }

    public function total_hour($date){
        $this->db->select("
            IFNULL(sum(TIME_TO_SEC(timediff(sp_td.CheckOut,sp_td.CheckIn))), 0) as total,
            IFNULL(Year(sp_t.Date), Year('$date')) as year,
            IFNULL(Month(sp_t.Date), Month('$date')) as month,
            IFNULL(Day(sp_t.Date), Day('$date'))as day,
        ");
        $this->db->join("SP_TransactionRouteDetail as sp_td", "sp_t.TransactionRouteID = sp_td.TransactionRouteID", "left");
        $this->db->where("sp_t.Date", $date);
        $this->db->where("sp_t.CompanyID", $this->session->CompanyID);
        $query = $this->db->get("SP_TransactionRoute as sp_t");

        return $query->row();
    }

    public function sales_visiting_hour($date, $page=""){
        $CompanyID  = $this->input->post("company");
        $BranchID   = $this->input->post("Sales");

        $CompanyID  = 1;
        $BranchID   = 31;

        if($page == "comparison"):
            $this->db->select("sp_td.CheckIn,sp_td.CheckOut");
        else:
            $this->db->select("
                IFNULL(sum(TIME_TO_SEC(timediff(sp_td.CheckOut,sp_td.CheckIn))), 0) as total,
                IFNULL(Year(sp_t.Date), Year('$date')) as year,
                IFNULL(Month(sp_t.Date), Month('$date')) as month,
                IFNULL(Day(sp_t.Date), Day('$date'))as day,
            ");
        endif;

        $this->db->join("SP_TransactionRouteDetail as sp_td", "sp_t.TransactionRouteID = sp_td.TransactionRouteID", "left");
        $this->db->where("sp_t.Date", $date);
        $this->db->where("sp_td.CheckIn !=", null);
        $this->db->where("sp_td.CheckOut !=", null);
        $this->db->order_by("sp_td.CheckIn");
        if($CompanyID):
            $this->db->where("sp_t.CompanyID", $CompanyID);
        else:
            $this->db->where("sp_t.CompanyID", $this->session->CompanyID);
        endif;
        $this->db->where("sp_t.BranchID", $BranchID);
        $query = $this->db->get("SP_TransactionRoute as sp_t");

        return $query;
    }

    public function comparison_sales_visiting_hour($date){
        $query = $this->sales_visiting_hour($date, "comparison");
        if($query->num_rows()>0):
            $count = count($query->result())-1;
            $first = $query->row();
            $last  = $query->row($count);
            $comparison = strtotime($last->CheckOut) - strtotime($first->CheckIn);
        else:
            $comparison = 0;
        endif;
        return $comparison;
    }

    public function button_action($method,$id="",$tambahan=""){
        $edit       = $this->lang->line('btn_edit');
        $delete     = $this->lang->line('btn_delete');
        $undelete   = $this->lang->line('btn_undelete');
        $active     = $this->lang->line('btn_active');
        $nonactive  = $this->lang->line('btn_nonactive');
        $batal      = $this->lang->line('btn_cancel');
        $view       = $this->lang->line('btn_view');
        $detail     = $this->lang->line('btn_detail');
        $btn_attachment  = '';
        $btn_next        = '';
        $btn_cancel      = '';
        $btn_delete      = '';
        $btn_edit        = '';
        $btn_view        = '';
        $btn_view_serial = '';
        $btn_price       = '';
        $btn_store       = '';

        $btn = "";
        if($method == "edit"):
            $btn = '<a href="javascript:;" type="button" class="btn btn-outline btn-default" title="'.$edit.'" onclick="edit('."'".$id."'".')">'.$edit.'</a>';
        elseif($method == "edit2"):
            $btn = '<a href="javascript:;" type="button" title="Edit Data" onclick="edit('."'".$id."'".')">'.$edit.'</a>';
        elseif($method == "view"):
            $btn = '<a href="javascript:;" type="button" class="btn btn-outline btn-default" title="'.$view.'" onclick="view_print('."'".$id."'".')">'.$view.'</a>';
        elseif($method == "view2"):
            $btn = '<a href="javascript:;" type="button" title="'.$detail.'" onclick="view('."'".$id."'".')">'.$detail.'</a>';
        elseif($method == "view_serial"):
            $btn = '<a href="javascript:;" type="button" title="'.$this->lang->line('btn_view_serial').'" onclick="view_serial('."'".$id."'".')">'.$this->lang->line('btn_view_serial').'</a>';
        elseif($method == "product_branch"):
            $btn = '<a href="javascript:;" type="button" title="'.$this->lang->line('lb_product_store').'" onclick="product_branch('."'".$id."'".')">'.$this->lang->line('lb_product_store').'</a>';
        elseif($method == "delete"):
            $btn = '<a href="javascript:;" type="button" class="btn btn-outline btn-default" title="'.$delete.'" onclick="hapus('."'".$id."'".')">'.$delete.'</a>';
        elseif($method == "delete2"):
            $btn = '<a href="javascript:;" type="button" class="btn btn-outline btn-default" title="'.$delete.'" onclick="active('."'".$id."','active'".')">'.$delete.'</a>';
        elseif($method == "delete3"):
            $btn = '<a href="javascript:;" type="button" title="'.$delete.'" onclick="delete_data('."'".$id."','".$tambahan."'".')">'.$delete.'</a>';
        elseif($method == "delete4"):
            $btn = '<a href="javascript:;" type="button" title="'.$delete.'" onclick="hapus('."'".$id."'".')">'.$delete.'</a>';
        elseif($method == "customer_price"):
            $btn = '<a href="javascript:;" type="button" title="'.$this->lang->line('btn_customer_price').'" onclick="modal_vendor_price('."'customer','".$id."'".')">'.$this->lang->line('btn_customer_price').'</a>';
        elseif($method == "active"):
            $btn = '<a href="javascript:;" type="button" class="btn btn-outline btn-default" title="'.$active.'" onclick="active('."'".$id."','active'".')">'.$active.'</a>';
        elseif($method == "active2"):
            $btn = '<a href="javascript:;" type="button" title="'.$this->lang->line('btn_undelete').'" onclick="active('."'".$id."','active'".')">'.$this->lang->line('btn_undelete').'</a>';
        elseif($method == "nonactive"):
            $btn = '<a href="javascript:;" type="button" class="btn btn-outline btn-default" title="'.$nonactive.'" onclick="active('."'".$id."','nonactive'".')">'.$nonactive.'</a>';
        elseif($method == "nonactive2"):
            $btn = '<a href="javascript:;" type="button" title="'.$nonactive.'" onclick="active('."'".$id."','nonactive'".')">'.$nonactive.'</a>';
        elseif($method == "undelete"):
            $btn = '<a href="javascript:;" type="button" class="btn btn-outline btn-default" title="'.$undelete.'" onclick="active('."'".$id."','nonactive'".')">'.$undelete.'</a>';
        elseif($method == "undelete2"):
            $btn = '<a href="javascript:;" type="button" title="'.$undelete.'" onclick="active('."'".$id."','nonactive'".')">'.$undelete.'</a>';
        elseif($method == "cancel"):
            $btn = '<a href="javascript:;" type="button" title="'.$this->lang->line('btn_cancel').'" onclick="cancel('."'".$id."'".')">'.$this->lang->line('btn_cancel').'</a>';
        elseif($method == "code"):
            $btn = '<a href="javascript:;" onclick="view('."'".$id."'".')">'.$tambahan."</a>";
        elseif($method == "delivery"):
            $btn = '<a href="javascript:;" type="button" title="'.$this->lang->line('lb_delivery').'" onclick="delivery_order('."'".$id."','".$tambahan."'".')">'.$this->lang->line('lb_delivery').'</a>';
        elseif($method == "invoice_selling"):
            $btn = '<a href="javascript:;" type="button" title="'.$this->lang->line('lb_invoicear').'" onclick="redirect_post('."'invoice-receivable','".$id."','".$tambahan."'".')">'.$this->lang->line('lb_invoicear').'</a>';
        elseif($method == "payment_ar"):
            $btn = '<a href="javascript:;" type="button" title="'.$this->lang->line('lb_paymentar').'" onclick="redirect_post('."'payment-receivable','".$id."','".$tambahan."'".')">'.$this->lang->line('lb_paymentar').'</a>';
        elseif($method == "payment_ap"):
            $btn = '<a href="javascript:;" type="button" title="'.$this->lang->line('lb_paymentap').'" onclick="redirect_post('."'payment-payable','".$id."','".$tambahan."'".')">'.$this->lang->line('lb_paymentap').'</a>';
        elseif($method == "receipt"):
            $btn = '<a href="javascript:;" type="button" title="'.$this->lang->line('lb_goodrc').'" onclick="redirect_post('."'penerimaan','".$id."','".$tambahan."'".')">'.$this->lang->line('lb_goodrc').'</a>';
        elseif($method == "invoice_purchase"):
            $btn = '<a href="javascript:;" type="button" title="'.$this->lang->line('lb_invoiceap').'" onclick="redirect_post('."'invoice-payable','".$id."','".$tambahan."'".')">'.$this->lang->line('lb_invoiceap').'</a>';
        elseif($method == "edit_attach"):
            $btn = '<a href="javascript:;" type="button" title="'.$this->lang->line('btn_edit').'" onclick="edit_attach('."'".$id."'".')">'.$this->lang->line('btn_edit').'</a>';
        elseif($method == "general_onclick"):
            $btn = '<a href="javascript:;" type="button" title="'.$view.'" onclick="'.$id.'">'.$tambahan.'</a>';
        elseif($method == "print"):
            // $btn = 
            //     '<div class="btn-group open vaction">
            //         <button type="button" class="btn btn-primary btn-white dropdown-toggle  waves-effect" data-toggle="dropdown" aria-expanded="true">
            //             Print <span class="caret"></span> </button>
            //         <ul class="dropdown-menu">
            //             <li><a href="#" id="link_print" target="_blank">Print View</a></li>
            //             <li><a href="#" id="link_pdf_1" target="_blank">PDF Portrait</a></li>
            //             <li><a href="#" id="link_pdf_2" target="_blank">PDF Landscape</a></li>
            //         </ul>
            //     </div>';
            $btn = '<a href="#" class="btn btn-primary open vaction" id="link_pdf_1" target="_blank">Print</a>';
        elseif($method == "action"):
            if(!$id):$id = array();endif;
            if(in_array("attachment", $id)):
                $btn_attachment = '<li class="btn-attachment"><a href="#" title="Attachment" id="link_attachment" target="_blank">Attachment</a></li>';
            endif;
            if(in_array("next", $id)): $btn_next = '<li class="btn-next"></li>'; endif;
            if(in_array("cancel", $id)): $btn_cancel = '<li class="btn-cancel"></li>'; endif;
            if(in_array("delete", $id)): $btn_delete = '<li class="btn-delete"></li>'; endif;
            if(in_array("edit", $id)): $btn_edit = '<li class="btn-edit-data"></li>'; endif;
            if(in_array("view", $id)): $btn_view = '<li class="btn-view-data"></li>'; endif;
            if(in_array("view", $id)): $btn_view_serial = '<li class="btn-view_serial-data"></li>'; endif;
            if(in_array("customer_price", $id)): $btn_price = '<li class="btn-customer_price-data"></li>'; endif;
            if(in_array("product_branch", $id)): $btn_store = '<li class="btn-product_branch-data"></li>'; endif;
            $btn = 
                '<div class="btn-group open vaction vaction2 check-dropdown">
                    <button type="button" class="btn btn-primary btn-white dropdown-toggle  waves-effect" data-toggle="dropdown" aria-expanded="true">
                        '.$this->lang->line('btn_action').' <span class="caret"></span> </button>
                    <ul class="dropdown-menu">
                        '.$btn_view.$btn_view_serial.$btn_store.$btn_edit.$btn_cancel.$btn_delete.$btn_price.$btn_attachment.$btn_next.'
                    </ul>
                </div>';
        endif;

        return $btn;
    }

    public function button_action_dropdown($method,$id,$tambahan=""){
        $btn = '';
        if($method == "view"):
            $btn = '<li><a href="javascript:;" type="button" title="View" onclick="view('."'".$id."'".')"><i class="icon fa-search" aria-hidden="true"></i>View</a></li>';
        elseif($method == "edit"):
            $btn = '<li><a href="javascript:;" type="button" title="Edit" onclick="edit('."'".$id."'".')"><i class="icon fa-pencil" aria-hidden="true"></i>Edit</a></li>';
        elseif($method == "delete"):
            $btn = '<li><a href="javascript:;" type="button" title="Delete" onclick="delete_data('."'".$id."'".')"><i class="icon fa-trash" aria-hidden="true"></i>Delete</a></li>';
        elseif($method == "cancel"):
            $btn = '<li><a href="javascript:;" type="button" title="Cancel" onclick="cancel('."'".$id."'".')"><i class="icon fa-times" aria-hidden="true"></i>Cancel</a></li>';
        elseif($method == "delivery"):
            $btn = '<li><a href="javascript:;" type="button" title="Delivery" onclick="delivery_order('."'".$id."','".$tambahan."'".')"><i class="icon fa-truck" aria-hidden="true"></i>Delivery</a></li>';
        elseif($method == "print"):
            $btn = '<li><a href="javascript:;" type="button" title="Print" onclick="view('."'".$id."','print'".')"><i class="icon fa-print" aria-hidden="true"></i>Print</a></li>';
        elseif($method == "serial"):
            $btn = '<li><a href="javascript:;" type="button" title="Serial" onclick="view('."'".$id."','serial'".')"><i class="icon fa-barcode" aria-hidden="true"></i>Serial</a></li>';
        endif;
        return $btn;
    }

    public function button_icon($method,$id,$tambahan=""){
        $btn = '';
        if($method == "view"):
            $btn = '<a href="javascript:;" type="button" class="btn btn-success" title="View" onclick="view('."'".$id."'".')"><i class="icon fa-search" aria-hidden="true"></i></a>';
        elseif($method == "edit"):
            $btn = '<a href="javascript:;" type="button" class="btn btn-success" title="Edit" onclick="edit('."'".$id."'".')"><i class="icon fa-pencil" aria-hidden="true"></i></a>';
        elseif($method == "cancel"):
            $btn = '<a href="javascript:;" type="button" class="btn btn-danger" title="Cancel" onclick="cancel('."'".$id."'".')"><i class="icon fa-times" aria-hidden="true"></i></a>';
        endif;

        return $btn;
    }

    public function button_attachment($ID,$link,$type=""){
        $icon = '<i class="icon fa-paperclip" aria-hidden="true"></i>';
        $attachment = '<a target="_blank" title="Attachment" class="btn btn-success btn-red" href="'.site_url($link).'">'.$icon.'</i></a>';

        return $attachment;
    }

    public function button_attach_dropdown($ID,$link,$type=""){
        $icon = '<i class="icon fa-paperclip" aria-hidden="true"></i>';
        $attachment = '<li><a target="_blank" title="Attachment" href="'.site_url($link).'">'.$icon.'</i>Attachment</a></li>';

        return $attachment;
    }

    public function button_serial($ID,$link,$type=""){
        $icon = '<i class="icon fa-barcode" aria-hidden="true"></i>';
        $attachment = '<a target="_blank" title="Serial Naumber" class="btn btn-success btn-red" href="'.site_url($link).'">'.$icon.'</i></a>';

        return $attachment;
    }

    public function button_serial_dropdown($ID,$link,$type=""){
        $icon = '<i class="icon fa-barcode" aria-hidden="true"></i>';
        $attachment = '<li><a target="_blank" title="Attachment" href="'.site_url($link).'">'.$icon.'</i>Serial Naumber</a></li>';

        return $attachment;
    }

    public function general_button($page="",$text="",$text2=""){
        $btn = '';
        if($page == "add"):
            $btn = '<a href="javascript:;" title="'.$text.'" type="button" class="btn btn-blue" onclick="tambah()" >'.$text.'</a>';
        elseif($page == "search"):
            $search = $this->lang->line('lb_search');
            $btn = '<a href="javascript:;" title="'.$search.'" type="button" class="btn btn-blue" onclick="filter_table()" ><i class="icon wb-search"></i>'.$search.'</a>';
        elseif($page == "search_report"):
            $search = 'Search';
            $btn = '<a href="javascript:;" title="'.$search.'" type="button" class="btn btn-blue" onclick="search_table()" ><i class="icon wb-search"></i>'.$search.'</a>';
        elseif($page == "reload"):
            $reload = $this->lang->line('lb_reload');
            $btn = '<a href="javascript:;" title="'.$reload.'" type="button" class="btn btn-blue content-hide" onclick="reload_table()" ><i class="icon wb-reload"></i>'.$reload.'</a>';
        elseif($page == "import"):
            $import = 'Import';
            $btn = '<a href="javascript:;" title="'.$import.'" type="button" class="btn btn-info" onclick="modal_import()" ><i class="icon wb-download"></i>'.$import.'</a>';
        elseif($page == "export"):
            $export = 'Export';
            $btn = '<a href="'.$text.'" target="_blank" title="'.$export.'" type="button" class="btn btn-info"><i class="icon wb-download"></i>'.$export.'</a>';
        elseif($page == "close"):
            $close = $this->lang->line('lb_close');
            $btn = '<a href="javascript:;" title="'.$close.'" type="button" class="btn btn-danger btn-default margin-0 kotak btn-close" data-dismiss="modal" >'.$close.'</a>';
        elseif($page == "general"):
            $btn = '<a href="'.$text.'" target="_blank" title="'.$text2.'" type="button" class="btn btn-info">'.$text2.'</a>';
        elseif($page == "general_blue"):
            $btn = '<a href="'.$text.'" target="_blank" title="'.$text2.'" type="button" class="btn btn-blue">'.$text2.'</a>';
        elseif($page == "general_blue2"):
            $btn = '<a href="javascript:;" onclick="'.$text.'" title="'.$text2.'" type="button" class="btn btn-blue">'.$text2.'</a>';
        elseif($page == "general_onclick_xs"):
            $btn = '<a href="javascript:;" title="'.$text2.'" onclick="'.$text.'" class="btn btn-blue btn-xs">'.$text2.'</a>';
        elseif($page == "general_save"):
            $save = $this->lang->line('btn_save');
            if(!$text):
                $text = "save()";
            endif;
            $btn = '<a href="javascript:;" title="'.$save.'" onclick="'.$text.'" id="btnSave" class="btn btn-primary save btnSave">'.$save.'</a>';
        endif;
        return $btn;
    }

    public function get_last_companyID(){
        $CompanyID = $this->session->CompanyID;
        if(strlen($CompanyID)>2):
            $CompanyID = substr($CompanyID, -2);
        endif;

        return $CompanyID;
    }

    // 201181224 MW

    #product
    public function product_detail($CompanyID="",$ProductID="",$page="",$BranchID=""){
        $this->db->select("
            ProductID,
            IFNULL(Qty,0) as Qty,
            ");
        if($CompanyID != ""):
            $this->db->where("CompanyID", $CompanyID);
        endif;
        if($page == "array"):
            if(count($ProductID)>0):
                $this->db->where_in("ProductID", $ProductID);
            else:
                $this->db->where("ProductID", 0);
            endif;
        else:
            if($ProductID != ""):
                $this->db->where("ProductID", $ProductID);
            endif;
        endif;
        if($BranchID):
            $this->db->where("BranchID", $BranchID);
            $query = $this->db->get("PS_Product_Branch");
        else:
            $query = $this->db->get("ps_product");
        endif;

        if($page == "detail"):
            return $query->row();
        else:
            return $query->result();
        endif;
    }

    public function checkDuitInput($input){
        $data = 0;
        if($input != ''):
            $data = str_replace(',', '', $input);
        endif;

        return $data;
    }

    public function checkNullInput($input){
        $data = $input;
        if($input == ''):
            $data = null;
        endif;

        return $data;
    }

    public function checkInputData($input){
        $data = '';
        if($input):
            $data = $input;
        endif;

        return $data;
    }


    public function label_active($status,$page="",$id="1"){
        if($status == 1):
            $label  = '<hijau class="status'.$id.'" data-status="1">Active</hijau>';
            if($page == "cetak"):
                $label  = 'Active';
            endif;
        else:
            $label  = '<merah class="status'.$id.'" data-status="0">Cancel</merah>';
            if($page == "cetak"):
                $label  = 'Cancel';
            endif;
        endif;

        return $label;
    }

    public function label_active2($status,$page="",$id="1"){
        if($status == 1):
            $label  = '<hijau class="status'.$id.'" data-status="1">'.$this->lang->line('btn_active').'</hijau>';
            if($page == "cetak"):
                $label  = $this->lang->line('btn_active');
            endif;
        else:
            $label  = '<merah class="status'.$id.'" data-status="0">'.$this->lang->line('btn_nonactive').'</merah>';
            if($page == "cetak"):
                $label  = $this->lang->line('btn_nonactive');
            endif;
        endif;

        return $label;
    }

    public function label_product($type,$page="",$id=""){
        $label = '';
        if($type == 1):
            $label  = '<hijau class="product_type'.$id.'" data-status="1">Service</hijau>';
            if($page == "cetak"):
                $label  = 'Service';
            endif;
        else:
            //$label  = '<hijau class="product_type'.$id.'" data-status="1">Product Item</hijau>';
            if($page == "cetak"):
                //$label  = 'Product Item';
            endif;
        endif;

        return $label;
    }

    public function label_tax($tax,$page="",$id="1"){
        if($tax == 1):
            $label  = '10%';
            if($page == "cetak"):
                $label  = '10%';
            endif;
        else:
            $label  = '0%';
            if($page == "cetak"):
                $label  = '0%';
            endif;
        endif;

        return $label;
    }

    public function label_attachment(){
        $Type = $this->input->get("type");
        $Type = strtolower($Type);
        if($Type == "selling"):
            $label = "Selling";
            $format= "File format (pdf, doc, docx, excel, image .JPG .JPEG .PNG) MAX size file 1,8 MB";
        else:
            $label = "Galeri";
            $format= "Pilih File foto terbaik untuk produk ini. (format .JPG .JPEG .PNG max 1,8 MB), good image resolution 1200px x 600px";
        endif;
        $data["label"]  = $label;
        $data["Type"]   = $Type;
        $data["format"] = $format;
        return $data;
    }

    public function label_payment_type($type){
        $label = "";
        if($type == 0):
            $label = "Cash";
        elseif($type == 1):
            $label = "Giro";
        elseif($type == 2):
            $label = "Transfer";
        endif;

        return $label;
    }

    public function label_return_type($type,$page="",$id=""){
        $label = "";
        if($type == 1):
            $label  = '<hijau class="status'.$id.'" data-status="1">Purchase</hijau>';
            if($page == "cetak"):
                $label  = 'Purchase';
            endif;
        elseif($type == 2):
            $label  = '<biru class="status'.$id.'" data-status="2">Receive</biru>';
            if($page == "cetak"):
                $label  = 'Receive';
            endif;
        elseif($type == 3):
            $label  = '<hijau class="status'.$id.'" data-status="3">Sell</hijau>';
            if($page == "cetak"):
                $label  = 'Sell';
            endif;
        elseif($type == 4):
            $label  = '<biru class="status'.$id.'" data-status="4">Delivery</biru>';
            if($page == "cetak"):
                $label  = 'Delivery';
            endif;
        endif;

        return $label;
    }

    public function label_invoice_type($OrderType,$page,$id="",$type=""){
        if($type == 1):
            if($OrderType == 1):
                $label  = '<hijau class="status'.$id.'" data-status="1">Receipt</hijau>';
                if($page == "cetak"):
                    $label  = 'Receipt';
                endif;
            elseif($OrderType == 2):
                $label  = '<biru class="status'.$id.'" data-status="2">Purchase Order</biru>';
                if($page == "cetak"):
                    $label  = 'Purchase Order';
                endif;
            endif;
        else:
            if($OrderType == 1):
                $label  = '<hijau class="status'.$id.'" data-status="1">Delivery</hijau>';
                if($page == "cetak"):
                    $label  = 'Delivery';
                endif;
            elseif($OrderType == 2):
                $label  = '<biru class="status'.$id.'" data-status="2">Sales Order</biru>';
                if($page == "cetak"):
                    $label  = 'Sales Order';
                endif;
            endif;
        endif;

        return $label;
    }

    public function label_balance_type($type,$page="",$id=""){
        $label ="";
        if($type == 1):
            $label  = '<hijau class="status'.$id.'" data-status="1">Debit</hijau>';
            if($page == "cetak"):
                $label  = '(Debit)';
            endif;
        elseif($type == 2):
            $label  = '<biru class="status'.$id.'" data-status="2">Credit</biru>';
            if($page == "cetak"):
                $label  = '(Credit)';
            endif;
        endif;

        return $label;
    }

    public function kasbank_type($type){
        // 0 = saldo awal, 1 = cash , 2 = bank, 3=Jurnal Biasa,4=Penerimaan,5=Jual Retail, 6=Jual Grosir,7=Retur Beli,8=Retur Jual,9=Pembayaran Hut,10=Pembayaran Piutang, 11=HPP,12=Kontrabon,13=Invoice,14=Correction
        $label = "";
        if($type == 0):
            $label = "Saldo Awal";
        elseif($type == 1):
            $label = $this->lang->line('lb_cash');
        elseif($type == 2):
            $label = $this->lang->line('lb_bank');
        elseif($type == 3):
            $label = $this->lang->line('lb_journal');
        elseif($type == 4):
            $label = $this->lang->line('lb_goodrc');
        elseif($type == 5):
            $label = $this->lang->line('lb_sales_retail');
        elseif($type == 6):
            $label = $this->lang->line('lb_selling');
        elseif($type == 7):
            $label = $this->lang->line('lb_returnap');
        elseif($type == 8):
            $label = $this->lang->line('lb_returnar');
        elseif($type == 9):
            $label = $this->lang->line('lb_paymentap');
        elseif($type == 10):
            $label = $this->lang->line('lb_paymentar');
        elseif($type == 11):
            $label = $this->lang->line('lb_cost_sold');
        elseif($type == 12):
            $label = $this->lang->line('lb_invoiceap');
        elseif($type == 13):
            $label = $this->lang->line('lb_invoicear');
        elseif($type == 14):
            $label = $this->lang->line('lb_correctionar');
        elseif($type == 15):
            $label = $this->lang->line('lb_correctionstore');
        elseif($type == 16):
            $label = $this->lang->line('lb_correctionap');
        elseif($type == 17):
            $label = $this->lang->line('lb_stock_opname');
        elseif($type == 18):
            $label = $this->lang->line('lb_stock_correction');
        endif;

        return $label;
    }

    public function label_template($type){
        $label  = "";
        if($type == "purchase"): $label = $this->lang->line('lb_purchase');
        elseif($type == "penerimaan"): $label = $this->lang->line('lb_goodrc');
        elseif($type == "retur"): $label = $this->lang->line('lb_returnap');
        elseif($type == "invoice_ap"): $label = $this->lang->line('lb_invoiceap');
        elseif($type == "ap_correction"): $label = $this->lang->line('lb_correctionap');
        elseif($type == "payment_ap"): $label = $this->lang->line('lb_paymentap');
        elseif($type == "selling"): $label = $this->lang->line('lb_selling');
        elseif($type == "delivery"): $label = $this->lang->line('lb_delivery');
        elseif($type == "return_sales"): $label = $this->lang->line('lb_returnar');
        elseif($type == "invoice_ar"): $label = $this->lang->line('lb_invoicear');
        elseif($type == "ar_correction"): $label = $this->lang->line('lb_correctionar');
        elseif($type == "payment_ar"): $label = $this->lang->line('lb_paymentar');
        endif;

        return $label;
    }

    public function label_modul($type){
        $label = "";
        if($type == "ap"):
            $label = "Account Payable";
        elseif($type == "ar"):
            $label = "Account Receivable";
        elseif($type == "ac"):
            $label = "Accounting";
        elseif($type == "inventory"):
            $label = "Inventory";
        endif;

        return $label;
    }

    public function label_modul2($type){
        $label = "";
        if($type == "so"): $label = $this->lang->line('lb_selling1');
        elseif($type == "delivery"): $label = $this->lang->line('lb_delivery1');
        elseif($type == "return_ar"): $label = $this->lang->line('lb_return');
        elseif($type == "invoice_ar"): $label = $this->lang->line('lb_invoice');
        elseif($type == "correction_ar"): $label = $this->lang->line('lb_correction');
        elseif($type == "payment_ar"): $label = $this->lang->line('lb_payment');
        elseif($type == "po"): $label = $this->lang->line('lb_purchase1');
        elseif($type == "receipt"): $label = $this->lang->line('lb_recipt');
        elseif($type == "return_ap"): $label = $this->lang->line('lb_return');
        elseif($type == "invoice_ap"): $label = $this->lang->line('lb_invoice');
        elseif($type == "correction_ap"): $label = $this->lang->line('lb_correction');
        elseif($type == "payment_ap"): $label = $this->lang->line('lb_payment');
        elseif($type == "cash_bank"): $label = $this->lang->line('lb_cash_bank');
        elseif($type == "jurnal"): $label = $this->lang->line('lb_journal');
        elseif($type == "mutation"): $label = $this->lang->line('lb_mutation');
        elseif($type == "stock"): $label = $this->lang->line('lb_stock');
        elseif($type == "ar"): $label = $this->lang->line('lb_module_selling');
        elseif($type == "ap"): $label = $this->lang->line('lb_module_purchase');
        elseif($type == "ac"): $label = $this->lang->line('lb_module_ac');
        elseif($type == "inventory"): $label = $this->lang->line('lb_module_inventory');
        elseif($type == "devices"): $label = $this->lang->line('lb_devices');
        elseif($type == "inventory_goodreceipt"): $label = $this->lang->line('lb_stock_receipt');
        elseif($type == "good_issue"): $label = $this->lang->line('lb_stock_issue');
        endif;

        return $label;
    }

    public function label_report_stock($type){
        $label = '';
        if($type == 1): $label = $this->lang->line('lb_stock_correction');
        elseif($type == 2 || $type == 3): $label = $this->lang->line('lb_mutation');
        elseif($type == 4): $label = $this->lang->line('lb_goodrc');
        elseif($type == 6): $label = $this->lang->line('lb_returnap');
        elseif($type == 8): $label = $this->lang->line('lb_delivery');
        elseif($type == 10 || $type == 11): $label = $this->lang->line('lb_returnar');
        elseif($type == 12): $label = $this->lang->line('lb_stock_opname');
        elseif($type == 13): $label = $this->lang->line('lb_selling');
        elseif($type == 14): $label = $this->lang->line('lb_stock_receipt');
        elseif($type == 15): $label = $this->lang->line('lb_stock_issue');
        endif;

        return $label;
    }

    public function label_reset_type($type){
        $label = '';
        if($type == 1):
            $label = $this->lang->line('lb_reset_only_trans1');
        elseif($type == 2):
            $label = $this->lang->line('lb_reset_all');
        endif;

        return $label;
    }

    public function type_file($fileName){
        $type   = substr($fileName, strpos($fileName,".")+1);
        $type   = strtolower($type);
        if($type == "png" || $type == "jpg" || $type == "jpeg"):
            $type = "image";
        endif;

        return $type;
    }

    public function icon_file($type){
        if($type == "pdf"):
            $file = 'aset/images/icon_file/pdf.png';
        elseif($type == "doc" || $type == "docx"):
            $file = 'aset/images/icon_file/doc.png';
        elseif($type == "xls" || $type == "xlsx"):
            $file = 'aset/images/icon_file/excell.png';
        else:
            $file = 'aset/images/icon_file/file.png';
        endif;

        return $file;
    }

    public function checkvalueprint($val){
        if(!$val):
            $val = '-';
        endif;
        return $val;
    }

    public function PersenttoRp($total,$persen){
      $total  = (float) $total;
      $persen = (float) $persen;
      $hasil = ($persen/100)*$total;
      return $hasil;
    }

    public function RptoPersent($Total,$Rp){
      $Total  = (float) $Total;
      $Rp     = (float) $Rp;
      $hasil  = ($Rp/$Total) * 100;

      return $hasil;
    }

    // invoice delivery
    public function invoice_delivery($vendorid=""){
        if($vendorid == ""):
            $vendorid   = $this->input->post("VendorID");
        endif;
        $method     = $this->input->post("method");
        $InvoiceNo  = $this->input->post("InvoiceNo");
        $deliveryno = $this->input->post("deliveryno");
        $returnno   = $this->input->post("returnno");

        $CompanyID  = $this->session->CompanyID;
        $table      = "PS_Delivery";
        $this->db->select("
            delivery.DeliveryNo,
            '' as ReturNo,
            delivery.Date,
            ifnull(delivery.SellNo, '') as SellNo,
            delivery.Total      as price,
            delivery.Payment    as total,
            delivery.Tax,
            delivery.PPN        as ppn,
            delivery.TotalPPN   as totalppn,
            delivery.Discount   as discount,
            delivery.DiscountPersent,
            delivery.DeliveryCost   as deliverycost,
            'delivery'              as invoiceType,
            'Delivery'              as invoiceTypetxt,
        ");
        $this->db->from("$table as delivery");
        $this->db->where("delivery.CompanyID", $CompanyID);
        $this->db->where("delivery.VendorID", $vendorid);
        $this->db->where("delivery.Status", 1);
        if($method == "update"):
            $deliveryno = explode(",", $deliveryno);
            $this->db->group_start();
            $this->db->where("delivery.InvoiceStatus", 0);
            $this->db->or_where_in("delivery.DeliveryNo", $deliveryno);
            $this->db->group_end();
        else:
            $this->db->where("delivery.InvoiceStatus", 0);
        endif;
        // $query  = $this->db->get();
        $query1 = $this->db->get_compiled_select();

        $table2 = "AP_Retur";
        $this->db->select("
            ifnull($table2.DeliveryNo, '')          as DeliveryNo,
            $table2.ReturNo                         as ReturNo,
            $table2.Date,
            ifnull($table2.SellNo, '')              as SellNo,
            ifnull(SUM(AP_Retur_Det.Qty * AP_Retur_Det.Price), 0)  as price,
            ifnull(SUM(AP_Retur_Det.Total), 0)      as total,
            ifnull(delivery.Tax,0)                  as Tax,
            '0'                                     as ppn,
            '0'                                     as totalppn,
            ifnull(SUM(AP_Retur_Det.Discount), 0)   as discount,
            '0'                                     as DiscountPersent,
            '0'                                     as deliverycost,
            'return'                                as invoiceType,
            'Return'                                as invoiceTypetxt,
        ");
        $this->db->join("PS_Delivery as delivery", "delivery.DeliveryNo = $table2.DeliveryNo and delivery.CompanyID = $table2.CompanyID", "left");
        $this->db->join("AP_Retur_Det", "AP_Retur.ReturNo = AP_Retur_Det.ReturNo and AP_Retur.CompanyID = AP_Retur_Det.CompanyID", "left");
        $this->db->from("$table2");
        $this->db->where("$table2.CompanyID", $CompanyID);
        $this->db->where("$table2.VendorID", $vendorid);
        $this->db->where("$table2.Status", 1);
        $this->db->where("$table2.Type", 2);
        $this->db->where("$table2.ReturType", 4);
        if($method == "update"):
            $returnno = explode(",", $returnno);
            $this->db->group_start();
            $this->db->where("$table2.InvoiceStatus", 0);
            $this->db->or_where_in("$table2.ReturNo", $returnno);
            $this->db->group_end();
        else:
            $this->db->where("$table2.InvoiceStatus", 0);
        endif;
        $this->db->group_by("$table2.ReturNo,delivery.Tax");
        $query2 = $this->db->get_compiled_select();

        $query = $this->db->query('SELECT * FROM ('.$query1 . ' UNION ' . $query2.') AS Z order by DeliveryNo,Date asc');
        
        return $query->result();
    }


    public function invoice_delivery_detail($DeliveryNo,$SellNo){
        $table      = "PS_Delivery_Det";
        $CompanyID  = $this->session->CompanyID;
        $this->db->select("
            deliverydet.DeliveryDet,
            selldet.SellDet,
            selldet.Discount,
            (deliverydet.Qty * selldet.Price)   as Price,
            IFNULL(sell.DeliveryCost, 0)        as DeliveryCost,
            (case
                when sell.Tax = 1 then sell.PPN
                else 0
            end) as PPN,
        ");
        $this->db->join("PS_Sell_Detail as selldet", "deliverydet.SellDet = selldet.SellDet and deliverydet.CompanyID = selldet.CompanyID", "left");
        $this->db->join("PS_Sell        as sell", "selldet.SellNo = sell.SellNo and selldet.CompanyID = sell.CompanyID", "left");
        $this->db->from("$table as deliverydet");
        $this->db->where("deliverydet.CompanyID", $CompanyID);
        $this->db->where("selldet.CompanyID", $CompanyID);
        $this->db->where("sell.CompanyID", $CompanyID);
        $this->db->where("deliverydet.DeliveryNo", $DeliveryNo);
        $this->db->where("deliverydet.SellNo", $SellNo);
        $query = $this->db->get();

        return $query->result();
    }
    // invoice delivery
    public function invoice_receive($vendorid=""){
        if($vendorid == ""):
            $vendorid   = $this->input->post("VendorID");
        endif;
        $method     = $this->input->post("method");
        $InvoiceNo  = $this->input->post("InvoiceNo");
        $receiveno  = $this->input->post("receiveno");
        $returnno   = $this->input->post("returnno");

        $CompanyID  = $this->session->CompanyID;
        $table      = "AP_GoodReceipt";
        $this->db->select("
            receive.ReceiveNo,
            ''                              as ReturNo,
            receive.Date,
            ifnull(receive.PurchaseNo, '')  as PurchaseNo,
            receive.Total                   as price,
            receive.Payment                 as total,
            receive.Tax,
            receive.PPN                     as ppn,
            receive.TotalPPN                as totalppn,
            receive.Discount                as discount,
            receive.DiscountPersent,
            receive.DeliveryCost            as deliverycost,
            'Receive'                       as invoiceType,
            'Receive'                       as invoiceTypetxt,
        ");
        $this->db->from("$table as receive");
        $this->db->where("receive.CompanyID", $CompanyID);
        $this->db->where("receive.VendorID", $vendorid);
        $this->db->where("receive.Status", 1);
        if($method == "update"):
            $receiveno = explode(",", $receiveno);
            $this->db->group_start();
            $this->db->where("receive.InvoiceStatus", 0);
            $this->db->or_where_in("receive.ReceiveNo", $receiveno);
            $this->db->group_end();
        else:
            $this->db->where("receive.InvoiceStatus", 0);
        endif;
        // $query  = $this->db->get();
        $query1 = $this->db->get_compiled_select();

        $table2 = "AP_Retur";
        $this->db->select("
            ifnull($table2.ReceiveNo, '')           as ReceiveNo,
            $table2.ReturNo                         as ReturNo,
            $table2.Date,
            ifnull($table2.PurchaseNo, '')          as PurchaseNo,
            ifnull(SUM(AP_Retur_Det.Qty * AP_Retur_Det.Price), 0)  as price,
            ifnull(SUM(AP_Retur_Det.Total), 0)      as total,
            receive.Tax                             as Tax,
            '0'                                     as ppn,
            '0'                                     as totalppn,
            ifnull(SUM(AP_Retur_Det.Discount), 0)   as discount,
            '0'                                     as DiscountPersent,
            '0'                                     as deliverycost,
            'return'                                as invoiceType,
            'Return'                                as invoiceTypetxt,
        ");
        $this->db->join("AP_GoodReceipt as receive", "receive.ReceiveNo = $table2.ReceiveNo and receive.CompanyID = $table2.CompanyID", "left");
        $this->db->join("AP_Retur_Det", "AP_Retur.ReturNo = AP_Retur_Det.ReturNo and AP_Retur.CompanyID = AP_Retur_Det.CompanyID", "left");
        $this->db->from("$table2");
        $this->db->where("$table2.CompanyID", $CompanyID);
        $this->db->where("$table2.VendorID", $vendorid);
        $this->db->where("$table2.Status", 1);
        $this->db->where("$table2.Type", 1);
        $this->db->where("$table2.ReturType", 2);
        if($method == "update"):
            $returnno = explode(",", $returnno);
            $this->db->group_start();
            $this->db->where("$table2.InvoiceStatus", 0);
            $this->db->or_where_in("$table2.ReturNo", $returnno);
            $this->db->group_end();
        else:
            $this->db->where("$table2.InvoiceStatus", 0);
        endif;
        $this->db->group_by("$table2.ReturNo,receive.Tax");
        $query2 = $this->db->get_compiled_select();

        $query  = $this->db->query('SELECT * FROM ('.$query1 . ' UNION ' . $query2.') AS Z order by ReceiveNo,Date asc');
        
        return $query->result();
    }

    public function invoice_receive_detail($ReceiveNo,$PurchaseNo){
        $table      = "AP_GoodReceipt_Det";
        $CompanyID  = $this->session->CompanyID;
        $this->db->select("
            receivedet.ReceiveDet,
            pspd.PurchaseDet,
            pspd.Discount,
            (receivedet.Qty * pspd.Price)   as Price,
            IFNULL(purchase.DeliveryCost, 0)    as DeliveryCost,
            (case
                when purchase.Tax = 1 then purchase.PPN
                else 0
            end) as PPN,
        ");
        $this->db->join("PS_Purchase_Detail as pspd", "receivedet.PurchaseDet = pspd.PurchaseDet and receivedet.CompanyID = pspd.CompanyID", "left");
        $this->db->join("PS_Purchase as purchase", "pspd.PurchaseNo = purchase.PurchaseNo and pspd.CompanyID = purchase.CompanyID", "left");
        $this->db->from("$table  as receivedet");
        $this->db->where("receivedet.CompanyID", $CompanyID);
        $this->db->where("pspd.CompanyID", $CompanyID);
        $this->db->where("purchase.CompanyID", $CompanyID);
        $this->db->where("receivedet.ReceiveNo", $ReceiveNo);
        $this->db->where("receivedet.PurchaseNo", $PurchaseNo);
        $query = $this->db->get();

        return $query->result();
    }

    public function get_invoice_delivery_detail($v){
        $list = $this->invoice_delivery_detail($v->DeliveryNo,$v->SellNo);
        $price          = 0;
        $discount       = 0;
        $ppn            = 0;
        $deliverycost   = 0;
        $total          = 0;
        foreach ($list as $k => $v) {
            $xprice         = (float) $v->Price;
            $xdiscount      = (float) $v->Discount;
            $xdiscountRp    = $this->main->PersenttoRp($xprice,$xdiscount);
            $xtotal         = $xprice-$xdiscountRp;
            $xPPN           = (float) $v->PPN;
            $xPPNRp         = $this->main->PersenttoRp($xtotal,$xPPN);

            $price          += $xprice;
            $discount       += $xdiscountRp;
            $ppn            += $xPPNRp;
            $deliverycost   += $v->DeliveryCost;
            $total          += $xtotal + $xPPNRp + $v->DeliveryCost;
        }
        $data = array(
            "price"         => $price,
            "discount"      => $discount,
            "ppn"           => $ppn,
            "deliverycost"  => $deliverycost,
            "total"         => $total,
        );

        return $data;
    }
    // end invoice delivery

    // invoice selling
    public function invoice_sell($vendorid=""){
        if($vendorid == ""):
            $vendorid   = $this->input->post("VendorID");
        endif;
        $method         = $this->input->post("method");
        $InvoiceNo      = $this->input->post("InvoiceNo");
        $temp_sellno    = $this->input->post("temp_sellno");
        $returnno       = $this->input->post('temp_returnno');
        $CompanyID      = $this->session->CompanyID;
        $table          = "PS_Sell";
        $this->db->select("
            sell.SellNo,
            sell.VendorID,
            ''              as ReturNo,
            sell.Date,
            '0'             as SubTotal,
            '0'             as Total,
            sell.Tax,
            '0'             as PPN,
            '0'             as Discount,
            ifnull(sell.DeliveryCost, 0) as DeliveryCost,
            'selling'       as invoiceType,
            'selling'       as invoiceTypetxt,
        ");
        $this->db->from("$table as sell");
        $this->db->where("sell.CompanyID", $CompanyID);
        $this->db->where("sell.VendorID", $vendorid);
        $this->db->where("sell.Status", 1);
        $this->db->group_start();
            $this->db->where("sell.DeliveryParameter", 0);
            $this->db->or_where("sell.ProductType", 1);
        $this->db->group_end();
        $this->db->where("ifnull((select sum(DeliveryQty) from PS_Sell_Detail where SellNo = sell.SellNo and CompanyID = '$CompanyID'),0) <= 0");
        if($method == "update"):
            $temp_sellno = explode(",", $temp_sellno);
            $this->db->group_start();
            $this->db->where("sell.DeliveryStatus", 0);
            $this->db->where("sell.InvoiceStatus", 0);
            $this->db->or_where_in("sell.SellNo", $temp_sellno);
            $this->db->group_end();
        else:
            $this->db->where("sell.DeliveryStatus", 0);
            $this->db->where("sell.InvoiceStatus", 0);
        endif;
        // $query = $this->db->get();
        $query1 = $this->db->get_compiled_select();

        $table2 = "AP_Retur";
        $this->db->select("
            ifnull($table2.SellNo, '')              as SellNo,
            $table2.VendorID,
            $table2.ReturNo,
            $table2.Date,
            ifnull(SUM(AP_Retur_Det.Qty * AP_Retur_Det.Price), 0)  as SubTotal,
            ifnull(SUM(AP_Retur_Det.Total), 0)      as Total,
            sell.Tax                                as Tax,
            '0'                                     as PPN,
            ifnull(SUM(AP_Retur_Det.Discount), 0)   as Discount,
            '0'                                     as DeliveryCost,
            'return'                                as invoiceType,
            'Return'                                as invoiceTypetxt,
        ");
        $this->db->join("PS_Sell as sell", "sell.SellNo = $table2.SellNo and sell.CompanyID = $table2.CompanyID", "left");
        $this->db->join("AP_Retur_Det", "AP_Retur.ReturNo = AP_Retur_Det.ReturNo and AP_Retur.CompanyID = AP_Retur_Det.CompanyID", "left");
        $this->db->from("$table2");
        $this->db->where("$table2.CompanyID", $CompanyID);
        $this->db->where("$table2.VendorID", $vendorid);
        $this->db->where("$table2.Status", 1);
        $this->db->where("$table2.Type", 2);
        $this->db->where("$table2.ReturType", 3);
        if($method == "update"):
            $returnno = explode(",", $returnno);
            $this->db->group_start();
            $this->db->where("$table2.InvoiceStatus", 0);
            $this->db->or_where_in("$table2.ReturNo", $returnno);
            $this->db->group_end();
        else:
            $this->db->where("$table2.InvoiceStatus", 0);
        endif;
        $this->db->group_by("$table2.ReturNo,sell.Tax");
        $query2 = $this->db->get_compiled_select();

        $query = $this->db->query('SELECT * FROM ('.$query1 . ' UNION ' . $query2.') AS Z order by SellNo,Date asc');

        return $query->result();
    }

    // invoice purchase
    public function invoice_purchase($vendorid=""){
        if($vendorid == ""):
            $vendorid   = $this->input->post("VendorID");
        endif;
        $method         = $this->input->post("method");
        $InvoiceNo      = $this->input->post("InvoiceNo");
        $temp_sellno    = $this->input->post("temp_purchaseno");
        $returnno       = $this->input->post('temp_returnno');
        $CompanyID      = $this->session->CompanyID;
        $table          = "PS_Purchase";
        $this->db->select("
            sell.PurchaseNo,
            sell.VendorID,
            ''              as ReturNo,
            sell.Date,
            '0'             as SubTotal,
            '0'             as Total,
            sell.Tax,
            '0'             as PPN,
            '0'             as Discount,
            ifnull(sell.DeliveryCost, 0) as DeliveryCost,
            'selling'       as invoiceType,
            'selling'       as invoiceTypetxt,
        ");
        $this->db->from("$table as sell");
        $this->db->where("sell.CompanyID", $CompanyID);
        $this->db->where("sell.VendorID", $vendorid);
        $this->db->where("sell.Status", 1);
        $this->db->where("ifnull((select sum(ReceiveQty) from PS_Purchase_Detail where PurchaseNo = sell.PurchaseNo and CompanyID = '$CompanyID'),0) <= 0");
        if($method == "update"):
            $temp_sellno = explode(",", $temp_sellno);
            $this->db->group_start();
            $this->db->where("sell.DeliveryStatus", 0);
            $this->db->where("sell.InvoiceStatus", 0);
            $this->db->or_where_in("sell.PurchaseNo", $temp_sellno);
            $this->db->group_end();
        else:
            $this->db->where("sell.DeliveryStatus", 0);
            // $this->db->where("sell.InvoiceStatus", 0);
        endif;
        // $query = $this->db->get();
        $query1 = $this->db->get_compiled_select();

        $table2 = "AP_Retur";
        $this->db->select("
            ifnull($table2.PurchaseNo, '')              as SellNo,
            $table2.VendorID,
            $table2.ReturNo,
            $table2.Date,
            ifnull(SUM(AP_Retur_Det.Qty * AP_Retur_Det.Price), 0)  as SubTotal,
            ifnull(SUM(AP_Retur_Det.Total), 0)      as Total,
            sell.Tax                                as Tax,
            '0'                                     as PPN,
            ifnull(SUM(AP_Retur_Det.Discount), 0)   as Discount,
            '0'                                     as DeliveryCost,
            'return'                                as invoiceType,
            'Return'                                as invoiceTypetxt,
        ");
        $this->db->join("PS_Purchase as sell", "sell.PurchaseNo = $table2.PurchaseNo and sell.CompanyID = $table2.CompanyID", "left");
        $this->db->join("AP_Retur_Det", "AP_Retur.ReturNo = AP_Retur_Det.ReturNo and AP_Retur.CompanyID = AP_Retur_Det.CompanyID", "left");
        $this->db->from("$table2");
        $this->db->where("$table2.CompanyID", $CompanyID);
        $this->db->where("$table2.VendorID", $vendorid);
        $this->db->where("$table2.Status", 1);
        $this->db->where("$table2.Type", 2);
        $this->db->where("$table2.ReturType", 3);
        if($method == "update"):
            $returnno = explode(",", $returnno);
            $this->db->group_start();
            $this->db->where("$table2.InvoiceStatus", 0);
            $this->db->or_where_in("$table2.ReturNo", $returnno);
            $this->db->group_end();
        else:
            $this->db->where("$table2.InvoiceStatus", 0);
        endif;
        $this->db->group_by("$table2.ReturNo,sell.Tax");
        $query2 = $this->db->get_compiled_select();

        $query = $this->db->query('SELECT * FROM ('.$query1 . ' UNION ' . $query2.') AS Z order by PurchaseNo,Date asc');

        return $query->result();
    }

    public function invoice_sell_detail($SellNo){
        $table      = "PS_Sell_Detail";
        $CompanyID  = $this->session->CompanyID;
        $this->db->select("
            selldet.Price,
            selldet.Discount,
            selldet.Qty,
            selldet.DeliveryQty,
        ");
        $this->db->from("$table as selldet");
        $this->db->where("selldet.CompanyID", $CompanyID);
        $this->db->where("selldet.SellNo", $SellNo);
        $query = $this->db->get();

        return $query->result();
    }

    public function invoice_purchase_detail($SellNo){
        $table      = "PS_Purchase_Detail";
        $CompanyID  = $this->session->CompanyID;
        $this->db->select("
            selldet.Price,
            selldet.Discount,
            selldet.Qty,
            selldet.ReceiveQty,
        ");
        $this->db->from("$table as selldet");
        $this->db->where("selldet.CompanyID", $CompanyID);
        $this->db->where("selldet.PurchaseNo", $PurchaseNo);
        $query = $this->db->get();

        return $query->result();
    }

    public function get_invoice_sell_detail($d){
        $list = $this->invoice_sell_detail($d->SellNo);
        
        $sub_total      = 0;
        $discount       = 0;
        $ppn            = 0;
        $total          = 0;
        
        foreach ($list as $k => $v) {
            $qty = $v->Qty - $v->DeliveryQty;

            $xsub_total = $qty * $v->Price;
            $sub_total += $xsub_total; 
            $xdiscount  = $this->PersenttoRp($xsub_total,$v->Discount);
            $discount  += $xdiscount;
            $xsub_total = $xsub_total - $xdiscount;

            if($d->Tax == 1):
                $xppn = 10;
            else:
                $xppn = 0;
            endif;
            $xppn       = $this->PersenttoRp($xsub_total, $xppn);
            $ppn       += $xppn;
            $xtotal     = $xsub_total + $xppn;
            $total     += $xtotal;
        }
        $total += $d->DeliveryCost;

        $data = array(
            "sub_total"     => $sub_total,
            "discount"      => $discount,
            "ppn"           => $ppn,
            "total"         => $total,
            "list"          => $list,
        );

        return $data;
    }

    public function get_invoice_purchase_detail($d){
        $list = $this->get_invoice_purchase_detail($d->PurchaseNo);
        
        $sub_total      = 0;
        $discount       = 0;
        $ppn            = 0;
        $total          = 0;
        
        foreach ($list as $k => $v) {
            $qty = $v->Qty - $v->ReceiveNo;

            $xsub_total = $qty * $v->Price;
            $sub_total += $xsub_total; 
            $xdiscount  = $this->PersenttoRp($xsub_total,$v->Discount);
            $discount  += $xdiscount;
            $xsub_total = $xsub_total - $xdiscount;

            if($d->Tax == 1):
                $xppn = 10;
            else:
                $xppn = 0;
            endif;
            $xppn       = $this->PersenttoRp($xsub_total, $xppn);
            $ppn       += $xppn;
            $xtotal     = $xsub_total + $xppn;
            $total     += $xtotal;
        }
        $total += $d->DeliveryCost;

        $data = array(
            "sub_total"     => $sub_total,
            "discount"      => $discount,
            "ppn"           => $ppn,
            "total"         => $total,
            "list"          => $list,
        );

        return $data;
    }

    // end invoice selling

    // invoice
    public function invoice($invoiceno="",$Type="",$page=""){
        $method     = $this->input->post('method');
        $modul      = $this->input->post("modul");
        if($Type == ""):
            $Type       = $this->input->post('Type');
        endif;
        $VendorID       = $this->input->post('VendorID');
        $temp_invoiceno = $this->input->post('temp_invoiceno');
        $CompanyID      = $this->session->CompanyID;
        $this->db->select("
            invoice.InvoiceNo as Code,
            invoice.InvoiceNo,
            '' as BalanceID,
            '' as BalanceDetID,
            invoice.VendorID,
            invoice.Date,
            invoice.Total,
            invoice.Type,
            invoice.OrderType,
            invoice.Remark,
            '1'         as transactionType,
            'Invoice'   as transactionTypetxt,
            '1'         as BalanceType,
        ");
        $this->db->where("invoice.CompanyID", $CompanyID);
        $this->db->from("PS_Invoice as invoice");
        
        // ap
        if($Type == 1):
            $this->db->where("invoice.Type", 1);
        // ar
        elseif($Type == 2):
            $this->db->where("invoice.Type", 2);
        endif;

        if($invoiceno):
            $this->db->where("invoice.InvoiceNo", $invoiceno);
        endif;

        if($VendorID):
            $this->db->where("invoice.VendorID", $VendorID);
        endif;

        if($modul == "payment_ar"):
            $this->db->where("invoice.Status", 1);

            if($method == "update"):
                $this->db->select("(invoice.Total - ifnull(
                    (select sum(pd.Total) from PS_Payment_Detail as pd 
                    left join PS_Payment as p on pd.PaymentNo = p.PaymentNo and pd.CompanyID = p.CompanyID
                    where pd.InvoiceNo = invoice.InvoiceNo and p.Status = '1' and p.CompanyID = invoice.CompanyID
                    )
                , 0)) as Unpaid,");

                $temp_invoiceno = explode(",", $temp_invoiceno);
                $this->db->group_start();
                $this->db->where("invoice.PaymentStatus", 0);
                $this->db->or_where_in("invoice.InvoiceNo", $temp_invoiceno);
                $this->db->group_end();
            else:
                $this->db->select("(invoice.Total - ifnull(
                    (select sum(pd.Total) from PS_Payment_Detail as pd 
                    left join PS_Payment as p on pd.PaymentNo = p.PaymentNo and pd.CompanyID = p.CompanyID
                    where pd.InvoiceNo = invoice.InvoiceNo and p.Status = '1' and p.CompanyID = invoice.CompanyID
                    )
                , 0)) as Unpaid,");
            $this->db->where("invoice.PaymentStatus", 0);
            endif;

            $query = $this->db->get_compiled_select();
            return $query;
        elseif($modul == "payment_ap"):
            $this->db->where("invoice.Status", 1);

            if($method == "update"):
                $this->db->select("(invoice.Total - ifnull(
                    (select sum(pd.Total) from PS_Payment_Detail as pd 
                    left join PS_Payment as p on pd.PaymentNo = p.PaymentNo and pd.CompanyID = p.CompanyID
                    where pd.InvoiceNo = invoice.InvoiceNo and p.Status = '1' and p.CompanyID = invoice.CompanyID
                    )
                , 0)) as Unpaid,");

                $temp_invoiceno = explode(",", $temp_invoiceno);
                $this->db->group_start();
                $this->db->where("invoice.PaymentStatus", 0);
                $this->db->or_where_in("invoice.InvoiceNo", $temp_invoiceno);
                $this->db->group_end();
            else:
                $this->db->select("(invoice.Total - ifnull(
                    (select sum(pd.Total) from PS_Payment_Detail as pd 
                    left join PS_Payment as p on pd.PaymentNo = p.PaymentNo and pd.CompanyID = p.CompanyID
                    where pd.InvoiceNo = invoice.InvoiceNo and p.Status = '1' and p.CompanyID = invoice.CompanyID
                    )
                , 0)) as Unpaid,");
            $this->db->where("invoice.PaymentStatus", 0);
            endif;

            $query = $this->db->get_compiled_select();
            return $query;
        endif;

        $query = $this->db->get();
        if($page == "detail"):
            return $query->row();
        else:
            return $query->result();
        endif;
    }

    #delivery
    public function delivery($page="",$search="",$p2="",$dt=""){
        $CompanyID  = $this->session->CompanyID;
        $table      = "PS_Delivery";

        $crud               = $this->input->post("crud");
        $temp_deliveryno    = $this->input->post("temp_deliveryno");

        if($p2 == "serverSide"):
            $pageServerSide = $dt["page"];
            if($pageServerSide == "count_all"):
                $this->db->where("InvoiceStatus", 0);
                $this->db->where("Status", 1);
                $this->db->where("CompanyID", $this->session->CompanyID);
                $this->db->from($table);
                return $this->db->count_all_results();
            endif;
        endif;

        $this->db->select("
            $table.DeliveryNo,
            $table.Date,
        ");
        $this->db->where("$table.CompanyID", $CompanyID);
        if($page == "return_sales"):
            $ck_invoice = "(select count(dt.DeliveryNo) from PS_Invoice_Detail dt join PS_Invoice mt
                on mt.InvoiceNo = dt.InvoiceNo and mt.CompanyID = dt.CompanyID 
                where mt.Status = '1' and dt.CompanyID = $table.CompanyID and dt.DeliveryNo = $table.DeliveryNo
                )";

            $ck_return = "(select sum(dt.Qty) from AP_Retur_Det dt join AP_Retur mt 
                on mt.ReturNo = dt.ReturNo and mt.CompanyID = dt.CompanyID
                where mt.Status = '1' and dt.CompanyID = $table.CompanyID and dt.DeliveryNo = $table.DeliveryNo
                )";
            $ck_qty    = "(select sum(dt.Qty) from PS_Delivery_Det dt where dt.CompanyID = $table.CompanyID and dt.DeliveryNo = $table.DeliveryNo)";
            $this->db->where("$table.ProductType", 0);
            $this->db->where("$table.Status", 1);
            $this->db->where("ifnull($ck_invoice,0) <= 0");
            if($crud == "update"):
                $this->db->group_start();
                $this->db->where("ifnull($ck_return,0) < ifnull($ck_qty,0)");
                $this->db->or_where("$table.DeliveryNo", $temp_deliveryno);
                $this->db->group_end();
            else:
                $this->db->where("ifnull($ck_return,0) < ifnull($ck_qty,0)");
            endif;
            $this->db->where("$table.VendorID", $search);
        endif;
        $this->db->from($table);

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
            $query = $this->db->get($table);
            return $query->result();
        endif;  
    }

    public function delivery_detail($page="",$search="",$search2=""){
        $table          = "PS_Delivery_Det";
        $CompanyID      = $this->session->CompanyID;

        $method             = $this->input->post("method");
        $temp_deliverydet   = $this->input->post("temp_deliverydet");

        $this->db->select("
            $table.DeliveryDet,
            $table.DeliveryNo,
            $table.CompanyID,
            $table.SellNo,
            $table.SellDet,
            $table.ProductID,
            $table.Qty,
            $table.Conversion,
            $table.Uom as UnitID,
            $table.Type,
            $table.Price,
            $table.TotalPrice,
            $table.Discount,
            $table.DiscountValue,
            (case 
                when delivery.Type = 1 then (select ifnull(PS_Sell.Module,'') from PS_Sell where SellNo = $table.SellNo and CompanyID = $table.CompanyID)
                else ifnull(delivery.Module,'')
            end) deliveryModule,

            delivery.Tax,
            delivery.CostPaid,

            product.Code    as product_code,
            product.Name    as product_name,

            ifnull(unit.Uom,'') as unit_name,

            (case when delivery.Type = 1 then sell.BranchID else delivery.BranchID end) as BranchID,
            (case when delivery.Type = 1 then ifnull((select Name from Branch where BranchID = sell.BranchID and CompanyID = delivery.CompanyID),'')
            else ifnull((select Name from Branch where BranchID = delivery.BranchID and CompanyID = delivery.CompanyID),'')
            end) as branchName,
        ");
        $this->db->join("PS_Delivery as delivery", "delivery.DeliveryNo = $table.DeliveryNo and delivery.CompanyID = $table.CompanyID", "left");
        $this->db->join("PS_Sell as sell", "sell.SellNo = $table.SellNo and sell.CompanyID = $table.CompanyID", "left");
        $this->db->join("ps_product as product", "product.ProductID = $table.ProductID", "left");
        // $this->db->join("ps_unit as unit", "unit.UnitID = $table.UnitID", "left");
        $this->db->join("ps_product_unit as unit", "unit.ProductUnitID = $table.Uom", "left");
        $this->db->where("$table.CompanyID", $CompanyID);
        if($page == "return_sales"):
            $this->db->where("delivery.ProductType", 0);
            $this->db->where("$table.DeliveryNo", $search);
            $where = "ifnull((select sum(dt.Qty) from AP_Retur_Det as dt left join AP_Retur as d 
            on d.ReturNo = dt.ReturNo and d.CompanyID = dt.CompanyID
            where d.Status = 1 and dt.CompanyID = '$CompanyID' and dt.DeliveryDet = $table.DeliveryDet), 0)";
            $this->db->select("$table.Qty - ".$where." as qty_stock");
            if($method == "update"):
                $temp_deliverydet = explode(",", $temp_deliverydet);
                $this->db->group_start();
                $this->db->where($where." < $table.Qty");
                $this->db->or_where_in("$table.DeliveryDet", $temp_deliverydet);
                $this->db->group_end();
            else:
                $this->db->where($where." < $table.Qty");
            endif;
        elseif($page == "detail"):
            $this->db->where("$table.DeliveryDet", $search);
            if($search2):
                $this->db->where("$table.DeliveryNo", $search2);
            endif;
        elseif($page == "list"):
            $this->db->where("$table.DeliveryNo", $search);
        endif;
        $query = $this->db->get($table);
        if($page == "detail"):
            return $query->row();
        else:
            return $query->result();
        endif;
    }
    #end delivery

    public function bulan_romawi($month=""){
        if($month == ""):
            $month = date("m");
        endif;
        $month = (int) $month;
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($month > 0) {
            foreach ($map as $roman => $int) {
                if($month >= $int) {
                    $month -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }

    #cost paid
    public function CostPaid($invoiceno,$totalpaid){
        $list = $this->api->invoice_det_detail($invoiceno);

        foreach ($list as $k => $v) {
            if($v->ReturNo):
                $totalpaid += $v->Total;
            endif;
        }

        $arrReceive  = array();
        $arrDelivery = array();
        foreach ($list as $k => $v) {
            $CompanyID = $v->CompanyID;
            if($totalpaid>0):
                // invoice ap
                if($v->invoiceType == 1):
                    // invoice ap
                    if($v->OrderType == 1):
                        // receive
                        if($v->ReceiveNo):
                            $receive = $this->api->receive_detail($v->ReceiveNo);
                            if($totalpaid>=$receive->Payment):
                                $total = $receive->Payment;
                                $data = array("CostPaid" => $total);
                            else:
                                $data = array("CostPaid" => $totalpaid);
                            endif;
                            $this->db->where("ReceiveNo", $v->ReceiveNo);
                            $this->db->where("CompanyID", $v->CompanyID);
                            $this->db->update("AP_GoodReceipt", $data);
                            $totalpaid -= $receive->Payment;
                            if(!in_array($v->ReceiveNo, $arrReceive)):
                                array_push($arrReceive, $v->ReceiveNo);
                            endif;
                        endif;
                    endif;
                    //end receive

                // invoice ar
                elseif($v->invoiceType == 2):
                    // delivery
                    if($v->OrderType == 1):
                        if($v->DeliveryNo):
                            $delivery = $this->api->delivery_detail($v->DeliveryNo);
                            if($totalpaid>=$delivery->Payment):
                                $total = $delivery->Payment;
                                $data = array("CostPaid" => $total);
                            else:
                                $data = array("CostPaid" => $totalpaid);
                            endif;
                            $this->db->where("DeliveryNo", $v->DeliveryNo);
                            $this->db->where("CompanyID", $v->CompanyID);
                            $this->db->update("PS_Delivery", $data);
                            $totalpaid -= $delivery->Payment;
                            if(!in_array($v->DeliveryNo, $arrDelivery)):
                                array_push($arrDelivery, $v->DeliveryNo);
                            endif;
                        endif;

                    // selling
                    elseif($v->OrderType == 2):
                        if($v->SellNo):
                            $selling = $this->api->selling($v->SellNo,"detail");
                            if($totalpaid>=$selling->Payment):
                                $total = $selling->Payment;
                                $data = array("CostPaid" => $total);
                            else:
                                $data = array("CostPaid" => $totalpaid);
                            endif;
                            $this->db->where("SellNo", $v->SellNo);
                            $this->db->where("CompanyID", $v->CompanyID);
                            $this->db->update("PS_Sell", $data);
                            $totalpaid -= $selling->Payment;
                        endif;
                    endif;
                endif;
            endif;
        }

        $this->CostPaidReceive($arrReceive);
        $this->CostPaidDelivery($arrDelivery);
    }

    private function CostPaidDelivery($array){
        $CompanyID = $this->session->CompanyID;
        $arrSellNo = array();
        foreach ($array as $k => $v) {
            $list = $this->delivery_detail("list", $v);
            $CostPaid = 0;
            foreach ($list as $key => $value) {
                if($key == 0):
                    //$return   = $this->get_CostPaidReturn($value->DeliveryNo);
                    $CostPaid   = $value->CostPaid;
                endif;
                if($CostPaid>0):
                    if($CostPaid>=$value->TotalPrice):
                        $total = $value->TotalPrice;
                    else:
                        $total = $CostPaid;
                    endif;
                    $CostPaid -= $value->TotalPrice;
                    $this->db->where("CompanyID", $value->CompanyID);
                    $this->db->where("DeliveryDet", $value->DeliveryDet);
                    $this->db->update("PS_Delivery_Det", array("CostPaid" => $total));
                    if($value->SellNo):
                        if(!in_array($value->SellNo, $arrSellNo)):
                            array_push($arrSellNo, $value->SellNo);
                        endif;
                    endif;
                endif;
            }
        }
        foreach ($arrSellNo as $key => $v) {
            $cost = $this->db->query("
                select sum(ifnull(deliverydet.CostPaid,0) + ifnull(deliverydet.DeliveryCost,0)) as costpaid from PS_Sell sell 
                join PS_Sell_Detail selldet on sell.SellNo = selldet.SellNo and sell.CompanyID = selldet.CompanyID
                join PS_Delivery_Det deliverydet on deliverydet.SellDet = selldet.SellDet and deliverydet.CompanyID = selldet.CompanyID
                join PS_Delivery delivery on deliverydet.DeliveryNo = delivery.DeliveryNo and deliverydet.CompanyID = delivery.CompanyID
                where
                sell.CompanyID = '$CompanyID'    and
                sell.SellNo     = '$v'  and
                delivery.Status = '1'
            ")->row()->costpaid;
            
            $this->db->query("
                UPDATE PS_Sell SET
                    CostPaid    = $cost,
                    Paid        = case when Payment <= $cost then 1 else 0 end
                WHERE
                    CompanyID   = '$CompanyID' and
                    SellNo      = '$v'
            ");
        }
    }

    private function CostPaidReceive($array){
        $CompanyID = $this->session->CompanyID;
        $arrPurchaseNo = array();
        foreach ($array as $k => $v) {
            $list = $this->receive_detail("list", $v);
            $CostPaid = 0;
            foreach ($list as $key => $value) {
                if($key == 0):
                    //$return   = $this->get_CostPaidReturn($value->DeliveryNo);
                    $CostPaid   = $value->CostPaid;
                endif;
                if($CostPaid>0):
                    if($CostPaid>=$value->TotalPrice):
                        $total = $value->TotalPrice;
                    else:
                        $total = $CostPaid;
                    endif;
                    $CostPaid -= $value->TotalPrice;
                    $this->db->where("CompanyID", $value->CompanyID);
                    $this->db->where("ReceiveDet", $value->receive_det);
                    $this->db->update("AP_GoodReceipt_Det", array("CostPaid" => $total));
                    if($value->PurchaseNo):
                        if(!in_array($value->PurchaseNo, $arrPurchaseNo)):
                            array_push($arrPurchaseNo, $value->PurchaseNo);
                        endif;
                    endif;
                endif;
            }
        }
        foreach ($arrPurchaseNo as $key => $v) {
            $cost = $this->db->query("
                select sum(ifnull(receivedet.CostPaid,0) + ifnull(receivedet.DeliveryCost,0)) as costpaid from PS_Purchase purchase 
                join PS_Purchase_Detail purchasedet on purchase.PurchaseNo = purchasedet.PurchaseNo and purchase.CompanyID = purchasedet.CompanyID
                join AP_GoodReceipt_Det receivedet on receivedet.PurchaseDet = purchasedet.PurchaseDet and receivedet.CompanyID = purchasedet.CompanyID
                join AP_GoodReceipt receive on receivedet.ReceiveNo = receive.ReceiveNo and receivedet.CompanyID = receive.CompanyID
                where
                purchase.CompanyID      = '$CompanyID'   and
                purchase.PurchaseNo     = '$v'  and
                receive.Status          = '1'
            ")->row()->costpaid;
            
            $this->db->query("
                UPDATE PS_Purchase SET
                    CostPaid    = $cost,
                    Paid        = case when Payment <= $cost then 1 else 0 end
                WHERE
                    CompanyID   = '$CompanyID' and
                    PurchaseNo  = '$v'
            ");
        }
    }

    private function get_CostPaidReturn($DeliveryNo=""){
        $CompanyID = $this->session->CompanyID;
        $query = $this->db->query("
            select ifnull(sum(returndet.Total),0) as total from AP_Retur_Det returndet
            join AP_Retur retur on retur.ReturNo = returndet.ReturNo and retur.CompanyID = returndet.CompanyID
            join PS_Invoice_Detail invoicedet on invoicedet.ReturNo = retur.ReturNo and invoicedet.CompanyID = retur.CompanyID
            join PS_Invoice invoice on invoice.InvoiceNo = invoicedet.InvoiceNo and invoice.CompanyID = invoicedet.CompanyID
            join PS_Payment_Detail paymentdet on paymentdet.InvoiceNo = invoice.InvoiceNo and paymentdet.CompanyID = invoice.CompanyID
            join PS_Payment payment on payment.PaymentNo = paymentdet.PaymentNo and payment.CompanyID = paymentdet.CompanyID
            where 
                returndet.CompanyID = '$CompanyID' and
                payment.Status = '1' and
                returndet.DeliveryNo = '$DeliveryNo'");
        return $query->row()->total;
    }

    //  private function get_CostPaidReturn($ReceiveNo=""){
    //     $CompanyID = $this->session->CompanyID;
    //     $query = $this->db->query("
    //         select ifnull(sum(returndet.Total),0) as total from AP_Retur_Det returndet
    //         join AP_Retur retur on retur.ReturNo = returndet.ReturNo and retur.CompanyID = returndet.CompanyID
    //         join PS_Invoice_Detail invoicedet on invoicedet.ReturNo = retur.ReturNo and invoicedet.CompanyID = retur.CompanyID
    //         join PS_Invoice invoice on invoice.InvoiceNo = invoicedet.InvoiceNo and invoice.CompanyID = invoicedet.CompanyID
    //         join PS_Payment_Detail paymentdet on paymentdet.InvoiceNo = invoice.InvoiceNo and paymentdet.CompanyID = invoice.CompanyID
    //         join PS_Payment payment on payment.PaymentNo = paymentdet.PaymentNo and payment.CompanyID = paymentdet.CompanyID
    //         where 
    //             returndet.CompanyID = '$CompanyID' and
    //             payment.Status = '1' and
    //             returndet.ReceiveNo = '$ReceiveNo'");
    //     return $query->row()->total;
    // }

    #end cost paid

    #create module app
    public function createModule($days,$page=""){
        $date1 = date("Y-m-d", strtotime(date("Y-m-d")." -1 Days"));
        $date2 = date("Y-m-d", strtotime(date("Y-m-d")." -1 Days"));
        $date3 = date("Y-m-d", strtotime(date("Y-m-d")." -1 Days"));
        $date4 = date("Y-m-d", strtotime(date("Y-m-d")." -1 Days"));
        $date5 = date("Y-m-d", strtotime(date("Y-m-d")." -1 Days"));

        if($page == "ar"):
            $date1 = date("Y-m-d", strtotime(date("Y-m-d")." +".$days." Days"));
        elseif($page == "ap"):
            $date2 = date("Y-m-d", strtotime(date("Y-m-d")." +".$days." Days"));
        elseif($page == "ac"):
            $date3 = date("Y-m-d", strtotime(date("Y-m-d")." +".$days." Days"));
        elseif($page == "inventory"):
            $date4 = date("Y-m-d", strtotime(date("Y-m-d")." +".$days." Days"));
        elseif($page == "asset"):
            $date5 = date("Y-m-d", strtotime(date("Y-m-d")." +".$days." Days"));
        endif;

        $data['ar'] = array(
            "status"    => 1,
            "expire"    => $date1,
        );

        $data['ap'] = array(
            "status"    => 1,
            "expire"    => $date2,
        );

        $data['ac'] = array(
            "status"    => 1,
            "expire"    => $date3,
        );

        $data['inventory'] = array(
            "status"    => 1,
            "expire"    => $date4,
        );

        $data['asset'] = array(
            "status"    => 1,
            "expire"    => $date5,
        );

        return json_encode($data);
    }
    #end create modlue app
    public function get_module_company($id=""){
        $this->db->select("Module");
        if($id):
            $this->db->where("id_user",$id);
        else:
            $this->db->where("id_user", $this->session->CompanyID);
        endif;
        $query = $this->db->get("user")->row();
        $modlue = $query->Module;
        $modlue = json_decode($modlue);

        return $modlue;
    }

    #parameter Module
    public function parameter_modul($module=""){
        // ["ar","so","delivery","return_ar","invoice_ar","correction_ar","payment_ar"]
        // ["ap","po","receipt","return_ap","invoice_ap","correction_ap","payment_ap"]
        // ["ac","cash_bank","jurnal"]
        // ["inventory","mutation","stock"]

        $d = "[]";
        if($module == "ar"):               $d = $this->session->AR;
        elseif($module == "ap"):           $d = $this->session->AP;
        elseif($module == "ac"):           $d = $this->session->AC;
        elseif($module == "inventory"):    $d = $this->session->Inventory;
        endif;
        $data   = json_decode($d);

        if(!is_array($data)):
            $data = array();
        endif;

        return $data;
    }

    public function arrModule(){
        $arr = array('ar','ap','ac','inventory');

        return $arr;
    }

    public function check_parameter_module($module,$page){
        $datenow            = date("Y-m-d");
        $Module             = $this->get_module_company();
        $parameter_modul    = $this->parameter_modul($module);
         if(!$parameter_modul):
            $parameter_modul = array();
        endif;
        
        $modulex = 0;
        $view    = 0;
        $add     = 0;

        if($module == "ar"):
            if($Module->ar->status == 1):
                if(in_array("ar", $parameter_modul)):
                    if($page == "selling" || $page == "so"): if(in_array("so", $parameter_modul)): 
                        $view = 1;
                        if($Module->ar->expire >= $datenow): $add = 1; endif;
                    endif; endif;
                    if($page == "delivery"): if(in_array("delivery", $parameter_modul)): 
                        $view = 1;
                        if($Module->ar->expire >= $datenow): $add = 1; endif;
                    endif; endif;
                    if($page == "return_ar"): if(in_array("return_ar", $parameter_modul)): 
                        $view = 1;
                        if($Module->ar->expire >= $datenow): $add = 1; endif;
                    endif; endif;
                    if($page == "invoice_ar"): if(in_array("invoice_ar", $parameter_modul)): 
                        $view = 1;
                        if($Module->ar->expire >= $datenow): $add = 1; endif;
                    endif; endif;
                    if($page == "correction_ar"): if(in_array("correction_ar", $parameter_modul)): 
                        $view = 1;
                        if($Module->ar->expire >= $datenow): $add = 1; endif;
                    endif; endif;
                    if($page == "payment_ar"): if(in_array("payment_ar", $parameter_modul)): 
                        $view = 1;
                        if($Module->ar->expire >= $datenow): $add = 1; endif;
                    endif; endif;
                    if($page == "ar"): if(in_array("ar", $parameter_modul)): 
                        $view = 1;
                        if($Module->ar->expire >= $datenow): $add = 1; endif;
                    endif; endif;
                endif;
            endif;
        elseif($module == "ap"):
            if($Module->ap->status == 1):
                if(in_array("ap", $parameter_modul)):
                    if($page == "po"): if(in_array("po", $parameter_modul)): 
                        $view = 1;
                        if($Module->ap->expire >= $datenow): $add = 1; endif;
                    endif; endif;
                    if($page == "receipt"): if(in_array("receipt", $parameter_modul)): 
                        $view = 1;
                        if($Module->ap->expire >= $datenow): $add = 1; endif;
                    endif; endif;
                    if($page == "return_ap"): if(in_array("return_ap", $parameter_modul)): 
                        $view = 1;
                        if($Module->ap->expire >= $datenow): $add = 1; endif;
                    endif; endif;
                    if($page == "invoice_ap"): if(in_array("invoice_ap", $parameter_modul)): 
                        $view = 1;
                        if($Module->ap->expire >= $datenow): $add = 1; endif;
                    endif; endif;
                    if($page == "correction_ap"): if(in_array("correction_ap", $parameter_modul)): 
                        $view = 1;
                        if($Module->ap->expire >= $datenow): $add = 1; endif;
                    endif; endif;
                    if($page == "payment_ap"): if(in_array("payment_ap", $parameter_modul)): 
                        $view = 1;
                        if($Module->ap->expire >= $datenow): $add = 1; endif;
                    endif; endif;
                    if($page == "ap"): if(in_array("ap", $parameter_modul)): 
                        $view = 1;
                        if($Module->ap->expire >= $datenow): $add = 1; endif;
                    endif; endif;
                endif;
            endif;
        elseif($module == "ac"):
            if($Module->ac->status == 1):
                if(in_array("ac", $parameter_modul)):
                    if($page == "cash_bank"): if(in_array("cash_bank", $parameter_modul)): 
                        $view = 1;
                        if($Module->ac->expire >= $datenow): $add = 1; endif;
                    endif; endif;
                    if($page == "jurnal"): if(in_array("jurnal", $parameter_modul)): 
                        $view = 1;
                        if($Module->ac->expire >= $datenow): $add = 1; endif;
                    endif; endif;
                    if($page == "ac"): if(in_array("ac", $parameter_modul)): 
                        $view = 1;
                        if($Module->ac->expire >= $datenow): $add = 1; endif;
                    endif; endif;
                endif;
            endif;
        elseif($module == "inventory"):
            if($Module->inventory->status == 1):
                if(in_array("inventory", $parameter_modul)):
                    if($page == "mutation"): if(in_array("mutation", $parameter_modul)): 
                        $view = 1;
                        if($Module->inventory->expire >= $datenow): $add = 1; endif;
                    endif; endif;
                    if($page == "stock"): if(in_array("stock", $parameter_modul)): 
                        $view = 1;
                        if($Module->inventory->expire >= $datenow): $add = 1; endif;
                    endif; endif;
                    if($page == "inventory_goodreceipt"): if(in_array("inventory_goodreceipt", $parameter_modul)): 
                        $view = 1;
                        if($Module->inventory->expire >= $datenow): $add = 1; endif;
                    endif; endif;
                    if($page == "good_issue"): if(in_array("good_issue", $parameter_modul)): 
                        $view = 1;
                        if($Module->inventory->expire >= $datenow): $add = 1; endif;
                    endif; endif;
                    if($page == "inventory"): if(in_array("inventory", $parameter_modul)): 
                        $view = 1;
                        if($Module->inventory->expire >= $datenow): $add = 1; endif;
                    endif; endif;
                endif;
            endif;
        endif;

        $data = array(
            "add"       => $add,
            "view"      => $view,
        );

        $data = json_encode($data);
        $data = json_decode($data);

        return $data;
    }
    public function save_rekening($data)
    {
        $this->db->set("UserAdd",$this->session->Name);
        $this->db->set("DateAdd",date("Y-m-d H:i:s"));
        $this->db->insert("user_rekening", $data);
        return $this->db->insert_id();  
    }

    #vendor_price------------------------------------------------------------------------------------------
    public function autocomplete_vendor_price($page,$search){
        $CompanyID = $this->session->CompanyID;

        $this->db->select("ProductCustomer as Name");
        $this->db->where("CompanyID", $CompanyID);
        $this->db->like("ProductCustomer",$search);
        $this->db->group_by("ProductCustomer");
        $this->db->from("PS_Vendor");
        $query1 = $this->db->get_compiled_select();

        $this->db->select("GroupName as Name");
        $this->db->where("CompanyID", $CompanyID);
        $this->db->like("GroupName",$search);
        $this->db->group_by("GroupName");
        $this->db->from("ps_product_customer");
        $query2 = $this->db->get_compiled_select();

        $query  = $this->db->query('SELECT * FROM ('.$query1 . ' UNION ' . $query2.') AS Z group by Z.Name');
        
        return $query->result();

    }

     #unit------------------------------------------------------------------------------------------
    public function autocomplete_product_unit($page,$search){
        $CompanyID = $this->session->CompanyID;

        $this->db->select("UnitID as unitid");
        $this->db->where("CompanyID", $CompanyID);
        $this->db->like("UnitID",$search);
        $this->db->group_by("UnitID");
        $this->db->from("ps_product");
        $query1 = $this->db->get_compiled_select();

        $this->db->select("Name as name");
        $this->db->where("CompanyID", $CompanyID);
        $this->db->like("Name",$search);
        $this->db->group_by("Name");
        $this->db->from("ps_product_unit");
        $query2 = $this->db->get_compiled_select();

        $query = $this->db->query('SELECT * FROM ('.$query1 . ' UNION ' . $query2.') AS Z group by Z.Name');
        
        return $query->result();

    }

    function _get_datatables_query_vendor_price($page=""){
        $table  = "ps_product_customer";
        $column = array("$table.GroupName");
        $order  = array("$table.GroupName" => 'asc');
        $CompanyID = $this->session->CompanyID;

        $this->db->select("
            $table.ProductCustomerID as ID,
            $table.GroupName,
            $table.ProductID,
            $table.Type as price_type,
            product.Code as product_code,
            product.Name as product_name,
            ifnull(unit.Uom,'') as unit_name,
            case
                when $table.Type = 1 then 'Purchases Type'
                else 'Selling Type'
            end as Type,
            $table.Price,
            $table.PriceSell,
            $table.Status,
            product.PurchasePrice,
            product.SellingPrice,
        ");
        $this->db->join("ps_product as product", "product.ProductID = $table.ProductID","left");
        $this->db->join("ps_product_unit as unit", "unit.ProductID = product.ProductID and unit.Uom = product.Uom","left");
        // $this->db->join("ps_unit as unit", "unit.UnitID = product.UnitID","left");
        $this->db->where("$table.CompanyID",$CompanyID);
        $this->db->from($table);
        $i = 0;
        foreach ($column as $item) // loop column 
        {
            if($_POST['search']['value']){
                
                if($i===0){
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND. 
                    $this->db->like($item, $_POST['search']['value']);
                }
                else{
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($column) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $column[$i] = $item; // set column array variable to order processing
            $i++;
        }
        if(isset($_POST['order'])){
            $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($order)){
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    function get_datatables_vendor_price($page = "")
    {
        $this->_get_datatables_query_vendor_price($page);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function count_filtered_vendor_price($page="")
    {
        $this->_get_datatables_query_vendor_price($page);
        $this->db->where("ps_product_customer.CompanyID",$this->session->CompanyID);
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function count_all_vendor_price($page="")
    {
        $this->db->where("ps_product_customer.CompanyID",$this->session->CompanyID);
        $this->db->from("ps_product_customer");
        return $this->db->count_all_results();
    }
    #end vendor_price------------------------------------------------------------------------------------------

    #bank company
    public function get_bank_company(){
        $CompanyID = $this->session->CompanyID;
        $this->db->select("
            UserRekID,
            BankName,
            BankBranch,
            NoRekening,
            AnRekening,
            Active,
        ");
        $this->db->where("CompanyID", $CompanyID);
        $query = $this->db->get("user_rekening");

        return $query->result();
    }
    #bank company

    #days report
    public function get_days_report(){
        $CompanyID = $this->session->CompanyID;
        $this->db->select("
            SettingParameterID,
            Days
        ");
        $this->db->where("CompanyID", $CompanyID);
        $query = $this->db->get("SettingParameter");

        return $query->result();
    }
    #days report

    #default template
    public function default_template($page){
        $CompanyID      = $this->session->CompanyID;
        $method         = $this->input->get("page");
        $TemplateID     = $this->input->post("TemplateID");
        $d_template     = $this->input->post("default_template");
        $cktemplate     = $this->input->post("cktemplate");

        if($method == "print" and $cktemplate == 1):
            $code   = "Template-".$page;
            $ck     = $this->db->count_all("UT_Rule where CompanyID = '$CompanyID' and cValue = 'template' and Code = '$code'");
            
            $data = array(
                "Code"          => $code,
                "CompanyID"     => $CompanyID,
                "nValue"        => $TemplateID,
                "cValue"        => "template",
                "Remarks"       => "auto generate system",
            );

            if($ck>0):
                // $this->update_setting_template($data,array("CompanyID" => $CompanyID, "Code" => $code));
            else:
                // $this->insert_setting_template($data);
            endif;

            if($d_template):
                $code           = "Template-".$page."-default";
                $data['Code']   = $code;
                
                $ck     = $this->db->count_all("UT_Rule where CompanyID = '$CompanyID' and cValue = 'template' and Code = '$code'");
                if($ck>0):
                    // $this->update_setting_template($data,array("CompanyID" => $CompanyID, "Code" => $code));
                else:
                    // $this->insert_setting_template($data);
                endif;
            endif;
        endif;
    }

    public function default_template2(){
        $CompanyID        = $this->session->CompanyID;
        $default_template = $this->input->post('default_template');
        foreach ($default_template as $k => $v) {
            if($v):
                $d = explode("-", $v);
                $page       = $d[0];
                $TemplateID = $d[1];

                $code   = "Template-".$page;
                $ck     = $this->db->count_all("UT_Rule where CompanyID = '$CompanyID' and cValue = 'template' and Code = '$code'");
                
                $data = array(
                    "Code"          => $code,
                    "CompanyID"     => $CompanyID,
                    "nValue"        => $TemplateID,
                    "cValue"        => "template",
                    "Remarks"       => "auto generate system",
                );

                if($ck>0):
                    $this->update_setting_template($data,array("CompanyID" => $CompanyID, "Code" => $code));
                else:
                    $this->insert_setting_template($data);
                endif;

                $code           = "Template-".$page."-default";
                $data['Code']   = $code;
                
                $ck     = $this->db->count_all("UT_Rule where CompanyID = '$CompanyID' and cValue = 'template' and Code = '$code'");
                if($ck>0):
                    $this->update_setting_template($data,array("CompanyID" => $CompanyID, "Code" => $code));
                else:
                    $this->insert_setting_template($data);
                endif;
            endif;
        }
    }

    public function insert_setting_template($data){
        $this->db->set("UserAdd",$this->session->NAMA);
        $this->db->set("DateAdd",date("Y-m-d H:i:s"));
        $this->db->insert("UT_Rule", $data);
        return $this->db->insert_id();
    }
    public function update_setting_template($data,$where){
        $this->db->set("UserCh",$this->session->NAMA);
        $this->db->set("DateCh",date("Y-m-d H:i:s"));
        $this->db->update("UT_Rule", $data, $where);
        return $this->db->affected_rows();
    }
    public function get_default_template($page){
        $code   = "Template-".$page;
        $table  = "Template";
        $this->db->select("
            $table.TemplateID,
            $table.Content,
        ");
        $this->db->join("UT_Rule as rule", "rule.nValue = $table.TemplateID");
        $this->db->where("rule.CompanyID", $this->session->CompanyID);
        $this->db->where("rule.Code", $code);
        $this->db->from($table);
        $query = $this->db->get();

        if(!$query->row()):
            $this->db->select("
                $table.TemplateID,
                $table.Content,
            ");
            $this->db->where("CompanyID", $this->session->CompanyID);
            $this->db->where("Status", 1);
            $this->db->where("Type", $page);
            $this->db->from($table);
            $query = $this->db->get();
        endif;
        return $query->row();
    }

    public function arrTemplate(){
        $arrTemplate = array('purchase','penerimaan','retur','invoice_ap','ap_correction','payment_ap','selling','delivery','return_sales','invoice_ar','ar_correction','payment_ar',);

        return $arrTemplate;
    }
    public function label_arrTemplate($type){
        $label = "";
        if($type == "purchase"): $label = "Purchase Order";
        elseif($type == "penerimaan"): $label = "Good Receipt";
        elseif($type == "retur"): $label = "Return Receipt";
        elseif($type == "invoice_ap"): $label = "Invoice Payable";
        elseif($type == "ap_correction"): $label = "Correction Payable";
        elseif($type == "payment_ap"): $label = "Payment Payable";
        elseif($type == "selling"): $label = "Selling Order";
        elseif($type == "delivery"): $label = "Delivery";
        elseif($type == "return_sales"): $label = "Return Selling";
        elseif($type == "invoice_ar"): $label = "Invoice Receivable";
        elseif($type == "ar_correction"): $label = "Correction Receivabler";
        elseif($type == "payment_ar"): $label = "Payment Receivable";
        endif;

        return $label;
    }
    #end template

    function penyebut($nilai) {
        $nilai = abs($nilai);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " ". $huruf[$nilai];
        } else if ($nilai <20) {
            $temp = $this->penyebut($nilai - 10). " belas";
        } else if ($nilai < 100) {
            $temp = $this->penyebut($nilai/10)." puluh". $this->penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus" . $this->penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = $this->penyebut($nilai/100) . " ratus" . $this->penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu" . $this->penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = $this->penyebut($nilai/1000) . " ribu" . $this->penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = $this->penyebut($nilai/1000000) . " juta" . $this->penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = $this->penyebut($nilai/1000000000) . " milyar" . $this->penyebut(fmod($nilai,1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = $this->penyebut($nilai/1000000000000) . " trilyun" . $this->penyebut(fmod($nilai,1000000000000));
        }     
        return $temp;
    }
 
    function terbilang($nilai) {
        if($nilai<0) {
            $hasil = "minus ". trim($this->penyebut($nilai));
        } else {
            $hasil = trim($this->penyebut($nilai));
        }           
        return $hasil;
    }

    public function PhoneISO(){
        $arrayISO = array("ID","SG","MY");
        $this->db->select("ISO,PhoneISO,Name");
        $this->db->where("PhoneISO !=","");
        $this->db->where_in("ISO",$arrayISO);
        $this->db->order_by("Name","ASC");
        $query = $this->db->get("Country");
        return $query->result();
    }

    public function PhoneFormat($phone){
        if(substr($phone, 0,1) == 0):
            $phone = substr($phone, -(strlen($phone) - 1), (strlen($phone) - 1));
        endif;
        return $phone;
    }
     public function tanggal($format, $tanggal="now"){
        return $this->konversi_tanggal($format,$tanggal);
    }

    public function AlertVerification()
    {
        $a = $this->user_detail($this->session->UserID);
        $AlertVerification  = 0;
        if($a->StatusVerify != 1):
            $JoinDate           = date("Y-m-d",strtotime($a->JoinDate));
            $datediff           = strtotime(date("Y-m-d")) - strtotime($JoinDate);
            $harix              = round($datediff / (60 * 60 * 24));
            if(in_array($harix, array(5,6))):
                $AlertVerification = 1;
            elseif($harix >= 7):
                $AlertVerification = 2;
                $this->DeadActiveCompany();
            endif;
        endif;
        return $AlertVerification;
    }
    private function DeadActiveCompany()
    {
        $this->db->set("StatusVerify",2);
        $this->db->set("status",0);
        $this->db->where("id_user",$this->session->UserID);
        $this->db->update("user");
    }

    public function relpace_str($string,$search,$to="'"){
        $str = str_replace($search, $to, $string);

        return $str;
    }

    public function check_module_stock($module){
        $data = array(
            "stock" => 1,
        );
        $data = json_encode($data);
        $data = json_decode($data);
        if($module):
            $module = json_decode($module);
            $data = $module;
        endif;

        return $data;
    }

    public function validate_modlue_add($module,$page){
        if($module && $page):
            $module = $this->check_parameter_module($module,$page);
            if($module->add <= 0):
                $data['inputerror'][]   = "";
                $data['error_string'][] = '';
                $data['list'][]      = '';
                $data['tab'][]       = '';
                $data['ara']         = $this->input->post();
                $data['message']     = 'Module selling is expired';
                $data['status']      = FALSE;
                $this->main->echoJson($data);
                exit();
            endif;
        endif;
    }

    public function validate_update(){
        $crud = $this->input->post("crud");
        if($crud != "insert"):
            $data['inputerror'][]   = "";
                $data['error_string'][] = '';
                $data['list'][]      = '';
                $data['tab'][]       = '';
                $data['ara']         = $this->input->post();
                $data['message']     = 'Sorry, the transaction can only be added';
                $data['status']      = FALSE;
                $this->main->echoJson($data);
                exit();
        endif;
    }

    public function join_vendor_address($page_post){
        if($page_post == "delivery"):
            $this->db->select("
                ifnull(address.Address,'')   as d_address,
                ifnull(address.City,'')      as d_city,
                ifnull(address.Province,'')  as d_province,
            ");
            $this->db->join("ps_vendor_address address", "address.VendorCode = vendor.Code and address.Delivery = '1' and address.CompanyID = vendor.CompanyID","left");
        elseif($page_post == "invoice"):
            $this->db->select("
                ifnull(address.Address,'')   as d_address,
                ifnull(address.City,'')      as d_city,
                ifnull(address.Province,'')  as d_province,
            ");
            $this->db->join("ps_vendor_address address", "address.VendorCode = vendor.Code and address.Payment = '1' and address.CompanyID = vendor.CompanyID","left");
        endif;
    }

    public function attachment_show($Type,$ID){
        $list = $this->api->attachment_list($Type,$ID);
        $data = array();
        foreach ($list as $k => $v) {
            $Name = '';
            if($v->Name):
                $Name = $v->Name;
            else:
                $file = $v->Image;
                $file = explode("/", $file);
                $Name = $file[count($file)-1];
            endif;
            $type = explode(".", $Name);
            if(count($type)>1):
                $type = $type[1];
            else:
                $type = '';
            endif;
            $h['filename']  = $Name;
            $h['url']       = $v->Image;
            $h['size']      = filesize($v->Image);
            $h['status']    = 1;
            $h['type']      = $type;
            $h['page']      = 'show';
            $h['id']        = $v->attachID;

            array_push($data, $h);
        }

        return $data;
    }

    public function js_css_version(){
        $val = "?version=4.5";
        return $val;
    }

    public function create_folder_temp(){
        $folder = 'file/temp'.$this->session->CompanyID.'/';
        if (!is_dir($folder)) {
            mkdir('./'.$folder, 0777, TRUE);
        }
    }

    public function create_folder_product(){
        $userid = $this->session->id_user;
        $this->create_folder_temp();
        $temp   = 'file/temp'.$this->session->CompanyID.'/';
        $folder = $temp."product_temp".$userid."/";
        if (!is_dir($folder)) {
            mkdir('./'.$folder, 0777, TRUE);
        }
        $files = glob('./'.$folder.'*.*');
        foreach($files as $file){
            if(is_file($file))
                unlink($file);
        }

        return $folder;
    }

    public function create_folder_vendor(){
        $userid = $this->session->id_user;
        $this->create_folder_temp();
        $temp   = 'file/temp'.$this->session->CompanyID.'/';
        $folder = $temp."vendor_temp".$userid."/";
        if (!is_dir($folder)) {
            mkdir('./'.$folder, 0777, TRUE);
        }
        $files = glob('./'.$folder.'*.*');
        foreach($files as $file){
            if(is_file($file))
                unlink($file);
        }

        return $folder;
    }

    public function create_folder_sales(){
        $userid = $this->session->id_user;
        $this->create_folder_temp();
        $temp   = 'file/temp'.$this->session->CompanyID.'/';
        $folder = $temp."sales_temp".$userid."/";
        if (!is_dir($folder)) {
            mkdir('./'.$folder, 0777, TRUE);
        }
        $files = glob('./'.$folder.'*.*');
        foreach($files as $file){
            if(is_file($file))
                unlink($file);
        }

        return $folder;
    }

    public function create_folder_coa(){
        $userid = $this->session->id_user;
        $this->create_folder_temp();
        $temp   = 'file/temp'.$this->session->CompanyID.'/';
        $folder = $temp."coa_temp".$userid."/";
        if (!is_dir($folder)) {
            mkdir('./'.$folder, 0777, TRUE);
        }
        $files = glob('./'.$folder.'*.*');
        foreach($files as $file){
            if(is_file($file))
                unlink($file);
        }

        return $folder;
    }

    public function create_folder_general($page){
        $userid = $this->session->id_user;
        $this->create_folder_temp();
        $temp   = 'file/temp'.$this->session->CompanyID.'/';
        $folder = $temp.$page.$userid."/";
        if (!is_dir($folder)) {
            mkdir('./'.$folder, 0777, TRUE);
        }
        $files = glob('./'.$folder.'*.*');
        foreach($files as $file){
            if(is_file($file))
                unlink($file);
        }

        return $folder;
    }

    public function meta(){
        $meta = '<meta charset="utf-8">';
        $meta .= '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
        $meta .= '<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">';
        $meta .= '<meta name="description" content="Online transaction cashier program, POS Android, Stock, and Accounting || ERP is Easy and Accurate">';
        $meta .= '<meta name="author" content="RC Electronic"> ';
        $meta .= "<meta name='keywords' content='software, point of sales, accounting software, software akuntansi, akuntansi keuangan, akuntansi dasar, software akuntansi gratis, software penjualan, software gudang' >";
        $meta .= "<meta content='Pipesys POS | Mobile POS | Point of Sale system' property='og:title'>";
        $meta .= "<meta property='og:image' content='".site_url('img/logo.png')."' >";
        $meta .= "<meta content='website' property='og:type'>";
        $meta .= "<meta content='".site_url()."' property='og:url'>";
        $meta .= "<meta content='website' property='og:type'>";
        $meta .= "<meta content='".site_url('img/logo.png')."' property='og:image'>";
        $meta .= "<meta content='Pipesys' property='og:site_name'>";

        return $meta;
    }

    public function session_voucher(){
        $data = array(
            "s_level"               => $this->session->s_level,
            "voucher_qty"           => $this->session->voucher_qty,
            "voucher_qty_module"    => $this->session->voucher_qty_module,
            "voucher_type"          => $this->session->voucher_type,
            "voucher_name"          => $this->session->voucher_name,
            "voucher_email"         => $this->session->voucher_email,
            "voucher_address"       => $this->session->voucher_address,
            "voucher_city"          => $this->session->voucher_city,
            "voucher_state"         => $this->session->voucher_state,
            "voucher_country"       => $this->session->voucher_country,
            "voucher_agree"         => $this->session->voucher_agree,
            "voucher_code"          => $this->session->voucher_code,
        );

        $data = json_encode($data);
        $data = json_decode($data);

        return $data;
    }

    public function session_voucher_reset(){
        $data = array(
            "s_level"               => "",
            "voucher_qty"           => "",
            "voucher_qty_module"    => "",
            "voucher_type"          => "",
            "voucher_name"          => "",
            "voucher_email"         => "",
            "voucher_address"       => "",
            "voucher_city"          => "",
            "voucher_state"         => "",
            "voucher_country"       => "",
            "voucher_agree"         => "",
            "voucher_code"          => "",
        );

        $this->session->set_userdata($data);
    }

    #20190730 MW
    #membuat file bahasa dan simpan di folder aset
    #bahasa english,indonesia
    public function create_file_language(){
        // indo
        $indonesia      = $this->lang->load('bahasa', 'indonesia');
        $script_indo    = "var language_app; $(document).ready(function(){ language_app = ".json_encode($this->lang->language,JSON_PRETTY_PRINT)."});";

        $file_indo      = "language_indo.js";
        $folder         = "aset/";
        if(is_file($folder.$file_indo)){
            unlink($folder.$file_indo);
        }
        file_put_contents($folder.$file_indo, $script_indo);

        // english
        $english            = $this->lang->load('bahasa', 'english');
        $script_english     = "var language_app; $(document).ready(function(){ language_app = ".json_encode($this->lang->language,JSON_PRETTY_PRINT)."});";
        $file_english       = "language_english.js";
        if(is_file($folder.$file_english)){
            unlink($folder.$file_english);
        }
        file_put_contents($folder.$file_english, $script_english);
    }

    public function create_site_file(){
        
        $folder     = "aset/";
        $data       = array();
        $file_name  = "general_app.js";

        $attachment = $this->api->attachment_list(array("SiteLogo","SiteLogoSmall"),"","array");
        foreach ($attachment as $k => $v) {
            $data[$v->Type] = site_url($v->Image);
        }

        $general = $this->api->general_settings();
        foreach ($general as $k => $v) {
            $data[$v->Code] = $v->Value;
        }

        $script     = "var general_app ; $(document).ready(function(){ general_app = ".json_encode($data,JSON_PRETTY_PRINT)."});";
        file_put_contents($folder.$file_name, $script);
    }

    #20190730 MW
    #validasi user add berdasarkan module
    public function check_user_add(){
        $datenow    = date("Y-m-d");
        $status     = true;
        $CompanyID  = $this->session->CompanyID;
        $Module     = $this->get_module_company();
        $module_ar  = $this->parameter_modul("ar");
        $module_ap  = $this->parameter_modul("ap");
        $module_ac  = $this->parameter_modul("ac");
        $module_inventory   = $this->parameter_modul("inventory");
        $count_module   = 0;
        $max_user       = 0;

        if($Module->ar->status == 1):
            if(in_array("ar", $module_ar)):
                if($Module->ar->expire >= $datenow): $count_module += 1; endif;
            endif;
        endif;
        if($Module->ap->status == 1):
            if(in_array("ap", $module_ap)):
                if($Module->ap->expire >= $datenow): $count_module += 1; endif;
            endif;
        endif;
        if($Module->ac->status == 1):
            if(in_array("ap", $module_ac)):
                if($Module->ac->expire >= $datenow): $count_module += 1; endif;
            endif;
        endif;
        if($Module->inventory->status == 1):
            if(in_array("inventory", $module_inventory)):
                if($Module->inventory->expire >= $datenow): $count_module += 1; endif;
            endif;
        endif;

        $ck_user = $this->db->count_all("user where (CompanyID = '$CompanyID' or id_user = '$CompanyID') and user.index <=7 and user.index is not null");
        $ck_user += 1;
        if($count_module == 1 && $ck_user>1):
            $status = false;
        elseif($count_module == 2 && $ck_user>3):
            $status = false;
        elseif($count_module == 3 && $ck_user>5):
            $status = false;
        elseif($count_module == 4 && $ck_user>7):
            $status = false;
        endif;

        return $status;
    }

    #20190730 MW
    #generate token unique
    public function generate_token_()
    {
        $b = sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0C2f ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0x2Aff ), mt_rand( 0, 0xffD3 ), mt_rand( 0, 0xff4B )
        );
        $generate = strtoupper(substr(rand(1,99).$b, 0,6));

        return $generate;
    }

    #20190730 MW
    #user activity
    #pengecekan user login
    public function check_login_user(){
        $UserID = $this->session->UserID;
        if($UserID):
            $UserUnique = $this->session->UserUnique;
            $ck_user_unique = $this->db->count_all("user where id_user = '$UserID' and UserUnique = '$UserUnique'");
            if($ck_user_unique>0 || $this->session->hak_akses == "super_admin"):
                $data = array(
                    "LoginActivity" => date("Y-m-d H:i:s"),
                );
                $this->db->where("id_user", $UserID);
                $this->db->update("user", $data);
            else:
                $data = array("login" => false, "UserID" => "");
                $this->session->set_userdata($data);
                $this->session->set_flashdata('message', 'Your account has been used by another user');
                redirect(site_url('login'));
            endif;
        endif;
    }

    #20190730 MW
    #log
    #1= login, 2 = insert, 3 = update, 4 = delete / nonactive, 5 = error, 6 = logout
    public function insert_log($type,$page,$content){
        $this->load->library('user_agent');
        if ($this->agent->is_browser()):
            $agent = $this->agent->browser().' '.$this->agent->version();
        elseif ($this->agent->is_mobile()):
            $agent = $this->agent->mobile();
        else:
            $agent = 'Data user gagal di dapatkan';
        endif;

        $data_user = array(
            "browser"           => $agent,
            "sistem operasi"    => $this->agent->platform(),
            "IP"                => $this->input->ip_address(),
        );

        $data = array(
            "CompanyID" => $this->session->CompanyID,
            "UserID"    => $this->session->UserID,
            "Type"      => $type,
            "Page"      => $page,
            "Content"   => $content,
            "User"      => json_encode($data_user),
            "UserAdd"   => $this->session->nama,
            "DateAdd"   => date("Y-m-d H:i:s"),
        );
        $this->db->insert("UT_Log", $data);
    }

    #20190730 MW
    #pengecekan login user berdasarkan module
    private function UserStatus($CompanyID,$UserID){
        $status         = false;
        $date_now       = date("Y-m-d");
        $count_module   = 0;
        $max_user       = 0;
        $Module         = $this->main->get_module_company($CompanyID);
        
        if($Module->ar->status == 1 and $Module->ar->expire >= $date_now):
            $count_module += 1;
        endif;
        if($Module->ap->status == 1 and $Module->ap->expire >= $date_now):
            $count_module += 1;
        endif;
        if($Module->ac->status == 1 and $Module->ac->expire >= $date_now):
            $count_module += 1;
        endif;
        if($Module->inventory->status == 1 and $Module->inventory->expire >= $date_now):
            $count_module += 1;
        endif;

        if($count_module == 1):
            $max_user = 1;
        elseif($count_module == 2):
            $max_user = 3;
        elseif($count_module == 3):
            $max_user = 5;
        elseif($count_module == 4):
            $max_user = 7;
        endif;

        $ck_user = $this->db->count_all("user where id_user = '$UserID' and user.index <= '$max_user' and user.index is not null");
        if($ck_user>0):
            $status = true;
        endif;

        $message = "Maximum user login ".$max_user." for ".$count_module." Module";

        $data = array(
            "status"    => $status,
            "message"   => $message,
        );

        $data = json_encode($data);
        $data = json_decode($data);

        return $data;
    }

    #20190730 MW
    #voucher
    #digunakan untuk menggunakan voucher
    public function UseVoucher($Voucher,$UserID,$page){
        if($page == "additional"):
            $usedate    = date("Y-m-d H:i:s");
            $date_now   = date("Y-m-d");

            $date_user      = $this->get_one_column("user","VoucherExpireDate",array("id_user" => $UserID))->VoucherExpireDate;
            $list_voucher   = $this->get_list_voucher($Voucher,"detail",2);
            $month          = $list_voucher->voucherType;

            $ExpireDate     = date("Y-m-d", strtotime(date("Y-m-d")." +".$month." Month"));

            if($date_user >= $date_now):
                $UserExpire = date("Y-m-d", strtotime($date_user." +".$month." Month"));
            else:
                $UserExpire = date("Y-m-d", strtotime($date_now." +".$month." Month"));
            endif;

            $data_voucher = array(
                "Status"        => "used",
                "ExpireDate"    => $ExpireDate,
                "UsedID"        => $UserID,
                "UsedCompanyID" => $this->session->CompanyID,
                "UseDate"       => $usedate,
            );
            $where = array("VoucherDetailID"    => $list_voucher->VoucherDetailID);
            $this->db->set("UserCh",$this->session->NAMA);
            $this->db->set("DateCh",date("Y-m-d H:i:s"));
            $this->db->update("VoucherDetail", $data_voucher, $where);

            $data_user = array(
                "VoucherExpireDate" => $UserExpire,
                "user_ch"   => $this->session->NAMA,
                "date_ch"   => date("Y-m-d H:i:s"),
            );

            $this->db->where("id_user", $UserID);
            $this->db->update("user", $data_user);
        elseif($page == "module"):
            $Module     = $UserID;
            $usedate    = date("Y-m-d H:i:s");
            $date_now   = date("Y-m-d");
            if(in_array($Module, array('ap','ar','ac','inventory'))):
                $module_company = $this->main->get_module_company();

                $date_ap = $module_company->ap->expire;
                $date_ar = $module_company->ar->expire;
                $date_ac = $module_company->ac->expire;
                $date_inventory = $module_company->inventory->expire;
                $date_aset = $module_company->asset->expire;

                $status_ap = $module_company->ap->status;
                $status_ar = $module_company->ar->status;
                $status_ac = $module_company->ac->status;
                $status_inventory = $module_company->inventory->status;
                $status_asset = $module_company->asset->status;

                $list_voucher   = $this->get_list_voucher($Voucher,"detail",1);
                $month          = $list_voucher->voucherType;
                $ExpireDate     = date("Y-m-d", strtotime(date("Y-m-d")." +".$month." Month"));
                $ReturnDate     = '';

                if($Module == "ap"):
                    $status_ap = 1;
                    if($date_ap >= $date_now):
                        $date_ap = date("Y-m-d", strtotime($date_ap." +".$month." Month"));
                    else:
                        $date_ap = date("Y-m-d", strtotime($date_now." +".$month." Month"));
                    endif;
                    $ReturnDate = $date_ap;

                elseif($Module == "ar"):
                    $status_ar = 1;
                    if($date_ar >= $date_now):
                        $date_ar = date("Y-m-d", strtotime($date_ar." +".$month." Month"));
                    else:
                        $date_ar = date("Y-m-d", strtotime($date_now." +".$month." Month"));
                    endif;
                    $ReturnDate = $date_ar;

                elseif($Module == "ac"):
                    $status_ac = 1;
                    if($date_ac >= $date_now):
                        $date_ac = date("Y-m-d", strtotime($date_ac." +".$month." Month"));
                    else:
                        $date_ac = date("Y-m-d", strtotime($date_now." +".$month." Month"));
                    endif;
                    $ReturnDate = $date_ac;

                elseif($Module == "inventory"):
                    $status_inventory = 1;
                    if($date_inventory >= $date_now):
                        $date_inventory = date("Y-m-d", strtotime($date_inventory." +".$month." Month"));
                    else:
                        $date_inventory = date("Y-m-d", strtotime($date_now." +".$month." Month"));
                    endif;
                    $ReturnDate = $date_inventory;
                endif;

                // update status voucher
                $data_voucher = array(
                    "Status"        => "used",
                    "ExpireDate"    => $ExpireDate,
                    "UsedID"        => $this->session->id_user,
                    "UsedCompanyID" => $this->session->CompanyID,
                    "UseDate"       => $usedate,
                    "Module"        => $Module,
                );

                $where = array("VoucherDetailID"    => $list_voucher->VoucherDetailID);
                $this->db->set("UserCh",$this->session->NAMA);
                $this->db->set("DateCh",date("Y-m-d H:i:s"));
                $this->db->update("VoucherDetail", $data_voucher, $where);

                // update expire user
                $data['ar'] = array(
                    "status"    => $status_ar,
                    "expire"    => $date_ar,
                );

                $data['ap'] = array(
                    "status"    => $status_ap,
                    "expire"    => $date_ap,
                );

                $data['ac'] = array(
                    "status"    => $status_ac,
                    "expire"    => $date_ac,
                );

                $data['inventory'] = array(
                    "status"    => $status_inventory,
                    "expire"    => $date_inventory,
                );

                $data['asset'] = array(
                    "status"    => $status_asset,
                    "expire"    => $date_aset,
                );

                $m_company = json_encode($data);

                $data_company = array(
                    "Module"    => $m_company,
                    "user_ch"   => $this->session->NAMA,
                    "date_ch"   => date("Y-m-d H:i:s"),
                );
                $this->db->where("id_user", $this->session->CompanyID);
                $this->db->update("user", $data_company);
                $this->setting_parameter();

                return $ReturnDate;
            endif;
        endif;
    }

    #20190808 MW
    #delete temp serial number
    public function delete_temp_sn($page){
        $CompanyID  = $this->session->CompanyID;
        $UserID     = $this->session->UserID;

        if($CompanyID):
            $this->db->where("CompanyID", $CompanyID);
            $this->db->where("UserID", $UserID);
            $this->db->where("Page", $page);
            $this->db->delete("Temp_Serial_Number");
        endif;
    }

    #20190801 MW
    #form
    #untuk mengambil nama form yg menjadi array string
    #fungsi ini belum digunakan. kali aja nanti butuh
    public function form_string(){
        $form = $this->input->post("form");
        $form = json_decode($form);
        foreach ($form as $k => $v) {
            if(strlen($v->name)>1):
                $check_arr = substr($v->name, -2);
                if($check_arr == "[]"):
                    $nmnya = substr($v->name, 0,-2);
                    ${$nmnya}[]   = $v->value;
                else:
                    ${$v->name}     = $v->value;
                endif;
            else:
                ${$v->name} = $v->value;
            endif;
        }

        $this->echoJson($form);
    }

    #20190816 MW
    #average
    #menghitung average
    public function average($ProductID,$qty,$price,$BranchID=""){
        $CompanyID = $this->session->CompanyID;

        // average branch
        if($BranchID):
            $dt_average = $this->db->query("
                SELECT
                (
                    (
                        (case when ifnull(dt.Qty,0) <= 0 then 0 else ifnull(dt.Qty,0) end * ifnull(dt.AveragePrice,0))+
                        ('$qty'*'$price')
                    )/
                    (
                        (case when ifnull(dt.Qty,0) <= 0 then 0 else ifnull(dt.Qty,0) end + '$qty')
                    )
                ) as average
                from PS_Product_Branch dt join ps_product product on dt.ProductID = product.ProductID and dt.CompanyID = product.CompanyID
                where dt.CompanyID = '$CompanyID' and dt.ProductID = '$ProductID' and dt.BranchID = '$BranchID'
            ")->row();
            $average = $dt_average->average;
            $this->db->query("
                UPDATE PS_Product_Branch set AveragePrice = '$average' where CompanyID = '$CompanyID' and ProductID = '$ProductID' and 
                BranchID = '$BranchID'
            ");
        // average product company
        else:
            $dt_average = $this->db->query("
                SELECT 
                (
                    (
                        (case when ifnull(Qty,0) <= 0 then 0 else ifnull(Qty,0) end * ifnull(AveragePrice,0)) + 
                        ('$qty'*'$price')
                    ) / 
                    (
                        (case when ifnull(Qty,0) <= 0 then 0 else ifnull(Qty,0) end + '$qty')
                    )
                ) as average 
                from ps_product where CompanyID = '$CompanyID' and ProductID = '$ProductID'    
            ")->row();
            $average = $dt_average->average;

            $this->db->query("
                UPDATE ps_product set AveragePrice = '$average' where CompanyID = '$CompanyID' and ProductID = '$ProductID'
            ");
        endif;

        return $average;
    }

    #default setting parameter
    public function default_setting_parameter($type,$page){
        $val = '';
        if($type == "datasetting"):
            $DataSetting = $this->session->DataSetting;
            if($page == "StartDate"):
                if($DataSetting == "Days"): $val = date("Y-m-d");
                elseif($DataSetting == "Month"): $val = date("Y-m-01");
                elseif($DataSetting == "Year"): $val = date("Y-01-01");
                endif;
            elseif($page == "EndDate"):
                $val = date("Y-m-d");
            endif;
        endif;

        return $val;
    }

    public function hapus_gambar($table,$column,$where){
    error_reporting(0);
    ini_set('display_errors', 0);
    
    $this->db->select($column);
    $this->db->where($where);
    $query = $this->db->get($table)->row();
    $Image = site_url($query->$column);
    if(!empty($query->$column)):
        $root       = explode(base_url(), $Image)[1];
        $headers    = @get_headers($Image);
        if (preg_match("|200|", $headers[0])) {
            unlink('./' . $root);
        } 
    endif;
    }
    public function set_header_image($type2){
    $this->db->select("AttachmentID, Image, Name");
    $this->db->where("Type2",$type2);
    $query = $this->db->get("PS_Attachment");
    $data = $query->row(); 
    if($data){
        $Image = base_url($data->Image);
    } else {
        $Image = base_url("aset/img/default-slide.png");
    }
    return $Image;
    }
}
