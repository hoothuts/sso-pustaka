<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_jurnal_akun extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        date_default_timezone_set('Asia/Jakarta');

        $this->load->database();
        $this->load->library('form_validation');

        $this->load->model('Md_jurnal_akun');
        $this->load->model('Md_log');

        $this->load->helper('pkrlib_helper');
        $this->load->helper('encryption_id');

        if ($this->session->userdata('login_type') != 'admin') {
            logoutNow();
        }
    }

    public function index()
    {
        $page_data['page_name']   = 'manage_jurnal_akun';
        $page_data['page_dir']    = 'manage_jurnal_akun';
        $page_data['page_file']   = 'index';

        $page_data['page_access'] = "admin";
        $page_data['page_now']    = 'Jurnal Berlangganan';
        $page_data['page_title']  = 'Manage Akun Jurnal';

        $this->load->view('index', $page_data);
    }

    public function fetch()
    {
        $datatable = $this->input->post('datatable');

        $search = $datatable['query']['generalSearch'] ?? '';

        $vendor = $this->input->post('vendor');

        $page    = $datatable['pagination']['page'] ?? 1;
        $perpage = $datatable['pagination']['perpage'] ?? 10;

        $sort  = $datatable['sort']['sort'] ?? 'DESC';
        $field = $datatable['sort']['field'] ?? 'jurnalakun_id';

        $offset = ($page - 1) * $perpage;

        $list = $this->Md_jurnal_akun->get_datatables(
            $search,
            $vendor,
            $perpage,
            $offset,
            $field,
            $sort
        );

        $total = $this->Md_jurnal_akun->count_filtered(
            $search,
            $vendor
        );

        $data = [];

        $no = $offset;

        foreach($list as $row){

            $no++;

            $data[] = [
                'number'        => $no,
                'id_enc'        => urlencode(base64_encode($row->jurnalakun_id)),
                'nama_vendor'   => $row->nama_vendor,
                'username'      => $row->username,
                'is_used'       => $row->is_used,
                'last_used'     => $row->last_used,
                'total_used'    => $row->total_used,
                'author'        => $row->author,
                'tgl_post'      => $row->tgl_post
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

    public function search_vendor()
    {
        $search = $this->input->get('searchtext');

        $this->db->select('jurnalvendor_id,nama_vendor');

        $this->db->from('jurnal_vendor');

        $this->db->where('status', 1);

        if($search){
            $this->db->like('nama_vendor', $search);
        }

        $this->db->order_by('nama_vendor', 'ASC');

        $this->db->limit(50);

        $list = $this->db->get()->result();

        $data = [];

        foreach($list as $row){

            $data[] = [
                'id'   => $row->jurnalvendor_id,
                'text' => $row->nama_vendor
            ];
        }

        echo json_encode([
            'items' => $data
        ]);
    }

    public function save()
    {
        $this->form_validation->set_rules(
            'jurnalvendor_id',
            'Vendor',
            'required'
        );

        $this->form_validation->set_rules(
            'username',
            'Username',
            'required|trim'
        );

        if($this->form_validation->run() == FALSE){

            echo json_encode([
                'status'  => 'error',
                'message' => validation_errors(
                    '<p class="text-danger">',
                    '</p>'
                )
            ]);

            exit;
        }

        $id_enc = $this->input->post('id_for_edit');

        $vendor_id = trim(
            $this->input->post('jurnalvendor_id')
        );

        $username = trim(
            $this->input->post('username')
        );

        $password = trim(
            $this->input->post('password')
        );

        $data = [
            'jurnalvendor_id' => $vendor_id,
            'username'        => $username,
            'catatan'         => trim(
                $this->input->post('catatan')
            )
        ];

        $this->db->trans_start();

        $is_update = !empty($id_enc);

        if($is_update){

            $id = base64_decode(
                urldecode($id_enc)
            );

            $existing = $this->Md_jurnal_akun->cek_duplikat(
                $vendor_id,
                $username,
                $id
            );

            if($existing){

                $this->db->trans_rollback();

                echo json_encode([
                    'status' => 'error',
                    'message' => 'Username sudah digunakan pada vendor tersebut'
                ]);

                exit;
            }

            if(!empty($password)){

                $data['password_encrypt'] =
                    encrypt_password($password);
            }

            $this->Md_jurnal_akun->update($id, $data);

            $jenis_akses = 'Update';

            $log_ket =
                $this->session->userdata('username') .
                ' Melakukan Update Akun Jurnal : ' .
                $username;

        } else {

            if(empty($password)){

                $this->db->trans_rollback();

                echo json_encode([
                    'status' => 'error',
                    'message' => 'Password wajib diisi'
                ]);

                exit;
            }

            $existing = $this->Md_jurnal_akun->cek_duplikat(
                $vendor_id,
                $username
            );

            if($existing){

                $this->db->trans_rollback();

                echo json_encode([
                    'status' => 'error',
                    'message' => 'Username sudah digunakan pada vendor tersebut'
                ]);

                exit;
            }

            $data['password_encrypt'] =
                encrypt_password($password);

            $data['author'] =
                $this->session->userdata('username');

            $data['tgl_post'] =
                date('Y-m-d H:i:s');

            $data['status'] = 1;

            $this->Md_jurnal_akun->insert($data);

            $jenis_akses = 'Add';

            $log_ket =
                $this->session->userdata('username') .
                ' Melakukan Tambah Akun Jurnal : ' .
                $username;
        }

        $log = [
            'user_id'     => $this->session->userdata('idsys'),
            'jenis_log'   => 'Admin',
            'jenis_akses' => $jenis_akses,
            'status'      => 1,
            'keterangan'  => $log_ket,
            'IP'          => $this->input->ip_address()
        ];

        $this->Md_log->addLog($log);

        if($this->db->trans_status() === FALSE){

            $this->db->trans_rollback();

            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menyimpan data'
            ]);

        } else {

            $this->db->trans_commit();

            echo json_encode([
                'status' => 'success',
                'message' => $is_update
                    ? 'Data berhasil diperbarui'
                    : 'Data berhasil ditambahkan'
            ]);
        }

        exit;
    }

    public function delete()
    {
        $id_enc = $this->input->post('id');

        $id = base64_decode(
            urldecode($id_enc)
        );

        $this->db->trans_start();

        $data = $this->Md_jurnal_akun->get_by_id($id);

        $this->Md_jurnal_akun->soft_delete($id);

        $log = [
            'user_id'     => $this->session->userdata('idsys'),
            'jenis_log'   => 'Admin',
            'jenis_akses' => 'Hapus',
            'status'      => 1,
            'keterangan'  =>
                $this->session->userdata('username') .
                ' Menghapus Akun Jurnal : ' .
                $data->username,
            'IP'          => $this->input->ip_address()
        ];

        $this->Md_log->addLog($log);

        if($this->db->trans_status() === FALSE){

            $this->db->trans_rollback();

            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menghapus data'
            ]);

        } else {

            $this->db->trans_commit();

            echo json_encode([
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ]);
        }

        exit;
    }

    public function get_detail()
    {
        $id = base64_decode(
            urldecode(
                $this->input->post('id')
            )
        );

        $data = $this->Md_jurnal_akun->get_by_id($id);

        if(!$data){

            echo json_encode([
                'status' => 'error'
            ]);

            return;
        }

        echo json_encode([
            'status' => 'success',
            'data'   => $data
        ]);
    }
    
    public function show_password()
    {
        $id_enc = $this->input->post('id');

        $id = base64_decode(
            urldecode($id_enc)
        );

        if (
            $id === false ||
            $id === '' ||
            !is_numeric($id)
        ) {

            echo json_encode([
                'status'  => 'error',
                'message' => 'ID tidak valid'
            ]);

            exit;
        }

        $data = $this->Md_jurnal_akun->get_detail_with_vendor($id);

        if (!$data) {

            echo json_encode([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan'
            ]);

            exit;
        }

        /* ================= LOGGING ================= */

        $log = [
            'user_id'     => $this->session->userdata('idsys'),
            'jenis_log'   => 'Admin',
            'jenis_akses' => 'Show Password',
            'status'      => 1,
            'keterangan'  =>
                $this->session->userdata('idsys') .
                ' Melihat Password Akun Jurnal : ' .
                $data->username,
            'IP'          => $this->input->ip_address()
        ];

        $this->Md_log->addLog($log);

        echo json_encode([
            'status'   => 'success',
            'vendor' => $data->nama_vendor,
            'username' => $data->username,
            'password' => decrypt_password(
                $data->password_encrypt
            )
        ]);
    }
    
    public function release_account()
    {
        $id = base64_decode(
            urldecode(
                $this->input->post('id')
            )
        );
        $result = $this->Md_jurnal_akun->release_account($id);
        echo json_encode($result);
    }

}