<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_konfigurasi_web extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->database();

        $this->load->model('Md_konfigurasi_web');
        $this->load->model('Md_log');

        if ($this->session->userdata('login_type') != 'admin') {
            logoutNow();
        }
    }

    public function index() {
        $page_data['page_name'] = 'manage_konfigurasi_web';
        $page_data['page_dir'] = 'manage_konfigurasi_web';
        $page_data['page_file'] = 'index';

        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'Konten Website';
        $page_data['page_title'] = 'Konfigurasi Beranda';

        $page_data['data'] = $this->Md_konfigurasi_web->get_all();

        $this->load->view('index', $page_data);
    }

    public function save() {
        $post = $this->input->post();

        // ================= VALIDATION =================
        $this->load->library('form_validation');

        $this->form_validation->set_rules('tgl_awal[]', 'Tanggal Awal', 'required');
        $this->form_validation->set_rules('tgl_akhir[]', 'Tanggal Akhir', 'required');
        $this->form_validation->set_rules('jumlah_data[]', 'Jumlah Data', 'required|integer');

        $this->form_validation->set_message('required', '%s tidak boleh kosong');
        $this->form_validation->set_message('integer', '%s harus berupa angka');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status' => 'error',
                'message' => validation_errors('<p class="text-danger">', '</p>')
            ]);
            exit;
        }

        // VALIDASI TAMBAHAN (ANTI ARRAY KOSONG / INDEX KOSONG)
        foreach ($post['id'] as $i => $id) {
            if (
                    empty($post['tgl_awal'][$i]) ||
                    empty($post['tgl_akhir'][$i]) ||
                    empty($post['jumlah_data'][$i])
            ) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Semua field wajib diisi'
                ]);
                exit;
            }
        }

        // ================= TRANSACTION =================
        $this->db->trans_begin();

        $data_update = [];

        foreach ($post['id'] as $key => $id) {

            $data_update[] = [
                'konfigurasiweb_id' => $id,
                'keterangan' => $post['keterangan'][$key],
                'tgl_awal' => $post['tgl_awal'][$key],
                'tgl_akhir' => $post['tgl_akhir'][$key],
                'jumlah_data' => $post['jumlah_data'][$key],
                'tgl_update' => date('Y-m-d H:i:s'),
                'update_by' => $this->session->userdata('idsys')
            ];
        }

        $this->Md_konfigurasi_web->update_batch($data_update);

        // ================= LOG =================
        $log = [
            'user_id' => $this->session->userdata('idsys'),
            'jenis_log' => 'Admin',
            'jenis_akses' => 'Update',
            'status' => 1,
            'keterangan' => $this->session->userdata('username') . ' Update Konfigurasi Beranda',
            'IP' => $this->input->ip_address()
        ];

        $this->Md_log->addLog($log);

        // ================= COMMIT =================
        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();

            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menyimpan data'
            ]);
        } else {

            $this->db->trans_commit();

            echo json_encode([
                'status' => 'success',
                'message' => 'Konfigurasi berhasil diperbarui'
            ]);
        }

        exit;
    }
}
