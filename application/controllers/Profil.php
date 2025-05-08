<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profil extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_settings');
        $this->load->model('M_profile');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        $data['website'] = $this->M_settings->get_all_settings();
        $data['title'] = 'Profile | Admin Panel';
        $data['content'] = 'profil/view_profil';
        $this->load->view('layouts/adminlte3', $data);
    }

    public function upload_photo()
    {
        $config['upload_path'] = './public/photos/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size'] = 2048; // 2MB
        $config['encrypt'] = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('photo')) {
            echo '<div class="alert alert-danger">' . $this->upload->display_errors() . '</div>';
        } else {
            $data = $this->upload->data();
            // Update user's photo path in the database
            $user_id = $this->session->userdata('user_id');
            $photo = $data['photo']['file_name'];

            $this->load->model('M_profile');
            $this->M_profile->update_photo($user_id, $photo);
            $this->M_profile->set_flashdata('upload_success', 'Photo uploaded successfully.');

            echo '<div class="alert alert-success">Photo uploaded successfully.</div>';
        }
    }
}
