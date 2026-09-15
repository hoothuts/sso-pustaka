<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_siperpus_buku extends CI_Model {

    var $table = 'siperpus_buku';
    var $column_search = array('no_klas', 'judul', 'siperpus_penerbit.nama_penerbit','tajuk','tajuksubyek');

    public function record_count($word, $limit, $id) {
        $this->getDatatablesQuery($word);
        $this->db->join('siperpus_penerbit', 'siperpus_buku.kd_penerbit = siperpus_penerbit.kd_penerbit');
        $this->db->where('siperpus_buku.idkategori !=', 20);
        $this->db->where('siperpus_buku.displayed = 0');
        $query = $this->db->get();
        return $query->num_rows();
    }

    private function getDatatablesQuery($word) {
        //$this->db->select('no_klas,judul,siperpus_buku.kd_penerbit,nama_penerbit,jml_buku,review');
        $this->db->from($this->table);
        //$this->db->where('no_klas', $id);
        if ($word) {
            $src = $word;
            $i = 0;
            foreach ($this->column_search as $item) { // loop column
                if ($src) { // if datatable send POST for search

                    if ($i === 0) { // first loop
                        $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                        $this->db->like($item, $src);
                    } else {
                        $this->db->or_like($item, $src);
                    }
                    if (count($this->column_search) - 1 == $i) //last loop
                        $this->db->group_end(); //close bracket
                }
                $i++;
            }
        }
        if ($this->session->userdata('judul')) {
            $this->db->like('siperpus_buku.judul', $this->session->userdata('judul'));
        }
        if ($this->session->userdata('penulis')) {
            $this->db->like('siperpus_buku.penulis', $this->session->userdata('penulis'));
        }
        if ($this->session->userdata('seri')) {
            $this->db->like('siperpus_buku.seri', $this->session->userdata('seri'));
        }
        if ($this->session->userdata('isbn')) {
            $this->db->like('siperpus_buku.ISBN', $this->session->userdata('isbn'));
        }
        if ($this->session->userdata('kategori') && $this->session->userdata('kategori') != 'all') {
            $this->db->like('siperpus_buku.idkategori', $this->session->userdata('kategori'));
        }
    }

    // Fetch data according to per_page limit.
    public function fetch_data($word, $limit, $start) {

        $this->getDatatablesQuery($word);
        $offset = (intval($start) - 1) * intval($limit);
        if ($offset < 0) {
            $offset = 0;
        }
        $this->db->limit($limit, $offset);
        $this->db->join('siperpus_penerbit', 'siperpus_buku.kd_penerbit = siperpus_penerbit.kd_penerbit');
        $this->db->where('siperpus_buku.idkategori !=', 20);
        $this->db->where('siperpus_buku.displayed = 0');
        $this->db->order_by("siperpus_buku.thn_terbit", "desc");
        $this->db->order_by("siperpus_buku.tanggal", "desc");
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }

            return $data;
        }
        return false;
    }

    public function fetch_data2($limit, $start) {

        $hasil = $this->db->query("SELECT * FROM siperpus_buku");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function getBuku() {
        $hasil = $this->db->query("SELECT * FROM siperpus_buku order by judul asc limit 20");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function getBukuById($id) {
        $hasil = $this->db->query("SELECT * FROM siperpus_buku where isbn in (select isbn from siperpus_inventaris where no_inv='$id') ");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function getDataBukuByISBN($id) {
        $hasil = $this->db->query("SELECT * FROM siperpus_buku where isbn='$id'");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function getBukuByISBN($id, $noklas) {
        $hasil = $this->db->query("SELECT *,(select nama_penerbit from siperpus_penerbit where siperpus_penerbit.kd_penerbit=siperpus_buku.kd_penerbit) as penerbit,(select kota from siperpus_penerbit where siperpus_penerbit.kd_penerbit=siperpus_buku.kd_penerbit) as kota_penerbit,(select nama from siperpus_bahasa where siperpus_bahasa.id=siperpus_buku.bahasa) as bhs FROM siperpus_buku where isbn='$id' and no_klas='$noklas'");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }

    function hapusBuku($data) {
        $this->db->where('id', $data);
        $this->db->delete('siperpus_buku');
    }

    function updateBuku($param, $data) {
        $this->db->where('id', $param);
        $this->db->update('siperpus_buku', $data);
    }

    function getJumlahBuku() {
        $hasil = $this->db->query("SELECT count(*) as jumlah FROM siperpus_buku  ");
        $data = $hasil->row();
        return $data->jumlah;
    }

    function getBukuByKlas($klas) {
        return $this->db->from('siperpus_buku')->like('no_klas', $klas, 'after')->get()->num_rows();
    }

    function getBukuByKat($kat) {
        return $this->db->from('siperpus_buku')->where('idkategori', $kat)->get()->num_rows();
    }

    function getBukuByBahasa($id) {
        return $this->db->from('siperpus_bahasa')->where('id', $id)->get()->num_rows();
    }

    function getBukuByAsal($id) {
        return $this->db->from('siperpus_asal_buku')->where('id', $id)->get()->num_rows();
    }

    // function getBukuByDipinjam($id) {
    //   return $this->db->from('siperpus_asal_buku')->where('id',$id)->get()->num_rows();
    // }
}
