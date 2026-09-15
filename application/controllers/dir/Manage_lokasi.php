<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_lokasi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->database();
        $this->load->model('Md_lokasi');
        $this->load->model('Md_log');
        $this->load->helper('pkrlib_helper');
        $this->load->helper('encryption_id_helper');

        if ($this->session->userdata('login_type') != 'admin') {
            logoutNow();
        }
    }

    public function index() {
        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'Data Referensi';
        $page_data['page_title'] = 'Manage Lokasi';
        $page_data['page_name'] = 'manage_lokasi';
        $page_data['page_dir'] = 'manage_lokasi';
        $page_data['page_file'] = 'index';
        $this->load->view('index', $page_data);
    }

    // Datatable JSON untuk tab Kampus
    public function list_kampus() {
        $datatable = $this->input->post('datatable');
        $search = isset($datatable['query']['generalSearch']) ? $datatable['query']['generalSearch'] : '';
        $page = isset($datatable['pagination']['page']) ? (int) $datatable['pagination']['page'] : 1;
        $perpage = isset($datatable['pagination']['perpage']) ? (int) $datatable['pagination']['perpage'] : 10;
        $sort = isset($datatable['sort']['sort']) ? $datatable['sort']['sort'] : 'ASC';
        $field = isset($datatable['sort']['field']) ? $datatable['sort']['field'] : 'lokasikampus_id';

        $offset = ($page - 1) * $perpage;

        $list = $this->Md_lokasi->get_kampus($search, $perpage, $offset, $field, $sort);
        $total = $this->Md_lokasi->count_kampus($search);

        $data = [];
        $no = $offset;
        foreach ($list as $row) {
            $no++;
            $data[] = [
                'number' => $no,
                'lokasikampus_id' => urlencode(base64_encode($row->lokasikampus_id)),
                'nama_kampus' => $row->nama_kampus,
                'alamat' => $row->alamat,
                'jumlah_koleksi' => $row->jumlah_koleksi,
                'tgl_post' => $row->tgl_post,
                'author' => $row->author,
                'status' => $row->status
            ];
        }

        $result = [
            "meta" => [
                "page" => $page,
                "pages" => ceil($total / $perpage),
                "perpage" => $perpage,
                "total" => $total,
                "sort" => $sort,
                "field" => $field
            ],
            "data" => $data
        ];

        echo json_encode($result);
        exit;
    }

    // Datatable JSON untuk tab Gedung
    public function list_gedung() {
        $datatable = $this->input->post('datatable');
        $search = isset($datatable['query']['generalSearch']) ? $datatable['query']['generalSearch'] : '';
        $page = isset($datatable['pagination']['page']) ? (int) $datatable['pagination']['page'] : 1;
        $perpage = isset($datatable['pagination']['perpage']) ? (int) $datatable['pagination']['perpage'] : 10;
        $sort = isset($datatable['sort']['sort']) ? $datatable['sort']['sort'] : 'ASC';
        $field = isset($datatable['sort']['field']) ? $datatable['sort']['field'] : 'lokasigedung_id';

        $offset = ($page - 1) * $perpage;

        $list = $this->Md_lokasi->get_gedung($search, $perpage, $offset, $field, $sort);
        $total = $this->Md_lokasi->count_gedung($search);

        $data = [];
        $no = $offset;
        foreach ($list as $row) {
            $no++;
            $data[] = [
                'number' => $no,
                'lokasigedung_id' => urlencode(base64_encode($row->lokasigedung_id)),
                'nama_gedung' => $row->nama_gedung,
                'nama_kampus' => $row->nama_kampus, // join dari kampus
                'keterangan' => $row->keterangan,
                'jumlah_koleksi' => $row->jumlah_koleksi,
                'tgl_post' => $row->tgl_post,
                'author' => $row->author,
                'status' => $row->status
            ];
        }

        $result = [
            "meta" => [
                "page" => $page,
                "pages" => ceil($total / $perpage),
                "perpage" => $perpage,
                "total" => $total,
                "sort" => $sort,
                "field" => $field
            ],
            "data" => $data
        ];

        echo json_encode($result);
        exit;
    }

    // Datatable JSON untuk tab Rak
    public function list_rak() {
        $datatable = $this->input->post('datatable');
        $search = isset($datatable['query']['generalSearch']) ? $datatable['query']['generalSearch'] : '';
        $page = isset($datatable['pagination']['page']) ? (int) $datatable['pagination']['page'] : 1;
        $perpage = isset($datatable['pagination']['perpage']) ? (int) $datatable['pagination']['perpage'] : 10;
        $sort = isset($datatable['sort']['sort']) ? $datatable['sort']['sort'] : 'ASC';
        $field = isset($datatable['sort']['field']) ? $datatable['sort']['field'] : 'lokasirak_id';

        $offset = ($page - 1) * $perpage;

        $list = $this->Md_lokasi->get_rak($search, $perpage, $offset, $field, $sort);
        $total = $this->Md_lokasi->count_rak($search);

        $data = [];
        $no = $offset;
        foreach ($list as $row) {
            $no++;
            $data[] = [
                'number' => $no,
                'lokasirak_id' => urlencode(base64_encode($row->lokasirak_id)),
                'nama_rak' => $row->nama_rak,
                'nama_gedung' => $row->nama_gedung, // join dari gedung
                'nama_kampus' => $row->nama_kampus, // join dari kampus
                'keterangan' => $row->keterangan,
                'jumlah_koleksi' => $row->jumlah_koleksi,
                'tgl_post' => $row->tgl_post,
                'author' => $row->author,
                'status' => $row->status
            ];
        }

        $result = [
            "meta" => [
                "page" => $page,
                "pages" => ceil($total / $perpage),
                "perpage" => $perpage,
                "total" => $total,
                "sort" => $sort,
                "field" => $field
            ],
            "data" => $data
        ];

        echo json_encode($result);
        exit;
    }

    public function form($type = '', $id = '', $mode = 'add') {
        //log_message('debug', 'Form called - type: "' . $type . '", id: "' . $id . '", mode: "' . $mode . '"');
        
        $page_data['type'] = $type;
        $page_data['mode'] = $mode; // 'add', 'edit', 'view'
        $page_data['is_view_mode'] = ($mode == 'view');
        $page_data['is_edit_mode'] = ($mode == 'edit');

        // Kirim data dropdown kampus
        $page_data['kampus_options'] = $this->Md_lokasi->get_kampus_options();

        if ($id && ($mode == 'edit' || $mode == 'view')) {
            $id = base64_decode(urldecode($id));
            if ($id === false || !is_numeric($id)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'ID tidak valid atau gagal didecode'
                ]);
                exit;
            }
        
            $page_data['edit_data'] = $this->Md_lokasi->get_by_id($type, $id);
            if (!$page_data['edit_data']) {
                show_404(); // atau redirect dengan pesan error
            }

            if ($type == 'gedung' || $type == 'rak') {
                $kampus_id = $page_data['edit_data']->lokasikampus_id ?? 0;
                $page_data['gedung_options'] = $this->Md_lokasi->get_gedung_by_kampus($kampus_id);
            }
        } else {
            $page_data['gedung_options'] = [];
        }

        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'Data Referensi';
        $page_data['page_title'] = 'Manage Lokasi';
        $page_data['page_name'] = 'manage_lokasi';
        $page_data['page_dir'] = 'manage_lokasi';
        $page_data['page_file'] = 'form';
        $this->load->view('index', $page_data);
    }

    public function save() {
        $this->load->library('form_validation');

        $type = $this->input->post('type');
        $id_for_edit = $this->input->post('id_for_edit');

        // Decode ID jika sudah di-encode di view
        if (!empty($id_for_edit)) {
            $id_for_edit = base64_decode(urldecode($id_for_edit));
        }

        $data = [];
        $is_update = !empty($id_for_edit);

        // Validasi umum
        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');

        if ($type == 'kampus') {
            $data = [
                'nama_kampus' => $this->input->post('nama'),
                'alamat' => $this->input->post('keterangan'),
            ];
            if (!$is_update) {
                $data['tgl_post'] = date('Y-m-d H:i:s');
                $data['author'] = $this->session->userdata('idsys');
                $data['status'] = 1;
            }
        } elseif ($type == 'gedung') {
            $this->form_validation->set_rules('lokasikampus_id', 'Lokasi Kampus', 'required');
            $data = [
                'lokasikampus_id' => $this->input->post('lokasikampus_id'),
                'nama_gedung' => $this->input->post('nama'),
                'keterangan' => $this->input->post('keterangan'),
            ];
            if (!$is_update) {
                $data['tgl_post'] = date('Y-m-d H:i:s');
                $data['author'] = $this->session->userdata('idsys');
                $data['status'] = 1;
            }
        } elseif ($type == 'rak') {
            $this->form_validation->set_rules('lokasikampus_id_rak', 'Lokasi Kampus', 'required');
            $this->form_validation->set_rules('lokasigedung_id', 'Lokasi Gedung', 'required');
            $data = [
                'lokasigedung_id' => $this->input->post('lokasigedung_id'),
                'nama_rak' => $this->input->post('nama'),
                'keterangan' => $this->input->post('keterangan'),
            ];
            if (!$is_update) {
                $data['tgl_post'] = date('Y-m-d H:i:s');
                $data['author'] = $this->session->userdata('idsys');
                $data['status'] = 1;
            }
        }else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Jenis lokasi tidak valid'
            ]);
            exit;
        }

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status' => 'error',
                'message' => validation_errors()
            ]);
            exit;
        }

        $this->db->trans_begin();

        if ($is_update) {
            $updated = $this->Md_lokasi->update($type, $id_for_edit, $data);
            $message = $updated ? 'Lokasi berhasil diperbarui' : 'Tidak ada perubahan atau ID tidak ditemukan';
        } else {
            $inserted = $this->Md_lokasi->insert($type, $data);
            $message = $inserted ? 'Lokasi berhasil ditambahkan' : 'Gagal menambahkan lokasi';
        }

        // Log (selalu dicatat, baik add maupun edit)
        $log = [
            'user_id' => $this->session->userdata('idsys'),
            'jenis_log' => 'Admin',
            'jenis_akses' => $is_update ? 'Edit' : 'Add',
            'status' => 1,
            'keterangan' => $this->session->userdata('username') . ' Melakukan ' . ($is_update ? 'Edit' : 'Tambah') . ' Lokasi ' . $type,
            'IP' => $this->input->ip_address()
        ];
        $this->Md_log->addLog($log);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menyimpan lokasi. Terjadi kesalahan sistem.'
            ]);
        } else {
            $this->db->trans_commit();
            echo json_encode([
                'status' => 'success',
                'message' => $message
            ]);
        }
        exit;
    }

    public function delete() {
        $id_encoded = $this->input->post('id');
        $type = $this->input->post('type');

        if (!$id_encoded || !$type) {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID atau jenis tidak valid'
            ]);
            exit;
        }

        // Decode ID (sama seperti di save)
        $id = base64_decode(urldecode($id_encoded));
        if ($id === false || !is_numeric($id)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID tidak valid atau gagal didecode'
            ]);
            exit;
        }

        // Mulai transaksi
        $this->db->trans_begin();

        $success = false;
        $message = '';

        if ($type == 'kampus') {
            // Cek apakah ada gedung terkait
            $has_gedung = $this->Md_lokasi->has_related_gedung($id); 
            if ($has_gedung) {
                $message = 'Gagal menghapus! Kampus ini masih memiliki gedung terkait.';
            } else {
                $success = $this->Md_lokasi->soft_delete($type, $id);
                $message = $success ? 'Kampus berhasil dihapus' : 'Gagal menghapus kampus';
            }
        } elseif ($type == 'gedung') {
            // Cek apakah ada rak terkait
            $has_rak = $this->Md_lokasi->has_related_rak($id);
            if ($has_rak) {
                $message = 'Gagal menghapus! Gedung ini masih memiliki rak terkait.';
            } else {
                $success = $this->Md_lokasi->soft_delete($type, $id);
                $message = $success ? 'Gedung berhasil dihapus' : 'Gagal menghapus gedung';
            }
        } elseif ($type == 'rak') {
            // Cek apakah rak masih digunakan di inventaris
            $used_in_inventaris = $this->Md_lokasi->is_rak_used($id);
            if ($used_in_inventaris) {
                $message = 'Gagal menghapus! Rak ini masih digunakan di data inventaris.';
            } else {
                $success = $this->Md_lokasi->soft_delete($type, $id);
                $message = $success ? 'Rak berhasil dihapus' : 'Gagal menghapus rak';
            }
        } else {
            $message = 'Jenis lokasi tidak valid';
        }

        // Log (selalu dicatat, meskipun gagal)
        $log = [
            'user_id' => $this->session->userdata('idsys'),
            'jenis_log' => 'Admin',
            'jenis_akses' => 'Delete',
            'status' => $success ? 1 : 0,
            'keterangan' => $this->session->userdata('username') . ' Melakukan Hapus Lokasi ' . $type . ' ID ' . $id . ($success ? '' : ' (Gagal: ' . $message . ')'),
            'IP' => $this->input->ip_address()
        ];
        $this->Md_log->addLog($log);

        // Final check transaksi
        if ($this->db->trans_status() === FALSE || !$success) {
            $this->db->trans_rollback();
            echo json_encode([
                'status' => 'error',
                'message' => $message ?: 'Gagal menghapus lokasi. Terjadi kesalahan sistem.'
            ]);
        } else {
            $this->db->trans_commit();
            echo json_encode([
                'status' => 'success',
                'message' => $message
            ]);
        }
        exit;
    }

    // Get options for gedung (join kampus)
    public function get_gedung_options() {
        $kampus_id = $this->input->post('kampus_id');
        echo json_encode($this->Md_lokasi->get_gedung_by_kampus($kampus_id));
        exit;
    }

    // Get options for rak (join gedung)
    public function get_rak_options() {
        $gedung_id = $this->input->post('gedung_id');
        echo json_encode($this->Md_lokasi->get_rak_by_gedung($gedung_id));
        exit;
    }
}
