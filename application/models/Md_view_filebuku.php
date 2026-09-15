<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_view_filebuku extends CI_Model {

    var $table = 'view_filebuku';
    var $column_order = array(array('nis', 'nis'), array('nama', 'nama'), array('prodi', 'kelas'), array('status', 'status_siswa')); //set column field database for datatable orderable
    var $column_search = array('nis', 'nama'); //set column field database for datatable searchable just firstname , lastname , address are searchable
    var $order = array('nama' => 'asc'); // default order

    function addData($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

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
        $hasil = $this->db->get_where('vwsiswa', array('nis	' => $id))->result();
        $data = $hasil;
        return $data;
    }

    function getSiswaByKelas($kls) {
        $hasil = $this->db->query("select * from vwsiswa where kelas = '$kls' GROUP BY nis")->result();
        // $hasil = $this->db->get_where('vwsiswa', array('kelas	' => $kls))->result();
        $data = $hasil;
        return $data;
    }

    function getKartuSiswaById($id) {
        return $this->db->select("nis, nama, kelas, status_siswa")->get_where('vwsiswa', array('nis	' => $id))->result_array();
    }

    function getDataByTgl($tglAwal, $tglAkhir) {

        $this->db->select('sk.nmkategori, COUNT(vbaca.file_id) as total');
        $this->db->from('siperpus_kategori as sk');
        $this->db->join('siperpus_buku as sb', 'sb.idkategori = sk.idkategori', 'left');
        $this->db->join('siperpus_buku_file as sbf', 'sbf.no_klas = sb.no_klas and sbf.ISBN = sb.ISBN', 'left');
        $this->db->join($this->table . ' as vbaca', 'vbaca.file_id = sbf.file_id AND vbaca.status = 1', 'left');

        if ($tglAwal) {
            $this->db->where('DATE(vbaca.tgl_post) >=', $tglAwal);
        }
        if ($tglAkhir) {
            $this->db->where('DATE(vbaca.tgl_post) <=', $tglAkhir);
        }

        $this->db->group_by('sk.nmkategori');
        $this->db->order_by('total', 'DESC');

        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }

    function getTopDataByLimit($limit) {

        $this->db->select('sb.buku_id, sb.judul, COUNT(vbaca.file_id) as total');
        $this->db->from('siperpus_buku as sb');
        $this->db->join('siperpus_buku_file as sbf', 'sbf.no_klas = sb.no_klas and sbf.ISBN = sb.ISBN');
        $this->db->join($this->table . ' as vbaca', 'vbaca.file_id = sbf.file_id AND vbaca.status = 1');

        $this->db->group_by('sb.buku_id');
        $this->db->order_by('total', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }
    
    public function getStatistikKunjunganHarian($tglAwal, $tglAkhir)
    {
        $this->db->select("
            DATE_FORMAT(vbaca.tgl_post, '%d-%m-%Y') as tgl,
            sk.nmkategori as kategori,
            COUNT(vbaca.file_id) as total
        ");

        $this->db->from('siperpus_kategori as sk');

        $this->db->join(
            'siperpus_buku as sb',
            'sb.idkategori = sk.idkategori',
            'left'
        );

        $this->db->join(
            'siperpus_buku_file as sbf',
            'sbf.no_klas = sb.no_klas 
             AND sbf.ISBN = sb.ISBN',
            'left'
        );

        $this->db->join(
            $this->table . ' as vbaca',
            'vbaca.file_id = sbf.file_id 
             AND vbaca.status = 1',
            'left'
        );

        if ($tglAwal) {
            $this->db->where(
                'DATE(vbaca.tgl_post) >=',
                $tglAwal
            );
        }

        if ($tglAkhir) {
            $this->db->where(
                'DATE(vbaca.tgl_post) <=',
                $tglAkhir
            );
        }

        $this->db->group_by([
            'DATE(vbaca.tgl_post)',
            'sk.nmkategori'
        ]);

        $this->db->order_by('DATE(vbaca.tgl_post)', 'ASC');
        $this->db->order_by('sk.nmkategori', 'ASC');

        return $this->db->get()->result();
    }
    
}
