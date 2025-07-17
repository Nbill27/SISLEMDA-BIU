<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KelolaProdi extends CI_Controller
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
        $data['title'] = 'Kelola Prodi';
        $data['user_name'] = $this->session->userdata('name');
        $data['prodis'] = $this->admin_m->get_all_prodis();
        $data['fakultas'] = $this->admin_m->get_all_fakultas();
        $data['content_view'] = 'admin/kelola_prodi/index';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_admin', $data);
        $this->load->view('template/footer', $data);
    }

    public function add_prodi()
    {
        $data = [];
        $data['title'] = 'Tambah Prodi';
        $data['user_name'] = $this->session->userdata('name');
        $data['fakultas'] = $this->admin_m->get_all_fakultas();

        if ($this->input->post()) {
            $nama_prodi = $this->input->post('nama_prodi');
            $id_fakultas = $this->input->post('id_fakultas');

            // Cek apakah nama prodi sudah ada
            if ($this->admin_m->check_prodi_exists($nama_prodi, $id_fakultas)) {
                $this->session->set_flashdata('error', 'Nama prodi sudah ada di fakultas tersebut!');
                redirect('admins/kelolaprodi');
            }

            $data_prodi = [
                'nama_prodi' => $nama_prodi,
                'id_fakultas' => $id_fakultas
            ];

            $this->admin_m->add_prodi($data_prodi);
            $this->session->set_flashdata('success', 'Prodi berhasil ditambahkan');
            redirect('admins/kelolaprodi');
        }

        $data['content_view'] = 'admin/kelola_prodi/add_prodi';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_admin', $data);
        $this->load->view('template/footer', $data);
    }

    public function edit_prodi($id_prodi)
    {
        $data = [];
        $data['title'] = 'Edit Prodi';
        $data['user_name'] = $this->session->userdata('name');
        $data['prodi'] = $this->admin_m->get_prodi_by_id($id_prodi);
        $data['fakultas'] = $this->admin_m->get_all_fakultas();

        if ($this->input->post()) {
            $nama_prodi = $this->input->post('nama_prodi');
            $id_fakultas = $this->input->post('id_fakultas');

            // Cek apakah sudah ada prodi dengan nama & fakultas yang sama (kecuali dirinya sendiri)
            if ($this->admin_m->check_prodi_exists($nama_prodi, $id_fakultas, $id_prodi)) {
                $this->session->set_flashdata('error', 'Nama prodi sudah ada pada fakultas tersebut!');
                redirect('admins/kelolaprodi');
            }

            $data_prodi = [
                'nama_prodi' => $nama_prodi,
                'id_fakultas' => $id_fakultas
            ];
            $this->admin_m->update_prodi($id_prodi, $data_prodi);
            $this->session->set_flashdata('success', 'Prodi berhasil diperbarui');
            redirect('admins/kelolaprodi');
        }

        $data['content_view'] = 'admin/kelola_prodi/edit_prodi';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_admin', $data);
        $this->load->view('template/footer', $data);
    }

    public function delete_prodi($id_prodi)
    {
        $this->admin_m->delete_prodi($id_prodi);
        $this->session->set_flashdata('success', 'Prodi berhasil dihapus');
        redirect('admins/kelolaprodi');
    }
}
