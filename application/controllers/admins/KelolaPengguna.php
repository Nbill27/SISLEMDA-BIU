<?php defined('BASEPATH') or exit('No direct script access allowed');

class KelolaPengguna extends CI_Controller
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
        $data['title'] = 'Kelola Pengguna';
        $data['user_name'] = $this->session->userdata('name');
        $data['users'] = $this->admin_m->get_all_users_with_roles();
        $data['roles'] = $this->admin_m->get_all_roles();
        $data['prodis'] = $this->admin_m->get_all_prodis();
        $data['fakultas'] = $this->admin_m->get_all_fakultas();
        $data['content_view'] = 'admin/kelola_pengguna/index';

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_admin', $data);
        $this->load->view('template/footer', $data);
    }

    public function add_user()
    {
        $data = [];
        $data['title'] = 'Tambah Pengguna';
        $data['user_name'] = $this->session->userdata('name');
        $data['roles'] = $this->admin_m->get_all_roles();
        $data['prodis'] = $this->admin_m->get_all_prodis();
        $data['fakultas'] = $this->admin_m->get_all_fakultas();

        if ($this->input->post()) {
            $post = $this->input->post();

            // Cek duplikat
            if ($this->admin_m->is_duplicate('nik', $post['nik'])) {
                $this->session->set_flashdata('error', 'NIK sudah digunakan.');
                redirect('admins/kelolapengguna'); return;
            }
            if ($this->admin_m->is_duplicate('username', $post['username'])) {
                $this->session->set_flashdata('error', 'Username sudah digunakan.');
                redirect('admins/kelolapengguna'); return;
            }
            if ($this->admin_m->is_duplicate('inisial', $post['inisial'])) {
                $this->session->set_flashdata('error', 'Inisial sudah digunakan.');
                redirect('admins/kelolapengguna'); return;
            }
            if ($this->admin_m->is_duplicate('email', $post['email'])) {
                $this->session->set_flashdata('error', 'Email sudah digunakan.');
                redirect('admins/kelolapengguna'); return;
            }

            $data_user = [
                'nama' => $post['nama'],
                'username' => $post['username'],
                'password' => md5($post['password']),
                'inisial' => $post['inisial'],
                'email' => $post['email'],
                'nik' => $post['nik']
            ];

            $user_id = $this->admin_m->add_user($data_user);

            $role_id = $post['role_id'];
            if (!empty($role_id)) {
                $prodi_id = $post['prodi_id'] ?: null;
                $fakultas_id = $post['fakultas_id'] ?: null;

                $this->db->insert('user_role', [
                    'id_user' => $user_id,
                    'id_role' => $role_id,
                    'id_prodi' => $prodi_id,
                    'id_fakultas' => $fakultas_id
                ]);
            }

            $this->session->set_flashdata('success', 'Pengguna berhasil ditambahkan');
            redirect('admins/kelolapengguna');
        }

        $data['content_view'] = 'admin/kelola_pengguna/add_user';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_admin', $data);
        $this->load->view('template/footer', $data);
    }

    public function edit_user($user_id)
    {
        $data = [];
        $data['title'] = 'Edit Pengguna';
        $data['user_name'] = $this->session->userdata('name');
        $data['user'] = $this->admin_m->get_user_by_id_with_prodi_fakultas($user_id);
        $data['roles'] = $this->admin_m->get_all_roles();
        $data['prodis'] = $this->admin_m->get_all_prodis();
        $data['fakultas'] = $this->admin_m->get_all_fakultas();

        if ($this->input->post()) {
            $post = $this->input->post();
            $username = $post['username'];
            $inisial = $post['inisial'];
            $nik = $post['nik'];
            $email = $post['email'];

            // Cek duplikat (kecuali dirinya sendiri)
            if ($this->admin_m->is_duplicate_username($username, $user_id)) {
                $this->session->set_flashdata('error', 'Username sudah digunakan.');
                redirect('admins/kelolapengguna'); return;
            }
            if ($this->admin_m->is_duplicate_inisial($inisial, $user_id)) {
                $this->session->set_flashdata('error', 'Inisial sudah digunakan.');
                redirect('admins/kelolapengguna'); return;
            }
            if ($this->admin_m->is_duplicate_nik($nik, $user_id)) {
                $this->session->set_flashdata('error', 'NIK sudah digunakan.');
                redirect('admins/kelolapengguna'); return;
            }
            if ($this->admin_m->is_duplicate_email($email, $user_id)) {
                $this->session->set_flashdata('error', 'Email sudah digunakan.');
                redirect('admins/kelolapengguna'); return;
            }

            // Update data user
            $data_user = [
                'nama' => $post['nama'],
                'username' => $username,
                'inisial' => $inisial,
                'email' => $email,
                'nik' => $nik
            ];

            if (!empty($post['password'])) {
                $data_user['password'] = md5($post['password']);
            }

            $this->admin_m->update_user($user_id, $data_user);

            // Update user_role
            $role_id = $post['role_id'];
            if (!empty($role_id)) {
                $prodi_id = $post['prodi_id'] ?: null;
                $fakultas_id = $post['fakultas_id'] ?: null;

                $this->db->where('id_user', $user_id);
                $this->db->update('user_role', [
                    'id_role' => $role_id,
                    'id_prodi' => $prodi_id,
                    'id_fakultas' => $fakultas_id
                ]);
            }

            $this->session->set_flashdata('success', 'Pengguna berhasil diperbarui');
            redirect('admins/kelolapengguna');
        }

        $data['content_view'] = 'admin/kelola_pengguna/edit_user';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_admin', $data);
        $this->load->view('template/footer', $data);
    }

    public function delete_user($user_id)
    {
        $this->admin_m->delete_user($user_id);
        $this->session->set_flashdata('success', 'Pengguna berhasil dihapus');
        redirect('admins/kelolapengguna');
    }
}