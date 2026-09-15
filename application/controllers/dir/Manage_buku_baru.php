<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_buku_baru extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->database();
        $this->load->library('form_validation');
        $this->load->model('Md_buku_baru');
        $this->load->model('Md_log');
        
        $this->load->helper('pkrlib_helper');

        if ($this->session->userdata('login_type') != 'admin') {
            logoutNow();
        }
    }

    public function index() {
        $page_data['page_name'] = 'manage_buku_baru';
        $page_data['page_dir']  = 'manage_buku_baru';
        $page_data['page_file'] = 'index';
        $page_data['page_access'] = "admin";
        $page_data['page_now']    = 'Konten Website';
        $page_data['page_title']  = 'Manage Buku Baru';

        $this->load->view('index', $page_data);
    }

    public function fetch() {
        $datatable = $this->input->post('datatable');

        $search = $datatable['query']['generalSearch'] ?? '';
        $page   = $datatable['pagination']['page'] ?? 1;
        $perpage= $datatable['pagination']['perpage'] ?? 10;
        $sort   = $datatable['sort']['sort'] ?? 'DESC';
        $field  = $datatable['sort']['field'] ?? 'bukubaru_id';

        $offset = ($page - 1) * $perpage;

        $list = $this->Md_buku_baru->get_datatables($search, $perpage, $offset, $field, $sort);
        $total= $this->Md_buku_baru->count_filtered($search);

        $data = [];
        $no = $offset;

        foreach ($list as $row) {
            $no++;
            $data[] = [
                'number' => $no,
                'id_enc' => urlencode(base64_encode($row->bukubaru_id)),
                'judul'  => $row->judul,
                'ISBN'   => $row->ISBN,
                'no_klas'=> $row->no_klas,
                'tahun_terbit'=> $row->tahun_terbit,
                'penulis'=> $row->penulis,
                'penerbit'=> $row->penerbit,
                'author' => $row->author,
                'tgl_post'=> $row->tgl_post,
                'is_tampil'=> $row->is_tampil,
                'cover' => $row->cover,
                'status' => $row->status
            ];
        }

        echo json_encode([
            "meta" => [
                "page"=>$page,
                "pages"=>ceil($total/$perpage),
                "perpage"=>$perpage,
                "total"=>$total
            ],
            "data"=>$data
        ]);
    }

    public function form($mode='add', $id_enc=null) {
        $page_data['is_view_mode'] = false;

        if ($mode != 'add') {
            $id = base64_decode(urldecode($id_enc));
            $page_data['edit_data'] = $this->Md_buku_baru->get_by_id($id);

            if ($mode == 'view') {
                $page_data['is_view_mode'] = true;
            }
        }

        $page_data['page_name'] = 'manage_buku_baru';
        $page_data['page_dir']  = 'manage_buku_baru';
        $page_data['page_file'] = 'form';
        
        $page_data['page_access'] = "admin";
        $page_data['page_now']    = 'Konten Website';
        $page_data['page_title']  = 'Manage Buku Baru';

        $this->load->view('index', $page_data);
    }
    
    public function save() {
        // Set rules validasi
        $this->form_validation->set_rules('buku_id', 'ID Buku', 'required|trim');

        // Custom message
        $this->form_validation->set_message('required', '%s tidak boleh kosong');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status'  => 'error',
                'message' => validation_errors('<p class="text-danger">', '</p>')
            ]);
            exit;
        }
        
        $id_enc  = $this->input->post('id_for_edit');
        $buku_id = trim($this->input->post('buku_id'));

        $data = [
            'buku_id' => $buku_id
        ];

        $this->db->trans_start();

        $is_update = !empty($id_enc);

        if ($is_update) {
            // ==================== UPDATE ====================
            $id = base64_decode(urldecode($id_enc));

            if ($id === false || $id === '' || !is_numeric($id)) {
                $this->db->trans_rollback();
                echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
                exit;
            }

            // Cek duplikat buku_id (kecuali record yang sedang diupdate)
            $existing = $this->Md_buku_baru->cek_duplikat_buku_id($buku_id, $id);
            if ($existing) {
                $this->db->trans_rollback();
                echo json_encode(['status' => 'error', 'message' => 'Buku yang dipilih sudah terdaftar sebelumnya!']);
                exit;
            }

            $this->Md_buku_baru->update($id, $data);

            $log_ket = $this->session->userdata('username') . ' Melakukan Update Buku Baru ID: ' . $buku_id;
            $jenis_akses = 'Update';

        } else {
            // ==================== INSERT ====================
            // Cek duplikat buku_id pada data aktif
            $existing = $this->Md_buku_baru->cek_duplikat_buku_id($buku_id);
            if ($existing) {
                $this->db->trans_rollback();
                echo json_encode(['status' => 'error', 'message' => 'Buku yang dipilih sudah terdaftar sebelumnya!']);
                exit;
            }

            $data['author']   = $this->session->userdata('idsys');
            $data['tgl_post'] = date('Y-m-d H:i:s');
            $data['status']   = 1;

            $this->Md_buku_baru->insert($data);

            $log_ket = $this->session->userdata('username') . ' Melakukan Tambah Buku Baru ID: ' . $buku_id;
            $jenis_akses = 'Add';
        }

        // ==================== LOG AKTIVITAS ====================
        $log = [
            'user_id'     => $this->session->userdata('idsys'),
            'jenis_log'   => 'Admin',
            'jenis_akses' => $jenis_akses,
            'status'      => 1,
            'keterangan'  => $log_ket,
            'IP'          => $this->input->ip_address()
        ];
        $this->Md_log->addLog($log);

        // Final transaksi
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode([
                'status'  => 'error',
                'message' => 'Gagal menyimpan data. Terjadi kesalahan sistem.'
            ]);
        } else {
            $this->db->trans_commit();
            $msg = $is_update ? 'Data berhasil diperbarui' : 'Data berhasil ditambahkan';
            echo json_encode([
                'status'  => 'success',
                'message' => $msg
            ]);
        }
        exit;
    }

    public function delete() {
        $id_enc = $this->input->post('id');
        $id = base64_decode(urldecode($id_enc));

        // Validasi ID yang lebih aman
        if ($id === false || $id === '' || !is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
            exit;
        }

        $this->db->trans_start();

        // Soft delete
        $this->Md_buku_baru->soft_delete($id);

        // Buat log
        $log = [
            'user_id'     => $this->session->userdata('idsys'),
            'jenis_log'   => 'Admin',
            'jenis_akses' => 'Hapus',
            'status'      => 1,
            'keterangan'  => $this->session->userdata('username') . ' Melakukan Hapus Buku Baru ID: ' . $id,
            'IP'          => $this->input->ip_address()
        ];
        $this->Md_log->addLog($log);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode([
                'status'  => 'error',
                'message' => 'Gagal menghapus data. Terjadi kesalahan sistem.'
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

    /*public function save() {
        $id_enc = $this->input->post('id_for_edit');

        $data = [
            'buku_id' => $this->input->post('buku_id'),
            'author'  => $this->session->userdata('idsys'),
            'tgl_post'=> date('Y-m-d H:i:s'),
            'status'  => 1
        ];

        if ($id_enc) {
            $id = base64_decode(urldecode($id_enc));
            $this->Md_buku_baru->update($id, $data);
            $msg = 'Update berhasil';
        } else {
            $this->Md_buku_baru->insert($data);
            $msg = 'Tambah berhasil';
        }

        echo json_encode(['status'=>'success','message'=>$msg]);
    }

    public function delete() {
        $id = base64_decode(urldecode($this->input->post('id')));
        $this->Md_buku_baru->soft_delete($id);

        echo json_encode(['status'=>'success','message'=>'Data dihapus']);
    }
  */
    public function search_buku()
    {
        $search = $this->input->get('searchtext');

        $list = $this->Md_buku_baru->search_buku($search);

        $data = [];
        foreach ($list as $row) {
            $data[] = [
                'id' => $row->buku_id,
                'text' => '[' . $row->ISBN . '] ' . $row->judul
            ];
        }

        echo json_encode(['items' => $data]);
    }
    
    public function get_detail()
    {
        $id = base64_decode(urldecode($this->input->post('id')));

        $this->db->select('bb.*, b.judul, b.ISBN');
        $this->db->from('buku_baru bb');
        $this->db->join('siperpus_buku b', 'b.buku_id = bb.buku_id', 'left');
        $this->db->where('bb.bukubaru_id', $id);

        $data = $this->db->get()->row();

        if (!$data) {
            echo json_encode(['status' => 'error']);
            return;
        }

        echo json_encode([
            'status' => 'success',
            'data' => $data
        ]);
    }
    
       public function toggle_tampil() {
        $id_enc = $this->input->post('id');
        $id = base64_decode(urldecode($id_enc));

        // Validasi ID
        if ($id === false || $id === '' || !is_numeric($id)) {
            echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
            exit;
        }

        $this->db->trans_start();

        // Ambil data existing
        $data = $this->Md_buku_baru->get_by_id($id);
        if (!$data) {
            $this->db->trans_rollback();
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
            exit;
        }

        // Toggle status
        $new_status = ($data->is_tampil == 'Ya') ? 'Tidak' : 'Ya';

        $this->Md_buku_baru->update($id, ['is_tampil' => $new_status]);

        // Logging
        $log_ket = $this->session->userdata('username') . ' Melakukan Toggle Status Buku Baru ID: ' . $id . ' menjadi ' . $new_status;
        
        $log = [
            'user_id'     => $this->session->userdata('idsys'),
            'jenis_log'   => 'Admin',
            'jenis_akses' => 'Update',
            'status'      => 1,
            'keterangan'  => $log_ket,
            'IP'          => $this->input->ip_address()
        ];
        $this->Md_log->addLog($log);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode([
                'status'  => 'error',
                'message' => 'Gagal mengubah status. Terjadi kesalahan sistem.'
            ]);
        } else {
            $this->db->trans_commit();
            echo json_encode([
                'status'  => 'success',
                'message' => 'Status berhasil diubah ke ' . $new_status
            ]);
        }
        exit;
    }

}