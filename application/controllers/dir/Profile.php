<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->database();
        $this->load->model('Md_siperpus_sysuser');
        $this->load->helper(['url', 'form']);

        if (!$this->session->userdata('username')) {
            logoutNow();
        }
    }

    public function index() {
        $username = $this->session->userdata('idsys');

        $user = $this->Md_siperpus_sysuser->getUserById($username);
        if (!$user) show_404();

        $page_data['user'] = $user[0];
        
        $page_data['page_now']    = 'Profile';
        $page_data['page_title'] = 'Profile';
        $page_data['page_name']  = 'profile';
        $page_data['page_dir']   = 'profile';
        $page_data['page_file']  = 'index';

        $this->load->view('index', $page_data);
    }

    // ================= SAVE PROFILE =================
    public function save() {
        $username = $this->session->userdata('idsys');
        $name     = $this->input->post('name');
        $nip      = $this->input->post('nip_pegawai');

        if (empty($name)) {
            echo json_encode(['status'=>'error','message'=>'Nama wajib diisi']);
            return;
        }

        $update = [
            'name' => $name,
            'nip_pegawai' => $nip
        ];

        // ================= UPLOAD TTD =================
        if (!empty($_FILES['ttd_digital']['name'])) {

            $config['upload_path']   = './uploads/ttd/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size']      = 5120; // 5MB
            $config['file_name']     = 'ttd_' . time() . '_' . rand(1000,9999);

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('ttd_digital')) {
                echo json_encode([
                    'status'=>'error',
                    'message'=>$this->upload->display_errors()
                ]);
                return;
            }

            $uploadData = $this->upload->data();

            // ================= COMPRESS IMAGE =================
            $this->load->library('image_lib');

            $config_img['image_library']  = 'gd2';
            $config_img['source_image']   = $uploadData['full_path'];
            $config_img['maintain_ratio'] = TRUE;
            $config_img['quality']        = '60%'; // compress
            $config_img['width']          = 500;

            $this->image_lib->initialize($config_img);
            $this->image_lib->resize();

            $update['ttd_digital'] = $uploadData['file_name'];
        }

        // ================= UPDATE =================
        $this->Md_siperpus_sysuser->updateUser($username, $update);

        echo json_encode([
            'status'=>'success',
            'message'=>'Profile berhasil diupdate'
        ]);
    }
}