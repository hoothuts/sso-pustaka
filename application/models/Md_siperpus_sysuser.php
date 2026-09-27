<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_siperpus_sysuser extends CI_Model {

    var $table = 'siperpus_sysuser';
    var $column_order = array(array('id', 'idsysuser'), array('nama', 'name'), array('group', 'idsysgroup'), array('active', 'active')); //set column field database for datatable orderable
    var $column_search = array('idsysuser', 'name', 'idsysgroup'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('name' => 'asc'); // default order

    public function countAll() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    function countFiltered() {
        $this->getDatatablesQuery();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function getValue($b, $text) {
        foreach ($b as $v) {
            if (in_array($text, $v, true)) {
                return $v[1];
            }
        }
        return false;
    }

    private function getDatatablesQuery() {

        $this->db->from($this->table);

        $i = 0;

        foreach ($this->column_search as $item) { // loop column
            if ($this->input->post('datatable[query][generalSearch]')) { // if datatable send POST for search

                if ($i === 0) { // first loop
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

        if ($this->input->post('datatable[query][group]')) {
            $this->db->where('idsysgroup', $this->input->post('datatable[query][group]'));
        }

        if ($this->input->post('datatable[query][status]') == '0' || $this->input->post('datatable[query][status]') == '1') {
            $this->db->where('active', $this->input->post('datatable[query][status]'));
        }

        if ($this->input->post('datatable[query][tipe]') == 'sso') {
            $this->db->where('nip_pegawai IS NOT NULL', NULL, FALSE);
            $this->db->where('nip_pegawai !=', '');
        } else if ($this->input->post('datatable[query][tipe]') == 'manual') {
            $this->db->group_start();
            $this->db->where('nip_pegawai', NULL);
            $this->db->or_where('nip_pegawai', '');
            $this->db->group_end();
        }

        //if($i > 0) $this->db->group_end(); //close bracket

        $val = $this->getValue($this->column_order, $this->input->post('datatable[sort][field]'));
        if ($val != false) { // here order processing
            $this->db->order_by($val, $this->input->post('datatable[sort][sort]'));
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function getDatatables() {
        $this->getDatatablesQuery();
        if ($this->input->post('datatable[pagination][perpage]') != -1)
            $this->db->limit($this->input->post('datatable[pagination][perpage]'), ($this->input->post('datatable[pagination][perpage]') * (($this->input->post('datatable[pagination][page]') - 1))));
        $query = $this->db->get();
        return $query->result();
    }

    function checkLogin($uname, $pwd) {
        $hasil = $this->db->get_where('siperpus_sysuser', array('idsysuser' => $uname, 'pass' => $pwd, 'active' => '1'))->result();
        $data = $hasil;
        return $data;
    }

    function getUserAll() {
        $hasil = $this->db->query("SELECT * FROM siperpus_sysuser where active='1' order by idsysuser asc");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function getUserById($id) {
        $hasil = $this->db->query("SELECT * FROM siperpus_sysuser where idsysuser='$id'");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function getUserByNip($nip) {
        $hasil = $this->db->get_where('siperpus_sysuser', array('nip_pegawai' => $nip, 'active' => '1'))->result();
        return $hasil;
    }

    function addUser($data) {
        $this->db->insert('siperpus_sysuser', $data);
    }

    function hapusUser($data) {
        $this->db->where('idsysuser', $data);
        $this->db->delete('siperpus_sysuser');
    }

    function updateUser($param, $data) {
        $this->db->where('idsysuser', $param);
        $this->db->update('siperpus_sysuser', $data);
    }

    function updatePic($id, $data) {
        $data = array('avatar' => $data);
        $this->db->where('idsysuser', $id);
        $this->db->update('siperpus_sysuser', $data);
    }

    function cekAnggota($username) {
        $cek = $this->db->select('nis')->from('vwanggota')->where('nis', $username)->get()->result();
        return isset($cek[0]) ? $cek[0]->nis : NULL;
    }
}
