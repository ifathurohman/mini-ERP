<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->lang->load('bahasa', $this->session->userdata("bahasa"));
    }
    public function index()
    {
        $data["index"]  = "";
        $data["page"]   = "page/blank";
        $this->load->view("index",$data);
    } 
    public function login()
    {
        $this->main->cek_session("luar");
        $data["page"]       = "login";
        $data["title"]      = "Login";
        $data["title_form"] = $this->lang->line('lb_login_account');
        $data["btn_text"]   = $this->lang->line('lb_login_account');
        $data["link"]       = $this->lang->line('lb_no_account')." <a href='".site_url("register")."'>".$this->lang->line('lb_register_now')."</a>";
        $this->load->view("page/login",$data);
    }
    public function register()
    {
        $this->main->cek_session("luar");
        $data["page"]       = "register";
        $data["title"]      = "pipesys";
        $data["title_form"] = $this->lang->line('lb_register_account');
        $data["btn_text"]   = $this->lang->line('lb_register_account');
        $data["link"]       = "<a href='".site_url("login")."'>".$this->lang->line('lb_have_account')."</a>";
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
                $data["title_form"] = $this->lang->line('lb_verification_account');
                $data["btn_text"]   = $this->lang->line('lb_verification_account');
                $data["link"]       = "<a href='".site_url("logout")."'>".$this->lang->line("lb_user_another_account")." ?</a>"."<a href='javascript:;' class='pull-right' onclick='verification_account_modal(this)'>".$this->lang->line('lb_resend_verification')."</a>";
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
        $data["title"]      = "pipesys";
        $data["title_form"] = $this->lang->line("lb_forgot_password");;
        $data["btn_text"]   = $this->lang->line("lb_send");
        $data["link"]       = $this->lang->line('lb_no_account')." <a href='".site_url("register")."'>".$this->lang->line('lb_register_now')."</a>";
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
        $data["title_form"] = $this->lang->line("lb_reset_password");
        $data["btn_text"]   = $this->lang->line("lb_reset_password");
        $data["link"]       = "<a href='".site_url("login")."'>".$this->lang->line('lb_have_account')."</a>";
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
    #2017-12-28
    public function company_information()
    {
        $this->main->cek_session();
        $data["index"]  = "";
        $data["title"]  = $this->lang->line("lb_company_info");
        $data["page"]   = "page/company_information";
        $this->load->view("index",$data);
    }
    #2017-12-28 
    public function user_account()
    {
        $this->main->cek_session();
        $data["index"]  = "";
        $data["title"]  = $this->lang->line("lb_user_account");
        $data["page"]   = "page/user_account";
        $this->load->view("index",$data);   
    }
    #2018-01-09
    public function setting_parameter()
    {
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

    public function bahasa($bahasa){
        $this->main->bahasa($bahasa);
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
            redirect();
        else:
            redirect($this->agent->referrer());
        endif;
    }
    public function general_setting($page){
        $tambah     = "";
        $url_modul  = $this->uri->segment(1); 
        $id_url     = $this->main->GetMenuID($url_modul);
        $menu_name  = $this->main->GetMenuName('current_url');
        $read       = $this->main->read($id_url);
        if($read == 0): redirect("dashboard"); endif;
        $admin_tambah = $this->main->menu_tambah($id_url);
        if($admin_tambah > 0):
            $tambah = '<button type="button" class="btn btn-white" onclick="add_data()" >Add New Data</button>';
        endif;
        #ini untuk session halaman aturan user privileges;
        $data['title']          = $menu_name;
        $data['content']        = 'page/general_setting';
        $data['page']           = 'page/general_setting';
        $data['modul']          = $page;
        $data['url_modul']      = $url_modul;
        $this->load->view('index',$data);
    }
}

