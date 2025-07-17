<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RiwayatPengajuan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Admin_m');

        if (!$this->session->userdata('logged_in') || strtolower($this->session->userdata('active_role')) != 'admin') {
            redirect('auth/login');
        }
    }

    public function index()
    {
        $data['title'] = 'Riwayat Pengajuan';
        $data['user_name'] = $this->session->userdata('name');
        $data['pengajuan_terbaru'] = $this->Admin_m->get_pengajuan_terbaru(); // fungsi sudah ada

        $data['content_view'] = 'admin/riwayat_pengajuan/index';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_admin', $data);
        $this->load->view('template/footer', $data);
    }
}
