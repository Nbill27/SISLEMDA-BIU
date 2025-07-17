<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UnitPengaju extends CI_Controller
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
        $data['title'] = 'Kelola Unit Pengaju';
        $data['user_name'] = $this->session->userdata('name');
        $data['unit_pengaju'] = $this->admin_m->get_all_unit_pengaju();

        $data['content_view'] = 'admin/unit_pengaju/index';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_admin', $data);
        $this->load->view('template/footer', $data);
    }

    public function add_unit_pengaju()
    {
        $data = [];
        $data['title'] = 'Tambah Unit pengaju';
        $data['user_name'] = $this->session->userdata('name');

        if ($this->input->post()) {
            $kode_unit = $this->input->post('kode_unit');

            // Cek apakah kode_unit sudah ada
            $this->db->where('kode_unit', $kode_unit);
            $existing = $this->db->get('unit_pengaju')->row();

            if ($existing) {
                $this->session->set_flashdata('error', 'Kode unit sudah digunakan. Silahkan gunakan kode lain.');
                redirect('admins/unitpengaju');
            } else {
                $data_unit = [
                    'kode_unit' => $kode_unit,
                    'nama_unit' => $this->input->post('nama_unit')
                ];
                $this->admin_m->add_unit_pengaju($data_unit);
                $this->session->set_flashdata('success', 'Data Berhasil Ditambahkan!');
                redirect('admins/unitpengaju');
            }
        }

        $data['content_view'] = 'admin/unit_pengaju/add_unit_pengaju';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_admin', $data);
        $this->load->view('template/footer', $data);
    }

    public function edit_unit_pengaju($id_unit)
    {
        $data = [];
        $data['title'] = 'Edit Unit Pengajuan';
        $data['user_name'] = $this->session->userdata('name');
        $data['unit'] = $this->admin_m->get_unit_pengaju_by_id($id_unit);

        if ($this->input->post()) {
            $kode_unit = $this->input->post('kode_unit');

            // Cek apakah kode_unit sudah ada untuk unit lain
            $this->db->where('kode_unit', $kode_unit);
            $this->db->where('id_unit !=', $id_unit);
            $existing = $this->db->get('unit_pengaju')->row();

            if ($existing) {
                $this->session->set_flashdata('error', 'Kode unit sudah digunakan. Silahkan gunakan kode lain.');
                redirect('admins/unitpengaju');
            } else {
                $data_unit = [
                    'kode_unit' => $kode_unit,
                    'nama_unit' => $this->input->post('nama_unit')
                ];
                $this->admin_m->update_unit_pengaju($id_unit, $data_unit);
                $this->session->set_flashdata('success', 'Data Berhasil Diperbarui!');
                redirect('admins/unitpengaju');
            }
        }

        $data['content_view'] = 'admin/unit_pengaju/edit_unit_pengaju';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_admin', $data);
        $this->load->view('template/footer', $data);
    }

    public function delete_unit_pengaju($id_unit)
    {
        $this->admin_m->delete_unit_pengaju($id_unit);
        $this->session->set_flashdata('success', 'Data Berhasil Dihapus!');
        redirect('admins/unitpengaju');
    }
}
