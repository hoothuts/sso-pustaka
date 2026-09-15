<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_siperpus_presensi extends CI_Model {
    var $table = 'siperpus_presensi';
    var $column_order = array(array('tanggal', 'tanggal')); //set column field database for datatable orderable
    var $column_search = array('tanggal'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('tanggal' => 'desc'); // default order

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

        if ($this->input->post('datatable[query][kategori]')) {
            $this->db->where('idsysgroup', $this->input->post('datatable[query][group]'));
        }

        if ($this->input->post('datatable[query][klasifikasi]')) {
            $this->db->where('active', $this->input->post('datatable[query][status]'));
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
        $this->db->join('siperpus_presensi_nonanggota', 'siperpus_presensi.tanggal = siperpus_presensi_nonanggota.tanggal');
        $this->db->group_by("DATE_FORMAT(tanggal, '%c %d %Y')");
        $query = $this->db->get();
        return $query->result();
    }

    function getPresensi7Hari() {
        $hasil = $this->db->query("SELECT 
                    tanggal,
                    SUM(jumlah) AS jumlah
                FROM (
                    SELECT 
                        DATE(tanggal) AS tanggal,
                        COUNT(*) AS jumlah
                    FROM siperpus_presensi
                    GROUP BY DATE(tanggal)

                    UNION ALL

                    SELECT 
                        DATE(tanggal) AS tanggal,
                        COUNT(*) AS jumlah
                    FROM siperpus_presensi_nonanggota
                    GROUP BY DATE(tanggal)
                ) t
                GROUP BY tanggal
                ORDER BY tanggal DESC
                LIMIT 7;");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function getDetailByTanggal($tgl) {
        $hasil = $this->db->query("SELECT DATE_FORMAT(tanggal, '%Y-%M-%d') as tgl,DATE_FORMAT(tanggal, '%H:%i:%s') as jam,nomor,jenis FROM ( select tanggal,nis as nomor, 'anggota' as jenis from siperpus_presensi where cast(tanggal as date)='$tgl' UNION ALL SELECT tanggal,nama as nomor, 'nonanggota' as jenis FROM siperpus_presensi_nonanggota where cast(tanggal as date)='$tgl' ) p");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function getJumlah($date) {
        $hasil = $this->db->query("SELECT sum(Acount) + sum(Bcount) as jumlah FROM (
		   SELECT count(tanggal)as Acount,0 as Bcount FROM siperpus_presensi where cast(tanggal as date) = '$date' UNION ALL SELECT 0 as Acount,count(tanggal) as Bcount FROM siperpus_presensi_nonanggota     where cast(tanggal as date) = '$date'
			) p");

        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data[0]->jumlah;
        } else {
            return 0;
        }
    }

    function addPresensi($data) {
        $this->db->insert('siperpus_presensi', $data);
        return 1;
    }

    function addPresensiNon($data) {
        $this->db->insert('siperpus_presensi_nonanggota', $data);
        return 1;
    }

    function getPresensiHari($hari) {
        $hasil = $this->db->query("SELECT distinct(tanggal) FROM ( select tanggal from siperpus_presensi UNION ALL SELECT tanggal FROM siperpus_presensi_nonanggota) p group by DATE_FORMAT(tanggal, '%c %d %Y') order by tanggal desc limit $hari");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function getPresensiPerHari($limit = 10) {
        $sql = "
                SELECT 
                    DATE(tanggal) AS tanggal,
                    COUNT(*) AS jumlah
                FROM (
                    SELECT tanggal FROM siperpus_presensi
                    UNION ALL
                    SELECT tanggal FROM siperpus_presensi_nonanggota
                ) p
                GROUP BY DATE(tanggal)
                ORDER BY DATE(tanggal) DESC
                LIMIT ?
            ";

        $query = $this->db->query($sql, [$limit]);
        return $query->result();
    }

    // Mode 1: Akumulasi per Prodi (Checkbox "Per Tanggal" TIDAK dicentang)
    function getStatistikPresensiProdi($awal = '', $akhir = '') {
        // Menampilkan semua prodi meskipun jumlah kunjungan 0
        $this->db->select('vwp.nmmspst as label, COUNT(p.presensi_id) as total');
        $this->db->from('vwprodi vwp');
        $this->db->join('vwsiswa s', 'vwp.nmmspst = s.kelas', 'left');
        $this->db->join('siperpus_presensi p', 's.nis = p.nis', 'left');

        if ($awal != '' && $akhir != '') {
            $this->db->group_start()
                    ->where('DATE(p.tanggal) >=', $awal)
                    ->where('DATE(p.tanggal) <=', $akhir)
                    ->or_where('p.tanggal IS NULL')
                    ->group_end();
        }

        $this->db->group_by('vwp.nmmspst');
        $this->db->order_by('vwp.nmmspst', 'ASC');
        return $this->db->get()->result();
    }

    // Mode 2: Tren per Tanggal (Checkbox "Per Tanggal" DICENTANG)
    function getStatistikPresensiHarian($awal, $akhir) {
        // Menggunakan RIGHT JOIN dari vwprodi agar prodi dengan nilai 0 tetap tampil
        $query = "SELECT DATE_FORMAT(p.tanggal, '%d %b') as tgl, vwp.nmmspst as prodi, COUNT(p.presensi_id) as total
                      FROM siperpus_presensi p
                      JOIN vwsiswa s ON p.nis = s.nis
                      RIGHT JOIN vwprodi vwp ON s.kelas = vwp.nmmspst
                      WHERE DATE(p.tanggal) >= '$awal' AND DATE(p.tanggal) <= '$akhir'
                      GROUP BY DATE(p.tanggal), vwp.nmmspst
                      ORDER BY DATE(p.tanggal) ASC, vwp.nmmspst ASC";

        return $this->db->query($query)->result();
    }

    function getPresensiByKelas($kls) {
        $query = "SELECT count(*) as total FROM siperpus_presensi WHERE nis in(select nis from vwsiswa where kelas='$kls')";
        $hasil = $this->db->query($query)->result();
        //$hasil = $this->db->get_where('siperpus_inventaris', array('ISBN' => $id))->result();
        $data = $hasil;
        return $data;
    }

    function getPresensiByKelas_tgl($kls, $tglAwal, $tglAkhir) {
        $query = "SELECT count(*) as total FROM siperpus_presensi WHERE nis in(select nis from vwsiswa where kelas='$kls') and date(tanggal) >= '$tglAwal' and date(tanggal) <= '$tglAkhir'";
        $hasil = $this->db->query($query)->result();
        //$hasil = $this->db->get_where('siperpus_inventaris', array('ISBN' => $id))->result();
        $data = $hasil;
        return $data;
    }

    function getKunjunganProdiByTahun($tahun, $bulan, $prodi) {
        $q = '';
        if ($bulan != '')
            $q = "and month(tanggal)='$bulan'";
        $query = "SELECT count(*) as total FROM siperpus_presensi WHERE year(tanggal)='$tahun' $q and nis in(select nis from vwsiswa where kelas='$prodi')";
        $hasil = $this->db->query($query);
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data[0]->total;
        } else {
            return 0;
        }
    }
    
    function getPengunjungTeraktifByPeriode($awal = '', $akhir = '', $limit = '') {
        // Menampilkan semua prodi meskipun jumlah kunjungan 0
        $this->db->select('s.nis, s.nama, s.kelas, s.angkatan, COUNT(p.presensi_id) as total');
        $this->db->from('vwsiswa s');
        $this->db->join('siperpus_presensi p', 's.nis = p.nis');

        if ($awal != '' && $akhir != '') {
            $this->db->where('DATE(p.tanggal) >=', $awal);
            $this->db->where('DATE(p.tanggal) <=', $akhir);
        }
        if (!empty($limit)) {
            $this->db->limit($limit);
        }else{
            $this->db->limit(2);
        }
        
        //$this->db->where('s.status_siswa', 'A');
        
        $this->db->group_by('s.nis');
        $this->db->order_by('total', 'DESC');
        return $this->db->get()->result();
    }
    
        // Mode 1: Akumulasi per Pegawai (Checkbox "Per Tanggal" TIDAK dicentang)
    function getStatistikPresensiPegawai($awal = '', $akhir = '') {
        // Menampilkan semua pegawai meskipun jumlah kunjungan 0
        $this->db->select('pg.nama as label, COUNT(p.presensi_id) as total');
        $this->db->from('pegawai pg');
        $this->db->join('siperpus_presensi p', 'pg.nip = p.nis', 'inner');

        if ($awal != '' && $akhir != '') {
            $this->db->group_start()
                    ->where('DATE(p.tanggal) >=', $awal)
                    ->where('DATE(p.tanggal) <=', $akhir)
                    ->or_where('p.tanggal IS NULL')
                    ->group_end();
        }

        $this->db->group_by('pg.nama');
        $this->db->order_by('pg.nama', 'ASC');
        return $this->db->get()->result();
    }

    // Mode 2: Tren per Tanggal per Pegawai (Checkbox "Per Tanggal" DICENTANG)
    function getStatistikPresensiHarianPegawai($awal, $akhir) {
        $query = "SELECT DATE_FORMAT(p.tanggal, '%d %b') as tgl, pg.nama as pegawai, COUNT(p.presensi_id) as total
                      FROM siperpus_presensi p
                      JOIN pegawai pg ON p.nis = pg.nip
                      WHERE DATE(p.tanggal) >= '$awal' AND DATE(p.tanggal) <= '$akhir'
                      GROUP BY DATE(p.tanggal), pg.nama
                      ORDER BY DATE(p.tanggal) ASC, pg.nama ASC";

        return $this->db->query($query)->result();
    }
    
}
