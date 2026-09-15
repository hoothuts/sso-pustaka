<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_vwdosen extends CI_Model {

  var $table = 'vwdosen';
	var $column_order = array('nip','nama','nip_dosen'); //set column field database for datatable orderable
	var $column_search = array('nip','nama'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('nama' => 'asc'); // default order

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



   function getDosenAll() {
		 $hasil = $this->db->query("SELECT * FROM vwdosen order by nama asc");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
    function getDosenById($id) {
        $hasil = $this->db->get_where('vwdosen', array('nip' => $id))->result();
        $data = $hasil;
        return $data;
    }
     function getKartuDosenById($id) {
        return $this->db->select("nip, nama, status")->get_where('vwdosen', array('nip' => $id))->result_array();
	}
}
