<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Md_resensi extends CI_Model
{

    // Ambil data dengan filter, sort, dan limit (Server Side)
    public function get_resensi_server_side($search = "", $limit = 10, $offset = 0, $sort_field = "r.tgl_resensi", $sort_order = "DESC")
    {
        $this->db->select('r.*, b.judul, b.ISBN');
        $this->db->from('siperpus_resensi r');
        $this->db->join('siperpus_buku b', 'r.buku_id = b.buku_id', 'left');
        $this->db->where('r.status', 1);

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('b.judul', $search);
            $this->db->or_like('r.judul_buku', $search);
            $this->db->or_like('b.ISBN', $search);
            $this->db->or_like('r.isi_resensi', $search);
            $this->db->group_end();
        }

        $this->db->order_by($sort_field, $sort_order);
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    // Hitung total data setelah difilter
    public function count_filtered($search = "")
    {
        $this->db->from('siperpus_resensi r');
        $this->db->join('siperpus_buku b', 'r.buku_id = b.buku_id', 'left');
        $this->db->where('r.status', 1);

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('b.judul', $search);
            $this->db->or_like('r.isi_resensi', $search);
            $this->db->group_end();
        }
        return $this->db->count_all_results();
    }

    // Hitung total data asli (tanpa filter)
    public function count_all()
    {
        return $this->db->where('status', 1)->from('siperpus_resensi')->count_all_results();
    }

    // ... fungsi get_resensi_by_id, add, update, dan search_buku tetap sama seperti sebelumnya ...
    public function get_resensi_by_id($id)
    {
        $this->db->select('r.*, b.judul AS judul_db,b.ISBN');
        $this->db->from('siperpus_resensi r');
        $this->db->join('siperpus_buku b', 'r.buku_id = b.buku_id', 'left');
        $this->db->where('r.resensi_id', $id);
        return $this->db->get()->row();
    }
    public function add_resensi($data)
    {
        return $this->db->insert('siperpus_resensi', $data);
    }
    public function update_resensi($id, $data)
    {
        $this->db->where('resensi_id', $id);
        return $this->db->update('siperpus_resensi', $data);
    }
    public function search_buku($text)
    {
        $this->db->select('buku_id as id, judul as text, ISBN');
        $this->db->from('siperpus_buku');
        $this->db->group_start();
        $this->db->like('judul', $text);
        $this->db->or_like('ISBN', $text);
        $this->db->group_end();
        $this->db->limit(100);
        return $this->db->get()->result_array();
    }

    public function get_all_resensi()
    {
        // Tambahkan b.cover di select
        $this->db->select('r.*, b.judul, b.ISBN, b.cover');
        $this->db->from('siperpus_resensi r');
        $this->db->join('siperpus_buku b', 'r.buku_id = b.buku_id', 'left');
        $this->db->where('r.status', 1);
        $this->db->order_by('r.tgl_resensi', 'DESC');
        return $this->db->get()->result();
    }




    // 3. Ambil Detail Satu Resensi (Untuk Halaman Detail)
    public function get_detail_resensi($id_resensi)
    {
        $this->db->select('r.*, kq.nmkategori, 
        b.judul as judul_db, 
        b.ISBN as isbn_db, 
        b.cover as cover_db, 
        b.penulis as penulis_db, 
        b.thn_terbit as thn_db,
        b.edisi as edisi_db,
        b.cetakan as cetakan_db,
        b.jml_hal as hal_db,
        b.tajuksubyek as tajuk_db,
        b.penyadur as penyadur_db,
        b.no_klas as no_klas_db,
        p.nama_penerbit');
        $this->db->from('siperpus_resensi r');
        $this->db->join('siperpus_buku b', 'r.buku_id = b.buku_id', 'left');
        $this->db->join('siperpus_penerbit p', '(CASE WHEN r.buku_id is null THEN r.kd_penerbit = p.kd_penerbit ELSE b.kd_penerbit = p.kd_penerbit END)', 'left', false);
        $this->db->join('siperpus_kategori kq', '(CASE WHEN r.buku_id is null THEN r.idkategori = kq.idkategori ELSE b.idkategori = kq.idkategori END)', 'left', false);
        $this->db->where('r.resensi_id', $id_resensi);
        $this->db->where('r.status', 1);
        return $this->db->get()->row();
    }

    public function get_resensi_paginated($limit, $start, $search = null)
    {
        // Kita ambil kolom dari resensi (r) dan buku (b)
        // Gunakan alias jika nama kolom bentrok agar View bisa memilih
        $this->db->select('r.*, b.judul as judul_db, b.ISBN as isbn_db, b.cover as cover_db, b.penulis as penulis_db, b.thn_terbit as thn_db, p.nama_penerbit');
        $this->db->from('siperpus_resensi r');
        $this->db->join('siperpus_buku b', 'r.buku_id = b.buku_id', 'left');

        // Join penerbit: cek di tabel resensi dulu, jika kosong cek di tabel buku
        $this->db->join('siperpus_penerbit p', '(CASE WHEN r.buku_id is null THEN r.kd_penerbit = p.kd_penerbit ELSE b.kd_penerbit = p.kd_penerbit END)', 'left', false);

        $this->db->where('r.status', 1);

        // Logika Pencarian: Cek judul di kedua tabel
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('b.judul', $search);          // Judul dari DB
            $this->db->or_like('r.judul_buku', $search);  // Judul Manual
            $this->db->or_like('b.ISBN', $search);        // ISBN dari DB
            $this->db->or_like('r.isbn', $search);        // ISBN Manual
            $this->db->group_end();
        }

        $this->db->order_by('r.tgl_resensi', 'DESC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }
    // 2. Hitung Total Resensi (Untuk Pagination + Pencarian)
    public function get_total_resensi($search = null)
    {
        $this->db->from('siperpus_resensi r');
        $this->db->join('siperpus_buku b', 'r.buku_id = b.buku_id', 'left');
        $this->db->where('r.status', 1);

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('b.judul', $search);
            $this->db->or_like('r.judul_buku', $search);
            $this->db->or_like('b.ISBN', $search);
            $this->db->or_like('r.isbn', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }
}
