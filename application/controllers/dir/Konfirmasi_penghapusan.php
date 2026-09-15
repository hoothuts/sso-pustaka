<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Konfirmasi_penghapusan extends CI_Controller {

    function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        
        $this->load->database();
        $this->load->model('Md_penghapusan');
        $this->load->model('Md_siperpus_inventaris'); 
        $this->load->model('Md_siperpus_setting');
        $this->load->helper('pkrlib_helper');
        $this->load->helper('encryption_id_helper');
       
        if ($this->session->userdata('login_type') != 'admin') {
            logoutNow();
        }
    }

    public function index() {
        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'Transaksi';
        $page_data['page_title'] = 'Konfirmasi Penghapusan Inventaris Buku';
        $page_data['page_name'] = 'konfirmasi_penghapusan';
        $page_data['page_dir'] = 'konfirmasi_penghapusan';
        $page_data['page_file'] = 'index';
        
        $this->load->view('index', $page_data);
    }

    public function fetch() {
        $datatable = $this->input->post('datatable');
        $search = isset($datatable['query']['generalSearch']) ? $datatable['query']['generalSearch'] : '';
        $page = isset($datatable['pagination']['page']) ? (int) $datatable['pagination']['page'] : 1;
        $perpage = isset($datatable['pagination']['perpage']) ? (int) $datatable['pagination']['perpage'] : 10;
        $sort = isset($datatable['sort']['sort']) ? $datatable['sort']['sort'] : 'DESC';
        // Ambil filter status dari POST (Pending atau Completed)
        $status_filter = $this->input->post('status_filter'); // 'Pending' atau 'Completed'
        
        if($status_filter=='Pending'){
            $field = isset($datatable['sort']['field']) ? $datatable['sort']['field'] : 'penghapusan_id';
        }else{
            $field = isset($datatable['sort']['field']) ? $datatable['sort']['field'] : 'tgl_approve';
        }
        // Mapping sort field
        $map_field = [
            'tgl' => 'p.tgl_penghapusan',
            'no_penghapusan' => 'p.no_penghapusan',
            'author' => 'p.author'
        ];
        if($status_filter=='Pending'){
            $sort_field = isset($map_field[$field]) ? $map_field[$field] : 'p.penghapusan_id';
        }else{
            $sort_field = isset($map_field[$field]) ? $map_field[$field] : 'p.tgl_approve';
        }
        $offset = ($page - 1) * $perpage;

        // Panggil model dengan status_filter
        $list = $this->Md_penghapusan->get_penghapusan_server_side($search, $perpage, $offset, $sort_field, $sort, $status_filter);
        $total = $this->Md_penghapusan->count_filtered($search, $status_filter);
        
        $id_kepala_perpustakaan = $this->Md_siperpus_setting->getSettingbyKode('userkepala');
        $is_kepala=0;
        if(isset($id_kepala_perpustakaan)){
            if($this->session->userdata('idsys') == $id_kepala_perpustakaan->valsetting){
                $is_kepala=1;
            }
        }
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
                'catatan_kaperpus' => substr($row->catatan_kaperpus, 0, 100) . '...',
                'approve_by' => $row->approve_by,
                'tgl_approve' => date('d/M/Y H:i', strtotime($row->tgl_approve)),
                'jumlah_buku' => $jumlah_buku,
                'no_dokumen' => "<a href='".base_url()."dir/manage_penyiangan/form/view_page/". encrypt($row->penyiangan_id)."' target='_blank' > ".$row->no_dokumen."</a>",
                'author' => $row->author,
                'is_kepala' => $is_kepala
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

    public function konfirmasi_save() {
        $penghapusan_id_enc = $this->input->post('penghapusan_id');
        $penghapusan_id = decrypt($penghapusan_id_enc);
        if (empty($penghapusan_id)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID penghapusan tidak valid'
            ]);
            exit;
        }

        $status_penghapusan = $this->input->post('status_penghapusan');
        if (!in_array($status_penghapusan, ['Approve', 'Reject'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Status konfirmasi tidak valid'
            ]);
            exit;
        }

        $catatan_kaperpus = $this->input->post('catatan_kaperpus');

        $data_update = [
            'status_penghapusan' => $status_penghapusan,
            'tgl_approve' => date('Y-m-d H:i:s'),
            'approve_by' => $this->session->userdata('idsys'),
            'catatan_kaperpus' => $catatan_kaperpus
        ];

        $this->db->trans_start();

        // Jika APPROVE → cek konflik dulu SEBELUM update apa pun
        if ($status_penghapusan === 'Approve') {
            $details = $this->Md_penghapusan->get_penghapusan_details($penghapusan_id);

            if (!empty($details)) {
                $conflict_messages = [];
                foreach ($details as $detail) {
                    if (!empty($detail->no_inv)) {
                        $inventaris = $this->Md_siperpus_inventaris->get_inventaris_by_no_inv($detail->no_inv);
                        if ($inventaris && $inventaris->status === 'D') {
                            $conflict_messages[] = "Ada NO INV: {$detail->no_inv} (Barcode: " . ($detail->no_barcode ?? '-') . ") yang sudah dihapus sebelumnya.";
                        }
                    }
                }

                if (!empty($conflict_messages)) {
                    $this->db->trans_rollback();
                    $message = implode("\n", $conflict_messages);
                    echo json_encode([
                        'status' => 'error',
                        'message' => $message
                    ]);
                    exit;
                }
            }
        }

        // Jika lolos pengecekan (atau Reject) → baru update header
        $this->Md_penghapusan->update_penghapusan($penghapusan_id, $data_update);

        // Jika APPROVE → update inventaris (sudah pasti aman)
        if ($status_penghapusan === 'Approve') {
            $details = $this->Md_penghapusan->get_penghapusan_details($penghapusan_id);
            foreach ($details as $detail) {
                if (!empty($detail->no_inv)) {
                    $this->Md_siperpus_inventaris->update_inventaris_status($detail->no_inv, 'D');
                }
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan konfirmasi (transaksi gagal)']);
            exit;
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Konfirmasi berhasil disimpan' . ($status_penghapusan === 'Approve' ? ' dan inventaris telah ditandai sebagai Dihapus' : '')
        ]);
        exit;
    }

    public function form_konfirmasi($id) {
        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'konfirmasi_penghapusan';
        $page_data['page_title'] = 'Konfirmasi Penghapusan Inventaris Buku';
        
        $penghapusan_id = decrypt($id);
        if (empty($penghapusan_id)) {
            show_404();
        }

        $page_data['edit_data'] = $this->Md_penghapusan->get_penghapusan_by_id($penghapusan_id);
        if (empty($page_data['edit_data'])) {
            show_404();
        }

        $page_data['edit_details'] = $this->Md_penghapusan->get_penghapusan_details($page_data['edit_data']->penghapusan_id);
        $page_data['page_name'] = 'konfirmasi_penghapusan';
        $page_data['page_file'] = 'form';
        $page_data['page_dir'] = 'konfirmasi_penghapusan';
        $this->load->view('index', $page_data);
    }

}