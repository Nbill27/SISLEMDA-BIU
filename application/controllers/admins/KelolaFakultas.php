
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kelolafakultas extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(['session', 'form_validation']);
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
        $data['title'] = 'Kelola Fakultas';
        $data['user_name'] = $this->session->userdata('name');
        $data['fakultas'] = $this->admin_m->get_all_fakultas();
        $data['content_view'] = 'admin/kelola_fakultas/index';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_admin', $data);
        $this->load->view('template/footer', $data);
    }

    public function add_fakultas()
    {
        $data = [];
        $data['title'] = 'Tambah Fakultas';
        $data['user_name'] = $this->session->userdata('name');

        $this->form_validation->set_rules('nama_fakultas', 'Nama Fakultas', 'required|trim', [
            'required' => 'Nama fakultas harus diisi!'
        ]);

        if ($this->form_validation->run() == FALSE) {
            $data['content_view'] = 'admin/kelola_fakultas/add_fakultas';
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar_admin', $data);
            $this->load->view('template/footer', $data);
        } else {
            $nama_fakultas = $this->input->post('nama_fakultas');
            if ($this->admin_m->check_fakultas_exists($nama_fakultas)) {
                $this->session->set_flashdata('error', 'Nama fakultas sudah ada!');
                redirect('admins/kelolafakultas');
            }
            $data_fakultas = ['nama_fakultas' => $nama_fakultas];
            $this->admin_m->add_fakultas($data_fakultas);
            $this->session->set_flashdata('success', 'Fakultas berhasil ditambahkan');
            redirect('admins/kelolafakultas');
        }
    }

    public function edit_fakultas($id_fakultas = null)
    {
        if (!$id_fakultas) {
            $this->session->set_flashdata('error', 'ID Fakultas tidak valid');
            redirect('admins/kelolafakultas');
        }

        $data = [];
        $data['title'] = 'Edit Fakultas';
        $data['user_name'] = $this->session->userdata('name');
        $data['fakultas'] = $this->admin_m->get_fakultas_by_id($id_fakultas);

        if (!$data['fakultas']) {
            $this->session->set_flashdata('error', 'Fakultas tidak ditemukan');
            redirect('admins/kelolafakultas');
        }

        $this->form_validation->set_rules('nama_fakultas', 'Nama Fakultas', 'required|trim', [
            'required' => 'Nama fakultas harus diisi!'
        ]);

        if ($this->form_validation->run() == FALSE) {
            $data['content_view'] = 'admin/kelola_fakultas/edit_fakultas';
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar_admin', $data);
            $this->load->view('template/footer', $data);
        } else {
            $nama_fakultas = $this->input->post('nama_fakultas');
            if ($this->admin_m->check_fakultas_exists($nama_fakultas, $id_fakultas)) {
                $this->session->set_flashdata('error', 'Nama fakultas sudah ada!');
                redirect('admins/kelolafakultas');
            }
            $data_fakultas = ['nama_fakultas' => $nama_fakultas];
            $this->admin_m->update_fakultas($id_fakultas, $data_fakultas);
            $this->session->set_flashdata('success', 'Fakultas berhasil diperbarui');
            redirect('admins/kelolafakultas');
        }
    }

    public function delete_fakultas($id_fakultas = null)
    {
        if (!$id_fakultas) {
            $this->session->set_flashdata('error', 'ID Fakultas tidak valid');
            redirect('admins/kelolafakultas');
        }

        $fakultas = $this->admin_m->get_fakultas_by_id($id_fakultas);
        if ($fakultas) {
            $this->admin_m->delete_fakultas($id_fakultas);
            $this->session->set_flashdata('success', 'Fakultas berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Fakultas tidak ditemukan');
        }
        redirect('admins/kelolafakultas');
    }

    public function check_unique_fakultas($nama_fakultas, $id_fakultas)
    {
        $existing = $this->admin_m->check_fakultas_exists($nama_fakultas, $id_fakultas);
        if ($existing) {
            $this->form_validation->set_message('check_unique_fakultas', 'Nama fakultas sudah ada!');
            return FALSE;
        }
        return TRUE;
    }
}
?>
