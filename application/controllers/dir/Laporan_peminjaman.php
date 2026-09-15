<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Laporan_peminjaman extends CI_Controller {

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
        session_write_close(); // Optimasi performa untuk data besar
        $page_data = [
            'page_action'  => 'list',
            'page_title'   => 'Daftar Peminjaman',
            'page_access'  => 'admin',
            'page_name'    => 'laporan_peminjaman',
            'page_now'     => 'Laporan Peminjaman Buku',
            'page_dir'     => 'laporan_peminjaman',
            'page_file'     => 'index',
            'prodi'        => $this->Md_vwprodi->getProdiAll(),
            // Inisialisasi filter default
            'src'          => $this->input->post('src') ?? '',
            'tanggalawal'  => $this->input->post('tanggalawal') ?? '',
            'tanggalakhir' => $this->input->post('tanggalakhir') ?? '',
            'jenis'        => $this->input->post('pilih') ?? 'mahasiswa',
            'pilihprodi'   => $this->input->post('pilihprodi') ?? 'All',
            'jenispeminjaman' => $this->input->post('jenispeminjaman') ?? 'All'
        ];

        $this->load->view('index', $page_data);
        
    }
    
    /**
     * Mengambil data untuk Datatables (AJAX)
     */
    public function fetch() {
        $dt = $this->input->post('datatable');
        $jenis = $dt['query']['jenis'] ?? 'mahasiswa';

        $total   = $this->Md_laporan_peminjaman->countFiltered();
        $page    = max(1, (int)($dt['pagination']['page'] ?? 1));
        $perpage = (int)($dt['pagination']['perpage'] ?? 10);
        $field   = $dt['sort']['field'] ?? ($dt['pagination']['field'] ?? 'tanggal');
        $sort    = $dt['sort']['sort'] ?? ($dt['pagination']['sort'] ?? 'desc');

        $list = $this->Md_laporan_peminjaman->getDatatables();
        $data = [];

        foreach ($list as $key => $row) {
            $data[] = [
                'number'  => ($perpage * ($page - 1)) + ($key + 1),
                'nomor'   => $row->no_anggota,
                'nama'    => $row->nama,
                'prodi'   => (in_array($jenis, ['dosen', 'anggota+luar'])) ? '' : ($row->kelas ?? ''),
                'noinv'   => $row->no_inv,
                'barcode' => $row->no_barcode,
                'judul'   => $row->judul,
                'tanggal' => $row->tgl_pinjam,
                'mandiri' => $row->is_mandiri != NULL ? $row->is_mandiri : '-',
                'aksi' => ($row->is_mandiri == 'Ya')
                    ? '<a href="'.base_url('dir/laporan_peminjaman/cetak_bukti/'.urlencode(base64_encode($row->tid))).'"
                         target="_blank"
                         class="btn btn-sm btn-info">
                         <i class="fa fa-print"></i> Cetak Bukti
                       </a>'
                    : '',
                'batas'   => $row->batas
            ];
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                "meta" => [
                    "page"    => $page,
                    "pages"   => ceil($total / $perpage),
                    "perpage" => $perpage,
                    "total"   => (int)$total,
                    "sort"    => $sort,
                    "field"   => $field,
                ],
                "data" => $data
            ]));
    }

    /**
     * Export data ke Excel atau Web View
     */
    public function export($format = 'excel') {
        // Gunakan prefix dinamis untuk efisiensi
        $prefix = ($format == 'excel') ? 'e' : 'w';
        
        $jenis        = $this->input->post($prefix . 'pilih');
        $tanggalawal  = $this->input->post($prefix . 'tanggalawal');
        $tanggalakhir = $this->input->post($prefix . 'tanggalakhir');
        $search       = $this->input->post($prefix . 'search');
        $p_prodi      = $this->input->post($prefix . 'prodi');
        $jenis_peminjaman = $this->input->post($prefix . 'jenispeminjaman');

        $page_data = [
            'laporan'    => 'laporan_peminjaman',
            'export'     => $format,
            'page_title' => 'Daftar Peminjaman',
            'page_dir'   => 'laporan_peminjaman',
            'page_file'  => 'export',
            'title'      => 'Daftar ' . ucfirst($jenis),
            'jenis'      => $jenis,
            'data'       => $this->Md_laporan_peminjaman->getLaporan($jenis, $tanggalawal, $tanggalakhir, $search, $p_prodi, $jenis_peminjaman)
        ];
        
        $this->load->view('pages/'.$page_data['page_dir'].'/'.$page_data['page_file'], $page_data);
    }

    public function cetak_bukti($tid)
    {
        $tid =  base64_decode(urldecode($tid));
        $data = $this->Md_laporan_peminjaman->getTransaksiMandiri($tid);

        if (!$data) {
            show_error('Data peminjaman tidak ditemukan', 404);
        }

        $detail = $this->Md_laporan_peminjaman
            ->getPeminjamanMandiriByTanggal(
                $data->no_anggota,
                $data->tgl_pinjam
            );

        $page_data['siswa']      = $data;
        $page_data['tgl_pinjam'] = $data->tgl_pinjam;
        $page_data['peminjaman'] = $detail;
        $page_data['title']      = 'Bukti Peminjaman Mandiri';

        $html = $this->load->view(
            'pages/laporan_peminjaman/cetak_bukti_mandiri',
            $page_data,
            TRUE
        );

        $mpdf = new \Mpdf\Mpdf([
            'mode'          => 'utf-8',
            'format'        => 'A4',
            'margin_left'   => 15,
            'margin_right'  => 15,
            'margin_top'    => 38,
            'margin_bottom' => 20,
            'margin_header' => 5,
            'margin_footer' => 10,
            'default_font'  => 'dejavusans'
        ]);

        $kop_path = FCPATH . 'assets/media/Kop PKR New.png';

        if (file_exists($kop_path)) {

            $mpdf->SetHTMLHeader('
                <div style="text-align:center;">
                    <img src="' . $kop_path . '" 
                         style="max-width:100%;height:auto;">
                </div>
            ');

        } else {

            $mpdf->SetHTMLHeader('
                <div style="
                    text-align:center;
                    font-weight:bold;
                    border-bottom:1px solid #000;
                    padding-bottom:5px;
                ">
                    BUKTI PEMINJAMAN BUKU MANDIRI
                </div>
            ');
        }

        /*$mpdf->SetHTMLFooter('
            <div style="
                text-align:center;
                font-size:10px;
                color:#666;
                border-top:1px solid #ddd;
                padding-top:5px;
            ">
                Halaman {PAGENO} dari {nb}
                | Dicetak pada '.date('d F Y H:i:s').'
            </div>
        ');*/

        $mpdf->WriteHTML($html);

        $filename =
            'Bukti_Peminjaman_Mandiri_' .
            $data->no_anggota .
            '_' .
            date('YmdHis') .
            '.pdf';

        $mpdf->Output($filename, 'I');
        exit;
    }


}
