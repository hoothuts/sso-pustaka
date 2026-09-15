<?php
defined('BASEPATH') or exit('No direct script access allowed');

class XML extends CI_Controller {

    public function index() {
        $this->load->database();
        $offset = $this->input->get('offset') ?? 0;
        $limit = $this->input->get('limit') ?? 0;

        $this->db->join('siperpus_penerbit', 'siperpus_buku.kd_penerbit = siperpus_penerbit.kd_penerbit');
        $this->db->select('siperpus_buku.*, siperpus_penerbit.nama_penerbit as nama_penerbit');
        $this->db->order_by('siperpus_buku.buku_id', 'desc');
        $query = $this->db->get('siperpus_buku', $limit, $offset)->result_array();

        $xml = '<OAI-PMH xmlns="https://www.openarchives.org/OAI/2.0/" xmlns:xsi="https://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="https://www.openarchives.org/OAI/2.0/ https://www.openarchives.org/OAI/2.0/OAI-PMH.xsd">
		<ListRecords>';
        foreach ($query as $row) {
            $xml .= '<record>
				<metadata>
					<oai_dc:dc xmlns:oai_dc="https://www.openarchives.org/OAI/2.0/oai_dc/" xmlns:dc="https://purl.org/dc/elements/1.1/" xmlns:xsi="https://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="https://www.openarchives.org/OAI/2.0/oai_dc/ https://www.openarchives.org/OAI/2.0/oai_dc.xsd">
						<dc:title>' . html_escape($row['judul']) . '</dc:title>
						<dc:description >' . html_escape($row['deskiprsi']) . '</dc:description>
						<dc:creator>' . html_escape($row['penulis']) . '</dc:creator>
						<dc:subject>' . html_escape($row['tajuksubyek']) . '</dc:subject>
						<dc:date>' . html_escape($row['thn_terbit']) . '</dc:date> 
						<dc:publisher>' . html_escape($row['nama_penerbit']) . '</dc:publisher> 
						<dc:language>' . html_escape($row['bahasa']) . '</dc:language>
						<dc:identifier>' . ($row['cover'] ? 'https://lib.pkr.ac.id/uploads/covers/' . $row['cover'] : '') . '</dc:identifier>
						<dc:identifier>https://lib.pkr.ac.id/home/detail/' . $row['ISBN'] . '/' . str_replace(' ', '+', $row['no_klas']) . '</dc:identifier>
					</oai_dc:dc>
				</metadata>
			</record>';
        }
        $xml .= '</ListRecords>
		</OAI-PMH>';

        $this->output->set_content_type('text/xml');
        $this->output->set_output($xml);
    }

}
