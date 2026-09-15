<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class md_siperpus_sysmodul extends CI_Model {
	function getModulAll() {
		 $hasil = $this->db->query("SELECT * FROM siperpus_sysmodul2 order by idsysmodul asc");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
	}
	function getModulNoAkses($grp) {
		 $hasil = $this->db->query("SELECT * FROM siperpus_sysmodul2 where idsysmodul not in(select idsysmodul from siperpus_sysgrant where idsysgroup='$grp') order by idsysmodul asc");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
	}
	function getModulById($modul) {
		 $hasil = $this->db->query("SELECT * FROM siperpus_sysmodul2 where idsysmodul='$modul'");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
}