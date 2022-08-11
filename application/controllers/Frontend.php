<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Frontend extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model("M_frontend",'frontend');
    }
    public function indexx()
    {
        if(!$this->session->bahasa):
            $this->session->set_userdata("bahasa","english");
            redirect();
        endif;

        $data["meta"]   = $this->main->meta();
        $data["index"]  = "";
        $data["title"]  = "Pipesys";
        $data["content"]  = "frontend_new/page/home";
        $this->load->view("frontend_new/index",$data);
    }
    public function index()
    {
        if(!$this->session->bahasa):
            $this->session->set_userdata("bahasa","english");
            redirect();
        endif;

        $this->load-> library('Mobile_Detect');
        $detect = new Mobile_Detect();

        if ($detect->isMobile() || $detect->isTablet() || $detect->isAndroidOS()) {
            $device = "mobile";
        } else {        
            $device = "web";
        }
        $data["meta"]   = $this->main->meta();
        $data["index"]  = "";
        $data["title"]  = "Pipesys - Manage business processes more easily with Pipesys (POS)";
        $data['device'] = $device;
        $data["content"]  = "frontend_new/page/home_horizontal";
        $this->load->view("frontend_new/index_horizontal",$data);
    }

    function buy_voucher($page=""){
        if($this->input->post("Status") == "complete"):
            $this->main->session_voucher_reset();
            redirect($this->agent->referrer());
        endif;
        $this->load-> library('Mobile_Detect');
        $detect = new Mobile_Detect();
        if ($detect->isMobile() || $detect->isTablet() || $detect->isAndroidOS()) {
            $device = "mobile";
        } else {        
            $device = "web";
        }
        $data["meta"]   = $this->main->meta();
        $data["index"]  = "";
        $data["title"]  = "Pipesys - Manage business processes more easily with Pipesys (POS)";
        $data['device'] = $device;
        $data["content"]  = "page/buy_voucher";
        $this->load->view("frontend_new/index",$data);
    }

    public function bahasa($bahasa){
        $this->frontend->bahasa($bahasa);
    }
}