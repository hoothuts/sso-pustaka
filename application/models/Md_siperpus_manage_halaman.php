<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_siperpus_manage_halaman extends CI_Model
{

    var $table = 'halaman';
    var $column_order = array(array('jenis', 'jenis')); //set column field database for datatable orderable
    var $column_search = array('judul_halaman'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('judul_halaman' => 'asc'); // default order

    public function countAll()
    {

        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function getValue($b, $text)
    {
        foreach ($b as $v) {
            if (in_array($text, $v, true)) {
                return $v[1];
            }
        }
        return false;
    }

    private function getDatatablesQuery()
    {

        $this->db->from($this->table);
        $this->db->where('status', 1);

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
        //filter group

        if ($this->input->post('datatable[query][jenis]')) {
            $this->db->where('jenis', $this->input->post('datatable[query][jenis]'));
        }

        //if($i > 0) $this->db->group_end(); //close bracket

        $val = $this->getValue($this->column_order, $this->input->post('datatable[sort][field]'));
        if ($val != false) // here order processing
        {
            $this->db->order_by($val, $this->input->post('datatable[sort][sort]'));
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

    function getAllHalaman()
    {
        $this->db->order_by("halaman_id", "desc");
        $hasil = $this->db->get("halaman"); //nama tabel database
        return $hasil->result();
    }

    function getHalamanById($id)
    {
        $this->db->where('status', 1)->where('halaman_id', $id);
        $hasil = $this->db->get("halaman");
        return $hasil->result();
    }

    function getHalamanByJudul($id)
    {
        $this->db->where('status', 1)->where('judul_halaman', $id);
        $hasil = $this->db->get("halaman");
        return $hasil->result();
    }

    function getHalamanByStatus($status = "1")
    {
        $this->db->where('status', $status);
        $hasil = $this->db->get("halaman");
        return $hasil->result_array();
    }

    function getHalamanByHakakses($hak_akses = "")
    {
        $this->db->where($hak_akses);
        $hasil = $this->db->get("halaman");
        return $hasil->result_array();
    }

    function addHalaman($data)
    {
        $this->db->insert('halaman', $data);
        return $this->db->affected_rows() > 0;
    }

    function updateHalaman($id = "", $data = "")
    {
        $this->db->where('halaman_id', $id);
        $this->db->update('halaman', $data);
    }

    function getHalamanByLink($link, $id = 0)
    {
        $this->db->where([
            'link_halaman' => $link,
            'status' => 1
        ]);

        if ($id) {
            $this->db->where('halaman_id !=', $id);
        }

        $hasil = $this->db->get("halaman");
        return $hasil->row();
    }
}
