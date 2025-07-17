<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Disposisi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Disposisi_m');
        $this->load->model('User_m');
        $this->load->model('Arsip_m');
        $this->load->library('session');
        if (!$this->session->userdata('id_user')) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        $data['title'] = 'Disposisi Masuk';
        $data['disposisi'] = $this->Disposisi_m->getDisposisiMasuk();
        $data['active_role'] = $this->session->userdata('active_role');
        $data['nama'] = $this->session->userdata('name');
        $data['roles'] = $this->User_m->get_user_roles($this->session->userdata('id_user'));
        $data['content_view'] = 'user/disposisi/index.php';

        $this->load->view('template/header',  $data);
        $this->load->view('template/sidebar_user', $data);
        $this->load->view('template/footer', $data);
    }

    public function detail($id)
    {
        $data['title'] = 'Detail Arsip Pengajuan';
        $data['roles'] = $this->User_m->get_user_roles($this->session->userdata('id_user'));
        $data['active_role'] = $this->session->userdata('active_role');
        $data['nama'] = $this->session->userdata('name');

        $active_code = $this->session->userdata('active_role_code');
        $id_user = $this->session->userdata('id_user');

        // 🔍 Tambahkan log untuk debug

        // $data['unit_list'] = [
        //     ['id_user' => 1, 'nama_role' => 'Kaprodi', 'nama_prodi' => 'Teknik Informatika'],
        //     ['id_user' => 2, 'nama_role' => 'Kaprodi', 'nama_prodi' => 'Sistem Informasi']
        // ];
        // Ganti dari getAllRoles() jadi fungsi berdasarkan role aktif
        $data['unit_list'] = $this->Disposisi_m->getAvailableTargetRoles($active_code, $id_user);
        $data['pengajuan'] = $this->Disposisi_m->get_detail_pengajuan_by_id($id);
        $data['content_view'] = 'user/disposisi/detail_pengajuan.php';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_user', $data);
        $this->load->view('template/footer', $data);
    }



    // public function view($file_name)
    // {
    //     $file_path = FCPATH . 'assets/uploads/' . urldecode($file_name);
    //     if (file_exists($file_path)) {
    //         $mime = mime_content_type($file_path) ?: 'application/pdf';
    //         header('Content-Type: ' . $mime);
    //         header('Content-Disposition: inline; filename="' . $file_name . '"');
    //         header('Content-Length: ' . filesize($file_path));
    //         readfile($file_path);
    //         exit;
    //     } else {
    //         show_404();
    //     }
    // }

    // public function download($file_name)
    // {
    //     $file_path = FCPATH . 'assets/uploads/' . urldecode($file_name);
    //     if (file_exists($file_path)) {
    //         $mime = mime_content_type($file_path) ?: 'application/octet-stream';
    //         header('Content-Type: ' . $mime);
    //         header('Content-Disposition: attachment; filename="' . $file_name . '"');
    //         header('Content-Length: ' . filesize($file_path));
    //         readfile($file_path);
    //         exit;
    //     } else {
    //         show_404();
    //     }
    // }

    public function kirim()
    {
        $this->Disposisi_m->kirim_disposisi();
    }
}
