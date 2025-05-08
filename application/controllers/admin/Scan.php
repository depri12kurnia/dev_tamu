<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Scan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_guest');

        $this->load->model('M_settings');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }
        if (!$this->ion_auth->in_group('admin')) {
            redirect('page_errors');
        }

        $this->load->library('ciqrcode');
        // $this->load->helper('ngrok');
    }

    public function index()
    {
        $data['csrf_token_name'] = $this->security->get_csrf_token_name();
        $data['csrf_hash'] = $this->security->get_csrf_hash();

        $data['website'] = $this->M_settings->get_all_settings();
        $data['title'] = 'Guest | Admin Panel';
        $data['content'] = 'paneladmin/scanner/scan';
        $this->load->view('layouts/adminlte3', $data);
    }

    public function check()
    {
        $this->output->set_content_type('application/json');

        $qr_code = $this->input->post('qr_code', TRUE);

        $guest = $this->M_guest->get_by_qr($qr_code);

        if ($guest) {
            // Update check-in
            $this->M_guest->checkin($qr_code);

            echo json_encode([
                'status' => true,
                'message' => 'Check-in sukses: ' . $guest->name . ' || Prodi: ' . $guest->prodi,
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'QR Code tidak ditemukan!',
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
        }
    }
}
