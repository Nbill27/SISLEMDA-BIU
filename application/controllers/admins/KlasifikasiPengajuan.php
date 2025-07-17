<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KlasifikasiPengajuan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('Admin_m');

        if (
            !$this->session->userdata('logged_in') ||
            strtolower($this->session->userdata('active_role')) != 'admin'
        ) {
            redirect('auth/login');
        }
    }

    public function index()
{
    $unit_filter = $this->input->get('unit'); // Ambil filter dari query string

    if ($unit_filter) {
        $data['klasifikasi_pengajuan'] = $this->Admin_m->get_klasifikasi_pengajuan_by_unit($unit_filter);
    } else {
        $data['klasifikasi_pengajuan'] = $this->Admin_m->get_all_klasifikasi_pengajuan();
    }

    $data['unit_pengaju'] = $this->Admin_m->get_all_unit_pengaju(); // Untuk isi dropdown filter

    $data['title'] = 'Kelola Klasifikasi Pengajuan';
    $data['user_name'] = $this->session->userdata('name');
    $data['content_view'] = 'admin/klasifikasi_pengajuan/index';

    $this->load->view('template/header', $data);
    $this->load->view('template/sidebar_admin', $data);
    $this->load->view('template/footer', $data);
}


    public function add_klasifikasi_pengajuan()
    {
        $data = [];
        $data['title'] = 'Tambah Klasifikasi pengajuan';
        $data['user_name'] = $this->session->userdata('name');
        $data['unit_pengaju'] = $this->Admin_m->get_all_unit_pengaju();

        if ($this->input->post()) {
            // ✅ PERBAIKI NAMA POST INPUT
            $kode_klasifikasi = $this->input->post('kode_klasifikasi');
            $nama_klasifikasi = $this->input->post('nama_klasifikasi');
            $id_unit          = $this->input->post('id_unit');

            // Validasi wajib isi
            if (empty($kode_klasifikasi) || empty($nama_klasifikasi) || empty($id_unit)) {
                $this->session->set_flashdata('error', 'Semua kolom wajib diisi.');
                redirect('admins/klasifikasipengajuan/add_klasifikasi_pengajuan');
            }

            // Cek kode_klasifikasi, sudah ada
            $existing = $this->Admin_m->get_klasifikasi_pengajuan_by_kode($kode_klasifikasi);
            if ($existing) {
                $this->session->set_flashdata('error', 'Kode sudah digunakan. Silakan gunakan kode lain.');
                redirect('admins/klasifikasipengajuan');
            }

            // ✅ PERBAIKI ARRAY UNTUK INSERT
            $data_klasifikasi = [
                'kode_klasifikasi'  => $kode_klasifikasi,
                'nama_klasifikasi'  => $nama_klasifikasi,
                'id_unit'           => $id_unit
            ];

            $this->Admin_m->add_klasifikasi_pengajuan($data_klasifikasi);
            $this->session->set_flashdata('success', 'Data Berhasil Ditambahkan!');
            redirect('admins/klasifikasipengajuan');
        }

        $data['content_view'] = 'admin/klasifikasi_pengajuan/add_klasifikasi_pengajuan';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_admin', $data);
        $this->load->view('template/footer', $data);
    }


    public function edit_klasifikasi_pengajuan($id_klasifikasi)
    {
        $data = [];
        $data['title'] = 'Edit Klasifikasi pengajuan';
        $data['user_name'] = $this->session->userdata('name');
        $data['klasifikasi'] = $this->Admin_m->get_klasifikasi_pengajuan_by_id($id_klasifikasi);
        $data['unit_pengaju'] = $this->Admin_m->get_all_unit_pengaju();

        if (!$data['klasifikasi']) {
            $this->session->set_flashdata('error', 'Data klasifikasi tidak ditemukan.');
            redirect('admins/klasifikasipengajuan');
        }

        if ($this->input->post()) {
            $kode_klasifikasi = $this->input->post('kode_klasifikasi');
            $nama_klasifikasi = $this->input->post('nama_klasifikasi');
            $id_unit = $this->input->post('id_unit');

            if (!$kode_klasifikasi || !$nama_klasifikasi || !$id_unit) {
                $this->session->set_flashdata('error', 'Semua field wajib diisi.');
                redirect('admins/klasifikasipengajuan/edit_klasifikasi_pengajuan/' . $id_klasifikasi);
            }

            $this->db->where('kode_klasifikasi', $kode_klasifikasi);
            $this->db->where('id_klasifikasi !=', $id_klasifikasi);
            $existing = $this->db->get('klasifikasi_pengajuan')->row();

            if ($existing) {
                $this->session->set_flashdata('error', 'Kode pengajuan sudah digunakan. Silahkan gunakan kode lain.');
                redirect('admins/klasifikasipengajuan');
            }

            $data_klasifikasi = [
                'kode_klasifikasi' => $kode_klasifikasi,
                'nama_klasifikasi' => $nama_klasifikasi,
                'id_unit' => $id_unit
            ];

            if ($this->Admin_m->update_klasifikasi_pengajuan($id_klasifikasi, $data_klasifikasi)) {
                $this->session->set_flashdata('success', 'Data Berhasil Diperbarui!');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui data.');
            }
            redirect('admins/klasifikasipengajuan');
        }

        $data['content_view'] = 'admin/klasifikasi_pengajuan/edit_klasifikasi_pengajuan';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_admin', $data);
        $this->load->view('template/footer', $data);
        
    }

    public function delete_klasifikasi_pengajuan($id_klasifikasi)
    {
        $this->Admin_m->delete_klasifikasi_pengajuan($id_klasifikasi);
        $this->session->set_flashdata('success', 'Data Berhasil Dihapus!');
        redirect('admins/klasifikasipengajuan');
    }
}
