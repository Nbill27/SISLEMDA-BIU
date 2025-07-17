<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('admin_m');

        if (
            !$this->session->userdata('logged_in') ||
            strtolower($this->session->userdata('active_role')) != 'admin'
        ) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        $data = [];
        $data['title'] = 'Admin Dashboard';
        $data['user_name'] = $this->session->userdata('name');

        // Data summary untuk cards
        $data['users'] = $this->admin_m->get_all_users_with_roles();
        $data['total_users'] = $this->admin_m->get_total_users();
        $data['prodis'] = $this->admin_m->get_all_prodis();
        $data['fakultas'] = $this->admin_m->get_all_fakultas();
        $data['total_prodis'] = count($data['prodis']);
        $data['total_fakultas'] = count($data['fakultas']);
        $data['total_klasifikasi'] = $this->admin_m->get_total_klasifikasi();
        $data['total_unit_pengaju'] = $this->admin_m->get_total_unit_pengaju();
        $data['total_roles'] = $this->admin_m->get_total_roles();
        $data['total_pengguna_peran'] = $this->admin_m->get_total_pengguna_peran();
        $data['pengajuan_terbaru'] = $this->admin_m->get_pengajuan_terbaru();
        $data['pending_pengajuan'] = $this->admin_m->get_total_pending_pengajuan();

        // -----------------------
        // DATA CHART PER BULAN
        // -----------------------
        $pengajuan_per_bulan = $this->admin_m->get_pengajuan_per_bulan(); // ex: SELECT MONTHNAME(...), COUNT(*) ...
        $labels_bulan = [];
        $data_bulan = [];

        foreach ($pengajuan_per_bulan as $row) {
            $labels_bulan[] = $row['bulan'];
            $data_bulan[] = (int)$row['total'];
        }

        // -----------------------
        // DATA CHART PER HARI (bulan berjalan)
        // -----------------------
        $pengajuan_per_hari = $this->admin_m->get_pengajuan_per_hari_bulan_ini(); // SELECT DAY(...), COUNT(*) ...
        $labels_hari = [];
        $data_hari = [];

        foreach ($pengajuan_per_hari as $row) {
            $labels_hari[] = $row['hari']; // angka tanggal (ex: 01, 02, ...)
            $data_hari[] = (int)$row['total'];
        }

        // Encode ke chart_data
        $data['chart_data'] = json_encode([
            'labels_bulan' => $labels_bulan,
            'data_bulan' => $data_bulan,
            'labels_hari' => $labels_hari,
            'data_hari' => $data_hari,
        ]);

        $data['content_view'] = 'admin/dashboard/index.php';

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_admin', $data);
        $this->load->view('template/footer', $data);
    }
}
