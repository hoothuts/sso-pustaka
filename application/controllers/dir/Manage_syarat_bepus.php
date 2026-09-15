<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_syarat_bepus extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->database();
        $this->load->model('Md_bepus_syarat');
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
        $page_data['page_title']  = 'Manage Syarat Bebas Pustaka';
        $page_data['page_name']   = 'manage_syarat_bepus';
        $page_data['page_dir']    = 'manage_syarat_bepus';
        $page_data['page_file']   = 'index';

        // Ambil semua data aktif dengan struktur bertingkat
        $page_data['syarat_list'] = $this->Md_bepus_syarat->get_all_active_hierarchical();

        $this->load->view('index', $page_data);
    }

    public function form($mode = 'add', $id_enc = null) {
        $page_data['page_access'] = "admin";
        $page_data['page_now']    = 'Request Bebas Pustaka';
        $page_data['page_title']  = 'Syarat Bebas Pustaka';
        $page_data['is_view_mode'] = false;
        $page_data['parents'] = $this->Md_bepus_syarat->get_parent_options(); // untuk dropdown parent

        if ($mode == 'edit' || $mode == 'view') {
            $id = decrypt($id_enc);
            if ($id === false || $id === '') show_404();

            $page_data['edit_data'] = $this->Md_bepus_syarat->get_by_id($id);
            if (!$page_data['edit_data']) show_404();

            if ($mode == 'view') {
                $page_data['is_view_mode'] = true;
            }
        }

        $page_data['page_file'] = 'form';
        $page_data['page_name'] = 'manage_syarat_bepus';
        $page_data['page_dir']  = 'manage_syarat_bepus';

        $this->load->view('index', $page_data);
    }

    public function save() {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('persyaratan', 'Persyaratan', 'required|trim');
        $this->form_validation->set_rules('urutan', 'Urutan', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('level', 'Level', 'required|in_list[1,2]');
        $this->form_validation->set_rules('is_isian', 'Is Isian', 'required|in_list[Ya,Tidak]');
        $this->form_validation->set_rules('is_aktif', 'Is Aktif', 'required|in_list[Ya,Tidak]');

        $this->form_validation->set_message('required', '%s tidak boleh kosong');
        $this->form_validation->set_message('integer', '%s harus berupa angka');
        $this->form_validation->set_message('greater_than', '%s minimal 1');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status' => 'error',
                'message' => validation_errors('<p class="text-danger">', '</p>')
            ]);
            exit;
        }

        $id_enc     = $this->input->post('id_for_edit');
        $persyaratan = trim($this->input->post('persyaratan'));
        $urutan      = (int)$this->input->post('urutan');
        $level       = (int)$this->input->post('level');
        $parent_id   = $this->input->post('parent_id') ? (int)$this->input->post('parent_id') : null;
        $is_isian    = $this->input->post('is_isian');
        $is_aktif    = $this->input->post('is_aktif');

        // Jika level = 2 (Child), parent_id wajib diisi
        if ($level == 2 && empty($parent_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Parent harus dipilih untuk syarat Child (Level 2)']);
            exit;
        }

        // Cek duplikat persyaratan (kecuali saat edit diri sendiri)
        $existing = $this->Md_bepus_syarat->check_duplicate($persyaratan, $id_enc ? decrypt($id_enc) : null);
        if ($existing) {
            echo json_encode(['status' => 'error', 'message' => 'Persyaratan dengan teks yang sama sudah ada']);
            exit;
        }

        $data = [
            'persyaratan'     => $persyaratan,
            'urutan'          => $urutan,
            'level'           => $level,
            'parent_id'       => $parent_id,
            'is_isian'        => $is_isian,
            'is_aktif'        => $is_aktif,
            'tgl_last_update' => date('Y-m-d H:i:s'),
            'last_update_by'  => $this->session->userdata('idsys')
        ];

        $this->db->trans_start();

        $is_update = !empty($id_enc);

        if ($is_update) {
            $id = decrypt($id_enc);
            if ($id === false) {
                $this->db->trans_rollback();
                echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
                exit;
            }

            $this->Md_bepus_syarat->update($id, $data);

            $log_ket = $this->session->userdata('username') . ' Melakukan Update Syarat Bebas Pustaka';
            $jenis_akses = 'Update';
        } else {
            $data['author']     = $this->session->userdata('idsys');
            $data['tgl_post']   = date('Y-m-d H:i:s');
            $data['status']     = 1;
            $this->Md_bepus_syarat->insert($data);

            $log_ket = $this->session->userdata('username') . ' Melakukan Tambah Syarat Bebas Pustaka';
            $jenis_akses = 'Add';
        }

        // Log
        $log = [
            'user_id'     => $this->session->userdata('idsys'),
            'jenis_log'   => 'Admin',
            'jenis_akses' => $jenis_akses,
            'status'      => 1,
            'keterangan'  => $log_ket,
            'IP'          => $this->input->ip_address()
        ];
        $this->Md_log->addLog($log);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data']);
        } else {
            $this->db->trans_commit();
            $msg = $is_update ? 'Syarat berhasil diperbarui' : 'Syarat berhasil ditambahkan';
            echo json_encode(['status' => 'success', 'message' => $msg]);
        }
        exit;
    }

    public function nonaktifkan() {
        $id_enc = $this->input->post('id');
        $id = decrypt($id_enc);

        if ($id === false || $id === '') {
            echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
            exit;
        }

        $this->db->trans_start();
        $this->Md_bepus_syarat->nonaktifkan($id);

        $syarat = $this->Md_bepus_syarat->get_by_id($id);
        $log = [
            'user_id'     => $this->session->userdata('idsys'),
            'jenis_log'   => 'Admin',
            'jenis_akses' => 'Update',
            'status'      => 1,
            'keterangan'  => $this->session->userdata('username') . ' Menonaktifkan Syarat Bebas Pustaka: ' . ($syarat ? $syarat->persyaratan : ''),
            'IP'          => $this->input->ip_address()
        ];
        $this->Md_log->addLog($log);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(['status' => 'error', 'message' => 'Gagal menonaktifkan']);
        } else {
            $this->db->trans_commit();
            echo json_encode(['status' => 'success', 'message' => 'Syarat berhasil dinonaktifkan']);
        }
        exit;
    }

    public function delete() {
        $id_enc = $this->input->post('id');
        $id = decrypt($id_enc);

        if ($id === false || $id === '') {
            echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
            exit;
        }

        $this->db->trans_start();
        $this->Md_bepus_syarat->soft_delete($id);

        $syarat = $this->Md_bepus_syarat->get_by_id($id);
        $log = [
            'user_id'     => $this->session->userdata('idsys'),
            'jenis_log'   => 'Admin',
            'jenis_akses' => 'Hapus',
            'status'      => 1,
            'keterangan'  => $this->session->userdata('username') . ' Menghapus Syarat Bebas Pustaka: ' . ($syarat ? $syarat->persyaratan : ''),
            'IP'          => $this->input->ip_address()
        ];
        $this->Md_log->addLog($log);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus']);
        } else {
            $this->db->trans_commit();
            echo json_encode(['status' => 'success', 'message' => 'Syarat berhasil dihapus']);
        }
        exit;
    }
    
    public function preview_form() {
        $page_data['page_access'] = "admin";
        $page_data['page_now']    = 'Request Bebas Pustaka';
        $page_data['page_title']  = 'Preview Tampilan Form Syarat Bebas Pustaka';
        $page_data['page_name']   = 'manage_syarat_bepus';
        $page_data['page_dir']    = 'manage_syarat_bepus';
        $page_data['page_file']   = 'preview_form';

        // Ambil data dengan struktur bertingkat (sama seperti index)
        $page_data['syarat_list'] = $this->Md_bepus_syarat->get_active_for_preview();

        $this->load->view('index', $page_data);
    }
    
}