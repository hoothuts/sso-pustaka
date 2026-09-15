<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_laporan_anggota extends CI_Model {

    public function countAll($jenis) {
        if ($jenis == 'pegawai') {
            $table = 'pegawai dos';
        } else if ($jenis == 'anggota+luar') {
            $table = 'siperpus_anggota_luar dos';
        } else {
            $table = 'vwsiswa';
        }
        $this->db->from($table);
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

    private function getDatatablesQuery($jenis) {
        //,array('tgl','ang.tgl_daftar'),array('berlaku','ang.berlaku_sampai')
        if ($jenis == 'pegawai') {
            $table = 'pegawai dos';
            $column_order = array(array('nip', 'dos.nip'), array('nama', 'dos.nama'), array('jenis', 'dos.jk'));
            $column_search = array('dos.nip', 'dos.nama', 'dos.jk');
            $order = array('dos.nama' => 'asc'); // default order
        } else if ($jenis == 'anggota+luar') {
            $table = 'siperpus_anggota_luar';
            $column_order = array(array('nama', 'nama'), array('jenis', 'jk'), array('instansi', 'instansi_asal_nama'), array('telepon', 'telepon')); //set column field database for datatable orderable
            $column_search = array('nama', 'jenis', 'instansi'); //set column field database for datatable searchable just firstname , lastname , address are searchable
            $order = array('tgl_post' => 'desc'); // default order
        } else {
            $table = 'vwsiswa sis';
            $column_order = array(array('nis', 'sis.nis'), array('nama', 'sis.nama'), array('prodi', 'sis.kelas'), array('jenis', 'sis.jk'), array('status', 'sis.status_siswa')); //set column field database for datatable orderable
            $column_search = array('sis.nis', 'sis.nama', 'sis.kelas', 'sis.jk', 'sis.status_siswa', 'sis.telepon'); //set column field database for datatable searchable just firstname , lastname , address are searchable
            $order = array('sis.tgl_masuk' => 'desc'); // default order
            // $groupby = array("sis.nis", "st.no_inv");
        }
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

//		if($jenis!='anggota+luar'){
//                  
//                        if($this->input->post('datatable[query][tanggalawal]')){
//				$this->db->where('ang.tanggal >=',$this->input->post('datatable[query][tanggalawal]'));
//			}
//			if($this->input->post('datatable[query][tanggalakhir]')){
//				$this->db->where('ang.tanggal <=',$this->input->post('datatable[query][tanggalakhir]'));
//			}
//		}
        if ($jenis == 'mahasiswa') {
            $this->db->where('sis.status_siswa','A');
            
            if ($this->input->post('datatable[query][prodi]')) {
                $this->db->where('sis.kdpst', $this->input->post('datatable[query][prodi]'));
            }
            if ($this->input->post('datatable[query][tanggalawal]')) {
                $this->db->where('sis.tgl_masuk >=', $this->input->post('datatable[query][tanggalawal]'));
            }
            if ($this->input->post('datatable[query][tanggalakhir]')) {
                $this->db->where('sis.tgl_masuk <=', $this->input->post('datatable[query][tanggalakhir]'));
            }
        } else if ($jenis == 'anggota+luar') {
            if ($this->input->post('datatable[query][tanggalawal]')) {
                $this->db->where('date(tgl_post) >=', $this->input->post('datatable[query][tanggalawal]'));
            }
            if ($this->input->post('datatable[query][tanggalakhir]')) {
                $this->db->where('date(tgl_post) <=', $this->input->post('datatable[query][tanggalakhir]'));
            }
        }
        //if($i > 0) $this->db->group_end(); //close bracket

        $val = $this->getValue($column_order, $this->input->post('datatable[sort][field]'));
        if ($val != false) { // here order processing
            $this->db->order_by($val, $this->input->post('datatable[sort][sort]'));
        } else if (isset($order)) {
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    private function getLaporanQuery($jenis, $ta, $tl, $src, $pst = null) {
        //,array('tgl','ang.tgl_daftar'),array('berlaku','ang.berlaku_sampai')
        if ($jenis == 'pegawai') {
            $table = 'pegawai dos';
            $column_order = array(array('nip', 'dos.nip'), array('nama', 'dos.nama'), array('jenis', 'dos.jk'));
            $column_search = array('dos.nip', 'dos.nama', 'dos.jk');
            $order = array('dos.nama' => 'asc'); // default order
        } else if ($jenis == 'anggota+luar') {
            $table = 'siperpus_anggota_luar';
            $column_order = array(array('nama', 'nama'), array('jenis', 'jk'), array('instansi', 'instansi_asal_nama'), array('telepon', 'telepon')); //set column field database for datatable orderable
            $column_search = array('nama', 'jenis', 'instansi'); //set column field database for datatable searchable just firstname , lastname , address are searchable
            $order = array('nama' => 'asc'); // default order
        } else {
            $table = 'vwsiswa sis';
            $column_order = array(array('nis', 'sis.nis'), array('nama', 'sis.nama'), array('prodi', 'sis.kelas'), array('jenis', 'sis.jk'), array('status', 'sis.status_siswa')); //set column field database for datatable orderable
            $column_search = array('sis.nis', 'sis.nama', 'sis.kelas', 'sis.jk', 'sis.status_siswa', 'sis.telepon'); //set column field database for datatable searchable just firstname , lastname , address are searchable
            $order = array('sis.nama' => 'asc'); // default order
        }
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

        if ($jenis != 'anggota+luar') {
            if ($ta) {
                $this->db->where('ang.tanggal >=', $ta);
            }
            if ($tl) {
                $this->db->where('ang.tanggal <=', $tl);
            }
        }
        
        if ($jenis == 'mahasiswa' && $pst) {
            $this->db->where('sis.kdpst', $pst);
        }
        $this->db->order_by(key($order), $order[key($order)]);
    }

    function getDatatables($jenis) {
        $this->getDatatablesQuery($jenis);
        if ($this->input->post('datatable[pagination][perpage]') != -1)
            $this->db->limit($this->input->post('datatable[pagination][perpage]'), ($this->input->post('datatable[pagination][perpage]') * (($this->input->post('datatable[pagination][page]') - 1))));
        
        if ($jenis == 'pegawai') {
            $this->db->join('vwanggota ang', 'ang.no_anggota = dos.nip');
        } else if ($jenis == 'anggota+luar') {
            // $this->db->join('siperpus_inventaris si', 'si.no_inv = siperpus_transaksi.no_inv');
        } else if ($jenis == 'mahasiswa') {
            $this->db->join('vwanggota ang', 'ang.no_anggota = sis.nis');
            //$this->db->group_by('sis.nis');
        }
        $query = $this->db->get();//echo $this->db->last_query();
        return $query->result();
    }

    function countFiltered($jenis) {
        $this->getDatatablesQuery($jenis);
        $query = $this->db->get();
        
        return $query->num_rows();
    }

    function getLaporan($jenis, $ta, $tl, $src, $pst = null) {

        $this->getLaporanQuery($jenis, $ta, $tl, $src, $pst);
        if ($jenis == 'pegawai') {
            $this->db->join('pegawai ang', 'ang.no_anggota = dos.nip');
        } else if ($jenis == 'anggota+luar') {
            //	$this->db->join('siperpus_inventaris si', 'si.no_inv = siperpus_transaksi.no_inv');
        } else {
            $this->db->join('vwanggota ang', 'ang.no_anggota = sis.nis');
        }
        $query = $this->db->get();
        return $query->result();
    }
}
