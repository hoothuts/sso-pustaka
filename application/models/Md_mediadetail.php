<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Md_mediadetail extends CI_Model {

	function getAllMdetail(){
		$this->db->order_by("judul", "asc");
		$hasil = $this->db->get("media_detail");
		return $hasil->result_array();
	}	

	function getMdetailById($mediadetail_id=""){
		$this->db->where("mediadetail_id", $mediadetail_id);
		$hasil = $this->db->get("media_detail");
		return $hasil->result_array();
	}

	function getMdetailByMediaId($media_id=""){
		$this->db->where("media_id", $media_id);
		$hasil = $this->db->get("media_detail");
		return $hasil->result_array();
	}

	function getMdetailHomeByMediaId($media_id=""){
		$this->db->where("jenis_ukuran", 'medium')->where("media_id", $media_id);
		$hasil = $this->db->get("media_detail");
		return $hasil->result();
	}

	function getMdetailArtikelByMediaId($media_id="", $ukuran=""){
		$this->db->where("jenis_ukuran", $ukuran)->where("media_id", $media_id);
		$hasil = $this->db->get("media_detail");
		return $hasil->result();
	}

	function getMdetailByStatus($status="1"){
		$this->db->where("status", $status);
		$hasil = $this->db->get("media_detail");
		return $hasil->result_array();
	}

	function getMdetailByPostStatus($post_status=""){
		$this->db->where("post_status", $post_status);
		$hasil = $this->db->get("media_detail");
		return $hasil->result_array();
	}

	function addMdetail($data=""){
		$this->db->insert('media_detail', $data);
		if ($this->db->affected_rows() > 0) {
			return TRUE;
		}else{
			return FALSE;
		}
    }

    function updateMdetail($id="", $data="") {
        $this->db->where("mediadetail_id", $id);
        $this->db->update("media_detail", $data);
        if ($this->db->affected_rows() > 0) {
			return TRUE;
		}else{
			return FALSE;
		}
    }

}

/* End of file Md_mediadetail.php */
/* Location: ./application/models/Md_mediadetail.php */