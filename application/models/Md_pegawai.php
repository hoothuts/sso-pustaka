<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Md_pegawai extends CI_Model {

    var $table = 'pegawai';

    /* =========================================================
     * DATATABLE PEGAWAI AKTIF
     * ========================================================= */
    public function get_datatables_aktif($search = '', $limit = 10, $offset = 0, $field = 'pegawai_id', $sort = 'DESC')
    {
        $this->db->from($this->table);

        $this->db->where('status', 1);
        $this->db->where('status_anggota', 'Aktif');

        if ($search) {
            $this->db->group_start();
            $this->db->like('nip', $search);
            $this->db->or_like('nama', $search);
            $this->db->or_like('nik', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('telp', $search);
            $this->db->group_end();
        }

        $this->db->order_by($field, $sort);
        $this->db->limit($limit, $offset);

        return $this->db->get()->result();
    }

    public function count_filtered_aktif($search = '')
    {
        $this->db->from($this->table);

        $this->db->where('status', 1);
        $this->db->where('status_anggota', 'Aktif');

        if ($search) {
            $this->db->group_start();
            $this->db->like('nip', $search);
            $this->db->or_like('nama', $search);
            $this->db->or_like('nik', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('telp', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    /* =========================================================
     * DATATABLE PEGAWAI NON AKTIF
     * ========================================================= */
    public function get_datatables_nonaktif($search = '', $limit = 10, $offset = 0, $field = 'pegawai_id', $sort = 'DESC')
    {
        $this->db->from($this->table);

        $this->db->where('status', 1);
        $this->db->where('status_anggota', 'Non Aktif');

        if ($search) {
            $this->db->group_start();
            $this->db->like('nip', $search);
            $this->db->or_like('nama', $search);
            $this->db->or_like('nik', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('telp', $search);
            $this->db->group_end();
        }

        $this->db->order_by($field, $sort);
        $this->db->limit($limit, $offset);

        return $this->db->get()->result();
    }

    public function count_filtered_nonaktif($search = '')
    {
        $this->db->from($this->table);

        $this->db->where('status', 1);
        $this->db->where('status_anggota', 'Non Aktif');

        if ($search) {
            $this->db->group_start();
            $this->db->like('nip', $search);
            $this->db->or_like('nama', $search);
            $this->db->or_like('nik', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('telp', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    /* =========================================================
     * GET DETAIL
     * ========================================================= */
    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, [
            'pegawai_id' => $id
        ])->row();
    }
    
    /* =========================================================
     * GET DETAIL BY NIP
     * ========================================================= */
    public function get_by_nip($nip)
    {
        return $this->db->get_where($this->table, [
            'nip' => $nip, 'status' => 1
        ])->row();
    }
    
    public function get_by_nip_aktif($nip)
    {
        return $this->db->get_where($this->table, [
            'nip' => $nip, 'status_anggota' => 'Aktif', 'status' => 1
        ])->row();
    }

    /* =========================================================
     * INSERT
     * ========================================================= */
    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    /* =========================================================
     * UPDATE
     * ========================================================= */
    public function update($id, $data)
    {
        $this->db->where('pegawai_id', $id);

        return $this->db->update($this->table, $data);
    }

    /* =========================================================
     * SOFT DELETE
     * ========================================================= */
    public function soft_delete($id)
    {
        $this->db->where('pegawai_id', $id);

        return $this->db->update($this->table, [
            'status' => 2
        ]);
    }

    /* =========================================================
     * CEK DUPLIKAT NIP
     * ========================================================= */
    public function cek_duplicate_nip($nip, $exclude_id = null)
    {
        $this->db->where('nip', $nip);
        $this->db->where('status', 1);

        if ($exclude_id !== null) {
            $this->db->where('pegawai_id !=', $exclude_id);
        }

        return $this->db->get($this->table)->row();
    }

    /* =========================================================
     * CEK DUPLIKAT NIK
     * ========================================================= */
    public function cek_duplicate_nik($nik, $exclude_id = null)
    {
        $this->db->where('nik', $nik);
        $this->db->where('status', 1);

        if ($exclude_id !== null) {
            $this->db->where('pegawai_id !=', $exclude_id);
        }

        return $this->db->get($this->table)->row();
    }

    /* =========================================================
     * CEK DUPLIKAT EMAIL
     * ========================================================= */
    public function cek_duplicate_email($email, $exclude_id = null)
    {
        $this->db->where('email', $email);
        $this->db->where('status', 1);

        if ($exclude_id !== null) {
            $this->db->where('pegawai_id !=', $exclude_id);
        }

        return $this->db->get($this->table)->row();
    }
    
    function get_kartu($id) {
        return $this->db->select("nip as no_angg, nama as nm_angg, '' as prodi, status")->get_where('pegawai', array('nip' => $id))->row();
    }
    
    function count_pegawai() {
        return $this->db->select("count(*) as total")->get_where('pegawai', array('status' => 1,'status_anggota' => 'Aktif'))->row();
    }

}