<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Md_lokasi extends CI_Model {

    private $table_kampus = 'lokasi_kampus';
    private $table_gedung = 'lokasi_gedung';
    private $table_rak = 'lokasi_rak';

    private $column_order = array('id', 'nama', null); //set column field database for datatable orderable
    private $column_search = array('id', 'nama'); //set column field database for datatable searchable 
    private $order = array('nama' => 'asc'); // default order 

    // Get all kampus for datatable
    public function get_kampus($search = '', $limit = 10, $offset = 0, $field = 'lokasikampus_id', $sort = 'ASC') {
        $this->db->select('k.*, COUNT(i.no_inv) AS jumlah_koleksi');
        $this->db->from($this->table_kampus . ' k');
        $this->db->join($this->table_gedung . ' g', 'g.lokasikampus_id = k.lokasikampus_id', 'left');
        $this->db->join($this->table_rak . ' r', 'r.lokasigedung_id = g.lokasigedung_id', 'left');
        $this->db->join('siperpus_inventaris i', 'i.lokasirak_id = r.lokasirak_id AND i.status = "A"', 'left');
        $this->db->where('k.status', 1);

        if ($search) {
            $this->db->group_start();
            $this->db->like('k.lokasikampus_id', $search);
            $this->db->or_like('k.nama_kampus', $search);
            $this->db->group_end();
        }

        $this->db->group_by('k.lokasikampus_id');
        $this->db->order_by($field, $sort);
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function count_kampus($search = '') {
        $this->db->from($this->table_kampus);
        $this->db->where('status', 1);

        if ($search) {
            $this->db->group_start();
            $this->db->like('lokasikampus_id', $search);
            $this->db->or_like('nama_kampus', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    // Get all gedung for datatable (join kampus)
    public function get_gedung($search = '', $limit = 10, $offset = 0, $field = 'lokasigedung_id', $sort = 'ASC') {
        $this->db->select('g.*, k.nama_kampus, COUNT(i.no_inv) AS jumlah_koleksi');
        $this->db->from($this->table_gedung . ' g');
        $this->db->join($this->table_kampus . ' k', 'g.lokasikampus_id = k.lokasikampus_id', 'left');
        $this->db->join($this->table_rak . ' r', 'r.lokasigedung_id = g.lokasigedung_id', 'left');
        $this->db->join('siperpus_inventaris i', 'i.lokasirak_id = r.lokasirak_id AND i.status = "A"', 'left');
        $this->db->where('g.status', 1);

        if ($search) {
            $this->db->group_start();
            $this->db->like('g.lokasigedung_id', $search);
            $this->db->or_like('g.nama_gedung', $search);
            $this->db->or_like('k.nama_kampus', $search);
            $this->db->group_end();
        }

        $this->db->group_by('g.lokasigedung_id');
        $this->db->order_by($field, $sort);
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function count_gedung($search = '') {
        $this->db->from($this->table_gedung . ' g');
        $this->db->join($this->table_kampus . ' k', 'g.lokasikampus_id = k.lokasikampus_id', 'left');
        $this->db->where('g.status', 1);

        if ($search) {
            $this->db->group_start();
            $this->db->like('g.lokasigedung_id', $search);
            $this->db->or_like('g.nama_gedung', $search);
            $this->db->or_like('k.nama_kampus', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    // Get all rak for datatable (join gedung & kampus)
    public function get_rak($search = '', $limit = 10, $offset = 0, $field = 'lokasirak_id', $sort = 'ASC') {
        $this->db->select('r.*, g.nama_gedung, k.nama_kampus, COUNT(i.no_inv) AS jumlah_koleksi');
        $this->db->from($this->table_rak . ' r');
        $this->db->join($this->table_gedung . ' g', 'r.lokasigedung_id = g.lokasigedung_id', 'left');
        $this->db->join($this->table_kampus . ' k', 'g.lokasikampus_id = k.lokasikampus_id', 'left');
        $this->db->join('siperpus_inventaris i', 'i.lokasirak_id = r.lokasirak_id AND i.status = "A"', 'left');
        $this->db->where('r.status', 1);

        if ($search) {
            $this->db->group_start();
            $this->db->like('r.lokasirak_id', $search);
            $this->db->or_like('r.nama_rak', $search);
            $this->db->or_like('g.nama_gedung', $search);
            $this->db->or_like('k.nama_kampus', $search);
            $this->db->group_end();
        }

        $this->db->group_by('r.lokasirak_id');
        $this->db->order_by($field, $sort);
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function count_rak($search = '') {
        $this->db->from($this->table_rak . ' r');
        $this->db->join($this->table_gedung . ' g', 'r.lokasigedung_id = g.lokasigedung_id', 'left');
        $this->db->join($this->table_kampus . ' k', 'g.lokasikampus_id = k.lokasikampus_id', 'left');
        $this->db->where('r.status', 1);

        if ($search) {
            $this->db->group_start();
            $this->db->like('r.lokasirak_id', $search);
            $this->db->or_like('r.nama_rak', $search);
            $this->db->or_like('g.nama_gedung', $search);
            $this->db->or_like('k.nama_kampus', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    // Get by ID for edit/view
    public function get_by_id($type, $id) {
        $type = strtolower(trim($type)); // case-insensitive
        $table = $this->get_table($type);
        $id_field = $this->get_id_field($type);

        if (!$table || !$id_field) {
            log_message('error', 'Invalid type or missing table/id_field for type: ' . $type);
            return null;
        }

        $this->db->where($id_field, $id);

        // Tambahkan JOIN berdasarkan type
        if ($type == 'gedung') {
            $this->db->select('g.*, k.nama_kampus');
            $this->db->from($table . ' g');
            $this->db->join('lokasi_kampus k', 'g.lokasikampus_id = k.lokasikampus_id', 'left');
        } elseif ($type == 'rak') {
            $this->db->select('r.*, g.nama_gedung, k.lokasikampus_id, k.nama_kampus');
            $this->db->from($table . ' r');
            $this->db->join('lokasi_gedung g', 'r.lokasigedung_id = g.lokasigedung_id', 'left');
            $this->db->join('lokasi_kampus k', 'g.lokasikampus_id = k.lokasikampus_id', 'left');
        } else {
            // Untuk kampus: tidak perlu join
            $this->db->from($table);
        }

        $query = $this->db->get();

        log_message('debug', 'get_by_id query: ' . $this->db->last_query());

        return $query->row();
    }

    // Insert new data
    public function insert($type, $data) {
        $table = $this->get_table($type);
        return $this->db->insert($table, $data);
    }

    // Update data
    public function update($type, $id, $data) {
        $table = $this->get_table($type);
        $id_field = $this->get_id_field($type);
        $this->db->where($id_field, $id);
        return $this->db->update($table, $data);
    }

    // Soft delete
    public function soft_delete($type, $id) {
        $table = $this->get_table($type);
        $id_field = $this->get_id_field($type);
        $this->db->where($id_field, $id);
        return $this->db->update($table, ['status' => 2]);
    }

    // Helper get table name
    private function get_table($type) {
        $type = strtolower($type);
        $mapping = [
            'kampus' => 'lokasi_kampus',
            'gedung' => 'lokasi_gedung',
            'rak'    => 'lokasi_rak'
        ];

        return $mapping[$type] ?? false;
    }

    // Helper get ID field
    private function get_id_field($type) {
        $type = strtolower($type);
        $mapping = [
            'kampus' => 'lokasikampus_id',
            'gedung' => 'lokasigedung_id',
            'rak'    => 'lokasirak_id'
        ];

        return $mapping[$type] ?? false;
    }

    // Get kampus for dropdown
    public function get_kampus_options() {
        $this->db->select('lokasikampus_id, nama_kampus');
        $this->db->from($this->table_kampus);
        $this->db->where('status', 1);
        $this->db->order_by('nama_kampus', 'ASC');
        return $this->db->get()->result();
    }

    // Get gedung by kampus for dropdown
    public function get_gedung_by_kampus($kampus_id) {
        $this->db->select('lokasigedung_id, nama_gedung');
        $this->db->from($this->table_gedung);
        $this->db->where('lokasikampus_id', $kampus_id);
        $this->db->where('status', 1);
        $this->db->order_by('nama_gedung', 'ASC');
        return $this->db->get()->result();
    }

    // Get rak by gedung for dropdown
    public function get_rak_by_gedung($gedung_id) {
        $this->db->select('lokasirak_id, nama_rak');
        $this->db->from($this->table_rak);
        $this->db->where('lokasigedung_id', $gedung_id);
        $this->db->where('status', 1);
        $this->db->order_by('nama_rak', 'ASC');
        return $this->db->get()->result();
    }
    
    /**
    * Cek apakah lokasirak_id masih digunakan di siperpus_inventaris
    * @param int $lokasirak_id
    * @return bool TRUE jika masih digunakan (ada record)
    */
    public function is_rak_used($lokasirak_id) {
       $this->db->where('lokasirak_id', $lokasirak_id);
       $this->db->from('siperpus_inventaris');
       return $this->db->count_all_results() > 0;
    }
    
    /**
    * Cek apakah kampus masih punya gedung terkait
    * @param int $lokasikampus_id
    * @return bool TRUE jika ada gedung
    */
    public function has_related_gedung($lokasikampus_id) {
       $this->db->where('lokasikampus_id', $lokasikampus_id);
       $this->db->where('status', 1); // hanya yang aktif
       $this->db->from('lokasi_gedung');
       return $this->db->count_all_results() > 0;
    }

    /**
    * Cek apakah gedung masih punya rak terkait
    * @param int $lokasigedung_id
    * @return bool TRUE jika ada rak
    */
    public function has_related_rak($lokasigedung_id) {
       $this->db->where('lokasigedung_id', $lokasigedung_id);
       $this->db->where('status', 1); // hanya yang aktif
       $this->db->from('lokasi_rak');
       return $this->db->count_all_results() > 0;
    }

}