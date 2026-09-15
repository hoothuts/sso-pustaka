<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_buku extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->database();

        $this->load->model('Md_siperpus_klasifikasi');
        $this->load->model('Md_siperpus_kategori_buku');
        $this->load->model('Md_siperpus_penerbit');
        $this->load->model('Md_siperpus_bahasa');
        $this->load->model('Md_vwprodi');
        $this->load->model('Md_siperpus_data_buku');
        $this->load->model('Md_siperpus_inventaris');
        $this->load->model('Md_siperpus_inventaris_one');
        $this->load->model('Md_siperpus_buku_prodi');
        $this->load->model('Md_siperpus_buku_file');
        $this->load->model('Md_siperpus_asal_buku');
        $this->load->model('Md_lokasi');
        $this->load->model('Md_log');

        $this->load->helper('pkrlib_helper');
        $this->load->helper('encryption_id_helper');

        $this->load->library('upload');
        $this->load->library('image_lib');

        if ($this->session->userdata('login_type') != 'admin') {
            logoutNow();
        }
    }

    public function index() {
        //$date = new DateTime(); //digunakan pada saat submit, update, edit inventaris dan tambah inventaris
        /* if ($param1 != 'submit' && $param1 != 'update') {
          session_write_close();
          } */

        $page_data['page_action'] = 'list';
        $page_data['page_title'] = 'Data Buku';
        $page_data['page_access'] = "dir";
        $page_data['page_name'] = 'manage_buku';
        $page_data['page_now'] = 'Buku & Inventarisasi';
        $page_data['klasifikasi'] = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
        $page_data['kategori'] = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
        $page_data['tahun_terbit'] = $this->Md_siperpus_data_buku->getTahunTerbitBuku();
        //$x = urldecode(str_replace('_', '/', $param2)); //ISBN (mengubah ISBN dengan pola xx_xx_xx_xx menjadi xx/xx/xx/xx)
        //$y = urldecode(str_replace('%20', ' ', $param3)); //Noklas(mengubah no_klas dengan pola xx%20xx%20xx menjadi xx xx xx)
        
        $page_data['page_dir']    = 'manage_buku';
        $page_data['page_file']   = 'list';
        $this->load->view('index', $page_data);
    }
    
    public function list() {
        session_write_close();

        // 1. Ambil data utama & total (Langsung gunakan countFiltered)
        // Jika tidak ada filter, countFiltered otomatis akan menghitung total semua baris
        $list  = $this->Md_siperpus_data_buku->getDatatables();
        $total = $this->Md_siperpus_data_buku->countFiltered();

        // 2. Ambil parameter pagination & sort
        $page    = max(1, intval($this->input->post('datatable[pagination][page]')));
        $perpage = intval($this->input->post('datatable[pagination][perpage]')) ?: 10;
        $pages   = ceil($total / $perpage);

        $field = $this->input->post('datatable[sort][field]') ?: $this->input->post('datatable[pagination][field]');
        $sort  = $this->input->post('datatable[sort][sort]') ?: $this->input->post('datatable[pagination][sort]');

        // 3. Mapping data (Gunakan array_map atau foreach yang lebih rapi)
        $data = array();
        $start_index = ($perpage * ($page - 1));

        foreach ($list as $key => $row) {
            $data[] = array(
                'number'     => $start_index + ($key + 1),
                'id'         => [$row->no_klas, $row->ISBN], // Array untuk ID composite
                'isbn'       => $row->ISBN,
                'no_klas'    => $row->no_klas,
                'no_rak'     => $row->no_rak,
                'judul'      => $row->judul,
                'penulis'    => $row->penulis,
                'penerbit'   => $row->nama_penerbit,
                'thn_terbit' => $row->thn_terbit,
                'jml_buku'   => $row->jumlah_buku,
                'jml_pinjam' => $row->jml_pinjam,
                'mk'         => "",
                'jml_prodi'  => $row->jml_prodi,
                'buku_id'       => encrypt($row->buku_id)
            );
        }

        // 4. Output JSON
        $output = array(
            "meta" => array(
                "page"    => $page,
                "pages"   => $pages,
                "perpage" => $perpage,
                "total"   => (int)$total,
                "sort"    => $sort,
                "field"   => $field,
            ),
            "data" => $data
        );

        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($output));
    }
    
    // Function to sanitize filename
    function sanitize_filename($filename) {
        return preg_replace('/[^A-Za-z0-9\-\_\.]/', '', $filename);
    }

    public function tambah() {
        $page_data['data']['kel_buku'] = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
        $page_data['data']['penerbit'] = $this->Md_siperpus_penerbit->getPenerbitAll();
        $page_data['data']['bahasa'] = $this->Md_siperpus_bahasa->getBahasaAll();
        $page_data['data']['prodi'] = $this->Md_vwprodi->getProdiAll();        
        $page_data['page_action'] = 'tambah';
        $page_data['page_title'] = 'Tambah Data Buku';
        $page_data['page_now'] = 'Buku & Inventarisasi';
        $page_data['page_name'] = 'manage_buku';
        $page_data['page_dir']    = 'manage_buku';
        $page_data['page_file']   = 'tambah';
        $this->load->view('index', $page_data);
    }
    
    public function submit() {
        $this->load->library('form_validation');
        
        // 1. Set rules validasi form (ganti pengecekan manual dengan CI form_validation)
        $this->form_validation->set_rules('no_klas', 'No Klas', 'required|trim');
        $this->form_validation->set_rules('jml_hal', 'Jumlah Halaman', 'required|trim|numeric');
        $this->form_validation->set_rules('penerbit', 'Kode Penerbit', 'required|trim');
        $this->form_validation->set_rules('ukuran_fisik', 'Ukuran Fisik', 'required|trim');
        $this->form_validation->set_rules('penulis', 'Penulis', 'required|trim');
        $this->form_validation->set_rules('judul', 'Judul', 'required|trim');
        $this->form_validation->set_rules('tajuksubyek', 'Tajuk Subyek', 'required|trim');
        $this->form_validation->set_rules('isbn', 'ISBN', 'required|trim');
        $this->form_validation->set_rules('thn_terbit', 'Tahun Terbit', 'required|trim|numeric');

        // Pesan error custom
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status'  => 'error',
                'message' => validation_errors()
            ]);
            exit;
        }

        $date = new DateTime();
        $data = [
            'no_klas' => trim($this->input->post('no_klas')),
            'ISBN' => trim($this->input->post('isbn')),
            'idkategori' => $this->input->post('kategori_buku'),
            'judul' => trim($this->input->post('judul')),
            'cetakkatalog_judulpenggal' => $this->input->post('cetakkatalog_judulpenggal'),
            'judulasli' => trim($this->input->post('judulasli')),
            'allow_review' => $this->input->post('allow_review') ?: 0,
            'deskripsi' => $this->input->post('deskripsi'),
            'penulis' => trim($this->input->post('penulis')),
            'penyadur' => trim($this->input->post('penyadur')),
            'penerjemah' => trim($this->input->post('penerjemah')),
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
            'ilustrasi' => $this->input->post('ilustrasi') ?: 0,
            'tabel' => $this->input->post('tabel') ?: 0,
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
        ];

        $prodi = $this->input->post('prodi') ?? [];

        // 1. Cek duplikat kombinasi ISBN + no_klas
        if ($this->Md_siperpus_data_buku->getDataBukuByISBNdanNo_klas($data['ISBN'], $data['no_klas'])) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Kombinasi ISBN dan No Klas sudah ada di database.'
            ]);
            exit;
        }

        // 2. Proses upload gambar cover (jika ada)
        $cover_filename = null;
        if (!empty($_FILES['gambar']['name'])) {
            $config1 = [
                'upload_path' => FCPATH . "uploads/covers",
                'allowed_types' => 'gif|jpg|png|jpeg|bmp',
                'file_name' => $date->format('YmdHis') . '-' . $_FILES["gambar"]['name'],
                'max_size' => 1024 * 20 // 20MB
            ];
            $this->upload->initialize($config1);

            if ($this->upload->do_upload('gambar')) {
                $data_up = $this->upload->data();
                $cover_filename = $data_up['file_name'];

                // Resize gambar
                $config['image_library'] = 'gd2';
                $config['source_image'] = $data_up['full_path'];
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 200;
                $config['height'] = 250;
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
            } else {
                echo json_encode([
                    'status'  => 'error',
                    'message' => $this->upload->display_errors()
                ]);
                exit;
            }
        }
        $data['cover'] = $cover_filename;
        
        // Mulai transaksi
        $this->db->trans_start();
        
        // 3. Insert data buku utama
        $this->Md_siperpus_data_buku->addDataBuku($data);

        // 4. Insert data prodi (jika ada)
        if (!empty($prodi)) {
            foreach ($prodi as $prodi_id) {
                $dataBukuProdi = [
                    'no_klas' => $data['no_klas'],
                    'ISBN' => $data['ISBN'],
                    'idmspst' => $prodi_id,
                    'idkategori' => $data['idkategori']
                ];
                $this->Md_siperpus_buku_prodi->addBukuProdi($dataBukuProdi);
            }
        }

        // 5. Insert data TA (jika kategori TA)
        if ($data['idkategori'] == 3) {
            $data_kti = [
                'no_klas_ta' => $data['no_klas'],
                'ISBN_ta' => $data['ISBN'],
                'nis_ta' => $this->input->post('nis_ta'),
                'tabel_ta' => $this->input->post('tabel_ta') ?: 0,
                'lampiran_ta' => $this->input->post('lampiran_ta') ?: 0,
                'pembimbing_ta' => $this->input->post('pembimbing_ta'),
            ];
            $this->Md_siperpus_data_buku->addDataBukuTa($data_kti);
        }

        // 6. Proses upload file buku (jika ada)
        if (!empty($_FILES['file']['name'][0])) {
            $filesCount = count($_FILES['file']['name']);
            $isbacaCount = count($this->input->post('is_baca_file') ?? []);
            $isdownloadCount = count($this->input->post('is_download_file') ?? []);

            if ($filesCount !== $isbacaCount || $filesCount !== $isdownloadCount) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Total Buku dan Option Is Baca / Is Download tidak sama.'
                ]);
                exit;
            }

            $uploaded_files = [];
            for ($i = 0; $i < $filesCount; $i++) {
                $_FILES['userFile'] = [
                    'name' => $_FILES['file']['name'][$i],
                    'type' => $_FILES['file']['type'][$i],
                    'tmp_name' => $_FILES['file']['tmp_name'][$i],
                    'error' => $_FILES['file']['error'][$i],
                    'size' => $_FILES['file']['size'][$i]
                ];

                $sanitized_name = $this->sanitize_filename($_FILES['userFile']['name']);
                $new_file = $date->format('YmdHis') . '-' . $sanitized_name;

                $config = [
                    'upload_path' => FCPATH . "uploads/files",
                    'allowed_types' => 'pdf|doc|docx|xls|xlsx|zip|txt',
                    'file_name' => $new_file,
                    'max_size' => 1024 * 20 // 20MB
                ];
                $this->upload->initialize($config);

                if ($this->upload->do_upload('userFile')) {
                    $data_up = $this->upload->data();
                    $uploaded_files[] = [
                        'filename' => $data_up['file_name'],
                        'raw_name' => $data_up['raw_name'],
                        'is_baca' => $this->input->post('is_baca_file')[$i],
                        'is_download' => $this->input->post('is_download_file')[$i]
                    ];
                } else {
                    echo json_encode([
                        'status'  => 'error',
                        'message' => $this->upload->display_errors()
                    ]);
                    exit;
                }
            }

            // Insert data file ke tabel (jika ada tabel file buku)
            if (!empty($uploaded_files)) {
                $data_upload = [
                    'file_name' => json_encode($uploaded_files),
                    'no_klas' => $data['no_klas'],
                    'ISBN' => $data['ISBN']
                ];
                $this->Md_siperpus_data_buku->addDataFile($data_upload);
            }
        }

        // 7. Insert log (dalam transaksi)
        $log = [
            'user_id' => $this->session->userdata('idsys'),
            'jenis_log' => 'Admin',
            'jenis_akses' => 'Add',
            'status' => 1,
            'keterangan' => $this->session->userdata('username') . ' Melakukan Add Buku ' . $data['no_klas'] . ' - ' . $data['judul'],
            'IP' => $this->input->ip_address()
        ];
        $this->Md_log->addLog($log);

        // Final check transaksi
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode([
                'status'  => 'error',
                'message' => 'Data gagal disimpan. Terjadi kesalahan sistem.'
            ]);
        }else {
            $this->db->trans_commit();
            echo json_encode([
                'status'  => 'success',
                'message' => 'Data Berhasil disimpan'
            ]);
        }
        exit;
    }

    public function update() {
        // 1. Set rules validasi form (ganti pengecekan manual dengan CI form_validation)
        $this->form_validation->set_rules('no_klas2', 'No Klas', 'required|trim');
        $this->form_validation->set_rules('isbn2', 'ISBN', 'required|trim');
        $this->form_validation->set_rules('jml_hal', 'Jumlah Halaman', 'required|trim|numeric');
        $this->form_validation->set_rules('kd_penerbit', 'Kode Penerbit', 'required|trim');
        $this->form_validation->set_rules('ukuran_fisik', 'Ukuran Fisik', 'required|trim');
        $this->form_validation->set_rules('penulis', 'Penulis', 'required|trim');
        $this->form_validation->set_rules('judul', 'Judul', 'required|trim');
        $this->form_validation->set_rules('tajuksubyek', 'Tajuk Subyek', 'required|trim');
        $this->form_validation->set_rules('isbn', 'ISBN', 'required|trim');
        $this->form_validation->set_rules('thn_terbit', 'Tahun Terbit', 'required|trim|numeric');

        // Pesan error custom
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
        $this->form_validation->set_message('numeric', '%s harus berupa angka');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Data gagal disimpan! <br/>'.validation_errors());
            redirect(base_url() . 'dir/manage_buku/edit/' . encrypt($this->input->post('buku_id')), 'refresh');
            exit;
        }
        
        $date = new DateTime();
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
        
        /* ===== start cek file buku ===== */
        if (!empty($_FILES['file']['name'])) {
            $filesCount = count($_FILES['file']['name']);
            $isbacabukufileCount = count($this->input->post('is_baca_file'));
            $isdownloadbukufileCount = count($this->input->post('is_download_file'));

            if (!($filesCount === $isbacabukufileCount && $filesCount === $isdownloadbukufileCount)) {
                $this->session->set_flashdata('error_updatebuku', 'Total Buku dan Option Is Baca dan Is Download Tidak sama');
                redirect(base_url() . 'dir/manage_buku/edit/' . encrypt($this->input->post('buku_id')), 'refresh');
                die;
            }
        }

        $file_id = $this->input->post('file_id');
        $file = $this->Md_siperpus_buku_file->getFileByFileId($file_id);

        $arrFilenameData = array();
        $removefile = array();
        $arrRawname = array();

        if ($file && $file->file_name != '' && $file->file_name != null) {
            $versi_file = determine_version($file->file_name);
            if ($versi_file == 1 || $versi_file == 2) {
                $decoded_data_filename = json_decode($file->file_name, true);
                $decoded_data_rawname = json_decode($file->raw_name, true);
                for ($i = 0; $i < count($decoded_data_filename); $i++) {
                    // penentuan key POST
                    $isbaca = ($versi_file == 1) ? $this->input->post('is_baca_file*' . $i) : $this->input->post('is_baca_file_' . $i);
                    $is_download = ($versi_file == 1) ? $this->input->post('is_download_file*' . $i) : $this->input->post('is_download_file_' . $i);
                    // penentuan filename
                    $filename = ($versi_file == 1) ? $decoded_data_filename[$i] : $decoded_data_filename[$i]['filename'];
                    $rawname = ($versi_file == 1) ? $decoded_data_rawname[$i] : $decoded_data_rawname[$i]['filename'];
                    if ($isbaca) {
                        array_push($arrFilenameData, array(
                            'filename' => $filename,
                            'is_baca' => $isbaca,
                            'is_download' => $is_download,
                        ));
                        array_push($arrRawname, array(
                            'filename' => $rawname,
                            'is_baca' => $isbaca,
                            'is_download' => $is_download,
                        ));
                    } else {
                        array_push($removefile, 'uploads/files/' . $filename);
                    }
                }
            } else if ($versi_file == 3) {
                $isbaca = $this->input->post('is_baca_file_0');
                $is_download = $this->input->post('is_download_file_0');
                if ($isbaca) {
                    array_push($arrFilenameData, array(
                        'filename' => $file->file_name,
                        'is_baca' => $isbaca,
                        'is_download' => $is_download,
                    ));
                    array_push($arrRawname, array(
                        'filename' => $file->raw_name,
                        'is_baca' => $isbaca,
                        'is_download' => $is_download,
                    ));
                } else {
                    array_push($removefile, 'uploads/files/' . $file->file_name);
                }
            }
        }        
        /* ===== end cek file buku ===== */
        
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
            $data_kti['tabel_ta']    = empty($data_kti['tabel_ta']) ? 0 : $data_kti['tabel_ta'];
            $data_kti['lampiran_ta'] = empty($data_kti['lampiran_ta']) ? 0 : $data_kti['lampiran_ta'];

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
        $data['ilustrasi']      = empty($data['ilustrasi']) ? 0 : $data['ilustrasi'];
        $data['tabel']          = empty($data['tabel']) ? 0 : $data['tabel'];
        $data['allow_review']   = empty($data['allow_review']) ? 0 : $data['allow_review'];

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
            $filesCount = count($_FILES['file']['name']);
            for ($i = 0; $i < $filesCount; $i++) {
                $_FILES['userFile']['name'] = $_FILES['file']['name'][$i];
                $_FILES['userFile']['type'] = $_FILES['file']['type'][$i];
                $_FILES['userFile']['tmp_name'] = $_FILES['file']['tmp_name'][$i];
                $_FILES['userFile']['error'] = $_FILES['file']['error'][$i];
                $_FILES['userFile']['size'] = $_FILES['file']['size'][$i];
                $sanitized_name = $this->sanitize_filename($_FILES['userFile']['name']);
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
                    redirect(base_url() . 'dir/manage_buku/edit/' . encrypt($this->input->post('buku_id')), 'refresh');
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
            redirect(base_url() . 'dir/manage_buku','refresh');
            die;
        } else {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error_updatebuku', 'Data gagal disimpan');
            // echo json_encode(array('status' => 'gagal', 'message' => 'Data gagal disimpan'));
            // die;
            redirect(base_url() . 'dir/manage_buku/edit/' . encrypt($this->input->post('buku_id')), 'refresh');
            die;
        }
        
        redirect(base_url() . 'dir/manage_buku', 'refresh');
    }

    public function hapus($isbn_param,$no_klas_param) {
        $isbn = urldecode(str_replace('_', '/', $isbn_param));
        $no_klas = urldecode(str_replace('%20', ' ', $no_klas_param));

        // 🔥 CEK RELASI
        $this->load->model('Md_siperpus_inventaris');
        $relasi = $this->Md_siperpus_inventaris->cek_relasi_buku($isbn, $no_klas);

        if ($relasi) {
            echo json_encode([
                "status" => false,
                "msg" => "Buku tidak bisa dihapus karena sudah digunakan pada data " . strtoupper($relasi)
            ]);
            return;
        }

        // ================================
        // LANJUT DELETE SETELAH CEK RELASI
        // ================================

        $cover = $this->Md_siperpus_data_buku->getDataBukuByISBNdanNo_klas($isbn, $no_klas);
        $file = $this->Md_siperpus_buku_file->getFileById($isbn, $no_klas);

        // hapus file DB
        $this->Md_siperpus_buku_file->hapusFileById($isbn, $no_klas);

        // hapus file fisik
        if (!empty($cover[0]->cover)) {
            @unlink(FCPATH . 'uploads/covers/' . $cover[0]->cover);
        }

        if (!empty($file[0]['file_name'])) {
            @unlink(FCPATH . 'uploads/files/' . $file[0]['file_name']);
        }

        // hapus data utama
        $this->Md_siperpus_data_buku->hapusDataBuku($isbn, $no_klas);
        $this->Md_siperpus_inventaris->hapusInventarisAll($isbn);

        // log
        $log = array(
            'user_id' => $this->session->userdata('idsys'),
            'jenis_log' => 'Admin',
            'jenis_akses' => 'Delete',
            'status' => 1,
            'keterangan' => $this->session->userdata('username') . ' Melakukan Delete Buku ' . $no_klas,
            'IP' => $this->input->ip_address()
        );

        $this->Md_log->addLog($log);

        echo json_encode([
            "status" => TRUE,
            "msg" => "Data Buku Sukses Dihapus"
        ]);
    }
    
    public function edit($enc_buku_id) {
        $buku_id = decrypt($enc_buku_id);
        $valid = $enc_buku_id == '' ? false : (is_int($buku_id) ? true : false);
        if (!$valid) {
            $this->session->set_flashdata('error', 'Data buku tidak ditemukan');
            redirect(base_url() . 'dir/manage_buku/', 'refresh');
            die;
        }
        $dt_buku=$this->Md_siperpus_data_buku->getDataBukuById($buku_id);
        $page_data['data'][0] = $dt_buku;
        $x = $dt_buku->ISBN;
        $y = $dt_buku->no_klas;
        
        //$x = urldecode(str_replace('_', '/', $isbn_param)); //ISBN (mengubah ISBN dengan pola xx_xx_xx_xx menjadi xx/xx/xx/xx)
        //$y = urldecode(str_replace('%20', ' ', $no_klas_param)); //Noklas(mengubah no_klas dengan pola xx%20xx%20xx menjadi xx xx xx)
        $arrjnsfile = array();

        //$page_data['data'] = $this->Md_siperpus_data_buku->getDataBukuByISBNdanNo_klas($x, $y);
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
        $page_data['page_title'] = 'Edit Data Buku';
        $page_data['page_name'] = 'manage_buku';
        $page_data['page_now'] = 'Buku & Inventarisasi';
        $page_data['page_dir']    = 'manage_buku';
        $page_data['page_file']   = 'edit';
        $this->load->view('index', $page_data);
    }

    public function delete_file() {
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

    public function get($isbn,$no_klas) {
        $x = urldecode(str_replace('_', '/', $isbn)); //ISBN (mengubah ISBN dengan pola xx_xx_xx_xx menjadi xx/xx/xx/xx)
        $y = urldecode(str_replace('%20', ' ', $no_klas)); //Noklas(mengubah no_klas dengan pola xx%20xx%20xx menjadi xx xx xx)
        $page_data['page_action'] = 'view';
        $page_data['data'] = $this->Md_siperpus_data_buku->getDataBukuByISBNdanNo_klas($x, $y);
        // $page_data['data'][1]= $this->Md_siperpus_data_buku->getDataFile($x,$y);
        $page_data['data'] = json_decode(json_encode($page_data['data']), true);
        $page_data['prodi'] = $this->Md_siperpus_data_buku->getDataProdiByISBN_No_klas($x, $y);
        $page_data['barcode'] = $this->Md_siperpus_data_buku->getDataBarcodeByISBN_No_klas($x, $y);
        
        $page_data['page_title'] = 'Detail Data Buku';
        $page_data['page_now'] = 'Buku & Inventarisasi';
        $page_data['page_name'] = 'manage_buku';
        $page_data['page_dir']    = 'manage_buku';
        $page_data['page_file']   = 'index';
        $this->load->view('index', $page_data);
    }
    
    public function cek_isbn_for_edit() {
        $buku_id = trim($this->input->post('buku_id'));
        $isbn2 = trim($this->input->post('isbn2'));
        
        $data = $this->Md_siperpus_data_buku->cekIsbnForEdit($isbn2, $buku_id);
        if ($data) {
            echo json_encode(array("status" => TRUE, "msg" => "Data ISBN Sudah Ada"));
        } else {
            echo json_encode(array("status" => false, "msg" => "Data ISBN Dapat Digunakan"));
        }
    }
    
    public function cek_for_edit() {
        $buku_id = trim($this->input->post('buku_id'));
        $no_klas2 = trim($this->input->post('no_klas2'));
        $isbn2 = trim($this->input->post('isbn2'));
        
        $data = $this->Md_siperpus_data_buku->getDataBukuByIsbnNoklas_for_edit($isbn2, $no_klas2, $buku_id);
        if ($data) {
            echo json_encode(array("status" => TRUE, "msg" => "Data ISBN dan NO Klas Sudah Ada"));
        } else {
            echo json_encode(array("status" => false, "msg" => "Data ISBN dan NO Klas Dapat Digunakan"));
        }
    }

    //digunakan pada form tambah buku
    public function cek2() {
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

    public function set_inv($enc_buku_id) {
        $buku_id = decrypt($enc_buku_id);
        $valid = $enc_buku_id == '' ? false : (is_int($buku_id) ? true : false);
        if (!$valid) {
            $this->session->set_flashdata('error', 'Data buku tidak ditemukan');
            redirect(base_url() . 'dir/manage_buku/', 'refresh');
            die;
        }
        $dt_buku=$this->Md_siperpus_data_buku->getDataBukuById($buku_id);
        //$page_data['data'][0] = $dt_buku;
        $x = $dt_buku->ISBN;
        $y = $dt_buku->no_klas;
        
        
        //$x = urldecode(str_replace('_', '/', $isbn)); //ISBN (mengubah ISBN dengan pola xx_xx_xx_xx menjadi xx/xx/xx/xx)
        //$y = urldecode(str_replace('%20', ' ', $no_klas)); //Noklas(mengubah no_klas dengan pola xx%20xx%20xx menjadi xx xx xx)
        $page_data['data'] = $this->Md_siperpus_data_buku->getDataBukuByISBN($x, $y);
        $page_data['data']['asal_buku'] = $this->Md_siperpus_asal_buku->getAsalBukuAll();
        $page_data['data']['lokasi'] = $this->Md_siperpus_inventaris->get_lokasi_rak_options();
        $page_data['isbn'] = $x;
        $page_data['no_klas'] = $y;
        $page_data['page_action'] = "set_inv";
        $page_data['page_title'] = 'Inventaris Buku';
        
        $page_data['page_now'] = 'Buku & Inventarisasi';
        $page_data['page_name'] = 'manage_buku';
        $page_data['page_dir']    = 'manage_buku';
        $page_data['page_file']   = 'set_inv';
        $this->load->view('index', $page_data);
    }

    public function get_inv($isbn,$no_klas) {
        $x = urldecode(str_replace('_', '/', $isbn)); //ISBN (mengubah ISBN dengan pola xx_xx_xx_xx menjadi xx/xx/xx/xx)
        $y = urldecode(str_replace('%20', ' ', $no_klas)); //Noklas(mengubah no_klas dengan pola xx%20xx%20xx menjadi xx xx xx)
        $total = $this->Md_siperpus_inventaris->countByISBNandNoKlas($x,$y);
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
            $arr['nama_kampus'] = $row->nama_kampus !='' ? $row->nama_kampus : '-';
            $arr['nama_gedung'] = $row->nama_gedung !='' ? $row->nama_gedung : '-';
            $arr['nama_rak'] = $row->nama_rak !='' ? $row->nama_rak : '-';
            $arr['ket'] = $row->ket;
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

    public function get_barcode() {
        $output['barcode'] = $this->Md_siperpus_inventaris->getNoBarcode();
        $output['barcode']++;
        echo json_encode($output);
    }

    /* start fungsi untuk inventaris */
    public function get_data_inv($barcode) {
        $inv = $this->Md_siperpus_inventaris->getInventarisByBarcode($barcode);
        echo json_encode($inv);
    }

    public function tambah_inv() {
        $this->load->library('form_validation');

        // Validasi input
        $this->form_validation->set_rules('no_inv', 'No Inventaris', 'required|trim');
        $this->form_validation->set_rules('no_barcode', 'No Barcode', 'required|trim|max_length[20]');
        $this->form_validation->set_rules('tgl_inv', 'Tanggal Inventaris', 'required|callback_tgl_inv_valid');
        $this->form_validation->set_rules('asal', 'Asal Buku', 'required|trim');
        $this->form_validation->set_rules('no_klas', 'No Klas', 'required|trim');
        $this->form_validation->set_rules('isbn', 'ISBN', 'required|trim');

        $this->form_validation->set_message('required', '%s tidak boleh kosong');
        $this->form_validation->set_message('max_length', '%s maksimal 20 karakter');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status'  => 'error',
                'msg' => validation_errors()
            ]);
            exit;
        }

        $date = new DateTime();
        $data = [
            'no_barcode' => trim($this->input->post('no_barcode')),
            'no_inv'     => preg_replace('/^[\s\p{Zs}]+|[\s\p{Zs}]+$/u', '', $this->input->post('no_inv')),
            'tgl_inv'    => $this->input->post('tgl_inv'),
            'asal'       => $this->input->post('asal'),
            'ket'        => $this->input->post('ket') ?: '-',
            'lokasirak_id'        => $this->input->post('lokasirak_id') ?: null,
            'no_klas'    => str_replace('%20', ' ', $this->input->post('no_klas')),
            'isbn'       => str_replace('_', '/', $this->input->post('isbn')),
            'status'     => $this->input->post('status'),
            'tanggal'    => $date->format('Y-m-d H:i:s')
        ];

        
        // 1. Cek duplikat kombinasi no_barcode + no_inv
        if ($this->Md_siperpus_inventaris->is_duplicate_inv($data['no_inv'], $data['no_barcode'])) {
            echo json_encode([
                'status'  => 'error',
                'msg' => 'Kombinasi No Inventaris "' . $data['no_inv'] . '" dan No Barcode "' . $data['no_barcode'] . '" sudah ada di database.'
            ]);
            exit;
        }

        // 2. Cek no_barcode sendiri (unik, terpisah dari no_inv)
        if ($this->Md_siperpus_inventaris->is_barcode_exists($data['no_barcode'])) {
            echo json_encode([
                'status'  => 'error',
                'msg' => 'No Barcode "' . $data['no_barcode'] . '" sudah digunakan oleh inventaris lain.'
            ]);
            exit;
        }
        // Mulai transaksi
        $this->db->trans_start();

        // 3. Insert inventaris baru
        $this->Md_siperpus_inventaris->addInventaris($data);

        // 4. Update jumlah buku
        $row = $this->Md_siperpus_inventaris->getNumRowInvByNoKlasISBN($data['no_klas'], $data['isbn']);
        $this->Md_siperpus_data_buku->updateJmlBuku($data['isbn'], $data['no_klas'], $row);

        // 5. Insert log (dalam transaksi)
        $log = [
            'user_id'     => $this->session->userdata('idsys'),
            'jenis_log'   => 'Admin',
            'jenis_akses' => 'Add',
            'status'      => 1,
            'keterangan'  => $this->session->userdata('username') . ' Melakukan Add Inventori No. Inv. Buku ' . $data['no_inv'] . ' (Barcode: ' . $data['no_barcode'] . ')',
            'IP'          => $this->input->ip_address()
        ];
        $this->Md_log->addLog($log);

        // Final check transaksi
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode([
                'status'  => 'error',
                'msg' => 'Gagal menambahkan inventaris. Terjadi kesalahan sistem.'
            ]);
        } else {
            $this->db->trans_commit();
            echo json_encode([
                'status' => TRUE,
                'msg'    => 'Data Inventaris Berhasil Ditambah'
            ]);
        }
        exit;
    }
    
    public function hapus_inv($isbn_param, $no_klas_param, $no_barcode) {
        // Decode parameter
        $isbn = urldecode(str_replace('_', '/', $isbn_param)); // ISBN: xx_xx_xx_xx → xx/xx/xx/xx
        $no_klas = urldecode(str_replace('%20', ' ', $no_klas_param)); // no_klas: xx%20xx → xx xx

        // Langkah 1: Cari no_inv berdasarkan no_barcode (barcode unique)
        $inventaris = $this->Md_siperpus_inventaris->get_by_barcode($no_barcode);
        if (!$inventaris) {
            echo json_encode([
                'status'  => false,
                'msg'     => 'Data inventaris dengan barcode ' . $no_barcode . ' tidak ditemukan'
            ]);
            exit;
        }

        $no_inv = $inventaris->no_inv;

        // Langkah 2: Cek apakah no_inv sudah ada di transaksi peminjaman atau penyiangan_detail
        if ($this->Md_siperpus_inventaris->has_related_records($no_inv)) {
            echo json_encode([
                'status'  => false,
                'msg'     => 'Inventaris Buku dengan No. Inv ' . $no_inv . ' gagal dihapus karena telah memiliki data peminjaman atau penyiangan.'
            ]);
            exit;
        }
        
        // Mulai transaksi
        $this->db->trans_start();
        // Langkah 3: Hapus inventaris (hard delete)
        $deleted = $this->Md_siperpus_inventaris->hapusInventaris($no_barcode);

        // Langkah 4: Update jumlah buku di data buku
        $row = $this->Md_siperpus_inventaris->getNumRowInvByNoKlasISBN($no_klas, $isbn);
        $this->Md_siperpus_data_buku->updateJmlBuku($isbn, $no_klas, $row);

        // Langkah 5: Insert log
        $log = [
            'user_id'     => $this->session->userdata('idsys'),
            'jenis_log'   => 'Admin',
            'jenis_akses' => 'Delete',
            'status'      => 1,
            'keterangan'  => $this->session->userdata('username') . ' Melakukan Delete Buku No. Inv. ' . $no_inv . ' (Barcode: ' . $no_barcode . ')',
            'IP'          => $this->input->ip_address()
        ];
        $this->Md_log->addLog($log);

        // Final check transaksi
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode([
                'status' => false,
                'msg'    => 'Gagal menghapus inventaris. Terjadi kesalahan sistem.'
            ]);
        } else {
            $this->db->trans_commit();
            echo json_encode([
                'status' => true,
                'msg'    => 'Data Inventaris Sukses Dihapus'
            ]);
        }
        exit;
    }
    
    public function edit_inv() {
        $this->load->library('form_validation');

        // Validasi input
        $this->form_validation->set_rules('no_inv_exist', 'No Inventaris Exist', 'required|trim');
        $this->form_validation->set_rules('no_inv2', 'No Inventaris', 'required|trim');
        $this->form_validation->set_rules('no_barcode2', 'No Barcode', 'required|trim|max_length[20]');
        $this->form_validation->set_rules('tgl_inv2', 'Tanggal Inventaris', 'required|callback_tgl_inv_valid');
        $this->form_validation->set_rules('asal2', 'Asal Buku', 'required|trim');
        $this->form_validation->set_rules('no_klas2', 'No Klas', 'required|trim');
        $this->form_validation->set_rules('isbn2', 'ISBN', 'required|trim');

        $this->form_validation->set_message('required', '%s tidak boleh kosong');
        $this->form_validation->set_message('max_length', '%s maksimal 20 karakter');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status' => 'error',
                'msg' => validation_errors()
            ]);
            exit;
        }

        $date = new DateTime();
        $data = [
            'no_barcode' => trim($this->input->post('no_barcode2')),
            'no_inv' => preg_replace('/^[\s\p{Zs}]+|[\s\p{Zs}]+$/u', '', $this->input->post('no_inv2')),
            'tgl_inv' => $this->input->post('tgl_inv2'),
            'asal' => $this->input->post('asal2'),
            'lokasirak_id'        => $this->input->post('lokasirak_id2') ?: null,
            'ket' => $this->input->post('ket2') ?: '-',
            'no_klas' => str_replace('%20', ' ', $this->input->post('no_klas2')),
            'isbn' => str_replace('_', '/', $this->input->post('isbn2')),
            'status' => $this->input->post('status2'),
            'tanggal' => $date->format('Y-m-d H:i:s')
        ];

        $no_inv = preg_replace('/^[\s\p{Zs}]+|[\s\p{Zs}]+$/u', '', $this->input->post('no_inv_exist')); // no_inv yang sedang di-edit (PK)
        $no_barcode = $data['no_barcode'];

        

        // 1. Cek kombinasi no_barcode + no_inv sudah ada atau belum (kecualikan dirinya sendiri)
        if ($this->Md_siperpus_inventaris->is_duplicate_inv_edit($no_inv,$data['no_inv'], $no_barcode)) {
            echo json_encode([
                'status' => 'error',
                'msg' => 'Kombinasi No Inventaris "' . $no_inv . '" dan No Barcode "' . $no_barcode . '" sudah ada di inventaris lain.'
            ]);
            exit;
        }

        // 2. Cek no_barcode sendiri (unik, kecualikan dirinya sendiri)
        if ($this->Md_siperpus_inventaris->is_barcode_exists_edit($no_inv, $no_barcode)) {
            echo json_encode([
                'status' => 'error',
                'msg' => 'No Barcode "' . $no_barcode . '" sudah digunakan oleh inventaris lain.'
            ]);
            exit;
        }
        
        // 3. Jika no_inv diubah cek apakah no_inv sudah ada di transaksi peminjaman atau penyiangan_detail
        if($no_inv != $data['no_inv']){
            if ($this->Md_siperpus_inventaris->has_related_records($no_inv)) {
                echo json_encode([
                    'status'  => false,
                    'msg'     => 'Inventaris Buku dengan No. Inv ' . $no_inv . ' gagal diubah karena telah memiliki data peminjaman atau penyiangan.'
                ]);
                exit;
            }
        }
        
        // Mulai transaksi
        $this->db->trans_start();
        
        // 4. Update inventaris
        $this->Md_siperpus_inventaris->updateInventaris($data);

        // 5. Update jumlah buku (jika no_klas atau isbn berubah, hitung ulang)
        $row = $this->Md_siperpus_inventaris->getNumRowInvByNoKlasISBN($data['no_klas'], $data['isbn']);
        $this->Md_siperpus_data_buku->updateJmlBuku($data['isbn'], $data['no_klas'], $row);

        // 6. Insert log (dalam transaksi)
        $log = [
            'user_id' => $this->session->userdata('idsys'),
            'jenis_log' => 'Admin',
            'jenis_akses' => 'Edit',
            'status' => 1,
            'keterangan' => $this->session->userdata('username') . ' Melakukan Edit Inventori No.Inv. Buku ' . $no_inv . ' (Barcode: ' . $no_barcode . ')',
            'IP' => $this->input->ip_address()
        ];
        $this->Md_log->addLog($log);

        // Final check transaksi
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode([
                'status' => 'error',
                'msg' => 'Gagal mengubah inventaris. Terjadi kesalahan sistem.'
            ]);
        } else {
            $this->db->trans_commit();
            echo json_encode([
                'status' => TRUE,
                'msg' => 'Data Inventaris Berhasil Diubah'
            ]);
        }
        exit;
    }

    public function tgl_inv_valid($tgl) {
        if (empty($tgl)) {
           $this->form_validation->set_message('tgl_inv_valid', 'Tanggal Inventaris tidak boleh kosong');
           return FALSE;
        }    

        $date = DateTime::createFromFormat('Y-m-d', $tgl);
        if (!$date) {
           $this->form_validation->set_message('tgl_inv_valid', 'Format tanggal tidak valid (harus YYYY-MM-DD)');
           return FALSE;
        }
        // Strip waktu, bandingkan hanya tanggal
        // Set kedua tanggal ke pukul 00:00:00 agar hanya bandingkan tanggal saja
        $date->setTime(0, 0, 0);

        $today = new DateTime();
        $today->setTime(0, 0, 0);  // hari ini pukul 00:00:00

        $min_date = new DateTime('2000-01-01');
        $min_date->setTime(0, 0, 0);
        
        if ($date > $today) {
           $this->form_validation->set_message('tgl_inv_valid', 'Tanggal Inventaris tidak boleh di masa depan');
           return FALSE;
        }

        if ($date < $min_date) {
           $this->form_validation->set_message('tgl_inv_valid', 'Tanggal Inventaris tidak boleh sebelum tahun 2000');
           return FALSE;
        }

        return TRUE;
    }
    /* end fungsi untuk inventaris */

    public function ubah_rak() {
        $page_data['klasifikasi'] = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
        $page_data['kategori'] = $this->Md_siperpus_kategori_buku->getKategoriBukuAll();
        $page_data['kampus'] = $this->Md_lokasi->get_kampus_options();
        $page_data['page_action'] = 'ubah_rak';
        $page_data['page_title'] = 'Ubah Rak Buku';
        
        $page_data['page_now'] = 'Buku & Inventarisasi';
        $page_data['page_name'] = 'manage_buku';
        $page_data['page_dir']    = 'manage_buku';
        $page_data['page_file']   = 'ubah_rak';
        $this->load->view('index', $page_data);
    }
    
    public function list_ubah_rak() {
        $datatable = $this->input->post('datatable');
        $search = isset($datatable['query']['generalSearch']) ? $datatable['query']['generalSearch'] : '';
        $page = isset($datatable['pagination']['page']) ? (int) $datatable['pagination']['page'] : 1;
        $perpage = isset($datatable['pagination']['perpage']) ? (int) $datatable['pagination']['perpage'] : 10;
        $sort = isset($datatable['sort']['sort']) ? $datatable['sort']['sort'] : 'desc';
        $field = isset($datatable['sort']['field']) ? $datatable['sort']['field'] : 'i.no_barcode';

        $offset = ($page - 1) * $perpage;

        $list = $this->Md_siperpus_inventaris->get_inventaris_for_ubah_rak($search, $perpage, $offset, $field, $sort);
        $total = $this->Md_siperpus_inventaris->count_inventaris_for_ubah_rak($search);

        $data = [];
        $no = $offset;
        foreach ($list as $row) {
            $no++;
            $data[] = [
                //'id'                => [$row->no_klas, $row->ISBN], // Langsung buat array di sini
                'id'                => $row->no_inv,
                'number'            => $no,
                'isbn'              => $row->ISBN,
                'no_klas'           => $row->no_klas,
                'no_inv'            => $row->no_inv,
                'no_barcode'        => $row->no_barcode,
                'current_rak_id'    => $row->current_rak_id,
                'judul'             => $row->judul,
                'penulis'           => $row->penulis,
                'no_rak'            => $row->nama_rak !='' ? $row->nama_rak : ($row->no_rak!='' ? $row->no_rak :'-'),
                'nama_kampus'       => $row->nama_kampus!='' ? $row->nama_kampus : '-'
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

        echo json_encode($result);
        exit;
    }

    public function get_lokasi_rak_options() {
        $options = $this->Md_siperpus_inventaris->get_lokasi_rak_options();
        echo json_encode($options);
        exit;
    }

    public function simpan_ubah_rak() {
        // Ambil data dari POST (array pasangan dari JS)
        $data = $this->input->post('data');

        if (empty($data) || !is_array($data)) {
            echo json_encode([
                'status'  => false,
                'message' => 'Tidak ada data yang dikirim atau format salah.'
            ]);
            exit;
        }

        // Mulai transaksi
        $this->db->trans_start();

        $success_count = 0;
        $updated_nos   = []; // untuk log keterangan

        foreach ($data as $item) {
            if (count($item) !== 2) continue; // skip jika format salah

            $no_inv       = trim($item[0]);
            $lokasirak_id = trim($item[1]);

            if (empty($no_inv) || empty($lokasirak_id)) continue;

            
            $updated = $this->Md_siperpus_inventaris->update_lokasirak($no_inv, $lokasirak_id);

            if ($updated) {
                $success_count++;
                $updated_nos[] = $no_inv;

                // Log per row (per no_inv)
                $log = [
                    'user_id'     => $this->session->userdata('idsys'),
                    'jenis_log'   => 'Admin',
                    'jenis_akses' => 'Edit',
                    'status'      => 1,
                    'keterangan'  => $this->session->userdata('username') . ' Melakukan Ubah Rak Buku ' . $no_inv,
                    'IP'          => $this->input->ip_address()
                ];
                $this->Md_log->addLog($log);
            }
        }

        // Final check transaksi
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode([
                'status'  => false,
                'message' => 'Gagal menyimpan perubahan rak. Terjadi kesalahan sistem.'
            ]);
        } else {
            $this->db->trans_commit();

            $message = $success_count > 0 
                ? "Rak Buku Berhasil Diedit ($success_count perubahan)" 
                : "Tidak ada perubahan yang berhasil disimpan";

            echo json_encode([
                'status'  => true,
                'message' => $message
            ]);
        }
        exit;
    }

    public function cetak_katalog() {
        $data = array();
        $data_final = $this->input->post('final');
        for ($i = 0; $i < sizeof($data_final); $i++) {
            $data[] = $this->Md_siperpus_data_buku->getDataKatalog($data_final[$i][0], $data_final[$i][1]);
        }
        echo json_encode($data);
    }
    
    public function cetak_katalog_inventaris() {
        $data = array();
        $data_final = $this->input->post('final');
       
        $data = $this->Md_siperpus_data_buku->getDataKatalogByBarcodes($data_final);
        
        echo json_encode($data);
    }

    public function cetak_callnumber() {
        $data = array();
        $data_final = $this->input->post('final');
        for ($i = 0; $i < sizeof($data_final); $i++) {
            $data[] = $this->Md_siperpus_data_buku->getDataCallNumber($data_final[$i]);
        }
        echo json_encode($data);
    }
    
    public function cetak_callnumber_buku() {
        $data = array();
        $output = array();
        $data_final = $this->input->post('final');

        if (!empty($data_final)) {
            for ($i = 0; $i < sizeof($data_final); $i++) {
                // Ambil data dari DB (Hasilnya bisa multiple rows)
                $res = $this->Md_siperpus_inventaris_one->getDataCallNumberByNoKlasISBN($data_final[$i][0], $data_final[$i][1]);

                if (!empty($res)) {
                    // Tambahkan perulangan foreach untuk memproses SEMUA baris dalam $res
                    foreach ($res as $item) {

                        // 1. Ambil angka setelah garis miring untuk no_inv (misal: 103184/5 jadi 5)
                        $x = explode("/", $item['no_inv']);
                        $no_inv_suffix = end($x);

                        // Simpan hasil explode kembali ke item
                        $item['no_inv'] = $no_inv_suffix;

                        // Jika ada angka (tidak kosong) setelah tanda /, masukkan ke output
                        if ($item['no_inv'] !== "") {
                            $output[] = $item;
                        }
                    }
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode($output);
    }
    
    public function cetak_callnumber_inventaris() {
        $data = array();
        $output = array();
        $data_final = $this->input->post('final');

        if (!empty($data_final)) {
            for ($i = 0; $i < sizeof($data_final); $i++) {
                // Ambil data dari DB
                $res = $this->Md_siperpus_inventaris_one->getDataCallNumberByNoInv($data_final[$i]);

                if (!empty($res)) {
                    $item = $res[0]; // Ambil baris pertama

                    // 1. Ambil angka setelah garis miring untuk no_inv (misal: 103184/5 jadi 5)
                    $x = explode("/", $item['no_inv']);
                    $item['no_inv'] = end($x);

                    // 2. Ambil angka SEBELUM garis miring dari PAYLOAD untuk pengecekan barcode
                    // Karena payload "103184/5" tapi barcode di DB "103184"
                    $y = explode("/", $data_final[$i]);
                    $barcode_dari_payload = $y[0]; 

                    // 3. Bandingkan barcode murni, hanya ambil yang format barcodenya benar
                    if ($item['no_barcode'] == $barcode_dari_payload) {
                        $output[] = $item;
                    }
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode($output);
    }

    public function cetak_barcode() {
        $data = array();
        $data_final = $this->input->post('final');
        for ($i = 0; $i < sizeof($data_final); $i++) {
            $data[] = $this->Md_siperpus_data_buku->getBarcode($data_final[$i][0], $data_final[$i][1]);
        }
        echo json_encode($data);
    }
    
    public function cetak_barcode_inventaris() {
        $data = array();
        $data_final = $this->input->post('final');       
        $data = $this->Md_siperpus_inventaris_one->getDataByBarcodes($data_final);        
        echo json_encode($data);
                
    }

    public function getpenerbit() {
        $data = $this->Md_siperpus_penerbit->getPenerbitAll();
        echo json_encode($data);
    }

    public function getfile($isbn,$no_klas) {
        $x = urldecode(str_replace('_', '/', $isbn)); //ISBN (mengubah ISBN dengan pola xx_xx_xx_xx menjadi xx/xx/xx/xx)
        $y = urldecode(str_replace('%20', ' ', $no_klas)); //Noklas(mengubah no_klas dengan pola xx%20xx%20xx menjadi xx xx xx)
        $data = $this->Md_siperpus_data_buku->getDataFile($x, $y);
        echo json_encode($data);
    }

    public function setfilestatus($file_id) {
        // echo $param2;
        $data = $this->Md_siperpus_buku_file->setFileStatus($file_id);
        // var_dump($data);
        echo json_encode($data);
    }
    
}
