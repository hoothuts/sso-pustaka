<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Md_siperpus_anggota_luar extends CI_Model
{

  var $table = 'siperpus_anggota_luar';
  var $column_order = array('tgl_post'); //set column field database for datatable orderable
  var $column_search = array('noid','nama','instansi_asal_nama'); //set column field database for datatable searchable just firstname , lastname , address are searchable
  var $order = array('tgl_post' => 'desc'); // default order

  public function countAll()
  {

    $this->db->from($this->table);
    return $this->db->count_all_results();
  }
  private function getDatatablesQuery()
  {

    $this->db->from($this->table);

    $i = 0;

    foreach ($this->column_search as $item) // loop column
    {
      if ($this->input->post('datatable[query][generalSearch]')) // if datatable send POST for search
      {

        if ($i === 0) // first loop
        {
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $this->input->post('datatable[query][generalSearch]'));
        } else {
          $this->db->or_like($item, $this->input->post('datatable[query][generalSearch]'));
        }

        if (count($this->column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }

    if (in_array($this->input->post('datatable[sort][field]'), $this->column_order)) // here order processing
    {
      $this->db->order_by($this->input->post('datatable[sort][field]'), $this->input->post('datatable[sort][sort]'));
    } else if (isset($this->order)) {
      $order = $this->order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  function getDatatables()
  {
    $this->getDatatablesQuery();
    if ($this->input->post('datatable[pagination][perpage]') != -1)
      $this->db->limit($this->input->post('datatable[pagination][perpage]'), ($this->input->post('datatable[pagination][perpage]') * (($this->input->post('datatable[pagination][page]') - 1))));

    $query = $this->db->get();
    return $query->result();
  }

  function countFiltered()
  {
    $this->getDatatablesQuery();
    $query = $this->db->get();
    return $query->num_rows();
  }

  function getAnggotaLuarById($id)
  {
    $hasil = $this->db->get_where('siperpus_anggota_luar', array('noid' => $id))->result();
    $data = $hasil;
    return $data;
  }

  function getRowAnggotaLuarById($id)
  {
    $id = strtolower($id); // Mengubah $id menjadi huruf kecil
    $this->db->where('LOWER(noid)', $id); // Mengubah noid menjadi huruf kecil di query
    $hasil = $this->db->get('siperpus_anggota_luar')->row();
    return $hasil;
  }
  function addKAnggotaLuar($data)
  {
    $this->db->insert('siperpus_anggota_luar', $data);
  }
  function hapusAnggotaLuar($data)
  {
    $this->db->where('noid', $data);
    $this->db->delete('siperpus_anggota_luar');
  }

  function updateAnggotaLuar($param, $data)
  {
    $this->db->where('noid', $param);
    $this->db->update('siperpus_anggota_luar', $data);
  }

  function getAnggotaAll()
  {
    $hasil = $this->db->query("SELECT * FROM  siperpus_anggota_luar order by noid asc");
    if ($hasil->num_rows() > 0) {
      foreach ($hasil->result() as $row) {
        $data[] = $row;
      }
      return $data;
    }
  }
  function getKartuAnggotaById($id)
  {
    return $this->db->get_where('siperpus_anggota_luar', array('noid' => $id))->result_array();
  }
}
