<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_kartu_anggota extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->database();
        $this->load->model('Md_media');
        $this->load->model('Md_log');
        $this->load->helper('pkrlib_helper');
        $this->load->helper('encryption_id_helper');

        if ($this->session->userdata('login_type') != 'admin') {
            logoutNow();
        }
    }

    public function index() {
        $page_data['page_access'] = "admin";
        $page_data['page_now']    = 'Data Referensi';
        $page_data['page_title']  = 'Manage Kartu Anggota';
        $page_data['page_name']   = 'manage_kartu_anggota';
        $page_data['page_dir']    = 'manage_kartu_anggota';
        $page_data['page_file']   = 'index';

        // Ambil desain aktif saat ini
        $page_data['kartu_depan']    = $this->Md_media->get_active(3);
        $page_data['kartu_belakang'] = $this->Md_media->get_active(4);

        $this->load->view('index', $page_data);
    }

    public function save() {
        $type = $this->input->post('type');

        if ($type === 'depan') {
            $jenismedia_id = 3;
            $judul         = 'Kartu Anggota Depan';
            $alt_teks      = 'Kartu Anggota Depan';
        } elseif ($type === 'belakang') {
            $jenismedia_id = 4;
            $judul         = 'Kartu Anggota Belakang';
            $alt_teks      = 'Kartu Anggota Belakang';
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tipe tidak valid']);
            exit;
        }

        // === Upload Handling ===
        $upload_path = FCPATH . 'uploads/kartu_anggota/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size']      = 10240;        // 10 MB
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('gambar')) {
            echo json_encode([
                'status'  => 'error',
                'message' => strip_tags($this->upload->display_errors())
            ]);
            exit;
        }

        $upload_data = $this->upload->data();
        $nama_file   = $upload_data['file_name'];

        // Validasi ukuran tepat 1040 x 650
        $info = getimagesize($upload_data['full_path']);
        if (!$info || $info[0] !== 1040 || $info[1] !== 650) {
            unlink($upload_data['full_path']);
            echo json_encode([
                'status'  => 'error',
                'message' => 'Ukuran gambar harus tepat 1040 × 650 piksel. Dimensi terdeteksi: ' .
                             ($info ? $info[0] . '×' . $info[1] : 'tidak terdeteksi')
            ]);
            exit;
        }

        // === Proses Database ===
        $this->db->trans_start();

        // Non-aktifkan desain lama (jika ada)
        $this->Md_media->deactivate_old($jenismedia_id);

        $data = [
            'jenismedia_id' => $jenismedia_id,
            'judul'         => $nama_file,
            'deskripsi'     => '-',
            'alt_teks'      => $alt_teks,
            'tipe'          => 0,
            'tgl_upload'    => date('Y-m-d'),
            'tgl_perubahan' => date('Y-m-d'),
            'author'        => $this->session->userdata('username'),
            'status'        => 1
        ];

        $this->Md_media->addMedia($data);

        // Log aktivitas
        $log = [
            'user_id'     => $this->session->userdata('idsys'),
            'jenis_log'   => 'Admin',
            'jenis_akses' => 'Update',
            'status'      => 1,
            'keterangan'  => $this->session->userdata('username') . ' Mengganti Desain Kartu Anggota ' . $judul,
            'IP'          => $this->input->ip_address()
        ];
        $this->Md_log->addLog($log);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            unlink($upload_data['full_path']);
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data. Terjadi kesalahan sistem.']);
        } else {
            $this->db->trans_commit();
            echo json_encode([
                'status'  => 'success',
                'message' => 'Desain Kartu Anggota ' . $judul . ' berhasil diperbarui'
            ]);
        }
        exit;
    }
}