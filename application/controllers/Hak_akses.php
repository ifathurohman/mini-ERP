<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hak_akses extends CI_Controller {
	var $title = 'User Privileges';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model("M_hak_akses",'hak_akses');
		$this->main->cek_session();
	}
	public function index()
	{
		$url 						= $this->uri->segment(1); 
		$id_url 					= $this->main->id_menu($url);
		$read 						= $this->main->read($id_url);
		// if($read == 0){ redirect(); }
		$hak_akses_tambah 			= $this->main->menu_tambah($id_url);
		if($hak_akses_tambah > 0):
           $tambah = $this->main->general_button('add', "Add New ".$this->title);
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges
		$array = array(
			array('management','Management'),
			array('administrasi','Administration'),
			array('master',"Master"),
			array('transaction',"Transaksi"),
			array('report',"Laporan"),
			array('setting',"Setting"),
		);
		$data['array']			= $array;
		$data['url'] 			= 'list-hak_akses';
		$data['title']  		= 'User Privileges';
		
		$data['tambah'] 		= $tambah;
		$data['modal'] 			= 'hak_akses/modal';
		$data['page'] 			= 'hak_akses/list';
		$this->load->view('index',$data);
	}
	//---------------------------------------------------------------------------------------
	public function ajax_list()
	{
		$url 	= $this->uri->segment(1); 
		$id_url = $this->main->id_menu($url);
		$list 	= $this->hak_akses->get_datatables();
		$data 	= array();
		$no 	= $_POST['start'];
		$i 		= 1;
		foreach ($list as $hak_akses) {
			$hak_akses_ubah 	= $this->main->menu_ubah($id_url);
			$hak_akses_hapus 	= $this->main->menu_hapus($id_url);
			if($hak_akses_ubah > 0):
           	$ubah = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="Edit" onclick="edit('."'".$hak_akses->id_hak_akses."'".','."'".$hak_akses->nama_hak_akses."'".')">Edit</a>';
			else:
				$ubah = ""; 
			endif;
			$button = '<div class="btn-group btn-group-xs" aria-label="Basic example" role="group">';
            $button .= $ubah;
            // $button .= $hapus;
            $button .= '</div>';

			$no++;
			$row = array();
			$row[] = $i++;
			$row[] = $hak_akses->nama_hak_akses;
			$row[] = $button;		
			$data[] = $row;
		}

		$output = array(
						"draw"  		  => $_POST['draw'],
						"recordsTotal" 	  => $this->hak_akses->count_all(),
						"recordsFiltered" => $this->hak_akses->count_filtered(),
						"data"			  => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->hak_akses->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function simpan()
	{
		// $this->_validate();
		$data = array(
			'app' 				=> $this->session->app,
			'nama_hak_akses' 	=> $this->input->post("nama_hak_akses"),
			'hak_akses' 		=> str_replace(" ", "_", strtolower($this->input->post("nama_hak_akses"))),
			'menu' 				=> json_encode($this->input->post('menu')),
			'tambah' 	 		=> json_encode($this->input->post('tambah')),
			'ubah' 	 			=> json_encode($this->input->post('ubah')),
			'hapus' 	 		=> json_encode($this->input->post('hapus')),
		);
		$insert = $this->hak_akses->save($data);
		echo json_encode(array("status" => TRUE,"pesan" => "yoi"));
	}
	public function ajax_update()
	{
		$data 		= array(
			'nama_hak_akses' => $this->input->post("nama_hak_akses"),
			'hak_akses' 		=> str_replace(" ", "_", strtolower($this->input->post("nama_hak_akses"))),
			'menu' 			=> json_encode($this->input->post('menu')),
			'tambah' 	 	=> json_encode($this->input->post('tambah')),
			'ubah' 	 		=> json_encode($this->input->post('ubah')),
			'hapus' 	 	=> json_encode($this->input->post('hapus')),
		);
		$this->hak_akses->update(array('id_hak_akses' => $this->input->post('id_hak_akses')), $data);
		echo json_encode(array("status" => TRUE,"pesan" => json_encode($this->input->post('menu')).json_encode($this->input->post('tambah')).json_encode($this->input->post('ubah')).json_encode($this->input->post('hapus'))));

	}

	public function ajax_delete($id)
	{
		$this->hak_akses->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('nama_hak_akses') == '')
		{
			$data['inputerror'][] = 'nama_hak_akses';
			$data['error_string'][] = 'Maaf nama hak_akses wajib di isi';
			$data['status'] = FALSE;
		}
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}
