<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_penghapusan extends CI_Controller {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        
        $this->load->database();
        $this->load->model('Md_penghapusan');
        $this->load->model('Md_penyiangan');
        $this->load->model('Md_log');
        $this->load->helper('pkrlib_helper');
        $this->load->helper('encryption_id_helper');
       
        if ($this->session->userdata('login_type') != 'admin') {
            logoutNow();
        }
    }

    public function index() {
        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'Transaksi';
        $page_data['page_title'] = 'Penghapusan Inventaris Buku';
        $page_data['page_name'] = 'manage_penghapusan';
        $page_data['page_dir'] = 'manage_penghapusan';
        $page_data['page_file'] = 'index';
        
        $this->load->view('index', $page_data);
    }

    public function fetch() {
        $datatable = $this->input->post('datatable');
        $search = isset($datatable['query']['generalSearch']) ? $datatable['query']['generalSearch'] : '';
        $page = isset($datatable['pagination']['page']) ? (int) $datatable['pagination']['page'] : 1;
        $perpage = isset($datatable['pagination']['perpage']) ? (int) $datatable['pagination']['perpage'] : 10;
        $sort = isset($datatable['sort']['sort']) ? $datatable['sort']['sort'] : 'DESC';
        $field = isset($datatable['sort']['field']) ? $datatable['sort']['field'] : 'penghapusan_id';

        // Mapping sort field
        $map_field = [
            'tgl' => 'p.tgl_penghapusan',
            'no_penghapusan' => 'p.no_penghapusan',
            'author' => 'p.author',
            'penghapusan_id'  => 'p.penghapusan_id'  // Tambahkan mapping ini
        ];
        $sort_field = isset($map_field[$field]) ? $map_field[$field] : 'p.penghapusan_id';
        $offset = ($page - 1) * $perpage;

        $list = $this->Md_penghapusan->get_penghapusan_server_side($search, $perpage, $offset, $sort_field, $sort);
        $total = $this->Md_penghapusan->count_filtered($search);

        $data = array();
        $no = $offset;
        foreach ($list as $row) {
            $no++;
            $jumlah_buku = $this->Md_penghapusan->count_details($row->penghapusan_id);
            $data[] = [
                'number' => $no,
                'id' => encrypt($row->penghapusan_id),
                'no_penghapusan' => $row->no_penghapusan,
                'tgl' => date('d/M/Y', strtotime($row->tgl_penghapusan)),
                'status_penghapusan' => $row->status_penghapusan,
                'catatan' => substr($row->catatan, 0, 100) . '...',
                'jumlah_buku' => $jumlah_buku,
                'no_dokumen' => "<a href='".base_url()."dir/manage_penyiangan/form/view_page/". encrypt($row->penyiangan_id)."' target='_blank' > ".$row->no_dokumen."</a>",
                'author' => $row->author
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

    public function search_penyiangan_ajax() {
        $text = $this->input->get('searchtext');
        $res = $this->Md_penyiangan->search_penyiangan_without_penghapusan($text); // Hanya penyiangan tanpa penghapusan
        $final = [];
        foreach ($res as $r) {
            $final[] = ['id' => encrypt($r['penyiangan_id']), 'text' => $r['no_dokumen'] . ' - ' . date('d/m/Y', strtotime($r['tgl_penyiangan']))];
        }
        echo json_encode(['items' => $final]);
        exit;
    }

    public function save() {
        $penghapusan_id_enc = $this->input->post('penghapusan_id');
        $author = $this->session->userdata('idsys');

        $tgl_penghapusan = $this->input->post('tgl_penghapusan');
        if (empty($tgl_penghapusan)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Tanggal Penghapusan tidak boleh kosong'
            ]);
            exit;
        }

        $data_header = [
            'tgl_penghapusan' => $tgl_penghapusan,
            'status_penghapusan' => 'Pending',
            'catatan' => $this->input->post('catatan')
            
        ];

        // --- Ambil dan validasi detail yang diceklist ---
        $penyiangandetail_ids = $this->input->post('penyiangandetail_id') ?? [];
        $keterangan_tambahans = $this->input->post('keterangan_tambahan') ?? [];

        if (empty($penyiangandetail_ids)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Minimal satu detail buku harus dipilih untuk penghapusan.'
            ]);
            exit;
        }
        
        if ($penghapusan_id_enc) {
            // === UPDATE ===
            $current_id = decrypt($penghapusan_id_enc);
            if ($current_id === false || !is_numeric($current_id) || $current_id <= 0) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'ID penghapusan tidak valid'
                ]);
                exit;
            }
            
            // === CEK DUPLIKAT NO_INV DI PENGHAPUSAN AKTIF (status != Reject) ===
            $errors = $this->Md_penghapusan->check_duplicate_no_inv_in_active_penghapusan($penyiangandetail_ids,$current_id);
            if (!empty($errors)) {
                $message = implode("\n", $errors);
                echo json_encode([
                    'status'  => 'error',
                    'message' => $message
                ]);
                exit;
            }
        
            // Update header TANPA menyentuh penyiangan_id
            $this->Md_penghapusan->update_penghapusan($current_id, $data_header);
            $message = 'Penghapusan berhasil diperbarui';

            // Sync details (tetap sama)
            $existing_details = $this->Md_penghapusan->get_active_details($current_id);
            $existing_map = [];
            foreach ($existing_details as $row) {
                $existing_map[$row['penyiangandetail_id']] = $row['keterangan_tambahan'];
            }

            foreach ($penyiangandetail_ids as $i => $penyiangandetail_id) {
                $keterangan = $keterangan_tambahans[$i] ?? '';
                if (isset($existing_map[$penyiangandetail_id])) {
                    if ($existing_map[$penyiangandetail_id] !== $keterangan) {
                        $this->Md_penghapusan->update_detail_keterangan(
                                $current_id,
                                $penyiangandetail_id,
                                $keterangan,
                                $author
                        );
                    }
                    unset($existing_map[$penyiangandetail_id]);
                } else {
                    $data_detail = [
                        'penghapusan_id' => $current_id,
                        'penyiangandetail_id' => $penyiangandetail_id,
                        'keterangan_tambahan' => $keterangan,
                        'author' => $author,
                        'tgl_post' => date('Y-m-d H:i:s'),
                        'status' => 1
                    ];
                    $this->Md_penghapusan->add_penghapusan_detail($data_detail);
                }
            }

            foreach ($existing_map as $penyiangandetail_id => $keterangan) {
                $this->Md_penghapusan->soft_delete_detail_by_penyiangandetail($current_id, $penyiangandetail_id);
            }
        } else {
            // === INSERT BARU ===
            $penyiangan_id_enc = $this->input->post('penyiangan_id');
            $penyiangan_id = decrypt($penyiangan_id_enc);
            if (empty($penyiangan_id) || !is_numeric($penyiangan_id) || $penyiangan_id <= 0) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Penyiangan harus dipilih dengan benar'
                ]);
                exit;
            }

            // Cek duplikat via model
            if ($this->Md_penghapusan->exists_active_penghapusan_for_penyiangan($penyiangan_id)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Penyiangan ini sudah memiliki data penghapusan aktif.'
                ]);
                exit;
            }

            $data_header['penyiangan_id'] = $penyiangan_id;

            // Generate nomor
            $yy = date('y');
            $mm = date('m');
            $seq = $this->Md_penghapusan->get_next_sequence($yy);
            if ($seq > 999) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Batas maksimal penghapusan per tahun (999) telah tercapai.'
                ]);
                exit;
            }
            $nnn = sprintf("%03d", $seq);
            $data_header['no_penghapusan'] = $yy . '/PH/' . $mm . '/' . $nnn;
            $data_header['author'] = $author;
            $data_header['tgl_post'] = date('Y-m-d H:i:s');
            $data_header['status'] = 1;

            $current_id = $this->Md_penghapusan->create_penghapusan_with_details($data_header, $penyiangandetail_ids, $keterangan_tambahans, $author);
            $message = 'Penghapusan berhasil disimpan';
        }

        echo json_encode(['status' => 'success', 'message' => $message]);
        exit;
    }

    public function delete($penghapusan_id) {
        $id = decrypt($penghapusan_id);
        if (empty($id)) {
            echo json_encode([
                'data' => false,
                'message' => 'ID penghapusan tidak valid'
            ]);
            exit;
        }

        // Ambil data dulu (SELECT di luar transaksi, aman karena read-only)
        $penghapusan = $this->Md_penghapusan->get_penghapusan_by_id($id);
        if (!$penghapusan) {
            echo json_encode([
                'data' => false,
                'message' => 'Data penghapusan tidak ditemukan'
            ]);
            exit;
        }

        $no_penghapusan = $penghapusan->no_penghapusan;

        // Mulai transaksi setelah validasi awal
        $this->db->trans_start();

        $highest_status = $this->Md_penghapusan->get_highest_deleted_status($no_penghapusan);
        $new_status = ($highest_status >= 2) ? $highest_status + 1 : 2;

        // Update status header
        $this->Md_penghapusan->update_penghapusan($id, ['status' => $new_status]);

        // Soft delete semua detail
        $this->Md_penghapusan->soft_delete_all_details($id);

        // Log dalam transaksi
        $log = [
            'user_id' => $this->session->userdata('idsys'),
            'jenis_log' => 'Admin',
            'jenis_akses' => 'Hapus',
            'status' => 1,
            'keterangan' => $this->session->userdata('username') . ' Melakukan Hapus Penghapusan Buku No Dokumen: ' . $no_penghapusan . ' (Status baru: ' . $new_status . ')',
            'IP' => $this->input->ip_address()
        ];
        $this->Md_log->addLog($log);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode([
                'data' => false,
                'message' => 'Gagal menghapus penghapusan. Terjadi kesalahan sistem.'
            ]);
        } else {
            $this->db->trans_commit();
            echo json_encode([
                'data' => true,
                'message' => 'Data berhasil dihapus (status: ' . $new_status . ')'
            ]);
        }
        exit;
    }

    public function form($jenis_form, $id = null) {
        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'Transaksi';
        $page_data['page_title'] = 'Penghapusan Inventaris Buku';
       
        if ($jenis_form == 'edit_page' || $jenis_form == 'view_page') {
            if (empty($id)) show_404();

            $decrypted_id = decrypt($id);
            if (empty($decrypted_id)) show_404();

            $page_data['edit_data'] = $this->Md_penghapusan->get_penghapusan_by_id($decrypted_id);
            if (empty($page_data['edit_data'])) show_404();

            $page_data['edit_details'] = $this->Md_penghapusan->get_penghapusan_details($page_data['edit_data']->penghapusan_id);
           
        } elseif ($jenis_form == 'add_page') {
            $penyiangan_id_enc = $this->input->get('penyiangan_id');
            if (!empty($penyiangan_id_enc)) {
                $page_data['preselected_penyiangan_id'] = $penyiangan_id_enc;
            }
        }

        // Pilih view sesuai jenis
        if ($jenis_form == 'add_page') {
            $page_data['page_file'] = 'add';
        } elseif ($jenis_form == 'edit_page') {
            $page_data['page_file'] = 'edit';
        } elseif ($jenis_form == 'view_page') {
            $page_data['page_file'] = 'view';
        } else {
            show_404();
        }

        $page_data['page_dir'] = 'manage_penghapusan';
        $page_data['page_name'] = 'manage_penghapusan';
        $this->load->view('index', $page_data);
    }
    
    public function export_excel($id_enc)
    {
        $id = decrypt($id_enc);
        if (!$id) show_404();

        $header = $this->Md_penghapusan->get_penghapusan_by_id($id);
        $details = $this->Md_penghapusan->get_penghapusan_details($id);

        if (!$header) show_404();

        require APPPATH . 'third_party/PHPExcel.php';

        $excel = new PHPExcel();
        $sheet = $excel->setActiveSheetIndex(0);

        // =========================
        // JUDUL
        // =========================
        $sheet->setCellValue('A1', 'DETAIL PENGHAPUSAN BUKU');
        $sheet->mergeCells('A1:L1');

        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER]
        ]);

        // =========================
        // HEADER INFO
        // =========================
        $rowHeader = 3;

        $sheet->setCellValue('A'.$rowHeader, 'No Penghapusan');
        $sheet->setCellValue('B'.$rowHeader++, $header->no_penghapusan);

        $sheet->setCellValue('A'.$rowHeader, 'No Penyiangan');
        $sheet->setCellValue('B'.$rowHeader++, $header->no_dokumen);

        $sheet->setCellValue('A'.$rowHeader, 'Tanggal');
        $sheet->setCellValue('B'.$rowHeader++, date('d/m/Y', strtotime($header->tgl_penghapusan)));

        $sheet->setCellValue('A'.$rowHeader, 'Status');
        $sheet->setCellValue('B'.$rowHeader++, $header->status_penghapusan);

        // =========================
        // TAMBAHAN (HANYA JIKA SUDAH DIKONFIRMASI)
        // =========================
        if ($header->status_penghapusan !== 'Pending') {

            if (!empty($header->tgl_approve)) {
                $sheet->setCellValue('A'.$rowHeader, 'Tanggal Konfirmasi');
                $sheet->setCellValue('B'.$rowHeader++, date('d/m/Y H:i', strtotime($header->tgl_approve)));
            }

            if (!empty($header->approve_by)) {
                $sheet->setCellValue('A'.$rowHeader, 'Dikonfirmasi Oleh');
                $sheet->setCellValue('B'.$rowHeader++, $header->approve_by);
            }

            if (!empty($header->catatan_kaperpus)) {
                $sheet->setCellValue('A'.$rowHeader, 'Catatan Kepala Perpustakaan');
                $sheet->setCellValue('B'.$rowHeader++, $header->catatan_kaperpus);
            }
        }

        // Catatan penghapusan tetap di bawah
        $sheet->setCellValue('A'.$rowHeader, 'Catatan Penghapusan');
        $sheet->setCellValue('B'.$rowHeader++, $header->catatan);

        // Bold label kiri
        $sheet->getStyle('A3:A'.$rowHeader)->applyFromArray([
            'font' => ['bold' => true]
        ]);

        // =========================
        // HEADER TABEL
        // =========================
        $row = $rowHeader + 1;

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
        $sheet->setCellValue('K'.$row, 'STATUS PENYIANGAN');
        $sheet->setCellValue('L'.$row, 'KETERANGAN PENGHAPUSAN');

        // Style header tabel
        $sheet->getStyle("A$row:L$row")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => '4F81BD']
            ],
            'alignment' => [
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ]
        ]);

        $sheet->getRowDimension($row)->setRowHeight(25);

        // =========================
        // DATA
        // =========================
        $row++;
        $startDataRow = $row;
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
            $sheet->setCellValue('L'.$row, $d->keterangan_tambahan);

            // Zebra row
            if ($row % 2 == 0) {
                $sheet->getStyle("A$row:L$row")->applyFromArray([
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
        $sheet->getStyle("A".($startDataRow-1).":L$lastRow")->applyFromArray([
            'borders' => [
                'allborders' => [
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                ]
            ]
        ]);

        // =========================
        // ALIGNMENT
        // =========================
        $sheet->getStyle("A$startDataRow:A$lastRow")->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle("F$startDataRow:F$lastRow")->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle("J$startDataRow:J$lastRow")->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle("E$startDataRow:E$lastRow")->getAlignment()->setWrapText(true);

        // =========================
        // AUTO WIDTH
        // =========================
        foreach(range('A','L') as $col){
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Freeze
        $sheet->freezePane('A'.($startDataRow));

        // =========================
        // OUTPUT
        // =========================
        $filename = 'Penghapusan_'.$header->no_penghapusan.'.xls';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $writer->save('php://output');
        exit;
    }

}