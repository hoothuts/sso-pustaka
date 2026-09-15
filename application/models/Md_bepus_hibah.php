<?php
class Md_bepus_hibah extends CI_Model {

    private $table = 'bepus_request_hibah';

    public function insert_batch($data){
        return $this->db->insert_batch($this->table, $data);
    }

    public function delete_by_request($bepusrequest_id){
        return $this->db->where('bepusrequest_id', $bepusrequest_id)
                        ->delete($this->table);
    }

    public function get_by_request($bepusrequest_id){
        return $this->db->where('bepusrequest_id', $bepusrequest_id)
                        ->where('status',1)
                        ->get($this->table)->result();
    }
}