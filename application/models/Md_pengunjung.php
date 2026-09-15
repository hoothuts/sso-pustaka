<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Md_pengunjung extends CI_Model
{

    public $table = 'pengunjung';

    function addData($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    function updateMemberalamat($id, $data)
    { //*
        $this->db->where('memberalamat_id', $id);
        $this->db->update($this->table, $data);
    }

    function getAlamatbymemberpmbid($memberpmb_id)
    {
        $this->db->select('ma.*');
        $this->db->from($this->table . ' as ma');
        $this->db->where('ma.status', 1);
        $this->db->where('ma.memberpmb_id', $memberpmb_id);
        return $this->db->get()->row();
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
        $table = 'pengunjung as sp';

        $column_order = array(array('tgl_post', 'tgl_post'),  array('nama', 'nama'));
        $column_search = array('tgl_post', 'nama', 'asal_instansi');
        $order = array('tgl_post' => 'desc',); // default order
        $this->db->from($table);
        $i = 0;

        foreach ($column_search as $item) // loop column
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

                if (count($column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if ($this->input->post('datatable[query][tanggalawal]')) {
            $this->db->where('DATE_FORMAT(sp.tgl_post, \'%Y-%m-%d\') >=', $this->input->post('datatable[query][tanggalawal]'));
        }
        if ($this->input->post('datatable[query][tanggalakhir]')) {
            $this->db->where('DATE_FORMAT(sp.tgl_post, \'%Y-%m-%d\') <=', $this->input->post('datatable[query][tanggalakhir]'));
        }
        //if($i > 0) $this->db->group_end(); //close bracket

        $val = $this->getValue($column_order, $this->input->post('datatable[sort][field]'));
        if ($val != false) // here order processing
        {
            $this->db->order_by($val, $this->input->post('datatable[sort][sort]'));
        } else if (isset($order)) {
            $this->db->order_by('tgl_post', 'desc');
        }
    }

    private function getLaporanQuery($ta, $tl, $src)
    {
        $table = 'pengunjung sp';

        $column_order = array(array('tgl_post', 'tgl_post'), array('nama', 'nama'));
        $column_search = array('tgl_post', 'nama');
        $order = array('tgl_post' => 'desc'); // default order
        $this->db->from($table);

        $i = 0;

        foreach ($column_search as $item) // loop column
        {
            if ($src) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $src);
                } else {
                    $this->db->or_like($item, $src);
                }

                if (count($column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if ($ta) {
            $this->db->where('DATE_FORMAT(ps.tgl_post, \'%Y-%m-%d\') >=', $ta);
        }
        if ($tl) {
            $this->db->where('DATE_FORMAT(ps.tgl_post, \'%Y-%m-%d\') <=', $tl);
        }
        $this->db->order_by('tgl_post', 'desc');
    }
    function getDatatables()
    {
        // echo $this->input->post('datatable[pagination][pilihprodi]');die;
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
    function getLaporan($ta, $tl, $src)
    {

        $this->getLaporanQuery($ta, $tl, $src);
        $query = $this->db->get();
        return $query->result();
    }
}
