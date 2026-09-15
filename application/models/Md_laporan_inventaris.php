<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Md_laporan_inventaris extends CI_Model {

    // 1. Sederhanakan mapping order (Key Datatable => Kolom Database)
    var $column_order = array(
        'penulis' => 'sb.penulis',
        'judul' => 'sb.judul',
        'penerbit' => 'sp.nama_penerbit',
        'edisi' => 'sb.edisi',
        'tahun' => 'sb.thn_terbit',
        'ISBN' => 'sb.ISBN',
        'jml' => 'jml_buku',
        'no_klas' => 'sb.no_klas',
        'tanggal' => 'sb.tanggal',
        'hr' => 'hilang',
        'kampus' => 'nama_kampus',
        'gedung' => 'nama_gedung',
        'rak' => 'nama_rak'
    );

    var $column_search = array('sb.penulis', 'sb.judul', 'sb.edisi', 'sp.nama_penerbit', 'sb.thn_terbit', 'sb.ISBN', 'sb.no_klas', 'kampus_lokasi', 'gedung_lokasi', 'rak_lokasi');
    var $order_default = array('sb.buku_id' => 'desc');

    private function getDatatablesQuery() {
        $this->db->select('sb.penulis, sb.judul, sb.cover, sb.edisi, sp.nama_penerbit as penerbit, sb.thn_terbit, sb.ISBN, sb.no_klas, sb.tanggal, 
            sab.nama as asal_buku,
            IFNULL(hr_count.total_hr, 0) as hilang, 
            GROUP_CONCAT(DISTINCT k.nama_kampus SEPARATOR ", ") AS nama_kampus,
            GROUP_CONCAT(DISTINCT g.nama_gedung SEPARATOR ", ") AS nama_gedung,
            GROUP_CONCAT(DISTINCT r.nama_rak SEPARATOR ", ") AS nama_rak,
            GROUP_CONCAT(DISTINCT vp.nmmspst SEPARATOR ", ") AS nama_prodi,
            COUNT(DISTINCT i.no_inv) AS jml_buku', FALSE);  // jml inventaris aktif per lokasi filter

        $this->db->from('siperpus_buku sb');
        $this->db->join('siperpus_penerbit sp', 'sp.kd_penerbit = sb.kd_penerbit', 'left');
        $this->db->join('siperpus_inventaris i', 'i.ISBN = sb.ISBN AND i.no_klas = sb.no_klas AND i.status = "A"', 'left');
        $this->db->join('lokasi_rak r', 'r.lokasirak_id = i.lokasirak_id', 'left');
        $this->db->join('lokasi_gedung g', 'g.lokasigedung_id = r.lokasigedung_id', 'left');
        $this->db->join('lokasi_kampus k', 'k.lokasikampus_id = g.lokasikampus_id', 'left');
        $this->db->join('siperpus_asal_buku sab', 'sab.id = i.asal','left');
        $this->db->join('siperpus_buku_prodi bp','bp.ISBN = sb.ISBN AND bp.no_klas = sb.no_klas', 'left');
        $this->db->join('vwprodi vp','vp.idmspst = bp.idmspst', 'left');
        
        // Subquery JOIN untuk hitung Hilang/Rusak (Optimasi)
        $this->db->join('(SELECT si.ISBN, si.no_klas, COUNT(sh.kd_hr) as total_hr 
                          FROM siperpus_inventaris si 
                          JOIN siperpus_hilangrusak sh ON sh.no_inv = si.no_inv 
                          GROUP BY si.ISBN, si.no_klas) hr_count', 
                        'hr_count.ISBN = sb.ISBN AND hr_count.no_klas = sb.no_klas', 'left');

        $query_payload = (array) $this->input->post('datatable[query]');

        // Logic Searching
        $search = $query_payload['generalSearch'] ?? '';
        if ($search !== '') {
            $this->db->group_start();
            foreach ($this->column_search as $i => $item) {
                ($i === 0) ? $this->db->like($item, $search) : $this->db->or_like($item, $search);
            }
            $this->db->group_end();
        }

        // Logic Filtering (Aman untuk nilai "0")
        if (isset($query_payload['klasifikasi']) && $query_payload['klasifikasi'] !== '' && $query_payload['klasifikasi'] !== '-') {
            $this->db->where('LEFT(sb.no_klas,1)', substr($query_payload['klasifikasi'], 0, 1));
        }
        if (!empty($query_payload['kategori']) && $query_payload['kategori'] !== '-') {
            $this->db->where('sb.idkategori', $query_payload['kategori']);
        }
        if (!empty($query_payload['tanggalawal'])) {
            $this->db->where('date(sb.tanggal) >=', $query_payload['tanggalawal']);
        }
        if (!empty($query_payload['tanggalakhir'])) {
            $this->db->where('date(sb.tanggal) <=', $query_payload['tanggalakhir']);
        }
        if (isset($query_payload['thn_terbit']) && $query_payload['thn_terbit'] !== '' && $query_payload['thn_terbit'] !== '-') {
            $this->db->where('sb.thn_terbit', $query_payload['thn_terbit']);
        }

        if (!empty($query_payload['m_form_kampus'])) {
            $this->db->where('k.lokasikampus_id', $query_payload['m_form_kampus']);
        }
        if (!empty($query_payload['m_form_gedung'])) {
            $this->db->where('g.lokasigedung_id', $query_payload['m_form_gedung']);
        }
        if (!empty($query_payload['m_form_rak'])) {
            $this->db->where('r.lokasirak_id', $query_payload['m_form_rak']);
        }
        if (!empty($query_payload['asal'])) {
            $this->db->where('sab.id', $query_payload['asal']);
        }
        if (!empty($query_payload['prodi'])) {
            $this->db->where_in('bp.idmspst', $query_payload['prodi']);
        }

        // Logic Ordering (Tanpa getValue)
        $sortField = $this->input->post('datatable[sort][field]');
        $sortOrder = $this->input->post('datatable[sort][sort]');

        if (isset($this->column_order[$sortField])) {
            $this->db->order_by($this->column_order[$sortField], $sortOrder);
        } else {
            $this->db->order_by(key($this->order_default), current($this->order_default));
        }
        $this->db->group_by('sb.ISBN, sb.no_klas');
    }

    public function getDatatables() {
        $this->getDatatablesQuery();
        $perpage = (int)$this->input->post('datatable[pagination][perpage]');
        $page    = (int)$this->input->post('datatable[pagination][page]');

        if ($perpage != -1) {
            $this->db->limit($perpage, ($perpage * ($page - 1)));
        }

        return $this->db->get()->result();
    }

    public function countFiltered() {
        $this->getDatatablesQuery();
        // Menggunakan subquery agar penghitungan total akurat terhadap JOIN agregasi
        $subquery = $this->db->get_compiled_select();
        return $this->db->query("SELECT COUNT(*) as total FROM ($subquery) AS filter_count")->row()->total;
    }

    private function getLaporanQuery($klas, $ktg, $ta, $tl, $src) {
        //,array('tgl','ang.tgl_daftar'),array('berlaku','ang.berlaku_sampai')
        $table = 'siperpus_buku sb';
        $column_order = array(array('penulis', 'sb.penulis'), array('judul', 'sb.judul'), array('penerbit', 'sp.nama_penerbit'), array('edisi', 'sb.edisi'), array('tahun', 'sb.thn_terbit'), array('ISBN', 'sb.ISBN'), array('jml', 'sb.jml_buku'), array('no_klas', 'sb.no_klas'), array('tanggal', 'sb.tanggal'), array('hr', 'hilang'));
        $column_search = array('sb.penulis', 'sb.judul', 'sb.edisi', 'sp.nama_penerbit', 'sb.thn_terbit', 'sb.ISBN', 'sb.jml_buku', 'sb.no_klas', 'sb.tanggal', 'hilang');
        $order = array('sb.penulis' => 'asc'); // default order

        $this->db->from($table);
        $i = 0;

        foreach ($column_search as $item) { // loop column
            if ($src) { // if datatable send POST for search

                if ($i === 0) { // first loop
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
        if ($klas !== NULL && $klas !== '' && $klas !== '-') {
            $klas = substr($klas, 0, 1);
            $this->db->where('LEFT(sb.no_klas,1)', $klas);
        }
        
        if ($ktg && $ktg != '-') {
            $this->db->where('sb.idkategori', $ktg);
        }
        if ($ta) {
            $this->db->where('date(sb.tanggal) >=', $ta);
        }
        if ($tl) {
            $this->db->where('date(sb.tanggal) <=', $tl);
        }
        $this->db->order_by(key($order), $order[key($order)]);
    }
    
    function getLaporan($klas, $ktg, $ta, $tl, $src) {

        $this->getLaporanQuery($klas, $ktg, $ta, $tl, $src);
        $this->db->join('siperpus_penerbit sp', 'sp.kd_penerbit = sb.kd_penerbit');
        $this->db->select('sb.penulis,sb.judul,sb.edisi,sp.nama_penerbit as penerbit,sb.thn_terbit,sb.ISBN,sb.jml_buku,sb.no_klas,sb.tanggal,(select count(*) from `siperpus_hilangrusak` sh where sh.no_inv in (select no_inv from siperpus_inventaris si where si.ISBN=sb.ISBN and si.no_klas=sb.no_klas)) as hilang');
        $query = $this->db->get();
        return $query->result();
    }
    
    function getLaporanBuku(){
        $this->getDatatablesQuery();
        return $this->db->get()->result();
    }
}
