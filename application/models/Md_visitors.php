<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Md_visitors extends CI_Model
{

	var $table = 'visitors';
	var $column_order = array(array('nis', 'nis'), array('nama', 'nama'), array('prodi', 'kelas'), array('status', 'status_siswa')); //set column field database for datatable orderable
	var $column_search = array('nis', 'nama'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('nama' => 'asc'); // default order

	function addData($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	function getDataByTgl($tglAwal, $tglAkhir)
	{

		$this->db->select('v.date, COUNT(v.time) as total');
		$this->db->from('visitors as v');
		if ($tglAwal) {
			$this->db->where('DATE(v.date) >=', $tglAwal);
		}
		if ($tglAkhir) {
			$this->db->where('DATE(v.date) <=', $tglAkhir);
		}
		$this->db->where('v.date !=', '0000-00-00');
		$this->db->group_by('v.date');
		$this->db->order_by('v.date', 'DESC');

		$query = $this->db->get();
		$result = $query->result();

		return $result;
	}
}
