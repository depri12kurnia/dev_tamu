<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Activity extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_settings');
        $this->load->model('M_log_user');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }
        if (!$this->ion_auth->in_group('admin')) {
            show_error('You do not have permission to access this page.');
        }
    }

    public function index()
    {
        $data['website'] = $this->M_settings->get_all_settings();
        // 
        $data['title'] = 'Activity | Admin Panel';
        $data['content'] = 'paneladmin/logs/activity';
        $this->load->view('layouts/adminlte3', $data);
    }

    public function get_data()
    {
        $csrf_token = $this->input->server('HTTP_X_CSRF_TOKEN');
        $valid_token = $this->security->get_csrf_hash();

        log_message('debug', 'CSRF Token dari request POST: ' . ($csrf_token ?: 'TIDAK ADA'));
        log_message('debug', 'CSRF Token yang valid: ' . $valid_token);
        log_message('debug', 'Session CSRF Token: ' . $this->session->userdata('csrf_token_jkt3'));


        if (empty($csrf_token)) {
            log_message('error', 'CSRF Token kosong, periksa apakah dikirim dari frontend.');
        }

        if ($csrf_token !== $valid_token) {
            echo json_encode(['status' => 'Error', 'message' => 'Invalid CSRF Token']);
            exit();
        }

        $list = $this->M_log_user->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $crud) {
            $no++;
            $row = array();
            $row[] = $crud->id;
            $row[] = $crud->user_id;
            $row[] = $crud->action;
            $row[] = $crud->timestamp;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_log_user->count_all(),
            "recordsFiltered" => $this->M_log_user->count_filtered(),
            "data" => $data,
            "csrf_token" => $this->security->get_csrf_hash() // Kirim token CSRF baru
        );
        echo json_encode($output);
    }

    public function delete_all_activity()
    {
        $deleted_rows = $this->M_log_user->delete_all_activity();
        echo "Deleted $deleted_rows old log(s)";
    }
}
