<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_mspst extends CI_Model {

	function getKelas() {
        $hasil = $this->db->get_where('mspst')->result();
        $data = $hasil;
        return $data;
    }
}
