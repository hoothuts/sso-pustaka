<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_laporan_kataloginventaris extends CI_Model {

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

	private function getDatatablesQuery($klas,$ktg,$src)
	{
		//,array('tgl','ang.tgl_daftar'),array('berlaku','ang.berlaku_sampai')
		$table = 'siperpus_buku sb';
		$column_order = array(array('penulis','sb.penulis'),array('judul','sb.judul'),array('edisi','sb.edisi'),array('tahun','sb.thn_terbit'),array('ISBN','sb.ISBN'),array('jml','sb.jml_buku'),array('no_klas','sb.no_klas'),array('tanggal','sb.tanggal'));
		$column_search = array('sb.penulis','sb.judul','sb.edisi','sp.nama_penerbit','sb.thn_terbit','sb.ISBN','sb.jml_buku','sb.no_klas','sb.tanggal'); 
		$order = array('sb.penulis' => 'asc'); // default order

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
			if($ktg){
				$this->db->where('sb.idkategori',$ktg);
			}
			$this->db->order_by(key($order), $order[key($order)]);
	}
	function getKatalog($klas,$ktg,$src)
	{
		$this->getDatatablesQuery($klas,$ktg,$src);
		$this->db->join('siperpus_penerbit sp', 'sp.kd_penerbit = sb.kd_penerbit');
	    $query = $this->db->get();
		return $query->result();
	}

	function countFiltered($klas,$ktg,$src)
	{
		$this->getDatatablesQuery($klas,$ktg,$src);
		$this->db->join('siperpus_penerbit sp', 'sp.kd_penerbit = sb.kd_penerbit');
	    $query = $this->db->get();
		return $query->num_rows();
	}
}
