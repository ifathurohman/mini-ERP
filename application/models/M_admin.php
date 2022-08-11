<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_admin extends CI_Model {

	var $table = 'user';
	var $column1 = array('user.id_user',"user.email","user.first_name","user.last_name","user.phone",'user.DeviceID'); //set column field database for order and search
	var $column2 = array('user.id_user',"user.email","user.nama","user.phone","user.ParentID"); //set column field database for order and search
	var $order = array('user.index' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($page = "")
	{
		$this->db->select("
			user.id_user 	as id_user,
			user.ParentID 	as ParentID,
			user.nama 		as nama,
			user.email 		as email,
			user.first_name as first_name,
			user.last_name 	as last_name,
			user.phone 		as phone,
			user.status 	as  status,
			user.status 	as  active,
			user.hak_akses 	as hak_akses,
			user.store 		as store,
			user.DeviceID 	as deviceid,
			user.DeviceToken 	as devicetoken,
			user.ExpireAccount 	as ExpireAccount,
			user.VoucherExpireDate,
			user.index 		as user_index,
			u.nama 			as super_admin,
		");
		$this->db->join("user as u", "u.id_user = user.ParentID", "left");
		$this->db->where_in("user.App", array('pipesys','all'));
		if($page == "company"):
			$this->db->where("user.hak_akses","company");
		else:
			$this->db->where("user.App",$this->session->app);
			$this->db->group_start();
			$this->db->where("user.CompanyID",$this->session->companyid);
			$this->db->or_where("user.id_user",$this->session->companyid);
			$this->db->group_end();
		endif;
		$this->db->from($this->table);
		if($page == "company"):
			$column = $this->column2;
		else:
			$column = $this->column1;
		endif;

		$i = 0;
		foreach ($column as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND. 
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($column) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$column[$i] = $item; // set column array variable to order processing
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables($page = "")
	{
		$this->_get_datatables_query($page);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($page = "")
	{
		$this->_get_datatables_query($page);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($page = "")
	{
		if($page == "company"):
			$this->db->where("user.hak_akses","company");
		else:
			$this->db->where("App",$this->session->app);
			$this->db->group_start();
			$this->db->where("CompanyID",$this->session->companyid);
			$this->db->or_where("id_user",$this->session->companyid);
			$this->db->group_end();
		endif;
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		// $this->db->where("App",$this->session->app);
		$this->db->where('id_user',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function save($data)
	{
		$this->db->set("user_add",$this->session->userdata("NAMA"));
		$this->db->set("date_add",date("Y-m-d H:i:s"));
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->set("user_ch",$this->session->userdata("NAMA"));
		$this->db->set("date_ch",date("Y-m-d H:i:s"));
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('id_user', $id);
		$this->db->delete($this->table);
	}

	//20180521 MW
	//list all company
	public function get_company(){
		$this->db->select("
			id_user,
			nama,
			");
		$this->db->where("hak_akses","company");
		$this->db->or_where("hak_akses","super_admin");
		$query = $this->db->get($this->table);

		return $query;
	}
}
