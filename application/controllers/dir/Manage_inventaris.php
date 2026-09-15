<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_inventaris extends CI_Controller {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        
        $this->load->database();
        $this->load->model('Md_siperpus_inventaris_one');
        $this->load->model('Md_penghapusan');
        $this->load->model('Md_siperpus_klasifikasi');
        $this->load->model('Md_lokasi');
        $this->load->model('Md_log');
        $this->load->helper('pkrlib_helper');
        $this->load->helper('encryption_id_helper');
       
        if ($this->session->userdata('login_type') != 'admin') {
            logoutNow();
        }
    }

    public function index() {
        $page_data['page_access'] = "admin";
        $page_data['page_now']    = 'inventaris';
        $page_data['page_title']  = 'Manage Inventaris Buku';
        $page_data['page_name']   = 'manage_inventaris';
        $page_data['page_dir']    = 'manage_inventaris';
        $page_data['page_file']    = 'index';
        $page_data['klasifikasi'] = $this->Md_siperpus_klasifikasi->getKlasifikasiAll();
        $page_data['kampus'] = $this->Md_lokasi->get_kampus_options();
        
        $this->load->view('index', $page_data);
    }

    public function fetch() {
        $datatable = $this->input->post('datatable');
        $status_filter = $this->input->post('status_filter'); // 'A' atau 'D'
        $klas_filter   = $this->input->post('klas_filter');
        $search = isset($datatable['query']['generalSearch']) ? $datatable['query']['generalSearch'] : '';
        $page = isset($datatable['pagination']['page']) ? (int) $datatable['pagination']['page'] : 1;
        $perpage = isset($datatable['pagination']['perpage']) ? (int) $datatable['pagination']['perpage'] : 10;
        $sort = isset($datatable['sort']['sort']) ? $datatable['sort']['sort'] : 'DESC';
        if($status_filter=='A'){
            $field = isset($datatable['sort']['field']) ? $datatable['sort']['field'] : 'tgl_inv';
        }else{
            $field = isset($datatable['sort']['field']) ? $datatable['sort']['field'] : 'ph.tgl_approve';
        }
        
        $map_field = [
            'no_inv'     => 'i.no_inv',
            'tgl_inv'    => 'i.tgl_inv',
            'judul'      => 'b.judul',
            'thn_terbit' => 'b.thn_terbit',
            'no_penghapusan' => 'ph.no_penghapusan',
            'tgl_approve' => 'ph.tgl_approve'
        ];
        if($status_filter=='A'){
            $sort_field = isset($map_field[$field]) ? $map_field[$field] : 'i.tgl_inv';
        }else{
            $sort_field = isset($map_field[$field]) ? $map_field[$field] : 'ph.tgl_approve';
        }
        $offset = ($page - 1) * $perpage;

        $list = $this->Md_siperpus_inventaris_one->get_inventaris_server_side($search, $perpage, $offset, $sort_field, $sort, $status_filter,$klas_filter);
        $total = $this->Md_siperpus_inventaris_one->count_filtered($search, $status_filter,$klas_filter);

        $data = array();
        $no = $offset;
        foreach ($list as $row) {
            $no++;
           $data[] = [
                'number'         => $no,
                'id'             => urlencode(base64_encode($row->no_inv)),
                'no_inv'         => $row->no_inv,
                'no_barcode'     => $row->no_barcode ?? '-',
                'isbn'           => $row->ISBN,
                'no_klas'        => $row->no_klas,
                'penerbit'       => $row->penerbit,
                'thn_terbit'     => $row->thn_terbit,
                'tgl_inv'        => date('d/m/Y', strtotime($row->tgl_inv)),
                'judul'          => $row->judul ?? '-',
                'penulis'       => $row->penulis,
                'edisi'       => $row->edisi,
                'asal_buku'       => $row->asal_buku,
                'status'         => $row->status,
                'no_rak'            => $row->nama_rak !='' ? $row->nama_rak : '-',
                'nama_kampus'       => $row->nama_kampus!='' ? $row->nama_kampus : '-',
                'no_penghapusan' => $row->no_penghapusan ? '<a href="' . base_url('dir/manage_penghapusan/form/view_page/' . encrypt($row->penghapusan_id)) . '" target="_blank">' . $row->no_penghapusan . '</a>' : '-',
                'tgl_penghapusan'=> $row->tgl_penghapusan ? date('d/m/Y H:i', strtotime($row->tgl_penghapusan)) : '-'
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

    public function form($jenis_form, $id = null) {
        $page_data['page_access'] = "admin";
        $page_data['page_now']    = 'inventaris';
        $page_data['page_title']  = 'Manage Inventaris Buku';

        // Load model asal buku
        $this->load->model('Md_siperpus_asal_buku');

        if ($jenis_form == 'edit_page' || $jenis_form == 'view_page') {
            $this->load->model('Md_siperpus_inventaris');
            $page_data['data']['lokasi'] = $this->Md_siperpus_inventaris->get_lokasi_rak_options();
            if (empty($id)) show_404();

            $decoded_id = base64_decode(urldecode($id));
            if (empty($decoded_id) || $decoded_id === false) show_404();

            $page_data['edit_data'] = $this->Md_siperpus_inventaris_one->get_inventaris_by_id($decoded_id);
            if (empty($page_data['edit_data'])) show_404();

            $page_data['is_view_mode'] = ($jenis_form == 'view_page');

            // Ambil data asal buku untuk select box
            $page_data['asal_buku'] = $this->Md_siperpus_asal_buku->getAsalBukuAll();
        }

        if ($jenis_form == 'add_page') {
            $page_data['page_file'] = 'add';
        } elseif ($jenis_form == 'edit_page') {
            $page_data['page_file'] = 'edit';
        } elseif ($jenis_form == 'view_page') {
            $page_data['page_file'] = 'view';
        } else {
            show_404();
        }

        $page_data['page_name'] = 'manage_inventaris';
        $page_data['page_dir']  = 'manage_inventaris';
        $this->load->view('index', $page_data);
    }
    
    public function save() {
        $this->load->library('form_validation');

        // Set rules validasi
        $this->form_validation->set_rules('no_inv', 'No Inventaris', 'required|trim');
        $this->form_validation->set_rules('tgl_inv', 'Tanggal Inventaris', 'required|callback_tgl_inv_valid');
        $this->form_validation->set_rules('asal', 'Asal Buku', 'required|trim|exact_length[1]');
        $this->form_validation->set_rules('no_barcode', 'No Barcode', 'trim|max_length[10]');

        $this->form_validation->set_message('required', '%s tidak boleh kosong');
        $this->form_validation->set_message('exact_length', '%s harus 1 karakter');

        if ($this->form_validation->run() == FALSE) {
            $error = validation_errors('<p class="text-danger">', '</p>');
            echo json_encode([
                'status'  => 'error',
                'message' => strip_tags($error)
            ]);
            exit;
        }

        $no_inv     = $this->input->post('no_inv');
        $no_barcode = $this->input->post('no_barcode');

        // === CEK UNIK NO_BARCODE (kecuali dirinya sendiri) ===
        if (!empty($no_barcode)) {
            $existing = $this->Md_siperpus_inventaris_one->check_barcode_unique($no_barcode, $no_inv);
            if ($existing) {
                echo json_encode([
                    'status'  => 'error',
                    'message' => "No Barcode {$no_barcode} sudah digunakan oleh inventaris lain (No Inv: {$existing->no_inv})"
                ]);
                exit;
            }
        }

        // Data yang akan diupdate
        $data_update = [
            'no_barcode' => $no_barcode,
            'tgl_inv'    => $this->input->post('tgl_inv'),
            'asal'       => $this->input->post('asal'),
            'lokasirak_id'       => $this->input->post('lokasirak_id') != '' ? $this->input->post('lokasirak_id') : NULL,
        ];

        // Mulai transaksi
        $this->db->trans_start();

        // Update inventaris
        $updated = $this->Md_siperpus_inventaris_one->update_inventaris($no_inv, $data_update);

        // Buat log (selalu dibuat, tapi hanya di-insert jika transaksi sukses)
        $log = [
            'user_id'     => $this->session->userdata('idsys'),
            'jenis_log'   => 'Admin',
            'jenis_akses' => 'Update',
            'status'      => 1,
            'keterangan'  => $this->session->userdata('username') . ' Melakukan Update Inventaris Buku No Inv: ' . $no_inv,
            'IP'          => $this->input->ip_address()
        ];
        $this->Md_log->addLog($log);

        // Cek status transaksi
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode([
                'status'  => 'error',
                'message' => 'Gagal memperbarui inventaris. Terjadi kesalahan sistem.'
            ]);
        } else {
            $this->db->trans_commit();
            $msg = $updated ? 'Inventaris berhasil diperbarui' : 'Tidak ada perubahan data';
            echo json_encode([
                'status'  => 'success',
                'message' => $msg
            ]);
        }
        exit;
    }
    
    /**
    * Callback untuk validasi tgl_inv
    * - Tidak boleh masa depan
    * - Tidak boleh sebelum tahun 2000
    */
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
    
    public function cetak_barcode() {
        $data_final = $this->input->post('final') ?? [];

        if (empty($data_final)) {
            echo json_encode([]);
            exit;
        }

        // Panggil model untuk ambil data barcode (bulk)
        $data = $this->Md_siperpus_inventaris_one->getDataByBarcodes($data_final);

        echo json_encode($data);
        exit;
    }

    public function cetak_callnumber() {
        $data = array();
        $data_final = $this->input->post('final');
        for ($i = 0; $i < sizeof($data_final); $i++) {
            $data[] = $this->Md_siperpus_inventaris_one->getDataCallNumber($data_final[$i]);
            $x = explode("/", $data[$i][0]['no_inv']);
            $data[$i][0]['no_inv'] = $x[sizeof($x) - 1];
            if ($data[$i][0]['no_barcode'] == $data_final[$i]) {
                $output[] = $data[$i][0];
            }
        }
        echo json_encode($output);
    }
    
    public function export_excel() {

        $status_filter = $this->input->get('status_filter');
        $klas_filter = $this->input->get('klas_filter');
        $kampus = $this->input->get('kampus');
        $gedung = $this->input->get('gedung');
        $rak = $this->input->get('rak');
        $search = $this->input->get('search');

        $sort_field = $this->input->get('sort_field');
        $sort_sort = $this->input->get('sort_sort');

        $map_field = [
            'no_inv' => 'i.no_inv',
            'tgl_inv' => 'i.tgl_inv',
            'judul' => 'b.judul',
            'thn_terbit' => 'b.thn_terbit',
            'isbn' => 'b.ISBN'
        ];

        $sort_db = isset($map_field[$sort_field]) ? $map_field[$sort_field] : 'i.tgl_inv';

        $data = $this->Md_siperpus_inventaris_one->get_export_data(
                $search,
                $status_filter,
                $klas_filter,
                $kampus,
                $gedung,
                $rak,
                $sort_db,
                $sort_sort
        );

        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=inventaris_buku.xls");

        echo "<table border='1'>";

        echo "<tr>
            <th>No</th>
            <th>No Inventaris</th>
            <th>Barcode</th>
            <th>Judul</th>
            <th>ISBN</th>
            <th>No Klas</th>
            <th>Penulis</th>
            <th>Penerbit</th>
            <th>Tahun Terbit</th>
            <th>Tgl Inventaris</th>
            <th>Kampus</th>
            <th>Rak</th>
            <th>Asal Buku</th>
          </tr>";

        $no = 1;

        foreach ($data as $row) {

            echo "<tr>
                <td>{$no}</td>
                <td>{$row->no_inv}</td>
                <td>{$row->no_barcode}</td>
                <td>{$row->judul}</td>
                <td>{$row->ISBN}</td>
                <td>{$row->no_klas}</td>
                <td>{$row->penulis}</td>
                <td>{$row->penerbit}</td>
                <td>{$row->thn_terbit}</td>
                <td>{$row->tgl_inv}</td>
                <td>{$row->nama_kampus}</td>
                <td>{$row->nama_rak}</td>
                <td>{$row->asal_buku}</td>
              </tr>";

            $no++;
        }

        echo "</table>";
    }
}