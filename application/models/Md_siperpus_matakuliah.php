<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_siperpus_matakuliah extends CI_Model {

  var $select= 'a.* , s.nmmspst';
  var $table = 'vwmk a, vwprodi s';
  var $where = 'a.idmspst = s.idmspst';

  var $column_order = array('kodetbkmk','nmtbkmk',null); //set column field database for datatable orderable
  var $column_search = array('kodetbkmk','nmtbkmk','pengajartbkmk'); //set column field database for datatable searchable just firstname , lastname , address are searchable
  var $order = array('nmtbkmk' => 'asc'); // default order

  public function countAll() {
        $this->db->from($this->table);
    return $this->db->count_all_results();
    }
  private function getDatatablesQuery()
  {
    $this->db->select($this->select);
    $this->db->from($this->table);//from vwanggota
    $this->db->where($this->where);
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


   function getMatakuliahAll() {
		 $hasil = $this->db->query("SELECT *,(SELECT nmmspst FROM `vwprodi` WHERE idmspst = mk.idmspst) as nmprodi FROM `vwmk` mk order by nmtbkmk asc");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            //$data['baris']=$this->db->query("SELECT MAX(idkategori) FROM siperpus_kategori")
            return $data;
        }
    }
	 function addMatakuliah($data){
         $this->db->insert('vwmk', $data);
    }
   function hapusMatakuliah($data)
    {
      $this->db->where('id',$data);
      $this->db->delete('vwmk');
    }
   function updateMatakuliah($param,$data){
        $this->db->where('id', $param);
        $this->db->update('vwmk', $data);
    }
	 function getMatakuliahById($id) {
        $hasil = $this->db->get_where('vwmk', array('id' => $id))->result();
        $data = $hasil;
        return $data;
    }

}
