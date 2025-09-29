<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Guest extends CI_Controller
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

        $this->load->library('ciqrcode');
    }

    public function index()
    {
        $data['website'] = $this->M_settings->get_all_settings();
        $data['title'] = 'Guest | Admin Panel';
        $data['content'] = 'paneladmin/guest/list';
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

        $list = $this->M_guest->get_datatables();
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
            $row[] = '<img src="' . base_url('public/uploads/qrcode/' . $guest->qr_code . '.png') . '" width="50">';
            $row[] = $guest->is_checked_time;
            $row[] = $guest->is_checked_in
                ? '<span class="badge badge-success">Hadir</span>'
                : '<span class="badge badge-secondary">Belum</span>';

            // Tombol aksi
            $action = '
        <a class="btn btn-primary btn-sm" href="javascript:void(0)" title="Edit" onclick="edit_guest(' . "'" . $guest->id . "'" . ')">
            <i class="fa fa-edit"></i>
        </a>
        <a class="btn btn-danger btn-sm" href="javascript:void(0)" title="Delete" onclick="delete_guest(' . "'" . $guest->id . "'" . ')">
            <i class="fa fa-trash"></i>
        </a>';

            if ($guest->is_checked_in == 0) {
                $action .= '
        <a class="btn btn-success btn-sm" href="javascript:void(0)" title="Manual Check-in" onclick="manualCheckin(' . $guest->id . ')">
            <i class="fa fa-check"></i>
        </a>';
            } else {
                $action .= '';
            }

            $row[] = $action;
            $data[] = $row;
        }

        echo json_encode([
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_guest->count_all(),
            "recordsFiltered" => $this->M_guest->count_filtered(),
            "data" => $data,
            "csrf_token" => $this->security->get_csrf_hash()
        ]);
    }

    public function ajax_manual_checkin()
    {
        $this->output->set_content_type('application/json');
        // Mendapatkan user yang login
        $user = $this->ion_auth->user()->row();

        $id = $this->input->post('id', TRUE);
        $success = $this->M_guest->manual_checkin($id);

        // Menyimpan log aktivitas login
        $this->M_log_user->save_log($user->id, 'Checkin Manual Berhasil');

        echo json_encode([
            'status' => $success,
            'csrf_token' => $this->security->get_csrf_hash(),
            'message' => $success ? 'Check-in berhasil' : 'Gagal check-in'
        ]);
    }

    public function ajax_add()
    {
        $this->validate_csrf(); // Validasi CSRF

        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $nim = $this->input->post('nim');
        $prodi = $this->input->post('prodi');
        // $qr_code = md5(uniqid());
        $qr_code = $this->input->post('nim');

        $params = [
            'data' => $qr_code,
            'level' => 'H',
            'size' => 10,
            'savename' => FCPATH . 'public/uploads/qrcode/' . $qr_code . '.png'
        ];
        $this->ciqrcode->generate($params);

        $this->M_guest->save([
            'name' => $name,
            'email' => $email,
            'nim' => $nim,
            'prodi' => $prodi,
            'qr_code' => $qr_code
        ]);

        echo json_encode([
            "status" => TRUE,
            "message" => "add data failed",
            "csrf_token" => $this->security->get_csrf_hash() // Token CSRF tetap dikirim
        ]);
    }

    public function ajax_edit($id)
    {
        echo json_encode($this->M_guest->get_by_id($id));
    }

    public function ajax_update()
    {
        $this->validate_csrf(); // Validasi CSRF

        $qr_code = $this->input->post('nim');
        $params = [
            'data' => $qr_code,
            'level' => 'H',
            'size' => 10,
            'savename' => FCPATH . 'public/uploads/qrcode/' . $qr_code . '.png'
        ];
        $this->ciqrcode->generate($params);
        $this->M_guest->update([
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'nim' => $this->input->post('nim'),
            'prodi' => $this->input->post('prodi'),
            'qr_code' => $qr_code
        ], $this->input->post('id'));

        echo json_encode([
            "status" => TRUE,
            "message" => "update data failed",
            "csrf_token" => $this->security->get_csrf_hash() // Token CSRF tetap dikirim
        ]);
    }

    public function ajax_delete($id)
    {
        $this->validate_csrf();

        $guest = $this->M_guest->get_by_id($id);
        @unlink(FCPATH . 'public/uploads/qrcode/' . $guest->qr_code . '.png');
        $this->M_guest->delete_by_id($id);
        echo json_encode([
            "status" => TRUE,
            "message" => "delete data failed",
            "csrf_token" => $this->security->get_csrf_hash() // Token CSRF tetap dikirim
        ]);
    }

    public function import_excel()
    {
        $this->validate_csrf();

        $this->load->library('upload');

        $config['upload_path'] = './public/uploads/';
        $config['allowed_types'] = 'xlsx';
        $config['max_size'] = 4048;

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file_excel')) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'message' => $this->upload->display_errors(),
                    'csrf_token' => $this->security->get_csrf_hash()
                ]));
        }

        require_once(FCPATH . 'vendor/autoload.php');
        $file = $this->upload->data();
        $filePath = './public/uploads/' . $file['file_name'];

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getActiveSheet()->toArray();

        $imported = 0;
        for ($i = 1; $i < count($sheet); $i++) {
            $name = $sheet[$i][0] ?? null;
            $email = $sheet[$i][1] ?? null;
            $nim = $sheet[$i][2] ?? null;
            $prodi = $sheet[$i][3] ?? null;
            if (!$name) continue;

            // $qr_code = md5(uniqid());
            $params = [
                'data' => $nim,
                'level' => 'H',
                'size' => 10,
                'savename' => FCPATH . 'public/uploads/qrcode/' . $nim . '.png'
            ];
            $this->ciqrcode->generate($params);

            $this->M_guest->save([
                'name' => $name,
                'email' => $email,
                'nim' => $nim,
                'prodi' => $prodi,
                'qr_code' => $nim
            ]);

            $imported++;
        }

        unlink($filePath); // hapus file setelah digunakan

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'message' => "Berhasil mengimpor $imported tamu.",
                'csrf_token' => $this->security->get_csrf_hash()
            ]));
    }

    public function print_labels()
    {
        $data['guests'] = $this->M_guest->get_all(); // Ambil semua tamu

        $this->load->view('paneladmin/guest/print', $data); // Tampilkan view cetak
    }

    public function print_label_pdf()
    {
        $this->load->library('dompdf_gen');

        $data['guests'] = $this->M_guest->get_all(); // Ambil semua tamu
        $html = $this->load->view('paneladmin/guest/label_pdf', $data, TRUE);

        $this->dompdf_gen->dompdf->loadHtml($html);
        $this->dompdf_gen->dompdf->setPaper([0, 0, 595, 468], 'landscape'); // ukuran 210mm × 165mm
        $this->dompdf_gen->dompdf->render();
        $this->dompdf_gen->dompdf->stream("label_qr_code.pdf", array("Attachment" => false));
    }



    private function validate_csrf()
    {
        $csrf_token = $this->input->server('HTTP_X_CSRF_TOKEN');
        $valid_token = $this->security->get_csrf_hash();

        if ($csrf_token !== $valid_token) {
            echo json_encode(['status' => 'Error', 'message' => 'Invalid CSRF Token']);
            exit();
        }
    }

    // Report Guest
    public function report()
    {
        $data['title'] = 'Report | Admin Panel';
        $data['content'] = 'paneladmin/guest/report';
        $this->load->view('layouts/adminlte3', $data);
    }

    public function get_report_data()
    {
        $start = $this->input->post('start');
        $end = $this->input->post('end');
        $data = $this->M_guest->get_report($start, $end);

        echo json_encode($data);
    }

    public function export_excel()
    {
        $this->load->helper('download');

        $guests = $this->M_guest->get_report(
            $this->input->get('start'),
            $this->input->get('end')
        );

        $content = "Nama\tEmail\tNIM\tProdi\tCheck-in\tWaktu\n";
        foreach ($guests as $g) {
            $content .= "{$g->name}\t{$g->email}\t{$g->nim}\t{$g->prodi}\t"
                . ($g->is_checked_in ? 'Hadir' : 'Tidak Hadir') . "\t{$g->is_checked_time}\n";
        }

        force_download('laporan_tamu.xls', $content);
    }

    public function reset_status()
    {
        $this->validate_csrf();

        // Reset semua tamu ke status belum hadir
        $this->M_guest->reset_status_all();

        echo json_encode([
            "status" => TRUE,
            "message" => "Status semua tamu berhasil direset ke belum hadir.",
            "csrf_token" => $this->security->get_csrf_hash()
        ]);
    }
}
