<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Md_kontak extends CI_Model
{
    public $table = 'kontak';
   

    function getAllKontak()
    {
        $this->db->select('l.*');
        $this->db->from('kontak as l');
        $this->db->where('l.status', 1);
        return $this->db->get()->result();
    }

    function getDataById($kontak_id)
    {
        $this->db->where("kontak_id", $kontak_id);
        $hasil = $this->db->get("kontak");
        return $hasil->row();
    }

    function cekData($hp)
    {
        $this->db->where("hp", $hp)->where('status', 1);
        $hasil = $this->db->get("kontak");
        return $hasil->row();
    }

    public function updateData($id, $data)
    {
        $this->db->where('kontak_id', $id);
        $this->db->update($this->table, $data);
    }

    function getKontakByStatus($status)
    {
        $this->db->where("status", $status);
        $hasil = $this->db->get("kontak");
        return $hasil->result_array();
    }

    function getPaginationKontak($limit, $start)
    {
        $this->db->limit($limit, $start);
        $hasil = $this->db->get_where('kontak', array('status' => '1'));
        if ($hasil->num_rows() > 0) {
            return $hasil->result();
        }
    }

    public function addData($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    function updateKontak($id, $data)
    {
        $this->db->where("kontak_id", $id);
        $this->db->update("kontak", $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    function updateKontakAll($data)
    {
        $this->db->update("kontak", $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    function getWhatsappOnly(){
        $hasil = $this->db->get_where('kontak',array('status'=>1))->result();
        return $hasil;
    }  

   
}
