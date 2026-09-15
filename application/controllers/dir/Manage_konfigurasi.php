<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_konfigurasi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->database();
        $this->load->model('Md_siperpus_setting');
        $this->load->model('Md_siperpus_kategori_buku');
        $this->load->model('Md_log');
        $this->load->helper('pkrlib_helper');
        $this->load->helper('encryption_id_helper');

        if ($this->session->userdata('login_type') != 'admin') {
            logoutNow();
        }
    }

    public function index() {
        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'Konfigurasi Transaksi';
        $page_data['page_title'] = 'Konfigurasi Transaksi';
        $page_data['page_name'] = 'konfigurasi';
        $page_data['page_dir'] = 'manage_konfigurasi';
        $page_data['page_file'] = 'index';

        $page_data['page_action'] = 'list';
        $page_data['data'] = $this->Md_siperpus_setting->getSettingAll();
        $page_data['data_kategori'] = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
        $this->load->view('index', $page_data);
    }

    public function update() {

        $setting1 = $this->input->post('setting1');
        $setting2 = $this->input->post('setting2');
        $setting3 = $this->input->post('setting3');
        $setting4 = $this->input->post('setting4');
        $setting5 = $this->input->post('setting5');
        $setting6 = $this->input->post('setting6');
        $setting7 = $this->input->post('setting7');
        $setting8 = $this->input->post('setting8');
        $setting9 = $this->input->post('setting9');
        //$setting10 = $this->input->post('setting10');
        $setting1kode = $this->input->post('setting1kode');
        $setting2kode = $this->input->post('setting2kode');
        $setting3kode = $this->input->post('setting3kode');
        $setting4kode = $this->input->post('setting4kode');
        $setting5kode = $this->input->post('setting5kode');
        $setting6kode = $this->input->post('setting6kode');
        $setting7kode = $this->input->post('setting7kode');
        $setting8kode = $this->input->post('setting8kode');
        $setting9kode = $this->input->post('setting9kode');

        if ($setting1 != '' && $setting1kode != '') {
            $data['valsetting'] = $setting1;
            $this->Md_siperpus_setting->updateSetting($setting1kode, $data);
        }
        if ($setting2 != '' && $setting2kode != '') {
            $data['valsetting'] = $setting2;
            $this->Md_siperpus_setting->updateSetting($setting2kode, $data);
        }
        if ($setting3 != '' && $setting3kode != '') {
            $data['valsetting'] = $setting3;
            $this->Md_siperpus_setting->updateSetting($setting3kode, $data);
        }
        if ($setting4 != '' && $setting4kode != '') {
            $data['valsetting'] = $setting4;
            $this->Md_siperpus_setting->updateSetting($setting4kode, $data);
        }
        if ($setting5 != '' && $setting5kode != '') {
            $data['valsetting'] = $setting5;
            $this->Md_siperpus_setting->updateSetting($setting5kode, $data);
        }
        if ($setting6 != '' && $setting6kode != '') {
            $data['valsetting'] = $setting6;
            $this->Md_siperpus_setting->updateSetting($setting6kode, $data);
        }
        if ($setting7 != '' && $setting7kode != '') {
            $data['valsetting'] = $setting7;
            $this->Md_siperpus_setting->updateSetting($setting7kode, $data);
        }
        if ($setting8 != '' && $setting8kode != '') {
            $data['valsetting'] = $setting8;
            $this->Md_siperpus_setting->updateSetting($setting8kode, $data);
        }
        if ($setting9 != '' && $setting9kode != '') {
            $data['valsetting'] = $setting9;
            $this->Md_siperpus_setting->updateSetting($setting9kode, $data);
        }
        $log = array(
            'user_id' => $this->session->userdata('idsys'),
            'jenis_log' => 'Admin',
            'jenis_akses' => 'Update',
            'status' => 1,
            'keterangan' => $this->session->userdata('username') . ' Megubah Pengaturan Program',
            'IP' => $this->input->ip_address()
        );
        $this->Md_log->addLog($log);
        $this->session->set_flashdata('alert', 'alert-warning');
        $this->session->set_flashdata('flash_message', 'Update Setting Sukses');
        redirect(base_url() . 'dir/manage_konfigurasi/', 'refresh');
    }
}
