<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Md_menu extends CI_Model
{
    protected $table = 'menu';

    function getAllMenu()
    {
        $this->db->where("status", 1)
            ->order_by("level", "asc")
            ->order_by("urutan", "asc")
            ->order_by("nama_menu", "asc");
        $hasil = $this->db->get($this->table);
        return $hasil->result();
    }

    function getAllActiveMenu()
    {
        $this->db->where("status", 1)
            ->where("is_active", 1)
            ->order_by("urutan", "asc")
            ->order_by("nama_menu", "asc");

        return $this->db->get($this->table)->result();
    }

    function getAllActiveParentMenu()
    {
        $this->db->where("status", 1)
            ->where("is_active", 1)
            ->where("menuparent_id", null)
            ->order_by("urutan", "asc")
            ->order_by("nama_menu", "asc");

        return $this->db->get($this->table)->result();
    }

    function getAllActiveChildMenu($parent_id)
    {
        if ($parent_id) {
            $this->db->where('menuparent_id', $parent_id);
        }

        $this->db->where("status", 1)
            ->where("is_active", 1)
            ->order_by("urutan", "asc")
            ->order_by("nama_menu", "asc");

        return $this->db->get($this->table)->result();
    }

    function getMenuById($menu_id)
    {
        $this->db->where("menu_id", $menu_id);
        return $this->db->get($this->table)->row();
    }

    function addData($data)
    {
        $this->db->insert('menu', $data);

        return $this->db->insert_id();
    }
    function updateData($id, $data)
    {
        $this->db->where('menu_id', $id);
        $this->db->update('menu', $data);

        return true;
    }
}
