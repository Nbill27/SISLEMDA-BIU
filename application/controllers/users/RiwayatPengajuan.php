<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RiwayatPengajuan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Riwayat_m');
        $this->load->model('User_m');

        if (!class_exists('Dompdf\Dompdf')) {
            $this->load->library('Dompdf_lib');
        }

        $data['roles'] = $this->User_m->get_user_roles($this->session->userdata('id_user'));
        $data['active_role'] = $this->session->userdata('active_role');
    }


    public function index()
    {
        $data = [];
        $data['active_role'] = $this->session->userdata('active_role');
        $data['roles'] = $this->User_m->get_user_roles($this->session->userdata('id_user'));
        $data['title'] = 'Riwayat Pengajuan';
        $data['nama'] = $this->session->userdata('name');
        $user_id = $this->session->userdata('id_user');
        $data['riwayat_data'] = $this->Riwayat_m->get_riwayat_pengajuan($user_id);
        // $this->_load_notification_data($data);
        $data['content_view'] = 'user/riwayat_pengajuan/index.php';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_user', $data);
        $this->load->view('template/footer', $data);
    }

    public function detail_pengajuan($id)
    {
        $data['title'] = 'Detail Arsip Pengajuan';
        $data['nama'] = $this->session->userdata('nama');
        $data['roles'] = $this->User_m->get_user_roles($this->session->userdata('id_user'));
        $data['active_role'] = $this->session->userdata('active_role');
        $data['nama'] = $this->session->userdata('name');
        // Ambil data utama + lampiran + disposisi
        $data['arsip'] = $this->Riwayat_m->get_detail_pengajuan_by_id($id);

        // Tambahkan URL file disposisi jika ada
        $file_path = FCPATH . 'uploads/disposisi/disposisi_' . $id . '.pdf';
        if (file_exists($file_path)) {
            $data['file_disposisi'] = base_url('uploads/disposisi/disposisi_' . $id . '.pdf');
        } else {
            $data['file_disposisi'] = null;
        }

        $data['content_view'] = 'user/riwayat_pengajuan/detail_pengajuan.php';
        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar_user', $data);
        $this->load->view('template/footer', $data);
    }

    public function unduh_riwayat($id_pengajuan)
    {
        // Ambil data utama
        $data['row'] = $this->Riwayat_m->get_pengajuan_detail($id_pengajuan);

        if (empty($data['row'])) {
            show_404();
            return;
        }

        // Hitung jumlah revisi
        $this->db->from('disposisi');
        $this->db->where('id_pengajuan', $id_pengajuan);
        $this->db->where('status_disposisi', 'revisi');
        $jumlah_revisi = $this->db->count_all_results();


        $data['jumlah_revisi'] = $jumlah_revisi;
        $data['riwayat_disposisi'] = $this->Riwayat_m->get_riwayat_disposisi($id_pengajuan);

        // Render view HTML
        $html = $this->load->view('user/riwayat_pengajuan/pdf_riwayat', $data, true);

        // Generate PDF
        if (class_exists('Dompdf\Dompdf')) {
            $dompdf = new Dompdf\Dompdf();
            $options = new Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            $dompdf->setOptions($options);

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream("Lembar_Kendali_Disposisi_{$id_pengajuan}.pdf", ["Attachment" => false]);
        } else {
            $this->dompdf_lib->createPdf(
                $html,
                "Lembar_Kendali_Disposisi_{$id_pengajuan}.pdf",
                'A4',
                'portrait',
                true
            );
        }
        exit();
    }
}
