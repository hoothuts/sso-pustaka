<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_media extends CI_Model {
    var $table = 'media';
    
    function getAllMedia() {
        $this->db->order_by("media_id", "asc");
        $this->db->where('jenismedia_id', 1);
        $hasil = $this->db->get("media");
        return $hasil->result_array();
    }

    function getMediaByTipe($tipe = "") {
        $this->db->where('tipe', $tipe);
        $hasil = $this->db->get('media');
        return $hasil->result_array();
    }

    function getMediaById($media_id) {
        $this->db->where('media_id', $media_id);
        $hasil = $this->db->get('media');
        return $hasil->result_array();
    }

    function getMediaByStatus($status = "1") {
        $this->db->where('status', $status);
        $hasil = $this->db->get("media");
        return $hasil->result_array();
    }

    function addMedia($data = "") {
        $this->db->insert('media', $data);
        $id = $this->db->insert_id();
        if ($this->db->affected_rows() > 0) {
            return $arrayName = array('media_id' => $id, TRUE);
        } else {
            return FALSE;
        }
    }

    function getMediaPagination($limit, $start) {
        $this->db->limit($limit, $start);
        $this->db->order_by('media_id', 'DESC');
        $this->db->where('jenismedia_id', 1);
        $hasil = $this->db->get_where('media', array('tipe' => 'gambar'));
        if ($hasil->num_rows() > 0) {
            return $hasil->result_array();
        }
    }

    function hapusMedia($id) {
        $this->db->where('media_id', $id);
        $this->db->delete('media');
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function updateMedia($id = "", $data = "") {
        $this->db->where('media_id', $id);
        $this->db->update('media', $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function getMediaByMediaid($media_id) {
        $this->db->where('media_id', $media_id);
        $hasil = $this->db->get('media');
        return $hasil->row();
    }

    public function get_active($jenismedia_id) {
        $this->db->where('jenismedia_id', $jenismedia_id);
        $this->db->where('status', 1);
        return $this->db->get($this->table)->row();
    }

    public function deactivate_old($jenismedia_id) {
        $this->db->where('jenismedia_id', $jenismedia_id);
        $this->db->where('status', 1);
        $this->db->update($this->table, ['status' => 2]);
    }

//    public function insert_media($data) {
//        $this->db->insert($this->table, $data);
//    }
}

/* End of file Md_media.php */
/* Location: ./application/models/Md_media.php */