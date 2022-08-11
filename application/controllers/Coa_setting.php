<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coa_setting extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->main->cek_session();
	}

	public function index()
	{	
		$url 	= $this->uri->segment(1); 
		$id_url = $this->main->id_menu($url);
		$read 	= $this->main->read($id_url);
		if($read == 0){ redirect(); }
		$add 		= $this->main->menu_tambah($id_url);
		$edit 		= $this->main->menu_ubah($id_url);
		$delete 	= $this->main->menu_hapus($id_url);
		#ini untuk session halaman aturan user privileges;
		$data['title']  		= $this->lang->line('lb_coa_setting');
		$data['modal_coa'] 		= 'modal/modal_coa';
		$data['page'] 			= 'page/coa_setting';
		$data['modul'] 			= 'coa_setting';
		$data['add'] 			= $add;
		$data['delete'] 		= $delete;
		$data['edit'] 			= $edit;
		$data['url_modul'] 		= $url;
		$this->load->view('index',$data);
	}
}