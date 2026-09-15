<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Md_mailbox extends CI_Model {

    private $table = 'mailbox';

    // Insert mailbox
    public function addMailbox($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // Ambil queue email
    public function getQueueMailbox($limit = 5)
    {
        $this->db->where_in('statuskirim', ['Draft', 'Gagal']);
        $this->db->where('retry_count <', 3);
        $this->db->where('status', 1);
        $this->db->order_by('mailbox_id', 'ASC');
        $this->db->limit($limit);

        return $this->db->get($this->table)->result();
    }

    // Cek duplicate reminder hari ini
    public function checkTodayReminder($email, $subject)
    {
        $this->db->where('to', $email);
        $this->db->where('subjek', $subject);
        $this->db->where('DATE(tglpost) = CURDATE()', null, false);
        $this->db->where('status', 1);
        return $this->db->get($this->table)->row();
    }

    // Update status Sending
    public function markSending($id)
    {
        $this->db->where('mailbox_id', $id);
        $this->db->where_in('statuskirim', ['Draft', 'Gagal']);
        return $this->db->update($this->table, [
            'statuskirim' => 'Sending'
        ]);
    }

    // Update success
    public function markSuccess($id)
    {
        $this->db->where('mailbox_id', $id);

        return $this->db->update($this->table, [
            'statuskirim' => 'Terkirim',
            'tglkirim'    => date('Y-m-d H:i:s'),
            'last_error'  => null
        ]);
    }

    // Update failed
    public function markFailed($id, $error = null)
    {
        $this->db->set('retry_count', 'retry_count+1', false);

        $this->db->where('mailbox_id', $id);

        return $this->db->update($this->table, [
            'statuskirim' => 'Gagal',
            'last_error'  => $error
        ]);
    }
}