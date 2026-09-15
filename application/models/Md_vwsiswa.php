<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_vwsiswa extends CI_Model {

    var $table = 'vwsiswa';
    var $column_order = array(array('nis', 'nis'), array('nama', 'nama'), array('prodi', 'kelas'), array('status', 'status_siswa')); //set column field database for datatable orderable
    var $column_search = array('nis', 'nama'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('nama' => 'asc'); // default order

    public function countAll() {

        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function getValue($b, $text) {
        foreach ($b as $v) {
            if (in_array($text, $v, true)) {
                return $v[1];
            }
        }
        return false;
    }

    private function getDatatablesQuery() {

        $this->db->from($this->table);
        $i = 0;

        foreach ($this->column_search as $item) { // loop column
            if ($this->input->post('datatable[query][generalSearch]')) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $this->input->post('datatable[query][generalSearch]'));
                } else {
                    $this->db->or_like($item, $this->input->post('datatable[query][generalSearch]'));
                }
                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        //filter group

        if ($this->input->post('datatable[query][prodi]')) {
            $this->db->where('kelas', $this->input->post('datatable[query][prodi]'));
        }
        
        if ($this->input->post('datatable[query][angkatan]')) {
            $this->db->where('angkatan', $this->input->post('datatable[query][angkatan]'));
        }

        if ($this->input->post('datatable[query][status]')) {
            $this->db->where('status_siswa', $this->input->post('datatable[query][status]'));
        }

        //if($i > 0) $this->db->group_end(); //close bracket

        $val = $this->getValue($this->column_order, $this->input->post('datatable[sort][field]'));
        if ($val != false) { // here order processing
            $this->db->order_by($val, $this->input->post('datatable[sort][sort]'));
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function getDatatables() {
        $this->getDatatablesQuery();
        if ($this->input->post('datatable[pagination][perpage]') != -1)
            $this->db->limit($this->input->post('datatable[pagination][perpage]'), ($this->input->post('datatable[pagination][perpage]') * (($this->input->post('datatable[pagination][page]') - 1))));

        $query = $this->db->get();
        return $query->result();
    }

    function countFiltered() {
        $this->getDatatablesQuery();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function getSiswaAll() {
        $hasil = $this->db->query("SELECT * FROM vwsiswa order by nis asc");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function getSiswaById($id) {
        $hasil = $this->db->get_where('vwsiswa', array('nis' => $id))->result();
        $data = $hasil;
        return $data;
    }

    function getSiswaByKelas($kls, $tglawal = '', $tglakhir = '') {
        /* $hasil = $this->db->query("select * from vwsiswa where status_siswa='A' and kelas = '$kls' GROUP BY nis")->result();
          $data = $hasil;
          return $data; */

        $this->db->select('vs.*');
        $this->db->from('vwsiswa vs');

        if ($tglawal)
            $this->db->where('date(vs.tgl_masuk) >=', $tglawal);
        if ($tglakhir)
            $this->db->where('date(vs.tgl_masuk) <=', $tglakhir);

        $this->db->where('vs.kelas', $kls);
        $this->db->where('vs.status_siswa', 'A');
        $this->db->group_by("vs.nis");
        $this->db->order_by('vs.kelas', 'desc');
        return $this->db->get()->result();
    }

    function getKartuSiswaById($id) {
        return $this->db->select("nis, nama, kelas, status_siswa")->get_where('vwsiswa', array('nis' => $id))->result_array();
    }
    
    function getSiswaByNim($nim) {
        $hasil = $this->db->get_where('vwsiswa', array('nis' => $nim))->row();
        return $hasil;
    }
    
    // Ambil daftar kelas unik
    public function get_distinct_kelas() {
        $this->db->distinct();
        $this->db->select('kelas');
        $this->db->from('vwsiswa');
        $this->db->where('kelas IS NOT NULL');
        $this->db->order_by('kelas', 'ASC');
        return $this->db->get()->result();
    }

    // Ambil daftar tahun masuk unik
    public function get_distinct_tahun_masuk() {
        $this->db->distinct();
        $this->db->select('angkatan AS tahun_masuk');
        $this->db->from('vwsiswa');
        $this->db->where('tgl_masuk IS NOT NULL');
        $this->db->order_by('tahun_masuk', 'DESC');
        return $this->db->get()->result();
    }
    
}
