<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_firebase extends CI_Model
{
	var $table = "FirebaseUser";
	public function __construct()
	{
		parent::__construct();
	}
	public function push_new_route_transaction($TransactionRouteID,$ID)
	{

			$this->db->where("ID",$ID);
			$query 		= $this->db->get("FirebaseUser")->result();
			$tokens 	= array();
			if(!empty($query)):
				foreach ($query as $data):
					$tokens[] 	= $data->Token;
				endforeach;
				$message 		= array(
					"TransactionRouteID"	=> $TransactionRouteID,
					"message"				=> "anda mendapatkan route baru",
					"status"				=> "new_transaction",
				);
				$message_status = $this->send_notification($tokens, $message);
			endif;
	}

	#20180427 MW
	#broadcast message salespro
	public function push_broadcast_message($Subject="", $Message="", $BranchID=""){
		$d = json_decode($BranchID);
		$query;
		if($d[0] == "all"):
			$query = $this->get_data_firebase();
		else:
			$query = $this->get_data_firebase($d);
		endif;

		$tokens 	= array();
		if(!empty($query)):
			foreach ($query->result() as $data):
				$tokens[] 	= $data->Token;
			endforeach;
			$message 		= array(
				"subject"	=> $Subject,			
				"message"	=> $Message,
				"status"	=> "broadcast",
			);
			$message_status = $this->send_notification($tokens, $message);
		endif;
	}

	#20180427 MW
	#broadcast transaction today
	public function push_broadcast_transaction($BranchID){
		$query = $this->get_all_data_firebase($BranchID, "salespro");
		$tokens 	= array();
		if(!empty($query)):
			foreach ($query->result() as $data):
				$tokens[] 	= $data->Token;
			endforeach;
			$message 		= array(
				"subject"	=> "Transaksi Hari ini",			
				"message"	=> "Daftar Transaksi Hari ini",
				"status"	=> "transaction_today",
			);
			$message_status = $this->send_notification($tokens, $message);
		endif;
	}

	public function send_notification($tokens, $message)
	{
		$url 	= 'https://fcm.googleapis.com/fcm/send';
		$fields = array(
		'registration_ids' 	=> $tokens,
		'data' 				=> $message
		);
		$headers = array(
			'Authorization: key='.$this->config->item("firebase_api"),
			'Content-Type: application/json'
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($ch);           
		if ($result === FALSE) {
		   die('Curl failed: ' . curl_error($ch));
		}
		curl_close($ch);
		return $result;
	}

	public function get_data_firebase($data = ""){
		$this->db->select("f.Token");
		$this->db->join("Branch as b", "f.ID = b.BranchID");
		$this->db->where("b.CompanyID", $this->session->CompanyID);
		$this->db->where("f.App", $this->session->app);
		if($data):
			$this->db->where_in("f.ID", $data);
		else:
			$this->db->where("b.Active", 1);
		endif;
		$query = $this->db->get($this->table." as f");

		return $query;
	}

	public function get_all_data_firebase($data = "", $App = ""){
		$this->db->select("f.Token");
		$this->db->join("Branch as b", "f.ID = b.BranchID");
		$this->db->where("f.App", $App);
		$this->db->where_in("f.ID", $data);
		$query = $this->db->get($this->table." as f");
		return $query;
	}
}