<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_siperpus_sysgrant extends CI_Model {
	
	var $table = 'siperpus_sysgrant';
	var $column_order = array(array('nama','idsysmodul')); //set column field database for datatable orderable
	var $column_search = array('idsysmodul'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('idsysmodul' => 'asc'); // default order 

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

		if($this->input->post('datatable[query][group]')){
			$this->db->where('idsysgroup', $this->input->post('datatable[query][group]'));
		}else{
			$this->db->where('idsysgroup', 'A');
		}
			
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
	
	function getGrantAll() {
		 $hasil = $this->db->query("SELECT * FROM siperpus_sysgrant order by idsysgrant asc");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
	}
	function getLastId() {
		 $hasil = $this->db->query("SELECT * FROM siperpus_sysgrant order by idsysgrant desc");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                return $row->idsysgrant;
            }
        }
	}
	function getGrantById($grant) {
		 $hasil = $this->db->query("SELECT * FROM siperpus_sysgrant where idsysgrant='$grant'");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	function getGrantByModul($modul,$group) {
		 $hasil = $this->db->query("SELECT * FROM siperpus_sysgrant where idsysmodul='$modul' and idsysgroup='$group'");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	function getGrantByGroup($group,$add,$edit,$delete,$view,$print) {
		 $hasil = $this->db->query("SELECT (select file from siperpus_sysmodul2 where siperpus_sysmodul2.idsysmodul= siperpus_sysgrant.idsysmodul)as idsysmodul FROM siperpus_sysgrant where idsysgroup='$group' and (allow_add='$add' or allow_edit='$edit' or allow_delete='$delete' or allow_view='$view' or allow_print='$print')");
        if ($hasil->num_rows() > 0) {
			$data=array();
            foreach ($hasil->result() as $row) {
                $data[] = $row->idsysmodul;
            }
            return $data;
        }
    }
	function hapusGrant($data)
    {
      $this->db->where('idsysgrant',$data);
      $this->db->delete('siperpus_sysgrant');
    }
	function updateGrant($param,$data){
        $this->db->where('idsysgrant', $param);
        $this->db->update('siperpus_sysgrant', $data);         
    }
	function addGrant($data){
         $this->db->insert('siperpus_sysgrant', $data);        
    }
	
}