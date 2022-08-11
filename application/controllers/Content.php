<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Content extends CI_Controller {
	var $title = 'Content';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('upload');
		$this->load->model("M_content", "content");
		// $this->main->cek_session();
		$this->title = $this->lang->line('lb_content');
	}

	public function index()
	{
		$this->main->cek_session();
		$url 						= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url);
		$read 						= $this->main->read($id_url);
		if($read == 0){ redirect(); }
		$tambah_message 				= $this->main->menu_tambah($id_url);
		if($tambah_message > 0):
            $tambah = $this->main->general_button('add',$this->lang->line('lb_add_new').' '.$this->title);
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['title']  	= $this->title;
		$data['tambah'] 	= $tambah;
		$data['page'] 		= 'content/list';
		$data['modal'] 		= 'content/modal';
		$data['modul'] 		= 'content';
		$data["sales"]		= $this->main->branch("","",1);
		$this->load->view('index',$data);
	}
	public function faq(){
		$this->main->cek_session();

		$top = $this->top();

		$data['title']  	= $this->title;
		$data['page'] 		= 'content/faq';
		$data['modul'] 		= 'content';
		$data["top"]		= $top;
		$this->load->view('index',$data);
	}
	public function faq_list(){
		$data       = $this->content->faq_list();
        $list_data  = array();
        foreach($data as $a):
        	$link = $this->content->relpace_root($a->ContentID, $a->Name);
        	$date = $this->main->konversi_tanggal("d M Y", $a->Date);
            $img  = "img/sales_pro/content/peopleshape_default_background.png";
            if($a->Image != null):
            	$img = $a->Image;
            endif;
            $item = array(
                "ID"        	=> $a->ContentID,
                "Name"			=> $a->Name,
              	"Description"	=> $a->Description,
            );
            array_push($list_data, $item);
        endforeach;
        $output = array(
            "hakakses"      => $this->session->hak_akses,
            "list_data"     => $list_data,
        );

        $this->main->echoJson($output);

	}

	public function blog(){
		$this->main->cek_session();

		$top = $this->top();

		$data['title']  	= 'Content';
		$data['page'] 		= 'content/blog_view';
		$data['modul'] 		= 'content';
		$data["top"]		= $top;
		$this->load->view('index',$data);
	}
	public function blog_detail($id){
		$this->main->cek_session();
		$a 					= $this->content->get_by_id($id);
		$data['title']  	= 'Content';
		$data['page'] 		= 'content/blog_detail';
		$data['modul'] 		= 'content';
		$data["data"]		= $a;
		$data["Category"]	= explode(",",$a->Category);
		$this->load->view('index',$data);
	}
	public function blog_list(){
		$pagenum    = $this->input->post("pagenum");
        $data       = $this->content->blog_list();
        $total_data = $this->content->blog_list("total_data")-4;
        $list_data  = array();
        foreach($data as $a):
        	$link = $this->content->relpace_root($a->ContentID, $a->Name);
        	$date = $this->main->konversi_tanggal("d M Y", $a->Date);
            $img  = "img/sales_pro/content/peopleshape_default_background.png";
            if($a->Image != null):
            	$img = $a->Image;
            endif;
            $item = array(
                "ID"        => $a->ContentID,
                "Name"		=> $a->Name,
                "Date"		=> $date,
                "Link"		=> $link,
                "Image"		=> base_url().$img,
            );
            array_push($list_data, $item);
        endforeach;
        $output = array(
            "hakakses"      => $this->session->hak_akses,
            "total_data"    => $total_data,
            "list_data"     => $list_data,
            "pagenum"       => $pagenum

        );

        $this->main->echoJson($output);
	}

	public function top(){
		$data1 = null;
		$data2 = null;
		$data3 = null;
		$data4 = null;

		$top = $this->content->top();
		$no = 1;
		foreach ($top->result() as $d) {
			if($no == 1):
				$data1 = $top->row(0);
			elseif($no == 2):
				$data2 = $top->row(1);
			elseif($no == 3):
				$data3 = $top->row(2);
			else:
				$data4 = $top->row(3);
			endif;
			$no += 1;
		}

		$data = array(
			"1" => $data1,
			"2" => $data2,
			"3" => $data3,
			"4" => $data4,
			);
		return $data;
	}

	public function ajax_list($page ="")
	{
		$url 	= $this->uri->segment(1); 
		$id_url = $this->main->id_menu($url);
		$list 	= $this->content->get_datatables($page);
		$data 	= array();
		$no 	= $this->input->post("start");
		$i 		= 1;
		foreach ($list as $a) {
			$transaction_route_ubah 	= $this->main->menu_ubah($id_url);
			if($transaction_route_ubah > 0):
           		$ubah = $this->main->button_action("edit", $a->ContentID);
			else:
				$ubah = ""; 
			endif;
			$hapus  = $this->main->button_action("delete", $a->ContentID);	
			
			$status = $this->content->label_status($a->Status);

			$button  = '<div class="btn-group btn-group-xs" aria-label="Basic example" role="group">';
            $button .= $ubah;
            $button .= $hapus;
            $button .= '</div>';
			
			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $a->Date;
			$row[] 	= $a->Author;
			$row[] 	= $a->Name;
			$row[] 	= $status;
			$row[] 	= $button;		
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $this->input->post("draw"),
			"recordsTotal" 	  => $this->content->count_all($page),
			"recordsFiltered" => $this->content->count_filtered($page),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$a 				= $this->content->get_by_id($id);
		$data = array(
			"ContentID"		=> $a->ContentID,
			"Name"			=> $a->Name,
			"Author"		=> $a->Author,
			"Category"		=> explode(",",$a->Category),
			"Status"		=> $a->Status,
			"Description"	=> $a->Description,
			"Image"			=> $a->Image,
			"hakakses"		=> $this->session->hak_akses,

		);
        $this->main->echoJson($data);
	}

	public function simpan(){
		$this->validate();

		$name 		= $this->input->post("name");
		$category	= $this->input->post("category");
		$status 	= $this->input->post("status");
		$content 	= $this->input->post("content");
		$author 	= $this->input->post("author");
		$ContentID 	= $this->input->post("ContentID");
		$method 	= $this->input->post("method");

		$nmfile                     = $this->session->app."_".time();
        $config['upload_path']      = './img/sales_pro/content'; //path folder 
        $config['allowed_types']    = 'gif|jpg|png|jpeg|bmp|PNG|JPG'; //type yang dapat diakses bisa anda sesuaikan 
        $config['max_size']         = '99999'; //maksimum besar file 2M 
        $config['max_width']        = '99999'; //lebar maksimum 1288 px 
        $config['max_height']       = '99999'; //tinggi maksimu 768 px 
        $config['file_name']        = $nmfile; //nama yang terupload nantinya 
        $this->upload->initialize($config); 
        $upload                     = $this->upload->do_upload('image');
        $gbr                        = $this->upload->data();

		$data 		= array(
			"App"			=> $this->session->app,
			"Name"			=> $name,
			"Category"		=> $category,
			"Status"		=> $status,
			"Author"		=> $author,
			"Description"	=> $content,
			"Date"			=> date("Y-m-d"),
			);
		if($upload):        
            $image            = "img/sales_pro/content/".$gbr['file_name'];
            $data['Image']    = $image;
            if($method == "update"):
            	$this->content->delete_img(array("ContentID"=>$ContentID));
            endif;
        endif;
		if($method == "add"):
			$this->content->save($data);
		else:
			$this->content->update($data,$ContentID);
		endif;
		
		$res["status"] 	= TRUE;
		$res["upload"]	= $this->upload->display_errors();
		$this->main->echoJson($res);
	}

	private function validate(){
		$data 					= array();
		$data['error_string'] 	= array();
		$data['inputerror'] 	= array();
		$data['status'] 		= TRUE;
		if($this->input->post('name') == '')
		{
			$data['inputerror'][] 	= 'name';
			$data['error_string'][] = '';
			$data['status'] 		= FALSE;
		}
		if($this->input->post('category') == '')
		{
			$data['inputerror'][] 	= 'category';
			$data['error_string'][] = '';
			$data['status'] 		= FALSE;
		}
		if($this->input->post('author') == '')
		{
			$data['inputerror'][] 	= 'author';
			$data['error_string'][] = '';
			$data['status'] 		= FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	public function delete($id){
		$this->content->delete_img(array("ContentID"=>$id));
		$this->content->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function faq_frontend(){
		$data['title']  	= 'Content';
		$data['page'] 		= 'frontend/faq';
		$data['modul'] 		= 'content';
		$this->load->view('frontend/index',$data);
	}
}