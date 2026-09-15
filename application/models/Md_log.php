<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Md_log extends CI_Model {
  var $table = 'siperpus_log';
	var $column_order = array(array('user_id','user_id'),array('tgl','tgl')); //set column field database for datatable orderable
	var $column_search = array('user_id','jenis_akses','keterangan'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('user_id  ' => 'asc'); // default order

	public function countAll() {

        $this->db->from($this->table);
	      return $this->db->count_all_results();
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

		if($this->input->post('datatable[query][tgl]')){
			$this->db->where('tgl', $this->input->post('datatable[query][tgl]'));
			}

		if($this->input->post('datatable[query][ja]')){
			$this->db->where('jenis_akses', $this->input->post('datatable[query][ja]'));
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
			// $this->db->order_by('user_id  ','asc');
      $this->db->order_by('tgl  ','desc');
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

  function addLog($log)
  {
    $this->db->insert('siperpus_log', $log);
  }
  function getJenisAkses(){
    // $this->db->distinct()->select('thn_terbit')->from('siperpus_buku')->order_by('thn_terbit')->get()->result();
    return $this->db->distinct()->select('jenis_akses')->from('siperpus_log')->get()->result();
  }

}
