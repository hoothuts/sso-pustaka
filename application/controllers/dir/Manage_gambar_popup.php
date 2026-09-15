<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_gambar_popup extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->database();
        $this->load->model('Md_media');
        $this->load->model('Md_log');
        $this->load->helper('pkrlib_helper');

        if ($this->session->userdata('login_type') != 'admin') {
            logoutNow();
        }
    }

    public function index() {
        $page_data['page_access'] = "admin";
        $page_data['page_now']    = 'Konten Website';
        $page_data['page_title']  = 'Manage Gambar Pop Up';
        $page_data['page_name']   = 'manage_gambar_popup';
        $page_data['page_dir']    = 'manage_gambar_popup';
        $page_data['page_file']   = 'index';

        // hanya 1 jenis media
        $page_data['popup'] = $this->Md_media->get_active(5);

        $this->load->view('index', $page_data);
    }

    public function save() {

        $jenismedia_id = 5;
        $judul         = 'Gambar Pop Up';
        $alt_teks      = 'Gambar Pop Up';

        $upload_path = FCPATH . 'uploads/popup/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size']      = 10240;
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
        $full_path   = $upload_data['full_path'];
        $nama_file   = $upload_data['file_name'];

        // ===== RESIZE & COMPRESS =====
        $this->load->library('image_lib');

        $config_resize['image_library']  = 'gd2';
        $config_resize['source_image']   = $full_path;
        $config_resize['maintain_ratio'] = TRUE;
        $config_resize['quality']        = '70%';

        // batas max 900 px (width / height)
        if ($upload_data['image_width'] > $upload_data['image_height']) {
            $config_resize['width'] = 900;
        } else {
            $config_resize['height'] = 900;
        }

        $this->image_lib->initialize($config_resize);
        $this->image_lib->resize();
        $this->image_lib->clear();

        // ===== DATABASE =====
        $this->db->trans_start();

        // nonaktifkan lama
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

        // log
        $log = [
            'user_id'     => $this->session->userdata('idsys'),
            'jenis_log'   => 'Admin',
            'jenis_akses' => 'Update',
            'status'      => 1,
            'keterangan'  => 'Update Gambar Pop Up',
            'IP'          => $this->input->ip_address()
        ];
        $this->Md_log->addLog($log);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            unlink($full_path);
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data']);
        } else {
            $this->db->trans_commit();
            echo json_encode(['status' => 'success', 'message' => 'Gambar Pop Up berhasil diupdate']);
        }
        exit;
    }

    // ===== DELETE POPUP =====
    public function delete() {

        $jenismedia_id = 5;

        $this->Md_media->deactivate_old($jenismedia_id);

        echo json_encode([
            'status'  => 'success',
            'message' => 'Gambar Pop Up berhasil dihapus (dinonaktifkan)'
        ]);
    }
}