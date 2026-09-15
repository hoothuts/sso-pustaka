<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_siperpus_setting extends CI_Model {

    function getSettingAll() {
        $hasil = $this->db->query("SELECT * FROM siperpus_setting order by urutsetting asc");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function updateSetting($param, $data) {
        $this->db->where('kodesetting', $param);
        $this->db->update('siperpus_setting', $data);
    }
    
    function getSettingbyKode($kode){
         return $this->db->from('siperpus_setting')->where('kodesetting', $kode)->get()->row();
    }
}
