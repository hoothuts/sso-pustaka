<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Md_jenis_media extends CI_Model {

	
	function getJenisById($id){
		$this->db->where('jenisartikel_id', $id);
		$hasil = $this->db->get('jenis_media');
		return $hasil->result_array();
	}

}