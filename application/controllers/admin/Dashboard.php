<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('M_guest');
        $this->load->model('M_settings');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        $data['website'] = $this->M_settings->get_all_settings();
        $data['total_guest']      = $this->M_guest->get_total_guest();
        $data['total_hadir']      = $this->M_guest->get_total_by_hadir(1);
        $data['total_tidak']      = $this->M_guest->get_total_by_tidak(0);


        $data['title'] = 'Dashboard | Admin Panel';
        $data['content'] = 'paneladmin/dashboard';
        $this->load->view('layouts/adminlte3', $data);
    }
}
