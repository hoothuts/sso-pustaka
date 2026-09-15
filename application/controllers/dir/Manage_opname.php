<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_opname extends CI_Controller {
    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->database();
        $this->load->model('Md_opname');
        $this->load->model('Md_log');
        $this->load->model('Md_lokasi');
        $this->load->helper('pkrlib_helper');
        $this->load->helper('encryption_id_helper');
        if ($this->session->userdata('login_type') != 'admin') {
            logoutNow();
        }
    }

    public function index() {
        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'Transaksi';
        $page_data['page_title'] = 'Manage Opname';
        $page_data['page_name'] = 'manage_opname';
        $page_data['page_dir'] = 'manage_opname';
        $page_data['page_file'] = 'index';
        $this->load->view('index', $page_data);
    }

    public function fetch() {
        $datatable = $this->input->post('datatable');
        $search = isset($datatable['query']['generalSearch']) ? $datatable['query']['generalSearch'] : '';
        $page = isset($datatable['pagination']['page']) ? (int)$datatable['pagination']['page'] : 1;
        $perpage = isset($datatable['pagination']['perpage']) ? (int)$datatable['pagination']['perpage'] : 10;
        $sort = isset($datatable['sort']['sort']) ? $datatable['sort']['sort'] : 'DESC';
        $field = isset($datatable['sort']['field']) ? $datatable['sort']['field'] : 'opname_id';
        $offset = ($page - 1) * $perpage;

        $list = $this->Md_opname->get_datatables($search, $perpage, $offset, $field, $sort);
        $total = $this->Md_opname->count_filtered($search);

        $data = [];
        $no = $offset;
        foreach ($list as $row) {
            $no++;
            $data[] = [
                'number' => $no,
                'id_enc' => urlencode(base64_encode($row->opname_id)),
                'id' => $row->opname_id,
                'no_ba' => $row->no_ba,
                'tgl_opname' => $row->tgl_opname ? date('d M Y', strtotime($row->tgl_opname)) : '-',
                'file_ba' => $row->file_ba,
                'jumlah_buku' => $row->jumlah_buku,
                'lokasi' => $row->nama_kampus ? $row->nama_kampus : '-',
                'catatan' => $row->catatan ? (strlen($row->catatan) > 50 ? substr($row->catatan, 0, 50) . '...' : $row->catatan) : '-',
                'author' => $row->author,
                'tgl_post' => $row->tgl_post ? date('d M Y H:i:s', strtotime($row->tgl_post)) : '-',
                'status' => $row->status
            ];
        }

        $output = [
            "meta" => [
                "page" => $page,
                "pages" => ceil($total / $perpage),
                "perpage" => $perpage,
                "total" => $total,
                "sort" => $sort,
                "field" => $field
            ],
            "data" => $data
        ];
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function form($mode = 'add', $id_enc = null) {
        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'Transaksi';
        $page_data['page_title'] = 'Opname';
        $page_data['is_view_mode'] = false;

        if ($mode == 'edit' || $mode == 'view') {
            $id = base64_decode(urldecode($id_enc));
            if ($id === null || $id === '') show_404();
            
            $page_data['edit_data'] = $this->Md_opname->get_by_id($id);
            $page_data['detail_data'] = $this->Md_opname->get_detail_by_opname($id);
            
            if (!$page_data['edit_data']) show_404();

            if ($mode == 'view') {
                $page_data['is_view_mode'] = true;
            }
        }
        
        $page_data['kampus'] = $this->Md_lokasi->get_kampus_options();
        $page_data['page_file'] = 'form';
        $page_data['page_name'] = 'manage_opname';
        $page_data['page_dir'] = 'manage_opname';
        $this->load->view('index', $page_data);
    }

    public function save() {
        $this->load->library('form_validation');
        //$this->form_validation->set_rules('no_ba', 'No BA', 'required|trim|max_length[50]');
        $this->form_validation->set_rules('tgl_opname', 'Tanggal Opname', 'required');
        $this->form_validation->set_rules('catatan', 'Catatan', 'trim');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');
        $this->form_validation->set_message('max_length', '%s maksimal 50 karakter');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status' => 'error',
                'message' => validation_errors('<p class="text-danger">', '</p>')
            ]);
            exit;
        }

        $id_enc = $this->input->post('id_for_edit');
        $is_update = !empty($id_enc);
        //$no_ba = $this->input->post('no_ba');
        $tgl_opname = $this->input->post('tgl_opname');
        $catatan = $this->input->post('catatan');
        $lokasikampus_id = $this->input->post('lokasikampus_id');
        $no_inv = $this->input->post('no_inv');
        //$no_barcode = $this->input->post('no_barcode');
        $keterangan = $this->input->post('keterangan');


        // ====================== CEK DUPLIKAT NO_BA ======================
        /*if ($is_update) {
            $id = base64_decode(urldecode($id_enc));
            if ($id === false || $id === '' || $id === null) {
                echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
                exit;
            }
            $existing = $this->Md_opname->check_duplicate_no_ba($no_ba, $id);
        } else {
            $existing = $this->Md_opname->check_duplicate_no_ba($no_ba);
        }

        if ($existing) {
            echo json_encode([
                'status' => 'error',
                'message' => 'No BA "' . $no_ba . '" sudah digunakan oleh data lain. Silakan gunakan No BA yang berbeda.'
            ]);
            exit;
        }*/
        // ================================================================
        
        // ================= Cek Minimal 1 data inventaris ================
        if (empty($no_inv)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Minimal 1 data inventaris harus ditambahkan'
            ]);
            exit;
        }

        $data = [
            //'no_ba' => $no_ba,
            'tgl_opname' => $tgl_opname,
            'lokasikampus_id' => $lokasikampus_id,
            'catatan' => $catatan
           
        ];

        // === UPLOAD FILE (sebelum transaksi) ===
        /*if (!$is_update && empty($_FILES['file_ba']['name'])) {
            echo json_encode(['status' => 'error', 'message' => 'File Berita Acara (PDF) wajib diupload']);
            exit;
        }

        if (!empty($_FILES['file_ba']['name'])) {
            $upload_path = './uploads/berita_acara/';
            if (!is_dir($upload_path))
                mkdir($upload_path, 0777, true);

            $config['upload_path'] = $upload_path;
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = 5120;
            $config['file_name'] = 'opname_' . date('YmdHis') . '_' . rand(1000, 9999);

            $this->load->library('upload');
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('file_ba')) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal upload file: ' . $this->upload->display_errors('', '')
                ]);
                exit;
            }
            $data['file_ba'] = $this->upload->data('file_name');
        }
        */
        
        // === TRANSAKSI + LOG ===
        $this->db->trans_start();

        if ($is_update) {
            $id = base64_decode(urldecode($id_enc));
            $this->Md_opname->update($id, $data);
            //hapus opname detail lama
            $this->Md_opname->soft_delete_detail_by_opname($id);
            
            if (!empty($no_inv)) {
                $data_detail = [];

                foreach ($no_inv as $i => $inv) {
                    $data_detail[] = [
                        'opname_id' => $id,
                        'no_inv' => $inv,
                        'keterangan' => $keterangan[$i] ?? null,
                        'status' => 1,
                    ];
                }

                $this->Md_opname->insert_detail_batch($data_detail);
            }
            
            $log_ket = $this->session->userdata('username') . ' Melakukan Update Opname Opname ID ' . $id;
            $jenis_akses = 'Update';
        } else {
            $data['no_ba'] = $this->Md_opname->generate_no_opname();
            $data['author'] = $this->session->userdata('idsys');
            $data['status'] = 1;
            $opname_id =$this->Md_opname->insert($data);
            
            if (!empty($no_inv)) {
                $data_detail = [];

                foreach ($no_inv as $i => $inv) {
                    $data_detail[] = [
                        'opname_id' => $opname_id,
                        'no_inv' => $inv,
                        'keterangan' => $keterangan[$i] ?? null,
                        'status' => 1,
                    ];
                }

                $this->Md_opname->insert_detail_batch($data_detail);
            }

            $log_ket = $this->session->userdata('username') . ' Melakukan Add Opname No BA ' . $data['no_ba'];
            $jenis_akses = 'Add';
        }

        $log = [
            'user_id' => $this->session->userdata('idsys'),
            'jenis_log' => 'Admin',
            'jenis_akses' => $jenis_akses,
            'status' => 1,
            'keterangan' => $log_ket,
            'IP' => $this->input->ip_address()
        ];
        $this->Md_log->addLog($log);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data.']);
        } else {
            $this->db->trans_commit();
            $msg = $is_update ? 'Opname berhasil diperbarui' : 'Opname berhasil ditambahkan';
            echo json_encode(['status' => 'success', 'message' => $msg]);
        }
        exit;
    }

    public function delete() {
        $id_enc = $this->input->post('id');
        $id = base64_decode(urldecode($id_enc));
        if ($id === false || $id === '' || $id === null) {
            echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
            exit;
        }

        $this->db->trans_start();
        $this->Md_opname->soft_delete($id);

        $opname = $this->Md_opname->get_by_id($id);
        if (!$opname) {
            $this->db->trans_rollback();
            echo json_encode(['status' => 'error', 'message' => 'Opname tidak ditemukan']);
            exit;
        }

        $log = [
            'user_id' => $this->session->userdata('idsys'),
            'jenis_log' => 'Admin',
            'jenis_akses' => 'Hapus',
            'status' => 1,
            'keterangan' => $this->session->userdata('username') . ' Melakukan Hapus Opname No BA ' . $opname->no_ba,
            'IP' => $this->input->ip_address()
        ];
        $this->Md_log->addLog($log);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menghapus opname. Terjadi kesalahan sistem.'
            ]);
        } else {
            $this->db->trans_commit();
            echo json_encode([
                'status' => 'success',
                'message' => 'Opname berhasil dihapus (soft delete)'
            ]);
        }
        exit;
    }
    
    public function get_barcode() {
        $barcode = $this->input->post('barcode');

        $this->load->model('Md_siperpus_inventaris_one');

        $data = $this->Md_siperpus_inventaris_one->get_inventaris_by_barcode($barcode);

        if ($data) {
            echo json_encode([
                'status' => 'success',
                'data' => $data
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Barcode tidak ditemukan'
            ]);
        }
    }
    
    public function export_excel($id_enc) {
        $id = base64_decode(urldecode($id_enc));

        $this->load->model('Md_opname');

        $header   = $this->Md_opname->get_by_id($id);
        $detail   = $this->Md_opname->get_detail_grouped($id);
        $total    = $this->Md_opname->get_total_saat_ini($id);
        $selisih  = $this->Md_opname->get_selisih_barcode($id);

        if (!$header) {
            show_404();
        }

        // ===========================
        // Mapping jumlah eksemplar saat ini
        // ===========================
        $mapTotal = [];
        foreach ($total as $t) {
            $key = $t->no_klas . '_' . $t->ISBN;
            $mapTotal[$key] = $t->total_saat_ini;
        }

        // ===========================
        // Mapping barcode selisih
        // ===========================
        $mapSelisih = [];
        foreach ($selisih as $s) {
            $key = $s->no_klas . '_' . $s->ISBN;
            $mapSelisih[$key] = $s;
        }

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Opname_" . $header->no_ba . ".xls");

        echo "<table border='1'>";

        echo "<tr><th colspan='11'>DATA OPNAME</th></tr>";
        echo "<tr><td>No BA</td><td colspan='10' align='left'>{$header->no_ba}</td></tr>";
        echo "<tr><td>Tanggal</td><td colspan='10' align='left'>{$header->tgl_opname}</td></tr>";
        echo "<tr><td>Lokasi Kampus</td><td colspan='10' align='left'>{$header->nama_kampus}</td></tr>";
        echo "<tr><td>Catatan</td><td colspan='10' align='left'>{$header->catatan}</td></tr>";

        echo "<tr>
                <th>No</th>
                <th>Judul Buku</th>
                <th>NO Klas</th>
                <th>ISBN</th>
                <th>Eksemplar (Opname)</th>
                <th>Eksemplar Saat Ini<br>Pada Lokasi Opname</th>
                <th>Selisih Dengan Sistem</th>
                <th>No Barcode Selisih Dengan Sistem</th>
                <th>No Inv</th>
                <th>No Barcode</th>
                <th>Lokasi Rak</th>
              </tr>";

        $no = 1;

        foreach ($detail as $d) {

            $key = $d->no_klas . '_' . $d->ISBN;

            $totalSaatIni = $mapTotal[$key] ?? 0;

            $selisihQty = 0;
            $barcodeSelisih = '';

            if (isset($mapSelisih[$key])) {
                $selisihQty      = $mapSelisih[$key]->total_selisih;
                $barcodeSelisih  = $mapSelisih[$key]->barcode_selisih;
            }

            echo "<tr>
                    <td align='center'>{$no}</td>
                    <td>{$d->judul}</td>
                    <td>{$d->no_klas}</td>
                    <td style='mso-number-format:\"\\@\"'>{$d->ISBN}</td>
                    <td align='center'>{$d->total}</td>
                    <td align='center'>{$totalSaatIni}</td>
                    <td align='center'>{$selisihQty}</td>
                    <td>{$barcodeSelisih}</td>
                    <td>{$d->no_inv}</td>
                    <td style='mso-number-format:\"\\@\"'>{$d->no_barcode}</td>
                    <td>{$d->lokasi_rak}</td>
                  </tr>";

            $no++;
        }

        echo "</table>";
    }

    public function export_excel_raw($id_enc) {
        $id = base64_decode(urldecode($id_enc));

        $this->load->model('Md_opname');

        $header = $this->Md_opname->get_by_id($id);
        $detail = $this->Md_opname->get_detail_raw($id);

        if (!$header) show_404();

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Opname_Detail_" . $header->no_ba . ".xls");

        echo "<table border='1'>";

        // HEADER
        echo "<tr><th colspan='9'>DATA OPNAME (DETAIL)</th></tr>";
        echo "<tr><td>No BA</td><td colspan='8'>{$header->no_ba}</td></tr>";
        echo "<tr><td>Tanggal</td><td colspan='8' align='left'>{$header->tgl_opname}</td></tr>";
        echo "<tr><td>Lokasi Kampus</td><td colspan='8'>{$header->nama_kampus}</td></tr>";
        echo "<tr><td>Catatan</td><td colspan='8'>{$header->catatan}</td></tr>";

        echo "<tr>
            <th>No</th>
            <th>Tgl Pendataan</th>
            <th>Judul Buku</th>
            <th>No Klas</th>
            <th>ISBN</th>
            <th>No Inv</th>
            <th>No Barcode</th>
            <th>Lokasi Rak</th>
            <th>Keterangan</th>
        </tr>";

        $no = 1;
        foreach ($detail as $d) {
            $tgl = date('d-M Y', strtotime($d->tgl_post)); // Hasil: 31-Jul 2026
            echo "<tr>
                <td align='center'>{$no}</td>
                <td align='left'>{$tgl}</td>
                <td align='left'>{$d->judul}</td>
                <td align='left'>{$d->no_klas}</td>
                <td align='left'>{$d->ISBN}</td>
                <td align='left'>{$d->no_inv}</td>
                <td align='left'>{$d->no_barcode}</td>
                <td align='left'>{$d->nama_rak}</td>
                <td align='left'>{$d->keterangan}</td>
            </tr>";
            $no++;
        }

        echo "</table>";
    }

}