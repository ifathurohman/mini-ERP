<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_content extends CI_Model {
    
    var $table = "Content";
    var $column = array(
        'ContentID',
        'Date',
        'Author',
        'Name',
        'Status'
    );
    var $order  = array('ContentID' => 'desc'); // default order

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
            ContentID,
            Date,
            Author,
            Name,
            Status,
            Image,
        ");
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
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {
        $this->db->select("
            ContentID,
            Image,
            Author,
            Name,
            Description,
            Status,
            Category,
            Date,
        ");
        $this->db->from($this->table);
        $this->db->where('ContentID',$id);
        $query = $this->db->get();
        return $query->row();
    }

    public function save($data)
    {
        $this->db->set("UserAdd",$this->session->userdata("NAMA"));
        $this->db->set("DateAdd",date("Y-m-d H:i:s"));
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($data, $id){
        $this->db->set("UserCh",$this->session->userdata("NAMA"));
        $this->db->set("DateCh",date("Y-m-d H:i:s"));
        $this->db->where("ContentID", $id);
        $this->db->update($this->table, $data);
    }

    public function label_status($status){
        if($status == 1):
            $status = '<span class="info-green">'.$this->lang->line('lb_publish').'</span>';
        else:
            $status = '<span class="info-red">'.$this->lang->line('lb_unpublish').'</span>';
        endif;

        return $status;
    }

    public function delete_by_id($id)
    {
        $this->db->where('ContentID', $id);
        $this->db->delete($this->table);
    }

    public function delete_img($where="")
    {
        $this->db->select("Image");
        $this->db->where($where);
        $query      = $this->db->get($this->table)->row();
        $gambar_url = base_url($query->Image);
        if(!empty($query->Image)):
            $root       = explode(base_url(), $gambar_url)[1];
            $headers = @get_headers($gambar_url);
            if (preg_match("|200|", $headers[0])) {
                unlink('./' . $root);
            } 
        endif;
    }

    public function faq_list(){
        $this->select();
        $page = $this->input->post("page");
        if($this->session->app):
            $this->db->where("App", 'salespro');
        else:
            $this->db->where("App", 'salespro');
        endif;
        $this->db->where("Status", 1);
        $this->db->group_start();
        $this->db->like("Category", $page);
        $this->db->or_like('Category','Faq');
        $this->db->group_end();
        $this->db->order_by("ContentID");

        $query = $this->db->get($this->table);

        return $query->result();
    }

    public function top(){
        $this->db->select("
            ContentID,
            Name,
            Image,
            ");
        $this->db->where("Status", 1);
        $this->db->limit(4);
        $this->db->order_by("ContentID", "desc");
        $query = $this->db->get($this->table);

        return $query;
    }

    public function blog_list($page=""){
        $pagenum    = $this->input->post("pagenum");
        if(empty($pagenum) || $pagenum == 1):
          $pagenum  = 1;
        endif;
        $pagestart = 6;
        $pagenum = $pagenum - 1;
        $pagenum = ($pagenum * $pagestart)+4;

        $this->select();

        $this->db->where("Status", 1);
        $this->db->like('Category', 'Article');
        $this->db->order_by("ContentID", "desc");
        if($page != "total_data"):
            $this->db->limit($pagestart,$pagenum);
        endif;
        $query = $this->db->get($this->table);
        if($page == "total_data"):
            return $query->num_rows();
        else:
            return $query->result();
        endif;
    }

    public function relpace_root($id,$str){
        $search = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]"," ",".");
        $str = $id."-".str_replace($search, "-", $str);
        $str = site_url('blog/').$str;
        return $str;
    }

    private function select(){
        $this->db->select("
            ContentID,
            Name,
            Image,
            Date,
            Description,
        ");
    }
}