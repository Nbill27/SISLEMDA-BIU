<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PenggunaPeran extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(['session', 'form_validation']);
        $this->load->helper(['url', 'form']);
        $this->load->model('admin_m');

        if (!$this->session->userdata('logged_in') || strtolower($this->session->userdata('active_role')) != 'admin') {
            redirect('auth/login');
        }
    }

    public function index()
    {
        $data['title'] = 'Kelola Peran Pengguna';
        $data['user_name'] = $this->session->userdata('name');

        $data['users'] = $this->admin_m->get_all_users_with_roles();
        foreach ($data['users'] as &$user) {
            $user['role_details'] = $this->admin_m->get_user_roles($user['id_user']);
        }

        $data['all_roles'] = $this->admin_m->get_all_roles();
        $data['fakultas_list'] = $this->admin_m->get_all_fakultas();
        $data['prodi_list'] = $this->admin_m->get_all_prodis();

        $data['content_view'] = 'admin/kelola_peran_pengguna/index';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_admin', $data);
        $this->load->view('template/footer', $data);
    }

    public function add_role_action()
    {
        $id_user     = $this->input->post('id_user');
        $id_role     = $this->input->post('id_role');
        $id_fakultas = $this->input->post('id_fakultas');
        $id_prodi    = $this->input->post('id_prodi');

        $role = $this->admin_m->get_role_by_id($id_role);
        $nama_role = strtolower($role['nama_role']);

        // Validasi sesuai role
        if ($nama_role === 'dekan' && !$id_fakultas) {
            $this->session->set_flashdata('error', 'Fakultas wajib diisi untuk role dekan.');
            redirect('admins/penggunaperan');
            return;
        } elseif (in_array($nama_role, ['kaprodi', 'dosen']) && (!$id_fakultas || !$id_prodi)) {
            $this->session->set_flashdata('error', 'Fakultas dan Prodi wajib diisi untuk role kaprodi/dosen.');
            redirect('admins/penggunaperan');
            return;
        }

        // Simpan ke user_role
        $data_user_role = [
            'id_user' => $id_user,
            'id_role' => $id_role,
            'id_fakultas' => in_array($nama_role, ['dekan', 'kaprodi', 'dosen']) ? $id_fakultas : null,
            'id_prodi' => in_array($nama_role, ['kaprodi', 'dosen']) ? $id_prodi : null,
        ];

        $this->db->insert('user_role', $data_user_role);
        $this->session->set_flashdata('success', 'Role berhasil ditambahkan.');
        redirect('admins/penggunaperan');
    }

    public function delete_role_action()
    {
        $id_user = $this->input->post('id_user');
        $id_role = $this->input->post('id_role');

        if (!$id_user || !$id_role) {
            $this->session->set_flashdata('error', 'Data user atau role tidak lengkap.');
            redirect('admins/penggunaperan');
            return;
        }

        $roles = $this->admin_m->get_user_roles($id_user);
        if (count($roles) <= 1) {
            $this->session->set_flashdata('error', 'Pengguna harus memiliki minimal satu role.');
            redirect('admins/penggunaperan');
            return;
        }

        $this->db->where('id_user', $id_user);
        $this->db->where('id_role', $id_role);
        $this->db->delete('user_role');

        $this->session->set_flashdata('success', 'Role berhasil dihapus.');
        redirect('admins/penggunaperan');
    }
}
