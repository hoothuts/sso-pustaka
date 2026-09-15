<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_siperpus_anggota extends CI_Model {

   function getAnggotaAll() {
		 $hasil = $this->db->query("SELECT * FROM siperpus_anggota  order by no_anggota asc");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
}