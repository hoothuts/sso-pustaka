<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function determine_version($data)
{
    // Attempt to decode the JSON string
    $decoded_data = json_decode($data, true);

    // Check if decoding was successful
    if (json_last_error() === JSON_ERROR_NONE) {
        // Check if $decoded_data is an array of strings (version 1)
        if (is_array($decoded_data) && !empty($decoded_data) && is_string($decoded_data[0])) {
            return 1;
        }

        // Check if $decoded_data is an array of associative arrays (version 2)
        if (is_array($decoded_data) && !empty($decoded_data) && is_array($decoded_data[0]) && isset($decoded_data[0]['filename'])) {
            return 2;
        }
    }

    // Check if $data is already a string and matches the format of a filename (version 3)
    if (is_string($data) && preg_match('/^\d{14}-.*\.[a-zA-Z0-9]+$/', $data)) {
        return 3;
    }

    // If it doesn't match any known version, return null or an appropriate value
    return null;
}

function delete_file($file_path)
{
    // Set the full path to the file
    $full_path = FCPATH . $file_path;

    // Check if the file exists
    if (file_exists($full_path)) {
        // Delete the file
        if (unlink($full_path)) {
            // File successfully deleted
            return true;
        } else {
            // Error deleting the file
            return false;
        }
    } else {
        // File does not exist
        return false;
    }
}

function getMediaSosialIcon($nm)
{

    if ($nm == 'Facebook') {
        return 'fa fa-facebook-official';
    } else if ($nm == 'Twitter') {
        return 'fa fa-twitter';
    } else if ($nm == 'Instagram') {
        return 'fa fa-instagram';
    } else if ($nm == 'Telegram') {
        return 'fa fa-telegram';
    } else if ($nm == 'Whatsapp') {
        return 'fa fa-whatsapp';
    } else if ($nm == 'Youtube') {
        return 'fa fa-youtube';
    } else if ($nm == 'Tiktok') {
        return 'fa-brands fa-tiktok';
    } else {
        return '';
    }
}

function formatTanggalIndonesia($tanggal)
{
    $bulan = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );

    $pecahkan = explode('-', $tanggal);

    // Hari tanpa leading zero
    $tgl = intval($pecahkan[2]);

    // Bulan
    $bln = intval($pecahkan[1]);

    // Tahun
    $thn = $pecahkan[0];

    return $tgl . ' ' . $bulan[$bln] . ' ' . $thn;
}

function readvisitor()
{

    $CI = &get_instance();

    //$ip    = $CI->input->ip_address(); // Mendapatkan IP user
    //untuk reverse proxy HTTP_X_FORWARDED_FOR
    $ip = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER['REMOTE_ADDR'];
    $date  = date("Y-m-d"); // Mendapatkan tanggal sekarang
    $waktu = time(); //
    $timeinsert = date("Y-m-d H:i:s");

    // Cek berdasarkan IP, apakah user sudah pernah mengakses hari ini
    $s = $CI->db->query("SELECT * FROM visitors WHERE ip=? AND date=?", array($ip, $date))->num_rows();
    $ss = isset($s) ? ($s) : 0;

    // Kalau belum ada, simpan data user tersebut ke database
    if ($ss == 0) {
        $CI->db->query("INSERT INTO visitors (ip, date, hits, online, time) VALUES(?, ?, '1', ?, ?)", array($ip, $date, $waktu, $timeinsert));
    } else {
        $CI->db->query("UPDATE visitors SET hits=hits+1, online=? WHERE ip=? AND date=?", array($waktu, $ip, $date));
    }
}

function logoutNow() {
    $CI = get_instance();
    $CI->load->model('Md_log');
    $CI->load->library('session');
    $CI->load->helper('url');
    $id = $CI->session->userdata('idsys');
    
    if (!empty($id)) {
        $log = array(
            'user_id' => $CI->session->userdata('idsys'),
            'jenis_log' => 'Admin',
            'jenis_akses' => 'LogOut',
            'status' => 1,
            'keterangan' => $CI->session->userdata('username') . ' Melakukan Logout',
            'IP' => $CI->input->ip_address()
        );
       $CI->Md_log->addLog($log);
    }
    $CI->session->sess_destroy();
    redirect(base_url() . 'home', 'refresh');
}
