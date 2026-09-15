<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_laporan_belumkembali extends CI_Model {

    public function getValue($b, $text) {
        foreach ($b as $v) {
            if (in_array($text, $v, true)) {
                return $v[1];
            }
        }
        return false;
    }

    private function getDatatablesQuery() {
        $table = 'siperpus_transaksi st';
        $column_order = array(array('nomor', 'st.no_anggota'), array('nama', 'nama'), array('noinv', 'st.no_inv'), array('judul', 'sb.judul'), array('tanggal', 'st.tgl_pinjam'), array('batas', 'st.batas'));
        $column_search = array('st.no_anggota', 'nama', 'nama', 'st.no_inv', 'sb.judul', 'st.tgl_kembali', 'st.tgl_pinjam', 'st.batas');
        $order = array('st.tgl_pinjam' => 'asc'); // default order
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
            $this->db->where('st.tgl_pinjam >=', $this->input->post('datatable[query][tanggalawal]'));
        }
        if ($this->input->post('datatable[query][tanggalakhir]')) {
            $this->db->where('st.tgl_pinjam <=', $this->input->post('datatable[query][tanggalakhir]'));
        }
        //if($i > 0) $this->db->group_end(); //close bracket

        $val = $this->getValue($column_order, $this->input->post('datatable[sort][field]'));
        if ($val != false) { // here order processing
            $this->db->order_by($val, $this->input->post('datatable[sort][sort]'));
        } else if (isset($order)) {
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    private function getLaporanQuery($ta, $tl, $src) {
        //,array('tgl','ang.tgl_daftar'),array('berlaku','ang.berlaku_sampai')
        $table = 'siperpus_transaksi st';
        $column_order = array(array('nomor', 'st.no_anggota'), array('nama', 'nama'), array('noinv', 'st.no_inv'), array('judul', 'sb.judul'), array('tanggal', 'st.tgl_pinjam'), array('batas', 'st.batas'));
        $column_search = array('st.no_anggota', 'nama', 'nama', 'st.no_inv', 'sb.judul', 'st.tgl_kembali', 'st.tgl_pinjam', 'st.batas');
        $order = array('st.tgl_pinjam' => 'asc'); // default order
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
            $this->db->where('st.tgl_pinjam >=', $ta);
        }
        if ($tl) {
            $this->db->where('st.tgl_pinjam <=', $tl);
        }
        $this->db->order_by(key($order), $order[key($order)]);
    }

    function getDatatables() {
        $this->getDatatablesQuery();
        if ($this->input->post('datatable[pagination][perpage]') != -1)
            $this->db->limit($this->input->post('datatable[pagination][perpage]'), ($this->input->post('datatable[pagination][perpage]') * (($this->input->post('datatable[pagination][page]') - 1))));
        $this->db->join('siperpus_inventaris si', 'si.no_inv = st.no_inv');
        $this->db->join('siperpus_buku sb', 'sb.no_klas = si.no_klas and sb.ISBN = si.ISBN');
        $this->db->where('st.kembali', 0);
        if ($this->input->post('datatable[query][jenis]') == 'pegawai') {
            $this->db->join('pegawai ang', 'ang.nip = st.no_anggota');
            $this->db->group_by(array("st.no_anggota", "st.no_inv", "st.tgl_pinjam"));
            // $this->db->group_by(array("st.no_anggota", "st.no_inv"));
        } else if ($this->input->post('datatable[query][jenis]') == 'anggota+luar') {
            $this->db->join('siperpus_anggota_luar ang', 'ang.noid = st.no_anggota');
            $this->db->group_by(array("st.no_anggota", "st.no_inv", "st.tgl_pinjam"));
            // $this->db->group_by(array("st.no_anggota", "st.no_inv"));
        } else {
            $this->db->join('vwsiswa ang', 'ang.nis = st.no_anggota');
            $this->db->group_by(array("st.no_anggota", "st.no_inv", "st.tgl_pinjam"));
            // $this->db->group_by(array("st.no_anggota", "st.no_inv"));
        }
        $query = $this->db->get();
        return $query->result();
    }

    function countFiltered() {
        $this->getDatatablesQuery();
        $this->db->join('siperpus_inventaris si', 'si.no_inv = st.no_inv');
        $this->db->join('siperpus_buku sb', 'sb.no_klas = si.no_klas and sb.ISBN = si.ISBN');
        $this->db->where('st.kembali', 0);
        if ($this->input->post('datatable[query][jenis]') == 'pegawai') {
            $this->db->join('pegawai ang', 'ang.nip = st.no_anggota');
        } else if ($this->input->post('datatable[query][jenis]') == 'anggota+luar') {
            $this->db->join('siperpus_anggota_luar ang', 'ang.noid = st.no_anggota');
        } else {
            $this->db->join('vwsiswa ang', 'ang.nis = st.no_anggota');
        }
        $query = $this->db->get();
        return $query->num_rows();
    }

    function getLaporan($jenis, $ta, $tl, $src) {

        $this->getLaporanQuery($ta, $tl, $src);
        $this->db->join('siperpus_inventaris si', 'si.no_inv = st.no_inv');
        $this->db->join('siperpus_buku sb', 'sb.no_klas = si.no_klas and sb.ISBN = si.ISBN');
        $this->db->where('st.kembali', 0);
        if ($jenis == 'pegawai') {
            $this->db->join('pegawai ang', 'ang.nip = st.no_anggota');
        } else if ($jenis == 'anggota+luar') {
            $this->db->join('siperpus_anggota_luar ang', 'ang.noid = st.no_anggota');
        } else {
            $this->db->join('vwsiswa ang', 'ang.nis = st.no_anggota');
        }
        $query = $this->db->get();
        return $query->result();
    }
}
