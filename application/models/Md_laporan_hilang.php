<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_laporan_hilang extends CI_Model {

  public function getValue($b,$text){
		foreach($b as $v)
		{
			if(in_array($text, $v, true))
			{
				return $v[1];
			}
		}
		return false;
	}

	private function getDatatablesQuery()
	{
		//,array('tgl','ang.tgl_daftar'),array('berlaku','ang.berlaku_sampai')
		$table = 'siperpus_hilangrusak hr';
		$column_order = array(array('noinv','hr.no_inv'),array('judul','sb.judul'),array('nomor','hr.no_anggota'),array('biaya','hr.biaya_ganti'),array('keterangan','hr.ket'),array('tanggal','hr.tgl_hr'));
		$column_search = array('sb.judul','hr.no_inv','hr.judul','hr.no_anggota','hr.biaya_ganti','hr.ket','hr.tgl_hr'); 
		$order = array('hr.tgl_hr' => 'desc'); // default order

		$this->db->from($table);

		$i = 0;

		foreach ($column_search as $item) // loop column
		{
			if($this->input->post('datatable[query][generalSearch]')) // if datatable send POST for search
			{

				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $this->input->post('datatable[query][generalSearch]'));
				}
				else
				{
					$this->db->or_like($item, $this->input->post('datatable[query][generalSearch]'));
				}

				if(count($column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
		
			if($this->input->post('datatable[query][tanggalawal]')){
				$this->db->where('hr.tgl_hr >=',$this->input->post('datatable[query][tanggalawal]'));
			}
			if($this->input->post('datatable[query][tanggalakhir]')){
				$this->db->where('hr.tgl_hr <=',$this->input->post('datatable[query][tanggalakhir]'));
			}
		//if($i > 0) $this->db->group_end(); //close bracket

		$val=$this->getValue($column_order,$this->input->post('datatable[sort][field]'));
		if($val!= false) // here order processing
		{
			$this->db->order_by($val, $this->input->post('datatable[sort][sort]'));
		}
		else if(isset($order))
		{
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	
	private function getLaporanQuery($ta,$tl,$src)
	{
		//,array('tgl','ang.tgl_daftar'),array('berlaku','ang.berlaku_sampai')
		$table = 'siperpus_hilangrusak hr';
		$column_order = array(array('noinv','hr.no_inv'),array('judul','sb.judul'),array('nomor','hr.no_anggota'),array('biaya','hr.biaya_ganti'),array('keterangan','hr.ket'),array('tanggal','hr.tgl_hr'));
		$column_search = array('sb.judul','hr.no_inv','hr.judul','hr.no_anggota','hr.biaya_ganti','hr.ket','hr.tgl_hr'); 
		$order = array('hr.tgl_hr' => 'desc'); // default order

		$this->db->from($table);

		$i = 0;

		foreach ($column_search as $item) // loop column
		{
			if($src) // if datatable send POST for search
			{

				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $src);
				}
				else
				{
					$this->db->or_like($item, $src);
				}

				if(count($column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

			if($ta){
				$this->db->where('hr.tgl_hr >=',$ta);
			}
			if($tl){
				$this->db->where('hr.tgl_hr <=',$tl);
			}
			$this->db->order_by(key($order), $order[key($order)]);
	}
	function getDatatables()
	{
		$this->getDatatablesQuery();
		if($this->input->post('datatable[pagination][perpage]') != -1)
		  $this->db->limit($this->input->post('datatable[pagination][perpage]'), ($this->input->post('datatable[pagination][perpage]')*(($this->input->post('datatable[pagination][page]')-1))));
	    $this->db->join('siperpus_inventaris si', 'si.no_inv = hr.no_inv');
	    $this->db->join('siperpus_buku sb', 'sb.no_klas = si.no_klas and sb.ISBN = si.ISBN');
	    $query = $this->db->get();
		return $query->result();
	}

	function countFiltered()
	{
		$this->getDatatablesQuery();
		$this->db->join('siperpus_inventaris si', 'si.no_inv = hr.no_inv');
	     $this->db->join('siperpus_buku sb', 'sb.no_klas = si.no_klas and sb.ISBN = si.ISBN');
	    $query = $this->db->get();
		return $query->num_rows();
	}
	function getLaporan($ta,$tl,$src) {
		
		$this->getLaporanQuery($ta,$tl,$src);
		$query = $this->db->get();
		$this->db->join('siperpus_inventaris si', 'si.no_inv = hr.no_inv');
	    $this->db->join('siperpus_buku sb', 'sb.no_klas = si.no_klas and sb.ISBN = si.ISBN');
	    return $query->result();
  }
}
