<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_siperpus_dosen extends CI_Model {

   function getDosenAll() {
		 $hasil = $this->db->query("SELECT * FROM siperpus_dosen order by nama asc");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
}