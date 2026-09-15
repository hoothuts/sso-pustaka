<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_hibah_buku extends CI_Model
{
	var $table = 'hibah_buku';

	function addData($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function updateData($id, $data)
	{
		$this->db->where('hibahbuku_id', $id);
		$this->db->update($this->table, $data);
	}
}
