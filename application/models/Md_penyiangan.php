<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_penyiangan extends CI_Model {

    // Ambil data dengan filter, sort, dan limit (Server Side)
    public function get_penyiangan_server_side($search = "", $limit = 10, $offset = 0, $sort_field = "p.penyiangan_id", $sort_order = "DESC") {
        $this->db->select('p.*,u.name,COUNT(ph.penghapusan_id) AS has_penghapusan');
        $this->db->from('penyiangan p');
        $this->db->join('siperpus_sysuser u', 'u.idsysuser=p.author', 'left');
        $this->db->join('penghapusan ph', 'ph.penyiangan_id = p.penyiangan_id AND ph.status = 1', 'left');
        $this->db->where('p.status', 1);

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('p.no_dokumen', $search);
            $this->db->or_like('p.catatan', $search);
            $this->db->group_end();
        }
        $this->db->group_by('p.penyiangan_id');
        $this->db->order_by($sort_field, $sort_order);
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    // Hitung total data setelah difilter
    public function count_filtered($search = "") {
        $this->db->from('penyiangan p');
        $this->db->where('p.status', 1);

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('p.no_dokumen', $search);
            $this->db->or_like('p.catatan', $search);
            $this->db->group_end();
        }
        return $this->db->count_all_results();
    }

    // Hitung jumlah detail untuk suatu penyiangan
    public function count_details($penyiangan_id) {
        $this->db->from('penyiangan_detail');
        $this->db->where('penyiangan_id', $penyiangan_id);
        $this->db->where('status', 1);
        return $this->db->count_all_results();
    }

    public function get_penyiangan_by_id($id) {
        $this->db->from('penyiangan');
        $this->db->where('penyiangan_id', $id);
        return $this->db->get()->row();
    }

    public function get_penyiangan_details($penyiangan_id) {
        $this->db->select('pd.*, i.no_inv,i.no_barcode,i.tgl_inv, b.judul,b.ISBN,b.thn_terbit,b.penulis, sp.nama_penerbit as penerbit, sab.nama as asal_buku'); // Asumsikan join ke inventaris dan buku
        $this->db->from('penyiangan_detail pd');
        $this->db->join('siperpus_inventaris i', 'pd.no_inv = i.no_inv', 'left');
        $this->db->join('siperpus_buku b', 'b.no_klas = i.no_klas and b.ISBN = i.ISBN', 'left');
        $this->db->join('siperpus_asal_buku sab','sab.id=i.asal','left');
        $this->db->join('siperpus_penerbit sp','sp.kd_penerbit=b.kd_penerbit','left');
        $this->db->where('pd.penyiangan_id', $penyiangan_id);
        $this->db->where('pd.status', 1);
        return $this->db->get()->result();
    }

    public function add_penyiangan($data) {
        return $this->db->insert('penyiangan', $data);
    }

    public function update_penyiangan($id, $data) {
        $this->db->where('penyiangan_id', $id);
        return $this->db->update('penyiangan', $data);
    }

    public function add_penyiangan_detail($data) {
        return $this->db->insert('penyiangan_detail', $data);
    }

    public function delete_details($penyiangan_id) {
        $this->db->where('penyiangan_id', $penyiangan_id);
        return $this->db->delete('penyiangan_detail');
    }

    public function search_inventaris($text) {
        $this->db->select("i.no_inv as id,i.no_inv, CONCAT(' Barcode: ',i.no_barcode, ' - ', b.judul) as text", FALSE);
        $this->db->from('siperpus_inventaris i');
        $this->db->join('siperpus_buku b', 'b.no_klas = i.no_klas and b.ISBN = i.ISBN');
        $this->db->where('i.status', 'A');
        $this->db->group_start();
        $this->db->like('b.judul', $text);
        $this->db->or_like('i.no_inv', $text);
        $this->db->or_like('i.no_barcode', $text);
        $this->db->group_end();
        $this->db->limit(100);
        return $this->db->get()->result_array();
    }

    public function is_no_dokumen_exists($no_dokumen, $exclude_id = null) {
        $this->db->where('no_dokumen', trim($no_dokumen));
        $this->db->where('status', 1);

        if ($exclude_id !== null) {
            $this->db->where('penyiangan_id !=', $exclude_id);
        }

        return $this->db->get('penyiangan')->num_rows() > 0;
    }

    public function get_next_sequence($yy) {
        $this->db->select('no_dokumen');
        $this->db->from('penyiangan');

        $this->db->where("SUBSTRING(no_dokumen, 1, 2) = ", $yy);

        $this->db->where('status', 1);
        $this->db->order_by('no_dokumen', 'DESC');
        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $last_doc = $query->row()->no_dokumen;
            $last_seq = (int) substr($last_doc, -3);
            return $last_seq + 1;
        }

        return 1;
    }

    // Ambil semua detail aktif (status=1) untuk satu penyiangan
    public function get_active_details($penyiangan_id) {
        $this->db->where('penyiangan_id', $penyiangan_id);
        $this->db->where('status', 1);
        $this->db->select('penyiangandetail_id, no_inv, status_buku');
        return $this->db->get('penyiangan_detail')->result_array();
    }

