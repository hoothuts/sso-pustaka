<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_jurnal_vendor extends CI_Controller {

    public function __construct() {
        parent::__construct();

        date_default_timezone_set('Asia/Jakarta');

        $this->load->database();
        $this->load->library('form_validation');
        $this->load->library('upload');
        $this->load->library('image_lib');

        $this->load->model('Md_jurnal_vendor');
        $this->load->model('Md_log');
        $this->load->helper('pkrlib_helper');

        if ($this->session->userdata('login_type') != 'admin') {
            logoutNow();
        }
    }

    public function index()
    {
        $page_data['page_name']   = 'manage_jurnal_vendor';
        $page_data['page_dir']    = 'manage_jurnal_vendor';
        $page_data['page_file']   = 'index';

        $page_data['page_access'] = "admin";
        $page_data['page_now']    = 'Jurnal Berlangganan';
        $page_data['page_title']  = 'Manage Vendor Jurnal';

        $this->load->view('index', $page_data);
    }

    public function fetch()
    {
        $datatable = $this->input->post('datatable');

        $search  = $datatable['query']['generalSearch'] ?? '';
        $page    = $datatable['pagination']['page'] ?? 1;
        $perpage = $datatable['pagination']['perpage'] ?? 10;

        $sort  = $datatable['sort']['sort'] ?? 'DESC';
        $field = $datatable['sort']['field'] ?? 'jurnalvendor_id';

        $offset = ($page - 1) * $perpage;

        $list = $this->Md_jurnal_vendor->get_datatables(
            $search,
            $perpage,
            $offset,
            $field,
            $sort
        );

        $total = $this->Md_jurnal_vendor->count_filtered($search);

        $data = [];
        $no = $offset;

        foreach ($list as $row) {

            $no++;

            $data[] = [
                'number'           => $no,
                'id_enc'           => urlencode(base64_encode($row->jurnalvendor_id)),
                'nama_vendor'      => $row->nama_vendor,
                'url_vendor'       => $row->url_vendor,
                'is_multi_login'   => $row->is_multi_login ,
                'kategori'         => $row->kategori,
                'deskripsi'        => $row->deskripsi,
                'logo'             => $row->logo,
                'author'           => $row->author,
                'tgl_post'         => $row->tgl_post
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

    public function save()
    {
        $this->form_validation->set_rules(
            'nama_vendor',
            'Nama Vendor',
            'required|trim'
        );

        $this->form_validation->set_rules(
            'url_vendor',
            'URL Vendor',
            'required|trim'
        );

        if ($this->form_validation->run() == FALSE) {

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

        $nama_vendor = trim($this->input->post('nama_vendor'));

        $data = [
            'nama_vendor' => $nama_vendor,
            'url_vendor'  => trim($this->input->post('url_vendor')),
            'is_multi_login' => $this->input->post('is_multi_login') ? 1 : 0,
            'kategori'    => trim($this->input->post('kategori')),
            'deskripsi'   => trim($this->input->post('deskripsi'))
        ];

        $this->db->trans_start();

        $is_update = !empty($id_enc);

        if ($is_update) {

            $id = base64_decode(urldecode($id_enc));

            if ($id === false || $id === '' || !is_numeric($id)) {

                $this->db->trans_rollback();

                echo json_encode([
                    'status' => 'error',
                    'message' => 'ID tidak valid'
                ]);
                exit;
            }

            $existing = $this->Md_jurnal_vendor->cek_duplikat(
                $nama_vendor,
                $id
            );

            if ($existing) {

                $this->db->trans_rollback();

                echo json_encode([
                    'status' => 'error',
                    'message' => 'Vendor sudah terdaftar'
                ]);
                exit;
            }

            $old = $this->Md_jurnal_vendor->get_by_id($id);

            if (!empty($_FILES['logo']['name'])) {

                $upload_logo = $this->_upload_logo();

                if ($upload_logo['status'] == false) {

                    $this->db->trans_rollback();

                    echo json_encode([
                        'status'  => 'error',
                        'message' => $upload_logo['message']
                    ]);
                    exit;
                }

                $data['logo'] = $upload_logo['file_name'];

                if (!empty($old->logo)) {

                    $old_file = FCPATH . 'uploads/jurnal_vendor/' . $old->logo;

                    if (file_exists($old_file)) {
                        unlink($old_file);
                    }
                }
            }

            $this->Md_jurnal_vendor->update($id, $data);

            $log_ket = $this->session->userdata('username') .
                       ' Melakukan Update Vendor Jurnal: ' .
                       $nama_vendor;

            $jenis_akses = 'Update';

        } else {

            $existing = $this->Md_jurnal_vendor->cek_duplikat(
                $nama_vendor
            );

            if ($existing) {

                $this->db->trans_rollback();

                echo json_encode([
                    'status' => 'error',
                    'message' => 'Vendor sudah terdaftar'
                ]);
                exit;
            }

            if (!empty($_FILES['logo']['name'])) {

                $upload_logo = $this->_upload_logo();

                if ($upload_logo['status'] == false) {

                    $this->db->trans_rollback();

                    echo json_encode([
                        'status'  => 'error',
                        'message' => $upload_logo['message']
                    ]);
                    exit;
                }

                $data['logo'] = $upload_logo['file_name'];
            }

            $data['author']   = $this->session->userdata('username');
            $data['tgl_post'] = date('Y-m-d H:i:s');
            $data['status']   = 1;

            $this->Md_jurnal_vendor->insert($data);

            $log_ket = $this->session->userdata('username') . ' Melakukan Tambah Vendor Jurnal: ' . $nama_vendor;

            $jenis_akses = 'Add';
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

        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();

            echo json_encode([
                'status'  => 'error',
                'message' => 'Gagal menyimpan data'
            ]);

        } else {

            $this->db->trans_commit();

            echo json_encode([
                'status'  => 'success',
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

        $id = base64_decode(urldecode($id_enc));

        if ($id === false || $id === '' || !is_numeric($id)) {

            echo json_encode([
                'status' => 'error',
                'message' => 'ID tidak valid'
            ]);
            exit;
        }

        $this->db->trans_start();

        $vendor = $this->Md_jurnal_vendor->get_by_id($id);

        $this->Md_jurnal_vendor->soft_delete($id);

        $log = [
            'user_id'     => $this->session->userdata('idsys'),
            'jenis_log'   => 'Admin',
            'jenis_akses' => 'Hapus',
            'status'      => 1,
            'keterangan'  => $this->session->userdata('username') .
                             ' Menghapus Vendor Jurnal: ' .
                             $vendor->nama_vendor,
            'IP'          => $this->input->ip_address()
        ];

        $this->Md_log->addLog($log);

        if ($this->db->trans_status() === FALSE) {

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

        $data = $this->Md_jurnal_vendor->get_by_id($id);

        if (!$data) {

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

    private function _upload_logo()
    {
        $filename = time() . '_' . $_FILES['logo']['name'];

        $config['upload_path']   = './uploads/jurnal_vendor/';
        $config['allowed_types'] = 'jpg|jpeg|png|webp';
        $config['max_size']      = 2048;
        $config['file_name']     = $filename;

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('logo')) {

            return [
                'status'  => false,
                'message' => strip_tags(
                    $this->upload->display_errors()
                )
            ];
        }

        $upload_data = $this->upload->data();

        $compress['image_library']  = 'gd2';
        $compress['source_image']   = $upload_data['full_path'];
        $compress['maintain_ratio'] = true;
        $compress['quality']        = '70%';

        $this->image_lib->initialize($compress);
        $this->image_lib->resize();

        return [
            'status'    => true,
            'file_name' => $upload_data['file_name']
        ];
    }
}