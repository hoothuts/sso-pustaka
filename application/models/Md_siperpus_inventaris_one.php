<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Md_siperpus_inventaris_one extends CI_Model {

    public function get_inventaris_server_side($search = "", $limit = 10, $offset = 0, $sort_field = "i.no_inv", $sort_order = "DESC", $status_filter = 'A', $klas_filter = '') {
        $this->db->select('
            i.no_inv,i.no_barcode,i.tgl_inv,i.status,
            b.judul, b.penulis, b.thn_terbit,b.edisi,i.ISBN,i.no_klas,
            ph.no_penghapusan, ph.tgl_approve as tgl_penghapusan, ph.penghapusan_id,
            sp.nama_penerbit as penerbit, sab.nama as asal_buku, k.nama_kampus, r.nama_rak
        ');
        $this->db->from('siperpus_inventaris i');
        $this->db->join('siperpus_buku b', 'b.no_klas = i.no_klas AND b.ISBN = i.ISBN', 'left');
        $this->db->join('siperpus_asal_buku sab', 'sab.id = i.asal','left');
        $this->db->join('siperpus_penerbit sp', 'sp.kd_penerbit = b.kd_penerbit','left');
        $this->db->join('lokasi_rak r', 'r.lokasirak_id = i.lokasirak_id', 'left');
        $this->db->join('lokasi_gedung g', 'g.lokasigedung_id = r.lokasigedung_id', 'left');
        $this->db->join('lokasi_kampus k', 'k.lokasikampus_id = g.lokasikampus_id', 'left');
        $this->db->join('penyiangan_detail pd', 'pd.no_inv = i.no_inv', 'left');
        $this->db->join('penghapusan_detail phd', 'phd.penyiangandetail_id = pd.penyiangandetail_id', 'left');
        $this->db->join('penghapusan ph', 'ph.penghapusan_id = phd.penghapusan_id AND ph.status_penghapusan = "Approve"', 'left');

        $this->db->where('i.status', $status_filter);
        if ($klas_filter !== NULL && $klas_filter !=='' && $klas_filter !== '-') {
            $klas_filter = substr($klas_filter, 0, 1);
            $this->db->where('LEFT(b.no_klas,1)', $klas_filter);
        }
        
        if ($this->input->post('datatable[query][kampus]')) {
            $this->db->where('k.lokasikampus_id', $this->input->post('datatable[query][kampus]'), 'after');
        }
        if ($this->input->post('datatable[query][gedung]')) {
            $this->db->where('g.lokasigedung_id', $this->input->post('datatable[query][gedung]'), 'after');
        }
        if ($this->input->post('datatable[query][rak]')) {
            $this->db->where('r.lokasirak_id', $this->input->post('datatable[query][rak]'), 'after');
        }
    
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('i.no_inv', $search);
            $this->db->or_like('i.no_barcode', $search);
            $this->db->or_like('b.ISBN', $search);
            $this->db->or_like('b.no_klas', $search);
            $this->db->or_like('b.judul', $search);
            $this->db->or_like('b.penulis', $search);
            $this->db->or_like('sp.nama_penerbit', $search);
            $this->db->group_end();
        }

        $this->db->order_by($sort_field, $sort_order);
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function count_filtered($search = "", $status_filter = 'A', $klas_filter = '') {
        $this->db->from('siperpus_inventaris i');
        $this->db->join('siperpus_buku b', 'b.no_klas = i.no_klas AND b.ISBN = i.ISBN', 'left');
        $this->db->join('siperpus_penerbit sp', 'sp.kd_penerbit = b.kd_penerbit','left');
        $this->db->join('lokasi_rak r', 'r.lokasirak_id = i.lokasirak_id', 'left');
        $this->db->join('lokasi_gedung g', 'g.lokasigedung_id = r.lokasigedung_id', 'left');
        $this->db->join('lokasi_kampus k', 'k.lokasikampus_id = g.lokasikampus_id', 'left');
        $this->db->where('i.status', $status_filter);
        
        if ($klas_filter !== NULL && $klas_filter !=='' && $klas_filter !== '-') {
            $klas_filter = substr($klas_filter, 0, 1);
            $this->db->where('LEFT(b.no_klas,1)', $klas_filter);
        }
        
        if ($this->input->post('datatable[query][kampus]')) {
            $this->db->where('k.lokasikampus_id', $this->input->post('datatable[query][kampus]'), 'after');
        }
        if ($this->input->post('datatable[query][gedung]')) {
            $this->db->where('g.lokasigedung_id', $this->input->post('datatable[query][gedung]'), 'after');
        }
        if ($this->input->post('datatable[query][rak]')) {
            $this->db->where('r.lokasirak_id', $this->input->post('datatable[query][rak]'), 'after');
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('i.no_inv', $search);
            $this->db->or_like('i.no_barcode', $search);
            $this->db->or_like('b.judul', $search);
            $this->db->or_like('b.ISBN', $search);
            $this->db->or_like('b.no_klas', $search);
            $this->db->or_like('b.penulis', $search);
            $this->db->or_like('sp.nama_penerbit', $search);
            $this->db->group_end();
        }
        return $this->db->count_all_results();
    }

    public function get_inventaris_by_id($no_inv) {
        $this->db->select('i.*, b.judul, , k.nama_kampus, g.nama_gedung, r.nama_rak, sab.nama as asal_buku');
        $this->db->from('siperpus_inventaris i');
        $this->db->join('siperpus_buku b', 'b.no_klas = i.no_klas AND b.ISBN = i.ISBN', 'left');
        $this->db->join('siperpus_asal_buku sab', 'sab.id = i.asal','left');
        $this->db->join('lokasi_rak r', 'r.lokasirak_id = i.lokasirak_id', 'left');
        $this->db->join('lokasi_gedung g', 'g.lokasigedung_id = r.lokasigedung_id', 'left');
        $this->db->join('lokasi_kampus k', 'k.lokasikampus_id = g.lokasikampus_id', 'left');
        $this->db->where('i.no_inv', $no_inv);
        return $this->db->get()->row();
    }
    
    public function get_inventaris_by_barcode($no_barcode) {
        $this->db->select('i.*, b.judul, , k.nama_kampus, g.nama_gedung, r.nama_rak, sab.nama as asal_buku');
        $this->db->from('siperpus_inventaris i');
        $this->db->join('siperpus_buku b', 'b.no_klas = i.no_klas AND b.ISBN = i.ISBN', 'left');
        $this->db->join('siperpus_asal_buku sab', 'sab.id = i.asal','left');
        $this->db->join('lokasi_rak r', 'r.lokasirak_id = i.lokasirak_id', 'left');
        $this->db->join('lokasi_gedung g', 'g.lokasigedung_id = r.lokasigedung_id', 'left');
        $this->db->join('lokasi_kampus k', 'k.lokasikampus_id = g.lokasikampus_id', 'left');
        $this->db->where('i.no_barcode', $no_barcode);
        return $this->db->get()->row();
    }

    public function update_inventaris($no_inv, $data) {
        $this->db->where('no_inv', $no_inv);
        return $this->db->update('siperpus_inventaris', $data);
    }
    
    public function check_barcode_unique($no_barcode, $current_no_inv) {
        $this->db->where('no_barcode', $no_barcode);
        $this->db->where('no_inv !=', $current_no_inv); // kecuali dirinya sendiri
        return $this->db->get('siperpus_inventaris')->row();
    }
    
    function getBarcode($no_barcode) {
        $this->db->select('no_inv, no_barcode');
        $this->db->from("siperpus_inventaris");
        $this->db->where(array('no_barcode' => $no_barcode));
        $data = $this->db->get();
        return $data->result();
    }
    
    function getDataCallNumber($no_barcode) {
        $this->db->select("i.no_klas, i.no_inv, i.no_barcode,k.kode_warna");
        $this->db->from("siperpus_inventaris i");
        $this->db->join('siperpus_klasifikasi k', 'LEFT(k.id, 1) = LEFT(i.no_klas, 1)', 'left'); // JOIN berdasarkan karakter pertama no_klas
        $this->db->where("i.no_barcode", $no_barcode);
        return $this->db->get()->result_array();
    }
    
    function getDataCallNumberByNoKlasISBN($no_klas,$isbn) {
        $this->db->select("i.no_klas, i.no_inv, i.no_barcode,k.kode_warna");
        $this->db->from("siperpus_inventaris i");
        $this->db->join('siperpus_klasifikasi k', 'LEFT(k.id, 1) = LEFT(i.no_klas, 1)', 'left'); // JOIN berdasarkan karakter pertama no_klas
        $this->db->where("i.no_klas", $no_klas);
        $this->db->where("i.ISBN", $isbn);
        return $this->db->get()->result_array();
    }
    
     function getDataCallNumberByNoInv($no_inv) {
        $this->db->select("i.no_klas, i.no_inv, i.no_barcode, k.kode_warna");
        $this->db->from("siperpus_inventaris i");
        $this->db->join('siperpus_klasifikasi k', 'LEFT(k.id, 1) = LEFT(i.no_klas, 1)', 'left'); // JOIN berdasarkan karakter pertama no_klas
        $this->db->where("i.no_inv", $no_inv);
        return $this->db->get()->result_array();
    }
    
    public function getDataByBarcodes($barcodes) {
        if (empty($barcodes)) {
            return [];
        }

        $this->db->select("i.no_inv, i.no_barcode, IFNULL(k.kode_warna, '#111111') as kode_warna, r.nama_rak");
        $this->db->from('siperpus_inventaris i');
        $this->db->join('siperpus_klasifikasi k', 'LEFT(k.id, 1) = LEFT(i.no_klas, 1)', 'left'); // JOIN berdasarkan karakter pertama no_klas
        $this->db->join('lokasi_rak r', 'r.lokasirak_id = i.lokasirak_id', 'left'); 
        $this->db->where_in('i.no_barcode', $barcodes);
        return $this->db->get()->result_array();
    }
    
    public function get_export_data($search,$status_filter,$klas_filter,$kampus,$gedung,$rak,$sort_field,$sort_sort){
        $this->db->select('
            i.no_inv,i.no_barcode,i.tgl_inv,
            b.judul,b.penulis,b.thn_terbit,b.ISBN,b.no_klas,
            sp.nama_penerbit as penerbit,
            sab.nama as asal_buku,
            k.nama_kampus,
            r.nama_rak
        ');

        $this->db->from('siperpus_inventaris i');
        $this->db->join('siperpus_buku b','b.no_klas=i.no_klas AND b.ISBN=i.ISBN','left');
        $this->db->join('siperpus_asal_buku sab','sab.id=i.asal','left');
        $this->db->join('siperpus_penerbit sp','sp.kd_penerbit=b.kd_penerbit','left');
        $this->db->join('lokasi_rak r','r.lokasirak_id=i.lokasirak_id','left');
        $this->db->join('lokasi_gedung g','g.lokasigedung_id=r.lokasigedung_id','left');
        $this->db->join('lokasi_kampus k','k.lokasikampus_id=g.lokasikampus_id','left');

        $this->db->where('i.status',$status_filter);

        if($klas_filter!='' && $klas_filter!='-'){
            $klas_filter = substr($klas_filter,0,1);
            $this->db->where('LEFT(b.no_klas,1)',$klas_filter);
        }

        if($kampus!=''){
            $this->db->where('k.lokasikampus_id',$kampus);
        }

        if($gedung!=''){
            $this->db->where('g.lokasigedung_id',$gedung);
        }

        if($rak!=''){
            $this->db->where('r.lokasirak_id',$rak);
        }

        if($search!=''){
            $this->db->group_start();
            $this->db->like('i.no_inv',$search);
            $this->db->or_like('i.no_barcode',$search);
            $this->db->or_like('b.judul',$search);
            $this->db->or_like('b.ISBN',$search);
            $this->db->or_like('b.no_klas',$search);
            $this->db->or_like('b.penulis',$search);
            $this->db->or_like('sp.nama_penerbit',$search);
            $this->db->group_end();
        }

        $this->db->order_by($sort_field,$sort_sort);

        return $this->db->get()->result();
    }

}