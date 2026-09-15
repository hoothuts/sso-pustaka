<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Md_bepus_syarat extends CI_Model {

    var $table = 'bepus_syarat';

    public function get_all_active_hierarchical() {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('status', 1);
        $this->db->order_by('urutan', 'ASC');
        $query = $this->db->get();
        $all = $query->result();

        // Bangun struktur bertingkat
        $parents = [];
        $children = [];

        foreach ($all as $row) {
            if ($row->level == 1) {
                $parents[$row->bepussyarat_id] = $row;
            } else {
                $children[$row->parent_id][] = $row;
            }
        }

        $result = [];
        foreach ($parents as $parent) {
            $result[] = $parent;
            if (isset($children[$parent->bepussyarat_id])) {
                foreach ($children[$parent->bepussyarat_id] as $child) {
                    $result[] = $child;
                }
            }
        }

        return $result;
    }
    
    /**
    * Untuk Preview Form - Hanya tampilkan yang is_aktif = 'Ya'
    */
    public function get_active_for_preview() {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('status', 1);
        $this->db->where('is_aktif', 'Ya');
        $this->db->order_by('urutan', 'ASC');
        $query = $this->db->get();
        $all = $query->result();

        // Bangun struktur bertingkat (Parent + Child)
        $parents = [];
        $children = [];

        foreach ($all as $row) {
            if ($row->level == 1) {
                $parents[$row->bepussyarat_id] = $row;
            } else {
                $children[$row->parent_id][] = $row;
            }
        }

        $result = [];
        foreach ($parents as $parent) {
            $result[] = $parent;
            if (isset($children[$parent->bepussyarat_id])) {
                foreach ($children[$parent->bepussyarat_id] as $child) {
                    $result[] = $child;
                }
            }
        }

        return $result;
    }

    public function get_parent_options() {
        $this->db->where('level', 1);
        $this->db->where('status', 1);
        $this->db->order_by('urutan', 'ASC');
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['bepussyarat_id' => $id])->row();
    }

    public function check_duplicate($persyaratan, $exclude_id = null) {
        $this->db->where('persyaratan', $persyaratan);
        $this->db->where('status', 1);
        if ($exclude_id) {
            $this->db->where('bepussyarat_id !=', $exclude_id);
        }
        return $this->db->get($this->table)->row();
    }

    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        $this->db->where('bepussyarat_id', $id);
        return $this->db->update($this->table, $data);
    }

    public function nonaktifkan($id) {
        $this->db->where('bepussyarat_id', $id);
        return $this->db->update($this->table, ['is_aktif' => 'Tidak', 'tgl_last_update' => date('Y-m-d H:i:s'), 'last_update_by' => $this->session->userdata('idsys')]);
    }

    public function soft_delete($id) {
        $this->db->where('bepussyarat_id', $id);
        return $this->db->update($this->table, ['status' => 2, 'tgl_last_update' => date('Y-m-d H:i:s'), 'last_update_by' => $this->session->userdata('idsys')]);
    }
}