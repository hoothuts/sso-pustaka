<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Md_bepus_request extends CI_Model {

    var $table = 'bepus_request';

    public function get_datatables($search = '', $limit = 10, $offset = 0) {
        $datatable = $this->input->post('datatable');

        $kelas_filter = $datatable['kelas_filter'] 
            ?? $datatable['query']['kelas_filter'] 
            ?? null;

        $tahun_filter = $datatable['tahun_filter'] 
            ?? $datatable['query']['tahun_filter'] 
            ?? null;

        $this->db->select('
            r.bepusrequest_id,
            r.nim,
            r.status_pengajuan,
            r.tgl_post,
            r.status,
            r.no_request,
            r.tahun_akademik,
            s.nama,
            s.kelas,
            YEAR(s.tgl_masuk) AS tahun_masuk,
            (
                SELECT COUNT(*) 
                FROM bepus_request_detail d2 
                JOIN bepus_syarat sy2 ON sy2.bepussyarat_id = d2.bepussyarat_id 
                WHERE d2.bepusrequest_id = r.bepusrequest_id 
                  AND d2.status = 1 
                  AND sy2.is_isian = "Ya" 
                  AND d2.status_syarat != "OK"
            ) AS belum_lengkap_count
        ');
        
        $this->db->from('bepus_request r');
        $this->db->join('vwsiswa s', 's.nis = r.nim', 'left');
        $this->db->where('r.status', 1);

        // Filter Search
        if ($search) {
            $this->db->group_start();
            $this->db->like('r.no_request', $search);
            $this->db->or_like('r.nim', $search);
            $this->db->or_like('s.nama', $search);
            $this->db->or_like('s.kelas', $search);
            $this->db->group_end();
        }

        // Filter Kelas
        if (!empty($kelas_filter)) {
            $this->db->where('s.kelas', $kelas_filter);
        }

        // Filter Tahun Masuk
        if (!empty($tahun_filter)) {
            $this->db->where('r.tahun_akademik', $tahun_filter);
        }

        $this->db->order_by('r.tgl_post', 'DESC');
        $this->db->limit($limit, $offset);

        return $this->db->get()->result();
    }

    public function count_filtered($search = '') {
        $datatable = $this->input->post('datatable');

        $kelas_filter = $datatable['kelas_filter'] 
            ?? $datatable['query']['kelas_filter'] 
            ?? null;

        $tahun_filter = $datatable['tahun_filter'] 
            ?? $datatable['query']['tahun_filter'] 
            ?? null;

        $this->db->select('r.bepusrequest_id');
        $this->db->from('bepus_request r');
        $this->db->join('vwsiswa s', 's.nis = r.nim', 'left');
        $this->db->where('r.status', 1);

        if ($search) {
            $this->db->group_start();
            $this->db->like('r.no_request', $search);
            $this->db->or_like('r.nim', $search);
            $this->db->or_like('s.nama', $search);
            $this->db->or_like('s.kelas', $search);
            $this->db->group_end();
        }

        if (!empty($kelas_filter)) {
            $this->db->where('s.kelas', $kelas_filter);
        }

        if (!empty($tahun_filter)) {
            $this->db->where('r.tahun_akademik', $tahun_filter);
        }

        return $this->db->count_all_results();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['bepusrequest_id' => $id])->row();
    }

    public function get_full_request($id) {
        $this->db->select('r.*, s.nama, s.jk, s.alamat, s.telepon, s.kelas, us.ttd_digital as ttd_kaperpus, us.nip_pegawai as nip_kaperpus, us.name as nama_kaperpus, pstk.ttd_digital as ttd_pstk, pstk.nip_pegawai as nip_pstk, pstk.name as nama_pstk');
        $this->db->from('bepus_request r');
        $this->db->join('vwsiswa s', 's.nis = r.nim', 'left');
        $this->db->join('siperpus_sysuser us', 'us.idsysuser = r.idsysuser_kaperpus', 'left');
        $this->db->join('(select bepusrequest_id,max(bepusrequestdetail_id) as bepusrequestdetail_id from bepus_request_detail where status=1 and check_by is not null group by bepusrequest_id) as last_dt', 'last_dt.bepusrequest_id = r.bepusrequest_id', 'left');
        $this->db->join('bepus_request_detail rd', 'rd.bepusrequestdetail_id = last_dt.bepusrequestdetail_id', 'left');
        $this->db->join('siperpus_sysuser pstk', 'pstk.idsysuser = rd.check_by', 'left');
        $this->db->where('r.bepusrequest_id', $id);
        $request = $this->db->get()->row();

        if ($request) {
            $this->db->select('d.*, sy.persyaratan, sy.level, sy.is_isian');
            $this->db->from('bepus_request_detail d');
            $this->db->join('bepus_syarat sy', 'sy.bepussyarat_id = d.bepussyarat_id');
            $this->db->where('d.bepusrequest_id', $id);
            $this->db->where('d.status', 1);
            $request->details = $this->db->get()->result();
            //hibah
            $this->db->where('bepusrequest_id', $id);
            $this->db->where('status',1);
            $request->hibah = $this->db->get('bepus_request_hibah')->result();
        }
        return $request;
    }

    public function cek_request_aktif($nim) {
        $this->db->where('nim', $nim);
        $this->db->where('status', 1);
        //$this->db->where('status_pengajuan', 'Pending');
        return $this->db->get('bepus_request')->row();
    }

    public function insert_request($data) {
        $this->db->insert('bepus_request', $data);
        return $this->db->insert_id();
    }

    public function insert_detail($data) {
        return $this->db->insert('bepus_request_detail', $data);
    }

    public function update_request($id, $data) {
        $this->db->where('bepusrequest_id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete_detail($bepusrequest_id) {
        $this->db->where('bepusrequest_id', $bepusrequest_id);
        return $this->db->delete('bepus_request_detail');
    }

    public function update_detail($detail_id, $data) {
        $this->db->where('bepusrequestdetail_id', $detail_id);
        return $this->db->update('bepus_request_detail', $data);
    }

    public function soft_delete($id) {
        $this->db->where('bepusrequest_id', $id);
        return $this->db->update($this->table, ['status' => 2]);
    }
    
    // Helper untuk cek apakah syarat sudah diceklis saat edit
    public function is_checked($bepusrequest_id, $bepussyarat_id) {
        $this->db->where('bepusrequest_id', $bepusrequest_id);
        $this->db->where('bepussyarat_id', $bepussyarat_id);
        $this->db->where('status_syarat', 'OK');
        return $this->db->get('bepus_request_detail')->row();
    }
    
    // Cek apakah semua syarat yang is_isian='Ya' sudah OK
    public function is_all_required_ok($bepusrequest_id) {
        $this->db->select('d.status_syarat, s.is_isian');
        $this->db->from('bepus_request_detail d');
        $this->db->join('bepus_syarat s', 's.bepussyarat_id = d.bepussyarat_id');
        $this->db->where('d.bepusrequest_id', $bepusrequest_id);
        $this->db->where('d.status', 1);
        $this->db->where('s.is_isian', 'Ya');

        $result = $this->db->get()->result();

        foreach ($result as $row) {
            if ($row->status_syarat !== 'OK') {
                return false;
            }
        }
        return true;
    }
    
    // Generate No Request reset tiap tahun)
    public function generate_no_request() {
        $tahun = date('y');           // 2 digit tahun
        $bulan = date('m');           // 2 digit bulan

        // Ambil nomor urut terakhir tahun ini
        $this->db->select('no_request');
        $this->db->from('bepus_request');
        $this->db->where('no_request LIKE', $tahun . '/BP/' . $bulan . '/%');
        $this->db->order_by('no_request', 'DESC');
        $this->db->limit(1);
        $last = $this->db->get()->row();

        if ($last && preg_match('/(\d{4})$/', $last->no_request, $matches)) {
            $next = str_pad((int)$matches[1] + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $next = '0001';
        }

        return $tahun . '/BP/' . $bulan . '/' . $next;
    }
    
     public function get_tahun_akademik() {
        $this->db->select('distinct(tahun_akademik)');
        $this->db->where('status', 1);
        //$this->db->where('status_pengajuan', 'Pending');
        return $this->db->get('bepus_request')->result();
    }
    
    public function get_all_export($search = '', $kelas = null, $tahun = null)
    {
        $this->db->select('
            r.bepusrequest_id,
            r.nim,
            r.status_pengajuan,
            r.tgl_post,
            r.no_request,
            r.tahun_akademik,
            r.tgl_approve,
            s.nama,
            s.kelas,
            (
                SELECT COUNT(*) 
                FROM bepus_request_detail d2 
                JOIN bepus_syarat sy2 ON sy2.bepussyarat_id = d2.bepussyarat_id 
                WHERE d2.bepusrequest_id = r.bepusrequest_id 
                  AND d2.status = 1 
                  AND sy2.is_isian = "Ya" 
                  AND d2.status_syarat != "OK"
            ) AS belum_lengkap_count
        ');

        $this->db->from('bepus_request r');
        $this->db->join('vwsiswa s', 's.nis = r.nim', 'left');
        $this->db->where('r.status', 1);

        // SEARCH
        if ($search) {
            $this->db->group_start();
            $this->db->like('r.no_request', $search);
            $this->db->or_like('r.nim', $search);
            $this->db->or_like('s.nama', $search);
            $this->db->or_like('s.kelas', $search);
            $this->db->group_end();
        }

        // FILTER
        if (!empty($kelas)) {
            $this->db->where('s.kelas', $kelas);
        }

        if (!empty($tahun)) {
            $this->db->where('r.tahun_akademik', $tahun);
        }

        $this->db->order_by('r.tgl_post', 'DESC');

        return $this->db->get()->result();
    }
    
    public function generate_no_request_prodi($nim)
    {
        $tahun = date('Y');

        /*
        |--------------------------------------------------------------------------
        | Ambil Prodi Mahasiswa
        |--------------------------------------------------------------------------
        */
        $mhs = $this->db->select('kdpst')
                ->where('nis', $nim)
                ->get('vwsiswa')->row();

        if (!$mhs) {
            return [
                'status'  => false,
                'message' => 'Data program studi mahasiswa tidak ditemukan'
            ];
        }

        $kdpst = $mhs->kdpst;

        /*
        |--------------------------------------------------------------------------
        | Ambil Prefix Nomor Surat
        |--------------------------------------------------------------------------
        */
        $setting = $this->db
            ->where('kdpst', $kdpst)
            ->where('tahun', $tahun)
            ->where('status', 1)
            ->get('bepus_prodi_no')
            ->row();

        if (!$setting) {
            return [
                'status'  => false,
                'message' => 'Format nomor surat bebas pustaka belum diset untuk program studi dan tahun ini.'
            ];
        }

        $prefix = trim($setting->kode_nomor);

        /*
        |--------------------------------------------------------------------------
        | Cari Nomor Terakhir Berdasarkan:
        | - Program Studi
        | - Tahun
        |--------------------------------------------------------------------------
        */
        $this->db->select('br.no_request');
        $this->db->from('bepus_request br');
        $this->db->join('vwsiswa vs', 'vs.nis = br.nim','inner');
        $this->db->where('vs.kdpst', $kdpst);
        $this->db->where('YEAR(br.tgl_post)', $tahun);
        $this->db->where('br.status', 1);
        $this->db->order_by('br.bepusrequest_id', 'DESC');
        $this->db->limit(1);

        $last = $this->db->get()->row();

        /*
        |--------------------------------------------------------------------------
        | Ambil Sequence
        |--------------------------------------------------------------------------
        */
        $next = 1;

        if ($last) {
            if (
                preg_match(
                    '/(\d{3})\/'.$tahun.'$/',
                    $last->no_request,
                    $match
                )
            ) {
                $next = ((int)$match[1]) + 1;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Format Nomor Surat
        |--------------------------------------------------------------------------
        */
        $no_request =
            $prefix .
            str_pad($next, 3, '0', STR_PAD_LEFT) .
            '/' .
            $tahun;

        return [
            'status'     => true,
            'no_request' => $no_request,
            'prefix'     => $prefix,
            'sequence'   => $next,
            'kdpst'      => $kdpst
        ];
    }
   
    
}