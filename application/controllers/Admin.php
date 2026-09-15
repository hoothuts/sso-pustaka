<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\PhpWord;

class Admin extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->database();
        $this->load->model('Md_siperpus_sysuser');
        $this->load->model('Md_siperpus_sysgroup');
        $this->load->model('Md_siperpus_sysgrant');
        $this->load->model('Md_vwsiswa');
        $this->load->model('Md_vwdosen');
        $this->load->model('Md_vwanggota');
        $this->load->model('Md_siperpus_anggota_luar');
        $this->load->model('Md_siperpus_klasifikasi');
        $this->load->model('Md_siperpus_setting');
        $this->load->model('Md_siperpus_config_transaksi');
        $this->load->model('Md_siperpus_kategori_buku');
        $this->load->model('Md_siperpus_bahasa');
        $this->load->model('Md_siperpus_asal_buku');
        $this->load->model('Md_siperpus_penerbit');
        $this->load->model('Md_siperpus_keperluan_sbppl');
        $this->load->model('Md_vwprodi');
        $this->load->model('Md_siperpus_matakuliah');
        $this->load->model('Md_siperpus_sysmodul');
        $this->load->model('Md_siperpus_hilangrusak');
        $this->load->model('Md_siperpus_inventaris');
        $this->load->model('Md_siperpus_buku');
        $this->load->model('Md_siperpus_presensi');
        $this->load->model('Md_siperpus_transaksi');
        $this->load->model('Md_vwkaryawan');
        $this->load->model('Md_laporan_anggota');
        $this->load->model('Md_laporan_inventaris');
        $this->load->model('Md_laporan_kataloginventaris');
        $this->load->model('Md_laporan_hilang');
        $this->load->model('Md_laporan_denda');
        $this->load->model('Md_laporan_presensi');
        $this->load->model('Md_laporan_peminjaman');
        $this->load->model('Md_laporan_pengembalian');
        $this->load->model('Md_laporan_belumkembali');
        $this->load->model('Md_laporan_buku_prodi');
        $this->load->model('Md_dashboard');
        $this->load->model('Md_siperpus_libur');
        $this->load->model('Md_siperpus_buku_prodi');
        $this->load->model('Md_siperpus_data_buku');
        $this->load->model('Md_siperpus_manage_halaman');
        $this->load->model('Md_siperpus_buku_file');
        $this->load->model('Md_log');
        $this->load->model('Md_artikel');
        $this->load->model('Md_slide');
        $this->load->model('Md_media');
        $this->load->model('Md_mediadetail');
        $this->load->model('Md_logo');
        $this->load->model('Md_msmhs');
        $this->load->model('Md_mediasosial');
        $this->load->model('Md_kontak');
        $this->load->model('Md_pengunjung');
        $this->load->model('Md_siperpus_kategori');
        $this->load->model('Md_view_filebuku');
        $this->load->model('Md_visitors');
        $this->load->model('Md_bebas_pustaka');
        $this->load->model('Md_hibah_buku');
        $this->load->model('Md_chat');
        $this->load->model('Md_resensi');
        $this->load->model('Md_siperpus_kategori');
        $this->load->model('Md_pegawai');

        $this->load->helper('pkrlib_helper');
        $this->load->helper('encryption_id_helper');

        $this->load->library('pagination');
        $this->load->library('upload');
        $this->load->library('image_lib');
        //from http://www.zedwood.com/article/php-calculate-duration-of-mp3
        //include APPPATH.'controllers/mp3file.php'; //load mp3file class for calculate duration
        /* cash control */
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    }

    public function index()
    {
        if ($this->session->userdata('login_type') != 'admin') {
            $this->session->sess_destroy();
            redirect(base_url() . 'home', 'refresh');
        }
        if ($this->session->userdata('login') == 'A')
            redirect(base_url() . 'admin/' . $this->session->userdata('default'), 'refresh');
    }

    function dashboard($param1 = '')
    {
        if ($this->session->userdata('login_type') != 'admin') {
            $this->logout();
        }
        $page_data['jml'] = $this->Md_siperpus_buku->getJumlahBuku();
        $page_data['eks'] = $this->Md_siperpus_inventaris->getJumlahEks();
        $page_data['pnj'] = $this->Md_siperpus_transaksi->getJumlahTran();
        $page_data['ang'] = $this->Md_vwanggota->getJumlahAng();
        $page_data['peminjaman'] = $this->Md_dashboard->getPeminjaman();
        $page_data['pengembalian'] = $this->Md_dashboard->getPengembalian();
        $page_data['batas'] = $this->Md_dashboard->getBatas();
        if ($param1 == 'fetch_peminjaman') {
            $total = $this->Md_dashboard->countFilteredPeminjaman();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;
            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');
            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');
            $sort = $this->input->post('datatable[sort][sort]');
            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');
            //mulai fetching data
            $data = array();
            $no = 0;
            $list = $this->Md_dashboard->getPeminjaman();
            foreach ($list as $row) {
                $no++;
                $arr = array();
                if ($this->input->post('datatable[query][jenis]') == 'dosen') {
                    $arr['number'] = ($perpage * ($page - 1)) + $no;
                    $arr['nomor'] = $row->no_anggota;
                    $arr['nama'] = $row->nama;
                    $arr['prodi'] = '';
                    $arr['noinv'] = $row->no_inv;
                    $arr['judul'] = $row->judul;
                    $arr['tanggal'] = $row->tgl_pinjam;
                    $arr['batas'] = $row->batas;
                } else if ($this->input->post('datatable[query][jenis]') == 'anggota+luar') {
                    $arr['number'] = ($perpage * ($page - 1)) + $no;
                    $arr['nomor'] = $row->no_anggota;
                    $arr['nama'] = $row->nama;
                    $arr['prodi'] = '';
                    $arr['noinv'] = $row->no_inv;
                    $arr['judul'] = $row->judul;
                    $arr['tanggal'] = $row->tgl_pinjam;
                    $arr['batas'] = $row->batas;
                } else {
                    $arr['number'] = ($perpage * ($page - 1)) + $no;
                    $arr['nomor'] = $row->no_anggota;
                    $arr['nama'] = $row->nama;
                    $arr['prodi'] = $row->kelas;
                    $arr['noinv'] = $row->no_inv;
                    $arr['judul'] = $row->judul;
                    $arr['tanggal'] = $row->tgl_pinjam;
                    $arr['batas'] = $row->batas;
                }
                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else if ($param1 == 'fetch_pengembalian') {
            $total = $this->Md_dashboard->countFilteredPengembalian();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;
            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');
            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');
            $sort = $this->input->post('datatable[sort][sort]');
            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');
            //mulai fetching data
            $data = array();
            $no = 0;
            $list = $this->Md_dashboard->getPengembalian();
            foreach ($list as $row) {
                $no++;
                $arr = array();
                if ($this->input->post('datatable[query][jenis]') == 'dosen') {
                    $arr['number'] = ($perpage * ($page - 1)) + $no;
                    $arr['nomor'] = $row->no_anggota;
                    $arr['nama'] = $row->nama;
                    $arr['prodi'] = '';
                    $arr['noinv'] = $row->no_inv;
                    $arr['judul'] = $row->judul;
                    $arr['tanggal'] = $row->tgl_pinjam;
                    $arr['batas'] = $row->batas;
                } else if ($this->input->post('datatable[query][jenis]') == 'anggota+luar') {
                    $arr['number'] = ($perpage * ($page - 1)) + $no;
                    $arr['nomor'] = $row->no_anggota;
                    $arr['nama'] = $row->nama;
                    $arr['prodi'] = '';
                    $arr['noinv'] = $row->no_inv;
                    $arr['judul'] = $row->judul;
                    $arr['tanggal'] = $row->tgl_pinjam;
                    $arr['batas'] = $row->batas;
                } else {
                    $arr['number'] = ($perpage * ($page - 1)) + $no;
                    $arr['nomor'] = $row->no_anggota;
                    $arr['nama'] = $row->nama;
                    $arr['prodi'] = $row->kelas;
                    $arr['noinv'] = $row->no_inv;
                    $arr['judul'] = $row->judul;
                    $arr['tanggal'] = $row->tgl_pinjam;
                    $arr['batas'] = $row->batas;
                }
                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else if ($param1 == 'fetch_batas') {
            $total = $this->Md_dashboard->countFilteredBatas();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;

            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');

            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');

            $sort = $this->input->post('datatable[sort][sort]');

            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');

            //mulai fetching data
            $data = array();
            $no = 0;
            $list = $this->Md_dashboard->getBatas();
            foreach ($list as $row) {
                $no++;
                $arr = array();
                if ($this->input->post('datatable[query][jenis]') == 'dosen') {
                    $arr['number'] = ($perpage * ($page - 1)) + $no;
                    $arr['nomor'] = $row->no_anggota;
                    $arr['nama'] = $row->nama;
                    $arr['prodi'] = '';
                    $arr['noinv'] = $row->no_inv;
                    $arr['judul'] = $row->judul;
                    $arr['tanggal'] = $row->tgl_pinjam;
                    $arr['batas'] = $row->batas;
                } else if ($this->input->post('datatable[query][jenis]') == 'anggota+luar') {
                    $arr['number'] = ($perpage * ($page - 1)) + $no;
                    $arr['nomor'] = $row->no_anggota;
                    $arr['nama'] = $row->nama;
                    $arr['prodi'] = '';
                    $arr['noinv'] = $row->no_inv;
                    $arr['judul'] = $row->judul;
                    $arr['tanggal'] = $row->tgl_pinjam;
                    $arr['batas'] = $row->batas;
                } else {
                    $arr['number'] = ($perpage * ($page - 1)) + $no;
                    $arr['nomor'] = $row->no_anggota;
                    $arr['nama'] = $row->nama;
                    $arr['prodi'] = $row->kelas;
                    $arr['noinv'] = $row->no_inv;
                    $arr['judul'] = $row->judul;
                    $arr['tanggal'] = $row->tgl_pinjam;
                    $arr['batas'] = $row->batas;
                }
                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            $peminjaman = $this->Md_siperpus_transaksi->getPeminjamanHari(10);
            $count_hari = 0;
            foreach ($peminjaman as $p) {
                if ($count_hari == 0) {
                    $pnj_hari = '{ y: "' . $p->tgl_pinjam . '",a :' . $p->jumlah . '}';
                } else {
                    $pnj_hari = $pnj_hari . ',{ y: "' . $p->tgl_pinjam . '",a :' . $p->jumlah . '}';
                }
                $count_hari++;
            }
            $page_data['pnj_hari'] = $pnj_hari;
            $kunjungan = $this->Md_siperpus_presensi->getPresensiHari(10);
            $count_hari = 0;
            foreach ($kunjungan as $k) {
                $jum = $this->Md_siperpus_presensi->getJumlah(substr($k->tanggal, 0, 10));
                if ($count_hari == 0) {
                    $pre_hari = '{ y: "' . substr($k->tanggal, 0, 10) . '",a :' . $jum . '}';
                } else {
                    $pre_hari = $pre_hari . ',{ y: "' . substr($k->tanggal, 0, 10) . '",a :' . $jum . '}';
                }
                $count_hari++;
            }
            $page_data['pre_hari'] = $pre_hari;
            $page_data['page_access'] = "admin";
            $page_data['page_name'] = 'dashboard';
            $page_data['page_now'] = 'dashboard';
            $page_data['page_title'] = 'Manage Dashboard';
            $this->load->view('index', $page_data);
        }
    }

    /* Ganti Password */
    function ganti_password($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $id = $this->session->userdata('idsys');
        $page_data['data_pengguna'] = $this->Md_siperpus_sysuser->getUserById($id);
        if ($param1 == 'update') {
            $pass = $this->input->post('password');
            //$sd= hash('sha1',preg_replace('/[^A-Za-z0-9\/\s,\s.\s-\s+\s?\s_\s)\s(\s@\s:\s#\s!\s*\s&\s>\s<\s=\s;\s"]/', '', $this->input->post('password')));
            //$sdnew= hash('sha1',preg_replace('/[^A-Za-z0-9\/\s,\s.\s-\s+\s?\s_\s)\s(\s@\s:\s#\s!\s*\s&\s>\s<\s=\s;\s"]/', '', $this->input->post('passwordbaru')));
            $sd = $this->input->post('passwordbaru');
            $sdnew = $this->input->post('passwordbaruconfirm');
            $data['pass'] = $sdnew;
            if ($page_data['data_pengguna'][0]->pass == $pass && $sd == $sdnew) {
                $this->Md_siperpus_sysuser->updateUser($id, $data);
                $this->session->set_flashdata('alert', 'alert-success');
                $this->session->set_flashdata('flash_message', 'Ubah Password Sukses');
                redirect(base_url() . 'admin/ganti_password', 'refresh');
            } else {
                $this->session->set_flashdata('alert', 'alert-warning');
                $this->session->set_flashdata('flash_message', 'Wrong Password');
                redirect(base_url() . 'admin/ganti_password', 'refresh');
            }
        }
        $page_data['page_action'] = $param1;
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'ganti_password';
        $page_data['page_now'] = 'dashboard';
        $page_data['page_title'] = 'Ganti Password ';
        $this->load->view('index', $page_data);
    }

    /* Ganti Avatar */
    function ganti_pic($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $id = $this->session->userdata('idsys');
        $date = new DateTime();
        $page_data['data_pengguna'] = $this->Md_siperpus_sysuser->getUserById($id);
        $page_data['data_pengguna'] = json_decode(json_encode($page_data['data_pengguna']), true);
        if (isset($_FILES['profile_pic'])) {
            $new_profile_pic = $date->format('YmdHis') . $_FILES["profile_pic"]['name'];
        } else {
            $new_profile_pic = NULL;
        }
        $config = array(
            'upload_path' => FCPATH . "uploads/profile",
            'allowed_types' => 'gif|jpg|png|jpeg|bmp',
            'file_name' => $new_profile_pic,
            'max_size' => 1024 * 10
        );
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('profile_pic')) {
            //upload to DB defaul image
            $data = $this->input->post('pic_def');
            $this->Md_siperpus_sysuser->updatePic($page_data['data_pengguna'][0]['idsysuser'], $data);
        } else {
            $data_up = $this->upload->data();
            $data = $data_up['file_name'];
            $config['image_library'] = 'gd2';
            $config['source_image'] = $data_up['full_path'];
            $config['maintain_ratio'] = TRUE;
            $config['overwrite'] = TRUE;
            $config['width'] = 200;
            $config['height'] = 250;
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
            $this->Md_siperpus_sysuser->updatePic($page_data['data_pengguna'][0]['idsysuser'], $data);
        }
        redirect(base_url() . 'admin/dashboard', 'refresh');
    }

    /**     * Data Mahasiswa* * */
    public function data_mahasiswa($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Data Mahasiswa';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'data_mahasiswa';
        $page_data['page_now'] = 'Data Induk';
        $page_data['prodi'] = $this->Md_vwprodi->getProdiAll();
        $page_data['angkatan'] = $this->Md_vwsiswa->get_distinct_tahun_masuk();

        if ($param1 == 'fetch') {
            $total = $this->Md_vwsiswa->countFiltered();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;
            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');
            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');
            $sort = $this->input->post('datatable[sort][sort]');
            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');
            //mulai fetching data
            $data = array();
            $no = 0;
            $list = $this->Md_vwsiswa->getDatatables();
            foreach ($list as $row) {
                $no++;
                $arr = array();
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['nis'] = $row->nis;
                $arr['nama'] = $row->nama;
                $arr['angkatan'] = $row->angkatan;
                $arr['kelas'] = $row->kelas;
                $arr['alamat'] = $row->alamat;
                $arr['telepon'] = $row->telepon;
                if ($row->status_siswa == 'A')
                    $row->status_siswa = "Aktif";
                $arr['status_siswa'] = $row->status_siswa;
                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            $this->load->view('index', $page_data);
        }
    }

    /**     * Data Dosen* * */
    public function data_dosen($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Data Dosen';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'data_dosen';
        $page_data['page_now'] = 'Data Induk';

        if ($param1 == 'fetch') {
            $total = $this->Md_vwdosen->countFiltered();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;
            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');
            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');
            $sort = $this->input->post('datatable[sort][sort]');
            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');
            //mulai fetching data
            $data = array();
            $no = 0;
            $list = $this->Md_vwdosen->getDatatables();
            //$prodi= $this->Md_vwprodi->getProdiAll();
            foreach ($list as $row) {
                $no++;
                $arr = array();
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['nip'] = $row->nip;
                $arr['nip_dosen'] = $row->nip_dosen;
                $arr['nama'] = $row->nama;
                $arr['kelas'] = $row->kelas;
                $arr['jabatan'] = "-";
                $arr['status'] = $row->status;
                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            $this->load->view('index', $page_data);
        }
    }

    /**     * Data Anggota* * */
    public function data_anggota($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Data Anggota';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'data_anggota';
        $page_data['page_now'] = 'Data Induk';
        $page_data['jenis'] = 'mahasiswa';
        if ($this->input->post('datatable[query][jenis]') == 'pegawai')
            $page_data['jenis'] = 'pegawai';
        if ($this->input->post('datatable[query][jenis]') == 'anggota+luar')
            $page_data['jenis'] = 'anggota+luar';
        if ($param1 == 'export' && $param2 != '') {
            $jenis = 'anggota+luar';
            $search = '';
            if ($param2 == 'excel') {
                $jenis = $this->input->post('epilih');
                $search = $this->input->post('esearch');
            } elseif ($param2 == 'web') {
                $jenis = $this->input->post('wpilih');
                $search = $this->input->post('wsearch');
            }
            if ($param2 == 'excel' || $param2 == 'web')
                $page_data['export'] = $param2;
            $list = $this->Md_vwanggota->getLaporan($jenis, $search);
            $page_data['title'] = 'Data_Anggota';
            $page_data['data'] = $list;
            $this->load->view('admin/data_anggota', $page_data);
        } 
        else if ($param1 == 'get' && $param2 != '' && $param3 != '') {
            $data = $this->Md_vwanggota->getAnggotaById($param2, $param3);
            echo json_encode($data);
        } 
        else if ($param1 == 'fetch') {
            $total = $this->Md_vwanggota->countFiltered();
            $page = $this->input->post('datatable[pagination][page]');
            if ($page < 1)
                $page = 1;
            $perpage = 10;
            $pages = $total / $perpage;
            $field = $this->input->post('datatable[sort][field]');
            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');
            $sort = $this->input->post('datatable[sort][sort]');
            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');
            //mulai fetching data
            $data = array();
            $no = 0;
            $list = $this->Md_vwanggota->getDatatables();
            foreach ($list as $row) {
                $no++;
                $arr = array();
                if ($this->input->post('datatable[query][jenis]') == 'pegawai') {
                    $arr['nis1'] = $row->nip;
                    $arr['nis'] = $row->nip;
                    $arr['kelas'] = '';
                    $status = $row->status_anggota;
                    $arr['status'] = $status;
                } else if ($this->input->post('datatable[query][jenis]') == 'anggota+luar') {
                    $arr['nis1'] = $row->noid;
                    $arr['nis'] = $row->noid;
                    $arr['kelas'] = '';
                    $arr['status'] = '';
                } else {
                    $arr['nis1'] = $row->nis;
                    $arr['nis'] = $row->nis;
                    $arr['kelas'] = $row->kelas;
                    if ($row->status == 1) {
                        $status = "Aktif";
                    } else {
                        $status = "Tidak Aktif";
                    }
                    $arr['status'] = $status;
                }
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['nama'] = $row->nama;
                $arr['berlaku'] = $row->berlaku_sampai;
                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );

            //output to json format
            echo json_encode($output);
        } 
        else if ($param1 == 'cetak_kartu' && $param2 != '') {
            $data = array();
            $data_final = $this->input->post('final');
            // Ambil desain aktif saat ini
            $kartu_depan    = $this->Md_media->get_active(3)->judul;
            $kartu_belakang = $this->Md_media->get_active(4)->judul;
        
            for ($i = 0; $i < sizeof($data_final); $i++) {
                if ($param2 == 'mahasiswa') {
                    $data_mhs = $this->Md_msmhs->getmahasiswaByNo($data_final[$i]);

                    $dataarr = array(
                        'no_angg' => $data_mhs->NIMHSMSMHS,
                        'nm_angg' => $data_mhs->NMMHSMSMHS,
                        'prodi' => $data_mhs->kelas,
                        'berlaku_sd' => 'Selama Aktif',
                        //'pasfoto' => 'https://mahasiswa.pkr.ac.id/files/foto/mahasiswa/' . $data_mhs->NIMHSMSMHS . '/' . $data_mhs->TAHUNMSMHS . '/0/0',
                         'pasfoto'       => base_url() . $page_data['page_access'] . '/proxy_image?url=' 
                       . urlencode('https://mahasiswa.pkr.ac.id/files/foto/mahasiswa/' 
                       . $data_mhs->NIMHSMSMHS . '/' . $data_mhs->TAHUNMSMHS . '/0/0'),
                        'jenis_anggota' => $param2,
                    );

                    array_push($data, $dataarr);

                    //array_push($data, $this->Md_vwsiswa->getKartuSiswaById($data_final[$i]));
                } 
                else if ($param2 == 'pegawai') {
                    $data['anggota']= $this->Md_pegawai->get_kartu($data_final[$i]);
                } 
                else {
                    array_push($data, $this->Md_siperpus_anggota_luar->getKartuAnggotaById($data_final[$i]));
                }
            }
            $data['kartu_depan']    = $kartu_depan;
            $data['kartu_belakang'] = $kartu_belakang;
            echo json_encode($data);
        } 
        else if ($param1 == 'trx_history') {
            $jenis = $param2;
            $id = $param3;
            $page_data['page_name'] = 'transaksi_buku';
            $page_data['page_title'] = 'Data Peminjaman Buku';
            $trx_data = $this->Md_siperpus_transaksi->getTrxBuku($id);
            $nama_anggota = '';
            if ($jenis == 'mahasiswa') {
                $data_mhs = $this->Md_msmhs->getmahasiswaByNo($id);

                $nama_anggota = $data_mhs->NMMHSMSMHS;
            } else {
            }

            $arrdata = array();

            if ($trx_data) {

                $no = 1;
                foreach ($trx_data as $dt_trx) {
                    array_push($arrdata, array(
                        'no' => $no++,
                        'no_inv' => $dt_trx->no_inv,
                        'judul' => $dt_trx->judul ? $dt_trx->judul : '-',
                        'tgl_pinjam' => $dt_trx->tgl_pinjam,
                        'tgl_kembali' => $dt_trx->kembali == 1 ? $dt_trx->tgl_kembali : '-',
                    ));
                }
            }
            $page_data['nm_anggota'] = $nama_anggota;
            $page_data['no_anggota'] = $id;
            $page_data['data_trx'] = $arrdata;
            $this->load->view('index', $page_data);
        } 
        else {
            $this->load->view('index', $page_data);
        }
    }
    
    public function proxy_image()
    {
        $url = $this->input->get('url');

        // Whitelist domain yang diizinkan
        $allowed_host = 'mahasiswa.pkr.ac.id';
        $parsed       = parse_url($url);

        if (!$url || $parsed['host'] !== $allowed_host) {
            show_404();
            return;
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $imageData = curl_exec($ch);
        $mimeType  = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);

        if (!$imageData) {
            show_404();
            return;
        }

        header("Content-Type: " . $mimeType);
        header("Cache-Control: public, max-age=86400");
        echo $imageData;
    }

    /**     * Data Anggota Luar* * */
    public function data_anggota_luar($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();

        function deleteFileUpload($x)
        {
            if ($x != '') {
                $listfileupload = './uploads/pasfoto_anggotaluar/' . $x;
                unlink($listfileupload);
            }
        }

        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Data Anggota Luar';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'data_anggota_luar';
        $page_data['page_now'] = 'Data Induk';
        if ($param1 == 'submit') {
            $data = array(
                'noid' => $this->input->post('noid'),
                'nama' => $this->input->post('nama'),
                'jk' => $this->input->post('jk'),
                'alamat' => $this->input->post('alamat'),
                'telepon' => $this->input->post('telepon'),
                'tempatlahir' => $this->input->post('tempatlahir'),
                'tgllahir' => $this->input->post('tgllahir'),
                'instansi_asal_nama' => $this->input->post('instansi_asal_nama'),
                'instansi_asal_alamat' => $this->input->post('instansi_asal_alamat'),
                'jabatan_semester' => $this->input->post('jabatan_semester')
            );
            if ($data['noid'] == '' || $data['nama'] == '') {
                //Empty Field
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Empty Field');
                echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Empty Field"));
            } else {
                if (empty($_FILES['file_pasphoto']['name'])) {
                    echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "File dokumen wajib di isi !"));
                    die;
                }

                $config['upload_path'] = "./uploads/pasfoto_anggotaluar";
                $config['allowed_types'] = 'jpg|jpeg';
                $config['encrypt_name'] = TRUE;
                //$config['max_size'] = '1024'; //in Kilobit => 2 Mb
                $this->upload->initialize($config);
                if ($this->upload->do_upload("file_pasphoto")) {



                    $datafoto = $this->upload->data();
                    $namafile = $datafoto['file_name'];
                    $filesize = round($_FILES['file_pasphoto']['size'] / 1024); //in Kb
                    $type = explode("/", $_FILES['file_pasphoto']['type']);
                    $file_type = strtoupper($type[1]);

                    $file_path = $_FILES['file_pasphoto']['tmp_name'];
                    list($width, $height) = getimagesize($file_path);
                    $image_width = $width;
                    $image_height = $height;

                    $mwidth = 255;
                    $mheight = 330;

                    // if ($image_width < $width || $image_height < $mheight) {
                    //     deleteFileUpload($namafile);
                    //     die(json_encode([
                    //         'status'  => 'error',
                    //         'message' => 'Lebar Minimal harus 472 pixel dan tinggi Minimal 709 pixel',
                    //     ]));
                    // }
                    //remove img from db 
                    // if ($dtPeserta->pasfoto) {
                    //     deleteFileUpload($dtPeserta->pasfoto);
                    // }
                    //compress file
                    $new_filename = 'cf_' . $namafile;
                    $cfresize = array(
                        'source_image' => './uploads/pasfoto_anggotaluar/' . $namafile,
                        'new_image' => './uploads/pasfoto_anggotaluar/' . $new_filename,
                        'maintain_ratio' => TRUE,
                        'quality' => '100%',
                        'width' => $mwidth,
                        'height' => $mheight,
                    );
                    $this->image_lib->initialize($cfresize);
                    $this->image_lib->resize();

                    if (!$this->image_lib->resize()) {
                        deleteFileUpload($namafile);
                        echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => $this->image_lib->display_errors()));

                        die;
                    }

                    $this->image_lib->clear();

                    //delete real image
                    deleteFileUpload($namafile);

                    $namafile = $new_filename;
                } else {
                    echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => $this->upload->display_errors()));
                    die;
                }

                $data['pasfoto'] = $namafile;

                $buku = $this->Md_siperpus_anggota_luar->getAnggotaLuarById($this->input->post('noid'));
                if ($buku) {
                    //Id duplicate
                    $this->session->set_flashdata('alert', 'alert-danger');
                    $this->session->set_flashdata('flash_message', 'ID telah ada, data anggota luar gagal dibuat');
                    echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "ID telah ada, data anggota luar gagal dibuat"));
                    //redirect(base_url() . 'admin/klasifikasi_buku/tambah', 'refresh');
                } else {
                    $this->Md_siperpus_anggota_luar->addKAnggotaLuar($data);
                    $log = array(
                        'user_id' => $this->session->userdata('idsys'),
                        'jenis_log' => 'Admin',
                        'jenis_akses' => 'Add',
                        'status' => 1,
                        'keterangan' => $this->session->userdata('username') . ' Melakukan Add Anggota Luar No. ID ' . $data['noid'],
                        'IP' => $this->input->ip_address()
                    );
                    $this->Md_log->addLog($log);
                    $this->session->set_flashdata('alert', 'alert-focus');
                    $this->session->set_flashdata('flash_message', 'Data Angota Luar Sukses dibuat');
                    echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Data Angota Luar Sukses dibuat"));
                }
            }
        } 
        else if ($param1 == 'update') {
            $id1 = $this->input->post('id1');
            $data = [
                'noid'                 => $this->input->post('noid'),
                'nama'                 => $this->input->post('nama'),
                'jk'                   => $this->input->post('jk'),
                'alamat'               => $this->input->post('alamat'),
                'telepon'              => $this->input->post('telepon'),
                'tempatlahir'          => $this->input->post('tempatlahir'),
                'tgllahir'             => $this->input->post('tgllahir'),
                'instansi_asal_nama'   => $this->input->post('instansi_asal_nama'),
                'instansi_asal_alamat' => $this->input->post('instansi_asal_alamat'),
                'jabatan_semester'     => $this->input->post('jabatan_semester'),
            ];

            // Validasi field wajib
            if (empty($data['noid']) || empty($data['nama'])) {
                echo json_encode(['status' => TRUE, 'alert' => 'alert-danger', 'msg' => 'Empty Field']);
                return;
            }

            $getdataanggotaLuar = $this->Md_siperpus_anggota_luar->getRowAnggotaLuarById($id1);
            $adaFotoBaru        = !empty($_FILES['file_pasphoto']['name']);

            // Proses upload foto jika ada file baru
            if ($adaFotoBaru) {
                $config = [
                    'upload_path'   => './uploads/pasfoto_anggotaluar',
                    'allowed_types' => 'jpg|jpeg',
                    'encrypt_name'  => TRUE,
                ];
                $this->upload->initialize($config);

                if (!$this->upload->do_upload('file_pasphoto')) {
                    echo json_encode(['status' => TRUE, 'alert' => 'alert-danger', 'msg' => $this->upload->display_errors()]);
                    return;
                }

                $datafoto  = $this->upload->data();
                $namafile  = $datafoto['file_name'];
                $mwidth    = 255;
                $mheight   = 330;

                // Resize / compress foto
                $new_filename = 'cf_' . $namafile;
                $cfresize = [
                    'source_image'   => './uploads/pasfoto_anggotaluar/' . $namafile,
                    'new_image'      => './uploads/pasfoto_anggotaluar/' . $new_filename,
                    'maintain_ratio' => TRUE,
                    'quality'        => '100%',
                    'width'          => $mwidth,
                    'height'         => $mheight,
                ];
                $this->image_lib->initialize($cfresize);

                if (!$this->image_lib->resize()) {
                    deleteFileUpload($namafile);
                    echo json_encode(['status' => TRUE, 'alert' => 'alert-danger', 'msg' => $this->image_lib->display_errors()]);
                    return;
                }

                $this->image_lib->clear();

                // Hapus file asli (sebelum resize) dan foto lama di database
                deleteFileUpload($namafile);
                if (!empty($getdataanggotaLuar->pasfoto)) {
                    deleteFileUpload($getdataanggotaLuar->pasfoto);
                }

                $data['pasfoto'] = $new_filename;
            }
            // Jika tidak ada foto baru, pasfoto tidak ikut diupdate (kolom tidak dimasukkan ke $data)

            // Update data anggota luar
            $this->Md_siperpus_anggota_luar->updateAnggotaLuar($id1, $data);

            // Catat log
            $this->Md_log->addLog([
                'user_id'     => $this->session->userdata('idsys'),
                'jenis_log'   => 'Admin',
                'jenis_akses' => 'Edit',
                'status'      => 1,
                'keterangan'  => $this->session->userdata('username') . ' Melakukan Edit Anggota Luar No. ID' . $data['noid'],
                'IP'          => $this->input->ip_address(),
            ]);

            $this->session->set_flashdata('alert',         'alert-focus');
            $this->session->set_flashdata('flash_message', 'Anggota Luar Sukses diedit');
            echo json_encode(['status' => TRUE, 'alert' => 'alert-focus', 'msg' => 'Anggota Luar Sukses diedit']);
        } 
        else if ($param1 == 'hapus' && $param2 != '') {
            $this->Md_siperpus_anggota_luar->hapusAnggotaLuar($param2);
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Delete',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Melakukan Hapus Anggota Luar No. ID' . $param2,
                'IP' => $this->input->ip_address()
            );
            $this->Md_log->addLog($log);
            $this->session->set_flashdata('alert', 'alert-focus');
            $this->session->set_flashdata('flash_message', 'Anggota Luar Sukses Dihapus');
            echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Anggota Luar Sukses Dihapus"));
        } 
        else if ($param1 == 'edit' && $param2 != '') {
            $data = $this->Md_siperpus_anggota_luar->getAnggotaLuarById($param2);
            echo json_encode($data);
        } 
        else if ($param1 == 'fetch') {
            $total = $this->Md_siperpus_anggota_luar->countFiltered();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;

            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');
            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');
            $sort = $this->input->post('datatable[sort][sort]');
            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');
            //mulai fetching data
            $data = array();
            $no = 0;
            $list = $this->Md_siperpus_anggota_luar->getDatatables();
            foreach ($list as $row) {
                $no++;
                $arr = array();
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['noid'] = $row->noid;
                $arr['tgl_post'] = $row->tgl_post;
                $arr['noid1'] = $row->noid;
                $arr['nama'] = $row->nama;
                $arr['telepon'] = $row->telepon;
                $arr['alamat'] = $row->alamat;
                $arr['instansi_asal_nama'] = $row->instansi_asal_nama;
                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } 
        else if ($param1 == 'cetak_kartu') {
            $data = array();
            $data_final = $this->input->post('final');
            // Ambil desain aktif saat ini
            $kartu_depan    = $this->Md_media->get_active(3)->judul;
            $kartu_belakang = $this->Md_media->get_active(4)->judul;
            
            for ($i = 0; $i < sizeof($data_final); $i++) {
                $data_al = $this->Md_siperpus_anggota_luar->getAnggotaLuarById($data_final[$i]);
                foreach ($data_al as $dt_al) {
                    $dataarr = array(
                        'no_angg' => $dt_al->noid,
                        'nm_angg' => $dt_al->nama,
                        'prodi' => $dt_al->instansi_asal_nama,
                        'berlaku_sd' => 'Selama Aktif',
                        'pasfoto' => base_url() . 'uploads/pasfoto_anggotaluar/' . $dt_al->pasfoto,
                        'dtpasfoto' => $dt_al->pasfoto,
                    );

                    array_push($data, $dataarr);
                }
            }

            $data['kartu_depan']    = $kartu_depan;
            $data['kartu_belakang'] = $kartu_belakang;
            echo json_encode($data);
        } 
        else {
            $this->load->view('index', $page_data);
        }
    }

    /**     * Data Klasifikasi* * */
    function klasifikasi_buku($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Data Klasifikasi Buku';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'klasifikasi_buku';
        $page_data['page_now'] = 'Data Referensi';

        if ($param1 == 'submit') {
            $data['id'] = $this->input->post('id');
            $data['nama'] = $this->input->post('nama');
            if ($data['id'] == '' || $data['nama'] == '') {
                //Empty Field
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Empty Field');
                echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Empty Field"));
                //redirect(base_url() . 'admin/klasifikasi_buku/tambah', 'refresh');
            } else {
                $buku = $this->Md_siperpus_klasifikasi->getKlasifikasiById($this->input->post('id'));
                if ($buku) {
                    //Id duplicate
                    $this->session->set_flashdata('alert', 'alert-danger');
                    $this->session->set_flashdata('flash_message', 'ID telah dipakai, klasifikasi buku gagal dibuat');
                    echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "ID telah dipakai, Klasifikasi buku gagal dibuat"));
                    //redirect(base_url() . 'admin/klasifikasi_buku/tambah', 'refresh');
                } else {
                    $this->Md_siperpus_klasifikasi->addKlasifikasi($data);
                    $log = array(
                        'user_id' => $this->session->userdata('idsys'),
                        'jenis_log' => 'Admin',
                        'jenis_akses' => 'Add',
                        'status' => 1,
                        'keterangan' => $this->session->userdata('username') . ' Melakukan Add Klasifikasi Buku ' . $data['nama'],
                        'IP' => $this->input->ip_address()
                    );
                    $this->Md_log->addLog($log);
                    $this->session->set_flashdata('alert', 'alert-focus');
                    $this->session->set_flashdata('flash_message', 'Klasifikasi Buku Sukses dibuat');
                    echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Klasifikasi Buku Sukses dibuat"));
                    //redirect(base_url() . 'admin/klasifikasi_buku', 'refresh');
                }
            }
        } else if ($param1 == 'update') {
            $id1 = $this->input->post('id1');
            $data['id'] = $this->input->post('id');
            $data['nama'] = $this->input->post('nama');
            if ($data['id'] == '' || $data['nama'] == '') {
                //Empty Field
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Empty Field');
                echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Empty Field"));
            } else {
                $this->Md_siperpus_klasifikasi->updateKlasifikasi($id1, $data);
                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Edit',
                    'status' => 1,
                    'keterangan' => $this->session->userdata('username') . ' Melakukan Edit Klasifikasi Buku ' . $data['nama'],
                    'IP' => $this->input->ip_address()
                );
                $this->Md_log->addLog($log);
                $this->session->set_flashdata('alert', 'alert-focus');
                $this->session->set_flashdata('flash_message', 'Klasifikasi Buku Sukses diedit');
                echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Klasifikasi Buku Sukses diedit"));
            }
        } else if ($param1 == 'hapus' && $param2 != '') {
            $this->Md_siperpus_klasifikasi->hapusKlasifikasi($param2);
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Delete',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Melakukan Hapus Klasifikasi Buku ' . $param2,
                'IP' => $this->input->ip_address()
            );
            $this->Md_log->addLog($log);
            $this->session->set_flashdata('alert', 'alert-focus');
            $this->session->set_flashdata('flash_message', 'Klasifikasi Buku Sukses Dihapus');
            echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Klasifikasi Buku Sukses Dihapus"));
        } else if ($param1 == 'edit' && $param2 != '') {
            $data = $this->Md_siperpus_klasifikasi->getKlasifikasiById($param2);
            echo json_encode($data);
        } else if ($param1 == 'fetch') {
            $total = $this->Md_siperpus_klasifikasi->countFiltered();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;

            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');

            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');

            $sort = $this->input->post('datatable[sort][sort]');

            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');

            $data = array();
            $no = 0;
            $list = $this->Md_siperpus_klasifikasi->getDatatables();

            foreach ($list as $row) {
                $no++;
                $arr = array();
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['id'] = $row->id;
                $arr['nama'] = $row->nama;
                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            $this->load->view('index', $page_data);
        }
    }

    /** Konfigurasi * */
    function konfigurasi($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['page_action'] = 'list';
        $page_data['data'] = $this->Md_siperpus_setting->getSettingAll();
        $page_data['data_kategori'] = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
        $page_data['page_title'] = 'Pengaturan Program';
        if ($param1 == 'update') {
            $setting1 = $this->input->post('setting1');
            $setting2 = $this->input->post('setting2');
            $setting3 = $this->input->post('setting3');
            $setting4 = $this->input->post('setting4');
            $setting5 = $this->input->post('setting5');
            $setting6 = $this->input->post('setting6');
            $setting7 = $this->input->post('setting7');
            $setting8 = $this->input->post('setting8');
            $setting9 = $this->input->post('setting9');
            $setting10 = $this->input->post('setting10');
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
            redirect(base_url() . 'admin/konfigurasi/', 'refresh');
        }
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'konfigurasi';
        $page_data['page_now'] = 'Konfigurasi Transaksi';
        $this->load->view('index', $page_data);
    }

    public function logout()
    {
        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        if (!empty($id)) {
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'LogOut',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Melakukan Logout',
                'IP' => $this->input->ip_address()
            );
            $this->Md_log->addLog($log);
        }
        $login_via = $this->session->userdata('login_via');
        $this->session->sess_destroy();
        if ($login_via === 'sso') {
            redirect($this->config->item('sso_base_url') . '/logout.php');
        }
        redirect(base_url() . 'home', 'refresh');
    }

    /*     * Kategori Buku* */
    public function kategori_buku($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Data Kategori Buku';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'kategori_buku';
        $page_data['page_now'] = 'Data Referensi';

        if ($param1 == 'submit') {
            $data['nmkategori'] = $this->input->post('nmkategori');
            if ($data['nmkategori'] == '') {
                //Empty Field
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Empty Field');
                echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Empty Field"));
            } else {
                $buku = $this->Md_siperpus_kategori_buku->getKategoriById($this->input->post('idkategori'));
                if ($buku) {
                    //Id duplicate
                    $this->session->set_flashdata('alert', 'alert-danger');
                    $this->session->set_flashdata('flash_message', 'ID telah dipakai, Kategori buku gagal dibuat');
                    echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "ID telah dipakai, Kategori buku gagal dibuat"));
                } else {
                    $this->Md_siperpus_kategori_buku->addKategoriBuku($data);
                    $log = array(
                        'user_id' => $this->session->userdata('idsys'),
                        'jenis_log' => 'Admin',
                        'jenis_akses' => 'Add',
                        'status' => 1,
                        'keterangan' => $this->session->userdata('username') . ' Melakukan Add Kategori Buku ' . $data['id'],
                        'IP' => $this->input->ip_address()
                    );
                    $this->Md_log->addLog($log);
                    $this->session->set_flashdata('alert', 'alert-focus');
                    $this->session->set_flashdata('flash_message', 'Kategori Buku Sukses dibuat');
                    echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Kategori Buku Sukses dibuat"));
                }
            }
        } else if ($param1 == 'update') {
            $data['idkategori'] = $this->input->post('idkategori');
            $data['nmkategori'] = $this->input->post('nmkategori');
            if ($data['idkategori'] == '' || $data['nmkategori'] == '') {
                //Empty Field
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Empty Field');
                echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Empty Field"));
            } else {
                $this->Md_siperpus_kategori_buku->updateKategoriBuku($data['idkategori'], $data);
                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Edit',
                    'status' => 1,
                    'keterangan' => $this->session->userdata('username') . ' Melakukan Edit Kategori Buku ' . $data['idkategori'],
                    'IP' => $this->input->ip_address()
                );
                $this->Md_log->addLog($log);
                $this->session->set_flashdata('alert', 'alert-focus');
                $this->session->set_flashdata('flash_message', 'Kategori Buku Sukses diedit');
                echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Kategori Buku Sukses diedit"));
            }
        } else if ($param1 == 'hapus' && $param2 != '') {
            $this->Md_siperpus_kategori_buku->hapusKategoriBuku($param2);
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Delete',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Melakukan Hapus Kategori Buku ' . $param2,
                'IP' => $this->input->ip_address()
            );
            $this->Md_log->addLog($log);
            $this->session->set_flashdata('alert', 'alert-focus');
            $this->session->set_flashdata('flash_message', 'Kategori Buku Sukses Dihapus');
            echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Kategori Buku Sukses Dihapus"));
        } else if ($param1 == 'edit' && $param2 != '') {
            $data = $this->Md_siperpus_kategori_buku->getKategoriById($param2);
            echo json_encode($data);
        } else if ($param1 == 'fetch') {
            $total = $this->Md_siperpus_kategori_buku->countFiltered();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;

            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');

            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');

            $sort = $this->input->post('datatable[sort][sort]');

            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');

            $data = array();
            $no = 0;
            $list = $this->Md_siperpus_kategori_buku->getDatatables();

            foreach ($list as $row) {
                $no++;
                $arr = array();
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['idkategori'] = $row->idkategori;
                $arr['nmkategori'] = $row->nmkategori;
                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            $this->load->view('index', $page_data);
        }
    }

    /**     * Bahasa* * */
    public function bahasa($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();

        $date = new DateTime();
        $id = $this->session->userdata('idsys');

        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Data Bahasa';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'bahasa';
        $page_data['page_now'] = 'Data Referensi';

        if ($param1 == 'submit') {
            $data['id'] = $this->input->post('id');
            $data['nama'] = $this->input->post('nama');
            $data['no_urut'] = $this->input->post('no_urut');
            if ($data['id'] == '' || $data['nama'] == '') {
                //Empty Field
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Empty Field');
                echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Empty Field"));
            } else {
                $buku = $this->Md_siperpus_bahasa->getBahasaById($this->input->post('id'));
                if ($buku) {
                    //Id duplicate
                    $this->session->set_flashdata('alert', 'alert-danger');
                    $this->session->set_flashdata('flash_message', 'ID telah dipakai, bahasa buku gagal dibuat');
                    echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "ID telah dipakai, bahasa buku gagal dibuat"));
                } else {
                    $this->Md_siperpus_bahasa->addBahasa($data);
                    $log = array(
                        'user_id' => $this->session->userdata('idsys'),
                        'jenis_log' => 'Admin',
                        'jenis_akses' => 'Add',
                        'status' => 1,
                        'keterangan' => $this->session->userdata('username') . ' Melakukan Add Bahasa ' . $data['nama'],
                        'IP' => $this->input->ip_address()
                    );
                    $this->Md_log->addLog($log);
                    $this->session->set_flashdata('alert', 'alert-focus');
                    $this->session->set_flashdata('flash_message', 'Kategori Buku Sukses dibuat');
                    echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Bahasa Buku Sukses dibuat"));
                }
            }
        } else if ($param1 == 'update') {
            $id1 = $this->input->post('id1');
            $data['id'] = $this->input->post('id');
            $data['nama'] = $this->input->post('nama');
            $data['no_urut'] = $this->input->post('no_urut');
            if ($data['id'] == '' || $data['nama'] == '') {
                //Empty Field
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Empty Field');
                echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Empty Field"));
            } else {
                $this->Md_siperpus_bahasa->updateBahasa($id1, $data);
                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Edit',
                    'status' => 1,
                    'keterangan' => $this->session->userdata('username') . ' Melakukan Edit Bahasa ' . $data['nama'],
                    'IP' => $this->input->ip_address()
                );
                $this->Md_log->addLog($log);
                $this->session->set_flashdata('alert', 'alert-focus');
                $this->session->set_flashdata('flash_message', 'Bahasa Buku Sukses diedit');
                echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Bahasa Buku Sukses diedit"));
            }
        } else if ($param1 == 'hapus' && $param2 != '') {
            $this->Md_siperpus_bahasa->hapusBahasa($param2);
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Delete',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Melakukan Delete Bahasa ' . $data['nama'],
                'IP' => $this->input->ip_address()
            );
            $this->Md_log->addLog($log);
            $this->session->set_flashdata('alert', 'alert-focus');
            $this->session->set_flashdata('flash_message', 'Bahasa Buku Sukses Dihapus');
            echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Bahasa Buku Sukses Dihapus"));
        } else if ($param1 == 'edit' && $param2 != '') {
            $data = $this->Md_siperpus_bahasa->getBahasaById($param2);
            echo json_encode($data);
        } else if ($param1 == 'fetch') {
            $total = $this->Md_siperpus_bahasa->countFiltered();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;

            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');

            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');

            $sort = $this->input->post('datatable[sort][sort]');

            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');

            $data = array();
            $no = 0;
            $list = $this->Md_siperpus_bahasa->getDatatables();

            foreach ($list as $row) {
                $no++;
                $arr = array();
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['id'] = $row->id;
                $arr['nama'] = $row->nama;
                $arr['no_urut'] = $row->no_urut;
                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            $this->load->view('index', $page_data);
        }
    }

    /**     * Asal Buku* * */
    public function asal_buku($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();

        $date = new DateTime();
        $id = $this->session->userdata('idsys');

        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Data Asal Buku';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'asal_buku';
        $page_data['page_now'] = 'Data Referensi';

        if ($param1 == 'submit') {
            $data['id'] = $this->input->post('id');
            $data['nama'] = $this->input->post('nama');
            if ($data['id'] == '' || $data['nama'] == '') {
                //Empty Field
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Empty Field');
                echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Empty Field"));
            } else {
                $buku = $this->Md_siperpus_asal_buku->getAsalBukuById($this->input->post('id'));
                if ($buku) {
                    //Id duplicate
                    $this->session->set_flashdata('alert', 'alert-danger');
                    $this->session->set_flashdata('flash_message', 'ID telah dipakai, Asal buku gagal dibuat');
                    echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "ID telah dipakai, Asal buku gagal dibuat"));
                } else {
                    $this->Md_siperpus_asal_buku->addAsalBuku($data);
                    $log = array(
                        'user_id' => $this->session->userdata('idsys'),
                        'jenis_log' => 'Admin',
                        'jenis_akses' => 'Add',
                        'status' => 1,
                        'keterangan' => $this->session->userdata('username') . ' Melakukan Add Asal Buku ' . $data['nama'],
                        'IP' => $this->input->ip_address()
                    );
                    $this->Md_log->addLog($log);
                    $this->session->set_flashdata('alert', 'alert-focus');
                    $this->session->set_flashdata('flash_message', 'Asal Buku Sukses dibuat');
                    echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Asal Buku Sukses dibuat"));
                }
            }
        } else if ($param1 == 'update') {
            $id1 = $this->input->post('id1');
            $data['id'] = $this->input->post('id');
            $data['nama'] = $this->input->post('nama');
            if ($data['id'] == '' || $data['nama'] == '') {
                //Empty Field
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Empty Field');
                echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Empty Field"));
            } else {
                $this->Md_siperpus_asal_buku->updateAsalBuku($id1, $data);
                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Edit',
                    'status' => 1,
                    'keterangan' => $this->session->userdata('username') . ' Melakukan Edit Asal Buku ' . $data['nama'],
                    'IP' => $this->input->ip_address()
                );
                $this->Md_log->addLog($log);
                $this->session->set_flashdata('alert', 'alert-focus');
                $this->session->set_flashdata('flash_message', 'Asal Buku Sukses diedit');
                echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Asal Buku Sukses diedit"));
            }
        } else if ($param1 == 'hapus' && $param2 != '') {
            $this->Md_siperpus_asal_buku->hapusAsalBuku($param2);
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Delete',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Melakukan Delete Asal Buku ' . $data['nama'],
                'IP' => $this->input->ip_address()
            );
            $this->Md_log->addLog($log);
            $this->session->set_flashdata('alert', 'alert-focus');
            $this->session->set_flashdata('flash_message', 'Asal Buku Sukses Dihapus');
            echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Asal Buku Sukses Dihapus"));
        } else if ($param1 == 'edit' && $param2 != '') {
            $data = $this->Md_siperpus_asal_buku->getAsalBukuById($param2);
            echo json_encode($data);
        } else if ($param1 == 'fetch') {
            $total = $this->Md_siperpus_asal_buku->countFiltered();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;

            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');

            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');

            $sort = $this->input->post('datatable[sort][sort]');

            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');

            $data = array();
            $no = 0;
            $list = $this->Md_siperpus_asal_buku->getDatatables();

            foreach ($list as $row) {
                $no++;
                $arr = array();
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['id'] = $row->id;
                $arr['nama'] = $row->nama;
                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            $this->load->view('index', $page_data);
        }
    }

    /**     * Penerbit* * */
    public function penerbit($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();

        $date = new DateTime();
        $id = $this->session->userdata('idsys');

        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Data Penerbit';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'penerbit';
        $page_data['page_now'] = 'Data Referensi';

        if ($param1 == 'submit') {
            $data = array(
                'nama_penerbit' => $this->input->post('nama_penerbit'),
                'kota' => $this->input->post('kota')
            );
            if ($data['nama_penerbit'] == '' || $data['kota'] == '') {
                //Empty Field
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Empty Field');
                echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Empty Field", "hasil" => 0));
            } else {

                $buku = $this->Md_siperpus_penerbit->getPenerbitByNamadanKota($data['nama_penerbit'], $data['kota']);
                if ($buku) {
                    //Id duplicate
                    $this->session->set_flashdata('alert', 'alert-danger');
                    $this->session->set_flashdata('flash_message', 'Penerbit Sudah Ada');
                    echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Penerbit Sudah Ada", "hasil" => 1, "id" => $buku[0]->kd_penerbit));
                } else {
                    $id = $this->Md_siperpus_penerbit->addPenerbit($data);
                    $log = array(
                        'user_id' => $this->session->userdata('idsys'),
                        'jenis_log' => 'Admin',
                        'jenis_akses' => 'Add',
                        'status' => 1,
                        'keterangan' => $this->session->userdata('username') . ' Melakukan Add Penerbit ' . $data['nama_penerbit'],
                        'IP' => $this->input->ip_address()
                    );
                    $this->Md_log->addLog($log);
                    $this->session->set_flashdata('alert', 'alert-focus');
                    $this->session->set_flashdata('flash_message', 'Penerbit Sukses dibuat');
                    echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Penerbit Sukses dibuat", "id" => $id, "hasil" => 2));
                }
            }
        } else if ($param1 == 'update') {
            $id1 = $this->input->post('id1');
            $data = array(
                'nama_penerbit' => $this->input->post('nama_penerbit'),
                'kota' => $this->input->post('kota')
            );
            if ($data['nama_penerbit'] == '' || $data['kota'] == '') {
                //Empty Field
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Empty Field');
                echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Empty Field"));
            } else {
                $this->Md_siperpus_penerbit->updatePenerbit($id1, $data);
                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Edit',
                    'status' => 1,
                    'keterangan' => $this->session->userdata('username') . ' Melakukan Edit Penerbit ' . $data['nama_penerbit'],
                    'IP' => $this->input->ip_address()
                );
                $this->Md_log->addLog($log);
                $this->session->set_flashdata('alert', 'alert-focus');
                $this->session->set_flashdata('flash_message', 'Penerbit Sukses diedit');
                echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Penerbit Sukses diedit"));
            }
        } else if ($param1 == 'hapus' && $param2 != '') {
            $this->Md_siperpus_penerbit->hapusPenerbit($param2);
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Delete',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Melakukan Delete Penerbit ' . $data['nama_penerbit'],
                'IP' => $this->input->ip_address()
            );
            $this->Md_log->addLog($log);
            $this->session->set_flashdata('alert', 'alert-focus');
            $this->session->set_flashdata('flash_message', 'Penerbit Sukses Dihapus');
            echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Penerbit Sukses Dihapus"));
        } else if ($param1 == 'edit' && $param2 != '') {
            $data = $this->Md_siperpus_penerbit->getPenerbitById($param2);
            echo json_encode($data);
        } else if ($param1 == 'fetch') {
            $total = $this->Md_siperpus_penerbit->countFiltered();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;

            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');

            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');

            $sort = $this->input->post('datatable[sort][sort]');

            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');

            $data = array();
            $no = 0;
            $list = $this->Md_siperpus_penerbit->getDatatables();

            foreach ($list as $row) {
                $no++;
                $arr = array();
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['kd_penerbit'] = $row->kd_penerbit;
                $arr['nama_penerbit'] = $row->nama_penerbit;
                $arr['kota'] = $row->kota;
                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            $this->load->view('index', $page_data);
        }
    }

    /**     * Keperluan SPPBL* * */
    public function keperluan_sbppl($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();

        $date = new DateTime();
        $id = $this->session->userdata('idsys');

        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Keperluan SBPPL';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'keperluan_sbppl';
        $page_data['page_now'] = 'Data Referensi';

        if ($param1 == 'submit') {
            $data['nama'] = $this->input->post('nama');
            $data['no_urut'] = $this->input->post('no_urut');
            if ($data['nama'] == '' || $data['no_urut'] == '') {
                //Empty Field
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Empty Field');
                echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Empty Field"));
            } else {
                $buku = $this->Md_siperpus_keperluan_sbppl->getSbpplByNama($this->input->post('nama'));
                if ($buku) {
                    //Id duplicate
                    $this->session->set_flashdata('alert', 'alert-danger');
                    $this->session->set_flashdata('flash_message', 'ID telah dipakai, SBPPL gagal dibuat');
                    echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "ID telah dipakai, SBPPL gagal dibuat"));
                } else {
                    $this->Md_siperpus_keperluan_sbppl->addSbppl($data);
                    $log = array(
                        'user_id' => $this->session->userdata('idsys'),
                        'jenis_log' => 'Admin',
                        'jenis_akses' => 'Add',
                        'status' => 1,
                        'keterangan' => $this->session->userdata('username') . ' Melakukan Add SBPPL ' . $data['nama'],
                        'IP' => $this->input->ip_address()
                    );
                    $this->Md_log->addLog($log);
                    $this->session->set_flashdata('alert', 'alert-focus');
                    $this->session->set_flashdata('flash_message', 'SBPPL Sukses dibuat');
                    echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "SBPPL Sukses dibuat"));
                }
            }
        } else if ($param1 == 'update') {
            $id1 = $this->input->post('id1');
            $data['nama'] = $this->input->post('nama');
            $data['no_urut'] = $this->input->post('no_urut');
            if ($data['nama'] == '' || $data['no_urut'] == '') {
                //Empty Field
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Empty Field');
                echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Empty Field"));
            } else {
                $this->Md_siperpus_keperluan_sbppl->updateSbppl($id1, $data);
                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Edit',
                    'status' => 1,
                    'keterangan' => $this->session->userdata('username') . ' Melakukan Edit SBPPL ' . $data['nama'],
                    'IP' => $this->input->ip_address()
                );
                $this->Md_log->addLog($log);
                $this->session->set_flashdata('alert', 'alert-focus');
                $this->session->set_flashdata('flash_message', 'SBPPL Sukses diedit');
                echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "SBPPL Sukses diedit"));
            }
        } else if ($param1 == 'hapus' && $param2 != '') {
            $this->Md_siperpus_keperluan_sbppl->hapusSbppl($param2);
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Delete',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Melakukan Delete SBPPL ' . $data['nama'],
                'IP' => $this->input->ip_address()
            );
            $this->Md_log->addLog($log);
            $this->session->set_flashdata('alert', 'alert-focus');
            $this->session->set_flashdata('flash_message', 'SBPPL Sukses Dihapus');
            echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "SBPPL Sukses Dihapus"));
        } else if ($param1 == 'edit' && $param2 != '') {
            $data = $this->Md_siperpus_keperluan_sbppl->getSbpplById($param2);
            echo json_encode($data);
        } else if ($param1 == 'fetch') {
            $total = $this->Md_siperpus_keperluan_sbppl->countFiltered();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;

            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');

            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');

            $sort = $this->input->post('datatable[sort][sort]');

            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');

            $data = array();
            $no = 0;
            $list = $this->Md_siperpus_keperluan_sbppl->getDatatables();

            foreach ($list as $row) {
                $no++;
                $arr = array();
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['id'] = $row->id;
                $arr['nama'] = $row->nama;
                $arr['no_urut'] = $row->no_urut;
                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            $this->load->view('index', $page_data);
        }
    }

    /**     * Proram Studi* * */
    public function program_studi($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();

        $date = new DateTime();
        $id = $this->session->userdata('idsys');

        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Program Studi';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'program_studi';
        $page_data['page_now'] = 'Data Referensi';

        if ($param1 == 'fetch') {
            $total = $this->Md_vwprodi->countFiltered();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;

            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');

            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');

            $sort = $this->input->post('datatable[sort][sort]');

            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');

            $data = array();
            $no = 0;
            $list = $this->Md_vwprodi->getDatatables();

            foreach ($list as $row) {
                $no++;
                $arr = array();
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['idmspst'] = $row->idmspst;
                $arr['kodemspst'] = $row->kodemspst;
                $arr['nmmspst'] = $row->nmmspst;
                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            $this->load->view('index', $page_data);
        }
    }

    /**     * Matakuliah* * */
    public function matakuliah($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();

        $date = new DateTime();
        $id = $this->session->userdata('idsys');

        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Data Mata Kuliah';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'matakuliah';
        $page_data['page_now'] = 'Data Referensi';

        if ($param1 == 'fetch') {
            $total = $this->Md_siperpus_matakuliah->countFiltered();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;

            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');

            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');

            $sort = $this->input->post('datatable[sort][sort]');

            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');

            $data = array();
            $no = 0;
            $list = $this->Md_siperpus_matakuliah->getDatatables();

            foreach ($list as $row) {
                $no++;
                $arr = array();
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['kodetbkmk'] = $row->kodetbkmk;
                $arr['nmtbkmk'] = $row->nmtbkmk;
                $arr['pengajartbkmk'] = $row->pengajartbkmk;
                $arr['gelarpengajartbkmk'] = $row->gelarpengajartbkmk;
                $arr['nmmspst'] = $row->nmmspst;
                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            $this->load->view('index', $page_data);
        }
    }

    /*     * Data Buku* */
    public function data_buku($param1 = '', $param2 = '', $param3 = '', $param4 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();

        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        if ($param1 != 'submit' && $param1 != 'update') {
            session_write_close();
        }


        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Data Data Buku';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'data_buku';
        $page_data['page_now'] = 'Buku & Inventarisasi';
        $page_data['klasifikasi'] = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
        $page_data['kategori'] = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
        $page_data['tahun_terbit'] = $this->Md_siperpus_data_buku->getTahunTerbitBuku();
        $x = urldecode(str_replace('_', '/', $param2)); //ISBN (mengubah ISBN dengan pola xx_xx_xx_xx menjadi xx/xx/xx/xx)
        $y = urldecode(str_replace('%20', ' ', $param3)); //Noklas(mengubah no_klas dengan pola xx%20xx%20xx menjadi xx xx xx)

        // Function to sanitize filename
        function sanitize_filename($filename)
        {
            // Replace disallowed characters with an underscore
            return preg_replace('/[^A-Za-z0-9\-\_\.]/', '', $filename);
        }

        if ($param1 == 'submit') {
            $data = array(
                'no_klas' => trim($this->input->post('no_klas')),
                'ISBN' => trim($this->input->post('isbn')),
                'idkategori' => $this->input->post('kategori_buku'),
                'judul' => $this->input->post('judul'),
                'cetakkatalog_judulpenggal' => $this->input->post('cetakkatalog_judulpenggal'),
                'judulasli' => $this->input->post('judulasli'),
                'allow_review' => $this->input->post('allow_review'),
                'deskripsi' => $this->input->post('deskripsi'),
                'penulis' => $this->input->post('penulis'),
                'penyadur' => $this->input->post('penyadur'),
                'penerjemah' => $this->input->post('penerjemah'),
                'penyusun' => $this->input->post('penyusun'),
                'penyunting' => $this->input->post('penyunting'),
                'illustrator' => $this->input->post('illustrator'),
                'editor' => $this->input->post('editor'),
                'edisi' => $this->input->post('edisi'),
                'cetakan' => $this->input->post('cetakan'),
                'kd_penerbit' => $this->input->post('penerbit'),
                'thn_terbit' => $this->input->post('thn_terbit'),
                'jilid' => $this->input->post('jilid'),
                'hlm_romawi' => $this->input->post('hlm_romawi'),
                'jml_hal' => $this->input->post('jml_hal'),
                'ilustrasi' => $this->input->post('ilustrasi'),
                'tabel' => $this->input->post('tabel'),
                'ukuran_fisik' => $this->input->post('ukuran_fisik'),
                'bibliografi' => $this->input->post('bibliografi'),
                'indeks' => $this->input->post('indeks'),
                'bahasa' => $this->input->post('bahasa'),
                'no_rak' => $this->input->post('no_rak'),
                'seri' => $this->input->post('seri'),
                'tajuk' => $this->input->post('tajuk'),
                'tajuksubyek' => $this->input->post('tajuksubyek'),
                'displayed' => $this->input->post('displayed'),
                'tanggal' => $date->format('Y-m-d H:i:s'),
                'is_baca' => 'Ya',
                'is_download' => 'Ya',
            );
            $prodi = $this->input->post('prodi');

            //cek file buku
            if (!empty($_FILES['file']['name'])) {
                $filesCount = count($_FILES['file']['name']);
                $isbacabukufileCount = count($this->input->post('is_baca_file'));

                $isdownloadbukufileCount = count($this->input->post('is_download_file'));
                if ($filesCount === $isbacabukufileCount && $filesCount === $isdownloadbukufileCount) {
                } else {

                    $this->session->set_flashdata('error_addbuku_tambah', 'Total Buku dan Option Is Baca dan Is Download Tidak sama');

                    // Redirect to the desired page with refresh
                    redirect(base_url() . 'admin/data_buku/tambah/', 'refresh');
                    die;
                }
            }
            //end cek file buku
            $this->db->trans_begin();
            if ($prodi) {
                for ($i = 0; $i < sizeof($prodi); $i++) {
                    $dataBukuProdi = array(
                        'no_klas' => $this->input->post('no_klas'),
                        'ISBN' => $this->input->post('isbn'),
                        'idmspst' => $prodi[$i],
                        'idkategori' => $this->input->post('kategori_buku')
                    );
                    $this->Md_siperpus_buku_prodi->addBukuProdi($dataBukuProdi);
                }
            }


            if ($this->input->post('kategori_buku') == 3) {
                $data_kti = array(
                    'no_klas_ta' => $this->input->post('no_klas'),
                    'ISBN_ta' => $this->input->post('isbn'),
                    'nis_ta' => $this->input->post('nis_ta'),
                    'tabel_ta' => $this->input->post('tabel_ta'),
                    'lampiran_ta' => $this->input->post('lampiran_ta'),
                    'pembimbing_ta' => $this->input->post('pembimbing_ta'),
                );
                if ($data_kti['tabel_ta'] == '')
                    $data_kti['tabel_ta'] = 0;
                if ($data_kti['lampiran_ta'] == '')
                    $data_kti['lampiran_ta'] = 0;
                $this->Md_siperpus_data_buku->addDataBukuTa($data_kti);
            }
            if (isset($_FILES['gambar'])) {
                $new_gambar = $date->format('YmdHis') . '-' . $_FILES["gambar"]['name'];
            } else {
                $new_gambar = NULL;
            }
            $config1 = array(
                'upload_path' => FCPATH . "uploads/covers",
                'allowed_types' => 'gif|jpg|png|jpeg|bmp',
                'file_name' => $new_gambar,
                'max_size' => 1024 * 20
            );

            if ($data['ilustrasi'] == '')
                $data['ilustrasi'] = 0;
            if ($data['tabel'] == '')
                $data['tabel'] = 0;
            if ($data['allow_review'] == '')
                $data['allow_review'] = 0;
            if ($data['no_klas'] == '' || $data['jml_hal'] == '' || $data['kd_penerbit'] == '' || $data['ukuran_fisik'] == '' || $data['penulis'] == '' || $data['judul'] == '' || $data['tajuksubyek'] == '' || $data['ISBN'] == '' || $data['thn_terbit'] == '') {
                // Empty Field
            } else {

                // upload image.
                $this->upload->initialize($config1);
                if (!$this->upload->do_upload('gambar')) {
                    $data['cover'] = '';
                    // echo $this->upload->display_errors();die;
                } else {
                    $data_up = $this->upload->data();
                    $data['cover'] = $data_up['file_name'];
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = $data_up['full_path'];
                    $config['maintain_ratio'] = TRUE;
                    $config['overwrite'] = TRUE;
                    $config['width'] = 200;
                    $config['height'] = 250;
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();
                }

                $this->Md_siperpus_data_buku->addDataBuku($data); //Upload Data
                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Add',
                    'status' => 1,
                    'keterangan' => $this->session->userdata('username') . ' Melakukan Add Buku ' . $data['no_klas'],
                    'IP' => $this->input->ip_address()
                );
                $this->Md_log->addLog($log);

                // upload file
                if (!empty($_FILES['file']['name'][0])) {
                    $filesCount = count($_FILES['file']['name']);
                    $arrfilename = array();
                    $arrrawname = array();
                    for ($i = 0; $i < $filesCount; $i++) {
                        $_FILES['userFile']['name'] = $_FILES['file']['name'][$i];
                        $_FILES['userFile']['type'] = $_FILES['file']['type'][$i];
                        $_FILES['userFile']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
                        $_FILES['userFile']['error'] = $_FILES['file']['error'][$i];
                        $_FILES['userFile']['size'] = $_FILES['file']['size'][$i];
                        $sanitized_name = sanitize_filename($_FILES['userFile']['name']);
                        $new_file = $date->format('YmdHis') . '-' . $sanitized_name;
                        $config = array(
                            'upload_path' => FCPATH . "uploads/files",
                            'allowed_types' => 'pdf|doc|docx|xls|xlsx|zip|txt',
                            'file_name' => $new_file,
                            'max_size' => 1024 * 20
                        );

                        $this->upload->initialize($config);
                        if ($this->upload->do_upload('userFile')) {
                            $data_up = $this->upload->data();
                            array_push($arrfilename, array('filename' => $data_up['file_name'], 'is_baca' => $this->input->post('is_baca_file')[$i], 'is_download' => $this->input->post('is_download_file')[$i]));
                            array_push($arrrawname, array('filename' => $data_up['raw_name'], 'is_baca' => $this->input->post('is_baca_file')[$i], 'is_download' => $this->input->post('is_download_file')[$i]));

                            $fullpath = $data_up['full_path'];
                        } else {
                            // Ensure the error message is a string
                            $upload_error = (string) $this->upload->display_errors();
                            $upload_error = strip_tags($upload_error);  // Menghapus semua tag HTML
                            $upload_error = htmlspecialchars($upload_error, ENT_QUOTES, 'UTF-8');  // Mengonversi karakter spesial menjadi entitas HTML
                            // Set flashdata for the error message
                            $this->session->set_flashdata('error_addbuku_tambah', $upload_error);

                            // Rollback the transaction
                            $this->db->trans_rollback();

                            // Redirect to the desired page with refresh
                            redirect(base_url() . 'admin/data_buku/tambah/', 'refresh');
                            die;
                        }
                        if (isset($fullpath)) {
                            $path[] = $fullpath;
                        }
                    }


                    $data_upload[] = array(
                        'file_name' => is_array($arrfilename) ? json_encode($arrfilename) : $arrfilename,
                        'raw_name' => is_array($arrrawname) ? json_encode($arrrawname) : $arrrawname,
                        'no_klas' => $data['no_klas'],
                        'ISBN' => $data['ISBN']
                    );
                }
                if (!empty($data_upload)) {
                    for ($i = 0; $i < count($data_upload); $i++) {
                        if (!$this->Md_siperpus_data_buku->addDataFile($data_upload[$i])) {
                            @unlink($path[$i]);
                        }
                    }
                }

                if ($this->db->trans_status() === true) {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('success_addbuku_list', 'Data Berhasil disimpan');
                    // echo json_encode(array('status' => 'success', 'message' => ''));
                    // die;
                    redirect(
                        base_url() . 'admin/data_buku',
                        'refresh'
                    );
                    die;
                } else {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('error_addbuku_tambah', 'Data gagal disimpan');

                    // Redirect to the desired page with refresh
                    redirect(base_url() . 'admin/data_buku/tambah/', 'refresh');
                    die;
                }
            }
            //die;
            redirect(
                base_url() . 'admin/data_buku',
                'refresh'
            );
        } 
        else if ($param1 == 'update') {
            $data = array(
                'no_klas' => trim($this->input->post('no_klas2')),
                'ISBN' => trim($this->input->post('isbn2')),
                'idkategori' => $this->input->post('kategori_buku'),
                'judul' => $this->input->post('judul'),
                'cetakkatalog_judulpenggal' => $this->input->post('cetakkatalog_judulpenggal'),
                'judulasli' => $this->input->post('judulasli'),
                'allow_review' => $this->input->post('allow_review'),
                'deskripsi' => $this->input->post('deskripsi'),
                'penulis' => $this->input->post('penulis'),
                'penyadur' => $this->input->post('penyadur'),
                'penerjemah' => $this->input->post('penerjemah'),
                'penyusun' => $this->input->post('penyusun'),
                'penyunting' => $this->input->post('penyunting'),
                'illustrator' => $this->input->post('illustrator'),
                'editor' => $this->input->post('editor'),
                'edisi' => $this->input->post('edisi'),
                'cetakan' => $this->input->post('cetakan'),
                'kd_penerbit' => $this->input->post('kd_penerbit'),
                'thn_terbit' => $this->input->post('thn_terbit'),
                'jilid' => $this->input->post('jilid'),
                'hlm_romawi' => $this->input->post('hlm_romawi'),
                'jml_hal' => $this->input->post('jml_hal'),
                'ilustrasi' => $this->input->post('ilustrasi'),
                'tabel' => $this->input->post('tabel'),
                'ukuran_fisik' => $this->input->post('ukuran_fisik'),
                'bibliografi' => $this->input->post('bibliografi'),
                'indeks' => $this->input->post('indeks'),
                'bahasa' => $this->input->post('bahasa'),
                'no_rak' => $this->input->post('no_rak'),
                'seri' => $this->input->post('seri'),
                'tajuk' => $this->input->post('tajuk'),
                'tajuksubyek' => $this->input->post('tajuksubyek'),
                'displayed' => $this->input->post('displayed'),
                'is_baca' => 'Ya',
                'is_download' => 'Ya',
                'tanggal' => $date->format('Y-m-d H:i:s')
                //'matkul' => $this->input->post('matkul')
            );
            $this->db->trans_begin();
            //cek file buku
            if (!empty($_FILES['file']['name'])) {
                $filesCount = count($_FILES['file']['name']);
                $isbacabukufileCount = count($this->input->post('is_baca_file'));

                $isdownloadbukufileCount = count($this->input->post('is_download_file'));
                if ($filesCount === $isbacabukufileCount && $filesCount === $isdownloadbukufileCount) {
                } else {
                    // The counts are not the same, handle the error
                    //echo "Error: The number of uploaded files, 'Is Baca' selections, and 'Is Download' selections must be the same.";
                    // Optionally, you can set an error message in the session and redirect to a form or another page

                    $this->session->set_flashdata('error_updatebuku', 'Total Buku dan Option Is Baca dan Is Download Tidak sama');

                    // Redirect to the desired page with refresh
                    redirect(base_url() . 'admin/data_buku/edit/' . trim($this->input->post('isbn2')) . '/' . trim($this->input->post('no_klas2')), 'refresh');
                    die;
                }
            }
            $file_id = $this->input->post('file_id');
            $file = $this->Md_siperpus_buku_file->getFileByFileId($file_id);

            $arrFilenameData = array();
            $removefile = array();
            $arrRawname = array();
            if ($file) {
                if ($file->file_name != '' && $file->file_name != null) {
                    $versi_file = determine_version($file->file_name);

                    if ($versi_file == 1) {
                        $decoded_data_filename = json_decode($file->file_name, true);
                        $decoded_data_rawname = json_decode($file->raw_name, true);
                        for ($i = 0; $i < count($decoded_data_filename); $i++) {
                            $isbaca = $this->input->post('is_baca_file_' . $i);
                            $is_download = $this->input->post('is_download_file_' . $i);
                            if ($isbaca) {
                                array_push(
                                    $arrFilenameData,
                                    array(
                                        'filename' => $decoded_data_filename[$i],
                                        'is_baca' => $isbaca,
                                        'is_download' => $is_download,
                                    )
                                );

                                array_push(
                                    $arrRawname,
                                    array(
                                        'filename' => $decoded_data_rawname[$i],
                                        'is_baca' => $isbaca,
                                        'is_download' => $is_download,
                                    )
                                );
                            } else {
                                array_push(
                                    $removefile,
                                    'uploads/files/' . $decoded_data_filename[$i]
                                );
                            }
                        }
                    } else if ($versi_file == 2) {
                        $decoded_data_filename = json_decode($file->file_name, true);
                        $decoded_data_rawname = json_decode($file->raw_name, true);

                        for ($i = 0; $i < count($decoded_data_filename); $i++) {
                            $isbaca = $this->input->post('is_baca_file_' . $i);
                            $is_download = $this->input->post('is_download_file_' . $i);
                            if ($isbaca) {

                                array_push(
                                    $arrFilenameData,
                                    array(
                                        'filename' => $decoded_data_filename[$i]['filename'],
                                        'is_baca' => $isbaca,
                                        'is_download' => $is_download,
                                    )
                                );

                                array_push(
                                    $arrRawname,
                                    array(
                                        'filename' => $decoded_data_rawname[$i]['filename'],
                                        'is_baca' => $isbaca,
                                        'is_download' => $is_download,
                                    )
                                );
                            } else {
                                array_push(
                                    $removefile,
                                    'uploads/files/' . $decoded_data_filename[$i]['filename']
                                );
                            }
                        }
                    } else if ($versi_file == 3) {

                        $isbaca = $this->input->post('is_baca_file_0');
                        $is_download = $this->input->post('is_download_file_0');
                        if ($isbaca) {
                            array_push(
                                $arrFilenameData,
                                array(
                                    'filename' => $file->file_name,
                                    'is_baca' => $isbaca,
                                    'is_download' => $is_download,
                                )
                            );

                            array_push(
                                $arrRawname,
                                array(
                                    'filename' => $file->raw_name,
                                    'is_baca' => $isbaca,
                                    'is_download' => $is_download,
                                )
                            );
                        } else {
                            array_push(
                                $removefile,
                                'uploads/files/' . $file->file_name
                            );
                        }
                    }
                }
            }





            $this->Md_siperpus_buku_prodi->hapusBukuProdiByISBN($data['ISBN'], $data['no_klas']);
            $prodi = $this->input->post('prodi');
            if ($prodi) {
                for ($i = 0; $i < sizeof($prodi); $i++) {
                    $dataBukuProdi = array(
                        'no_klas' => $this->input->post('no_klas'),
                        'ISBN' => $this->input->post('isbn'),
                        'idmspst' => $prodi[$i],
                        'idkategori' => $this->input->post('kategori_buku')
                    );
                    $this->Md_siperpus_buku_prodi->addBukuProdi($dataBukuProdi);
                }
            }


            if ($this->input->post('kategori_buku') == 3) {
                $data_kti = array(
                    'no_klas_ta' => $this->input->post('no_klas'),
                    'ISBN_ta' => $this->input->post('isbn'),
                    'nis_ta' => $this->input->post('nis_ta'),
                    'tabel_ta' => $this->input->post('tabel_ta'),
                    'lampiran_ta' => $this->input->post('lampiran_ta'),
                    'pembimbing_ta' => $this->input->post('pembimbing_ta'),
                );
                if ($data_kti['tabel_ta'] == '')
                    $data_kti['tabel_ta'] = 0;
                if ($data_kti['lampiran_ta'] == '')
                    $data_kti['lampiran_ta'] = 0;
                $this->Md_siperpus_data_buku->updateDataBukuTa($data_kti);
            }
            if (isset($_FILES['gambar'])) {
                $new_gambar = $date->format('YmdHis') . '-' . $_FILES["gambar"]['name'];
            } else {
                $new_gambar = NULL;
            }
            $config1 = array(
                'upload_path' => FCPATH . "uploads/covers",
                'allowed_types' => 'gif|jpg|png|jpeg|bmp',
                'file_name' => $new_gambar,
                'max_size' => 1024 * 1000
            );
            if ($data['ilustrasi'] == '') {
                $data['ilustrasi'] = 0;
            }
            if ($data['tabel'] == '') {
                $data['tabel'] = 0;
            }
            if ($data['allow_review'] == '') {
                $data['allow_review'] = 0;
            }
            if ($data['no_klas'] == '' || $data['judul'] == '' || $data['penulis'] == '') {
                //Empty Field
            } else {
                // upload image.

                $this->upload->initialize($config1);
                if (!$this->upload->do_upload('gambar')) {
                    //$data['cover'] = '';
                    // echo $this->upload->display_errors();die;
                } else if ($this->upload->do_upload('gambar')) {
                    $data_up = $this->upload->data(); //up new cover
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = $data_up['full_path'];
                    $config['maintain_ratio'] = TRUE;
                    $config['overwrite'] = TRUE;
                    $config['width'] = 200;
                    $config['height'] = 250;

                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();
                    $cover = $data_up['file_path'] . $this->input->post('cover'); //get full_path cover by id
                    @unlink($cover); //unlink full path
                    $data['cover'] = $data_up['file_name'];
                }
                $data['is_baca'] = 'Ya';
                $data['is_download'] = 'Ya';
                $this->Md_siperpus_data_buku->updateDataBuku($this->input->post('isbn'), $this->input->post('no_klas'), $data);
                $this->Md_siperpus_data_buku->updateNoklasBukuInventaris($this->input->post('isbn'), $this->input->post('no_klas'), $data['ISBN'], $data['no_klas']);
                $this->Md_siperpus_data_buku->updateNoklasBukuFile($this->input->post('isbn'), $this->input->post('no_klas'), $data['ISBN'], $data['no_klas']);
                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Edit',
                    'status' => 1,
                    'keterangan' => $this->session->userdata('username') . ' Melakukan Edit Buku ' . $data['no_klas'],
                    'IP' => $this->input->ip_address()
                );
                $this->Md_log->addLog($log);

                // upload file
                $updatedata = array();
                if (!empty($_FILES['file']['name']) && $_FILES['file']['name'][0] != '') {
                    // var_dump($_FILES['file']);die;
                    // var_dump($_FILES['file']);die;
                    $filesCount = count($_FILES['file']['name']);
                    for ($i = 0; $i < $filesCount; $i++) {
                        $_FILES['userFile']['name'] = $_FILES['file']['name'][$i];
                        $_FILES['userFile']['type'] = $_FILES['file']['type'][$i];
                        $_FILES['userFile']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
                        $_FILES['userFile']['error'] = $_FILES['file']['error'][$i];
                        $_FILES['userFile']['size'] = $_FILES['file']['size'][$i];
                        $sanitized_name = sanitize_filename($_FILES['userFile']['name']);
                        $new_file = $date->format('YmdHis') . '-' . $sanitized_name;
                        $config = array(
                            'upload_path' => FCPATH . "uploads/files",
                            'allowed_types' => 'pdf|doc|docx|xls|xlsx|zip|txt',
                            'file_name' => $new_file,
                            'max_size' => 1024 * 20
                        );

                        $this->upload->initialize($config);
                        if ($this->upload->do_upload('userFile')) {
                            $data_up = $this->upload->data();
                            array_push($arrFilenameData, array('filename' => $data_up['file_name'], 'is_baca' => $this->input->post('is_baca_file')[$i], 'is_download' => $this->input->post('is_download_file')[$i]));
                            array_push($arrRawname, array('filename' => $data_up['raw_name'], 'is_baca' => $this->input->post('is_baca_file')[$i], 'is_download' => $this->input->post('is_download_file')[$i]));

                            $fullpath = $data_up['full_path'];
                        } else {
                            // Ensure the error message is a string
                            $upload_error = (string) $this->upload->display_errors();
                            $upload_error = strip_tags($upload_error);  // Menghapus semua tag HTML
                            $upload_error = htmlspecialchars($upload_error, ENT_QUOTES, 'UTF-8');  // Mengonversi karakter spesial menjadi entitas HTML


                            $this->session->set_flashdata('error_updatebuku', $upload_error);
                            // Rollback the transaction
                            $this->db->trans_rollback();

                            // Set flashdata for the error message
                            redirect(base_url() . 'admin/data_buku/edit/' . trim($this->input->post('isbn2')) . '/' . trim($this->input->post('no_klas2')), 'refresh');
                            die;
                        }
                        if (isset($fullpath)) {
                            $path[] = $fullpath;
                        }
                    }
                }
                if ($arrFilenameData) {
                    $updatedata = array(
                        'file_name' => is_array($arrFilenameData) ? json_encode($arrFilenameData) : $arrFilenameData,
                        'raw_name' => is_array($arrRawname) ? json_encode($arrRawname) : $arrRawname,
                        'ISBN' => $data['ISBN'],
                        'no_klas' => $data['no_klas']
                    );
                } else {
                    $updatedata = array(
                        'file_name' => '',
                        'raw_name' => '',
                        'ISBN' => $data['ISBN'],
                        'no_klas' => $data['no_klas']
                    );
                }
                if ($file) {
                    $this->Md_siperpus_buku_file->updateData($file_id, $updatedata);
                } else {
                    $this->Md_siperpus_data_buku->addDataFile($updatedata);
                }


                if ($removefile) {
                    foreach ($removefile as $remvfile) {
                        delete_file($remvfile);
                    }
                }
                if ($this->db->trans_status() === true) {
                    $this->db->trans_commit();
                    // echo json_encode(array('status' => 'success', 'message' => ''));
                    // die;

                    $this->session->set_flashdata('success_addbuku_list', 'Data Berhasil disimpan');
                    redirect(
                        base_url() . 'admin/data_buku',
                        'refresh'
                    );
                    die;
                } else {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('error_updatebuku', 'Data gagal disimpan');
                    // echo json_encode(array('status' => 'gagal', 'message' => 'Data gagal disimpan'));
                    // die;
                    redirect(base_url() . 'admin/data_buku/edit/' . trim($this->input->post('isbn2')) . '/' . trim($this->input->post('no_klas2')), 'refresh');
                    die;
                }
            }

            redirect(base_url() . 'admin/data_buku', 'refresh');
        } 
        else if ($param1 == 'hapus') {
            $cover = $this->Md_siperpus_data_buku->getDataBukuByISBNdanNo_klas($x, $y);
            $file = $this->Md_siperpus_buku_file->getFileById($x, $y);
            $this->Md_siperpus_buku_file->hapusFileById($x, $y);
            @unlink(FCPATH . 'uploads/covers/' . $cover[0]->cover);
            @unlink(FCPATH . 'uploads/files/' . $file[0]['file_name']);
            $this->Md_siperpus_data_buku->hapusDataBuku($x, $y);
            $this->Md_siperpus_inventaris->hapusInventarisAll($x);
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Delete',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Melakukan Delete Buku ' . $y,
                'IP' => $this->input->ip_address()
            );
            $this->Md_log->addLog($log);
            echo json_encode(array("status" => TRUE, "msg" => "Data Buku Sukses Dihapus"));
        } 
        else if ($param1 == 'hapus_inv') {
            $this->Md_siperpus_inventaris->hapusInventaris($param4);
            $row = $this->Md_siperpus_inventaris->getNumRowInvByNoKlasISBN($y, $x);
            $this->Md_siperpus_data_buku->updateJmlBuku($x, $y, $row);
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Delete',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Melakukan Delete Buku No. Inv. ' . $param4,
                'IP' => $this->input->ip_address()
            );
            $this->Md_log->addLog($log);
            echo json_encode(array("status" => TRUE, "msg" => "Data Inventaris Sukses Dihapus"));
        } 
        else if ($param1 == 'edit') {





            $arrjnsfile = array();

            $page_data['data'] = $this->Md_siperpus_data_buku->getDataBukuByISBNdanNo_klas($x, $y);
            $page_data['data']['file'] = $this->Md_siperpus_data_buku->getDataFile($x, $y);

            if ($page_data['data']['file']) {
                foreach ($page_data['data']['file'] as $datafile) {

                    array_push($arrjnsfile, determine_version($datafile->file_name));
                }
            }

            $arrFile = array();
            $page_data['file_id'] = $page_data['data']['file'] ? $page_data['data']['file'][0]->file_id : null;
            if ($arrjnsfile) {
                if ($arrjnsfile[0] == 1) {
                    $decoded_data = json_decode($page_data['data']['file'][0]->file_name, true);

                    foreach ($decoded_data as $dt) {
                        array_push($arrFile, array(
                            'namafile' => $dt,
                            'is_baca' => 'Ya',
                            'is_download' => 'Ya'
                        ));
                    }
                } else if ($arrjnsfile[0] == 2) {
                    $decoded_data = json_decode($page_data['data']['file'][0]->file_name, true);

                    foreach ($decoded_data as $dt) {
                        array_push($arrFile, array(
                            'namafile' => $dt['filename'],
                            'is_baca' => $dt['is_baca'],
                            'is_download' => $dt['is_download']
                        ));
                    }
                } else if ($arrjnsfile[0] == 3) {
                    array_push($arrFile, array(
                        'namafile' => $page_data['data']['file'][0]->file_name,
                        'is_baca' => 'Ya',
                        'is_download' => 'Ya'
                    ));
                }
            }



            $page_data['data']['jnsfile'] = $arrjnsfile;
            $page_data['data']['filebuku'] = $arrFile;
            $page_data['data']['kel_buku'] = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
            $page_data['data']['penerbit'] = $this->Md_siperpus_penerbit->getPenerbitAll();
            $page_data['data']['bahasa'] = $this->Md_siperpus_bahasa->getBahasaAll();
            $page_data['data']['prodi'] = $this->Md_vwprodi->getProdiAll();
            $page_data['data']['prodi'] = json_decode(json_encode($page_data['data']['prodi']), true);
            // var_dump($page_data['data']['prodi']);die;
            $page_data['data_prodi'] = $this->Md_siperpus_buku_prodi->getBukuProdiByISBN($x, $y);
            $page_data['page_action'] = "edit";
            $page_data['page_title'] = 'Tambah Data Buku';
            $this->load->view('index', $page_data);
        } 
        else if ($param1 == 'delete_file') {
            @unlink(FCPATH . 'uploads/files/' . $param4);
            $this->Md_siperpus_data_buku->hapusDataFile($x, $y, $param4);
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Delete',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Melakukan Delete File Buku ' . $y,
                'IP' => $this->input->ip_address()
            );
            $this->Md_log->addLog($log);
            echo json_encode(array("status" => TRUE, "msg" => "File Buku Berhasil Dihapus"));
        } 
        else if ($param1 == 'get') {
            $page_data['page_action'] = 'view';
            $page_data['data'] = $this->Md_siperpus_data_buku->getDataBukuByISBNdanNo_klas($x, $y);
            // $page_data['data'][1]= $this->Md_siperpus_data_buku->getDataFile($x,$y);
            $page_data['data'] = json_decode(json_encode($page_data['data']), true);
            $page_data['prodi'] = $this->Md_siperpus_data_buku->getDataProdiByISBN_No_klas($x, $y);
            $page_data['barcode'] = $this->Md_siperpus_data_buku->getDataBarcodeByISBN_No_klas($x, $y);
            $this->load->view('index', $page_data);
        } 
        else if ($param1 == 'cek') {
            $data = $this->Md_siperpus_data_buku->getDataBukuByISBN($x, $y);
            if ($data) {
                echo json_encode(array("status" => TRUE, "msg" => "Data ISBN Sudah Ada"));
            } else {
                echo json_encode(array("status" => false, "msg" => "Data ISBN Dapat Digunakan"));
            }
        } 
        else if ($param1 == 'cek2') {
            // var_dump($this->input->post('isbn'));
            // echo json_encode("ini");die;
            $cekISBN = $this->Md_siperpus_data_buku->cekISBN($this->input->post('isbn'));
            $cekNoklasdanISBN = $this->Md_siperpus_data_buku->getDataBukuByISBN($this->input->post('isbn'), $this->input->post('no_klas'));
            if ($cekNoklasdanISBN != NULL && $cekISBN != NULL) {
                $data = "Nomor ISBN dan Nomor Klasifikasi Sudah Ada";
                echo json_encode($data);
            } else if ($cekISBN != NULL) {
                $data = "Nomor ISBN Sudah Ada";
                echo json_encode($data);
            } else {
                $data = "true";
                echo json_encode($data);
            }
        } 
        else if ($param1 == 'set_inv') {
            $page_data['data'] = $this->Md_siperpus_data_buku->getDataBukuByISBN($x, $y);
            $page_data['data']['asal_buku'] = $this->Md_siperpus_asal_buku->getAsalBukuAll();
            $page_data['isbn'] = $param2;
            $page_data['no_klas'] = $param3;
            $page_data['page_action'] = "set_inv";
            $page_data['page_title'] = 'Inventaris Buku';
            $this->load->view('index', $page_data);
        } 
        else if ($param1 == 'get_inv') {
            $total = $this->Md_siperpus_inventaris->countByISBN($x);
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;
            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');
            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');
            $sort = $this->input->post('datatable[sort][sort]');
            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');
            $data = array();
            $no = 0;
            $inv = $this->Md_siperpus_inventaris->getInventarisByISBNdanNoKlas($x, $y);
            foreach ($inv as $row) {
                $no++;
                $arr = array();
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['no_inv'] = $row->no_inv;
                $arr['isbn'] = $row->ISBN;
                $arr['tgl_inv'] = $row->tgl_inv;
                if ($row->asal == '-') {
                    $arr['asal'] = 'Tidak Tahu';
                } else if ($row->asal == 'H') {
                    $arr['asal'] = 'Hadiah';
                } else if ($row->asal == 'L') {
                    $arr['asal'] = 'Langganan';
                } else if ($row->asal == 'P') {
                    $arr['asal'] = 'Pembelian';
                } else if ($row->asal == 'S') {
                    $arr['asal'] = 'Sumbangan';
                } else if ($row->asal == 'G') {
                    $arr['asal'] = 'Ganti Buku';
                }
                $arr['ket'] = $row->ket;
                $arr['no_barcode'] = $row->no_barcode;
                $arr['no_barcode2'] = $row->no_barcode;
                $arr['no_klas'] = $row->no_klas;
                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } 
        else if ($param1 == 'list') {
            session_write_close();
            if ($this->input->post('datatable[query][generalSearch]') || $this->input->post('datatable[query][klas]') || $this->input->post('datatable[query][kel]') || $this->input->post('datatable[query][thn_terbit]'))
                $total = $this->Md_siperpus_data_buku->countFiltered();
            else
                $total = $this->Md_siperpus_data_buku->countAll();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;

            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');

            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');

            $sort = $this->input->post('datatable[sort][sort]');

            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');

            $data = array();
            $no = 0;
            $list = $this->Md_siperpus_data_buku->getDatatables();

            foreach ($list as $row) {
                $no++;
                $arr = array();
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['id'][0] = $row->no_klas;
                $arr['id'][1] = $row->ISBN;
                $arr['isbn'] = $row->ISBN;
                $arr['no_klas'] = $row->no_klas;
                $arr['no_rak'] = $row->no_rak;
                $arr['judul'] = $row->judul;
                $arr['penulis'] = $row->penulis;
                $arr['penerbit'] = $row->nama_penerbit;
                $arr['thn_terbit'] = $row->thn_terbit;
                $arr['jml_buku'] = $row->jml_buku;
                $arr['jml_pinjam'] = $row->jml_pinjam;
                $arr['mk'] = ""; //$row->mk;
                $arr['jml_prodi'] = $row->jml_prodi; //$row->prodi;
                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } 
        else if ($param1 == 'tambah') {
            $page_data['data']['kel_buku'] = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
            $page_data['data']['penerbit'] = $this->Md_siperpus_penerbit->getPenerbitAll();
            $page_data['data']['bahasa'] = $this->Md_siperpus_bahasa->getBahasaAll();
            $page_data['data']['prodi'] = $this->Md_vwprodi->getProdiAll();
            $page_data['page_action'] = $param1;
            $page_data['page_title'] = 'Tambah Data Buku';
            $this->load->view('index', $page_data);
        } 
        else if ($param1 == 'tambah_inv') {

            $data = array(
                'no_barcode' => $this->input->post('no_barcode'),
                'no_inv' => $this->input->post('no_inv'),
                'tgl_inv' => $this->input->post('tgl_inv'),
                'asal' => $this->input->post('asal'),
                'ket' => $this->input->post('ket'),
                'no_klas' => str_replace('%20', ' ', $this->input->post('no_klas')),
                'isbn' => str_replace('_', '/', $this->input->post('isbn')),
                'status' => $this->input->post('status'),
                'tanggal' => $date->format('Y-m-d H:i:s')
            );
            if ($data['ket'] == '')
                $data['ket'] = '-';
            $this->Md_siperpus_inventaris->addInventaris($data);
            $row = $this->Md_siperpus_inventaris->getNumRowInvByNoKlasISBN($data['no_klas'], $data['isbn']);
            $this->Md_siperpus_data_buku->updateJmlBuku($data['isbn'], $data['no_klas'], $row);
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Add',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Melakukan Add Inventori No. Inv. Buku ' . $data['no_inv'],
                'IP' => $this->input->ip_address()
            );
            $this->Md_log->addLog($log);
            echo json_encode(array("status" => TRUE, "msg" => "Data Inventaris Berhasil Ditambah"));
        } 
        else if ($param1 == 'get_barcode') {
            $output['barcode'] = $this->Md_siperpus_inventaris->getNoBarcode();
            $output['barcode']++;
            echo json_encode($output);
        } 
        else if ($param1 == 'get_data_inv') {
            $inv = $this->Md_siperpus_inventaris->getInventarisByBarcode($param2);
            echo json_encode($inv);
        } 
        else if ($param1 == 'edit_inv') {
            $data = array(
                'no_barcode' => $this->input->post('no_barcode2'),
                'no_inv' => $this->input->post('no_inv2'),
                'tgl_inv' => $this->input->post('tgl_inv2'),
                'asal' => $this->input->post('asal2'),
                'ket' => $this->input->post('ket2'),
                'no_klas' => str_replace('%20', ' ', $this->input->post('no_klas2')),
                'isbn' => str_replace('_', '/', $this->input->post('isbn2')),
                'status' => $this->input->post('status2'),
                'tanggal' => $date->format('Y-m-d H:i:s')
            );
            if ($data['ket'] == '')
                $data['ket'] = ' ';
            $this->Md_siperpus_inventaris->updateInventaris($data);
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Edit',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Melakukan Edit Inventori No.Inv. Buku ' . $data['no_inv'],
                'IP' => $this->input->ip_address()
            );
            $this->Md_log->addLog($log);
            echo json_encode(array("status" => TRUE, "msg" => "Data Inventaris Berhasil Diubah"));
            // $inv=$this->Md_siperpus_inventaris->getInventarisByBarcode($param2);
            // echo json_encode($inv);
        } 
        else if ($param1 == 'ubah_rak') {
            $page_data['klasifikasi'] = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
            $page_data['kategori'] = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
            $page_data['page_action'] = $param1;
            $page_data['page_title'] = 'Ubah Rak Buku';
            $this->load->view('index', $page_data);
        } 
        else if ($param1 == 'simpan_ubah_rak') {
            $data = $this->input->post('data'); //array('data' => , );
            // echo count($data);
            for ($i = 0; $i < count($data); $i++) {
                if ($data[$i][1] != '') {
                    $this->Md_siperpus_data_buku->updateRakBuku($data[$i][0], $data[$i][1]);
                    $log = array(
                        'user_id' => $this->session->userdata('idsys'),
                        'jenis_log' => 'Admin',
                        'jenis_akses' => 'Edit',
                        'status' => 1,
                        'keterangan' => $this->session->userdata('username') . ' Melakukan Ubah Rak Buku ' . $data[$i][0],
                        'IP' => $this->input->ip_address()
                    );
                    $this->Md_log->addLog($log);
                }
            }
            echo json_encode(array("status" => TRUE, "msg" => "Rak Buku Berhasil Diubah"));
        } 
        else if ($param1 == 'cetak_katalog') {
            $data = array();
            $data_final = $this->input->post('final');
            for ($i = 0; $i < sizeof($data_final); $i++) {
                $data[] = $this->Md_siperpus_data_buku->getDataKatalog($data_final[$i][0], $data_final[$i][1]);
            }
            echo json_encode($data);
        } 
        else if ($param1 == 'cetak_callnumber') {
            $data = array();
            $data_final = $this->input->post('final');
            for ($i = 0; $i < sizeof($data_final); $i++) {
                $data[] = $this->Md_siperpus_data_buku->getDataCallNumber($data_final[$i]);
            }
            echo json_encode($data);
        } 
        else if ($param1 == 'cetak_barcode') {
            $data = array();
            $data_final = $this->input->post('final');
            for ($i = 0; $i < sizeof($data_final); $i++) {
                $data[] = $this->Md_siperpus_data_buku->getBarcode($data_final[$i][0], $data_final[$i][1]);
            }
            echo json_encode($data);
        } 
        else if ($param1 == 'getpenerbit') {
            $data = $this->Md_siperpus_penerbit->getPenerbitAll();
            echo json_encode($data);
        } 
        else if ($param1 == 'getfile') {
            $data = $this->Md_siperpus_data_buku->getDataFile($x, $y);
            echo json_encode($data);
        } 
        elseif ($param1 == 'setfilestatus') {
            // echo $param2;
            $data = $this->Md_siperpus_buku_file->setFileStatus($param2);
            // var_dump($data);
            echo json_encode($data);
        } 
        else {
            $this->load->view('index', $page_data);
        }
    }

    /*     * Inventarisasi* */
    function inventaris($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();

        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Inventaris';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'inventaris';
        $page_data['page_now'] = 'Buku & Inventarisasi';
        $page_data['klasifikasi'] = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
        $page_data['data']['asal_buku'] = $this->Md_siperpus_asal_buku->getAsalBukuAll();
        $x = str_replace('_', '/', $param2);
        $y = str_replace('%20', ' ', $param3);

        if ($param1 == 'list') {
            $total = $this->Md_siperpus_inventaris->countFiltered();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;
            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');
            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');
            $sort = $this->input->post('datatable[sort][sort]');
            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');

            //mulai fetching data
            $data = array();
            $no = 0;
            $list = $this->Md_siperpus_inventaris->getDatatables();

            foreach ($list as $row) {
                $no++;
                $arr = array();
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['no_inv'] = $row->no_inv;
                $arr['tgl_inv'] = $row->tgl_inv;
                $arr['no_klas'] = $row->no_klas;
                if ($row->status == 'A')
                    $row->status = 'Ada';
                if ($row->status == 'D')
                    $row->status = 'Dipinjam';
                $arr['status'] = $row->status;
                if ($row->asal == '-')
                    $row->asal = "Tidak Tahu";
                if ($row->asal == 'H')
                    $row->asal = "Hadiah";
                if ($row->asal == 'L')
                    $row->asal = "Langganan";
                if ($row->asal == 'P')
                    $row->asal = "Pembelian";
                if ($row->asal == 'S')
                    $row->asal = "Sumbangan";
                if ($row->asal == 'G')
                    $row->asal = "Ganti";
                $arr['asal'] = $row->asal;
                $arr['ket'] = $row->ket;
                $arr['judul'] = $row->judul;
                $arr['id'] = $row->ISBN;
                $arr['no_barcode'] = $row->no_barcode;
                $arr['barcode'] = $row->no_barcode;
                $data[] = $arr;
            }
            // echo $perpage;die;
            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            echo json_encode($output); //output to json format
        } 
        else if ($param1 == 'update') {
            $data = array(
                'no_inv' => $this->input->post('no_inv'),
                'tgl_inv' => $this->input->post('tgl_inv'),
                'asal' => $this->input->post('asal'),
                'no_barcode' => $this->input->post('no_barcode'),
                'no_klas' => $this->input->post('no_klas_e'),
                'isbn' => $this->input->post('isbn_e'),
                'status' => $this->input->post('status_e'),
                'ket' => $this->input->post('ket_e'),
                'tanggal' => $this->input->post('tanggal_e')
            );
            $this->Md_siperpus_inventaris->updateInventaris($data);
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Edit',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Melakukan Update Inventaris No.Inv. ' . $data['no_inv'],
                'IP' => $this->input->ip_address()
            );
            $this->Md_log->addLog($log);
            echo json_encode(array("status" => TRUE, "msg" => "Inventaris Berhasil Diubah"));
        } 
        else if ($param1 == 'edit') {
            $data = $this->Md_siperpus_inventaris->getInventarisById($param2);
            echo json_encode($data);
        } 
        else if ($param1 == 'cetak_barcode') {
            $data = array();
            $data_final = $this->input->post('final');
            for ($i = 0; $i < sizeof($data_final); $i++) {
                $data[] = $this->Md_siperpus_inventaris->getBarcode($data_final[$i]);
            }
            echo json_encode($data);
        } 
        else if ($param1 == 'cetak_callnumber') {
            $data = array();
            $data_final = $this->input->post('final');
            for ($i = 0; $i < sizeof($data_final); $i++) {
                $data[] = $this->Md_siperpus_inventaris->getDataCallNumber($data_final[$i]);
                $x = explode("/", $data[$i][0]['no_inv']);
                $data[$i][0]['no_inv'] = $x[sizeof($x) - 1];
                if ($data[$i][0]['no_barcode'] == $data_final[$i]) {
                    $output[] = $data[$i][0];
                }
            }
            echo json_encode($output);
        } 
        else {
            $this->load->view('index', $page_data);
        }
    }

    /**     * buku_prodi* * */
    function buku_prodi($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Buku Jurusan';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'buku_prodi';
        $page_data['page_now'] = 'Buku & Inventarisasi';
        $page_data['klasifikasi'] = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
        $page_data['kategori'] = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
        $page_data['prodi'] = $this->Md_vwprodi->getProdiAll();
        $page_data['data']['asal_buku'] = $this->Md_siperpus_asal_buku->getAsalBukuAll();
        $x = str_replace('_', '/', $param2);
        $y = str_replace('%20', ' ', $param3);

        if ($param1 == 'list') {
            if ($this->input->post('datatable[query][generalSearch]'))
                $total = $this->Md_siperpus_buku_prodi->countFiltered();
            else
                $total = $this->Md_siperpus_buku_prodi->countAll();
            $page = intval($this->input->post('datatable[pagination][page]'));
            // echo $total;die;
            if ($page < 1)
                $page = 1;

            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');

            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');

            $sort = $this->input->post('datatable[sort][sort]');

            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');

            //mulai fetching data
            $data = array();
            $no = 0;
            $list = $this->Md_siperpus_buku_prodi->getDatatables();
            $list = json_decode(json_encode($list), FALSE);

            foreach ($list as $row) {
                $no++;
                $arr = array();
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['id'][0] = $row->no_klas;
                $arr['id'][1] = $row->ISBN;
                $arr['no_klas'] = $row->no_klas;
                $arr['judul'] = $row->judul;
                $arr['penerbit'] = $row->nama_penerbit . ": " . $row->thn_terbit;
                $arr['prodi'] = '<ul>';
                for ($i = 0; $i < sizeof($row->prodi); $i++) {
                    $arr['prodi'] .= '<li>' . $row->prodi[$i] . '</li>';
                }
                $arr['prodi'] .= '</ul>';
                $arr['jml_buku'] = $row->jml_buku;
                $data[] = $arr;
            }

            $meta = array();
            $meta['page'] = $page; //1
            $meta['pages'] = $pages; //0
            $meta['perpage'] = $perpage; //10
            $meta['total'] = $total; //2
            $meta['sort'] = $sort; //
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            echo json_encode($output); //output to json format
        } 
        else if ($param1 == 'update') {
            $data = array(
                'no_inv' => $this->input->post('no_inv'),
                'tgl_inv' => $this->input->post('tgl_inv'),
                'asal' => $this->input->post('asal'),
                'no_barcode' => $this->input->post('no_barcode'),
                'no_klas' => $this->input->post('no_klas_e'),
                'isbn' => $this->input->post('isbn_e'),
                'status' => $this->input->post('status_e'),
                'ket' => $this->input->post('ket_e'),
                'tanggal' => $this->input->post('tanggal_e')
            );
            $this->Md_siperpus_inventaris->updateInventaris($data);
            echo json_encode(array("status" => TRUE, "msg" => "Inventaris Berhasil Diubah"));
        } 
        else if ($param1 == 'edit') {
            $data = $this->Md_siperpus_inventaris->getInventarisById($param2);
            echo json_encode($data);
        } 
        else if ($param1 == 'cetak_katalog') {
            $data = array();
            $data_final = $this->input->post('final');
            for ($i = 0; $i < sizeof($data_final); $i++) {
                $data[] = $this->Md_siperpus_data_buku->getDataKatalog($data_final[$i][0], $data_final[$i][1]);
            }
            echo json_encode($data);
        } 
        else if ($param1 == 'cetak_callnumber') {
            $data = array();
            $data_final = $this->input->post('final');
            for ($i = 0; $i < sizeof($data_final); $i++) {
                $data[] = $this->Md_siperpus_data_buku->getDataCallNumber($data_final[$i]);
            }
            echo json_encode($data);
        } 
        else if ($param1 == 'cetak_barcode') {
            $data = array();
            $data_final = $this->input->post('final');
            for ($i = 0; $i < sizeof($data_final); $i++) {
                $data[] = $this->Md_siperpus_data_buku->getBarcode($data_final[$i][0], $data_final[$i][1]);
            }
            // var_dump($data);die;
            echo json_encode($data);
        } 
        else {
            $this->load->view('index', $page_data);
        }
    }

    /** Konfigurasi * */
    function konfigurasi_transaksi($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['page_action'] = 'list';
        $page_data['data'] = $this->Md_siperpus_config_transaksi->getConfigAll('m');
        $page_data['page_title'] = 'Pengaturan Transaksi';
        if ($param1 == 'update') {
            $setting1 = $this->input->post('setting1');
            $setting2 = $this->input->post('setting2');
            $setting3 = $this->input->post('setting3');
            $setting4 = $this->input->post('setting4');
            $setting5 = $this->input->post('setting5');
            if ($setting1 != '') {
                $data['jml_buku'] = $setting1;
            }
            if ($setting2 != '') {
                $data['lama'] = $setting2;
            }
            if ($setting3 != '') {
                $data['denda'] = $setting3;
            }
            if ($setting4 != '') {
                $data['perpanjang'] = $setting4;
            }
            if ($setting5 != '') {
                $data['masa_berlaku'] = $setting5;
            }
            $this->Md_siperpus_config_transaksi->updateConfig('m', $data);
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Update',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Megubah Pengaturan Transaksi',
                'IP' => $this->input->ip_address()
            );
            $this->Md_log->addLog($log);
            $this->session->set_flashdata('alert', 'alert-warning');
            $this->session->set_flashdata('flash_message', 'Update Konfigurasi Transaksi Sukses');
            redirect(base_url() . 'admin/konfigurasi_transaksi/', 'refresh');
        }
        if ($param1 == 'forceupdate') {
            $tgl = $this->input->post('tgl');
            if ($tgl == '') {
                $this->session->set_flashdata('alert', 'alert-warning');
                $this->session->set_flashdata('flash_message', 'Empty Field');
                redirect(base_url() . 'admin/konfigurasi_transaksi/', 'refresh');
            } else {
                $this->Md_siperpus_config_transaksi->updateConfig('m', $data);
                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Update',
                    'status' => 1,
                    'keterangan' => $this->session->userdata('username') . ' Megubah Pengaturan Transaksi',
                    'IP' => $this->input->ip_address()
                );
                $this->Md_log->addLog($log);
                $this->session->set_flashdata('alert', 'alert-warning');
                $this->session->set_flashdata('flash_message', 'Update Konfigurasi Transaksi Sukses');
                redirect(base_url() . 'admin/konfigurasi_transaksi/', 'refresh');
            }
        }
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'konfigurasi_transaksi';
        $page_data['page_now'] = 'Konfigurasi Transaksi';
        $this->load->view('index', $page_data);
    }

    /*     * Hari Libur* */
    public function hari_libur($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Pengaturan Hari Libur';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'hari_libur';
        $page_data['page_now'] = 'Konfigurasi & Transaksi';
        $page_data['year'] = $date->format('Y');
        if ($param1 == 'year' && $param2 != '') {
            if (is_numeric($param2) && $param2 > 1990)
                $page_data['year'] = $param2;

            $libur = $this->Md_siperpus_libur->getLibur($page_data['year']);
            $dates = '';
            $count = 0;
            if ($libur != '') {
                if ($libur & count($libur) > 0) {
                    foreach ($libur as $l) {
                        if ($count > 0)
                            $dates = $dates . ',';
                        $dates = $dates . '"' . $l . '"';
                        $count++;
                    }
                }
            }
            $page_data['dates'] = $dates;
            $this->load->view('index', $page_data);
        } else if ($param1 == 'change') {
            $tgl = $this->input->post('date');
            $m = $this->input->post('month');
            $y = $this->input->post('year');
            if ($m > 0 && $y > 0) {
                $this->Md_siperpus_libur->hapusLiburMonth($m, $y);
            }
            foreach ($tgl as $t) {
                //echo substr($t,0,strrpos($t,'GMT')-1);
                $dates = new DateTime(substr($t, 0, strrpos($t, 'GMT') - 1));
                $fdate = $dates->format('Y-m-d');
                //echo $fdate;
                $isLibur = $this->Md_siperpus_libur->isLibur($fdate);
                if (!$isLibur) {
                    $data['tgl_libur'] = $fdate;
                    $this->Md_siperpus_libur->addLibur($data);
                }
            }
            echo json_encode(array("status" => TRUE));
        } else {
            $libur = $this->Md_siperpus_libur->getLibur($page_data['year']);
            $dates = '';
            $count = 0;
            if ($libur) {
                if ($libur & count($libur) > 0) {
                    foreach ($libur as $l) {
                        if ($count > 0)
                            $dates = $dates . ',';
                        $dates = $dates . '"' . $l . '"';
                        $count++;
                    }
                }
            }
            $page_data['dates'] = $dates;

            $this->load->view('index', $page_data);
        }
    }

    /**     * Group User* * */
    function group_user($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Daftar Group User';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'group_user';
        $page_data['page_now'] = 'Administrator';
        $page_data['modul'] = $this->Md_siperpus_sysmodul->getModulAll();
        if ($param1 == 'submit') {
            $data['idsysgroup'] = $this->input->post('id');
            $data['name'] = $this->input->post('nama');
            $data['def_modul'] = $this->input->post('default');
            if ($data['def_modul'] == '' || $data['idsysgroup'] == '' || $data['name'] == '') {
                //Empty Field
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Empty Field');
                echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Empty Field"));
            } else {
                $Group = $this->Md_siperpus_sysgroup->getGroupById($this->input->post('id'));
                if ($Group) {
                    $this->session->set_flashdata('alert', 'alert-danger');
                    $this->session->set_flashdata('flash_message', 'ID telah dipakai,Group User gagal dibuat');
                    echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "ID telah dipakai, User Gagal Dibuat"));
                } else {
                    $this->Md_siperpus_sysgroup->addGroup($data);
                    $log = array(
                        'user_id' => $this->session->userdata('idsys'),
                        'jenis_log' => 'Admin',
                        'jenis_akses' => 'Add',
                        'status' => 1,
                        'keterangan' => $this->session->userdata('username') . ' Menambahkan Group User' . $data['name'],
                        'IP' => $this->input->ip_address()
                    );
                    $this->Md_log->addLog($log);
                    $this->session->set_flashdata('alert', 'alert-focus');
                    $this->session->set_flashdata('flash_message', 'Group User Sukses dibuat');
                    echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Group User Sukses dibuat"));
                    //redirect(base_url() . 'admin/klasifikasi_buku', 'refresh');
                }
            }
        } else if ($param1 == 'update') {
            $id1 = $this->input->post('id1');
            $data['idsysgroup'] = $this->input->post('id');
            $data['name'] = $this->input->post('nama');
            $data['def_modul'] = $this->input->post('default');
            if ($id1 == '' || $data['def_modul'] == '' || $data['idsysgroup'] == '' || $data['name'] == '') {
                //Empty Field
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Empty Field');
                echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Empty Field"));
            } else {
                $this->Md_siperpus_sysgroup->updateGroup($id1, $data);
                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Edit',
                    'status' => 1,
                    'keterangan' => $this->session->userdata('username') . ' Mengubah Group User' . $data['name'],
                    'IP' => $this->input->ip_address()
                );
                $this->Md_log->addLog($log);
                $this->session->set_flashdata('alert', 'alert-focus');
                $this->session->set_flashdata('flash_message', 'Group User Sukses diedit');
                echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Group User Sukses diedit"));
            }
        } else if ($param1 == 'edit' && $param2 != '') {
            $data = $this->Md_siperpus_sysgroup->getGroupById($param2);
            echo json_encode($data);
        } else if ($param1 == 'hapus' && $param2 != '') {
            $this->Md_siperpus_sysgroup->hapusGroupById($param2);
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Delete',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Menghapus Group User Dengan ID' . $param2,
                'IP' => $this->input->ip_address()
            );
            $this->Md_log->addLog($log);
            $this->session->set_flashdata('alert', 'alert-focus');
            $this->session->set_flashdata('flash_message', 'Group User Sukses Dihapus');
            echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Group User Sukses Dihapus"));
        } else if ($param1 == 'fetch') {
            $total = $this->Md_siperpus_sysgroup->countFiltered();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;
            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');
            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');
            $sort = $this->input->post('datatable[sort][sort]');
            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');
            $data = array();
            $no = 0;
            $list = $this->Md_siperpus_sysgroup->getDatatables();
            foreach ($list as $row) {
                $no++;
                $arr = array();
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['id'] = $row->idsysgroup;
                $arr['nama'] = $row->name;
                $modul = $this->Md_siperpus_sysmodul->getModulById($row->def_modul);
                if ($modul)
                    $arr['def'] = $modul[0]->name;
                else
                    $arr['def'] = '';

                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            $this->load->view('index', $page_data);
        }
    }

    /**     * Daftar User* * */
    function daftar_user($param1 = '', $param2 = '', $param3 = '')
    {
        // CodeIgniter 3 tidak otomatis men-decode segmen URI (mis. %20 untuk spasi),
        // sehingga ID user yang mengandung spasi (mis. "Asno Feri") perlu di-decode manual.
        $param2 = rawurldecode($param2);
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Daftar User Program';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'daftar_user';
        $page_data['page_now'] = 'Administrator';
        $page_data['sysgroup'] = $this->Md_siperpus_sysgroup->getGroupAll();
        if ($param1 == 'submit') {
            $data['idsysuser'] = $this->input->post('id');
            $data['name'] = $this->input->post('nama');
            $nip_pegawai = $this->input->post('nip_pegawai');
            $confirm = $this->input->post('password2');
            if ($nip_pegawai != '') {
                // Akun terhubung pegawai -> login lewat SSO, password lokal tidak wajib diisi manual
                $password = $this->input->post('password');
                $data['pass'] = $password != '' ? $password : substr(bin2hex(random_bytes(8)), 0, 20);
                $confirm = $data['pass'];
            } else {
                $data['pass'] = $this->input->post('password');
            }
            $data['idsysgroup'] = $this->input->post('group');
            $data['active'] = $this->input->post('status');
            if ($data['pass'] != $confirm) {
                //Empty Field
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Password Tidak Sama');
                echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Password Tidak Sama"));
            } else if ($data['idsysuser'] == '' || $data['pass'] == '' || $data['name'] == '' || $data['idsysgroup'] == '' || $data['active'] == '') {
                //Empty Field
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Empty Field');
                echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Empty Field"));
            } else {
                $user = $this->Md_siperpus_sysuser->getUserById($this->input->post('id'));
                $existing_by_nip = $nip_pegawai != '' ? $this->Md_siperpus_sysuser->getUserByNip($nip_pegawai) : [];
                if ($user) {
                    $this->session->set_flashdata('alert', 'alert-danger');
                    $this->session->set_flashdata('flash_message', 'ID telah dipakai, User gagal dibuat');
                    echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "ID telah dipakai, User Gagal Dibuat"));
                } else if (!empty($existing_by_nip)) {
                    $this->session->set_flashdata('alert', 'alert-danger');
                    $this->session->set_flashdata('flash_message', 'Pegawai ini sudah memiliki akun (ID: ' . $existing_by_nip[0]->idsysuser . ')');
                    echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Pegawai ini sudah memiliki akun (ID: " . $existing_by_nip[0]->idsysuser . ")"));
                } else {
                    $data['nip_pegawai'] = $nip_pegawai != '' ? $nip_pegawai : NULL;
                    $this->Md_siperpus_sysuser->addUser($data);
                    $log = array(
                        'user_id' => $this->session->userdata('idsys'),
                        'jenis_log' => 'Admin',
                        'jenis_akses' => 'Add',
                        'status' => 1,
                        'keterangan' => $this->session->userdata('username') . ' Menambahkan User ' . $data['name'],
                        'IP' => $this->input->ip_address()
                    );
                    $this->Md_log->addLog($log);
                    $this->session->set_flashdata('alert', 'alert-focus');
                    $this->session->set_flashdata('flash_message', 'User Sukses dibuat');
                    echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "User Sukses dibuat"));
                    //redirect(base_url() . 'admin/klasifikasi_buku', 'refresh');
                }
            }
        } else if ($param1 == 'update') {
            $id1 = $this->input->post('id1');
            $data['idsysuser'] = $this->input->post('id');
            $data['name'] = $this->input->post('nama');
            $nip_pegawai = $this->input->post('nip_pegawai');
            $pass = $this->input->post('password');
            $confirm = $this->input->post('password2');
            $data['idsysgroup'] = $this->input->post('group');
            $data['active'] = $this->input->post('status');
            // Akun terhubung pegawai -> login lewat SSO, password lokal tidak wajib diisi ulang saat edit
            $pass_wajib_kosong = ($nip_pegawai == '' && $pass == '');
            if ($pass != $confirm && $confirm != '') {
                //Empty Field
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Password Tidak Sama');
                echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Password Tidak Sama"));
            } else if ($id1 == '' || $data['idsysuser'] == '' || $pass_wajib_kosong || $data['name'] == '' || $data['idsysgroup'] == '' || $data['active'] == '') {
                //Empty Field
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Empty Field');
                echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Empty Field"));
            } else {
                $existing_by_nip = $nip_pegawai != '' ? $this->Md_siperpus_sysuser->getUserByNip($nip_pegawai) : [];
                $dipakai_lain = FALSE;
                foreach ($existing_by_nip as $row_existing) {
                    if ($row_existing->idsysuser != $id1) {
                        $dipakai_lain = $row_existing;
                    }
                }
                if ($dipakai_lain) {
                    $this->session->set_flashdata('alert', 'alert-danger');
                    $this->session->set_flashdata('flash_message', 'Pegawai ini sudah dipakai akun lain (ID: ' . $dipakai_lain->idsysuser . ')');
                    echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Pegawai ini sudah dipakai akun lain (ID: " . $dipakai_lain->idsysuser . ")"));
                    return;
                }
                $data['nip_pegawai'] = $nip_pegawai != '' ? $nip_pegawai : NULL;
                $this->Md_siperpus_sysuser->updateUser($id1, $data);
                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Edit',
                    'status' => 1,
                    'keterangan' => $this->session->userdata('username') . ' Melakukan Edit User ' . $data['name'],
                    'IP' => $this->input->ip_address()
                );
                $this->Md_log->addLog($log);
                $this->session->set_flashdata('alert', 'alert-focus');
                $this->session->set_flashdata('flash_message', 'User Sukses diedit');
                echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "User Sukses diedit"));
            }
        } else if ($param1 == 'hapus' && $param2 != '') {
            $this->Md_siperpus_sysuser->hapusUser($param2);
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Delete',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Menghapus User Dengan ID' . $param2,
                'IP' => $this->input->ip_address()
            );
            $this->Md_log->addLog($log);
            $this->session->set_flashdata('alert', 'alert-focus');
            $this->session->set_flashdata('flash_message', 'User Sukses Dihapus');
            echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "User Sukses Dihapus"));
        } else if ($param1 == 'pass' && $param2 != '') {
            $data = $this->Md_siperpus_sysuser->getUserById($param2);
            echo json_encode(array("status" => TRUE, "pass" => $data[0]->pass));
        } else if ($param1 == 'detail_pegawai' && $param2 != '') {
            $user = $this->Md_siperpus_sysuser->getUserById($param2);
            if (empty($user) || empty($user[0]->nip_pegawai)) {
                echo json_encode(array("status" => FALSE, "msg" => "Akun ini tidak terhubung ke data pegawai"));
            } else {
                $peg = $this->Md_pegawai->get_by_nip($user[0]->nip_pegawai);
                echo json_encode(array("status" => TRUE, "pegawai" => $peg));
            }
        } else if ($param1 == 'edit' && $param2 != '') {
            $data = $this->Md_siperpus_sysuser->getUserById($param2);
            if (!empty($data[0]->nip_pegawai)) {
                $peg = $this->Md_pegawai->get_by_nip($data[0]->nip_pegawai);
                $data[0]->pegawai_nama = $peg ? $peg->nama : '';
            }
            echo json_encode($data);
        } else if ($param1 == 'search_pegawai') {
            $search = $this->input->get('searchtext');
            $list = $this->Md_pegawai->get_datatables_aktif($search, 20, 0, 'nama', 'ASC');
            $items = array();
            foreach ($list as $row) {
                $items[] = array(
                    'id' => $row->nip,
                    'nip' => $row->nip,
                    'nama' => $row->nama . ' — ' . $row->nip,
                );
            }
            echo json_encode(array('items' => $items));
        } else if ($param1 == 'fetch') {
            $total = $this->Md_siperpus_sysuser->countFiltered();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;
            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');
            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');
            $sort = $this->input->post('datatable[sort][sort]');
            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');
            $data = array();
            $no = 0;
            $list = $this->Md_siperpus_sysuser->getDatatables();
            foreach ($list as $row) {
                $no++;
                $arr = array();
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['id'] = $row->idsysuser;
                $arr['nama'] = $row->name;
                $arr['pass'] = "******";
                $arr['group'] = $row->idsysgroup;
                $arr['tipe'] = !empty($row->nip_pegawai) ? 'SSO' : 'Manual';
                if ($row->active == 1)
                    $arr['active'] = "Aktif";
                else
                    $arr['active'] = "Non-Aktif";
                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            $this->load->view('index', $page_data);
        }
    }

    /**     * User Grant* * */
    function user_grant($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Daftar Hak Akses User';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'user_grant';
        $page_data['page_now'] = 'Administrator';
        $page_data['group'] = $this->Md_siperpus_sysgroup->getGroupAll();
        $page_data['modulall'] = $this->Md_siperpus_sysmodul->getModulAll();
        $page_data['modul'] = $this->Md_siperpus_sysmodul->getModulNoAkses('A');
        if ($param1 == 'submit') {
            $id = $this->Md_siperpus_sysgrant->getLastId();
            $data['idsysgrant'] = $id + 1;
            $data['idsysmodul'] = $this->input->post('modul');
            $data['idsysgroup'] = $this->input->post('group');
            if ($this->input->post('add') && $this->input->post('add') == 1)
                $data['allow_add'] = 1;
            else
                $data['allow_add'] = 0;
            if ($this->input->post('edit') && $this->input->post('edit') == 1)
                $data['allow_edit'] = 1;
            else
                $data['allow_edit'] = 0;
            if ($this->input->post('delete') && $this->input->post('delete') == 1)
                $data['allow_delete'] = 1;
            else
                $data['allow_delete'] = 0;
            if ($this->input->post('view') && $this->input->post('view') == 1)
                $data['allow_view'] = 1;
            else
                $data['allow_view'] = 0;
            if ($this->input->post('print') && $this->input->post('print') == 1)
                $data['allow_print'] = 1;
            else
                $data['allow_print'] = 0;
            if ($data['idsysmodul'] == '' || $data['idsysgroup'] == '') {
                //Empty Field
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Empty Field');
                echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Empty Field"));
            } else {
                $user = $this->Md_siperpus_sysgrant->getGrantByModul($data['idsysmodul'], $data['idsysgroup']);
                if ($user) {
                    $this->session->set_flashdata('alert', 'alert-danger');
                    $this->session->set_flashdata('flash_message', 'ID telah dipakai,Grant User gagal dibuat');
                    echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Akses Modul Telah Di tambahakan Sebelumnya"));
                } else {
                    $this->Md_siperpus_sysgrant->addGrant($data);
                    $log = array(
                        'user_id' => $this->session->userdata('idsys'),
                        'jenis_log' => 'Admin',
                        'jenis_akses' => 'Add',
                        'status' => 1,
                        'keterangan' => $this->session->userdata('username') . ' Menambahkan User Grant Pada Group ' . $data['idsysgroup'],
                        'IP' => $this->input->ip_address()
                    );
                    $this->Md_log->addLog($log);
                    $this->session->set_flashdata('alert', 'alert-focus');
                    $this->session->set_flashdata('flash_message', 'Grant User Sukses dibuat');
                    echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Grant User Sukses dibuat"));
                }
            }
        } else if ($param1 == 'update') {
            $id1 = $this->input->post('eid1');
            $data['idsysmodul'] = $this->input->post('emodul');
            $data['idsysgroup'] = $this->input->post('egroup');
            if ($this->input->post('eadd') == 1)
                $data['allow_add'] = 1;
            else
                $data['allow_add'] = 0;
            if ($this->input->post('eedit') == 1)
                $data['allow_edit'] = 1;
            else
                $data['allow_edit'] = 0;
            if ($this->input->post('edelete') == 1)
                $data['allow_delete'] = 1;
            else
                $data['allow_delete'] = 0;
            if ($this->input->post('eview') == 1)
                $data['allow_view'] = 1;
            else
                $data['allow_view'] = 0;
            if ($this->input->post('eprint') == 1)
                $data['allow_print'] = 1;
            else
                $data['allow_print'] = 0;
            if ($id1 == '' || $data['idsysmodul'] == '' || $data['idsysgroup'] == '') {
                echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Data Tidak Lengkap" . $id1));
            } else {
                $this->Md_siperpus_sysgrant->updateGrant($id1, $data);
                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Edit',
                    'status' => 1,
                    'keterangan' => $this->session->userdata('username') . ' Mengubah User Grant Pada Group ' . $data['idsysgroup'],
                    'IP' => $this->input->ip_address()
                );
                $this->Md_log->addLog($log);
                $this->session->set_flashdata('alert', 'alert-focus');
                $this->session->set_flashdata('flash_message', 'Hak Akses Sukses diedit');
                echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Hak Akses Sukses diedit"));
            }
        } else if ($param1 == 'hapus' && $param2 != '') {
            $this->Md_siperpus_sysgrant->hapusGrant($param2);
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Delete',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Menghapus User Grant Pada Group ' . $param2,
                'IP' => $this->input->ip_address()
            );
            $this->Md_log->addLog($log);
            $this->session->set_flashdata('alert', 'alert-focus');
            $this->session->set_flashdata('flash_message', 'Hak Akses Sukses Dihapus');
            echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Hak Akses Sukses Dihapus"));
        } else if ($param1 == 'edit' && $param2 != '') {
            $data = $this->Md_siperpus_sysgrant->getGrantById($param2);
            echo json_encode($data);
        } else if ($param1 == 'modul') {
            $g = $this->input->post('g');
            $modul = $this->Md_siperpus_sysmodul->getModulNoAkses($g);
            if ($g != '') {
                echo json_encode($modul);
            } else {
                $modul = $this->Md_siperpus_sysmodul->getModulAll();
                echo json_encode($modul);
            }
        } else if ($param1 == 'fetch') {
            $total = $this->Md_siperpus_sysgrant->countFiltered();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;
            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');
            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');
            $sort = $this->input->post('datatable[sort][sort]');
            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');
            $data = array();
            $no = 0;
            $list = $this->Md_siperpus_sysgrant->getDatatables();
            foreach ($list as $row) {
                $no++;
                $arr = array();
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['id'] = $row->idsysgrant;
                $modul = $this->Md_siperpus_sysmodul->getModulById($row->idsysmodul);
                if ($modul)
                    $arr['nama'] = $modul[0]->name;
                $arr['add'] = $row->allow_add;
                $arr['edit'] = $row->allow_edit;
                $arr['delete'] = $row->allow_delete;
                $arr['view'] = $row->allow_view;
                $arr['print'] = $row->allow_print;
                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            $this->load->view('index', $page_data);
        }
    }

    function import_data($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Import Data';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'import_data';
        $page_data['page_now'] = 'Administrator';
        $page_data['lastbarcode'] = $this->Md_siperpus_inventaris->getNoBarcode();
        if ($param1 == 'import') {
            $tipe = $this->input->post('tipe');
            $kolom = $this->input->post('kolom') - 1;
            if ($kolom < 0)
                $kolom = 0;
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'xls|xlsx';
            $this->upload->initialize($config);
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('imports')) {
                $dataupload['files'] = $this->upload->data();
                $file = './uploads/' . $dataupload['files']['file_name'];
                //load the excel library
                $this->load->library('excel');
                //read file from path
                $objPHPExcel = PHPExcel_IOFactory::load($file);
                //get only the Cell Collection
                $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                //extract to a PHP readable array format
                foreach ($cell_collection as $cell) {
                    $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                    $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                    $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();

                    //header will/should be in row 1 only. of course this can be modified to suit your need.
                    if ($tipe == 'inventaris') {
                        if ($row == 3) {
                            $header[$row][$column] = $data_value;
                        } else {
                            $arr_data[$row][$column] = $data_value;
                        }
                    } else {
                        if ($row == 2) {
                            $header[$row][$column] = $data_value;
                        } else {
                            $arr_data[$row][$column] = $data_value;
                        }
                    }
                }
                if ($tipe == 'inventaris') {
                    //check Header
                    $check = true;
                    $cell = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K');
                    $data['no_inv'] = '';
                    $data['tgl_inv'] = '';
                    $data['no_klas'] = '';
                    $data['status'] = '';
                    $data['asal'] = '';
                    $data['tanggal'] = '';
                    $data['no_barcode'] = '';
                    $data['ISBN'] = '';
                    $data['ket'] = '';
                    $head = '';
                    if (isset($header[3][$cell[0 + $kolom]])) {
                        if ($header[3][$cell[0 + $kolom]] != 'No Inventaris')
                            $check = false;
                        $head += 'noinv';
                    } else
                        $check = false;
                    if (isset($header[3][$cell[1 + $kolom]])) {
                        if ($header[3][$cell[1 + $kolom]] != 'Tanggal Inventaris')
                            $check = false;
                        $head += 'tglinv';
                    } else
                        $check = false;
                    if (isset($header[3][$cell[2 + $kolom]])) {
                        if ($header[3][$cell[2 + $kolom]] != 'No Klasifikasi')
                            $check = false;
                        $head += 'no klas';
                    } else
                        $check = false;
                    if (isset($header[3][$cell[3 + $kolom]])) {
                        if ($header[3][$cell[3 + $kolom]] != 'Status Buku')
                            $check = false;
                        $head += 'status';
                    } else
                        $check = false;
                    if (isset($header[3][$cell[4 + $kolom]])) {
                        if ($header[3][$cell[4 + $kolom]] != 'Asal Buku')
                            $check = false;
                        $head += 'asal';
                    } else
                        $check = false;
                    if (isset($header[3][$cell[5 + $kolom]])) {
                        if ($header[3][$cell[5 + $kolom]] != 'No Barcode')
                            $check = false;
                        $head += 'barcode';
                    } else
                        $check = false;
                    if (isset($header[3][$cell[6 + $kolom]])) {
                        if ($header[3][$cell[6 + $kolom]] != 'ISBN')
                            $check = false;
                        $head += 'isbn';
                    } else
                        $check = false;
                    if (isset($header[3][$cell[7 + $kolom]])) {
                        if ($header[3][$cell[7 + $kolom]] != 'Keterangan')
                            $check = false;
                    } else
                        $check = false;
                    //header lengkap
                    $res = array();
                    if ($check) {
                        foreach ($arr_data as $rows) {
                            if (substr($rows['A'], 0, 1) != '*' && $rows['A'] != "") {
                                $err = '';
                                $status = '';
                                $asal = '';
                                if (isset($rows[$cell[0 + $kolom]]))
                                    $data['no_inv'] = $rows[$cell[0 + $kolom]];
                                if (isset($rows[$cell[1 + $kolom]]))
                                    $data['tgl_inv'] = PHPExcel_Style_NumberFormat::toFormattedString($rows[$cell[1 + $kolom]], 'YYYY-MM-DD');
                                if (isset($rows[$cell[2 + $kolom]]))
                                    $data['no_klas'] = $rows[$cell[2 + $kolom]];
                                if (isset($rows[$cell[3 + $kolom]])) {
                                    if ($rows[$cell[3 + $kolom]] == "Aktif")
                                        $data['status'] = 'A';
                                    else
                                        $data['status'] = 'D';
                                }
                                if (isset($rows[$cell[4 + $kolom]])) {
                                    $asalbuku = $this->Md_siperpus_asal_buku->getAsalBukuByNama(strtolower($rows[$cell[4 + $kolom]]));
                                    if (count($asalbuku) > 0)
                                        $data['asal'] = $asalbuku[0]->id;
                                }
                                if (isset($rows[$cell[5 + $kolom]]))
                                    $data['no_barcode'] = $rows[$cell[5 + $kolom]];
                                if (isset($rows[$cell[6 + $kolom]]))
                                    $data['ISBN'] = $rows[$cell[6 + $kolom]];
                                if (isset($rows[$cell[7 + $kolom]]))
                                    $data['ket'] = $rows[$cell[7 + $kolom]];
                                $data['tanggal'] = date("Y-m-d H:i:s");
                                if ($data['status'] == 'A')
                                    $status = 'Aktif';
                                else
                                    $status = 'Tidak Aktif';
                                $buku = $this->Md_siperpus_data_buku->cekISBN($data['ISBN']);
                                if ($buku) {
                                    $invs = $this->Md_siperpus_inventaris->getInventarisByNoInv($data['no_inv']);
                                    if (count($invs) == 0) {
                                        $bar = $this->Md_siperpus_inventaris->getInventarisByBarcode($data['no_barcode']);
                                        if (count($bar) > 0) {
                                            $err = $err . " <span style=\"width: 110px;\"><span class=\"m-badge  m-badge--danger m-badge--wide\">No Barcode Already Exist</span></span>";
                                        } else {
                                            $this->Md_siperpus_inventaris->addInventaris($data);
                                            $row = $this->Md_siperpus_inventaris->getNumRowInvByNoKlasISBN($data['no_klas'], $data['ISBN']);
                                            $this->Md_siperpus_data_buku->updateJmlBuku($data['ISBN'], $data['no_klas'], $row);
                                            $err = "<span style=\"width: 110px;\"><span class=\"m-badge  m-badge--success m-badge--wide\">Success</span></span>";
                                        }
                                    } else {
                                        $err = $err . " <span style=\"width: 110px;\"><span class=\"m-badge  m-badge--danger m-badge--wide\">No Inventaris Already Exist</span></span>";
                                    }
                                } else {
                                    $err = $err . " <span style=\"width: 110px;\"><span class=\"m-badge  m-badge--danger m-badge--wide\">Data Buku Tidak Ditemukan</span></span>";
                                }
                                $rowres = array($data['no_inv'], $data['tgl_inv'], $data['no_klas'], $status, $data['asal'], $data['tanggal'], $data['ISBN'], $data['ket'], $err);
                                array_push($res, $rowres);
                            }

                            $this->session->set_flashdata('alert', 'alert-success');
                            $this->session->set_flashdata('flash_message', 'Import Data Inventaris Sukses ');
                            //redirect(base_url() . 'admin/import_data/', 'refresh');
                            $page_data['importResult'] = $res;
                            $page_data['importType'] = 'inventaris';
                        }
                    } else {
                        //do nothing header not same.
                        $this->session->set_flashdata('alert', 'alert-warning');
                        $this->session->set_flashdata('flash_message', 'Import Data Inventaris Gagal - Wrong Header');
                        redirect(base_url() . 'admin/import_data/', 'refresh');
                    }
                } else if ($tipe == 'buku') {
                    //check Header
                    $check = true;
                    $cell = array(
                        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
                        'AA', 'AB', 'AC', 'AD', 'AE'
                    );
                    $data['no_klas'] = '';
                    $data['ISBN'] = '';
                    $data['idkategori'] = '';
                    $data['judul'] = '';
                    $data['cetakkatalog_judulpenggal'] = '';
                    $data['judulasli'] = '';
                    $data['deskripsi'] = '';
                    $data['penulis'] = '';
                    $data['penyadur'] = '';
                    $data['penerjemah'] = '';
                    $data['penyusun'] = '';
                    $data['penyunting'] = '';
                    $data['illustrator'] = '';
                    $data['editor'] = '';
                    $data['edisi'] = '';
                    $data['cetakan'] = '';
                    $data['kd_penerbit'] = '';
                    $data['thn_terbit'] = '';
                    $data['jilid'] = '';
                    $data['hlm_romawi'] = '';
                    $data['jml_hal'] = '';
                    $data['ukuran_fisik'] = '';
                    $data['bibliografi'] = '';
                    $data['indeks'] = '';
                    $data['bahasa'] = '';
                    $data['no_rak'] = '';
                    $data['seri'] = '';
                    $data['tajuk'] = '';
                    $data['tajuksubyek'] = '';
                    $data['tanggal'] = '';
                    if (isset($header[2][$cell[0 + $kolom]])) {
                        if ($header[2][$cell[0 + $kolom]] != 'No Klasifikasi')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[1 + $kolom]])) {
                        if ($header[2][$cell[1 + $kolom]] != 'ISBN')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[2 + $kolom]])) {
                        if ($header[2][$cell[2 + $kolom]] != 'Kelompok Buku')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[3 + $kolom]])) {
                        if ($header[2][$cell[3 + $kolom]] != 'Judul Buku')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[4 + $kolom]])) {
                        if ($header[2][$cell[4 + $kolom]] != 'Penggalan Judul Katalog')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[5 + $kolom]])) {
                        if ($header[2][$cell[5 + $kolom]] != 'Judul Asli')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[6 + $kolom]])) {
                        if ($header[2][$cell[6 + $kolom]] != 'Penulis')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[7 + $kolom]])) {
                        if ($header[2][$cell[7 + $kolom]] != 'Penyadur')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[8 + $kolom]])) {
                        if ($header[2][$cell[8 + $kolom]] != 'Penerjemah')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[9 + $kolom]])) {
                        if ($header[2][$cell[9 + $kolom]] != 'Penyusun')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[10 + $kolom]])) {
                        if ($header[2][$cell[10 + $kolom]] != 'Penyunting')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[11 + $kolom]])) {
                        if ($header[2][$cell[11 + $kolom]] != 'Illustrator')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[12 + $kolom]])) {
                        if ($header[2][$cell[12 + $kolom]] != 'Editor')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[13 + $kolom]])) {
                        if ($header[2][$cell[13 + $kolom]] != 'Edisi')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[14 + $kolom]])) {
                        if ($header[2][$cell[14 + $kolom]] != 'Cetakan')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[15 + $kolom]])) {
                        if ($header[2][$cell[15 + $kolom]] != 'Penerbit')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[16 + $kolom]])) {
                        if ($header[2][$cell[16 + $kolom]] != 'Kota')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[17 + $kolom]])) {
                        if ($header[2][$cell[17 + $kolom]] != 'Tahun Terbit')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[18 + $kolom]])) {
                        if ($header[2][$cell[18 + $kolom]] != 'Jilid')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[19 + $kolom]])) {
                        if ($header[2][$cell[19 + $kolom]] != 'No. Hal. Romawi')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[20 + $kolom]])) {
                        if ($header[2][$cell[20 + $kolom]] != 'Jumlah Halaman')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[21 + $kolom]])) {
                        if ($header[2][$cell[21 + $kolom]] != 'Ukuran Fisik')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[22 + $kolom]])) {
                        if ($header[2][$cell[22 + $kolom]] != 'Bibliografi')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[23 + $kolom]])) {
                        if ($header[2][$cell[23 + $kolom]] != 'Index')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[24 + $kolom]])) {
                        if ($header[2][$cell[24 + $kolom]] != 'Bahasa')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[25 + $kolom]])) {
                        if ($header[2][$cell[25 + $kolom]] != 'No. Rak')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[26 + $kolom]])) {
                        if ($header[2][$cell[26 + $kolom]] != 'Seri')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[27 + $kolom]])) {
                        if ($header[2][$cell[27 + $kolom]] != 'Tajuk Utama')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[28 + $kolom]])) {
                        if ($header[2][$cell[28 + $kolom]] != 'Tajuk Subjek')
                            $check = false;
                    } else
                        $check = false;
                    if (isset($header[2][$cell[29 + $kolom]])) {
                        if ($header[2][$cell[29 + $kolom]] != 'Deskripsi')
                            $check = false;
                    } else
                        $check = false;
                    //header lengkap
                    $res = array();
                    if ($check) {
                        foreach ($arr_data as $rows) {
                            if (substr($rows['A'], 0, 1) != '*' && $rows['A'] != "") {
                                $err = '';
                                $nmkategori = "";
                                $nmpenerbit = "";
                                if (isset($rows[$cell[0 + $kolom]]))
                                    $data['no_klas'] = $rows[$cell[0 + $kolom]]; //harus ada
                                if (isset($rows[$cell[1 + $kolom]]))
                                    $data['ISBN'] = $rows[$cell[1 + $kolom]]; //harus ada
                                if (isset($rows[$cell[2 + $kolom]])) {
                                    $nmkategori = $rows[$cell[2 + $kolom]];
                                    $ktg = $this->Md_siperpus_kategori_buku->getKategoriByNama($nmkategori);
                                    if ($ktg && count($ktg) > 0)
                                        $data['idkategori'] = $ktg[0]->idkategori;
                                }
                                if (isset($rows[$cell[3 + $kolom]]))
                                    $data['judul'] = $rows[$cell[3 + $kolom]]; //harus ada
                                if (isset($rows[$cell[4 + $kolom]]))
                                    $data['cetakkatalog_judulpenggal'] = $rows[$cell[4 + $kolom]];
                                if (isset($rows[$cell[5 + $kolom]]))
                                    $data['judulasli'] = $rows[$cell[5 + $kolom]];
                                if (isset($rows[$cell[6 + $kolom]]))
                                    $data['penulis'] = $rows[$cell[6 + $kolom]]; //harus ada
                                if (isset($rows[$cell[7 + $kolom]]))
                                    $data['penyadur'] = $rows[$cell[7 + $kolom]]; //harus ada
                                if (isset($rows[$cell[8 + $kolom]]))
                                    $data['penerjemah'] = $rows[$cell[8 + $kolom]];
                                if (isset($rows[$cell[9 + $kolom]]))
                                    $data['penyusun'] = $rows[$cell[9 + $kolom]];
                                if (isset($rows[$cell[10 + $kolom]]))
                                    $data['penyunting'] = $rows[$cell[10 + $kolom]];
                                if (isset($rows[$cell[11 + $kolom]]))
                                    $data['illustrator'] = $rows[$cell[11 + $kolom]];
                                if (isset($rows[$cell[12 + $kolom]]))
                                    $data['editor'] = $rows[$cell[12 + $kolom]];
                                if (isset($rows[$cell[13 + $kolom]]))
                                    $data['edisi'] = $rows[$cell[13 + $kolom]];
                                if (isset($rows[$cell[14 + $kolom]]))
                                    $data['cetakan'] = $rows[$cell[14 + $kolom]];
                                if ($rows[$cell[15 + $kolom]] == '' || $rows[$cell[16 + $kolom]] == '') {
                                    $data['kd_penerbit'] = '';
                                } else {
                                    $nmpenerbit = $rows[$cell[15 + $kolom]];
                                    $ktpenerbit = $rows[$cell[16 + $kolom]];
                                    $pnb = $this->Md_siperpus_penerbit->getPenerbitByNamadanKota($nmpenerbit, $ktpenerbit);
                                    if ($pnb)
                                        $data['kd_penerbit'] = $pnb[0]->kd_penerbit;
                                    else if ($nmpenerbit != "" && $ktpenerbit != "") {
                                        $penerbit = array('nama_penerbit' => $nmpenerbit, 'kota' => $ktpenerbit);
                                        $data['kd_penerbit'] = $this->Md_siperpus_penerbit->addPenerbit($penerbit);
                                    }
                                } //harus ada
                                if (isset($rows[$cell[17 + $kolom]]))
                                    $data['thn_terbit'] = $rows[$cell[17 + $kolom]]; //harus ada
                                if (isset($rows[$cell[18 + $kolom]]))
                                    $data['jilid'] = $rows[$cell[18 + $kolom]];
                                if (isset($rows[$cell[19 + $kolom]]))
                                    $data['hlm_romawi'] = $rows[$cell[19 + $kolom]];
                                if (isset($rows[$cell[20 + $kolom]]))
                                    $data['jml_hal'] = $rows[$cell[20 + $kolom]]; //harus ada
                                if (isset($rows[$cell[21 + $kolom]]))
                                    $data['ukuran_fisik'] = $rows[$cell[21 + $kolom]]; //harus ada
                                if (isset($rows[$cell[22 + $kolom]]))
                                    $data['bibliografi'] = $rows[$cell[22 + $kolom]];
                                if (isset($rows[$cell[23 + $kolom]]))
                                    $data['indeks'] = $rows[$cell[23 + $kolom]];
                                if (isset($rows[$cell[24 + $kolom]])) {
                                    if (strtolower($rows[$cell[24 + $kolom]]) == "bahasa inggris")
                                        $data['bahasa'] = 'A';
                                    if (strtolower($rows[$cell[24 + $kolom]]) == "bahasa lainnya")
                                        $data['bahasa'] = 'L';
                                    if (strtolower($rows[$cell[24 + $kolom]]) == "bahasa indonesia")
                                        $data['bahasa'] = 'I';
                                    if (strtolower($rows[$cell[24 + $kolom]]) == "bahasa sunda")
                                        $data['bahasa'] = 'S';
                                    else
                                        $data['bahasa'] = 'I';
                                }
                                if (isset($rows[$cell[25 + $kolom]]))
                                    $data['no_rak'] = $rows[$cell[25 + $kolom]];
                                if (isset($rows[$cell[26 + $kolom]]))
                                    $data['seri'] = $rows[$cell[26 + $kolom]];
                                if (isset($rows[$cell[27 + $kolom]]))
                                    $data['tajuk'] = $rows[$cell[27 + $kolom]];
                                if (isset($rows[$cell[28 + $kolom]]))
                                    $data['tajuksubyek'] = $rows[$cell[28 + $kolom]]; //harus ada
                                if (isset($rows[$cell[29 + $kolom]]))
                                    $data['deskripsi'] = $rows[$cell[29 + $kolom]];
                                $data['tanggal'] = date("Y-m-d H:i:s");
                                $buku = $this->Md_siperpus_data_buku->cekISBN($data['ISBN']);
                                // echo $buku;
                                // var_dump(count($buku));die;
                                if (!$buku) {
                                    if ($data['ISBN'] != '' && $data['no_klas'] != '' && $data['judul'] != '' && $data['penulis'] != '' && $data['thn_terbit'] != '' && $data['jml_hal'] != '' && $data['ukuran_fisik'] != '' && $data['tajuksubyek'] != '' && $data['kd_penerbit']) {
                                        $this->Md_siperpus_data_buku->addDataBuku($data);
                                        $err = " <span style=\"width: 110px;\"><span class=\"m-badge  m-badge--success m-badge--wide\">Success</span></span>";
                                    } else {
                                        $err = " <span style=\"width: 110px;\"><span class=\"m-badge  m-badge--brand m-badge--wide\">Data Tidak Lengkap</span></span>";
                                    }
                                } else {
                                    $err = " <span style=\"width: 110px;\"><span class=\"m-badge m-badge--danger m-badge--wide\">Already Exist</span></span>";
                                }
                                $rowres = array($data['no_klas'], $data['ISBN'], $nmkategori, $data['judul'], $data['cetakkatalog_judulpenggal'], $data['judulasli'], $data['penulis'], $data['penyadur'], $data['penerjemah'], $data['penyusun'], $data['penyunting'], $data['illustrator'], $data['editor'], $data['edisi'], $data['cetakan'], $nmpenerbit, $data['thn_terbit'], $data['jilid'], $data['hlm_romawi'], $data['jml_hal'], $data['ukuran_fisik'], $data['bibliografi'], $data['indeks'], $data['bahasa'], $data['no_rak'], $data['seri'], $data['tajuk'], $data['tajuksubyek'], $data['tanggal'], $err);
                                array_push($res, $rowres);
                            }
                        }
                        $this->session->set_flashdata('alert', 'alert-success');
                        $this->session->set_flashdata('flash_message', 'Import Success Data imported ');
                        $page_data['importResult'] = $res;
                        $page_data['importType'] = 'buku';
                    } else {
                        //do nothing header not same.
                        $this->session->set_flashdata('alert', 'alert-warning');
                        $this->session->set_flashdata('flash_message', 'Import Failed - Wrong Header');
                        redirect(base_url() . 'admin/import_data/', 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('alert', 'alert-warning');
                    $this->session->set_flashdata('flash_message', 'Import Failed!');
                    redirect(base_url() . 'admin/import_data/', 'refresh');
                }
            } else {
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Import Failed ' . $this->upload->display_errors());
                redirect(base_url() . 'admin/import_data/', 'refresh');
            }
        }
        if (isset($file)) {
            unlink($file);
        }
        $this->load->view('index', $page_data);
    }

    /**     * Hilang* * */
    function hilang($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Status Buku (Hilang, Rusak, Diarsipkan, Dilelang)';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'hilang';
        $page_data['page_now'] = 'Transaksi';
        $page_data['klasifikasi'] = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
        $page_data['kategori'] = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
        $page_data['inventaris'] = $this->Md_siperpus_inventaris->getInventarisAll();
        if ($param1 == 'submit') {
            $inven = $this->input->post('inventaris');
            $id = $this->Md_siperpus_hilangrusak->getLastId();
            $data['tgl_hr'] = $this->input->post('tanggal');
            $data['ket'] = $this->input->post('ket');
            if ($this->input->post('anggota'))
                $data['no_anggota'] = $this->input->post('anggota');
            if ($this->input->post('harga'))
                $data['biaya_ganti'] = $this->input->post('harga');
            if (!$inven || $data['tgl_hr'] == '' || $data['ket'] == '') {
                //Empty Field
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Empty Field');
                echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Empty Field"));
            } else {
                $insert = 0;
                $failed = 0;
                $error = '';
                $invs = $this->Md_siperpus_inventaris->getInventarisByBarcode($inven);
                if (count($invs) > 0) {
                    $data['no_inv'] = $invs[0]->no_inv;
                    if ($this->Md_siperpus_hilangrusak->addHilang($data)) {
                        $insert++;
                        $log = array(
                            'user_id' => $this->session->userdata('idsys'),
                            'jenis_log' => 'Admin',
                            'jenis_akses' => 'Add',
                            'status' => 1,
                            'keterangan' => $this->session->userdata('username') . ' Melakukan Add Buku Hilang No.Inv.' . $inven,
                            'IP' => $this->input->ip_address()
                        );
                        $this->Md_log->addLog($log);
                    }
                } else {
                    $error = 'No. Barcode Tidak Ditemukan';
                }
                if ($insert == 0) {
                    $this->session->set_flashdata('alert', 'alert-danger');
                    $this->session->set_flashdata('flash_message', 'Gagal Melakukan Penambahan Buku Hilang');
                    echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Gagal Melakukan Penambahan Buku Hilang " . $error));
                } else {
                    $this->session->set_flashdata('alert', 'alert-focus');
                    $this->session->set_flashdata('flash_message', 'Sukses Melakukan Penambahan Buku Hilang');
                    echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Sukses Melakukan Penambahan Buku Hilang"));
                    //redirect(base_url() . 'admin/klasifikasi_buku', 'refresh');
                }
            }
        } else if ($param1 == 'update') {
            $id1 = $this->input->post('id1');
            $data['tgl_hr'] = $this->input->post('tanggal');
            $data['no_inv'] = $this->input->post('inventaris');
            $data['ket'] = $this->input->post('ket');
            if ($this->input->post('anggota'))
                $data['no_anggota'] = $this->input->post('anggota');
            if ($this->input->post('harga'))
                $data['biaya_ganti'] = $this->input->post('harga');
            if ($id1 == '' || $data['no_inv'] == '' || $data['tgl_hr'] == '' || $data['ket'] == '') {
                //Empty Field
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Empty Field');
                echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Empty Field"));
            } else {
                $this->Md_siperpus_hilangrusak->updateHilang($id1, $data);
                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Edit',
                    'status' => 1,
                    'keterangan' => $this->session->userdata('username') . ' Melakukan Edit Buku Hilang No.Inv.' . $data['no_inv'],
                    'IP' => $this->input->ip_address()
                );
                $this->Md_log->addLog($log);
                $this->session->set_flashdata('alert', 'alert-focus');
                $this->session->set_flashdata('flash_message', 'Transaksi Sukses diedit');
                echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Transaksi Sukses diedit"));
            }
        } else if ($param1 == 'kembali' && $param2 != '') {
            $data['ket'] = 'K';
            $this->Md_siperpus_hilangrusak->updateHilang($param2, $data);
            $ket = json_decode(json_encode($this->Md_siperpus_hilangrusak->getHilangById($param2)), true);
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Edit',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Melakukan Pengembalian Buku Hilang No.Inv.' . $ket[0]['no_inv'],
                'IP' => $this->input->ip_address()
            );
            $this->Md_log->addLog($log);
            $this->session->set_flashdata('alert', 'alert-focus');
            $this->session->set_flashdata('flash_message', 'Transaksi Sukses DiUpdate');
            echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Transaksi Sukses DiUpdate"));
        } else if ($param1 == 'hapus' && $param2 != '') {
            $ket = json_decode(json_encode($this->Md_siperpus_hilangrusak->getHilangById($param2)), true);
            $this->Md_siperpus_hilangrusak->hapusHilang($param2);
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Delete',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Melakukan Delete Buku Hilang No.Inv.' . $ket[0]['no_inv'],
                'IP' => $this->input->ip_address()
            );
            $this->Md_log->addLog($log);
            $this->session->set_flashdata('alert', 'alert-focus');
            $this->session->set_flashdata('flash_message', 'Transaksi Sukses Dihapus');
            echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Transaksi Sukses Dihapus"));
        } else if ($param1 == 'edit' && $param2 != '') {
            $data = $this->Md_siperpus_hilangrusak->getHilangById($param2);
            echo json_encode($data);
        } else if ($param1 == 'fetch') {
            $total = $this->Md_siperpus_hilangrusak->countFiltered();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;
            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');
            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');
            $sort = $this->input->post('datatable[sort][sort]');
            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');
            $data = array();
            $no = 0;
            $list = $this->Md_siperpus_hilangrusak->getDatatables();
            foreach ($list as $row) {
                $no++;
                $arr = array();
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['kd'] = $row->kd_hr;
                $buku = $this->Md_siperpus_buku->getBukuById($row->no_inv);
                if ($buku)
                    $arr['buku'] = $buku[0]->judul;
                $arr['tgl'] = $row->tgl_hr;
                $arr['anggota'] = $row->no_anggota;
                $arr['biaya'] = $row->biaya_ganti;
                $arr['keterangan'] = $row->ket;
                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            $this->load->view('index', $page_data);
        }
    }

    /**     * Presensi* * */
    function presensi($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'FORM KEHADIRAN DI PERPUSTAKAAN';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'presensi';
        $page_data['page_now'] = 'Transaksi';
        if ($param1 == 'submit') {
            // --- Form Validation ---
            $this->form_validation->set_rules('nomor', 'Nomor', 'required|trim');
            $this->form_validation->set_rules('tgl_presensi', 'Tanggal Presensi', 'required|trim');

            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', validation_errors());
                redirect(base_url() . 'admin/presensi/', 'refresh');
                return;
            }

            $nomor = $this->input->post('nomor', TRUE);
            $tgl   = $this->input->post('tgl_presensi', TRUE);

            // --- Cek keberadaan nomor (pegawai ATAU siswa) ---
            $is_pegawai = (bool) $this->Md_pegawai->get_by_nip($nomor);
            $is_siswa   = (bool) $this->Md_vwsiswa->getSiswaById($nomor);
            $exist      = $is_pegawai || $is_siswa;

            if (!$exist) {
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Nomor Tidak ditemukan');
                redirect(base_url() . 'admin/presensi/', 'refresh');
                return;
            }

            $data = [
                'nis'      => $nomor,
                'tanggal'  => $tgl,
            ];

            $this->Md_siperpus_presensi->addPresensi($data);
            $this->session->set_flashdata('alert', 'alert-focus');
            $this->session->set_flashdata('flash_message', 'Sukses Menambah Buku Tamu');
            redirect(base_url() . 'admin/presensi/', 'refresh');
            
        } else if ($param1 == 'submitnon') {
            $exist = false;
            $nama = $this->input->post('nama');
            $asal = $this->input->post('asal');
            $tgl = $this->input->post('tgl');
            $data['tanggal'] = $tgl;
            $data['nama'] = $this->input->post('nama');
            $data['asal'] = $this->input->post('asal');
            if ($data['nama'] == '' || $data['asal'] == '') {
                //Empty Field
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Empty Field');
                redirect(base_url() . 'admin/presensi/', 'refresh');
            } else {
                $this->Md_siperpus_presensi->addPresensiNon($data);
                $this->session->set_flashdata('alert', 'alert-focus');
                $this->session->set_flashdata('flash_message', 'Sukses Menambah Buku Tamu');
                redirect(base_url() . 'admin/presensi/', 'refresh');
            }
        } else if ($param1 == 'detail' && $param2 != '') {
            $data = $this->Md_siperpus_presensi->getDetailByTanggal($param2);
            $presensi = array();
            foreach ($data as $row) {
                $arr = array();
                $arr['tgl'] = $row->tgl;
                $arr['jam'] = $row->jam;
                $jenis = $row->jenis;
                if ($jenis == 'nonanggota') {
                    $arr['nomor'] = '';
                    $arr['nama'] = $row->nama;
                } else {
                    $arr['nomor'] = $row->nomor;
                    $mhs = $this->Md_vwsiswa->getSiswaById($row->nomor);
                    $pegawai = $this->Md_pegawai->get_by_nip($row->nomor);
                    $anggotaluar = $this->Md_siperpus_anggota_luar->getAnggotaLuarById($row->nomor);
                    if ($mhs) {
                        $arr['nama'] = $mhs[0]->nama;
                    } else {
                        if ($pegawai)
                            $arr['nama'] = $pegawai->nama;
                        else
                            $arr['nama'] = $anggotaluar[0]->nama;
                    }
                }
                $presensi[] = $arr;
            }
            $size = count($presensi);
            echo json_encode(array("status" => TRUE, "tanggal" => $param2, "table" => $presensi, "size" => $size));
        } else if ($param1 == 'fetch') {
            $data = array();
            $no = 0;
            $list = $this->Md_siperpus_presensi->getPresensi7Hari();
            foreach ($list as $row) {
                $no++;
                $arr = array();
                $arr['number'] = $no;
                $arr['tanggal'] = substr($row->tanggal, 0, 10);
                $arr['jumlah'] = $this->Md_siperpus_presensi->getJumlah(substr($row->tanggal, 0, 10));
                $data[] = $arr;
            }
            $output = array(
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            $this->load->view('index', $page_data);
        }
    }

    /**     * Transaksi* * */
    function transaksi($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $date = new DateTime();

        $id = $this->session->userdata('idsys');
        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Transaksi Buku';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'transaksi';
        $page_data['page_now'] = 'Transaksi';
        $page_data['search'] = false;
        $config = $this->Md_siperpus_config_transaksi->getConfigAll('m');
        $page_data['lama_pinjam'] = $config[0]->lama;
        $page_data['denda'] = $config[0]->denda;
        $page_data['fetch'] = $this->Md_siperpus_transaksi->getDatatables($param2);
        
        if ($param1 == 'submit') {
            $defaultBatas = date('Y-m-d', strtotime('+7 days'));
            $no_anggota   = $this->input->post('id1');
            $no_barcode   = $this->input->post('inventaris');
            $dtinv        = $this->Md_siperpus_inventaris->getInventarisAktifById($no_barcode);

            // Buku tidak ditemukan atau sudah tidak aktif
            if (!$dtinv) {
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Buku Tidak Tercatat/Sudah tidak aktif!');
                redirect(base_url() . 'admin/transaksi/search/' . $no_anggota, 'refresh');
                return;
            }

            // Validasi format tanggal Y-m-d
            $isValidDate = function (string $date): bool {
                $d = DateTime::createFromFormat('Y-m-d', $date);
                return $d && $d->format('Y-m-d') === $date;
            };

            $tgl_pinjam = $isValidDate($this->input->post('tanggalpinjam')) ? $this->input->post('tanggalpinjam') : date('Y-m-d');

            $batas = $isValidDate($this->input->post('tanggalkembali')) ? $this->input->post('tanggalkembali') : $defaultBatas;

            // Validasi field wajib
            if (empty($no_anggota) || empty($tgl_pinjam) || empty($batas)) {
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Cek kembali Field yang kosong!');
                redirect(base_url() . 'admin/transaksi/search/' . $no_anggota, 'refresh');
                return;
            }

            // Buku sedang dipinjam
            $isPinjam = $this->Md_siperpus_transaksi->isPinjam($dtinv->no_inv);
            if ($isPinjam) {
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Buku Sedang Dipinjam');
                redirect(base_url() . 'admin/transaksi/search/' . $no_anggota, 'refresh');
                return;
            }

            // Siapkan data transaksi
            $data = [
                'tid'        => $this->Md_siperpus_transaksi->getLastId() + 1,
                'no_inv'     => $dtinv->no_inv,
                'no_anggota' => $no_anggota,
                'tgl_pinjam' => $tgl_pinjam,
                'batas'      => $batas,
                'pinjam_ke'  => $this->Md_siperpus_transaksi->getPinjamKe($no_anggota, $dtinv->no_inv) + 1,
                'denda'      => 0,
                'kembali'    => 0,
            ];

            // Simpan transaksi
            if (!$this->Md_siperpus_transaksi->addTransaksi($data)) {
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Gagal Menambah Transaksi');
                redirect(base_url() . 'admin/transaksi/search/' . $no_anggota, 'refresh');
                return;
            }

            // Catat log peminjaman
            $this->Md_log->addLog([
                'user_id'     => $this->session->userdata('idsys'),
                'jenis_log'   => 'Admin',
                'jenis_akses' => 'Add',
                'status'      => 1,
                'keterangan'  => $no_anggota . ' Melakukan Peminjaman Buku Dengan No. Inv. ' . $dtinv->no_inv,
                'IP'          => $this->input->ip_address(),
            ]);

            $this->session->set_flashdata('alert', 'alert-focus');
            $this->session->set_flashdata('flash_message', 'Sukses Menambah Peminjaman Buku');
            redirect(base_url() . 'admin/transaksi/search/' . $no_anggota, 'refresh');
            
        } 
        else if ($param1 == 'search' && $param2 != '') {
            $search = $param2;
            // Default data anggota
            $arr = [
                'nomor'    => $search,
                'nama'     => '-',
                'kelas'    => '-',
                'alamat'   => '-',
                'telepon'  => '-',
            ];

            // Cek apakah input adalah No. Anggota Mahasiswa
            $mhs = $this->Md_vwsiswa->getSiswaById($search);
            if ($mhs) {
                // Mahasiswa sudah lulus / tidak aktif
                if ($mhs[0]->status_siswa == 'L') {
                    $this->session->set_flashdata('alert', 'alert-danger');
                    $this->session->set_flashdata('flash_message', 'Anggota sudah tidak aktif!');
                    redirect(base_url() . 'admin/transaksi', 'refresh');
                    return;
                }

                $arr['jenis_anggota'] = 'Mahasiswa';
                $arr['nama']          = $mhs[0]->nama;
                $arr['kelas']         = $mhs[0]->kelas;
                $arr['alamat']        = $mhs[0]->alamat;
                $arr['telepon']       = $mhs[0]->telepon;
            } 
            
            // Cek apakah input adalah No. Anggota pegawai
            $pegawai = $this->Md_pegawai->get_by_nip($search);
            if (!empty($pegawai)){

                $arr['jenis_anggota']   = 'Pegawai';
                $arr['nama']            = $pegawai->nama;
                $arr['kelas']           = '';
                $arr['alamat']          = '';
                $arr['telepon']         = $pegawai->telp;
                
            
            } 
            
            /*
            // Cek apakah input adalah Barcode Buku
            if ($bar = $this->Md_siperpus_inventaris->getInventarisAktifByBarcode($search)) {
                // Barcode ditemukan, cari no anggota dari transaksi aktif
                $tran = $this->Md_siperpus_transaksi->getTransaksiByNoInv($bar->no_inv);
                if ($tran && count($tran) > 0) {
                    $arr['nomor'] = $tran[0]->no_anggota;
                }
            
            }*/
            
            // Tidak ditemukan sama sekali
            if(empty($pegawai) && empty($mhs)) {
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'No Anggota Tidak Ditemukan');
                redirect(base_url() . 'admin/transaksi', 'refresh');
                return;
            }

            $page_data['page_action'] = 'detail';
            $page_data['search']      = $arr;
            $this->load->view('index', $page_data);
        } 
        else if ($param1 == 'search') {
            $search = $this->input->post('search', true);
            // Cek apakah input adalah No. Anggota Mahasiswa atau Pegawai
            $isMahasiswa = (bool) $this->Md_vwsiswa->getSiswaById($search);
            $isPegawai     = (bool) $this->Md_pegawai->get_by_nip_aktif($search);

            if (!$isMahasiswa && !$isPegawai) {
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'No. Anggota ' . $search . ' Tidak Ditemukan');
                redirect(base_url() . 'admin/transaksi', 'refresh');
                return;
            }
            redirect(base_url() . 'admin/transaksi/search/' . $search, 'refresh');
        } 
        else if ($param1 == 'hilang') {
            //insert ke table hilangrusak
            $idh = $this->input->post('idh');
            $tran = $this->Md_siperpus_transaksi->getTransaksiById($idh);
            if (count($tran) > 0) {
                $data['tgl_hr'] = date("Y-m-d");
                $data['ket'] = 'H';
                $data['no_anggota'] = $tran[0]->no_anggota;
                $data['biaya_ganti'] = $this->input->post('hilang');
                $data['no_inv'] = $tran[0]->no_inv;
                $this->Md_siperpus_hilangrusak->addHilang($data);

                $datatran['denda'] = str_replace(',', '', $this->input->post('hilang'));
                $this->Md_siperpus_transaksi->updateTransaksi($idh, $datatran);

                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Add',
                    'status' => 1,
                    'keterangan' => $data['no_anggota'] . ' Kehilangan Buku Dengan No. Inv. ' . $data['no_inv'],
                    'IP' => $this->input->ip_address()
                );
                $this->Md_log->addLog($log);

                echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Transaksi yang hilang Sukses DiUpdate"));
            } else {
                echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Transaksi Tidak ditemukan"));
            }
        } 
        else if ($param1 == 'perpanjang') {
            $idp = $this->input->post('idp');
            if ($idp) {
                $data['batas'] = $this->input->post('tanggalperpanjang');
                $this->Md_siperpus_transaksi->updateTransaksi($idp, $data);
                $data_log = $this->Md_siperpus_transaksi->getTransaksiById($idp);
                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Edit',
                    'status' => 1,
                    'keterangan' => $data_log[0]->no_anggota . ' Memperpanjang Peminjaman Buku Dengan No. Inv. ' . $data_log[0]->no_inv,
                    'IP' => $this->input->ip_address()
                );
                $this->Md_log->addLog($log);
                // echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Perpajangan Transaksi ke "+ $data['batas'] +" Sukses DiUpdate"));
                echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Perpajangan Transaksi ke  Sukses DiUpdate"));
            } else {
                echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Empty Field"));
            }
        } 
        else if ($param1 == 'kembali') {
            $tid = $this->input->post('idk');
            if ($tid) {
                $data['denda'] = str_replace(',', '', $this->input->post('denda'));
                $data['tgl_kembali'] = date("Y-m-d");
                $data['kembali'] = 1;
                $this->Md_siperpus_transaksi->updateTransaksi($tid, $data);
                $data_log = $this->Md_siperpus_transaksi->getTransaksiById($tid);
                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Edit',
                    'status' => 1,
                    'keterangan' => $data_log[0]->no_anggota . ' Mengembalikan Buku Dengan No. Inv. ' . $data_log[0]->no_inv,
                    'IP' => $this->input->ip_address()
                );
                $this->Md_log->addLog($log);
                echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Transaksi Sukses DiUpdate"));
            } else {
                echo json_encode(array("status" => TRUE, "alert" => "alert-danger", "msg" => "Empty Field"));
            }
        } 
        else if ($param1 == 'hapus' && $param2 != '') {
            $data_log = $this->Md_siperpus_transaksi->getTransaksiById($param2);
            $this->Md_siperpus_transaksi->hapusTransaksi($param2);
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Delete',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Melakukan Delete Peminjaman Buku No. Inv. ' . $data_log[0]->no_inv,
                'IP' => $this->input->ip_address()
            );
            $this->Md_log->addLog($log);
            echo json_encode(array("status" => TRUE, "alert" => "alert-focus", "msg" => "Transaksi Sukses Dihapus"));
        } 
        else if ($param1 == 'detail' && $param2 != '') {
            $tran = $this->Md_siperpus_transaksi->getTransaksiById($param2);
            $arr = array();
            if (count($tran) > 0) {
                $data = $this->Md_siperpus_inventaris->getInventarisById($tran[0]->no_inv);
                $arr['status'] = TRUE;
                $arr['referensi'] = '';
                $arr['klas'] = $data[0]->no_klas;
                $arr['tanggal'] = $data[0]->tgl_inv;
                $arr['isbn'] = $data[0]->ISBN;

                $buku = $this->Md_siperpus_buku->getBukuByISBN($data[0]->ISBN, $data[0]->no_klas);
                if ($buku[0]->bahasa == 'I')
                    $arr['bahasa'] = 'Indonesia';
                else if ($buku[0]->bahasa == 'A')
                    $arr['bahasa'] = 'Asing';
                else
                    $arr['bahasa'] = '';
                $arr['judul'] = $buku[0]->judul;
                $arr['tajuk'] = $buku[0]->tajuksubyek;
                $arr['penulis'] = $buku[0]->penulis;
                $arr['edisi'] = $buku[0]->edisi;
                $arr['cetakan'] = $buku[0]->cetakan;
                $arr['tahun'] = $buku[0]->thn_terbit;
                $arr['jumlah'] = $buku[0]->jml_hal;
                $arr['ukuran'] = $buku[0]->ukuran_fisik;
                $arr['rak'] = $buku[0]->no_rak;
                $arr['review'] = $buku[0]->review;
                $arr['deskripsi'] = $buku[0]->deskripsi;

                $penerbit = $this->Md_siperpus_penerbit->getPenerbitById($buku[0]->kd_penerbit);
                $arr['penerbit'] = $penerbit[0]->nama_penerbit;
                $arr['kota'] = $penerbit[0]->kota;

                $stok = $this->Md_siperpus_inventaris->getInventarisByISBNdanNoKlas($data[0]->ISBN, $data[0]->no_klas);
                $arr['stok'] = count($stok);

                $pinjam = $this->Md_siperpus_transaksi->getTransaksiDipinjamByISBN($data[0]->ISBN, $data[0]->no_klas);
                $arr['pinjam'] = count($pinjam);
            } else {
                $arr['status'] = FALSE;
            }
            echo json_encode($arr);
        } 
        else if ($param1 == 'fetch' && $param2 != '') {
            $total = $this->Md_siperpus_transaksi->countFiltered($param2);
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;
            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');
            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');
            $sort = $this->input->post('datatable[sort][sort]');
            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');
            $data = array();
            $no = 0;
            $list = $this->Md_siperpus_transaksi->getDatatables($param2);
            if ($page_data['fetch']) {
                foreach ($list as $row) {
                    $no++;
                    $arr = array();
                    $arr['number'] = ($perpage * ($page - 1)) + $no;
                    $arr['id'] = $row->tid;
                    $arr['noinv'] = $row->no_inv;
                    $arr['no_barcode'] = $row->no_barcode;
                    $buku = $this->Md_siperpus_buku->getBukuById($row->no_inv);
                    if ($buku)
                        $arr['buku'] = $buku[0]->judul;
                    $arr['tgl'] = date('d-M-Y', strtotime($row->tgl_pinjam));
                    $arr['batas'] = date('d-M-Y', strtotime($row->batas));

                    $Tgl = date('Y-m-d');
                    $tglkembali = strtotime($row->batas);
                    $datediff = strtotime($Tgl) - $tglkembali;
                    //mengecek tanggal yang lebih tinggi dan cek hari libur di siperpus_libur
                    if (strtotime($Tgl) < strtotime($row->batas)) {
                        $libur = $this->Md_siperpus_libur->haveLibur($Tgl, $row->batas);
                    } else {
                        $libur = $this->Md_siperpus_libur->haveLibur($row->batas, $Tgl);
                    }

                    @$lewathari = floor($datediff / (60 * 60 * 24)) - count($libur);
                    if ($lewathari > 0) {
                        $arr['terlambat'] = $lewathari . ' Hari';
                        $arr['denda'] = number_format($config[0]->denda * $lewathari, 0, ',', '.');
                    } else {
                        $arr['terlambat'] = '-';
                        $arr['denda'] = 0;
                    }
                    if ($row->tgl_kembali != '0000-00-00') {
                        $date1 = new DateTime($row->batas);
                        $date2 = new DateTime($row->tgl_kembali);
                        if ($date2 > $date1) {
                            $interval = $date1->diff($date2);
                            $arr['terlambat'] = $interval->days . ' Hari';
                        }
                    }
                    $arr['pinjam'] = $row->pinjam_ke;
                    $data[] = $arr;
                }
            }
            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } 
        else {
            $this->load->view('index', $page_data);
        }
    }

    /**     * Pengembalian* * */
    function pengembalian($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Pengembalian Buku';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'pengembalian';
        $page_data['page_now'] = 'Transaksi';
        $page_data['search'] = false;
        if ($param1 == 'search') {
            $config = $this->Md_siperpus_config_transaksi->getConfigAll('m');
            $page_data['lama_pinjam'] = $config[0]->lama;
            $page_data['denda'] = $config[0]->denda;
            $error = false;
            $search = $this->input->post('search');
            $bar = $this->Md_siperpus_inventaris->getInventarisByBarcode($search);
            if (count($bar) > 0) {
                $noinv = $bar[0]->no_inv;
                $isPinjam = $this->Md_siperpus_transaksi->isPinjam($noinv);
                if ($isPinjam) {
                    $arr['nomor'] = $noinv;
                    $arr['no_barcode'] = $search;
                    $buku = $this->Md_siperpus_buku->getBukuByISBN($bar[0]->ISBN, $bar[0]->no_klas);
                    $tran = $this->Md_siperpus_transaksi->getTransaksiTanpaKartu($noinv);
                    $noanggota = $tran[0]->no_anggota;
                    $arr['noanggota'] = $noanggota;
                    $mhs = $this->Md_vwsiswa->getSiswaById($noanggota);
                    if (!$mhs) {
                        $pegawai = $this->Md_pegawai->get_by_nip($noanggota);

                        if (!$pegawai) {
                            $error = true;
                        } else {
                            $arr['jenis_anggota']   = 'Pegawai';
                            $arr['nama']            = $pegawai->nama;
                            $arr['kelas']           = '';
                            $arr['alamat']          = '';
                            $arr['telepon']         = $pegawai->telp;
                        }
                    } else {
                        $arr['jenis_anggota'] = 'Mahasiswa';
                        $arr['nama'] = $mhs[0]->nama;
                        $arr['kelas'] = $mhs[0]->kelas;
                        $arr['alamat'] = $mhs[0]->alamat;
                        $arr['telepon'] = $mhs[0]->telepon;
                    }
                    if ($error) {
                        $this->session->set_flashdata('alert', 'alert-danger');
                        $this->session->set_flashdata('flash_message', 'No Barcode ' . $search . ' ditemukan<br> tetapi peminjam mungkin sudah terhapus keanggotaannya');
                        redirect(base_url() . 'admin/pengembalian', 'refresh');
                    } else {
                        $Tgl = date('Y-m-d');
                        $tglkembali = strtotime($tran[0]->batas);
                        $datediff = strtotime($Tgl) - $tglkembali;
                        //mengecek tanggal yang lebih tinggi dan cek hari libur di siperpus_libur
                        if (strtotime($Tgl) < strtotime($tran[0]->batas)) {
                            $libur = $this->Md_siperpus_libur->haveLibur($Tgl, $tran[0]->batas);
                        } else {
                            $libur = $this->Md_siperpus_libur->haveLibur($tran[0]->batas, $Tgl);
                        }
                        $count_libur = 0;
                        if (empty($libur)) {
                            $count_libur = 0;
                        } else {
                            $count_libur = count($libur);
                        }
                        $page_data['libur'] = $count_libur;
                        $lewathari = floor($datediff / (60 * 60 * 24)) - $count_libur;
                        if ($lewathari > 0) {
                            $dendabuku = $config[0]->denda * $lewathari;
                        } else {
                            $dendabuku = 0;
                        }
                        $page_data['dendabuku'] = $dendabuku;
                        $page_data['tglkembali'] = $Tgl;
                        $page_data['page_action'] = $search;
                        $page_data['detail'] = $tran;
                        $page_data['search'] = $arr;
                        $page_data['buku'] = $buku;

                        $this->load->view('index', $page_data);
                    }
                } else {
                    $this->session->set_flashdata('alert', 'alert-danger');
                    $this->session->set_flashdata('flash_message', 'No Barcode ' . $search . ' tidak ditemukan.<br> Belum Dipinjam Atau sudah dikembalikan');
                    redirect(base_url() . 'admin/pengembalian', 'refresh');
                }
            } else {
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'No Barcode ' . $search . ' Tidak Ditemukan');
                redirect(base_url() . 'admin/pengembalian', 'refresh');
            }
        } else if ($param1 == 'kembali') {
            $idk = $this->input->post('idk');
            if ($idk) {
                $data['denda'] = str_replace(',', '', $this->input->post('denda'));
                $data['tgl_kembali'] = date("Y-m-d", strtotime($this->input->post('tanggalkembali')));
                $data['tgl_post_pengembalian'] = date("Y-m-d h:i:s");
                $data['kembali'] = 1;
                //var_dump($data);die;
                $this->Md_siperpus_transaksi->updateTransaksi($idk, $data);
                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Edit',
                    'status' => 1,
                    'keterangan' => $this->input->post('no_anggota') . ' Mengembalikan Buku Dengan No. Inv. ' . $this->input->post('no_inv'),
                    'IP' => $this->input->ip_address()
                );
                $this->Md_log->addLog($log);
                $this->session->set_flashdata('alert', 'alert-success');
                $this->session->set_flashdata('flash_message', 'Sukses Melakukan Pengembalian');
                redirect(base_url() . 'admin/pengembalian', 'refresh');
            } else {
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Gagal Melakukan Pengembalian');
                redirect(base_url() . 'admin/pengembalian', 'refresh');
            }
        } else {
            $this->load->view('index', $page_data);
        }
    }

    function traninv($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Kembalikan Buku Tanpa Kartu Anggota';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'traninv';
        $page_data['page_now'] = 'Transaksi';
        $page_data['search'] = false;
        if ($param1 == 'search') {
            $error = false;
            $search = $this->input->post('search');
            $inv = $this->Md_siperpus_inventaris->getInventarisByNoInv($search);
            $bar = $this->Md_siperpus_inventaris->getInventarisByBarcode($search);
            if (count($inv) > 0 || count($bar) > 0) {
                if (count($bar) > 0) {
                    $noinv = $bar[0]->no_inv;
                    $ket = 'Barcode';
                } else {
                    $noinv = $search;
                    $ket = 'No. Inventaris';
                }
                $isPinjam = $this->Md_siperpus_transaksi->isPinjam($noinv);
                if ($isPinjam) {
                    $tran = $this->Md_siperpus_transaksi->getTransaksiTanpaKartu($noinv);
                    $noanggota = $tran[0]->no_anggota;
                    $mhs = $this->Md_vwsiswa->getSiswaById($noanggota);
                    if (!$mhs) {
                        $pegawai = $this->Md_pegawai->get_by_nip($noanggota);
                        if (!$pegawai)
                            $error = true;
                    }
                    if ($error) {
                        $this->session->set_flashdata('alert', 'alert-danger');
                        $this->session->set_flashdata('flash_message', $ket . ' ' . $search . ' ditemukan<br> tetapi peminjam mungkin sudah terhapus keanggotaannya');
                        redirect(base_url() . 'admin/traninv', 'refresh');
                    } else {
                        $this->session->set_flashdata('alert', 'alert-focus');
                        $this->session->set_flashdata('flash_message', $ket . ' ' . $search . ' ditemukan');
                        redirect(base_url() . 'admin/transaksi/search/' . $noanggota, 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('alert', 'alert-danger');
                    $this->session->set_flashdata('flash_message', $ket . ' ' . $search . ' tidak ditemukan.<br> Belum Dipinjam Atau sudah dikembalikan');
                    redirect(base_url() . 'admin/traninv', 'refresh');
                }
            } else {
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'No Inventaris/Barcode ' . $search . ' Tidak Ditemukan');
                redirect(base_url() . 'admin/traninv', 'refresh');
            }
        } else {
            $this->load->view('index', $page_data);
        }
    }

    /**     * Laporan* * */
    function laporan_anggota($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();

        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['src'] = '';
        $page_data['tanggalawal'] = '';
        $page_data['tanggalakhir'] = '';
        $page_data['jenis'] = 'mahasiswa';
        $page_data['prodi']       = '';

        if ($this->input->post('pilih') == 'pegawai')
            $page_data['jenis'] = 'pegawai';
        if ($this->input->post('pilih') == 'anggota+luar')
            $page_data['jenis'] = 'anggota+luar';
        $page_data['page_title'] = 'Laporan Data Anggota';

        if ($this->input->post('src'))
            $page_data['src'] = $this->input->post('src');
        if ($this->input->post('tanggalawal'))
            $page_data['tanggalawal'] = $this->input->post('tanggalawal');
        if ($this->input->post('tanggalakhir'))
            $page_data['tanggalakhir'] = $this->input->post('tanggalakhir');
        if ($this->input->post('prodi')) {
            $page_data['prodi'] = $this->input->post('prodi');
        }

        if ($param1 == 'export') {
            $jenis = 'anggota+luar';
            $tanggalawal = '';
            $tanggalakhir = '';
            $search = '';
            $pst = '';

            if ($param2 == 'excel') {
                $jenis = $this->input->post('epilih');
                $tanggalawal = $this->input->post('etanggalawal');
                $tanggalakhir = $this->input->post('etanggalakhir');
                $search = $this->input->post('esearch');
                $pst = $this->input->post('epst');
            } elseif ($param2 == 'web') {
                $jenis = $this->input->post('wpilih');
                $tanggalawal = $this->input->post('wtanggalawal');
                $tanggalakhir = $this->input->post('wtanggalakhir');
                $search = $this->input->post('wsearch');
                $pst = $this->input->post('wpst');
            }
            $page_data['jenis'] = $jenis;
            $page_data['laporan'] = 'laporan_anggota';
            $page_data['export'] = $param2;
            $page_data['page_title'] = 'Laporan Data Anggota ' . $jenis;
            $page_data['title'] = 'Laporan Anggota ';
            $page_data['data'] = $this->Md_laporan_anggota->getLaporan($jenis, $tanggalawal, $tanggalakhir, $search, $pst);
            $this->load->view('admin/excel', $page_data);
        } else if ($param1 == 'fetch') {
            $jenis = $param2;
            $total = $this->Md_laporan_anggota->countFiltered($jenis);
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;

            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');

            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');

            $sort = $this->input->post('datatable[sort][sort]');

            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');

            //mulai fetching data
            $data = array();
            $no = 0;
            $list = $this->Md_laporan_anggota->getDatatables($jenis); //var_dump($list);die;
            foreach ($list as $row) {
                $no++;
                $arr = array();
                if ($jenis == 'pegawai') {
                    $arr['number'] = ($perpage * ($page - 1)) + $no;
                    $arr['nip'] = $row->nip;
                    $arr['nama'] = $row->nama;
                    $arr['jenis'] = '';
                    $arr['tgl'] = $row->tanggal;
                    $arr['berlaku'] = $row->berlaku_sampai;
                } else if ($jenis == 'anggota+luar') {
                    $arr['number'] = ($perpage * ($page - 1)) + $no;
                    $arr['nama'] = $row->nama;
                    $arr['noid'] = $row->noid;
                    $arr['alamat'] = $row->alamat;
                    $arr['telepon'] = $row->telepon;
                    $arr['tgl_post'] = date('Y-m-d', strtotime($row->tgl_post));
                    $arr['tgl'] = $row->tgl_post;
                    $arr['berlaku'] = $row->tgl_post;
                    $arr['jenis'] = $row->jk;
                    $arr['instansi'] = $row->instansi_asal_nama;
                    $arr['telepon'] = $row->telepon;
                } else {
                    $arr['number'] = ($perpage * ($page - 1)) + $no;
                    $arr['nis'] = $row->nis;
                    $arr['nama'] = $row->nama;
                    $arr['kelas'] = $row->kelas;
                    $arr['jenis'] = $row->jk;
                    if ($row->status_siswa == 'A') {
                        $status_siswa = "Aktif";
                    }
                    $arr['status_siswa'] = $status_siswa;
                    $arr['status_anggota'] = $status_siswa;
                    $arr['tgl'] = $row->tgl_masuk;
                    $arr['berlaku'] = $row->berlaku_sampai;
                }

                $data[] = $arr;
            }

            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            $page_data['prodi_list'] = $this->Md_vwprodi->getProdiAll();
            $page_data['page_access'] = "admin";
            $page_data['page_name'] = 'laporan_anggota';
            $page_data['page_now'] = 'dashboard';
            $this->load->view('index', $page_data);
        }
    }

    function laporan_kataloginventaris($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();

        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['src'] = '';
        $page_data['tanggalawal'] = '';
        $page_data['tanggalakhir'] = '';
        $page_data['klas'] = '';
        $page_data['ktg'] = '';
        $page_data['page_title'] = 'Daftar Inventaris';

        $page_data['klasifikasi'] = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
        $page_data['kategori'] = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
        if ($this->input->post('src'))
            $page_data['src'] = $this->input->post('src');
        if ($this->input->post('klasifikasi'))
            $page_data['klas'] = $this->input->post('klasifikasi');
        if ($this->input->post('kategori'))
            $page_data['ktg'] = $this->input->post('kategori');

        $page_data['data'] = $this->Md_laporan_kataloginventaris->getKatalog($page_data['klas'], $page_data['ktg'], $page_data['src']);

        if ($param1 == 'export') {
            $klas = '';
            $ktg = '';
            $search = '';

            if ($param2 == 'excel') {
                $klas = $this->input->post('eklas');
                $ktg = $this->input->post('ektg');
                $search = $this->input->post('esearch');
            } elseif ($param2 == 'web') {
                $klas = $this->input->post('wklas');
                $ktg = $this->input->post('wktg');
                $search = $this->input->post('wsearch');
            }
            $page_data['nmktg'] = $this->Md_siperpus_kategori_buku->getKategoriById($this->input->post('kategori'));
            $page_data['laporan'] = 'laporan_kataloginventaris';
            $page_data['export'] = $param2;
            $page_data['page_title'] = 'Daftar Inventaris ';
            $page_data['title'] = 'Daftar Inventaris';
            $page_data['data'] = $this->Md_laporan_kataloginventaris->getKatalog($klas, $ktg, $search);
            $this->load->view('admin/excel', $page_data);
        } else if ($param1 == 'fetch') {
            $total = $this->Md_laporan_kataloginventaris->countFiltered();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;

            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');

            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');

            $sort = $this->input->post('datatable[sort][sort]');

            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');

            //mulai fetching data
            $data = array();
            $no = 0;
            $list = $this->Md_laporan_kataloginventaris->getDatatables();
            foreach ($list as $row) {
                $no++;
                $arr = array();
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['penulis'] = $row->penulis;
                $arr['judul'] = $row->judul;
                $arr['edisi'] = $row->edisi;
                $arr['penerbit'] = $row->kd_penerbit;
                $arr['tahun'] = $row->thn_terbit;
                $arr['ISBN'] = $row->ISBN;
                $arr['jumlah'] = $row->jml_buku;
                $arr['noklas'] = $row->no_klas;
                $arr['tanggal'] = $row->tanggal;
                $data[] = $arr;
            }

            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            $page_data['page_access'] = "admin";
            $page_data['page_name'] = 'laporan_kataloginventaris';
            $page_data['page_now'] = 'dashboard';
            $this->load->view('index', $page_data);
        }
    }

    function laporan_hilang($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();

        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['tanggalawal'] = '';
        $page_data['tanggalakhir'] = '';
        $page_data['page_title'] = 'Daftar Buku Hilang/Rusak';

        if ($this->input->post('tanggalawal'))
            $page_data['tanggalawal'] = $this->input->post('tanggalawal');
        if ($this->input->post('tanggalakhir'))
            $page_data['tanggalakhir'] = $this->input->post('tanggalakhir');
        if ($param1 == 'export') {
            $tanggalawal = '';
            $tanggalakhir = '';
            $search = '';

            if ($param2 == 'excel') {
                $tanggalawal = $this->input->post('etanggalawal');
                $tanggalakhir = $this->input->post('etanggalakhir');
                $search = $this->input->post('esearch');
            } elseif ($param2 == 'web') {
                $tanggalawal = $this->input->post('wtanggalawal');
                $tanggalakhir = $this->input->post('wtanggalakhir');
                $search = $this->input->post('wsearch');
            }
            $page_data['laporan'] = 'laporan_hilang';
            $page_data['export'] = $param2;
            $page_data['title'] = 'Daftar Buku Hilang/Rusak';
            $page_data['data'] = $this->Md_laporan_hilang->getLaporan($tanggalawal, $tanggalakhir, $search);
            $this->load->view('admin/excel', $page_data);
        } else if ($param1 == 'fetch') {
            $total = $this->Md_laporan_hilang->countFiltered();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;

            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');

            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');

            $sort = $this->input->post('datatable[sort][sort]');

            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');

            //mulai fetching data
            $data = array();
            $no = 0;
            $list = $this->Md_laporan_hilang->getDatatables();
            foreach ($list as $row) {
                $no++;
                $arr = array();
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['noinv'] = $row->no_inv;
                $arr['judul'] = $row->judul;
                $arr['tanggal'] = $row->tgl_hr;
                $arr['nomor'] = $row->no_anggota;
                $arr['biaya'] = $row->biaya_ganti;
                $arr['keterangan'] = $row->ket;
                $data[] = $arr;
            }

            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            $page_data['page_access'] = "admin";
            $page_data['page_name'] = 'laporan_hilang';
            $page_data['page_now'] = 'dashboard';
            $this->load->view('index', $page_data);
        }
    }

    function laporan_denda($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();

        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['src'] = '';
        $page_data['tanggalawal'] = '';
        $page_data['tanggalakhir'] = '';
        $page_data['jenis'] = 'mahasiswa';
        if ($this->input->post('pilih') == 'pegawai')
            $page_data['jenis'] = 'pegawai';
        if ($this->input->post('pilih') == 'anggota+luar')
            $page_data['jenis'] = 'anggota+luar';
        $page_data['page_title'] = 'Form Periode Denda';

        if ($this->input->post('src'))
            $page_data['src'] = $this->input->post('src');
        if ($this->input->post('tanggalawal'))
            $page_data['tanggalawal'] = $this->input->post('tanggalawal');
        if ($this->input->post('tanggalakhir'))
            $page_data['tanggalakhir'] = $this->input->post('tanggalakhir');

        if ($param1 == 'export') {
            $jenis = 'anggota+luar';
            $tanggalawal = '';
            $tanggalakhir = '';
            $search = '';

            if ($param2 == 'excel') {
                $jenis = $this->input->post('epilih');
                $tanggalawal = $this->input->post('etanggalawal');
                $tanggalakhir = $this->input->post('etanggalakhir');
                $search = $this->input->post('esearch');
            } elseif ($param2 == 'web') {
                $jenis = $this->input->post('wpilih');
                $tanggalawal = $this->input->post('wtanggalawal');
                $tanggalakhir = $this->input->post('wtanggalakhir');
                $search = $this->input->post('wsearch');
            }
            $page_data['laporan'] = 'laporan_denda';
            $page_data['export'] = $param2;
            $page_data['page_title'] = 'Form Periode Denda ' . $jenis;
            $page_data['title'] = 'Form Periode Denda ';
            $page_data['data'] = $this->Md_laporan_denda->getLaporan($jenis, $tanggalawal, $tanggalakhir, $search);
            $this->load->view('admin/excel', $page_data);
        } else if ($param1 == 'fetch') {
            $total = $this->Md_laporan_denda->countFiltered();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;

            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');

            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');

            $sort = $this->input->post('datatable[sort][sort]');

            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');

            //mulai fetching data
            $data = array();
            $no = 0;
            $list = $this->Md_laporan_denda->getDatatables();
            foreach ($list as $row) {
                $no++;
                $arr = array();
                if ($this->input->post('datatable[query][jenis]') == 'pegawai') {
                    $arr['number'] = ($perpage * ($page - 1)) + $no;
                    $arr['nomor'] = $row->no_anggota;
                    $arr['nama'] = $row->nama;
                    $arr['prodi'] = '';
                    $arr['judul'] = $row->judul;
                    $arr['tanggal'] = $row->tgl_kembali;
                    $arr['denda'] = $row->denda;
                } else if ($this->input->post('datatable[query][jenis]') == 'anggota+luar') {
                    $arr['number'] = ($perpage * ($page - 1)) + $no;
                    $arr['nomor'] = $row->no_anggota;
                    $arr['nama'] = $row->nama;
                    $arr['prodi'] = '';
                    $arr['judul'] = $row->judul;
                    $arr['tanggal'] = $row->tgl_kembali;
                    $arr['denda'] = $row->denda;
                } else {
                    $arr['number'] = ($perpage * ($page - 1)) + $no;
                    $arr['nomor'] = $row->no_anggota;
                    $arr['nama'] = $row->nama;
                    $arr['prodi'] = $row->kelas;
                    $arr['judul'] = $row->judul;
                    $arr['tanggal'] = $row->tgl_kembali;
                    $arr['denda'] = $row->denda;
                }

                $data[] = $arr;
            }

            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            $page_data['page_access'] = "admin";
            $page_data['page_name'] = 'laporan_denda';
            $page_data['page_now'] = 'dashboard';
            $this->load->view('index', $page_data);
        }
    }

    function laporan_presensi($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();

        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['src'] = '';
        $page_data['tanggalawal'] = '';
        $page_data['tanggalakhir'] = '';
        $page_data['jenis'] = 'mahasiswa';
        if ($this->input->post('pilih') == 'pegawai')
            $page_data['jenis'] = 'pegawai';
        if ($this->input->post('pilih') == 'anggota+luar')
            $page_data['jenis'] = 'anggota+luar';
        $page_data['page_title'] = 'Daftar Presensi Kunjungan Perpustakaan';

        $page_data['pilihprodi'] = 'All';
        if ($this->input->post('pilihprodi') == 'All')
            $page_data['pilihprodi'] = 'All';
        elseif ($this->input->post('pilihprodi') == 'D3-Gizi')
            $page_data['pilihprodi'] = 'D3-Gizi';
        elseif ($this->input->post('pilihprodi') == 'D3-Kebidanan')
            $page_data['pilihprodi'] = 'D3-Kebidanan';
        elseif ($this->input->post('pilihprodi') == 'D3-Keperawatan')
            $page_data['pilihprodi'] = 'D3-Keperawatan';
        elseif ($this->input->post('pilihprodi') == 'D4-Kebidanan')
            $page_data['pilihprodi'] = 'D4-Kebidanan';
        elseif ($this->input->post('pilihprodi') == 'D4-Keperawatan')
            $page_data['pilihprodi'] = 'D4-Keperawatan';
        elseif ($this->input->post('pilihprodi') == 'D3-Keperawatan (Kampus Kab. Indragiri Hulu)')
            $page_data['pilihprodi'] = 'D3-Keperawatan (Kampus Kab. Indragiri Hulu)';
        elseif ($this->input->post('pilihprodi') == 'D3-Keperawatan PSDKU Indragiri Hulu')
            $page_data['pilihprodi'] = 'D3-Keperawatan PSDKU Indragiri Hulu';
        similar_text($this->input->post('pilihprodi'), "D4-Kebidanan Alih Jenjang", $percent);
        if ($percent > 95)
            $page_data['pilihprodi'] = "D4-Kebidanan Alih Jenjang";

        if ($this->input->post('src'))
            $page_data['src'] = $this->input->post('src');
        if ($this->input->post('tanggalawal'))
            $page_data['tanggalawal'] = $this->input->post('tanggalawal');
        if ($this->input->post('tanggalakhir'))
            $page_data['tanggalakhir'] = $this->input->post('tanggalakhir');

        if ($param1 == 'export') {
            $jenis = 'anggota+luar';
            $tanggalawal = '';
            $tanggalakhir = '';
            $search = '';
            $p_prodi = 'All';

            if ($param2 == 'excel') {
                $jenis = $this->input->post('epilih');
                $tanggalawal = $this->input->post('etanggalawal');
                $tanggalakhir = $this->input->post('etanggalakhir');
                $search = $this->input->post('esearch');
                $p_prodi = $this->input->post('eprodi');
            } elseif ($param2 == 'web') {
                $jenis = $this->input->post('wpilih');
                $tanggalawal = $this->input->post('wtanggalawal');
                $tanggalakhir = $this->input->post('wtanggalakhir');
                $search = $this->input->post('wsearch');
                $p_prodi = $this->input->post('wprodi');
            }
            $page_data['laporan'] = 'laporan_presensi';
            $page_data['export'] = $param2;
            $page_data['page_title'] = 'Daftar Presensi Kunjungan Perpustakaan ';
            $page_data['title'] = 'Daftar ' . $jenis;
            $page_data['jenis'] = $jenis;
            $page_data['data'] = $this->Md_laporan_presensi->getLaporan($jenis, $tanggalawal, $tanggalakhir, $search, $p_prodi);
            $this->load->view('admin/excel', $page_data);
        } else if ($param1 == 'fetch') {
            $total = $this->Md_laporan_presensi->countFiltered();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;

            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');

            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');

            $sort = $this->input->post('datatable[sort][sort]');

            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');

            //mulai fetching data
            $data = array();
            $no = 0;
            $list = $this->Md_laporan_presensi->getDatatables();
            // var_dump($list);die;
            foreach ($list as $row) {
                $no++;
                $arr = array();
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['nomor'] = $row->nis;
                $arr['nama'] = $row->nama;
                $arr['jam'] = $row->jam;
                $arr['tanggal'] = $row->tanggal;
                if (isset($row->kelas)) {
                    $arr['prodi'] = $row->kelas;
                }
                $data[] = $arr;
            }

            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            $page_data['page_access'] = "admin";
            $page_data['page_name'] = 'laporan_presensi';
            $page_data['page_now'] = 'dashboard';
            $page_data['prodi'] = $this->Md_vwprodi->getProdiAll();
            $this->load->view('index', $page_data);
        }
    }

    function laporan_log($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Laporan Log Aktifitas';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'laporan_log';
        $page_data['page_now'] = 'Dashboard';
        $page_data['prodi'] = $this->Md_vwprodi->getProdiAll();
        $page_data['jenis_akses'] = $this->Md_log->getJenisAkses();

        if ($param1 == 'fetch') {
            $total = $this->Md_log->countFiltered();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;
            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');
            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');
            $sort = $this->input->post('datatable[sort][sort]');
            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');
            //mulai fetching data
            $data = array();
            $no = 0;
            $list = $this->Md_log->getDatatables();
            foreach ($list as $row) {
                $no++;
                $arr = array();
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['user_id'] = $row->user_id;
                $arr['tgl'] = $row->tgl;
                $arr['jenis_akses'] = $row->jenis_akses;
                $arr['keterangan'] = $row->keterangan;
                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            $this->load->view('index', $page_data);
        }
    }

    function laporan_belumkembali($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();

        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['src'] = '';
        $page_data['tanggalawal'] = '';
        $page_data['tanggalakhir'] = '';
        $page_data['jenis'] = 'mahasiswa';
        if ($this->input->post('pilih') == 'pegawai')
            $page_data['jenis'] = 'pegawai';
        if ($this->input->post('pilih') == 'anggota+luar')
            $page_data['jenis'] = 'anggota+luar';
        $page_data['page_title'] = 'Form Peminjaman Belum Kembali';

        if ($this->input->post('src'))
            $page_data['src'] = $this->input->post('src');
        if ($this->input->post('tanggalawal'))
            $page_data['tanggalawal'] = $this->input->post('tanggalawal');
        if ($this->input->post('tanggalakhir'))
            $page_data['tanggalakhir'] = $this->input->post('tanggalakhir');

        if ($param1 == 'export') {
            $jenis = 'anggota+luar';
            $tanggalawal = '';
            $tanggalakhir = '';
            $search = '';

            if ($param2 == 'excel') {
                $jenis = $this->input->post('epilih');
                $tanggalawal = $this->input->post('etanggalawal');
                $tanggalakhir = $this->input->post('etanggalakhir');
                $search = $this->input->post('esearch');
            } elseif ($param2 == 'web') {
                $jenis = $this->input->post('wpilih');
                $tanggalawal = $this->input->post('wtanggalawal');
                $tanggalakhir = $this->input->post('wtanggalakhir');
                $search = $this->input->post('wsearch');
            }
            $page_data['laporan'] = 'laporan_belumkembali';
            $page_data['export'] = $param2;
            $page_data['page_title'] = 'Form Peminjaman Belum Kembali';
            $page_data['title'] = 'Daftar ' . $jenis;
            $page_data['data'] = $this->Md_laporan_belumkembali->getLaporan($jenis, $tanggalawal, $tanggalakhir, $search);
            $this->load->view('admin/excel', $page_data);
        } else if ($param1 == 'fetch') {
            $total = $this->Md_laporan_belumkembali->countFiltered();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;

            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');

            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');

            $sort = $this->input->post('datatable[sort][sort]');

            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');

            //mulai fetching data
            $data = array();
            $no = 0;
            $list = $this->Md_laporan_belumkembali->getDatatables();
            foreach ($list as $row) {
                $no++;
                $arr = array();
                if ($this->input->post('datatable[query][jenis]') == 'pegawai') {
                    $arr['number'] = ($perpage * ($page - 1)) + $no;
                    $arr['nomor'] = $row->no_anggota;
                    $arr['nama'] = $row->nama;
                    $arr['prodi'] = '';
                    $arr['noinv'] = $row->no_inv;
                    $arr['no_barcode'] = $row->no_barcode;
                    $arr['judul'] = $row->judul;
                    $arr['tanggal'] = $row->tgl_pinjam;
                    $arr['batas'] = $row->batas;
                } else if ($this->input->post('datatable[query][jenis]') == 'anggota+luar') {
                    $arr['number'] = ($perpage * ($page - 1)) + $no;
                    $arr['nomor'] = $row->no_anggota;
                    $arr['nama'] = $row->nama;
                    $arr['prodi'] = '';
                    $arr['noinv'] = $row->no_inv;
                    $arr['no_barcode'] = $row->no_barcode;
                    $arr['judul'] = $row->judul;
                    $arr['tanggal'] = $row->tgl_pinjam;
                    $arr['batas'] = $row->batas;
                } else {
                    $arr['number'] = ($perpage * ($page - 1)) + $no;
                    $arr['nomor'] = $row->no_anggota;
                    $arr['nama'] = $row->nama;
                    $arr['prodi'] = $row->kelas;
                    $arr['noinv'] = $row->no_inv;
                    $arr['no_barcode'] = $row->no_barcode;
                    $arr['judul'] = $row->judul;
                    $arr['tanggal'] = $row->tgl_pinjam;
                    $arr['batas'] = $row->batas;
                }

                $data[] = $arr;
            }

            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            $page_data['page_access'] = "admin";
            $page_data['page_name'] = 'laporan_belumkembali';
            $page_data['page_now'] = 'dashboard';
            $this->load->view('index', $page_data);
        }
    }

    function laporan_pengunjung($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();

        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['src'] = '';
        $page_data['tanggalawal'] = '';
        $page_data['tanggalakhir'] = '';
        //$page_data['jenis'] = 'mahasiswa';
        // if ($this->input->post('pilih') == 'dosen')
        // 	$page_data['jenis'] = 'dosen';
        // if ($this->input->post('pilih') == 'anggota+luar')
        // 	$page_data['jenis'] = 'anggota+luar';
        $page_data['page_title'] = 'Daftar Pengunjung Tamu Perpustakaan';

        // $page_data['pilihprodi'] = 'All';
        // if ($this->input->post('pilihprodi') == 'All') $page_data['pilihprodi'] = 'All';
        // elseif ($this->input->post('pilihprodi') == 'D3-Gizi') $page_data['pilihprodi'] = 'D3-Gizi';
        // elseif ($this->input->post('pilihprodi') == 'D3-Kebidanan') $page_data['pilihprodi'] = 'D3-Kebidanan';
        // elseif ($this->input->post('pilihprodi') == 'D3-Keperawatan') $page_data['pilihprodi'] = 'D3-Keperawatan';
        // elseif ($this->input->post('pilihprodi') == 'D4-Kebidanan') $page_data['pilihprodi'] = 'D4-Kebidanan';
        // elseif ($this->input->post('pilihprodi') == 'D4-Keperawatan') $page_data['pilihprodi'] = 'D4-Keperawatan';
        // elseif ($this->input->post('pilihprodi') == 'D3-Keperawatan (Kampus Kab. Indragiri Hulu)') $page_data['pilihprodi'] = 'D3-Keperawatan (Kampus Kab. Indragiri Hulu)';
        // similar_text($this->input->post('pilihprodi'), "D4-Kebidanan Alih Jenjang", $percent);
        // if ($percent > 95) $page_data['pilihprodi'] = "D4-Kebidanan Alih Jenjang";

        if ($this->input->post('src'))
            $page_data['src'] = $this->input->post('src');
        if ($this->input->post('tanggalawal'))
            $page_data['tanggalawal'] = $this->input->post('tanggalawal');
        if ($this->input->post('tanggalakhir'))
            $page_data['tanggalakhir'] = $this->input->post('tanggalakhir');

        if ($param1 == 'export') {
            $jenis = 'anggota+luar';
            $tanggalawal = '';
            $tanggalakhir = '';
            $search = '';
            $p_prodi = 'All';

            if ($param2 == 'excel') {
                //$jenis = $this->input->post('epilih');
                $tanggalawal = $this->input->post('etanggalawal');
                $tanggalakhir = $this->input->post('etanggalakhir');
                $search = $this->input->post('esearch');
                //$p_prodi = $this->input->post('eprodi');
            } elseif ($param2 == 'web') {

                $tanggalawal = $this->input->post('wtanggalawal');
                $tanggalakhir = $this->input->post('wtanggalakhir');
                $search = $this->input->post('wsearch');
            }
            $page_data['laporan'] = 'laporan_pengunjung';
            $page_data['export'] = $param2;
            $page_data['page_title'] = 'Daftar Pengunjung Tamu Perpustakaan ';
            $page_data['title'] = 'Daftar Pengunjung Tamu Perpustakaan';
            $page_data['jenis'] = 'Daftar Pengunjung Tamu Perpustakaan';
            $page_data['data'] = $this->Md_pengunjung->getLaporan($tanggalawal, $tanggalakhir, $search);

            $this->load->view('admin/excel', $page_data);
        } else if ($param1 == 'fetch') {
            $total = $this->Md_pengunjung->countFiltered();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;

            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');

            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');

            $sort = $this->input->post('datatable[sort][sort]');

            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');

            //mulai fetching data
            $data = array();
            $no = 0;
            $list = $this->Md_pengunjung->getDatatables();
            // var_dump($list);
            // die;
            foreach ($list as $row) {
                $no++;
                $arr = array();
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['email'] = $row->email;
                $arr['asal_instansi'] = $row->asal_instansi;
                $arr['no_wa'] = $row->no_wa;
                $arr['tujuan_berkunjung'] = $row->tujuan_berkunjung;
                $arr['nama'] = $row->nama;
                $arr['tgl_post'] = $row->tgl_post;
                $data[] = $arr;
            }

            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            $page_data['page_access'] = "admin";
            $page_data['page_name'] = 'laporan_pengunjung';
            $page_data['page_now'] = 'dashboard';
            $page_data['prodi'] = $this->Md_vwprodi->getProdiAll();
            $this->load->view('index', $page_data);
        }
    }

    /** Artikel * */
    public function manage_artikel($param1 = "", $param2 = "", $param3 = "")
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();

        $page_data['artikel'] = '';
        $page_data['media'] = $this->Md_media->getAllMedia();
        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'artikel';
        $page_data['page_title'] = 'Data Artikel';
        $page_data['page_name'] = 'manage_artikel';

        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
        $config['max_size'] = '2048';
        $config['file_name'] = 'artikel_' . date("Ymdhi");

        $this->upload->initialize($config);
        $this->load->library('upload', $config);

        if ($param1 == 'add') {
            if ($param2 == 'do_add') {
                if ($this->input->post('add_media_artikel') == "") {
                    if (!$this->upload->do_upload('file')) {
                        //artikel tidak ada gambar
                        $data['media_id'] = 0;
                    } else if ($id = $this->upload_media('artikel')) {
                        $data['media_id'] = $id['id'];
                    } else {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata('alert', 'alert-danger');
                        $this->session->set_flashdata('flash_message', '<strong>Gagal</strong>' . $error);
                        redirect(site_url('admin/manage_artikel/add'), 'refresh');
                    }
                } else {
                    $data['media_id'] = $this->input->post('add_media_artikel');
                }

                $data['jenisartikel_id'] = 1;
                $data['judul'] = $this->input->post('add_judul_artikel');
                $data['isi'] = $this->input->post('add_isi_artikel');
                $data['post_status'] = $this->input->post('add_statpost_artikel');
                $data['tgl_post'] = $this->input->post('add_tglpost_artikel');
                $data['tgl_perubahan'] = date("Y-m-d");
                $data['meta_desc'] = '';
                $data['meta_keyword'] = '';
                $data['author'] = $this->session->userdata('username');
                $data['status'] = 1;

                $this->Md_artikel->addArtikel($data);
                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Add',
                    'status' => 1,
                    'keterangan' => $this->session->userdata('username') . ' Melakukan Add Artikel ' . $data['judul'],
                    'IP' => $this->input->ip_address()
                );
                $this->Md_log->addLog($log);
                $this->session->set_flashdata('alert', 'alert-success');
                $this->session->set_flashdata('flash_message', '<strong>Berhasil</strong>Kamu berhasil menambah Artikel.');
                redirect(site_url('admin/manage_artikel'), 'refresh');
            } else {
                $page_data['artikel'] = 'add';
            }

            $this->load->view('index', $page_data);
        } else if ($param1 == 'delete') {
            $id = $param2;
            $data['status'] = 2;

            $this->Md_artikel->updateArtikel($id, $data);
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Delete',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Melakukan Delete Artikel ' . $id,
                'IP' => $this->input->ip_address()
            );
            $this->Md_log->addLog($log);
            echo json_encode('success');
            die;
        } else if ($param1 == 'edit') {
            if ($param2 != '') {
                if ($param2 == 'do_edit') {
                    $error = '';
                    if ($this->upload->do_upload('file')) {
                        if ($id = $this->upload_media('artikel')) {
                            $data['media_id'] = $id['id'];
                        } else {
                            $error = $this->upload->display_errors();
                            $this->session->set_flashdata('alert', 'alert-danger');
                            $this->session->set_flashdata('flash_message', '<strong>Gagal</strong>' . $error);
                            redirect(site_url('admin/manage_artikel/edit'), 'refresh');
                        }
                    } else {
                        $data['media_id'] = $this->input->post('edit_media_artikel');
                    }
                    //$data['media_id']			= $this->input->post('edit_media_artikel');
                    $data['jenisartikel_id'] = 1;
                    $data['judul'] = $this->input->post('edit_judul_artikel');
                    $data['isi'] = $this->input->post('edit_isi_artikel');
                    $data['post_status'] = $this->input->post('edit_statpost_artikel');
                    $data['tgl_post'] = $this->input->post('edit_tglpost_artikel');
                    $data['tgl_perubahan'] = date("Y-m-d");
                    $data['meta_desc'] = '';
                    $data['meta_keyword'] = '';
                    //$data['author']				= $this->session->userdata('username');
                    $data['status'] = $this->input->post('edit_status_artikel');
                    if ($this->Md_artikel->updateArtikel($param3, $data) && $error == '') {
                        $log = array(
                            'user_id' => $this->session->userdata('idsys'),
                            'jenis_log' => 'Admin',
                            'jenis_akses' => 'Edit',
                            'status' => 1,
                            'keterangan' => $this->session->userdata('username') . ' Melakukan Edit Artikel ' . $data['judul'],
                            'IP' => $this->input->ip_address()
                        );
                        $this->Md_log->addLog($log);
                        $this->session->set_flashdata('alert', 'alert-success');
                        $this->session->set_flashdata('flash_message', '<strong>Berhasil</strong> Kamu berhasil update Artikel.');
                    } else {
                        $this->session->set_flashdata('alert', 'alert-danger');
                        $this->session->set_flashdata('flash_message', '<strong>Gagal</strong> Kamu gagal update Artikel.' . $error);
                    }
                    redirect(base_url('admin/manage_artikel'), 'refresh');
                } else {
                    $page_data['artikel'] = 'edit';
                    $page_data['edit'] = $this->Md_artikel->getArtikelById($param2);
                }
                $this->load->view('index', $page_data);
            } else {
                redirect(base_url('admin/manage_artikel'), 'refresh');
            }
        } else if ($param1 == 'list') {
            $list = $this->Md_artikel->getAllArtikelByJenis('Berita');
            //$list = $this->Md_artikel->getAllArtikel();
            foreach ($list as $row) {
                $arr = array();
                $arr['id'] = $row->artikel_id;
                $arr['judul'] = $row->judul;
                $arr['tgl_post'] = date('d M Y', strtotime($row->tgl_post));
                if ($row->post_status == 1) {
                    $arr['post_status'] = "Publish";
                } else {
                    $arr['post_status'] = "Arsip";
                }
                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = 1;
            $meta['pages'] = 1;
            $meta['perpage'] = 1;
            $meta['total'] = count($list);
            $meta['sort'] = 'asc';
            $meta['field'] = 'nama_dosen';
            $output = array(
                "meta" => $meta,
                "data" => $data
            );

            echo json_encode($output);
            exit();
        } else {
            $this->load->view('index', $page_data);
        }
    }

    public function manage_pengumuman($param1 = "", $param2 = "", $param3 = "")
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();

        $page_data['pengumuman'] = '';
        $page_data['media'] = $this->Md_media->getAllMedia();
        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'pengumuman';
        $page_data['page_title'] = 'Data Pengumuman';
        $page_data['page_name'] = 'manage_pengumuman';

        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
        $config['max_size'] = '2048';
        $config['file_name'] = 'pengumuman_' . date("Ymdhi");

        $this->upload->initialize($config);
        $this->load->library('upload', $config);

        if ($param1 == 'add') {
            if ($param2 == 'do_add') {
                if ($this->input->post('add_media_artikel') == "") {
                    if (!$this->upload->do_upload('file')) {
                        //artikel tidak ada gambar
                        $data['media_id'] = 0;
                    } else if ($id = $this->upload_media('artikel')) {
                        $data['media_id'] = $id['id'];
                    } else {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata('alert', 'alert-danger');
                        $this->session->set_flashdata('flash_message', '<strong>Gagal</strong>' . $error);
                        redirect(site_url('admin/manage_artikel/add'), 'refresh');
                    }
                } else {
                    $data['media_id'] = $this->input->post('add_media_artikel');
                }

                $data['jenisartikel_id'] = 2;
                $data['judul'] = $this->input->post('add_judul_artikel');
                $data['isi'] = $this->input->post('add_isi_artikel');
                $data['post_status'] = $this->input->post('add_statpost_artikel');
                $data['tgl_post'] = $this->input->post('add_tglpost_artikel');
                $data['tgl_perubahan'] = date("Y-m-d");
                $data['meta_desc'] = '';
                $data['meta_keyword'] = '';
                $data['author'] = $this->session->userdata('username');
                $data['status'] = 1;

                $this->Md_artikel->addArtikel($data);
                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Add',
                    'status' => 1,
                    'keterangan' => $this->session->userdata('username') . ' Melakukan Add Pengumuman ' . $data['judul'],
                    'IP' => $this->input->ip_address()
                );
                $this->Md_log->addLog($log);
                $this->session->set_flashdata('alert', 'alert-success');
                $this->session->set_flashdata('flash_message', '<strong>Berhasil</strong>Kamu berhasil menambah Pengumuman.');
                redirect(site_url('admin/manage_pengumuman'), 'refresh');
            } else {

                $page_data['pengumuman'] = 'add';
            }

            $this->load->view('index', $page_data);
        } else if ($param1 == 'delete') {
            $id = $param2;
            $data['status'] = 2;

            $this->Md_artikel->updateArtikel($id, $data);
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Delete',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Melakukan Delete Pengumuman ' . $id,
                'IP' => $this->input->ip_address()
            );
            $this->Md_log->addLog($log);
            echo json_encode('success');
            die;
        } else if ($param1 == 'edit') {

            if ($param2 != '') {
                if ($param2 == 'do_edit') {
                    $error = '';
                    if ($this->upload->do_upload('file')) {
                        if ($id = $this->upload_media('artikel')) {
                            $data['media_id'] = $id['id'];
                        } else {
                            $error = $this->upload->display_errors();
                            $this->session->set_flashdata('alert', 'alert-danger');
                            $this->session->set_flashdata('flash_message', '<strong>Gagal</strong>' . $error);
                            redirect(site_url('admin/manage_pengumuman/edit'), 'refresh');
                        }
                    } else {
                        $data['media_id'] = $this->input->post('edit_media_artikel');
                    }
                    //$data['media_id']			= $this->input->post('edit_media_artikel');
                    $data['jenisartikel_id'] = 2;
                    $data['judul'] = $this->input->post('edit_judul_artikel');
                    $data['isi'] = $this->input->post('edit_isi_artikel');
                    $data['post_status'] = $this->input->post('edit_statpost_artikel');
                    $data['tgl_post'] = $this->input->post('edit_tglpost_artikel');
                    $data['tgl_perubahan'] = date("Y-m-d");
                    $data['meta_desc'] = '';
                    $data['meta_keyword'] = '';
                    //$data['author']				= $this->session->userdata('username');
                    $data['status'] = $this->input->post('edit_status_artikel');
                    if ($this->Md_artikel->updateArtikel($param3, $data) && $error == '') {
                        $log = array(
                            'user_id' => $this->session->userdata('idsys'),
                            'jenis_log' => 'Admin',
                            'jenis_akses' => 'Edit',
                            'status' => 1,
                            'keterangan' => $this->session->userdata('username') . ' Melakukan Edit Pengumuman ' . $data['judul'],
                            'IP' => $this->input->ip_address()
                        );
                        $this->Md_log->addLog($log);
                        $this->session->set_flashdata('alert', 'alert-success');
                        $this->session->set_flashdata('flash_message', '<strong>Berhasil</strong> Kamu berhasil update Pengumuman.');
                    } else {
                        $this->session->set_flashdata('alert', 'alert-danger');
                        $this->session->set_flashdata('flash_message', '<strong>Gagal</strong> Kamu gagal update Pengumuman.' . $error);
                    }
                    redirect(base_url('admin/manage_pengumuman'), 'refresh');
                } else {
                    $page_data['pengumuman'] = 'edit';
                    $page_data['edit'] = $this->Md_artikel->getArtikelById($param2);
                }
                $this->load->view('index', $page_data);
            } else {
                redirect(base_url('admin/manage_pengumuman'), 'refresh');
            }
        } else if ($param1 == 'list') {
            $list = $this->Md_artikel->getAllArtikelByJenis('Pengumuman');
            foreach ($list as $row) {
                $arr = array();
                $arr['id'] = $row->artikel_id;
                $arr['judul'] = $row->judul;
                $arr['tgl_post'] = date('d M Y', strtotime($row->tgl_post));
                if ($row->post_status == 1) {
                    $arr['post_status'] = "Publish";
                } else {
                    $arr['post_status'] = "Arsip";
                }
                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = 1;
            $meta['pages'] = 1;
            $meta['perpage'] = 1;
            $meta['total'] = count($list);
            $meta['sort'] = 'asc';
            $meta['field'] = 'nama_dosen';
            $output = array(
                "meta" => $meta,
                "data" => $data
            );

            echo json_encode($output);
            exit();
        } else {
            $this->load->view('index', $page_data);
        }
    }

    function preview($param1 = "", $param2 = "", $param3 = "")
    {
        if ($param1 == 'previewedit_artikel') {

            $this->load->helper('menu_generator');
            $page_data['page_access'] = "home";
            $page_data['page_name'] = 'readartikel';
            $page_data['page_content'] = 'readartikel';
            $page_data['page_action'] = $param1;
            $page_data['media'] = '';
            $page_data['artikel'] = $this->Md_artikel->getNewArtikel();
            $page_data['mediasosial'] = $this->Md_mediasosial->getAllMediasosial();
            $page_data['is_preview'] = 'Ya';
            $page_data['jns_artikel'] = $param2;
            if (isset($_FILES['file']) && $_FILES['file']['size'] > 0) {

                //artikel tidak ada gambar
                $file_data = file_get_contents($_FILES['file']['tmp_name']);
                $file_name = $_FILES['file']['name'];

                // Dapatkan informasi ekstensi file
                $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

                // Konversi file menjadi base64
                $file_base64 = base64_encode($file_data);

                // Kirim data base64 ke view
                $page_data['file_base64'] = $file_base64;
                $page_data['file_extension'] = $file_extension;

                // Kirim data base64 ke view


                $page_data['media_id'] = 0;
            } else {
                if ($this->input->post('edit_media_artikel') != "") {
                    $getDataById = $this->Md_media->getMediaByMediaid($this->input->post('edit_media_artikel'));
                    if (!$getDataById) {
                        echo 'file tidak ditemukan';
                        die;
                    }
                    $file_path = base_url() . 'uploads/big/big_' . $getDataById->judul;
                    $file_path_serach = FCPATH . 'uploads/big/big_' . $getDataById->judul;

                    // Memeriksa apakah file ada
                    if (file_exists($file_path_serach)) {
                        // File ada
                    } else {
                        // File tidak ada
                        echo "File Media tidak ditemukan!";
                        die;
                    }
                    // Mendapatkan ekstensi file
                    $file_extension = pathinfo($file_path, PATHINFO_EXTENSION);

                    // Membaca isi file
                    $file_data = file_get_contents($file_path);

                    // Mengonversi file menjadi base64
                    $file_base64 = base64_encode($file_data);

                    // Kirim data base64 dan ekstensi file ke view
                    $page_data['file_base64'] = $file_base64;
                    $page_data['file_extension'] = $file_extension;
                } else {
                    return 'file tidak ada, harap upload file media terlebih dahulu!';
                    die;
                }
            }


            $judul = $this->input->post('edit_judul_artikel');
            $isi = $this->input->post('edit_isi_artikel');
            $tglpost = $this->input->post('edit_tglpost_artikel');
            $statpost = $this->input->post('edit_statpost_artikel');
            $sts_artikel = $this->input->post('edit_status_artikel');

            $detailartikel = new stdClass();

            $detailartikel->tgl_post = $tglpost;
            $detailartikel->judul = $judul;
            $detailartikel->isi = $isi;

            $dataarray = array();
            array_push($dataarray, $detailartikel);

            $page_data['detail'] = $dataarray;

            $this->load->view('front', $page_data);
        } else if ($param1 == 'previewadd_artikel') {
            $this->load->helper('menu_generator');
            $page_data['page_access'] = "home";
            $page_data['page_name'] = 'readartikel';
            $page_data['page_content'] = 'readartikel';
            $page_data['page_action'] = $param1;
            $page_data['media'] = '';
            $page_data['artikel'] = $this->Md_artikel->getNewArtikel();
            $page_data['mediasosial'] = $this->Md_mediasosial->getAllMediasosial();
            $page_data['is_preview'] = 'Ya';
            $page_data['jns_artikel'] = $param2;
            if (isset($_FILES['file'])) {

                //artikel tidak ada gambar
                $file_data = file_get_contents($_FILES['file']['tmp_name']);
                $file_name = $_FILES['file']['name'];

                // Dapatkan informasi ekstensi file
                $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

                // Konversi file menjadi base64
                $file_base64 = base64_encode($file_data);

                // Kirim data base64 ke view
                $page_data['file_base64'] = $file_base64;
                $page_data['file_extension'] = $file_extension;

                // Kirim data base64 ke view


                $page_data['media_id'] = 0;
            } else {
                if ($this->input->post('add_media_artikel') != "") {
                    $getDataById = $this->Md_media->getMediaByMediaid($this->input->post('add_media_artikel'));
                    if (!$getDataById) {
                        echo "Data Media tidak ditemukan!";
                        die;
                    }
                    $file_path = base_url() . 'uploads/big/big_' . $getDataById->judul;

                    $file_path_serach = FCPATH . 'uploads/big/big_' . $getDataById->judul;

                    // Memeriksa apakah file ada
                    if (file_exists($file_path_serach)) {
                        // File ada
                    } else {
                        // File tidak ada
                        echo "File Media tidak ditemukan!";
                        die;
                    }

                    // Mendapatkan ekstensi file
                    $file_extension = pathinfo($file_path, PATHINFO_EXTENSION);

                    // Membaca isi file
                    $file_data = file_get_contents($file_path);

                    // Mengonversi file menjadi base64
                    $file_base64 = base64_encode($file_data);

                    // Kirim data base64 dan ekstensi file ke view
                    $page_data['file_base64'] = $file_base64;
                    $page_data['file_extension'] = $file_extension;
                } else {
                    return 'file tidak ada, harap upload file media terlebih dahulu!';
                    die;
                }
            }


            $judul = $this->input->post('add_judul_artikel');
            $isi = $this->input->post('add_isi_artikel');
            $tglpost = $this->input->post('add_tglpost_artikel');
            $statpost = $this->input->post('add_statpost_artikel');
            $sts_artikel = $this->input->post('add_status_artikel');

            $detailartikel = new stdClass();

            $detailartikel->tgl_post = $tglpost;
            $detailartikel->judul = $judul;
            $detailartikel->isi = $isi;

            $dataarray = array();
            array_push($dataarray, $detailartikel);

            $page_data['detail'] = $dataarray;

            $this->load->view('front', $page_data);
        }
    }

    public function manage_menu($param1 = "", $param2 = "", $param3 = "")
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();

        $this->load->model('Md_menu');
        $this->load->helper('menu_generator');

        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'menu';
        $page_data['page_title'] = 'Manage Menu';
        $page_data['page_name'] = 'manage_menu';

        if ($param1 == 'add') {
            $page_data['page_now'] = 'menu';
            $page_data['page_title'] = 'Tambah Menu';
            $page_data['page_name'] = 'manage_menu_add';
            $page_data['menus'] = $this->Md_menu->getAllMenu();

            $active_menus = $this->Md_menu->getAllActiveParentMenu();
            if (count($active_menus) > 10) {
                $page_data['_pesan'] = 'Jumlah menu aktif sudah mencapai batas wajar, disarankan untuk tidak melebihi 10 menu aktif';
            }

            $this->load->view('index', $page_data);
        } else if ($param1 == 'save') {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('nama_menu', 'Nama menu', 'required|max_length[200]');
            $this->form_validation->set_rules('level', 'Level', 'required');
            $this->form_validation->set_rules('link', 'Link', 'required');

            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('alert', ['type' => 'danger', 'msg' => validation_errors()]);
                redirect(base_url('admin/manage_menu/add'));
            }

            if ($this->input->post('level') != 1 && $this->input->post('menuparent_id') == '') {
                $this->session->set_flashdata('alert', ['type' => 'danger', 'msg' => 'Parent menu wajib diisi jika bukan level 1']);
                redirect(base_url('admin/manage_menu/add'));
            }

            $this->db->trans_start();

            $this->Md_menu->addData([
                'nama_menu' => $this->input->post('nama_menu'),
                'level' => $this->input->post('level'),
                'link' => $this->input->post('link'),
                'menuparent_id' => $this->input->post('menuparent_id') == '' ? null : $this->input->post('menuparent_id'),
                'urutan' => $this->input->post('urutan'),
                'is_new_tab' => $this->input->post('is_new_tab') == '' ? 0 : 1,
                'author' => $this->session->userdata('username'),
                'tgl_post' => date('Y-m-d H:i:s'),
                'status' => 1,
                'is_active' => 1,
            ]);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('alert', ['type' => 'danger', 'msg' => 'Tidak dapat menyimpan data']);
                redirect(base_url('admin/manage_menu/add'));
            }

            $this->session->set_flashdata('alert', ['type' => 'success', 'msg' => 'Berhasil menambahkan menu']);
            redirect(base_url('admin/manage_menu'));
        } else if ($param1 == 'edit') {
            $page_data['page_now'] = 'menu';
            $page_data['page_title'] = 'Ubah Menu';
            $page_data['page_name'] = 'manage_menu_edit';

            $page_data['menus'] = $this->Md_menu->getAllMenu();
            $page_data['menu'] = $this->Md_menu->getMenuById($param2);

            $this->load->view('index', $page_data);
        } else if ($param1 == 'update') {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('menu_id', 'ID', 'required');
            $this->form_validation->set_rules('nama_menu', 'Nama menu', 'required|max_length[200]');
            $this->form_validation->set_rules('level', 'Level', 'required');
            $this->form_validation->set_rules('link', 'Link', 'required');

            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('alert', ['type' => 'danger', 'msg' => validation_errors()]);
                redirect(base_url('admin/manage_menu'));
            }

            $menu_id = $this->input->post('menu_id');

            if ($this->input->post('level') != 1 && $this->input->post('menuparent_id') == '') {
                $this->session->set_flashdata('alert', ['type' => 'danger', 'msg' => 'Parent menu wajib diisi jika bukan level 1']);
                redirect(base_url('admin/manage_menu/edit/' . $menu_id));
            }

            $this->db->trans_start();

            $this->Md_menu->updateData($menu_id, [
                'nama_menu' => $this->input->post('nama_menu'),
                'level' => $this->input->post('level'),
                'link' => $this->input->post('link'),
                'menuparent_id' => $this->input->post('menuparent_id') == '' ? null : $this->input->post('menuparent_id'),
                'urutan' => $this->input->post('urutan'),
                'is_new_tab' => $this->input->post('is_new_tab') == '' ? 0 : 1,
            ]);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('alert', ['type' => 'danger', 'msg' => 'Tidak dapat menyimpan data']);
                redirect(base_url('admin/manage_menu/edit/' . $menu_id));
            }

            $this->session->set_flashdata('alert', ['type' => 'success', 'msg' => 'Berhasil mengubah menu']);
            redirect(base_url('admin/manage_menu'));
        } else if ($param1 == 'list') {
            $list = $this->Md_menu->getAllMenu();

            $data = [];
            foreach ($list as $key => $value) {
                $data[] = [
                    'no' => ++$key,
                    'menu_id' => $value->menu_id,
                    'nama_menu' => strtoupper($value->nama_menu),
                    'level' => $value->level,
                    'urutan' => $value->urutan,
                    'link' => $value->link,
                    'is_new_tab' => $value->is_new_tab,
                    'is_active' => $value->is_active,
                    'author' => $value->author,
                    'tgl_post' => date('d M Y', strtotime($value->tgl_post)),
                ];
            }

            $meta = array();
            $meta['page'] = 1;
            $meta['pages'] = 1;
            $meta['perpage'] = 10;
            $meta['total'] = count($list);
            $meta['sort'] = 'asc';
            $meta['field'] = 'menu_id';

            $output = array(
                "meta" => $meta,
                "data" => $data
            );

            die(json_encode($output));
        } else if ($param1 == 'update_urutan') {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() === FALSE) {
                die(json_encode([
                    'success' => false,
                    'message' => 'ID is not valid'
                ]));
            }

            $this->db->trans_start();

            $this->Md_menu->updateData($this->input->post('id'), [
                'urutan' => $this->input->post('urutan')
            ]);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                die(json_encode([
                    'success' => false,
                    'message' => 'Error'
                ]));
            }

            die(json_encode([
                'success' => true,
                'message' => 'Success'
            ]));
        } else if ($param1 == 'update_status_aktif') {


            $this->load->library('form_validation');

            $this->form_validation->set_rules('id', 'ID', 'required');

            if ($this->form_validation->run() === FALSE) {
                die(json_encode([
                    'success' => false,
                    'message' => 'ID is not valid'
                ]));
            }

            $this->db->trans_start();

            $this->Md_menu->updateData($this->input->post('id'), [
                'is_active' => $this->input->post('is_active')
            ]);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                die(json_encode([
                    'success' => false,
                    'message' => 'Error'
                ]));
            }

            die(json_encode([
                'success' => true,
                'message' => 'Success'
            ]));
        } else if ($param1 == 'delete') {
            $id = $this->input->post('id');

            $this->Md_menu->updateData($id, ['status' => 2]);
            $this->Md_log->addLog([
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Delete',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Melakukan Delete Menu ' . $id,
                'IP' => $this->input->ip_address()
            ]);

            die(json_encode([
                'success' => true,
                'message' => 'Deleted'
            ]));
        } else {
            $this->load->view('index', $page_data);
        }
    }

    function manage_media($param1 = "", $param2 = "")
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();

        if ($param1 == 'add') {

            if ($upload = $this->upload_file()) {
                $jenis = $upload['upload']['file_type'];
                $path = $upload['upload']['full_path'];
                $file = $upload['upload']['file_name'];
                $height = $upload['upload']['image_height'];
                $width = $upload['upload']['image_width'];

                if ($jenis == 'image/jpeg' || $jenis == 'image/png' || $jenis == 'image/gif') {
                    $tipe_data = 'gambar';
                } else {
                    $tipe_data = 'file';
                }

                $data['jenismedia_id'] = 1; //$this->input->post('add_jenisid_media');
                $data['judul'] = $upload['upload']['file_name'];
                $data['deskripsi'] = '';
                $data['alt_teks'] = $upload['upload']['file_name'];
                $data['tipe'] = $tipe_data;
                $data['tgl_upload'] = date("Y-m-d");
                $data['tgl_perubahan'] = date("Y-m-d");
                $data['author'] = 'author';
                $data['status'] = 1;

                $media = $this->Md_media->addMedia($data);
                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Add',
                    'status' => 1,
                    'keterangan' => $this->session->userdata('username') . ' Melakukan Add Media ' . $data['judul'],
                    'IP' => $this->input->ip_address()
                );
                $this->Md_log->addLog($log);

                if ($media and $tipe_data == "gambar") {
                    $id = $media['media_id'];
                    $this->rs_big($path, $file, $id, 1920, 1280);
                    $this->rs_medium($path, $file, $id, 850, 430);
                    $this->rs_small($path, $file, $id, 370, 247);
                }
            } else {
                return;
            }
        }

        if ($param1 == 'delete') {
            $id = $param2;
            $media = $this->Md_media->getMediaById($id);
            if (count($media) > 0) {
                $this->Md_media->hapusMedia($id);
                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Delete',
                    'status' => 1,
                    'keterangan' => $this->session->userdata('username') . ' Melakukan Delete Media ' . $id,
                    'IP' => $this->input->ip_address()
                );
                $this->Md_log->addLog($log);
                @unlink(base_url() . 'uploads/' . $media[0]['judul']);
                @unlink(base_url() . 'uploads/big/big_' . $media[0]['judul']);
                @unlink(base_url() . 'uploads/medium/medium_' . $media[0]['judul']);
                @unlink(base_url() . 'uploads/small/small_' . $media[0]['judul']);
            } else {
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Gagal Menghapus Media');
                redirect(base_url() . 'admin/manage_media', 'refresh');
            }

            $this->session->set_flashdata('alert', 'alert-success');
            $this->session->set_flashdata('flash_message', 'Sukses Menghapus Media');
            redirect(base_url() . 'admin/manage_media', 'refresh');
        }

        if ($param1 == 'edit') {
            # code...
        }

        // Pagination Start
        // s
        $config["base_url"] = base_url() . "admin/manage_media";
        $config["total_rows"] = count($this->Md_media->getAllMedia());
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $config['use_page_numbers'] = TRUE;

        //style pagination
        $config['full_tag_open'] = '<ul class="pagination xs-pull-center m-0">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = 'First Page';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Last Page';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = 'Next Page';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = 'Prev Page';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="numlink">';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if ($page == 0) {
            $page = $page * $config["per_page"];
        } else {
            $page = ($page - 1) * $config["per_page"];
        }

        $page_data["media"] = $this->Md_media->getMediaPagination($config["per_page"], $page);
        $page_data["links"] = $this->pagination->create_links();
        // Pagination Finish

        $page_data['data'] = $this->Md_media->getAllMedia();

        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'media';
        $page_data['page_title'] = 'Media';
        $page_data['page_name'] = 'manage_media';
        $this->load->view('index', $page_data);
    }

    public function details($param1 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();

        $page_data['media'] = $this->Md_media->getMediaByID($param1);
        $page_data['detail'] = $this->Md_mediadetail->getMdetailByMediaId($param1);

        $page_data['page_title'] = 'Details';
        $page_data['page_name'] = 'manage_media';
        $this->load->view('admin/detail_media', $page_data);
    }

    private function rs_big($path, $file, $id, $width, $height)
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $rs_width = $width;
        $rs_height = $height;

        $config['image_library'] = 'gd2';
        $config['source_image'] = $path;
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = $rs_width;
        $config['height'] = $rs_height;
        $config['new_image'] = './uploads/big/big_' . $file;

        $this->image_lib->initialize($config);
        $this->load->library('image_lib', $config);
        if (!$this->image_lib->resize()) {
            showhouse_log(MY_Log::KERROR, $ci->image_lib->display_errors());
        }

        $info = getimagesize('./uploads/big/big_' . $file);
        $size = filesize('./uploads/big/big_' . $file) / 1024;
        $detail['media_id'] = $id;
        $detail['jenis_ukuran'] = "big";
        $detail['media_link'] = base_url() . 'uploads/big/big_' . $file;
        $detail['width'] = $info[0];
        $detail['height'] = $info[1];
        $detail['size'] = $size;
        $detail['status'] = 1;

        $this->Md_mediadetail->addMdetail($detail);
    }

    private function rs_medium($path, $file, $id, $width, $height)
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $rs_width = $width;
        $rs_height = $height;

        $config['image_library'] = 'gd2';
        $config['source_image'] = $path;
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = $rs_width;
        $config['height'] = $rs_height;
        $config['new_image'] = './uploads/medium/medium_' . $file;

        $this->image_lib->initialize($config);
        $this->load->library('image_lib', $config);
        if (!$this->image_lib->resize()) {
            showhouse_log(MY_Log::KERROR, $ci->image_lib->display_errors());
        }

        $info = getimagesize('./uploads/medium/medium_' . $file);
        $size = filesize('./uploads/medium/medium_' . $file) / 1024;
        $detail['media_id'] = $id;
        $detail['jenis_ukuran'] = "medium";
        $detail['media_link'] = base_url() . 'uploads/medium/medium_' . $file;
        $detail['width'] = $info[0];
        $detail['height'] = $info[1];
        $detail['size'] = $size;
        $detail['status'] = 1;

        $this->Md_mediadetail->addMdetail($detail);
    }

    private function rs_small($path, $file, $id, $width, $height)
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $rs_width = $width;
        $rs_height = $height;

        $config['image_library'] = 'gd2';
        $config['source_image'] = $path;
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = $rs_width;
        $config['height'] = $rs_height;
        $config['new_image'] = './uploads/small/small_' . $file;

        $this->image_lib->initialize($config);
        $this->load->library('image_lib', $config);
        if (!$this->image_lib->resize()) {
            showhouse_log(MY_Log::KERROR, $ci->image_lib->display_errors());
        }

        $info = getimagesize('./uploads/small/small_' . $file);
        $size = filesize('./uploads/small/small_' . $file) / 1024;
        $detail['media_id'] = $id;
        $detail['jenis_ukuran'] = "small";
        $detail['media_link'] = base_url() . 'uploads/small/small_' . $file;
        $detail['width'] = $info[0];
        $detail['height'] = $info[1];
        $detail['size'] = $size;
        $detail['status'] = 1;

        $this->Md_mediadetail->addMdetail($detail);
    }

    private function upload_file()
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG|doc|docx|xls|xlsx|pdf';
        $config['max_size'] = '2048';
        // $config['max_width']		= '5000';
        // $config['max_height']		= '5000';
        $config['file_name'] = 'media_' . date("Ymdhi");

        $this->upload->initialize($config);
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file')) {
            $error = $this->upload->display_errors();
            echo $error;
            header("Error:", true, 500);
        } else {
            $upload_data = $this->upload->data();
            return $data = array('upload' => $upload_data, TRUE);
        }
    }

    public function upload_media($tipe)
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();

        if (!$this->upload->do_upload('file')) {

            $error = $this->upload->display_errors();
            $this->session->set_flashdata('alert', 'alert-danger');
            $this->session->set_flashdata('flash_message', '<strong>Gagal</strong>' . $error);
        } else {
            $upload = $this->upload->data();
            $jenis = $upload['file_type'];
            $path = $upload['full_path'];
            $file = $upload['file_name'];
            $height = $upload['image_height'];
            $width = $upload['image_width'];

            $data['jenismedia_id'] = 1; //$this->input->post('add_jenisid_media');
            $data['judul'] = $upload['file_name'];
            $data['deskripsi'] = "Gambar " . $tipe;
            $data['alt_teks'] = $upload['file_name'];
            $data['tipe'] = 'gambar';
            $data['tgl_upload'] = date("Y-m-d");
            $data['tgl_perubahan'] = date("Y-m-d");
            $data['author'] = $this->session->userdata('username');
            $data['status'] = 1;

            $id = $this->Md_media->addMedia($data);
            if ($id) {

                if ($tipe == 'artikel') {
                    $this->rs_big($path, $file, $id['media_id'], 850, 430);
                    $this->rs_medium($path, $file, $id['media_id'], 370, 247);
                    $this->rs_small($path, $file, $id['media_id'], 150, 150);
                } else if ($tipe == 'logo') {
                    //do nothing
                } else {
                    $this->rs_big($path, $file, $id['media_id'], 1920, 1280);
                    $this->rs_medium($path, $file, $id['media_id'], 850, 430);
                    $this->rs_small($path, $file, $id['media_id'], 370, 247);
                }
            }
            return $arrayName = array('id' => $id['media_id'], TRUE);
        }
    }

    public function manage_slide($param1 = "", $param2 = "", $param3 = "")
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $page_data['slide'] = '';
        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'slide';
        $page_data['page_title'] = 'Data Slide';
        $page_data['page_name'] = 'manage_slide';
        $page_data['media'] = $this->Md_media->getAllMedia();

        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
        $config['max_size'] = '2048';
        $config['file_name'] = 'slide_' . date("Ymdhi");

        $this->upload->initialize($config);
        $this->load->library('upload', $config);

        if ($param1 == 'add') {
            if ($param2 == 'do_add') {
                if ($this->input->post('add_media_slide') == "") {
                    if ($id = $this->upload_media('slide')) {
                        $data['media_id'] = $id['id'];
                    } else {
                        $error = $this->upload->display_errors();
                        $this->session->set_flashdata('alert', 'alert-danger');
                        $this->session->set_flashdata('flash_message', '<strong>Gagal</strong>' . $error);
                        redirect(site_url('admin/manage_slide/add'), 'refresh');
                    }
                } else {
                    $data['media_id'] = $this->input->post('add_media_slide');
                }

                $data['judul'] = $this->input->post('add_judul_slide');
                $data['keterangan'] = $this->input->post('add_keterangan_slide');
                $data['urutan'] = $this->input->post('add_urutan_slide');
                $data['tgl_post'] = date("Y-m-d");
                $data['author'] = $this->session->userdata('username');
                $data['status'] = 1;
                $this->Md_slide->addSlide($data);
                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Add',
                    'status' => 1,
                    'keterangan' => $this->session->userdata('username') . ' Melakukan Add Slide ' . $data['judul'],
                    'IP' => $this->input->ip_address()
                );
                $this->Md_log->addLog($log);
                $this->session->set_flashdata('alert', 'alert-success');
                $this->session->set_flashdata('flash_message', '<strong>Berhasil</strong>Kamu berhasil menambah Slide');
                redirect(site_url('admin/manage_slide'), 'refresh');
            } else {
                $page_data['slide'] = 'add';
            }

            $this->load->view('index', $page_data);
        } else if ($param1 == 'delete') {
            $id = $param2;
            $data['status'] = 2;

            $this->Md_slide->updateSlide($id, $data);
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Delete',
                'status' => 2,
                'keterangan' => $this->session->userdata('username') . ' Melakukan Delete Slide ' . $data['judul'],
                'IP' => $this->input->ip_address()
            );
            $this->Md_log->addLog($log);

            echo json_encode('success');
            die;
        } else if ($param1 == 'edit') {
            if ($param2 != '') {
                if ($param2 == 'do_edit') {
                    $error = '';
                    if ($this->upload->do_upload('file')) {
                        if ($id = $this->upload_media('slide')) {
                            $data['media_id'] = $id['id'];
                        } else {
                            $error = $this->upload->display_errors();
                            $this->session->set_flashdata('alert', 'alert-danger');
                            $this->session->set_flashdata('flash_message', '<strong>Gagal</strong>' . $error);
                            redirect(site_url('admin/manage_slide/edit'), 'refresh');
                        }
                    } else {
                        $data['media_id'] = $this->input->post('edit_media_slide');
                    }
                    $data['judul'] = $this->input->post('edit_judul_slide');
                    $data['keterangan'] = $this->input->post('edit_keterangan_slide');
                    $data['urutan'] = $this->input->post('edit_urutan_slide');
                    $data['tgl_post'] = date("Y-m-d");
                    $data['author'] = $this->session->userdata('username');
                    $data['status'] = 1;

                    if ($this->Md_slide->updateSlide($param3, $data) == TRUE) {
                        $log = array(
                            'user_id' => $this->session->userdata('idsys'),
                            'jenis_log' => 'Admin',
                            'jenis_akses' => 'Edit',
                            'status' => 1,
                            'keterangan' => $this->session->userdata('username') . ' Melakukan Edit Slide ' . $data['judul'],
                            'IP' => $this->input->ip_address()
                        );
                        $this->Md_log->addLog($log);
                        $this->session->set_flashdata('alert', 'alert-success');
                        $this->session->set_flashdata('flash_message', '<strong>Berhasil</strong>Berhasil Update Slide');
                    } else {
                        $this->session->set_flashdata('alert', 'alert-danger');
                        $this->session->set_flashdata('flash_message', '<strong>Gagal</strong>Gagal Update Slide' . $error);
                    }
                    redirect(base_url('admin/manage_slide'), 'refresh');
                } else {
                    $page_data['slide'] = 'edit';
                    $page_data['edit'] = $this->Md_slide->getSlideById($param2);
                }
                $this->load->view('index', $page_data);
            } else {
                redirect(base_url('admin/manage_slide'), 'refresh');
            }
        } else if ($param1 == 'list') {
            $list = $this->Md_slide->getAllSlide();
            foreach ($list as $row) {
                $arr = array();
                $arr['id'] = $row->slide_id;
                $arr['slide'] = "<img src='" . base_url() . "uploads/small/small_" . $row->file_slide . "' alt='' />";
                $arr['judul'] = $row->judul;
                $arr['keterangan'] = $row->keterangan;
                $arr['status'] = $row->status;
                $arr['author'] = $row->author;
                $arr['tgl_post'] = date('d M Y', strtotime($row->tgl_post));
                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = 1;
            $meta['pages'] = 1;
            $meta['perpage'] = 1;
            $meta['total'] = count($list);
            $meta['sort'] = 'asc';
            $meta['field'] = 'nama_dosen';
            $output = array(
                "meta" => $meta,
                "data" => $data
            );

            echo json_encode($output);
            exit();
        } else {
            $this->load->view('index', $page_data);
        }
    }

    function manage_halaman($param1 = "", $param2 = "", $param3 = "")
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();

        $page_data['hal'] = '';
        $page_data['halaman'] = $this->Md_siperpus_manage_halaman->getAllHalaman();
        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'halaman';

        if ($param1 == '') {
            $page_data['page_title'] = 'Manage Halaman';
            $page_data['page_name'] = 'manage_halaman';

            $this->load->view('index', $page_data);
        } else if ($param1 == 'edit') {

            $page_data['edit_halaman'] = 'edit';
            $page_data['edit'] = $this->Md_siperpus_manage_halaman->getHalamanById($param2);

            $page_data['page_title'] = 'Ubah Halaman';
            $page_data['page_name'] = 'manage_halaman_edit';

            $this->load->view('index', $page_data);
        } else if ($param1 == 'add') {
            $page_data['page_title'] = 'Tambah Halaman';
            $page_data['page_name'] = 'manage_halaman_add';

            $this->load->view('index', $page_data);
        } else if ($param1 == 'update') {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('halaman_id', 'ID', 'required');
            $this->form_validation->set_rules('judul_halaman', 'Judul', 'required|max_length[255]');
            $this->form_validation->set_rules('link_halaman', 'Link', 'required');
            $this->form_validation->set_rules('isi_halaman', 'Isi', 'required');

            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', validation_errors());
                redirect(base_url('admin/manage_halaman/edit'));
            }

            $halaman_id = $this->input->post('halaman_id');

            $slug = preg_replace('/[^a-z0-9]+/i', '-', trim(strtolower($this->input->post('link_halaman'))));

            $is_slug_exist = $this->Md_siperpus_manage_halaman->getHalamanByLink("lib.pkr.ac.id/halaman/$slug", $halaman_id);

            if (!empty($is_slug_exist)) {
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Link halaman sudah ada.');
                redirect(base_url('admin/manage_halaman/edit'));
            }

            $this->db->trans_start();

            $this->Md_siperpus_manage_halaman->updateHalaman($halaman_id, [
                'judul_halaman' => $this->input->post('judul_halaman'),
                'link_halaman' => 'lib.pkr.ac.id/halaman/' . $slug,
                'isi_halaman' => $this->input->post('isi_halaman'),
                'meta_keyword' => $this->input->post('meta_keyword'),
                'meta_desc' => $this->input->post('meta_desc'),
            ]);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Gagal edit halaman.');
                redirect(base_url('admin/manage_halaman/edit'));
            }

            $this->Md_log->addLog([
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'edit',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Melakukan edit Halaman',
                'IP' => $this->input->ip_address()
            ]);

            $this->session->set_flashdata('alert', 'alert-success');
            $this->session->set_flashdata('flash_message', 'Berhasil diubah.');

            redirect(base_url('admin/manage_halaman'));
        } else if ($param1 == 'save') {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('judul_halaman', 'Judul', 'required|max_length[255]');
            $this->form_validation->set_rules('link_halaman', 'Link', 'required');
            $this->form_validation->set_rules('isi_halaman', 'Isi', 'required');

            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', validation_errors());
                redirect(base_url('admin/manage_halaman/add'));
            }

            $slug = preg_replace('/[^a-z0-9]+/i', '-', trim(strtolower($this->input->post('link_halaman'))));

            $is_slug_exist = $this->Md_siperpus_manage_halaman->getHalamanByLink("lib.pkr.ac.id/halaman/$slug");

            if (!empty($is_slug_exist)) {
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Link halaman sudah ada.');
                redirect(base_url('admin/manage_halaman/add'));
            }

            $this->db->trans_start();

            $this->Md_siperpus_manage_halaman->addHalaman([
                'judul_halaman' => $this->input->post('judul_halaman'),
                'link_halaman' => 'lib.pkr.ac.id/halaman/' . $slug,
                'isi_halaman' => $this->input->post('isi_halaman'),
                'meta_keyword' => $this->input->post('meta_keyword'),
                'meta_desc' => $this->input->post('meta_desc'),
                'tgl_post' => date('Y-m-d'),
                'author' => $this->session->userdata('username'),
                'status' => 1,
                'jenis' => 'dinamis',
            ]);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Gagal tambah halaman.');
                redirect(base_url('admin/manage_halaman/add'));
            }

            $this->Md_log->addLog([
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'add',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Melakukan add Halaman',
                'IP' => $this->input->ip_address()
            ]);

            $this->session->set_flashdata('alert', 'alert-success');
            $this->session->set_flashdata('flash_message', 'Berhasil ditambahkan.');

            redirect(base_url('admin/manage_halaman'));
        } else if ($param1 == 'list') {
            $total = $this->Md_siperpus_manage_halaman->countFiltered();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;
            $perpage = intval($this->input->post('datatable[pagination][perpage]')) == 0 ? 1 : intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');
            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');
            $sort = $this->input->post('datatable[sort][sort]');
            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');

            //mulai fetching data
            $data = array();
            $no = 0;
            $list = $this->Md_siperpus_manage_halaman->getDatatables();
            foreach ($list as $row) {
                $arr = array();
                $arr['id'] = $row->halaman_id;
                $arr['judul_halaman'] = $row->judul_halaman;
                $arr['link_halaman'] = $row->link_halaman;
                $arr['jenis'] = ucwords($row->jenis);
                $arr['tgl_post'] = date('d M Y', strtotime($row->tgl_post));
                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else if ($param1 == 'delete') {
            $halaman = $this->Md_siperpus_manage_halaman->getHalamanById($param2);

            if ($halaman[0]->jenis == 'statis') {
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Jenis Statis Tidak dapat dihapus.');
                redirect(base_url('admin/manage_halaman'));
            }

            $this->db->trans_start();

            $this->Md_siperpus_manage_halaman->updateHalaman($param2, [
                'status' => 2,
            ]);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Gagal hapus halaman.');
                redirect(base_url('admin/manage_halaman'));
            }

            $this->Md_log->addLog([
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'edit',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Melakukan hapus Halaman',
                'IP' => $this->input->ip_address()
            ]);

            $this->session->set_flashdata('alert', 'alert-success');
            $this->session->set_flashdata('flash_message', 'Berhasil hapus.');

            redirect(base_url('admin/manage_halaman'));
        } else if ($param1 == 'preview') {
            $this->load->library('form_validation');
            $this->load->helper('menu_generator');

            $this->form_validation->set_rules('judul_halaman', 'Judul', 'required|max_length[255]');
            $this->form_validation->set_rules('link_halaman', 'Link', 'required');
            $this->form_validation->set_rules('isi_halaman', 'Isi', 'required');

            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', validation_errors());
                redirect(base_url('admin/manage_halaman/add'));
            }
            $isihalaman = $this->input->post('isi_halaman');
            $judul_halaman = $this->input->post('judul_halaman');
            //'link_halaman' => 'lib.pkr.ac.id/halaman/' . $slug,

            $meta_keyword = $this->input->post('meta_keyword');
            $meta_desc = $this->input->post('meta_desc');

            $halaman = new stdClass();

            $halaman->isi_halaman = $isihalaman;
            $halaman->judul_halaman = $judul_halaman;
            $halaman->meta_keyword = $meta_keyword;
            $halaman->meta_desc = $meta_desc;

            //$halaman = $this->Md_siperpus_manage_halaman->getHalamanByLink("lib.pkr.ac.id/halaman/$slug");
            $page_data['halaman'] = $halaman;

            $page_data['isi'] = json_decode(json_encode($halaman), true);
            $page_data['page_content'] = 'halaman';
            $page_data['page_name'] = 'halaman';

            $this->load->view('front', $page_data);
        }
    }

    function statistik($param1 = "", $param2 = "", $param3 = "")
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $page_data['slide'] = '';
        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'slide';
        $page_data['page_title'] = 'Statistik';
        $page_data['page_name'] = 'statistik';
        $page_data['page_action'] = '';
        if ($param1 == 'anggota') {
            $page_data['page_action'] = $param1;
            $total = 0;
            $xAxis = array();
            $yAxis = array();
            $mspst = $this->Md_vwprodi->getProdiAll();
            foreach ($mspst as $m) {
                $siswa = $this->Md_vwsiswa->getSiswaByKelas($m->nmmspst);
                array_push($xAxis, array('number', $m->nmmspst));
                array_push($yAxis, count($siswa));
                $total += count($siswa);
            }
            array_push($xAxis, array('number', 'Pegawai'));
            array_push($xAxis, array('number', 'Anggota Luar'));

            $pegawai = $this->Md_pegawai->count_pegawai();
            array_push($yAxis, $pegawai->total);
            $total += $pegawai->total;

            $anggotaluar = $this->Md_siperpus_anggota_luar->getAnggotaAll();
            $countanggota_lr = empty($anggotaluar) ? 0 : count($anggotaluar);
            array_push($yAxis, $countanggota_lr);
            $total += $countanggota_lr;
            $page_data['xAxis'] = $xAxis;
            $page_data['yAxis'] = $yAxis;
            $page_data['total'] = $total;
        }
        else if ($param1 == 'denda') {
            $awal = $this->input->post('tanggalawal');
            $akhir = $this->input->post('tanggalakhir');
            if ($awal != '' && $akhir != '') {
                $awal = date('Y-m-d', strtotime($this->input->post('tanggalawal')));
                $akhir = date('Y-m-d', strtotime($this->input->post('tanggalakhir')));
            }
            $page_data['tanggalawal'] = $this->input->post('tanggalawal');
            $page_data['tanggalakhir'] = $this->input->post('tanggalakhir');

            $page_data['page_action'] = $param1;
            $total = 0;
            $xAxis = array();
            $yAxis = array();
            $mspst = $this->Md_vwprodi->getProdiAll();
            foreach ($mspst as $m) {
                if ($awal != '' && $akhir != '')
                    $denda = $this->Md_siperpus_transaksi->getDendaByKelas_tgl($m->nmmspst, $awal, $akhir);
                else
                    $denda = $this->Md_siperpus_transaksi->getDendaByKelas($m->nmmspst);
                array_push($xAxis, array('number', $m->nmmspst));
                if ($denda[0]->denda > 0) {
                    array_push($yAxis, $denda[0]->denda);
                } else {
                    array_push($yAxis, 0);
                }
                $total += $denda[0]->denda;
            }
            $page_data['xAxis'] = $xAxis;
            $page_data['yAxis'] = $yAxis;
            $page_data['total'] = $total;
        } 
        else if ($param1 == 'bukureferensi') {
            $page_data['page_action'] = $param1;
            $total = 0;
            $xAxis = array();
            $yAxis = array();
            $mspst = $this->Md_vwprodi->getProdiAll();
            $klas = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();

            $page_data['prodi'] = $mspst;
            $page_data['klas'] = $klas;
        } 
        else if ($param1 == 'presensi') {
            $awal = $this->input->post('tanggalawal');
            $akhir = $this->input->post('tanggalakhir');
            if ($awal != '' && $akhir != '') {
                $awal = date('Y-m-d', strtotime($this->input->post('tanggalawal')));
                $akhir = date('Y-m-d', strtotime($this->input->post('tanggalakhir')));
            }
            $page_data['tanggalawal'] = $this->input->post('tanggalawal');
            $page_data['tanggalakhir'] = $this->input->post('tanggalakhir');

            $page_data['page_action'] = $param1;
            $total = 0;
            $xAxis = array();
            $yAxis = array();
            $mspst = $this->Md_vwprodi->getProdiAll();
            foreach ($mspst as $m) {
                if ($awal != '' && $akhir != '')
                    $presensi = $this->Md_siperpus_presensi->getPresensiByKelas_tgl($m->nmmspst, $awal, $akhir);
                else
                    $presensi = $this->Md_siperpus_presensi->getPresensiByKelas($m->nmmspst);
                array_push($xAxis, array('number', $m->nmmspst));
                if ($presensi[0]->total > 0) {
                    array_push($yAxis, $presensi[0]->total);
                } else {
                    array_push($yAxis, 0);
                }
                $total += $presensi[0]->total;
            }
            $page_data['xAxis'] = $xAxis;
            $page_data['yAxis'] = $yAxis;
            $page_data['total'] = $total;
        } 
        else if ($param1 == 'peminjaman') {
            $awal = $this->input->post('tanggalawal');
            $akhir = $this->input->post('tanggalakhir');
            if ($awal != '' && $akhir != '') {
                $awal = date('Y-m-d', strtotime($this->input->post('tanggalawal')));
                $akhir = date('Y-m-d', strtotime($this->input->post('tanggalakhir')));
            }
            $page_data['tanggalawal'] = $this->input->post('tanggalawal');
            $page_data['tanggalakhir'] = $this->input->post('tanggalakhir');
            $page_data['page_action'] = $param1;
            $total = 0;

            $xAxis = array();
            $yAxis = array();
            $mspst = $this->Md_vwprodi->getProdiAll();
            $klas = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
            foreach ($mspst as $m) {
                if ($awal != '' && $akhir != '')
                    $peminjaman = $this->Md_siperpus_transaksi->getPeminjamanByKelas_tgl($m->nmmspst, $awal, $akhir);
                else
                    $peminjaman = $this->Md_siperpus_transaksi->getPeminjamanByKelas($m->nmmspst);
                array_push($xAxis, array('number', $m->nmmspst));
                if ($peminjaman[0]->total > 0) {
                    array_push($yAxis, $peminjaman[0]->total);
                } else {
                    array_push($yAxis, 0);
                }
                $total += $peminjaman[0]->total;
            }
            $page_data['pxAxis'] = $xAxis;
            $page_data['pyAxis'] = $yAxis;
            $page_data['ptotal'] = $total;
            $page_data['ptitle'] = 'Statistik Peminjaman Berdasarkan Prodi';

            $xAxis = array();
            $yAxis = array();
            $total = 0;
            foreach ($klas as $k) {
                if ($awal != '' && $akhir != '')
                    $peminjaman = $this->Md_siperpus_transaksi->getPeminjamanByKlasifikasi_tgl($k->id, $awal, $akhir);
                else
                    $peminjaman = $this->Md_siperpus_transaksi->getPeminjamanByKlasifikasi($k->id);

                array_push($xAxis, array('number', $k->nama));
                if ($peminjaman[0]->total > 0) {
                    array_push($yAxis, $peminjaman[0]->total);
                } else {
                    array_push($yAxis, 0);
                }
                $total += $peminjaman[0]->total;
            }
            $page_data['kxAxis'] = $xAxis;
            $page_data['kyAxis'] = $yAxis;
            $page_data['ktotal'] = $total;
            $page_data['ktitle'] = 'Statistik Peminjaman Berdasarkan Klasifikasi Buku';

            $xAxis = array();
            $yAxis = array();
            $total = 0;

            if ($awal != '' && $akhir != '')
                $mhs = $this->Md_siperpus_transaksi->getAnggotaPinjamTerbanyak_tgl($awal, $akhir);
            else
                $mhs = $this->Md_siperpus_transaksi->getAnggotaPinjamTerbanyak();

            foreach ($mhs as $m) {
                array_push($xAxis, array('number', $m->nama));
                if ($m->total > 0) {
                    array_push($yAxis, $m->total);
                } else {
                    array_push($yAxis, 0);
                }
                $total += $m->total;
            }
            $page_data['mxAxis'] = $xAxis;
            $page_data['myAxis'] = $yAxis;
            $page_data['mtotal'] = $total;
            $page_data['mtitle'] = 'Statistik 10 mahasiswa Aktif Terbanyak Peminjaman Buku';

            $yearnow = date('Y');
            $yearbefore = $yearnow - 1;
            $xAxis = array();
            $yAxis = array();
            $total = 0;
            $mhs = $this->Md_siperpus_transaksi->getAnggotaPinjamTerbanyakTahun($yearnow);
            foreach ($mhs as $m) {
                array_push($xAxis, array('number', $m->nama));
                if ($m->total > 0) {
                    array_push($yAxis, $m->total);
                } else {
                    array_push($yAxis, 0);
                }
                $total += $m->total;
            }
            $page_data['nxAxis'] = $xAxis;
            $page_data['nyAxis'] = $yAxis;
            $page_data['ntotal'] = $total;
            $page_data['ntitle'] = 'Statistik 10 mahasiswa Aktif Terbanyak Peminjaman Buku Tahun ' . $yearnow;

            $xAxis = array();
            $yAxis = array();
            $total = 0;
            $mhs = $this->Md_siperpus_transaksi->getAnggotaPinjamTerbanyakTahun($yearbefore);
            foreach ($mhs as $m) {
                array_push($xAxis, array('number', $m->nama));
                if ($m->total > 0) {
                    array_push($yAxis, $m->total);
                } else {
                    array_push($yAxis, 0);
                }
                $total += $m->total;
            }
            $page_data['bxAxis'] = $xAxis;
            $page_data['byAxis'] = $yAxis;
            $page_data['btotal'] = $total;
            $page_data['btitle'] = 'Statistik 10 mahasiswa Aktif Terbanyak Peminjaman Buku Tahun ' . $yearbefore;
        } 
        else if ($param1 == 'periodik') {
            $page_data['page_action'] = $param1;
            if (!$this->input->post('tahun'))
                $year = date('Y');
            else
                $year = $this->input->post('tahun');
            if (!$this->input->post('bulan'))
                $month = '';
            else
                $month = $this->input->post('bulan');
            $total = 0;
            $page_data['curY'] = $year;
            $page_data['curM'] = $month;

            $xAxis = array();
            $yAxis = array();
            $mspst = $this->Md_vwprodi->getProdiAll();
            $klas = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
            $total = 0;
            foreach ($mspst as $m) {
                $kunjungan = $this->Md_siperpus_presensi->getKunjunganProdiByTahun($year, $month, $m->nmmspst);
                array_push($xAxis, array('number', $m->nmmspst));
                if ($kunjungan > 0) {
                    array_push($yAxis, $kunjungan);
                } else {
                    array_push($yAxis, 0);
                }
                $total += $kunjungan;
            }
            $page_data['pxAxis'] = $xAxis;
            $page_data['pyAxis'] = $yAxis;
            $page_data['ptotal'] = $total;
            $page_data['ptitle'] = 'Statistik Presensi Kunjungan Per Prodi pada Tahun ' . $year;

            $xAxis = array();
            $yAxis = array();
            $total = 0;
            foreach ($mspst as $m) {
                $pinjam = $this->Md_siperpus_transaksi->getPeminjamanProdiByTahun($year, $month, $m->nmmspst);
                array_push($xAxis, array('number', $m->nmmspst));
                if ($pinjam > 0) {
                    array_push($yAxis, $pinjam);
                } else {
                    array_push($yAxis, 0);
                }
                $total += $pinjam;
            }
            $page_data['kxAxis'] = $xAxis;
            $page_data['kyAxis'] = $yAxis;
            $page_data['ktotal'] = $total;
            $page_data['ktitle'] = 'Statistik Peminjaman Per Prodi pada Tahun ' . $year;

            $xAxis = array();
            $yAxis = array();
            $total = 0;
            foreach ($klas as $k) {
                $pinjam = $this->Md_siperpus_transaksi->getPeminjamanKlasByTahun($year, $month, $k->id);
                array_push($xAxis, array('number', $k->nama));
                if ($pinjam > 0) {
                    array_push($yAxis, $pinjam);
                } else {
                    array_push($yAxis, 0);
                }
                $total += $pinjam;
            }
            $page_data['mxAxis'] = $xAxis;
            $page_data['myAxis'] = $yAxis;
            $page_data['mtotal'] = $total;
            $page_data['mtitle'] = 'Statistik Peminjaman Per Klasifikasi Buku pada Tahun ' . $year;

            $xAxis = array();
            $yAxis = array();
            $total = 0;
            foreach ($klas as $k) {
                $pinjam = $this->Md_siperpus_inventaris->getInventarisByTahunKlas($year, $month, $k->id);
                array_push($xAxis, array('number', $k->nama));
                if ($pinjam > 0) {
                    array_push($yAxis, $pinjam);
                } else {
                    array_push($yAxis, 0);
                }
                $total += $pinjam;
            }
            $page_data['nxAxis'] = $xAxis;
            $page_data['nyAxis'] = $yAxis;
            $page_data['ntotal'] = $total;
            $page_data['ntitle'] = 'Statistik Jumlah Inventaris pada Tahun ' . $year;
        } 
        else if ($param1 == 'buku') {
            $page_data['page_action'] = $param1;
            if (!$this->input->post('status'))
                $status = '';
            else
                $status = $this->input->post('status');
            $page_data['cur'] = $status;
            $nm = '';
            if ($status == '')
                $nm = 'Stok Aktif';
            if ($status == 'H')
                $nm = 'Hilang';
            if ($status == 'R')
                $nm = 'Rusak';
            if ($status == 'A')
                $nm = 'Diarsipkan';
            if ($status == 'L')
                $nm = 'Dilelang';

            $xAxis = array();
            $yAxis = array();
            $mspst = $this->Md_vwprodi->getProdiAll();
            $asal = $this->Md_siperpus_asal_buku->getAsalBukuAll();
            $bahasa = $this->Md_siperpus_bahasa->getBahasaAll();
            $kat = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
            $klas = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
            $total = 0;
            foreach ($klas as $k) {
                $invs = $this->Md_siperpus_inventaris->getInventarisByStatusKlas($status, $k->id);
                array_push($xAxis, array('number', $k->nama));
                if ($invs > 0) {
                    array_push($yAxis, $invs);
                } else {
                    array_push($yAxis, 0);
                }
                $total += $invs;
            }
            $page_data['pxAxis'] = $xAxis;
            $page_data['pyAxis'] = $yAxis;
            $page_data['ptotal'] = $total;
            $page_data['ptitle'] = 'Statistik Inventarisasi Buku Berdasarkan Klasifikasi dengan Status ' . $nm;

            $xAxis = array();
            $yAxis = array();
            $total = 0;
            foreach ($kat as $k) {
                $invs = $this->Md_siperpus_inventaris->getInventarisByStatusKat($status, $k->idkategori);
                array_push($xAxis, array('number', $k->nmkategori));
                if ($invs > 0) {
                    array_push($yAxis, $invs);
                } else {
                    array_push($yAxis, 0);
                }
                $total += $invs;
            }
            $page_data['kxAxis'] = $xAxis;
            $page_data['kyAxis'] = $yAxis;
            $page_data['ktotal'] = $total;
            $page_data['ktitle'] = 'Statistik Inventarisasi Buku Berdasarkan Kategori dengan Status ' . $nm;

            $xAxis = array();
            $yAxis = array();
            $total = 0;
            foreach ($asal as $a) {
                $invs = $this->Md_siperpus_inventaris->getInventarisByStatusAsal($status, $a->id);
                array_push($xAxis, array('number', $a->nama));
                if ($invs > 0) {
                    array_push($yAxis, $invs);
                } else {
                    array_push($yAxis, 0);
                }
                $total += $invs;
            }
            $page_data['mxAxis'] = $xAxis;
            $page_data['myAxis'] = $yAxis;
            $page_data['mtotal'] = $total;
            $page_data['mtitle'] = 'Statistik Inventarisasi Buku Berdasarkan Asal Buku dengan Status ' . $nm;

            $xAxis = array();
            $yAxis = array();
            $total = 0;
            foreach ($bahasa as $b) {
                $pinjam = $this->Md_siperpus_inventaris->getInventarisByStatusBahasa($status, $b->id);
                array_push($xAxis, array('number', $b->nama));
                if ($pinjam > 0) {
                    array_push($yAxis, $pinjam);
                } else {
                    array_push($yAxis, 0);
                }
                $total += $pinjam;
            }
            $page_data['nxAxis'] = $xAxis;
            $page_data['nyAxis'] = $yAxis;
            $page_data['ntotal'] = $total;
            $page_data['ntitle'] = 'Statistik Inventarisasi Buku Berdasarkan Bahasa Buku dengan Status ' . $nm;

            $xAxis = array();
            $yAxis = array();
            $total = 0;
            $bukupinjam = $this->Md_siperpus_transaksi->getStatusBukuPinjamTerbanyak($status, 10);
            if (count($bukupinjam) > 0) {
                foreach ($bukupinjam as $b) {
                    array_push($xAxis, array('number', $b->judul));
                    if ($b->total > 0) {
                        array_push($yAxis, $b->total);
                    } else {
                        array_push($yAxis, 0);
                    }
                    $total += $b->total;
                }
            } else {
                array_push($xAxis, array('number', ''));
                array_push($yAxis, 0);
            }
            $page_data['qxAxis'] = $xAxis;
            $page_data['qyAxis'] = $yAxis;
            $page_data['qtotal'] = $total;
            $page_data['qtitle'] = 'Statistik Inventarisasi Buku Yang Paling Banyak Dipinjam dengan Status ' . $nm;
        } 
        else if ($param1 == 'bukubyjudul') {
            $page_data['page_action'] = $param1;
            if (!$this->input->post('status'))
                $status = '';
            else
                $status = $this->input->post('status');
            $page_data['cur'] = $status;
            $nm = '';
            if ($status == '')
                $nm = 'Stok Aktif';
            if ($status == 'H')
                $nm = 'Hilang';
            if ($status == 'R')
                $nm = 'Rusak';
            if ($status == 'A')
                $nm = 'Diarsipkan';
            if ($status == 'L')
                $nm = 'Dilelang';

            $klas = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
            $kat = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
            $asal = $this->Md_siperpus_asal_buku->getAsalBukuAll();
            $bahasa = $this->Md_siperpus_bahasa->getBahasaAll();
            $mspst = $this->Md_vwprodi->getProdiAll();

            $xAxis = array();
            $yAxis = array();
            $total = 0;
            foreach ($klas as $k) {
                $invs = $this->Md_siperpus_data_buku->getBukuByStatusKlas($status, $k->id);
                array_push($xAxis, array('number', $k->nama));
                if ($invs > 0) {
                    array_push($yAxis, $invs);
                } else {
                    array_push($yAxis, 0);
                }
                $total += $invs;
            }
            $page_data['pxAxis'] = $xAxis;
            $page_data['pyAxis'] = $yAxis;
            $page_data['ptotal'] = $total;
            $page_data['ptitle'] = 'Statistik Judul Buku Berdasarkan Klasifikasi dengan Status ' . $nm;

            $xAxis = array();
            $yAxis = array();
            $total = 0;
            foreach ($kat as $k) {
                $invs = $this->Md_siperpus_data_buku->getBukuByStatusKat($status, $k->idkategori);
                array_push($xAxis, array('number', $k->nmkategori));
                if ($invs > 0) {
                    array_push($yAxis, $invs);
                } else {
                    array_push($yAxis, 0);
                }
                $total += $invs;
            }
            $page_data['kxAxis'] = $xAxis;
            $page_data['kyAxis'] = $yAxis;
            $page_data['ktotal'] = $total;
            $page_data['ktitle'] = 'Statistik Judul Buku Berdasarkan Kategori dengan Status ' . $nm;

            $xAxis = array();
            $yAxis = array();
            $total = 0;
            foreach ($asal as $a) {
                $invs = $this->Md_siperpus_data_buku->getBukuByStatusAsal($status, $a->id);
                array_push($xAxis, array('number', $a->nama));
                if ($invs > 0) {
                    array_push($yAxis, $invs);
                } else {
                    array_push($yAxis, 0);
                }
                $total += $invs;
            }
            $page_data['mxAxis'] = $xAxis;
            $page_data['myAxis'] = $yAxis;
            $page_data['mtotal'] = $total;
            $page_data['mtitle'] = 'Statistik Judul Buku Berdasarkan Asal Buku dengan Status ' . $nm;

            $xAxis = array();
            $yAxis = array();
            $total = 0;
            foreach ($bahasa as $b) {
                $pinjam = $this->Md_siperpus_data_buku->getBukuByStatusBahasa($status, $b->id);
                array_push($xAxis, array('number', $b->nama));
                if ($pinjam > 0) {
                    array_push($yAxis, $pinjam);
                } else {
                    array_push($yAxis, 0);
                }
                $total += $pinjam;
            }
            $page_data['nxAxis'] = $xAxis;
            $page_data['nyAxis'] = $yAxis;
            $page_data['ntotal'] = $total;
            $page_data['ntitle'] = 'Statistik Judul Buku Berdasarkan Bahasa Buku dengan Status ' . $nm;

            $xAxis = array();
            $yAxis = array();
            $total = 0;
            $bukupinjam = $this->Md_siperpus_transaksi->getStatusBukuPinjamTerbanyak($status, 10);
            if (count($bukupinjam) > 0) {
                foreach ($bukupinjam as $b) {
                    array_push($xAxis, array('number', $b->judul));
                    if ($b->total > 0) {
                        array_push($yAxis, $b->total);
                    } else {
                        array_push($yAxis, 0);
                    }
                    $total += $b->total;
                }
            } else {
                array_push($xAxis, array('number', ''));
                array_push($yAxis, 0);
            }
            $page_data['qxAxis'] = $xAxis;
            $page_data['qyAxis'] = $yAxis;
            $page_data['qtotal'] = $total;
            $page_data['qtitle'] = 'Statistik Judul Buku Yang Paling Banyak Dipinjam dengan Status ' . $nm;
        } 
        else if ($param1 == 'bukubythn_judul') {
            $page_data['page_action'] = $param1;
            $gettahun = $this->Md_siperpus_data_buku->getTahunAll();
            $page_data['gettahun'] = $gettahun;

            if (!$this->input->post('status'))
                $status = '';
            else
                $status = $this->input->post('status');
            $page_data['cur'] = $status;

            if (!$this->input->post('tmp'))
                $tmp = 'T';
            else
                $tmp = $this->input->post('tmp');
            $page_data['tmp'] = $tmp;

            if (!$this->input->post('tahun'))
                $tahun = '';
            else
                $tahun = $this->input->post('tahun');
            $page_data['tahun'] = $tahun;

            $nm = '';
            if ($status == '')
                $nm = 'Stok Aktif';
            if ($status == 'H')
                $nm = 'Hilang';
            if ($status == 'R')
                $nm = 'Rusak';
            if ($status == 'A')
                $nm = 'Diarsipkan';
            if ($status == 'L')
                $nm = 'Dilelang';

            $klas = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
            $kat = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
            $asal = $this->Md_siperpus_asal_buku->getAsalBukuAll();
            $bahasa = $this->Md_siperpus_bahasa->getBahasaAll();
            $mspst = $this->Md_vwprodi->getProdiAll();

            if ($tmp == 'T' || $tmp == 'Pen') {
                $xAxis = array();
                $yAxis = array();
                $total = 0;
                foreach ($gettahun as $k) {
                    $thn = $this->Md_siperpus_data_buku->getBukuByThnJudul_Total($status, $k->thn_terbit);

                    array_push($xAxis, array('number', $k->thn_terbit));
                    if ($thn > 0) {
                        array_push($yAxis, $thn);
                    } else {
                        array_push($yAxis, 0);
                    }
                    $total += $thn;
                }
                $page_data['pxAxis'] = $xAxis;
                $page_data['pyAxis'] = $yAxis;
                $page_data['ptotal'] = $total;
                $page_data['ptitle'] = 'Statistik (Berdasar Judul Buku) Tahun Terbit Buku dengan Status Stok ' . $nm;
            } else {
                $xAxis = array();
                $yAxis = array();
                $total = 0;
                foreach ($klas as $k) {
                    $invs = $this->Md_siperpus_data_buku->getBukuByThnJudul_Klas($status, $k->id, $tahun);
                    array_push($xAxis, array('number', $k->nama));
                    if ($invs > 0) {
                        array_push($yAxis, $invs);
                    } else {
                        array_push($yAxis, 0);
                    }
                    $total += $invs;
                }
                $page_data['pxAxis'] = $xAxis;
                $page_data['pyAxis'] = $yAxis;
                $page_data['ptotal'] = $total;
                $page_data['ptitle'] = 'Statistik (Berdasar Judul Buku) Tahun Terbit Buku Berdasarkan Klasifikasi Buku dengan Status Stok ' . $nm . ' (' . $tahun . ')';

                $xAxis = array();
                $yAxis = array();
                $total = 0;
                foreach ($kat as $k) {
                    $invs = $this->Md_siperpus_data_buku->getBukuByThnJudul_Kat($status, $k->idkategori, $tahun);
                    array_push($xAxis, array('number', $k->nmkategori));
                    if ($invs > 0) {
                        array_push($yAxis, $invs);
                    } else {
                        array_push($yAxis, 0);
                    }
                    $total += $invs;
                }
                $page_data['kxAxis'] = $xAxis;
                $page_data['kyAxis'] = $yAxis;
                $page_data['ktotal'] = $total;
                $page_data['ktitle'] = 'Statistik (Berdasar Judul Buku) Tahun Terbit Buku Berdasarkan Kategori Buku dengan Status Stok ' . $nm . ' (' . $tahun . ')';

                $xAxis = array();
                $yAxis = array();
                $total = 0;
                foreach ($asal as $a) {
                    $invs = $this->Md_siperpus_data_buku->getBukuByThnJudul_Asal($status, $a->id, $tahun);
                    array_push($xAxis, array('number', $a->nama));
                    if ($invs > 0) {
                        array_push($yAxis, $invs);
                    } else {
                        array_push($yAxis, 0);
                    }
                    $total += $invs;
                }
                $page_data['mxAxis'] = $xAxis;
                $page_data['myAxis'] = $yAxis;
                $page_data['mtotal'] = $total;
                $page_data['mtitle'] = 'Statistik (Berdasar Judul Buku) Tahun Terbit Buku Berdasarkan Asal Buku dengan Status Stok ' . $nm . ' (' . $tahun . ')';

                // $xAxis=array();
                // $yAxis=array();
                // $total=0;
                // foreach($bahasa as $b){
                // 		$pinjam=$this->Md_siperpus_data_buku->getBukuByStatusBahasa($status,$b->id);
                // 		array_push($xAxis,array('number',$b->nama));
                // 		if($pinjam>0){
                // 		array_push($yAxis,$pinjam);
                // 		}else{
                // 		array_push($yAxis,0);
                // 		}
                // 		$total+=$pinjam;
                // }
                // $page_data['nxAxis'] = $xAxis;
                // $page_data['nyAxis'] = $yAxis;
                // $page_data['ntotal'] = $total;
                // $page_data['ntitle'] = 'Statistik Judul Buku Berdasarkan Bahasa Buku dengan Status '.$nm;
                // $xAxis=array();
                // $yAxis=array();
                // $total=0;
                // $bukupinjam=$this->Md_siperpus_transaksi->getStatusBukuPinjamTerbanyak($status,10);
                // if(count($bukupinjam)>0){
                // 	foreach($bukupinjam as $b){
                // 			array_push($xAxis,array('number',$b->judul));
                // 			if($b->total>0){
                // 			array_push($yAxis,$b->total);
                // 			}else{
                // 			array_push($yAxis,0);
                // 			}
                // 			$total+=$b->total;
                // 	}
                // }else{
                // 	array_push($xAxis,array('number',''));
                // 	array_push($yAxis,0);
                // }
                // $page_data['qxAxis'] = $xAxis;
                // $page_data['qyAxis'] = $yAxis;
                // $page_data['qtotal'] = $total;
                // $page_data['qtitle'] = 'Statistik Judul Buku Yang Paling Banyak Dipinjam dengan Status '.$nm;
            }
        } 
        else if ($param1 == 'bukubythn_jml') {
            $page_data['page_action'] = $param1;
            $gettahun = $this->Md_siperpus_data_buku->getTahunAll();
            $page_data['gettahun'] = $gettahun;

            if (!$this->input->post('status'))
                $status = '';
            else
                $status = $this->input->post('status');
            $page_data['cur'] = $status;

            if (!$this->input->post('tmp'))
                $tmp = 'T';
            else
                $tmp = $this->input->post('tmp');
            $page_data['tmp'] = $tmp;

            if (!$this->input->post('tahun'))
                $tahun = '';
            else
                $tahun = $this->input->post('tahun');
            $page_data['tahun'] = $tahun;

            $nm = '';
            if ($status == '')
                $nm = 'Stok Aktif';
            if ($status == 'H')
                $nm = 'Hilang';
            if ($status == 'R')
                $nm = 'Rusak';
            if ($status == 'A')
                $nm = 'Diarsipkan';
            if ($status == 'L')
                $nm = 'Dilelang';

            $klas = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
            $kat = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
            $asal = $this->Md_siperpus_asal_buku->getAsalBukuAll();
            $bahasa = $this->Md_siperpus_bahasa->getBahasaAll();
            $mspst = $this->Md_vwprodi->getProdiAll();

            if ($tmp == 'T' || $tmp == 'Pen') {
                $xAxis = array();
                $yAxis = array();
                $total = 0;
                foreach ($gettahun as $k) {
                    $thn = $this->Md_siperpus_inventaris->getJmlBukuByThnJudul_Total($status, $k->thn_terbit);

                    array_push($xAxis, array('number', $k->thn_terbit));
                    if ($thn > 0) {
                        array_push($yAxis, $thn);
                    } else {
                        array_push($yAxis, 0);
                    }
                    $total += $thn;
                }
                $page_data['pxAxis'] = $xAxis;
                $page_data['pyAxis'] = $yAxis;
                $page_data['ptotal'] = $total;
                $page_data['ptitle'] = 'Statistik (Berdasar Judul Buku) Tahun Terbit Buku dengan Status Stok ' . $nm;
            } else {
                $xAxis = array();
                $yAxis = array();
                $total = 0;
                foreach ($klas as $k) {
                    $invs = $this->Md_siperpus_inventaris->getJmlBukuByThnJudul_Klas($status, $k->id, $tahun);
                    array_push($xAxis, array('number', $k->nama));
                    if ($invs > 0) {
                        array_push($yAxis, $invs);
                    } else {
                        array_push($yAxis, 0);
                    }
                    $total += $invs;
                }
                $page_data['pxAxis'] = $xAxis;
                $page_data['pyAxis'] = $yAxis;
                $page_data['ptotal'] = $total;
                $page_data['ptitle'] = 'Statistik (Berdasar Judul Buku) Tahun Terbit Buku Berdasarkan Klasifikasi Buku dengan Status Stok ' . $nm . ' (' . $tahun . ')';

                $xAxis = array();
                $yAxis = array();
                $total = 0;
                foreach ($kat as $k) {
                    $invs = $this->Md_siperpus_inventaris->getJmlBukuByThnJudul_Kat($status, $k->idkategori, $tahun);
                    array_push($xAxis, array('number', $k->nmkategori));
                    if ($invs > 0) {
                        array_push($yAxis, $invs);
                    } else {
                        array_push($yAxis, 0);
                    }
                    $total += $invs;
                }
                $page_data['kxAxis'] = $xAxis;
                $page_data['kyAxis'] = $yAxis;
                $page_data['ktotal'] = $total;
                $page_data['ktitle'] = 'Statistik (Berdasar Judul Buku) Tahun Terbit Buku Berdasarkan Kategori Buku dengan Status Stok ' . $nm . ' (' . $tahun . ')';

                $xAxis = array();
                $yAxis = array();
                $total = 0;
                foreach ($asal as $a) {
                    $invs = $this->Md_siperpus_inventaris->getJmlBukuByThnJudul_Asal($status, $a->id, $tahun);
                    array_push($xAxis, array('number', $a->nama));
                    if ($invs > 0) {
                        array_push($yAxis, $invs);
                    } else {
                        array_push($yAxis, 0);
                    }
                    $total += $invs;
                }
                $page_data['mxAxis'] = $xAxis;
                $page_data['myAxis'] = $yAxis;
                $page_data['mtotal'] = $total;
                $page_data['mtitle'] = 'Statistik (Berdasar Judul Buku) Tahun Terbit Buku Berdasarkan Asal Buku dengan Status Stok ' . $nm . ' (' . $tahun . ')';
            }
        } 
        else if ($param1 == 'kunjungan_baca_buku') {
            $awal = $this->input->post('tanggalawal');
            $akhir = $this->input->post('tanggalakhir');
            if ($awal != '' && $akhir != '') {
                $awal = date('Y-m-d', strtotime($this->input->post('tanggalawal')));
                $akhir = date('Y-m-d', strtotime($this->input->post('tanggalakhir')));
            }
            $page_data['tanggalawal'] = $this->input->post('tanggalawal');
            $page_data['tanggalakhir'] = $this->input->post('tanggalakhir');

            $page_data['page_action'] = 'kunjungan_baca_buku';
            $total = 0;
            $xAxis = array();
            $yAxis = array();
            $mspst = $this->Md_siperpus_kategori->getKategoriAll();

            $xAxis2 = array();
            $yAxis2 = array();
            $total2 = 0;
            $limit = 30;
            $data = $this->Md_view_filebuku->getDataByTgl($awal, $akhir);

            $data2 = $this->Md_view_filebuku->getTopDataByLimit($limit);

            if ($data) {
                foreach ($data as $p) {
                    array_push($xAxis, array('number', $p->nmkategori));

                    if ($p->total > 0) {
                        array_push($yAxis, $p->total);
                    } else {
                        array_push($yAxis, 0);
                    }
                    $total += $p->total;
                }
            }

            if ($data2) {
                foreach ($data2 as $p) {
                    array_push($xAxis2, array('number', $p->judul));

                    if ($p->total > 0) {
                        array_push($yAxis2, $p->total);
                    } else {
                        array_push($yAxis2, 0);
                    }
                    $total2 += $p->total;
                }
            }


            $page_data['xAxis'] = $xAxis;
            $page_data['yAxis'] = $yAxis;
            $page_data['total'] = $total;

            $page_data['xAxis2'] = $xAxis2;
            $page_data['yAxis2'] = $yAxis2;
            $page_data['total2'] = $total2;
            $page_data['top'] = $limit;
        } 
        else if ($param1 == 'pengunjung_web') {
            $awal = $this->input->post('tanggalawal');
            $akhir = $this->input->post('tanggalakhir');
            if ($awal != '' && $akhir != '') {
                $awal = date('Y-m-d', strtotime($this->input->post('tanggalawal')));
                $akhir = date('Y-m-d', strtotime($this->input->post('tanggalakhir')));
            }
            $page_data['tanggalawal'] = $awal;
            $page_data['tanggalakhir'] = $akhir;

            $page_data['page_action'] = 'pengunjung_web';
            $total = 0;
            $xAxis = array();
            $yAxis = array();

            $data = $this->Md_visitors->getDataByTgl($awal, $akhir);

            if ($data) {
                foreach ($data as $p) {
                    array_push($xAxis, array('number', $p->date));

                    if ($p->total > 0) {
                        array_push($yAxis, $p->total);
                    } else {
                        array_push($yAxis, 0);
                    }
                    $total += $p->total;
                }
            }

            $page_data['xAxis'] = $xAxis;
            $page_data['yAxis'] = $yAxis;
            $page_data['total'] = $total;
        }

        $this->load->view('index', $page_data);
    }

    function cetak($param1 = "", $param2 = "", $param3 = "")
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();

        // Ambil semua data POST
        $all_post = $this->input->post();
        $page_data['imagedata'] = ""; // Default kosong

        // LOOP OTOMATIS: Mencari input yang namanya mengandung 'imagedata'
        // Ini akan menangkap imagedata, imagedata2, ..., imagedata7, dst.
        if ($all_post) {
            foreach ($all_post as $key => $value) {
                if (strpos($key, 'imagedata') !== false && !empty($value)) {
                    $page_data['imagedata'] = $value;
                    break; // Hentikan loop jika sudah ketemu satu yang berisi
                }
            }
        }

        // Ambil judul jika dikirim (Opsional, agar judul di cetakan sesuai)
        $page_data['judul_cetak'] = $this->input->post('judul_cetak') ? $this->input->post('judul_cetak') : 'Laporan Statistik';

        if ($this->input->post('type'))
            $page_data['import'] = $this->input->post('type');
        else
            $page_data['import'] = 'chart';

        if ($page_data['import'] == 'table') {
            $mspst = $this->Md_vwprodi->getProdiAll();
            $klas = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();

            $page_data['prodi'] = $mspst;
            $page_data['klas'] = $klas;
        }

        $page_data['export'] = 'web';

        $this->load->view('admin/cetak', $page_data);
    }

    public function laporan()
    {

        if ($this->session->userdata('login_type') == 'admin')
            redirect(base_url() . 'admin/laporan_anggota', 'refresh');
    }

    function laporan_buku_prodi($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();

        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['src'] = '';
        $page_data['prodi'] = '';
        $page_data['klas'] = '';
        $page_data['ktg'] = '';
        $page_data['page_title'] = 'Form Daftar Buku Referensi Jurusan';

        $page_data['jur'] = $this->Md_vwprodi->getProdiAll();
        $page_data['klasifikasi'] = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
        $page_data['kategori'] = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
        if ($this->input->post('src'))
            $page_data['src'] = $this->input->post('src');
        if ($this->input->post('klasifikasi'))
            $page_data['klas'] = $this->input->post('klasifikasi');
        if ($this->input->post('kategori'))
            $page_data['ktg'] = $this->input->post('kategori');
        if ($this->input->post('prodi'))
            $page_data['prodi'] = $this->input->post('prodi');

        if ($param1 == 'export') {
            $klas = '';
            $ktg = '';
            $prodi = '';
            $search = '';

            if ($param2 == 'excel') {
                $klas = $this->input->post('eklas');
                $ktg = $this->input->post('ektg');
                $prodi = $this->input->post('eprodi');
                $search = $this->input->post('esearch');
            } elseif ($param2 == 'web') {
                $klas = $this->input->post('wklas');
                $ktg = $this->input->post('wktg');
                $prodi = $this->input->post('wprodi');
                $search = $this->input->post('wsearch');
            }
            $page_data['nmkls'] = $this->Md_siperpus_klasifikasi->getKlasifikasiById($klas);
            $page_data['nmprodi'] = $this->Md_vwprodi->getProdiById($prodi);
            $page_data['nmktg'] = $this->Md_siperpus_kategori_buku->getKategoriById($ktg);
            $page_data['laporan'] = 'laporan_buku_prodi';
            $page_data['export'] = $param2;
            $page_data['page_title'] = 'Form Daftar Buku Referensi Jurusan';
            $page_data['title'] = 'Form Daftar Buku Referensi Jurusan';
            $page_data['data'] = $this->Md_laporan_buku_prodi->getLaporan($klas, $ktg, $prodi, $search);
            $this->load->view('admin/excel', $page_data);
        } else if ($param1 == 'fetch') {
            $total = $this->Md_laporan_buku_prodi->countFiltered();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;

            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');

            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');

            $sort = $this->input->post('datatable[sort][sort]');

            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');

            //mulai fetching data
            $data = array();
            $no = 0;
            $list = $this->Md_laporan_buku_prodi->getDatatables();
            foreach ($list as $row) {
                $no++;
                $arr = array();
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['noklas'] = $row->no_klas;
                $arr['judul'] = $row->judul;
                $arr['penerbit'] = $row->penerbit;
                $nmprodi = '';
                $prodi = $this->Md_siperpus_buku_prodi->getBukuByISBN($row->ISBN, $row->no_klas);
                if (count($prodi) > 0) {
                    foreach ($prodi as $p) {
                        if ($nmprodi == '')
                            $nmprodi = $p->namaprodi;
                        else
                            $nmprodi = $nmprodi . ' , ' . $p->namaprodi;
                    }
                } else {
                    $nmprodi = '';
                }

                $arr['jurusan'] = $nmprodi;
                $arr['jumlah'] = $row->jml_buku;
                $data[] = $arr;
            }

            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else {
            $page_data['page_access'] = "admin";
            $page_data['page_name'] = 'laporan_buku_prodi';
            $page_data['page_now'] = 'dashboard';
            $this->load->view('index', $page_data);
        }
    }

    public function manage_logo($param1 = "", $param2 = "", $param3 = "")
    {
        if ($this->session->userdata('login_type') != 'admin') {
            $this->logout();
        }

        $page_data['logo'] = '';
        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'logo';
        $page_data['page_title'] = 'Data Logo';
        $page_data['page_name'] = 'manage_logo';
        //$page_data['media'] = $this->Md_media->getAllMedia();

        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
        $config['max_size'] = '2048';
        $config['file_name'] = 'logo_' . date("Ymdhi");

        $this->upload->initialize($config);
        $this->load->library('upload', $config);

        if ($param1 == 'add') {
            if ($param2 == 'do_add') {
                //if ($this->input->post('file')) {
                if ($id = $this->upload_media('logo')) {
                    $data['media_id'] = $id['id'];
                } else {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('alert', 'alert-danger');
                    $this->session->set_flashdata('flash_message', '<strong>Gagal</strong>' . $error);
                    redirect(site_url('admin/manage_logo/add'), 'refresh');
                }
                //}else{
                //    var_dump($this->input->post('file'));die;
                //}
                //$data['judul']				= $this->input->post('add_judul_slide');
                //$data['keterangan']			= $this->input->post('add_keterangan_slide');
                //$data['urutan']				= $this->input->post('add_urutan_slide');
                $data['tgl_post'] = date("Y-m-d");
                $data['author'] = $this->session->userdata('username');
                $data['status'] = 1;

                //hapus logo lama
                $dataUpdate['status'] = 2;
                $this->Md_logo->updateLogoAll($dataUpdate);

                $this->Md_logo->addLogo($data);
                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Add',
                    'status' => 1,
                    'keterangan' => $this->session->userdata('username') . ' Melakukan Add Logo ' . $data['media_id'],
                    'IP' => $this->input->ip_address()
                );
                $this->Md_log->addLog($log);
                $this->session->set_flashdata('alert', 'alert-success');
                $this->session->set_flashdata('flash_message', '<strong>Berhasil</strong>Kamu berhasil menambah Logo');
                redirect(site_url('admin/manage_logo'), 'refresh');
            } else {
                $page_data['logo'] = 'add';
            }

            $this->load->view('index', $page_data);
        } else if ($param1 == 'delete') {
            $id = $param2;
            $data['status'] = 2;

            $this->Md_slide->updateSlide($id, $data);
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Delete',
                'status' => 2,
                'keterangan' => $this->session->userdata('username') . ' Melakukan Delete Slide ' . $data['judul'],
                'IP' => $this->input->ip_address()
            );
            $this->Md_log->addLog($log);

            echo json_encode('success');
            die;
        } else if ($param1 == 'edit') {
            if ($param2 != '') {
                if ($param2 == 'do_edit') {
                    $error = '';
                    if ($this->upload->do_upload('file')) {
                        if ($id = $this->upload_media('slide')) {
                            $data['media_id'] = $id['id'];
                        } else {
                            $error = $this->upload->display_errors();
                            $this->session->set_flashdata('alert', 'alert-danger');
                            $this->session->set_flashdata('flash_message', '<strong>Gagal</strong>' . $error);
                            redirect(site_url('admin/manage_slide/edit'), 'refresh');
                        }
                    } else {
                        $data['media_id'] = $this->input->post('edit_media_slide');
                    }
                    $data['judul'] = $this->input->post('edit_judul_slide');
                    $data['keterangan'] = $this->input->post('edit_keterangan_slide');
                    $data['urutan'] = $this->input->post('edit_urutan_slide');
                    $data['tgl_post'] = date("Y-m-d");
                    $data['author'] = $this->session->userdata('username');
                    $data['status'] = 1;

                    if ($this->Md_slide->updateSlide($param3, $data) && $error == '') {
                        $log = array(
                            'user_id' => $this->session->userdata('idsys'),
                            'jenis_log' => 'Admin',
                            'jenis_akses' => 'Edit',
                            'status' => 1,
                            'keterangan' => $this->session->userdata('username') . ' Melakukan Edit Slide ' . $data['judul'],
                            'IP' => $this->input->ip_address()
                        );
                        $this->Md_log->addLog($log);
                        $this->session->set_flashdata('alert', 'alert-success');
                        $this->session->set_flashdata('flash_message', '<strong>Berhasil</strong>Berhasil Update Slide');
                    } else {
                        $this->session->set_flashdata('alert', 'alert-danger');
                        $this->session->set_flashdata('flash_message', '<strong>Gagal</strong>Gagal Update Slide' . $error);
                    }
                    redirect(base_url('admin/manage_slide'), 'refresh');
                } else {
                    $page_data['slide'] = 'edit';
                    $page_data['edit'] = $this->Md_slide->getSlideById($param2);
                }
                $this->load->view('index', $page_data);
            } else {
                redirect(base_url('admin/manage_slide'), 'refresh');
            }
        } else if ($param1 == 'list') {
            $list = $this->Md_logo->getAllLogo();
            if ($list) {
                foreach ($list as $row) {
                    $arr = array();
                    $arr['id'] = $row->logo_id;
                    $arr['judul'] = '<img src="' . base_url() . 'uploads/' . $row->judul . '" alt="" />';
                    //$arr['keterangan'] = $row->keterangan;
                    $arr['status'] = $row->status;
                    $arr['author'] = $row->author;
                    $arr['tgl_post'] = date('d M Y', strtotime($row->tgl_post));
                    $data[] = $arr;
                }
            } else {
                $data[] = array();
            }

            $meta = array();
            $meta['page'] = 1;
            $meta['pages'] = 1;
            $meta['perpage'] = 1;
            $meta['total'] = count($list);
            $meta['sort'] = 'asc';
            $meta['field'] = 'judul';
            $output = array(
                "meta" => $meta,
                "data" => $data
            );

            echo json_encode($output);
            exit();
        } else {
            $this->load->view('index', $page_data);
        }
    }

    public function manage_mediasosial($param1 = "", $param2 = "", $param3 = "")
    {

        if ($this->session->userdata('login_type') != 'admin') {
            $this->logout();
        }

        $page_data['logo'] = '';
        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'mediasosial';
        $page_data['page_title'] = 'Data Media Sosial';
        $page_data['page_name'] = 'manage_mediasosial';

        if ($param1 == 'add') {
            $this->form_validation->set_rules('user_mediasosial', 'field user_mediasosial', 'required');
            $this->form_validation->set_rules('nm_mediasosial', 'field nm_mediasosial', 'required');

            if ($this->form_validation->run() != false) {

                $user_mediasosial = $this->input->post('user_mediasosial');
                $nm_mediasosial = $this->input->post('nm_mediasosial');

                $icon_mediasosial = getMediaSosialIcon($nm_mediasosial);

                if ($icon_mediasosial == '') {
                    echo json_encode(array('status' => 'gagal', 'message' => 'Icon tidak ditemukan'));
                    die;
                }
                $dataInsert = array(
                    'user_mediasosial' => $user_mediasosial,
                    'nm_mediasosial' => $nm_mediasosial,
                    'icon' => $icon_mediasosial,
                    'tglpost' => date("Y-m-d H:i:s"),
                    'status' => 1
                );

                //cek gedung berdasarkan kode gedung, gedung dan lokasi id
                $cekDataSebelumnya = $this->Md_mediasosial->cekData($nm_mediasosial);

                if (empty($cekDataSebelumnya)) {
                    $this->db->trans_begin();
                    $this->Md_mediasosial->addData($dataInsert);
                    $log = array(
                        'user_id' => $this->session->userdata('idsys'),
                        'jenis_log' => 'Admin',
                        'jenis_akses' => 'Add',
                        'status' => 1,
                        'keterangan' => $this->session->userdata('username') . ' Melakukan Add Media Sosial ' . 'nm_mediasosial ' . $nm_mediasosial . ', user_mediasosial ' . $user_mediasosial,
                        'IP' => $this->input->ip_address()
                    );

                    $this->Md_log->addLog($log);

                    if ($this->db->trans_status() === true) {
                        $this->db->trans_commit();
                        echo json_encode(array('status' => 'success', 'message' => ''));
                        die;
                    } else {
                        $this->db->trans_rollback();
                        echo json_encode(array('status' => 'gagal', 'message' => 'Data gagal disimpan'));
                        die;
                    }
                } else {
                    echo json_encode(array('status' => 'gagal', 'message' => 'Data gagal disimpan, data sudah terdaftar'));
                    die;
                }
            } else {
                echo json_encode(array('status' => 'gagal', 'message' => 'Semua fill harus terisi'));
                die;
            }
        } else if ($param1 == 'list') {
            $list = $this->Md_mediasosial->getAllMediasosial();

            if ($list) {
                foreach ($list as $row) {
                    $arr = array();
                    $arr['id'] = encrypt($row->mediasosial_id);
                    $arr['icon'] = $row->icon;
                    //$arr['keterangan'] = $row->keterangan;
                    $arr['nm_mediasosial'] = $row->nm_mediasosial;
                    $arr['user_mediasosial'] = $row->user_mediasosial;

                    //$arr['tgl_post'] = date('d M Y', strtotime($row->tgl_post));
                    $data[] = $arr;
                }
            } else {
                $data[] = array();
            }

            $meta = array();
            $meta['page'] = 1;
            $meta['pages'] = 1;
            $meta['perpage'] = 1;
            $meta['total'] = count($list);
            $meta['sort'] = 'asc';
            $meta['field'] = 'judul';
            $output = array(
                "meta" => $meta,
                "data" => $data
            );

            echo json_encode($output);
            exit();
        } else if ($param1 == 'edit') {
            $valid = $param2 == '' ? false : (is_int(decrypt($param2)) ? true : false);

            if (!$valid) {
                echo json_encode(array('data' => false));
                die;
            }

            $data_load = $this->Md_mediasosial->getDataById(decrypt($param2));

            $row = array();
            if ($data_load) {
                $row['data'] = true;
                $row['mediasosial_id'] = encrypt($data_load->mediasosial_id);
                $row['nm_mediasosial'] = $data_load->nm_mediasosial;
                $row['user_mediasosial'] = $data_load->user_mediasosial;
            } else {
                $row['data'] = false;
            }

            echo json_encode($row);
            die;
        } else if ($param1 == 'update') {
            $this->form_validation->set_rules('mediasosial_id', 'field mediasosial_id', 'required');
            $this->form_validation->set_rules('user_mediasosial', 'field user_mediasosial', 'required');

            if ($this->form_validation->run() != false) {
                $mediasosial_id = is_int(decrypt($this->input->post('mediasosial_id'))) ? decrypt($this->input->post('mediasosial_id')) : redirect(base_url() . 'admin/manage_mediasosial/', 'refresh');

                $user_mediasosial = $this->input->post('user_mediasosial');

                $dataUpdate = array(
                    'user_mediasosial' => $user_mediasosial,
                );

                $this->db->trans_begin();
                $this->Md_mediasosial->updateData($mediasosial_id, $dataUpdate);

                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Edit',
                    'status' => 1,
                    'keterangan' => $this->session->userdata('username') . ' Melakukan Edit Media Sosial ' . ', user_mediasosial ' . $user_mediasosial . ', mediasosial_id   ' . $mediasosial_id,
                    'IP' => $this->input->ip_address()
                );

                $this->Md_log->addLog($log);

                if ($this->db->trans_status() === true) {
                    $this->db->trans_commit();
                    echo json_encode(array('status' => 'success', 'message' => ''));
                    die;
                } else {
                    $this->db->trans_rollback();
                    echo json_encode(array('status' => 'gagal', 'message' => 'Data gagal disimpan'));
                    die;
                }
            } else {
                echo json_encode(array('status' => 'gagal', 'message' => 'Semua fill harus terisi'));
                die;
            }
        } else if ($param1 == 'delete') {
            $valid = $param2 == '' ? false : (is_int(decrypt($param2)) ? true : false);
            if (!$valid) {
                echo json_encode(array('data' => false));
                die;
            }
            $delete_id = decrypt($param2);
            $this->db->trans_begin();
            $this->Md_mediasosial->updateData($delete_id, array('status' => 2));
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Delete',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' id: ' . $delete_id,
                'IP' => $this->input->ip_address()
            );

            $this->Md_log->addLog($log);
            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();
                echo json_encode(array('data' => true));
                die;
            } else {
                $this->db->trans_rollback();
                echo json_encode(array('data' => false));
                die;
            }
        } else {
            $this->load->view('index', $page_data);
        }
    }

    public function manage_kontak($param1 = "", $param2 = "", $param3 = "")
    {

        if ($this->session->userdata('login_type') != 'admin') {
            $this->logout();
        }

        $page_data['logo'] = '';
        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'manage_kontak';
        $page_data['page_title'] = 'Data WA';
        $page_data['page_name'] = 'manage_kontak';

        if ($param1 == 'add') {
            $this->form_validation->set_rules('no_wa', 'field no_wa', 'required');

            if ($this->form_validation->run() != false) {

                $no_wa = $this->input->post('no_wa');

                $dataInsert = array(
                    'hp' => $no_wa,
                    'status' => 1
                );

                //cek gedung berdasarkan kode gedung, gedung dan lokasi id
                $cekDataSebelumnya = $this->Md_kontak->cekData($no_wa);

                if (empty($cekDataSebelumnya)) {
                    $this->db->trans_begin();
                    $this->Md_kontak->addData($dataInsert);
                    $log = array(
                        'user_id' => $this->session->userdata('idsys'),
                        'jenis_log' => 'Admin',
                        'jenis_akses' => 'Add',
                        'status' => 1,
                        'keterangan' => $this->session->userdata('username') . ' Melakukan Add NO WA ' . 'no_hp ' . $no_wa,
                        'IP' => $this->input->ip_address()
                    );

                    $this->Md_log->addLog($log);

                    if ($this->db->trans_status() === true) {
                        $this->db->trans_commit();
                        echo json_encode(array('status' => 'success', 'message' => ''));
                        die;
                    } else {
                        $this->db->trans_rollback();
                        echo json_encode(array('status' => 'gagal', 'message' => 'Data gagal disimpan'));
                        die;
                    }
                } else {
                    echo json_encode(array('status' => 'gagal', 'message' => 'Data gagal disimpan, data sudah terdaftar'));
                    die;
                }
            } else {
                echo json_encode(array('status' => 'gagal', 'message' => 'Semua fill harus terisi'));
                die;
            }
        } else if ($param1 == 'list') {
            $list = $this->Md_kontak->getAllKontak();

            if ($list) {
                foreach ($list as $row) {
                    $arr = array();
                    $arr['id'] = encrypt($row->kontak_id);
                    $arr['hp'] = $row->hp;

                    //$arr['tgl_post'] = date('d M Y', strtotime($row->tgl_post));
                    $data[] = $arr;
                }
            } else {
                $data[] = array();
            }

            $meta = array();
            $meta['page'] = 1;
            $meta['pages'] = 1;
            $meta['perpage'] = 1;
            $meta['total'] = count($list);
            $meta['sort'] = 'asc';
            $meta['field'] = 'judul';
            $output = array(
                "meta" => $meta,
                "data" => $data
            );

            echo json_encode($output);
            exit();
        } else if ($param1 == 'edit') {
            $valid = $param2 == '' ? false : (is_int(decrypt($param2)) ? true : false);

            if (!$valid) {
                echo json_encode(array('data' => false));
                die;
            }

            $data_load = $this->Md_kontak->getDataById(decrypt($param2));

            $row = array();
            if ($data_load) {
                $row['data'] = true;
                $row['kontak_id'] = encrypt($data_load->kontak_id);
                $row['hp'] = $data_load->hp;
            } else {
                $row['data'] = false;
            }

            echo json_encode($row);
            die;
        } else if ($param1 == 'update') {
            $this->form_validation->set_rules('kontak_id', 'field kontak_id', 'required');
            $this->form_validation->set_rules('no_wa', 'field hp', 'required');

            if ($this->form_validation->run() != false) {
                $kontak_id = is_int(decrypt($this->input->post('kontak_id'))) ? decrypt($this->input->post('kontak_id')) : redirect(base_url() . 'admin/manage_kontak/', 'refresh');

                $hp = $this->input->post('no_wa');

                $dataUpdate = array(
                    'hp' => $hp,
                );

                $this->db->trans_begin();
                $this->Md_kontak->updateData($kontak_id, $dataUpdate);

                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Edit',
                    'status' => 1,
                    'keterangan' => $this->session->userdata('username') . ' Melakukan Edit Kontak ' . ', no_hp ' . $hp . ', kontak_id   ' . $kontak_id,
                    'IP' => $this->input->ip_address()
                );

                $this->Md_log->addLog($log);

                if ($this->db->trans_status() === true) {
                    $this->db->trans_commit();
                    echo json_encode(array('status' => 'success', 'message' => ''));
                    die;
                } else {
                    $this->db->trans_rollback();
                    echo json_encode(array('status' => 'gagal', 'message' => 'Data gagal disimpan'));
                    die;
                }
            } else {
                echo json_encode(array('status' => 'gagal', 'message' => 'Semua fill harus terisi'));
                die;
            }
        } else if ($param1 == 'delete') {
            $valid = $param2 == '' ? false : (is_int(decrypt($param2)) ? true : false);
            if (!$valid) {
                echo json_encode(array('data' => false));
                die;
            }
            $delete_id = decrypt($param2);
            $this->db->trans_begin();
            $this->Md_kontak->updateData($delete_id, array('status' => 2));
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Delete',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' id: ' . $delete_id,
                'IP' => $this->input->ip_address()
            );

            $this->Md_log->addLog($log);
            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();
                echo json_encode(array('data' => true));
                die;
            } else {
                $this->db->trans_rollback();
                echo json_encode(array('data' => false));
                die;
            }
        } else {
            $this->load->view('index', $page_data);
        }
    }

    public function manage_bebas_pustaka($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('login_type') != 'admin')
            $this->logout();
        $date = new DateTime();
        $id = $this->session->userdata('idsys');
        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Data Bebas Pustaka';
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'manage_bebas_pustaka';
        $page_data['page_now'] = 'Bebas Pustaka';

        if ($param1 == 'fetch') {
            $total = $this->Md_bebas_pustaka->countFiltered();
            $page = intval($this->input->post('datatable[pagination][page]'));
            if ($page < 1)
                $page = 1;
            $perpage = intval($this->input->post('datatable[pagination][perpage]'));
            $pages = intval($total / $perpage);
            $field = $this->input->post('datatable[sort][field]');
            if ($field == '')
                $field = $this->input->post('datatable[pagination][field]');
            $sort = $this->input->post('datatable[sort][sort]');
            if ($sort == '')
                $sort = $this->input->post('datatable[pagination][sort]');
            //mulai fetching data
            $data = array();
            $no = 0;
            $list = $this->Md_bebas_pustaka->getDatatables();

            //$prodi= $this->Md_vwprodi->getProdiAll();
            foreach ($list as $row) {
                $no++;
                $arr = array();
                $arr['id'] = encrypt($row->bebaspustaka_id);
                $arr['number'] = ($perpage * ($page - 1)) + $no;
                $arr['no_surat_bebaspustaka'] = $row->no_surat_bebaspustaka;
                $arr['no_surat_hibahbuku'] = $row->no_surat_hibahbuku;
                $arr['nama'] = $row->no_anggota . ' - ' . $row->nama;
                $arr['tgl_bebas_pustaka'] = $row->tgl_bebas_pustaka;
                $arr['author'] = $row->author;
                $arr['status'] = $row->status;
                $data[] = $arr;
            }
            $meta = array();
            $meta['page'] = $page;
            $meta['pages'] = $pages;
            $meta['perpage'] = $perpage;
            $meta['total'] = $total;
            $meta['sort'] = $sort;
            $meta['field'] = $field;
            $output = array(
                "meta" => $meta,
                "data" => $data
            );
            //output to json format
            echo json_encode($output);
        } else if ($param1 == 'add_page') {

            $page_data['page_name'] = 'add_bebas_pustaka';
            $page_data['page_now'] = 'Bebas Pustaka';
            $this->load->view('index', $page_data);
        } else if ($param1 == 'edit_page') {

            $bebaspustaka_id = decrypt($param2);

            $getdata = $this->Md_bebas_pustaka->getBebasPustakaByBebaspustakaId($bebaspustaka_id);

            if ($getdata) {
                $getdata->bebaspustaka_id = encrypt($getdata->bebaspustaka_id);
                $getdata->hibahbuku_id = encrypt($getdata->hibahbuku_id);
            }
            $page_data['page_name'] = 'edit_bebas_pustaka';
            $page_data['data'] = $getdata;
            $page_data['page_now'] = 'Bebas Pustaka';
            $this->load->view('index', $page_data);
        } else if ($param1 == 'serach_anggota') {
            $text = $this->input->get('searchtext') ? $this->input->get('searchtext') : null;

            $dtKaryawan = $this->Md_msmhs->serachmhs($text);

            $output = array();
            $output['total_data'] = count($dtKaryawan);
            foreach ($dtKaryawan as $list) {
                $row['id'] = $list->NIMHSMSMHS;
                $row['no_anggota'] = $list->NIMHSMSMHS;
                $row['nama'] = $list->NMMHSMSMHS;
                $output['items'][] = $row;
            }

            echo json_encode($output);
            die;
        } else if ($param1 == 'save') {
            $this->form_validation->set_rules('anggota_id', 'field anggota_id', 'required');
            $this->form_validation->set_rules('no_surat_bebas_pustaka', 'field no_surat_bebas_pustaka', 'required');
            //$this->form_validation->set_rules('no_surat_hibah_buku', 'field no_surat_hibah_buku', 'required');
            $this->form_validation->set_rules('tanggal', 'field tanggal', 'required');
            $this->form_validation->set_rules('tahun_akademik', 'field tahun_akademik', 'required');
            $this->form_validation->set_rules('nama_kepala_perpus', 'field nama_kepala_perpus', 'required');
            $this->form_validation->set_rules('nip_kepala_perpus', 'field nip_kepala_perpus', 'required');
            $this->form_validation->set_rules('nama_petugas_perpus', 'field nama_petugas_perpus', 'required');
            $this->form_validation->set_rules('nip_petugas_perpus', 'field nip_petugas_perpus', 'required');

            // $this->form_validation->set_rules('judul1', 'field judul1', 'required');
            // $this->form_validation->set_rules('pengarang1', 'field pengarang1', 'required');
            // $this->form_validation->set_rules('penerbit1', 'field penerbit1', 'required');
            // $this->form_validation->set_rules('tempat_terbit1', 'field tempat_terbit1', 'required');
            // $this->form_validation->set_rules('tahun_terbit1', 'field tahun_terbit1', 'required');
            // $this->form_validation->set_rules('isbn1', 'field isbn1', 'required');
            // $this->form_validation->set_rules('judul2', 'field judul2', 'required');
            // $this->form_validation->set_rules('pengarang2', 'field pengarang2', 'required');
            // $this->form_validation->set_rules('penerbit2', 'field penerbit2', 'required');
            // $this->form_validation->set_rules('tempat_terbit2', 'field tempat_terbit2', 'required');
            // $this->form_validation->set_rules('tahun_terbit2', 'field tahun_terbit2', 'required');
            // $this->form_validation->set_rules('isbn2', 'field isbn2', 'required');

            if ($this->form_validation->run() != false) {

                $anggota_id = $this->input->post('anggota_id');
                $no_surat_bebas_pustaka = $this->input->post('no_surat_bebas_pustaka');
                $no_surat_hibah_buku = $this->input->post('no_surat_hibah_buku') ? $this->input->post('no_surat_hibah_buku') : null;
                $tanggal = $this->input->post('tanggal');
                $tahun_akademik = $this->input->post('tahun_akademik');
                $nama_kepala_perpus = $this->input->post('nama_kepala_perpus');
                $nip_kepala_perpus = $this->input->post('nip_kepala_perpus');
                $nama_petugas_perpus = $this->input->post('nama_petugas_perpus');
                $nip_petugas_perpus = $this->input->post('nip_petugas_perpus');

                $judul1 = $this->input->post('judul1') ? $this->input->post('judul1') : null;
                $pengarang1 = $this->input->post('pengarang1') ? $this->input->post('pengarang1') : null;
                $penerbit1 = $this->input->post('penerbit1') ? $this->input->post('penerbit1') : null;
                $tempat_terbit1 = $this->input->post('tempat_terbit1') ? $this->input->post('tempat_terbit1') : null;
                $tahun_terbit1 = $this->input->post('tahun_terbit1') ? $this->input->post('tahun_terbit1') : null;
                $isbn1 = $this->input->post('isbn1') ? $this->input->post('isbn1') : null;

                $judul2 = $this->input->post('judul2') ? $this->input->post('judul2') : null;
                $pengarang2 = $this->input->post('pengarang2') ? $this->input->post('pengarang2') : null;
                $penerbit2 = $this->input->post('penerbit2') ? $this->input->post('penerbit2') : null;
                $tempat_terbit2 = $this->input->post('tempat_terbit2') ? $this->input->post('tempat_terbit2') : null;
                $tahun_terbit2 = $this->input->post('tahun_terbit2') ? $this->input->post('tahun_terbit2') : null;
                $isbn2 = $this->input->post('isbn2') ? $this->input->post('isbn2') : null;

                $dataInsert = array(
                    'no_anggota' => $anggota_id,
                    'no_surat' => $no_surat_bebas_pustaka,
                    'tahun_akademik' => $tahun_akademik,
                    'kepala_perpus' => $nama_kepala_perpus,
                    'nip_kepala_perpus' => $nip_kepala_perpus,
                    'petugas_perpus' => $nama_petugas_perpus,
                    'nip_petugas_perpus' => $nip_petugas_perpus,
                    'tgl_bebas_pustaka' => date('Y-m-d', strtotime($tanggal)),
                    'author' => $this->session->userdata('username'),
                    'tgl_post' => date("Y-m-d H:i:s"),
                    'status' => 1
                );

                $cekData = $this->Md_bebas_pustaka->getbebaspustakaByNoAnggota($anggota_id);
                if ($cekData) {
                    echo json_encode(array('status' => 'gagal', 'message' => 'Mahasiswa ini telah terdaftar di bebas pustaka'));
                    die;
                }
                $this->db->trans_begin();
                $bebaspustakaid = $this->Md_bebas_pustaka->addData($dataInsert);

                $dataInsert2 = array(
                    'no_anggota' => $anggota_id,
                    'no_surat' => $no_surat_hibah_buku,
                    'tahun_akademik' => $tahun_akademik,
                    'buku1_judul' => $judul1,
                    'buku1_pengarang' => $pengarang1,
                    'buku1_penerbit' => $penerbit1,
                    'buku1_tempat_terbit' => $tempat_terbit1,
                    'buku1_tahun_terbit' => $tahun_terbit1,
                    'buku1_isbn' => $isbn1,
                    'buku2_judul' => $judul2,
                    'buku2_pengarang' => $pengarang2,
                    'buku2_penerbit' => $penerbit2,
                    'buku2_tempat_terbit' => $tempat_terbit2,
                    'buku2_tahun_terbit' => $tahun_terbit2,
                    'buku2_isbn' => $isbn2,
                    'author' => $this->session->userdata('username'),
                    'bebaspustaka_id' => $bebaspustakaid,
                    'tgl_post' => date("Y-m-d H:i:s"),
                    'status' => 1
                );

                $hibahbuku_id = $this->Md_hibah_buku->addData($dataInsert2);

                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Add',
                    'status' => 1,
                    'keterangan' => $this->session->userdata('username') . ' Add Bebas Pustaka & Hibah Buku ' . ', bebaspustaka_id ' . $bebaspustakaid . ', hibahbuku_id   ' . $hibahbuku_id,
                    'IP' => $this->input->ip_address()
                );

                $this->Md_log->addLog($log);

                if ($this->db->trans_status() === true) {
                    $this->db->trans_commit();
                    echo json_encode(array('status' => 'success', 'message' => ''));
                    die;
                } else {
                    $this->db->trans_rollback();
                    echo json_encode(array('status' => 'gagal', 'message' => 'Data gagal disimpan'));
                    die;
                }
            } else {
                echo json_encode(array('status' => 'gagal', 'message' => 'Semua fill harus terisi'));
                die;
            }
        } else if ($param1 == 'update') {
            $this->form_validation->set_rules('bebaspustaka_id', 'field bebaspustaka_id', 'required');
            $this->form_validation->set_rules('hibahbuku_id', 'field hibahbuku_id', 'required');

            $this->form_validation->set_rules('anggota_id', 'field anggota_id', 'required');
            $this->form_validation->set_rules('no_surat_bebas_pustaka', 'field no_surat_bebas_pustaka', 'required');
            //$this->form_validation->set_rules('no_surat_hibah_buku', 'field no_surat_hibah_buku', 'required');
            $this->form_validation->set_rules('tanggal', 'field tanggal', 'required');
            $this->form_validation->set_rules('tahun_akademik', 'field tahun_akademik', 'required');
            $this->form_validation->set_rules('nama_kepala_perpus', 'field nama_kepala_perpus', 'required');
            $this->form_validation->set_rules('nip_kepala_perpus', 'field nip_kepala_perpus', 'required');
            $this->form_validation->set_rules('nama_petugas_perpus', 'field nama_petugas_perpus', 'required');
            $this->form_validation->set_rules('nip_petugas_perpus', 'field nip_petugas_perpus', 'required');

            // $this->form_validation->set_rules('judul1', 'field judul1', 'required');
            // $this->form_validation->set_rules('pengarang1', 'field pengarang1', 'required');
            // $this->form_validation->set_rules('penerbit1', 'field penerbit1', 'required');
            // $this->form_validation->set_rules('tempat_terbit1', 'field tempat_terbit1', 'required');
            // $this->form_validation->set_rules('tahun_terbit1', 'field tahun_terbit1', 'required');
            // $this->form_validation->set_rules('isbn1', 'field isbn1', 'required');
            // $this->form_validation->set_rules('judul2', 'field judul2', 'required');
            // $this->form_validation->set_rules('pengarang2', 'field pengarang2', 'required');
            // $this->form_validation->set_rules('penerbit2', 'field penerbit2', 'required');
            // $this->form_validation->set_rules('tempat_terbit2', 'field tempat_terbit2', 'required');
            // $this->form_validation->set_rules('tahun_terbit2', 'field tahun_terbit2', 'required');
            // $this->form_validation->set_rules('isbn2', 'field isbn2', 'required');

            if ($this->form_validation->run() != false) {

                $bebaspustaka_id = decrypt($this->input->post('bebaspustaka_id'));
                $hibahbuku_id = decrypt($this->input->post('hibahbuku_id'));
                $anggota_id = $this->input->post('anggota_id');
                $no_surat_bebas_pustaka = $this->input->post('no_surat_bebas_pustaka');
                $no_surat_hibah_buku = $this->input->post('no_surat_hibah_buku');
                $tanggal = $this->input->post('tanggal');
                $tahun_akademik = $this->input->post('tahun_akademik');
                $nama_kepala_perpus = $this->input->post('nama_kepala_perpus');
                $nip_kepala_perpus = $this->input->post('nip_kepala_perpus');
                $nama_petugas_perpus = $this->input->post('nama_petugas_perpus');
                $nip_petugas_perpus = $this->input->post('nip_petugas_perpus');

                $judul1 = $this->input->post('judul1') ? $this->input->post('judul1') : null;
                $pengarang1 = $this->input->post('pengarang1') ? $this->input->post('pengarang1') : null;
                $penerbit1 = $this->input->post('penerbit1') ? $this->input->post('penerbit1') : null;
                $tempat_terbit1 = $this->input->post('tempat_terbit1') ? $this->input->post('tempat_terbit1') : null;
                $tahun_terbit1 = $this->input->post('tahun_terbit1') ? $this->input->post('tahun_terbit1') : null;
                $isbn1 = $this->input->post('isbn1') ? $this->input->post('isbn1') : null;

                $judul2 = $this->input->post('judul2') ? $this->input->post('judul2') : null;
                $pengarang2 = $this->input->post('pengarang2') ? $this->input->post('pengarang2') : null;
                $penerbit2 = $this->input->post('penerbit2') ? $this->input->post('penerbit2') : null;
                $tempat_terbit2 = $this->input->post('tempat_terbit2') ? $this->input->post('tempat_terbit2') : null;
                $tahun_terbit2 = $this->input->post('tahun_terbit2') ? $this->input->post('tahun_terbit2') : null;
                $isbn2 = $this->input->post('isbn2') ? $this->input->post('isbn2') : null;

                $dataInsert = array(
                    'no_anggota' => $anggota_id,
                    'no_surat' => $no_surat_bebas_pustaka,
                    'tahun_akademik' => $tahun_akademik,
                    'kepala_perpus' => $nama_kepala_perpus,
                    'nip_kepala_perpus' => $nip_kepala_perpus,
                    'petugas_perpus' => $nama_petugas_perpus,
                    'nip_petugas_perpus' => $nip_petugas_perpus,
                    'tgl_bebas_pustaka' => date('Y-m-d', strtotime($tanggal)),
                    'author' => $this->session->userdata('username'),
                    'tgl_post' => date("Y-m-d H:i:s"),
                    'status' => 1
                );

                $cekData = $this->Md_bebas_pustaka->getbebaspustakaByNoAnggota($anggota_id, $bebaspustaka_id);
                if ($cekData) {
                    echo json_encode(array('status' => 'gagal', 'message' => 'Mahasiswa ini telah terdaftar di bebas pustaka'));
                    die;
                }

                $this->db->trans_begin();
                $this->Md_bebas_pustaka->updateData($bebaspustaka_id, $dataInsert);

                $dataInsert2 = array(
                    'no_anggota' => $anggota_id,
                    'no_surat' => $no_surat_hibah_buku,
                    'tahun_akademik' => $tahun_akademik,
                    'buku1_judul' => $judul1,
                    'buku1_pengarang' => $pengarang1,
                    'buku1_penerbit' => $penerbit1,
                    'buku1_tempat_terbit' => $tempat_terbit1,
                    'buku1_tahun_terbit' => $tahun_terbit1,
                    'buku1_isbn' => $isbn1,
                    'buku2_judul' => $judul2,
                    'buku2_pengarang' => $pengarang2,
                    'buku2_penerbit' => $penerbit2,
                    'buku2_tempat_terbit' => $tempat_terbit2,
                    'buku2_tahun_terbit' => $tahun_terbit2,
                    'buku2_isbn' => $isbn2,
                );

                $this->Md_hibah_buku->updateData($hibahbuku_id, $dataInsert2);

                $log = array(
                    'user_id' => $this->session->userdata('idsys'),
                    'jenis_log' => 'Admin',
                    'jenis_akses' => 'Update',
                    'status' => 1,
                    'keterangan' => $this->session->userdata('username') . ' Update Bebas Pustaka & Hibah Buku ' . ', bebaspustaka_id ' . $bebaspustaka_id . ', hibahbuku_id   ' . $hibahbuku_id,
                    'IP' => $this->input->ip_address()
                );

                $this->Md_log->addLog($log);

                if ($this->db->trans_status() === true) {
                    $this->db->trans_commit();
                    echo json_encode(array('status' => 'success', 'message' => ''));
                    die;
                } else {
                    $this->db->trans_rollback();
                    echo json_encode(array('status' => 'gagal', 'message' => 'Data gagal disimpan'));
                    die;
                }
            } else {
                echo json_encode(array('status' => 'gagal', 'message' => 'Semua fill harus terisi'));
                die;
            }
        } else if ($param1 == 'cetak') {
            $bebaspustaka_id = decrypt($param2);

            $getData = $this->Md_bebas_pustaka->getBebasPustakaByBebaspustakaId($bebaspustaka_id);

            $templateProcessor = new TemplateProcessor(base_url() . 'assets/template/CEKLIST_PERSYARATAN_BEBAS_PUSTAKA_2024.docx');

            $templateProcessor->setValues([
                'nomor_surat' => $getData->no_surat,
                'nim' => $getData->nis,
                'nama' => $getData->nama,
                'tgl_post' => formatTanggalIndonesia($getData->tgl_bebas_pustaka),
                'tanggal' => formatTanggalIndonesia($getData->tgl_bebas_pustaka),
                'prodi' => $getData->kelas,
                'tahun_akademik' => $getData->tahun_akademik,
                'author' => strtoupper($getData->nm_author),
                'kepala_perpus' => $getData->kepala_perpus,
                'nip_kepala_perpus' => $getData->nip_kepala_perpus,
                'petugas_perpus' => $getData->petugas_perpus,
                'nip_petugas_perpus' => $getData->nip_petugas_perpus,
                'buku1_judul' => $getData->buku1_judul,
                'buku1_pengarang' => $getData->buku1_pengarang,
                'buku1_penerbit' => $getData->buku1_penerbit,
                'buku1_tempat_terbit' => $getData->buku1_tempat_terbit,
                'buku1_tahun_terbit' => $getData->buku1_tahun_terbit,
                'buku1_isbn' => $getData->buku1_isbn,
                'buku2_judul' => $getData->buku2_judul,
                'buku2_pengarang' => $getData->buku2_pengarang,
                'buku2_penerbit' => $getData->buku2_penerbit,
                'buku2_tempat_terbit' => $getData->buku2_tempat_terbit,
                'buku2_tahun_terbit' => $getData->buku2_tahun_terbit,
                'buku2_isbn' => $getData->buku2_isbn,
            ]);

            header("Content-Disposition: attachment; filename=Surat Keterangan Bebas Pustaka dan Hibah Buku  " . $getData->nis . ' - ' . $getData->nama . ".docx");
            ob_clean();

            $templateProcessor->saveAs('php://output');
            die;
        } else if ($param1 == 'delete') {
            $valid = $param2 == '' ? false : (is_int(decrypt($param2)) ? true : false);
            if (!$valid) {
                echo json_encode(array('data' => false));
                die;
            }
            $delete_id = decrypt($param2);
            $getdata = $this->Md_bebas_pustaka->getBebasPustakaByBebaspustakaId($delete_id);

            $this->db->trans_begin();
            $this->Md_bebas_pustaka->updateData($delete_id, array('status' => 2));
            $this->Md_hibah_buku->updateData($getdata->hibahbuku_id, array('status' => 2));
            $log = array(
                'user_id' => $this->session->userdata('idsys'),
                'jenis_log' => 'Admin',
                'jenis_akses' => 'Delete',
                'status' => 1,
                'keterangan' => $this->session->userdata('username') . ' Delete Bebas Pustaka & Hibah Buku id: ' . $delete_id,
                'IP' => $this->input->ip_address()
            );

            $this->Md_log->addLog($log);
            if ($this->db->trans_status() === true) {
                $this->db->trans_commit();
                echo json_encode(array('data' => true));
                die;
            } else {
                $this->db->trans_rollback();
                echo json_encode(array('data' => false));
                die;
            }
        } else {
            $this->load->view('index', $page_data);
        }
    }

    public function manage_chat($param1 = "", $param2 = "")
    {
        if ($this->session->userdata('login_type') != 'admin') {
            $this->logout();
        }

        // Identitas halaman untuk view 'index'
        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'Live Chat';
        $page_data['page_title'] = 'Live Chat Pelanggan';
        $page_data['page_name'] = 'manage_chat';

        // 1. AJAX: Ambil Daftar User (Sidebar)
        if ($param1 == 'list_users_ajax') {

            $list = $this->Md_chat->list_sesi_aktif();
            ob_clean(); // Hapus buffer output sebelumnya
            header('Content-Type: application/json');
            echo json_encode($list);
            exit; // WAJIB exit agar view index tidak dipanggil
        }

        // 2. AJAX: Ambil Pesan Chat (Jendela Chat)
        else if ($param1 == 'load_chat') {
            $chatsesi_id = $param2;
            $admin_username = $this->session->userdata('username');

            // PERBAIKAN: Hanya update pesan yang 'sudah_dibaca' nya masih 0
            $this->db->where('chatsesi_id', $chatsesi_id);
            $this->db->where('pengirim', 'user');
            $this->db->where('sudah_dibaca', 0); // KUNCI: Agar tidak menimpa data lama
            $this->db->update('chat_pesan', [
                'sudah_dibaca' => 1,
                'readby'       => $admin_username, // Sesuaikan dengan field tooltip di view
                'tgl_read'     => date('Y-m-d H:i:s')
            ]);

            $pesan = $this->Md_chat->ambil_pesan($chatsesi_id);
            ob_clean();
            header('Content-Type: application/json');
            echo json_encode($pesan);
            exit;
        }

        // 3. AJAX: Simpan Balasan Admin
        // 3. AJAX: Simpan Balasan Admin
        else if ($param1 == 'reply') {
            $is_file = 0;
            $message = $this->input->post('message');

            // Cek apakah ada file yang dikirim admin
            if (!empty($_FILES['file_reply']['name'])) {
                $config['upload_path']   = './assets/media/lampiran_chat/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|zip';
                $config['file_name']     = 'admin_' . time() . '_' . uniqid();
                $config['max_size']      = 5000; // 5MB

                $this->load->library('upload');
                $this->upload->initialize($config); // Gunakan initialize agar config terbaru diterapkan

                if ($this->upload->do_upload('file_reply')) {
                    $uploadData = $this->upload->data();
                    $message    = $uploadData['file_name'];
                    $is_file    = 1;
                } else {
                    ob_clean();
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'error', 'message' => $this->upload->display_errors('', '')]);
                    exit;
                }
            }

            if (empty($message)) exit; // Jangan simpan jika kosong

            $data = [
                'chatsesi_id'  => $this->input->post('chatsesi_id'),
                'pengirim'     => 'admin',
                'isi_pesan'    => $message,
                'is_file'      => $is_file,
                'sudah_dibaca' => 1,
                'sendby'       => $this->session->userdata('username'),
                'tgl_read'     => date('Y-m-d H:i:s')
            ];
            $this->Md_chat->simpan_pesan($data);

            echo json_encode(['status' => 'success']);
            exit;
        }

        //
        else if ($param1 == 'finish_session') {
            $chatsesi_id = $this->input->post('chatsesi_id');

            $data_update = [
                'status_sesi'        => 'tidak aktif',
                'tgl_akhir'          => date('Y-m-d H:i:s'),
                'diakhiri_oleh'      => 'admin',
                'diakhiri_idsysuser' => $this->session->userdata('username')
            ];

            $this->Md_chat->update_sesi($chatsesi_id, $data_update);

            // Kirim log aktivitas
            $log = array(
                'user_id'     => $this->session->userdata('idsys'),
                'jenis_log'   => 'Admin',
                'jenis_akses' => 'Finish Chat Session',
                'status'      => 1,
                'keterangan'  => $this->session->userdata('username') . ' Mengakhiri Sesi Chat ID ' . $chatsesi_id,
                'IP'          => $this->input->ip_address()
            );
            $this->Md_log->addLog($log);

            ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success']);
            exit;
        } else if ($param1 == 'unread_count_ajax') {
            // Ambil total unread dari model
            $total = $this->Md_chat->get_total_unread_global();

            ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['total' => $total]);
            exit;
        }

        // 4. LOAD HALAMAN BIASA (Bukan AJAX)
        else {

            $page_data['daftar_sesi'] = $this->Md_chat->list_sesi_aktif();
            $this->load->view('index', $page_data);
        }
    }

    public function manage_resensi($param1 = "", $param2 = "")
    {
        if ($this->session->userdata('login_type') != 'admin') $this->logout();

        $page_data['page_access'] = "admin";
        $page_data['page_now']    = 'resensi';
        $page_data['page_title']  = 'Resensi Buku';
        $page_data['page_name']   = 'manage_resensi';

        // --- 1. AJAX: FETCH DATA (SERVER SIDE) ---
        if ($param1 == 'fetch') {
            $datatable = $this->input->post('datatable');
            $search    = isset($datatable['query']['generalSearch']) ? $datatable['query']['generalSearch'] : '';
            $page      = isset($datatable['pagination']['page']) ? (int)$datatable['pagination']['page'] : 1;
            $perpage   = isset($datatable['pagination']['perpage']) ? (int)$datatable['pagination']['perpage'] : 10;
            $sort      = isset($datatable['sort']['sort']) ? $datatable['sort']['sort'] : 'DESC';
            $field     = isset($datatable['sort']['field']) ? $datatable['sort']['field'] : 'tgl_resensi';

            // Mapping sort field (untuk manual, sorting mungkin perlu penyesuaian di Model)
            $map_field = [
                'tgl'    => 'r.tgl_resensi',
                'judul'  => 'b.judul',
                'author' => 'r.author'
            ];
            $sort_field = isset($map_field[$field]) ? $map_field[$field] : 'r.tgl_resensi';
            $offset     = ($page - 1) * $perpage;

            $list  = $this->Md_resensi->get_resensi_server_side($search, $perpage, $offset, $sort_field, $sort);
            $total = $this->Md_resensi->count_filtered($search);

            $data = array();
            $no = $offset;
             foreach ($list as $row) {
                $no++;

                // LOGIKA UTAMA: Cek apakah buku dari DB atau Manual
                if ($row->buku_id > 0) {
                    // Ambil dari hasil JOIN tabel buku
                    $display_judul = (!empty($row->judul)) ? $row->judul : "Judul tidak ditemukan";
                    $display_isbn  = (!empty($row->ISBN)) ? $row->ISBN : "-";
                    $label_tipe    = '<span class="m-badge m-badge--info m-badge--wide">Internal</span>';
                } else {
                    // Ambil dari kolom manual di tabel resensi
                    $display_judul = (!empty($row->judul_buku)) ? $row->judul_buku : "Judul Manual Kosong";
                    $display_isbn  = (!empty($row->isbn)) ? $row->isbn : "-";
                    $label_tipe    = '<span class="m-badge m-badge--brand m-badge--wide">Manual</span>';
                }
		
		$clean_review = $row->isi_resensi;
                // Hilangkan tag HTML
                $clean_review = strip_tags($clean_review);
                // Decode entity HTML (&nbsp; jadi spasi, dll)
                $clean_review = html_entity_decode($clean_review, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                // Pastikan encoding UTF-8 valid
                $clean_review = mb_convert_encoding($clean_review, 'UTF-8', 'UTF-8');
                // Ganti NBSP dan tab dengan spasi biasa
                $clean_review = str_replace(["\xC2\xA0", "\t"], ' ', $clean_review);
                // Hilangkan karakter non-printable
                $clean_review = preg_replace('/[\x00-\x1F\x7F]/u', '', $clean_review);
                // Ambil hanya karakter yang diizinkan: a-z, A-Z, 0-9, spasi, ?,!,.
                $clean_review = preg_replace('/[^a-zA-Z0-9\s\?\!\.]/u', '', $clean_review);
                // Rapikan spasi berlebih
                $clean_review = preg_replace('/\s+/', ' ', $clean_review);
                // Hilangkan spasi/tab di awal dan akhir
                $clean_review = trim($clean_review);
                // Potong 100 karakter
                $clean_review = substr($clean_review, 0, 100) . '...';

                $data[] = [
                    'number' => $no,
                    'id'     => encrypt($row->resensi_id),
                   'judul' => sprintf(
                        '<div class="book-info">
                            <strong>%s</strong><br>
                            <small>ISBN: %s</small><br>
                            %s
                        </div>', $display_judul, $display_isbn, $label_tipe),
                    'review' => $clean_review,
                    'tgl'    => date('d/m/Y H:i', strtotime($row->tgl_resensi)),
                    'author' => $row->author
                ];
            }

            $result = [
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

            ob_clean();
            header('Content-Type: application/json');
            echo json_encode($result);
            exit;
        }

        // --- 2. AJAX: SEARCH BUKU ---
        else if ($param1 == 'search_buku_ajax') {
            $text = $this->input->get('searchtext');
            $res = $this->Md_resensi->search_buku($text);
            $final = [];
            foreach ($res as $r) {
                $final[] = ['id' => $r['id'], 'text' => '[' . $r['ISBN'] . '] ' . $r['text']];
            }
            echo json_encode(['items' => $final]);
            exit;
        }

        // --- 3. SAVE DATA ---
        else if ($param1 == 'save') {
            $resensi_id   = $this->input->post('resensi_id');
            $metode_input = $this->input->post('metode_input');

            // Data dasar (HAPUS 'jenis_buku' dari sini karena akan di-set di bawah)
            $data = [
                'isi_resensi'  => $this->input->post('isi_resensi'),
                'tgl_resensi'  => date('Y-m-d H:i:s'),
                'author'       => $this->session->userdata('username'),
                'status'       => 1,
                'idkategori'   => $this->input->post('idkategori'),
                'isbn'         => $this->input->post('isbn'),
                'tajuksubyek'  => $this->input->post('tajuksubyek'),
                'penyadur'     => $this->input->post('penyadur'),
                'edisi'        => $this->input->post('edisi'),
                'cetakan'      => $this->input->post('cetakan'),
                'thn_terbit'   => $this->input->post('thn_terbit'),
                'jml_hal'      => $this->input->post('jml_hal'),
                'kd_penerbit'  => $this->input->post('kd_penerbit'),
            ];

            // LOGIKA PENENTUAN JENIS BUKU (INTERNAL / EKSTERNAL)
            if ($metode_input == 'pilih') {
                // ... (Kode Internal Tetap Sama) ...
                $data['jenis_buku'] = 'Internal';
                $data['buku_id']    = $this->input->post('buku_id');
                $data['judul_buku'] = null;
                $data['penulis']    = null;
                $data['cover']      = null;
                $data['idkategori']      = null;
                $data['thn_terbit']      = null;
                $data['jml_hal']      = null;
                $data['kd_penerbit']      = null;
            } else {
                // Kalo Input Manual = EKSTERNAL
                $data['jenis_buku'] = 'Eksternal';
                $data['buku_id']    = null;
                $data['judul_buku'] = $this->input->post('judul_manual');
                $data['penulis']    = $this->input->post('penulis_manual');

                // --- PROSES UPLOAD COVER + COMPRESS PHP NATIVE ---
                if (!empty($_FILES['cover_manual']['name'])) {

                    $upload_path = FCPATH . 'assets/media/cover_buku_manual/';

                    if (!is_dir($upload_path)) {
                        mkdir($upload_path, 0777, true);
                    }

                    $config['upload_path']   = $upload_path;
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['max_size']      = 5120; // 5MB
                    $config['encrypt_name']  = TRUE;

                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('cover_manual')) {
                        $uploadData = $this->upload->data();
                        $full_path  = $uploadData['full_path'];
                        $file_type  = $uploadData['file_type']; // image/jpeg atau image/png

                        // --- LOGIKA KOMPRESI NATIVE ---
                        // Kita naikkan memory limit sebentar jaga-jaga gambarnya resolusi raksasa
                        ini_set('memory_limit', '256M');

                        // 1. KOMPRESI JPG/JPEG
                        if ($file_type == 'image/jpeg' || $file_type == 'image/jpg') {
                            $image = imagecreatefromjpeg($full_path);
                            // Simpan ulang (overwrite) dengan Quality 50 (Skala 0-100)
                            // Semakin kecil angka, semakin burik tapi size kecil
                            imagejpeg($image, $full_path, 50);
                            imagedestroy($image);
                        }
                        // 2. KOMPRESI PNG
                        elseif ($file_type == 'image/png') {
                            $image = imagecreatefrompng($full_path);
                            // Pertahankan transparansi
                            imagealphablending($image, false);
                            imagesavealpha($image, true);
                            // PNG Quality pakai range 0-9 (0=no compress, 9=max compress)
                            // PNG itu lossless, jadi size tidak akan turun drastis seperti JPG
                            imagepng($image, $full_path, 9);
                            imagedestroy($image);
                        }
                        // --------------------------------------------

                        $data['cover'] = $uploadData['file_name'];

                        // Hapus file lama jika update
                        $cover_lama = $this->input->post('cover_lama');
                        if ($resensi_id && !empty($cover_lama)) {
                            $path_lama = $upload_path . $cover_lama;
                            if (file_exists($path_lama)) {
                                unlink($path_lama);
                            }
                        }
                    } else {
                        // Error Upload
                        $error_msg = $this->upload->display_errors('', '');
                        echo json_encode(['status' => 'error', 'message' => 'Gagal Upload: ' . $error_msg]);
                        exit;
                    }
                }
            }

            // ... (Kode Simpan ke DB tetap sama) ...
            if ($resensi_id) {
                $this->Md_resensi->update_resensi(decrypt($resensi_id), $data);
                $message = 'Resensi berhasil diperbarui';
            } else {
                $this->Md_resensi->add_resensi($data);
                $message = 'Resensi berhasil disimpan';
            }

            echo json_encode(['status' => 'success', 'message' => $message]);
            exit;
        }

        // --- 4. DELETE ---
        else if ($param1 == 'delete') {
            $this->Md_resensi->update_resensi(decrypt($param2), ['status' => 2]);
            echo json_encode(['data' => true]);
            exit;
        }

        // --- 5. PAGES ---
        else if ($param1 == 'add_page' || $param1 == 'edit_page') {
            $page_data['daftar_kategori'] = $this->Md_siperpus_kategori->getKategoriAll();
            $page_data['daftar_penerbit'] = $this->Md_siperpus_penerbit->getPenerbitAll();

            if ($param1 == 'edit_page') {
                $page_data['edit_data'] = $this->Md_resensi->get_resensi_by_id(decrypt($param2));
                // var_dump($page_data['edit_data']);
                // die;
            }
            $page_data['page_name'] = 'resensi_form';
            $this->load->view('index', $page_data);
        } else {
            $this->load->view('index', $page_data);
        }
    }
    
}
