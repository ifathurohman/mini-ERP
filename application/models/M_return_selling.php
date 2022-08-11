<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_return_selling extends CI_Model {
	var $table = 'AP_Retur';
	
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set("Asia/Jakarta");
    }

    public function save($data)
    {
        $this->db->set("User_Add",$this->session->NAMA);
        $this->db->set("Date_Add",date("Y-m-d H:i:s"));
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();  
    }
    public function update($where, $data)
    {
        $this->db->set("User_Ch",$this->session->NAMA);
        $this->db->set("Date_Ch",date("Y-m-d H:i:s"));
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }
    public function save_det($data)
    {
        $this->db->set("User_Add",$this->session->NAMA);
        $this->db->set("Date_Add",date("Y-m-d H:i:s"));
        $this->db->insert("AP_Retur_Det", $data);
        return $this->db->insert_id();  
    }
    public function update_det($where, $data)
    {
        $this->db->set("User_Ch",$this->session->NAMA);
        $this->db->set("Date_Ch",date("Y-m-d H:i:s"));
        $this->db->update("AP_Retur_Det", $data, $where);
        return $this->db->affected_rows();
    }

    public function get_by_detail($id,$iddet="",$page=""){
        $this->db->select("
            returdet.ReturDet,
            returdet.CompanyID,
            returdet.ReturNo,
            returdet.ReceiveNo,
            returdet.ReceiveDet,
            returdet.SellNo,
            returdet.SellDet,
            returdet.ProductID,
            returdet.UnitID,
            returdet.Qty,
            returdet.Conversion,
            ReceiveQty,
            returdet.KuantitasB,
            returdet.KuantitasPakai,
            returdet.Price,
            returdet.Total,
            returdet.Type,
            returdet.SerialNumber,
            returdet.Remark,
            returdet.Complete,
        ");
        $this->db->join("AP_Retur as retur", "returdet.ReturNo = retur.ReturNo", "left");
        $this->db->where("retur.CompanyID", $this->session->CompanyID);
        if($page == "selling"):
            $this->db->where("returdet.SellNo", $id);
            $this->db->where("returdet.SellDet", $iddet);
            $this->db->where("retur.Type", 2);
        endif;
        $query = $this->db->get("AP_Retur_Det as returdet");

        return $query->result();
    }
}