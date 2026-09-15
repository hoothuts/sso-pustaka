<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Md_slide extends CI_Model {

	function getSlideHome(){
		$this->db->limit(10);
		$this->db->where("status", 1)->order_by("urutan", "asc");
		$hasil = $this->db->get("slide");
		return $hasil->result();
	}

	function getAllSlide(){
                $this->db->select('s.*,m.judul as file_slide');
        
		$this->db->where("s.status", 1)->order_by("s.urutan", "asc");
                $this->db->join('media m', 'm.media_id = s.media_id');
		$hasil = $this->db->get("slide as s");
		return $hasil->result();
	}	

	function getSlideById($slide_id){
		$this->db->where("slide_id", $slide_id);
		$hasil = $this->db->get("slide");
		return $hasil->row();
	}

	function getSlideByStatus($status){
		$this->db->where("status", $status);
		$hasil = $this->db->get("slide");
		return $hasil->result_array();
	}

	function getPaginationSlide($limit, $start) {
    	$this->db->limit($limit, $start);
    	$this->db->order_by('tgl_post', 'DESC');
    	$hasil = $this->db->get_where('slide', array('status' => '1'));
    	if ($hasil->num_rows() > 0) {
    		return $hasil->result();
    	}
    }

	function addSlide($data){
		$this->db->insert('slide', $data);
		if ($this->db->affected_rows() > 0) {
			return TRUE;
		}else{
			return FALSE;
		}
    }

    function updateSlide($id, $data) {
        $this->db->where("slide_id", $id);
        $this->db->update("slide", $data);
        if ($this->db->affected_rows() > 0) {
			return TRUE;
		}else{
			return FALSE;
		}
    }
	function getSlide() {
		 $hasil = $this->db->query("SELECT * FROM slide where status=1 order by urutan asc limit 6");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
}