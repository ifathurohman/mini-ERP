<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('upload'); 

    }
    public function login()
    {
        $data = $this->main->login("api");
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    public function register()
    {
        $data = $this->main->register("api");
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    public function forgot_password()
    {
        $data = $this->main->forgot_password("api");
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    public function reset_password()
    {
        $data = $this->main->reset_password("api");
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    public function verification_account()
    {
        $data = $this->main->VerificationAccount("api");
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    public function send_verification_code($page = ""){
        $output = $this->main->SendVerificationCode();
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);   
    }
    public function change_verification(){
       $output = $this->main->ChangeVerification();
       header('Content-Type: application/json');
       echo json_encode($output,JSON_PRETTY_PRINT);      
    }



    #list_data
    public function category()
    {
        $data = $this->main->category("api");
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    public function unit()
    {
        $data = $this->main->unit("api");
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    public function product()
    {
        $data = $this->main->product("api");
        $list_data = array();
        foreach($data as $a):
            $item = array(
                "productid"         => $a->productid,
                "product_code"      => $a->product_code,
                "unitid"            => $a->unitid,
                "product_name"      => $a->product_name,
                "product_type"      => $a->product_type,
                "serial_format"     => $a->serial_format,
                "min_qty"           => $this->main->qty($a->min_qty),
                "qty"               => $this->main->qty($a->qty),
                "sellingprice"      => $a->sellingprice,
                "category_name"     => $a->category_name,
                "unit_name"         => $a->unit_name,
                "conversion"        => $a->conversion
            );
            array_push($list_data,$item);
        endforeach;
        $data = array("list_product" => $list_data);
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    public function company()
    {
        if($this->session->hak_akses == "sales"):
            $data = $this->main->branch_by_id($this->session->branchid);
        else:
            $data = $this->main->company("api");
        endif;

        $data = array(
            "data" => $data,
            "hak_akses" => $this->session->hak_akses,
        );
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    public function vendor()
    {
        $data = $this->main->vendor("api");
        $data = array(
            "app" => $this->session->app,
            "list_data" => $data);
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    public function customer()
    {
        $data = $this->main->customer("api");
        $data = array("list_data" => $data);
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    public function branch()
    {
        $data = $this->main->branch("api");
        $data = array("list_data" => $data);
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    public function sell($page = "",$search = "")
    {
        $data = $this->main->sell($page,$search);
        $list_data = $data;
        $output = array(
            "status"    => TRUE,
            "message"   => "",
            "hakakses"  => $this->session->hak_akses,
            "list_data" => $list_data,
        );

        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }
    public function sell_detail($page = "",$search = "")
    {
        $data = $this->main->sell_detail($page,$search);
        $list_data = array();
        foreach($data as $a):
            $item = array(
                "selldet"       => $a->selldet,
                "sellno"        => $a->sellno,
                "productid"     => $a->productid,
                "product_code"  => $a->product_code,
                "product_name"  => $a->product_name,
                "sellprice"     => $a->sellprice
            );
            array_push($list_data,$item);
        endforeach;
        $output = array(
            "status"    => TRUE,
            "message"   => "",
            "hakakses"  => $this->session->hak_akses,
            "list_data" => $list_data,
        );

        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }
    
    public function company_save($page = "")
    {
        $this->company_validation($page);
        $data = array();
        if($page == "company"):
           $this->main->upload_validation('photo','image');
            $nama                       = str_replace(" ", "_", $this->input->post("nama"));
            $nmfile                     = "pipesys_".$nama.date("ymd");
            $config['upload_path']      = './img/logo'; //path folder 
            $config['allowed_types']    = 'gif|jpg|png|jpeg|bmp|PNG|JPG'; //type yang dapat diakses bisa anda sesuaikan 
            $config['max_size']         = '99999'; //maksimum besar file 2M 
            $config['max_width']        = '99999'; //lebar maksimum 1288 px 
            $config['max_height']       = '99999'; //tinggi maksimu 768 px 
            $config['file_name']        = $nmfile; //nama yang terupload nantinya 
            $this->upload->initialize($config); 
            $upload                     = $this->upload->do_upload('photo');
            $gbr                        = $this->upload->data();
            $data = array(
                "nama"      => $this->input->post("nama"),
                "address"   => $this->input->post("address"),
                "city"      => $this->input->post("city"),
                "province"  => $this->input->post("province"),
                "country"   => $this->input->post("country"),
                "postal"    => $this->input->post("postal"),
                "fax"       => $this->input->post("fax"),
                "phone"     => $this->main->PhoneFormat($this->input->post('phone')),
                "phone_company" =>$this->main->PhoneFormat($this->input->post('phone')),
            );

            if($upload):        
                $image              = "img/logo/".$gbr['file_name'];
                $data['img_bin']    = $gbr['file_name'];
                $data['img_url']    = $image;
                $image              = site_url($image);
                $this->main->delete_img("user",array("id_user"=>$this->session->id_user),'img/logo/');
            endif;

            $this->db->where("id_user",$this->session->id_user);
            $this->db->update("user",$data);

        elseif($page == "user_account"):
            $Name       = $this->input->post("Name");
            $FirstName  = explode(" ", $Name)[0];
            $LastName   = str_replace($FirstName, "", $Name);
            $pass       = $this->input->post("password");
            $password   = $this->main->hash($pass);

            if($this->session->hak_akses == "sales"):
                $pass       = $this->input->post("password");
                $password   = $this->main->hash($pass);
                $data = array(
                    'Name'             => $Name,
                    'Phone'            => $this->main->PhoneFormat($this->input->post('phone')),
                    'PhoneCode'        => $this->input->post("PhoneCode"),
                    'FirstName'        => $FirstName,
                    'LastName'         => $LastName,
                );
                if($pass && $pass != "*****"):
                    $data["Password"] = $password;
                endif;
                $this->db->where("BranchID",$this->session->branchid);
                $this->db->update("Branch",$data);
            else:
                $data = array(
                    'nama'             => $Name,
                    'phone'            => $this->main->PhoneFormat($this->input->post('phone')),
                    'PhoneCode'        => $this->input->post("PhoneCode"),
                    'first_name'       => $FirstName,
                    'last_name'        => $LastName,
                );
                if($pass && $pass != "*****"):
                    $data["password"] = $password;
                endif;
                $this->db->where("id_user",$this->session->id_user);
                $this->db->update("user",$data);
            endif;
        elseif($page == "setting_parameter"):
            $settingid = $this->input->post("settingparameterid");
            $data = array(
                "Currency"      => $this->input->post("currency"),
                "AmountDecimal" => $this->input->post("amountdecimal"),
                "QtyDecimal"    => $this->input->post("qtydecimal"),
                "NegativeStock" => $this->input->post("negativestock"),
            );
            if($settingid):
                $this->db->where("CompanyID",$this->session->CompanyID);
                $this->db->where("SettingParameterID",$settingid);
                $this->db->update("SettingParameter",$data);
            else:
                $data["CompanyID"] = $this->session->CompanyID;
                $this->db->insert("SettingParameter",$data);
            endif;

            $this->main->setting_parameter();
        endif;
        $respon = array(
            "status"    => TRUE,
            "message"   => "Saving data success",
            "data"      => $data,
            "page"      => $page
        );
        header('Content-Type: application/json');
        echo json_encode($respon,JSON_PRETTY_PRINT);   
    }
    private function company_validation($page = "")
    {
        $data                   = array();
        $data['error_string']   = array();
        $data['inputerror']     = array();
        $data['status']         = TRUE;
        if($page == "company" && $this->input->post('nama') == '')
        {
            $data['inputerror'][]   = 'nama';
            $data['error_string'][] = 'Company name must be filled';
            $data['status']         = FALSE;
        }
        if($page == "user_account" && $this->input->post('Name') == '')
        {
            $data['inputerror'][]   = 'Name';
            $data['error_string'][] = 'Full name must be filled';
            $data['status']         = FALSE;
        }
        if($page == "user_account" && $this->input->post('phone') == '')
        {
            $data['inputerror'][]   = 'phone';
            $data['error_string'][] = 'Phone must be filled';
            $data['status']         = FALSE;
        }
        if($page == "user_account" && $this->input->post("password") != "*****" && $this->input->post('password') != '')
        {
            if($this->input->post('password') != $this->input->post('password_kon')){
                $data['inputerror'][]   = 'password_kon';
                $data['error_string'][] = 'Password Confirmation doesnt match';
                $data['status']         = FALSE;
            }
            $validasi_password = $this->main->validasi_password($this->input->post("password"));
            if($this->input->post("password") != "" && $this->input->post("password") != "********" && $validasi_password != ""){
                $data['inputerror'][]   = 'password';
                $data['error_string'][] = $validasi_password;
                $data['status']         = FALSE;
            }
        }
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }
    #user-account
    public function user_account_save()
    {

    }
    #autocomplete
    public function autocomplete_product(){
        $search   = $this->input->post('search',TRUE);
        $query  = $data = $this->main->product("autocomplete",$search);
        $data       =  array();
        foreach ($query as $d) {
            $data[]     = array(
                'label'                 => $d->product_code,
                'productid'             => $d->productid,
                'product_code'          => $d->product_code,
                'product_name'          => $d->product_name,
                'product_unitid'        => $d->unitid,
                'product_unit'          => $d->unit_name,
                'product_conversion'    => $d->conversion,
                'product_sellingprice'  => $this->main->currency($d->sellingprice),
            );
        }
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    #autocomplete
    public function autocomplete_vendor(){
        $search   = $this->input->post('search',TRUE);
        $query  = $data = $this->main->vendor("autocomplete",$search);
        $data       =  array();
        foreach ($query as $d) {
            $data[]     = array(
                'label'        => $d->vendorid."-".$d->name,
                'vendorid'     => $d->vendorid,
                'usercode'     => $d->usercode,
                'parentid'     => $d->parentid,
                'position'     => $d->position,
                'email'        => $d->email,
            );
        }
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    public function autocomplete_branch(){
        $search   = $this->input->post('search',TRUE);
        $query  = $data = $this->main->branch("autocomplete",$search);
        $data       =  array();
        foreach ($query as $d) {
            $data[]     = array(
                'label'     => $d->branchid."-".$d->name,
                'branchid'  => $d->branchid,
                'companyid' => $d->companyid,
                'name'      => $d->name,
            );
        }
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    #autocomplete serial number 2018-02-21 
    public function autocomplete_serialnumber(){
        $search     = $this->input->post('search',TRUE);
        $productid  = $this->input->post('productid',TRUE);
        $query      = $data = $this->main->product_serial("autocomplete",$search,$productid);
        $data       =  array();
        foreach ($query as $d) {
            if($d->serialnumber != ""){
                $data[]     = array(
                    'label'     => $d->serialnumber,
                );                
            }
        }
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);  
    }
    public function email()
    {
        $data["message"] = "tes";
        $data["page"] = "email/register";
        $this->load->view("email/email",$data);
    }
    public function emailtes()
    {
        $this->load->view("email/tes");
    }


    public function serial_number_datatables()
    {
        $page   = "serial_number";
        $list   = $this->main->serial_number_datatables($page);
        $data   = array();
        $no     = $_POST['start'];
        $i      = 1;
        foreach ($list as $a) {
            $no++;
            $row    = array();
            $row[]  = $i++;
            $row[]  = $a->serialno;      
            $data[] = $row;
        }
        $output = array(
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->main->serial_count_all($page),
            "recordsFiltered" => $this->main->serial_count_filtered($page),
            "data"            => $data,
        );
        echo json_encode($output);
    }
    public function ar_correction($page = "",$search = ""){
    	$data = $this->main->ar_correction($page,$search);
        $list_data = array();
        foreach($data as $a):
            $item = array(
                "branchid"      => $a->branchid,
                "branchname"    => $a->branchname,
                "total"         => $a->total,
                "grandtotal"    => $a->grandtotal,
                "lbl_sisatotal" => $this->main->currency($a->sisatotal,TRUE),
                "sisatotal"     => $a->sisatotal
            );
            array_push($list_data,$item);
        endforeach;
    	$output = array(
    		"status" 	=> TRUE,
    		"hakakses" 	=> $this->session->hak_akses,
    		"list_data"	=> $list_data,
    	);
    	header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }


    #2018-01-25 iqbal
    public function receive()
    {
        $data       = $this->main->receive();
        // $list_data  = array();
        $list_data  = $data;

        $output = array(
            "status"    => TRUE,
            "hakakses"  => $this->session->hak_akses,
            "list_data" => $list_data,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }
    public function receive_detail()
    {
        $data       = $this->main->receive_detail();
        $list_data  = array();
        foreach($data as $a):
            $item = array(
                "product_code"     => $a->product_code, 
                "product_konv"     => $a->product_konv,
                "product_name"     => $a->product_name,
                "product_price"    => $a->product_price,
                "product_qty"      => $a->product_qty,
                "product_subtotal" => $a->product_subtotal,
                "product_type"     => $a->product_type,
                "productid"        => $a->productid,
                "receive_det"      => $a->receive_det,
                "receive_no"       => $a->receive_no,
                "unit_name"        => $a->unit_name,
                "unitid"           => $a->unitid
            );
            array_push($list_data, $item);
        endforeach;
        $output     = array(
            "status"    => TRUE,
            "hakakses"  => $this->session->hak_akses,
            "list_data" => $list_data,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  

    }
    #ini untuk salespro
    #2018-02-04
    public function dashboard()
    {
        $bahasa = $this->session->bahasa;
        $arrayID     = array();
        if($this->session->hak_akses == "sales" && $this->session->HeadGroup == 1):
            $arrayID = $this->main->group_employe_id();
        endif;
        $user_detail            = $this->main->user_detail($this->session->UserID);
        $data                   = $this->main->dashboard("none",$arrayID);
        $branch                 = $this->main->sp_sales_location($arrayID);
        $total_customer         = $this->main->customer("count");
        $total_sales            = $this->main->sp_total_route_transaction("count_sales",$arrayID);
        $total_route            = $this->main->sp_total_route_transaction("all",$arrayID);
        $total_route_complete   = $this->main->sp_total_route_transaction("complete",$arrayID);
        $top_sales              = $this->main->sp_top_sales($arrayID);
        $total_route1           = $data["total_route"];
        $total_hour             = $data["total_hour"];
        $list_store             = array();
        $list_checkin           = array();
        $list_checkout          = array();
        $list_expire            = array();
        $list_expire            = $this->ListSalesExpire();
        foreach($branch as $a):
            $CheckTime = "";
            if($a->CheckTime):
                $CheckTime = date("d M Y H:i",strtotime($a->CheckTime));
            endif;
            $Duration = $this->main->selisih_waktu(date("Y-m-d H:i",strtotime($a->CheckTime)),date("Y-m-d H:i"));
            $item_branch = array(
                "App"           => $this->session->app,
                "ID"            => $a->BranchID,
                "Name"          => $a->Name,
                "Phone"         => $a->Phone,
                "Email"         => $a->Email,
                "Lat"           => $a->Lat,
                "Lng"           => $a->Lng,
                "Check"         => $a->Check,
                "CheckTime"     => $CheckTime,
                "CheckAddress"  => $a->CheckAddress,
                "Duration"      => $Duration
            );
            array_push($list_store,$item_branch);
            if($a->Check == "in"):
                $item_branch["CheckTime"] = date("H:i",strtotime($CheckTime));
                array_push($list_checkin,$item_branch);
            endif;
            if($a->Check == "out"):
                $item_branch["CheckTime"] = $CheckTime;
                array_push($list_checkout,$item_branch);
            endif;
        endforeach;
        $JoinDate           = date("Y-m-d",strtotime($user_detail->JoinDate));
        $VerificationExpire = date('Y-m-d', strtotime("+6 day", strtotime($JoinDate)));
        $VerificationExpire = $this->main->tanggal("D, d M Y",$VerificationExpire);
        $output = array(
            "status"                    => TRUE,
            "bahasa"                    => $bahasa,
            "VerificationExpire"        => $VerificationExpire,
            "StatusVerify"              => $this->session->StatusVerify,
            "JoinDate"                  => $JoinDate,
            "ExpireAccount"             => $this->session->ExpireAccount,
            "StatusAccount"             => $this->session->StatusAccount,
            "AlertVerification"         => $this->main->AlertVerification(),
            "ExpireAccountDayLeft"      => $this->main->selisih_hari(date("Y-m-d"),date("Y-m-d",strtotime($this->session->ExpireAccount))),
            "hakakses"                  => $this->session->hak_akses,
            "total_sales"               => $total_sales,
            "total_route_transaction"   => $total_route,
            "total_complete_route"      => $total_route_complete,
            "total_customer"            => $total_customer,
            "top_sales"                 => $top_sales,
            'total_route1'              => $total_route1,
            'total_hour'                => $total_hour,
            "list_sales"                => $list_store,
            "list_checkin"              => $list_checkin,
            "list_checkout"             => $list_checkout,
            "list_expire"               => $list_expire,
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }
    public function push_notif_transaction($transactionID,$BranchID)
    {
        $this->firebase->push_new_route_transaction($transactionID,$BranchID); #transaction branchid
    }
    public function selisih_waktu()
    {
        $selish_waktu = $this->main->selish_waktu(date("Y-m",strtotime("2017-02-01")),date("Y-m"));
        print_r($selish_waktu);
    }
    public function get_voucher_price()
    {
        $App    = $this->input->post("App");
        $Type   = $this->input->post("Type");
        $Qty    = $this->input->post("Qty");
        $price  = 0.00;
        $price_total = 0.00;
        $a      = $this->main->voucher_package($App,$Type,$Qty);
        if($a):
            $price       = $a->Price;
            $price_total = $price * $Qty;
        endif;
        $output = array(
            "price_total"   => $price_total,
            "price"         => $price,
            "price_total_txt"   => $this->main->currency($price_total),
            "price_txt"     => $this->main->currency($price),
            "status"        => TRUE,
            "message"       => "Success",
            "hakakses"      => $this->session->hak_akses
        );
        header('Content-Type: application/json');
        echo json_encode($output,JSON_PRETTY_PRINT);  
    }
    public function ListSalesExpire(){
        $data = $this->main->ListSalesExpire();
        return $data;
    }
    public function get_setting($modul = "")
    { 
        $CompanyID  = $this->session->CompanyID;
        $post       = $this->input->post();
        $Data       = array(); 
        $ListData   = array(); 
        $CodeArray  = array();
        if($modul == "connect-sap"):
            if($CompanyID):
                $this->db->where("Type","sap");
                $this->db->where("CompanyID",$CompanyID);
                $this->db->order_by("ConnectID","desc");
                $query = $this->db->get("UT_ConnectPlugin");
                $a = $query->row();

                $Data['IPAddress']  = $this->main->token_decode($a->IPAddress);
                $Data['Port']       = $this->main->token_decode($a->Port);
                $Data['Username']   = $this->main->token_decode($a->Username);
                $Data['Password']   = $this->main->token_decode($a->Password);
                $Data['Database']   = $this->main->token_decode($a->Database); 
            endif;
        elseif($modul == "slideshow"):
            $ListData = $this->api->slideshow('list_data');
        else:
            foreach($post as $key => $a):
                $CodeArray[] = $key;
            endforeach;
            foreach($_FILES as $key => $a):
                $CodeArray[] = $key;
            endforeach;
            if(count($CodeArray) > 0):
                $this->db->where_in("Code",$CodeArray);
                $query = $this->db->get("UT_General");
                $Listdatax = $query->result();
                foreach($Listdatax as $a):
                    $ListData[] = array(
                        "Code"  => $a->Code,
                        "Value" => $a->Value,
                    );
                endforeach;
            endif;
        endif;
        $output     = array(
            "HakAkses"  => $this->session->HakAkses,
            "Status"    => TRUE,
            "Data"      => $Data,
            "ListData"  => $ListData
        );
        $this->main->echoJson($output);
    }
    public function save_setting($modul = "")
    {
        $CompanyID  = $this->session->CompanyID;
        $post       = $this->input->post();
        $postar     = array();
        $data       = array();
        $PostName   = array();
        if($modul == "connect-sap"):
            $IPAddress = $this->input->post("IPAddress");
            $Port      = $this->input->post("Port");
            $Username  = $this->input->post("Username");
            $Password  = $this->input->post("Password");
            $Database  = $this->input->post("Database");
            $data = array(
                "CompanyID" => $CompanyID,
                "IPAddress" => $this->main->token_encode($IPAddress),
                "Port"      => $this->main->token_encode($Port),
                "Username"  => $this->main->token_encode($Username),
                "Password"  => $this->main->token_encode($Password),
                "Database"  => $this->main->token_encode($Database),
                "Type"      => 'sap',
            );
            if($this->db->count_all("UT_ConnectPlugin where Type = 'sap' and CompanyID='$CompanyID' ") > 0):
                $this->db->where("Type","sap");
                $this->db->where("CompanyID",$CompanyID);
                $this->db->update("UT_ConnectPlugin",$data);
                $data['UserCh'] = $this->session->Name;
                $data['DateCh'] = date("Y-m-d H:i:s");
            else:
                $data['UserAdd'] = $this->session->Name;
                $data['DateAdd'] = date("Y-m-d H:i:s");
                $this->db->insert("UT_ConnectPlugin",$data);
            endif;
        elseif($modul == "slideshow"):
            $AttachmentID               = $this->input->post("AttachmentID");
            $Name                       = $this->input->post("Name");
            $NameColor                  = $this->input->post("NameColor");
            $Description                = $this->input->post("Description");
            $Position                   = $this->input->post("Position");

            $ButtonLink                 = array();
            $BtnID                      = $this->input->post("BtnID");
            $BtnName                    = $this->input->post("BtnName");
            $BtnLink                    = $this->input->post("BtnLink");
            $BtnColor                   = $this->input->post("BtnColor");
            foreach($BtnID as $key => $a):
                $ButtonLink[] = array(
                    "BtnID"     => $BtnID[$key],
                    "BtnName"   => $BtnName[$key],
                    "BtnLink"   => $BtnLink[$key],
                    "BtnColor"  => $BtnColor[$key]
                );
            endforeach;
            $ButtonLink = json_encode($ButtonLink);


            $nmfile                     = 'peopleshape_salespro_'.$Name."_".time();
            $config['upload_path']      = './img/slideshow'; //path folder 
            $config['allowed_types']    = 'gif|jpg|png|jpeg|bmp|PNG|JPG';
            $config['max_size']         = '99999'; //maksimum besar file 2M 
            $config['max_width']        = '99999'; //lebar maksimum 1288 px 
            $config['max_height']       = '99999'; //tinggi maksimu 768 px 
            $config['file_name']        = $nmfile; //nama yang terupload nantinya 
            $this->upload->initialize($config); 
            $upload = $this->upload->do_upload("Image");
            $gbr    = $this->upload->data();
            #---------------------------------------------------------------------------------------------------------------
            $data   = array(
                "Name"          => $Name,
                "NameColor"     => $NameColor,
                "Description"   => $Description,
                "Position"      => $Position,
                "Type"          => "slideshow",
                "ButtonLink"    => $ButtonLink
            );
            if($upload):        
                $image          = "img/".$modul."/".$gbr['file_name'];
                $data["Patch"]  = $image;
                if($AttachmentID > 0):
                    $this->main->hapus_gambar('UT_Attachment','Patch',array('AttachmentID' => $AttachmentID));
                endif;
            endif;
            if($AttachmentID > 0):
                $this->db->where("AttachmentID",$AttachmentID);
                $this->db->update("UT_Attachment",$data);
            else:
                $data["Sort"] = $this->api->slideshow("last_sort") + 1;
                $this->db->insert("UT_Attachment",$data);
            endif;
        elseif($modul == "main-menu"):
            $Value          = array();
            $Code           = "MainMenu";
            $cek            = $this->db->count_all("UT_General where Code='$Code' ");
            $ContentID      = $this->input->post("ContentID");
            $ContentIDFix   = $this->input->post("ContentIDFix");
            $ContentName    = $this->input->post("ContentName");
            $ContentUrl     = $this->input->post("ContentUrl");
            $ContentType    = $this->input->post("ContentType");
            if(count($ContentIDFix) > 0):
                foreach($ContentID as $key => $a):
                    if(in_array($a, $ContentIDFix)):
                        $Value[] = array(
                            "ContentID" => $ContentID[$key],
                            "Name"      => $ContentName[$key],
                            "Url"       => $ContentUrl[$key],
                            "Type"      => $ContentType[$key],
                            "Sub"       => array()
                        );
                    endif;
                endforeach;
                $Value = json_encode($Value);
                $this->save_setting_db($cek,$Code,$Value);
            endif;
        else:
            foreach($post as $key => $a):
                $PostName[] = $key;
                #$postar[$key] = $this->input->post($key);
                $Code   = $key;
                $Value  = $this->input->post($Code);
                $cek    = $this->db->count_all("UT_General where Code='$Code' ");
                #data------------------------------------------------------------
                if(strlen($Value) > 0):
                    $this->save_setting_db($cek,$Code,$Value);
                endif;
            endforeach;

            foreach($_FILES as $key => $a):
                $PostName[] = $key;
                $Code       = $key;
                $cek        = $this->db->count_all("UT_General where Code='$Code' ");
                #data------------------------------------------------------------
                if($Code == "Logo" || $Code == "Icon"):
                    $nmfile                     = 'peopleshape_salespro_'.$Code."_".time();
                    $config['upload_path']      = './img/general'; //path folder 
                    $config['allowed_types']    = 'gif|jpg|png|jpeg|bmp|PNG|JPG'; //type yang dapat diakses bisa anda sesuaikan 
                    $config['max_size']         = '99999'; //maksimum besar file 2M 
                    $config['max_width']        = '99999'; //lebar maksimum 1288 px 
                    $config['max_height']       = '99999'; //tinggi maksimu 768 px 
                    $config['file_name']        = $nmfile; //nama yang terupload nantinya 
                    $this->upload->initialize($config); 
                    $upload = $this->upload->do_upload($Code);
                    $gbr    = $this->upload->data();
                    if($upload):        
                        $image  = "img/general/".$gbr['file_name'];
                        $Value  = $image;
                        if($cek > 0):
                            $this->main->hapus_gambar('UT_General','Value',array('Code' => $Code));
                        endif;
                        $this->save_setting_db($cek,$Code,$Value);
                    endif;
                endif;
            endforeach;
        endif;
        $output = array(
            "HakAkses"  => $this->session->HakAkses,
            "Status"    => TRUE,
            "Modul"     => $modul,
            "Post"      => $PostName,
            "Data"      => $data
        );
        $this->main->echoJson($output);
    }
    public function save_setting_db($cek,$Code,$Value){
        $data["Value"]  = $Value;
        if($cek > 0):
            $data["UserCh"] = $this->session->Name;
            $data["DateCh"] = date("Y-m-d H:i:s");
            $this->db->where("Code",$Code);
            $this->db->update("UT_General",$data);
        else:
            $data["Code"]    = $Code;
            $data["UserAdd"] = $this->session->Name;
            $data["DateAdd"] = date("Y-m-d H:i:s");
            $this->db->insert("UT_General",$data);
        endif;
    }
    public function slideshow($page = "",$id = ""){
        $data = array();
        if($page == "ubah_urutan"):
            $ArrayID = $this->input->post("ArrayID");
            $ArrayUrutan = $this->input->post("ArrayUrutan");
            foreach($ArrayID as $key => $a):
                $datax = array(
                    "Sort" => $ArrayUrutan[$key]
                );
                $data[] = $a;
                $this->db->where("Type","slideshow");
                $this->db->where("AttachmentID",$a);
                $this->db->update("UT_Attachment",$datax);
            endforeach;
        elseif($page == "delete"):
            $this->main->hapus_gambar('UT_Attachment','Patch',array('AttachmentID' => $id));

            $this->db->where("AttachmentID",$id);
            $this->db->delete("UT_Attachment");
        elseif($page == "edit"):
            $data = $this->api->slideshow($page,$id);
            $a    = $data;
            $data = array(
                "Active"        => $a->Active,
                "AttachmentID"  => $a->AttachmentID,
                "ButtonLink"    => json_decode($a->ButtonLink),
                "Description"   => $a->Description,
                "ID"            => $a->AttachmentID,
                "Name"          => $a->Name,
                "NameColor"     => $a->NameColor,
                "Patch"         => $a->Patch,
                "Position"      => $a->Position,
                "Sort"          => $a->Sort,
                "Type"          => $a->Type
            );
        endif;
        $output = array(
            "HakAkses"  => $this->session->HakAkses,
            "Status"    => TRUE,
            "Data"      => $data,
            "Page"      => $page
        );
        $this->main->echoJson($output);
    }
    public function sap(){
        $this->load->model("M_sap");
        echo "<pre>";
        $data = $this->M_sap->connect_db_check();
        print_r($data);

    }
}