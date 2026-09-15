<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Manage_penyiangan extends CI_Controller {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        
        $this->load->database();
        $this->load->model('Md_penyiangan');
        $this->load->model('Md_log');
        $this->load->helper('pkrlib_helper');
        $this->load->helper('encryption_id_helper');
       
        if ($this->session->userdata('login_type') != 'admin'){
            logoutNow();
        }
    }

    public function index() {
        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'Transaksi';
        $page_data['page_title'] = 'Penyiangan Koleksi Buku';
        $page_data['page_name'] = 'manage_penyiangan';
        $page_data['page_dir'] = 'manage_penyiangan';
        $page_data['page_file'] = 'index';
        
        $this->load->view('index', $page_data);
    }

    public function fetch() {
        $datatable = $this->input->post('datatable');
        $search = isset($datatable['query']['generalSearch']) ? $datatable['query']['generalSearch'] : '';
        $page = isset($datatable['pagination']['page']) ? (int) $datatable['pagination']['page'] : 1;
        $perpage = isset($datatable['pagination']['perpage']) ? (int) $datatable['pagination']['perpage'] : 10;
        $sort = isset($datatable['sort']['sort']) ? $datatable['sort']['sort'] : 'DESC';
        $field = isset($datatable['sort']['field']) ? $datatable['sort']['field'] : 'tgl_penyiangan';

        // Mapping sort field
        $map_field = [
            'tgl' => 'p.tgl_penyiangan',
            'no_dokumen' => 'p.no_dokumen',
            'author' => 'p.author'
        ];
        $sort_field = isset($map_field[$field]) ? $map_field[$field] : 'p.tgl_penyiangan';
        $offset = ($page - 1) * $perpage;

        $list = $this->Md_penyiangan->get_penyiangan_server_side($search, $perpage, $offset, $sort_field, $sort);
        $total = $this->Md_penyiangan->count_filtered($search);

        $data = array();
        $no = $offset;
        foreach ($list as $row) {
            $no++;
            $jumlah_buku = $this->Md_penyiangan->count_details($row->penyiangan_id); // Hitung jumlah detail
            $data[] = [
                'number' => $no,
                'id' => encrypt($row->penyiangan_id),
                'no_dokumen' => $row->no_dokumen,
                'tgl' => date('d/M/Y', strtotime($row->tgl_penyiangan)),
                'tgl_post' => date('d/M/Y', strtotime($row->tgl_post)),
                'catatan' => substr($row->catatan, 0, 100) . '...',
                'jumlah_buku' => $jumlah_buku,
                'author' => $row->author,
                'has_penghapusan' => $row->has_penghapusan > 0 ? 1 : 0,
            ];
        }

        $result = [
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

        ob_clean();
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }
    
    public function search_inventaris_ajax() {
        $text = $this->input->get('searchtext');
        $res = $this->Md_penyiangan->search_inventaris($text);
        $final = [];
        foreach ($res as $r) {
            $final[] = ['id' => $r['no_inv'], 'text' => '[' . $r['no_inv'] . '] ' . $r['text']];
        }
        echo json_encode(['items' => $final]);
        exit;
    }
    
    public function save() {
        $this->load->library('form_validation');

        // Validasi dasar
        $this->form_validation->set_rules('tgl_penyiangan', 'Tanggal Penyiangan', 'required');
        $this->form_validation->set_message('required', '%s tidak boleh kosong');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status' => 'error',
                'message' => validation_errors('<p class="text-danger">', '</p>')
            ]);
            exit;
        }

        $penyiangan_id_enc = $this->input->post('penyiangan_id');
        $author = $this->session->userdata('idsys');
        $tgl_penyiangan = $this->input->post('tgl_penyiangan');

        $data_header = [
            'tgl_penyiangan' => $tgl_penyiangan,
            'catatan' => $this->input->post('catatan')
        ];

        // --- Ambil dan validasi detail ---
        $no_inv_array = $this->input->post('no_inv') ?? [];
        $status_buku_array = $this->input->post('status_buku') ?? [];

        $new_details = [];
        $no_inv_seen = [];
        foreach ($no_inv_array as $i => $no_inv_raw) {
            $no_inv = trim($no_inv_raw);
            if (!empty($no_inv)) {
                if (in_array($no_inv, $no_inv_seen)) {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'No Inventaris "' . $no_inv . '" tidak boleh diinput lebih dari sekali dalam dokumen yang sama.'
                    ]);
                    exit;
                }
                $no_inv_seen[] = $no_inv;
                $new_details[$no_inv] = $status_buku_array[$i] ?? 'Rusak Ringan';
            }
        }

        if (empty($new_details)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Minimal satu buku harus ditambahkan dalam penyiangan.'
            ]);
            exit;
        }

        // Mulai transaksi
        $this->db->trans_start();

        $is_update = !empty($penyiangan_id_enc);
        $current_id = null;
        $no_dokumen = ''; // Inisialisasi untuk log

        if ($is_update) {
            // UPDATE
            $current_id = decrypt($penyiangan_id_enc);
            if (!$current_id) {
                $this->db->trans_rollback();
                echo json_encode(['status' => 'error', 'message' => 'ID penyiangan tidak valid']);
                exit;
            }

            $this->Md_penyiangan->update_penyiangan($current_id, $data_header);

            // Ambil no_dokumen existing untuk log
            $existing_header = $this->Md_penyiangan->get_penyiangan_by_id($current_id);
            $no_dokumen = $existing_header->no_dokumen ?? 'Tidak Diketahui';

            // Ambil existing details
            $existing_details = $this->Md_penyiangan->get_active_details($current_id);
            $existing_map = [];
            foreach ($existing_details as $row) {
                $existing_map[$row['no_inv']] = [
                    'id' => $row['penyiangandetail_id'],
                    'status_buku' => $row['status_buku']
                ];
            }

            // Sinkronisasi detail
            foreach ($new_details as $no_inv => $status_buku_baru) {
                if (isset($existing_map[$no_inv])) {
                    if ($existing_map[$no_inv]['status_buku'] !== $status_buku_baru) {
                        $this->Md_penyiangan->update_detail_status(
                                $existing_map[$no_inv]['id'],
                                $status_buku_baru
                        );
                    }
                    unset($existing_map[$no_inv]);
                } else {
                    $data_detail = [
                        'penyiangan_id' => $current_id,
                        'no_inv' => $no_inv,
                        'status_buku' => $status_buku_baru,
                        'author' => $author,
                        'status' => 1
                    ];
                    $this->Md_penyiangan->add_penyiangan_detail($data_detail);
                }
            }

            // Soft delete yang dihapus dari form
            foreach ($existing_map as $info) {
                $this->Md_penyiangan->soft_delete_detail($info['id']);
            }

            $message = 'Penyiangan berhasil diperbarui';
            $log_ket = $this->session->userdata('username') . ' Melakukan Update Penyiangan Buku No Dokumen: ' . $no_dokumen;
        } else {
            // INSERT BARU
            $yy = date('y');
            $mm = date('m');
            $seq = $this->Md_penyiangan->get_next_sequence($yy);
            if ($seq > 999) {
                $this->db->trans_rollback();
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Batas maksimal penyiangan per tahun (999) telah tercapai.'
                ]);
                exit;
            }
            $nnn = sprintf("%03d", $seq);
            $data_header['no_dokumen'] = $yy . '/PG/' . $mm . '/' . $nnn;            
            $data_header['author'] = $author;
            $data_header['status'] = 1;

            $this->Md_penyiangan->add_penyiangan($data_header);
            $current_id = $this->db->insert_id();

            // Insert detail baru
            foreach ($new_details as $no_inv => $status_buku) {
                $data_detail = [
                    'penyiangan_id' => $current_id,
                    'no_inv' => $no_inv,
                    'status_buku' => $status_buku,
                    'author' => $author,
                    'status' => 1
                ];
                $this->Md_penyiangan->add_penyiangan_detail($data_detail);
            }

            $message = 'Penyiangan berhasil disimpan';
            $log_ket = $this->session->userdata('username') . ' Melakukan Add Penyiangan Buku No Dokumen: ' . $data_header['no_dokumen'];
        }

        // Log selalu dibuat di sini (dalam transaksi)
        $log = [
            'user_id' => $this->session->userdata('idsys'),
            'jenis_log' => 'Admin',
            'jenis_akses' => $is_update ? 'Update' : 'Add',
            'status' => 1,
            'keterangan' => $log_ket,
            'IP' => $this->input->ip_address()
        ];
        $this->Md_log->addLog($log);

        // Final check transaksi
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menyimpan penyiangan. Terjadi kesalahan sistem.'
            ]);
        } else {
            $this->db->trans_commit();
            echo json_encode([
                'status' => 'success',
                'message' => $message
            ]);
        }
        exit;
    }

    public function delete($penyiangan_id) {
        // Decrypt ID
        $id = decrypt($penyiangan_id);
        if (empty($id)) {
            echo json_encode([
                'data'    => false,
                'message' => 'ID penyiangan tidak valid'
            ]);
            exit;
        }

        // Mulai transaksi
        $this->db->trans_start();

        // Ambil data penyiangan untuk no_dokumen dan validasi
        $penyiangan = $this->Md_penyiangan->get_penyiangan_by_id($id);
        if (!$penyiangan) {
            $this->db->trans_rollback();
            echo json_encode([
                'data'    => false,
                'message' => 'Data penyiangan tidak ditemukan'
            ]);
            exit;
        }

        $no_dokumen = $penyiangan->no_dokumen;

        // Tentukan status baru
        $highest_status = $this->Md_penyiangan->get_highest_deleted_status($no_dokumen);
        $new_status = ($highest_status >= 2) ? $highest_status + 1 : 2;

        // Update status header
        $this->Md_penyiangan->update_penyiangan($id, ['status' => $new_status]);

        // Soft delete semua detail
        $this->Md_penyiangan->soft_delete_all_details($id);

        // Buat log (dalam transaksi)
        $log = [
            'user_id'     => $this->session->userdata('idsys'),
            'jenis_log'   => 'Admin',
            'jenis_akses' => 'Hapus',
            'status'      => 1,
            'keterangan'  => $this->session->userdata('username') . ' Melakukan Hapus Penyiangan Buku No Dokumen: ' . $no_dokumen . ' (Status baru: ' . $new_status . ')',
            'IP'          => $this->input->ip_address()
        ];
        $this->Md_log->addLog($log);

        // Cek status transaksi
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode([
                'data'    => false,
                'message' => 'Gagal menghapus penyiangan. Terjadi kesalahan sistem.'
            ]);
        } else {
            $this->db->trans_commit();
            echo json_encode([
                'data'    => true,
                'message' => 'Data berhasil dihapus (status: ' . $new_status . ')'
            ]);
        }
        exit;
    }
    
    public function form($jenis_form, $id = null) {
        $page_data['page_access'] = "admin";
        $page_data['page_now']    = 'Transaksi';
        $page_data['page_title']  = 'Penyiangan Koleksi Buku';

        //$page_data['is_view_mode'] = false;

        if ($jenis_form == 'edit_page' || $jenis_form == 'view_page') {
            if (empty($id)) show_404();

            $decrypted_id = decrypt($id);
            if (empty($decrypted_id)) show_404();

            $page_data['edit_data']    = $this->Md_penyiangan->get_penyiangan_by_id($decrypted_id);
            if (empty($page_data['edit_data'])) show_404();

            $page_data['edit_details'] = $this->Md_penyiangan->get_penyiangan_details($page_data['edit_data']->penyiangan_id);

            //$page_data['is_view_mode'] = ($jenis_form == 'view_page');
        } 
        elseif ($jenis_form == 'add_page') {
            $penyiangan_id_enc = $this->input->get('penyiangan_id');
            if (!empty($penyiangan_id_enc)) {
                $page_data['preselected_penyiangan_id'] = $penyiangan_id_enc;
            }
        }

        // Pilih file view sesuai jenis
        if ($jenis_form == 'add_page') {
            $page_data['page_file'] = 'add';
        } 
        elseif ($jenis_form == 'edit_page') {
            $page_data['page_file'] = 'edit';
        } 
        elseif ($jenis_form == 'view_page') {
            $page_data['page_file'] = 'view';
        } 
        else {
            show_404();
        }

        $page_data['page_dir'] = 'manage_penyiangan';
        $page_data['page_name'] = 'manage_penyiangan';
        $this->load->view('index', $page_data);
    }
    
    public function fetch_details() {
        $penyiangan_id = $this->input->post('penyiangan_id');
        $details = $this->Md_penyiangan->get_penyiangan_details(decrypt($penyiangan_id));
        echo json_encode(['details' => $details]);
        exit;
    }
    
    public function export_excel($id_enc)
    {
        $id = decrypt($id_enc);
        if (!$id) show_404();

        $header = $this->Md_penyiangan->get_penyiangan_by_id($id);
        $details = $this->Md_penyiangan->get_penyiangan_details($id);

        if (!$header) show_404();

        require APPPATH . 'third_party/PHPExcel.php';

        $excel = new PHPExcel();
        $sheet = $excel->setActiveSheetIndex(0);

        // =========================
        // JUDUL
        // =========================
        $sheet->setCellValue('A1', 'DETAIL PENYIANGAN BUKU');
        $sheet->mergeCells('A1:K1');

        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14
            ],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
            ]
        ]);

        // =========================
        // HEADER INFO
        // =========================
        $sheet->setCellValue('A3', 'No Dokumen');
        $sheet->setCellValue('B3', $header->no_dokumen);

        $sheet->setCellValue('A4', 'Tanggal');
        $sheet->setCellValue('B4', date('d/m/Y', strtotime($header->tgl_penyiangan)));

        $sheet->setCellValue('A5', 'Catatan');
        $sheet->setCellValue('B5', $header->catatan);

        $sheet->getStyle('A3:A5')->applyFromArray([
            'font' => ['bold' => true]
        ]);

        // =========================
        // HEADER TABEL
        // =========================
        $row = 7;

        $sheet->setCellValue('A'.$row, 'NO');
        $sheet->setCellValue('B'.$row, 'NO INVENTARIS');
        $sheet->setCellValue('C'.$row, 'NO BARCODE');
        $sheet->setCellValue('D'.$row, 'ISBN');
        $sheet->setCellValue('E'.$row, 'JUDUL');
        $sheet->setCellValue('F'.$row, 'TAHUN TERBIT');
        $sheet->setCellValue('G'.$row, 'PENULIS');
        $sheet->setCellValue('H'.$row, 'PENERBIT');
        $sheet->setCellValue('I'.$row, 'ASAL BUKU');
        $sheet->setCellValue('J'.$row, 'TGL INVENTARIS');
        $sheet->setCellValue('K'.$row, 'STATUS');

        // Style header tabel
        $sheet->getStyle('A7:K7')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => '4F81BD']
            ],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ]
        ]);

        $sheet->getRowDimension('7')->setRowHeight(25);

        // =========================
        // DATA
        // =========================
        $row++;
        $no = 1;

        foreach ($details as $d) {
            $sheet->setCellValue('A'.$row, $no++);
            $sheet->setCellValue('B'.$row, $d->no_inv);
            $sheet->setCellValue('C'.$row, $d->no_barcode);
            $sheet->setCellValue('D'.$row, $d->ISBN);
            $sheet->setCellValue('E'.$row, $d->judul);
            $sheet->setCellValue('F'.$row, $d->thn_terbit);
            $sheet->setCellValue('G'.$row, $d->penulis);
            $sheet->setCellValue('H'.$row, $d->penerbit);
            $sheet->setCellValue('I'.$row, $d->asal_buku);
            $sheet->setCellValue('J'.$row, date('d/m/Y', strtotime($d->tgl_inv)));
            $sheet->setCellValue('K'.$row, $d->status_buku);

            // Zebra row
            if ($row % 2 == 0) {
                $sheet->getStyle("A$row:K$row")->applyFromArray([
                    'fill' => [
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => ['rgb' => 'F2F2F2']
                    ]
                ]);
            }

            $row++;
        }

        $lastRow = $row - 1;

        // =========================
        // BORDER
        // =========================
        $sheet->getStyle("A7:K$lastRow")->applyFromArray([
            'borders' => [
                'allborders' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                ]
            ]
        ]);

        // =========================
        // ALIGNMENT
        // =========================
        $sheet->getStyle("A8:A$lastRow")->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle("F8:F$lastRow")->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle("J8:J$lastRow")->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        // Wrap text (judul)
        $sheet->getStyle("E8:E$lastRow")->getAlignment()->setWrapText(true);

        // =========================
        // AUTO WIDTH
        // =========================
        foreach(range('A','K') as $col){
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // =========================
        // FREEZE HEADER
        // =========================
        $sheet->freezePane('A8');

        // =========================
        // OUTPUT
        // =========================
        $filename = 'Penyiangan_'.$header->no_dokumen.'.xls';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $writer->save('php://output');
        exit;
    }
    
    public function get_inventaris_barcode()
    {
        $barcode = trim($this->input->post('no_barcode'));

        if ($barcode == '') {
            echo json_encode([
                'status'=>'error',
                'message'=>'Barcode kosong'
            ]);
            return;
        }

        $buku = $this->Md_penyiangan->get_inventaris_by_barcode($barcode);

        if (!$buku) {
            echo json_encode([
                'status'=>'error',
                'message'=>'Barcode tidak ditemukan.'
            ]);
            return;
        }

        echo json_encode([
            'status'=>'success',
            'data'=>$buku
        ]);
    }

}

