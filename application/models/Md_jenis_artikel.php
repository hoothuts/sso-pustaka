<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_jenis_artikel extends CI_Model
{


	function getAllData()
	{
		$hasil = $this->db->get('jenis_artikel');
		return $hasil->result();
	}

	function getDataById($id)
	{
		$this->db->select('*');
		$this->db->from('jenis_artikel');
		$this->db->where('jenisartikel_id', $id);
		return $this->db->get()->row();
	}
}
