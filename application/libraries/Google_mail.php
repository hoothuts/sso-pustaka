<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once FCPATH . 'vendor/autoload.php';
/* 
 * google/apiclient SDK v1.1.9 => tanpa renew token 
*/
class Google_mail extends Google_Client
{
    private $service;

    function __construct($params = array())
    {
        parent::__construct();

        $this->gmailService();
    }

    /**
     * Returns authorized Google Client
     */
    function getClient()
    {
        $client = new Google_Client();

        // CONFIG
        $json = json_decode(
            file_get_contents(APPPATH . 'config/gmail_credential.json'),
            true
        );

        // Desktop App Credential
        $config = $json['installed'];

        // CLIENT CONFIG
        $client->setApplicationName('PKR Lib');

        $client->setClientId($config['client_id']);

        $client->setClientSecret($config['client_secret']);

        $client->setRedirectUri($config['redirect_uris'][0]);

        // SCOPES
        $client->addScope('https://www.googleapis.com/auth/gmail.readonly');
        $client->addScope('https://www.googleapis.com/auth/gmail.modify');
        $client->addScope('https://www.googleapis.com/auth/gmail.send');
        $client->addScope('https://www.googleapis.com/auth/gmail.compose');

        $client->setAccessType('offline');

        // SDK lama
        $client->setApprovalPrompt('force');

        // TOKEN PATH
        $tokenPath = APPPATH . 'config/gmail_token.json';

        if (!file_exists($tokenPath)) {

            log_message('error', 'gmail_token.json not found');

            return false;
        }

        // SDK lama menggunakan JSON STRING
        $accessToken = json_decode(
            file_get_contents($tokenPath),
            true
        );

        $client->setAccessToken($accessToken);
        
        // REFRESH TOKEN
        /*if ($client->isAccessTokenExpired()) {
            $refreshToken = isset($accessToken['refresh_token'])
                ? $accessToken['refresh_token']
                : null;

            if (!$refreshToken) {
                log_message('error', 'Refresh token not found');
                return false;
            }

            try {
                // Refresh token
                $client->refreshToken($refreshToken);

                // Ambil token baru
                $newToken = $client->getAccessToken();

                // VALIDASI TOKEN BARU
                if (
                    empty($newToken) ||
                    !is_array($newToken) ||
                    !isset($newToken['access_token'])
                ) {

                    log_message('error', 'Refresh token failed: invalid token response');

                    // fallback pakai token lama
                    $newToken = $accessToken;
                }

                // PERTAHANKAN refresh token lama
                $newToken['refresh_token'] = $refreshToken;

                // Simpan token baru
                file_put_contents(
                    $tokenPath,
                    json_encode($newToken)
                );

                // Set token baru
                $client->setAccessToken($newToken);

            } catch (Exception $e) {
                log_message(
                    'error',
                    'Refresh token exception: ' . $e->getMessage()
                );
                // fallback pakai token lama
                $client->setAccessToken($accessToken);
            }
        }*/

        return $client;
    }

    /**
     * Gmail Service
     */
    function gmailService()
    {
        $client = $this->getClient();

        /*echo '<pre>';
        var_dump($client);
        die;*/

        if (!$client) {
            return false;
        }

        $this->service = new Google_Service_Gmail($client);
    }

    /**
     * Send Gmail
     */
    public function sendGmail(
        string $email_to,
        string $subject,
        string $message,
        $email_from,
        $email_name,
        $Cc = false,
        $Bcc = false,
        $attachment = array()
    )
    {
        if (empty($this->service)) {

            return [
                'status' => false,
                'message' => 'Google Gmail Service not initialized'
            ];
        }

        try {

            $mime = new Mail_mime();

            $mime->setSubject($subject);

            $mime->setTXTBody(strip_tags($message));

            $mime->setHTMLBody($message);

            $mime->setFrom($email_name . ' <' . $email_from . '>');

            $mime->addTo($email_to);

            // CC
            if (!empty($Cc)) {

                if (is_array($Cc)) {

                    foreach ($Cc as $ccEmail) {
                        $mime->addCc($ccEmail);
                    }

                } else {

                    $mime->addCc($Cc);
                }
            }

            // BCC
            if (!empty($Bcc)) {

                if (is_array($Bcc)) {

                    foreach ($Bcc as $bccEmail) {
                        $mime->addBcc($bccEmail);
                    }

                } else {

                    $mime->addBcc($Bcc);
                }
            }

            // Attachment
            if (!empty($attachment) && is_array($attachment)) {

                foreach ($attachment as $file) {

                    if (
                        !empty($file['filename']) &&
                        !empty($file['filetype'])
                    ) {

                        $mime->addAttachment(
                            $file['filename'],
                            $file['filetype']
                        );
                    }
                }
            }

            $message_body = $mime->getMessage();

            $encodeMessage = $this->base64url_encode($message_body);

            $msg = new Google_Service_Gmail_Message();

            $msg->setRaw($encodeMessage);

            $send = $this->service
                ->users_messages
                ->send('me', $msg);

            if (!empty($send->getId())) {

                return [
                    'status' => true,
                    'message' => null
                ];

            } else {

                return [
                    'status' => false,
                    'message' => json_encode($send)
                ];
            }

        } catch (Exception $e) {

            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Base64 URL Encode
     */
    public function base64url_encode($data)
    {
        return rtrim(
            strtr(base64_encode($data), '+/', '-_'),
            '='
        );
    }
}