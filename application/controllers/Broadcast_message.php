<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Broadcast_message extends CI_Controller {
	var $CompanyID;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->CompanyID = $this->session->CompanyID;
		$this->load->model("M_broadcast_message", "broadcast_message");
		$this->load->model("M_branch", "branch");
	}
	
	public function echoJson($data){
		header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);
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
            $tambah = '<button type="button" class="btn btn-primary btn-outline" onclick="tambah()" ><i class="fa fa-plus"></i> Add New Message</button>';
		else: 
			$tambah = ""; 
		endif;
		#ini untuk session halaman aturan user privileges;
		$data['title']  	= 'Broadcast Message to Employee';
		$data['tambah'] 	= $tambah;
		$data['page'] 		= 'broadcast_message/list';
		$data['modal'] 		= 'broadcast_message/modal';
		$data['modul'] 		= 'broadcast_message';
		$data["sales"]		= $this->main->branch("","",1);
		$this->load->view('index',$data);
	}

	public function ajax_list($page ="")
	{
		$url 	= $this->uri->segment(1); 
		$id_url = $this->main->id_menu($url);
		$list 	= $this->broadcast_message->get_datatables($page);
		$data 	= array();
		$no 	= $this->input->post("start");
		$i 		= 1;
		foreach ($list as $a) {
			$transaction_route_ubah 	= $this->main->menu_ubah($id_url);
			if($transaction_route_ubah > 0):
           		$ubah = '<a href="javascript:void(0)" type="button" class="btn btn-outline btn-default" title="Edit" onclick="edit('."'".$a->BroadcastID."'".')">view detail</a>';
			else:
				$ubah = ""; 
			endif;
			// if($transaction_route->position == 1): $hapus = ""; endif;

			$button  = '<div class="btn-group btn-group-xs" aria-label="Basic example" role="group">';
            $button .= $ubah;
            $button .= '</div>';
			
			$no++;
			$row 	= array();
			$row[] 	= $i++;
			$row[] 	= $a->Subject;
			$row[] 	= $a->DateAdd;
			$row[] 	= $button;		
			$data[] = $row;
		}
		$output = array(
			"draw"  		  => $this->input->post("draw"),
			"recordsTotal" 	  => $this->broadcast_message->count_all($page),
			"recordsFiltered" => $this->broadcast_message->count_filtered($page),
			"data"			  => $data,
		);
		echo json_encode($output);
	}

	public function simpan(){
		$this->main->cek_session();
		$this->validate();

		$Subject 		= $this->input->post("Subject");
		$Message 		= $this->input->post("Message");
		$Sales 	 		= $this->input->post("Sales");
		$Select_sales	= $this->input->post("Select_sales");

		$data 	= array(
			"CompanyID" => $this->session->CompanyID,
			"Subject"	=> $Subject,
			"Message"	=> $Message,
			"Date"		=> date("Y-m-d H:i:s"),
			);
		if(!$Sales):
			$data["BranchID"] = json_encode($Select_sales);
		else:
			$data["BranchID"] = json_encode(array('all'));
		endif;
		
		$this->broadcast_message->save($data);
		$this->firebase->push_broadcast_message($Subject, $Message, $data["BranchID"]);

		$res["status"] 		= true;
		$this->echoJson($res);
	}

	public function ajax_edit($id)
	{
		$a 				= $this->broadcast_message->get_by_id($id);
		
		$data = array(
			"BroadcastID"	=> $a->BroadcastID,
			"Subject" 		=> $a->Subject,
			"Message" 		=> $a->Message,
			"hakakses"		=> "super_admin",

		);

		$BranchID = json_decode($a->BranchID);
		if($BranchID[0] == "all"):
			$data["All"] 		= true;
		else:
			$data["All"] 		= false;
			$data["Sales"]		= array();
			foreach ($BranchID as $d) {
				$Branch = $this->branch->get_by_id($d);
				$Name 	= $Branch->Name;

				array_push($data["Sales"], $Name);
			}
		endif;
        $this->echoJson($data);
	}

	private function validate(){
		$data 					= array();
		$data['error_string'] 	= array();
		$data['inputerror'] 	= array();
		$data['status'] 		= TRUE;
		
		if($this->input->post('Subject') == '')
		{
			$data['inputerror'][] 	= 'Subject';
			$data['error_string'][] = 'Enter Subject';
			$data['status'] 		= FALSE;
		}

		if($this->input->post('Message') == '')
		{
			$data['inputerror'][] 	= 'Message';
			$data['error_string'][] = 'Enter Message';
			$data['status'] 		= FALSE;
		}

		if(!$this->input->post('Sales'))
		{
			if($this->input->post('Select_sales') == ''){
				$data['inputerror'][] 	= 'Select_sales';
				$data['error_string'][] = 'Select Sales';
				$data['status'] 		= FALSE;
			}
		}
		
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	public function broadcastAll(){
		$time = date("H:i");
		if($time >= "08:30" && $time <= "08:35"):
			$query = $this->main->broadcastToday();
			if($query->num_rows()>0):
				$BranchID = array();
				foreach ($query->result() as $d) {
					$h = $d->BranchID;

					array_push($BranchID, $h);
				}
				// $this->echoJson($BranchID);
				$this->firebase->push_broadcast_transaction($BranchID);
				$res["status"] 	= true;
				$res["Message"]	= "transaksi berhasil di push";
			else:
				$res["status"] 	= false;
				$res["Message"]	= "data transaksi hari ini kosong";
			endif;
		else:
			$res["status"] 	= false;
			$res["Message"]	= "time belum sesuai";
		endif;

		$this->echoJson($res);
	}
}