<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {



	 function __construct() {

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


		$this->load->model('Md_artikel');
		$this->load->model('Md_slide');
		$this->load->model('Md_media');
		$this->load->model('Md_mediadetail');


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

		if ($this->session->userdata('login_type') != 'admin')

			{

			$this->session->sess_destroy();

            redirect(base_url() . 'home', 'refresh');

			}

        if ($this->session->userdata('login') == 'A')

            redirect(base_url() . 'admin/'.$this->session->userdata('default'), 'refresh');

	}


function dashboard($param1='') {

        if ($this->session->userdata('login_type') != 'admin')
			$this->logout();

        $page_data['jml'] = $this->Md_siperpus_buku->getJumlahBuku();
		$page_data['eks'] = $this->Md_siperpus_inventaris->getJumlahEks();
		$page_data['pnj'] = $this->Md_siperpus_transaksi->getJumlahTran();
		$page_data['ang'] = $this->Md_vwanggota->getJumlahAng();



		$page_data['peminjaman'] = $this->Md_dashboard->getPeminjaman();
		$page_data['pengembalian'] = $this->Md_dashboard->getPengembalian();
		$page_data['batas'] = $this->Md_dashboard->getBatas();
		if($param1 =='fetch_peminjaman'){
			$total=$this->Md_dashboard->countFilteredPeminjaman();
				$page=intval($this->input->post('datatable[pagination][page]'));
			if($page<1)
				$page=1;

			$perpage=intval($this->input->post('datatable[pagination][perpage]'));
			$pages= intval($total/$perpage);
			$field=$this->input->post('datatable[sort][field]');

			if($field=='')
				$field=$this->input->post('datatable[pagination][field]');

			$sort=$this->input->post('datatable[sort][sort]');

			if($sort=='')
				$sort=$this->input->post('datatable[pagination][sort]');

			//mulai fetching data
			$data = array();
			$no = 0;
			$list=$this->Md_dashboard->getPeminjaman();
			foreach ($list as $row) {
				$no++;
				$arr = array();
					if($this->input->post('datatable[query][jenis]')=='dosen'){
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['nomor'] = $row->no_anggota;
					$arr['nama'] = $row->nama;
					$arr['prodi'] = '';
					$arr['noinv'] = $row->no_inv;
					$arr['judul'] = $row->judul;
					$arr['tanggal'] = $row->tgl_pinjam;
					$arr['batas'] = $row->batas;

				}else if($this->input->post('datatable[query][jenis]')=='anggota+luar'){
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['nomor'] = $row->no_anggota;
					$arr['nama'] = $row->nama;
					$arr['prodi'] = '';
					$arr['noinv'] = $row->no_inv;
					$arr['judul'] = $row->judul;
					$arr['tanggal'] = $row->tgl_pinjam;
					$arr['batas'] = $row->batas;
				}else{
					$arr['number'] = ($perpage*($page-1))+$no;
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

			$meta=array();
			$meta['page']=$page;
			$meta['pages']=$pages;
			$meta['perpage']=$perpage;
			$meta['total']=$total;
			$meta['sort']=$sort;
			$meta['field']=$field;
			$output = array(
							"meta" => $meta,
							"data" => $data
					);
			//output to json format
			echo json_encode($output);
		}else if($param1 =='fetch_pengembalian'){
			$total=$this->Md_dashboard->countFilteredPengembalian();
				$page=intval($this->input->post('datatable[pagination][page]'));
			if($page<1)
				$page=1;

			$perpage=intval($this->input->post('datatable[pagination][perpage]'));
			$pages= intval($total/$perpage);
			$field=$this->input->post('datatable[sort][field]');

			if($field=='')
				$field=$this->input->post('datatable[pagination][field]');

			$sort=$this->input->post('datatable[sort][sort]');

			if($sort=='')
				$sort=$this->input->post('datatable[pagination][sort]');

			//mulai fetching data
			$data = array();
			$no = 0;
			$list=$this->Md_dashboard->getPengembalian();
			foreach ($list as $row) {
				$no++;
				$arr = array();
					if($this->input->post('datatable[query][jenis]')=='dosen'){
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['nomor'] = $row->no_anggota;
					$arr['nama'] = $row->nama;
					$arr['prodi'] = '';
					$arr['noinv'] = $row->no_inv;
					$arr['judul'] = $row->judul;
					$arr['tanggal'] = $row->tgl_pinjam;
					$arr['batas'] = $row->batas;

				}else if($this->input->post('datatable[query][jenis]')=='anggota+luar'){
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['nomor'] = $row->no_anggota;
					$arr['nama'] = $row->nama;
					$arr['prodi'] = '';
					$arr['noinv'] = $row->no_inv;
					$arr['judul'] = $row->judul;
					$arr['tanggal'] = $row->tgl_pinjam;
					$arr['batas'] = $row->batas;
				}else{
					$arr['number'] = ($perpage*($page-1))+$no;
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

			$meta=array();
			$meta['page']=$page;
			$meta['pages']=$pages;
			$meta['perpage']=$perpage;
			$meta['total']=$total;
			$meta['sort']=$sort;
			$meta['field']=$field;
			$output = array(
							"meta" => $meta,
							"data" => $data
					);
			//output to json format
			echo json_encode($output);
		}else if($param1 =='fetch_batas'){
			$total=$this->Md_dashboard->countFilteredBatas();
				$page=intval($this->input->post('datatable[pagination][page]'));
			if($page<1)
				$page=1;

			$perpage=intval($this->input->post('datatable[pagination][perpage]'));
			$pages= intval($total/$perpage);
			$field=$this->input->post('datatable[sort][field]');

			if($field=='')
				$field=$this->input->post('datatable[pagination][field]');

			$sort=$this->input->post('datatable[sort][sort]');

			if($sort=='')
				$sort=$this->input->post('datatable[pagination][sort]');

			//mulai fetching data
			$data = array();
			$no = 0;
			$list=$this->Md_dashboard->getBatas();
			foreach ($list as $row) {
				$no++;
				$arr = array();
					if($this->input->post('datatable[query][jenis]')=='dosen'){
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['nomor'] = $row->no_anggota;
					$arr['nama'] = $row->nama;
					$arr['prodi'] = '';
					$arr['noinv'] = $row->no_inv;
					$arr['judul'] = $row->judul;
					$arr['tanggal'] = $row->tgl_pinjam;
					$arr['batas'] = $row->batas;

				}else if($this->input->post('datatable[query][jenis]')=='anggota+luar'){
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['nomor'] = $row->no_anggota;
					$arr['nama'] = $row->nama;
					$arr['prodi'] = '';
					$arr['noinv'] = $row->no_inv;
					$arr['judul'] = $row->judul;
					$arr['tanggal'] = $row->tgl_pinjam;
					$arr['batas'] = $row->batas;
				}else{
					$arr['number'] = ($perpage*($page-1))+$no;
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

			$meta=array();
			$meta['page']=$page;
			$meta['pages']=$pages;
			$meta['perpage']=$perpage;
			$meta['total']=$total;
			$meta['sort']=$sort;
			$meta['field']=$field;
			$output = array(
							"meta" => $meta,
							"data" => $data
					);
			//output to json format
			echo json_encode($output);
		}else{

			$peminjaman=$this->Md_siperpus_transaksi->getPeminjamanHari(10);
			$count_hari=0;
			foreach($peminjaman as $p){
				if($count_hari==0){
					$pnj_hari='{ y: "'.$p->tgl_pinjam.'",a :'.$p->jumlah.'}';
				}else{
					$pnj_hari=$pnj_hari.',{ y: "'.$p->tgl_pinjam.'",a :'.$p->jumlah.'}';
				}
				$count_hari++;
			}
			$page_data['pnj_hari'] = $pnj_hari;


			$kunjungan=$this->Md_siperpus_presensi->getPresensiHari(10);

			$count_hari=0;
			foreach($kunjungan as $k){
				$jum=$this->Md_siperpus_presensi->getJumlah(substr($k->tanggal,0,10));
				if($count_hari==0){
					$pre_hari='{ y: "'.substr($k->tanggal,0,10).'",a :'.$jum.'}';
				}else{
					$pre_hari=$pre_hari.',{ y: "'.substr($k->tanggal,0,10).'",a :'.$jum.'}';
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
	function ganti_password($param1 = '', $param2 = '', $param3 = '') {
        if ($this->session->userdata('login_type') != 'admin')
		  	$this->logout();
		$id=$this->session->userdata('idsys');
		$page_data['data_pengguna'] = $this->Md_siperpus_sysuser->getUserById($id);
		 if ($param1 == 'update'){

			$pass=$this->input->post('password');
			//$sd= hash('sha1',preg_replace('/[^A-Za-z0-9\/\s,\s.\s-\s+\s?\s_\s)\s(\s@\s:\s#\s!\s*\s&\s>\s<\s=\s;\s"]/', '', $this->input->post('password')));
			//$sdnew= hash('sha1',preg_replace('/[^A-Za-z0-9\/\s,\s.\s-\s+\s?\s_\s)\s(\s@\s:\s#\s!\s*\s&\s>\s<\s=\s;\s"]/', '', $this->input->post('passwordbaru')));
			$sd= $this->input->post('passwordbaru');
			$sdnew=$this->input->post('passwordbaruconfirm');
			$data['pass']=$sdnew;

			if($page_data['data_pengguna'][0]->pass == $pass && $sd == $sdnew){
				$this->Md_siperpus_sysuser->updateUser($id, $data);
				$this->session->set_flashdata('alert', 'alert-success');
				$this->session->set_flashdata('flash_message', 'Ubah Password Sukses');
				redirect(base_url() . 'admin/ganti_password', 'refresh');

			}else{
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
		function ganti_pic($param1 = '', $param2 = '', $param3 = '') {
			if ($this->session->userdata('login_type') != 'admin') $this->logout();
			$id=$this->session->userdata('idsys');
			$page_data['data_pengguna'] = $this->Md_siperpus_sysuser->getUserById($id);
			$page_data['data_pengguna'] = json_decode(json_encode($page_data['data_pengguna']), true);

			$config = array(
				'upload_path' => FCPATH . "uploads/profile",
				'allowed_types' => 'gif|jpg|png|jpeg|bmp',
				'max_size' => 1024 * 10
			);


			$this->upload->initialize($config);
			if (!$this->upload->do_upload('profile_pic'))
			{
				//upload to DB defaul image
				$data = $this->input->post('pic_def');
				$this->Md_siperpus_sysuser->updatePic($page_data['data_pengguna'][0]['idsysuser'],$data);
			}
			else{
				$data_up = $this->upload->data();
				$data = $data_up['file_name'];
				$config['image_library'] = 'gd2';
				$config['source_image'] = $data_up['full_path'];
				$config['maintain_ratio'] = TRUE;
				$config['overwrite'] = TRUE;
				$config['width']     = 200;
				$config['height']   = 250;
				$this->image_lib->initialize($config);
				$this->image_lib->resize();
				$this->Md_siperpus_sysuser->updatePic($page_data['data_pengguna'][0]['idsysuser'],$data);
			}
			redirect(base_url() . 'admin/dashboard', 'refresh');
		}

	/** *Data Mahasiswa* **/

	public function data_mahasiswa($param1='',$param2='')
	{
		if ($this->session->userdata('login_type') != 'admin')
				$this->logout();

		$date = new DateTime();
		$id=$this->session->userdata('idsys');

		$page_data['page_action'] = 'list';
		$page_data['page_title'] = 'Data Mahasiswa';
		$page_data['page_access'] = "admin";
		$page_data['page_name'] = 'data_mahasiswa';
		$page_data['page_now'] = 'Data Induk';
		$page_data['prodi'] = $this->Md_vwprodi->getProdiAll();

		if($param1 =='fetch'){
				$total=$this->Md_vwsiswa->countFiltered();
				$page=intval($this->input->post('datatable[pagination][page]'));
			if($page<1)
				$page=1;

			$perpage=intval($this->input->post('datatable[pagination][perpage]'));
			$pages= intval($total/$perpage);
			$field=$this->input->post('datatable[sort][field]');

			if($field=='')
				$field=$this->input->post('datatable[pagination][field]');

			$sort=$this->input->post('datatable[sort][sort]');

			if($sort=='')
				$sort=$this->input->post('datatable[pagination][sort]');

			//mulai fetching data
			$data = array();
			$no = 0;
			$list=$this->Md_vwsiswa->getDatatables();

			foreach ($list as $row) {
				$no++;
				$arr = array();
				$arr['number'] = ($perpage*($page-1))+$no;
				$arr['nis'] = $row->nis;
				$arr['nama'] = $row->nama;
				$arr['kelas'] = $row->kelas;
				$arr['alamat'] = $row->alamat;
				$arr['telepon'] = $row->telepon;
				if ($row->status_siswa=='A')$row->status_siswa="Aktif";
				$arr['status_siswa'] = $row->status_siswa;
				$data[] = $arr;
			}

			$meta=array();
			$meta['page']=$page;
			$meta['pages']=$pages;
			$meta['perpage']=$perpage;
			$meta['total']=$total;
			$meta['sort']=$sort;
			$meta['field']=$field;
			$output = array(
							"meta" => $meta,
							"data" => $data
					);
			//output to json format
			echo json_encode($output);
		}else{
			$this->load->view('index', $page_data);
		}
	}

	/** *Data Dosen* **/
	public function data_dosen($param1='',$param2='')
	{
		if ($this->session->userdata('login_type') != 'admin')
				$this->logout();

		$date = new DateTime();
		$id=$this->session->userdata('idsys');

		$page_data['page_action'] = 'list';
		$page_data['page_title'] = 'Data Dosen';
		$page_data['page_access'] = "admin";
		$page_data['page_name'] = 'data_dosen';
		$page_data['page_now'] = 'Data Induk';

		if($param1 =='fetch'){
				$total=$this->Md_vwdosen->countFiltered();
				$page=intval($this->input->post('datatable[pagination][page]'));
			if($page<1)
				$page=1;

			$perpage=intval($this->input->post('datatable[pagination][perpage]'));
			$pages= intval($total/$perpage);
			$field=$this->input->post('datatable[sort][field]');

			if($field=='')
				$field=$this->input->post('datatable[pagination][field]');

			$sort=$this->input->post('datatable[sort][sort]');

			if($sort=='')
				$sort=$this->input->post('datatable[pagination][sort]');

			//mulai fetching data
			$data = array();
			$no = 0;
			$list=$this->Md_vwdosen->getDatatables();
			//$prodi= $this->Md_vwprodi->getProdiAll();

			foreach ($list as $row) {
				$no++;
				$arr = array();
				$arr['number'] = ($perpage*($page-1))+$no;
				$arr['nip'] = $row->nip;
				$arr['nama'] = $row->nama;
				$arr['kelas'] = $row->kelas;
				$arr['jabatan'] = "-";
				$arr['status'] = $row->status;
				$data[] = $arr;
			}

			$meta=array();
			$meta['page']=$page;
			$meta['pages']=$pages;
			$meta['perpage']=$perpage;
			$meta['total']=$total;
			$meta['sort']=$sort;
			$meta['field']=$field;
			$output = array(
							"meta" => $meta,
							"data" => $data
					);
			//output to json format
			echo json_encode($output);
		}else{
			$this->load->view('index', $page_data);
		}
	}

	/** *Data Anggota* **/

	public function data_anggota($param1='',$param2='',$param3='')
	{
		if ($this->session->userdata('login_type') != 'admin')
				$this->logout();

		$date = new DateTime();
		$id=$this->session->userdata('idsys');

		$page_data['page_action'] = 'list';
		$page_data['page_title'] = 'Data Anggota';
		$page_data['page_access'] = "admin";
		$page_data['page_name'] = 'data_anggota';
		$page_data['page_now'] = 'Data Induk';
		$page_data['jenis']='mahasiswa';
		if($this->input->post('datatable[query][jenis]')=='dosen')$page_data['jenis']='dosen';
		if($this->input->post('datatable[query][jenis]')=='anggota+luar')$page_data['jenis']='anggota+luar';
		if ($param1=='export' && $param2!='') {

				$jenis='anggota+luar';
				$search='';

				if($param2=='excel'){
					$jenis=$this->input->post('epilih');
					$search=$this->input->post('esearch');
				}elseif($param2=='web'){
					$jenis=$this->input->post('wpilih');
					$search=$this->input->post('wsearch');
				}
			if($param2=='excel'|| $param2 =='web')$page_data['export'] = $param2;
			$list=$this->Md_vwanggota->getLaporan($jenis,$search);
			$page_data['title'] = 'Data_Anggota';
			$page_data['data'] = $list;
				$this->load->view('admin/data_anggota', $page_data);
		}else if ($param1=='get' && $param2!='' && $param3!='') {
			$data = $this->Md_vwanggota->getAnggotaById($param2,$param3);
			echo json_encode($data);
		}else if($param1 =='fetch'){
				$total=$this->Md_vwanggota->countFiltered();
				$page=$this->input->post('datatable[pagination][page]');
			if($page<1)
				$page=1;

			$perpage=10;
			$pages=$total/$perpage;
			$field=$this->input->post('datatable[sort][field]');

			if($field=='')
				$field=$this->input->post('datatable[pagination][field]');

			$sort=$this->input->post('datatable[sort][sort]');

			if($sort=='')
				$sort=$this->input->post('datatable[pagination][sort]');

			//mulai fetching data
			$data = array();
			$no = 0;
			$list=$this->Md_vwanggota->getDatatables();
			//$prodi= $this->Md_vwprodi->getProdiAll();

			foreach ($list as $row) {
				$no++;
				$arr = array();
				if($this->input->post('datatable[query][jenis]')=='dosen'){
					$arr['nis1'] = $row->nip;
					$arr['nis'] = $row->nip;
					$arr['kelas'] = '';
					if ($row->status==1){
						$status="Aktif";
					}else{
						$status="Tidak Aktif";
					}
					$arr['status'] = $status;

				}else if($this->input->post('datatable[query][jenis]')=='anggota+luar'){
					$arr['nis1'] = $row->noid;
					$arr['nis'] = $row->noid;
					$arr['kelas'] = '';
					$arr['status'] ='';
				}else{
					$arr['nis1'] = $row->nis;
					$arr['nis'] = $row->nis;
					$arr['kelas'] = $row->kelas;
					if ($row->status==1){
						$status="Aktif";
					}else{
						$status="Tidak Aktif";
					}
					$arr['status'] = $status;

				}
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['nama'] = $row->nama;
					$arr['berlaku'] = $row->berlaku_sampai;

				$data[] = $arr;
			}

			$meta=array();
			$meta['page']=$page;
			$meta['pages']=$pages;
			$meta['perpage']=$perpage;
			$meta['total']=$total;
			$meta['sort']=$sort;
			$meta['field']=$field;
			$output = array(
							"meta" => $meta,
							"data" => $data
					);
			//output to json format
			echo json_encode($output);
		}
		else if ($param1=='cetak_kartu' && $param2 !='') {
			$data = array();
			$data_final = $this->input->post('final');
			// var_dump($data_final);die;
			for ($i=0; $i < sizeof($data_final); $i++) {
				if($param2=='mahasiswa'){
				array_push($data,$this->Md_vwsiswa->getKartuSiswaById($data_final[$i]));
				}else if($param2=='dosen'){
				array_push($data,$this->Md_vwdosen->getKartuDosenById($data_final[$i]));
				}else{
				array_push($data,$this->Md_siperpus_anggota_luar->getKartuAnggotaById($data_final[$i]));
				}
			}
			echo json_encode($data);
		}
		else{
			$this->load->view('index', $page_data);
		}
	}

	/** *Data Anggota Luar* **/
	public function data_anggota_luar($param1='',$param2='')
	{
		if ($this->session->userdata('login_type') != 'admin')
				$this->logout();

		$date = new DateTime();
		$id=$this->session->userdata('idsys');

		$page_data['page_action'] = 'list';
		$page_data['page_title'] = 'Data Anggota Luar';
		$page_data['page_access'] = "admin";
		$page_data['page_name'] = 'data_anggota_luar';
		$page_data['page_now'] = 'Data Induk';

		if ($param1 == 'submit') {
			$data = array('noid' => $this->input->post('noid'),
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
			if($data['noid']=='' || $data['nama']==''){
				//Empty Field
				$this->session->set_flashdata('alert', 'alert-danger');
				$this->session->set_flashdata('flash_message', 'Empty Field');
				echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Empty Field"));
			}else{
				$buku=$this->Md_siperpus_anggota_luar->getAnggotaLuarById($this->input->post('noid'));
				if($buku){
					//Id duplicate
					$this->session->set_flashdata('alert', 'alert-danger');
					$this->session->set_flashdata('flash_message', 'ID telah ada, data anggota luar gagal dibuat');
					echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "ID telah ada, data anggota luar gagal dibuat"));
					//redirect(base_url() . 'admin/klasifikasi_buku/tambah', 'refresh');
				}else{
					$this->Md_siperpus_anggota_luar->addKAnggotaLuar($data);
					$this->session->set_flashdata('alert', 'alert-focus');
					$this->session->set_flashdata('flash_message', 'Data Angota Luar Sukses dibuat');
					echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Data Angota Luar Sukses dibuat"));
					}
				}
			}else if ($param1 == 'update') {
				$id1=$this->input->post('id1');
				$data = array('noid' => $this->input->post('noid'),
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
				if($data['noid']=='' || $data['nama']==''){
					//Empty Field
					$this->session->set_flashdata('alert', 'alert-danger');
					$this->session->set_flashdata('flash_message', 'Empty Field');
					echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Empty Field"));
				}else{
					$this->Md_siperpus_anggota_luar->updateAnggotaLuar($id1, $data);
					$this->session->set_flashdata('alert', 'alert-focus');
					$this->session->set_flashdata('flash_message', 'Anggota Luar Sukses diedit');
					echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Anggota Luar Sukses diedit"));
				}
			}else if ($param1=='hapus' && $param2 !='') {
				$this->Md_siperpus_anggota_luar->hapusAnggotaLuar($param2);
				$this->session->set_flashdata('alert', 'alert-focus');
				$this->session->set_flashdata('flash_message', 'Anggota Luar Sukses Dihapus');
				echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Anggota Luar Sukses Dihapus"));
			}else if($param1 =='edit' && $param2 !=''){
				$data = $this->Md_siperpus_anggota_luar->getAnggotaLuarById($param2);
				echo json_encode($data);
			}else if($param1 =='fetch'){
					$total=$this->Md_siperpus_anggota_luar->countFiltered();
					$page=intval($this->input->post('datatable[pagination][page]'));
				if($page<1)
					$page=1;

				$perpage=intval($this->input->post('datatable[pagination][perpage]'));
				$pages= intval($total/$perpage);
				$field=$this->input->post('datatable[sort][field]');

				if($field=='')
					$field=$this->input->post('datatable[pagination][field]');

				$sort=$this->input->post('datatable[sort][sort]');

				if($sort=='')
					$sort=$this->input->post('datatable[pagination][sort]');

				//mulai fetching data
				$data = array();
				$no = 0;
				$list=$this->Md_siperpus_anggota_luar->getDatatables();
				//$prodi= $this->Md_vwprodi->getProdiAll();

				foreach ($list as $row) {
					$no++;
					$arr = array();
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['noid'] = $row->noid;
					$arr['nama'] = $row->nama;
					$arr['instansi_asal_nama'] = $row->instansi_asal_nama;
					$data[] = $arr;
				}

				$meta=array();
				$meta['page']=$page;
				$meta['pages']=$pages;
				$meta['perpage']=$perpage;
				$meta['total']=$total;
				$meta['sort']=$sort;
				$meta['field']=$field;
				$output = array(
								"meta" => $meta,
								"data" => $data
						);
				//output to json format
				echo json_encode($output);
		}else{
			$this->load->view('index', $page_data);
		}
	}

	/** *Data Klasifikasi* **/
    function klasifikasi_buku($param1 = '', $param2 = '', $param3 = '')
		{
			if ($this->session->userdata('login_type') != 'admin') $this->logout();

			$date = new DateTime();
			$id=$this->session->userdata('idsys');
	    $page_data['page_action'] = 'list';
	    $page_data['page_title'] = 'Data Klasifikasi Buku';
			$page_data['page_access'] = "admin";
			$page_data['page_name'] = 'klasifikasi_buku';
			$page_data['page_now'] = 'Data Referensi';

			if ($param1 == 'submit') {
				$data['id'] = $this->input->post('id');
				$data['nama'] = $this->input->post('nama');
				if($data['id']=='' || $data['nama']==''){
					//Empty Field
					$this->session->set_flashdata('alert', 'alert-danger');
					$this->session->set_flashdata('flash_message', 'Empty Field');
					echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Empty Field"));
					//redirect(base_url() . 'admin/klasifikasi_buku/tambah', 'refresh');
				}else{
					$buku=$this->Md_siperpus_klasifikasi->getKlasifikasiById($this->input->post('id'));
					if($buku){
						//Id duplicate
						$this->session->set_flashdata('alert', 'alert-danger');
						$this->session->set_flashdata('flash_message', 'ID telah dipakai, klasifikasi buku gagal dibuat');
						echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "ID telah dipakai, Klasifikasi buku gagal dibuat"));
						//redirect(base_url() . 'admin/klasifikasi_buku/tambah', 'refresh');
					}else{
						$this->Md_siperpus_klasifikasi->addKlasifikasi($data);
						$this->session->set_flashdata('alert', 'alert-focus');
						$this->session->set_flashdata('flash_message', 'Klasifikasi Buku Sukses dibuat');
						echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Klasifikasi Buku Sukses dibuat"));
						//redirect(base_url() . 'admin/klasifikasi_buku', 'refresh');
						}
					}
	      }else if ($param1 == 'update') {
					$id1=$this->input->post('id1');
					$data['id'] = $this->input->post('id');
					$data['nama'] = $this->input->post('nama');
					if($data['id']=='' || $data['nama']==''){
						//Empty Field
						$this->session->set_flashdata('alert', 'alert-danger');
						$this->session->set_flashdata('flash_message', 'Empty Field');
						echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Empty Field"));
					}else{
						$this->Md_siperpus_klasifikasi->updateKlasifikasi($id1, $data);
						$this->session->set_flashdata('alert', 'alert-focus');
						$this->session->set_flashdata('flash_message', 'Klasifikasi Buku Sukses diedit');
						echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Klasifikasi Buku Sukses diedit"));
					}
	      }else if ($param1=='hapus' && $param2 !='') {
					$this->Md_siperpus_klasifikasi->hapusKlasifikasi($param2);
					$this->session->set_flashdata('alert', 'alert-focus');
					$this->session->set_flashdata('flash_message', 'Klasifikasi Buku Sukses Dihapus');
					echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Klasifikasi Buku Sukses Dihapus"));
				}else if($param1 =='edit' && $param2 !=''){
					$data = $this->Md_siperpus_klasifikasi->getKlasifikasiById($param2);
					echo json_encode($data);
				}else if($param1 =='fetch'){
						$total=$this->Md_siperpus_klasifikasi->countFiltered();
						$page=intval($this->input->post('datatable[pagination][page]'));
					if($page<1)
						$page=1;

					$perpage=intval($this->input->post('datatable[pagination][perpage]'));
					$pages= intval($total/$perpage);
					$field=$this->input->post('datatable[sort][field]');

					if($field=='')
						$field=$this->input->post('datatable[pagination][field]');

					$sort=$this->input->post('datatable[sort][sort]');

					if($sort=='')
						$sort=$this->input->post('datatable[pagination][sort]');

					$data = array();
					$no = 0;
					$list=$this->Md_siperpus_klasifikasi->getDatatables();

					foreach ($list as $row) {
						$no++;
						$arr = array();
						$arr['number'] = ($perpage*($page-1))+$no;
						$arr['id'] = $row->id;
						$arr['nama'] = $row->nama;
						$data[] = $arr;
					}
					$meta=array();
					$meta['page']=$page;
					$meta['pages']=$pages;
					$meta['perpage']=$perpage;
					$meta['total']=$total;
					$meta['sort']=$sort;
					$meta['field']=$field;
					$output = array(
									"meta" => $meta,
									"data" => $data
							);
					//output to json format
					echo json_encode($output);

				}else{
					$this->load->view('index', $page_data);
				}
		}
	/** Konfigurasi **/
    function konfigurasi($param1 = '', $param2 = '', $param3 = '') {

        if ($this->session->userdata('login_type') != 'admin')

            $this->logout();

		$date = new DateTime();

		$id=$this->session->userdata('idsys');

        $page_data['page_action'] = 'list';
        $page_data['data']=$this->Md_siperpus_setting->getSettingAll();
		$page_data['data_kategori']=$this->Md_siperpus_kategori_buku->getKategoriBukuAll();
        $page_data['page_title'] = 'Pengaturan Program';
		if ($param1 == 'update') {

			$setting1= $this->input->post('setting1');
			$setting2= $this->input->post('setting2');
			$setting3= $this->input->post('setting3');
			$setting4= $this->input->post('setting4');
			$setting5= $this->input->post('setting5');
			$setting6= $this->input->post('setting6');
			$setting7= $this->input->post('setting7');
			$setting8= $this->input->post('setting8');
			$setting9= $this->input->post('setting9');
			$setting10= $this->input->post('setting10');
			$setting1kode= $this->input->post('setting1kode');
			$setting2kode= $this->input->post('setting2kode');
			$setting3kode= $this->input->post('setting3kode');
			$setting4kode= $this->input->post('setting4kode');
			$setting5kode= $this->input->post('setting5kode');
			$setting6kode= $this->input->post('setting6kode');
			$setting7kode= $this->input->post('setting7kode');
			$setting8kode= $this->input->post('setting8kode');
			$setting9kode= $this->input->post('setting9kode');

			if($setting1 !=''&& $setting1kode !=''){
				$data['valsetting']=$setting1;
				$this->Md_siperpus_setting->updateSetting($setting1kode,$data);
			}
			if($setting2 !=''&& $setting2kode !=''){
				$data['valsetting']=$setting2;
				$this->Md_siperpus_setting->updateSetting($setting2kode,$data);
			}
			if($setting3 !=''&& $setting3kode !=''){
				$data['valsetting']=$setting3;
				$this->Md_siperpus_setting->updateSetting($setting3kode,$data);
			}
			if($setting4 !=''&& $setting4kode !=''){
				$data['valsetting']=$setting4;
				$this->Md_siperpus_setting->updateSetting($setting4kode,$data);
			}
			if($setting5 !=''&& $setting5kode !=''){
				$data['valsetting']=$setting5;
				$this->Md_siperpus_setting->updateSetting($setting5kode,$data);
			}
			if($setting6 !=''&& $setting6kode !=''){
				$data['valsetting']=$setting6;
				$this->Md_siperpus_setting->updateSetting($setting6kode,$data);
			}
			if($setting7 !=''&& $setting7kode !=''){
				$data['valsetting']=$setting7;
				$this->Md_siperpus_setting->updateSetting($setting7kode,$data);
			}
			if($setting8 !=''&& $setting8kode !=''){
				$data['valsetting']=$setting8;
				$this->Md_siperpus_setting->updateSetting($setting8kode,$data);
			}
			if($setting9 !=''&& $setting9kode !=''){
				$data['valsetting']=$setting9;
				$this->Md_siperpus_setting->updateSetting($setting9kode,$data);
			}


				$this->session->set_flashdata('alert', 'alert-warning');

				$this->session->set_flashdata('flash_message', 'Update Setting Sukses');

				redirect(base_url() . 'admin/konfigurasi/', 'refresh');


        }
        $page_data['page_access'] = "admin";
        $page_data['page_name'] = 'konfigurasi';
        $page_data['page_now'] = 'Konfigurasi Transaksi';
        $this->load->view('index', $page_data);

    }
	public function logout(){

	$date = new DateTime();

		$id=$this->session->userdata('idsys');

		if(!empty($id)){
				/*
			$user=$this->Md_user->getuserById($id);
			$log['idsys']=$id;
			$log['tgl']=$date->format("Y-m-d H:i:s");
			$log['jenis_log']='admin';
			$log['jenis_akses']='LogOut';
			$log['status']=1;
			$log['keterangan']=$user[0]->username.' Melakukan LogOut';
			$log['IP']=$_SERVER['REMOTE_ADDR'];
			$this->Md_log->addLogBaru($log);
			*/
		}
		$this->session->sess_destroy();

	    redirect(base_url() . 'home', 'refresh');

	}

	/**Kategori Buku**/

	public function kategori_buku($param1='',$param2='')
		{
			if ($this->session->userdata('login_type') != 'admin')
					$this->logout();

			$date = new DateTime();
			$id=$this->session->userdata('idsys');

			$page_data['page_action'] = 'list';
			$page_data['page_title'] = 'Data Kategori Buku';
			$page_data['page_access'] = "admin";
			$page_data['page_name'] = 'kategori_buku';
			$page_data['page_now'] = 'Data Referensi';

			if ($param1 == 'submit') {
				$data['nmkategori'] = $this->input->post('nmkategori');
				if($data['nmkategori']==''){
					//Empty Field
					$this->session->set_flashdata('alert', 'alert-danger');
					$this->session->set_flashdata('flash_message', 'Empty Field');
					echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Empty Field"));
				}else{
					$buku=$this->Md_siperpus_kategori_buku->getKategoriById($this->input->post('idkategori'));
					if($buku){
						//Id duplicate
						$this->session->set_flashdata('alert', 'alert-danger');
						$this->session->set_flashdata('flash_message', 'ID telah dipakai, Kategori buku gagal dibuat');
						echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "ID telah dipakai, Kategori buku gagal dibuat"));
					}else{
						$this->Md_siperpus_kategori_buku->addKategoriBuku($data);
						$this->session->set_flashdata('alert', 'alert-focus');
						$this->session->set_flashdata('flash_message', 'Kategori Buku Sukses dibuat');
						echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Kategori Buku Sukses dibuat"));
						}
					}
	      }else if ($param1 == 'update') {
					$data['idkategori'] = $this->input->post('idkategori');
					$data['nmkategori'] = $this->input->post('nmkategori');
					if($data['idkategori']=='' || $data['nmkategori']==''){
						//Empty Field
						$this->session->set_flashdata('alert', 'alert-danger');
						$this->session->set_flashdata('flash_message', 'Empty Field');
						echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Empty Field"));
					}else{
						$this->Md_siperpus_kategori_buku->updateKategoriBuku($data['idkategori'], $data);
						$this->session->set_flashdata('alert', 'alert-focus');
						$this->session->set_flashdata('flash_message', 'Kategori Buku Sukses diedit');
						echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Kategori Buku Sukses diedit"));
					}
	      }else if ($param1=='hapus' && $param2 !='') {
					$this->Md_siperpus_kategori_buku->hapusKategoriBuku($param2);
					$this->session->set_flashdata('alert', 'alert-focus');
					$this->session->set_flashdata('flash_message', 'Kategori Buku Sukses Dihapus');
					echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Kategori Buku Sukses Dihapus"));
				}else if($param1 =='edit' && $param2 !=''){
					$data = $this->Md_siperpus_kategori_buku->getKategoriById($param2);
					echo json_encode($data);
				}else if($param1 =='fetch'){
					$total=$this->Md_siperpus_kategori_buku->countFiltered();
						$page=intval($this->input->post('datatable[pagination][page]'));
					if($page<1)
						$page=1;

					$perpage=intval($this->input->post('datatable[pagination][perpage]'));
					$pages= intval($total/$perpage);
					$field=$this->input->post('datatable[sort][field]');

					if($field=='')
						$field=$this->input->post('datatable[pagination][field]');

					$sort=$this->input->post('datatable[sort][sort]');

					if($sort=='')
						$sort=$this->input->post('datatable[pagination][sort]');

					$data = array();
					$no = 0;
					$list=$this->Md_siperpus_kategori_buku->getDatatables();

					foreach ($list as $row) {
						$no++;
						$arr = array();
						$arr['number'] = ($perpage*($page-1))+$no;
						$arr['idkategori'] = $row->idkategori;
						$arr['nmkategori'] = $row->nmkategori;
						$data[] = $arr;
					}
					$meta=array();
					$meta['page']=$page;
					$meta['pages']=$pages;
					$meta['perpage']=$perpage;
					$meta['total']=$total;
					$meta['sort']=$sort;
					$meta['field']=$field;
					$output = array(
									"meta" => $meta,
									"data" => $data
							);
					//output to json format
					echo json_encode($output);

				}else{
					$this->load->view('index', $page_data);
				}
		}

		/** *Bahasa* **/

		public function bahasa($param1='',$param2='')
		{
			if ($this->session->userdata('login_type') != 'admin')
					$this->logout();

			$date = new DateTime();
			$id=$this->session->userdata('idsys');

			$page_data['page_action'] = 'list';
			$page_data['page_title'] = 'Data Bahasa';
			$page_data['page_access'] = "admin";
			$page_data['page_name'] = 'bahasa';
			$page_data['page_now'] = 'Data Referensi';

			if ($param1 == 'submit') {
				$data['id'] = $this->input->post('id');
				$data['nama'] = $this->input->post('nama');
				$data['no_urut'] = $this->input->post('no_urut');
				if($data['id']=='' || $data['nama']==''){
					//Empty Field
					$this->session->set_flashdata('alert', 'alert-danger');
					$this->session->set_flashdata('flash_message', 'Empty Field');
					echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Empty Field"));
				}else{
					$buku=$this->Md_siperpus_bahasa->getBahasaById($this->input->post('id'));
					if($buku){
						//Id duplicate
						$this->session->set_flashdata('alert', 'alert-danger');
						$this->session->set_flashdata('flash_message', 'ID telah dipakai, bahasa buku gagal dibuat');
						echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "ID telah dipakai, bahasa buku gagal dibuat"));
					}else{
						$this->Md_siperpus_bahasa->addBahasa($data);
						$this->session->set_flashdata('alert', 'alert-focus');
						$this->session->set_flashdata('flash_message', 'Kategori Buku Sukses dibuat');
						echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Bahasa Buku Sukses dibuat"));
						}
					}
	      }else if ($param1 == 'update') {
					$id1 = $this->input->post('id1');
					$data['id'] = $this->input->post('id');
					$data['nama'] = $this->input->post('nama');
					$data['no_urut'] = $this->input->post('no_urut');
					if($data['id']=='' || $data['nama']==''){
						//Empty Field
						$this->session->set_flashdata('alert', 'alert-danger');
						$this->session->set_flashdata('flash_message', 'Empty Field');
						echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Empty Field"));
					}else{
						$this->Md_siperpus_bahasa->updateBahasa($id1, $data);
						$this->session->set_flashdata('alert', 'alert-focus');
						$this->session->set_flashdata('flash_message', 'Bahasa Buku Sukses diedit');
						echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Bahasa Buku Sukses diedit"));
					}
	      }else if ($param1=='hapus' && $param2 !='') {
					$this->Md_siperpus_bahasa->hapusBahasa($param2);
					$this->session->set_flashdata('alert', 'alert-focus');
					$this->session->set_flashdata('flash_message', 'Bahasa Buku Sukses Dihapus');
					echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Bahasa Buku Sukses Dihapus"));
				}else if($param1 =='edit' && $param2 !=''){
					$data = $this->Md_siperpus_bahasa->getBahasaById($param2);
					echo json_encode($data);
				}else if($param1 =='fetch'){
						$total=$this->Md_siperpus_bahasa->countFiltered();
						$page=intval($this->input->post('datatable[pagination][page]'));
					if($page<1)
						$page=1;

					$perpage=intval($this->input->post('datatable[pagination][perpage]'));
					$pages= intval($total/$perpage);
					$field=$this->input->post('datatable[sort][field]');

					if($field=='')
						$field=$this->input->post('datatable[pagination][field]');

					$sort=$this->input->post('datatable[sort][sort]');

					if($sort=='')
						$sort=$this->input->post('datatable[pagination][sort]');

					$data = array();
					$no = 0;
					$list=$this->Md_siperpus_bahasa->getDatatables();

					foreach ($list as $row) {
						$no++;
						$arr = array();
						$arr['number'] = ($perpage*($page-1))+$no;
						$arr['id'] = $row->id;
						$arr['nama'] = $row->nama;
						$arr['no_urut'] = $row->no_urut;
						$data[] = $arr;
					}
					$meta=array();
					$meta['page']=$page;
					$meta['pages']=$pages;
					$meta['perpage']=$perpage;
					$meta['total']=$total;
					$meta['sort']=$sort;
					$meta['field']=$field;
					$output = array(
									"meta" => $meta,
									"data" => $data
							);
					//output to json format
					echo json_encode($output);

				}else{
					$this->load->view('index', $page_data);
				}
		}
		/** *Asal Buku* **/

		public function asal_buku($param1='',$param2='')
		{
			if ($this->session->userdata('login_type') != 'admin')
					$this->logout();

			$date = new DateTime();
			$id=$this->session->userdata('idsys');

			$page_data['page_action'] = 'list';
			$page_data['page_title'] = 'Data Asal Buku';
			$page_data['page_access'] = "admin";
			$page_data['page_name'] = 'asal_buku';
			$page_data['page_now'] = 'Data Referensi';

			if ($param1 == 'submit') {
				$data['id'] = $this->input->post('id');
				$data['nama'] = $this->input->post('nama');
				if($data['id']=='' || $data['nama']==''){
					//Empty Field
					$this->session->set_flashdata('alert', 'alert-danger');
					$this->session->set_flashdata('flash_message', 'Empty Field');
					echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Empty Field"));
				}else{
					$buku=$this->Md_siperpus_asal_buku->getAsalBukuById($this->input->post('id'));
					if($buku){
						//Id duplicate
						$this->session->set_flashdata('alert', 'alert-danger');
						$this->session->set_flashdata('flash_message', 'ID telah dipakai, Asal buku gagal dibuat');
						echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "ID telah dipakai, Asal buku gagal dibuat"));
					}else{
						$this->Md_siperpus_asal_buku->addAsalBuku($data);
						$this->session->set_flashdata('alert', 'alert-focus');
						$this->session->set_flashdata('flash_message', 'Asal Buku Sukses dibuat');
						echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Asal Buku Sukses dibuat"));
						}
					}
	      }else if ($param1 == 'update') {
					$id1 = $this->input->post('id1');
					$data['id'] = $this->input->post('id');
					$data['nama'] = $this->input->post('nama');
					if($data['id']=='' || $data['nama']==''){
						//Empty Field
						$this->session->set_flashdata('alert', 'alert-danger');
						$this->session->set_flashdata('flash_message', 'Empty Field');
						echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Empty Field"));
					}else{
						$this->Md_siperpus_asal_buku->updateAsalBuku($id1, $data);
						$this->session->set_flashdata('alert', 'alert-focus');
						$this->session->set_flashdata('flash_message', 'Asal Buku Sukses diedit');
						echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Asal Buku Sukses diedit"));
					}
	      }else if ($param1=='hapus' && $param2 !='') {
					$this->Md_siperpus_asal_buku->hapusAsalBuku($param2);
					$this->session->set_flashdata('alert', 'alert-focus');
					$this->session->set_flashdata('flash_message', 'Asal Buku Sukses Dihapus');
					echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Asal Buku Sukses Dihapus"));
				}else if($param1 =='edit' && $param2 !=''){
					$data = $this->Md_siperpus_asal_buku->getAsalBukuById($param2);
					echo json_encode($data);
				}else if($param1 =='fetch'){
						$total=$this->Md_siperpus_asal_buku->countFiltered();
						$page=intval($this->input->post('datatable[pagination][page]'));
					if($page<1)
						$page=1;

					$perpage=intval($this->input->post('datatable[pagination][perpage]'));
					$pages= intval($total/$perpage);
					$field=$this->input->post('datatable[sort][field]');

					if($field=='')
						$field=$this->input->post('datatable[pagination][field]');

					$sort=$this->input->post('datatable[sort][sort]');

					if($sort=='')
						$sort=$this->input->post('datatable[pagination][sort]');

					$data = array();
					$no = 0;
					$list=$this->Md_siperpus_asal_buku->getDatatables();

					foreach ($list as $row) {
						$no++;
						$arr = array();
						$arr['number'] = ($perpage*($page-1))+$no;
						$arr['id'] = $row->id;
						$arr['nama'] = $row->nama;
						$data[] = $arr;
					}
					$meta=array();
					$meta['page']=$page;
					$meta['pages']=$pages;
					$meta['perpage']=$perpage;
					$meta['total']=$total;
					$meta['sort']=$sort;
					$meta['field']=$field;
					$output = array(
									"meta" => $meta,
									"data" => $data
							);
					//output to json format
					echo json_encode($output);

				}else{
					$this->load->view('index', $page_data);
				}
		}
		/** *Penerbit* **/

		public function penerbit($param1='',$param2='')
		{
			if ($this->session->userdata('login_type') != 'admin')
					$this->logout();

			$date = new DateTime();
			$id=$this->session->userdata('idsys');

			$page_data['page_action'] = 'list';
			$page_data['page_title'] = 'Data Penerbit';
			$page_data['page_access'] = "admin";
			$page_data['page_name'] = 'penerbit';
			$page_data['page_now'] = 'Data Referensi';

			if ($param1 == 'submit') {
				$data = array('nama_penerbit' => $this->input->post('nama_penerbit'),
											'kota' => $this->input->post('kota')
								);
				if($data['nama_penerbit']=='' || $data['kota']==''){
					//Empty Field
					$this->session->set_flashdata('alert', 'alert-danger');
					$this->session->set_flashdata('flash_message', 'Empty Field');
					echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Empty Field"));
				}else{
					$buku=$this->Md_siperpus_penerbit->getPenerbitByNama($this->input->post('nama_penerbit'));
					if($buku){
						//Id duplicate
						$this->session->set_flashdata('alert', 'alert-danger');
						$this->session->set_flashdata('flash_message', 'Nama Penerbit Sudah Ada, Penerbit gagal dibuat');
						echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Nama Penerbit Sudah Ada, Penerbit gagal dibuat"));
					}else{
						$this->Md_siperpus_penerbit->addPenerbit($data);
						$this->session->set_flashdata('alert', 'alert-focus');
						$this->session->set_flashdata('flash_message', 'Penerbit Sukses dibuat');
						echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Penerbit Sukses dibuat"));
						}
					}
	      }else if ($param1 == 'update') {
					$id1 = $this->input->post('id1');
					$data = array('nama_penerbit' => $this->input->post('nama_penerbit'),
												'kota' => $this->input->post('kota')
									);
					if( $data['nama_penerbit']=='' || $data['kota']==''){
						//Empty Field
						$this->session->set_flashdata('alert', 'alert-danger');
						$this->session->set_flashdata('flash_message', 'Empty Field');
						echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Empty Field"));
					}else{
						$this->Md_siperpus_penerbit->updatePenerbit($id1, $data);
						$this->session->set_flashdata('alert', 'alert-focus');
						$this->session->set_flashdata('flash_message', 'Penerbit Sukses diedit');
						echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Penerbit Sukses diedit"));
					}
	      }else if ($param1=='hapus' && $param2 !='') {
					$this->Md_siperpus_penerbit->hapusPenerbit($param2);
					$this->session->set_flashdata('alert', 'alert-focus');
					$this->session->set_flashdata('flash_message', 'Penerbit Sukses Dihapus');
					echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Penerbit Sukses Dihapus"));
				}else if($param1 =='edit' && $param2 !=''){
					$data = $this->Md_siperpus_penerbit->getPenerbitById($param2);
					echo json_encode($data);
				}else if($param1 =='fetch'){
						$total=$this->Md_siperpus_penerbit->countFiltered();
						$page=intval($this->input->post('datatable[pagination][page]'));
					if($page<1)
						$page=1;

					$perpage=intval($this->input->post('datatable[pagination][perpage]'));
					$pages= intval($total/$perpage);
					$field=$this->input->post('datatable[sort][field]');

					if($field=='')
						$field=$this->input->post('datatable[pagination][field]');

					$sort=$this->input->post('datatable[sort][sort]');

					if($sort=='')
						$sort=$this->input->post('datatable[pagination][sort]');

					$data = array();
					$no = 0;
					$list=$this->Md_siperpus_penerbit->getDatatables();

					foreach ($list as $row) {
						$no++;
						$arr = array();
						$arr['number'] = ($perpage*($page-1))+$no;
						$arr['kd_penerbit'] = $row->kd_penerbit;
						$arr['nama_penerbit'] = $row->nama_penerbit;
						$arr['kota'] = $row->kota;
						$data[] = $arr;
					}
					$meta=array();
					$meta['page']=$page;
					$meta['pages']=$pages;
					$meta['perpage']=$perpage;
					$meta['total']=$total;
					$meta['sort']=$sort;
					$meta['field']=$field;
					$output = array(
									"meta" => $meta,
									"data" => $data
							);
					//output to json format
					echo json_encode($output);

				}else{
					$this->load->view('index', $page_data);
				}
		}
		/** *Keperluan SPPBL* **/

		public function keperluan_sbppl($param1='',$param2='')
		{
			if ($this->session->userdata('login_type') != 'admin')
					$this->logout();

			$date = new DateTime();
			$id=$this->session->userdata('idsys');

			$page_data['page_action'] = 'list';
			$page_data['page_title'] = 'Keperluan SBPPL';
			$page_data['page_access'] = "admin";
			$page_data['page_name'] = 'keperluan_sbppl';
			$page_data['page_now'] = 'Data Referensi';

			if ($param1 == 'submit') {
				$data['nama'] = $this->input->post('nama');
				$data['no_urut'] = $this->input->post('no_urut');
				if($data['nama']=='' || $data['no_urut'] == ''){
					//Empty Field
					$this->session->set_flashdata('alert', 'alert-danger');
					$this->session->set_flashdata('flash_message', 'Empty Field');
					echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Empty Field"));
				}else{
					$buku=$this->Md_siperpus_keperluan_sbppl->getSbpplByNama($this->input->post('nama'));
					if($buku){
						//Id duplicate
						$this->session->set_flashdata('alert', 'alert-danger');
						$this->session->set_flashdata('flash_message', 'ID telah dipakai, SBPPL gagal dibuat');
						echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "ID telah dipakai, SBPPL gagal dibuat"));
					}else{
						$this->Md_siperpus_keperluan_sbppl->addSbppl($data);
						$this->session->set_flashdata('alert', 'alert-focus');
						$this->session->set_flashdata('flash_message', 'SBPPL Sukses dibuat');
						echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "SBPPL Sukses dibuat"));
						}
					}
	      }else if ($param1 == 'update') {
					$id1 = $this->input->post('id1');
					$data['nama'] = $this->input->post('nama');
					$data['no_urut'] = $this->input->post('no_urut');
					if($data['nama']=='' || $data['no_urut']==''){
						//Empty Field
						$this->session->set_flashdata('alert', 'alert-danger');
						$this->session->set_flashdata('flash_message', 'Empty Field');
						echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Empty Field"));
					}else{
						$this->Md_siperpus_keperluan_sbppl->updateSbppl($id1, $data);
						$this->session->set_flashdata('alert', 'alert-focus');
						$this->session->set_flashdata('flash_message', 'SBPPL Sukses diedit');
						echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "SBPPL Sukses diedit"));
					}
	      }else if ($param1=='hapus' && $param2 !='') {
					$this->Md_siperpus_keperluan_sbppl->hapusSbppl($param2);
					$this->session->set_flashdata('alert', 'alert-focus');
					$this->session->set_flashdata('flash_message', 'SBPPL Sukses Dihapus');
					echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "SBPPL Sukses Dihapus"));
				}else if($param1 =='edit' && $param2 !=''){
					$data = $this->Md_siperpus_keperluan_sbppl->getSbpplById($param2);
					echo json_encode($data);
				}else if($param1 =='fetch'){
					$total=$this->Md_siperpus_keperluan_sbppl->countFiltered();
						$page=intval($this->input->post('datatable[pagination][page]'));
					if($page<1)
						$page=1;

					$perpage=intval($this->input->post('datatable[pagination][perpage]'));
					$pages= intval($total/$perpage);
					$field=$this->input->post('datatable[sort][field]');

					if($field=='')
						$field=$this->input->post('datatable[pagination][field]');

					$sort=$this->input->post('datatable[sort][sort]');

					if($sort=='')
						$sort=$this->input->post('datatable[pagination][sort]');

					$data = array();
					$no = 0;
					$list=$this->Md_siperpus_keperluan_sbppl->getDatatables();

					foreach ($list as $row) {
						$no++;
						$arr = array();
						$arr['number'] = ($perpage*($page-1))+$no;
						$arr['id'] = $row->id;
						$arr['nama'] = $row->nama;
						$arr['no_urut'] = $row->no_urut;
						$data[] = $arr;
					}
					$meta=array();
					$meta['page']=$page;
					$meta['pages']=$pages;
					$meta['perpage']=$perpage;
					$meta['total']=$total;
					$meta['sort']=$sort;
					$meta['field']=$field;
					$output = array(
									"meta" => $meta,
									"data" => $data
							);
					//output to json format
					echo json_encode($output);

				}else{
					$this->load->view('index', $page_data);
				}
		}
		/** *Proram Studi* **/

		public function program_studi($param1='',$param2='')
		{
			if ($this->session->userdata('login_type') != 'admin')
					$this->logout();

			$date = new DateTime();
			$id=$this->session->userdata('idsys');

			$page_data['page_action'] = 'list';
			$page_data['page_title'] = 'Program Studi';
			$page_data['page_access'] = "admin";
			$page_data['page_name'] = 'program_studi';
			$page_data['page_now'] = 'Data Referensi';

			if($param1 =='fetch'){
						$total=$this->Md_vwprodi->countFiltered();
					$page=intval($this->input->post('datatable[pagination][page]'));
				if($page<1)
					$page=1;

				$perpage=intval($this->input->post('datatable[pagination][perpage]'));
				$pages= intval($total/$perpage);
				$field=$this->input->post('datatable[sort][field]');

				if($field=='')
					$field=$this->input->post('datatable[pagination][field]');

				$sort=$this->input->post('datatable[sort][sort]');

				if($sort=='')
					$sort=$this->input->post('datatable[pagination][sort]');

				$data = array();
				$no = 0;
				$list=$this->Md_vwprodi->getDatatables();

				foreach ($list as $row) {
					$no++;
					$arr = array();
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['idmspst'] = $row->idmspst;
					$arr['kodemspst'] = $row->kodemspst;
					$arr['nmmspst'] = $row->nmmspst;
					$data[] = $arr;
				}
				$meta=array();
				$meta['page']=$page;
				$meta['pages']=$pages;
				$meta['perpage']=$perpage;
				$meta['total']=$total;
				$meta['sort']=$sort;
				$meta['field']=$field;
				$output = array(
								"meta" => $meta,
								"data" => $data
						);
				//output to json format
				echo json_encode($output);

			}else{
				$this->load->view('index', $page_data);
			}
		}

		/** *Matakuliah* **/

		public function matakuliah($param1='',$param2='')
		{
			if ($this->session->userdata('login_type') != 'admin')
					$this->logout();

			$date = new DateTime();
			$id=$this->session->userdata('idsys');

			$page_data['page_action'] = 'list';
			$page_data['page_title'] = 'Data Mata Kuliah';
			$page_data['page_access'] = "admin";
			$page_data['page_name'] = 'matakuliah';
			$page_data['page_now'] = 'Data Referensi';

			if($param1 =='fetch'){
					$total=$this->Md_siperpus_matakuliah->countFiltered();
					$page=intval($this->input->post('datatable[pagination][page]'));
				if($page<1)
					$page=1;

				$perpage=intval($this->input->post('datatable[pagination][perpage]'));
				$pages= intval($total/$perpage);
				$field=$this->input->post('datatable[sort][field]');

				if($field=='')
					$field=$this->input->post('datatable[pagination][field]');

				$sort=$this->input->post('datatable[sort][sort]');

				if($sort=='')
					$sort=$this->input->post('datatable[pagination][sort]');

				$data = array();
				$no = 0;
				$list=$this->Md_siperpus_matakuliah->getDatatables();

				foreach ($list as $row) {
					$no++;
					$arr = array();
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['kodetbkmk'] = $row->kodetbkmk;
					$arr['nmtbkmk'] = $row->nmtbkmk;
					$arr['pengajartbkmk'] = $row->pengajartbkmk;
					$arr['gelarpengajartbkmk'] = $row->gelarpengajartbkmk;
					$arr['nmmspst'] = $row->nmmspst;
					$data[] = $arr;
				}
				$meta=array();
				$meta['page']=$page;
				$meta['pages']=$pages;
				$meta['perpage']=$perpage;
				$meta['total']=$total;
				$meta['sort']=$sort;
				$meta['field']=$field;
				$output = array(
								"meta" => $meta,
								"data" => $data
						);
				//output to json format
				echo json_encode($output);

			}else{
				$this->load->view('index', $page_data);
			}
		}

		/**Data Buku**/

		public function data_buku($param1='',$param2='',$param3='',$param4='')
	    {
				if ($this->session->userdata('login_type') != 'admin')

            $this->logout();

		$date = new DateTime();
		$id=$this->session->userdata('idsys');

		$page_data['page_action'] = 'list';
		$page_data['page_title'] = 'Data Data Buku';
		$page_data['page_access'] = "admin";
		$page_data['page_name'] = 'data_buku';
		$page_data['page_now'] = 'Buku & Inventarisasi';
		$page_data['klasifikasi'] = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
		$page_data['kategori'] = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
		$x=str_replace('_','/',$param2);
		$y=str_replace('%20',' ',$param3);

		if ($param1 == 'submit'){
			$data = array(
				'no_klas' => $this->input->post('no_klas') ,
				'ISBN' => $this->input->post('isbn') ,
				'idkategori' => $this->input->post('kategori_buku') ,
				'judul' => $this->input->post('judul') ,
				'cetakkatalog_judulpenggal' => $this->input->post('cetakkatalog_judulpenggal') ,
				'judulasli' => $this->input->post('judulasli') ,
				'allow_review' => $this->input->post('allow_review') ,
				'deskripsi' => $this->input->post('deskripsi') ,
				'penulis' => $this->input->post('penulis') ,
				'penyadur' => $this->input->post('penyadur') ,
				'penerjemah' => $this->input->post('penerjemah') ,
				'penyusun' => $this->input->post('penyusun') ,
				'penyunting' => $this->input->post('penyunting') ,
				'illustrator' => $this->input->post('illustrator') ,
				'editor' => $this->input->post('editor') ,
				'edisi' => $this->input->post('edisi') ,
				'cetakan' => $this->input->post('cetakan') ,
				'kd_penerbit' => $this->input->post('penerbit') ,
				'thn_terbit' => $this->input->post('thn_terbit') ,
				'jilid' => $this->input->post('jilid') ,
				'hlm_romawi' => $this->input->post('hlm_romawi') ,
				'jml_hal' => $this->input->post('jml_hal') ,
				'ilustrasi' => $this->input->post('ilustrasi') ,
				'tabel' => $this->input->post('tabel') ,
				'ukuran_fisik' => $this->input->post('ukuran_fisik') ,
				'bibliografi' => $this->input->post('bibliografi') ,
				'indeks' => $this->input->post('indeks') ,
				'bahasa' => $this->input->post('bahasa') ,
				'no_rak' => $this->input->post('no_rak') ,
				'seri' => $this->input->post('seri') ,
				'tajuk' => $this->input->post('tajuk') ,
				'tajuksubyek' => $this->input->post('tajuksubyek') ,
				'tanggal' => $date->format('Y-m-d H:i:s') ,

				// 'matkul' => $this->input->post('matkul')

			);
			$prodi = $this->input->post('prodi');
			for ($i = 0; $i < sizeof($prodi); $i++)
				{
				$dataBukuProdi = array(
					'no_klas' => $this->input->post('no_klas') ,
					'ISBN' => $this->input->post('isbn') ,
					'idmspst' => $prodi[$i],
					'idkategori'=>$this->input->post('kategori_buku')
				);
				$this->Md_siperpus_buku_prodi->addBukuProdi($dataBukuProdi);
				}

			if ($this->input->post('kategori_buku') == 3)
				{
				$data_kti = array(
					'no_klas_ta' => $this->input->post('no_klas') ,
					'ISBN_ta' => $this->input->post('isbn') ,
					'nis_ta' => $this->input->post('nis_ta') ,
					'tabel_ta' => $this->input->post('tabel_ta') ,
					'lampiran_ta' => $this->input->post('lampiran_ta') ,
					'pembimbing_ta' => $this->input->post('pembimbing_ta') ,
				);
				if ($data_kti['tabel_ta'] == '') $data_kti['tabel_ta'] = 0;
				if ($data_kti['lampiran_ta'] == '') $data_kti['lampiran_ta'] = 0;
				$this->Md_siperpus_data_buku->addDataBukuTa($data_kti);
				}

			$config1 = array(
				'upload_path' => FCPATH . "uploads/covers",
				'allowed_types' => 'gif|jpg|png|jpeg|bmp',
				'max_size' => 1024 * 1000
			);
			$config2 = array(
				'upload_path' => FCPATH . "uploads/files",
				'allowed_types' => 'pdf|doc|docx|xls|xlsx|zip|',
				'max_size' => 1024 * 1000
			);


			if ($data['ilustrasi'] == '') $data['ilustrasi'] = 0;
			if ($data['tabel'] == '') $data['tabel'] = 0;
			if ($data['allow_review'] == '') $data['allow_review'] = 0;
			if ($data['no_klas'] == '' || $data['jml_hal'] == '' || $data['kd_penerbit'] == '' || $data['ukuran_fisik'] == '' || $data['penulis'] == '' || $data['judul'] == '' || $data['tajuksubyek'] == '' || $data['ISBN'] == '' || $data['thn_terbit'] == '')
				{

				// Empty Field

				}
			  else
				{

				// upload image.

				$this->upload->initialize($config1);
				if (!$this->upload->do_upload('gambar'))
					{
						$data['cover'] = '';
						echo $this->upload->display_errors();die;
					}
				  else
					{
						$data_up = $this->upload->data();
						$data['cover'] = $data_up['file_name'];
							$config['image_library'] = 'gd2';
							$config['source_image'] = $data_up['full_path'];
							$config['maintain_ratio'] = TRUE;
							$config['overwrite'] = TRUE;
							$config['width']     = 200;
							$config['height']   = 250;
							$this->image_lib->initialize($config);
							$this->image_lib->resize();
					}

				$this->Md_siperpus_data_buku->addDataBuku($data); //Upload Data

				// upload file

				$this->upload->initialize($config2);
				if (!$this->upload->do_upload('file'))
					{
						$data_upload['file_name'] = '';
						$data_upload['raw_name'] = '';
						echo $this->upload->display_errors();die;
					}
				  else
					{
						$data_up = $this->upload->data();
						$data_upload['file_name'] = $data_up['file_name'];
						$data_upload['raw_name'] = $data_up['raw_name'];
					}

				$data_upload['no_klas'] = $data['no_klas'];
				$data_upload['ISBN'] = $data['ISBN'];
				// var_dump($data_upload);die;
				if ($this->Md_siperpus_data_buku->addDataFile($data_upload))
					{
					$this->session->set_flashdata('alert', 'alert-success');
					$this->session->set_flashdata('flash_message', 'Data Buku Sukses Ditambah');
					}
				  else
					{
					@unlink($data_up['full_path']);
					$status = FALSE;
					$msg = "Something went wrong when saving the file, please try again.";
					}
				}
			redirect(base_url() . 'admin/data_buku', 'refresh');
		}
		else if ($param1=='update') {
			$data = array('no_klas' => $this->input->post('no_klas'),
										'ISBN' => $this->input->post('isbn'),
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
										'tanggal' => $date->format('Y-m-d H:i:s')
										//'matkul' => $this->input->post('matkul')
		 						);
			$this->Md_siperpus_buku_prodi->hapusBukuProdiByISBN($data['ISBN'],$data['no_klas']);
			// echo "string";die;
			$prodi = $this->input->post('prodi');
			for ($i = 0; $i < sizeof($prodi); $i++)
				{
				$dataBukuProdi = array(
					'no_klas' => $this->input->post('no_klas') ,
					'ISBN' => $this->input->post('isbn') ,
					'idmspst' => $prodi[$i],
					'idkategori'=>$this->input->post('kategori_buku')
				);
				$this->Md_siperpus_buku_prodi->addBukuProdi($dataBukuProdi);
				}

			if ($this->input->post('kategori_buku')==3) {
				$data_kti = array('no_klas_ta' => $this->input->post('no_klas'),
													'ISBN_ta' => $this->input->post('isbn'),
													'nis_ta' => $this->input->post('nis_ta'),
													'tabel_ta' => $this->input->post('tabel_ta'),
													'lampiran_ta' => $this->input->post('lampiran_ta'),
													'pembimbing_ta' => $this->input->post('pembimbing_ta'),
										);
				if ($data_kti['tabel_ta']=='') $data_kti['tabel_ta']=0;
				if ($data_kti['lampiran_ta']=='') $data_kti['lampiran_ta']=0;
				$this->Md_siperpus_data_buku->updateDataBukuTa($data_kti);
		 }
		 $config1 = array(
			 'upload_path' => FCPATH . "uploads/covers",
			 'allowed_types' => 'gif|jpg|png|jpeg|bmp',
			 'max_size' => 1024 * 1000
		 );
		 $config2 = array(
			 'upload_path' => FCPATH . "uploads/files",
			 'allowed_types' => 'txt|pdf|doc|docx|xls|xlsx|zip|',
			 'max_size' => 1024 * 1000
		 );
		 if ($data['ilustrasi']=='') {$data['ilustrasi']=0;}
		 if ($data['tabel']=='') {$data['tabel']=0;}
		 if ($data['allow_review']=='') {$data['allow_review']=0;}
		 if ($data['no_klas']==''||$data['judul']==''|| $data['penulis']==''){
				//Empty Field
		 }else{
			 // upload image.

			 $this->upload->initialize($config1);
			 if (!$this->upload->do_upload('gambar'))
			 {
				 $data['cover'] = '';
				 echo $this->upload->display_errors();die;
			 }else if ($this->upload->do_upload('gambar'))
			 {
				 $data_up = $this->upload->data();//up new cover
					 $config['image_library'] = 'gd2';
					 $config['source_image'] = $data_up['full_path'];
					 $config['maintain_ratio'] = TRUE;
					 $config['overwrite'] = TRUE;
					 $config['width']     = 200;
					 $config['height']   = 250;

					 $this->image_lib->initialize($config);
					 $this->image_lib->resize();
				 $cover=$data_up['file_path'].$this->input->post('cover');//get full_path cover by id
				 @unlink($cover); //unlink full path
				 $data['cover'] = $data_up['file_name'];
			 }
			$this->Md_siperpus_data_buku->updateDataBuku($data['ISBN'], $data);


			// upload file

			$this->upload->initialize($config2);
			if (!$this->upload->do_upload('file'))
				{
					$data_upload['file_name'] = '';
					$data_upload['raw_name'] = '';
					echo $this->upload->display_errors();die;
				}
				else
				{
					$data_up = $this->upload->data();
					$data_upload['file_name'] = $data_up['file_name'];
					$data_upload['raw_name'] = $data_up['raw_name'];
				}

        $data_upload['no_klas'] = $data['no_klas'];
				$data_upload['ISBN'] = $data['ISBN'];
				// var_dump($data_upload);die;
				if ($this->Md_siperpus_data_buku->addDataFile($data_upload))
					{
						$alert = 'alert-success';
						$flash_message = 'Data Buku Sukses Diedit';
					}
				  else
					{
						@unlink($data_up['full_path']);
						$alert = 'alert-warning';
						$flash_message = 'Data Buku Gagal Diedit';
					}
				}
				$this->session->set_flashdata('alert', $alert);
				$this->session->set_flashdata('flash_message', $flash_message);
				redirect(base_url() . 'admin/data_buku', 'refresh');
		}
		else if ($param1=='hapus') {
			$this->Md_siperpus_data_buku->hapusDataBuku($x,$y);
			$this->Md_siperpus_inventaris->hapusInventarisAll($x);
			echo json_encode(array("status" => TRUE,"msg" => "Data Buku Sukses Dihapus"));
		}
		else if ($param1=='hapus_inv') {
			$this->Md_siperpus_inventaris->hapusInventaris($param4);
			$row=$this->Md_siperpus_inventaris->getNumRowInvByNoKlas($y);
			$this->Md_siperpus_data_buku->updateJmlBuku($x,$y,$row);
			echo json_encode(array("status" => TRUE,"msg" => "Data Inventaris Sukses Dihapus"));
		}
		else if ($param1=='edit') {
			 $page_data['data']=$this->Md_siperpus_data_buku->getDataBukuByISBNdanNo_klas($x,$y);
			 $page_data['data']['file']=$this->Md_siperpus_data_buku->getDataFile($x,$y);
			 $page_data['data']['kel_buku']=$this->Md_siperpus_kategori_buku->getKategoriBukuAll();
			 $page_data['data']['penerbit']=$this->Md_siperpus_penerbit->getPenerbitAll();
			 $page_data['data']['bahasa']=$this->Md_siperpus_bahasa->getBahasaAll();
			 $page_data['data']['prodi']=$this->Md_vwprodi->getProdiAll();
			 $page_data['data']['prodi'] = json_decode(json_encode($page_data['data']['prodi']), true);
			 // var_dump($page_data['data']['prodi']);die;
			 $page_data['data_prodi']= $this->Md_siperpus_buku_prodi->getBukuProdiByISBN($x,$y);
			 $page_data['page_action'] = "edit";
			 $page_data['page_title'] = 'Tambah Data Buku';
			 $this->load->view('index', $page_data);
		}
		else if ($param1=='delete_file') {
			@unlink(FCPATH.'uploads/files/'.$param4);
			$this->Md_siperpus_data_buku->hapusDataFile($x,$y);
			echo json_encode(array("status" => TRUE,"msg" => "File Buku Berhasil Dihapus"));
		}
		else if ($param1=='get') {
			$page_data['page_action'] = 'view';
			$page_data['data']= $this->Md_siperpus_data_buku->getDataBukuByISBNdanNo_klas($x,$y);
			$page_data['data'][1]= $this->Md_siperpus_data_buku->getDataFile($x,$y);
			$page_data['data'] = json_decode(json_encode($page_data['data']), true);
			$page_data['prodi'] = $this->Md_siperpus_data_buku->getDataProdiByISBN_No_klas($x,$y);
			$page_data['barcode'] = $this->Md_siperpus_data_buku->getDataBarcodeByISBN_No_klas($x,$y);
			$this->load->view('index', $page_data);
		}
		else if ($param1=='cek') {
			 $data = $this->Md_siperpus_data_buku->getDataBukuByISBN($x);
			 if ($data) {
			 	echo json_encode(array("status" => TRUE,"msg" => "Data ISBN Sudah Ada"));
			}else {
				echo json_encode(array("status" => false,"msg" => "Data ISBN Dapat Digunakan"));
			}
		}
		else if ($param1=='set_inv') {
			$page_data['data']=$this->Md_siperpus_data_buku->getDataBukuByISBN($x);
			$page_data['data']['asal_buku']=$this->Md_siperpus_asal_buku->getAsalBukuAll();
			$page_data['isbn']=$param2;
			$page_data['no_klas']=$param3;
			$page_data['page_action'] = "set_inv";
			$page_data['page_title'] = 'Inventaris Buku';
			$this->load->view('index', $page_data);
		}
		else if ($param1=='get_inv') {
			$total=$this->Md_siperpus_inventaris->countByISBN($x);
			$page=intval($this->input->post('datatable[pagination][page]'));
			if($page<1)
				$page=1;
			$perpage=intval($this->input->post('datatable[pagination][perpage]'));
			$pages= intval($total/$perpage);
			$field=$this->input->post('datatable[sort][field]');
			if($field=='')
				$field=$this->input->post('datatable[pagination][field]');
			$sort=$this->input->post('datatable[sort][sort]');
			if($sort=='')
				$sort=$this->input->post('datatable[pagination][sort]');
			$data = array();
			$no = 0;
			$inv=$this->Md_siperpus_inventaris->getInventarisByISBNdanNoKlas($x,$y);
			foreach ($inv as $row) {
				$no++;
				$arr = array();
				$arr['number'] = ($perpage*($page-1))+$no;
				$arr['no_inv'] = $row->no_inv;
				$arr['isbn'] = $row->ISBN;
				$arr['tgl_inv'] = $row->tgl_inv;
				$arr['asal'] = $row->asal;
				$arr['ket'] = $row->ket;
				$arr['no_barcode'] = $row->no_barcode;
				$arr['no_klas'] = $row->no_klas;
				$data[] = $arr;
			}
			$meta=array();
			$meta['page']=$page;
			$meta['pages']=$pages;
			$meta['perpage']=$perpage;
			$meta['total']=$total;
			$meta['sort']=$sort;
			$meta['field']=$field;
			$output = array(
							"meta" => $meta,
							"data" => $data
					);
			//output to json format
			echo json_encode($output);
		}
		else if ($param1=='list') {
			if($this->input->post('datatable[query][generalSearch]')||$this->input->post('datatable[query][klas]')||$this->input->post('datatable[query][kel]'))
				$total=$this->Md_siperpus_data_buku->countFiltered();
			else $total=$this->Md_siperpus_data_buku->countAll();
				$page=intval($this->input->post('datatable[pagination][page]'));
			if($page<1)
				$page=1;

			$perpage=intval($this->input->post('datatable[pagination][perpage]'));
			$pages= intval($total/$perpage);
			$field=$this->input->post('datatable[sort][field]');

			if($field=='')
				$field=$this->input->post('datatable[pagination][field]');

			$sort=$this->input->post('datatable[sort][sort]');

			if($sort=='')
				$sort=$this->input->post('datatable[pagination][sort]');

			$data = array();
			$no = 0;
			$list=$this->Md_siperpus_data_buku->getDatatables();

			foreach ($list as $row) {
				$no++;
				$arr = array();
				$arr['number'] = ($perpage*($page-1))+$no;
				$arr['id'][0] = $row->no_klas;
				$arr['id'][1] = $row->ISBN;
				$arr['isbn'] = $row->ISBN;
				$arr['no_klas'] = $row->no_klas;
				$arr['no_rak'] = $row->no_rak;
				$arr['judul'] = $row->judul;
				$arr['penulis'] = $row->penulis;
				$arr['penerbit'] = $row->nama_penerbit;
				$arr['jml_buku'] = $row->jml_buku;
				$arr['jml_pinjam'] = $row->jml_pinjam;
				$arr['mk'] = "";//$row->mk;
				$arr['jml_prodi'] = $row->jml_prodi;//$row->prodi;
				$data[] = $arr;
			}
			$meta=array();
			$meta['page']=$page;
			$meta['pages']=$pages;
			$meta['perpage']=$perpage;
			$meta['total']=$total;
			$meta['sort']=$sort;
			$meta['field']=$field;
			$output = array(
							"meta" => $meta,
							"data" => $data
					);
			//output to json format
			echo json_encode($output);

		}
		else if ($param1=='tambah') {
			$page_data['data']['kel_buku']=$this->Md_siperpus_kategori_buku->getKategoriBukuAll();
			 $page_data['data']['penerbit']=$this->Md_siperpus_penerbit->getPenerbitAll();
			 $page_data['data']['bahasa']=$this->Md_siperpus_bahasa->getBahasaAll();
			 $page_data['data']['prodi']=$this->Md_vwprodi->getProdiAll();
			 $page_data['page_action'] = $param1;
			 $page_data['page_title'] = 'Tambah Data Buku';
			$this->load->view('index', $page_data);
		}
		else if ($param1=='tambah_inv') {

			$data = array('no_barcode' => $this->input->post('no_barcode'),
										'no_inv' => $this->input->post('no_inv'),
										'tgl_inv' => $this->input->post('tgl_inv'),
										'asal' => $this->input->post('asal'),
										'ket' => $this->input->post('ket'),
										'no_klas' => str_replace('%20',' ',$this->input->post('no_klas')),
										'isbn' => str_replace('_','/',$this->input->post('isbn')),
										'status' => $this->input->post('status'),
										'tanggal' => $date->format('Y-m-d H:i:s')
							);
			if ($data['ket']=='') $data['ket']='-';
			$this->Md_siperpus_inventaris->addInventaris($data);
			$row=$this->Md_siperpus_inventaris->getNumRowInvByNoKlas($data['no_klas']);
			$this->Md_siperpus_data_buku->updateJmlBuku($data['isbn'],$data['no_klas'],$row);
			echo json_encode(array("status" => TRUE,"msg" => "Data Inventaris Berhasil Ditambah"));
		}
		else if ($param1=='get_barcode') {
			$output['barcode']=$this->Md_siperpus_inventaris->getNoBarcode();
			$output['barcode']++;
			echo json_encode($output);
		}
		else if ($param1=='get_data_inv') {
			$inv=$this->Md_siperpus_inventaris->getInventarisByBarcode($param2);
			echo json_encode($inv);
		}
		else if ($param1=='edit_inv') {
			$data = array('no_barcode' => $this->input->post('no_barcode2'),
										'no_inv' => $this->input->post('no_inv2'),
										'tgl_inv' => $this->input->post('tgl_inv2'),
										'asal' => $this->input->post('asal2'),
										'ket' => $this->input->post('ket2'),
										'no_klas' => str_replace('%20',' ',$this->input->post('no_klas2')),
										'isbn' => str_replace('_','/',$this->input->post('isbn2')),
										'status' => $this->input->post('status2'),
										'tanggal' => $date->format('Y-m-d H:i:s')
							);
			if ($data['ket']=='') $data['ket']=' ';
			$this->Md_siperpus_inventaris->updateInventaris($data);
			echo json_encode(array("status" => TRUE,"msg" => "Data Inventaris Berhasil Diubah"));
			// $inv=$this->Md_siperpus_inventaris->getInventarisByBarcode($param2);
			// echo json_encode($inv);
		}
		else if ($param1=='ubah_rak') {
			$page_data['klasifikasi'] = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
			$page_data['kategori'] = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
			$page_data['page_action'] = $param1;
			$page_data['page_title'] = 'Ubah Rak Buku';
			$this->load->view('index', $page_data);
		}
		else if ($param1=='simpan_ubah_rak') {
			$data = $this->input->post('data');//array('data' => , );
			// echo count($data);
			for ($i=0; $i < count($data); $i++) {
				if ($data[$i][1]!='') {
					$this->Md_siperpus_data_buku->updateRakBuku($data[$i][0],$data[$i][1]);
				}
			}
			echo json_encode(array("status" => TRUE,"msg" => "Rak Buku Berhasil Diubah"));
		}
		else if ($param1=='cetak_katalog') {
			$data = array();
			$data_final = $this->input->post('final');
			for ($i=0; $i < sizeof($data_final); $i++) {
				$data[] = $this->Md_siperpus_data_buku->getDataKatalog($data_final[$i][0],$data_final[$i][1]);
			}
			echo json_encode($data);
		}
		else if ($param1=='cetak_callnumber') {
			$data = array();
			$data_final = $this->input->post('final');
			for ($i=0; $i < sizeof($data_final); $i++) {
				$data[] = $this->Md_siperpus_data_buku->getDataCallNumber($data_final[$i]);
			}
			echo json_encode($data);
		}
		else if ($param1=='cetak_barcode') {
			$data = array();
			$data_final = $this->input->post('final');
			for ($i=0; $i < sizeof($data_final); $i++) {
				$data[] = $this->Md_siperpus_data_buku->getBarcode($data_final[$i][0],$data_final[$i][1]);
			}
			echo json_encode($data);
		}
		else{
			$this->load->view('index', $page_data);
		}
	}

	/**Inventarisasi**/
