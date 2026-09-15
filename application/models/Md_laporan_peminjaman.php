<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_laporan_peminjaman extends CI_Model {

    protected $column_order = [
        'nomor' => 'st.no_anggota',
        'nama' => 'ang.nama',
        'noinv' => 'st.no_inv',
        'judul' => 'sb.judul',
        'tanggal' => 'st.tgl_pinjam',
        'batas' => 'st.batas'
    ];
    protected $column_search = ['st.no_anggota', 'ang.nama', 'st.no_inv', 'sb.judul', 'st.tgl_kembali', 'st.tgl_pinjam', 'st.batas'];
    protected $order_default = ['st.tgl_pinjam' => 'desc'];

    private function getDatatablesQuery() {
        $this->db->from('siperpus_transaksi st');

        // 1. Join Dasar
        $this->db->join('siperpus_inventaris si', 'si.no_inv = st.no_inv');
        $this->db->join('siperpus_buku sb', 'sb.no_klas = si.no_klas AND sb.ISBN = si.ISBN');

        // 2. Logic Join Berdasarkan Jenis Anggota
        $query_data = (array) $this->input->post('datatable[query]');
        $jenis = $query_data['jenis'] ?? '';

        if ($jenis === 'pegawai') {
            $this->db->join('pegawai ang', 'ang.nip = st.no_anggota');
        } elseif ($jenis === 'anggota+luar') {
            $this->db->join('siperpus_anggota_luar ang', 'ang.noid = st.no_anggota');
        } else {
            $this->db->join('vwsiswa ang', 'ang.nis = st.no_anggota');
            if (isset($query_data['pilihprodi']) && $query_data['pilihprodi'] !== 'All') {
                $this->db->where('ang.kelas', $query_data['pilihprodi']);
            }
        }

        // 3. Logic Searching
        $search = $query_data['generalSearch'] ?? '';
        if ($search !== '') {
            $this->db->group_start();
            foreach ($this->column_search as $i => $item) {
                ($i === 0) ? $this->db->like($item, $search) : $this->db->or_like($item, $search);
            }
            $this->db->group_end();
        }

        // 4. Logic Filtering Tanggal
        if (!empty($query_data['tanggalawal'])) {
            $this->db->where('date(st.tgl_pinjam) >=', $query_data['tanggalawal']);
        }
        if (!empty($query_data['tanggalakhir'])) {
            $this->db->where('date(st.tgl_pinjam) <=', $query_data['tanggalakhir']);
        }

        // tambahan: filter jenis peminjaman
        $jenis_peminjaman = $query_data['jenispeminjaman'] ?? 'All';
        if ($jenis_peminjaman == 'Mandiri') {
            $this->db->where('st.is_mandiri', 'Ya');
        } elseif ($jenis_peminjaman == 'Reguler') {
            $this->db->where('st.is_mandiri IS NULL', null, false);
        }

        // 5. Grouping (Wajib karena join ke tabel inventaris/buku)
        $this->db->group_by(["st.no_anggota", "st.no_inv", "st.tgl_pinjam"]);

        // 6. Logic Ordering (Menggantikan getValue)
        $sortField = $this->input->post('datatable[sort][field]');
        $sortOrder = $this->input->post('datatable[sort][sort]');

        if (isset($this->column_order[$sortField])) {
            $this->db->order_by($this->column_order[$sortField], $sortOrder);
        } else {
            $this->db->order_by(key($this->order_default), current($this->order_default));
        }
    }

    public function getDatatables() {
        $this->getDatatablesQuery();

        $perpage = (int) $this->input->post('datatable[pagination][perpage]');
        $page = (int) $this->input->post('datatable[pagination][page]');

        if ($perpage !== -1) {
            $limit = $perpage;
            $offset = $limit * (max(1, $page) - 1);
            $this->db->limit($limit, $offset);
        }

        return $this->db->get()->result();
    }

    public function countFiltered() {
        $this->getDatatablesQuery();

        // PERBAIKAN: Paksa select hanya 1 kolom unik untuk menghindari duplikasi nama kolom di subquery
        $this->db->select('st.no_inv', FALSE);

        $subquery = $this->db->get_compiled_select();
        $query = $this->db->query("SELECT COUNT(*) as total FROM ($subquery) AS filter_count");

        return (int) ($query->row()->total ?? 0);
    }

    private function getLaporanQuery($ta, $tl, $src) {
        $this->db->from('siperpus_transaksi st');

        // 1. Logic Searching (Disederhanakan)
        if ($src) {
            $this->db->group_start();
            foreach ($this->column_search as $i => $item) {
                ($i === 0) ? $this->db->like($item, $src) : $this->db->or_like($item, $src);
            }
            $this->db->group_end();
        }

        // 2. Logic Filtering Tanggal
        if ($ta)
            $this->db->where('date(st.tgl_pinjam) >=', $ta);
        if ($tl)
            $this->db->where('date(st.tgl_pinjam) <=', $tl);

        // 3. Default Ordering
        $this->db->order_by(key($this->order_default), current($this->order_default));
    }

    public function getLaporan($jenis, $ta, $tl, $src, $prodi, $jenis_peminjaman = 'All') {
        $this->getLaporanQuery($ta, $tl, $src);
        
        if ($jenis_peminjaman == 'Mandiri') {
            $this->db->where('st.is_mandiri', 'Ya');
        } elseif ($jenis_peminjaman == 'Reguler') {
            $this->db->where('st.is_mandiri IS NULL', null, false);
        }

        $this->db->join('siperpus_inventaris si', 'si.no_inv = st.no_inv');
        $this->db->join('siperpus_buku sb', 'sb.no_klas = si.no_klas AND sb.ISBN = si.ISBN');

        // Inisialisasi select dasar
        // Gunakan alias 'nama' dan 'kelas' agar seragam di semua jenis anggota
        if ($jenis === 'pegawai') {
            $this->db->select('st.*, sb.judul, si.no_barcode, ang.nama as nama, "" as kelas'); // Sesuaikan nama kolom asli vwdosen
            $this->db->join('pegawai ang', 'ang.nip = st.no_anggota');
        } elseif ($jenis === 'anggota+luar') {
            $this->db->select('st.*, sb.judul, si.no_barcode, ang.nama as nama, "" as kelas'); // Sesuaikan nama kolom asli anggota luar
            $this->db->join('siperpus_anggota_luar ang', 'ang.noid = st.no_anggota');
        } else {
            // Untuk Mahasiswa/Siswa
            $this->db->select('st.*, sb.judul, si.no_barcode, ang.nama as nama, ang.kelas as kelas');
            $this->db->join('vwsiswa ang', 'ang.nis = st.no_anggota');
            if ($prodi !== 'All' && !empty($prodi)) {
                $this->db->where('ang.kelas', $prodi);
            }
        }

        $this->db->group_by(["st.no_anggota", "st.no_inv", "st.tgl_pinjam"]);
        return $this->db->get()->result();
    }

    /* -- Start laporan_buku_populer -- */
    private function getBukuPopulerQuery() {
        $this->db->select('sb.judul, sb.no_klas, sb.ISBN, sb.idkategori, sb.thn_terbit, sk.nmkategori as kategori, COUNT(st.no_inv) as total_dipinjam');
        $this->db->from('siperpus_buku sb');
        $this->db->join('siperpus_kategori sk', 'sk.idkategori = sb.idkategori');
        $this->db->join('siperpus_inventaris si', 'si.no_klas = sb.no_klas AND si.ISBN = sb.ISBN');
        $this->db->join('siperpus_transaksi st', 'st.no_inv = si.no_inv');

        $query_data = (array) $this->input->post('datatable[query]');

        // Filter Tanggal
        if (!empty($query_data['tanggalawal']))
            $this->db->where('date(st.tgl_pinjam) >=', $query_data['tanggalawal']);
        if (!empty($query_data['tanggalakhir']))
            $this->db->where('date(st.tgl_pinjam) <=', $query_data['tanggalakhir']);

        // Filter Kategori & Klasifikasi
        // Logic Filtering (Aman untuk nilai "0")
        if (isset($query_data['klasifikasi']) && $query_data['klasifikasi'] !== '' && $query_data['klasifikasi'] !== '-') {
            $this->db->where('LEFT(sb.no_klas,1)', substr($query_data['klasifikasi'], 0, 1));
        }
        if (!empty($query_data['kategori']) && $query_data['kategori'] !== '-') {
            $this->db->where('sb.idkategori', $query_data['kategori']);
        }
        
        // Logic Join Berdasarkan Jenis Anggota
        $jenis = $query_data['jenis'] ?? '';

        if ($jenis === 'pegawai') {
            $this->db->join('pegawai ang', 'ang.nip = st.no_anggota');
        } elseif ($jenis === 'anggota+luar') {
            $this->db->join('siperpus_anggota_luar ang', 'ang.noid = st.no_anggota');
        } else {
            $this->db->join('vwsiswa ang', 'ang.nis = st.no_anggota');
            if (isset($query_data['pilihprodi']) && $query_data['pilihprodi'] !== 'All') {
                $this->db->where('ang.kelas', $query_data['pilihprodi']);
            }
        }
        
        // Searching
        $search = $query_data['generalSearch'] ?? '';
        if ($search !== '') {
            $this->db->group_start();
            $this->db->like('sb.judul', $search);
            $this->db->or_like('sb.no_klas', $search);
            $this->db->group_end();
        }

        $this->db->group_by(['sb.no_klas', 'sb.ISBN']);
        // Sorting
        $this->db->order_by('total_dipinjam', 'desc');
        
    }

    public function getDatatablesPopuler() {
        $this->getBukuPopulerQuery();
        $perpage = (int) $this->input->post('datatable[pagination][perpage]');
        $page = (int) $this->input->post('datatable[pagination][page]');
        if ($perpage !== -1) {
            $this->db->limit($perpage, ($perpage * (max(1, $page) - 1)));
        }
        return $this->db->get()->result();
    }

    public function countFilteredPopuler() {
        $this->getBukuPopulerQuery();
        $subquery = $this->db->get_compiled_select();
        return $this->db->query("SELECT COUNT(*) as total FROM ($subquery) AS filter_count")->row()->total;
    }

    public function getLaporanPopuler($jenis, $ta, $tl, $src, $kat, $klas, $prodi) {
        $this->db->select('sb.judul, sb.no_klas, sb.isbn, sb.idkategori,sb.thn_terbit, sk.nmkategori as kategori, COUNT(st.no_inv) as total_dipinjam');
        $this->db->from('siperpus_buku sb');
        $this->db->join('siperpus_kategori sk', 'sk.idkategori = sb.idkategori');
        $this->db->join('siperpus_inventaris si', 'si.no_klas = sb.no_klas AND si.ISBN = sb.ISBN');
        $this->db->join('siperpus_transaksi st', 'st.no_inv = si.no_inv');

        if ($klas !== NULL && $klas !== '' && $klas !== '-') {
            $this->db->where('LEFT(sb.no_klas,1)', substr($klas, 0, 1));
        }
        
        if ($ta)
            $this->db->where('date(st.tgl_pinjam) >=', $ta);
        if ($tl)
            $this->db->where('date(st.tgl_pinjam) <=', $tl);
        if ($kat && $kat!= '-')
            $this->db->where('sb.idkategori', $kat);
        if ($src)
            $this->db->like('sb.judul', $src);
        
        if ($jenis === 'pegawai') {
            $this->db->select('st.*, sb.judul, ang.nama as nama, "" as kelas'); // Sesuaikan nama kolom asli vwdosen
            $this->db->join('pegawai ang', 'ang.nip = st.no_anggota');
        } elseif ($jenis === 'anggota+luar') {
            $this->db->select('st.*, sb.judul, ang.nama as nama, "" as kelas'); // Sesuaikan nama kolom asli anggota luar
            $this->db->join('siperpus_anggota_luar ang', 'ang.noid = st.no_anggota');
        } else {
            // Untuk Mahasiswa/Siswa
            $this->db->select('st.*, sb.judul, ang.nama as nama, ang.kelas as kelas');
            $this->db->join('vwsiswa ang', 'ang.nis = st.no_anggota');
            if ($prodi !== 'All' && !empty($prodi)) {
                $this->db->where('ang.kelas', $prodi);
            }
        }

        $this->db->group_by(['sb.judul', 'sb.no_klas', 'sb.ISBN']);
        $this->db->order_by('total_dipinjam', 'desc');
        return $this->db->get()->result();
    }    
    /* -- End laporan_buku_populer --*/
    
    /* -- Start laporan anggota teraktif  --*/
    private function getAnggotaTeraktifQuery() {
        $this->db->select('st.no_anggota, ang.nama, COUNT(st.no_inv) as total_pinjam');
        $this->db->from('siperpus_transaksi st');
        $this->db->join('siperpus_inventaris si', 'st.no_inv = si.no_inv');
        $this->db->join('siperpus_buku sb', 'si.no_klas = sb.no_klas AND si.ISBN = sb.ISBN');

        $query_data = (array) $this->input->post('datatable[query]');

        // Filter Tanggal
        if (!empty($query_data['tanggalawal']))
            $this->db->where('date(st.tgl_pinjam) >=', $query_data['tanggalawal']);
        if (!empty($query_data['tanggalakhir']))
            $this->db->where('date(st.tgl_pinjam) <=', $query_data['tanggalakhir']);
        
        // Logic Join Berdasarkan Jenis Anggota
        $jenis = $query_data['jenis'] ?? '';

        if ($jenis === 'pegawai') {
            $this->db->select('"" as kelas'); 
            $this->db->join('pegawai ang', 'ang.nip = st.no_anggota');
        } elseif ($jenis === 'anggota+luar') {
            $this->db->select('"" as kelas'); 
            $this->db->join('siperpus_anggota_luar ang', 'ang.noid = st.no_anggota');
        } else {
            $this->db->select('ang.kelas as kelas');
            $this->db->join('vwsiswa ang', 'ang.nis = st.no_anggota');
            if (isset($query_data['pilihprodi']) && $query_data['pilihprodi'] !== 'All') {
                $this->db->where('ang.kelas', $query_data['pilihprodi']);
            }
        }
        
        $this->db->group_by(['st.no_anggota']);
        $this->db->order_by('total_pinjam', 'desc');
        
    }

    public function getAnggotaTeraktif() {
        $this->getAnggotaTeraktifQuery();
        $perpage = (int) $this->input->post('datatable[pagination][perpage]');
        $page = (int) $this->input->post('datatable[pagination][page]');
        if ($perpage !== -1) {
            $this->db->limit($perpage, ($perpage * (max(1, $page) - 1)));
        }
        return $this->db->get()->result();
    }

    public function countFilteredAnggotaTeraktif() {
        $this->getAnggotaTeraktifQuery();
        $subquery = $this->db->get_compiled_select();
        return $this->db->query("SELECT COUNT(*) as total FROM ($subquery) AS filter_count")->row()->total;
    }

    public function getLaporanAnggotaTeraktif($jenis, $prodi, $ta, $tl) {
        $this->db->select('st.no_anggota, ang.nama, COUNT(st.no_inv) as total_pinjam');
        $this->db->from('siperpus_transaksi st');
        $this->db->join('siperpus_inventaris si', 'st.no_inv = si.no_inv');
        $this->db->join('siperpus_buku sb', 'si.no_klas = sb.no_klas AND si.ISBN = sb.ISBN');

        if ($ta)
            $this->db->where('date(st.tgl_pinjam) >=', $ta);
        if ($tl)
            $this->db->where('date(st.tgl_pinjam) <=', $tl);
        
        if ($jenis === 'pegawai') {
            $this->db->select('"" as kelas'); // Sesuaikan nama kolom asli vwdosen
            $this->db->join('pegawai ang', 'ang.nip = st.no_anggota');
        } elseif ($jenis === 'anggota+luar') {
            $this->db->select('"" as kelas'); // Sesuaikan nama kolom asli anggota luar
            $this->db->join('siperpus_anggota_luar ang', 'ang.noid = st.no_anggota');
        } else {
            // Untuk Mahasiswa/Siswa
            $this->db->select('ang.kelas as kelas');
            $this->db->join('vwsiswa ang', 'ang.nis = st.no_anggota');
            if ($prodi !== 'All' && !empty($prodi)) {
                $this->db->where('ang.kelas', $prodi);
            }
        }

        $this->db->group_by(['st.no_anggota']);
        $this->db->order_by('total_pinjam', 'desc');
        return $this->db->get()->result();
    }    
    /* -- End laporan anggota teraktif  --*/
    
    public function getTransaksiMandiri($tid)
    {
        $this->db->select("
            st.*,
            ang.nama,
            ang.kelas,
            ang.nis
        ");

        $this->db->from('siperpus_transaksi st');
        $this->db->join('vwsiswa ang','ang.nis = st.no_anggota');

        $this->db->where('st.tid',$tid);
        $this->db->where('st.is_mandiri','Ya');

        return $this->db->get()->row();
    }
 
    public function getPeminjamanMandiriByTanggal($nim,$tgl_pinjam)
    {
        $this->db->select("
            st.*,
            sb.judul,
            sb.penulis,
            si.no_barcode
        ");

        $this->db->from('siperpus_transaksi st');
        $this->db->join('siperpus_inventaris si', 'si.no_inv = st.no_inv');
        $this->db->join('siperpus_buku sb', 'sb.no_klas = si.no_klas AND sb.ISBN = si.ISBN');

        $this->db->where('st.no_anggota',$nim);
        $this->db->where('st.tgl_pinjam',$tgl_pinjam);
        $this->db->where('st.is_mandiri','Ya');

        return $this->db->get()->result();
    }

}
