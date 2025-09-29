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
            // $row[] = $guest->is_checked_time;
            // $row[] = $guest->is_checked_in;
            $row[] = $guest->is_sendmail_time;
            $row[] = $guest->is_sendmail_in
                ? '<span class="badge badge-success">Success</span>'
                : '<span class="badge badge-secondary">Not yet</span>';
            // Tambahkan tombol kirim email
            $row[] = '<button class="btn btn-sm btn-primary" onclick="sendMail(\'' . $guest->email . '\')">Kirim Email</button>';
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

    public function send_email()
    {
        $email = $this->input->post('email');
        $guest = $this->M_sendmail->get_by_email($email);

        if (!$guest) {
            echo json_encode(['status' => false, 'message' => 'Guest not found']);
            return;
        }

        // Path template dan QR code
        $template_path = FCPATH . 'public/uploads/template_undangan.png';
        $qr_path = FCPATH . 'public/uploads/qrcode/' . $guest->qr_code . '.png';

        // Load template dan QR code
        $template = imagecreatefrompng($template_path);
        $qr = imagecreatefrompng($qr_path);

        // Resize QR code jika perlu
        $qr_width = 200; // lebar QR code di undangan
        $qr_height = 200;
        $qr_resized = imagecreatetruecolor($qr_width, $qr_height);
        imagealphablending($qr_resized, false);
        imagesavealpha($qr_resized, true);
        imagecopyresampled($qr_resized, $qr, 0, 0, 0, 0, $qr_width, $qr_height, imagesx($qr), imagesy($qr));

        // Tempel QR code ke template (atur posisi X,Y sesuai kebutuhan)
        $pos_x = 400; // contoh posisi X
        $pos_y = 600; // contoh posisi Y
        imagecopy($template, $qr_resized, $pos_x, $pos_y, 0, 0, $qr_width, $qr_height);

        // Simpan hasil undangan personalisasi
        $output_path = FCPATH . 'public/uploads/undangan/undangan_' . $guest->qr_code . '.png';
        imagepng($template, $output_path);

        // Bersihkan memory
        imagedestroy($template);
        imagedestroy($qr);
        imagedestroy($qr_resized);

        // Load config email secara eksplisit
        $this->load->config('email');
        $email_config = $this->config->config;

        $this->load->library('email');
        $this->email->initialize($email_config);

        $this->email->from('noreply@poltekkesjakarta3.ac.id', 'Panitia');
        $this->email->to($guest->email);
        $this->email->subject('Undangan Acara Wisuda');
        $this->email->message('Halo ' . $guest->name . ',<br>Ini adalah undangan acara wisuda Poltekkes Kemenkes Jakarta III.<br>Silakan lihat undangan terlampir.<br>Terima kasih.');

        // Attach undangan
        $this->email->attach($output_path);

        if ($this->email->send()) {
            $this->M_sendmail->upsend($guest->email);
            echo json_encode(['status' => true, 'message' => 'Email berhasil dikirim']);
        } else {
            echo json_encode(['status' => false, 'message' => 'Gagal mengirim email: ' . $this->email->print_debugger()]);
        }
    }
}
