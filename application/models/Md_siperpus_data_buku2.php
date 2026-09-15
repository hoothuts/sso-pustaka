<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_siperpus_data_buku extends CI_Model {
  var $select= 'b.*, p.nama_penerbit, p.kota as penerbit, (SELECT COUNT(bp.idmspst)FROM siperpus_buku_prodi bp WHERE bp.no_klas = b.no_klas AND bp.ISBN = b.ISBN) AS jml_prodi, (SELECT COUNT(*) FROM siperpus_transaksi t WHERE t.no_inv IN( SELECT i.no_inv FROM siperpus_inventaris i   WHERE i.ISBN = b.ISBN AND i.no_klas = b.no_klas)) AS jml_pinjam';
  var $table1 = 'siperpus_buku';
  var $table = 'siperpus_buku b';
  var $where = 'b.kd_penerbits = a.kd_penerbit';
  var $column_order = array(array('no_klas','no_klas'),array('judul','judul'),array('penulis','penulis'),array('jml_buku','jml_buku')); //set column field database for datatable orderable
  var $column_search = array('ISBN','judul','penulis','no_klas','p.nama_penerbit'); //set column field database for datatable searchable just firstname , lastname , address are searchable
  var $order = array('tanggal' => 'desc'); // default order

  public function countAll() {

        $this->db->from($this->table1);
        return $this->db->count_all_results();
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
    $this->db->select($this->select);
    $this->db->from($this->table);
    $this->db->join('siperpus_penerbit p', 'p.kd_penerbit = b.kd_penerbit');
    // $this->db->where($this->where);
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
    // $string = explode(" ", $this->input->post('datatable[query][generalSearch]'));
    // if ($string) {
    //   $this->db->group_start();
    //   $this->db->like($this->column_search[0], $string[0]);
    //   $this->db->or_like($this->column_search[1], $string[0]);
    //   $this->db->or_like($this->column_search[2], $string[0]);
    //   // $this->db->or_like($this->column_search[3], $string[0]);
    //   if (isset($string[1]) {
    //     for ($i=1; $i < count($string); $i++) {
    //       $this->db->or_like($this->column_search[0], $string[$i]);
    //       for ($j=1; $j < count($this->column_search); $j++) {
    //         $this->db->or_like($this->column_search[$j], $string[$i]);
    //       }
    //     }
    //   }
    //   $this->db->group_end(); //close bracket
    // }
    if($this->input->post('datatable[query][klas]')){
			$this->db->like('no_klas', $this->input->post('datatable[query][klas]'), 'after');
		}
		if($this->input->post('datatable[query][kel]')){
			$this->db->where('idkategori', $this->input->post('datatable[query][kel]'));
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
  function countFiltered(){
    $this->getDatatablesQuery();
    $query = $this->db->get();
    return $query->num_rows();
  }
  function getDataBukuAll() {
		 $hasil = $this->db->query("SELECT *,(SELECT nama_penerbit from siperpus_penerbit where kd_penerbit = bk.kd_penerbit)as penerbit FROM siperpus_buku bk where kd_penerbit = 1565 order by judul asc");
        if ($hasil->num_rows() > 0) {
            foreach ($hasil->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
    }
	function addDataBuku($data){
         $this->db->insert('siperpus_buku', $data);
  }
  function hapusDataBuku($ISBN,$no_klas){
    $this->db->where(array('ISBN' => $ISBN, 'no_klas'=> $no_klas));
    // $this->db->where('ISBN',$data);
    $this->db->delete('siperpus_buku');
  }
  function updateDataBuku($ISBN,$no_klas,$data){
        $this->db->where(array('ISBN' => $ISBN, 'no_klas'=> $no_klas));
        $this->db->update('siperpus_buku', $data);
    }

  function updateNoklasBukuInventaris($ISBN_l,$no_klas_l,$ISBN_b,$no_klas_b){
    $this->db->where(array('ISBN' => $ISBN_l, 'no_klas'=> $no_klas_l));
    $this->db->set(array('ISBN' => $ISBN_b, 'no_klas'=> $no_klas_b));
    $this->db->update('siperpus_inventaris');
  }
  function updateNoklasBukuFile($ISBN_l,$no_klas_l,$ISBN_b,$no_klas_b){
    $this->db->where(array('ISBN' => $ISBN_l, 'no_klas'=> $no_klas_l));
    $this->db->set(array('ISBN' => $ISBN_b, 'no_klas'=> $no_klas_b));
    $this->db->update('siperpus_buku_file');
  }


  function updateJmlBuku($isbn,$no_klas,$row){
    $this->db->set('jml_buku',$row);
    $this->db->where('ISBN', $isbn);
    $this->db->where('no_klas', $no_klas);
    $this->db->update('siperpus_buku');
    }
  function cekISBN($ISBN)
  {
    $data = $this->db->from($this->table1)->where(array('ISBN' => $ISBN))->get()->result();
    if ($data) {
      return TRUE;
    }else {
      return FALSE;
    }

  }
	function getDataBukuByISBN($ISBN,$no_klas) {
    $this->db->from($this->table1);
    $this->db->where(array('ISBN' => $ISBN, 'no_klas'=> $no_klas));
    $data = $this->db->get();
    return $data->result();
  }
  function getDataBukuByNoklas($no_klas) {
    $this->db->from($this->table1);
    $this->db->where('no_klas', $no_klas);
    $data = $this->db->get();
    if ($data->num_rows() > 0) {
      return true;
    }else {
      return false;
    }
  }
  function getDataBukuByISBNdanNo_klas($ISBN,$no_klas) {
    $this->db->select('a.*,b.nama_penerbit,b.kota')
         ->from('siperpus_penerbit b')
         ->join('siperpus_buku a', 'a.kd_penerbit = b.kd_penerbit')
         ->where(array('a.ISBN' => $ISBN, 'a.no_klas'=> $no_klas));
    $data = $this->db->get();
    return $data->result();
  }
  function updateRakBuku($no_klas,$rak) {
    $this->db->set('no_rak',$rak);
    $this->db->where('no_klas', $no_klas);
    $this->db->update('siperpus_buku');
  }


  function addDataBukuTa($data)
  {
    $db = $this->db->insert('siperpus_buku_ta', $data);
    if ($this->db->affected_rows() > 0)
    {
      return TRUE;
    } else{
      return FALSE;
    }
  }
  function updateDataBukuTa($data){
    $this->db->where('ISBN_ta', $data['ISBN_ta']);
    $this->db->where('no_klas_ta', $data['no_klas_ta']);
    $this->db->update('siperpus_buku_ta', $data);
  }
  function getDataKatalog($no_klas,$isbn){
    $this->db->select("bk.*,pen.nama_penerbit,pen.kota")
             ->from("siperpus_penerbit pen")
             ->join("siperpus_buku bk", "pen.kd_penerbit = bk.kd_penerbit")
             ->where(array('ISBN' => $isbn, 'no_klas'=> $no_klas));
    $data = $this->db->get();
    return $data->result_array();
  }
  function getDataCallNumber($no_klas){
    $this->db->select('no_klas');
    $this->db->from("siperpus_inventaris");
    $this->db->like('no_klas',$no_klas,'before');
    $data = $this->db->get();
    return $data->num_rows();
  }
  function getBarcode($no_klas,$isbn){
    $this->db->select('no_barcode , no_inv');
    $this->db->from("siperpus_inventaris");
    $this->db->where(array('ISBN' => $isbn, 'no_klas'=> $no_klas));
    $data = $this->db->get();
    return $data->result();
  }
  function addDataFile($file_up)
  {
    $db = $this->db->insert('siperpus_buku_file', $file_up);
    if ($this->db->affected_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
  }
  function getDataFile($ISBN,$no_klas)
  {
    return $this->db->from('siperpus_buku_file')->where(array('ISBN' => $ISBN, 'no_klas'=> $no_klas))->get()->result();
  }
  function hapusDataFile($ISBN,$no_klas,$filename)
  {
    return $this->db->where(array('ISBN' => $ISBN, 'no_klas'=> $no_klas, 'file_name'=> $filename))->delete('siperpus_buku_file');
  }
  function getDataProdiByISBN_No_klas($ISBN,$no_klas)
  {
    return $this->db->select("p.nmmspst")
                    ->from("vwprodi p")
                    ->join("siperpus_buku_prodi bp", "p.idmspst = bp.idmspst")
                    ->where(array('ISBN' => $ISBN, 'no_klas'=> $no_klas))
                    ->get()->result_array();
  }
  function getDataBarcodeByISBN_No_klas($ISBN,$no_klas)
  {
    return $this->db->select("no_barcode")
                    ->from("siperpus_inventaris")
                    ->where(array('ISBN' => $ISBN, 'no_klas'=> $no_klas))
                    ->get()->result();
  }

  function getBukuByStatusKlas($status,$klas) {
    $q="and no_klas not in(SELECT DISTINCT no_klas from siperpus_inventaris where no_inv in (SELECT no_inv from siperpus_hilangrusak where ket <> 'K'))";// stok aktif
    if($status!='')$q="and no_klas in(SELECT DISTINCT no_klas from siperpus_inventaris where no_inv in (SELECT no_inv from siperpus_hilangrusak where ket ='$status'))";//with selector/status(hilang/rusak/diarsipkan/dilelang)
    $query = "SELECT count(*) as total FROM siperpus_buku WHERE no_klas like '$klas%' $q";
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
function getBukuByStatusKat($status,$kat) {
  $q="and no_klas not in(SELECT DISTINCT no_klas from siperpus_inventaris where no_inv in (SELECT no_inv from siperpus_hilangrusak where ket <> 'K'))";// stok aktif
  if($status!='')$q="and no_klas in(SELECT DISTINCT no_klas from siperpus_inventaris where no_inv in (SELECT no_inv from siperpus_hilangrusak where ket ='$status'))";//with selector/status(hilang/rusak/diarsipkan/dilelang)
  $query = "SELECT count(*) as total FROM siperpus_buku WHERE idkategori like '$kat%' $q";
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
function getBukuByStatusAsal($status,$asal) {
  $q="and no_inv not in(select no_inv from siperpus_hilangrusak where ket <> 'K')) no_klas";
  if($status!='')$q="and no_inv in(select no_inv from siperpus_hilangrusak where ket='$status')) no_klas";
  $query = "SELECT count(DISTINCT(no_klas)) total from (SELECT no_klas from siperpus_inventaris where asal = '$asal' $q";
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
function getBukuByStatusBahasa($status,$bahasa) {
  $q="and no_klas not in(SELECT DISTINCT no_klas from siperpus_inventaris where no_inv in (SELECT no_inv from siperpus_hilangrusak where ket <> 'K'))";// stok aktif
  if($status!='')$q="and no_klas in(SELECT DISTINCT no_klas from siperpus_inventaris where no_inv in (SELECT no_inv from siperpus_hilangrusak where ket ='$status'))";//with selector/status(hilang/rusak/diarsipkan/dilelang)
  $query = "SELECT count(*) as total FROM siperpus_buku WHERE bahasa like '$bahasa%' $q";
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
function getTahunAll(){
  return $this->db->distinct()->select('thn_terbit')->from('siperpus_buku')->order_by('thn_terbit')->get()->result();
}
function getBukuByThnJudul_Total($status,$thn){
  $q="and no_klas not in(SELECT DISTINCT no_klas from siperpus_inventaris where no_inv in (SELECT no_inv from siperpus_hilangrusak where ket <> 'K'))";// stok aktif
  if($status!='')$q="and no_klas in(SELECT DISTINCT no_klas from siperpus_inventaris where no_inv in (SELECT no_inv from siperpus_hilangrusak where ket ='$status'))";//with selector/status(hilang/rusak/diarsipkan/dilelang)
  $query = "select count(*) total from siperpus_buku where thn_terbit = '$thn' $q";
  $hasil = $this->db->query($query)->result_array();
  return $hasil[0]['total'];
}
function getBukuByThnJudul_Klas($status,$no_klas,$thn){
  $q="and no_klas not in(SELECT DISTINCT no_klas from siperpus_inventaris where no_inv in (SELECT no_inv from siperpus_hilangrusak where ket <> 'K'))";// stok aktif
  if($status!='')$q="and no_klas in(SELECT DISTINCT no_klas from siperpus_inventaris where no_inv in (SELECT no_inv from siperpus_hilangrusak where ket ='$status'))";//with selector/status(hilang/rusak/diarsipkan/dilelang)
  $query = "select count(*) total from siperpus_buku where thn_terbit = '$thn' and no_klas like '$no_klas%' $q";
  $hasil = $this->db->query($query)->result_array();
  return $hasil[0]['total'];
}
function getBukuByThnJudul_Kat($status,$idkategori,$thn){
  $q="and no_klas not in(SELECT DISTINCT no_klas from siperpus_inventaris where no_inv in (SELECT no_inv from siperpus_hilangrusak where ket <> 'K'))";// stok aktif
  if($status!='')$q="and no_klas in(SELECT DISTINCT no_klas from siperpus_inventaris where no_inv in (SELECT no_inv from siperpus_hilangrusak where ket ='$status'))";//with selector/status(hilang/rusak/diarsipkan/dilelang)
  $query = "select count(*) total from siperpus_buku where thn_terbit = '$thn' and idkategori ='$idkategori' $q";
  $hasil = $this->db->query($query)->result_array();
  return $hasil[0]['total'];
}
function getBukuByThnJudul_Asal($status,$asal,$thn){
  $q="and no_inv not in(select no_inv from siperpus_hilangrusak where ket <> 'K')) no_klas";
  if($status!='')$q="and no_inv in(select no_inv from siperpus_hilangrusak where ket='$status')) no_klas";
  $query = "SELECT count(DISTINCT(no_klas)) total from (SELECT no_klas from siperpus_inventaris where no_klas in (SELECT no_klas from siperpus_buku where thn_terbit = '$thn') AND asal = '$asal' $q";
  $hasil = $this->db->query($query)->result_array();
  return $hasil[0]['total'];
}

}
