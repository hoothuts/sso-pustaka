<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('pagination');
        $this->load->library('session');
        $this->load->helper('menu_generator');
        $this->load->helper('pkrlib_helper');
        $this->load->helper('encryption_id_helper');

        $this->load->model('Md_siperpus_sysuser');
        $this->load->model('Md_siperpus_sysgroup');
        $this->load->model('Md_siperpus_sysmodul');
        $this->load->model('Md_siperpus_presensi');
        $this->load->model('Md_siperpus_buku_digital');
        $this->load->model('Md_siperpus_buku');
        $this->load->model('Md_siperpus_buku_file');
        $this->load->model('Md_siperpus_buku_prodi');
        $this->load->model('Md_siperpus_inventaris');
        $this->load->model('Md_siperpus_penerbit');
        $this->load->model('Md_siperpus_sysgrant');
        $this->load->model('Md_siperpus_kategori_buku');
        $this->load->model('Md_siperpus_transaksi');
        $this->load->model('Md_siperpus_hilangrusak');
        $this->load->model('Md_siperpus_manage_halaman');
        $this->load->model('Md_siperpus_anggota_luar');
        $this->load->model('Md_vwanggota');
        $this->load->model('Md_vwsiswa');
        $this->load->model('Md_vwdosen');
        $this->load->model('Md_log');
        $this->load->model('Md_mediasosial');
        $this->load->model('Md_kontak');
        $this->load->model('Md_pengunjung');
        $this->load->model('Md_jenis_artikel');
        $this->load->model('Md_chat');
        $this->load->helper('pkrlib_helper');
        $this->load->model('Md_resensi');
        $this->load->model('Md_buku_baru');
        $this->load->model('Md_konfigurasi_web');
        $this->load->model('Md_pegawai');

        $this->load->model('Md_media');
        $this->load->model('Md_slide');
        $this->load->model('Md_artikel');
        
        $this->config->load('recaptcha');
        date_default_timezone_set('Asia/Jakarta');
        /* cash control */
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        readvisitor();
    }

    public function index()
    {
        
        // ambil konfigurasi via model
        $cfg_buku = $this->Md_konfigurasi_web->get_by_jenis('Buku paling banyak dipinjam');
        $cfg_mhs = $this->Md_konfigurasi_web->get_by_jenis('Mahasiswa paling banyak meminjam');
        $cfg_pgj = $this->Md_konfigurasi_web->get_by_jenis('Mahasiswa paling banyak berkunjung');

        // default fallback (antisipasi null)
        $buku_terbanyak = [];
        $mahasiswa_terbanyak = [];
        $pengunjung_terakitf = [];

        if ($cfg_buku && $cfg_buku->tgl_awal && $cfg_buku->tgl_akhir && $cfg_buku->jumlah_data) {
            $buku_terbanyak = $this->Md_siperpus_transaksi->get_buku_terbanyak($cfg_buku->tgl_awal, $cfg_buku->tgl_akhir, $cfg_buku->jumlah_data);
        }else{
            $buku_terbanyak = $this->Md_siperpus_transaksi->get_buku_terbanyak();
        }

        if ($cfg_mhs && $cfg_mhs->tgl_awal && $cfg_mhs->tgl_akhir && $cfg_mhs->jumlah_data) {
            $mahasiswa_terbanyak = $this->Md_siperpus_transaksi->get_mahasiswa_terbanyak($cfg_mhs->tgl_awal, $cfg_mhs->tgl_akhir, $cfg_mhs->jumlah_data);
        }else{
            $mahasiswa_terbanyak = $this->Md_siperpus_transaksi->get_mahasiswa_terbanyak();
        }
        
        if ($cfg_pgj && $cfg_pgj->tgl_awal && $cfg_pgj->tgl_akhir && $cfg_pgj->jumlah_data) {
            $pengunjung_terakitf = $this->Md_siperpus_presensi->getPengunjungTeraktifByPeriode($cfg_pgj->tgl_awal, $cfg_pgj->tgl_akhir, $cfg_pgj->jumlah_data);
        }else{
            $pengunjung_terakitf = $this->Md_siperpus_presensi->getPengunjungTeraktifByPeriode();
        }

        // kirim ke view
        // ================= POPUP =================
        $page_data['popup'] = $this->Md_media->get_active(5);
    
        $page_data['buku_terbanyak'] = $buku_terbanyak;
        $page_data['mahasiswa_terbanyak'] = $mahasiswa_terbanyak;
        $page_data['pengunjung_terakitf'] = $pengunjung_terakitf;

        
        $page_data['page_content'] = 'dashboard';
        $page_data['page_name'] = 'dashboard';
        $page_data['jml'] = $this->Md_siperpus_buku->getJumlahBuku();
        $page_data['eks'] = $this->Md_siperpus_inventaris->getJumlahEks();
        $page_data['pnj'] = $this->Md_siperpus_transaksi->getJumlahTran();
        $page_data['ang'] = $this->Md_vwanggota->getJumlahAng();
        $page_data['visit'] = $this->Md_vwanggota->getJumlahVisit();
        $page_data['slide'] = $this->Md_slide->getSlide();
        $page_data['artikel'] = $this->Md_artikel->getNewArtikel();
        $page_data['pengumuman'] = $this->Md_artikel->getAllArtikelByJenis('Pengumuman');
        $page_data['buku_baru'] = $this->Md_buku_baru->get_buku_baru_front(10);

        $page_data['mediasosial'] = $this->Md_mediasosial->getAllMediasosial();
        $this->load->view('front', $page_data);
    }

    function bukutamu($param1 = '')
    {
        $page_data['page_content'] = 'bukutamu';
        $page_data['page_name'] = 'bukutamu';
        $page_data['mediasosial'] = $this->Md_mediasosial->getAllMediasosial();
        $kunjungan = $this->Md_siperpus_presensi->getPresensiPerHari(10);

        $pre_hari = [];
        $pre_val = [];

        foreach ($kunjungan as $k) {
            $pre_hari[] = '"' . $k->tanggal . '"';
            $pre_val[] = $k->jumlah;
        }

        $page_data['pre_hari'] = implode(',', $pre_hari);
        $page_data['pre_val'] = implode(',', $pre_val);
        if ($param1 == 'submit') {
            $exist = false;
            $nomor = $this->input->post('nomor');
            if (strlen($nomor) > 0) {
                $nama = '';
                if ($dt = $this->Md_pegawai->get_by_nip($nomor)) {
                    $exist = true;
                    $nama = $dt->nama;
                }
                if (!$exist)
                    if ($dt = $this->Md_vwsiswa->getSiswaById($nomor)) {
                        if($dt[0]->status_siswa!='L'){
                            $exist = true;
                            $nama = $dt[0]->nama;
                        }else{
                            $this->session->set_flashdata('alert', 'alert-danger');
                            $this->session->set_flashdata('flash_message', 'Anggota sudah Lulus, silahkan daftar menjadi Anggota Luar!');
                            redirect(base_url() . 'home/bukutamu', 'refresh');
                        }
                    }
                if (!$exist)
                    if ($dt = $this->Md_siperpus_anggota_luar->getKartuAnggotaById($nomor)) {
                        $exist = true;
                        $nama = $dt[0]['nama'];
                    }
                $data['tanggal'] = date("Y-m-d H:i:s");
                if (!$exist || $nomor == '') {
                    $this->session->set_flashdata('alert', 'alert-danger');
                    $this->session->set_flashdata('flash_message', 'Nomor Anggota Tidak ditemukan');
                    redirect(base_url() . 'home/bukutamu', 'refresh');
                } else {
                    $data['nis'] = $nomor;
                    $this->Md_siperpus_presensi->addPresensi($data);
                    $this->session->set_flashdata('alert', 'alert-success');
                    $this->session->set_flashdata('flash_message', 'Terima Kasih dan Selamat Datang ' . $nama);
                    redirect(base_url() . 'home/bukutamu', 'refresh');
                }
            } else {
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Nomor Anggota Tidak ditemukan');
                redirect(base_url() . 'home/bukutamu', 'refresh');
            }
        } else {
            $this->load->view('front', $page_data);
        }
    }

    public function resensi()
    {
        $this->load->library('pagination');

        $page_data['page_access']  = "home";
        $page_data['page_name']    = 'resensi';
        $page_data['page_content'] = 'resensi';

        // 1. Tangkap Kata Kunci Pencarian
        $search_query = $this->input->get('q'); // Menggunakan GET agar URL bisa dicopy-paste
        $search_query = ($search_query) ? $search_query : '';

        // 2. Konfigurasi Pagination
        $config['base_url'] = base_url('home/resensi');
        // Masukkan search query ke hitung total
        $config['total_rows'] = $this->Md_resensi->get_total_resensi($search_query);
        $config['per_page'] = 5;
        $config['uri_segment'] = 3;

        // PENTING: Agar saat klik halaman 2, pencarian tidak hilang
        $config['reuse_query_string'] = TRUE;

        // Styling Pagination (Sama seperti sebelumnya)
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Prev';

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // 3. Ambil Data dengan Search Query
        $page_data['resensi_list'] = $this->Md_resensi->get_resensi_paginated($config['per_page'], $page, $search_query);
        $page_data['links'] = $this->pagination->create_links();

        // Kirim balik kata kunci ke view untuk ditampilkan di input box
        $page_data['search_keyword'] = $search_query;

        $page_data['mediasosial'] = $this->Md_mediasosial->getAllMediasosial();
        $this->load->view('front', $page_data);
    }


    public function detail_resensi($id_encrypted)
    {
        $id_resensi = decrypt($id_encrypted); // Decrypt ID

        $page_data['page_access']  = "home";
        $page_data['page_name']    = 'resensi';
        $page_data['page_content'] = 'detail_resensi'; // View detail

        $data_resensi = $this->Md_resensi->get_detail_resensi($id_resensi);

        // Cek jika data tidak ditemukan
        if (!$data_resensi) {
            redirect('home/resensi');
        }
        //ambil data rak dan inv buku
        $inv=array();
        if($data_resensi->judul_db != ''){
            $inv=$this->Md_siperpus_inventaris->getInventarisByISBNdanNoKlas($data_resensi->isbn_db, $data_resensi->no_klas_db);
        }
        
        $page_data['inv'] = $inv;
        $page_data['row'] = $data_resensi;
        $page_data['mediasosial'] = $this->Md_mediasosial->getAllMediasosial();

        $this->load->view('front', $page_data);
    }
    function penelusuran_buku($param1 = '', $param2 = '', $param3 = '', $param4 = '')
    {
        $this->load->library('pagination');
        $msc = microtime(true);
        $page_data['page_access'] = "home";
        $page_data['page_name'] = 'penelusuran_buku';
        $page_data['page_content'] = 'pbuku';
        $page_data['mediasosial'] = $this->Md_mediasosial->getAllMediasosial();
        $page_data['kategori'] = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
        $config = array();

        if ($param1 == '_search') {
            if ($this->session->userdata('judul') != '' || $this->session->userdata('penulis') != '' || $this->session->userdata('seri') != '' || $this->session->userdata('isbn') != '' || $this->session->userdata('kategori') != '') {
                $this->session->unset_userdata('judul');
                $this->session->unset_userdata('penulis');
                $this->session->unset_userdata('seri');
                $this->session->unset_userdata('isbn');
                $this->session->unset_userdata('kategori');
            } // Cecking set Session

            if ($this->input->post('search')) {
                $page_data['search'] = $this->input->post('search');
                $this->session->set_userdata('cari', $page_data['search']);
            } else {
                $page_data['search'] = $this->session->userdata('cari');
            }
            $config['base_url'] = base_url() . 'home/penelusuran_buku/_search/';
            $page = $param2;
        } else if ($param1 == '_advance_search') {
            if ($this->session->userdata('cari') != '') {
                $this->session->unset_userdata('cari');
            } // Cecking set Session
            $dataAdv = array(
                'judul' => $this->input->post('judul'),
                'penulis' => $this->input->post('penulis'),
                'seri' => $this->input->post('seri'),
                'isbn' => $this->input->post('isbn'),
                'kategori' => $this->input->post('kategori')
            );
            if ($this->input->post('judul') != '' || $this->input->post('penulis') != '' || $this->input->post('seri') != '' || $this->input->post('isbn') != '' || $this->input->post('kategori') != '') {
                $this->session->set_userdata($dataAdv);
                $page_data['search'] = '';
            } else {
                $page_data['search'] = '';
            }
            $config['base_url'] = base_url() . 'home/penelusuran_buku/_advance_search/';
            $page = $param2;
        } else {
            $this->session->unset_userdata(array('cari', 'judul', 'penulis', 'seri', 'isbn', 'kategori'));
            $page_data['search'] = '';
            $config['base_url'] = base_url() . 'home/penelusuran_buku/';
            $page = $param1;
        }
        $config["per_page"] = 12;
        $total_row = $this->Md_siperpus_buku->record_count($page_data['search'], $config["per_page"], $page);
        $config["total_rows"] = $total_row;
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = 9;
        $config["uri_segment"] = 3;
        $config["cur_page"] = $page;
        $config['reuse_query_string'] = TRUE;
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

        $page_data["results"] = $this->Md_siperpus_buku->fetch_data($page_data['search'], $config["per_page"], $page);
        $msc = microtime(true) - $msc;
        $page_data["links"] = $this->pagination->create_links();
        $page_data["num_row"] = $total_row;
        $page_data["extime"] = $msc;
        $page_data["keyword"] = $page_data['search'];
        $num = 0;
        if ($this->input->post('judul') != '') {
            $page_data["keyword"] = $page_data["keyword"] . ' Judul: ' . $this->input->post('judul');
            $num++;
        }
        if ($this->input->post('penulis') != '') {
            if ($num > 0)
                $page_data["keyword"] = $page_data["keyword"] . ',';
            $page_data["keyword"] = $page_data["keyword"] . ' Penulis: ' . $this->input->post('penulis');
            $num++;
        }
        if ($this->input->post('seri') != '') {
            if ($num > 0)
                $page_data["keyword"] = $page_data["keyword"] . ',';
            $page_data["keyword"] = $page_data["keyword"] . ' Seri: ' . $this->input->post('seri');
            $num++;
        }
        if ($this->input->post('isbn') != '') {
            if ($num > 0)
                $page_data["keyword"] = $page_data["keyword"] . ',';
            $page_data["keyword"] = $page_data["keyword"] . ' ISBN: ' . $this->input->post('isbn');
            $num++;
        }
        if ($this->input->post('kategori') != '') {
            $idkat = $this->input->post('kategori');
            if ($idkat != 'all') {
                if ($num > 0)
                    $page_data["keyword"] = $page_data["keyword"] . ',';
                $kat = $this->Md_siperpus_kategori_buku->getKategoriById($idkat);
                if ($kat)
                    $page_data["keyword"] = $page_data["keyword"] . ' Kategori: ' . $kat[0]->nmkategori;
                $num++;
            }
        }
        $this->load->view('front', $page_data);
    }

    /*
     function penelusuran_buku($param1='')
     {
     	$this->load->library('pagination');
     	$msc = microtime(true);
    
     	$page_data['page_access'] = "home";
     	$page_data['page_name'] = 'penelusuran_buku';
     	$page_data['page_content']='pbuku';
     	$page_data['kategori']=$this->Md_siperpus_kategori_buku->getKategoriBukuAll();
     	$config = array();
     	if($param1 !='' && $this->uri->segment(4)){
     		$page_data['search']=$param1;
     		$uri=4;
     		$config['base_url'] = base_url().'home/penelusuran_buku/'.$page_data['search'].'/';
     	}else{
     		$page_data['search']='';
     		$uri=3;
     		$config['base_url'] = base_url().'home/penelusuran_buku/';
     	}
    	if($this->input->post('search')){
     		$page_data['search']=$this->input->post('search');
     		$config['base_url'] = base_url().'home/penelusuran_buku/'.$page_data['search'].'/';
     	}
    
     	if($this->uri->segment($uri)){
     		$page = ($this->uri->segment($uri)) ;
     	}
     	else{
     		$page = 1;
     	}
     	$config["per_page"] = 12;
    
     	$total_row = $this->Md_siperpus_buku->record_count($page_data['search'],$config["per_page"], $page);
    
     	$config["total_rows"] = $total_row;
     	$config['use_page_numbers'] = TRUE;
     	$config['num_links'] = 9;
     	$config["uri_segment"] = 3;
     	$config["cur_page"] = $page;
     	$config['reuse_query_string'] = TRUE;
    
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
     	$page_data["results"] = $this->Md_siperpus_buku->fetch_data($page_data['search'],$config["per_page"], $page);
     	$msc = microtime(true) - $msc;
     	$page_data["links"] = $this->pagination->create_links();
     	$page_data["num_row"] = $total_row;
     	$page_data["extime"] = $msc;
     	$page_data["keyword"] = $page_data['search'];
     	$num=0;
     	if($this->input->post('judul')!=''){
     		$page_data["keyword"]=$page_data["keyword"].' Judul: '.$this->input->post('judul');
     		$num++;
     	}
     	if($this->input->post('penulis')!=''){
     		if($num>0)$page_data["keyword"]=$page_data["keyword"].',';
     		$page_data["keyword"]=$page_data["keyword"].' Penulis: '.$this->input->post('penulis');
     		$num++;
     	}
     	if($this->input->post('seri')!=''){
     		if($num>0)$page_data["keyword"]=$page_data["keyword"].',';
     		$page_data["keyword"]=$page_data["keyword"].' Seri: '.$this->input->post('seri');
     		$num++;
     	}
     	if($this->input->post('isbn')!=''){
     		if($num>0)$page_data["keyword"]=$page_data["keyword"].',';
     		$page_data["keyword"]=$page_data["keyword"].' ISBN: '.$this->input->post('isbn');
     		$num++;
     	}
     	if($this->input->post('kategori')!=''){
     		$idkat=$this->input->post('kategori');
     		if($idkat!='all'){
     			if($num>0)$page_data["keyword"]=$page_data["keyword"].',';
     			$kat=$this->Md_siperpus_kategori_buku->getKategoriById($idkat);
     			if($kat)$page_data["keyword"]=$page_data["keyword"].' Kategori: '.$kat[0]->nmkategori;
     			$num++;
     		}
     	}
     	$this->load->view('front',$page_data);
     }
 */

    function digital_book($param1 = '')
    {

        $this->load->library('pagination');
        $msc = microtime(true);

        $page_data['page_access'] = "home";
        $page_data['page_name'] = 'digital_book';
        $page_data['page_content'] = 'dbook';
        $config = array();
        if ($param1 != '' && $this->uri->segment(4)) {
            $page_data['search'] = $param1;
            $uri = 4;
            $config['base_url'] = base_url() . 'home/digital_book/' . $page_data['search'] . '/';
        } else {
            $page_data['search'] = '';
            $uri = 3;
            $config['base_url'] = base_url() . 'home/digital_book/';
        }
        if ($this->input->post('search')) {
            $page_data['search'] = $this->input->post('search');
            $config['base_url'] = base_url() . 'home/digital_book/' . $page_data['search'] . '/';
        }

        if ($this->uri->segment($uri)) {
            $page = ($this->uri->segment($uri));
        } else {
            $page = 1;
        }
        $config["per_page"] = 12;

        $total_row = $this->Md_siperpus_buku_digital->record_count($page_data['search'], $config["per_page"], $page);

        $config["total_rows"] = $total_row;
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = 9;
        $config["uri_segment"] = 3;
        $config["cur_page"] = $page;

        $config['reuse_query_string'] = TRUE;

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
        $page_data["results"] = $this->Md_siperpus_buku_digital->fetch_data($page_data['search'], $config["per_page"], $page);
        $msc = microtime(true) - $msc;
        $page_data["links"] = $this->pagination->create_links();
        $page_data["num_row"] = $total_row;
        $page_data["extime"] = $msc;
        $page_data["keyword"] = $page_data['search'];
        $num = 0;
        if ($this->input->post('judul') != '') {
            $page_data["keyword"] = $page_data["keyword"] . ' Judul: ' . $this->input->post('judul');
            $num++;
        }
        if ($this->input->post('penulis') != '') {
            if ($num > 0)
                $page_data["keyword"] = $page_data["keyword"] . ',';
            $page_data["keyword"] = $page_data["keyword"] . ' Penulis: ' . $this->input->post('penulis');
            $num++;
        }
        if ($this->input->post('seri') != '') {
            if ($num > 0)
                $page_data["keyword"] = $page_data["keyword"] . ',';
            $page_data["keyword"] = $page_data["keyword"] . ' Seri: ' . $this->input->post('seri');
            $num++;
        }
        if ($this->input->post('isbn') != '') {
            if ($num > 0)
                $page_data["keyword"] = $page_data["keyword"] . ',';
            $page_data["keyword"] = $page_data["keyword"] . ' ISBN: ' . $this->input->post('isbn');
            $num++;
        }
        $page_data['mediasosial'] = $this->Md_mediasosial->getAllMediasosial();
        $this->load->view('front', $page_data);
    }

    function detail($param1 = '', $param2 = '')
    {
        $page_data['page_access'] = "home";
        $page_data['page_name'] = 'penelusuran_buku';
        $page_data['page_content'] = 'pbukudetail';
        $page_data['page_action'] = $param1;
        $page_data['page_action2'] = $param2;
        $isbn = urldecode(str_replace('_', '/', $param1));
        $noklas = urldecode(str_replace('+', ' ', $param2));

        $page_data['detail'] = $this->Md_siperpus_buku->getBukuByISBN($isbn, $noklas);
        $page_data['kategori'] = $this->Md_siperpus_kategori_buku->getKategoriById($page_data['detail'][0]->idkategori);
        $page_data['referensi'] = $this->Md_siperpus_buku_prodi->getBukuByISBN($isbn, $noklas);
        $page_data['inv'] = $this->Md_siperpus_inventaris->getInventarisByISBNdanNoKlas($isbn, $noklas);
        $page_data['mediasosial'] = $this->Md_mediasosial->getAllMediasosial();
        $dtfile = $this->Md_siperpus_buku_file->getRowFileById($isbn, $noklas);

        $jnsfile = '';
        $arrFile = array();
        if ($dtfile) {
            $decoded_data = json_decode($dtfile->file_name);

            $jnsfile = determine_version($dtfile->file_name);

            if ($jnsfile == 1) {
                $decoded_data = json_decode($dtfile->file_name, true);

                foreach ($decoded_data as $dt) {

                    $fileInfo = pathinfo($dt);
                    $fileExtension = '-';
                    if (isset($fileInfo['extension'])) {
                        $fileExtension = $fileInfo['extension'];
                    }

                    array_push($arrFile, array(
                        'nm_file' => $dt,
                        'is_baca' => 'Ya',
                        'is_download' => 'Ya',
                        'ext_file' => $fileExtension
                    ));
                }
            } else if ($jnsfile == 2) {
                $decoded_data = json_decode($dtfile->file_name, true);

                foreach ($decoded_data as $dt) {

                    $fileInfo = pathinfo($dt['filename']);
                    $fileExtension = '-';
                    if (isset($fileInfo['extension'])) {
                        $fileExtension = $fileInfo['extension'];
                    }

                    array_push($arrFile, array(
                        'nm_file' => $dt['filename'],
                        'is_baca' => $dt['is_baca'],
                        'is_download' => $dt['is_download'],
                        'ext_file' => $fileExtension
                    ));
                }
            } else if ($jnsfile == 3) {

                $fileInfo = pathinfo($dtfile->file_name);
                $fileExtension = '-';
                if (isset($fileInfo['extension'])) {
                    $fileExtension = $fileInfo['extension'];
                }

                array_push($arrFile, array(
                    'nm_file' => $dtfile->file_name,
                    'is_baca' => 'Ya',
                    'is_download' => 'Ya',
                    'ext_file' => $fileExtension
                ));
            }
        }



        /*
         $arrayfile = array();
         $arrfile = array();
         if ($dtfile) {
         	$string = $dtfile[0]['file_name'];
         	// Menghilangkan kurung kurawal di awal dan akhir string
         	$string = trim($string, '[]');
         	// Memisahkan string menjadi array menggunakan delimiter '-'
         	$arrayfile = explode(',', $string);
         	foreach ($arrayfile as $arrdata) {
         		$fileName =
         			$arrdata;
         		$fileInfo = pathinfo($fileName);
         		$fileExtension = '-';
         		if (isset($fileInfo['extension'])) {
         			$fileExtension = $fileInfo['extension'];
         		}
         		array_push($arrfile, array(
         			'nm_file' => $arrdata,
         			'ext_file' => $fileExtension,
         		));
         	}
         }
         */
        // Menampilkan hasil
        // var_dump($arrayfile);
        // die;
        $page_data['file'] = $dtfile;
        $page_data['lokasifile'] = $arrFile;
        // var_Dump($page_data['file']);
        // die;
        $this->load->view('front', $page_data);
    }

    function read($param1 = '', $param2 = '')
    {
        $page_data['page_access'] = "home";
        $page_data['page_name'] = 'readartikel';
        $page_data['page_content'] = 'readartikel';
        $page_data['page_action'] = $param1;
        $page_data['media'] = '';
        $page_data['artikel'] = $this->Md_artikel->getNewArtikel();
        $detail = $this->Md_artikel->getArtikelById($param1);

        $data_kategori = $this->Md_jenis_artikel->getAllData();

        $page_data['detail'] = $detail;
        $page_data['kategori_artikel'] = $data_kategori;
        $page_data['jns_artikel'] = strtolower($detail[0]->jenis_artikel);
        $page_data['mediasosial'] = $this->Md_mediasosial->getAllMediasosial();
        if ($page_data['detail'] && count($page_data['detail']) > 0) {

            $media = $this->Md_media->getMediaById($page_data['detail'][0]->media_id);
            if ($media && count($media) > 0) {
                $page_data['media'] = $media[0]['judul'];
            }
        }

        $this->load->view('front', $page_data);
    }

    function artikel($param1 = '', $param2 = '')
    {
        $page_data['page_access'] = "home";
        $page_data['page_name'] = 'artikel';
        $page_data['page_content'] = 'artikel';
        $page_data['page_action'] = $param1;
        $page_data['media'] = '';

        if ($param1 == 'kategori') {



            $kategori_artikel_id = decrypt($param2);

            // Pagination settings
            $config['base_url'] = base_url("home/artikel/kategori/$param2");
            $config['total_rows'] = $this->Md_artikel->countArtikelByJenisArtikelId($kategori_artikel_id);
            $config['per_page'] = 5; // Adjust the number of items per page
            $config['uri_segment'] = 5; // Adjust this based on your URL structure
            // Porto pagination settings
            $config['full_tag_open'] = '<ul class="pagination bootpag">';
            $config['full_tag_close'] = '</ul>';
            $config['first_link'] = 'First';
            $config['last_link'] = 'Last';
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
            $config['prev_link'] = '&laquo';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
            $config['next_link'] = '&raquo';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active"><a href="#">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';

            // Load pagination library and initialize
            $this->load->library('pagination');
            $this->pagination->initialize($config);

            // Get current page from URL
            $page = $this->uri->segment(5, 0); // Default to 0 if not set
            $limit = $config['per_page'];
            $detail = $this->Md_artikel->getArtikelByJenisArtikelId($kategori_artikel_id, $limit, $page);

            $getKategori = $this->Md_jenis_artikel->getDataById($kategori_artikel_id);
            $data_kategori = $this->Md_jenis_artikel->getAllData();

            $page_data['detail'] = $detail;
            $page_data['kategori_artikel'] = $data_kategori;
            $page_data['artikel'] = $this->Md_artikel->getNewArtikel();
            $page_data['jns_artikel'] = strtolower($getKategori->jenis_artikel);
            $page_data['mediasosial'] = $this->Md_mediasosial->getAllMediasosial();

            // Pass pagination links to view
            $page_data['pagination_links'] = $this->pagination->create_links();
        } else if ($param1 == 'search') {

            $search = $this->input->get('cari_artikel') ? $this->input->get('cari_artikel') : '';

            // Pagination settings
            $config['base_url'] = base_url("home/artikel/search");
            $config['total_rows'] = $this->Md_artikel->countgetArtikelByJudul($search);
            $config['per_page'] = 5; // Adjust the number of items per page
            $config['reuse_query_string'] = TRUE; // Ensure query strings are preserved
            $config['page_query_string'] = TRUE;
            $config['query_string_segment'] = 'per_page';

            // Porto pagination settings
            $config['full_tag_open'] = '<ul class="pagination bootpag">';
            $config['full_tag_close'] = '</ul>';
            $config['first_link'] = 'First';
            $config['last_link'] = 'Last';
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
            $config['prev_link'] = '&laquo';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
            $config['next_link'] = '&raquo';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active"><a href="#">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';

            // Load pagination library and initialize
            $this->load->library('pagination');
            $this->pagination->initialize($config);

            // Get current page from URL
            $page = $this->input->get('per_page', TRUE) ? $this->input->get('per_page', TRUE) : 0; // Adjust this to get 'per_page' from query string
            $limit = $config['per_page'];
            $detail = $this->Md_artikel->getArtikelByJudul($search, $limit, $page);

            $data_kategori = $this->Md_jenis_artikel->getAllData();

            $page_data['detail'] = $detail;
            $page_data['kategori_artikel'] = $data_kategori;
            $page_data['artikel'] = $this->Md_artikel->getNewArtikel();
            $page_data['jns_artikel'] = $search;
            $page_data['mediasosial'] = $this->Md_mediasosial->getAllMediasosial();

            // Pass pagination links to view
            $page_data['pagination_links'] = $this->pagination->create_links();
        }
        $this->load->view('front', $page_data);
    }

    function penelusuran_bukuprodi()
    {
        $page_data['page_content'] = 'pbukuprodi';
        $page_data['page_name'] = 'pbukuprodi';
        $page_data['mediasosial'] = $this->Md_mediasosial->getAllMediasosial();
        $this->load->view('front', $page_data);
    }

    function kontak($param1 = '')
    {
        if ($param1 == 'getWhatsappReady') {
            $x = $this->Md_kontak->getWhatsappOnly();
            foreach ($x as $row) {
                $num = $row->hp;
                $row->hp = "62" . substr_replace($num, '', 0, 1);
            }
            echo json_encode($x);
            die;
        }

        $page_data['mediasosial'] = $this->Md_mediasosial->getAllMediasosial();
        $page_data['page_content'] = 'kontak';
        $page_data['page_name'] = 'kontak';
        $this->load->view('front', $page_data);
    }

    function about()
    {
        $page_data['page_content'] = 'about';
        $page_data['page_name'] = 'about';
        $page_data['mediasosial'] = $this->Md_mediasosial->getAllMediasosial();
        $isi = $this->Md_siperpus_manage_halaman->getHalamanById(1);

        $page_data['isi'] = json_decode(json_encode($isi), true);
        $this->load->view('front', $page_data);
    }

    function halaman($slug)
    {

        $halaman = $this->Md_siperpus_manage_halaman->getHalamanByLink("lib.pkr.ac.id/halaman/$slug");
        $page_data['halaman'] = $halaman;
        // var_dump(json_decode(json_encode($halaman), true));
        // die;
        $page_data['isi'] = json_decode(json_encode($halaman), true);
        $page_data['mediasosial'] = $this->Md_mediasosial->getAllMediasosial();
        $page_data['page_content'] = 'halaman';
        $page_data['page_name'] = 'halaman';

        $this->load->view('front', $page_data);
    }

    function referensi()
    {
        $page_data['page_content'] = 'referensi';
        $page_data['page_name'] = 'referensi';
        $page_data['mediasosial'] = $this->Md_mediasosial->getAllMediasosial();
        $page_data['isi'] = $this->Md_siperpus_manage_halaman->getHalamanById(2);
        $page_data['isi'] = json_decode(json_encode($page_data['isi']), true);
        $this->load->view('front', $page_data);
    }

    function loginform()
    {
        $data['recaptcha_site_key'] = $this->config->item('recaptcha_site_key');
        $this->load->view('login', $data);
    }

    function login()
    {
        $config = array(
            array(
                'field' => 'login_type',
                'label' => 'Account Type',
                'rules' => 'required|xss_clean'
            ),
            array(
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'required|xss_clean'
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required|xss_clean|callback__validate_login'
            )
        );
        $this->form_validation->set_rules($config);
        $this->form_validation->set_message('_validate_login', ' Login failed!');
        $this->form_validation->set_error_delimiters('<div class="alert alert-error">
                    <button type="button" class="close" data-dismiss="alert">×</button>', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('login');
        } else {
            if ($this->session->userdata('login') == "A")
                redirect(base_url() . 'admin/manage_artikel', 'refresh');
            if ($this->session->userdata('login') == "C")
                redirect(base_url() . 'admin/manage_artikel', 'refresh');
            if ($this->session->userdata('login') == "K")
                redirect(base_url() . 'admin/manage_artikel', 'refresh');
            if ($this->session->userdata('login') == "P")
                redirect(base_url() . 'admin/manage_artikel', 'refresh');
            if ($this->session->userdata('login') == "R")
                redirect(base_url() . 'admin/manage_artikel', 'refresh');
            if ($this->session->userdata('login') == "S")
                redirect(base_url() . 'admin/manage_artikel', 'refresh');
            if ($this->session->userdata('login') == "V")
                redirect(base_url() . 'admin/manage_artikel', 'refresh');
        }
    }

    function _validate_login($str)
    {
        if ($this->input->post('username') == '') {
            $this->session->set_flashdata('flash_message', 'login_failed1');
            return FALSE;
        }
        //$sd= hash('sha1',preg_replace('/[^A-Za-z0-9\/\s,\s.\s-\s+\s?\s_\s)\s(\s@\s:\s#\s!\s*\s&\s>\s<\s=\s;\s"]/', '', $this->input->post('password')));
        //$sd= preg_replace('/[^A-Za-z0-9\/\s,\s.\s-\s+\s?\s_\s)\s(\s@\s:\s#\s!\s*\s&\s>\s<\s=\s;\s"]/', '', $this->input->post('password'));
        $sd = $this->input->post('password');
        $data = $this->Md_siperpus_sysuser->checkLogin($this->input->post('username'), $sd);
        //var_dump($data);
        if (count($data) > 0) {
            $row = $data[0];
            if ($row->idsysgroup == 'K') {
                $this->session->set_userdata('login_type', 'admin');
                $this->session->set_userdata('login', 'A');
                $this->session->set_userdata('idsys', $row->idsysuser);
                $this->session->set_userdata('username', $row->name);
                /*
                  $date = new DateTime();

                  $log['user_id']=$row->user_id;
                  $log['tgl']=$date->format("Y-m-d H:i:s");
                  $log['jenis_log']='admin';
                  $log['jenis_akses']='LogIn';
                  $log['status']=1;
                  $log['keterangan']=$row->username.' Melakukan Login';
                  $log['IP']=$_SERVER['REMOTE_ADDR'];
                  $this->md_log->addLogBaru($log);
                 */
                redirect(base_url() . 'admin/manage_artikel', 'refresh');
            }
            return TRUE;
        } else {
            $this->session->set_flashdata('alert', 'alert-danger');
            $this->session->set_flashdata('flash_message', 'Login failed!');
            return FALSE;
        }
    }

    public function login2()
    {
        // ============================================
        // 1. HONEYPOT — tolak jika terisi (bot)
        // ============================================
        if (!empty($this->input->post('form_botcheck'))) {
            redirect(base_url() . 'home', 'refresh');
            return;
        }

        // ============================================
        // 2. RATE LIMITING — maks 5 percobaan per 10 menit per IP
        // ============================================
        $ip          = $this->input->ip_address();
        $session_key = 'login_attempt_' . str_replace('.', '_', $ip);
        $attempts    = $this->session->userdata($session_key) ?? 0;

        if ($attempts >= 5) {
            $this->session->set_flashdata('flash_message', 'Terlalu banyak percobaan login. Silakan coba lagi dalam beberapa menit.');
            redirect(base_url() . 'home', 'refresh');
            return;
        }

        // ============================================
        // 3. VERIFIKASI RECAPTCHA V3
        // ============================================
        $recaptcha_token  = $this->input->post('recaptcha_token');
        $recaptcha_secret = $this->config->item('recaptcha_secret_key');

        if (empty($recaptcha_token)) {
            $this->session->set_flashdata('flash_message', 'Token keamanan tidak ditemukan.');
            redirect(base_url() . 'home', 'refresh');
            return;
        }

        $verify_response  = file_get_contents(
            'https://www.google.com/recaptcha/api/siteverify'
            . '?secret='   . $recaptcha_secret
            . '&response=' . $recaptcha_token
            . '&remoteip=' . $ip
        );
        $recaptcha_result = json_decode($verify_response);

        if (!$recaptcha_result->success || (isset($recaptcha_result->score) && $recaptcha_result->score < 0.5)) {
            $this->session->set_flashdata('flash_message', 'Permintaan terdeteksi sebagai bot.');
            redirect(base_url() . 'home', 'refresh');
            return;
        }

        // ============================================
        // 4. VALIDASI INPUT — cegah array injection
        // ============================================
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        if (is_array($username) || is_array($password)) {
            redirect(base_url() . 'home', 'refresh');
            return;
        }

        $username = trim($username);

        if (empty($username) || empty($password)) {
            $this->session->set_flashdata('flash_message', 'Username dan password wajib diisi.');
            redirect(base_url() . 'home', 'refresh');
            return;
        }

        // Batasi panjang input
        if (strlen($username) > 50 || strlen($password) > 100) {
            $this->session->set_flashdata('flash_message', 'Input tidak valid.');
            redirect(base_url() . 'home', 'refresh');
            return;
        }

        // ============================================
        // 5. CEK LOGIN KE DATABASE
        // ============================================
        $data = $this->Md_siperpus_sysuser->checkLogin($username, $password);

        if (count($data) > 0) {

            // Login berhasil — reset counter percobaan
            $this->session->unset_userdata($session_key);

            $row        = $data[0];
            $grantadd   = $this->Md_siperpus_sysgrant->getGrantByGroup($row->idsysgroup, 1, 0, 0, 0, 0);
            $grantedit  = $this->Md_siperpus_sysgrant->getGrantByGroup($row->idsysgroup, 0, 1, 0, 0, 0);
            $grantdelete= $this->Md_siperpus_sysgrant->getGrantByGroup($row->idsysgroup, 0, 0, 1, 0, 0);
            $grantview  = $this->Md_siperpus_sysgrant->getGrantByGroup($row->idsysgroup, 0, 0, 0, 1, 0);
            $grantprint = $this->Md_siperpus_sysgrant->getGrantByGroup($row->idsysgroup, 0, 0, 0, 0, 1);

            $this->session->set_userdata([
                'idsys'        => $row->idsysuser,
                'username'     => $row->name,
                'avatar'       => $row->avatar ?: 'Male-1.png',
                'login_type'   => 'admin',
                'default'      => 'manage_artikel',
                'perm_add'     => $grantadd,
                'perm_edit'    => $grantedit,
                'perm_delete'  => $grantdelete,
                'perm_view'    => $grantview,
                'perm_print'   => $grantprint,
            ]);

            // Catat log login
            $this->Md_log->addLog([
                'user_id'    => $row->idsysuser,
                'jenis_log'  => 'Admin',
                'jenis_akses'=> 'LogIn',
                'status'     => 1,
                'keterangan' => $row->name . ' Melakukan Login',
                'IP'         => $ip,
            ]);

            redirect(base_url() . 'admin/' . $this->session->userdata('default'), 'refresh');

        } else {

        
            $msg  = "Login gagal. Username atau password anda salah!";

            // Catat log gagal login
            $this->Md_log->addLog([
                'user_id'    => 0,
                'jenis_log'  => 'Admin',
                'jenis_akses'=> 'LogIn Failed',
                'status'     => 0,
                'keterangan' => "Percobaan login gagal untuk username: {$username}",
                'IP'         => $ip,
            ]);

            $this->session->set_flashdata('flash_message', $msg);
            redirect(base_url() . 'home/loginform', 'refresh');
        }
    }

    function sendemail($param1 = '')
    {
        $email = $this->input->post('form_email');
        $subject = $this->input->post('form_subject');
        $pesan = $this->input->post('form_message');
        $nama = $this->input->post('form_name');
        if ($email != '') {
            $this->load->library('email');
            $this->email->from('poltekkeskemenkes@gmail.com', 'POLTEKES KESEHATAN RIAU');
            $this->email->to('frendy@pcr.ac.id');
            //$this->email->to('perpuspkr@yahoo.com');
            $this->email->subject($subject);
            $this->email->message($pesan);
            $this->email->send();

            echo json_encode(array("status" => TRUE, "message" => "Email Send"));
        } else {
            echo json_encode(array("status" => FALSE, "message" => "Empty Field"));
        }
    }

    function forgetpassword($param1 = '')
    {
        $forgetu = $this->input->post('email');
        if ($forgetu != '') {
            $dtpengguna = $this->Md_siperpus_user->getUserByEmail($forgetu);
            //kirim email
            if (count($dtpengguna) > 0) {

                $this->Md_user->resetUser($dtpengguna[0]->user_id, $dtpengguna[0]->username);

                $this->load->library('email');
                $this->email->from('noreply@pcr.ac.id', 'Care Learn');
                $this->email->to($dtpengguna[0]->email);
                $this->email->subject('Forget Password');
                $this->email->message('
			Hello, ' . $dtpengguna[0]->username . '
			<br/><br/>
			Username : ' . $dtpengguna[0]->username . '  <br/>
			Password : ' . $dtpengguna[0]->username . ' <br/><br/>
			Please do reset your password to avoid problem.

			<br/><br/>
			Regards,<br/>
			Perpustakaan Politeknik Kesehatan Riau
			');
                $this->email->send();
                $this->session->set_flashdata('alert', 'alert-success');
                $this->session->set_flashdata('flash_message', 'Forget Password Success Please visit your email address');
                redirect(base_url(), 'refresh');
            } else {
                $this->session->set_flashdata('alert', 'alert-danger');
                $this->session->set_flashdata('flash_message', 'Email Tidak Terdaftar');
                redirect(base_url(), 'refresh');
            }
        } else {
            $this->session->set_flashdata('alert', 'alert-danger');
            $this->session->set_flashdata('flash_message', 'Email Kosong');
            redirect(base_url(), 'refresh');
        }
    }

    function download($param1 = '')
    {
        $username = $this->input->post('username');
        $pass = strrev($this->input->post('pass'));
        $cek = array();
        if ($username != '' && $pass != '') {
            if ($username == $pass) {
                $cek = $this->Md_siperpus_sysuser->cekAnggota($username);
            }
        }
        if ($cek) {
            $info = $this->Md_vwanggota->getInfoAnggota($username);
            $this->session->set_userdata('member', $username);
            $this->session->set_userdata('member_nama', $info && $info->nama ? $info->nama : $username);
            $this->session->set_userdata('member_tipe', $info && $info->kategori == 'k' ? 'Pegawai' : 'Anggota');
            echo json_encode(TRUE);
        } else {
            echo json_encode(FALSE);
        }
    }

    function readbook($param1 = '')
    {
        //$param1 = 'mysteryofyellowr0000lero_q2k7.pdf';
        $this->load->model('Md_view_filebuku');
        $namafile = $param1;

        $url = base_url() . 'uploads/files/' . $namafile;


        $path = FCPATH . 'uploads/files/' . $param1;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:application/' . $type . ';base64,' . base64_encode($data);

        $arrdata = array(
            'pdf_base64' => $base64
        );
        $data_file = $this->Md_siperpus_buku_file->search_file($namafile);

        if (!$data_file) {
            echo "Data File tidak ditemukan.";
            die;
        }

        $headers = get_headers($url);

        if ($headers && strpos($headers[0], '200')) {
        } else {
            echo "File tidak ditemukan.";
            die;
        }

        $arrinsert = [
            'ip' => $this->input->ip_address(),
            'tgl_post' => date('Y-m-d H:i:s'),
            'status' => 1,
            'file_id' => $data_file->file_id
        ];
        $this->Md_view_filebuku->addData($arrinsert);

        $this->load->view('readbook', $arrdata);
    }

    function pengunjung($param1 = '')
    {
        $page_data['mediasosial'] = $this->Md_mediasosial->getAllMediasosial();
        if ($param1 == '') {
            $page_data['recaptcha_site_key'] = $this->config->item('recaptcha_site_key');
            $page_data['page_access'] = "home";
            $page_data['page_name'] = 'pengunjung';
            $page_data['page_content'] = 'dpengunjung';

            $this->load->view('front', $page_data);
        } else if ($param1 == 'send') {
            // ============================================
            // VERIFIKASI RECAPTCHA V3
            // ============================================
            
            // Ambil secret key dari config
            $recaptcha_secret = $this->config->item('recaptcha_secret_key');

            $recaptcha_token  = $this->input->post('recaptcha_token');

            // Tolak jika token tidak dikirim
            if (empty($recaptcha_token)) {
                echo json_encode(['status' => FALSE, 'message' => 'Token keamanan tidak ditemukan.']);
                die;
            }

            // Kirim token ke Google untuk diverifikasi
            $verify_response = file_get_contents(
                'https://www.google.com/recaptcha/api/siteverify'
                . '?secret='   . $recaptcha_secret
                . '&response=' . $recaptcha_token
                . '&remoteip=' . $this->input->ip_address()
            );

            $recaptcha_result = json_decode($verify_response);

            // Tolak jika verifikasi gagal atau skor terlalu rendah (bot < 0.5)
            if (!$recaptcha_result->success || (isset($recaptcha_result->score) && $recaptcha_result->score < 0.5)) {
                echo json_encode(['status' => FALSE, 'message' => 'Permintaan terdeteksi sebagai spam.']);
                die;
            }

            $this->form_validation->set_rules('form_name',             'Nama',             'required|min_length[3]|regex_match[/^[a-zA-Z,.\s]+$/]');
            $this->form_validation->set_rules('form_email', 'Email', 'required|callback__valid_email_custom');
            $this->form_validation->set_rules('form_asal_instansi',    'Asal Instansi',    'required|min_length[3]');
            $this->form_validation->set_rules('form_nowhatsapp',       'No Whatsapp',      'required|min_length[8]|numeric');
            $this->form_validation->set_rules('form_tujuan_berkunjung','Tujuan Berkunjung','required');

            if (!$this->form_validation->run()) {
                die(json_encode([
                    'status'  => FALSE,
                    'message' => validation_errors(), // tampilkan pesan error spesifik
                ]));
            }
            
            $nama = $this->input->post('form_name');
            $email = $this->input->post('form_email');
            $asal_instansi = $this->input->post('form_asal_instansi');
            $no_whatsapp = $this->input->post('form_nowhatsapp');
            $tujuan_berkunjung = $this->input->post('form_tujuan_berkunjung');

            $tglnow = date('Y-m-d H:i:s');
            $data = [
                'nama' => $nama,
                'email' => $email,
                'asal_instansi' => $asal_instansi,
                'no_wa' => $no_whatsapp,
                'tujuan_berkunjung' => $tujuan_berkunjung,
                'tgl_post' => $tglnow,
                'status' => 1,
            ];

            $this->db->trans_begin();
            $this->Md_pengunjung->addData($data);

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                echo json_encode(array("status" => FALSE, "message" => "Data gagal disimpan"));
                die;
            }

            $this->db->trans_commit();
            echo json_encode(array("status" => TRUE, "message" => "Data Berhasil Disimpan"));
            die;
        }
    }
    
    public function _valid_email_custom($email)
    {
        if (function_exists('idn_to_ascii') && strpos($email, '@') !== FALSE) {
            list($local, $domain) = explode('@', $email, 2);

            // Gunakan konstanta UTS46 agar kompatibel dengan PHP 7.2+
            $domain = idn_to_ascii($domain, 0, INTL_IDNA_VARIANT_UTS46);

            $email = $local . '@' . $domain;
        }

        return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
    }


    function perpanjang_pinjam($param1 = '', $param2 = '')
    {
        $page_data['mediasosial'] = $this->Md_mediasosial->getAllMediasosial();
        if ($param1 == '') {
            $page_data['page_access'] = "home";
            $page_data['page_name'] = 'perpanjang_pinjam';
            $page_data['page_content'] = 'perpanjang_pinjam';

            $this->load->view('front', $page_data);
        } else if ($param1 == 'cekdatapinjam') {
            $this->form_validation->set_rules('no_anggota', 'field no_anggota', 'required');
            $this->form_validation->set_rules('no_buku', 'field no_buku', 'required');

            //tidak boleh edit jika konfigurasi is_allow_edit=Tidak dan is_daftar=Tidak           
            if (!$this->form_validation->run()) {

                die(json_encode([
                    'status' => 'error',
                    'message' => 'Harap lengkapi data input',
                ]));
            }
            $no_anggota = $this->input->post('no_anggota');
            $no_buku = $this->input->post('no_buku');

            $getDataPinjam = $this->Md_siperpus_transaksi->getDataPinjam($no_anggota, $no_buku);

            $arrData = array();
            if ($getDataPinjam) {

                $arrData = [
                    'judul' => $getDataPinjam->judul,
                    'is_perpanjang' => $getDataPinjam->is_perpanjang == 0 ? '<span class="text-success">Bisa Diperpanjang</span>' : '<span class="text-black">Tidak Bisa Diperpanjang</span>',
                    'tgl_pinjam' => $getDataPinjam->tgl_pinjam,
                    'batas' => $getDataPinjam->batas,
                    'aksi' => $getDataPinjam->is_perpanjang == 0 ? '<a href="javascript:perpanjang_peminjaman(\'' . encrypt($getDataPinjam->tid) . '\')" class="btn btn-primary mb-2">Perpanjang</a>' : ''
                ];
                echo json_encode(array("status" => TRUE, "message" => "Data Ditemukan", 'data' => $arrData));
                die;
            } else {
                echo json_encode(array("status" => FALSE, "message" => "Data Tidak Ditemukan", 'data' => $arrData));
                die;
            }
        } else if ($param1 == 'perpanjang_peminjaman') {
            $transaksi_id = decrypt($param2);

            //tidak boleh edit jika konfigurasi is_allow_edit=Tidak dan is_daftar=Tidak           

            $getDataPinjam = $this->Md_siperpus_transaksi->getDataById($transaksi_id);

            if (!$getDataPinjam) {
                echo json_encode(array("status" => FALSE, "message" => "Data Peminjaman Tidak Ditemukan"));
                die;
            }
            if ($getDataPinjam->kembali == 1) {
                echo json_encode(array("status" => FALSE, "message" => "Buku Telah Dikembalikan"));
                die;
            }

            $tambah_batas_tanggal_peminjaman = date('Y-m-d', strtotime($getDataPinjam->batas . ' + 7 days'));

            if ($getDataPinjam->is_perpanjang == 1) {
                echo json_encode(array("status" => FALSE, "message" => "Perpanjangan Buku hanya berlaku 1 kali"));
                die;
            }

            $update = [
                'is_perpanjang' => 1,
                'tgl_update_perpanjang' => date('Y-m-d H:i:s'),
                'batas' => $tambah_batas_tanggal_peminjaman,
            ];

            $this->db->trans_begin();
            $this->Md_siperpus_transaksi->updateTransaksi($transaksi_id, $update);

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                echo json_encode(array("status" => FALSE, "message" => "Data gagal disimpan"));
                die;
            }

            $this->db->trans_commit();

            $arrData = [
                'judul' => $getDataPinjam->judul,
                'is_perpanjang' => '<span class="text-black">Tidak Bisa Diperpanjang</span>',
                'tgl_pinjam' => $getDataPinjam->tgl_pinjam,
                'batas' => $tambah_batas_tanggal_peminjaman,
                'aksi' => ''
            ];

            echo json_encode(array("status" => TRUE, "message" => "Data Berhasil Disimpan", "data" => $arrData));
            die;
        }
    }

    function daftar_anggota_luar($param1 = '', $param2 = '')
    {
        $this->load->library('upload');
        $this->load->library('image_lib');

        $page_data['mediasosial'] = $this->Md_mediasosial->getAllMediasosial();
        if ($param1 == '') {
            $page_data['page_access'] = "home";
            $page_data['page_name'] = 'daftar_anggota_luar';
            $page_data['page_content'] = 'daftar_anggota_luar';

            $this->load->view('front', $page_data);
        } else if ($param1 == 'add') {

            function deleteFileUpload($x)
            {
                if ($x != '') {
                    $listfileupload = './uploads/pasfoto_anggotaluar/' . $x;
                    unlink($listfileupload);
                }
            }

            $this->form_validation->set_rules('no_anggota_al', 'field no_anggota_al', 'required');
            $this->form_validation->set_rules('nama_al', 'field nama_al', 'required');
            $this->form_validation->set_rules('alamat', 'field alamat', 'required');
            $this->form_validation->set_rules('jk_al', 'field jk_al', 'required');
            $this->form_validation->set_rules('notelp_al', 'field notelp_al', 'required');
            $this->form_validation->set_rules('tempatlahir_al', 'field tempatlahir_al', 'required');
            $this->form_validation->set_rules('tgllahir_al', 'field tgllahir_al', 'required');
            $this->form_validation->set_rules('instansi_asal_nama_al', 'field instansi_asal_nama_al', 'required');
            $this->form_validation->set_rules('instansi_asal_alamat_al', 'field instansi_asal_alamat_al', 'required');
            $this->form_validation->set_rules('jabatan_semester_al', 'field jabatan_semester_al', 'required');
            //tidak boleh edit jika konfigurasi is_allow_edit=Tidak dan is_daftar=Tidak           


            if (!$this->form_validation->run()) {

                die(json_encode([
                    'status' => 'error',
                    'message' => 'Harap lengkapi data input',
                ]));
            }

            if (empty($_FILES['pasfoto_al']['name'])) {
                echo json_encode(array("status" => FALSE, "message" => "File pasfoto wajib di isi !"));
                die;
            }

            $noid = $this->input->post('no_anggota_al');
            $nama = $this->input->post('nama_al');
            $jk = $this->input->post('jk_al');
            $telepon = $this->input->post('notelp_al');
            $tgllahir = $this->input->post('tgllahir_al');
            $tgllahir = $this->input->post('tgllahir_al');
            $tempatlahir = $this->input->post('tempatlahir_al');
            $instansi_asal_nama = $this->input->post('instansi_asal_nama_al');
            $instansi_asal_alamat = $this->input->post('instansi_asal_alamat_al');
            $jabatan_semester = $this->input->post('jabatan_semester_al');
            $alamat = $this->input->post('alamat');

            if (!ctype_digit($telepon)) {
                echo json_encode(array("status" => FALSE, "message" => "Format Nomor telpon hanya boleh angka"));
                die;
            }


            //cek no ktp / nik 
            $getdata = $this->Md_siperpus_anggota_luar->getRowAnggotaLuarById($noid);

            if ($getdata) {
                echo json_encode(array("status" => FALSE, "message" => "Data dengan NO KTP/NIK tersebut sudah terdaftar"));
                die;
            }

            $config['upload_path'] = "./uploads/pasfoto_anggotaluar/";
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['encrypt_name'] = TRUE;
            $this->upload->initialize($config);

            if ($this->upload->do_upload("pasfoto_al")) {



                $datafoto = $this->upload->data();
                $namafile = $datafoto['file_name'];
                $filesize = round($_FILES['pasfoto_al']['size'] / 1024); //in Kb
                $type = explode("/", $_FILES['pasfoto_al']['type']);
                $file_type = strtoupper($type[1]);

                $file_path = $_FILES['pasfoto_al']['tmp_name'];
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
                    echo json_encode(array("status" => FALSE, "message" => $this->image_lib->display_errors()));

                    die;
                }

                $this->image_lib->clear();

                //delete real image
                deleteFileUpload($namafile);

                $namafile = $new_filename;
            } else {
                echo json_encode(array("status" => FALSE, "message" => $this->upload->display_errors()));
                die;
            }

            $tglnow = date('Y-m-d');
            $data = [
                'pasfoto' => $namafile,
                'noid' => $noid,
                'nama' => $nama,
                'jk' => $jk,
                'telepon' => $telepon,
                'tgllahir' => $tgllahir,
                'tempatlahir' => $tempatlahir,
                'instansi_asal_nama' => $instansi_asal_nama,
                'instansi_asal_alamat' => $instansi_asal_alamat,
                'jabatan_semester' => $jabatan_semester,
                'tgl_masuk' => $tglnow,
                'metode_daftar' => 'Mandiri',
                'alamat' => $alamat,
            ];

            $this->db->trans_begin();
            $this->Md_siperpus_anggota_luar->addKAnggotaLuar($data);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                echo json_encode(array("status" => FALSE, "message" => "Data gagal disimpan"));
                die;
            }

            $this->db->trans_commit();
            echo json_encode(array("status" => TRUE, "message" => "Data Berhasil Disimpan"));
            die;
        }
    }

    // Method untuk memulai sesi chat (Registrasi User)
    public function start_chat()
    {
        // ============================================
        // VERIFIKASI RECAPTCHA V3
        // ============================================
        $recaptcha_token  = $this->input->post('recaptcha_token');
        $recaptcha_secret = $this->config->item('recaptcha_secret_key');

        if (empty($recaptcha_token)) {
            echo json_encode(['status' => 'error', 'message' => 'Token keamanan tidak ditemukan.']);
            return;
        }

        $verify_response  = file_get_contents(
            'https://www.google.com/recaptcha/api/siteverify'
            . '?secret='   . $recaptcha_secret
            . '&response=' . $recaptcha_token
            . '&remoteip=' . $this->input->ip_address()
        );
        $recaptcha_result = json_decode($verify_response);

        if (!$recaptcha_result->success || (isset($recaptcha_result->score) && $recaptcha_result->score < 0.5)) {
            echo json_encode(['status' => 'error', 'message' => 'Permintaan terdeteksi sebagai spam.']);
            return;
        }

    
        $this->load->library('form_validation');

        // Gunakan callback atau validasi manual untuk email
        $email = $this->input->post('email');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Format email tidak valid.'
            ]);
            return;
        }

        // Lanjutkan validasi field lainnya
        $this->form_validation->set_rules('name', 'Nama', 'required');
        $this->form_validation->set_rules('phone', 'Nomor HP', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Data tidak lengkap.'
            ]);
            return;
        }

        // Proses simpan data...
        $data = [
            'nama'   => $this->input->post('name'),
            'email'  => $email,
            'no_hp'  => $this->input->post('phone'),
            'status' => '1',
            'status_sesi'  => 'aktif'    // Indikator sesi chat sedang berlangsung
        ];

        $chatsesi_id = $this->Md_chat->simpan_sesi($data);
        $this->session->set_userdata('chat_session_id', $chatsesi_id);

        echo json_encode(['status' => 'success', 'session_id' => $chatsesi_id]);
    }

    // Method untuk mengirim pesan dari user
    public function send()
    {
        $chatsesi_id = $this->input->post('session_id');
        $is_file = 0;
        $message = $this->input->post('message'); // Mengambil pesan teks jika ada
        $upload_error = "";

        // Logika upload file untuk user
        if (!empty($_FILES['file_lampiran']['name'])) {
            $config['upload_path']   = './assets/media/lampiran_chat/';
            // Pastikan 'xls' dan 'xlsx' ada di allowed_types
            $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|zip';
            $config['file_name']     = 'user_' . time() . '_' . uniqid();
            $config['max_size']      = 5000; // 5MB

            $this->load->library('upload');
            $this->upload->initialize($config);

            if ($this->upload->do_upload('file_lampiran')) {
                $uploadData = $this->upload->data();
                $message    = $uploadData['file_name'];
                $is_file    = 1;
            } else {
                // Jika upload gagal, ambil pesan errornya
                $upload_error = $this->upload->display_errors('', '');
                echo json_encode(['status' => 'error', 'message' => $upload_error]);
                return; // Berhenti di sini, jangan lanjut simpan ke database
            }
        }

        // PROTEKSI: Cek jika pesan kosong atau null agar tidak error database
        if (empty($message) && $is_file == 0) {
            echo json_encode(['status' => 'error', 'message' => 'Pesan tidak boleh kosong']);
            return;
        }

        $data = [
            'chatsesi_id'  => $chatsesi_id,
            'pengirim'     => $this->input->post('sender_type'),
            'isi_pesan'    => $message,
            'is_file'      => $is_file,
            'sudah_dibaca' => 0
        ];

        $this->Md_chat->simpan_pesan($data);
        echo json_encode(['status' => 'sent']);
    }

    // Method untuk mengambil history pesan (Polling)
    public function load_messages($chatsesi_id)
    {
        // 1. Ambil history pesan dari model
        $messages = $this->Md_chat->ambil_pesan($chatsesi_id);

        // 2. Ambil status sesi terbaru untuk mengecek apakah sudah diakhiri admin/user
        $this->db->select('status_sesi, tgl_akhir, diakhiri_oleh, diakhiri_idsysuser');
        $this->db->where('chatsesi_id', $chatsesi_id);
        $sesi = $this->db->get('chat_sesi')->row();

        // 3. Kirimkan data gabungan dalam format JSON
        echo json_encode([
            'messages'      => $messages,
            'status_sesi'   => ($sesi) ? $sesi->status_sesi : 'tidak aktif',
            'tgl_akhir'     => ($sesi) ? $sesi->tgl_akhir : null,
            'diakhiri_oleh' => ($sesi) ? $sesi->diakhiri_oleh : null,
            'admin_name'    => ($sesi) ? $sesi->diakhiri_idsysuser : null
        ]);
    }

    // Method untuk mengakhiri chat dari sisi User
    public function akhiri_chat()
    {
        $session_id = $this->session->userdata('chat_session_id');

        if ($session_id) {
            $data_update = [
                'status_sesi'   => 'tidak aktif',
                'tgl_akhir'     => date('Y-m-d H:i:s'),
                'diakhiri_oleh' => 'user', // Dicatat diakhiri oleh pelanggan sendiri
            ];

            $this->Md_chat->update_sesi($session_id, $data_update);

            // Hapus session agar user bisa memulai chat baru nanti
            $this->session->unset_userdata('chat_session_id');

            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    function logoutmember()
    {
        $this->session->unset_userdata('member');
        $this->session->unset_userdata('member_nama');
        $this->session->unset_userdata('member_tipe');
        redirect(base_url(), 'refresh');
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
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
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

}
