<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Md_bebas_pustaka extends CI_Model
{

	var $table = 'bebas_pustaka';
	var $column_order = array('no_surat_bebaspustaka', 'no_anggota', null); //set column field database for datatable orderable
	var $column_search = array('hb.no_surat', 'hb.no_surat', 'bp.no_anggota', 'vs.nama'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('bp.bebaspustaka_id' => 'desc'); // default order

	public function countAll()
	{

		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
	private function getDatatablesQuery()
	{

		$this->db->select('bp.*,bp.no_surat as no_surat_bebaspustaka,hb.no_surat as no_surat_hibahbuku,vs.nis,vs.kelas,hb.hibahbuku_id,vs.nama,hb.buku1_judul,hb.buku1_pengarang,hb.buku1_penerbit,hb.buku1_tempat_terbit,hb.buku1_tahun_terbit,hb.buku1_isbn,hb.buku2_judul,hb.buku2_pengarang,hb.buku2_penerbit,hb.buku2_tempat_terbit,hb.buku2_tahun_terbit,hb.buku2_isbn');
		$this->db->from($this->table . ' as bp');
		$this->db->join('hibah_buku as hb', 'bp.bebaspustaka_id= hb.bebaspustaka_id and hb.status=1');
		$this->db->join('vwsiswa as vs', 'bp.no_anggota = vs.nis');
		$this->db->where('bp.status', 1);

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

	function getBebasPustakaByBebaspustakaId($bebaspustaka_id)
	{
		$this->db->select('bp.*,sys_user.name as nm_author, bp.no_surat as no_surat_bebaspustaka,hb.no_surat as no_surat_hibahbuku,vs.nis,vs.kelas,hb.hibahbuku_id,vs.nama,hb.buku1_judul,hb.buku1_pengarang,hb.buku1_penerbit,hb.buku1_tempat_terbit,hb.buku1_tahun_terbit,hb.buku1_isbn,hb.buku2_judul,hb.buku2_pengarang,hb.buku2_penerbit,hb.buku2_tempat_terbit,hb.buku2_tahun_terbit,hb.buku2_isbn');
		$this->db->from($this->table . ' as bp');
		$this->db->join('hibah_buku as hb', 'bp.bebaspustaka_id= hb.bebaspustaka_id and hb.status=1');
		$this->db->join('vwsiswa as vs', 'bp.no_anggota = vs.nis');
		$this->db->join('siperpus_sysuser as sys_user', 'sys_user.name = bp.author');
		$this->db->where('bp.status', 1);
		$this->db->where("bp.bebaspustaka_id", $bebaspustaka_id);
		return $this->db->get()->row();
	}

	function getbebaspustakaByNoAnggota($noanggota, $bebaspustaka_id = '')
	{
		$this->db->select('bp.*');
		$this->db->from($this->table . ' as bp');
		$this->db->where('bp.status', 1);
		if ($bebaspustaka_id != '') {
			$this->db->where("bp.bebaspustaka_id !=", $bebaspustaka_id);
		}
		$this->db->where("bp.no_anggota", $noanggota);
		return $this->db->get()->row();
	}
	function addData($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function updateData($id, $data)
	{
		$this->db->where('bebaspustaka_id', $id);
		$this->db->update($this->table, $data);
	}
}
