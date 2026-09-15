<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_laporan_buku_prodi extends CI_Model {

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
		//	No. Klasifikasi	Judul Buku	Penerbit	Jurusan (Matakuliah)	Jml Buku
		$table = 'siperpus_buku_prodi bp';
		$column_order = array(array('klasifikasi','bp.no_klas'),array('judul','sb.judul'),array('penerbit','sp.nama_penerbit'),array('jml','sb.jml_buku'));
		$column_search = array('bp.no_klas','sb.judul','sp.nama_penerbit','pr.nmmspst'); 
		$order = array('sb.judul' => 'asc'); // default order

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
		
			if($this->input->post('datatable[query][klasifikasi]')&& $this->input->post('datatable[query][klasifikasi]')!='-'){
				$this->db->like('sb.no_klas',$this->input->post('datatable[query][klasifikasi]'), 'after'); 
			}
			if($this->input->post('datatable[query][kategori]')&& $this->input->post('datatable[query][kategori]')!='-'){
				$this->db->where('sb.idkategori',$this->input->post('datatable[query][kategori]'));
			}
			if($this->input->post('datatable[query][prodi]')&& $this->input->post('datatable[query][prodi]')!='-'){
				$this->db->where('bp.idmspst',$this->input->post('datatable[query][prodi]'));
			}

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
	
	private function getLaporanQuery($klas,$ktg,$pr,$src)
	{
		$table = 'siperpus_buku_prodi bp';
		$column_order = array(array('klasifikasi','bp.no_klas'),array('judul','sb.judul'),array('penerbit','sp.nama_penerbit'),array('jurusan','pr.nmmspst'),array('jml','sb.jml_buku'));
		$column_search = array('bp.no_klas','sb.judul','sp.nama_penerbit','pr.nmmspst'); 
		$order = array('sb.judul' => 'asc'); // default order

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

			if($klas && $klas!='-'){
				$this->db->like('sb.no_klas', $klas, 'after'); 
			}
			if($ktg && $ktg!='-'){
				$this->db->where('sb.idkategori',$ktg);
			}
			if($pr && $ktg !='-'){
				$this->db->where('bp.idmspst',$pr);
			}
			$this->db->order_by(key($order), $order[key($order)]);
	}
	function getDatatables()
	{
		$this->getDatatablesQuery();
		if($this->input->post('datatable[pagination][perpage]') != -1)
		  $this->db->limit($this->input->post('datatable[pagination][perpage]'), ($this->input->post('datatable[pagination][perpage]')*(($this->input->post('datatable[pagination][page]')-1))));
	     $this->db->join('siperpus_buku sb', 'sb.no_klas = bp.no_klas and sb.ISBN = bp.ISBN');
	     $this->db->join('vwprodi pr', 'pr.idmspst = bp.idmspst');
	    $this->db->join('siperpus_penerbit sp', 'sp.kd_penerbit = sb.kd_penerbit');
	    $this->db->select('bp.no_klas,sb.judul,sp.nama_penerbit as penerbit,pr.nmmspst,sb.jml_buku,bp.ISBN');
		 $this->db->group_by('bp.no_klas'); 
		 $this->db->group_by('bp.ISBN'); 
	    $query = $this->db->get();
		return $query->result();
	}

	function countFiltered()
	{
		$this->getDatatablesQuery();
	    $this->db->join('siperpus_buku sb', 'sb.no_klas = bp.no_klas and sb.ISBN = bp.ISBN');
	     $this->db->join('vwprodi pr', 'pr.idmspst = bp.idmspst');
	    $this->db->join('siperpus_penerbit sp', 'sp.kd_penerbit = sb.kd_penerbit');
	    $this->db->select('bp.no_klas,sb.judul,sp.nama_penerbit as penerbit,pr.nmmspst,sb.jml_buku,bp.ISBN');
		 $this->db->group_by('bp.no_klas'); 
		 $this->db->group_by('bp.ISBN'); 
	   $query = $this->db->get();
		return $query->num_rows();
	}
	function getLaporan($klas,$ktg,$pr,$src) {
		
		$this->getLaporanQuery($klas,$ktg,$pr,$src);
	    $this->db->join('siperpus_buku sb', 'sb.no_klas = bp.no_klas and sb.ISBN = bp.ISBN');
	     $this->db->join('vwprodi pr', 'pr.idmspst = bp.idmspst');
	    $this->db->join('siperpus_penerbit sp', 'sp.kd_penerbit = sb.kd_penerbit');
	    $this->db->select('bp.no_klas,sb.judul,sp.nama_penerbit as penerbit,pr.nmmspst,sb.jml_buku,bp.ISBN');
		 $this->db->group_by('bp.no_klas'); 
		 $this->db->group_by('bp.ISBN'); 
	   $query = $this->db->get();
		return $query->result();
  }
}
