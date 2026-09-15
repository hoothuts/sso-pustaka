<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_siperpus_libur extends CI_Model {

   function getLibur($year) {
		 $hasil = $this->db->query("SELECT DATE_FORMAT(tgl_libur,'%m/%d/%Y') as tgl_libur FROM siperpus_libur where year(tgl_libur)='$year'");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row->tgl_libur;
            }
            return $data;
        }
    }
	function haveLibur($date,$date2) {
		 $hasil = $this->db->query("SELECT DATE_FORMAT(tgl_libur,'%m/%d/%Y') as tgl_libur FROM siperpus_libur where tgl_libur > '$date' and tgl_libur<='$date2' ");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row->tgl_libur;
            }
            return $data;
        }
    }
	function isLibur($date) {
		 $hasil = $this->db->query("SELECT * FROM siperpus_libur where tgl_libur='$date'");
        if ($hasil->num_rows() > 0) {
            return true;
        }else{
			return false;
		}
    }
	 function addLibur($data){
         $this->db->insert('siperpus_libur', $data);
    }
   function hapusLibur($date)
    {
      $this->db->where('tgl_libur',$date);
      $this->db->delete('siperpus_libur');
    }
	function hapusLiburMonth($month,$year)
    {
      $this->db->where('MONTH(tgl_libur)',$month);
      $this->db->where('YEAR(tgl_libur)',$year);
      $this->db->delete('siperpus_libur');
    }
	
}