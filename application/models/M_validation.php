<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#tanggal 2018-04-19
#author m iqbal ramadhan
class M_validation extends CI_Model {
    public function __construct()
    {
        parent::__construct();
    }
    public function TokenAndroidCheck(){
        // $status = FALSE;
        // $Token  = $this->input->post("TokenAndroid");
        // $this->db->where("Code","TokenAndroid");
        // $this->db->where("Value",$Token);
        // $query = $this->db->get("UT_Main");
        // if($query->num_rows() > 0):
        //     $status = TRUE;
        // endif;
        // if($status == FALSE):
        //     $res["status"]      = FALSE;
        //     $res["res_code"]    = 0;
        //     $res["message"]     = "Maaf perangkat android anda tidak mempunyai izin akses, silakan update ke versi terbaru";
        //     echo json_encode($res);
        //     exit();
        // endif;
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
    public function validasi_register($page = "")
    {
        $bahasa     = $this->session->bahasa;
        $email      = $this->input->post('email');
        $phone      = $this->input->post('no_hp');
        $phone      = $this->main->PhoneFormat($phone);
        $cek_email  = $this->db->count_all("user where email='$email'");
        $cek_phone  = $this->db->count_all("user where phone='$phone' ");
        $cek_branch_email = $this->db->count_all("Branch where Email = '$email' AND Active = '1'");
        $cek_branch_phone = $this->db->count_all("Branch where Phone = '$phone' AND Active = '1'");
        $data       = array();
        $data['error_string']   = array();
        $data['inputerror']     = array();
        $data['status']         = TRUE;
        // if($page == "member" && $this->input->post('nama_toko') == '')
        // {
        //     $data['inputerror'][]   = 'nama_toko';
        //     $data['error_string'][] = 'Please fill out full name1';
        //     $data['status']         = FALSE;
        // }
        if($page == "member" && $this->input->post('nama_perusahaan') == ''){
            $data['inputerror'][]   = 'nama_perusahaan';
            $data['error_string'][] = 'Please fill out full name1';
            $data['status']         = FALSE;
        }
        if($page == "member" && $this->input->post('no_hp') == ''){
            $data['inputerror'][]   = 'no_hp';
            $data['error_string'][] = $this->lang->line('v_phone_empty');
            $data['status']         = FALSE;

        }
        if($page == "agen" && $this->input->post('nama') == ''){
            $data['inputerror'][]   = 'nama';
            $data['error_string'][] = $this->lang->line('v_name_empty');
            $data['status']         = FALSE;
        }
        if($page == "agen" && $this->input->post('telepon') == ''){
            $data['inputerror'][]   = 'telepon';
            $data['error_string'][] = $this->lang->line('v_phone_empty');
            $data['status']         = FALSE;
        }
        if (strpos($email, '@') == false){
            $data['inputerror'][]   = 'email';
            $data['error_string'][] = $this->lang->line('v_email_format');
            $data['status']         = FALSE;
        }
        if($this->input->post('email') == ''){
            $data['inputerror'][]   = 'email';
            $data['error_string'][] = $this->lang->line('v_email_empty');
            $data['status']         = FALSE;
        }
        if($cek_phone > 0){
            $data['inputerror'][]   = 'Phone';
            $data['error_string'][] = $this->lang->line('v_phone_already');
            $data['status']         = FALSE;
        }
        if($cek_email > 0){
            $data['inputerror'][]   = 'email';
            $data['error_string'][] = $this->lang->line('v_email_already');
            $data['status']         = FALSE;
        }
        if($cek_branch_email > 0) {
            $query = $this->main->getBranch($email, "branch");
            $comanyName = "";
            foreach($query->result() as $d):
                $comanyName .= $d->nama.", ";
            endforeach;
            $data['inputerror'][]   = 'email';
            if($bahasa == "indonesia"):
            $data['error_string'][] = 'Alamat email sudah digunakan oleh pegawai di perusahaan '.$comanyName.", silakan kontak administrator perusahaan untuk menonaktifkan alamat email";
            else:
            $data['error_string'][] = 'Email address has been taken as employee in  '.$comanyName." Please contact the company administrator to deactivate email address";
            endif;
            $data['status']         = FALSE;
        }
        if($cek_branch_phone > 0) {
            $query = $this->main->getBranch($phone, "branch","phone");
            $comanyName = "";
            foreach($query->result() as $d):
                $comanyName .= $d->nama.", ";
            endforeach;
            $data['inputerror'][]   = 'Phone';
            if($bahasa == "indonesia"):
            $data['error_string'][] = 'Nomor telepon sudah digunakan oleh pegawai di perusahaan '.$comanyName.", silakan kontak administrator perusahaan untuk menonaktifkan nomor telepon";
            else:
            $data['error_string'][] = 'Phone number has been taken as employee in  '.$comanyName." Please contact the company administrator to deactivate phone number";
            endif;
            $data['status']         = FALSE;
        }
        $validasi_password = $this->validasi_password($this->input->post("password"));
        if($this->input->post("password") != "" && $this->input->post("password") != "********" && $validasi_password != ""){
            $data['inputerror'][]   = 'password';
            $data['error_string'][] = $validasi_password;
            $data['status']         = FALSE;
        }
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }
    public function register_android(){
        $name             = $this->input->post("nama_perusahaan");
        $email            = $this->input->post('email');
        $phone            = $this->input->post('no_hp');
        $phone            = $this->main->PhoneFormat($phone);
        $password         = $this->input->post("password");
        $cek_email        = $this->db->count_all("user where email='$email'");
        $cek_phone        = $this->db->count_all("user where phone='$phone' ");
        $cek_branch_email = $this->db->count_all("Branch where Email = '$email' AND Active = '1'");
        $cek_branch_phone = $this->db->count_all("Branch where Phone = '$phone' AND Active = '1'");
        $res            = array();
        $res['status']  = TRUE;
        if($name == ""):
            $res["status"]  = FALSE;
            $res["message"] = $this->lang->line('v_company_name_empty');
        elseif($email == ""):
            $res["status"]  = FALSE;
            $res["message"] = $this->lang->line('v_email_empty');
        elseif(strpos($email, '@') == false):
            $data['message'] = $this->lang->line('v_email_format');
            $data['status']  = FALSE;
        elseif($phone == ""):
            $res["status"]  = FALSE;
            $res["message"] = $this->lang->line('v_phone_empty');
        elseif($password == ""):
            $res["status"]  = FALSE;
            $res["message"] = $this->lang->line('v_password_empty');
        elseif($cek_phone > 0):
            $res['message'] = $this->lang->line('v_phone_already');
            $res['status']  = FALSE;
        elseif($cek_email > 0):
            $res['message'] = $this->lang->line('v_email_already');
            $res['status']  = FALSE;
        elseif($cek_branch_phone > 0):
            $query      = $this->main->getBranch($phone, "branch","phone");
            $comanyName = "";
            foreach($query->result() as $d):
                $comanyName .= $d->nama.", ";
            endforeach;
            $data['inputerror'][]   = 'Phone';
            $data['error_string'][] = 'Phone number has been taken as employee in  '.$comanyName." Please contact the company administrator to deactivate phone number";
            $data['status']         = FALSE;
        elseif($cek_branch_email > 0):
            $query      = $this->main->getBranch($email, "branch");
            $comanyName = "";
            $a          = $query->row();
            if($a):
                $comanyName .= $a->nama.", ";
            endif;
            $res['message'] = 'Email address has been taken as employee in '.$comanyName." Please contact the company administrator to deactivate email address";
            $res['status']  = FALSE;
        endif;
        if($res['status'] === FALSE):
            echo json_encode($res);
            exit();
        else:
            return true;
        endif;
    }
    public function login_android(){
        $this->TokenAndroidCheck();
        $CompanyID  = $this->input->post("CompanyID");
        $DeviceID   = $this->input->post("DeviceID");
        $Email      = $this->input->post("Email");
        $Password   = $this->input->post("Password");
        $token      = $this->input->post("tokenFirebase");
        $imei       = $this->input->post("imei");
        $Activation = $this->input->post("Activation");
        $Password   = $this->main->hash($Password);
        $Passwordx  = "";
        $data       = array(
            "CompanyID" => $CompanyID,
            "Email"     => $Email,
        );
        $query    = $this->sales_pro->Branch($data);
        $employee = FALSE;
        $Active   = 0;
        if($query->num_rows()>0):
            $d          = $query->row();
            $Active     = $d->Active;
            $Passwordx  = $d->Password;
            $employee   = TRUE;
        endif;

        $res        = array();
        $res['status']  = TRUE;
        if($Email == ""):
            $res["status"]  = FALSE;
            $res["message"] = $this->lang->line('v_email_empty');
        elseif($Password == ""):
            $res["status"]  = FALSE;
            $res["message"] = $this->lang->line('v_password_empty');
        elseif($this->db->count_all("user where id_user = '$CompanyID' ") == 0):
            $res["status"]  = FALSE;
            $res["message"] = $this->lang->line('v_company_not_registered');
        elseif($employee == FALSE):
            $res["status"]  = FALSE;
            $res["message"] = $this->lang->line('v_employee_404');
        elseif($Password != $Passwordx):
            $res["status"]  = FALSE;
            $res["message"] = $this->lang->line('v_password_wrong');
        elseif($Active == 0):
            $res["status"]  = FALSE;
            $res["message"] = $this->lang->line('v_account_deadactive');
        elseif($this->db->count_all("user where id_user = '$CompanyID' AND email = '$Email' ") > 0):
            if($this->db->count_all("user where id_user = '$CompanyID' AND status = '1' and email = '$Email' ") == 0):
                $a = $this->main->user_detail($CompanyID);
                $res["PhoneNumber"] = str_replace("+", "", $a->PhoneNumber);
                $res["res_code"]    = 2; 
                $res["status"]      = FALSE;
                $res["message"]     = $this->lang->line('v_account_deadactive_cek');
            endif;
        elseif($this->db->count_all("user where id_user = '$CompanyID' and status = '1' ") == 0):
            $res["status"]  = FALSE;
            $res["message"] = $this->lang->line('v_account_deadactive_cek');
        endif;


        if($res['status'] === FALSE):
            if(!isset($res["res_code"])):
                $res["res_code"] = 0;
            endif;
            echo json_encode($res);
            exit();
        endif;
    }
    public function LoginToken(){
        $this->TokenAndroidCheck();
    }
    public function NewCustomerValidation(){
        $CompanyID  = $this->input->post("CompanyID");
        $DeviceID   = $this->input->post("DeviceID");
        $BranchID   = $this->input->post("BranchID");
        $Name       = $this->input->post("Name");
        $Phone      = $this->input->post("Phone");
        $Email      = $this->input->post("Email");
        $Address    = $this->input->post("Address");
        $LatLng     = $this->input->post("LatLng");
        $Lat        = $this->input->post("Lat");
        $Lng        = $this->input->post("Lng");
        $data = array();
        $data['error_string']   = array();
        $data['inputerror']     = array();
        $data['status']         = TRUE;
        if($Name == '')
        {
            $data['inputerror'][]   = 'Name';
            $data['error_string'][] = 'Please fill out Customer Name';
            $data['status']         = FALSE;
        }
        if($Phone == ''){
            $data['inputerror'][]   = 'Phone';
            $data['error_string'][] = 'Please fill out Phone';
            $data['status']         = FALSE;
        }
        if($Address == '')
        {
            $data['inputerror'][] = 'Address';
            $data['error_string'][] = 'Please fill out Address';
            $data['status'] = FALSE;
        }
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }
    public function VerificationAccountAndroid(){
        $this->TokenAndroidCheck();
        $CompanyID  = $this->input->post("CompanyID");
        $Email      = $this->input->post("Email");
        $Password   = $this->input->post("Password");
        $Password   = $this->main->hash($Password);
        $res        = array();
        $res['status']  = TRUE;
        if($Email == ""):
            $res["status"]  = FALSE;
            $res["message"] = "Please fill out email address";
        elseif($Password == ""):
            $res["status"]  = FALSE;
            $res["message"] = "Please fill out password";
        elseif($this->db->count_all("user where id_user = '$CompanyID' ") == 0):
            $res["status"]  = FALSE;
            $res["message"] = "Your company account not registered";
        // elseif($this->db->count_all("user where id_user = '$CompanyID' and email = '$Email' and password = '$Password' ") == 0):
        //     $res["status"]  = FALSE;
        //     $res["message"] = "Failed to login web service";
        endif;
        if($res['status'] === FALSE):
            if(!isset($res["res_code"])):
                $res["res_code"] = 0;
            endif;
            echo json_encode($res);
            exit();
        endif;
    }
    public function ChangeVerificationValidation(){
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
                $message = $this->lang->line("v_email_empty");
            elseif(strpos($Email, '@') == false):
                $status  = FALSE;
                $message = $this->lang->line("v_email_format");
            elseif($cek_email > 0):
                $status  = FALSE;
                $message = $this->lang->line("v_email_already");; 
            endif;
        else:
            $cek_phone   = $this->db->count_all("user where phone='$Phone' ");
            // $cek_branch_phone = $this->db->count_all("Branch where Phone = '$Phone' AND Active = '1'");
            if($Phone == ""):
                $status  = FALSE;
                $message = $this->lang->line("v_phone_empty");;
            elseif($cek_phone > 0):
                $status  = FALSE;
                $message = $this->lang->line("v_email_already");;
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
    public function SendNewVerificationNumber(){
        $this->validation->TokenAndroidCheck();
        $this->TokenAndroidCheck();
        $UserID     = $this->input->post("UserID");
        $CompanyID  = $this->input->post("CompanyID");
        $Modul      = $this->input->post("Modul");
        $Email      = $this->input->post("Email");
        $Password   = $this->input->post("Password");
        $Password   = $this->input->post("Password");
        $res        = array();
        $res['status']  = TRUE;
        if($UserID == ""):
            $res["status"]  = FALSE;
            $res["message"] = "Your Company ID not found";
        elseif($Email == ""):
            $res["status"]  = FALSE;
            $res["message"] = "Please fill out email address";
        elseif($Modul != "email" && $Modul != "phone" || $Modul == ""):
            $res["status"]  = FALSE;
            $res["message"] = "Please fill out modul send verification number";
        elseif($Password == ""):
            $res["status"]  = FALSE;
            $res["message"] = "Please fill out password";
        elseif($this->db->count_all("user where id_user = '$CompanyID' ") == 0):
            $res["status"]  = FALSE;
            $res["message"] = "Your company account not registered";
        elseif($this->db->count_all("user where id_user = '$CompanyID' and email = '$Email' and password = '$Password' ")):
            $res["status"]  = FALSE;
            $res["message"] = "Failed to login web service";
        endif;
        if($res['status'] === FALSE):
            if(!isset($res["res_code"])):
                $res["res_code"] = 0;
            endif;
            echo json_encode($res);
            exit();
        endif;
    }
    public function Vendor($page="")
    {
        $data                   = array();
        $data['error_string']   = array();
        $data['inputerror']     = array();
        $data['status']         = TRUE;
        if($this->input->post('name') == '')
        {
            $data['inputerror'][]   = 'name';
            $data['error_string'][] = $this->lang->line("v_customer_name");
            $data['status']         = FALSE;
        }
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }
    public function Branch($modul = "")
    {   
        $bahasa   = $this->session->bahasa;
        $EmailOld = "";
        $PhoneOld = "";
        if($modul == "update"):
            $a        = $this->branch->get_by_id($this->input->post("BranchID"));
            $EmailOld = $a->Email;
            $PhoneOld = $a->Phone;
        endif;
        $CompanyID  = $this->session->CompanyID;
        $crud       = $this->input->post('crud');
        $Phone      = $this->input->post('Phone');
        $Email      = $this->input->post('Email');
        $password   = 0; 
        if($crud == "insert"):
            $password = 1;
        else:
            
        endif;
        $data                   = array();
        $data['error_string']   = array();
        $data['inputerror']     = array();
        $data['status']         = TRUE;
        if($this->input->post('Name') == '')
        {
            $data['inputerror'][]   = 'Name';
            $data['error_string'][] = $this->lang->line("v_fullname");
            $data['status']         = FALSE;
        }
        if($this->input->post('Email') == '')
        {
            $data['inputerror'][]   = 'Email';
            $data['error_string'][] = $this->lang->line("v_email_empty");
            $data['status']         = FALSE;
        }
        if($this->input->post('Phone') == '')
        {
            $data['inputerror'][]   = 'Phone';
            $data['error_string'][] = $this->lang->line("v_phone_empty");
            $data['status']         = FALSE;
        }
        if($modul == "save" && $this->input->post('Password') == '')
        {
            $data['inputerror'][]   = 'Password';
            $data['error_string'][] = $this->lang->line("v_password_empty");
            $data['status']         = FALSE;
        }
        $app = $this->session->app;
        $cek_email = $this->db->count_all("Branch left join user on Branch.Email = user.email where Branch.Email='$Email' ");
        $cek_phone = $this->db->count_all("Branch left join user on Branch.Phone = user.phone where Branch.Phone='$Phone' ");
        if($modul == "save" &&  $cek_email > 0 ||
            $modul == "update" && $cek_email > 0 && $Email != $EmailOld
        )
        {
            $data['inputerror'][]   = 'Email';
            $data['error_string'][] = $this->lang->line("v_email_already");
            $data['status']         = FALSE;
        }
        if($modul == "save" &&  $cek_phone > 0 ||
            $modul == "update" && $cek_phone > 0 && $Phone != $PhoneOld
        )
        {
            $data['inputerror'][]   = 'Phone';
            $data['error_string'][] = $this->lang->line("v_phone_already");
            $data['status']         = FALSE;
        }
        if($modul == "save" &&  $cek_email > 0 ||
            $modul == "update" && $cek_email > 0 && $Email != $EmailOld) 
        {
            $query = $this->main->getBranch($Email);
            $comanyName = "";
            foreach ($query->result() as $d) {
                $comanyName .= $d->nama.", ";
            }
            $data['inputerror'][]   = 'Email';
            if($bahasa == "indonesia"):
            $data['error_string'][] = 'Alamat email sudah digunakan oleh pegawai di perusahaan '.$comanyName.", silakan kontak administrator perusahaan untuk menonaktifkan alamat email";
            else:
            $data['error_string'][] = 'Email address has been taken in '.$comanyName." Please contact the company administrator to deactivate email address";
            endif;
            $data['status']         = FALSE;
        }
        if($modul == "save" &&  $cek_phone > 0 ||
            $modul == "update" && $cek_phone > 0 && $Phone != $PhoneOld)
        {
            $query = $this->main->getBranch($Phone,"branch","phone");
            $comanyName = "";
            foreach ($query->result() as $d) {
                $comanyName .= $d->nama.", ";
            }
            $data['inputerror'][]   = 'Phone';
            if($bahasa == "indonesia"):
            $data['error_string'][] = 'Nomor telepon sudah digunakan oleh pegawai di perusahaan '.$comanyName.", silakan kontak administrator perusahaan untuk menonaktifkan nomor telepon";
            else:
            $data['error_string'][] = 'Phone number has been taken in '.$comanyName." Please contact the company administrator to deactivate phone number";
            endif;
            $data['status']         = FALSE;
        }

        if($data['status'] === FALSE){
            echo json_encode($data);
            exit();
        }
    }
    public function BranchVoucer(){
        $data                   = array();
        $data['error_string']   = array();
        $data['inputerror']     = array();
        $data['status']         = TRUE;
        if($this->input->post('VoucherCode') == ''):
            $data['inputerror'][]   = 'VoucherCode';
            $data['error_string'][] = $this->lang->line("v_insert_voucher");
            $data['status']         = FALSE;
        endif;
        if($data['status'] === FALSE){
            echo json_encode($data);
            exit();
        }
    }
    public function GroupEmployee($page = "")
    {
        $data                   = array();
        $data['error_string']   = array();
        $data['inputerror']     = array();
        $data['status']         = TRUE;
        if($this->input->post('Name') == '')
        {
            $data['inputerror'][]   = 'Name';
            $data['error_string'][] = $this->lang->line("v_group_name");
            $data['type'][]         = '';
            $data['status']         = FALSE;
        }
        if($this->input->post('HeadID') == 0)
        {
            $data['inputerror'][]   = 'HeadID';
            $data['error_string'][] = $this->lang->line("v_headgroup");
            $data['type'][]         = 'select2';
            $data['status']         = FALSE;
        }
        if(count($this->input->post('MemberID')) == 0)
        {
            $data['inputerror'][]   = 'MemberID';
            $data['error_string'][] = $this->lang->line("v_membergroup");
            $data['type'][]         = 'select2';
            $data['status']         = FALSE;
        }
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }
    public function Voucher($page = "")
    {
        $data                   = array();
        $data['error_string']   = array();
        $data['inputerror']     = array();
        $data['status']         = TRUE;
        if($page == "buy"){
            if($this->input->post('Type') == 'none')
            {
                $data['inputerror'][]   = 'Type';
                $data['error_string'][] = $this->lang->line("v_select_voucher_package");
                $data['status']         = FALSE;
            }
            if($this->input->post('Bank') == 'none')
            {
                $data['inputerror'][]   = 'Bank';
                $data['error_string'][] = $this->lang->line("v_select_bank");
                $data['status']         = FALSE;
            }
            if($this->input->post('Qty') == 'none')
            {
                $data['inputerror'][]   = 'Qty';
                $data['error_string'][] = $this->lang->line("v_select_qty");
                $data['status']         = FALSE;
            }
        } elseif($page == "confirmation"){
            if($this->input->post('TransferDate') == '')
            {
                $data['inputerror'][]   = 'TransferDate';
                $data['error_string'][] = $this->lang->line("v_transfer_date_empty");
                $data['status']         = FALSE;
            }
            if($this->input->post('AccountBank') == 'none')
            {
                $data['inputerror'][]   = 'AccountBank';
                $data['error_string'][] = $this->lang->line("v_select_bank");
                $data['status']         = FALSE;
            }
            if($this->input->post('AccountName') == '')
            {
                $data['inputerror'][]   = 'AccountName';
                $data['error_string'][] = $this->lang->line("v_account_bank_empty");
                $data['status']         = FALSE;
            }
            if($this->input->post('AccountNumber') == '')
            {
                $data['inputerror'][]   = 'AccountNumber';
                $data['error_string'][] = $this->lang->line("v_account_number_empty");
                $data['status']         = FALSE;
            }
            if($this->input->post('TransferAmount') < 1)
            {
                $data['inputerror'][]   = 'TransferAmount';
                $data['error_string'][] = $this->lang->line("v_transfer_amount");
                $data['status']         = FALSE;
            }
        }
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }
    public function BroadcastMessage(){
        $data                   = array();
        $data['error_string']   = array();
        $data['inputerror']     = array();
        $data['status']         = TRUE;
        
        if($this->input->post('Subject') == '')
        {
            $data['inputerror'][]   = 'Subject';
            $data['error_string'][] = $this->lang->line("v_subject_empty");
            $data['status']         = FALSE;
        }

        if($this->input->post('Message') == '')
        {
            $data['inputerror'][]   = 'Message';
            $data['error_string'][] = $this->lang->line("v_message_empty");
            $data['status']         = FALSE;
        }

        if(!$this->input->post('Sales'))
        {
            if($this->input->post('Select_sales') == ''){
                $data['inputerror'][]   = 'Select_sales';
                $data['error_string'][] = $this->lang->line("lb_chosse_employee_name");
                $data['status']         = FALSE;
            }
        }
        
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }
    public function TransactionRoute($page="")
    {
        $data                   = array();
        $data['error_string']   = array();
        $data['inputerror']     = array();
        $data['status']         = TRUE;
        if($this->input->post('Name') == 0)
        {
            $data['inputerror'][]   = 'Name';
            $data['error_string'][] = $this->lang->line("lb_chosse_employee_name");
            $data['status']         = FALSE;
        }
        if($this->input->post('Date') == '')
        {
            $data['inputerror'][]   = 'Date';
            $data['error_string'][] = $this->lang->line("v_date_empty");
            $data['status']         = FALSE;
        }
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }
}
