<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Md_jurnal_vendor extends CI_Model {

    var $table = 'jurnal_vendor';

    public function get_datatables($search = '', $limit = 10, $offset = 0, $field = 'jurnalvendor_id', $sort = 'DESC') {
        $this->db->from($this->table);
        $this->db->where('status', 1);

        if ($search) {
            $this->db->group_start();
            $this->db->like('nama_vendor', $search);
            $this->db->or_like('kategori', $search);
            $this->db->group_end();
        }

        $this->db->order_by($field, $sort);
        $this->db->limit($limit, $offset);

        return $this->db->get()->result();
    }

    public function count_filtered($search = '') {
        $this->db->from($this->table);
        $this->db->where('status', 1);

        if ($search) {
            $this->db->group_start();
            $this->db->like('nama_vendor', $search);
            $this->db->or_like('kategori', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, [
                    'jurnalvendor_id' => $id
                ])->row();
    }

    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        $this->db->where('jurnalvendor_id', $id);
        return $this->db->update($this->table, $data);
    }

    public function soft_delete($id) {
        $this->db->where('jurnalvendor_id', $id);

        return $this->db->update($this->table, [
                    'status' => 2
        ]);
    }

    public function cek_duplikat($nama_vendor, $exclude_id = null) {
        $this->db->where('nama_vendor', $nama_vendor);
        $this->db->where('status', 1);

        if ($exclude_id !== null) {
            $this->db->where('jurnalvendor_id !=', $exclude_id);
        }

        return $this->db->get($this->table)->row();
    }

    public function get_total_vendor_aktif() {
        return $this->db
                        ->where('status', 1)
                        ->count_all_results('jurnal_vendor');
    }

    public function get_top_vendor() {
        $this->db->select('
        jv.nama_vendor,
        COUNT(*) as total
    ');

        $this->db->from('jurnal_log jl');

        $this->db->join(
                'jurnal_vendor jv',
                'jv.jurnalvendor_id = jl.jurnalvendor_id',
                'left'
        );

        $this->db->where('jl.status', 1);

        $this->db->group_by('jl.jurnalvendor_id');

        $this->db->order_by('total', 'DESC');

        $this->db->limit(10);

        return $this->db->get()->result();
    }
}
