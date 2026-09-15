<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_siperpus_inventaris extends CI_Model {

    var $select = 'i.*, b.judul';
    var $table1 = 'siperpus_inventaris';
    var $table = 'siperpus_inventaris i, siperpus_buku b';
    var $where = 'i.ISBN = b.ISBN AND i.no_klas = b.no_klas';
    var $column_order = array(array('no_inv', 'no_inv'), array('tgl_inv', 'tgl_inv'), array('asal', 'asal')); //set column field database for datatable orderable
    var $column_search = array('no_inv', 'no_barcode', 'judul'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('tanggal' => 'desc'); // default order

    public function countAll() {
        $this->db->from($this->table1)->where(array('no_klas' => $this->input->post('datatable[query][no_klas]'), 'isbn' => $this->input->post('datatable[query][isbn]')));
        return $this->db->count_all_results();
    }

    function countFiltered() {
        $this->getDatatablesQuery();
        $query = $this->db->count_all_results();
        return $query;
    }

    private function getDatatablesQuery() {
        $sql = "(SELECT i.*, ( SELECT judul FROM siperpus_buku b WHERE b.no_klas = i.no_klas AND b.ISBN = i.ISBN limit 1) AS judul FROM siperpus_inventaris i) as inventaris";
        $this->db->from($sql);
        $i = 0;
        //checking if there's searching :
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
        if ($this->input->post('datatable[query][klas]')) {
            $this->db->like('no_klas', $this->input->post('datatable[query][klas]'), 'after');
        }
        //filtering order :

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
        $query = $this->db->get();
        return $query->result();
    }

    public function getValue($b, $text) {
        foreach ($b as $v) {
            if (in_array($text, $v, true)) {
                return $v[1];
            }
        }
        return false;
    }

    function getNumRowInvByNoKlasISBN($no_klas, $isbn) {
        $this->db->where(array('ISBN' => $isbn, 'no_klas' => $no_klas, 'status' => 'A'));
        return $this->db->get('siperpus_inventaris')->num_rows();
    }

    function getNoBarcode() {
        $this->db->select_max('no_barcode');
        return $this->db->get('siperpus_inventaris')->row()->no_barcode;
    }

    function getInventarisByBarcode($barcode) {
        $hasil = $this->db->get_where('siperpus_inventaris', array('no_barcode' => $barcode))->result();
        $data = $hasil;
        return $data;
    }
    
    function getInventarisAktifByBarcode($barcode) {
        $hasil = $this->db->get_where('siperpus_inventaris', array('no_barcode' => $barcode,'status' => 'A'))->row();
        return $hasil;
    }

    function getInventarisAll() {
        $hasil = $this->db->query("SELECT *,(SELECT judul FROM `siperpus_buku` WHERE ISBN = inv.ISBN limit 1) as judul FROM siperpus_inventaris inv WHERE ISBN ='978.979.044.092.0' order by no_inv asc");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function addInventaris($data) {
        $this->db->insert('siperpus_inventaris', $data);
    }

    function hapusInventaris($data) {
        $this->db->where('no_barcode', $data); //jangan hapus dengan menggunakan isbn, gunakan no_barcode
        $this->db->delete('siperpus_inventaris');
    }

    function updateInventaris($data) {
        $this->db->where('no_barcode', $data['no_barcode']);
        $this->db->update('siperpus_inventaris', $data);
    }

    function getInventarisById($id) {
        $query = "SELECT *,(SELECT judul FROM `siperpus_buku` WHERE ISBN = inv.ISBN AND no_klas = inv.no_klas limit 1) as judul FROM siperpus_inventaris inv WHERE no_barcode = '" . $id . "'";
        $hasil = $this->db->query($query)->result();
        //$hasil = $this->db->get_where('siperpus_inventaris', array('ISBN' => $id))->result();
        $data = $hasil;
        return $data;
    }
    
    function getInventarisAktifById($id) {
        $this->db->select('inv.*, b.judul');
        $this->db->from('siperpus_inventaris inv');
        $this->db->join('siperpus_buku b', 'b.ISBN = inv.ISBN AND b.no_klas = inv.no_klas', 'left');
        $this->db->where('inv.no_barcode', $id);
        $this->db->where('inv.status', 'A');

        return $this->db->get()->row();
    }

    function getInventarisByNoInv($id) {
        $query = "SELECT *,(SELECT judul FROM `siperpus_buku` WHERE ISBN = inv.ISBN AND no_klas = inv.no_klas limit 1) as judul FROM siperpus_inventaris inv WHERE no_inv = '" . $id . "'";
        $hasil = $this->db->query($query)->result();
        //$hasil = $this->db->get_where('siperpus_inventaris', array('ISBN' => $id))->result();
        $data = $hasil;
        return $data;
    }

    function getInventarisByISBNdanNoKlas($isbn, $no_klas) {
        $this->db->select('si.*, sb.judul, k.nama_kampus, g.nama_gedung, r.nama_rak');
        $this->db->from('siperpus_inventaris si');
        $this->db->join('siperpus_buku sb', 'si.no_klas = sb.no_klas AND si.ISBN = sb.ISBN');
        $this->db->join('lokasi_rak r', 'r.lokasirak_id = si.lokasirak_id', 'left');
        $this->db->join('lokasi_gedung g', 'g.lokasigedung_id = r.lokasigedung_id', 'left');
        $this->db->join('lokasi_kampus k', 'k.lokasikampus_id = g.lokasikampus_id', 'left');
        $this->db->where('si.no_klas', $no_klas);
        $this->db->where('si.ISBN', $isbn);
        $this->db->where('si.status', 'A');// yang aktif saja
        $this->db->order_by('si.tanggal', 'desc');
        return $this->db->get()->result();
    }

    function countByISBN($isbn) {
        $where = "ISBN = '" . $isbn . "'";
        $this->db->from('siperpus_inventaris'); //from vwanggota
        $this->db->where($where);
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    function countByISBNandNoKlas($isbn,$no_klas) {
        $this->db->from('siperpus_inventaris'); //from vwanggota
        $this->db->where('ISBN',$isbn);
        $this->db->where('no_klas',$no_klas);
        $this->db->where('status','A');        
        $query = $this->db->get();
        return $query->num_rows();
    }

    function hapusInventarisAll($isbn) {
        $this->db->where('ISBN', $isbn);
        $this->db->delete('siperpus_inventaris');
    }

    function getBarcode($no_barcode) {
        $this->db->select('no_inv, no_barcode');
        $this->db->from("siperpus_inventaris");
        $this->db->where(array('no_barcode' => $no_barcode));
        $data = $this->db->get();
        return $data->result();
    }

    function getDataCallNumber($no_barcode) {
        // echo $no_barcode."a";die;
        $this->db->select('no_klas, no_inv, no_barcode');
        $this->db->from("siperpus_inventaris");
        $this->db->where("no_barcode", $no_barcode);
        // $isbn = $this->db->get()->result_array();
        return $this->db->get()->result_array();
    }

    function getJumlahEks() {
        $hasil = $this->db->query("SELECT count(*) as jumlah FROM siperpus_inventaris  ");
        $data = $hasil->row();
        return $data->jumlah;
    }

    function getInventarisByTahunKlas($tahun, $bulan, $klas) {
        $q = '';
        if ($bulan != '')
            $q = "and month(tgl_inv)='$bulan'";
        $query = "SELECT count(*) as total FROM siperpus_inventaris WHERE year(tgl_inv)='$tahun' $q and no_klas like '$klas%'";
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

    function getInventarisByStatusKlas($status, $klas, $tglawal='',$tglakhir='') {
        $this->db->select('COUNT(si.no_inv) as total');
        $this->db->from('siperpus_inventaris si');

        /*
            ** SUBQUERY: Mengambil baris terakhir (kd_hr tertinggi) untuk setiap no_inv
            ** Ini menjamin kita mendapatkan status 'ket' yang paling update
        */
       $subquery = "(SELECT a.no_inv, a.ket 
                    FROM siperpus_hilangrusak a
                    JOIN (SELECT MAX(kd_hr) as max_id FROM siperpus_hilangrusak GROUP BY no_inv) b ON a.kd_hr = b.max_id) sh";

       $this->db->join($subquery, 'si.no_inv = sh.no_inv', 'left');

        // Filter berdasarkan klasifikasi 
        $klas_filter = substr($klas, 0, 1);
        $this->db->where('LEFT(si.no_klas,1)', $klas_filter);
        
        if(!empty($tglawal) && !empty($tglakhir)){
            $this->db->where('date(si.tgl_inv) >=', $tglawal); 
            $this->db->where('date(si.tgl_inv) <=', $tglakhir); 
        }

        if ($status != '') {
            // Jika mencari status spesifik (H/R/D dll)
            $this->db->where('sh.ket', $status);
        } else {
            $this->db->where('si.status', 'A');// yang aktif saja
            // Logika aslinya: mencari yang tidak ada di daftar rusak KECUALI yang berkode 'K'
            $this->db->group_start();
                $this->db->where('sh.no_inv', NULL); // Bersih (tidak ada di tabel hilangrusak)
                $this->db->or_where('sh.ket', 'K');  // Atau ada catatan tapi sudah kembali/K
            $this->db->group_end();
        }

        $hasil = $this->db->get()->row();

        return $hasil ? (int)$hasil->total : 0;
    }

    function getInventarisByStatusKat($status, $kat, $tglawal = '', $tglakhir = '') {
        $this->db->select('COUNT(si.no_inv) as total');
        $this->db->from('siperpus_inventaris si');
        $this->db->join('siperpus_buku sb', 'si.no_klas = sb.no_klas AND si.ISBN = sb.ISBN');

        /*
        ** SUBQUERY: memastikan hanya status terbaru yang dicek
        */
        $subquery = "(SELECT a.no_inv, a.ket 
                    FROM siperpus_hilangrusak a
                    JOIN (SELECT MAX(kd_hr) as max_id FROM siperpus_hilangrusak GROUP BY no_inv) b ON a.kd_hr = b.max_id) sh";

        $this->db->join($subquery, 'si.no_inv = sh.no_inv', 'left');

        $this->db->where('sb.idkategori', $kat);

        if ($status != '') {
            $this->db->where('sh.ket', $status);
        } else {
            $this->db->where('si.status', 'A');// yang aktif saja
            $this->db->group_start();
            $this->db->where('sh.no_inv', NULL);
            $this->db->or_where('sh.ket', 'K');
            $this->db->group_end();
        }

        // Filter Tanggal Input (Opsional)
        if(!empty($tglawal) && !empty($tglakhir)){
            $this->db->where('date(si.tgl_inv) >=', $tglawal); 
            $this->db->where('date(si.tgl_inv) <=', $tglakhir); 
        }

        $hasil = $this->db->get()->row();
        return $hasil ? (int) $hasil->total : 0;
    }

    function getInventarisByStatusAsal($status, $asal, $tglawal = '', $tglakhir = '') {
        $this->db->select('COUNT(si.no_inv) as total');
        $this->db->from('siperpus_inventaris si');

        /** * SUBQUERY: Mendapatkan status terakhir (ket) berdasarkan kd_hr tertinggi
         * agar satu buku hanya terhitung satu kali meskipun memiliki banyak riwayat.
         */
        $subquery = "(SELECT a.no_inv, a.ket 
                      FROM siperpus_hilangrusak a
                      JOIN (SELECT MAX(kd_hr) as max_id FROM siperpus_hilangrusak GROUP BY no_inv) b ON a.kd_hr = b.max_id) sh";

        $this->db->join($subquery, 'si.no_inv = sh.no_inv', 'left');

        // Filter berdasarkan Asal Buku
        $this->db->where('si.asal', $asal);

        // Filter Tanggal Input (Instruksi Khusus)
        if (!empty($tglawal) && !empty($tglakhir)) {
            $this->db->where('DATE(si.tgl_inv) >=', $tglawal); 
            $this->db->where('DATE(si.tgl_inv) <=', $tglakhir); 
        }

        // Logic Status Hilang/Rusak
        if ($status != '') {
            // Jika mencari status spesifik
            $this->db->where('sh.ket', $status);
        } else {
            $this->db->where('si.status', 'A');// yang aktif saja
            // Jika status kosong (Buku tersedia: tidak ada riwayat ATAU status terakhirnya 'K')
            $this->db->group_start();
                $this->db->where('sh.no_inv', NULL);
                $this->db->or_where('sh.ket', 'K');
            $this->db->group_end();
        }

        $hasil = $this->db->get()->row();

        return $hasil ? (int)$hasil->total : 0;
    }

    function getInventarisByStatusBahasa($status, $bahasa, $tglawal = '', $tglakhir = '') {
        $this->db->select('COUNT(si.no_inv) as total');
        $this->db->from('siperpus_inventaris si');

        // 1. Join ke tabel buku untuk filter bahasa
        $this->db->join('siperpus_buku sb', 'si.no_klas = sb.no_klas AND si.ISBN = sb.ISBN');

        /** * 2. SUBQUERY: Mendapatkan status terbaru (ket) berdasarkan kd_hr tertinggi.
         * Teknik ini memastikan satu buku hanya terhitung satu kali dengan status terupdate.
         */
        $subquery = "(SELECT a.no_inv, a.ket 
                      FROM siperpus_hilangrusak a
                      JOIN (SELECT MAX(kd_hr) as max_id FROM siperpus_hilangrusak GROUP BY no_inv) b 
                      ON a.kd_hr = b.max_id) sh";

        $this->db->join($subquery, 'si.no_inv = sh.no_inv', 'left');

        // 3. Filter berdasarkan Bahasa
        $this->db->where('sb.bahasa', $bahasa);

        // 4. Filter Tanggal Input Inventaris
        if (!empty($tglawal) && !empty($tglakhir)) {
            $this->db->where('DATE(si.tgl_inv) >=', $tglawal); 
            $this->db->where('DATE(si.tgl_inv) <=', $tglakhir); 
        }

        // 5. Logic Status Hilang/Rusak/Tersedia
        if ($status != '') {
            // Jika mencari status spesifik (misal: 'H' untuk Hilang)
            $this->db->where('sh.ket', $status);
        } else {
            $this->db->where('si.status', 'A');// yang aktif saja
            // Jika status kosong (Buku tersedia: tidak ada riwayat ATAU status terakhirnya 'K')
            $this->db->group_start();
                $this->db->where('sh.no_inv', NULL);
                $this->db->or_where('sh.ket', 'K');
            $this->db->group_end();
        }

        $hasil = $this->db->get()->row();

        return $hasil ? (int)$hasil->total : 0;
    }

    function getJmlBukuByThnJudul_Total($status, $thn) {
        $q = "and no_inv not in(select no_inv from siperpus_hilangrusak where ket <> 'K')";
        if ($status != '')
            $q = "and no_inv in(select no_inv from siperpus_hilangrusak where ket='$status')";
        $query = "SELECT count(*) as total FROM siperpus_inventaris WHERE no_klas IN (SELECT no_klas FROM siperpus_buku WHERE thn_terbit = '$thn') $q";
        $hasil = $this->db->query($query)->result_array();
        return $hasil[0]['total'];
    }

    function getJmlBukuByThnJudul_Klas($status, $no_klas, $thn) {
        $q = "and no_inv not in(select no_inv from siperpus_hilangrusak where ket <> 'K')";
        if ($status != '')
            $q = "and no_inv in(select no_inv from siperpus_hilangrusak where ket='$status')";
        $query = "SELECT count(*) as total FROM siperpus_inventaris WHERE no_klas IN (SELECT no_klas FROM siperpus_buku WHERE thn_terbit = '$thn') AND no_klas like '$no_klas%' $q";
        $hasil = $this->db->query($query)->result_array();
        return $hasil[0]['total'];
    }

    function getJmlBukuByThnJudul_Kat($status, $idkategori, $thn) {
        $q = "and no_inv not in(select no_inv from siperpus_hilangrusak where ket <> 'K')";
        if ($status != '')
            $q = "and no_inv in(select no_inv from siperpus_hilangrusak where ket='$status')";
        $query = "SELECT count(*) as total FROM siperpus_inventaris WHERE no_klas IN (SELECT no_klas FROM siperpus_buku WHERE thn_terbit = '$thn' AND idkategori = '$idkategori') $q";
        $hasil = $this->db->query($query)->result_array();
        return $hasil[0]['total'];
    }

    function getJmlBukuByThnJudul_Asal($status, $asal, $thn) {
        $q = "and no_inv not in(select no_inv from siperpus_hilangrusak where ket <> 'K')";
        if ($status != '')
            $q = "and no_inv in(select no_inv from siperpus_hilangrusak where ket='$status')";
        $query = "SELECT count(*) as total FROM siperpus_inventaris WHERE no_klas IN (SELECT no_klas FROM siperpus_buku WHERE thn_terbit = '$thn') AND asal = '$asal' $q";
        $hasil = $this->db->query($query)->result_array();
        return $hasil[0]['total'];
    }

    public function update_inventaris_status($no_inv, $status) {
        $this->db->where('no_inv', $no_inv);
        return $this->db->update('siperpus_inventaris', ['status' => $status]);
    }
    
    /**
    * Ambil data inventaris berdasarkan no_inv
    * 
    * @param string $no_inv
    * @return object|null
    */
    public function get_inventaris_by_no_inv($no_inv) {
        $this->db->where('no_inv', $no_inv);
        return $this->db->get('siperpus_inventaris')->row();
    }
    
    /**
    * Ambil data inventaris berdasarkan no_barcode (unique)
    */
    public function get_by_barcode($no_barcode) {
       return $this->db->get_where('siperpus_inventaris', ['no_barcode' => $no_barcode])->row();
    }

    /**
    * Cek apakah no_inv sudah ada di tabel transaksi peminjaman atau penyiangan_detail
    * Return TRUE jika ada data terkait (tidak boleh dihapus)
    */
    public function has_related_records($no_inv) {
       // Cek di siperpus_transaksi
       $transaksi = $this->db->where('no_inv', $no_inv)
                             ->get('siperpus_transaksi')
                             ->num_rows();

       if ($transaksi > 0) return TRUE;

       // Cek di penyiangan_detail
       $penyiangan = $this->db->where('no_inv', $no_inv)
                              ->get('penyiangan_detail')
                              ->num_rows();

       return ($penyiangan > 0);
    }
    
    /**
    * Cek apakah kombinasi no_inv + no_barcode sudah ada
    * Return TRUE jika sudah ada (duplikat)
    */
    public function is_duplicate_inv($no_inv, $no_barcode) {
       $this->db->where('no_inv', $no_inv);
       $this->db->where('no_barcode', $no_barcode);
       return $this->db->count_all_results('siperpus_inventaris') > 0;
    }

    /**
    * Cek apakah no_barcode sudah ada (unik sendiri)
    * Return TRUE jika sudah ada
    */
    public function is_barcode_exists($no_barcode) {
       $this->db->where('no_barcode', $no_barcode);
       return $this->db->count_all_results('siperpus_inventaris') > 0;
    }
    
    /**
    * Cek apakah kombinasi no_inv + no_barcode sudah ada di inventaris lain
    * (kecualikan no_inv yang sedang di-edit)
    * Return TRUE jika ada duplikat
    */
   public function is_duplicate_inv_edit($current_no_inv,$no_inv, $no_barcode) {
       $this->db->where('no_inv !=', $current_no_inv); // kecualikan dirinya sendiri
       $this->db->where('no_inv', $no_inv);
       $this->db->where('no_barcode', $no_barcode);
       return $this->db->count_all_results('siperpus_inventaris') > 0;
   }

   /**
    * Cek apakah no_barcode sudah ada di inventaris lain
    * (kecualikan no_inv yang sedang di-edit)
    * Return TRUE jika sudah ada
    */
   public function is_barcode_exists_edit($current_no_inv, $no_barcode) {
       $this->db->where('no_inv !=', $current_no_inv);
       $this->db->where('no_barcode', $no_barcode);
       return $this->db->count_all_results('siperpus_inventaris') > 0;
   }

   
   // Fungsi untuk fetch data inventaris join buku untuk datatable ubah rak
    public function get_inventaris_for_ubah_rak($search = '', $limit = 10, $offset = 0, $sort_field = 'i.no_inv', $sort_order = 'ASC') {
        $this->db->select('i.no_inv, i.no_barcode, i.ISBN, i.no_klas, i.lokasirak_id AS current_rak_id, b.judul, b.penulis, b.no_rak, k.nama_kampus, r.nama_rak');
        $this->db->from('siperpus_inventaris i');
        $this->db->join('siperpus_buku b', 'b.no_klas = i.no_klas AND b.ISBN = i.ISBN', 'left');
        $this->db->join('lokasi_rak r', 'r.lokasirak_id = i.lokasirak_id', 'left');
        $this->db->join('lokasi_gedung g', 'g.lokasigedung_id = r.lokasigedung_id', 'left');
        $this->db->join('lokasi_kampus k', 'k.lokasikampus_id = g.lokasikampus_id', 'left');
        $this->db->where('i.status', 'A'); // Asumsi hanya aktif

        if ($search) {
            $this->db->group_start();
            $this->db->like('i.no_inv', $search);
            $this->db->or_like('b.judul', $search);
            $this->db->group_end();
        }
        if ($this->input->post('datatable[query][klas]') !== NULL && $this->input->post('datatable[query][klas]')!=='') {
            $klas_filter = substr($this->input->post('datatable[query][klas]'), 0, 1);
            $this->db->where('LEFT(b.no_klas,1)', $klas_filter);
        }
        if ($this->input->post('datatable[query][kel]')) {
            $this->db->where('b.idkategori', $this->input->post('datatable[query][kel]'));
        }
        if ($this->input->post('datatable[query][kampus]')) {
            $this->db->where('k.lokasikampus_id', $this->input->post('datatable[query][kampus]'), 'after');
        }
        if ($this->input->post('datatable[query][gedung]')) {
            $this->db->where('g.lokasigedung_id', $this->input->post('datatable[query][gedung]'), 'after');
        }
        if ($this->input->post('datatable[query][rak]')) {
            $this->db->where('r.lokasirak_id', $this->input->post('datatable[query][rak]'), 'after');
        }

        $this->db->order_by($sort_field, $sort_order);
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function count_inventaris_for_ubah_rak($search = '') {
        $this->db->from('siperpus_inventaris i');
        $this->db->join('siperpus_buku b', 'b.no_klas = i.no_klas AND b.ISBN = i.ISBN', 'left');
        $this->db->join('lokasi_rak r', 'r.lokasirak_id = i.lokasirak_id', 'left');
        $this->db->join('lokasi_gedung g', 'g.lokasigedung_id = r.lokasigedung_id', 'left');
        $this->db->join('lokasi_kampus k', 'k.lokasikampus_id = g.lokasikampus_id', 'left');
        $this->db->where('i.status', 'A');
        
        if ($this->input->post('datatable[query][klas]') !== NULL && $this->input->post('datatable[query][klas]')!=='') {
            $klas_filter = substr($this->input->post('datatable[query][klas]'), 0, 1);
            $this->db->where('LEFT(b.no_klas,1)', $klas_filter);
        }
        if ($this->input->post('datatable[query][kel]')) {
            $this->db->where('b.idkategori', $this->input->post('datatable[query][kel]'));
        }
        if ($this->input->post('datatable[query][kampus]')) {
            $this->db->where('k.lokasikampus_id', $this->input->post('datatable[query][kampus]'), 'after');
        }
        if ($this->input->post('datatable[query][gedung]')) {
            $this->db->where('g.lokasigedung_id', $this->input->post('datatable[query][gedung]'), 'after');
        }
        if ($this->input->post('datatable[query][rak]')) {
            $this->db->where('r.lokasirak_id', $this->input->post('datatable[query][rak]'), 'after');
        }
        
        if ($search) {
            $this->db->group_start();
            $this->db->like('i.no_inv', $search);
            $this->db->or_like('b.judul', $search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    // Fungsi untuk fetch options lokasi rak dengan optgroup (join kampus, gedung, rak)
    public function get_lokasi_rak_options() {
        $this->db->select('k.lokasikampus_id, k.nama_kampus, g.lokasigedung_id, g.nama_gedung, r.lokasirak_id, r.nama_rak');
        $this->db->from('lokasi_rak r');
        $this->db->join('lokasi_gedung g', 'r.lokasigedung_id = g.lokasigedung_id', 'left');
        $this->db->join('lokasi_kampus k', 'g.lokasikampus_id = k.lokasikampus_id', 'left');
        $this->db->where('r.status', 1); // Asumsi hanya rak aktif
        $query = $this->db->get();

        $options = [];
        foreach ($query->result() as $row) {
            $group_label = $row->nama_kampus . ' - ' . $row->nama_gedung;
            $options[$group_label][] = [
                'value' => $row->lokasirak_id,
                'text'  => $row->nama_rak
            ];
        }

        return $options;
    }
    
    /**
    * Update lokasirak_id berdasarkan no_inv
    * @param string $no_inv
    * @param int $lokasirak_id
    * @return bool TRUE jika berhasil update
    */
   public function update_lokasirak($no_inv, $lokasirak_id) {
       $this->db->where('no_inv', $no_inv);
       $this->db->update('siperpus_inventaris', [
           'lokasirak_id' => $lokasirak_id
       ]);

       return $this->db->affected_rows() > 0;
   }
   
    public function cek_relasi_buku($isbn, $no_klas) {

        // cek inventaris berdasarkan buku
        $this->db->select('no_inv');
        $this->db->from('siperpus_inventaris');
        $this->db->where('ISBN', $isbn);
        $this->db->where('no_klas', $no_klas);
        $inventaris = $this->db->get()->result();

        if (!$inventaris) return false;

        $no_inv_list = array_column($inventaris, 'no_inv');

        // 🔥 CEK PEMINJAMAN
        $this->db->where_in('no_inv', $no_inv_list);
        $cek_pinjam = $this->db->get('siperpus_transaksi')->num_rows();

        if ($cek_pinjam > 0) {
            return 'peminjaman';
        }

        // 🔥 CEK PENYIANGAN
        $this->db->where_in('no_inv', $no_inv_list);
        $cek_penyiangan = $this->db->get('penyiangan_detail')->num_rows();

        if ($cek_penyiangan > 0) {
            return 'penyiangan';
        }

        // 🔥 CEK OPNAME
        $this->db->where_in('no_inv', $no_inv_list);
        $cek_opname = $this->db->get('opname_detail')->num_rows();

        if ($cek_opname > 0) {
            return 'opname';
        }

        return false;
    }
    

}
