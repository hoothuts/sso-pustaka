<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Md_buku_baru extends CI_Model {

    var $table = 'buku_baru';

    public function get_datatables($search = '', $limit = 10, $offset = 0, $field = 'bukubaru_id', $sort = 'DESC') {
        $this->db->select('bb.*, b.judul, b.ISBN, b.no_klas, b.cover,  b.thn_terbit as tahun_terbit,b.penulis, sp.nama_penerbit as penerbit');
        $this->db->from($this->table . ' bb');
        $this->db->join('siperpus_buku b', 'b.buku_id = bb.buku_id', 'left');
        $this->db->join('siperpus_penerbit sp','sp.kd_penerbit=b.kd_penerbit','left');
        $this->db->where('bb.status', 1); // <-- INI PENTING

        if ($search) {
            $this->db->group_start();
            $this->db->like('b.judul', $search);
            $this->db->or_like('b.ISBN', $search);
            $this->db->or_like('b.penulis', $search);
            $this->db->group_end();
        }

        $this->db->order_by($field, $sort);
        $this->db->limit($limit, $offset);

        return $this->db->get()->result();
    }

    public function count_filtered($search = '') {
        $this->db->from($this->table . ' bb');
        $this->db->join('siperpus_buku b', 'b.buku_id = bb.buku_id', 'left');
        $this->db->where('bb.status', 1);
        if ($search) {
            $this->db->group_start();
            $this->db->like('b.judul', $search);
            $this->db->or_like('bb.author', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['bukubaru_id' => $id])->row();
    }

    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        $this->db->where('bukubaru_id', $id);
        return $this->db->update($this->table, $data);
    }

    public function soft_delete($id) {
        $this->db->where('bukubaru_id', $id);
        return $this->db->update($this->table, ['status' => 2]);
    }

    public function search_buku($text)
    {
        $this->db->select('buku_id, judul, ISBN');
        $this->db->from('siperpus_buku');
        $this->db->group_start();
        $this->db->like('judul', $text);
        $this->db->or_like('ISBN', $text);
        $this->db->group_end();
        $this->db->limit(100);

        return $this->db->get()->result();
    }
    
    public function get_buku_baru_front($limit = 12)
    {
        $this->db->select('bb.*, b.judul, b.cover, b.penulis, b.ISBN, b.no_klas');
        $this->db->from('buku_baru bb');
        $this->db->join('siperpus_buku b', 'b.buku_id = bb.buku_id');
        $this->db->where('bb.status', 1);
        $this->db->where('bb.is_tampil', 'Ya');
        $this->db->order_by('bb.tgl_post', 'DESC');
        $this->db->limit($limit);

        return $this->db->get()->result();
    }
    
    public function cek_duplikat_buku_id($buku_id, $exclude_id = null) {
        $this->db->where('buku_id', $buku_id);
        $this->db->where('status', 1);
        
        if ($exclude_id !== null) {
            $this->db->where('bukubaru_id !=', $exclude_id);
        }

        return $this->db->get($this->table)->row(); 
    }
}