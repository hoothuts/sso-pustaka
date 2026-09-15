<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_buku_populer extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->database();
        $this->load->model('Md_laporan_peminjaman');
        $this->load->model('Md_vwprodi');
        $this->load->model('Md_siperpus_klasifikasi');
        $this->load->model('Md_siperpus_kategori_buku');
        $this->load->helper('pkrlib_helper');

        if ($this->session->userdata('login_type') != 'admin') {
            logoutNow();
        }
    }

    public function index() {
        $page_data = [
            'page_title'   => 'Buku Paling Banyak Dipinjam',
            'page_now'     => 'Laporan Peminjaman Buku',
            'page_name'    => 'laporan_buku_populer',
            'page_file'    => 'index',
            'page_dir'     => 'laporan_buku_populer',
            'tanggalawal'  => $this->input->post('tanggalawal') ?? '',
            'tanggalakhir' => $this->input->post('tanggalakhir') ?? '',
            'ktg'          => $this->input->post('ktg') ?? '',
            'klas'         => $this->input->post('klas') ?? '',
            'pilihprodi'   => $this->input->post('pilihprodi') ?? '',
            'prodi'        => $this->Md_vwprodi->getProdiAll(),
            'klasifikasi'  => $this->Md_siperpus_klasifikasi->getKlasifikasiAll(),
            'kategori'     => $this->Md_siperpus_kategori_buku->getKategoriBukuAll(),
            'jenis'        => $this->input->post('pilih') ?? 'mahasiswa',
            'pilihprodi'   => $this->input->post('pilihprodi') ?? 'All'
        ];
        $this->load->view('index', $page_data);
    }

    public function fetch() {
        $total   = $this->Md_laporan_peminjaman->countFilteredPopuler();
        $dt      = $this->input->post('datatable');
        $perpage = (int)($dt['pagination']['perpage'] ?? 10);
        $page    = (int)($dt['pagination']['page'] ?? 1);

        $list = $this->Md_laporan_peminjaman->getDatatablesPopuler();
        $data = [];
        foreach ($list as $key => $row) {
            $data[] = [
                'number' => ($perpage * ($page - 1)) + ($key + 1),
                'judul'  => $row->judul,
                'klas'   => $row->no_klas,
                'kategori'    => $row->kategori,
                'isbn'    => $row->ISBN,
                'thn_terbit'    => $row->thn_terbit,
                'total'  => $row->total_dipinjam . ' kali'
            ];
        }

        return $this->output->set_content_type('application/json')->set_output(json_encode([
            "meta" => ["page" => $page, 
            "pages" => ceil($total/$perpage), 
            "perpage" => $perpage, "total" => (int)$total],
            "data" => $data
        ]));
    }

    public function export($format = 'excel') {
        $prefix = ($format == 'excel') ? 'e' : 'w';
        $jenis   = $this->input->post($prefix . 'pilih');
        $ta   = $this->input->post($prefix . 'tanggalawal');
        $tl   = $this->input->post($prefix . 'tanggalakhir');
        $src  = $this->input->post($prefix . 'search');
        $kat  = $this->input->post($prefix . 'kategori');
        $klas = $this->input->post($prefix . 'klas');
        $prodi = $this->input->post($prefix . 'prodi');

        $page_data = [
            'export' => $format,
            'page_title' => 'Laporan Buku Populer',
            'data'   => $this->Md_laporan_peminjaman->getLaporanPopuler($jenis, $ta, $tl, $src, $kat, $klas, $prodi)
        ];
        $this->load->view('pages/laporan_buku_populer/export', $page_data);
    }
}