// Soft delete satu detail tertentu (set status=2)
    public function soft_delete_detail($detail_id) {
        $this->db->where('penyiangandetail_id', $detail_id);
        return $this->db->update('penyiangan_detail', ['status' => 2]);
    }

// Soft delete semua detail aktif dari satu penyiangan
    public function soft_delete_all_details($penyiangan_id) {
        $this->db->where('penyiangan_id', $penyiangan_id);
        $this->db->where('status', 1);
        return $this->db->update('penyiangan_detail', ['status' => 2]);
    }

// Update status_buku dan author pada detail tertentu
    public function update_detail_status($detail_id, $status_buku) {
        $this->db->where('penyiangandetail_id', $detail_id);
        return $this->db->update('penyiangan_detail', [
            'status_buku' => $status_buku
        ]);
    }

    /**
     * Ambil status tertinggi dari record yang sudah dihapus (status >= 2) 
     * berdasarkan no_dokumen yang sama
     * 
     * @param string $no_dokumen Nomor dokumen yang dicari
     * @return int Status tertinggi (minimal 1 jika tidak ada)
     */
    public function get_highest_deleted_status($no_dokumen) {
        $this->db->select_max('status');
        $this->db->where('no_dokumen', $no_dokumen);
        $this->db->where('status >=', 2); // hanya record yang sudah dihapus
        $query = $this->db->get('penyiangan');

        $result = $query->row();
        return $result && $result->status ? (int) $result->status : 1;
    }
    
    public function search_penyiangan_without_penghapusan($text) {
        $this->db->select('p.penyiangan_id, p.no_dokumen, p.tgl_penyiangan');
        $this->db->from('penyiangan p');
        $this->db->where('p.status', 1);
        $this->db->where('NOT EXISTS (SELECT 1 FROM penghapusan ph WHERE ph.penyiangan_id = p.penyiangan_id AND ph.status = 1)', NULL, FALSE);
        if (!empty($text)) {
            // Cek apakah text adalah encrypted ID (panjang dan berisi huruf + angka acak)
            $decrypted_id = decrypt($text);
            if ($decrypted_id && is_numeric($decrypted_id) && (int)$decrypted_id > 0) {
                // Jika berhasil decrypt → cari berdasarkan ID asli
                $this->db->where('p.penyiangan_id', $decrypted_id);
            } else {
                // Jika bukan ID encrypted → cari seperti biasa
                $this->db->like('p.no_dokumen', $text);
            }
        }
        $this->db->limit(100);
        return $this->db->get()->result_array();
    }
    
    public function get_inventaris_by_barcode($barcode)
    {
        $this->db->select("i.no_inv, i.no_barcode, b.judul");
        $this->db->from('siperpus_inventaris i');
        $this->db->join('siperpus_buku b', 'b.no_klas=i.no_klas AND b.ISBN=i.ISBN');
        $this->db->where('i.no_barcode', trim($barcode));
        $this->db->where('i.status','A');
        return $this->db->get()->row();
    }

}
