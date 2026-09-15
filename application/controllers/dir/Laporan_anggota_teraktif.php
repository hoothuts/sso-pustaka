<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_anggota_teraktif extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->database();
        $this->load->model('Md_laporan_peminjaman');
        $this->load->model('Md_vwprodi');
        $this->load->helper('pkrlib_helper');

        if ($this->session->userdata('login_type') != 'admin') {
            logoutNow();
        }
    }

    public function index() {
        $page_data = [
            'page_title'   => 'Laporan Anggota Teraktif',
            'page_now'     => 'Anggota Paling Banyak Melakukan Peminjaman',
            'page_name'    => 'laporan_anggota_teraktif',
            'page_file'    => 'index',
            'page_dir'     => 'laporan_anggota_teraktif',
            'tanggalawal'  => $this->input->post('tanggalawal') ?? '',
            'tanggalakhir' => $this->input->post('tanggalakhir') ?? '',
            'pilihprodi'   => $this->input->post('pilihprodi') ?? '',
            'prodi'        => $this->Md_vwprodi->getProdiAll(),
            'jenis'        => $this->input->post('pilih') ?? 'mahasiswa',
            'pilihprodi'   => $this->input->post('pilihprodi') ?? 'All'
        ];
        $this->load->view('index', $page_data);
    }

    public function fetch() {
        $datatable = $this->input->post('datatable');
        $page = isset($datatable['pagination']['page']) ? (int) $datatable['pagination']['page'] : 1;
        $perpage = isset($datatable['pagination']['perpage']) ? (int) $datatable['pagination']['perpage'] : 10;
        $sort = isset($datatable['sort']['sort']) ? $datatable['sort']['sort'] : 'DESC';
        $field = isset($datatable['sort']['field']) ? $datatable['sort']['field'] : 'total_pinjam';

        $offset = ($page - 1) * $perpage;

        $list = $this->Md_laporan_peminjaman->getAnggotaTeraktif();
        $total = $this->Md_laporan_peminjaman->countFilteredAnggotaTeraktif();
        $data = [];
        $no = $offset;
        foreach ($list as $row) {
            $no++;
            $data[] = [
                'number' => $no,
                'nama' => $row->nama,
                'no_anggota' => $row->no_anggota,
                //'jenis' => $row->jenis,
                'prodi' => $row->kelas,
                'total_pinjam' => $row->total_pinjam
            ];
        }

        $result = [
            "meta" => [
                "page" => $page,
                "pages" => ceil($total / $perpage),
                "perpage" => $perpage,
                "total" => $total,
                "sort" => $sort,
                "field" => $field
            ],
            "data" => $data
        ];

        echo json_encode($result);
        exit;
    }

    public function export($format) {
        $prefix = ($format == 'excel') ? 'e' : 'w';
        $jenis   = $this->input->post($prefix . 'pilih');
        $ta   = $this->input->post($prefix . 'tanggalawal');
        $tl   = $this->input->post($prefix . 'tanggalakhir');
        $prodi = $this->input->post($prefix . 'prodi');
        
        $page_data = [
            'export' => $format,
            'page_title' => 'Laporan Anggota Teraktif',
            'data' => $this->Md_laporan_peminjaman->getLaporanAnggotaTeraktif($jenis, $prodi, $ta, $tl)
        ];
        $this->load->view('pages/laporan_anggota_teraktif/export', $page_data);
    }
}