<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_vwprodi extends CI_Model {

  var $table = 'vwprodi';
	var $column_order = array('idmspst','kodemspst',null); //set column field database for datatable orderable
	var $column_search = array('idmspst','kodemspst'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('idmspst' => 'asc'); // default order

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
    if($this->input->post('datatable[query][nama]')!='')$this->db->or_like('nama', $this->input->post('datatable[query][nama]'));

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

   function getProdiAll() {
		 $hasil = $this->db->query("SELECT * FROM vwprodi order by nmmspst asc");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
     function getProdiById($id) {
		 $hasil = $this->db->query("SELECT * FROM vwprodi where idmspst='$id' order by nmmspst asc");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
}
