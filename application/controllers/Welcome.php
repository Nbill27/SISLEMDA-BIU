<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('Mrental');
		$this->load->helper('cookie');  // Load helper cookie
	}

	public function index()
	{
		$data = [];

		// Cek cookie remember_me
		$remember = get_cookie('remember_me');
		if ($remember) {
			$decoded = base64_decode($remember);
			$parts = explode('|', $decoded);
			if (count($parts) == 2) {
				$data['username'] = $parts[0];
				$data['password'] = $parts[1];
				$data['remember'] = true;
			}
		}

		$this->load->view('vlogin', $data);
	}

	// untuk login
	function login()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$remember = $this->input->post('remember'); // checkbox remember

		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		if ($this->form_validation->run() != false) {
			$where = array(
				'admin_username' => $username,
				'admin_password' => md5($password)
			);

			$data = $this->Mrental->edit_data($where, 'admin');
			$d = $data->row();

			$cek = $data->num_rows();
			if ($cek > 0) {
				$session = array(
					'id' => $d->admin_id,
					'nama' => $d->admin_nama,
					'status' => 'login'
				);

				$this->session->set_userdata($session);

				if ($remember) {
					// Set cookie selama 7 hari
					$cookie = array(
						'name'   => 'remember_me',
						'value'  => base64_encode($username . '|' . $password),
						'expire' => 86400 * 7,
						'secure' => false
					);
					$this->input->set_cookie($cookie);
				} else {
					// Hapus cookie jika tidak dicentang
					delete_cookie('remember_me');
				}

				redirect('admin');
			} else {
				redirect(base_url() . 'welcome?pesan=gagal');
			}
		} else {
			$this->load->view('vlogin');
		}
	}

	public function logout()
	{
		// Hapus session admin_username
		$this->session->unset_userdata('admin_username');

		// Hancurkan semua session (optional tapi direkomendasikan)
		$this->session->sess_destroy();

		// Jika ada cookie remember, hapus juga (jika kamu pakai)
		delete_cookie('admin_username');
		delete_cookie('admin_password'); // tapi jangan simpan password asli di cookie ya, kurang aman

		// Redirect ke halaman login dengan pesan logout
		redirect(base_url('welcome/login?pesan=logout'));
	}


	// untuk register
	public function register()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('nama', 'Nama', 'required|trim');
		$this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[admin.admin_username]', [
			'is_unique' => 'Username sudah digunakan!'
		]);
		$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[5]', [
			'min_length' => 'Password minimal 5 karakter!'
		]);

		if ($this->form_validation->run() == FALSE) {
			$this->load->view('vregister');
		} else {
			$data = [
				'admin_nama' => $this->input->post('nama', TRUE),
				'admin_username' => $this->input->post('username', TRUE),
				'admin_password' => md5($this->input->post('password', TRUE))
			];

			$this->db->insert('admin', $data);
			$this->session->set_flashdata('pesan', 'Akun berhasil didaftarkan. Silakan login.');
			redirect('welcome/register');
		}
	}

	// untuk lupa password
	public function forget_password()
	{
		$this->load->library('form_validation');
		$this->load->helper('string');

		$this->form_validation->set_rules('username', 'Username', 'required|trim');

		if ($this->form_validation->run() == FALSE) {
			$this->load->view('vforget_password');
		} else {
			$username = $this->input->post('username', TRUE);

			$admin = $this->db->get_where('admin', ['admin_username' => $username])->row();

			if (!$admin) {
				$this->session->set_flashdata('pesan', 'Username tidak ditemukan.');
				$this->session->set_flashdata('status', 'danger');
				redirect('welcome/forget_password');
			} else {
				$token = bin2hex(random_bytes(16));
				$expired = date('Y-m-d H:i:s', strtotime('+1 hour'));

				$this->db->where('admin_username', $username);
				$this->db->update('admin', [
					'token' => $token,
					'expired_token' => $expired
				]);

				$data['reset_link'] = base_url("welcome/reset_password/$token");
				$this->load->view('vforget_password', $data);
			}
		}
	}

	// untuk reset password
	public function reset_password($token = null)
	{
		if (!$token) {
			redirect('welcome/login');
		}

		$this->load->library('form_validation');
		$this->form_validation->set_rules('password', 'Password Baru', 'required|trim|min_length[5]');
		$this->form_validation->set_rules('passconf', 'Konfirmasi Password', 'required|trim|matches[password]');

		$admin = $this->db->get_where('admin', ['token' => $token])->row();

		if (!$admin) {
			$this->session->set_flashdata('pesan', 'Token tidak valid.');
			$this->session->set_flashdata('status', 'danger');
			redirect('welcome/forget_password');
		}

		if (strtotime($admin->expired_token) < time()) {
			$this->session->set_flashdata('pesan', 'Token sudah kedaluwarsa.');
			$this->session->set_flashdata('status', 'danger');
			redirect('welcome/forget_password');
		}

		if ($this->form_validation->run() == FALSE) {
			$data['token'] = $token;
			$this->load->view('vreset_password', $data);
		} else {
			$password_baru = $this->input->post('password', TRUE);
			$password_hash = md5($password_baru);

			$this->db->where('admin_id', $admin->admin_id);
			$this->db->update('admin', [
				'admin_password' => $password_hash,
				'token' => null,
				'expired_token' => null
			]);

			$this->session->set_flashdata('pesan_pw', 'Password berhasil diubah. Silakan login.');
			$this->session->set_flashdata('status', 'success');
			redirect('welcome/login');
		}
	}
}
