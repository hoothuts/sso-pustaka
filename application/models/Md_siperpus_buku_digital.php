<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_siperpus_buku_digital extends CI_Model {

   var $table = 'siperpus_buku';
	var $column_search = array('no_klas','judul','siperpus_penerbit.nama_penerbit'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	
// Count all record of table "contact_info" in database.
	public function record_count($word,$limit,$id) {
			$this->getDatatablesQuery($word);
			$this->db->join('siperpus_penerbit', 'siperpus_buku.kd_penerbit = siperpus_penerbit.kd_penerbit');
			$query = $this->db->get();
			return $query->num_rows();
	}
	
	private function getDatatablesQuery($word)
	{
		
		//$this->db->select('no_klas,judul,siperpus_buku.kd_penerbit,nama_penerbit,jml_buku,review');
		$this->db->from($this->table);
		//$this->db->where('no_klas', $id);
		if($word){
			$src=$word;
			$i=0;
			foreach ($this->column_search as $item) // loop column 
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

					if(count($this->column_search) - 1 == $i) //last loop
						$this->db->group_end(); //close bracket
				}
				$i++;
			}
		}
		if($this->input->post('judul')){
			$this->db->like('siperpus_buku.judul', $this->input->post('judul'));
		}
		if($this->input->post('penulis')){
			$this->db->like('siperpus_buku.penulis', $this->input->post('penulis'));
		}
		if($this->input->post('seri')){
			$this->db->like('siperpus_buku.seri', $this->input->post('seri'));
		}
		if($this->input->post('isbn')){
			$this->db->like('siperpus_buku.ISBN', $this->input->post('isbn'));
		}
		$this->db->like('siperpus_buku.idkategori',20);
	}
	// Fetch data according to per_page limit.
	public function fetch_data($word,$limit, $start) {
 
		$this->getDatatablesQuery($word);
		$offset = ($start-1)*$limit;
		$this->db->limit($limit,$offset);
		$this->db->join('siperpus_penerbit', 'siperpus_buku.kd_penerbit = siperpus_penerbit.kd_penerbit');
                $this->db->order_by('siperpus_buku.tanggal','desc');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
		foreach ($query->result() as $row) {
		$data[] = $row;
		}

		return $data;
		}
		return false;

	}
	public function fetch_data2($limit, $start) {
 
		$hasil = $this->db->query("SELECT * FROM siperpus_buku");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }

	}
}