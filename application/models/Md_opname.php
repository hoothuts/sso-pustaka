<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_opname extends CI_Model {
    var $table = 'opname';

    public function get_datatables($search = '', $limit = 10, $offset = 0, $field = 'opname_id', $sort = 'ASC') {
        $this->db->select("o.*, k.nama_kampus, IFNULL(od.jml, 0) as jumlah_buku");
        $this->db->from($this->table . ' o');
        $this->db->join('(select opname_id,count(*) as jml from opname_detail where status=1 group by opname_id) as od','od.opname_id=o.opname_id','left');
        $this->db->join('lokasi_kampus k', 'k.lokasikampus_id = o.lokasikampus_id', 'left');
        $this->db->where('o.status', 1);
        if ($search) {
            $this->db->group_start();
            $this->db->like('o.no_ba', $search);
            $this->db->or_like('o.catatan', $search);
            $this->db->or_like('o.author', $search);
            $this->db->or_like('k.nama_kampus', $search);
            $this->db->group_end();
        }
        $this->db->order_by($field, $sort);
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function count_filtered($search = '') {
        $this->db->from($this->table . ' o');
        $this->db->join('(select opname_id,count(*) as jml from opname_detail where status=1 group by opname_id) as od','od.opname_id=o.opname_id','left');
        $this->db->join('lokasi_kampus k', 'k.lokasikampus_id = o.lokasikampus_id', 'left');
        $this->db->where('o.status', 1);
        if ($search) {
            $this->db->group_start();
            $this->db->like('o.no_ba', $search);
            $this->db->or_like('o.catatan', $search);
            $this->db->or_like('o.author', $search);
            $this->db->or_like('k.nama_kampus', $search);
            $this->db->group_end();
        }
        return $this->db->count_all_results();
    }

    public function get_by_id($id) {
        $this->db->select('o.*, k.nama_kampus');
        $this->db->from($this->table . ' o');
        $this->db->join('lokasi_kampus k', 'k.lokasikampus_id = o.lokasikampus_id', 'left');
        $this->db->where('o.opname_id', $id);

        return $this->db->get()->row();
    }

    public function insert($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        $this->db->where('opname_id', $id);
        return $this->db->update($this->table, $data);
    }

    public function soft_delete($id) {
        $this->db->where('opname_id', $id);
        return $this->db->update($this->table, ['status' => 2]);
    }
    
    public function check_duplicate_no_ba($no_ba, $exclude_id = null) {
        $this->db->where('no_ba', $no_ba);
        $this->db->where('status', 1);           // hanya data aktif
        if ($exclude_id !== null) {
            $this->db->where('opname_id !=', $exclude_id);
        }
        return $this->db->get($this->table)->row();
    }
    
    public function insert_detail_batch($data_detail) {
        return $this->db->insert_batch('opname_detail', $data_detail);
    }
    
    public function get_detail_by_opname($opname_id) {
        $this->db->select('d.*, i.no_inv, i.no_barcode, b.judul, g.nama_gedung, r.nama_rak');
        $this->db->from('opname_detail d');
        $this->db->join('siperpus_inventaris i', 'i.no_inv = d.no_inv', 'left');
        $this->db->join('siperpus_buku b', 'b.no_klas = i.no_klas AND b.ISBN = i.ISBN', 'left');
        $this->db->join('lokasi_rak r', 'r.lokasirak_id = i.lokasirak_id', 'left');
        $this->db->join('lokasi_gedung g', 'g.lokasigedung_id = r.lokasigedung_id', 'left');
        $this->db->where('d.status', 1);
        $this->db->where('d.opname_id', $opname_id);
        $this->db->order_by('b.judul', 'ASC');
        return $this->db->get()->result();
    }
    
    public function soft_delete_detail_by_opname($opname_id) {
        $this->db->where('opname_id', $opname_id);
        return $this->db->update('opname_detail', [
            'status' => 2
        ]);
    }
    
    public function get_detail_grouped($opname_id) {
        $this->db->select("
            b.judul,b.no_klas, b.ISBN,
            COUNT(d.no_inv) as total,
            GROUP_CONCAT(d.no_inv SEPARATOR ', ') as no_inv,
            GROUP_CONCAT(i.no_barcode SEPARATOR ', ') as no_barcode,
            GROUP_CONCAT(r.nama_rak SEPARATOR ', ') as lokasi_rak,
            GROUP_CONCAT(d.keterangan SEPARATOR ' | ') as keterangan
        ");

        $this->db->from('opname_detail d');
        $this->db->join('siperpus_inventaris i', 'i.no_inv = d.no_inv', 'left');
        $this->db->join('siperpus_buku b', 'b.no_klas = i.no_klas AND b.ISBN = i.ISBN', 'left');
        $this->db->join('lokasi_rak r', 'r.lokasirak_id = i.lokasirak_id', 'left');
        
        $this->db->where('d.opname_id', $opname_id);
        $this->db->where('d.status', 1);

        $this->db->group_by('b.no_klas, b.ISBN, b.judul');
        $this->db->order_by('b.judul', 'ASC');

        return $this->db->get()->result();
    }
    
    public function get_total_saat_ini($opname_id) {
        $this->db->select("
            i.no_klas,
            i.ISBN,
            COUNT(DISTINCT i.no_inv) as total_saat_ini
        ");

        $this->db->from('siperpus_inventaris i');
        $this->db->join('lokasi_rak r', 'r.lokasirak_id = i.lokasirak_id', 'left');
        $this->db->join('lokasi_gedung g', 'g.lokasigedung_id = r.lokasigedung_id', 'left');
        $this->db->join('opname o', 'o.lokasikampus_id = g.lokasikampus_id', 'left');

        $this->db->where('o.opname_id', $opname_id);
        $this->db->where('i.status', 'A');

        $this->db->group_by(['i.no_klas', 'i.ISBN']);

        return $this->db->get()->result();
    }
    
    public function get_detail_raw($opname_id) {
        $this->db->select("
            d.*,
            i.no_inv,
            i.no_barcode,
            b.judul,
            b.no_klas,
            b.ISBN,
            r.nama_rak
        ");

        $this->db->from('opname_detail d');
        $this->db->join('siperpus_inventaris i', 'i.no_inv = d.no_inv', 'left');
        $this->db->join('siperpus_buku b', 'b.no_klas = i.no_klas AND b.ISBN = i.ISBN', 'left');
        $this->db->join('lokasi_rak r', 'r.lokasirak_id = i.lokasirak_id', 'left');

        $this->db->where('d.opname_id', $opname_id);
        $this->db->where('d.status', 1);

        $this->db->order_by('b.judul', 'ASC');

        return $this->db->get()->result();
    }

    public function generate_no_opname()
    {
        $tahun = date('y');
        $bulan = date('m');

        $this->db->select("IFNULL(MAX(CAST(RIGHT(no_ba,3) AS UNSIGNED)),0) AS nomor");
        $this->db->from($this->table);
        $this->db->where("LEFT(no_ba,2)", $tahun);

        $row = $this->db->get()->row();

        $next = $row->nomor + 1;

        return sprintf('%s/OP/%s/%03d', $tahun, $bulan, $next);
    }
    
    public function get_selisih_barcode($opname_id)
    {
        $this->db->query("
            SET SESSION group_concat_max_len = 1000000
        ");

        $sql = "
            SELECT
                i.no_klas,
                i.ISBN,
                COUNT(i.no_inv) AS total_selisih,
                GROUP_CONCAT(i.no_barcode ORDER BY i.no_barcode SEPARATOR ', ') AS barcode_selisih
            FROM opname o
            JOIN lokasi_gedung g
                ON g.lokasikampus_id = o.lokasikampus_id
            JOIN lokasi_rak r
                ON r.lokasigedung_id = g.lokasigedung_id
            JOIN siperpus_inventaris i
                ON i.lokasirak_id = r.lokasirak_id
            WHERE
                o.opname_id = ?
                AND i.status = 'A'
                AND NOT EXISTS (
                    SELECT 1
                    FROM opname_detail od
                    WHERE od.opname_id = o.opname_id
                    AND od.no_inv = i.no_inv
                    AND od.status = 1
                )
            GROUP BY
                i.no_klas,
                i.ISBN
        ";

        return $this->db->query($sql, [$opname_id])->result();
    }

}