function inventaris($param1='',$param2='',$param3='')
	{
		if ($this->session->userdata('login_type') != 'admin')

            $this->logout();

		$date = new DateTime();
		$id=$this->session->userdata('idsys');
    $page_data['page_action'] = 'list';
    $page_data['page_title'] = 'Inventaris';
		$page_data['page_access'] = "admin";
    $page_data['page_name'] = 'inventaris';
    $page_data['page_now'] = 'Buku & Inventarisasi';
		$page_data['klasifikasi'] = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
		$page_data['data']['asal_buku']=$this->Md_siperpus_asal_buku->getAsalBukuAll();
		$x=str_replace('_','/',$param2);
		$y=str_replace('%20',' ',$param3);

		if ($param1 == 'list') {
			if($this->input->post('datatable[query][generalSearch]'))
				$total=$this->Md_siperpus_inventaris->countFiltered();
			else
				$total=$this->Md_siperpus_inventaris->countAll();
			$page=intval($this->input->post('datatable[pagination][page]'));
			if($page<1)
				$page=1;
				// echo $this->input->post('datatable[pagination][perpage]');die;
			$perpage=intval($this->input->post('datatable[pagination][perpage]'));

			$pages= intval($total/$perpage);
			// echo $pages;die;
			$field=$this->input->post('datatable[sort][field]');

			if($field=='')
				$field=$this->input->post('datatable[pagination][field]');

			$sort=$this->input->post('datatable[sort][sort]');

			if($sort=='')
				$sort=$this->input->post('datatable[pagination][sort]');

			//mulai fetching data
			$data = array();
			$no = 0;
			$list=$this->Md_siperpus_inventaris->getDatatables();

			foreach ($list as $row) {
				$no++;
				$arr = array();
				$arr['number'] = ($perpage*($page-1))+$no;
				$arr['no_inv'] = $row->no_inv;
				$arr['tgl_inv'] = $row->tgl_inv;
				$arr['no_klas'] = $row->no_klas;
				if ($row->status == 'A') $row->status='Ada';
				if ($row->status == 'D') $row->status='Dipinjam';
				$arr['status'] = $row->status;
				if ($row->asal=='-') $row->asal="Tidak Tahu";
				if ($row->asal=='H') $row->asal="Hadiah";
				if ($row->asal=='L') $row->asal="Langganan";
				if ($row->asal=='P') $row->asal="Pembelian";
				if ($row->asal=='S') $row->asal="Sumbangan";
				if ($row->asal=='G') $row->asal="Ganti";
				$arr['asal'] = $row->asal;
				$arr['ket'] = $row->ket;
				$arr['judul'] = $row->judul;
				$arr['id'] = $row->ISBN;
				$arr['no_barcode'] = $row->no_barcode;
				$arr['barcode'] = $row->no_barcode;
				$data[] = $arr;
			}
			// echo $perpage;die;
			$meta=array();
			$meta['page']=$page;
			$meta['pages']=$pages;
			$meta['perpage']=$perpage;
			$meta['total']=$total;
			$meta['sort']=$sort;
			$meta['field']=$field;
			$output = array(
							"meta" => $meta,
							"data" => $data
					);
			echo json_encode($output);//output to json format
		}
		else if ($param1 == 'update') {
			$data = array('no_inv' => $this->input->post('no_inv'),
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
				echo json_encode(array("status" => TRUE,"msg" => "Inventaris Berhasil Diubah"));
    }
		else if ($param1 == 'edit') {
			$data = $this->Md_siperpus_inventaris->getInventarisById($param2);
			echo json_encode($data);
		}
		else if ($param1=='cetak_barcode') {
			$data = array();
			$data_final = $this->input->post('final');
			for ($i=0; $i < sizeof($data_final); $i++) {
				$data[] = $this->Md_siperpus_inventaris->getBarcode($data_final[$i]);
			}
			echo json_encode($data);
		}
		else if ($param1=='cetak_callnumber') {
			$data = array();
			$data_final = $this->input->post('final');
			for ($i=0; $i < sizeof($data_final); $i++) {
				$data[] = $this->Md_siperpus_inventaris->getDataCallNumber($data_final[$i]);
				$x = explode("/",$data[$i][0]['no_inv']);
				$data[$i][0]['no_inv'] = $x[sizeof($x)-1];
				if ($data[$i][0]['no_barcode']==$data_final[$i]) {
					$output[]=$data[$i][0];
				}
			}
			echo json_encode($output);
		}
		else {
			$this->load->view('index', $page_data);
		}
	}

	/** *buku_prodi* **/
	function buku_prodi($param1='',$param2='',$param3='')
	{
		if ($this->session->userdata('login_type') != 'admin')

            $this->logout();


		$date = new DateTime();
		$id=$this->session->userdata('idsys');
    $page_data['page_action'] = 'list';
    $page_data['page_title'] = 'Buku Jurusan';
		$page_data['page_access'] = "admin";
    $page_data['page_name'] = 'buku_prodi';
    $page_data['page_now'] = 'Buku & Inventarisasi';
		$page_data['klasifikasi'] = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
		$page_data['kategori'] = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
		$page_data['prodi'] = $this->Md_vwprodi->getProdiAll();
		$page_data['data']['asal_buku']=$this->Md_siperpus_asal_buku->getAsalBukuAll();
		$x=str_replace('_','/',$param2);
		$y=str_replace('%20',' ',$param3);

		if ($param1 == 'list') {
			if($this->input->post('datatable[query][generalSearch]'))
				$total=$this->Md_siperpus_buku_prodi->countFiltered();
			else
				$total=$this->Md_siperpus_buku_prodi->countAll();
			$page=intval($this->input->post('datatable[pagination][page]'));
			// echo $total;die;
			if($page<1)
				$page=1;

			$perpage=intval($this->input->post('datatable[pagination][perpage]'));
			$pages= intval($total/$perpage);
			$field=$this->input->post('datatable[sort][field]');

			if($field=='')
				$field=$this->input->post('datatable[pagination][field]');

			$sort=$this->input->post('datatable[sort][sort]');

			if($sort=='')
				$sort=$this->input->post('datatable[pagination][sort]');

			//mulai fetching data
			$data = array();
			$no = 0;
			$list=$this->Md_siperpus_buku_prodi->getDatatables();
			$list = json_decode(json_encode($list), FALSE);

			foreach ($list as $row) {
				$no++;
				$arr = array();
				$arr['number'] = ($perpage*($page-1))+$no;
				$arr['id'][0] = $row->no_klas;
				$arr['id'][1] = $row->ISBN;
				$arr['no_klas'] = $row->no_klas;
				$arr['judul'] = $row->judul;
				$arr['penerbit'] = $row->nama_penerbit.": ".$row->thn_terbit;
				$arr['prodi'] = '<ul>';
				for ($i=0; $i < sizeof($row->prodi); $i++) {
					$arr['prodi'] .= '<li>'.$row->prodi[$i].'</li>';
				}
				$arr['prodi'] .= '</ul>';
				$arr['jml_buku'] = $row->jml_buku;
				$data[] = $arr;
			}

			$meta=array();
			$meta['page']=$page;//1
			$meta['pages']=$pages;//0
			$meta['perpage']=$perpage;//10
			$meta['total']=$total;//2
			$meta['sort']=$sort;//
			$meta['field']=$field;
			$output = array(
							"meta" => $meta,
							"data" => $data
					);
			echo json_encode($output);//output to json format
		}
		else if ($param1 == 'update') {
			$data = array('no_inv' => $this->input->post('no_inv'),
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
				echo json_encode(array("status" => TRUE,"msg" => "Inventaris Berhasil Diubah"));
    }
		else if ($param1 == 'edit') {
			$data = $this->Md_siperpus_inventaris->getInventarisById($param2);
			echo json_encode($data);
		}
		else if ($param1=='cetak_katalog') {
			$data = array();
			$data_final = $this->input->post('final');
			for ($i=0; $i < sizeof($data_final); $i++) {
				$data[] = $this->Md_siperpus_data_buku->getDataKatalog($data_final[$i][0],$data_final[$i][1]);
			}
			echo json_encode($data);
		}
		else if ($param1=='cetak_callnumber') {
			$data = array();
			$data_final = $this->input->post('final');
			for ($i=0; $i < sizeof($data_final); $i++) {
				$data[] = $this->Md_siperpus_data_buku->getDataCallNumber($data_final[$i]);
			}
			echo json_encode($data);
		}
		else if ($param1=='cetak_barcode') {
			$data = array();
			$data_final = $this->input->post('final');
			for ($i=0; $i < sizeof($data_final); $i++) {
				$data[] = $this->Md_siperpus_data_buku->getBarcode($data_final[$i][0],$data_final[$i][1]);
			}
			// var_dump($data);die;
			echo json_encode($data);
		}
		else {
			$this->load->view('index', $page_data);
		}
	}

	/** Konfigurasi **/
    function konfigurasi_transaksi($param1 = '', $param2 = '', $param3 = '') {

        if ($this->session->userdata('login_type') != 'admin')

            $this->logout();

		$date = new DateTime();

		$id=$this->session->userdata('idsys');

        $page_data['page_action'] = 'list';
        $page_data['data']=$this->Md_siperpus_config_transaksi->getConfigAll('m');
        $page_data['page_title'] = 'Pengaturan Transaksi';
		if ($param1 == 'update') {

			$setting1= $this->input->post('setting1');
			$setting2= $this->input->post('setting2');
			$setting3= $this->input->post('setting3');
			$setting4= $this->input->post('setting4');
			$setting5= $this->input->post('setting5');

			if($setting1 !=''){
				$data['jml_buku']=$setting1;
			}
			if($setting2 !=''){
				$data['lama']=$setting2;
			}
			if($setting3 !=''){
				$data['denda']=$setting3;
			}
			if($setting4 !=''){
				$data['perpanjang']=$setting4;
			}
			if($setting5 !=''){
				$data['masa_berlaku']=$setting5;
			}
				$this->Md_siperpus_config_transaksi->updateConfig('m',$data);


				$this->session->set_flashdata('alert', 'alert-warning');

				$this->session->set_flashdata('flash_message', 'Update Konfigurasi Transaksi Sukses');

				redirect(base_url() . 'admin/konfigurasi_transaksi/', 'refresh');

        }if ($param1 == 'forceupdate') {

			$tgl= $this->input->post('tgl');

			if($tgl ==''){
				$this->session->set_flashdata('alert', 'alert-warning');

				$this->session->set_flashdata('flash_message', 'Empty Field');

				redirect(base_url() . 'admin/konfigurasi_transaksi/', 'refresh');
			}else{
				$this->Md_siperpus_config_transaksi->updateConfig('m',$data);
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

	/**Hari Libur**/

	public function hari_libur($param1='',$param2='')
		{
			if ($this->session->userdata('login_type') != 'admin')
					$this->logout();

			$date = new DateTime();
			$id=$this->session->userdata('idsys');

			$page_data['page_action'] = 'list';
			$page_data['page_title'] = 'Pengaturan Hari Libur';
			$page_data['page_access'] = "admin";
			$page_data['page_name'] = 'hari_libur';
			$page_data['page_now'] = 'Konfigurasi & Transaksi';


			$page_data['year'] = $date->format('Y');
			if($param1=='year' && $param2 !=''){
				if(is_numeric($param2) && $param2 > 1990)
				$page_data['year'] = $param2;

				$libur=$this->Md_siperpus_libur->getLibur($page_data['year']);
				$dates='';
				$count=0;
				if($libur & count($libur)>0){
					foreach($libur as $l){
						if($count>0)$dates=$dates.',';
						$dates=$dates.'"'.$l.'"';
						$count++;
					}
				}
				$page_data['dates'] = $dates;

				$this->load->view('index', $page_data);
		    }else if ($param1 == 'change')
			{
				$tgl = $this->input->post('date');
				$m=$this->input->post('month');
				$y=$this->input->post('year');
				if($m>0 && $y > 0){
					$this->Md_siperpus_libur->hapusLiburMonth($m,$y);
				}
				foreach($tgl as $t){
					//echo substr($t,0,strrpos($t,'GMT')-1);
					$dates=new DateTime(substr($t,0,strrpos($t,'GMT')-1));
					$fdate=$dates->format('Y-m-d');
					//echo $fdate;
					$isLibur=$this->Md_siperpus_libur->isLibur($fdate);
					if(!$isLibur){
						$data['tgl_libur']=$fdate;
						$this->Md_siperpus_libur->addLibur($data);
					}
				}

				echo json_encode(array("status" => TRUE));
			}
			else{
				$libur=$this->Md_siperpus_libur->getLibur($page_data['year']);
				$dates='';
				$count=0;
				if($libur & count($libur)>0){
					foreach($libur as $l){
						if($count>0)$dates=$dates.',';
						$dates=$dates.'"'.$l.'"';
						$count++;
					}
				}
				$page_data['dates'] = $dates;

				$this->load->view('index', $page_data);
		    }
		}
	/** *Group User* **/

    function group_user($param1 = '', $param2 = '', $param3 = '') {

        if ($this->session->userdata('login_type') != 'admin')

            $this->logout();

		$date = new DateTime();

		$id=$this->session->userdata('idsys');

        $page_data['page_action'] = 'list';
         $page_data['page_title'] = 'Daftar Group User';
		$page_data['page_access'] = "admin";
		$page_data['page_name'] = 'group_user';
		$page_data['page_now'] = 'Administrator';
		$page_data['modul']=$this->Md_siperpus_sysmodul->getModulAll();
        if ($param1 == 'submit') {

			$data['idsysgroup'] = $this->input->post('id');
			$data['name'] = $this->input->post('nama');
			$data['def_modul'] = $this->input->post('default');

			if( $data['def_modul']=='' || $data['idsysgroup']=='' || $data['name']==''){
				//Empty Field
				$this->session->set_flashdata('alert', 'alert-danger');

				$this->session->set_flashdata('flash_message', 'Empty Field');
				echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Empty Field"));
			}else{
				$Group=$this->Md_siperpus_sysgroup->getGroupById($this->input->post('id'));

				if($Group){
					$this->session->set_flashdata('alert', 'alert-danger');

					$this->session->set_flashdata('flash_message', 'ID telah dipakai,Group User gagal dibuat');
					echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "ID telah dipakai, User Gagal Dibuat"));

				}else{
					$this->Md_siperpus_sysgroup->addGroup($data);

					$this->session->set_flashdata('alert', 'alert-focus');

					$this->session->set_flashdata('flash_message', 'Group User Sukses dibuat');
					echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Group User Sukses dibuat"));
					//redirect(base_url() . 'admin/klasifikasi_buku', 'refresh');
				}
			}
        }else if ($param1 == 'update') {

			$id1 = $this->input->post('id1');
			$data['idsysgroup'] = $this->input->post('id');
			$data['name'] = $this->input->post('nama');
			$data['def_modul'] = $this->input->post('default');

			if($id1=='' || $data['def_modul']=='' || $data['idsysgroup']=='' || $data['name']==''){
				//Empty Field
				$this->session->set_flashdata('alert', 'alert-danger');

				$this->session->set_flashdata('flash_message', 'Empty Field');
				echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Empty Field"));
			}else{
				$this->Md_siperpus_sysgroup->updateGroup($id1, $data);
				$this->session->set_flashdata('alert', 'alert-focus');
				$this->session->set_flashdata('flash_message', 'Group User Sukses diedit');
				echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Group User Sukses diedit"));
			}

        }else if($param1 =='edit' && $param2 !=''){
			$data = $this->Md_siperpus_sysgroup->getGroupById($param2);
			echo json_encode($data);

		}else if($param1 =='fetch'){
			$total=$this->Md_siperpus_sysgroup->countFiltered();
			$page=intval($this->input->post('datatable[pagination][page]'));
			if($page<1)$page=1;
			$perpage=intval($this->input->post('datatable[pagination][perpage]'));
			$pages= intval($total/$perpage);
			$field=$this->input->post('datatable[sort][field]');
			if($field=='')$field=$this->input->post('datatable[pagination][field]');
			$sort=$this->input->post('datatable[sort][sort]');
			if($sort=='')$sort=$this->input->post('datatable[pagination][sort]');
			$data = array();
			$no = 0;
			$list=$this->Md_siperpus_sysgroup->getDatatables();
			foreach ($list as $row) {
				$no++;
				$arr = array();
				$arr['number'] = ($perpage*($page-1))+$no;
				$arr['id'] = $row->idsysgroup;
				$arr['nama'] = $row->name;
				$modul=$this->Md_siperpus_sysmodul->getModulById($row->def_modul);
				if($modul)$arr['def'] = $modul[0]->name;
				else $arr['def']='';

				$data[] = $arr;
			}
			$meta=array();
			$meta['page']=$page;
			$meta['pages']=$pages;
			$meta['perpage']=$perpage;
			$meta['total']=$total;
			$meta['sort']=$sort;
			$meta['field']=$field;
			$output = array(
							"meta" => $meta,
							"data" => $data
					);
			//output to json format
			echo json_encode($output);

		}else{
			$this->load->view('index', $page_data);
		}
    }
	/** *Daftar User* **/

    function daftar_user($param1 = '', $param2 = '', $param3 = '') {

        if ($this->session->userdata('login_type') != 'admin')

            $this->logout();

		$date = new DateTime();

		$id=$this->session->userdata('idsys');

        $page_data['page_action'] = 'list';
         $page_data['page_title'] = 'Daftar User Program';
		$page_data['page_access'] = "admin";
		$page_data['page_name'] = 'daftar_user';
		$page_data['page_now'] = 'Administrator';
		$page_data['sysgroup']=$this->Md_siperpus_sysgroup->getGroupAll();
        if ($param1 == 'submit') {

			$data['idsysuser'] = $this->input->post('id');
			$data['name'] = $this->input->post('nama');
			$data['pass'] = $this->input->post('password');
			$confirm = $this->input->post('password2');
			$data['idsysgroup'] = $this->input->post('group');
			$data['active'] = $this->input->post('status');
			if($data['pass']!=$confirm){
				//Empty Field
				$this->session->set_flashdata('alert', 'alert-danger');

				$this->session->set_flashdata('flash_message', 'Password Tidak Sama');
				echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Password Tidak Sama"));
			}else if($data['idsysuser']=='' || $data['pass']=='' || $data['name']=='' || $data['idsysgroup']=='' || $data['active']==''){
				//Empty Field
				$this->session->set_flashdata('alert', 'alert-danger');

				$this->session->set_flashdata('flash_message', 'Empty Field');
				echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Empty Field"));
			}else{
				$user=$this->Md_siperpus_sysuser->getUserById($this->input->post('id'));

				if($user){
					$this->session->set_flashdata('alert', 'alert-danger');

					$this->session->set_flashdata('flash_message', 'ID telah dipakai, User gagal dibuat');
					echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "ID telah dipakai, User Gagal Dibuat"));

				}else{
					$this->Md_siperpus_sysuser->addUser($data);

					$this->session->set_flashdata('alert', 'alert-focus');

					$this->session->set_flashdata('flash_message', 'User Sukses dibuat');
					echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "User Sukses dibuat"));
					//redirect(base_url() . 'admin/klasifikasi_buku', 'refresh');
				}
			}
        }else if ($param1 == 'update') {

			$id1 = $this->input->post('id1');
			$data['idsysuser'] = $this->input->post('id');
			$data['name'] = $this->input->post('nama');
			$pass = $this->input->post('password');
			$confirm = $this->input->post('password2');
			$data['idsysgroup'] = $this->input->post('group');
			$data['active'] = $this->input->post('status');

			if( $pass != $confirm && $confirm != '' ){
				//Empty Field
				$this->session->set_flashdata('alert', 'alert-danger');

				$this->session->set_flashdata('flash_message', 'Password Tidak Sama');
				echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Password Tidak Sama"));
			}else if($id1=='' || $data['idsysuser']=='' || $pass=='' || $data['name']=='' || $data['idsysgroup']=='' || $data['active']==''){
				//Empty Field
				$this->session->set_flashdata('alert', 'alert-danger');

				$this->session->set_flashdata('flash_message', 'Empty Field');
				echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Empty Field"));
			}else{

				//$data['pass'] = $pass;

				$this->Md_siperpus_sysuser->updateUser($id1, $data);

				$this->session->set_flashdata('alert', 'alert-focus');

				$this->session->set_flashdata('flash_message', 'User Sukses diedit');
				//redirect(base_url() . 'admin/klasifikasi_buku', 'refresh');
				echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "User Sukses diedit"));
			}

        }else if ($param1=='hapus' && $param2 !='') {
				$this->Md_siperpus_sysuser->hapusUser($param2);
				$this->session->set_flashdata('alert', 'alert-focus');
				$this->session->set_flashdata('flash_message', 'User Sukses Dihapus');
				echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "User Sukses Dihapus"));
		}else if($param1 =='pass' && $param2 !=''){
			$data = $this->Md_siperpus_sysuser->getUserById($param2);
			echo json_encode(array("status" => TRUE,"pass" => $data[0]->pass));

		}else if($param1 =='edit' && $param2 !=''){
			$data = $this->Md_siperpus_sysuser->getUserById($param2);
			echo json_encode($data);

		}else if($param1 =='fetch'){
		    $total=$this->Md_siperpus_sysuser->countFiltered();
			$page=intval($this->input->post('datatable[pagination][page]'));
			if($page<1)$page=1;
			$perpage=intval($this->input->post('datatable[pagination][perpage]'));
			$pages= intval($total/$perpage);
			$field=$this->input->post('datatable[sort][field]');
			if($field=='')$field=$this->input->post('datatable[pagination][field]');
			$sort=$this->input->post('datatable[sort][sort]');
			if($sort=='')$sort=$this->input->post('datatable[pagination][sort]');
			$data = array();
			$no = 0;
			$list=$this->Md_siperpus_sysuser->getDatatables();
			foreach ($list as $row) {
				$no++;
				$arr = array();
				$arr['number'] = ($perpage*($page-1))+$no;
				$arr['id'] = $row->idsysuser;
				$arr['nama'] = $row->name;
				$arr['pass'] = "******";
				$arr['group'] = $row->idsysgroup;
				if($row->active ==1)$arr['active'] = "Aktif";
				else $arr['active'] = "Non-Aktif";

				$data[] = $arr;
			}
			$meta=array();
			$meta['page']=$page;
			$meta['pages']=$pages;
			$meta['perpage']=$perpage;
			$meta['total']=$total;
			$meta['sort']=$sort;
			$meta['field']=$field;
			$output = array(
							"meta" => $meta,
							"data" => $data
					);
			//output to json format
			echo json_encode($output);

		}else{
			$this->load->view('index', $page_data);
		}
    }
	/** *User Grant* **/

    function user_grant($param1 = '', $param2 = '', $param3 = '') {

        if ($this->session->userdata('login_type') != 'admin')

            $this->logout();

		$date = new DateTime();

		$id=$this->session->userdata('idsys');

        $page_data['page_action'] = 'list';
         $page_data['page_title'] = 'Daftar Hak Akses User';
		$page_data['page_access'] = "admin";
		$page_data['page_name'] = 'user_grant';
		$page_data['page_now'] = 'Administrator';
		$page_data['group']=$this->Md_siperpus_sysgroup->getGroupAll();
        $page_data['modulall']=$this->Md_siperpus_sysmodul->getModulAll();
		$page_data['modul']=$this->Md_siperpus_sysmodul->getModulNoAkses('A');
		if ($param1 == 'submit') {

			$id=$this->Md_siperpus_sysgrant->getLastId();
			$data['idsysgrant'] = $id+1;
			$data['idsysmodul'] = $this->input->post('modul');
			$data['idsysgroup'] = $this->input->post('group');
			if($this->input->post('add') && $this->input->post('add')==1)$data['allow_add'] = 1;
			else $data['allow_add'] = 0;
			if($this->input->post('edit') && $this->input->post('edit')==1)$data['allow_edit'] = 1;
			else $data['allow_edit'] = 0;
			if($this->input->post('delete') && $this->input->post('delete')==1)$data['allow_delete'] = 1;
			else $data['allow_delete'] = 0;
			if($this->input->post('view') && $this->input->post('view')==1)$data['allow_view'] = 1;
			else $data['allow_view'] = 0;
			if($this->input->post('print') && $this->input->post('print')==1)$data['allow_print'] = 1;
			else $data['allow_print'] = 0;
			if($data['idsysmodul']=='' || $data['idsysgroup']==''){
				//Empty Field
				$this->session->set_flashdata('alert', 'alert-danger');

				$this->session->set_flashdata('flash_message', 'Empty Field');
				echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Empty Field"));
			}else{
				$user=$this->Md_siperpus_sysgrant->getGrantByModul($data['idsysmodul'],$data['idsysgroup']);

				if($user){
					$this->session->set_flashdata('alert', 'alert-danger');

					$this->session->set_flashdata('flash_message', 'ID telah dipakai,Grant User gagal dibuat');
					echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Akses Modul Telah Di tambahakan Sebelumnya"));

				}else{
					$this->Md_siperpus_sysgrant->addGrant($data);

					$this->session->set_flashdata('alert', 'alert-focus');

					$this->session->set_flashdata('flash_message', 'Grant User Sukses dibuat');
					echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Grant User Sukses dibuat"));
				}
			}
        }else if ($param1 == 'update') {

			$id1 = $this->input->post('eid1');
			$data['idsysmodul'] = $this->input->post('emodul');
			$data['idsysgroup'] = $this->input->post('egroup');
			if($this->input->post('eadd')==1)$data['allow_add'] = 1;
			else $data['allow_add'] = 0;
			if($this->input->post('eedit')==1)$data['allow_edit'] = 1;
			else $data['allow_edit'] = 0;
			if($this->input->post('edelete')==1)$data['allow_delete'] = 1;
			else $data['allow_delete'] = 0;
			if($this->input->post('eview')==1)$data['allow_view'] = 1;
			else $data['allow_view'] = 0;
			if($this->input->post('eprint')==1)$data['allow_print'] = 1;
			else $data['allow_print'] = 0;
			if($id1=='' || $data['idsysmodul']=='' || $data['idsysgroup']==''){
				echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Data Tidak Lengkap".$id1));
			}else{

				//$data['pass'] = $pass;

				$this->Md_siperpus_sysgrant->updateGrant($id1, $data);

				$this->session->set_flashdata('alert', 'alert-focus');

				$this->session->set_flashdata('flash_message', 'Hak Akses Sukses diedit');
				echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Hak Akses Sukses diedit"));
			}

        }else if ($param1=='hapus' && $param2 !='') {
				$this->Md_siperpus_sysgrant->hapusGrant($param2);
				$this->session->set_flashdata('alert', 'alert-focus');
				$this->session->set_flashdata('flash_message', 'Hak Akses Sukses Dihapus');
				echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Hak Akses Sukses Dihapus"));
		}else if($param1 =='edit' && $param2 !=''){
			$data = $this->Md_siperpus_sysgrant->getGrantById($param2);
			echo json_encode($data);

		}else if($param1 =='modul'){
			$g=$this->input->post('g');
			$modul=$this->Md_siperpus_sysmodul->getModulNoAkses($g);
			if($g!=''){
			echo json_encode($modul);
			}else{
			$modul=$this->Md_siperpus_sysmodul->getModulAll();
			echo json_encode($modul);
			}

		}else if($param1 =='fetch'){
			$total=$this->Md_siperpus_sysgrant->countFiltered();
			$page=intval($this->input->post('datatable[pagination][page]'));
			if($page<1)$page=1;
			$perpage=intval($this->input->post('datatable[pagination][perpage]'));
			$pages= intval($total/$perpage);
			$field=$this->input->post('datatable[sort][field]');
			if($field=='')$field=$this->input->post('datatable[pagination][field]');
			$sort=$this->input->post('datatable[sort][sort]');
			if($sort=='')$sort=$this->input->post('datatable[pagination][sort]');
			$data = array();
			$no = 0;
			$list=$this->Md_siperpus_sysgrant->getDatatables();
			foreach ($list as $row) {
				$no++;
				$arr = array();
				$arr['number'] = ($perpage*($page-1))+$no;
				$arr['id'] = $row->idsysgrant;
				$modul=$this->Md_siperpus_sysmodul->getModulById($row->idsysmodul);
				if($modul)$arr['nama'] = $modul[0]->name;
				$arr['add'] = $row->allow_add;
				$arr['edit'] = $row->allow_edit;
				$arr['delete'] = $row->allow_delete;
				$arr['view'] = $row->allow_view;
				$arr['print'] = $row->allow_print;

				$data[] = $arr;
			}
			$meta=array();
			$meta['page']=$page;
			$meta['pages']=$pages;
			$meta['perpage']=$perpage;
			$meta['total']=$total;
			$meta['sort']=$sort;
			$meta['field']=$field;
			$output = array(
							"meta" => $meta,
							"data" => $data
					);
			//output to json format
			echo json_encode($output);

		}else{
			$this->load->view('index', $page_data);
		}
    }
	function import_data($param1 = '', $param2 = '', $param3 = ''){

		if ($this->session->userdata('login_type') != 'admin')

            $this->logout();


		$date = new DateTime();

		$id=$this->session->userdata('idsys');

        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Import Data';
		$page_data['page_access'] = "admin";
		$page_data['page_name'] = 'import_data';
		$page_data['page_now'] = 'Administrator';
		if($param1=='import'){
			$tipe=$this->input->post('tipe');
		    $kolom=$this->input->post('kolom')-1;
			if($kolom<0)$kolom=0;

		    $config['upload_path']		= './uploads/';
			$config['allowed_types']	= 'xls|xlsx';

			$this->upload->initialize($config);
			$this->load->library('upload', $config);


			if($this->upload->do_upload('imports')){

				$dataupload['files'] = $this->upload->data();

				$file = './uploads/'.$dataupload['files']['file_name'];

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
				if ($row == 1) {
					$header[$row][$column] = $data_value;
				} else {
					$arr_data[$row][$column] = $data_value;
				}
			}

			if($tipe=='inventaris'){
					//check Header
					$check=true;
					$cell=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
					'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
					$data['no_inv']='';
					$data['tgl_inv']='';
					$data['no_klas']='';
					$data['status']='';
					$data['asal']='';
					$data['tanggal']='';
					$data['no_barcode']='';
					$data['ISBN']='';
					$data['ket']='';
					$head='';
					if(isset($header[1][$cell[0+$kolom]])){if($header[1][$cell[0+$kolom]]!='No Inventaris')$check=false;$head+='noinv';}
					else $check=false;
					if(isset($header[1][$cell[1+$kolom]])){if($header[1][$cell[1+$kolom]]!='Tanggal Inventaris')$check=false;$head+='tglinv';}
					else $check=false;
					if(isset($header[1][$cell[2+$kolom]])){if($header[1][$cell[2+$kolom]]!='No Klasifikasi')$check=false;$head+='no klas';}
					else $check=false;
					if(isset($header[1][$cell[3+$kolom]])){if($header[1][$cell[3+$kolom]]!='Status Buku')$check=false;$head+='status';}
					else $check=false;
					if(isset($header[1][$cell[4+$kolom]])){if($header[1][$cell[4+$kolom]]!='Asal Buku')$check=false;$head+='asal';}
					else $check=false;
					if(isset($header[1][$cell[5+$kolom]])){if($header[1][$cell[5+$kolom]]!='No Barcode')$check=false;$head+='barcode';}
					else $check=false;
					if(isset($header[1][$cell[6+$kolom]])){if($header[1][$cell[6+$kolom]]!='ISBN')$check=false;$head+='isbn';}
					else $check=false;
					if(isset($header[1][$cell[7+$kolom]])){if($header[1][$cell[7+$kolom]]!='Keterangan')$check=false;$head+='keterangan';}
					else $check=false;
					//header lengkap
					$res=array();
						if($check){
							foreach($arr_data as $rows){
								$err='';
								$status='';
								$asal='';
								if(isset($rows[$cell[0+$kolom]]))$data['no_inv']=$rows[$cell[0+$kolom]];
								if(isset($rows[$cell[1+$kolom]]))$data['tgl_inv']=PHPExcel_Style_NumberFormat::toFormattedString($rows[$cell[1+$kolom]], 'YYYY-MM-DD');
								if(isset($rows[$cell[2+$kolom]]))$data['no_klas']=$rows[$cell[2+$kolom]];
								if(isset($rows[$cell[3+$kolom]]))$data['status']=$rows[$cell[3+$kolom]];
								if(isset($rows[$cell[4+$kolom]])){
									$asalbuku=$this->Md_siperpus_asal_buku->getAsalBukuByNama($rows[$cell[4+$kolom]]);
									if(count($asalbuku)>0)$data['asal']=$asalbuku[0]->id;
								}
								if(isset($rows[$cell[5+$kolom]]))$data['no_barcode']=$rows[$cell[5+$kolom]];
								if(isset($rows[$cell[6+$kolom]]))$data['ISBN']=$rows[$cell[6+$kolom]];
								if(isset($rows[$cell[7+$kolom]]))$data['ket']=$rows[$cell[7+$kolom]];
								$data['tanggal']=date("Y-m-d H:i:s");

								if($data['status']=='A')$status='Aktif';
								else $status='Tidak Aktif';
								if($data['asal']!=''){

								}
								$buku=$this->Md_siperpus_buku->getBukuByISBN($data['ISBN'],$data['no_klas']);
								if(count($buku)==0){
									$invs=$this->Md_siperpus_inventaris->getInventarisByNoInv($data['no_inv']);
									if(count($invs)==0){
										$bar=$this->Md_siperpus_inventaris->getInventarisByBarcode($data['no_barcode']);
										if(count($bar)>0){
											$err=$err." No Barcode telah digunakan";
										}else{
											$this->Md_siperpus_inventaris->addInventaris($data);
											$err=$err." Data Inserted";
										}
									}else{
										$err=$err." No Inventaris telah digunakan";
									}
								}else{
									$err=$err." Data Buku Tidak Ditemukan";
								}
								$rowres=array($data['no_inv'],$data['tgl_inv'],$data['no_klas'],$status,$rows[$cell[4+$kolom]],$data['tanggal'],$data['ISBN'],$data['ket'],$err);

								array_push($res, $rowres);
							}
							$this->session->set_flashdata('alert', 'alert-success');

							$this->session->set_flashdata('flash_message', 'Import Data Inventaris Sukses ');

							//redirect(base_url() . 'admin/import_data/', 'refresh');
							$page_data['importResult'] = $res;
							$page_data['importType'] = 'inventaris';
						}else{
							//do nothing header not same.
							$this->session->set_flashdata('alert', 'alert-warning');

							$this->session->set_flashdata('flash_message', 'Import Data Inventaris Gagal - Wrong Header'.$head);

							redirect(base_url() . 'admin/import_data/', 'refresh');
						}


			}else if($tipe=='buku'){
					//check Header
					$check=true;
					$cell=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
					'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
					$data['no_klas']='';
					$data['ISBN']='';
					$data['idkategori']='';
					$data['judul']='';
					$data['cetakkatalog_judulpenggal']='';
					$data['judulasli']='';
					$data['allow_review']='';
					$data['deskripsi']='';
					$data['penulis']='';
					$data['penyadur']='';
					$data['penerjemah']='';
					$data['penyusun']='';
					$data['penyunting']='';
					$data['illustrator']='';
					$data['editor']='';
					$data['edisi']='';
					$data['cetakan']='';
					$data['kd_penerbit']='';
					$data['thn_terbit']='';
					$data['jilid']='';
					$data['hlm_romawi']='';
					$data['jml_hal']='';
					$data['ilustrasi']='';
					$data['tabel']='';
					$data['ukuran_fisik']='';
					$data['bibliografi']='';
					$data['indeks']='';
					$data['bahasa']='';
					$data['no_rak']='';
					$data['seri']='';
					$data['tajuk']='';
					$data['tajuksubyek']='';
					$data['tanggal']='';
					if(isset($header[1][$cell[0+$kolom]])){if($header[1][$cell[0+$kolom]]!='No Klasifikasi')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[1+$kolom]])){if($header[1][$cell[1+$kolom]]!='ISBN')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[2+$kolom]])){if($header[1][$cell[2+$kolom]]!='Kelompok Buku')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[3+$kolom]])){if($header[1][$cell[3+$kolom]]!='Judul Buku')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[4+$kolom]])){if($header[1][$cell[4+$kolom]]!='Penggalan Judul Katalog')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[5+$kolom]])){if($header[1][$cell[5+$kolom]]!='Judul Asli')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[6+$kolom]])){if($header[1][$cell[6+$kolom]]!='Allow Review')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[7+$kolom]])){if($header[1][$cell[7+$kolom]]!='Penulis')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[8+$kolom]])){if($header[1][$cell[8+$kolom]]!='Penyadur')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[9+$kolom]])){if($header[1][$cell[9+$kolom]]!='Penerjemah')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[10+$kolom]])){if($header[1][$cell[10+$kolom]]!='Penyusun')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[11+$kolom]])){if($header[1][$cell[11+$kolom]]!='Penyunting')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[12+$kolom]])){if($header[1][$cell[12+$kolom]]!='Illustrator')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[13+$kolom]])){if($header[1][$cell[13+$kolom]]!='Editor')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[14+$kolom]])){if($header[1][$cell[14+$kolom]]!='Edisi')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[15+$kolom]])){if($header[1][$cell[15+$kolom]]!='Cetakan')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[16+$kolom]])){if($header[1][$cell[16+$kolom]]!='Penerbit')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[17+$kolom]])){if($header[1][$cell[17+$kolom]]!='Tahun Terbit')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[18+$kolom]])){if($header[1][$cell[18+$kolom]]!='Jilid')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[19+$kolom]])){if($header[1][$cell[19+$kolom]]!='No. Hal. Romawi')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[20+$kolom]])){if($header[1][$cell[20+$kolom]]!='Jumlah Halaman')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[21+$kolom]])){if($header[1][$cell[21+$kolom]]!='Ilustrasi')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[22+$kolom]])){if($header[1][$cell[22+$kolom]]!='Tabel')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[23+$kolom]])){if($header[1][$cell[23+$kolom]]!='Ukuran Fisik')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[24+$kolom]])){if($header[1][$cell[24+$kolom]]!='Bibliografi')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[25+$kolom]])){if($header[1][$cell[25+$kolom]]!='Index')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[26+$kolom]])){if($header[1][$cell[26+$kolom]]!='Bahasa')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[27+$kolom]])){if($header[1][$cell[27+$kolom]]!='No. Rak')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[28+$kolom]])){if($header[1][$cell[28+$kolom]]!='Seri')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[29+$kolom]])){if($header[1][$cell[29+$kolom]]!='Tajuk Utama')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[30+$kolom]])){if($header[1][$cell[30+$kolom]]!='Tajuk Subjek')$check=false;}
					else $check=false;
					if(isset($header[1][$cell[31+$kolom]])){if($header[1][$cell[31+$kolom]]!='Deskripsi')$check=false;}
					else $check=false;

					//header lengkap
					$res=array();
						if($check){
							foreach($arr_data as $rows){
								$err='';
								$nmkategori="";
								$nmpenerbit="";
								if(isset($rows[$cell[0+$kolom]]))$data['no_klas']=$rows[$cell[0+$kolom]];
								if(isset($rows[$cell[1+$kolom]]))$data['ISBN']=$rows[$cell[1+$kolom]];
								if(isset($rows[$cell[2+$kolom]])){
									$nmkategori=$rows[$cell[2+$kolom]];
									$ktg=$this->Md_siperpus_kategori_buku->getKategoriByNama($nmkategori);
									if($ktg && count($ktg)>0)$data['idkategori']=$ktg[0]->idkategori;
								}
								if(isset($rows[$cell[3+$kolom]]))$data['judul']=$rows[$cell[3+$kolom]];
								if(isset($rows[$cell[4+$kolom]]))$data['cetakkatalog_judulpenggal']=$rows[$cell[4+$kolom]];
								if(isset($rows[$cell[5+$kolom]]))$data['judulasli']=$rows[$cell[5+$kolom]];
								if(isset($rows[$cell[6+$kolom]]))$data['allow_review']=$rows[$cell[6+$kolom]];
								if(isset($rows[$cell[7+$kolom]]))$data['penulis']=$rows[$cell[7+$kolom]];
								if(isset($rows[$cell[8+$kolom]]))$data['penyadur']=$rows[$cell[8+$kolom]];
								if(isset($rows[$cell[9+$kolom]]))$data['penerjemah']=$rows[$cell[9+$kolom]];
								if(isset($rows[$cell[10+$kolom]]))$data['penyusun']=$rows[$cell[10+$kolom]];
								if(isset($rows[$cell[11+$kolom]]))$data['penyunting']=$rows[$cell[11+$kolom]];
								if(isset($rows[$cell[12+$kolom]]))$data['illustrator']=$rows[$cell[12+$kolom]];
								if(isset($rows[$cell[13+$kolom]]))$data['editor']=$rows[$cell[13+$kolom]];
								if(isset($rows[$cell[14+$kolom]]))$data['edisi']=$rows[$cell[14+$kolom]];
								if(isset($rows[$cell[15+$kolom]]))$data['cetakan']=$rows[$cell[15+$kolom]];
								if(isset($rows[$cell[16+$kolom]])){
									$nmpenerbit=$rows[$cell[16+$kolom]];
									$pnb=$this->Md_siperpus_penerbit->getPenerbitByNama($nmpenerbit);
									if($pnb && count($pnb)>0)$data['idkategori']=$pnb[0]->kd_penerbit;
								}
								if(isset($rows[$cell[17+$kolom]]))$data['thn_terbit']=$rows[$cell[17+$kolom]];
								if(isset($rows[$cell[18+$kolom]]))$data['jilid']=$rows[$cell[18+$kolom]];
								if(isset($rows[$cell[19+$kolom]]))$data['hlm_romawi']=$rows[$cell[19+$kolom]];
								if(isset($rows[$cell[20+$kolom]]))$data['jml_hal']=$rows[$cell[20+$kolom]];
								if(isset($rows[$cell[21+$kolom]]))$data['ilustrasi']=$rows[$cell[21+$kolom]];
								if(isset($rows[$cell[22+$kolom]]))$data['tabel']=$rows[$cell[22+$kolom]];
								if(isset($rows[$cell[23+$kolom]]))$data['ukuran_fisik']=$rows[$cell[23+$kolom]];
								if(isset($rows[$cell[24+$kolom]]))$data['bibliografi']=$rows[$cell[24+$kolom]];
								if(isset($rows[$cell[25+$kolom]]))$data['indeks']=$rows[$cell[25+$kolom]];
								if(isset($rows[$cell[26+$kolom]]))$data['bahasa']=$rows[$cell[26+$kolom]];
								if(isset($rows[$cell[27+$kolom]]))$data['no_rak']=$rows[$cell[27+$kolom]];
								if(isset($rows[$cell[28+$kolom]]))$data['seri']=$rows[$cell[28+$kolom]];
								if(isset($rows[$cell[29+$kolom]]))$data['tajuk']=$rows[$cell[29+$kolom]];
								if(isset($rows[$cell[30+$kolom]]))$data['tajuksubyek']=$rows[$cell[30+$kolom]];
								if(isset($rows[$cell[31+$kolom]]))$data['deskripsi']=$rows[$cell[31+$kolom]];
								$data['tanggal']=date("Y-m-d H:i:s");

								$buku=$this->Md_siperpus_buku->getBukuByISBN($data['ISBN'],$data['no_klas']);
								if(count($buku)==0){
									//$this->Md_siperpus_data_buku->addDataBuku($data);
									$err=$err." Data Inserted";
								}else{
									$err=$err." Duplicate Data";
								}


								$rowres=array($data['no_klas'],$data['ISBN'],$nmkategori,$data['judul'],$data['cetakkatalog_judulpenggal'],$data['judulasli'],$data['penulis'],$data['penyadur'],$data['penerjemah'],$data['penyusun'],$data['penyunting'],$data['illustrator'],$data['editor'],$data['edisi'],$data['cetakan'],$nmpenerbit,$data['thn_terbit'],$data['jilid'],$data['hlm_romawi'],$data['jml_hal'],$data['ilustrasi'],$data['tabel'],$data['ukuran_fisik'],$data['bibliografi'],$data['indeks'],$data['bahasa'],$data['no_rak'],$data['seri'],$data['tajuk'],$data['tajuksubyek'],$data['deskripsi'],$data['tanggal'],$err);

								array_push($res, $rowres);
							}
							$this->session->set_flashdata('alert', 'alert-success');

							$this->session->set_flashdata('flash_message', 'Import Success Data imported ');

							//redirect(base_url() . 'admin/import_data/', 'refresh');
							$page_data['importResult'] = $res;
							$page_data['importType'] = 'buku';
						}else{
							//do nothing header not same.
							$this->session->set_flashdata('alert', 'alert-warning');

							$this->session->set_flashdata('flash_message', 'Import Failed - Wrong Header');

							redirect(base_url() . 'admin/import_data/', 'refresh');
						}


			}else{

				$this->session->set_flashdata('alert', 'alert-warning');

				$this->session->set_flashdata('flash_message', 'Import Failed!');

				redirect(base_url() . 'admin/import_data/', 'refresh');
			}

		}else{
			$this->session->set_flashdata('alert', 'alert-danger');

			$this->session->set_flashdata('flash_message', 'Import Failed '.$this->upload->display_errors());

			redirect(base_url() . 'admin/import_data/', 'refresh');
		}

		}
		$this->load->view('index', $page_data);
	}
    /** *Hilang* **/

    function hilang($param1 = '', $param2 = '', $param3 = '') {

        if ($this->session->userdata('login_type') != 'admin')

            $this->logout();

		$date = new DateTime();

		$id=$this->session->userdata('idsys');

        $page_data['page_action'] = 'list';
         $page_data['page_title'] = 'Status Buku (Hilang, Rusak, Diarsipkan, Dilelang)';
		$page_data['page_access'] = "admin";
		$page_data['page_name'] = 'hilang';
		$page_data['page_now'] = 'Transaksi';
		$page_data['klasifikasi']=$this->Md_siperpus_klasifikasi->getKlasifikasiAll();
        $page_data['kategori']=$this->Md_siperpus_kategori_buku->getKategoriBukuAll();
        $page_data['inventaris']=$this->Md_siperpus_inventaris->getInventarisAll();
        if ($param1 == 'submit') {
			$inven=$this->input->post('inventaris');
			$id=$this->Md_siperpus_hilangrusak->getLastId();
			$data['tgl_hr'] = $this->input->post('tanggal');
			$data['ket'] = $this->input->post('ket');
			if($this->input->post('anggota'))$data['no_anggota'] = $this->input->post('anggota');
			if($this->input->post('harga'))$data['biaya_ganti'] = $this->input->post('harga');

			if(!$inven || $data['tgl_hr']=='' || $data['ket']==''){
				//Empty Field
				$this->session->set_flashdata('alert', 'alert-danger');

				$this->session->set_flashdata('flash_message', 'Empty Field');
				echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Empty Field"));
			}else{
				$insert=0;
				$failed=0;
				$error='';
				$invs=$this->Md_siperpus_inventaris->getInventarisByBarcode($inven);
				if(count($invs)>0){
					$data['no_inv'] = $invs[0]->no_inv;
					if($this->Md_siperpus_hilangrusak->addHilang($data))$insert++;
				}else{
					$error='No. Barcode Tidak Ditemukan';
				}

				if($insert==0){
					$this->session->set_flashdata('alert', 'alert-danger');

					$this->session->set_flashdata('flash_message', 'Gagal Melakukan Penambahan Buku Hilang');
					echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Gagal Melakukan Penambahan Buku Hilang ".$error ));

				}else{
					$this->session->set_flashdata('alert', 'alert-focus');

					$this->session->set_flashdata('flash_message', 'Sukses Melakukan Penambahan Buku Hilang');
					echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Sukses Melakukan Penambahan Buku Hilang"));
					//redirect(base_url() . 'admin/klasifikasi_buku', 'refresh');
				}
			}
        }else if ($param1 == 'update') {

			$id1 = $this->input->post('id1');
			$data['tgl_hr'] = $this->input->post('tanggal');
			$data['no_inv'] = $this->input->post('inventaris');
			$data['ket'] = $this->input->post('ket');
			if($this->input->post('anggota'))$data['no_anggota'] = $this->input->post('anggota');
			if($this->input->post('harga'))$data['biaya_ganti'] = $this->input->post('harga');
			if($id1=='' || $data['no_inv']=='' || $data['tgl_hr']=='' || $data['ket']==''){
				//Empty Field
				$this->session->set_flashdata('alert', 'alert-danger');

				$this->session->set_flashdata('flash_message', 'Empty Field');
				echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Empty Field"));
			}else{

				//$data['pass'] = $pass;

				$this->Md_siperpus_hilangrusak->updateHilang($id1, $data);

				$this->session->set_flashdata('alert', 'alert-focus');

				$this->session->set_flashdata('flash_message', 'Transaksi Sukses diedit');
				//redirect(base_url() . 'admin/klasifikasi_buku', 'refresh');
				echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Transaksi Sukses diedit"));
			}

        }else if ($param1=='kembali' && $param2 !='') {
				$data['ket'] = 'K';
				$this->Md_siperpus_hilangrusak->updateHilang($param2, $data);
				$this->session->set_flashdata('alert', 'alert-focus');
				$this->session->set_flashdata('flash_message', 'Transaksi Sukses DiUpdate');
				echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Transaksi Sukses DiUpdate"));
		}else if ($param1=='hapus' && $param2 !='') {
				$this->Md_siperpus_hilangrusak->hapusHilang($param2);
				$this->session->set_flashdata('alert', 'alert-focus');
				$this->session->set_flashdata('flash_message', 'Transaksi Sukses Dihapus');
				echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Transaksi Sukses Dihapus"));
		}else if($param1 =='edit' && $param2 !=''){
			$data = $this->Md_siperpus_hilangrusak->getHilangById($param2);
			echo json_encode($data);

		}else if($param1 =='fetch'){
			$total=$this->Md_siperpus_hilangrusak->countFiltered();
			$page=intval($this->input->post('datatable[pagination][page]'));
			if($page<1)$page=1;
			$perpage=intval($this->input->post('datatable[pagination][perpage]'));
			$pages= intval($total/$perpage);
			$field=$this->input->post('datatable[sort][field]');
			if($field=='')$field=$this->input->post('datatable[pagination][field]');
			$sort=$this->input->post('datatable[sort][sort]');
			if($sort=='')$sort=$this->input->post('datatable[pagination][sort]');
			$data = array();
			$no = 0;
			$list=$this->Md_siperpus_hilangrusak->getDatatables();
			foreach ($list as $row) {
				$no++;
				$arr = array();
				$arr['number'] = ($perpage*($page-1))+$no;
				$arr['kd'] = $row->kd_hr;
				$buku=$this->Md_siperpus_buku->getBukuById($row->no_inv);
				if($buku)$arr['buku'] = $buku[0]->judul;
				$arr['tgl'] = $row->tgl_hr;
				$arr['anggota'] = $row->no_anggota;
				$arr['biaya'] = $row->biaya_ganti;
				$arr['keterangan'] = $row->ket;

				$data[] = $arr;
			}
			$meta=array();
			$meta['page']=$page;
			$meta['pages']=$pages;
			$meta['perpage']=$perpage;
			$meta['total']=$total;
			$meta['sort']=$sort;
			$meta['field']=$field;
			$output = array(
							"meta" => $meta,
							"data" => $data
					);
			//output to json format
			echo json_encode($output);

		}else{
			$this->load->view('index', $page_data);
		}
    }
	/** *Presensi* **/

    function presensi($param1 = '', $param2 = '', $param3 = '') {

        if ($this->session->userdata('login_type') != 'admin')

            $this->logout();

		$date = new DateTime();

		$id=$this->session->userdata('idsys');

        $page_data['page_action'] = 'list';
         $page_data['page_title'] = 'FORM KEHADIRAN DI PERPUSTAKAAN';
		$page_data['page_access'] = "admin";
		$page_data['page_name'] = 'presensi';
		$page_data['page_now'] = 'Transaksi';
        if ($param1 == 'submit') {

			$exist=false;
			$nomor=$this->input->post('nomor');
			if($this->Md_vwdosen->getDosenById($nomor))$exist=true;
			if(!$exist)if($this->Md_vwsiswa->getSiswaById($nomor))$exist=true;
			$data['tanggal'] = date("Y-m-d H:i:s");

			if(!$exist || $nomor ==''){
				//Empty Field
				$this->session->set_flashdata('alert', 'alert-danger');

				$this->session->set_flashdata('flash_message', 'Nomor Tidak ditemukan');
				//echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Nomor Tidak ditemukan"));
				redirect(base_url() . 'admin/presensi/', 'refresh');
			}else{
				$data['nis'] = $nomor;
				$this->Md_siperpus_presensi->addPresensi($data);
				$this->session->set_flashdata('alert', 'alert-focus');
				$this->session->set_flashdata('flash_message', 'Sukses Menambah Buku Tamu '.$exist);
				redirect(base_url() . 'admin/presensi/', 'refresh');
				//echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Sukses Menambah Buku Tamu"));
			}
        }else if ($param1 == 'submitnon') {

			$exist=false;
			$nama=$this->input->post('nama');
			$asal=$this->input->post('asal');
			$tgl=$this->input->post('tgl');
			$data['tanggal'] = $tgl;
			$data['nama'] = $this->input->post('nama');
			$data['asal'] = $this->input->post('asal');

			if( $data['nama']=='' || $data['asal']=='' ){
				//Empty Field
				$this->session->set_flashdata('alert', 'alert-danger');

				$this->session->set_flashdata('flash_message', 'Empty Field');
				redirect(base_url() . 'admin/presensi/', 'refresh');
				//echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Empty Field"));
			}else{
				$this->Md_siperpus_presensi->addPresensiNon($data);
				$this->session->set_flashdata('alert', 'alert-focus');

				$this->session->set_flashdata('flash_message', 'Sukses Menambah Buku Tamu');
				redirect(base_url() . 'admin/presensi/', 'refresh');
				//echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Sukses Menambah Buku Tamu"));
			}
        }else if($param1 =='detail' && $param2 !=''){
			$data = $this->Md_siperpus_presensi->getDetailByTanggal($param2);
			$presensi=array();
			foreach($data as $row){
			$arr = array();
			$arr['tgl'] = $row->tgl;
			$arr['jam'] = $row->jam;
			$jenis=$row->jenis;
			if($jenis=='nonanggota'){
			$arr['nomor'] = '';
			$arr['nama'] = $row->nomor;
			}else{
				$arr['nomor'] = $row->nomor;
				$mhs=$this->Md_vwsiswa->getSiswaById($row->nomor);
				if($mhs){
					$arr['nama']=$mhs[0]->nama;
				}else{
					$dosen=$this->Md_vwdosen->getDosenById($row->nomor);
					if($dosen)$arr['nama']=$dosen[0]->nama;
					else $arr['nama']='-';
				}
			}
			$presensi[] = $arr;
			}
			$size=count($presensi);
			echo json_encode(array("status" => TRUE,"tanggal" => $param2,"table" => $presensi,"size" => $size));
		}else if($param1 =='fetch'){
			$data = array();
			$no = 0;
			$list=$this->Md_siperpus_presensi->getPresensi7Hari();
			foreach ($list as $row) {
				$no++;
				$arr = array();
				$arr['number'] = $no;
				$arr['tanggal'] = substr($row->tanggal,0,10);
				$arr['jumlah'] =$this->Md_siperpus_presensi->getJumlah(substr($row->tanggal,0,10));

				$data[] = $arr;
			}
			$output = array(
							"data" => $data
					);
			//output to json format
			echo json_encode($output);

		}else{
			$this->load->view('index', $page_data);
		}
    }
	/** *Transaksi* **/

   function transaksi($param1 = '', $param2 = '', $param3 = '') {

        if ($this->session->userdata('login_type') != 'admin')

            $this->logout();

		$date = new DateTime();

		$id=$this->session->userdata('idsys');

        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Transaksi Buku';
		$page_data['page_access'] = "admin";
		$page_data['page_name'] = 'transaksi';
		$page_data['page_now'] = 'Transaksi';
		$page_data['search'] = false;
		$config=$this->Md_siperpus_config_transaksi->getConfigAll('m');
		$page_data['lama_pinjam']=$config[0]->lama;
        $page_data['denda']=$config[0]->denda;
        if ($param1 == 'submit') {
			$date = date('Y-m-d', strtotime('+5 days'));
			$insert=0;
			$inv=$this->input->post('inventaris');
			$dtinv=$this->Md_siperpus_inventaris->getInventarisById($inv);
			$id1=$this->input->post('id1');
			if($dtinv){
			if(strlen($this->input->post('tanggalpinjam'))==10)$data['tgl_pinjam']=$this->input->post('tanggalpinjam');
			else $data['tgl_pinjam']= date("Y-m-d");
			if(strlen($this->input->post('tanggalkembali'))==10)$data['batas']=$this->input->post('tanggalkembali');
			else $data['batas']=$date;
			$data['no_anggota']=$id1;
			$data['denda']=0;
			$data['kembali']=0;
			$isPinjam=$this->Md_siperpus_transaksi->isPinjam($dtinv[0]->no_inv);
			if(count($dtinv)==0){
				$this->session->set_flashdata('alert', 'alert-danger');
				$this->session->set_flashdata('flash_message', 'Buku Tidak Tercatat');
				redirect(base_url() . 'admin/transaksi/search/'.$id1, 'refresh');
			}else if($isPinjam){
				$this->session->set_flashdata('alert', 'alert-danger');
				$this->session->set_flashdata('flash_message', 'Buku Sedang Dipinjam');
				redirect(base_url() . 'admin/transaksi/search/'.$id1, 'refresh');
			}else if($id1=='' || $data['tgl_pinjam']=='' || $data['batas']==''){

				$this->session->set_flashdata('alert', 'alert-danger');
					$this->session->set_flashdata('flash_message', 'Empty Field');
					redirect(base_url() . 'admin/transaksi/search/'.$id1, 'refresh');
			}else{
				//pengecekan no inventory
				$data['tid']=$this->Md_siperpus_transaksi->getLastId()+1;
				$data['no_inv'] = $dtinv[0]->no_inv;
				$pinjam_ke=$this->Md_siperpus_transaksi->getPinjamKe($id1,$dtinv[0]->no_inv)+1;
				$data['pinjam_ke']=$pinjam_ke;
				if($this->Md_siperpus_transaksi->addTransaksi($data))$insert++;

				if($insert>0){
					$this->session->set_flashdata('alert', 'alert-focus');
					$this->session->set_flashdata('flash_message', 'Sukses Menambah Peminjaman Buku');
					redirect(base_url() . 'admin/transaksi/search/'.$id1, 'refresh');
				}else{
					$this->session->set_flashdata('alert', 'alert-danger');
					$this->session->set_flashdata('flash_message', 'Gagal Menambah Transaksi');
					redirect(base_url() . 'admin/transaksi/search/'.$id1, 'refresh');
				}
			}
			}else{
				$this->session->set_flashdata('alert', 'alert-danger');
				$this->session->set_flashdata('flash_message', 'Buku Tidak Tercatat');
				redirect(base_url() . 'admin/transaksi/search/'.$id1, 'refresh');
			}
        }else if($param1 =='search' && $param2 !=''){
			$error=false;
			$search=$param2;
			$arr = array();
			$arr['nomor']=$search;
			$arr['nama']='-';
			$arr['kelas']='-';
			$arr['alamat']='-';
			$arr['telepon']='-';
			$mhs=$this->Md_vwsiswa->getSiswaById($search);
			if($mhs){
					$arr['nama']=$mhs[0]->nama;
					$arr['kelas']=$mhs[0]->kelas;
					$arr['alamat']=$mhs[0]->alamat;
					$arr['telepon']=$mhs[0]->telepon;
			}else{
					$dosen=$this->Md_vwdosen->getDosenById($search);
					if($dosen){
						$arr['nama']=$dosen[0]->nama;
						$arr['kelas']=$dosen[0]->kelas;
						$karyawan=$this->Md_vwkaryawan->getKaryawanById($search);
						if($karyawan){
							$arr['alamat']=$karyawan[0]->alamat;
							$arr['telepon']=$karyawan[0]->telepon;
						}
					}else {
						$bar=$this->Md_siperpus_inventaris->getInventarisByBarcode($search);
						if($bar && count($bar)==0){
							$error=true;
						}else{
							//barcode ditemukan
							$tran=$this->Md_siperpus_transaksi->getTransaksiByNoInv($bar[0]->no_inv);
							if($tran && count($tran)>0)
							$arr['nomor']=$tran[0]->no_anggota;
						}
					}
			}

			if($error){
				$this->session->set_flashdata('alert', 'alert-danger');
				$this->session->set_flashdata('flash_message', 'No Anggota Tidak Ditemukan');
				redirect(base_url() . 'admin/transaksi', 'refresh');
			}else{
				$page_data['page_action'] = 'detail';
				$page_data['search'] = $arr;
				$this->load->view('index', $page_data);
			}
		}else if($param1 =='search'){
			$error=false;
			$errmsg='';
			$search=$this->input->post('search');
			$mhs=$this->Md_vwsiswa->getSiswaById($search);
			if(!$mhs){
				$dosen=$this->Md_vwdosen->getDosenById($search);
				if(!$dosen)$error=true;
				if($error){
					$errmsg='No. Anggota '.$search.' Tidak Ditemukan';
				}
			}

			if($error){
				$this->session->set_flashdata('alert', 'alert-danger');
				$this->session->set_flashdata('flash_message', $errmsg);
				redirect(base_url() . 'admin/transaksi', 'refresh');
			}else{
				redirect(base_url() . 'admin/transaksi/search/'.$search, 'refresh');
			}

		}else if ($param1=='hilang') {
				//insert ke table hilangrusak
				$idh = $this->input->post('idh');
				$tran=$this->Md_siperpus_transaksi->getTransaksiById($idh);
				if(count($tran)>0){
					$data['tgl_hr'] = date("Y-m-d");
					$data['ket'] = 'H';
					$data['no_anggota'] = $tran[0]->no_anggota;
					$data['biaya_ganti'] =$this->input->post('hilang');
					$data['no_inv'] = $tran[0]->no_inv;
					$this->Md_siperpus_hilangrusak->addHilang($data);

				$datatran['denda'] =str_replace(',','',$this->input->post('hilang'));
				$this->Md_siperpus_transaksi->updateTransaksi($idh, $datatran);

				echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Transaksi yang hilang Sukses DiUpdate"));
				}else{
				echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Transaksi Tidak ditemukan"));
				}
		}else if ($param1=='perpanjang') {
				$idp = $this->input->post('idp');
				if($idp){
				$data['batas'] = $this->input->post('tanggalperpanjang');
				$this->Md_siperpus_transaksi->updateTransaksi($idp, $data);
				echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Perpajangan Transaksi ke "+$data['batas']+" Sukses DiUpdate"));
				}else{
				echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Empty Field"));
				}
		}else if ($param1=='kembali') {
				$idk = $this->input->post('idk');
				if($idk){
				$data['denda'] =str_replace(',','',$this->input->post('denda'));
				$data['tgl_kembali'] = date("Y-m-d");
				$data['kembali'] = 1;
				$this->Md_siperpus_transaksi->updateTransaksi($idk, $data);
				echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Transaksi Sukses DiUpdate"));
				}else{
				echo json_encode(array("status" => TRUE,"alert" => "alert-danger","msg" => "Empty Field"));
				}
		}else if ($param1=='hapus' && $param2 !='') {
				$this->Md_siperpus_transaksi->hapusTransaksi($param2);
				echo json_encode(array("status" => TRUE,"alert" => "alert-focus","msg" => "Transaksi Sukses Dihapus"));
		}else if($param1 =='detail' && $param2 !=''){
			$tran = $this->Md_siperpus_transaksi->getTransaksiById($param2);
			$arr = array();
		  if(count($tran)>0){
			$data = $this->Md_siperpus_inventaris->getInventarisById($tran[0]->no_inv);
			 $arr['status']=TRUE;
		   $arr['referensi']='';
		   $arr['klas']=$data[0]->no_klas;
		   $arr['tanggal']=$data[0]->tgl_inv;
		   $arr['isbn']=$data[0]->ISBN;


		   $buku=$this->Md_siperpus_buku->getBukuByISBN($data[0]->ISBN,$data[0]->no_klas);
		   if($buku[0]->bahasa =='I')$arr['bahasa']='Indonesia';
		   else if($buku[0]->bahasa =='A')$arr['bahasa']='Asing';
		   else $arr['bahasa']='';
		   $arr['judul']=$buku[0]->judul;
		   $arr['tajuk']=$buku[0]->tajuksubyek;
		   $arr['penulis']=$buku[0]->penulis;
		   $arr['edisi']=$buku[0]->edisi;
		   $arr['cetakan']=$buku[0]->cetakan;
		   $arr['tahun']=$buku[0]->thn_terbit;
		   $arr['jumlah']=$buku[0]->jml_hal;
		   $arr['ukuran']=$buku[0]->ukuran_fisik;
		   $arr['rak']=$buku[0]->no_rak;
		   $arr['review']=$buku[0]->review;
		   $arr['deskripsi']=$buku[0]->deskripsi;

		  $penerbit=$this->Md_siperpus_penerbit->getPenerbitById($buku[0]->kd_penerbit);
		   $arr['penerbit']=$penerbit[0]->nama_penerbit;
		   $arr['kota']=$penerbit[0]->kota;

		   $stok=$this->Md_siperpus_inventaris->getInventarisByISBNdanNoKlas($data[0]->ISBN,$data[0]->no_klas);
		   $arr['stok']=count($stok);

		   $pinjam=$this->Md_siperpus_transaksi->getTransaksiDipinjamByISBN($data[0]->ISBN,$data[0]->no_klas);
		   $arr['pinjam']=count($pinjam);
		   }else{
			$arr['status']=FALSE;
			}
			echo json_encode($arr);

		}else if($param1 =='fetch' && $param2 !=''){
			$total=$this->Md_siperpus_transaksi->countFiltered($param2);
			$page=intval($this->input->post('datatable[pagination][page]'));
			if($page<1)$page=1;
			$perpage=intval($this->input->post('datatable[pagination][perpage]'));
			$pages= intval($total/$perpage);
			$field=$this->input->post('datatable[sort][field]');
			if($field=='')$field=$this->input->post('datatable[pagination][field]');
			$sort=$this->input->post('datatable[sort][sort]');
			if($sort=='')$sort=$this->input->post('datatable[pagination][sort]');
			$data = array();
			$no = 0;
			$list=$this->Md_siperpus_transaksi->getDatatables($param2);
			foreach ($list as $row) {
				$no++;
				$arr = array();
				$arr['number'] = ($perpage*($page-1))+$no;
				$arr['id'] = $row->tid;
				$arr['noinv'] = $row->no_inv;
				$buku=$this->Md_siperpus_buku->getBukuById($row->no_inv);
				if($buku)$arr['buku'] = $buku[0]->judul;
				$arr['tgl'] = $row->tgl_pinjam;
				$arr['batas'] = $row->batas;

				$Tgl=date('Y-m-d');
				$tglkembali = strtotime($row->batas);
				$datediff = strtotime($Tgl) - $tglkembali;
				//mengecek tanggal yang lebih tinggi dan cek hari libur di siperpus_libur
				if(strtotime($Tgl) < strtotime($row->batas)){
					$libur=$this->Md_siperpus_libur->haveLibur($Tgl,$row->batas);
				}else{
					$libur=$this->Md_siperpus_libur->haveLibur($row->batas,$Tgl);
				}
				$lewathari=floor($datediff / (60 * 60 * 24))-count($libur);
				if($lewathari>0){
					$arr['terlambat'] = $lewathari.' Hari';
					$arr['denda'] = $config[0]->denda * $lewathari;
				}else{
					$arr['terlambat'] = '-';
					$arr['denda'] = 0;
				}
				if($row->tgl_kembali !='0000-00-00'){
				$date1 = new DateTime($row->batas);
				$date2 = new DateTime($row->tgl_kembali);
					if($date2>$date1){
						$interval = $date1->diff($date2);
						$arr['terlambat'] = $interval->days.' Hari';
					}
				}

				$arr['pinjam'] = $row->pinjam_ke;

				$data[] = $arr;
			}
			$meta=array();
			$meta['page']=$page;
			$meta['pages']=$pages;
			$meta['perpage']=$perpage;
			$meta['total']=$total;
			$meta['sort']=$sort;
			$meta['field']=$field;
			$output = array(
							"meta" => $meta,
							"data" => $data
					);
			//output to json format
			echo json_encode($output);


		}else{
			$this->load->view('index', $page_data);
		}
    }
	/** *Pengembalian* **/
	function pengembalian($param1 = '', $param2 = '', $param3 = '') {

        if ($this->session->userdata('login_type') != 'admin')

            $this->logout();

		$date = new DateTime();

		$id=$this->session->userdata('idsys');

        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Pengembalian Buku';
		$page_data['page_access'] = "admin";
		$page_data['page_name'] = 'pengembalian';
		$page_data['page_now'] = 'Transaksi';
		$page_data['search'] = false;
        if($param1 =='search'){
			$config=$this->Md_siperpus_config_transaksi->getConfigAll('m');
			$page_data['lama_pinjam']=$config[0]->lama;
			$page_data['denda']=$config[0]->denda;
			$error=false;
			$search=$this->input->post('search');
			$bar=$this->Md_siperpus_inventaris->getInventarisByBarcode($search);
			if(count($bar)>0){

				$noinv=$bar[0]->no_inv;

				$isPinjam=$this->Md_siperpus_transaksi->isPinjam($noinv);

				if($isPinjam){
					$arr['nomor']=$noinv;
					$buku=$this->Md_siperpus_buku->getBukuByISBN($bar[0]->ISBN,$bar[0]->no_klas);
					$tran=$this->Md_siperpus_transaksi->getTransaksiTanpaKartu($noinv);
					$noanggota=$tran[0]->no_anggota;
					$mhs=$this->Md_vwsiswa->getSiswaById($noanggota);
					if(!$mhs){
						$dosen=$this->Md_vwdosen->getDosenById($noanggota);
						if(!$dosen){
							$error=true;
						}else{
							$arr['nama']=$dosen[0]->nama;
							$arr['kelas']=$dosen[0]->kelas;
							$karyawan=$this->Md_vwkaryawan->getKaryawanById($search);
							if($karyawan){
								$arr['alamat']=$karyawan[0]->alamat;
								$arr['telepon']=$karyawan[0]->telepon;
							}
						}
					}else{
						$arr['nama']=$mhs[0]->nama;
						$arr['kelas']=$mhs[0]->kelas;
						$arr['alamat']=$mhs[0]->alamat;
						$arr['telepon']=$mhs[0]->telepon;
					}
					if($error){
						$this->session->set_flashdata('alert', 'alert-danger');
						$this->session->set_flashdata('flash_message','No Barcode '.$search.' ditemukan<br> tetapi peminjam mungkin sudah terhapus keanggotaannya');
						redirect(base_url() . 'admin/pengembalian', 'refresh');
					}else{
						$Tgl=date('Y-m-d');
						$tglkembali = strtotime($tran[0]->batas);
						$datediff = strtotime($Tgl) - $tglkembali;
						//mengecek tanggal yang lebih tinggi dan cek hari libur di siperpus_libur
						if(strtotime($Tgl) < strtotime($tran[0]->batas)){
							$libur=$this->Md_siperpus_libur->haveLibur($Tgl,$tran[0]->batas);
						}else{
							$libur=$this->Md_siperpus_libur->haveLibur($tran[0]->batas,$Tgl);
						}
						$page_data['libur'] = count($libur);
						$lewathari=floor($datediff / (60 * 60 * 24))-count($libur);
						if($lewathari>0){
							$dendabuku=$config[0]->denda * $lewathari;
						}else{
						$dendabuku=0;
						}
						$page_data['dendabuku'] = $dendabuku;
						$page_data['tglkembali']= $Tgl;
						$page_data['page_action'] = $search;
						$page_data['detail'] = $tran;
						$page_data['search'] = $arr;
						$page_data['buku'] = $buku;
						$this->load->view('index', $page_data);
					}
				}else{
					$this->session->set_flashdata('alert', 'alert-danger');
					$this->session->set_flashdata('flash_message', 'No Barcode '.$search.' tidak ditemukan.<br> Belum Dipinjam Atau sudah dikembalikan');
					redirect(base_url() . 'admin/pengembalian', 'refresh');
				}
			}else{
				$this->session->set_flashdata('alert', 'alert-danger');
				$this->session->set_flashdata('flash_message', 'No Barcode '.$search.' Tidak Ditemukan');
				redirect(base_url() . 'admin/pengembalian', 'refresh');
			}
		}else if ($param1=='kembali') {
				$idk = $this->input->post('idk');
				if($idk){
				$data['denda'] = str_replace(',','',$this->input->post('denda'));
				$data['tgl_kembali'] = $this->input->post('kembali');
				$data['kembali'] = 1;
				$this->Md_siperpus_transaksi->updateTransaksi($idk, $data);
				$this->session->set_flashdata('alert', 'alert-success');
				$this->session->set_flashdata('flash_message', 'Sukses Melakukan Pengembalian');
				redirect(base_url() . 'admin/pengembalian', 'refresh');
				}else{
				$this->session->set_flashdata('alert', 'alert-danger');
				$this->session->set_flashdata('flash_message', 'Gagal Melakukan Pengembalian');
				redirect(base_url() . 'admin/pengembalian', 'refresh');
				}
		}else{
			$this->load->view('index', $page_data);
		}
    }
    function traninv($param1 = '', $param2 = '', $param3 = '') {

        if ($this->session->userdata('login_type') != 'admin')

            $this->logout();

		$date = new DateTime();

		$id=$this->session->userdata('idsys');

        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Kembalikan Buku Tanpa Kartu Anggota';
		$page_data['page_access'] = "admin";
		$page_data['page_name'] = 'traninv';
		$page_data['page_now'] = 'Transaksi';
		$page_data['search'] = false;
        if($param1 =='search'){
			$error=false;
			$search=$this->input->post('search');
			$inv=$this->Md_siperpus_inventaris->getInventarisByNoInv($search);
			$bar=$this->Md_siperpus_inventaris->getInventarisByBarcode($search);
			if(count($inv)>0 || count($bar)>0){
				if(count($bar)>0){
					$noinv=$bar[0]->no_inv;
					$ket='Barcode';
				}else{
					$noinv=$search;
					$ket='No. Inventaris';
				}
				$isPinjam=$this->Md_siperpus_transaksi->isPinjam($noinv);
				if($isPinjam){
					$tran=$this->Md_siperpus_transaksi->getTransaksiTanpaKartu($noinv);
					$noanggota=$tran[0]->no_anggota;
					$mhs=$this->Md_vwsiswa->getSiswaById($noanggota);
					if(!$mhs){
						$dosen=$this->Md_vwdosen->getDosenById($noanggota);
						if(!$dosen)$error=true;
					}
					if($error){
						$this->session->set_flashdata('alert', 'alert-danger');
						$this->session->set_flashdata('flash_message',$ket.' '.$search.' ditemukan<br> tetapi peminjam mungkin sudah terhapus keanggotaannya');
						redirect(base_url() . 'admin/traninv', 'refresh');
					}else{
						$this->session->set_flashdata('alert', 'alert-focus');
						$this->session->set_flashdata('flash_message', $ket.' '.$search.' ditemukan');
						redirect(base_url() . 'admin/transaksi/search/'.$noanggota, 'refresh');
					}
				}else{
					$this->session->set_flashdata('alert', 'alert-danger');
					$this->session->set_flashdata('flash_message', $ket.' '.$search.' tidak ditemukan.<br> Belum Dipinjam Atau sudah dikembalikan');
					redirect(base_url() . 'admin/traninv', 'refresh');
				}
			}else{
				$this->session->set_flashdata('alert', 'alert-danger');
				$this->session->set_flashdata('flash_message', 'No Inventaris/Barcode '.$search.' Tidak Ditemukan');
				redirect(base_url() . 'admin/traninv', 'refresh');
			}
		}else{
			$this->load->view('index', $page_data);
		}
    }
	/** *Laporan* **/

	function laporan_anggota($param1='',$param2='')
	{
		if ($this->session->userdata('login_type') != 'admin')
				$this->logout();

		$date = new DateTime();
		$id=$this->session->userdata('idsys');
		$page_data['src']='';
		$page_data['tanggalawal']='';
		$page_data['tanggalakhir']='';
		$page_data['jenis'] = 'mahasiswa';
		if($this->input->post('pilih')=='dosen')
		$page_data['jenis'] = 'dosen';
		if($this->input->post('pilih')=='anggota+luar')
		$page_data['jenis'] = 'anggota+luar';
		$page_data['page_title'] = 'Laporan Data Anggota';

		if($this->input->post('src'))$page_data['src'] = $this->input->post('src');
		if($this->input->post('tanggalawal'))$page_data['tanggalawal'] = $this->input->post('tanggalawal');
		if($this->input->post('tanggalakhir'))$page_data['tanggalakhir'] = $this->input->post('tanggalakhir');

		if($param1=='export'){
			$jenis='anggota+luar';
			$tanggalawal='';
			$tanggalakhir='';
			$search='';

			if($param2=='excel'){
				$jenis=$this->input->post('epilih');
				$tanggalawal=$this->input->post('etanggalawal');
				$tanggalakhir=$this->input->post('etanggalakhir');
				$search=$this->input->post('esearch');
			}elseif($param2=='web'){
				$jenis=$this->input->post('wpilih');
				$tanggalawal=$this->input->post('wtanggalawal');
				$tanggalakhir=$this->input->post('wtanggalakhir');
				$search=$this->input->post('wsearch');
			}
			$page_data['laporan'] = 'laporan_anggota';
			$page_data['export'] =$param2;
			$page_data['page_title'] = 'Laporan Data Anggota '.$jenis;
			$page_data['title'] = 'Laporan Anggota ';
			$page_data['data']=$this->Md_laporan_anggota->getLaporan($jenis,$tanggalawal,$tanggalakhir,$search);
			$this->load->view('admin/excel', $page_data);
		}else if($param1 =='fetch'){
			$jenis=$param2;
			$total=$this->Md_laporan_anggota->countFiltered($jenis);
				$page=intval($this->input->post('datatable[pagination][page]'));
			if($page<1)
				$page=1;

			$perpage=intval($this->input->post('datatable[pagination][perpage]'));
			$pages= intval($total/$perpage);
			$field=$this->input->post('datatable[sort][field]');

			if($field=='')
				$field=$this->input->post('datatable[pagination][field]');

			$sort=$this->input->post('datatable[sort][sort]');

			if($sort=='')
				$sort=$this->input->post('datatable[pagination][sort]');

			//mulai fetching data
			$data = array();
			$no = 0;
			$list=$this->Md_laporan_anggota->getDatatables($jenis);
			foreach ($list as $row) {
				$no++;
				$arr = array();
				if($jenis=='dosen'){
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['nip'] = $row->nip;
					$arr['nama'] = $row->nama;
					$arr['jenis'] = $row->jk;
					$arr['tgl'] = $row->tanggal;
					$arr['berlaku'] = $row->berlaku_sampai;

				}else if($jenis=='anggota+luar'){
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['nama'] = $row->nama;
					$arr['jenis'] = $row->jk;
					$arr['instansi'] = $row->instansi_asal_nama;
					$arr['telepon'] = $row->telepon;
				}else{
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['nis'] = $row->nis;
					$arr['nama'] = $row->nama;
					$arr['kelas'] = $row->kelas;
					$arr['jenis'] = $row->jk;
					if ($row->status_siswa=='A'){
						$status_siswa="Aktif";
					}
					$arr['status_siswa'] = $status_siswa;
					$arr['status_anggota'] = $status_siswa;
					$arr['tgl'] = $row->tanggal;
					$arr['berlaku'] = $row->berlaku_sampai;
				}

				$data[] = $arr;
			}

			$meta=array();
			$meta['page']=$page;
			$meta['pages']=$pages;
			$meta['perpage']=$perpage;
			$meta['total']=$total;
			$meta['sort']=$sort;
			$meta['field']=$field;
			$output = array(
							"meta" => $meta,
							"data" => $data
					);
			//output to json format
			echo json_encode($output);
		}else{
			$page_data['page_access'] = "admin";
			$page_data['page_name'] = 'laporan_anggota';
			$page_data['page_now'] = 'dashboard';
			$this->load->view('index', $page_data);
		}
	}
	function laporan_inventaris($param1='',$param2='')
	{
		if ($this->session->userdata('login_type') != 'admin')
				$this->logout();

		$date = new DateTime();
		$id=$this->session->userdata('idsys');
		$page_data['src']='';
		$page_data['tanggalawal']='';
		$page_data['tanggalakhir']='';
		$page_data['klas'] = '';
		$page_data['ktg'] = '';
		$page_data['page_title'] = 'Daftar Inventaris';

		$page_data['klasifikasi'] = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
		$page_data['kategori'] = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
		if($this->input->post('src'))$page_data['src'] = $this->input->post('src');
		if($this->input->post('klasifikasi'))$page_data['klas'] = $this->input->post('klasifikasi');
		if($this->input->post('kategori'))$page_data['ktg'] = $this->input->post('kategori');
		if($this->input->post('tanggalawal'))$page_data['tanggalawal'] = $this->input->post('tanggalawal');
		if($this->input->post('tanggalakhir'))$page_data['tanggalakhir'] = $this->input->post('tanggalakhir');

		if($param1=='export'){
			$klas='';
			$ktg='';
			$tanggalawal='';
			$tanggalakhir='';
			$search='';

			if($param2=='excel'){
				$klas=$this->input->post('eklas');
				$ktg=$this->input->post('ektg');
				$tanggalawal=$this->input->post('etanggalawal');
				$tanggalakhir=$this->input->post('etanggalakhir');
				$search=$this->input->post('esearch');
			}elseif($param2=='web'){
				$klas=$this->input->post('wklas');
				$ktg=$this->input->post('wktg');
				$tanggalawal=$this->input->post('wtanggalawal');
				$tanggalakhir=$this->input->post('wtanggalakhir');
				$search=$this->input->post('wsearch');
			}
			$page_data['nmktg'] = $this->Md_siperpus_kategori_buku->getKategoriById($this->input->post('kategori'));
			$page_data['laporan'] = 'laporan_inventaris';
			$page_data['export'] =$param2;
			$page_data['page_title'] = 'Daftar Inventaris ';
			$page_data['title'] = 'Daftar Inventaris';
			$page_data['data']=$this->Md_laporan_inventaris->getLaporan($klas,$ktg,$tanggalawal,$tanggalakhir,$search);
			$this->load->view('admin/excel', $page_data);
		}else if($param1 =='fetch'){
			$total=$this->Md_laporan_inventaris->countFiltered();
			$page=intval($this->input->post('datatable[pagination][page]'));
			if($page<1)
				$page=1;

			$perpage=intval($this->input->post('datatable[pagination][perpage]'));
			$pages= intval($total/$perpage);
			$field=$this->input->post('datatable[sort][field]');

			if($field=='')
				$field=$this->input->post('datatable[pagination][field]');

			$sort=$this->input->post('datatable[sort][sort]');

			if($sort=='')
				$sort=$this->input->post('datatable[pagination][sort]');

			//mulai fetching data
			$data = array();
			$no = 0;
			$list=$this->Md_laporan_inventaris->getDatatables();
			foreach ($list as $row) {
				$no++;
				$arr = array();
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['penulis'] = $row->penulis;
					$arr['judul'] = $row->judul;
					$arr['edisi'] = $row->edisi;
					$arr['penerbit'] = $row->penerbit;
					$arr['tahun'] = $row->thn_terbit;
					$arr['ISBN'] = $row->ISBN;
					$arr['jumlah'] = $row->jml_buku;
					$arr['hr'] = $row->hilang;
					$arr['noklas'] = $row->no_klas;
					$arr['tanggal'] = $row->tanggal;
				$data[] = $arr;
			}

			$meta=array();
			$meta['page']=$page;
			$meta['pages']=$pages;
			$meta['perpage']=$perpage;
			$meta['total']=$total;
			$meta['sort']=$sort;
			$meta['field']=$field;
			$output = array(
							"meta" => $meta,
							"data" => $data
					);
			//output to json format
			echo json_encode($output);
		}else{
			$page_data['page_access'] = "admin";
			$page_data['page_name'] = 'laporan_inventaris';
			$page_data['page_now'] = 'dashboard';
			$this->load->view('index', $page_data);
		}
	}
	function laporan_kataloginventaris($param1='',$param2='')
	{
		if ($this->session->userdata('login_type') != 'admin')
				$this->logout();

		$date = new DateTime();
		$id=$this->session->userdata('idsys');
		$page_data['src']='';
		$page_data['tanggalawal']='';
		$page_data['tanggalakhir']='';
		$page_data['klas'] = '';
		$page_data['ktg'] = '';
		$page_data['page_title'] = 'Daftar Inventaris';

		$page_data['klasifikasi'] = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
		$page_data['kategori'] = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
		if($this->input->post('src'))$page_data['src'] = $this->input->post('src');
		if($this->input->post('klasifikasi'))$page_data['klas'] = $this->input->post('klasifikasi');
		if($this->input->post('kategori'))$page_data['ktg'] = $this->input->post('kategori');

		$page_data['data']=$this->Md_laporan_kataloginventaris->getKatalog($page_data['klas'],$page_data['ktg'],$page_data['src']);

		if($param1=='export'){
			$klas='';
			$ktg='';
			$search='';

			if($param2=='excel'){
				$klas=$this->input->post('eklas');
				$ktg=$this->input->post('ektg');
				$search=$this->input->post('esearch');
			}elseif($param2=='web'){
				$klas=$this->input->post('wklas');
				$ktg=$this->input->post('wktg');
				$search=$this->input->post('wsearch');
			}
			$page_data['nmktg'] = $this->Md_siperpus_kategori_buku->getKategoriById($this->input->post('kategori'));
			$page_data['laporan'] = 'laporan_kataloginventaris';
			$page_data['export'] =$param2;
			$page_data['page_title'] = 'Daftar Inventaris ';
			$page_data['title'] = 'Daftar Inventaris';
			$page_data['data']=$this->Md_laporan_kataloginventaris->getKatalog($klas,$ktg,$search);
			$this->load->view('admin/excel', $page_data);
		}else if($param1 =='fetch'){
			$total=$this->Md_laporan_kataloginventaris->countFiltered();
			$page=intval($this->input->post('datatable[pagination][page]'));
			if($page<1)
				$page=1;

			$perpage=intval($this->input->post('datatable[pagination][perpage]'));
			$pages= intval($total/$perpage);
			$field=$this->input->post('datatable[sort][field]');

			if($field=='')
				$field=$this->input->post('datatable[pagination][field]');

			$sort=$this->input->post('datatable[sort][sort]');

			if($sort=='')
				$sort=$this->input->post('datatable[pagination][sort]');

			//mulai fetching data
			$data = array();
			$no = 0;
			$list=$this->Md_laporan_kataloginventaris->getDatatables();
			foreach ($list as $row) {
				$no++;
				$arr = array();
					$arr['number'] = ($perpage*($page-1))+$no;
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

			$meta=array();
			$meta['page']=$page;
			$meta['pages']=$pages;
			$meta['perpage']=$perpage;
			$meta['total']=$total;
			$meta['sort']=$sort;
			$meta['field']=$field;
			$output = array(
							"meta" => $meta,
							"data" => $data
					);
			//output to json format
			echo json_encode($output);
		}else{
			$page_data['page_access'] = "admin";
			$page_data['page_name'] = 'laporan_kataloginventaris';
			$page_data['page_now'] = 'dashboard';
			$this->load->view('index', $page_data);
		}
	}
	function laporan_hilang($param1='',$param2='')
	{
		if ($this->session->userdata('login_type') != 'admin')
				$this->logout();

		$date = new DateTime();
		$id=$this->session->userdata('idsys');
		$page_data['tanggalawal']='';
		$page_data['tanggalakhir']='';
		$page_data['page_title'] = 'Daftar Buku Hilang/Rusak';

		if($this->input->post('tanggalawal'))$page_data['tanggalawal'] = $this->input->post('tanggalawal');
		if($this->input->post('tanggalakhir'))$page_data['tanggalakhir'] = $this->input->post('tanggalakhir');
		if($param1=='export'){
			$tanggalawal='';
			$tanggalakhir='';
			$search='';

			if($param2=='excel'){
				$tanggalawal=$this->input->post('etanggalawal');
				$tanggalakhir=$this->input->post('etanggalakhir');
				$search=$this->input->post('esearch');
			}elseif($param2=='web'){
				$tanggalawal=$this->input->post('wtanggalawal');
				$tanggalakhir=$this->input->post('wtanggalakhir');
				$search=$this->input->post('wsearch');
			}
			$page_data['laporan'] = 'laporan_hilang';
			$page_data['export'] =$param2;
			$page_data['title'] = 'Daftar Buku Hilang/Rusak';
			$page_data['data']=$this->Md_laporan_hilang->getLaporan($tanggalawal,$tanggalakhir,$search);
			$this->load->view('admin/excel', $page_data);
		}else if($param1 =='fetch'){
			$total=$this->Md_laporan_hilang->countFiltered();
			$page=intval($this->input->post('datatable[pagination][page]'));
			if($page<1)
				$page=1;

			$perpage=intval($this->input->post('datatable[pagination][perpage]'));
			$pages= intval($total/$perpage);
			$field=$this->input->post('datatable[sort][field]');

			if($field=='')
				$field=$this->input->post('datatable[pagination][field]');

			$sort=$this->input->post('datatable[sort][sort]');

			if($sort=='')
				$sort=$this->input->post('datatable[pagination][sort]');

			//mulai fetching data
			$data = array();
			$no = 0;
			$list=$this->Md_laporan_hilang->getDatatables();
			foreach ($list as $row) {
				$no++;
				$arr = array();
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['noinv'] = $row->no_inv;
					$arr['judul'] = $row->judul;
					$arr['tanggal'] = $row->tgl_hr;
					$arr['nomor'] = $row->no_anggota;
					$arr['biaya'] = $row->biaya_ganti;
					$arr['keterangan'] = $row->ket;
				$data[] = $arr;
			}

			$meta=array();
			$meta['page']=$page;
			$meta['pages']=$pages;
			$meta['perpage']=$perpage;
			$meta['total']=$total;
			$meta['sort']=$sort;
			$meta['field']=$field;
			$output = array(
							"meta" => $meta,
							"data" => $data
					);
			//output to json format
			echo json_encode($output);
		}else{
			$page_data['page_access'] = "admin";
			$page_data['page_name'] = 'laporan_hilang';
			$page_data['page_now'] = 'dashboard';
			$this->load->view('index', $page_data);
		}
	}
	function laporan_denda($param1='',$param2='')
	{
		if ($this->session->userdata('login_type') != 'admin')
				$this->logout();

		$date = new DateTime();
		$id=$this->session->userdata('idsys');
		$page_data['src']='';
		$page_data['tanggalawal']='';
		$page_data['tanggalakhir']='';
		$page_data['jenis'] = 'mahasiswa';
		if($this->input->post('pilih')=='dosen')
		$page_data['jenis'] = 'dosen';
		if($this->input->post('pilih')=='anggota+luar')
		$page_data['jenis'] = 'anggota+luar';
		$page_data['page_title'] = 'Form Periode Denda';

		if($this->input->post('src'))$page_data['src'] = $this->input->post('src');
		if($this->input->post('tanggalawal'))$page_data['tanggalawal'] = $this->input->post('tanggalawal');
		if($this->input->post('tanggalakhir'))$page_data['tanggalakhir'] = $this->input->post('tanggalakhir');

		if($param1=='export'){
			$jenis='anggota+luar';
			$tanggalawal='';
			$tanggalakhir='';
			$search='';

			if($param2=='excel'){
				$jenis=$this->input->post('epilih');
				$tanggalawal=$this->input->post('etanggalawal');
				$tanggalakhir=$this->input->post('etanggalakhir');
				$search=$this->input->post('esearch');
			}elseif($param2=='web'){
				$jenis=$this->input->post('wpilih');
				$tanggalawal=$this->input->post('wtanggalawal');
				$tanggalakhir=$this->input->post('wtanggalakhir');
				$search=$this->input->post('wsearch');
			}
			$page_data['laporan'] = 'laporan_denda';
			$page_data['export'] =$param2;
			$page_data['page_title'] = 'Form Periode Denda '.$jenis;
			$page_data['title'] = 'Form Periode Denda ';
			$page_data['data']=$this->Md_laporan_denda->getLaporan($jenis,$tanggalawal,$tanggalakhir,$search);
			$this->load->view('admin/excel', $page_data);
		}else if($param1 =='fetch'){
			$total=$this->Md_laporan_denda->countFiltered();
				$page=intval($this->input->post('datatable[pagination][page]'));
			if($page<1)
				$page=1;

			$perpage=intval($this->input->post('datatable[pagination][perpage]'));
			$pages= intval($total/$perpage);
			$field=$this->input->post('datatable[sort][field]');

			if($field=='')
				$field=$this->input->post('datatable[pagination][field]');

			$sort=$this->input->post('datatable[sort][sort]');

			if($sort=='')
				$sort=$this->input->post('datatable[pagination][sort]');

			//mulai fetching data
			$data = array();
			$no = 0;
			$list=$this->Md_laporan_denda->getDatatables();
			foreach ($list as $row) {
				$no++;
				$arr = array();
				if($this->input->post('datatable[query][jenis]')=='dosen'){
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['nomor'] = $row->no_anggota;
					$arr['nama'] = $row->nama;
					$arr['prodi'] = '';
					$arr['judul'] = $row->judul;
					$arr['tanggal'] = $row->tgl_kembali;
					$arr['denda'] = $row->denda;

				}else if($this->input->post('datatable[query][jenis]')=='anggota+luar'){
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['nomor'] = $row->no_anggota;
					$arr['nama'] = $row->nama;
					$arr['prodi'] = '';
					$arr['judul'] = $row->judul;
					$arr['tanggal'] = $row->tgl_kembali;
					$arr['denda'] = $row->denda;
				}else{
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['nomor'] = $row->no_anggota;
					$arr['nama'] = $row->nama;
					$arr['prodi'] = $row->kelas;
					$arr['judul'] = $row->judul;
					$arr['tanggal'] = $row->tgl_kembali;
					$arr['denda'] = $row->denda;
				}

				$data[] = $arr;
			}

			$meta=array();
			$meta['page']=$page;
			$meta['pages']=$pages;
			$meta['perpage']=$perpage;
			$meta['total']=$total;
			$meta['sort']=$sort;
			$meta['field']=$field;
			$output = array(
							"meta" => $meta,
							"data" => $data
					);
			//output to json format
			echo json_encode($output);
		}else{
			$page_data['page_access'] = "admin";
			$page_data['page_name'] = 'laporan_denda';
			$page_data['page_now'] = 'dashboard';
			$this->load->view('index', $page_data);
		}
	}
	function laporan_presensi($param1='',$param2='')
	{
		if ($this->session->userdata('login_type') != 'admin')
				$this->logout();

		$date = new DateTime();
		$id=$this->session->userdata('idsys');
		$page_data['src']='';
		$page_data['tanggalawal']='';
		$page_data['tanggalakhir']='';
		$page_data['jenis'] = 'mahasiswa';
		if($this->input->post('pilih')=='dosen')
		$page_data['jenis'] = 'dosen';
		if($this->input->post('pilih')=='anggota+luar')
		$page_data['jenis'] = 'anggota+luar';
		$page_data['page_title'] = 'Daftar Presensi Kunjungan Perpustakaan';

		if($this->input->post('src'))$page_data['src'] = $this->input->post('src');
		if($this->input->post('tanggalawal'))$page_data['tanggalawal'] = $this->input->post('tanggalawal');
		if($this->input->post('tanggalakhir'))$page_data['tanggalakhir'] = $this->input->post('tanggalakhir');

		if($param1=='export'){
			$jenis='anggota+luar';
			$tanggalawal='';
			$tanggalakhir='';
			$search='';

			if($param2=='excel'){
				$jenis=$this->input->post('epilih');
				$tanggalawal=$this->input->post('etanggalawal');
				$tanggalakhir=$this->input->post('etanggalakhir');
				$search=$this->input->post('esearch');
			}elseif($param2=='web'){
				$jenis=$this->input->post('wpilih');
				$tanggalawal=$this->input->post('wtanggalawal');
				$tanggalakhir=$this->input->post('wtanggalakhir');
				$search=$this->input->post('wsearch');
			}
			$page_data['laporan'] = 'laporan_presensi';
			$page_data['export'] =$param2;
			$page_data['page_title'] = 'Daftar Presensi Kunjungan Perpustakaan ';
			$page_data['title'] = 'Daftar '.$jenis;
			$page_data['data']=$this->Md_laporan_presensi->getLaporan($jenis,$tanggalawal,$tanggalakhir,$search);
			$this->load->view('admin/excel', $page_data);
		}else if($param1 =='fetch'){
			$total=$this->Md_laporan_presensi->countFiltered();
				$page=intval($this->input->post('datatable[pagination][page]'));
			if($page<1)
				$page=1;

			$perpage=intval($this->input->post('datatable[pagination][perpage]'));
			$pages= intval($total/$perpage);
			$field=$this->input->post('datatable[sort][field]');

			if($field=='')
				$field=$this->input->post('datatable[pagination][field]');

			$sort=$this->input->post('datatable[sort][sort]');

			if($sort=='')
				$sort=$this->input->post('datatable[pagination][sort]');

			//mulai fetching data
			$data = array();
			$no = 0;
			$list=$this->Md_laporan_presensi->getDatatables();
			foreach ($list as $row) {
				$no++;
				$arr = array();
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['nomor'] = $row->nis;
					$arr['nama'] = $row->nama;
					$arr['jam'] = $row->jam;
					$arr['tanggal'] = $row->tanggal;

				$data[] = $arr;
			}

			$meta=array();
			$meta['page']=$page;
			$meta['pages']=$pages;
			$meta['perpage']=$perpage;
			$meta['total']=$total;
			$meta['sort']=$sort;
			$meta['field']=$field;
			$output = array(
							"meta" => $meta,
							"data" => $data
					);
			//output to json format
			echo json_encode($output);
		}else{
			$page_data['page_access'] = "admin";
			$page_data['page_name'] = 'laporan_presensi';
			$page_data['page_now'] = 'dashboard';
			$this->load->view('index', $page_data);
		}
	}
	function laporan_peminjaman($param1='',$param2='')
	{
		if ($this->session->userdata('login_type') != 'admin')
				$this->logout();

		$date = new DateTime();
		$id=$this->session->userdata('idsys');
		$page_data['src']='';
		$page_data['tanggalawal']='';
		$page_data['tanggalakhir']='';
		$page_data['jenis'] = 'mahasiswa';
		if($this->input->post('pilih')=='dosen')
		$page_data['jenis'] = 'dosen';
		if($this->input->post('pilih')=='anggota+luar')
		$page_data['jenis'] = 'anggota+luar';
		$page_data['page_title'] = 'Daftar Peminjaman';

		if($this->input->post('src'))$page_data['src'] = $this->input->post('src');
		if($this->input->post('tanggalawal'))$page_data['tanggalawal'] = $this->input->post('tanggalawal');
		if($this->input->post('tanggalakhir'))$page_data['tanggalakhir'] = $this->input->post('tanggalakhir');

		if($param1=='export'){
			$jenis='anggota+luar';
			$tanggalawal='';
			$tanggalakhir='';
			$search='';

			if($param2=='excel'){
				$jenis=$this->input->post('epilih');
				$tanggalawal=$this->input->post('etanggalawal');
				$tanggalakhir=$this->input->post('etanggalakhir');
				$search=$this->input->post('esearch');
			}elseif($param2=='web'){
				$jenis=$this->input->post('wpilih');
				$tanggalawal=$this->input->post('wtanggalawal');
				$tanggalakhir=$this->input->post('wtanggalakhir');
				$search=$this->input->post('wsearch');
			}
			$page_data['laporan'] = 'laporan_peminjaman';
			$page_data['export'] =$param2;
			$page_data['page_title'] = 'Daftar Peminjaman';
			$page_data['title'] = 'Daftar '.$jenis;
			$page_data['data']=$this->Md_laporan_peminjaman->getLaporan($jenis,$tanggalawal,$tanggalakhir,$search);
			$this->load->view('admin/excel', $page_data);
		}else if($param1 =='fetch'){
			$total=$this->Md_laporan_peminjaman->countFiltered();
				$page=intval($this->input->post('datatable[pagination][page]'));
			if($page<1)
				$page=1;

			$perpage=intval($this->input->post('datatable[pagination][perpage]'));
			$pages= intval($total/$perpage);
			$field=$this->input->post('datatable[sort][field]');

			if($field=='')
				$field=$this->input->post('datatable[pagination][field]');

			$sort=$this->input->post('datatable[sort][sort]');

			if($sort=='')
				$sort=$this->input->post('datatable[pagination][sort]');

			//mulai fetching data
			$data = array();
			$no = 0;
			$list=$this->Md_laporan_peminjaman->getDatatables();
			foreach ($list as $row) {
				$no++;
				$arr = array();
					if($this->input->post('datatable[query][jenis]')=='dosen'){
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['nomor'] = $row->no_anggota;
					$arr['nama'] = $row->nama;
					$arr['prodi'] = '';
					$arr['noinv'] = $row->no_inv;
					$arr['judul'] = $row->judul;
					$arr['tanggal'] = $row->tgl_pinjam;
					$arr['batas'] = $row->batas;

				}else if($this->input->post('datatable[query][jenis]')=='anggota+luar'){
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['nomor'] = $row->no_anggota;
					$arr['nama'] = $row->nama;
					$arr['prodi'] = '';
					$arr['noinv'] = $row->no_inv;
					$arr['judul'] = $row->judul;
					$arr['tanggal'] = $row->tgl_pinjam;
					$arr['batas'] = $row->batas;
				}else{
					$arr['number'] = ($perpage*($page-1))+$no;
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

			$meta=array();
			$meta['page']=$page;
			$meta['pages']=$pages;
			$meta['perpage']=$perpage;
			$meta['total']=$total;
			$meta['sort']=$sort;
			$meta['field']=$field;
			$output = array(
							"meta" => $meta,
							"data" => $data
					);
			//output to json format
			echo json_encode($output);
		}else{
			$page_data['page_access'] = "admin";
			$page_data['page_name'] = 'laporan_peminjaman';
			$page_data['page_now'] = 'dashboard';
			$this->load->view('index', $page_data);
		}
	}
	function laporan_pengembalian($param1='',$param2='')
	{
		if ($this->session->userdata('login_type') != 'admin')
				$this->logout();

		$date = new DateTime();
		$id=$this->session->userdata('idsys');
		$page_data['src']='';
		$page_data['tanggalawal']='';
		$page_data['tanggalakhir']='';
		$page_data['jenis'] = 'mahasiswa';
		if($this->input->post('pilih')=='dosen')
		$page_data['jenis'] = 'dosen';
		if($this->input->post('pilih')=='anggota+luar')
		$page_data['jenis'] = 'anggota+luar';
		$page_data['page_title'] = 'Daftar Pengembalian';

		if($this->input->post('src'))$page_data['src'] = $this->input->post('src');
		if($this->input->post('tanggalawal'))$page_data['tanggalawal'] = $this->input->post('tanggalawal');
		if($this->input->post('tanggalakhir'))$page_data['tanggalakhir'] = $this->input->post('tanggalakhir');

		if($param1=='export'){
			$jenis='anggota+luar';
			$tanggalawal='';
			$tanggalakhir='';
			$search='';

			if($param2=='excel'){
				$jenis=$this->input->post('epilih');
				$tanggalawal=$this->input->post('etanggalawal');
				$tanggalakhir=$this->input->post('etanggalakhir');
				$search=$this->input->post('esearch');
			}elseif($param2=='web'){
				$jenis=$this->input->post('wpilih');
				$tanggalawal=$this->input->post('wtanggalawal');
				$tanggalakhir=$this->input->post('wtanggalakhir');
				$search=$this->input->post('wsearch');
			}
			$page_data['laporan'] = 'laporan_pengembalian';
			$page_data['export'] =$param2;
			$page_data['page_title'] = 'Daftar Pengembalian';
			$page_data['title'] = 'Daftar '.$jenis;
			$page_data['data']=$this->Md_laporan_pengembalian->getLaporan($jenis,$tanggalawal,$tanggalakhir,$search);
			$this->load->view('admin/excel', $page_data);
		}else if($param1 =='fetch'){
			$total=$this->Md_laporan_pengembalian->countFiltered();
				$page=intval($this->input->post('datatable[pagination][page]'));
			if($page<1)
				$page=1;

			$perpage=intval($this->input->post('datatable[pagination][perpage]'));
			$pages= intval($total/$perpage);
			$field=$this->input->post('datatable[sort][field]');

			if($field=='')
				$field=$this->input->post('datatable[pagination][field]');

			$sort=$this->input->post('datatable[sort][sort]');

			if($sort=='')
				$sort=$this->input->post('datatable[pagination][sort]');

			//mulai fetching data
			$data = array();
			$no = 0;
			$list=$this->Md_laporan_pengembalian->getDatatables();
			foreach ($list as $row) {
				$no++;
				$arr = array();
					if($this->input->post('datatable[query][jenis]')=='dosen'){
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['nomor'] = $row->no_anggota;
					$arr['nama'] = $row->nama;
					$arr['prodi'] = '';
					$arr['noinv'] = $row->no_inv;
					$arr['judul'] = $row->judul;
					$arr['tanggal'] = $row->tgl_kembali;

				}else if($this->input->post('datatable[query][jenis]')=='anggota+luar'){
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['nomor'] = $row->no_anggota;
					$arr['nama'] = $row->nama;
					$arr['prodi'] = '';
					$arr['noinv'] = $row->no_inv;
					$arr['judul'] = $row->judul;
					$arr['tanggal'] = $row->tgl_kembali;
				}else{
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['nomor'] = $row->no_anggota;
					$arr['nama'] = $row->nama;
					$arr['prodi'] = $row->kelas;
					$arr['noinv'] = $row->no_inv;
					$arr['judul'] = $row->judul;
					$arr['tanggal'] = $row->tgl_kembali;
				}

				$data[] = $arr;
			}

			$meta=array();
			$meta['page']=$page;
			$meta['pages']=$pages;
			$meta['perpage']=$perpage;
			$meta['total']=$total;
			$meta['sort']=$sort;
			$meta['field']=$field;
			$output = array(
							"meta" => $meta,
							"data" => $data
					);
			//output to json format
			echo json_encode($output);
		}else{
			$page_data['page_access'] = "admin";
			$page_data['page_name'] = 'laporan_pengembalian';
			$page_data['page_now'] = 'dashboard';
			$this->load->view('index', $page_data);
		}
	}
	function laporan_belumkembali($param1='',$param2='')
	{
		if ($this->session->userdata('login_type') != 'admin')
				$this->logout();

		$date = new DateTime();
		$id=$this->session->userdata('idsys');
		$page_data['src']='';
		$page_data['tanggalawal']='';
		$page_data['tanggalakhir']='';
		$page_data['jenis'] = 'mahasiswa';
		if($this->input->post('pilih')=='dosen')
		$page_data['jenis'] = 'dosen';
		if($this->input->post('pilih')=='anggota+luar')
		$page_data['jenis'] = 'anggota+luar';
		$page_data['page_title'] = 'Form Peminjaman Belum Kembali';

		if($this->input->post('src'))$page_data['src'] = $this->input->post('src');
		if($this->input->post('tanggalawal'))$page_data['tanggalawal'] = $this->input->post('tanggalawal');
		if($this->input->post('tanggalakhir'))$page_data['tanggalakhir'] = $this->input->post('tanggalakhir');

		if($param1=='export'){
			$jenis='anggota+luar';
			$tanggalawal='';
			$tanggalakhir='';
			$search='';

			if($param2=='excel'){
				$jenis=$this->input->post('epilih');
				$tanggalawal=$this->input->post('etanggalawal');
				$tanggalakhir=$this->input->post('etanggalakhir');
				$search=$this->input->post('esearch');
			}elseif($param2=='web'){
				$jenis=$this->input->post('wpilih');
				$tanggalawal=$this->input->post('wtanggalawal');
				$tanggalakhir=$this->input->post('wtanggalakhir');
				$search=$this->input->post('wsearch');
			}
			$page_data['laporan'] = 'laporan_belumkembali';
			$page_data['export'] =$param2;
			$page_data['page_title'] = 'Form Peminjaman Belum Kembali';
			$page_data['title'] = 'Daftar '.$jenis;
			$page_data['data']=$this->Md_laporan_belumkembali->getLaporan($jenis,$tanggalawal,$tanggalakhir,$search);
			$this->load->view('admin/excel', $page_data);
		}else if($param1 =='fetch'){
			$total=$this->Md_laporan_belumkembali->countFiltered();
				$page=intval($this->input->post('datatable[pagination][page]'));
			if($page<1)
				$page=1;

			$perpage=intval($this->input->post('datatable[pagination][perpage]'));
			$pages= intval($total/$perpage);
			$field=$this->input->post('datatable[sort][field]');

			if($field=='')
				$field=$this->input->post('datatable[pagination][field]');

			$sort=$this->input->post('datatable[sort][sort]');

			if($sort=='')
				$sort=$this->input->post('datatable[pagination][sort]');

			//mulai fetching data
			$data = array();
			$no = 0;
			$list=$this->Md_laporan_belumkembali->getDatatables();
			foreach ($list as $row) {
				$no++;
				$arr = array();
					if($this->input->post('datatable[query][jenis]')=='dosen'){
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['nomor'] = $row->no_anggota;
					$arr['nama'] = $row->nama;
					$arr['prodi'] = '';
					$arr['noinv'] = $row->no_inv;
					$arr['judul'] = $row->judul;
					$arr['tanggal'] = $row->tgl_pinjam;
					$arr['batas'] = $row->batas;

				}else if($this->input->post('datatable[query][jenis]')=='anggota+luar'){
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['nomor'] = $row->no_anggota;
					$arr['nama'] = $row->nama;
					$arr['prodi'] = '';
					$arr['noinv'] = $row->no_inv;
					$arr['judul'] = $row->judul;
					$arr['tanggal'] = $row->tgl_pinjam;
					$arr['batas'] = $row->batas;
				}else{
					$arr['number'] = ($perpage*($page-1))+$no;
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

			$meta=array();
			$meta['page']=$page;
			$meta['pages']=$pages;
			$meta['perpage']=$perpage;
			$meta['total']=$total;
			$meta['sort']=$sort;
			$meta['field']=$field;
			$output = array(
							"meta" => $meta,
							"data" => $data
					);
			//output to json format
			echo json_encode($output);
		}else{
			$page_data['page_access'] = "admin";
			$page_data['page_name'] = 'laporan_belumkembali';
			$page_data['page_now'] = 'dashboard';
			$this->load->view('index', $page_data);
		}
	}

/** Artikel **/

		public function manage_artikel($param1="", $param2="", $param3=""){
			if ($this->session->userdata('login_type') != 'admin')

				$this->logout();

		$page_data['artikel'] = '';
		$page_data['media'] = $this->Md_media->getAllMedia();
		$page_data['page_access'] = "admin";
		$page_data['page_now'] = 'artikel';
		$page_data['page_title'] = 'Data Artikel';
		$page_data['page_name'] = 'manage_artikel';

		$config['upload_path']		= './uploads/';
		$config['allowed_types']	= 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
		$config['max_size']			= '2048';
		$config['file_name']		= 'artikel_'.date("Ymdhi");

		$this->upload->initialize($config);
		$this->load->library('upload', $config);

		if ($param1 == 'add') {
			if ($param2=='do_add') {
				if ($this->input->post('add_media_artikel')=="") {
					if(!$this->upload->do_upload('file')){
						//artikel tidak ada gambar
						$data['media_id']=0;
					}else if ($id = $this->upload_media('artikel')) {
						$data['media_id']	= $id['id'];
					}else{
						$error = $this->upload->display_errors();
						$this->session->set_flashdata('alert', 'alert-danger');
						$this->session->set_flashdata('flash_message', '<strong>Gagal</strong>'.$error);
						redirect(site_url('admin/manage_artikel/add'),'refresh');
					}
				}else{
					$data['media_id']			= $this->input->post('add_media_artikel');
				}

				$data['jenisartikel_id']	= 1;
				$data['judul']				= $this->input->post('add_judul_artikel');
				$data['isi']				= $this->input->post('add_isi_artikel');
				$data['post_status']		= $this->input->post('add_statpost_artikel');
				$data['tgl_post']			= $this->input->post('add_tglpost_artikel');
				$data['tgl_perubahan']		= date("Y-m-d");
				$data['meta_desc']			= '';
				$data['meta_keyword']		= '';
				$data['author']				= $this->session->userdata('username');
				$data['status']				= 1;

				$this->Md_artikel->addArtikel($data);
				$this->session->set_flashdata('alert', 'alert-success');
				$this->session->set_flashdata('flash_message', '<strong>Berhasil</strong>Kamu berhasil menambah Artikel.');
				redirect(site_url('admin/manage_artikel'),'refresh');
			}else{
				$page_data['artikel'] = 'add';
			}

			$this->load->view('index', $page_data);
		}else if ($param1 == 'delete') {
			$id 			= $param2;
			$data['status']	= 2;

			$this->Md_artikel->updateArtikel($id, $data);

            echo json_encode('success');
            die;
		}else if ($param1 == 'edit') {
			if ($param2 != '') {
				if ($param2=='do_edit') {
					$error='';
					if($this->upload->do_upload('file')){
						if ($id = $this->upload_media('artikel')) {
							$data['media_id']	= $id['id'];
						}else{
							$error = $this->upload->display_errors();
							$this->session->set_flashdata('alert', 'alert-danger');
							$this->session->set_flashdata('flash_message', '<strong>Gagal</strong>'.$error);
							redirect(site_url('admin/manage_artikel/edit'),'refresh');
						}
					}else{
						$data['media_id']			= $this->input->post('edit_media_artikel');
					}
					//$data['media_id']			= $this->input->post('edit_media_artikel');
					$data['jenisartikel_id']	= 1;
					$data['judul']				= $this->input->post('edit_judul_artikel');
					$data['isi']				= $this->input->post('edit_isi_artikel');
					$data['post_status']		= $this->input->post('edit_statpost_artikel');
					$data['tgl_post']			= $this->input->post('edit_tglpost_artikel');
					$data['tgl_perubahan']		= date("Y-m-d");
					$data['meta_desc']			= '';
					$data['meta_keyword']		= '';
					//$data['author']				= $this->session->userdata('username');
					$data['status']				= $this->input->post('edit_status_artikel');
					if ($this->Md_artikel->updateArtikel($param3, $data) && $error=='') {
						$this->session->set_flashdata('alert', 'alert-success');
						$this->session->set_flashdata('flash_message', '<strong>Berhasil</strong> Kamu berhasil update Artikel.');

					}else{
						$this->session->set_flashdata('alert', 'alert-danger');
						$this->session->set_flashdata('flash_message', '<strong>Gagal</strong> Kamu gagal update Artikel.'.$error);
					}
					redirect(base_url('admin/manage_artikel'),'refresh');
				}else{
					$page_data['artikel'] = 'edit';
					$page_data['edit'] = $this->Md_artikel->getArtikelById($param2);
				}
				$this->load->view('index', $page_data);
			}else{
				redirect(base_url('admin/manage_artikel'),'refresh');
			}
		}else if ($param1 == 'list') {
				$list = $this->Md_artikel->getAllArtikel();
				foreach($list as $row){
					$arr = array();
					$arr['id'] = $row->artikel_id;
					$arr['judul'] = $row->judul;
					$arr['tgl_post'] = date('d M Y',strtotime($row->tgl_post));
					if($row->post_status == 1){
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

				echo json_encode($output);exit();

		}else{
			$this->load->view('index', $page_data);
		}

	}
	function manage_media($param1="", $param2=""){
		 if ($this->session->userdata('login_type') != 'admin')

            $this->logout();

			if ($param1=='add') {

			if ($upload = $this->upload_file()){
				$jenis 	= $upload['upload']['file_type'];
				$path 	= $upload['upload']['full_path'];
				$file 	= $upload['upload']['file_name'];
				$height = $upload['upload']['image_height'];
				$width 	= $upload['upload']['image_width'];

				if ($jenis=='image/jpeg' || $jenis=='image/png' || $jenis=='image/gif') {
					$tipe_data = 'gambar';
				}else{$tipe_data= 'file';}

				$data['jenismedia_id']	= 1;//$this->input->post('add_jenisid_media');
				$data['judul']			= $upload['upload']['file_name'];
				$data['deskripsi']		= '';
				$data['alt_teks']		= $upload['upload']['file_name'];
				$data['tipe']			= $tipe_data;
				$data['tgl_upload']		= date("Y-m-d");
				$data['tgl_perubahan']	= date("Y-m-d");
				$data['author']			= 'author';
				$data['status']			= 1;

				$media = $this->Md_media->addMedia($data);

				if ($media and $tipe_data=="gambar") {
					$id = $media['media_id'];
					$this->rs_big($path, $file, $id, 1920, 1280);
					$this->rs_medium($path, $file, $id, 850, 430);
					$this->rs_small($path, $file, $id, 370, 247);
				}
			}else{
				return;
			}
		}

		if ($param1=='delete') {
			$id 			= $param2;
			$media=$this->Md_media->getMediaById($id);
			if(count($media)>0){
				$this->Md_media->hapusMedia($id);
				@unlink(base_url().'uploads/'.$media[0]['judul']);
				@unlink(base_url().'uploads/big/big_'.$media[0]['judul']);
				@unlink(base_url().'uploads/medium/medium_'.$media[0]['judul']);
				@unlink(base_url().'uploads/small/small_'.$media[0]['judul']);

            }else{
			$this->session->set_flashdata('alert', 'alert-danger');
				$this->session->set_flashdata('flash_message', 'Gagal Menghapus Media');
				redirect(base_url() . 'admin/manage_media', 'refresh');
			}

            $this->session->set_flashdata('alert', 'alert-success');
				$this->session->set_flashdata('flash_message', 'Sukses Menghapus Media');
				redirect(base_url() . 'admin/manage_media', 'refresh');
		}

		if ($param1=='edit') {
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

	public function details($param1=''){
		if ($this->session->userdata('login_type') != 'admin')

            $this->logout();

		$page_data['media'] = $this->Md_media->getMediaByID($param1);
		$page_data['detail'] = $this->Md_mediadetail->getMdetailByMediaId($param1);

		$page_data['page_title'] = 'Details';
		$page_data['page_name'] = 'manage_media';
		$this->load->view('admin/detail_media', $page_data);
	}

	private function rs_big($path, $file, $id, $width, $height){
		 if ($this->session->userdata('login_type') != 'admin')

            $this->logout();
			$rs_width  = $width;
			$rs_height = $height;

		$config['image_library']  = 'gd2';
		$config['source_image']	  = $path;
		$config['create_thumb']	  = FALSE;
		$config['maintain_ratio'] = TRUE;
		$config['width']		  = $rs_width;
		$config['height']		  = $rs_height;
		$config['new_image']	  = './uploads/big/big_'.$file;

		$this->image_lib->initialize($config);
        $this->load->library('image_lib', $config);
		if (!$this->image_lib->resize()) {
			showhouse_log(MY_Log::KERROR, $ci->image_lib->display_errors());
		}

		$info = getimagesize('./uploads/big/big_'.$file);
		$size = filesize('./uploads/big/big_'.$file)/1024;
		$detail['media_id']		= $id;
		$detail['jenis_ukuran'] = "big";
		$detail['media_link']	= base_url().'uploads/big/big_'.$file;
		$detail['width']		= $info[0];
		$detail['height']		= $info[1];
		$detail['size']			= $size;
		$detail['status']		= 1;

		$this->Md_mediadetail->addMdetail($detail);
	}

	private function rs_medium($path, $file, $id, $width, $height){
		 if ($this->session->userdata('login_type') != 'admin')

            $this->logout();
			$rs_width  = $width;
			$rs_height = $height;

		$config['image_library']	= 'gd2';
		$config['source_image']		= $path;
		$config['create_thumb']		= FALSE;
		$config['maintain_ratio'] 	= TRUE;
		$config['width']			= $rs_width;
		$config['height']			= $rs_height;
		$config['new_image']		= './uploads/medium/medium_'.$file;

		$this->image_lib->initialize($config);
        $this->load->library('image_lib', $config);
		if (!$this->image_lib->resize()) {
			showhouse_log(MY_Log::KERROR, $ci->image_lib->display_errors());
		}

		$info = getimagesize('./uploads/medium/medium_'.$file);
		$size = filesize('./uploads/medium/medium_'.$file)/1024;
		$detail['media_id']		= $id;
		$detail['jenis_ukuran'] = "medium";
		$detail['media_link']	= base_url().'uploads/medium/medium_'.$file;
		$detail['width']		= $info[0];
		$detail['height']		= $info[1];
		$detail['size']			= $size;
		$detail['status']		= 1;

		$this->Md_mediadetail->addMdetail($detail);

	}

	private function rs_small($path, $file, $id, $width, $height){
		 if ($this->session->userdata('login_type') != 'admin')

            $this->logout();
			$rs_width  = $width;
			$rs_height = $height;

		$config['image_library']	= 'gd2';
		$config['source_image']		= $path;
		$config['create_thumb']		= FALSE;
		$config['maintain_ratio'] 	= TRUE;
		$config['width']			= $rs_width;
		$config['height']			= $rs_height;
		$config['new_image']		= './uploads/small/small_'.$file;

		$this->image_lib->initialize($config);
        $this->load->library('image_lib', $config);
		if (!$this->image_lib->resize()) {
			showhouse_log(MY_Log::KERROR, $ci->image_lib->display_errors());
		}

		$info = getimagesize('./uploads/small/small_'.$file);
		$size = filesize('./uploads/small/small_'.$file)/1024;
		$detail['media_id']		= $id;
		$detail['jenis_ukuran'] = "small";
		$detail['media_link']	= base_url().'uploads/small/small_'.$file;
		$detail['width']		= $info[0];
		$detail['height']		= $info[1];
		$detail['size']			= $size;
		$detail['status']		= 1;

		$this->Md_mediadetail->addMdetail($detail);
	}

	private function upload_file(){
		 if ($this->session->userdata('login_type') != 'admin')

            $this->logout();
		$config['upload_path']		= './uploads/';
		$config['allowed_types']	= 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG|doc|docx|xls|xlsx|pdf';
		$config['max_size']			= '2048';
		// $config['max_width']		= '5000';
		// $config['max_height']		= '5000';
		$config['file_name']		= 'media_'.date("Ymdhi");

		$this->upload->initialize($config);
		$this->load->library('upload', $config);

		if ( !$this->upload->do_upload('file')){
			$error = $this->upload->display_errors();
			echo $error;
			header("Error:", true, 500);

		}else{
			$upload_data = $this->upload->data();
			return $data = array('upload' => $upload_data, TRUE );
		}
	}

	public function upload_media($tipe){
		 if ($this->session->userdata('login_type') != 'admin')

            $this->logout();

		if ( !$this->upload->do_upload('file')){
			$error = $this->upload->display_errors();
			$this->session->set_flashdata('alert', 'alert-danger');
			$this->session->set_flashdata('flash_message', '<strong>Gagal</strong>'.$error);
		}
		else{
			$upload = $this->upload->data();
			$jenis 	= $upload['file_type'];
			$path 	= $upload['full_path'];
			$file 	= $upload['file_name'];
			$height = $upload['image_height'];
			$width 	= $upload['image_width'];

			$data['jenismedia_id']	= 1;//$this->input->post('add_jenisid_media');
			$data['judul']			= $upload['file_name'];
			$data['deskripsi']		= "Gambar ".$tipe;
			$data['alt_teks']		= $upload['file_name'];
			$data['tipe']			= 'gambar';
			$data['tgl_upload']		= date("Y-m-d");
			$data['tgl_perubahan']	= date("Y-m-d");
			$data['author']			= $this->session->userdata('username');
			$data['status']			= 1;

			$id = $this->Md_media->addMedia($data);
			if ($id) {

				if($tipe=='artikel'){
				$this->rs_big($path, $file, $id['media_id'], 850, 430);
				$this->rs_medium($path, $file, $id['media_id'], 370, 247);
				$this->rs_small($path, $file, $id['media_id'], 150, 150);
				}else{
				$this->rs_big($path, $file, $id['media_id'], 1920, 1280);
				$this->rs_medium($path, $file, $id['media_id'], 850, 430);
				$this->rs_small($path, $file, $id['media_id'], 370, 247);
				}
			}
			return $arrayName = array('id' => $id['media_id'] , TRUE );

		}
	}

	public function manage_slide($param1="", $param2="", $param3=""){
	 if ($this->session->userdata('login_type') != 'admin')

            $this->logout();
		$page_data['slide'] = '';
		$page_data['page_access'] = "admin";
		$page_data['page_now'] = 'slide';
		$page_data['page_title'] = 'Data Slide';
		$page_data['page_name'] = 'manage_slide';
		$page_data['media'] = $this->Md_media->getAllMedia();

		$config['upload_path']		= './uploads/';
		$config['allowed_types']	= 'gif|jpg|png|jpeg|GIF|JPG|PNG|JPEG';
		$config['max_size']			= '2048';
		$config['file_name']		= 'slide_'.date("Ymdhi");

		$this->upload->initialize($config);
		$this->load->library('upload', $config);


		if ($param1 == 'add') {
			if ($param2=='do_add') {
				if ($this->input->post('add_media_slide')=="") {
					if ($id = $this->upload_media('slide')) {
						$data['media_id']	= $id['id'];
					}else{
						$error = $this->upload->display_errors();
					$this->session->set_flashdata('alert', 'alert-danger');
					$this->session->set_flashdata('flash_message', '<strong>Gagal</strong>'.$error);
					redirect(site_url('admin/manage_slide/add'),'refresh');
					}
				}else{
					$data['media_id']			= $this->input->post('add_media_slide');
				}

				$data['judul']				= $this->input->post('add_judul_slide');
				$data['keterangan']			= $this->input->post('add_keterangan_slide');
				$data['urutan']				= $this->input->post('add_urutan_slide');
				$data['tgl_post']			= date("Y-m-d");
				$data['author']				= $this->session->userdata('username');
				$data['status']				= 1;
				$this->Md_slide->addSlide($data);
				$this->session->set_flashdata('alert', 'alert-success');
				$this->session->set_flashdata('flash_message', '<strong>Berhasil</strong>Kamu berhasil menambah Slide');
				redirect(site_url('admin/manage_slide'),'refresh');
			}else{
				$page_data['slide'] = 'add';
			}

			$this->load->view('index', $page_data);
		}else if ($param1 == 'delete') {
			$id 			= $param2;
			$data['status']	= 2;

			$this->Md_slide->updateSlide($id, $data);

            echo json_encode('success');
            die;
		}else if ($param1 == 'edit') {
			if ($param2 != '') {
				if ($param2=='do_edit') {
					$error='';
					if($this->upload->do_upload('file')){
						if ($id = $this->upload_media('slide')) {
								$data['media_id']	= $id['id'];
						}else{
							$error = $this->upload->display_errors();
							$this->session->set_flashdata('alert', 'alert-danger');
							$this->session->set_flashdata('flash_message', '<strong>Gagal</strong>'.$error);
							redirect(site_url('admin/manage_slide/edit'),'refresh');
						}
					}else{
							$data['media_id']			= $this->input->post('edit_media_slide');
					}
						$data['judul']				= $this->input->post('edit_judul_slide');
						$data['keterangan']			= $this->input->post('edit_keterangan_slide');
						$data['urutan']				= $this->input->post('edit_urutan_slide');
						$data['tgl_post']			= date("Y-m-d");
						$data['author']				= $this->session->userdata('username');
						$data['status']				= 1;

					if ($this->Md_slide->updateSlide($param3, $data) && $error=='') {
						$this->session->set_flashdata('alert', 'alert-success');
						$this->session->set_flashdata('flash_message', '<strong>Berhasil</strong>Berhasil Update Slide');
					}else{
						$this->session->set_flashdata('alert', 'alert-danger');
						$this->session->set_flashdata('flash_message', '<strong>Gagal</strong>Gagal Update Slide'.$error);
					}
					redirect(base_url('admin/manage_slide'),'refresh');
				}else{
					$page_data['slide'] = 'edit';
					$page_data['edit'] = $this->Md_slide->getSlideById($param2);
				}
				$this->load->view('index', $page_data);
			}else{
				redirect(base_url('admin/manage_slide'),'refresh');
			}
		}else if ($param1 == 'list') {
				$list = $this->Md_slide->getAllSlide();
				foreach($list as $row){
					$arr = array();
					$arr['id'] = $row->slide_id;
					$arr['judul'] = $row->judul;
					$arr['keterangan'] = $row->keterangan;
					$arr['status'] = $row->status;
					$arr['author'] = $row->author;
					$arr['tgl_post'] = date('d M Y',strtotime($row->tgl_post));
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

				echo json_encode($output);exit();

		}else{
			$this->load->view('index', $page_data);
		}

	}
	function manage_halaman($param1 = "", $param2 = "", $param3 = "")
		{
			if ($this->session->userdata('login_type') != 'admin') $this->logout();
			$page_data['hal'] = '';
			$page_data['halaman'] = $this->Md_siperpus_manage_halaman->getAllHalaman();
			$page_data['page_access'] = "admin";
			$page_data['page_now'] = 'halaman';
				$page_data['edit_halaman'] = '';
				if ($param1 == 'edit')
				{
					if ($param2 != '')
					{
						if ($param2 == 'do_edit')
						{
							$data['isi_halaman'] = $this->input->post('edit_isi_halaman');
							$data['tgl_post'] = date('Y-m-d');
							$data['author'] =$this->session->userdata('username');
							$data['meta_desc'] = $this->input->post('edit_mdesc_halaman');
							$data['meta_keyword'] = $this->input->post('edit_mkey_halaman');
							$this->Md_siperpus_manage_halaman->updateHalaman($param3, $data);
							$this->session->set_flashdata('alert', 'alert-success');
							$this->session->set_flashdata('flash_message', '<strong>Berhasil</strong>Update Halaman');
							redirect(base_url('admin/manage_halaman') , 'refresh');
						}
						else
						{
							$page_data['edit_halaman'] = 'edit';
							$page_data['edit'] = $this->Md_siperpus_manage_halaman->getHalamanById($param2);
						}
					}
					else
					{
						redirect(base_url('manage_halaman') , 'refresh');
					}
				}

				if ($param1 == 'list')
				{
					$list = $this->Md_siperpus_manage_halaman->getAllHalaman();
					foreach($list as $row)
					{
						$arr = array();
						$arr['id'] = $row->halaman_id;
						$arr['judul_halaman'] = $row->judul_halaman;
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
				}
				$page_data['page_title'] = 'Halaman';
				$page_data['page_name'] = 'manage_halaman';
				$this->load->view('index', $page_data);
		}
function statistik($param1="", $param2="", $param3=""){
	 if ($this->session->userdata('login_type') != 'admin')

            $this->logout();
		$page_data['slide'] = '';
		$page_data['page_access'] = "admin";
		$page_data['page_now'] = 'slide';
		$page_data['page_title'] = 'Statistik';
		$page_data['page_name'] = 'statistik';
		$page_data['page_action'] = '';
		if($param1=='anggota'){
		$page_data['page_action'] = $param1;
			$total=0;
			$xAxis=array();
			$yAxis=array();
			$mspst=$this->Md_vwprodi->getProdiAll();
			foreach($mspst as $m){
				$siswa=$this->Md_vwsiswa->getSiswaByKelas($m->nmmspst);
				array_push($xAxis,array('number',$m->nmmspst));
				array_push($yAxis,count($siswa));
				$total+=count($siswa);

			}
			array_push($xAxis,array('number','Dosen'));
			array_push($xAxis,array('number','Anggota Luar'));

			$dosen=$this->Md_vwdosen->getDosenAll();
			array_push($yAxis,count($dosen));
			$total+=count($dosen);

			$anggotaluar=$this->Md_siperpus_anggota_luar->getAnggotaAll();
			array_push($yAxis,count($anggotaluar));
			$total+=count($anggotaluar);
			$page_data['xAxis'] = $xAxis;
			$page_data['yAxis'] = $yAxis;
			$page_data['total'] = $total;

		}else if($param1=='denda'){
			$page_data['page_action'] = $param1;
			$total=0;
			$xAxis=array();
			$yAxis=array();
			$mspst=$this->Md_vwprodi->getProdiAll();
			foreach($mspst as $m){
				$denda=$this->Md_siperpus_transaksi->getDendaByKelas($m->nmmspst);
				array_push($xAxis,array('number',$m->nmmspst));
				if($denda[0]->denda>0){
				array_push($yAxis,$denda[0]->denda);
				}else{
				array_push($yAxis,0);
			}
				$total+=$denda[0]->denda;

			}
			$page_data['xAxis'] = $xAxis;
			$page_data['yAxis'] = $yAxis;
			$page_data['total'] = $total;

		}else if($param1=='bukureferensi'){
		$page_data['page_action'] = $param1;
			$total=0;
			$xAxis=array();
			$yAxis=array();
			$mspst=$this->Md_vwprodi->getProdiAll();
			$klas=$this->Md_siperpus_klasifikasi->getKlasifikasiAll();

			$page_data['prodi'] = $mspst;
			$page_data['klas'] = $klas;

		}else if($param1=='presensi'){
		$page_data['page_action'] = $param1;
			$total=0;
			$xAxis=array();
			$yAxis=array();
			$mspst=$this->Md_vwprodi->getProdiAll();
			foreach($mspst as $m){
				$presensi=$this->Md_siperpus_presensi->getPresensiByKelas($m->nmmspst);
				array_push($xAxis,array('number',$m->nmmspst));
				if($presensi[0]->total>0){
				array_push($yAxis,$presensi[0]->total);
				}else{
				array_push($yAxis,0);
			}
				$total+=$presensi[0]->total;

			}
			$page_data['xAxis'] = $xAxis;
			$page_data['yAxis'] = $yAxis;
			$page_data['total'] = $total;

		}else if($param1=='peminjaman'){
		$page_data['page_action'] = $param1;
			$total=0;

			$xAxis=array();
			$yAxis=array();
			$mspst=$this->Md_vwprodi->getProdiAll();
			$klas=$this->Md_siperpus_klasifikasi->getKlasifikasiAll();
			foreach($mspst as $m){
				$peminjaman=$this->Md_siperpus_transaksi->getPeminjamanByKelas($m->nmmspst);
				array_push($xAxis,array('number',$m->nmmspst));
				if($peminjaman[0]->total>0){
				array_push($yAxis,$peminjaman[0]->total);
				}else{
				array_push($yAxis,0);
			}
				$total+=$peminjaman[0]->total;

			}
			$page_data['pxAxis'] = $xAxis;
			$page_data['pyAxis'] = $yAxis;
			$page_data['ptotal'] = $total;
			$page_data['ptitle'] = 'Statistik Peminjaman Berdasarkan Prodi';

			$xAxis=array();
			$yAxis=array();
			$total=0;
			foreach($klas as $k){
				$peminjaman=$this->Md_siperpus_transaksi->getPeminjamanByKlasifikasi($k->id);
				array_push($xAxis,array('number',$k->nama));
				if($peminjaman[0]->total>0){
				array_push($yAxis,$peminjaman[0]->total);
				}else{
				array_push($yAxis,0);
			}
				$total+=$peminjaman[0]->total;

			}
			$page_data['kxAxis'] = $xAxis;
			$page_data['kyAxis'] = $yAxis;
			$page_data['ktotal'] = $total;
			$page_data['ktitle'] = 'Statistik Peminjaman Berdasarkan Klasifikasi Buku';

			$xAxis=array();
			$yAxis=array();
			$total=0;
			$mhs=$this->Md_siperpus_transaksi->getAnggotaPinjamTerbanyak();
			foreach($mhs as $m){
				array_push($xAxis,array('number',$m->nama));
				if($m->total>0){
				array_push($yAxis,$m->total);
				}else{
				array_push($yAxis,0);
			}
				$total+=$m->total;

			}
			$page_data['mxAxis'] = $xAxis;
			$page_data['myAxis'] = $yAxis;
			$page_data['mtotal'] = $total;
			$page_data['mtitle'] = 'Statistik 10 mahasiswa Aktif Terbanyak Peminjaman Buku';

			$yearnow=date('Y');
			$yearbefore=$yearnow-1;
			$xAxis=array();
			$yAxis=array();
			$total=0;
			$mhs=$this->Md_siperpus_transaksi->getAnggotaPinjamTerbanyakTahun($yearnow);
			foreach($mhs as $m){
				array_push($xAxis,array('number',$m->nama));
				if($m->total>0){
				array_push($yAxis,$m->total);
				}else{
				array_push($yAxis,0);
			}
				$total+=$m->total;

			}
			$page_data['nxAxis'] = $xAxis;
			$page_data['nyAxis'] = $yAxis;
			$page_data['ntotal'] = $total;
			$page_data['ntitle'] = 'Statistik 10 mahasiswa Aktif Terbanyak Peminjaman Buku Tahun '.$yearnow;

			$xAxis=array();
			$yAxis=array();
			$total=0;
			$mhs=$this->Md_siperpus_transaksi->getAnggotaPinjamTerbanyakTahun($yearbefore);
			foreach($mhs as $m){
				array_push($xAxis,array('number',$m->nama));
				if($m->total>0){
				array_push($yAxis,$m->total);
				}else{
				array_push($yAxis,0);
			}
				$total+=$peminjaman[0]->total;

			}
			$page_data['bxAxis'] = $xAxis;
			$page_data['byAxis'] = $yAxis;
			$page_data['btotal'] = $total;
			$page_data['btitle'] = 'Statistik 10 mahasiswa Aktif Terbanyak Peminjaman Buku Tahun '.$yearbefore;

		}else if($param1=='periodik'){
			$page_data['page_action'] = $param1;
			if(!$this->input->post('tahun'))$year=date('Y');
			else $year=$this->input->post('tahun');
			if(!$this->input->post('bulan'))$month='';
			else $month=$this->input->post('bulan');
			$total=0;
			$page_data['curY'] = $year;
			$page_data['curM'] = $month;

			$xAxis=array();
			$yAxis=array();
			$mspst=$this->Md_vwprodi->getProdiAll();
			$klas=$this->Md_siperpus_klasifikasi->getKlasifikasiAll();
			$total=0;
				foreach($mspst as $m){
					$kunjungan=$this->Md_siperpus_presensi->getKunjunganProdiByTahun($year,$month,$m->nmmspst);
					array_push($xAxis,array('number',$m->nmmspst));
					if($kunjungan>0){
					array_push($yAxis,$kunjungan);
					}else{
					array_push($yAxis,0);
					}
					$total+=$kunjungan;
			}
			$page_data['pxAxis'] = $xAxis;
			$page_data['pyAxis'] = $yAxis;
			$page_data['ptotal'] = $total;
			$page_data['ptitle'] = 'Statistik Presensi Kunjungan Per Prodi pada Tahun '.$year;


			$xAxis=array();
			$yAxis=array();
			$total=0;
				foreach($mspst as $m){
					$pinjam=$this->Md_siperpus_transaksi->getPeminjamanProdiByTahun($year,$month,$m->nmmspst);
					array_push($xAxis,array('number',$m->nmmspst));
					if($pinjam>0){
					array_push($yAxis,$pinjam);
					}else{
					array_push($yAxis,0);
					}
					$total+=$pinjam;
				}
			$page_data['kxAxis'] = $xAxis;
			$page_data['kyAxis'] = $yAxis;
			$page_data['ktotal'] = $total;
			$page_data['ktitle'] = 'Statistik Peminjaman Per Prodi pada Tahun '.$year;


			$xAxis=array();
			$yAxis=array();
			$total=0;
			foreach($klas as $k){
					$pinjam=$this->Md_siperpus_transaksi->getPeminjamanKlasByTahun($year,$month,$k->id);
					array_push($xAxis,array('number',$k->nama));
					if($pinjam>0){
					array_push($yAxis,$pinjam);
					}else{
					array_push($yAxis,0);
					}
					$total+=$pinjam;
			}
			$page_data['mxAxis'] = $xAxis;
			$page_data['myAxis'] = $yAxis;
			$page_data['mtotal'] = $total;
			$page_data['mtitle'] = 'Statistik Peminjaman Per Klasifikasi Buku pada Tahun '.$year;

			$xAxis=array();
			$yAxis=array();
			$total=0;
			foreach($klas as $k){
					$pinjam=$this->Md_siperpus_inventaris->getInventarisByTahunKlas($year,$month,$k->id);
					array_push($xAxis,array('number',$k->nama));
					if($pinjam>0){
					array_push($yAxis,$pinjam);
					}else{
					array_push($yAxis,0);
					}
					$total+=$pinjam;
			}
			$page_data['nxAxis'] = $xAxis;
			$page_data['nyAxis'] = $yAxis;
			$page_data['ntotal'] = $total;
			$page_data['ntitle'] = 'Statistik Jumlah Inventaris pada Tahun '.$year;
		}
		else if($param1=='buku'){
			$page_data['page_action'] = $param1;
			if(!$this->input->post('status'))$status='';
			else $status=$this->input->post('status');
			$page_data['cur'] = $status;
			$nm='';
			if($status=='')$nm='Stok Aktif';
			if($status=='H')$nm='Hilang';
			if($status=='R')$nm='Rusak';
			if($status=='A')$nm='Diarsipkan';
			if($status=='L')$nm='Dilelang';

			$xAxis=array();
			$yAxis=array();
			$mspst=$this->Md_vwprodi->getProdiAll();
			$asal=$this->Md_siperpus_asal_buku->getAsalBukuAll();
			$bahasa=$this->Md_siperpus_bahasa->getBahasaAll();
			$kat=$this->Md_siperpus_kategori_buku->getKategoriBukuAll();
			$klas=$this->Md_siperpus_klasifikasi->getKlasifikasiAll();
			$total=0;
				foreach($klas as $k){
					$invs=$this->Md_siperpus_inventaris->getInventarisByStatusKlas($status,$k->id);
					array_push($xAxis,array('number',$k->nama));
					if($invs>0){
					array_push($yAxis,$invs);
					}else{
					array_push($yAxis,0);
					}
					$total+=$invs;
				}
			$page_data['pxAxis'] = $xAxis;
			$page_data['pyAxis'] = $yAxis;
			$page_data['ptotal'] = $total;
			$page_data['ptitle'] = 'Statistik Inventarisasi Buku Berdasarkan Klasifikasi dengan Status '.$nm;


			$xAxis=array();
			$yAxis=array();
			$total=0;
				foreach($kat as $k){
					$invs=$this->Md_siperpus_inventaris->getInventarisByStatusKat($status,$k->idkategori);
					array_push($xAxis,array('number',$k->nmkategori));
					if($invs>0){
					array_push($yAxis,$invs);
					}else{
					array_push($yAxis,0);
					}
					$total+=$invs;
				}
			$page_data['kxAxis'] = $xAxis;
			$page_data['kyAxis'] = $yAxis;
			$page_data['ktotal'] = $total;
			$page_data['ktitle'] = 'Statistik Inventarisasi Buku Berdasarkan Kategori dengan Status '.$nm;


			$xAxis=array();
			$yAxis=array();
			$total=0;
			foreach($asal as $a){
					$invs=$this->Md_siperpus_inventaris->getInventarisByStatusAsal($status,$a->id);
					array_push($xAxis,array('number',$a->nama));
					if($invs>0){
					array_push($yAxis,$invs);
					}else{
					array_push($yAxis,0);
					}
					$total+=$invs;
				}
			$page_data['mxAxis'] = $xAxis;
			$page_data['myAxis'] = $yAxis;
			$page_data['mtotal'] = $total;
			$page_data['mtitle'] = 'Statistik Inventarisasi Buku Berdasarkan Asal Buku dengan Status '.$nm;

			$xAxis=array();
			$yAxis=array();
			$total=0;
			foreach($bahasa as $b){
					$pinjam=$this->Md_siperpus_inventaris->getInventarisByStatusBahasa($status,$b->id);
					array_push($xAxis,array('number',$b->nama));
					if($pinjam>0){
					array_push($yAxis,$pinjam);
					}else{
					array_push($yAxis,0);
					}
					$total+=$pinjam;
			}
			$page_data['nxAxis'] = $xAxis;
			$page_data['nyAxis'] = $yAxis;
			$page_data['ntotal'] = $total;
			$page_data['ntitle'] = 'Statistik Inventarisasi Buku Berdasarkan Bahasa Buku dengan Status '.$nm;

			$xAxis=array();
			$yAxis=array();
			$total=0;
			$bukupinjam=$this->Md_siperpus_transaksi->getStatusBukuPinjamTerbanyak($status,10);
			if(count($bukupinjam)>0){
				foreach($bukupinjam as $b){
						array_push($xAxis,array('number',$b->judul));
						if($b->total>0){
						array_push($yAxis,$b->total);
						}else{
						array_push($yAxis,0);
						}
						$total+=$b->total;
				}
			}else{
				array_push($xAxis,array('number',''));
				array_push($yAxis,0);
			}
			$page_data['qxAxis'] = $xAxis;
			$page_data['qyAxis'] = $yAxis;
			$page_data['qtotal'] = $total;
			$page_data['qtitle'] = 'Statistik Inventarisasi Buku Yang Paling Banyak Dipinjam dengan Status '.$nm;
		}
		else if($param1=='bukubyjudul') {
			$page_data['page_action'] = $param1;
			if(!$this->input->post('status'))$status='';
			else $status=$this->input->post('status');
			$page_data['cur'] = $status;
			$nm='';
			if($status=='')$nm='Stok Aktif';
			if($status=='H')$nm='Hilang';
			if($status=='R')$nm='Rusak';
			if($status=='A')$nm='Diarsipkan';
			if($status=='L')$nm='Dilelang';

			$klas=$this->Md_siperpus_klasifikasi->getKlasifikasiAll();
			$kat=$this->Md_siperpus_kategori_buku->getKategoriBukuAll();
			$asal=$this->Md_siperpus_asal_buku->getAsalBukuAll();
			$bahasa=$this->Md_siperpus_bahasa->getBahasaAll();
			$mspst=$this->Md_vwprodi->getProdiAll();

			$xAxis=array();
			$yAxis=array();
			$total=0;
				foreach($klas as $k){
					$invs=$this->Md_siperpus_data_buku->getBukuByStatusKlas($status,$k->id);
					array_push($xAxis,array('number',$k->nama));
					if($invs>0){
					array_push($yAxis,$invs);
					}else{
					array_push($yAxis,0);
					}
					$total+=$invs;
				}
			$page_data['pxAxis'] = $xAxis;
			$page_data['pyAxis'] = $yAxis;
			$page_data['ptotal'] = $total;
			$page_data['ptitle'] = 'Statistik Judul Buku Berdasarkan Klasifikasi dengan Status '.$nm;



			$xAxis=array();
			$yAxis=array();
			$total=0;
				foreach($kat as $k){
					$invs=$this->Md_siperpus_data_buku->getBukuByStatusKat($status,$k->idkategori);
					array_push($xAxis,array('number',$k->nmkategori));
					if($invs>0){
					array_push($yAxis,$invs);
					}else{
					array_push($yAxis,0);
					}
					$total+=$invs;
				}
			$page_data['kxAxis'] = $xAxis;
			$page_data['kyAxis'] = $yAxis;
			$page_data['ktotal'] = $total;
			$page_data['ktitle'] = 'Statistik Judul Buku Berdasarkan Kategori dengan Status '.$nm;


			$xAxis=array();
			$yAxis=array();
			$total=0;
			foreach($asal as $a){
					$invs=$this->Md_siperpus_data_buku->getBukuByStatusAsal($status,$a->id);
					array_push($xAxis,array('number',$a->nama));
					if($invs>0){
					array_push($yAxis,$invs);
					}else{
					array_push($yAxis,0);
					}
					$total+=$invs;
				}
			$page_data['mxAxis'] = $xAxis;
			$page_data['myAxis'] = $yAxis;
			$page_data['mtotal'] = $total;
			$page_data['mtitle'] = 'Statistik Judul Buku Berdasarkan Asal Buku dengan Status '.$nm;

			$xAxis=array();
			$yAxis=array();
			$total=0;
			foreach($bahasa as $b){
					$pinjam=$this->Md_siperpus_data_buku->getBukuByStatusBahasa($status,$b->id);
					array_push($xAxis,array('number',$b->nama));
					if($pinjam>0){
					array_push($yAxis,$pinjam);
					}else{
					array_push($yAxis,0);
					}
					$total+=$pinjam;
			}
			$page_data['nxAxis'] = $xAxis;
			$page_data['nyAxis'] = $yAxis;
			$page_data['ntotal'] = $total;
			$page_data['ntitle'] = 'Statistik Judul Buku Berdasarkan Bahasa Buku dengan Status '.$nm;

			$xAxis=array();
			$yAxis=array();
			$total=0;
			$bukupinjam=$this->Md_siperpus_transaksi->getStatusBukuPinjamTerbanyak($status,10);
			if(count($bukupinjam)>0){
				foreach($bukupinjam as $b){
						array_push($xAxis,array('number',$b->judul));
						if($b->total>0){
						array_push($yAxis,$b->total);
						}else{
						array_push($yAxis,0);
						}
						$total+=$b->total;
				}
			}else{
				array_push($xAxis,array('number',''));
				array_push($yAxis,0);
			}
			$page_data['qxAxis'] = $xAxis;
			$page_data['qyAxis'] = $yAxis;
			$page_data['qtotal'] = $total;
			$page_data['qtitle'] = 'Statistik Judul Buku Yang Paling Banyak Dipinjam dengan Status '.$nm;
		}
		else if($param1=='bukubythn_judul') {
			$page_data['page_action'] = $param1;
			$gettahun= $this->Md_siperpus_data_buku->getTahunAll();
			$page_data['gettahun'] = $gettahun;

			if(!$this->input->post('status'))$status='';
			else $status=$this->input->post('status');
			$page_data['cur'] = $status;

			if(!$this->input->post('tmp'))$tmp='T';
			else $tmp=$this->input->post('tmp');
			$page_data['tmp'] = $tmp;

			if(!$this->input->post('tahun'))$tahun='';
			else $tahun=$this->input->post('tahun');
			$page_data['tahun'] = $tahun;
			// var_dump($this->input->post('tahun'));die;

			$nm='';
			if($status=='')$nm='Stok Aktif';
			if($status=='H')$nm='Hilang';
			if($status=='R')$nm='Rusak';
			if($status=='A')$nm='Diarsipkan';
			if($status=='L')$nm='Dilelang';

			$klas=$this->Md_siperpus_klasifikasi->getKlasifikasiAll();
			$kat=$this->Md_siperpus_kategori_buku->getKategoriBukuAll();
			$asal=$this->Md_siperpus_asal_buku->getAsalBukuAll();
			$bahasa=$this->Md_siperpus_bahasa->getBahasaAll();
			$mspst=$this->Md_vwprodi->getProdiAll();

			if ($tmp=='T' || $tmp=='Pen') {
				$xAxis=array();
				$yAxis=array();
				$total=0;
					foreach($gettahun as $k){
						$thn=$this->Md_siperpus_data_buku->getBukuByThnJudul_Total($status,$k->thn_terbit);
						// var_dump($thn);die;
						array_push($xAxis,array('number',$k->thn_terbit));
						if($thn>0){
						array_push($yAxis,$thn);
						}else{
						array_push($yAxis,0);
						}
						$total+=$thn;
					}
				$page_data['pxAxis'] = $xAxis;
				$page_data['pyAxis'] = $yAxis;
				$page_data['ptotal'] = $total;
				$page_data['ptitle'] = 'Statistik (Berdasar Judul Buku) Tahun Terbit Buku dengan Status Stok '.$nm;
			}
			else {
				$xAxis=array();
				$yAxis=array();
				$total=0;
					foreach($klas as $k){
						$invs=$this->Md_siperpus_data_buku->getBukuByThnJudul_Klas($status,$k->id,$tahun);
						array_push($xAxis,array('number',$k->nama));
						if($invs>0){
						array_push($yAxis,$invs);
						}else{
						array_push($yAxis,0);
						}
						$total+=$invs;
					}
				$page_data['pxAxis'] = $xAxis;
				$page_data['pyAxis'] = $yAxis;
				$page_data['ptotal'] = $total;
				$page_data['ptitle'] = 'Statistik (Berdasar Judul Buku) Tahun Terbit Buku Berdasarkan Klasifikasi Buku dengan Status Stok '.$nm.' ('.$tahun.')';

				$xAxis=array();
				$yAxis=array();
				$total=0;
					foreach($kat as $k){
						$invs=$this->Md_siperpus_data_buku->getBukuByThnJudul_Kat($status,$k->idkategori,$tahun);
						array_push($xAxis,array('number',$k->nmkategori));
						if($invs>0){
						array_push($yAxis,$invs);
						}else{
						array_push($yAxis,0);
						}
						$total+=$invs;
					}
				$page_data['kxAxis'] = $xAxis;
				$page_data['kyAxis'] = $yAxis;
				$page_data['ktotal'] = $total;
				$page_data['ktitle'] = 'Statistik (Berdasar Judul Buku) Tahun Terbit Buku Berdasarkan Kategori Buku dengan Status Stok '.$nm.' ('.$tahun.')';


				$xAxis=array();
				$yAxis=array();
				$total=0;
				foreach($asal as $a){
						$invs=$this->Md_siperpus_data_buku->getBukuByThnJudul_Asal($status,$a->id,$tahun);
						array_push($xAxis,array('number',$a->nama));
						if($invs>0){
						array_push($yAxis,$invs);
						}else{
						array_push($yAxis,0);
						}
						$total+=$invs;
					}
				$page_data['mxAxis'] = $xAxis;
				$page_data['myAxis'] = $yAxis;
				$page_data['mtotal'] = $total;
				$page_data['mtitle'] = 'Statistik (Berdasar Judul Buku) Tahun Terbit Buku Berdasarkan Asal Buku dengan Status Stok '.$nm.' ('.$tahun.')';

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
		else if($param1=='bukubythn_jml') {
			$page_data['page_action'] = $param1;
			$gettahun= $this->Md_siperpus_data_buku->getTahunAll();
			$page_data['gettahun'] = $gettahun;

			if(!$this->input->post('status'))$status='';
			else $status=$this->input->post('status');
			$page_data['cur'] = $status;

			if(!$this->input->post('tmp'))$tmp='T';
			else $tmp=$this->input->post('tmp');
			$page_data['tmp'] = $tmp;

			if(!$this->input->post('tahun'))$tahun='';
			else $tahun=$this->input->post('tahun');
			$page_data['tahun'] = $tahun;
			// var_dump($this->input->post('tahun'));die;

			$nm='';
			if($status=='')$nm='Stok Aktif';
			if($status=='H')$nm='Hilang';
			if($status=='R')$nm='Rusak';
			if($status=='A')$nm='Diarsipkan';
			if($status=='L')$nm='Dilelang';

			$klas=$this->Md_siperpus_klasifikasi->getKlasifikasiAll();
			$kat=$this->Md_siperpus_kategori_buku->getKategoriBukuAll();
			$asal=$this->Md_siperpus_asal_buku->getAsalBukuAll();
			$bahasa=$this->Md_siperpus_bahasa->getBahasaAll();
			$mspst=$this->Md_vwprodi->getProdiAll();

			if ($tmp=='T' || $tmp=='Pen') {
				$xAxis=array();
				$yAxis=array();
				$total=0;
					foreach($gettahun as $k){
						$thn=$this->Md_siperpus_inventaris->getJmlBukuByThnJudul_Total($status,$k->thn_terbit);
						// var_dump($thn);die;
						array_push($xAxis,array('number',$k->thn_terbit));
						if($thn>0){
						array_push($yAxis,$thn);
						}else{
						array_push($yAxis,0);
						}
						$total+=$thn;
					}
				$page_data['pxAxis'] = $xAxis;
				$page_data['pyAxis'] = $yAxis;
				$page_data['ptotal'] = $total;
				$page_data['ptitle'] = 'Statistik (Berdasar Judul Buku) Tahun Terbit Buku dengan Status Stok '.$nm;
			}
			else {
				$xAxis=array();
				$yAxis=array();
				$total=0;
					foreach($klas as $k){
						$invs=$this->Md_siperpus_inventaris->getJmlBukuByThnJudul_Klas($status,$k->id,$tahun);
						array_push($xAxis,array('number',$k->nama));
						if($invs>0){
						array_push($yAxis,$invs);
						}else{
						array_push($yAxis,0);
						}
						$total+=$invs;
					}
				$page_data['pxAxis'] = $xAxis;
				$page_data['pyAxis'] = $yAxis;
				$page_data['ptotal'] = $total;
				$page_data['ptitle'] = 'Statistik (Berdasar Judul Buku) Tahun Terbit Buku Berdasarkan Klasifikasi Buku dengan Status Stok '.$nm.' ('.$tahun.')';

				$xAxis=array();
				$yAxis=array();
				$total=0;
					foreach($kat as $k){
						$invs=$this->Md_siperpus_inventaris->getJmlBukuByThnJudul_Kat($status,$k->idkategori,$tahun);
						array_push($xAxis,array('number',$k->nmkategori));
						if($invs>0){
						array_push($yAxis,$invs);
						}else{
						array_push($yAxis,0);
						}
						$total+=$invs;
					}
				$page_data['kxAxis'] = $xAxis;
				$page_data['kyAxis'] = $yAxis;
				$page_data['ktotal'] = $total;
				$page_data['ktitle'] = 'Statistik (Berdasar Judul Buku) Tahun Terbit Buku Berdasarkan Kategori Buku dengan Status Stok '.$nm.' ('.$tahun.')';


				$xAxis=array();
				$yAxis=array();
				$total=0;
				foreach($asal as $a){
						$invs=$this->Md_siperpus_inventaris->getJmlBukuByThnJudul_Asal($status,$a->id,$tahun);
						array_push($xAxis,array('number',$a->nama));
						if($invs>0){
						array_push($yAxis,$invs);
						}else{
						array_push($yAxis,0);
						}
						$total+=$invs;
					}
				$page_data['mxAxis'] = $xAxis;
				$page_data['myAxis'] = $yAxis;
				$page_data['mtotal'] = $total;
				$page_data['mtitle'] = 'Statistik (Berdasar Judul Buku) Tahun Terbit Buku Berdasarkan Asal Buku dengan Status Stok '.$nm.' ('.$tahun.')';

			}
		}

		$this->load->view('index', $page_data);

	}
	function cetak($param1="", $param2="", $param3=""){
	 if ($this->session->userdata('login_type') != 'admin')

            $this->logout();
		if($this->input->post('imagedata'))$page_data['imagedata'] = $this->input->post('imagedata');
		if($this->input->post('imagedata2'))$page_data['imagedata'] = $this->input->post('imagedata2');
		if($this->input->post('imagedata3'))$page_data['imagedata'] = $this->input->post('imagedata3');
		if($this->input->post('imagedata4'))$page_data['imagedata'] = $this->input->post('imagedata4');
		if($this->input->post('imagedata5'))$page_data['imagedata'] = $this->input->post('imagedata5');
		if($this->input->post('type'))$page_data['import'] = $this->input->post('type');
		else $page_data['import'] = 'chart';
		if($page_data['import']=='table'){
			$mspst=$this->Md_vwprodi->getProdiAll();
			$klas=$this->Md_siperpus_klasifikasi->getKlasifikasiAll();

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
function laporan_buku_prodi($param1='',$param2='')
	{
		if ($this->session->userdata('login_type') != 'admin')
				$this->logout();

		$date = new DateTime();
		$id=$this->session->userdata('idsys');
		$page_data['src']='';
		$page_data['prodi']='';
		$page_data['klas'] = '';
		$page_data['ktg'] = '';
		$page_data['page_title'] = 'Form Daftar Buku Referensi Jurusan';

		$page_data['jur'] = $this->Md_vwprodi->getProdiAll();
		$page_data['klasifikasi'] = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
		$page_data['kategori'] = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
		if($this->input->post('src'))$page_data['src'] = $this->input->post('src');
		if($this->input->post('klasifikasi'))$page_data['klas'] = $this->input->post('klasifikasi');
		if($this->input->post('kategori'))$page_data['ktg'] = $this->input->post('kategori');
		if($this->input->post('prodi'))$page_data['prodi'] = $this->input->post('prodi');

		if($param1=='export'){
			$klas='';
			$ktg='';
			$prodi='';
			$search='';

			if($param2=='excel'){
				$klas=$this->input->post('eklas');
				$ktg=$this->input->post('ektg');
				$prodi=$this->input->post('eprodi');
				$search=$this->input->post('esearch');
			}elseif($param2=='web'){
				$klas=$this->input->post('wklas');
				$ktg=$this->input->post('wktg');
				$prodi=$this->input->post('wprodi');
				$search=$this->input->post('wsearch');
			}
			$page_data['nmkls'] = $this->Md_siperpus_klasifikasi->getKlasifikasiById($klas);
			$page_data['nmprodi'] = $this->Md_vwprodi->getProdiById($prodi);
			$page_data['nmktg'] = $this->Md_siperpus_kategori_buku->getKategoriById($ktg);
			$page_data['laporan'] = 'laporan_buku_prodi';
			$page_data['export'] =$param2;
			$page_data['page_title'] = 'Form Daftar Buku Referensi Jurusan';
			$page_data['title'] = 'Form Daftar Buku Referensi Jurusan';
			$page_data['data']=$this->Md_laporan_buku_prodi->getLaporan($klas,$ktg,$prodi,$search);
			$this->load->view('admin/excel', $page_data);
		}else if($param1 =='fetch'){
			$total=$this->Md_laporan_buku_prodi->countFiltered();
			$page=intval($this->input->post('datatable[pagination][page]'));
			if($page<1)
				$page=1;

			$perpage=intval($this->input->post('datatable[pagination][perpage]'));
			$pages= intval($total/$perpage);
			$field=$this->input->post('datatable[sort][field]');

			if($field=='')
				$field=$this->input->post('datatable[pagination][field]');

			$sort=$this->input->post('datatable[sort][sort]');

			if($sort=='')
				$sort=$this->input->post('datatable[pagination][sort]');

			//mulai fetching data
			$data = array();
			$no = 0;
			$list=$this->Md_laporan_buku_prodi->getDatatables();
			foreach ($list as $row) {
				$no++;
				$arr = array();
					$arr['number'] = ($perpage*($page-1))+$no;
					$arr['noklas'] = $row->no_klas;
					$arr['judul'] = $row->judul;
					$arr['penerbit'] = $row->penerbit;
					$nmprodi='';
					$prodi=$this->Md_siperpus_buku_prodi->getBukuByISBN($row->ISBN,$row->no_klas);
					if(count($prodi)>0){
						foreach($prodi as $p){
						if($nmprodi=='')$nmprodi=$p->namaprodi;
						else $nmprodi=$nmprodi.' , '.$p->namaprodi;
						}
					}else{
						$nmprodi='';
					}

					$arr['jurusan'] = $nmprodi;
					$arr['jumlah'] = $row->jml_buku;
				$data[] = $arr;
			}

			$meta=array();
			$meta['page']=$page;
			$meta['pages']=$pages;
			$meta['perpage']=$perpage;
			$meta['total']=$total;
			$meta['sort']=$sort;
			$meta['field']=$field;
			$output = array(
							"meta" => $meta,
							"data" => $data
					);
			//output to json format
			echo json_encode($output);
		}else{
			$page_data['page_access'] = "admin";
			$page_data['page_name'] = 'laporan_buku_prodi';
			$page_data['page_now'] = 'dashboard';
			$this->load->view('index', $page_data);
		}
	}
}
