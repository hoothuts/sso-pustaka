<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_siperpus_user extends CI_Model {

   function getUserAll() {
		 $hasil = $this->db->query("SELECT * FROM Md_siperpus_user where order by username asc");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
}