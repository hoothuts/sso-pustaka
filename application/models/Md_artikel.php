<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_artikel extends CI_Model
{

	function getArtikelHome()
	{
		$this->db->limit(10);
		$this->db->where("status", 1)->order_by("tgl_post", "desc");
		$hasil = $this->db->get("artikel");
		return $hasil->result();
	}

	function getAllArtikel()
	{
		$this->db->where("status", 1)->order_by("judul", "asc");
		$hasil = $this->db->get("artikel");
		return $hasil->result();
	}

	function getAllArtikelByJenis($jenis)
	{
		$this->db->select('*');
		$this->db->from('artikel as ar');
		$this->db->join('jenis_artikel as ja', 'ja.jenisartikel_id= ar.jenisartikel_id and ja.status=1');
		$this->db->where('ar.status', 1);
		$this->db->where('ja.jenis_artikel', $jenis);
		$this->db->order_by('ar.tgl_post', 'desc');
		return $this->db->get()->result();
	}
	function getArtikelById($artikel_id)
	{
		$this->db->where("artikel.artikel_id", $artikel_id);
		$this->db->join('jenis_artikel as ja', 'ja.jenisartikel_id= artikel.jenisartikel_id and ja.status=1');
		$hasil = $this->db->get("artikel");
		if ($hasil->num_rows() > 0) {
			foreach ($hasil->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}

	function getArtikelByJenisArtikelId($jns_artikel_id, $limit, $offset)
	{
		$this->db->from('artikel');
		$this->db->where("ja.jenisartikel_id", $jns_artikel_id);
		$this->db->join('jenis_artikel as ja', 'ja.jenisartikel_id= artikel.jenisartikel_id and ja.status=1');
		$this->db->join('media as m', 'artikel.media_id= m.media_id and m.status=1');
		$this->db->select('artikel.*, m.judul as mediajudul');
		$this->db->where("artikel.status", 1);
		// Add limit and offset
		$this->db->limit($limit, $offset);
                $this->db->order_by('tgl_post', 'desc');
		return $this->db->get()->result();
	}

	// Function to count total articles for pagination
	function countArtikelByJenisArtikelId($jns_artikel_id)
	{
		$this->db->from('artikel');
		$this->db->where("ja.jenisartikel_id", $jns_artikel_id);
		$this->db->join('jenis_artikel as ja', 'ja.jenisartikel_id= artikel.jenisartikel_id and ja.status=1');
		$this->db->where("artikel.status", 1);
		return $this->db->count_all_results();
	}

	function getArtikelByJudul($judul, $limit, $offset)
	{
		$this->db->from('artikel');

		// Convert artikel.judul and $judul to lowercase for case-insensitive comparison
		if ($judul != '') {
			$this->db->like('LOWER(artikel.judul)', strtolower($judul));
		}

		$this->db->join('jenis_artikel as ja', 'ja.jenisartikel_id= artikel.jenisartikel_id and ja.status=1');
		$this->db->join('media as m', 'artikel.media_id= m.media_id and m.status=1');
		$this->db->select('artikel.*, m.judul as mediajudul');
		$this->db->where("artikel.status", 1);
		// Add limit and offset
		$this->db->limit($limit, $offset);

		return $this->db->get()->result();
	}

	// Function to count total articles for pagination
	function countgetArtikelByJudul($judul)
	{
		$this->db->from('artikel');
		if ($judul != '') {
			$this->db->like('LOWER(artikel.judul)', strtolower($judul));
		}

		$this->db->join('jenis_artikel as ja', 'ja.jenisartikel_id= artikel.jenisartikel_id and ja.status=1');
		$this->db->where("artikel.status", 1);
		return $this->db->count_all_results();
	}

	function getArtikelByStatus($status = "1")
	{
		$this->db->where("status", $status);
		$hasil = $this->db->get("artikel");
		return $hasil->result_array();
	}

	function getArtikelByPostStatus($post_status = "")
	{
		$this->db->where("post_status", $post_status);
		$hasil = $this->db->get("artikel");
		return $hasil->result_array();
	}

	function getPaginationArtikel($limit, $start)
	{
		$this->db->limit($limit, $start);
		$this->db->order_by('tgl_post', 'DESC');
		$hasil = $this->db->get_where('artikel', array('status' => '1'));
		if ($hasil->num_rows() > 0) {
			return $hasil->result();
		}
	}

	function addArtikel($data = "")
	{
		$this->db->insert('artikel', $data);
		if ($this->db->affected_rows() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	function updateArtikel($id, $data)
	{
		$this->db->where("artikel_id", $id);
		$this->db->update("artikel", $data);
		if ($this->db->affected_rows() > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	function getNewArtikel()
	{
		$hasil = $this->db->query("SELECT * FROM artikel where status=1 and post_status=1 order by tgl_post desc limit 6");
		if ($hasil->num_rows() > 0) {
			foreach ($hasil->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}
	function getNewArtikelLimit($limit)
	{
		$hasil = $this->db->query("SELECT * FROM artikel where status=1 and post_status=1 order by tgl_post desc limit $limit");
		if ($hasil->num_rows() > 0) {
			foreach ($hasil->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}
}
