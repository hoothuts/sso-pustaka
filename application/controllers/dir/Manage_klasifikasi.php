<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_klasifikasi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->database();
        $this->load->model('Md_siperpus_klasifikasi');
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
        $page_data['page_title']  = 'Manage Klasifikasi Buku';
        $page_data['page_name']   = 'manage_klasifikasi';
        $page_data['page_dir']    = 'manage_klasifikasi';
        $page_data['page_file']   = 'index';

        $this->load->view('index', $page_data);
    }

    public function fetch() {
        $datatable = $this->input->post('datatable');
        $search = isset($datatable['query']['generalSearch']) ? $datatable['query']['generalSearch'] : '';
        $page = isset($datatable['pagination']['page']) ? (int)$datatable['pagination']['page'] : 1;
        $perpage = isset($datatable['pagination']['perpage']) ? (int)$datatable['pagination']['perpage'] : 10;
        $sort = isset($datatable['sort']['sort']) ? $datatable['sort']['sort'] : 'ASC';
        $field = isset($datatable['sort']['field']) ? $datatable['sort']['field'] : 'id';

        $offset = ($page - 1) * $perpage;

        $list = $this->Md_siperpus_klasifikasi->get_datatables($search, $perpage, $offset, $field, $sort);
        $total = $this->Md_siperpus_klasifikasi->count_filtered($search);

        $data = [];
        $no = $offset;
        foreach ($list as $row) {
            $no++;
            $data[] = [
                'number'      => $no,
                'id_enc'          => urlencode(base64_encode($row->id)),
                'id'      => $row->id,
                'nama'        => $row->nama,
                'kode_warna'  => $row->kode_warna ? $row->kode_warna : '-',
                'status'      => $row->status
            ];
        }

        $output = [
            "meta" => [
                "page"    => $page,
                "pages"   => ceil($total / $perpage),
                "perpage" => $perpage,
                "total"   => $total,
                "sort"    => $sort,
                "field"   => $field
            ],
            "data" => $data
        ];

        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function form($mode = 'add', $id_enc = null) {
        $page_data['page_access'] = "admin";
        $page_data['page_now']    = 'Data Referensi';
        $page_data['page_title']  = 'Klasifikasi Buku';
        $page_data['is_view_mode'] = false;
        
        if ($mode == 'edit' || $mode == 'view') {
            $id = base64_decode(urldecode($id_enc));
            if ($id===null or $id==='') show_404();

            $page_data['edit_data'] = $this->Md_siperpus_klasifikasi->get_by_id($id);
            if (!$page_data['edit_data']) show_404();
            
            if($mode=='view'){
                $page_data['is_view_mode'] = ($mode == 'view');
            }
            
        }
        //var_dump($page_data['is_view_mode']);die;
        $page_data['page_file'] = 'form';
        $page_data['page_name'] = 'manage_klasifikasi';
        $page_data['page_dir']  = 'manage_klasifikasi';

        $this->load->view('index', $page_data);
    }

    public function save() {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('nama', 'Nama Klasifikasi', 'required|trim|min_length[3]|max_length[50]');
        $this->form_validation->set_rules('kode_warna', 'Kode Warna', 'required|trim|callback_valid_hex_color');

        $this->form_validation->set_message('required', '%s tidak boleh kosong');
        $this->form_validation->set_message('min_length', '%s minimal 3 karakter');
        $this->form_validation->set_message('max_length', '%s maksimal 50 karakter');
        $this->form_validation->set_message('valid_hex_color', '%s harus format warna hex yang valid (contoh: #FF0000)');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status' => 'error',
                'message' => validation_errors('<p class="text-danger">', '</p>')
            ]);
            exit;
        }

        $id_enc = $this->input->post('id_for_edit');
        $id_input = $this->input->post('id');
        $nama = $this->input->post('nama');
        $kode_warna = strtoupper($this->input->post('kode_warna'));

        $data = [
            'nama' => $nama,
            'kode_warna' => $kode_warna,
            'status' => 1
        ];

        $this->db->trans_start();

        $is_update = !empty($id_enc);

        if ($is_update) {
            // Mode UPDATE
            $id = base64_decode(urldecode($id_enc));

            // Pengecekan ID yang lebih aman & benar
            if ($id === false || $id === '' || $id === null) {
                $this->db->trans_rollback();
                echo json_encode(['status' => 'error', 'message' => 'ID tidak valid atau tidak ditemukan']);
                exit;
            }

            // Update data
            $this->Md_siperpus_klasifikasi->update($id, $data);

            $log_ket = $this->session->userdata('username') . ' Melakukan Update Klasifikasi Buku ' . $nama;
            $jenis_akses = 'Update';
        } else {
            // Mode ADD
            // Cek duplikat ID
            $existing = $this->Md_siperpus_klasifikasi->get_by_id($id_input);
            if ($existing) {
                $this->db->trans_rollback();
                echo json_encode([
                    'status' => 'error',
                    'message' => 'ID Klasifikasi "' . $id_input . '" sudah digunakan. Silakan gunakan ID lain.'
                ]);
                exit;
            }

            $data['id'] = $id_input;

            $this->Md_siperpus_klasifikasi->insert($data);

            $log_ket = $this->session->userdata('username') . ' Melakukan Add Klasifikasi Buku ' . $nama;
            $jenis_akses = 'Add';
        }

        // Log selalu dibuat di sini (dalam transaksi)
        $log = [
            'user_id' => $this->session->userdata('idsys'),
            'jenis_log' => 'Admin',
            'jenis_akses' => $jenis_akses,
            'status' => 1,
            'keterangan' => $log_ket,
            'IP' => $this->input->ip_address()
        ];
        $this->Md_log->addLog($log);

        // Final check transaksi
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menyimpan data. Terjadi kesalahan sistem.'
            ]);
        } else {
            $this->db->trans_commit();
            $msg = $is_update ? 'Klasifikasi berhasil diperbarui' : 'Klasifikasi berhasil ditambahkan';
            echo json_encode([
                'status' => 'success',
                'message' => $msg
            ]);
        }
        exit;
    }

    public function delete() {
        $id_enc = $this->input->post('id');
        $id = base64_decode(urldecode($id_enc));

        // Pengecekan ID yang lebih aman (boleh '0', tapi tidak boleh null/empty)
        if ($id === false || $id === '' || $id === null) {
            echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
            exit;
        }

        // Mulai transaksi
        $this->db->trans_start();

        // Soft delete klasifikasi
        $this->Md_siperpus_klasifikasi->soft_delete($id);

        // Ambil data klasifikasi untuk log (masih dalam transaksi, aman)
        $klas = $this->Md_siperpus_klasifikasi->get_by_id($id);
        if (!$klas) {
            $this->db->trans_rollback();
            echo json_encode(['status' => 'error', 'message' => 'Klasifikasi tidak ditemukan']);
            exit;
        }

        // Buat log
        $log = [
            'user_id' => $this->session->userdata('idsys'),
            'jenis_log' => 'Admin',
            'jenis_akses' => 'Hapus',
            'status' => 1,
            'keterangan' => $this->session->userdata('username') . ' Melakukan Hapus Klasifikasi Buku ' . $klas->nama,
            'IP' => $this->input->ip_address()
        ];
        $this->Md_log->addLog($log);

        // Cek status transaksi
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menghapus klasifikasi. Terjadi kesalahan sistem.'
            ]);
        } else {
            $this->db->trans_commit();
            echo json_encode([
                'status' => 'success',
                'message' => 'Klasifikasi berhasil dihapus'
            ]);
        }
        exit;
    }

    public function valid_hex_color($str) {
        // Boleh dengan atau tanpa #, 3 atau 6 digit hex
        if (preg_match('/^#?([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $str)) {
            return TRUE;
        }

        $this->form_validation->set_message('valid_hex_color', '%s harus format warna hex yang valid (contoh: #FF0000 atau #F00)');
        return FALSE;
    }
}