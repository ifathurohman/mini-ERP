<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url','file');
    }
    public function index()
    {
        $data["index"]  = "";
        $data["page"]   = "page/blank";
        $this->load->view("index",$data);
    }

    public function bahasa($bahasa){
        $this->session->set_userdata("bahasa",$bahasa);
        redirect($this->agent->referrer());
    }
    
    public function login()
    {
        $this->main->cek_session("luar");
        $data["page"]       = "login";
        $data["title"]      = "Login";
        $data["title_form"] = "Login Account";
        $data["btn_text"]   = "Login Account";
        $data["link"]       = "Don't have an account yet ? <a href='".site_url("register")."'>Register Now</a>";
        $this->load->view("page/login",$data);
    }
    public function maintenance()
    {
        $data["page"]       = "login";
        $data["title"]      = "Login";
        $this->load->view("page/fb",$data);
    }
    public function register()
    {
        $this->main->cek_session("luar");
        $data["page"] = "register";
        $data["title"] = "Register";
        $data["title_form"] = "Register Account";
        $data["btn_text"] = "Register Account";
        $data["link"] = "<a href='".site_url("login")."'>Already have an account?</a>";
        $this->load->view("page/login",$data);
    }
    public function konfirmasi_akun()
    {
        $token   = $this->input->get("t");
        $id_user = $this->main->token_decode($token);
        $a       = $this->main->user_detail($id_user);
        if(empty($token) || empty($a)):
            redirect("login");
        else:
            $id_user    = $a->id_user;
            $email      = $a->email;
            $respon     = $this->main->login("konfirmasi_akun",$email);
            $this->main->user_update($id_user,array("status"=>1,"StatusVerify" => 1));
            if($respon["status"]):
                redirect("dashboard");
            else:
                redirect("login");
            endif;
        endif;
    }

    public function verification_account(){
        if($this->session->id_user):
            if($this->session->StatusVerify == 1):
                $this->main->cek_session("luar");
            else:
                $data["page"]       = "verification_account";
                $data["title"]      = "pipesys";
                $data["title_form"] = "Verification Account";
                $data["btn_text"]   = "Verification Account";
                $data["link"]       = "<a href='".site_url("logout")."'>"."Use another account"." ?</a>"."<a href='javascript:;' class='pull-right' onclick='verification_account_modal(this)'>"."Resend verification code"."</a>";
                $data["Token"]      = $this->main->token_encode($this->session->id_user);
                $data["a"]          = $this->main->user_detail($this->session->id_user);
                $this->load->view("page/login",$data);
            endif;
        else:
            redirect("login");
        endif;
    }

    public function forgot_password()
    {
        $this->main->cek_session("luar");
        $data["page"]       = "forgot_password";
        $data["title"]      = "Forgot Password";
        $data["title_form"] = "Forgot password";
        $data["btn_text"]   = "Send";
        $data["link"]       = "Forgot password ? <a href='".site_url("register")."'>Register Now</a>";
        $this->load->view("page/login",$data);
    }
    public function reset_password()
    {
        $token   = $this->input->get("t");
        $id_user = $this->main->token_decode($token);
        $a       = $this->main->user_detail($id_user);
        if(empty($token) || empty($a)):
            redirect("login");
        else:
            $id_user = $a->id_user;
        endif;
        $this->main->cek_session("luar");
        $data["page"]       = "reset_password";
        $data["title"]      = "pipesys";
        $data["title_form"] = $this->lang->line('lb_reset_password');
        $data["btn_text"]   = $this->lang->line('lb_reset');
        $data["link"]       = "<a href='".site_url("login")."'>".$this->lang->line('lb_have_account')." ?</a>";
        $data["id_user"]    = $token;
        $this->load->view("page/login",$data);
    }
    public function logout()
    {
        $this->main->logout();
    }
    public function error_404()
    {
        redirect(site_url());
        // $this->load->view('backend/error/error_404');
    }

    #20190930
    public function error_403(){
        $data["index"]  = "";
        $data["title"]  = "403 Forbidden";
        $data["page"]   = "page/403_forbidden";
        $this->load->view("index",$data);
    }

    #2017-12-28
    public function company_information()
    {
        $this->main->cek_session();
        $data["index"]  = "";
        $data["title"]  = "Company Information";
        $data["page"]   = "page/company_information";
        $this->load->view("index",$data);
    }
    #2017-12-28 
    public function user_account()
    {
        $this->main->cek_session();
        $data["index"]  = "";
        $data["title"]  = "User Account";
        $data["page"]   = "page/user_account";
        $this->load->view("index",$data);   
    }

    #2018-01-09
    public function setting_parameter()
    {
        $this->main->cek_session("parameter");
        $data["index"]  = "";
        $data["title"]  = "Setting & Parameter";
        $data["page"]   = "page/setting_parameter";
        $this->load->view("index",$data);   
    }
    public function set_app($app)
    {
        if($app == "salespro"):
            $set_app = "salespro";
        else:
            $set_app = "pipesys";
        endif;
        $this->session->set_userdata("app",$set_app);
        redirect($this->agent->referrer());
        redirect();
    }
    #2018-04-05
    public function billing_information()
    {
        $data["index"]  = "";
        $data["title"]  = "Billing Information";
        $data["page"]   = "page/billing_information";
        $this->load->view("index",$data);   
    }

    public function verification_account_lewat()
    {
        if($this->session->login):
            $UserID = $this->session->UserID;
            $this->db->set("StatusVerify",2);
            $this->db->set("status",1);
            $this->db->where("id_user",$UserID);
            $this->db->update("user");
            $this->session->set_userdata("StatusVerify",2);
            redirect('dashboard');
        else:
            redirect($this->agent->referrer());
        endif;
    }

    #venor price
    public function vendor_price(){
        $list   = $this->main->get_datatables_vendor_price();
        $data   = array();

        $i = 1;
        foreach ($list as $a) {
            $row    = array();
            
            $classnya  = 'class="data-vendor'.$a->ID.' rowid_'.$a->ID.'"';
            $tag       = ' data-id = "'.$a->ID.'"';
            $tag      .= ' data-group_name = "'.$a->GroupName.'"';
            $tag      .= ' data-producid = "'.$a->ProductID.'"';
            $tag      .= ' data-product_code = "'.$a->product_code.'"';
            $tag      .= ' data-product_name = "'.$a->product_name.'"';
            $tag      .= ' data-product_unit = "'.$a->unit_name.'"';
            $tag      .= ' data-price_type = "'.$a->price_type.'"';
            $tag      .= ' data-price = "'.$a->Price.'"';
            $tag      .= ' data-price_sell = "'.$a->PriceSell.'"';
            $tag      .= ' data-purchase_price = "'.$a->PurchasePrice.'"';
            $tag      .= ' data-sell_price = "'.$a->SellingPrice.'"';

            if($a->Status == 1):
                $btn = '<a class="btn-active"href="javascript:void(0)" type="button" title="Nonactive" onclick="delete_vendor_price('."'".$a->ID."','nonactive'".')">Nonactive</a>';
            else:
                $btn = '<a class="btn-active"href="javascript:void(0)" type="button" title="Active" onclick="delete_vendor_price('."'".$a->ID."','active'".')">Active</a>';
            endif;

            $row[]  = '<div style="width: 50px !important" class="vendor_price_check data-list data-vendor'.$a->ID.'" '.$tag.'">
                        <input type="checkbox" name="vendor_price_check[]" value="'.$a->ID.'"/>
                    </div>';
            $row[]  =   '<div '.$classnya.'">
                        <div class="list-data">'.$i++.'</div>
                        </div>';
            $row[]  =   '<div '.$classnya.'">
                            <div class="list-data">'.$a->GroupName." ".$this->main->label_active2($a->Status).'</div>
                        </div>';
            $row[]  =   '<div '.$classnya.'>
                            <div class="list-data">'.$a->product_code.'</div>
                        </div>';
            $row[]  =   '<div '.$classnya.'>
                            <div class="list-data">'.$a->product_name.'</div>
                        </div>';
            $row[]  =   '<div '.$classnya.'>
                            <div class="list-data">'.$a->unit_name.'</div>
                        </div>';
            $row[]  =   '<div '.$classnya.'>
                            <div class="list-data">'.$a->Type.'</div>
                        </div>';
            $row[]  =   '<div '.$classnya.'>
                            <div class="list-data">'.$this->main->Currency($a->Price).'</div>
                        </div>';
            $row[]  =   '<div '.$classnya.'>
                            <div class="list-data">'.$this->main->Currency($a->PriceSell).'</div>
                        </div>';
            $row[]  = $btn;
            $data[] = $row;
        }
        $output = array(
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->main->count_all_vendor_price(),
            "recordsFiltered" => $this->main->count_filtered_vendor_price(),
            "data"            => $data,
        );
        echo json_encode($output);
    }

    public function save_vendor_price(){
        $this->validate_save_vendor_price();

        $CompanyID      = $this->session->CompanyID;
        $product_group  = $this->input->post('product_group');
        $product_id     = $this->input->post('product_id');
        $product_p_type = $this->input->post('product_p_type');
        $product_price  = $this->input->post('product_price');
        $product_total  = $this->input->post('product_total');

        foreach ($product_id as $key => $v) {
            if($product_id[$key] != "" and $product_group[$key] != ""):
                $data = array(
                    "CompanyID"     => $CompanyID,
                    "ProductID"     => $product_id[$key],
                    "GroupName"     => strtoupper($product_group[$key]),
                    "Type"          => $product_p_type[$key],
                    "Price"         => $this->main->checkDuitInput($product_price[$key]),
                    "PriceSell"     => $this->main->checkDuitInput($product_total[$key]),
                    "Status"        => 1,
                    "User_Add"      => $this->session->NAMA,
                    "Date_Add"      => date("Y-m-d H:i:s"),
                );
                $this->db->insert("ps_product_customer", $data);
            endif;
        }

        $res = array(
            "status"    => true,
            "message"   => "Success",
        );

        $this->main->echoJson($res);
    }

    public function update_vendor_price(){
        $this->validate_update_vendor_price();

        $CompanyID      = $this->session->CompanyID;

        $check          = $this->input->post('vendor_price_check');
        $ID             = $this->input->post('data_vendor_ID');
        $product_group  = $this->input->post('product_group');
        $product_id     = $this->input->post('product_id');
        $p_type         = $this->input->post('product_p_type');
        $product_total  = $this->input->post('product_total');
        $product_price  = $this->input->post('product_price');
        $product_total  = $this->input->post('product_total');

        foreach ($ID as $key => $v) {
            if(in_array($ID[$key], $check)):
                $data = array(
                    "CompanyID"     => $CompanyID,
                    "ProductID"     => $product_id[$key],
                    "GroupName"     => strtoupper($product_group[$key]),
                    "Type"          => $p_type[$key],
                    "Price"         => $this->main->checkDuitInput($product_price[$key]),
                    "PriceSell"     => $this->main->checkDuitInput($product_total[$key]),
                    "User_Ch"      => $this->session->NAMA,
                    "Date_Ch"      => date("Y-m-d H:i:s"),
                );
                $this->db->where("CompanyID", $CompanyID);
                $this->db->where("ProductCustomerID", $ID[$key]);
                $this->db->update("ps_product_customer",$data);
            endif;
        }

        $res = array(
            "status"    => true,
            "message"   => "Success",
        );

        $this->main->echoJson($res);
    }

    private function validate_save_vendor_price(){
        $data = array();
        $data['status'] = TRUE;
        $CompanyID      = $this->session->CompanyID;

        $product_rowid  = $this->input->post('product_rowid');
        $product_group  = $this->input->post('product_group');
        $product_code   = $this->input->post('product_code');
        $product_id     = $this->input->post('product_id');
        $product_p_type = $this->input->post('product_p_type');

        $product_status = FALSE;
        foreach ($product_id as $key => $v) {
            if($product_id[$key] != "" and $product_group[$key] != ""):
                $product_status = TRUE;
                $ProductID = $product_id[$key];
                $GroupName = $product_group[$key];
                $cek = $this->db->count_all("ps_product_customer where CompanyID = '$CompanyID' and ProductID = '$ProductID' and GroupName = '$GroupName'");
                if(!$product_p_type[$key]):
                    $data['inputerror'][]   = $product_rowid[$key];
                    $data['error_string'][] = "select type price";
                    $data['list'][]         = 'list';
                    $data['tab'][]          = '';
                    $data['message']        = "incomplete form";
                    $data['status']         = FALSE;
                elseif($cek>0):
                    $data['inputerror'][]   = $product_rowid[$key];
                    $data['error_string'][] = "data has been exists";
                    $data['list'][]         = 'list';
                    $data['tab'][]          = '';
                    $data['message']        = "data has been exists";
                    $data['status']         = FALSE;
                endif;
            elseif($product_id[$key] != "" and $product_group[$key] == "" OR $product_id[$key] == "" and $product_group[$key] != ""):
                $data['inputerror'][]   = $product_rowid[$key];
                $data['error_string'][] = "incomplete form";
                $data['list'][]         = 'list';
                $data['tab'][]          = '';
                $data['message']        = "incomplete form";
                $data['status']         = FALSE;
            endif;
        }

        if(!$product_status):
            $data['inputerror'][]   = '';
            $data['error_string'][] = '';
            $data['list'][]         = '';
            $data['message']        = "incomplete form";
            $data['status']         = FALSE;
            echo json_encode($data);
            exit();
        endif;

        if($data['status'] === FALSE)
        {
            header('Content-Type: application/json');
            echo json_encode($data,JSON_PRETTY_PRINT);  
            exit();
        }
    }

    private function validate_update_vendor_price(){
        $data = array();
        $data['status'] = TRUE;
        $CompanyID      = $this->session->CompanyID;

        $check          = $this->input->post('vendor_price_check');
        $ID             = $this->input->post('data_vendor_ID');
        $product_group  = $this->input->post('product_group');
        $product_id     = $this->input->post('product_id');
        $p_type         = $this->input->post('product_p_type');
        $product_total  = $this->input->post('product_total');

        if(count($check)>0):
            foreach ($ID as $key => $v) {
                if(in_array($ID[$key], $check)):
                    if($product_id[$key] != "" and $product_group[$key] != ""):
                        $ProductID = $product_id[$key];
                        $GroupName = $product_group[$key];
                        $ProductCustomerID = $ID[$key];
                        $cek = $this->db->count_all("ps_product_customer where CompanyID = '$CompanyID' and ProductID = '$ProductID' and GroupName = '$GroupName' and ProductCustomerID != '$ProductCustomerID'");
                        if(!$p_type[$key]):
                            $data['inputerror'][]   = $ID[$key];
                            $data['error_string'][] = "select type price";
                            $data['list'][]         = 'list';
                            $data['tab'][]          = '';
                            $data['message']        = "incomplete form";
                            $data['status']         = FALSE;
                        elseif($cek>0):
                            $data['inputerror'][]   = $ID[$key];
                            $data['error_string'][] = "data has been exists";
                            $data['list'][]         = 'list';
                            $data['tab'][]          = '';
                            $data['message']        = "data has been exists";
                            $data['status']         = FALSE;
                        endif;
                    else:
                        $data['inputerror'][]   = $ID[$key];
                        $data['error_string'][] = "incomplete form";
                        $data['list'][]         = 'list';
                        $data['tab'][]          = '';
                        $data['message']        = "incomplete form";
                        $data['status']         = FALSE;
                    endif;
                endif;
            }
        else:
            $data['inputerror'][]   = '';
            $data['error_string'][] = '';
            $data['list'][]         = '';
            $data['message']        = "Please select group name";
            $data['status']         = FALSE;
            echo json_encode($data);
            exit();
        endif;

        if($data['status'] === FALSE)
        {
            header('Content-Type: application/json');
            echo json_encode($data,JSON_PRETTY_PRINT);  
            exit();
        }
    }

    public function delete_vendor_price($id){
        $list = $this->api->get_product_customer($id,"detail");
        if(count($list)>0):
            $status = 1;
            if($list->Status == 1):
                $status = 0;
            endif;
            $this->db->where("CompanyID", $this->session->CompanyID);
            $this->db->where("ProductCustomerID",$id);
            $this->db->update("ps_product_customer", array("Status" => $status));

            $output = array(
                "status"    => TRUE,
                "message"   => "success",
            );
        else:
            $output = array(
                "status"    => FALSE,
                "message"   => "Data not found",
            );
        endif;

        $this->main->echoJson($output);
    }

    public function general_setting($page){
        $tambah     = "";
        $url_modul  = $this->uri->segment(1); 
        $id_url     = $this->main->id_menu($url_modul);
        $menu_name  = $this->main->GetMenuName('current_url');
        #ini untuk session halaman aturan user privileges;
        $data['title']          = $menu_name;
        $data['content']        = 'page/general_setting';
        $data['page']           = 'page/general_setting';
        $data['modul']          = $page;
        $data['url_modul']      = $url_modul;
        $this->load->view('index',$data);
    }

    public function frame(){
        $this->main->cek_session();
        $page       = $this->input->post("page");
        
        $folder = 'file/temp'.$this->session->CompanyID.'/';
        if (!is_dir($folder)) {
            mkdir('./'.$folder, 0777, TRUE);
        }
        $files = glob('./'.$folder.'*.*');
        foreach($files as $file){
            if(is_file($file))
                unlink($file);
        }

        $frame      = $this->input->post("frame");
        $filename   = $this->input->post("filename");
        $type       = $this->input->post("type");
        $typenya    = array('png','jpg','jpeg');
        $patch      = '';
        $style      = '';

        if(!$frame):
            $frame = site_url();
        else:
            if($page != "show"):
                $frame  = str_replace('[removed]', "", $frame);
                $file   = substr($frame, strpos($frame,"base64,"));
                $decoded=base64_decode($file);
                file_put_contents($folder.$filename,$decoded);
            else:
                $folder = '';
                $filename = $frame;
            endif;
            $filename;
            if(!in_array($type,$typenya)):
                $frame = 'https://docs.google.com/viewerng/viewer?url='.site_url($folder.$filename).'&embedded=true';
                $style = '<style>embed{width: 100%;height: 100%}</style>';
                $frame = '<embed src="'.$frame.'"></embed>';
            else:
                $frame = $folder.$filename;
                $frame = '<img src="'.$frame.'"/>';
            endif;
            $patch = $folder.$filename;
        endif;
        // $url = 'https://docs.google.com/viewerng/viewer?url='.site_url('file/SampelProduct20190703_092515.xls');
        $data['title']  = "Attachment";
        $data['frame']  = $frame;
        $data['style']  = $style;
        $this->load->view('page/frame',$data);
    }

    public function tes(){
        $this->main->send_email("testing",1,"ya");
    }
    public function tes1(){
        $this->main->send_email("buy_voucher",1,"ya");
    }
}

