<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_siperpus_buku_prodi extends CI_Model {

  var $select= 'a.* , b.nama_penerbit, b.kota';
  var $table1 = 'siperpus_buku';
  var $table = 'siperpus_buku_prodi a, siperpus_penerbit b';
  var $where = 'b.kd_penerbit = a.kd_penerbit';
  var $column_order = array(array('no_klas','no_klas'),array('judul','judul'),array('penulis','penulis'),array('jml_buku','jml_buku')); //set column field database for datatable orderable
  var $column_search = array('b.ISBN','b.judul','b.penulis','b.no_klas'); //set column field database for datatable searchable just firstname , lastname , address are searchable
  var $order = array('tanggal' => 'desc'); // default order

  public function countAll() {
    $this->db->distinct();
    $total = $this->db->select("db.no_klas, db.ISBN")
             ->from("siperpus_buku db, siperpus_buku_prodi bp")
             ->where("db.no_klas = bp.no_klas AND db.ISBN = bp.ISBN")->get()->num_rows();
    return $total;
  }
  public function getValue($b,$text){
		foreach($b as $v)
		{
			if(in_array($text, $v, true))
			{
				return $v[1];
			}
		}
		return false;
	}
  private function getDatatablesQuery(){
    if ($this->input->post('datatable[query][prodi]')) {
      $join="(SELECT DISTINCT ISBN,no_klas,idmspst FROM siperpus_buku_prodi) a";
    }else {
      $join="(SELECT DISTINCT ISBN,no_klas FROM siperpus_buku_prodi) a";
    }

    $this->db->select("b.no_klas, b.ISBN,b.judul, b.thn_terbit, b.kd_penerbit, b.jml_buku, c.nama_penerbit");
    $this->db->from("siperpus_buku b")
             ->join($join,"a.ISBN = b.ISBN AND a.no_klas = b.no_klas")
             ->join("siperpus_penerbit c","c.kd_penerbit = b.kd_penerbit");
    $i = 0;
    //checking if there's searching :
    foreach ($this->column_search as $item) // loop column
    {
      if($this->input->post('datatable[query][generalSearch]')) // if datatable send POST for search
      {
        if($i===0) // first loop
        {
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $this->input->post('datatable[query][generalSearch]'));
        }
        else
        {
          $this->db->or_like($item, $this->input->post('datatable[query][generalSearch]'));
        }
        if(count($this->column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }
    if($this->input->post('datatable[query][klas]')){
			$this->db->like('a.no_klas', $this->input->post('datatable[query][klas]'), 'after');
		}
		if($this->input->post('datatable[query][kel]')){
			$this->db->where('idkategori', $this->input->post('datatable[query][kel]'));
		}
    if($this->input->post('datatable[query][prodi]')){
			$this->db->where('a.idmspst', $this->input->post('datatable[query][prodi]'));
		}
    //filtering order :

    $val=$this->getValue($this->column_order,$this->input->post('datatable[sort][field]'));
		if($val!= false) // here order processing
		{
			$this->db->order_by($val, $this->input->post('datatable[sort][sort]'));
		}
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
  }
  function getDatatables(){
    $this->getDatatablesQuery();
    if($this->input->post('datatable[pagination][perpage]') != -1)
      $this->db->limit($this->input->post('datatable[pagination][perpage]'), ($this->input->post('datatable[pagination][perpage]')*(($this->input->post('datatable[pagination][page]')-1))));

    $query = $this->db->get()->result_array();
    for ($i=0; $i < sizeof($query); $i++) {
      $prodi = $this->db->select("b.nmmspst")
                        ->from("vwprodi b")
                        ->join("siperpus_buku_prodi a", "b.idmspst = a.idmspst")
                        ->where(array('ISBN' => $query[$i]['ISBN'], 'no_klas'=> $query[$i]['no_klas']))->get()->result_array();
      for ($j=0; $j < sizeof($prodi); $j++) {
        $query[$i]['prodi'][]=$prodi[$j]['nmmspst'];
      }
    }
    return $query;
  }
  function countFiltered(){
    $this->getDatatablesQuery();
    $query = $this->db->get();
    return $query->num_rows();
  }

  function addBukuProdi($data) {
    $this->db->insert('siperpus_buku_prodi', $data);
  }
  function getBukuProdiByISBN($ISBN,$no_klas)
  {
    return $this->db->select('idmspst')->from('siperpus_buku_prodi')->where(array('ISBN' => $ISBN, 'no_klas'=> $no_klas))->get()->result_array();
  }
  function hapusBukuProdiByISBN($ISBN,$no_klas) {
      $this->db->where(array('ISBN' => $ISBN, 'no_klas'=> $no_klas))->delete('siperpus_buku_prodi');
  }
  function getBukuByISBN($id,$noklas) {
		 $hasil = $this->db->query("SELECT *,(select nmmspst from vwprodi where vwprodi.idmspst=siperpus_buku_prodi.idmspst) as namaprodi  FROM siperpus_buku_prodi where isbn='$id' and no_klas='$noklas'");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
   }
   function getJumlahBukuProdiKlas($prodi,$klas) {
		 $hasil = $this->db->query("SELECT * FROM siperpus_buku_prodi where idmspst='$prodi' and no_klas like '$klas%'  ");
        if ($hasil->num_rows() > 0) {
            return $hasil->num_rows();
        }else{
			return 0;
		}
    }
	 function getJumlahBukuProdi($prodi) {
		 $hasil = $this->db->query("SELECT * FROM siperpus_buku_prodi where idmspst='$prodi'");
        if ($hasil->num_rows() > 0) {
            return $hasil->num_rows();
        }else{
			return 0;
		}
    }
}
