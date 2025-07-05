<?php

class Sendmail extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_sendmail');

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
        $data['title'] = 'Sendmail | Admin Panel';
        $data['content'] = 'paneladmin/sendmail/list';
        $this->load->view('layouts/adminlte3', $data);
    }

    public function ajax_list()
    {
        $csrf_token = $this->input->server('HTTP_X_CSRF_TOKEN');
        $valid_token = $this->security->get_csrf_hash();

        // log_message('debug', 'CSRF Token dari request POST: ' . ($csrf_token ?: 'TIDAK ADA'));
        // log_message('debug', 'CSRF Token yang valid: ' . $valid_token);
        // log_message('debug', 'Session CSRF Token: ' . $this->session->userdata('csrf_token_jkt3'));


        if (empty($csrf_token)) {
            log_message('error', 'CSRF Token kosong, periksa apakah dikirim dari frontend.');
        }

        if ($csrf_token !== $valid_token) {
            echo json_encode(['status' => 'Error', 'message' => 'Invalid CSRF Token']);
            exit();
        }

        $list = $this->M_sendmail->get_datatables();
        $data = [];
        $no = $_POST['start'];
        foreach ($list as $guest) {
            $no++;
            $row = [];
            $row[] = $no;
            $row[] = $guest->name;
            $row[] = $guest->email;
            $row[] = $guest->nim;
            $row[] = $guest->prodi;
            $row[] = '<img src="' . base_url('public/uploads/qrcode/' . $guest->qr_code . '.png') . '" width="20">';
            $row[] = $guest->is_checked_time;
            $row[] = $guest->is_checked_in;
            $row[] = $guest->is_sendmail_time;
            $row[] = $guest->is_sendmail_in
                ? '<span class="badge badge-success">Success</span>'
                : '<span class="badge badge-secondary">Not yet</span>';
            $data[] = $row;
        }

        echo json_encode([
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_sendmail->count_all(),
            "recordsFiltered" => $this->M_sendmail->count_filtered(),
            "data" => $data,
            "csrf_token" => $this->security->get_csrf_hash()
        ]);
    }
}
