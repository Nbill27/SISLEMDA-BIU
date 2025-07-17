<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KelolaPeran extends CI_Controller
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
        $data = [
            'title' => 'Kelola Peran',
            'user_name' => $this->session->userdata('name'),
            'roles' => $this->admin_m->get_all_roles(),
            'content_view' => 'admin/kelola_peran/index'
        ];

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_admin', $data);
        $this->load->view('template/footer', $data);
    }

    public function add_role()
    {
        if ($this->input->post()) {
            $role_code = $this->input->post('kode_role');
            $role_name = $this->input->post('nama_role');

            $existing_role = $this->admin_m->get_role_by_name($role_code, $role_name);

            if (!$existing_role) {
                $data_role = [
                    'kode_role' => $role_code,
                    'nama_role' => $role_name
                ];
                $this->admin_m->add_role($data_role);
                $this->session->set_flashdata('success', 'Role baru berhasil ditambahkan');
            } else {
                $this->session->set_flashdata('error', 'Kode role atau nama role sudah ada');
            }

            redirect('admins/kelolaperan');
        }
    }

    public function edit_role_action()
    {
        $id_role = $this->input->post('id_role');
        $role_code = $this->input->post('kode_role');
        $role_name = $this->input->post('nama_role');

        if (!$id_role || !$role_code || !$role_name) {
            $this->session->set_flashdata('error', 'Data tidak lengkap');
            redirect('admins/kelolaperan');
        }

        $existing_role = $this->admin_m->get_role_by_name($role_name);

        if ($existing_role && $existing_role['id_role'] != $id_role) {
            $this->session->set_flashdata('error', 'Nama role sudah digunakan');
        } else {
            $data_role = [
                'kode_role' => $role_code,
                'nama_role' => $role_name
            ];
            $this->admin_m->update_role($id_role, $data_role);
            $this->session->set_flashdata('success', 'Role berhasil diperbarui');
        }

        redirect('admins/kelolaperan');
    }

    public function delete_role($id_role)
    {
        $this->db->where('id_role', $id_role);
        $this->db->from('user_role');
        $count = $this->db->count_all_results();

        if ($count > 0) {
            $this->session->set_flashdata('error', 'Role tidak dapat dihapus karena sedang digunakan oleh user');
        } else {
            $this->admin_m->delete_role($id_role);
            $this->session->set_flashdata('success', 'Role berhasil dihapus');
        }

        redirect('admins/kelolaperan');
    }
}
