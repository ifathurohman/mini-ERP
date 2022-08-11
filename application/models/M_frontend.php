<?php
defined('BASEPATH') OR exit('No direct script access allowed');
#tanggal 2019-07-09
#author m iqbal ramadhan
class M_frontend extends CI_Model {
    var $SiteTitle = 'Pipesys';
    var $Logo      = 'img/logo.png';
    var $Icon      = 'img/icon.png';
    var $TimeZone  = 'Asia/Jakarta';
    public function __construct()
    {
        parent::__construct();
        $this->set_('TimeZone');
        $this->Logo = site_url($this->Logo);
        $this->Icon = site_url($this->Icon);
        $this->load->language('bahasa',$this->session->bahasa);
    }
    public function bahasa($bahasa){
        $this->session->set_userdata("bahasa",$bahasa);
        redirect($_SERVER['HTTP_REFERER']);
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

        // if($Type == "Code"):
        //     $this->db->where("Code",$Search);
        // else:
        //     $this->db->where("GeneralID",$Search);
        // endif;
        // $query = $this->db->get("UT_General");
        // return $query->row();
        // SAP Business One Indonesia Bandung, Absensi Sales Tracking, Erp, RC Electronic, CV Jl. Indrayasa No.158 - 160, Cibaduyut, Bojongloa Kidul, Kota Bandung, Jawa Barat 40236 
        // SAP Business One Indonesia Bandung, Absensi Sales Tracking, Erp, RC Electronic, CV Support@rcelectronic.co.id 
        // SAP Business One Indonesia Bandung, Absensi Sales Tracking, Erp, RC Electronic, CV (+62)2287787325 
        // SAP Business One Indonesia Bandung, Absensi Sales Tracking, Erp, RC Electronic, CV (+62)8122059327 
        // SAP Business One Indonesia Bandung, Absensi Sales Tracking, Erp, RC Electronic, CV (+62)8112199050

        $value = "";
        if($Search == "CompanyAddress"):
            $value = 'Jl. Indrayasa No.158 - 160, Cibaduyut, Bojongloa Kidul, Kota Bandung, Jawa Barat 40236 ';
        elseif($Search == "EmailTechnical"):
            $value = 'Support@rcelectronic.co.id';
        elseif($Search == "CompanyTelephone"):
            $value = '(+62)2287787325 ';
        elseif($Search == "CompanyWhatsapp1"):
            $value = '(+62)8112199050';
        elseif($Search == "CompanyWhatsapp2"):
            $value = '(+62)8112199050';
        endif;

        $data = array();
        $data['Value'] = $value;
        $data = json_encode($data);
        $data = json_decode($data);
        return $data;
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
    public static function link($text,$page=""){
      $text = preg_replace('~[^\pL\d]+~u', '-', $text);
      $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
      $text = preg_replace('~[^-\w]+~', '', $text);
      $text = trim($text, '-');
      $text = preg_replace('~-+~', '-', $text);
      $text = strtolower($text);
      if (empty($text)) {
        return 'n-a';
      }
      if($page):
        return $text;
      else:
        return $text.".html";
      endif;
    }
    public function telp($text,$add = ""){
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '', $text);
        $text = strtolower($text);
        return $text;
    }
    public function tanggal($format, $tanggal="now", $bahasa="id"){
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
     // if($this->session->bahasa != "indonesia"):
     //    $bahasa = "eng";
     // endif;
     return str_replace($en,$bahasa,date($format,strtotime($tanggal)));
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
    public function get_image_profile(){
        $img = $this->session->Image;
        if($img == null):
            $img = "assets/images/users/avatar-1.jpg";
        endif;

        return $img;
    }
    public function Category($Modul = ""){
        // if($Modul == "list" || $Modul == "list_active"):
        //     $this->db->select("CategoryID,Link,Name,Icon");
        //     if($Modul == "list_active"):
        //         $this->db->where("Active",1);
        //     endif;
        //     $query = $this->db->get("Category");
        //     $data = $query->result();
        // endif;
        $data = array();
        return $data;
    }
    function character_limiter($str, $n = 500, $end_char = '&#8230;')
    {
        if (strlen($str) < $n)
        {
            return $str;
        }

        $str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

        if (strlen($str) <= $n)
        {
            return $str;
        }

        $out = "";
        foreach (explode(' ', trim($str)) as $val)
        {
            $out .= $val.' ';

            if (strlen($out) >= $n)
            {
                $out = trim($out);
                return (strlen($out) == strlen($str)) ? $out : $out.$end_char;
            }
        }
     }

    #custom
    public function list_link($page){
        $links = array();
        if($page == "main_menu"):
            $links[] = array('type' => 'home','link' => site_url(), "name" => "Home");
            // $links[] = array('type' => '','link' => '#about-us', "name" => "Tentang Kami");
            $links[] = array('type' => 'fitur','link' => '#fitur', "name" => $this->lang->line('features'));
            $links[] = array('type' => 'harga','link' => '#harga', "name" => $this->lang->line('price'));
            if($this->session->login):
            $links[] = array('type' => '','link' => site_url('dashboard'), "name" => "Dashboard");
            else:
            $links[] = array('type' => '','link' => site_url('login'), "name" => $this->lang->line('login'));
            $links[] = array('type' => '','link' => site_url('register'), "name" => $this->lang->line('try_free'));
            endif;
        elseif($page == "bahasa"):
            //$links[] = array('icon' => 'flag-icon flag-icon-id','link' => site_url('main/bahasa/indonesia'), "name" => "Bahasa Indonesia");        
            $links[] = array('icon' => 'flag-icon flag-icon-us','link' => site_url('main/bahasa/english'), "name" => "English");        
        elseif($page == "another_product"):
            $links[] = array('link' => 'javascript:;', "name" => "SAP Business One Indonesia Bandung");
            $links[] = array('link' => 'javascript:;', "name" => "People Shape Sales");
            $links[] = array('link' => 'javascript:;', "name" => "People Shape");
            $links[] = array('link' => 'javascript:;', "name" => "Amazon");
            $links[] = array('link' => 'javascript:;', "name" => "Custom App");
            $links[] = array('link' => 'javascript:;', "name" => "CCTV");
        elseif($page == "social_media"):
            $links[] = array(
                'link' => $this->set_('SocialFacebook'), 
                'name' => 'Facebook', 
                'icon' => 'fab fa-facebook-f');
            $links[] = array(
                'link' => $this->set_('SocialInstagram'), 
                'name' => 'Instagram', 
                'icon' => 'fab fa-instagram');
            $links[] = array(
                'link' => $this->set_('SocialLinkedIn'), 
                'name' => 'LinkedIn', 
                'icon' => 'fab fa-linkedin-in');
        elseif($page == "fitur"):
            $links[] = array(
                'img'   => base_url('aset/frontend/img/aplikasi-penjualan-aplikasi-gudang-program-penjualan-accounting-software-dashboard.png'), 
                'link'  => site_url('login'), 
                'title' => $this->lang->line('fitur_dashboard'),
                'text'  => $this->lang->line('fitur_dashboard_txt')
            );
            $links[] = array(
                'img'   => base_url('aset/frontend/img/aplikasi-penjualan-aplikasi-gudang-program-penjualan-accounting-software-payment.jpg'), 
                'link'  => site_url('login'), 
                'title' => $this->lang->line('fitur_purchase'),
                'text'  => $this->lang->line('fitur_purchase_txt')
            );
            $links[] = array(
                'img'   => base_url('aset/frontend/img/aplikasi-penjualan-aplikasi-gudang-program-penjualan-accounting-software-selling.jpg'), 
                'link'  => site_url('login'), 
                'title' => $this->lang->line('fitur_sales'),
                'text'  => $this->lang->line('fitur_sales_txt')
            );
            $links[] = array(
                'img'   => base_url('aset/frontend/img/aplikasi-penjualan-aplikasi-gudang-program-penjualan-accounting-software-warehouse.png'), 
                'link'  => site_url('login'), 
                'title' => $this->lang->line('fitur_stock'),
                'text'  => $this->lang->line('fitur_stock_txt')
            );
            $links[] = array(
                'img'   => base_url('aset/frontend/img/aplikasi-penjualan-aplikasi-gudang-program-penjualan-accounting-software-accounting.png'), 
                'link'  => site_url('login'), 
                'title' => $this->lang->line('fitur_accounting'),
                'text'  => $this->lang->line('fitur_accounting_txt')
            );
        elseif($page == "fitur_one"):
            $links[] = array(
                'img'   => base_url('aset/frontend/img/aplikasi-penjualan-aplikasi-gudang-program-penjualan-accounting-software-dashboard.png'), 
                'link'  => site_url('login'), 
                'title' => $this->lang->line('fitur_dashboard'),
                'text'  => $this->lang->line('fitur_dashboard_txt')
            );
            $links[] = array(
                'img'   => base_url('aset/frontend/img/aplikasi-penjualan-aplikasi-gudang-program-penjualan-accounting-software-payment.jpg'), 
                'link'  => site_url('login'), 
                'title' => $this->lang->line('fitur_purchase'),
                'text'  => $this->lang->line('fitur_purchase_txt')
            );
            $links[] = array(
                'img'   => base_url('aset/frontend/img/aplikasi-penjualan-aplikasi-gudang-program-penjualan-accounting-software-selling.jpg'), 
                'link'  => site_url('login'), 
                'title' => $this->lang->line('fitur_sales'),
                'text'  => $this->lang->line('fitur_sales_txt')
            );
        elseif($page == "fitur_two"):
            $links[] = array(
                'img'   => base_url('aset/frontend/img/aplikasi-penjualan-aplikasi-gudang-program-penjualan-accounting-software-warehouse.png'), 
                'link'  => site_url('login'), 
                'title' => $this->lang->line('fitur_stock'),
                'text'  => $this->lang->line('fitur_stock_txt')
            );
            $links[] = array(
                'img'   => base_url('aset/frontend/img/aplikasi-penjualan-aplikasi-gudang-program-penjualan-accounting-software-accounting.png'), 
                'link'  => site_url('login'), 
                'title' => $this->lang->line('fitur_accounting'),
                'text'  => $this->lang->line('fitur_accounting_txt')
            );
        elseif($page == "harga"):
            $modul_txt  = $this->lang->line('module');
            $max_txt    = $this->lang->line('max');
            $person_txt = $this->lang->line('person');
            $month_txt  = $this->lang->line('month');
            $year_txt   = $this->lang->line('year');
            $user_txt   = $this->lang->line('user');

            $footer     = array('1 '.$modul_txt.' : '.$max_txt.' 1 '.$person_txt,'2 '.$modul_txt.' : '.$max_txt.' 3 '.$person_txt,'3 '.$modul_txt.' : '.$max_txt.' 5 '.$person_txt, ' 4 '.$modul_txt.' : '.$max_txt.' 7 '.$person_txt);

            $links[] = array(
                'link' => site_url('login'), 
                'title' => 'Basic', 
                "name" => '<i class="far fa-calendar-alt"></i> '.'3 '.$month_txt,
                'active' => '',
                "harga" => 'Rp. 69.000', 'text' => '/ '.$month_txt.' / '.$modul_txt,
                'footer' => $footer);
            $links[] = array(
                'link' => site_url('login'), 
                'title' => 'Medium', 
                "name" => '<i class="far fa-calendar-alt"></i> '.'6 '.$month_txt,
                'active' => 'active',
                "harga" => 'Rp. 59.000', 'text' => '/ '.$month_txt.' / '.$modul_txt,
                'footer' => $footer);
            $links[] = array(
                'link' => site_url('login'), 
                'title' => 'Premium', 
                "name" => '<i class="far fa-calendar-alt"></i> '.'1 '.$year_txt,
                'active' => '',
                "harga" => 'Rp. 49.000', 'text' => '/ '.$month_txt.' / '.$modul_txt,
                'footer' => $footer);
            $links[] = array(
                'link' => site_url('login'), 
                'title' => $this->lang->line('additional'),
                "name" => '<i class="fa fa-user"></i> '.$this->lang->line('additional'),
                'active' => '',
                "harga" => 'Rp. 19.900', 'text' => '/ '.$month_txt.' / '.$user_txt,
                'footer' => array());
        endif;
        $links = json_encode($links);
        $links = json_decode($links);
        return $links;
    }
    function Slideshow(){

        $data = array();
        $data[] = array(
            'Image' => site_url("aset/frontend/img/aplikasi-penjualan-aplikasi-gudang-program-penjualan-accounting-software-bg.jpg"), 
            'Link'  => site_url('register'),
            'NameColor'     => '', 
            'Name'          => $this->lang->line('slide_1'),
            'Description'   => $this->lang->line('slogan'), 
            'Position'      => 'left', 
            'ButtonLink'    => array(array('BtnName' => $this->lang->line('free_trial'),'BtnLink' => site_url('register'),'BtnColor' => ''))
        );
        

        $data = json_encode($data);
        $data = json_decode($data);
        return $data;
    }
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
}