<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('admin_m');
    }

    public function index()
    {
        $this->load->view('auth/login');
    }

    public function login()
    {
        if ($this->session->userdata('logged_in')) {
            $role = strtolower($this->session->userdata('active_role'));
            if ($role == 'admin') {
                redirect('admins/dashboard');
            } else {
                redirect('users/dashboard');
            }
        }

        if ($this->input->post()) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $this->db->where('username', $username);
            $query = $this->db->get('user');
            $user = $query->row_array();

            if ($user) {
                $hashed_password = md5($password); // Gantilah dengan bcrypt untuk keamanan
                if ($hashed_password == $user['password']) {
                    // Set session dasar
                    $this->session->set_userdata([
                        'logged_in' => true,
                        'id_user' => $user['id_user'],
                        'username' => $user['username'],
                        'name' => $user['nama'],
                    ]);

                    // Ambil semua role yang dimiliki user
                    $roles = $this->admin_m->get_user_roles($user['id_user']);
                    if (empty($roles)) {
                        $this->session->set_flashdata('error', 'User tidak memiliki role!');
                        redirect('auth/login');
                    } elseif (count($roles) > 1) {
                        $data['roles'] = $roles;
                        $this->load->view('auth/choose_role', $data);
                    } else {
                        // Jika hanya satu role
                        $this->session->set_userdata([
                            'active_role' => $roles[0]['nama_role'],
                            'active_role_id' => $roles[0]['id_role'],
                            'active_role_code' => $roles[0]['kode_role']
                        ]);

                        $role_name = strtolower($roles[0]['nama_role']);
                        if ($role_name == 'admin') {
                            redirect('admins/dashboard');
                        } else {
                            redirect('users/dashboard');
                        }
                    }
                } else {
                    $this->session->set_flashdata('error', 'Password salah!');
                    redirect('auth/login');
                }
            } else {
                $this->session->set_flashdata('error', 'Username tidak ditemukan!');
                redirect('auth/login');
            }
        } else {
            $this->load->view('auth/login');
        }
    }

    public function choose_role()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        $selected_role = $this->input->post('role');
        $user_id = $this->session->userdata('id_user');
        $roles = $this->admin_m->get_user_roles($user_id);

        if ($selected_role) {
            foreach ($roles as $r) {
                if ($r['nama_role'] == $selected_role) {
                    $this->session->set_userdata([
                        'active_role' => $r['nama_role'],
                        'active_role_id' => $r['id_role'],
                        'active_role_code' => $r['kode_role']
                    ]);

                    $role_lower = strtolower($r['nama_role']);
                    if ($role_lower == 'admin') {
                        redirect('admins/dashboard');
                    } else {
                        redirect('users/dashboard');
                    }
                }
            }

            $this->session->set_flashdata('error', 'Role tidak valid!');
            $data['roles'] = $roles;
            $this->load->view('auth/choose_role', $data);
        } else {
            $this->session->set_flashdata('error', 'Silakan pilih role terlebih dahulu!');
            $data['roles'] = $roles;
            $this->load->view('auth/choose_role', $data);
        }
    }

    public function logout()
    {
        $this->session->unset_userdata([
            'logged_in',
            'id_user',
            'username',
            'name',
            'active_role',
            'active_role_id',
            'active_role_code'
        ]);
        $this->session->set_flashdata('success', 'Anda telah logout.');
        redirect('auth');
    }

    // ================= GANTI PERAN =================
    public function set_role($kode_role)
    {
        // Harus login dulu
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        $id_user = $this->session->userdata('id_user');
        $available_roles = $this->admin_m->get_user_roles($id_user); // pastikan ini ambil kode_role juga

        foreach ($available_roles as $role) {
            if (strtolower($role['kode_role']) == strtolower($kode_role)) {
                // Set session baru untuk role aktif
                $this->session->set_userdata([
                    'active_role' => $role['nama_role'],         // tetap pakai nama untuk tampilan
                    'active_role_id' => $role['id_role'],
                    'active_role_code' => $role['kode_role']     // ini yang penting untuk backend
                ]);

                $this->session->set_flashdata('success', 'Selamat! Anda berhasil ganti peran ke ' . ucwords($role['nama_role']));

                // Arahkan berdasarkan role
                if ($role['kode_role'] == 'adm') {
                    redirect('admins/dashboard');
                } else {
                    redirect('users/dashboard');
                }
            }
        }

        // Kalau role tidak valid
        $this->session->set_flashdata('error', 'Peran tidak valid atau tidak dimiliki!');
        redirect('auth');
    }



    // ================= LUPA PASSWORD =================

    // Step 1: Tampilkan form input username
    public function lupa_password()
    {
        $this->load->view('auth/lupa_password');
    }

    // Step 2: Proses form username
    public function lupa_password_action()
    {
        $username = $this->input->post('username');
        $user = $this->db->get_where('user', ['username' => $username])->row_array();

        if ($user) {
            // Simpan ke session sementara
            $this->session->set_userdata('reset_user_id', $user['id_user']);
            redirect('auth/ubah_password');
        } else {
            $this->session->set_flashdata('error', 'Username tidak ditemukan.');
            redirect('auth/lupa_password');
        }
    }

    // Step 3: Tampilkan form ubah password
    public function ubah_password()
    {
        if (!$this->session->userdata('reset_user_id')) {
            redirect('auth/lupa_password');
        }

        // Ambil data user dari DB untuk menampilkan username
        $id_user = $this->session->userdata('reset_user_id');
        $user = $this->db->get_where('user', ['id_user' => $id_user])->row_array();

        $data['username'] = $user ? $user['username'] : '';

        $this->load->view('auth/ubah_password', $data);
    }

    // Step 4: Simpan password baru
    public function ubah_password_action()
    {
        $id_user = $this->session->userdata('reset_user_id');
        if (!$id_user) {
            redirect('auth/lupa_password');
        }

        $password = $this->input->post('password');
        $confirm = $this->input->post('confirm_password');

        if ($password != $confirm) {
            $this->session->set_flashdata('error', 'Password tidak cocok.');
            redirect('auth/ubah_password');
        }

        $this->db->where('id_user', $id_user);
        $this->db->update('user', ['password' => md5($password)]);

        $this->session->unset_userdata('reset_user_id');
        $this->session->set_flashdata('success', 'Password berhasil diubah. Silakan login kembali.');
        redirect('auth/login');
    }
}
