<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Md_jurnal_log extends CI_Model {

    public function get_datatables(
            $search = '',
            $vendor = '',
            $aktivitas = '',
            $tanggal_awal = '',
            $tanggal_akhir = '',
            $limit = 10,
            $offset = 0,
            $field = 'jl.jurnallog_id',
            $sort = 'DESC'
    ) {

        $this->query_builder(
                $search,
                $vendor,
                $aktivitas,
                $tanggal_awal,
                $tanggal_akhir
        );

        $this->db->order_by($field, $sort);

        $this->db->limit($limit, $offset);

        return $this->db->get()->result();
    }

    public function count_filtered(
            $search = '',
            $vendor = '',
            $aktivitas = '',
            $tanggal_awal = '',
            $tanggal_akhir = ''
    ) {

        $this->query_builder(
                $search,
                $vendor,
                $aktivitas,
                $tanggal_awal,
                $tanggal_akhir
        );

        return $this->db->count_all_results();
    }

    private function query_builder(
            $search = '',
            $vendor = '',
            $aktivitas = '',
            $tanggal_awal = '',
            $tanggal_akhir = ''
    ) {

        $this->db->select('
            jl.*,
            jv.nama_vendor,
            ja.username,
            vs.nama,
            vs.kelas
        ');

        $this->db->from('jurnal_log jl');

        $this->db->join(
                'jurnal_vendor jv',
                'jv.jurnalvendor_id = jl.jurnalvendor_id',
                'left'
        );

        $this->db->join(
                'jurnal_akun ja',
                'ja.jurnalakun_id = jl.jurnalakun_id',
                'left'
        );

        $this->db->join(
                'vwsiswa vs',
                'vs.nis = jl.nim',
                'left'
        );

        $this->db->where('jl.status', 1);

        if ($vendor) {
            $this->db->where(
                    'jl.jurnalvendor_id',
                    $vendor
            );
        }

        if ($aktivitas) {
            $this->db->where(
                    'jl.jenis_aktivitas',
                    $aktivitas
            );
        }

        if ($tanggal_awal && $tanggal_akhir) {

            $this->db->where(
                    'DATE(jl.tgl_post) >=',
                    $tanggal_awal
            );

            $this->db->where(
                    'DATE(jl.tgl_post) <=',
                    $tanggal_akhir
            );
        }

        if ($search) {

            $this->db->group_start();

            $this->db->like('jl.nim', $search);
            $this->db->or_like('vs.nama', $search);
            $this->db->or_like('jv.nama_vendor', $search);
            $this->db->or_like('ja.username', $search);

            $this->db->group_end();
        }
    }

    public function get_detail($id) {
        $this->db->select('
            jl.*,
            jv.nama_vendor,
            ja.username,
            vs.nama,
            vs.kelas
        ');

        $this->db->from('jurnal_log jl');

        $this->db->join(
                'jurnal_vendor jv',
                'jv.jurnalvendor_id = jl.jurnalvendor_id',
                'left'
        );

        $this->db->join(
                'jurnal_akun ja',
                'ja.jurnalakun_id = jl.jurnalakun_id',
                'left'
        );

        $this->db->join(
                'vwsiswa vs',
                'vs.nis = jl.nim',
                'left'
        );

        $this->db->where(
                'jl.jurnallog_id',
                $id
        );

        return $this->db->get()->row();
    }

    public function get_total_today() {
        return $this->db
                        ->where('DATE(tgl_post)', date('Y-m-d'))
                        ->where('status', 1)
                        ->count_all_results('jurnal_log');
    }

    public function get_total_month() {
        return $this->db
                        ->where('MONTH(tgl_post)', date('m'))
                        ->where('YEAR(tgl_post)', date('Y'))
                        ->where('status', 1)
                        ->count_all_results('jurnal_log');
    }

    public function get_total_user() {
        $this->db->select('COUNT(DISTINCT nim) as total');

        $this->db->from('jurnal_log');

        $this->db->where('status', 1);

        return $this->db->get()->row()->total;
    }

    public function get_chart_7_hari() {
        $this->db->select('
        DATE(tgl_post) as tanggal,
        COUNT(*) as total
    ');

        $this->db->from('jurnal_log');

        $this->db->where('status', 1);

        $this->db->where(
                'DATE(tgl_post) >=',
                date('Y-m-d', strtotime('-6 day'))
        );

        $this->db->group_by('DATE(tgl_post)');

        $this->db->order_by('DATE(tgl_post)', 'ASC');

        return $this->db->get()->result();
    }

    public function get_top_prodi() {
        $this->db->select('
        vs.kelas,
        COUNT(*) as total
    ');

        $this->db->from('jurnal_log jl');

        $this->db->join(
                'vwsiswa vs',
                'vs.nis = jl.nim',
                'left'
        );

        $this->db->where('jl.status', 1);

        $this->db->group_by('vs.kelas');

        $this->db->order_by('total', 'DESC');

        $this->db->limit(10);

        return $this->db->get()->result();
    }

    public function get_aktivitas_distribution() {
        $this->db->select('
        jenis_aktivitas,
        COUNT(*) as total
    ');

        $this->db->from('jurnal_log');

        $this->db->where('status', 1);

        $this->db->group_by('jenis_aktivitas');

        $this->db->order_by('total', 'DESC');

        return $this->db->get()->result();
    }
    
    public function get_chart_access($period = '30d')
    {
        if($period == '7d'){

            $this->db->select('
                DATE(tgl_post) as label,
                COUNT(*) as total
            ');

            $this->db->from('jurnal_log');

            $this->db->where('status', 1);

            $this->db->where(
                'DATE(tgl_post) >=',
                date('Y-m-d', strtotime('-6 day'))
            );

            $this->db->group_by('DATE(tgl_post)');

            $this->db->order_by('DATE(tgl_post)', 'ASC');

        }

        else if($period == '30d'){

            $this->db->select('
                DATE(tgl_post) as label,
                COUNT(*) as total
            ');

            $this->db->from('jurnal_log');

            $this->db->where('status', 1);

            $this->db->where(
                'DATE(tgl_post) >=',
                date('Y-m-d', strtotime('-29 day'))
            );

            $this->db->group_by('DATE(tgl_post)');

            $this->db->order_by('DATE(tgl_post)', 'ASC');

        }

        else if($period == '1y'){

            $this->db->select("
                DATE_FORMAT(tgl_post, '%b %Y') as label,
                COUNT(*) as total,
                YEAR(tgl_post) as tahun,
                MONTH(tgl_post) as bulan
            ");

            $this->db->from('jurnal_log');

            $this->db->where('status', 1);

            $this->db->where(
                'DATE(tgl_post) >=',
                date('Y-m-d', strtotime('-11 month'))
            );

            $this->db->group_by('YEAR(tgl_post), MONTH(tgl_post)');

            $this->db->order_by('tahun', 'ASC');

            $this->db->order_by('bulan', 'ASC');
        }

        return $this->db->get()->result();
    }

}
