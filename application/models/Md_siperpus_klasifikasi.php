<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_siperpus_klasifikasi extends CI_Model {

    var $table = 'siperpus_klasifikasi';
    var $column_order = array('id', 'nama', null); //set column field database for datatable orderable
    var $column_search = array('id', 'nama'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('nama' => 'asc'); // default order 

    public function countAll() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
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

        if ($this->input->post('datatable[sort][field]') && $this->input->post('datatable[sort][field]') != 'action' && $this->input->post('datatable[sort][field]') != 'number') { // here order processing
            $this->db->order_by($this->input->post('datatable[sort][field]'), $this->input->post('datatable[sort][sort]'));
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

    function countFiltered() {
        $this->getDatatablesQuery();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function getKlasifikasiAll() {
        $hasil = $this->db->query("SELECT * FROM siperpus_klasifikasi order by id asc");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function addKlasifikasi($data) {
        $this->db->insert('siperpus_klasifikasi', $data);
    }

    function hapusKlasifikasi($data) {
        $this->db->where('id', $data);
        $this->db->delete('siperpus_klasifikasi');
    }

    function updateKlasifikasi($param, $data) {
        $this->db->where('id', $param);
        $this->db->update('siperpus_klasifikasi', $data);
    }

    function getKlasifikasiById($id) {
        $hasil = $this->db->get_where('siperpus_klasifikasi', array('id' => $id))->result();
        $data = $hasil;
        return $data;
    }
    
    /*===style baru===*/
    public function get_datatables($search = '', $limit = 10, $offset = 0, $field = 'nama', $sort = 'ASC') {
        $this->db->from($this->table);
        //$this->db->where('status', 1);

        if ($search) {
            $this->db->group_start();
            $this->db->like('id', $search);
            $this->db->or_like('nama', $search);
            $this->db->group_end();
        }

        $this->db->order_by($field, $sort);
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function count_filtered($search = '') {
        $this->db->from($this->table);
        //$this->db->where('status', 1);

        if ($search) {
            $this->db->group_start();
            $this->db->like('id', $search);
            $this->db->or_like('nama', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function soft_delete($id) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, ['status' => 2]);
    }
}
