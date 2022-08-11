<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_hak_akses extends CI_Model {

	var $table = 'hak_akses';
	var $column = array(
	'id_hak_akses',
	'nama_hak_akses',
	'menu',); //set column field database for order and search
	var $order = array('id_hak_akses' => 'desc'); // default order 
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	private function _get_datatables_query()
	{
		// $this->db->where("id_hak_akses !=",6);
		$this->db->where("id_hak_akses !=",0);
		$this->db->where("app",$this->session->app);
		// $this->db->where("id_hak_akses !=",1);
		$this->db->from($this->table);
		$i = 0;
	
		foreach ($this->column as $item) // loop column 
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

				if(count($this->column) - 1 == $i) //last loop
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
	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}
	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}
	public function count_all()
	{
		// $this->db->where("id_hak_akses !=",6);
		$this->db->where("id_hak_akses !=",0);
		$this->db->where("app",$this->session->app);
		// $this->db->where("id_hak_akses !=",1);
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('id_hak_akses',$id);
		$query = $this->db->get();

		return $query->row();
	}
	public function save($data)
	{
		// $this->db->set("user_add",$this->session->userdata("NAMA"));
		// $this->db->set("date_add",date("Y-m-d H:i:s"));
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		// $this->db->set("user_ch",$this->session->userdata("NAMA"));
		// $this->db->set("date_ch",date("Y-m-d H:i:s"));
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}
	public function delete_by_id($id)
	{
		$this->db->where('id_hak_akses', $id);
		$this->db->delete($this->table);
	}
	public function menu($kategori)
	{
		$this->db->where("kategori",$kategori);
		// $this->db->like("app",$this->session->app);
		return $this->db->get("menu")->result();
	}
}
