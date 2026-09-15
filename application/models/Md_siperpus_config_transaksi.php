<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_siperpus_config_transaksi extends CI_Model {

   function getConfigAll($cat) {
		 $hasil = $this->db->query("SELECT * FROM siperpus_config_transaksi where cat='$cat'");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	function updateConfig($param,$data){
        $this->db->where('cat', $param);
        $this->db->update('siperpus_config_transaksi', $data);         
    }
}