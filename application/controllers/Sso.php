<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sso extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('Md_siperpus_sysuser');
        $this->load->model('Md_siperpus_sysgrant');
        $this->load->model('Md_vwanggota');
        $this->load->model('Md_log');
    }

    public function callback() {
        $token = $this->input->get('token');
        if (empty($token)) {
            $this->session->set_flashdata('alert', 'alert-danger');
            $this->session->set_flashdata('flash_message', 'Token SSO tidak ditemukan. Silakan login ulang.');
            redirect(base_url() . 'home', 'refresh');
            return;
        }

        $apiUrl = $this->config->item('sso_base_url') . '/validate.php?token=' . urlencode($token);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            $this->session->set_flashdata('alert', 'alert-danger');
            $this->session->set_flashdata('flash_message', 'Gagal menghubungi Server SSO.');
            redirect(base_url() . 'home', 'refresh');
            return;
        }

        $data = json_decode($response, true);
        if (!isset($data['success']) || $data['success'] !== true) {
            $this->session->set_flashdata('alert', 'alert-danger');
            $this->session->set_flashdata('flash_message', 'Token SSO tidak valid atau sudah kadaluarsa.');
            redirect(base_url() . 'home', 'refresh');
            return;
        }

        $userType = isset($data['user_type']) ? strtolower(trim($data['user_type'])) : '';
        $userData = isset($data['user_data']) && is_array($data['user_data']) ? $data['user_data'] : [];
        $ip       = $this->input->ip_address();

        // 1. Login Akun Mahasiswa / Student
        if ($userType === 'student' || $userType === 'mahasiswa') {
            $nim = trim($userData['nim'] ?? $userData['username'] ?? $userData['nis'] ?? $userData['no_anggota'] ?? $userData['id'] ?? '');
            if ($nim === '') {
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Data NIM mahasiswa tidak ditemukan dari server SSO.');
                redirect(base_url() . 'home', 'refresh');
                return;
            }

            $ssoName = trim($userData['nama'] ?? $userData['name'] ?? $userData['fullname'] ?? $userData['full_name'] ?? '');
            $info    = $this->Md_vwanggota->getInfoAnggota($nim);
            $nama    = ($info && !empty($info->nama)) ? $info->nama : ($ssoName !== '' ? $ssoName : $nim);

            $this->session->set_userdata([
                'member'      => $nim,
                'member_nama' => $nama,
                'member_tipe' => 'Mahasiswa',
                'login_type'  => 'student',
                'login_via'   => 'sso',
            ]);

            $this->Md_log->addLog([
                'user_id'     => substr($nim, 0, 20),
                'jenis_log'   => 'Mahasiswa',
                'jenis_akses' => 'LogIn',
                'status'      => 1,
                'keterangan'  => $nama . ' (Mahasiswa) Melakukan Login via SSO',
                'IP'          => $ip,
            ]);

            redirect(base_url(), 'refresh');
            return;
        }

        // 2. Login Akun Pegawai / Employee
        if ($userType === 'employee' || $userType === 'pegawai') {
            $nip = trim($userData['nip'] ?? $userData['username'] ?? $userData['no_anggota'] ?? $userData['id'] ?? '');
            if ($nip === '') {
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Data NIP pegawai tidak ditemukan dari server SSO.');
                redirect(base_url() . 'home', 'refresh');
                return;
            }

            $ssoName = trim($userData['nama'] ?? $userData['name'] ?? $userData['fullname'] ?? $userData['full_name'] ?? '');

            // Cek apakah terdaftar sebagai staf / admin perpustakaan
            $accounts = $this->Md_siperpus_sysuser->getUserByNip($nip);
            if (empty($accounts)) {
                $accounts = $this->Md_siperpus_sysuser->getUserById($nip);
            }

            if (!empty($accounts)) {
                $row = $accounts[0];

                $grantadd    = $this->Md_siperpus_sysgrant->getGrantByGroup($row->idsysgroup, 1, 0, 0, 0, 0);
                $grantedit   = $this->Md_siperpus_sysgrant->getGrantByGroup($row->idsysgroup, 0, 1, 0, 0, 0);
                $grantdelete = $this->Md_siperpus_sysgrant->getGrantByGroup($row->idsysgroup, 0, 0, 1, 0, 0);
                $grantview   = $this->Md_siperpus_sysgrant->getGrantByGroup($row->idsysgroup, 0, 0, 0, 1, 0);
                $grantprint  = $this->Md_siperpus_sysgrant->getGrantByGroup($row->idsysgroup, 0, 0, 0, 0, 1);

                $this->session->set_userdata([
                    'idsys'       => $row->idsysuser,
                    'username'    => $row->name,
                    'avatar'      => $row->avatar ?: 'Male-1.png',
                    'login_type'  => 'admin',
                    'default'     => 'manage_artikel',
                    'login_via'   => 'sso',
                    'perm_add'    => $grantadd,
                    'perm_edit'   => $grantedit,
                    'perm_delete' => $grantdelete,
                    'perm_view'   => $grantview,
                    'perm_print'  => $grantprint,
                    'member'      => $nip ?: $row->idsysuser,
                    'member_nama' => $row->name,
                    'member_tipe' => 'Pegawai',
                ]);

                $this->Md_log->addLog([
                    'user_id'     => substr($row->idsysuser, 0, 20),
                    'jenis_log'   => 'Admin',
                    'jenis_akses' => 'LogIn',
                    'status'      => 1,
                    'keterangan'  => $row->name . ' Melakukan Login via SSO',
                    'IP'          => $ip,
                ]);

                redirect(base_url(), 'refresh');
                return;
            }

            // Jika bukan staf admin perpustakaan, login sebagai anggota pegawai
            $info = $this->Md_vwanggota->getInfoAnggota($nip);
            $nama = ($info && !empty($info->nama)) ? $info->nama : ($ssoName !== '' ? $ssoName : $nip);

            $this->session->set_userdata([
                'member'      => $nip,
                'member_nama' => $nama,
                'member_tipe' => 'Pegawai',
                'login_type'  => 'pegawai',
                'login_via'   => 'sso',
            ]);

            $this->Md_log->addLog([
                'user_id'     => substr($nip, 0, 20),
                'jenis_log'   => 'Pegawai',
                'jenis_akses' => 'LogIn',
                'status'      => 1,
                'keterangan'  => $nama . ' (Pegawai) Melakukan Login via SSO',
                'IP'          => $ip,
            ]);

            redirect(base_url(), 'refresh');
            return;
        }

        // Tipe akun tidak dikenali
        $this->session->set_flashdata('alert', 'alert-danger');
        $this->session->set_flashdata('flash_message', 'Tipe akun SSO tidak didukung di sistem ini.');
        redirect(base_url() . 'home', 'refresh');
        return;
    }
}
