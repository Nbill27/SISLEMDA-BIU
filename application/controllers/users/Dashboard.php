<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('admin_m');
        $this->load->model('User_m');
        if (!$this->session->userdata('logged_in') || $this->session->userdata('active_role') == 'admin') {
            redirect('auth/login');
        }
        // Load necessary models, libraries, etc. here
    }

    public function index()
    {
        // Default method for Dashboard controller
        $data = [];
        $data['title'] = 'Dashboard User';
        $data['nama'] = $this->session->userdata('name');
        $data['content_view'] = 'user/dashboard/index.php';
        $data['active_role'] = $this->session->userdata('active_role');
        $data['roles'] = $this->User_m->get_user_roles($this->session->userdata('id_user'));
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_user', $data);
        $this->load->view('template/footer', $data);
    }
}
