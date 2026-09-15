<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

    public function __construct() {
        parent::__construct();

        date_default_timezone_set('Asia/Jakarta');

        $this->load->database();

        $this->load->model('Md_mailbox');
        $this->load->model('Md_siperpus_transaksi');
        $this->load->model('Md_jurnal_akun');

        $this->load->helper('mailbox_helper');

        if ($this->router->fetch_method() != 'generate_token') {
            $this->validate_cron();
        }
    }

    // Validasi token cron
    private function validate_cron() {
        $token = $this->input->get('token');

        if ($token != $this->config->item('cron_token')) {
            show_404();
            exit;
        }
    }

    // Generate reminder keterlambatan
    public function generate_reminder_keterlambatan() {
        $list = $this->Md_siperpus_transaksi->get_peminjaman_mhs_belum_kembali();

        if (!$list) {
            echo "Tidak ada data keterlambatan";
            return;
        }

        // Group by email mahasiswa
        $grouped = [];

        foreach ($list as $row) {

            if (empty(trim($row->EMAIL)) || !filter_var($row->EMAIL, FILTER_VALIDATE_EMAIL) ) {
                continue;
            }

            $grouped[$row->EMAIL]['nama'] = $row->nama;
            $grouped[$row->EMAIL]['email'] = $row->EMAIL;
            $grouped[$row->EMAIL]['items'][] = $row;
        }

        foreach ($grouped as $g) {

            $subject = 'Reminder Pengembalian Buku Perpustakaan Poltekkes Riau';

            // Cek duplicate hari ini
            $cek = $this->Md_mailbox->checkTodayReminder(
                    $g['email'],
                    $subject
            );

            if ($cek) {
                continue;
            }

            // Generate isi email
            $isi = '
            Yth. ' . $g['nama'] . ',<br><br>

            Diberitahukan bahwa terdapat peminjaman buku perpustakaan yang telah melewati batas pengembalian.<br><br>

            Berikut daftar buku yang belum dikembalikan:<br><br>

            <table border="1" cellpadding="5" cellspacing="0" width="100%">
                <tr>
                    <th>No</th>
                    <th>Judul Buku</th>
                    <th>Barcode</th>
                    <th>Tgl Pinjam</th>
                    <th>Batas Kembali</th>
                </tr>
            ';

            $no = 1;

            foreach ($g['items'] as $item) {

                $isi .= '
                <tr>
                    <td>' . $no++ . '</td>
                    <td>' . $item->judul . '</td>
                    <td>' . $item->no_barcode . '</td>
                    <td>' . date('d-m-Y', strtotime($item->tgl_pinjam)) . '</td>
                    <td>' . date('d-m-Y', strtotime($item->batas)) . '</td>
                </tr>
                ';
            }

            $isi .= '
            </table>
            <br>
            Mohon segera melakukan pengembalian buku ke Perpustakaan Poltekkes Riau.
            <br><br>
            Terima kasih.
            ';

            $dataMail = [
                'to' => $g['email'],
                'from' => getEmailForm(),
                'subjek' => $subject,
                'isi' => $isi,
                'tglpost' => date('Y-m-d H:i:s'),
                'statuskirim' => 'Draft',
                'status' => 1
            ];

            $this->Md_mailbox->addMailbox($dataMail);
        }

        echo "Generate reminder selesai";
    }

    // Send queue mailbox
    public function send_mailbox() {
        $this->load->library('Google_mail');

        $queue = $this->Md_mailbox->getQueueMailbox(5);

        if (!$queue) {
            echo "Tidak ada queue";
            return;
        }

        foreach ($queue as $q) {

            // Lock queue
            $this->Md_mailbox->markSending($q->mailbox_id);

            // Kirim email
            $send = $this->google_mail->sendGmail(
                    $q->to,
                    $q->subjek,
                    $q->isi,
                    $q->from,
                    'Perpustakaan'
            );

            if ($send['status'] == true) {

                $this->Md_mailbox->markSuccess($q->mailbox_id);

                echo "SUCCESS: " . $q->to . "<br>";
            } else {

                $this->Md_mailbox->markFailed(
                        $q->mailbox_id,
                        $send['message']
                );

                echo "FAILED: " . $q->to . "<br>";
                echo "<pre>";
                print_r($send);
                echo "</pre><hr>";
            }
        }
    }

    //hanya sekali akses saja
    /*
    public function generate_token() {
        $json = json_decode(
                file_get_contents(APPPATH . 'config/gmail_credential.json'),
                true
        );

        $config = $json['installed'];

        $client = new Google_Client();

        $client->setApplicationName('PKR Lib');

        $client->setClientId($config['client_id']);

        $client->setClientSecret($config['client_secret']);

        $client->setRedirectUri('http://localhost');

        $client->setScopes([
            'https://www.googleapis.com/auth/gmail.send',
            'https://www.googleapis.com/auth/gmail.compose',
            'https://www.googleapis.com/auth/gmail.modify',
            'https://www.googleapis.com/auth/gmail.readonly'
        ]);

        $client->setAccessType('offline');

        $client->setApprovalPrompt('force');

        // JIKA ADA CODE
        if ($this->input->get('code')) {

            $client->authenticate($this->input->get('code'));

            $accessToken = $client->getAccessToken();

            file_put_contents(
                    APPPATH . 'config/gmail_token.json',
                    json_encode($accessToken)
            );

            echo '<h3 style="color:green">Token berhasil dibuat!</h3>';

            echo '<pre>';
            print_r($accessToken);

            exit;
        }

        $authUrl = $client->createAuthUrl();

        redirect($authUrl);
    }
    */
    
    //test sebelum production
    public function test_send_email()
    {
        $this->load->library('Google_mail');

        $send = $this->google_mail->sendGmail(
            'na2nk70@gmail.com',
            'Test Email Pustaka PKR',
            '
            <h3>Email Test Pustaka Berhasil</h3>
            <p>Ini adalah email test dari PKR Lib menggunakan Gmail API.</p>
            ',
            'EMAILPENGIRIM@gmail.com',
            'PKR Lib'
        );

        echo '<pre>';
        print_r($send);
    }
    
    
    
    public function auto_release_jurnal_account()
    {
        $total = $this->Md_jurnal_akun->auto_release_account();

        echo '

            <h3>
                AUTO RELEASE JURNAL SUCCESS
            </h3>

            <hr>

            Total released :
            <b>'.$total.'</b>

        ';
    }
    
}
