<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Md_mediasosial extends CI_Model
{
    public $table = 'media_sosial';
    function getLogoHome()
    {
        $this->db->select('l.*,m.judul');
        $this->db->limit(1);
        $this->db->order_by("l.logo_id", "desc");
        $this->db->from('logo as l');
        $this->db->join('media as m', "m.media_id=l.media_id");
        $this->db->where('l.status', 1);
        return $this->db->get()->row();
    }

    function getAllMediasosial()
    {
        $this->db->select('l.*');
        $this->db->from('media_sosial as l');
        $this->db->where('l.status', 1);
        return $this->db->get()->result();
    }

    function getDataById($mediasosial_id)
    {
        $this->db->where("mediasosial_id", $mediasosial_id);
        $hasil = $this->db->get("media_sosial");
        return $hasil->row();
    }

    function cekData($nm_mediasosial)
    {
        $this->db->where("nm_mediasosial", $nm_mediasosial)->where('status', 1);
        $hasil = $this->db->get("media_sosial");
        return $hasil->row();
    }

    public function updateData($id, $data)
    {
        $this->db->where('mediasosial_id', $id);
        $this->db->update($this->table, $data);
    }

    function getLogoByStatus($status)
    {
        $this->db->where("status", $status);
        $hasil = $this->db->get("logo");
        return $hasil->result_array();
    }

    function getPaginationLogo($limit, $start)
    {
        $this->db->limit($limit, $start);
        $this->db->order_by('tgl_post', 'DESC');
        $hasil = $this->db->get_where('logo', array('status' => '1'));
        if ($hasil->num_rows() > 0) {
            return $hasil->result();
        }
    }

    public function addData($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    function updateLogo($id, $data)
    {
        $this->db->where("logo_id", $id);
        $this->db->update("logo", $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    function updateLogoAll($data)
    {
        $this->db->update("logo", $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function getLogo()
    {
        $hasil = $this->db->query("SELECT * FROM logo where status=1 order by urutan asc limit 6");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
}
