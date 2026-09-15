<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_pegawai extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');

        $this->load->database();
        $this->load->library('form_validation');
        $this->load->model('Md_pegawai');
        $this->load->model('Md_log');
        $this->load->helper('pkrlib_helper');

        if ($this->session->userdata('login_type') != 'admin') {
            logoutNow();
        }
    }

    /* =========================================================
     * INDEX
     * ========================================================= */
    public function index()
    {
        $page_data['page_name']   = 'manage_pegawai';
        $page_data['page_dir']    = 'manage_pegawai';
        $page_data['page_file']   = 'index';

        $page_data['page_access'] = "admin";
        $page_data['page_now']    = 'Data Induk';
        $page_data['page_title']  = 'Manage Pegawai';

        $this->load->view('index', $page_data);
    }

    /* =========================================================
     * FETCH PEGAWAI AKTIF
     * ========================================================= */
    public function fetch_aktif()
    {
        $datatable = $this->input->post('datatable');

        $search  = $datatable['query']['generalSearch'] ?? '';
        $page    = $datatable['pagination']['page'] ?? 1;
        $perpage = $datatable['pagination']['perpage'] ?? 10;
        $sort    = $datatable['sort']['sort'] ?? 'DESC';
        $field   = $datatable['sort']['field'] ?? 'pegawai_id';

        $offset = ($page - 1) * $perpage;

        $list  = $this->Md_pegawai->get_datatables_aktif($search, $perpage, $offset, $field, $sort);
        $total = $this->Md_pegawai->count_filtered_aktif($search);

        $data = [];
        $no   = $offset;

        foreach ($list as $row) {

            $no++;

            $data[] = [
                'number'         => $no,
                'id_enc'         => urlencode(base64_encode($row->pegawai_id)),
                'nip'            => $row->nip,
                'nama'           => $row->nama,
                'nik'            => $row->nik,
                'email'          => $row->email,
                'telp'           => $row->telp,
                'tempat_lahir'   => $row->tempat_lahir,
                'tgl_lahir'      => date("d-M-Y", strtotime($row->tgl_lahir)),
                'author'         => $row->author,
                'tgl_post'       => date("d-M-Y h:i", strtotime($row->tgl_post))
            ];
        }

        echo json_encode([
            "meta" => [
                "page"    => $page,
                "pages"   => ceil($total / $perpage),
                "perpage" => $perpage,
                "total"   => $total
            ],
            "data" => $data
        ]);
    }

    /* =========================================================
     * FETCH PEGAWAI NON AKTIF
     * ========================================================= */
    public function fetch_nonaktif()
    {
        $datatable = $this->input->post('datatable');

        $search  = $datatable['query']['generalSearch'] ?? '';
        $page    = $datatable['pagination']['page'] ?? 1;
        $perpage = $datatable['pagination']['perpage'] ?? 10;
        $sort    = $datatable['sort']['sort'] ?? 'DESC';
        $field   = $datatable['sort']['field'] ?? 'pegawai_id';

        $offset = ($page - 1) * $perpage;

        $list  = $this->Md_pegawai->get_datatables_nonaktif($search, $perpage, $offset, $field, $sort);
        $total = $this->Md_pegawai->count_filtered_nonaktif($search);

        $data = [];
        $no   = $offset;

        foreach ($list as $row) {

            $no++;

            $data[] = [
                'number'           => $no,
                'id_enc'           => urlencode(base64_encode($row->pegawai_id)),
                'nip'              => $row->nip,
                'nama'             => $row->nama,
                'nik'              => $row->nik,
                'email'            => $row->email,
                'telp'             => $row->telp,
                'tempat_lahir'     => $row->tempat_lahir,
                'tgl_lahir'        => date("d-M-Y", strtotime($row->tgl_lahir)),
                'tgl_nonaktif'     => date("d-M-Y", strtotime($row->tgl_nonaktif)),
                'author_nonaktif'  => $row->author_nonaktif
            ];
        }

        echo json_encode([
            "meta" => [
                "page"    => $page,
                "pages"   => ceil($total / $perpage),
                "perpage" => $perpage,
                "total"   => $total
            ],
            "data" => $data
        ]);
    }

    /* =========================================================
     * SAVE
     * ========================================================= */
    public function save()
    {
        $this->form_validation->set_rules('nip', 'NIP', 'required|trim');
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('nik', 'NIK', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim');
        $this->form_validation->set_rules('telp', 'Telp', 'required|trim');
        $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required|trim');
        $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required|trim');

        $this->form_validation->set_message('required', '%s tidak boleh kosong');

        if ($this->form_validation->run() == FALSE) {

            echo json_encode([
                'status'  => 'error',
                'message' => validation_errors('<p class="text-danger">', '</p>')
            ]);
            exit;
        }

        $id_enc = $this->input->post('id_for_edit');

        $nip            = trim($this->input->post('nip'));
        $nama           = trim($this->input->post('nama'));
        $nik            = trim($this->input->post('nik'));
        $email          = trim($this->input->post('email'));
        $telp           = trim($this->input->post('telp'));
        $tempat_lahir   = trim($this->input->post('tempat_lahir'));
        $tgl_lahir      = trim($this->input->post('tgl_lahir'));

        $data = [
            'nip'            => $nip,
            'nama'           => $nama,
            'nik'            => $nik,
            'email'          => $email,
            'telp'           => $telp,
            'tempat_lahir'   => $tempat_lahir,
            'tgl_lahir'      => $tgl_lahir
        ];

        $this->db->trans_start();

        $is_update = !empty($id_enc);

        /* =====================================================
         * UPDATE
         * ===================================================== */
        if ($is_update) {

            $id = base64_decode(urldecode($id_enc));

            if ($id === false || $id === '' || !is_numeric($id)) {

                $this->db->trans_rollback();

                echo json_encode([
                    'status'  => 'error',
                    'message' => 'ID tidak valid'
                ]);
                exit;
            }

            /* ================= CEK DUPLIKAT ================= */

            if ($this->Md_pegawai->cek_duplicate_nip($nip, $id)) {

                $this->db->trans_rollback();

                echo json_encode([
                    'status'  => 'error',
                    'message' => 'NIP sudah digunakan'
                ]);
                exit;
            }

            if ($this->Md_pegawai->cek_duplicate_nik($nik, $id)) {

                $this->db->trans_rollback();

                echo json_encode([
                    'status'  => 'error',
                    'message' => 'NIK sudah digunakan'
                ]);
                exit;
            }

            if ($this->Md_pegawai->cek_duplicate_email($email, $id)) {

                $this->db->trans_rollback();

                echo json_encode([
                    'status'  => 'error',
                    'message' => 'Email sudah digunakan'
                ]);
                exit;
            }

            $this->Md_pegawai->update($id, $data);

            $jenis_akses = 'Update';

            $log_ket = $this->session->userdata('username') .
                ' Melakukan Update Pegawai NIP : ' . $nip;

        } else {

            /* =====================================================
             * INSERT
             * ===================================================== */

            if ($this->Md_pegawai->cek_duplicate_nip($nip)) {

                $this->db->trans_rollback();

                echo json_encode([
                    'status'  => 'error',
                    'message' => 'NIP sudah digunakan'
                ]);
                exit;
            }

            if ($this->Md_pegawai->cek_duplicate_nik($nik)) {

                $this->db->trans_rollback();

                echo json_encode([
                    'status'  => 'error',
                    'message' => 'NIK sudah digunakan'
                ]);
                exit;
            }

            if ($this->Md_pegawai->cek_duplicate_email($email)) {

                $this->db->trans_rollback();

                echo json_encode([
                    'status'  => 'error',
                    'message' => 'Email sudah digunakan'
                ]);
                exit;
            }
            
            $data['status_anggota'] = 'Aktif';
            $data['tgl_aktif']      = date('Y-m-d');
            $data['author']         = $this->session->userdata('idsys');
            $data['tgl_post']       = date('Y-m-d H:i:s');
            $data['status']         = 1;

            $this->Md_pegawai->insert($data);

            $jenis_akses = 'Add';

            $log_ket = $this->session->userdata('username') .
                ' Melakukan Tambah Pegawai NIP : ' . $nip;
        }

        /* =====================================================
         * LOG AKTIVITAS
         * ===================================================== */
        $log = [
            'user_id'     => $this->session->userdata('idsys'),
            'jenis_log'   => 'Admin',
            'jenis_akses' => $jenis_akses,
            'status'      => 1,
            'keterangan'  => $log_ket,
            'IP'          => $this->input->ip_address()
        ];

        $this->Md_log->addLog($log);

        /* =====================================================
         * FINAL TRANSACTION
         * ===================================================== */
        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();

            echo json_encode([
                'status'  => 'error',
                'message' => 'Gagal menyimpan data'
            ]);

        } else {

            $this->db->trans_commit();

            $msg = $is_update ?
                'Data berhasil diperbarui' :
                'Data berhasil ditambahkan';

            echo json_encode([
                'status'  => 'success',
                'message' => $msg
            ]);
        }

        exit;
    }

    /* =========================================================
     * GET DETAIL
     * ========================================================= */
    public function get_detail()
    {
        $id = base64_decode(
            urldecode($this->input->post('id'))
        );

        $data = $this->Md_pegawai->get_by_id($id);

        if (!$data) {

            echo json_encode([
                'status' => 'error'
            ]);

            return;
        }

        /* =========================================
         * FORMAT KHUSUS UNTUK DISPLAY
         * ========================================= */

        $data->tgl_lahir_format = '';

        if (!empty($data->tgl_lahir)) {

            $data->tgl_lahir_format = date(
                'd-M-Y',
                strtotime($data->tgl_lahir)
            );
        }

        $data->tgl_post_format = '';

        if (!empty($data->tgl_post)) {

            $data->tgl_post_format = date(
                'd-M-Y H:i',
                strtotime($data->tgl_post)
            );
        }

        echo json_encode([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    /* =========================================================
     * NON AKTIFKAN PEGAWAI
     * ========================================================= */
    public function nonaktifkan()
    {
        $id_enc = $this->input->post('id');

        $id = base64_decode(
            urldecode($id_enc)
        );

        if ($id === false || $id === '' || !is_numeric($id)) {

            echo json_encode([
                'status'  => 'error',
                'message' => 'ID tidak valid'
            ]);
            exit;
        }

        $this->db->trans_start();

        $data = $this->Md_pegawai->get_by_id($id);

        if (!$data) {

            $this->db->trans_rollback();

            echo json_encode([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
            exit;
        }

        $update = [
            'status_anggota'  => 'Non Aktif',
            'tgl_nonaktif'    => date('Y-m-d'),
            'author_nonaktif' => $this->session->userdata('idsys')
        ];

        $this->Md_pegawai->update($id, $update);

        /* ================= LOG ================= */
        $log = [
            'user_id'     => $this->session->userdata('idsys'),
            'jenis_log'   => 'Admin',
            'jenis_akses' => 'Update',
            'status'      => 1,
            'keterangan'  => $this->session->userdata('username') .
                              ' Menonaktifkan Pegawai NIP : ' .
                              $data->nip,
            'IP'          => $this->input->ip_address()
        ];

        $this->Md_log->addLog($log);

        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();

            echo json_encode([
                'status'  => 'error',
                'message' => 'Gagal menonaktifkan pegawai'
            ]);

        } else {

            $this->db->trans_commit();

            echo json_encode([
                'status'  => 'success',
                'message' => 'Pegawai berhasil dinonaktifkan'
            ]);
        }

        exit;
    }

    /* =========================================================
     * SOFT DELETE
     * ========================================================= */
    public function delete()
    {
        $id_enc = $this->input->post('id');

        $id = base64_decode(
            urldecode($id_enc)
        );

        if ($id === false || $id === '' || !is_numeric($id)) {

            echo json_encode([
                'status'  => 'error',
                'message' => 'ID tidak valid'
            ]);
            exit;
        }

        $this->db->trans_start();

        $data = $this->Md_pegawai->get_by_id($id);

        $this->Md_pegawai->soft_delete($id);

        /* ================= LOG ================= */
        $log = [
            'user_id'     => $this->session->userdata('idsys'),
            'jenis_log'   => 'Admin',
            'jenis_akses' => 'Hapus',
            'status'      => 1,
            'keterangan'  => $this->session->userdata('username') .
                              ' Melakukan Hapus Pegawai NIP : ' .
                              $data->nip,
            'IP'          => $this->input->ip_address()
        ];

        $this->Md_log->addLog($log);

        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();

            echo json_encode([
                'status'  => 'error',
                'message' => 'Gagal menghapus data'
            ]);

        } else {

            $this->db->trans_commit();

            echo json_encode([
                'status'  => 'success',
                'message' => 'Data berhasil dihapus'
            ]);
        }

        exit;
    }
    
    /* =========================================================
    * EXPORT EXCEL PEGAWAI AKTIF
    * ========================================================= */
    public function export_excel_aktif()
    {
        require APPPATH . 'third_party/PHPExcel.php';

        $excel = new PHPExcel();

        $sheet = $excel->setActiveSheetIndex(0);

        $sheet->setTitle('Pegawai Aktif');

        /* ================= HEADER ================= */
        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'NIP');
        $sheet->setCellValue('C1', 'NAMA');
        $sheet->setCellValue('D1', 'NIK');
        $sheet->setCellValue('E1', 'EMAIL');
        $sheet->setCellValue('F1', 'TELP');
        $sheet->setCellValue('G1', 'TEMPAT LAHIR');
        $sheet->setCellValue('H1', 'TANGGAL LAHIR');
        $sheet->setCellValue('I1', 'AUTHOR');
        $sheet->setCellValue('J1', 'TGL POST');

        $list = $this->db
            ->where('status', 1)
            ->where('status_anggota', 'Aktif')
            ->get('pegawai')
            ->result();

        $rowNum = 2;
        $no     = 1;

        foreach ($list as $row) {

            $sheet->setCellValue('A'.$rowNum, $no++);
            $sheet->setCellValue('B'.$rowNum, $row->nip);
            $sheet->setCellValue('C'.$rowNum, $row->nama);
            $sheet->setCellValue('D'.$rowNum, $row->nik);
            $sheet->setCellValue('E'.$rowNum, $row->email);
            $sheet->setCellValue('F'.$rowNum, $row->telp);
            $sheet->setCellValue('G'.$rowNum, $row->tempat_lahir);
            $sheet->setCellValue('H'.$rowNum, $row->tgl_lahir);
            $sheet->setCellValue('I'.$rowNum, $row->author);
            $sheet->setCellValue('J'.$rowNum, $row->tgl_post);

            $rowNum++;
        }

        /* ================= AUTO SIZE ================= */
        foreach(range('A','J') as $columnID) {
            $sheet->getColumnDimension($columnID)
                  ->setAutoSize(true);
        }

        /* ================= LOG ================= */
        $log = [
            'user_id'     => $this->session->userdata('idsys'),
            'jenis_log'   => 'Admin',
            'jenis_akses' => 'Export',
            'status'      => 1,
            'keterangan'  => $this->session->userdata('username') .
                              ' Export Excel Pegawai Aktif',
            'IP'          => $this->input->ip_address()
        ];

        $this->Md_log->addLog($log);

        /* ================= OUTPUT ================= */
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="pegawai_aktif.xls"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $writer->save('php://output');

        exit;
    }

    /* =========================================================
     * EXPORT EXCEL PEGAWAI NON AKTIF
     * ========================================================= */
    public function export_excel_nonaktif()
    {
        require APPPATH . 'third_party/PHPExcel.php';

        $excel = new PHPExcel();

        $sheet = $excel->setActiveSheetIndex(0);

        $sheet->setTitle('Pegawai Non Aktif');

        /* ================= HEADER ================= */
        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'NIP');
        $sheet->setCellValue('C1', 'NAMA');
        $sheet->setCellValue('D1', 'NIK');
        $sheet->setCellValue('E1', 'EMAIL');
        $sheet->setCellValue('F1', 'TELP');
        $sheet->setCellValue('G1', 'TEMPAT LAHIR');
        $sheet->setCellValue('H1', 'TANGGAL LAHIR');
        $sheet->setCellValue('I1', 'TGL NON AKTIF');
        $sheet->setCellValue('J1', 'UPDATE BY');

        $list = $this->db
            ->where('status', 1)
            ->where('status_anggota', 'Non Aktif')
            ->get('pegawai')
            ->result();

        $rowNum = 2;
        $no     = 1;

        foreach ($list as $row) {

            $sheet->setCellValue('A'.$rowNum, $no++);
            $sheet->setCellValue('B'.$rowNum, $row->nip);
            $sheet->setCellValue('C'.$rowNum, $row->nama);
            $sheet->setCellValue('D'.$rowNum, $row->nik);
            $sheet->setCellValue('E'.$rowNum, $row->email);
            $sheet->setCellValue('F'.$rowNum, $row->telp);
            $sheet->setCellValue('G'.$rowNum, $row->tempat_lahir);
            $sheet->setCellValue('H'.$rowNum, $row->tgl_lahir);
            $sheet->setCellValue('I'.$rowNum, $row->tgl_nonaktif);
            $sheet->setCellValue('J'.$rowNum, $row->author_nonaktif);

            $rowNum++;
        }

        foreach(range('A','J') as $columnID) {

            $sheet->getColumnDimension($columnID)
                  ->setAutoSize(true);
        }

        /* ================= LOG ================= */
        $log = [
            'user_id'     => $this->session->userdata('idsys'),
            'jenis_log'   => 'Admin',
            'jenis_akses' => 'Export',
            'status'      => 1,
            'keterangan'  => $this->session->userdata('username') .
                              ' Export Excel Pegawai Non Aktif',
            'IP'          => $this->input->ip_address()
        ];

        $this->Md_log->addLog($log);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="pegawai_nonaktif.xls"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $writer->save('php://output');

        exit;
    }

    /* =========================================================
     * DOWNLOAD TEMPLATE IMPORT
     * ========================================================= */
    public function download_template_import()
    {
        require APPPATH . 'third_party/PHPExcel.php';

        $excel = new PHPExcel();

        $sheet = $excel->setActiveSheetIndex(0);

        $sheet->setTitle('Template Import Pegawai');

        /* ================= HEADER ================= */
        $sheet->setCellValue('A1', 'NIP');
        $sheet->setCellValue('B1', 'NAMA');
        $sheet->setCellValue('C1', 'NIK');
        $sheet->setCellValue('D1', 'EMAIL');
        $sheet->setCellValue('E1', 'TELP');
        $sheet->setCellValue('F1', 'TEMPAT LAHIR');
        $sheet->setCellValue('G1', 'TGL LAHIR');

        /* ================= CONTOH DATA ================= */
        $sheet->setCellValue('A2', '198901011');
        $sheet->setCellValue('B2', 'Budi Santoso');
        $sheet->setCellValue('C2', '140101010101');
        $sheet->setCellValue('D2', 'budi@mail.com');
        $sheet->setCellValue('E2', '08123456789');
        $sheet->setCellValue('F2', 'Pekanbaru');
        $sheet->setCellValue('G2', '1990-01-01');

        foreach(range('A','G') as $columnID) {

            $sheet->getColumnDimension($columnID)
                  ->setAutoSize(true);
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="template_import_pegawai.xls"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $writer->save('php://output');

        exit;
    }

    /* =========================================================
     * IMPORT EXCEL
     * ========================================================= */
    public function import_excel()
    {
        require APPPATH . 'third_party/PHPExcel.php';

        if (empty($_FILES['file_excel']['name'])) {

            echo json_encode([
                'status'  => 'error',
                'message' => 'File excel belum dipilih'
            ]);

            exit;
        }

        $path = $_FILES['file_excel']['tmp_name'];

        $excel = PHPExcel_IOFactory::load($path);

        $sheet = $excel->getActiveSheet()->toArray(null, true, true, true);

        $this->db->trans_start();

        $inserted = 0;

        foreach ($sheet as $key => $row) {

            /* ================= SKIP HEADER ================= */
            if ($key == 1) {
                continue;
            }

            $nip            = trim($row['A']);
            $nama           = trim($row['B']);
            $nik            = trim($row['C']);
            $email          = trim($row['D']);
            $telp           = trim($row['E']);
            $tempat_lahir   = trim($row['F']);
            $tgl_lahir_raw  = trim($row['G']);
            $tgl_lahir      = null;

            if ($tgl_lahir_raw != '') {
                if (is_numeric($tgl_lahir_raw)) {
                    $tgl_lahir = PHPExcel_Shared_Date::ExcelToPHP($tgl_lahir_raw);
                    $tgl_lahir = date('Y-m-d', $tgl_lahir);
                } else {
                    $tgl_lahir = date(
                        'Y-m-d',
                        strtotime($tgl_lahir_raw)
                    );
                }
            }

            /* ================= SKIP BARIS KOSONG ================= */
            if ($nip == '' && $nama == '') {
                continue;
            }

            /* ================= VALIDASI DUPLIKAT ================= */
            if ($this->Md_pegawai->cek_duplicate_nip($nip)) {

                $this->db->trans_rollback();

                echo json_encode([
                    'status'  => 'error',
                    'message' => 'Duplicate NIP : ' . $nip
                ]);

                exit;
            }

            if ($this->Md_pegawai->cek_duplicate_nik($nik)) {

                $this->db->trans_rollback();

                echo json_encode([
                    'status'  => 'error',
                    'message' => 'Duplicate NIK : ' . $nik
                ]);

                exit;
            }

            if ($email != '') {

                if ($this->Md_pegawai->cek_duplicate_email($email)) {

                    $this->db->trans_rollback();

                    echo json_encode([
                        'status'  => 'error',
                        'message' => 'Duplicate Email : ' . $email
                    ]);

                    exit;
                }

            }

            /* ================= INSERT ================= */
            $data = [

                'nip'             => $nip,
                'nama'            => $nama,
                'nik'             => $nik,
                'email'           => ($email != '') ? $email : null,
                'telp'            => ($telp != '') ? $telp : null,
                'tempat_lahir'    => $tempat_lahir,
                'tgl_lahir'       => $tgl_lahir,

                'status_anggota'  => 'Aktif',
                'tgl_aktif'       => date('Y-m-d'),

                'author'          => $this->session->userdata('idsys'),
                'tgl_post'        => date('Y-m-d H:i:s'),

                'status'          => 1
            ];

            $this->Md_pegawai->insert($data);

            $inserted++;
        }

        /* ================= LOG ================= */
        $log = [
            'user_id'     => $this->session->userdata('idsys'),
            'jenis_log'   => 'Admin',
            'jenis_akses' => 'Import',
            'status'      => 1,
            'keterangan'  => $this->session->userdata('username') .
                              ' Import Excel Pegawai Total : ' .
                              $inserted,
            'IP'          => $this->input->ip_address()
        ];

        $this->Md_log->addLog($log);

        /* ================= FINAL TRANSACTION ================= */
        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();

            echo json_encode([
                'status'  => 'error',
                'message' => 'Gagal import data'
            ]);

        } else {

            $this->db->trans_commit();

            echo json_encode([
                'status'  => 'success',
                'message' => 'Berhasil import ' . $inserted . ' data'
            ]);
        }

        exit;
    }

}