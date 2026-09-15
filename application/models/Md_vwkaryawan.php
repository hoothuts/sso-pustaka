<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_vwkaryawan extends CI_Model {

   function getKaryawanAll() {
		 $hasil = $this->db->query("SELECT * FROM vwkaryawan order by nama asc");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	function getKaryawanById($id) {
        $hasil = $this->db->get_where('vwkaryawan', array('nip' => $id))->result();
        $data = $hasil;
        return $data;
    }
}