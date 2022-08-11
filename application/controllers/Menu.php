<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {
	var $title = 'Menu & Page';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_menu","menu");
		$this->main->cek_session();
	}
	public function index()
	{
		$url 						= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url);
		$read 						= $this->main->read($id_url);
		if($read == 0){ redirect(); }
		$menu_tambah 				= $this->main->menu_tambah($id_url);
		if($menu_tambah > 0):
           $tambah = $this->main->general_button('add', "Add New ".$this->title);
		else: 
			$tambah = ""; 
		endif;
		$data['tambah'] = $tambah;
		#ini untuk aturan tambah user privilage
		
		$data['url'] 	= 'list-menu';
		$data['title']  = 'Menu & Page';
		$data['modal']	= 'menu/modal';
		$data['page'] 	= 'menu/list';
		$this->load->view('index',$data);
	}
	//---------------------------------------------------------------------------------------
	public function ajax_list()
	{	
		$url 						= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url);
		#ini aturan untuk CRUD

		$list 	= $this->menu->get_datatables();
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		foreach ($list as $menu) {
			$menu_ubah 				= $this->main->menu_ubah($id_url);
			$menu_hapus 			= $this->main->menu_hapus($id_url);
			if($menu_ubah > 0){
           	$ubah = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="Edit" onclick="edit('."'".$menu->id_menu."'".')">Edit</a>';
			} else { $ubah = ""; }
			if($menu_hapus > 0){
           	$hapus = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="Hapus" onclick="hapus('."'".$menu->id_menu."'".')">Delete</a>';
			} else { $hapus = ""; }
			#ini aturan untuk crud
		
			$button = '<div class="btn-group btn-group-xs" aria-label="Basic example" role="group">';
            $button .= $ubah;
            $button .= $hapus;
            $button .= '</div>';
			$no++;
			$row = array();

			$modul  = '';
			if($menu->modul):
				foreach (json_decode($menu->modul) as $a) {
					$modul .= $this->main->label_modul($a).", ";
				}
			endif;
			$modul2 = '';
			if($menu->modul2):
				foreach (json_decode($menu->modul2) as $a) {
					$modul2 .= $this->main->label_modul2($a).", ";
				}
			endif;
			
			$row[] 	= $i++;
			$row[]	= '<input type="text" style="max-width:100px;" onkeyup="numberonly(this)" maxlength="10" class="angka" onchange="set_index(this,'."'".$menu->id_menu."'".')" value="'.$menu->index2.'">';
			$row[] 	= "<a href='".site_url($menu->url)."'>".$menu->nama_menu."</a>";
			$row[] 	= $menu->url;
			$row[] 	= $menu->root;
			$row[] 	= $menu->kategori;
			$row[] 	= $modul;
			$row[] 	= $modul2;
			$row[] 	= $button;
			$data[] = $row;
		}
		$output = array(
			"draw" 				=> $_POST['draw'],
			"recordsTotal" 		=> $this->menu->count_all(),
			"recordsFiltered" 	=> $this->menu->count_filtered(),
			"data" 				=> $data,
		);
		echo json_encode($output);
	}
	public function ajax_edit($id)
	{

		$a 	= $this->menu->get_by_id($id);
		$output = array(
			"id_menu"	=> $a->id_menu,
			"nama_menu"	=> $a->nama_menu,
			"url"		=> $a->url,
			"kategori"	=> $a->kategori,
			"root"		=> $a->root,
			"modul"		=> json_decode($a->modul),
			"modul2"	=> json_decode($a->modul2),
			"app"		=> json_decode($a->app),
			"type"		=> $a->type,
		);
		echo json_encode($output);
	}

	public function simpan()
	{
		$this->_validate();
		$app  = $this->input->post("app");

		if($this->input->post('modul')):
			$modul = $this->input->post('modul');
			$modul = json_encode($modul);
		else:
			$modul = null;
		endif;
		if($this->input->post("modul2")):
			$modul2 = $this->input->post('modul2');
			$modul2 = json_encode($modul2);
		else:
			$modul2 = null;
		endif;

		$type = 0;
		if($this->input->post('kategori') == "report"):
			$type = $this->input->post('type');
		endif;

		$data = array(
				'nama_menu' => $this->input->post('nama_menu'),
				'url' 		=> $this->input->post('url'),
				'kategori' 	=> $this->input->post('kategori'),
				'root' 		=> $this->input->post('root'),
				"modul"		=> $modul,
				"modul2"	=> $modul2,
				'app'		=> json_encode($app),
				'type'		=> $type,
			);
		$insert = $this->menu->save($data);
		echo json_encode(array("status" => TRUE));
	}
	public function ajax_update()
	{
		$this->_validate();
		$app  = $this->input->post("app");
		if($this->input->post('modul')):
			$modul = $this->input->post('modul');
			$modul = json_encode($modul);
		else:
			$modul = null;
		endif;
		if($this->input->post("modul2")):
			$modul2 = $this->input->post('modul2');
			$modul2 = json_encode($modul2);
		else:
			$modul2 = null;
		endif;

		$type = 0;
		if($this->input->post('kategori') == "report"):
			$type = $this->input->post('type');
		endif;

		$data = array(
				'nama_menu' => $this->input->post('nama_menu'),
				'url' 		=> $this->input->post('url'),
				'kategori' 	=> $this->input->post('kategori'),
				'root' 		=> $this->input->post('root'),
				"modul"		=> $modul,
				"modul2"	=> $modul2,
				'app'		=> json_encode($app),
				'type'		=> $type,
			);
		$this->menu->update(array('id_menu' => $this->input->post('id_menu')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->menu->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('nama_menu') == '')
		{
			$data['inputerror'][] = 'nama_menu';
			$data['error_string'][] = 'Maaf nama menu wajib di isi';
			$data['status'] = FALSE;
		}

		if($this->input->post('url') == '')
		{
			$data['inputerror'][] = 'url';
			$data['error_string'][] = 'Maaf url wajib di isi';
			$data['status'] = FALSE;
		}
		if($this->input->post('root') == '')
		{
			$data['inputerror'][] = 'root';
			$data['error_string'][] = 'Maaf method wajib di isi';
			$data['status'] = FALSE;
		}
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	public function set_index(){
		$hakakses = $this->session->hak_akses;

		$ID   	= $this->input->post("ID");
		$index 	= $this->input->post("index");

		$status  = false;
		$message = 'data not found';

		if($hakakses == "super_admin"):
			$cek = $this->db->count_all("menu where id_menu = '$ID'");
			if($cek>0):
				if(!$index):
					$index = null;
				endif;
				$data = array(
					"index"	=> $index,
				);
				$this->menu->update(array('id_menu' => $ID), $data);

				$status = TRUE;
				$message = "success";
			endif;
		endif;

		$output = array(
			"status"	=> $status,
			"message"	=> $message,
		);

		$this->main->echoJson($output);
	}

}
