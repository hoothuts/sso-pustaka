<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_siperpus_kategori_buku extends CI_Model {

  var $table = 'siperpus_kategori';
	var $column_order = array('idkategori','nmkategori',null); //set column field database for datatable orderable
	var $column_search = array('idkategori','nmkategori'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('nmkategori' => 'asc'); // default order

	public function countAll() {
        $this->db->from($this->table);
		return $this->db->count_all_results();
    }
	private function getDatatablesQuery()
	{

		$this->db->from($this->table);

		$i = 0;

		foreach ($this->column_search as $item) // loop column
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

				if(count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
    if($this->input->post('datatable[query][nama]')!='')$this->db->or_like('nmkategori', $this->input->post('datatable[query][nama]'));

		if(in_array($this->input->post('datatable[sort][field]'),$this->column_order)) // here order processing
		{
			$this->db->order_by($this->input->post('datatable[sort][field]'), $this->input->post('datatable[sort][sort]'));
		}
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function getDatatables()
	{
		$this->getDatatablesQuery();
		if($this->input->post('datatable[pagination][perpage]') != -1)
		$this->db->limit($this->input->post('datatable[pagination][perpage]'), ($this->input->post('datatable[pagination][perpage]')*(($this->input->post('datatable[pagination][page]')-1))));
		$query = $this->db->get();
		return $query->result();
	}

	function countFiltered()
	{
		$this->getDatatablesQuery();
		$query = $this->db->get();
		return $query->num_rows();
	}

   function getKategoriBukuAll() {
		 $hasil = $this->db->query("SELECT * FROM siperpus_kategori order by nmkategori asc");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            //$data['baris']=$this->db->query("SELECT MAX(idkategori) FROM siperpus_kategori")
            return $data;
        }
    }
	 function addKategoriBuku($data){
         $this->db->insert('siperpus_kategori', $data);
    }
   function hapusKategoriBuku($data)
    {
      $this->db->where('idkategori',$data);
      $this->db->delete('siperpus_kategori');
    }
   function updateKategoriBuku($id,$data){
        $this->db->where('idkategori', $id);
        $this->db->update('siperpus_kategori', $data);
    }
	 function getKategoriById($id) {
        $hasil = $this->db->get_where('siperpus_kategori', array('idkategori' => $id))->result();
        $data = $hasil;
        return $data;
    }
	 function getKategoriByNama($id) {
        $hasil = $this->db->get_where('siperpus_kategori', array('nmkategori' => $id))->result();
        $data = $hasil;
        return $data;
    }

}
