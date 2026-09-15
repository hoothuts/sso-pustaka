<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Md_logo extends CI_Model
{

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

    function getAllLogo()
    {
        $this->db->select('l.*,m.judul');
        $this->db->from('logo as l');
        $this->db->join('media as m', "m.media_id=l.media_id");
        $this->db->where('l.status', 1);
        return $this->db->get()->result();
    }

    function getLogoById($logo_id)
    {
        $this->db->where("logo_id", $logo_id);
        $hasil = $this->db->get("logo");
        return $hasil->row();
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

    function addLogo($data)
    {
        $this->db->insert('logo', $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
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
