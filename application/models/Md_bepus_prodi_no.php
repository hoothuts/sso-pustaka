<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Md_bepus_prodi_no extends CI_Model {

    var $table = 'bepus_prodi_no';

    public function get_datatables($search = '', $limit = 10, $offset = 0, $field = 'bepusprodino_id', $sort = 'DESC')
    {
        $this->db->select("
            bp.*,
            m.NMPSTMSPST,
            m.KDJENMSPST
        ");

        $this->db->from($this->table.' bp');

        $this->db->join(
            'sim_akademik.mspst m',
            'm.KDPSTMSPST = bp.kdpst',
            'left'
        );

        $this->db->where('bp.status',1);

        if($search)
        {
            $this->db->group_start();
            $this->db->like('m.NMPSTMSPST',$search);
            $this->db->or_like('bp.kode_nomor',$search);
            $this->db->or_like('bp.tahun',$search);
            $this->db->group_end();
        }

        $this->db->order_by($field,$sort);
        $this->db->limit($limit,$offset);

        return $this->db->get()->result();
    }

    public function count_filtered($search='')
    {
        $this->db->from($this->table.' bp');

        $this->db->join(
            'sim_akademik.mspst m',
            'm.KDPSTMSPST = bp.kdpst',
            'left'
        );

        $this->db->where('bp.status',1);

        if($search)
        {
            $this->db->group_start();
            $this->db->like('m.NMPSTMSPST',$search);
            $this->db->or_like('bp.kode_nomor',$search);
            $this->db->or_like('bp.tahun',$search);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {
        return $this->db
            ->get_where(
                $this->table,
                ['bepusprodino_id'=>$id]
            )
            ->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table,$data);
    }

    public function update($id,$data)
    {
        $this->db->where('bepusprodino_id',$id);
        return $this->db->update($this->table,$data);
    }

    public function soft_delete($id)
    {
        $this->db->where('bepusprodino_id',$id);
        return $this->db->update(
            $this->table,
            ['status'=>2]
        );
    }

    public function cek_duplikat($kdpst,$tahun,$exclude_id=null)
    {
        $this->db->where('kdpst',$kdpst);
        $this->db->where('tahun',$tahun);
        $this->db->where('status',1);

        if($exclude_id != null)
        {
            $this->db->where(
                'bepusprodino_id !=',
                $exclude_id
            );
        }

        return $this->db->get($this->table)->row();
    }

    public function search_prodi($text)
    {
        $this->db->select("
            KDPSTMSPST,
            NMPSTMSPST,
            KDJENMSPST
        ");

        $this->db->from('sim_akademik.mspst');

        $this->db->group_start();
        $this->db->like('NMPSTMSPST',$text);
        $this->db->or_like('KDPSTMSPST',$text);
        $this->db->group_end();

        $this->db->order_by('NMPSTMSPST','ASC');

        return $this->db->get()->result();
    }
    
    public function get_prodi_by_kode($kdpst)
    {
        return $this->db
            ->where('KDPSTMSPST',$kdpst)
            ->get('sim_akademik.mspst')
            ->row();
    }

}