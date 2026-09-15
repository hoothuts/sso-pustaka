<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Laporan_inventaris extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->database();
        $this->load->model('Md_laporan_inventaris');
        $this->load->model('Md_lokasi');
        $this->load->model('Md_siperpus_klasifikasi');
        $this->load->model('Md_siperpus_kategori_buku');
        $this->load->model('Md_siperpus_data_buku');
        $this->load->model('Md_siperpus_asal_buku');
        $this->load->model('Md_vwprodi');
        $this->load->model('Md_log');
        $this->load->helper('pkrlib_helper');
        $this->load->helper('encryption_id_helper');

        if ($this->session->userdata('login_type') != 'admin') {
            logoutNow();
        }
    }

    public function index($action = '', $param2 = '') {

        session_write_close(); // Optimasi performa untuk data besar
        // 1. Persiapan Data Default (Options untuk Filter)
        $page_data = [
            'page_title' => 'Daftar Inventaris',
            'klasifikasi' => $this->Md_siperpus_klasifikasi->getKlasifikasiAll(),
            'kategori' => $this->Md_siperpus_kategori_buku->getKategoriBukuAll(),
            'tahun_terbit' => $this->Md_siperpus_data_buku->getTahunTerbitBuku(),
            'kampus' => $this->Md_lokasi->get_kampus_options(),
            'asal_buku' => $this->Md_siperpus_asal_buku->getAsalBukuAll(),
            'prodi' => $this->Md_vwprodi->getProdiAll(),
            // Default Value untuk Form Filter
            'src' => $this->input->post('src') ?? '',
            'klas' => $this->input->post('klasifikasi') ?? '',
            'ktg' => $this->input->post('kategori') ?? '',
            'tanggalawal' => $this->input->post('tanggalawal') ?? '',
            'tanggalakhir' => $this->input->post('tanggalakhir') ?? '',
            'thn_terbit' => $this->input->post('thn_terbit') ?? '',
            'asal_bk' => $this->input->post('asal') ?? '',
            'prodi_bk' => $this->input->post('prodi') ?? '',
        ];

        // 2. Logika Lokasi (Kampus, Gedung, Rak)
        $kampus_id = $this->input->post('m_form_kampus');
        $gedung_id = $this->input->post('m_form_gedung');

        if ($kampus_id) {
            $page_data['m_form_kampus'] = $kampus_id;
            $page_data['list_gedung'] = $this->Md_lokasi->get_gedung_by_kampus($kampus_id);
        }
        if ($gedung_id) {
            $page_data['m_form_gedung'] = $gedung_id;
            $page_data['list_rak'] = $this->Md_lokasi->get_rak_by_gedung($gedung_id);
        }
        $page_data['m_form_rak'] = $this->input->post('m_form_rak');

        // 3. Routing Action (Export, Fetch, atau Load View)
        switch ($action) {
            case 'export':
                $page_data['page_action'] = 'list';
                $page_data['page_title'] = 'Laporan Inventaris';
                $page_data['page_now'] = 'Laporan Inventaris Buku';
                $page_data['page_name'] = 'laporan_inventaris';
                $page_data['page_dir']    = 'laporan_inventaris';
                $page_data['page_file']   = 'export';
                
                $page_data['laporan'] = 'laporan_inventaris';
                $page_data['export'] = $param2;
                $page_data['title'] = 'Daftar Inventaris';
                $page_data['data'] = $this->Md_laporan_inventaris->getLaporanBuku();
                return $this->load->view('pages/'.$page_data['page_dir'].'/'.$page_data['page_file'], $page_data);

            case 'fetch':
                return $this->list();

            default:
                $page_data['page_action'] = 'list';
                $page_data['page_title'] = 'Laporan Inventaris';
                $page_data['page_now'] = 'Laporan Inventaris Buku';
                $page_data['page_dir']    = 'laporan_inventaris';
                $page_data['page_file']   = 'index';
        
                $page_data['page_access'] = "admin";
                $page_data['page_name'] = 'laporan_inventaris';
                $page_data['page_now'] = 'dashboard';
                return $this->load->view('index', $page_data);
        }
    }

    private function list() {
        $dt = $this->input->post('datatable');

        $total = $this->Md_laporan_inventaris->countFiltered();
        $page = max(1, (int) ($dt['pagination']['page'] ?? 1));
        $perpage = (int) ($dt['pagination']['perpage'] ?? 10);
        $field = $dt['sort']['field'] ?? ($dt['pagination']['field'] ?? 'tanggal');
        $sort = $dt['sort']['sort'] ?? ($dt['pagination']['sort'] ?? 'desc');

        $list = $this->Md_laporan_inventaris->getDatatables();
        $data = [];

        foreach ($list as $key => $row) {
            $data[] = [
                'number' => ($perpage * ($page - 1)) + ($key + 1),
                'penulis' => $row->penulis,
                'judul' => $row->judul,
                'edisi' => $row->edisi,
                'penerbit' => $row->penerbit,
                'tahun' => $row->thn_terbit,
                'ISBN' => $row->ISBN,
                'jumlah' => $row->jml_buku,
                'hr' => $row->hilang,
                'noklas' => $row->no_klas,
                'tanggal' => $row->tanggal,
                'nama_kampus' => $row->nama_kampus,
                'nama_gedung' => $row->nama_gedung,
                'nama_rak' => $row->nama_rak,
                'asal_buku' => $row->asal_buku,
                'prodi' => $row->nama_prodi,
                'cover' => $this->_render_cover($row->cover)
            ];
        }

        return $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode([
                            "meta" => [
                                "page" => $page,
                                "pages" => ceil($total / $perpage),
                                "perpage" => $perpage,
                                "total" => (int) $total,
                                "sort" => $sort,
                                "field" => $field,
                            ],
                            "data" => $data
        ]));
    }

    private function _render_cover($filename) {
        if (empty($filename))
            return "-";

        $path = base_url("uploads/covers/$filename");
        return "<span>
                <a href='$path' target='_blank'>
                    <img src='$path' alt='cover' style='max-width: 80px; max-height: 80px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;'>
                </a>
            </span>";
    }
}
