<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_chat extends CI_Model
{

    // Menyimpan data pengunjung baru
    public function simpan_sesi($data)
    {
        $this->db->insert('chat_sesi', $data);
        return $this->db->insert_id();
    }






    public function simpan_pesan($data)
    {
        return $this->db->insert(
            'chat_pesan',
            $data
        );
    }

    public function ambil_pesan($chatsesi_id)
    {
        // Pastikan is_file disertakan dalam select
        $this->db->select('pengirim as sender_type, isi_pesan as message, tgl_kirim as created_at, is_file, readby, tgl_read, sendby');
        $this->db->where('chatsesi_id', $chatsesi_id);
        $this->db->order_by('tgl_kirim', 'ASC');
        return $this->db->get('chat_pesan')->result();
    }

    public function list_sesi_aktif()
    {
        // Kita gunakan subquery untuk menghitung pesan yang belum dibaca dari 'user'
        $this->db->select('chat_sesi.*, 
        (SELECT COUNT(*) 
         FROM chat_pesan 
         WHERE chat_pesan.chatsesi_id = chat_sesi.chatsesi_id 
         AND chat_pesan.pengirim = "user" 
         AND chat_pesan.sudah_dibaca = 0) as jumlah_unread');
        $this->db->from('chat_sesi');
        $this->db->where('status', 1);
        $this->db->order_by('tgl_buat', 'DESC');
        return $this->db->get()->result();
    }

    public function update_sesi($chatsesi_id, $data)
    {
        $this->db->where('chatsesi_id', $chatsesi_id);
        return $this->db->update('chat_sesi', $data);
    }

    public function get_total_unread_global()
    {
        $this->db->where('pengirim', 'user');
        $this->db->where('sudah_dibaca', 0);
        $this->db->where('status', 1);
        return $this->db->count_all_results('chat_pesan');
    }
}
