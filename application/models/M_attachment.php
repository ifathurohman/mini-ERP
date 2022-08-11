<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_attachment extends CI_Model {
    
    var $table = "PS_Attachment";
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set("Asia/Jakarta");
    }

    public function get_by_id($ID,$Type){
        $this->db->select("
            AttachmentID,
            ID,
            Name,
            Image,
            Remark,
            Cek,
            Type,
            ");
        $this->db->where("ID",$ID);
        $this->db->where("Type",$Type);
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function update_attachment_cek($Type,$ID)
    {
        $this->db->set("Cek",0);
        $this->db->where("Type",$Type);
        $this->db->where("ID",$ID);
        $query = $this->db->update($this->table);
        if($query): $a = 1; else: $a = 0; endif;
        return $a; 
    }

    public function delete_file($id){
        $this->db->select("AttachmentID,Image");
        $this->db->where('AttachmentID', $id);
        $query      = $this->db->get("PS_Attachment")->row();
        $gambar_url = site_url($query->Image);
        if(!empty($gambar_url)):
            $root       = explode(base_url(), $gambar_url)[1];
            $headers    = @get_headers($gambar_url);
            if (preg_match("|200|", $headers[0])) {

                if(file_exists('./' . $root)){
                    unlink('./' . $root);
                }else{

                }
            } 
        endif;
    }
}