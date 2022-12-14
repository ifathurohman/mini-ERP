<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_broadcast_message extends CI_Model {
    
    var $table = "Broadcast";
    var $column = array(
        'BroadcastID',
        'Broadcast.Subject',
        'Broadcast.DateAdd',
    );
    var $order  = array('BroadcastID' => 'desc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set("Asia/Jakarta");
    }

    private function _get_datatables_query($page="")
    {
        $url = $this->uri->segment(1);
        $this->db->select("
            BroadcastID,
            Subject,
            DateAdd,
        ");
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->from($this->table);
        $i = 0;
        foreach ($this->column as $item) // loop column 
        {
            if($this->input->post("search")) // if datatable send POST for search
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

    function get_datatables($page ="")
    {
        $this->_get_datatables_query($page);
        if($this->input->post("length") != -1)
        $this->db->limit($this->input->post("length"), $this->input->post("start"));
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($page ="")
    {
        $this->_get_datatables_query($page);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($page = "")
    {
        $this->db->where("CompanyID",$this->session->CompanyID);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {
        $this->db->select("
            BroadcastID,
            Subject,
            Message,
            BranchID,
        ");
        $this->db->from($this->table);
        $this->db->where('BroadcastID',$id);
        $query = $this->db->get();
        return $query->row();
    }

    public function save($data){
    	$this->db->set("UserAdd",$this->session->userdata("NAMA"));
		$this->db->set("DateAdd",date("Y-m-d H:i:s"));
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
    }
}