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
        $this->load->language('bahasa',$this->session->bahasa);
    }
    public function set_($set){
        $data_set   = '';
        if(in_array($set, array("SiteTitle","Logo","Icon","TimeZone"))):
            $data_set   = $this->{$set};
        endif;
        $data       = $this->get_setting_val($set,"Code");
        if($data):
            if($data->Value != ''):
                if(in_array($set,array("Logo","Icon"))):
                    $data_set = site_url($data->Value);
                else:
                    $data_set = $data->Value;
                endif;
            endif;
        endif;
        if($set == "TimeZone"):
            date_default_timezone_set($data_set);
        endif;
        return $data_set;
    }
    public function get_setting_val($Search,$Type = ""){
        if($Type == "Code"):
            $this->db->where("Code",$Search);
        else:
            $this->db->where("GeneralID",$Search);
        endif;
        $query = $this->db->get("UT_General");
        return $query->row();
    }
    public function generate_timezone_list()
    {
        static $regions = array(
            DateTimeZone::AFRICA,
            DateTimeZone::AMERICA,
            DateTimeZone::ANTARCTICA,
            DateTimeZone::ASIA,
            DateTimeZone::ATLANTIC,
            DateTimeZone::AUSTRALIA,
            DateTimeZone::EUROPE,
            DateTimeZone::INDIAN,
            DateTimeZone::PACIFIC,
        );

        $timezones = array();
        foreach( $regions as $region )
        {
            $timezones = array_merge( $timezones, DateTimeZone::listIdentifiers( $region ) );
        }

        $timezone_offsets = array();
        foreach( $timezones as $timezone )
        {
            $tz = new DateTimeZone($timezone);
            $timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
        }

        // sort timezone by offset
        asort($timezone_offsets);

        $timezone_list = array();
        foreach( $timezone_offsets as $timezone => $offset )
        {
            $offset_prefix              = $offset < 0 ? '-' : '+';
            $offset_formatted           = gmdate( 'H:i', abs($offset) );
            $pretty_offset              = "UTC${offset_prefix}${offset_formatted}";
            // $timezone_list[$timezone]   = "(${pretty_offset}) $timezone";
            $timezone_list[] = array(
                'Name'  =>  "(${pretty_offset}) $timezone",
                'Value' => $timezone
            );
        }
        $timezone_list = json_encode($timezone_list);
        $timezone_list = json_decode($timezone_list);
        return $timezone_list;
    }
    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
    public function number_to_alphabet($number) {
        $number = intval($number);
        if ($number <= 0) {
            return '';
        }
        $alphabet = '';
        while($number != 0) {
            $p = ($number - 1) % 26;
            $number = intval(($number - $p) / 26);
            $alphabet = chr(65 + $p) . $alphabet;
        }
        return $alphabet;
    }
    public function checkLength($text){
        $length = strlen($text);
        if($length<1):
            $text = "-";
        endif;
        return $text;
    }
    public function hapus_gambar($table,$column,$where){
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
    public static function link($text){
      $text = preg_replace('~[^\pL\d]+~u', '-', $text);
      $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
      $text = preg_replace('~[^-\w]+~', '', $text);
      $text = trim($text, '-');
      $text = preg_replace('~-+~', '-', $text);
      $text = strtolower($text);
      if (empty($text)) {
        return 'n-a';
      }
      return $text;
    }
    public function type_file($fileName){
        $type   = substr($fileName, strpos($fileName,".")+1);
        $type   = strtolower($type);
        if($type == "png" || $type == "jpg" || $type == "jpeg"):
            $type = "image";
        endif;

        return $type;
    }

    public function convertDate($date,$format = "Y-m-d"){
        if($date != ''):
            $date = date($format, strtotime($date));
        else:
            $date = null;
        endif;
        return $date;
    }
    public function convertToMonth($date1,$date2){
        $tanggal1 = new DateTime($date1);
        $tanggal2 = new DateTime($date2);
         
        $perbedaan = $tanggal2->diff($tanggal1);

        return $perbedaan->m;
    }
    public function convertToRp($input){
        $data = '';
        if($input != ''):
            $rp = (float) $input;
            $rp = "RP ". number_format($rp,0,',',',');
            $data = $rp;
        endif;

        return $data;
    }
    public function label_hari($tanggal){
        $day = date('D', strtotime($tanggal));
        $dayList = array(
            'Sun' => 'Minggu',
            'Mon' => 'Senin',
            'Tue' => 'Selasa',
            'Wed' => 'Rabu',
            'Thu' => 'Kamis',
            'Fri' => 'Jumat',
            'Sat' => 'Sabtu'
        );

        $day    = $dayList[$day];

        return $day;
    }
    public function label_bulan($bulan){
        $bulanList = array(
                '01' => 'Januari',
                '02' => 'Februari',
                '03' => 'Maret',
                '04' => 'April',
                '05' => 'Mei',
                '06' => 'Juni',
                '07' => 'Juli',
                '08' => 'Agustus',
                '09' => 'September',
                '10' => 'Oktober',
                '11' => 'November',
                '12' => 'Desember',
        );

        return $bulanList[$bulan];
    }
    public function bahasa($bahasa){
        $this->session->set_userdata("bahasa",$bahasa);
        redirect($_SERVER['HTTP_REFERER']);
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
            if($tabel == "AP_GoodReceipt" || $tabel == "PS_Mutation" || $tabel == "PS_Correction" || $tabel == "AP_Retur" || $tabel == "AC_CorrectionPR"):
                $this->db->where("MONTH(date_add)",date("m"));
                $this->db->where("CompanyID",$this->session->CompanyID);
            elseif($tabel == "PS_Product_Serial"):
                $this->db->where("CompanyID",$this->session->CompanyID);
                $this->db->where("ProductID",$tambahan);
            else:

            endif;
        else:
        #ini untuk salespro
            if($tabel == "SP_TransactionRoute"):
                $this->db->where("MONTH(DateAdd)",date("m"));
                $this->db->where("CompanyID",$tambahan);
            endif;
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
        return $angka;
    }
    public function tanggal($format, $tanggal="now", $bahasa="en"){
        return $this->konversi_tanggal($format,$tanggal,$bahasa);
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
     if($this->session->bahasa == "indonesia"):
        $bahasa = "id";
     else:
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
    public function vendor_code_generate()
    {
        return $this->autoNumber("PS_Vendor","Code",5,date("ym"));
    }
    public function vendor_address_code_generate()
    {
        return $this->autoNumber("ps_vendor_address","Code",5,date("ym"));
    }
    public function vendor_phone_code_generate()
    {
        return $this->autoNumber("ps_vendor_contact","Code",5,date("ym"));
    }
    public function penerimaan_code_generate()
    {
        return $this->autoNumber("AP_GoodReceipt","ReceiveNo",5,"GR".date("ym"));
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
    public function correction_code_generate()
    {
        return $this->autoNumber("PS_Correction","CorrectionNo",5,"CS".date("ym"));
    }
    public function correctiondet_code_generate()
    {
        return $this->autoNumber("PS_Correction_Detail","CorrectionDet",5,"CSD".date("ym"));
    }
    public function returno_generate()
    {
        return $this->autoNumber("AP_Retur","ReturNo",5,"RT".date("ym"));
    }
    public function correctionar_generate()
    {
        return $this->autoNumber("AC_CorrectionPR","BalanceNo",5,"AC".date("ym"));
    }
    public function paymentno_generate()
    {
        return $this->autoNumber("PS_Payment","PaymentNo",5,"PS".date("ym"));
    }
    public function transaction_route_code_generate($CompanyID = "")
    {
        if($CompanyID == ""):
            $CompanyID = $this->session->CompanyID;
        endif;

        return $this->autoNumber("SP_TransactionRoute","Code",5,date("ym"), $CompanyID);
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
    public function menu($kategori)
    {
        $hk     = $this->hak_akses();
        $data   = json_decode($hk);
        $bahasa = $this->session->bahasa;
        if($bahasa == "indonesia"):
            $nama_menu = 'Name';
        else:
            $nama_menu = 'nama_menu';
        endif;

        $query  = $this->db->get("menu");
        $query  = $this->db->query('SELECT url,'.$nama_menu.' as nama_menu FROM menu WHERE kategori="'.$kategori.'" AND id_menu IN (' . implode(',', array_map('intval', $data)) . ') ORDER BY nama_menu ASC' );
        return $query->result();
    }
    public function GetMenuData($MenuID){
        $bahasa = $this->session->bahasa;
        if($bahasa == "indonesia"):
            $x = 'Name as Name';
        else:
            $x = 'nama_menu as Name';
        endif;

        $this->db->select("
            id_menu as MenuID,
            ".$x.",
            url as Url,
            kategori as Category,
            root as Root
        ");
        $this->db->where("id_menu",$MenuID);
        $query = $this->db->get("menu");
        return $query->row();
    }
    public function GetMenuID($url){
        return $this->id_menu($url);
    }
    public function GetMenuName($MenuID){
        $data = $this->GetMenuData($MenuID);
        if($data):
            $name = $data->Name;
        else:   
            $name = "";
        endif;
        return $name;
    }
    public function id_menu($url)
    {
        $this->db->select('id_menu');
        $this->db->from('menu');
        $this->db->like('url',$url);
        $this->db->or_like('root',$url);
        $id_menu = $this->db->get()->row()->id_menu;
        if(empty($id_menu)):
            $id_menu = 0;
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
            user.App,
            '0' as HeadGroup,
            user.status,
            user.StatusVerify      as StatusVerify,
            user.StatusVerifyEmail as StatusVerifyEmail,
            user.StatusVerifyPhone as StatusVerifyPhone,
            user.token             as Token,
            user.JoinDate          as JoinDate,
        ");
        $this->db->where("id_user",$id);
        $query = $this->db->get("user");
        $a          = $query->row();
        $modulapp   = "salespro";
        $app        = "salespro";
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
            'StatusVerify'  => $a->StatusVerify,
            'StatusAccount' => $a->StatusAccount,
            'ExpireAccount' => $a->ExpireAccount,
            'Token'         => $a->Token,
            'HeadGroup'     => $a->HeadGroup,
            'bahasa'        => 'english'
        );
        if($a->hak_akses == "super_admin" || $a->hak_akses == "company"):
            $data['companyid'] = $a->id_user;
            $data['CompanyID'] = $a->id_user;
        else:
            $data['companyid'] = $a->companyid;
            $data['CompanyID'] = $a->companyid;
        endif;
        $this->session->set_userdata($data);
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
            user.App,
            '0' as HeadGroup,
            user.status,
            user.StatusVerify      as StatusVerify,
            user.StatusVerifyEmail as StatusVerifyEmail,
            user.StatusVerifyPhone as StatusVerifyPhone,
            user.token             as Token
        ");
        // $this->db->where("user.App",'all');
        // $this->db->where("user.status",1);
        $this->db->where("user.email",$email);
        if($page == "konfirmasi_akun"):

        else:
           $this->db->where("password",$password);
        endif;
        $this->db->where_in("hak_akses",array("super_admin","company","branch"));
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
            $this->set_session_user($a->id_user);
            $this->setting_parameter();
            $AlertVerification = $this->AlertVerification();
            $data["status"]   = TRUE;
            $data["message"]  = "Login Success";
            if(in_array($a->StatusVerify, array(1,2))):
                if($a->status == 1 && $AlertVerification == 2 || $a->status == 0 && $a->StatusVerify == 2):
                    $data["redirect"] = site_url("verification-account");
                else:
                    $data["redirect"] = site_url("dashboard");
                endif;
            else:
                $data["redirect"] = site_url("verification-account");
            endif;
        else:
            $data["popup"]    = TRUE;
            $data["status"]   = FALSE;
            $data["message"]  = $this->lang->line("v_login_fail")." your account not found";
        endif;
        return $data;
    }
    public function logout()
    {
        session_destroy();
        redirect(site_url("login"));
    }
    public function register($from = ""){
        if($from == "android"):
            $validasi   = $this->validation->register_android();
        else:
            $validasi   = $this->validation->validasi_register("member");
        endif;
        #-----------------------------------------------------
        $PhoneNumber="";
        $status     = FALSE;
        $message    = "";
        $CompanyID  = "";
        // $nama_toko  = $this->input->post("nama_toko");
        $nama       = $this->input->post("nama_perusahaan");
        $no_hp      = $this->input->post("no_hp");
        $PhoneCode  = $this->input->post("PhoneCode");
        $TokenFireB = $this->input->post("tokenFirebase");
        $imei       = $this->input->post("imei");

        if($PhoneCode == ""):
            $PhoneCode = "62";
        endif;
        $email      = $this->input->post("email");
        $password   = $this->input->post("password");
        $no_hp      = $this->PhoneFormat($no_hp);
        $password   = $this->hash($password);
        $kode_user  = "";
        $PhoneNumber= $PhoneCode.$no_hp;
        // $kode_user  = $this->user_code_generate();
        #case when SUBSTRING(phone, 1, 1) = 0 then SUBSTRING(phone,-(LENGTH(phone) - 1),(LENGTH(phone) - 1)) 
        $VerificationNumber       = $this->random_number(4);
        $VerificationNumberExpire = date("Y-m-d H:i:s",strtotime("+1 hour"));
        $data       = array(
            "token"         => $this->token_encode($email),
            "title"         => "MR",
            "nama"          => $nama,
            "email"         => $email,
            "password"      => $password,
            "hak_akses"     => "company",
            "App"           => "salespro",
            "StatusAccount" => "trial",
            "ExpireAccount" => date("Y-m-d",strtotime("+30 days")),
            "VerificationNumber"       => $VerificationNumber,
            "VerificationNumberExpire" => $VerificationNumberExpire,
            "JoinDate"                 => date("Y-m-d H:i:s"),
            "jenis_kelamin"            => "male",
            "status"                   => 0,
            "phone"                    => $no_hp,
            "PhoneCode"                => $PhoneCode,
            "user_add"                 => $nama,
            "date_add"                 => date("Y-m-d H:i:s")
        );
        $this->db->insert("user",$data);
        $CompanyID = $this->db->insert_id();
        $this->setting_insert($CompanyID);

        $PhoneNumber                = $PhoneCode.$this->PhoneFormat($no_hp);
        $VerificationNumberExpire   = $this->tanggal("d M Y H:i",$VerificationNumberExpire);
        $msg                        = "Kode verifikasi anda adalah ".$VerificationNumber.", berlaku sampai ".$VerificationNumberExpire.". Mohon tidak memberikan kode verifikasi kepada siapapun.";

        if($from == "android"):
            $Name       = $nama;
            $FirstName  = explode(" ", $Name)[0];
            $LastName   = str_replace($FirstName, "", $Name);
            $data_branch = array(
                "CompanyID"     => $CompanyID,
                "UserCode"      => $kode_user,
                "Name"          => $nama,
                "User_Add"      => $nama,
                "Date_Add"      => date("Y-m-d H:i:s"),
                "App"           => "salespro",
                "FirstName"     => $FirstName,
                "LastName"      => $LastName,
                "Email"         => $email,
                "Phone"         => $no_hp,
                "PhoneCode"     => $PhoneCode,
                "Password"      => $password,
                "StatusAccount" => "trial",
                "ExpireAccount" => date("Y-m-d",strtotime("+30 days")),
                "Active"        => 1,
            );
            $this->branch_insert($kode_user,$data_branch);
            $BranchID = $this->db->insert_id();
            $this->checkFirebase($BranchID, $TokenFireB, $imei);
            #kirim emailnya dari aplikasi android di android sales pro
            $kirim_sms   = $this->send_sms($PhoneNumber,$msg);
            // $kirim_email = $this->send_email("register",$CompanyID);
            $message     = "Register a new account successfully, please check your email or phone number to verify your account";
            $status      = TRUE;
        else:
            $kirim_sms   = $this->send_sms($PhoneNumber,$msg);
            $kirim_email = $this->send_email("register",$CompanyID);

            $this->set_session_user($CompanyID);
            $status   = TRUE;
            $message  = $this->lang->line('note_register');
        endif;

        #ini send sms
        $respon = array(
            "status"        => $status,
            "message"       => $message,
            "CompanyID"     => $CompanyID,
            "PhoneNumber"   => $PhoneNumber,
            "img_logo"      => site_url("img/rc.png"),
            "redirect"      => site_url("verification-account"),
            ""
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
            "NegativeStock" => "allow"
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
            $data['error_string'][] = $this->lang->line('v_email_empty');
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
        $respon["message"] = $this->lang->line('note_forgot_password');
        $respon["redirect"] = site_url("main/tes_email/forgot_password/".$email);
        return $respon;
    }
    public function reset_password(){
        $respon["status"]   = FALSE;
        $respon["message"]  = "Please Try Again";
        $id_user            = $this->input->post("id_user");
        $id_user            = $this->token_decode($id_user);
        $password           = $this->input->post("password");
        $password_kon       = $this->input->post("password_kon");
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
            $respon["message"] = $this->lang->line("v_password_reset");
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
                $respon["message"]  = $this->lang->line("v_verificationcode_empty");
            elseif($VerificationNumber != $data->VerificationNumber):
                $respon["message"]  = $this->lang->line("v_verificationcode_wrong");
            elseif(time() > strtotime($data->VerificationNumberExpire)):
                $respon["message"]  = $this->lang->line("v_verificationcode_expire");
            elseif($VerificationNumber && time() < strtotime($data->VerificationNumberExpire)  && $VerificationNumber == $data->VerificationNumber):
                $this->db->set("StatusVerify",1);
                $this->db->set("status",1);
                $this->db->where("id_user",$UserID);
                $this->db->update("user");
                $respon["status"]   = TRUE;
                if($page == "android"):
                    $respon["message"]  = $this->lang->line("v_verificationcode_success");
                else:
                    $this->session->set_userdata("StatusVerify",1);
                    $respon["redirect"] = site_url();
                    $respon["message"]  = $this->lang->line("v_verificationcode_success");
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
        $VerificationNumberExpire = date("Y-m-d H:i:s",strtotime("+1 hour"));
        $this->main->user_update($a->id_user,array(
            "VerificationNumber"       => $VerificationNumber,
            "VerificationNumberExpire" => $VerificationNumberExpire,
        ));
        $status     = FALSE;
        if($Modul == "email"):
            $kirim = $this->main->send_email("verification_account",$a->id_user);
            if($kirim):
                $status     = TRUE;
                $message    = $this->lang->line("v_sendnew_verificationcode")." : ".$a->Email; 
            else:
                $message    = $this->lang->line("v_sendnew_verificationcode_fail")." : ".$a->Email; 
            endif;
        else:
            $VerificationNumberExpire = $this->main->tanggal("d M Y H:i",$VerificationNumberExpire);
            // $msg = $VerificationNumber." adalah Kode Verifikasi Anda. gunakan sebelum ".$VerificationNumberExpire." - People Shape Sales";
            $msg   = "Kode verifikasi anda adalah ".$VerificationNumber.", berlaku sampai ".$VerificationNumberExpire.". Mohon tidak memberikan kode verifikasi kepada siapapun.";
            $kirim = $this->main->send_sms($PhoneNumber,$msg);
            if($kirim):
                $status     = TRUE;
                $message    = $this->lang->line("v_sendnew_verificationcode2")." : ".$a->PhoneNumber; 
            else:
                $message    = $this->lang->line("v_sendnew_verificationcode_fail2")." : ".$a->PhoneNumber; 
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
        $this->validation->ChangeVerificationValidation();
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
            $message = $this->lang->line("v_xchange_resend_vercode1");
        else:
            $data["PhoneCode"] = $PhoneCode;
            $data["phone"] = $Phone;
            $message = $this->lang->line("v_xchange_resend_vercode2");
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
            $UPDATE = $this->db->update("Branch",$datax);
        endif;
        if($UPDATE):
            $respon = $this->SendVerificationCode("change_verification");
            $UPDATE = $respon["status"];
            if($UPDATE):
                $status = TRUE;
                if($Modul == "email"):
                    $message = $this->lang->line("v_achange_resend_vercode1")." ".$respon["message"];
                else:
                    $message = $this->lang->line("v_achange_resend_vercode2")." ".$respon["message"];
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
    public function send_email($page,$code,$cek_page = ""){
        $lampiran   = FALSE;
        $nama       = "";
        $bcc        = array();
        if($page == "forgot_password"):
            $a                  = $this->user_detail($code);
            $nama               = $a->nama;
            $email              = $a->email;
            $token              = $a->token;
            $subject            = "Forgot Password";
            $page_email         = "email/index";
            $data["message"]    = "untuk melakukan reset password, silakan <a href='".site_url("reset-password?t=".$token."&#reset")."' target='_blank'>Klik Disini</a> ";
            $data["page"]       = "email/forgot_password"; 
        elseif($page == "register"):
            $a                  = $this->user_detail($code);
            $nama               = $a->nama;
            $email              = $a->email;
            $token              = $a->token;
            $VerificationNumber = $a->VerificationNumber;
            $VerificationNumberExpire = $a->VerificationNumberExpire;
            $url_konfirmasi     = site_url(urlencode("konfirmasi-akun?t=".$token));
            $subject            = "Verifikasi Akun";
            $page_email         = "email/index";
            $data["modul"]      = $page;
            $data["url"]        = $url_konfirmasi;
            $data["page"]       = "email/register";
            $data["VerificationNumber"] = $VerificationNumber;
            $data["VerificationNumberExpire"] = $VerificationNumberExpire;
            $bcc                = array('ricky@rcelectronic.co.id','luna@rcelectronic.net');
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
            // $bcc                = array('ricky@rcelectronic.co.id','luna@rcelectronic.net');
            // $bcc                = array('luna@rcelectronic.net');
            $data["data"]       = $a;
            $data["modul"]      = "buy_voucher";
            $subject            = "Transaction Purchase Voucher " . $a->Code;
            $page_email         = "email/index";
            $data["page"]       = "email/buy_voucher"; 
        elseif($page == "acc_voucher"):
            $a                  = $this->voucher_detail($code);
            $nama               = $a->Name;
            $email              = $a->Email;
            $data["data"]       = $a;
            $data["modul"]      = "acc_voucher";
            $subject            = "Thank you for buying voucher " . $a->Code;
            $page_email         = "email/index";
            $data["page"]       = "email/acc_voucher";
        endif;
        $data["nama"] = $nama;
        if($cek_page == "ya"):
            echo "<pre>";
            print_r($page_email);
            print_r($data);
            echo "</pre>";
            $this->load->view($page_email,$data);
            // exit();
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


            #smtp host = mail.peopleshape.id
            #user = info@peopleshape.id
            #pass = 12345!
            // $protocol  = 'smtp';
            // $smtp_host = 'mail.peopleshape.id';
            // $smtp_port = '587';
            // $smtp_user = 'info@peopleshape.id';
            // $smtp_pass = '12345!';
            // $email     = 'luna@rcelectronic.net';


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
            $this->email->subject($subject);
            $this->email->from("info@peopleshape.id",$title);
            if(count($bcc)>0):
                $this->email->bcc($bcc);
            endif;
            $this->email->to($email);
            $this->email->message($this->load->view($page_email, $data,TRUE));
            if($lampiran):
                if($voucher):
                    $this->email->attach($voucher);
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
                    redirect();
                else:
                    redirect("verification-account");
                endif;
            endif;
        else:
            if(!$this->session->login):
                redirect("logout");
            elseif($this->session->StatusVerify == 0 || $this->AlertVerification() == 2):
                redirect("verification-account");
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
            email as Email,
            token,
            PhoneCode as PhoneCode,
            phone as Phone,
            VerificationNumber,
            VerificationNumberExpire,
            JoinDate,
            StatusVerify,
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
            Voucher.TotalPrice,
            Voucher.TrxUnique,
            Voucher.Bank,
            Voucher.ExpirePurchase,
            Voucher.Status,
            user.nama   as Name,
            user.email  as Email,
            (case 
            when Voucher.App ='pipesys' then 'Pipesys'
            when Voucher.App ='salespro' then 'SalesPro'
            else 'Pipesys & Salespro' end) as App,
            (case 
            when Voucher.Type = 24 THEN '2 Year'
            when Voucher.Type = 12 THEN '1 Year'
            when Voucher.Type = 6 THEN '6 Month'
            when Voucher.Type = 3 THEN '3 Month'
            when Voucher.Type = 1 THEN '1 Month' else 'none' end)   as Type
        ");
        $this->db->join("user","Voucher.CompanyID = user.id_user","left");
        // $this->db->where("Voucher.CompanyID",$this->session->CompanyID);
        $this->db->where("VoucherID",$VoucherID);
        $query = $this->db->get("Voucher");
        return $query->row();
    }
    public function voucher_detail_list($VoucherID){
        $this->db->select("Code");
        // $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->where("App","salespro");
        $this->db->where("VoucherID",$VoucherID);
        $query = $this->db->get("VoucherDetail");
        return $query->result();
    }
    #list data dan list option
    public function category()
    {
        $position = $this->input->post("level");
        $this->db->select("productid as categoryid,UPPER(name) as category_name,code as category_code");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->where("Active",1);
        if($position > 1):
            $position = $position - 1;
            $this->db->where("position",$position);
        else:
            $this->db->where("position !=",0);
        endif;
        $this->db->order_by("name","ASC");
        $query = $this->db->get("ps_product");
        return $query->result();
    }
    public function unit()
    {
        $page   = $this->input->post("page");
        $unitid = $this->input->post("unitid");
        $this->db->select("unitid,name as unit_name,conversion,type");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->where("Active",1);
        
        if($page == "select"):
            $this->db->where("unitid",$unitid);
        endif;
        $this->db->order_by("type,name","ASC");
        $query = $this->db->get("ps_unit");
        if($page == "select"):
            $data = $query->row();
        else:
            $data = $query->result();
        endif;
        return $data;
    }
    public function product($page = "",$search = "")
    {
        $this->db->select("
            ps_product.productid    as productid,
            ps_product.code         as product_code,
            ps_product.unitid       as unitid,
            ps_product.name         as product_name,
            ps_product.type         as product_type,
            ps_product.SNFormat     as serial_format,
            ps_product.minimumstock as min_qty,
            ps_product.qty          as qty,
            ps_product.sellingprice as sellingprice,
            LCASE(category.name)    as category_name,
            unit.name               as unit_name,
            unit.conversion         as conversion,
        ");

        $this->db->join("ps_product as category","ps_product.parentcode = category.code");
        $this->db->join("ps_unit as unit","ps_product.unitid = unit.unitid","left");
        $this->db->where("ps_product.companyid",$this->session->companyid);
        $this->db->where("category.companyid",$this->session->companyid);
        $this->db->where("ps_product.position",0);
        $this->db->where("ps_product.active",1);
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
    public function product_serial($page = "",$search = "",$productid="")
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
            $this->db->like("pps.SerialNo",$search);
        elseif($page == "add_serial_mutasi"):
            $this->db->where("pps.ProductID",$search);
        else:
            $this->db->where("ReceiveDet",$search);
        endif;
        $query  = $this->db->get("PS_Product_Serial as pps");
        $data   = $query->result();
        return $data;
    }
    public function vendor($page = "",$search = ""){
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
        ");
        
        if($this->session->app == "pipesys"):
            $this->db->where("Position",1);
        elseif($this->session->app == "salespro"):
            $this->db->where("App",$this->session->app);
        endif;

        $this->db->where("CompanyID",$this->session->companyid);
        $this->db->where("Active",1);
        if($page == "autocomplete"):
            $this->db->like("psv.name",$search);
            $this->db->limit(15);
        endif;
        $query = $this->db->get("PS_Vendor as psv");
        $data = $query->result();    
        return $data;
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
        $ParentID = $this->getParentID();
        $this->db->select("
            b.BranchID as branchid,
            b.CompanyID as companyid,
            b.Name as name,
            b.Code as code,
            b.Lat as lat,
            b.Lng as lng,
        ");
        $this->db->where("b.App",$this->session->app);
        if($active == 1):
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
            FirstName   as first_name,
            LastName    as last_name,
            Email       as email,
            Phone       as phone,
        ");
        $this->db->where("BranchID", $BranchID);
        $query = $this->db->get("Branch");

        return $query->row();
    }

    public function sell($page = "",$search = "",$date = ""){
        $this->db->select("
            sell.SellNo     as sellno,
            sell.Total      as total,
            sell.Payment    as payment,
            sell.Paid       as paid,
            sell.Paid       as status,
            sell.Date       as date,
            pv.VendorID     as vendorid,
            pv.Name         as vendorname,
        ");
        $this->db->join("PS_Vendor pv","sell.VendorID = pv.VendorID","left");
        $this->db->where("sell.CompanyID",$this->session->CompanyID);
        if($page == "autocomplete"):
            $this->db->like("sell.Sellno",$search);
            $this->db->limit(15);
        elseif($page == "branch"):
            $this->db->where("sell.BranchID",$search);
            $this->db->where("sell.Paid",0);
        elseif($page == "customer"):
            $this->db->where("sell.VendorID",$search);
            $this->db->where("sell.Paid",0);
        endif;
        if($date){
            $this->db->where("DATE(sell.Date) <=",date("Y-m-d",strtotime($date)));
        }
        $this->db->order_by("DATE(sell.Date)","ASC");
        $query  = $this->db->get("PS_Sell as sell");
        if($page == "count"):
            return $query->num_rows();
        else:
            $data   = $query->result();    
            return $data;
        endif;    
    }
    public function sell_detail($page = "",$search = ""){
        $this->db->select("
            sell.SellDet    as selldet,
            sell.SellNo     as sellno,
            sell.ProductID  as productid,
            p.Code          as product_code,
            p.Name          as product_name,
            p.SellingPrice  as sellprice,
        ");
        $this->db->join("ps_product as p","sell.ProductID = p.ProductID","left");
        $this->db->where("sell.CompanyID",$this->session->CompanyID);
        if($page == "autocomplete"):
            $this->db->like("sell.Sellno",$search);
            $this->db->limit(15);
        elseif($page == "branch"):
            $this->db->where("BranchID",$search);
        elseif($page == "sell" || $page == "retur"):
            $this->db->where("SellNo",$search);
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
    #receive 
    public function receive($page = "")
    {
        $CompanyID = $this->session->CompanyID;
        $this->db->select("
            gr.ReceiveNo    as receiveno,
            gr.ReceiveName  as receivename,
            gr.Date         as date,

            gr.VendorID     as vendorid,
            v.Name          as vendorname
        ");
        $this->db->join("PS_Vendor as v","gr.VendorID = v.VendorID","left");
        $this->db->where("gr.CompanyID",$CompanyID);
        $this->db->order_by("gr.receiveno","DESC");
        $query = $this->db->get("AP_GoodReceipt as gr");
        return $query->result();
    }
    public function receive_detail()
    {
        $CompanyID  = $this->session->CompanyID;
        $page       = $this->input->post("page"); #return
        $receiveno  = $this->input->post("receiveno");
        $vendorid   = $this->input->post("vendorid");
        $this->db->select("
            gd.ReceiveDet       as receive_det,
            gd.ReceiveNo        as receive_no,
            gd.ProductID        as productid,
            gd.Qty              as product_qty,
            gd.Conversion       as product_konv,
            gd.Price            as product_price,
            gd.SubTotal         as product_subtotal,
            gd.UnitID           as unitid,
            ps_product.Code     as product_code,
            ps_product.Name     as product_name,
            (CASE
            WHEN ps_product.type = 1 THEN 'unique'
            WHEN ps_product.type = 2 THEN 'serial'
            ELSE 'general' END) AS product_type,

            ps_unit.Name as unit_name,
        ");
        $this->db->join("ps_product","gd.ProductID = ps_product.ProductID","left");
        $this->db->join("ps_unit","gd.UnitID = ps_unit.UnitID","left");
        $this->db->where("gd.CompanyID",$CompanyID);
        $this->db->where("gd.ReceiveNo",$receiveno);
        $query = $this->db->get("AP_GoodReceipt_Det as gd");
        return $query->result();
    }
    #------------------------------------------------------------------------------------------------------------------------------
    public function company($page = "")
    {   
        $this->db->select("*,user.nama as Name, user.phone as Phone");
        $this->db->where("id_user",$this->session->companyid);
        $this->db->join("SettingParameter as sp","user.id_user = sp.CompanyID",'left');
        $query  = $this->db->get("user");
        $data   = $query->row();
        return $data;
    }
    public function company_logo()
    {
        $this->db->select("(CASE WHEN img_bin IS NOT NULL THEN CONCAT('$this->host/img/logo/',img_bin) ELSE  '$this->host/img/noimage.png' END) as photo");
        $this->db->where("CompanyID",$this->session->companyid);
        $this->db->or_where("id_user",$this->session->companyid);
        $query  = $this->db->get("user");
        $data   = $query->row();
        return $data->photo;   
    }
    #qty penerimaan dan mutasi
    public function penerimaan_qty($page ="",$productid="",$qty=""){
        if($qty > 0):
            if($page == "done"):
            $this->db->query("UPDATE ps_product set Qty=Qty+$qty WHERE productid='$productid' ");
            elseif($page == "cancel"):
            $this->db->query("UPDATE ps_product set Qty=Qty-$qty WHERE productid='$productid' ");
            endif;
        endif;
    }
    public function mutasi_qty($page ="",$type="",$BranchID ="",$productid="",$qty=""){
        $CompanyID = $this->session->CompanyID;
        if($qty > 0):
            if($page == "to"):
                $this->db->query("UPDATE PS_Product_Branch set Qty=Qty+$qty WHERE CompanyID='$CompanyID' AND BranchID='$BranchID' AND ProductID='$productid' ");
            elseif($page == "from"):
                if($type == 1):
                    $this->db->query("UPDATE PS_Product_Branch set Qty=Qty-$qty WHERE CompanyID='$CompanyID' AND BranchID='$BranchID' AND ProductID='$productid' ");
                else:
                    $this->db->query("UPDATE ps_product set Qty=Qty-$qty WHERE CompanyID='$CompanyID' AND ProductID='$productid' ");
                endif;
            endif;
        endif;
    }
    public function retur_qty($page ="",$productid="",$qty="")
    {
        $CompanyID = $this->session->CompanyID;
        if($qty > 0):
            if($page == "done"):
            $this->db->query("UPDATE ps_product set Qty=Qty-$qty WHERE productid='$productid' ");
            elseif($page == "cancel"):
            $this->db->query("UPDATE ps_product set Qty=Qty+$qty WHERE productid='$productid' ");
            endif;
        endif;
    }
    #validasi -------------------------------------------------------------------------------------
    private function validasi_login()
    {
        $email          = $this->input->post("email");
        $StatusAccount  = "";
        $email_confirm  = "";
        $password       = "";
        $hak_akses      = "";
        $status         = 0;
        $HeadGroup      = 0;
        $StatusVerify   = 0;
        $confirm_password = $this->input->post("password");
        $this->db->select("email,password,status,StatusAccount,hak_akses, '' as HeadGroup,StatusVerify");
        $this->db->where("email",$email);
        $query  = $this->db->get("user");
        $row    = $query->row();

        if(count($row) == 0):
            $this->db->select("Email as email,Password as password,Active as status,StatusAccount, 'sales' as hak_akses, HeadGroup, '0' as StatusVerify");
            $this->db->where("Email",$email);
            $query  = $this->db->get("Branch");
            $row    = $query->row();
        endif;
        if($row):
            $email_confirm  = $row->email;
            $password       = $row->password;
            $status         = $row->status;
            $StatusAccount  = $row->StatusAccount;
            $HeadGroup      = $row->HeadGroup;
            $hak_akses      = $row->hak_akses;
            $StatusVerify   = $row->StatusVerify;
        endif;
        $confirm_password = $this->hash($confirm_password);
        $data = array();
        $data['error_string']   = array();
        $data['inputerror']     = array();
        $data['status']         = TRUE;
        if($this->input->post('email') == '')
        {
            $data['inputerror'][] = 'email';
            $data['error_string'][] = $this->lang->line('v_email_empty');
            $data['status'] = FALSE;
        }
        if($email_confirm == '')
        {
            $data['inputerror'][] = 'email';
            $data['error_string'][] = $this->lang->line('v_email_not_registered');
            $data['status'] = FALSE;
        }

        if($this->input->post('password') == '')
        {
            $data['inputerror'][] = 'password';
            $data['error_string'][] = $this->lang->line('v_password_empty');
            $data['status'] = FALSE;
        }

        if($email_confirm != "" && $password != $confirm_password){
            $data['inputerror'][]   = 'password';
            $data['error_string'][] = $this->lang->line('v_password_wrong');
            $data['status'] = FALSE;
        }
        if($StatusAccount != "trial" && $status == 0 && $StatusVerify != 2)
        {
            $data["popup"]    = TRUE;
            $data["status"]   = FALSE;
            $data["message"]  = $this->lang->line('v_account_deadactive');
        }
        if($hak_akses == "sales" && $HeadGroup == 0):
            $data["popup"]    = TRUE;
            $data["status"]   = FALSE;
            $data["message"]  = $this->lang->line('v_account_not_headgroup');
        endif;
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }
    public function PhoneFormat($phone){
        if(substr($phone, 0,1) == 0):
            $phone = substr($phone, -(strlen($phone) - 1), (strlen($phone) - 1));
        endif;
        return $phone;
    }
    
    public function validasi_password($password){
        $passwordErr = '';
        if (strlen($password) < 8) {
            $passwordErr = $this->lang->line("v_password1");
        }
        elseif(!preg_match("#[0-9]+#",$password)) {
            $passwordErr = $this->lang->line("v_password2");
        }
        elseif(!preg_match("#[A-Z]+#",$password)) {
            $passwordErr = $this->lang->line("v_password3");
        }
        elseif(!preg_match("#[a-z]+#",$password)) {
            $passwordErr = $this->lang->line("v_password4");
        }
        return $passwordErr;
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
                "Currency"      => $a->Currency,
                "AmountDecimal" => $a->AmountDecimal,
                "QtyDecimal"    => $a->QtyDecimal,
                "NegativeStock" => $a->NegativeStock,
            );
        else:
            $data = array(
                "currency"      => "IDR",
                "amountdecimal" => "0",
                "qtydecimal"    => "0",
                "negativestock" => "allow",
                "Currency"      => "IDR",
                "AmountDecimal" => "0",
                "QtyDecimal"    => "0",
                "NegativeStock" => "allow",
            );
        endif;
        $this->session->set_userdata($data);        
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
        $query = $this->db->get("PS_Sell as sell");
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
    public function qty($qty)
    {
        $qtydecimal  = $this->session->qtydecimal;
        $qty         = number_format($qty,$qtydecimal,".",",");
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

    public function sp_list_sales($page = "")
    {
        if($this->session->hak_akses == "sales" && $this->session->HeadGroup == 1):
            $arrayID = $this->group_employe_id();
        endif;

        $this->db->select("BranchID as ID,Name as Name");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->where("App","salespro");
        if($this->session->hak_akses == "sales" && $this->session->HeadGroup == 1):
            $this->db->where_in("BranchID",$arrayID);
        endif;
        $this->db->order_by("Branch.Name","ASC");
        $query = $this->db->get("Branch");
        if($page == "count"):
            return $query->num_rows();
        else:
            return $query->result();
        endif;
    }
    public function head_group_list($page = "")
    {
        $this->db->select("BranchID as ID,Name as Name");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->where("App","salespro");
        $this->db->where("HeadGroup",1);
        $this->db->order_by("Branch.Name","ASC");
        $query = $this->db->get("Branch");
        if($page == "count"):
            return $query->num_rows();
        else:
            return $query->result();
        endif;
    }
    public function sp_total_route_transaction($page = "",$arrayID = "")
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
            if($this->session->hak_akses == "sales" && $this->session->HeadGroup == 1):
                $this->db->where_in("SP_TransactionRoute.BranchID",$arrayID);
            endif;
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
            if($this->session->hak_akses == "sales" && $this->session->HeadGroup == 1):
                $this->db->where_in("SP_TransactionRoute.BranchID",$arrayID);
            endif;
            $query = $this->db->get("SP_TransactionRouteDetail");
        endif;
        return $query->num_rows();
    }    
    public function sp_sales_location($arrayID = "",$modul = ""){
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
        if($modul == "last_position_employee"):
            $this->db->where("Branch.Active",1);
            $this->db->where("Branch.Lat !=","");
            $this->db->where("Branch.Lng !=","");
        else:
            $this->db->where("date(CheckTime)",date("Y-m-d"));
        endif;
        $this->db->group_start();
        $this->db->where("CompanyID",$this->session->companyid);
        if($this->session->hak_akses == "sales" && $this->session->HeadGroup == 1):
            $this->db->where_in("Branch.BranchID",$arrayID);
        else:
            if($this->session->hak_akses == "sales" && $this->session->HeadGroup == 1):
                $this->db->where_in("Branch.BranchID",$arrayID);
            else:
                $this->db->or_where("BranchID",$this->session->companyid);    
            endif;
        endif;
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
    public function voucher_package($App,$Type,$Qty)
    {
        $price = 0.00;
        if($Qty < 1):
            $Qty = 1;
        endif;

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
        $this->db->where("App",$App);
        $this->db->where("Type",$Type);
        $query  = $this->db->get("VoucherPackage");
        $a      = $query->row();
        return $a;
    }
    #20180427 MW
    #broadcast transaction today
    public function broadcastToday(){
        if($this->session->hak_akses == "sales" && $this->session->HeadGroup == 1):
            $arrayID = $this->main->group_employe_id();
        endif;
        $this->db->select("
            BranchID,
        ");
        $this->db->where("Date", date("Y-m-d"));
        $this->db->where("Active", 1);
        $this->db->where("CompanyID", 1);
        if($this->session->hak_akses == "sales" && $this->session->HeadGroup == 1):
            $this->db->where_in("SP_TransactionRoute.BranchID",$arrayID);
        endif;
        $this->db->group_by("BranchID");
        $query = $this->db->get("SP_TransactionRoute");
        return $query;
    }
    //2018-05-14 MW
    // get branch selain comany sendiri
    public function getBranch($Email, $CompanyID = "none",$x = ""){
        $this->db->select("user.nama");
        if($x == "phone"):
            $this->db->where("Branch.Phone",$Email);
        else:
            $this->db->where("Branch.Email", $Email);
        endif;
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
        if($domain == "http://qa.peopleshape.rcelectronic.co.id" || $domain = "https://peopleshape.rcelectronic.co.id"):
            $title = "People Shape Sales";
        else:
            $title = "PipeSys";
        endif;
        return $title;
    }
    //2018-05-21 MW
    //ParentID jadikan sebagai session
    public function countParentID(){
        if($this->session->hak_akses != "sales"):
            $CompanyID  = $this->session->CompanyID;
            $count      = $this->db->count_all("user where ParentID = '$CompanyID'");
            $data = array(
                "ParentID" => $count,
            );
            $this->session->set_userdata($data);
        endif;
    }
    //2018-05-21 MW
    //Company Parent List
    public function sp_list_company(){
        $this->db->select("id_user,nama");
        $this->db->where("ParentID", $this->session->CompanyID);
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

    public function sp_top_sales($arrayID = ""){
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
        if($this->session->hak_akses == "sales" && $this->session->HeadGroup == 1):
            $this->db->where_in("sp_t.BranchID",$arrayID);
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

    public function dashboard($page="",$arrayID = ""){
        $StartDate  = $this->input->post("StartDate");
        $EndDate    = $this->input->post("EndDate");

        if(!$StartDate):
            $StartDate = date("Y-m-01");
            $EndDate   = date("Y-m-d");
        endif;

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
                    $data_visiting_hour = $this->sales_visiting_hour($date,"none",$arrayID)->row();
                    $data_comparison    = $this->comparison_sales_visiting_hour($date,$arrayID);
                    array_push($data["sales_visiting_hour"], $data_visiting_hour);
                    array_push($data["comparison"], $data_comparison);
                endif;
            }
        else:
            $begin  = new DateTime($StartDate);
            $end    = new DateTime($EndDate);

            $interval   = DateInterval::createFromDateString('1 day');
            $period     = new DatePeriod($begin, $interval, $end);

            $data["total_route"] = array();
            $data["total_hour"]  = array();
            foreach ($period as $key => $dt) {
                $date       = $dt->format("Y-m-d");
                $data_route = $this->total_route($date,$arrayID);
                $data_hour  = $this->total_hour($date,$arrayID);
                array_push($data["total_route"], $data_route);
                array_push($data["total_hour"], $data_hour);
            }
            $data_route = $this->total_route($EndDate,$arrayID);
            $data_hour  = $this->total_hour($EndDate,$arrayID);
            array_push($data["total_route"], $data_route);
            array_push($data["total_hour"], $data_hour);
        endif;

        return $data;
    }
    public function total_route($date,$arrayID){
        $this->db->select("
            IFNULL(Year(sp_t.Date), Year('$date')) as year,
            IFNULL(Month(sp_t.Date), Month('$date')) as month,
            IFNULL(Day(sp_t.Date), Day('$date')) as day,
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
        if(count($arrayID)):
            $this->db->where_in("sp_t.BranchID",$arrayID);
        endif;
        $query = $this->db->get("SP_TransactionRoute as sp_t");
        return $query->row();
    }

    public function total_hour($date,$arrayID){
        $this->db->select("
            IFNULL(sum(TIME_TO_SEC(timediff(sp_td.CheckOut,sp_td.CheckIn))), 0) as total,
            IFNULL(Year(sp_t.Date), Year('$date')) as year,
            IFNULL(Month(sp_t.Date), Month('$date')) as month,
            IFNULL(Day(sp_t.Date), Day('$date'))as day,
        ");
        $this->db->join("SP_TransactionRouteDetail as sp_td", "sp_t.TransactionRouteID = sp_td.TransactionRouteID", "left");
        $this->db->where("sp_t.Date", $date);
        $this->db->where("sp_t.CompanyID", $this->session->CompanyID);
        if(count($arrayID)):
            $this->db->where_in("sp_t.BranchID",$arrayID);
        endif;
        $query = $this->db->get("SP_TransactionRoute as sp_t");

        return $query->row();
    }

    public function sales_visiting_hour($date, $page="",$arrayID = ""){
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
        if($CompanyID):
            $this->db->where("sp_t.CompanyID", $CompanyID);
        else:
            $this->db->where("sp_t.CompanyID", $this->session->CompanyID);
        endif;
        if($this->session->hak_akses == "sales" && $this->session->HeadGroup == 1):
            $this->db->where_in("sp_t.BranchID",$arrayID);
        endif;
        $this->db->order_by("sp_td.CheckIn");
        $this->db->where("sp_t.BranchID", $BranchID);
        $query = $this->db->get("SP_TransactionRoute as sp_t");

        return $query;
    }

    public function comparison_sales_visiting_hour($date,$arrayID = ""){
        $query = $this->sales_visiting_hour($date, "comparison",$arrayID);
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
    public function cek_expire_voucher(){
        $VoucherIDArray = array();
        $this->db->select("VoucherID,ExpirePurchase");
        $this->db->where("status","proccess");
        $this->db->where("StatusTransfer","proccess");
        $this->db->where("ExpirePurchase <",date("Y-m-d"));
        $query = $this->db->get("Voucher");
        $data  = $query->result();
        foreach($data as $a):
            $ExpirePurchase = $a->ExpirePurchase;
            $ExpirePurchaseAuto = date("Y-m-d",strtotime("+2 days",strtotime($a->ExpirePurchase)));
            if($ExpirePurchaseAuto < date("Y-m-d")):
                // $VoucherIDArray[] = $ExpirePurchase." - ".$ExpirePurchaseAuto;
                $VoucherIDArray[] = $a->VoucherID;
            endif;
        endforeach;
        if(count($VoucherIDArray) > 0):
            $this->db->where_in("VoucherID",$VoucherIDArray);
            $this->db->set("status","expire");
            $this->db->update("Voucher");
        endif;
    }
    public function group_employe_id(){
        $arrayID = array($this->session->branchid);
        $this->db->select("GroupID,MemberID");
        $this->db->where("HeadID",$this->session->id_user);
        $this->db->where("Active",1);
        $query = $this->db->get("Group");
        foreach($query->result() as $a):
            $MemberID = json_decode($a->MemberID);
            foreach($MemberID as $b):
                if(!in_array($b, $arrayID)):
                  $arrayID[] = $b;
                endif;
            endforeach;
        endforeach;
        return $arrayID;
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
    public function TokenAndroidCheck($Token){
        $status = FALSE;
        $this->db->where("Code","TokenAndroid");
        $this->db->where("Value",$Token);
        $query = $this->db->get("UT_Main");
        if($query->num_rows()):
            $status = TRUE;
        endif;
        return $status;
    }
    public function ListSalesExpire(){
        $current_date = date("Y-m-d");
        foreach(array(0,1,2,3,7) as $a):
            $this->db->select("
                BranchID,
                Name,
                Email,
                PhoneCode,
                Phone,
                (case when LENGTH(phone) > 0 and SUBSTRING(phone, 1, 1) = 0 then concat('+62',SUBSTRING(phone,-(LENGTH(phone) - 1),(LENGTH(phone) - 1))) 
            when LENGTH(phone) > 0 then concat('+',PhoneCode,phone) else '' end)as PhoneNumber,
                ExpireAccount,
                DATEDIFF(date(ExpireAccount), '$current_date') AS Selisih,
                '$a' as Tipe,

            ");
            $this->db->where("CompanyID",$this->session->CompanyID);
            $this->db->where("ExpireAccount !=","");
            $this->db->where("Active",1);
            if($a == 0):
                $this->db->having("Selisih <",1);
            else: 
                $this->db->having("Selisih",$a);
            endif;
            ${"query$a"} = $this->db->get_compiled_select("Branch",FALSE);
            $this->db->reset_query();
        endforeach;
        $query = $this->db->query("$query0 UNION $query1 UNION $query2 UNION $query3 UNION $query7 order by ExpireAccount asc");
        return $query->result();
    }
    public function checkFirebase($BranchID, $token, $imei){
        $cek = $this->db->count_all("FirebaseUser where ID = '$BranchID'");
        if($cek>0):
            $data = array(
                "Token"     => $token,
                "Imei"      => $imei,
                "UserCh"    => $this->sales_pro->user_name($BranchID),
                "DateCh"    => date("Y-m-d H:i:s"),
                );
            $this->updateFirebase($data,$BranchID);
        else:
            $data = array(
                "ID"        => $BranchID,
                "Token"     => $token,
                "App"       => "salespro",
                "Imei"      => $imei,
                "UserAdd"   => $this->sales_pro->user_name($BranchID),
                "DateAdd"   => date("Y-m-d H:i:s"),
                );
            $this->insertFirebase($data);
        endif;
    }

    private function insertFirebase($data){
        $this->db->insert("FirebaseUser", $data);
    }
    private function updateFirebase($data,$BranchID){
        $this->db->where("ID", $BranchID);
        $this->db->update("FirebaseUser", $data);
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
}