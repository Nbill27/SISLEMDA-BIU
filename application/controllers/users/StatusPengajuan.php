<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StatusPengajuan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Tracking_m'); // Load the Tracking model for fetching peng
        $this->load->model('User_m'); // Load the User model for user-related operations
        // Load necessary models, libraries, or helpers here
    }


    public function index()
    {
        $user_id = $this->session->userdata('id_user');
        $riwayat = $this->Tracking_m->get_single_pengajuan($user_id);

        $klasifikasi_all = $this->db->get('klasifikasi_pengajuan')->result();
        $data = [
            'title'             => 'Riwayat Pengajuan',
            'nama'         => $this->session->userdata('name'),
            'active_role'       => $this->session->userdata('active_role'),
            'roles'             => $this->User_m->get_user_roles($this->session->userdata('id_user')),
            'riwayat_pengajuan' => $riwayat,
            'klasifikasi_all'   => $klasifikasi_all,
            'content_view'      => 'user/status_pengajuan/index.php'
        ];

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_user', $data);
        $this->load->view('template/footer',  $data);
    }

    public function detail_status($id_pengajuan = null)
    {
        if (! $id_pengajuan) {
            show_404();
            return;
        }

        $tracking = $this->Tracking_m->get_tracking($id_pengajuan);

        $pengajuan = ! empty($tracking)
            ? $tracking[0]
            : $this->Tracking_m->get_single_pengajuan($id_pengajuan);

        $data['klasifikasi_pengajuan'] = $this->db->get('klasifikasi_pengajuan')->result();
        $data['nama_klasifikasi'] = $pengajuan->nama_klasifikasi;
        $data['active_role'] = $this->session->userdata('active_role');
        $data['roles'] = $this->User_m->get_user_roles($this->session->userdata('id_user'));
        $data['nama'] = $this->session->userdata('name');
        $data['tracking']  = $tracking;
        $data['pengajuan'] = $pengajuan;
        $data['title']     = 'Tracking Surat #' . $id_pengajuan;
        $data['content_view'] = 'user/status_pengajuan/detail_status.php';

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_user', $data);
        $this->load->view('template/footer',  $data);
    }
}
