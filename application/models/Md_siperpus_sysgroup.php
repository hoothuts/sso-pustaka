<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_siperpus_sysgroup extends CI_Model {
	
	var $table = 'siperpus_sysgroup';
	var $column_order = array(array('id','idsysgroup'),array('nama','name'),array('def','def_modul')); 
	var $column_search = array('idsysgroup','name'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('idsysgroup' => 'asc'); // default order 

	public function countAll() {
        $this->db->from($this->table);
		return $this->db->count_all_results();
    }
	function countFiltered()
	{
		$this->getDatatablesQuery();
		$query = $this->db->get();
		return $query->num_rows();
	}
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
		//filter group

		//if($i > 0) $this->db->group_end(); //close bracket
		
		$val=$this->getValue($this->column_order,$this->input->post('datatable[sort][field]'));
		if($val!= false) // here order processing
		{
			$this->db->order_by($val, $this->input->post('datatable[sort][sort]'));
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
	
	function getGroupAll() {
		 $hasil = $this->db->query("SELECT * FROM siperpus_sysgroup order by idsysgroup asc");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
	}
	function getGroupById($group) {
		 $hasil = $this->db->query("SELECT * FROM siperpus_sysgroup where idsysgroup='$group'");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	function updateGroup($param,$data){
        $this->db->where('idsysgroup', $param);
        $this->db->update('siperpus_sysgroup', $data);         
    }
}