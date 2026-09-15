<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_laporan_pengembalian extends CI_Model {

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
        $column_order = array(array('nomor', 'st.no_anggota'), array('nama', 'nama'), array('noinv', 'st.no_inv'), array('judul', 'sb.judul'), array('tanggal', 'st.tgl_kembali'));
        $column_search = array('st.no_anggota', 'nama', 'nama', 'st.no_inv', 'sb.judul', 'st.tgl_kembali', 'st.tgl_kembali');
        $order = array('st.tgl_kembali' => 'desc'); // default order
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
            $this->db->where('st.tgl_kembali >=', $this->input->post('datatable[query][tanggalawal]'));
        }
        if ($this->input->post('datatable[query][tanggalakhir]')) {
            $this->db->where('st.tgl_kembali <=', $this->input->post('datatable[query][tanggalakhir]'));
        }
        
         // tambahan: filter jenis peminjaman
        $jenis_pengembalian = $this->input->post('datatable[query][jenispengembalian]')?? 'All';
        if ($jenis_pengembalian == 'Mandiri') {
            $this->db->where('st.is_mandiri_pengembalian', 'Ya');
        } elseif ($jenis_pengembalian == 'Reguler') {
            $this->db->where('st.is_mandiri_pengembalian IS NULL', null, false);
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
        $column_order = array(array('nomor', 'st.no_anggota'), array('nama', 'nama'), array('noinv', 'st.no_inv'), array('judul', 'sb.judul'), array('tanggal', 'st.tgl_kembali'));
        $column_search = array('st.no_anggota', 'nama', 'nama', 'st.no_inv', 'sb.judul', 'st.tgl_kembali', 'st.tgl_kembali');
        $order = array('st.tgl_kembali' => 'asc'); // default order
        $this->db->from($table);

        $i = 0;

        foreach ($column_search as $item) { // loop column
            if (strlen($src) > 0) { // if datatable send POST for search

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
            $this->db->where('st.tgl_kembali >=', $ta);
        }
        if ($tl) {
            $this->db->where('st.tgl_kembali <=', $tl);
        }
        $this->db->order_by(key($order), $order[key($order)]);
    }

    function getDatatables() {
        $this->getDatatablesQuery();
        if ($this->input->post('datatable[pagination][perpage]') != -1)
            $this->db->limit($this->input->post('datatable[pagination][perpage]'), ($this->input->post('datatable[pagination][perpage]') * (($this->input->post('datatable[pagination][page]') - 1))));
        $this->db->join('siperpus_inventaris si', 'si.no_inv = st.no_inv');
        $this->db->join('siperpus_buku sb', 'sb.no_klas = si.no_klas and sb.ISBN = si.ISBN');
        $this->db->where('st.kembali', 1);
        if ($this->input->post('datatable[query][jenis]') == 'pegawai') {
            $this->db->join('pegawai ang', 'ang.nip = st.no_anggota');
            $this->db->group_by(array("st.no_anggota", "st.no_inv", "st.tgl_pinjam"));
        } else if ($this->input->post('datatable[query][jenis]') == 'anggota+luar') {
            $this->db->join('siperpus_anggota_luar ang', 'ang.noid = st.no_anggota');
            $this->db->group_by(array("st.no_anggota", "st.no_inv", "st.tgl_pinjam"));
        } else {
            $this->db->join('vwsiswa ang', 'ang.nis = st.no_anggota');
            $this->db->group_by(array("st.no_anggota", "st.no_inv", "st.tgl_pinjam"));
            if ($this->input->post('datatable[query][pilihprodi]') != 'All') {
                $this->db->where('ang.kelas = ', $this->input->post('datatable[query][pilihprodi]'));
            }
        }
        $query = $this->db->get();
        return $query->result();
    }

    function countFiltered() {
        $this->getDatatablesQuery();
        $this->db->join('siperpus_inventaris si', 'si.no_inv = st.no_inv');
        $this->db->join('siperpus_buku sb', 'sb.no_klas = si.no_klas and sb.ISBN = si.ISBN');
        $this->db->where('st.kembali', 1);
        if ($this->input->post('datatable[query][jenis]') == 'pegawai') {
            $this->db->join('pegawai ang', 'ang.nip = st.no_anggota');
            $this->db->group_by(array("st.no_anggota", "st.no_inv", "st.tgl_pinjam"));
        } else if ($this->input->post('datatable[query][jenis]') == 'anggota+luar') {
            $this->db->join('siperpus_anggota_luar ang', 'ang.noid = st.no_anggota');
            $this->db->group_by(array("st.no_anggota", "st.no_inv", "st.tgl_pinjam"));
        } else {
            $this->db->join('vwsiswa ang', 'ang.nis = st.no_anggota');
            $this->db->group_by(array("st.no_anggota", "st.no_inv", "st.tgl_pinjam"));
            if ($this->input->post('datatable[query][pilihprodi]') != 'All') {
                $this->db->where('ang.kelas = ', $this->input->post('datatable[query][pilihprodi]'));
            }
        }
        $query = $this->db->get();
        return $query->num_rows();
    }

    function getLaporan($jenis, $ta, $tl, $src, $prodi, $jenispengembalian='All') {

        $this->getLaporanQuery($ta, $tl, $src);
        $this->db->join('siperpus_inventaris si', 'si.no_inv = st.no_inv');
        $this->db->join('siperpus_buku sb', 'sb.no_klas = si.no_klas and sb.ISBN = si.ISBN');
        
        if ($jenispengembalian == 'Mandiri') {
            $this->db->where('st.is_mandiri_pengembalian', 'Ya');
        } elseif ($jenispengembalian == 'Reguler') {
            $this->db->where('st.is_mandiri_pengembalian IS NULL', null, false);
        }
        
        $this->db->where('st.kembali', 1);
        if ($jenis == 'pegawai') {
            $this->db->join('pegawai ang', 'ang.nip = st.no_anggota');
            $this->db->group_by(array("st.no_anggota", "st.no_inv", "st.tgl_pinjam"));
        } else if ($jenis == 'anggota+luar') {
            $this->db->join('siperpus_anggota_luar ang', 'ang.noid = st.no_anggota');
            $this->db->group_by(array("st.no_anggota", "st.no_inv", "st.tgl_pinjam"));
        } else {
            $this->db->join('vwsiswa ang', 'ang.nis = st.no_anggota');
            $this->db->group_by(array("st.no_anggota", "st.no_inv", "st.tgl_pinjam"));
            if ($prodi != 'All') {
                $this->db->where('ang.kelas = ', $prodi);
            }
        }
        $query = $this->db->get();
        return $query->result();
    }
    
    public function getTransaksiPengembalianMandiri($tid)
    {
        $this->db->select("
            st.*,
            ang.nama,
            ang.kelas,
            ang.nis
        ");

        $this->db->from('siperpus_transaksi st');
        $this->db->join('vwsiswa ang','ang.nis = st.no_anggota');

        $this->db->where('st.tid',$tid);
        $this->db->where('st.is_mandiri_pengembalian','Ya');

        return $this->db->get()->row();
    }
    
    public function getPengembalianMandiriByTanggal($nim,$tgl_kembali)
    {
        $this->db->select("
            st.*,
            sb.judul,
            sb.penulis,
            si.no_barcode
        ");

        $this->db->from('siperpus_transaksi st');

        $this->db->join(
            'siperpus_inventaris si',
            'si.no_inv = st.no_inv'
        );

        $this->db->join(
            'siperpus_buku sb',
            'sb.no_klas = si.no_klas
             AND sb.ISBN = si.ISBN'
        );

        $this->db->where('st.no_anggota',$nim);
        $this->db->where('DATE(st.tgl_kembali)', date('Y-m-d', strtotime($tgl_kembali)));
        $this->db->where('st.is_mandiri_pengembalian','Ya');

        return $this->db->get()->result();
    }

    
}
