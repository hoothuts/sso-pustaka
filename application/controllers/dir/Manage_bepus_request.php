<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_bepus_request extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->database();
        $this->load->model('Md_bepus_request');
        $this->load->model('Md_bepus_syarat');
        $this->load->model('Md_bepus_hibah');
        $this->load->model('Md_siperpus_setting');
        $this->load->model('Md_vwsiswa');
        $this->load->model('Md_log');
        $this->load->helper('pkrlib_helper');
        $this->load->helper('encryption_id_helper');

        if ($this->session->userdata('login_type') != 'admin') {
            logoutNow();
        }
    }

    public function index() {
        $page_data['page_access'] = "admin";
        $page_data['page_now']    = 'Request Bebas Pustaka';
        $page_data['page_title']  = 'Manage Request Bebas Pustaka';
        $page_data['page_name']   = 'manage_bepus_request';
        $page_data['page_dir']    = 'manage_bepus_request';
        $page_data['page_file']   = 'index';
        
        // Ambil nilai filter saat ini (untuk mengisi select box setelah reload)
        $page_data['current_kelas'] = $this->input->post('kelas_filter');
        $page_data['current_tahun'] = $this->input->post('tahun_filter');
        
        $page_data['kelas_list'] = $this->get_kelas_options();
        $page_data['tahun_list'] = $this->Md_bepus_request->get_tahun_akademik();
        $this->load->view('index', $page_data);
    }

    // ====================== FETCH DATATABLE ======================
    public function fetch() {
        $datatable = $this->input->post('datatable');
        $search = isset($datatable['query']['generalSearch']) ? $datatable['query']['generalSearch'] : '';
        $page   = isset($datatable['pagination']['page']) ? (int)$datatable['pagination']['page'] : 1;
        $perpage = isset($datatable['pagination']['perpage']) ? (int)$datatable['pagination']['perpage'] : 10;

        $offset = ($page - 1) * $perpage;

        $list  = $this->Md_bepus_request->get_datatables($search, $perpage, $offset);
        $total = $this->Md_bepus_request->count_filtered($search);

        $data = [];
        $no = $offset;

        //ambil data kepala perpustakaan
        $id_kepala_perpustakaan = $this->Md_siperpus_setting->getSettingbyKode('userkepala'); 
        $is_kepala=0;
        if(isset($id_kepala_perpustakaan)){
            if($this->session->userdata('idsys') == $id_kepala_perpustakaan->valsetting){
                $is_kepala=1;
            }
        }
        
        foreach ($list as $row) {
            $no++;
            $kelengkapan = ($row->belum_lengkap_count == 0) ? 'Lengkap' : 'Belum Lengkap';

            $data[] = [
                'number'          => $no,
                'id_enc'          => encrypt($row->bepusrequest_id),
                'no_request'      => $row->no_request ?? '-',
                'tahun_akademik'             => $row->tahun_akademik,
                'nim'             => $row->nim,
                'kelas'           => $row->kelas ?? '-',
                'tahun_masuk'     => $row->tahun_masuk ?? '-',
                'nama_siswa'      => $row->nama ?? '-',
                'status_pengajuan'=> $row->status_pengajuan,
                'kelengkapan'     => $kelengkapan,
                'tgl_post'        => $row->tgl_post,
                'status'          => $row->status,
                'is_kepala' => $is_kepala
            ];
        }

        $output = [
            "meta" => [
                "page"    => $page,
                "pages"   => ceil($total / $perpage),
                "perpage" => $perpage,
                "total"   => $total
            ],
            "data" => $data
        ];

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    // ====================== FORM TAMBAH (Step 1 & Step 2) ======================
    public function form($mode = 'add', $param = null) {
        $page_data['page_access'] = "admin";
        $page_data['page_now']    = 'Request Bebas Pustaka';
        $page_data['page_title']  = 'Tambah Request Bebas Pustaka';
        $page_data['page_name']   = 'manage_bepus_request';
        $page_data['page_dir']    = 'manage_bepus_request';
        $page_data['page_file']   = 'form_add';

        if ($mode === 'add_step2' && $param !== null) {
            // Step 2 Tambah
            $nim = base64_decode(urldecode($param));
            if (empty($nim)) show_404();

            $page_data['siswa']       = $this->Md_vwsiswa->getSiswaByNim($nim);
            $page_data['syarat_list'] = $this->Md_bepus_syarat->get_active_for_preview();
            $page_data['nim']         = $nim;
            $page_data['mode']        = 'add_step2';

        } elseif ($mode === 'add') {
            // Step 1 Tambah (Input NIM)
            $page_data['mode'] = 'add';

        } else {
            show_404();
        }

        $this->load->view('index', $page_data);
    }

    // ====================== FORM EDIT ======================
    public function edit($id_enc) {
        $page_data['page_access'] = "admin";
        $page_data['page_now']    = 'Bebas Pustaka';
        $page_data['page_title']  = 'Edit Request Bebas Pustaka';
        $page_data['page_name']   = 'manage_bepus_request';
        $page_data['page_dir']    = 'manage_bepus_request';
        $page_data['page_file']   = 'form_edit';   // ← gunakan form_edit.php

        $bepusrequest_id = decrypt($id_enc);
        if (!$bepusrequest_id) show_404();

        $request = $this->Md_bepus_request->get_by_id($bepusrequest_id);
        if (!$request) show_404();

        $page_data['request']     = $request;
        $page_data['siswa']       = $this->Md_vwsiswa->getSiswaByNim($request->nim);
        $page_data['syarat_list'] = $this->Md_bepus_syarat->get_active_for_preview();
        $page_data['nim']         = $request->nim;
        $page_data['id_enc']      = $id_enc;

        $this->load->view('index', $page_data);
    }

    // ================== PROSES NIM (Step 1 Tambah) ==================
    public function proses_nim() {
        $nim = trim($this->input->post('nim'));

        if (empty($nim)) {
            echo json_encode(['status' => 'error', 'message' => 'NIM tidak boleh kosong']);
            exit;
        }

        if ($this->Md_bepus_request->cek_request_aktif($nim)) {
            echo json_encode(['status' => 'error', 'message' => 'Sudah ada request bebas pustaka aktif untuk NIM ini']);
            exit;
        }

        $siswa = $this->Md_vwsiswa->getSiswaByNim($nim);
        if (!$siswa) {
            echo json_encode(['status' => 'error', 'message' => 'NIM tidak ditemukan di database']);
            exit;
        }

        $nim_encoded = urlencode(base64_encode($nim));

        echo json_encode([
            'status'   => 'success',
            'redirect' => base_url('dir/manage_bepus_request/form/add_step2/' . $nim_encoded)
        ]);
        exit;
    }

    // ================== SAVE (Tambah & Edit) ==================
    public function save() {
        $nim      = trim($this->input->post('nim'));
        $is_edit  = $this->input->post('is_edit') === '1';
        $id_enc   = $this->input->post('id_for_edit');
        $checkbox = $this->input->post('checkbox') ?? [];
        $tahun_akademik = trim($this->input->post('tahun_akademik'));
        //hibah buku
        $judul_buku     = $this->input->post('judul_buku') ?? [];
        $isbn           = $this->input->post('isbn') ?? [];
        $penerbit       = $this->input->post('penerbit') ?? [];
        $pengarang      = $this->input->post('pengarang') ?? [];
        $tahun_terbit   = $this->input->post('tahun_terbit') ?? [];
        $tempat_terbit  = $this->input->post('tempat_terbit') ?? [];
        $harga_buku     = $this->input->post('harga_buku') ?? [];
        $keterangan     = $this->input->post('keterangan') ?? [];
        
        if (empty($nim)) {
            echo json_encode(['status' => 'error', 'message' => 'NIM tidak valid']);
            exit;
        }

        $this->db->trans_start();
        
        // ============================================================
        // VALIDASI BUKU HIBAH
        // Jika ada baris yang diisi sebagian, pastikan field wajib ada
        // ============================================================
        for ($i = 0; $i < count($judul_buku); $i++) {
            $has_judul    = trim($judul_buku[$i]) !== '';
            $has_pengarang = trim($pengarang[$i]) !== '';
            $has_tahun    = trim($tahun_terbit[$i]) !== '';
            $row_filled   = $has_judul || $has_pengarang || $has_tahun
                            || trim($isbn[$i] ?? '') !== ''
                            || trim($penerbit[$i] ?? '') !== ''
                            || trim($tempat_terbit[$i] ?? '') !== ''
                            || trim($keterangan[$i] ?? '') !== '';

            // Baris ini ada isian → wajib lengkap di 3 field utama
            if ($row_filled && (!$has_judul || !$has_pengarang || !$has_tahun)) {
                echo json_encode([
                    'status'  => 'error',
                    'message' => 'Baris buku ke-' . ($i + 1) . ': Judul, Pengarang, dan Tahun Terbit wajib diisi!'
                ]);
                exit;
            }
        }
        // ============================================================

        if ($is_edit && $id_enc) {
            $bepusrequest_id = decrypt($id_enc);
            if (!$bepusrequest_id) {
                $this->db->trans_rollback();
                echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
                exit;
            }
            // Update existing request
            $this->Md_bepus_request->update_request($bepusrequest_id, [
                'tgl_post' => date('Y-m-d H:i:s')
            ]);
            $this->Md_bepus_request->delete_detail($bepusrequest_id);   // hapus detail lama
        } else {
            // Insert baru
            // Generate No Request : 26/BP/04/0001
            $nomor = $this->Md_bepus_request->generate_no_request_prodi($nim);

            if (!$nomor['status']) {
                $this->db->trans_rollback();
                echo json_encode([
                    'status'  => 'error',
                    'message' => $nomor['message']
                ]);
                exit;
            }

            $request_data = [
                'nim'               => $nim,
                'tahun_akademik'    => $tahun_akademik,
                'no_request'        => $nomor['no_request'],
                'status_pengajuan'  => 'Pending',
                'tgl_post'          => date('Y-m-d H:i:s'),
                'status'            => 1
            ];
            
            $this->Md_bepus_request->insert_request($request_data);
            $bepusrequest_id = $this->db->insert_id();
        }

        // Insert / Re-insert detail
        $syarat_list = $this->Md_bepus_syarat->get_active_for_preview();
        foreach ($syarat_list as $syarat) {
            $status_syarat = in_array($syarat->bepussyarat_id, $checkbox) ? 'OK' : 'NOT OK';

            $detail = [
                'bepusrequest_id' => $bepusrequest_id,
                'bepussyarat_id'  => $syarat->bepussyarat_id,
                'status_syarat'   => $status_syarat,
                'tgl_post'        => date('Y-m-d H:i:s'),
                'status'          => 1
            ];
            $this->Md_bepus_request->insert_detail($detail);
        }
        
        // ================= HIBAH BUKU =================
        

        $data_hibah = [];

        for($i=0; $i<count($judul_buku); $i++){
            if(!empty($judul_buku[$i])){
                // Sanitasi harga: pastikan hanya angka, default 0
                $raw_harga = isset($harga_buku[$i]) ? preg_replace('/[^0-9]/', '', $harga_buku[$i]) : '0';
                $raw_harga = ($raw_harga === '') ? 0 : (int) $raw_harga;
 
                $data_hibah[] = [
                    'bepusrequest_id' => $bepusrequest_id,
                    'judul_buku'      => $judul_buku[$i],
                    'isbn'            => $isbn[$i],
                    'penerbit'        => $penerbit[$i],
                    'pengarang'       => $pengarang[$i],
                    'tahun_terbit'    => $tahun_terbit[$i],
                    'tempat_terbit'   => $tempat_terbit[$i],
                    'harga_buku'      => $raw_harga,
                    'keterangan'      => $keterangan[$i],
                    'status'          => 1
                ];
            }
        }

        // kalau edit → hapus lama
        if(!empty($data_hibah)){
            $this->Md_bepus_hibah->delete_by_request($bepusrequest_id);
            $this->Md_bepus_hibah->insert_batch($data_hibah);
        }
        
        $log = [
            'user_id'     => $this->session->userdata('idsys'),
            'jenis_log'   => 'Admin',
            'jenis_akses' => 'Add',
            'status'      => 1,
            'keterangan'  => $this->session->userdata('username') . ' Menambah Request Bebas Pustaka ID #' . $bepusrequest_id,
            'IP'          => $this->input->ip_address()
        ];
        $this->Md_log->addLog($log);
        
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan request']);
        } else {
            echo json_encode([
                'status'  => 'success',
                'message' => 'Request Bebas Pustaka berhasil ' . ($is_edit ? 'diperbarui' : 'disimpan')
            ]);
        }
        exit;
    }
    
    // ====================== KONfirmASI (Approve / Reject) ======================
    public function konfirmasi($id_enc = null) {
        $page_data['page_access'] = "admin";
        $page_data['page_now']    = 'Request Bebas Pustaka';
        $page_data['page_title']  = 'Konfirmasi Request Bebas Pustaka';
        $page_data['page_name']   = 'manage_bepus_request';
        $page_data['page_dir']    = 'manage_bepus_request';
        $page_data['page_file']   = 'konfirmasi';

        $id = decrypt($id_enc);
        if (!$id) show_404();

        $page_data['request'] = $this->Md_bepus_request->get_full_request($id);
        if (!$page_data['request']) show_404();

        $this->load->view('index', $page_data);
    }

    // ====================== UPDATE SAVE KONFIRMASI (dengan validasi) ======================
    public function save_konfirmasi() {
        $id_enc = $this->input->post('id_for_edit');
        $id = decrypt($id_enc);

        $status_pengajuan = $this->input->post('status_pengajuan');
        $notes            = $this->input->post('notes');
        //$detail_status    = $this->input->post('detail_status') ?? [];
        //$detail_note      = $this->input->post('detail_note') ?? [];

        // === BACKEND VALIDATION ===
        if ($status_pengajuan === 'Approve') {
            if (!$this->Md_bepus_request->is_all_required_ok($id)) {
                echo json_encode([
                    'status'  => 'error', 
                    'message' => 'Semua syarat yang wajib harus di Centang terlebih dahulu sebelum Approve!'
                ]);
                exit;
            }
        }

        // Tambahan: Cegah Approve jika ada checkbox yang belum dicentang (double check)
        // Meskipun sudah ada pengecekan di model, ini lebih aman
        /*if ($status_pengajuan === 'Approve') {
            $detail_status = $this->input->post('detail_status') ?? [];

            // Ambil semua detail yang is_isian = 'Ya'
            $required_details = $this->Md_bepus_request->get_required_details($id); 

            foreach ($required_details as $detail) {
                $detail_id = $detail->bepusrequestdetail_id;
                if (!isset($detail_status[$detail_id]) || $detail_status[$detail_id] !== 'OK') {
                    echo json_encode([
                        'status'  => 'error', 
                        'message' => 'Semua persyaratan wajib harus dicentang (OK) sebelum Approve'
                    ]);
                    exit;
                }
            }
        }*/

        $this->db->trans_start();

        // Update request
        $this->Md_bepus_request->update_request($id, [
            'status_pengajuan'   => $status_pengajuan,
            'idsysuser_kaperpus' => $this->session->userdata('idsys'),
            'tgl_approve'        => date('Y-m-d H:i:s'),
            'notes'              => $notes
        ]);
        
        /*
        // Update detail
        foreach ($detail_status as $detail_id => $status) {
            $note = $detail_note[$detail_id] ?? null;
            $this->Md_bepus_request->update_detail($detail_id, [
                'status_syarat'   => $status,
                'note_pustakawan' => $note
            ]);
        }
        */
        $log = [
            'user_id'     => $this->session->userdata('idsys'),
            'jenis_log'   => 'Admin',
            'jenis_akses' => 'Update',
            'status'      => 1,
            'keterangan'  => $this->session->userdata('username') . ' Mengkonfirmasi Request Bebas Pustaka ID #' . $id,
            'IP'          => $this->input->ip_address()
        ];
        $this->Md_log->addLog($log);
        
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan konfirmasi']);
        } else {
            echo json_encode(['status' => 'success', 'message' => 'Konfirmasi berhasil disimpan']);
        }
        exit;
    }
    
    // ====================== VALIDASI REQUEST ======================
    public function validasi($id_enc) {
        $page_data['page_access'] = "admin";
        $page_data['page_now']    = 'Request Bebas Pustaka';
        $page_data['page_title']  = 'Validasi Request Bebas Pustaka';
        $page_data['page_name']   = 'manage_bepus_request';
        $page_data['page_dir']    = 'manage_bepus_request';
        $page_data['page_file']   = 'validasi';

        $id = decrypt($id_enc);
        if (!$id) show_404();

        $page_data['request'] = $this->Md_bepus_request->get_full_request($id);
        if (!$page_data['request']) show_404();

        $this->load->view('index', $page_data);
    }

    // ====================== SAVE VALIDASI ======================
    public function save_validasi() {
        $id_enc = $this->input->post('id_for_edit');
        $id = decrypt($id_enc); // pastikan decrypt berfungsi dengan benar

        $detail_status = $this->input->post('detail_status') ?? [];   // hanya yang dicentang
        $detail_note   = $this->input->post('detail_note')   ?? [];   // semua catatan selalu ada

        $this->db->trans_start();

        foreach ($detail_note as $detail_id => $note) {
            // Tentukan status: OK jika checkbox dicentang, NOT OK jika tidak
            $status = isset($detail_status[$detail_id]) ? 'OK' : 'NOT OK';

            // Update detail
            $this->Md_bepus_request->update_detail($detail_id, [
                'status_syarat'   => $status,
                'note_pustakawan' => $note,                    // tetap simpan catatan
                'check_by'        => $this->session->userdata('idsys')
            ]);
        }
        $log = [
            'user_id'     => $this->session->userdata('idsys'),
            'jenis_log'   => 'Admin',
            'jenis_akses' => 'Update',
            'status'      => 1,
            'keterangan'  => $this->session->userdata('username') . ' Melakukan Validasi Request Bebas Pustaka ID #' . $id,
            'IP'          => $this->input->ip_address()
        ];
        $this->Md_log->addLog($log);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode([
                'status'  => 'error', 
                'message' => 'Gagal menyimpan validasi'
            ]);
        } else {
            echo json_encode([
                'status'  => 'success', 
                'message' => 'Validasi berhasil disimpan'
            ]);
        }
        exit;
    }

    // ====================== HAPUS ======================
    public function delete() {
        $id_enc = $this->input->post('id');
        $id = decrypt($id_enc);

        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
            exit;
        }

        $this->db->trans_start();
        $this->Md_bepus_request->soft_delete($id);

        $log = [
            'user_id'     => $this->session->userdata('idsys'),
            'jenis_log'   => 'Admin',
            'jenis_akses' => 'Hapus',
            'status'      => 1,
            'keterangan'  => $this->session->userdata('username') . ' Menghapus Request Bebas Pustaka ID #' . $id,
            'IP'          => $this->input->ip_address()
        ];
        $this->Md_log->addLog($log);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus']);
        } else {
            $this->db->trans_commit();
            echo json_encode(['status' => 'success', 'message' => 'Request berhasil dihapus']);
        }
        exit;
    }
    
    // ====================== FILTER OPTIONS ======================
    public function get_kelas_options() {
        $this->load->model('Md_vwsiswa');
        return $this->Md_vwsiswa->get_distinct_kelas();
    }

    public function get_tahun_masuk_options() {
        $this->load->model('Md_vwsiswa');
        return $this->Md_vwsiswa->get_distinct_tahun_masuk();
    }
    
    /**
    * Download Form Pengajuan sebagai PDF
    */
    public function download($id_enc = null)
    {
        if (!$id_enc) {
            show_error('ID Request tidak ditemukan', 404);
        }

        $id = decrypt($id_enc);

        $request = $this->Md_bepus_request->get_full_request($id);

        if (!$request) {
            show_error('Data request tidak ditemukan', 404);
        }

        $data['request'] = $request;
        $data['title']   = 'Form Pengajuan Bebas Pustaka';

        $html_surat = $this->load->view(
            'pages/manage_bepus_request/download_surat',
            $data,
            TRUE
        );

        $html_hibah = $this->load->view(
            'pages/manage_bepus_request/download_hibah',
            $data,
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
            'default_font'  => 'dejavusans',
        ]);

        $kop_path = FCPATH . 'assets/media/Kop PKR New.png';

        if (file_exists($kop_path)) {

            $header_kop = '
                <div style="text-align:center;">
                    <img src="' . $kop_path . '" 
                         style="max-width:100%;height:auto;">
                </div>
            ';

            $mpdf->SetHTMLHeader($header_kop);

        } else {

            $mpdf->SetHTMLHeader('
                <div style="text-align:center;
                            font-weight:bold;
                            font-size:14px;
                            border-bottom:1px solid #000;
                            padding-bottom:5px;">
                    FORM PENGAJUAN BEBAS PUSTAKA
                </div>
            ');
        }

        $mpdf->SetHTMLFooter('
            <div style="text-align:center;
                        font-size:10px;
                        color:#666;
                        border-top:1px solid #ddd;
                        padding-top:5px;">
                Halaman {PAGENO} dari {nb} |
                Dicetak otomatis dari sistem pada ' . date('d F Y H:i:s') . '
            </div>
        ');

        /*
         * HALAMAN 1
         * SURAT BEBAS PUSTAKA
         */
        $mpdf->WriteHTML($html_surat);

        /*
         * HALAMAN 2
         * HIBAH BUKU
         * TANPA KOP SURAT
         */
        if(!empty($request->hibah)){
            $mpdf->SetHTMLHeader('');
            $mpdf->AddPage();

            $mpdf->WriteHTML($html_hibah);
        }

        $filename =
            'Form_Pengajuan_Bebas_Pustaka_' .
            ($request->no_request ?? 'Unknown') .
            '_' .
            date('YmdHis') .
            '.pdf';

        $mpdf->Output($filename, 'I');
        exit;
    }
    
    public function export_excel()
    {
        $search = $this->input->get('search');
        $kelas  = $this->input->get('kelas_filter');
        $tahun  = $this->input->get('tahun_filter');

        // Ambil data TANPA limit
        $data = $this->Md_bepus_request->get_all_export($search, $kelas, $tahun);

        // Load library sederhana (tanpa composer)
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Export_Bebas_Pustaka_" . date('YmdHis') . ".xls");

        echo "<table border='1'>";
        echo "<tr>
                <th>No</th>
                <th>No Request</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Tahun Akademik</th>
                <th>Status</th>
                <th>Kelengkapan</th>
                <th>Tanggal Request</th>
                <th>Tanggal Approve/Reject</th>
              </tr>";

        $no = 1;
        foreach ($data as $row) {
            $kelengkapan = ($row->belum_lengkap_count == 0) ? 'Lengkap' : 'Belum Lengkap';

            echo "<tr>
                    <td>{$no}</td>
                    <td>{$row->no_request}</td>
                    <td>{$row->nim}</td>
                    <td>{$row->nama}</td>
                    <td>{$row->kelas}</td>
                    <td>{$row->tahun_akademik}</td>
                    <td>{$row->status_pengajuan}</td>
                    <td>{$kelengkapan}</td>
                    <td>{$row->tgl_post}</td>
                    <td>{$row->tgl_approve}</td>
                  </tr>";
            $no++;
        }

        echo "</table>";
        exit;
    }
    

}