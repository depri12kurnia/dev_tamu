<?php
class Checkin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_guest');

        $this->load->model('M_settings');
        $this->load->model('M_log_user');

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
        // Mendapatkan user yang login
        $user = $this->ion_auth->user()->row();

        if ($guest) {
            $this->M_guest->checkin($qr);

            // Menyimpan log aktivitas login
            $this->M_log_user->save_log($user->id, 'Checkin Manual Berhasil' . $guest->name);

            // Jika berhasil
            echo json_encode([
                'status' => 'success',
                'message' => 'QR berhasil diverifikasi!' . $guest->name,
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
        } else {
            // Jika gagal
            echo json_encode([
                'status' => 'error',
                'message' => 'QR tidak valid!',
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
        }
    }
}
