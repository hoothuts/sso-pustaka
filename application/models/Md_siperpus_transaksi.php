<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_siperpus_transaksi extends CI_Model
{

    var $table = 'siperpus_transaksi';
    var $column_order = array(array('tgl', 'siperpus_transaksi.tgl_pinjam'), array('batas', 'siperpus_transaksi.batas'), array('buku', 'sb.judul'));
    var $column_search = array('sb.judul');
    var $order = array('tgl_pinjam' => 'desc'); // default order

    function countFiltered($id)
    {
        $this->getDatatablesQuery($id);
        $query = $this->db->count_all_results();
        return $query;
    }
    public function getValue($b, $text)
    {
        foreach ($b as $v) {
            if (in_array($text, $v, true)) {
                return $v[1];
            }
        }
        return false;
    }

    private function getDatatablesQuery($id)
    {

        $this->db->from($this->table);
        $this->db->where('no_anggota', $id);
        $this->db->where('kembali', 0);
        $i = 0;
        foreach ($this->column_search as $item) // loop column
        {
            if ($this->input->post('datatable[query][generalSearch]')) // if datatable send POST for search
            {
                if ($i === 0) // first loop
                {
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
        $val = $this->getValue($this->column_order, $this->input->post('datatable[sort][field]'));
        if ($val != false) // here order processing
        {
            $this->db->order_by($val, $this->input->post('datatable[sort][sort]'));
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    // Fetch data according to per_page limit.

    public function getDatatables($id)
    {

        $this->getDatatablesQuery($id);
        if ($this->input->post('datatable[pagination][perpage]') != -1)
            $this->db->limit($this->input->post('datatable[pagination][perpage]'), ($this->input->post('datatable[pagination][perpage]') * (($this->input->post('datatable[pagination][page]') - 1))));
        $this->db->join('siperpus_inventaris si', 'si.no_inv = siperpus_transaksi.no_inv');
        $this->db->join('(select DISTINCT no_klas , idkategori , judul , tajuksubyek , cetakkatalog_judulpenggal , judulasli , penulis , penyusun , penyadur , penerjemah , penyunting , illustrator , editor , edisi , cetakan , kd_penerbit , thn_terbit , jilid , hlm_romawi , jml_hal , ilustrasi , tabel , ukuran_fisik , bibliografi , indeks , ISBN , bahasa , jml_buku , jur , tanggal , no_rak , tajuk , seri , allow_review , review , deskripsi , cover , displayed from siperpus_buku) sb', 'sb.isbn = si.isbn and sb.no_klas = si.no_klas');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }

            return $data;
        } else {
            return false;
        }
    }

    function addTransaksi($data)
    {
        $this->db->insert('siperpus_transaksi', $data);
        return 1;
    }
    function hapusTransaksi($data)
    {
        $this->db->where('tid', $data);
        $this->db->delete('siperpus_transaksi');
    }
    function updateTransaksi($param, $data)
    {
        $this->db->where('tid', $param);
        $this->db->update('siperpus_transaksi', $data);
    }
    // function updateTransaksi($tid){
    //   return $this->db->from('siperpus_transaksi')->where('tid',$tid);
    // }
    function getLastId()
    {
        $hasil = $this->db->query("SELECT * FROM siperpus_transaksi order by tid desc");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                return $row->tid;
            }
        } else {
            return 0;
        }
    }
    function isPinjam($inv)
    {
        $hasil = $this->db->query("SELECT * FROM siperpus_transaksi where no_inv='$inv' and kembali=0 ");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                return true;
            }
        } else {
            return false;
        }
    }
    function getPinjamKe($no, $inv)
    {
        $hasil = $this->db->query("SELECT pinjam_ke FROM siperpus_transaksi where no_anggota='$no' and no_inv='$inv'");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                return $row->pinjam_ke;
            }
        } else {
            return 0;
        }
    }
    function getTransaksiById($id)
    {
        $hasil = $this->db->get_where('siperpus_transaksi', array('tid' => $id))->result();
        $data = $hasil;
        return $data;
    }
    function getTransaksiTanpaKartu($id)
    {
        $hasil = $this->db->get_where('siperpus_transaksi', array('no_inv' => $id, 'kembali' => 0))->result();
        $data = $hasil;
        return $data;
    }
    function getTransaksiDipinjamByISBN($isbn, $noklas)
    {
        $hasil = $this->db->query("SELECT * FROM siperpus_transaksi where kembali=0 and no_inv in (select no_inv from siperpus_inventaris where isbn='$isbn' and no_klas='$noklas')");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
    function getJumlahTran()
    {
        $hasil = $this->db->query("SELECT count(*) as jumlah FROM siperpus_transaksi");
        $data=$hasil->row();
        return $data->jumlah;
    }
    function getPeminjamanHari($hari)
    {
        $hasil = $this->db->query("SELECT distinct(tgl_pinjam),(select count(*) from siperpus_transaksi where tgl_pinjam=st.tgl_pinjam) as jumlah  FROM siperpus_transaksi st group by DATE_FORMAT(tgl_pinjam, '%c %d %Y') order by tgl_pinjam desc limit $hari");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
    function getTransaksiByNoInvExist($id)
    {
        $query = "SELECT * FROM siperpus_transaksi WHERE no_inv = '" . $id . "'";
        $hasil = $this->db->query($query);
        if ($hasil->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    function getTransaksiByNoInv($id)
    {
        $query = "SELECT * FROM siperpus_transaksi WHERE no_inv = '" . $id . "' and kembali=0";
        $hasil = $this->db->query($query)->result();
        $data = $hasil;
        return $data;
    }
    function getDendaByKelas($kls)
    {
        $query = "SELECT sum(denda) as denda FROM siperpus_transaksi WHERE denda>0 and no_anggota in(select nis from vwsiswa where kelas='$kls')";
        $hasil = $this->db->query($query)->result();
        $data = $hasil;
        return $data;
    }
    function getDendaByKelas_tgl($kls, $tglAwal, $tglAkhir)
    {
        $this->db->select("SUM(t.denda) as denda");
        $this->db->from('siperpus_transaksi t');
        $this->db->join('vwsiswa s', 's.nis = t.no_anggota', 'left');
        $this->db->join('siperpus_inventaris si', 'si.no_inv = t.no_inv');
        $this->db->join('siperpus_buku sb', 'sb.no_klas = si.no_klas and sb.ISBN = si.ISBN');
        
        $this->db->where('DATE(t.tgl_kembali) >=', $tglAwal);
        $this->db->where('DATE(t.tgl_kembali) <=', $tglAkhir);
        $this->db->where('s.kelas', $kls);
        $this->db->where('t.denda >', 0);
        
        return $this->db->get()->result();
        
        /*$query = "SELECT sum(denda) as denda "
                . "FROM siperpus_transaksi "
                . "WHERE denda>0 and no_anggota in(select nis from vwsiswa where kelas='$kls') and tgl_kembali >= '$tglAwal' and tgl_kembali <= '$tglAkhir'";
        $hasil = $this->db->query($query)->result();
        $data = $hasil;
        return $data;*/
    }
    
    function getPeminjamanByKelas($kls)
    {
        $query = "SELECT COUNT(*) as total 
                  FROM siperpus_transaksi t
                  JOIN vwsiswa s ON t.no_anggota = s.nis
                  WHERE s.kelas = ?";

        return $this->db->query($query, [$kls])->result();
    }

    function getPeminjamanByKelas_tgl($kls, $tglAwal, $tglAkhir)
    {
        $query = "SELECT COUNT(*) as total 
                  FROM siperpus_transaksi t
                  JOIN vwsiswa s ON t.no_anggota = s.nis
                  WHERE s.kelas = ? 
                  AND t.tgl_pinjam BETWEEN ? AND ?";

        return $this->db->query($query, [$kls, $tglAwal, $tglAkhir])->result();
    }
    
    function getPeminjamanByKelasPerTanggal($kls, $tglAwal, $tglAkhir) {
        // Mengambil data berkelompok per tanggal dalam satu rentang waktu
        $query = "SELECT tgl_pinjam, COUNT(*) as total 
              FROM siperpus_transaksi t
              JOIN vwsiswa s ON t.no_anggota = s.nis
              WHERE s.kelas = ? 
              AND t.tgl_pinjam BETWEEN ? AND ?
              GROUP BY tgl_pinjam";

        return $this->db->query($query, [$kls, $tglAwal, $tglAkhir])->result();
    }

    // Fungsi untuk Mode Per Tanggal (Matriks)
    function getPeminjamanByKlasifikasiPerTanggal($klas, $tglAwal, $tglAkhir, $pilihprodi = '')
    {
        // Mengambil digit pertama dari input klasifikasi
        $klas_filter = substr($klas, 0, 1);

        $this->db->select('t.tgl_pinjam, COUNT(*) as total');
        $this->db->from('siperpus_transaksi t');
        $this->db->join('vwsiswa s', 't.no_anggota = s.nis');
        $this->db->join('siperpus_inventaris si', 't.no_inv = si.no_inv'); // Join ke inventaris

        $this->db->where('LEFT(si.no_klas, 1) =', $klas_filter);
        $this->db->where('t.tgl_pinjam >=', $tglAwal);
        $this->db->where('t.tgl_pinjam <=', $tglAkhir);

        if ($pilihprodi != '') {
            $this->db->where('s.kelas', $pilihprodi);
        }

        $this->db->group_by('t.tgl_pinjam');
        return $this->db->get()->result();
    }

    // Fungsi Akumulasi Berdasarkan Tanggal
    function getPeminjamanByKlasifikasi_tgl($klas, $tglAwal, $tglAkhir, $pilihprodi = '')
    {
        $klas_filter = substr($klas, 0, 1);

        $this->db->select('COUNT(*) as total');
        $this->db->from('siperpus_transaksi t');
        $this->db->join('vwsiswa s', 't.no_anggota = s.nis');
        $this->db->join('siperpus_inventaris si', 't.no_inv = si.no_inv');

        $this->db->where('LEFT(si.no_klas, 1) =', $klas_filter);
        $this->db->where('t.tgl_pinjam >=', $tglAwal);
        $this->db->where('t.tgl_pinjam <=', $tglAkhir);

        if ($pilihprodi != '') {
            $this->db->where('s.kelas', $pilihprodi);
        }

        return $this->db->get()->result();
    }

    // Fungsi Akumulasi Standar
    function getPeminjamanByKlasifikasi($klas, $pilihprodi = '')
    {
        $klas_filter = substr($klas, 0, 1);

        $this->db->select('COUNT(*) as total');
        $this->db->from('siperpus_transaksi t');
        $this->db->join('vwsiswa s', 't.no_anggota = s.nis');
        $this->db->join('siperpus_inventaris si', 't.no_inv = si.no_inv');

        $this->db->where('LEFT(si.no_klas, 1) =', $klas_filter);

        if ($pilihprodi != '') {
            $this->db->where('s.kelas', $pilihprodi);
        }

        return $this->db->get()->result();
    }
    
    // Fungsi untuk Mode Per Tanggal (Matriks)
    function getPeminjamanByKategoriPerTanggal($kat_id, $awal, $akhir, $pilihprodi = '')
    {
        $this->db->select('t.tgl_pinjam, COUNT(*) as total');
        $this->db->from('siperpus_transaksi t');
        $this->db->join('vwsiswa s', 't.no_anggota = s.nis');
        $this->db->join('siperpus_inventaris si', 't.no_inv = si.no_inv');
        $this->db->join('siperpus_buku sb', 'si.no_klas = sb.no_klas AND si.ISBN = sb.ISBN');

        $this->db->where('sb.idkategori', $kat_id);
        $this->db->where('t.tgl_pinjam >=', $awal);
        $this->db->where('t.tgl_pinjam <=', $akhir);

        if ($pilihprodi != '') {
            $this->db->where('s.kelas', $pilihprodi);
        }

        $this->db->group_by('t.tgl_pinjam');
        return $this->db->get()->result();
    }

    // Fungsi Akumulasi Berdasarkan Tanggal
    function getPeminjamanByKategori_tgl($kat_id, $awal, $akhir, $pilihprodi = '')
    {
        $this->db->select('COUNT(*) as total');
        $this->db->from('siperpus_transaksi t');
        $this->db->join('vwsiswa s', 't.no_anggota = s.nis');
        $this->db->join('siperpus_inventaris si', 't.no_inv = si.no_inv');
        $this->db->join('siperpus_buku sb', 'si.no_klas = sb.no_klas AND si.ISBN = sb.ISBN');

        $this->db->where('sb.idkategori', $kat_id);
        $this->db->where('t.tgl_pinjam >=', $awal);
        $this->db->where('t.tgl_pinjam <=', $akhir);

        if ($pilihprodi != '') {
            $this->db->where('s.kelas', $pilihprodi);
        }

        return $this->db->get()->result();
    }

    // Fungsi Akumulasi Standar
    function getPeminjamanByKategori($kat_id, $pilihprodi = '')
    {
        $this->db->select('COUNT(*) as total');
        $this->db->from('siperpus_transaksi t');
        $this->db->join('vwsiswa s', 't.no_anggota = s.nis');
        $this->db->join('siperpus_inventaris si', 't.no_inv = si.no_inv');
        $this->db->join('siperpus_buku sb', 'si.no_klas = sb.no_klas AND si.ISBN = sb.ISBN');

        $this->db->where('sb.idkategori', $kat_id);

        if ($pilihprodi != '') {
            $this->db->where('s.kelas', $pilihprodi);
        }

        return $this->db->get()->result();
    }

    // 1. Mode Per Tanggal (Matriks untuk Top 10 Buku)
    function getPeminjamanByBukuPerTanggal($awal, $akhir, $pilihprodi = '', $limit = 10)
    {
        // A. Cari Top 10 Judul buku paling populer dalam rentang waktu tersebut
        $this->db->select('sb.judul, COUNT(*) as total_populer');
        $this->db->from('siperpus_transaksi t');
        $this->db->join('vwsiswa s', 't.no_anggota = s.nis');
        $this->db->join('siperpus_inventaris si', 't.no_inv = si.no_inv');
        $this->db->join('siperpus_buku sb', 'si.no_klas = sb.no_klas AND si.ISBN = sb.ISBN');

        $this->db->where('t.tgl_pinjam >=', $awal);
        $this->db->where('t.tgl_pinjam <=', $akhir);

        if ($pilihprodi != '') {
            $this->db->where('s.kelas', $pilihprodi);
        }

        $this->db->group_by('sb.judul');
        $this->db->order_by('total_populer', 'DESC');
        $this->db->limit($limit);
        $top_books = $this->db->get()->result();

        if (empty($top_books)) return [];

        $judul_list = array_map(function($b) { return $b->judul; }, $top_books);

        // B. Ambil data harian untuk buku-buku Top 10 tersebut
        $this->db->select('sb.judul, t.tgl_pinjam, COUNT(*) as total');
        $this->db->from('siperpus_transaksi t');
        $this->db->join('vwsiswa s', 't.no_anggota = s.nis');
        $this->db->join('siperpus_inventaris si', 't.no_inv = si.no_inv');
        $this->db->join('siperpus_buku sb', 'si.no_klas = sb.no_klas AND si.ISBN = sb.ISBN');

        $this->db->where_in('sb.judul', $judul_list);
        $this->db->where('t.tgl_pinjam >=', $awal);
        $this->db->where('t.tgl_pinjam <=', $akhir);

        if ($pilihprodi != '') {
            $this->db->where('s.kelas', $pilihprodi);
        }

        $this->db->group_by(['sb.judul', 't.tgl_pinjam']);

        return [
            'results' => $this->db->get()->result(),
            'top_list' => $judul_list
        ];
    }

    // 2. Mode Akumulasi (Standar)
    function getPeminjamanTerpopuler($awal = '', $akhir = '', $pilihprodi = '', $limit = 10)
    {
        $this->db->select('sb.judul, COUNT(*) as total');
        $this->db->from('siperpus_transaksi t');
        $this->db->join('vwsiswa s', 't.no_anggota = s.nis');
        $this->db->join('siperpus_inventaris si', 't.no_inv = si.no_inv');
        $this->db->join('siperpus_buku sb', 'si.no_klas = sb.no_klas AND si.ISBN = sb.ISBN');

        if ($awal && $akhir) {
            $this->db->where('t.tgl_pinjam >=', $awal);
            $this->db->where('t.tgl_pinjam <=', $akhir);
        }

        if ($pilihprodi != '') {
            $this->db->where('s.kelas', $pilihprodi);
        }

        $this->db->group_by('sb.judul');
        $this->db->order_by('total', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }
    
    function getAnggotaPinjamTerbanyak($pilihprodi = '')
    {
        $this->db->select('t.no_anggota, s.nama, COUNT(*) as total');
        $this->db->from('siperpus_transaksi t');
        $this->db->join('vwsiswa s', 't.no_anggota = s.nis');

        // Filter Prodi jika dipilih
        if ($pilihprodi != '') {
            $this->db->where('s.kelas', $pilihprodi); // Sesuaikan 'kelas' dengan nama kolom prodi di vwsiswa
        }
        $this->db->where('t.status',1);
        $this->db->group_by('t.no_anggota');
        $this->db->order_by('total', 'DESC');
        $this->db->limit(10);
        return $this->db->get()->result();
    }

    function getAnggotaPinjamTerbanyak_tgl($tglAwal, $tglAkhir, $pilihprodi = '')
    {
        $this->db->select('t.no_anggota, s.nama, COUNT(*) as total');
        $this->db->from('siperpus_transaksi t');
        $this->db->join('vwsiswa s', 't.no_anggota = s.nis');

        $this->db->where('t.tgl_pinjam >=', $tglAwal);
        $this->db->where('t.tgl_pinjam <=', $tglAkhir);

        // Filter Prodi jika dipilih
        if ($pilihprodi != '') {
            $this->db->where('s.kelas', $pilihprodi);
        }
        $this->db->where('t.status',1);
        $this->db->group_by('t.no_anggota');
        $this->db->order_by('total', 'DESC');
        $this->db->limit(10);
        return $this->db->get()->result();
    }
    
    function getAnggotaPinjamTerbanyakTahun($tahun, $pilihprodi = '')
    {
        $this->db->select('t.no_anggota, s.nama, COUNT(*) as total');
        $this->db->from('siperpus_transaksi t');
        $this->db->join('vwsiswa s', 't.no_anggota = s.nis');

        // Filter berdasarkan tahun
        $this->db->where('YEAR(t.tgl_pinjam)', $tahun);
        $this->db->where('t.status',1);
        // Filter Prodi jika dipilih
        if ($pilihprodi != '') {
            $this->db->where('s.kelas', $pilihprodi); 
        }

        $this->db->group_by('t.no_anggota');
        $this->db->order_by('total', 'DESC');
        $this->db->limit(10);

        return $this->db->get()->result();
    }
    
    function getPeminjamanKlasByTahun($tahun, $bulan, $klas)
    {
        $q = '';
        if ($bulan != '') $q = "and month(tgl_pinjam)='$bulan'";
        $query = "SELECT count(*) as total FROM siperpus_transaksi WHERE year(tgl_pinjam)='$tahun' $q and no_inv in (select no_inv from siperpus_inventaris where no_klas like '$klas%')";
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
    function getPeminjamanProdiByTahun($tahun, $bulan, $prodi)
    {
        $q = '';
        if ($bulan != '') $q = "and month(tgl_pinjam)='$bulan'";
        $query = "SELECT count(*) as total FROM siperpus_transaksi WHERE year(tgl_pinjam)='$tahun' $q and no_anggota in(select nis from vwsiswa where kelas='$prodi')";
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
    function getStatusBukuPinjamTerbanyak($status, $lim, $tglAwal = '', $tglAkhir = '') {
        // 1. Definisikan Subquery untuk Status Terakhir (MAX kd_hr)
        $sub_status = "(SELECT a.no_inv, a.ket 
                        FROM siperpus_hilangrusak a
                        JOIN (SELECT MAX(kd_hr) as max_id FROM siperpus_hilangrusak GROUP BY no_inv) b 
                        ON a.kd_hr = b.max_id) sh";

        // 2. Query Utama
        $this->db->select('si.no_klas, si.ISBN, sb.judul, COUNT(st.no_inv) as total');
        $this->db->from('siperpus_transaksi st');
        $this->db->join('siperpus_inventaris si', 'st.no_inv = si.no_inv');
        $this->db->join('siperpus_buku sb', 'si.no_klas = sb.no_klas AND si.ISBN = sb.ISBN');

        // 3. Left Join ke status terakhir
        $this->db->join($sub_status, 'si.no_inv = sh.no_inv', 'left');

        // 4. Filter Tanggal (jika ada rentang waktu peminjaman tertentu)
        if (!empty($tglAwal) && !empty($tglAkhir)) {
            $this->db->where('DATE(st.tgl_pinjam) >=', $tglAwal);
            $this->db->where('DATE(st.tgl_pinjam) <=', $tglAkhir);
        }

        // 5. Logic Status (Sesuai teknik sebelumnya)
        if ($status != '') {
            $this->db->where('sh.ket', $status);
        } else {
            $this->db->where('si.status', 'A');// yang aktif saja
            $this->db->group_start();
                $this->db->where('sh.no_inv', NULL);
                $this->db->or_where('sh.ket', 'K');
            $this->db->group_end();
        }

        // 6. Grouping dan Ordering
        $this->db->group_by(['si.no_klas', 'si.ISBN', 'sb.judul']);
        $this->db->order_by('total', 'DESC');
        $this->db->limit($lim);

        return $this->db->get()->result();
    }

    function getTrxBuku($noanggota)
    {
        $this->db->select('st.*,sb.judul');
        $this->db->from($this->table . ' as st');
        $this->db->join('siperpus_inventaris si', 'si.no_inv = st.no_inv');
        $this->db->join('siperpus_buku sb', 'sb.no_klas = si.no_klas and sb.ISBN = si.ISBN', 'left');

        $this->db->where('no_anggota', $noanggota);
        return $this->db->get()->result();
    }

    function getDataPinjam($noanggota, $nobuku)
    {
        $this->db->select('st.*,sb.judul');
        $this->db->from($this->table . ' as st');
        $this->db->join('siperpus_inventaris si', 'si.no_inv = st.no_inv');
        $this->db->join('siperpus_buku sb', 'sb.no_klas = si.no_klas and sb.ISBN = si.ISBN', 'left');

        $this->db->where('st.no_anggota', $noanggota);
        $this->db->where('st.no_inv', $nobuku);
        $this->db->where('st.kembali', 0);
        return $this->db->get()->row();
    }

    function getDataById($tid)
    {
        $this->db->select('st.*,sb.judul');
        $this->db->from($this->table . ' as st');
        $this->db->join('siperpus_inventaris si', 'si.no_inv = st.no_inv');
        $this->db->join('siperpus_buku sb', 'sb.no_klas = si.no_klas and sb.ISBN = si.ISBN', 'left');
        $this->db->where('st.tid', $tid);
        return $this->db->get()->row();
    }
    
    public function get_buku_terbanyak($tgl_awal = null, $tgl_akhir = null, $limit = null)
    {
        $this->db
            ->select('b.no_klas, b.ISBN, b.judul, b.cover, b.penulis, COUNT(t.tid) as total_pinjam')
            ->from('siperpus_transaksi t')
            ->join('siperpus_inventaris i', 'i.no_inv = t.no_inv')
            ->join('siperpus_buku b', 'b.no_klas = i.no_klas and b.ISBN = i.ISBN');

        // hanya tambahkan kondisi jika parameter ada isinya
        if (!empty($tgl_awal)) {
            $this->db->where('DATE(t.tgl_pinjam) >=', $tgl_awal);
        }
        if (!empty($tgl_akhir)) {
            $this->db->where('DATE(t.tgl_pinjam) <=', $tgl_akhir);
        }
        if (!empty($limit)) {
            $this->db->limit($limit);
        }else{
            $this->db->limit(12);
        }

        return $this->db
            ->group_by('b.no_klas, b.ISBN')
            ->order_by('total_pinjam', 'DESC')
            ->get()
            ->result();
    }

    
    public function get_mahasiswa_terbanyak($tgl_awal = null, $tgl_akhir = null, $limit = null)
    {
        $this->db
            ->select('t.no_anggota, s.nama, s.kelas, s.angkatan, COUNT(t.tid) as total_pinjam')
            ->from('siperpus_transaksi t')
            ->join('siperpus_inventaris si', 't.no_inv = si.no_inv')
            ->join('siperpus_buku sb', 'si.no_klas = sb.no_klas AND si.ISBN = sb.ISBN')
            ->join('sim_akademik.msmhs v', 'v.NIMHSMSMHS = t.no_anggota')//jika join ke vwanggota jadi lambat
            ->join('vwsiswa s', 's.nis = v.NIMHSMSMHS');
        //$this->db->where('s.status_siswa', 'A');
        // hanya tambahkan kondisi jika parameter ada isinya
        if (!empty($tgl_awal)) {
            $this->db->where('t.tgl_pinjam >=', $tgl_awal);
        }
        if (!empty($tgl_akhir)) {
            $this->db->where('t.tgl_pinjam <=', $tgl_akhir);
        }
        
        if (!empty($limit)) {
            $this->db->limit($limit);
        }else{
            $this->db->limit(2);
        }

        return $this->db
            ->group_by('t.no_anggota')
            ->order_by('total_pinjam', 'DESC')
            ->get()
            ->result();
    }

        // Ambil data mahasiswa berdasarkan NIM (dari view vwsiswa)
    public function get_siswa_by_nim($nim)
    {
        return $this->db->get_where('vwsiswa', ['nis' => $nim])->row();
    }

    // Ambil peminjaman mandiri berdasarkan NIM dan tanggal
    public function get_peminjaman_mandiri_by_date($nim, $tgl_pinjam)
    {
        $this->db->select("t.*, b.judul, b.penulis, i.no_barcode, i.no_klas");
        $this->db->from('siperpus_transaksi t');
        $this->db->join('siperpus_inventaris i', 't.no_inv = i.no_inv');
        $this->db->join('siperpus_buku b', 'i.no_klas = b.no_klas AND i.ISBN = b.ISBN', 'inner');
        
        $this->db->where('t.no_anggota', $nim);
        $this->db->where('t.tgl_pinjam', $tgl_pinjam);
        $this->db->where('t.is_mandiri', 'Ya');
        $this->db->where('t.status', 1);
        $this->db->where('kembali', 0);
        $this->db->order_by('t.tid', 'ASC');

        return $this->db->get()->result();
    }
    
        /**
     * Ambil data pengembalian mandiri berdasarkan NIM dan tanggal
     */
    public function get_pengembalian_mandiri_by_date($nim, $tgl_pengembalian)
    {
        $this->db->select("t.*, b.judul, b.penulis, i.no_barcode, i.no_klas");
        $this->db->from('siperpus_transaksi t');
        $this->db->join('siperpus_inventaris i', 't.no_inv = i.no_inv');
        $this->db->join('siperpus_buku b', 'i.no_klas = b.no_klas AND i.ISBN = b.ISBN');
        
        $this->db->where('t.no_anggota', $nim);
        $this->db->where('t.is_mandiri_pengembalian', 'Ya');
        $this->db->where('t.tgl_kembali', $tgl_pengembalian);
        $this->db->where('t.status', 1);
        $this->db->where('kembali', 1);
        $this->db->order_by('t.tid', 'ASC');

        return $this->db->get()->result();
    }
    
    //Ambil peminjaman belum kembali
    public function get_peminjaman_mhs_belum_kembali()
    {
        $this->db->select("t.tid, t.tgl_pinjam, t.batas, b.judul, b.penulis, i.no_barcode, i.no_klas, s.EMAIL, s.nama");
        $this->db->from('siperpus_transaksi t');
        $this->db->join('vwsiswa s', 's.nis = t.no_anggota');
        $this->db->join('siperpus_inventaris i', 't.no_inv = i.no_inv');
        $this->db->join('siperpus_buku b', 'i.no_klas = b.no_klas AND i.ISBN = b.ISBN');
        $this->db->where('t.status', 1);
        $this->db->where('kembali', 0);
        $this->db->where('CURDATE() >', 't.batas');
        $this->db->order_by('t.tid', 'ASC');

        return $this->db->get()->result();
    }
    
    public function getStatistikDendaHarian($awal, $akhir)
    {
        $this->db->select("
            DATE_FORMAT(t.tgl_kembali, '%d-%m-%Y') as tgl,
            s.kelas as prodi,
            SUM(t.denda) as denda
        ");
        $this->db->from('siperpus_transaksi t');
        $this->db->join('vwsiswa s', 's.nis = t.no_anggota', 'left');
        $this->db->join('siperpus_inventaris si', 'si.no_inv = t.no_inv');
        $this->db->join('siperpus_buku sb', 'sb.no_klas = si.no_klas and sb.ISBN = si.ISBN');
        
        $this->db->where('DATE(t.tgl_kembali) >=', $awal);
        $this->db->where('DATE(t.tgl_kembali) <=', $akhir);
        $this->db->where('t.denda >', 0);

        $this->db->group_by([
            'DATE(t.tgl_kembali)',
            's.kelas'
        ]);
        $this->db->order_by(
            'DATE(t.tgl_kembali)',
            'ASC'
        );
        $this->db->order_by(
            's.kelas',
            'ASC'
        );
        return $this->db->get()->result();
    }
    
    public function getPegawaiPinjamTerbanyak()
    {
        $this->db->select('
            t.no_anggota,
            p.nama,
            COUNT(*) as total
        ');

        $this->db->from('siperpus_transaksi t');
        $this->db->join('pegawai p', 'p.nip = t.no_anggota');

        $this->db->where('t.status', 1);
        $this->db->where('p.status', 1);

        $this->db->group_by('t.no_anggota');
        $this->db->order_by('total', 'DESC');
        $this->db->limit(10);

        return $this->db->get()->result();
    }

    public function getPegawaiPinjamTerbanyak_tgl($tglAwal, $tglAkhir)
    {
        $this->db->select('
            t.no_anggota,
            p.nama,
            COUNT(*) as total
        ');

        $this->db->from('siperpus_transaksi t');
        $this->db->join('pegawai p', 'p.nip = t.no_anggota');

        $this->db->where('t.status', 1);
        $this->db->where('p.status', 1);

        $this->db->where('DATE(t.tgl_pinjam) >=', $tglAwal);
        $this->db->where('DATE(t.tgl_pinjam) <=', $tglAkhir);

        $this->db->group_by('t.no_anggota');
        $this->db->order_by('total', 'DESC');
        $this->db->limit(10);

        return $this->db->get()->result();
    }

}
