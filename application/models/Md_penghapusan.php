<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Md_penghapusan extends CI_Model {

    public function get_penghapusan_server_side($search = "", $limit = 10, $offset = 0, $sort_field = "p.penghapusan_id", $sort_order = "DESC", $status_filter = null) {
        $this->db->select('p.*, u.name,py.no_dokumen');
        $this->db->from('penghapusan p');
        $this->db->join('siperpus_sysuser u', 'u.idsysuser = p.author', 'left');
        $this->db->join('penyiangan py', 'py.penyiangan_id = p.penyiangan_id', 'left');
        $this->db->where('p.status', 1);

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('p.no_penghapusan', $search);
            $this->db->or_like('p.catatan', $search);
            $this->db->group_end();
        }

        // Filter berdasarkan status_filter
        if ($status_filter === 'Pending') {
            $this->db->where('p.status_penghapusan', 'Pending');
        } elseif ($status_filter === 'Completed') {
            $this->db->where_in('p.status_penghapusan', ['Approve', 'Reject']);
        }

        $this->db->order_by($sort_field, $sort_order);
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function count_filtered($search = "", $status_filter = null) {
        $this->db->from('penghapusan p');
        $this->db->where('p.status', 1);

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('p.no_penghapusan', $search);
            $this->db->or_like('p.catatan', $search);
            $this->db->group_end();
        }

        if ($status_filter === 'Pending') {
            $this->db->where('p.status_penghapusan', 'Pending');
        } elseif ($status_filter === 'Completed') {
            $this->db->where_in('p.status_penghapusan', ['Approve', 'Reject']);
        }

        return $this->db->count_all_results();
    }

    public function count_details($penghapusan_id) {
        $this->db->from('penghapusan_detail');
        $this->db->where('penghapusan_id', $penghapusan_id);
        $this->db->where('status', 1);
        return $this->db->count_all_results();
    }

    public function get_penghapusan_by_id($id) {
        $this->db->select('p.*,py.no_dokumen');
        $this->db->from('penghapusan p');
        $this->db->join('penyiangan py', 'py.penyiangan_id = p.penyiangan_id', 'left');
        $this->db->where('p.penghapusan_id', $id);
        return $this->db->get()->row();
    }

    public function get_penghapusan_details($penghapusan_id) {
        $this->db->select('pd.*, pnd.no_inv, pnd.status_buku, b.judul,b.ISBN,i.no_barcode,i.tgl_inv, b.thn_terbit,b.penulis, sp.nama_penerbit as penerbit, sab.nama as asal_buku');
        $this->db->from('penghapusan_detail pd');
        $this->db->join('penyiangan_detail pnd', 'pd.penyiangandetail_id = pnd.penyiangandetail_id', 'left');
        $this->db->join('siperpus_inventaris i', 'pnd.no_inv = i.no_inv', 'left');
        $this->db->join('siperpus_buku b', 'b.no_klas = i.no_klas and b.ISBN = i.ISBN', 'left');
        $this->db->join('siperpus_asal_buku sab','sab.id=i.asal','left');
        $this->db->join('siperpus_penerbit sp','sp.kd_penerbit=b.kd_penerbit','left');
        $this->db->where('pd.penghapusan_id', $penghapusan_id);
        $this->db->where('pd.status', 1);
        return $this->db->get()->result();
    }

    public function add_penghapusan($data) {
        return $this->db->insert('penghapusan', $data);
    }

    public function update_penghapusan($id, $data) {
        $this->db->where('penghapusan_id', $id);
        return $this->db->update('penghapusan', $data);
    }

    public function add_penghapusan_detail($data) {
        return $this->db->insert('penghapusan_detail', $data);
    }

    public function update_detail_keterangan($penghapusan_id, $penyiangandetail_id, $keterangan, $author) {
        $this->db->where('penghapusan_id', $penghapusan_id);
        $this->db->where('penyiangandetail_id', $penyiangandetail_id);
        return $this->db->update('penghapusan_detail', [
                    'keterangan_tambahan' => $keterangan,
                    'author' => $author,
                    'tgl_post' => date('Y-m-d H:i:s')
        ]);
    }

    public function soft_delete_detail_by_penyiangandetail($penghapusan_id, $penyiangandetail_id) {
        $this->db->where('penghapusan_id', $penghapusan_id);
        $this->db->where('penyiangandetail_id', $penyiangandetail_id);
        return $this->db->update('penghapusan_detail', ['status' => 2]);
    }

    public function get_next_sequence($yy) {
        $this->db->select('no_penghapusan');
        $this->db->from('penghapusan');
        $this->db->where("SUBSTRING(no_penghapusan, 1, 2) = ", $yy);
        $this->db->where('status', 1);
        $this->db->order_by('no_penghapusan', 'DESC');
        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $last_doc = $query->row()->no_penghapusan;
            $last_seq = (int) substr($last_doc, -3);
            return $last_seq + 1;
        }

        return 1;
    }

    public function get_highest_deleted_status($no_penghapusan) {
        $this->db->select_max('status');
        $this->db->where('no_penghapusan', $no_penghapusan);
        $this->db->where('status >=', 2);
        $query = $this->db->get('penghapusan');

        $result = $query->row();
        return $result && $result->status ? (int) $result->status : 1;
    }

    public function soft_delete_all_details($penghapusan_id) {
        $this->db->where('penghapusan_id', $penghapusan_id);
        $this->db->where('status', 1);
        return $this->db->update('penghapusan_detail', ['status' => 2]);
    }

    /**
     * Cek apakah penyiangan_id sudah punya penghapusan aktif (status=1)
     * 
     * @param int $penyiangan_id
     * @return bool true jika sudah ada, false jika belum
     */
    public function exists_active_penghapusan_for_penyiangan($penyiangan_id) {
        $this->db->where('penyiangan_id', $penyiangan_id);
        $this->db->where('status', 1);
        return $this->db->count_all_results('penghapusan') > 0;
    }

    /**
     * Buat penghapusan baru beserta detailnya sekaligus
     * 
     * @param array $header
     * @param array $penyiangandetail_ids
     * @param array $keterangan_tambahans
     * @param string $author
     * @return int ID penghapusan yang baru dibuat
     */
    public function create_penghapusan_with_details($header, $penyiangandetail_ids, $keterangan_tambahans, $author) {
        // Insert header
        $this->db->insert('penghapusan', $header);
        $penghapusan_id = $this->db->insert_id();

        // Insert detail
        foreach ($penyiangandetail_ids as $i => $penyiangandetail_id) {
            $keterangan = $keterangan_tambahans[$i] ?? '';
            $data_detail = [
                'penghapusan_id' => $penghapusan_id,
                'penyiangandetail_id' => $penyiangandetail_id,
                'keterangan_tambahan' => $keterangan,
                'author' => $author,
                'tgl_post' => date('Y-m-d H:i:s'),
                'status' => 1
            ];
            $this->add_penghapusan_detail($data_detail);
        }

        return $penghapusan_id;
    }

    public function get_active_details($penghapusan_id) {
        $this->db->select('penghapusandetail_id, penyiangandetail_id, keterangan_tambahan');
        $this->db->from('penghapusan_detail');
        $this->db->where('penghapusan_id', $penghapusan_id);
        $this->db->where('status', 1);
        return $this->db->get()->result_array();
    }

    /**
     * Cek apakah ada no_inv yang sudah pernah dihapus (status != Reject)
     * dari penyiangandetail_id yang dipilih
     * 
     * @param array $penyiangandetail_ids Array ID detail yang akan disimpan
     * @return array Pesan error per no_inv yang konflik, atau array kosong jika aman
     */
    public function check_duplicate_no_inv_in_active_penghapusan($penyiangandetail_ids, $current_penghapusan_id = null) {
        if (empty($penyiangandetail_ids)) {
            return [];
        }

        // Ambil semua no_inv dari penyiangandetail_id yang dipilih
        $this->db->select('pd.penyiangandetail_id, i.no_inv');
        $this->db->from('penyiangan_detail pd');
        $this->db->join('siperpus_inventaris i', 'pd.no_inv = i.no_inv', 'left');
        $this->db->where_in('pd.penyiangandetail_id', $penyiangandetail_ids);
        $inventaris_map = $this->db->get()->result_array();

        // Buat map: penyiangandetail_id => no_inv
        $detail_to_noinv = [];
        foreach ($inventaris_map as $row) {
            $detail_to_noinv[$row['penyiangandetail_id']] = $row['no_inv'];
        }

        $errors = [];
        foreach ($detail_to_noinv as $penyiangandetail_id => $no_inv) {
            if (empty($no_inv))
                continue;

            $this->db->select('ph.no_penghapusan');
            $this->db->from('penghapusan_detail phd');
            $this->db->join('penghapusan ph', 'phd.penghapusan_id = ph.penghapusan_id');
            $this->db->where('phd.penyiangandetail_id', $penyiangandetail_id);
            $this->db->where('ph.status !=', 'Reject');
            $this->db->where('ph.status', 1); // aktif
            // KECUALIKAN penghapusan yang sedang di-edit
            if ($current_penghapusan_id !== null) {
                $this->db->where('ph.penghapusan_id !=', $current_penghapusan_id);
            }

            $this->db->limit(1);
            $existing = $this->db->get()->row();

            if ($existing) {
                $errors[] = "NO INV : {$no_inv} sudah pernah dilakukan penghapusan dengan no penghapusan: {$existing->no_penghapusan}";
            }
        }

        return $errors;
    }
}
