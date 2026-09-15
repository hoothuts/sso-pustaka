<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_msmhs extends CI_Model
{
    var $table = 'sim_akademik.msmhs';

    function getmahasiswaByNo($nomhs)
    {
        $this->db->select('mhs.NIMHSMSMHS,
        mhs.NMMHSMSMHS,
        mhs.TPLHRMSMHS,
        mhs.TGLHRMSMHS,
        mhs.TAHUNMSMHS,
        mhs.KDJEKMSMHS,
        mhs.STMHSMSMHS,
        mhs.ALAMATLENGKAP,
        mhs.TELP,
        CONCAT(tbkod.NMKODTBKOD,"-", mspst.NMPSTMSPST) AS kelas');
        $this->db->from($this->table . ' as mhs');
        $this->db->join('sim_akademik.mspst as mspst', 'mhs.KDJENMSMHS = mspst.KDJENMSPST and mhs.KDPSTMSMHS = mspst.KDPSTMSPST');
        $this->db->join("sim_akademik.tbkod as tbkod", "mhs.KDJENMSMHS = tbkod.KDKODTBKOD and tbkod.KDAPLTBKOD = '04'");
        $this->db->where('mhs.NIMHSMSMHS', $nomhs);
        $this->db->where_in('mhs.STMHSMSMHS', ['A', 'T', 'L']);
        return $this->db->get()->row();
    }

    function serachmhs($text)
    {
        $this->db->select('
        mhs.NIMHSMSMHS,
        mhs.NMMHSMSMHS,
        mhs.TPLHRMSMHS,
        mhs.TGLHRMSMHS,
        mhs.TAHUNMSMHS,
        mhs.KDJEKMSMHS,
        mhs.STMHSMSMHS,
        mhs.ALAMATLENGKAP,
        mhs.TELP
    ');
        $this->db->from($this->table . ' as mhs');

        // Menambahkan kondisi pencarian
        $this->db->group_start();
        $this->db->like('mhs.NIMHSMSMHS', $text);
        $this->db->or_like('mhs.NMMHSMSMHS', $text);
        $this->db->group_end();

        return $this->db->get()->result();
    }
}
