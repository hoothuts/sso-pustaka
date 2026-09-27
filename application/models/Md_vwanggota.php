<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Md_vwanggota extends CI_Model
{

  public function getValue($b, $text)
  {
    foreach ($b as $v) {
      if (in_array($text, $v, true)) {
        return $v[1];
      }
    }
    return false;
  }
  private function getDatatablesQuery()
  {
    if ($this->input->post('datatable[query][jenis]') == 'pegawai') {
      $table = 'pegawai dos';
      $column_order = array(array('nip', 'dos.nip'), array('nama', 'dos.nama'), array('berlaku', 'ang.berlaku_sampai'));
      $column_search = array('dos.nip', 'dos.nama', 'ang.berlaku_sampai');
      $order = array('dos.nama' => 'asc'); // default order
    } else if ($this->input->post('datatable[query][jenis]') == 'anggota+luar') {
      $table = 'siperpus_anggota_luar al';
      $column_order = array(array('nip', 'al.noid'), array('nama', 'al.nama'), array('berlaku', 'ang.berlaku_sampai'));
      $column_search = array('al.noid', 'al.nama', 'ang.berlaku_sampai');
      $order = array('nama' => 'asc'); // default order
    } else {
      $table = 'vwsiswa sis';
      $column_order = array(array('nip', 'sis.nis'), array('nama', 'sis.nama'), array('kelas', 'sis.kelas'), array('berlaku', 'ang.berlaku_sampai'));
      $column_search = array('sis.nis', 'sis.nama', 'sis.nama', 'ang.berlaku_sampai');
      $order = array('sis.nama' => 'asc'); // default order
    }
    $this->db->from($table);

    $i = 0;

    foreach ($column_search as $item) // loop column
    {
      if ($this->input->post('datatable[query][generalSearch]')) // if datatable send POST for search
      {
        if ($i === 0) // first loop
        {
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $this->input->post('datatable[query][generalSearch]'));
        } else {
          $this->db->or_like($item, $this->input->post('datatable[query][generalSearch]'));
        }
        if (count($column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }

    $val = $this->getValue($column_order, $this->input->post('datatable[sort][field]'));
    if ($val != false) // here order processing
    {
      $this->db->order_by($val, $this->input->post('datatable[sort][sort]'));
    } else if (isset($this->order)) {
      $order = $this->order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  function getDatatables()
  {
    $this->getDatatablesQuery();
    if ($this->input->post('datatable[pagination][perpage]') != -1)
      $this->db->limit($this->input->post('datatable[pagination][perpage]'), ($this->input->post('datatable[pagination][perpage]') * (($this->input->post('datatable[pagination][page]') - 1))));
    
    if ($this->input->post('datatable[query][jenis]') == 'pegawai') {
        $this->db->join('vwanggota ang', 'ang.no_anggota = dos.nip');
    } else if ($this->input->post('datatable[query][jenis]') == 'anggota+luar') {
        $this->db->join('vwanggota ang', 'ang.no_anggota = al.noid');
    } else {
        $this->db->join('vwanggota ang', 'ang.no_anggota = sis.nis');
    }
    $query = $this->db->get();
    return $query->result();
  }
  private function getLaporanQuery($jenis, $src)
  {
    if ($jenis == 'pegawai') {
        $table = 'pegawai dos';
        $column_search = array('dos.nip', 'dos.nama', 'ang.berlaku_sampai');
        $order = array('dos.nama' => 'asc'); // default order
    } else if ($jenis == 'anggota+luar') {
        $table = 'siperpus_anggota_luar al';
        $column_search = array('al.noid', 'al.nama', 'ang.berlaku_sampai');
        $order = array('nama' => 'asc'); // default order
    } else {
        $table = 'vwsiswa sis';
        $column_search = array('sis.nis', 'sis.nama', 'sis.nama', 'ang.berlaku_sampai');
        $order = array('sis.nama' => 'asc'); // default order
    }
    $this->db->from($table);

    $i = 0;

    foreach ($column_search as $item) // loop column
    {
      if ($src) // if datatable send POST for search
      {
        if ($i === 0) // first loop
        {
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $src);
        } else {
          $this->db->or_like($item, $src);
        }
        if (count($column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }
  }

  function getLaporan($jenis, $src)
  {
    $this->getLaporanQuery($jenis, $src);
    if ($jenis == 'pegawai') {
      $this->db->join('vwanggota ang', 'ang.no_anggota = dos.nip');
    } else if ($jenis == 'anggota+luar') {
      $this->db->join('vwanggota ang', 'ang.no_anggota = al.noid');
    } else {
      $this->db->join('vwanggota ang', 'ang.no_anggota = sis.nis');
    }
    $query = $this->db->get();
    return $query->result();
  }
  function countFiltered()
  {
    $this->getDatatablesQuery();
    if ($this->input->post('datatable[query][jenis]') == 'pegawai') {
      $this->db->join('vwanggota ang', 'ang.no_anggota = dos.nip');
    } else if ($this->input->post('datatable[query][jenis]') == 'anggota+luar') {
      $this->db->join('vwanggota ang', 'ang.no_anggota = al.noid');
    } else {
      $this->db->join('vwanggota ang', 'ang.no_anggota = sis.nis');
    }
    $query = $this->db->get();
    return $query->num_rows();
  }

  function getAnggotaById($jenis, $id)
  {
    if ($jenis == 'pegawai') {
      $hasil = $this->db->query("SELECT * FROM `vwanggota` ang join pegawai dos on ang.no_anggota = dos.nip where ang.no_anggota='$id'");
    } else if ($jenis == 'anggota+luar') {
      $hasil = $this->db->query("SELECT * FROM `vwanggota` ang, siperpus_anggota_luar al where ang.nis='$id' and ang.nis = al.noid");
    } else {
      $hasil = $this->db->query("SELECT * FROM `vwanggota` ang, vwsiswa sis where ang.nis='$id' and ang.nis = sis.nis");
    }
    if ($hasil->num_rows() > 0) {
      foreach ($hasil->result() as $row) {
        $data[] = $row;
      }
      return $data;
    }
  }

  function getInfoAnggota($no_anggota)
  {
    return $this->db->query(
      "SELECT v.kategori, COALESCE(s.nama, p.nama) AS nama
       FROM vwanggota v
       LEFT JOIN vwsiswa s ON s.nis = v.no_anggota
       LEFT JOIN pegawai p ON p.nip = v.no_anggota
       WHERE v.no_anggota = ? LIMIT 1",
      array($no_anggota)
    )->row();
  }

  function getAnggotaAll()
  {
    $hasil = $this->db->query("SELECT *,(SELECT nama FROM `vwsiswa` WHERE `nis` = vwang.`nis` AND nama IS NOT NULL) as nama FROM `vwanggota` vwang");
    if ($hasil->num_rows() > 0) {
      foreach ($hasil->result() as $row) {
        $data[] = $row;
      }
      return $data;
    }
  }
  function getJumlahAng()
  {
    $hasil = $this->db->query("SELECT * FROM vwanggota");
    if ($hasil->num_rows() > 0) {
      return $hasil->num_rows();
    } else {
      return 0;
    }
  }
  function getJumlahVisit()
  {
    $hasil = $this->db->query("SELECT count(*) as jumlah FROM visitors WHERE visitors.date != '0000-00-00'");
    $data=$hasil->row();
    return $data->jumlah;
    
  }

  function getAllAnggotaByNoOrNamabACKUP($no)
  {
    $this->db->select('v.no_anggota');
    $this->db->select("CASE 
                           WHEN vd.nip IS NOT NULL THEN vd.nama 
                           WHEN vs.nis IS NOT NULL THEN vs.nama 
                       END as nama", FALSE);
    $this->db->from('vwanggota as v');
    $this->db->join('pegawai vd', 'v.no_anggota = vd.nip', 'left');
    $this->db->join('vwsiswa vs', 'v.no_anggota = vs.nis', 'left');
    $this->db->group_start();
    $this->db->like('lower(v.no_anggota)', strtolower($no));
    $this->db->group_end();

    $this->db->where('v.status', 1);
    $this->db->where('(vs.nis IS NOT NULL OR vd.nip IS NOT NULL)');
    $this->db->order_by('v.no_anggota');
    $hasil = $this->db->get()->result();
    return $hasil;
  }

  function getAllAnggotaByNoOrNama($no)
  {
    $this->db->select('v.no_anggota,vs.nama');

    $this->db->from('vwanggota as v');
    $this->db->join('vwsiswa vs', 'v.no_anggota = vs.nis');
    $this->db->group_start();
    $this->db->like('lower(v.no_anggota)', strtolower($no));
    $this->db->or_like('lower(vs.nama)', strtolower($no));
    $this->db->group_end();
    $this->db->where('v.status', 1);

    $this->db->order_by('v.no_anggota');
    $hasil = $this->db->get()->result();
    return $hasil;
  }
}
