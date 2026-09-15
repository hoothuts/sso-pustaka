<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_laporan_presensi extends CI_Model {

    public function getValue($b, $text) {
        foreach ($b as $v) {
            if (in_array($text, $v, true)) {
                return $v[1];
            }
        }
        return false;
    }

    private function getDatatablesQuery() {
        $table = 'siperpus_presensi sp';

        $column_order = array(array('tanggal', 'tanggal'), array('jam', 'jam'), array('nomor', 'sp.nis'), array('nama', 'nama'));
        $column_search = array('tanggal', 'jam', 'sp.nis', 'nama');
        $order = array('tanggal' => 'desc', 'jam' => 'desc'); // default order
        $this->db->from($table);
        $i = 0;

        foreach ($column_search as $item) { // loop column
            if ($this->input->post('datatable[query][generalSearch]')) { // if datatable send POST for search

                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $this->input->post('datatable[query][generalSearch]'));
                } else {
                    $this->db->or_like($item, $this->input->post('datatable[query][generalSearch]'));
                }

                if (count($column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if ($this->input->post('datatable[query][tanggalawal]')) {
            $this->db->where('date(sp.tanggal) >=', $this->input->post('datatable[query][tanggalawal]'));
        }
        if ($this->input->post('datatable[query][tanggalakhir]')) {
            $this->db->where('date(sp.tanggal) <=', $this->input->post('datatable[query][tanggalakhir]'));
        }
        //if($i > 0) $this->db->group_end(); //close bracket

        $val = $this->getValue($column_order, $this->input->post('datatable[sort][field]'));
        if ($val != false) { // here order processing
            $this->db->order_by($val, $this->input->post('datatable[sort][sort]'));
        } else if (isset($order)) {
            $this->db->order_by('tanggal', 'desc');
            $this->db->order_by('jam', 'desc');
        }
    }

    private function getLaporanQuery($ta, $tl, $src) {
        $table = 'siperpus_presensi sp';

        $column_order = array(array('tanggal', 'tanggal'), array('jam', 'jam'), array('nomor', 'sp.nis'), array('nama', 'nama'));
        $column_search = array('tanggal', 'jam', 'sp.nis', 'nama');
        $order = array('tanggal' => 'desc', 'jam' => 'desc'); // default order
        $this->db->from($table);

        $i = 0;

        foreach ($column_search as $item) { // loop column
            if ($src) { // if datatable send POST for search

                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $src);
                } else {
                    $this->db->or_like($item, $src);
                }

                if (count($column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if ($ta) {
            $this->db->where('date(sp.tanggal) >=', $ta);
        }
        if ($tl) {
            $this->db->where('date(sp.tanggal) <=', $tl);
        }
        $this->db->order_by('tanggal', 'desc');
        $this->db->order_by('jam', 'desc');
    }

    function getDatatables() {
        // echo $this->input->post('datatable[pagination][pilihprodi]');die;
        $this->getDatatablesQuery();
        if ($this->input->post('datatable[pagination][perpage]') != -1)
            $this->db->limit($this->input->post('datatable[pagination][perpage]'), ($this->input->post('datatable[pagination][perpage]') * (($this->input->post('datatable[pagination][page]') - 1))));
        if ($this->input->post('datatable[query][jenis]') == 'pegawai') {
            $this->db->join('pegawai ang', 'ang.nip = sp.nis');
        } else if ($this->input->post('datatable[query][jenis]') == 'anggota+luar') {
            $this->db->join('siperpus_anggota_luar ang', 'ang.noid = sp.nis');
        } else {
            $this->db->join('vwsiswa ang', 'ang.nis = sp.nis');
            if ($this->input->post('datatable[query][pilihprodi]') != 'All') {
                $this->db->where('ang.kelas = ', $this->input->post('datatable[query][pilihprodi]'));
            }
        }
        if ($this->input->post('datatable[query][jenis]') == 'mahasiswa')
            $this->db->select('cast(tanggal as date) as tanggal,cast(tanggal as time) as jam,sp.nis,ang.nama,ang.kelas');
        else
            $this->db->select('cast(tanggal as date) as tanggal,cast(tanggal as time) as jam,sp.nis,ang.nama');
        $this->db->group_by(array("sp.tanggal", "sp.nis"));
        $query = $this->db->get();
        return $query->result();
    }

    function countFiltered() {
        $this->getDatatablesQuery();
        if ($this->input->post('datatable[query][jenis]') == 'pegawai') {
            $this->db->join('pegawai ang', 'ang.nip = sp.nis');
        } else if ($this->input->post('datatable[query][jenis]') == 'anggota+luar') {
            $this->db->join('siperpus_anggota_luar ang', 'ang.noid = sp.nis');
        } else {
            $this->db->join('vwsiswa ang', 'ang.nis = sp.nis');
            if ($this->input->post('datatable[query][pilihprodi]') != 'All') {
                $this->db->where('ang.kelas = ', $this->input->post('datatable[query][pilihprodi]'));
            }
        }
        if ($this->input->post('datatable[query][jenis]') == 'mahasiswa'){
            $this->db->select('cast(tanggal as date) as tanggal,cast(tanggal as time) as jam,sp.nis,ang.nama,ang.kelas');
        }else{
            $this->db->select('cast(tanggal as date) as tanggal,cast(tanggal as time) as jam,sp.nis,ang.nama');
        }
        $this->db->group_by(array("sp.tanggal", "sp.nis"));
        $query = $this->db->get();
        return $query->num_rows();
    }

    function getLaporan($jenis, $ta, $tl, $src, $prodi) {

        $this->getLaporanQuery($ta, $tl, $src);
        if ($jenis == 'pegawai') {
            $this->db->join('pegawai ang', 'ang.nip = sp.nis');
        } else if ($jenis == 'anggota+luar') {
            $this->db->join('siperpus_anggota_luar ang', 'ang.noid = sp.nis');
        } else {
            $this->db->join('vwsiswa ang', 'ang.nis = sp.nis');
            if ($prodi != 'All') {
                $this->db->where('ang.kelas = ', $prodi);
            }
        }
        if ($this->input->post('datatable[query][jenis]') == 'mahasiswa'){
            $this->db->select('cast(tanggal as date) as tanggal,cast(tanggal as time) as jam,sp.nis,ang.nama,ang.kelas');
        }else{
            $this->db->select('cast(tanggal as date) as tanggal,cast(tanggal as time) as jam,sp.nis,ang.nama');
        }
        $this->db->group_by(array("sp.tanggal", "sp.nis"));
        $query = $this->db->get();
        return $query->result();
    }
}
