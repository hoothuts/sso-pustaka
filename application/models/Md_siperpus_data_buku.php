<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_siperpus_data_buku extends CI_Model {

    var $table1 = 'siperpus_buku';
    var $table = 'siperpus_buku b';
    var $select = 'b.thn_terbit, b.judul, b.kd_penerbit, b.penulis, b.no_klas, b.ISBN, b.no_rak, b.jml_buku, p.nama_penerbit, p.kota as penerbit, 
                   IFNULL(bp.total_prodi, 0) AS jml_prodi, 
                   IFNULL(t_count.total_pinjam, 0) AS jml_pinjam, 
                   IFNULL(i_count.jumlah_buku, 0) AS jumlah_buku, b.buku_id';
    var $column_order = array('no_klas' => 'b.no_klas', 'judul' => 'b.judul', 'penulis' => 'b.penulis', 'jml_buku' => 'b.jml_buku', 'jml_pinjam' => 't_count.total_pinjam');
    var $column_search = array('b.ISBN', 'judul', 'penulis', 'b.no_klas', 'p.nama_penerbit', 'thn_terbit');
    var $order = array('b.buku_id' => 'desc');

    private function getDatatablesQuery() {
        $this->db->select($this->select, FALSE);
        $this->db->from($this->table);
        $this->db->join('siperpus_penerbit p', 'p.kd_penerbit = b.kd_penerbit', 'left');

        // Optimasi Query: Mengganti subquery lambat dengan JOIN agregasi
        $this->db->join('(SELECT no_klas, ISBN, COUNT(idmspst) AS total_prodi FROM siperpus_buku_prodi GROUP BY no_klas, ISBN) bp',
                'bp.no_klas = b.no_klas AND bp.ISBN = b.ISBN', 'left');

        $this->db->join('(SELECT i.no_klas, i.ISBN, COUNT(t.tid) AS total_pinjam 
                          FROM siperpus_inventaris i 
                          JOIN siperpus_transaksi t ON t.no_inv = i.no_inv 
                          GROUP BY i.no_klas, i.ISBN) t_count','t_count.no_klas = b.no_klas AND t_count.ISBN = b.ISBN', 'left');
        
        $this->db->join('(SELECT i.no_klas, i.ISBN, COUNT(i.no_inv) AS jumlah_buku 
                          FROM siperpus_inventaris i where i.status="A"
                          GROUP BY i.no_klas, i.ISBN) i_count','i_count.no_klas = b.no_klas AND i_count.ISBN = b.ISBN', 'left');

        // Logic Searching
        $search = $this->input->post('datatable[query][generalSearch]');
        if ($search) {
            $this->db->group_start();
            foreach ($this->column_search as $i => $item) {
                ($i === 0) ? $this->db->like($item, $search) : $this->db->or_like($item, $search);
            }
            $this->db->group_end();
        }

        // Logic Filtering
        $query = $this->input->post('datatable[query]');
        if ($this->input->post('datatable[query][klas]') !== NULL && $this->input->post('datatable[query][klas]') !== '') {
            $this->db->where('LEFT(b.no_klas,1)', substr($query['klas'], 0, 1));
        }
        if (!empty($query['kel'])) {
            $this->db->where('b.idkategori', $query['kel']);
        }
        if (!empty($query['thn_terbit'])) {
            $this->db->where('b.thn_terbit', $query['thn_terbit']);
        }

        // Logic Ordering
        $sortField = $this->input->post('datatable[sort][field]');
        $sortOrder = $this->input->post('datatable[sort][sort]');
        // Cek langsung: Apakah field yang dikirim user ada di daftar $column_order?
        if (isset($this->column_order[$sortField])) {
            $this->db->order_by($this->column_order[$sortField], $sortOrder);
        }
        // Jika tidak cocok, gunakan order default
        else if (isset($this->order)) {
            $this->db->order_by(key($this->order), current($this->order));
        }
    }

    public function getDatatables() {
        $this->getDatatablesQuery();
        $perpage = $this->input->post('datatable[pagination][perpage]');
        $page = $this->input->post('datatable[pagination][page]');

        if ($perpage != -1) {
            $limit = (int) $perpage;
            $offset = ($limit * ((int) $page - 1));
            $this->db->limit($limit, $offset);
        }

        return $this->db->get()->result();
    }

    public function countFiltered() {
        $this->getDatatablesQuery();
        return $this->db->count_all_results();
    }

    function getDataBukuAll() {
        $hasil = $this->db->query("SELECT *,(SELECT nama_penerbit from siperpus_penerbit where kd_penerbit = bk.kd_penerbit)as penerbit FROM siperpus_buku bk where kd_penerbit = 1565 order by judul asc");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function addDataBuku($data) {
        $this->db->insert('siperpus_buku', $data);
    }

    function hapusDataBuku($ISBN, $no_klas) {
        $this->db->where(array('TRIM(ISBN)' => $ISBN, 'TRIM(no_klas)' => $no_klas));
        // $this->db->where('ISBN',$data);
        $this->db->delete('siperpus_buku');
    }

    function updateDataBuku($ISBN, $no_klas, $data) {
        $this->db->where(array('ISBN' => $ISBN, 'no_klas' => $no_klas));
        $this->db->update('siperpus_buku', $data);
    }

    function updateNoklasBukuInventaris($ISBN_l, $no_klas_l, $ISBN_b, $no_klas_b) {
        $this->db->where(array('ISBN' => $ISBN_l, 'no_klas' => $no_klas_l));
        $this->db->set(array('ISBN' => $ISBN_b, 'no_klas' => $no_klas_b));
        $this->db->update('siperpus_inventaris');
    }

    function updateNoklasBukuFile($ISBN_l, $no_klas_l, $ISBN_b, $no_klas_b) {
        $this->db->where(array('ISBN' => $ISBN_l, 'no_klas' => $no_klas_l));
        $this->db->set(array('ISBN' => $ISBN_b, 'no_klas' => $no_klas_b));
        $this->db->update('siperpus_buku_file');
    }

    function updateJmlBuku($isbn, $no_klas, $row) {
        $this->db->set('jml_buku', $row);
        $this->db->where('ISBN', $isbn);
        $this->db->where('no_klas', $no_klas);
        $this->db->update('siperpus_buku');
    }

    function cekISBN($ISBN) {
        $data = $this->db->from($this->table1)->where(array('ISBN' => $ISBN))->get()->result();
        if ($data) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function getDataBukuByISBN($ISBN, $no_klas = '') {
        $this->db->from($this->table1);
        $this->db->where('ISBN', $ISBN);
        if ($no_klas) {
            $this->db->where('no_klas', $no_klas);
        }
        $data = $this->db->get();
        return $data->result();
    }

    function getDataBukuByNoklas($no_klas) {
        $this->db->from($this->table1);
        $this->db->where('no_klas', $no_klas);
        $data = $this->db->get();
        if ($data->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function getDataBukuByISBNdanNo_klas($ISBN, $no_klas) {
        $this->db->select('b.*,a.nama_penerbit,a.kota')
                ->from('siperpus_buku b')
                ->join('siperpus_penerbit a', 'a.kd_penerbit = b.kd_penerbit', 'left')
                ->where(array('b.ISBN' => $ISBN, 'b.no_klas' => $no_klas));
        $data = $this->db->get();
        return $data->result();
    }

    function getTahunTerbitBuku() {
        $hasil = $this->db->query("SELECT distinct(thn_terbit) as thn_terbit FROM siperpus_buku order by thn_terbit desc");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function updateRakBuku($no_klas, $rak) {
        $this->db->set('no_rak', $rak);
        $this->db->where('no_klas', $no_klas);
        $this->db->update('siperpus_buku');
    }

    function addDataBukuTa($data) {
        $db = $this->db->insert('siperpus_buku_ta', $data);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function updateDataBukuTa($data) {
        $this->db->where('ISBN_ta', $data['ISBN_ta']);
        $this->db->where('no_klas_ta', $data['no_klas_ta']);
        $this->db->update('siperpus_buku_ta', $data);
    }

    function getDataKatalog($no_klas, $isbn) {
        $this->db->select("bk.*,pen.nama_penerbit,pen.kota")
                ->from("siperpus_penerbit pen")
                ->join("siperpus_buku bk", "pen.kd_penerbit = bk.kd_penerbit")
                ->where(array('ISBN' => $isbn, 'no_klas' => $no_klas));
        $data = $this->db->get();
        return $data->result_array();
    }

    function getDataKatalogByBarcodes($barcodes) {
        $this->db->select("bk.*,pen.nama_penerbit,pen.kota")
                ->from("siperpus_inventaris i")
                ->join("siperpus_buku bk", "i.ISBN = bk.ISBN and i.no_klas=bk.no_klas")
                ->join("siperpus_penerbit pen", "pen.kd_penerbit = bk.kd_penerbit")
                ->where_in('i.no_barcode', $barcodes);
        $data = $this->db->get();
        return $data->result_array();
    }

    function getDataCallNumber($no_klas) {
        $this->db->select('no_klas');
        $this->db->from("siperpus_inventaris");

        $this->db->like('no_klas', $no_klas, 'before');
        $data = $this->db->get();
        return $data->num_rows();
    }

    function getBarcode($no_klas, $isbn) {
        $this->db->select("i.no_barcode , i.no_inv, IFNULL(k.kode_warna, '#111111') as kode_warna, r.nama_rak");
        $this->db->from("siperpus_inventaris i");
        $this->db->join('siperpus_klasifikasi k', 'LEFT(k.id, 1) = LEFT(i.no_klas, 1)', 'left'); // JOIN berdasarkan karakter pertama no_klas
        $this->db->join('lokasi_rak r', 'r.lokasirak_id = i.lokasirak_id', 'left'); 
        $this->db->where(array('i.ISBN' => $isbn, 'i.no_klas' => $no_klas));
        $data = $this->db->get();
        return $data->result();
    }

    function addDataFile($file_up) {
        $db = $this->db->insert('siperpus_buku_file', $file_up);
        if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function getDataFile($ISBN, $no_klas) {
        return $this->db->from('siperpus_buku_file')->where(array('ISBN' => $ISBN, 'no_klas' => $no_klas))->get()->result();
    }

    function hapusDataFile($ISBN, $no_klas, $filename) {
        return $this->db->where(array('ISBN' => $ISBN, 'no_klas' => $no_klas, 'file_name' => $filename))->delete('siperpus_buku_file');
    }

    function getDataProdiByISBN_No_klas($ISBN, $no_klas) {
        return $this->db->select("p.nmmspst")
                        ->from("vwprodi p")
                        ->join("siperpus_buku_prodi bp", "p.idmspst = bp.idmspst")
                        ->where(array('ISBN' => $ISBN, 'no_klas' => $no_klas))
                        ->get()->result_array();
    }

    function getDataBarcodeByISBN_No_klas($ISBN, $no_klas) {
        return $this->db->select("no_barcode")
                        ->from("siperpus_inventaris")
                        ->where(array('ISBN' => $ISBN, 'no_klas' => $no_klas))
                        ->get()->result();
    }

    public function getBukuByStatusKlas($status, $no_klas, $tglawal = '', $tglakhir = '') {
        $this->db->select('COUNT(DISTINCT sb.no_klas, sb.ISBN) as total');
        $this->db->from('siperpus_buku sb');

        // Join ke inventaris untuk memastikan data fisik ada
        $this->db->join('siperpus_inventaris si', 'sb.no_klas = si.no_klas AND sb.ISBN = si.ISBN');

        /**
         * OPTIMASI: Langsung Join ke table hilangrusak tanpa nested subquery b bertingkat.
         * Kita gunakan LEFT JOIN untuk mengecek apakah buku tersebut masuk daftar hilang/rusak atau tidak.
         */
        $this->db->join('siperpus_hilangrusak sh', 'si.no_inv = sh.no_inv', 'left');

        // Filter Klasifikasi (mengambil 1 digit pertama seperti contoh Anda)
        $klas_filter = substr($no_klas, 0, 1);
        $this->db->where('LEFT(sb.no_klas, 1) =', $klas_filter);

        // Filter Tanggal Inventarisasi (jika diisi)
        if (!empty($tglawal) && !empty($tglakhir)) {
            $this->db->where('si.tgl_inv >=', $tglawal);
            $this->db->where('si.tgl_inv <=', $tglakhir);
        }

        // Logic Status
        if ($status !== '') {
            // Jika mencari status spesifik (misal: Rusak/Hilang)
            $this->db->where('sh.ket', $status);
        } else {
            // Stok Aktif: Status di inventaris 'A' DAN (tidak ada di tabel hilangrusak ATAU keterangannya 'K')
            $this->db->where('si.status', 'A');
            $this->db->group_start();
                $this->db->where('sh.no_inv', NULL); 
                $this->db->or_where('sh.ket', 'K');
            $this->db->group_end();
        }

        $hasil = $this->db->get()->row();
        return $hasil ? (int)$hasil->total : 0;
    }

    function getBukuByStatusKat($status, $kat, $tglawal = '', $tglakhir = '') {
        $this->db->select('COUNT(DISTINCT sb.no_klas, sb.ISBN) as total');
        $this->db->from('siperpus_buku sb');

        // Inner Join: Memastikan buku memiliki eksemplar di inventaris
        $this->db->join('siperpus_inventaris si', 'sb.no_klas = si.no_klas AND sb.ISBN = si.ISBN');

        /**
         * SUBQUERY: Mengambil status terakhir setiap no_inv
         */
        $sub_status = "(SELECT a.no_inv, a.ket 
                        FROM siperpus_hilangrusak a
                        JOIN (SELECT MAX(kd_hr) as max_id FROM siperpus_hilangrusak GROUP BY no_inv) b 
                        ON a.kd_hr = b.max_id) sh";

        $this->db->join($sub_status, 'si.no_inv = sh.no_inv', 'left');

        // Filter ID Kategori
        $this->db->where('sb.idkategori', $kat);

        // Filter Tanggal Inventarisasi (jika ada range tanggal)
        if (!empty($tglawal) && !empty($tglakhir)) {
            $this->db->where('DATE(si.tgl_inv) >=', $tglawal);
            $this->db->where('DATE(si.tgl_inv) <=', $tglakhir);
        }

        // Logic Status (Sama seperti fungsi klasifikasi sebelumnya)
        if ($status != '') {
            $this->db->where('sh.ket', $status);
        } else {
            $this->db->where('si.status', 'A');// yang aktif saja
            // Stok Aktif
            $this->db->group_start();
                $this->db->where('sh.no_inv', NULL);
                $this->db->or_where('sh.ket', 'K');
            $this->db->group_end();
        }

        $hasil = $this->db->get()->row();

        return $hasil ? (int)$hasil->total : 0;
    }

    function getBukuByStatusAsal($status, $asal, $tglawal = '', $tglakhir = '') {
        $this->db->select('COUNT(DISTINCT sb.no_klas, sb.ISBN) as total');
        $this->db->from('siperpus_buku sb');

        // Inner Join: Pastikan buku memiliki eksemplar di inventaris
        $this->db->join('siperpus_inventaris si', 'sb.no_klas = si.no_klas AND sb.ISBN = si.ISBN');

        /**
         * SUBQUERY: Status fisik buku terbaru
         */
        $sub_status = "(SELECT a.no_inv, a.ket 
                        FROM siperpus_hilangrusak a
                        JOIN (SELECT MAX(kd_hr) as max_id FROM siperpus_hilangrusak GROUP BY no_inv) b 
                        ON a.kd_hr = b.max_id) sh";

        $this->db->join($sub_status, 'si.no_inv = sh.no_inv', 'left');

        // Filter Asal (dari tabel inventaris)
        $this->db->where('si.asal', $asal);

        // Filter Tanggal Inventarisasi
        if (!empty($tglawal) && !empty($tglakhir)) {
            $this->db->where('DATE(si.tgl_inv) >=', $tglawal);
            $this->db->where('DATE(si.tgl_inv) <=', $tglakhir);
        }

        // Logic Status
        if ($status != '') {
            $this->db->where('sh.ket', $status);
        } else {
            $this->db->where('si.status', 'A');// yang aktif saja
            // Stok Aktif
            $this->db->group_start();
                $this->db->where('sh.no_inv', NULL);
                $this->db->or_where('sh.ket', 'K');
            $this->db->group_end();
        }

        $hasil = $this->db->get()->row();

        return $hasil ? (int)$hasil->total : 0;
    }

    function getBukuByStatusBahasa($status, $bahasa, $tglawal = '', $tglakhir = '') {
        $this->db->select('COUNT(DISTINCT sb.no_klas, sb.ISBN) as total');
        $this->db->from('siperpus_buku sb');

        // Inner Join: Memastikan buku memiliki eksemplar di inventaris
        $this->db->join('siperpus_inventaris si', 'sb.no_klas = si.no_klas AND sb.ISBN = si.ISBN');

        /**
         * SUBQUERY: Mengambil status terakhir setiap no_inv
         */
        $sub_status = "(SELECT a.no_inv, a.ket 
                        FROM siperpus_hilangrusak a
                        JOIN (SELECT MAX(kd_hr) as max_id FROM siperpus_hilangrusak GROUP BY no_inv) b 
                        ON a.kd_hr = b.max_id) sh";

        $this->db->join($sub_status, 'si.no_inv = sh.no_inv', 'left');

        // Filter Bahasa
        $this->db->where('sb.bahasa', $bahasa);

        // Filter Tanggal Inventarisasi (jika ada range tanggal)
        if (!empty($tglawal) && !empty($tglakhir)) {
            $this->db->where('DATE(si.tgl_inv) >=', $tglawal);
            $this->db->where('DATE(si.tgl_inv) <=', $tglakhir);
        }

        // Logic Status (Sama seperti fungsi klasifikasi sebelumnya)
        if ($status != '') {
            $this->db->where('sh.ket', $status);
        } else {
            $this->db->where('si.status', 'A');// yang aktif saja
            // Stok Aktif
            $this->db->group_start();
                $this->db->where('sh.no_inv', NULL);
                $this->db->or_where('sh.ket', 'K');
            $this->db->group_end();
        }

        $hasil = $this->db->get()->row();

        return $hasil ? (int)$hasil->total : 0;
    }

    function getTahunAll() {
        return $this->db->distinct()->select('thn_terbit')->from('siperpus_buku')->order_by('thn_terbit')->get()->result();
    }

    function getBukuByThnJudul_Total($status, $thn, $tglawal = '', $tglakhir = '') {
        $this->db->select('COUNT(DISTINCT sb.no_klas, sb.ISBN) as total');
        $this->db->from('siperpus_buku sb');

        // MENGGUNAKAN JOIN (Default adalah INNER JOIN)
        // Ini memastikan buku tanpa eksemplar di siperpus_inventaris TIDAK AKAN dihitung
        $this->db->join('siperpus_inventaris si', 'sb.no_klas = si.no_klas AND sb.ISBN = si.ISBN');

        $sub_status = "(SELECT a.no_inv, a.ket 
                        FROM siperpus_hilangrusak a
                        JOIN (SELECT MAX(kd_hr) as max_id FROM siperpus_hilangrusak GROUP BY no_inv) b 
                        ON a.kd_hr = b.max_id) sh";

        $this->db->join($sub_status, 'si.no_inv = sh.no_inv', 'left');

        $this->db->where('sb.thn_terbit', $thn);

        if (!empty($tglawal) && !empty($tglakhir)) {
            $this->db->where('DATE(si.tgl_inv) >=', $tglawal);
            $this->db->where('DATE(si.tgl_inv) <=', $tglakhir);
        }

        if ($status != '') {
            $this->db->where('sh.ket', $status);
        } else {
            $this->db->where('si.status', 'A');// yang aktif saja
            $this->db->group_start();
                $this->db->where('sh.no_inv', NULL);
                $this->db->or_where('sh.ket', 'K');
            $this->db->group_end();
        }

        $hasil = $this->db->get()->row();

        return $hasil ? (int)$hasil->total : 0;
    }

    function getBukuByThnJudul_Klas($status, $no_klas, $thn, $tglawal = '', $tglakhir = '') {
        $this->db->select('COUNT(DISTINCT sb.no_klas, sb.ISBN) as total');
        $this->db->from('siperpus_buku sb');

        // Inner Join: Pastikan buku memiliki eksemplar di inventaris
        $this->db->join('siperpus_inventaris si', 'sb.no_klas = si.no_klas AND sb.ISBN = si.ISBN');

        /** * SUBQUERY: Status fisik buku terbaru
         */
        $sub_status = "(SELECT a.no_inv, a.ket 
                        FROM siperpus_hilangrusak a
                        JOIN (SELECT MAX(kd_hr) as max_id FROM siperpus_hilangrusak GROUP BY no_inv) b 
                        ON a.kd_hr = b.max_id) sh";

        $this->db->join($sub_status, 'si.no_inv = sh.no_inv', 'left');

        // Filter Tahun dan Klasifikasi (LIKE)
        $this->db->where('sb.thn_terbit', $thn);
        $klas_filter = substr($no_klas, 0, 1);
        $this->db->where('LEFT(sb.no_klas,1)', $klas_filter);
        
        // Filter Tanggal Inventarisasi
        if (!empty($tglawal) && !empty($tglakhir)) {
            $this->db->where('DATE(si.tgl_inv) >=', $tglawal);
            $this->db->where('DATE(si.tgl_inv) <=', $tglakhir);
        }

        // Logic Status
        if ($status != '') {
            $this->db->where('sh.ket', $status);
        } else {
            $this->db->where('si.status', 'A');// yang aktif saja
            // Stok Aktif
            $this->db->group_start();
                $this->db->where('sh.no_inv', NULL);
                $this->db->or_where('sh.ket', 'K');
            $this->db->group_end();
        }

        $hasil = $this->db->get()->row();

        return $hasil ? (int)$hasil->total : 0;
    }

    function getBukuByThnJudul_Kat($status, $idkategori, $thn, $tglawal = '', $tglakhir = '') {
        $this->db->select('COUNT(DISTINCT sb.no_klas, sb.ISBN) as total');
        $this->db->from('siperpus_buku sb');

        // Inner Join: Memastikan buku memiliki eksemplar di inventaris
        $this->db->join('siperpus_inventaris si', 'sb.no_klas = si.no_klas AND sb.ISBN = si.ISBN');

        /**
         * SUBQUERY: Mengambil status terakhir setiap no_inv
         */
        $sub_status = "(SELECT a.no_inv, a.ket 
                        FROM siperpus_hilangrusak a
                        JOIN (SELECT MAX(kd_hr) as max_id FROM siperpus_hilangrusak GROUP BY no_inv) b 
                        ON a.kd_hr = b.max_id) sh";

        $this->db->join($sub_status, 'si.no_inv = sh.no_inv', 'left');

        // Filter Tahun dan ID Kategori
        $this->db->where('sb.thn_terbit', $thn);
        $this->db->where('sb.idkategori', $idkategori);

        // Filter Tanggal Inventarisasi (jika ada range tanggal)
        if (!empty($tglawal) && !empty($tglakhir)) {
            $this->db->where('DATE(si.tgl_inv) >=', $tglawal);
            $this->db->where('DATE(si.tgl_inv) <=', $tglakhir);
        }

        // Logic Status (Sama seperti fungsi klasifikasi sebelumnya)
        if ($status != '') {
            $this->db->where('sh.ket', $status);
        } else {
            $this->db->where('si.status', 'A');// yang aktif saja
            // Stok Aktif
            $this->db->group_start();
                $this->db->where('sh.no_inv', NULL);
                $this->db->or_where('sh.ket', 'K');
            $this->db->group_end();
        }

        $hasil = $this->db->get()->row();

        return $hasil ? (int)$hasil->total : 0;
    }

    function getBukuByThnJudul_Asal($status, $asal, $thn, $tglawal = '', $tglakhir = '') {
        $this->db->select('COUNT(DISTINCT sb.no_klas, sb.ISBN) as total');
        $this->db->from('siperpus_buku sb');

        // Inner Join: Pastikan buku memiliki eksemplar di inventaris
        $this->db->join('siperpus_inventaris si', 'sb.no_klas = si.no_klas AND sb.ISBN = si.ISBN');

        /**
         * SUBQUERY: Status fisik buku terbaru
         */
        $sub_status = "(SELECT a.no_inv, a.ket 
                        FROM siperpus_hilangrusak a
                        JOIN (SELECT MAX(kd_hr) as max_id FROM siperpus_hilangrusak GROUP BY no_inv) b 
                        ON a.kd_hr = b.max_id) sh";

        $this->db->join($sub_status, 'si.no_inv = sh.no_inv', 'left');

        // Filter Tahun (dari tabel buku) dan Asal (dari tabel inventaris)
        $this->db->where('sb.thn_terbit', $thn);
        $this->db->where('si.asal', $asal);

        // Filter Tanggal Inventarisasi
        if (!empty($tglawal) && !empty($tglakhir)) {
            $this->db->where('DATE(si.tgl_inv) >=', $tglawal);
            $this->db->where('DATE(si.tgl_inv) <=', $tglakhir);
        }

        // Logic Status
        if ($status != '') {
            $this->db->where('sh.ket', $status);
        } else {
            $this->db->where('si.status', 'A');// yang aktif saja
            // Stok Aktif
            $this->db->group_start();
                $this->db->where('sh.no_inv', NULL);
                $this->db->or_where('sh.ket', 'K');
            $this->db->group_end();
        }

        $hasil = $this->db->get()->row();

        return $hasil ? (int)$hasil->total : 0;
    }

    function getBukuByThnJudul_Penerbit($status, $tahun, $tglawal = '', $tglakhir = '') {
        // Kita ambil Nama Penerbit dan Total Judul-nya
        $this->db->select('sp.nama_penerbit, COUNT(DISTINCT sb.no_klas, sb.ISBN) as total');
        $this->db->from('siperpus_buku sb');

        // Join ke Penerbit
        $this->db->join('siperpus_penerbit sp', 'sb.kd_penerbit = sp.kd_penerbit');

        // Inner Join ke Inventaris (Pastikan ada eksemplar)
        $this->db->join('siperpus_inventaris si', 'sb.no_klas = si.no_klas AND sb.ISBN = si.ISBN');

        // Subquery Status Terakhir (Hilang/Rusak/Kembali)
        $sub_status = "(SELECT a.no_inv, a.ket 
                        FROM siperpus_hilangrusak a
                        JOIN (SELECT MAX(kd_hr) as max_id FROM siperpus_hilangrusak GROUP BY no_inv) b 
                        ON a.kd_hr = b.max_id) sh";

        $this->db->join($sub_status, 'si.no_inv = sh.no_inv', 'left');
        
        $this->db->where('sb.thn_terbit', $tahun);
         
        // Filter Tanggal Inventarisasi
        if (!empty($tglawal) && !empty($tglakhir)) {
            $this->db->where('DATE(si.tgl_inv) >=', $tglawal);
            $this->db->where('DATE(si.tgl_inv) <=', $tglakhir);
        }

        // Filter Status
        if ($status != '') {
            $this->db->where('sh.ket', $status);
        } else {
            // Sesuai logic Anda: Status Inventaris Aktif ('A') dan tidak sedang bermasalah
            $this->db->where('si.status', 'A');
            $this->db->group_start();
                $this->db->where('sh.no_inv', NULL);
                $this->db->or_where('sh.ket', 'K');
            $this->db->group_end();
        }

        // WAJIB: Grouping berdasarkan penerbit agar muncul list per kategori
        $this->db->group_by('sp.kd_penerbit');
        $this->db->order_by('total', 'DESC'); // Urutkan dari yang terbanyak

        $hasil = $this->db->get()->result_array();

        return $hasil; 
    }
    
    function getDataBukuByIsbnNoklas_for_edit($isbn, $no_klas, $buku_id) {
        $this->db->select('b.*,a.nama_penerbit,a.kota')
                ->from('siperpus_buku b')
                ->join('siperpus_penerbit a', 'a.kd_penerbit = b.kd_penerbit', 'left')
                ->where('b.ISBN', $isbn)
                ->where('b.no_klas', $no_klas)
                ->where('b.buku_id !=', $buku_id);
        $data = $this->db->get();
        return $data->result();
    }

    function cekIsbnForEdit($ISBN, $buku_id) {
        $this->db->select('b.*,a.nama_penerbit,a.kota')
                ->from('siperpus_buku b')
                ->join('siperpus_penerbit a', 'a.kd_penerbit = b.kd_penerbit', 'left')
                ->where('b.ISBN', $ISBN)
                ->where('b.buku_id !=', $buku_id);
        $data = $this->db->get();
        return $data->result();
    }
    
    function getStatistikBukuProdi($awal = '', $akhir = '', $pilih_klas = '') {
        $this->db->select("v.nmmspst, 
                           COUNT(DISTINCT CONCAT(IFNULL(b.ISBN,''), IFNULL(b.no_klas,''))) as jml_judul,
                           COUNT(i.no_inv) as jml_eksemplar"); 
        $this->db->from('siperpus_buku_prodi bp');
        $this->db->join('vwprodi v', 'bp.idmspst = v.idmspst');
        $this->db->join('siperpus_buku b', 'bp.ISBN = b.ISBN AND bp.no_klas = b.no_klas');
        $this->db->join('siperpus_inventaris i', 'b.ISBN = i.ISBN AND b.no_klas = i.no_klas');

        // Filter Tanggal
        if ($awal != '' && $akhir != '') {
            $this->db->where('i.tgl_inv >=', $awal);
            $this->db->where('i.tgl_inv <=', $akhir);
        }

        // FILTER BARU: Klasifikasi
        if ($pilih_klas != '') {
            $klas_filter = substr($pilih_klas, 0, 1);
            $this->db->where('LEFT(bp.no_klas,1)', $klas_filter);
        }

        $this->db->group_by('v.nmmspst');
        $this->db->order_by('jml_judul', 'DESC'); // Urutkan dari koleksi terbanyak

        return $this->db->get()->result();
    }
    
    function getTotalKoleksiRiil($awal, $akhir, $pilih_klas) {
        $this->db->select("COUNT(DISTINCT CONCAT(b.ISBN, b.no_klas)) as total_judul, 
                           COUNT(DISTINCT i.no_inv) as total_eksemplar");
        $this->db->from('siperpus_buku_prodi bp');
        $this->db->join('siperpus_buku b', 'bp.ISBN = b.ISBN AND bp.no_klas = b.no_klas');
        $this->db->join('siperpus_inventaris i', 'b.ISBN = i.ISBN AND b.no_klas = i.no_klas');

        if ($awal != '' && $akhir != '') {
            $this->db->where('i.tgl_inv >=', $awal);
            $this->db->where('i.tgl_inv <=', $akhir);
        }
        if ($pilih_klas != '') {
            $klas_filter = substr($pilih_klas, 0, 1);
            $this->db->where('LEFT(bp.no_klas,1)', $klas_filter);
        }

        return $this->db->get()->row_array();
    }
    
     function getDataBukuById($buku_id) {
        $this->db->select('b.*,a.nama_penerbit,a.kota')
                ->from('siperpus_buku b')
                ->join('siperpus_penerbit a', 'a.kd_penerbit = b.kd_penerbit', 'left')
                ->where('b.buku_id', $buku_id);
        $data = $this->db->get();
        return $data->row();
    }

}
