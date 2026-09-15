<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_siperpus_buku_file extends CI_Model
{


	function getFileById($isbn, $noklas)
	{
		$this->db->where('ISBN', $isbn);
		$this->db->where('no_klas', $noklas);
		$hasil = $this->db->get('siperpus_buku_file');
		return $hasil->result_array();
	}

	function getRowFileById($isbn, $noklas)
	{
		$this->db->where('ISBN', $isbn);
		$this->db->where('no_klas', $noklas);
		$this->db->order_by('file_id', 'DESC');
		$hasil = $this->db->get('siperpus_buku_file');
		return $hasil->row();
	}

	function hapusFileById($ISBN, $no_klas)
	{
		return $this->db->where(array('ISBN' => $ISBN, 'no_klas' => $no_klas))->delete('siperpus_buku_file');
	}
	function setFileStatus($file_id)
	{
		$status = $this->db->select('status_akses')->from("siperpus_buku_file")->where('file_id', $file_id)->get()->result_array();
		$akses = $status[0]['status_akses'] ? 0 : 1;
		$this->db->set('status_akses', $akses)->where('file_id', $file_id)->update('siperpus_buku_file');
		return $this->db->affected_rows();
	}
	function getFileByFileId($file_id)
	{
		$this->db->where('file_id', $file_id);
		$hasil = $this->db->get('siperpus_buku_file');
		return $hasil->row();
	}

	function updateData($id, $data)
	{ //*
		$this->db->where('file_id', $id);
		$this->db->update('siperpus_buku_file', $data);
	}

	public function search_file($keyword)
	{
		$this->db->like('file_name', $keyword);
		$query = $this->db->get('siperpus_buku_file');
		return $query->row();
	}
}
