<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_siperpus_kategori extends CI_Model
{

    function getKategoriAll()
    {
        $hasil = $this->db->query("SELECT * FROM siperpus_kategori order by nmkategori asc");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
    function getKategoriById($id)
    {
        $hasil = $this->db->query("SELECT * FROM siperpus_kategori where idkategori='$id' order by nmkategori asc");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
}
