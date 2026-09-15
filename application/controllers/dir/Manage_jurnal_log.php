<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_jurnal_log extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        date_default_timezone_set('Asia/Jakarta');

        $this->load->database();

        $this->load->model('Md_jurnal_log');

        if ($this->session->userdata('login_type') != 'admin') {
            logoutNow();
        }
    }

    public function index()
    {
        $page_data['page_name']   = 'manage_jurnal_log';
        $page_data['page_dir']    = 'manage_jurnal_log';
        $page_data['page_file']   = 'index';

        $page_data['page_access'] = "admin";
        $page_data['page_now']    = 'Jurnal Berlangganan';
        $page_data['page_title']  = 'Manage Jurnal Log';

        $this->load->view('index', $page_data);
    }

    public function fetch()
    {
        $datatable = $this->input->post('datatable');

        $search = $datatable['query']['generalSearch'] ?? '';

        $vendor = $this->input->post('vendor');

        $aktivitas = $this->input->post('aktivitas');

        $tanggal_awal = $this->input->post('tanggal_awal');
        $tanggal_akhir = $this->input->post('tanggal_akhir');

        $page    = $datatable['pagination']['page'] ?? 1;
        $perpage = $datatable['pagination']['perpage'] ?? 10;

        $sort  = $datatable['sort']['sort'] ?? 'DESC';
        $field = $datatable['sort']['field'] ?? 'jl.jurnallog_id';

        $offset = ($page - 1) * $perpage;

        $list = $this->Md_jurnal_log->get_datatables(
            $search,
            $vendor,
            $aktivitas,
            $tanggal_awal,
            $tanggal_akhir,
            $perpage,
            $offset,
            $field,
            $sort
        );

        $total = $this->Md_jurnal_log->count_filtered(
            $search,
            $vendor,
            $aktivitas,
            $tanggal_awal,
            $tanggal_akhir
        );

        $data = [];

        $no = $offset;

        foreach($list as $row){

            $no++;

            $data[] = [

                'number'            => $no,

                'id_enc'            => urlencode(
                    base64_encode($row->jurnallog_id)
                ),

                'tgl_post'          => $row->tgl_post,

                'nim'               => $row->nim,

                'nama'              => $row->nama,

                'kelas'             => $row->kelas,

                'nama_vendor'       => $row->nama_vendor,

                'username'          => $row->username,

                'jenis_aktivitas'   => $row->jenis_aktivitas,

                'ip_address'        => $row->ip_address,

                'device'            => $this->simplify_user_agent(
                    $row->user_agent
                ),

                'keterangan'        => $row->keterangan
            ];
        }

        echo json_encode([

            "meta" => [

                "page"    => $page,
                "pages"   => ceil($total / $perpage),
                "perpage" => $perpage,
                "total"   => $total

            ],

            "data" => $data

        ]);
    }

    public function search_vendor()
    {
        $search = $this->input->get('searchtext');

        $this->db->select('
            jurnalvendor_id,
            nama_vendor
        ');

        $this->db->from('jurnal_vendor');

        $this->db->where('status', 1);

        if($search){
            $this->db->like('nama_vendor', $search);
        }

        $this->db->order_by('nama_vendor', 'ASC');

        $list = $this->db->get()->result();

        $data = [];

        foreach($list as $row){

            $data[] = [
                'id'   => $row->jurnalvendor_id,
                'text' => $row->nama_vendor
            ];
        }

        echo json_encode([
            'items' => $data
        ]);
    }

    public function get_detail()
    {
        $id = base64_decode(
            urldecode(
                $this->input->post('id')
            )
        );

        $data = $this->Md_jurnal_log->get_detail($id);

        if(!$data){

            echo json_encode([
                'status' => 'error'
            ]);

            exit;
        }

        echo json_encode([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    private function simplify_user_agent($ua)
    {
        if(!$ua){
            return '-';
        }

        $ua = strtolower($ua);

        if(strpos($ua, 'chrome') !== false){
            return 'Chrome';
        }

        if(strpos($ua, 'firefox') !== false){
            return 'Firefox';
        }

        if(strpos($ua, 'safari') !== false){
            return 'Safari';
        }

        if(strpos($ua, 'edge') !== false){
            return 'Edge';
        }

        if(strpos($ua, 'opera') !== false){
            return 'Opera';
        }

        return 'Browser';
    }

    public function export_excel()
    {
        $search = $this->input->get('search');

        $vendor = $this->input->get('vendor');

        $aktivitas = $this->input->get('aktivitas');

        $tanggal_awal = $this->input->get('tanggal_awal');

        $tanggal_akhir = $this->input->get('tanggal_akhir');

        $data = $this->Md_jurnal_log->get_datatables(
            $search,
            $vendor,
            $aktivitas,
            $tanggal_awal,
            $tanggal_akhir,
            999999,
            0,
            'jl.tgl_post',
            'DESC'
        );

        header("Content-Type: application/vnd.ms-excel");
        header(
            "Content-Disposition: attachment; filename=Log_Jurnal_" .
            date('YmdHis') .
            ".xls"
        );

        echo '
        <table border="1">

            <tr style="font-weight:bold;background:#eee;">

                <th>No</th>
                <th>Tanggal</th>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Prodi / Kelas</th>
                <th>Vendor</th>
                <th>Username Vendor</th>
                <th>Aktivitas</th>
                <th>IP Address</th>
                <th>Keterangan</th>

            </tr>
        ';

        $no = 1;

        foreach($data as $row){

            echo '

            <tr>

                <td>'.$no++.'</td>

                <td>'.$row->tgl_post.'</td>

                <td>'.$row->nim.'</td>

                <td>'.$row->nama.'</td>

                <td>'.$row->kelas.'</td>

                <td>'.$row->nama_vendor.'</td>

                <td>'.$row->username.'</td>

                <td>'.$row->jenis_aktivitas.'</td>

                <td>'.$row->ip_address.'</td>

                <td>'.$row->keterangan.'</td>

            </tr>

            ';
        }

        echo '</table>';
    }

}