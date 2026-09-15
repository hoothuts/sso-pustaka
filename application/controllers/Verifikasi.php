<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verifikasi extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Md_siperpus_transaksi');
        $this->load->model('Md_vwsiswa');
        $this->load->helper('pkrlib');
        
    }

    /**
     * Verifikasi Peminjaman Mandiri dari QR Code
     * URL: https://lib.pkr.ac.id/verifikasi/peminjaman/[encoded_data]
     */
    public function peminjaman($encoded = null)
    {
        if (empty($encoded)) {
            $data['title']   = "Verifikasi Gagal";
            $data['message'] = "Data QR Code tidak ditemukan atau tidak valid.";
            $this->load->view('verifikasi_error', $data);
            return;
        }

        // Decode data dari QR Code
        $decoded = base64_decode(urldecode($encoded));
        $parts   = explode('|', $decoded);

        if (count($parts) !== 2) {
            $data['title']   = "Verifikasi Gagal";
            $data['message'] = "Format QR Code tidak sesuai.";
            $this->load->view('verifikasi_error', $data);
            return;
        }

        $nim        = trim($parts[0]);
        $tgl_pinjam = trim($parts[1]);

        // Ambil data peminjaman menggunakan model
        $peminjaman = $this->Md_siperpus_transaksi->get_peminjaman_mandiri_by_date($nim, $tgl_pinjam);

        // Ambil data mahasiswa
        $siswa = $this->Md_vwsiswa->getSiswaByNim($nim); 

        if (empty($peminjaman)) {
            $data['title']   = "Verifikasi Gagal";
            $data['message'] = "Tidak ditemukan data peminjaman mandiri untuk NIM tersebut pada tanggal yang dipilih.";
            $this->load->view('verifikasi_error', $data);
            return;
        }

        $data = [
            'title'      => 'Verifikasi Peminjaman Mandiri',
            'siswa'      => $siswa,
            'nim'        => $nim,
            'tgl_pinjam' => $tgl_pinjam,
            'peminjaman' => $peminjaman
        ];

        $this->load->view('peminjaman_verifikasi', $data);
    }
    
    
    // ====================== VERIFIKASI PENGEMBALIAN BUKU MANDIRI ======================
    public function pengembalian($encoded = null)
    {
        if (empty($encoded)) {
            $data['title']   = "Verifikasi Gagal";
            $data['message'] = "Data QR Code tidak ditemukan atau tidak valid.";
            $this->load->view('verifikasi_error', $data);
            return;
        }

        // Decode data dari QR Code
        $decoded = base64_decode(urldecode($encoded));
        $parts   = explode('|', $decoded);

        if (count($parts) !== 2) {
            $data['title']   = "Verifikasi Gagal";
            $data['message'] = "Format QR Code tidak sesuai.";
            $this->load->view('verifikasi_error', $data);
            return;
        }

        $nim              = trim($parts[0]);
        $tgl_pengembalian = trim($parts[1]);

        // Ambil data pengembalian mandiri
        $pengembalian = $this->Md_siperpus_transaksi->get_pengembalian_mandiri_by_date($nim, $tgl_pengembalian);

        // Ambil data mahasiswa
        $siswa = $this->Md_siperpus_transaksi->get_siswa_by_nim($nim);

        if (empty($pengembalian)) {
            $data['title']   = "Verifikasi Gagal";
            $data['message'] = "Tidak ditemukan data pengembalian mandiri untuk NIM tersebut pada tanggal yang dipilih.";
            $this->load->view('verifikasi_error', $data);
            return;
        }

        $data = [
            'title'          => 'Verifikasi Pengembalian Buku Mandiri',
            'siswa'          => $siswa,
            'nim'            => $nim,
            'tgl_pengembalian' => $tgl_pengembalian,
            'pengembalian'   => $pengembalian
        ];

        $this->load->view('pengembalian_verifikasi', $data);
    }

}