<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Statistik extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->database();
        $this->load->model('Md_siperpus_inventaris');
        $this->load->model('Md_siperpus_anggota_luar');
        $this->load->model('Md_vwdosen');
        $this->load->model('Md_vwsiswa');
        $this->load->model('Md_vwprodi');
        $this->load->model('Md_siperpus_data_buku');
        $this->load->model('Md_siperpus_klasifikasi');
        $this->load->model('Md_siperpus_presensi');
        $this->load->model('Md_siperpus_transaksi');
        $this->load->model('Md_siperpus_asal_buku');
        $this->load->model('Md_siperpus_bahasa');
        $this->load->model('Md_siperpus_kategori_buku');
        $this->load->model('Md_siperpus_kategori_buku');
        $this->load->model('Md_view_filebuku');
        $this->load->model('Md_siperpus_kategori');
        $this->load->model('Md_visitors');
        $this->load->model('Md_pegawai');
        $this->load->helper('pkrlib_helper');

        if ($this->session->userdata('login_type') != 'admin') {
            logoutNow();
        }
    }

    public function index() {
        
    }

    public function anggota() {
        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'statistik';
        $page_data['page_title'] = 'Statistik Anggota';
        $page_data['page_name'] = 'statistik';
        $page_data['page_action'] = 'statistik_anggota';
        $page_data['page_file'] = 'statistik_anggota';
        $page_data['page_dir'] = 'statistik';
        $page_data['list_prodi'] = $this->Md_vwprodi->getProdiAll();
        $page_data['pilihprodi'] = $this->input->post('pilihprodi') ?? '';
        $page_data['tanggalawal'] = $this->input->post('tanggalawal') ?? '';
        $page_data['tanggalakhir'] = $this->input->post('tanggalakhir') ?? '';

        $total = 0;
        $xAxis = array();
        $yAxis = array();
        $mspst = $this->Md_vwprodi->getProdiAll();
        if (!empty($page_data['pilihprodi'])) {

            $siswa = $this->Md_vwsiswa->getSiswaByKelas($page_data['pilihprodi'], $page_data['tanggalawal'], $page_data['tanggalakhir']);
            array_push($xAxis, array('number', $page_data['pilihprodi']));
            array_push($yAxis, count($siswa));
            $total += count($siswa);
        } else {
            foreach ($mspst as $m) {
                $siswa = $this->Md_vwsiswa->getSiswaByKelas($m->nmmspst, $page_data['tanggalawal'], $page_data['tanggalakhir']);
                array_push($xAxis, array('number', $m->nmmspst));
                array_push($yAxis, count($siswa));
                $total += count($siswa);
            }
        }
        array_push($xAxis, array('number', 'Pegawai'));
        array_push($xAxis, array('number', 'Anggota Luar'));

        $dosen = $this->Md_pegawai->count_pegawai();
        array_push($yAxis, $dosen->total);
        $total += $dosen->total;

        $anggotaluar = $this->Md_siperpus_anggota_luar->getAnggotaAll();
        $countanggota_lr = empty($anggotaluar) ? 0 : count($anggotaluar);
        array_push($yAxis, $countanggota_lr);
        $total += $countanggota_lr;
        $page_data['xAxis'] = $xAxis;
        $page_data['yAxis'] = $yAxis;
        $page_data['total'] = $total;

        $this->load->view('index', $page_data);
    }

    public function buku() {
        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'statistik';
        $page_data['page_title'] = 'Statistik Buku';
        $page_data['page_name'] = 'statistik';
        $page_data['page_action'] = 'statistik_buku';
        $page_data['page_file'] = 'statistik_buku';
        $page_data['page_dir'] = 'statistik';
        //$page_data['klasifikasi']  = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
        //$page_data['klas'] = $this->input->post('klas') ?? '';
        $page_data['tanggalawal'] = $this->input->post('tanggalawal') ?? '';
        $page_data['tanggalakhir'] = $this->input->post('tanggalakhir') ?? '';

        if (!$this->input->post('status'))
            $status = '';
        else
            $status = $this->input->post('status');
        $page_data['cur'] = $status;
        $nm = '';
        if ($status == '')
            $nm = 'Stok Aktif';
        if ($status == 'H')
            $nm = 'Hilang';
        if ($status == 'R')
            $nm = 'Rusak';
        if ($status == 'A')
            $nm = 'Diarsipkan';
        if ($status == 'L')
            $nm = 'Dilelang';

        $xAxis = array();
        $yAxis = array();
        $mspst = $this->Md_vwprodi->getProdiAll();
        $asal = $this->Md_siperpus_asal_buku->getAsalBukuAll();
        $bahasa = $this->Md_siperpus_bahasa->getBahasaAll();
        $kat = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
        $klas =  $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
        $total = 0;
        
        foreach ($klas as $k) {
            $invs = $this->Md_siperpus_inventaris->getInventarisByStatusKlas($status, $k->id, $page_data['tanggalawal'], $page_data['tanggalakhir']);
            array_push($xAxis, array('number', $k->nama));
            if ($invs > 0) {
                array_push($yAxis, $invs);
            } else {
                array_push($yAxis, 0);
            }
            $total += $invs;
        }
        
        $page_data['pxAxis'] = $xAxis;
        $page_data['pyAxis'] = $yAxis;
        $page_data['ptotal'] = $total;
        $page_data['ptitle'] = 'Statistik Inventarisasi Buku Berdasarkan Klasifikasi dengan Status ' . $nm;

        $xAxis = array();
        $yAxis = array();
        $total = 0;
        foreach ($kat as $k) {
            $invs = $this->Md_siperpus_inventaris->getInventarisByStatusKat($status, $k->idkategori, $page_data['tanggalawal'], $page_data['tanggalakhir']);
            array_push($xAxis, array('number', $k->nmkategori));
            if ($invs > 0) {
                array_push($yAxis, $invs);
            } else {
                array_push($yAxis, 0);
            }
            $total += $invs;
        }
        $page_data['kxAxis'] = $xAxis;
        $page_data['kyAxis'] = $yAxis;
        $page_data['ktotal'] = $total;
        $page_data['ktitle'] = 'Statistik Inventarisasi Buku Berdasarkan Kategori dengan Status ' . $nm;

        $xAxis = array();
        $yAxis = array();
        $total = 0;
        foreach ($asal as $a) {
            $invs = $this->Md_siperpus_inventaris->getInventarisByStatusAsal($status, $a->id, $page_data['tanggalawal'], $page_data['tanggalakhir']);
            array_push($xAxis, array('number', $a->nama));
            if ($invs > 0) {
                array_push($yAxis, $invs);
            } else {
                array_push($yAxis, 0);
            }
            $total += $invs;
        }
        $page_data['mxAxis'] = $xAxis;
        $page_data['myAxis'] = $yAxis;
        $page_data['mtotal'] = $total;
        $page_data['mtitle'] = 'Statistik Inventarisasi Buku Berdasarkan Asal Buku dengan Status ' . $nm;

        $xAxis = array();
        $yAxis = array();
        $total = 0;
        foreach ($bahasa as $b) {
            $pinjam = $this->Md_siperpus_inventaris->getInventarisByStatusBahasa($status, $b->id, $page_data['tanggalawal'], $page_data['tanggalakhir']);
            array_push($xAxis, array('number', $b->nama));
            if ($pinjam > 0) {
                array_push($yAxis, $pinjam);
            } else {
                array_push($yAxis, 0);
            }
            $total += $pinjam;
        }
        $page_data['nxAxis'] = $xAxis;
        $page_data['nyAxis'] = $yAxis;
        $page_data['ntotal'] = $total;
        $page_data['ntitle'] = 'Statistik Inventarisasi Buku Berdasarkan Bahasa Buku dengan Status ' . $nm;

        $xAxis = array();
        $yAxis = array();
        $total = 0;
        $bukupinjam = $this->Md_siperpus_transaksi->getStatusBukuPinjamTerbanyak($status, 10, $page_data['tanggalawal'], $page_data['tanggalakhir']);
        if (count($bukupinjam) > 0) {
            foreach ($bukupinjam as $b) {
                array_push($xAxis, array('number', $b->judul));
                if ($b->total > 0) {
                    array_push($yAxis, $b->total);
                } else {
                    array_push($yAxis, 0);
                }
                $total += $b->total;
            }
        } else {
            array_push($xAxis, array('number', ''));
            array_push($yAxis, 0);
        }
        $page_data['qxAxis'] = $xAxis;
        $page_data['qyAxis'] = $yAxis;
        $page_data['qtotal'] = $total;
        $page_data['qtitle'] = 'Statistik Inventarisasi Buku Yang Paling Banyak Dipinjam dengan Status ' . $nm;
        
        $this->load->view('index', $page_data);
        
    }
    
    public function buku_judul() {
        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'statistik';
        $page_data['page_title'] = 'Statistik Buku Berdasarkan Judul';
        $page_data['page_name'] = 'statistik';
        $page_data['page_action'] = 'statistik_buku';
        $page_data['page_file'] = 'statistik_buku_judul';
        $page_data['page_dir'] = 'statistik';
        $page_data['tanggalawal'] = $this->input->post('tanggalawal') ?? '';
        $page_data['tanggalakhir'] = $this->input->post('tanggalakhir') ?? '';

        if (!$this->input->post('status'))
            $status = '';
        else
            $status = $this->input->post('status');
        $page_data['cur'] = $status;
        $nm = '';
        if ($status == '')
            $nm = 'Stok Aktif';
        if ($status == 'H')
            $nm = 'Hilang';
        if ($status == 'R')
            $nm = 'Rusak';
        if ($status == 'A')
            $nm = 'Diarsipkan';
        if ($status == 'L')
            $nm = 'Dilelang';

        $klas = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
        $kat = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
        $asal = $this->Md_siperpus_asal_buku->getAsalBukuAll();
        $bahasa = $this->Md_siperpus_bahasa->getBahasaAll();
        $mspst = $this->Md_vwprodi->getProdiAll();

        $xAxis = array();
        $yAxis = array();
        $total = 0;
        foreach ($klas as $k) {
            $invs = $this->Md_siperpus_data_buku->getBukuByStatusKlas($status, $k->id, $page_data['tanggalawal'], $page_data['tanggalakhir']);
            array_push($xAxis, array('number', $k->nama));
            if ($invs > 0) {
                array_push($yAxis, $invs);
            } else {
                array_push($yAxis, 0);
            }
            $total += $invs;
        }
        $page_data['pxAxis'] = $xAxis;
        $page_data['pyAxis'] = $yAxis;
        $page_data['ptotal'] = $total;
        $page_data['ptitle'] = 'Statistik Judul Buku Berdasarkan Klasifikasi dengan Status ' . $nm;

        $xAxis = array();
        $yAxis = array();
        $total = 0;
        foreach ($kat as $k) {
            $invs = $this->Md_siperpus_data_buku->getBukuByStatusKat($status, $k->idkategori, $page_data['tanggalawal'], $page_data['tanggalakhir']);
            array_push($xAxis, array('number', $k->nmkategori));
            if ($invs > 0) {
                array_push($yAxis, $invs);
            } else {
                array_push($yAxis, 0);
            }
            $total += $invs;
        }
        $page_data['kxAxis'] = $xAxis;
        $page_data['kyAxis'] = $yAxis;
        $page_data['ktotal'] = $total;
        $page_data['ktitle'] = 'Statistik Judul Buku Berdasarkan Kategori dengan Status ' . $nm;

        $xAxis = array();
        $yAxis = array();
        $total = 0;
        foreach ($asal as $a) {
            $dt_buku_asal = $this->Md_siperpus_data_buku->getBukuByStatusAsal($status, $a->id, $page_data['tanggalawal'], $page_data['tanggalakhir']);
            array_push($xAxis, array('number', $a->nama));
            if ($dt_buku_asal > 0) {
                array_push($yAxis, $dt_buku_asal);
            } else {
                array_push($yAxis, 0);
            }
            $total += $dt_buku_asal;
        }
        $page_data['mxAxis'] = $xAxis;
        $page_data['myAxis'] = $yAxis;
        $page_data['mtotal'] = $total;
        $page_data['mtitle'] = 'Statistik Judul Buku Berdasarkan Asal Buku dengan Status ' . $nm;

        $xAxis = array();
        $yAxis = array();
        $total = 0;
        foreach ($bahasa as $b) {
            $pinjam = $this->Md_siperpus_data_buku->getBukuByStatusBahasa($status, $b->id, $page_data['tanggalawal'], $page_data['tanggalakhir']);
            array_push($xAxis, array('number', $b->nama));
            if ($pinjam > 0) {
                array_push($yAxis, $pinjam);
            } else {
                array_push($yAxis, 0);
            }
            $total += $pinjam;
        }
        $page_data['nxAxis'] = $xAxis;
        $page_data['nyAxis'] = $yAxis;
        $page_data['ntotal'] = $total;
        $page_data['ntitle'] = 'Statistik Judul Buku Berdasarkan Bahasa Buku dengan Status ' . $nm;

        $xAxis = array();
        $yAxis = array();
        $total = 0;
        $bukupinjam = $this->Md_siperpus_transaksi->getStatusBukuPinjamTerbanyak($status, 10, $page_data['tanggalawal'], $page_data['tanggalakhir']);
        if (count($bukupinjam) > 0) {
            foreach ($bukupinjam as $b) {
                array_push($xAxis, array('number', $b->judul));
                if ($b->total > 0) {
                    array_push($yAxis, $b->total);
                } else {
                    array_push($yAxis, 0);
                }
                $total += $b->total;
            }
        } else {
            array_push($xAxis, array('number', ''));
            array_push($yAxis, 0);
        }
        $page_data['qxAxis'] = $xAxis;
        $page_data['qyAxis'] = $yAxis;
        $page_data['qtotal'] = $total;
        $page_data['qtitle'] = 'Statistik Judul Buku Yang Paling Banyak Dipinjam dengan Status ' . $nm;

        $this->load->view('index', $page_data);
    }

    public function buku_tahun_judul() {
        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'statistik';
        $page_data['page_title'] = 'Statistik Buku Berdasarkan Tahun Terbit';
        $page_data['page_name'] = 'statistik';
        $page_data['page_action'] = 'statistik_buku';
        $page_data['page_file'] = 'statistik_buku_tahun_judul';
        $page_data['page_dir'] = 'statistik';
        $page_data['tanggalawal'] = $this->input->post('tanggalawal') ?? '';
        $page_data['tanggalakhir'] = $this->input->post('tanggalakhir') ?? '';

        $gettahun = $this->Md_siperpus_data_buku->getTahunAll();
        $page_data['gettahun'] = $gettahun;

        if (!$this->input->post('status'))
            $status = '';
        else
            $status = $this->input->post('status');
        $page_data['cur'] = $status;

        if (!$this->input->post('tmp'))
            $tmp = 'T';
        else
            $tmp = $this->input->post('tmp');
        $page_data['tmp'] = $tmp;

        if (!$this->input->post('tahun'))
            $tahun = '';
        else
            $tahun = $this->input->post('tahun');
        $page_data['tahun'] = $tahun;

        $nm = '';
        if ($status == '')
            $nm = 'Stok Aktif';
        if ($status == 'H')
            $nm = 'Hilang';
        if ($status == 'R')
            $nm = 'Rusak';
        if ($status == 'A')
            $nm = 'Diarsipkan';
        if ($status == 'L')
            $nm = 'Dilelang';

        $klas = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
        $kat = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
        $asal = $this->Md_siperpus_asal_buku->getAsalBukuAll();
        $bahasa = $this->Md_siperpus_bahasa->getBahasaAll();
        $mspst = $this->Md_vwprodi->getProdiAll();

        if ($tmp == 'T') {
            $xAxis = array();
            $yAxis = array();
            $total = 0;
            foreach ($gettahun as $k) {
                $thn = $this->Md_siperpus_data_buku->getBukuByThnJudul_Total($status, $k->thn_terbit, $page_data['tanggalawal'], $page_data['tanggalakhir']);

                array_push($xAxis, array('number', $k->thn_terbit));
                if ($thn > 0) {
                    array_push($yAxis, $thn);
                } else {
                    array_push($yAxis, 0);
                }
                $total += $thn;
            }
            $page_data['pxAxis'] = $xAxis;
            $page_data['pyAxis'] = $yAxis;
            $page_data['ptotal'] = $total;
            $page_data['ptitle'] = 'Statistik (Berdasar Judul Buku) Tahun Terbit Buku dengan Status Stok ' . $nm;
        } 
        else if ($tmp == 'Pen'){
            $xAxis = array();
            $yAxis = array();
            $total = 0;

            // Ambil data dari model (sudah berupa array berisi nama_penerbit dan total)
            $getPenerbit = $this->Md_siperpus_data_buku->getBukuByThnJudul_Penerbit($status,$tahun, $page_data['tanggalawal'], $page_data['tanggalakhir']);

            foreach ($getPenerbit as $p) {
                // Nama Penerbit sebagai label (X)
                array_push($xAxis, array('string', $p['nama_penerbit']));

                // Jumlah koleksi sebagai nilai (Y)
                $jml = (int)$p['total'];
                array_push($yAxis, $jml);

                $total += $jml;
            }

            $page_data['pxAxis'] = $xAxis;
            $page_data['pyAxis'] = $yAxis;
            $page_data['ptotal'] = $total;
            $page_data['ptitle'] = 'Statistik (Berdasar Judul Buku) Per Penerbit dengan Status Stok ' . $nm;
        }
        else {
            $xAxis = array();
            $yAxis = array();
            $total = 0;
            foreach ($klas as $k) {
                $invs = $this->Md_siperpus_data_buku->getBukuByThnJudul_Klas($status, $k->id, $tahun, $page_data['tanggalawal'], $page_data['tanggalakhir']);
                array_push($xAxis, array('number', $k->nama));
                if ($invs > 0) {
                    array_push($yAxis, $invs);
                } else {
                    array_push($yAxis, 0);
                }
                $total += $invs;
            }
            $page_data['pxAxis'] = $xAxis;
            $page_data['pyAxis'] = $yAxis;
            $page_data['ptotal'] = $total;
            $page_data['ptitle'] = 'Statistik (Berdasar Judul Buku) Tahun Terbit Buku Berdasarkan Klasifikasi Buku dengan Status Stok ' . $nm . ' (' . $tahun . ')';

            $xAxis = array();
            $yAxis = array();
            $total = 0;
            foreach ($kat as $k) {
                $invs = $this->Md_siperpus_data_buku->getBukuByThnJudul_Kat($status, $k->idkategori, $tahun, $page_data['tanggalawal'], $page_data['tanggalakhir']);
                array_push($xAxis, array('number', $k->nmkategori));
                if ($invs > 0) {
                    array_push($yAxis, $invs);
                } else {
                    array_push($yAxis, 0);
                }
                $total += $invs;
            }
            $page_data['kxAxis'] = $xAxis;
            $page_data['kyAxis'] = $yAxis;
            $page_data['ktotal'] = $total;
            $page_data['ktitle'] = 'Statistik (Berdasar Judul Buku) Tahun Terbit Buku Berdasarkan Kategori Buku dengan Status Stok ' . $nm . ' (' . $tahun . ')';

            $xAxis = array();
            $yAxis = array();
            $total = 0;
            foreach ($asal as $a) {
                $invs = $this->Md_siperpus_data_buku->getBukuByThnJudul_Asal($status, $a->id, $tahun, $page_data['tanggalawal'], $page_data['tanggalakhir']);
                array_push($xAxis, array('number', $a->nama));
                if ($invs > 0) {
                    array_push($yAxis, $invs);
                } else {
                    array_push($yAxis, 0);
                }
                $total += $invs;
            }
            $page_data['mxAxis'] = $xAxis;
            $page_data['myAxis'] = $yAxis;
            $page_data['mtotal'] = $total;
            $page_data['mtitle'] = 'Statistik (Berdasar Judul Buku) Tahun Terbit Buku Berdasarkan Asal Buku dengan Status Stok ' . $nm . ' (' . $tahun . ')';
        }
        
        $this->load->view('index', $page_data);
        
    }
    
    public function peminjaman() {
        // 1. Konfigurasi Halaman Utama
        $page_data = [
            'page_access' => "admin",
            'page_now' => 'statistik',
            'page_title' => 'Statistik Peminjaman Buku',
            'page_name' => 'statistik',
            'page_action' => 'statistik_peminjaman',
            'page_file' => 'statistik_peminjaman',
            'page_dir' => 'statistik',
            'list_prodi' => $this->Md_vwprodi->getProdiAll(),
            'pilihprodi' => $this->input->post('pilihprodi') ?? '',
            'tanggalawal' => $this->input->post('tanggalawal') ?? '',
            'tanggalakhir' => $this->input->post('tanggalakhir') ?? ''
        ];
        
        // 2. Normalisasi Tanggal
        $pertanggal = $this->input->post('pertanggal');
        $awal = $page_data['tanggalawal'];
        $akhir = $page_data['tanggalakhir'];
        if ($awal != '' && $akhir != '') {
            $awal = date('Y-m-d', strtotime($awal));
            $akhir = date('Y-m-d', strtotime($akhir));
        }
        
        // Validasi Rentang 31 Hari jika pertanggal dipilih
        if ($pertanggal && $awal && $akhir) {
            $diff = (strtotime($akhir) - strtotime($awal)) / (60 * 60 * 24);
            if ($diff > 31) {
               // Set pesan error yang hanya muncul sekali
                $this->session->set_flashdata('error_msg', 'Maksimal rentang adalah 31 hari untuk mode per tanggal.');

                // Redirect kembali ke halaman yang sama (GET request)
                // Ini akan menghilangkan pesan "Confirm Form Resubmission"
                redirect('dir/statistik/peminjaman'); 
                return;
            }
        }
    
        // 3. Chart 1: Berdasarkan Prodi
        $prodi_list = $this->Md_vwprodi->getProdiAll();
        // Tentukan list prodi yang akan diproses (Single atau All)
        $pilihprodi = $page_data['pilihprodi'];
        $suffix_prodi = ($pilihprodi != '') ? " [ $pilihprodi ]" : "";
        $list_to_process = ($pilihprodi != '') ? [(object)['nmmspst' => $pilihprodi]] : $prodi_list;

        //jika Checkbox muncul per tanggal dipilih
        if ($pertanggal && $awal && $akhir) {
            // 1. Persiapkan Periode Tanggal
            $period = new DatePeriod(
                new DateTime($awal),
                new DateInterval('P1D'),
                (new DateTime($akhir))->modify('+1 day')
            );

            $xAxis = [];
            $tempData = []; 
            $grandTotal = 0;

            // 2. EKSEKUSI QUERY
            foreach ($list_to_process as $p) {
                // PERBAIKAN: xAxis harus diisi baik single maupun all prodi
                $xAxis[] = ['number', $p->nmmspst];

                $results = $this->Md_siperpus_transaksi->getPeminjamanByKelasPerTanggal($p->nmmspst, $awal, $akhir);
                foreach ($results as $row) {
                    $tempData[$p->nmmspst][$row->tgl_pinjam] = (int)$row->total;
                }
            }

            // 3. SUSUN DATA UNTUK VIEW
            $chartDataPerTanggal = [];
            foreach ($period as $date) {
                $currDate = $date->format("Y-m-d");
                $rowValues = [];

                foreach ($list_to_process as $p) {
                    $count = $tempData[$p->nmmspst][$currDate] ?? 0;
                    $rowValues[] = $count;
                    $grandTotal += $count;
                }
                $chartDataPerTanggal[] = array_merge([$date->format("d M")], $rowValues);
            }

            $page_data['pxAxis'] = $xAxis;
            $page_data['pyAxis'] = $chartDataPerTanggal;
            $page_data['ptotal'] = $grandTotal;
            $page_data['is_mode_tanggal'] = true;
            $page_data['ptitle'] = 'Statistik Peminjaman Berdasarkan Prodi (Per Tanggal)'. $suffix_prodi;

        } else {
            // MODE STANDAR (AKUMULASI)
            $chart_prodi = $this->_generate_chart_data($list_to_process, 'nmmspst', function ($item) use ($awal, $akhir) {
                return ($awal != '' && $akhir != '') 
                    ? $this->Md_siperpus_transaksi->getPeminjamanByKelas_tgl($item->nmmspst, $awal, $akhir) 
                    : $this->Md_siperpus_transaksi->getPeminjamanByKelas($item->nmmspst);
            });

            $page_data['pxAxis'] = $chart_prodi['xAxis'];
            $page_data['pyAxis'] = $chart_prodi['yAxis'];
            $page_data['ptotal'] = $chart_prodi['total'];
            $page_data['is_mode_tanggal'] = false;
            $page_data['ptitle'] = 'Statistik Peminjaman Berdasarkan Prodi' . $suffix_prodi;
        }
        
        // 4. Chart 2: Berdasarkan Klasifikasi
        $klas_list = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
        
        if ($pertanggal && $awal && $akhir) {
            // 1. Gunakan period yang sudah dibuat di Chart 1
            $kxAxis = [];
            $kTempData = [];
            $kGrandTotal = 0;

            // 2. EKSEKUSI QUERY (1 Query per Klasifikasi)
            foreach ($klas_list as $k) {
                $kxAxis[] = ['number', $k->nama];

                // Pastikan $k->id (atau kolom kode klasifikasi Anda) dikirim ke sini
                $results = $this->Md_siperpus_transaksi->getPeminjamanByKlasifikasiPerTanggal($k->id, $awal, $akhir, $pilihprodi);

                foreach ($results as $row) {
                    $kTempData[$k->nama][$row->tgl_pinjam] = (int)$row->total;
                }
            }

            // 3. SUSUN DATA UNTUK VIEW
            $kChartDataPerTanggal = [];
            foreach ($period as $date) {
                $currDate = $date->format("Y-m-d");
                $kRowValues = [];

                foreach ($klas_list as $k) {
                    $kCount = $kTempData[$k->nama][$currDate] ?? 0;
                    $kRowValues[] = $kCount;
                    $kGrandTotal += $kCount;
                }
                $kChartDataPerTanggal[] = array_merge([$date->format("d M")], $kRowValues);
            }

            $page_data['kxAxis'] = $kxAxis;
            $page_data['kyAxis'] = $kChartDataPerTanggal;
            $page_data['ktotal'] = $kGrandTotal;
            $page_data['is_mode_tanggal_klas'] = true; // Flag khusus chart 2
            $page_data['ktitle'] = 'Statistik Peminjaman Berdasarkan Klasifikasi (Per Tanggal) '. $suffix_prodi;
        } else {
            // MODE STANDAR (AKUMULASI)
            $chart_klas = $this->_generate_chart_data($klas_list, 'nama', function ($item) use ($awal, $akhir, $pilihprodi) {
                // Update: Pastikan fungsi akumulasi di model juga mendukung filter prodi jika diperlukan
                return ($awal != '' && $akhir != '') 
                    ? $this->Md_siperpus_transaksi->getPeminjamanByKlasifikasi_tgl($item->id, $awal, $akhir, $pilihprodi) 
                    : $this->Md_siperpus_transaksi->getPeminjamanByKlasifikasi($item->id, $pilihprodi);
            });

            $page_data['kxAxis'] = $chart_klas['xAxis'];
            $page_data['kyAxis'] = $chart_klas['yAxis'];
            $page_data['ktotal'] = $chart_klas['total'];
            $page_data['is_mode_tanggal_klas'] = false;
            $page_data['ktitle'] = 'Statistik Peminjaman Berdasarkan Klasifikasi Buku '. $suffix_prodi;
        }

        // 5. Chart 3: Mahasiswa Aktif (Pertimbangkan Filter Prodi)
        $mhs_list = ($awal != '' && $akhir != '') 
                    ? $this->Md_siperpus_transaksi->getAnggotaPinjamTerbanyak_tgl($awal, $akhir, $pilihprodi) 
                    : $this->Md_siperpus_transaksi->getAnggotaPinjamTerbanyak($pilihprodi);

        $chart_mhs = $this->_generate_chart_data($mhs_list, 'nama', null, true);

        $page_data['mxAxis'] = $chart_mhs['xAxis'];
        $page_data['myAxis'] = $chart_mhs['yAxis'];
        $page_data['mtotal'] = $chart_mhs['total'];
        $page_data['mtitle'] = 'Statistik 10 mahasiswa Aktif Terbanyak Peminjaman Buku '. $suffix_prodi;

        // 6. Chart 4 & 5: Tahunan (Update dengan Filter Prodi)
        $years = ['now' => date('Y'), 'before' => date('Y') - 1];
        $prefixes = ['now' => 'n', 'before' => 'b'];

        foreach ($years as $key => $year) {
            // Tambahkan parameter $pilihprodi di sini
            $data_mhs = $this->Md_siperpus_transaksi->getAnggotaPinjamTerbanyakTahun($year, $pilihprodi);
            $res = $this->_generate_chart_data($data_mhs, 'nama', null, true);

            $p = $prefixes[$key];
            $page_data["{$p}xAxis"] = $res['xAxis'];
            $page_data["{$p}yAxis"] = $res['yAxis'];
            $page_data["{$p}total"] = $res['total'];
            $page_data["{$p}title"] = "Statistik 10 mahasiswa Aktif Terbanyak Peminjaman Buku Tahun $year ". $suffix_prodi;
        }
        
        // 7. Chart 6: Berdasarkan Kategori Buku
        $kat_list = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
        if ($pertanggal && $awal && $akhir) {
            $gaxAxis = [];
            $gTempData = [];
            $gGrandTotal = 0;

            foreach ($kat_list as $kat) {
                $gaxAxis[] = ['number', $kat->nmkategori]; // Sesuaikan nama kolom di tabel kategori
                $results = $this->Md_siperpus_transaksi->getPeminjamanByKategoriPerTanggal($kat->idkategori, $awal, $akhir, $pilihprodi);

                foreach ($results as $row) {
                    $gTempData[$kat->nmkategori][$row->tgl_pinjam] = (int)$row->total;
                }
            }

            $gChartDataPerTanggal = [];
            foreach ($period as $date) {
                $currDate = $date->format("Y-m-d");
                $gRowValues = [];
                foreach ($kat_list as $kat) {
                    $count = $gTempData[$kat->nmkategori][$currDate] ?? 0;
                    $gRowValues[] = $count;
                    $gGrandTotal += $count;
                }
                $gChartDataPerTanggal[] = array_merge([$date->format("d M")], $gRowValues);
            }

            $page_data['gaxAxis'] = $gaxAxis;
            $page_data['gyAxis'] = $gChartDataPerTanggal;
            $page_data['gtotal'] = $gGrandTotal;
            $page_data['is_mode_tanggal_kat'] = true;
            $page_data['gtitle'] = 'Statistik Peminjaman Berdasarkan Kategori Buku '. $suffix_prodi;
        } else {
            $chart_kat = $this->_generate_chart_data($kat_list, 'nmkategori', function ($item) use ($awal, $akhir, $pilihprodi) {
                return ($awal != '' && $akhir != '') 
                    ? $this->Md_siperpus_transaksi->getPeminjamanByKategori_tgl($item->idkategori, $awal, $akhir, $pilihprodi) 
                    : $this->Md_siperpus_transaksi->getPeminjamanByKategori($item->idkategori, $pilihprodi);
            });

            $page_data['gaxAxis'] = $chart_kat['xAxis'];
            $page_data['gyAxis'] = $chart_kat['yAxis'];
            $page_data['gtotal'] = $chart_kat['total'];
            $page_data['is_mode_tanggal_kat'] = false;
            $page_data['gtitle'] = 'Statistik Peminjaman Berdasarkan Kategori Buku '. $suffix_prodi;
        }
        
        // 8. Chart 7: Judul Buku Terpopuler
        if ($pertanggal && $awal && $akhir) {
            $dataBuku = $this->Md_siperpus_transaksi->getPeminjamanByBukuPerTanggal($awal, $akhir, $pilihprodi);

            $bxAxis = [];
            $bTempData = [];
            $bGrandTotal = 0;
            $top_titles = $dataBuku['top_list'] ?? [];

            foreach ($top_titles as $title) {
                $bxAxis[] = ['number', $title];
                foreach ($dataBuku['results'] as $row) {
                    if ($row->judul == $title) {
                        $bTempData[$title][$row->tgl_pinjam] = (int)$row->total;
                    }
                }
            }

            $bChartDataPerTanggal = [];
            foreach ($period as $date) {
                $currDate = $date->format("Y-m-d");
                $bRowValues = [];
                foreach ($top_titles as $title) {
                    $count = $bTempData[$title][$currDate] ?? 0;
                    $bRowValues[] = $count;
                    $bGrandTotal += $count;
                }
                $bChartDataPerTanggal[] = array_merge([$date->format("d M")], $bRowValues);
            }

            $page_data['baxAxis'] = $bxAxis;
            $page_data['bayAxis'] = $bChartDataPerTanggal;
            $page_data['batotal'] = $bGrandTotal;
            $page_data['is_mode_tanggal_buku'] = true;
            $page_data['batitle'] = 'Top 10 Buku Paling Banyak Dipinjam '. $suffix_prodi;
        } else {
            $top_buku = $this->Md_siperpus_transaksi->getPeminjamanTerpopuler($awal, $akhir, $pilihprodi);

            $xAxis = []; 
            $yValues = []; // Array flat
            $total = 0;

            foreach ($top_buku as $tb) {
                $xAxis[] = ['number', $tb->judul];
                $yValues[] = (int)$tb->total; // Masukkan angka saja
                $total += $tb->total;
            }

            $page_data['baxAxis'] = $xAxis;
            $page_data['bayAxis'] = $yValues; // JANGAN dibungkus array lagi
            $page_data['batotal'] = $total;
            $page_data['is_mode_tanggal_buku'] = false;
            $page_data['batitle'] = 'Top 10 Buku Paling Banyak Dipinjam '. $suffix_prodi;
        }
        
        // 9. Chart 8 : Pegawai Terbanyak Peminjaman Buku
        $pegawai_list = ($awal != '' && $akhir != '')
            ? $this->Md_siperpus_transaksi->getPegawaiPinjamTerbanyak_tgl($awal, $akhir)
            : $this->Md_siperpus_transaksi->getPegawaiPinjamTerbanyak();
        
        $chart_pegawai = $this->_generate_chart_data($pegawai_list,'nama',null,true);
        //var_dump($chart_pegawai);die;
        $page_data['pgxAxis'] = $chart_pegawai['xAxis'];
        $page_data['pgyAxis'] = $chart_pegawai['yAxis'];
        $page_data['pgtotal'] = $chart_pegawai['total'];
        $page_data['pgtitle'] = 'Statistik 10 Pegawai Terbanyak Peminjaman Buku';

        $this->load->view('index', $page_data);
    }

    /**
     * Helper function untuk merapikan data chart
     */
    private function _generate_chart_data($list, $label_field, $callback = null, $is_direct_data = false) {
        $xAxis = [];
        $yAxis = [];
        $total = 0;
        if (empty($list)) {
            return ['xAxis' => [], 'yAxis' => [], 'total' => 0];
        }
        foreach ($list as $item) {
            $xAxis[] = ['number', $item->$label_field];

            // Jika data sudah ada di list (seperti data mahasiswa)
            if ($is_direct_data) {
                $count = (int) ($item->total ?? 0);
            } else {
                // Jika harus ambil data lagi (seperti prodi/klasifikasi)
                $peminjaman = $callback($item);
                $count = (int) ($peminjaman[0]->total ?? 0);
            }

            $yAxis[] = $count;
            $total += $count;
        }

        return ['xAxis' => $xAxis, 'yAxis' => $yAxis, 'total' => $total];
    }
    
    public function buku_prodi() {
        // 1. Konfigurasi Halaman Utama
        $page_data = [
            'page_access' => "admin",
            'page_now' => 'statistik',
            'page_title' => 'Statistik Koleksi Buku Prodi',
            'page_name' => 'statistik',
            'page_action' => 'statistik_buku_prodi',
            'page_file' => 'statistik_buku_prodi',
            'page_dir' => 'statistik',
            'list_klas' => $this->Md_siperpus_klasifikasi->getKlasifikasiAll(),
            'pilih_klas' => $this->input->post('pilih_klas') ?? '',
            'tanggalawal' => $this->input->post('tanggalawal') ?? '',
            'tanggalakhir' => $this->input->post('tanggalakhir') ?? ''
        ];
        
        $awal = $this->input->post('tanggalawal');
        $akhir = $this->input->post('tanggalakhir');
        $pilih_klas = $this->input->post('pilih_klas');
        
        // Logika untuk mencari Nama Klasifikasi berdasarkan ID ($pilih_klas)
        $nama_klas = "";
        if ($pilih_klas != '') {
            foreach ($page_data['list_klas'] as $k) {
                if ($k->id == $pilih_klas) {
                    $nama_klas = $k->nama; // Ambil kolom nama/deskripsi klasifikasi
                    break;
                }
            }
        }
    
        // Logika tambahan untuk judul dinamis
        $suffix = ($pilih_klas != '') ? " Klasifikasi $nama_klas" : "";

        // Data Statistik
        $data_raw = $this->Md_siperpus_data_buku->getStatistikBukuProdi($awal, $akhir, $pilih_klas);
        $total_riil = $this->Md_siperpus_data_buku->getTotalKoleksiRiil($awal, $akhir, $pilih_klas);

        $xAxis = []; $yJudul = []; $yEksemplar = [];
        foreach ($data_raw as $row) {
            $xAxis[] = ['string', $row->nmmspst];
            $yJudul[] = (int)$row->jml_judul;
            $yEksemplar[] = (int)$row->jml_eksemplar;
        }

        // Judul Chart 1 (Judul)
        $page_data['c1xAxis'] = $xAxis;
        $page_data['c1yAxis'] = $yJudul;
        $page_data['c1total'] = $total_riil['total_judul'];
        $page_data['c1title'] = 'Statistik Judul Buku Per Prodi' . $suffix;

        // Judul Chart 2 (Eksemplar)
        $page_data['c2xAxis'] = $xAxis;
        $page_data['c2yAxis'] = $yEksemplar;
        $page_data['c2total'] = $total_riil['total_eksemplar'];
        $page_data['c2title'] = 'Statistik Eksemplar Buku Per Prodi' . $suffix;

        $page_data['page_title'] = "Statistik Koleksi Buku Per Prodi";
        $this->load->view('index', $page_data);
    }
    
    public function presensi() {
        $awal = $this->input->post('tanggalawal');
        $akhir = $this->input->post('tanggalakhir');
        $pertanggal = $this->input->post('pertanggal');
        $awal_db = ($awal != '') ? date('Y-m-d', strtotime($awal)) : '';
        $akhir_db = ($akhir != '') ? date('Y-m-d', strtotime($akhir)) : '';

        // ===== DATA MAHASISWA (PRODI) =====
        $is_stacked = false;
        $daftar_prodi = [];
        $xAxis = [];
        $yAxis = [];

        // Validasi Rentang 31 Hari jika pertanggal dipilih
        if ($pertanggal && $awal && $akhir) {
            $diff = (strtotime($akhir) - strtotime($awal)) / (60 * 60 * 24);
            if ($diff > 31) {
                $this->session->set_flashdata('error_msg', 'Maksimal rentang adalah 31 hari untuk mode per tanggal.');
                redirect('dir/statistik/presensi');
                return;
            }
        }

        if ($pertanggal && $awal_db != '' && $akhir_db != '') {
            $raw_data = $this->Md_siperpus_presensi->getStatistikPresensiHarian($awal_db, $akhir_db);
            $matrix = [];
            foreach ($raw_data as $row) {
                $matrix[$row->tgl][$row->prodi] = (int) $row->total;
                if (!in_array($row->prodi, $daftar_prodi))
                    $daftar_prodi[] = $row->prodi;
            }
            foreach ($daftar_prodi as $p) {
                $xAxis[] = ['number', $p];
            }
            foreach ($matrix as $tgl => $prodis) {
                $row_data = [$tgl];
                foreach ($daftar_prodi as $p) {
                    $row_data[] = isset($prodis[$p]) ? $prodis[$p] : 0;
                }
                $yAxis[] = $row_data;
            }
            $is_stacked = true;
            $title = "Tren Presensi Kunjungan Mahasiswa Per Prodi (Harian)";
        } else {
            $raw_data = $this->Md_siperpus_presensi->getStatistikPresensiProdi($awal_db, $akhir_db);
            foreach ($raw_data as $row) {
                $xAxis[] = ['string', $row->label];
                $yAxis[] = (int) $row->total;
            }
            $is_stacked = false;
            $title = "Total Presensi Kunjungan Mahasiswa Per Program Studi";
            if ($awal != '')
                $title .= " (Periode: $awal - $akhir)";
        }

        $total = 0;
        foreach ($raw_data as $r) {
            $total += $r->total;
        }

        // ===== DATA PEGAWAI =====
        $is_stacked_pegawai = false;
        $daftar_pegawai = [];
        $xAxis_pegawai = [];
        $yAxis_pegawai = [];

        if ($pertanggal && $awal_db != '' && $akhir_db != '') {
            $raw_data_pegawai = $this->Md_siperpus_presensi->getStatistikPresensiHarianPegawai($awal_db, $akhir_db);
            $matrix_pegawai = [];
            foreach ($raw_data_pegawai as $row) {
                $matrix_pegawai[$row->tgl][$row->pegawai] = (int) $row->total;
                if (!in_array($row->pegawai, $daftar_pegawai))
                    $daftar_pegawai[] = $row->pegawai;
            }
            foreach ($daftar_pegawai as $p) {
                $xAxis_pegawai[] = ['number', $p];
            }
            foreach ($matrix_pegawai as $tgl => $pegawais) {
                $row_data = [$tgl];
                foreach ($daftar_pegawai as $p) {
                    $row_data[] = isset($pegawais[$p]) ? $pegawais[$p] : 0;
                }
                $yAxis_pegawai[] = $row_data;
            }
            $is_stacked_pegawai = true;
            $title_pegawai = "Tren Presensi Kunjungan Per Pegawai (Harian)";
        } else {
            $raw_data_pegawai = $this->Md_siperpus_presensi->getStatistikPresensiPegawai($awal_db, $akhir_db);
            foreach ($raw_data_pegawai as $row) {
                $xAxis_pegawai[] = ['string', $row->label];
                $yAxis_pegawai[] = (int) $row->total;
            }
            $is_stacked_pegawai = false;
            $title_pegawai = "Total Presensi Kunjungan Per Pegawai";
            if ($awal != '')
                $title_pegawai .= " (Periode: $awal - $akhir)";
        }

        $total_pegawai = 0;
        foreach ($raw_data_pegawai as $r) {
            $total_pegawai += $r->total;
        }

        $page_data = [
            'page_access' => "admin",
            'page_now' => 'statistik',
            'page_title' => 'Statistik Presensi Kunjungan',
            'page_name' => 'statistik',
            'page_action' => 'statistik_presensi',
            'page_file' => 'statistik_presensi',
            'page_dir' => 'statistik',
            'tanggalawal' => $awal,
            'tanggalakhir' => $akhir,
            'pertanggal' => $pertanggal,
            // Mahasiswa
            'xAxis' => $xAxis,
            'yAxis' => $yAxis,
            'total' => $total,
            'chart_title' => $title,
            'is_stacked' => $is_stacked,
            'daftar_prodi' => $daftar_prodi,
            // Pegawai
            'xAxis_pegawai' => $xAxis_pegawai,
            'yAxis_pegawai' => $yAxis_pegawai,
            'total_pegawai' => $total_pegawai,
            'chart_title_pegawai' => $title_pegawai,
            'is_stacked_pegawai' => $is_stacked_pegawai,
            'daftar_pegawai' => $daftar_pegawai
        ];
        $this->load->view('index', $page_data);
    }
    
    public function kunjungan_baca_buku() {
        $this->load->model('Md_view_filebuku');

        $awal = $this->input->post('tanggalawal');
        $akhir = $this->input->post('tanggalakhir');
        $pertanggal = $this->input->post('pertanggal');

        $awal_db = ($awal != '') ? date('Y-m-d', strtotime($awal)) : '';
        $akhir_db = ($akhir != '') ? date('Y-m-d', strtotime($akhir)) : '';

        // ================= VALIDASI 31 HARI =================
        if ($pertanggal && $awal && $akhir) {
            $diff = (strtotime($akhir) - strtotime($awal)) / (60 * 60 * 24);

            if ($diff > 31) {
                $this->session->set_flashdata('error_msg','Maksimal rentang adalah 31 hari untuk mode per tanggal.');
                redirect('dir/statistik/kunjungan_baca_buku');
                return;
            }
        }

        $page_data['tanggalawal'] = $awal;
        $page_data['tanggalakhir'] = $akhir;
        $page_data['pertanggal'] = $pertanggal;

        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'statistik';
        $page_data['page_title'] = 'Statistik Kunjungan Baca Buku';
        $page_data['page_name'] = 'statistik';
        $page_data['page_action'] = 'kunjungan_baca_buku';
        $page_data['page_file'] = 'statistik_kunjungan_baca_buku';
        $page_data['page_dir'] = 'statistik';

        $total = 0;
        $xAxis = [];
        $yAxis = [];

        $is_stacked = false;
        $daftar_kategori = [];

        // ================= MODE PER TANGGAL =================
        if ($pertanggal && $awal_db != '' && $akhir_db != '') {
            $raw_data = $this->Md_view_filebuku->getStatistikKunjunganHarian($awal_db, $akhir_db);
            $matrix = [];

            foreach ($raw_data as $row) {
                $matrix[$row->tgl][$row->kategori] = (int) $row->total;
                if (!in_array($row->kategori, $daftar_kategori)) {
                    $daftar_kategori[] = $row->kategori;
                }
            }

            foreach ($daftar_kategori as $k) {
                $xAxis[] = ['number', $k];
            }

            foreach ($matrix as $tgl => $kategori) {
                $row_data = [$tgl];
                foreach ($daftar_kategori as $k) {
                    $row_data[] = isset($kategori[$k]) ? $kategori[$k] : 0;
                }
                $yAxis[] = $row_data;
            }

            foreach ($raw_data as $r) {
                $total += $r->total;
            }

            $chart_title = "Tren Kunjungan Baca Buku Per Kategori (Harian)";
            $is_stacked = true;
        } else {

            // ================= MODE AKUMULASI =================
            $data = $this->Md_view_filebuku->getDataByTgl($awal_db, $akhir_db);

            if ($data) {
                 foreach ($data as $p) {
                    // SKIP jika total 0
                    if ((int)$p->total <= 0) {
                        continue;
                    }

                    $xAxis[] = ['string', $p->nmkategori];
                    $yAxis[] = (int)$p->total;
                    $total += $p->total;
                }
            }

            $chart_title = "Statistik Kunjungan Baca Buku";

            if ($awal != '' && $akhir != '') {
                $chart_title .= " (Periode: $awal - $akhir)";
            }
        }

        // ================= TOP BUKU =================
        $limit = 30;
        $xAxis2 = [];
        $yAxis2 = [];
        $total2 = 0;

        $data2 = $this->Md_view_filebuku->getTopDataByLimit($limit);

        if ($data2) {
            foreach ($data2 as $p) {
                $xAxis2[] = ['number', $p->judul];
                $yAxis2[] = ($p->total > 0) ? (int) $p->total : 0;
                $total2 += $p->total;
            }
        }

        $page_data['xAxis'] = $xAxis;
        $page_data['yAxis'] = $yAxis;
        $page_data['total'] = $total;

        $page_data['chart_title'] = $chart_title;
        $page_data['is_stacked'] = $is_stacked;
        $page_data['daftar_kategori'] = $daftar_kategori;

        $page_data['xAxis2'] = $xAxis2;
        $page_data['yAxis2'] = $yAxis2;
        $page_data['total2'] = $total2;
        $page_data['top'] = $limit;

        $this->load->view('index', $page_data);
    }
    
    public function pengunjung_web(){
        $this->load->model('Md_visitors');
        
        $awal = $this->input->post('tanggalawal');
        $akhir = $this->input->post('tanggalakhir');
        if ($awal != '' && $akhir != '') {
            $awal = date('Y-m-d', strtotime($this->input->post('tanggalawal')));
            $akhir = date('Y-m-d', strtotime($this->input->post('tanggalakhir')));
        }
        $page_data['tanggalawal'] = $awal;
        $page_data['tanggalakhir'] = $akhir;

        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'statistik';
        $page_data['page_title'] = 'Statistik Pengunjung Web';
        $page_data['page_name'] = 'statistik';
        $page_data['page_action'] = 'pengunjung_web';
        $page_data['page_file'] = 'pengunjung_web';
        $page_data['page_dir'] = 'statistik';
        
        $total = 0;
        $xAxis = array();
        $yAxis = array();

        $data = $this->Md_visitors->getDataByTgl($awal, $akhir);

        if ($data) {
            foreach ($data as $p) {
                array_push($xAxis, array('number', $p->date));

                if ($p->total > 0) {
                    array_push($yAxis, $p->total);
                } else {
                    array_push($yAxis, 0);
                }
                $total += $p->total;
            }
        }

        $page_data['xAxis'] = $xAxis;
        $page_data['yAxis'] = $yAxis;
        $page_data['total'] = $total;
        
        $this->load->view('index', $page_data);
    }
    
    public function denda()
    {
        $awal = $this->input->post('tanggalawal');
        $akhir = $this->input->post('tanggalakhir');
        $pertanggal = $this->input->post('pertanggal');

        $awal_db = ($awal != '')
            ? date('Y-m-d', strtotime($awal))
            : '';

        $akhir_db = ($akhir != '')
            ? date('Y-m-d', strtotime($akhir))
            : '';

        // ================= VALIDASI 31 HARI =================

        if ($pertanggal && $awal && $akhir) {

            $diff =
                (strtotime($akhir) - strtotime($awal))
                / (60 * 60 * 24);

            if ($diff > 31) {

                $this->session->set_flashdata(
                    'error_msg',
                    'Maksimal rentang adalah 31 hari untuk mode per tanggal.'
                );

                redirect('dir/statistik/denda');

                return;
            }
        }

        $page_data['tanggalawal'] = $awal;
        $page_data['tanggalakhir'] = $akhir;
        $page_data['pertanggal'] = $pertanggal;

        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'statistik';
        $page_data['page_title'] = 'Statistik Denda Peminjaman';
        $page_data['page_name'] = 'denda';
        $page_data['page_action'] = 'denda';
        $page_data['page_file'] = 'denda';
        $page_data['page_dir'] = 'statistik';

        $total = 0;
        $xAxis = [];
        $yAxis = [];

        $is_stacked = false;
        $daftar_prodi = [];

        // ================= MODE STACKED =================

        if ($pertanggal && $awal_db != '' && $akhir_db != '') {

            $raw_data =
                $this->Md_siperpus_transaksi
                ->getStatistikDendaHarian(
                    $awal_db,
                    $akhir_db
                );

            $matrix = [];

            foreach ($raw_data as $row) {

                $matrix[$row->tgl][$row->prodi] =
                    (int)$row->denda;

                if (!in_array($row->prodi, $daftar_prodi)) {
                    $daftar_prodi[] = $row->prodi;
                }

                $total += $row->denda;
            }

            foreach ($daftar_prodi as $p) {
                $xAxis[] = ['number', $p];
            }

            foreach ($matrix as $tgl => $prodis) {

                $row_data = [$tgl];

                foreach ($daftar_prodi as $p) {

                    $row_data[] =
                        isset($prodis[$p])
                        ? $prodis[$p]
                        : 0;
                }

                $yAxis[] = $row_data;
            }

            $chart_title =
                "Tren Denda Per Program Studi (Harian)";

            $is_stacked = true;

        } else {

            // ================= MODE NORMAL =================

            $mspst =
                $this->Md_vwprodi->getProdiAll();

            foreach ($mspst as $m) {

                if ($awal_db && $akhir_db) {

                    $denda =
                        $this->Md_siperpus_transaksi
                        ->getDendaByKelas_tgl(
                            $m->nmmspst,
                            $awal_db,
                            $akhir_db
                        );

                } else {

                    $denda =
                        $this->Md_siperpus_transaksi
                        ->getDendaByKelas(
                            $m->nmmspst
                        );
                }

                $nilai =
                    isset($denda[0]->denda)
                    ? (int)$denda[0]->denda
                    : 0;

                // skip jika 0
                if ($nilai <= 0) {
                    continue;
                }

                $xAxis[] = ['string', $m->nmmspst];

                $yAxis[] = $nilai;

                $total += $nilai;
            }

            $chart_title =
                "Statistik Denda Peminjaman";

            if ($awal != '' && $akhir != '') {

                $chart_title .=
                    " (Periode: $awal - $akhir)";
            }
        }

        $page_data['xAxis'] = $xAxis;
        $page_data['yAxis'] = $yAxis;
        $page_data['total'] = $total;

        $page_data['chart_title'] = $chart_title;
        $page_data['is_stacked'] = $is_stacked;
        $page_data['daftar_prodi'] = $daftar_prodi;

        $this->load->view('index', $page_data);
    }
    
}
