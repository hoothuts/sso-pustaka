<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Md_jurnal_akun extends CI_Model {

    var $table = 'jurnal_akun';

    public function get_datatables(
        $search = '',
        $vendor = '',
        $limit = 10,
        $offset = 0,
        $field = 'jurnalakun_id',
        $sort = 'DESC'
    ){

        $this->db->select('
            ja.*,
            jv.nama_vendor
        ');

        $this->db->from('jurnal_akun ja');

        $this->db->join(
            'jurnal_vendor jv',
            'jv.jurnalvendor_id = ja.jurnalvendor_id',
            'left'
        );

        $this->db->where('ja.status', 1);

        if($vendor){
            $this->db->where('ja.jurnalvendor_id', $vendor);
        }

        if($search){

            $this->db->group_start();

            $this->db->like('ja.username', $search);
            $this->db->or_like('jv.nama_vendor', $search);

            $this->db->group_end();
        }

        $this->db->order_by($field, $sort);

        $this->db->limit($limit, $offset);

        return $this->db->get()->result();
    }

    public function count_filtered($search = '', $vendor = ''){

        $this->db->from('jurnal_akun ja');

        $this->db->join(
            'jurnal_vendor jv',
            'jv.jurnalvendor_id = ja.jurnalvendor_id',
            'left'
        );

        $this->db->where('ja.status', 1);

        if($vendor){
            $this->db->where('ja.jurnalvendor_id', $vendor);
        }

        if($search){

            $this->db->group_start();

            $this->db->like('ja.username', $search);
            $this->db->or_like('jv.nama_vendor', $search);

            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->get_where($this->table, [
                'jurnalakun_id' => $id
            ])
            ->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        $this->db->where('jurnalakun_id', $id);

        return $this->db->update($this->table, $data);
    }

    public function soft_delete($id)
    {
        $this->db->where('jurnalakun_id', $id);

        return $this->db->update($this->table, [
            'status' => 2
        ]);
    }

    public function cek_duplikat($vendor_id, $username, $exclude_id = null){

        $this->db->where('jurnalvendor_id', $vendor_id);
        $this->db->where('username', $username);
        $this->db->where('status', 1);

        if($exclude_id !== null){
            $this->db->where(
                'jurnalakun_id !=',
                $exclude_id
            );
        }

        return $this->db
            ->get($this->table)
            ->row();
    }
    
    public function get_detail_with_vendor($id)
    {
        $this->db->select('
            ja.*,
            jv.nama_vendor
        ');

        $this->db->from('jurnal_akun ja');

        $this->db->join(
            'jurnal_vendor jv',
            'jv.jurnalvendor_id = ja.jurnalvendor_id',
            'left'
        );

        $this->db->where('ja.jurnalakun_id', $id);

        return $this->db->get()->row();
    }

    /* Ambil jurnal_akun yang penggunaan terakhir sudah lewat 2 jam */
    public function get_expired_account()
    {
        $this->db->select('ja.*, jl.nim');

        $this->db->from('jurnal_akun ja');

        $this->db->join('jurnal_vendor jv', 'jv.jurnalvendor_id = ja.jurnalvendor_id', 'left');

        $this->db->join('jurnal_log jl', 'jl.jurnalakun_id = ja.jurnalakun_id AND jl.jenis_aktivitas = "SHOW_ACCOUNT"', 'left');

        $this->db->where('ja.status', 1);

        $this->db->where('ja.is_used', 1);

        /* ONLY SINGLE SESSION VENDOR */

        $this->db->where('jv.is_multi_login', 0);

        $this->db->where('ja.last_used <=', date('Y-m-d H:i:s', strtotime('-2 hour')));

        return $this->db->get()->result();
    }
    
    /* Auto release jurnal_akun yg sudah expired */
    public function auto_release_account()
    {
        $list = $this->get_expired_account();

        $total = 0;

        foreach($list as $row){

            /* ================= RELEASE ACCOUNT ================= */

            $this->db->where(
                'jurnalakun_id',
                $row->jurnalakun_id
            );

            $this->db->update('jurnal_akun', [

                'is_used' => 0

            ]);

            /* ================= INSERT LOG ================= */

            $this->db->insert('jurnal_log', [
                'jurnalvendor_id' => $row->jurnalvendor_id,
                'jurnalakun_id'   => $row->jurnalakun_id,
                'nim'             => $row->nim,
                'jenis_aktivitas' => 'AUTO_RELEASE',
                'ip_address'      => '',
                'user_agent'      => '',
                'session_id'      => '',
                'keterangan'      => 'Release account otomatis oleh sistem cron',
                'tgl_post'        => date('Y-m-d H:i:s'),
                'status'          => 1
            ]);

            $total++;
        }

        return $total;
    }
    
    /* Manual release jurnal_akun */
    public function release_account($id)
    {
        /* ================= GET ACCOUNT ================= */

        $this->db->select('
            ja.*,
            jl.nim
        ');

        $this->db->from('jurnal_akun ja');

        $this->db->join(
            'jurnal_log jl',
            'jl.jurnalakun_id = ja.jurnalakun_id
            AND jl.jenis_aktivitas = "SHOW_ACCOUNT"',
            'left'
        );

        $this->db->where(
            'ja.jurnalakun_id',
            $id
        );

        $data = $this->db->get()->row();

        if(!$data){

            return [
                'status'  => 'error',
                'message' => 'Data account tidak ditemukan'
            ];
        }

        /* ================= CHECK STATUS ================= */

        if($data->is_used != 1){

            return [
                'status'  => 'error',
                'message' => 'Account tidak sedang digunakan'
            ];
        }

        /* ================= RELEASE ACCOUNT ================= */

        $this->db->where(
            'jurnalakun_id',
            $id
        );

        $this->db->update('jurnal_akun', [

            'is_used' => 0

        ]);

        /* ================= INSERT LOG ================= */

        $this->db->insert('jurnal_log', [

            'jurnalvendor_id' => $data->jurnalvendor_id,

            'jurnalakun_id'   => $data->jurnalakun_id,

            'nim'             => $data->nim,

            'jenis_aktivitas' => 'RELEASE_ACCOUNT',

            'ip_address'      => $this->input->ip_address(),

            'user_agent'      => $this->input->user_agent(),

            'session_id'      => session_id(),

            'keterangan'      =>
                'Release account manual oleh pustakawan',

            'tgl_post'        => date('Y-m-d H:i:s'),

            'status'          => 1

        ]);

        return [
            'status'  => 'success',
            'message' => 'Account berhasil direlease'
        ];
    }

    
}