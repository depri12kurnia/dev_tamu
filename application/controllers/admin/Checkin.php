<?php
class Checkin extends CI_Controller
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
    }

    public function index()
    {
        $data['website'] = $this->M_settings->get_all_settings();

        $data['title'] = 'Checkin | Admin Panel';
        $data['content'] = 'paneladmin/guest/checkin';
        $this->load->view('layouts/adminlte3', $data);
    }

    public function validate()
    {
        $qr = $this->input->post('qr_code');
        $guest = $this->M_guest->get_by_qr($qr);

        if ($guest) {
            $this->M_guest->checkin($qr);
            echo json_encode(['status' => 'success', 'message' => 'Check-in sukses untuk ' . $guest->name]);
        } else {
            echo json_encode(['status' => 'fail', 'message' => 'QR Code tidak dikenali']);
        }
    }
}
