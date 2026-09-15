<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Md_siperpus_penerbit extends CI_Model
{

	var $table = 'siperpus_penerbit';
	var $column_order = array('kd_penerbit', 'nama_penerbit', null); //set column field database for datatable orderable
	var $column_search = array('kd_penerbit', 'nama_penerbit'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('nama_penerbit' => 'asc'); // default order

	public function countAll()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
	private function getDatatablesQuery()
	{

		$this->db->from($this->table);

		$i = 0;

		foreach ($this->column_search as $item) // loop column
		{
			if ($this->input->post('datatable[query][generalSearch]')) // if datatable send POST for search
			{

				if ($i === 0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $this->input->post('datatable[query][generalSearch]'));
				} else {
					$this->db->or_like($item, $this->input->post('datatable[query][generalSearch]'));
				}

				if (count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
		if ($this->input->post('datatable[query][nama]') != '') $this->db->or_like('nama', $this->input->post('datatable[query][nama]'));

		if (in_array($this->input->post('datatable[sort][field]'), $this->column_order)) // here order processing
		{
			$this->db->order_by($this->input->post('datatable[sort][field]'), $this->input->post('datatable[sort][sort]'));
		} else if (isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function getDatatables()
	{
		$this->getDatatablesQuery();
		if ($this->input->post('datatable[pagination][perpage]') != -1)
			$this->db->limit($this->input->post('datatable[pagination][perpage]'), ($this->input->post('datatable[pagination][perpage]') * (($this->input->post('datatable[pagination][page]') - 1))));
		$query = $this->db->get();
		return $query->result();
	}

	function countFiltered()
	{
		$this->getDatatablesQuery();
		$query = $this->db->get();
		return $query->num_rows();
	}

	function getPenerbitAll()
	{
		$hasil = $this->db->query("SELECT * FROM siperpus_penerbit order by nama_penerbit asc");
		if ($hasil->num_rows() > 0) {
			foreach ($hasil->result() as $row) {
				$data[] = $row;
			}
			//$data['baris']=$this->db->query("SELECT MAX(idkategori) FROM siperpus_kategori")
			return $data;
		}
	}
	function addPenerbit($data)
	{
		$this->db->insert('siperpus_penerbit', $data);
		$penerbit = $this->db->query("SELECT MAX(kd_penerbit) as id from siperpus_penerbit")->result_array();
		return $penerbit[0]['id'];
	}
	function hapusPenerbit($data)
	{
		$this->db->where('kd_penerbit', $data);
		$this->db->delete('siperpus_penerbit');
	}
	function updatePenerbit($param, $data)
	{
		$this->db->where('kd_penerbit', $param);
		$this->db->update('siperpus_penerbit', $data);
	}
	function getPenerbitByNamadanKota($nm, $kota)
	{
		$hasil = $this->db->get_where('siperpus_penerbit', array('nama_penerbit' => $nm, 'kota' => $kota))->result();
		return $hasil;
	}
	function getPenerbitById($id)
	{
		$hasil = $this->db->get_where('siperpus_penerbit', array('kd_penerbit' => $id))->result();
		$data = $hasil;
		return $data;
	}
}
