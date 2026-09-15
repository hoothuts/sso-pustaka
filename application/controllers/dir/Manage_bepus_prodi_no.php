<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_bepus_prodi_no extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');

        $this->load->database();
        $this->load->library('form_validation');

        $this->load->model('Md_bepus_prodi_no');
        $this->load->model('Md_log');
        $this->load->helper('pkrlib_helper');

        if ($this->session->userdata('login_type') != 'admin')
        {
            logoutNow();
        }
    }
    
    public function index()
    {
        $page_data['page_name'] = 'manage_bepus_prodi_no';
        $page_data['page_dir'] = 'manage_bepus_prodi_no';
        $page_data['page_file'] = 'index';

        $page_data['page_access'] = "admin";
        $page_data['page_now'] = 'Request Bebas Pustaka';
        $page_data['page_title'] = 'Format Nomor Bebas Pustaka';

        $this->load->view('index',$page_data);
    }

    public function fetch()
    {
        $datatable = $this->input->post('datatable');
        $search = $datatable['query']['generalSearch'] ?? '';

        $page = $datatable['pagination']['page'] ?? 1;
        $perpage = $datatable['pagination']['perpage'] ?? 10;

        $sort = $datatable['sort']['sort'] ?? 'DESC';
        $field = $datatable['sort']['field'] ?? 'bepusprodino_id';

        $offset = ($page - 1) * $perpage;

        $list = $this->Md_bepus_prodi_no->get_datatables(
            $search,
            $perpage,
            $offset,
            $field,
            $sort
        );

        $total = $this->Md_bepus_prodi_no->count_filtered($search);

        $data = [];
        $no = $offset;

        foreach($list as $row)
        {
            $no++;
            $jenjang='-';

            if($row->KDJENMSPST=='E')
            {
                $jenjang='D3';
            }
            elseif($row->KDJENMSPST=='D')
            {
                $jenjang='D4';
            }

            $data[] = [
                'number'=>$no,
                'id_enc'=>urlencode(base64_encode($row->bepusprodino_id)),
                'jenjang'=>$jenjang,
                'nama_prodi'=>$row->NMPSTMSPST,
                'tahun'=>$row->tahun,
                'kode_nomor'=>$row->kode_nomor,
                'author'=>$row->author,
                'tgl_post'=>$row->tgl_post
            ];
        }

        echo json_encode([
            "meta"=>[
                "page"=>$page,
                "pages"=>ceil($total/$perpage),
                "perpage"=>$perpage,
                "total"=>$total
            ],
            "data"=>$data
        ]);
    }

    public function search_prodi()
    {
        $search = $this->input->get('searchtext');
        $list = $this->Md_bepus_prodi_no->search_prodi($search);
        $data=[];

        foreach($list as $row)
        {
            $jenjang='-';

            if($row->KDJENMSPST=='E')
            {
                $jenjang='D3';
            }
            elseif($row->KDJENMSPST=='D')
            {
                $jenjang='D4';
            }

            $data[]=[
                'id'=>$row->KDPSTMSPST,
                'text'=>'['.$jenjang.'] '.$row->NMPSTMSPST
            ];
        }

        echo json_encode([
            'items'=>$data
        ]);
    }
    
    public function get_detail()
    {
        $id = base64_decode(
            urldecode(
                $this->input->post('id')
            )
        );
        $data = $this->Md_bepus_prodi_no->get_by_id($id);
        if(!$data)
        {
            echo json_encode([
                'status'=>'error'
            ]);
            return;
        }

        echo json_encode([
            'status'=>'success',
            'data'=>$data
        ]);
    }

    public function save()
    {
        $this->form_validation->set_rules('kdpst', 'Program Studi', 'required');
        $this->form_validation->set_rules('tahun', 'Tahun', 'required');
        $this->form_validation->set_rules('kode_nomor', 'Prefix Nomor', 'required');

        if($this->form_validation->run()==FALSE)
        {
            echo json_encode([
                'status'=>'error',
                'message'=>validation_errors()
            ]);
            exit;
        }

        $id_enc = $this->input->post('id_for_edit');

        $kdpst = $this->input->post('kdpst');
        $tahun = $this->input->post('tahun');

        $data = [
            'kdpst'=>$kdpst,
            'tahun'=>$tahun,
            'kode_nomor'=>$this->input->post('kode_nomor'),
            'author'=>$this->session->userdata('idsys'),
            'tgl_post'=>date('Y-m-d H:i:s'),
            'status'=>1
        ];

        if($id_enc)
        {
            $id = base64_decode(urldecode($id_enc));
            if($this->Md_bepus_prodi_no->cek_duplikat($kdpst, $tahun, $id)){
                echo json_encode([
                    'status'=>'error',
                    'message'=>'Prodi dan tahun sudah terdaftar'
                ]);
                exit;
            }

            $this->Md_bepus_prodi_no->update($id, $data);
            echo json_encode([
                'status'=>'success',
                'message'=>'Data berhasil diperbarui'
            ]);
        }
        else
        {
            if($this->Md_bepus_prodi_no->cek_duplikat($kdpst, $tahun)){
                echo json_encode([
                    'status'=>'error',
                    'message'=>'Prodi dan tahun sudah terdaftar'
                ]);
                exit;
            }

            $this->Md_bepus_prodi_no->insert($data);
            echo json_encode([
                'status'=>'success',
                'message'=>'Data berhasil ditambahkan'
            ]);
        }
    }
    
    public function delete()
    {
        $id = base64_decode(urldecode($this->input->post('id')));
        $this->Md_bepus_prodi_no->soft_delete($id);

        echo json_encode([
            'status'=>'success',
            'message'=>'Data berhasil dihapus'
        ]);
    }

    
}
