<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Md_konfigurasi_web extends CI_Model {

    private $table = 'konfigurasi_web';

    public function get_all()
    {
        return $this->db
            ->where('status', 1)
            ->order_by('konfigurasiweb_id','ASC')
            ->get($this->table)
            ->result();
    }

    public function update_batch($data)
    {
        return $this->db->update_batch($this->table, $data, 'konfigurasiweb_id');
    }
    
    public function get_by_jenis($jenis)
    {
        return $this->db
            ->where('jenis_konfigurasi', $jenis)
            ->where('status', 1)
            ->get($this->table)
            ->row();
    }

}