<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_siswa extends CI_Model {

   function getSiswaAll() {
		 $hasil = $this->db->query("SELECT * FROM siswa order by nis asc");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
    function getSiswaById($id) {
        $hasil = $this->db->get_where('siswa', array('nis' => $id))->result();
        $data = $hasil;
        return $data;
    }
    
    function getSiswaByNim($nim) {
        $hasil = $this->db->get_where('siswa', array('nis' => $nim))->row();
        $data = $hasil;
        return $data;
    }
}