<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_jurnal extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        date_default_timezone_set('Asia/Jakarta');

        $this->load->database();

        $this->load->model('Md_jurnal_log');
        $this->load->model('Md_jurnal_vendor');
        $this->load->model('Md_jurnal_akun');
        
        $this->load->helper('pkrlib_helper');

        if ($this->session->userdata('login_type') != 'admin') {
            logoutNow();
        }
    }

    public function index()
    {
        $page_data['page_name']   = 'dashboard_jurnal';
        $page_data['page_dir']    = 'dashboard_jurnal';
        $page_data['page_file']   = 'index';

        $page_data['page_access'] = "admin";
        $page_data['page_now']    = 'Jurnal Berlangganan';
        $page_data['page_title']  = 'Dashboard Statistik Jurnal';

        /* ================= KPI ================= */

        $page_data['total_today'] =
            $this->Md_jurnal_log->get_total_today();

        $page_data['total_month'] =
            $this->Md_jurnal_log->get_total_month();

        $page_data['total_user'] =
            $this->Md_jurnal_log->get_total_user();

        $page_data['total_vendor'] =
            $this->Md_jurnal_vendor->get_total_vendor_aktif();

        $this->load->view('index', $page_data);
    }

    public function chart_access()
    {
        $period = $this->input->get('period');

        echo json_encode(
            $this->Md_jurnal_log->get_chart_access($period)
        );
    }

    public function top_vendor()
    {
        echo json_encode(
            $this->Md_jurnal_vendor->get_top_vendor()
        );
    }

    public function top_prodi()
    {
        echo json_encode(
            $this->Md_jurnal_log->get_top_prodi()
        );
    }

    public function aktivitas_distribution()
    {
        echo json_encode(
            $this->Md_jurnal_log->get_aktivitas_distribution()
        );
    }

}