<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_siperpus_inventaris extends CI_Model {

  var $select= 'i.*, b.judul';
  var $table1 = 'siperpus_inventaris';
  var $table = 'siperpus_inventaris i, siperpus_buku b';
  var $where = 'i.ISBN = b.ISBN AND i.no_klas = b.no_klas';
  var $column_order = array(array('no_inv','no_inv'),array('tgl_inv','tgl_inv'),array('asal','asal')); //set column field database for datatable orderable
  var $column_search = array('no_inv','no_barcode','judul'); //set column field database for datatable searchable just firstname , lastname , address are searchable
  var $order = array('tanggal' => 'desc'); // default order


  public function countAll() {
    $this->db->from($this->table1);
    return $this->db->count_all_results();
  }
  function countFiltered(){
    $this->getDatatablesQuery();
    $query = $this->db->get();
    return $query->num_rows();
  }
  private function getDatatablesQuery(){
    $sql="(SELECT i.*, ( SELECT judul FROM siperpus_buku b WHERE b.no_klas = i.no_klas AND b.ISBN = i.ISBN limit 1) AS judul FROM siperpus_inventaris i) as inventaris";
    $this->db->from($sql);
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
  		$this->db->like('no_klas', $this->input->post('datatable[query][klas]'), 'after');
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
  $query = $this->db->get();
  return $query->result();
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
  function getNumRowInvByNoKlasISBN($no_klas,$isbn){
    $this->db->where(array('ISBN' => $isbn, 'no_klas'=> $no_klas));
    return $this->db->get('siperpus_inventaris')->num_rows();
  }
  function getNoBarcode(){
    $this->db->select_max('no_barcode');
    return $this->db->get('siperpus_inventaris')->row()->no_barcode;
  }
  function getInventarisByBarcode($barcode){
    $hasil = $this->db->get_where('siperpus_inventaris', array('no_barcode' => $barcode))->result();
    $data = $hasil;
    return $data;
  }
  function getInventarisAll() {
    $hasil = $this->db->query("SELECT *,(SELECT judul FROM `siperpus_buku` WHERE ISBN = inv.ISBN) as judul FROM siperpus_inventaris inv WHERE ISBN ='978.979.044.092.0' order by no_inv asc");
    if ($hasil->num_rows() > 0) {
      foreach ($hasil->result() as $row) {
        $data[] = $row;
      }
      return $data;
    }
  }
  function addInventaris($data){
         $this->db->insert('siperpus_inventaris', $data);
    }
  function hapusInventaris($data){
      $this->db->where('no_barcode',$data);//jangan hapus dengan menggunakan isbn, gunakan no_barcode
      $this->db->delete('siperpus_inventaris');
    }
  function updateInventaris($data){
        $this->db->where('no_barcode', $data['no_barcode']);
        $this->db->update('siperpus_inventaris', $data);
    }
  function getInventarisById($id) {
        $query = "SELECT *,(SELECT judul FROM `siperpus_buku` WHERE ISBN = inv.ISBN AND no_klas = inv.no_klas) as judul FROM siperpus_inventaris inv WHERE no_barcode = '".$id."'";
        $hasil = $this->db->query($query)->result();
        //$hasil = $this->db->get_where('siperpus_inventaris', array('ISBN' => $id))->result();
        $data = $hasil;
        return $data;
    }
   function getInventarisByNoInv($id) {
        $query = "SELECT *,(SELECT judul FROM `siperpus_buku` WHERE ISBN = inv.ISBN AND no_klas = inv.no_klas) as judul FROM siperpus_inventaris inv WHERE no_inv = '".$id."'";
        $hasil = $this->db->query($query)->result();
        //$hasil = $this->db->get_where('siperpus_inventaris', array('ISBN' => $id))->result();
        $data = $hasil;
        return $data;
    }
  function getInventarisByISBNdanNoKlas($id,$no_klas) {
         $query = "SELECT *,(SELECT judul FROM `siperpus_buku` WHERE ISBN = inv.ISBN and no_klas = inv.no_klas limit 1) as judul FROM siperpus_inventaris inv WHERE ISBN = '".$id."' and no_klas = '".$no_klas."'";
         $hasil = $this->db->query($query)->result();
         //$hasil = $this->db->get_where('siperpus_inventaris', array('ISBN' => $id))->result();
         $data = $hasil;
         return $data;
     }
  function countByISBN($isbn){
    $where = "ISBN = '".$isbn."'";
    $this->db->from('siperpus_inventaris');//from vwanggota
    $this->db->where($where);
    $query = $this->db->get();
    return $query->num_rows();
  }
  function hapusInventarisAll($isbn){
    $this->db->where('ISBN',$isbn);
    $this->db->delete('siperpus_inventaris');
  }
  function getBarcode($no_barcode){
    $this->db->select('no_inv, no_barcode');
    $this->db->from("siperpus_inventaris");
    $this->db->where(array('no_barcode'=> $no_barcode));
    $data = $this->db->get();
    return $data->result();
  }
  function getDataCallNumber($no_barcode){
    // echo $no_barcode."a";die;
    $this->db->select('no_klas, no_inv, no_barcode');
    $this->db->from("siperpus_inventaris");
    $this->db->where("no_barcode", $no_barcode);
    // $isbn = $this->db->get()->result_array();
    return $this->db->get()->result_array();
  }
  function getJumlahEks() {
		 $hasil = $this->db->query("SELECT * FROM siperpus_inventaris  ");
        if ($hasil->num_rows() > 0) {
            return $hasil->num_rows();
        }else{
			return 0;
		}
    }
    function getInventarisByTahunKlas($tahun,$bulan,$klas) {
        $q='';
		if($bulan!='')$q="and month(tgl_inv)='$bulan'";
        $query = "SELECT count(*) as total FROM siperpus_inventaris WHERE year(tgl_inv)='$tahun' $q and no_klas like '$klas%'";
        $hasil = $this->db->query($query);
		if ($hasil->num_rows() > 0) {
             foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data[0]->total;
        }else{
			return 0;
		}
    }
    function getInventarisByStatusKlas($status,$klas) {
        $q="and no_inv not in(select no_inv from siperpus_hilangrusak where ket <> 'K')";
		if($status!='')$q="and no_inv in(select no_inv from siperpus_hilangrusak where ket='$status')";
        $query = "SELECT count(*) as total FROM siperpus_inventaris WHERE no_klas like '$klas%' $q";
        $hasil = $this->db->query($query);
		if ($hasil->num_rows() > 0) {
             foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data[0]->total;
        }else{
			return 0;
		}
    }
	function getInventarisByStatusKat($status,$kat) {
        $q="and si.no_inv not in(select no_inv from siperpus_hilangrusak where ket <> 'K')";
		if($status!='')$q="and si.no_inv in(select no_inv from siperpus_hilangrusak where ket='$status')";
        $query = "SELECT count(*) as total FROM siperpus_inventaris si, siperpus_buku sb WHERE si.no_klas = sb.no_klas and si.ISBN = sb.ISBN and sb.idkategori = '$kat' $q";
        $hasil = $this->db->query($query);
		if ($hasil->num_rows() > 0) {
             foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data[0]->total;
        }else{
			return 0;
		}
    }
	function getInventarisByStatusAsal($status,$asal) {
        $q="and no_inv not in(select no_inv from siperpus_hilangrusak where ket <> 'K')";
		if($status!='')$q="and no_inv in(select no_inv from siperpus_hilangrusak where ket='$status')";
        $query = "SELECT count(*) as total FROM siperpus_inventaris WHERE asal = '$asal' $q";
        $hasil = $this->db->query($query);
		if ($hasil->num_rows() > 0) {
             foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data[0]->total;
        }else{
			return 0;
		}
    }
	function getInventarisByStatusBahasa($status,$bahasa) {
        $q="and si.no_inv not in(select no_inv from siperpus_hilangrusak where ket <> 'K')";
		if($status!='')$q="and si.no_inv in(select no_inv from siperpus_hilangrusak where ket='$status')";
        $query = "SELECT count(*) as total FROM siperpus_inventaris si, siperpus_buku sb WHERE si.no_klas = sb.no_klas and si.ISBN = sb.ISBN and sb.bahasa = '$bahasa' $q";
        $hasil = $this->db->query($query);
		if ($hasil->num_rows() > 0) {
             foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data[0]->total;
        }else{
			return 0;
		}
    }
    function getJmlBukuByThnJudul_Total($status,$thn){
      $q="and no_inv not in(select no_inv from siperpus_hilangrusak where ket <> 'K')";
      if($status!='')$q="and no_inv in(select no_inv from siperpus_hilangrusak where ket='$status')";
      $query = "SELECT count(*) as total FROM siperpus_inventaris WHERE no_klas IN (SELECT no_klas FROM siperpus_buku WHERE thn_terbit = '$thn') $q";
      $hasil = $this->db->query($query)->result_array();
      return $hasil[0]['total'];
    }
    function getJmlBukuByThnJudul_Klas($status,$no_klas,$thn)
    {
      $q="and no_inv not in(select no_inv from siperpus_hilangrusak where ket <> 'K')";
      if($status!='')$q="and no_inv in(select no_inv from siperpus_hilangrusak where ket='$status')";
      $query = "SELECT count(*) as total FROM siperpus_inventaris WHERE no_klas IN (SELECT no_klas FROM siperpus_buku WHERE thn_terbit = '$thn') AND no_klas like '$no_klas%' $q";
      $hasil = $this->db->query($query)->result_array();
      return $hasil[0]['total'];
    }
    function getJmlBukuByThnJudul_Kat($status,$idkategori,$thn)
    {
      $q="and no_inv not in(select no_inv from siperpus_hilangrusak where ket <> 'K')";
      if($status!='')$q="and no_inv in(select no_inv from siperpus_hilangrusak where ket='$status')";
      $query = "SELECT count(*) as total FROM siperpus_inventaris WHERE no_klas IN (SELECT no_klas FROM siperpus_buku WHERE thn_terbit = '$thn' AND idkategori = '$idkategori') $q";
      $hasil = $this->db->query($query)->result_array();
      return $hasil[0]['total'];
    }
    function getJmlBukuByThnJudul_Asal($status,$asal,$thn) {
      $q="and no_inv not in(select no_inv from siperpus_hilangrusak where ket <> 'K')";
      if($status!='')$q="and no_inv in(select no_inv from siperpus_hilangrusak where ket='$status')";
      $query = "SELECT count(*) as total FROM siperpus_inventaris WHERE no_klas IN (SELECT no_klas FROM siperpus_buku WHERE thn_terbit = '$thn') AND asal = '$asal' $q";
      $hasil = $this->db->query($query)->result_array();
      return $hasil[0]['total'];
    }
}
