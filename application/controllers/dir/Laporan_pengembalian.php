<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Laporan_pengembalian extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->database();
        $this->load->model('Md_laporan_pengembalian');
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
            'page_title'   => 'Daftar Pengembalian',
            'page_access'  => 'admin',
            'page_name'    => 'laporan_pengembalian',
            'page_now'     => 'Laporan Pengembalian Buku',
            'page_dir'     => 'laporan_pengembalian',
            'page_file'     => 'index',
            'prodi'        => $this->Md_vwprodi->getProdiAll(),
            // Inisialisasi filter default
            'src'          => $this->input->post('src') ?? '',
            'tanggalawal'  => $this->input->post('tanggalawal') ?? '',
            'tanggalakhir' => $this->input->post('tanggalakhir') ?? '',
            'jenis'        => $this->input->post('pilih') ?? 'mahasiswa',
            'pilihprodi'   => $this->input->post('pilihprodi') ?? 'All',
            'jenispengembalian' => $this->input->post('jenispengembalian') ?? 'All'
        ];

        $this->load->view('index', $page_data);
        
    }
    
    /**
     * Mengambil data untuk Datatables (AJAX)
     */
    public function fetch() {
        $total = $this->Md_laporan_pengembalian->countFiltered();
        $page = intval($this->input->post('datatable[pagination][page]'));
        $page = $page < 1 ? 1 : $page;

        $perpage = intval($this->input->post('datatable[pagination][perpage]'));
        $pages = intval($total / $perpage);
        $field = $this->input->post('datatable[sort][field]') ?: $this->input->post('datatable[pagination][field]');
        $sort = $this->input->post('datatable[sort][sort]') ?: $this->input->post('datatable[pagination][sort]');

        //mulai fetching data
        $data = array();
        $no = 0;
        $list = $this->Md_laporan_pengembalian->getDatatables();
        foreach ($list as $row) {
            $no++;
            $jenis = $this->input->post('datatable[query][jenis]');
            $arr = [
                'number'   => ($perpage * ($page - 1)) + $no,
                'nomor'    => $row->no_anggota,
                'nama'     => $row->nama,
                'prodi'    => in_array($jenis, ['pegawai', 'anggota+luar']) ? '' : $row->kelas,
                'noinv'    => $row->no_inv,
                'no_barcode'    => $row->no_barcode,
                'judul'    => $row->judul,
                'tanggal'  => $row->tgl_kembali,
                'mandiri'  => $row->is_mandiri_pengembalian !== NULL ? $row->is_mandiri_pengembalian : '-',
                'aksi' => ($row->is_mandiri_pengembalian == 'Ya')
                    ? '<a href="' .
                        base_url('dir/laporan_pengembalian/cetak_bukti/' . $row->tid) .
                      '" target="_blank"
                         class="btn btn-sm btn-info">
                            <i class="fa fa-print"></i> Cetak Bukti
                       </a>'
                    : ''
            ];
            $data[] = $arr;
        }

        $meta = array();
        $meta['page'] = $page;
        $meta['pages'] = $pages;
        $meta['perpage'] = $perpage;
        $meta['total'] = $total;
        $meta['sort'] = $sort;
        $meta['field'] = $field;
        $output = array(
            "meta" => $meta,
            "data" => $data
        );
        //output to json format
        echo json_encode($output);
    }

    /**
     * Export data ke Excel atau Web View
     */
    public function export($format = 'excel') {
        // Gunakan prefix dinamis untuk efisiensi
        $jenis = 'anggota+luar';
        $tanggalawal = '';
        $tanggalakhir = '';
        $search = '';
        $p_prodi = 'All';

        if ($format == 'excel') {
            $jenis = $this->input->post('epilih');
            $tanggalawal = $this->input->post('etanggalawal');
            $tanggalakhir = $this->input->post('etanggalakhir');
            $search = $this->input->post('esearch');
            $p_prodi = $this->input->post('eprodi');
            $jenis_pengembalian = $this->input->post('ejenispengembalian');
        } elseif ($format == 'web') {
            $jenis = $this->input->post('wpilih');
            $tanggalawal = $this->input->post('wtanggalawal');
            $tanggalakhir = $this->input->post('wtanggalakhir');
            $search = $this->input->post('wsearch');
            $p_prodi = $this->input->post('wprodi');
            $jenis_pengembalian = $this->input->post('wjenispengembalian');
        }
        
        $page_data = [
            'laporan'    => 'laporan_pengembalian',
            'export'     => $format,
            'page_title' => 'Daftar Pengembalian',
            'page_dir'   => 'laporan_pengembalian',
            'page_file'  => 'export',
            'title'      => 'Daftar Pengembalian Buku' . ucfirst($jenis),
            'jenis'      => $jenis,
            'data'       => $this->Md_laporan_pengembalian->getLaporan($jenis, $tanggalawal, $tanggalakhir, $search, $p_prodi, $jenis_pengembalian)
        ];
        
        $this->load->view('pages/'.$page_data['page_dir'].'/'.$page_data['page_file'], $page_data);
    }

    public function cetak_bukti($tid)
    {
        $data = $this->Md_laporan_pengembalian
            ->getTransaksiPengembalianMandiri($tid);

        if (!$data) {
            show_error('Data pengembalian tidak ditemukan', 404);
        }

        $detail =
            $this->Md_laporan_pengembalian
                ->getPengembalianMandiriByTanggal(
                    $data->no_anggota,
                    $data->tgl_kembali
                );

        $page_data['siswa'] = $data;
        $page_data['tgl_pengembalian'] = $data->tgl_kembali;
        $page_data['peminjaman'] = $detail;
        $page_data['title'] = 'Bukti Pengembalian Mandiri';

        $html = $this->load->view(
            'pages/laporan_pengembalian/cetak_bukti_mandiri',
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
            'Bukti_Pengembalian_Mandiri_' .
            $data->no_anggota .
            '_' .
            date('YmdHis') .
            '.pdf';

        $mpdf->Output($filename, 'I');
        exit;
    }

}
